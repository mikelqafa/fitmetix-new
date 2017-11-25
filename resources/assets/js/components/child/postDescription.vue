<template>
    <div class="text-wrapper_desc">
        <div class="text-wrapper" v-if="hasItem" v-html="postHtmlViewAble"></div>
        <template v-if="isMoreViewable">
            <span>...</span>
        </template>
        <a href="javascript:;" v-show="isMoreViewable" v-on:click="viewMore" class="viewAll md-button--link">view more</a>
    </div>
</template>
<style>
    .viewAll {
        margin-left: 4px;
        color: #1E7C82;
    }
    .md-button--link {
        margin-bottom: 4px;
    }
    .text-wrapper_desc {
        margin-bottom: 8px;
    }
</style>
<script>
    export default {
        props: {
            postHtml: ''
        },
        data: function () {
            return {
                textLimit: 151,
                postHtmlViewAble: ''
            }
        },
        methods: {
            viewMore: function () {
                this.postHtmlViewAble = this.postHtml
                window.emojify.run()
            }
        },
        computed: {
            hasItem () {
                return this.postHtml !== ''
            },
            isMoreViewable () {
                return this.postHtmlViewAble.length < this.postHtml.length
            }
        },
        mounted() {
            this.postHtmlViewAble =  (this.postHtml.length < 160 && this.postHtml.length > this.textLimit) ? this.postHtml : (this.postHtml.length > this.textLimit) ? this.postHtml.substr(0, this.textLimit) : this.postHtml
            window.setTimeout(function(){
                window.emojify.run()
            }, 300)
        }
    }
</script>