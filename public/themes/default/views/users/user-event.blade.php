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