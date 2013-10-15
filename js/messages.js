var data_actualize_messages_info = null;
var chating_now = null;

$(document).ready(function() 
{
  get_localstorage_messages_info_data();

  actualize_messages_info();
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
  });
}

function select_first_user_in_user_list()
{
  var first_user_id = $('.user_in_user_list').first().attr("user_id");

  if (typeof first_user_id !== "undefined" && first_user_id !== null && first_user_id !== '')
  {
    chating_now = first_user_id;
    localStorage.setItem("chating_now",chating_now);
    select_user_in_user_list()
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

function close_user_conversation()
{

}

function load_chat_title()
{

}

function actualize_conversation()
{

}

function clear_messages_no_readen()
{

}

function check_if_user_writing_me()
{

}