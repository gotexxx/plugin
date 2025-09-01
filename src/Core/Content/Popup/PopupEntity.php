<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup;

use Alpha\Popup\Core\Content\Popup\Aggregate\PopupCategory\PopupCategoryDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupSalesChannel\PopupSalesChannelCollection;
use Shopware\Core\Checkout\Cart\Rule\LineItemGroupRule;
use Shopware\Core\Checkout\Customer\CustomerCollection;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\Rule\CustomerNumberRule;
use Shopware\Core\Content\Category\CategoryCollection;
use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Content\ProductStream\ProductStreamCollection;
use Shopware\Core\Content\Rule\RuleCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Framework\Rule\Container\AndRule;
use Shopware\Core\Framework\Rule\Container\OrRule;
use Shopware\Core\Framework\Rule\Rule;

class PopupEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var bool
     */
    protected $customerRestriction;

    /**
     * @var float
     */
    protected $cookieExpireDays;

    /**
     * @var string
     */
    protected $popupWidthIndividual;

    /**
     * @var string
     */
    protected $popupMaxWidth;

    /**
     * @var string
     */
    protected $popupWidth;

    /**
     * @var \DateTimeInterface|null
     */
    protected $validFrom;

    /**
     * @var \DateTimeInterface|null
     */
    protected $validUntil;

    /**
     * @var PopupSalesChannelCollection|null
     */
    protected $salesChannel;

    /**
     * @var ProductCollection|null
     */
    protected $products;

    /**
     * @var RuleCollection|null
     */
    protected $orderRules;

    /**
     * @var RuleCollection|null
     */
    protected $cartRules;

    /**
     * @var RuleCollection|null
     */
    protected $personaRules;

    /**
     * @var CustomerCollection|null
     */
    protected $personaCustomers;

    /**
     * @var CategoryCollection|null
     */
    protected $categories;


    /**
     * @var CmsPageEntity
     */
    protected $cmsPage;

    /**
     * @var string
     */
    protected $jsEvents;
    /**
     * @var string
     */
    protected $elementSelectors;
    /**
     * @var ProductStreamCollection
     */
    protected $productStreams;


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function isProductActive()
    {
        return $this->productActive;
    }

    public function seProducttActive(bool $productActive)
    {
        $this->productActive = $productActive;
    }


    /**
     * @return bool
     */
    public function isCustomerRestriction()
    {
        return $this->customerRestriction;
    }

    public function setCustomerRestriction(bool $customerRestriction)
    {
        $this->customerRestriction = $customerRestriction;
    }

    /**
     * @return float
     */
    public function getCookieExpireDays()
    {
        return $this->cookieExpireDays;
    }

    public function setCookieExpireDays(float $cookieExpireDays)
    {
        $this->cookieExpireDays = $cookieExpireDays;
    }

    /**
     * @return string
     */
    public function getPopupWidth()
    {
        return $this->popupWidth;
    }

    public function setPopupWidth(string $popupWidth)
    {
        $this->popupWidth = $popupWidth;
    }

    /**
     * @return string
     */
    public function getPopupWidthIndividual(): string
    {
        if (!is_null($this->popupWidthIndividual)) {
            return $this->popupWidthIndividual;
        }
        return "";
    }

    public function setPopupWidthIndividual(string $popupWidthIndividual): void
    {
        $this->popupWidthIndividual = $popupWidthIndividual;
    }

    /**
     * @return string
     */
    public function getPopupMaxWidth()
    {
        return $this->popupMaxWidth ?? "";
    }

    public function setPopupMaxWidth(string $popupMaxWidth): void
    {
        $this->popupMaxWidth = $popupMaxWidth;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    public function setValidFrom(\DateTimeInterface $validFrom)
    {
        $this->validFrom = $validFrom;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    public function setValidUntil(\DateTimeInterface $validUntil)
    {
        $this->validUntil = $validUntil;
    }

    /**
     * @return PopupSalesChannelCollection|null
     */
    public function getSalesChannel()
    {
        return $this->salesChannel;
    }

    /**
     * @param PopupSalesChannelCollection $salesChannel
     */
    public function setSalesChannel(PopupSalesChannelCollection $salesChannel)
    {
        $this->salesChannel = $salesChannel;
    }


    /**
     * @return CmsPageEntity
     */
    public function getCmsPage()
    {
        return $this->cmsPage;
    }

    public function setCmsPage(CmsPageEntity $cmsPage)
    {
        $this->cmsPage = $cmsPage;
    }

    public function getOrderRules(): ?RuleCollection
    {
        return $this->orderRules;
    }

    public function setOrderRules(?RuleCollection $orderRules)
    {
        $this->orderRules = $orderRules;
    }

    public function getCartRules(): ?RuleCollection
    {
        return $this->cartRules;
    }

    public function setCartRules(?RuleCollection $cartRules)
    {
        $this->cartRules = $cartRules;
    }

    public function getPersonaRules(): ?RuleCollection
    {
        return $this->personaRules;
    }

    public function setPersonaRules(?RuleCollection $personaRules)
    {
        $this->personaRules = $personaRules;
    }

    public function getPersonaCustomers(): ?CustomerCollection
    {
        return $this->personaCustomers;
    }

    public function setPersonaCustomers(?CustomerCollection $personaCustomers)
    {
        $this->personaCustomers = $personaCustomers;
    }

    public function getProducts(): ?ProductCollection
    {
        return $this->products;
    }

    public function setProducts(?ProductCollection $products)
    {
        $this->products = $products;
    }

    public function getCategories(): ?CategoryCollection
    {
        return $this->categories;
    }

    public function setCategories(?CategoryCollection $categories)
    {
        $this->categories = $categories;
    }

    public function getJsEvents(): ?string
    {
        return $this->jsEvents;
    }

    public function setJsEvents(?string $jsEvents)
    {
        $this->jsEvents = $jsEvents;
    }

    public function getElementSelectors(): ?string
    {
        return $this->elementSelectors;
    }

    public function setElementSelectors(?string $elementSelectors)
    {
        $this->elementSelectors = $elementSelectors;
    }

    public function getProductStreams(): ?ProductStreamCollection
    {
        return $this->productStreams;
    }

    public function setProductStreams(?ProductStreamCollection $productStreams): void
    {
        $this->productStreams = $productStreams;
    }


    /**
     * Builds our aggregated precondition rule condition for this promotion.
     * If this rule matches within all its sub conditions, then the
     * whole promotion is allowed to be used.
     */
    public function getPreconditionRule(): Rule
    {
        // we combine each topics with an AND and a OR inside of their rules.
        // so all topics have to match, and one topic needs at least 1 rule that matches.
        $requirements = new AndRule(
            []
        );

        // first check if we either restrict our persona
        // with direct customer assignments or with persona rules
        if ($this->isCustomerRestriction()) {
            // we use assigned customers
            // check if we have customers.
            // if so, create customer rules for it and add that also as
            // a separate OR condition to our main persona rule
            if ($this->getPersonaCustomers() !== null) {
                $personaCustomerOR = new OrRule();

                /* @var CustomerEntity $ruleEntity */
                foreach ($this->getPersonaCustomers()->getElements() as $customer) {
                    // build our new rule for this
                    // customer and his/her customer number
                    $custRule = new CustomerNumberRule();
                    $custRule->assign(['numbers' => [$customer->getCustomerNumber()], 'operator' => CustomerNumberRule::OPERATOR_EQ]);

                    $personaCustomerOR->addRule($custRule);
                }

                // add the rule to our main rule
                $requirements->addRule($personaCustomerOR);
            }
        } else {
            // we use persona rules.
            // check if we have persona rules and add them
            // to our persona OR as a separate OR rule with all configured rules
            if ($this->getPersonaRules() !== null && count($this->getPersonaRules()->getElements()) > 0) {
                $personaRuleOR = new OrRule();

                foreach ($this->getPersonaRules()->getElements() as $ruleEntity) {
                    $personaRuleOR->addRule($ruleEntity->getPayload());
                }

                $requirements->addRule($personaRuleOR);
            }
        }
//

        if ($this->getCartRules() !== null && count($this->getCartRules()->getElements()) > 0) {
            $cartOR = new OrRule([]);

            foreach ($this->getCartRules()->getElements() as $ruleEntity) {
                $cartOR->addRule($ruleEntity->getPayload());
            }

            $requirements->addRule($cartOR);
        }

        // verify if we are in SetGroup mode and build
        // a custom setgroup rule for all groups
//        if ($this->isUseSetGroups() !== null && $this->isUseSetGroups() && $this->getSetgroups() !== null && $this->getSetgroups()->count() > 0) {
//            $groupsRootRule = new OrRule();
//
//            foreach ($this->getSetgroups() as $group) {
//                $groupRule = new LineItemGroupRule();
//                $groupRule->assign(
//                    [
//                        'groupId' => $group->getId(),
//                        'packagerKey' => $group->getPackagerKey(),
//                        'value' => $group->getValue(),
//                        'sorterKey' => $group->getSorterKey(),
//                        'rules' => $group->getSetGroupRules(),
//                    ]
//                );
//
//                $groupsRootRule->addRule($groupRule);
//            }
//
//            $requirements->addRule($groupsRootRule);
//        }

        if ($this->getOrderRules() !== null && count($this->getOrderRules()->getElements()) > 0) {
            $orderOR = new OrRule([]);

            foreach ($this->getOrderRules()->getElements() as $ruleEntity) {
                $payload = $ruleEntity->getPayload();


                $orderOR->addRule($payload);
            }

            $requirements->addRule($orderOR);
        }

        return $requirements;
    }
}
