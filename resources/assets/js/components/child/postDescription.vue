<template>
    <div class="text-wrapper_desc">
        <div class="text-wrapper" v-if="hasItem" v-html="postHtmlViewAble"></div>
        <template v-if="isMoreViewable">
            <span>...</span>
        </template>
        <a href="javascript:;" v-show="isMoreViewable" v-on:click="viewMore" class="viewAll md-button--link">
            {{$t('post.v_more') }}
        </a>
    </div>
</template>
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
                window.setTimeout(function(){
                    window.emojify.run()
                    window.mentionify()
                    window.hashtagify()
                }, 300)
            }
        },
        computed: {
            hasItem () {
                return this.postHtml !== '' && this.postHtml !== null
            },
            isMoreViewable () {
                return this.postHtmlViewAble.length < this.postHtml.length
            }
        },
        mounted() {
            this.postHtmlViewAble =  (this.postHtml.length < 160 && this.postHtml.length > this.textLimit) ? this.postHtml : (this.postHtml.length > this.textLimit) ? this.postHtml.substr(0, this.textLimit) : this.postHtml
            window.setTimeout(function(){
                window.emojify.run()
                window.mentionify()
                window.hashtagify()
            }, 300)
        },
        watch: {
            postHtml: function (val) {
                this.postHtmlViewAble = this.postHtml
                setTimeout(function () {
                    window.emojify.run()
                }, 100)
            }
        }
    }
</script>