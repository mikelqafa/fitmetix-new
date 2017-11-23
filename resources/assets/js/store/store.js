import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
export const store = new Vuex.Store({
  state: {
    pageTitle: 'FreeKaaDeal | Best Online Deals, Offers and Coupons',
    postItemList: [],
    theaterPostItem: {}
  },
  getters: {
    postItemList: state => state.postItemList,
    theaterPostItem: state => state.theaterPostItem
  },
  mutations: {
    /* eslint-disable no-param-reassign */
    ADD_POST_ITEM_LIST (state, postItem) {
      state.postItemList.push(postItem)
    },
    REMOVE_POST_ITEM_LIST (state, index) {
      state.slider = slider
    },
    RESET_POST_ITEM_LIST (state) {
      state.postItemList = []
    },
    SET_POST_FOR_THEATER (state, postItems) {
      state.postItemList = postItems
    },
    SET_THEATER_ITEM (state, postObj) {
      Vue.set(state.theaterPostItem, 'postIndex', postObj.postIndex)
      Vue.set(state.theaterPostItem, 'imageIndex', postObj.imageIndex)
    },
    SET_POST_META (state, data) {
      // state.slider = slider
      let obj = {
        postCommentsCount: data.postCommentsCount,
        postLikesCount: data.postLikesCount,
        userLiked: data.userLiked,
        userCommented: data.userCommented
      }
      Vue.set(state.postItemList[data.index], 'postMetaInfo', obj)
    },
    SET_POST_META_COUNT (state, data) {
      // state.slider = slider
      Vue.set(state.postItemList[data.index].postMetaInfo, 'postCommentsCount', data.postCommentsCount)
    },
    SET_POST_META_LIKES_COUNT (state, data) {
      Vue.set(state.postItemList[data.index].postMetaInfo, 'postLikesCount', data.postLikesCount)
    },
    SET_POST_META_USER_LIKED (state, data) {
      Vue.set(state.postItemList[data.index].postMetaInfo, 'userLiked', data.userLiked)
    },
    SET_POST_META_USER_COMMENTED (state, data) {
      Vue.set(state.postItemList[data.index].postMetaInfo, 'userCommented', data.userCommented)
    },
    SET_POST_WHO_LIKES (state, data) {
      let whoLikes = {
        itemList: [ {} ],
        hasMore: true
      }
      Vue.set(state.postItemList[data.index].whoLikes, 'postCommentsCount', data.postCommentsCount)
    }
  },
  actions: {
    showTheater: (context) => {
      alert()
    }
  }
})
