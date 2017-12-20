<div class="timeline-cover-section">
    <div class="timeline-cover" style="background-image: url('@if($timeline->cover_id) {{ url('user/cover/'.$timeline->cover->source) }} @else {{ url('user/cover/default-cover-user.png') }} @endif')">
        <img class="v-hidden" src=" @if($timeline->cover_id) {{ url('user/cover/'.$timeline->cover->source) }} @else {{ url('user/cover/default-cover-user.png') }} @endif" alt="{{ $timeline->name }}" title="{{ $timeline->name }}">
        @if($timeline->id == Auth::user()->timeline_id)
            <a href="javascript:;" class="btn btn-camera-cover change-cover"><i class="fa fa-camera" aria-hidden="true"></i><span class="change-cover-text">{{ trans('common.change_cover') }}</span></a>
        @endif
        <div class="user-cover-progress hidden">
        </div>
    </div>
    <div class="timeline-user-info-wrapper">
        <div class="timeline-user-avtar">
            <img src="{{ $timeline->user->avatar }}" alt="{{ $timeline->name }}" class="img-circle" title="{{ $timeline->name }}">
            @if($timeline->id == Auth::user()->timeline_id)
                <div class="chang-user-avatar">
                    <a href="#" class="btn btn-camera change-avatar"><i class="fa fa-camera" aria-hidden="true"></i><span class="avatar-text">{{ trans('common.update_profile') }}<span>{{ trans('common.picture') }}</span></span></a>
                </div>
            @endif
            <div class="user-avatar-progress hidden">
            </div>
        </div>
        <div class="user-timeline-name">
            <a href="{{ url($timeline->username) }}" class="username__link">{{$timeline->username }}</a>
            <div class="name">{{$timeline->name }}</div>
        </div>
    </div>
</div>
<div class="layout-m-t-2 timeline-option layout-m-b-2 md-layout md-layout--row md-align md-align--space-between-center">
    <div class="timeline-option__item">
        <a href="#" class="ft-btn ft-btn--icon">
            <i class="icon icon-chat"></i>
        </a>
    </div>
    <button class="btn ft-btn-primary ft-btn-primary--outline">Follow</button>
    <div class="timeline-option__item">
        <a href="#" class="ft-btn ft-btn--icon">
            <i class="icon icon-options"></i>
        </a>
    </div>
</div>
<div class="timeline-user-desc">
    {{$timeline->about}}
</div>