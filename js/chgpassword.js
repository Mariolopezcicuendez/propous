var user_id = null;

$(document).ready(function() 
{
  user_id = $('.gdata_user_id').val();

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

  $('.chgpassword_button_enter_button').on("click", function()
  {
    chgpassword_clean_form();

    var data_post = {};
    data_post.user_old_password = $('.chgpassword_user_oldpassword').val();
    data_post.user_password = $('.chgpassword_user_password').val();
    data_post.user_re_password = $('.chgpassword_user_repassword').val();

    if (USE_CAPTHAS_IN_FORMS)
    {
      data_post.captcha_word = $('.captcha_input').val();
    }

    var error_occurred = validate_chgpassword(data_post);
    if (!error_occurred)
    {
      var req = {};
      req.url = '/user/change_password/'+user_id;
      req.type = 'POST';
      req.success = "success_change_password";
      req.error = "error_change_password";
      req.data = data_post;
      ajaxp(req);
    }
    else
    {
      show_fail("chgpassword_data_alert", lang('p_not_chgpassword_by_error'));
    }
  });

  $('.chgpassword_press_for_logout').on("click", function()
  {
    var req = {};
    req.url = '/user/logout';
    req.type = 'GET';
    req.success = "success_logout";
    req.error = "error_logout";
    ajaxp(req);
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
  $('.chgpassword_image_captcha').html(data.result);
} 

function error_get_new_captcha(data)
{

}

function chgpassword_clean_form()
{
  $('.chgpassword_user_password').parent().parent().removeClass('has-error');
  $('.error_validation_chgpassword_user_password').addClass('hidden');
  $('.chgpassword_user_password_validation_error').text("");

  $('.chgpassword_user_repassword').parent().parent().removeClass('has-error');
  $('.error_validation_chgpassword_user_repassword').addClass('hidden');
  $('.chgpassword_user_repassword_validation_error').text("");

  if (USE_CAPTHAS_IN_FORMS)
  {
    $('.chgpassword_div_captcha').parent().parent().removeClass('has-error');
    $('.error_validation_chgpassword_captcha').addClass('hidden');
    $('.chgpassword_captcha_validation_error').text("");
  }
}

function success_change_password(data)
{
  chgpassword_clean_form();

  show_success("chgpassword_data_alert", lang('p_password_changed_correctly'));

  $('.chgpassword_div_logout').parent().parent().removeClass('hidden');
  $('.chgpassword_button_enter').parent().parent().addClass('hidden');
  $('.chgpassword_user_repassword').parent().parent().addClass('hidden');
  $('.chgpassword_user_oldpassword').parent().parent().addClass('hidden');
  $('.chgpassword_user_password').parent().parent().addClass('hidden');
  
  if (USE_CAPTHAS_IN_FORMS)
  {
    $('.chgpassword_div_captcha').parent().parent().addClass('hidden');
    $('.chgpassword_reload_captcha_zone').parent().parent().addClass('hidden');
    $('.chgpassword_image_captcha').parent().parent().addClass('hidden');
  }
}

function error_change_password(data)
{
  var error_number = data.status;
  var error_message = data.error.message;

  if (error_number === 1105)
  {
    $('.chgpassword_user_password').parent().parent().addClass('has-error');
    $('.error_validation_chgpassword_user_password').removeClass('hidden');
    $('.chgpassword_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1106)
  {
    $('.chgpassword_user_password').parent().parent().addClass('has-error');
    $('.error_validation_chgpassword_user_password').removeClass('hidden');
    $('.chgpassword_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1107)
  {
    $('.chgpassword_user_password').parent().parent().addClass('has-error');
    $('.error_validation_chgpassword_user_password').removeClass('hidden');
    $('.chgpassword_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1108)
  {
    $('.chgpassword_user_repassword').parent().parent().addClass('has-error');
    $('.error_validation_chgpassword_user_repassword').removeClass('hidden');
    $('.chgpassword_user_repassword_validation_error').text(error_message);
    return;
  }
  if (error_number === 1128)
  {
    show_fail("chgpassword_data_alert", error_message);
    return;
  }
  if (error_number === 1129)
  {
    show_fail("chgpassword_data_alert", error_message);
    return;
  }
  if (USE_CAPTHAS_IN_FORMS)
  {
    if (error_number === 1801)
    {
      $('.chgpassword_div_captcha').parent().parent().addClass('has-error');
      $('.error_validation_chgpassword_captcha').removeClass('hidden');
      $('.chgpassword_captcha_validation_error').text(error_message);
      return;
    }
  }
}

function validate_chgpassword(data)
{
  if (data.user_password.length < USER_PASSWORD_MIN_SIZE)
  {
    $('.chgpassword_user_password').parent().parent().addClass('has-error');
    $('.error_validation_chgpassword_user_password').removeClass('hidden');
    $('.chgpassword_user_password_validation_error').text(lang('p_password_too_short'));
    return true;
  }
  if (data.user_password.length > USER_PASSWORD_MAX_SIZE)
  {
    $('.chgpassword_user_password').parent().parent().addClass('has-error');
    $('.error_validation_chgpassword_user_password').removeClass('hidden');
    $('.chgpassword_user_password_validation_error').text(lang('p_password_too_long'));
    return true;
  }
  if (!data.user_password.match(/[A-Za-z0-9_\-.#@%&áéíóúÁÉÍÓÚüÜñÑ]{1,255}/))
  {
    $('.chgpassword_user_password').parent().parent().addClass('has-error');
    $('.error_validation_chgpassword_user_password').removeClass('hidden');
    $('.chgpassword_user_password_validation_error').text(lang('p_password_not_valid'));
    return true;
  }
  if (data.user_re_password !== data.user_password)
  {
    $('.chgpassword_user_repassword').parent().parent().addClass('has-error');
    $('.error_validation_chgpassword_user_repassword').removeClass('hidden');
    $('.chgpassword_user_repassword_validation_error').text(lang('p_password_repassword_not_equals'));
    return true;
  }

  return false;
}