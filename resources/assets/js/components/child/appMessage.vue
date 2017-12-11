<template>
    <div>
        <a href="javascript:;" @click="showConversations($event)" class="has-hover-effect fm-nav__item font-large">
            <span class="icon icon-chat"></span>
            <span class="unread-notification" v-bind:class="{ 'is-visible': isShowUCN }"></span>
        </a>
        <div id="ft-message" data-width="5" class="md-menu__container ft-chat">
            <template v-if="notifications.length">
                <div class="ft-chat__header">Notifications</div>
                <div class="md-menu">
                    <a href="javascript:;" data-user-id="26" class="md-menu__item ft-chat__item">
                        <div class="md-list__item md-list__item--three-line has-divider">
                            <div class="md-list__item-content">
                            <span class="md-list__item-icon">
                                <img src="http://localhost/fitmetix/public/user/avatar/default-male-avatar.png" alt="Mikel" class="md-list__item-avatar">
                            </span>
                                <div class="md-list__item-primary">
                                    <div>Single-line item</div>
                                    <div class="md-list__item-text-body">
                                        Bryan Cranston played the role.
                                    </div>
                                </div>
                                <div class="md-list__item-secondary">
                                    <div class="md-list__item-secondary-info">1m</div>
                                    <a class="md-list__item-secondary-action" href="#">
                                        <i class="material-icons">star</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="javascript:;" data-user-id="26" class="md-menu__item ft-chat__item">
                        <div class="md-list__item md-list__item--three-line has-divider">
                            <div class="md-list__item-content">
                            <span class="md-list__item-icon">
                                <img src="http://localhost/fitmetix/public/user/avatar/default-male-avatar.png" alt="Mikel" class="md-list__item-avatar">
                            </span>
                                <div class="md-list__item-primary">
                                    <div>Single-line item</div>
                                    <div class="md-list__item-text-body">
                                        Bryan Cranston played the role.
                                    </div>
                                </div>
                                <div class="md-list__item-secondary">
                                    <div class="md-list__item-secondary-info">20m</div>
                                    <a class="hidden md-list__item-secondary-action" href="#">
                                        <i class="icon ico">star</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <span class="media hidden">
                        <span class="media-left">
                            <img src="http://localhost/fitmetix/public/user/avatar/default-male-avatar.png" alt="images" class="media-object img-icon">
                        </span>
                        <span class="media-body">
                            <h4 class="media-heading">
                                <span class="message-heading">@Prakash</span>
                                <span class="online-status hidden"></span>
                            </h4>
                            <p class="message-text"></p>
                        </span>
                    </span>
                    </a>
                    <a href="javascript:;" data-user-id="26" class="ft-chat__item">
                        <div class="md-list__item md-list__item--see-all">
                            <div class="md-list__item-content">
                                <span>SEE ALL</span>
                            </div>
                        </div>
                    </a>
                </div>
            </template>
            <template v-else="">
                <div class="ft-chat__header">No notifications</div>
            </template>
        </div>
    </div>
