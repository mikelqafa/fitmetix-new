<template>

</template>
<script>
    export default {
        props: {
            postId: ''
        },
        data: function () {
            return {
                isInteract: false,
                hasMore: true,
                itemList: []
            }
        },
        computed: {
            userAvatar () {
                return 'hello'
            }
        },
        methods: {

        }
    }
</script>