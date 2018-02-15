<template>
    <div class="md-dialog" :id="unid">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface">
                <header v-if="!alert" class="md-dialog__header">
                    <slot name="header">
                        default header
                    </slot>
                </header>
                <div class="md-dialog__body">
                    <slot name="body">
                    </slot>
                </div>
                <footer class="md-dialog__footer">
                    <slot name="footer">
                        <button class="md-dialog__action md-button md-button--compact" data-action="dismissive">{{$t('common.cancel')}}</button>
                        <button class="md-dialog__action md-button md-button--compact" data-action="affirmative">{{$t('common.continue')}}</button>
                    </slot>
                </footer>
                <slot name="dialog-surface"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            alert: false,
            unid: '',
            scrollable: false
        },
        data: function () {
            return {}
        },
        methods: {},
        mounted () {
            $('#'+ this.unid).MaterialDialog({
                show: false
            })
        },
        beforeDestroy () {
            $('#'+ this.unid).MaterialDialog('destroy')
        }
    }
</script>
