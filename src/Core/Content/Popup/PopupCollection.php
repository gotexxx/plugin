<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup;

use Shopware\Core\Checkout\Cart\Rule\CartRuleScope;
use Shopware\Core\Checkout\Cart\CartRuleLoader;
use Shopware\Core\Checkout\CheckoutRuleScope;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

/**
 * @method void              add(PopupEntity $entity)
 * @method void              set(string $key, PopupEntity $entity)
 * @method PopupEntity[]    getIterator()
 * @method PopupEntity[]    getElements()
 * @method PopupEntity|null get(string $key)
 * @method PopupEntity|null first()
 * @method PopupEntity|null last()
 */
class PopupCollection extends EntityCollection
{
    public function filterRules(SalesChannelContext $salesChannelContext, ): PopupCollection
    {
        return $this->filter(
            function (PopupEntity $popup) use ($salesChannelContext) {
                $addToPopupCollection = true;
                $rule = $popup->getPreconditionRule();

                if(!empty($rule->getRules())) {
                    $addToPopupCollection = $rule->match(new CheckoutRuleScope($salesChannelContext));
                }

                return $addToPopupCollection;
            }
        );
    }

    /**
     * Filters the cart rules based on the given sales channel context and cart rule loader.
     *
     * @param SalesChannelContext $salesChannelContext The sales channel context.
     * @param CartRuleLoader $cartRuleLoader The cart rule loader.
     * @return PopupCollection The filtered popup collection.
     */
    public function filterCartRules(SalesChannelContext $salesChannelContext, CartRuleLoader $cartRuleLoader): PopupCollection
    {
        $cartRuleLoader = $cartRuleLoader->loadByToken($salesChannelContext, $salesChannelContext->getToken());
        $cart= $cartRuleLoader->getCart();

        return $this->filter(
            function (PopupEntity $popup) use ($salesChannelContext, $cart) {
                $addToPopupCollectionCart = true;
                $rule = $popup->getPreconditionRule();

                if(!empty($rule->getRules())) {
                    $addToPopupCollectionCart = $rule->match(new CartRuleScope($cart, $salesChannelContext));
                }


                return $addToPopupCollectionCart;
            }
        );
    }


    protected function getExpectedClass(): string
    {
        return PopupEntity::class;
    }
}