</template>
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
                unreadConversations: 0,
                conversationsLoaded: false,
                conversationsLoading: false,
                pusher: null
            }
        },
        mounted () {
            // init the pusher
            this.pusher = this.$store.state.pusher
            console.log(this.pusher)
                    this.subscribeToPrivateMessageChannel(current_username);
            //this.getConversations();
            //let that = this
            //$('.coversations-thread').bind('scroll',that.chk_scroll);
            //$('.coversations-list').bind('scroll',that.chk_scroll);
            //this.initStateVariable()

            // Get if there are any unread notifications or conversations
            this.getConversationsCounter();
        },
        methods: {
            subscribeToPrivateMessageChannel: function(receiverUsername) {
                let that = this
                this.MessageChannel = this.pusher.subscribe(receiverUsername + '-message-created');
                this.MessageChannel.bind('App\\Events\\MessagePublished', function(data) {
                    data.message.user = data.sender;
                    if(that.currentConversation.id ==  data.message.thread_id) {
                        that.currentConversation.conversationMessages.push(data.message);
                        setTimeout(function(){
                            that.autoScroll('.coversations-thread');
                        },100)
                    }
                    else {
                        indexes = $.map(that.conversations.data, function(thread, key) {
                            if(thread.id == data.message.thread_id) {
                                return key;
                            }
                        });
                        if(indexes != '') {
                            that.conversations.data[indexes[0]].unread = true;
                            that.conversations.data[indexes[0]].lastMessage = data.message;
                        }
                        else {
                            that.$http.post(base_url + 'ajax/get-message/' + data.message.thread_id).then( function(response) {
                                that.conversations.data.unshift(response.data.data);
                            });
                        }
                    }
                });

                this.NotificationChannel = this.pusher.subscribe(current_username + '-notification-created');
                this.NotificationChannel.bind('App\\Events\\NotificationPublished', function(data) {
                    that.unreadNotifications = that.unreadNotifications + 1;
                    data.notification.notified_from = data.notified_from
                    if(that.notifications.data != null)
                    {
                        that.notifications.data.unshift(data.notification);
                    }
                    // that.notify(data.notification.description);
                    console.log('unread notification', data.notification)
                    // $.playSound(theme_url + '/sounds/notification');
                    //jQuery("time.timeago").timeago(); TODO timeago
                });
            },
            getConversations : function() {
                let that = this
                axios.post(base_url + 'ajax/get-messages').then(function (response) {
                    that.conversations = response.data.data;
                    that.showConversation(that.conversations.data[0]);
                });
            },
            showConversation : function(conversation) {
                this.newConversation = false;
                if(conversation) {
                    if(conversation.id != this.currentConversation.id) {
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
            postMessage : function(conversation) {
                messageBody = this.messageBody;
                this.messageBody = '';
                let that = this
                axios.post(base_url + 'ajax/post-message/' + conversation.id,{message: messageBody}).then( function(response) {
                    if(response.status) {
                        that.currentConversation.conversationMessages.data.push(response.data.data);
                        $('#messageReceipient').focus();
                        setTimeout(function(){
                            that.autoScroll('.coversations-thread');
                        },100)
                    }
                });

            },
            postNewConversation : function() {
                if(this.recipients.length) {
                    let that = this
                    let newThread, indexes
                    axios.post(base_url + 'ajax/create-message',{message: this.messageBody, recipients : this.recipients}).then( function(response) {
                        if(response.status) {
                            newThread = response.data.data;
                            indexes = $.map(that.conversations.data, function(thread, key) {
                                if(thread.id == newThread.id) {
                                    return key;
                                }
                            });
                            if(indexes != '') {
                                that.conversations.data[indexes[0]].unread = true;
                                that.conversations.data[indexes[0]].lastMessage = newThread.lastMessage;
                            }
                            else {
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
            autoScroll : function(element) {
                // $(element).animate({scrollTop: $(element)[0].scrollHeight + 600 }, 2000);
            },
            chk_scroll : function(e) {
                var elem = $(e.currentTarget);
                if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()) {
                    if(elem.data('type')=="threads") {
                        this.getMoreConversations();
                    }
                    else {
                        this.getMoreConversationMessages();
                    }
                }
            },
            getMoreConversationMessages : function() {
                if(this.currentConversation.conversationMessages.data.length < this.currentConversation.conversationMessages.total) {
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
            getMoreConversations : function() {
                if(this.conversations.data.length < this.conversations.total) {
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
            showNewConversation : function() {
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
            toggleUsersSelectize : function() {
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
                    onChange: function(value) {
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
            showConversations: function (el) {
                if(!$('#ft-notification').hasClass('is-open')) {
                    let rect = el.currentTarget.getBoundingClientRect()
                    $('#ft-notification').css({left: rect.right- ($('#ft-notification').width())+'px', top: 60+'px'})
                    $('#ft-notification').addClass('is-open')
                } else {
                    $('#ft-notification').addClass('is-leaving')
                    setTimeout(function () {
                        $('#ft-notification').removeClass('is-open').removeClass('is-leaving')
                    }, 200);
                }

            },
            getConversationsCounter: function () {
                // Lets get the unread  messages once the Vue instance is ready
                /*this.$http.post(base_url + 'ajax/get-unread-messages').then(function (response) {
                 this.unreadConversations = JSON.parse(response.body).unread_conversations;
                 });*/
                let that = this
                axios.post(base_url + 'ajax/get-unread-messages').then(function (response) {
                    // that.unreadNotifications = JSON.parse(response.body).unread_notifications;
                    console.log(response)
                });

            }
        },
        computed: {
            isShowUCN () {
                return this.unreadNotifications > 0
            }
        }
    }
</script>