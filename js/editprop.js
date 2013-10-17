var data_proposal = null;
var proposal_id = null;
var initial_load = true;

$(document).ready(function() 
{
	proposal_id = localStorage.getItem("proposal_id");

	var get_proposal_data = function()
  {
  	if (typeof proposal_id !== "undefined" && proposal_id !== null && proposal_id !== '')
    {
	    var req = {};
	    req.url = '/proposal/read_proposal/'+proposal_id;
	    req.type = 'GET';
	    req.success = "success_get_proposal_data";
	    req.error = "error_get_proposal_data";
	    ajaxp(req);
	  }
  }

  $('.editprop_save_prop_link').on("click", function()
  {
    editprop_clean_form();

    var data_post = {};
    data_post.proposal_user_id = $('.gdata_user_id').val();
    data_post.proposal_text = $('.editprop_prop_title').val();
    data_post.proposal_description = $('.editprop_prop_description').val();
    data_post.proposal_country_id = $('.editprop_select_country').val();
    data_post.proposal_state_id = $('.editprop_select_state').val();
    data_post.proposal_visibility = ($('.editprop_visibility_onoffswitch .onoffswitch-checkbox').is(':checked') === true) ? 1 : 0 ;
    data_post.filter_t = $('input[name=filter_t]').val();

    var error_occurred = validate_editprop(data_post);
    if (!error_occurred)
    {
      var req = {};
      req.url = '/proposal/update_proposal/'+proposal_id;
      req.type = 'POST';
      req.success = "success_save_proposal";
      req.error = "error_save_proposal";
      req.data = data_post;
      ajaxp(req);
    }
    else
    {
      show_fail("editprop_data_alert", lang('p_prop_not_saved_by_error'));
    }
  });

  $('.editprop_delete_prop_link').on("click", function()
  {
    delete_proposal();
  });

  $('.editprop_select_country').on("change", function()
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

	$('.editprop_upload_photo_button').on("click", function(submitEvent)
  {
    var filename = $(".content_editprop_photos_right input[name=upload]").val();

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
          show_fail("editprop_photo_alert", lang('p_photo_must_be_image'));
		    break;  
		  }
    }
  });

	$('.editprop_category_select_add_button').on("click", function(submitEvent)
  {
    var category = $('.editprop_category_select').val();
    
    var data_post = {};
    data_post.proposal_category_id = category;
    data_post.filter_t = $('input[name=filter_t]').val();

    if (typeof category !== "undefined" && category !== null && category !== '')
    {
      var req = {};
      req.url = '/category/save_category/'+proposal_id;
      req.type = 'POST';
      req.success = "success_save_category";
      req.error = "error_save_category";
      req.data = data_post;
      ajaxp(req);
    }
  });

  editprop_clean_form();
  disable_editprop_form();
	get_proposal_data();
	get_proposal_photos();
	get_proposal_category();
  get_all_categories_for_add();
  create_info_popover();
  $(":file").filestyle({icon: false});
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

function send_photo()
{
	var req = {};
  req.url = '/photo/add_for_proposal/'+proposal_id;
  req.type = 'POST';
  req.success = "success_upload_photo";
  req.error = "error_upload_photo";
  req.data = new FormData($('.editprop_upload_photo_form')[0]);
  req.cache = false;
  req.contentType = false;
  req.processData = false;
  ajaxp(req);
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

function success_get_proposal_data(data)
{
	data_proposal = data.result;

	get_countries();
}

function put_proposal_data_in_form()
{
	$('.editprop_prop_title').val(data_proposal.proposal);
	$('.editprop_prop_description').val(data_proposal.description);

	$('.editprop_prop_creationdate').text(format_date_creation(data_proposal.time));

	var visible = (data_proposal.visibility === "1") ? true : false ;
	$('.editprop_visibility_onoffswitch .onoffswitch-checkbox').attr('checked', visible);

	$('.editprop_select_country').val(data_proposal.country_id);

  $('.editprop_prop_visited').text(data_proposal.visited);

	// Pasaron menos de 24h desde su creación
	if (data_proposal.editable === '0')
	{
		$('.editprop_prop_title').prop('disabled', true);
		$('.editprop_select_country').prop('disabled', true);
		$('.editprop_select_state').prop('disabled', true);
		$('.editprop_prop_title_validation_error').text(lang('p_proposal_cant_change_data'));
    $('.error_validation_editprop_prop_title').removeClass('hidden');
  	$('.editprop_select_region_validation_error').text(lang('p_proposal_cant_change_data'));
    $('.error_validation_editprop_select_region').removeClass('hidden');
	}
	else
	{
		$('.editprop_prop_title').prop('disabled', false);
		$('.editprop_select_country').prop('disabled', false);
		$('.editprop_select_state').prop('disabled', false);
    $('.editprop_prop_title_validation_error').text("");
    $('.error_validation_editprop_prop_title').addClass('hidden');
    $('.editprop_select_region_validation_error').text("");
    $('.error_validation_editprop_select_region').addClass('hidden');
	}

  if (data_proposal.moderated_invalid === '1')
  {
    $('.editprop_prop_title').prop('disabled', true);
    $('.editprop_select_country').prop('disabled', true);
    $('.editprop_select_state').prop('disabled', true);
    $('.editprop_prop_description').prop('disabled', true);
    $('.editprop_visibility_onoffswitch .onoffswitch-checkbox').parent().parent().addClass('hidden');
    $(":file").prop('disabled', true);
    $('.editprop_upload_photo_button').prop('disabled', true);
    $('.editprop_category_select_add_button').prop('disabled', true);
    $('.editprop_category_select').prop('disabled', true);

    $('.editprop_prop_title_validation_error').text(lang('p_proposal_moderated_invalid'));
    $('.error_validation_editprop_prop_title').removeClass('hidden');
  }
  else
  {
    $('.editprop_visibility_onoffswitch .onoffswitch-checkbox').parent().parent().removeClass('hidden');
  }
}

function error_get_proposal_data()
{
  show_fail("editprop_data_alert", lang('p_proposal_data_not_load'));
}

function success_get_countries(data)
{
  var country_id_data = data_proposal['country_id'];

  $.each(data.result, function (country_id) {
    var country = data.result[country_id];
    $('.editprop_select_country').append($('<option></option>'));
    var option = $('.editprop_select_country option:last').attr("value", country.id).text(country.name);

    if (parseInt(country_id_data) === parseInt(country.id))
    {
      option.attr("selected", true);
    }
  });
  $('.editprop_select_country').prop('disabled', false);

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
  $('.editprop_select_country').prop('disabled', true);
}

function success_get_states(data)
{
	var state_id_data = data_proposal['state_id'];

  $.each(data.result, function (state_id) {
    var state = data.result[state_id];
    $('.editprop_select_state').append($('<option></option>'));
    var option = $('.editprop_select_state option:last').attr("value", state.id).text(state.name);

	  if (parseInt(state_id_data) === parseInt(state.id))
    {
    	option.attr("selected", true);
    }
  });
  $('.editprop_select_state').prop('disabled', false);

  if (initial_load)
  {
    enable_editprop_form();
    put_proposal_data_in_form();
    initial_load = false;
  }
}

function success_get_states_clean(data)
{
	var state_id_data = data_proposal['state_id'];

  $.each(data.result, function (state_id) {
    var state = data.result[state_id];
    $('.editprop_select_state').append($('<option></option>').attr("value", state.id).text(state.name));
  });
  $('.editprop_select_state').prop('disabled', false);
}

function error_get_states(data)
{
  $('.editprop_select_state').prop('disabled', true);
}

function error_get_states_clean(data)
{
  $('.editprop_select_state').prop('disabled', true);
}

function reset_country_select()
{
  $('.editprop_select_country').val('');
  $(".editprop_select_country option").not(":eq(0)").remove(); 
  $('.editprop_select_country').prop("disabled",true);
}

function reset_state_select()
{
  $('.editprop_select_state').val('');
  $(".editprop_select_state option").not(":eq(0)").remove(); 
  $('.editprop_select_state').prop("disabled",true);
}

function editprop_clean_form()
{
  $('.editprop_prop_title').parent().parent().removeClass('has-error');
  $('.editprop_select_region').parent().parent().removeClass('has-error');

  if (typeof data_proposal !== "undefined" && data_proposal !== null && data_proposal !== '')
  {
    if (data_proposal.editable === '0')
    {
      $('.editprop_prop_title_validation_error').text(lang('p_proposal_cant_change_data'));
      $('.editprop_select_region_validation_error').text(lang('p_proposal_cant_change_data'));
    }
    else
    {
      $('.error_validation_editprop_prop_title').addClass('hidden');
      $('.editprop_prop_title_validation_error').text("");
      $('.error_validation_editprop_select_region').addClass('hidden');
      $('.editprop_select_region_validation_error').text("");
    }
  }
  else
  {
    $('.error_validation_editprop_prop_title').addClass('hidden');
    $('.editprop_prop_title_validation_error').text("");
    $('.error_validation_editprop_select_region').addClass('hidden');
    $('.editprop_select_region_validation_error').text("");
  }

  $('.editproprop_description').parent().parent().removeClass('has-error');
  $('.error_validation_editproprop_description').addClass('hidden');
  $('.editprop_prop_description_validation_error').text("");
}

function enable_editprop_form()
{
	$('.editprop_prop_title').prop('disabled',false);
	$('.editprop_visibility_onoffswitch .onoffswitch-checkbox').prop('disabled',false);
	$('.editprop_prop_description').prop('disabled',false);
	$('.editprop_select_country').prop('disabled',false);
	$('.editprop_select_state').prop('disabled',false);
}

function disable_editprop_form()
{
	$('.editprop_prop_title').prop('disabled',true);
	$('.editprop_visibility_onoffswitch .onoffswitch-checkbox').prop('disabled',true);
	$('.editprop_prop_description').prop('disabled',true);
	$('.editprop_select_country').prop('disabled',true);
	$('.editprop_select_state').prop('disabled',true);
}

function validate_editprop(data)
{
  if (data.proposal_text.length < PROPOSAL_NAME_MIN_SIZE)
  {
    $('.editprop_prop_title').parent().parent().addClass('has-error');
    $('.error_validation_editprop_prop_title').removeClass('hidden');
    $('.editprop_prop_title_validation_error').text(lang('p_title_too_short'));
    return true;
  }
  if (data.proposal_text.length > PROPOSAL_NAME_MAX_SIZE)
  {
    $('.editprop_prop_title').parent().parent().addClass('has-error');
    $('.error_validation_editprop_prop_title').removeClass('hidden');
    $('.editprop_prop_title_validation_error').text(lang('p_title_too_long'));
    return true;
  }
  if (!data.proposal_state_id.match(/^-{0,1}\d*\.{0,1}\d+$/) || (data.proposal_state_id < VALIDATE_ID_MIN_VALUE) || (typeof data.proposal_state_id === "undefined"))
  {
    $('.editprop_select_region').parent().parent().addClass('has-error');
    $('.error_validation_editprop_select_region').removeClass('hidden');
    $('.editprop_select_region_validation_error').text(lang('p_state_not_valid'));
    return true;
  }
  if (!data.proposal_country_id.match(/^-{0,1}\d*\.{0,1}\d+$/) || (data.proposal_country_id < VALIDATE_ID_MIN_VALUE) || (typeof data.proposal_country_id === "undefined"))
  {
    $('.editprop_select_region').parent().parent().addClass('has-error');
    $('.error_validation_editprop_select_region').removeClass('hidden');
    $('.editprop_select_region_validation_error').text(lang('p_country_not_valid'));
    return true;
  }
  return false;
}

function success_save_proposal(data)
{
  show_success("editprop_data_alert", lang('p_prop_saved_correctly'));
}

function error_save_proposal(data)
{
  var error_number = data.status;
  var error_message = data.error.message;

  show_fail("editprop_data_alert", lang('p_prop_not_saved_by_error'));

  if (error_number === 1601)
  {
    $('.editprop_prop_title').parent().parent().addClass('has-error');
    $('.error_validation_editprop_prop_title').removeClass('hidden');
    $('.editprop_prop_title_validation_error').text(error_message);
    return;
  }
  if (error_number === 1602)
  {
    $('.editprop_prop_title').parent().parent().addClass('has-error');
    $('.error_validation_editprop_prop_title').removeClass('hidden');
    $('.editprop_prop_title_validation_error').text(error_message);
    return;
  }
  if (error_number === 1603)
  {
    show_fail("editprop_data_alert", error_message);
    return;
  }
  if (error_number === 1604)
  {
    $('.editprop_select_region').parent().parent().addClass('has-error');
    $('.error_validation_editprop_select_region').removeClass('hidden');
    $('.editprop_select_region_validation_error').text(error_message);
    return;
  }
  if (error_number === 1605)
  {
    $('.editprop_select_region').parent().parent().addClass('has-error');
    $('.error_validation_editprop_select_region').removeClass('hidden');
    $('.editprop_select_region_validation_error').text(error_message);
    return;
  }
  if (error_number === 1608)
  {
  	show_fail("editprop_data_alert", error_message);
    return;
  }
  if (error_number === 1623)
  {
    show_fail("editprop_data_alert", error_message);
    return;
  }
  if (error_number === 1624)
  {
    $('.editprop_prop_title').parent().parent().addClass('has-error');
    $('.error_validation_editprop_prop_title').removeClass('hidden');
    $('.editprop_prop_title_validation_error').text(error_message);
    return;
  }
  if (error_number === 1625)
  {
    $('.editprop_select_region').parent().parent().addClass('has-error');
    $('.error_validation_editprop_select_region').removeClass('hidden');
    $('.editprop_select_region_validation_error').text(error_message);
    return;
  }
}

function success_upload_photo(data)
{
  $(":file").filestyle('clear');
  show_success("editprop_photo_alert", lang('p_photo_added_correctly'));
	get_proposal_photos();
}

function error_upload_photo(data)
{
  $(":file").filestyle('clear');
	show_fail("editprop_photo_alert", data.error.message);
}

function get_proposal_photos()
{
	var req = {};
  req.url = '/photo/all_from_proposal/'+proposal_id;
  req.type = 'GET';
  req.success = "success_get_proposal_photos";
  req.error = "error_get_proposal_photos";
  ajaxp(req);
}

function success_get_proposal_photos(data)
{
	if (data.count > 0)
	{
    $(".editprop_photos_carousel").children().remove();
    $(".editprop_photos_carousel").append('<div id="editprop_carousel"></div>');
    $(".editprop_photos_carousel").append('<div class="clearfix"></div>');
    $(".editprop_photos_carousel").append('<a class="prev" id="foo1_prev" href="#"><span>prev</span></a>');
    $(".editprop_photos_carousel").append('<a class="next" id="foo1_next" href="#"><span>next</span></a>');

    $(".editprop_photos_text_no_photos").text("");
    $(".editprop_photos_text_no_photos").addClass("hidden");

    $.each(data.result, function (photo_id) {
      var photo = data.result[photo_id];

      var div_images = $('<div></div>').addClass("thumbnail").addClass("form-inline");
      $("#editprop_carousel").append(div_images);

      var link_to_box = $('<a class="fancybox" rel="group" href="'+baseurl + photo.route+'"></a>');
      $(div_images).append(link_to_box);

      $(link_to_box).append($('<img></img>').attr("class", "editprop_photos_carousel_img").attr("id", "editprop_photos_carousel_img"+photo.id).attr("src", baseurl + photo.thumbnail));

      var div_images_caption = $('<div></div>').addClass("caption");
      $(div_images).append(div_images_caption);

      var div_images_caption_p = $('<p></p>');
      $(div_images_caption).append(div_images_caption_p);

      $(div_images_caption_p).append($('<a></a>').attr("tagid", photo.id).addClass("btn").addClass("btn-default").addClass("btn-block").addClass("editprop_photos_carousel_img_btn_delete").text(lang('p_delete')));
    });

    $(".editprop_photos_carousel").removeClass("hidden");

    $("#editprop_carousel").carouFredSel({
      items   : PHOTOS_IN_CARROUSEL,
      auto    : false,
      prev    : "#foo1_prev",
      next    : "#foo1_next"
    });
    $(".fancybox").fancybox();

    $(".editprop_photos_carousel_img_btn_delete").on("click", function(submitEvent)
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
    $(".editprop_photos_text_no_photos").text(lang('p_proposal_without_photos'));
    $(".editprop_photos_text_no_photos").removeClass("hidden");
    $(".editprop_photos_carousel").addClass("hidden");
  } 
}

function error_get_proposal_photos(data)
{
  $(".editprop_photos_carousel").addClass("hidden");
  show_fail("editprop_photo_alert", data.error.message);
}

function success_delete_photo(data)
{
  var photo_id = data.result;

  $(".editprop_photos_carousel_img_btn_delete[tagid="+photo_id+"]").parent().parent().parent().remove();

  get_proposal_photos();

  show_success("editprop_photo_alert", lang('p_photo_deleted_correctly'));

  if (typeof $('#editprop_carousel div.thumbnail').get(0) === "undefined")
  {
    $(".editprop_photos_text_no_photos").text(lang('p_proposal_without_photos'));
    $(".editprop_photos_text_no_photos").removeClass("hidden");
    $(".editprop_photos_carousel").addClass("hidden");
  }
}

function error_delete_photo(data)
{
  show_fail("editprop_photo_alert", data.error.message);
}

function get_proposal_category()
{
  var req = {};
  req.url = '/category/get_category/'+proposal_id;
  req.type = 'GET';
  req.success = "success_get_proposal_category";
  req.error = "error_get_proposal_category";
  ajaxp(req);
}

function success_get_proposal_category(data)
{
	if (data.count > 0)
	{
    $(".editprop_category_images").children().remove();

    $(".editprop_category_text_no_categories").text("");
    $(".editprop_category_text_no_categories").addClass("hidden");   

    $.each(data.result, function (category_id) {
      var category = data.result[category_id];

      var div_images = $('<div></div>').addClass("thumbnail").addClass("form-inline");
      $(".editprop_category_images").append(div_images);
      $(div_images).append($('<img></img>').attr("src", baseurl + "/assets/icons/category/" + get_name_category_from_tag(category.tag) + ".png"));

      var div_images_caption = $('<div></div>').addClass("caption");
      $(div_images).append(div_images_caption);

      var div_images_caption_p = $('<p></p>').text(category.name);
      $(div_images_caption).append(div_images_caption_p);

      $(div_images_caption_p).append($('<a></a>').attr("tagid", category.id).addClass("btn").addClass("btn-default").addClass("btn-block").addClass("editprop_category_images_btn_delete").text(lang('p_delete')));
    });

    $(".editprop_category_images_btn_delete").on("click", function(submitEvent)
    {
      var category_id = $(this).attr("tagid");

      var req = {};
      req.url = '/category/delete_category/'+category_id;
      req.type = 'GET';
      req.success = "success_delete_category";
      req.error = "error_delete_category";
      ajaxp(req);
    });

    $(".editprop_category_images").removeClass('hidden');
	}
	else
	{
    $(".editprop_category_text_no_categories").text(lang('p_proposal_without_categories'));
    $(".editprop_category_text_no_categories").removeClass("hidden");
    $(".editprop_category_images").addClass("hidden");
	}   
}

function error_get_proposal_category(data)
{
  show_fail("editprop_sociality_alert", data.error.message);
}

function get_name_category_from_tag(tag)
{
  return tag.substring(1).toLowerCase();
}

function success_delete_category(data)
{
  get_proposal_category();
  show_success("editprop_category_alert", lang('p_category_deleted_correctly'));
}

function error_delete_category(data)
{
  show_fail("editprop_category_alert", data.error.message);
}

function get_all_categories_for_add()
{
  var req = {};
  req.url = '/category/all';
  req.type = 'GET';
  req.success = "success_get_all_categories";
  req.error = "error_get_all_categories";
  ajaxp(req);
}

function success_get_all_categories(data)
{
  $(".editprop_category_select").prop("disabled",true);
  $(".editprop_category_select_add_button").prop("disabled",true);

  $.each(data.result, function (category_id) {
    var category = data.result[category_id];
    $('.editprop_category_select').append($('<option></option>'));
    $('.editprop_category_select option:last').attr("value", category.id).text(category.name);
  });

  $(".editprop_category_select").prop("disabled",false);
  $(".editprop_category_select_add_button").prop("disabled",false);
}

function error_get_all_categories(data)
{
  $(".editprop_category_select").prop("disabled",true);
  $(".editprop_category_select_add_button").prop("disabled",true);

  show_fail("editprop_category_alert", data.error.message);
}

function success_save_category(data)
{
  show_success("editprop_category_alert", lang('p_category_added_correctly'));
  get_proposal_category();
}

function error_save_category(data)
{
  show_fail("editprop_category_alert", data.error.message);
}

function delete_proposal()
{
	if (typeof data_proposal !== "undefined" && data_proposal !== null && data_proposal !== '')
  {
		if (data_proposal.editable === '0')
		{
      show_fail("editprop_data_alert", lang('p_proposal_cant_delete'));	
		}
		else
		{
      localStorage.setItem("proposal_id", data_proposal.id);
			window.location = baseurl + "/" + m_lang + "/deleteprop";
		}
  	
  }
}

function format_date_creation(datetime)
{
	var datetime_part = datetime.split(" ");
  var date_part = datetime_part[0];
  var time_part = datetime_part[1];

  var date_part_arr = date_part.split("-");
  var time_part_arr = time_part.split(":");

  var d_year = date_part_arr[0];
  var d_month = date_part_arr[1]-1;
  var d_day = date_part_arr[2];
  var t_hour = time_part_arr[0];
  var t_minutes = time_part_arr[1];
  var t_seconds = time_part_arr[2];

  d_month = parseInt(d_month+1);
  d_month = ""+d_month;
	d_day = ""+d_day;
	t_hour = ""+t_hour;
	t_minutes = ""+t_minutes;
	t_seconds = ""+t_seconds;

  if (d_month.length === 1) { d_month = "0"+d_month; }
  if (d_day.length === 1) { d_day = "0"+d_day; }
  if (t_hour.length === 1) { t_hour = "0"+t_hour; }
  if (t_minutes.length === 1) { t_minutes = "0"+t_minutes; }
  if (t_seconds.length === 1) { t_seconds = "0"+t_seconds; }

  if (date_format['ytod'].in_array(m_lang))
	{
		return d_year+"/"+d_month+"/"+d_day+" "+t_hour+":"+t_minutes;
	}
  return d_day+"/"+d_month+"/"+d_year+" "+t_hour+":"+t_minutes;
}