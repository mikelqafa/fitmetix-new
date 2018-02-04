<template>
    <div>
        <a href="javascript:;" @click="showMessages($event)" class="pos-rel has-hover-effect fm-nav__item font-large">
            <span class="navicon icon-chat" style="font-size: 17px" data-icon="b"></span>
            <span class="unread-notification" v-bind:class="{ 'is-visible': unreadMsg }"></span>
        </a>
        <div id="ft-notification-msg" data-width="5" class="md-menu__container ft-chat">
            <template v-if="conversations.data !== undefined && conversations.data.length">
                <div class="ft-chat__header">Messages</div>
                <div class="md-menu">
                    <template v-if="conversations.data !== undefined">
                        <div v-for="item in conversations.data" :key="'msg-noti'+item.id" class="md-list__item has-divider"
                             @click="openChat(item)">
                            <div class="md-list__item-content">
                                <a href="#"
                                   class="md-list__item-icon user-avatar"
                                   :style="{backgroundImage: 'url('+getThumbImage(item.user.avatar)+')'}">
                                </a>
                                <div class="md-list__item-primary">
                                    <span class="pos-rel">
                                        <span>{{item.user.name}}</span>
                                        <span class="unread-notification unread-notification--chat" v-bind:class="{ 'is-visible': item.unread }"></span>
                                    </span>
                                    <div class="md-list__item-text-body" v-html="getShortMsg(item.lastMessage.body)"></div>
                                </div>
                                <div class="md-list__item-secondary text-right">
                                    <div class="md-list__item-secondary-info">
                                        <timeago :since="since(item.lastMessage.created_at)"
                                                 :auto-update="autoUpdate"
                                                 class="timeago"></timeago>
                                    </div>
                                    <div class="md-layout-spacer"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            <template v-else="">
                <div class="ft-chat__header">No Message</div>
            </template>
        </div>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex'
    export default {
        data: function () {
            return {
                ntSeeAllLink: '',
                autoUpdate: 60,
                notificationsLoaded: false,
                notificationsLoading: false,
                redirect: false,
                config: {

                }
            }
        },
        mounted () {

        },
        methods: {
            getShortMsg: function(item) {
                return item.length < 50 ? item : item.substr(0, 50) + '...'
            },
            getThumbImage: function (url) {
               return getThumbImage(url)
            },
            openChat: function (c) {
                $('.ft-chat--list-wrapper').addClass('is-list-open')
                $('.ft-chat-box.ft-chat-box--docker ').addClass('ft-chat-box--open')
                this.$store.dispatch('showConversation', {byTap: true, conversation: c})
                $('#ft-notification-msg').addClass('is-leaving')
                setTimeout(function () {
                    $('#ft-notification-msg').removeClass('is-open').removeClass('is-leaving')
                }, 200);
            },
            since: function (date) {
                let str = date
                if(date != '') {
                    str = date
                    let res = str.split(' ')
                    str = res[0]+'T'+res[1]
                    str.replace(/\s/, 'T')
                }
                return date != '' ? new Date(str+'Z').getTime() : new Date().getTime()
            },
            showMessages: function (el) {
                if(!$('#ft-notification-msg').hasClass('is-open')) {
                    this.markNotificationsRead()
                    let rect = el.currentTarget.getBoundingClientRect()
                    $('#ft-notification-msg').css({left: rect.right- ($('#ft-notification-msg').width())+'px', top: 60+'px'})
                    $('#ft-notification-msg').addClass('is-open')
                    $('.md-menu__backdrop').addClass('is-open')
                } else {
                    $('#ft-notification-msg').addClass('is-leaving')
                    $('.md-menu__backdrop').removeClass('is-open')
                    setTimeout(function () {
                        $('#ft-notification-msg').removeClass('is-open').removeClass('is-leaving')
                    }, 200);
                }
            },
            markNotificationsRead: function () {
                this.$store.dispatch('setUnread',{})
            }
        },
        watch: {
            unreadMsg: function (val) {
                if(val) {
                    $('.unread-notification.is-shown-msg').addClass('is-visible')
                } else {
                    $('.unread-notification.is-shown-msg').removeClass('is-visible')
                }
            }
        },
        computed: {
            ...mapGetters({
               unreadMsg: 'unreadMsg',
               conversations: 'conversations'
            })
        }
    }
</script>