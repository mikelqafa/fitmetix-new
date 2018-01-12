<link href="{{ asset('css/theme1.css') }}" rel="stylesheet">
<link href="{{ asset('css/timeline-event.css') }}" rel="stylesheet">
<!-- main-section -->
	<div class="container container--standard section-container @if($timeline->hide_cover) no-cover @endif">
        <div class="row">
            <div class="md-layout md-algin md-layout--wrap md-align--start-start layout-timeline">
                <div class="md-col layout-timeline__post layout-m-t-1">
                    {!! Theme::partial('timeline-header',compact('timeline','user_post')) !!}
                    <div class="ft-header-hashtag">
                        <ul class="nav nav-justified" >
                            <li><a href="{{ url($timeline->username) }}">Posts</a></li>
                            <li class="is-active"><a href="{{ url($timeline->username.'/gallery') }}" class="">Gallery</a></li>
                            <li><a href="{{ url($timeline->username.'/event') }}" class="">Events</a></li>
                        </ul>
                    </div>
                    <div class="timeline timeline-posts--user wrap-ft-card--small">
                        @if(($timeline->user->settings()->post_privacy == 'everyone') || (Auth::user()->following->contains($timeline->user->id)) || (Auth::user()->id == $timeline->user->id))
                            <div id="app-timeline">
                                <input type="hidden" id="newPostId">
                                @if(isset($timeline->username))
                                    <input type="hidden" id="postByUsername" value="{{$timeline->username}}">
                                    <input type="hidden" id="galleryByUsername" value="{{$timeline->username}}">
                                @endif
                                <app-post-option></app-post-option>
                                <app-comment-option></app-comment-option>
                                <app-gallery-hlu>
                                    <div class="post-filters post-filters--auto-width">
                                        <div class="ft-grid">
                                            <div class="ft-grid__item">
                                                <div class="post-image--wrapper lg-loading-skeleton">
                                                    <div class="ft-card ft-card--only-image">
                                                        <div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ft-grid__item">
                                                <div class="post-image--wrapper lg-loading-skeleton">
                                                    <div class="ft-card ft-card--only-image">
                                                        <div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ft-grid__item">
                                                <div class="post-image--wrapper lg-loading-skeleton">
                                                    <div class="ft-card ft-card--only-image">
                                                        <div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ft-grid__item">
                                                <div class="post-image--wrapper lg-loading-skeleton">
                                                    <div class="ft-card ft-card--only-image">
                                                        <div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </app-gallery-hlu>
                                <div id="scroll-bt"></div>
                            </div>
                        @else
                            <div class="text-center">
                                <h2>This Account is Private</h2>
                                <p>Follow to see their photos and videos.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="md-col layout-timeline__left">
                    <div id="caleandar" style="width: 374px;margin-top: 1px"></div>
                </div>
            </div>
        </div>
	</div>