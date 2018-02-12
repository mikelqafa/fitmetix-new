<template>
    <div class="ft-suggestion-wrapper hidden-sm hidden-xs">
        <template v-if="!hasNoUser">
            <h4 class="text-center layout-p-t-1" style="font-size: 18px">{{$t('common.each_other_grt') }}</h4>
            <div class="ft-suggestion md-layout md-layout--row md-layout--wrap">
                <div class="ft-suggestion__item md-layout md-layout--column" v-for="item in itemList" :key="item.id">
                    <a class="ft-cp__user" :title="'@'+item.username" :href="userLink(item)" :style="{backgroundImage: 'url('+userAvatar(item)+')'}"></a>
                    <a :href="userLink(item)" class="text-center">{{ item.username }}</a>
                </div>
            </div>
        </template>
        <template v-else="">
            <div class="text-center ft-loading ft-loading--transparent" style="">
                <span>{{$t('common.user_n_f') }}</span>
            </div>
        </template>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                itemList: [],
                hasNoUser: false
            }
        },
        methods: {
            checkDesktop: function () {
                if( navigator.userAgent.match(/Android/i)
                        || navigator.userAgent.match(/webOS/i)
                        || navigator.userAgent.match(/iPhone/i)
                        || navigator.userAgent.match(/iPad/i)
                        || navigator.userAgent.match(/iPod/i)
                        || navigator.userAgent.match(/BlackBerry/i)
                        || navigator.userAgent.match(/Windows Phone/i)
                ){
                    return false;
                }
                else {
                    return true;
                }
            },
            getList: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.itemList = []
                axios({
                    method: 'get',
                    responseType: 'json',
                    url: base_url + 'ajax/get-suggested-users',
                    data: {
                        _token: _token
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        let d = response.data[0].suggested_users
                        let i = 0
                        for(i = 0;i<d.length; i++) {
                            that.itemList.push(d[i])
                        }
                        if(!i) {
                            that.hasNoUser = true
                        }
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            getThumbImage: function (url) {
                return getThumbImage(url)
            },
            userAvatar (item) {
                return getThumbImage(
                        item.avatar_url.length ? asset_url + 'uploads/users/avatars/' + item.avatar_url[0].source : base_url + 'images/default.png'
                )
            },
            userLink: function(item) {
                return base_url + item.username
            }
        },
        mounted () {
            this.isDesktop = false
            if(this.checkDesktop()) {
                this.isDesktop = true
                this.getList()
            }
        }
    }
</script>