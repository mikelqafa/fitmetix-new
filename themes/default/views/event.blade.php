<!-- main-section -->
<!-- <div class="main-content"> -->
<div class="container-fluid">
    <div class="row">
        <div class="visible-lg col-lg-2">
            {!! Theme::partial('home-leftbar',compact('trending_tags')) !!}
        </div>

        <div class="col-md-7 col-lg-6">
            @if (Session::has('message'))
                <div class="alert alert-{{ Session::get('status') }}" role="alert">
                    {!! Session::get('message') !!}
                </div>
            @endif


            @if(isset($active_announcement))
                <div class="announcement alert alert-info">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <h3>{{ $active_announcement->title }}</h3>
                    <p>{{ $active_announcement->description }}</p>
                </div>
            @endif

            @if($mode != "eventlist")
                {!! Theme::partial('create-post',compact('timeline','user_post')) !!}

                <div class="timeline-posts">
                    @if($posts->count() > 0)
                        @foreach($posts as $post)
                        <ul class="list-unstyled no-margin">
                            <li class="list-group-item deleteevent">
                                <div class="connect-list">
                                    <div class="connect-link side-left">
                                        <div class="media">
                                            <div class="pull-left">
                                                <a href="{{ url($post->timeline->username) }}">
                                                    <img style="width:48px;height:48px;" src="{!! !is_null($post->timeline->cover) ? url('event/cover/'.$post->timeline->cover->source) : url('group/avatar/default-group-avatar.png') !!}" alt="Event Image" class="img-responsive">
                                                </a>
                                            </div>
                                            <div class="media-body" style="vertical-align:middle;">
                                                <a style="font-size:18px;color: #5f5d5d !important;font-family: 'Source Sans Pro', sans-serif;" href="{{ url($post->timeline->username) }}">
                                                    {{ $post->timeline->name }} <small style="font-size:9px;"><i class="fa fa-external-link"></i></small>
                                                    <br>
                                                    <small><time class="post-time timeago" datetime="{{ $post->created_at }}+00:00" title="{{ $post->created_at }}+00:00">{{ $post->created_at }}+00:00</time></small> <a target="_blank" href="{{ url('/get-location/'.$post->location) }}">
                                                        <i class="fa fa-map-marker"></i> {{ $post->location }}
                                                    </a>
                                                </a>
                                            </div>
                                        </div>
                                        <hr>
                                        Starts: <b>{{{ \Carbon\Carbon::createFromTimestamp(strtotime($post->start_date))->diffForHumans().' ('.(date('d-M-Y H:i', strtotime($post->start_date))).')' }}}</b> until <b>{{ \Carbon\Carbon::createFromTimestamp(strtotime($post->end_date))->diffForHumans().' ('.(date('d-M-Y H:i', strtotime($post->end_date))).')' }}</b>
                                        <hr>
                                        <a href="{{ url($post->timeline->username) }}" class="btn btn-default"> Details</a>
                                        <a href="{{ url($post->timeline->username) }}" class="pull-right btn btn-default"> Register </a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </li>
                        </ul>
                            <br>
                        @endforeach
                    @else
                        <div class="no-posts alert alert-warning">{{ trans('common.no_posts') }}</div>
                    @endif
                </div>
            @else
                {!! Theme::partial('eventslist',compact('user_events','username')) !!}
            @endif
        </div><!-- /col-md-6 -->

        <div class="col-md-5 col-lg-4">
            {!! Theme::partial('home-rightbar',compact('suggested_users', 'suggested_groups', 'suggested_pages')) !!}
        </div>
    </div>
</div>
<!-- </div> -->
<!-- /main-section -->