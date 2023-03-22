import Core from '../../../core';

// payload must contains product_id, attribute_id, option_id

// module.exports =
export default (store, payload) => {
  store.commit('addUsedOptions', [payload.option_id]);

  return new Promise((resolve, reject) => {
    axios.post(`/api/shop/attributor/create?api_token=${Core.getApiToken()}`, payload)
      .then((response) => {
        resolve(response.data);
      })
      .catch((response) => {
        reject(response.data);
      });
  });
};
