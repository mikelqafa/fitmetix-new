<template>
    <div class="panel-heading no-bg">
        <div class="post-author">
            <div class="post-options">
                <a href="javascript:;" class="dropdown-togle">
                    <i class="icon icon-options"></i>
                </a>
            </div>
            <a :href="userLink" class="user-avatar"  :title="userAtTitle" v-bind:style="{ backgroundColor: userAvatar}">
                <img :src="userAvatar" class="hidden"  :alt="userAvatar.name" :title="timelineData.name">
            </a>
            <div class="user-post-details">
                <ul class="list-unstyled no-margin">
                    <li>
                        <a :href="userLink" data-toggle="tooltip" data-placement="top" class="user-name user ft-user-name" :title="userAtTitle" :data-original-title="userAtTitle">
                            {{ timelineData.name }}
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
                autoUpdate: 60,
                defaultImage: 'default.png'
            }
        },
        computed: {
            since () {
                return this.date != '' ? new Date(this.date).getTime() : ''
            },
            userLink () {
                return base_url + this.timelineData.username
            },
            userAtTitle() {
                return '@' + this.timelineData.username
            },
            userAvatar () {
                return this.timelineData.avatar_url.length !== 0 ? base_url + this.timelineData.avatar_url[0].source : base_url + this.defaultImage
            }
        },
        methods: {
            openPostDialog: function () {

            }
        }
    }
</script>