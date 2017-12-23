<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Setting;
use App\Timeline;
use Teepluss\Theme\Facades\Theme;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function getLogin()
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('guest');
        $theme->setTitle(trans('auth.login').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('login')->render();
    }

    public function login(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'email'    => 'required',
            'password' => 'required',
        ]);

        if (!$validate->passes()) {
            return response()->json(['status' => '201', 'message' => trans('auth.login_failed')]);
        } else {
            $user = '';
            $nameoremail = '';
            $canLogin = false;
            $remember = ($request->remember ? true : false);

            if (filter_var(($request->email), FILTER_VALIDATE_EMAIL)  == true) {
                $nameoremail = $request->email;
                $user = DB::table('users')->where('email', $request->email)->first();
            } else {
                $timeline = DB::table('timelines')->where('username', $request->email)->first();
                if ($timeline != null) {
                    $user = DB::table('users')->where('timeline_id', $timeline->id)->first();
                    if ($user) {
                        $nameoremail = $user->email;
                    }
                }
            }

            if (Setting::get('mail_verification') == 'off') {
                $canLogin = true;
            } else {
                if ($user != null) {
                    if ($user->email_verified == 1) {
                        $canLogin = true;
                    } else {
                        return response()->json(['status' => '201', 'message' => trans('messages.verify_mail')]);
                    }
                }
            }
        }

        if ($canLogin && Auth::attempt(['email' => $nameoremail, 'password' => $request->password], $remember)) {
            $color_code = User::find($user->id)->color_code;
            Session::put('color_code',$color_code);
            return response()->json(['status' => '200', 'message' => trans('auth.login_success')]);
        } else {
            return response()->json(['status' => '201', 'message' => trans('auth.login_failed')]);
        }
    }

    public function getReset() {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('guest');
        $theme->setTitle(trans('auth.login').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
        return $theme->scope('reset-password')->render();
    }

    public function postReset() {
       
    }
}
