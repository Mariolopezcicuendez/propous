var data_table = [];
var action = null;
var mtag = 'socialities';
var controller = 'sociality';

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
    data_post.filter_t = $('input[name=filter_t]').val();

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
  
  $(":file").filestyle({icon: false});
});

function success_cms_all(data)
{
  $.each(data.result, function (i) {
    var mdata = data.result[i];
    data_table.push([
      create_link_to_edit(mtag,mdata.id),
      mdata.tag,
      mdata.I18n_en_name + " (" +  mdata.I18n_en_id + ")",
      mdata.I18n_es_name + " (" +  mdata.I18n_es_id + ")",
      "<img class='cms_icon_list' title='/assets/icons/"+controller+"/"+ get_icon_flag_name(mdata.tag) +".png' src='"+ baseurl + "/assets/icons/"+controller+"/"+ get_icon_flag_name(mdata.tag) +".png'/>"
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
  show_fail("cms_data_alert", data.error.message);
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
  show_fail("cms_data_alert", data.error.message);
}

function success_cms_save(data)
{
  show_success("cms_data_alert","Datos guardados");

  var id = data.result;

  var icon = $(".cms_icon_inputfile").val();
  if (icon !== '')
  {
    var extension = icon.replace(/^.*\./, '');
    extension = (extension == icon) ? '' : extension.toLowerCase() ;
    
    switch (extension) 
    {
      case 'png':
        save_icon(id);  
      break;  
      default:
        submitEvent.preventDefault();
        show_fail("cms_data_alert", "El icono debe ser una imagen .png");
      break;  
    }
  }

  window.location = baseurl + "/" + m_lang + "/cms/"+mtag+"/edit/" + id;
}

function error_cms_save(data)
{
  if (typeof data.error.message !== "undefined")
  {
    show_fail("cms_data_alert", data.error.message);
  }
  else
  {
    show_fail("cms_data_alert", data.responseText);
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

function save_icon(id)
{
  var req = {};
  req.url = '/'+controller+'/cms_save_icon/'+id;
  req.type = 'POST';
  req.success = "success_save_icon";
  req.error = "error_save_icon";
  req.data = new FormData($('.cms_icon_upload_form')[0]);
  req.cache = false;
  req.contentType = false;
  req.processData = false;
  ajaxp(req);
}

function success_save_icon(data)
{
  
}

function error_save_icon(data)
{
  
}