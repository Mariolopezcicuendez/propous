var data_proposal = null;
var proposal_id = null;
var favorite_id = null;

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

  $('.viewprop_addfavorite_prop_link').on("click", function()
  {
    if ($(this).hasClass('viewprop_addfavorite_prop_link_add'))
    {
      save_prop_to_favorites();
    }
    else if ($(this).hasClass('viewprop_addfavorite_prop_link_delete'))
    {
      delete_prop_from_favorites();
    }
  });

  $('.viewprop_viewuser_prop_link').on("click",function() 
  {
    localStorage.setItem("user_id", data_proposal.user_id);
  	window.location = baseurl + "/" + m_lang + "/viewprofile";
  });

  $('.viewprop_sendmessage_prop_link').on("click",function() 
  {
    localStorage.setItem("chating_now",data_proposal.user_id)
  	window.location = baseurl + "/" + m_lang + "/messages";
  });

  get_proposal_data();
  add_one_visited();
	get_proposal_photos();
	get_proposal_category();
	get_favorites();
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

function success_get_proposal_data(data)
{
	data_proposal = data.result;

	put_proposal_data_in_form();
}	

function put_proposal_data_in_form()
{
	$('.viewprop_prop_title').text(data_proposal.proposal);
  if (data_proposal.description !== null)
  {
    $('.viewprop_prop_description').text(data_proposal.description);
    $('.viewprop_prop_description').removeClass("hidden");
    $('.viewprop_prop_description').parent().parent().removeClass("hidden");
  }
	$('.viewprop_prop_creationdate').text(format_date_creation(data_proposal.time));
	$('.viewprop_prop_region').html("<img class='flag' src='"+baseurl + "/assets/icons/flags/"+get_icon_flag_name(data_proposal.country_tag)+".png'/> "+data_proposal.country[0].name + " / "+data_proposal.state[0].name);
  $('.viewprop_prop_visited').text(data_proposal.visited);
}

function error_get_proposal_data()
{
  show_fail("editprop_data_alert", lang('p_proposal_data_not_load'));
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
    $(".viewprop_photos_carousel").children().remove();
    $(".viewprop_photos_carousel").append('<div id="viewprop_carousel"></div>');
    $(".viewprop_photos_carousel").append('<div class="clearfix"></div>');
    $(".viewprop_photos_carousel").append('<a class="prev" id="foo1_prev" href="#"><span>prev</span></a>');
    $(".viewprop_photos_carousel").append('<a class="next" id="foo1_next" href="#"><span>next</span></a>');

    $(".viewprop_photos_text_no_photos").text("");
    $(".viewprop_photos_text_no_photos").addClass("hidden");

    $.each(data.result, function (photo_id) {
      var photo = data.result[photo_id];

      var div_images = $('<div></div>').addClass("thumbnail").addClass("form-inline");
      $("#viewprop_carousel").append(div_images);

      var link_to_box = $('<a class="fancybox" rel="group" href="'+baseurl + photo.route+'"></a>');
      $(div_images).append(link_to_box);

      $(link_to_box).append($('<img></img>').addClass("viewprop_photos_carousel_img").addClass("img-rounded").attr("id", "viewprop_photos_carousel_img"+photo.id).attr("src", baseurl + photo.thumbnail));      
    });

    $(".viewprop_photos_carousel").removeClass("hidden");

    $("#viewprop_carousel").carouFredSel({
      items   : PHOTOS_IN_CARROUSEL,
      auto    : false,
      prev    : "#foo1_prev",
      next    : "#foo1_next"
    });
    $(".fancybox").fancybox();
  }
  else
  {
    $(".viewprop_photos_text_no_photos").text(lang('p_proposal_without_photos'));
    $(".viewprop_photos_text_no_photos").removeClass("hidden");
    $(".viewprop_photos_carousel").addClass("hidden");
  } 
}

