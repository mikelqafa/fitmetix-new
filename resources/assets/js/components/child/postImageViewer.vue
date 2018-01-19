<template>
    <div v-if="hasItem" class="post-image--wrapper">
        <template v-if="isMultiple">
            <div class="pos-rel">
                <swiper :options="swiperOption" class="deal-card-slider">
                    <swiper-slide :key="imageIndex" v-for="(image, imageIndex) in postImg">
                        <a href="javascript:;" :key="imageIndex" @click="showTheater(imageIndex)"
                           class="item__background" :style="{ backgroundImage: 'url(' + sourceImagePath(image.source) + ')' }">
                            <img :data-src="sourceImagePath(image.source)" :src="sourceImagePath(image.source)" class="swiper-lazy img-responsive">
                        </a>
                    </swiper-slide>
                    <div class="swiper-pagination" slot="pagination"></div>
                    <div class="swiper-button-prev hidden" slot="button-prev"></div>
                    <div class="swiper-button-next hidden" slot="button-next"></div>
                </swiper>
                <div class="swiper-multiple-icon">
                    <img :src="multiple" class="svg-object" type="image/svg+xml" style="height: 24px">
                </div>
            </div>
        </template>
        <template v-else="">
            <div class="image-responsive item__background--post img-viewer raven" v-for="(image, imageIndex) in postImg" @click="showTheater(0)">
                <img :src="sourceImagePath(image.source)" onload="raven(this)" class="img-responsive">
                <div class="raven-wrapper md-layout md-align md-align--start-center">
                    <div class="raven-inner-wrapper">
                        <img :src="getThumbImage(sourceImagePath(image.source))" class="img--base img-responsive">
                    </div>
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
                index: null,
                swiperOption: {
                    slidesPerView: 1,
                    autoplay: false,
                    paginationClickable: true,
                    spaceBetween: 0,
                    loop: false,
                    autoHeight: true,
                    pagination: '.swiper-pagination'
                },
                disableShowcase: false,
                multiple: ''
            }
        },
        mounted () {
            this.multiple = base_url+'images/multiple.svg'
        },
        methods: {
            sourceImagePath: function (s) {
                return this.isTypeEvent ? asset_url + 'uploads/events/covers/'+s : asset_url+'uploads/users/gallery/'+s
            },
            getThumbImage: function (url) {
                return getThumbImage(url,50)
            },
            showTheater: function(imageIndex) {
                if(this.disableShowcase) {
                    return
                }
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
                return this.postImg.length !== 1 && this.postImg.length !== 0
            },
            hasItem: function () {
                return this.postImg !== undefined ? this.postImg.length  : false
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