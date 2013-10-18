var data_table = [];
var oTable = {};
var oTableDone = {};
var prop_chosen = null;

$(document).ready(function() 
{
  get_favorites_props();
});

function get_favorites_props()
{
  var user_id = $(".gdata_user_id").val();

  var data_post = {};
  data_post.favorites = user_id;
  data_post.filter_t = $('input[name=filter_t]').val();
  
  var req = {};
  req.url = '/proposal/list_proposals';
  req.type = 'POST';
  req.success = "success_get_favorites_props";
  req.error = "error_get_favorites_props";
  req.data = data_post;
  ajaxp(req);
}

function success_get_favorites_props(data)
{
  $.each(data.result, function (prop_id) {
    var proposal = data.result[prop_id];
    data_table.push([
      get_proposal_main_view_link(proposal.id, proposal.proposal),
      (((typeof proposal.photos === "undefined") || (proposal.photos === null)) ? '' : get_photo_icon()), 
      show_datetime_proposals(proposal.time), 
      get_user_socials_in_proposal(proposal.user) + get_proposal_categories(proposal.categories), 
      get_actions_buttons(proposal.user_id, proposal.id),
      "<span class='hidden_popover_zone' prop_id='"+proposal.id+"'></span>"
    ]);
  });

  $('.favorites_main_table_props').dataTable({
    "bFilter": DATATABLES_SHOW_FILTER,
    "bSort": DATATABLES_SHOW_SORT,
    "bInfo": DATATABLES_SHOW_INFO,
    "bAutoWidth": DATATABLES_AUTO_WIDTH,
    "iDisplayLength": DATATABLES_DEFAULT_LENGHT_MENU,
    "sDom": '<"top"i>rt<"bottom"flp><"clear">',
    "bStateSave": DATATABLES_SAVE_COOKIE_STATE,
    "sPaginationType": DATATABLES_PAGINATION_TYPE,
    "oLanguage": {
      "sLengthMenu": lang('p_view') + " _MENU_ " + lang('p_records'),
      "sEmptyTable": lang('p_empty_table'),
      "oPaginate": {
        "sFirst": lang('p_first'),
        "sLast": lang('p_last'),
        "sNext": lang('p_next'),
        "sPrevious": lang('p_previous')
      }
    },
    "aLengthMenu": [DATATABLES_SHOW_LENGTH, DATATABLES_SHOW_LENGTH],
    "aaData": data_table,
    "fnDrawCallback": function( oSettings ) 
    {
      activate_datatable_events();
      stilize_datatable();
      load_popovers(data);
    }
  });
}

function load_popovers(data)
{
  $.each(data.result, function (prop_id) 
  {
    var proposal = data.result[prop_id];

    var options_popover = {};
    options_popover.html = true;
    options_popover.title = '';
    options_popover.content = build_proposal_popover_html(proposal);
    options_popover.placement = 'right';
    options_popover.container = 'body';
    set_popover('a.proposal_main_view_link[prop_id='+proposal.id+']',options_popover,'td.popover_zone[prop_id='+proposal.id+']');
  });
}

function build_proposal_popover_html(proposal)
{
  var html = "<p class='popover_body'>";

  html += "<span class='popover_body_ptext'>" + proposal.proposal + "</span><br/>";
  html += "<hr class='popover_body_phr'/>";
  html += "<strong>"+lang('p_proposal_creationdate')+":</strong> <span class='popover_body_pdate'>" + show_datetime_proposals(proposal.time) + "</span><br/>"; 
  if ((proposal.description !== null) && (typeof proposal.description !== "undefined")) 
  {
    html += "<strong>"+lang('p_proposal_description')+":</strong> <span class='popover_body_pdescription'>" + proposal.description + "</span><br/>"; 
  }
  html += "<strong>"+lang('p_localization')+":</strong> <img class='flag' src='"+baseurl + "/assets/icons/flags/"+get_icon_flag_name(proposal.country_tag)+".png'/> <span class='popover_body_plocalization'>" + proposal.country + " / " + proposal.state + "</span><br/>"; 
  html += "<strong>"+lang('p_visited')+":</strong> <span class='popover_body_pvisited'>" + proposal.visited + "</span><br/>"; 
  html += "<hr class='popover_body_phr'/>";
  html += "<strong>"+lang('p_photos')+":</strong> <span class='popover_body_pphotos'>" + (((proposal.photos !== null) && (typeof proposal.photos !== "undefined")) ? proposal.photos.length : 0 ) + "</span><br/>"; 
  html += "<hr class='popover_body_phr'/>";
  html += "<strong>"+lang('p_user')+":</strong> <span class='popover_body_pusername'>" + proposal.user.name + "</span><br/>"; 
  var online, tag_image, image_status;
  if (proposal.user.connected === true)
  {
    image_status = "<img class='status_image' src='"+baseurl+"/assets/icons/online.png' />";
    online = lang("p_connected");
    tag_image = "connected";
  }
  else
  {
    image_status = "<img class='status_image' src='"+baseurl+"/assets/icons/offline.png' />";
    online = lang("p_disconnected");
    tag_image = "disconnected";
  }
  html += "<strong>"+lang('p_status')+":</strong> <span class='popover_body_pstatus user_text_"+tag_image+"'>" + online + "</span> "+image_status+"<br/>";
  html += "<strong>"+lang('p_gender')+":</strong> <span class='popover_body_pgender'>" + ((proposal.user.sex === 'F') ? lang('p_female') : lang('p_male')) + "</span><br/>";
  if ((proposal.user.show_in_proposal !== null) && (typeof proposal.user.show_in_proposal !== "undefined")) 
  {
    html += "<strong>"+lang('p_social_main')+":</strong> <span class='popover_body_pusersocial'>" + proposal.user.show_in_proposal.name + "</span><br/>"; 
  }
  html += "<hr class='popover_body_phr'/>";
  html += "<strong>"+lang('p_categories')+":</strong> <span class='popover_body_pcategories'>" + (((proposal.categories !== null) && (typeof proposal.categories !== "undefined")) ? proposal.categories.length : 0 ) + "</span><br/>"; 
  if ((proposal.categories !== null) && (typeof proposal.categories !== "undefined")) 
  {
    $.each(proposal.categories, function (i) 
    {
      html += " <span class='popover_body_pcategoriesname'>- " + proposal.categories[i].name + "</span><br/>";
    });
  }
  html += "</p>";

  return html;
}

