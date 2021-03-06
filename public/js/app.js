document.getElementById('message-audio-btn').addEventListener("click", function(){
  msgX_.play()
});
document.getElementById('notification-audio-btn').addEventListener("click", function(){
  notX_.play()
});
(function($){
  var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
  if(iOS) {
    $('body').addClass('is-ios')
  }
  setTimeout(function(){
    msgX_.volume = 1
    notX_.volume = 1
  }, 20000)
  $.extend({
    playSound: function(){
      arguments[0] == theme_url + '/sounds/notification' ? $('#notification-audio-btn').click() : $('#message-audio-btn').click()
    }
  });
}(jQuery));
function unjoin_event(el){
    var timeline_id = el.getAttribute('data-timeline');
      $.post(
        SP_source() + "ajax/join-event",
        { 'timeline_id' : timeline_id },
        function(data){
            if(data.status == 200){
                if(data.joined == false) {
                    $(el).parent().find('.btn-primary').html("Register");
                    $(el).remove();
                }
                else {
                    $(el).html("Unregister");   
                }
            }
        },"json"
      );
  }
document_title = document.title;
(function (global) {

  if(typeof (global) === "undefined") {
    throw new Error("window is undefined");
  }
  global.firstHash = true
  var _hash = "!";
  var noBack = function () {
    global.location.href += "#!!"
  };
  var resetBack = function () {
    global.location.hash = '';
  }
  global.onhashchange = function () {
    if (!global.firstHash) {
      $('#' + global.dialogId).MaterialDialog('hide')
      global.location.hash = '';
      global.firstHash = !global.firstHash
    }
    else {
      global.firstHash = !global.firstHash
    }
  };
  global.noBack = noBack
  global.resetBack = resetBack
})(window);

function hashtagify(){
  // Lets turn hashtags in the post clickable
  $('.text-wrapper').each(function() {
    $(this).html($(this).html().replace(
        /#([a-zA-Z0-9]+)/g,
        '<a class="hashtag" href="' + SP_source() + 'gallery/hashtag/$1">#$1</a>'
    ));
  });
}
function mentionify(){
  // Lets turn usernames in the post clickable
  $('.text-wrapper').each(function() {
    $(this).html($(this).html().replace(
        /@([a-zA-Z0-9]+)/g,
        '<a class="hashtag" href="' + SP_source() + '$1">@$1</a>'
    ));
  });
}

