<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup\Aggregate\PopupProduct;


use Alpha\Popup\Core\Content\Popup\PopupDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;

class PopupProductDefinition extends MappingEntityDefinition
{
    final public const ENTITY_NAME = 'alpha_popup_products';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function isVersionAware(): bool
    {
        return true;
    }

    public function getEntityClass(): string
    {
        return PopupProductEntity::class;
    }

    public function getCollectionClass(): string
    {
        return PopupProductCollection::class;
    }

    protected function getParentDefinitionClass(): ?string
    {
        return PopupDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            // FKs
            (new FkField('popup_id', 'popupId', PopupDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            // ASSOCIATIONS
            new ManyToOneAssociationField('popup', 'popup_id', PopupDefinition::class, 'id', false),
            new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id', false),
        ]);
    }
}
