var user_id = null;

$(document).ready(function() 
{
	user_id = $(".gdata_user_id").val();

	$('.newprop_select_country').on("change", function()
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

  $('.newprop_save_prop_link').on("click", function()
  {
  	newprop_clean_form();

    var data_post = {};
    data_post.proposal_user_id = user_id;
    data_post.proposal_text = $('.newprop_prop_title').val();
    data_post.proposal_description = $('.newprop_prop_description').val();
    data_post.proposal_country_id = $('.newprop_select_country').val();
    data_post.proposal_state_id = $('.newprop_select_state').val();
    data_post.proposal_visibility = ($('.newprop_visibility_onoffswitch .onoffswitch-checkbox').is(':checked') === true) ? 1 : 0 ;

    var error_occurred = validate_newprop(data_post);
    if (!error_occurred)
    {
      var req = {};
      req.url = '/proposal/create_proposal';
      req.type = 'POST';
      req.success = "success_save_proposal";
      req.error = "error_save_proposal";
      req.data = data_post;
      ajaxp(req);
    }
    else
    {
      show_fail("newprop_data_alert", lang('p_prop_not_saved_by_error'));
    }
  });

  newprop_clean_form();
  get_countries();
  create_info_popover();
});	

function create_info_popover()
{
  var options_popover = {};
  options_popover.html = true;
  options_popover.content = lang('p_category_for_what');
  options_popover.placement = 'right';
  options_popover.container = 'body';
  set_popover('.img_info',options_popover);
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

function success_get_countries(data)
{
  var country_id_data = $(".gdata_country_id").val();

  $.each(data.result, function (country_id) {
    var country = data.result[country_id];
    $('.newprop_select_country').append($('<option></option>'));
    var option = $('.newprop_select_country option:last').attr("value", country.id).text(country.name);
    if (parseInt(country_id_data) === parseInt(country.id))
    {
    	option.attr("selected", true);
    }
  });
  $('.newprop_select_country').prop('disabled', false);
  
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
  $('.newprop_select_country').prop('disabled', true);
}

function success_get_states(data)
{
	var state_id_data = $(".gdata_state_id").val();

  $.each(data.result, function (state_id) {
    var state = data.result[state_id];
    $('.newprop_select_state').append($('<option></option>'));
    var option = $('.newprop_select_state option:last').attr("value", state.id).text(state.name);
	  if (parseInt(state_id_data) === parseInt(state.id))
    {
    	option.attr("selected", true);
    }
  });
  $('.newprop_select_state').prop('disabled', false);
}

function success_get_states_clean(data)
{
	var state_id_data = $(".gdata_state_id").val();

  $.each(data.result, function (state_id) {
    var state = data.result[state_id];
    $('.newprop_select_state').append($('<option></option>').attr("value", state.id).text(state.name));
  });
  $('.newprop_select_state').prop('disabled', false);
}

function error_get_states(data)
{
  $('.newprop_select_state').prop('disabled', true);
}

function error_get_states_clean(data)
{
  $('.newprop_select_state').prop('disabled', true);
}

function reset_country_select()
{
  $('.newprop_select_country').val('');
  $(".newprop_select_country option").not(":eq(0)").remove(); 
  $('.newprop_select_country').prop("disabled",true);
}

function reset_state_select()
{
  $('.newprop_select_state').val('');
  $(".newprop_select_state option").not(":eq(0)").remove(); 
  $('.newprop_select_state').prop("disabled",true);
}

function newprop_clean_form()
{
  $('.newprop_prop_title').parent().parent().removeClass('has-error');
  $('.error_validation_newprop_prop_title').addClass('hidden');
  $('.newprop_prop_title_validation_error').text("");

  $('.newprop_select_region').parent().parent().removeClass('has-error');
  $('.error_validation_newprop_select_region').addClass('hidden');
  $('.newprop_select_region_validation_error').text("");

  $('.newprop_prop_description').parent().parent().removeClass('has-error');
  $('.error_validation_newprop_prop_description').addClass('hidden');
  $('.newprop_prop_description_validation_error').text("");
}

function enable_newprop_form()
{
	$('.newprop_prop_title').prop('disabled',false);
	$('.newprop_visibility_onoffswitch').prop('disabled',false);
	$('.newprop_prop_description').prop('disabled',false);
	$('.newprop_select_country').prop('disabled',false);
	$('.newprop_select_state').prop('disabled',false);
}

function disable_newprop_form()
{
	$('.newprop_prop_title').prop('disabled',true);
	$('.newprop_visibility_onoffswitch').prop('disabled',true);
	$('.newprop_prop_description').prop('disabled',true);
	$('.newprop_select_country').prop('disabled',true);
	$('.newprop_select_state').prop('disabled',true);
}

function success_save_proposal(data)
{
  show_success("newprop_data_alert", lang('p_created_saved_correctly'));
	clean_data_for_new_proposal();
  get_num_myprops();
}

function error_save_proposal(data)
{
	var error_number = data.status;
  var error_message = data.error.message;

  show_fail("newprop_data_alert", lang('p_prop_not_saved_by_error'));

  if (error_number === 1601)
  {
    $('.newprop_prop_title').parent().parent().addClass('has-error');
    $('.error_validation_newprop_prop_title').removeClass('hidden');
    $('.newprop_prop_title_validation_error').text(error_message);
    return;
  }
  if (error_number === 1602)
  {
    $('.newprop_prop_title').parent().parent().addClass('has-error');
    $('.error_validation_newprop_prop_title').removeClass('hidden');
    $('.newprop_prop_title_validation_error').text(error_message);
    return;
  }
  if (error_number === 1604)
  {
    $('.newprop_select_region').parent().parent().addClass('has-error');
    $('.error_validation_newprop_select_region').removeClass('hidden');
    $('.newprop_select_region_validation_error').text(error_message);
    return;
  }
  if (error_number === 1605)
  {
    $('.newprop_select_region').parent().parent().addClass('has-error');
    $('.error_validation_newprop_select_region').removeClass('hidden');
    $('.newprop_select_region_validation_error').text(error_message);
    return;
  }
  if (error_number === 1607)
  {
    show_fail("newprop_data_alert", error_message);
    return;
  }
  if (error_number === 1622)
  {
    show_fail("newprop_data_alert", error_message);
    return;
  }
}

function validate_newprop(data)
{
  if (data.proposal_text.length < PROPOSAL_NAME_MIN_SIZE)
  {
    $('.newprop_prop_title').parent().parent().addClass('has-error');
    $('.error_validation_newprop_prop_title').removeClass('hidden');
    $('.newprop_prop_title_validation_error').text(lang('p_title_too_short'));
    return true;
  }
  if (data.proposal_text.length > PROPOSAL_NAME_MAX_SIZE)
  {
    $('.newprop_prop_title').parent().parent().addClass('has-error');
    $('.error_validation_newprop_prop_title').removeClass('hidden');
    $('.newprop_prop_title_validation_error').text(lang('p_title_too_long'));
    return true;
  }
  if (!data.proposal_state_id.match(/^-{0,1}\d*\.{0,1}\d+$/) || (data.proposal_state_id < 1) || (typeof data.proposal_state_id === "undefined"))
  {
    $('.newprop_select_region').parent().parent().addClass('has-error');
    $('.error_validation_newprop_select_region').removeClass('hidden');
    $('.newprop_select_region_validation_error').text(lang('p_state_not_valid'));
    return true;
  }
  if (!data.proposal_country_id.match(/^-{0,1}\d*\.{0,1}\d+$/) || (data.proposal_country_id < 1) || (typeof data.proposal_country_id === "undefined"))
  {
    $('.newprop_select_region').parent().parent().addClass('has-error');
    $('.error_validation_newprop_select_region').removeClass('hidden');
    $('.newprop_select_region_validation_error').text(lang('p_country_not_valid'));
    return true;
  }

  return false;
}

function clean_data_for_new_proposal()
{
	$('.newprop_prop_title').val('');
	$('.newprop_prop_description').val('');
	$('.newprop_visibility_onoffswitch .onoffswitch-checkbox').prop("checked",true);
}