var user_id = null;
var premium_selected = null;

$(document).ready(function() 
{
	user_id = $(".gdata_user_id").val();

	$('.content_premium_mod_div').on("click", function()
  {
    var premium_tag = $(this).attr("premium_tag");
    premium_selected = premium_tag;

    var data_post = {};
    data_post.user_id = user_id;
    data_post.type = premium_tag;
    data_post.filter_t = $('input[name=filter_t]').val();

    var req = {};
    req.url = '/premium/add';
    req.type = 'POST';
    req.success = "success_add_premium";
    req.error = "error_add_premium";
    req.data = data_post;
    ajaxp(req);
  });
});   

function success_add_premium(data)
{
  show_payment_card();
}

function error_add_premium(data)
{
  var error_number = data.status;
  var error_message = data.error.message;

  if (error_number === 1502)
  {
    var conf_data = {};
    conf_data.title = lang('p_premium');
    conf_data.body = lang("p_user_already_premium_want_proceed");
    conf_data.ok_text = lang('p_yes');
    conf_data.cancel_text = lang('p_no');
    conf_data.ok = "premium_forced";
    show_confirm(conf_data);
  }
  if (error_number === 1503)
  {
    show_fail('premium_data_alert',error_message);
  }
}

function premium_forced()
{
  hide_confirm();

  if ((premium_selected !== null) && (user_id !== null))
  {
    var data_post = {};
    data_post.user_id = user_id;
    data_post.type = premium_selected;
    data_post.force = true;
    data_post.filter_t = $('input[name=filter_t]').val();

    var req = {};
    req.url = '/premium/add';
    req.type = 'POST';
    req.success = "success_add_premium_forced";
    req.error = "error_add_premium_forced";
    req.data = data_post;
    ajaxp(req);
  }
}

function success_add_premium_forced(data)
{
  show_payment_card();
}

function error_add_premium_forced(data)
{
  var error_message = data.error.message;
  show_fail('premium_data_alert',error_message);
}

function show_payment_card()
{
  if ((premium_selected !== null) && (user_id !== null))
  {
    alert('show_payment_card '+user_id+"|"+premium_selected);
  }
}