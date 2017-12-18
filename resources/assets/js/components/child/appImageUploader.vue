<template>
    <vue-clip ref="vc" :uploaderClass="uploaderClass"
              :on-max-files = "alertMaxFile"
              :on-added-file="addedFile" :on-queue-complete="queueCompleted"
              :options="optionsFileUpload" :on-complete="complete"
              :on-sending="sending" :on-total-progress="totalProgress">
        <template slot="clip-uploader-action" scope="params">
            <div v-bind:class="{'is-dragging': params.dragging}" id="upload-action-create" class="upload-action">
            </div>
        </template>
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
                        <p class="error-text">Error</p>
                        <p class="file-name">{{file.name}}</p>
                    </div>
                </div>
                <div v-if="files.length && files.length < 5" @click="addMore" class="upload-action-add" title="choose a file to upload">
                </div>
            </div>
        </template>
        <template slot="clip-preview-template">
            hello
        </template>
    </vue-clip>
</template>
<style>
    .upload-action-add{
        background-image: url(https://www.facebook.com/rsrc.php/v3/yr/r/EWLe5fNY_Iz.png);
        background-position: 50%;
        background-repeat: no-repeat;
        background-size: 20px;
        border: 2px dashed #dddfe2;
        border-radius: 2px;
        box-sizing: border-box;
        display: inline-block;
        height: 100px;
        margin-right: 4px;
        position: relative;
        width: 100px;
        margin-left: 4px;
        cursor: pointer;
    }
</style>
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
                        console.log('hola', file)
                        /*if(file.size > 10*1024*1024) {
                            done('Image file is too large')
                            return
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
            window.meow = this.uploader
        },
        methods: {
            alertMaxFile: function () {
                this.alertMaxFileError = true
                alertApp('Max error')
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
                // Adding server id to be used for deleting
                // the file.
                // console.log(xhr)
                // file.addAttribute('id', xhr.response.id)
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
            removeFileFrom: function(file, index) {
                this.$refs.vc.removeFile(file)
                this.files.splice(index, 1)
            },
            reset: function (){
                for(let k=0;k<this.files.length;k++) {
                    this.removeFileFrom(this.files[k], k)
                }
            }
        }
    }
</script>