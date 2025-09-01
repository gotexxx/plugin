import template from "./alpha-popup-element-selector.html.twig"

const { Component } = Shopware;

Component.register("alpha-popup-element-selector",{
    template,
    props: {
        popup: {
            type: Object,
            required: true
        }
    }
});