<?php

namespace App\Http\Controllers;

use Alaouy\Youtube\Facades\Youtube;
use App\Album;
use App\Announcement;
use App\Category;
use App\Comment;
use App\Event;
use App\Group;
use App\Hashtag;
use App\Http\Requests\CreateTimelineRequest;
use App\Http\Requests\UpdateTimelineRequest;
use App\Media;
use App\Notification;
use App\Page;
use App\Post;
use App\PostMedia;
use App\Repositories\TimelineRepository;
use App\Role;
use App\Setting;
use App\Timeline;
use App\User;
use App\Wallpaper;
use Carbon\Carbon;
use DB;
use Flash;
use Flavy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;
use LinkPreview\LinkPreview;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Storage;
use Teepluss\Theme\Facades\Theme;
use Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use LaravelPusher;
use App\Follower;
use App\EventUser;
use Session;
use App\BlockNotification;

class TimelineController extends AppBaseController
{
    /** @var TimelineRepository */
    private $timelineRepository;

    public function __construct(TimelineRepository $timelineRepo, Request $request)
    {
        $this->timelineRepository = $timelineRepo;
        $this->watchEventExpires();

        $this->request = $request;
        $this->checkCensored();
    }

    protected function checkCensored()
    {
        $messages['not_contains'] = 'The :attribute must not contain banned words';
        if($this->request->method() == 'POST') {
            // Adjust the rules as needed
            $this->validate($this->request, 
                [
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
                  'username'      => 'not_contains',
                  'username'      => 'not_contains',
                  'email'         => 'email',
                ],$messages);
        }
    }

    public function watchEventExpires()
    {   
        if(Auth::user())
        {
            $events = Event::where('user_id',Auth::user()->id)->get();

            if($events)
            {
                foreach ($events as $event) {
                    if(strtotime($event->end_date) < strtotime('-2 week'))
                    {
                        //Deleting Events
                        
                         $event->users()->detach();
                         $event->pages()->detach();
                            // Deleting event posts
                                $event_posts = $event->timeline()->with('posts')->first();
                        
                                if(count($event_posts->posts) != 0)
                                {
                                    foreach($event_posts->posts as $post)
                                    {
                                        $post->deleteMe();
                                    }
                                }

                                //Deleting event notifications
                                $timeline_alerts = $event->timeline()->with('notifications')->first();

                                if(count($timeline_alerts->notifications) != 0)
                                {
                                    foreach($timeline_alerts->notifications as $notification)
                                    {
                                        $notification->delete();
                                    }
                                }

                            $event_timeline = $event->timeline();
                            $event->delete();
                            $event_timeline->delete();

                    }
                }

            }
        } 
        
    }

    /**
     * Display a listing of the Timeline.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->timelineRepository->pushCriteria(new RequestCriteria($request));
        $timelines = $this->timelineRepository->all();

        return view('timelines.index')
            ->with('timelines', $timelines);
    }

    /**
     * Show the form for creating a new Timeline.
     *
     * @return Response
     */
    public function create()
    {
        return view('timelines.create');
    }

    /**
     * Store a newly created Timeline in storage.
     *
     * @param CreateTimelineRequest $request
     *
     * @return Response
     */
    public function store(CreateTimelineRequest $request)
    {
        $input = $request->all();

        $timeline = $this->timelineRepository->create($input);

        Flash::success('Timeline saved successfully.');

        return redirect(route('timelines.index'));
    }

