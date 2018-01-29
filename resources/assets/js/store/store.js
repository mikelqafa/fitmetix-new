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
    conversations: {},
    recipients: [],
    selfUserObj: ''
  },
  getters: {
    unreadMsg: (state) => {
      let hasMsgNotification = false
      if(state.conversations.data !== undefined) {
        for(let i = 0;i< state.conversations.data.length;i++){
          if(state.conversations.data[i].unread) {
            hasMsgNotification = true
            break
          }
        }
      }
      return hasMsgNotification
    },
    currentConversation: state => state.currentConversation,
    recipients: state => state.recipients,
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
      if (postItem.postFrom !== undefined) {
        state.postItemList.unshift(postItem.data)
      } else {
        state.postItemList.push(postItem)
      }
    },
    ADD_SINGLE_POST_ITEM (state, postItem) {
      state.postItemList.push(postItem.data)
    },
    ADD_NEW_NOTIFICATION (state, data) {
      if (!data.seen) {
        store.commit('SET_URN', state.unreadNotifications + 1)
        materialSnackBar({messageText: data.description, autoClose: true, timeout: 5000})
        $.playSound(theme_url + '/sounds/notification');
      }
      if (data.notified_from.username != current_username) {
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
      if (state.postItemList[data.postIndex].whoLikes !== undefined) {
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
      for (let i = 0; i < data.postComments.length; i++) {
        state.postItemList[data.postIndex].postComments.push(data.postComments[i])
      }
    },
    REMOVE_POST_COMMENT (state, data) {
      state.postItemList[data.postIndex].postComments.splice(data.commentIndex, 1)
    },
    ADD_POST_COMMENT_ONLY (state, data) {
      if (state.postItemList[data.postIndex].postComments !== undefined && state.postItemList[data.postIndex].postComments.length !== 0) {
        state.postItemList[data.postIndex].postComments.unshift(data.postComments)
      } else {
        Vue.set(state.postItemList[data.postIndex], 'postComments', [data.postComments])
      }
    },
    SET_POST_COMMENT_INTERACT (state, data) {
      Vue.set(state.postItemList[data.postIndex], 'commentInteract', data.commentInteract)
    },
    SET_EVENT_STATUS (state, data) {
      Vue.set(state.postItemList[data.postIndex].event[0], data.name, data.status)
    },
    SET_EVENT_WHO (state, data) {
      Vue.set(state.eventWho, 'eventId', data.eventId)
    },
    SET_OPTIONS_COMMENT (state, data) {
      state.commentOption = data
    },
    ADD_CONVERSATION_MESSAGE(state, data) {
      state.currentConversation.conversationMessages.data.push(data.message);
    },
    UNSHIFT_CONVERSATION_MESSAGE(state, data) {
      state.currentConversation.conversationMessages.data.unshift(data.message);
    },
    SET_CONVERSATION(state, data) {
      state.conversations = data.message
    },
    ADD_CONVERSATION(state, data) {
      state.conversations.data.unshift(data)
    },
    SET_CONVERSATION_(state, data) {
      if (state.currentConversation.conversationMessages.data !== undefined) {
        state.currentConversation.conversationMessages.data.push(data.message);
      } else {
        Vue.set(state.currentConversation.conversationMessages, 'data', data.message)
        // state.currentConversation.conversationMessages.data.push(data.message);
      }
    },
    SET_CURRENT_CONVERSATION(state, data) {
      state.currentConversation = data;
      let msgAry = data.conversationMessages.data
      msgAry = msgAry.reverse()
      state.currentConversation.conversationMessages.data = msgAry
    },
    SET_CURRENT_CONVERSATION_OBJ(state, data) {
      Vue.set(state.currentConversation, data.obj, data.data)
    }
  },
  actions: {
    markNotificationsRead: (context, data) => {
      axios.post(base_url + 'ajax/mark-all-notifications').then(function (response) {
        if (response.status == 200) {
          context.commit('SET_URN', 0)
          $.map(context.state.notification, function (notification, key) {
            context.commit('CHANGE_NOTIFICATION_SEEN', {index: key, changed: true});
          });
        }
      });
    },
    likePostByPusher: (context, data) => {
      if (data.type !== undefined && (data.type === 'like_post' || data.type === 'unlike_post')) {
        let index = -1
        for (let i = 0; i < context.state.postItemList.length; i++) {
          if (data.post_id == context.state.postItemList[i].id) {
            index = i
            break
          }
        }
        if (index > -1) {
          let likeCount = context.state.postItemList[index].postMetaInfo.postLikesCount
          console.log(likeCount)
          likeCount = parseInt(likeCount)
          data.type === 'like_post' ? likeCount++ : likeCount--
          console.log('hola', likeCount)
          context.commit('SET_POST_META_LIKES_COUNT', {postIndex: index, postLikesCount: likeCount})
          if (data.type == 'like_post' && !data.seen) {
            if (likeCount > 2) {
              materialSnackBar({
                messageText: data.notified_from.name + ' and ' + likeCount - 1 + ' others like your post',
                autoClose: true,
                timeout: 5000
              })
              $.playSound(theme_url + '/sounds/notification');
            } else {
              materialSnackBar({messageText: data.description, autoClose: true, timeout: 5000})
              $.playSound(theme_url + '/sounds/notification');
            }
          }
        } else {
          if (data.type == 'like_post' && !data.seen) {
            materialSnackBar({messageText: data.description, autoClose: true, timeout: 5000})
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
          // check user at conversation page
          if($('ft-chat-box--desktop').length) {
            // conversation page open
            if(!$('.chat-user-list-wrapper').hasClass('is-open')) {
              let indexes = $.map(context.state.conversations.data, function (thread, key) {
                if (thread.id == data.message.thread_id) {
                  return key;
                }
              });
              context.state.conversations.data[indexes[0]].unread = true;
              context.state.conversations.data[indexes[0]].lastMessage = data.message;
              $.playSound(theme_url + '/sounds/notification');
            } else {
              // means box is open
              let indexes = $.map(context.state.conversations.data, function (thread, key) {
                if (thread.id == data.message.thread_id) {
                  return key;
                }
              });
              context.state.conversations.data[indexes[0]].lastMessage = data.message
              if(context.state.currentConversation.id != data.message.thread_id || $('.ft-chat--list-wrapper').hasClass('is-list-open')) {
                context.state.conversations.data[indexes[0]].unread = true;
                $.playSound(theme_url + '/sounds/notification');
              }
            }
          }
          else {
            // using chat box
            if(!$('.ft-chat-box--docker').hasClass('ft-chat-box--open')) {
              let indexes = $.map(context.state.conversations.data, function (thread, key) {
                if (thread.id == data.message.thread_id) {
                  return key;
                }
              });
              context.state.conversations.data[indexes[0]].unread = true;
              context.state.conversations.data[indexes[0]].lastMessage = data.message;
              $.playSound(theme_url + '/sounds/notification');
            }
            else {
              // means box is open
              let indexes = $.map(context.state.conversations.data, function (thread, key) {
                if (thread.id == data.message.thread_id) {
                  return key;
                }
              });
              context.state.conversations.data[indexes[0]].lastMessage = data.message
              console.log(context.state.currentConversation.id, data.message.thread_id)
              if(context.state.currentConversation.id != data.message.thread_id || $('.ft-chat--list-wrapper').hasClass('is-list-open')) {
                context.state.conversations.data[indexes[0]].unread = true;
                $.playSound(theme_url + '/sounds/notification');
              }
            }
          }
        }
        else {
          let indexes = $.map(context.state.conversations.data, function (thread, key) {
            if (thread.id == data.message.thread_id) {
              return key;
            }
          });
          if (indexes != '') {
            context.state.conversations.data[indexes[0]].unread = true;
            $.playSound(theme_url + '/sounds/notification');
            context.state.conversations.data[indexes[0]].lastMessage = data.message;
          } else {
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
                context.state.conversations.data.unshift(response.data.data);
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
          context.dispatch('showConversation', {conversation: context.state.conversations.data[0], byTap: false})
        }
      }).catch(function (error) {
        console.log(error)
      })
    },
    postMessage: (context, data) => {
      let messageBody = data.nonHtmlContent;
      let _token = $("meta[name=_token]").attr('content')
      let preData = {
        body: messageBody,
        user: context.state.selfUserObj,
        user_id: user_id
      }
      context.commit('ADD_CONVERSATION_MESSAGE', {message: preData})
      let indexes = $.map(context.state.conversations.data, function (thread, key) {
        if (thread.id == context.state.currentConversation.id) {
          return key;
        }
      });
      let index = context.state.currentConversation.conversationMessages.data.length - 1
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
          if (indexes != '') {
            context.state.conversations.data[indexes[0]].lastMessage = response.data.data;
            context.state.conversations.data[indexes[0]].unread = false
          }
          context.state.currentConversation.conversationMessages.data[index] = response.data.data;
        }
      }).catch(function (error) {
        console.log(error)
      })
    },
    sendDirectMessage: (context, data) => {
      // if(showBoxIf)
      context.state.recipients = []
      context.state.recipients.push(data.recipients)
      context.dispatch('postNewConversation', data.message)
    },
    postNewConversation: (context, data) => {
      if (context.state.recipients.length) {
        axios({
          method: 'post',
          responseType: 'json',
          url: base_url + 'ajax/create-message',
          data: {
            _token: _token,
            message: data,
            recipients: context.state.recipients.join(',')
          }
        }).then(function (response) {
          if (response.status == 200) {
            let newThread = response.data.data
            let indexes = $.map(context.state.conversations.data, function (thread, key) {
              if (thread.id == newThread.id) {
                return key;
              }
            });
            if (indexes != '') {
              context.state.conversations.data[indexes[0]].unread = true;
              context.state.conversations.data[indexes[0]].lastMessage = newThread.lastMessage;
            }
            else {
              context.commit('ADD_CONVERSATION', response.data.data)
            }
            // $('#messageReceipient').focus();
            // TODO this.recipients = [];
            context.state.recipients = []
            $('.ft-chat-box.ft-chat-box--docker').addClass('ft-chat-box--open')
            context.dispatch('showConversation', {conversation:context.state.conversations.data[0], byTap: true})
          }
        }).catch(function (error) {
          console.log(error)
        })
      }
    },
    setUnread:(context, data) => {
      context.state.ureadMsg = false
    },
    showConversation: (context, data) => {
      $('.ft-dock-wrapper').removeClass('hidden')
      data.byTap ? $('.ft-chat--list-wrapper').removeClass('is-list-open') : ''
      if (data.conversation && data.conversation !== undefined) {
        let indexes = $.map(context.state.conversations.data, function (thread, key) {
          if (thread.id == data.conversation.id) {
            return key;
          }
        });
        if(indexes !== '') {
          context.state.conversations.data[indexes[0]].unread = false;
        }
        if (data.conversation.id != context.state.currentConversation.id || data.byTap) {
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
            $.each(latestConversations.conversationMessages.data, function (i, latestConversation) {
              context.commit('UNSHIFT_CONVERSATION_MESSAGE', {message: latestConversation})
            });
          }
        }).catch(function (error) {
          console.log(error)
        })
      }
    },
    getMoreConversations: (context) => {
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
            var latestConversations = response.data.data
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
});
