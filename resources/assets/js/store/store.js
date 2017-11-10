import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
  state: {
    showBanner: false,
  },
  getters: {
    showBanner: state => {
      return state.showBanner
    }
  },
  mutations: {},
  actions: {}
})
