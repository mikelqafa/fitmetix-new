<template>
    <div>
        <template v-if="isLoading">
            <div class="loader">
                <div class="spinner spinner--small"></div>
            </div>
        </template>
        <template v-else="">
            <div class="pos-rel">
                <a href="#" data-toggle="dropdown" @click="showConversations" class="has-hover-effect fm-nav__item dropdown font-large hidden-sm hidden-xs" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="icon icon-chat"></span>
                    <span class="unread-notification" v-bind:class="{ 'is-visible': isShowUCM }"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu ft-menu" data-width="3">
                    <li class="dropdown-menu-header ft-menu__item">
                        <span class="side-left">{{ ntCommonMessages }}</span>
                    </li>
                    <li class="no-messages ft-menu__item ft-menu__item--icon" v-if="noMessage">
                        <i class="fa fa-commenting-o"></i>
                        <p>{{ ntMessageNo }}</p>
                    </li>
                    <li class="inbox-message" v-for="conversation in conversations.data">
                        <a href="javascript:;" :data-user-id="conversation.user.id" onclick="chatBoxes.sendMessageOnClick(this)">
                            <div class="media">
                                <div class="media-left">
                                    <img class="media-object img-icon" v-bind:src="conversation.user.avatar" alt="images">
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <span class="message-heading">@{{ conversation.user.name }}</span>
                                        <span class="online-status hidden"></span>
                                        <!--TODO timeago -->
                                    </h4>
                                    <p class="message-text">
                                        <!--@{{ conversation.lastMessage.body }}-->
                                    </p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="dropdown-menu-footer ft-menu__item ft-menu__item--icon">
                        <a :href="ntSeeAllLink">{{ ntSeeAll }}</a>
                    </li>
                </ul>
            </div>
        </template>
    </div>
</template>
<style>
    .font-large {
        font-size: 24px;
    }
    .loader {
        position: relative;
        width:24px;
        height: 24px;
    }
