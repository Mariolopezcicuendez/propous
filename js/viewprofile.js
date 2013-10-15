var data_user = null;
var user_id = null;

$(document).ready(function() 
{
	user_id = localStorage.getItem("user_id");

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

  $('.viewprofile_sendmessage_profile_link').on("click",function() 
  {
    localStorage.setItem("chating_now",user_id)
  	window.location = baseurl + "/" + m_lang + "/messages";
  });

	get_user_data();
  get_user_spoken_languages();
  get_main_photo();
	get_user_photos();
	get_user_sociality();
  create_info_popover();
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

function success_get_user_data(data)
{
	data_user = data.result;

	put_user_data_in_form();
}

function put_user_data_in_form()
{
  var image_is_online;

	if (data_user.online) 
	{
    image_is_online = $('<img></img>').addClass('status_image').addClass('viewprofile_user_connected_status').attr('title',lang('p_connected')).attr('src',baseurl + '/assets/icons/online.png');
	}
  else
  {
    image_is_online = $('<img></img>').addClass('status_image').addClass('viewprofile_user_connected_status').attr('title',lang('p_disconnected')).attr('src',baseurl + '/assets/icons/offline.png')
  }
  $('.viewprofile_user_name').text(data_user.name+" ");
  $('.viewprofile_user_name').append(image_is_online);

	$('.viewprofile_age_data').text(calculate_age_from_birthdate());
	$('.viewprofile_user_gender').text((data_user.sex === 'M') ? lang('p_male') : lang('p_female') );
	$('.viewprofile_user_region').html("<img class='flag' src='"+baseurl + "/assets/icons/flags/"+get_icon_flag_name(data_user.country_tag)+".png'/> "+data_user.country[0].name + " / "+data_user.state[0].name);

	if (data_user.nationality !== null) 
	{
		$('.viewprofile_nationality').text(data_user.nationality);
		$('.viewprofile_nationality').removeClass("hidden");
		$('.viewprofile_nationality').parent().parent().removeClass("hidden");
	}
	if (data_user.dwelling !== null) 
	{
		$('.viewprofile_dwelling').text(data_user.dwelling);
		$('.viewprofile_dwelling').removeClass("hidden");
		$('.viewprofile_dwelling').parent().parent().removeClass("hidden");
	}
	if (data_user.car !== null) 
	{
		$('.viewprofile_car').text(data_user.car);
		$('.viewprofile_car').removeClass("hidden");
		$('.viewprofile_car').parent().parent().removeClass("hidden");
	}
	if (data_user.sexuality !== null) 
	{
		$('.viewprofile_sexuality').text(data_user.sexuality);
		$('.viewprofile_sexuality').removeClass("hidden");
		$('.viewprofile_sexuality').parent().parent().removeClass("hidden");
	}
	if (data_user.partner !== null) 
	{
		$('.viewprofile_partner').text(data_user.partner);
		$('.viewprofile_partner').removeClass("hidden");
		$('.viewprofile_partner').parent().parent().removeClass("hidden");
	}
	if (data_user.childrens !== null) 
	{
		$('.viewprofile_childrens').text(data_user.childrens);
		$('.viewprofile_childrens').removeClass("hidden");
		$('.viewprofile_childrens').parent().parent().removeClass("hidden");
	}
	if (data_user.occupation !== null) 
	{
		$('.viewprofile_occupation').text(data_user.occupation);
		$('.viewprofile_occupation').removeClass("hidden");
		$('.viewprofile_occupation').parent().parent().removeClass("hidden");
	}
	if (data_user.hobbies !== null) 
	{
		$('.viewprofile_hobbies').text(data_user.hobbies);
		$('.viewprofile_hobbies').removeClass("hidden");
		$('.viewprofile_hobbies').parent().parent().removeClass("hidden");
	}
	if (data_user.phone !== null) 
	{
		$('.viewprofile_phone').text(data_user.phone);
		$('.viewprofile_phone').removeClass("hidden");
		$('.viewprofile_phone').parent().parent().removeClass("hidden");
	}
	if (data_user.description !== null) 
	{
		$('.viewprofile_description').text(data_user.description);
		$('.viewprofile_description').removeClass("hidden");
		$('.viewprofile_description').parent().parent().removeClass("hidden");
	}
}

function error_get_user_data()
{
  show_fail("viewprofile_data_alert", lang('p_user_data_not_load'));
}

function calculate_age_from_birthdate()
{
	var birthdate = data_user.birthdate;
	var birthdate_year = birthdate.substring(0, 4);
	var birthdate_month = birthdate.substring(5, 7);
	var birthdate_day = birthdate.substring(8, 10);

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
    $(".viewprofile_photos_carousel").children().remove();
    $(".viewprofile_photos_carousel").append('<div id="viewprofile_carousel"></div>');
    $(".viewprofile_photos_carousel").append('<div class="clearfix"></div>');
    $(".viewprofile_photos_carousel").append('<a class="prev" id="foo1_prev" href="#"><span>prev</span></a>');
    $(".viewprofile_photos_carousel").append('<a class="next" id="foo1_next" href="#"><span>next</span></a>');

    $(".viewprofile_photos_text_no_photos").text("");
    $(".viewprofile_photos_text_no_photos").addClass("hidden");

    $.each(data.result, function (photo_id) {
      var photo = data.result[photo_id];

      var div_images = $('<div></div>').addClass("thumbnail").addClass("form-inline");
      $("#viewprofile_carousel").append(div_images);

      var link_to_box = $('<a class="fancybox" rel="group" href="'+baseurl + photo.route+'"></a>');
      $(div_images).append(link_to_box);

      $(link_to_box).append($('<img></img>').attr("class", "viewprofile_photos_carousel_img").attr("id", "viewprofile_photos_carousel_img"+photo.id).attr("src", baseurl + photo.thumbnail));
    });

    $(".viewprofile_photos_carousel").removeClass("hidden");

    $("#viewprofile_carousel").carouFredSel({
      items   : PHOTOS_IN_CARROUSEL,
      auto    : false,
      prev    : "#foo1_prev",
      next    : "#foo1_next"
    });
    $(".fancybox").fancybox();
  }
  else
  {
    $(".viewprofile_photos_text_no_photos").text(lang('p_user_without_photos'));
    $(".viewprofile_photos_text_no_photos").removeClass("hidden");
    $(".viewprofile_photos_carousel").addClass("hidden");
  }   
}

function error_get_user_photos(data)
{
  $(".viewprofile_photos_carousel").addClass("hidden");
  show_fail("viewprofile_photo_alert", data.error.message);
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
  $(".content_viewprofile_main_photo").attr("src",baseurl + data.result.route_main_for_user);
}

function error_get_main_photo(data)
{
  $(".content_viewprofile_main_photo").attr("src",baseurl + "/assets/photos/no_photo.png");
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
    $(".viewprofile_sociality_images").children().remove();

    $(".viewprofile_sociality_text_no_socialities").text("");
    $(".viewprofile_sociality_text_no_socialities").addClass("hidden");  

    $.each(data.result, function (sociality_id) {
      var sociality = data.result[sociality_id];

      var div_images = $('<div></div>').addClass("thumbnail").addClass("form-inline");
      $(".viewprofile_sociality_images").append(div_images);
      $(div_images).append($('<img></img>').attr("src", baseurl + "/assets/icons/sociality/" + get_name_sociality_from_tag(sociality.tag) + ".png"));

      var div_images_caption = $('<div></div>').addClass("caption");
      $(div_images).append(div_images_caption);

      var div_images_caption_p = $('<p></p>').text(sociality.name);
      $(div_images_caption).append(div_images_caption_p);
    });

    $(".viewprofile_sociality_images").removeClass('hidden');
  }
  else
  {
    $(".viewprofile_sociality_text_no_socialities").text(lang('p_user_without_socialities'));
    $(".viewprofile_sociality_text_no_socialities").removeClass("hidden");
    $(".viewprofile_sociality_images").addClass("hidden");
  }    
}

function error_get_user_sociality(data)
{
  $(".viewprofile_sociality_images").addClass('hidden');
  show_fail("editprofile_sociality_alert", data.error.message);
  $(".viewprofile_sociality_text_no_socialities").text(lang('p_user_without_socialities'));
  $(".viewprofile_sociality_text_no_socialities").removeClass("hidden");
}

function get_name_sociality_from_tag(tag)
{
  return tag.substring(1).toLowerCase();
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

function success_get_user_spoken_languages(data)
{
  if (data.count > 0)
  {
    $(".viewprofile_select_spoken").html('');

    $.each(data.result, function (i) {
      var spoken = data.result[i];
      $(".viewprofile_select_spoken").append($('<option></option>').attr("class", "viewprofile_select_spoken_option").attr("value", spoken.id).text(spoken.name + " ("+lang('p_'+spoken.quality)+")"));      
    });

    $('.viewprofile_spoken').parent().removeClass("hidden");
  }
  else
  {
    $(".viewprofile_select_spoken").html('');
  }
}

function error_get_user_spoken_languages(data)
{
  
}
