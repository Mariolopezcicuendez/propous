var data_user = null;
var user_id = null;
var initial_load = true;

$(document).ready(function() 
{
	user_id = $('.gdata_user_id').val();

  var get_user_data = function()
  {
  	if (typeof user_id !== "undefined" && user_id !== null && user_id !== '')
    {
	    var req = {};
	    req.url = '/user/get/'+user_id;
	    req.type = 'GET';
	    req.success = "success_get_user_data";
	    req.error = "error_get_user_data";
	    ajaxp(req);
	  }
  }

  $('.editprofile_change_password_link').on("click", function()
  {
    window.location = baseurl + "/"+ m_lang +"/chgpassword";
  });

  $('.editprofile_delete_account_link').on("click", function()
  {
    window.location = baseurl + "/"+ m_lang +"/deleteaccount";
  });

  $('.editprofile_save_profile_link').on("click", function()
  {
    editprofile_clean_form();

    var data_post = {};
    data_post.user_name = clean_field($('.editprofile_user_name').val());
    data_post.user_birthdate = get_birthdate_from_form();
    data_post.user_sex = $('.editprofile_radio_gender').val();
    data_post.user_country_id = $('.editprofile_select_country').val();
    data_post.user_state_id = $('.editprofile_select_state').val();
    
    data_post.user_nationality = clean_field($('.editprofile_nationality').val());
    data_post.user_dwelling = clean_field($('.editprofile_dwelling').val());
    data_post.user_car = clean_field($('.editprofile_car').val());
    data_post.user_sexuality = clean_field($('.editprofile_sexuality').val());
    data_post.user_partner = clean_field($('.editprofile_partner').val());
    data_post.user_children = clean_field($('.editprofile_children').val());
    data_post.user_occupation = clean_field($('.editprofile_occupation').val());
    data_post.user_hobbies = clean_field($('.editprofile_hobbies').val());
    data_post.user_phone = clean_field($('.editprofile_phone').val());
    data_post.user_description = clean_field($('.editprofile_description').val());
    data_post.filter_t = $('input[name=filter_t]').val();

    var error_occurred = validate_editprofile(data_post);
    if (!error_occurred)
    {
      var req = {};
      req.url = '/user/save/'+user_id;
      req.type = 'POST';
      req.success = "success_save_profile";
      req.error = "error_save_profile";
      req.data = data_post;
      ajaxp(req);
    }
    else
    {
      show_fail("editprofile_data_alert", lang('p_profile_not_saved_by_error'));
    }
  });

  $('.editprofile_select_country').on("change", function()
  {
    reset_state_select();

    var country_id = $(this).val();
    if (typeof country_id !== "undefined" && country_id !== null && country_id !== '')
    {
      var req = {};
      req.url = '/state/all_from_country/'+country_id;
      req.type = 'GET';
      req.success = "success_get_states_clean";
      req.error = "error_get_states_clean";
      ajaxp(req);
    }
  });

  $(".editprofile_select_spoken").on("change", function()
  {
    $(".editprofile_link_delete_language_selected").prop("disabled",false);
  });

  $(".editprofile_select_spoken_add").on("change", function()
  {
    if ($(".editprofile_select_spoken_add option:selected").val() !== '')
    {
       $(".editprofile_link_add_language").prop("disabled",false);
    }
    else
    {
       $(".editprofile_link_add_language").prop("disabled",true);
    }
  });

  $(".editprofile_link_add_language").on("click", function(submitEvent)
  {
    var lang_to_add = $(".editprofile_select_spoken_add").val();
    var level = $(".editprofile_select_spoken_level").val();

    if (typeof lang_to_add !== "undefined" && lang_to_add !== null && lang_to_add !== '')
    {
      var data_post = {};
      data_post.user_spoken_language = lang_to_add+":"+level;
      data_post.filter_t = $('input[name=filter_t]').val();

      var req = {};
      req.url = '/language/save_spoken/'+user_id;
      req.type = 'POST';
      req.success = "success_save_spoken";
      req.error = "error_save_spoken";
      req.data = data_post;
      ajaxp(req);
    }
  });

  $(".editprofile_link_delete_language_selected").on("click", function(submitEvent)
  {
    var id_lang_to_delete = $(".editprofile_select_spoken option:selected").val();

    var req = {};
    req.url = '/language/delete_spoken/'+id_lang_to_delete;
    req.type = 'GET';
    req.success = "success_delete_spoken";
    req.error = "error_delete_spoken";
    ajaxp(req);
  });

  $('.editprofile_select_day').on("change", function()
  {
    $('.editprofile_age_data').text(calculate_age_from_birthdate());
  });

  $('.editprofile_select_month').on("change", function()
  {
    $('.editprofile_age_data').text(calculate_age_from_birthdate());
  });

  $('.editprofile_select_year').on("change", function()
  {
    $('.editprofile_age_data').text(calculate_age_from_birthdate());
  });

  $('.editprofile_upload_photo_button').on("click", function(submitEvent)
  {
    var filename = $(".content_editprofile_photos_right input[name=upload]").val();

    if (filename !== '')
    {
		  var extension = filename.replace(/^.*\./, '');
		  extension = (extension == filename) ? '' : extension.toLowerCase() ;
		  
		  switch (extension) 
		  {
		    case 'jpg':
		    case 'jpeg':
		    case 'gif':
		    case 'png':
		      send_photo();  
		    break;  
		    default:
		      submitEvent.preventDefault();
          show_fail("editprofile_photo_alert", lang('p_photo_must_be_image'));
		    break;  
		  }
    }
  });

  $('.editprofile_sociality_select_add_button').on("click", function(submitEvent)
  {
    var sociality = $('.editprofile_sociality_select').val();
    
    var data_post = {};
    data_post.user_sociality_id = sociality;
    data_post.filter_t = $('input[name=filter_t]').val();

    if (typeof sociality !== "undefined" && sociality !== null && sociality !== '')
    {
      var req = {};
      req.url = '/sociality/save_social/'+user_id;
      req.type = 'POST';
      req.success = "success_save_social";
      req.error = "error_save_social";
      req.data = data_post;
      ajaxp(req);
    }
  });

  editprofile_clean_form();
  disable_editprofile_form();
	get_user_data();
  get_user_spoken_languages();
  get_all_spoken_languages();
  get_main_photo();
	get_user_photos();
	get_user_sociality();
  get_all_socialities_for_add();
  create_info_popover();
  $(":file").filestyle({icon: false});
});

