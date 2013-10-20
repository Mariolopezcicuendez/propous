$(document).ready(function() 
{
  $('form[role=form]').keyup(function(event)
  {
    if (event.which == 13) // Enter Key
    {
      $('form[role=form] .btn-form-submit').trigger( "click" );
    }
  });
  
  $('.image_captcha_refresh').on("click", function()
  {
    image_captcha_refresh();
    $('.captcha_input').val('');
  });

  $('.forgetpassword_button_enter_button').on("click", function()
  {
    forgetpassword_clean_form();

    var data_post = {};
    data_post.user_email = clean_field($('.forgetpassword_user_email').val());
    data_post.filter_t = $('input[name=filter_t]').val();

    data_post.captcha_word = $('.captcha_input').val();

    var error_occurred = validate_forgetpassword(data_post);
    if (!error_occurred)
    {
      var req = {};
      req.url = '/user/forgetpassword';
      req.type = 'POST';
      req.success = "success_forgetpassword";
      req.error = "error_forgetpassword";
      req.data = data_post;
      ajaxp(req);
    }
    else
    {
      show_fail("forgetpassword_data_alert", lang('p_not_forgetpassword_by_error'));
    }
  });

  $('.forgetpasswordnew_button_enter_button').on("click", function()
  {
    forgetpasswordnew_clean_form();

    var data_post = {};
    data_post.user_token = $('.forgetpasswordnew_token').val();
    data_post.user_password = clean_field($('.forgetpasswordnew_user_password').val());
    data_post.user_re_password = clean_field($('.forgetpasswordnew_user_repassword').val());
    data_post.filter_t = $('input[name=filter_t]').val();

    data_post.captcha_word = $('.captcha_input').val();

    var error_occurred = validate_forgetpasswordnew(data_post);
    if (!error_occurred)
    {
      var req = {};
      req.url = '/user/change_password_token';
      req.type = 'POST';
      req.success = "success_forgetpasswordnew";
      req.error = "error_forgetpasswordnew";
      req.data = data_post;
      ajaxp(req);
    }
    else
    {
      show_fail("forgetpasswordnew_data_alert", lang('p_not_forgetpasswordnew_by_error'));
    }
  });
});

function image_captcha_refresh()
{
  var req = {};
  req.url = '/captcha/get_new_captcha';
  req.type = 'GET';
  req.success = "success_get_new_captcha";
  req.error = "error_get_new_captcha";
  ajaxp(req);
}

function success_get_new_captcha(data)
{
  $('.forgetpassword_image_captcha').html(data.result);
} 

function error_get_new_captcha(data)
{

}

function forgetpassword_clean_form()
{
  $('.forgetpassword_user_email').parent().parent().removeClass('has-error');
  $('.error_validation_forgetpassword_user_email').addClass('hidden');
  $('.forgetpassword_user_email_validation_error').text("");

  $('.forgetpassword_div_captcha').parent().parent().removeClass('has-error');
  $('.error_validation_forgetpassword_captcha').addClass('hidden');
  $('.forgetpassword_captcha_validation_error').text("");
}

