
Shopware.Component.override('sw-cms-detail', {

    computed: {
        cmsPageTypes() {
            return {
                page: this.$tc('sw-cms.detail.label.pageTypeShopPage'),
                landingpage: this.$tc('sw-cms.detail.label.pageTypeLandingpage'),
                product_list: this.$tc('sw-cms.detail.label.pageTypeCategory'),
                product_detail: this.$tc('sw-cms.detail.label.pageTypeProduct'),
                alpha_popup: this.$tc('alpha-popup.detail.main.general.cms.pageTypePopupLabel'),
            };
        },
    }
});
