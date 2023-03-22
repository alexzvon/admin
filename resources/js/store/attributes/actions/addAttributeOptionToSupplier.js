import Core from '../../../core';

// payload must contains relation, relation_id, attribute_id, option_id

// module.exports =
export default (store, payload) => {
  store.commit('addUsedOptions', [payload.option_id]);

  return new Promise((resolve, reject) => {
    axios.post(`/api/shop/attributor/create?api_token=${Core.getApiToken()}`, payload)
      .then((response) => {
        resolve(response.data);
        Core.notify('Свойство поставщика обновлено', { type: 'success' });
      })
      .catch((response) => {
        reject(response.data);
      });
  });
};
