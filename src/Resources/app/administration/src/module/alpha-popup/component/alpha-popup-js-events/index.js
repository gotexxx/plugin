import template from "./alpha-popup-js-events.html.twig"

const { Component } = Shopware;

Component.register("alpha-popup-js-events",{
    template,
    props: {
        popup: {
            type: Object,
            required: true
        }
    }
});