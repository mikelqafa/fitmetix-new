<template>
    <div class="pos-rel create-event-form">
        <div class="form-group required layout-m-t-1">
            <div class="event_images_upload--label md-layout md-align md-align--center-center">
                <a href="javascript:;" style="z-index: 2; width: 200px;" id="eventImageUpload" class="form-helper-wrapper md-layout md-layout--row md-align md-align--center-center">
                    <div class="icon icon-photo center-block" style="font-size: 56px;width: 56px;"></div>
                    <div class="form-helper" style="top: 60px;">
                        <div class="helper-inner arrow_box">
                            Upload event's images. You can upload multiple images.
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <app-image-uploader v-on:imagechagned="setClass" ref="vue_event_img"></app-image-uploader>
    </div>
</template>

<script>
    import appImageUploader from './child/createEventImageUploader'

    export default {
        data: function () {
            return {
                userImage: ''
            }
        },
        mounted () {
            let that = this
            $(document).on('click','#eventImageUpload',function(e){
                e.preventDefault();
                $('#upload-action-create').trigger('click');
            });
            $('.create-event-form').submit(function( e ) {
                if(that.checkImageStatus()){

                } else {
                    e.preventDefault()
                }
            });
        },
        components: {
            'app-image-uploader': appImageUploader
        },
        methods: {
            checkImageStatus: function () {
                if($('#gender').val() =='') {
                    alertApp('Please select gender!')
                    return false
                }
                if($('#location-input').val() =='') {
                    alertApp('Please enter location!')
                    return false
                }
                if($('#description').val() =='') {
                    alertApp('Please write description!')
                    return false
                }
                if($('#privacy').val() =='') {
                    alertApp('Please select privacy!')
                    return false
                }
                if($('#duration-event').val() == 0) {
                    alertApp('Please provide duration for the event!')
                    return false
                }
                if($('#duration-event').val() > 172800) {
                    alertApp('Event duration must be less than 48 hours')
                    return false
                }
                let $imageInputs = this.$refs.vue_event_img.files
                if($imageInputs.length == 0) {
                    alertApp('Please upload image!')
                    return false;
                }
                let imageUploaded = true
                for(let i=0; i<$imageInputs.length; i++) {
                    if($imageInputs[i].status == 'added') {
                        imageUploaded = false
                        break
                    }
                }
                if(!imageUploaded) {
                    alertApp('Please wait while images are being uploading')
                    return false;
                }
                let invalid = false
                for(let i=0; i<$imageInputs.length; i++) {
                    if($imageInputs[i].status == 'error') {
                        invalid = true
                        break
                    }
                }
                if(invalid) {
                    alertApp('Unable to create event. Remove invalid images or try again!')
                    return false;
                }
                for(let i=0; i<$imageInputs.length; i++) {
                   console.log($imageInputs[i])
                }
                return true
            },
            setClass: function (e) {
                if(e) {
                    $('.create-event-form').addClass('has-item')
                } else {
                    $('.create-event-form').removeClass('has-item')
                }
            },
            resetCreatePost: function () {
                let create_post_form = $('.create-event-form')
                create_post_form.find("input[type=text], textarea, input[type=file]").val("")
                create_post_form.find('#post-image-holder').empty()
                create_post_form.find('.post-images-selected').hide()
                this.$refs.vue_event_img.reset()
            }
        },
        computed: {
            hasItem() {
                return ''
            }
        }
    }
</script>