<template>
    <div class="">
        <div class="md-dialog md-dialog--center md-dialog--maintain-width md-dialog--md" id="post-share-init-dialog">
            <div class="md-dialog__wrapper">
                <div class="md-dialog__shadow"></div>
                <div class="md-dialog__surface" style="position: relative">
                    <template v-if="hasItem">
                        <header class="md-dialog__header">
                        </header>
                        <div class="md-dialog__body">
                            <div class="form-group">
                                <label for="share-user-select">Select user to share:</label>
                                <input placeholder="Send to" class="hidden form-control" id="share-user-select"/>
                                <app-autocomplete ref="shareUser"></app-autocomplete>
                            </div>
                        </div>
                        <footer class="md-dialog__footer">
                            <button class="md-dialog__action md-button md-button--compact" data-action="dismissive">CANCEL</button>
                            <button class="md-dialog__action md-button ft-btn-primary btn md-button--compact" @click="confirmReport">Share</button>
                        </footer>
                    </template>
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

<style>
    #post-share-dialog{
        z-index: 26;
    }
</style>

<script>
    import { mapGetters } from 'vuex'
    import appConfirm from './appConfirm'
    import appAutocomplete from './appAutocomplete'
    export default {
        props: {
            index: 0
        },
        data: function () {
            return {
                isLoading: false,
                userImage: '',
                placeholder: '',
                content: '',
                backContent: '',
                viewContent: '',
                reportComment: '',
                unid: 'app-confirm-share-post',
                imageFile: [],
                selectizeUsers: [],
                members: [],
                cities : [
                    'Bangalore','Chennai','Cochin','Delhi','Kolkata','Mumbai'
                ],
                value: ''
            }
        },
        components: {
            'app-confirm': appConfirm,
            'app-autocomplete': appAutocomplete
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
                let userList = this.$refs.shareUser.selections
                if(userList !== undefined && userList.length) {
                    this.sharePost (userList)
                } else {
                    alertApp('Please add user to share!')
                }
            },
            sharePost: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/share-post',
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
            addSuggestion: function (e) {
                this.members.push('hola')
            }
        },
        mounted () {
            let that = this
            let dialog = $('#post-share-init-dialog').MaterialDialog({show:false});
            dialog.on('ca.dialog.shown', function () {
                window.setTimeout(function(){
                    emojify.run()
                    hashtagify()
                }, 300)
            });
            dialog.on('ca.dialog.hidden', function () {
                that.$store.commit('SET_POST_SHARE_ITEM', {postIndex: undefined})
            });
        },
        computed: {
            ...mapGetters({
                sharePostItem: 'sharePostItem'
            }),
            postItem: function () {
                return this.sharePostItem.postIndex !== undefined ? this.$store.state.postItemList[this.sharePostItem.postIndex] : {}
            },
            hasItem: function () {
                return this.postItem.id !== undefined
            }
        }
    }
</script>
