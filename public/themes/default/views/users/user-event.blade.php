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
                        <li><a href="{{ url($timeline->username.'/gallery') }}" class="">Gallery</a></li>
                        <li class="is-active"><a href="{{ url($timeline->username.'/event') }}" class="">Events</a></li>
                    </ul>
                </div>
                <div class="timeline timeline-posts--user wrap-ft-card--small">
                    <div id="app-timeline">
                        <input type="hidden" id="newPostId">
                        @if(isset($hashtag))
                            <input type="hidden" id="postByUsername" value="{{$username}}">
                        @endif
                        <app-post-option></app-post-option>
                        <app-comment-option></app-comment-option>
                        <app-post-hashtag>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="lg-loading-skeleton ft-image-post">
                                            <div class="ft-image-post__item lg-loadable">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="lg-loading-skeleton ft-image-post">
                                            <div class="ft-image-post__item lg-loadable">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="lg-loading-skeleton ft-image-post">
                                            <div class="ft-image-post__item lg-loadable">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </app-post-hashtag>
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