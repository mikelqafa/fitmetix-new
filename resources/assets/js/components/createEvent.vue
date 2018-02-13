<template>
    <div class="pos-rel create-event-form">
        <div class="form-group required layout-m-t-1">
            <div class="event_images_upload--label md-layout md-align md-align--center-center">
                <a href="javascript:;" style="z-index: 2; width: 200px;" id="eventImageUpload" class="form-helper-wrapper force-focus md-layout md-layout--row md-align md-align--center-center">
                    <div class="icon icon-photo center-block" style="font-size: 56px;width: 56px;"></div>
                    <div class="form-helper" style="top: 60px;">
                        <div class="helper-inner arrow_box">
                            {{$t('common.helper_upload')}}
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
                    alertApp($t('common.alert_gender')+'!')
                    return false
                }
                if($('#location-input').val() =='') {
                    alertApp($t('common.alert_loc')+'!')
                    return false
                }
                if($('#description').val() =='') {
                    alertApp('Please write description!')
                    return false
                }
                if($('#privacy').val() =='') {
                    alertApp($t('common.alert_pri')+'!')
                    return false
                }
                if($('#duration-event').val() == 0) {
                    alertApp($t('common.alert_dur')+'!')
                    return false
                }
                if($('#duration-event').val() > 172800) {
                    alertApp($t('common.alert_evn_dur')+'!')
                    return false
                }
                let $imageInputs = this.$refs.vue_event_img.files
                if($imageInputs.length == 0) {
                    alertApp($t('common.alert_u_i')+'!')
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
                    alertApp($t('common.alert_wait_img')+'!')
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
                    alertApp($t('common.alert_u_event')+'!')
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