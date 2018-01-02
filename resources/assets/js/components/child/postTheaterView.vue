<template>
    <div class="md-dialog md-dialog--dark md-dialog--theater md-dialog--full-screen" id="post-image-theater-dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface">
                <header class="md-dialog__header panel-post hidden md-dialog__header--xs visible-xs">
                    <template v-if="holaMola">
                        <post-header style="flex-grow: 1" :post-data="postItem" :post-index="theaterPostItem.postIndex" :date="postItem.created_at"></post-header>
                    </template>
                    <a href="javascript:;" style="margin-right: 15px" class="md-button md-button--icon md-dialog__header-action-dismissive" data-action="dismissive">
                        <i class="icon icon-close"></i>
                    </a>
                </header>
                <div class="md-dialog__body">
                    <div class="stage">
                        <template v-if="holaMola">
                            <image-theater-surface :post-event="postItem.event" :post-index="theaterPostItem.postIndex" :post-img="postItem.images"></image-theater-surface>
                        </template>
                    </div>
                    <div class="stage-photo-sidebar ft-theme--color">
                        <header class="panel-post hidden-xs">
                            <template v-if="holaMola">
                                <post-header style="flex-grow: 1" :post-data="postItem" :post-index="theaterPostItem.postIndex" :date="postItem.created_at"></post-header>
                                <post-description :post-html="postItem.description"></post-description>
                            </template>
                        </header>
                        <post-event v-if="holaMola" :post-item="postItem" :post-index="theaterPostItem.postIndex"></post-event>
                        <div class="hidden-xs" v-if="holaMola">
                            <post-comment v-on:focuscomment="focuscomment" :post-index="theaterPostItem.postIndex" show-sidebar="true" :post-item="postItem" :post-id="postItem.id"></post-comment>
                        </div>
                    </div>
                </div>
                <footer class="md-dialog__footer  hidden visible-xs">
                    <template v-if="holaMola">
                        <post-comment :post-index="theaterPostItem.postIndex" v-on:focuscomment="focuscomment" :show-theater="true" :post-item="postItem" :post-id="postItem.id"></post-comment>
                    </template>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
    import imageTheaterSurface from './imageTheaterSurface'
    import postHeader from './postHeader'
    import postComment from './postComment'
    import postEvent from './postEvent'
    import { mapGetters } from 'vuex'
    import postDescription from './postDescription'

    export default {
        data: function () {
            return {}
        },
        components: {
            'image-theater-surface': imageTheaterSurface,
            'post-header': postHeader,
            'post-comment': postComment,
            'post-description': postDescription,
            'post-event': postEvent
        },
        methods: {
            focuscomment: function () {
                $('#post-image-theater-dialog').MaterialDialog('hide')
                let commentBtn = $('#ft-post'+this.postItem.id + ' ' +'.ft-comment__item .ft-expression--comment')
                commentBtn[0].click()
                let commentBox = $('#ft-post'+this.postItem.id + ' ' + '.ft-post__comment-form').focus()
                commentBox !== undefined ? commentBox.focus(): ''
            }
        },
        mounted () {
            let that = this
            let dialog = $('#post-image-theater-dialog').MaterialDialog({show:false});
            dialog.on('ca.dialog.shown', function () {
                if($(window).width()<600) {
                    noBack()
                    window.dialogId = 'post-image-theater-dialog'
                }
                window.setTimeout(function(){
                    emojify.run()
                    hashtagify()
                }, 300)
            });
            dialog.on('ca.dialog.hidden', function () {
                if($(window).width()<600) {
                    resetBack()
                }
                that.$store.commit('SET_THEATER_ITEM', {postIndex: undefined, imageIndex: undefined})
            });
            console.log(this.theaterPostItem)
        },
        computed: {
            ...mapGetters({
                theaterPostItem: 'theaterPostItem'
            }),
            postItem: function () {
                return this.theaterPostItem.postIndex !== undefined ? this.$store.state.postItemList[this.theaterPostItem.postIndex] : {}
            },
            holaMola: function () {
                return this.postItem.images !== undefined ? this.postItem.images.length : false
            }
        }
    }
</script>