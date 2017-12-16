// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Post from './components/Post'
import HasTagPost from './components/HasTagPost'
import createPost from './components/createPost'
//import editPost from './components/child/editPost'
import AppNotification from './components/AppNotification'
import postDialogOption from './components/DialogOption'
import commentDialogOption from './components/CommentDialogOption'
import VueTimeago from 'vue-timeago'
import VueAwesomeSwiper from 'vue-awesome-swiper'
// import AppEventCalendar from './components/appEventCalendar'
import AppEventList from './components/appEventList'
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
    'app-post-hashtag': HasTagPost,
    'app-post-option': postDialogOption,
    'app-comment-option': commentDialogOption,
    'app-event-list': AppEventList
  }
})

window.appNotification = new Vue({
  el: '#app-notification',
  store,
  components: {
    'app-notification': AppNotification
  }
})

window.createPost = new Vue({
  el: '#app-create-post',
  components: {
    'app-create-post': createPost
  }
})

/*window.eventCalendar = new Vue({
  el: '#app-create-event',
  components: {
    'app-event-calender': AppEventCalendar
  }
})*/