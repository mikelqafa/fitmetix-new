<template>
    <div class="md-dialog md-dialog--who-likes md-dialog--full-screen" id="post-who-likes-dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface">
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
                            <div class="md-list__item has-divider" v-for="item in whoLikesItem">
                                <a data-theme="m"  href="//localhost:3008/fitmetix/public/Uppal" :title="'@' + item.username"
                                   class="md-list__item-icon user-avatar" style="background-image: url(&quot;http://localhost/fitmetix/public/user/avatar/2017-10-22-14-07-04athletebookprofilepage.png&quot;);"></a>
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
</template>
<style>
    .md-dialog--who-likes .md-dialog__body {
        min-height: 216px;
        padding-right: 0;
        padding-left: 0;
        padding-top: 0;
        margin-top: 0;
    }
    .md-dialog--who-likes.md-dialog--open {
        z-index: 28;
    }
    .md-list--likes {
        background-color: transparent !important;
    }
    .md-dialog--who-likes .md-dialog__surface {
        width: 440px;
    }

    .md-dialog--who-likes .loading-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 192px;
        margin-top: -24px;
    }

    .md-dialog--who-likes .md-dialog__header {
        padding-top: 8px;
        padding-bottom: 8px;
    }
</style>
<script>
    import { mapGetters } from 'vuex'

    export default {
        data: function () {
            return {}
        },
        methods: {},
        mounted () {
            let that = this
            let dialog = $('#post-who-likes-dialog').MaterialDialog({show: false});
            dialog.on('ca.dialog.hidden', function () {
                that.$store.commit('SET_WHO_LIKES_ITEM', {postIndex: undefined})
            });
        },
        computed: {
                ...mapGetters({
                    postWhoLikes: 'postWhoLikes'
                }),
            whoLikesItem: function () {
                return this.postWhoLikes.postIndex !== undefined ?

                        (this.$store.state.postItemList[this.postWhoLikes.postIndex].whoLikes !== undefined ? this.$store.state.postItemList[this.postWhoLikes.postIndex].whoLikes.itemList : [] )

                        : []

            },
            loading: function () {
                return !this.whoLikesItem.length
            }
        }
    }
</script>