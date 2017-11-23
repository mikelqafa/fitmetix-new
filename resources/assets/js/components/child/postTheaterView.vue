<template>
    <div class="md-dialog md-dialog--dark md-dialog--theater md-dialog--full-screen" id="post-image-theater-dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface">
                <header class="md-dialog__header panel-post hidden md-dialog__header--xs visible-xs">
                    <template v-if="holaMola">
                        <post-header style="flex-grow: 1" :post-data="postItem" :date="postItem.created_at"></post-header>
                    </template>
                    <a href="javascript:;" style="margin-right: 15px" class="md-button md-button--icon md-dialog__header-action-dismissive" data-action="dismissive">
                        <i class="icon icon-close"></i>
                    </a>
                </header>
                <div class="md-dialog__body">
                    <div class="stage">
                        <template v-if="holaMola">
                            <image-theater-surface :post-index="theaterPostItem.postIndex" :post-img="postItem.images"></image-theater-surface>
                        </template>
                    </div>
                    <div class="stage-photo-sidebar">
                        <header class="panel-post hidden-xs">
                            <template v-if="holaMola">
                                <post-header style="flex-grow: 1" :post-data="postItem" :date="postItem.created_at"></post-header>
                                <post-description :post-html="postItem.description"></post-description>
                            </template>
                        </header>
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

<style>
    .text-wrapper + .post-image--wrapper {
        margin-top: 15px;
    }
    @media screen  and (min-width: 768px){
        .md-dialog--theater {
            overflow-x: auto;
        }
        .md-dialog--theater .md-dialog__surface {
            max-width: 100%;
            min-width: 900px;
        }
        .md-dialog--theater .md-dialog__body {
            width: 1020px;
            max-width: 100%;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            min-height: 520px;
            height: 90vh;
            max-height: 636px;
        }
        .md-dialog--theater .md-dialog__wrapper {
            justify-content: center;
        }
        .md-dialog--theater .md-dialog__surface .img-viewer,
        .md-dialog--theater .md-dialog__surface .post-image--wrapper {
            height: 100%;
        }
        .md-dialog--theater .md-dialog__surface .img-viewer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .md-dialog--theater .md-dialog__surface .img-responsive{
            max-height: 100%;
        }
        .md-dialog--theater .stage {
            width: 660px;
        }
        .md-dialog--dark .stage{
            background-color: #000;
            display: flex;
            justify-content: center;
            flex-shrink: 0;
            align-items: center;
        }
        .stage-photo-sidebar {
            flex-grow: 1;
            flex-shrink: 1;
            max-height: 570px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .stage-photo-sidebar .text-wrapper {
            padding: 0 15px;
        }
    }

    .md-dialog__header.panel-post{
        padding-left:0;
        padding-right: 0;
    }
    .md-dialog__header .panel-heading {
        border-left: none !important;
        border-right:none !important;
    }
    .md-button {
        background: transparent;
        border: none;
        border-radius: 2px;
        color: rgba(0,0,0,.84);
        position: relative;
        height: 36px;
        min-width: 64px;
        padding: 0 16px;
        display: inline-block;
        font-family: Roboto,sans-serif;
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0;
        overflow: hidden;
        will-change: box-shadow;
        transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),color .2s cubic-bezier(.4,0,.2,1);
        -webkit-transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),color .2s cubic-bezier(.4,0,.2,1);
        outline: none;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        line-height: 36px;
        vertical-align: middle;
    }
    .md-button--icon{
        border-radius: 50%;
        font-size: 24px;
        height: 32px;
        margin-left: 0;
        margin-right: 0;
        min-width: 32px;
        width: 32px;
        padding: 0;
        overflow: hidden;
        line-height: normal;
    }
    .md-button--icon .icon {
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-12px,-12px);
        transform: translate(-12px,-12px);
        line-height: 24px;
        width: 24px;
        font-size: 24px;
    }
    .md-dialog__header .post-options {
        display: none;
    }
    .md-dialog--theater .md-dialog__shadow {
        background-color: rgba(0,0,0,.7);
    }
    .md-dialog--theater .md-dialog__body {
        padding-left: 0;
        padding-right: 0;
        margin-top: 0;
        padding-bottom: 0;
    }
    .md-dialog--theater .md-dialog__header {
        padding-bottom: 0;
        padding-top: 0;
    }
    @media screen and (max-width: 767px) {
        .md-dialog__header--xs{
            display: flex !important;
            flex-direction: row;
            flex-wrap: nowrap;
        }
        .md-dialog--theater .md-dialog__surface {
            height: 100vh;
            color: #fff;
        }
        .md-dialog--theater .md-dialog__body {
            justify-content: center;
            align-items: center;
            display: flex;
        }
        .md-dialog--dark .md-dialog__surface{
            background-color: #0A0A0A;
        }
        .md-dialog--dark .panel-heading.no-bg{
            background-color: transparent !important;
            border-top:none !important;
            border-bottom:none !important;
        }
        .md-dialog--dark .ft-socialite{
            background-color: transparent;
        }
        .md-dialog--dark .ft-expression{
            color: #fff;
        }
    }
</style>

<script>
    import imageTheaterSurface from './imageTheaterSurface'
    import postHeader from './postHeader'
    import postComment from './postComment'
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
            'post-description': postDescription
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
                noBack();
            });
            dialog.on('ca.dialog.hidden', function () {
                resetBack();
                that.$store.commit('SET_THEATER_ITEM', {postIndex: undefined, imageIndex: undefined})
            });
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