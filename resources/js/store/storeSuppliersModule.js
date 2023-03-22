import Core from '../core';

import _ from 'lodash';

export default {
// module.exports = {
  namespaced: true,
  state: {
    all: [],
  },
  getters: {
    getById: (state, getters) => (supplier_id) => {
      return _.find(state.all, { id: supplier_id });
    },
  },
  mutations: {
    set(state, payload) {
      state[payload[0]] = payload[1];
    },
  },
  actions: {
    get(store) {
      return new Promise((resolve, reject) => {
        axios.get(`/api/shop/suppliers?api_token=${Core.getApiToken()}`).then((response) => {
          store.commit('set', ['all', response.data.suppliers]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
  },
};
