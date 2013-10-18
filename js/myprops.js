var data_table = [];
var prop_chosen = null;

$(document).ready(function() 
{
  $('.prop_new_prop').on("click",function() 
  {
    window.location = baseurl + "/" + m_lang + "/newprop";
  });

  get_my_props();
});

function get_my_props()
{
  var user_id = $(".gdata_user_id").val();

  var data_post = {};
  data_post.user_id = user_id;
  data_post.visibility = 'all';
  data_post.moderated_invalid = 'all';
  data_post.filter_t = $('input[name=filter_t]').val();
  
  var req = {};
  req.url = '/proposal/list_proposals';
  req.type = 'POST';
  req.success = "success_get_my_props";
  req.error = "error_get_my_props";
  req.data = data_post;
  ajaxp(req);
}

function success_get_my_props(data)
{
  $.each(data.result, function (prop_id) {
    var proposal = data.result[prop_id];
    data_table.push([
      get_proposal_main_edit_link(proposal.id, proposal.proposal, proposal.moderated_invalid),
      (((typeof proposal.photos === "undefined") || (proposal.photos === null)) ? '' : get_photo_icon()), 
      show_datetime_proposals(proposal.time), 
      get_proposal_categories(proposal.categories), 
      get_actions_buttons(proposal.user_id, proposal.id, proposal.visibility, proposal.moderated_invalid),
      "<span class='hidden_popover_zone' prop_id='"+proposal.id+"'></span>"
    ]);
  });

  $('.myprops_main_table_props').dataTable({
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
    set_popover('a.proposal_main_edit_link[prop_id='+proposal.id+']',options_popover,'td.popover_zone[prop_id='+proposal.id+']');
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

  if (proposal.moderated_invalid != "0")
  {
    html += "<hr class='popover_body_phr'/>";
    html += "<span class='popover_body_pmoderated_invalid'>" + lang("p_proposal_invalid") + "</span><br/>";
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
  
  $.each($('.prop_list_images_img_action_edit'), function (prop_id) 
  {
    var prop_id = $(this).attr("prop_id");
    $(this).parent().parent().attr("prop_id",prop_id);
  });

  $('.prop_list_images_img_action_edit').on("click", function()
  {
    var prop_id = $(this).attr("prop_id");
    localStorage.setItem("proposal_id", prop_id);
    window.location = baseurl + "/" + m_lang + "/editprop";
  });

  $('.prop_list_images_img_action_visible').on("click", function()
  {
    if ($(this).hasClass('prop_list_images_img_action_visible_off'))
    {
      var prop_id = $(this).attr("prop_id");
      save_prop_to_visible(prop_id);
    }
    else if ($(this).hasClass('prop_list_images_img_action_visible_on'))
    {
      var prop_id = $(this).attr("prop_id");
      delete_prop_from_visible(prop_id);
    }
  });

  $('.proposal_main_edit_link').on("click", function() 
  {
    event.preventDefault();
    if (prop_chosen === null)
    {
      prop_chosen = $(this).attr("prop_id");
    }
    localStorage.setItem("proposal_id", prop_chosen);
    window.location = baseurl+"/"+m_lang+"/editprop";
  });

  $('.proposal_main_edit_link').on("mousedown", function( event ) 
  {
    prop_chosen = $(this).attr("prop_id");
    localStorage.setItem("proposal_id", prop_chosen);
  });
}

function stilize_datatable()
{
  $('.dataTables_wrapper div.bottom').addClass("container");
  $('select[name=myprops_main_table_props_length]').addClass("form-control").addClass("input_inline_3_elements_full").addClass("prop_list_select_length");
}

function get_photo_icon()
{
  return "<img class='prop_list_images_img' title='"+lang('p_prop_with_photos')+"' src='"+baseurl + "/assets/icons/photo.png"+"'></img>";
}

function error_get_my_props(data)
{

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

function get_actions_buttons(user_id, prop_id, visibility, moderated_invalid)
{
  var edit_button = "<img class='prop_list_images_img prop_list_images_img_action prop_list_images_img_action_edit' prop_id='"+prop_id+"' title='"+lang('p_edit_prop')+"' src='"+baseurl + "/assets/icons/actions/edit.png'></img>";
  
  var visib_status = (visibility === "1") ? "on" : "off" ;
  var visible_button = "<img class='prop_list_images_img prop_list_images_img_action prop_list_images_img_action_visible prop_list_images_img_action_visible_"+visib_status+"' prop_id='"+prop_id+"' title='"+lang('p_visible_'+visib_status)+"' src='"+baseurl + "/assets/icons/actions/visible_"+visib_status+".png'></img>";

  if (moderated_invalid != "0")
  {
    return edit_button;
  }

  return edit_button+" "+visible_button;
}

function get_proposal_main_edit_link(prop_id, prop_text, moderated_invalid)
{
  var text_proposal = prop_text;
  if (text_proposal.length > PROPOSAL_NAME_MAX_SIZE_WRAP)
  {
    text_proposal = text_proposal.substring(0,PROPOSAL_NAME_MAX_SIZE_WRAP)+"...";
  }

  var invalid = "";
  if (moderated_invalid != "0")
  {
    invalid = "<img title='"+lang("p_proposal_invalid")+"' class='alert_image' src='"+baseurl + "/assets/icons/alert.png'/> ";
  }

  return invalid+"<a class='proposal_main_edit_link' prop_id='"+prop_id+"' href='" + baseurl + "/" + m_lang + "/editprop'>"+text_proposal+"</a>"
}

function save_prop_to_visible(prop_id)
{
  var data_post = {};
  data_post.proposal_visibility = 1;
  data_post.filter_t = $('input[name=filter_t]').val();
  
  var req = {};
  req.url = '/proposal/set_proposal_visibility/'+prop_id;
  req.type = 'POST';
  req.success = "success_save_prop_to_visible";
  req.error = "error_save_prop_to_visible";
  req.data = data_post;
  ajaxp(req);
}

function delete_prop_from_visible(prop_id)
{
  var data_post = {};
  data_post.proposal_visibility = 0;
  data_post.filter_t = $('input[name=filter_t]').val();
  
  var req = {};
  req.url = '/proposal/set_proposal_visibility/'+prop_id;
  req.type = 'POST';
  req.success = "success_delete_prop_from_visible";
  req.error = "error_delete_prop_from_visible";
  req.data = data_post;
  ajaxp(req);
}

function success_save_prop_to_visible(data)
{
  if ((typeof data.result !== "undefined") && (data.result !== null))
  {
    $('img.prop_list_images_img_action_visible[prop_id='+data.result[0].id+']').removeClass('prop_list_images_img_action_visible_off').addClass('prop_list_images_img_action_visible_on');
    $('img.prop_list_images_img_action_visible[prop_id='+data.result[0].id+']').attr("src",baseurl + "/assets/icons/actions/visible_on.png");
    $('img.prop_list_images_img_action_visible[prop_id='+data.result[0].id+']').attr("title",lang('p_visible_on'));
  }
}

function error_save_prop_to_visible(data)
{

}

function success_delete_prop_from_visible(data)
{
  if ((typeof data.result !== "undefined") && (data.result !== null))
  {
    $('img.prop_list_images_img_action_visible[prop_id='+data.result[0].id+']').removeClass('prop_list_images_img_action_visible_on').addClass('prop_list_images_img_action_visible_off');
    $('img.prop_list_images_img_action_visible[prop_id='+data.result[0].id+']').attr("src",baseurl + "/assets/icons/actions/visible_off.png");
    $('img.prop_list_images_img_action_visible[prop_id='+data.result[0].id+']').attr("title",lang('p_visible_off'));
  }
}

function error_delete_prop_from_visible(data)
{

}