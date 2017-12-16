<template>
    <div>
        <div class="event-filter-wrapper">
            <div class="hidden-sm hidden-xs ft-filter">
                <fieldset class="form-group">
                    <input v-on:keyup.enter="submit(0)" v-model.trim="filterData[0]" class="form-control" id="filter-location-input" autocomplete="off" placeholder="By Location" name="location" type="text" style="position: relative; overflow: hidden;">
                </fieldset>
                <fieldset class="form-group">
                    <input v-on:keyup.enter="submit(1)" class="form-control filter-date" name="date" id="filter-date" autocomplete="off" placeholder="By Date" type="text" style="position: relative; overflow: hidden;">
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
                @php $i = 0; @endphp
                @foreach($user_events as $user_event)
                <div class="ft-card hidden" data-index="{{$i}}">
                    <div class="dropdown-wrapper">
                        <div class="dropdown">
                            <a href="javascript:;" class="btn btn-icon dropdown-toggle"  data-toggle="dropdown">
                                <span class="icon icon-options"></span>
                            </a>
                            <ul class="dropdown-menu" style="left: auto; right:0;">
                                @if($user_event->user_id != Auth::user()->id)
                                <li><a href="javascript:;" class="event-report">Report</a></li>
                                <li><a href="javascript:;">Save</a></li>
                                <li><a href="javascript:;">Share on facebook</a></li>
                                @else
                                <li><a href="javascript:;" class="">Edit</a></li>
                                <li><a href="javascript:;" class="delete-own-event">Delete</a></li>
                                <li><a href="javascript:;">Share on facebook</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    @if($user_event->timeline->cover)
                    <div class="ft-card__img-wrapper ft-card__img-wrapper--background" style="background-image: url('{{ env('STORAGE_URL').'uploads/events/covers/'.$user_event->timeline->cover['source'] }}')">
                        <img class="ft-card__img" src="{{ env('STORAGE_URL').'uploads/events/covers/'.$user_event->timeline->cover['source'] }}" alt="Event Cover">
                    </div>
                    @else
                    <div class="ft-card__img-wrapper ft-card__img-wrapper--background" style="background-image: url('{{ env('STORAGE_URL').'uploads/events/covers/default-cover-event.png' }}')">
                        <img class="ft-card__img" src="{{ env('STORAGE_URL').'uploads/events/covers/default-cover-event.png' }}" alt="Event Cover">
                    </div>
                    @endif


                    <div class="ft-card__primary">
                        <div class="ft-card__title">
                            <h5 class="ft-event-card__title">{{ $user_event->timeline->name }}</h5>
                        </div>
                        <div class="ft-card__list-wrapper">
                            <div class="ft-card__list">
                                <div class="icon icon-location-o"></div>
                                <div class="card-desc">
                                    {{ $user_event->location }}
                                </div>
                            </div>
                            <div class="ft-card__list">
                                <div class="icon icon-participant"></div>
                                <div class="card-desc">
                                    {{ $user_event->gender }}
                                </div>
                            </div>
                            <div class="ft-card__list">
                                <div class="icon icon-time-o"></div>
                                <div class="card-desc">
                                    {{ $user_event->start_date }} to {{ $user_event->end_date }}
                                </div>
                            </div>

                            <div class="ft-card__list">
                                <div class="icon icon-label-o"></div>
                                <div class="card-desc">
                                    @if(($user_event->price == NULL) || ($user_event->price < 1))
                                    {{ "FREE" }}
                                    @else
                                    {{ "$ $user_event->price" }}
                                    @endif
                                    @if(Auth::user()->id != $user_event->user_id)
                                    @if($user_event->expired == true)
                                    <button class="btn" disabled>Register</button>
                                    @else
                                    @if(($user_event->gender == 'all') || ($user_event->gender == Auth::user()->gender))

                                    @if($user_event->registered == true)
                                    <button class="btn btn-primary">Registered</button>
                                    @if(($user_event->price == NULL) || ($user_event->price < 1))
                                    <button onclick="unjoin_event(this)" class="btn btn-warning" data-timeline = "{{ $user_event->timeline->id }}">Unregister</button>
                                    @else
                                    <form class="form-horizontal" method="POST" action="{{ url('refundmoney.paypal') }}" >
                                        {{ csrf_field() }}
                                        <input id="amount" type="hidden" class="form-control" name="amount" value="{{ $user_event->price }}">
                                        <input id="currency" type="hidden" class="form-control" name="currency" value="USD">
                                        <input type="hidden" value="{{ $user_event->timeline->id }}">
                                        <input type="hidden" name="sale_id" value="{{ $user_event->transaction->transaction }}">
                                        <button type="submit" class="btn btn-primary">Unregister</button>

                                    </form>
                                    @endif


                                    @elseif($user_event->users()->count() >= $user_event->user_limit)

                                    <button disabled class="btn" data-timeline = "{{ $user_event->timeline->id }}">Register</button>

                                    @else
                                    @if(($user_event->price == NULL) || ($user_event->price < 1))

                                    <button class="btn btn-primary join-event-btn" data-timeline = "{{ $user_event->timeline->id }}">Register</button>

                                    @else
                                    <form class="form-horizontal" method="POST" action="{{ URL::route('addmoney.paypal') }}" target="_blank">
                                        {{ csrf_field() }}
                                        <input id="amount" type="hidden" class="form-control" name="amount" value="{{ $user_event->price }}">
                                        <input id="currency" type="hidden" class="form-control" name="currency" value="USD">
                                        <input type="hidden" value="{{ $user_event->timeline->id }}">
                                        <button type="submit" class="btn btn-primary">Register</button>

                                    </form>

                                    @endif
                                    @endif
                                    @else
                                    <button disabled class="btn" data-timeline = "{{ $user_event->timeline->id }}">Register</button>
                                    @endif
                                    @endif
                                    @endif
                                </div>
                            </div>
                            <div class="ft-card__list">
                                <div class="icon icon-participant"></div>
                                <div class="card-desc">
                                    <p class="show_participants" data-eventID = "{{ $user_event->id }}">{{ $user_event->users()->count() }}</p>
                                </div>
                            </div>
                            <div class="ft-card__list">
                                <div class="icon"></div>
                                <div class="card-desc">
                                    {{ $user_event->frequency }}
                                </div>
                            </div>
                        </div>
                        <div class="ft-card__desc">
                            {{ $user_event->timeline->about }}
                        </div>
                        {{-- <div class="ft-card__desc text-center">
                        <a href="{{url('/'.$user_event->timeline->username)}}" class="btn ft-btn-primary ">Details</a>
                    </div> --}}
                    </div>
                </div>
                @php $i++ @endphp
                @endforeach
            </div>
        </aside>
    </div>
