<link rel="stylesheet" type="text/css" href="{{ asset('css/drawer.css') }}">
<div class="event-wrapper">
    <div class="container-fluid map-view-under">
        <div class="row">
            @if((env('GOOGLE_MAPS_API_KEY') != NULL))
                <iframe style="height:260px;width:100%;border:0;" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q={!! $location !!}&key={{ env('GOOGLE_MAPS_API_KEY') }}"></iframe>
            @endif
        </div>
        <div class="shadow-layer"></div>
    </div>
    <div class="container container--standard upper-layer">
        <div class="row">
            <div class="col-md-12">
                <div class="ft-header-hashtag">
                    <div class="jumbotron jumbotron--transparent jumbotron--ft  text-center">
                        <h1>{{$location}}</h1>
                    </div>
                    <ul class="nav nav-justified">
                        <li><a href="{{url('gallery/location/'.$location)}}" class="">Gallery</a></li>
                        <li class="is-active"><a href="{{url('/event/location/'.$location)}}" class="">Events</a></li>
                    </ul>
                </div>
                @if (Session::has('message'))
                    <div class="alert alert-{{ Session::get('status') }}" role="alert">
                        {!! Session::get('message') !!}
                    </div>
                @endif
            </div>
        </div>
        <div id="app-timeline">
            <input type="hidden" id="location" value="{{$location}}">
            <app-post-option></app-post-option>
            <app-comment-option></app-comment-option>
            <app-event-list>
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
            </app-event-list>
        </div>
    </div>
</div>

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