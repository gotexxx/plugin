
import template from './alpha-popup-cart-condition-form.html.twig';
import './alpha-popup-cart-condition-form.scss';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors, mapState } = Shopware.Component.getComponentHelper();

Component.register('alpha-popup-cart-condition-form', {
    template,

    computed: {

        ...mapState('alphaPopupDetail', [
            'popup',
        ]),

        ...mapPropertyErrors('popup', ['tags']),

        hasPopupCartRulesLoaded() {
            if (!this.popup || !this.popup.cartRules ) {
                return false;
            }
            return true;
        },

        ruleFilter() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.multi('AND', [
                Criteria.equalsAny('conditions.type', [
                    'cartCartAmount','cartPositionPrice','cartLineItemOfType',
                    'cartLineItemTotalPrice','cartLineItemUnitPrice','cartWeight','cartVolume',
                    'cartHasDeliveryFreeItem', 'cartLineItemsInCartCount','cartLineItemTag','cartLineItemIsNew',
                    'cartLineItemDimensionWidth','cartLineItemDimensionHeight',
                    'cartLineItemDimensionLength','cartLineItemDimensionWeight',
                    'cartLineItemDimensionVolume','cartLineItemListPrice',
                    'cartLineItemStock','cartLineItemActualStock'
                ])
            ]));
            return criteria;
        },


        isEditingDisabled() {
            return false;
        }
    }
});
