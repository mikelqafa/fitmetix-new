<template>
    <div class="md-dialog md-dialog--maintain-width md-dialog--edit-post md-dialog--full-screen" id="event-edit-option-dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface" style="position: relative">
                <div class="md-dialog__body" v-if="hasItem">
                    <div class="panel-create panel-create--m-t-0">
                        <div class="panel-heading">
                            <div class="heading-text">
                                Edit Event
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <post-image-viewer disable-showcase="true" :post-event="postItem.event" :post-index="index" :post-img="postItem.images"></post-image-viewer>
                        <div class="event-edit-group" style="padding: 24px 0">
                            <fieldset class="form-group required">
                                <input class="form-control" placeholder="Name of your event" maxlength="30" name="name" type="text">
                            </fieldset>

                            <fieldset class="form-group required">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control selectize" required="required">
                                            <option value="">Privacy</option>
                                            <option value="Private">Private</option>
                                            <option value="Public">Public</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control selectize" required="required" name="gender">
                                            <option value="">Gender</option>
                                            <option value="male">Males Only</option>
                                            <option value="female">Females Only</option>
                                            <option value="everyone">Everyone</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-group required">
                                <input class="form-control" placeholder="Enter location" type="text">
                            </fieldset>

                            <fieldset class="form-group required">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" placeholder="Number of participants" min="1">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" placeholder="Price (USD)" min="0" max="10000">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-group required">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group date form_datetime">
                                            <input type="text" class="datepick2--event form-control" name="start_date"
                                                   placeholder="Start Time" value="">
                                        <span class="input-group-addon addon-right calendar-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="duration" id="duration-event">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
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
                        <button class="btn" @click="cancelSaveEvent">Cancel</button>
                        <button style="margin-left: 8px" class="btn btn-edit-submit ft-btn-primary" @click="editSavePost">Save Event</button>
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

<style>
    .replace-with-edit {
        height: 0;
        width: 0;
        visibility: hidden;
    }
    .ft-cp--edit .ft-cp__presentation{
        padding: 0;
        min-height: 70px;
    }
    .post-edit-heading{
        min-height:40px;
        line-height: 20px;

    }
    .md-dialog--edit-post.md-dialog--open {
        z-index: 27;
    }
    .md-dialog--edit-post .md-dialog__body {
        margin-top: 0;
        max-width: 590px;
        width: 100%;
        padding-left: 0;
        padding-right: 0;
        padding-bottom: 0;
    }
    .md-dialog--edit-post .md-dialog__wrapper {
        justify-content: center;
    }
    .bdp-input {
        border-radius: 2px;
        padding: 4px 2px;
        border: 1px solid rgba(34, 36, 38, .15);
        cursor: pointer;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    .bdp-input > .black-label {
        color: #c3c6cb;
    }
    .bdp-input.disabled {
        color: #AAA;
        cursor: default;
    }

    .bdp-popover {
        min-width: 110px;
    }

    .bdp-popover input {
        display: inline;
        margin-bottom: 3px;
        width: 60px;
    }

    .bdp-block {
        display: inline-block;
        line-height: 1;
        text-align: center;
        padding: 5px 3px;
    }

    .bdp-label {
        font-size: 90%;
        margin-left: 4px;
    }
    .padding-bottom{
        padding-bottom: 16px;
    }
</style>

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
            cancelSaveEvent: function () {
                $('#event-edit-option-dialog').MaterialDialog('hide')
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
                        $('#event-edit-option-dialog').MaterialDialog('hide')
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
            this.placeholder = 'Event Description'
            let dialog = $('#event-edit-option-dialog').MaterialDialog({show:false});
            this.backContent = this.backContentPrev
            setTimeout(function(){
                emojify.run()
                $('.selectize').selectize()

                $(".datepick2--event").datetimepicker({
                    format: "mm/dd/yyyy H P",
                    autoclose: true,
                    minView: 1,
                    startView: "month",
                    startDate: today,
                    endDate: new Date(today.getFullYear(), today.getMonth()+3, today.getDate()),
                    showMeridian: true
                });

                $('#duration-event').durationPicker({
                    lang: 'en',
                    formatter: function (s) {
                        return s;
                    },
                    showSeconds: false
                });

            }, 300)
        },
        components: {
            'medium-editor': editor,
            'post-image-viewer': postImageViewer,
            'post-sound-cloud': postSoundCloud,
            'post-youtube': postYouTube,
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
