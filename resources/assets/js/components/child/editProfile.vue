<template>
    <div class="md-dialog md-dialog--maintain-width md-dialog--edit-post md-dialog--full-screen" id="profile-edit-option-dialog">
        <div class="md-dialog__wrapper">
            <div class="md-dialog__shadow"></div>
            <div class="md-dialog__surface" style="position: relative">
                <div class="md-dialog__body" v-if="hasItem">
                    <div class="panel-create panel-create--m-t-0">
                        <div class="panel-heading">
                            <div class="heading-text">
                                Edit Event
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <post-image-viewer disable-showcase="true" :post-event="postItem.event" :post-index="index" :post-img="postItem.images"></post-image-viewer>
                        <form v-on:submit.prevent="editSavePost" method="get" class="event-edit-group" style="padding: 24px 0">
                            <fieldset class="form-group required">
                                <input class="form-control" v-model="event.title" required placeholder="Name of your event" maxlength="30" name="name" type="text">
                            </fieldset>

                            <fieldset class="form-group required">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control selectize" id="edit-event-privacy" required="required">
                                            <option value="">Privacy</option>
                                            <option value="private">Private</option>
                                            <option value="public">Public</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control selectize" id="edit-event-gender" required="required" name="gender">
                                            <option value="">Gender</option>
                                            <option value="male">Males Only</option>
                                            <option value="female">Females Only</option>
                                            <option value="all">Everyone</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-group required">
                                <input class="form-control" v-model="event.location" placeholder="Enter location" required type="text">
                            </fieldset>

                            <fieldset class="form-group required">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="number" v-model="event.participant" class="form-control" required placeholder="Number of participants" min="1">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" v-model="event.price" readonly class="form-control" required placeholder="Price (USD)" min="0" max="10000">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-group required">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group date form_datetime">
                                            <input type="text" id="edit-event-start-date" required class="datepick2--event-edit form-control" name="start_date"
                                                   placeholder="Start Time" value="">
                                        <label for="edit-event-start-date" class="input-group-addon addon-right calendar-addon">
                                            <span class="fa fa-calendar"></span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control"  required v-model="event.duration" placeholder="duration" id="duration-event">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <div class="ft-cp ft-cp--edit">
                            <div class="ft-cp__presentation">
                                <div class="write-post">
                                    <div class="write-post__placeholder" v-if="hasNotContent">{{placeholder}}</div>
                                    <medium-editor id="edit-post-vue" :text='backContent' :options='options'
                                                   class="write-post__text text-wrapper"
                                                   v-on:edit='processEditOperationPost'
                                                   style="outline: none; user-select: text; white-space: pre-wrap; word-wrap: break-word;"
                                                   custom-tag='div'>
                                    </medium-editor>
                                    <div class="replace-with-edit-event"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer md-layout md-layout--row">
                        <div class="md-layout-spacer"></div>
                        <button class="btn" type="button" @click="cancelSaveEvent">Cancel</button>
                        <button type="submit" style="margin-left: 8px" class="btn btn-edit-submit ft-btn-primary" @click="editSavePost">Save Event</button>
                    </div>
                    <div v-if="isLoading" class="absolute-loader" style="z-index: 11">
                        <div class="ft-loading">
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                            <span class="ft-loading__dot"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex'

    export default {
        props: {
            postItem: '',
            index: 0
        },
        data: function () {
            return {
                isLoading: false,
                event: {
                    startDate: '' ,
                    duration:'',
                    participant:'',
                    gender:'',
                    location:'',
                    title:'',
                    price:'',
                    privacy:'',
                    id: ''
                }
            }
        },
        methods: {
            validate: function () {
                if(this.event.duration > 172800) {
                    alertApp('Event duration must be less than 48 hours')
                    return false
                }
                return true
            },
            initEventPlugin: function () {
                setTimeout(function(){
                    emojify.run()
                    $('.selectize').selectize()
                    $(".datepick2--event-edit").datetimepicker({
                        format: "mm/dd/yyyy H P",
                        autoclose: true,
                        minView: 1,
                        startView: "month",
                        startDate: today,
                        endDate: new Date(today.getFullYear(), today.getMonth()+3, today.getDate()),
                        showMeridian: true
                    });
                    $('#duration-event').durationPicker({
                        lang: 'en',
                        formatter: function (s) {
                            return s;
                        },
                        showSeconds: false
                    });
                }, 300)
            }
        },
        mounted () {
            let that = this
            this.placeholder = 'Event Description'
            let dialog = $('#profile-edit-option-dialog').MaterialDialog({show:false});
            this.backContent = this.backContentPrev
            if(this.hasItem) {
                this.initEventForm()
            }
            this.initEventPlugin()
        },
        components: {

        },
        computed: {
            hasItem: function () {
                return this.postItem.description !== undefined
            }
        }
    }
</script>