function activate_datatable_events()
{
  $.each($('.hidden_popover_zone'), function (prop_id) 
  {
    var prop_id = $(this).attr("prop_id");
    $(this).parent().addClass('popover_zone').attr("prop_id",prop_id);
  });
  
  $.each($('.prop_list_images_img_action_view_user'), function (prop_id) 
  {
    var prop_id = $(this).attr("prop_id");
    $(this).parent().parent().attr("prop_id",prop_id);
  });

  $('.prop_list_images_img_action_view_user').on("click", function()
  {
    var user_id = $(this).attr("user_id");
    localStorage.setItem("user_id", user_id);
    window.location = baseurl + "/" + m_lang + "/viewprofile";
  });

  $('.prop_list_images_img_action_view').on("click", function()
  {
    var prop_id = $(this).attr("prop_id");
    localStorage.setItem("proposal_id", prop_id);
    window.location = baseurl + "/" + m_lang + "/viewprop";
  });

  $('.proposal_main_view_link').on("click", function( event ) 
  {
    event.preventDefault();
    if (prop_chosen === null)
    {
      prop_chosen = $(this).attr("prop_id");
    }
    localStorage.setItem("proposal_id", prop_chosen);
    window.location = baseurl+"/"+m_lang+"/viewprop";
  });

  $('.proposal_main_view_link').on("mousedown", function( event ) 
  {
    prop_chosen = $(this).attr("prop_id");
    localStorage.setItem("proposal_id", prop_chosen);
  });

  get_proposal_favorites();
}

function stilize_datatable()
{
  $('.dataTables_wrapper div.bottom').addClass("container");
  $('select[name=favorites_main_table_props_length]').addClass("form-control").addClass("input_inline_3_elements_full").addClass("prop_list_select_length");
}

function get_photo_icon()
{
  return "<img class='prop_list_images_img' title='"+lang('p_prop_with_photos')+"' src='"+baseurl + "/assets/icons/photo.png"+"'></img>";
}

function error_get_favorites_props(data)
{

}

function get_user_socials_in_proposal(user)
{
  if (typeof user !== "undefined" && user !== null && user !== '')
  {
    var online, tag_image;
    if (user.connected === true)
    {
      online = lang("p_connected");
      tag_image = "connected";
    }
    else
    {
      online = lang("p_disconnected");
      tag_image = "disconnected";
    }

    var user_data = "";
    user_data += " <img class='prop_list_images_img' title='"+user.name+", "+online+"' src='"+baseurl + "/assets/icons/" + ((user.sex === 'F') ? "female" : "male") + "_"+tag_image.toLowerCase()+".png"+"'></img>" ;
    user_data += ((typeof user.show_in_proposal !== "undefined") && (user.show_in_proposal !== null)) ? " <img class='prop_list_images_img' title='"+user.show_in_proposal.name+"' src='"+baseurl + "/assets/icons/sociality/" + get_name_sociality_from_tag(user.show_in_proposal.tag) + ".png"+"'></img>" : "" ;
    return user_data;
  }
  return "";    
}

function get_proposal_categories(categories)
{
  var categ_data = "";
  if ((typeof categories !== "undefined") && (categories !== null))
  {
    $.each(categories, function (i) {   
      categ_data += " <img class='prop_list_images_img' title='"+categories[i].name+"' src='"+baseurl + "/assets/icons/category/" + get_name_sociality_from_tag(categories[i].tag) + ".png"+"'></img>";
    });
  }
  return categ_data;
}

