<link href="{{ asset('css/theme1.css') }}" rel="stylesheet">
<link href="{{ asset('css/timeline-event.css') }}" rel="stylesheet">
<style>
    @media  screen and (min-width: 960px) {
        .md-drawer--permanent {
            width: auto;
            padding-right: 0px;
            padding-left: 0;
            padding-top: 60px;
            z-index: -1;
        }
        .md-drawer--permanent.md-drawer--visible {
            z-index: 1 !important;
        }
        .md-drawer {
            left: auto;
            right:0;
            width: 360px;
            -webkit-transform: translateX(0px);
            transform: translateX(0px);
        }
        .md-drawer--permanent {
            -webkit-transform: translateX(0px);
            transform: translateX(0px);
            max-width:100%;
            width: 360px;
        }
        .md-drawer--permanent .md-drawer__surface {
            -webkit-transform:translateX(360px);
            transform:translateX(360px);
            width:360px;
            max-width: none;
        }
        .md-drawer--animating .md-drawer__surface {
            -webkit-transform:translateX(360px);
            transform:translateX(360px)
        }

        body.has-permanent-drawer.is-drawer-open {
            padding-right: 360px;
            padding-left: 0 !important;
        }
    }
</style>
<!-- main-section -->
<div class="container container--standard section-container @if($timeline->hide_cover) no-cover @endif">
    <div class="row">
        <div class="md-layout md-algin md-layout--wrap md-align--start-start layout-timeline">
            <div class="md-col layout-timeline__post layout-m-t-1">
                {!! Theme::partial('timeline-header',compact('timeline','user_post')) !!}
                                    <div class="options" style="text-align: center;display: none;">
                        @if($timeline->user->settings()->post_privacy == 'everyone' || (Auth::user()->id == $timeline->user->id))
                                            <div class="ft-user-info md-layout md-layout--row md-align md-align--space-around show-more">
                                                <div class="ft-user-info__item">
                                                    <div class="ft-icon">
                                                        Events
                                                    </div>
                                                    <a class="info btn" href="{{ url($timeline->username.'/event') }}">
                                                        {{ count($user_events) }}
                                                    </a>
                                                </div>
                                                <div class="ft-user-info__item">
                                                    <div class="ft-icon">
                                                        Posts
                                                    </div>
                                                    <a class="info btn" href="">
                                                        {{ count($posts) }}
                                                    </a>
                                                </div>
                                                <div class="ft-user-info__item">
                                                    <div class="ft-icon">
                                                        Follows
                                                    </div>
                                                    <a href="javascript:;" class="info btn" onclick="$('#user-who-following--dialog').MaterialDialog('show')">
                                                        {{ $following_count }}
                                                    </a>
                                                </div>
                                                <div class="ft-user-info__item">
                                                    <div class="ft-icon">
                                                        Followers
                                                    </div>
                                                    <a href="javascript:;" class="info btn" onclick="$('#user-who-follow--dialog').MaterialDialog('show')">
                                                        {{ $followers_count }}
                                                    </a>
                                                </div>
                                                @if(Auth::user()->id == $timeline->user->id)
                                                    <div class="ft-user-info__item">
                                                        <div class="ft-icon">
                                                            Saved
                                                        </div>
                                                        <div class="info">
                                                            {{ count($timeline->user->postsSaved()) }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div id="user-follow-view">
                                <input type="hidden" id="follow-userid" value="{{$timeline->user->id}}">
                                <user-follow-list></user-follow-list>
                                <user-following-list></user-following-list>
                            </div>
                        @endif
                        @if(Auth::user()->id == $timeline->user->id)
                            <a class="btn btn-default" href="{{ url($timeline->username.'/settings') }}">Settings</a>
                        @elseif(Auth::user()->following->contains($timeline->user->id))
                                @if($block_text == 'Unblock')
                                    <button class="btn btn-default" onclick="unblock()">{{ $block_text }}</button>
                                @else
                                    <button class="btn btn-default" onclick="$('#profile-option-dialog').MaterialDialog('show')">{{ $block_text }}</button>
                                @endif
                            <button class="btn btn-default" onclick="$('#profile-option-dialog').MaterialDialog('show')">Report</button>
                            <button class="pos-rel btn btn-default" data-timeline-id="{{$timeline->id}}" data-toggle="follow" data-following="true">
                                Unfollow
                                <span class="absolute-loader hidden">
                                    <span class="ft-loading">
                                        <span class="ft-loading__dot"></span>
                                        <span class="ft-loading__dot"></span>
                                        <span class="ft-loading__dot"></span>
                                    </span>
                                </span>
                            </button>
                        @else
                            @if($block_text == 'Unblock')
                                <button class="btn btn-default" onclick="unblock()">{{ $block_text }}</button>
                            @else
                                <button class="btn btn-default" onclick="$('#profile-option-dialog').MaterialDialog('show')">{{ $block_text }}</button>
                            @endif
                            <button class="btn btn-default" onclick="$('#profile-option-dialog').MaterialDialog('show')">Report</button>
                        @endif
                    </div>
                <div class="ft-header-hashtag">
                    <ul class="nav nav-justified" >
                        <li><a href="{{ url($timeline->username) }}">Posts</a></li>
                        <li><a href="{{ url($timeline->username.'/gallery') }}" class="">Gallery</a></li>
                        <li class="is-active"><a href="{{ url($timeline->username.'/event') }}" class="">Events</a></li>
                    </ul>
                </div>
                <div class="timeline timeline-posts--user wrap-ft-card--big">
                    <div id="app-timeline">
                        <input type="hidden" id="newPostId">
                        @if(isset($timeline->username))
                            <input type="hidden" id="eventByUsername" value="{{$timeline->username}}">
                        @endif

                        @if(Auth::user()->id != $timeline->user->id)
                            <input type="hidden" id="username" value="{{$timeline->username}}">
                            <input type="hidden" id="timeline_id" value="{{$timeline->id}}">
                            <app-profile-option></app-profile-option>
                        @endif

                        @if(Auth::user()->id == $timeline->user->id)
                            <app-picture-option></app-picture-option>
                        @endif

                        <app-post-option></app-post-option>
                        <app-comment-option></app-comment-option>
                        <app-event-hlu>
                            <div class="post-filters pages-groups">
                                <div class="pane">
                                    <div class="pan">
                                        <div class="ft-grid ft-grid--12-xs">
                                            <div class="ft-grid__item lg-loading-skeleton">
                                                <div class="ft_card">
                                                    <div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
                                                    </div>
                                                    <div class="ft-card__primary">
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
                                                    <div class="ft-card__primary">
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
                                                    <div class="ft-card__primary">
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
                        </app-event-hlu>
                        <div id="scroll-bt"></div>
                    </div>
                </div>
            </div>
            <div class="md-col layout-timeline__left">
                <div id="caleandar" style="width: 374px;margin-top: 1px"></div>
            </div>
        </div>
    </div>
</div>