<template>
    <div>
        <app-post-share></app-post-share>
        <post-theater-view></post-theater-view>
        <post-wholikes-view></post-wholikes-view>
        <event-participate-list></event-participate-list>
        <template v-if="!noPostFound || alreadyHavePost">
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
                <div v-show="!singlePost" class="lg-loading-skeleton panel panel-default timeline-posts__item panel-post">
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
                <div v-for="(postItem, index) in itemList" :key="index+'post'+postItem.id" class="slideFadeIn panel panel-default ft-theme--color timeline-posts__item panel-post" :id="'ft-post'+postItem.id">
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
                <div v-if="isFetchingBottom" class="ft-loading ft-loading--transparent" style="margin: 50px 0">
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                </div>
                <div class="text-center no-found" v-if="!hasMorePost && !singlePost">
                    {{$t('post.no_more') }}
                </div>
            </template>
        </template>
        <template v-else="">
            <div class="text-center  no-found">
                {{$t('post.n_f') }}
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
    import postEvent from './child/postEvent'
    import postYouTube from './child/postYouTube'
    import postSoundCloud from './child/postSoundCloud'
    import postHeader from './child/postHeader'
    import postComment from './child/postComment'
    import postTheaterView from './child/postTheaterView'
    import postWhoLikesView from './child/postWhoLikesView'
    import appPostShare from './child/appPostShare'
    import eventParticipateList from './child/eventParticipateList'
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
        offset: 0,
        singlePost: false,
        noPostFound: false,
        alreadyHavePost: true,
        interact: false
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
                let username = current_username
                let paginate = 4
                let _token = $("meta[name=_token]").attr('content')
                let url = ''
                that.inProgress = true
                if($('#saved').length && $('#saved').val()) {
                    url = base_url + 'ajax/get-saved-post'
                } else {
                    url = base_url + 'get-posts'
                }
                alert(url)
                let profile_timeline = false
                if($('#timeline_username').length) {
                    username =  $('#timeline_username').val()
                    //url = base_url + 'get-user-posts'
                    profile_timeline = true
                }
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: url,
                    data: {
                        username: username,
                        paginate: paginate,
                        _token: _token,
                        offset: that.offset,
                        profile_timeline: profile_timeline
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        let posts = response.data[0].posts;
                        let i = 0
                        $.each(posts, function(key, val) {
                            that.$store.commit('ADD_POST_ITEM_LIST', val)
                            i++
                        });
                        if(!i) {
                            if(!that.interact) {
                                that.alreadyHavePost = false
                                that.noPostFound = true
                            } else {
                                that.noPostFound = true
                            }
                        } else {
                            that.interact = true
                        }
                        setTimeout(function () {
                            console.log("assssssssssssssssssssssssssssss")
                            //emojify.run();
                            //hashtagify();
                            //mentionify();
                        }, 500)
                        that.inProgress = false
                        that.hasMorePost = i == paginate;
                        that.offset += i
                        that.isFetchingBottom = false
                    }
                }).catch(function(error) {
                    that.inProgress = false
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
                        }
                    }
                });
            },
            fetchNewOnePost: function (postId) {
                this.fetchNew(postId)
            },
            fetchNew: function (postId){
                custTomData.isLoadingCurrent = true
                let that = this
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
                    custTomData.isLoadingCurrent = false
                    that.noPostFound = false
                    if (response.status ==  200) {
                        let post = response.data[0].post;
                        post.timeline = response.data[0].timeline
                        that.offset++;
                        vmThat.$store.commit('ADD_POST_ITEM_LIST',{data: post, postFrom: 'timeline'})
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
            let that = this
            vmThat = this
            that.getDefaultData()
            that.scrollFetchInit()
            console.log(this.$t('message.hello'))
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
            'app-post-share': appPostShare,
            'event-participate-list': eventParticipateList
        },
        watch:{
            itemList: function (val) {
                if(val.length) {
                    this.noPostFound = false
                    this.interact = true
                } else {
                    if(this.interact) {
                        this.alreadyHavePost = false
                        this.noPostFound = true
                    }
                }
            }
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
