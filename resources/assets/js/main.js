// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Post from './components/Post'
import PostSingle from './components/PostSingle'
import GalleryPostByHLU from './components/GalleryPostByHLU'
import EventPostByHLU from './components/EventPostByHLU'
import createPost from './components/createPost'
import ProfilePictureDialog from './components/ProfilePictureDialog'
//import editPost from './components/child/editPost'
import AppNotification from './components/AppNotification'
import AppNotificationAll from './components/AppNotificationAll'
import postDialogOption from './components/DialogOption'
import commentDialogOption from './components/CommentDialogOption'
import VueTimeago from 'vue-timeago'
import VueAwesomeSwiper from 'vue-awesome-swiper'
// import AppEventCalendar from './components/appEventCalendar'
import AppEventList from './components/appEventList'
import ProfileOptionDialog from './components/ProfileOptionDialog'
import {store} from './store/store'
import VueClip from 'vue-clip'
require('swiper/dist/css/swiper.css')

Vue.use(VueAwesomeSwiper)
Vue.use(VueClip)
Vue.use(VueTimeago, {
  name: 'timeago', // component name, `timeago` by default
  locale: 'en-US',
  locales: {
    'en-US': require('vue-timeago/locales/en-US.json')
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

window.appNotification = new Vue({
  el: '#app-notification',
  store,
  components: {
    'app-notification': AppNotification
  }
})
window.appNotificationAll = new Vue({
  el: '#app-notification-all',
  store,
  components: {
    'app-notification-all': AppNotificationAll
  }
})

window.createPost = new Vue({
  el: '#app-create-post',
  components: {
    'app-create-post': createPost
  }
})