var chating_now = null;
var user_id = null;
var data_users_list = null;
var writing = false;
var last_message_user = null;

var timeout_writing = null;
var interval_is_writing_me = null;
var interval_i_am_writing = null;
var interval_last_message_readen = null;

$(document).ready(function() 
{
  user_id = $('.gdata_user_id').val();
	chating_now = localStorage.getItem("chating_now");

  enable_chat();

  $('.div_content_chat_users_count_select').on("change", function()
  {
    var showed = $(this).val();
    get_users_with_conversations(showed);
  });

  $('.messages_send_button').on("click", function()
  {
    send_write_text();
    clear_send_textbox();
  });

  $('.div_content_chat_messages_count_select').on("change", function()
  {
    load_conversation(chating_now);
  });

  $(".messages_send_textbox").keyup(function(event) 
  {
    var text = $.trim($(this).val());

    $('.messages_send_button').prop("disabled",(text === ''));
    
    if (text !== '')
    {
      writing = true;
      clearTimeout(timeout_writing);
      timeout_writing = setTimeout( "set_no_writing()"  , TIME_SECONDS_LEAVE_WRITING_STATUS * 1000 );
    }

    if (event.which == 13)
    {
       event.preventDefault();
       send_write_text();
       clear_send_textbox();
       writing = false;
    }
  });

  get_users_with_conversations(DEFAULT_NUMBER_USERS_MESSAGED_SHOWED);

  clearInterval(interval_is_writing_me);
  interval_is_writing_me = setInterval( "check_if_user_writing_me()"  , TIME_SECONDS_CHECK_USER_IS_WRITING * 1000 );
  clearInterval(interval_i_am_writing);
  interval_i_am_writing = setInterval( "send_writing_if_i_am_writing()"  , TIME_SECONDS_CHECK_USER_IS_WRITING * 1000 );
});

function get_users_with_conversations(showed)
{
  user_id = $('.gdata_user_id').val();

  var req = {};
  req.url = '/message/get_last_messaged_users/'+user_id+'/'+showed;
  req.type = 'GET';
  req.success = "success_get_users_with_conversations";
  req.error = "error_get_users_with_conversations";
  ajaxp(req);
}

function success_get_users_with_conversations(data)
{
  $('.div_content_chat_users_list_div div.user_in_user_list_container').remove();

  data_users_list = new Array();

  $.each(data.result, function (i) {
    var user = data.result[i];
    data_users_list[user.id] = data.result[i];

    if (chating_now === null)
    {
      chating_now = user.id;
      localStorage.setItem("chating_now",chating_now);
    }

    var messages_no_readen = "";
    if (user.no_readen > 0)
    {
      messages_no_readen = user.no_readen;
    }

    var user_container = $('<div></div>').addClass('user_in_user_list_container');

    var user_to_chat = $('<div></div>').attr("user_id",user.id).addClass('media').addClass('user_in_user_list').addClass('pull-left');
    var user_to_chat_button_close = $('<div></div>').attr("user_id",user.id).addClass('user_in_user_list_button_div').addClass('pull-left');

    var user_to_chat_link = $('<a></a>').addClass('pull-left');
    var user_to_chat_link_img = $('<img></img>').addClass('chat_users_main_image').attr("src",baseurl + "/" + user.photo);

    var user_to_chat_div_body = $('<div></div>').addClass('media-body');
    var user_to_chat_div_name = $('<span></span>').text(user.name);
    var user_to_chat_div_noreaden = $('<span></span>').addClass('badge').text(messages_no_readen);
    var user_to_chat_img_connected = $("<img></img>").addClass('status_image').addClass("chat_users_online_image").attr("src",baseurl + "/" + "assets/icons/" + ((user.connected === false) ? 'off' : 'on' )+"line.png");

    var user_to_chat_button_close_img = $('<button type="button" user_id="'+user.id+'" class="close" aria-hidden="true">&times;</button>');

    $(user_to_chat_div_body).append(user_to_chat_div_name);
    $(user_to_chat_div_body).append(user_to_chat_img_connected);
    $(user_to_chat_div_body).append(user_to_chat_div_noreaden);

    $(user_to_chat_link).append(user_to_chat_link_img);
    $(user_to_chat).append(user_to_chat_link);
    $(user_to_chat).append(user_to_chat_div_body);

    $(user_to_chat_button_close).append(user_to_chat_button_close_img);

    $(user_container).append(user_to_chat);
    $(user_container).append(user_to_chat_button_close);

    $('.div_content_chat_users_list_div').append(user_container);
  });

  $('.user_in_user_list').on("click", function()
  {
    var user_talking = $(this).attr("user_id");
    select_user_in_user_list(user_talking);
  });

  $('.user_in_user_list_button_div button.close').on("click", function()
  {
    var id = $(this).attr("user_id");
    close_user_conversation(id);
  });

  if (chating_now !== null)
  {
    if (typeof data_users_list[chating_now] === "undefined")
    {
      add_user_to_users_list(chating_now);
    }
    else
    {
      select_user_in_list(chating_now);
      load_chat_title(chating_now);
      load_conversation(chating_now);
      clear_messages_no_readen(chating_now);
      check_if_user_writing_me();
    }
  }
}

