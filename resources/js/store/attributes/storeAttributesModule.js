import Core from '../../core/index';
import _ from 'lodash';

export default {
// module.exports = {
  namespaced: true,
  state: {
    attributeRelations: [],
    attributes: [],
    options: [],
    prices: [],
    usedAttributes: [],
    usedOptions: [],
    globalAttributeIds: [
      2, 4, 12, 14,
    ],
  },
  getters: {
    attributes: state => _.sortBy(state.attributes, attribute => attribute.i18n[0].title),

    usedAttributes: (state, getters) => getters.attributes.filter(attribute => getters.isAttributeUsed(attribute)),

    globalAttributes: (state, getters) => getters.attributes.filter(attribute => getters.isAttributeGlobal(attribute)),

    notGlobalAttributes: (state, getters) => getters.attributes.filter(attribute => !getters.isAttributeGlobal(attribute)),

    usedGlobalAttributes: (state, getters) => getters.globalAttributes.filter(attribute => getters.isAttributeUsed(attribute)),

    usedNotGlobalAttributes: (state, getters) => getters.notGlobalAttributes.filter(attribute => getters.isAttributeUsed(attribute)),

    // payload contains searchQuery & onlyGlobal
    searchAttributesByName: (state, getters) => (payload) => {
      if (typeof payload.searchQuery === 'string') {
        const source = payload.onlyGlobal ? getters.globalAttributes : getters.notGlobalAttributes;

        return _.filter(source, attribute => {
          let pass = attribute.i18n[0].title.toLowerCase().indexOf(payload.searchQuery.toLowerCase()) !== -1;
          if (pass) {
            pass = state.usedAttributes.indexOf(attribute.id) === -1;
          }
          return pass;
        });
      }
      return [];
    },
    attributeById: (state, getters) => id => _.find(getters.attributes, { id: id }),

    attributeRelationByAttributeId: (state, getters) => id => _.find(state.attributeRelations, { attribute_id: id }),

    attributeRelationOptionsByAttributeId: (state, getters) => attribute_id => getters.attributeRelationByAttributeId(+attribute_id).linked_options,

    isAttributeUsed: state => attribute => state.usedAttributes.indexOf(+attribute.id) >= 0,

    isOptionIdUsed: state => option_id => state.usedOptions.indexOf(+option_id) >= 0,

    isAttributeGlobal: state => attribute => state.globalAttributeIds.indexOf(+attribute.id) !== -1,

    options: (state) => _.sortBy(state.options, option => option.i18n[0].title),

    optionById: (state, getters) => id => _.find(getters.options, { id: id }),

    optionsByAttributeId: (state, getters) => (id) => {
      return _.sortBy(_.filter(getters.options, { attribute_id: id }), 'id');
    },

    selectedOptionsByAttributeId: (state, getters) => (id) => {
      const allAttributeOptions = getters.optionsByAttributeId(id);

      const filtered = _.filter(allAttributeOptions, option => {
        return state.usedOptions.indexOf(option.id) > -1;
      });

      return _.sortBy(filtered, option => {
        const linkAttributeOption = _.find(getters.attributeRelationOptionsByAttributeId(option.attribute_id), { option_id: option.id });
        return typeof linkAttributeOption === 'object' ? linkAttributeOption.id : 0;
      });
    },
  },
  mutations: {
    set(state, payload) {
      state[payload[0]] = payload[1];
    },

    addUsedAttributes: (state, ids) => state.usedAttributes = [...state.usedAttributes, ...ids],

    addUsedOptions: (state, ids) => state.usedOptions = [...state.usedOptions, ...ids],

    addAttributeRelations: (state, els) => {
      state.attributeRelations = [...state.attributeRelations, ...els];
    },

    removeEmptyAttributesFromUsed: state => state.usedOptions = [...state.usedOptions, ...ids],
  },
  actions: {
    getAttributes(store) {
      console.info('attributes/getAttributes');
      return new Promise((resolve, reject) => {
        axios.get(`/api/shop/attributes?api_token=${Core.getApiToken()}`).then((response) => {
          store.commit('set', ['attributes', response.data.attributes]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },

    getOptions(store){},




    // getOptions: store => new Promise((resolve, reject) => {
    //   axios.post(`/api/shop/attribute-options/many?api_token=${Core.getApiToken()}`, { ids: _.map(store.state.attributes, 'id') })
    //     .then((response) => {
    //       const sameCollectionOptions = store.state.options.filter(option => option.attribute_id === parseInt(process.env.SAME_COLLECTION_KIT_ATTR_ID));
    //       store.commit('set', ['options', sameCollectionOptions.concat(response.data.options)]);
    //       resolve(response.data);
    //     })
    //     .catch((response) => {
    //       reject(response.data);
    //     });
    // }),

    getUsedAttributes(store) {
      // function param(object) {
      //   var parameters = '';
      //   for (var property in object) {
      //     if (object.hasOwnProperty(property)) {
      //       parameters = object[property].reduce((str, val) => {
      //         return str + '&' + (property + '[]' + '=' + val);
      //       }, '');
      //     }
      //   }
      //
      //   return parameters;
      // }
      //
      // console.info('attributes/getUsedAttributes');
      //
      // console.log('store.state.usedAttributes');
      // console.log(store.state.usedAttributes);
      //
      // const url = `/api/shop/attributes?api_token=${Core.getApiToken()}&${param({ ids: _.map(store.state.usedAttributes) })}`;
      //
      // return new Promise((resolve, reject) => {
      //   axios.get(url)
      //     .then((response) => {
      //       store.commit('set', ['attributes', response.data.attributes]);
      //       resolve(response.data);
      //     }).catch((response) => {
      //       reject(response.data);
      //     });
      // });
    },



    getUsedOptions: store => new Promise((resolve, reject) => {
      axios.post(`/api/shop/attribute-options/many-used?api_token=${Core.getApiToken()}`, { ids: _.map(store.state.usedOptions) })
        .then((response) => {
          store.commit('set', ['options', response.data.options]);
          resolve(response.data);
        })
        .catch((response) => {
          reject(response.data);
        });
    }),

    loadData(store) {
      console.info('attributes/loadData');
      store.dispatch('getUsedAttributes').then(() => {
        store.dispatch('getUsedOptions').then(() => {
          store.dispatch('getAttributes').then(() => {
            store.dispatch('getOptions').catch(e => {
              console.error('Reject attributes/getOptions');
            });
          }).catch(() => {
            console.error('Reject attributes/getAttributes');
          });
        }).catch(() => {
          console.error('Reject attributes/getUsedOptions');
        });
      }).catch(() => {
        console.error('Reject attributes/getUsedAttributes');
      });
    },

    /**
         * Получить все записи аттрибут-опция по конкретной модели
         *
         * @param store
         *
         * @param payload
         * payload must contains: relation_id, relation
         * optional: needAddingToUsed (default: true)
         *
         * @returns {Promise<any>}
         */
    getAttributesAndOptionsByRelation(store, payload) {

      console.log('getAttributesAndOptionsByRelation(store, payload)');
      console.log(payload);

      if (typeof payload.relation !== 'string') {
        console.error('getAttributesAndOptionsByRelation > bad relation: ' + payload.relation);
      }
      if (typeof payload.relation_id !== 'number') {
        console.error('getAttributesAndOptionsByRelation > bad relation_id: ' + payload.relation_id);
      }
      if (typeof payload.needAddingToUsed === 'undefined') {
        payload.needAddingToUsed = true;
      }

      return new Promise((resolve, reject) => {
        axios.get(`/api/shop/attributor/${payload.relation}/${payload.relation_id}?api_token=${Core.getApiToken()}`)
          .then((response) => {
            store.commit('addAttributeRelations', response.data);
            if (payload.needAddingToUsed) {
              const optionIds = [];
              _.map(response.data, 'linked_options').forEach(linkedOptionArray => {
                linkedOptionArray.forEach(linkedOption => {
                  optionIds.push(linkedOption.option_id);
                });
              });

              store.commit('addUsedAttributes', _.uniq(_.map(response.data, 'attribute_id')));
              store.commit('addUsedOptions', _.uniq(optionIds));
            }
            resolve(response.data);
          })
          .catch((response) => {
            reject(response.data);
          });
      });
    },

    /**
         * Привязка свойства к модели
         *
         * @param store
         * @param payload
         * Must contain: relation, relation_id, attribute_id
         */
    createAttributeRelation(store, payload) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/attributor/create?api_token=${Core.getApiToken()}`, payload).then((response) => {
          store.commit('addAttributeRelations', [response.data]);
          store.commit('addUsedAttributes', [payload.attribute_id]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },

    updateAttributeRelation(store, payload) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/attributor/update?api_token=${Core.getApiToken()}`, payload).then((response) => {
          store.commit('set', [
            'attributeRelations',
            [...store.state.attributeRelations.filter(el => {
              return el.id !== response.data.id;
            }), response.data],
          ]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },

    deleteAttributeRelation(store, attributeRelation) {
      return new Promise((resolve, reject) => {
        store.commit('set', [
          'usedAttributes',
          store.state.usedAttributes.filter(el => el !== attributeRelation.attribute_id),
        ]);
        store.commit('set', [
          'usedOptions',
          store.state.usedOptions.filter(option_id => {
            return !_.find(attributeRelation.linked_options, { option_id: option_id });
          }),
        ]);

        axios.post(`/api/shop/attributor/delete?api_token=${Core.getApiToken()}`, { id: attributeRelation.id }).then((response) => {
          store.commit('set', ['attributeRelations', store.state.attributeRelations.filter(el => el.id !== attributeRelation.id)]);

          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },

    createOption(store, payload) {
      // payload must contains attribute_id, name
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/attribute-options/create?api_token=${Core.getApiToken()}`, payload).then((response) => {
          store.commit('set', ['options', [...store.state.options, response.data.option]]);
          resolve(response.data.option);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },

    createAttribute(store, payload) {
      // payload must contains name
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/attributes/create?api_token=${Core.getApiToken()}`, payload).then((response) => {
          store.commit('set', ['attributes', [...store.state.attributes, response.data.attribute]]);
          resolve(response.data.attribute);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },

    /**
         * Односторонняя синхронизация свойств и значений между моделями
         * (например, от поставщика в товар)
         * @param store
         * @param payload
         * payload must contains: relation_source, relation_source_id, relation_target, relation_target_id
         *
         * @returns {Promise<any>}
         */
    syncRelationElements: (store, payload) => new Promise((resolve, reject) => {
      if (typeof payload.relation_source !== 'string') {
        console.error('syncRelationElements > bad relation source: ' + payload.relation_source);
      }
      if (typeof payload.relation_source_id !== 'number') {
        console.error('syncRelationElements > bad relation_source_id: ' + payload.relation_source_id);
      }
      if (typeof payload.relation_target !== 'string') {
        console.error('syncRelationElements > bad target relation: ' + payload.relation_target);
      }
      if (typeof payload.relation_target_id !== 'number') {
        console.error('syncRelationElements > bad relation_target_id: ' + payload.relation_target_id);
      }

      axios.post(`/api/shop/attributor/sync?api_token=${Core.getApiToken()}`, payload).then((response) => {
        resolve(response.data);
      }).catch((response) => {
        reject(response.data);
      });
    }),

    getAttributeOptionRelationPrices: (store, attributeOptionRelation) => new Promise((resolve, reject) => {
      axios.get(`/api/shop/attributor/options/${attributeOptionRelation.id}/prices?api_token=${Core.getApiToken()}`).then((response) => {
        resolve(response.data);
      }).catch((response) => {
        reject(response.data);
      });
    }),

    /**
         * Привязка значения свойства
         * @param store
         * @param payload
         * Payload must contains attribute_relation_id, attribute_id, option_id
         *
         * @returns {Promise<any>}
         */
    createAttributeOptionRelation: (store, payload) => new Promise((resolve, reject) => {
      axios.post(`/api/shop/attributor/options/create?api_token=${Core.getApiToken()}`, payload)
        .then((response) => {
          store.commit('set', [
            'attributeRelations',
            [...store.state.attributeRelations.filter(el => {
              return el.id !== response.data.id;
            }), response.data],
          ]);
          store.commit('addUsedOptions', [payload.option_id]);
          Core.notify('Значение свойства успешно добавлено', { type: 'success' });
          resolve(response.data);
        })
        .catch((response) => {
          Core.notify('Не удалось добавить значение свойства', { type: 'danger' });
          reject(response.data);
        });
    }),

    updateAttributeOptionRelation(store, relationElement) {
      return new Promise((resolve, reject) => {
        axios.post(`/api/shop/attributor/options/update?api_token=${Core.getApiToken()}`, relationElement).then((response) => {
          store.commit('set', [
            'attributeRelations',
            [...store.state.attributeRelations.filter(el => {
              return el.id !== response.data.id;
            }), response.data],
          ]);
          resolve(response.data);
        }).catch((response) => {
          reject(response.data);
        });
      });
    },

    deleteAttributeOptionRelation: (store, payload) => new Promise((resolve, reject) => {
      console.log(payload);
      console.dir(store.state.usedOptions);
      store.commit('set', [
        'usedOptions',
        store.state.usedOptions.filter(option_id => {
          return option_id !== payload.option_id;
        }),
      ]);
      axios.post(`/api/shop/attributor/options/delete?api_token=${Core.getApiToken()}`, payload).then((response) => {
        store.commit('set', [
          'attributeRelations',
          [...store.state.attributeRelations.filter(el => {
            return el.id !== payload.attribute_relation_id;
          }), response.data],
        ]);
        resolve(response.data);
      }).catch((response) => {
        reject(response.data);
      });
    }),

    createRelationElementPrice: (store, price) => new Promise((resolve, reject) => {
      axios.post(`/api/shop/attributor/prices/create?api_token=${Core.getApiToken()}`, price).then((response) => {
        resolve(response.data.price);
      }).catch((response) => {
        reject(response.data);
      });
    }),

    updateRelationElementPrice: (store, price) => new Promise((resolve, reject) => {
      axios.post(`/api/shop/attributor/prices/update?api_token=${Core.getApiToken()}`, price).then((response) => {
        resolve(response.data);
      }).catch((response) => {
        reject(response.data);
      });
    }),

    addAttributeOptionToSupplier: require('./actions/addAttributeOptionToSupplier').default,

    deleteAttributeOptionFromSupplier: require('./actions/deleteAttributeOptionFromSupplier').default,

    addAttributeOptionToProduct: require('./actions/addAttributeOptionToProduct').default,

    deleteAttributeOptionFromProduct: require('./actions/deleteAttributeOptionFromProduct').default,

  },
};
