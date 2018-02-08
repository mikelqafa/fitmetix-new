<template>
    <div data-width="5" class="ft-chat" style="width: 100%;max-width: none">
        <template v-if="hasItem">
            <div class="ft-chat__header">Notifications</div>
            <div class="">
                <a :href="notificationUrl(item)" v-for="(item, index) in notifications" :data-nid="item.id" :key="'all-nt'+item.id" class="md-menu__item ft-chat__item">
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
                                <div class="md-layout-spacer"></div>
                                <div class="md-layout ft-nt-group md-layout--row" v-if="item.type == 'follow_requested' && (item.type !== 'follow_requested_accept' || item.type !=='follow_requested_deny')">
                                    <a class="md-list__item-secondary-action color-deny" @click="denyRequest(item, index)" href="#" title="Deny">
                                        <i class="icon icon-close"></i>
                                    </a>
                                    <a class="md-list__item-secondary-action color-accept" @click="acceptRequest(item, index)" href="#" title="Accept">
                                        <i class="icon icon-accept"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="md-list--abs">
                                <div class="md-layout ft-nt-group md-layout--row"  v-if="item.type == 'follow_requested_accept'">
                                    <div class="color-accept" title="Accepted">
                                        Accepted
                                    </div>
                                </div>
                                <div class="md-layout ft-nt-group md-layout--row"  v-if="item.type == 'follow_requested_deny'">
                                    <div class="color-deny">
                                        Denied
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="text-enter layout-m-t-1" v-if="hasItemNoti">
                    <button class="btn btn-block ft-btn-primary" @click="loadMore">Load More</button>
                </div>
            </div>
        </template>
        <template v-else="">
            <div class="ft-chat__header">No Notifications</div>
        </template>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    export default {
        data: function () {
            return {
                autoUpdate: 60,
                unreadNotifications: 0,
                notificationsLoaded: false,
                notificationsLoading: false,
                hasMoreItem: true
            }
        },
        mounted () {
            let that = this
            setTimeout(function(){
                that.$store.dispatch('markNotificationsRead',{})
            }, 1000)
        },
        methods: {
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
                    case 'follow_requested':
                        url =  base_url + item.notified_from.username
                        break
                    case 'follow_requested_accept':
                        url =  base_url + item.notified_from.username
                        break
                    case 'join_event':
                        url =  base_url + item.notified_from.username
                        break
                    case 'follow_requested_deny':
                        url =  base_url + item.notified_from.username
                        break
                    case 'accept_follow_request':
                        url =  base_url + item.notified_from.username
                        break
                    case 'unlike_post':
                        url =  base_url + 'post/'+item.post_id
                        break
                }
                return url
            },
            fetchOldNotification: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                let offset = this.$store.state.notiSetting.offset
                let hasItem = true
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url+'ajax/get-notifications',
                    data :{
                        _token: _token,
                        offset:offset,
                        paginate:5
                    }
                }).then( function (response) {
                    let notifications = response.data.notifications
                    for(let i=0; i< notifications.length; i++) {
                        that.$store.commit('ADD_NOTIFICATION', notifications[i])
                    }
                    offset += notifications.length
                    if(notifications.length == 5) {
                        hasItem = true
                    } else {
                        hasItem = false
                    }
                    that.$store.commit('SET_NOTI_SETTING', {offset: offset, hasItem: hasItem})
                }).catch(function(error) {
                    console.log(error)
                })
            },
            notificationImageUrl: function (item) {
                let url = ''
                switch(item.type) {
                    case 'like_post':
                        if(item.link !== '' && item.link !== null ) {
                            url =  getThumbImage(asset_url + 'uploads/users/gallery/'+item.link)
                        }
                        break
                    case 'unlike_post':
                        if(item.link !== '' && item.link !== null ) {
                            url =  getThumbImage(asset_url + 'uploads/users/gallery/'+item.link)
                        }
                        break
                }
                return url
            },
            userLink: function (username) {
                return base_url + username
            },
            loadMore: function () {
                this.fetchOldNotification()
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
            acceptRequest: function (item, index) {
                let _token = $("meta[name=_token]").attr('content')
                this.process = true
                let that = this
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url+'ajax/follow-accept',
                    data :{
                        _token: _token,
                        user_id: item.notified_from.id
                    }
                }).then( function (response) {
                    that.process = false
                    if (response.status ==  200) {
                        materialSnackBar({autoClose: true, messageText:'Follow request accepted'})
                        axios({
                            method: 'post',
                            responseType: 'json',
                            url: base_url+'ajax/notification-reacted',
                            data :{
                                _token: _token,
                                type: 'follow_requested_accept',
                                notification_id: item.id
                            }
                        }).then( function (response) {
                            console.log(response)
                            if (response.status ==  200) {
                                that.$store.commit('CHANGE_NOTIFICATION_TYPE', {index: index, changed: 'follow_requested_accept'})
                            }
                        })
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            },
            denyRequest: function (item, index) {
                let _token = $("meta[name=_token]").attr('content')
                this.process = true
                let that = this
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url+'ajax/follow-reject',
                    data :{
                        _token: _token,
                        user_id: item.notified_from.id

                    }
                }).then( function (response) {
                    that.process = false
                    if (response.status ==  200) {
                        axios({
                            method: 'post',
                            responseType: 'json',
                            url: base_url+'ajax/notification-reacted',
                            data :{
                                _token: _token,
                                type: 'follow_requested_deny',
                                notification_id: item.id
                            }
                        }).then( function (response) {
                            if (response.status ==  200) {
                                that.$store.commit('CHANGE_NOTIFICATION_TYPE', {index: index, changed: 'follow_requested_deny'})
                            }
                        })
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            }
        },
        computed: {
           ...mapGetters({
                    notifications: 'notification',
                    hasItemNoti: 'hasItemNoti'
                }),
            hasItem() {
                return this.notifications.length !== 0
            },

        }
    }
</script>