function validate_forgetpassword(data)
{
  if (!data.user_email.match(/[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/))
  {
    $('.forgetpassword_user_email').parent().parent().addClass('has-error');
    $('.error_validation_forgetpassword_user_email').removeClass('hidden');
    $('.forgetpassword_user_email_validation_error').text(lang('p_email_not_valid'));
    return true;
  }
  if (!data.captcha_word.match(/[A-Za-z0-9]{1,255}/))
  {
    $('.forgetpassword_div_captcha').parent().parent().addClass('has-error');
    $('.error_validation_forgetpassword_captcha').removeClass('hidden');
    $('.forgetpassword_captcha_validation_error').text(lang('p_invalid_captcha'));
    return true;
  }

  return false;
}

function success_forgetpassword(data)
{
  forgetpassword_clean_form();

  show_success("forgetpassword_data_alert", lang('p_email_sent_correctly'));

  $('.captcha_input').val('');
  image_captcha_refresh();
}

function error_forgetpassword(data)
{
  var error_number = data.status;
  var error_message = data.error.message;

  if (error_number === 1104)
  {
    $('.forgetpassword_user_email').parent().parent().addClass('has-error');
    $('.error_validation_forgetpassword_user_email').removeClass('hidden');
    $('.forgetpassword_user_email_validation_error').text(error_message);
    return;
  }
  if (error_number === 1130)
  {
    show_fail("forgetpassword_data_alert", error_message);
    return;
  }
  if (error_number === 1131)
  {
    show_fail("forgetpassword_data_alert", error_message);
    return;
  }
  if (error_number === 1801)
  {
    $('.forgetpassword_div_captcha').parent().parent().addClass('has-error');
    $('.error_validation_forgetpassword_captcha').removeClass('hidden');
    $('.forgetpassword_captcha_validation_error').text(error_message);
    return;
  }
}

function forgetpasswordnew_clean_form()
{
  $('.forgetpasswordnew_user_password').parent().parent().removeClass('has-error');
  $('.error_validation_forgetpasswordnew_user_password').addClass('hidden');
  $('.forgetpasswordnew_user_password_validation_error').text("");

  $('.forgetpasswordnew_user_repassword').parent().parent().removeClass('has-error');
  $('.error_validation_forgetpasswordnew_user_repassword').addClass('hidden');
  $('.forgetpasswordnew_user_repassword_validation_error').text("");

  $('.forgetpasswordnew_div_captcha').parent().parent().removeClass('has-error');
  $('.error_validation_forgetpasswordnew_captcha').addClass('hidden');
  $('.forgetpasswordnew_captcha_validation_error').text("");
}

function validate_forgetpasswordnew(data)
{
  if (data.user_password.length < USER_PASSWORD_MIN_SIZE)
  {
    $('.forgetpasswordnew_user_password').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_user_password').removeClass('hidden');
    $('.forgetpasswordnew_user_password_validation_error').text(lang('p_password_too_short'));
    return;
  }
  if (data.user_password.length > USER_PASSWORD_MAX_SIZE)
  {
    $('.forgetpasswordnew_user_password').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_user_password').removeClass('hidden');
    $('.forgetpasswordnew_user_password_validation_error').text(lang('p_password_too_long'));
    return;
  }
  if (!data.user_password.match(/[A-Za-z0-9_\-.#@%&áéíóúÁÉÍÓÚüÜñÑ]{1,255}/))
  {
    $('.forgetpasswordnew_user_password').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_user_password').removeClass('hidden');
    $('.forgetpasswordnew_user_password_validation_error').text(lang('p_password_not_valid'));
    return;
  }
  if (!data.user_password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{1,255}$/))
  {
    $('.forgetpasswordnew_user_password').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_user_password').removeClass('hidden');
    $('.forgetpasswordnew_user_password_validation_error').text(lang('p_password_not_valid'));
    return true;
  }
  if (data.user_re_password !== data.user_password)
  {
    $('.forgetpasswordnew_user_repassword').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_user_repassword').removeClass('hidden');
    $('.forgetpasswordnew_user_repassword_validation_error').text(lang('p_password_repassword_not_equals'));
    return;
  }
  if (!data.captcha_word.match(/[A-Za-z0-9]{1,255}/))
  {
    $('.forgetpasswordnew_div_captcha').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_captcha').removeClass('hidden');
    $('.forgetpasswordnew_captcha_validation_error').text(lang('p_invalid_captcha'));
    return true;
  }
  return false;
}

function success_forgetpasswordnew(data)
{
  forgetpasswordnew_clean_form();
  
  show_success("forgetpasswordnew_data_alert", lang('p_password_changed_correctly'));

  $('.forgetpasswordnew_user_password').parent().parent().addClass('hidden');
  $('.forgetpasswordnew_user_repassword').parent().parent().addClass('hidden');
  $('.forgetpasswordnew_button_enter').parent().parent().addClass('hidden');
  $('.forgetpasswordnew_div_login').parent().parent().removeClass('hidden');

  $('.forgetpasswordnew_div_captcha').parent().parent().addClass('hidden');
  $('.forgetpasswordnew_reload_captcha_zone').parent().parent().addClass('hidden');
  $('.forgetpasswordnew_image_captcha').parent().parent().addClass('hidden');
}

function error_forgetpasswordnew(data)
{
  var error_number = data.status;
  var error_message = data.error.message;

  if (error_number === 1105)
  {
    $('.forgetpasswordnew_user_password').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_user_password').removeClass('hidden');
    $('.forgetpasswordnew_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1106)
  {
    $('.forgetpasswordnew_user_password').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_user_password').removeClass('hidden');
    $('.forgetpasswordnew_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1107)
  {
    $('.forgetpasswordnew_user_password').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_user_password').removeClass('hidden');
    $('.forgetpasswordnew_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1108)
  {
    $('.forgetpasswordnew_user_repassword').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_user_repassword').removeClass('hidden');
    $('.forgetpasswordnew_user_repassword_validation_error').text(error_message);
    return;
  }
  if (error_number === 1128)
  {
    show_fail("forgetpasswordnew_data_alert", error_message);
    return;
  }  
  if (error_number === 1132)
  {
    show_fail("forgetpasswordnew_data_alert", error_message);
    return;
  }  
  if (error_number === 1133)
  {
    show_fail("forgetpasswordnew_data_alert", error_message);
    return;
  }  
  if (error_number === 1134)
  {
    show_fail("forgetpasswordnew_data_alert", error_message);
    return;
  } 
  if (error_number === 1801)
  {
    $('.forgetpasswordnew_div_captcha').parent().parent().addClass('has-error');
    $('.error_validation_forgetpasswordnew_captcha').removeClass('hidden');
    $('.forgetpasswordnew_captcha_validation_error').text(error_message);
    return;
  }
}