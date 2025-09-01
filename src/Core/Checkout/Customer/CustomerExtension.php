<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Checkout\Customer;

use Alpha\Popup\Core\Content\Popup\Aggregate\PopupPersonaCustomer\PopupPersonaCustomerDefinition;
use Alpha\Popup\Core\Content\Popup\PopupDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CustomerExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'alpha_popups',
                PopupDefinition::class,
                PopupPersonaCustomerDefinition::class,
                'customer_id',
                'popup_id'
            ))->addFlags(new Inherited())
        );
    }

    public function getDefinitionClass(): string
    {
        return CustomerDefinition::class;
    }
}
