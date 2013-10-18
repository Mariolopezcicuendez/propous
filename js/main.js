// main.js, main js file

var request_url = null;
var ajaxp = null;
var advanced_searching = false;
var loaded_advanced_searching_data = false;
var search_object = null;
var interval_actualize_chat = null;

// FUNCTIONS

function hide_popup()
{
  $('#modal_popup').modal('hide');
}

function show_popup(data)
{
  if ((data.title !== null) && (typeof data.title !== "undefined"))
  {
    $('#modal_popup_title').text(data.title);
    $('#modal_popup_title').parent().removeClass('hidden');
  }

  if ((data.body !== null) && (typeof data.body !== "undefined"))
  {
    $('#modal_popup_body').html(data.body);
  }
  else
  {
    $('#modal_popup_body').html('');
  }

  if ((data.accept_text !== null) && (typeof data.accept_text !== "undefined"))
  {
    $('#modal_popup_button_accept').text(data.accept_text);
    $('#modal_popup_button_accept').removeClass('hidden');
  }
  else
  {
    $('#modal_popup_button_accept').text(lang('p_accept'));
  }

  if ((data.accept !== null) && (typeof data.accept !== "undefined"))
  {
    var params = ((data.accept_params !== null) && (typeof data.accept_params !== "undefined")) ? data.accept_params : null ;

    $("#modal_popup_button_accept").unbind("click");
    $("#modal_popup_button_accept").on("click", function()
    {
      window[data.accept](params);
    });
  }

  $('#modal_popup').modal({
    backdrop: 'static'
  });
}

function hide_confirm()
{
  $('#modal_confirm').modal('hide');
}

function show_confirm(data)
{
  if ((data.title !== null) && (typeof data.title !== "undefined"))
  {
    $('#modal_confirm_title').text(data.title);
    $('#modal_confirm_title').parent().removeClass('hidden');
  }

  if ((data.body !== null) && (typeof data.body !== "undefined"))
  {
    $('#modal_confirm_body').html(data.body);
  }
  else
  {
    $('#modal_confirm_body').html('');
  }

  if ((data.ok_text !== null) && (typeof data.ok_text !== "undefined"))
  {
    $('#modal_confirm_button_ok').text(data.ok_text);
    $('#modal_confirm_button_ok').removeClass('hidden');
  }
  else
  {
    $('#modal_confirm_button_ok').text(lang('p_accept'));
  }

  if ((data.cancel_text !== null) && (typeof data.cancel_text !== "undefined"))
  {
    $('#modal_confirm_button_cancel').text(data.cancel_text);
  }
  else
  {
    $('#modal_confirm_button_cancel').text(lang('p_cancel'));
  }

  if ((data.ok !== null) && (typeof data.ok !== "undefined"))
  {
    var params = ((data.ok_params !== null) && (typeof data.ok_params !== "undefined")) ? data.ok_params : null ;

    $("#modal_confirm_button_ok").unbind("click");
    $("#modal_confirm_button_ok").on("click", function()
    {
      window[data.ok](params);
    });
  }

  if ((data.cancel !== null) && (typeof data.cancel !== "undefined"))
  {
    var params = ((data.cancel_params !== null) && (typeof data.cancel_params !== "undefined")) ? data.cancel_params : null ;

    $("#modal_confirm_button_cancel").unbind("click");
    $("#modal_confirm_button_cancel").on("click", function()
    {
      window[data.cancel](params);
    });
  }

  $('#modal_confirm').modal({
    backdrop: 'static'
  });
}

function show_loading()
{
  $('#modal_loading').modal({
    backdrop: 'static',
    keyboard: false
  });
}

function hide_loading()
{
  $('#modal_loading').modal('hide');
}

function show_success(element, message, static_time)
{
  if (typeof $("."+element+"_success").get(0) !== "undefined")
  {
    $("."+element+"_success").html(message+'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>');
    $("."+element+"_success").removeClass("hidden");
    $("."+element+"_fail").addClass("hidden");
    $("."+element+"_success").parent().parent().css("display", "block");
    $("."+element+"_success").parent().parent().removeClass("hidden");
    $("."+element+"_success").unbind('close.bs.alert');
    $("."+element+"_success").bind('close.bs.alert', function (closeEvent) 
    {
      closeEvent.preventDefault();
      $("."+element+"_success").html('');
      $("."+element+"_success").addClass("hidden");
      $("."+element+"_success").parent().parent().addClass("hidden");
    });

    if (static_time !== true)
    {
      setTimeout( 'hide_alert(".'+element+'_success")' , TIME_MILISECONDS_ALERTS_HIDE );
    }
  }
}

