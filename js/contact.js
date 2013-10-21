$(document).ready(function() 
{
  $('form[role=form] input[type=text]').keyup(function(event)
  {
    if (event.which == 13) // Enter Key
    {
      $('form[role=form] .btn-form-submit').trigger( "click" );
    }
  });
  
	var get_countries = function()
  {
    var req = {};
    req.url = '/country/all';
    req.type = 'GET';
    req.success = "success_get_countries";
    req.error = "error_get_countries";
    ajaxp(req);
  }

  $('.image_captcha_refresh').on("click", function()
  {
    image_captcha_refresh();
    $('.captcha_input').val('');
  });

	$('.contact_button_enter_button').on("click", function()
  {
    contact_clean_form();

    var data_post = {};
    data_post.user_name = clean_field($('.contact_user_name').val());
    data_post.user_email = clean_field($('.contact_user_email').val());
    data_post.user_phone = clean_field($('.contact_user_phone').val());
    data_post.user_comment = clean_field($('.contact_comment').val());
    data_post.user_country_id = $('.contact_select_country').val();
    data_post.filter_t = $('input[name=filter_t]').val();

    data_post.captcha_word = $('.captcha_input').val();

    var error_occurred = validate_contact(data_post);
    if (!error_occurred)
    {
      var req = {};
      req.url = '/contact/send';
      req.type = 'POST';
      req.success = "success_contact";
      req.error = "error_contact";
      req.data = data_post;
      ajaxp(req);
    }
    else
    {
      show_fail("contact_data_alert", lang('p_contact_not_send_by_error'));
    }
  });

  get_countries();
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
  $('.contact_image_captcha').html(data.result);
} 

function error_get_new_captcha(data) {}

function contact_clean_form()
{
  $('.contact_user_name').parent().parent().removeClass('has-error');
  $('.error_validation_contact_user_name').addClass('hidden');
  $('.contact_user_name_validation_error').text("");

  $('.contact_user_email').parent().parent().removeClass('has-error');
  $('.error_validation_contact_user_email').addClass('hidden');
  $('.contact_user_email_validation_error').text("");

  $('.contact_select_country').parent().parent().removeClass('has-error');
  $('.error_validation_contact_select_country').addClass('hidden');
  $('.contact_select_country_validation_error').text("");

  $('.contact_comment').parent().parent().removeClass('has-error');
  $('.error_validation_contact_comment').addClass('hidden');
  $('.contact_comment_validation_error').text("");

  $('.contact_div_captcha').parent().parent().removeClass('has-error');
  $('.error_validation_contact_captcha').addClass('hidden');
  $('.contact_captcha_validation_error').text("");
}

function validate_contact(data)
{
  if (data.user_name.length < USER_NAME_MIN_SIZE)
  {
    $('.contact_user_name').parent().parent().addClass('has-error');
    $('.error_validation_contact_user_name').removeClass('hidden');
    $('.contact_user_name_validation_error').text(lang('p_name_too_short'));
    return true;
  }
  if (data.user_name.length > USER_NAME_MAX_SIZE)
  {
    $('.contact_user_name').parent().parent().addClass('has-error');
    $('.error_validation_contact_user_name').removeClass('hidden');
    $('.contact_user_name_validation_error').text(lang('p_name_too_long'));
    return true;
  }
  if (!data.user_email.match(/[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/))
  {
    $('.contact_user_email').parent().parent().addClass('has-error');
    $('.error_validation_contact_user_email').removeClass('hidden');
    $('.contact_user_email_validation_error').text(lang('p_email_not_valid'));
    return true;
  }
  if (!data.user_country_id.match(/^-{0,1}\d*\.{0,1}\d+$/) || (data.user_country_id < VALIDATE_ID_MIN_VALUE) || (typeof data.user_country_id === "undefined"))
  {
    $('.contact_select_country').parent().parent().addClass('has-error');
    $('.error_validation_contact_select_country').removeClass('hidden');
    $('.contact_select_country_validation_error').text(lang('p_country_not_valid'));
    return true;
  }
  if (data.user_comment.length < USER_CONTACT_MESSAGE_MIN_SIZE)
  {
    $('.contact_comment').parent().parent().addClass('has-error');
    $('.error_validation_contact_comment').removeClass('hidden');
    $('.contact_comment_validation_error').text(lang('p_comment_too_short'));
    return true;
  }
  if (!data.captcha_word.match(/[A-Za-z0-9]{1,255}/))
  {
    $('.contact_div_captcha').parent().parent().addClass('has-error');
    $('.error_validation_contact_captcha').removeClass('hidden');
    $('.contact_captcha_validation_error').text(lang('p_invalid_captcha'));
    return true;
  }

  return false;
}

function success_contact(data)
{
  contact_clean_form();
  contact_clean_data_form();
  image_captcha_refresh();
  show_message_sended();
}

function error_contact(data)
{
  var error_number = data.status;
  var error_message = data.error.message;

  if (error_number === 1101)
  {
    $('.contact_user_name').parent().parent().addClass('has-error');
    $('.error_validation_contact_user_name').removeClass('hidden');
    $('.contact_user_name_validation_error').text(error_message);
    return;
  }
  if (error_number === 1102)
  {
    $('.contact_user_name').parent().parent().addClass('has-error');
    $('.error_validation_contact_user_name').removeClass('hidden');
    $('.contact_user_name_validation_error').text(error_message);
    return;
  }
  if (error_number === 1104)
  {
    $('.contact_user_email').parent().parent().addClass('has-error');
    $('.error_validation_contact_user_email').removeClass('hidden');
    $('.contact_user_email_validation_error').text(error_message);
    return;
  }
  if (error_number === 1112)
  {
    $('.contact_select_country').parent().parent().addClass('has-error');
    $('.error_validation_contact_select_country').removeClass('hidden');
    $('.contact_select_country_validation_error').text(error_message);
    return;
  }
  if (error_number === 1135)
  {
    show_fail("contact_data_alert", error_message);
    return;
  }
  if (error_number === 1136)
  {
    $('.contact_comment').parent().parent().addClass('has-error');
    $('.error_validation_contact_comment').removeClass('hidden');
    $('.contact_comment_validation_error').text(error_message);
    return;
  }    
  if (error_number === 1801)
  {
    $('.contact_div_captcha').parent().parent().addClass('has-error');
    $('.error_validation_contact_captcha').removeClass('hidden');
    $('.contact_captcha_validation_error').text(error_message);
    return;
  }
}

function success_get_countries(data)
{
  $.each(data.result, function (country_id) {
    var country = data.result[country_id];
    $('.contact_select_country').append($('<option></option>').attr("value", country.id).text(country.name));
  });
  $('.contact_select_country').prop('disabled', false);
}

function error_get_countries(data)
{
  show_fail("contact_data_alert", lang('p_error_ocurred_by_page_charge'), true);
  $('.contact_select_country').prop('disabled', true);
}

function contact_clean_data_form()
{
	$('.captcha_input').val('');
  $('.contact_comment').val('');
}

function show_message_sended()
{
  show_success("contact_data_alert", lang('p_message_sended_correctly'));
}