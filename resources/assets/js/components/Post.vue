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
                <post-header :timeline-data="postItem.timeline" :date="postItem.created_at"></post-header>
                <div class="panel-body">
                    <post-description :post-html="postItem.description"></post-description>
                    <post-youtube :post-you-tube="postItem.youtube_video_id" :you-tube-title="postItem.youtube_title"></post-youtube>
                    <post-image :post-images="dummy"></post-image>
                    <post-sound-cloud :soundcloud="postItem.soundcloud_id"></post-sound-cloud>
                </div>
                <post-comment></post-comment>
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
    import postSoundCloud from './child/postSoundCloud'
    import postHeader from './child/postHeader'
    import postComment from './child/postComment'

    let axios = window.axios
    export default {
        data: function () {
            return {
                itemList: [],
                autoUpdate: 60,
                dummy: []
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
                let paginate = 50
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
            'post-sound-cloud': postSoundCloud,
            'post-youtube': postYouTube,
            'post-header': postHeader,
            'post-comment': postComment
        }
    }
</script>