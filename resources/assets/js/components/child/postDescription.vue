<template>
    <div class="text-wrapper" v-if="hasItem">
        <div v-html="postHtml"></div>
    </div>
</template>
<script>
    export default {
        props: {
            postHtml: ''
        },
        data: function () {
            return {}
        },
        computed: {
            hasItem () {
                return this.postHtml !== ''
            }
        },

    }
</script>