<template>
    <div v-if="hasItem">
        <div class="ft-card__primary">
            <div class="ft-card__title text-center">
                    <h4 class="ft-event-card__title">{{ postItem.timeline.name }}</h4>
            </div>
            <div class="ft-card__list-wrapper">
                <div class="ft-card__list">
                    <div class="icon icon-location-o"></div>
                    <div class="card-desc">
                        <a :href="formatUrl(event.location)" target="_blank">{{ event.location }}</a>
                    </div>
                </div>
                <div class="ft-card__list">
                    <div class="icon icon-participant"></div>
                    <div class="card-desc">
                        {{ formatGender(event.gender) }}
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
                    <div class="card-desc">
                        {{ formatPrice(event.price) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center" v-if="!eventList">
            <a class="btn btn-submit ft-btn-primary" :href="userLink">
                Details
            </a>
        </div>
        <div class="text-center layout-m-b-1" v-if="eventList">
            <a class="btn btn-submit ft-btn-primary" :href="userLink">
                Register
            </a>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            postItem: {},
            eventList:false
        },
        data: function () {
            return {}
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
            }
        },
        computed: {
            hasItem () {
                return this.postItem !== undefined ? this.postItem.event !== undefined && this.postItem.event !== '' : false
            },
            event() {
                return this.hasItem ? this.postItem.event[0] : {}
            },
            eventPrice () {
                return this.hasItem ? (this.event.price !== 0 &&  this.event.price !== null) ? this.event.price : 'Free' : ''
            },
            userLink () {
                return base_url + this.postItem.timeline.username
            }
        },
        mounted() {

        }
    }
</script>