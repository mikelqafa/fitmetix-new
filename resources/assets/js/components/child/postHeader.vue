<template>
    <div class="panel-heading no-bg">
        <div class="post-author">
            <div class="post-options md-layout">
                <a class="ft-btn--icon" href="javascript:;" @click="emitClose" v-if="eventList">
                    <i class="icon icon-close"></i>
                </a>
                <a href="javascript:;" class="ft-btn--icon"  v-on:click="openPostDialog">
                    <i class="icon icon-options"></i>
                </a>
            </div>
            <a :href="userLink" class="user-avatar"  :title="userAtTitle" v-bind:style="{ backgroundImage: 'url(' + userAvatar +')'}">
                <img :src="userAvatar" class="hidden"  :alt="userAvatar.name" :title="timeLineData.name">
            </a>
            <div class="user-post-details">
                <div class="no-margin">
                    <div class="meta-font">
                        <a :href="userLink" data-toggle="tooltip" data-placement="top" class="user-name user ft-user-name" :title="userAtTitle" :data-original-title="userAtTitle">
                            {{ headerTitle }}
                        </a>
                        <div class="small-text">
                        </div>
                    </div>
                    <div class="md-layout meta-font">
                        <div class="ft-timeago sub-meta-info">
                            <timeago :since="since"
                                     :auto-update="autoUpdate"
                                     class="timeago"></timeago>
                        </div>
                        <div v-if="locationLink !== ''" class="ft-location layout-m-l-0">
                        <span class="post-place">
                          <a :href="locationLink">
                              <i class="fa fa-map-marker"></i> {{ postData.location }}
                          </a>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .panel-default .sub-meta-info {
        color: rgba(0,0,0,.54)
    }
    .ft-btn--icon {
        height: 24px;
        width: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<script>
    import postImageViewer from './showOnlySlider'
    export default {
        props: {
            postData: {},
            date: '',
            postIndex: 0,
            eventList:false
        },
        data: function () {
            return {
                autoUpdate: 60,
                defaultImage: 'default.png',
                timeLineData: {}
            }
        },
        computed: {
            since () {
                return this.date != '' ? new Date(this.date + 'Z').getTime() : new Date().getTime()
            },
            locationLink () {
                return this.postData.type !== null? (this.postData.location !== '' ? base_url + 'gallery/location/' + this.postData.location : '') : this.postData.location !== '' ? base_url + 'gallery/location/' + this.postData.location : ''
            },
            userLink () {
                return this.isTypeEvent ? base_url + this.postData.creator_timeline.username: base_url + this.postData.timeline.username
            },
            userAtTitle() {
                return this.isTypeEvent ? '@' + this.postData.creator_timeline.username: '@' + this.postData.timeline.username
            },
            userAvatar () {
                return this.isTypeEvent ?
                        this.postData.creator_timeline.avatar_url.length ? asset_url + 'uploads/users/avatars/' + this.postData.creator_timeline.avatar_url[0].source : base_url + 'images/' + this.defaultImage:
                        this.postData.timeline.avatar_url.length ? asset_url + 'uploads/users/avatars/' + this.postData.timeline.avatar_url[0].source : base_url + 'images/' + this.defaultImage
            },
            headerTitle () {
                return this.isTypeEvent ? this.postData.creator_timeline.name : this.postData.timeline.name
            },
            isTypeEvent: function () {
                return this.postData.type !== undefined && this.postData.type !== null && this.postData.type === 'event'
            }
        },
        methods: {
            openPostDialog: function () {
                this.$store.commit('SET_OPTIONS_MENU_ITEM', {postIndex: this.postIndex})
                $('#post-option-dialog').MaterialDialog('show')
            },
            emitClose: function () {
                this.$emit('close')
            }
        },
        mounted () {
            this.timeLineData = this.postData.timeline
        }
    }
</script>