$(function () {
  emojify.setConfig({
    img_dir : base_url + 'images/emoji/basic',
    mode: 'img'
  });
  var validFiles = [];
  //Admin panel user sorting
  $('.usersort').on('change', function() {
    window.location = SP_source() + 'admin/users?sort=' + this.value;
  });

  //Admin panel page sorting
  $('.pagesort').on('change', function() {
    window.location = SP_source() + 'admin/pages?sort=' + this.value;
  });

  //Admin panel group sorting
  $('.groupsort').on('change', function() {
    window.location = SP_source() + 'admin/groups?sort=' + this.value;
  });

  //Admin panel event sorting
  $('.eventsort').on('change', function() {
    window.location = SP_source() + 'admin/events?sort=' + this.value;
  });

 /* $(".form_datetime").datetimepicker({
    format: "mm/dd/yyyy hh:ii"
  });*/

  // save/unsave the timelines by logged user
  $('.save-timeline').on('click',function(e){
    e.preventDefault();
    $.post(SP_source() + 'ajax/save-timeline', {timeline_id: $(this).data('timeline-id')}, function(data) {
      if (data.status == 200) {
        notify(data.message,'success');
      }
      else if (data.status == 201) {
        notify(data.message,'warning');
      }
    });
  });

  // To switch language
  $('.switch-language').on('click',function(e){
      e.preventDefault();
      $.post(SP_source() + 'ajax/switch-language', {language: $(this).data('language')}, function(data) {
        if (data.status == 200) {
          if (typeof redirect_source != "undefined") {
            url = SP_source()+'/register';
            window.location = url;
          }
          else {
            window.location = SP_source();
          }
        }
        else if (data.status == 201) {
          notify(data.message,'warning');
        }
      });
    });

  $('.login-form').ajaxForm({
    url: SP_source() + 'login',

    beforeSend: function() {
      login_form = $('.login-form');
      login_button = login_form.find('.btn-submit');
      login_button.attr('disabled', true);
      $('.login-progress').removeClass('hidden');
      $('.login-errors').html('');
    },

    success: function(responseText) {
      login_button.attr('disabled', false);
      $('.login-progress').addClass('hidden');
      if (responseText.status == 200) {
        window.location = responseText.url;
      } else {
        //console.log(responseText.message)
        var n = noty({
          text: responseText.message,
          layout: 'topRight',
          type : 'error',
          theme : 'relax',
          timeout:5000,
          animation: {
            open: 'animated fadeIn', // Animate.css class names
            close: 'animated fadeOut', // Animate.css class names
            easing: 'swing', // unavailable - no need
            speed: 500 // unavailable - no need
          }
        });
      }

    }
  });

  // save/unsave the posts by logged user
  $('.save-post').on('click',function(e){
    e.preventDefault();
    postPanel = $(this).closest('.panel-post');
    $.post(SP_source() + 'ajax/save-post', {post_id: $(this).data('post-id')}, function(data) {
      if (data.status == 200) {
        notify(data.message,'success');
      }
      else if (data.status == 201) {
        notify(data.message,'warning');
      }
    });
  });


  // Unsave a page from saved list of pages
  $('body').on('click','.unsave-timeline',function(e){
    e.preventDefault();
    follow_btn = $(this).closest('.follow-links');
    $.post(SP_source() + 'ajax/save-timeline', {timeline_id: $(this).data('timeline-id')}, function(data) {
      if (data.status == 200) {
        follow_btn.find('.follow').closest('.holder').slideToggle();
        notify(data.message,'success');
      }
    });
  });

  $('#link_other a').attr('target', '_blank');

  // show users modal
  $('body').on('click','.show-users-modal',function(e){
    e.preventDefault();
    // $(this).tooltip('hide');

    $.post(SP_source() + 'ajax/get-users-modal',{user_ids: $(this).data('users'), heading: $(this).data('heading')}, function(responseText) {
      if(responseText.status == 200) {
        $('.modal-content').html(responseText.responseHtml);
      }
    });

    $('#usersModal').modal('show');
  });

  $('body').on('click','.edit-post',function(e){
    e.preventDefault();
    $.post(SP_source() + 'ajax/edit-post',{post_id: $(this).data('post-id'),}, function(responseText) {
      if(responseText.status == 200) {
        $('.modal-content').html(responseText.data);
      }
    });
    $('#usersModal').modal('show');
    setTimeout(function(){
      jQuery("time.timeago").timeago();
    },100);
  });

  $('body').on('click','.btn-delete-user',function(){
    if(confirm('are you sure to delete?'))
    {
      window.location = base_url  + current_username + '/settings/deleteme';
    }
  });

  // This will show modal when the settings are saved and flashed with overlay
  $('#flash-overlay-modal').modal();

  // $(".datepick2").datepicker();
  /*jQuery("time.timeago").timeago();*/

  $(".datepick2").datetimepicker({
    format: "mm/dd/yyyy H P",
    autoclose: true,
    minView: 1,
    startView: "decade",
    showMeridian: true
  });


  $('.create-post-formd').ajaxForm({
    url: SP_source() + 'ajax/create-post',

    beforeSubmit : function validate(formData, jqForm, options) {
      var form = jqForm[0];
      //Uploading selected images on create post box
      var hasFile = false
      for(var i=0; i<=validFiles.length; i++){
        if(validFiles[i] != null)
        {
          hasFile = true
          var file = new File([validFiles[i]], validFiles[i].name  ,{type: validFiles[i].type});
          formData.push({name:'post_images_upload_modified[]', value: file})
        }
      }
      validFiles = []; // making array empty

      if (!hasFile && !$('.post-video-upload').val() && !form.description.value && !form.youtube_video_id.value && !form.location.value && !form.soundcloud_id.value) {
        materialSnackBar({messageText: 'Your post cannot be empty!', autoClose: true })
        return false;
      }

    },

    beforeSend: function() {
      create_post_form = $('.create-post-form');
      create_post_button = create_post_form.find('.btn-submit');
      create_post_button.attr('disabled', true).append(' <i class="fa fa-spinner fa-pulse "></i>');
      create_post_form.find('.post-message').fadeOut('fast');
    },

    success: function(responseText) {
      create_post_button.attr('disabled', false).find('.fa-spinner').addClass('hidden');
      if (responseText.status == 200)
      {
        $('.no-posts').hide();
        // Resetting the create post form after successfull message
        $('.video-addon').hide();
        $('.music-addon').hide();
        $('.emoticons-wrapper').hide();
        $('.user-tags-addon').hide();
        $('.user-tags-added').hide();
        $(".user-results").hide();
        create_post_form.find("input[type=text], textarea, input[type=file]").val("");
        create_post_form.find('.youtube-iframe').empty();
        create_post_form.find('#post-image-holder').empty();
        create_post_form.find('.post-images-selected').hide();
        create_post_form.find('#post-video-holder').empty();
        create_post_form.find('.post-videos-selected').hide();
        $('[name="youtube_video_id"]').val('');
        $('[name="youtube_title"]').val('');
        $('[name="soundcloud_id"]').val('');
        $('[name="soundcloud_title"]').val('');
        $('[name="user_tags[]"]').val('');
        $('.user-tags').val('');
        $('.user-tag-names').empty('');
        //$('.post-description').linkify()
        window.timeLine.$options.components["app-post"].methods.fetchNewOnePost(responseText.data.id)
        //$('[data-toggle="tooltip"]').tooltip();
        $('[name="description"]').focus();
        materialSnackBar({messageText: 'Your post has been successfully published', autoClose: true })
        emojify.run()
      }
      else
      {
        materialSnackBar({messageText: responseText.message, autoClose: true })
      }
    }

  });

  // Toggle youtube input in create post form
  $('#videoUpload').on('click',function(e){
    e.preventDefault();
    $('.video-addon').slideToggle();
    if($(".music-addon").css("display") === "block"){
      $(".music-addon").slideUp(300);
    }
  });

  // Toggle add Tags input in create post form
  $('#addUserTags').on('click',function(e){
    e.preventDefault();
    $('.user-tags-addon').slideToggle();
    $('.user-tags-added').slideToggle();
  });

  // Toggle music input in create post form
  $('#musicUpload').on('click',function(e){
    e.preventDefault();
    $('.music-addon').slideToggle();
    if($(".video-addon").css("display") === "block"){
      $(".video-addon").slideUp(300);
    }
  });
  // Toggle location input in create post form
  $('#locationUpload').on('click',function(e){
    e.preventDefault();
    $('.location-addon').slideToggle();
  });
  // Toggle emoticons input in create post form

  // Fetch the youtube title and id when keyup
  $('#youtubeText').on('keyup',function(){
    var video_addon = $(this).closest('.video-addon');
    video_addon.find('.fa-film').addClass('fa-spinner fa-spin');
    $(this).closest('.video-addon').find('.fa-film').addClass('fa-spinner fa-spin');
    $.post( SP_source() + 'ajax/get-youtube-video' , { youtube_source: $('#youtubeText').val() , csrf_token: $('[name="csrf_token"]').attr('content') })
        .done(function( data ) {
          if(data.status == 200)
          {
            $('.youtube-iframe').html(data.message.iframe);
            $('[name="youtube_video_id"]').val(data.message.id);
            $('[name="youtube_title"]').val(data.message.title);
            video_addon.find('.fa-film').removeClass('fa-spinner fa-spin');
          }
        });
  });

  $('#youtubeText').bind('input propertychange', function() {
    var video_addon = $(this).closest('.video-addon');
    video_addon.find('.fa-film').addClass('fa-spinner fa-spin');
    $(this).closest('.video-addon').find('.fa-film').addClass('fa-spinner fa-spin');
    $.post( SP_source() + 'ajax/get-youtube-video' , { youtube_source: $('#youtubeText').val() , csrf_token: $('[name="csrf_token"]').attr('content') })
        .done(function( data ) {
          if(data.status == 200)
          {
            $('.youtube-iframe').html(data.message.iframe);
            $('[name="youtube_video_id"]').val(data.message.id);
            $('[name="youtube_title"]').val(data.message.title);
            video_addon.find('.fa-film').removeClass('fa-spinner fa-spin');
          }
        });
  });

  // Fetch the youtube title and id when keyup
  $('#soundCloudText').on('keyup',function(){
    var music_addon = $(this).closest('.music-addon');
    if($(".soundcloud-results").length){
      $(".soundcloud-results").show();
      music_addon.find('.fa-music').addClass('fa-spinner fa-spin');
      $(this).closest('.music-addon').find('.fa-music').addClass('fa-spinner fa-spin')
    }
    else
    {
      $('.soundcloud-results-wrapper').html('<div class="list-group soundcloud-results"></div>');
    }
    $.post( SP_source() + 'ajax/get-soundcloud-results' , { q: $('#soundCloudText').val() , csrf_token: $('[name="csrf_token"]').attr('content') })
        .done(function( responseText ) {
          if(responseText.status == 200)
          {
            music_addon.find('.fa-music').removeClass('fa-spinner fa-spin');
            $('.soundcloud-results').html('');
            var soundCloud_results = jQuery.parseJSON(responseText.data);
            $.each(soundCloud_results, function(key, value) {
              $('.soundcloud-results').append('<a class="list-group-item soundcloud-result-item" data-soundcloud-id="' + value.id  + '" data-soundcloud-title="' + value.title  + '" href="#"> <img src="' +  value.artwork_url + '"> '+ value.title + '</a>');
            });

          }
        });
  });

  // Like/Unlike the post by user
  $(document).on('click','.soundcloud-result-item',function(e){
    e.preventDefault();
    $('#soundCloudText').val($(this).data('soundcloud-title'));
    $('.soundcloud-results').slideToggle();
    $('[name="soundcloud_id"]').val($(this).data('soundcloud-id'));
    $('[name="soundcloud_title"]').val($(this).data('soundcloud-title'));

  });

  // Add user to the post as tag
  $(document).on('click','.user-result-item',function(e){
    e.preventDefault();
    $('.user-tags-added').append('<input type="hidden" value="' + $(this).data('user-id') + '" name="user_tags[]" >');

    var values = $("input[name='user_tags[]']")
        .map(function(){return $(this).val();}).get();
    if(values.length <= 1)
    {
      $('.user-tags-added').find('.user-tag-names').append('<a href="#">' + $(this).data('user-name')  + '</a>');
    }
    else
    {
      $('.user-tags-added').find('.user-tag-names').append(', <a href="#">' + $(this).data('user-name')  + '</a>');
    }


  });

  // Join/Joined the timeline user  by  logged user
  $('.join-user').on('click',function(e){
    e.preventDefault();
    join_btn = $(this).closest('.join-links');
    $.post(SP_source() + 'ajax/join-group', {timeline_id: $(this).data('timeline-id')}, function(data) {
      if (data.status == 200) {
        if (data.joined == true) {
          join_btn.find('.join').parent().addClass('hidden');
          join_btn.find('.joined').parent().removeClass('hidden');
        } else {
          join_btn.find('.join').parent().removeClass('hidden');
          join_btn.find('.joined').parent().addClass('hidden');
        }
      }
    });
  });

  $(".join-event-btn").click(function(){
      let btn = $(this);
      let timeline_id = $(this).data('timeline');
      $.post(
        SP_source() + "ajax/join-event",
        { 'timeline_id' : timeline_id },
        function(data){
            if(data.status == 200){
                if(data.joined == true) {
                    btn.html("Registered");
                    btn.parent().append('<button onclick="unjoin_event(this)" class="btn btn-warning" data-timeline='+timeline_id+'>Unregister</button>');
                }
                else {
                    btn.html("Register");   
                }
            }
        },"json"
      );
  });

  // Join/Joined the event guests  by  logged user
  $('.join-guest').on('click',function(e){
    e.preventDefault();
    join_btn = $(this).closest('.join-links');
    $.post(SP_source() + 'ajax/join-event', {timeline_id: $(this).data('timeline-id')}, function(data) {
      if (data.status == 200) {
        if (data.joined == true) {
          join_btn.find('.join').parent().addClass('hidden');
          join_btn.find('.joined').parent().removeClass('hidden');
        } else {
          join_btn.find('.join').parent().removeClass('hidden');
          join_btn.find('.joined').parent().addClass('hidden');
        }
      }
    });
  });

  // Follow/Requested switching by clcking on follow
  $('.follow-user-confirm').on('click',function(e){
    e.preventDefault();
    join_btn = $(this).closest('.follow-links');
    input_ids = $(this).data('timeline-id').split('-');
    timeline_id = input_ids[0];
    follow_status = input_ids[1];
    $.post(SP_source() + 'ajax/follow-user-confirm', {timeline_id: timeline_id, follow_status: follow_status}, function(data) {
      if (data.status == 200) {
        if (data.followrequest == true) {
          join_btn.find('.follow').parent().addClass('hidden');
          join_btn.find('.followrequest').parent().removeClass('hidden');
        }
        else if(data.unfollow == true)
        {
          join_btn.find('.unfollow').parent().addClass('hidden');
          join_btn.find('.follow').parent().removeClass('hidden');
        }
        else
        {
          join_btn.find('.follow').parent().removeClass('hidden');
          join_btn.find('.followrequest').parent().addClass('hidden');
        }
      }
    });
  });

  // Join group/Joined the timeline user  by  logged user
  $('.join-close-group').on('click',function(e){
    e.preventDefault();
    join_btn = $(this).closest('.join-links');
    $.post(SP_source() + 'ajax/join-close-group', {timeline_id: $(this).data('timeline-id')}, function(data) {
      if (data.status == 200) {
        if (data.joinrequest == true) {
          join_btn.find('.join').parent().addClass('hidden');
          join_btn.find('.joinrequest').parent().removeClass('hidden');
        } else if(data.join == true) {
          join_btn.find('.joined').parent().addClass('hidden');
          join_btn.find('.join').parent().removeClass('hidden');
        }else{
          join_btn.find('.join').parent().removeClass('hidden');
          join_btn.find('.joinrequest').parent().addClass('hidden');
        }
      }
    });
  });

  // Follow/UnFollow the timeline user  by  logged user
  $('body').on('click','.follow-user',function(e){
    e.preventDefault();
    alert()
    return
    follow_btn = $(this).closest('.follow-links');
    $.post(SP_source() + 'ajax/follow-post', {timeline_id: $(this).data('timeline-id')}, function(data) {
      if (data.status == 200) {
        if (data.followed == true) {
          follow_btn.find('.follow').parent().addClass('hidden');
          follow_btn.find('.unfollow').parent().removeClass('hidden');
        } else {
          follow_btn.find('.follow').parent().removeClass('hidden');
          follow_btn.find('.unfollow').parent().addClass('hidden');
        }
        follow_btn.find('.unfollow').closest('.holder').slideToggle();
      }
    });
  });

  $('body').on('click', '[data-toggle="follow"]', function (e){
    e.preventDefault();
    var el = $(this)
    el.attr('disabled', true)
    el.attr('data-processing', true)
    $.post(SP_source() + 'ajax/follow-user-confirm', {timeline_id: el.data('timeline-id')}, function(data) {
      console.log(data)
      el.attr('disabled', false)
      el.attr('data-processing', false)
      if (data.status == 200) {
        console.log(data)
        if (data.followed == true) {
          el.attr('data-following', true)
        } else {
          el.attr('data-following', false)
        }
        materialSnackBar({messageText: data.message, autoClose: true })
        var r = el.attr('data-noreload')
        if(r !== undefined && r) {
          if(data.followrequest) {
            el.attr('data-following', true)
              if(data.follow_status !== 'approved') {
                  el.find('.true').text('Requested')
                  el.attr('disabled', true)
              }
          }
        } else {
          location.reload();
        }
      }
    })
  });

  $('body').on('click', '[data-toggle="eventRegister"]', function (e){
    e.preventDefault();
    var el = $(this)
    el.attr('disabled', true)
    el.attr('data-processing', true)
    var btn = $(this);
    var _token = $("meta[name=_token]").attr('content')
    $.post(SP_source() + 'ajax/unregister-event', {_token: _token, user_id: el.data('user-id'), event_id:el.data('event-id') }, function(data) {
      el.attr('data-processing', false)
      if (data.status == 200) {
        el.text('Unregistered')
        materialSnackBar({messageText: data.data, autoClose: true })
      }
    })
  });

  //Accept user request through join request tab in close group
  $('.accept-user').on('click',function(e){
    e.preventDefault();
    input_ids = $(this).data('user-id').split('-');
    user_id = input_ids[0];
    group_id = input_ids[1];

    accept_btn = $(this).closest('.follow-links');
    $.post(SP_source() + 'ajax/join-accept', {user_id: user_id,group_id: group_id}, function(data) {
      if (data.status == 200) {
        if (data.accepted == true) {
          accept_btn.find('.accept').closest('.holder').slideToggle();
        }
      }
    });
  });

//Accept follow request through join request tab in close group
  $('.accept-follow').on('click',function(e){
    e.preventDefault();

    accept_btn = $(this).closest('.follow-links');
    $.post(SP_source() + 'ajax/follow-accept', {user_id: $(this).data('user-id')}, function(data) {
      if (data.status == 200) {
        if (data.accepted == true) {
          accept_btn.find('.accept').closest('.holder').slideToggle();
        }
      }
    });
  });

  //Reject follow user request through join request tab in close group
  $('.reject-follow').on('click',function(e){
    e.preventDefault();

    reject_btn = $(this).closest('.follow-links');
    $.post(SP_source() + 'ajax/follow-reject', {user_id: $(this).data('user-id')}, function(data) {
      if (data.status == 200) {
        if (data.rejected == true) {
          reject_btn.find('.reject').closest('.holder').slideToggle();
        }
      }
    });
  });

  //Adding follower through add members tab in close group
  $(document).on('click','.add-member',function(e){
    e.preventDefault();
    input_ids = $(this).data('user-id').split('-');
    user_id = input_ids[0];
    group_id = input_ids[1];
    user_status = input_ids[2];

    add_btn = $(this).closest('.follow-links');
    $.post(SP_source() + 'ajax/add-memberGroup', {user_id: user_id,group_id: group_id,user_status: user_status}, function(data) {
      if (data.status == 200) {
        if (data.added == true) {
          add_btn.find('.add').closest('.holder').slideToggle();
        }
      }
    });
  });

  //Adding follower through add members tab in page
  $(document).on('click','.add-page-member',function(e){
    e.preventDefault();
    input_ids = $(this).data('user-id').split('-');
    user_id = input_ids[0];
    page_id = input_ids[1];
    user_status = input_ids[2];

    add_btn = $(this).closest('.follow-links');
    $.post(SP_source() + 'ajax/add-page-members', {user_id: user_id,page_id: page_id,user_status: user_status}, function(data) {
      if (data.status == 200) {
        if (data.added == true) {
          add_btn.find('.add').closest('.holder').slideToggle();
        }
      }
    });
  });

  //Adding follower through add members tab in page
  $(document).on('click','.add-event-member',function(e){
    e.preventDefault();
    input_ids = $(this).data('user-id').split('-');
    user_id = input_ids[0];
    event_id = input_ids[1];
    user_status = input_ids[2];

    add_btn = $(this).closest('.follow-links');
    $.post(SP_source() + 'ajax/add-event-members', {user_id: user_id,event_id: event_id,user_status: user_status}, function(data) {
      if (data.status == 200) {
        if (data.added == true) {
          add_btn.find('.add').closest('.holder').slideToggle();
        }
      }
    });
  });

  //Manage report user request
  $('.manage-report').on('click',function(e){
    e.preventDefault();
    post_id = $(this).data('post-id');

    report_btn = $(this).closest('.list-inline');
    $.post(SP_source() + 'ajax/report-post', {post_id: post_id}, function(data) {
      if (data.status == 200) {
        if (data.reported == true) {
          //report_btn.find('.report').closest('.holder').slideToggle();
          $('#post'+post_id).slideToggle();
          notify('You have successfully reported the page');
        }
      }
    });
  });

  // smiley's on posts


  // Page Like/Liked the timeline user  by  logged user
  $('.page-like').on('click',function(e){
    e.preventDefault();
    pagelike_btn = $(this).closest('.pagelike-links');
    $.post(SP_source() + 'ajax/page-like', {timeline_id: $(this).data('timeline-id')}, function(data) {
      if (data.status == 200) {
        if (data.liked == true) {
          pagelike_btn.find('.like').parent().addClass('hidden');
          pagelike_btn.find('.liked').parent().removeClass('hidden');
        } else {
          pagelike_btn.find('.like').parent().removeClass('hidden');
          pagelike_btn.find('.liked').parent().addClass('hidden');
        }
      }
    });
  });

  // Page Report/Reported the timeline user  by  logged user
  $('.page-report').on('click',function(e){
    e.preventDefault();
    pagereport_btn = $(this).closest('.pagelike-links');
    $.post(SP_source() + 'ajax/page-report', {timeline_id: $(this).data('timeline-id')}, function(data) {
      if (data.status == 200) {
        if (data.reported == true) {
          pagereport_btn.find('.report').parent().addClass('hidden');
          pagereport_btn.find('.reported').parent().removeClass('hidden');
          notify('You have successfully reported');
        } else {
          pagereport_btn.find('.report').parent().removeClass('hidden');
          pagereport_btn.find('.reported').parent().addClass('hidden');
          notify('You have successfully unreported');
        }
      }
    });
  });

  // Comment Like/Liked the timeline user  by  logged user
  $(document).on('click','.like-comment',function(e){
    e.preventDefault();
    commentId = $(this).data('comment-id');
    commentlike_btn = $(this).closest('.comments-list');
    $.post(SP_source() + 'ajax/comment-like', {comment_id: $(this).data('comment-id')}, function(data) {
      if (data.status == 200) {
        if (data.liked == true) {
          commentlike_btn.find('.like').parent().addClass('hidden');
          commentlike_btn.find('.unlike').parent().removeClass('hidden');
          $('.comments-list').find('.like3-'+commentId).parent().addClass('hidden');
          $('.like4-'+commentId).empty();
          $('.comments-list').find('.like4-'+commentId).removeClass('hidden').append('<a href="#" class=".show-likes">' + data.likecount + '<i class="fa fa-thumbs-up"></i></a>');
        } else {
          commentlike_btn.find('.like').parent().removeClass('hidden');
          commentlike_btn.find('.unlike').parent().addClass('hidden');
          $('.comments-list').find('.like3-'+commentId).parent().addClass('hidden');
          $('.like4-'+commentId).empty();
          $('.comments-list').find('.like4-'+commentId).removeClass('hidden').append('<a href="#" class=".show-likes">' + data.likecount + '<i class="fa fa-thumbs-up"></i></a>');
        }
      }
    });
  });

  // Post Share/shared the timeline user  by  logged user
  $('body').on('click','.share-post',function(e){
    e.preventDefault();
    post_id = $(this).data('post-id');
    sharepost_btn = $(this).closest('.list-inline');
    $.post(SP_source() + 'ajax/share-post', {post_id: post_id}, function(data) {
      if (data.status == 200) {
        if (data.shared == true) {
          sharepost_btn.find('.share').parent().addClass('hidden');
          sharepost_btn.find('.shared').parent().removeClass('hidden');
          $('.list-inline').find('.share1-'+post_id).parent().addClass('hidden');
          $('.share2-'+post_id).empty();
          $('.list-inline').find('.share2-'+post_id).removeClass('hidden').append('<a href="#" class=".show-share">' + data.share_count + '<i class="fa fa-share"></i></a>');
        } else {
          sharepost_btn.find('.share').parent().removeClass('hidden');
          sharepost_btn.find('.shared').parent().addClass('hidden');
          $('.list-inline').find('.share1-'+post_id).parent().addClass('hidden');
          $('.share2-'+post_id).empty();
          $('.list-inline').find('.share2-'+post_id).removeClass('hidden').append('<a href="#" class=".show-share">' + data.share_count + '<i class="fa fa-share"></i></a>');
        }
      }
    });
  });

  // Timeline Page Liked/Unliked the timeline user  by  logged user
  $(document).on('click','.page-liked',function(e){
    e.preventDefault();
    pagelike_btn = $(this).closest('.page-links');
    $.post(SP_source() + 'ajax/page-liked', {timeline_id: $(this).data('timeline-id')}, function(data) {
      if (data.status == 200) {
        if (data.like == true) {
          pagelike_btn.find('.pageliked').parent().addClass('hidden');
          pagelike_btn.find('.pagelike').parent().removeClass('hidden');
          pagelike_btn.find('.pagelike').closest('.holder').slideToggle();
        }
      }
    });
  });

  // Timeline Group Join/Joined the timeline user  by  logged user
  $(document).on('click','.group-join',function(e){
    e.preventDefault();
    pagelike_btn = $(this).closest('.page-links');
    timeline_id = $(this).data('timeline-id');
    $.confirm({
      title: 'Confirm!',
      content: 'Do you want to unjoin this group?',
      confirmButton: 'Yes',
      cancelButton: 'No',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/group-join', {timeline_id: timeline_id}, function(data) {
          if (data.status == 200) {
            if (data.join == true) {
              pagelike_btn.find('.joined').parent().addClass('hidden');
              pagelike_btn.find('.join').parent().removeClass('hidden');
              pagelike_btn.find('.join').closest('.holder').slideToggle();
              notify('You have successfully unjoined this group','warning');
            }
          }
        });
      },
      cancel: function(){

      }
    });

  });

  //DeleteComment  the timeline user  by  logged user
  $('body').on('click','.delete-comment',function(e){
    e.preventDefault();
    commentdelete_btn = $(this).closest('.delete_comment_list');
    $.post(SP_source() + 'ajax/comment-delete', {comment_id: $(this).data('commentdelete-id')}, function(data) {
      if (data.status == 200) {
        if (data.deleted == true) {
          commentdelete_btn.find('.delete_comment').closest('.comments').slideToggle();
          notify('You have successfully deleted the comment','warning');
        }
      }
    });
  });

  //DeleteComment  the timeline user  by  logged user
  $('body').on('click','.delete-post',function(e){
    e.preventDefault();
    postPanel = $(this).closest('.panel-post');
    post_id = $(this).data('post-id');
    $.confirm({
      title: 'Confirm!',
      content: 'Are you sure to delete the post?',
      confirmButton: 'Yes',
      cancelButton: 'No',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/post-delete', {post_id: post_id}, function(data) {
          if (data.status == 200) {
            postPanel.addClass('fadeOut');
            setTimeout(function(){
              postPanel.remove();
            },800);
            notify('You have successfully deleted the post','warning');
          }
        });
      },
      cancel: function(){

      }
    });
  });

  //Hide notification  the timeline user  by  logged user
  $('body').on('click','.hide-post',function(e){
    e.preventDefault();
    postPanel = $(this).closest('.panel-post');
    post_id = $(this).data('post-id');
    $.confirm({
      title: 'Confirm!',
      content: 'Are you sure to hide the post?',
      confirmButton: 'Yes',
      cancelButton: 'No',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/post-hide', {post_id: post_id}, function(data) {
          if (data.status == 200) {
            postPanel.addClass('fadeOut');
            setTimeout(function(){
              postPanel.remove();
            },800);
            notify('You have successfully hidden the post','warning');
          }
        });
      },
      cancel: function(){

      }
    });

  });

  //ReplyComment  the timeline user  by  logged user
  $(document).on('click','.show-comment-reply',function(e){
    e.preventDefault();
    $(this).parents('.main-comment').find('.comment-reply').slideToggle(100).find('.post-comment').focus();
  });

  //Removing member from group
  $(document).on('click','.remove-member',function(e){
    e.preventDefault();
    input_ids = $(this).data('user-id').split('-');
    user_id = input_ids[0];
    group_id = input_ids[1];
    commentdelete_btn = $(this).closest('.follow-links');

    $.confirm({
      title: 'Confirm!',
      content: 'Do you want to delete member?',
      confirmButton: 'Yes',
      cancelButton: 'No',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/groupmember-remove', {user_id: user_id, group_id: group_id}, function(data) {
          if (data.status == 200) {
            if (data.deleted == true) {
              commentdelete_btn.find('.remove-member').closest('.holder').slideToggle();
              notify('You have successfully deleted the member','warning');
            }else if(data.deleted == false) {
              notify('Assign admin role for member and remove','warning');
            }
          }
        });
      },
      cancel: function(){

      }
    });

  });

  //Removing member from page
  $(document).on('click','.remove-pagemember',function(e){
    e.preventDefault();
    input_ids = $(this).data('user-id').split('-');
    user_id = input_ids[0];
    page_id = input_ids[1];
    commentdelete_btn = $(this).closest('.follow-links');

    $.confirm({
      title: 'Confirm!',
      content: 'Do you want to delete member?',
      confirmButton: 'Yes',
      cancelButton: 'No',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/pagemember-remove', {user_id: user_id, page_id: page_id}, function(data) {
          if (data.status == 200) {
            if (data.deleted == true) {
              commentdelete_btn.find('.remove-pagemember').closest('.holder').slideToggle();
              notify('You have successfully deleted the member','warning');
            }else if(data.deleted == false) {
              notify('Assign admin role for member and remove','warning');
            }
          }
        });
      },
      cancel: function(){

      }
    });

  });


  //Delete Page  the timeline user  by  logged user
  $(document).on('click','.delete-page',function(e){
    e.preventDefault();
    pagedelete_btn = $(this).closest('.deletepage');
    page_id = $(this).data('pagedelete-id');
    $.confirm({
      title: 'Confirm!',
      content: 'Do you want to delete page?',
      confirmButton: 'Yes',
      cancelButton: 'No',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/page-delete', {page_id: page_id}, function(data) {
          if (data.status == 200) {
            if (data.deleted == true) {
              pagedelete_btn.find('.delete_page').closest('.deletepage').slideToggle();
              notify('Page deleted successfully');
            }
          }
        });
      },
      cancel: function(){

      }
    });
  });

  $(document).on('click','.delete-own-event',function(e){
    $.confirm({
      title: 'Confirm!',
      content: 'Are you sure you want to delete this event entirely?',
      confirmButton: 'Yes, delete',
      cancelButton: 'Cancel',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/event-delete', {event_id: event_id}, function(data) {
          if (data.status == 200) {
            if (data.deleted == true) {
              location.reload();
            }
          }
        });
      },
      cancel: function(){

      }
    });
  });

  //Delete event list display by logged user
  $(document).on('click','.delete-event',function(e){
    e.preventDefault();
    eventdelete_btn = $(this).closest('.deleteevent');
    event_id = $(this).data('eventdelete-id');
    $.confirm({
      title: 'Confirm!',
      content: 'Do you want to delete event?',
      confirmButton: 'Yes',
      cancelButton: 'No',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/event-delete', {event_id: event_id}, function(data) {
          if (data.status == 200) {
            if (data.deleted == true) {
              eventdelete_btn.find('.delete_event').closest('.deleteevent').slideToggle();
            }
          }
        });
      },
      cancel: function(){

      }
    });
  });

  //Delete notification by logged user
  $('.notification-delete').on('click',function(e){
    e.preventDefault();
    notification_btn = $(this).closest('.notification-delete');
    notification_id = $(this).data('notification-id');
    $.confirm({
      title: 'Confirm!',
      content: 'Do you want to delete notification?',
      confirmButton: 'Yes',
      cancelButton: 'No',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/notification-delete', {notification_id: notification_id}, function(data) {
          if (data.status == 200)
          {
            if (data.notify == true)
            {
              notification_btn.closest('tr').hide();
            }
          }
        });
      },
      cancel: function(){

      }
    });
  });

  //Delete event on user timeline by logged user
  $(document).on('click','.event-report',function(e){
    e.preventDefault();
    eventdelete_btn = $(this).closest('.deleteevent');
    input_ids = $(this).data('event-id').split('-');
    event_id = input_ids[0];
    username = input_ids[1];
    $.confirm({
      title: 'Confirm!',
      content: 'Do you want to delete event?',
      confirmButton: 'Yes',
      cancelButton: 'No',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/event-delete', {event_id: event_id}, function(data) {
          if (data.status == 200) {
            if (data.deleted == true) {
              window.location = SP_source() + username + '/events';
            }
          }
        });
      },
      cancel: function(){

      }
    });
  });

  // get/stop notifications in the timeline post by user
  $('body').on('click', '.notify-user', function (e) {
    e.preventDefault();
    notify_btn = $(this).closest('.list-inline');
    $.post(SP_source() + 'ajax/notify-user', {post_id: $(this).data('post-id')}, function(data) {
      if (data.status == 200) {
        if (data.notified == true) {
          notify_btn.find('.notify').parent().addClass('hidden');
          notify_btn.find('.unnotify').parent().removeClass('hidden');
        } else {
          notify_btn.find('.notify').parent().removeClass('hidden');
          notify_btn.find('.unnotify').parent().addClass('hidden');
        }
      }
    });
  });

  // Post comments on the post
  $('body').on('keypress', '.post-comment', function (e) {
    if(e.keyCode==13)
    {

      e.preventDefault();

      var current_post = $(this).closest('.panel-post');
      var comment_id = $(this).data('comment-id');
      if($(this).val() ) {
        if(comment_id)
        {
          current_post = $(this).closest('.commented');
        }

        $.post(SP_source() + 'ajax/post-comment', {post_id: $(this).data('post-id'),comment_id: comment_id,description : $(this).val() }, function(responseText) {
          if (responseText.status == 200) {
            if(comment_id)
            {
              // $(current_post).find('.comment-replies').show();
              // $(current_post).find('.comment-replies').append(responseText.data.original);
              // $(current_post).find('.commented').find('.post-comment').val('');
              var commentTag = '.comment' + comment_id;
              if($(commentTag).hasClass('has-replies'))
              {
                $(commentTag).find('.comment-replies').show();
              }
              else
              {
                $(commentTag).append('<li>' +
                    '<div class="comment-replies" style="">' +
                    ' <ul class="list-unstyled comment-replys">' +
                    '</ul>' +
                    '</div>' +
                    '</li>');
                $(commentTag).addClass('has-replies');
              }

              $(commentTag).find('.comment-replies').find('.comment-replys').prepend(responseText.data.original);
              $(commentTag).find('.post-comment').val('');

            }
            else
            {
              $(current_post).find('div.post-comments-list').prepend(responseText.data.original);
              $(current_post).find('.post-comment').val('');
            }

            $(current_post).find('.post-comment').val('');
            jQuery("time.timeago").timeago();
          }
        });
      }
    }
  });

  $(document).on('click','.show-comments',function(e){
    e.preventDefault();
    var comments_section = $(this).closest('.panel-footer').next('.comments-section');
    comments_section.slideToggle();
    setTimeout(function(){
      comments_section.find('.post-comment').focus();
    },100);
  });

  $(document).on('click','.show-all-comments',function(e){
    e.preventDefault();
    var all_comments = $(this).closest('.panel-post');
    all_comments.find('.comments-section').slideToggle();
  });

  $(document).on('click','.show-comment-replies',function(e){
    e.preventDefault();
    $(this).next().slideToggle();
  });

  // Change avatar button click event
  $(document).on('click','.change-avatar',function(e){
    e.preventDefault();
    $('.change-avatar-input').trigger('click');
  });

  $(document).on('change','.change-avatar-input',function(e){
    e.preventDefault();
    $('form.change-avatar-form').submit();
  });

  $('form.change-avatar-form').ajaxForm({
    url: SP_source() + 'ajax/change-avatar',

    beforeSend: function() {
      $('.user-avatar-progress').html('0%<br>Uploaded').fadeIn('fast').removeClass('hidden');
    },

    uploadProgress: function(event, position, total, percentComplete) {
      var percentVal = percentComplete+'%';


      $('.user-avatar-progress').html(percentVal+'<br>Uploaded');

      if (percentComplete == 100) {

        setTimeout(function () {
          $('.user-avatar-progress').html('Processing');
          setTimeout(function () {
            $('.user-avatar-progress').html('Please wait');
          }, 2000);
        }, 500);
      }
    },
    success: function(responseText) {

      if (responseText.status == 200) {
        $('.timeline-user-avtar').find('img')
            .attr('src', responseText.avatar_url)
            .load(function() {
              $('.user-avatar-progress').fadeOut('fast').addClass('hidden').html('');
              $('.change-avatar-input').val();
            });
      }
      else {
        $('.user-avatar-progress').fadeOut('fast').addClass('hidden').html('');
        $('.change-avatar-input').val();
        notify(responseText.message,'warning');
      }
    }
  });

  // Change cover button click event
  $(document).on('click','.change-cover',function(e){
    e.preventDefault();
    $('.change-cover-input').trigger('click');
  });

  $(document).on('change','.change-cover-input',function(e){
    e.preventDefault();
    $('form.change-cover-form').submit();
  });

  hashtagify();
  mentionify();

  //Image upload trigger on create post    // Change cover button click event

  $(document).on('click','#selfVideoUpload',function(e){
    e.preventDefault();
    $('.post-video-upload').trigger('click');
  });


  $(document).on('change','.post-video-upload',function(e){
    e.preventDefault();
    var files = !!this.files ? this.files : [];

    if((files[0].size/1024)/1024 < 50)
    {

      $('.post-video-selected').find('span').text(files[0]['name']);
      $('.post-video-selected').show('slow');
      if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
    }
    else
    {
      $('.post-video-upload').val("");
      alert('file size is more than 50 MB');
    }

  });

  //  Navbar Search suggestions
  var bigSearchUrl = $('#navbar-search').data('url');

  //  Create post user tags
  var bigSearchUrl = $('#navbar-search').data('url');

  var selectizeUsers = $('#userTags').selectize({
    valueField: 'id',
    labelField: 'name',
    searchField: 'name',
    plugins: ['remove_button'],
    render: {
      option: function(item, escape) {
        if(item.about != null)
        {
          var about = escape(item.about);
        }
        else
        {
          var about = '(no description added)';
        }

        return '<div class="media big-search-dropdown">' +
            '<a class="media-left" href="#">' +
            '<img src="'+ item.avatar + '" alt="...">' +
            '</a>' +
            '<div class="media-body">' +
            '<h4 class="media-heading">' + escape(item.name) + '</h4>' +
            '<p>' +  about +  '</p>' +               '</div>' +
            '</div>';
      },

    },
    onChange: function(value)
    {
      $('[name="user_tags"]').val(value);
      // $('.user-tags-added').find('.user-tag-names').append('<a href="#">' + value  + '</a>');
      var selectize = selectizeUsers[0].selectize;
      var values = selectize.items;

      getUsersData();
    },
    load: function(query, callback) {
      if (!query.length) return callback();
      $.ajax({
        url: base_url  + 'api/v1/users',
        type: 'GET',
        dataType: 'json',
        data: {
          search: query
        },
        error: function() {
          callback();
        },
        success: function(res) {
          callback(res.data);
        }
      });
    }
  });

  function getUsersData() {
    var selectize = selectizeUsers[0].selectize;
    var values = selectize.getValue();
    var array = values.split(',');
    var selectedUserTags = ''
    $.each(array, function(key, value) {
      selectedUserTags = selectedUserTags  + '<a href="#">' + selectize.options[value].name  + '</a>, ';
    });

    $('.user-tags-added').find('.user-tag-names').html(selectedUserTags);
  }


