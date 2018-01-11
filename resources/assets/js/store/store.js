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
    unreadNotifications: 0,
    chatPusher: null,
    MessageChannel: '',
    currentConversation: {
      conversationMessages: {},
      user: []
    },
    conversations: [],
    selfUserObj: ''
  },
  getters: {
    currentConversation: state => state.currentConversation,
    conversations: state => state.conversations,
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
    CHANGE_NOTIFICATION_SEEN (state, obj) {
      Vue.set(state.notification[obj.index], 'seen', obj.changed)
    },
    SET_URN (state, n) {
      state.unreadNotifications = n
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
      if(!data.seen) {
        store.commit('SET_URN', state.unreadNotifications + 1)
        materialSnackBar({messageText: data.description, autoClose: true, timeout: 5000 })
        $.playSound(theme_url + '/sounds/notification');
      }
      if(data.notified_from.username != current_username) {
        state.notification.unshift(data)
      }
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
      for(let i=0; i <data.postComments.length; i++) {
        state.postItemList[data.postIndex].postComments.push(data.postComments[i])
      }
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
    },
    ADD_CONVERSATION_MESSAGE(state, data) {
      state.currentConversation.conversationMessages.data.push(data.message);
    },
    SET_CONVERSATION(state, data) {
      state.conversations = data.message
    },
    ADD_CONVERSATION(state, data) {
      state.conversations.data.unshift(data)
    },
    SET_CONVERSATION_(state, data) {
      if( state.currentConversation.conversationMessages.data !== undefined ) {
        state.currentConversation.conversationMessages.data.push(data.message);
      } else {
        Vue.set(state.currentConversation.conversationMessages, 'data', data.message)
        // state.currentConversation.conversationMessages.data.push(data.message);
      }
    },
    SET_CURRENT_CONVERSATION(state, data) {
      state.currentConversation = data;
    },
    SET_CURRENT_CONVERSATION_OBJ(state, data) {
      Vue.set(state.currentConversation, data.obj, data.data)
    }
  },
  actions: {
    markNotificationsRead: (context, data) =>{
      if(!context.state.notification.length) {
        return
      }
      axios.post(base_url + 'ajax/mark-all-notifications').then(function (response) {
        if(response.status == 200) {
          context.commit('SET_URN', 0)
          $.map(context.state.notification, function (notification, key) {
            context.commit('CHANGE_NOTIFICATION_SEEN', {index: key, changed: true});
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
    },
    subscribeToPrivateMessageChannel: (context, receiverUsername) => {
      context.state.chatPusher = new Pusher(pusherConfig.PUSHER_KEY, {
        encrypted: true,
        auth: {
          headers: {
            'X-CSRF-Token': pusherConfig.token
          },
          params: {
            username: receiverUsername
          }
        }
      });
      context.state.MessageChannel = context.state.chatPusher.subscribe(receiverUsername + '-message-created');
      context.state.MessageChannel.bind('App\\Events\\MessagePublished', function (data) {
        data.message.user = data.sender;
        if (context.state.currentConversation.id == data.message.thread_id) {
          context.state.currentConversation.conversationMessages.data.push(data.message);
          //TODO mutation will work?
        }
        else {
          let indexes = $.map(context.state.conversations.data, function (thread, key) {
            if (thread.id == data.message.thread_id) {
              return key;
            }
          });
          if (indexes != '') {
            //context.state.conversations.data[indexes[0]].unread = true;
            //context.state.conversations.data[indexes[0]].lastMessage = data.message;
          }  else {
            let _token = $("meta[name=_token]").attr('content')
            axios({
              method: 'post',
              responseType: 'json',
              url: base_url + 'ajax/get-message/' + data.message.thread_id,
              data: {
                _token: _token
              }
            }).then(function (response) {
              if (response.status == 200) {
                //context.state.conversations.data.unshift(response.data.data);
              }
            }).catch(function (error) {
              console.log(error)
            })
          }
        }
      });
    },
    getConversations: (context) => {
      let _token = $("meta[name=_token]").attr('content')
      axios({
        method: 'post',
        responseType: 'json',
        url: base_url + 'ajax/get-messages',
        data: {
          _token: _token
        }
      }).then(function (response) {
        if (response.status == 200) {
          context.commit('SET_CONVERSATION', {message: response.data.data})
          context.dispatch('showConversation', {conversation: context.state.conversations.data[0], byTap:false})
        }
      }).catch(function (error) {
        console.log(error)
      })
    },
    autoScroll: function (context, el) {
      console.log(el)
      $(el).animate({scrollTop: $(el)[0].scrollHeight + 600}, 2000);
    },
    postMessage: (context, data) => {
      let messageBody = data.nonHtmlContent;
      let _token = $("meta[name=_token]").attr('content')
      let preData = {
        body: messageBody,
        user: context.state.selfUserObj,
        user_id: user_id
      }
      context.commit('ADD_CONVERSATION_MESSAGE', {message:preData})
      let index = context.state.currentConversation.conversationMessages.data.length - 1
      setTimeout(function () {
        context.dispatch('autoScroll', ('.coversations-thread'));
      }, 100)
      axios({
        method: 'post',
        responseType: 'json',
        url: base_url + 'ajax/post-message/' + context.state.currentConversation.id,
        data: {
          _token: _token,
          message: messageBody
        }
      }).then(function (response) {
        if (response.status == 200) {
          context.state.currentConversation.conversationMessages.data[index] = response.data.data;
        }
      }).catch(function (error) {
        console.log(error)
      })
    },
    postNewConversation: (context, data) => {
      if (this.recipients.length) {
        this.$http.post(base_url + 'ajax/create-message', {
          message: this.messageBody,
          recipients: this.recipients
        }).then(function (response) {
          if (response.status) {
            vm = this;

            newThread = JSON.parse(response.body).data;
            indexes = $.map(vm.conversations.data, function (thread, key) {
              if (thread.id == newThread.id) {
                return key;
              }
            });

            if (indexes != '') {
              vm.conversations.data[indexes[0]].unread = true;
              vm.conversations.data[indexes[0]].lastMessage = newThread.lastMessage;
            }
            else {
              vm.conversations.data.unshift(response.data.data);
            }

            $('#messageReceipient').focus();
            this.recipients = [];
            this.newConversation = false;
            this.messageBody = "";
            this.showConversation(vm.conversations.data[0]);
            setTimeout(function () {
              vm.autoScroll('.coversations-thread');
            }, 100)
          }
        });
      }
    },
    showConversation: (context, data) => {
      data.byTap ? $('.ft-chat--list-wrapper').removeClass('is-list-open') : ''
      if (data.conversation && data.conversation !== undefined) {
        if (data.conversation.id != context.state.currentConversation.id) {
          data.conversation.unread = false;
          let _token = $("meta[name=_token]").attr('content')
          axios({
            method: 'post',
            responseType: 'json',
            url: base_url + 'ajax/get-conversation/' + data.conversation.id,
            data: {
              _token: _token
            }
          }).then(function (response) {
            if (response.status == 200) {
              //that.currentConversation = response.data.data;
              context.commit('SET_CURRENT_CONVERSATION', response.data.data)
              context.commit('SET_CURRENT_CONVERSATION_OBJ', {obj: 'user', data: data.conversation.user})
              /*setTimeout(function () {
                that.autoScroll('.coversations-thread');
              }, 100)*/
            }
          }).catch(function (error) {
            console.log(error)
          })
        }
      }
    },
    getMoreConversationMessages: (context) => {
      if (context.state.currentConversation.conversationMessages.data.length < context.state.currentConversation.conversationMessages.total) {
        let _token = $("meta[name=_token]").attr('content')
        axios({
          method: 'post',
          responseType: 'json',
          url: context.state.currentConversation.conversationMessages.next_page_url,
          data: {
            _token: _token
          }
        }).then(function (response) {
          if (response.status == 200) {
            var latestConversations = response.data.data;
            context.state.currentConversation.conversationMessages.last_page = latestConversations.conversationMessages.last_page;
            context.state.currentConversation.conversationMessages.next_page_url = latestConversations.conversationMessages.next_page_url;
            context.state.currentConversation.conversationMessages.per_page = latestConversations.conversationMessages.per_page;
            context.state.currentConversation.conversationMessages.prev_page_url = latestConversations.conversationMessages.prev_page_url;
            $.each(latestConversations.data, function (i, latestConversation) {
              context.commit('ADD_CONVERSATION_MESSAGE', {message: latestConversation})
            });
          }
        }).catch(function (error) {
          console.log(error)
        })
      }
    },
    getMoreConversations:(context) => {
      if (context.state.conversations.data.length < context.state.conversations.total) {
        let _token = $("meta[name=_token]").attr('content')
        axios({
          method: 'post',
          responseType: 'json',
          url: context.state.conversations.next_page_url,
          data: {
            _token: _token
          }
        }).then(function (response) {
          if (response.status == 200) {
            var latestConversations = response.data.data;
            context.state.conversations.last_page = latestConversations.last_page;
            context.state.conversations.next_page_url = latestConversations.next_page_url;
            context.state.conversations.per_page = latestConversations.per_page;
            context.state.conversations.prev_page_url = latestConversations.prev_page_url;
            $.each(latestConversations.data, function (i, latestConversation) {
              context.commit('ADD_CONVERSATION', latestConversation)
            });
          }
        }).catch(function (error) {
          console.log(error)
        })
      }
    },
    showNewConversation: function () {
      this.newConversation = true;
      this.currentConversation = {
        user: []
      };
      $('#messageReceipient').focus();
      let that = this;
      setTimeout(function () {
        that.toggleUsersSelectize();
      }, 10);
    },
    setUserObj: (context) => {
      let _token = $("meta[name=_token]").attr('content')
      axios({
        method: 'post',
        responseType: 'json',
        url: base_url + 'get-self-timeline',
        data: {
          _token: _token
        }
      }).then(function (response) {
        if (response.status == 200) {
          context.state.selfUserObj = response.data[0].user_timeline
        }
      }).catch(function (error) {
        console.log(error)
      })
    }
  }
})
