<template>
    <div>
        <app-confirm :unid="unid" :body="body"></app-confirm>
        <div class="md-dialog md-dialog--center md-dialog--maintain-width md-dialog--md" id="comment-report-dialog">
            <div class="md-dialog__wrapper">
                <div class="md-dialog__shadow"></div>
                <div class="md-dialog__surface" style="position: relative">
                    <header class="md-dialog__header">
                        Help us keep Fitmetix an environment that promotes healthy living.
                    </header>
                    <div class="md-dialog__body">
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
        <div class="md-dialog md-dialog--maintain-width md-dialog--post-option md-dialog--zindex-default  md-dialog--full-screen" id="comment-option-dialog">
            <div class="md-dialog__wrapper">
                <div class="md-dialog__shadow"></div>
                <div class="md-dialog__surface" style="position: relative">
                    <div class="md-dialog__body">
                        <div class="ft-dialog-option" v-bind:class="{'is-loading': isLoading}">
                            <a v-if="!authUser" href="javascript:;" data-value="post" class="btn ft-dialog-option__item" @click="initReportComment">
                                Report Comment
                            </a>
                            <a v-else="" href="javascript:;" data-value="post" class="btn ft-dialog-option__item" @click="confirmDeleteComment">
                                Delete Comment
                            </a>
                        </div>
                    </div>
                    <div v-if="isLoading" class="absolute-loader">
                        <div class="ft-loading">
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
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
                unid: 'app-confirm-delete-comment',
                body: 'Do you really want to delete this comment?',
                isLoading: false,
                initEdit: false,
                userImage: '',
                placeholder: '',
                content: '',
                backContent: '',
                viewContent: '',
                reportComment: '',
                imageFile: [],
                options: { disableReturn: false },
                commentIndex: 0
            }
        },
        components: {
            'app-confirm': appConfirm
        },
        mounted () {
            let that = this
            let dialog = $('#comment-option-dialog').MaterialDialog({show:false});
        },
        methods: {
            confirmReport: function () {
                this.body = 'Do you really want to report this comment?'
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.MaterialDialog('show')
                let that = this
                confirmDialog.on('ca.dialog.affirmative.action', function(){
                    that.reportCommentM()
                });
            },
            initReportComment: function () {
              $('#comment-report-dialog').MaterialDialog('show')
            },
            reportCommentM: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.off('ca.dialog.affirmative.action');
                $('#comment-report-dialog').MaterialDialog('hide')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/report-comment',
                    data: {
                        _token: _token,
                        comment_id: that.optionMenuPostItem.comment.id,
                        description: that.reportComment
                    }
                }).then( function (response) {
                    $('#comment-option-dialog').MaterialDialog('hide')
                    if (response.status ==  200) {
                        materialSnackBar({messageText: response.data.message, autoClose: true })
                    }
                    that.isLoading = false
                    that.reportComment = ''
                }).catch(function(error) {
                    $('#comment-option-dialog').MaterialDialog('hide')
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                })
            },
            confirmDeleteComment: function () {
                this.body = 'Do you really want to delete this comment?'
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.MaterialDialog('show')
                let that = this
                confirmDialog.on('ca.dialog.affirmative.action', function(){
                    that.deleteComment()
                });
            },
            deleteComment: function() {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                let comment_id = that.optionMenuPostItem.comment.id
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.MaterialDialog('hide')
                //comment-option-dialog
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/comment-delete',
                    data: {
                        _token: _token,
                        comment_id: comment_id
                    }
                }).then( function (response) {
                    $('#comment-option-dialog').MaterialDialog('hide');
                    if (response.status ==  200) {
                        that.$store.commit('REMOVE_POST_COMMENT', {postIndex: that.optionMenuPostItem.postIndex, commentIndex:that.optionMenuPostItem.index})
                        materialSnackBar({messageText: response.data.message, autoClose: true })
                    }
                    that.isLoading = false
                }).catch(function(error) {
                    $('#comment-report-dialog').MaterialDialog('hide')
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                    $('#comment-option-dialog').MaterialDialog('hide');
                })
            }
        },
        computed: {
            ...mapGetters({
                optionMenuPostItem: 'commentOption'
            }),
            postItem: function () {
                return this.optionMenuPostItem.comment !== undefined ? this.optionMenuPostItem.comment : undefined
            },
            authUser: function () {
                return this.postItem !== undefined ? (this.postItem.user.user_id == user_id || this.postItem.user_id == user_id): false
            }
        }
    }
</script>
