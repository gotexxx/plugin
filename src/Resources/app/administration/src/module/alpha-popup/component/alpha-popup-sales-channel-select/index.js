import template from './alpha-popup-sales-channel-select.html.twig';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors, mapState, mapGetters } = Shopware.Component.getComponentHelper();

Component.register('alpha-popup-sales-channel-select', {
    template,

    inject: ['repositoryFactory'],

    data() {
        return {
            salesChannels: []
        };
    },

    computed: {
        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },

        popupSalesChannelRepository() {
            if (this.popup) {
                return this.repositoryFactory.create(
                    this.popup.salesChannels.entity,
                    this.popup.salesChannels.source
                );
            }

            return null;
        },

        salesChannelIds: {
            get() {
                if (!this.popup || !this.popup.salesChannels) {
                    return [];
                }

                return this.popup.salesChannels.map((popupSalesChannels) => {
                    return popupSalesChannels.salesChannelId;
                });
            },

            set(salesChannelsIds) {
                salesChannelsIds = salesChannelsIds || [];
                const { deleted, added } = this.getChangeset(salesChannelsIds);
                if (this.popup.isNew()) {
                    this.handleLocalMode(deleted, added);
                    return;
                }

                this.handleWithRepository(deleted, added);
            }
        },

        ...mapState('alphaPopupDetail', [
            'popup',
        ]),

        ...mapPropertyErrors('popup', ['tags']),

    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.salesChannelRepository.search(new Criteria(), Shopware.Context.api).then((searchResult) => {
                this.salesChannels = searchResult;
            });
        },

        getChangeset(salesChannelsIds) {
            const deleted = [];
            const added = [];

            salesChannelsIds.forEach((id) => {
                if (!this.popup.salesChannels.find((salesChannel) => {
                    return salesChannel.salesChannelId === id;
                })) {
                    added.push(id);
                }
            });

            this.popup.salesChannels.forEach((salesChannel) => {
                if (!salesChannelsIds.includes(salesChannel.salesChannelId)) {
                    deleted.push(salesChannel.salesChannelId);
                }
            });

            return { deleted, added };
        },

        getAssociationBySalesChannelId(salesChannelId) {
            return this.popup.salesChannels.find((association) => {
                return association.salesChannelId === salesChannelId;
            });
        },

        handleLocalMode(deleted, added) {
            deleted.forEach((deletedId) => {
                const collectionEntry = this.getAssociationBySalesChannelId(deletedId);
                this.popup.salesChannels.remove(collectionEntry.id);
            });

            added.forEach((newId) => {
                const newAssociation = this.popupSalesChannelRepository.create(this.popup.salesChannels.context);

                newAssociation.salesChannelId = newId;
                newAssociation.popupId = this.popup.id;
                newAssociation.priority = 1;
                this.popup.salesChannels.add(newAssociation);
            });
        },

        handleWithRepository(deleted, added) {
            deleted.forEach((deletedId) => {
                const associationEntry = this.getAssociationBySalesChannelId(deletedId);
                this.popup.salesChannels.remove(associationEntry.id);
            });

            added.forEach((addedId) => {
                const newAssociation = this.popupSalesChannelRepository.create(this.popup.salesChannels.context);

                newAssociation.salesChannelId = addedId;
                newAssociation.popupId = this.popup.id;
                newAssociation.priority = 1;
                this.popup.salesChannels.add(newAssociation);
            });
        }
    }
});
