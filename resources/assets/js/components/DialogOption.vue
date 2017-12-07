<template>
    <div>
        <edit-post-option :index="optionMenuPostItem.postIndex" v-if="(postItem !== '') && initEdit"  :post-item="postItem"></edit-post-option>
        <div class="md-dialog md-dialog--maintain-width md-dialog--post-option md-dialog--full-screen" id="post-option-dialog">
            <div class="md-dialog__wrapper">
                <div class="md-dialog__shadow"></div>
                <div class="md-dialog__surface" style="position: relative">
                    <div class="md-dialog__body">
                        <div class="ft-dialog-option" v-bind:class="{'is-loading': isLoading}">
                            <template v-if="authUser">
                                <a href="javascript:;" data-value="post" class="btn ft-dialog-option__item" @click="editPost">
                                    Edit Post
                                </a>
                                <a href="javascript:;" class="btn ft-dialog-option__item" @click="deletePost">
                                    Delete Post
                                </a>
                                <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item" @click="shareTo('facebook')">
                                    Share
                                </a>
                            </template>
                            <template v-else="">
                                <a href="javascript:;" class="btn ft-dialog-option__item" @click="reportPost">
                                    Report Post
                                </a>
                                <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item" @click="shareTo('facebook')">
                                    Share
                                </a>
                                <a href="javascript:;" class="btn ft-dialog-option__item" @click="savePost">
                                    Save
                                </a>
                            </template>
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
    </div>
</template>
<style>
    @media (max-width: 640px) {
        .md-dialog--maintain-width .md-dialog__surface {
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }
    }

    .md-dialog--post-option.md-dialog--open {
        z-index: 26;
    }
    .md-dialog--post-option .md-dialog__body {
        padding: 0;
        margin-top: 0;
    }
    .md-dialog--post-option .md-dialog__wrapper {
        justify-content: center;
    }
    .ft-dialog-option__item:active {
        box-shadow: none !important;
    }
    .ft-dialog-option__item {
        max-width: 510px;
    }
    .absolute-loader {
        position: absolute;
        top:0;
        left: 0;
        width: 100%;
        min-height: 100%;
        display: flex;
        justify-content: center;
        background-color: rgba(0,0,0,.2);
        align-items: center;
    }
    .ft-dialog-option.is-loading {
        cursor: wait;
        pointer-events: none;
    }
    .absolute-loader .ft-loading {
        background-color: transparent;
    }
</style>
<script>
    import editPostOption from './child/editPost'
    import { mapGetters } from 'vuex'
    export default {
        data: function () {
            return {
                isLoading: false,
                initEdit: false
            }
        },
        methods: {
            emitAction: function(e) {
                console.log(e.target.getAttribute('data-value'))
            },
            reportPost: function() {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.isLoading = true
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/report-post',
                    data: {
                        _token: _token,
                        post_id: that.postItem.id
                    }
                }).then( function (response) {
                    console.log(response)
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
            deletePost: function () {
                if(confirm('Do you really want to delete this post?')) {
                    let that = this
                    let _token = $("meta[name=_token]").attr('content')
                    this.isLoading = true
                    $('#post-image-theater-dialog').MaterialDialog('hide')
                    axios({
                        method: 'post',
                        responseType: 'json',
                        url: base_url + 'ajax/post-delete',
                        data: {
                            _token: _token,
                            post_id: that.postItem.id
                        }
                    }).then( function (response) {
                        if (response.status ==  200) {
                            that.$store.commit('REMOVE_POST_ITEM_LIST', that.optionMenuPostItem.postIndex)
                            $('#post-option-dialog').MaterialDialog('hide')
                            materialSnackBar({messageText: response.data.message, autoClose: true })
                        }
                        that.isLoading = false
                    }).catch(function(error) {
                        $('#post-option-dialog').MaterialDialog('hide')
                        materialSnackBar({messageText: error, autoClose: true })
                        that.isLoading = false
                    })
                }
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
            editPost: function () {
                this.initEdit = true
                setTimeout(function() {
                    $('#post-edit-option-dialog').MaterialDialog('show');
                },300)
            },
            shareTo: function (network) {
                let that = this
                switch (network) {
                    case 'facebook':
                        window.FB.ui({
                            method: 'feed',
                            name: 'shared the post',
                            link: 'http://www.fitmetix.com'+'post/'+that.postItem.id,
                            picture: that.postItem.postImg !== undefined ? '' : that.postItem.postImg,
                            description: that.postItem.description
                        }, function (response) {
                            console.log(response)
                        })
                        break
                    case 'google':
                        window.open('https://plus.google.com/share?url=' + this.metaTag.url + '&text=' + this.metaTag.title + '&via=freekaadeal', '', 'menubar=no, toolbar=no,resizeable=yes,scrollbars=yes,height=300,width=600')
                        break
                    case 'twitter':
                        window.open('https://twitter.com/share?url=' + this.metaTag.url + '&text=' + this.metaTag.title + '&via=freekaadeal', '', 'menubar=no, toolbar=no,resizeable=yes,scrollbars=yes,height=300,width=600')
                        break
                }
            }
        },
        components: {
            'edit-post-option': editPostOption
        },
        mounted () {
            let that = this
            let dialog = $('#post-option-dialog').MaterialDialog({show:false});
            dialog.on('ca.dialog.hidden', function () {
                this.initEdit = false
                that.$store.commit('SET_OPTIONS_MENU_ITEM', {postIndex: undefined})
            });
        },
        computed: {
            ...mapGetters({
                    optionMenuPostItem: 'optionMenuPostItem'
            }),
            postItem: function () {
                return this.optionMenuPostItem.postIndex !== undefined ? this.$store.state.postItemList[this.optionMenuPostItem.postIndex] : {}
            },
            authUser: function () {
                return this.postItem.user_id !== undefined ? this.postItem.user_id == user_id : false
            }
        }
    }
</script>
