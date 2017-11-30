<template>
    <div class="soundcloud-wrapper" v-if="hasItem">
        <iframe width="100%" height="166" scrolling="no" frameborder="no" :src="soundcloudSrc"></iframe>
    </div>
</template>
<script>
    export default {
        props: {
            soundcloud: ''
        },
        data: function () {
            return {}
        },
        computed: {
            hasItem () {
                return this.soundcloud !== '' && this.soundcloud !== null
            },
            soundcloudSrc () {
                return 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/' + this.soundcloud
            }
        }
    }
</script>