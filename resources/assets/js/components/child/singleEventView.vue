<template>
    <div v-if="!isLoading">
        <swiper :options="swiperOptionE" class="event-slider">
            <swiper-slide :key="postItem.id" v-for="(postItem, index) in eventList">
                <div class="panel panel--eventlist panel-default timeline-posts__item panel-post" :id="'ft-post'+postItem.id">
                    <post-header event-list="false" disable-close="true" :post-data="postItem" :post-index="index" :date="postItem.created_at"></post-header>
                    <div class="panel-body">
                        <post-back-viewer :post-img="postItem.images"></post-back-viewer>
                        <post-event :post-item="postItem" :post-index="index" :post-img="postItem.images"
                                    event-list="true" enable-url="true"></post-event>
                        <post-description :post-html="postItem.description"></post-description>
                    </div>
                    <div class="md-layout-spacer"></div>
                </div>
            </swiper-slide>
            <div class="swiper-button-prev hidden" slot="button-prev"></div>
            <div class="swiper-button-next hidden" slot="button-next"></div>
        </swiper>
    </div>
    <div v-else-if="!noEventListFound" class="ft-grid__item lg-loading-skeleton" style="width: 100%;padding-top: 0">
        <div class="ft_card">
            <div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
            </div>
            <div class="ft-card__primary hidden-sm hidden-xs">
                <div class="ft-card__title lg-loadable">
                    <h5 class="ft-event-card__title">&nbsp;</h5>
                </div>
                <div class="ft-card__list-wrapper">
                    <div class="ft-card__list">
                        <div class="icon lg-loadable"></div>
                        <div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
                            &nbsp;
                        </div>
                    </div>
                    <div class="ft-card__list">
                        <div class="icon icon-participant lg-loadable"></div>
                        <div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
                            &nbsp;
                        </div>
                    </div>
                    <div class="ft-card__list">
                        <div class="icon lg-loadable"></div>
                        <div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
                            &nbsp;
                        </div>
                    </div>
                    <div class="ft-card__list">
                        <div class="icon lg-loadable"></div>
                        <div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center" v-else="">
        <h3>No Event Found</h3>
    </div>
</template>

<script>
    import postDescription from './postDescription'
    import postBackViewer from './showOnlySlider'
    import postEvent from './postEvent'
    import postHeader from './postHeader'
    import postComment from './postComment'
    import { mapGetters } from 'vuex'
    export default {
        data: function () {
            return {
                filterData: [],
                eventList: [],
                noEventFound: false,
                noEventListFound:false,
                swiperOptionE: {
                    slidesPerView: 1,
                    autoplay: 10000,
                    /*autoplay: false,*/
                    paginationClickable: true,
                    spaceBetween: 0,
                    loop: false,
                    autoHeight: true,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev'
                    }
                }
            }
        },
        methods: {
            formatGender: function(g) {
                return g == '' ? 'Everyone' : g == 'male'? 'Male Only' : 'Female Only'
            },
            formatDate: function(d) {
                let obj = new Date(d)
                let options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }
                return obj.toLocaleString('en-us', options)
            },
            formatPrice: function(p) {
                return p == null ? 'Free' : '$' + p
            },
            formatUrl: function(u) {
                return base_url+ 'locate-on-map/' + u
            },
            getDefaultData: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                let url = base_url + 'ajax/get-events-on-homepage'
                this.noEventListFound = false
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: url,
                    data: {
                        username: current_username,
                        paginate: 10
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        that.eventList = []
                        let events = response.data[0].posts
                        for (let i = 0; i < events.length; i++) {
                            that.eventList.push(events[i])
                        }
                        if (!that.eventList.length) {
                            that.noEventListFound = true
                        } else {
                            that.noEventListFound = false
                        }
                        setTimeout(function () {
                            emojify.run();
                            hashtagify();
                            mentionify();
                        }, 500)
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            }
        },
        mounted() {
                this.getDefaultData()
            },
        components: {
                'post-description': postDescription,
                'post-back-viewer': postBackViewer,
                'post-header': postHeader,
                'post-event': postEvent,
                'post-comment': postComment
            },
        computed: {
            isLoading () {
                return this.eventList.length === 0
            }
        },
    }
</script>
