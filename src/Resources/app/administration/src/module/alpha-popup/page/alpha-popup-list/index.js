import template from './alpha-popup-list.html.twig';
import './alpha-popup-list.scss';

const { Component, Mixin, Filter } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('alpha-popup-list', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('listing')
    ],

    data() {
        return {
            popups: null,
            showDeleteModal: false,
            sortBy: 'name',
            isLoading: true
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        popupRepository() {
            return this.repositoryFactory.create('alpha_popup');
        },

        popupColumns() {
            return this.getPopupColumns();
        },

        dateFilter() {
            return Filter.getByName('date');
        },
    },

    methods: {
        getList() {
            this.isLoading = true;
            const criteria = new Criteria(this.page, this.limit);
            criteria.addAssociation('cmsPage');
            criteria.setTerm(this.term);
            criteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection));

            return this.popupRepository.search(criteria, Shopware.Context.api).then((searchResult) => {
                this.total = searchResult.total;
                this.popups = searchResult;

                this.isLoading = false;

                return this.popups;
            });
        },

        onChangeLanguage() {
            this.getList();
        },

        getPopupColumns() {
            return [{
                property: 'name',
                label: this.$tc('alpha-popup.list.columnName'),
                routerLink: 'alpha.popup.detail',
                inlineEdit: 'string',
                allowResize: true,
                primary: true
            }, {
                property: 'active',
                label: this.$tc('alpha-popup.list.columnActive'),
                inlineEdit: 'boolean',
                allowResize: true,
                align: 'center'
            }, {
                property: 'cmsPage.name',
                label: this.$tc('alpha-popup.list.cmsPageLayoutName'),
                inlineEdit: false,
                allowResize: true,
                align: 'center'
            }, {
                property: 'validFrom',
                label: this.$tc('alpha-popup.list.columnValidFrom'),
                inlineEdit: 'date',
                allowResize: true,
                align: 'center'
            }, {
                property: 'validUntil',
                label: this.$tc('alpha-popup.list.columnValidUntil'),
                inlineEdit: 'date',
                allowResize: true,
                align: 'center'
            }];
        }
    }
});
