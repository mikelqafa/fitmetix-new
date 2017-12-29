<template>
    <div>
        <app-confirm :unid="unid" :body="body"></app-confirm>
        <div class="md-dialog md-dialog--center md-dialog--maintain-width md-dialog--md" id="profile-report-dialog">
            <div class="md-dialog__wrapper">
                <div class="md-dialog__shadow"></div>
                <div class="md-dialog__surface" style="position: relative">
                    <header class="md-dialog__header">
                        Help us keep Fitmetix an environment that promotes healthy living.
                    </header>
                    <div class="md-dialog__body" v-if="hasItem">
                        <div class="form-group">
                            <label for="comment">Write your comment:</label>
                            <textarea class="form-control" v-model="reportComment"  rows="5" id="comment"></textarea>
                        </div>
                    </div>
                    <div v-if="isLoading" class="absolute-loader">
                        <div class="ft-loading">
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                        </div>
                    </div>
                    <footer class="md-dialog__footer">
                        <button class="md-dialog__action md-button md-button--compact" data-action="dismissive">CANCEL</button>
                        <button class="md-dialog__action md-button ft-btn-primary btn md-button--compact" @click="confirmReport">REPORT</button>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    #profile-report-dialog{
        z-index: 27;
    }
</style>

<script>
    import appConfirm from './appConfirm'
    export default {
        props: {
            postItem: '',
            index: 0
        },
        data: function () {
            return {
                body: 'Do you really want to report this user?',
                isLoading: false,
                userImage: '',
                placeholder: '',
                content: '',
                backContent: '',
                viewContent: '',
                reportComment: '',
                unid: 'app-confirm-report-profile',
                imageFile: [],
                options: { disableReturn: false }
            }
        },
        computed: {
            hasItem: function () {
                return true
            }
        },
        components: {
            'app-confirm': appConfirm
        },
        methods: {
            confirmReport: function () {
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.MaterialDialog('show')
                let that = this
                confirmDialog.on('ca.dialog.affirmative.action', function(){
                    that.reportPost()
                });
            },
            reportProfile: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.off('ca.dialog.affirmative.action');
                $('#profile-report-dialog').MaterialDialog('hide')
                console.log('hola mola')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/page-report',
                    data: {
                        _token: _token,
                        post_id: that.postItem.id,
                        description: that.reportComment
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        materialSnackBar({messageText: response.data.message, autoClose: true })
                    }
                    that.isLoading = false
                    that.reportComment = ''
                }).catch(function(error) {
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                })
            }
        },
        mounted() {
            $('#profile-report-dialog').MaterialDialog({show:false})
        }
    }
</script>