function create_info_popover()
{
  var options_popover = {};
  options_popover.html = true;
  options_popover.content = lang('p_sociality_for_what');
  options_popover.placement = 'right';
  options_popover.container = 'body';
  set_popover('.img_info',options_popover);
}

function send_photo()
{
	var req = {};
  req.url = '/photo/add_for_user/'+user_id;
  req.type = 'POST';
  req.success = "success_upload_photo";
  req.error = "error_upload_photo";
  req.data = new FormData($('.editprofile_upload_photo_form')[0]);
  req.cache = false;
  req.contentType = false;
  req.processData = false;
  ajaxp(req);
}

function get_birthdate_from_form()
{
  return $('.editprofile_select_year').val() + "-" + $('.editprofile_select_month').val() + "-" + $('.editprofile_select_day').val();
}

function get_countries()
{
  var req = {};
  req.url = '/country/all';
  req.type = 'GET';
  req.success = "success_get_countries";
  req.error = "error_get_countries";
  ajaxp(req);
}

function success_get_user_data(data)
{
	data_user = data.result;

	get_countries();
}

function put_user_data_in_form()
{
	var birthdate = data_user.birthdate;
	var birthdate_year = birthdate.substring(0, 4);
	var birthdate_month = birthdate.substring(5, 7);
	var birthdate_day = birthdate.substring(8, 10);

	$('.editprofile_user_name').val(data_user.name);

	$('.editprofile_select_day').val(birthdate_day);
	$('.editprofile_select_month').val(birthdate_month);
	$('.editprofile_select_year').val(birthdate_year);

	$('.editprofile_age_data').text(calculate_age_from_birthdate());

	var user_sex_male = (data_user.sex === 'M') ? true : false ;
	var user_sex_female = (data_user.sex === 'M') ? false : true ;
	$('input:radio[name=editprofile_radio_gender]:nth(0)').attr('checked',user_sex_male);
	$('input:radio[name=editprofile_radio_gender]:nth(1)').attr('checked',user_sex_female);

	$('.editprofile_nationality').val(data_user.nationality);
	$('.editprofile_dwelling').val(data_user.dwelling);
	$('.editprofile_car').val(data_user.car);
	$('.editprofile_sexuality').val(data_user.sexuality);
	$('.editprofile_partner').val(data_user.partner);
	$('.editprofile_children').val(data_user.childrens);
	$('.editprofile_occupation').val(data_user.occupation);
	$('.editprofile_hobbies').val(data_user.hobbies);
	$('.editprofile_phone').val(data_user.phone);
	$('.editprofile_description').val(data_user.description);

  $('.content_editprofile_data_right').removeClass('hidden');
}

function error_get_user_data()
{
  show_fail("editprofile_data_alert", lang('p_user_data_not_load'), true);
}

function success_get_countries(data)
{
  var country_id_data = data_user['country_id'];

  $.each(data.result, function (country_id) {
    var country = data.result[country_id];
    $('.editprofile_select_country').append($('<option></option>'));
    var option = $('.editprofile_select_country option:last').attr("value", country.id).text(country.name);
    if (parseInt(country_id_data) === parseInt(country.id))
    {
    	option.attr("selected", true);
    }
  });
  $('.editprofile_select_country').prop('disabled', false);
  
  if (typeof country_id_data !== "undefined" && country_id_data !== null && country_id_data !== '')
  {
    var req = {};
    req.url = '/state/all_from_country/'+country_id_data;
    req.type = 'GET';
    req.success = "success_get_states";
    req.error = "error_get_states";
    ajaxp(req);
  }
}

