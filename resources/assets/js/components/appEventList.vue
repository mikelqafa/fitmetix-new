<template>
    <div style="position: relative">

        <app-post-share></app-post-share>

        <post-theater-view></post-theater-view>

        <post-wholikes-view></post-wholikes-view>

        <event-participate-list></event-participate-list>

        <div v-if="showFilter" class="event-filter-wrapper">
            <div class="hidden-sm hidden-xs ft-filter">
                <fieldset class="form-group">
                    <input v-on:keyup.enter="submit(0)" onKeyPress="return initMapDesk(event)" class="pac-input form-control" id="filter-location-input" autocomplete="off" placeholder="By Location" name="location" type="text" style="position: relative; overflow: hidden;">
                </fieldset>
                <fieldset class="form-group">
                    <input v-on:keyup.enter="submit(1, $event)" class="form-control filter-date" name="date" id="filter-date" autocomplete="off" placeholder="By Date" type="text" style="position: relative; overflow: hidden;">
                </fieldset>
                <fieldset class="form-group">
                    <input v-on:keyup.enter="submit(2)" v-model.trim="filterData[2]" class="form-control" id="filter-tag" name="tags" autocomplete="off" placeholder="By Tag" type="text" style="position: relative; overflow: hidden;">
                </fieldset>
                <fieldset class="form-group">
                    <input v-on:keyup.enter="submit(3)" v-model.trim="filterData[3]" class="form-control" id="filter-title" name="title" autocomplete="off" placeholder="By Title" type="text" style="position: relative; overflow: hidden;">
                </fieldset>
            </div>

            <ul class="nav nav--event-filter nav-justified hidden-lg hidden-md">
                <li class="active">
                    <a data-toggle="tab" href="#home">
                        <i class="icon icon-location hidden-active"></i>
                        <i class="icon icon-location-o hidden-inactive"></i>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#menu1">
                        <i class="icon icon-time hidden-active"></i>
                        <i class="icon icon-time-o hidden-inactive"></i>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#menu2">
                        <i class="icon icon-tag hidden-active"></i>
                        <i class="icon icon-tag-o hidden-inactive"></i>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#menu3">
                        <i class="icon icon-label hidden-active"></i>
                        <i class="icon icon-label-o hidden-inactive"></i>
                    </a>
                </li>
            </ul>

            <div class="hidden-lg hidden-md tab-content tab-content--event-filter">
                <div id="home" class="tab-pane fade in active">
                    <input v-on:keyup.enter="submit(0)" v-model.trim="filterData[0]" class="form-control" id="filter-location-input-mob" autocomplete="off" placeholder="By Location" name="location" type="text" style="position: relative; overflow: hidden;">
                </div>
                <div id="menu1" class="tab-pane fade">
                    <input v-on:keyup.enter="submit(1)" class="form-control filter-date" name="date" id="filter-date-mob" autocomplete="off" placeholder="By Date" type="text" style="position: relative; overflow: hidden;">
                </div>
                <div id="menu2" class="tab-pane fade">
                    <input v-on:keyup.enter="submit(2)" v-model.trim="filterData[2]" class="form-control" id="filter-tag-mob" name="tags" autocomplete="off" placeholder="By Tag" type="text" style="position: relative; overflow: hidden;">
                </div>
                <div id="menu3" class="tab-pane fade">
                    <fieldset class="form-group required " style="margin-right: 0">
                        <input v-on:keyup.enter="submit(3)" v-model.trim="filterData[3]" class="form-control" id="filter-title-mob" name="title" autocomplete="off" placeholder="By Title" type="text" style="position: relative; overflow: hidden;">
                    </fieldset>
                </div>
            </div>

            <div class="md-layout md-layout--row layout-m-t-1 md-layout--wrap" style="margin-left: 12px; margin-right: 12px">
                <div class="md-layout md-layout--row md-layout--wrap">
                    <template v-for="item in filter">
                        <div class="md-chips is-removable" v-if="item.content !== ''">
                            <div class="md-chip">
                                <div class="md-chip__content">
                                    {{item.content}}</div>
                                <div class="md-chip__remove-container">
                                    <button class="md-chip__remove md-chip__remove--round" @click="removeFilter(item)"><i class="icon icon-close material-icons"></i> </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="md-layout-spacer"></div>
                <button class="btn btn-submit ft-btn-primary" @click="submitFilter">FILTER</button>
            </div>
        </div>

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
                    </template>
                    <template v-else-if="!noEventListFound">
                        <div class="ft-grid">
                            <div v-for="(item, index) in eventList" :key="item.id" class="ft-grid__item">
                                <div class="ft_card">
                                    <post-back-viewer gallery-view="true" v-on:open="showEventPost(item.event_id)" :post-img="item.media"></post-back-viewer>
                                    <div class="ft-card__primary hidden-sm hidden-xs">
                                        <div class="ft-card__title">
                                            <h5 class="ft-event-card__title">{{item.name}}</h5>
                                        </div>
                                        <div class="ft-card__list-wrapper">
                                            <div class="ft-card__list">
                                                <div class="icon icon-location-o"></div>
                                                <div class="card-desc">
                                                    <a :href="formatUrl(item.location)" target="_blank">{{ item.location }}</a>
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
                                                <div class="card-desc" v-html="formatPrice(item)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" v-if="!hasMorePost && !interact">
                            No more events to fetch
                        </div>
                        <div v-if="isFetchingBottom" class="ft-loading ft-loading--transparent" style="margin: 50px 0">
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                        </div>
                    </template>
                    <template v-else="">
                        <div v-if="noEventListFound" class="">
                            <h2 class="ft-loading text-center">
                                No Event found
                            </h2>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <aside class="md-drawer md-drawer--permanent" id="drawer-1" data-permanent="true">
            <div class="md-drawer__shadow"></div>
            <div class="md-drawer__surface">
                <div class="md-drawer-scroll" style="position: relative">
                    <div v-for="(postItem, index) in postItemList" :key="postItem.id" class="md-drawer-scroll__wrapper" :id="'ft-event-post'+postItem.id">
                        <div class="panel panel--eventlist panel-default timeline-posts__item panel-post" :id="'ft-post'+postItem.id">
                            <post-header event-list="true" v-on:close="closeEventPost" :post-data="postItem" :post-index="index" :date="postItem.created_at"></post-header>
                            <div class="panel-body">
                                <post-image-viewer :post-event="postItem.event" :post-index="index" :post-img="postItem.images"></post-image-viewer>
                                <post-event :post-item="postItem" :post-index="index" :post-img="postItem.images"
                                            event-list="true"></post-event>
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
                        <header class="md-layout md-layout--row">
                            <div class="md-layout-spacer"></div>
                            <a href="javascript:;" @click="closeEventPost" class="ft-btn--icon ft-btn-icon"><i class="icon icon-close"></i></a>
                        </header>
                        <div class="ft-loading text-center">
                            Event not found or deleted!
                        </div>
                    </div>
                </div>
            </div>
        </aside>

    </div>
