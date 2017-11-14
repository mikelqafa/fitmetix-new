<template>
    <div class="panel-heading no-bg">
        <div class="post-author">
            <div class="post-options">
                <a href="#" class="dropdown-togle">
                    <i class="icon icon-options"></i>
                </a>
            </div>
            <div class="user-avatar">
                <a href="#">
                    <img src="" alt="postItem.userName" title="postItem.userName">
                </a>
            </div>
            <div class="user-post-details">
                <ul class="list-unstyled no-margin">
                    <li>
                        <a :href="userLink" title="" data-toggle="tooltip" data-placement="top" class="user-name user ft-user-name" data-original-title="@mikele">
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
</template>
<script>
    export default {
        props: {
            timelineData: {},
            date: ''
        },
        data: function () {
            return {
                autoUpdate: 60
            }
        },
        computed: {
            since () {
                return this.date != '' ? new Date(this.date).getTime() : ''
            },
            userLink () {
                return this.timelineData != {} ? window.base_url + this.timelineData.username : '#'
            }
        }
    }
</script>