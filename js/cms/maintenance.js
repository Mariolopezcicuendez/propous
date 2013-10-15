var data_table = [];
var mtag = 'maintenance';
var controller = 'maintenance';

$(document).ready(function() 
{
  $('#cms_save').on("click", function()
  {
    var id = $(this).attr('tag_id');

    var data_post = {};

    var input;
    $.each(input = $(".cms_input_change"), function (i) 
    {
      var tag = $(input[i]).attr("tag_id");
      var value = $(input[i]).val();

      data_post[tag] = value;
    });

    var req = {};
    req.url = '/'+controller+'/cms_save';
    req.type = 'POST';
    req.success = "success_cms_save";
    req.error = "error_cms_save";
    req.data = data_post;
    ajaxp(req);
  });
});

function success_cms_save(data)
{
  show_success("cms_data_alert","Datos guardados");
}

function error_cms_save(data)
{
  if (typeof data.error.message !== "undefined")
  {
    show_fail("cms_data_alert", data.error.message, true);
  }
  else
  {
    show_fail("cms_data_alert", data.responseText, true);
  }
}