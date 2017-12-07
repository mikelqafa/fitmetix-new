<template>
    <div  class="post-image--wrapper" v-bind:class="{ 'post-image--wrapper--slider': isMultiple }">
        <template v-if="!noImage">
            <template v-if="isMultiple">
                <swiper :options="swiperOption" class="deal-card-slider">
                    <swiper-slide :key="imageIndex" v-for="(image, imageIndex) in postImg">
                        <div :key="imageIndex" class="">
                            <img :data-src="sourceImagePath(image.source)" :src="sourceImagePath(image.source)" class="swiper-lazy img-responsive">
                        </div>
                    </swiper-slide>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                    <div class="swiper-button-next" slot="button-next"></div>
                    <div class="swiper-pagination" slot="pagination"></div>
                </swiper>
            </template>
            <template v-else="">
                <div class="img-viewer" v-for="(image, index) in postImg">
                    <img :src="sourceImagePath(image.source)" class="img-responsive">
                </div>
            </template>
        </template>
    </div>
</template>
<script>
    import { swiper, swiperSlide } from 'vue-awesome-swiper'
    export default {
        props: {
            postImg: '',
            postIndex: 0,
            postEvent: ''
        },
        data: function () {
            return {
                swiperOption: {
                    slidesPerView: 1,
                    autoplay: false,
                    paginationClickable: true,
                    spaceBetween: 0,
                    loop: false,
                    pagination: '.swiper-pagination',
                    prevButton: '.swiper-button-prev',
                    preloadImages: false,
                    lazyLoading: true,
                    nextButton: '.swiper-button-next'
                }
            }
        },
        mounted () {},
        methods: {
            sourceImagePath: function (s) {
                let url = 'http://assets.fitmetix.com/'
                return this.isTypeEvent ? asset_url + 'uploads/events/covers/'+s : asset_url+'uploads/users/gallery/'+s
            }
        },
        computed: {
            isMultiple: function () {
                return this.postImg !== undefined ?  this.postImg.length > 1 : false
            },
            noImage: function () {
                return (this.postImg !== undefined) ?  this.postImg.length == 0 : true
            },
            isTypeEvent: function () {
                return this.postEvent !== undefined && this.postEvent !== ''
            }
        },
        components: {
            swiper,
            swiperSlide
        },
        watch: {
            postImg: function (val) {}
        }
    }
</script>