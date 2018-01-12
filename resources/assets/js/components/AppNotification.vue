<template>
    <div>
        <app-notification></app-notification>
        <app-chat></app-chat>
        <app-direct-msg></app-direct-msg>
    </div>
</template>
<script>
    import appNotification from './child/appNotification'
    //import appMessage from './child/appMessage'
    import appChat from './child/appChat'
    import directMsg from './child/sendDirect'
    import { mapGetters } from 'vuex'

    export default {
        data: function () {
            return {

            }
        },
        mounted () {
            this.init()
        },
        methods: {
            init: function () {
                let pusher = new Pusher(pusherConfig.PUSHER_KEY, {
                    encrypted: true,
                    auth: {
                        headers: {
                            'X-CSRF-Token': pusherConfig.token
                        },
                        params: {
                            username: current_username
                        }
                    }
                });
                this.$store.commit('SET_PUSHER', pusher)
            }
        },
        components: {
            'app-notification': appNotification,
            'app-chat': appChat,
            'app-direct-msg': directMsg
        }
    }
</script>