<template>
    <div class="md-dialog md-dialog--user-list md-dialog--md md-dialog--who-likes" id="user-who-follow--dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface">
                <div>
                    <header class="md-dialog__header panel-post">
                        <div class="layout-m-l-1 md-layout md-align md-align--start-center">
                            <i class="icon icon-participant" style="margin-top: 4px"></i>
                            <span class="layout-m-l-1">{{ timelineUserName }}&apos;s followers</span>
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
                        <template v-if="loading && !noItem">
                            <div class="loading-wrapper">
                                <div class="ft-loading" style="background-color: transparent">
                                    <span class="ft-loading__dot"></span>
                                    <span class="ft-loading__dot"></span>
                                    <span class="ft-loading__dot"></span>
                                </div>
                            </div>
                        </template>
                        <template v-else-if="!noItem">
                            <div class="md-list md-list--likes md-list--dense">
                                <div class="md-list__item" v-for="(item, index) in filterUserSearch" :key="index+'user-following-list'">
                                    <a :href="userLink(item)" class="md-list__item-icon user-avatar"  :title="'@' + item.username" v-bind:style="{ backgroundImage: 'url(' + userAvatar(item) +')'}">
                                    </a>
                                    <div class="md-list__item-content">
                                        <div class="md-list__item-primary md-align md-align--start-center md-layout">
                                            <a :href="userLink(item)"
                                               :title="'@' + item.username"
                                               class="user-name user ft-user-name">
                                                {{item.name}}
                                            </a>
                                        </div>
                                        <div class="md-layout-spacer">
                                        </div>
                                        <template v-if="!authUser">
                                            <button v-if="item.following_status == 'Following'"  class="btn btn-sm ft-btn-primary pos-rel ft-btn-primary--outline" @click="removeFollowers(item, index)">
                                                <span class="absolute-loader hidden">
                                                        <span class="ft-loading">
                                                            <span class="ft-loading__dot"></span>
                                                            <span class="ft-loading__dot"></span>
                                                            <span class="ft-loading__dot"></span>
                                                        </span>
                                                    </span>
                                                <span class="true">Remove</span>
                                            </button>
                                        </template>
                                        <template v-else="">
                                            <button v-if="item.following_status == 'Following'"  class="btn btn-sm ft-btn-primary pos-rel ft-btn-primary--outline" data-noreload="true" :data-timeline-id="item.id" data-toggle="follow" data-following="true">
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
                        <template v-else="">
                            <div class="text-center"> No followers found!</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                participantList: [],
                filterParticipantList: [],
                defaultImage: 'default.png',
                filterSearch: '',
                offset: 0,
                noItem: false,
                timelineUserName: '',
                hasMorePost: true,
                inProgress: false,
                interact: false
            }
        },
        methods: {
            scrollFetchInit: function () {
                let that = this
                $('#user-who-follow--dialog .md-dialog__body--scrollable').scroll(function() {
                    if($(this).scrollTop() + $(this).innerHeight() > ($(this)[0].scrollHeight - 32)) {
                        setTimeout(function() {
                            if(!that.inProgress && that.hasMorePost ){
                                that.isFetchingBottom = true
                                that.getList()
                                that.inProgress = true
                                that.participantList = []
                            }
                        }, 100)
                    }
                });
            },
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
                let _user_id = $('#follow-userid').val()
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/get-followers',
                    data: {
                        user_id: _user_id,
                        _token: _token,
                        paginate: 5,
                        offset: this.offset
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        for(let i = 0;i<response.data.followers.length; i++) {
                            that.participantList.push(response.data.followers[i])
                        }
                        if(!i) {
                            if(that.interact) {
                                that.noItem = true
                            } else {
                                that.hasMorePost = false
                            }
                        }
                        that.interact = true
                        that.offset += response.data.followers.length
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            filterList: function(item) {
                let o = item.username.search(this.filterSearch)
                return o != -1
            },
            removeFollowers: function (item, index) {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                let _user_id = item.id
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/remove-follower',
                    data: {
                        user_id: _user_id,
                        _token: _token,
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        that.participantList.splice(index,1)
                        that.offset--
                        materialSnackBar({messageText: 'User removed successfully', autoClose: true })
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            }
        },
        mounted () {
            let that = this
            let dialog = $('#user-who-follow--dialog').MaterialDialog({show: false});
            dialog.on('ca.dialog.hidden', function () {
                that.participantList = []
                that.offset = 0
                that.noItem = false
            });
            this.timelineUserName = $('#follow-username').val()
            dialog.on('ca.dialog.show', function () {
                that.getList()
            });
            this.scrollFetchInit()
        },
        computed: {
            filterUserSearch: function () {
                if(this.filterSearch == '') {
                    return  this.participantList
                }
                return this.participantList.filter(this.filterList);
            },
            authUser: function () {
                return $('#follow-userid').val() == current_username
            },
            loading: function () {
                return !this.participantList.length
            }
        }
    }
</script>