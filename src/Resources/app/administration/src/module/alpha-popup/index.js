/**
 *
 */
import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

import './component/alpha-popup-rule-select';
import './component/alpha-popup-active-form';
import './component/alpha-popup-basic-form';
import './component/alpha-popup-publish-select';
import './component/alpha-popup-sales-channel-select';
import './component/alpha-popup-cms-layout-card';
import './component/alpha-popup-layout-modal';
import './component/alpha-popup-persona-form';
import './component/alpha-popup-order-condition-form';
import './component/alpha-popup-cart-condition-form';
import './component/alpha-popup-category-form';
import './component/alpha-popup-cookie-expire-time-select';
import './component/alpha-popup-product-form';
import './component/alpha-popup-time-delay-select';
import './component/alpha-popup-scroll-activation';
import './component/alpha-popup-width-select';
import './component/alpha-popup-js-events';
import './component/alpha-popup-element-selector';

import './view/alpha-popup-detail-base';
import './view/alpha-popup-detail-condition-restrictions';
import './view/alpha-popup-detail-location-restrictions';

import './page/alpha-popup-detail';
import './page/alpha-popup-list';

Shopware.Module.register('alpha-popup', {
    type: 'plugin',
    name: 'Popup',
    title: 'alpha-popup.general.mainMenuItemGeneral',
    description: 'alpha-popup.general.description',
    color: '#62ff80',
    icon: 'regular-list-xs',
    entity: 'alpha_popup',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        overview: {
            component: 'alpha-popup-list',
            path: 'overview'
        },
        create: {
            component: 'alpha-popup-detail',
            path: 'create',
            redirect: {
                name: 'alpha.popup.create.base'
            },
            children: {
                base: {
                    component: 'alpha-popup-detail-base',
                    path: 'base',
                    meta: {
                        parentPath: 'alpha.popup.overview'
                    }
                }
            }
        },
        detail: {
            component: 'alpha-popup-detail',
            path: 'detail/:id?',
            redirect: {
                name: 'alpha.popup.detail.base'
            },
            children: {
                base: {
                    component: 'alpha-popup-detail-base',
                    path: 'base',
                    meta: {
                        parentPath: 'alpha.popup.overview'
                    }
                },
                locations: {
                    component: 'alpha-popup-detail-location-restrictions',
                    path: 'locations',
                    meta: {
                        parentPath: 'alpha.popup.overview'
                    }
                },
                conditions: {
                    component: 'alpha-popup-detail-condition-restrictions',
                    path: 'conditions',
                    meta: {
                        parentPath: 'alpha.popup.overview'
                    }
                }
            },
            props: {
                default: (route) => {
                    return {
                        popupId: route.params.id
                    };
                }
            }
        }
    },

    navigation: [ {
        path: 'alpha.popup.overview',
        label: 'alpha-popup.general.mainMenuItemGeneral',
        color: '#62ff80',
        icon: 'regular-list-xs',
        position: 120,
        parent: 'sw-marketing'
    }]
});
