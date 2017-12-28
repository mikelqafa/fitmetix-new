<template>
    <div class="md-dialog md-dialog--who-likes" id="post-who-participate-dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface">
                <div>
                    <header class="md-dialog__header panel-post">
                        <div class="md-layout-spacer"></div>
                        <a href="javascript:;" style="margin-right: 15px"
                           class="md-button md-button--icon md-dialog__header-action-dismissive" data-action="dismissive">
                            <i class="icon icon-close"></i>
                        </a>
                    </header>
                    <div class="md-dialog__body md-dialog__body--scrollable">
                        <template v-if="loading">
                            <div class="loading-wrapper">
                                <div class="ft-loading" style="background-color: transparent">
                                    <span class="ft-loading__dot"></span>
                                    <span class="ft-loading__dot"></span>
                                    <span class="ft-loading__dot"></span>
                                </div>
                            </div>
                        </template>
                        <template v-else="">
                            <div class="md-list md-list--likes md-list--dense">
                                <div class="md-list__item has-divider" v-for="item in participantList">
                                    <a :href="userLink(item)" class="md-list__item-icon user-avatar"  :title="'@' + item.username" v-bind:style="{ backgroundImage: 'url(' + userAvatar(item) +')'}">
                                    </a>
                                    <div class="md-list__item-content">
                                        <div class="md-list__item-primary">
                                            <a href="http://localhost/fitmetix/public/mikele"
                                               :title="'@' + item.username"
                                               class="user-name user ft-user-name">
                                                {{item.name}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex'

    export default {
        data: function () {
            return {
                participantList: [],
                defaultImage: 'default.png'
            }
        },
        methods: {
            userLink (item) {
                return base_url + item.username
            },
            userAvatar (item) {
                return item.avatar_url.length ? asset_url + 'uploads/users/avatars/' + item.avatar_url[0].source : base_url + 'images/' + this.defaultImage

            },
            getList: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.participantList = []
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/get-registered-users-for-event',
                    data: {
                        event_id: that.eventWho.eventId,
                        user_id: user_id,
                        _token: _token
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        console.log(response)
                        /*for(let i = 0;i<response.data.length; i++) {
                            that.participantList.push(response.data[i])
                        }*/
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            }
        },
        mounted () {
            let that = this
            let dialog = $('#post-who-participate-dialog').MaterialDialog({show: false});
            dialog.on('ca.dialog.hidden', function () {
                that.participantList = []
            });
            dialog.on('ca.dialog.show', function () {
                that.getList()
            });
        },
        computed: {
                ...mapGetters({
                    eventWho: 'eventWho'
                }),
            loading: function () {
                return !this.participantList.length
            }
        }
    }
</script>