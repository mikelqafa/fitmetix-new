<template>
    <div class="ft-dock-wrapper">
        <div class="ft-chat-group">
            <div class="ft-chat-wrapper">
                <div class="ft-chat-box ft-chat-box--abs ft-chat-box--docker">
                    <div class="ft-chat-box__inner-wrapper">
                        <header class="ft-chat-box__header" style="cursor: pointer">
                            <a href="javascript:;" class="chat-user margin-left-8" @click="showThread">
                                <svg fill="#ffffff" height="24" viewBox="0 0 24 24" width="24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                                </svg>
                            </a>
                            <a href="javascript:;" class="chat-user margin-left-8">{{currentConversation.user.name}}</a>
                            <div class="md-layout-spacer" style="height: 100%" @click="toggleChatMinimize"></div>
                            <a href="javascript:;" class="chat-options">
                                <i class="icon icon-options"></i>
                            </a>
                            <a href="javascript:;" class="chat-options margin-right-8" @click="closeChat">
                                <i class="icon icon-close"></i>
                            </a>
                        </header>
                        <div class="ft-chat-box__body">
                            <div class="inner-chat-wrapper coversations-thread coversations-thread--docker" data-type="threads" v-chat-scroll="{always: false}">
                                <div class="inner-chat">
                                    <template v-if="currentConversation.conversationMessages !== undefined">
                                        <li v-for="(item, index) in currentConversation.conversationMessages.data" :key="'chat'+index">
                                            <chat-thread :message="item" ></chat-thread>
                                        </li>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="ft-chat-box__footer">
                            <div class="ft-chat__write">
                                <div class="write-post__placeholder" v-if="hasNotContent" style="top: 10px;left:  15px;font-size:  12px;">{{placeholder}}</div>
                                <medium-editor
                                        id="create-chat-vue" :text='backContent' :options='options'
                                        class="ft-chat__write-box"
                                        v-on:edit='processEditOperation'
                                        style="z-index:2;outline: none; user-select: text; white-space: pre-wrap; word-wrap: break-word;"
                                        custom-tag='div'>
                                </medium-editor>
                                <div class="ft-chat__write-button-wrapper">
                                    <button type="submit" class="btn ft-chat__write-button">
                                        <svg class="svg-icon" fill="#ffffff" height="24" viewBox="0 0 24 24" width="24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                            <path d="M0 0h24v24H0z" fill="none"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="ft-loading ft-loading--abs" v-if="isSendingMsg">
                                    <span class="ft-loading__dot"></span>
                                    <span class="ft-loading__dot"></span>
                                    <span class="ft-loading__dot"></span>
                                </div>
                            </div>
                            <div class="ft-chat__quick-action hidden">
                                <a href="javascript:;" class="btn btn--icon">
                                    <i class="icon icon-add"></i>
                                </a>
                                <a href="javascript:;" class="btn btn--icon">
                                    <i class="icon icon-photo"></i>
                                </a>
                                <a href="javascript:;" class="btn btn--icon btn--icon--fa">
                                    <i class="fa fa-smile-o"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ft-chat--list-wrapper is-list-open">
                            <header class="ft-chat-box__header"  style="cursor: pointer">
                                <a href="javascript:;" class="chat-user margin-left-8 pos-rel" @click="toggleChatMinimize">
                                    Chat Story
                                    <span class="unread-notification" v-bind:class="{ 'is-visible': unreadMsg }" style="left: auto; right: -12px;top: 8px" title="You have a new message"></span>
                                </a>
                                <div class="md-layout-spacer" style="height: 100%" @click="toggleChatMinimize"></div>
                                <a href="javascript:;" class="chat-options margin-right-8" @click="closeChat">
                                    <i class="icon icon-close"></i>
                                </a>
                            </header>
                            <div class="ft-chat-box__body pos-rel">
                                <div class="scroll-wrapper coversations-list coversations-list--docker" data-type="list">
                                    <div class="md-list md-list--dense">
                                        <template v-if="conversations.data !== undefined">
                                            <div v-for="item in conversations.data" :key="item.id" class="md-list__item"
                                                 @click="openChat(item)">
                                                <div class="md-list__item-content">
                                                    <a :href="userLink(item)"
                                                       class="md-list__item-icon user-avatar"
                                                       :style="{backgroundImage: 'url('+getThumbImage(item)+')'}">
                                                    </a>
                                                    <div class="md-list__item-primary">
                                                        <span>{{item.user.name}}</span>
                                                        <div class="md-list__item-text-body" v-html="item.lastMessage.body"></div>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import editor from 'vue2-medium-editor'
    import { mapGetters } from 'vuex'
    import chatText from './chatText'
    export default {
        data: function () {
            return {
                isSendingMsg: false,
                autoUpdate: 60,
                item: [1, 2, 3, 4, 5, 6, 7, 8, 9],
                newConversation: false,
                recipients: [],
                backContent: '',
                options: { disableReturn: false },
                messageBody: '',
                placeholder: 'Type a message...'
            }
        },
        methods: {
            closeChat: function () {
              $('.ft-dock-wrapper').addClass('hidden')
            },
            toggleChatMinimize: function () {
                $('.ft-chat-box--docker').toggleClass('ft-chat-box--open')
            },
            processEditOperation: function (operation) {
                this.backContent = operation.api.origElements.innerHTML
            },
            userLink: function (item) {
                return base_url + item.user.username
            },
            getThumbImage: function (item) {
                return getThumbImage(item.user.avatar_url.length ? asset_url + 'uploads/users/avatars/' + item.user.avatar_url[0].source : base_url + 'images/' + this.defaultImage)
            },
            since (d) {
                let str = ''
                if (d != '' && d != undefined) {
                    str = d
                    let res = str.split(' ')
                    str = res[0] + 'T' + res[1]
                    str.replace(/\s/, 'T')
                }
                return d != '' ? new Date(str + 'Z').getTime() : new Date().getTime()
            },
            initPostMessage: function () {
                if(this.strip(this.backContent).trim()!=='') {
                    this.$store.dispatch('postMessage', {nonHtmlContent: this.nonHtmlContent})
                    this.backContent = '';
                    $('#create-chat-vue').html('')
                }
            },
            strip : function(html) {
                var tmp = document.createElement("DIV");
                tmp.classList = 'hidden';
                tmp.innerHTML = html;
                let val =  tmp.textContent || tmp.innerText || "";
                tmp.remove()
                return val
            },
            whichUser: function (u) {
                return u === current_username ? 0 : 1
            },
            showThread: function () {
                $('.ft-chat--list-wrapper').addClass('is-list-open')
                this.$store.dispatch('getConversations')
            },
            openChat: function (c) {
                this.showConversation(c, true)
            },
            fetchLIstOnScroll: function (e) {
                let elem = $(e.currentTarget);
                if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()) {
                    console.log('fetching list')
                    this.$store.dispatch('getMoreConversations')
                }
            },
            fetchThreadOnScroll: function (e) {
                let elem = $(e.currentTarget);
                if (!elem.scrollTop()) {
                    this.$store.dispatch('getMoreConversationMessages')
                }
            },
            showConversation: function(c) {
                this.$store.dispatch('showConversation', {byTap: true, conversation: c})
            }
        },
        mounted () {
            this.$store.dispatch('subscribeToPrivateMessageChannel', current_username)
            this.$store.dispatch('getConversations')
            let that = this
            $('.ft-dock-wrapper .coversations-thread--docker').bind('scroll', that.fetchThreadOnScroll);
            $('.ft-dock-wrapper .coversations-list--docker').bind('scroll', that.fetchLIstOnScroll);
            $("#create-chat-vue").keypress(function(e) {
                if(e.which ==13) {
                    that.initPostMessage()
                    return false
                }
                return true
            });
            this.$store.dispatch('setUserObj')
        },
        components: {
            'medium-editor': editor,
            'chat-thread': chatText
        },
        computed: {
            ...mapGetters({
                unreadMsg: 'unreadMsg',
                currentConversation: 'currentConversation',
                conversations: 'conversations'
            }),
            hasNotContent () {
                return this.nonHtmlContent === ''
            },
            nonHtmlContent() {
                let is_xhtml = false
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (this.backContent + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            }
        }
    }
</script>
