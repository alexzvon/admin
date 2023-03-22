import Core from '../core';

import _ from 'lodash';

export default {
// module.exports = {
  namespaced: true,
  state: {
    all: [],
    category: false,
  },
  getters: {
    flatCategories(state) {
      let cats = [];
      state.all.forEach(cat => {
        cats = [...cats, cat];
        if (cat.children) {
          cats = [...cats, ...cat.children];
        }
      });
      return _.map(cats, cat => {
        cat.name = cat.i18n[0].title;
        return cat;
      });
    },
    getById: (state, getters) => (category_id) => {
      return _.find(getters.flatCategories, { id: category_id });
    },
  },
  mutations: {
    set(state, payload) {
      state[payload[0]] = payload[1];
    },
    updateCategory(state, cat) {
      const index = state.all.indexOf(state.all.find(item => {
        return item.id === cat.id;
      }));
      state.menus[index] = cat;
    },
  },
  actions: {
    get(store) {
      return new Promise((resolve, reject) => {
        axios.get(`/api/shop/categories?api_token=${Core.getApiToken()}`).then((response) => {
          store.commit('set', ['all', response.data.categories]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
  },
};
