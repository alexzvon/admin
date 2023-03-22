import Core from '../core';
import _ from 'lodash';

export default {
// module.exports ={
  namespaced: true,
  state: {
    expandBanners: [],
    expandBanner: false,
  },
  getters: {},
  mutations: {
    set(state, payload) {
      state[payload[0]] = payload[1];
    },
  },
  actions: {
    getAll(store) {
      return new Promise((resolve, reject) => {
        axios.get(`/api/shop/expand-banners?api_token=${Core.getApiToken()}`).then((response) => {
          store.commit('set', ['expandBanners', response.data]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    getOne(store, expandBanner) {
      return new Promise((resolve, reject) => {
        axios.get(`/api/shop/expand-banners/${expandBanner.id}?api_token=${Core.getApiToken()}`).then((response) => {
          store.commit('set', ['expandBanner', response.data]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    create(store, expandBanner) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/expand-banners/create?api_token=${Core.getApiToken()}`, {
          name: expandBanner.name,
        }).then((response) => {
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    update(store, expandBanner) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/expand-banners/${expandBanner.id}/update?api_token=${Core.getApiToken()}`, expandBanner).then((response) => {
          store.commit('set', ['expandBanner', response.data]);
          resolve();
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    delete(store, expandBanner) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/expand-banners/${expandBanner.id}/delete?api_token=${Core.getApiToken()}`).then((response) => {
          store.commit('set', ['expandBanners', response.data]);
          Core.notify('Баннер удален', { type: 'success' });
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    toggleStatus(store, expandBanner) {
      const expandBanners = _.cloneDeep(store.state.expandBanners);

      _.map(expandBanners, banner => {
        if (banner.id === expandBanner.id) {
          banner.enabled = !expandBanner.enabled;
          store.dispatch('update', banner);
        } else {
          banner.enabled = false;
        }
      });

      store.commit('set', ['expandBanners', expandBanners]);
    },
  },
};