function error_get_countries(data)
{
  show_fail("editprofile_data_alert", lang('p_error_ocurred_by_page_charge'), true);
  $('.editprofile_select_country').prop('disabled', true);
}

function success_get_states(data)
{
	var state_id_data = data_user['state_id'];

  $.each(data.result, function (state_id) {
    var state = data.result[state_id];
    $('.editprofile_select_state').append($('<option></option>'));
    var option = $('.editprofile_select_state option:last').attr("value", state.id).text(state.name);
	  if (parseInt(state_id_data) === parseInt(state.id))
    {
    	option.attr("selected", true);
    }
  });
  $('.editprofile_select_state').prop('disabled', false);

  if (initial_load)
  {
    put_user_data_in_form();
    enable_editprofile_form();
    initial_load = false;
  }
}

function success_get_states_clean(data)
{
	var state_id_data = data_user['state_id'];

  $.each(data.result, function (state_id) {
    var state = data.result[state_id];
    $('.editprofile_select_state').append($('<option></option>').attr("value", state.id).text(state.name));
  });
  $('.editprofile_select_state').prop('disabled', false);
}

function error_get_states(data)
{
  show_fail("editprofile_data_alert", lang('p_error_ocurred_by_page_charge'), true);
  $('.editprofile_select_state').prop('disabled', true);
}

function error_get_states_clean(data)
{
  show_fail("editprofile_data_alert", lang('p_error_ocurred_by_page_charge'), true);
  $('.editprofile_select_state').prop('disabled', true);
}

function reset_country_select()
{
  $('.editprofile_select_country').val('');
  $(".editprofile_select_country option").not(":eq(0)").remove(); 
  $('.editprofile_select_country').prop("disabled",true);
}

function reset_state_select()
{
  $('.editprofile_select_state').val('');
  $(".editprofile_select_state option").not(":eq(0)").remove(); 
  $('.editprofile_select_state').prop("disabled",true);
}

function editprofile_clean_form()
{
  $('.editprofile_user_name').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_user_name').addClass('hidden');
	$('.editprofile_user_name_validation_error').text("");
  $('.editprofile_select_year').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_select_year').addClass('hidden');
  $('.editprofile_select_year_validation_error').text("");
  $('.editprofile_select_region').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_select_region').addClass('hidden');
  $('.editprofile_select_region_validation_error').text("");
  $('.editprofile_radio_gender').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_radio_gender').addClass('hidden');
  $('.editprofile_radio_gender_validation_error').text("");
  $('.editprofile_nacionality').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_nacionality').addClass('hidden');
  $('.editprofile_nacionality_validation_error').text("");
  $('.editprofile_dwelling').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_dwelling').addClass('hidden');
  $('.editprofile_dwelling_validation_error').text("");
  $('.editprofile_car').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_car').addClass('hidden');
  $('.editprofile_car_validation_error').text("");
  $('.editprofile_sexuality').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_sexuality').addClass('hidden');
  $('.editprofile_sexuality_validation_error').text("");
  $('.editprofile_partner').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_partner').addClass('hidden');
  $('.editprofile_partner_validation_error').text("");
  $('.editprofile_children').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_children').addClass('hidden');
  $('.editprofile_children_validation_error').text("");
  $('.editprofile_occupation').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_occupation').addClass('hidden');
  $('.editprofile_occupation_validation_error').text("");
  $('.editprofile_hobbies').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_hobbies').addClass('hidden');
  $('.editprofile_hobbies_validation_error').text("");
  $('.editprofile_phone').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_phone').addClass('hidden');
  $('.editprofile_phone_validation_error').text("");
  $('.editprofile_description').parent().parent().removeClass('has-error');
  $('.error_validation_editprofile_description').addClass('hidden');
  $('.editprofile_description_validation_error').text("");
}

function enable_editprofile_form()
{
	$('.editprofile_user_name').prop('disabled',false);
	$('.editprofile_age_data').prop('disabled',false);
	$('.editprofile_select_day').prop('disabled',false);
	$('.editprofile_select_month').prop('disabled',false);
	$('.editprofile_select_year').prop('disabled',false);
	$('.editprofile_radio_gender').prop('disabled',false);
	$('.editprofile_select_country').prop('disabled',false);
	$('.editprofile_select_state').prop('disabled',false);
	$('.editprofile_nacionality').prop('disabled',false);
	$('.editprofile_dwelling').prop('disabled',false);
	$('.editprofile_car').prop('disabled',false);
	$('.editprofile_sexuality').prop('disabled',false);
	$('.editprofile_partner').prop('disabled',false);
	$('.editprofile_children').prop('disabled',false);
	$('.editprofile_occupation').prop('disabled',false);
	$('.editprofile_hobbies').prop('disabled',false);
	$('.editprofile_phone').prop('disabled',false);
	$('.editprofile_description').prop('disabled',false);
}

