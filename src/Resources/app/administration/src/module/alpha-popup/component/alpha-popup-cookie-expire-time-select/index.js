import template from './alpha-popup-cookie-expire-time-select.html.twig';

const { Component } = Shopware;
const { mapPropertyErrors, mapState } = Shopware.Component.getComponentHelper();

Component.register('alpha-popup-cookie-expire-time-select', {
    template,

    computed: {
        ...mapState('alphaPopupDetail', [
            'popup',
        ]),

        ...mapPropertyErrors('popup', ['tags']),

        selectValues() {
            return [
                {
                    label: this.$tc('alpha-popup.detail.main.general.cookie.none'),
                    value: 0
                },
                {
                    label: this.$tc('alpha-popup.detail.main.general.cookie.oneHour'),
                    value: 0.0417
                },
                {
                    label: this.$tc('alpha-popup.detail.main.general.cookie.sixHours'),
                    value: 0.25
                },
                {
                    label: this.$tc('alpha-popup.detail.main.general.cookie.twelveHours'),
                    value: 0.5
                },
                {
                    label: this.$tc('alpha-popup.detail.main.general.cookie.oneDay'),
                    value: 1
                },
                {
                    label: this.$tc('alpha-popup.detail.main.general.cookie.twoDay'),
                    value: 2
                },
                {
                    label: this.$tc('alpha-popup.detail.main.general.cookie.oneWeek'),
                    value: 7
                },
                {
                    label: this.$tc('alpha-popup.detail.main.general.cookie.twoWeeks'),
                    value: 14
                },
                {
                    label: this.$tc('alpha-popup.detail.main.general.cookie.oneMonth'),
                    value: 30
                },
                {
                    label: this.$tc('alpha-popup.detail.main.general.cookie.indefinite'),
                    value: 999
                }
            ];
        }
    }

});
