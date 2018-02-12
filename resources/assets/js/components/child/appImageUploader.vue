<template>
    <vue-clip ref="vc" :uploaderClass="uploaderClass"
              :on-max-files = "alertMaxFile" :on-drag-enter="dragging"
              :on-added-file="addedFile" :on-queue-complete="queueCompleted"
              :options="optionsFileUpload" :on-complete="complete"
              :on-sending="sending" :on-total-progress="totalProgress">
        <template slot="clip-uploader-body" scope="props">
            <div class="image-pip-holder">
                <div class="pip" :data-index="index" v-for="(file, index) in files" v-bind:class="{error: validate(file)}">
                    <img class="thumb-image" :src="file.dataUrl">
                    <a :data-id="index" class="remove-thumb" v-on:click="removeFileFrom(file, index)">
                        <i class="fa fa-times"></i>
                    </a>
                    <div class='image-loader' v-show="file.progress < 100">
                        <div class='image-loader-progress' :style="{width: file.progress+'%'}"></div>
                    </div>
                    <div class="text-center image-pip__error" v-if="isNetworkProblem(file) || file.status == 'error'">
                        <p class="error-text">{{$t('common.error') }}</p>
                        <p class="file-name">{{file.name}}</p>
                    </div>
                    <div class="text-center image-pip__error" v-if="isNetworkProblem(file) || file.status == 'error'">
                        <p class="error-text">{{$t('common.error') }}</p>
                        <p class="file-name">{{file.name}}</p>
                    </div>
                </div>
                <div v-show="files.length && files.length < 5" @click="addMore" class="upload-action upload-action-add" title="choose a file to upload">
                </div>
            </div>
        </template>
        <template slot="clip-uploader-action">
            <div id="upload-action-create" class="dz-message dz-message--drag">
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
                        extensions: ['image/*'],
                        message: 'Please upload only image file'
                    },
                    parallelUploads: 2,
                    maxFilesize:10,
                    maxFiles:5,
                    resizeWidth: 800,
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
            this.uploader = this.$refs.vc.uploader
        },
        methods: {
            alertMaxFile: function () {
                this.alertMaxFileError = true
                alertApp('Max file exceeded')
            },
            validate: function (f) {
                return f.status == 'error'
            },
            retryUpload: function (f) {
                //dropzone.removeAllFiles();
                let dropzoneFilesCopy = this.uploader._uploader.files.slice(0,1)
                let that = this
                $.each(dropzoneFilesCopy, function(file) {
                    that.uploader.addFile(file)
                });
                console.log('retrying...')
            },
            isNetworkProblem: function (f) {
                return f.xhrResponse.statusCode == 0
            },
            complete: function(file, status, xhr) {
                let index = -1
                if( file._file.width < 600 || file._file.height < 150) {
                    alertApp('Please add a larger image')
                    console.log(file)
                    for(let i =0; i < this.files.length; i++) {
                        if(this.files[i].name == file.name) {
                            index = i
                            break
                        }
                    }
                }
                if(index>-1) {
                    this.files[index].status = 'error'
                    this.files[index].errorMessage = 'File too small to upload'
                }
            },
            addedFile: function(file) {
                if(this.alertMaxFileError) {
                    this.alertMaxFileError = false
                    return
                }
                this.files.push(file)
            },
            addMore: function () {
                $('#upload-action-create').trigger('click');
            },
            sending: function(file, xhr, formData) {
                formData.append('_token', $(".create-post-form input[name=_token]").val())
            },
            totalProgress: function(progress, totalBytes, bytesSent) {
                //console.log(progress, totalBytes, bytesSent)
            },
            queueCompleted: function() {
                // console.log('queue Completed')
            },
            onRemovedFile: function () {
                console.log('file removed')
            },
            dragging: function () {
              console.log('hola')
            },
            removeFileFrom: function(file, index) {
                this.$refs.vc.removeFile(file)
                this.files.splice(index, 1)
            },
            reset: function (){
                let len = this.files.length -1
                for(let k=len;k>=0;k--) {
                    this.$refs.vc.removeFile(this.files[k])
                    this.files.splice(k, 1)
                }
            }
        }
    }
</script>