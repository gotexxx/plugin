<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup\Aggregate\PopupOrderRule;

use Alpha\Popup\Core\Content\Popup\PopupDefinition;
use Shopware\Core\Content\Rule\RuleDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;

class PopupOrderRuleDefinition extends MappingEntityDefinition
{
    final public const ENTITY_NAME = 'alpha_popup_order_rule';

    /**
     * This class is used as m:n relation between popups and order rules.
     * It gives the option to assign what rules may be used for order conditions.
     */
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('popup_id', 'popupId', PopupDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('rule_id', 'ruleId', RuleDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('popup', 'popup_id', PopupDefinition::class, 'id'),
            new ManyToOneAssociationField('rule', 'rule_id', RuleDefinition::class, 'id'),
        ]);
    }
}