     /**
      * Display the specified Timeline.
      *
      * @param  int $id
      *
      * @return Response
      */
    public function showTimeline($username)
    {
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        $posts = [];
        $timeline = Timeline::where('username', $username)->first();
        $user_post = '';

        if ($timeline == null) {
            return redirect('/');
        }

        $timeline_posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));

        foreach ($timeline_posts as $timeline_post) {
            //This is for filtering reported(flag) posts, displaying non flag posts
            if ($timeline_post->check_reports($timeline_post->id) == false) {
                array_push($posts, $timeline_post);
            }
        }

        if ($timeline->type == 'user') {
            $follow_user_status = '';
            $timeline_post_privacy = '';
            $timeline_post = '';


            $user = User::where('timeline_id', $timeline['id'])->first();
            
            $is_blocked = DB::table('user_blocked')->where([['blocker_uid',$user->id],['blocked_uid',Auth::user()->id]])->first();
            
            if($is_blocked){
                abort(404);
            }

            $blocked = DB::table('user_blocked')->where([['blocked_uid',$user->id],['blocker_uid',Auth::user()->id]])->first();
            $block_text = trans('common.block');

            if($blocked){
                $block_text = trans('common.unblock');
            }

            $own_pages = $user->own_pages();
            $own_groups = $user->own_groups();
            $liked_pages = $user->pageLikes()->get();
            $joined_groups = $user->groups()->get();
            $joined_groups_count = $user->groups()->where('role_id', '!=', $admin_role_id->id)->where('status', '=', 'approved')->get()->count();
            $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
            $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
            $followRequests = $user->followers()->where('status', '=', 'pending')->get();
            $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
            $guest_events = $user->getEvents();


            $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                               ->where('leader_id', '=', $user->id)->first();

            if ($follow_user_status) {
                $follow_user_status = $follow_user_status->status;
            }

            $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
            $follow_confirm = $confirm_follow_setting->confirm_follow;

           //get user settings
            $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
            $privacy_settings = explode('-', $live_user_settings);
            $timeline_post = $privacy_settings[0];
            $user_post = $privacy_settings[1];
        } elseif ($timeline->type == 'page') {
            $page = Page::where('timeline_id', '=', $timeline->id)->first();
            $page_members = $page->members();
            $user_post = 'page';
        } elseif ($timeline->type == 'group') {
            $group = Group::where('timeline_id', '=', $timeline->id)->first();
            $group_members = $group->members();
            $group_events = $group->getEvents($group->id);
            $ongoing_events = $group->getOnGoingEvents($group->id);
            $upcoming_events = $group->getUpcomingEvents($group->id);
            $user_post = 'group';
        } elseif ($timeline->type == 'event') {
            $user_post = 'event';
            $event = Event::where('timeline_id', '=', $timeline->id)->first();
        }

        $next_page_url = url('ajax/get-more-posts?page=2&username='.$username);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/timeline', compact('user', 'timeline', 'posts', 'liked_pages', 'timeline_type', 'page', 'group', 'next_page_url', 'joined_groups', 'follow_user_status', 'followRequests', 'following_count', 'followers_count', 'timeline_post', 'user_post', 'follow_confirm', 'joined_groups_count', 'own_pages', 'own_groups', 'group_members', 'page_members', 'event', 'user_events', 'block_text' ,'guest_events', 'username', 'group_events', 'ongoing_events', 'upcoming_events'))->render();  
    }

    public function getMorePosts(Request $request)
    {
        $timeline = Timeline::where('username', $request->username)->first();

        $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));
        $theme = Theme::uses('default')->layout('default');

        $responseHtml = '';
        foreach ($posts as $post) {
            $responseHtml .= $theme->partial('post', ['post' => $post, 'timeline' => $timeline, 'next_page_url' => $posts->appends(['username' => $request->username])->nextPageUrl()]);
        }

        return $responseHtml;
    }

    public function showFeed(Request $request)
    {
        $mode = "showfeed";
        $user_post = 'showfeed';
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $timeline = Timeline::where('username', Auth::user()->username)->first();

        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        // Check for hashtag
        if ($request->hashtag) {
            $hashtag = '#'.$request->hashtag;

            $posts = Post::where('description', 'like', "%{$hashtag}%")->where('active', 1)->latest()->paginate(Setting::get('items_page'));
        } // else show the normal feed
        else {
            $posts = Post::whereIn('user_id', function ($query) use ($id) {
                $query->select('leader_id')
                    ->from('followers')
                    ->where('follower_id', $id);
            })->orWhere('user_id', $id)->where('active', 1)->latest()->paginate(Setting::get('items_page'));
        }


        if ($request->ajax) {
            $responseHtml = '';
            foreach ($posts as $post) {
                $responseHtml .= $theme->partial('post', ['post' => $post, 'timeline' => $timeline, 'next_page_url' => $posts->appends(['ajax' => true, 'hashtag' => $request->hashtag])->nextPageUrl()]);
            }

            return $responseHtml;
        }

        $announcement = Announcement::find(Setting::get('announcement'));
        if ($announcement != null) {
            $chk_isExpire = $announcement->chkAnnouncementExpire($announcement->id);

            if ($chk_isExpire == 'notexpired') {
                $active_announcement = $announcement;
                if (!$announcement->users->contains(Auth::user()->id)) {
                    $announcement->users()->attach(Auth::user()->id);
                }
            }
        }


        $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.Auth::user()->username);

        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('home', compact('timeline','hashtag' ,'posts', 'next_page_url', 'trending_tags', 'suggested_users', 'active_announcement', 'suggested_groups', 'suggested_pages', 'mode', 'user_post'))
        ->render();
    }

    public function showGlobalFeed(Request $request)
    {
        $mode = 'globalfeed';
        $user_post = 'globalfeed';
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $timeline = Timeline::where('username', Auth::user()->username)->first();

        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        // Check for hashtag
        if ($request->hashtag) {
            $hashtag = '#'.$request->hashtag;

            $posts = Post::where('description', 'like', "%{$hashtag}%")->where('active', 1)->latest()->paginate(Setting::get('items_page'));
        } // else show the normal feed
        else {
            $posts = Post::orderBy('created_at', 'desc')->where('active', 1)->paginate(Setting::get('items_page'));
        }

        if ($request->ajax) {
            $responseHtml = '';
            foreach ($posts as $post) {
                $responseHtml .= $theme->partial('post', ['post' => $post, 'timeline' => $timeline, 'next_page_url' => $posts->appends(['ajax' => true, 'hashtag' => $request->hashtag])->nextPageUrl()]);
            }

            return $responseHtml;
        }

        $announcement = Announcement::find(Setting::get('announcement'));
        if ($announcement != null) {
            $chk_isExpire = $announcement->chkAnnouncementExpire($announcement->id);

            if ($chk_isExpire == 'notexpired') {
                $active_announcement = $announcement;
                if (!$announcement->users->contains(Auth::user()->id)) {
                    $announcement->users()->attach(Auth::user()->id);
                }
            }
        }

        $next_page_url = url('ajax/get-global-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.Auth::user()->username);

        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('home', compact('timeline', 'posts', 'next_page_url', 'trending_tags', 'suggested_users', 'active_announcement', 'suggested_groups', 'suggested_pages', 'mode', 'user_post'))
        ->render();
    }

    public function changeAvatar(Request $request)
    {
        if (Config::get('app.env') == 'demo' && Auth::user()->username == 'bootstrapguru') {
            return response()->json(['status' => '201', 'message' => trans('common.disabled_on_demo')]);
        }
        $timeline = Timeline::where('id', $request->timeline_id)->first();

        if (($request->timeline_type == 'user' && $request->timeline_id == Auth::user()->timeline_id) ||
        ($request->timeline_type == 'page' && $timeline->page->is_admin(Auth::user()->id) == true) ||
        ($request->timeline_type == 'group' && $timeline->groups->is_admin(Auth::user()->id) == true)
        ) {
            if ($request->hasFile('change_avatar')) {
                $timeline_type = $request->timeline_type;

                $change_avatar = $request->file('change_avatar');
                $strippedName = str_replace(' ', '', $change_avatar->getClientOriginalName());
                // $photoName = microtime().$strippedName;

                // Lets resize the image to the square with dimensions of either width or height , which ever is smaller.
                list($width, $height) = getimagesize($change_avatar->getRealPath());


                $avatar = Image::make($change_avatar->getRealPath())->orientate();
                $avatar_thumbnail = $avatar;

                $mime = $avatar->mime();
                if ($mime == 'image/jpeg')
                    $extension = '.jpg';
                elseif ($mime == 'image/png')
                    $extension = '.png';
                elseif ($mime == 'image/gif')
                    $extension = '.gif';
                else
                    $extension = '';
                $photoName = hexdec(uniqid()).'_'.str_replace('.','',microtime(true)).Auth::user()->id.$extension;
                $photoName_thumbnail = '100_'.$photoName;

                if ($width > $height) {
                    $avatar->crop($height, $height);
                    $avatar_thumbnail->crop($height, $height);
                } else {
                    $avatar->crop($width, $width);
                    $avatar_thumbnail->crop($width, $width);
                }
                $avatar->resize(600, 600);

                $avatar->save(storage_path().'/uploads/'.$timeline_type.'s/avatars/'.$photoName, 60);

                $avatar_thumbnail->resize(100, 100);
                $avatar_thumbnail->save(storage_path().'/uploads/'.$timeline_type.'s/avatars/'.$photoName_thumbnail, 60);

                $media = Media::create([
                      'title'  => $photoName,
                      'type'   => 'image',
                      'source' => $photoName,
                    ]);

                $timeline->avatar_id = $media->id;

                if ($timeline->save()) {
                    return response()->json(['status' => '200', 'avatar_url' => url($timeline_type.'/avatar/'.$photoName), 'message' => trans('messages.update_avatar_success')]);
                }
            } else {
                return response()->json(['status' => '201', 'message' => trans('messages.update_avatar_failed')]);
            }
        }
    }

    public function changeCover(Request $request)
    {
        if (Config::get('app.env') == 'demo' && Auth::user()->username == 'bootstrapguru') {
            return response()->json(['status' => '201', 'message' => trans('common.disabled_on_demo')]);
        }
        if ($request->hasFile('change_cover')) {
            $timeline_type = $request->timeline_type;

            $change_avatar = $request->file('change_cover');
            $strippedName = str_replace(' ', '', $change_avatar->getClientOriginalName());
            // $photoName = date('Y-m-d-H-i-s').$strippedName;
            $avatar = Image::make($change_avatar->getRealPath())->orientate();
            $mime = $avatar->mime();
            if ($mime == 'image/jpeg')
                $extension = '.jpg';
            elseif ($mime == 'image/png')
                $extension = '.png';
            elseif ($mime == 'image/gif')
                $extension = '.gif';
            else
                $extension = '';
            $photoName = hexdec(uniqid()).'_'.str_replace('.','',microtime(true)).Auth::user()->id.$extension;
            $avatar->save(storage_path().'/uploads/'.$timeline_type.'s/covers/'.$photoName, 60);

            $media = Media::create([
              'title'  => $photoName,
              'type'   => 'image',
              'source' => $photoName,
              ]);

            $timeline = Timeline::where('id', $request->timeline_id)->first();
            $timeline->cover_id = $media->id;

            if ($timeline->save()) {
                return response()->json(['status' => '200', 'cover_url' => url($timeline_type.'/covers/'.$photoName), 'message' => trans('messages.update_cover_success')]);
            }
        } else {
            return response()->json(['status' => '201', 'message' => trans('messages.update_cover_failed')]);
        }
    }

    public function uploadPostImages(Request $request)
    {
        if ($request->file('post_images_upload')) {
            $postImage = $request->file('post_images_upload');
            $strippedName = str_replace(' ', '', $postImage->getClientOriginalName());
            // $photoName = 'post'.time().$strippedName;
            
            $avatar = Image::make($postImage->getRealPath())->orientate();
            $mime = $avatar->mime();
            if ($mime == 'image/jpeg')
                $extension = '.jpg';
            elseif ($mime == 'image/png')
                $extension = '.png';
            elseif ($mime == 'image/gif')
                $extension = '.gif';
            else
                $extension = '';
            $photoName = hexdec(uniqid()).'_'.str_replace('.','',microtime(true)).Auth::user()->id.$extension;
            
            $width = $avatar->width();

            if($width > 1200) {
                $avatar = $avatar->resize(1200,null,function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
            }
            
            $height = $avatar->height();

            if($height > 1000) {
                    $avatar = $avatar->resize(null,1000,function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
            }

            // post image size max-width 1200
            $avatar->save(storage_path().'/uploads/users/gallery/'.$photoName, 60);

            //image width 400
            $avatar_thumbnail_400 = $avatar;
            $photoName_thumbnail_400 = '400_'.$photoName;
            $avatar_thumbnail_400 = $avatar_thumbnail_400->resize(400,null,function ($constraint) {
                $constraint->aspectRatio();
            });
            $avatar_thumbnail_400->save(storage_path().'/uploads/users/gallery/'.$photoName_thumbnail_400, 60);


            //image width 100x100
            $avatar_thumbnail = $avatar;
            $photoName_thumbnail = '100_'.$photoName;
            $avatar_thumbnail->resize(100, 100);
            $avatar_thumbnail->save(storage_path().'/uploads/users/gallery/'.$photoName_thumbnail, 60);

            // image width 50x50
            $avatar_thumbnail_50 = $avatar;
            $avatar_thumbnail_50 = $avatar_thumbnail_50->resize(50,null,function ($constraint) {
                $constraint->aspectRatio();
            });
            $photoName_thumbnail_50 = '50_'.$photoName;
            $avatar_thumbnail_50->save(storage_path().'/uploads/users/gallery/'.$photoName_thumbnail_50, 60);
            
            return response()->json(['status' => '200', $photoName]);
        }

    }

    public function createPost(Request $request)
    {
        $input = $request->all();
        
        $input['user_id'] = Auth::user()->id;
        
        $post = Post::create($input);
        $post->notifications_user()->sync([Auth::user()->id], true);

        if($request->post_images_upload){
            foreach ($request->post_images_upload as $postImage) {

                $media = Media::create([
                    'title'  => $postImage,
                    'type'   => 'image',
                    'source' => $postImage,
                ]);

                $post->images()->attach($media);
            }
        }

        $post->notifications_user()->sync([Auth::user()->id], true);

//        $media = Media::create([
//            'title'  => $photoName,
//            'type'   => 'image',
//            'source' => $photoName,
//        ]);
//
//        $post->images()->attach($media);

        if ($request->hasFile('post_video_upload')) {
            $uploadedFile = $request->file('post_video_upload');
            $s3 = Storage::disk('uploads');

            $timestamp = date('Y-m-d-H-i-s');

            $strippedName = $timestamp.str_replace(' ', '', $uploadedFile->getClientOriginalName());

            $s3->put('users/gallery/'.$strippedName, file_get_contents($uploadedFile));

            $basename = $timestamp.basename($request->file('post_video_upload')->getClientOriginalName(), '.'.$request->file('post_video_upload')->getClientOriginalExtension());

            Flavy::thumbnail(storage_path().'/uploads/users/gallery/'.$strippedName, storage_path().'/uploads/users/gallery/'.$basename.'.jpg', 1); //returns array with file info

            $media = Media::create([
                      'title'  => $basename,
                      'type'   => 'video',
                      'source' => $strippedName,
                    ]);

            $post->images()->attach($media);
        }
        if ($post) {
            // Check for any mentions and notify them
            preg_match_all('/(^|\s)(@\w+)/', $request->description, $usernames);
            foreach ($usernames[2] as $value) {
                $timeline = Timeline::where('username', str_replace('@', '', $value))->first();
                App::setLocale($timeline->user->language);
                $notification = Notification::create(['user_id' => $timeline->user->id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.mentioned_you_in_post'), 'type' => 'mention', 'link' => 'post/'.$post->id]);
                App::setLocale(Auth::user()->language);
            }
            $timeline = Timeline::where('id', $request->timeline_id)->first();

            //Notify the user when someone posts on his timeline/page/group

            if ($timeline->type == 'page') {
                $notify_users = $timeline->page->users()->whereNotIn('user_id', [Auth::user()->id])->get();
                $notify_message = 'posted on this page';
            } elseif ($timeline->type == 'group') {
                $notify_users = $timeline->groups->users()->whereNotIn('user_id', [Auth::user()->id])->get();
                $notify_message = 'posted on this group';
            } else {
                $notify_users = $timeline->user()->whereNotIn('id', [Auth::user()->id])->get();
                $notify_message = 'posted on your timeline';
            }

            foreach ($notify_users as $notify_user) {
                if($notify_user->id != Auth::user()->id){
                    Notification::create(['user_id' => $notify_user->id, 'timeline_id' => $request->timeline_id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.$notify_message, 'type' => $timeline->type, 'link' => $timeline->username]);
                }
            }


            // Check for any hashtags and save them
            preg_match_all('/(^|\s)(#\w+)/', $request->description, $hashtags);
            foreach ($hashtags[2] as $value) {
                $timeline = Timeline::where('username', str_replace('@', '', $value))->first();
                $hashtag = Hashtag::where('tag', str_replace('#', '', $value))->first();
                if ($hashtag) {
                    $hashtag->count = $hashtag->count + 1;
                    $hashtag->save();
                } else {
                    Hashtag::create(['tag' => str_replace('#', '', $value), 'count' => 1]);
                }
            }

            // Let us tag the post friends :)
            if ($request->user_tags != null) {
                $post->users_tagged()->sync(explode(',', $request->user_tags));
            }
        }

        // $post->users_tagged = $post->users_tagged();
        // $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('ajax');
        // $postHtml = $theme->scope('timeline/post', compact('post', 'timeline'))->render();
        
        return response()->json(['status' => '200', 'data' => $post]);
    }

    public function editPost(Request $request)
    {
        $post = Post::where('id', $request->post_id)->with('user')->first();
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('ajax');
        $postHtml = $theme->partial('edit-post', compact('post'));

        return response()->json(['status' => '200', 'data' => $postHtml]);
    }

    public function loadEmoji()
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('ajax');
        $postHtml = $theme->partial('emoji');

        return response()->json(['status' => '200', 'data' => $postHtml]);
    }

    public function updatePost(Request $request)
    {
        $post = Post::where('id', $request->post_id)->first();
        if ($post->user->id == Auth::user()->id) {
            $post->description = $request->description;
            $post->save();
        }

//        return redirect('post/'.$post->id);
          return response()->json(['status' => '200', 'data' => 'Post has been edited successfully']);
    }

    public function getSoundCloudResults(Request $request)
    {
        $soundcloudJson = file_get_contents('http://api.soundcloud.com/tracks.json?client_id='.env('SOUNDCLOUD_CLIENT_ID').'&q='.$request->q);

        return response()->json(['status' => '200', 'data' => $soundcloudJson]);
    }

    public function postComment(Request $request)
    {
        $comment = Comment::create([
                    'post_id'     => $request->post_id,
                    'description' => $request->description,
                    'user_id'     => Auth::user()->id,
                    'parent_id'   => $request->comment_id,
                  ]);

        $post = Post::where('id', $request->post_id)->first();
        $posted_user = $post->user;

        if ($request->user_tags != null) {
            $comment->users_tagged()->sync(explode(',', $request->user_tags));
            foreach ($request->user_tags as $user_tag) {
                $user_t = User::find($user_tag->user_id);
                App::setLocale($user_t->language);
                Notification::create(['user_id' => $user_tag->user_id, 'post_id' => $request->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' mentioned you in comment', 'type' => 'comment_post','link' => 'post/'.$post->id]);
                App::setLocale(Auth::user()->language);
            }
        }

        preg_match_all('/(^|\s)(@\w+)/', $request->description, $usernames);
            foreach ($usernames[2] as $value) {
                $timeline = Timeline::where('username', str_replace('@', '', $value))->first();
                App::setLocale($timeline->user->language);
                $notification = Notification::create(['user_id' => $timeline->user->id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.mentioned_you_in_comment'), 'type' => 'mention', 'link' => 'post/'.$post->id]);
                App::setLocale(Auth::user()->language);
            }

        if ($comment) {
            if (Auth::user()->id != $post->user_id) {
                //Check if the user has blocked the post.
                $blocked = $this->checkIfPostBlocked($request->post_id,$post->user_id);
                if ($blocked == FALSE){
                  //Notify the user for comment on his/her post
                  App::setLocale($posted_user->language);
                  Notification::create(['user_id' => $post->user_id, 'post_id' => $request->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.commented_on_your_post'), 'type' => 'comment_post']);
                  App::setLocale(Auth::user()->language);
                }
            }

            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('ajax');
            if ($request->comment_id) {
                $reply = $comment;
                $main_comment = Comment::find($reply->parent_id);
                $main_comment_user = $main_comment->user;

                $user = User::find(Auth::user()->id);
                $user_settings = $user->getUserSettings($main_comment_user->id);
                $user_url = 'fitmetix.com/'.$user->username;
                if (Auth::user()->id != $post->user_id) {
                    if ($user_settings && $user_settings->email_reply_comment == 'yes') {
                        Mail::send('emails.commentreply_mail', ['user' => $user, 'main_comment_user' => $main_comment_user, 'user_url'=>$user_url], function ($m) use ($user, $main_comment_user) {
                            $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                            $m->to($main_comment_user->email, $main_comment_user->name)->subject('New reply to your comment');
                        });
                    }
                }
                $postHtml = $theme->scope('timeline/reply', compact('reply', 'post'))->render();
            } else {
                $user = User::find(Auth::user()->id);
                $user_settings = $user->getUserSettings($posted_user->id);
                $user_url = 'fitmetix.com/'.$user->username;
                if (Auth::user()->id != $post->user_id) {
                    if ($user_settings && $user_settings->email_comment_post == 'yes') {
                        Mail::send('emails.commentmail', ['user' => $user, 'posted_user' => $posted_user, 'user_url'=>$user_url], function ($m) use ($user, $posted_user) {
                            $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                            $m->to($posted_user->email, $posted_user->name)->subject('New comment to your post');
                        });
                    }
                }

                $postHtml = $theme->scope('timeline/comment', compact('comment', 'post'))->render();
            }
        }

        $user_info['avatar'] = Auth::user()->avatar;
        $user_info['name'] = Auth::user()->name;
        $user_info['username'] = Auth::user()->username;
        $user_info['user_id'] = Auth::user()->id;
        return response()->json(['status' => '200', 'comment_id' => $comment->id, 'user_info'=>$user_info]);
    }

    public function likePost(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        $posted_user = $post->user;
        $like_count = $post->users_liked()->count();

        $post_image = null;
        if($post->images()->count() > 0) {
            $post_image = $post->images()->first()->source;
        }

        //Like the post
        if (!$post->users_liked->contains(Auth::user()->id)) {
            $post->users_liked()->attach(Auth::user()->id, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            $post->notifications_user()->attach(Auth::user()->id);

            $user = User::find(Auth::user()->id);
            $user_settings = $user->getUserSettings($posted_user->id);
            $post_url = 'fitmetix.com/post/'.$post->id;
            $user_url = 'fitmetix.com/'.$user->username;
            if (Auth::user()->id != $post->user_id) {
                if ($user_settings && $user_settings->email_like_post == 'yes') {
                    Mail::send('emails.postlikemail', ['user' => $user, 'posted_user' => $posted_user,'post_url' => $post_url, 'user_url'=>$user_url], function ($m) use ($posted_user, $user) {
                        $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                        $m->to($posted_user->email, $posted_user->name)->subject($user->name.' '.'liked your post');
                    });
                }
            }
            $status_message = '';

            if ($post->user->id != Auth::user()->id) {
                //Notify the user for post like
                App::setLocale($post->user->language);
                $notify_message = trans('common.liked_your_post');
                $notify_type = 'like_post';
                $status_message = 'successfully liked';

                Notification::create(['user_id' => $post->user->id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.$notify_message, 'link' =>$post_image, 'type' => $notify_type]);
                App::setLocale(Auth::user()->language);
            }

            return response()->json(['status' => '200', 'liked' => true, 'message' => $status_message, 'likecount' => $like_count]);
        } //Unlike the post
        else {
            $post->users_liked()->detach([Auth::user()->id]);
            $post->notifications_user()->detach([Auth::user()->id]);

            //Notify the user for post unlike
            $notify_message = 'unliked your post';
            $notify_type = 'unlike_post';
            $status_message = 'successfully unliked';

            // if ($post->user->id != Auth::user()->id) {
            //     Notification::create(['user_id' => $post->user->id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.$notify_message, 'type' => $notify_type]);
            // }

            return response()->json(['status' => '200', 'liked' => false, 'message' => $status_message, 'likecount' => $like_count]);
        }

        if ($post) {
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('ajax');
            $postHtml = $theme->scope('timeline/post', compact('post'))->render();
        }

        return response()->json(['status' => '200', 'data' => $postHtml]);
    }

    public function likeComment(Request $request)
    {
        $comment = Comment::findOrFail($request->comment_id);
        $comment_user = $comment->user;
        $post_id = $comment->post_id;
        //Check if the user has blocked the post.
        $block = $this->checkIfPostBlocked($post_id,Auth::user()->id);
        if (!$comment->comments_liked->contains(Auth::user()->id)) {
            $comment->comments_liked()->attach(Auth::user()->id);
            $comment_likes = $comment->comments_liked()->get();
            $like_count = $comment_likes->count();
            if ($block == FALSE){
              //sending email notification
              $user = User::find(Auth::user()->id);
              $user_settings = $user->getUserSettings($comment_user->id);
              $post_url = 'fitmetix.com/post/'.$comment->post_id;
              $user_url = 'fitmetix.com/'.$user->username;
              if ($user_settings && $user_settings->email_like_comment == 'yes') {
                Mail::send('emails.commentlikemail', ['user' => $user, 'comment_user' => $comment_user,'user_url'=> $user_url, 'post_url'=>$post_url], function ($m) use ($user, $comment_user) {
                  $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                  $m->to($comment_user->email, $comment_user->name)->subject($user->name.' '.'likes your comment');
                });
              }

              //Notify the user for comment like
              if ($comment->user->id != Auth::user()->id) {
                App::setLocale($comment->user->language);
                Notification::create(['user_id' => $comment->user_id, 'post_id' => $comment->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.liked_your_comment'), 'type' => 'like_comment']);
                App::setLocale(Auth::user()->language);
              }
            }

            return response()->json(['status' => '200', 'liked' => true, 'message' => 'successfully liked', 'likecount' => $like_count]);
        } else {
            $comment->comments_liked()->detach([Auth::user()->id]);
            $comment_likes = $comment->comments_liked()->get();
            $like_count = $comment_likes->count();
            if ($block == FALSE){
              //Notify the user for comment unlike
              // if ($comment->user->id != Auth::user()->id) {
              //   Notification::create(['user_id' => $comment->user_id, 'post_id' => $comment->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.unliked_your_comment'), 'type' => 'unlike_comment']);
              // }
            }

            return response()->json(['status' => '200', 'unliked' => false, 'message' => 'successfully unliked', 'likecount' => $like_count]);
        }
    }

    public function sharePost(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        $posted_user = $post->user;


        if (!$post->users_shared->contains(Auth::user()->id)) {
            $post->users_shared()->attach(Auth::user()->id, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            $post_share_count = $post->users_shared()->get()->count();
            // we need to insert the shared post into the timeline of the person who shared
            $input['user_id'] = Auth::user()->id;
            $post = Post::create([
                'timeline_id' => Auth::user()->timeline->id,
                'user_id' => Auth::user()->id,
                'shared_post_id' => $request->post_id,
            ]);


            if ($post->user_id != Auth::user()->id) {
                //Notify the user for post share
                App::setLocale($posted_user->language);
                Notification::create(['user_id' => $post->user_id, 'post_id' => $request->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.shared_your_post'), 'type' => 'share_post', 'link' => '/'.Auth::user()->username]);
                App::setLocale(Auth::user()->language);

                $user = User::find(Auth::user()->id);
                $user_settings = $user->getUserSettings($posted_user->id);
                $post_url = 'fitmetix.com/post/'.$request->post_id;

                if ($user_settings && $user_settings->email_post_share == 'yes') {
                    Mail::send('emails.postsharemail', ['user' => $user, 'posted_user' => $posted_user,'post_url'=>$post_url], function ($m) use ($user, $posted_user) {
                        $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                        $m->to($posted_user->email, $posted_user->name)->subject($user->name.' '.'shared your post');
                    });
                }
            }

            return response()->json(['status' => '200', 'shared' => true, 'message' => 'successfully shared', 'share_count' => $post_share_count]);
        } else {
            $post->users_shared()->detach([Auth::user()->id]);
            $post_share_count = $post->users_shared()->get()->count();

            $sharedPost = Post::where('shared_post_id', $post->id)->delete();

            if ($post->user_id != Auth::user()->id) {
                //Notify the user for post share
                App::setLocale($posted_user->language);
                Notification::create(['user_id' => $post->user_id, 'post_id' => $request->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.unshared_your_post'), 'type' => 'unshare_post', 'link' => '/'.Auth::user()->username]);
                App::setLocale(Auth::user()->language);
            }

            return response()->json(['status' => '200', 'unshared' => false, 'message' => 'Successfully unshared', 'share_count' => $post_share_count]);
        }
    }

    public function pageLiked(Request $request)
    {
        $page = Page::where('timeline_id', '=', $request->timeline_id)->first();

        if ($page->likes->contains(Auth::user()->id)) {
            $page->likes()->detach([Auth::user()->id]);

            return response()->json(['status' => '200', 'like' => true, 'message' => 'successfully unliked']);
        }
    }

    public function pageReport(Request $request)
    {
        $timeline = Timeline::where('id', '=', $request->timeline_id)->first();

        if ($timeline->type == 'page') {
            $admins = $timeline->page->admins();
            $report_type = 'page_report';
        }
        if ($timeline->type == 'group') {
            $admins = $timeline->groups->admins();
            $report_type = 'group_report';
        }


        if (!$timeline->reports->contains(Auth::user()->id)) {
            $timeline->reports()->attach(Auth::user()->id, ['status' => 'pending']);

            if ($timeline->type == 'user') {
                Notification::create(['user_id' => $timeline->user->id, 'timeline_id' => $timeline->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.reported_you'), 'type' => 'user_report']);
            } else {
                foreach ($admins as $admin) {
                    Notification::create(['user_id' => $admin->id, 'timeline_id' => $timeline->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' reported your '.$timeline->type, 'type' => $report_type]);
                }
            }


            return response()->json(['status' => '200', 'reported' => true, 'message' => 'successfully reported']);
        } else {
            $timeline->reports()->detach([Auth::user()->id]);

            if ($timeline->type == 'user') {
                Notification::create(['user_id' => $timeline->user->id, 'timeline_id' => $timeline->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.unreported_you'), 'type' => 'user_report']);
            } else {
                foreach ($admins as $admin) {
                    Notification::create(['user_id' => $admin->id, 'timeline_id' => $timeline->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.unreported_your_page'), 'type' => 'page_report']);
                }
            }

            return response()->json(['status' => '200', 'reported' => false, 'message' => 'successfully unreport']);
        }
    }

    public function timelineGroups(Request $request)
    {
        $group = Group::where('timeline_id', '=', $request->timeline_id)->first();

        if ($group->users->contains(Auth::user()->id)) {
            $group->users()->detach([Auth::user()->id]);

            return response()->json(['status' => '200', 'join' => true, 'message' => 'successfully unjoined']);
        }
    }

    public function getYoutubeVideo(Request $request)
    {
        $videoId = Youtube::parseVidFromURL($request->youtube_source);

        $video = Youtube::getVideoInfo($videoId);

        $videoData = [
                        'id'     => $video->id,
                        'title'  => $video->snippet->title,
                        'iframe' => $video->player->embedHtml,
                      ];

        return response()->json(['status' => '200', 'message' => $video]);
    }

    public function show($id)
    {
        $timeline = $this->timelineRepository->findWithoutFail($id);

        if (empty($timeline)) {
            Flash::error('Timeline not found');

            return redirect(route('timelines.index'));
        }

        return view('timelines.show')->with('timeline', $timeline);
    }

    /**
     * Show the form for editing the specified Timeline.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $timeline = $this->timelineRepository->findWithoutFail($id);

        if (empty($timeline)) {
            Flash::error('Timeline not found');

            return redirect(route('timelines.index'));
        }

        return view('timelines.edit')->with('timeline', $timeline);
    }

    /**
     * Update the specified Timeline in storage.
     *
     * @param int                   $id
     * @param UpdateTimelineRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTimelineRequest $request)
    {
        $timeline = $this->timelineRepository->findWithoutFail($id);

        if (empty($timeline)) {
            Flash::error('Timeline not found');

            return redirect(route('timelines.index'));
        }

        $timeline = $this->timelineRepository->update($request->all(), $id);

        Flash::success('Timeline updated successfully.');

        return redirect(route('timelines.index'));
    }

    /**
     * Remove the specified Timeline from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $timeline = $this->timelineRepository->findWithoutFail($id);

        if (empty($timeline)) {
            Flash::error('Timeline not found');

            return redirect(route('timelines.index'));
        }

        $this->timelineRepository->delete($id);

        Flash::success('Timeline deleted successfully.');

        return redirect(route('timelines.index'));
    }

    public function follow(Request $request)
    {
        $follow = User::where('timeline_id', '=', $request->timeline_id)->first();

        if (!$follow->followers->contains(Auth::user()->id)) {
            $follow->followers()->attach(Auth::user()->id, ['status' => 'approved']);

            $user = User::find(Auth::user()->id);
            $user_settings = $user->getUserSettings($follow->id);
            $user_url = 'fitmetix.com/'.$user->username;

            if ($user_settings && $user_settings->email_follow == 'yes') {
                Mail::send('emails.followmail', ['user' => $user, 'follow' => $follow,'user_url'=>$user_url], function ($m) use ($user, $follow) {
                    $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                    $m->to($follow->email, $follow->name)->subject($user->name.' '.trans('common.follows_you'));
                });
            }

            App::setLocale($follow->language);
            //Notify the user for follow
            Notification::create(['user_id' => $follow->id, 'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.is_following_you'), 'type' => 'follow']);
            App::setLocale(Auth::user()->language);

            return response()->json(['status' => '200', 'followed' => true, 'message' => 'successfully followed']);
        } else {
            $follow->followers()->detach([Auth::user()->id]);

            //Notify the user for follow
            // Notification::create(['user_id' => $follow->id, 'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.is_unfollowing_you'), 'type' => 'unfollow']);

            return response()->json(['status' => '200', 'followed' => false, 'message' => 'successfully unFollowed']);
        }
    }

    public function joiningGroup(Request $request)
    {
        $user_role_id = Role::where('name', '=', 'user')->first();
        $group = Group::where('timeline_id', '=', $request->timeline_id)->first();
        $group_timeline = $group->timeline;

        $users = $group->users()->get();

        if (!$group->users->contains(Auth::user()->id)) {
            $group->users()->attach(Auth::user()->id, ['role_id' => $user_role_id->id, 'status' => 'approved']);


            foreach ($users as $user) {
                if ($user->id != Auth::user()->id) {
                    //Notify the user for page like
                    Notification::create(['user_id' => $user->id, 'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.joined_your_group'), 'type' => 'join_group']);
                }

                if ($group->is_admin($user->id)) {
                    $group_admin = User::find($user->id);
                    $user = User::find(Auth::user()->id);
                    $user_settings = $user->getUserSettings($group_admin->id);
                    if ($user_settings && $user_settings->email_join_group == 'yes') {
                        Mail::send('emails.groupjoinmail', ['user' => $user, 'group_timeline' => $group_timeline], function ($m) use ($user, $group_admin, $group_timeline) {
                            $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                            $m->to($group_admin->email)->subject($user->name.' '.trans('common.joined_your_group'));
                        });
                    }
                }
            }

            return response()->json(['status' => '200', 'joined' => true, 'message' => 'successfully joined']);
        } else {
            $group->users()->detach([Auth::user()->id]);

            foreach ($users as $user) {
                if ($user->id != Auth::user()->id) {
                    //Notify the user for page like
                    Notification::create(['user_id' => $user->id, 'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.unjoined_your_group'), 'type' => 'unjoin_group']);
                }
            }

            return response()->json(['status' => '200', 'joined' => false, 'data' => 'successfully unjoined']);
        }
    }

    public function joiningEvent(Request $request)
    {
        $event = Event::where('timeline_id', '=', $request->timeline_id)->first();
        $users = $event->users()->get();
        $event_post_id = Post::where('timeline_id',$request->timeline_id)->first()->id;

        if (!$event->users->contains(Auth::user()->id)) {
            if($event->user_limit < $event->users()->count()){
                return response()->json(['status' => '200', 'joined' => false, 'message' => 'Limit reached']);
            }
            else if(($event->gender != 'all') && ($event->gender != Auth::user()->gender)){
                return response()->json(['status' => '200', 'joined' => false, 'message' => 'Gender mismatch']);   
            }
            else {
                $event->users()->attach(Auth::user()->id);

                foreach ($users as $user) {
                    if ($user->id != Auth::user()->id) {
                        //Notify the user for event join
                        App::setLocale($user->language);
                        Notification::create(['user_id' => $user->id,'post_id' => $event_post_id,'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.attending_your_event'), 'type' => 'join_event','link' => '/post/'.$event_post_id]);
                        App::setLocale(Auth::user()->language);
                    }
                }
                return response()->json(['status' => '200', 'joined' => true, 'message' => 'successfully joined']);
            }
        } else {
            $event->users()->detach([Auth::user()->id]);

            foreach ($users as $user) {
                if ($user->id != Auth::user()->id) {
                    App::setLocale($user->language);
                    Notification::create(['user_id' => $user->id,'post_id' => $event_post_id,'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.quit_attending_your_event'), 'type' => 'unjoin_event','link' => '/post/'.$event_post_id]);
                    App::setLocale(Auth::user()->language);
                }
            }
            return response()->json(['status' => '200', 'joined' => false, 'message' => 'successfully unjoined']);
        }
    }

    public function joiningPaidEvent(Request $request)
    {   
        $timeline_id = $request->session()->get('event_timeline_id');
        $transaction_id = $request->session()->get('sale_id');
        $request->session()->forget('event_timeline_id');
        $event = Event::where('timeline_id', '=', $timeline_id)->first();
        $users = $event->users()->get();

        $event->users()->attach(Auth::user()->id);

        DB::table('event_user')->where([['event_id',$event->id],['user_id',Auth::user()->id]])->update(['transaction'=>$transaction]);

        foreach ($users as $user) {
            if ($user->id != Auth::user()->id) {
                //Notify the user for event join
                App::setLocale($user->language);
                Notification::create(['user_id' => $user->id, 'timeline_id' => $timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.attending_your_event'), 'type' => 'join_event']);
                App::setLocale(Auth::user()->language);
            }
        }
        return redirect('events')->with('msg','Successfully joined');
    }

    public function joiningClosedGroup(Request $request)
    {
        $user_role_id = Role::where('name', '=', 'user')->first();
        $group = Group::where('timeline_id', '=', $request->timeline_id)->first();

        if (!$group->users->contains(Auth::user()->id)) {
            $group->users()->attach(Auth::user()->id, ['role_id' => $user_role_id->id, 'status' => 'pending']);


            $users = $group->users()->get();
            foreach ($users as $user) {
                if (Auth::user()->id != $user->id) {
                    //Notify the user for page like
                    Notification::create(['user_id' => $user->id, 'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.request_join_group'), 'type' => 'group_join_request']);
                }
            }

            return response()->json(['status' => '200', 'joinrequest' => true, 'message' => 'successfully sent group join request']);
        } else {
            $checkStatus = $group->chkGroupUser($group->id, Auth::user()->id);

            if ($checkStatus && $checkStatus->status == 'approved') {
                $group->users()->detach([Auth::user()->id]);

                return response()->json(['status' => '200', 'join' => true, 'message' => 'unsuccessfully request']);
            } else {
                $group->users()->detach([Auth::user()->id]);

                return response()->json(['status' => '200', 'joinrequest' => false, 'message' => 'unsuccessfully request']);
            }
        }
    }

    public function userFollowRequest(Request $request)
    {
        $user = User::where('timeline_id', '=', $request->timeline_id)->first();

        $user_settings = $user->getUserSettings($user->id);
        $notification = '';

        if (!$user->followers->contains(Auth::user()->id)) { 
            if($user->settings()->confirm_follow == "no"){
                $user->followers()->attach(Auth::user()->id, ['status' => 'pending']);
                $follow_status = 'pending';
                App::setLocale($user->language);
                $notification = Notification::create(['user_id' => $user->id, 'timeline_id' => Auth::user()->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.request_follow'), 'type' => 'follow_requested']);

                App::setLocale(Auth::user()->language);
                $follower = User::find(Auth::user()->id);
                $user_url = 'fitmetix.com/'.$follower->username;
                
                if ($user_settings && $user_settings->email_follow == 'yes') {
                    Mail::send('emails.followmail', ['user' => $user, 'follow' => $follower, 'user_url'=>$user_url], function ($m) use ($user) {
                        $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                        $m->to($user->email, $user->name)->subject(Auth::user()->name.' wants to follow you');
                    });
                }
            }
            else {
                $user->followers()->attach(Auth::user()->id, ['status' => 'approved']);
                $follow_status = 'approved';

                App::setLocale($user->language);
                $notification = Notification::create(['user_id' => $user->id, 'timeline_id' => Auth::user()->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.follow_public'), 'type' => 'follow']);

                App::setLocale(Auth::user()->language);

                $follower = User::find(Auth::user()->id);
                $user_url = 'fitmetix.com/'.$follower->username;

                if ($user_settings && $user_settings->email_follow == 'yes') {
                    Mail::send('emails.followmail', ['user' => $user, 'follow' => $follower, 'user_url'=>$user_url], function ($m) use ($user) {
                        $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                        $m->to($user->email, $user->name)->subject(Auth::user()->name.' '.trans('common.follows_you'));
                    });
                }
            }

            return response()->json(['status' => '200', 'followrequest' => true, 'message' => 'successfully sent user follow request','follow_status'=>$follow_status,'notification'=>$notification]);
        } else {
            if ($request->follow_status == 'approved') {
                $user->followers()->detach([Auth::user()->id]);

                return response()->json(['status' => '200', 'unfollow' => true, 'message' => 'unfollowed successfully']);
            } else {
                $user->followers()->detach([Auth::user()->id]);
                if(isset($request->notification_id)) {
                    $notification = Notification::find($request->notification_id)->delete();
                }
                Notification::where([['user_id',$user->id], ['timeline_id',$request->timeline_id], ['notified_by',Auth::user()->id],['type','follow_rejected']])->delete();
                return response()->json(['status' => '200', 'followrequest' => false, 'message' => 'Request cancelled']);
            }
        }
    }

    public function pageLike(Request $request)
    {
        $page = Page::where('timeline_id', '=', $request->timeline_id)->first();
        $page_timeline = $page->timeline;

        if (!$page->likes->contains(Auth::user()->id)) {
            $page->likes()->attach(Auth::user()->id);

            if (!$page->users->contains(Auth::user()->id)) {
                $users = $page->users()->get();
                foreach ($users as $user) {
                    //Notify the user for page like
                    Notification::create(['user_id' => $user->id, 'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.liked_your_page'), 'type' => 'like_page']);

                    if ($page->is_admin($user->id)) {
                        $page_admin = User::find($user->id);
                        $user = User::find(Auth::user()->id);
                        $user_settings = $user->getUserSettings($page_admin->id);
                        if ($user_settings && $user_settings->email_like_page == 'yes') {
                            Mail::send('emails.pagelikemail', ['user' => $user, 'page_timeline' => $page_timeline], function ($m) use ($user, $page_admin, $page_timeline) {
                                $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                                $m->to($page_admin->email)->subject($user->name.' '.'liked your page');
                            });
                        }
                    }
                }
            }

            return response()->json(['status' => '200', 'liked' => true, 'message' => 'Page successfully liked']);
        } else {
            $page->likes()->detach([Auth::user()->id]);

            if (!$page->users->contains(Auth::user()->id)) {
                $users = $page->users()->get();
                foreach ($users as $user) {
                    //Notify the user for page unlike
                    Notification::create(['user_id' => $user->id, 'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.unliked_your_page'), 'type' => 'unlike_page']);
                }
            }

            return response()->json(['status' => '200', 'liked' => false, 'message' => 'Page successfully unliked']);
        }
    }

    public function getNotifications(Request $request)
    {
        $post = Post::findOrFail($request->post_id);

        if (!$post->notifications_user->contains(Auth::user()->id)) {
            $post->notifications_user()->attach(Auth::user()->id);

            return response()->json(['status' => '200', 'notified' => true, 'message' => 'Successfull']);
        } else {
            $post->notifications_user()->detach([Auth::user()->id]);

            return response()->json(['status' => '200', 'unnotify' => false, 'message' => 'UnSuccessfull']);
        }
    }

    public function addPage($username)
    {
        $category_options = ['' => 'Select Category'] + Category::active()->pluck('name', 'id')->all();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $theme->setTitle(trans('common.create_page').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('timeline/create-page', compact('username', 'category_options'))->render();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name'     => 'required|max:30|min:5',
            'category' => 'required',
            'username' => 'required|max:26|min:5|alpha_num|unique:timelines|no_admin'
        ];

        $messages = [
            'no_admin' => 'The name admin is restricted for :attribute'
        ];

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function groupPageSettingsValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required',
        ]);
    }

    public function createPage(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                  ->withInput($request->all())
                  ->withErrors($validator->errors());
        }

        //Create timeline record for userpage
        $timeline = Timeline::create([
            'username' => $request->username,
            'name'     => $request->name,
            'about'    => $request->about,
            'type'     => 'page',
            ]);

        $page = Page::create([
            'timeline_id'           => $timeline->id,
            'category_id'           => $request->category,
            'member_privacy'        => Setting::get('page_member_privacy'),
            'message_privacy'       => Setting::get('page_message_privacy'),
            'timeline_post_privacy' => Setting::get('page_timeline_post_privacy'),
            ]);

        $role = Role::where('name', '=', 'Admin')->first();
        //below code inserting record in to page_user table
        $page->users()->attach(Auth::user()->id, ['role_id' => $role->id, 'active' => 1]);
        $message = 'Page created successfully';
        $username = $request->username;

        return redirect('/'.$timeline->username);
    }

    public function addGroup($username)
    {
        
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.create_group').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('timeline/create-group', compact('username'))->render();
    }

    public function posts($username)
    {
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        $timeline = Timeline::where('username', $username)->first();
        $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));


        if ($timeline->type == 'user') {
            $follow_user_status = '';
            $user = User::where('timeline_id', $timeline['id'])->first();
            $followRequests = $user->followers()->where('status', '=', 'pending')->get();
            $liked_pages = $user->pageLikes()->get();
            $joined_groups = $user->groups()->get();
            $own_pages = $user->own_pages();
            $own_groups = $user->own_groups();
            $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
            $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
            $joined_groups_count = $user->groups()->where('role_id', '!=', $admin_role_id->id)->where('status', '=', 'approved')->get()->count();
            $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                                ->where('leader_id', '=', $user->id)->first();
            $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
            $guest_events = $user->getEvents();


            if ($follow_user_status) {
                $follow_user_status = $follow_user_status->status;
            }

            $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
            $follow_confirm = $confirm_follow_setting->confirm_follow;

            $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
            $privacy_settings = explode('-', $live_user_settings);
            $timeline_post = $privacy_settings[0];
            $user_post = $privacy_settings[1];
        } else {
            $user = User::where('id', Auth::user()->id)->first();
        }

        $next_page_url = url('ajax/get-more-posts?page=2&username='.$username);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.posts').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));


        return $theme->scope('timeline/posts', compact('timeline', 'user', 'posts', 'liked_pages', 'followRequests', 'joined_groups', 'own_pages', 'own_groups', 'follow_user_status', 'following_count', 'followers_count', 'follow_confirm', 'user_post', 'timeline_post', 'joined_groups_count', 'next_page_url', 'user_events', 'guest_events'))->render();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function groupPageValidator(array $data)
    {
        $rules = [
            'name'     => 'required',
            'username' => 'required|max:16|min:5|alpha_num|unique:timelines|no_admin'
        ];
        
        $messages = [
            'no_admin' => 'The name admin is restricted for :attribute'
        ];

        return Validator::make($data, $rules, $messages);
    }

    public function createGroupPage(Request $request)
    {
        $validator = $this->groupPageValidator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
            ->withInput($request->all())
            ->withErrors($validator->errors());
        }

        //Create timeline record for userpage
        $timeline = Timeline::create([
            'username' => $request->username,
            'name'     => $request->name,
            'about'    => $request->about,
            'type'     => 'group',
            ]);

        if ($request->type == 'open') {
            $group = Group::create([
            'timeline_id'    => $timeline->id,
            'type'           => $request->type,
            'active'         => 1,
            'member_privacy' => 'everyone',
            'post_privacy'   => 'members',
            'event_privacy'  => 'only_admins',
            ]);
        } else {
            $group = Group::create([
                'timeline_id'    => $timeline->id,
                'type'           => $request->type,
                'active'         => 1,
                'member_privacy' => Setting::get('group_member_privacy'),
                'post_privacy'   => Setting::get('group_timeline_post_privacy'),
                'event_privacy'  => Setting::get('group_event_privacy'),
                ]);
        }
        $role = Role::where('name', '=', 'Admin')->first();
        //below code inserting record in to page_user table
        if ($request->type == 'open' || $request->type == 'closed' || $request->type == 'secret') {
            $group->users()->attach(Auth::user()->id, ['role_id' => $role->id, 'status' => 'approved']);
        } else {
            $group->users()->attach(Auth::user()->id, ['role_id' => $role->id]);
        }

        $message = trans('messages.create_page_success');
        $username = $request->username;

        return redirect('/'.$timeline->username);
    }

    public function pagesGroups($username)
    {
        
        $timeline = Timeline::where('username', $username)->with('user')->first();
        if ($timeline == null) {
            return redirect('/');
        }
        if ($timeline->id == Auth::user()->timeline_id) {
            $user = $timeline->user;
            $userPages = $user->own_pages();
            $groupPages = $user->own_groups();
            $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
            $theme->setTitle('Pages & Groups | '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

            return $theme->scope('timeline/pages-groups', compact('username', 'userPages', 'groupPages'))->render();
        } else {
            return redirect($timeline->username);
        }
    }

    public function generalPageSettings($username)
    {
        $timeline = Timeline::where('username', $username)->with('page')->first();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.general_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('page/settings/general', compact('timeline', 'username'))->render();
    }

    public function updateGeneralPageSettings(Request $request)
    {
        $validator = $this->groupPageSettingsValidator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                  ->withInput($request->all())
                  ->withErrors($validator->errors());
        }
        $timeline = Timeline::where('username', $request->username)->first();
        $timeline_values = $request->only('username', 'name', 'about');
        $update_timeline = $timeline->update($timeline_values);

        $page = Page::where('timeline_id', $timeline->id)->first();
        $page_values = $request->only('address', 'phone', 'website');
        $update_page = $page->update($page_values);


        Flash::success(trans('messages.update_Settings_success'));

        return redirect()->back();
    }

    public function privacyPageSettings($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        $page_details = $timeline->page()->first();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.privacy_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('page/settings/privacy', compact('timeline', 'username', 'page_details'))->render();
    }

    public function updatePrivacyPageSettings(Request $request)
    {
        $timeline = Timeline::where('username', $request->username)->first();
        $page = Page::where('timeline_id', $timeline->id)->first();
        $page->timeline_post_privacy = $request->timeline_post_privacy;
        $page->member_privacy = $request->member_privacy;
        $page->save();

        Flash::success(trans('messages.update_privacy_success'));

        return redirect()->back();
    }

    public function rolesPageSettings($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        $page = $timeline->page;
        $page_members = $page->members();
        $roles = Role::pluck('name', 'id');

        $theme = Theme::uses('default')->layout('default');
        $theme->setTitle(trans('common.manage_roles').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('page/settings/roles', compact('timeline', 'page_members', 'roles', 'page'))->render();
    }

    public function likesPageSettings($username)
    {
        $timeline = Timeline::where('username', $username)->with('page')->first();
        $page_likes = $timeline->page->likes()->where('user_id', '!=', Auth::user()->id)->get();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.page_likes').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('page/settings/likes', compact('timeline', 'page_likes'))->render();
    }

    //Getting group members
    public function getGroupMember($username, $group_id)
    {
        $timeline = Timeline::where('username', $username)->with('groups')->first();
        $group = $timeline->groups;
        $group_members = $group->members();
        $group_events = $group->getEvents($group->id);
        $ongoing_events = $group->getOnGoingEvents($group->id);
        $upcoming_events = $group->getUpcomingEvents($group->id);

        $member_role_options = Role::pluck('name', 'id');

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.members').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/members', compact('timeline', 'group_members', 'group', 'group_id', 'member_role_options', 'group_events', 'ongoing_events', 'upcoming_events'))->render();
    }

    //Displaying group admins
    public function getAdminMember($username, $group_id)
    {
        $timeline = Timeline::where('username', $username)->with('groups')->first();
        $group = $timeline->groups;
        $group_admins = $group->admins();
        $group_members = $group->members();
        $member_role_options = Role::pluck('name', 'id');
        $group_events = $group->getEvents($group->id);
        $ongoing_events = $group->getOnGoingEvents($group->id);
        $upcoming_events = $group->getUpcomingEvents($group->id);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.admins').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/admin-group-member', compact('timeline', 'group', 'group_id', 'group_admins', 'member_role_options', 'group_members', 'group_events', 'ongoing_events', 'upcoming_events'))->render();
    }

    //Displaying group members posts
    public function getGroupPosts($username, $group_id)
    {
        $user_post = 'group';
        $timeline = Timeline::where('username', $username)->with('groups')->first();
        $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->get();
        $group = $timeline->groups;
        $group_members = $group->members();
        $group_events = $group->getEvents($group->id);
        $ongoing_events = $group->getOnGoingEvents($group->id);
        $upcoming_events = $group->getUpcomingEvents($group->id);
        $next_page_url = url('ajax/get-more-posts?page=2&username='.$username);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.posts').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('timeline/groupposts', compact('timeline', 'group', 'posts', 'group_members', 'next_page_url', 'user_post', 'username', 'group_events', 'ongoing_events', 'upcoming_events'))->render();
    }

    public function getJoinRequests($username, $group_id)
    {
        $group = Group::findOrFail($group_id);
        $requestedUsers = $group->pending_members();
        $timeline = Timeline::where('username', $username)->first();
        $group_members = $group->members();
        $group_events = $group->getEvents($group->id);
        $ongoing_events = $group->getOnGoingEvents($group->id);
        $upcoming_events = $group->getUpcomingEvents($group->id);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.join_requests').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/joinrequests', compact('timeline', 'username', 'requestedUsers', 'group_id', 'group', 'group_members', 'group_events', 'ongoing_events', 'upcoming_events'))->render();
    }

    //Getting page members with count whose status approved
    public function getPageMember($username)
    {
        $timeline = Timeline::where('username', $username)->with('page')->first();
        $page = $timeline->page;
        $page_members = $page->members();
        $roles = Role::pluck('name', 'id');

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.members').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/pagemembers', compact('timeline', 'page', 'roles', 'page_members'))->render();
    }

    //Displaying admin of the page
    public function getPageAdmins($username)
    {
        $timeline = Timeline::where('username', $username)->with('page')->first();
        $page = $timeline->page;
        $page_admins = $page->admins();
        $page_members = $page->members();
        $roles = Role::pluck('name', 'id');

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.admins').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/pageadmins', compact('timeline', 'page', 'page_admins', 'roles', 'page_members'))->render();
    }

    // Displaying page likes
    public function getPageLikes($username)
    {
        $timeline = Timeline::where('username', $username)->with('page', 'page.likes', 'page.users')->first();
        $page = $timeline->page;
        $page_likes = $page->likes()->get();
        $page_members = $page->members();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.page_likes').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('timeline/page_likes', compact('timeline', 'page', 'page_likes', 'page_members'))->render();
    }

    //Displaying page members posts
    public function getPagePosts($username)
    {
        $user_post = 'page';
        $page_user_id = '';
        $timeline = Timeline::where('username', $username)->with('page', 'page.likes', 'page.users')->first();
        $page = $timeline->page;
        $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->get();
        $page_members = $page->members();
        $next_page_url = url('ajax/get-more-posts?page=2&username='.$username);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.posts').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('timeline/pageposts', compact('timeline', 'posts', 'page', 'page_user_id', 'page_members', 'next_page_url', 'user_post'))->render();
    }

    //Assigning role for a member in group
    public function assignMemberRole(Request $request)
    {
        $chkUser_exists = '';
        $group = Group::findOrFail($request->group_id);
        $chkUser_exists = $group->chkGroupUser($request->group_id, $request->user_id);
        if ($chkUser_exists) {
            $result = $group->updateMemberRole($request->member_role, $request->group_id, $request->user_id);
            if ($result) {
                Flash::success(trans('messages.assign_role_success'));

                return redirect()->back();
            } else {
                Flash::success(trans('messages.assign_role_success'));

                return redirect()->back();
            }
        }
    }

    //Assigning role for a member in page
    public function assignPageMemberRole(Request $request)
    {
        $chkUser_exists = '';
        $page = Page::findOrFail($request->page_id);

        $chkUser_exists = $page->chkPageUser($request->page_id, $request->user_id);

        if ($chkUser_exists) {
            $result = $page->updatePageMemberRole($request->member_role, $request->page_id, $request->user_id);
            if ($result) {
                Flash::success(trans('messages.assign_role_success'));

                return redirect()->back();
            } else {
                Flash::success(trans('messages.assign_role_success'));

                return redirect()->back();
            }
        }
    }

    //Removing member from group
    public function removeGroupMember(Request $request)
    {
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        $chkUser_exists = '';
        $group = Group::findOrFail($request->group_id);

        $group_admins = $group->users()->where('group_id', $group->id)->where('role_id', '=', $admin_role_id->id)->get()->count();
        $group_members = $group->users()->where('group_id', $group->id)->where('user_id', '=', $request->user_id)->first();

        if ($group_members->pivot->role_id == $admin_role_id->id && $group_admins > 1) {
            $chkUser_exists = $group->removeMember($request->group_id, $request->user_id);
        } elseif ($group_members->pivot->role_id != $admin_role_id->id) {
            $chkUser_exists = $group->removeMember($request->group_id, $request->user_id);
        }

        if ($chkUser_exists) {
            if (Auth::user()->id != $request->user_id) {
                //Notify the user for accepting group's join request
                Notification::create(['user_id' => $request->user_id, 'timeline_id' => $group->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.removed_from_group'), 'type' => 'remove_group_member']);
            }

            return response()->json(['status' => '200', 'deleted' => true, 'message' => trans('messages.remove_member_group_success')]);
        } else {
            return response()->json(['status' => '200', 'deleted' => false, 'message' => trans('messages.assign_admin_role_remove')]);
        }
    }

    //Removing member from page
    public function removePageMember(Request $request)
    {
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        $chkUser_exists = '';
        $page = Page::findOrFail($request->page_id);

        $page_admins = $page->users()->where('page_id', $page->id)->where('role_id', '=', $admin_role_id->id)->get()->count();
        $page_members = $page->users()->where('page_id', $page->id)->where('user_id', '=', $request->user_id)->first();

        if ($page_members->pivot->role_id == $admin_role_id->id && $page_admins > 1) {
            $chkUser_exists = $page->removePageMember($request->page_id, $request->user_id);
        } elseif ($page_members->pivot->role_id != $admin_role_id->id) {
            $chkUser_exists = $page->removePageMember($request->page_id, $request->user_id);
        }
          // else{
          //     return response()->json(['status' => '200','deleted' => false,'message'=>'Assign admin role for member and remove']);
          // }

        if ($chkUser_exists) {
            if (Auth::user()->id != $request->user_id) {
                //Notify the user for accepting page's join request
                Notification::create(['user_id' => $request->user_id, 'timeline_id' => $page->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.removed_from_page'), 'type' => 'remove_page_member']);
            }

            return response()->json(['status' => '200', 'deleted' => true, 'message' => trans('messages.remove_member_page_success')]);
        } else {
            return response()->json(['status' => '200', 'deleted' => false, 'message' => trans('messages.assign_admin_role_remove')]);
        }
    }

    public function acceptJoinRequest(Request $request)
    {
        $group = Group::findOrFail($request->group_id);

        $chkUser = $group->chkGroupUser($request->group_id, $request->user_id);


        if ($chkUser) {
            $group_user = $group->updateStatus($chkUser->id);

            if ($group_user) {
                //Notify the user for accepting group's join request
                Notification::create(['user_id' => $request->user_id, 'timeline_id' => $group->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.accepted_join_request'), 'type' => 'accept_group_join']);
            }

            Flash::success('Request Accepted');

            return response()->json(['status' => '200', 'accepted' => true, 'message' => trans('messages.join_request_accept')]);
        }
    }

    public function rejectJoinRequest(Request $request)
    {
        $group = Group::findOrFail($request->group_id);
        $chkUser = $group->chkGroupUser($request->group_id, $request->user_id);

        if ($chkUser) {
            $group_user = $group->decilneRequest($chkUser->id);
            if ($group_user) {
              //Notify the user for rejected group's join request
                Notification::create(['user_id' => $request->user_id, 'timeline_id' => $group->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.rejected_join_request'), 'type' => 'reject_group_join']);
            }

            Flash::success('Request Rejected');

            return response()->json(['status' => '200', 'rejected' => true, 'message' => trans('messages.join_request_reject')]);
        }
    }

    public function updateUserGroupSettings(Request $request, $username)
    {
        $validator = $this->groupPageSettingsValidator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                  ->withInput($request->all())
                  ->withErrors($validator->errors());
        }

        $timeline = Timeline::where('username', $username)->first();
        $timeline->username = $username;
        $timeline->name = $request->name;
        $timeline->about = $request->about;
        $timeline->save();

        $group = Group::where('timeline_id', $timeline->id)->first();
        $group->type = $request->type;
        $group->member_privacy = $request->member_privacy;
        $group->post_privacy = $request->post_privacy;
        $group->event_privacy = $request->event_privacy;
        $group->save();

        Flash::success(trans('messages.update_group_settings'));

        return redirect()->back();
    }

    public function deleteComment(Request $request)
    {
        $comment = Comment::find($request->comment_id);

        if($comment->parent_id != null)
        {
            $parent_comment = Comment::find($comment->parent_id);
            $comment->update(['parent_id' => null]);
            $parent_comment->comments_liked()->detach();
            $parent_comment->delete();
        }
        else
        {
            $comment->comments_liked()->detach();
            $comment->delete();
        }
        if (Auth::user()->id != $comment->user_id) {
          //Check if the user has blocked the post.
          $block = $this->checkIfPostBlocked($comment->post_id,$comment->user->id);
          if ($block == FALSE){
            //Notify the user for comment delete
            // Notification::create(['user_id' => $comment->user->id, 'post_id' => $comment->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.deleted_your_comment'), 'type' => 'delete_comment']);
          }

        }
        return response()->json(['status' => '200', 'deleted' => true, 'message' => 'Comment successfully deleted']);
    }

    public function deletePage(Request $request)
    {
        // $page = Page::where('id', '=', $request->page_id)->first();

        // if ($page->delete()) {
        //     $users = $page->users()->get();
        //     foreach ($users as $user) {
        //         if ($user->id != Auth::user()->id) {
        //             //Notify the user for page delete
        //         Notification::create(['user_id' => $user->id, 'timeline_id' => $page->timeline->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' deleted your page', 'type' => 'delete_page']);
        //         }
        //     }

        //     return response()->json(['status' => '200', 'deleted' => true, 'message' => 'Page successfully deleted']);
        // } else {
        //     return response()->json(['status' => '200', 'notdeleted' => false, 'message' => 'Unsuccessful']);
        // }

        $page = Page::where('id', '=', $request->page_id)->first();

        $page->timeline->reports()->detach();
        $page->users()->detach();
        $page->likes()->detach();

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
        $page->delete();
        $page_timeline->delete();

        return response()->json(['status' => '200', 'deleted' => true, 'message' => 'Page successfully deleted']);
    }

    public function deletePost(Request $request)
    {
        $post = Post::find($request->post_id);
        
        if ($post->user->id == Auth::user()->id) {
            preg_match_all('/(^|\s)(#\w+)/', $post->description, $hashtags);
            foreach ($hashtags[2] as $value) {
                $hashtag = Hashtag::where('tag', str_replace('#', '', $value))->first();
                if ($hashtag) {
                    if($hashtag->count > 1){
                        $hashtag->count = $hashtag->count - 1;
                        $hashtag->save();
                    }
                    else {  
                        $hashtag->delete();
                    }
                }
            }
            $post->deleteMe();
        }
        return response()->json(['status' => '200', 'deleted' => true, 'message' => 'Post successfully deleted']);
    }

    public function reportPost(Request $request)
    {
        $post = Post::where('id', '=', $request->post_id)->first();
        $ifReported = DB::table('post_reports')->where('post_id',$request->post_id)->where('reporter_id',Auth::user()->id)->first();
        if ($ifReported == NULL){
          $reported = $post->managePostReport($request->post_id, Auth::user()->id,$request->description);
        }
        else{
          $reported = FALSE;
        }

        if ($reported) {
            //Notify the user for reporting his post
            // Notification::create(['user_id' => $post->user_id, 'post_id' => $request->post_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.reported_your_post'), 'type' => 'report_post']);

            return response()->json(['status' => '200', 'reported' => true, 'message' => 'Post successfully reported']);
        }
        else{
          return response()->json(['status' => '200', 'reported' => true, 'message' => 'Post already reported']);
        }
    }

    public function reportComment(Request $request)
    {
        $comment = Comment::where('id', '=', $request->comment_id)->first();
        $reported = $comment->manageCommentReport($request->comment_id, Auth::user()->id, $request->description);

        if ($reported) {
            //Notify the user for reporting his comment
            // Notification::create(['user_id' => $comment->user_id, 'comment_id' => $request->comment_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.reported'), 'type' => 'report_comment']);

            return response()->json(['status' => '200', 'reported' => true, 'message' => 'Comment successfully reported']);
        }
    }

    public function singlePost($post_id)
    {
        $mode = 'posts';
        $post = Post::where('id', '=', $post_id)->first();
        $timeline = Auth::user()->timeline;

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        //Redirect to home page if post doesn't exist
        if ($post == null) {
            return redirect('/');
        }

        $url = url('/share/'.$post->id);
        $theme = Theme::uses('default')->layout('default');
        $theme->setTitle(trans('common.post').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        $post_image_source = null;
        $post_image_source_row = $post->images()->where('post_id',$post->id)->first();
        if($post_image_source_row){
            $post_image_source = $post_image_source_row->source;
        }
        

        if($post->type == 'event'){
            if($post_image_source != null) {
                $post_image = env('STORAGE_URL').'uploads/events/covers/'.$post_image_source; 
                $theme->set('meta_image',$post_image);
            }
            $theme->set('meta_site_title',strip_tags($post->timeline->about));
        }
        else {
            if($post_image_source != null) {
                $post_image = env('STORAGE_URL').'uploads/users/gallery/'.$post_image_source; 
                $theme->set('meta_image',$post_image);
            }
            $theme->set('meta_site_title',$post->timeline->username);
        }
        

        $theme->set('meta_url',$url);
        $theme->set('meta_description',strip_tags($post->description));

        return $theme->scope('timeline/single-post', compact('post', 'timeline', 'suggested_users', 'trending_tags', 'suggested_groups', 'suggested_pages', 'mode'))->render();
    }

    public function eventsList(Request $request, $username)
    {
        $mode = "eventlist";

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        
        $user_events = Event::with('timeline')->latest()->get();
        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $event_tags = NULL;
        foreach ($user_events as $user_event) {
            $user_event['transaction'] = DB::table('event_user')->where('event_id',$user_event->id)->first();
            $user_event['registered'] = false;
            $user_event['expired'] = false;
            if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                $event_tags['tags'] = $matches[0];
                $event_tags['event_id'] = $user_event->id;
            }
            if($user_event->users->contains(Auth::user()->id)){
                $user_event['registered'] = true;
            }
            if($user_event->start_date < Carbon::now()){
                $user_event['expired'] = true;
            }
        }

        $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.$username);

        $theme->setTitle(trans('common.events').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('home', compact('event_tags','next_page_url', 'trending_tags', 'suggested_users', 'suggested_groups', 'suggested_pages', 'mode', 'user_events', 'username'))
        ->render();
    }

    public function getEventApi(Request $request) {
    	$location = $request->location;
    	$date     = $request->date;
    	$tag      = $request->tag;
    	$title    = $request->title;
        $username  = $request->username;
        $user_id = '';
        $start_date_time = '';
        if($date && $date != ''){
            $date = date('Y-m-d H:i:s',strtotime($date));
            $start_date_time_temp = new \DateTime($request->date.' 23:59:59');
            $start_date_time =  date_format($start_date_time_temp, 'Y-m-d H:i:s');
        }
        if($username && $username !=''){
            $user_id = Timeline::where('username',$username)->first()->user->id;
            $events = DB::table('events')
            ->join('timelines', 'timelines.id', '=', 'events.timeline_id')
            ->join('event_user', 'event_user.event_id', '=', 'events.id')
            ->where(function ($query)  use ($location){
                    if($location != '') {
                            $query->where('events.location','like','%'.$location.'%');
                    }
            })
            ->where(function($query) use ($date,$start_date_time){
                    if($date != '') {
                        $query->whereDate('events.start_date','<=',$date)->where('events.end_date','>=',$date);
                    }
            })
            ->where(function($query) use ($tag){
                    if($tag != '') {
                        $query->where('timelines.about','like','%'.$tag.'%');
                    }
            })
            ->where(function ($query) use ($title){
                    if($title != '') {
                        $query->where('timelines.name','like','%'.$title.'%');
                    }
            })
            ->where(function ($query) use ($user_id) {
                if($user_id != '') {
                    $query->where('event_user.user_id', $user_id);
                }
            })
            ->select('events.*', 'timelines.*','events.id as event_id')->limit($request->paginate)->offset($request->offset)->orderBy('events.created_at','desc')->get();

        }
        else {
            $events = DB::table('events')
            ->join('timelines', 'timelines.id', '=', 'events.timeline_id')
            ->where(function ($query)  use ($location){
                    if($location != '') {
                            $query->where('events.location','like','%'.$location.'%');
                    }
            })
            ->where(function($query) use ($date,$start_date_time){
                    if($date != '') {
                        $query->whereDate('events.start_date','<=',$date)->where('events.end_date','>=',$date);
                    }
            })
            ->where(function($query) use ($tag){
                    if($tag != '') {
                        $query->where('timelines.about','like','%'.$tag.'%');
                    }
            })
            ->where(function ($query) use ($title){
                    if($title != '') {
                        $query->where('timelines.name','like','%'.$title.'%');
                    }
            })
            ->select('events.*', 'timelines.*','events.id as event_id')->limit($request->paginate)->offset($request->offset)->orderBy('events.created_at','desc')->get();
        }
        
			$a = 0;
			$events = $events->all();
			$post_model = new Post();
			$post_media_model = new PostMedia();
			$media_model = new Media();
			$post = array();
			$post_media = array();
			foreach ($events as $key => $event) {
                $event_timeline;
				$event_media = array();
				$post = $post_model->where('timeline_id','=',$event->id)->get()->toArray();
				if(!empty($post)) {
					$post_media = $post_media_model->where('post_id', '=', $post[0]['id'])
						->get()
						->toArray();
					foreach ($post_media as $post_media_key => $item) {
						$media = $media_model->where('id', '=', $item['media_id'])
							->get()
							->toArray();
						if (isset($media[0])) {
							$event_media [] = $media[0];
						}
					}
					$a = $events[$key];
					$events[$key]->media = array();
					$events[$key]->media = $event_media;
				}
                $event->expired = false;
                if($event->start_date < Carbon::now()){
                    $event->expired = true;
                }
			}
			return response()->json(['status' => '200', 'data' => $events]);

		}

    public function getRegisterButton(Request $request) {
      $date = gmdate('Y-m-d H:i:s');
			$event_id = $request->event_id;
			$user_id  = $request->uid;
			$event_model = new Event();
			//$reg_status = 0 means show reguster button;1 means already registered;2 means do not show register button
			$reg_status = 0;
			$event_user_model = new EventUser();
			$followers_model = new Follower();
			$user_model = new User();
			$event = $event_model->where('id','=',$event_id)->get()->toArray();
			$user = $user_model->where('id',$user_id)->get()->toArray();
			if(!empty($event)) {
				$event_user = $event_user_model->where('user_id', '=', $user_id)
					->where('event_id', '=', $event_id)
					->get()
					->toArray();
        $event_users = $event_user_model
          ->where('event_id', '=', $event_id)
          ->count();
				if (!empty($event_user)) {
					return response()->json([
						'status' => '200',
						'register' => FALSE,
						'error' => TRUE,
						'err_msg' => 'Already Registered',
						'reg_status' => 1
					]);
				}
				if ($event[0]['gender'] != 'all' AND $event[0]['gender'] != $user[0]['gender']){
          return response()->json([
            'status' => '200',
            'register' => FALSE,
            'error' => TRUE,
            'err_msg' => 'Gender Mismatch',
            'reg_status' => 2
          ]);
        }
        if (isset($event[0]['user_limit']) AND $event[0]['user_limit'] == $event_users){
          return response()->json([
            'status' => '200',
            'register' => FALSE,
            'error' => TRUE,
            'err_msg' => 'Registration Full',
            'reg_status' => 2
          ]);
        }
        if ($event[0]['start_date'] < $date){
          return response()->json([
            'status' => '200',
            'register' => FALSE,
            'error' => TRUE,
            'err_msg' => 'Registration Deadline Over',
            'reg_status' => 2
          ]);
        }
				else {
					if ($event[0]['type'] == 'public') {
						return response()->json([
							'status' => '200',
							'register' => TRUE,
							'error' => FALSE,
							'err_msg' => '',
							'reg_status' => 0
						]);
					}
					else {
						$followers = $followers_model->where('leader_id', '=', $event[0]['user_id'])
							->where('follower_id', '=', $user_id)
							->where('status', '=', 'approved')
							->get()
							->toArray();
						if (!empty($followers)) {
							return response()->json([
								'status' => '200',
								'register' => TRUE,
								'error' => FALSE,
								'err_msg' => '',
								'reg_status' => 0
							]);
						}
						else {
							return response()->json([
								'status' => '200',
								'register' => FALSE,
								'error' => TRUE,
								'err_msg' => 'User is not a follower',
								'reg_status' => 2
							]);
						}
					}
				}
			}
			else {
				return response()->json(['status' => '200', 'register' => FALSE, 'error' => TRUE,'err_msg'=>'Event Not Found','reg_status' => 2]);
			}
		}

    public function getPostByEventId(Request $request) {
        $event = Event::find($request->event_id);
        $post = Post::where('timeline_id',$event->timeline_id)->first();
        $request->request->add(['post_id'=>$post->id]);
        return $this->singlePostAPI(request());
    }

	public function getEventPostByEventId(Request $request) {
			$event_id = $request->event_id;
			if($event_id != '') {
				$event = array();
				$user  = array();
				$timeline = array();
				$post = array();
				$post_media = array();
				$media = array();
				$user_timeline = array();

				$event_model = new Event();
				$event = $event_model->where('id', '=', $event_id)->get()->toArray();
				$event = $event[0];
				$post_model = new Post();
				$post = $post_model->where('timeline_id', '=', $event['timeline_id'])
					->get()
					->toArray();
                if(!count($post)) {
                    return response()->json(['status' => '200','error' => TRUE,'err_msg'=>'Event Id Not Found']);
                }
				$post = $post[0];
				if(!empty($post)) {
					$user_model = new User();
					$user = $user_model->where('id', '=', $event['user_id'])
						->get()
						->toArray();
					$user = $user[0];
					$timeline_model = new Timeline();
					$timeline = $timeline_model->where('id', '=', $user['timeline_id'])
						->get()
						->toArray();
					if(isset($timeline[0]))
						$timeline = $timeline[0];

					$user_timeline = $timeline_model->where('id', '=', $user['timeline_id'])
						->get()
						->toArray();
					if(isset($user_timeline[0]))
							$user_timeline = $user_timeline[0];
					$post_media_model = new PostMedia();
					$post_media = $post_media_model->where('post_id', '=', $post['id'])
						->get()
						->toArray();
					if(!empty($post_media)) {
						foreach ($post_media as $key => $item) {
							$media_model = new Media();
							if (!empty($post_media)) {
								$media []= $media_model->where('id', '=', $item['media_id'])
									->get()
									->toArray()[0];
						}

							//$media = $media[0];
						}
					}
				}
				return response()->json(['status' => '200','error' => FALSE,'err_msg'=>'','event'=>$event,'post'=>$post,'event_timeline'=>$timeline,'event_media' => $media,'user_timeline' => $user_timeline]);
			}
			else {
				return response()->json(['status' => '200','error' => TRUE,'err_msg'=>'Event Id Not Found']);
			}

	}

	public function updateEvent(Request $request) {
		$privacy = $request->privacy;
		$gender  = $request->gender;
		$location = $request->location;
		$participant = $request->participant+1;
		$event_id   = $request->event_id;
		$title 			= $request->title;
        //$start_date = date('Y-m-d H:i', strtotime($request->start_date));
        $start_date = Event::find($event_id)->start_date;
        $end_date = Carbon::parse($start_date);
        $end_date = $end_date->addSeconds($request->duration);
		$description = $request->description;
		$event_model = new Event();
		$timeine_model = new Timeline();
		$event = $event_model->where('id','=',$event_id)->get()->toArray();
		DB::table('events')->where('id','=',$event_id)
											->update([	'type' => $privacy,
																	'user_limit' => $participant,
																	'gender' => $gender,
																	'location' => $location,

																	'end_date'   => $end_date
											]);
		DB::table('timelines')->where('id','=',$event[0]['timeline_id'])->update(['name' => $title,'about' => $description]);
		DB::table('posts')->where('timeline_id','=',$event[0]['timeline_id'])->update(['description' => $description]);

        $event_edited = Event::find($event_id);
        $notify_users = $event_edited->users()->where('users.id','!=',Auth::user()->id)->get();

        $post = Post::where('timeline_id',$event_edited->timeline_id)->first();

        preg_match_all('/(^|\s)(#\w+)/', $post->description, $hashtags);
        foreach ($hashtags[2] as $value) {
            $hashtag = Hashtag::where('tag', str_replace('#', '', $value))->first();
            if ($hashtag) {
                $hashtag->count = $hashtag->count + 1;
                $hashtag->save();
            } else {
                Hashtag::create(['tag' => str_replace('#', '', $value), 'count' => 1]);
            }
        }

        foreach ($notify_users as $notify_user) {
            App::setLocale($notify_user->language);
            Notification::create(['user_id' => $notify_user->id, 'timeline_id' => $event_edited->timeline_id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.edited_event'), 'type' => 'edit_event', 'link' => '/post/'.$post->id]);
            App::setLocale(Auth::user()->language);
        }

		return response()->json(['status' => '200','error' => FALSE,'err_msg'=>'','success'=>TRUE]);
	}

	public function setUserBackground(Request $request) {
        User::find(Auth::user()->id)->update(['color_code' => $request->color_code]);
		Session::put('color_code',$request->color_code);
		return response()->json(['status' => '200','error' => FALSE,'err_msg'=>'','success'=>TRUE]);
	}

	public function getUserBackground(Request $request) {
		$user_id = $request->user_id;
		$user_model = new User();
		$user = $user_model->where('id','=',$user_id)->get()->toArray();
		if(!empty($user)) {
			$color_code = $user[0]['color_code'];
			Session::put('color_code', $color_code);
			return response()->json([
				'status' => '200',
				'error' => FALSE,
				'err_msg' => '',
				'success' => TRUE
			]);
		}
		else {
			return response()->json([
				'status' => '200',
				'error' => TRUE,
				'err_msg' => 'User Not Found',
				'success' => FALSE
			]);
		}
	}

	public function getNotificationStatus(Request $request) {
    	$event_post_id = $request->event_post_id;
    	$user_id       = $request->user_id;
    	$block_notification_model = new BlockNotification();
    	$block_notification = $block_notification_model->where('user_id','=',$user_id)->where('post_event_id','=',$event_post_id)->get()->toArray();
    	if(!empty($block_notification)) {
				return response()->json([
					'status' => '200',
					'block_status' => 1,
					'msg' => 'Blocked'
				]);
			}
			else {
				return response()->json([
					'status' => '200',
					'block_status' => 0,
					'msg' => 'Allowed'
				]);
			}
	}

	public function onOffNotification(Request $request) {
		$event_post_id = $request->event_post_id;
		$user_id       = $request->user_id;
		$type          = $request->type;
		$block_notification_model = new BlockNotification();
		$block_notification = $block_notification_model->where('user_id','=',$user_id)->where('post_event_id','=',$event_post_id)->get()->toArray();
		if(!empty($block_notification)) {
			$block_notification_object = $block_notification_model->find($block_notification[0]['id']);
			$block_notification_object->delete();
			return response()->json([
				'status' => '200',
				'block_status' => 0,
				'msg' => 'Allowed'
			]);
		}
		else {
			$block_notification_model->create([
				'user_id' => $user_id,
				'post_event_id' => $event_post_id,
				'type' => $type
			]);
			return response()->json([
				'status' => '200',
				'block_status' => 1,
				'msg' => 'Blocked'
			]);
		}
	}
		
    public function eventsListFilteredLocation(Request $request)
    {
        $mode = "eventlist";

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        
        $location = '%'.$request->location.'%';

        $user_events = Event::where([['user_id', Auth::user()->id],['location','LIKE',$location]])->with('timeline')->latest()->get();
        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $event_tags = NULL;
        if($user_events) {
            foreach ($user_events as $user_event) {
                $user_event['registered'] = false;
                $user_event['expired'] = false;
                if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                    $event_tags['tags'] = $matches[0];
                    $event_tags['event_id'] = $user_event->id;
                }
                if($user_event->users->contains(Auth::user()->id)){
                    $user_event['registered'] = true;
                }
                if($user_event->start_date < Carbon::now()){
                    $user_event['expired'] = true;
                }
            }
        }

        // $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.$username);

        $theme->setTitle(trans('common.events').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('home', compact('event_tags','next_page_url', 'trending_tags', 'suggested_users', 'suggested_groups', 'suggested_pages', 'mode', 'user_events', 'username'))
        ->render();
    }

    public function eventsListFilteredTags(Request $request)
    {
        $mode = "eventlist";

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        
        $tags = "#".$request->tags;

        $user_events_all = Event::where('user_id', Auth::user()->id)->with('timeline')->latest()->get();
        $id = Auth::id();

        $user_events = NULL;
        foreach ($user_events_all as $key => $value) {
            if(strpos($value->timeline->about, $tags) !== false){
                $user_events[$key] = $value;
            }
        }

        $event_tags = NULL;
        if($user_events) {
            foreach ($user_events as $user_event) {
                $user_event['registered'] = false;
                $user_event['expired'] = false;
                if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                    $event_tags['tags'] = $matches[0];
                    $event_tags['event_id'] = $user_event->id;
                }
                if($user_event->users->contains(Auth::user()->id)){
                    $user_event['registered'] = true;
                }
                if($user_event->start_date < Carbon::now()){
                    $user_event['expired'] = true;
                }
            }
        }

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        // $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.$username);

        $theme->setTitle(trans('common.events').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('home', compact('event_tags','next_page_url', 'trending_tags', 'suggested_users', 'suggested_groups', 'suggested_pages', 'mode', 'user_events', 'username'))
        ->render();
    }

    public function eventsListFilteredDate(Request $request)
    {
        $mode = "eventlist";

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        
        $date = $request->date;

        $user_events = Event::where([['user_id', Auth::user()->id],['start_date','<=',$date],['end_date','>=',$date]])->with('timeline')->latest()->get();
        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        // $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.$username);

        $event_tags = NULL;
        if($user_events) {
            foreach ($user_events as $user_event) {
                $user_event['registered'] = false;
                $user_event['expired'] = false;
                if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                    $event_tags['tags'] = $matches[0];
                    $event_tags['event_id'] = $user_event->id;
                }
                if($user_event->users->contains(Auth::user()->id)){
                    $user_event['registered'] = true;
                }
                if($user_event->start_date < Carbon::now()){
                    $user_event['expired'] = true;
                }
            }
        }

        $theme->setTitle(trans('common.events').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('home', compact('event_tags','next_page_url', 'trending_tags', 'suggested_users', 'suggested_groups', 'suggested_pages', 'mode', 'user_events', 'username'))
        ->render();
    }

    public function eventsListFilteredTitle(Request $request)
    {
        $mode = "eventlist";

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        
        $title = $request->title;

        $user_events_all = Event::where('user_id', Auth::user()->id)->with('timeline')->latest()->get();
        $id = Auth::id();

        $user_events = NULL;
        foreach ($user_events_all as $key => $value) {
            if(strpos($value->timeline->name, $title) !== false){
                $user_events[$key] = $value;
            }
        }

        $event_tags = NULL;
        if($user_events) {
            foreach ($user_events as $user_event) {
                $user_event['registered'] = false;
                $user_event['expired'] = false;
                if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                    $event_tags['tags'] = $matches[0];
                    $event_tags['event_id'] = $user_event->id;
                }
                if($user_event->users->contains(Auth::user()->id)){
                    $user_event['registered'] = true;
                }
                if($user_event->start_date < Carbon::now()){
                    $user_event['expired'] = true;
                }
            }
        }

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        // $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.$username);

        $theme->setTitle(trans('common.events').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('home', compact('event_tags','next_page_url', 'trending_tags', 'suggested_users', 'suggested_groups', 'suggested_pages', 'mode', 'user_events', 'username'))
        ->render();
    }

    public function addEvent($username, $group_id = null)
    {
        $timeline_name = '';
        if ($group_id) {
            $group = Group::find($group_id);
            $timeline_name = $group->timeline->name;
        }

        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        return $theme->scope('event-create', compact('suggested_users', 'suggested_groups', 'suggested_pages', 'username', 'group_id', 'timeline_name'))
            ->render();
    }

     /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateEventPage(array $data)
    {
        return Validator::make($data, [
            'name'        => 'required|max:45|min:5',
            'start_date'  => 'required',
            'duration'    => 'required|max:2:min:1',
            'location'    => 'required',
            'type'        => 'required',
            'event_images_upload' => 'required',
            'duration'    => 'required',
        ]);
    }

    public function uploadEventImages(Request $request) {
        if ($request->file('event_images_upload')) {
            $eventImage = $request->file('event_images_upload');
            $strippedName = str_replace(' ', '', $eventImage->getClientOriginalName());

            $avatar = Image::make($eventImage->getRealPath())->orientate();

            $mime = $avatar->mime();
            if ($mime == 'image/jpeg')
                $extension = '.jpg';
            elseif ($mime == 'image/png')
                $extension = '.png';
            elseif ($mime == 'image/gif')
                $extension = '.gif';
            else
                $extension = '';
            $photoName = hexdec(uniqid()).'_'.str_replace('.','',microtime(true)).Auth::user()->id.$extension;

            $avatar->save(storage_path().'/uploads/events/covers/'.$photoName, 60);

            //image width 400
            $avatar_thumbnail_400 = $avatar;
            $photoName_thumbnail_400 = '400_'.$photoName;
            $avatar_thumbnail_400 = $avatar_thumbnail_400->resize(400,null,function ($constraint) {
                $constraint->aspectRatio();
            });
            $avatar_thumbnail_400->save(storage_path().'/uploads/events/covers/'.$photoName_thumbnail_400, 60);

            //image width 50
            $avatar_thumbnail_50 = $avatar;
            $avatar_thumbnail_50 = $avatar_thumbnail_50->resize(50,null,function ($constraint) {
                $constraint->aspectRatio();
            });
            $photoName_thumbnail_50 = '50_'.$photoName;
            $avatar_thumbnail_50->save(storage_path().'/uploads/events/covers/'.$photoName_thumbnail_50, 60);

            return response()->json(['status' => '200', $photoName]);
        }
        else {
            return response()->json(['status' => '200', 'no data received']);
        }
    }

    public function createEvent($username, Request $request)
    {
        $validator = $this->validateEventPage($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                  ->withInput($request->all())
                  ->withErrors($validator->errors());
        }

        $start_date = date('Y-m-d H:i', strtotime($request->start_date));
        // $end_date  = date('Y-m-d H:i', strtotime($request->end_date));

        $end_date = Carbon::parse($start_date);
        $end_date = $end_date->addSeconds($request->duration);
        if ($start_date >= date('Y-m-d', strtotime(Carbon::now())) && $end_date >= $start_date) {
            $user_timeline = Timeline::where('username', $username)->first();
            $i = 0;

            if ($request->event_images_upload) {
                foreach ($request->event_images_upload as $eventImage) {
                    
                    $media = Media::create([
                      'title'  => $eventImage,
                      'type'   => 'image',
                      'source' => $eventImage,
                    ]);

                    $media_to_attach[$i] = $media;
                    $i++;

                    $timeline = Timeline::create([
                        'username'  => $user_timeline->gen_num(),
                        'name'      => $request->name,
                        'about'     => nl2br($request->about),
                        'cover_id'  => $media->id,
                        'type'      => 'event',
                    ]);
                }
            }
            else {
                $timeline = Timeline::create([
                    'username'  => $user_timeline->gen_num(),
                    'name'      => $request->name,
                    'about'     => nl2br($request->about),
                    'type'      => 'event',
                    ]);

            }

            $post = Post::create([
                'type' => 'event',
                'description' => nl2br($request->about),
                'timeline_id' => $timeline->id,
                'user_id'     => Auth::user()->id,
                'active'      => '1',
                'location'    => $request->location,
            ]);
            if($i != 0){
                foreach($media_to_attach as $attach_media){
                    $post->images()->attach($attach_media);
                }
            }

            $event = Event::create([
                'timeline_id' => $timeline->id,
                'type'        => $request->type,
                'user_id'     => Auth::user()->id,
                'user_limit'  => $request->user_limit+1,
                'price'       => $request->price,
                'currency'    => $request->currency,
                'location'    => $request->location,
                'start_date'  => $start_date,
                'end_date'    => $end_date,
                'invite_privacy'        => Setting::get('invite_privacy'),
                'timeline_post_privacy' => Setting::get('event_timeline_post_privacy'),
                'focus' => $request->focus,
                'gender' => $request->gender,
                'frequency' => $request->frequency,
                ]);

            if ($request->group_id) {
                $event->group_id = $request->group_id;
                $event->save();
            }

            // Check for any hashtags and save them
            preg_match_all('/(^|\s)(#\w+)/', $request->about, $hashtags);
            foreach ($hashtags[2] as $value) {
                $timeline = Timeline::where('username', str_replace('@', '', $value))->first();
                $hashtag = Hashtag::where('tag', str_replace('#', '', $value))->first();
                if ($hashtag) {
                    $hashtag->count = $hashtag->count + 1;
                    $hashtag->save();
                } else {
                    Hashtag::create(['tag' => str_replace('#', '', $value), 'count' => 1]);
                }
            }

            $event->users()->attach(Auth::user()->id);
            Flash::success(trans('messages.create_event_success'));
            $page = Auth::user()->username.'/events';
            return redirect($page);
        } else {
            $message = 'Invalid date selection';
            return redirect()->back()->with('message', trans('messages.invalid_date_selection'));
        }
    }

    //Displaying event posts
    public function getEventPosts($username)
    {
        $user_post = 'event';
        $timeline = Timeline::where('username', $username)->with('event', 'event.users')->first();
        $event = $timeline->event;

        if (!$event->is_eventadmin(Auth::user()->id, $event->id) &&  !$event->users->contains(Auth::user()->id)) {
            return redirect($username);
        }

        $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->get();
      
        $next_page_url = url('ajax/get-more-posts?page=2&username='.$username);

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.posts').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('timeline/eventposts', compact('timeline', 'posts', 'event', 'next_page_url', 'user_post'))->render();
    }

     //Displaying event guests
    public function displayGuests($username)
    {
        $timeline = Timeline::where('username', $username)->with('event')->first();
        $event = $timeline->event;
        $event_guests = $event->guests($event->user_id);
        
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.invitemembers').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('users/eventguests', compact('timeline', 'event', 'event_guests'))->render();
    }

    public function generalEventSettings($username)
    {
        $timeline = Timeline::where('username', $username)->with('event')->first();

        $event_details = $timeline->event()->first();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.general_settings').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('event/settings', compact('timeline', 'username', 'event_details'))->render();
    }

    public function updateUserEventSettings($username, Request $request)
    {
        $validator = $this->validateEventPage($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                  ->withInput($request->all())
                  ->withErrors($validator->errors());
        }

        $start_date = date('Y-m-d H:i', strtotime($request->start_date));
        $end_date  = date('Y-m-d H:i', strtotime($request->end_date));
        
        if ($start_date >= date('Y-m-d', strtotime(Carbon::now())) && $end_date >= $start_date) {
            $timeline = Timeline::where('username', $username)->first();
            $timeline_values = $request->only('name', 'about');
            $update_timeline = $timeline->update($timeline_values);

            $event = Event::where('timeline_id', $timeline->id)->first();
            $event_values = $request->only('type', 'location', 'invite_privacy', 'timeline_post_privacy');
            $event_values['start_date'] = date('Y-m-d H:i', strtotime($request->start_date));
            $event_values['end_date'] = date('Y-m-d H:i', strtotime($request->end_date));
            $update_values = $event->update($event_values);

            if ($request->group_id) {
                $event->group_id = $request->group_id;
                $event->save();
            }

            Flash::success(trans('messages.update_event_Settings'));
            return redirect()->back();
        } else {
            Flash::error(trans('messages.invalid_date_selection'));
            return redirect()->back();
        }
    }

    public function deleteEvent(Request $request)
    {
        $event = Event::find($request->event_id);

        //Deleting event notifications
        $timeline_alerts = $event->timeline()->with('notifications')->first();

        if (count($timeline_alerts->notifications) != 0) {
            foreach ($timeline_alerts->notifications as $notification) {
                $notification->delete();
            }
        }

        $notify_users = $event->users()->where('users.id','!=',Auth::user()->id)->get();

        $post = Post::where('timeline_id',$event->timeline_id)->first();

        foreach ($notify_users as $notify_user) {
            App::setLocale($notify_user->language);
            Notification::create(['user_id' => $notify_user->id, 'timeline_id' => $event->timeline_id, 'post_id' => $post->id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' deleted event', 'type' => 'delete_event', 'link' => '/']);
            App::setLocale(Auth::user()->language);
        }

        // Deleting event posts
        $event_posts = $event->timeline()->with('posts')->first();
        
        if (count($event_posts->posts) != 0) {
            foreach ($event_posts->posts as $post) {
                preg_match_all('/(^|\s)(#\w+)/', $post->description, $hashtags);
                foreach ($hashtags[2] as $value) {
                    $hashtag = Hashtag::where('tag', str_replace('#', '', $value))->first();
                    if ($hashtag) {
                        if($hashtag->count > 1){
                            $hashtag->count = $hashtag->count - 1;
                            $hashtag->save();
                        }
                        else {  
                            $hashtag->delete();
                        }
                    }
                }
                $post->deleteMe();
            }
        }

        $event->users()->detach();
        $event_timeline = $event->timeline();
        $event->delete();
        $event_timeline->delete();
        
        return response()->json(['status' => '200', 'deleted' => true, 'message' => 'Event successfully deleted']);
    }

    public function allNotifications(Request $request)
    {
        $mode = 'notifications';
        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();
        $theme = Theme::uses('default')->layout('default');
        $theme->setTitle(trans('common.notifications').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('timeline/single-post', compact('notifications', 'suggested_users', 'trending_tags', 'suggested_groups', 'suggested_pages', 'mode'))->render();
    }

    public function deleteNotification(Request $request)
    {
        $notification = Notification::find($request->notification_id);
        if ($notification->delete()) {
            Flash::success(trans('messages.notification_deleted_success'));

            return response()->json(['status' => '200', 'notify' => true, 'message' => 'Notification deleted successfully']);
        }
    }

    public function deleteAllNotifications(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->get();

        if ($notifications) {
            foreach ($notifications as $notification) {
                $notification->delete();
            }

            Flash::success(trans('messages.notifications_deleted_success'));
            return response()->json(['status' => '200', 'allnotify' => true, 'message' => 'Notifications deleted successfully']);
        }
    }
    
    public function hidePost(Request $request)
    {
        $post = Post::where('id', '=', $request->post_id)->first();

        if ($post->user->id == Auth::user()->id) {
            $post->active = 0;
            $post->save();

            return response()->json(['status' => '200', 'hide' => true, 'message' => 'Post is hidden successfully']);
        } else {
            return response()->json(['status' => '200', 'unhide' => false, 'message' => 'Unsuccessful']);
        }
    }

    public function linkPreview()
    {
        $linkPreview = new LinkPreview('http://github.com');
        $parsed = $linkPreview->getParsed();
        foreach ($parsed as $parserName => $link) {
            echo $parserName. '<br>' ;
            echo $link->getUrl() . PHP_EOL;
            echo $link->getRealUrl() . PHP_EOL;
            echo $link->getTitle() . PHP_EOL;
            echo $link->getDescription() . PHP_EOL;
            echo $link->getImage() . PHP_EOL;
            print_r($link->getPictures());
            dd();
        }
    }

    public function deleteGroup(Request $request)
    {
        $group = Group::where('id', '=', $request->group_id)->first();
        
        $group->timeline->reports()->detach();
        
        //Deleting events in a group
        if (count($group->getEvents()) != 0) {
            foreach ($group->getEvents() as $event) {
                $event->users()->detach();

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
        
        $timeline_alerts = $group->timeline()->with('notifications')->first();

        if (count($timeline_alerts->notifications) != 0) {
            foreach ($timeline_alerts->notifications as $notification) {
                $notification->delete();
            }
        }
        $timeline_posts = $group->timeline()->with('posts')->first();
        
        if (count($timeline_posts->posts) != 0) {
            foreach ($timeline_posts->posts as $post) {
                $post->deleteMe();
            }
        }
        $group_timeline = $group->timeline();
        $group->delete();
        $group_timeline->delete();

        return response()->json(['status' => '200', 'deleted' => true, 'message' => 'Group successfully deleted']);
    }

    public function allAlbums($username)
    {
        $mode = "showfeed";
        $user_post = 'showfeed';
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $timeline = Timeline::where('username', Auth::user()->username)->first();

        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $announcement = Announcement::find(Setting::get('announcement'));
        if ($announcement != null) {
            $chk_isExpire = $announcement->chkAnnouncementExpire($announcement->id);

            if ($chk_isExpire == 'notexpired') {
                $active_announcement = $announcement;
                if (!$announcement->users->contains(Auth::user()->id)) {
                    $announcement->users()->attach(Auth::user()->id);
                }
            }
        }


        // $next_page_url = url('ajax/get-more-feed-by-location?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.Auth::user()->username);

        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/index', compact('active_announcement', 'username'))
            ->render();
    }

    public function allPhotos($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        $albums = $timeline->albums()->get();

        if (count($albums) > 0) {
            foreach ($albums as $album) {
                $photos[] = $album->photos()->where('type', 'image')->get();
            }
            foreach ($photos as $photo) {
                foreach ($photo as $image) {
                    $images[] = $image;
                }
            }
        }
        $trending_tags = trendingTags();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(Auth::user()->name.' '.Setting::get('title_seperator').' '.trans('common.photos').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/photos', compact('timeline', 'images', 'trending_tags'))->render();
    }

    public function allVideos($username)
    {
        $timeline = Timeline::where('username', $username)->first();
        if (Setting::get('announcement') != null) {
            $election = Announcement::find(Setting::get('announcement'));
        }

        $albums = $timeline->albums()->get();

        if (count($albums) > 0) {
            foreach ($albums as $album) {
                $photos[] = $album->photos()->where('type', 'youtube')->get();
            }
            foreach ($photos as $photo) {
                foreach ($photo as $video) {
                    $videos[] = $video;
                }
            }
        }

        $trending_tags = trendingTags();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(Auth::user()->name.' '.Setting::get('title_seperator').' '.trans('common.photos').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/videos', compact('timeline', 'videos', 'trending_tags', 'election'))->render();
    }

    public function viewAlbum($username, $id)
    {
        $timeline = Timeline::where('username', $username)->first();
        $album = Album::where('id', $id)->with('photos')->first();

        $trending_tags = trendingTags();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle($album->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/show', compact('timeline', 'album', 'trending_tags'))->render();
    }

    public function albumPhotoEdit(Request $request)
    {
        $media = Media::find($request->media_id);
        if ($media->source) {
            return response()->json(['status' => '200', 'photo_src' => true, 'pic_source' => $media->source]);
        } else {
            return response()->json(['status' => '200', 'photo_src' => false]);
        }
    }

    public function createAlbum($username)
    {
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $timeline = Timeline::where('username', Auth::user()->username)->first();
        
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.create_album').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/create', compact('suggested_users', 'suggested_groups', 'suggested_pages', 'timeline'))->render();
    }

    protected function albumValidator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:30|min:5',
            'privacy'  => 'required'
          ]);
    }

    public function saveAlbum(Request $request, $username)
    {
        // $validator = $this->albumValidator($request->only('name','privacy'));

        // if ($validator->fails()) {
        //     return redirect()->back()
        //           ->withInput($request->all())
        //           ->withErrors($validator->errors());
        // }

        if ($request->album_photos[0] == null || $request->name == null || $request->privacy == null) {
            Flash::error(trans('messages.album_validation_error'));
            return redirect()->back();
        }

        $input = $request->except('_token', 'album_photos');
        $input['timeline_id'] = Timeline::where('username', $username)->first()->id;
        $album = Album::create($input);

        foreach ($request->album_photos as $album_photo) {
            $strippedName = str_replace(' ', '', $album_photo->getClientOriginalName());
            $photoName = date('Y-m-d-H-i-s').$strippedName;
            $photo = Image::make($album_photo->getRealPath());
            $photo->save(storage_path().'/uploads/albums/'.$photoName, 60);

            $media = Media::create([
              'title'  => $album_photo->getClientOriginalName(),
              'type'   => 'image',
              'source' => $photoName,
            ]);

            $album->photos()->attach($media->id);
        }

        if ($request->album_videos[0] != null) {
            foreach ($request->album_videos as $album_video) {
                $match;
                if (preg_match("/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/", $album_video, $match)) {
                    if ($match[2] != null) {
                        $videoId = Youtube::parseVidFromURL($album_video);
                        $video = Youtube::getVideoInfo($videoId);
                
                        $video = Media::create([
                        'title'  => $video->snippet->title,
                        'type'   => 'youtube',
                        'source' => $videoId,
                        ]);
                        $album->photos()->attach($video->id);
                    } else {
                        Flash::error(trans('messages.not_valid_url'));
                        return redirect()->back();
                    }
                } else {
                    Flash::error(trans('messages.not_valid_url'));
                    return redirect()->back();
                }
            }
        }

        if ($album) {
            Flash::success(trans('messages.create_album_success'));
            return redirect('/'.$username.'/album/show/'.$album->id);
        } else {
            Flash::error(trans('messages.create_album_error'));
        }
        return redirect()->back();
    }

    public function editAlbum($username, $id)
    {
        $album = Album::where('id', $id)->with('photos')->first();

        $trending_tags = trendingTags();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle($album->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('albums/edit', compact('album', 'trending_tags'))->render();
    }

    public function updateAlbum($username, $id, Request $request)
    {
        // $validator = $this->albumValidator($request->all());

        // if ($validator->fails()) {
        //     return redirect()->back()
        //           ->withInput($request->all())
        //           ->withErrors($validator->errors());
        // }
        if ($request->name == null || $request->privacy == null) {
            Flash::error(trans('messages.album_validation_error'));
            return redirect()->back();
        }

        $album = Album::findOrFail($id);
        $input = $request->except('_token', 'album_photos');
        $album->update($input);
        
        if ($request->album_photos[0] != null) {
            foreach ($request->album_photos as $album_photo) {
                $strippedName = str_replace(' ', '', $album_photo->getClientOriginalName());
                $photoName = date('Y-m-d-H-i-s').$strippedName;
                $photo = Image::make($album_photo->getRealPath());
                $photo->save(storage_path().'/uploads/albums/'.$photoName, 60);

                $media = Media::create([
                  'title'  => $album_photo->getClientOriginalName(),
                  'type'   => 'image',
                  'source' => $photoName,
                ]);

                $album->photos()->attach($media->id);
            }
        }

        if ($request->album_videos[0] != null) {
            foreach ($request->album_videos as $album_video) {
                $match;
                if (preg_match("/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/", $album_video, $match)) {
                    if ($match[2] != null) {
                        $videoId = Youtube::parseVidFromURL($album_video);
                        $video = Youtube::getVideoInfo($videoId);
                
                        $video = Media::create([
                        'title'  => $video->snippet->title,
                        'type'   => 'youtube',
                        'source' => $videoId,
                        ]);
                        $album->photos()->attach($video->id);
                    } else {
                        Flash::error(trans('messages.not_valid_url'));
                        return redirect()->back();
                    }
                } else {
                    Flash::error(trans('messages.not_valid_url'));
                    return redirect()->back();
                }
            }
        }

        if ($album) {
            Flash::success(trans('messages.update_album_success'));
            return redirect('/'.$username.'/album/show/'.$album->id);
        } else {
            Flash::error(trans('messages.update_album_error'));
        }
        return redirect()->back();
    }

    public function deleteAlbum($username, $photo_id)
    {
        $album = Album::findOrFail($photo_id);
        $album->photos()->detach();
        if ($album->delete()) {
            Flash::success(trans('messages.delete_album_success'));
        } else {
            Flash::error(trans('messages.delete_album_error'));
        }
        return redirect('/'.$username.'/albums');
    }

    public function addPreview($username, $id, $photo_id)
    {
        $album = Album::findOrFail($id);
        $album->preview_id = $photo_id;
        if ($album->save()) {
            Flash::success(trans('messages.update_preview_success'));
        } else {
            Flash::error(trans('messages.update_preview_error'));
        }
        return redirect()->back();
    }

    public function deleteMedia($username, $photo_id)
    {
        $media = Media::find($photo_id);
        $media->albums()->where('preview_id', $media->id)->update(['albums.preview_id' => null]);
        $media->albums()->detach();
      
        if ($media->delete()) {
            Flash::success(trans('messages.delete_media_success'));
        } else {
            Flash::error(trans('messages.delete_media_error'));
        }
        return redirect()->back();
    }
    
    public function unjoinPage(Request $request)
    {
        $page = Page::where('timeline_id', '=', $request->timeline_id)->first();

        if ($page->users->contains(Auth::user()->id)) {
            $page->users()->detach([Auth::user()->id]);

            return response()->json(['status' => '200', 'join' => true, 'username'=> Auth::user()->username, 'message' => 'successfully unjoined']);
        }
    }

    public function saveWallpaperSettings($username, Request $request)
    {
        if($request->wallpaper == null)
        {
            Flash::error(trans('messages.no_file_added'));
            return redirect()->back();
        }

        $timeline = Timeline::where('username', $username)->first();
        $result = $timeline->saveWallpaper($request->wallpaper);
        if($result)
        {
            Flash::success(trans('messages.wallpaper_added_activated'));
            return redirect()->back();
        }
    }

    public function toggleWallpaper($username,$action, Media $media)
    {
        $timeline = Timeline::where('username', $username)->first();
        
        $result = $timeline->toggleWallpaper($action, $media);

        if($result == 'activate')
        {
            Flash::success(trans('messages.activate_wallpaper_success'));
        }
        if($result == 'deactivate')
        {
            Flash::success(trans('messages.deactivate_wallpaper_success'));
        }
        return Redirect::back();
    }

    public function pageWallpaperSettings($username)
    {
      $timeline = Timeline::where('username', $username)->first();
      $wallpapers = Wallpaper::all();

      $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.wallpaper_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('page/settings/wallpaper', compact('timeline', 'wallpapers'))->render();
    }

    public function groupGeneralSettings($username)
    {
        $timeline = Timeline::where('username', $username)->first();

        $group_details = $timeline->groups()->first();

        $group = Group::where('timeline_id', '=', $timeline->id)->first();

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.group_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('group/settings/general', compact('timeline', 'username', 'group_details'))->render();
    }

    public function groupWallpaperSettings($username)
    {
      $timeline = Timeline::where('username', $username)->first();
      $wallpapers = Wallpaper::all();

      $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        $theme->setTitle(trans('common.wallpaper_settings').' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('group/settings/wallpaper', compact('timeline', 'wallpapers'))->render();
    }

    public function saveTimeline(Request $request)
    {
        $timeline = Timeline::find($request->timeline_id);
        if($timeline == null)
        {
           return response()->json(['status' => '201', 'message' => 'Invalid Timeline']); 
        }
        if(Auth::user()->timelinesSaved()->where('timeline_id',$request->timeline_id)->where('saved_timelines.type', $timeline->type)->get()->isEmpty())
        {
            Auth::user()->timelinesSaved()->attach($timeline->id, ['type' => $timeline->type]);
            return response()->json(['status' => '200', 'message' => $timeline->type.' saved successfully']);
        }
        else
        {
            Auth::user()->timelinesSaved()->detach($timeline->id);
            return response()->json(['status' => '200', 'message' => $timeline->type.' unsaved successfully']);
        }
    }

    public function savePost(Request $request)
    {
        $post = Post::find($request->post_id);
        if($post == null)
        {
           return response()->json(['status' => '201', 'message' => 'Invalid Post']); 
        }
        if(Auth::user()->postsSaved()->where('post_id',$request->post_id)->get()->isEmpty())
        {
            Auth::user()->postsSaved()->attach($post->id);
            return response()->json(['status' => '200', 'type' => 'save', 'message' => 'Post saved successfully']);
        }
        else
        {
            Auth::user()->postsSaved()->detach($post->id);
            return response()->json(['status' => '200', 'type' => 'unsave', 'message' => 'Post unsaved successfully']);
        }
    }

    public function switchLanguage(Request $request)
    {
        if(Auth::user()){
            Auth::user()->update(['language' => $request->language]);
            App::setLocale($request->language);
        }
        else {
            $request->session()->put('guest_locale',$request->language);
        }
        return response()->json(['status' => '200', 'message' => 'Switched language to '.$request->language]);
    }

    public function getLocation(Request $request)
    {
        $location = $request->location;

        $mode = "showfeed";
        $user_post = 'showfeed';
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $timeline = Timeline::where('username', Auth::user()->username)->first();

        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        // Check for hashtag
        if ($request->hashtag) {
            $hashtag = '#'.$request->hashtag;

            $posts = Post::where([['description', 'like', "%{$hashtag}%"],['location','like',$location],['active',1]])->latest()->paginate(Setting::get('items_page'));
        } // else show the normal feed
        else {
            $posts = Post::where('location',$location)->whereIn('user_id', function ($query) use ($id) {
                $query->select('leader_id')
                    ->from('followers')
                    ->where('follower_id', $id);
            })->orWhere('user_id', $id)->where([['active', 1],['location','like',$location]])->latest()->paginate(Setting::get('items_page'));
        }


        $announcement = Announcement::find(Setting::get('announcement'));
        if ($announcement != null) {
            $chk_isExpire = $announcement->chkAnnouncementExpire($announcement->id);

            if ($chk_isExpire == 'notexpired') {
                $active_announcement = $announcement;
                if (!$announcement->users->contains(Auth::user()->id)) {
                    $announcement->users()->attach(Auth::user()->id);
                }
            }
        }


        // $next_page_url = url('ajax/get-more-feed-by-location?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.Auth::user()->username);

        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('location', compact('timeline', 'location' , 'posts', 'next_page_url', 'trending_tags', 'suggested_users', 'active_announcement', 'suggested_groups', 'suggested_pages', 'mode', 'user_post'))
        ->render();
        
    }

    public function getEventByHashtag(Request $request) {
        $mode = "eventlist";

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $tags = "#".$request->tags;

        $user_events_all = Event::where('user_id', Auth::user()->id)->with('timeline')->latest()->get();
        $id = Auth::id();

        $user_events = NULL;
        foreach ($user_events_all as $key => $value) {
            if(strpos($value->timeline->about, $tags) !== false){
                $user_events[$key] = $value;
            }
        }

        $event_tags = NULL;
        $request_tags = $request->hashtag;
        if($user_events) {
            foreach ($user_events as $user_event) {
                $user_event['registered'] = false;
                $user_event['expired'] = false;
                if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                    $event_tags['tags'] = $matches[0];
                    $event_tags['event_id'] = $user_event->id;
                }
                if($user_event->users->contains(Auth::user()->id)){
                    $user_event['registered'] = true;
                }
                if($user_event->start_date < Carbon::now()){
                    $user_event['expired'] = true;
                }
            }
        }

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        // $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.$username);

        $theme->setTitle(trans('common.events').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('event-by-hashtag', compact('event_tags','request_tags','next_page_url', 'trending_tags', 'suggested_users', 'suggested_groups', 'suggested_pages', 'mode', 'user_events', 'username'))
            ->render();
    }

    public function editEvent(Request $request){
        $event = Event::find($request->event_id);

        $input = $request->all();

        $event->fill($input);
        $event->save();
    }

    public function suggestedUsersAPI() {
        $suggested_users = suggestedUsers();
        return response()->json(['status' => '200', ['suggested_users'=>$suggested_users]]);
    }

    public function postAPI(Request $request)
    {
        $timeline = Timeline::where('username', $request->username)->first();
        $id = $timeline->user->id;
        if($request->profile_timeline) {
            $posts = Post::where('user_id', $id)->where('active', 1)->latest()->with('timeline')->limit($request->paginate)->offset($request->offset)->get();
        }
        else {
            $posts = Post::whereIn('user_id', function ($query) use ($id) {
                $query->select('leader_id')
                    ->from('followers')
                    ->where([['follower_id', $id],['status','approved']]);
            })->orWhere('user_id', $id)->where('active', 1)->latest()->with('timeline')->limit($request->paginate)->offset($request->offset)->get();
        }

        // $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('timeline')->limit($request->paginate)->offset($request->offset)->get();

        foreach ($posts as $post) {
            if($post->images()->count() > 0) {
                $post['images'] = $post->images()->get();
            }
            if($post->comments()->count() > 0) {
                $post['comments'] = $post->comments()->where('user_id',$post->user_id)->get();
            }

            $post['likes_count'] = $post->users_liked()->count();
            $post['user_liked'] = false;

            if($post['likes_count'] > 0) {
                if($post->users_liked()->where('user_id',Auth::user()->id)->count()) {
                    $post['user_liked'] = true;
                }
            }

            if($post->type == 'event'){
                $event = Event::where('timeline_id',$post->timeline_id)->latest()->get();
                if(count($event)){
                    $post['event'] = $event;
                    $event = $event->toArray();
                    $creatorId = $event[0]['user_id'];
                    $creator = DB::table('users')->where('id',$creatorId)->first();
                    $creatorTimeline = Timeline::where('id',$creator->timeline_id)->first();
                    $post['creator_timeline'] = $creatorTimeline;
                    foreach ($post['event'] as $user_event) {
                        $user_event['event_details'] = $user_event->timeline->username;
                        if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                            $user_event['event_tags'] = $matches[0];
                        }
                        if($user_event->users->contains(Auth::user()->id)){
                            $user_event['registered'] = true;
                        }
                        if($user_event->start_date < Carbon::now()){
                            $user_event['expired'] = true;
                        }
                    }
                }
            }
        }

        $post_image_path = storage_path().'/uploads/users/gallery/';
        $event_image_path = storage_path().'/uploads/events/covers/';

        return response()->json(['status' => '200', ['posts'=>$posts, 'timeline'=>$timeline, 'postImagePath'=>$post_image_path,'eventImagePath'=>$event_image_path]]);
    }

    public function eventOnHomePageAPI(Request $request)
    {
        $timeline = Timeline::where('username', $request->username)->first();
        $id = $timeline->user->id;
        if($request->profile_timeline) {
            $posts = Post::where('user_id', $id)->where([['active', 1],['type','event']])->latest()->with('timeline')->limit($request->paginate)->offset($request->offset)->get();
        }
        else {
            $posts = Post::where([['active', 1],['type','event']])->whereIn('user_id', function ($query) use ($id) {
                $query->select('leader_id')
                    ->from('followers')
                    ->where('follower_id', $id);
            })->orWhere([['active', 1],['type','event']])->latest()->with('timeline')->limit($request->paginate)->offset($request->offset)->get();
        }

        // $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('timeline')->limit($request->paginate)->offset($request->offset)->get();

        foreach ($posts as $post) {
            if($post->images()->count() > 0) {
                $post['images'] = $post->images()->get();
            }
            if($post->comments()->count() > 0) {
                $post['comments'] = $post->comments()->where('user_id',$post->user_id)->get();
            }

            $post['likes_count'] = $post->users_liked()->count();
            $post['user_liked'] = false;

            if($post['likes_count'] > 0) {
                if($post->users_liked()->where('user_id',Auth::user()->id)->count()) {
                    $post['user_liked'] = true;
                }
            }

            if($post->type == 'event'){
                $event = Event::where('timeline_id',$post->timeline_id)->latest()->get();
                if(count($event)){
                    $post['event'] = $event;
                    $event = $event->toArray();
                    $creatorId = $event[0]['user_id'];
                    $creator = DB::table('users')->where('id',$creatorId)->first();
                    $creatorTimeline = Timeline::where('id',$creator->timeline_id)->first();
                    $post['creator_timeline'] = $creatorTimeline;
                    foreach ($post['event'] as $user_event) {
                        $user_event['event_details'] = $user_event->timeline->username;
                        if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                            $user_event['event_tags'] = $matches[0];
                        }
                        if($user_event->users->contains(Auth::user()->id)){
                            $user_event['registered'] = true;
                        }
                        if($user_event->start_date < Carbon::now()){
                            $user_event['expired'] = true;
                        }
                    }
                }
            }
        }

        $post_image_path = storage_path().'/uploads/users/gallery/';
        $event_image_path = storage_path().'/uploads/events/covers/';

        return response()->json(['status' => '200', ['posts'=>$posts, 'timeline'=>$timeline, 'postImagePath'=>$post_image_path,'eventImagePath'=>$event_image_path]]);
    }

    public function userPostAPI(Request $request) {
        $timeline = Timeline::where('username', $request->username)->first();
        $id = $timeline->user->id;
        $posts = Post::Where([['user_id', $id],['active',1]])->latest()->with('timeline')->limit($request->paginate)->offset($request->offset)->get();

        // $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('timeline')->limit($request->paginate)->offset($request->offset)->get();

        foreach ($posts as $post) {
            if($post->images()->count() > 0) {
                $post['images'] = $post->images()->get();
            }
            if($post->comments()->count() > 0) {
                $post['comments'] = $post->comments()->where('user_id',$post->user_id)->get();
            }

            $post['likes_count'] = $post->users_liked()->count();
            $post['user_liked'] = false;

            if($post['likes_count'] > 0) {
                if($post->users_liked()->where('user_id',Auth::user()->id)->count()) {
                    $post['user_liked'] = true;
                }
            }

            if($post->type == 'event'){
                $post['event'] = Event::where('timeline_id',$post->timeline_id)->latest()->get();
                foreach ($post['event'] as $user_event) {
                    $user_event['event_details'] = $user_event->timeline->username;
                    if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                        $user_event['event_tags'] = $matches[0];
                    }
                    if($user_event->users->contains(Auth::user()->id)){
                        $user_event['registered'] = true;
                    }
                    if($user_event->start_date < Carbon::now()){
                        $user_event['expired'] = true;
                    }
                }
            }
        }

        $post_image_path = storage_path().'/uploads/users/gallery/';
        $event_image_path = storage_path().'/uploads/events/covers/';

        return response()->json(['status' => '200', ['posts'=>$posts, 'timeline'=>$timeline, 'postImagePath'=>$post_image_path,'eventImagePath'=>$event_image_path]]);
    }

    public function getGalleryByLocation(Request $request) {
        $timeline = Timeline::where('username', $request->username)->first();
        $location = $request->location;
        $user = User::where('timeline_id', $timeline['id'])->first();
        $allposts = Post::where([['active', 1],['location','like','%'.$location.'%']])->latest()->get();
        $posts = [];
        $i = 0;
        $start = $request->offset;
        $end = $start + $request->paginate - 1;
        foreach ($allposts as $key => $value) {
            if($value->images()->count() > 0 AND $value->type != 'event') {
               if(($i >= $request->offset) && ($i <= $end)) {
                    $posts[$key] = $value;
               }
               $i++;
            }
        }
        foreach ($posts as $post) {
            if($post->images()->count() > 0) {
                $post['images'] = $post->images()->get();
            }
            if($post->comments()->count() > 0) {
                $post['comments'] = $post->comments()->where('user_id',$post->user_id)->get();
            }

            $post['likes_count'] = $post->users_liked()->count();
            $post['user_liked'] = false;

            if($post['likes_count'] > 0) {
                if($post->users_liked()->where('user_id',Auth::user()->id)->count()) {
                    $post['user_liked'] = true;
                }
            }

            if($post->type == 'event'){
                $post['event'] = Event::where('timeline_id',$post->timeline_id)->limit($request->paginate)->offset($request->offset)->latest()->get();
                foreach ($post['event'] as $user_event) {
                    $user_event['event_details'] = $user_event->timeline->username;
                    if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                        $user_event['event_tags'] = $matches[0];
                    }
                    if($user_event->users->contains(Auth::user()->id)){
                        $user_event['registered'] = true;
                    }
                    if($user_event->start_date < Carbon::now()){
                        $user_event['expired'] = true;
                    }
                }
            }
        }

        $post_image_path = storage_path().'/uploads/users/gallery/';
        $event_image_path = storage_path().'/uploads/events/covers/';

        return response()->json(['status' => '200', ['posts'=>$posts, 'timeline'=>$timeline, 'postImagePath'=>$post_image_path,'eventImagePath'=>$event_image_path]]);
    }

    public function getGalleryByHashtag(Request $request) {
        $timeline = Timeline::where('username', $request->username)->first();
        $hashtag = $request->hashtag;
        $user = User::where('timeline_id', $timeline['id'])->first();
        $allposts = Post::where([['active', 1],['description','like','%'.$hashtag.'%']])->latest()->get();
        $posts = [];
        $i = 0;
        $start = $request->offset;
        $end = $start + $request->paginate - 1;
        foreach ($allposts as $key => $value) {
            if($value->images()->count() > 0 AND $value->type != 'event') {
               if(($i >= $request->offset) && ($i <= $end)) {
                    $posts[$key] = $value;
               }
               $i++;
            }
        }
        foreach ($posts as $post) {
            if($post->images()->count() > 0) {
                $post['images'] = $post->images()->get();
            }
            if($post->comments()->count() > 0) {
                $post['comments'] = $post->comments()->where('user_id',$post->user_id)->get();
            }

            $post['likes_count'] = $post->users_liked()->count();
            $post['user_liked'] = false;

            if($post['likes_count'] > 0) {
                if($post->users_liked()->where('user_id',Auth::user()->id)->count()) {
                    $post['user_liked'] = true;
                }
            }

            if($post->type == 'event'){
                $post['event'] = Event::where('timeline_id',$post->timeline_id)->latest()->limit($request->paginate)->offset($request->offset)->get();
                foreach ($post['event'] as $user_event) {
                    $user_event['event_details'] = $user_event->timeline->username;
                    if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                        $user_event['event_tags'] = $matches[0];
                    }
                    if($user_event->users->contains(Auth::user()->id)){
                        $user_event['registered'] = true;
                    }
                    if($user_event->start_date < Carbon::now()){
                        $user_event['expired'] = true;
                    }
                }
            }
        }

        $post_image_path = storage_path().'/uploads/users/gallery/';
        $event_image_path = storage_path().'/uploads/events/covers/';

        return response()->json(['status' => '200', ['posts'=>$posts, 'timeline'=>$timeline, 'postImagePath'=>$post_image_path,'eventImagePath'=>$event_image_path]]);
    }

    public function getGalleryByUsername(Request $request) {
        $timeline = Timeline::where('username', $request->username)->first();
        $user = User::where('timeline_id', $timeline['id'])->first();
        $allposts = Post::where([['active', 1],['user_id',$user->id]])->latest()->get();
        $posts = [];
        $i = 0;
        $start = $request->offset;
        $end = $start + $request->paginate - 1;
        foreach ($allposts as $key => $value) {
            if($value->images()->count() > 0 AND $value->type != 'event') {
               if(($i >= $request->offset) && ($i <= $end)) {
                    $posts[$key] = $value;
               }
               $i++;
            }
        }
        foreach ($posts as $post) {
            if($post->images()->count() > 0) {
                $post['images'] = $post->images()->get();
            }
            if($post->comments()->count() > 0) {
                $post['comments'] = $post->comments()->where('user_id',$post->user_id)->get();
            }

            $post['likes_count'] = $post->users_liked()->count();
            $post['user_liked'] = false;

            if($post['likes_count'] > 0) {
                if($post->users_liked()->where('user_id',Auth::user()->id)->count()) {
                    $post['user_liked'] = true;
                }
            }

            if($post->type == 'event'){
                $post['event'] = Event::where('timeline_id',$post->timeline_id)->latest()->limit($request->paginate)->offset($request->offset)->get();
                foreach ($post['event'] as $user_event) {
                    $user_event['event_details'] = $user_event->timeline->username;
                    if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                        $user_event['event_tags'] = $matches[0];
                    }
                    if($user_event->users->contains(Auth::user()->id)){
                        $user_event['registered'] = true;
                    }
                    if($user_event->start_date < Carbon::now()){
                        $user_event['expired'] = true;
                    }
                }
            }
        }

        $post_image_path = storage_path().'/uploads/users/gallery/';
        $event_image_path = storage_path().'/uploads/events/covers/';

        return response()->json(['status' => '200', ['posts'=>$posts, 'timeline'=>$timeline, 'postImagePath'=>$post_image_path,'eventImagePath'=>$event_image_path]]);
    }

    public function fetchPostLikes(Request $request) {

        $posts = Post::where('id',$request->post_id)->with('timeline')->get();

        $hasMore = false;
        $post_likes_by = '';
        foreach ($posts as $post) {
            $post_likes_by = $post->users_liked()->limit($request->paginate)->offset($request->offset)->get();
        }

        foreach ($post_likes_by as $user) {
            if(Auth::user()->following->contains($user->id)){
                $user->follow_status = 'following';
                if(Auth::user()->checkFollowStatus($user->id)) {
                    $user->follow_status = 'pending';
                }
            }
            else {
                $user->follow_status = 'not following';
            }
        }

        $total_likes = DB::table('post_likes')->where('post_id',$request->post_id)->count();

        if($total_likes > $request->paginate) {
            $hasMore = true;
        }
       
        return response()->json(['status' => '200', ['post_likes_by'=>$post_likes_by,'has_more_likes'=>$hasMore]]);

    }

    public function singlePostAPI(Request $request) {
        $post = Post::find($request->post_id);
        $timeline = Timeline::find($post->timeline_id);
        if($post->images()->count() > 0) {
            $post['images'] = $post->images()->get();
        }
        if($post->comments()->count() > 0) {
            $post['comments'] = $post->comments()->where('user_id',$post->user_id)->get();
        }

        $post['likes_count'] = $post->users_liked()->count();
        $post['user_liked'] = false;
        $post['timeline'] = $timeline;
        if($post['likes_count'] > 0) {
            if($post->users_liked()->where('user_id',Auth::user()->id)->count()) {
                $post['user_liked'] = true;
            }
        }

        if($post->type == 'event'){
            $event = Event::where('timeline_id',$post->timeline_id)->latest()->get();
            if(count($event)){
                $post['event'] = $event;
                $event = $event->toArray();
                $creatorId = $event[0]['user_id'];
                $creator = DB::table('users')->where('id',$creatorId)->first();
                $creatorTimeline = Timeline::where('id',$creator->timeline_id)->first();
                $post['creator_timeline'] = $creatorTimeline;
                foreach ($post['event'] as $user_event) {
                    $user_event['event_details'] = $user_event->timeline->username;
                    if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                        $user_event['event_tags'] = $matches[0];
                    }
                    if($user_event->users->contains(Auth::user()->id)){
                        $user_event['registered'] = true;
                    }
                    if($user_event->start_date < Carbon::now()){
                        $user_event['expired'] = true;
                    }
                    $event_host = User::find($creatorId);
                    if ($event_host) {
                        $user_event['host_name'] = $event_host->timeline->name;
                    }
                    $user_event->protected = false;
                    $checkUser = User::find($user_event->user_id);
                    if((!($checkUser->followers->contains(Auth::user()->id))) && ($user_event->type == 'private') && ($user_event->user_id != Auth::user()->id)) {
                        $user_event->protected = true;
                    }
                }
            }
        }

        $post_image_path = storage_path().'/uploads/users/gallery/';
        $event_image_path = storage_path().'/uploads/events/covers/';

        $image_path = storage_path().'/uploads/users/gallery/';

        return response()->json(['status' => '200', ['post'=>$post, 'timeline'=>$timeline, 'imagePath'=>$image_path]]);
    }

    public function getSearch()
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        return $theme->scope('search', compact('username'))
            ->render();
    }

    public function commentsCountAPI(Request $request) {
        $total_comments = Comment::where('post_id',$request->post_id)->count();
        $total_likes = DB::table('post_likes')->where('post_id',$request->post_id)->count();
        $id = Auth::user()->id;
        $user_liked = DB::table('post_likes')->where([['post_id',$request->post_id],['user_id',$id]])->count();
        return response()->json(['status' => '200', ['post_comment_count'=>$total_comments,'post_likes_count'=>$total_likes,'user_liked'=>$user_liked]]);
    }

    public function commentsAPI(Request $request) {
        $total_comments = Comment::where('post_id',$request->post_id)->count();

        $comments = Comment::where('post_id',$request->post_id)->latest()->limit(3)->with('user')->offset($request->offset)->get();

        $limit = 3 + $request->offset;
        $hasMore = false;
        if($total_comments > $limit) {
            $hasMore = true;
        }

        foreach ($comments as $comment) {
            $likedBy = DB::table('comment_likes')->where([['comment_id',$comment->id],['user_id',$comment->user_id]])->get();
            if($likedBy){
                $comment->commentLikes = $likedBy;
            }
        }

        return response()->json(['status' => '200', ['comments'=>$comments,'hasMore'=>$hasMore]]);
    }

    public function removeUserEvent($event_id)
    {
        $event = Event::find($event_id);
        
        //Deleting Events
        $event->users()->detach();

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

        Flash::success(trans('messages.event_deleted_success'));
        return redirect()->back();
    }

    public function redirectToLocation(Request $request){
        $location = str_replace(' ', '+', $request->location);
        $map_url = 'http://www.google.com/maps/place/'.$location;
        return redirect($map_url);
    }

    public function getParticipants(Request $request) {
        $event = Event::find($request->event_id);
        $event_users = $event->users()->get();
        return $event_users;
    }

    public function getEventByDate(Request $request) {

			  $start_date = $request->start_date;
        $start_date_time_temp = date_create($request->start_date.' 23:59:59');
        $start_date_time =  date_format($start_date_time_temp, 'Y-m-d H:i:s');

        $events_all = Event::where('start_date','>=',$request->start_date)->orWhere(function($query) use ($request,$start_date_time) {
                 $query->where('start_date','<=',$request->start_date)->where('start_date', '>=', $start_date_time);
              }
          )->with('timeline')->get();
        $events = [];
        foreach ($events_all as $key => $event) {
            if($event->users->contains(Auth::user()->id)){
                $events[$key] = $event;
                $events[$key]['details_link'] = '';
                $post = Post::where('timeline_id',$event->timeline_id)->first();
                if($post){
                    $events[$key]['details_link'] = 'post/'.$post->id;
                }
            }
        }
        return response()->json(['status' => '200', ['events'=>$events]]);

    }

    public function getEventById(Request $request) {
        $events = Event::where('id',$request->event_id)->with('timeline')->get();
        foreach ($events as $event) {
            $event['description'] = $event->timeline->about;
            $event['title'] = $event->timeline->name;
        }
        return response()->json(['status'=>'200',['events'=>$events]]);
    }

    public function getHashtag(Request $request, $hashtag)
    {

        $mode = "showfeed";
        $user_post = 'showfeed';
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $timeline = Timeline::where('username', Auth::user()->username)->first();

        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();
        // Check for hashtag
        if ($request->hashtag) {
            $hashtag = '#'.$request->hashtag;
            $posts = Post::where([['description', 'like', "%{$hashtag}%"],['active',1]])->latest()->get();
        } // else show the normal feed
        else {
            $posts = Post::whereIn('user_id', function ($query) use ($id) {
                $query->select('leader_id')
                    ->from('followers')
                    ->where('follower_id', $id);
            })->orWhere('user_id', $id)->where('active', 1)->latest()->paginate(Setting::get('items_page'));
        }


        $announcement = Announcement::find(Setting::get('announcement'));
        if ($announcement != null) {
            $chk_isExpire = $announcement->chkAnnouncementExpire($announcement->id);

            if ($chk_isExpire == 'notexpired') {
                $active_announcement = $announcement;
                if (!$announcement->users->contains(Auth::user()->id)) {
                    $announcement->users()->attach(Auth::user()->id);
                }
            }
        }


        // $next_page_url = url('ajax/get-more-feed-by-location?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.Auth::user()->username);

        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('gallery-by-hashtag', compact('timeline', 'hashtag' ,'posts', 'next_page_url', 'trending_tags', 'suggested_users', 'active_announcement', 'suggested_groups', 'suggested_pages', 'mode', 'user_post'))
            ->render();

    }

    public function getImagePostByLocation(Request $request)
    {

        $mode = "showfeed";
        $user_post = 'showfeed';
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $timeline = Timeline::where('username', Auth::user()->username)->first();

        $id = Auth::id();

        /*$trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();*/

        // Check for location
        if ($request->location) {
            $location = $request->location;
            $posts = Post::where([['location', 'like', "%{$location}%"],['active',1]])->latest()->paginate(Setting::get('items_page'));
        } // else show the normal feed

        // $next_page_url = url('ajax/get-more-feed-by-location?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.Auth::user()->username);

        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('gallery-by-location', compact('timeline','location', 'posts', 'next_page_url', 'mode', 'user_post'))
            ->render();

    }

    public function getEventByLocation(Request $request) {


        $mode = "eventlist";

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $location = '%'.$request->location.'%';

        $user_events = Event::where([['location','LIKE',$location]])->with('timeline')->latest()->get();
        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        $event_tags = NULL;
        if($user_events) {
            foreach ($user_events as $user_event) {
                $user_event['registered'] = false;
                $user_event['expired'] = false;
                if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                    $event_tags['tags'] = $matches[0];
                    $event_tags['event_id'] = $user_event->id;
                }
                if($user_event->users->contains(Auth::user()->id)){
                    $user_event['registered'] = true;
                }
                if($user_event->start_date < Carbon::now()){
                    $user_event['expired'] = true;
                }
            }
        }

        // $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.$username);

        $theme->setTitle(trans('common.events').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));
        $location = $request->location;
        return $theme->scope('event-by-location', compact('location', 'event_tags','next_page_url', 'trending_tags', 'suggested_users', 'suggested_groups', 'suggested_pages', 'mode', 'user_events', 'username'))
            ->render();

        /*$mode = "eventlist";

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $location = $request->location;

        $user_events = Event::where([['user_id', Auth::user()->id],['location',$location]])->with('timeline')->latest()->get();
        $id = Auth::id();

        $trending_tags = trendingTags();
        $suggested_users = suggestedUsers();
        $suggested_groups = suggestedGroups();
        $suggested_pages = suggestedPages();

        // $next_page_url = url('ajax/get-more-feed?page=2&ajax=true&hashtag='.$request->hashtag.'&username='.$username);

        $theme->setTitle(trans('common.events').' | '.Setting::get('site_title').' | '.Setting::get('site_tagline'));

        return $theme->scope('event-by-location', compact('location','event_tags','next_page_url', 'trending_tags', 'suggested_users', 'suggested_groups', 'suggested_pages', 'mode', 'user_events', 'username'))
            ->render();*/
    }

    public function getRegisteredUserForEvent(Request $request){
      $eventId = $request->event_id;
      $userId = $request->user_id;
      $event_owner= Event::find($eventId)->user_id;
      $registeredUsers = DB::table('event_user')->where('event_id',$eventId)->limit($request->paginate)->offset($request->offset)->get();
      if (!empty($registeredUsers)){
        foreach ($registeredUsers as $key => $value){
          $registeredUsers[$key]->eventOwner = false;
          if($value->user_id == $event_owner){
            $registeredUsers[$key]->eventOwner = true;
          }
          $user = DB::table('users')->where('id',$value->user_id)->first();
          // $timeline = DB::table('timelines')->where('id',$user->timeline_id)->first();
          $timeline = Timeline::find($user->timeline_id);
          $registeredUsers[$key]->timeline = $timeline;
          $following = DB::table('followers')->where('leader_id',$value->user_id)->where('follower_id',$userId)->first();
          if (!empty($following)){
            unset($following);
            $registeredUsers[$key]->following = true;
          }
          else{
            $registeredUsers[$key]->following = false;
          }
        }
          $currentUserOwner = false; 
          if(Auth::user()->id == $event_owner){
              $currentUserOwner = true;
          }
        return response()->json(['registeredUsers'=>$registeredUsers,'currentUserOwner'=>$currentUserOwner]);
      }
      else{
        return json_encode('');
      }
    }

    public function unregisterEvent(Request $request)
  {
      $eventId = $request->event_id;
      $userId = $request->user_id;
      $event = DB::table('events')->where('id', $eventId)->first();
      $event_d = Event::find($request->event_id);
      $post_id = Post::where('timeline_id',$event_d->timeline_id)->first()->id;
      $users = $event_d->users()->get();
      // if ($event->price > 0) {
      //     $registration = DB::table('event_user')
      //         ->where('event_id', $eventId)
      //         ->where('user_id', $userId)
      //         ->first();
      //     $unregister = DB::table('event_unregister_request')
      //         ->insert([
      //             'reg_user_id' => $userId,
      //             'event_id' => $eventId,
      //             'event_reg_id' => $registration->id,
      //             'event_owner_id' => $event->user_id,
      //             'created_at' => Carbon::now()
      //         ]);
      //     if ($unregister) {
      //         $msg = 'Successfully submitted unregistration request';
      //     } else {
      //         $msg = 'Unable to submit unregistration request';
      //     }
      // } else {
      //     $unregister = DB::table('event_user')
      //         ->where('event_id', $eventId)
      //         ->where('user_id', $userId)
      //         ->delete();
      //     if ($unregister) {
      //         $msg = 'Successfully unregistered from Event';
      //     } else {
      //         $msg = 'No registration found for this Event';
      //     }
      //     return response()->json(['status' => '200', 'data' => $msg]);
      // }
      $unregister = DB::table('event_user')
              ->where('event_id', $eventId)
              ->where('user_id', $userId)
              ->delete();
          if ($unregister) {
              $msg = 'Successfully unregistered from Event';
              foreach ($users as $user) {
                if ($user->id != Auth::user()->id) {
                    App::setLocale($user->language);
                    Notification::create(['user_id' => $user->id,'post_id' => $post_id ,'timeline_id' => $request->timeline_id, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' '.trans('common.quit_attending_your_event'), 'type' => 'unjoin_event','link' => '/post/'.$post_id]);
                    App::setLocale(Auth::user()->language);
                }
            }
          } else {
              $msg = 'No registration found for this Event';
          }
          return response()->json(['status' => '200', 'data' => $msg]);
  }

    public function timelineUserGallery($username)
    {
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        $posts = [];
        $timeline = Timeline::where('username', $username)->first();
        $user_post = '';

        if ($timeline == null) {
            return redirect('/');
        }

        $timeline_posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));

        foreach ($timeline_posts as $timeline_post) {
            //This is for filtering reported(flag) posts, displaying non flag posts
            if ($timeline_post->check_reports($timeline_post->id) == false) {
                array_push($posts, $timeline_post);
            }
        }

        if ($timeline->type == 'user') {
            $user = User::where('timeline_id', $timeline['id'])->first();
            $joined_groups_count = $user->groups()->where('role_id', '!=', $admin_role_id->id)->where('status', '=', 'approved')->get()->count();
            $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
            $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
            $followRequests = $user->followers()->where('status', '=', 'pending')->get();
            $user_events = $user->events()->whereDate('end_date', '>=', date('Y-m-d', strtotime(Carbon::now())))->get();
            $guest_events = $user->getEvents();

            $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                ->where('leader_id', '=', $user->id)->first();

            if ($follow_user_status) {
                $follow_user_status = $follow_user_status->status;
            }

            $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
            $follow_confirm = $confirm_follow_setting->confirm_follow;

            //get user settings
            $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
            $privacy_settings = explode('-', $live_user_settings);
            $timeline_post = $privacy_settings[0];
            $user_post = $privacy_settings[1];
        }

        $blocked = DB::table('user_blocked')->where([['blocked_uid',$user->id],['blocker_uid',Auth::user()->id]])->first();
        $block_text = trans('common.block');

        if($blocked){
            $block_text = trans('common.unblock');
        }

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/gallery', compact('user','timeline', 'timeline_type','posts' ,'follow_user_status', 'followRequests', 'following_count', 'followers_count', 'timeline_post', 'user_post', 'follow_confirm', 'joined_groups_count','group_members', 'page_members', 'event', 'user_events', 'guest_events', 'username','block_text'))->render();
    }

    public function timelineUserEvent($username) {
        $admin_role_id = Role::where('name', '=', 'admin')->first();
        $posts = [];
        $timeline = Timeline::where('username', $username)->first();
        $user_post = '';

        if ($timeline == null) {
            return redirect('/');
        }

        $timeline_posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('comments')->paginate(Setting::get('items_page'));

        foreach ($timeline_posts as $timeline_post) {
            //This is for filtering reported(flag) posts, displaying non flag posts
            if ($timeline_post->check_reports($timeline_post->id) == false) {
                array_push($posts, $timeline_post);
            }
        }

        if ($timeline->type == 'user') {
            $user = User::where('timeline_id', $timeline['id'])->first();
            $joined_groups_count = $user->groups()->where('role_id', '!=', $admin_role_id->id)->where('status', '=', 'approved')->get()->count();
            $following_count = $user->following()->where('status', '=', 'approved')->get()->count();
            $followers_count = $user->followers()->where('status', '=', 'approved')->get()->count();
            $followRequests = $user->followers()->where('status', '=', 'pending')->get();
            $all_events = Event::with('timeline')->latest()->get();
            $user_events = [];
            foreach ($all_events as $key => $value) {
                if($value->users->contains($user->id)){
                    $user_events[$key] = $value;
                }
            }
            $guest_events = $user->getEvents();

            $follow_user_status = DB::table('followers')->where('follower_id', '=', Auth::user()->id)
                ->where('leader_id', '=', $user->id)->first();

            if ($follow_user_status) {
                $follow_user_status = $follow_user_status->status;
            }

            $confirm_follow_setting = $user->getUserSettings(Auth::user()->id);
            $follow_confirm = $confirm_follow_setting->confirm_follow;

            //get user settings
            $live_user_settings = $user->getUserPrivacySettings(Auth::user()->id, $user->id);
            $privacy_settings = explode('-', $live_user_settings);
            $timeline_post = $privacy_settings[0];
            $user_post = $privacy_settings[1];
        }

        $blocked = DB::table('user_blocked')->where([['blocked_uid',$user->id],['blocker_uid',Auth::user()->id]])->first();
        $block_text = trans('common.block');

        if($blocked){
            $block_text = trans('common.unblock');
        }

        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');

        $theme->setTitle($timeline->name.' '.Setting::get('title_seperator').' '.Setting::get('site_title').' '.Setting::get('title_seperator').' '.Setting::get('site_tagline'));

        return $theme->scope('users/user-event', compact('user','timeline', 'timeline_type','posts', 'follow_user_status', 'followRequests', 'following_count', 'followers_count', 'timeline_post', 'user_post', 'follow_confirm', 'joined_groups_count','group_members', 'page_members', 'event', 'user_events', 'guest_events', 'username','block_text'))->render();
    }

    public function getEventPostByLocation(Request $request) {
    $timeline = Timeline::where('username', $request->username)->first();
    $location = '%'.$request->location.'%';
    $id = Auth::user()->id;
    $allposts = Post::where([['active', 1],['location','like',$location]])->whereIn('user_id', function ($query) use ($id) {
      $query->select('leader_id')
        ->from('followers')
        ->where('follower_id', $id);
    })->orWhere([['user_id', $id],['location','like',$location]])->latest()->with('timeline')->limit($request->paginate)->offset($request->offset)->get();

    // $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('timeline')->limit($request->paginate)->offset($request->offset)->get();
    $posts = [];
    foreach ($allposts as $key => $value) {
      if($value->type == 'event') {
        $posts[$key] = $value;
      }
    }
    foreach ($posts as $post) {
      if($post->images()->count() > 0) {
        $post['images'] = $post->images()->get();
      }
      if($post->comments()->count() > 0) {
        $post['comments'] = $post->comments()->where('user_id',$post->user_id)->get();
      }

      $post['likes_count'] = $post->users_liked()->count();
      $post['user_liked'] = false;

      if($post['likes_count'] > 0) {
        if($post->users_liked()->where('user_id',Auth::user()->id)->count()) {
          $post['user_liked'] = true;
        }
      }

      if($post->type == 'event'){

        $post['event'] = Event::where('timeline_id',$post->timeline_id)->latest()->get();
        foreach ($post['event'] as $user_event) {
          $user_event['event_details'] = $user_event->timeline->username;
          if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
            $user_event['event_tags'] = $matches[0];
          }
          if($user_event->users->contains(Auth::user()->id)){
            $user_event['registered'] = true;
          }
          if($user_event->start_date < Carbon::now()){
            $user_event['expired'] = true;
          }
        }
      }
    }

    $post_image_path = storage_path().'/uploads/users/gallery/';
    $event_image_path = storage_path().'/uploads/events/covers/';

    return response()->json(['status' => '200', ['posts'=>$posts, 'timeline'=>$timeline, 'postImagePath'=>$post_image_path,'eventImagePath'=>$event_image_path]]);
  }

    public function getEventPostByHashtag(Request $request) {
    $timeline = Timeline::where('username', $request->username)->first();
    $hashtag = '%'.$request->hashtag.'%';
    $id = Auth::user()->id;
    $allposts = Post::where([['active', 1],['description','like',$hashtag]])->whereIn('user_id', function ($query) use ($id) {
      $query->select('leader_id')
        ->from('followers')
        ->where('follower_id', $id);
    })->orWhere([['user_id', $id],['description','like',$hashtag]])->latest()->with('timeline')->limit($request->paginate)->offset($request->offset)->get();

    // $posts = $timeline->posts()->where('active', 1)->orderBy('created_at', 'desc')->with('timeline')->limit($request->paginate)->offset($request->offset)->get();
    $posts = [];
    foreach ($allposts as $key => $value) {
      if($value->type == 'event') {
        $posts[$key] = $value;
      }
    }
    foreach ($posts as $post) {
      if($post->images()->count() > 0) {
        $post['images'] = $post->images()->get();
      }
      if($post->comments()->count() > 0) {
        $post['comments'] = $post->comments()->where('user_id',$post->user_id)->get();
      }

      $post['likes_count'] = $post->users_liked()->count();
      $post['user_liked'] = false;

      if($post['likes_count'] > 0) {
        if($post->users_liked()->where('user_id',Auth::user()->id)->count()) {
          $post['user_liked'] = true;
        }
      }

      if($post->type == 'event'){
        $post['event'] = Event::where('timeline_id',$post->timeline_id)->latest()->get();
        foreach ($post['event'] as $user_event) {
          $user_event['event_details'] = $user_event->timeline->username;
          if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
            $user_event['event_tags'] = $matches[0];
          }
          if($user_event->users->contains(Auth::user()->id)){
            $user_event['registered'] = true;
          }
          if($user_event->start_date < Carbon::now()){
            $user_event['expired'] = true;
          }
        }
      }
    }

    $post_image_path = storage_path().'/uploads/users/gallery/';
    $event_image_path = storage_path().'/uploads/events/covers/';

    return response()->json(['status' => '200', ['posts'=>$posts, 'timeline'=>$timeline, 'postImagePath'=>$post_image_path,'eventImagePath'=>$event_image_path]]);
  }

    public function getPaidEventUnregisterRequests(Request $request) {
    $eventOwnerId = $request->event_owner_id;
    $eventId = $request->event_id;
    if (empty($eventId)) {
      $requests = DB::table('event_unregister_request')
        ->where('event_owner_id', $eventOwnerId)
        ->get()
        ->toArray();
    }
    else {
      $requests = DB::table('event_unregister_request')
        ->where('event_owner_id', $eventOwnerId)
        ->where('event_id', $eventId)
        ->get()
        ->toArray();
    }
    if (!empty($requests)) {
      return json_encode($requests);
    }
    else {
      return json_encode('');
    }
  }

    public function sharePostByNotification(Request $request){
      //todo: translation not implemented for the description.
      $postId = $request->post_id;
      $post_shared = Post::findOrFail($postId);
      $posted_user = $post_shared->user;
      $users = $request->users;
      foreach ($users as $key => $value){
        $timeline = DB::table('timelines')->where('username',$value)->first();
        $user = DB::table('users')->where('timeline_id',$timeline->id)->first();
        //Check if the user has blocked the post.
        $block = $this->checkIfPostBlocked($postId,$user->id);
        if ($block == FALSE){
          App::setLocale($user->language);
          Notification::create(['user_id' => $user->id, 'post_id' => $postId, 'notified_by' => Auth::user()->id, 'description' => Auth::user()->name.' wants you to view this post', 'type' => 'share_post', 'link' => '/post/'.$postId]);
          App::setLocale(Auth::user()->language);

          $user = User::find(Auth::user()->id);
          $user_settings = $user->getUserSettings($posted_user->id);
          $post_url = 'fitmetix.com/post/'.$request->post_id;

          if ($user_settings && $user_settings->email_post_share == 'yes') {
              Mail::send('emails.postsharemail', ['user' => $user, 'posted_user' => $posted_user,'post_url'=>$post_url], function ($m) use ($user, $posted_user) {
                  $m->from(Setting::get('noreply_email'), Setting::get('site_name'));
                  $m->to($posted_user->email, $posted_user->name)->subject($user->name.' '.'shared your post');
              });
          }

        }
      }
      return response()->json(['status' => '200','data' => 'Post Shared']);
  }

    public function acceptDeclineUnregisterRequest(Request $request) {
    $unregisterRequestId = $request->id;
    $operation = $request->operation;
    if ($operation == 'accept'){
      $unregisterRequest = DB::table('event_unregister_request')->where('id',$unregisterRequestId)->first();
      $registrationId = $unregisterRequest->event_reg_id;
      $removeRequest = DB::table('event_unregister_request')->where('id',$unregisterRequestId)->delete();
      $removeRegister = DB::table('event_user')->where('id',$registrationId)->delete();
      if ($removeRequest AND $removeRegister){
        return response()->json(['status' => '200'], ['data'=> 'Unregistered User']);
      }
    }
    elseif ($operation == 'decline'){
      $removeRequest = DB::table('event_unregister_request')->where('id',$unregisterRequestId)->delete();
      return response()->json(['status' => '200'], ['data'=> 'Unregistered Request Declined']);
    }
    else{
      return response()->json(['status' => '200'], ['data'=> 'Invalid Operation']);
    }
  }

    public function unregisterUserEventByCreator(Request $request){
      $eventId = $request->event_id;
      $userId = $request->user_id;
      $unregister = DB::table('event_user')->where('event_id',$eventId)->where('user_id',$userId)->delete();
      $removeRequest = DB::table('event_unregister_request')->where('event_id',$eventId)->where('reg_user_id',$userId)->delete();
      if ($unregister){
        return response()->json(['status' => '200'], ['data'=> 'Unregister Successfull']);
      }
      else{
        return response()->json(['status' => '200'], ['data'=> 'Failed to Unregister']);
      }
  }

  public function checkIfPostBlocked($post_id,$user_id){
    $block_notification_model = new BlockNotification();
    $block_notification = $block_notification_model->where('user_id','=',$user_id)->where('post_event_id','=',$post_id)->get()->toArray();
    if(!empty($block_notification)) {
      return TRUE;
    }
    else{
      return FALSE;
    }
  }

  public function searchAPI(Request $request) {
      $title = $request->keyword;
      // $title = 'mikele';
      $users = Timeline::where('username','LIKE','%'.$title.'%')->get()->toArray();
      $tags = Hashtag::where('tag','LIKE','%'.$title.'%')->get()->toArray();

      $events = DB::table('events')
                ->join('timelines', 'timelines.id', '=', 'events.timeline_id')
                ->where(function ($query) use ($title){
                        if($title != '') {
                            $query->where('timelines.name','like','%'.$title.'%');
                        }
                })
                ->orWhere(function ($query) use ($title){
                        if($title != '') {
                            $query->where('timelines.about','like','%'.$title.'%');
                        }
                })
                ->select('events.*', 'timelines.*','events.id as event_id')
                ->get();
                $a = 0;
                $events = $events->all();
                $post_model = new Post();
                $post_media_model = new PostMedia();
                $media_model = new Media();
                $post = array();
                $post_media = array();
                foreach ($events as $key => $event) {
                    $event_timeline;
                    $event_media = array();
                    $post = $post_model->where('timeline_id','=',$event->id)->get()->toArray();
                    if(!empty($post)) {
                        $event->post_id = $post[0]['id'];
                        $post_media = $post_media_model->where('post_id', '=', $post[0]['id'])
                            ->get()
                            ->toArray();
                        foreach ($post_media as $post_media_key => $item) {
                            $media = $media_model->where('id', '=', $item['media_id'])
                                ->get()
                                ->toArray();
                            if (isset($media[0])) {
                                $event_media [] = $media[0];
                            }
                        }
                        $a = $events[$key];
                        $events[$key]->media = array();
                        $events[$key]->media = $event_media;
                    }
                }
                return response()->json([compact('users','tags','events')]);
  }

  public function getSelfTimeline() {
      $user_timeline = User::where('id',Auth::user()->id)->with('timeline')->first();
      return response()->json([compact('user_timeline')]);
  }
public function saveMessageAttachment(Request $request) {
        $attached_file = $request->file('attachment');
        $strippedName = str_replace(' ', '', $attached_file->getClientOriginalName());
        $attached_file_name = 'chat'.time().$strippedName;
        $s3 = Storage::disk('uploads');
        $s3->put('users/attachments/'.$attached_file_name, file_get_contents($attached_file));
        $file_link = Storage::disk('uploads')->url('users/attachments/'.$attached_file_name);
        return response()->json(['file_link'=>$file_link]);
    }

    public function getSettingMobile()
    {
        $theme = Theme::uses(Setting::get('current_theme', 'default'))->layout('default');
        return $theme->scope('search', compact('username'))
            ->render();
  }

  public function checkBlockStatus(Request $request) {
       $blocked = DB::table('user_blocked')->where([['blocker_uid',$request->user_id],['blocked_uid',Auth::user()->id]])->first();
       $block_status = false; 
       if($blocked){
           $block_status = true;
       }
       return response()->json(['blocked'=>$block_status]);

  }

  public function getFollowers(Request $request) {
      $user = User::find($request->user_id);
      $followers = $user->followers()->where('status','approved')->limit($request->paginate)->offset($request->offset)->get();
      foreach ($followers as $follower) {
          $following = DB::table('followers')->where([['leader_id',Auth::user()->id],['follower_id',$follower->id]])->first();
          if (!empty($following)){
            if($following->status == 'pending') {
                $follower->following_status = 'Request Sent';
            }
            else {
                $follower->following_status = 'Following';
            }
          }
          else {
            $follower->following_status = 'Follow';
          }
                    
      }   
      return response()->json(['followers'=>$followers]);
  }

  public function getFollowing(Request $request) {
      $user = User::find($request->user_id);
      $followings = $user->following()->where('status','approved')->limit($request->paginate)->offset($request->offset)->get();
      foreach ($followings as $following) {
          $following_auth = DB::table('followers')->where([['follower_id',Auth::user()->id],['leader_id',$following->id]])->first();
          if (!empty($following_auth)){
            if($following_auth->status == 'pending') {
                $following->following_status = 'Request Sent';
            }
            else {
                $following->following_status = 'Following';
            }
          }
          else {
            $following->following_status = 'Follow';
          }
                    
      } 
      return response()->json(['following'=>$followings]);
  }

    public function removeFollower(Request $request) {
        Auth::user()->followers()->detach([$request->user_id]);
        return response()->json(['data'=>'Successfully removed from followers']);
    }

    public function getSavedPost(Request $request) {

        $timeline = Timeline::where('username', $request->username)->first();
        $id = $timeline->user->id;
        $user = User::find($id);

        $posts = $user->postsSaved()->latest()->with('timeline')->limit($request->paginate)->offset($request->offset)->get();

        foreach ($posts as $post) {
            if($post->images()->count() > 0) {
                $post['images'] = $post->images()->get();
            }
            if($post->comments()->count() > 0) {
                $post['comments'] = $post->comments()->where('user_id',$post->user_id)->get();
            }

            $post['likes_count'] = $post->users_liked()->count();
            $post['user_liked'] = false;

            if($post['likes_count'] > 0) {
                if($post->users_liked()->where('user_id',Auth::user()->id)->count()) {
                    $post['user_liked'] = true;
                }
            }

            if($post->type == 'event'){
                $event = Event::where('timeline_id',$post->timeline_id)->latest()->get();
                if(count($event)){
                    $post['event'] = $event;
                    $event = $event->toArray();
                    $creatorId = $event[0]['user_id'];
                    $creator = DB::table('users')->where('id',$creatorId)->first();
                    $creatorTimeline = Timeline::where('id',$creator->timeline_id)->first();
                    $post['creator_timeline'] = $creatorTimeline;
                    foreach ($post['event'] as $user_event) {
                        $user_event['event_details'] = $user_event->timeline->username;
                        if(preg_match_all('/(?<!\w)#\S+/', $user_event->timeline->about, $matches)) {
                            $user_event['event_tags'] = $matches[0];
                        }
                        if($user_event->users->contains(Auth::user()->id)){
                            $user_event['registered'] = true;
                        }
                        if($user_event->start_date < Carbon::now()){
                            $user_event['expired'] = true;
                        }
                    }
                }
            }
        }

        $post_image_path = storage_path().'/uploads/users/gallery/';
        $event_image_path = storage_path().'/uploads/events/covers/';

        return response()->json(['status' => '200', ['posts'=>$posts, 'timeline'=>$timeline, 'postImagePath'=>$post_image_path,'eventImagePath'=>$event_image_path]]);

    }
  
    public function FBshare($post_id) {
        $post = Post::where('id', '=', $post_id)->first();
        $post_id = $post->id;

        $url = url('/share/'.$post->id);
        $post_image_source = $post->images()->where('post_id',$post->id)->first()->source;

        if($post->type == 'event'){
            if($post_image_source != null) {
                $post_image = env('STORAGE_URL').'uploads/events/covers/'.$post_image_source; 
            }
            $title = strip_tags($post->timeline->name);
            $description = strip_tags($post->timeline->about);
        }
        else {
            if($post_image_source != null) {
                $post_image = env('STORAGE_URL').'uploads/users/gallery/'.$post_image_source; 
            }
            $title = strip_tags($post->timeline->username);
            $description = strip_tags($post->description);
        }
        
        return view('FBshare',compact('url','post_image','title','description','post_id'));
    }

}