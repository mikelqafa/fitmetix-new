<template>
    <div>
        <app-confirm :unid="unid" :body="body"></app-confirm>
        <div id="profile-option-dialog" class="md-dialog md-dialog--maintain-width md-dialog--post-option md-dialog--full-screen" aria-hidden="false">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface" style="position: relative;">
                <div class="md-dialog__body">
                    <div class="ft-dialog-option">
                        <a href="javascript:;" data-value="post" class="btn ft-dialog-option__item" @click="initReportUser">
                            Report user
                        </a>
                        <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item" @click="initBlockUser">
                            Block user
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import appConfirm from './child/appConfirm'

    export default {
        data: function () {
            return {
                unid: 'app-confirm-block-user',
                body: 'Do you really want to block this user?',
                isLoading: false,
                initEdit: false
            }
        },
        components: {
            'app-confirm': appConfirm,
            'report-profile-option': appConfirm
        },
        mounted () {
            let that = this
            let dialog = $('#comment-option-dialog').MaterialDialog({show:false});
            dialog.on('ca.dialog.hidden', function () {
                this.initEdit = false
            });
        },
        methods: {
            initBlockUser: function () {
                this.body = 'Do you really want to block this user?'
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.MaterialDialog('show')
                let that = this
                confirmDialog.on('ca.dialog.affirmative.action', function(){
                    that.blockUser()
                });
            },
            initReportUser: function () {
                this.body = 'Do you really want to report this user?'
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.MaterialDialog('show')
                let that = this
                confirmDialog.on('ca.dialog.affirmative.action', function(){
                    that.reportUser()
                });
            },
            blockUser: function() {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                $('#profile-option-dialog').MaterialDialog('hide')
                let url = base_url + 'ajax/block-user/'+$('#username').val()
                axios({
                    method: 'get',
                    responseType: 'json',
                    url: url,
                    data: {
                        _token: _token
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        $('#profile-option-dialog').MaterialDialog('hide')
                        materialSnackBar({messageText: 'User blocked!', autoClose: true })
                        setTimeout(function(){
                            window.location.href = base_url
                        }, 300)
                    }
                    that.isLoading = false
                }).catch(function(error) {
                    $('#profile-option-dialog').MaterialDialog('hide')
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                })
            },
            reportUser: function() {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.off('ca.dialog.affirmative.action');
                $('#profile-option-dialog').MaterialDialog('hide')
                let url = base_url + 'ajax/page-report'
                let timeline_id = $('#timeline_id').val()
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: url,
                    data: {
                        _token: _token,
                        timeline_id: timeline_id
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        materialSnackBar({messageText: response.data.message, autoClose: true })
                    }
                    setTimeout(function(){
                        window.location.href = base_url
                    }, 300)
                    that.isLoading = false
                }).catch(function(error) {
                    $('#profile-option-dialog').MaterialDialog('hide')
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                })
            }
        },
        computed: {
            ...mapGetters({
                optionMenuPostItem: 'commentOption'
            }),
            postItem: function () {
                return this.optionMenuPostItem.id !== undefined ? this.optionMenuPostItem : undefined
            },
            authUser: function () {
                return this.postItem !== undefined ? this.postItem.user_id == user_id : false
            }
        }
    }
</script>
