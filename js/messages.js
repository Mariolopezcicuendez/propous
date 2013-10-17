var data_actualize_messages_info = null;
var chating_now = null;
var last_message_from = null;
var user_id = null;
var timeout_writing = null;
var last_id_message_printed = null;

$(document).ready(function() 
{
  user_id = $(".gdata_user_id").val();

  $('.div_content_chat_messages_conversation').html('');

  localStorage.setItem("users_list_count",DEFAULT_NUMBER_USERS_MESSAGED_SHOWED);
  localStorage.setItem("messages_list_count",DEFAULT_NUMBER_MESSAGES_SHOWED);

  get_localstorage_messages_info_data();

  actualize_messages_info();

  $('.div_content_chat_users_count_select').on("change", function()
  {
    console.log("borramos");
    $('.div_content_chat_users_list_div div.user_in_user_list_container').remove();

    var showed = $(this).val();
    localStorage.setItem("users_list_count",showed);
    actualize_messages_info();
  });
  $('.div_content_chat_messages_count_select').on("change", function()
  {
    last_id_message_printed = 1;
    localStorage.setItem("last_id_message_printed",last_id_message_printed);

    var showed = $(this).val();
    localStorage.setItem("messages_list_count",showed);
    actualize_messages_info();
  });

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
      if (localStorage.getItem("writing") !== true) localStorage.setItem("writing",true);
      clearTimeout(timeout_writing);
      timeout_writing = setTimeout(function() {
        set_no_writing();
      }, TIME_SECONDS_LEAVE_WRITING_STATUS * 1000);
    }

    if (event.which == 13)
    {
       event.preventDefault();
       
       if (localStorage.getItem("writing") !== false) localStorage.setItem("writing",false);
       
       send_write_text();
       clear_send_textbox();
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
  if (data_actualize_messages_info.users_list.length > 0)
  {
    $.each(data_actualize_messages_info.users_list, function (i) 
    {
      user = data_actualize_messages_info.users_list[i];

      add_user_to_list(user);
    });
  }

  $('.user_in_user_list').on("click", function()
  {
    $('.div_content_chat_messages_conversation').html('');

    last_id_message_printed = 1;
    localStorage.setItem("last_id_message_printed",last_id_message_printed);

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

function actualize_print_user_list()
{
  var user;
  if (data_actualize_messages_info.users_list.length > 0)
  {
    $.each(data_actualize_messages_info.users_list, function (i) 
    {
      user = data_actualize_messages_info.users_list[i];

      // Este usuario no está en la conversación, añadirlo
      if (typeof $('.div_content_chat_users_list_div div.user_in_user_list_container div[user_id='+user.id+']').get(0) === 'undefined')
      {
        add_user_to_list(user);
        
        $('.user_in_user_list[user_id='+user.id+']').on("click", function()
        {
          $('.div_content_chat_messages_conversation').html('');

          last_id_message_printed = 1;
          localStorage.setItem("last_id_message_printed",last_id_message_printed);

          var user_talking = $(this).attr("user_id");
          chating_now = user_talking;
          localStorage.setItem("chating_now",chating_now);
          actualize_messages_info();
          select_user_in_user_list();
        });

        $('.user_in_user_list_button_div[user_id='+user.id+'] button.close').on("click", function()
        {
          var id = $(this).attr("user_id");
          close_user_conversation(id);
        });
      }
    });
  }
}

function add_user_to_list(user)
{
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
}

function actualize_users_list_with_no_readen_messages()
{
  if (data_actualize_messages_info.users_list.length > 0)
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
}

function select_first_user_in_user_list()
{
  last_id_message_printed = 1;
  localStorage.setItem("last_id_message_printed",last_id_message_printed);

  $('.div_content_chat_messages_conversation').html('');

  if (data_actualize_messages_info.users_list.length > 0)
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
  if (typeof data_actualize_messages_info.user_chating_now !== "undefined")
  {
    var user = data_actualize_messages_info.user_chating_now;

    var image_conected = "<img class='status_image chat_users_online_image' src='"+baseurl + "/" + "assets/icons/" + ((user.connected === false) ? 'off' : 'on' )+"line.png'></img>";
    var image_user = "<img class='conversation_chat_title_user_image' src='" + baseurl + user.photo + "'></img>";

    $('.div_content_chat_messages_title').html(image_user + " <span class='conversation_chat_title_user_name'>"+user.name + "</span> " + image_conected + " <span class='hidden div_content_chat_messages_title_iswriting_span'>"+lang('p_writing')+"</span>");
    $('.div_content_chat_messages_title').removeClass('hidden');
  }
  else
  {
    $('.div_content_chat_messages_title').children().remove();
  }
}

function actualize_conversation()
{
  if (typeof data_actualize_messages_info.user_chating_now_conversation !== "undefined")
  {
    var conversation = data_actualize_messages_info.user_chating_now_conversation;

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
            readen = "<span class='readen_message_draw hidden'></span>";
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
            readen = "<span class='readen_message_draw hidden'></span>";
          }

          $(div_conv).append($('<p></p>').addClass('conversation_chat_content_message').html(readen + escapeHTML(conversation[i].message)+"<span class='chat_date_joined_off'> - "+time_message+"</span>"));
        }

        last_message_from = (conversation[i].user_from_id == user_id) ? user_id : conversation[i].user_from_id ;

        localStorage.setItem("last_id_message_printed",conversation[i].id);

        move_chat_scroll_to_finish();
      });
    }
  }
}

function actualize_conversation_with_readen(last_id)
{
  var mydiv;
  $.each(mydiv = $("div.conversation_chat_div_frommetouser"), function (i) 
  {
    var id = $(mydiv[i]).attr("message_id");
    if (id <= last_id)
    {
      $("div.conversation_chat_div_frommetouser[message_id="+id+"] p.conversation_chat_content_message span.readen_message_draw").removeClass('hidden');
    }
  });
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
  if (typeof data_actualize_messages_info.user_chating_now !== "undefined")
  {
    if (data_actualize_messages_info.user_chating_now.writing === '1')
    {
      $('.div_content_chat_messages_title_iswriting_span').removeClass("hidden");
    }
    else
    {
      $('.div_content_chat_messages_title_iswriting_span').addClass("hidden");
    }
  }
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

  actualize_messages_info();
}

function set_no_writing()
{
  if (localStorage.getItem("writing") !== false) localStorage.setItem("writing",false);
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
  
  localStorage.setItem("delete_user_conversation",id);

  actualize_messages_info();

  $('.user_in_user_list[user_id='+id+']').parent().remove();

  show_success("messages_data_alert", lang('p_conversation_deleted_correctly'));

  if (chating_now != id)
  {
    select_user_in_user_list(chating_now)
  }
  else
  {
    chating_now = null;
    localStorage.setItem("chating_now",chating_now);
    select_first_user_in_user_list();
  }
}