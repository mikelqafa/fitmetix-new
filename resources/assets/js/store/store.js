import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
export const store = new Vuex.Store({
  state: {
    pageTitle: '',
    postItemList: [],
    theaterPostItem: {},
    sharePostItem: {},
    optionMenuPostItem: {},
    eventWho: {},
    postWhoLikes: {},
    commentOption: {},
    pusher: null,
    notification: [],
    unreadNotifications: 0
  },
  getters: {
    postItemList: state => state.postItemList,
    notification: state => state.notification,
    unreadNotifications: state => state.unreadNotifications,
    theaterPostItem: state => state.theaterPostItem,
    sharePostItem: state => state.sharePostItem,
    optionMenuPostItem: state => state.optionMenuPostItem,
    postWhoLikes: state => state.postWhoLikes,
    pusher: state => state.pusher,
    eventWho: state => state.eventWho,
    commentOption: state => state.commentOption,
  },
  mutations: {
    /* eslint-disable no-param-reassign */
    SET_PUSHER (state, pusher) {
      state.pusher = pusher
    },
    SET_OPTIONS_MENU_ITEM (state, postObj) {
      Vue.set(state.optionMenuPostItem, 'postIndex', postObj.postIndex)
    },
    CHANGE_NOTIFICATION_TYPE (state, obj) {
      Vue.set(state.notification[obj.index], 'type', obj.changed)
    },
    ADD_POST_ITEM_LIST (state, postItem) {
      if(postItem.postFrom !== undefined) {
        state.postItemList.unshift(postItem.data)
      } else {
        state.postItemList.push(postItem)
      }
    },
    ADD_SINGLE_POST_ITEM (state, postItem) {
        state.postItemList.push(postItem.data)
    },
    ADD_NEW_NOTIFICATION (state, data) {
      if(!data.notification.seen) {
        state.unreadNotifications = state.unreadNotifications + 1;
        materialSnackBar({messageText: data.notification.description, autoClose: true, timeout: 5000 })
        $.playSound(theme_url + '/sounds/notification');
      }
      state.notification.unshift(data)
    },
    ADD_NOTIFICATION (state, data) {
        state.notification.push(data)
    },
    REMOVE_POST_ITEM_LIST (state, index) {
      state.postItemList.splice(index, 1)
    },
    RESET_POST_ITEM_LIST (state) {
      state.postItemList = []
    },
    EDIT_POST_ITEM (state, obj) {
      Vue.set(state.postItemList[obj.index], 'description', obj.description)
    },
    REPLACE_POST_ITEM(state, obj) {
      Vue.set(state.postItemList, obj.index, obj.data)
    },
    SET_POST_FOR_THEATER (state, postItems) {
      state.postItemList = postItems
    },
    SET_THEATER_ITEM (state, postObj) {
      Vue.set(state.theaterPostItem, 'postIndex', postObj.postIndex)
      Vue.set(state.theaterPostItem, 'imageIndex', postObj.imageIndex)
    },
    SET_POST_SHARE_ITEM (state, postObj) {
      Vue.set(state.sharePostItem, 'postIndex', postObj.postIndex)
    },
    SET_POST_META (state, data) {
      // state.slider = slider
      let obj = {
        postCommentsCount: data.postCommentsCount,
        postLikesCount: data.postLikesCount,
        userLiked: data.userLiked,
        userCommented: data.userCommented
      }
      Vue.set(state.postItemList[data.postIndex], 'postMetaInfo', obj)
    },
    SET_POST_META_COUNT (state, data) {
      Vue.set(state.postItemList[data.postIndex].postMetaInfo, 'postCommentsCount', data.postCommentsCount)
    },
    SET_POST_META_LIKES_COUNT (state, data) {
      Vue.set(state.postItemList[data.postIndex].postMetaInfo, 'postLikesCount', data.postLikesCount)
    },
    SET_POST_META_USER_LIKED (state, data) {
      Vue.set(state.postItemList[data.postIndex].postMetaInfo, 'userLiked', data.userLiked)
    },
    SET_POST_META_USER_COMMENTED (state, data) {
      Vue.set(state.postItemList[data.postIndex].postMetaInfo, 'userCommented', data.userCommented)
    },
    SET_WHO_LIKES_ITEM(state, data) {
      Vue.set(state.postWhoLikes, 'postIndex', data.postIndex)
    },
    SET_POST_WHO_LIKES (state, data) {
      if(state.postItemList[data.postIndex].whoLikes !== undefined) {
        Vue.set(state.postItemList[data.postIndex].whoLikes, 'hasMore', data.hasMore)
        Vue.set(state.postItemList[data.postIndex].whoLikes, 'offset', data.offset)
        state.postItemList[data.postIndex].whoLikes.itemList.push(data.itemList)
      } else {
        let whoLikes = {
          itemList: data.itemList,
          hasMore: data.hasMore,
          offset: data.offset
        }
        Vue.set(state.postItemList[data.postIndex], 'whoLikes', whoLikes)
      }
    },
    SET_POST_COMMENT (state, data) {
      Vue.set(state.postItemList[data.postIndex], 'postComments', data.postComments)
      Vue.set(state.postItemList[data.postIndex], 'commentHasMore', data.hasMore)
      Vue.set(state.postItemList[data.postIndex], 'commentOffset', data.offset)
    },
    ADD_POST_COMMENT (state, data) {
      Vue.set(state.postItemList[data.postIndex], 'commentHasMore', data.hasMore)
      Vue.set(state.postItemList[data.postIndex], 'commentOffset', data.offset)
      state.postItemList[data.postIndex].postComments.unshift(data.postComments)
    },
    ADD_POST_COMMENT_ONLY (state, data) {
      if(state.postItemList[data.postIndex].postComments !== undefined && state.postItemList[data.postIndex].postComments.length !== 0  ) {
        state.postItemList[data.postIndex].postComments.unshift(data.postComments)
      } else {
        Vue.set(state.postItemList[data.postIndex], 'postComments', [data.postComments])
      }
    },
    SET_POST_COMMENT_INTERACT (state, data) {
      Vue.set(state.postItemList[data.postIndex],'commentInteract', data.commentInteract)
    },
    SET_EVENT_STATUS (state, data) {
      Vue.set(state.postItemList[data.postIndex].event[0], data.name, data.status)
    },
    SET_EVENT_WHO (state, data) {
      Vue.set(state.eventWho, 'eventId', data.eventId)
    },
    SET_OPTIONS_COMMENT (state, data) {
      state.commentOption = data.comment
    }
  },
  actions: {
    markNotificationsRead: (context, data) =>{
      if(!context.state.notification.length) {
        return
      }
      axios.post(base_url + 'ajax/mark-all-notifications').then(function (response) {
        if(response.status == 200) {
          context.state.unreadNotifications = 0;
          $.map(context.state.notification, function (notification, key) {
            context.state.notification[key].seen = true;
          });
        }
      });
    },
    likePostByPusher: (context, data) => {
      console.log(data)
      if(data.type !== undefined && (data.type === 'like_post' || data.type === 'unlike_post')) {
        let index = -1
        for(let i=0; i < context.state.postItemList.length; i++) {
          if(data.post_id == context.state.postItemList[i].id ) {
            index = i
            break
          }
        }
        if(index>-1) {
          let likeCount = context.state.postItemList[index].postMetaInfo.postLikesCount
          console.log(likeCount)
          likeCount = parseInt(likeCount)
          data.type === 'like_post' ? likeCount++ : likeCount--
          console.log('hola', likeCount)
          context.commit('SET_POST_META_LIKES_COUNT', {postIndex: index, postLikesCount:likeCount})
          if(data.type== 'like_post' && !data.seen) {
            if(likeCount > 2) {
              materialSnackBar({messageText: data.notified_from.name +' and '+ likeCount-1  +' others like your post', autoClose: true, timeout: 5000 })
              $.playSound(theme_url + '/sounds/notification');
            } else {
              materialSnackBar({messageText: data.description, autoClose: true, timeout: 5000 })
              $.playSound(theme_url + '/sounds/notification');
            }
          }
        } else {
          if(data.type== 'like_post' && !data.seen) {
            materialSnackBar({messageText: data.description, autoClose: true, timeout: 5000 })
            $.playSound(theme_url + '/sounds/notification');
          }
        }
      }
    }
  }
})
