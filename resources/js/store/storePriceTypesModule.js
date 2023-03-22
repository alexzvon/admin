import Core from '../core';

import _ from 'lodash';

export default {
// module.exports = {
  namespaced: true,
  state: {
    all: [],
  },
  getters: {
    getById: (state, getters) => (priceTypeId) => {
      return _.find(getters.withTitle, { id: priceTypeId });
    },
    withTitle: (state, getters) => _.map(state.all, type => {
      type.title = type.i18n[0].title;
      return type;
    }),
  },
  mutations: {
    set(state, payload) {
      state[payload[0]] = payload[1];
    },
  },
  actions: {
    get(store) {
      return new Promise((resolve, reject) => {
        if (store.state.all.length > 0) {
          resolve(store.state.all);
        } else {
          axios.get(`/api/shop/price-types?api_token=${Core.getApiToken()}`).then((response) => {
            store.commit('set', ['all', response.data['price-types']]);
            resolve(response.data);
          }).catch((response) => {
            reject(response.data);
          });
        }
      });
    },
  },
};
