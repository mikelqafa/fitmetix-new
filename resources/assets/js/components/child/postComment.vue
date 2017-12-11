<template>
    <div style="width: 100%">
        <div class="panel-footer ft-socialite meta-font">
            <div class="ft-comment md-layout md-layout--row">
                <div class="ft-comment__item md-layout md-layout--row">
                    <a href="javascript:;" class="ft-expression ft-expression--likes" v-bind:class="{ 'ft-expression--liked': userLiked }"
                       @click="toggleLikePost">
                        <i class="icon icon-like visible-default"></i>
                        <i class="icon icon-liked hidden-default"></i>
                    </a>
                    <template v-if="!showTheater">
                        <a href="javascript:;" class="ft-expression ft-expression--comment" v-bind:class="{ 'ft-expression--liked': userCommented }"
                           @click="commentOnPost">
                            <i class="icon icon-comment"></i>
                        </a>
                    </template>
                    <template v-else="">
                        <a href="javascript:;" class="ft-expression" @click="closeDilaogFocusComment" v-bind:class="{ 'ft-expression--liked': userCommented }">
                            <i class="icon icon-comment"></i>
                        </a>
                    </template>

                </div>
                <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow">
                    <a v-show="postLikesCount"  @click="showLikesCount" href="javascript:;" class="ft-expression ft-expression--meta">
                        <span class="icon icon-liked visible-default"></span>
                    <span class="ft-expression--meta-text">
                        {{postLikesCount}}
                    </span>
                    </a>
                    <a v-show="postCommentsCount" @click="commentOnPost" href="javascript:;" class="ft-expression  ft-expression--meta">
                        <span class="icon icon-commentcount"></span>
                    <span class="ft-expression--meta-text">
                        {{postCommentsCount}}
                    </span>
                    </a>
                </div>
                <div class="ft-comment__item">
                    <a href="javascript:;" class="ft-expression">
                        <i class="icon icon-share"></i>
                    </a>
                </div>
            </div>
        </div>
        <template v-if="!showTheater">
            <section class="zippy suggestion-list-expand" :id="expandID">
                <div class="zippy__wrapper">
                    <template v-if="commentInteract">
                        <div class="comment-textfield">
                            <form action="#">
                                <textarea v-on:keydown.13="postComment" class="ft-post__comment-form form-control"  autocomplete="off" data-post-id="" data-comment-id="" name="post_comment" placeholder="Write a comment" rows="1"></textarea>
                            </form>
                            <div class="loading-wrapper"></div>
                        </div>
                        <div class="comment-list-action md-list md-list--dense" v-if="commentItemList.length">
                            <div class="md-list__item has-divider" v-for="(item, index) in commentItemList" :data-comment-id="item.id">
                                <a :style="{ backgroundImage: 'url(' + item.user.avatar + ')'}" data-theme="m" href="//localhost:3008/fitmetix/public/Uppal" :title="'@'+item.user.username" class="md-list__item-icon user-avatar"></a>
                                <div class="md-list__item-content">
                                    <div class="md-list__item-primary">
                                        <a :href="base_url+item.user.username" :title="'@'+ item.user.username " data-original-title="@Uppal" class="user-name user ft-user-name">
                                            {{item.user.name}}
                                        </a>
                                        <div class="md-list__item-text-body" v-html="item.description"></div>
                                    </div>
                                    <div class="md-list__item-secondary md-layout md-layout--row">
                                        <a href="javascript:;" class="md-list__item-secondary-action ft-expression" :data-comment-id="item.id" v-on:click="likeUnlikeComment($event, index)"  v-bind:class="{ 'ft-expression--liked': item.isLiked }">
                                            <i class="icon icon-like visible-default"></i>
                                            <i class="icon icon-liked hidden-default"></i>
                                        </a>
                                        <a class="md-list__item-secondary-action" href="javascript:;" v-on:click="openCommentDialog">
                                            <i class="icon icon-options"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="ft-menu" v-if="commentHasMore">
                                <button type="submit" class="text-center ft-menu__item btn" v-on:click="loadMore">
                                    Load More
                                </button>
                            </div>
                        </div>
                    </template>
                    <template v-else="">
                        <div class="ft-loading">
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                        </div>
                    </template>
                </div>
            </section>
        </template>
    </div>
