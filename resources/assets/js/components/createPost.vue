<template>
    <div class="pos-rel">
        <div class="ft-cp">
            <div class="ft-cp__user" :style="{ backgroundImage: 'url(\'' + userImage + '\')' }"></div>
            <div class="ft-cp__presentation">
                <div class="write-post">
                    <div class="write-post__placeholder" v-if="hasNotContent">{{placeholder}}</div>
                    <mention-at :members="members" v-on:at="fetchUser" name-key="name">
                        <template slot="item" scope="s">
                            <img :src="s.item.avatar">
                            <span v-text="s.item.name"></span>
                        </template>
                        <medium-editor id="create-post-vue" :text='backContent' :options='options'
                                       class="write-post__text"
                                       v-on:edit='processEditOperation'
                                       style="outline: none; user-select: text; white-space: pre-wrap; word-wrap: break-word;"
                                       custom-tag='div'>
                        </medium-editor>
                    </mention-at>
                    <div class="replace-with"></div>
                </div>
            </div>
        </div>
        <app-image-uploader ref="vue_img"></app-image-uploader>
        <div v-if="isPosting" class="ft-loading ft-loading--abs">
            <span class="ft-loading__dot"></span>
            <span class="ft-loading__dot"></span>
            <span class="ft-loading__dot"></span>
        </div>
    </div>
</template>

