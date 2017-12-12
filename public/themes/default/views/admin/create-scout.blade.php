<div class="panel panel-default">
@include('flash::message')
    <div class="panel-heading no-bg panel-settings">
        <h3 class="panel-title">
            {{ "Manage Scout Account" }}
        </h3>
    </div>
    {{-- {{ dd($scouts) }} --}}
    @if (Session::has('msg'))
         <br/>
        <div class="alert alert-success alert-dismissable" style="text-align: center;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {!! session('msg') !!}
        </div>
        <br/>
    @endif
    <br/>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#view_all">View all users</a></li>
        <li><a data-toggle="tab" href="#view_scouts">View Scout Accounts</a></li>
        <li><a data-toggle="tab" href="#create">Create new scout account</a></li>
    </ul>
    <div class="panel-body">
        
        <div class="tab-content">
            <div id="create" class="tab-pane fade">
                
                <form method="POST" class="mobile-align-center md-layout md-layout--column ft-login__wrapper md-align md-align--center-start" action="{{ url('admin/create-scout') }}">
                    {{ csrf_field() }}
                    <div class="md-layout layout-m-b-1 layout-m-b-1--register md-layout-spacer mobile-layout-column__register mobile-layout-column md-layout--row md-align md-align-start-center">
                        <div class="mail-form  form-group form-group__adjust">
                            <input class="form-control" id="username" required placeholder="{{ trans('auth.name') }}" name="username" type="text">
                        </div>
                        <div class="form-group form-group__adjust">
                            <input class="form-control" id="scout_code" required placeholder="Scout Code" name="scout_code" type="text">
                            <br/>
                            <input class="form-control" id="email" required placeholder="{{ trans('auth.email_address') }}" name="email" type="email" value="">
                        </div>
                    </div>

                    <div class="md-layout layout-m-b-1 layout-m-b-1--register md-layout-spacer mobile-layout-column__register mobile-layout-column md-layout--row md-align md-align-start-center">
                        <div class="mail-form  form-group form-group__adjust">
                            <input class="form-control" id="password" required placeholder="{{ trans('auth.password') }}" name="password" type="password">
                        </div>
                    </div>


                    <div class="md-layout md-layout-spacer mobile-layout-column__register-group mobile-layout-column md-layout--row md-align md-align-start-center" style="width: 100%">
                        <div class="mail-form  form-group form-group__adjust">
                            <input class="form-control" id="datepicker1" placeholder="Birthday" name="birthday" type="date">
                        </div>
                        <div class="form-group form-group__adjust">
                            <select class="form-control" id="gender" required name="gender">
                                <option value="">Gender</option>
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                            </select>
                        </div>
                    </div>

                    <div class="md-layout layout-m-t-0 layout-p-r-0" style="width: 100%">
                        <div class="md-layout-spacer"></div>
                        <div class="form-group mobile-full-width md-layout layout-m-l-1 layout-m-l-0--sm">
                            <button type="submit" id="submit" class="btn btn-primary btn-submit">{{ trans('auth.register') }}</button>
                        </div>
                    </div>

                </form>

            </div>
            <div id="view_all" class="tab-pane fade in active">
                @if(count($timelines) > 0)
            <div class="table table-responsive manage-table">
                <table class="table existing-products-table socialite">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>{{ trans('admin.id') }}</th> 
                            <th>{{ trans('auth.name') }}</th>
                            <th>Type</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timelines as $timeline)
                        <tr>
                            <td>&nbsp;</td> 
                            <td>{{ $timeline->user->id }}</td>
                            <td><a href="#"><img src=" @if($timeline->avatar_id != null) {{ url('user/avatar/'.$timeline->avatar->source) }} @else {{ url('user/avatar/default-'.$timeline->user->gender.'-avatar.png') }} @endif" alt="images"></a><a href="{{ url($timeline->username) }}"> {{ $timeline->name }}</a></td>
                            @if($timeline->user->custom_option1)
                                <td>Scout</td>
                            @else
                                @if($timeline->user->gender == 'male')
                                    <td>
                                        <form action="{{ url('admin/make-scout') }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="user_id" value="{{ $timeline->user->id }}">
                                            <button type="submit" class="btn btn-defualt btn-xs">Make him scout</button>
                                        </form>
                                    </td>
                                @else
                                    <td>
                                        <form action="{{ url('admin/make-scout') }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="user_id" value="{{ $timeline->user->id }}">
                                            <button type="submit" class="btn btn-defualt btn-xs">Make her scout</button>
                                        </form>
                                    </td>
                                @endif
                            @endif
                            <td>&nbsp;</td> 
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-holder userpage">
                    {{ $timelines->render() }}
                </div>  
            @else
                <div class="alert alert-warning">{{ trans('messages.no_users') }}</div>
            @endif
            </div>

            <div id="view_scouts" class="tab-pane fade">
                @if(count($scouts) > 1)
            <div class="table table-responsive manage-table">
                <table class="table existing-products-table socialite">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>{{ trans('admin.id') }}</th> 
                            <th>{{ trans('auth.name') }}</th>
                            <th>Scout Code</th>
                            <th>Users Connected</th>
                            <th>Total Amount Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scouts as $scout)
                        <tr>
                            <td>&nbsp;</td> 
                            <td>{{ $scout->id }}</td>
                            <td><a href="#"><img src=" @if($scout->timeline->avatar_id != null) {{ url('user/avatar/'.$scout->timeline->avatar->source) }} @else {{ url('user/avatar/default-'.$scout->gender.'-avatar.png') }} @endif" alt="images"></a><a href="{{ url($scout->timeline->username) }}"> {{ $scout->timeline->name }}</a></td>
                            <td>{{ $scout->timeline->username }}</td>
                            <td>{{ $scout->user_count }}</td>
                            <td>{{ $scout->amount_spent }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>  
            @else
                <div class="alert alert-warning">{{ trans('messages.no_users') }}</div>
            @endif
            </div>

        </div>

	</div>
</div><!-- /panel -->