// Adding members to the group
  $('#add-members-group').on('keyup',function(){
    $('.group-suggested-users').empty();
    if($('#add-members-group').val() != null && $('#add-members-group').val() != "")
      groupId = $(this).data('group-id');
    $.post( SP_source() + 'ajax/get-users' , { searchname: $('#add-members-group').val() ,group_id: groupId, csrf_token: $('[name="csrf_token"]').attr('content') })
        .done(function( responseText ) {

          if(responseText.status == 200)
          {
            var users_results = responseText.data;

            $.each(users_results, function(key, value) {

              var user = value[0];
              var joinStatus = '';
              var user_id = '';
              var group_id = '';

              if(user.groups[0] != null)
              {
                user_id = user.groups[0].pivot.user_id;
                group_id = user.groups[0].pivot.group_id;

                if(user.groups[0].pivot.status == "pending")
                {
                  joinStatus = 'Join Requested';

                }
                else if(user.groups[0].pivot.status == "approved")
                {
                  joinStatus = 'Joined';
                }
              }
              else
              {
                user_id = user.id;
                group_id = groupId;
                joinStatus = 'Join';
              }


              if(user.avatar_id != null){
                avatarSource = user.avatar_url[0].source;

              }else{
                avatarSource = "default-"+user.gender+"-avatar.png";
              }

              var verified = '';

              if(user.verified == 1)
              {
                var verified = '<span class="verified-badge verified-small bg-success"> <i class="fa fa-check"></i></span>';
              }

              $('.group-suggested-users').append('<div class="holder">' +
                  '<div class="follower side-left">' +
                  '<a href="' +  SP_source() + user.username + '">' +
                  '<img src="' + SP_source() + 'user/avatar/'+ avatarSource +'" alt="images">' +
                  '</a>' +
                  '<a href="' +  SP_source() + user.username + '">' +
                  '<span>' + user.name + '</span>' +
                  '</a>' + verified +
                  '</div>' +
                  '<div class="follow-links side-right">' +
                  '<div class="left-col">' +
                  '<a href="#" class="btn btn-to-follow btn-default add-member  add" data-user-id="'+user_id+' - '+group_id+'-'+joinStatus+'">' + joinStatus + '</a>' +
                  '</div>' +
                  '</div>' +
                  '<div class="clearfix"></div>'+
                  '</div>');

            });
          }
        });
  });

  // Adding members to the event
  $('#add-members-event').on('keyup',function(){
    $('.event-suggested-users').empty();
    if($('#add-members-event').val() != null && $('#add-members-event').val() != "")
      eventId = $(this).data('event-id');
    $.post( SP_source() + 'ajax/get-members-invite', { searchname: $('#add-members-event').val() ,event_id: eventId, csrf_token: $('[name="csrf_token"]').attr('content') })
        .done(function( responseText ) {

          if(responseText.status == 200)
          {
            var users_results = responseText.data;

            $.each(users_results, function(key, value) {

              var user = value[0];
              var joinStatus = '';
              var user_id = '';
              var event_id = '';

              if(user.events[0] != null)
              {
                user_id = user.events[0].pivot.user_id;
                event_id = user.events[0].pivot.event_id;
                joinStatus = 'Invited';
              }
              else
              {
                user_id = user.id;
                event_id = eventId;
                joinStatus = 'Invite';
              }


              if(user.avatar_id != null){
                avatarSource = user.avatar_url[0].source;

              }else{
                avatarSource = "default-"+user.gender+"-avatar.png";
              }

              var verified = '';
              if(user.verified == 1)
              {
                var verified = '<span class="verified-badge verified-small bg-success"> <i class="fa fa-check"></i></span>';
              }

              $('.event-suggested-users').append('<div class="holder">' +
                  '<div class="follower side-left">' +
                  '<a href="' +  SP_source() + user.username + '">' +
                  '<img src="' + SP_source() + 'user/avatar/'+ avatarSource +'" alt="images">' +
                  '</a>' +
                  '<a href="' +  SP_source() + user.username + '">' +
                  '<span>' + user.name + '</span>' +
                  '</a>' + verified +
                  '</div>' +
                  '<div class="follow-links side-right">' +
                  '<div class="left-col">' +
                  '<a href="#" class="btn btn-to-follow btn-default add-event-member  add" data-user-id="'+user_id+' - '+event_id+'-'+joinStatus+'">' + joinStatus + '</a>' +
                  '</div>' +
                  '</div>' +
                  '<div class="clearfix"></div>'+
                  '</div>');
            });
          }
        });
  });


