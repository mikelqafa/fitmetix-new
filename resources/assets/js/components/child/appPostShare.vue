<template>
    <div>
        <app-confirm :unid="unid" :body="body"></app-confirm>
        <div class="md-dialog md-dialog--center md-dialog--maintain-width md-dialog--md" id="post-share-dialog">
            <div class="md-dialog__wrapper">
                <div class="md-dialog__shadow"></div>
                <div class="md-dialog__surface" style="position: relative">
                    <header class="md-dialog__header">
                        Help us keep Fitmetix an environment that promotes healthy living.
                    </header>
                    <div class="md-dialog__body" v-if="hasItem">
                        <div class="form-group">
                            <mention-at :members="members" v-on:at="fetchUser" name-key="name">
                                <template slot="item" scope="s">
                                    <img :src="s.item.avatar">
                                    <span v-text="s.item.name"></span>
                                </template>
                                <label for="share-user-select">Select user to share:</label>
                                <input placeholder="Send to" class="form-control" id="share-user-select"/>
                            </mention-at>
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
                        <button class="md-dialog__action md-button ft-btn-primary btn md-button--compact" @click="confirmReport">Share</button>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    #post-share-dialog{
        z-index: 26;
    }
</style>

<script>
    import { mapGetters } from 'vuex'
    import appConfirm from './appConfirm'
    import At from 'vue-at'
    export default {
        props: {
            postItem: '',
            index: 0
        },
        data: function () {
            return {
                body: 'Do you really want to report this post?',
                isLoading: false,
                userImage: '',
                placeholder: '',
                content: '',
                backContent: '',
                viewContent: '',
                reportComment: '',
                unid: 'app-confirm-share-post',
                imageFile: [],
                options: { disableReturn: false },
                selectizeUsers: [],
                members: []
            }
        },
        computed: {
            hasItem: function () {
                return true
            }
        },
        components: {
            'mention-at':At,
            'app-confirm': appConfirm
        },
        methods: {
            fetchUser: function (data) {
                if(data == '') {
                    return
                }
                let that = this
                axios({
                    method: 'get',
                    responseType: 'json',
                    url: base_url + 'ajax/get-users-mentions',
                    params: {
                        query: data,
                        limit: 10
                    }
                }).then( function (response) {
                    that.members = []
                    if(response.status == 200) {
                        for(let i=0; i<response.data.length; i++) {
                            that.members.push({avatar: response.data[i].image, name: response.data[i].username})
                            //console.log({avatar: response.data[i].image, name: response.data[i].username})
                        }
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            },
            confirmReport: function () {
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.MaterialDialog('show')
                let that = this
                confirmDialog.on('ca.dialog.affirmative.action', function(){
                    that.sharePost()
                });
            },
            sharePost: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.off('ca.dialog.affirmative.action');
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/report-post',
                    data: {
                        _token: _token,
                        post_id: that.postItem.id,
                        description: that.reportComment
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        materialSnackBar({messageText: response.data.message, autoClose: true })
                        $('#post-share-dialog').MaterialDialog('hide')
                        $('#post-share-dialog').MaterialDialog('hide')
                        $('#post-image-theater-dialog').MaterialDialog('hide')
                    }
                    that.isLoading = false
                    that.reportComment = ''
                }).catch(function(error) {
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                })
            },
        },
        mounted() {
            // $('#post-share-dialog').MaterialDialog({show:true})
            let that = this
        }
    }
</script>
