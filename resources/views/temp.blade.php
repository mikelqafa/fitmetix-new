<div class="row visible-sm visible-xs" style="margin-top:-25px;">
    <ul class="nav nav-justified">
        @if(Auth::user()->hasRole('admin'))
            <li class="{{ Request::segment(1) == 'admin' ? 'active' : '' }}"><a style="color:#484848" href="{{ url('admin') }}"><i class="fa fa-user-secret" aria-hidden="true"></i>{{ trans('common.admin') }}</a></li>
        @endif
        <li><a style="color:#484848" href="{{ url('/'.Auth::user()->username.'/settings/general') }}"><i class="fa fa-bars"></i></a></li>
        <li><a style="color:#484848" href="{!! url(Auth::user()->username.'/create-event') !!}"><i class="fa fa-heart"></i></a></li>
        <li><a style="color:#484848" href="{!! url('messages') !!}"><i class="fa fa-commenting-o"></i></a></li>
        <li><a style="color:#484848" href="{!! url('events') !!}"><i class="fa fa-users"></i></a></li>
        <li><a style="color:#484848" data-toggle="collapse" href="#bs-example-navbar-collapse-4" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-search"></i></a></li>
        <li><a style="color:#484848" href="{!! url(Auth::user()->username) !!}"><i class="fa fa-user"></i></a></li>
    </ul>
</div>




<div id="myModal" class="modal fade" role="dialog" tabindex='-1'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">{{ trans('common.copy_embed_post') }}</h3>
                </div>
        <textarea class="form-control" rows="3">
          <iframe src="{{ url('/share-post/'.$post->id) }}" width="600px" height="420px" frameborder="0"></iframe>
          </textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
            </div>
        </div>
    </div>
</div>