function disable_editprofile_form()
{
	$('.editprofile_user_name').prop('disabled',true);
	$('.editprofile_age_data').prop('disabled',true);
	$('.editprofile_select_day').prop('disabled',true);
	$('.editprofile_select_month').prop('disabled',true);
	$('.editprofile_select_year').prop('disabled',true);
	$('.editprofile_radio_gender').prop('disabled',true);
	$('.editprofile_select_country').prop('disabled',true);
	$('.editprofile_select_state').prop('disabled',true);
	$('.editprofile_nacionality').prop('disabled',true);
	$('.editprofile_dwelling').prop('disabled',true);
	$('.editprofile_car').prop('disabled',true);
	$('.editprofile_sexuality').prop('disabled',true);
	$('.editprofile_partner').prop('disabled',true);
	$('.editprofile_children').prop('disabled',true);
	$('.editprofile_occupation').prop('disabled',true);
	$('.editprofile_hobbies').prop('disabled',true);
	$('.editprofile_phone').prop('disabled',true);
	$('.editprofile_description').prop('disabled',true);
}

function calculate_age_from_birthdate()
{
	var birthdate_year = $('.editprofile_select_year').val();
	var birthdate_month = $('.editprofile_select_month').val();
	var birthdate_day = $('.editprofile_select_day').val();

  var today = new Date();
  var today_year = today.getYear();
  var today_month = today.getMonth();
  var today_day = today.getDate();
  
  var age = (today_year + 1900) - birthdate_year;
  if (today_month < (birthdate_month - 1)) age--;
  if (((birthdate_month - 1) == today_month) && (today_day < birthdate_day)) age--;
  if (age > 1900) age -= 1900;

  return age;
}

