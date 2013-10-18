$(document).ready(function() 
{
  $('form[role=form]').keyup(function(event)
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

  $('.register_button_enter_button').on("click", function()
  {
    register_clean_form();

    var data_post = {};
    data_post.user_name = clean_field($('.register_user_name').val());
    data_post.user_email = clean_field($('.register_user_email').val());
    data_post.user_password = clean_field($('.register_user_password').val());
    data_post.user_re_password = clean_field($('.register_user_repassword').val());
    data_post.user_birthdate = get_birthdate_from_form();
    data_post.user_sex = $('.register_radio_gender').val();
    data_post.user_country_id = $('.register_select_country').val();
    data_post.user_state_id = $('.register_select_state').val();
    data_post.filter_t = $('input[name=filter_t]').val();

    var error_occurred = validate_register(data_post);
    if (!error_occurred)
    {
      var req = {};
      req.url = '/user/register';
      req.type = 'POST';
      req.success = "success_register";
      req.error = "error_register";
      req.data = data_post;
      ajaxp(req);
    }
    else
    {
      show_fail("register_data_alert", lang('p_not_register_by_error'));
    }
  });

  $('.register_select_country').on("change", function()
  {
    reset_state_select();

    var country_id = $(this).val();
    if (typeof country_id !== "undefined" && country_id !== null && country_id !== '')
    {
      var req = {};
      req.url = '/state/all_from_country/'+country_id;
      req.type = 'GET';
      req.success = "success_get_states";
      req.error = "error_get_states";
      ajaxp(req);
    }
  });

  get_countries();

});

function get_birthdate_from_form()
{
  return $('.register_select_year').val() + "-" + $('.register_select_month').val() + "-" + $('.register_select_day').val();
}

function register_clean_form()
{
  $('.register_user_name').parent().parent().removeClass('has-error');
  $('.error_validation_register_user_name').addClass('hidden');
  $('.register_user_name_validation_error').text("");

  $('.register_user_email').parent().parent().removeClass('has-error');
  $('.error_validation_register_user_email').addClass('hidden');
  $('.register_user_email_validation_error').text("");

  $('.register_user_password').parent().parent().removeClass('has-error');
  $('.error_validation_register_user_password').addClass('hidden');
  $('.register_user_password_validation_error').text("");

  $('.register_user_repassword').parent().parent().removeClass('has-error');
  $('.error_validation_register_user_repassword').addClass('hidden');
  $('.register_user_repassword_validation_error').text("");

  $('.register_select_year').parent().parent().removeClass('has-error');
  $('.error_validation_register_select_year').addClass('hidden');
  $('.register_select_year_validation_error').text("");

  $('.register_select_region').parent().parent().removeClass('has-error');
  $('.error_validation_register_select_region').addClass('hidden');
  $('.register_select_region_validation_error').text("");

  $('.register_radio_gender').parent().parent().removeClass('has-error');
  $('.error_validation_register_radio_gender').addClass('hidden');
  $('.register_radio_gender_validation_error').text("");
}

function validate_register(data)
{
  if (data.user_name.length < USER_NAME_MIN_SIZE)
  {
    $('.register_user_name').parent().parent().addClass('has-error');
    $('.error_validation_register_user_name').removeClass('hidden');
    $('.register_user_name_validation_error').text(lang('p_name_too_short'));
    return true;
  }
  if (data.user_name.length > USER_NAME_MAX_SIZE)
  {
    $('.register_user_name').parent().parent().addClass('has-error');
    $('.error_validation_register_user_name').removeClass('hidden');
    $('.register_user_name_validation_error').text(lang('p_name_too_long'));
    return true;
  }
  if (!data.user_name.match(/[A-Za-z0-9_\-. áéíóúÁÉÍÓÚüÜñÑ]{1,255}/))
  {
    $('.register_user_name').parent().parent().addClass('has-error');
    $('.error_validation_register_user_name').removeClass('hidden');
    $('.register_user_name_validation_error').text(lang('p_name_not_valid'));
    return true;
  }
  if (!data.user_email.match(/[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/))
  {
    $('.register_user_email').parent().parent().addClass('has-error');
    $('.error_validation_register_user_email').removeClass('hidden');
    $('.register_user_email_validation_error').text(lang('p_email_not_valid'));
    return true;
  }
  if (data.user_password.length < USER_PASSWORD_MIN_SIZE)
  {
    $('.register_user_password').parent().parent().addClass('has-error');
    $('.error_validation_register_user_password').removeClass('hidden');
    $('.register_user_password_validation_error').text(lang('p_password_too_short'));
    return true;
  }
  if (data.user_password.length > USER_PASSWORD_MAX_SIZE)
  {
    $('.register_user_password').parent().parent().addClass('has-error');
    $('.error_validation_register_user_password').removeClass('hidden');
    $('.register_user_password_validation_error').text(lang('p_password_too_long'));
    return true;
  }
  if (!data.user_password.match(/[A-Za-z0-9_\-.#@%&áéíóúÁÉÍÓÚüÜñÑ]{1,255}/))
  {
    $('.register_user_password').parent().parent().addClass('has-error');
    $('.error_validation_register_user_password').removeClass('hidden');
    $('.register_user_password_validation_error').text(lang('p_password_not_valid'));
    return true;
  }
  if (data.user_re_password !== data.user_password)
  {
    $('.register_user_repassword').parent().parent().addClass('has-error');
    $('.error_validation_register_user_repassword').removeClass('hidden');
    $('.register_user_repassword_validation_error').text(lang('p_password_repassword_not_equals'));
    return true;
  }
  if (!data.user_birthdate.match(/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/))
  {
    $('.register_select_year').parent().parent().addClass('has-error');
    $('.error_validation_register_select_year').removeClass('hidden');
    $('.register_select_year_validation_error').text(lang('p_birthdate_not_valid'));
    return true;
  }  
  if (!have_18_years_old(data.user_birthdate))
  {
    $('.register_select_year').parent().parent().addClass('has-error');
    $('.error_validation_register_select_year').removeClass('hidden');
    $('.register_select_year_validation_error').text(lang('p_must_have_18_years'));
    return true;
  }
  if (!data.user_state_id.match(/^-{0,1}\d*\.{0,1}\d+$/) || (data.user_state_id < VALIDATE_ID_MIN_VALUE) || (typeof data.user_state_id === "undefined"))
  {
    $('.register_select_region').parent().parent().addClass('has-error');
    $('.error_validation_register_select_region').removeClass('hidden');
    $('.register_select_region_validation_error').text(lang('p_state_not_valid'));
    return true;
  }
  if (!data.user_country_id.match(/^-{0,1}\d*\.{0,1}\d+$/) || (data.user_country_id < VALIDATE_ID_MIN_VALUE) || (typeof data.user_country_id === "undefined"))
  {
    $('.register_select_region').parent().parent().addClass('has-error');
    $('.error_validation_register_select_region').removeClass('hidden');
    $('.register_select_region_validation_error').text(lang('p_country_not_valid'));
    return true;
  }
  if (data.user_sex !== 'M' && data.user_sex !== 'F')
  {
    $('.register_radio_gender').parent().parent().addClass('has-error');
    $('.error_validation_register_radio_gender').removeClass('hidden');
    $('.register_radio_gender_validation_error').text(lang('p_gender_not_valid'));
    return true;
  }

  return false;
}

function have_18_years_old(mdate)
{
  var mdate_arr = mdate.split("-");
  var birthDate = new Date(mdate_arr[0],mdate_arr[1],mdate_arr[2]);
  var today = new Date();
  return (today >= new Date(birthDate.getFullYear() + 18, birthDate.getMonth(), birthDate.getDate())) 
}

function success_register(data)
{
  register_clean_form();
  window.location = baseurl + "/" + m_lang + "/activateaccount";
}

function error_register(data)
{
  var error_number = data.status;
  var error_message = data.error.message;

  show_fail("register_data_alert", lang('p_not_register_by_error'));

  if (error_number === 1101)
  {
    $('.register_user_name').parent().parent().addClass('has-error');
    $('.error_validation_register_user_name').removeClass('hidden');
    $('.register_user_name_validation_error').text(error_message);
    return;
  }
  if (error_number === 1102)
  {
    $('.register_user_name').parent().parent().addClass('has-error');
    $('.error_validation_register_user_name').removeClass('hidden');
    $('.register_user_name_validation_error').text(error_message);
    return;
  }
  if (error_number === 1103)
  {
    $('.register_user_name').parent().parent().addClass('has-error');
    $('.error_validation_register_user_name').removeClass('hidden');
    $('.register_user_name_validation_error').text(error_message);
    return;
  }
  if (error_number === 1104)
  {
    $('.register_user_email').parent().parent().addClass('has-error');
    $('.error_validation_register_user_email').removeClass('hidden');
    $('.register_user_email_validation_error').text(error_message);
    return;
  }
  if (error_number === 1105)
  {
    $('.register_user_password').parent().parent().addClass('has-error');
    $('.error_validation_register_user_password').removeClass('hidden');
    $('.register_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1106)
  {
    $('.register_user_password').parent().parent().addClass('has-error');
    $('.error_validation_register_user_password').removeClass('hidden');
    $('.register_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1107)
  {
    $('.register_user_password').parent().parent().addClass('has-error');
    $('.error_validation_register_user_password').removeClass('hidden');
    $('.register_user_password_validation_error').text(error_message);
    return;
  }
  if (error_number === 1108)
  {
    $('.register_user_repassword').parent().parent().addClass('has-error');
    $('.error_validation_register_user_repassword').removeClass('hidden');
    $('.register_user_repassword_validation_error').text(error_message);
    return;
  }
  if (error_number === 1109)
  {
    $('.register_select_year').parent().parent().addClass('has-error');
    $('.error_validation_register_select_year').removeClass('hidden');
    $('.register_select_year_validation_error').text(error_message);
    return;
  }
  if (error_number === 1110)
  {
    $('.register_select_year').parent().parent().addClass('has-error');
    $('.error_validation_register_select_year').removeClass('hidden');
    $('.register_select_year_validation_error').text(error_message);
    return;
  }
  if (error_number === 1111)
  {
    $('.register_radio_gender').parent().parent().addClass('has-error');
    $('.error_validation_register_radio_gender').removeClass('hidden');
    $('.register_radio_gender_validation_error').text(error_message);
    return;
  }
  if (error_number === 1112)
  {
    $('.register_select_region').parent().parent().addClass('has-error');
    $('.error_validation_register_select_region').removeClass('hidden');
    $('.register_select_region_validation_error').text(error_message);
    return;
  }
  if (error_number === 1113)
  {
    $('.register_select_region').parent().parent().addClass('has-error');
    $('.error_validation_register_select_region').removeClass('hidden');
    $('.register_select_region_validation_error').text(error_message);
    return;
  }
  if (error_number === 1123)
  {
    $('.register_user_email').parent().parent().addClass('has-error');
    $('.error_validation_register_user_email').removeClass('hidden');
    $('.register_user_email_validation_error').text(error_message);
    return;
  }
  if (error_number === 1124)
  {
    show_fail("register_data_alert", error_message);
    return;
  }
}

function success_get_countries(data)
{
  $.each(data.result, function (country_id) {
    var country = data.result[country_id];
    $('.register_select_country').append($('<option></option>').attr("value", country.id).text(country.name));
  });
  $('.register_select_country').prop('disabled', false);
}

function error_get_countries(data)
{
  $('.register_select_country').prop('disabled', true);
}

function success_get_states(data)
{
  $.each(data.result, function (state_id) {
    var state = data.result[state_id];
    $('.register_select_state').append($('<option></option>').attr("value", state.id).text(state.name));
  });
  $('.register_select_state').prop('disabled', false);
}

function error_get_states(data)
{
  $('.register_select_state').prop('disabled', true);
}

function reset_country_select()
{
  $('.register_select_country').val('');
  $(".register_select_country option").not(":eq(0)").remove(); 
  $('.register_select_country').prop("disabled",true);
}

function reset_state_select()
{
  $('.register_select_state').val('');
  $(".register_select_state option").not(":eq(0)").remove(); 
  $('.register_select_state').prop("disabled",true);
}