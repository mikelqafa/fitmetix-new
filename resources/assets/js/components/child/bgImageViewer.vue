<template>
    <div v-if="!noImage" class="post-image--wrapper">
        <template v-if="isMultiple">
            <swiper :options="swiperOption" class="deal-card-slider">
                <swiper-slide :key="imageIndex" v-for="(image, imageIndex) in images">
                    <a href="javascript:;" :key="imageIndex" @click="showTheater(imageIndex)" class="item__background"
                       :style="{ backgroundImage: 'url(' + image + ')' }"></a>
                </swiper-slide>
                <div class="swiper-pagination" slot="pagination"></div>
                <div class="swiper-button-prev hidden--lt-sm" slot="button-prev"></div>
                <div class="swiper-button-next hidden--lt-sm" slot="button-next"></div>
            </swiper>
        </template>
        <template v-else="">
            <div class="ft-image-post" v-for="(image, imageIndex) in images" @click="showTheater(0)">
                <div class="ft-image-post__item" v-bind:style="{ backgroundImage: 'url(' + image +')'}">
                </div>
            </div>
        </template>
    </div>
</template>
<script>

    import { swiper, swiperSlide } from 'vue-awesome-swiper'
    export default {
        props: {
            postImg: {},
            postIndex: 0,
            postEvent: {}
        },
        data: function () {
            return {
                images: [],
                index: null,
                swiperOption: {
                    slidesPerView: 1,
                    autoplay: false,
                    paginationClickable: true,
                    spaceBetween: 0,
                    loop: false,
                    pagination: '.swiper-pagination'
                }
            }
        },
        mounted () {
            let that = this
            var url = 'http://assets.fitmetix.com/'
            if (this.postImg !== undefined) {
                let eventUrl = '/var/www/html/fitmetix/storage/uploads/events/covers/'
                $.each(this.postImg, function(key, val) {
                    let s = val.source !== undefined ? val.source : ''
                    that.images.push(that.isTypeEvent ? asset_url + 'uploads/events/covers/'+s : asset_url+'uploads/users/gallery/'+s)
                });
            }
        },
        methods: {
            showTheater: function(imageIndex) {
                if(!this.detectmob()) {
                    this.$store.commit('SET_THEATER_ITEM', {postIndex: this.postIndex, imageIndex: imageIndex})
                    $('#post-image-theater-dialog').MaterialDialog('show')
                }
            },
            detectmob: function () {
                if (
                        navigator.userAgent.match(/Android/i) ||
                        navigator.userAgent.match(/webOS/i) ||
                        navigator.userAgent.match(/iPhone/i) ||
                        navigator.userAgent.match(/iPad/i) ||
                        navigator.userAgent.match(/iPod/i) ||
                        navigator.userAgent.match(/BlackBerry/i) ||
                        navigator.userAgent.match(/Windows Phone/i)
                ) {
                    return true
                } else {
                    return false
                }
            }
        },
        computed: {
            isMultiple: function () {
                return this.images.length !== 1 && this.images.length !== 0
            },
            noImage: function () {
                return this.images.length === 0
            },
            isTypeEvent: function () {
                return this.postEvent !== undefined && this.postEvent !== ''
            }
        },
        components: {
            swiper,
            swiperSlide
        }
    }
</script>