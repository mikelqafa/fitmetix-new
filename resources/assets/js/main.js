// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Post from './components/Post'
import AppNotification from './components/AppNotification'
import postDialogOption from './components/DialogOption'
import commentDialogOption from './components/CommentDialogOption.vue'
import VueTimeago from 'vue-timeago'
import VueAwesomeSwiper from 'vue-awesome-swiper'
require('swiper/dist/css/swiper.css')

Vue.use(VueAwesomeSwiper)

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
  components: {
    'app-post': Post,
    'app-post-option': postDialogOption,
    'app-comment-option': commentDialogOption
  }
})

window.appNotification = new Vue({
  el: '#app-notification',
  components: {
    'app-notification': AppNotification
  }
})
