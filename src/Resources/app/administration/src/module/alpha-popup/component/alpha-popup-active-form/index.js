import template from './alpha-popup-active-form.html.twig';

const {Component} = Shopware;

Component.register('alpha-popup-active-form', {
    template,
    inject: [
        'repositoryFactory'
    ],
    props: {
        popup: {
            type: Object,
            required: false,
            default: null
        }
    },

});