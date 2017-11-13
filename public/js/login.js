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
      var post_url = SP_source() + 'register'
      var formData = {
        'email' : $('input[name=email]').val(),
        'username' : $('input[name=username]').val(),
        'affiliate' : $('input[name=affiliate]').val(),
        'birthday' : $('input[name=birthday]').val(),
        'gender' : $('#gender').val(),
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
          console.log(e.err_result)
          var c = 0;
          $.each(e.err_result, function( index, value ) {
            var config = {
              messageText:  value,
              alignCenter: false,
              autoClose: true
            }
            setTimeout(function(){
              window.materialSnackBar(config)
            }, 2000*c)
            c++;
          });
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

    $('.switch-language').on('click',function(e){
      e.preventDefault();
      var formData = {
        'language' : $(this).data('language'),
        '_token': $('input[name=_token]').val()
      };
      $.ajax({
        url : SP_source() + 'ajax/switch-language',
        type: "post",
        data: formData
      }).done(function(e){ //
        if(e.status == 200) {
          window.location = SP_source();
        } else {
          var config = {
            messageText:  e.message,
            alignCenter: false,
            autoClose: false
          }
          window.materialSnackBar(config)
        }
      })
    })
  }
  return {
    //main function to initiate
    initLogin: function () {
      handleLoginForm();
    }
  };
}();
LoginForm.initLogin()