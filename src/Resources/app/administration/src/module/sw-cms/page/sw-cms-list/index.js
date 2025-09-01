Shopware.Component.override('sw-cms-list', {
    inject: [
        'cmsPageTypeService',
    ],
    methods: {
        createdComponent() {

            if (!this.cmsPageTypeService.getTypeNames().includes('alpha_popup')) {
                this.cmsPageTypeService.register({
                    name: 'alpha_popup',
                    icon: 'regular-gift',
                    title: 'alpha-popup.detail.main.general.cms.pageTypePopupLabel'
                })
                this.$super('createdComponent')
            }
        }
    }
});
