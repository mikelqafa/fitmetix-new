<template>
    <div class="ft-cp">
        <div class="ft-cp__user"></div>
        <div class="ft-cp__presentation"></div>
    </div>
</template>
<style>
    .ft-cp {
        display: flex;
        flex-direction: row;
    }
    .ft-cp__user {
        height: 40px;
        width: 40px;
        flex-shrink: 0;
    }
    .ft-cp__presentation {
        flex-grow: 1;
        min-height: 188px;
    }
</style>
<script>
    export default {
        data: function () {
            return {

            }
        },
        mounted () {

        },
        methods: {}
    }
</script>