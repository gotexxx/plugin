<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\ProductStream;

use Alpha\Popup\Core\Content\Popup\Aggregate\PopupProductStream\PopupProductStreamDefinition;
use Alpha\Popup\Core\Content\Popup\PopupDefinition;
use Shopware\Core\Content\ProductStream\ProductStreamDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ProductStreamExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'alpha_popups',
                PopupDefinition::class,
                PopupProductStreamDefinition::class,
                'product_stream_id',
                'popup_id'
            ))->addFlags(new Inherited())
        );
    }

    public function getDefinitionClass(): string
    {
        return ProductStreamDefinition::class;
    }
}
