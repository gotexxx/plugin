<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Product;

use Alpha\Popup\Core\Content\Popup\Aggregate\PopupCategory\PopupCategoryDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupProduct\PopupProductDefinition;
use Alpha\Popup\Core\Content\Popup\PopupDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class ProductExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'alpha_popups',
                PopupDefinition::class,
                PopupProductDefinition::class,
                'product_id',
                'popup_id'
            ))->addFlags(new Inherited())
        );
    }

    public function getDefinitionClass(): string
    {
        return ProductDefinition::class;
    }
}
