<template>
    <div class="post-image--wrapper">
        <template v-if="isMultiple">
            <swiper :options="swiperOption" class="deal-card-slider slider--gallery">
                <swiper-slide :key="imageIndex" v-for="(image, imageIndex) in images">
                    <a href="javascript:;" :key="imageIndex" class="item__background" @click="emitOpen"
                       :style="{ backgroundImage: 'url(' + image + ')' }"></a>
                </swiper-slide>
                <div class="swiper-pagination" slot="pagination"></div>
                <div class="swiper-button-prev hidden" slot="button-prev"></div>
                <div class="swiper-button-next hidden" slot="button-next"></div>
            </swiper>
        </template>
        <template v-else="">
            <template v-if="!noImage">
                <div class="ft-card--only-image" v-for="(image, imageIndex) in images">
                    <div class="ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" v-bind:class="{'image-default': defaultImage}" @click="emitOpen" v-bind:style="{ backgroundImage: 'url(' + image +')'}">
                        <img class="ft-card__img" :src="image">
                    </div>
                </div>
            </template>
            <div v-else="" class="ft-card--only-image">
                <div class="ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" @click="emitOpen" v-bind:style="{ backgroundImage: 'url(' + defaultImageSrc +')'}">
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
                },
                isTypeEvent: true,
                defaultImage: false
            }
        },
        mounted () {
            let that = this
            if (this.postImg !== undefined) {
                $.each(this.postImg, function(key, val) {
                    let s = val.source !== undefined ? val.source : ''
                    that.images.push(s != '' ? asset_url + 'uploads/events/covers/'+s : base_url+'images/no-image.png')
                    if(s == '') {
                        this.defaultImage  = true
                    }
                });
            } else{
                this.images.push(base_url+'images/no-image.png')
                this.defaultImage  = true
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
        methods: {
            emitOpen: function () {
                this.$emit('open')
            },
            defaultImageSrc: function () {
               return base_url+'images/no-image.png'
            }
        },
        components: {
            swiper,
            swiperSlide
        }
    }
</script>