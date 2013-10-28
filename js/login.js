$(document).ready(function() 
{
  $('form[role=form]').keyup(function(event)
  {
    if (event.which == 13) // Enter Key
    {
      $('form[role=form] .btn-form-submit').trigger( "click" );
    }
  });

  $('.login_button_enter_button').on("click", function()
  {
    login_clean_form();

    var data_post = {};
    data_post.user_email = clean_field($('.login_user_email').val());
    data_post.user_password = clean_field($('.login_user_password').val());
    data_post.user_remember = $('.login_user_remember').is(':checked');
    data_post.filter_t = $('input[name=filter_t]').val();

    var error_occurred = validate_login(data_post);
    if (!error_occurred)
    {
      var req = {};
      req.url = '/user/login';
      req.type = 'POST';
      req.success = "success_login";
      req.error = "error_login";
      req.data = data_post;
      ajaxp(req);
    }
    else
    {
      show_fail("login_data_alert", lang('p_not_logged_by_error'));
    }
  });
});

function login_clean_form()
{
  $('.login_user_email').parent().parent().removeClass('has-error');
  $('.error_validation_login_user_email').addClass('hidden');
  $('.login_user_email_validation_error').text("");

  $('.login_user_password').parent().parent().removeClass('has-error');
  $('.error_validation_login_user_password').addClass('hidden');
  $('.login_user_password_validation_error').text("");
}

function validate_login(data)
{
  if (!data.user_email.match(/[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/))
  {
    $('.login_user_email').parent().parent().addClass('has-error');
    $('.error_validation_login_user_email').removeClass('hidden');
    $('.login_user_email_validation_error').text(lang('p_email_not_valid'));
    return true;
  }

  return false;
}

function success_login(data)
{
  login_clean_form();
  window.location = baseurl + "/" + m_lang + "/prop";
}

function error_login(data)
{
  var error_number = data.status;
  var error_message = data.error.message;

  if (error_number === 1104)
  {
    $('.login_user_email').parent().parent().addClass('has-error');
    $('.error_validation_login_user_email').removeClass('hidden');
    $('.login_user_email_validation_error').text(error_message);
    show_fail("login_data_alert", lang('p_not_logged_by_error'));
    return;
  }
  if (error_number === 1130)
  {
    show_fail("login_data_alert", error_message);
    return;
  }
  if (error_number === 1137)
  {
    // BANNED
    show_fail("login_data_alert", lang('p_user_banned_reason') + error_message);
    return;
  }
}