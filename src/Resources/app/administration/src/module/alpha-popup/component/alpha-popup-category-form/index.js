import template from './alpha-popup-category-form.html.twig';

const { Component } = Shopware;
const { mapPropertyErrors, mapState } = Shopware.Component.getComponentHelper();

Component.register('alpha-popup-category-form', {
    template,

    computed: {
        ...mapState('alphaPopupDetail', [
            'popup',
        ]),

        ...mapPropertyErrors('popup', ['tags']),

    }

});
