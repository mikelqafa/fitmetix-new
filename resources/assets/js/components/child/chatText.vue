<template>
    <div class="chat-item" :data-user="whichUser(message.user.username)">
        <div class="chat-item__user">
            <a aria-label="Prem Bharti Wednesday 7:08pm" class="_4tdw"
               data-hover="tooltip"
               data-tooltip-content="Prem Bharti Wednesday 7:08pm"
               data-tooltip-position="left"
               href="">
                <img :src="message.user.avatar"
                     alt="" class="img">
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
            }
        },
        mounted () {
            emojify.run()
        }
    }
</script>