//Adding members to the page

  $('#add-members-page').on('keyup',function(){
    $('.page-suggested-users').empty();
    if($('#add-members-page').val() != null && $('#add-members-page').val() != "")
      pageId = $(this).data('page-id');
    $.post( SP_source() + 'ajax/get-members-join' , { searchname: $('#add-members-page').val() ,page_id: pageId, csrf_token: $('[name="csrf_token"]').attr('content') })
        .done(function( responseText ) {

          if(responseText.status == 200)
          {
            var users_results = responseText.data;

            $.each(users_results, function(key, value) {

              var user = value[0];
              var joinStatus = '';
              var user_id = '';
              var page_id = '';

              if(user.pages[0] != null)
              {
                user_id = user.pages[0].pivot.user_id;
                page_id = user.pages[0].pivot.page_id;
                joinStatus = 'Joined';
              }
              else
              {
                user_id = user.id;
                page_id = pageId;
                joinStatus = 'Join';
              }


              if(user.avatar_id != null){
                avatarSource = user.avatar_url[0].source;

              }else{
                avatarSource = "default-"+user.gender+"-avatar.png";
              }
              var verified = '';

              if(user.verified == 1)
              {
                var verified = '<span class="verified-badge verified-small bg-success"> <i class="fa fa-check"></i></span>';
              }

              $('.page-suggested-users').append('<div class="holder">' +
                  '<div class="follower side-left">' +
                  '<a href="' +  SP_source() + user.username + '">' +
                  '<img src="' + SP_source() + 'user/avatar/'+ avatarSource +'" alt="images">' +
                  '</a>' +
                  '<a href="' +  SP_source() + user.username + '">' +
                  '<span>' + user.name + '</span>' +
                  '</a>' + verified +
                  '</div>' +
                  '<div class="follow-links side-right">' +
                  '<div class="left-col">' +
                  '<a href="#" class="btn btn-to-follow btn-default add-page-member  add" data-user-id="'+user_id+' - '+page_id+'-'+joinStatus+'">' + joinStatus + '</a>' +
                  '</div>' +
                  '</div>' +
                  '<div class="clearfix"></div>'+
                  '</div>');

            });
          }
        });
  });

  // for timeline-list toggle in small screens
  $('.btn-status').on('click',function(e){
    // $('.timeline-list .list-inline').slideToggle('slow');
    // $('.timeline-list .list-inline').toggle('slow');
    e.preventDefault();
    if($(window).width() < 1200) {

      $('.timeline-list .list-inline').slideToggle('slow');
    }
  });

  $(window).on('resize',function(){
    var win = $(this);
    if (win.width()>= 1200){
      $('.timeline-list .list-inline').show('slow');
    }
  });

  //smooth scroll intialization

  $(".smooth-scroll").mCustomScrollbar("scrollTo","bottom",{
    autoHideScrollbar:true,
    theme:"rounded",
    mouseWheel:{ preventDefault: true }
  });

  //tooltip intialization
  $('[data-toggle="tooltip"]').tooltip();

  // focus fix for input
  $('.input-group-addon').on('click',function(){
    $(this).parents('.input-group').find('.form-control').trigger('select');
  });
  $('.input-group .form-control').on('focus',function(){
    $(this).parents('.input-group').addClass('input-group-focus');
  });
  $('.input-group .form-control').on('blur',function(){
    $(this).parents('.input-group').removeClass('input-group-focus');
  });


  function notify(message,type,layout)
  {
    var n = noty({
      text: message,
      layout: 'bottomLeft',
      type : type ? type : 'success',
      theme : 'relax',
      timeout:5000,
      animation: {
        open: 'animated fadeIn', // Animate.css class names
        close: 'animated fadeOut', // Animate.css class names
        easing: 'swing', // unavailable - no need
        speed: 500 // unavailable - no need
      }
    });
  };

  function readURL(input, imageId) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $(imageId).attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#imgInp").change(function(){
    readURL(this,"#blah");
  });

  $(".settings_switch").change(function(){
    // $(this).parent('.email_follower').css("color",this.checked ? "red" : "#354052");
    alert('vj');
  });