</template>
<style></style>
<script>
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
                eventList: []
            }
        },
        methods: {
            submit: function (i) {
                this.filter[i].content = this.filterData[i]
            },
            getDefaultData: function () {
                let that = this
                let username = current_username
                let paginate = 4
                let _token = $("meta[name=_token]").attr('content')
                if($('#timeline_username').length) {
                    username =  $('#timeline_username').val()
                }
                axios({
                    method: 'get',
                    responseType: 'json',
                    url: base_url + 'ajax/get-events',
                    data: {
                        username: username,
                        paginate: paginate,
                        _token: _token,
                        offset: that.offset
                    }
                }).then( function (response) {
                    if (response.status ==  200) {
                        let posts = response.data[0].posts;
                        let i = 0
                        $.each(posts, function(key, val) {
                            that.$store.commit('ADD_POST_ITEM_LIST', val)
                            i++
                        });
                        if(!i) {
                            if(!that.interact) {
                                that.alreadyHavePost = false
                                that.noPostFound = true
                            } else {
                                that.noPostFound = true
                            }
                        } else {
                            that.interact = true
                        }
                        setTimeout(function () {
                            emojify.run();
                            hashtagify();
                            mentionify();
                        }, 500)
                        that.inProgress = false
                        that.hasMorePost = i == paginate;
                        that.offset += i
                        that.isFetchingBottom = false
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            },
            closeDrawer: function () {
                $('#drawer-1').MaterialDrawer('hide')
            }
        },
        mounted() {
            $( ".filter-date" ).datepicker({
                format: 'mm/dd/yyyy',
                ignoreReadonly: true,
                allowInputToggle: true
            });
        }
    }
</script>