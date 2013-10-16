var data_actualize_messages_info = null;
var chating_now = null;
var last_message_from = null;
var user_id = null;
var writing = false;

$(document).ready(function() 
{
  user_id = $(".gdata_user_id").val();

  get_localstorage_messages_info_data();

  actualize_messages_info();

  $('.messages_send_button').on("click", function()
  {
    send_write_text();
    clear_send_textbox();
  });
  $(".messages_send_textbox").keyup(function(event) 
  {
    var text = $.trim($(this).val());

    $('.messages_send_button').prop("disabled",(text === ''));
    
    if (text !== '')
    {
      writing = true;
    }

    if (event.which == 13)
    {
       event.preventDefault();
       send_write_text();
       clear_send_textbox();
       writing = false;
    }
  });

});

function get_localstorage_messages_info_data()
{
  data_actualize_messages_info = JSON.parse(localStorage.getItem("data_actualize_messages_info"));
  chating_now = localStorage.getItem("chating_now");
}

function empty_user_list()
{
  return (typeof $('.div_content_chat_users_list_div div.user_in_user_list_container').children().get(0) === 'undefined');
}

function print_user_list()
{
  $('.div_content_chat_users_list_div div.user_in_user_list_container').remove();

  var user;
  $.each(data_actualize_messages_info.users_list, function (i) 
  {
    user = data_actualize_messages_info.users_list[i];

    var user_container = $('<div></div>').addClass('user_in_user_list_container');

    var user_to_chat = $('<div></div>').attr("user_id",user.id).addClass('media').addClass('user_in_user_list').addClass('pull-left');
    var user_to_chat_button_close = $('<div></div>').attr("user_id",user.id).addClass('user_in_user_list_button_div').addClass('pull-left');

    var user_to_chat_link = $('<a></a>').addClass('pull-left');
    var user_to_chat_link_img = $('<img></img>').addClass('chat_users_main_image').attr("src",baseurl + "/" + user.photo);

    var user_to_chat_div_body = $('<div></div>').addClass('media-body');
    var user_to_chat_div_name = $('<span></span>').text(user.name);
    var user_to_chat_div_noreaden = $('<span></span>').addClass('badge').addClass('hidden').text("");

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

    $('.div_content_chat_users_list_div').prepend(user_container);

    if (user.no_readen > 0) 
    {
      $(user_to_chat_div_noreaden).removeClass('hidden');
      $(user_to_chat_div_noreaden).text(user.no_readen);
    }
  });

  $('.user_in_user_list').on("click", function()
  {
    var user_talking = $(this).attr("user_id");
    chating_now = user_talking;
    localStorage.setItem("chating_now",chating_now);
    actualize_messages_info();
    select_user_in_user_list();
  });

  $('.user_in_user_list_button_div button.close').on("click", function()
  {
    var id = $(this).attr("user_id");
    close_user_conversation(id);
  });
}

function actualize_users_list_with_no_readen_messages()
{
  $.each(data_actualize_messages_info.users_list, function (i) 
  {
    user = data_actualize_messages_info.users_list[i];
    if (user.no_readen > 0) 
    {
      $('div.user_in_user_list_container div[user_id='+user.id+'] span.badge').text(user.no_readen);
      $('div.user_in_user_list_container div[user_id='+user.id+'] span.badge').removeClass('hidden');
    }
    else
    {
      $('div.user_in_user_list_container div[user_id='+user.id+'] span.badge').text("");
      $('div.user_in_user_list_container div[user_id='+user.id+'] span.badge').addClass('hidden');
    }
  });
}

function select_first_user_in_user_list()
{
  var first_user_id = $('.user_in_user_list').first().attr("user_id");

  if (typeof first_user_id !== "undefined" && first_user_id !== null && first_user_id !== '')
  {
    chating_now = first_user_id;
    localStorage.setItem("chating_now",chating_now);
    actualize_messages_info();
    select_user_in_user_list();
  }
}

function select_user_in_user_list()
{
  var user;

  $.each(user = $('.div_content_chat_users_list_div div.media'), function (i) 
  {
    if ($(user[i]).attr("user_id") == chating_now)
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

function load_chat_title()
{
  var user = data_actualize_messages_info.users_chating_now;

  var image_conected = "<img class='status_image chat_users_online_image' src='"+baseurl + "/" + "assets/icons/" + ((user.connected === false) ? 'off' : 'on' )+"line.png'></img>";
  var image_user = "<img class='conversation_chat_title_user_image' src='" + baseurl + user.photo + "'></img>";

  $('.div_content_chat_messages_title').html(image_user + " "+user.name + " " + image_conected + " <span class='hidden div_content_chat_messages_title_iswriting_span'>"+lang('p_writing')+"</span>");
}

function actualize_conversation()
{
  var scroll_at_end = is_scroll_at_end();

  $('.div_content_chat_messages_conversation').html('');

  var conversation = data_actualize_messages_info.users_chating_now_conversation;

  if (conversation !== null)
  {
    $.each(conversation, function (i) 
    {
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

      last_message_from = (conversation[i].user_from_id == user_id) ? 'me' : 'user' ;
    });
  }

  if (scroll_at_end)
  {
    move_chat_scroll_to_finish();
  }
}

function is_scroll_at_end()
{
  return false;
}

function move_chat_scroll_to_finish()
{
  document.getElementById('div_content_chat_messages_conversation').scrollTop = 9999999;
}

function escapeHTML(str) 
{
  str = str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  str = str.replace(/\\n/g,'<br/>');
  return str;
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

function check_if_user_writing_me()
{

}

function close_user_conversation()
{

}

function clear_send_textbox()
{
  $(".messages_send_textbox").val('');
}

function send_write_text()
{
  var message = $(".messages_send_textbox").val();
  message = $.trim(message);

  localStorage.setItem("user_message",JSON.stringify(message));

  last_message_from = 'me';
  actualize_messages_info();
}