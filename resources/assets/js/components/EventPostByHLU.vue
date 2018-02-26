<template>
    <div class="row-on-mobile">
        <app-post-share></app-post-share>
        <post-theater-view></post-theater-view>
        <post-wholikes-view></post-wholikes-view>
        <event-participate-list></event-participate-list>
        <div class="post-filters pages-groups post-filters--auto-width">
            <div class="pane">
                <div class="pan">
                    <template v-if="!noEventListFound && isEventListLoading">
                        <div class="ft-grid">
                            <div class="ft-grid__item lg-loading-skeleton">
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
                            <div class="ft-grid__item lg-loading-skeleton">
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
                            <div class="ft-grid__item lg-loading-skeleton">
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
                            <div class="ft-grid__item lg-loading-skeleton">
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
                        </div>
                        <div v-if="isFetchingBottom" class="ft-loading ft-loading--transparent" style="margin: 50px 0">
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                        </div>
                        <div class="hidden text-center" v-if="!hasMorePost">
                            {{$t('common.n_m_e_f')}}
                        </div>
                    </template>
                    <template v-else-if="!noEventListFound">
                        <div class="ft-grid">
                            <div v-for="(item, index) in eventList" :key="item.id" class="ft-grid__item">
                                <div class="ft_card ft-card--shadow">
                                    <div class="ft-card ft-card--only-image">
                                        <post-back-viewer v-on:open="showEventPost(item.event_id, index)" :post-img="item.media"></post-back-viewer>
                                    </div>
                                    <div class="ft-card__primary hidden-xs">
                                        <div class="ft-card__title lg-loadable">
                                            <h5 class="ft-event-card__title">{{item.name}}</h5>
                                        </div>
                                        <div class="ft-card__list-wrapper">
                                            <div class="ft-card__list">
                                                <div class="icon icon-location-o"></div>
                                                <div class="card-desc">
                                                    <a :href="formatUrl(item.location)">{{ item.location }}</a>
                                                </div>
                                            </div>
                                            <div class="ft-card__list">
                                                <div class="icon icon-participant"></div>
                                                <div class="card-desc">
                                                    {{ formatGender(item.gender) }}
                                                </div>
                                            </div>
                                            <div class="ft-card__list">
                                                <div class="icon icon-time-o"></div>
                                                <div class="card-desc">
                                                    {{ formatDate(item.start_date) }} - {{ formatDate(item.end_date) }}
                                                </div>
                                            </div>
                                            <div class="ft-card__list">
                                                <div class="icon icon-label-o"></div>
                                                <div class="card-desc" v-html="formatPrice(item)"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center ft-loading ft-loading--transparent" v-if="!hasMorePost">
                            {{$t('common.n_m_e_f')}}
                        </div>
                        <div v-if="isFetchingBottom" class="ft-loading ft-loading--transparent" style="margin: 50px 0">
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                        </div>
                    </template>
                    <template v-else="">
                        <div v-if="noEventListFound" class="">
                            <h2 class="ft-loading ft-loading--transparent text-center">
                                {{$t('common.event')}} {{$t('post.n_f')}}
                            </h2>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <aside class="md-drawer" id="drawer-1">
            <div class="md-drawer__shadow"></div>
            <div class="md-drawer__surface">
                <div class="md-drawer-scroll" style="position: relative">
                    <div v-for="(postItem, index) in postItemList" :key="postItem.id" class="md-drawer-scroll__wrapper" :id="'ft-event-post'+postItem.id">
                        <div class="panel panel--eventlist panel-default timeline-posts__item panel-post" :id="'ft-post-mob'+postItem.id">
                            <post-header event-list="true" v-on:close="closeEventPost" :post-data="postItem" :post-index="index" :date="postItem.created_at"></post-header>
                            <div class="panel-body">
                                <post-image-viewer :post-event="postItem.event" :post-index="index" :post-img="postItem.images"></post-image-viewer>
                                <post-event event-list="true" :post-item="postItem" :post-index="index" :post-img="postItem.images"></post-event>
                                <post-description :post-html="postItem.description"></post-description>
                            </div>
                            <div class="md-layout-spacer"></div>
                            <post-comment :post-index="index" :post-id="postItem.id" :post-item="postItem"></post-comment>
                        </div>
                    </div>
                    <div v-if="isEventLoading && !noEventFound" class="absolute-loader">
                        <div class="ft-loading">
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                        </div>
                    </div>
                    <div v-if="noEventFound" class="absolute-loader">
                        <div class="ft-loading text-center">
                            {{$t('common.event')}} {{$t('post.n_f')}}
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</template>
<script>
    import postDescription from './child/postDescription'
    import postBackViwer from './child/showOnlySlider'
    import postImageViewer from './child/postImageViewer'
    import postEvent from './child/postEvent'
    import postHeader from './child/postHeader'
    import postComment from './child/postComment'
    import postWhoLikesView from './child/postWhoLikesView'
    import appPostShare from './child/appPostShare'
    import eventParticipateList from './child/eventParticipateList'
    import postTheaterView from './child/postTheaterView'
    import { mapGetters } from 'vuex'
    export default {
        data: function () {
            return {
                filterData: [],
                isFetchingBottom:false,
                eventList: [],
                alreadyHavePost: true,
                interact: false,
                noPostFound: false,
                showProgress: true,
                noEventFound: false,
                noEventListFound:false,
                isFilterEventListLoading: false,
                showFilter: true,
                location : '',
                tag:'',
                username: '',
                enableOverlay: false,
                eventUnid: 'event-dialog-opener',
                hasMorePost:true,
                offset: 0,
                currentIndex: ''
            }
        },
        methods: {
            scrollFetchInit: function () {
                let that = this
                $(window).scroll(function() {
                    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                        setTimeout(function() {
                            if(!$('body').hasClass('is-drawer-open')) {
                                if(!that.inProgress && that.hasMorePost ){
                                    that.isFetchingBottom = true
                                    that.getDefaultData()
                                    that.inProgress = true
                                }
                            }
                        }, 100)
                    }
                });
            },
            formatGender: function(g) {
                return g == '' ? 'Everyone' : g == 'male'? 'Male Only' : 'Female Only'
            },
            formatDate: function(date) {
                let str = ''
                if(date != '') {
                    str = date
                    let res = str.split(' ')
                    str = res[0]+'T'+res[1]
                    str.replace(/\s/, 'T')
                }
                let obj = new Date(str+'Z')
                let options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }
                return obj.toLocaleString('en-us', options)
            },
            makeUrl: function () {
                let url = base_url + 'ajax/get-events'
                this.location = ''
                this.tag = ''
                this.username = ''
                let l = $('#eventByLocation')
                if(l !== undefined && l.length) {
                    url += '?'+ 'location' + '=' + l.val()
                    this.location = l.val()
                }
                let e = $('#eventByHashTag')
                if(e !== undefined && e.length) {
                    url += '?'+ 'tag' + '=' + e.val()
                    this.tag = e.val()
                }
                let u = $('#eventByUsername')
                if(u !== undefined && u.length) {
                    url += '?'+ 'username' + '=' + u.val()
                    this.username = u.val()
                }
                return url
            },
            formatPrice: function(e) {
                return (e.price == null || 0) ? 'Free' : e.currency == 'EURO' ? '&euro;' + e.price : e.currency == 'POUND' ?  '&#163;' + e.price :'&dollar;' + e.price
            },
            formatUrl: function(u) {
                return base_url+ 'event/location/' + u
            },
            submit: function (i, e) {
                if(i==1) {
                    console.log(e.target.value)
                    this.filter[i].content = e.target.value
                } else {
                    this.filter[i].content = this.filterData[i]
                }
                this.eventList = []
                this.getDefaultData()
            },
            getDefaultData: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.noEventListFound = false
                this.makeUrl()
                let data = {
                    paginate: 6,
                    offset: this.offset,
                    location : this.location,
                    tag:this.tag,
                    username: this.username,
                    _token: _token
                }
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/get-events',
                    data: data
                }).then( function (response) {
                    if (response.status ==  200) {
                        let events = response.data.data;
                        console.log(response.data)
                        let i = 0
                        $.each(events, function(key, val) {
                            that.eventList.push(val)
                            i++
                        });
                        if(!i) {
                            if(!that.interact) {
                                that.alreadyHavePost = false
                                that.noEventListFound = true
                            } else {
                                that.hasMorePost = false
                            }
                        } else {
                            that.interact = true
                        }
                        that.inProgress = false
                        that.hasMorePost = i == data.paginate;
                        that.offset += i
                        that.isFetchingBottom = false
                        setTimeout(function () {
                            emojify.run();
                            hashtagify();
                            mentionify();
                        }, 500)
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            },
            closeDrawer: function () {
                $('#drawer-1').MaterialDrawer('hide')
            },
            showEventPost: function (e, index) {
                this.postItem = {}
                $('#drawer-1').MaterialDrawer('show')
                this.currentIndex = index
                this.fetchNew(e)
            },
            closeEventPost: function (e) {
                $('#drawer-1').MaterialDrawer('hide')
                $('#'+this.eventUnid).MaterialDialog('hide')
            },
            fetchNew: function (postId){
                this.showProgress = true
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.$store.commit('RESET_POST_ITEM_LIST')
                that.noEventFound = false
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/get-event-post-by-eventid',
                    data: {
                        _token: _token,
                        event_id: postId
                    }
                }).then( function (response) {
                    that.showProgress = false
                    if (response.status ==  200) {
                        let data = response.data[0]
                        if(data.error) {
                            that.noEventFound = true
                            return
                        }
                        console.log(data)
                        let obj = { }
                        obj = data.post
                        that.$store.commit('ADD_POST_ITEM_LIST',{data:obj, postFrom: 'timeline'})
                        setTimeout(function () {
                            hashtagify()
                            mentionify()
                        }, 300)
                    }
                }).catch(function(error) {
                    console.log(error)
                    that.showProgress = false
                })
            }
        },
        mounted() {
            $( ".filter-date" ).datepicker({
                format: 'mm/dd/yyyy',
                ignoreReadonly: true,
                allowInputToggle: true
            });

            let u = $('#eventByUsername')
            if(u !== undefined && u.length) {
                this.enableOverlay = true
            }
            this.getDefaultData()
            $('#drawer-1').MaterialDrawer({show: false, permanent: true})
            this.scrollFetchInit()
        },
        components: {
            'post-description': postDescription,
            'post-image-viewer': postImageViewer,
            'post-header': postHeader,
            'post-event': postEvent,
            'post-comment': postComment,
            'post-back-viewer': postBackViwer,
            'post-wholikes-view': postWhoLikesView,
            'app-post-share': appPostShare,
            'post-theater-view': postTheaterView,
            'event-participate-list': eventParticipateList
        },
        computed: {
            isLoading () {
                return this.eventList.length === 0
            },
            isEventLoading () {
                return this.postItemList.length === 0
            },
            isEventListLoading () {
                return this.eventList.length === 0
            },
                ...mapGetters({
                    postItemList: 'postItemList'
                })
            }
    }
</script>