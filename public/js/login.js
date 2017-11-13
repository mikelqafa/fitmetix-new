var LoginForm = function () {
  var handleLoginForm = function () {
    $('#login-form').on('submit', function (e) {
      e.preventDefault()
      var post_url = SP_source() + 'login'
      var formData = {
        'email' : $('input[name=email]').val(),
        'password' : $('input[name=password]').val(),
        '_token': $('input[name=_token]').val()
      };

      var submitBtn = $('#submit')
      submitBtn.prop('disabled',true);
      $.ajax({
        url : post_url,
        type: "post",
        data: formData
      }).done(function(e){ //
        if(e.status == 200) {
          window.location = e.url
        } else {
          var config = {
            messageText:  e.message,
            alignCenter: false,
            autoClose: false
          }
          window.materialSnackBar(config)
        }
      }).always(function(e){
        submitBtn.prop('disabled',false)
      }).fail(function(e){
        var config = {
          messageText:  'Authentication failed. Please try again!',
          alignCenter: false,
          autoClose: true
        }
        window.materialSnackBar(config)
      })
    })


    $('#signup-form').on('submit', function (e) {
      e.preventDefault()
      var post_url = SP_source() + 'login'
      var formData = {
        'email' : $('input[name=email]').val(),
        'username' : $('input[name=username]').val(),
        'affiliate' : $('input[name=affiliate]').val(),
        'birthday' : $('input[name=birthday]').val(),
        'gender' : $('input[name=gender]').val(),
        'password' : $('input[name=password]').val(),
        '_token': $('input[name=_token]').val()
      };

      var submitBtn = $('#submit')
      submitBtn.prop('disabled',true);
      $.ajax({
        url : post_url,
        type: "post",
        data: formData
      }).done(function(e){ //
        if(e.status == 200) {
          window.location = e.url
        } else {
          var config = {
            messageText:  e.message,
            alignCenter: false,
            autoClose: false
          }
          window.materialSnackBar(config)
        }
      }).always(function(e){
        submitBtn.prop('disabled',false)
      }).fail(function(e){
        var config = {
          messageText:  'Authentication failed. Please try again!',
          alignCenter: false,
          autoClose: true
        }
        window.materialSnackBar(config)
      })
    })

  }
  var  submitAjaxForm = function (post_url, fd) {


  }
  return {
    //main function to initiate
    initLogin: function () {
      handleLoginForm();
    }
  };
}();
LoginForm.initLogin()