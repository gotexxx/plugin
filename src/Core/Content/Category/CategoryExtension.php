<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Category;

use Alpha\Popup\Core\Content\Popup\Aggregate\PopupCategory\PopupCategoryDefinition;
use Alpha\Popup\Core\Content\Popup\PopupDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CategoryExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'alpha_popups',
                PopupDefinition::class,
                PopupCategoryDefinition::class,
                'category_id',
                'popup_id'
            ))->addFlags(new Inherited())
        );
    }

    public function getDefinitionClass(): string
    {
        return CategoryDefinition::class;
    }
}
