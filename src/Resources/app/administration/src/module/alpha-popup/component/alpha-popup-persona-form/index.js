import template from './alpha-popup-persona-form.html.twig';
import './alpha-popup-persona-form.scss';
import PersonaCustomerGridService from '../../service/persona-customer-grid.service';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors, mapState } = Shopware.Component.getComponentHelper();

Component.register('alpha-popup-persona-form', {
    template,
    inject: ['repositoryFactory'],

    data() {
        return {
            customerService: null,
            customerPersonaRepository: null,
            removeButtonDisabled: true,
            // PAGINATION
            gridCustomersPageDataSource: [],
            gridCustomersPageNr: 1,
            gridCustomersPageLimit: 10,
            customerModel: null
        };
    },
    watch: {
        popup() {
            if (!this.popup || !this.popup.personaCustomers ) {
                return [];
            }
            // as soon as our popup has a value
            // we load our real data (async handling)
            this.createdComponent();
        }
    },

    computed: {

        ...mapState('alphaPopupDetail', [
            'popup',
        ]),

        ...mapPropertyErrors('popup', ['tags']),

        hasPopupPersonaRulesLoaded() {
            if (!this.popup || !this.popup.personaRules ) {
                return false;
            }
            return true;
        },

        personaRules() {
            if (!this.popup || !this.popup.personaRules ) {
                return false;
            }
            return this.popup.personaRules;
        },

        ruleFilter() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.multi('AND', [
                Criteria.not('AND', [Criteria.equalsAny('conditions.type', ['cartCartAmount'])]),
                Criteria.equalsAny('conditions.type', [
                    'customerBillingCountry', 'customerBillingStreet', 'customerBillingZipCode', 'customerIsNewCustomer',
                    'customerCustomerGroup', 'customerCustomerNumber', 'customerDaysSinceLastOrder',
                    'customerDifferentAddresses', 'customerLastName', 'customerOrderCount', 'customerShippingCountry',
                    'customerShippingStreet', 'customerShippingZipCode'
                ])
            ]));

            return criteria;
        },

        gridCustomersColumns() {
            if (this.customerService === null) {
                return [];
            }

            return this.customerService.getColumns();
        },

        gridCustomersTotalCount() {
            if (this.customerService === null) {
                return 0;
            }

            return this.customerService.getTotalCount();
        },

        gridCustomersItems() {
            return this.gridCustomersPageDataSource;
        },

        isRemoveButtonDisabled() {
            return this.removeButtonDisabled;
        },

        customerCriteria() {
            return new Criteria();
        },

        isEditingDisabled() {
            return false;
        }
    },

    created() {
        if (!this.popup || !this.popup.personaCustomers ) {
            return [];
        }
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            // fetch our popup which is necessary
            // to create our many-to-many repository fro the customer persona
            // inside the "then" function.
            // we create our actual repository for the
            // popup-customer many-to-many
            this.customerPersonaRepository = this.repositoryFactory.create(
                this.popup.personaCustomers.entity,
                this.popup.personaCustomers.source
            );

            // create our customer grid object
            // that handles logic for the grid and persona customer assignments
            this.customerService = new PersonaCustomerGridService(
                this,
                this.repositoryFactory.create('customer'),
                this.customerPersonaRepository,
                Shopware.Context.api
            );

            this.customerService.reloadCustomers().then(() => {
                this.refreshGridDataSource();
                this.updateStateVariables();
            });
        },

        // -------------------------------------------------------------------------------------------------------
        // CUSTOMER GRID EVENTS
        // -------------------------------------------------------------------------------------------------------
        // adds the provided customer to the
        // persona list and updates the grid
        onAddCustomer(id, item) {
            // somehow also null is being passed on
            // "all the time". to avoid circular references and
            // exceeding call stacks, we check for null
            if (item == null) {
                return;
            }

            this.customerService.addCustomer(item.id, Shopware.Context.api).then(() => {
                // remove from vue search field
                // and make it empty for the next searches
                this.$refs.selectCustomerSearch.clearSelection();
                // also refresh our grid
                this.refreshGridDataSource();
                this.updateStateVariables();
            });
        },

        // removes an assigned customer from the
        // persona list and updates the grid
        onRemoveCustomer(customer) {
            this.customerService.removeCustomer(customer).then(() => {
                // refresh our grid
                this.refreshGridDataSource();
                this.updateStateVariables();
            });
        },

        onRemoveSelectedCustomers() {
            const promiseList = [];

            // remove all our selected customers from our grid
            const selection = this.$refs.gridCustomers.selection;
            Object.values(selection).forEach(customer => {
                promiseList.push(this.customerService.removeCustomer(customer));
            });

            Promise.all(promiseList).then(() => {
                this.refreshGridDataSource();
                this.updateStateVariables();
            });
        },
        onCustomerPageChange(data) {
            // assign new pagination status data
            this.gridCustomersPageNr = data.page;
            this.gridCustomersPageLimit = data.limit;

            this.refreshGridDataSource();
            this.updateStateVariables();
        },
        onGridSelectionChanged(selection, selectionCount) {
            // enable our button if rows have been selected.
            // disable our delete button if nothing has been selected
            this.removeButtonDisabled = selectionCount <= 0;
        },
        refreshGridDataSource() {
            this.gridCustomersPageDataSource = this.customerService.getPageDataSource(
                this.gridCustomersPageNr,
                this.gridCustomersPageLimit
            );

            // if we have no data on the current page
            // but still a total count, then this means
            // that we are on a page that has been removed due to removing some customers.
            // so just try to reduce the page and refresh again
            if (this.gridCustomersTotalCount > 0 && this.gridCustomersPageDataSource.length <= 0) {
                // decrease, but stick with minimum of 1
                this.gridCustomersPageNr = (this.gridCustomersPageNr === 1) ? 1 : this.gridCustomersPageNr -= 1;
                this.refreshGridDataSource();
            }
        },
        updateStateVariables() {
            // assign our data to our popup state.
            // this one will be saved later on
            Shopware.State.commit('alphaPopupDetail/setPersonaCustomerIdsAdd', this.customerService.getCustomerIdsToAdd());
            Shopware.State.commit(
                'alphaPopupDetail/setPersonaCustomerIdsDelete',
                this.customerService.getCustomerIdsToDelete()
            );
        }
    }
});
