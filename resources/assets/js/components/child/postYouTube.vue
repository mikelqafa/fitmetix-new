<template>
    <div class="text-wrapper" v-if="hasItem">
        <iframe  :src="youTubeSrc" frameborder="0" allowfullscreen></iframe>
    </div>
</template>
<script>
    export default {
        props: {
            postYouTube: '',
            youTubeTitle:''
        },
        data: function () {
            return {}
        },
        computed: {
            hasItem () {
                return this.postYouTube !== '' && this.postYouTube !== null
            },
            youTubeSrc () {
                return 'https://www.youtube.com/embed/' + this.postYouTube
            }
        }
    }
</script>