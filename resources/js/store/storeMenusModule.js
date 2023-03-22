import Core from '../core';
import _ from 'lodash';

export default {
// module.exports ={
  namespaced: true,
  state: {
    menus: [],
  },
  getters: {},
  mutations: {
    set(state, payload) {
      state[payload[0]] = payload[1];
    },
    updateLink(state, link) {
      const index = state.menus.indexOf(state.menus.find(item => {
        return item.id === link.id;
      }));
      state.menus[index] = link;
    },
  },
  actions: {
    get(store) {
      return new Promise((resolve, reject) => {
        axios.get(`/api/shop/menus?api_token=${Core.getApiToken()}`).then((response) => {
          store.commit('set', ['menus', response.data]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },

    // link contains name & url
    create(store, link) {
      return new Promise((resolve, reject) => {
        if (link.name && link.url) {
          axios.post(`/api/shop/menus/create?api_token=${Core.getApiToken()}`, link).then((response) => {
            resolve(response.data);
            store.commit('set', ['menus', response.data]);
            Core.notify('Пункт меню создан', { type: 'success' });
          }).catch((response) => {
            reject(response.data);
          });
        } else {
          if (!link.name) {
            Core.notify('Укажите текст ссылки', { type: 'error' });
          }
          if (!link.url) {
            Core.notify('Укажите адрес ссылки', { type: 'error' });
          }
          reject();
        }
      });
    },

    updateLink(store, link) {
      store.commit('updateLink', link);
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/menus/${link.id}/update?api_token=${Core.getApiToken()}`, link).then((response) => {
          Core.notify('Пункт меню обновлен', { type: 'success' });
          resolve();
        }).catch((response) => {
          reject(response.data);
        });
      });
    },

    delete(store, link) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/menus/${link.id}/delete?api_token=${Core.getApiToken()}`).then((response) => {
          store.commit('set', ['menus', response.data]);
          Core.notify('Пункт меню удален', { type: 'success' });
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },

    sorting(store, payload) {
      return new Promise((resolve, reject) => {
        payload = _.map(payload, (link, index) => {
          link = _.find(store.state.menus, { id: link.id });
          link.position = index;
          store.commit('updateLink', link);
          return link;
        });

        axios.post(`/api/shop/menus/sorting?api_token=${Core.getApiToken()}`, payload).then(() => {
          Core.notify('Порядок элементов сохранен', { type: 'success' });
          resolve();
        }).catch((response) => {
          reject();
        });
      });
    },

    toggleStatus(store, link) {
      return new Promise((resolve, reject) => {
        link.enabled = !link.enabled;
        store.dispatch('updateLink', link);
      });
    },
  },
};
