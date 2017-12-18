<template>
    <div v-if="!noImage" class="post-image--wrapper">
        <template v-if="isMultiple">
            <swiper :options="swiperOption" class="deal-card-slider slider--gallery">
                <swiper-slide :key="imageIndex" v-for="(image, imageIndex) in images">
                    <a href="javascript:;" :key="imageIndex" class="item__background"
                       :style="{ backgroundImage: 'url(' + image + ')' }"></a>
                </swiper-slide>
                <div class="swiper-pagination" slot="pagination"></div>
                <div class="swiper-button-prev hidden" slot="button-prev"></div>
                <div class="swiper-button-next hidden" slot="button-next"></div>
            </swiper>
        </template>
        <template v-else="">
            <div class="ft-card ft-card--only-image" v-for="(image, imageIndex) in images">
                <div class="ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" v-bind:style="{ backgroundImage: 'url(' + image +')'}">
                    <img class="ft-card__img" :src="image">
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
                    autoHeight: false,
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