<template>
    <div v-if="!noImage" class="post-image--wrapper">
        <template v-if="isMultiple">
            <swiper :options="swiperOption" class="deal-card-slider slider--gallery">
                <swiper-slide v-for="(image, imageIndex) in postImg" :key="imageIndex">
                    <a href="javascript:;" class="item__background" :key="imageIndex" @click="showTheater(imageIndex)"
                       :style="{ 'background-image': 'url(' + sourceImagePath(image.source) + ')' }">
                        <img :data-src="sourceImagePath(image.source)" :src="sourceImagePath(image.source)" class="v-hidden swiper-lazy img-responsive">
                    </a>
                </swiper-slide>
                <div class="swiper-pagination" slot="pagination"></div>
                <div class="swiper-button-prev hidden" slot="button-prev"></div>
                <div class="swiper-button-next hidden" slot="button-next"></div>
            </swiper>
        </template>
        <template v-else="">
            <div class="ft-card ft-card--only-image" v-for="(image, imageIndex) in postImg">
                <div @click="showTheater(0)" class="ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" :style="{ backgroundImage: 'url(' + sourceImagePath(image.source) + ')' }">
                    <img :data-src="sourceImagePath(image.source)" :src="sourceImagePath(image.source)" class="ft-card__img">
                </div>
            </div>
        </template>
    </div>
</template>
<script>

    import { swiper, swiperSlide } from 'vue-awesome-swiper'
    export default {
        props: {
            postImg: '',
            postIndex: 0,
            postEvent: {},
            galleryView: false,
            postId:'',
            disableShowcase: false
        },
        data: function () {
            return {
                images: [],
                index: null,
                ready: false,
                swiperOption: {
                    slidesPerView: 1,
                    autoplay: false,
                    paginationClickable: true,
                    spaceBetween: 0,
                    loop: false,
                    autoHeight: false,
                    pagination: '.swiper-pagination'
                }
            }
        },
        methods: {
            sourceImagePath: function (s) {
                //gallery-view
                if(this.galleryView) {
                    return getThumbImage(this.isTypeEvent ? asset_url + 'uploads/events/covers/' + s : asset_url + 'uploads/users/gallery/' + s, 400)
                }
                return this.isTypeEvent ? asset_url + 'uploads/events/covers/' + s : asset_url + 'uploads/users/gallery/' + s
            },
            showTheater: function (imageIndex) {
                if (this.disableShowcase) {
                    return
                }
                if(this.galleryView  && ($(window).width() < 600)) {
                    window.location.href = base_url+'post/' + this.postId
                    return
                } else {
                    this.$store.commit('SET_THEATER_ITEM', {postIndex: this.postIndex, imageIndex: imageIndex})
                    $('#post-image-theater-dialog').MaterialDialog('show')
                }
            }
        },
        mounted () {
            console.log(this.postImg.length)
        },
        computed: {
            isMultiple: function () {
                return this.postImg !== undefined && this.postImg.length > 1
            },
            noImage: function () {
                return this.postImg !== undefined ? this.postImg.length === 0 : true
            },
            isTypeEvent: function () {
                return this.postEvent !== undefined && this.postEvent !== ''
            }
        },
        components: {
            swiper,
            swiperSlide
        }
    };
</script>