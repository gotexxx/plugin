import template from './alpha-popup-basic-form.html.twig';
import './alpha-popup-basic-form.scss';

const {Component, Mixin} = Shopware;
const {mapPropertyErrors} = Shopware.Component.getComponentHelper();
const {Criteria, EntityCollection} = Shopware.Data;
const types = Shopware.Utils.types;

Component.register('alpha-popup-basic-form', {
    template,

    inject: [
        'cmsService',
        'repositoryFactory'
    ],

    mixins: [
        Mixin.getByName('placeholder')
    ],

    props: {
        popup: {
            type: Object,
            required: false,
            default: null
        }
    },

    data() {
        return {
            isLoading: false,
        };
    },

    computed: {
        cmsPageRepository() {
            return this.repositoryFactory.create('cms_page');
        },
        cmsPage() {
            return Shopware.State.get('cmsPageState').currentPage;
        },

        cmsPageId() {
            return this.popup ? this.popup.cmsPageId : null;
        },

        ...mapPropertyErrors('popup', ['name', 'validUntil']),
    },

    watch: {
        popup() {
            if (this.popup) {
                this.loadPopups();
            }
        },
        cmsPageId() {
            Shopware.State.dispatch('cmsPageState/resetCmsPageState');
            this.getAssignedCmsPage();
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        loadPopups() {
            const popupRepository = this.repositoryFactory.create('alpha_popup');
            const criteria = new Criteria();

            popupRepository.search(criteria, Shopware.Context.api);
        },

        createdComponent() {
            if (this.popup) {
                this.loadPopups();
                if(this.popup.isNew && this.popup.isNew()){
                    Shopware.State.dispatch('cmsPageState/resetCmsPageState');
                }
            }
        },

        getAssignedCmsPage() {
            if (this.cmsPageId === null) {
                return Promise.resolve(null);
            }
            const criteria = new Criteria(1, 1);
            criteria.setIds([this.cmsPageId]);
            criteria.addAssociation('previewMedia');

            return this.cmsPageRepository.search(criteria).then((response) => {
                const cmsPage = response.get(this.cmsPageId);

                this.updateCmsPageDataMapping();
                Shopware.State.commit('cmsPageState/setCurrentPage', cmsPage);
                return this.cmsPage;
            });
        },

        updateCmsPageDataMapping() {
            Shopware.State.commit('cmsPageState/setCurrentMappingEntity', 'popup');
            Shopware.State.commit(
                'cmsPageState/setCurrentMappingTypes',
                this.cmsService.getEntityMappingTypes('popup')
            );
            Shopware.State.commit('cmsPageState/setCurrentDemoEntity', this.popup);
        },

    }
});
