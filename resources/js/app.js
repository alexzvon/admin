import _ from 'lodash';
import Vue from 'vue';
import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';
import Vuex from 'vuex';
import VuePusher from 'vue-pusher';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/ru-RU';

import {BootstrapVue, IconsPlugin} from 'bootstrap-vue';
import Core from './core';
import CategoriesTable from './components/shop/categories/CategoriesTable';
import CategoryEdit from './components/shop/categories/CategoryEdit';

import RoomsTable from './components/shop/rooms/RoomsTable';
import RoomEdit from './components/shop/rooms/RoomEdit';

import StyleEdit from './components/shop/styles/StyleEdit';

import QuickFilterTable from './components/shop/quickFilter/quickFilterTable';
import QuickFilterCreate from './components/shop/quickFilter/quickCreateFilter';
import QuickFilterEdit from './components/shop/quickFilter/quickEditFilter';

import CityCreate from './components/territory/cityCreate';
import CityEdit from './components/territory/cityEdit';
import CityTable from './components/territory/cityTable';

import IncomeTable from './components/income/incomeTable.vue';
import IncomeEdit from './components/income/incomeEdit';
import IncomeCreate from './components/income/incomeCreate';

//import ProbaTable from './views/city/probaTable';

//import LoaderProducts from './views/loader/products';

//LoaderProducts = () => import('./views/loader/products');

import CompaniesTable from './views/franchise/companies/companiesTable';
import CompaniesCreate from './views/franchise/companies/companiesCreate';
import CompaniesEdit from './views/franchise/companies/companiesEdit';

import ProductsTable from './components/shop/products/ProductsTable';
import ProductEdit from './components/shop/products/ProductEdit';

import OrdersTable from './components/shop/orders/OrdersTable';

import CustomerEdit from './components/shop/customers/CustomerEdit';
import CustomersTable from './components/shop/customers/CustomersTable';

import ReplyTable from './components/shop/reply/ReplyTable';
import ReplyEdit from './components/shop/reply/ReplyEdit';

import ReturnsTable from './components/returns/ReturnsTable';
import ReturnsEdit from './components/returns/ReturnsEdit';

import SuppliersTable from './components/shop/suppliers/SuppliersTable';
import SupplierEdit from './components/shop/suppliers/SupplierEdit';

import AttributesTable from './components/shop/attributes/AttributesTable';
import AttributeEdit from './components/shop/attributes/AttributeEdit';

import PriceTypesTable from './components/shop/priceTypes/PriceTypesTable';
import PriceTypeEdit from './components/shop/priceTypes/PriceTypeEdit';

import PromoCodesTable from './components/shop/promoCodes/PromoCodesTable';
import PromoCodeEdit from './components/shop/promoCodes/PromoCodeEdit';

import BadgeTypesTable from './components/shop/badges/BadgeTypesTable';
import BadgeTypeEdit from './components/shop/badges/BadgeTypeEdit';

import BannersTypes from './components/shop/banners/BannersTypes';
import BannersTable from './components/shop/banners/BannersTable';
import BannerEdit from './components/shop/banners/BannerEdit';

import SaleTable from './components/shop/sale/SaleTable';
import SaleEdit from './components/shop/sale/SaleEdit';

import InteriorEdit from './components/shop/interior/InteriorEdit';
import InteriorTable from './components/shop/interior/InteriorTable';

import AdminsTable from './components/system/Admins/AdminsTable';
import AdminEdit from './components/system/Admins/AdminEdit';

import RolesTable from './components/system/rbac/Roles/RolesTable';
import RoleEdit from './components/system/rbac/Roles/RoleEdit';

import PermissionGroupsTable from './components/system/rbac/Permissions/PermissionGroupsTable';
import PermissionGroupEdit from './components/system/rbac/Permissions/PermissionGroupEdit';

import Loading from './components/Loading';
import ClearCacheBtn from './components/ClearCacheBtn';
import Avatar from './components/Avatar';

import ReviewsTable from './components/reviews/ReviewsTable';
import ReviewEdit from './components/reviews/ReviewEdit';

import MenuEdit from './components/shop/menu/MenuEdit.vue';
import draggable from 'vuedraggable';
import SidebarMenu from './components/sidebar/SidebarMenu.vue';
import Toggle from './components/Toggle';
import wysiwyg from 'vue-wysiwyg';
import FastSaleEdit from './components/fastSale/FastSaleEdit';

