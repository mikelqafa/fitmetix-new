<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Event;
use App\Group;
use App\Hashtag;
use App\Http\Requests;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Media;
use App\Notification;
use App\Page;
use App\Repositories\UserRepository;
use App\Role;
use App\Setting;
use App\Timeline;
use App\User;
use App\Wallpaper;
use Auth;
use Carbon\Carbon;
use Flash;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Teepluss\Theme\Facades\Theme;
use Validator;
use App;

class UserController extends AppBaseController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo, Request $request)
    {
        $this->request = $request;
        $this->checkCensored();

        $this->userRepository = $userRepo;
        $this->middleware('disabledemo', ['only' => 'deleteMe']);
    }

    protected function checkCensored()
    {
        $messages['not_contains'] = 'The :attribute must not contain banned words';
        if($this->request->method() == 'POST') {
            // Adjust the rules as needed
            $this->validate($this->request, 
                [
                  'first_name'    => 'not_contains',
                  'last_name'     => 'not_contains',
                  'username'      => 'not_contains',
                  'name'          => 'not_contains',
                  'about'         => 'not_contains',
                  'title'         => 'not_contains',
                  'description'   => 'not_contains',
                  'tag'           => 'not_contains',
                  'email'         => 'not_contains',
                  'body'          => 'not_contains',
                  'link'          => 'not_contains',
                  'address'       => 'not_contains',
                  'website'       => 'not_contains',
                  'display_name'  => 'not_contains',
                  'key'           => 'not_contains',
                  'value'         => 'not_contains',
                  'subject'       => 'not_contains',
                  'country'       => 'not_contains',
                  'city'          => 'not_contains',
                  'email'         => 'email',
                ],$messages);
        }
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $users = $this->userRepository->all();

        return view('users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param int               $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $user = $this->userRepository->update($request->all(), $id);

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }

    public function userGeneralSettings($username)
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.general_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/settings/general', compact('username'))->render();
    }

    public function userPrivacySettings($username)
    {
        $timeline = Timeline::where('username', $username)->first();

        if ($timeline == null) {
            return Redirect::to('/');
        }
        $blockList = DB::table('user_blocked')->where('blocker_uid',Auth::user()->id)->get()->toArray();
        $privacySettings = DB::table('user_settings')->where('user_id', $timeline->user->id)->first();
        $settings['blocklist'] = $blockList;
        $settings['privacy'] = $privacySettings;
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.privacy_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/settings/privacy', compact('settings'))->render();
    }

    public function userPasswordSettings($username)
    {
        $timeline = Timeline::where('username', $username)
        ->get()->toArray();

        if ($timeline == null) {
            return Redirect::to('/');
        }

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        return $theme->scope('users/settings/password')->render();
    }

    public function affliates($username)
    {
        $referrals = User::where('affiliate_id', Auth::user()->id)->where('id', '!=', Auth::user()->id)->paginate(Setting::get('items_page', 10));

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.affiliates').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/affliates', compact('referrals'))->render();
    }

    public function deactivate($username)
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.deactivate_account').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/deactivate')->render();
    }

    public function deleteMe($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        $user = $timeline->user;

        // Announcements delete
        $user->announcements()->detach();

        //Deleting post related data for the user
        $user->postLikes()->detach();
        $user->postFollows()->detach();
        $user->postShares()->detach();
        $user->postReports()->detach();
        $user->postTags()->detach();

        //other user posts on logedin user timeline
        $user->deleteOthers();

        foreach ($user->posts()->get() as $post) {
            $post->deleteMe();
        }
        
        //Deleting page related data for the user
        $user->pageLikes()->detach();
        $admin_role_id = Role::where('name', '=', 'admin')->first();

        
        foreach ($user->pages()->get() as $page) {
            if (count($page->users()
              ->where('page_user.role_id', $admin_role_id->id)
              ->where('page_user.user_id', $user->id)
              ->get()) != 0) {
                $page->users()->detach();
                $page->likes()->detach();
                $page->timeline->reports()->detach();
                //Deleting page notifications
                $timeline_alerts = $page->timeline()->with('notifications')->first();
                if (count($timeline_alerts->notifications) != 0) {
                    foreach ($timeline_alerts->notifications as $notification) {
                        $notification->delete();
                    }
                }
                
                //Deleting page posts
                $timeline_posts = $page->timeline()->with('posts')->first();
                if (count($timeline_posts->posts) != 0) {
                    foreach ($timeline_posts->posts as $post) {
                        $post->deleteMe();
                    }
                }
                $page_timeline = $page->timeline();

                //Deleting page albums
                foreach ($page->timeline->albums()->get() as $album) {
                    foreach ($album->photos()->get() as $media) {
                        $saveMedia = $media;
                        $album->photos()->detach($media->id);
                    }
                    $album->delete();
                }
                $page->delete();
                $page_timeline->delete();
            } else {
                $user->pages()->detach($page->id);
            }
        }
        
        //Deleting comments
        $user->commentLikes()->detach();

        foreach ($user->comments()->get() as $comment) {
            $comment->comments_liked()->detach();
            $dependencies = Comment::where('parent_id', $comment->id)->update(['parent_id' => null]);
            // $comment->replies()->detach();
            $comment->update(['parent_id' => null]);
            $comment->delete();
        }

        //Deleting Events
        $user->events()->detach();

        foreach ($user->userEvents()->get() as $event) {
            $event->users()->detach();
            $event->timeline->reports()->detach();
            // Deleting event posts
            $event_posts = $event->timeline()->with('posts')->first();

            if (count($event_posts->posts) != 0) {
                foreach ($event_posts->posts as $post) {
                    $post->deleteMe();
                }
            }

            //Deleting event notifications
            $timeline_alerts = $event->timeline()->with('notifications')->first();

            if (count($timeline_alerts->notifications) != 0) {
                foreach ($timeline_alerts->notifications as $notification) {
                    $notification->delete();
                }
            }

            $event_timeline = $event->timeline();

            //Deleting event albums
            foreach ($event->timeline->albums()->get() as $album) {
                foreach ($album->photos()->get() as $media) {
                    $saveMedia = $media;
                    $album->photos()->detach($media->id);
                }
                $album->delete();
            }
            $event->delete();
            $event_timeline->delete();
        }

        //Deleting top friends, followers and following
        $user->followers()->detach();
        $user->following()->detach();

        //Deleting notifications for the user
        $user->notifications()->delete();
        $user->notifiedBy()->delete();
        
        //Deleting timeline and related data
        $user->timelineReports()->detach();
        $timeline->reports()->detach();

        //Deleting user albums
        foreach ($timeline->albums()->get() as $album) {
            foreach ($album->photos()->get() as $media) {
                $saveMedia = $media;
                $album->photos()->detach($media->id);
            }
            $album->delete();
        }

        //Deleting subscriptions,participants, messages and user settings
        $participants = DB::table('participants')->where('user_id', $user->id)->get();
        if(!$participants->isEmpty())
        {
            foreach($participants as $participant)
            {
                $users = DB::table('participants')->where('thread_id',$participant->thread_id)->where('user_id','!=', $user->id)->get();
                if($users->count() == 1)
                {
                    DB::table('participants')->where('thread_id',$participant->thread_id)->delete();
                    DB::table('messages')->where('thread_id',$participant->thread_id)->delete();
                    DB::table('threads')->where('id',$participant->thread_id)->delete();
                }
                elseif($users->count() > 1)
                {
                    DB::table('participants')->where('thread_id',$participant->thread_id)
                                             ->where('user_id',$user->id)
                                             ->delete();
                    $owner = DB::table('messages')->where('thread_id',$participant->thread_id)
                                                  ->where('user_id',$user->id)
                                                  ->get();
                    if(!$owner->isEmpty())
                    {
                        DB::table('messages')->where('thread_id',$participant->thread_id)
                                             ->where('user_id',$user->id)
                                             ->update(['user_id' => $users->first()->user_id]);
                    }
                }
            }
        }

        DB::table('user_settings')->where('user_id', $user->id)->delete();

        //Deleting groups
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        foreach ($user->groups()->get() as $group) {
            if (count($group->users()
              ->where('group_user.role_id', $admin_role_id->id)
              ->where('group_user.user_id', $user->id)
              ->get()) != 0) {
                //Deleting events in a group
                if (count($group->getEvents()) != 0) {
                    foreach ($group->getEvents() as $event) {
                        $event->users()->detach();
                        $event->timeline->reports()->detach();
                         // Deleting event posts
                        $event_posts = $event->timeline()->with('posts')->first();

                        if (count($event_posts->posts) != 0) {
                            foreach ($event_posts->posts as $post) {
                                $post->deleteMe();
                            }
                        }

                        //Deleting event notifications
                        $timeline_alerts = $event->timeline()->with('notifications')->first();

                        if (count($timeline_alerts->notifications) != 0) {
                            foreach ($timeline_alerts->notifications as $notification) {
                                $notification->delete();
                            }
                        }

                        $event_timeline = $event->timeline();
                        $event->delete();
                        $event_timeline->delete();
                    }
                }
                $group->users()->detach();
                
                //Deleting group notifications
                $timeline_alerts = $group->timeline()->with('notifications')->first();
                
                if (count($timeline_alerts->notifications) != 0) {
                    foreach ($timeline_alerts->notifications as $notification) {
                        $notification->delete();
                    }
                }
                
                //Deleting group posts
                $timeline_posts = $group->timeline()->with('posts')->first();
                
                if (count($timeline_posts->posts) != 0) {
                    foreach ($timeline_posts->posts as $post) {
                        $post->deleteMe();
                    }
                }
                $group->timeline->reports()->detach();
                $group_timeline = $group->timeline();

                //Deleting group albums
                foreach ($group->timeline->albums()->get() as $album) {
                    foreach ($album->photos()->get() as $media) {
                        $saveMedia = $media;
                        $album->photos()->detach($media->id);
                    }
                    $album->delete();
                }
                $group->delete();
                $group_timeline->delete();
            } else {
                $user->groups()->detach($group->id);
            }
        }
        
        //Finally delete timeline and then the user
        $user->delete();
        $timeline->delete();
        
        
        return redirect('/admin/users');
    }

    public function emailNotifications($username)
    {
        $timeline = Timeline::where('username', $username)->with('user')->first();
        $user = $timeline->user;
        $userSettings = $user->getUserSettings($user->id);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.email_notifications').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/notifications', compact('userSettings'))->render();
    }

    public function updateEmailNotifications($username, Request $request)
    {
        if (Config::get('app.env') == 'demo' && $request->username == 'bootstrapguru') {
            Flash::error(trans('common.disabled_on_demo'));

            return Redirect::back();
        }
        $timeline = Timeline::where('username', $username)->with('user')->first();
        $user = $timeline->user;
        $input = $request->except('_token');

        $user_settings = [
                            'email_follow'        => $input['email_follow'],
                            'email_like_post'     => $input['email_like_post'],
                            'email_post_share'    => $input['email_post_share'],
                            'email_comment_post'  => $input['email_comment_post'],
                            'email_like_comment'  => $input['email_like_comment'],
                          ];

        $privacy = DB::table('user_settings')->where('user_id', $user->id)
                   ->update($user_settings);

        Flash::success(trans('messages.email_notifications_updated_success'));

        return Redirect::back();
    }

    public function SaveUserPrivacySettings($username, Request $request)
    {
        $timeline = Timeline::where('username', $username)->with('user')->first();
        $user = $timeline->user;
        $input = $request->except('_token');

        $confirm_follow = 'yes';
        $post_privacy = 'everyone';

        if($request->privacy_type == 'private'){
          $confirm_follow = 'no';
          $post_privacy = 'only_follow';
        }

        $user_settings = [
                            'confirm_follow'        => $confirm_follow,
                            'comment_privacy'       => 'everyone',
                            'follow_privacy'        => 'everyone',
                            'post_privacy'          => $post_privacy,
                            'timeline_post_privacy' => 'only_follow',
                            'message_privacy'       => 'everyone', ];

        $privacy = DB::table('user_settings')->where('user_id', $user->id)
                   ->update($user_settings);

        Flash::success(trans('messages.privacy_settings_updated_success'));

        return Redirect::back();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function generalSettingsValidator(array $data)
    {
        $messages = [
            'no_admin' => 'The name admin is restricted for :attribute'
        ];
        return Validator::make($data, [
            'username' => 'required|max:16|min:5|alpha_num|no_admin|unique:timelines,username,'.Auth::user()->timeline->id,
            'name'     => 'required',
            'email'    => 'unique:users,email,'.Auth::id(),
        ],$messages);

    }

    public function saveUserGeneralSettings(Request $request)
    {
        if (Config::get('app.env') == 'demo' && $request->username == 'bootstrapguru') {
            Flash::error(trans('common.disabled_on_demo'));

            return Redirect::back();
        }

        $data = $request->all();
        $data['username'] = $request->new_username;
        $validator = $this->generalSettingsValidator($data);
        if ($validator->fails()) {
            return redirect()->back()
            ->withInput($request->all())
            ->withErrors($validator->errors());
        }
        $user = User::find(Auth::user()->id);
        $timeline = Timeline::find($user->timeline_id);
        $timeline->update([
                            'username' => $data['username'],
                            'name'     => $data['name'],
                            'about'    => $data['about'],
                            ]);

        $user_details = $request->except('username', 'name', 'about');
        $user_details['birthday'] = date('Y-m-d', strtotime($request->birthday));
        $user->update($user_details);

        Flash::success('Updated successfully');

        return redirect()->back();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function passwordValidator(array $data)
    {
        return Validator::make($data, [
            'new_password'     => 'required|min:6',
            'current_password' => 'required|min:6',
        ]);
    }

    public function saveNewPassword(Request $request)
    {
        if (Config::get('app.env') == 'demo' && $request->username == 'bootstrapguru') {
            Flash::error(trans('common.disabled_on_demo'));

            return Redirect::back();
        }

        $validator = $this->passwordValidator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
            ->withInput($request->all())
            ->withErrors($validator->errors());
        }

        $user = User::findOrFail(Auth::user()->id);
        if (Hash::check($request->current_password, Auth::user()->password)) {
            if ($request->current_password != $request->new_password) {
                $user->password = bcrypt($request->new_password);
                $user->save();
                Flash::success(trans('messages.new_password_updated_success'));

                return redirect()->back();
            } else {
                Flash::error(trans('messages.password_no_match'));

                return redirect()->back()->with('Password not match.');
            }
        } else {
            Flash::error(trans('messages.old_password_no_match'));

            return redirect()->back()->with('Password not match.');
        }
    }

    public function messages($username)
    {
        $timeline = Timeline::where('username', $username)->with('user')->first();
        $user = $timeline->user;
        $trending_tags = Hashtag::orderBy('count', 'desc')->get()->random(Setting::get('min_items_page', 5));
        $following = $user->following()->get();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.messages').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/messages', compact('following', 'trending_tags'))->render();
    }

    public function membersList($username)
    {
        $timeline = Timeline::where('username', $username)->with('groups')->first();
        $group = $timeline->groups;
        $group_members = $group->members();
        $group_events = $group->getEvents($group->id);
        $ongoing_events = $group->getOnGoingEvents($group->id);
        $upcoming_events = $group->getUpcomingEvents($group->id);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.add_members').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/addmembers', compact('timeline', 'group', 'group_members', 'group_events', 'ongoing_events', 'upcoming_events'))->render();
    }

    public function pageMembersList($username)
    {
        // if($username != Auth::user()->username) {
        //     return Redirect('/'.$username);
        // }
        $timeline = Timeline::where('username', $username)->with('page')->first();
        $page = $timeline->page;
        $page_members = $page->members();
        if (!$page->is_admin(Auth::user()->id)) {
            return redirect($username);
        }

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.add_members').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/addpagemembers', compact('timeline', 'page', 'page_members'))->render();
    }

    public function getUsersJoin(Request $request)
    {
        $timelines = Timeline::where('username', 'like', "%{$request->searchname}%")->where('type', 'user')->where('username', '!=', Auth::user()->username)->get();
        $group_id = $request->group_id;
        $group = Group::findOrFail($request->group_id);

        $users = new \Illuminate\Database\Eloquent\Collection();


        foreach ($timelines as $key => $timeline) {
            $user = $timeline->user()->with(['groups' => function ($query) use ($group_id) {
                $query->where('groups.id', $group_id);
            }])->get();

            $users->add($user);
        }

        return response()->json(['status' => '200', 'data' => $users]);
    }

    public function getMembersJoin(Request $request)
    {
        $timelines = Timeline::where('username', 'like', "%{$request->searchname}%")->where('type', 'user')->where('username', '!=', Auth::user()->username)->get();
        $page_id = $request->page_id;
        $page = Page::find($request->page_id);

        $users = new \Illuminate\Database\Eloquent\Collection();


        foreach ($timelines as $key => $timeline) {
            $user = $timeline->user()->with(['pages' => function ($query) use ($page_id) {
                $query->where('pages.id', $page_id);
            }])->get();

            $users->add($user);
        }

        return response()->json(['status' => '200', 'data' => $users]);
    }

    public function getUsersMentions(Request $request)
    {
        $requestData = $request->all();
        $timelines = Timeline::where('type','user')
        ->where(function ($query) use ($requestData){
                            $query->where('username', 'like', "%{$requestData['query']}%")->
                            orWhere('name', 'like', "%{$requestData['query']}%");
                })->limit(5)->get();

        $users = $timelines;
        foreach ($timelines as $key => $value) {
            if ($value->avatar != null) {
                $users[$key]['image'] = url('user/avatar/'.$value->avatar->source);
            } else {
                $gender = isset($value->user) ? $value->user->gender : 'male';
                $users[$key]['image'] = url('user/avatar/default-'.$gender.'-avatar.png');
            }
        }

        return response()->json($users);
    }

    public function addingMembersGroup(Request $request)
    {
        $group = Group::findOrFail($request->group_id);

        if ($request->user_status == 'Joined') {
            $group->users()->detach([$request->user_id]);

            return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully unjoined']);
        } else {
            $chkUser = $group->chkGroupUser($request->group_id, $request->user_id);

            if ($chkUser) {
                $group_user = $group->updateStatus($chkUser->id);
                if ($group_user) {
                    return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully accepted']);
                }
            } else {
                $user_role = Role::where('name', '=', 'user')->first();
                $group->users()->attach($request->user_id, ['group_id' => $request->group_id, 'role_id' => $user_role->id, 'status' => 'approved']);

                return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully added']);
            }
        }
    }

    // public function addingMembersPage(Request $request)
    // {
    //     $page = Page::findOrFail($request->group_id);

    //         if ($request->user_status == "Joined")
    //         {
    //             $page->users()->detach([$request->user_id]);
    //             return response()->json(['status' => '200','added' => true,'message'=>'successfully unjoined']);
    //         }
    //         else
    //         {
    //             $chkUser = $page->chkPageUser($request->group_id,$request->user_id);

    //             if($chkUser)
    //             {
    //                 $page_user = $page->updateStatus($chkUser->id);
    //                 if($page_user)
    //                 return response()->json(['status' => '200','added' => true,'message'=>'successfully accepted']);
    //             }
    //             else
    //             {
    //               $user_role = Role::where('name','=','user')->first();
    //               $page->users()->attach($request->user_id, array('page_id'=>$request->page_id,'role_id'=>$user_role->id,'status'=>'approved'));
    //               return response()->json(['status' => '200','added' => true,'message'=>'successfully added']);
    //             }

    //         }
    // }

    public function addingMembersPage(Request $request)
    {
        $page = Page::find($request->page_id);

        if ($request->user_status == 'Joined') {
            $page->users()->detach([$request->user_id]);

            return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully unjoined']);
        } else {
            $page->users()->attach($request->user_id, ['page_id' => $request->page_id, 'role_id' => 2, 'active' => 1]);

            return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully added']);
        }
    }

    public function followers($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups', 'user.followers')->first();
        $user = $timeline->user;
        $joined_groups_count = $user->groups()->where('role_id', '!=', 1)->where('status', '=', 'approved')->get()->count();
        $followers = $user->followers()->where('status', '=', 'approved')->get();
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                                ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.followers').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/followers', compact('timeline', 'user', 'followers', 'followRequests', 'own_groups', 'own_pages', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'user_events', 'guest_events'))->render();
    }

    public function following($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups')->first();
        $user = $timeline->user;
        $following = $user->following()->where('status', '=', 'approved')->get();
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $joined_groups_count = $user->groups()->where('role_id', '!=', 1)->where('status', '=', 'approved')->get()->count();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                                ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.following').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/following', compact('timeline', 'user', 'following', 'followRequests', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'own_pages', 'own_groups', 'user_events', 'guest_events'))->render();
    }

    public function getGuestEvents($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups')->first();
        $user = $timeline->user;
        
        $following = $user->following()->where('status', '=', 'approved')->get();
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $joined_groups_count = $user->groups()->where('role_id', '!=', 1)->where('status', '=', 'approved')->get()->count();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                                ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();
        
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.following').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/guestevents', compact('timeline', 'user', 'following', 'followRequests', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'own_pages', 'own_groups', 'user_events', 'guest_events'))->render();
    }

    public function likedPages($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups')->first();
        $user = $timeline->user;
        $liked_pages = $user->pageLikes;
        $joined_groups_count = $user->groups()->where('role_id', '!=', 1)->where('status', '=', 'approved')->get()->count();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                                ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.liked_pages').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/liked-pages', compact('timeline', 'liked_pages', 'user', 'followRequests', 'own_pages', 'own_groups', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'user_events', 'guest_events'))->render();
    }

    public function joinedGroups($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups')->first();
        $user = $timeline->user;
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        $joined_groups = $user->groups()->where('role_id', '!=', $admin_role_id->id)->where('status', '=', 'approved')->get();
        $joined_groups_count = $joined_groups->count();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                                ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.joined_groups').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/joined-groups', compact('timeline', 'user', 'joined_groups', 'followRequests', 'own_groups', 'own_pages', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'user_events', 'guest_events'))->render();
    }

    public function followRequests($username)
    {
        $timeline = Timeline::where('username', $username)->with('user', 'user.pageLikes', 'user.groups')->first();
        $user = $timeline->user;
        $followRequests = $user->followers()->where('status', '=', 'pending')->get();
        $joined_groups_count = $user->groups()->where('role_id', '!=', 1)->where('status', '=', 'approved')->get()->count();
        $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
        $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
        $follow_user_status = '';
        $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                                ->where('leader_id', '=', $user->id)->first();

        if ($follow_user_status) {
            $follow_user_status = $follow_user_status->status;
        }

        $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
        $follow_confirm = $confirm_follow_setting->confirm_follow;

        $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
        $privacy_settings = explode('-', $live_user_settings);
        $timeline_post = $privacy_settings[0];
        $user_post = $privacy_settings[1];
        $own_pages = $user->own_pages();
        $own_groups = $user->own_groups();
        $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
        $guest_events = $user->getEvents();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.follow_requests').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/follow-requests', compact('timeline', 'user', 'followRequests', 'own_groups', 'own_pages', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'joined_groups_count', 'user_events', 'guest_events'))->render();
    }

    public function acceptFollowRequest(Request $request)
    {
        $user = User::find($request->user_id);
        $follow_user = $user->updateFollowStatus($request->user_id);

        $user_settings = $user->getUserSettings($user->id);

        if ($follow_user) {
            Flash::success(trans('messages.request_accepted'));
        }

        //Notify the user for accepting the follow request
        App::setLocale($user->language);
        Notification::create(['user_id' => $request->user_id, 'timeline_id' => $user->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.accepted_follow_request'), 'type' => 'accept_follow_request', 'link' => Auth::user()->username.'/followers']);
        App::setLocale(Auth::user()->language);


        $follow_user = Auth::user();
        $user_url = 'fitmetix.com/'.$follow_user->username;

        if ($user_settings && $user_settings->email_follow == 'yes') {
            Mail::send('emails.followmail', ['user' => $user, 'follow' => $follow_user,'user_url'=>$user_url], function ($m) use ($user) {
                $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                $m->to($user->email, $user->name)->subject(Auth::user()->name.' '.trans('common.follows_you'));
          });
        }

        return response()->json(['status' => '200', 'accepted' => true, 'message' => 'follow request successfully accepted']);
    }

    public function rejectFollowRequest(Request $request)
    {
        $user = User::find($request->user_id);


        $follow_user = $user->decilneRequest($request->user_id);

        if ($follow_user) {
            Flash::success(trans('messages.request_rejected'));
        }

        return response()->json(['status' => '200', 'rejected' => true, 'message' => 'follow request successfully denied']);
    }

    public function changeNotificationType(Request $request) {
        $notification = Notification::find($request->notification_id);
        $notification->type = $request->type;
        $notification->save();
        return response()->json(['status' => '200', 'message' => 'Notification type changed']);   
    }

    public function getNotifications(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->with('notified_from')->latest()->limit($request->paginate)->offset($request->offset)->get();

        return response()->json(['status' => '200', 'notifications' => $notifications]);
    }

    public function getUnreadNotifications()
    {
        $notifications = Notification::where('seen', 0)->where('user_id', Auth::user()->id)->count();

        return response()->json(['status' => '200', 'unread_notifications' => $notifications]);
    }

    public function getUsersModal(Request $request)
    {
        $users = User::whereIn('id', explode(',', $request->user_ids))->get();
        $heading = isset($request->heading) ? $request->heading : 'Users';

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $responseHtml = $theme->partial('modal', ['users' => $users, 'heading' => $heading]);

        return response()->json(['status' => '200', 'responseHtml' => $responseHtml]);
    }

    public function getEventGuests($username)
    {
        $timeline = Timeline::where('username', $username)->with('event')->first();
        $event = $timeline->event;
        $event_guests = $event->users();

        if (!$event->is_eventadmin(Auth::user()->id, $event->id)) {
            return redirect($username);
        }

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.invitemembers').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('users/inviteguests', compact('timeline', 'event', 'event_guests'))->render();
    }

    public function getMembersInvite(Request $request)
    {
        $timelines = Timeline::where('username', 'like', "%{$request->searchname}%")->where('type', 'user')->where('username', '!=', Auth::user()->username)->get();
        $event_id = $request->event_id;
        $event = Event::find($request->page_id);

        $users = new \Illuminate\Database\Eloquent\Collection();


        foreach ($timelines as $key => $timeline) {
            $user = $timeline->user()->with(['events' => function ($query) use ($event_id) {
                $query->where('events.id', $event_id);
            }])->get();

            $users->add($user);
        }

        return response()->json(['status' => '200', 'data' => $users]);
    }

    public function addingEventMembers(Request $request)
    {
        $event = Event::find($request->event_id);

        if ($request->user_status == 'Invited') {
            $event->users()->detach([$request->user_id]);

            return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully unjoined']);
        } else {
            $event->users()->attach($request->user_id, ['event_id' => $request->event_id]);

            return response()->json(['status' => '200', 'added' => true, 'message' => 'successfully added']);
        }
    }

    public function movePhotos(Request $request)
    {
        if ($request->album_id == null) {
            Flash::error('Select the album to move the photos');
            return redirect()->back();
        }
        if ($request->photos == null) {
            Flash::error('Select atleast one photo');
            return redirect()->back();
        }
        $photo_ids = explode(",", $request->photos);
        foreach ($photo_ids as $photo) {
            $media = Media::find($photo);
            $media->albums()->detach();
            $media->albums()->attach($request->album_id);
        }
        Flash::success('Selected photos moved successfully');
        return redirect()->back();
    }

    public function deletePhotos(Request $request)
    {
        if ($request->photos == null) {
            Flash::error('Select atleast one photo');
            return redirect()->back();
        }
        $photo_ids = explode(",", $request->photos);
        foreach ($photo_ids as $photo) {
            $media = Media::find($photo);
            $media->albums()->where('preview_id', $media->id)->update(['preview_id' => null]);
            $media->albums()->detach();
            $media->delete();
        }
        Flash::success('Selected photos deleted successfully');
        return redirect()->back();
    }

    public function pages($username)
    {
        $timeline = Timeline::where('username', $username)->with('user')->first();
        if ($timeline == null) {
            return redirect('/');
        }
        if ($timeline->id == Auth::user()->timeline_id) {
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
            $theme->setTitle('Pages | '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

            return $theme->scope('users/pages')->render();
        } else {
            return redirect($timeline->username);
        }
    }

    public function groups($username)
    {
        $timeline = Timeline::where('username', $username)->with('user')->first();
        if ($timeline == null) {
            return redirect('/');
        }
        if ($timeline->id == Auth::user()->timeline_id) {
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
            $theme->setTitle('Groups | '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

            return $theme->scope('users/groups')->render();
        } else {
            return redirect($timeline->username);
        }
    }

    public function wallpaperSettings($username)
    {
        $wallpapers = Wallpaper::all();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.wallpaper_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/settings/wallpaper', compact('wallpapers'))->render();
    }

    public function savedItems($username)
    {
        $mode = 'globalfeed';
        $user_post = 'globalfeed';
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $timeline = Timeline::where('username', $username)->first();

        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $theme->setTitle($timeline->name.' - Saved Post '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));
        return $theme->scope('users/save_timeline', compact('username', 'timeline','hashtag', 'trending_tags', 'suggested_users', 'suggested_groups', 'suggested_pages', 'mode', 'user_post'))
            ->render();
    }

    public function changeLanguageSetting(Request $request) {
        $user = User::find(Auth::user()->id);
        $user->language = $request->language;
        $user->save();
        Flash::success('Language Updated');
        return redirect()->back();
    }

    public function editProfile($username) {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.edit_profile').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/edit-profile', compact('username'))->render();
    }

    public function showProfileSettings($username) {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.edit_profile').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/profile-settings', compact('username'))->render();
    }

  public function blockUser($username) {
    $blockedTimeline = DB::table('timelines')
      ->where('username', $username)
      ->first();
    $blockedUser = DB::table('users')
      ->where('timeline_id', $blockedTimeline->id)
      ->first();
    $blockerUid = Auth::user()->id;
    $blockerTid = Auth::user()->timeline_id;
    $blockExists = DB::table('user_blocked')
      ->where('blocked_uid', $blockedUser->id)
      ->where('blocker_uid', $blockerUid)
      ->first();
    if ($blockExists == NULL) {
      $block_user = DB::table('user_blocked')
        ->insert([
          'blocked_uid' => $blockedUser->id,
          'blocked_tid' => $blockedTimeline->id,
          'blocked_username' => $username,
          'blocker_uid' => $blockerUid,
          'blocker_tid' => $blockerTid,
          'created_at' => Carbon::now(),
        ]);
      $result = $block_user ? 'Successfully blocked user' : 'Failed to block user';
    }
    else {
      $result = 'User already blocked';
    }
    return $result;
  }

  public function unblockUser(Request $request) {
    $unblock = DB::table('user_blocked')->where([['blocked_uid', $request->user_id],['blocker_uid',Auth::user()->id]])->delete();
    $result = $unblock ? 'Successfully unblocked user' : 'Failed to unblock user';
    return response()->json(['result'=>$result]);
  }

  public function getConversation() {
      $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
      return $theme->scope('chat', compact('username'))
          ->render();
  }
}
