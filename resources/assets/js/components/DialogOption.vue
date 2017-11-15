<template>
    <div class="ft-dialog">
        <div class="ft-dialog__inner-layer" @click="closeDialog"></div>
        <button class="ft-dialog__btn" @click="closeDialog"><i class="icon icon-close"></i> </button>
        <div class="ft-dialog__wrapper">
            <div class="ft-dialog__surface">
                <div class="ft-dialog-option">
                    <a href="javascript:;" data-value="post" class="btn ft-dialog-option__item" @click="emitAction">
                        Go to post
                    </a>
                    <a href="javascript:;" data-value="embed" class="btn ft-dialog-option__item" @click="emitAction">
                        Embed
                    </a>
                    <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item" @click="emitAction">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .navbar.socialite{
        z-index: 10 !important;
    }
    .ft-dialog {
        background-color: rgba(0,0,0,.5);
        bottom: 0;
        justify-content: space-between;
        left: 0;
        overflow-y: auto;
        position: fixed;
        right: 0;
        top: 0;
        z-index: 12;
        display: none;
    }
    .ft-dialog--open {
        display: block;
    }
    .ft-dialog__inner-layer {
        bottom: 0;
        left: 0;
        position: fixed;
        right: 0;
        top: 0;
        z-index: 11
    }
    .ft-dialog__wrapper{
        padding: 0 40px;
        position: relative;
        pointer-events: none;
        -webkit-transform: translate3d(0,0,0);
        transform: translate3d(0,0,0);
        display: flex;
        min-height: 100%;
        overflow: auto;
        width: auto;
        z-index: 11;
    }
    .ft-dialog__surface {
        align-items: center;
        margin: auto;
        max-width: 935px;
        pointer-events: auto;
        width: 100%;
    }
    .ft-dialog-option{
        width: 100%;
        min-height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .ft-dialog-option__item {
        background: #fff;
        border: none;
        color: #262626;
        cursor: pointer;
        font-size: 16px;
        font-weight: 400;
        line-height: 50px;
        margin: 0;
        overflow: hidden;
        border-radius: 0;
        padding: 0 16px;
        text-align: center;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 100%;
        max-width: 510px;
        border-bottom: 1px solid #dbdbdb;
    }
    .ft-dialog-option__item:hover,
    .ft-dialog-option__item:focus,
    .ft-dialog-option__item:active{
        background-color: #efefef;
    }
    .ft-dialog__btn{
        position: fixed;
        top:15px;
        right:15px;
        font-size: 24px;
        z-index: 14;
        cursor: pointer;
    }
</style>
<script>
    export default {
        data: function () {
            return {}
        },
        computed: {},
        methods: {
            closeDialog: function () {
               $(this.$el).removeClass('ft-dialog--open')
            },
            emitAction: function(e) {
                console.log(e.target.getAttribute('data-value'))
            }
        },
        mounted () {}
    }
</script>