function success_add_user_to_users_list(data)
{
  var user_talked = data.result;
  data_users_list[user_talked.id] = user_talked;

  var user_container = $('<div></div>').addClass('user_in_user_list_container');

  var user_to_chat = $('<div></div>').attr("user_id",user_talked.id).addClass('media').addClass('user_in_user_list').addClass('pull-left');
  var user_to_chat_button_close = $('<div></div>').attr("user_id",user_talked.id).addClass('user_in_user_list_button_div').addClass('pull-left');

  var user_to_chat_link = $('<a></a>').addClass('pull-left');
  var user_to_chat_link_img = $('<img></img>').addClass('chat_users_main_image').attr("src",baseurl + "/" + user_talked.photo);

  var user_to_chat_div_body = $('<div></div>').addClass('media-body');
  var user_to_chat_div_name = $('<span></span>').text(user_talked.name);
  var messages_no_readen = "";
  var user_to_chat_div_noreaden = $('<span></span>').addClass('badge').text(messages_no_readen);
  var user_to_chat_img_connected = $("<img></img>").addClass('status_image').addClass("chat_users_online_image").attr("src",baseurl + "/" + "assets/icons/" + ((user_talked.connected === false) ? 'off' : 'on' )+"line.png");

  var user_to_chat_button_close_img = $('<button type="button" user_id="'+user_talked.id+'" class="close" aria-hidden="true">&times;</button>');

  $(user_to_chat_div_body).append(user_to_chat_div_name);
  $(user_to_chat_div_body).append(user_to_chat_img_connected);
  $(user_to_chat_div_body).append(user_to_chat_div_noreaden);

  $(user_to_chat_link).append(user_to_chat_link_img);
  $(user_to_chat).append(user_to_chat_link);
  $(user_to_chat).append(user_to_chat_div_body);

  $(user_to_chat_button_close).append(user_to_chat_button_close_img);

  $(user_container).append(user_to_chat);
  $(user_container).append(user_to_chat_button_close);

  $('.div_content_chat_users_list_div').prepend(user_container);

  $('.div_content_chat_users_list_div div[user_id='+user_talked.id+'].user_in_user_list').on("click", function()
  {
    var user_talking = $(this).attr("user_id");
    select_user_in_user_list(user_talking);
  });

  $('.div_content_chat_users_list_div div[user_id='+user_talked.id+'] button.close').on("click", function()
  {
    var id = $(this).attr("user_id");
    close_user_conversation(id);
  });

  select_user_in_list(user_talked.id);
  load_chat_title(user_talked.id);
  load_conversation(user_talked.id);
  clear_messages_no_readen(user_talked.id);
  check_if_user_writing_me();
}

function error_get_users_with_conversations(data)
{
  disable_chat();
}

function add_user_to_users_list(user_talked)
{
  var req = {};
  req.url = '/message/get_user_conversation_data/'+user_id+'/'+user_talked;
  req.type = 'GET';
  req.success = "success_add_user_to_users_list";
  req.error = "error_add_user_to_users_list";
  ajaxp(req);
}

function error_add_user_to_users_list(data)
{

}

function disable_chat()
{
  $('.messages_send_textbox').prop("disabled",true);
  $('.div_content_chat_users_count_select').prop("disabled",true);
}

