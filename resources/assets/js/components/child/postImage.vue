<template>
    <div class="text-wrapper" v-if="hasItem"></div>
</template>
<script>
    export default {
        props: {
            postImages: {
                type: Array,
                default: function () {
                    return []
                }
            },
        },
        data: function () {
            return {}
        },
        computed: {
            hasItem () {
                return this.postImages.length !== 0
            },
            hasMultipleImages () {
                return this.postImages.length > 1
            }
        }
    }
</script>