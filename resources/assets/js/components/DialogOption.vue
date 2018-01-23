<template>
    <div>
        <app-confirm :unid="unid" :body="body"></app-confirm>
        <report-post-option v-if="!authUser && (postItem !== '')" :post-item="postItem"></report-post-option>
        <edit-post-option :index="optionMenuPostItem.postIndex" v-if="(postItem !== '') && initEdit && !isTypeEvent"  :post-item="postItem"></edit-post-option>
        <edit-event-option :index="optionMenuPostItem.postIndex" v-if="(postItem !== '') && initEdit && isTypeEvent"  :post-item="postItem"></edit-event-option>
        <div class="md-dialog md-dialog--maintain-width md-dialog--post-option md-dialog--full-screen md-dialog-post-option" id="post-option-dialog">
            <div class="md-dialog__wrapper">
                <div class="md-dialog__shadow"></div>
                <div class="md-dialog__surface" style="position: relative">
                    <div class="md-dialog__body">
                        <div class="ft-dialog-option" v-bind:class="{'is-loading': isLoading}">
                            <template v-if="authUser">
                                <a v-if="isTypeEvent" href="javascript:;" data-value="post" class="btn ft-dialog-option__item" @click="editEvent">
                                    Edit Event
                                </a>
                                <a v-else="" href="javascript:;" data-value="post" class="btn ft-dialog-option__item" @click="editPost">
                                    Edit Post
                                </a>

                                <a v-if="isTypeEvent" href="javascript:;" data-value="post" class="btn ft-dialog-option__item" @click="confirmDeleteEvent">
                                    Delete Event
                                </a>
                                <a v-else="" href="javascript:;" class="btn ft-dialog-option__item" @click="confirmDeletePost">
                                    Delete Post
                                </a>

                                <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item" @click="shareTo('facebook')">
                                    Share
                                </a>
                            </template>
                            <template v-else="">
                                <a v-if="isTypeEvent" href="javascript:;" data-value="post" class="btn ft-dialog-option__item" @click="initReportPost">
                                    Report Event
                                </a>
                                <a v-else="" href="javascript:;" data-value="post" class="btn ft-dialog-option__item" @click="initReportPost">
                                    Report Post
                                </a>
                                <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item" @click="shareTo('facebook')">
                                    Share
                                </a>
                                <a href="javascript:;" class="btn ft-dialog-option__item" @click="savePost">
                                    Save/Unsave
                                </a>
                            </template>
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
    import editPostOption from './child/editPost'
    import editEventOption from './child/editEvent'
    import reportPostOption from './child/sendReport'
    import { mapGetters } from 'vuex'
    import appConfirm from './child/appConfirm'
    export default {
        data: function () {
            return {
                unid: 'app-confirm-delete-post',
                body: 'Do you really want to delete this post?',
                isLoading: false,
                initEdit: false
            }
        },
        methods: {
            emitAction: function(e) {
                console.log(e.target.getAttribute('data-value'))
            },
            initReportPost: function() {
                $('#post-report-dialog').MaterialDialog('show')
            },
            confirmDeletePost: function () {
                this.body = 'Do you really want to delete this post?'
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.MaterialDialog('show')
                let that = this
                confirmDialog.on('ca.dialog.affirmative.action', function(){
                    that.deletePost()
                });
            },
            confirmDeleteEvent: function () {
                this.body = 'Are you sure you want to delete this event entirely?'
                let confirmDialog = $('#'+ this.unid)
                confirmDialog.MaterialDialog('show')
                let that = this
                confirmDialog.on('ca.dialog.affirmative.action', function(){
                    that.deleteEvent()
                });
            },
            deletePost: function() {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                $('#post-image-theater-dialog').MaterialDialog('hide')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/post-delete',
                    data: {
                        _token: _token,
                        post_id: that.postItem.id
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        that.$store.commit('REMOVE_POST_ITEM_LIST', that.optionMenuPostItem.postIndex)
                        $('#post-option-dialog').MaterialDialog('hide')
                        materialSnackBar({messageText: response.data.message, autoClose: true })
                    }
                    that.isLoading = false
                }).catch(function(error) {
                    $('#post-option-dialog').MaterialDialog('hide')
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                })
            },
            deleteEvent: function() {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                $('#post-image-theater-dialog').MaterialDialog('hide')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/event-delete',
                    data: {
                        _token: _token,
                        event_id: that.postItem.event[0].id
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        $('#post-option-dialog').MaterialDialog('hide')
                        $('#drawer-1').MaterialDialog('hide')
                        that.$store.commit('REMOVE_POST_ITEM_LIST', that.optionMenuPostItem.postIndex)
                        materialSnackBar({messageText: response.data.message, autoClose: true })
                        setTimeout(function(){
                            window.location.reload()
                        },300)
                    }
                    that.isLoading = false
                }).catch(function(error) {
                    $('#post-option-dialog').MaterialDialog('hide')
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                })
            },
            savePost: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/save-post',
                    data: {
                        _token: _token,
                       post_id: that.postItem.id
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        materialSnackBar({messageText: response.data.message, autoClose: true })
                        $('#post-option-dialog').MaterialDialog('hide')
                    }
                    that.isLoading = false
                }).catch(function(error) {
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                })
            },
            editPost: function () {
                this.initEdit = true
                setTimeout(function() {
                    $('#post-edit-option-dialog').MaterialDialog('show');
                },300)
            },
            editEvent: function () {
                this.initEdit = true
                setTimeout(function() {
                    $('#event-edit-option-dialog').MaterialDialog('show');
                },300)
            },
            shareTo: function (network) {
                let that = this
                switch (network) {
                    case 'facebook':
                        FB.ui({
                            method: 'feed',
                            name: 'shared the post',
                            link: base_url+'post/'+that.postItem.id,
                            picture: that.postItem.postImg !== undefined ? '' : that.postItem.postImg,
                            description: that.postItem.description
                        }, function (response) {
                            console.log(response)
                        })
                        break
                }
            }
        },
        components: {
            'edit-post-option': editPostOption,
            'edit-event-option': editEventOption,
            'report-post-option': reportPostOption,
            'app-confirm': appConfirm
        },
        mounted () {
            let that = this
            let dialog = $('#post-option-dialog').MaterialDialog({show:false});
            dialog.on('ca.dialog.hidden', function () {
                this.initEdit = false
                that.$store.commit('SET_OPTIONS_MENU_ITEM', {postIndex: undefined})
            });
        },
        computed: {
            ...mapGetters({
                    optionMenuPostItem: 'optionMenuPostItem'
            }),
            postItem: function () {
                return this.optionMenuPostItem.postIndex !== undefined ? this.$store.state.postItemList[this.optionMenuPostItem.postIndex] : {}
            },
            authUser: function () {
                return this.postItem !== undefined ? this.postItem.user_id == user_id : false
            },
            isTypeEvent: function () {
                return this.postItem !== undefined ? this.postItem.type == 'event' : false
            }
        }
    }
</script>