function enable_chat()
{
  $('.messages_send_textbox').prop("disabled",false);
  $('.div_content_chat_users_count_select').prop("disabled",false);
}

function clear_messages_no_readen(user_talking)
{
  if (data_users_list[user_talking].no_readen > 0)
  {
    var req = {};
    req.url = '/message/set_all_message_readen/'+user_talking+'/'+user_id;
    req.type = 'GET';
    req.success = "success_clear_messages_no_readen";
    req.error = "error_clear_messages_no_readen";
    ajaxp(req);
  }
}

function select_user_in_list(user_talking)
{
  var user;

  $.each(user = $('.div_content_chat_users_list_div div.media'), function (i) 
  {
    if ($(user[i]).attr("user_id") == user_talking)
    {
      $(user[i]).addClass("chat_users_list_user_selected");
      $(user[i]).removeClass("chat_users_list_user_noselected");
    }
    else
    {
      $(user[i]).addClass("chat_users_list_user_noselected");
      $(user[i]).removeClass("chat_users_list_user_selected");
    }
  });
}

function load_chat_title(user_talking)
{
  var image_conected = "<img class='status_image chat_users_online_image' src='"+baseurl + "/" + "assets/icons/" + ((data_users_list[user_talking].connected === false) ? 'off' : 'on' )+"line.png'></img>";
  var image_user = "<img class='conversation_chat_title_user_image' src='" + baseurl + data_users_list[user_talking].photo + "'></img>";

  $('.div_content_chat_messages_title').html(image_user + " "+data_users_list[user_talking].name + " " + image_conected + " <span class='hidden div_content_chat_messages_title_iswriting_span'>Escribiendo</span>");
}

function success_clear_messages_no_readen(data)
{
  if (chating_now !== null)
  {
    user_talking = chating_now;
    var user = data_users_list[user_talking];

    var user_to_chat = $('.div_content_chat_users_list_div div[user_id='+user_talking+'].user_in_user_list');

    var user_to_chat_button_close = $('<div></div>').attr("user_id",user.id).addClass('user_in_user_list_button_div').addClass('pull-left');

    var user_to_chat_link = $('<a></a>').addClass('pull-left');
    var user_to_chat_link_img = $('<img></img>').addClass('chat_users_main_image').attr("src",baseurl + "/" + user.photo);

    var user_to_chat_div_body = $('<div></div>').addClass('media-body');
    var user_to_chat_div_name = $('<span></span>').text(user.name);
    var messages_no_readen = "";
    var user_to_chat_div_noreaden = $('<span></span>').addClass('badge').text('');
    var user_to_chat_img_connected = $("<img></img>").addClass('status_image').addClass("chat_users_online_image").attr("src",baseurl + "/" + "assets/icons/" + ((user.connected === false) ? 'off' : 'on' )+"line.png");

    var user_to_chat_button_close_img = $('<button type="button" user_id="'+user.id+'" class="close" aria-hidden="true">&times;</button>');

    $(user_to_chat_div_body).append(user_to_chat_div_name);
    $(user_to_chat_div_body).append(user_to_chat_img_connected);
    $(user_to_chat_div_body).append(user_to_chat_div_noreaden);

    $(user_to_chat_link).append(user_to_chat_link_img);
    $(user_to_chat).children().remove();
    $(user_to_chat).append(user_to_chat_link);
    $(user_to_chat).append(user_to_chat_div_body);

    $(user_to_chat_button_close).append(user_to_chat_button_close_img);

    var user_to_chat_parent = $(user_to_chat).parent();

    $(user_to_chat_parent).children().remove();
    $(user_to_chat_parent).append(user_to_chat);
    $(user_to_chat_parent).append(user_to_chat_button_close);

    $('.div_content_chat_users_list_div div[user_id='+user.id+'].user_in_user_list').on("click", function()
    {
      var user_talking = $(this).attr("user_id");
      select_user_in_user_list(user_talking);
    });

    $('.div_content_chat_users_list_div div[user_id='+user.id+'] button.close').on("click", function()
    {
      var id = $(this).attr("user_id");
      close_user_conversation(id);
    });

    check_if_i_have_no_readen_messages();
  }
}

function error_clear_messages_no_readen(data)
{
  
}

