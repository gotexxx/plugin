<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup\Aggregate\PopupSalesChannel;

use Alpha\Popup\Core\Content\Popup\PopupDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

class PopupSalesChannelDefinition extends MappingEntityDefinition
{
    final public const ENTITY_NAME = 'alpha_popup_sales_channel';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return PopupSalesChannelEntity::class;
    }

    public function getCollectionClass(): string
    {
        return PopupSalesChannelCollection::class;
    }

    protected function getParentDefinitionClass(): ?string
    {
        return PopupDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
             // PK
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            // FKs
            (new FkField('popup_id', 'popupId', PopupDefinition::class))->addFlags(new Required()),
            (new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class))->addFlags(new Required()),
            // FIELDS
            (new IntField('priority', 'priority'))->addFlags(new Required()),
            // ASSOCIATIONS
            new ManyToOneAssociationField('popup', 'popup_id', PopupDefinition::class, 'id', false),
            new ManyToOneAssociationField('salesChannel', 'sales_channel_id', SalesChannelDefinition::class, 'id', false),
        ]);
    }
}
