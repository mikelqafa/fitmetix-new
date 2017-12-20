<template>
    <div>
        <post-theater-view></post-theater-view>
        <post-wholikes-view></post-wholikes-view>
        <template v-if="!noPostFound || alreadyHavePost">
            <template v-if="isLoading">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-6">
                            <div class="lg-loading-skeleton ft-image-post">
                                <div class="ft-image-post__item lg-loadable">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-6">
                            <div class="lg-loading-skeleton ft-image-post">
                                <div class="ft-image-post__item lg-loadable">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="lg-loading-skeleton ft-image-post">
                                <div class="ft-image-post__item lg-loadable">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else="">
                <div class="post-filters">
                    <div class="ft-grid">
                        <div v-for="(postItem, index) in itemList" :key="postItem.id" class="ft-grid__item" :id="'ft-post'+postItem.id">
                            <post-image-viewer :post-event="postItem.event" :post-index="index" :post-img="postItem.images"></post-image-viewer>
                        </div>
                    </div>
                </div>
                <div v-if="isFetchingBottom" class="ft-loading">
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                </div>
                <div class="hidden text-center" v-if="!hasMorePost">
                    That&apos;s all for now
                </div>
            </template>
        </template>
        <template v-else="">
            <div class="text-center">
                No Gallery Found
            </div>
        </template>
    </div>
</template>
<script>
    import postImageViewer from './child/bgImageViewer'
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
        offset: 0,
        noPostFound: false,
        alreadyHavePost: true,
        interact: false,
        singlePost: false,
        onlyImagePost: false
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
                let url = ''
                let location = ''
                let hashtag = ''
                let data = {}
                data.username =  current_username
                data.offset =  0
                data.paginate =  10

                let l = $('#galleryByLocation')
                if(l !== undefined && l.length) {
                    location = l.val()
                    url = base_url + 'get-gallery-by-location'
                    data.url = url
                    data.location = location
                }

                let u = $('#galleryByUsername')
                if(u !== undefined && u.length) {
                    username =   u.val()
                    url = base_url + 'get-gallery-by-username'
                    data.url = url
                    data.username = username
                }

                let h = $('#galleryByHashTag')
                if(h !== undefined && h.length) {
                    hashtag = h.val()
                    url = base_url + 'get-gallery-by-hashtag'
                    data.url = url
                    data.hashtag = hashtag
                }

                this.onlyImagePost = true

                let paginate = 4

                let _token = $("meta[name=_token]").attr('content')

                data._token = _token
                console.log(data)
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: url,
                    data :data
                }).then( function (response) {
                    if (response.status ==  200) {
                        console.log(response)
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
                // TODO disable scroll fectch
                /*$(window).scroll(function() {
                    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                        if(!that.inProgress && that.hasMorePost ){
                            that.isFetchingBottom = true
                            that.getDefaultData()
                            that.inProgress = true
                        }
                    }
                });*/
            },
            fetchNewOnePost: function (postId) {
                this.fetchNew(postId)
            },
            fetchNew: function (postId){
                custTomData.isLoadingCurrent = true
                let _token = $("meta[name=_token]").attr('content')

                let username = current_username
                if($('#timeline_username').length) {
                    username =  $('#timeline_username').val()
                }

                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'get-single-post',

                    data: {
                        username: username,
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
            that.getDefaultData()
        },
        components: {
            'post-image-viewer': postImageViewer,
            'post-theater-view': postTheaterView,
            'post-wholikes-view': postWhoLikesView
        },
        computed: {
            ...mapGetters({
                itemList: 'postItemList'
            }),
            isLoading () {
                return this.itemList.length == 0
            }
        }
    }
</script>