function send_write_text()
{
  var message = $(".messages_send_textbox").val();
  message = $.trim(message);

  var data_post = {};
  data_post.user_from_id = user_id;
  data_post.user_to_id = chating_now;
  data_post.message = message;

  var req = {};
  req.url = '/message/send';
  req.type = 'POST';
  req.success = "success_send_message";
  req.error = "error_send_message";
  req.data = data_post;
  ajaxp(req);

  set_no_writing();
  send_writing_if_i_am_writing();
}

function clear_send_textbox()
{
  $(".messages_send_textbox").val('');
}

function success_send_message(data)
{
  var message = data.result;

  var div_conv = $('<div></div>').addClass('conversation_chat_div_frommetouser').attr("message_id",message.id);

  var time_title = convert_chat_timestamp_title(message.time);
  var time_message = convert_chat_timestamp_message(message.time);

  $('.div_content_chat_messages_conversation').append(div_conv);

  if (last_message_user !== 'me')
  {
    var image_user = "<img class='conversation_chat_title_user_image' src='" + baseurl + message.user_from_photo + "'></img>";

    $(div_conv).append($('<span></span>').addClass('conversation_chat_title_message').html(image_user + " " + message.user_from_name+"<span class='chat_date_joined_off'> - "+time_title+"</span>"));

    $(div_conv).append($('<p></p>').addClass('conversation_chat_content_message').html(escapeHTML(message.message)+"<span class='chat_date_joined_off'> - "+time_message+"</span>"));
  }
  else
  {
    var p_text = $('<p></p>').addClass('conversation_chat_content_message').html(escapeHTML(message.message)+"<span class='chat_date_joined_off'> - "+time_message+"</span>");
    $(div_conv).append(p_text);
    $(p_text).parent().addClass('chat_text_joined_up');
  }  

  last_message_user = 'me';

  move_chat_scroll_to_finish();
  check_last_messages_readen();
}

function error_send_message(data)
{

}

