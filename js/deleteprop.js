var proposal_id = null;

$(document).ready(function() 
{
	proposal_id = localStorage.getItem("proposal_id");

  $('.deleteprop_delete_button').on("click", function()
  {
    var req = {};
    req.url = '/proposal/delete_proposal/'+proposal_id;
    req.type = 'GET';
    req.success = "success_delete_prop";
    req.error = "error_delete_prop";
    ajaxp(req);
  });

  $('.deleteprop_return_myprops').on("click", function()
  {
    window.location = baseurl + "/" + m_lang + "/myprops";
  });
});

function success_delete_prop(data)
{
  show_success("deleteprop_data_alert", lang('p_deleteprop_deleted_correctly'));

  $('.deleteprop_div_return_myprops').parent().parent().removeClass('hidden');
  $('.page_info_paragraph').addClass('hidden');
}

function error_delete_prop(data)
{
  show_fail("deleteprop_data_alert", data.error.message);
}