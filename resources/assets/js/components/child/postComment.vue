<template>
    <div class="panel-footer socialite" >
        <ul class="list-inline footer-list pos-rel">
            <li class="hidden">
                <a href="#" class="like-post like-87" data-post-id="87">
                    <i class="icon icon-like"></i>
                </a>
            </li>
            <li>
                <a href="#" class="like-post unlike unlike-87" data-post-id="87">
                    <i class="icon icon-liked unlike"></i>
                </a>
            </li>
            <li>
                <a href="#" class="show-comments">
                    <i class="icon icon-comment"></i>
                </a>
            </li>
            <li class="text-center full-center ">
                <a href="#" class="show-users-modal" data-html="true" data-heading="Likes" data-users="7" data-original-title="Mikel">
                        <span class="count-circle">
                            <i class="icon icon-like"></i>
                        </span>
                    <span class="hidden-sm hidden-xs">Likes</span>
                </a>
            </li>
            <li>
                <a href="#" class="show-all-comments">
                        <span class="count-circle">
                            <i class="icon icon-comment"></i>
                        </span>1
                    <span class="hidden-sm hidden-xs">comments</span>
                </a>
            </li>
            <li class="pull-right">
                <a href="//localhost:3004/fitmetix/public/post/87">
                    <i class="icon icon-share"></i>
                </a>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: { },
        data: function () {
            return {}
        },
        computed: {
            userAvatar () {
                return 'hello'
            }
        },
        methods: {
            getDefaultData: function () {
                let that = this
                let username = ''
                let paginate = 50
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'get-posts',
                    data: {
                        username: current_username,
                        paginate: paginate,
                        _token: _token
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        let posts = response.data[0].posts;
                        $.each(posts, function(key, val) {
                            that.itemList.push(val);
                            console.log(val)
                        });
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            }
        },
        mounted () {
            let that = this
            setTimeout(function () {
                that.getDefaultData()
            }, 1000)
        }
    }
</script>