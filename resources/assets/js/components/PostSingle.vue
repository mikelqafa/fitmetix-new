<template>
    <div>
        <app-post-share></app-post-share>
        <post-theater-view></post-theater-view>
        <post-wholikes-view></post-wholikes-view>
        <template v-if="!noPostFound">
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
            </template>
            <template v-else="">
                <div v-for="(postItem, index) in itemList" :key="postItem.id" class="panel panel-default timeline-posts__item panel-post" :id="'ft-post'+postItem.id">
                    <post-header :post-data="postItem" :post-index="index" :date="postItem.created_at"></post-header>
                    <div class="panel-body">
                        <post-description :post-html="postItem.description"></post-description>
                        <post-youtube :post-you-tube="postItem.youtube_video_id" :you-tube-title="postItem.youtube_title"></post-youtube>
                        <post-image-viewer :post-event="postItem.event" :post-index="index" :post-img="postItem.images"></post-image-viewer>
                        <post-event :post-item="postItem" :post-index="index" :post-img="postItem.images"></post-event>
                        <post-sound-cloud :soundcloud="postItem.soundcloud_id"></post-sound-cloud>
                    </div>
                    <post-comment :post-index="index" :post-id="postItem.id" :post-item="postItem"></post-comment>
                </div>
            </template>
        </template>
        <template v-else="">
            <div class="text-center">
                No Post Found
            </div>
        </template>
    </div>
</template>

<script>
    import postDescription from './child/postDescription'
    import postImageViewer from './child/postImageViewer'
    import postEvent from './child/postEvent'
    import postYouTube from './child/postYouTube'
    import postSoundCloud from './child/postSoundCloud'
    import postHeader from './child/postHeader'
    import postComment from './child/postComment'
    import postTheaterView from './child/postTheaterView'
    import postWhoLikesView from './child/postWhoLikesView'
    import appPostShare from './child/appPostShare'
    import { mapGetters } from 'vuex'

    export default {
        data: function () {
            return  {
                autoUpdate: 60,
                inProgress: false,
                noPostFound: false
            }
        },
        methods: {
            since(date) {
                return new Date(date).getTime()
            },
            fetchNew: function (postId){
                let that = this
                let username = current_username
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'get-single-post',
                    data: {
                        username: current_username,
                        _token: _token,
                        post_id: postId
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        let post = response.data[0].post;
                        that.$store.commit('ADD_SINGLE_POST_ITEM',{data: post} )
                        setTimeout(function () {
                            emojify.run();
                            hashtagify();
                            mentionify();
                        }, 1000)
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            }
        },
        mounted () {
            let postId = $('#post-id')
            if(postId !== undefined && postId.length) {
                this.fetchNew(postId.val())
            } else {
                this.noPostFound = true
            }
        },
        components: {
            'post-description': postDescription,
            'post-image-viewer': postImageViewer,
            'post-sound-cloud': postSoundCloud,
            'post-youtube': postYouTube,
            'post-header': postHeader,
            'post-event': postEvent,
            'post-comment': postComment,
            'post-theater-view': postTheaterView,
            'post-wholikes-view': postWhoLikesView,
            'app-post-share': appPostShare
        },
        computed: {
            ...mapGetters({
                    itemList: 'postItemList'
                }),
            isLoading () {
                return !(this.itemList.length !== 0 && this.itemList[0] !== undefined)
            }
        }
    }
</script>