.spinner {
    border: 2px solid #333;
    border-right-color: transparent;
    border-radius: 50%;
    height: 16px;
    left: 50%;
    position: absolute;
    top: 50%;
    width: 16px;
    animation: spinner 1s linear 0s infinite;
}
.spinner--small {
    width: 12px;
    height: 12px;
}
@keyframes spinner {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
<script>
    export default {
        data: function () {
            return {
                noMessage: false,
                isLoading: false,
                conversations: [],
                newConversation : false,
                recipients : [],
                currentConversation: {
                    conversationMessages : [],
                    user : []
                },
                messageBody : '',
                ntCount: '',
                ntCommonMessages: '',
                ntSeeAll: '',
                ntMessageNo: '',
                ntSeeAllLink: '',
                isShowUCM: false,
                pusher: ''
            }
        },
        mounted () {
            this.subscribeToPrivateMessageChannel(current_username);
            this.getConversations();
            //$('.coversations-thread').bind('scroll',this.chk_scroll);
            //$('.coversations-list').bind('scroll',this.chk_scroll);
            this.initStateVariable()
        },
        methods: {
            subscribeToPrivateMessageChannel: function(receiverUsername)
            {
                let that = this
                // pusher configuration
                this.pusher = new Pusher(pusherConfig.PUSHER_KEY, {
                    encrypted: true,
                    auth: {
                        headers: {
                            'X-CSRF-Token': pusherConfig.token
                        },
                        params: {
                            username: current_username
                        }
                    }
                });

                this.MessageChannel = this.pusher.subscribe(receiverUsername + '-message-created');
                this.MessageChannel.bind('App\\Events\\MessagePublished', function(data) {
                    data.message.user = data.sender;
                    if(that.currentConversation.id ==  data.message.thread_id)
                    {
                        that.currentConversation.conversationMessages.push(data.message);
                        setTimeout(function(){
                            that.autoScroll('.coversations-thread');
                        },100)
                    }
                    else
                    {
                        indexes = $.map(that.conversations.data, function(thread, key) {
                            if(thread.id == data.message.thread_id) {
                                return key;
                            }
                        });
                        if(indexes != '')
                        {
                            that.conversations.data[indexes[0]].unread = true;
                            that.conversations.data[indexes[0]].lastMessage = data.message;
                        }
                        else
                        {
                            that.$http.post(base_url + 'ajax/get-message/' + data.message.thread_id).then( function(response) {
                                that.conversations.data.unshift(response.data.data);
                            });
                        }
                    }

                });
            },
            getConversations : function() {
                let that = this
                axios.post(base_url + 'ajax/get-messages').then(function (response) {
                    that.conversations = response.data.data;
                    that.showConversation(that.conversations.data[0]);
                });
            }
            ,
            showConversation : function(conversation)
            {
                this.newConversation = false;
                if(conversation)
                {
                    if(conversation.id != this.currentConversation.id)
                    {
                        conversation.unread = false;
                        let that = this
                        axios.post(base_url + 'ajax/get-conversation/' + conversation.id).then( function(response) {
                            that.currentConversation = response.data.data;
                            that.currentConversation.user = conversation.user;
                            setTimeout(function(){
                                that.autoScroll('.coversations-thread');
                            },100)
                        });
                    }
                }

            },
            postMessage : function(conversation)
            {

                messageBody = this.messageBody;
                this.messageBody = '';
                let that = this
                axios.post(base_url + 'ajax/post-message/' + conversation.id,{message: messageBody}).then( function(response) {
                    if(response.status)
                    {
                        that.currentConversation.conversationMessages.data.push(response.data.data);
                        $('#messageReceipient').focus();
                        setTimeout(function(){
                            that.autoScroll('.coversations-thread');
                        },100)

                    }
                });

            },
            postNewConversation : function()
            {
                if(this.recipients.length)
                {
                    let that = this
                    let newThread, indexes
                    axios.post(base_url + 'ajax/create-message',{message: this.messageBody, recipients : this.recipients}).then( function(response) {
                        if(response.status)
                        {
                            newThread = response.data.data;
                            indexes = $.map(that.conversations.data, function(thread, key) {
                                if(thread.id == newThread.id) {
                                    return key;
                                }
                            });

                            if(indexes != '')
                            {
                                that.conversations.data[indexes[0]].unread = true;
                                that.conversations.data[indexes[0]].lastMessage = newThread.lastMessage;
                            }
                            else
                            {
                                that.conversations.data.unshift(response.data.data);
                            }

                            $('#messageReceipient').focus();
                            that.recipients= [];
                            that.newConversation = false;
                            that.messageBody = "";
                            that.showConversation(that.conversations.data[0]);
                            setTimeout(function(){
                                that.autoScroll('.coversations-thread');
                            },100)
                        }
                    });
                }
            },
            autoScroll : function(element)
            {
               // $(element).animate({scrollTop: $(element)[0].scrollHeight + 600 }, 2000);
            },
            chk_scroll : function(e)
            {
                var elem = $(e.currentTarget);

                if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight())
                {
                    if(elem.data('type')=="threads")
                    {
                        this.getMoreConversations();
                    }
                    else
                    {
                        this.getMoreConversationMessages();
                    }
                }
            },
            getMoreConversationMessages : function()
            {
                if(this.currentConversation.conversationMessages.data.length < this.currentConversation.conversationMessages.total)
                {
                    let that = this
                    axios.post(this.currentConversation.conversationMessages.next_page_url).then( function(response) {
                        var latestConversations = response.data.data;
                        that.currentConversation.conversationMessages.last_page =  latestConversations.conversationMessages.last_page;
                        that.currentConversation.conversationMessages.next_page_url =  latestConversations.conversationMessages.next_page_url;
                        that.currentConversation.conversationMessages.per_page =  latestConversations.conversationMessages.per_page;
                        that.currentConversation.conversationMessages.prev_page_url =  latestConversations.conversationMessages.prev_page_url;
                        $.each(latestConversations.conversationMessages.data, function(i, latestConversation) {
                            that.currentConversation.conversationMessages.data.unshift(latestConversation);
                        });
                    });
                }
            },
            getMoreConversations : function()
            {
                if(this.conversations.data.length < this.conversations.total)
                {
                    let that = this
                    axios.post(this.conversations.next_page_url).then( function(response) {
                        let latestConversations = response.data.data;
                        that.conversations.last_page =  latestConversations.last_page;
                        that.conversations.next_page_url =  latestConversations.next_page_url;
                        that.conversations.per_page =  latestConversations.per_page;
                        that.conversations.prev_page_url =  latestConversations.prev_page_url;
                        $.each(latestConversations.data, function(i, latestConversation) {
                            that.conversations.data.unshift(latestConversation);
                        });
                    });
                }
            },
            showNewConversation : function()
            {
                this.newConversation = true
                this.currentConversation = {
                    user : []
                }
                $('#messageReceipient').focus()
                let that = this
                setTimeout(function(){
                    that.toggleUsersSelectize()
                },10)
            },
            toggleUsersSelectize : function()
            {
                let that = this;
                let selectizeUsers = $('#messageReceipient').selectize({
                    valueField: 'id',
                    labelField: 'name',
                    searchField: 'name',
                    render: {
                        option: function(item, escape) {
                            return '<div class="media big-search-dropdown">' +
                                    '<a class="media-left" href="#">' +
                                    '<img src="'+ item.avatar + '" alt="...">' +
                                    '</a>' +
                                    '<div class="media-body">' +
                                    '<h4 class="media-heading">' + escape(item.name) + '</h4>' +
                                    '<p>' +  item.username +  '</p>' +               '</div>' +
                                    '</div>';
                        },

                    },
                    onChange: function(value)
                    {
                        $('[name="user_tags"]').val(value);
                        // $('.user-tags-added').find('.user-tag-names').append('<a href="#">' + value  + '</a>');
                        var selectize =selectizeUsers[0].selectize;
                        that.recipients = selectize.items;
                    },
                    load: function(query, callback) {
                        if (!query.length) return callback();
                        $.ajax({
                            url: base_url  + 'api/v1/users',
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                search: query
                            },
                            error: function() {
                                callback();
                            },
                            success: function(res) {
                                callback(res.data);
                            }
                        });
                    }
                });
            },
            initStateVariable: function () {
                let ntCount = $("input[name='nt-count']").val()
                let ntCommonMessages = $("input[name='nt-common-messages']").val()
                let ntSeeAll = $("input[name='nt-common-see_all']").val()
                let ntMessageNo = $("input[name='nt-no_messages']").val()
                let ntSeeAllLink = $("input[name='see-all-messages']").val()
                this.ntCount = ntCount
                this.ntCommonMessages = ntCommonMessages
                this.ntSeeAll = ntSeeAll
                this.ntMessageNo = ntMessageNo
                this.ntSeeAllLink = ntSeeAllLink
            },
            showConversations: function () {
                // TODO
            }
        }
    }
</script>