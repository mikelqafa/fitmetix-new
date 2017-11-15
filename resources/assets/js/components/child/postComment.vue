<template>
    <div class="panel-footer ft-socialite" >
        <div class="ft-comment md-layout md-layout--row">
            <div class="ft-comment__item md-layout md-layout--row">
                <a href="#" class="ft-expression" v-bind:class="{ 'ft-expression--liked': userLiked }">
                    <i class="icon icon-like visible-default"></i>
                    <i class="icon icon-liked hidden-default"></i>
                </a>
                <a href="#" class="ft-expression">
                    <i class="icon icon-comment"></i>
                </a>
            </div>
            <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow">
                <a href="#" class="ft-expression ft-expression--meta" v-bind:class="{ 'ft-expression--liked': userLiked }">
                    <i class="icon icon-like visible-default"></i>
                    <i class="icon icon-liked hidden-default"></i>
                    <span class="ft-expression--meta-text">
                        {{postCommentsCount}}
                    </span>
                </a>
                <a href="#" class="ft-expression ft-expression--meta">
                    <i class="icon icon-comment"></i>
                    <span class="ft-expression--meta-text">
                        {{postLikesCount}}
                    </span>
                </a>
            </div>
            <div class="ft-comment__item">
                <a href="" class="ft-expression">
                    <i class="icon icon-share"></i>
                </a>
            </div>
        </div>
    </div>
</template>
<style>
    .ft-socialite {
        background-color: #fff;
        padding: 10px 15px;
        border: none;
    }
    .ft-expression{
        display: flex;
        height: 32px;
        width: 48px;
        align-items: center;
        text-align: center;
        justify-content: center;
        align-items: center;
        color: #333;
    }
    .ft-expression .hidden-default {
        display: none;
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
    .ft-comment__item--grow{
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
    export default {
        props: {
            postId: ''
        },
        data: function () {
            return {
                postCommentsCount: 0,
                postLikesCount: 0,
                userLiked: 0
            }
        },
        computed: {
            userAvatar () {
                return 'hello'
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
                }).then( function (response) {
                    if (response.status ==  200) {
                        that.postCommentsCount = response.data[0].post_comment_count;
                        that.postLikesCount = response.data[0].post_likes_count;
                        that.userLiked =  response.data[0].user_liked
                    }
                    console.log(response)
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
        }
    }
</script>