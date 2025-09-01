import template from './alpha-popup-time-delay-select.html.twig';
const { mapState, mapPropertyErrors } = Shopware.Component.getComponentHelper();

const { Component } = Shopware;

Component.register('alpha-popup-time-delay-select', {
    template,
    computed: {
        ...mapState('alphaPopupDetail', [
            'popup',
        ]),
        ...mapPropertyErrors('popup', ['tags']),


        selectTimeValues() {
            return [
                {
                    label: '1 Sekunde',
                    value: 1000
                },
                {
                    label: '2 Sekunden',
                    value: 2000
                },
                {
                    label: '3 Sekunden',
                    value: 3000
                },
                {
                    label: '4 Sekunden',
                    value: 4000
                },
                {
                    label: '5 Sekunden',
                    value: 5000
                },
                {
                    label: '10 Sekunden',
                    value: 10000
                },
                {
                    label: '15 Sekunden',
                    value: 15000
                },
                {
                    label: '20 Sekunden',
                    value: 20000
                },
                {
                    label: '30 Sekunden',
                    value: 30000
                }
            ];
        }
    }
});