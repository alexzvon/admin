import Core from '../core';
import _ from 'lodash';

export default {
// module.exports ={
  namespaced: true,
  state: {
    product: false,
    videos: [],
  },
  getters: {},
  mutations: {
    set(state, payload) {
      state[payload[0]] = payload[1];
    },
  },
  actions: {
    searchProductByName(store, query) {
      return new Promise((resolve, reject) => {
        axios.get(`/api${document.location.pathname.replace(/\/$/, '')}/search?api_token=${Core.getApiToken()}&q=${query}`).then((response) => {
          resolve(response.data.result);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    createProduct(store, product) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/products/create?api_token=${Core.getApiToken()}`, {
          title: product.title,
        }).then((response) => {
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    getProductVideos(store, product_id = false) {
      product_id = product_id || store.state.product.id;
      return new Promise((resolve, reject) => {
        axios.get(`/api/shop/products/${product_id}/videos?api_token=${Core.getApiToken()}`).then((response) => {
          store.commit('set', ['videos', response.data]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    // payload = url, product_id, provider_id
    addProductVideo(store, payload) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/products/${payload.product_id}/videos/add?api_token=${Core.getApiToken()}`, {
          url: payload.url,
          provider_id: payload.provider_id,
        }).then((response) => {
          console.dir(response);
          store.commit('set', ['videos', response.data]);
          resolve();
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    // payload = url, product_id, provider_id
    getVimeoThumb(store, payload) {
      return new Promise((resolve) => {
        axios.post(`/api/shop/products/${payload.product_id}/videos/vimeo/thumb?api_token=${Core.getApiToken()}`, {
          video_id: payload.video_id,
        }).then((responce) => {
          resolve(responce);
        });
      });
    },
    updateProductVideo(store, video) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/products/${video.product_id}/videos/update?api_token=${Core.getApiToken()}`, video).then((response) => {
          store.commit('set', ['videos', response.data]);
          resolve();
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
    deleteProductVideo(store, video) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/products/${video.product_id}/videos/delete?api_token=${Core.getApiToken()}`, video).then((response) => {
          store.commit('set', ['videos', response.data]);
          Core.notify('Видеоролик удален', { type: 'success' });
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },
  },
};
