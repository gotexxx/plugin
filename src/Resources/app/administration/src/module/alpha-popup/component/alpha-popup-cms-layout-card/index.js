import template from './alpha-popup-cms-layout-card.html.twig';
import './alpha-popup-cms-layout-card.scss';

const { Component } = Shopware;

Component.register('alpha-popup-cms-layout-card', {
    template,

    props: {
        popup: {
            type: Object,
            required: true
        },

        cmsPage: {
            type: Object,
            required: false,
            default: null
        },

        isLoading: {
            type: Boolean,
            required: false,
            default: false
        }
    },

    data() {
        return {
            showLayoutSelectionModal: false
        };
    },

    computed: {
        cmsPageTypes() {
            return {
                page: this.$tc('sw-cms.detail.label.pageTypeShopPage'),
                landingpage: this.$tc('sw-cms.detail.label.pageTypeLandingpage'),
                product_list: this.$tc('sw-cms.detail.label.pageTypeCategory'),
                product_detail: this.$tc('sw-cms.detail.label.pageTypeProduct')
            };
        }
    },

    methods: {
        onLayoutSelect(selectedLayout) {
            this.popup.cmsPageId = selectedLayout;
        },

        onLayoutReset() {
            this.onLayoutSelect(null);
        },

        openInPagebuilder() {
            let routeData;
            if (!this.cmsPage) {
                routeData = this.$router.resolve({ name: 'sw.cms.create' });
            } else {
                routeData = this.$router.resolve({ name: 'sw.cms.detail', params: { id: this.popup.cmsPageId } });
            }
            window.open(routeData.href,'_blank');
        },

        openLayoutModal() {
            this.showLayoutSelectionModal = true;
        },

        closeLayoutModal() {
            this.showLayoutSelectionModal = false;
        }
    }
});
