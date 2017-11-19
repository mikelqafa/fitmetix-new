<template>
    <div class="panel-heading no-bg">
        <div class="post-author">
            <div class="post-options">
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
                            {{ timeLineData.name }}
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
                          <a target="_blank" :href="locationLink">
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
    export default {
        props: {
            postData: {},
            date: ''
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
                return this.date != '' ? new Date(this.date).getTime() : ''
            },
            locationLink () {
                return this.postData.location !== '' ? base_url + 'get-location/' + this.postData.location : ''
            },
            userLink () {
                return base_url + this.timeLineData.username
            },
            userAtTitle() {
                return '@' + this.timeLineData.username
            },
            userAvatar () {
                // console.log(this.timeLineData.avatar_url[0].source)
                // return this.timeLineData.avatar_url  !== undefined ? base_url + 'user/avatar/' : base_url + this.defaultImage
                return base_url + 'images/' + this.defaultImage
            }
        },
        methods: {
            openPostDialog: function () {
                $('#post-option-dialog').addClass('ft-dialog--open')
            }
        },
        mounted () {
            this.timeLineData = this.postData.timeline
        }
    }
</script>