import template from './alpha-popup-detail.html.twig';
import alphaPopupDetailState from "./state";
import errorConfig from './error-config.json';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPageErrors } = Shopware.Component.getComponentHelper();

Component.register('alpha-popup-detail', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder'),
        Mixin.getByName('discard-detail-page-changes')('popup')
    ],

    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        ESCAPE: 'onCancel'
    },

    props: {
        popupId: {
            type: String,
            required: false,
            default: null
        }
    },

    data() {
        return {
            isSaveSuccessful: false,
            saveCallbacks: []
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier)
        };
    },

    computed: {
        identifier() {
            return this.placeholder(this.popup, 'name');
        },

        popupRepository() {
            return this.repositoryFactory.create('alpha_popup');
        },

        tooltipSave() {
            const systemKey = this.$device.getSystemKey();

            return {
                message: `${systemKey} + S`,
                appearance: 'light'
            };
        },
        tooltipCancel() {
            return {
                message: 'ESC',
                appearance: 'light'
            };
        },

        popup: {
            get() {
                return Shopware.State.get('alphaPopupDetail').popup;
            },
            set(popup) {
                Shopware.State.commit('alphaPopupDetail/setPopup', popup);
            }
        },

        isLoading: {
            get() {
                return Shopware.State.get('alphaPopupDetail').isLoading;
            },
            set(isLoading) {
                Shopware.State.commit('alphaPopupDetail/setIsLoading', isLoading);
            }
        },

        personaCustomerIdsAdd() {
            return Shopware.State.get('alphaPopupDetail').personaCustomerIdsAdd;
        },

        personaCustomerIdsDelete() {
            return Shopware.State.get('alphaPopupDetail').personaCustomerIdsDelete;
        },

        ...mapPageErrors(errorConfig)
    },

    beforeCreate() {
        Shopware.State.registerModule('alphaPopupDetail', alphaPopupDetailState);
    },

    created() {
        this.createdComponent();
    },

    beforeDestroy() {
        Shopware.State.unregisterModule('alphaPopupDetail');
    },

    watch: {
        popupId() {
            this.createdComponent();
        },
    },

    methods: {
        createdComponent() {
            this.isLoading = true;

            this.initState();
            this.$root.$on('popup-save-start', this.onShouldSave);
        },

        destroyedComponent() {
            this.$root.$off('popup-save-start', this.onShouldSave);
        },

        initState() {
            if (this.popupId) {
                return this.loadState();
            }
            this.popup = this.popupRepository.create(Shopware.Context.api);
            this.isLoading = false;
        },

        loadState() {
            return Promise.all([this.loadEntityData()]);
        },

        loadEntityData() {
            const criteria = new Criteria(1, 1);
            criteria.addAssociation('salesChannels');
            criteria.addAssociation('cmsPage');
            criteria.addAssociation('categories');
            criteria.addAssociation('personaRules');
            criteria.addAssociation('orderRules');

            this.popupRepository.get(this.popupId, Shopware.Context.api, criteria).then((popup) => {
                this.popup = popup;
                Shopware.State.commit('alphaPopupDetail/setPopup', this.popup);
                this.isLoading = false;
            });
        },

        abortOnLanguageChange() {
            if (this.popupRepository.hasChanges(this.popup)) {
                return true;
            }

            if (this.discounts !== null) {
                const discountRepository = this.repositoryFactory.create(
                    this.discounts.entity,
                    this.discounts.source
                );

                return this.discounts.some((discount) => {
                    return discount.isNew() || discountRepository.hasChanges(discount);
                });
            }

            return false;
        },

        saveOnLanguageChange() {
            return this.onSave();
        },

        onChangeLanguage() {
            this.loadEntityData();
        },

        onSave() {
            this.isLoading = true;
            if (!this.popupId) {
                return this.createPopup();
            }

            return this.savePopup();
        },

        onShouldSave() {
            this.onSave()
                .then(() => {
                    this.$root.$emit('popup-save-success');
                })
                .catch(() => {
                    this.$root.$emit('popup-save-error');
                });
        },

        createPopup() {
            return this.savePopup().then(() => {
                this.$router.push({ name: 'alpha.popup.detail', params: { id: this.popup.id } });
            });
        },

        async savePopup() {

             return this.savePopupAssociations().then(() => {
                return this.popupRepository.save(this.popup, Shopware.Context.api)
                    .then(() => {
                        this.isSaveSuccessful = true;
                        const criteria = new Criteria(1, 1);
                        criteria.addAssociation('salesChannels');
                        criteria.addAssociation('categories');

                        return this.popupRepository.get(
                            this.popup.id,
                            Shopware.Context.api, criteria
                        ).then((popup) => {
                            this.popup = popup;
                            this.isLoading = false;
                        });
                    })
                    .catch((error) => {
                        this.isLoading = false;
                        this.createNotificationError({
                            title: this.$tc('global.default.error'),
                            message: this.$tc(
                                'global.notification.notificationSaveErrorMessage',
                                0,
                                { entityName: this.popup.name }
                            )
                        });
                        throw error;
                    });
            });
        },


        async savePopupAssociations() {
            const customerPersonaRepository = this.repositoryFactory.create(
                this.popup.personaCustomers.entity,
                this.popup.personaCustomers.source
            );

            if (this.personaCustomerIdsDelete !== null) {
                await this.personaCustomerIdsDelete.forEach((customerId) => {
                    customerPersonaRepository.delete(customerId, Shopware.Context.api);
                });
            }

            if (this.personaCustomerIdsAdd !== null) {
                await this.personaCustomerIdsAdd.forEach((customerId) => {
                    customerPersonaRepository.assign(customerId, Shopware.Context.api);
                });
            }

            // reset our helper "delta" arrays
            Shopware.State.commit('alphaPopupDetail/setPersonaCustomerIdsAdd', []);
            Shopware.State.commit('alphaPopupDetail/setPersonaCustomerIdsDelete', []);
        },

        onCancel() {
            this.$router.push({ name: 'alpha.popup.overview' });
        }
    }
});
