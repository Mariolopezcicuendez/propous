var data_table = [];
var action = null;
var mtag = 'users';
var controller = 'user';
var rol = null;

$(document).ready(function() 
{
	action = $('#cms_action').val();

  var user_id = $('#user_id').val();

  var req = {};
  req.url = '/'+controller+'/cms_get_user_rol/'+user_id;
  req.type = 'GET';
  req.success = "success_cms_get_user_rol";
  req.error = "error_cms_get_user_rol";
  ajaxp(req);

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
  else
  {
    $("#cms_image_carousel").carouFredSel({
      items   : PHOTOS_IN_CARROUSEL,
      auto    : false,
      prev    : "#foo1_prev",
      next    : "#foo1_next"
    });
    $(".fancybox").fancybox();

    $(".cms_photos_carousel_img_btn_delete").on("click", function(submitEvent)
    {
      var photo_id = $(this).attr("tagid");

      var req = {};
      req.url = '/photo/delete/'+photo_id;
      req.type = 'GET';
      req.success = "success_delete_photo";
      req.error = "error_delete_photo";
      ajaxp(req);
    });
  }
});

function success_cms_get_user_rol(data)
{
  rol = data.result;
}

function error_cms_get_user_rol(data)
{
  rol = null;
}

function success_cms_all(data)
{
	$.each(data.result, function (i) {
    var mdata = data.result[i];
    data_table.push([
      create_link_to_edit(mtag,mdata.id),
      mdata.name,
      mdata.email,
			mdata.password,
			mdata.birthdate,
			mdata.registerdate,
			mdata.sex,
			create_link_to_edit("countries",mdata.country_id),
			create_link_to_edit("cities",mdata.state_id),
			mdata.nationality,
			mdata.description,
			mdata.dwelling,
			mdata.car,
			mdata.sexuality,
			mdata.children,
			mdata.partner,
			mdata.hobbies,
			mdata.occupation,
			mdata.phone,
			mdata.activated,
			mdata.change_password_token,
			mdata.activate_account_token,
			mdata.login_cookie_tag,
			(rol === 'superadmin') ? mdata.rol : "#HIDDEN" ,
      ((mdata.photos === '0') ? '' : mdata.photos )
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

function success_delete_photo(data)
{
  var photo_id = data.result;

  $(".cms_photos_carousel_img_btn_delete[tagid="+photo_id+"]").parent().parent().parent().remove();

  show_success("cms_data_alert", "Foto borrada correctamente");

  if (typeof $('#cms_image_carousel div.thumbnail').get(0) === "undefined")
  {
    $(".cms_photos_text_no_photos").text("No existen fotos");
    $(".cms_photos_text_no_photos").removeClass("hidden");
    $(".cms_image_carousel").addClass("hidden");
  }
}

function error_delete_photo(data)
{
  show_fail("cms_data_alert", data.error.message);
}