function escapeHTML(str) 
{
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function convert_chat_timestamp_title(timestamp)
{
  var datetime_part = (timestamp).split(" ");
  var date_part = datetime_part[0];
  var time_part = datetime_part[1];

  var date_part_arr = date_part.split("-");
  var time_part_arr = time_part.split(":");

  var d_year = date_part_arr[0];
  var d_month = date_part_arr[1]-1;
  var d_day = date_part_arr[2];

  d_month = parseInt(d_month+1);
  d_month = ""+d_month;
  d_day = ""+d_day;

  if (d_month.length === 1) { d_month = "0"+d_month; }
  if (d_day.length === 1) { d_day = "0"+d_day; }

  if (date_format['ytod'].in_array(m_lang))
  {
    date_part_arr[0] = d_year+"/"+d_month+"/"+d_day;
  }
  else
  {
    date_part_arr[0] = d_day+"/"+d_month+"/"+d_year;
  }

  return (date_part_arr[0] + " " + time_part_arr[0]+":"+time_part_arr[1]);
}

function convert_chat_timestamp_message(timestamp)
{
  var datetime_part = (timestamp).split(" ");
  var date_part = datetime_part[0];
  var time_part = datetime_part[1];

  var time_part_arr = time_part.split(":");

  return (time_part_arr[0]+":"+time_part_arr[1]);
}

function set_no_writing()
{
  writing = false;

  var req = {};
  req.url = '/message/set_user_no_writing/'+user_id+'/'+chating_now;
  req.type = 'GET';
  req.success = "success_set_user_no_writing";
  req.error = "error_set_user_no_writing";
  ajaxp(req);
}

function send_writing_if_i_am_writing()
{
  if (writing)
  {
    var req = {};
    req.url = '/message/set_user_writing/'+user_id+'/'+chating_now;
    req.type = 'GET';
    req.success = "success_set_user_writing";
    req.error = "error_set_user_writing";
    ajaxp(req);
  }
}

function check_if_user_writing_me()
{
  $('.div_content_chat_messages_title_iswriting_span').addClass("hidden");

  var req = {};
  req.url = '/message/check_user_writing/'+chating_now+'/'+user_id;
  req.type = 'GET';
  req.success = "success_check_if_user_writing_me";
  req.error = "error_check_if_user_writing_me";
  ajaxp(req);
}

function success_check_if_user_writing_me(data)
{
  var is_writing = data.result;

  if (is_writing === "1")
  {
    $('.div_content_chat_messages_title_iswriting_span').removeClass("hidden");
  }
  else
  {
    $('.div_content_chat_messages_title_iswriting_span').addClass("hidden");
  }
}

function error_check_if_user_writing_me(data)
{
  
}

function success_set_user_writing(data)
{

}

function error_set_user_writing(data)
{

}

function success_set_user_no_writing(data)
{

}

function error_set_user_no_writing(data)
{

}

function load_conversation(user_talking)
{
  var count = ($('.div_content_chat_messages_count_select').val() !== DEFAULT_NUMBER_MESSAGES_SHOWED) ? $('.div_content_chat_messages_count_select').val() : DEFAULT_NUMBER_MESSAGES_SHOWED ;  

  var data_post = {};
  data_post.message_id = 1;
  data_post.count = count;

  var req = {};
  req.url = '/message/get_conversation_from_id/'+user_id+'/'+user_talking;
  req.type = 'POST';
  req.success = "success_load_conversation";
  req.error = "error_load_conversation";
  req.data = data_post;
  ajaxp(req);
}

function success_load_conversation(data)
{
  $('.div_content_chat_messages_conversation').html('');

  var conversation = data.result;

  var last_message_from = null;

  if (conversation !== null)
  {
    $.each(conversation, function (i) {
    
      var div_conv;

      if (conversation[i].user_from_id == user_id)
      {
        div_conv = $('<div></div>').addClass('conversation_chat_div_frommetouser').attr("message_id",conversation[i].id);
      }
      else
      {
        div_conv = $('<div></div>').addClass('conversation_chat_div_fromusertome').attr("message_id",conversation[i].id);
      }

      $('.div_content_chat_messages_conversation').append(div_conv);

      if (typeof conversation[i-1] !== "undefined")
      {
        last_message_from = conversation[i-1].user_from_id;
      }

      var time_title = convert_chat_timestamp_title(conversation[i].time);
      var time_message = convert_chat_timestamp_message(conversation[i].time);

      if ((last_message_from !== null) && (conversation[i].user_from_id === last_message_from))
      {
        var readen = '';
        if (conversation[i].user_from_id == user_id)
        {
          if (conversation[i].readen === '1')
          {
            readen = "<img class='conversation_chat_content_message_readen_image' src='" + baseurl + "/assets/icons/ok.png'></img>";
          }
        }

        var p_text = $('<p></p>').addClass('conversation_chat_content_message').html(readen + escapeHTML(conversation[i].message)+"<span class='chat_date_joined_off'> - "+time_message+"</span>");
        $(div_conv).append(p_text);
        $(p_text).parent().addClass('chat_text_joined_up');
      }
      else
      {
        var image_user = "<img class='conversation_chat_title_user_image' src='" + baseurl + conversation[i].user_from_photo + "'></img>";

        $(div_conv).append($('<span></span>').addClass('conversation_chat_title_message').html(image_user + " " + conversation[i].user_from_name+"<span class='chat_date_joined_off'> - "+time_title+"</span>"));

        var readen = '';
        if (conversation[i].user_from_id == user_id)
        {
          if (conversation[i].readen === '1')
          {
            readen = "<img class='conversation_chat_content_message_readen_image' src='" + baseurl + "/assets/icons/ok.png'></img>";
          }
        }

        $(div_conv).append($('<p></p>').addClass('conversation_chat_content_message').html(readen + escapeHTML(conversation[i].message)+"<span class='chat_date_joined_off'> - "+time_message+"</span>"));
      }

      last_message_user = (conversation[i].user_from_id == user_id) ? 'me' : 'user' ;
    });
  }

  move_chat_scroll_to_finish();
  check_last_messages_readen();
}

function error_load_conversation(data)
{
 
}

function move_chat_scroll_to_finish()
{
  document.getElementById('div_content_chat_messages_conversation').scrollTop = 9999999;
}

function check_last_messages_readen()
{
  var last_id_sended;
  var last_id_readen;

  $.each(div_conv = $('div.conversation_chat_div_frommetouser'), function (i) 
  {
    last_id_sended = $(div_conv[i]).attr("message_id");

    if (typeof $('div.conversation_chat_div_frommetouser[message_id='+last_id_sended+'] p.conversation_chat_content_message img.conversation_chat_content_message_readen_image').get(0) !== 'undefined')
    {
      last_id_readen = last_id_sended;
    }
  });

  if (last_id_readen === last_id_sended)
  {
    clearInterval(interval_last_message_readen);
  }
  else
  {
    clearInterval(interval_last_message_readen);
    interval_last_message_readen = setInterval( "check_interval_last_message_readen()"  , TIME_SECONDS_CHECK_USER_LAST_MESSAGE_IS_READEN * 1000 );
  }
}

function check_interval_last_message_readen()
{
  var req = {};
  req.url = '/message/get_last_message_readen/'+user_id+'/'+chating_now;
  req.type = 'GET';
  req.success = "success_get_last_message_readen";
  req.error = "error_get_last_message_readen";
  ajaxp(req);
}

function success_get_last_message_readen(data)
{
  var message_id = data.result;
  var div_conv;

  var last_id_sended;
  var last_id_readen;

  $.each(div_conv = $('div.conversation_chat_div_frommetouser'), function (i) 
  {
    last_id_sended = $(div_conv[i]).attr("message_id");

    if (typeof $('div.conversation_chat_div_frommetouser[message_id='+last_id_sended+'] p.conversation_chat_content_message img.conversation_chat_content_message_readen_image').get(0) !== 'undefined')
    {
      last_id_readen = last_id_sended;
    }
  });

  $.each(div_conv = $('div.conversation_chat_div_frommetouser'), function (i) 
  {
    var div_m_id = $(div_conv[i]).attr("message_id");
    if ((div_m_id > last_id_readen) && (div_m_id <= message_id) && (typeof $('div.conversation_chat_div_frommetouser[message_id='+div_m_id+'] p.conversation_chat_content_message img.conversation_chat_content_message_readen_image').get(0) === 'undefined'))
    {
      var message = $('div.conversation_chat_div_frommetouser[message_id='+div_m_id+'] p.conversation_chat_content_message').html();
      var readen = "<img class='conversation_chat_content_message_readen_image' src='" + baseurl + "/assets/icons/ok.png'></img>";

      $('div.conversation_chat_div_frommetouser[message_id='+div_m_id+'] p.conversation_chat_content_message').html(readen + message);
    }
  });
}

function error_get_last_message_readen(data)
{
  
}

function close_user_conversation(id)
{
  var conf_data = {};
  conf_data.title = lang('p_delete_conversation');
  conf_data.body = lang("p_confirm_delete_all_user_conversation");
  conf_data.ok_text = lang('p_delete_conversation_yes');
  conf_data.cancel_text = lang('p_no');
  conf_data.ok = "delete_conversation_confirmed";
  conf_data.ok_params = id;
  show_confirm(conf_data);
}

function delete_conversation_confirmed(id)
{
  hide_confirm();
  
  var req = {};
  req.url = '/message/delete_conversation/'+user_id+'/'+id;
  req.type = 'GET';
  req.success = "success_delete_conversation";
  req.error = "error_delete_conversation";
  ajaxp(req);
}

function success_delete_conversation(data)
{
  user_todelete_id = data.result;

  $('.user_in_user_list[user_id='+user_todelete_id+']').parent().remove();

  show_success("messages_data_alert", lang('p_conversation_deleted_correctly'));

  if (chating_now != user_todelete_id)
  {
    select_user_in_user_list(chating_now)
  }
  else
  {
    select_first_user_in_user_list();
  }
}

function error_delete_conversation(data)
{
  show_fail("messages_data_alert", data.error.message);
}

function select_user_in_user_list(id)
{
  chating_now = id;
  localStorage.setItem("chating_now",chating_now);

  select_user_in_list(id);
  load_chat_title(id);
  load_conversation(id);
  clear_messages_no_readen(id);
  check_if_user_writing_me();
}

function select_first_user_in_user_list()
{
  var first_user_id = $('.user_in_user_list').first().attr("user_id");

  if (typeof first_user_id !== "undefined" && first_user_id !== null && first_user_id !== '')
  {
    select_user_in_user_list(first_user_id)
  }
}