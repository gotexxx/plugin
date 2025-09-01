import template from './alpha-popup-width-select.html.twig';
const { mapState, mapPropertyErrors } = Shopware.Component.getComponentHelper();

const { Component, Mixin } = Shopware;

Component.register('alpha-popup-width-select', {
    template,
    mixins: [
        Mixin.getByName('placeholder')
    ],
    computed: {
        ...mapState('alphaPopupDetail', [
            'popup',
        ]),
        ...mapPropertyErrors('popup', ['tags']),


        selectWidthValues() {
            return [
                {
                    label: 'Full',
                    value: 'full'
                },
                {
                    label: 'Small',
                    value: 'small'
                },
                {
                    label: 'Medium',
                    value: 'medium'
                },
                {
                    label: 'Large',
                    value: 'large'
                },
                {
                    label: 'Individual',
                    value: 'individual'
                },
            ];
        }
    }
});