</template>
<script>
    import postBackViwer from './child/showOnlySlider'
    import postImageViewer from './child/postImageViewer'
    import postEvent from './child/postEvent'
    import postHeader from './child/postHeader'
    import postComment from './child/postComment'
    import appPostShare from './child/appPostShare'
    import eventParticipateList from './child/eventParticipateList'
    import postTheaterView from './child/postTheaterView'
    import postWhoLikesView from './child/postWhoLikesView'
    import { mapGetters } from 'vuex'
    var appEvent;
    export default {
        data: function () {
            return {
                filter: [
                    {filter: 'location', content: ''},
                    {filter: 'date', content: ''},
                    {filter: 'tag', content: ''},
                    {filter: 'title', content: ''}
                ],
                filterData: [],
                eventList: [],
                alreadyHavePost: true,
                interact: false,
                noPostFound: false,
                showProgress: true,
                noEventFound: false,
                noEventListFound:false,
                isFilterEventListLoading: false,
                showFilter: true,
                location: false,
                hashtag: false,
                hasMorePost:true,
                isFetchingBottom: false,
                offset: 0
            }
        },
        methods: {
            formatPrice: function(e) {
                if(!isNaN(e.price)) {
                    return (Math.ceil(e.price) ==  0) ? 'Free' : e.currency == 'EURO' ? '&euro;' + e.price : e.currency == 'POUND' ?  '&#163;' + e.price :'&dollar;' + e.price
                }
                return 'Free'
            },
            formatUrl: function(u) {
                return base_url+ 'locate-on-map/' + u
            },
            formatGender: function(g) {
                return g == 'all' ? 'Everyone' : g == 'male' ? 'Male Only' : 'Female Only'
            },
            formatDate: function(date) {
                let str = ''
                if(date != '') {
                    str = date
                    let res = str.split(' ')
                    str = res[0]+'T'+res[1]
                    str.replace(/\s/, 'T')
                }
                let obj = new Date(str)
                let options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }
                return obj.toLocaleString('en-us', options)
            },
            submit: function (i, e) {
                if(i==1) {
                    this.filter[i].content = e.target.value
                } else {
                    this.filter[1].content = $('#filter-date').val() == '' ?  $('#filter-date-mob').val() : $('#filter-date').val()
                    this.filter[i].content = this.filterData[i]
                }
                this.eventList = []
                this.offset = 0
                this.alreadyHavePost = true
                this.interact = false
                this.noPostFound = false
                this.isFetchingBottom = false
                this.getDefaultData()
            },
            submitFilter: function() {
                if($(window).width()>600) {
                    if($('#filter-date').val()!='')
                    this.filter[1].content = $('#filter-date').val()
                } else  {
                    if($('#filter-date-mob').val()!='')
                        this.filter[1].content = $('#filter-date-mob').val()
                }
                this.filter[0].content = this.filterData[0] !== undefined ?this.filterData[0]: ''
                this.filter[2].content = this.filterData[2] !== undefined ?this.filterData[2]: ''
                this.filter[3].content = this.filterData[3] !== undefined ?this.filterData[3]: ''
                this.eventList = []
                this.offset = 0
                this.alreadyHavePost = true
                this.interact = false
                this.noPostFound = false
                this.isFetchingBottom = false
                this.getDefaultData()
            },
            getDefaultData: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                this.noEventListFound = false
                let data = {
                    paginate: 6,
                    offset: this.offset,
                    location : this.filter[0].content,
                    date : this.filter[1].content,
                    tag : this.filter[2].content,
                    title : this.filter[3].content,
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
            showEventPost: function (e) {
                this.postItem = {}
                $('#drawer-1').MaterialDrawer('show')
                this.fetchNew(e)
            },
            closeEventPost: function (e) {
                $('#drawer-1').MaterialDrawer('hide')
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
                    console.log(response)
                    if (response.status ==  200) {
                        let obj = response.data[0].post
                        obj.timeline = response.data[0].timeline
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
            },
            acMapEventDesk: function (e) {
                let deskMapVal = $('#'+e).val()
                appEvent.filterData[0] = deskMapVal
                // appEvent.submit(0)
            },
            acMapEventMobo: function (e) {
                let moboMapVal = $('#'+e).val()
                appEvent.filterData[0] = moboMapVal
                //appEvent.submit(0)
            },
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
            removeFilter: function (item) {
                let index = $.map(this.filter, function (e, index) {
                    if(e.filter ==  item.filter){
                        return index
                    }
                });
                this.filter[index].content = ''
                if(index == 1) {
                    $('#filter-date').val('')
                    $('#filter-date-mob').val('')
                }
                this.filterData[index] = ''
                this.submitFilter()
            }
        },
        mounted() {
            appEvent = this
            if(mapReady) {
                if(!initMap) {
                    init()
                }
            }
            $( ".filter-date" ).datepicker({
                format: 'mm/dd/yyyy',
                ignoreReadonly: true,
                allowInputToggle: true
            });

            $('#drawer-1').MaterialDrawer({show: false, permanent: true})

            if($('#location').length) {
                this.showFilter = false
                this.location = true
            }

            if($('#hashtag').length) {
                this.showFilter = false
                this.hashtag = true
            }
            this.getDefaultData()
            this.scrollFetchInit()
        },
        components: {
            'post-image-viewer': postImageViewer,
            'post-header': postHeader,
            'post-event': postEvent,
            'post-comment': postComment,
            'post-back-viewer': postBackViwer,
            'post-theater-view': postTheaterView,
            'post-wholikes-view': postWhoLikesView,
            'app-post-share': appPostShare,
            'event-participate-list': eventParticipateList
        },
        computed: {
            showFilterButton () {
                for(let i=0;i<this.filter.length;i++) {
                    if(this.filter[i].content !== '') {
                        return true
                    }
                }
                return false
            },
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