//WYSIWYG EDITOR(TinyMCE)
  /*tinymce.init({
    selector: '.mytextarea',
    theme: 'modern',
    height : 84,
    max_width: 884.25,
    plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
      'searchreplace wordcount visualblocks visualchars  code fullscreen insertdatetime media nonbreaking',
      'save table contextmenu directionality emoticons template paste textcolor'
    ],
    content_css: '../../../themes/default/assets/css/tinymce.css',
    toolbar: 'bold italic underline strikethrough | link blockquote image code | bullist  numlist alignjustify aligncenter alignleft alignright',
    menubar: true,
    statusbar: false,
    resize: true,

  });*/

  $('.add_selectize').selectize({
    plugins: ['drag_drop'],
    delimiter: ',',
    persist: false,
    create: function(input) {
      return {
        value: input,
        text: input
      }
    }
  });

  //Delete notification by logged user
  $('.allnotifications-delete').on('click',function(e){
    e.preventDefault();
    notification_btn = $(this).closest('.allnotifications-delete');
    $.confirm({
      title: 'Confirm!',
      content: 'Do you want to delete all notifications?',
      confirmButton: 'Yes',
      cancelButton: 'No',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-danger',

      confirm: function(){
        $.post(SP_source() + 'ajax/allnotifications-delete', {}, function(data) {
          if (data.status == 200)
          {
            if (data.allnotify == true)
            {
              window.location = SP_source() + 'allnotifications';
            }
          }
        });
      },
      cancel: function(){

      }
    });
  });

});