function validate_editprofile(data)
{
  if (data.user_name.length < USER_NAME_MIN_SIZE)
  {
    $('.editprofile_user_name').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_user_name').removeClass('hidden');
    $('.editprofile_user_name_validation_error').text(lang('p_name_too_short'));
    return true;
  }
  if (data.user_name.length > USER_NAME_MAX_SIZE)
  {
    $('.editprofile_user_name').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_user_name').removeClass('hidden');
    $('.editprofile_user_name_validation_error').text(lang('p_name_too_long'));
    return true;
  }
  if (!data.user_name.match(/[A-Za-z0-9_\-. áéíóúÁÉÍÓÚüÜñÑ]{1,255}/))
  {
    $('.editprofile_user_name').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_user_name').removeClass('hidden');
    $('.editprofile_user_name_validation_error').text(lang('p_name_not_valid'));
    return true;
  }
  if (!data.user_birthdate.match(/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/))
  {
    $('.editprofile_select_year').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_select_year').removeClass('hidden');
    $('.editprofile_select_year_validation_error').text(lang('p_birthdate_not_valid'));
    return true;
  }  
  if (!have_18_years_old(data.user_birthdate))
  {
    $('.editprofile_select_year').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_select_year').removeClass('hidden');
    $('.editprofile_select_year_validation_error').text(lang('p_must_have_18_years'));
    return true;
  }
  if (data.user_sex !== 'M' && data.user_sex !== 'F')
  {
    $('.editprofile_radio_gender').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_radio_gender').removeClass('hidden');
    $('.editprofile_radio_gender_validation_error').text(lang('p_gender_not_valid'));
    return true;
  }
  if (!data.user_state_id.match(/^-{0,1}\d*\.{0,1}\d+$/) || (data.user_state_id < 1) || (typeof data.user_state_id === "undefined"))
  {
    $('.editprofile_select_region').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_select_region').removeClass('hidden');
    $('.editprofile_select_region_validation_error').text(lang('p_state_not_valid'));
    return true;
  }
  if (!data.user_country_id.match(/^-{0,1}\d*\.{0,1}\d+$/) || (data.user_country_id < 1) || (typeof data.user_country_id === "undefined"))
  {
    $('.editprofile_select_region').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_select_region').removeClass('hidden');
    $('.editprofile_select_region_validation_error').text(lang('p_country_not_valid'));
    return true;
  }
  if (data.user_nationality.length > USER_NATIONALITY_MAX_SIZE)
  {
    $('.editprofile_nationality').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_nationality').removeClass('hidden');
    $('.editprofile_nationality_validation_error').text(lang('p_nationality_too_long'));
    return true;
  }
  if (data.user_dwelling.length > USER_DWELLING_MAX_SIZE)
  {
    $('.editprofile_dwelling').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_dwelling').removeClass('hidden');
    $('.editprofile_dwelling_validation_error').text(lang('p_dwelling_too_long'));
    return true;
  }
  if (data.user_car.length > USER_CAR_MAX_SIZE)
  {
    $('.editprofile_car').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_car').removeClass('hidden');
    $('.editprofile_car_validation_error').text(lang('p_car_too_long'));
    return true;
  }
  if (data.user_sexuality.length > USER_SEXUALITY_MAX_SIZE)
  {
    $('.editprofile_sexuality').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_sexuality').removeClass('hidden');
    $('.editprofile_sexuality_validation_error').text(lang('p_sexuality_too_long'));
    return true;
  }
  if (data.user_partner.length > USER_PARTNER_MAX_SIZE)
  {
    $('.editprofile_partner').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_partner').removeClass('hidden');
    $('.editprofile_partner_validation_error').text(lang('p_partner_too_long'));
    return true;
  }
  if (data.user_children.length > USER_CHILDREN_MAX_SIZE)
  {
    $('.editprofile_children').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_children').removeClass('hidden');
    $('.editprofile_children_validation_error').text(lang('p_children_too_long'));
    return true;
  }
  if (data.user_occupation.length > USER_OCCUPATION_MAX_SIZE)
  {
    $('.editprofile_occupation').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_occupation').removeClass('hidden');
    $('.editprofile_occupation_validation_error').text(lang('p_occupation_too_long'));
    return true;
  }
  if (data.user_phone.length > USER_PHONE_MAX_SIZE)
  {
    $('.editprofile_phone').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_phone').removeClass('hidden');
    $('.editprofile_phone_validation_error').text(lang('p_phone_too_long'));
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

function success_save_profile(data)
{
  show_success("editprofile_data_alert", lang('p_profile_saved_correctly'));
}

function error_save_profile(data)
{
  var error_number = data.status;
  var error_message = data.error.message;

  show_fail("editprofile_data_alert", lang('p_profile_not_saved_by_error'));

  if (error_number === 1101)
  {
    $('.editprofile_user_name').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_user_name').removeClass('hidden');
    $('.editprofile_user_name_validation_error').text(error_message);
    return;
  }
  if (error_number === 1102)
  {
    $('.editprofile_user_name').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_user_name').removeClass('hidden');
    $('.editprofile_user_name_validation_error').text(error_message);
    return;
  }
  if (error_number === 1103)
  {
    $('.editprofile_user_name').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_user_name').removeClass('hidden');
    $('.editprofile_user_name_validation_error').text(error_message);
    return;
  }
  if (error_number === 1109)
  {
    $('.editprofile_select_year').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_select_year').removeClass('hidden');
    $('.editprofile_select_year_validation_error').text(error_message);
    return;
  }
  if (error_number === 1110)
  {
    $('.editprofile_select_year').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_select_year').removeClass('hidden');
    $('.editprofile_select_year_validation_error').text(error_message);
    return;
  }
  if (error_number === 1111)
  {
    $('.editprofile_radio_gender').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_radio_gender').removeClass('hidden');
    $('.editprofile_radio_gender_validation_error').text(error_message);
    return;
  }
  if (error_number === 1112)
  {
    $('.editprofile_select_region').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_select_region').removeClass('hidden');
    $('.editprofile_select_region_validation_error').text(error_message);
    return;
  }
  if (error_number === 1113)
  {
    $('.editprofile_select_region').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_select_region').removeClass('hidden');
    $('.editprofile_select_region_validation_error').text(error_message);
    return;
  }
  if (error_number === 1115)
  {
    $('.editprofile_nationality').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_nationality').removeClass('hidden');
    $('.editprofile_nacionality_validation_error').text(error_message);
    return;
  }
  if (error_number === 1116)
  {
    $('.editprofile_dwelling').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_dwelling').removeClass('hidden');
    $('.editprofile_dwelling_validation_error').text(error_message);
    return;
  }
  if (error_number === 1117)
  {
    $('.editprofile_car').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_car').removeClass('hidden');
    $('.editprofile_car_validation_error').text(error_message);
    return;
  }
  if (error_number === 1118)
  {
    $('.editprofile_sexuality').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_sexuality').removeClass('hidden');
    $('.editprofile_sexuality_validation_error').text(error_message);
    return;
  }
  if (error_number === 1119)
  {
    $('.editprofile_children').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_children').removeClass('hidden');
    $('.editprofile_children_validation_error').text(error_message);
    return;
  }
  if (error_number === 1120)
  {
    $('.editprofile_partner').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_partner').removeClass('hidden');
    $('.editprofile_partner_validation_error').text(error_message);
    return;
  }
  if (error_number === 1121)
  {
    $('.editprofile_occupation').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_occupation').removeClass('hidden');
    $('.editprofile_occupation_validation_error').text(error_message);
    return;
  }
  if (error_number === 1122)
  {
    $('.editprofile_phone').parent().parent().addClass('has-error');
    $('.error_validation_editprofile_phone').removeClass('hidden');
    $('.editprofile_phone_validation_error').text(error_message);
    return;
  }
  if (error_number === 1125)
  {
    show_fail("editprofile_data_alert", error_message);
    return;
  }
}

function success_upload_photo(data)
{
  $(":file").filestyle('clear');
  show_success("editprofile_photo_alert", lang('p_photo_added_correctly'));
	get_user_photos();
}

function error_upload_photo(data)
{
  $(":file").filestyle('clear');
  show_fail("editprofile_photo_alert", data.error.message);
}

function get_user_photos()
{
	var req = {};
  req.url = '/photo/all_from_user/'+user_id;
  req.type = 'GET';
  req.success = "success_get_user_photos";
  req.error = "error_get_user_photos";
  ajaxp(req);
}

function success_get_user_photos(data)
{
  if (data.count > 0)
  {
    $(".editprofile_photos_carousel").children().remove();
    $(".editprofile_photos_carousel").append('<div id="editprofile_carousel"></div>');
    $(".editprofile_photos_carousel").append('<div class="clearfix"></div>');
    $(".editprofile_photos_carousel").append('<a class="prev" id="foo1_prev" href="#"><span>prev</span></a>');
    $(".editprofile_photos_carousel").append('<a class="next" id="foo1_next" href="#"><span>next</span></a>');

    $(".editprofile_photos_text_no_photos").text("");
    $(".editprofile_photos_text_no_photos").addClass("hidden");

  	$.each(data.result, function (photo_id) {
      var photo = data.result[photo_id];

      var div_images = $('<div></div>').addClass("thumbnail").addClass("form-inline");
      $("#editprofile_carousel").append(div_images);

      var link_to_box = $('<a class="fancybox" rel="group" href="'+baseurl + photo.route+'"></a>');
      $(div_images).append(link_to_box);

      $(link_to_box).append($('<img></img>').addClass("editprofile_photos_carousel_img").addClass("img-rounded").attr("id", "editprofile_photos_carousel_img"+photo.id).attr("src", baseurl + photo.thumbnail));

      var div_images_caption = $('<div></div>').addClass("caption");
      $(div_images).append(div_images_caption);

      var div_images_caption_p = $('<p></p>');
      $(div_images_caption).append(div_images_caption_p);

      var icon_main = $('<a></a>').attr("tagid", photo.id).attr("title",lang("p_mark_photo_as_main_for_user")).addClass("btn").addClass("btn-default").addClass("btn-block").addClass("editprofile_photos_carousel_img_btn_main").text(lang('p_main'));
      if (photo.main_for_user == "1")
      {
        $(icon_main).addClass("btn-success");
      }

      $(div_images_caption_p).append(icon_main);
      $(div_images_caption_p).append($('<a></a>').attr("tagid", photo.id).addClass("btn").addClass("btn-default").addClass("btn-block").addClass("editprofile_photos_carousel_img_btn_delete").text(lang('p_delete')));
    });

    $(".editprofile_photos_carousel").removeClass("hidden");
    $('.content_editprofile_photos_right').removeClass('hidden');

    $("#editprofile_carousel").carouFredSel({
      items   : PHOTOS_IN_CARROUSEL,
      auto    : false,
      prev    : "#foo1_prev",
      next    : "#foo1_next"
    });
    $(".fancybox").fancybox();

    $(".editprofile_photos_carousel_img_btn_main").on("click", function(submitEvent)
    {
      if (!$(this).hasClass("btn-success"))
      {
        var photo_id = $(this).attr("tagid");

        var req = {};
        req.url = '/photo/set_photo_as_main/'+user_id+'/'+photo_id;
        req.type = 'GET';
        req.success = "success_set_photo_as_main";
        req.error = "error_set_photo_as_main";
        ajaxp(req);
      }
    });

    $(".editprofile_photos_carousel_img_btn_delete").on("click", function(submitEvent)
    {
      var photo_id = $(this).attr("tagid");

      var req = {};
      req.url = '/photo/delete/'+photo_id;
      req.type = 'GET';
      req.success = "success_delete_photo";
      req.error = "error_delete_photo";
      ajaxp(req);
    });
  }
  else
  {
    $(".editprofile_photos_text_no_photos").text(lang('p_user_without_photos'));
    $(".editprofile_photos_text_no_photos").removeClass("hidden");
    $(".editprofile_photos_carousel").addClass("hidden");
    $('.content_editprofile_photos_right').removeClass('hidden');
  }   
}

function error_get_user_photos(data)
{
  $('.content_editprofile_photos_right div.form-group').eq(0).addClass("hidden");
  $('.content_editprofile_photos_right div.form-group').eq(1).addClass("hidden");
  $(".editprofile_photos_carousel").addClass("hidden");
  show_fail("editprofile_photo_alert", data.error.message, true);
}

function success_set_photo_as_main(data)
{
  var photo_id = data.result;

  var buttons_main;

  $.each(buttons_main = $(".editprofile_photos_carousel_img_btn_main"), function (i) 
  {
    $(buttons_main).removeClass("btn-success");
  });

  $(".editprofile_photos_carousel_img_btn_main[tagid="+photo_id+"]").addClass("btn-success");

  get_main_photo();

  show_success("editprofile_photo_alert", lang('p_photo_save_as_main_correctly'));
}

function error_set_photo_as_main(data)
{
  show_fail("editprofile_photo_alert", data.error.message);
}

function success_delete_photo(data)
{
  var photo_id = data.result;

  $(".editprofile_photos_carousel_img_btn_delete[tagid="+photo_id+"]").parent().parent().parent().remove();

  get_main_photo();

  show_success("editprofile_photo_alert", lang('p_photo_deleted_correctly'));

  if (typeof $('#editprofile_carousel div.thumbnail').get(0) === "undefined")
  {
    $(".editprofile_photos_text_no_photos").text(lang('p_user_without_photos'));
    $(".editprofile_photos_text_no_photos").removeClass("hidden");
    $(".editprofile_photos_carousel").addClass("hidden");
  }
}

function error_delete_photo(data)
{
  show_fail("editprofile_photo_alert", data.error.message);
}

function get_main_photo()
{
  var req = {};
  req.url = '/photo/get_main_from_user/'+user_id;
  req.type = 'GET';
  req.success = "success_get_main_photo";
  req.error = "error_get_main_photo";
  ajaxp(req);
}

function success_get_main_photo(data)
{
  $(".content_editprofile_main_photo").attr("src",baseurl + data.result.route_main_for_user);
}

function error_get_main_photo(data)
{
  $(".content_editprofile_main_photo").attr("src",baseurl + "/assets/photos/"+DEFAULT_NO_PHOTO_NAME);
}

function get_user_sociality()
{
  var req = {};
  req.url = '/sociality/get_social/'+user_id;
  req.type = 'GET';
  req.success = "success_get_user_sociality";
  req.error = "error_get_user_sociality";
  ajaxp(req);
}

function success_get_user_sociality(data)
{
  if (data.count > 0)
  {
    $(".editprofile_sociality_images").children().remove();

    $(".editprofile_sociality_text_no_socialities").text("");
    $(".editprofile_sociality_text_no_socialities").addClass("hidden");    

    $.each(data.result, function (sociality_id) {
      var sociality = data.result[sociality_id];

      var div_images = $('<div></div>').addClass("thumbnail").addClass("form-inline");
      $(".editprofile_sociality_images").append(div_images);
      $(div_images).append($('<img></img>').attr("src", baseurl + "/assets/icons/sociality/" + get_name_sociality_from_tag(sociality.tag) + ".png"));

      var div_images_caption = $('<div></div>').addClass("caption");
      $(div_images).append(div_images_caption);

      var div_images_caption_p = $('<p></p>').text(sociality.name);
      $(div_images_caption).append(div_images_caption_p);

      var icon_main = $('<a></a>').attr("tagid", sociality.sociality_id).attr("title",lang("p_mark_sociality_as_main_for_proposal")).addClass("btn").addClass("btn-default").addClass("btn-block").addClass("editprofile_sociality_images_btn_main").text(lang('p_main'));
      if (sociality.show_in_proposal == "1")
      {
        $(icon_main).addClass("btn-success");
      }

      $(div_images_caption_p).append(icon_main);
      $(div_images_caption_p).append($('<a></a>').attr("tagid", sociality.id).addClass("btn").addClass("btn-default").addClass("btn-block").addClass("editprofile_sociality_images_btn_delete").text(lang('p_delete')));
    });

    $(".editprofile_sociality_images_btn_main").on("click", function(submitEvent)
    {
      if (!$(this).hasClass("btn-success"))
      {
        var sociality_id = $(this).attr("tagid");

        var req = {};
        req.url = '/sociality/show_in_proposal/'+user_id+'/'+sociality_id;
        req.type = 'GET';
        req.success = "success_show_in_proposal";
        req.error = "error_show_in_proposal";
        ajaxp(req);
      }
    });

    $(".editprofile_sociality_images_btn_delete").on("click", function(submitEvent)
    {
      var sociality_id = $(this).attr("tagid");

      var req = {};
      req.url = '/sociality/delete_social/'+sociality_id;
      req.type = 'GET';
      req.success = "success_delete_social";
      req.error = "error_delete_social";
      ajaxp(req);
    });

    $(".editprofile_sociality_images").removeClass('hidden');
  }
  else
  {
    $(".editprofile_sociality_text_no_socialities").text(lang('p_user_without_socialities'));
    $(".editprofile_sociality_text_no_socialities").removeClass("hidden");
    $(".editprofile_sociality_images").addClass("hidden");
  }    

  $('.content_editprofile_sociality_right').removeClass('hidden');
}

function error_get_user_sociality(data)
{
  $('.content_editprofile_sociality_right div.form-group').eq(0).addClass("hidden");
  $('.content_editprofile_sociality_right div.form-group').eq(1).addClass("hidden");
  $('.content_editprofile_sociality_right').removeClass('hidden');
  show_fail("editprofile_sociality_alert", data.error.message, true);
}

function get_name_sociality_from_tag(tag)
{
  return tag.substring(1).toLowerCase();
}

function success_show_in_proposal(data)
{
  get_user_sociality();
  show_success("editprofile_sociality_alert", lang('p_sociality_show_in_proposal_correctly'));
}

function error_show_in_proposal(data)
{
  show_fail("editprofile_sociality_alert", data.error.message);
}

function success_delete_social(data)
{
  get_user_sociality();
  show_success("editprofile_sociality_alert", lang('p_sociality_deleted_correctly'));
}

function error_delete_social(data)
{
  show_fail("editprofile_sociality_alert", data.error.message);
}

function get_all_socialities_for_add()
{
  var req = {};
  req.url = '/sociality/all';
  req.type = 'GET';
  req.success = "success_get_all_socialities";
  req.error = "error_get_all_socialities";
  ajaxp(req);
}

function success_get_all_socialities(data)
{
  $(".editprofile_sociality_select").prop("disabled",true);
  $(".editprofile_sociality_select_add_button").prop("disabled",true);

  $.each(data.result, function (sociality_id) {
    var sociality = data.result[sociality_id];
    $('.editprofile_sociality_select').append($('<option></option>'));
    $('.editprofile_sociality_select option:last').attr("value", sociality.id).text(sociality.name);
  });

  $(".editprofile_sociality_select").prop("disabled",false);
  $(".editprofile_sociality_select_add_button").prop("disabled",false);
}

function error_get_all_socialities(data)
{
  $(".editprofile_sociality_select").prop("disabled",true);
  $(".editprofile_sociality_select_add_button").prop("disabled",true);

  show_fail("editprofile_sociality_alert", data.error.message, true);
}

function success_save_social(data)
{
  show_success("editprofile_sociality_alert", lang('p_social_added_correctly'));
  get_user_sociality();
}

function error_save_social(data)
{
  show_fail("editprofile_sociality_alert", data.error.message);
}

function get_user_spoken_languages()
{
  var req = {};
  req.url = '/language/get_spoken/'+user_id;
  req.type = 'GET';
  req.success = "success_get_user_spoken_languages";
  req.error = "error_get_user_spoken_languages";
  ajaxp(req); 
}

function get_all_spoken_languages()
{
  var req = {};
  req.url = '/language/all';
  req.type = 'GET';
  req.success = "success_get_all_spoken_languages";
  req.error = "error_get_all_spoken_languages";
  ajaxp(req); 
}

function success_get_user_spoken_languages(data)
{
  if (data.count > 0)
  {
    $(".editprofile_select_spoken").html('');

    $.each(data.result, function (i) {
      var spoken = data.result[i];
      $(".editprofile_select_spoken").append($('<option></option>').attr("class", "editprofile_select_spoken_option").attr("value", spoken.id).text(spoken.name + " ("+lang('p_'+spoken.quality)+")"));      
    });
  }
  else
  {
    $(".editprofile_select_spoken").html('');
  }
}

function error_get_user_spoken_languages(data)
{
  $(".editprofile_select_spoken").parent().parent().addClass('hidden');
  $(".editprofile_select_spoken_level").parent().parent().addClass('hidden');
}

function success_get_all_spoken_languages(data)
{
  if (data.count > 0)
  {
    $(".editprofile_select_spoken_add option").not(":eq(0)").remove(); 

    $.each(data.result, function (i) {
      var spoken = data.result[i];
      $(".editprofile_select_spoken_add").append($('<option></option>').attr("class", "editprofile_select_spoken_add_option").attr("value", spoken.id).text(spoken.name));      
    });
  }
  else
  {
    $(".editprofile_select_spoken_add option").not(":eq(0)").remove(); 
  }
}

function error_get_all_spoken_languages(data)
{
  $(".editprofile_select_spoken_level").parent().parent().addClass('hidden');
}

function success_delete_spoken(data)
{
  $(".editprofile_link_delete_language_selected").prop("disabled",true);
  get_user_spoken_languages();
}

function error_delete_spoken(data)
{
  show_fail("editprofile_data_alert", data.error.message);
}

function success_save_spoken(data)
{
  get_user_spoken_languages();
}

function error_save_spoken(data)
{
  show_fail("editprofile_data_alert", data.error.message);
}