<template>
    <div>
        <post-theater-view></post-theater-view>
        <post-wholikes-view></post-wholikes-view>
        <template v-if="!noPostFound || alreadyHavePost">
            <template v-if="isLoading">
                <div class="post-filters post-filters--auto-width">
                    <div class="ft-grid">
                        <div class="ft-grid__item">
                            <div class="post-image--wrapper lg-loading-skeleton">
                                <div class="ft-card ft-card--only-image">
                                    <div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ft-grid__item">
                            <div class="post-image--wrapper lg-loading-skeleton">
                                <div class="ft-card ft-card--only-image">
                                    <div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ft-grid__item">
                            <div class="post-image--wrapper lg-loading-skeleton">
                                <div class="ft-card ft-card--only-image">
                                    <div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ft-grid__item">
                            <div class="post-image--wrapper lg-loading-skeleton">
                                <div class="ft-card ft-card--only-image">
                                    <div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else="">
                <div class="post-filters post-filters--auto-width">
                    <div class="ft-grid">
                        <div v-for="(postItem, index) in itemList" :key="postItem.id" class="ft-grid__item" :id="'ft-post'+postItem.id">
                            <post-image-viewer :gallery-view="true"  :post-id="postItem.id" :post-event="postItem.event" :post-index="index" :post-img="postItem.images"></post-image-viewer>
                        </div>
                    </div>
                </div>
                <div v-if="isFetchingBottom" class="ft-loading ft-loading--transparent" style="margin: 50px 0">
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                </div>
                <div class="text-center ft-loading ft-loading--transparent" v-if="!hasMorePost">
                    {{$t('common.n_m_g_f')}}
                </div>
            </template>
        </template>
        <template v-else="">
            <div class="text-center ft-loading ft-loading--transparent">
                {{$t('common.no')}} {{$t('common.gl_f')}}
            </div>
        </template>
    </div>
</template>
<script>
    import postImageViewer from './child/bgImageViewer'
    import postTheaterView from './child/postTheaterView'
    import postWhoLikesView from './child/postWhoLikesView'
    import { mapGetters } from 'vuex'

    let custTomDataHLU = {
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
            return custTomDataHLU
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
                data.offset =  this.offset
                data.paginate =  6

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
                        let posts = response.data[0].posts;
                        let i = 0
                        $.each(posts, function(key, val) {
                            if(val.images !== undefined && val.images.length) {
                                let obj = {}
                                obj = val
                                obj['timeline'] = response.data[0].timeline;
                                that.$store.commit('ADD_POST_ITEM_LIST', val)
                                i++
                            }
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
                        that.inProgress = false
                        that.hasMorePost = i == data.paginate;
                        that.offset += i
                        that.isFetchingBottom = false
                        setTimeout(function () {
                            emojify.run();
                            hashtagify();
                            mentionify();
                        }, 500)
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
                custTomDataHLU.isLoadingCurrent = true
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
                    custTomDataHLU.isLoadingCurrent = false
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
            this.scrollFetchInit()
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
