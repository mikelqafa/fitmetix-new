<template>
    <div>
        <a href="javascript:;" @click="showNotifications($event)" class="pos-rel has-hover-effect fm-nav__item font-large">
            <span class="icon icon-like"></span>
            <span class="unread-notification" v-bind:class="{ 'is-visible': isShowUCM }"></span>
        </a>
        <div id="ft-notification" data-width="5" class="md-menu__container ft-chat">
            <template v-if="notifications.length">
                <div class="ft-chat__header">Notifications</div>
                <div class="md-menu">
                    <a href="javascript:;" v-for="item in notifications" data-user-id="26" class="md-menu__item ft-chat__item">
                        <div class="md-list__item  has-divider">
                            <div class="md-list__item-content">
                            <span class="md-list__item-icon">
                                <img src="http://localhost/fitmetix/public/user/avatar/default-male-avatar.png" alt="Mikel" class="md-list__item-avatar">
                            </span>
                                <div class="md-list__item-primary">
                                    <div>Prakash</div>
                                    <div class="md-list__item-text-body">
                                        {{item.description}}
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
                <div class="ft-chat__header">No Notifications</div>
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
                notifications: [],
                unreadNotifications: 0,
                notificationsLoaded: false,
                notificationsLoading: false,
                config: {
                    
                }
            }
        },
        mounted () {
            // init the pusher
            //this.getConversations();
            //let that = this
            //$('.coversations-thread').bind('scroll',that.chk_scroll);
            //$('.coversations-list').bind('scroll',that.chk_scroll);
            //this.initStateVariable()

            // Get if there are any unread notifications or conversations
            this.getNotificationsCounter()
            this.fetchOldNotification()
        },
        methods: {
            subscribeToPrivateMessageChannel: function(receiverUsername) {
                let that = this
                // pusher configuration

                this.NotificationChannel = this.pusher.subscribe(current_username + '-notification-created');
                this.NotificationChannel.bind('App\\Events\\NotificationPublished', function(data) {
                    that.unreadNotifications = that.unreadNotifications + 1;
                    data.notification.notified_from = data.notified_from
                    if(that.notifications != null) {
                        that.notifications.unshift(data.notification);
                    }
                    materialSnackBar({messageText: data.notification.description, autoClose: true, timeout: 5000 })
                    that.$store.dispatch('likePostByPusher', data.notification)
                    $.playSound(theme_url + '/sounds/notification');
                });
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
            getNotificationsCounter: function () {
                // Lets get the unread notifications once the Vue instance is ready
                let that = this
                axios.post(base_url + 'ajax/get-unread-notifications').then(function (response) {
                    if(response.data.status == 200) {
                        that.unreadNotifications = response.data.unread_notifications
                    }
                });
            },
            fetchOldNotification: function () {
                let that = this
                axios.post(base_url + 'ajax/get-notifications').then(function (response) {
                    if(response.data.status == 200) {
                        let notifications = response.data.notifications
                        that.config.last_page = notifications.last_page
                        that.config.next_page_url = notifications.next_page_url
                        that.config.per_page = notifications.per_page
                        that.config.prev_page_url = notifications.prev_page_url
                        $.each(notifications.data, function (i, latestNotification) {
                            that.notifications.push(latestNotification);
                        });
                    }
                });
            },
            showNotifications: function (el) {
                if(!$('#ft-notification').hasClass('is-open')) {
                    this.unreadNotifications = 0
                    let rect = el.currentTarget.getBoundingClientRect()
                    $('#ft-notification').css({left: rect.right- ($('#ft-notification').width())+'px', top: 60+'px'})
                    $('#ft-notification').addClass('is-open')
                } else {
                    $('#ft-notification').addClass('is-leaving')
                    setTimeout(function () {
                        $('#ft-notification').removeClass('is-open').removeClass('is-leaving')
                    }, 200);
                }
                /*if (!this.notificationsLoaded) {
                 this.notificationsLoading = true;
                 this.$http.post(base_url + 'ajax/get-notifications').then(function (response) {
                 this.notifications = JSON.parse(response.body).notifications;
                 setTimeout(function () {
                 jQuery("time.timeago").timeago();
                 }, 10);
                 this.notificationsLoading = false;
                 });
                 this.notificationsLoaded = true;
                 }*/
            },
            getMoreNotifications: function () {
                if (this.notifications.data.length < this.notifications.total) {
                    this.notificationsLoading = true;
                    this.$http.post(this.notifications.next_page_url).then(function (response) {
                        var latestNotifications = JSON.parse(response.body).notifications;

                        this.notifications.last_page = latestNotifications.last_page;
                        this.notifications.next_page_url = latestNotifications.next_page_url;
                        this.notifications.per_page = latestNotifications.per_page;
                        this.notifications.prev_page_url = latestNotifications.prev_page_url;

                        var vm = this;
                        $.each(latestNotifications.data, function (i, latestNotification) {
                            vm.notifications.data.push(latestNotification);
                        });
                        this.notificationsLoading = false;
                        setTimeout(function () {
                            jQuery("time.timeago").timeago();
                        }, 10);
                    });
                }
            },
            markNotificationsRead: function () {

                this.$http.post(base_url + 'ajax/mark-all-notifications').then(function (response) {
                    this.unreadNotifications = 0;
                    var vm = this;
                    $.map(this.notifications, function (notification, key) {
                        vm.notifications[key].seen = true;
                    });
                });
            }
        },
        watch: {
            pusher: function (val) {
                if(val !== null) {
                    this.subscribeToPrivateMessageChannel(current_username);
                }
            }
        },
        computed: {
            isShowUCM () {
                return this.unreadNotifications > 0
            },
            ...mapGetters({
                pusher: 'pusher'
            })
        }
    }
</script>