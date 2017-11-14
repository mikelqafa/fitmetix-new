<!--
created_at: "2017-11-01 13:19:09"
deleted_at: null
description:" :full_moon_with_face: ↵"
id: 80
location: ""
shared_post_id: null
soundcloud_id: ""
soundcloud_title: ""
timeline_id: 1
type: null
updated_at: "2017-11-01 13:19:09"
user_id: 1
youtube_title: ""
youtube_video_id: "",
-->
<template>
    <div>
        <template v-if="isLoading">
            <div class="lg-loading-skeleton panel panel-default timeline-posts__item panel-post">
                <div class="panel-heading no-bg post-avatar md-layout md-layout--row">
                    <div class="user-avatar lg-loadable"></div>
                    <div class="md-layout md-layout--column">
                        <div class="user-meta-info lg-loadable"></div>
                        <div class="user-meta-info lg-loadable user-meta-info--sm"></div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="lg-loadable lg-loadable--text"></div>
                    <div class="lg-loadable lg-loadable--text--lg lg-loadable--text"></div>
                    <div class="lg-loadable lg-loadable--text--sm lg-loadable--text"></div>
                </div>
            </div>
            <div class="lg-loading-skeleton panel panel-default timeline-posts__item panel-post">
                <div class="panel-heading no-bg post-avatar md-layout md-layout--row">
                    <div class="user-avatar lg-loadable"></div>
                    <div class="md-layout md-layout--column">
                        <div class="user-meta-info lg-loadable"></div>
                        <div class="user-meta-info lg-loadable user-meta-info--sm"></div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="lg-loadable lg-loadable--text"></div>
                    <div class="lg-loadable lg-loadable--text--lg lg-loadable--text"></div>
                    <div class="lg-loadable lg-loadable--text--sm lg-loadable--text"></div>
                </div>
            </div>
        </template>
        <template v-else="">
            <div v-for="postItem in itemList" class="panel panel-default timeline-posts__item panel-post" :id="postItem.id">
                <div class="panel-heading no-bg">
                    <div class="post-author">
                        <div class="post-options">
                            <a href="#" class="dropdown-togle">
                                <i class="icon icon-options"></i>
                            </a>
                        </div>
                        <div class="user-avatar">
                            <a href="#">
                                <img src="" alt="postItem.userName" title="postItem.userName">
                            </a>
                        </div>
                        <div class="user-post-details">
                            <ul class="list-unstyled no-margin">
                                <li>
                                    <a href="//localhost:3004/fitmetix/public/mikele" title="" data-toggle="tooltip" data-placement="top" class="user-name user ft-user-name" data-original-title="@mikele">
                                        mikele
                                    </a>
                                    <div class="small-text">
                                    </div>
                                </li>
                                <li>
                                    <!--<timeago :since="postItem.time"></timeago>-->
                                    <timeago :since="since(postItem.created_at)"
                                            :auto-update="autoUpdate"
                                            class="timeago"></timeago>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <post-description :post-html="postItem.description"></post-description>
                    <post-youtube></post-youtube>
                    <post-image></post-image>
                    <post-sound-cloud></post-sound-cloud>
                </div>
                <div class="panel-footer socialite">
                    <ul class="list-inline footer-list pos-rel">
                        <li class="hidden">
                            <a href="#" class="like-post like-87" data-post-id="87">
                                <i class="icon icon-like"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="like-post unlike unlike-87" data-post-id="87">
                                <i class="icon icon-liked unlike"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="show-comments">
                                <i class="icon icon-comment"></i>
                            </a>
                        </li>
                        <li class="text-center full-center ">
                            <a href="#" class="show-users-modal" data-html="true" data-heading="Likes" data-users="7" data-original-title="Mikel">
                        <span class="count-circle">
                            <i class="icon icon-like"></i>
                        </span>
                                <span class="hidden-sm hidden-xs">Likes</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="show-all-comments">
                        <span class="count-circle">
                            <i class="icon icon-comment"></i>
                        </span>1
                                <span class="hidden-sm hidden-xs">comments</span>
                            </a>
                        </li>
                        <li class="pull-right">
                            <a href="//localhost:3004/fitmetix/public/post/87">
                                <i class="icon icon-share"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </template>
    </div>
</template>
<style>
    .user-avatar {
        overflow: hidden;
    }
</style>
<script>
    import postDescription from './child/postDescription'
    import postImage from './child/postImage'
    import postYouTube from './child/postYouTube'
    import postSoundColud from './child/postSoundColud'

    let axios = window.axios
    export default {
        data: function () {
            return {
                itemList: [],
                autoUpdate: 60
            }
        },
        computed: {
            isLoading () {
                return this.itemList.length === 0
            }
        },
        methods: {
            since(date) {
                return new Date(date).getTime()
            },
            getDefaultData: function () {
                let that = this
                let username = ''
                let paginate = 5
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'get-posts',
                    data: {
                        username: current_username,
                        paginate: paginate,
                        _token: _token
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        let posts = response.data[0].posts;
                        $.each(posts, function(key, val) {
                            that.itemList.push(val);
                            console.log(val)
                        });
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            }
        },
        mounted () {
            let that = this
            setTimeout(function () {
                that.getDefaultData()
            }, 1000)
        },
        components: {
            'post-description': postDescription,
            'post-image': postImage,
            'post-sound-cloud': postSoundColud,
            'post-youtube': postYouTube
        }
    }
</script>