function show_fail(element, message, static_time)
{
  if (typeof $("."+element+"_fail").get(0) !== "undefined")
  {
    $("."+element+"_fail").html(message+'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>');
    $("."+element+"_fail").removeClass("hidden");
    $("."+element+"_success").addClass("hidden");
    $("."+element+"_fail").parent().parent().css("display", "block");
    $("."+element+"_fail").parent().parent().removeClass("hidden");
    $("."+element+"_fail").unbind('close.bs.alert');
    $("."+element+"_fail").bind('close.bs.alert', function (closeEvent) 
    {
      closeEvent.preventDefault();
      $("."+element+"_fail").html('');
      $("."+element+"_fail").addClass("hidden");
      $("."+element+"_fail").parent().parent().addClass("hidden");
    });

    if (static_time !== true)
    {
      setTimeout( 'hide_alert(".'+element+'_fail")' , TIME_MILISECONDS_ALERTS_HIDE );
    }
  }
}

function hide_alert(alert_id)
{
  $(alert_id).parent().parent().fadeOut( "slow", function() {});
}

function lang(lang_string)
{
	return langs[lang_string];
}

function set_popover(element,options,element_show)
{
  if (element_show !== null && typeof element_show !== "undefined")
  {
    $(element_show).popover(options);
    $(element).mouseover(function() 
    {
      $(element_show).popover("show");
    });

    $(element).mouseleave(function() 
    {
      $(element_show).popover("hide");
    });
  }
  else
  {
    $(element).popover(options);
    $(element).mouseover(function() 
    {
      $(element).popover("show");
    });

    $(element).mouseleave(function() 
    {
      $(element).popover("hide");
    });
  }
}

function clear_popover(element)
{
  $(element).popover('destroy');
}

// DOCUMENT READY

