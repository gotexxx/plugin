import template from './alpha-popup-publish-select.html.twig';

const { Component } = Shopware;
const { mapPropertyErrors, mapState, mapGetters } = Shopware.Component.getComponentHelper();

Component.register('alpha-popup-publish-select', {
    template,

    computed: {
        ...mapState('alphaPopupDetail', [
            'popup',
        ]),

        ...mapPropertyErrors('popup', ['tags']),
    }

});
