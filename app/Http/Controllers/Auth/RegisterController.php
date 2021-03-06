<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Media;
use File;
use Storage;
use App\Setting;
use App\Timeline;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;
use Teepluss\Theme\Facades\Theme;
use Validator;
use URL;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $captcha = null)
    {
        $messages = [
            'no_admin' => 'The name admin is restricted for :attribute'
        ];
        $rules = [
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|min:6',
            'gender'    => 'required',
            'username'  => 'required|max:25|min:3|alpha_num|unique:timelines|no_admin',
            'affiliate' => 'exists:timelines,username',
        ];

        if ($captcha) {
            $messages = ['g-recaptcha-response.required' => trans('messages.captcha_required')];
            $rules['g-recaptcha-response'] = 'required';
        }

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $timeline = Timeline::create([
            'username' => $data['username'],
            'name'     => $data['username'],
        ]);

        return User::create([
            'email'       => $data['email'],
            'password'    => bcrypt($data['password']),
            'timeline_id' => $timeline->id,
        ]);
    }

    public function register()
    {
        if (Auth::user()) {
            return Redirect::to('/');
        }

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('guest');
        $theme->setTitle(trans('auth.register').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('register')->render();
    }

    protected function registerUser(Request $request, $socialLogin = false)
    {
        if (Setting::get('captcha') == 'on' && !$socialLogin) {
            $validator = $this->validator($request->all(), true);
        } else {
            $validator = $this->validator($request->all());
        }

        if ($validator->fails()) {
            return response()->json(['status' => '201', 'err_result' => $validator->errors()->toArray()]);
        }

        if ($request->affiliate) {
            $timeline = Timeline::where('username', $request->affiliate)->first();
            $affiliate_id = $timeline->user->id;
        } else {
            $affiliate_id = null;
        }

        //Create timeline record for the user
        $timeline = Timeline::create([
            'username' => $request->username,
            'name'     => $request->username,
            'type'     => 'user',
            'about'    => 'Hi, I am on Fitmetix.'
        ]);
        $a = $request->social;
        $b = $request->avatar;
        if($request->social != '' && $request->avatar != '') {
            $file_path = json_decode(file_get_contents($b.'&redirect=false'), TRUE);
            $file_actual_url  = $file_path['data']['url'];
            $fileContents = file_get_contents($file_actual_url);
            $path_ = public_path().'/temp/temp'.$timeline->id.'.jpg';
            File::put($path_, $fileContents);
            $change_avatar = Image::make($path_);
            $strippedName = 'userfromfb';
            // Lets resize the image to the square with dimensions of either width or height , which ever is smaller.
            list($width, $height) = getimagesize($path_);
            $avatar_thumbnail = $change_avatar;
            $avatar = $change_avatar;
            $mime = $avatar->mime();
            if ($mime == 'image/jpeg')
                $extension = '.jpg';
            elseif ($mime == 'image/png')
                $extension = '.png';
            elseif ($mime == 'image/gif')
                $extension = '.gif';
            else
                $extension = '';
            $photoName = hexdec(uniqid()).'_'.str_replace('.','',microtime(true)).$timeline->id.$extension;
            $photoName_thumbnail = '100_'.$photoName;

            if ($width > $height) {
                $avatar->crop($height, $height);
                $avatar_thumbnail->crop($height, $height);
            } else {
                $avatar->crop($width, $width);
                $avatar_thumbnail->crop($width, $width);
            }
            $avatar->resize(600, 600);

            $avatar->save(storage_path().'/uploads/users/avatars/'.$photoName, 100);

            $avatar_thumbnail->resize(100, 100);
            $avatar_thumbnail->save(storage_path().'/uploads/users/avatars/'.$photoName_thumbnail, 100);

            $media = Media::create([
                'title'  => $photoName,
                'type'   => 'image',
                'source' => $photoName,
            ]);
            $timeline->avatar_id = $media->id;
            $timeline->save();
            Storage::delete($path_);
        }
        if(Setting::get('mail_verification') == 'off')
        {
            $mail_verification = 1;
        }
        else
        {
            $mail_verification = null;
        }
        //Create user record
        $user = User::create([
            'email'             => $request->email,
            'password'          => bcrypt($request->password),
            'timeline_id'       => $timeline->id,
            'gender'            => $request->gender,
            'affiliate_id'      => $affiliate_id,
            'verification_code' => str_random(30),
            'remember_token'    => str_random(10),
            'email_verified'    => $mail_verification
        ]);
        if (Setting::get('birthday') == 'on' && $request->birthday != '') {
            $user->birthday = date('Y-m-d', strtotime($request->birthday));
            $user->save();
        }

        if (Setting::get('city') == 'on' && $request->city != '') {
            $user->city = $request->city;
            $user->save();
        }

        $user->name = $timeline->name;
        $user->email = $request->email;

        //saving default settings to user settings
        $user_settings = [
            'user_id'               => $user->id,
            'confirm_follow'        => 'yes',
            'follow_privacy'        => 'everyone',
            'comment_privacy'       => 'everyone',
            'timeline_post_privacy' => 'everyone',
            'post_privacy'          => 'everyone',
            'message_privacy'       => 'everyone',
            'email_follow'          => 'yes',
            'email_like_post'       => 'yes',
            'email_post_share'      => 'yes',
            'email_comment_post'    => 'yes',
            'email_like_comment'    => 'yes',
            'email_reply_comment'   => 'yes',
            'email_join_group'      => 'yes',
            'email_like_page'       => 'yes',
        ];

        //Create a record in user settings table.
        $userSettings = DB::table('user_settings')->insert($user_settings);

        Auth::login($user);

        if ($user) {
            if ($socialLogin) {
                return $timeline;
            } else {
                $chk = '';
                if (Setting::get('mail_verification') == 'on') {
                    $chk = 'on';
                    Mail::send('emails.welcome', ['user' => $user], function ($m) use ($user) {
                        $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                        $m->to($user->email, $user->name)->subject('Welcome to '.Setting::get('site_name'));
                    });
                }
                $url=URL::to('/');
                return response()->json(['status' => '200', 'message' => trans('auth.verify_email'), 'emailnotify' => $chk,'url' => $url]);
            }
        }
    }

    public function verifyEmail(Request $request)
    {
        $user = User::where('email', '=', $request->email)->where('verification_code', '=', $request->code)->first();

        if ($user->email_verified) {
            return Redirect::to('login')
                ->with('login_notice', trans('messages.verified_mail'));
        } elseif ($user) {
            $user->email_verified = 1;
            $user->update();
            return Redirect::to('login')
                ->with('login_notice', trans('messages.verified_mail_success'));
        } else {
            echo trans('messages.invalid_verification');
        }
    }

    public function facebookRedirect()
    {
        $a = 0;
        return Socialite::with('facebook')->redirect();
    }

    // to get authenticate user data
    public function facebook()
    {
        $a = 0;
        $user_model = new User();
        $facebook_user = Socialite::with('facebook')->user();
        if(!isset($facebook_user->test)) {
            $a = 0;
        }
        $data = array();
        $data = $facebook_user->user;
        $data['social'] = TRUE;
        $data['avatar'] = $facebook_user->avatar_original;
        if(!isset($data['email'])) {
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('guest');
            $theme->setTitle(trans('auth.register').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
            return $theme->scope('register',compact('data'))->render();
        }
        else {
            $user_info = $user_model->where('email','=',$data['email'])->get()->toArray();
            if(empty($user_info)) {
                $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('guest');
                $theme->setTitle(trans('auth.register').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
                return $theme->scope('register',compact('data'))->render();
            }
            else {
                $user = User::firstOrNew(['email' => $data['email']]);
                Auth::login($user,TRUE);
                return redirect('/');
            }
        }


        /*if ($email == null) {
            $email = $facebook_user->id.'@facebook.com';
        }

        $user = User::firstOrNew(['email' => $email]);

        if ($facebook_user->name != null) {
            $name = $facebook_user->name;
        } else {
            $name = $email;
        }

        if (!$user->id) {
            $request = new Request(['username' => $facebook_user->id,
              'name'                           => $name,
              'email'                          => $email,
              'password'                       => bcrypt(str_random(8)),
              'gender'                         => 'other',
            ]);

            $timeline = $this->registerUser($request, true);
            //  Prepare the image for user avatar
            if ($facebook_user->avatar != null) {

                $fileContents = file_get_contents($facebook_user->getAvatar());
                $photoName = date('Y-m-d-H-i-s').str_random(8).'.png';
                File::put(storage_path() . '/uploads/users/avatars/' . $photoName, $fileContents);

                $media = Media::create([
                        'title'  => $photoName,
                        'type'   => 'image',
                        'source' => $photoName,
                      ]);
                $timeline->avatar_id = $media->id;
                $timeline->save();
            }

            $user = $timeline->user;
        } else {
            $timeline = $user->timeline;
        }


        if (Auth::loginUsingId($user->id)) {
            return redirect('/')->with(['message' => trans('messages.change_username_facebook'), 'status' => 'warning']);
        } else {
            return redirect($timeline->username)->with(['message' => trans('messages.user_login_failed'), 'status' => 'success']);
        }*/
    }

    public function googleRedirect()
    {
        return Socialite::with('google')->redirect();
    }

    // to get authenticate user data
    public function google()
    {
        $google_user = Socialite::with('google')->user();
        if (isset($google_user->user['gender'])) {
            $user_gender = $google_user->user['gender'];
        } else {
            $user_gender = 'other';
        }
        $user = User::firstOrNew(['email' => $google_user->email]);
        if (!$user->id) {
            $request = new Request(['username' => $google_user->id,
                'name'                           => $google_user->name,
                'email'                          => $google_user->email,
                'password'                       => bcrypt(str_random(8)),
                'gender'                         => $user_gender,
            ]);
            $timeline = $this->registerUser($request, true);

            //  Prepare the image for user avatar
            $avatar = Image::make($google_user->avatar);
            $photoName = date('Y-m-d-H-i-s').str_random(8).'.png';
            $avatar->save(storage_path().'/uploads/users/avatars/'.$photoName, 60);

            $media = Media::create([
                'title'  => $photoName,
                'type'   => 'image',
                'source' => $photoName,
            ]);

            $timeline->avatar_id = $media->id;

            $timeline->save();
            $user = $timeline->user;
        }

        if (Auth::loginUsingId($user->id)) {
            return redirect('/')->with(['message' => trans('messages.change_username_google'), 'status' => 'warning']);
        } else {
            return redirect($timeline->username)->with(['message' => trans('messages.user_login_failed'), 'status' => 'success']);
        }
    }

    public function twitterRedirect()
    {
        return Socialite::with('twitter')->redirect();
    }

    // to get authenticate user data
    public function twitter()
    {
        $twitter_user = Socialite::with('twitter')->user();

        $user = User::firstOrNew(['email' => $twitter_user->id.'@twitter.com']);
        if (!$user->id) {
            $request = new Request(['username'   => $twitter_user->id,
                'name'                           => $twitter_user->name,
                'email'                          => $twitter_user->id.'@twitter.com',
                'password'                       => bcrypt(str_random(8)),
                'gender'                         => 'other',
            ]);
            $timeline = $this->registerUser($request, true);
            //  Prepare the image for user avatar
            $avatar = Image::make($twitter_user->avatar_original);
            $photoName = date('Y-m-d-H-i-s').str_random(8).'.png';
            $avatar->save(storage_path().'/uploads/users/avatars/'.$photoName, 60);

            $media = Media::create([
                'title'  => $photoName,
                'type'   => 'image',
                'source' => $photoName,
            ]);

            $timeline->avatar_id = $media->id;

            $timeline->save();
            $user = $timeline->user;
        }

        if (Auth::loginUsingId($user->id)) {
            return redirect('/')->with(['message' => trans('messages.change_username_twitter').' <b>'.$user->email.'</b>', 'status' => 'warning']);
        } else {
            return redirect('login')->with(['message' => trans('messages.user_login_failed'), 'status' => 'error']);
        }
    }

    public function linkedinRedirect()
    {
        return Socialite::with('linkedin')->redirect();
    }

    // to get authenticate user data
    public function linkedin()
    {
        $linkedin_user = Socialite::with('linkedin')->user();

        $user = User::firstOrNew(['email' => $linkedin_user->email]);
        if (!$user->id) {
            $request = new Request(['username'   => preg_replace('/[^A-Za-z0-9 ]/', '', $linkedin_user->id),
                'name'                           => $linkedin_user->name,
                'email'                          => $linkedin_user->email,
                'password'                       => bcrypt(str_random(8)),
                'gender'                         => 'other',
            ]);

            $timeline = $this->registerUser($request, true);

            //  Prepare the image for user avatar
            $avatar = Image::make($linkedin_user->avatar_original);
            $photoName = date('Y-m-d-H-i-s').str_random(8).'.png';
            $avatar->save(storage_path().'/uploads/users/avatars/'.$photoName, 60);

            $media = Media::create([
                'title'  => $photoName,
                'type'   => 'image',
                'source' => $photoName,
            ]);

            $timeline->avatar_id = $media->id;

            $timeline->save();
            $user = $timeline->user;
        }

        if (Auth::loginUsingId($user->id)) {
            return redirect('/')->with(['message' => trans('messages.change_username_linkedin'), 'status' => 'warning']);
        } else {
            return redirect('login')->with(['message' => trans('messages.user_login_failed'), 'status' => 'error']);
        }
    }
}