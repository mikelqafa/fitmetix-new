<template>
    <div class="row layout-m-t-1">
        <app-confirm :unid="unid" :body="body"></app-confirm>
        <div class="col-md-4 col-sm-5 no-padding--mobile">
            <div class="ft-box--desktop">
                <header class="ft-chat-desktop__header" style="box-shadow: none; border-bottom: 1px solid rgba(0,0,0,.12);background-color: #fafafa">
                    <div class="input-group no-margin">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                        </span>
                        <input type="text" class="form-control" placeholder="Search User"/>
                    </div>
                </header>
                <div class="ft-chat-box__body pos-rel">
                    <div class="scroll-wrapper coversations-list coversations-list--desktop" data-type="list">
                        <div class="md-list" style="background-color: #FFF">
                            <template v-if="conversations.data !== undefined">
                                <transition-group name="flip-list" tag="div">
                                    <div v-for="item in sortedConversations" :key="item.id" class="md-list__item"
                                         @click="openChat(item)" v-bind:class="{'is-new': item.unread, 'is-active': item.id == currentConversation.id}">
                                        <div class="md-list__item-content">
                                            <a :href="userLink(item)"
                                               class="md-list__item-icon user-avatar"
                                               :style="{backgroundImage: 'url('+getThumbImage(item)+')'}">
                                            </a>
                                            <div class="md-list__item-primary">
                                            <span class="pos-rel">
                                                <span>{{item.user.name}}</span>
                                                <span class="unread-notification unread-notification--chat" v-bind:class="{ 'is-visible': item.unread }"></span>
                                            </span>
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
                                </transition-group>
                            </template>
                        </div>
                        <template v-if="conversations.data == undefined || !conversations.data.length">
                            <div class="ft-chat__header">No Message Found</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-7 no-padding--mobile chat-user-list-wrapper">
            <div class="ft-chat-box ft-chat-box--desktop">
                <div class="ft-chat-box__inner-wrapper" v-if="conversations.data !== undefined && conversations.data.length">
                    <header class="ft-chat-desktop__header" style="cursor: pointer">
                        <a href="javascript:;" class="hidden visible-xs chat-user margin-left-8" @click="goBack">
                            <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 0h24v24H0z" fill="none"/>
                                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                            </svg>
                        </a>
                        <a href="javascript:;" class="chat-user margin-left-8">{{currentConversation.user.name}}</a>
                        <div class="md-layout-spacer"></div>
                        <a href="javascript:;" @click="confirmClearThreadMessage" class="chat-options">
                            <i class="icon icon-delete"></i>
                        </a>
                        <a href="javascript:;" class="hidden chat-options">
                            <i class="icon icon-options"></i>
                        </a>
                    </header>
                    <div class="ft-chat-box__body">
                        <div class="inner-chat-wrapper coversations-thread coversations-thread--desktop" v-chat-scroll="{always: false}" data-type="threads">
                            <ul class="inner-chat">
                                <template v-if="currentConversation.conversationMessages !== undefined">
                                    <li v-for="(item, index) in currentConversation.conversationMessages.data" :key="'conv'+index">
                                        <chat-thread :message="item" ></chat-thread>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    <div class="ft-chat-box__footer" style="box-shadow: none;padding-bottom: 24px">
                        <div class="ft-chat__write">
                            <div class="write-post__placeholder" v-if="hasNotContent" style="top: 14px;left:26px;font-size:  12px;">{{placeholder}}</div>
                            <medium-editor
                                    id="create-chat-vue-single" :text='backContent' :options='options'
                                    class="ft-chat__write-box ft-chat__write-box--big"
                                    v-on:edit='processEditOperation'
                                    style="z-index:2;outline: none; user-select: text; white-space: pre-wrap; word-wrap: break-word;"
                                    custom-tag='div'>
                            </medium-editor>
                            <div class="ft-chat__write-button-wrapper" style="right: 20px;top: 8px">
                                <button type="submit" class="btn ft-chat__write-button" v-show="nonEmpty" @click="initPostMessage">
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
                        <div class="ft-chat__quick-action hidden" style="bottom: 8px;top: auto;">
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
                </div>
                <div v-if="conversations.data == undefined || !conversations.data.length">
                    <div class="ft-chat__header">No Message Found</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import editor from 'vue2-medium-editor'
    import chatText from './chatText'
    import appConfirm from './appConfirm'
    import { mapGetters } from 'vuex'
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
                placeholder: 'Type a message...',
                nonEmpty: false,
                defaultImage: 'default.png',
                unid: 'app-confirm-delete-message-single',
                body: ''
            }
        },
        methods: {
            confirmClearThreadMessage: function () {
                this.body = 'Do you really want to delete this conversation?'
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.MaterialDialog('show')
                let that = this
                confirmDialog.on('ca.dialog.affirmative.action', function(){
                    that.clearMessage()
                });
            },
            clearMessage: function () {
                let id_ = this.$store.state.currentConversation.id
                let indexes = $.map(this.$store.state.conversations.data, function (thread, key) {
                    if (thread.id == id_) {
                        return key;
                    }
                });
                this.$store.dispatch('deleteThreadMessage', {item:this.$store.state.currentConversation, index: indexes[0]})
            },
            processEditOperation: function (operation) {
                this.backContent = operation.api.origElements.innerHTML
            },
            userLink: function (item) {
                return base_url + item.user.username
            },
            getThumbImage: function (item) {
                if(item.user !== undefined) {
                    return getThumbImage(item.user.avatar_url.length ? asset_url + 'uploads/users/avatars/' + item.user.avatar_url[0].source : base_url + 'images/' + this.defaultImage)
                } else {
                    return ''
                }
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
                    let nonHtmlContent = this.nonHtmlContent
                    this.backContent = '';
                    $('#create-chat-vue-single').html('')
                    this.$store.dispatch('postMessage', {nonHtmlContent: nonHtmlContent})
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
                $('.chat-user-list-wrapper').addClass('is-open')
                this.getConversations()
            },
            goBack: function () {
                $('.chat-user-list-wrapper').removeClass('is-open')
            },
            openChat: function (c) {
                this.showConversation(c, true)
                $('.chat-user-list-wrapper').addClass('is-open')
            },
            fetchLIstOnScroll: function (e) {
                console.log('scroll list')
                let elem = $(e.currentTarget);
                if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()) {
                    console.log('fetching list')
                    this.$store.dispatch('getMoreConversations')
                }
            },
            fetchThreadOnScroll: function (e) {
                console.log('scroll thread')
                let elem = $(e.currentTarget);
                if (!elem.scrollTop()) {
                    this.$store.dispatch('getMoreConversationMessages')
                }
            },
            showConversation: function(c) {
                this.$store.dispatch('showConversation', {byTap: false, conversation: c})
            }
        },
        mounted () {
            let that = this
            $('.coversations-thread--desktop').bind('scroll', that.fetchThreadOnScroll);
            $('.coversations-list--desktop').bind('scroll', that.fetchLIstOnScroll);
            $("#create-chat-vue-single").keypress(function(e) {
                if(e.which ==13) {
                    that.initPostMessage()
                    return false
                }
                return true
            });
        },
        components: {
            'medium-editor': editor,
            'chat-thread': chatText,
            'app-confirm': appConfirm
        },
        watch: {
            backContent: function (val) {
                this.nonEmpty = $('#create-chat-vue-single').text().trim() !== ''
            }
        },
        computed: {
            sortedConversations () {
                return this.conversations.data.sort(function(a,b){
                    if (a.lastMessage.id > b.lastMessage.id)
                        return -1;
                    if (a.lastMessage.id < b.lastMessage.id)
                        return 1;
                    return 0;
                });
            },
            hasNotContent () {
                return this.nonHtmlContent === ''
            },
            nonHtmlContent() {
                let is_xhtml = false
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (this.backContent + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            },
            ...mapGetters({
                currentConversation: 'currentConversation',
                conversations: 'conversations'
            })
        }
    }
</script>
