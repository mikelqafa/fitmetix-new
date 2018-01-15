<template>
    <div data-width="5" class="ft-chat" style="width: 100%;max-width: none">
        <template v-if="hasItem">
            <div class="ft-chat__header">Notifications</div>
            <div class="">
                <a :href="notificationUrl(item)" v-for="item in notifications" :data-nid="item.id" :key="item.id" class="md-menu__item ft-chat__item">
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
                notificationsLoading: false
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
            since: function (date) {
                let str = date
                if(date != '') {
                    str = date
                    let res = str.split(' ')
                    str = res[0]+'T'+res[1]
                    str.replace(/\s/, 'T')
                }
                return date != '' ? new Date(str+'Z').getTime() : new Date().getTime()
            }
        },
        computed: {
                ...mapGetters({
                    notifications: 'notification'
                }),
        hasItem() {
            return this.notifications.length !== 0
        }
    }
    }
</script>