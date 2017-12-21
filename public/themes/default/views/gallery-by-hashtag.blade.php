<!-- main-section -->
<!-- <div class="main-content"> -->
<div class="container container--standard">
    <div class="row">
        <div class="col-md-12">

            <div class="ft-header-hashtag">
                <div class="jumbotron jumbotron--transparent jumbotron--ft  text-center">
                    <h1>{{$hashtag}}</h1>
                </div>
                <ul class="nav nav-justified">
                    <li class="is-active"><a href="{{url('gallery/hashtag/'.substr($hashtag, 1))}}" class="">Gallery</a></li>
                    <li><a href="{{url('event/hashtag/'.substr($hashtag, 1))}}" class="">Events</a></li>
                </ul>
            </div>


            @if (Session::has('message'))
                <div class="alert alert-{{ Session::get('status') }}" role="alert">
                    {!! Session::get('message') !!}
                </div>
            @endif

            @if(isset($active_announcement))
                <div class="announcement alert alert-info">
                    <a href="javascript:;" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <h3>{{ $active_announcement->title }}</h3>
                    <p>{{ $active_announcement->description }}</p>
                </div>
            @endif
            <div id="app-timeline">
                    <input type="hidden" id="newPostId">
                    @if(isset($hashtag))
                        <input type="hidden" id="galleryByHashTag" value="{{$hashtag}}">
                    @endif
                    <app-post-option></app-post-option>
                    <app-comment-option></app-comment-option>

                    <app-gallery-hlu>
                        <div class="post-filters">
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
        </div>
    </div>
</div>