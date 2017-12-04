<template>
    <div v-if="hasItem">
    </div>
</template>
<script>
    export default {
        props: {
            postEvent: ''
        },
        data: function () {
            return {
            }
        },
        methods: {
        },
        computed: {
            hasItem () {
                return this.postEvent !== undefined
            }
        },
        mounted() {

        }
    }
</script>