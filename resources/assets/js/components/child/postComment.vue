<template>
    <div>
        <div class="panel-footer ft-socialite">
            <div class="ft-comment md-layout md-layout--row">
                <div class="ft-comment__item md-layout md-layout--row">
                    <a href="javascript:;" class="ft-expression" v-bind:class="{ 'ft-expression--liked': userLiked }"
                       @click="toggleLikePost">
                        <i class="icon icon-like visible-default"></i>
                        <i class="icon icon-liked hidden-default"></i>
                    </a>
                    <a href="javascript:;" class="ft-expression" v-bind:class="{ 'ft-expression--liked': userCommented }"
                       @click="commentOnPost">
                        <i class="icon icon-comment"></i>
                    </a>
                </div>
                <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow">
                    <a href="javascript:;" class="ft-expression ft-expression--meta"
                       v-bind:class="{ 'ft-expression--liked': userLiked }">
                        <i class="icon icon-like visible-default"></i>
                        <i class="icon icon-liked hidden-default"></i>
                    <span class="ft-expression--meta-text">
                        {{postLikesCount}}
                    </span>
                    </a>
                    <a href="javascript:;" class="ft-expression ft-expression--meta"
                       v-bind:class="{ 'ft-expression--liked': userCommented }">
                        <i class="icon icon-comment"></i>
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
        <section class="zippy suggestion-list-expand" :id="expandID">
            <div class="zippy__wrapper">
                <template v-if="commentInteract">
                    <div class="comment-textfield">
                        <form action="#">
                            <textarea v-on:keyup.enter.prevent="postComment" class="form-control"  autocomplete="off" data-post-id="" data-comment-id="" name="post_comment" placeholder="Write a comment" rows="1"></textarea>
                        </form>
                        <div class="loading-wrapper">

                        </div>
                    </div>
                    <div class="comment-list-action md-list md-list--dense" v-if="commentItemList.length">
                        <div class="md-list__item has-divider" v-for="item in reverseCommentItemList">
                            <a style="background-image: url('http://localhost:3008/fitmetix/public/images/default.png')" href="//localhost:3008/fitmetix/public/Uppal" title="@Uppal" class="md-list__item-icon user-avatar"></a>
                            <div class="md-list__item-content">
                                <div class="md-list__item-primary">
                                    <a href="//localhost:3008/fitmetix/public/Uppal" title="@Uppal" data-original-title="@Uppal" class="user-name user ft-user-name">
                                        Sidhant
                                    </a>
                                    <div class="md-list__item-text-body">
                                        {{item.description}}
                                    </div>
                                </div>
                                <div class="md-list__item-secondary md-layout md-layout--row">
                                    <a class="md-list__item-secondary-action" href="#">
                                        <i class="icon icon-options"></i>
                                    </a>
                                    <a href="javascript:;" class="md-list__item-secondary-action ft-expression" v-bind:class="{ 'ft-expression--liked': userLiked }"
                                       @click="toggleLikePost">
                                        <i class="icon icon-like visible-default"></i>
                                        <i class="icon icon-liked hidden-default"></i>
                                    </a>
                                </div>
                            </div>
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
    </div>
</template>
<style>
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
    .comment-list-action{
        max-height: 320px;
        overflow-y: auto;
    }
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
        min-height: 40px;
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
        padding-top:10px;
        padding-bottom:10px;
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
        padding: 10px 15px;
        border: none;
    }

    .ft-expression {
        display: flex;
        height: 32px;
        width: 48px;
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
        font-size: 24px;
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
        font-size: 13px;
        height: 24px;
        min-width: 24px;
        width: auto;
        padding: 0 7px;
        line-height: 24px;
    }

    .ft-expression--meta i {
        font-size: 14px;
    }

    .ft-expression--meta-text {
        margin-left: 5px;
    }
</style>
<script>
    // let Zippy = window.Zippy
    export default {
        props: {
            postId: ''
        },
        data: function () {
            return {
                postCommentsCount: 0,
                postLikesCount: 0,
                userLiked: 0,
                userCommented: 0,
                commentInteract: false,
                commentHasMore: true,
                commentItemList: [],
                commentIsPosting: false
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
            }
        },
        methods: {
            getDefaultData: function () {
                let that = this
                let paginate = 50
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
                        that.postCommentsCount = response.data[0].post_comment_count;
                        that.postLikesCount = response.data[0].post_likes_count;
                        that.userLiked = response.data[0].user_liked
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            toggleLikePost: function () {
                let that = this
                let paginate = 50
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
                    if (response.status == 200) {
                        that.userLiked = response.data.liked
                    }
                    console.log(response)
                }).catch(function (error) {
                    console.log(error)
                })
                this.userLiked = !this.userLiked
                if (this.userLiked) {
                    this.postLikesCount++
                } else {
                    this.postLikesCount--
                }

            },
            commentOnPost: function () {
                $('#' + this.expandID).Zippy('toggle')
                if(!this.commentInteract) {
                    let that = this
                    setTimeout(function () {
                        that.fetchComment()
                    }, 1000)
                }
            },
            updateZippy: function () {
                $('#' + this.expandID).Zippy('update')
            },
            postComment: function (e) {
                e.preventDefault()
                let input = e.target
                let value = e.target.value
                console.log(input, value)
                $(e.target).parent().addClass('is-loading')
                let loadingWrapper = $(e.target).parent().find('.loading-wrapper')
                if(value == '') {
                    return
                }
                //this.commentIsPosting = true
                // appending html for loading
                loadingWrapper.html(
                        '<div class="ft-loading">'+
                        '<span class="ft-loading__dot"></span>'+
                        '<span class="ft-loading__dot"></span>'+
                        '<span class="ft-loading__dot"></span>'+
                        '</div>')
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                return
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + '/ajax/post-comment',
                    data: {
                        post_id: that.postId,
                        description: value,
                        _token: _token
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        input.value = ''
                        that.postCommentsCount++
                        $(input).removeClass('is-loading')
                        that.commentIsPosting = false
                        that.commentInteract = true
                        /*that.commentItemList.push({
                            comment: value,
                            timestamp: new Date().getTime(),
                            userId: '',
                            userAvatar: ''
                        })*/
                    }
                }).catch(function (error) {
                    console.log(error)
                })

            },
            fetchComment: function () {
                let _token = $("meta[name=_token]").attr('content')
                let that = this
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'get-comments',
                    data: {
                        post_id: that.postId,
                        offset: 0,
                        _token: _token
                    }
                }).then(function (response) {
                    console.log(response)
                    if (response.status == 200) {
                        let comments = response.data[0].comments
                       console.log(comments.length)
                        for(let i = 0; i < comments.length;  i++) {
                            that.commentItemList.push(comments[i])
                        }
                        that.commentInteract = true

                        setTimeout(function () {
                            that.updateZippy()
                        }, 1000)
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            }
        },
        mounted () {
            let that = this
            setTimeout(function () {
                that.getDefaultData()
            }, 1000)
            $('#' + this.expandID).Zippy();
        },
        components: {}
    }
</script>