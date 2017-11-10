var indexes;
var el = document.getElementById('messages-page');
if(el !== null) {
  new Vue({
    el: '#messages-page',
    data: {
      conversations: [],
      newConversation : false,
      recipients : [],
      currentConversation: {
        conversationMessages : [],
        user : []
      },
      messageBody : ''
    },
    created : function() {
      this.subscribeToPrivateMessageChannel(current_username);
      this.getConversations();

      $('.coversations-thread').bind('scroll',this.chk_scroll);
      $('.coversations-list').bind('scroll',this.chk_scroll);
    },
    methods : {
      notify: function(message,type,layout)
      {
        var n = noty({
          text: message,
          layout: 'bottomLeft',
          type : 'success',
          theme : 'relax',
          timeout:1,
          animation: {
            open: 'animated fadeIn', // Animate.css class names
            close: 'animated fadeOut', // Animate.css class names
            easing: 'swing', // unavailable - no need
            speed: 500 // unavailable - no need
          }
        });
      },
      timeago : function(){
        jQuery.timeago.settings.strings.suffixAgo = "";
        jQuery.timeago.settings.strings.suffixFromNow = "from now";
        jQuery.timeago.settings.strings.inPast = "any moment now";
        jQuery.timeago.settings.strings.seconds = "less than 1m";
        jQuery.timeago.settings.strings.minute = "1m";
        jQuery.timeago.settings.strings.minutes = "%dm";
        jQuery.timeago.settings.strings.hour = "1h";
        jQuery.timeago.settings.strings.hours = "%dh";
        jQuery.timeago.settings.strings.day = "1d";
        jQuery.timeago.settings.strings.days = "%dd";
        jQuery.timeago.settings.strings.month = "1m";
        jQuery.timeago.settings.strings.months = "%dm";
        jQuery.timeago.settings.strings.year = "1y";
        jQuery.timeago.settings.strings.years = "%dy";
        jQuery("time.microtime").timeago();

      },
      subscribeToPrivateMessageChannel: function(receiverUsername)
      {

        var vm = this;
        // pusher configuration
        this.pusher = new Pusher(pusherConfig.PUSHER_KEY, {
          encrypted: true,
          auth: {
            headers: {
              'X-CSRF-Token': pusherConfig.token
            },
            params: {
              username: "vijay"
            }
          }
        });

        this.MessageChannel = this.pusher.subscribe(receiverUsername + '-message-created');
        this.MessageChannel.bind('App\\Events\\MessagePublished', function(data) {

          data.message.user = data.sender;
          if(vm.currentConversation.id ==  data.message.thread_id)
          {
            vm.currentConversation.conversationMessages.push(data.message);
            setTimeout(function(){
              vm.timeago();
              vm.autoScroll('.coversations-thread');
            },100)
          }
          else
          {

            indexes = $.map(vm.conversations.data, function(thread, key) {
              if(thread.id == data.message.thread_id) {
                return key;
              }
            });

            if(indexes != '')
            {
              vm.conversations.data[indexes[0]].unread = true;
              vm.conversations.data[indexes[0]].lastMessage = data.message;
            }
            else
            {
              vm.$http.post(base_url + 'ajax/get-message/' + data.message.thread_id).then( function(response) {
                vm.conversations.data.unshift(response.data.data);
              });
            }
          }

        });
      },
      getConversations : function() {
        axios.post(base_url + 'ajax/get-messages').then(function (response) {
          console.log(response.data)
          // this.conversations = response.data.data;
          // this.showConversation(this.conversations.data[0]);
        });
      }
      ,
      showConversation : function(conversation)
      {
        this.newConversation = false;
        if(conversation)
        {
          if(conversation.id != this.currentConversation.id)
          {
            conversation.unread = false;
            axios.post(base_url + 'ajax/get-conversation/' + conversation.id).then( function(response) {
              this.currentConversation = response.data.data;
              this.currentConversation.user = conversation.user;
              var vm = this;
              setTimeout(function(){
                vm.autoScroll('.coversations-thread');
                vm.timeago();
              },100)
            });
          }
        }

      },
      postMessage : function(conversation)
      {

        messageBody = this.messageBody;
        this.messageBody = '';
        axios.post(base_url + 'ajax/post-message/' + conversation.id,{message: messageBody}).then( function(response) {
          if(response.status)
          {
            this.currentConversation.conversationMessages.data.push(response.data.data);
            var vm = this;
            $('#messageReceipient').focus();
            setTimeout(function(){
              vm.timeago();
              vm.autoScroll('.coversations-thread');
            },100)

          }
        });

      },
      postNewConversation : function()
      {
        if(this.recipients.length)
        {
          axios.post(base_url + 'ajax/create-message',{message: this.messageBody, recipients : this.recipients}).then( function(response) {
            if(response.status)
            {
              var vm = this;

              newThread = response.data.data;
              indexes = $.map(vm.conversations.data, function(thread, key) {
                if(thread.id == newThread.id) {
                  return key;
                }
              });

              if(indexes != '')
              {
                vm.conversations.data[indexes[0]].unread = true;
                vm.conversations.data[indexes[0]].lastMessage = newThread.lastMessage;
              }
              else
              {
                vm.conversations.data.unshift(response.data.data);
              }

              $('#messageReceipient').focus();
              this.recipients= [];
              this.newConversation = false;
              this.messageBody = "";
              this.showConversation(vm.conversations.data[0]);
              setTimeout(function(){
                vm.timeago();
                vm.autoScroll('.coversations-thread');
              },100)
            }
          });
        }
      },
      autoScroll : function(element)
      {
        $(element).animate({scrollTop: $(element)[0].scrollHeight + 600 }, 2000);
      },
      chk_scroll : function(e)
      {
        var elem = $(e.currentTarget);

        if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight())
        {
          if(elem.data('type')=="threads")
          {
            this.getMoreConversations();
          }
          else
          {
            this.getMoreConversationMessages();
          }
        }
      },
      getMoreConversationMessages : function()
      {
        if(this.currentConversation.conversationMessages.data.length < this.currentConversation.conversationMessages.total)
        {
          axios.post(this.currentConversation.conversationMessages.next_page_url).then( function(response) {
            var latestConversations = response.data.data;


            this.currentConversation.conversationMessages.last_page =  latestConversations.conversationMessages.last_page;
            this.currentConversation.conversationMessages.next_page_url =  latestConversations.conversationMessages.next_page_url;
            this.currentConversation.conversationMessages.per_page =  latestConversations.conversationMessages.per_page;
            this.currentConversation.conversationMessages.prev_page_url =  latestConversations.conversationMessages.prev_page_url;

            var vm = this;
            $.each(latestConversations.conversationMessages.data, function(i, latestConversation) {
              vm.currentConversation.conversationMessages.data.unshift(latestConversation);
            });

            setTimeout(function(){
              vm.timeago();
            },10);
          });
        }
      },
      getMoreConversations : function()
      {
        if(this.conversations.data.length < this.conversations.total)
        {
          axios.post(this.conversations.next_page_url).then( function(response) {
            var latestConversations = response.data.data;


            this.conversations.last_page =  latestConversations.last_page;
            this.conversations.next_page_url =  latestConversations.next_page_url;
            this.conversations.per_page =  latestConversations.per_page;
            this.conversations.prev_page_url =  latestConversations.prev_page_url;


            var vm = this;
            $.each(latestConversations.data, function(i, latestConversation) {
              vm.conversations.data.unshift(latestConversation);
            });

            setTimeout(function(){
              vm.timeago();
            },10);
          });
        }
      },
      showNewConversation : function()
      {
        this.newConversation = true;
        this.currentConversation = {
          user : []
        };
        $('#messageReceipient').focus();
        var vm = this;
        setTimeout(function(){
          vm.toggleUsersSelectize();
        },10);

      },
      toggleUsersSelectize : function()
      {
        var vm = this;
        var selectizeUsers = $('#messageReceipient').selectize({
          valueField: 'id',
          labelField: 'name',
          searchField: 'name',
          render: {
            option: function(item, escape) {
              return '<div class="media big-search-dropdown">' +
                  '<a class="media-left" href="#">' +
                  '<img src="'+ item.avatar + '" alt="...">' +
                  '</a>' +
                  '<div class="media-body">' +
                  '<h4 class="media-heading">' + escape(item.name) + '</h4>' +
                  '<p>' +  item.username +  '</p>' +               '</div>' +
                  '</div>';
            },

          },
          onChange: function(value)
          {
            $('[name="user_tags"]').val(value);
            // $('.user-tags-added').find('.user-tag-names').append('<a href="#">' + value  + '</a>');
            var selectize =selectizeUsers[0].selectize;
            vm.recipients = selectize.items;
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
      }
    }
  });
}

var chatBoxes = new Vue({
  el: '#chatBoxes',
  data: {
    chatBoxes: [],
    messageBody : '',
    conversations : [],
    message: {}
  },
  created : function() {
    this.subscribeToPrivateMessageChannel(current_username);
    this.getConversations();

    $('.chat-conversation-list').bind('scroll',this.chk_scroll);
    $('.following-group').bind('scroll',this.chk_scroll_bottom);
  },
  methods : {
    notify: function(message,type,layout)
    {
      var n = noty({
        text: message,
        layout: 'bottomLeft',
        type : 'success',
        theme : 'relax',
        timeout:1,
        animation: {
          open: 'animated fadeIn', // Animate.css class names
          close: 'animated fadeOut', // Animate.css class names
          easing: 'swing', // unavailable - no need
          speed: 500 // unavailable - no need
        }
      });
    },
    timeago : function(){


      jQuery.timeago.settings.strings.suffixAgo = "";
      jQuery.timeago.settings.strings.suffixFromNow = "from now";
      jQuery.timeago.settings.strings.inPast = "any moment now";
      jQuery.timeago.settings.strings.seconds = "less than 1m";
      jQuery.timeago.settings.strings.minute = "1m";
      jQuery.timeago.settings.strings.minutes = "%dm";
      jQuery.timeago.settings.strings.hour = "1h";
      jQuery.timeago.settings.strings.hours = "%dh";
      jQuery.timeago.settings.strings.day = "1d";
      jQuery.timeago.settings.strings.days = "%dd";
      jQuery.timeago.settings.strings.month = "1m";
      jQuery.timeago.settings.strings.months = "%dm";
      jQuery.timeago.settings.strings.year = "1y";
      jQuery.timeago.settings.strings.years = "%dy";
      jQuery("time.microtime").timeago();

    },
    autoScroll : function(element)
    {
      $(element).animate({scrollTop: $(element)[0].scrollHeight + 600 }, 2000);
    },
    subscribeToPrivateMessageChannel: function(receiverUsername)
    {

      var vm = this;
      // pusher configuration
      this.pusher = new Pusher(pusherConfig.PUSHER_KEY, {
        encrypted: true,
        auth: {
          headers: {
            'X-CSRF-Token': pusherConfig.token
          },
          params: {
            username: "vijay"
          }
        }
      });

      this.MessageChannel = this.pusher.subscribe(receiverUsername + '-message-created');
      this.MessageChannel.bind('App\\Events\\MessagePublished', function(data) {

        indexes = $.map(vm.chatBoxes, function(thread, key) {
          if(thread.id == data.message.thread_id) {
            return key;
          }
        });

        if(indexes[0] >= 0)
        {
          data.message.user = data.sender;
          vm.chatBoxes[indexes[0]].conversationMessages.data.push(data.message);
          vm.autoScroll('.chat-conversation');
        }
        else
        {
          conversation = [];
          conversation.id = data.message.thread_id;
          conversation.user = data.sender;
          vm.showChatBox(conversation);
        }
      });
    },
    getConversations : function()
    {
      axios.post(base_url + 'ajax/get-messages').then( function(response) {
        this.conversations = response.data.data;
      });

    },
    showConversation : function(conversation)
    {
      if(conversation)
      {
        if(conversation.id != this.currentConversation.id)
        {
          conversation.unread = false;
          axios.post(base_url + 'ajax/get-conversation/' + conversation.id).then( function(response) {
            this.currentConversation = response.data.data;
            this.currentConversation.user = conversation.user;
            var vm = this;
            setTimeout(function(){
              // vm.autoScroll('.coversations-thread');
              vm.timeago();
            },100)
          });
        }
      }

    },
    postMessage : function(conversation)
    {
      if(conversation.newMessage != '')
      {
        axios.post(base_url + 'ajax/post-message/' + conversation.id,{message: conversation.newMessage}).then( function(response) {
          if(response.status)
          {
            conversation.conversationMessages.data.push(response.data.data);

            conversation.newMessage="";
            var vm = this;
            setTimeout(function(){
              vm.autoScroll('.chat-conversation');
            },100)

          }
        });
      }

    },
    showChatBox : function(conversation)
    {
      indexes = $.map(this.chatBoxes, function(thread, key) {
        if(thread.id == conversation.id) {
          return key;
        }
      });



      if(indexes[0] >= 0)
      {
        console.log('prevented second opening of chat box');
      }
      else{
        axios.post(base_url + 'ajax/get-conversation/' + conversation.id).then( function(response) {
          if(response.status)
          {
            var chatBox = response.data.data;
            chatBox.newMessage = "";
            chatBox.user = conversation.user;
            chatBox.minimised = false;
            this.chatBoxes.push(chatBox);
            var vm = this;
            setTimeout(function(){
              vm.autoScroll('.chat-conversation');
            },100)

          }
        });
      }
    },
    sendMessage: function(userid)
    {
      var indexes = $.map(this.conversations.data, function(thread, key) {
        if(thread.user)
        {
          if(thread.user.id == userid) {
            return key;
          }
        }
      });

      if(indexes[0] >= 0)
      {
        this.showChatBox(this.conversations.data[indexes[0]]);
      }
      else
      {
        axios.post(base_url + 'ajax/get-private-conversation/' + userid).then( function(response) {
          if(response.status)
          {
            this.showChatBox(response.data.data);
          }
        });
      }

    },
    sendMessageOnClick: function (el) {
      var userId = el.getAttribute('data-user-id')
      console.log(userId)
      if(userId !== undefined) {
        this.sendMessage(userId)
      }
    },
    chk_scroll : function(e)
    {
      var elem = $(e.currentTarget);

      if (elem.scrollTop() == 0)
      {
        this.getMoreConversationMessages();
      }
    },
    getMoreConversationMessages : function()
    {
      if(this.currentConversation.conversationMessages.data.length < this.currentConversation.conversationMessages.total)
      {
        axios.post(this.currentConversation.conversationMessages.next_page_url).then( function(response) {
          var latestConversations = response.data.data;


          this.currentConversation.conversationMessages.last_page =  latestConversations.conversationMessages.last_page;
          this.currentConversation.conversationMessages.next_page_url =  latestConversations.conversationMessages.next_page_url;
          this.currentConversation.conversationMessages.per_page =  latestConversations.conversationMessages.per_page;
          this.currentConversation.conversationMessages.prev_page_url =  latestConversations.conversationMessages.prev_page_url;

          var vm = this;
          $.each(latestConversations.conversationMessages.data, function(i, latestConversation) {
            vm.currentConversation.conversationMessages.data.unshift(latestConversation);
          });

          setTimeout(function(){
            vm.timeago();
          },10);
        });
      }
    },
    chk_scroll_bottom : function(e)
    {
      var elem = $(e.currentTarget);

      if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight())
      {
        this.getMoreConversations();
      }
    },
    getMoreConversations : function()
    {
      if(this.conversations.data.length < this.conversations.total)
      {
        axios.post(this.conversations.next_page_url).then( function(response) {
          var latestConversations = response.data.data;
          this.conversations.last_page =  latestConversations.last_page;
          this.conversations.next_page_url =  latestConversations.next_page_url;
          this.conversations.per_page =  latestConversations.per_page;
          this.conversations.prev_page_url =  latestConversations.prev_page_url;


          var vm = this;
          $.each(latestConversations.data, function(i, latestConversation) {
            vm.conversations.data.unshift(latestConversation);
          });

          setTimeout(function(){
            vm.timeago();
          },10);
        });
      }
    },
    removeChat: function (index) {
      this.chatBoxes.splice(index, 1);
    }
  }
});
window.chatBoxes = chatBoxes

var conversation = new Vue({
  el: '#navbar-right',
  data: {
    notifications: [],
    unreadNotifications: 0,
    notificationsLoaded: false,
    notificationsLoading: false,
    conversations: [],
    posts: [],
    unreadConversations: 0,
    conversationsLoaded: false,
    conversationsLoading: false,
    pusher: [],
  },
  computed: {
    isShowUCM: function () {
      return this.unreadNotifications > 0
    },
    isShowUN: function () {
      return this.unreadConversations > 0
    }
  },
  created : function () {

    $('.dropdown-messages-list').bind('scroll', this.chk_scroll);

    // Get if there are any unread notifications or conversations
    this.getNotificationsCounter();
    this.getConversationsCounter();

    // init the pusher
    this.subscribeToNotificationsChannel();
    this.subscribeToMessagesChannel();

  },
  methods : {
    subscribeToNotificationsChannel: function () {

      var vm = this;
      // pusher configuration
      this.pusher = new Pusher(pusherConfig.PUSHER_KEY, {
        encrypted: true,
        auth: {
          headers: {
            'X-CSRF-Token': pusherConfig.token
          },
          params: {
            username: "vijay"
          }
        }
      });
      this.NotificationChannel = this.pusher.subscribe(current_username + '-notification-created');
      this.NotificationChannel.bind('App\\Events\\NotificationPublished', function (data) {
        vm.unreadNotifications = vm.unreadNotifications + 1;
        data.notification.notified_from = data.notified_from
        if (vm.notifications.data != null) {
          vm.notifications.data.unshift(data.notification);
        }
        vm.notify(data.notification.description);
        $.playSound(theme_url + '/sounds/notification');
        jQuery("time.timeago").timeago();
      });
    }
    ,
    subscribeToMessagesChannel: function () {

      var vm = this;
      // pusher configuration
      this.pusher = new Pusher(pusherConfig.PUSHER_KEY, {
        encrypted: true,
        auth: {
          headers: {
            'X-CSRF-Token': pusherConfig.token
          },
          params: {
            username: "vijay"
          }
        }
      });

      this.MessageChannel = this.pusher.subscribe(current_username + '-message-created');
      this.MessageChannel.bind('App\\Events\\MessagePublished', function (data) {
        vm.unreadConversations = vm.unreadConversations + 1;
        if (vm.conversationsLoaded) {
          vm.conversations.data.unshift(data.message);
        }
        vm.notify(data.message.body);
        jQuery("time.timeago").timeago();
      });
    },
    getNotificationsCounter : function () {
      // Lets get the unread notifications once the Vue instance is ready
      axios.post(base_url + 'ajax/get-unread-notifications').then(function (response) {
        console.log(response.data)
        this.unreadNotifications = response.data.unread_notifications;
      });
    },
    showNotifications : function () {
      if (!this.notificationsLoaded) {
        this.notificationsLoading = true;
        axios.post(base_url + 'ajax/get-notifications').then(function (response) {
          console.log(response)
          this.notifications = response.data.notifications;
          setTimeout(function () {
            jQuery("time.timeago").timeago();
          }, 10);
          this.notificationsLoading = false;
        });
        this.notificationsLoaded = true;
      }
    }
    ,
    getMoreNotifications : function () {
      if (this.notifications.data.length < this.notifications.total) {
        this.notificationsLoading = true;
        axios.post(this.notifications.next_page_url).then(function (response) {
          var latestNotifications = response.data.notifications;

          this.notifications.last_page = latestNotifications.last_page;
          this.notifications.next_page_url = latestNotifications.next_page_url;
          this.notifications.per_page = latestNotifications.per_page;
          this.notifications.prev_page_url = latestNotifications.prev_page_url;

          var vm = this;
          $.each(latestNotifications.data, function (i, latestNotification) {
            vm.notifications.data.push(latestNotification);
          });
          this.notificationsLoading = false;
          setTimeout(function () {
            jQuery("time.timeago").timeago();
          }, 10);
        });
      }
    }
    ,
    markNotificationsRead : function () {

      axios.post(base_url + 'ajax/mark-all-notifications').then(function (response) {
        this.unreadNotifications = 0;
        var vm = this;
        $.map(this.notifications, function (notification, key) {
          vm.notifications[key].seen = true;
        });
      });
    }
    ,
    getConversationsCounter : function () {
      // Lets get the unread  messages once the Vue instance is ready
      axios.post(base_url + 'ajax/get-unread-messages').then(function (response) {
        console.log(response.data.unread_conversations)
        this.unreadConversations = response.data.unread_conversations;
      });

    }
    ,
    showConversations : function () {
      if (!this.conversationsLoaded) {
        this.conversationsLoading = true;
        axios.post(base_url + 'ajax/get-messages').then(function (response) {
          this.conversations = response.data.data;
          setTimeout(function () {
            jQuery("time.timeago").timeago();
          }, 10);
          this.conversationsLoaded = true;
          this.conversationsLoading = false;
        });
      }
    }
    ,
    getMoreConversations : function () {
      this.conversationsLoading = true;

      if (this.conversations.data.length < this.conversations.total) {
        axios.post(this.conversations.next_page_url).then(function (response) {
          var latestConversations = response.data.data;

          this.conversations.last_page = latestConversations.last_page;
          this.conversations.next_page_url = latestConversations.next_page_url;
          this.conversations.per_page = latestConversations.per_page;
          this.conversations.prev_page_url = latestConversations.prev_page_url;

          var vm = this;
          $.each(latestConversations.data, function (i, latestConversation) {
            vm.conversations.data.push(latestConversation);
          });

          this.conversationsLoaded = true;
          this.conversationsLoading = false;

          setTimeout(function () {
            jQuery("time.timeago").timeago();
          }, 10);
        });
      }

    }
    ,
    chk_scroll : function (e) {
      var elem = $(e.currentTarget);
      if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()) {
        if (elem.data('type') == "notifications") {
          this.getMoreNotifications();
        }
        else {
          this.getMoreConversations();
        }
      }
    }
    ,
    notify: function (message, type, layout) {
      var n = noty({
        text: message,
        layout: 'bottomLeft',
        type: 'success',
        theme: 'relax',
        timeout: 1,
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
window.conversation = conversation



var Child = {
  template: '<div>' +
  '' +
  '' +
  '</div>'
}

var timeline = new Vue({
  el: '#timeline-app',
  data: function () {
    return {
      isFirstTime: false
    }
  },
  components: {
    'my-component': Child
  }
})