<template>
    <div>
        <post-theater-view></post-theater-view>
        <post-wholikes-view></post-wholikes-view>
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
            <template v-if="isLoadingCurrent">
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
            <div v-for="postItem in currentItemList" class="panel panel-default timeline-posts__item panel-post" :id="'ft-post'+postItem.id">
                <post-header :post-data="postItem" :date="postItem.created_at"></post-header>
                <div class="panel-body">
                    <post-description :post-html="postItem.description"></post-description>
                    <post-youtube :post-you-tube="postItem.youtube_video_id" :you-tube-title="postItem.youtube_title"></post-youtube>
                    <post-image-viewer :post-img="postItem.images"></post-image-viewer>
                    <post-sound-cloud :soundcloud="postItem.soundcloud_id"></post-sound-cloud>
                </div>
                <post-comment :post-id="postItem.id"></post-comment>
            </div>
            <div v-for="(postItem, index) in itemList" :key="postItem.id" class="panel panel-default timeline-posts__item panel-post" :id="'ft-post'+postItem.id">
                <post-header :post-data="postItem" :date="postItem.created_at"></post-header>
                <div class="panel-body">
                    <post-description :post-html="postItem.description"></post-description>
                    <post-youtube :post-you-tube="postItem.youtube_video_id" :you-tube-title="postItem.youtube_title"></post-youtube>
                    <post-image-viewer :post-index="index" :post-img="postItem.images"></post-image-viewer>
                    <post-sound-cloud :soundcloud="postItem.soundcloud_id"></post-sound-cloud>
                </div>
                <post-comment :post-index="index" :post-id="postItem.id" :post-item="postItem"></post-comment>
            </div>
            <div v-if="isFetchingBottom" class="ft-loading">
                <span class="ft-loading__dot"></span>
                <span class="ft-loading__dot"></span>
                <span class="ft-loading__dot"></span>
            </div>
            <div class="text-center" v-if="!hasMorePost">
                No more posts to fetch
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
    import postImageViewer from './child/postImageViewer'
    import postYouTube from './child/postYouTube'
    import postSoundCloud from './child/postSoundCloud'
    import postHeader from './child/postHeader'
    import postComment from './child/postComment'
    import postTheaterView from './child/postTheaterView'
    import postWhoLikesView from './child/postWhoLikesView'
    import { mapGetters } from 'vuex'

    let axios = window.axios
    let custTomData = {
        isFetchingBottom: false,
        currentItemList: [],
        isLoadingCurrent: false,
        autoUpdate: 60,
        dummy: [],
        inProgress: false,
        hasMorePost: true,
        offset: 0
    }
    let vmThat;
    export default {
        props: {
            newPostAdded: false
        },
        data: function () {
            return custTomData
        },
        methods: {
            since(date) {
                return new Date(date).getTime()
            },
            getDefaultData: function () {
                let that = this
                let username = ''
                let paginate = 4
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'get-posts',
                    data: {
                        username: current_username,
                        paginate: paginate,
                        _token: _token,
                        offset: that.offset
                    }
                }).then( function (response) {
                    console.log(response)
                    if (response.status ==  200) {
                        let posts = response.data[0].posts;
                        let i = 0
                        $.each(posts, function(key, val) {
                            that.$store.commit('ADD_POST_ITEM_LIST', val)
                            i++
                        });
                        setTimeout(function () {
                            emojify.run();
                            hashtagify();
                            mentionify();
                        }, 500)
                        that.inProgress = false
                        that.hasMorePost = i == paginate;
                        that.offset += i
                        that.isFetchingBottom = false
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            },
            scrollFetchInit: function () {
                let that = this
                $(window).scroll(function() {
                    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                        if(!that.inProgress && that.hasMorePost ){
                            that.isFetchingBottom = true
                            that.getDefaultData()
                            that.inProgress = true
                        }
                    }
                });
            },
            fetchNewOnePost: function (postId) {
                this.fetchNew(postId)
            },
            fetchNew: function (postId){
                custTomData.isLoadingCurrent = true
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
                    let that = this
                    custTomData.isLoadingCurrent = false
                    if (response.status ==  200) {
                        let post = response.data[0].post;
                        vmThat.$store.commit('ADD_POST_ITEM_LIST',{data:post[0], postFrom: 'timeline'})
                        setTimeout(function () {
                            hashtagify()
                            mentionify()
                        }, 1000)
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            }
        },
        mounted () {
            let that = this
            vmThat = this
            setTimeout(function () {
                that.getDefaultData()
                that.scrollFetchInit()
            }, 1000)
        },
        components: {
            'post-description': postDescription,
            'post-image-viewer': postImageViewer,
            'post-sound-cloud': postSoundCloud,
            'post-youtube': postYouTube,
            'post-header': postHeader,
            'post-comment': postComment,
            'post-theater-view': postTheaterView,
            'post-wholikes-view': postWhoLikesView
        },
        computed: {
            ...mapGetters({
                itemList: 'postItemList'
            }),
            isLoading () {
                return this.itemList.length === 0
            }
        }
    }
</script>
