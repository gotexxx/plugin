
import template from './alpha-popup-order-condition-form.html.twig';
import './alpha-popup-order-condition-form.scss';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors, mapState } = Shopware.Component.getComponentHelper();

Component.register('alpha-popup-order-condition-form', {
    template,

    computed: {

        ...mapState('alphaPopupDetail', [
            'popup',
        ]),

        ...mapPropertyErrors('popup', ['tags']),

        hasPopupOrderRulesLoaded() {
            if (!this.popup || !this.popup.orderRules ) {
                return false;
            }
            return true;
        },

        ruleFilter() {
            const criteria = new Criteria();

            criteria.addFilter(Criteria.multi('AND', [
                Criteria.equalsAny('conditions.type', [
                    'customerOrderCount', 'customerDaysSinceLastOrder', 'customerBillingCountry','customerOrderTotalAmount',
                    'customerBillingStreet', 'customerShippingCountry',
                ]),
                Criteria.not('AND', [Criteria.equalsAny('conditions.type', ['cartCartAmount'])])
            ]));

            return criteria;
        },

        isEditingDisabled() {
            return false;
        }
    }
});
