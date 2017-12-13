<div class="list-group list-group-navigation socialite-group">
    <a href="{{ url('/admin') }}" class="list-group-item">

        <div class="list-icon socialite-icon {{ (Request::segment(1) == 'admin' && Request::segment(2)==null) ? 'active' : '' }}">
            <i class="fa fa-dashboard"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.dashboard') }}
            <div class="text-muted">
                {{ trans('common.application_statistics') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a>
{{--     <a href="{{ url('/admin/general-settings') }}" class="list-group-item">

        <div class="list-icon socialite-icon {{ Request::segment(2) == 'general-settings' ? 'active' : '' }}">
            <i class="fa fa-shield"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.website_settings') }}
            <div class="text-muted">
             {{ trans('common.general_website_settings') }}
         </div>
     </div>
     <span class="clearfix"></span>
 </a> --}}
{{--  <a href="{{ url('/admin/user-settings') }}" class="list-group-item">

    <div class="list-icon socialite-icon {{ Request::segment(2) == 'user-settings' ? 'active' : '' }}">
        <i class="fa fa-user-secret"></i>
    </div>
    <div class="list-text">
        <span class="badge pull-right"></span>
        {{ trans('common.user_settings') }}
        <div class="text-muted">
            {{ trans('common.user_settings_text') }}
        </div>
    </div>
    <span class="clearfix"></span>
</a> --}}

    {{-- <a href="{{ url('/admin/wallpapers') }}" class="list-group-item">
  
        <div class="list-icon socialite-icon {{ Request::segment(2) == 'wallpapers' ? 'active' : '' }}">
            <i class="fa fa-picture-o"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.wallpapers') }}
            <div class="text-muted">
                {{ trans('common.wallpapers_text') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a> 
    
    <a href="{{ url('/admin/themes') }}" class="list-group-item">
  
        <div class="list-icon socialite-icon {{ Request::segment(2) == 'themes' ? 'active' : '' }}">
            <i class="fa fa-picture-o"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.themes') }}
            <div class="text-muted">
                {{ trans('common.themes_text') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a> --}}

{{-- <a href="{{ url('/admin/event-settings') }}" class="list-group-item">

    <div class="list-icon socialite-icon {{ Request::segment(2) == 'event-settings' ? 'active' : '' }}">
        <i class="fa fa-calendar"></i>
    </div>
    <div class="list-text">
        <span class="badge pull-right"></span>
        {{ trans('common.event_settings') }}
        <div class="text-muted">
            {{ trans('common.event_settings_text') }}
        </div>
    </div>
    <span class="clearfix"></span>
</a> --}}
{{-- <a href="{{ url('/admin/announcements') }}" class="list-group-item">

    <div class="list-icon socialite-icon {{ Request::segment(2) == 'announcements' ? 'active' : '' }}">
        <i class="fa fa-bullhorn"></i>
    </div>
    <div class="list-text">
        <span class="badge pull-right"></span>
        {{ trans('common.announcements') }}
        <div class="text-muted">
            {{ trans('common.announcements_text') }}
        </div>
    </div>
    <span class="clearfix"></span>
</a> --}}

    <a href="{{ url('/admin/users') }}" class="list-group-item">

        <div class="list-icon socialite-icon {{ Request::segment(2) == 'users' ? 'active' : '' }}">
            <i class="fa fa-user-plus"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.manage_users') }}
            <div class="text-muted">
                {{ trans('common.manage_users_text') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a>

     <a href="{{ url('/admin/events') }}" class="list-group-item">

        <div class="list-icon socialite-icon {{ Request::segment(2) == 'events' ? 'active' : '' }}">
            <i class="fa fa-calendar-o"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.manage_events') }}
            <div class="text-muted">
                {{ trans('common.manage_events_text') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a>
    <a href="{{ url('/admin/manage-reports') }}" class="list-group-item">

        <div class="list-icon socialite-icon {{ Request::segment(2) == 'manage-reports' ? 'active' : '' }}">
            <i class="fa fa-bug"></i>
        </div>
        
        <div class="list-text">
            @if(Auth::user()->getReportsCount() > 0)
            <span class="badge pull-right">{{ Auth::user()->getReportsCount() }}</span>
            @endif            
            {{ trans('common.manage_reports') }}
            <div class="text-muted">
                {{ trans('common.manage_reports_text') }}
            </div>             
        </div>
        <span class="clearfix"></span>
    </a>   

   {{--  <a href="{{ url('/admin/manage-ads') }}" class="list-group-item">

        <div class="list-icon socialite-icon {{ Request::segment(2) == 'manage-ads' ? 'active' : '' }}">
            <i class="fa fa-send"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.manage_ads') }}
            <div class="text-muted">
                {{ trans('common.manage_ads_text') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a> --}}

     <a href="{{ url('/admin/manage-scouts') }}" class="list-group-item">

        <div class="list-icon socialite-icon {{ Request::segment(2) == 'manage-scouts' ? 'active' : '' }}">
            <i class="fa fa-user-plus"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ "Manage Scouts" }}
            <div class="text-muted">
                {{ "Create/view scout accounts" }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a>

    {{-- <a href="{{ url('/admin/get-env') }}" class="list-group-item">

        <div class="list-icon socialite-icon {{ Request::segment(2) == 'get-env' ? 'active' : '' }}">
            <i class="fa fa-cogs"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.environment_settings') }}
            <div class="text-muted">
                {{ trans('common.edit_on_risk') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a> --}}

</div>



