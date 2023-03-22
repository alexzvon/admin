import _ from 'lodash';
import Core from '../../../core';

// payload must contains supplier_id, attribute_id, option_id

// module.exports =
export default (store, payload) => {
  store.commit('set', [
    'usedOptions',
    _.filter(store.state.usedOptions, option_id => {
      return option_id !== payload.option_id;
    }),
  ]);

  return new Promise((resolve, reject) => {
    axios.post(`/api/shop/attributor/delete?api_token=${Core.getApiToken()}`, payload).then((response) => {
      resolve(response.data);
    }).catch((response) => {
      reject(response.data);
    });
  });
};
