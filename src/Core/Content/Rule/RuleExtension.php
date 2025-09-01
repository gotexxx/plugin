<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Rule;

use Alpha\Popup\Core\Content\Popup\Aggregate\PopupCartRule\PopupCartRuleDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupOrderRule\PopupOrderRuleDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupPersonaRule\PopupPersonaRuleDefinition;
use Alpha\Popup\Core\Content\Popup\PopupDefinition;
use Shopware\Core\Content\Rule\RuleDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class RuleExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'alpha_popups_persona',
                PopupDefinition::class,
                PopupPersonaRuleDefinition::class,
                'rule_id',
                'popup_id'
            ))->addFlags(new Inherited())
        );
        $collection->add(
            (new ManyToManyAssociationField(
                'alpha_popups_order',
                PopupDefinition::class,
                PopupOrderRuleDefinition::class,
                'rule_id',
                'popup_id'
            ))->addFlags(new Inherited())
        );
        $collection->add(
            (new ManyToManyAssociationField(
                'alpha_popup_cart_rule',
                PopupDefinition::class,
                PopupCartRuleDefinition::class,
                'rule_id',
                'popup_id'
            ))->addFlags(new Inherited())
        );
    }

    public function getDefinitionClass(): string
    {
        return RuleDefinition::class;
    }
}
