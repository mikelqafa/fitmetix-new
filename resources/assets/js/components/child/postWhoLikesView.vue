<template>
    <div class="md-dialog md-dialog--md md-dialog--user-list md-dialog--who-likes" id="post-who-likes-dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface">
                <div>
                    <header class="md-dialog__header panel-post">
                        <div class="layout-m-l-1 md-layout md-align md-align--start-center">
                            <i class="icon icon-participant" style="margin-top: 4px"></i>
                            <span class="layout-m-l-1">Who Likes to this post</span>
                        </div>
                        <div class="md-layout-spacer"></div>
                        <a href="javascript:;" style="margin-right: 15px"
                           class="md-button md-button--icon md-dialog__header-action-dismissive" data-action="dismissive">
                            <i class="icon icon-close"></i>
                        </a>
                    </header>
                    <div style="position:relative; padding: 4px 16px 8px 16px;">
                        <input placeholder="Search user" v-model="filterSearch" class="form-control" type="text"/>
                    </div>
                    <div class="md-dialog__body md-dialog__body--scrollable" style="padding-left: 0; padding-right: 0">
                        <template v-if="loading">
                            <div v-if="hasItem">
                                <div class="loading-wrapper">
                                    <div class="ft-loading" style="background-color: transparent">
                                        <span class="ft-loading__dot"></span>
                                        <span class="ft-loading__dot"></span>
                                        <span class="ft-loading__dot"></span>
                                    </div>
                                </div>
                            </div>
                            <div v-else="" class="text-center">
                                <h3>No likes yet</h3>
                            </div>
                        </template>
                        <template v-else="">
                            <div class="md-list md-list--likes md-list--dense">
                                <div class="md-list__item has-divider" v-for="item in filterUserSearch">
                                    <a :href="userLink(item)" class="md-list__item-icon user-avatar"  :title="'@' + item.username" v-bind:style="{ backgroundImage: 'url(' + userAvatar(item) +')'}">
                                    </a>
                                    <div class="md-list__item-content">
                                        <div class="md-list__item-primary md-algin md-align--start-center md-layout">
                                            <a :href="userLink(item)"
                                               :title="'@' + item.username"
                                               class="user-name user ft-user-name">
                                                {{item.name}}
                                            </a>
                                        </div>
                                        <div class="md-layout-spacer">
                                        </div>
                                        <template v-if="sameUser(item)">

                                        </template>
                                        <template v-else="">
                                            <button v-if="item.follow_status == 'pending'"  class="btn btn-sm ft-btn-primary pos-rel ft-btn-primary--outline" data-noreload="true" :data-timeline-id="item.id" data-following="true">
                                                <span class="absolute-loader hidden">
                                                    <span class="ft-loading">
                                                        <span class="ft-loading__dot"></span>
                                                        <span class="ft-loading__dot"></span>
                                                        <span class="ft-loading__dot"></span>
                                                    </span>
                                                </span>
                                                <span class="false">Follow</span>
                                                <span class="true">Request sent</span>
                                            </button>
                                            <button v-else-if="item.follow_status == 'following'" class="btn btn-sm ft-btn-primary pos-rel ft-btn-primary--outline" data-noreload="true"  :data-timeline-id="item.id" data-toggle="follow" data-following="true">
                                                <span class="absolute-loader hidden">
                                                <span class="ft-loading">
                                                    <span class="ft-loading__dot"></span>
                                                    <span class="ft-loading__dot"></span>
                                                    <span class="ft-loading__dot"></span>
                                                </span>
                                            </span>
                                                <span class="false">Follow</span>
                                                <span class="true">Following</span>
                                            </button>
                                            <button v-else="" class="btn btn-sm ft-btn-primary pos-rel ft-btn-primary--outline" data-noreload="true"  :data-timeline-id="item.id" data-toggle="follow" data-following="false">
                                                <span class="absolute-loader hidden">
                                                    <span class="ft-loading">
                                                    <span class="ft-loading__dot"></span>
                                                    <span class="ft-loading__dot"></span>
                                                    <span class="ft-loading__dot"></span>
                                                </span>
                                                </span>
                                                <span class="false">Follow</span>
                                                <span class="true">Following</span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex'

    export default {
        data: function () {
            return {
                participantList: [],
                filterParticipantList: [],
                defaultImage: 'default.png',
                filterSearch: '',
                hasItem: true
            }
        },
        methods: {
            userLink (item) {
                return base_url + item.username
            },
            sameUser: function (item) {
                return item.username == current_username
            },
            userAvatar (item) {
                return getThumbImage(item.avatar_url.length ? asset_url + 'uploads/users/avatars/' + item.avatar_url[0].source : base_url + 'images/' + this.defaultImage)
            },
            getList: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.participantList = []
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/get-likes-details',
                    data: {
                        post_id: that.whoLikesItem.id,
                        user_id: user_id,
                        paginate: 10,
                        offset: 0,
                        _token: _token
                    }
                }).then(function (response) {
                    console.log(response)
                    if (response.status == 200) {
                        let likes = response.data[0].post_likes_by
                        for(let i = 0; i<likes.length; i++) {
                            that.participantList.push(likes[i])
                        }
                        if(!likes.length) {
                            that.hasItem = false
                        }
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            filterList: function(item) {
                let o = item.username.search(this.filterSearch)
                return o != -1
            }
        },
        mounted () {
            let that = this
            let dialog = $('#post-who-likes-dialog').MaterialDialog({show: false});
            dialog.on('ca.dialog.hidden', function () {
                that.participantList = []
            });
            dialog.on('ca.dialog.show', function () {
                that.getList()
            });
        },
        computed: {
            filterUserSearch: function () {
                if(this.filterSearch == '') {
                    return  this.participantList
                }
                return this.participantList.filter(this.filterList);
            },
            ...mapGetters({
                postWhoLikes: 'postWhoLikes'
            }),
            loading: function () {
                return !this.participantList.length
            },
            whoLikesItem: function () {
                return this.postWhoLikes.postIndex !== undefined ? this.$store.state.postItemList[this.postWhoLikes.postIndex] : {}
            }
        }
    }
</script>