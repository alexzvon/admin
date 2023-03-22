import Vuex from 'vuex/dist/vuex.esm';
// import Core from "./core";

// module.exports =
export default
new Vuex.Store({
  state: {},
  getters: {},
  mutations: {},
  actions: {},
  modules: {
    product: require('./store/storeProductModule').default,
    expandBanner: require('./store/storeExpandBannersModule').default,
    menu: require('./store/storeMenusModule').default,
    categories: require('./store/storeCategoriesModule').default,
    priceTypes: require('./store/storePriceTypesModule').default,
    contents: require('./store/storeContentsModule').default,
    attributes: require('./store/attributes/storeAttributesModule').default,
    products: require('./store/storeProductsModule').default,
    suppliers: require('./store/storeSuppliersModule').default,
  },
});
