<template>
    <div class="md-dialog md-dialog--center md-dialog--maintain-width md-dialog--md" id="send-direct-dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface" style="position: relative">
                <div class="md-dialog__body">
                    <div class="form-group">
                        <label for="comment">Write your message:</label>
                        <textarea class="form-control" v-model="chatMessage"  rows="5" id="comment"></textarea>
                        <input type="hidden" value="" class="hidden-user-id">
                    </div>
                </div>
                <footer class="md-dialog__footer">
                    <button class="md-dialog__action md-button md-button--compact" data-action="dismissive">CANCEL</button>
                    <button class="md-dialog__action md-button ft-btn-primary btn md-button--compact" @click="sendMessage">SEND</button>
                </footer>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                chatMessage: ''
            }
        },
        methods: {
            sendMessage: function () {
                if(this.chatMessage.trim() != '') {
                    $('#send-direct-dialog').MaterialDialog('hide')
                    this.$store.dispatch('sendDirectMessage', {recipients:$('.hidden-user-id').val(), message: this.chatMessage})
                }
            }
        },
        mounted() {
            let confirmDialog = $('#send-direct-dialog')
            confirmDialog.MaterialDialog({show:false})
            let that = this
            confirmDialog.on('ca.dialog.hidden', function(){
                that.chatMessage = ''
            });
        }
    }
</script>