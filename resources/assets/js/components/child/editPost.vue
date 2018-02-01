<template>
    <div class="md-dialog md-dialog--maintain-width md-dialog--edit-post md-dialog--full-screen" id="post-edit-option-dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface" style="position: relative">
                <div class="md-dialog__body" v-if="hasItem">
                    <div class="panel-create panel-create--m-t-0">
                        <div class="panel-heading">
                            <div class="heading-text">
                                Edit Post
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="ft-cp ft-cp--edit">
                            <div class="ft-cp__presentation">
                                <div class="write-post">
                                    <div class="write-post__placeholder" v-if="hasNotContent">{{placeholder}}</div>
                                    <medium-editor id="edit-post-vue" :text='backContent' :options='options'
                                                   class="write-post__text text-wrapper"
                                                   v-on:edit='processEditOperationPost'
                                                   style="outline: none; user-select: text; white-space: pre-wrap; word-wrap: break-word;"
                                                   custom-tag='div'>
                                    </medium-editor>
                                    <div class="replace-with-edit"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer md-layout md-layout--row">
                        <div class="md-layout-spacer"></div>
                        <button class="btn" @click="cancelSavePost">Cancel</button>
                        <button style="margin-left: 8px" class="btn btn-edit-submit ft-btn-primary" @click="editSavePost">Save Post</button>
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
</template>

<script>
    import { mapGetters } from 'vuex'
    import editor from 'vue2-medium-editor'

    import postDescription from './postDescription'
    import postImageViewer from './postImageViewer'
    import postEvent from './postEvent'
    import postYouTube from './postYouTube'
    import postSoundCloud from './postSoundCloud'
    import postHeader from './postHeader'

    export default {
        props: {
            postItem: '',
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
                imageFile: [],
                options: { disableReturn: false }
            }
        },
        methods: {
            replaceImgEmoji: function () {
                $('.replace-with-edit').html('')
                $('.replace-with-edit').html($('#edit-post-vue').html())
                $.each($('.replace-with-edit img'), function(){
                    $(this).replaceWith('<div> '+$(this).attr('title')+' </div>');
                });
            },
            nl2br: function(html) {
                let is_xhtml = false
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (html + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
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
            cancelSavePost: function () {
                $('#post-edit-option-dialog').MaterialDialog('hide')
            },
            editSavePost: function () {
                this.replaceImgEmoji()
                let description = this.nl2br($('.replace-with-edit').html())
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                let create_post_button = $('.btn-edit-submit')
                create_post_button.attr('disabled', true).append(' <i class="fa fa-spinner fa-pulse "></i>');
                this.isLoading = true
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/update-post',
                    data: {
                        _token: _token,
                        post_id: that.postItem.id,
                        description: description
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        console.log(response.data)
                        that.$store.commit('EDIT_POST_ITEM', {index: that.index, description: description})
                        materialSnackBar({messageText: response.data.data, autoClose: true })
                        create_post_button.attr('disabled', false)
                        create_post_button.html(create_post_button.text())
                        $('#post-edit-option-dialog').MaterialDialog('hide')
                        setTimeout(function() {emojify.run()
                            $('#post-option-dialog').MaterialDialog('hide')
                        }, 100)
                    }
                    that.isLoading = false
                }).catch(function(error) {
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                    create_post_button.attr('disabled', false)
                    create_post_button.html(create_post_button.text())
                })
            },
            processEditOperationPost: function (operation) {
                this.backContent = operation.api.origElements.innerHTML
            }
        },
        mounted () {
            let that = this
            this.placeholder = $('#post-placeholder').val()
            let dialog = $('#post-edit-option-dialog').MaterialDialog({show:false});
            this.backContent = this.backContentPrev
            setTimeout(function(){
                emojify.run()
            }, 300)
        },
        components: {
            'medium-editor': editor,
            'post-image-viewer': postImageViewer,
            'post-event': postEvent
        },
        computed: {
            hasNotContent () {
                return this.backContent === ''
            },
            viewContentHtml: function() {
                let is_xhtml = false
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (this.viewContent + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            },
            backContentPrev: function () {
                return this.postItem.description !== undefined ? this.postItem.description : ''
            },
            hasItem: function () {
                return this.postItem.description !== undefined
            }
        },
        watch: {
            // whenever backContentPrev changes, this function will run
            backContentPrev: function (val) {
                this.backContent = val
                setTimeout(function(){
                    emojify.run()
                }, 300)
            }
        },
    }
</script>