$('.checkbox-panel .checkbox-label').on('click',function(){
  $(this).parents('.checkbox-panel').find('.checkbox-input').trigger('click')
  if($(this).parents('.checkbox-panel').find('input.checkbox-input').is(':checked')) {
    $(this).parents('.checkbox-panel').find('.widget-card.preview.with-slim').addClass('pd-10')
    $(this).parents('.checkbox-panel').find('.input-label').addClass('extra-space')
  }
  else{
    $(this).parents('.checkbox-panel').find('.widget-card.preview.with-slim').removeClass('pd-10')
    $(this).parents('.checkbox-panel').find('.input-label').removeClass('extra-space')
  }
})
$('.checkbox-panel').on('click',function(){
  $(this).find('.checkbox-input').trigger('click')
  if($(this).find('input.checkbox-input').is(':checked')) {
    $(this).find('.widget-card.preview.with-slim').addClass('pd-10')
    $(this).find('.input-label').addClass('extra-space')
  }
  else{
    $(this).find('.widget-card.preview.with-slim').removeClass('pd-10')
    $(this).find('.input-label').removeClass('extra-space')
  }
})

$(document).on('click', '.change-theme', function(e){
  var color = $(this).attr('data-theme');
  $('body').attr('data-theme', color)
})

// Image upload on create post on timeline
$(document).on('change','#event_images_upload',function(e){
  e.preventDefault();
  var files = !!this.files ? this.files : [];
  if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
  var imgPath = $(this)[0].value;
  var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
  var image_holder = $("#event_images_upload--image");
  image_holder.empty();
  if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg")
  {
    if (typeof(FileReader) != "undefined")
    {
      let validFiles = [];
      //loop for each file selected for uploaded.
      $.each(files, function(key,val) {
        validFiles.push(files[key]);

        var reader = new FileReader();
        reader.onload = function(e) {
          var file = e.target;
          var image = new Image();
          image.src = file.result;
          $("<span class=\"pip\" data-index='"+ key +"'>" +
              "<img class=\"thumb-image\" src='" + e.target.result + "'/>" +
              "<a data-id=" + (key) + " class='event-remove-thumb'><i class='icon icon-close'></i></a>" +
              "<div class='image-loader'>" +
              "<div class='image-loader-progress'></div>" +
              "</div>" +
              "</span>").appendTo(image_holder);
          $('.event_images_upload--label').addClass('image-added')
          image.onload = function(){
            if(this.width < 600 || this.height < 150) {
              imgPath = '';
              validFiles = [];
              image_holder.empty();
              files.length = 0;
              $('.post-images-selected').hide('slow');
              $('.post-images-selected').find('span').text(files.length);
              return;
            }
          };
        }
        image_holder.show();
        reader.readAsDataURL(files[key]);
      });
    } else {
      alertApp("This browser does not support FileReader.");
    }
  } else {
    alertApp("Please select only images");
  }
});