$(document).ready(function() 
{
  if (!supports_html5_storage() && !contact_page_showed())
  {
    invalid_navigator_page();
    return;
  }

	m_lang = $('.actual_lang_tag').val();
	request_url = request_url_domain + '/' + m_lang + request_url_api;

	ajaxp = function(ajaxobj) 
	{
		if (typeof ajaxobj === "undefined") 
		{
			return;
		}	
		if (typeof ajaxobj.url === "undefined")
		{
			return;
		}
		if (typeof ajaxobj.type === "undefined")
		{
			return;
		}
		if (typeof ajaxobj.success === "undefined")
		{
			return;
		}
		if (typeof ajaxobj.error === "undefined")
		{
			return;
		}
		if ((ajaxobj.type === "POST") && (typeof ajaxobj.data === "undefined"))
		{
			return;
		}

		var async_eval = (typeof ajaxobj.async !== "undefined") ? ajaxobj.async : true;
		var cache_eval = (typeof ajaxobj.cache !== "undefined") ? ajaxobj.cache : false;
		var contentType_eval = (typeof ajaxobj.contentType !== "undefined") ? ajaxobj.contentType : 'application/x-www-form-urlencoded; charset=UTF-8';
		var processData_eval = (typeof ajaxobj.processData !== "undefined") ? ajaxobj.processData : true;

		$.ajax({
	  	type: ajaxobj.type,
	  	async: async_eval,
	  	url: request_url + ajaxobj.url,
    	cache: cache_eval,
      contentType: contentType_eval,
      processData: processData_eval,
	  	data: ajaxobj.data
		}).done(function(datasuccess) {

			if (datasuccess.status !== 200)
			{
				window[ajaxobj.error](datasuccess);
				return;
			}

			window[ajaxobj.success](datasuccess);

		}).fail(function(dataerror) { 

			window[ajaxobj.error](dataerror);

		});
	};

	$(".logout_link").on("click", function()
	{
    var req = {};
    req.url = '/user/logout';
    req.type = 'GET';
    req.success = "success_logout";
    req.error = "error_logout";
    ajaxp(req);
	}); 

	$(".advanced_search_link_show").on("click", function()
	{
    advanced_searching = true;
    $('#search_button_at_simple').parent().addClass("hidden");
    $('#search_button_at_advance').parent().removeClass("hidden");
    $(".advanced_search_link_show").addClass("hidden");
    $(".advanced_search_link_hide").removeClass("hidden");
    $(".content_page_menu_div_advanced_search").removeClass("hidden");
    if (!loaded_advanced_searching_data)
    {
      get_advanced_search_ajax_data();
    }
	});

  $(".advanced_search_link_hide").on("click", function()
  {
    advanced_searching = false;
    $('#search_button_at_simple').parent().removeClass("hidden");
    $('#search_button_at_advance').parent().addClass("hidden");
    $(".advanced_search_link_hide").addClass("hidden");
    $(".advanced_search_link_show").removeClass("hidden");
    $(".content_page_menu_div_advanced_search").addClass("hidden");
    clear_search();
  });

	$(".home_select_language").on("change", function()
	{
		window.location = baseurl + "/"+ $(this).attr("selected","selected").val() +"/home";
	});

  $('.advanced_search_country').on("change", function()
  {
    reset_advanced_search_select();

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

  $('.search_button').on("click", function()
  {
    search_props();
  });

  $('#header_button_props').on("click", function()
  {
    window.location = baseurl + "/" + m_lang + "/prop";
  });

  $('#header_button_messages').on("click", function()
  {
    window.location = baseurl + "/" + m_lang + "/messages";
  });

  $('#header_button_profile').on("click", function()
  {
    window.location = baseurl + "/" + m_lang + "/editprofile";
  });

  $('#header_button_myprops').on("click", function()
  {
    window.location = baseurl + "/" + m_lang + "/myprops";
  });

  $('#header_button_favorites').on("click", function()
  {
    window.location = baseurl + "/" + m_lang + "/favorites";
  });

  $('#header_button_premium').on("click", function()
  {
    window.location = baseurl + "/" + m_lang + "/premium";
  });

  $('.notify_button_accept').on("click", function()
  {
    var id = $(this).attr("id");
    var id_arr = id.split("_");
    id = id_arr[2];
    close_notification(id);
  });

  if (!maintenance_page_showed()) 
  {
    is_on_maintenance();

    get_statistics();

  	if (logged())
  	{
  		get_num_favorites();
  		get_num_myprops();

      if (!messages_page_showed())
      {
        chating_now = null;
        localStorage.setItem("chating_now",chating_now);
      }

      actualize_messages_info();

      clearInterval(interval_actualize_chat);
      interval_actualize_chat = setInterval(function() {
        actualize_messages_info();
      }, TIME_SECONDS_ACTUALIZE_MESSAGES_INFO * 1000);
  	}
  }
  else
  {
    hide_statistics();
  }
});

function is_on_maintenance()
{
  var req_props = {};
  req_props.url = '/maintenance/on_maintenance';
  req_props.type = 'GET';
  req_props.success = "success_is_on_maintenance";
  req_props.error = "error_is_on_maintenance";
  ajaxp(req_props);
}

function success_is_on_maintenance(data)
{
  var on_maint = data.result;

  if (on_maint === "1")
  {
    if (logged())
    {
      $(".logout_link").trigger('click');
    }

    if (!about_page_showed() && !help_page_showed() && !useconditions_page_showed() && !privacy_page_showed())
    {
      window.location = baseurl + "/" + m_lang + "/maintenance";
    }
  }
}

function error_is_on_maintenance(data)
{
  
}

function logged()
{
	var user_id = $(".gdata_user_id").val();
	return ((typeof user_id !== "undefined") && (user_id !== null) && (user_id !== ''));
}

function success_logout(data)
{
  if ((typeof interval_actualize_chat !== "undefined") && (interval_actualize_chat !== null) && (interval_actualize_chat !== ''))
  {
    clearInterval(interval_actualize_chat);
  }

  clear_search();
  clear_localstorage();
	window.location = baseurl + "/" + m_lang + "/login";
}

function error_logout(data)
{

}

function get_statistics()
{
	var user_country_id = $(".gdata_country_id").val();
	var user_state_id = $(".gdata_state_id").val();

	var req_props = {};
  req_props.url = '/user/get_total_users';
  req_props.type = 'GET';
  req_props.success = "success_get_total_users";
  req_props.error = "error_get_total_users";
  ajaxp(req_props);

  if (logged())
  {
  	req_props = {};
    req_props.url = '/user/get_total_users_state/'+user_country_id+'/'+user_state_id;
    req_props.type = 'GET';
    req_props.success = "success_get_total_users_state";
    req_props.error = "error_get_total_users_state";
    ajaxp(req_props);

  	req_props = {};
    req_props.url = '/user/get_total_users_state_online/'+user_country_id+'/'+user_state_id;
    req_props.type = 'GET';
    req_props.success = "success_get_total_users_state_online";
    req_props.error = "error_get_total_users_state_online";
    ajaxp(req_props);
  }

	req_props = {};
  req_props.url = '/proposal/get_total_props';
  req_props.type = 'GET';
  req_props.success = "success_get_total_props";
  req_props.error = "error_get_total_props";
  ajaxp(req_props);

  if (logged())
  {
  	req_props = {};
    req_props.url = '/proposal/get_total_props_state/'+user_country_id+'/'+user_state_id;
    req_props.type = 'GET';
    req_props.success = "success_get_total_props_state";
    req_props.error = "error_get_total_props_state";
    ajaxp(req_props);

  	req_props = {};
    req_props.url = '/proposal/get_total_props_state_today/'+user_country_id+'/'+user_state_id;
    req_props.type = 'GET';
    req_props.success = "success_get_total_props_state_today";
    req_props.error = "error_get_total_props_state_today";
    ajaxp(req_props);  
  }      
}

function success_get_total_users(data)
{
	$(".page_total_users").text(data.result);
}

function error_get_total_users(data)
{
	$(".page_total_statistics").hide();
}

function success_get_total_users_state(data)
{
	$(".page_total_users_state").text(data.result);
}

function error_get_total_users_state(data)
{
	$(".page_total_statistics_state").hide();
}

function success_get_total_users_state_online(data)
{
	$(".page_total_users_state_online").text(data.result);
}

function error_get_total_users_state_online(data)
{
	$(".page_total_statistics_state").hide();
}

function success_get_total_props(data)
{
	$(".page_total_props").text(data.result);
}

function error_get_total_props(data)
{
	$(".page_total_statistics").hide();
}

function success_get_total_props_state(data)
{
	$(".page_total_props_state").text(data.result);
}

function error_get_total_props_state(data)
{
	$(".page_total_statistics_state").hide();
}

function success_get_total_props_state_today(data)
{
	$(".page_total_props_state_today").text(data.result);
}

function error_get_total_props_state_today(data)
{
	$(".page_total_statistics_state").hide();
}

Array.prototype.in_array = function(elem)
{ 
  return ("#"+this.join("#")+"#").indexOf("#"+elem+"#") > -1;
} 

function show_datetime_proposals(datetime)
{
  var now = new Date();

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

  var date_proposal = new Date(d_year, d_month, d_day, t_hour, t_minutes, t_seconds);

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

  if (DiffDays(date_proposal, now) >= 2)
  {
  	if (date_format['ytod'].in_array(m_lang))
  	{
  		return d_year+"/"+d_month+"/"+d_day;
  	}
    return d_day+"/"+d_month+"/"+d_year;
  }
  else if (DiffDays(date_proposal, now) == 1)
  {
    return lang('p_yesterday') + ", "+t_hour+":"+t_minutes;
  }
  else
  {
    return lang('p_today') + ", "+t_hour+":"+t_minutes;
  }
}

function DaysOfYear(nYear) 
{
  if ((nYear % 4) == 0) 
  {
    return 366;
  }
  else 
  {
    return 365;
  }
}

function DiffDays(fDate, tDate) 
{
  var fYear = fDate.getFullYear();
  var tYear = tDate.getFullYear();
  var fMonth = fDate.getMonth();
  var tMonth = tDate.getMonth();
  var fDay = fDate.getDate();
  var tDay = tDate.getDate();
  var nDays = 0;

  if (tYear > fYear) 
  {
    nDays = DaysOfYear(fYear) - YTD_Days(fDate);
    for (y = (fYear + 1); y < tYear; y++) 
    {
      nDays = nDays + DaysOfYear(y);
    }
    nDays = nDays + YTD_Days(tDate);
  }
  else 
  {   
    nDays = YTD_Days(tDate) - YTD_Days(fDate);
  };
  return nDays;
}

function YTD_Days(dDate) 
{
  var y = dDate.getFullYear();
  var m = dDate.getMonth();
  var nDays = 0

  for (i = 0; i < m; i++) 
  {
    switch (i) 
    {
      case 0:  
      case 2:  
      case 4: 
      case 6: 
      case 7:  
      case 9:  
      case 11: 
        nDays = nDays + 31;
        break;
      case 1:    
        if ((y % 4) == 0) 
        {
          nDays = nDays + 29;
        }
        else 
        {
          nDays = nDays + 28;
        };
        break;
      case 3:    
      case 5:   
      case 8: 
      case 10:  
        nDays = nDays + 30;
        break;
    }
  }
  nDays = nDays + dDate.getDate();
  return nDays;
}

function get_num_favorites()
{
	var user_id = $(".gdata_user_id").val();

	var req = {};
  req.url = '/favorite/get_from_user/'+user_id;
  req.type = 'GET';
  req.success = "success_get_num_favorites";
  req.error = "error_get_num_favorites";
  ajaxp(req);
}

function success_get_num_favorites(data)
{
	$(".menu_favorites_button_count").text(data.count);
}

function error_get_num_favorites(data)
{
	$(".menu_favorites_button_count").text(0);
}

function get_num_myprops()
{
	var user_id = $(".gdata_user_id").val();

	var req = {};
  req.url = '/proposal/get_total_from_user/'+user_id;
  req.type = 'GET';
  req.success = "success_get_num_myprops";
  req.error = "error_get_num_myprops";
  ajaxp(req);
}

function success_get_num_myprops(data)
{
	$(".menu_myprops_button_count").text(data.result);
}

function error_get_num_myprops(data)
{
	$(".menu_myprops_button_count").text(0);
}

function get_advanced_search_ajax_data()
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
  $(".advanced_search_categories_select").prop("disabled",true);

  $.each(data.result, function (category_id) {
    var category = data.result[category_id];
    $('.advanced_search_categories_select').append($('<option></option>'));
    $('.advanced_search_categories_select option:last').attr("value", category.id).text(category.name);
  });

  $(".advanced_search_categories_select").prop("disabled",false);

  get_advanced_search_localizations();
}

function error_get_all_categories(data)
{
  $(".advanced_search_categories_select").prop("disabled",true);
}

function get_advanced_search_localizations()
{
  var req = {};
  req.url = '/country/all';
  req.type = 'GET';
  req.success = "advanced_search_success_get_countries";
  req.error = "advanced_search_error_get_countries";
  ajaxp(req);
}

function advanced_search_success_get_countries(data)
{
  $(".advanced_search_country").prop("disabled",true);

  $.each(data.result, function (country_id) {
    var country = data.result[country_id];
    $('.advanced_search_country').append($('<option></option>'));
    $('.advanced_search_country option:last').attr("value", country.id).attr("data-image", baseurl + "/assets/icons/flags/"+get_icon_flag_name(country.tag)+".png").text(country.name);
  });

  $('.advanced_search_country').prop('disabled', false);

  loaded_advanced_searching_data = true;

  var local_storage_item = localStorage.getItem("search_object");

  if (typeof local_storage_item !== "undefined" && local_storage_item !== null && local_storage_item !== '')
  {
    var search = JSON.parse(local_storage_item);

    put_advanced_search_data_in_search_box();

    var country_id = search.s_country_id;
    if (typeof country_id !== "undefined" && country_id !== null && country_id !== '')
    {
      var req = {};
      req.url = '/state/all_from_country/'+country_id;
      req.type = 'GET';
      req.success = "success_get_states";
      req.error = "error_get_states";
      ajaxp(req);
    }
  }
}

function advanced_search_error_get_countries(data)
{
  $(".advanced_search_country").prop("disabled",true);
  $(".advanced_search_state").prop("disabled",true);
}

function reset_advanced_search_select()
{
  $('.advanced_search_state').val('');
  $(".advanced_search_state option").not(":eq(0)").remove(); 
  $('.advanced_search_state').prop("disabled",true);
}

function success_get_states(data)
{
  $('.advanced_search_state').prop('disabled', true);
  $.each(data.result, function (state_id) {
    var state = data.result[state_id];
    $('.advanced_search_state').append($('<option></option>').attr("value", state.id).text(state.name));
  });
  $('.advanced_search_state').prop('disabled', false);

  var local_storage_item = localStorage.getItem("search_object");

  if (typeof local_storage_item !== "undefined" && local_storage_item !== null && local_storage_item !== '')
  {
    var search = JSON.parse(local_storage_item);

    put_advanced_search_data_in_search_box();
  }  
}

function error_get_states(data)
{
  $('.advanced_search_state').prop('disabled', true);
}

function search_props()
{
  if (advanced_searching)
  {
    var user_id = $(".gdata_user_id").val();

    var req = {};
    req.url = '/premium/get/'+user_id;
    req.type = 'GET';
    req.success = "success_get_premium";
    req.error = "error_get_premium";
    ajaxp(req);    
  }
  else
  {
    if ($('.search_simple_input').val() !== '')
    {
      var data_search = {};
      data_search.s_search = clean_field($('.search_simple_input').val());

      search_redirect_to_props_page(data_search);
    }
    else
    {
      search_object = '';
      localStorage.setItem("search_object",'');
      window.location = baseurl + "/"+ m_lang +"/prop";
    }
  }
}

function success_get_premium(data)
{
  if (data.result !== null)
  {
    var data_search = {};
    data_search.s_search = $('.search_simple_input').val();
    data_search.s_categories = build_advanced_search_categories_token();
    data_search.s_from_date = get_advanced_search_from_date();
    data_search.s_to_date = get_advanced_search_to_date();
    data_search.s_country_id = $('.advanced_search_country').val();
    data_search.s_state_id = $('.advanced_search_state').val();

    search_redirect_to_props_page(data_search);
  }
  else
  {
    var conf_data = {};
    conf_data.title = lang('p_search');
    conf_data.body = lang("p_for_search_be_premium");
    conf_data.cancel_text = lang('p_want_to_be_premium');
    conf_data.ok_text = lang('p_accept');
    conf_data.cancel = "popup_want_to_be_premium";
    conf_data.ok = "hide_confirm";
    show_confirm(conf_data);
  }
}

function error_get_premium(data)
{

}

function popup_want_to_be_premium()
{
  hide_confirm();
  window.location = baseurl + "/"+ m_lang +"/premium";
}

function build_advanced_search_categories_token()
{
  var categories = $('.advanced_search_categories_select').val();

  if (categories !== null)
  {
    var categories_str = categories.join(":");
    return categories_str;
  }
  return null;
}

function get_advanced_search_from_date()
{
  var day = $('.advanced_search_select_day_from').val();
  var month = $('.advanced_search_select_month_from').val();
  var year = $('.advanced_search_select_year_from').val();

  if ((day !== null) && (day !== '') && (month !== null) && (month !== '') && (year !== null) && (year !== ''))
  {
    return year + "-" + month + "-" + day;
  }
  return null;
}

function get_advanced_search_to_date()
{
  var day = $('.advanced_search_select_day_to').val();
  var month = $('.advanced_search_select_month_to').val();
  var year = $('.advanced_search_select_year_to').val();

  if ((day !== null) && (day !== '') && (month !== null) && (month !== '') && (year !== null) && (year !== ''))
  {
    return year + "-" + month + "-" + day;
  }
  return null;
}

function supports_html5_storage() 
{
  try 
  {
    return ('localStorage' in window && window['localStorage'] !== null);
  } 
  catch (e) 
  {
    return false;
  }
}

function search_redirect_to_props_page(search_obj)
{
  localStorage.setItem("search_object", JSON.stringify(search_obj));
  window.location = baseurl + "/"+ m_lang +"/prop";
}

function clear_search()
{
  delete localStorage["search_object"];
}

function put_advanced_search_data_in_search_box()
{
  var date_part_arr, d_year, d_month, d_day;

  var local_storage_item = localStorage.getItem("search_object");
  var search = JSON.parse(local_storage_item);

  if (typeof search.s_search !== "undefined" && search.s_search !== null && search.s_search !== '')
  {
    $('.search_simple_input').val(search.s_search);
  }
  if (typeof search.s_categories !== "undefined" && search.s_categories !== null && search.s_categories !== '')
  {
    $('.advanced_search_categories_select').val(search.s_categories);
  }
  if (typeof search.s_from_date !== "undefined" && search.s_from_date !== null && search.s_from_date !== '')
  {
    date_part_arr = search.s_from_date.split("-");
    d_year = date_part_arr[0];
    d_month = date_part_arr[1];
    if (d_month.length === 1) { d_month = "0"+d_month; }
    d_day = date_part_arr[2];

    $('.advanced_search_select_day_from').val(d_day);
    $('.advanced_search_select_month_from').val(d_month);
    $('.advanced_search_select_year_from').val(d_year);
  }
  if (typeof search.s_to_date !== "undefined" && search.s_to_date !== null && search.s_to_date !== '')
  {
    date_part_arr = search.s_to_date.split("-");
    d_year = date_part_arr[0];
    d_month = date_part_arr[1];
    if (d_month.length === 1) { d_month = "0"+d_month; }
    d_day = date_part_arr[2];

    $('.advanced_search_select_day_to').val(d_day);
    $('.advanced_search_select_month_to').val(d_month);
    $('.advanced_search_select_year_to').val(d_year);
  }
  if (typeof search.s_country_id !== "undefined" && search.s_country_id !== null && search.s_country_id !== '')
  {
    $('.advanced_search_country').val(search.s_country_id);
  }
  if (typeof search.s_state_id !== "undefined" && search.s_state_id !== null && search.s_state_id !== '')
  {
    $('.advanced_search_state').val(search.s_state_id);
  }
}

function put_simple_search_data_in_search_box()
{
  var local_storage_item = localStorage.getItem("search_object");
  var search = JSON.parse(local_storage_item);

  if (typeof search.s_search !== "undefined" && search.s_search !== null && search.s_search !== '')
  {
    $('.search_simple_input').val(search.s_search);
  }
}

function invalid_navigator_page()
{
  window.location = baseurl + "/" + m_lang + "/invalidnavigator";
}

function clear_localstorage()
{
  localStorage.clear();
}

function contact_page_showed()
{
  return ((typeof $(".page_contact_form_div").get(0)) !== "undefined");
}

function maintenance_page_showed()
{
  return ((typeof $(".page_info_maintenance_zone").get(0)) !== "undefined");
}

function about_page_showed()
{
  return ((typeof $(".page-about").get(0)) !== "undefined");
}

function help_page_showed()
{
  return ((typeof $(".page-help").get(0)) !== "undefined");
}

function useconditions_page_showed()
{
  return ((typeof $(".page-useconditions").get(0)) !== "undefined");
}

function privacy_page_showed()
{
  return ((typeof $(".page-privacy").get(0)) !== "undefined");
}

function hide_statistics()
{
  $(".div_statistics").html('');
}

function get_icon_flag_name(tag)
{
  return tag.substring(1).toLowerCase();
}

function messages_page_showed()
{
  return ((typeof $(".div_content_chat_users_list").get(0)) !== "undefined");
}

function actualize_messages_info()
{
  var user_id = $(".gdata_user_id").val();
  var chating_now = localStorage.getItem("chating_now");
  var users_list_count = localStorage.getItem("users_list_count");
  var messages_list_count = localStorage.getItem("messages_list_count");
  var user_message = localStorage.getItem("user_message");
  var writing = localStorage.getItem("writing");
  var delete_user_conversation = localStorage.getItem("delete_user_conversation");
  var last_id_message_printed = localStorage.getItem("last_id_message_printed");

  if (typeof users_list_count === "undefined") users_list_count = DEFAULT_NUMBER_USERS_MESSAGED_SHOWED;
  if (typeof messages_list_count === "undefined") messages_list_count = DEFAULT_NUMBER_MESSAGES_SHOWED;

  if (typeof chating_now === "undefined") chating_now = null;

  if ((typeof user_message !== "undefined") && (user_message !== null)) user_message = user_message.substring(1,user_message.length-1);

  var data_post = {};
  data_post.user_from_id = user_id;
  data_post.user_to_id = chating_now;
  data_post.users_list_count = users_list_count;
  data_post.messages_list_count = messages_list_count;
  data_post.user_message = user_message;
  data_post.writing = writing;
  data_post.delete_user_conversation = delete_user_conversation;
  data_post.last_id_message_printed = last_id_message_printed;
  data_post.filter_t = $('input[name=filter_t]').val();

  var req = {};
  req.url = '/message/actualize_messages_info';
  req.type = 'POST';
  req.success = "success_actualize_messages_info";
  req.error = "error_actualize_messages_info";
  req.data = data_post;
  ajaxp(req);

  delete localStorage["user_message"];
  delete localStorage["delete_user_conversation"];
}

function success_actualize_messages_info(data)
{ 
  data = data.result;

  save_localstorage_messages_info_data(data);

  if ((data.no_readen_messages > 0) && (data.no_readen_messages > get_last_no_readen_messages()))
  {
    play_messages_sound(); 
  }
  
  set_last_no_readen_messages(data.no_readen_messages);
  print_messages_no_readen_in_messages_text(data.no_readen_messages);

  if (messages_page_showed())
  {
    get_localstorage_messages_info_data();
    
    if (empty_user_list()) 
    {
      print_user_list();
    }
    else
    {
      actualize_print_user_list();
    }

    var chating_now = localStorage.getItem("chating_now");
    if ((chating_now === null) || (chating_now === 'null'))
    {
      select_first_user_in_user_list();
    }
    else
    {
      select_user_in_user_list();
    }

    load_chat_title();
    actualize_conversation();

    if (typeof data.user_chating_now !== "undefined")
    {
      actualize_conversation_with_readen(data.user_chating_now.last_message_readen);
    }

    actualize_users_list_with_no_readen_messages();
    check_if_user_writing_me();
  }
}

function error_actualize_messages_info(data)
{
  
}

function get_last_no_readen_messages()
{
  var no_readen_messages = localStorage.getItem("last_no_readen_messages");
  if (no_readen_messages === null || typeof no_readen_messages === "undefined")
  {
    var no_readen_messages_in_text = $(".messages_main_link_page_text_messages_noreaden").text();
    if (no_readen_messages_in_text === '') 
    {
      no_readen_messages = 0;
    }
    else
    {
      no_readen_messages = no_readen_messages_in_text;
    }  
  } 
  return parseInt(no_readen_messages);
}

function set_last_no_readen_messages(no_readen)
{
  localStorage.setItem("last_no_readen_messages",no_readen);
}

function save_localstorage_messages_info_data(data)
{
  delete localStorage["data_actualize_messages_info"];
  localStorage.setItem("data_actualize_messages_info",JSON.stringify(data));
}

function print_messages_no_readen_in_messages_text(no_readen_messages)
{
  if (no_readen_messages === 0)
  {
    $(".messages_main_link_page_text_messages_noreaden").text("");
    $(".messages_main_link_page_text_messages_noreaden").addClass("hidden");
    return;
  }

  $(".messages_main_link_page_text_messages_noreaden").text(no_readen_messages);
  $(".messages_main_link_page_text_messages_noreaden").removeClass("hidden");
}

function play_messages_sound()
{
  var snd = new Audio(baseurl + "/assets/sounds/message.ogg");
  snd.play();
}

function clean_field(text)
{
  var text_mod = $.trim(text);
  return text_mod;
}

function close_notification(id)
{
  var user_id = $(".gdata_user_id").val();

  var data_post = {};
  data_post.user_id = user_id;
  data_post.notify_id = id;
  data_post.filter_t = $('input[name=filter_t]').val();

  var req = {};
  req.url = '/notify/set_readen';
  req.type = 'POST';
  req.success = "success_notification_set_readen";
  req.error = "error_notification_set_readen";
  req.data = data_post;
  ajaxp(req);
}

function success_notification_set_readen(data)
{
  var id = data.result;

  $("#div_notify_text_"+id).remove();

  if ($(".content_div_notification_to_user").children().length === 0) $(".content_div_notification_to_user").addClass('hidden');
}

function error_notification_set_readen(data)
{

}