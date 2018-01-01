<template>
    <div class="ft-suggestion-wrapper">
        <h4 class="text-center layout-p-t-1" style="font-size: 18px">Make each other great</h4>
        <div class="ft-suggestion md-layout md-layout--row md-layout--wrap">
            <div class="ft-suggestion__item md-layout md-layout--column" v-for="item in itemList" :key="item.id">
                <a class="ft-cp__user" :title="'@'+item.username" :href="userLink(item)" :style="{backgroundImage: 'url('+item.avatar+')'}"></a>
                <a :href="userLink(item)" class="text-center">{{ item.username }}</a>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                itemList: []
            }
        },
        methods: {
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
                        console.log(response.data)
                        let d = response.data[0].suggested_users
                        for(let i = 0;i<d.length; i++) {
                            that.itemList.push(d[i])
                        }
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            },
            userLink: function(item) {
                return base_url + item.username
            }
        },
        mounted () {
            this.getList()
        }
    }
</script>