function get_name_sociality_from_tag(tag)
{
  return tag.substring(1).toLowerCase();
}

function get_actions_buttons(user_id, prop_id)
{
  var view_user_button = "<img class='prop_list_images_img prop_list_images_img_action prop_list_images_img_action_view_user' user_id='"+user_id+"' prop_id='"+prop_id+"' title='"+lang('p_view_userprop')+"' src='"+baseurl + "/assets/icons/actions/viewuser.png'></img>";
  var favorite_button = "<img class='prop_list_images_img prop_list_images_img_action prop_list_images_img_action_favorite prop_list_images_img_action_favorite_off' prop_id='"+prop_id+"' favorite_id='' title='"+lang('p_favorite_off')+"' src='"+baseurl + "/assets/icons/actions/favorite_off.png'></img>";
  var view_button = "<img class='prop_list_images_img prop_list_images_img_action prop_list_images_img_action_view' prop_id='"+prop_id+"' title='"+lang('p_view_prop')+"' src='"+baseurl + "/assets/icons/actions/view.png'></img>";

  return favorite_button+" "+view_button+" "+view_user_button;
}

function get_proposal_main_view_link(prop_id, prop_text)
{
  var text_proposal = prop_text;
  if (text_proposal.length > PROPOSAL_NAME_MAX_SIZE_WRAP)
  {
    text_proposal = text_proposal.substring(0,PROPOSAL_NAME_MAX_SIZE_WRAP)+"...";
  }

  return "<a class='proposal_main_view_link' prop_id='"+prop_id+"' href='" + baseurl + "/" + m_lang + "/viewprop'>"+text_proposal+"</a>";
}

function save_prop_to_favorites(prop_id)
{
  var user_id = $(".gdata_user_id").val();

  var data_post = {};
  data_post.proposal_id = prop_id;
  data_post.filter_t = $('input[name=filter_t]').val();
  
  var req = {};
  req.url = '/favorite/add_favorite/'+user_id;
  req.type = 'POST';
  req.success = "success_save_prop_to_favorites";
  req.error = "error_save_prop_to_favorites";
  req.data = data_post;
  ajaxp(req);
}

function delete_prop_from_favorites(favorite_id)
{
  var req = {};
  req.url = '/favorite/delete_favorite/'+favorite_id;
  req.type = 'GET';
  req.success = "success_delete_prop_from_favorites";
  req.error = "error_delete_prop_from_favorites";
  ajaxp(req);
}

function success_save_prop_to_favorites(data)
{
  if ((typeof data.result !== "undefined") && (data.result !== null))
  {
    $('img.prop_list_images_img_action_favorite[prop_id='+data.result.proposal_id+']').removeClass('prop_list_images_img_action_favorite_off').addClass('prop_list_images_img_action_favorite_on');
    $('img.prop_list_images_img_action_favorite[prop_id='+data.result.proposal_id+']').attr("src",baseurl + "/assets/icons/actions/favorite_on.png");
    $('img.prop_list_images_img_action_favorite[prop_id='+data.result.proposal_id+']').attr("favorite_id",data.result.id);
    $('img.prop_list_images_img_action_favorite[prop_id='+data.result.proposal_id+']').attr("title",lang('p_favorite_on'));
    get_num_favorites();
  }
}

function error_save_prop_to_favorites(data)
{

}

function success_delete_prop_from_favorites(data)
{
  get_num_favorites();
  window.location = baseurl + "/" + m_lang + "/favorites";
}

function error_delete_prop_from_favorites(data)
{

}

function get_proposal_favorites()
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
      $('img.prop_list_images_img_action_favorite[prop_id='+data.result[i].proposal_id+']').removeClass('prop_list_images_img_action_favorite_off').addClass('prop_list_images_img_action_favorite_on');
      $('img.prop_list_images_img_action_favorite[prop_id='+data.result[i].proposal_id+']').attr("src",baseurl + "/assets/icons/actions/favorite_on.png");
      $('img.prop_list_images_img_action_favorite[prop_id='+data.result[i].proposal_id+']').attr("favorite_id",data.result[i].id);
      $('img.prop_list_images_img_action_favorite[prop_id='+data.result[i].proposal_id+']').attr("title",lang('p_favorite_on'));
    });
  }

  $('.prop_list_images_img_action_favorite').on("click", function()
  {
    if ($(this).hasClass('prop_list_images_img_action_favorite_off'))
    {
      var prop_id = $(this).attr("prop_id");
      save_prop_to_favorites(prop_id);
    }
    else if ($(this).hasClass('prop_list_images_img_action_favorite_on'))
    {
      var favorite_id = $(this).attr("favorite_id");
      delete_prop_from_favorites(favorite_id);
    }
  });
}

function error_get_proposal_favorites()
{
  
}