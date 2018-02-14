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
                        <form v-on:submit.prevent="editSaveEvent" method="get" class="event-edit-group" style="padding: 24px 0">
                            <fieldset class="form-group required">
                                <input class="form-control" v-model="event.title" required placeholder="Name of your event" maxlength="45" name="name" type="text">
                            </fieldset>

                            <fieldset class="form-group required">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control selectize" id="edit-event-privacy" required="required">
                                            <option value="">Privacy</option>
                                            <option value="private">Private</option>
                                            <option value="public">Public</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control selectize" id="edit-event-gender" required="required" name="gender">
                                            <option value="">Gender</option>
                                            <option value="male">Males Only</option>
                                            <option value="female">Females Only</option>
                                            <option value="all">Everyone</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-group required">
                                <input class="form-control" v-model="event.location" placeholder="Enter location" required type="text">
                            </fieldset>

                            <fieldset class="form-group required">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="number" v-model="event.participant" class="form-control" required placeholder="Number of participants" min="1">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" v-model="event.price" readonly class="form-control" required placeholder="Price (USD)" min="0" max="10000">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-group required">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group date form_datetime">
                                            <input type="text" id="edit-event-start-date" disabled class="datepick2--event-edit form-control" name="start_date"
                                                   placeholder="Start Time" value="">
                                        <label for="edit-event-start-date" class="input-group-addon addon-right calendar-addon">
                                            <span class="fa fa-calendar"></span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control"  required v-model="event.duration" placeholder="duration" id="duration-event">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
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
                                    <div class="replace-with-edit-event"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer md-layout md-layout--row">
                        <div class="md-layout-spacer"></div>
                        <button class="btn" type="button" @click="cancelSaveEvent">Cancel</button>
                        <button type="submit" style="margin-left: 8px" class="btn btn-edit-submit ft-btn-primary" @click="editSaveEvent">Save Event</button>
                    </div>
                    <div v-if="isLoading" class="absolute-loader" style="z-index: 11">
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
    .replace-with-edit-event {
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
                options: { disableReturn: false },
                event: {
                    startDate: '' ,
                    duration:'',
                    participant:'',
                    gender:'',
                    location:'',
                    title:'',
                    price:'',
                    privacy:'',
                    id: ''
                }
            }
        },
        methods: {
            replaceImgEmoji: function () {
                $('.replace-with-edit-event').html('')
                $('.replace-with-edit-event').html($('#edit-post-vue').html())
                $.each($('.replace-with-edit-event img'), function(){
                    $(this).replaceWith('<div> '+$(this).attr('title')+' </div>');
                });
            },
            validate: function () {
                if(this.event.duration > 172800) {
                    alertApp('Event duration must be less than 48 hours')
                    return false
                }
                return true
            },
            nl2br: function(html) {
                let is_xhtml = false
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (html + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            },
            cancelSaveEvent: function () {
                $('#event-edit-option-dialog').MaterialDialog('hide')
            },
            editSaveEvent: function () {
                if(!this.validate()) {
                    return
                }
                this.replaceImgEmoji()
                this.isLoading = true
                let description = this.nl2br($('.replace-with-edit-event').html())
                this.event.privacy = $('#edit-event-privacy').val()
                this.event.gender = $('#edit-event-gender').val()
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/update-event',
                    data: {
                        _token: _token,
                        post_id: that.postItem.id,
                        description: description,
                        gender: that.event.gender,
                        location: that.event.location,
                        participant: that.event.participant,
                        privacy: that.event.privacy,
                        event_id: that.event.id,
                        title: that.event.title,
                        duration: that.event.duration
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        that.getAndSetPostById()
                    }
                }).catch(function(error) {
                    materialSnackBar({messageText: error, autoClose: true })
                    that.isLoading = false
                })
            },
            getAndSetPostById: function () {
                let that = this
                let username = current_username
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'get-single-post',
                    data: {
                        username: current_username,
                        _token: _token,
                        post_id: that.postItem.id
                    }
                }).then( function (response) {
                    console.log(response)
                    if (response.status ==  200) {
                        let post = response.data[0].post;
                        that.isLoading = false
                        $('#event-edit-option-dialog').MaterialDialog('hide')
                        $('#post-option-dialog').MaterialDialog('hide')
                        that.$store.commit('REPLACE_POST_ITEM',{data: post, index: that.index} )
                        setTimeout(function () {
                            emojify.run();
                            hashtagify();
                            mentionify();
                        }, 300)
                        materialSnackBar({messageText: 'Event updated successfully', autoClose: true })
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            },
            processEditOperationPost: function (operation) {
                this.backContent = operation.api.origElements.innerHTML
            },
            getDuration: function (s,e) {
                let t1 = new Date(s + 'Z')
                let t2 = new Date(e + 'Z')
                console.log((t1.getTime() - t2.getTime()) / 1000)
                return Math.abs( (t1.getTime() - t2.getTime()) / 1000);
            },
            initEventForm: function () {
                this.event.startDate = this.postItem.event[0].start_date
                console.log(this.event.startDate)
                this.event.duration = this.getDuration(this.postItem.event[0].start_date, this.postItem.event[0].end_date)
                this.event.gender = this.postItem.event[0].gender
                this.event.participant = this.postItem.event[0].user_limit
                this.event.location = this.postItem.event[0].location
                this.event.title = this.postItem.timeline.name
                this.event.price = this.postItem.event[0].price
                this.event.privacy = this.postItem.event[0].type
                this.event.id = this.postItem.event[0].id
                $('#edit-event-start-date').val(this.event.startDate)
                $("#edit-event-privacy option[value=" + this.event.privacy +"]").attr("selected","selected")
                $("#edit-event-gender option[value=" + this.event.gender +"]").attr("selected","selected")
            },
            initEventPlugin: function () {
                setTimeout(function(){
                    emojify.run()
                    $('.selectize').selectize()
                    $(".datepick2--event-edit").datetimepicker({
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
            }
        },
        mounted () {
            let that = this
            this.placeholder = 'Event Description'
            let dialog = $('#event-edit-option-dialog').MaterialDialog({show:false});
            this.backContent = this.backContentPrev
            if(this.hasItem) {
                this.initEventForm()
            }
            this.initEventPlugin()
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
        }
    }
</script>
