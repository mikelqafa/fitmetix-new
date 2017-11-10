
@if(isset($post->shared_post_id))
  <?php
    $sharedOwner = $post;
    $post = App\Post::where('id', $post->shared_post_id)->with('comments')->first();
  ?>
@endif

 <div class="panel panel-default timeline-posts__item panel-post animated" id="post{{ $post->id }}">
  <div class="panel-heading no-bg">
    <div class="post-author">
      <div class="post-options">
        <ul class="list-inline no-margin">
          <li class="dropdown"><a href="#" class="dropdown-togle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                  <i class="icon icon-options"></i>
              </a>
            <ul class="dropdown-menu">
              @if($post->notifications_user->contains(Auth::user()->id))
              <li class="main-link">
                <a href="#" data-post-id="{{ $post->id }}" class="notify-user unnotify">
                  <i class="fa  fa-bell-slash" aria-hidden="true"></i>{{ trans('common.stop_notifications') }}
                  <span class="small-text">{{ trans('messages.stop_notification_text') }}</span>
                </a>
              </li>
              <li class="main-link hidden">
                <a href="#" data-post-id="{{ $post->id }}" class="notify-user notify">
                  <i class="fa fa-bell" aria-hidden="true"></i>{{ trans('common.get_notifications') }}
                  <span class="small-text">{{ trans('messages.get_notification_text') }}</span>
                </a>
              </li>
              @else
              <li class="main-link hidden">
                <a href="#" data-post-id="{{ $post->id }}" class="notify-user unnotify">
                  <i class="fa  fa-bell-slash" aria-hidden="true"></i>{{ trans('common.stop_notifications') }}
                  <span class="small-text">{{ trans('messages.stop_notification_text') }}</span>
                </a>
              </li>
              <li class="main-link">
                <a href="#" data-post-id="{{ $post->id }}" class="notify-user notify">
                  <i class="fa fa-bell" aria-hidden="true"></i>{{ trans('common.get_notifications') }}
                  <span class="small-text">{{ trans('messages.get_notification_text') }}</span>
                </a>
              </li>
              @endif

              @if(Auth::user()->id == $post->user->id)
              <li class="main-link">
                <a href="#" data-post-id="{{ $post->id }}" class="edit-post">
                  <i class="fa fa-edit" aria-hidden="true"></i>{{ trans('common.edit') }}
                  <span class="small-text">{{ trans('messages.edit_text') }}</span>
                </a>
              </li>
              @endif

              @if((Auth::id() == $post->user->id) || ($post->timeline_id == Auth::user()->timeline_id))
              <li class="main-link">
                <a href="#" class="delete-post" data-post-id="{{ $post->id }}">
                  <i class="icon icon-delete" aria-hidden="true"></i>{{ trans('common.delete') }}
                  <span class="small-text">{{ trans('messages.delete_text') }}</span>
                </a>
              </li>
              @endif

              @if(Auth::user()->id != $post->user->id)
               <li class="main-link">
                <a href="#" class="hide-post" data-post-id="{{ $post->id }}">
                  <i class="fa fa-eye-slash" aria-hidden="true"></i>{{ trans('common.hide_notifications') }}
                  <span class="small-text">{{ trans('messages.hide_notification_text') }}</span>
                </a>
              </li>

              <li class="main-link">
                <a href="#" class="save-post" data-post-id="{{ $post->id }}">
                  <i class="icon icon-save" aria-hidden="true"></i>
                    @if(!Auth::user()->postsSaved->contains($post->id))
                      {{ trans('common.save_post') }}
                      <span class="small-text">{{ trans('messages.post_save_text') }}</span>
                    @else
                      {{ trans('common.unsave_post') }}
                      <span class="small-text">{{ trans('messages.post_unsave_text') }}</span>
                    @endif
                </a>
              </li>

              <li class="main-link">
                <a href="#" class="manage-report report" data-post-id="{{ $post->id }}">
                  <i class="icon icon-report" aria-hidden="true"></i>{{ trans('common.report') }}
                  <span class="small-text">{{ trans('messages.report_text') }}</span>
                </a>
              </li>
              @endif
              <li class="divider"></li>

              <li class="main-link">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/share-post/'.$post->id)) }}" class="fb-xfbml-parse-ignore" target="_blank">
                    <i class="fa fa-facebook-square"></i>Facebook {{ trans('common.share') }}
                </a>
              </li>

              <li class="main-link">
                <a href="https://twitter.com/intent/tweet?text={{ url('/share-post/'.$post->id) }}"target="_blank">
                    <i class="fa fa-twitter-square"></i>Twitter {{ trans('common.tweet') }}
                </a>
              </li>

              <li class="main-link">
                <a href="#" data-toggle="modal" data-target="#myModal">
                    <i class="icon icon-share-alt"></i>Embed {{ trans('common.post') }}
                </a>
              </li>

            </ul>

          </li>

        </ul>
      </div>
      <div class="user-avatar">
        <a href="{{ url($post->user->username) }}"><img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" title="{{ $post->user->name }}"></a>
      </div>
      <div class="user-post-details">
        <ul class="list-unstyled no-margin">
          <li>

              @if(isset($sharedOwner))
                <a href="{{ url($sharedOwner->user->username) }}" title="{{ '@'.$sharedOwner->user->username }}" data-toggle="tooltip" data-placement="top" class="user-name user ft-user-name">
                {{ $sharedOwner->user->username }}
              </a>
              shared
              @endif

            <a href="{{ url($post->user->username) }}" title="{{ '@'.$post->user->username }}" data-toggle="tooltip" data-placement="top" class="user-name user ft-user-name">
              {{ $post->user->username }}
            </a>
            @if($post->user->verified)
              <span class="verified-badge bg-success">
                    <i class="icon icon-accept"></i>
                </span>
            @endif

            @if(isset($sharedOwner))
               's post
            @endif

            @if($post->users_tagged->count() > 0)
              {{ trans('common.with') }}
              <?php $post_tags = $post->users_tagged->pluck('name')->toArray(); ?>
              <?php $post_tags_ids = $post->users_tagged->pluck('id')->toArray(); ?>
              @foreach($post->users_tagged as $key => $user)
                @if($key==1)
                  {{ trans('common.and') }}
                    @if(count($post_tags)==1)
                      <a href="{{ url($user->username) }}"> {{ $user->name }}</a>
                    @else
                      <a href="#" data-toggle="tooltip" title="" data-placement="top" class="show-users-modal" data-html="true" data-heading="{{ trans('common.with_people') }}"  data-users="{{ implode(',', $post_tags_ids) }}" data-original-title="{{ implode('<br />', $post_tags) }}"> {{ count($post_tags).' '.trans('common.others') }}</a>
                    @endif
                  @break
                @endif
                @if($post_tags != null)
                  <a href="{{ url($user->username) }}" class="user"> {{ array_shift($post_tags) }} </a>
                @endif
              @endforeach

            @endif
            <div class="small-text">
              @if(isset($timeline))
                @if($timeline->type != 'event' && $timeline->type != 'page' && $timeline->type != 'group')
                  @if($post->timeline->type == 'page' || $post->timeline->type == 'group' || $post->timeline->type == 'event')
                    (posted on
                    <a href="{{ url($post->timeline->username) }}">{{ $post->timeline->name }}</a>
                    {{ $post->timeline->type }})
                  @endif
                @endif
              @endif
            </div>
          </li>
          <li>
            @if(isset($sharedOwner))
               <time class="post-time timeago" datetime="{{ $sharedOwner->created_at }}+00:00" title="{{ $sharedOwner->created_at }}+00:00">
                {{ $sharedOwner->created_at }}+00:00
              </time>
            @else

              <time class="post-time timeago" datetime="{{ $post->created_at }}+00:00" title="{{ $post->created_at }}+00:00">
                {{ $post->created_at }}+00:00
              </time>
            @endif


            @if($post->location != NULL && !isset($sharedOwner))
            {{ trans('common.at') }} <span class="post-place">
              <a target="_blank" href="{{ url('/get-location/'.$post->location) }}">
                <i class="fa fa-map-marker"></i> {{ $post->location }}
              </a>
              </span></li>
            @endif
          </ul>
        </div>
      </div>
    </div>
    <div class="panel-body">
      <div class="text-wrapper">
        <?php
              $links = preg_match_all("/(?i)\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/", $post->description, $matches);

              $main_description = $post->description;
              ?>
              @foreach($matches[0] as $link)
                <?php $linkPreview = new LinkPreview($link);
                  $parsed = $linkPreview->getParsed();
                  foreach ($parsed as $parserName => $main_link) {
                    $data = '<div class="row link-preview">
                              <div class="col-md-3">
                                <a target="_blank" href="'.$link.'"><img src="'.$main_link->getImage().'"></a>
                              </div>
                              <div class="col-md-9">
                                <a target="_blank" href="'.$link.'">'.$main_link->getTitle().'</a><br>'.substr($main_link->getDescription(), 0, 500). '...'.'
                              </div>
                            </div>';
                  }
                 $main_description = str_replace($link, $data, $main_description); ?>
              @endforeach

        <div class="post-image-holder  @if(count($post->images()->get()) == 1) single-image @endif">
          @foreach($post->images()->get() as $postImage)
          @if($postImage->type=='image')
              {{--{{ dd(storage_path()) }}--}}
