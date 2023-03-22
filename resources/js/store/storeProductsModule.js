// import Core from "../core";

// import _ from "lodash";

export default {
// module.exports = {
  namespaced: true,
  state: {
    all: [],
    filteredBy: {
      categories: [],
      suppliers: [],
    },
  },
  getters: {

  },
  mutations: {
    set(state, payload) {
      state[payload[0]] = payload[1];
    },
    setFilter(state, payload) {
      state.filteredBy[payload[0]] = payload[1];
    },
  },
  actions: {
  },
};
