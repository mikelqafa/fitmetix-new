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
import searchAppDesktopVue from './components/searchAppDesktopVue'
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
import VueI18n from 'vue-i18n'
import { messages} from './lang/index'
require('swiper/dist/css/swiper.css')

Vue.use(VueAwesomeSwiper)
Vue.use(VueClip)
Vue.use(VueI18n)
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
let local_ = getLang()
const i18n = new VueI18n({
  locale: local_, // set locale
  messages
})

window.timeLine = new Vue({
  el: '#app-timeline',
  store,
  i18n,
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
  i18n,
  components: {
    'app-send-direct': directMsg
  }
})
window.appNotification = new Vue({
  el: '#app-notification',
  store,
  i18n,
  components: {
    'app-notification': AppNotification
  }
})

window.singleEvent = new Vue({
  el: '#single-event-view',
  store,
  i18n,
  components: {
    'single-event-view': singleEventView,
    'suggestion-user': suggestionUser
  }
})

window.userFollowList = new Vue({
  el: '#user-follow-view',
  store,
  i18n,
  components: {
    'user-follow-list': AppUserFollowList,
    'user-following-list': AppUserFollowingList
  }
})

window.searchApp = new Vue({
  el: '#app-search',
  store,
  i18n,
  components: {
    'app-search': searchAppVue
  }
})
window.searchDesktopApp = new Vue({
  el: '#app-search-desktop',
  store,
  i18n,
  components: {
    'app-search-desktop': searchAppDesktopVue
  }
})

window.appNotificationAll = new Vue({
  el: '#app-notification-all',
  store,
  i18n,
  components: {
    'app-notification-all': AppNotificationAll
  }
})
window.singleChatApp = new Vue({
  el: '#app-single-chat',
  store,
  i18n,
  components: {
    'app-single-chat': AppSingleChat
  }
})

window.createPost = new Vue({
  el: '#app-create-post',
  i18n,
  components: {
    'app-create-post': createPost,
    'app-make-event': makeEvent
  }
})