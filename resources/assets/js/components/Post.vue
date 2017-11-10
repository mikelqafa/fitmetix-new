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
            <div v-for="postItem in itemList" class="panel panel-default timeline-posts__item panel-post animated" :id="postItem._id">
                <div class="panel-heading no-bg">
                    <div class="post-author">
                        <div class="post-options">
                            <a href="#" class="dropdown-togle">
                                <i class="icon icon-options"></i>
                            </a>
                        </div>
                        <div class="user-avatar">
                            <a :href="postItem.userLink">
                                <img :src="postItem.userImage" alt="postItem.userName" :title="postItem.userName">
                            </a>
                        </div>
                        <div class="user-post-details">
                            <ul class="list-unstyled no-margin">
                                <li>
                                    <a href="//localhost:3004/fitmetix/public/mikele" title="" data-toggle="tooltip" data-placement="top" class="user-name user ft-user-name" data-original-title="@mikele">
                                        mikele
                                    </a>
                                    <div class="small-text">
                                    </div>
                                </li>
                                <li>
                                    <!--<timeago :since="postItem.time"></timeago>-->
                                    <timeago :since="since"
                                            :auto-update="autoUpdate"
                                            class="timeago"></timeago>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="text-wrapper">
                        <div v-html="postItem.postHtml"></div>
                        <div class="post-v-holder"></div>
                    </div>
                </div>
                <div class="panel-footer socialite">
                    <ul class="list-inline footer-list pos-rel">
                        <li class="hidden">
                            <a href="#" class="like-post like-87" data-post-id="87">
                                <i class="icon icon-like"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="like-post unlike unlike-87" data-post-id="87">
                                <i class="icon icon-liked unlike"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="show-comments">
                                <i class="icon icon-comment"></i>
                            </a>
                        </li>
                        <li class="text-center full-center ">
                            <a href="#" class="show-users-modal" data-html="true" data-heading="Likes" data-users="7" data-original-title="Mikel">
                        <span class="count-circle">
                            <i class="icon icon-like"></i>
                        </span>
                                <span class="hidden-sm hidden-xs">Likes</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="show-all-comments">
                        <span class="count-circle">
                            <i class="icon icon-comment"></i>
                        </span>1
                                <span class="hidden-sm hidden-xs">comments</span>
                            </a>
                        </li>
                        <li class="pull-right">
                            <a href="//localhost:3004/fitmetix/public/post/87">
                                <i class="icon icon-share"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="comments-section all_comments" style="display:none">
                    <div class="comments-wrapper">
                        <div class="to-comment">  <!-- to-comment -->
                            <div class="commenter-avatar">
                                <a href="#"><img src="//localhost:3004/fitmetix/public/user/avatar/2017-10-22-14-07-04athletebookprofilepage.png" alt="Mikel" title="Mikel"></a>
                            </div>
                            <div class="comment-textfield">
                                <form action="#" class="comment-form" method="post" files="true" enctype="multipart/form-data" id="comment-form">
                                    <div class="comment-holder">
                                        <input class="form-control post-comment" autocomplete="off" data-post-id="87" name="post_comment" placeholder="Write a comment...Press Enter to Post">

                                        <input type="file" class="comment-images-upload hidden" accept="image/jpeg,image/png,image/gif" name="comment_images_upload">
                                        <ul class="list-inline meme-reply hidden">
                                            <li><a href="#" id="imageComment"><i class="icon icon-photo" aria-hidden="true"></i></a></li>
                                        </ul>
                                    </div>
                                    <div id="comment-image-holder"></div>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- to-comment -->

                        <div class="comments post-comments-list"> <!-- comments/main-comment  -->
                            <ul class="list-unstyled main-comment comment54 " id="comment54">
                                <li>
                                    <div class="comments delete_comment_list"> <!-- main-comment -->
                                        <div class="commenter-avatar">
                                            <a href="#"><img src="//localhost:3004/fitmetix/public/user/avatar/2017-10-22-14-07-04athletebookprofilepage.png" title="Mikel" alt="Mikel"></a>
                                        </div>
                                        <div class="comments-list">
                                            <div class="commenter">
                                                <a href="#" class="delete-comment delete_comment" data-commentdelete-id="54"><i class="fa fa-times"></i></a>
                                                <div class="commenter-name">
                                                    <a href="//localhost:3004/fitmetix/public/mikele">Mikel</a>
                                                    <span class="comment-description">dd</span>
                                                </div>
                                                <ul class="list-inline comment-options">
                                                    <li><a href="#" class="text-capitalize like-comment like" data-comment-id="54">like</a></li>
                                                    <li class="hidden"><a href="#" class="text-capitalize like-comment unlike" data-comment-id="54">unlike</a></li>
                                                    <li>.</li>
                                                    <li><a href="#" class="show-comment-reply">reply</a></li>
                                                    <li>.</li>
                                                    <li><a href="#" class="show-likes like3-54"><i class="fa fa-thumbs-up"></i>0</a></li>
                                                    <li class="show-likes like4-54 hidden"></li>
                                                    <li>.</li>
                                                    <li>
                                                        <time class="post-time timeago" datetime="2017-11-08 21:49:47+00:00" title="2017-11-08 21:49:47+00:00">about 12 hours ago</time>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li><li>
                                <div class="to-comment comment-reply" style="display:none">  <!-- to-comment -->
                                    <div class="commenter-avatar">
                                        <img src="//localhost:3004/fitmetix/public/user/avatar/2017-10-22-14-07-04athletebookprofilepage.png" alt="Mikel" title="Mikel">
                                    </div>
                                    <div class="comment-textfield">
                                        <form action="#" class="comment-form">
                                            <input class="form-control post-comment" autocomplete="off" data-post-id="87" data-comment-id="54" name="post_comment" placeholder="Write a comment...Press Enter to Post" rows="1">
                                        </form>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </li>
                            </ul>
                        </div>
                    </div>
                </div>
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
    export default {
        data: function () {
            return {
                itemList: [],
                since: 1510235516210,
                autoUpdate: 60
            }
        },
        computed: {
            isLoading () {
                return this.itemList.length === 0
            }
        },
        methods: {
            getDefaultData: function () {
                let that = this
                window.axios.get(base_url + 'ajax/dummy').then(function (response) {
                    console.log(response.data)
                    let data  =  {
                        "_id": "12",
                        "userLink": "//localhost:3004/fitmetix/public/mikele",
                        "userImage": "//localhost:3000/fitmetix/public/user/avatar/default-male-avatar.png",
                        "postTime": "2017-11-08 21:47:49+00:00",
                        "postHtml": "<p>If instead of discarding all local commits, you can make your branch identical to some other branch, tag, ref, or SHA that exists on your system.</p>",
                        "tags": [
                            "qui",
                            "voluptate",
                            "pariatur",
                            "ullamco",
                            "dolore",
                            "duis",
                            "sint"
                        ],
                        "friends": [
                            {
                                "id": 0,
                                "name": "Whitney Palmer"
                            },
                            {
                                "id": 1,
                                "name": "Workman Dean"
                            },
                            {
                                "id": 2,
                                "name": "Jenifer Armstrong"
                            }
                        ]
                    }
                    that.itemList.push(data)
                    // this.conversations = response.data.data;
                    // this.showConversation(this.conversations.data[0]);
                });
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