window._ = _;

require('@fancyapps/fancybox');
require('select2');

// import 'bootstrap/dist/css/bootstrap.css';
// import 'bootstrap-vue/dist/bootstrap-vue.css';

Vue.config.devtools = true

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

Vue.use(Vuex);

Vue.use(ElementUI, {locale});

Vue.use(VuePusher, {
    api_key: 'c33cecbacb03f023e7e8',
    options: {
        cluster: 'eu',
        encrypted: true,
        forceTLS: true,
    },
});

// import * as Sentry from '@sentry/browser';
//
// Sentry.init({
//     dsn         : 'https://71f274ca760b4075ae269d324d5c96f3@sentry.io/1397467',
//     integrations: [new Sentry.Integrations.Vue({
//         Vue,
//         attachProps: true
//     })]
// });

require('../sass/app.scss');

try {
// window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {
}

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

Vue.prototype.$helpers = {
    getImagePath(path) {
        // @s3
        if (path.indexOf('https') === -1) {
            let slash = '/';

            if (path.charAt(0) === '/' || path.charAt(0) === '/\/') {
                slash = '';
            }

            return process.env.MIX_AWS_URL + slash + path;
        }

        return path;
    },
    getNumbersFromString(string) {
        return +(string.replace(/\D+/g, ''));
    },
};
Vue.prototype.$core = Core;

Vue.use(VueRouter);
Vue.use(VeeValidate, {fieldsBagName: 'formFields', errorBagName: 'formErrors'});

window.CKEDITOR_BASEPATH = '/js/vendor/ckeditor/';

Vue.directive('onClickaway', require('./directives/clickAway').default);

Vue.component('drag', draggable);

Vue.component('sidebar-menu', SidebarMenu);

Vue.component('loading-spinner', require('./components/LoadingSpinner').default);
Vue.component('uploadPicSingle', require('./components/UploadPicSingle').default);
Vue.component('selector', require('./components/Selector').default);
Vue.component('attributor-add', require('./components/attributor/AttributorAdd').default);
Vue.component('attributor-edit', require('./components/attributor/AttributorEdit').default);
Vue.component('input-editor', require('./components/InputEditor').default);

Vue.component('toggle', Toggle);

Vue.use(wysiwyg, {
    // modules: bold, italic, justifyLeft, justifyCenter, justifyRight, headings, orderedList, unorderedList, table, removeFormat
    hideModules: {
        code: true,
        image: true,
        link: true,
        separator: true,
        underline: true,
        justifyRight: true,
        justifyCenter: true,
        justifyLeft: true,
    },
    forcePlainTextOnPaste: true,
});

require('vue-wysiwyg/dist/vueWysiwyg.css');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
});

