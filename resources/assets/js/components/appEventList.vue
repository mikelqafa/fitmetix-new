<template>
    <div>
        <post-theater-view></post-theater-view>
        <post-wholikes-view></post-wholikes-view>
        <div class="event-filter-wrapper">
            <div class="hidden-sm hidden-xs ft-filter">
                <fieldset class="form-group">
                    <input v-on:keyup.enter="submit(0)" v-model.trim="filterData[0]" class="form-control" id="filter-location-input" autocomplete="off" placeholder="By Location" name="location" type="text" style="position: relative; overflow: hidden;">
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
            <div class="md-layout md-layout--row">
                <div class="md-layout md-layout--row md-layout--wrap hidden">
                    <template v-for="item in filter">
                        <div class="md-chips is-removable" v-if="item.content !== ''">
                            <div class="md-chip">
                                <div class="md-chip__content">
                                    {{item.content}}</div>
                                <div class="md-chip__remove-container">
                                    <button class="md-chip__remove md-chip__remove--round"><i class="icon icon-close material-icons"></i> </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="md-layout-spacer"></div>
            </div>
        </div>
        <div class="post-filters pages-groups">
            <div class="pane">
                <div class="pan">
                    <template v-if="!noPostFound || alreadyHavePost">
                        <template v-if="isLoading">
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
                            </div>
                        </template>
                        <template v-else="">
                            <div class="ft-grid">
                                <div v-for="(item, index) in eventList" :key="item.id" class="ft-grid__item">
                                    <div class="ft_card">
                                        <a href="javascript:;" @click="showEventPost(item.id)" class="ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" :style="{backgroundImage: 'url(' + getCoverImage(item) + ')'}">
                                            <img class="ft-card__img" :src="getCoverImage(item)" alt="Event Cover">
                                        </a>
                                        <div class="ft-card__primary hidden-sm hidden-xs">
                                            <div class="ft-card__title lg-loadable">
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
                                                    <div class="card-desc">
                                                        {{ formatPrice(item.price) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                    <template v-else="">
                        <div class="text-center">
                            No Post Found
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <aside class="md-drawer md-drawer--permanent" id="drawer-1" data-permanent="true">
            <div class="md-drawer__shadow"></div>
            <div class="md-drawer__surface">
                <div style="">
                    <a class="btn" href="javascript:;" @click="closeDrawer">
                        <i class="icon icon-close"></i>
                    </a>
                </div>
                <div class="md-drawer-scroll" style="position: relative">
                    <div class="md-drawer-scroll__wrapper" v-if="postItem.id !== undefined">
                        <div class="panel panel-default timeline-posts__item panel-post" :id="'ft-post'+postItem.id">
                            <post-header :post-data="postItem" :post-index="index" :date="postItem.created_at"></post-header>
                            <div class="panel-body">
                                <post-description :post-html="postItem.description"></post-description>
                                <post-image-viewer :post-event="postItem.event" :post-index="index" :post-img="postItem.images"></post-image-viewer>
                                <post-event :post-item="postItem" :post-index="index" :post-img="postItem.images"></post-event>
                            </div>
                            <post-comment :post-index="index" :post-id="postItem.id" :post-item="postItem"></post-comment>
                        </div>
                    </div>
                    <div v-if="showProgress" class="absolute-loader">
                        <div class="ft-loading">
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</template>
<script>
    import postDescription from './child/postDescription'
    import postImageViewer from './child/postImageViewer'
    import postEvent from './child/postEvent'
    import postHeader from './child/postHeader'
    import postComment from './child/postComment'
    import postTheaterView from './child/postTheaterView'
    import postWhoLikesView from './child/postWhoLikesView'
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
                postItem: {},
                showProgress: true
            }
        },
        methods: {
            getCoverImage: function(i) {
                //uploads/events/covers/default-cover-event.png
                if(false) {
                    //return asset_url+ 'uploads/events/covers/' + user_event.timeline.cover['source']
                }  else {
                    //return asset_url+ 'uploads/events/covers/' + user_event.timeline.cover['source']
                }
                return ''
            },
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
            submit: function (i, e) {
                if(i==1) {
                    console.log(e.target.value)
                    this.filter[i].content = e.target.value
                } else {
                    this.filter[i].content = this.filterData[i]
                }
                this.showEventPost(e)
                this.getDefaultData()
            },
            getDefaultData: function () {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                let url = this.makeUrl()
                axios({
                    method: 'get',
                    responseType: 'json',
                    url: url
                }).then( function (response) {
                    console.log(response)
                    if (response.status ==  200) {
                        that.eventList = response.data.data
                        if(!that.eventList.length) {
                            if(!that.interact) {
                                that.alreadyHavePost = false
                                that.noPostFound = true
                            } else {
                                that.noPostFound = true
                            }
                        } else {
                            that.interact = true
                        }
                        /*setTimeout(function () {
                            emojify.run();
                            hashtagify();
                            mentionify();
                        }, 500)*/
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            },
            closeDrawer: function () {
                $('#drawer-1').MaterialDrawer('hide')
            },
            makeUrl: function () {
                let url = base_url + 'ajax/get-events'
                let parameterAdded = false
                for(let i = 0; i< this.filter.length; i++) {
                    if(this.filter[i].content != '') {
                        if(!parameterAdded) {
                            url += '?'+ this.filter[i].filter+'='+this.filter[i].content
                            parameterAdded = true
                        } else {
                            url += '&'+ this.filter[i].filter+'='+this.filter[i].content
                        }
                    }
                }
                return url
            },
            showEventPost: function (e) {
                this.postItem = {}
                $('#drawer-1').MaterialDrawer('show')
                this.fetchNew(248)
            },
            closeEventPost: function (e) {
                this.postItem = {}
                $('#drawer-1').MaterialDrawer('hide')
            },
            fetchNew: function (postId){
                this.showProgress = true
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'get-single-post',
                    data: {
                        username: '0149015297511611',
                        _token: _token,
                        post_id: postId
                    }
                }).then( function (response) {
                    that.showProgress = false
                    if (response.status ==  200) {
                        let post = response.data[0].post;
                        if(post.length) {
                            that.postItem = post[0]
                        }
                        setTimeout(function () {
                            hashtagify()
                            mentionify()
                        }, 1000)
                    }
                }).catch(function(error) {
                    console.log(error)
                    that.showProgress = false
                })
            },
        },
        mounted() {
            $( ".filter-date" ).datepicker({
                format: 'mm/dd/yyyy',
                ignoreReadonly: true,
                allowInputToggle: true
            });
            $('#drawer-1').MaterialDrawer({show: false, permanent: true})
            this.getDefaultData()
        },
        computed: {
            isLoading () {
                return this.eventList.length === 0
            }
        },
        components: {
            'post-description': postDescription,
            'post-image-viewer': postImageViewer,
            'post-header': postHeader,
            'post-event': postEvent,
            'post-comment': postComment,
            'post-theater-view': postTheaterView,
            'post-wholikes-view': postWhoLikesView
        },
    }
</script>