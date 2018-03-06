<template>
    <div class="chat-item" :data-user="whichUser(message.user.username)">
        <div class="chat-item__user">
            <a class="_4tdw"  :href="userLink(message)" :title="message.user.name">
                <img :src="getThumbImage(message)" alt="" class="img">
            </a>
        </div>
        <div class="chat-item__bubble">
            <div class="chat-item__content">
                <div class="chat-item__content-wrapper">
                    <div class="chat-item__content-text text-wrapper" v-html="message.body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import editor from 'vue2-medium-editor'
    import { mapGetters } from 'vuex'
    export default {
        props: [
            'message'
        ],
        methods: {
            whichUser: function (u) {
                return u === current_username ? 0 : 1
            },
            userLink: function (item) {
                return base_url + item.user.username
            },
            getThumbImage: function (item) {
                return getThumbImage(item.user.avatar_url.length ? asset_url + 'uploads/users/avatars/' + item.user.avatar_url[0].source : base_url + 'images/' + 'default.png')
            }
        },
        mounted () {

        }
    }
</script>
