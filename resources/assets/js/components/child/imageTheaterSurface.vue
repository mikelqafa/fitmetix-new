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
<style>
    @media screen and (min-width: 600px) {
        .post-image--wrapper--slider {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100vw;
        }
        .post-image--wrapper--slider .swiper-container{
            width: 100%;
        }
    }
    .item__background--post{
        width: 100%;
        background-size: cover;
        background-position: center;
    }
    .img-responsive{
        max-width: 100%;
        display: block;
        margin: 0 auto;
    }
    .img-viewer{
        cursor: pointer;
    }
    .fkd-slider-wrapper {
        min-height: 300px;
        position: relative;
    }
    .item__background--home-slider{
        max-height: 300px;
    }
    @media screen and (max-width: 599px){
        .md-dialog--full-screen .md-dialog__surface{
            width: 100%;
        }
        .loading-state-wrapper{
            height: auto;
            min-height: 200px;
            width: 100%;
        }
        .loading-state-relative{
            width: 100%;
        }
        .fkd-slider-wrapper{
            min-height: 180px;
        }
        .item__background{
            background-size: cover;
        }
        .swiper-slide .item__background {
            max-height: 200px;
            min-height: 180px;
        }
        .panel-post .panel-body .post-image--wrapper{
            margin-left:-15px;
            margin-right:-15px;
        }
    }

    .component-loading-state {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
    }

    .item__background {
        height: 360px;
        width: 100vw;
        position: relative;
        background-size: auto 100%;
        background-position: center;
        display: block;
    }
    .swiper-pagination-bullet-active{
        background-color: #1E7C82;
    }
    .md-dialog__body .post-image--wrapper{
        width: 100%;
    }
    .swiper-button-prev, .swiper-container-rtl .swiper-button-next{
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 27 44'><path d='M0,22L22,0l2.1,2.1L4.2,22l19.9,19.9L22,44L0,22L0,22L0,22z' fill='#81C784'/></svg>");
        transform: scale(.7);
    }
    .swiper-button-next, .swiper-container-rtl .swiper-button-prev{
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 27 44'><path d='M27,22L27,22L5,44l-2.1-2.1L22.8,22L2.9,2.1L5,0L27,22L27,22z' fill='#81C784'/></svg>");
        transform: scale(.7);
    }
</style>
<script>
    import { swiper, swiperSlide } from 'vue-awesome-swiper'
    export default {
        props: {
            postImg: '',
            postIndex: 0
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
                return asset_url+'uploads/users/gallery/'+s
            }
        },
        computed: {
            isMultiple: function () {
                return this.postImg !== undefined ?  this.postImg.length > 1 : false
            },
            noImage: function () {
                return (this.postImg !== undefined) ?  this.postImg.length == 0 : true
            }
        },
        components: {
            swiper,
            swiperSlide
        },
        watch: {
            postImg: function (val) {
                console.log(val)
            }
        }
    }
</script>