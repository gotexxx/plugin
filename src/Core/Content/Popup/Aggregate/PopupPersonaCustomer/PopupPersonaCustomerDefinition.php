<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup\Aggregate\PopupPersonaCustomer;

use Alpha\Popup\Core\Content\Popup\PopupDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;

class PopupPersonaCustomerDefinition extends MappingEntityDefinition
{
    final public const ENTITY_NAME = 'alpha_popup_persona_customer';

    /**
     * This class is used as m:n relation between popups and customers.
     * It gives the option to assign what customers may use this
     * popup based on a whitelist algorithm.
     */
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('popup_id', 'popupId', PopupDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('customer_id', 'customerId', CustomerDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('popup', 'popup_id', PopupDefinition::class, 'id'),
            new ManyToOneAssociationField('customer', 'customer_id', CustomerDefinition::class, 'id'),
        ]);
    }
}
