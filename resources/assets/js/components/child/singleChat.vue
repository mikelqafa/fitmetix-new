<template>
    <div class="row layout-m-t-1">
        <div class="col-md-4">
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
                    <div class="scroll-wrapper coversations-list" data-type="list">
                        <div class="md-list" style="background-color: #FFF">
                            <template v-if="conversations.data !== undefined">
                                <div v-for="item in conversations.data" :key="item.id" class="md-list__item"
                                     @click="openChat(item)" v-bind:class="{'is-active': item.id == currentConversation.id}">
                                    <div class="md-list__item-content">
                                        <a href="//localhost:3000/fitmetix/public/doremon"
                                           class="md-list__item-icon user-avatar"
                                           :style="{backgroundImage: 'url('+item.user.avatar+')'}">
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
        <div class="col-md-8">
            <div class="ft-chat-box ft-chat-box--desktop">
                <div class="ft-chat-box__inner-wrapper">
                    <header class="ft-chat-desktop__header" style="cursor: pointer">
                        <a href="javascript:;" class="chat-user margin-left-8">{{currentConversation.user.name}}</a>
                        <div class="md-layout-spacer"></div>
                        <a href="javascript:;" class="chat-options">
                            <i class="icon icon-options"></i>
                        </a>
                    </header>
                    <div class="ft-chat-box__body">
                        <div class="inner-chat-wrapper coversations-thread" data-type="threads">
                            <div class="inner-chat">
                                <template v-if="currentConversation.conversationMessages !== undefined">
                                    <div v-for="item in currentConversation.conversationMessages.data"
                                         class="chat-item" :data-user="whichUser(item.user.username)">
                                        <div class="chat-item__user">
                                            <a aria-label="Prem Bharti Wednesday 7:08pm" class="_4tdw"
                                               data-hover="tooltip"
                                               data-tooltip-content="Prem Bharti Wednesday 7:08pm"
                                               data-tooltip-position="left"
                                               href="">
                                                <img :src="item.user.avatar"
                                                     alt="" class="img">
                                            </a>
                                        </div>
                                        <div class="chat-item__bubble">
                                            <div class="chat-item__content">
                                                <div class="chat-item__content-wrapper">
                                                    <div class="chat-item__content-text" v-html="item.body">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="ft-chat-box__footer" style="box-shadow: none;padding-bottom: 40px">
                        <div class="ft-chat__write">
                            <div class="write-post__placeholder" v-if="hasNotContent" style="top: 10px;left:  15px;font-size:  12px;">{{placeholder}}</div>
                            <medium-editor
                                    id="create-chat-vue-single" :text='backContent' :options='options'
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
                        <div class="ft-chat__quick-action" style="bottom: 8px;top: auto;">
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
            </div>
        </div>
    </div>
</template>
<script>
    import editor from 'vue2-medium-editor'
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
                placeholder: 'Type a message...'
            }
        },
        methods: {
            processEditOperation: function (operation) {
                this.backContent = operation.api.origElements.innerHTML
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
                $('.ft-chat--list-wrapper').addClass('is-list-open')
                this.getConversations()
            },
            openChat: function (c) {
                this.showConversation(c, true)
            },
            autoScroll: function (el) {
                $(el).animate({scrollTop: $(el)[0].scrollHeight + 600}, 2000);
            },
            chk_scroll: function (e) {
                let elem = $(e.currentTarget);
                if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()) {
                    if (elem.data('type') == "threads") {
                        // TODO this.getMoreConversations();
                    } else {
                        //TODO this.getMoreConversationMessages();
                    }
                }
            },
            showConversation: function(c) {
                this.$store.dispatch('showConversation', {byTap: false, conversation: c})
            }
        },
        mounted () {
            let that = this
            //$('.coversations-thread').bind('scroll', that.chk_scroll);
            //$('.coversations-list').bind('scroll', that.chk_scroll);
            $("#create-chat-vue-single").keypress(function(e) {
                if(e.which ==13) {
                    that.initPostMessage()
                    return false
                }
                return true
            });
        },
        components: {
            'medium-editor': editor
        },
        computed: {
            ...mapGetters({
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
            },
        }
    }
</script>