function error_get_proposal_photos(data)
{
  $(".viewprop_photos_carousel").addClass("hidden");
  show_fail("viewprop_photo_alert", data.error.message);
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
    $(".viewprop_category_images").children().remove();

    $(".viewprop_category_text_no_categories").text("");
    $(".viewprop_category_text_no_categories").addClass("hidden");   

    $.each(data.result, function (category_id) {
      var category = data.result[category_id];

      var div_images = $('<div></div>').addClass("thumbnail").addClass("form-inline");
      $(".viewprop_category_images").append(div_images);
      $(div_images).append($('<img></img>').attr("src", baseurl + "/assets/icons/category/" + get_name_category_from_tag(category.tag) + ".png"));

      var div_images_caption = $('<div></div>').addClass("caption");
      $(div_images).append(div_images_caption);

      var div_images_caption_p = $('<p></p>').text(category.name);
      $(div_images_caption).append(div_images_caption_p); 
    });

    $(".viewprop_category_images").removeClass('hidden');
  }
  else
  {
    $(".viewprop_category_text_no_categories").text(lang('p_proposal_without_categories'));
    $(".viewprop_category_text_no_categories").removeClass("hidden");
    $(".viewprop_category_images").addClass("hidden");
  }
}

function error_get_proposal_category(data)
{
  show_fail("viewprop_sociality_alert", data.error.message);
}

function get_name_category_from_tag(tag)
{
  return tag.substring(1).toLowerCase();
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

function save_prop_to_favorites()
{
	var user_id = $(".gdata_user_id").val();

  var data_post = {};
  data_post.proposal_id = proposal_id;
  data_post.filter_t = $('input[name=filter_t]').val();
  
  var req = {};
  req.url = '/favorite/add_favorite/'+user_id;
  req.type = 'POST';
  req.success = "success_save_prop_to_favorites";
  req.error = "error_save_prop_to_favorites";
  req.data = data_post;
  ajaxp(req);
}

function delete_prop_from_favorites()
{
	if (favorite_id !== null)
	{
	  var req = {};
	  req.url = '/favorite/delete_favorite/'+favorite_id;
	  req.type = 'GET';
	  req.success = "success_delete_prop_from_favorites";
	  req.error = "error_delete_prop_from_favorites";
	  ajaxp(req);
	}
}

function success_save_prop_to_favorites(data)
{
  if ((typeof data.result !== "undefined") && (data.result !== null))
  {
  	favorite_id = data.result.id;

    var image_icon = "<img class='image_icon' src='"+ baseurl + "/assets/icons/actions/favorite_off.png'/> ";

    $('.viewprop_addfavorite_prop_link').removeClass('viewprop_addfavorite_prop_link_add').addClass('viewprop_addfavorite_prop_link_delete');
    $('.viewprop_addfavorite_prop_link').html(image_icon + lang('p_favorite_on'));
    get_num_favorites();
  }
}

function error_save_prop_to_favorites(data)
{

}

function success_delete_prop_from_favorites(data)
{
	if ((typeof data.result !== "undefined") && (data.result !== null))
  {
	  favorite_id = null;

    var image_icon = "<img class='image_icon' src='"+ baseurl + "/assets/icons/actions/favorite_on.png'/> ";

    $('.viewprop_addfavorite_prop_link').removeClass('viewprop_addfavorite_prop_link_delete').addClass('viewprop_addfavorite_prop_link_add');
    $('.viewprop_addfavorite_prop_link').html(image_icon + lang('p_favorite_off'));
    get_num_favorites();
	}
}

function error_delete_prop_from_favorites(data)
{

}

function get_favorites()
{
  var user_id = $(".gdata_user_id").val();

  var req = {};
  req.url = '/favorite/get_from_user/'+user_id;
  req.type = 'GET';
  req.success = "success_get_proposal_favorites";
  req.error = "error_get_proposal_favorites";
  ajaxp(req);
}

function success_get_proposal_favorites(data)
{
  if ((typeof data.result !== "undefined") && (data.result !== null))
  {
    $.each(data.result, function (i) {   
    	if (data.result[i].proposal_id == proposal_id)
    	{
    		favorite_id = data.result[i].id;

        var image_icon = "<img class='image_icon' src='"+ baseurl + "/assets/icons/actions/favorite_off.png'/> ";

    		$('.viewprop_addfavorite_prop_link').removeClass('viewprop_addfavorite_prop_link_add').addClass('viewprop_addfavorite_prop_link_delete');
    		$('.viewprop_addfavorite_prop_link').html(image_icon + lang('p_favorite_on'));
    	}
    });
  }
}

function error_get_proposal_favorites()
{
  
}

function add_one_visited()
{
  var req = {};
  req.url = '/proposal/add_one_visited/'+proposal_id;
  req.type = 'GET';
  req.success = "success_add_one_visited";
  req.error = "error_add_one_visited";
  ajaxp(req);
}

function success_add_one_visited(data)
{

}

function error_add_one_visited(data)
{
  
}