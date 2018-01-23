// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Post from './components/Post'
import PostSingle from './components/PostSingle'
import GalleryPostByHLU from './components/GalleryPostByHLU'
import EventPostByHLU from './components/EventPostByHLU'
import createPost from './components/createPost'
import ProfilePictureDialog from './components/ProfilePictureDialog'
import singleEventView from './components/child/singleEventView'
import AppSingleChat from './components/child/singleChat'
import AppNotification from './components/AppNotification'
import AppNotificationAll from './components/AppNotificationAll'
import postDialogOption from './components/DialogOption'
import commentDialogOption from './components/CommentDialogOption'
import suggestionUser from './components/suggestionUser'
import searchAppVue from './components/searchApp'
import makeEvent from './components/createEvent'
import VueTimeago from 'vue-timeago'
import VueAwesomeSwiper from 'vue-awesome-swiper'
// import AppEventCalendar from './components/appEventCalendar'
import AppEventList from './components/appEventList'
import AppUserFollowList from './components/child/userFollowList'
import AppUserFollowingList from './components/child/userFollowingList'
import ProfileOptionDialog from './components/ProfileOptionDialog'
import {store} from './store/store'
import VueClip from 'vue-clip'
import directMsg from './components/child/sendDirect'
import VueChatScroll from 'vue-chat-scroll'
require('swiper/dist/css/swiper.css')

Vue.use(VueAwesomeSwiper)
Vue.use(VueClip)
Vue.use(VueChatScroll)
Vue.use(VueTimeago, {
  name: 'timeago',
  locale: 'en-US',
  locales: {
    'en-US': require('./en-US.json')
  }
})
Vue.config.productionTip = true

/* eslint-disable no-new */

window.timeLine = new Vue({
  el: '#app-timeline',
  store,
  components: {
    'app-post': Post,
    'app-post-single': PostSingle,
    'app-gallery-hlu': GalleryPostByHLU,
    'app-event-hlu': EventPostByHLU,
    'app-post-option': postDialogOption,
    'app-comment-option': commentDialogOption,
    'app-event-list': AppEventList,
    'app-profile-option': ProfileOptionDialog,
    'app-picture-option': ProfilePictureDialog
  }
})
window.sendDirect = new Vue({
  el: '#app-send-direct',
  store,
  components: {
    'app-send-direct': directMsg
  }
})
window.appNotification = new Vue({
  el: '#app-notification',
  store,
  components: {
    'app-notification': AppNotification
  }
})

window.singleEvent = new Vue({
  el: '#single-event-view',
  store,
  components: {
    'single-event-view': singleEventView,
    'suggestion-user': suggestionUser
  }
})

window.userFollowList = new Vue({
  el: '#user-follow-view',
  store,
  components: {
    'user-follow-list': AppUserFollowList,
    'user-following-list': AppUserFollowingList
  }
})

window.searchApp = new Vue({
  el: '#app-search',
  store,
  components: {
    'app-search': searchAppVue
  }
})

window.appNotificationAll = new Vue({
  el: '#app-notification-all',
  store,
  components: {
    'app-notification-all': AppNotificationAll
  }
})
window.singleChatApp = new Vue({
  el: '#app-single-chat',
  store,
  components: {
    'app-single-chat': AppSingleChat
  }
})

window.createPost = new Vue({
  el: '#app-create-post',
  components: {
    'app-create-post': createPost,
    'app-make-event': makeEvent
  }
})