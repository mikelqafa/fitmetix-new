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
                    <a v-show="postLikesCount" href="javascript:;" class="ft-expression ft-expression--meta">
                        <span class="icon icon-liked visible-default"></span>
                    <span class="ft-expression--meta-text">
                        {{postLikesCount}}
                    </span>
                    </a>
                    <a v-show="postCommentsCount" href="javascript:;" class="ft-expression  ft-expression--meta">
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
                                        <a :href="base_url+item.user.username" title="@Uppal" data-original-title="@Uppal" class="user-name user ft-user-name">
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
    .icon {
        pointer-events: none;
    }
    .text-center.ft-menu__item {
        text-align: center !important;
    }
    .zippy {
        height: 0;
    }
    .zippy.zippy--open,
    .zippy.zippy--animating {
        height: auto;
    }
    .comment-textfield{
        position: relative;
    }
    .comment-textfield .loading-wrapper {
        position: absolute;
        top:0;
        left:0;
        height: 100%;
        width: 100%;
        display: none;
    }
    .comment-textfield.is-loading {
        pointer-events: none;
        cursor: wait;
    }
    .comment-textfield .ft-loading {
        background-color: rgba(0,0,0,.12);
    }
    .comment-textfield.is-loading .loading-wrapper {
        display: block;
    }
    /*.comment-list-action{
        max-height: 320px;
        overflow-y: auto;
    }*/
    .ft-loading{
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        flex-wrap: nowrap;
        height: 48px;
        background-color: #FAFBFC;
    }
    .ft-loading__dot{
        background: #333;
        display: block;
        height: 8px;
        float: left;
        margin: 0 2px;
        opacity: 0;
        width: 8px;
        border-radius: 50%;
        animation: dot 1s cubic-bezier(0.77, 0, 0.175, 1) 0s infinite;
    }
    .ft-loading .ft-loading__dot:nth-child(1) {
        animation-delay: 0.15s;
    }
    .ft-loading .ft-loading__dot:nth-child(2) {
        animation-delay: 0.3s;
    }
    .ft-loading .ft-loading__dot:nth-child(3) {
        animation-delay: 0.45s;
    }
    @keyframes dot {
        0% {
            opacity: 0;
        }
        25% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
    .comment-textfield .form-control{
        height: 48px;
        border-top: none;
        border-left:none;
        border-right:none;
        border-radius: 0;
        padding-left: 15px;
        padding-right: 15px;
        padding-top: 15px;
        background-color: #FAFBFC;
    }
    .md-list__item-icon {
        margin-top: 2px;
        height: 32px;
        width: 32px;
        align-self: flex-start;
        margin-right: 16px;
        flex-shrink: 0;
    }
    .user-avatar {
        background-size: cover;
    }
    .md-list {
        background-color: #FAFBFC;
        padding: 4px 0;
        width: 100%;
        display: block;
        list-style: none;
    }
    .md-list .md-list__item {
        border-bottom: 1px solid rgba(0,0,0,.12);
    }

    .md-list .md-list__item:last-child {
        border-bottom: none
    }
    .md-list--dense .md-list__item {
        min-height: 32px;
        font-size: 14px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        font-weight: 400;
        color: rgba(0,0,0,.87);
        letter-spacing: .04em;
        flex-direction: row;
        flex-wrap: nowrap;
        align-items: center;
        cursor: default;
        overflow: hidden;
        line-height: 1;
        padding-left: 16px;
        padding-top:5px;
        padding-bottom:5px;
    }
    .md-list__item-content {
        flex: 1 1 100%;
        display: flex;
        position: relative;
        flex-direction: row;
        flex-wrap: nowrap;
        line-height: 20px;
        justify-content: space-between;
        padding-right: 16px;
    }
    .md-list__item-text-body{
        line-height: 18px;
        color: rgba(0,0,0,.54);
        padding: 0;
        font-size: 12px;
    }
    .md-list__item-secondary{
        display: flex;
        margin-left: 8px;
    }

    .md-list__item-secondary-action {
        height: 20px;
        width: 20px;
        padding: 2px;
        text-align: center;
        font-size: 11px;
        color: #333;
    }
    .md-list__item-secondary-action.ft-expression{
        height: 20px;
        width: 20px;
    }
    .md-list__item-secondary-action .icon{
        font-size: 1.4rem;
    }
    .ft-socialite {
        background-color: #fff;
        padding: 7.5px 15px;
        padding-bottom: 2px;
        border: none;
    }

    .ft-expression {
        display: flex;
        height: 32px;
        width: 36px;
        text-align: center;
        justify-content: center;
        align-items: center;
        color: #333;
    }

    .ft-expression .hidden-default {
        display: none;
    }

    .ft-expression--liked,
    .ft-expression--liked:focus,
    .ft-expression--liked:hover {
        color: #EB5757;
    }

    .ft-expression--liked .hidden-default {
        display: block;
    }

    .ft-expression--liked .visible-default {
        display: none;
    }

    .ft-expression i {
        font-size: 22px;
    }

    .ft-comment {
        flex-wrap: wrap;
        align-items: center;
    }

    .ft-comment__item {
        display: flex;
    }

    .ft-comment__item--grow {
        flex-grow: 1;
    }

    .ft-expression--meta {
        font-size: 11px;
        height: 24px;
        min-width: 24px;
        width: auto;
        padding: 0 4px;
        line-height: 24px;
    }

    .ft-expression--meta .icon {
        font-size: 12px;
        margin-top: 4px;
    }

    .ft-expression--meta-text {
        margin-left: 2px;
    }

    @media screen and (max-width: 599px) {
        .main-content > .container > .row > .col-lg-6 {
            padding-left: 0;
            padding-right: 0;
        }
    }
</style>
<script>
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
                commentInteract: false,
                commentHasMore: false,
                commentItemList: [],
                commentIsPosting: false,
                offset: 0,
                showUserComment: 0
            }
        },
        computed: {
            userAvatar () {
                return 'hello'
            },
            expandID () {
                return 'comment-expand-' + this.postId
            },
            reverseCommentItemList: function() {
                return this.commentItemList.slice().reverse();
            },
            postCommentsCount: function() {
                return this.$store.state.postItemList[this.postIndex].postMetaInfo !== undefined ? this.$store.state.postItemList[this.postIndex].postMetaInfo.postCommentsCount : 0
            },
            postLikesCount: function() {
                return this.$store.state.postItemList[this.postIndex].postMetaInfo !== undefined ? this.$store.state.postItemList[this.postIndex].postMetaInfo.postLikesCount : 0
            },
            userLiked: function (){
                return this.$store.state.postItemList[this.postIndex].postMetaInfo !== undefined ? this.$store.state.postItemList[this.postIndex].postMetaInfo.userLiked : 0
            },
            userCommented: function (){
                return this.$store.state.postItemList[this.postIndex].postMetaInfo !== undefined ? this.$store.state.postItemList[this.postIndex].postMetaInfo.userCommented : 0
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
                    console.log(response.data[0])
                    if (response.status == 200) {
                        that.$store.commit('SET_POST_META', {
                            index: that.postIndex,
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
                    if (response.status == 200) {
                        /*that.$store.commit('SET_POST_META_USER_LIKED', {
                            index: that.postIndex,
                            userLiked: response.data.liked
                        })*/
                    }
                }).catch(function (error) {
                    console.log(error)
                })
                console.log(this.userLiked)
                let like = !this.userLiked
                console.log(like)
                this.$store.commit('SET_POST_META_USER_LIKED', {
                    index: that.postIndex,
                    userLiked: like
                })
                this.$store.commit('SET_POST_META_LIKES_COUNT', {
                    index: this.postIndex,
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
                }
            },
            updateZippy: function () {
                $('#' + this.expandID).Zippy('update')
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
                        that.postCommentsCount++
                        $(e.target).parent().addClass('is-loading')
                        loadingWrapper.html('')
                        that.commentIsPosting = false
                        that.commentInteract = true
                        that.userCommented
                        that.commentItemList.unshift({
                            created_at: new Date().toString(),
                            id: response.data.comment_id,
                            description: that.nl2br(value),
                            media_id: null,
                            parent_id: null,
                            post_id: that.postId,
                            user: response.data.user_info
                         })
                        that.userCommented++
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
                that.commentInteract = true
                if(this.postCommentsCount == 0) {
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
                    console.log(response)
                    if (response.status == 200) {
                        let comments = response.data[0].comments
                        let hasMore = response.data[0].hasMore
                        for(let i = 0; i < comments.length;  i++) {
                            comments[i]['isLiked'] = false
                            that.commentItemList.unshift(comments[i])
                        }
                        setTimeout(function () {
                            that.updateZippy()
                        }, 500)
                        that.offset += comments.length
                        that.commentHasMore = hasMore
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
                console.log(e, index)
                let _token = $("meta[name=_token]").attr('content')
                let commentId = $(this).data('comment-id')
                let that = this

                axios({
                    method: 'post',
                    responseType: 'json',
                    url: SP_source() + 'ajax/comment-like',
                    data: {
                        comment_id: commentId,
                        _token: _token
                    }
                }).then(function (response) {
                    console.log(response)
                    if (response.status == 200) {
                        if (response.data.liked == true) {
                            that.commentItemList[index].isLiked = true
                        } else {
                            that.commentItemList[index].isLiked = false
                        }
                    }
                }).catch(function (error) {
                    console.log(error)
                })

                that.commentItemList[index].isLiked = !that.commentItemList[index].isLiked
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
                    this.commentOnPost()
                }
            }
        },
        components: {}
    }
</script>