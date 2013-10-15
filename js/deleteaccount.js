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

  $('.deleteaccount_delete_button').on("click", function()
  {
    deleteaccount_clean_form();

    var data_post = {};
    data_post.user_password = $('.deleteaccount_user_password').val();

    if (USE_CAPTHAS_IN_FORMS)
    {
      data_post.captcha_word = $('.captcha_input').val();
    }

    var error_occurred = validate_deleteaccount(data_post);
    if (!error_occurred)
    {
      var req = {};
      req.url = '/user/delete/'+user_id;
      req.type = 'POST';
      req.success = "success_delete_account";
      req.error = "error_delete_account";
      req.data = data_post;
      ajaxp(req);
    }
    else
    {
      show_fail("deleteaccount_data_alert", lang('p_not_deleteaccount_by_error'));
    }  
  });

  $('.deleteaccount_press_for_logout').on("click", function()
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
  $('.deleteaccount_image_captcha').html(data.result);
} 

function error_get_new_captcha(data)
{

}

function deleteaccount_clean_form()
{
  $('.deleteaccount_user_password').parent().parent().removeClass('has-error');
  $('.error_validation_deleteaccount_user_password').addClass('hidden');
  $('.deleteaccount_user_password_validation_error').text("");

  if (USE_CAPTHAS_IN_FORMS)
  {
    $('.deleteaccount_div_captcha').parent().parent().removeClass('has-error');
    $('.error_validation_deleteaccount_captcha').addClass('hidden');
    $('.deleteaccount_captcha_validation_error').text("");
  }
}

function success_delete_account(data)
{
  deleteaccount_clean_form();

  show_success("deleteaccount_data_alert", lang('p_account_deleted_correctly'));

  $('.deleteaccount_div_logout').parent().parent().removeClass('hidden');
  $('.deleteaccount_button_enter').parent().parent().addClass('hidden');
  $('.deleteaccount_user_password').parent().parent().addClass('hidden');
  $('.deleteaccount_user_password').parent().parent().addClass('hidden');
  $('.info_zone').parent().parent().addClass('hidden');

  if (USE_CAPTHAS_IN_FORMS)
  {
    $('.deleteaccount_div_captcha').parent().parent().addClass('hidden');
    $('.deleteaccount_reload_captcha_zone').parent().parent().addClass('hidden');
    $('.deleteaccount_image_captcha').parent().parent().addClass('hidden');
  }
}

function error_delete_account(data)
{
  var error_number = data.status;
  var error_message = data.error.message;

  if (error_number === 1105)
  {
    $('.deleteaccount_user_password').parent().parent().addClass('has-error');
    $('.error_validation_deleteaccount_user_password').removeClass('hidden');
    $('.deleteaccount_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1106)
  {
    $('.deleteaccount_user_password').parent().parent().addClass('has-error');
    $('.error_validation_deleteaccount_user_password').removeClass('hidden');
    $('.deleteaccount_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1107)
  {
    $('.deleteaccount_user_password').parent().parent().addClass('has-error');
    $('.error_validation_deleteaccount_user_password').removeClass('hidden');
    $('.deleteaccount_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1129)
  {
    show_fail("deleteaccount_data_alert", data.error.message);
    return;
  }
  if (USE_CAPTHAS_IN_FORMS)
  {
    if (error_number === 1801)
    {
      $('.deleteaccount_div_captcha').parent().parent().addClass('has-error');
      $('.error_validation_deleteaccount_captcha').removeClass('hidden');
      $('.deleteaccount_captcha_validation_error').text(error_message);
      return;
    }
  }
}

function validate_deleteaccount(data)
{
  if (data.user_password.length < USER_PASSWORD_MIN_SIZE)
  {
    $('.deleteaccount_user_password').parent().parent().addClass('has-error');
    $('.error_validation_deleteaccount_user_password').removeClass('hidden');
    $('.deleteaccount_user_password_validation_error').text(lang('p_password_too_short'));
    return true;
  }
  if (data.user_password.length > USER_PASSWORD_MAX_SIZE)
  {
    $('.deleteaccount_user_password').parent().parent().addClass('has-error');
    $('.error_validation_deleteaccount_user_password').removeClass('hidden');
    $('.deleteaccount_user_password_validation_error').text(lang('p_password_too_long'));
    return true;
  }
  if (!data.user_password.match(/[A-Za-z0-9_\-.#@%&áéíóúÁÉÍÓÚüÜñÑ]{1,255}/))
  {
    $('.deleteaccount_user_password').parent().parent().addClass('has-error');
    $('.error_validation_deleteaccount_user_password').removeClass('hidden');
    $('.deleteaccount_user_password_validation_error').text(lang('p_password_not_valid'));
    return true;
  }

  return false;
}