<?php
                        if (!file_exists(storage_path().'/uploads/users/gallery/thumbnail_'.$postImage->source)) {
                        if(file_exists(storage_path().'/uploads/users/gallery/'.$postImage->source)) {
                            $img = \Intervention\Image\Facades\Image::make(storage_path().'/uploads/users/gallery/'.$postImage->source)->fit(550, 600, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                            $img->save(storage_path().'/uploads/users/gallery/thumbnail_' . $postImage->source, 60);
                        }
                    }
?>
            <a href="{{ url('user/gallery/'.$postImage->source) }}" data-lightbox="imageGallery.{{ $post->id }}" ><img src="{{ url('user/gallery/thumbnail_'.$postImage->source) }}"  title="{{ $post->user->name }}" class="post-image-content" alt="{{ $post->user->name }}"></a>
          @endif
          @endforeach
          </div>
          <p class="post-description">
          {!! clean($main_description) !!}
        </p>

          <div class="post-v-holder">
          @foreach($post->images()->get() as $postImage)
          @if($postImage->type=='video')
            <video width="100%" preload="none" height="auto" poster="{{ url('user/gallery/video/'.$postImage->title) }}.jpg" controls class="video-video-playe">
              <source src="{{ url('user/gallery/video/'.$postImage->source) }}" type="video/mp4">
              <!-- Captions are optional -->
            </video>
          @endif
          @endforeach
        </div>
      </div>
      @if($post->youtube_video_id)
      <iframe  src="https://www.youtube.com/embed/{{ $post->youtube_video_id }}" frameborder="0" allowfullscreen></iframe>
      @endif
      @if($post->soundcloud_id)
      <div class="soundcloud-wrapper">
        <iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/{{ $post->soundcloud_id }}&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>
      </div>
      @endif
    </div>

    <?php
    $display_comment ="";
    $user_follower = $post->chkUserFollower(Auth::user()->id,$post->user_id);
    $user_setting = $post->chkUserSettings($post->user_id);

    if($user_follower != NULL)
    {
      if($user_follower == "only_follow") {
        $display_comment = "only_follow";
      }elseif ($user_follower == "everyone") {
        $display_comment = "everyone";
      }
    }
    else{
      if($user_setting){
        if($user_setting == "everyone"){
          $display_comment = "everyone";
        }
      }
    }

    ?>

    <div class="panel-footer socialite">
      <ul class="list-inline footer-list pos-rel">
        @if(!$post->users_liked->contains(Auth::user()->id))

          {{--<li><a href="#" class="like-post like-{{ $post->id }}" data-post-id="{{ $post->id }}"><i class="fa fa-thumbs-o-up"></i>{{ trans('common.like') }}</a></li>
--}}
          <li>
              <a href="#" class="like-post like-{{ $post->id }}" data-post-id="{{ $post->id }}">
                  <i class="icon icon-like"></i>
              </a>
          </li>
          <li class="hidden">
              <a href="#" class="like-post unlike unlike-{{ $post->id }}" data-post-id="{{ $post->id }}">
                  <i class="icon icon-liked unlike"></i>
              </a>
          </li>
        @else
          <li class="hidden">
              <a href="#" class="like-post like-{{ $post->id }}" data-post-id="{{ $post->id }}">
                  <i class="icon icon-like"></i>
              </a>
          </li>
          <li>
              <a href="#" class="like-post unlike unlike-{{ $post->id }}" data-post-id="{{ $post->id }}">
                  <i class="icon icon-liked unlike"></i>
              </a>
          </li>
        @endif
        <li><a href="#" class="show-comments"><i class="icon icon-comment"></i></a></li>
        @if($post->users_liked()->count() > 0)
            <?php
            $liked_ids = $post->users_liked->pluck('id')->toArray();
            $liked_names = $post->users_liked->pluck('name')->toArray();
            ?>
            <li class="text-center full-center ">
                <a href="#" class="show-users-modal" data-html="true" data-heading="{{ trans('common.likes') }}"  data-users="{{ implode(',', $liked_ids) }}" data-original-title="{{ implode('<br />', $liked_names) }}">
                    <span class="count-circle">
                        <i class="icon icon-like"></i>
                    </span>
                    {{ $post->users_liked->count() }} <span class="hidden-sm hidden-xs">{{ trans('common.likes') }}</span></a>
            </li>
        @endif

        @if($post->comments->count() > 0)
            <li>
                <a href="#" class="show-all-comments"><span class="count-circle"><i class="icon icon-comment"></i></span>{{ $post->comments->count() }} <span class="hidden-sm hidden-xs">{{ trans('common.comments') }}</span></a>
            </li>
        @endif

        @if($post->shares->count() > 0)
            <?php
            $shared_ids = $post->shares->pluck('id')->toArray();
            $shared_names = $post->shares->pluck('name')->toArray(); ?>
            <li>
                <a href="#" class="show-users-modal" data-html="true" data-heading="{{ trans('common.shares') }}"  data-users="{{ implode(',', $shared_ids) }}" data-original-title="{{ implode('<br />', $shared_names) }}"><span class="count-circle"><i class="icon icon-share"></i></span> {{ $post->shares->count() }} {{ trans('common.shares') }}</a>
            </li>
        @endif
            <li class="pull-right"><a href="{!! url('post/'.$post->id) !!}" ><i class="icon icon-share"></i></a></li>
        @if(Auth::user()->id != $post->user_id)
          @if(!$post->users_shared->contains(Auth::user()->id))
            <li class="pull-right"><a href="#" class="share-post share" data-post-id="{{ $post->id }}"><i class="icon icon-label-o"></i></a></li>
            <li class="hidden pull-right"><a href="#" class="share-post unlike shared" data-post-id="{{ $post->id }}"><i class="fa icon icon-label-o unlike"></i></a></li>
          @else
            <li class="hidden pull-right"><a href="#" class="share-post share" data-post-id="{{ $post->id }}"><i class="icon icon-label-o"></i></a></li>
            <li class="pull-right"><a href="#" class="share-post unlike shared" data-post-id="{{ $post->id }}"><i class="fa icon icon-label-o unlike"></i></a></li>
          @endif
        @endif
      </ul>
    </div>

    @if($post->comments->count() > 0 || $post->user_id == Auth::user()->id || $display_comment == "everyone")
      <div class="comments-section all_comments" style="display:none">
        <div class="comments-wrapper">
          <div class="to-comment">  <!-- to-comment -->
            @if($display_comment == "only_follow" || $display_comment == "everyone" || $user_setting == "everyone" || $post->user_id == Auth::user()->id)
            <div class="commenter-avatar">
              <a href="#"><img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" title="{{ Auth::user()->name }}"></a>
            </div>
            <div class="comment-textfield">
              <form action="#" class="comment-form" method="post" files="true" enctype="multipart/form-data" id="comment-form">
                <div class="comment-holder">{{-- commentholder --}}
                  <input class="form-control post-comment" autocomplete="off" data-post-id="{{ $post->id }}" name="post_comment" placeholder="{{ trans('messages.comment_placeholder') }}" >

                    <input type="file" class="comment-images-upload hidden" accept="image/jpeg,image/png,image/gif" name="comment_images_upload">
                     <ul class="list-inline meme-reply hidden">
                      <li><a href="#" id="imageComment"><i class="icon icon-photo" aria-hidden="true"></i></a></li>
                      {{-- <li><a href="#"><i class="fa fa-smile-o" aria-hidden="true"></i></a></li> --}}
                    </ul>
                </div>
                  <div id="comment-image-holder"></div>
              </form>
            </div>
            <div class="clearfix"></div>
            @endif
          </div><!-- to-comment -->

          <div class="comments post-comments-list"> <!-- comments/main-comment  -->
            @if($post->comments->count() > 0)
            @foreach($post->comments as $comment)
            {!! Theme::partial('comment',compact('comment','post')) !!}
            @endforeach
            @endif
          </div><!-- comments/main-comment  -->
        </div>
      </div><!-- /comments-section -->
    @endif
  </div>

  <!-- Modal Ends here -->
  @if(isset($next_page_url))
  <a class="jscroll-next hidden" href="{{ $next_page_url }}">{{ trans('messages.get_more_posts') }}</a>
  @endif

  {{--comment repeative js inejection --}}
  {{--{!! Theme::asset()->container('footer')->usePath()->add('lightbox', 'js/lightbox.min.js') !!}--}}
