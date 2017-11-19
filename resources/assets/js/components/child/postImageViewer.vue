<template>
    <div v-if="!noImage" class="post-image--wrapper">
        <gallery :images="images" :index="index" @close="index = null"></gallery>
        <template v-if="isMultiple">
            <swiper :options="swiperOption" class="deal-card-slider">
                <swiper-slide :key="imageIndex" v-for="(image, imageIndex) in images">
                    <a href="javascript:;" :key="imageIndex" @click="index = imageIndex" class="item__background"
                       :style="{ backgroundImage: 'url(' + image + ')' }"></a>
                </swiper-slide>
                <div class="swiper-pagination" slot="pagination"></div>
                <div class="swiper-button-prev hidden--lt-sm" slot="button-prev"></div>
                <div class="swiper-button-next hidden--lt-sm" slot="button-next"></div>
            </swiper>
        </template>
        <template v-else="">
            <div class="image-responsive item__background--post img-viewer" v-for="(image, imageIndex) in images" :key="imageIndex" @click="index = imageIndex"
                 :style="{ backgroundImage: 'url(' + image + ')', height: '250px' }"></div>
        </template>
    </div>
</template>
<style>
    .item__background--post{
        width: 100%;
        background-size: cover;
        background-position: center;
    }
    .img-viewer{
        cursor: pointer;
        pointer-events: none;
    }
    .fkd-slider-wrapper {
        min-height: 300px;
        position: relative;
    }
    .item__background--home-slider{
        max-height: 300px;
    }

    @media screen and (max-width: 599px){
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
            postImg: {}
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
                    loop: true,
                    pagination: '.swiper-pagination'
                }
            }
        },
        mounted () {
            let that = this
            var url = 'http://assets.fitmetix.com/'
            if (this.postImg !== undefined) {
                $.each(this.postImg, function(key, val) {
                    // "/var/www/html/fitmetix/storage/uploads/users/gallery/"
                    console.log(val)
                    let s = val.source !== undefined ? val.source : ''
                    that.images.push(asset_url+'uploads/users/gallery/'+s)
                });
            }
        },
        computed: {
            isMultiple: function () {
                return this.images.length !== 1 && this.images.length !== 0
            },
            noImage: function () {
                return this.images.length === 0
            }
        },
        components: {
            swiper,
            swiperSlide
        }
    }
</script>