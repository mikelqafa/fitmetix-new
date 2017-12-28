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
                    <a :href="notificationUrl(item)" v-for="item in notifications" data-user-id="26" class="md-menu__item ft-chat__item">
                        <div class="md-list__item  has-divider">
                            <div class="md-list__item-content">
                                <a :href="userLink(item.notified_from.username)" class="md-list__item-icon">
                                    <img :src="item.notified_from.avatar" alt="Mikel" class="md-list__item-avatar">
                                </a>
                                <div class="md-list__item-primary">
                                    <a :href="userLink(item.notified_from.username)">{{item.notified_from.name}}</a>
                                    <div class="md-list__item-text-body">
                                        {{item.description}}
                                    </div>
                                </div>
                                <div class="md-list__image" v-if="notificationImageUrl(item) !== ''" v-bind:style="{ backgroundImage: 'url(' + notificationImageUrl(item) + ')' }"></div>
                                <div class="md-list__item-secondary">
                                    <div class="md-list__item-secondary-info">
                                        <timeago :since="since(item.created_at)"
                                                 :auto-update="autoUpdate"
                                                 class="timeago"></timeago>
                                    </div>
                                    <a class="md-list__item-secondary-action" href="#">
                                        <i class="hidden material-icons">star</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a :href="allNotificationLink" data-user-id="26" class="ft-chat__item">
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
<style>
    .md-list__image {
        width: 48px;
        height: 48px;
        background-size: cover;
        background-position: center center;
    }
</style>
<script>
    import { mapGetters } from 'vuex'
    export default {
        data: function () {
            return {
                ntSeeAllLink: '',
                notifications: [],
                autoUpdate: 60,
                unreadNotifications: 0,
                notificationsLoaded: false,
                notificationsLoading: false,
                allNotificationLink: base_url + 'allnotifications',
                redirect: false,
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
            this.init()
        },
        methods: {
            init: function () {
                let that = this
                $('#ft-mobile-nt').click( function (e) {
                    e.preventDefault()
                    that.markNotificationsRead()
                    that.redirect = true
                });
            },
            notificationUrl: function (item) {
                let url = ''
                switch(item.type) {
                    case 'report_post':
                          url =  base_url + 'post/'+item.post_id
                    break
                    case 'comment_post':
                          url =  base_url + 'post/'+item.post_id
                    break
                    case 'like_post':
                        url =  base_url + 'post/'+item.post_id
                    break
                    case 'share_post':
                        url =  base_url + 'post/'+item.post_id
                    break
                    case 'mention':
                        url =  base_url + 'post/'+item.post_id
                    break
                    case 'follow':
                        url =  base_url + item.notified_from.username
                    break
                    case 'unfollow':
                        url =  base_url + item.notified_from.username
                    break
                    case 'unlike_post':
                        url =  base_url + 'post/'+item.post_id
                    break
                }
                return url
            },
            notificationImageUrl: function (item) {
                let url = ''
                switch(item.type) {
                    case 'like_post':
                        if(item.link !== '' && item.link !== null ) {
                            url =  asset_url + 'uploads/users/gallery/'+item.link
                        }
                    break
                    case 'unlike_post':
                        if(item.link !== '' && item.link !== null ) {
                            url =  asset_url + 'uploads/users/gallery/'+item.link
                        }
                    break
                }
                return url
            },
            userLink: function (username) {
                return base_url + username
            },
            since: function (date) {
                return date != '' ? new Date(date + 'Z').getTime() : new Date().getTime()
            },
            subscribeToPrivateMessageChannel: function(receiverUsername) {
                let that = this
                // pusher configuration
                this.NotificationChannel = this.pusher.subscribe(current_username + '-notification-created');
                this.NotificationChannel.bind('App\\Events\\NotificationPublished', function(data) {
                    data.notification.notified_from = data.notified_from
                    if(that.notifications != null) {
                        switch (data.notification.type) {
                            case 'like_post':
                                that.notifications.unshift(data.notification);
                                that.$store.dispatch('likePostByPusher', data.notification)
                                if(!data.notification.seen) {
                                    that.unreadNotifications = that.unreadNotifications + 1;
                                }
                            break;
                            case 'unlike_post':
                                that.$store.dispatch('likePostByPusher', data.notification)
                            break;
                            default:
                                that.notifications.unshift(data.notification);
                                if(!data.notification.seen) {
                                    that.unreadNotifications = that.unreadNotifications + 1;
                                    materialSnackBar({messageText: data.notification.description, autoClose: true, timeout: 5000 })
                                    $.playSound(theme_url + '/sounds/notification');
                                }
                        }

                    }
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
                    this.markNotificationsRead()
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
                let that = this
                if(!this.notifications.length) {
                    return
                }
                axios.post(base_url + 'ajax/mark-all-notifications').then(function (response) {
                    that.unreadNotifications = 0;
                    $.map(that.notifications, function (notification, key) {
                        that.notifications[key].seen = true;
                    });
                    if(that.redirect) {
                        that.redirect = false
                        window.location.href = base_url + 'allnotifications'
                    }
                });
            }
        },
        watch: {
            pusher: function (val) {
                if(val !== null) {
                    this.subscribeToPrivateMessageChannel(current_username);
                }
            },
            isShowUCM: function (val) {
              if(val) {
                  $('.is-shown-un').addClass('is-visible')
              } else {
                  $('.is-shown-un').removeClass('is-visible')
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