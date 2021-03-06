<div class="timeline-cover-section">
    <div id="timeline-cover" class="timeline-cover" style="background-image: url('@if($timeline->cover_id) {{ url('user/cover/'.$timeline->cover->source) }} @else {{ url('user/cover/default-cover-user.png') }} @endif')">
        <img class="v-hidden" src=" @if($timeline->cover_id) {{ url('user/cover/'.$timeline->cover->source) }} @else {{ url('user/cover/default-cover-user.png') }} @endif" alt="{{ $timeline->name }}" title="{{ $timeline->name }}">
        @if($timeline->id == Auth::user()->timeline_id)
            <a href="javascript:;" class="btn btn-camera-cover change-cover" onclick="$('#picture-option-dialog').MaterialDialog('show')">
                <span class="icon icon-photo"></span>
            </a>
        @endif
        <div class="absolute-loader hidden">
            <div class="ft-loading">
                <span class="ft-loading__dot"></span>
                <span class="ft-loading__dot"></span>
                <span class="ft-loading__dot"></span>
            </div>
        </div>
    </div>
    <div class="timeline-user-info-wrapper">
        <div class="timeline-user-avtar" id="timeline-user-avtar" style="background-image: url({{ $timeline->user->avatar }})">
            @if($timeline->id == Auth::user()->timeline_id)
                <div class="chang-user-avatar">
                    <a href="javascript:;" class="btn btn-camera change-avatar" onclick="$('#picture-profile-option-dialog').MaterialDialog('show')">
                        <span class="icon icon-photo"></span>
                    </a>
                </div>
                <input type="hidden" id="timeline_id_user" value="{{Auth::user()->timeline_id}}">
            @endif
            <div class="absolute-loader hidden" style="background-color: transparent">
                <div class="ft-loading">
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                </div>
            </div>
        </div>
        <div class="user-timeline-name">
            <a href="{{ url($timeline->username) }}" class="username__link">{{'@'.$timeline->username }}</a>
            <div class="name">{{$timeline->name }}</div>
        </div>
    </div>
</div>
<div class="layout-m-t-2 timeline-option layout-m-b-2 md-layout md-layout--row md-align md-align--space-around-center">
    <div class="timeline-option__item">
        @if(Auth::user()->id == $timeline->user->id)
            <a href="{{ url(Auth::user()->username.'/create-event') }}" title="Inspire" class="md-layout md-layout--column text-center">
                <img src="{{asset('images/plus.svg')}}" class="fkd-step__svg"/>
            </a>
        @else
            <a href="javascript:;" class="ft-btn ft-btn--icon" data-user-id="{{$timeline->user->id}}" data-toggle="initChat">
                <i class="icon icon-chat"></i>
            </a>
        @endif
    </div>
    @if(Auth::user()->id == $timeline->user->id)
        <a href="{{ url('/'.Auth::user()->username.'/edit-profile') }}" class="btn ft-btn-primary ft-btn-primary--outline">{{ trans('common.edit_profile') }}</a>
    @elseif(Auth::user()->following->contains($timeline->user->id))

        @if(Auth::user()->checkFollowStatus($timeline->user->id))
            <button class="btn ft-btn-primary pos-rel ft-btn-primary--outline" data-timeline-id="{{$timeline->id}}" data-toggle="follow" disabled data-following="true" data-approved="false">
                <span class="absolute-loader hidden">
                    <span class="ft-loading">
                        <span class="ft-loading__dot"></span>
                        <span class="ft-loading__dot"></span>
                        <span class="ft-loading__dot"></span>
                    </span>
                </span>
                <span class="false">{{ trans('common.follow') }}</span>
                <span class="true">{{ trans('common.request_sent') }}</span>
            </button>
        @else
            <button class="btn ft-btn-primary pos-rel ft-btn-primary--outline" data-timeline-id="{{$timeline->id}}" data-toggle="follow" data-following="true">
                <span class="absolute-loader hidden">
                    <span class="ft-loading">
                        <span class="ft-loading__dot"></span>
                        <span class="ft-loading__dot"></span>
                        <span class="ft-loading__dot"></span>
                    </span>
                </span>
                <span class="false">{{ trans('common.follow') }}</span>
                <span class="true">{{ trans('common.following') }}</span>
            </button>
        @endif
    @else
        <button class="btn ft-btn-primary pos-rel ft-btn-primary--outline" data-timeline-id="{{$timeline->id}}" data-toggle="follow" data-following="false">
            <span class="absolute-loader hidden">
                <span class="ft-loading">
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                    <span class="ft-loading__dot"></span>
                </span>
            </span>
            <span class="false">{{ trans('common.follow') }}</span>
            <span class="true">{{ trans('common.following') }}</span>
        </button>
    @endif
    <div class="timeline-option__item">
        @if(Auth::user()->id == $timeline->user->id)
            <a href="javascript:;" class="ft-btn ft-btn--icon" onclick="$('.options').slideToggle()">
                <i class="icon icon-options"></i>
            </a>
        @else
            <a href="javascript:;" class="ft-btn ft-btn--icon" onclick="$('.options').slideToggle()">
                <i class="icon icon-options"></i>
            </a>
        @endif
    </div>
</div>
<div class="timeline-user-desc text-wrapper text-wrapper_desc">
    {{$timeline->about}}
    {{--Hi, I am on Fitmetix.--}}
</div>