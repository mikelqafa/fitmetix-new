<template>
    <vue-clip ref="vcd" :uploaderClass="uploaderClass"
              :on-max-files = "alertMaxFile"
              :on-added-file="addedFile" :options="optionsFileUpload" :on-sending="sending">
        <template slot="clip-uploader-body" scope="props">
            <div class="image-pip-holder image-pip-holder--video">
                <div class="pip pip--video" :data-index="index" v-for="(file, index) in files" v-bind:class="{error: validate(file)}">
                    <!--<img class="thumb-image" :src="file.dataUrl">-->
                    <div style="height: 100px;width: 100px" class="md-layout md-align md-align--center-center">
                        <i class="icon icon-video" style="font-size: 40px"></i>
                    </div>
                    <a :data-id="index" class="remove-thumb" v-on:click="removeFileFrom(file, index)">
                        <i class="fa fa-times"></i>
                    </a>
                    <div class='image-loader' v-show="file.progress < 100">
                        <div class='image-loader-progress' :style="{width: file.progress+'%'}"></div>
                    </div>
                    <div class="text-center image-pip__error" v-if="isNetworkProblem(file) || file.status == 'error'">
                        <p class="error-text">Error</p>
                        <p class="file-name">{{file.name}}</p>
                    </div>
                    <div class="text-center image-pip__error" v-if="isNetworkProblem(file) || file.status == 'error'">
                        <p class="error-text">Error</p>
                        <p class="file-name">{{file.name}}</p>
                    </div>
                </div>
            </div>
        </template>
        <template slot="clip-uploader-action">
            <div id="video-upload-action-create" class="dz-message dz-message--drag">
            </div>
        </template>
    </vue-clip>
</template>
<script>
    export default {
        data: function () {
            return {
                uploaderClass: 'is-uploader-class',
                optionsFileUpload: {
                    url: base_url + 'ajax/upload-post-images',
                    paramName: 'post_images_upload',
                    acceptedFiles: {
                        extensions: ['video/*'],
                        message: 'Please upload only video file'
                    },
                    parallelUploads: 2,
                    maxFilesize:50,
                    maxFiles:1,
                    accept: function (file, done) {
                        /*if(file.size > 10*1024*1024) {
                         done('Image file is too large')
                         return
                         }*/
                        /*if ((file = this.files[0])) {
                            img = new Image();
                            img.onload = function () {
                                if(this.width < 600 || this.height < 150) {
                                    alert("Please select a larger image");
                                }
                            };
                        }*/
                        done()
                    }
                },
                files: [],
                uploader: '',
                allValidated: false,
                alertMaxFileError: false
            }
        },
        mounted() {
            this.uploader = this.$refs.vcd.uploader
        },
        methods: {
            alertMaxFile: function () {
                this.alertMaxFileError = true
                alertApp('Only 50MB of video allowed')
            },
            validate: function (f) {
                return f.status == 'error'
            },
            isNetworkProblem: function (f) {
                return f.xhrResponse.statusCode == 0
            },
            addedFile: function(file) {
                if(this.alertMaxFileError) {
                    this.alertMaxFileError = false
                    return
                }
                this.files.push(file)
            },
            addMore: function () {
                $('#video-upload-action-create').trigger('click');
            },
            sending: function(file, xhr, formData) {
                formData.append('_token', $(".create-post-form input[name=_token]").val())
            },
            removeFileFrom: function(file, index) {
                this.$refs.vc.removeFile(file)
                this.files.splice(index, 1)
            },
            reset: function (){
                let len = this.files.length -1
                for(let k=len;k>=0;k--) {
                    this.$refs.vcd.removeFile(this.files[k])
                    this.files.splice(k, 1)
                }
            }
        }
    }
</script>