<template>
    <div v-if="hasItem">
        <div class="ft-card__primary">
            <div class="ft-card__title text-center">
                    <h4 class="ft-event-card__title">{{ event.timeline.name }}</h4>
            </div>
            <div class="ft-card__list-wrapper">
                <div class="ft-card__list">
                    <div class="icon icon-location-o"></div>
                    <div class="card-desc">
                        <a :href="formatUrl(event.location)">{{ event.location }}</a>
                    </div>
                </div>
                <div class="ft-card__list">
                    <div class="icon icon-gender"></div>
                    <div class="card-desc">
                        {{ formatGender(event.gender) }}
                    </div>
                </div>
                <div class="ft-card__list">
                    <div class="icon icon-participant"></div>
                    <div class="card-desc">
                        <a href="javascript:;" class="" @click="viewParticipant">
                            {{participant}}
                        </a>
                    </div>
                </div>
                <div class="ft-card__list">
                    <div class="icon icon-time-o"></div>
                    <div class="card-desc">
                        {{ formatDate(event.start_date) }} - {{ formatDate(event.end_date) }}
                    </div>
                </div>
                <div class="ft-card__list">
                    <div class="icon icon-label-o"></div>
                    <div class="card-desc" v-html="formatPrice(event)">
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center layout-m-b-1">
            <div class="pos-rel md-layout md-layout--row md-align md-align--center-center">
                <template v-if="enableEventForThis">
                    <template v-if="!enableUrl">
                        <div class="md-layout-spacer" v-if="!disableDetail"></div>
                        <a :href="eventLink" type="button" v-if="!disableDetail" class="details-btn btn btn-submit ft-btn-primary">
                            {{$t('common.details') }}
                        </a>
                        <div class="md-layout-spacer"></div>
                        <button type="button" class="btn btn-submit ft-btn-primary" @click="registerEvent">
                            <template v-if="isRegistered">
                                {{$t('post.unreg') }}
                            </template>
                            <template v-else="">
                                {{$t('post.reg') }}
                            </template>
                        </button>
                        <div class="md-layout-spacer"></div>
                    </template>
                    <template v-else="">
                        <div class="md-layout-spacer"></div>
                        <a :href="eventLink" type="button" class="btn btn-submit ft-btn-primary">
                            {{$t('common.details') }}
                        </a>
                        <div class="md-layout-spacer"></div>
                    </template>
                </template>
                <template v-else="">
                    <template v-if="!enableUrl">
                        <div class="md-layout-spacer"></div>
                        <a :href="eventLink" type="button" class="btn btn-submit ft-btn-primary">
                            {{$t('common.details') }}
                        </a>
                        <div class="md-layout-spacer"></div>
                        <button type="button" disabled class="btn btn-submit">
                            {{$t('post.reg') }}
                        </button>
                        <div class="md-layout-spacer"></div>
                    </template>
                    <template v-else="">
                        <div class="md-layout-spacer"></div>
                        <a :href="eventLink" type="button" class="btn btn-submit ft-btn-primary">
                            {{$t('common.details') }}
                        </a>
                        <div class="md-layout-spacer"></div>
                    </template>
                </template>
                <div class="ft-loading ft-loading--abs" v-if="isLoading">
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            postItem: {},
            eventList:false,
            postIndex: '',
            enableUrl: false,
            disableDetail: false
        },
        data: function () {
            return {
                isLoading: false,
                participantList: []
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
                return g == 'all' ? this.$t('common.everyone') : g == 'male' ? this.$t('common.male') : this.$t('common.female')
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
            formatPrice: function(e) {
                if(!isNaN(e.price)) {
                    return (Math.ceil(e.price) ==  0) ? 'Free' : e.currency == 'EURO' ? '&euro;' + e.price : e.currency == 'POUND' ?  '&#163;' + e.price :'&dollar;' + e.price
                }
                return 'Free'
            },
            formatUrl: function(u) {
                return base_url+ 'event/location/' + u
            },
            unRegister: function () {
                let _token = $("meta[name=_token]").attr('content')
                let that = this
                this.isLoading = true
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: SP_source() + 'ajax/unregister-event',
                    data: {
                        event_id: that.event.id,
                        user_id: user_id,
                        _token: _token
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        materialSnackBar({messageText: response.data.data, autoClose: true })
                        that.$store.commit('SET_EVENT_STATUS', {postIndex: that.postIndex, name: 'registered', status: false})
                        /*if (response.data.liked == true) {
                         Vue.set(that.commentItemList[index], 'isLiked', true)
                         } else {
                         Vue.set(that.commentItemList[index], 'isLiked', false)
                         }*/
                    }
                    that.isLoading = false
                }).catch(function (error) {
                    that.isLoading = false
                    console.log(error)
                })
            },
            registerEvent: function () {
                if(this.isRegistered) {
                    this.unRegister()
                    return
                }
                if(this.event.price > 0) {
                    // joiningPaidEvent
                }
                let _token = $("meta[name=_token]").attr('content')
                let that = this
                this.isLoading = true
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: SP_source() + 'ajax/join-event',
                    data: {
                        timeline_id: that.event.timeline_id,
                        _token: _token
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        materialSnackBar({messageText: response.data.message, autoClose: true })
                        let obj = {postIndex: that.postIndex, name: 'registered', status: response.data.joined}
                        that.$store.commit('SET_EVENT_STATUS', obj)
                    }
                    that.isLoading = false
                }).catch(function (error) {
                    that.isLoading = false
                    console.log(error)
                })
                //Vue.set(that.commentItemList[index], 'isLiked', !that.commentItemList[index].isLiked)
            },
            viewParticipant: function () {
                this.$store.commit('SET_EVENT_WHO', {eventId: this.postItem.event[0].id})
                $('#post-who-participate-dialog').MaterialDialog('show')
            }
        },
        computed: {
            hasItem () {
                return this.postItem !== undefined ? this.postItem.event !== undefined && this.postItem.event !== '' : false
            },
            event() {
                return this.hasItem ? this.postItem.event[0] : {}
            },
            eventLink() {
                return this.hasItem ? base_url + 'post/' + this.postItem.id : ''
            },
            eventPrice () {
                return this.hasItem ? (this.event.price !== 0 &&  this.event.price !== null) ? this.event.price : 'Free' : ''
            },
            isRegistered () {
                return this.hasItem ? this.event.registered : false
            },
            enableEventForThis () {
                if(this.hasItem) {
                    if((this.event.gender === 'all' || this.event.gender === user_gender) && !this.event.protected && !this.event.expired) {
                        if (this.event.user_limit > this.participantList.length ) {
                            return true
                        }
                        return false
                    }
                    return false
                }
                return false
            },
            participant () {
                if(!this.participantList.length) {
                    return 'No Participant joined yet'
                } else {
                    return this.participantList.length +' (View Participants)'
                }
            }
        },
        mounted() {
            if(this.hasItem) {
                let that = this
                let _token = $("meta[name=_token]").attr('content')
                axios({
                    method: 'post',
                    responseType: 'json',
                    url: base_url + 'ajax/get-participants',
                    data: {
                        event_id: that.postItem.event[0].id,
                        _token: _token
                    }
                }).then(function (response) {
                    if (response.status == 200) {
                        for(let i = 0;i<response.data.length; i++) {
                            that.participantList.push(response.data[i])
                        }
                    }
                }).catch(function (error) {
                    console.log(error)
                })
            }
        }
    }
</script>