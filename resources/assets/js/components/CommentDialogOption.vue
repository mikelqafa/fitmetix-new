<template>
    <div class="ft-dialog" id="comment-option-dialog">
        <div class="ft-dialog__inner-layer" @click="closeDialog"></div>
        <a class="ft-dialog__btn" href="javascript:;" @click="closeDialog"><i class="icon icon-close"></i> </a>
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