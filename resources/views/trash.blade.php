<template>
    <div class="pos-rel create-event-form">
        <fieldset class="form-group required">
            <div class="event_images_upload--label md-layout md-align md-align--center-center" id="event_images_upload">
                <span style="z-index: 2" class="md-layout md-layout--row md-align md-align--center-center" title="{{ trans('common.upload_photos') }}">
                    <div id="imageUpload" class="icon icon-photo center-block" style="font-size: 56px;width: 56px;"></div>
                </span>
            </div>
        </fieldset>
        <span id="event_images_upload--image"><h1>Hello World</h1></span>
    </div>
</template>

<script>
    // import appImageUploader from './child/createEventImageUploader' <app-image-uploader ref="vue_event_img"></app-image-uploader>

    export default {
        data: function () {
            return {
                userImage: ''
            }
        },
        mounted () {
            let that = this
            $(document).on('click','#imageUpload',function(e){
                alert()
                //e.preventDefault();
                //$('.post-images-upload').trigger('click');
                //$('#upload-action-create').trigger('click');
            });
            $(document).on('click','#videoUploadFile',function(e){
                e.preventDefault();
                $('#video-upload-action-create').trigger('click');
            });
        },
        methods: {
            uploadPostImage: function (key, files, loaderDiv) {
                let that = this
                $('.create-event-form').append('<input type="hidden" data-key="'+ key +'"  name="upload-image-name[]" value="">')
                console.log(key)
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $(".create-event-form input[name=_token]").val()
                    }
                });
                var data = new FormData();
                data.append('event_images_upload', files[key], files[key].name);
                $.ajax({
                    url : base_url + 'ajax/upload-event-images',
                    type: "post",
                    data: data,
                    cache : false,
                    processData: false,
                    contentType: false,
                    xhr: function(){
                        //upload Progress
                        var xhr = $.ajaxSettings.xhr();
                        if (xhr.upload) {
                            xhr.upload.addEventListener('progress', function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                    percent = Math.ceil(position / total * 100);
                                }
                                loaderDiv.css('width',percent+'%');
                            }, true);
                        }
                        return xhr;
                    },
                    mimeType:"multipart/form-data"
                }).done(function(e){
                    let data = JSON.parse(e)
                    loaderDiv.parent().remove()
                    $('input[name="event_images_upload[]"][data-key="'+key+'"]').val(data[0])
                })
            },
            resetCreatePost: function () {
                let create_post_form = $('.create-event-form')
                create_post_form.find("input[type=text], textarea, input[type=file]").val("")
                create_post_form.find('#post-image-holder').empty()
                create_post_form.find('.post-images-selected').hide()
                this.$refs.vue_event_img.reset()
            }
        }
    }
</script>