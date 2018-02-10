<template>
    <div>
        <app-confirm :unid="unid" :body="body"></app-confirm>
        <div id="picture-option-dialog" class="md-dialog md-dialog--maintain-width md-dialog--post-option md-dialog--full-screen" aria-hidden="false">
            <div class="md-dialog__wrapper">
                <div class="md-dialog__shadow"></div>
                <div class="md-dialog__surface" style="position: relative;">
                    <div class="md-dialog__body">
                        <div class="ft-dialog-option">
                            <a href="javascript:;" data-value="post" class="hidden btn ft-dialog-option__item" @click="initRemoveBgPicture">
                                Remove Current Photo
                            </a>
                            <form method="post" enctype="multipart/form-data">
                                <label for="cover-picture" data-value="post" class="btn ft-dialog-option__item" style="text-transform: none">
                                    Upload Photo
                                </label>
                                <input type="file" accept="image/*" style="height:0;width: 0" class="" id="cover-picture" name="">
                            </form>
                            <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item" @click="cancelTask">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="picture-profile-option-dialog" class="md-dialog md-dialog--maintain-width md-dialog--post-option md-dialog--full-screen" aria-hidden="false">
            <div class="md-dialog__wrapper">
                <div class="md-dialog__shadow"></div>
                <div class="md-dialog__surface" style="position: relative;">
                    <div class="md-dialog__body">
                        <div class="ft-dialog-option">
                            <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item" @click="uploadBgPicture">
                                Remove Current Photo
                            </a>
                            <form method="post" enctype="multipart/form-data">
                                <label for="profile-picture" data-value="post" class="btn ft-dialog-option__item" style="text-transform: none">
                                    Upload Photo
                                </label>
                                <input type="file" accept="image/*" style="height:0;width: 0" class="" id="profile-picture" name="">
                            </form>
                            <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item" @click="cancelTask">
                                Cancel
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
                unid: 'app-confirm-profile-picture',
                body: 'Do you really want to block this user?',
                isLoading: false,
                initEdit: false
            }
        },
        components: {
            'app-confirm': appConfirm,
            'report-picture-option': appConfirm
        },
        mounted () {
            let that = this
            let dialog = $('#comment-option-dialog').MaterialDialog({show:false});
            dialog.on('ca.dialog.hidden', function () {
            });
            $('#profile-picture').change(function(){
                if($(this).val() !== '') {
                    that.uploadPicture()
                }
            })
            $('#cover-picture').change(function(){
                if($(this).val() !== '') {
                    that.uploadCover()
                }
            })
        },
        methods: {
            initRemoveBgPicture: function () {

            },
            removeBgPicture: function () {

            },
            uploadBgPicture: function ()  {

            },
            cancelTask: function () {
                $('#picture-option-dialog').MaterialDialog('hide')
            },
            uploadPicture: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                let timeline_id = $('#timeline_id_user').val()
                $('#picture-profile-option-dialog').MaterialDialog('hide')
                $('#timeline-user-avtar .absolute-loader').removeClass('hidden')
                let data = new FormData();
                let files = document.getElementById('profile-picture').files
                if (!files.length) {
                    console.log('no files');
                }
                for (var i = 0; i < files.length; i++) {
                    let file = files.item(i);
                    data.append('change_avatar', file, file.name);
                }
                data.append('timeline_id',timeline_id)
                data.append('timeline_type','user')
                const config = {
                    headers: { 'content-type': 'multipart/form-data', _token:  _token }
                }
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/change-avatar',
                    data: data,
                    config: config
                }).then( function (response) {
                    console.log(response)
                    if (response.status ==  200) {
                        $('#timeline-user-avtar .absolute-loader').addClass('hidden')
                        $('#timeline-user-avtar').css('background-image', 'url('+ response.data.avatar_url +')')
                        materialSnackBar({messageText: 'Profile Picture has been changed.', autoClose: true })
                    }
                }).catch(function(error) {
                    $('#timeline-user-avtar .absolute-loader').addClass('hidden')
                    materialSnackBar({messageText: error, autoClose: true })
                })
            },
            uploadCover: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                let timeline_id = $('#timeline_id_user').val()
                $('#picture-option-dialog').MaterialDialog('hide')
                $('#timeline-cover .absolute-loader').removeClass('hidden')
                let data = new FormData();
                let files = document.getElementById('cover-picture').files
                if (!files.length) {
                    console.log('no files');
                }
                for (var i = 0; i < files.length; i++) {
                    let file = files.item(i);
                    data.append('change_cover', file, file.name);
                }
                data.append('timeline_id',timeline_id)
                data.append('timeline_type','user')
                const config = {
                    headers: { 'content-type': 'multipart/form-data', _token:  _token }
                }
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/change-cover',
                    data: data,
                    config: config
                }).then( function (response) {
                    if (response.status ==  200) {
                        $('#timeline-cover .absolute-loader').addClass('hidden')
                        $('#timeline-cover').css('background-image', 'url('+ response.data.cover_url +')')
                        materialSnackBar({messageText: 'Cover Picture has been changed.', autoClose: true })
                    }
                }).catch(function(error) {
                    $('#timeline-cover .absolute-loader').addClass('hidden')
                    materialSnackBar({messageText: error, autoClose: true })
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
