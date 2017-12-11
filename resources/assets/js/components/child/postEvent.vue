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
                        <a href="" target="_blank">{{event.location}}</a>
                    </div>
                </div>
                <div class="ft-card__list">
                    <div class="icon icon-participant"></div>
                    <div class="card-desc">
                        {{event.gender}}
                    </div>
                </div>
                <div class="ft-card__list">
                    <div class="icon icon-time-o"></div>
                    <div class="card-desc">
                        {{ event.start_date }} to {{event.end_date}}
                    </div>
                </div>
                <div class="ft-card__list">
                    <div class="icon icon-label-o"></div>
                    <div class="card-desc">
                        {{eventPrice}}
                    </div>
                </div>
                <div class="ft-card__list hidden">
                    <div class="card-desc" style="color: #1E7C82">
                        tags
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <a class="btn btn-submit ft-btn-primary" :href="userLink">
                Details
            </a>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            postItem: {}
        },
        data: function () {
            return {}
        },
        methods: {
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