</template>
<style>
</style>
<script>
    import { mapGetters } from 'vuex'
    import Vue from 'vue'
    export default {
        props: {
            postId: '',
            showTheater: false,
            postItem: '',
            showSidebar: false,
            postIndex: ''
        },
        data: function () {
            return {
                base_url: base_url,
                showUserComment: 0
            }
        },
        methods: {
            closeDilaogFocusComment: function () {
                this.$emit('focuscomment')
            },
            getDefaultData: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'get-likes-comments-count',
                    data: {
                        post_id: that.postId,
                        _token: _token
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        that.$store.commit('SET_POST_META', {
                            postIndex: that.postIndex,
                            postCommentsCount: response.data[0].post_comment_count,
                            postLikesCount: response.data[0].post_likes_count,
                            userLiked: response.data[0].user_liked,
                            userCommented: that.postItem.comments !== undefined
                        })
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            toggleLikePost: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + '/ajax/like-post',
                    data: {
                        post_id: that.postId,
                        _token: _token
                    }
                }).then(function (response) {
                    console.log(response)
                    if (response.status == 200) {}
                }).catch(function (error) {
                    console.log(error)
                })
                let like = !this.userLiked
                this.$store.commit('SET_POST_META_USER_LIKED', {
                    postIndex: that.postIndex,
                    userLiked: like
                })
                this.$store.commit('SET_POST_META_LIKES_COUNT', {
                    postIndex: this.postIndex,
                    postLikesCount: !like ? this.postLikesCount - 1 : this.postLikesCount + 1
                })
            },
            commentOnPost: function () {
                $('#' + this.expandID).Zippy('toggle')
                if(!this.commentInteract) {
                    let that = this
                    setTimeout(function () {
                        that.fetchComment()
                    }, 400)
                } else {
                    this.updateZippy()
                }
            },
            updateZippy: function () {
                $('#' + this.expandID).Zippy('update')
            },
            showLikesCount: function () {
                this.$store.commit('SET_WHO_LIKES_ITEM', {postIndex: this.postIndex})
                let offset = 0
                if(this.$store.state.postItemList[this.postIndex].whoLikes !== undefined ) {
                    offset = this.$store.state.postItemList[this.postIndex].whoLikes.offset
                    offset = this.$store.state.postItemList[this.postIndex].whoLikes.offset
                    if(!this.$store.state.postItemList[this.postIndex].whoLikes.hasMore) {
                        $('#post-who-likes-dialog').MaterialDialog('show')
                        return
                    }
                }
                $('#post-who-likes-dialog').MaterialDialog('show')
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + '/ajax/get-likes-details',
                    data: {
                        post_id: that.postId,
                        paginate: 4,
                        offset: offset,
                        _token: _token
                    }
                }).then(function (response) {
                    console.log(response)
                    if(response.status == 200) {
                        let likesBy = response.data[0].post_likes_by;
                        let itemArr = []
                        for(let i = 0; i < likesBy.length;  i++) {
                            itemArr.unshift({
                                name: likesBy[i].name,
                                username: likesBy[i].username,
                                avatar: likesBy[i].avatar
                            })
                            offset++
                        }
                        that.$store.commit('SET_POST_WHO_LIKES', {
                            postIndex: that.postIndex,
                            hasMore: response.data[0].has_more_likes,
                            itemList: itemArr,
                            offset: offset
                        })
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            postComment: function (e) {
                if (e.shiftKey) {
                    if(e.which == 13) {
                        return true
                    }
                } else {
                    if(e.which != 13) {
                        return true
                    }
                }
                e.preventDefault()
                let input = e.target
                let value = e.target.value
                console.log(input, value)
                $(e.target).parent().addClass('is-loading')
                let loadingWrapper = $(e.target).parent().find('.loading-wrapper')
                if(value == '') {
                    return
                }
                loadingWrapper.html(
                        '<div class="ft-loading">'+
                        '<span class="ft-loading__dot"></span>'+
                        '<span class="ft-loading__dot"></span>'+
                        '<span class="ft-loading__dot"></span>'+
                        '</div>')
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.$store.commit('SET_POST_COMMENT_INTERACT',  {postIndex: that.postIndex, commentInteract: true})
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + '/ajax/post-comment',
                    data: {
                        post_id: that.postId,
                        description: that.nl2br(value),
                        _token: _token
                    }
                }).then(function (response) {
                    console.log(response)
                    if (response.status == 200) {
                        input.value = ''
                        that.$store.commit('SET_POST_META_COUNT',  {postIndex: that.postIndex, postCommentsCount: that.postCommentsCount+1})
                        $(e.target).parent().addClass('is-loading')
                        loadingWrapper.html('')
                        //that.userCommented
                        that.$store.commit('ADD_POST_COMMENT_ONLY',
                                {
                                    postIndex: that.postIndex,
                                    postComments: {
                                        created_at: new Date().toString(),
                                        id: response.data.comment_id,
                                        description: that.nl2br(value),
                                        media_id: null,
                                        parent_id: null,
                                        post_id: that.postId,
                                        user: response.data.user_info
                                    }
                                }
                        )
                        //that.userCommented++
                        setTimeout(function () {
                            that.updateZippy()
                        }, 300)
                    }
                }).catch(function (error) {
                    console.log(error)
                })

            },
            fetchComment: function () {
                let _token = $("meta[name=_token]").attr('content')
                let that = this
                let paginate = 3
                if(this.postCommentsCount == 0) {
                    that.$store.commit('SET_POST_COMMENT_INTERACT',  {postIndex: that.postIndex, commentInteract: true})
                    return
                }
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'get-comments',
                    data: {
                        post_id: that.postId,
                        paginate: paginate,
                        offset: that.offset,
                        _token: _token
                    }
                }).then(function (response) {
                    that.$store.commit('SET_POST_COMMENT_INTERACT',  {postIndex: that.postIndex, commentInteract: true})
                    if (response.status == 200) {
                        console.log(response.data[0])
                        let comments = response.data[0].comments
                        let hasMore = response.data[0].hasMore
                        let offset = 0
                        let commentItemList = []
                        if(that.postItemList[that.postIndex].postComments !== undefined) {
                            if(that.postItemList[that.postIndex].commentHasMore) {
                                offset = that.postItemList[that.postIndex].offset
                            } else {
                                return
                            }
                        }
                        for(let i = 0; i < comments.length;  i++) {
                            comments[i]['isLiked'] = false

                            if(comments[i].commentLikes !== undefined) {
                                for(let j = 0; j < comments[i].commentLikes.length; j++) {
                                    if(comments[i].commentLikes[j].user_id == user_id) {
                                        comments[i]['isLiked'] = true
                                        break;
                                    }
                                }
                                comments[i]['likesCount'] = comments[i].commentLikes.length
                            }
                            commentItemList.unshift(comments[i])
                        }
                        offset += comments.length
                        if(that.postItemList[that.postIndex].postComments !== undefined) {
                            // data already in the store, add new data
                            that.$store.commit('ADD_POST_COMMENT',  {hasMore: hasMore, offset: offset, postIndex: that.postIndex, postComments: commentItemList})
                        } else {
                            // data not initialized, so data need to init
                            that.$store.commit('SET_POST_COMMENT',  {hasMore: hasMore, offset: offset, postIndex: that.postIndex, postComments: commentItemList})
                        }
                        setTimeout(function () {
                            that.updateZippy()
                        }, 500)
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            nl2br: function (str, is_xhtml) {
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
            },
            openCommentDialog: function () {
                $('#comment-option-dialog').addClass('ft-dialog--open')
            },
            likeUnlikeComment: function (e, index) {
                let _token = $("meta[name=_token]").attr('content')
                let commentId = $(e.target).data('comment-id')
                let that = this
                let _Vue = Vue
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: SP_source() + 'ajax/comment-like',
                    data: {
                        comment_id: commentId,
                        _token: _token
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        console.log(response.data)
                        if (response.data.liked == true) {
                            Vue.set(that.commentItemList[index], 'isLiked', true)
                        } else {
                            Vue.set(that.commentItemList[index], 'isLiked', false)
                        }
                        that.$store.dispatch('sendNotification', {meow: 'meow'})
                    }
                }).catch(function (error) {
                    console.log(error)
                })
                Vue.set(that.commentItemList[index], 'isLiked', !that.commentItemList[index].isLiked)
            },
            loadMore: function () {
                this.fetchComment()
            }
        },
        mounted () {
            let that = this
            setTimeout(function () {
                that.getDefaultData()
            }, 1000)
            $('#' + this.expandID).Zippy();
            if(this.showSidebar) {
                if($(window).width() > 599)  {
                    if(this.postCommentsCount)
                    this.commentOnPost()
                }
            }
        },
        components: {},
        computed: {
            ...mapGetters({
                    postItemList: 'postItemList'
                }),
            userAvatar () {
                return 'hello'
            },
            expandID () {
                return Math.floor((Math.random() * 10) + 1)+'comment-expand-' + this.postId
            },
            reverseCommentItemList: function() {
                return this.commentItemList.slice().reverse();
            },
            postCommentsCount: function() {
                return this.postItemList[this.postIndex].postMetaInfo !== undefined ? this.postItemList[this.postIndex].postMetaInfo.postCommentsCount : 0
            },
            postLikesCount: function() {
                return this.postItemList[this.postIndex].postMetaInfo !== undefined ? this.postItemList[this.postIndex].postMetaInfo.postLikesCount : 0
            },
            userLiked: function (){
                return this.postItemList[this.postIndex].postMetaInfo !== undefined ? this.postItemList[this.postIndex].postMetaInfo.userLiked : 0
            },
            userCommented: function (){
                return this.postItemList[this.postIndex].postMetaInfo !== undefined ? this.postItemList[this.postIndex].postMetaInfo.userCommented : 0
            },
            commentHasMore: function () {
                return this.postItemList[this.postIndex].commentHasMore !== undefined ? this.postItemList[this.postIndex].commentHasMore : 0
            },
            commentItemList: function () {
                return this.postItemList[this.postIndex].postComments !== undefined ? this.postItemList[this.postIndex].postComments : []
            },
            commentInteract: function () {
                return this.postItemList[this.postIndex].commentInteract !== undefined ? this.postItemList[this.postIndex].commentInteract : false
            }
        }
    }
</script>