const routes = [
    {path: '/', component: require('./components/shop/Dashboard').default},
    {path: '/contents', component: require('./components/contents/Contents').default},

//    {path: '/city/proba', component: ProbaTable},
//    {path: '/loader/products', component: LoaderProducts},
    {path: '/loader/products', component: () => import('./views/loader/products')},

    {
        path: '/contents/:scope',
        component: require('./components/contents/ContentEdit'),
        props: route => {
            return route.params;
        },
    },
    // {path: "/contents/:scope/:block", component: require("./components/contents/ContentEdit"), props: route => ({...route.params})},

    {path: '/shop', redirect: '/shop/products'},
    {path: '/shop/categories', component: CategoriesTable},
    {
        path: '/shop/categories/create',
        component: CategoryEdit,
        props: {type: 'create'},
    },
    {
        path: '/shop/categories/:id',
        component: CategoryEdit,
        props: function (route) {
            return {...route.params, type: 'edit'};
        },
    },
    {path: '/shop/rooms', component: RoomsTable},
    {path: '/shop/rooms/create', component: RoomEdit, props: {type: 'create'}},
    {path: '/shop/rooms/:id', component: RoomEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/styles', component: require('./components/shop/styles/StylesTable').default},
    {path: '/shop/styles/create', component: StyleEdit, props: {type: 'create'}},
    {path: '/shop/styles/:id', component: StyleEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/products', component: ProductsTable},
    {path: '/shop/products/create', component: require('./components/shop/products/ProductCreate').default},
    {path: '/shop/products/:id', component: ProductEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/quick-filter/table', component: QuickFilterTable},
    {path: '/shop/quick-filter/create', component: QuickFilterCreate},
    {path: '/shop/quick-filter/edit/:id', component: QuickFilterEdit},

    {path: '/shop/orders', component: OrdersTable},

    {path: '/shop/customers', component: CustomersTable},

    {path: '/shop/suppliers', component: SuppliersTable},
    {path: '/shop/suppliers/create', component: SupplierEdit, props: {type: 'create'}},
    {path: '/shop/suppliers/:id', component: SupplierEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/attributes', component: AttributesTable},
    {path: '/shop/attributes/create', component: AttributeEdit, props: {type: 'create'}},
    {path: '/shop/attributes/:id', component: AttributeEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/price-types', component: PriceTypesTable},
    {path: '/shop/price-types/create', component: PriceTypeEdit, props: {type: 'create'}},
    {path: '/shop/price-types/:id', component: PriceTypeEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/promo-codes', component: PromoCodesTable},
    {path: '/shop/promo-codes/create', component: PromoCodeEdit, props: {type: 'create'}},
    {path: '/shop/promo-codes/:id', component: PromoCodeEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/customers', component: CustomersTable},
    {path: '/shop/customers/create', component: CustomerEdit, props: {type: 'create'}},
    {path: '/shop/customers/:id', component: CustomerEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/reply/table', component: ReplyTable},
    {path: '/shop/reply/edit/:return_id/:product_id', component: ReplyEdit},

    {path: '/shop/returns/table', component: ReturnsTable},
    {path: '/shop/returns/edit/:id', component: ReturnsEdit},

    {path: '/shop/badge-types', component: BadgeTypesTable},
    {path: '/shop/badge-types/create', component: BadgeTypeEdit, props: {type: 'create'}},
    {path: '/shop/badge-types/:id', component: BadgeTypeEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/fast-sale', component: FastSaleEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/banners', component: BannersTable},
    {path: '/shop/banners/default', redirect: '/shop/banners'},
    {path: '/shop/banners/header', redirect: '/shop/banners'},
    {path: '/shop/banners/default/create', component: BannerEdit, props: {type: 'create', bannerType: 'default'}},
    {path: '/shop/banners/header/create', component: BannerEdit, props: {type: 'create', bannerType: 'header'}},
    {
        path: '/shop/banners/:id',
        component: BannerEdit,
        props: route => ({...route.params, type: 'edit'}),
        name: 'banner-edit',
    },

    {path: '/shop/banners-types', component: BannersTypes},

    {path: '/shop/expand-banners', component: require('./components/shop/expandBanners/expandBannersTable').default},
    {
        path: '/shop/expand-banners/create',
        component: require('./components/shop/expandBanners/expandBannersCreate').default
    },
    {path: '/shop/expand-banners/:id', component: require('./components/shop/expandBanners/expandBannersEdit').default},

    {path: '/shop/sale', component: SaleTable},
    {path: '/shop/sale/create', component: SaleEdit, props: {type: 'create'}},
    {path: '/shop/sale/:id', component: SaleEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/interiors', component: InteriorTable},
    {path: '/shop/interiors/create', component: InteriorEdit, props: {type: 'create'}},
    {path: '/shop/interiors/:id', component: InteriorEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/shop/menus', component: MenuEdit},

    {path: '/system/admins', component: AdminsTable},
    {path: '/system/admins/create', component: AdminEdit, props: {type: 'create'}},
    {path: '/system/admins/:id', component: AdminEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/system/rbac/roles', component: RolesTable},
    {path: '/system/rbac/roles/create', component: RoleEdit, props: {type: 'create'}},
    {path: '/system/rbac/roles/:id', component: RoleEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/system/rbac/roles', component: RolesTable},
    {path: '/system/rbac/roles/create', component: RoleEdit, props: {type: 'create'}},
    {path: '/system/rbac/roles/:id', component: RoleEdit, props: route => ({...route.params, type: 'edit'})},

    {path: '/system/rbac/permission-groups', component: PermissionGroupsTable},
    {path: '/system/rbac/permission-groups/create', component: PermissionGroupEdit, props: {type: 'create'}},
    {
        path: '/system/rbac/permission-groups/:id',
        component: PermissionGroupEdit,
        props: route => ({...route.params, type: 'edit'}),
    },

    {path: '/city/table', component: CityTable},
    {path: '/city/create', component: CityCreate},
    {path: '/city/edit/:id', component: CityEdit},

    {path: '/test/address/', component: () => import('./views/test/address/checkIn')},
    {path: '/test/product/', component: () => import('./views/product/product')},

    {path: '/cities/table', component: () => import('./views/franchise/territory/citiesTable')},
    {path: '/cities/create', component: () => import('./views/franchise/territory/citiesCreate')},
    {path: '/cities/edit/:id', component: () => import('./views/franchise/territory/citiesEdit')},

    {path: '/showroom/table/:id_group/', component: () => import('./views/franchise/showroom/tableShowRoom')},
    {path: '/showroom/create/:id_group/', component: () => import('./views/franchise/showroom/createShowRoom')},
    {path: '/showroom/edit/:id/', component: () => import('./views/franchise/showroom/editShowRoom')},

    {path: '/showroomgroup/create/', component: () => import('./views/franchise/showroom/createShowRoomGroup')},
    {path: '/showroomgroup/edit/:id', component: () => import('./views/franchise/showroom/editShowRoomGroup')},
    {path: '/showroomgroup/table/', component: () => import('./views/franchise/showroom/tableShowRoomGroup')},

    {path: '/income/table', component: IncomeTable},
    {path: '/income/edit/:id', component: IncomeEdit},
    {path: '/income/create', component: IncomeCreate},

    {path: '/companies/table', component: CompaniesTable},
    {path: '/companies/create', component: CompaniesCreate},
    {path: '/companies/edit/:id', component: CompaniesEdit},

    {path: '/franchise/users/table', component: () => import('./views/franchise/users/usersTable') },
    {path: '/franchise/users/create', component: () => import('./views/franchise/users/usersCreate') },
    {path: '/franchise/users/edit/:id', component: () => import('./views/franchise/users/usersEdit') },

    {path: '/reviews', component: ReviewsTable},
    {path: '/reviews/:id', component: ReviewEdit, props: route => ({...route.params, type: 'edit'})},
];

const router = new VueRouter({
    mode: 'history',
    routes,
});

Core.init().then(() => {
    window.App = new Vue({
        router,
        store: require('./store').default,
        components: {
            Loading,
            ClearCacheBtn,
            Avatar,
        },

        data() {
            return {
                loading: false,
            };
        },

        created() {
            const resizeHandler = _.debounce(() => {
                this.windowWidth = window.innerWidth;
                this.$emit('resize');
            }, 50);

            window.addEventListener('resize', resizeHandler, {passive: true});
        },

        methods: {
            loadingStart() {
                this.loading = true;
            },

            loadingEnd() {
                this.loading = false;
            },

            config() {
                return Core.config.apply(Core, arguments);
            },
        },
    }).$mount('#app');

    App.$router.beforeEach((to, from, next) => {
        App.$store.commit('attributes/set', ['usedAttributes', []]);
        App.$store.commit('attributes/set', ['usedOptions', []]);
        App.$store.commit('product/set', ['product', []]);
        next();
    });
});

let isFirstTime = true;

router.afterEach(() => {
    if (isFirstTime) {
        isFirstTime = false;
    } else {
        const previousPath = window.location.href.replace(window.location.origin, '');

        setTimeout(() => {
            window.history.replaceState({
                ...window.history.state,
                previousPath,
            }, '', window.location.href);
        });
    }
});

// BlockQueue

window.processSelectedFile = function (file, input) {
    const inputEl = document.querySelector('#' + input);

    if (inputEl) {
        inputEl.value = file;

        inputEl.dispatchEvent(makeEvent('change'));
    }
};

function makeEvent(eventName) {
    let myEvent;

    if (typeof CustomEvent === 'undefined') {
        myEvent = document.createEvent(eventName);
        myEvent.initCustomEvent(eventName, false, true);
    } else {
        myEvent = new CustomEvent(eventName, {
            bubbles: false,
            cancelable: true,
        });
    }

    return myEvent;
}

// if (module.hot) {
//    module.hot.accept(function () {
//        window.location.reload();
//    });
// }
