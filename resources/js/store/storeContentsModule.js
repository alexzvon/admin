import Core from '../core';

// import _ from "lodash";

export default {
// module.exports = {
  namespaced: true,
  state: {
    all: [],
  },
  getters: {},
  mutations: {
    set(state, payload) {
      state[payload[0]] = payload[1];
    },
    updateContent(state, content) {
      const index = state.all.indexOf(state.all.find(item => {
        return item.id === content.id;
      }));
      state.all[index] = content;
    },
  },
  actions: {
    get(store) {
      return new Promise((resolve, reject) => {
        axios.get(`/api/shop/contents?api_token=${Core.getApiToken()}`).then((response) => {
          store.commit('set', ['all', response.data]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    update(store, content) {
      store.commit('updateContent', content);
      return new Promise((resolve, reject) => {
        axios.patch(`/api/shop/contents/${content.id}?api_token=${Core.getApiToken()}`, content).then((response) => {
          Core.notify('Элемент обновлен', { type: 'success' });
          store.commit('updateContent', response.data
          );
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
  },
};
