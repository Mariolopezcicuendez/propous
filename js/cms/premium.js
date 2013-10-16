var data_table = [];
var action = null;
var mtag = 'premium';
var controller = 'premium';

$(document).ready(function() 
{
  action = $('#cms_action').val();

  $('#cms_new').on("click", function()
  {
    window.location = baseurl + "/"+ m_lang +"/cms/"+mtag+"/create";
  });

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
    req.url = '/'+controller+'/cms_save/'+id;
    req.type = 'POST';
    req.success = "success_cms_save";
    req.error = "error_cms_save";
    req.data = data_post;
    ajaxp(req);
  });

  $('#cms_delete').on("click", function()
  {
    var id = $(this).attr('tag_id');

    var conf_data = {};
    conf_data.title = "Eliminar";
    conf_data.body = "¿Quieres eliminar?";
    conf_data.ok_text = "SI";
    conf_data.cancel_text = "NO";
    conf_data.ok = "delete_confirmed";
    conf_data.ok_params = id;
    show_confirm(conf_data);
  });

  if (action === 'list')
  {
    show_loading();

    var req = {};
    req.url = '/'+controller+'/cms_all';
    req.type = 'GET';
    req.success = "success_cms_all";
    req.error = "error_cms_all";
    ajaxp(req);
  }
});

function success_cms_all(data)
{
  $.each(data.result, function (i) {
    var mdata = data.result[i];
    data_table.push([
      create_link_to_edit(mtag,mdata.id),
      create_link_to_edit("users",mdata.user_id),
      mdata.time,
      mdata.type
    ]);
  });

  $('.cms_main_table').dataTable({
    "bFilter": true,
    "bSort": true,
    "bInfo": true,
    "bAutoWidth": true,
    "iDisplayLength": 50,
    "bStateSave": true,
    "sPaginationType": 'full_numbers',
    "aLengthMenu": [[50, 100, 250, 500], [50, 100, 250, 500]],
    "aaData": data_table,
    "fnDrawCallback": function( oSettings ) 
    {
      $('.cms_main_table').removeClass('hidden');
      hide_loading();
    }
  });
}

function error_cms_all(data)
{
  hide_loading();
  show_fail("cms_data_alert", data.error.message, true);
}

function create_link_to_edit(entity,data)
{
  return "<a href='" + baseurl + "/" + m_lang + "/cms/"+entity+"/edit/" + data + "'>"+data+"</a>";
}

function success_cms_delete(data)
{
  window.location = baseurl + "/"+ m_lang +"/cms/"+mtag;
}

function error_cms_delete(data)
{
  show_fail("cms_data_alert", data.error.message, true);
}

function success_cms_save(data)
{
  show_success("cms_data_alert","Datos guardados");

  var id = data.result;

  window.location = baseurl + "/" + m_lang + "/cms/"+mtag+"/edit/" + id;
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

function delete_confirmed(id)
{
  hide_confirm();

  var req = {};
  req.url = '/'+controller+'/cms_delete/'+id;
  req.type = 'GET';
  req.success = "success_cms_delete";
  req.error = "error_cms_delete";
  ajaxp(req);
}