<script>
    import appImageUploader from './child/appImageUploader'
    import editor from 'vue2-medium-editor'
    import At from 'vue-at'

    export default {
        data: function () {
            return {
                userImage: '',
                placeholder: '',
                members: [],
                content: '',
                backContent: '',
                viewContent: '',
                imageFile: [],
                options: { disableReturn: false },
                optionsFileUpload: {
                    url: '/upload',
                    paramName: 'file'
                },
                isPosting: false
            }
        },
        mounted () {
            this.userImage = $('#user-image').val()
            this.placeholder = $('#post-placeholder').val()
            let username = current_username
            if($('#timeline_username').length) {
                username =  $('#timeline_username').val()
            }
            if(username !== current_username) {
                this.placeholder = 'Write something on ' + username
            }
            let that = this
            $(document).on('click', '.smiley-post', function(e){
                e.preventDefault();
                let textbox = $("#create-post-vue");
                textbox.val(textbox.val() + ' ' + $(this).data('smiley-id'));
                that.backContent += ' ' + $(this).data('smiley-id')

                setTimeout(function (){
                    emojify.run()
                }, 300)
            });

            $('#emoticons').on('click',function(e){
                e.preventDefault();
                var emoticonButton = $(this);
                if(!emoticonButton.hasClass('loaded-emoji'))
                {
                    $.get( SP_source() + 'ajax/load-emoji')
                            .done(function( data ) {
                                $('.emoticons-wrapper').html(data.data);
                                emojify.run()
                                console.log('hello')
                                emoticonButton.addClass('loaded-emoji')
                            });
                }

                $('.emoticons-wrapper').fadeToggle();
            });

            $('.create-post-form').on('submit', function(e) {
                e.preventDefault()
                that.createNewPost()
            });

            $(document).on('click','#imageUpload',function(e){
                e.preventDefault();
                //$('.post-images-upload').trigger('click');
                $('#upload-action-create').trigger('click');
            });
            $(document).on('click','#videoUploadFile',function(e){
                e.preventDefault();
                $('#video-upload-action-create').trigger('click');
            });
        },
        computed: {
            hasNotContent () {
                return this.backContent === ''
            },
            viewContentHtml: function() {
                let is_xhtml = false
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (this.viewContent + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            }
        },
        components: {
            'mention-at':At,
            'app-image-uploader': appImageUploader,
            'medium-editor': editor
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
            replaceImgEmoji: function () {
                $('.replace-with').html('')
                $('.replace-with').html($('#create-post-vue').html())
                $.each($('.replace-with img'), function(){
                    $(this).replaceWith('<div> '+$(this).attr('title')+' </div>');
                });
            },
            nl2br: function(html) {
                let is_xhtml = false
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (html + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            },
            createNewPost: function () {
                let _token = $(".create-post-form input[name=_token]").val()
                let youtubeText = $(".create-post-form input[name=youtubeText]").val()
                let location = $(".create-post-form input[name=location]").val()
                let timeline_id = $(".create-post-form input[name=timeline_id]").val()
                let youtube_title = $(".create-post-form input[name=youtube_title]").val()
                let youtube_video_id = $(".create-post-form input[name=youtube_video_id]").val()
                let soundcloud_id = $(".create-post-form input[name=soundcloud_id]").val()
                let user_tags = $(".create-post-form input[name=user_tags]").val()
                let soundcloud_title = $(".create-post-form input[name=soundcloud_title]").val()
                this.replaceImgEmoji()
                let description = this.nl2br($('.replace-with').html())
                let $imageInputs = this.$refs.vue_img.files
                if($imageInputs.length == 0 && youtubeText == '' && location =='' && youtube_title == '' && youtube_video_id == ''
                        && soundcloud_id == '' && user_tags == '' && soundcloud_title == '' &&  description == '' ) {
                    alertApp('Your post cannot be empty!')
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
                    return;
                }
                let invalid = false
                for(let i=0; i<$imageInputs.length; i++) {
                    if($imageInputs[i].status == 'error') {
                        invalid = true
                        break
                    }
                }
                if(invalid) {
                    alertApp('Unable to post. Remove invalid images or try again!')
                    return;
                }
                let that = this
                // set loading state
                let create_post_form = $('.create-post-form')
                let post_images_upload = []
                let response
                let fileUploaded = true
                let reason = ''
                for(let j=0; j<$imageInputs.length;j++) {
                    response = $imageInputs[j].xhrResponse.response
                    response = JSON.parse(response)
                    if (response.status == 200) {
                        post_images_upload.push(response[0])
                    }
                }
                let create_post_button = create_post_form.find('.btn-submit')
                create_post_button.attr('disabled', true).append(' <i class="fa fa-spinner fa-pulse "></i>');
                create_post_form.find('.post-message').fadeOut('fast');
                let data = {
                    _token: _token,
                    description: description,
                    youtubeText: youtubeText,
                    post_images_upload: post_images_upload,
                    location: location,
                    timeline_id: timeline_id,
                    youtube_title: youtube_title,
                    youtube_video_id: youtube_video_id,
                    soundcloud_id: soundcloud_id,
                    user_tags: user_tags,
                    soundcloud_title: soundcloud_title
                };
                this.isPosting = true
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/create-post',
                    data: data
                }).then(function (response) {
                    if (response.status == 200) {
                        window.timeLine.$options.components["app-post"].methods.fetchNewOnePost(response.data.data.id)
                        materialSnackBar({messageText: 'Your post has been successfully published', autoClose: true })
                        that.resetCreatePost()
                    }
                }).catch(function (error) {
                    that.resetCreatePost()
                    materialSnackBar({messageText: 'Error occurred! Please try again.', autoClose: true })
                    console.log(error)
                })
            },
            uploadPostImage: function (key, files, loaderDiv) {
                let that = this
                $('.create-post-form').append('<input type="hidden" data-key="'+ key +'"  name="upload-image-name[]" value="">')
                console.log(key)
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $(".create-post-form input[name=_token]").val()
                    }
                });
                var data = new FormData();
                data.append('post_images_upload', files[key], files[key].name);
                $.ajax({
                    url : base_url + 'ajax/upload-post-images',
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
                }).done(function(e){ //
                    //TODO
                    let data = JSON.parse(e)
                    console.log(data)
                    loaderDiv.parent().remove()
                    $('input[name="upload-image-name[]"][data-key="'+key+'"]').val(data[0])
                    /*var string = '';
                     progress.css('width','100%');
                     btnBrowse.attr('disabled',true);
                     fileInput.attr('disabled',true);
                     submitBtn.prop('disabled',false);
                     $('[data-toggle="upload"]').prop('disabled',false);
                     fileInput.val('');*/
                }).always(function(e){
                }).fail(function(e){
                    /*$('#uploadBtn').prop('disabled', false);
                     //$('#mdl-progress-wrp').addClass('hidden');
                     Pmh.showNotificationSnackBar({message:'Upload failed. Please try again!',timeout:3000});
                     progress.css('width','0%');*/
                })
            },
            resetCreatePost: function () {
                $('.no-posts').hide();
                // Resetting the create post form after successfull message
                $('.video-addon').hide()
                $('.music-addon').hide()
                $('.emoticons-wrapper').hide()
                $('.user-tags-addon').hide()
                $('.user-tags-added').hide()
                $(".user-results").hide()
                let create_post_form = $('.create-post-form')
                create_post_form.find("input[type=text], textarea, input[type=file]").val("")
                create_post_form.find('.youtube-iframe').empty()
                create_post_form.find('#post-image-holder').empty()
                create_post_form.find('.post-images-selected').hide()
                create_post_form.find('#post-video-holder').empty()
                create_post_form.find('.post-videos-selected').hide()
                $('[name="youtube_video_id"]').val('')
                $('[name="youtube_title"]').val('')
                $('[name="soundcloud_id"]').val('')
                $('[name="soundcloud_title"]').val('')
                $('[name="user_tags[]"]').val('')
                $('.user-tags').val('')
                $('.user-tag-names').empty('')
                $('#create-post-vue').html('')
                let create_post_button = $('.create-post-form .btn-submit')
                create_post_button.html(create_post_button.text())
                create_post_button.attr('disabled', false)
                this.$refs.vue_img.reset()
                this.isPosting = false
                this.backContent = ''
            },
            processEditOperation: function (operation) {
                this.backContent = operation.api.origElements.innerHTML
            }
        }
    }
</script>