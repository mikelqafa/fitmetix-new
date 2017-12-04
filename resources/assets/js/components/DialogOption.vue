<template>
    <div class="md-dialog md-dialog--dark md-dialog--post-option md-dialog--full-screen" id="post-option-dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface">
                <div class="md-dialog__body">
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
    </div>
</template>
<style>
    .md-dialog--post-option .md-dialog__body {
        padding: 0;
        margin-top: 0;
    }
    .md-dialog--post-option .md-dialog__wrapper {
        justify-content: center;
    }
    .ft-dialog-option__item:active {
        box-shadow: none !important;
    }
    .ft-dialog-option__item {
        width: 90vw;
        max-width: 510px;
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
        mounted () {
            $('#post-option-dialog').MaterialDialog('show')
        }
    }
</script>