$(document).on('click','.event-remove-thumb',function(e) {
  e.preventDefault()
  $('#event_images_upload--image').empty();
  $('#event_images_upload').val('')
  $('.event_images_upload--label').removeClass('image-added')
})

$(document).on('click','[data-toggle="unblock"]',function(e) {
  e.preventDefault()
  var $el = $(e.target)
  var u_id = $el.attr('data-userid')
  let _token = $("meta[name=_token]").attr('content')
  axios({
    method: 'post',
    responseType: 'json',
    url: base_url+'ajax/unblock-user',
    data :{
      _token: _token,
      user_id: u_id
    }
  }).then( function (response) {
    if (response.status ==  200) {
      materialSnackBar({autoClose: true, message: response.data.message})
      window.location.href = ''
    }
  }).catch(function(error) {
    console.log(error)
  })

})

$(function() {
  $('#app-alert').MaterialDialog({show:false})
})

function alertApp (text) {
  $('#app-alert .app-alert__text').html(text)
  $('#app-alert').MaterialDialog('show')
}
$(function() {
  $('.color-picker').click(function() {
    var c = $(this).attr('data-color')
    $('body').attr('data-theme', c)
    let _token = $("meta[name=_token]").attr('content')
    axios({
      method: 'post',
      responseType: 'json',
      url: base_url + 'ajax/set-user-background-color',
      data: {
        _token: _token,
        color_code: c
      }
    }).then(function (response) {
      if (response.status == 200) {
        materialSnackBar({messageText: 'Theme color has been changed', autoClose: true})
      }
    }).catch(function (error) {
      console.log(error)
    })
  })
  hashtagify();
  mentionify();
})

$(function(){
    $('[data-toggle="initChat"]').click(function() {
      var uid = $(this).data('user-id')
      $('#send-direct-dialog .hidden-user-id').val(uid)
      $('#send-direct-dialog').MaterialDialog('show')
    })
    $(".toggleSlide").click(function(){
        $(".mobile-menu-slide").toggleClass('active');
    })
    $('.md-menu__backdrop').click(function() {
      $('.md-menu__container.is-open').removeClass('is-open')
      $(this).removeClass('is-open')
    })
})

function getThumbImage (url, size=0) {
  let url_arr = url.split('/');
  let last_string = url_arr[url_arr.length - 1]
  return size ? url.replace(last_string, size+'_' + last_string) : url.replace(last_string, '100_' + last_string);
}
function raven(e) {
  $(e).parent().addClass('raven-loaded')
}
