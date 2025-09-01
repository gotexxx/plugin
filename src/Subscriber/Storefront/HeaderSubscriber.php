<?php

declare(strict_types=1);

namespace Alpha\Popup\Subscriber\Storefront;

use Alpha\Popup\Core\Content\Popup\PopupCollection;
use Alpha\Popup\Core\Content\Popup\PopupEntity;
use Shopware\Core\Checkout\Cart\CartRuleLoader;
use Shopware\Core\Checkout\Promotion\Gateway\Template\ActiveDateRange;
use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Content\Cms\Exception\PageNotFoundException;
use Shopware\Core\Content\Cms\SalesChannel\SalesChannelCmsPageLoaderInterface;
use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\PlatformRequest;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Pagelet\Header\HeaderPagelet;
use Shopware\Storefront\Pagelet\Header\HeaderPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;

class HeaderSubscriber implements EventSubscriberInterface
{
    /**
     * HeaderSubscriber constructor.
     */
    public function __construct(
        protected readonly EntityRepository                 $popupRepository,
        protected EntityRepository                          $productRepository,
        private readonly SalesChannelCmsPageLoaderInterface $cmsPageLoader,
        private readonly CartRuleLoader                     $cartRuleLoader,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            HeaderPageletLoadedEvent::class => 'addPopup',
        ];
    }

    /**
     * Adds a popup based on the controller name and action.
     *
     * @param HeaderPageletLoadedEvent $event the event object
     *
     * @throws InconsistentCriteriaIdsException if there are inconsistent criteria IDs
     */
    public function addPopup(HeaderPageletLoadedEvent $event): void
    {
        $request = $event->getRequest();
        /* @var SalesChannelContext $salesChannelContext */
        $salesChannelContext = $request->attributes->get(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_CONTEXT_OBJECT);
        $controllerPath = $request->attributes->get('_controller');

        if ($controllerPath) {
            preg_match('/\\\([^\\\]*)::/', (string)$controllerPath, $matches);
            if (!isset($matches[1])) {
                return;
            }
            $controllerName = $matches[1];

            $pattern = '/(?<=::)\w+$/';
            preg_match($pattern, $controllerPath, $matches);
            $action = '';
            if (!empty($matches)) {
                $action = $matches[0];
            }

            if (in_array($controllerName, [
                'NavigationController',
                'CheckoutController',
                'AuthController',
                'RegisterController',
                'ProductController',
            ])) {
                $this->createPageletController($event, $salesChannelContext, $request, $controllerName, $action);
            }
        }
    }

    /**
     * Creates a Peglet controller based on the given parameters.
     *
     * @param mixed $event the event
     * @param SalesChannelContext $salesChannelContext the sales channel context
     * @param Request $request the request
     * @param string $controllerName the name of the controller
     * @param string $action the action
     *
     * @throws InconsistentCriteriaIdsException
     */
    private function createPageletController($event, SalesChannelContext $salesChannelContext, Request $request, string $controllerName, string $action): void
    {
        $criteria = new Criteria();

        switch ($controllerName) {
            case 'NavigationController':
                // $criteria->addFilter(new EqualsFilter('navigationActive', true));

                $popups = $this->fetchPopups($event->getContext(), $salesChannelContext, $request, $event->getPagelet());

                $popups = $popups->filter(function ($popup) {
                    return false !== $popup->get('navigationActive');
                });

                break;

            case 'CheckoutController':
                $criteria->addFilter(new EqualsFilter('checkoutActive', true));
                if ('confirmPage' == $action) {
                    $criteria->addFilter(new EqualsFilter('checkoutConfirmActive', true));
                }
                if ('finishPage' == $action) {
                    $criteria->addFilter(new EqualsFilter('checkoutFinishActive', true));
                }
                $popups = $this->fetchPopups($event->getContext(), $salesChannelContext, $request, $event->getPagelet(), $criteria);

                break;

            case 'ProductController':
                $criteria->addFilter(new EqualsFilter('productActive', true));
                $criteria = $this->addProductIdFilter($criteria, $request->get('productId'), $event->getContext());

                $popups = $this->fetchPopups($event->getContext(), $salesChannelContext, $request, $event->getPagelet(), $criteria);
                break;

            case 'AuthController':
                $criteria->addFilter(new EqualsFilter('loginActive', true));
                $popups = $this->fetchPopups($event->getContext(), $salesChannelContext, $request, $event->getPagelet(), $criteria);
                break;

            case 'RegisterController':
                $criteria->addFilter(new EqualsFilter('registerActive', true));
                $popups = $this->fetchPopups($event->getContext(), $salesChannelContext, $request, $event->getPagelet(), $criteria);
                break;

            default:
                return;
        }

        //        $filteredPopups = $popups->filterRules($salesChannelContext);
        $filteredPopups = $popups->filterCartRules($salesChannelContext, $this->cartRuleLoader);
        $event->getPagelet()->addExtension('popups', $filteredPopups);
    }

    /**
     * @throws InconsistentCriteriaIdsException
     * @throws PageNotFoundException
     */
    private function fetchPopups(Context $context, SalesChannelContext $salesChannelContext, $request, HeaderPagelet $headerPagelet, $criteria = new Criteria()): PopupCollection
    {
        $criteria = $this->getCriteriaWithAssociations($criteria);

        $criteria->addFilter(new EqualsFilter('active', true));
        $criteria->addFilter(new ActiveDateRange());
        $criteria = $this->addSalesChannelFilter($criteria, $salesChannelContext);
        $criteria = $this->addCategoryFilter($criteria, $headerPagelet);

        /** @var PopupCollection $popupCollection * */
        $popupCollection = $this->popupRepository->search($criteria, $context)->getEntities();

        return $this->loadPages($popupCollection, $salesChannelContext, $request);
    }

    /**
     * @throws InconsistentCriteriaIdsException
     * @throws PageNotFoundException
     */
    private function loadPages(PopupCollection $popupCollection, SalesChannelContext $salesChannelContext, $request): PopupCollection
    {
        /** @var PopupEntity $popupEntity * */
        foreach ($popupCollection as $key => $popupEntity) {
            $page = $popupEntity->getCmsPage();
            if ($page) {
                $cmsPage = $this->load($page->getId(), $request, $salesChannelContext);
                $popupEntity->setCmsPage($cmsPage);
            } else {
                $popupCollection->remove($key);
            }
        }

        return $popupCollection;
    }

    /**
     * @throws InconsistentCriteriaIdsException
     * @throws PageNotFoundException
     */
    private function load(string $id, Request $request, SalesChannelContext $context, array $config = null): ?CmsPageEntity
    {
        $criteria = new Criteria([$id]);
        $pages = $this->cmsPageLoader->load($request, $criteria, $context, $config);

        if (!$pages->has($id)) {
            throw new PageNotFoundException($id);
        }

        return $pages->get($id);
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    private function getCriteriaWithAssociations(Criteria $criteria): ?Criteria
    {
        $criteria->addAssociation('cmsPage');
        $criteria->addAssociation('salesChannels');
        $criteria->addAssociation('categories');
        $criteria->addAssociation('personaCustomers');
        $criteria->addAssociation('personaRules');
        $criteria->addAssociation('orderRules');
        $criteria->addAssociation('products');

        $criteria->addAssociation('cartRules');

        return $criteria;
    }

    private function addCategoryFilter(Criteria $criteria, HeaderPagelet $headerPagelet): Criteria
    {
        $activeCategory = $headerPagelet->getNavigation()->getActive();
        $activeCategoryId = null;
        if ($activeCategory) {
            $activeCategoryId = $activeCategory->getId();
        }

        $criteria->addFilter(new MultiFilter(
            MultiFilter::CONNECTION_OR,
            [
                new EqualsFilter('categories.id', $activeCategoryId),
                new EqualsFilter('categories.id', null),
            ]
        ));

        return $criteria;
    }

    private function addSalesChannelFilter(Criteria $criteria, SalesChannelContext $salesChannelContext): Criteria
    {
        $criteria->addFilter(new MultiFilter(
            MultiFilter::CONNECTION_OR,
            [
                new EqualsFilter('salesChannels.salesChannelId', $salesChannelContext->getSalesChannel()->getId()),
                new EqualsFilter('salesChannels.salesChannelId', null),
            ]
        ));

        return $criteria;
    }

    private function addProductIdFilter($criteria, $productId, Context $context): Criteria
    {
        /** @var ProductCollection $result */
        $result = $this->productRepository->search(new Criteria([$productId]), $context);
        /** @var ProductEntity $product */
        $product = $result?->first();

        $parentId = $product?->getParentId();
        if ($parentId) {
            $productId = $parentId;
        }
        $criteria->addFilter(new MultiFilter(
            MultiFilter::CONNECTION_OR,
            [
                new EqualsFilter('products.id', $productId),
                new EqualsFilter('products.id', null),
            ]
        ));

        $streamIds = $product->getStreamIds();
        if ($streamIds) {
            $criteria->addFilter(new MultiFilter(
                MultiFilter::CONNECTION_OR,
                [
                    new EqualsAnyFilter('productStreams.id', $streamIds),
                    new EqualsFilter('productStreams.id', null),
                ]
            ));
        }
        return $criteria;
    }
}
