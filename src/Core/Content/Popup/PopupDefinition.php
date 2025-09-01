<?php declare(strict_types=1);

namespace Alpha\Popup\Core\Content\Popup;

use Alpha\Popup\Core\Content\Popup\Aggregate\PopupCategory\PopupCategoryDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupCartRule\PopupCartRuleDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupOrderRule\PopupOrderRuleDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupPersonaCustomer\PopupPersonaCustomerDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupPersonaRule\PopupPersonaRuleDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupProduct\PopupProductDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupProductStream\PopupProductStreamDefinition;
use Alpha\Popup\Core\Content\Popup\Aggregate\PopupSalesChannel\PopupSalesChannelDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Content\Category\CategoryDefinition;
use Shopware\Core\Content\Cms\CmsPageDefinition;
use Shopware\Core\Content\Product\Aggregate\ProductCategory\ProductCategoryDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\ProductStream\ProductStreamDefinition;
use Shopware\Core\Content\Rule\RuleDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ReverseInherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class PopupDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'alpha_popup';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return PopupEntity::class;
    }

    public function getCollectionClass(): string
    {
        return PopupCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new FkField('cms_page_id', 'cmsPageId', CmsPageDefinition::class)),
            new BoolField('active', 'active'),
            new BoolField('navigation_active', 'navigationActive'),
            new BoolField('product_active', 'productActive'),
            new BoolField('checkout_active', 'checkoutActive'),
            new BoolField('checkout_finish_active', 'checkoutFinishActive'),
            new BoolField('checkout_confirm_active', 'checkoutConfirmActive'),
            new BoolField('register_active', 'registerActive'),
            new BoolField('login_active', 'loginActive'),
            new IntField('delay_show_time', 'delayShowTime'),
            new IntField('scroll_activation_percentage', 'scrollActivationPercentage'),
            new BoolField('customer_restriction', 'customerRestriction'),
            new FloatField('cookie_expire_days', 'cookieExpireDays'),
            new StringField('popup_width', 'popupWidth'),
            new StringField('popup_max_width', 'popupMaxWidth'),
            new StringField('popup_width_individual', 'popupWidthIndividual'),
            new StringField('js_events','jsEvents'),
            new StringField('element_selectors','elementSelectors'),
            new DateTimeField('valid_from', 'validFrom'),
            new DateTimeField('valid_until', 'validUntil'),
            new ManyToOneAssociationField('cmsPage', 'cms_page_id', CmsPageDefinition::class),
            (new OneToManyAssociationField('salesChannels', PopupSalesChannelDefinition::class, 'popup_id', 'id'))->addFlags(new CascadeDelete()),
            (new ManyToManyAssociationField('categories', CategoryDefinition::class, PopupCategoryDefinition::class, 'popup_id', 'category_id'))->addFlags(new CascadeDelete()),
            (new ManyToManyAssociationField('personaRules', RuleDefinition::class, PopupPersonaRuleDefinition::class, 'popup_id', 'rule_id'))->addFlags(new CascadeDelete()),
            (new ManyToManyAssociationField('personaCustomers', CustomerDefinition::class, PopupPersonaCustomerDefinition::class, 'popup_id', 'customer_id'))->addFlags(new CascadeDelete()),
            (new ManyToManyAssociationField('orderRules', RuleDefinition::class, PopupOrderRuleDefinition::class, 'popup_id', 'rule_id'))->addFlags(new CascadeDelete()),
            (new ManyToManyAssociationField('products', ProductDefinition::class, PopupProductDefinition::class, 'popup_id', 'product_id'))->addFlags(new CascadeDelete()),
            (new ManyToManyAssociationField('productStreams',ProductStreamDefinition::class,PopupProductStreamDefinition::class,'popup_id','product_stream_id'))->addFlags(new CascadeDelete()),
            (new ManyToManyAssociationField('cartRules', RuleDefinition::class, PopupCartRuleDefinition::class, 'popup_id', 'rule_id'))->addFlags(new CascadeDelete()),
        ]);
    }
}
