import template from './alpha-popup-product-form.html.twig';
import './alpha-popup-product-form.scss'

const { Component } = Shopware;
const { Criteria } = Shopware.Data;
const {mapPropertyErrors, mapState} = Shopware.Component.getComponentHelper();

Component.register('alpha-popup-product-form', {
    template,
    inject: ['repositoryFactory', 'acl'],

    mixins: [
        'placeholder',
    ],

    props: {
        isLoading: {
            type: Boolean,
            required: false,
        },
    },
    data() {
        return {
            productStreamFilter: null,
            productStreamInvalid: false,
            manualAssignedProductsCount: 0,
        };
    },

    computed: {

        productStreamRepository() {
            return this.repositoryFactory.create('product_stream');
        },

        productColumns() {
            return [
                {
                    property: 'name',
                    label: this.$tc('sw-category.base.products.columnNameLabel'),
                    dataIndex: 'name',
                    routerLink: 'sw.product.detail',
                    sortable: false,
                },
            ];
        },
        productStreamColumns(){
            return [
                {
                    property: 'name',
                    label: this.$tc('sw-category.base.products.columnNameLabel'),
                    dataIndex: 'name',
                    routerLink: 'sw.product.stream.detail',
                    sortable: false,
                },
            ];
        },

        nameColumn() {
            return 'column-name';
        },

        productCriteria() {
            return (new Criteria(1, 10))
                .addFilter(Criteria.equals('parentId', null));
        },
        productStreamCriteria() {
            return new Criteria(1,10);
        },

        productStreamInvalidError() {
            if (this.productStreamInvalid) {
                return new ShopwareError({
                    code: 'PRODUCT_STREAM_INVALID',
                    detail: this.productStreamInvalid,
                });
            }
            return null;
        },

        ...mapState('alphaPopupDetail', [
            'popup',
        ]),

        ...mapPropertyErrors('product', [
            'productAssignmentType',
        ]),

        ...mapPropertyErrors('popup', ['tags']),

    },
    watch:{
        'popup.productStreamId'(id) {
            if (!id) {
                this.productStreamFilter = null;
                return;
            }
            this.loadProductStreamPreview();
        },
        created() {
            this.createdComponent();
        },
    },
    method:{
        createdComponent() {
            if (!this.popup.productStreamId) {
                return;
            }
            this.loadProductStreamPreview();
        },
        loadProductStreamPreview() {
            this.productStreamRepository.get(this.popup.productStreamId)
                .then((response) => {
                    this.productStreamFilter = response.apiFilter;
                    this.productStreamInvalid = response.invalid;
                }).catch(() => {
                this.productStreamFilter = null;
                this.productStreamInvalid = true;
            });
        },
    }
});