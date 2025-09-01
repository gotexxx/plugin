import template from './alpha-popup-scroll-activation.html.twig';
const { mapState, mapPropertyErrors } = Shopware.Component.getComponentHelper();

const { Component } = Shopware;

Component.register('alpha-popup-scroll-activation', {
    template,
    computed: {
        ...mapState('alphaPopupDetail', [
            'popup',
        ]),
        ...mapPropertyErrors('popup', ['tags']),


        selectValues() {
            return [
                {
                    label: '---',
                    value: 999
                },
                {
                    label: '0%',
                    value: 0
                },
                {
                    label: '25%',
                    value: 25
                },
                {
                    label: '50%',
                    value: 50
                },
                {
                    label: '75%',
                    value: 75
                },
                {
                    label: '100%',
                    value: 100
                },
            ];
        }
    }
});