var agrupation = null;
var time = null;
var tables = 0;
var controller = 'proposal';
var mtag = 'props';
var action = null;

var analytics_detail_idg = "";
var analytics_detail_idg_tag = "";
var analytics_detail_agrupation = "";
var analytics_detail_year = "";
var analytics_detail_month = "";
var analytics_detail_day = "";

$(document).ready(function() 
{
  action = $('#analitycs_action').val();

  $('.analitycs_button_get_stats').on("click", function()
  {
    show_loading();

    $('.analitycs_result_div').html('');

    setTimeout(function() {
      make_request();
    }, 1000);
  });

  if (action === 'detail')
  {
    show_loading();

    analytics_detail_idg = localStorage.getItem("analytics_detail_idg");
    analytics_detail_idg_tag = localStorage.getItem("analytics_detail_idg_tag");
    analytics_detail_agrupation = localStorage.getItem("analytics_detail_agrupation");
    analytics_detail_year = localStorage.getItem("analytics_detail_year");
    analytics_detail_month = localStorage.getItem("analytics_detail_month");
    analytics_detail_day = localStorage.getItem("analytics_detail_day");

    var title_by_group = '';
    if ((analytics_detail_agrupation !== 'day') && (analytics_detail_idg !== '')) 
    {
      if (analytics_detail_idg_tag !== '')
      {
        if ((analytics_detail_agrupation === 'hour') && (analytics_detail_idg_tag.length === 1)) 
        {
          var analytics_detail_idg_tag_more = ""+(parseInt(analytics_detail_idg_tag) + 1);
          analytics_detail_idg_tag = "0"+analytics_detail_idg_tag;
          if (analytics_detail_idg_tag_more.length === 1) analytics_detail_idg_tag_more = "0"+analytics_detail_idg_tag_more;
          analytics_detail_idg_tag = analytics_detail_idg_tag + " - " + analytics_detail_idg_tag_more;
        }

        if (analytics_detail_idg_tag === 'null') analytics_detail_idg_tag = "SIN CLASIFICAR";

        title_by_group = ' por "'+analytics_detail_agrupation + '" ('+analytics_detail_idg_tag+')';
      }
      else
      {
        title_by_group = ' por "'+analytics_detail_agrupation + '"';
      }
    }
    $('.title_zone').text('Props' + title_by_group+'  '+ ((analytics_detail_day !== '') ? analytics_detail_day + "/" : analytics_detail_day ) + analytics_detail_month + "/" + analytics_detail_year);

    setTimeout( 'make_request_detail()' , 1000 );
  }
});

function make_request()
{
  agrupation = $('.analitycs_select_agrupation').val();
  time = $('.analitycs_select_range').val();

  var data_post = {};
  data_post.agrupation = agrupation;
  data_post.time = time;
  data_post.filter_t = $('input[name=filter_t]').val();

  var req = {};
  req.url = '/'+controller+'/analitycs_stats';
  req.type = 'POST';
  req.success = "success_stats";
  req.error = "error_stats";
  req.data = data_post;
  ajaxp(req);
}

function make_request_detail()
{
  var data_post = {};
  data_post.idg = analytics_detail_idg;
  data_post.agrupation = analytics_detail_agrupation;
  data_post.year = analytics_detail_year;
  data_post.month = analytics_detail_month;
  data_post.day = analytics_detail_day;
  data_post.filter_t = $('input[name=filter_t]').val();

  var req = {};
  req.url = '/'+controller+'/analitycs_detail';
  req.type = 'POST';
  req.success = "success_detail";
  req.error = "error_detail";
  req.data = data_post;
  ajaxp(req);
}

function success_stats(data)
{
  data = data.result;

  switch (agrupation) 
  {
    case 'hour':
      build_hour_tables(data);
    break;  
    case 'user':
    case 'country':
    case 'city':
    case 'category':
      build_agrupped_tables(data);  
    break;
    default:
      build_day_tables(data);  
    break;  
  }

  $('.analitycs_result_div').removeClass('hidden');

  hide_loading();

  show_success("analitycs_data_alert","Datos obtenidos");
}

function error_stats(data)
{
  hide_loading();

  show_fail("analitycs_data_alert", data.error.message, true);
}

function build_day_tables(json)
{
  agrupation = $('.analitycs_select_agrupation').val();
  tables = 0;
  var data = {};

  for (var year = json.from_year; year <= json.actual_year; year++) 
  {
    data['year_'+year] = {};
    for (var month = 1; month <= 12; month++) 
    {
      data['year_'+year]['month_'+month] = {};
      for (var day = 1; day <= 31; day++) 
      {
        data['year_'+year]['month_'+month]['day_'+day] = 0;
      }
    }
    tables += 12;
  };

  $.each(json, function (i) 
  {
    if (typeof json[i].groupd !== "undefined")
    {
      data['year_'+json[i].anyo]['month_'+json[i].mes]['day_'+json[i].dia] = parseInt(json[i].groupd);
    }
  });

  var act_year = json.from_year;
  act_year--;

  for (var i = 1; i <= tables; i++) 
  {
    if ((i % 12) === 1) { act_year++; }

    var div_table = $("<div></div>").addClass('analitycs_div_content_table').attr('month',act_year+"_"+i);
    var table = $("<table></table>").addClass('analitycs_table').attr('month',act_year+"_"+i);
    var thead = $("<thead></thead>").addClass('analitycs_table_thead');
    var tbody = $("<tbody></tbody>").addClass('analitycs_table_tbody');
    var trh = $("<tr></tr>").addClass('analitycs_table_thead_tr');

    var thn = $("<th></th>").addClass('analitycs_table_thead_thn').text("Mes");
    $(trh).append(thn);
    for (var j = 1; j <= 31; j++) 
    {
      var th = $("<th></th>").addClass('analitycs_table_thead_th').text(j);
      $(trh).append(th);
    }
    var tht = $("<th></th>").addClass('analitycs_table_thead_tht').text("Total");
    $(trh).append(tht);

    $(thead).append(trh);

    $(table).append(thead);

    var trb = $("<tr></tr>").addClass('analitycs_table_tbody_tr');

    var tdn = $("<td></td>").addClass('analitycs_table_tbody_tdn').text(act_year+"_"+get_month_name(((i % 12 == 0) ? 12 : (i % 12))));
    $(trb).append(tdn);
    var total_month = 0;
    for (var j = 1; j <= 31; j++) 
    {
      var entitys = data['year_'+act_year]['month_'+((i % 12 == 0) ? 12 : (i % 12) ) ]['day_'+j];

      var link;
      if (entitys > 0)
      {
        link = $("<a href='"+baseurl + "/"+ m_lang +"/analytics/"+mtag+"/detail" +"' class='analitycs_number_link press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' day='"+j+"'></a>").text(entitys);
      }
      else
      {
        link = $("<a class='analitycs_number_link no-press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' day='"+j+"'></a>").text(entitys);
      }

      var td = $("<td></td>").addClass('analitycs_table_tbody_td').append(link);
      total_month += entitys;
      $(trb).append(td);
    }

    var linkt;
    if (total_month > 0)
    {
      linkt = $("<a href='"+baseurl + "/"+ m_lang +"/analytics/"+mtag+"/detail" +"' class='analitycs_number_linkt press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"'></a>").text(total_month);
    }
    else
    {
      linkt = $("<a class='analitycs_number_link no-press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"'></a>").text(total_month);
    }
    var tdt = $("<td></td>").addClass('analitycs_table_tbody_tdt').append(linkt);
    $(trb).append(tdt);

    $(tbody).append(trb);

    $(table).append(tbody);

    $(div_table).append(table);

    $('.analitycs_result_div').append(div_table);

    if (total_month === 0)
    {
      $('.analitycs_div_content_table[month='+act_year+'_'+i+']').addClass('hidden');
    }

    $('.analitycs_table[month='+act_year+'_'+i+']').dataTable({
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bSort": false,
      "bInfo": false,
      "bAutoWidth": false,
      "fnDrawCallback": function( oSettings ) 
      {
        activate_datatable_events();
      }
    });
  };
}

function build_hour_tables(json)
{
  agrupation = $('.analitycs_select_agrupation').val();

  length_group = 0;
  $.each(json, function (i) 
  {
    if (typeof json[i].groupd !== "undefined")
    {
      length_group++;
    }
  });

  agrupation = $('.analitycs_select_agrupation').val();
  tables = 0;
  var data = {};

  for (var year = json.from_year; year <= json.actual_year; year++) 
  {
    data['year_'+year] = {};
    for (var month = 1; month <= 12; month++) 
    {
      data['year_'+year]['month_'+month] = {};
      for (var day = 1; day <= 31; day++) 
      {
        data['year_'+year]['month_'+month]['day_'+day] = {};

        for (var hour = 0; hour <= 23; hour++) 
        {
           data['year_'+year]['month_'+month]['day_'+day]['hour_'+hour] = 0;
        }
      }
    }
    tables += 12;
  };

  $.each(json, function (i) 
  {
    if (typeof json[i].groupd !== "undefined")
    {
      data['year_'+json[i].anyo]['month_'+json[i].mes]['day_'+json[i].dia]['hour_'+json[i].hora] += parseInt(json[i].groupd);
    }
  });

  var act_year = json.from_year;
  act_year--;

  for (var i = 1; i <= tables; i++) 
  {
    if ((i % 12) === 1) { act_year++; }

    var div_table = $("<div></div>").addClass('analitycs_div_content_table').attr('month',act_year+"_"+i);
    var table = $("<table></table>").addClass('analitycs_table').attr('month',act_year+"_"+i);
    var thead = $("<thead></thead>").addClass('analitycs_table_thead');
    var tbody = $("<tbody></tbody>").addClass('analitycs_table_tbody');
    var trh = $("<tr></tr>").addClass('analitycs_table_thead_tr');

    var thn = $("<th></th>").addClass('analitycs_table_thead_thn').text("Mes "+act_year+"_"+get_month_name(((i % 12 == 0) ? 12 : (i % 12))));
    $(trh).append(thn);
    for (var j = 1; j <= 31; j++) 
    {
      var th = $("<th></th>").addClass('analitycs_table_thead_th').text(j);
      $(trh).append(th);
    }
    var tht = $("<th></th>").addClass('analitycs_table_thead_tht').text("Total");
    $(trh).append(tht);

    $(thead).append(trh);

    $(table).append(thead);

    for (var hour = 0; hour <= 23; hour++) 
    {
      var trb = $("<tr></tr>").addClass('analitycs_table_tbody_tr');

      var hour_text = "";

      var hourt = hour+"";
      if (hourt.length === 1)
      {
        hourt = "0"+hourt;
      }

      var hourp = parseInt(hour)+1;

      if (hourp === 24) { hourp = 0; }
      var hourpt = hourp+"";
      if (hourpt.length === 1)
      {
        hourpt = "0"+hourpt;
      }

      hour_text = hourt + " - " + hourpt;
      var tdn = $("<td></td>").addClass('analitycs_table_tbody_tdn').text(hour_text + " horas");

      $(trb).append(tdn);
      var total_month = 0;
      for (var j = 1; j <= 31; j++) 
      {
        var entitys = data['year_'+act_year]['month_'+((i % 12 == 0) ? 12 : (i % 12) ) ]['day_'+j]['hour_'+hour];

        var link;
        if (entitys > 0)
        {
          link = $("<a href='"+baseurl + "/"+ m_lang +"/analytics/"+mtag+"/detail" +"' class='analitycs_number_link press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' day='"+j+"' idg='"+hour+"' idg_tag='"+hour+"'></a>").text(entitys);
        }
        else
        {
          link = $("<a class='analitycs_number_link no-press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' day='"+j+"' idg='"+hour+"' idg_tag='"+hour+"'></a>").text(entitys);
        }

        var td = $("<td></td>").addClass('analitycs_table_tbody_td').append(link);
        total_month += entitys;
        $(trb).append(td);
      }

      var linkt;
      if (total_month > 0)
      {
        linkt = $("<a href='"+baseurl + "/"+ m_lang +"/analytics/"+mtag+"/detail" +"' class='analitycs_number_linkt press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' idg='"+hour+"' idg_tag='"+hour+"'></a>").text(total_month);
      }
      else
      {
        linkt = $("<a class='analitycs_number_link no-press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' idg='"+hour+"' idg_tag='"+hour+"'></a>").text(total_month);
      }
      var tdt = $("<td></td>").addClass('analitycs_table_tbody_tdt').append(linkt);
      $(trb).append(tdt);

      $(tbody).append(trb);
    }

    var trb = $("<tr></tr>").addClass('analitycs_table_tbody_tr');
    var tdn = $("<td></td>").addClass('analitycs_table_tbody_tdn').text("TOTAL");
    $(trb).append(tdn);

    var totalv_month = 0;
    for (var j = 1; j <= 31; j++) 
    {
      var totalv = 0;
      for (var h = 0; h <= 23; h++) 
      {
        totalv += data['year_'+act_year]['month_'+((i % 12 == 0) ? 12 : (i % 12) ) ]['day_'+j]['hour_'+h];
      }

      var link;
      if (totalv > 0)
      {
        link = $("<a href='"+baseurl + "/"+ m_lang +"/analytics/"+mtag+"/detail" +"' class='analitycs_number_link press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' day='"+j+"'></a>").text(totalv);
      }
      else
      {
        link = $("<a class='analitycs_number_link no-press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' day='"+j+"'></a>").text(totalv);
      }

      var td = $("<td></td>").addClass('analitycs_table_tbody_tdtt').append(link);
      totalv_month += totalv;
      $(trb).append(td);
    }
    var linktv;
    if (totalv_month > 0)
    {
      linktv = $("<a href='"+baseurl + "/"+ m_lang +"/analytics/"+mtag+"/detail" +"' class='analitycs_number_linkt press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"'></a>").text(totalv_month);
    }
    else
    {
      linktv = $("<a class='analitycs_number_link no-press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"'></a>").text(totalv_month);
    }
    var tdt = $("<td></td>").addClass('analitycs_table_tbody_tdtt').append(linktv);

    $(trb).append(tdt);

    $(tbody).append(trb);

    $(table).append(tbody);

    $(div_table).append(table);

    $('.analitycs_result_div').append(div_table);

    if (totalv_month === 0)
    {
      $('.analitycs_div_content_table[month='+act_year+'_'+i+']').addClass('hidden');
    }

    $('.analitycs_table[month='+act_year+'_'+i+']').dataTable({
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bSort": false,
      "bInfo": false,
      "bAutoWidth": false,
      "fnDrawCallback": function( oSettings ) 
      {
        activate_datatable_events();
      }
    });
  };
}

function build_agrupped_tables(json)
{
  agrupation = $('.analitycs_select_agrupation').val();

  length_group = 0;
  $.each(json, function (i) 
  {
    if (typeof json[i].groupd !== "undefined")
    {
      length_group++;
    }
  });

  agrupation = $('.analitycs_select_agrupation').val();
  tables = 0;
  var data = {};

  for (var year = json.from_year; year <= json.actual_year; year++) 
  {
    data['year_'+year] = {};
    for (var month = 1; month <= 12; month++) 
    {
      data['year_'+year]['month_'+month] = {};
      for (var day = 1; day <= 31; day++) 
      {
        data['year_'+year]['month_'+month]['day_'+day] = {};

        $.each(json, function (i) 
        {
          if (typeof json[i].groupd !== "undefined")
          {
            data['year_'+year]['month_'+month]['day_'+day]['idg_'+json[i].idg] = 0;
          }
        });
      }
    }
    tables += 12;
  };

  $.each(json, function (i) 
  {
    if (typeof json[i].groupd !== "undefined")
    {
      data['year_'+json[i].anyo]['month_'+json[i].mes]['day_'+json[i].dia]['idg_'+json[i].idg] += parseInt(json[i].groupd);
    }
  });

  var act_year = json.from_year;
  act_year--;

  for (var i = 1; i <= tables; i++) 
  {
    var idgs_pushed = new Array();
    if ((i % 12) === 1) { act_year++; }

    var div_table = $("<div></div>").addClass('analitycs_div_content_table').attr('month',act_year+"_"+i);
    var table = $("<table></table>").addClass('analitycs_table').attr('month',act_year+"_"+i);
    var thead = $("<thead></thead>").addClass('analitycs_table_thead');
    var tbody = $("<tbody></tbody>").addClass('analitycs_table_tbody');
    var trh = $("<tr></tr>").addClass('analitycs_table_thead_tr');

    var thn = $("<th></th>").addClass('analitycs_table_thead_thn').text("Mes "+act_year+"_"+get_month_name(((i % 12 == 0) ? 12 : (i % 12))));
    $(trh).append(thn);
    for (var j = 1; j <= 31; j++) 
    {
      var th = $("<th></th>").addClass('analitycs_table_thead_th').text(j);
      $(trh).append(th);
    }
    var tht = $("<th></th>").addClass('analitycs_table_thead_tht').text("Total");
    $(trh).append(tht);

    $(thead).append(trh);

    $(table).append(thead);

    $.each(json, function (n) 
    {
    if ((typeof json[n].groupd !== "undefined") && (!idgs_pushed.in_array(json[n].idg)))
    {
      var trb = $("<tr></tr>").attr("idg",""+json[n].idg).addClass('analitycs_table_tbody_tr');

      if (json[n].idg === null)
      {
        var tdn = $("<td></td>").addClass('analitycs_table_tbody_tdn').text("SIN CLASIFICAR");
      }
      else
      {
        var tdn = $("<td></td>").addClass('analitycs_table_tbody_tdn').text(json[n].idg + " ("+json[n].idg_tag+")"); 
      }

      idgs_pushed.push(""+json[n].idg);

      $(trb).append(tdn);
      var total_month = 0;
      for (var j = 1; j <= 31; j++) 
      {
        var entitys = data['year_'+act_year]['month_'+((i % 12 == 0) ? 12 : (i % 12) ) ]['day_'+j]['idg_'+json[n].idg];

        var link;
        if (entitys > 0)
        {
          link = $("<a href='"+baseurl + "/"+ m_lang +"/analytics/"+mtag+"/detail" +"' class='analitycs_number_link press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' day='"+j+"' idg='"+json[n].idg+"' idg_tag='"+json[n].idg_tag+"'></a>").text(entitys);
        }
        else
        {
          link = $("<a class='analitycs_number_link no-press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' day='"+j+"' idg='"+json[n].idg+"' idg_tag='"+json[n].idg_tag+"'></a>").text(entitys);
        }

        var td = $("<td></td>").addClass('analitycs_table_tbody_td').append(link);
        total_month += entitys;
        $(trb).append(td);
      }

      var linkt;
      if (total_month > 0)
      {
        linkt = $("<a href='"+baseurl + "/"+ m_lang +"/analytics/"+mtag+"/detail" +"' class='analitycs_number_linkt press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' idg='"+json[n].idg+"' idg_tag='"+json[n].idg_tag+"'></a>").text(total_month);
      }
      else
      {
        linkt = $("<a class='analitycs_number_link no-press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' idg='"+json[n].idg+"' idg_tag='"+json[n].idg_tag+"'></a>").text(total_month);
      }
      var tdt = $("<td></td>").addClass('analitycs_table_tbody_tdt').append(linkt);
      $(trb).append(tdt);

      $(tbody).append(trb);
    }
    });

    var trb = $("<tr></tr>").addClass('analitycs_table_tbody_tr');
    var tdn = $("<td></td>").addClass('analitycs_table_tbody_tdn').text("TOTAL");
    $(trb).append(tdn);

    var totalv_month = 0;
    for (var j = 1; j <= 31; j++) 
    {
      var idgs_pushed = new Array();
      var totalv = 0;
      $.each(json, function (n) 
      {
      if ((typeof json[n].groupd !== "undefined") && (!idgs_pushed.in_array(json[n].idg)))
      {
        totalv += data['year_'+act_year]['month_'+((i % 12 == 0) ? 12 : (i % 12) ) ]['day_'+j]['idg_'+json[n].idg];
        idgs_pushed.push(""+json[n].idg);
      }
      });

      var link;
      if (totalv > 0)
      {
        link = $("<a href='"+baseurl + "/"+ m_lang +"/analytics/"+mtag+"/detail" +"' class='analitycs_number_link press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' day='"+j+"'></a>").text(totalv);
      }
      else
      {
        link = $("<a class='analitycs_number_link no-press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"' day='"+j+"'></a>").text(totalv);
      }

      var td = $("<td></td>").addClass('analitycs_table_tbody_tdtt').append(link);
      totalv_month += totalv;
      $(trb).append(td);
    }
    var linktv;
    if (totalv_month > 0)
    {
      linktv = $("<a href='"+baseurl + "/"+ m_lang +"/analytics/"+mtag+"/detail" +"' class='analitycs_number_linkt press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"'></a>").text(totalv_month);
    }
    else
    {
      linktv = $("<a class='analitycs_number_link no-press' agrupation='"+agrupation+"' year='"+act_year+"' month='"+i+"'></a>").text(totalv_month);
    }
    var tdt = $("<td></td>").addClass('analitycs_table_tbody_tdtt').append(linktv);

    $(trb).append(tdt);

    $(tbody).append(trb);

    $(table).append(tbody);

    $(div_table).append(table);

    $('.analitycs_result_div').append(div_table);

    if (totalv_month === 0)
    {
      $('.analitycs_div_content_table[month='+act_year+'_'+i+']').addClass('hidden');
    }

    $('.analitycs_table[month='+act_year+'_'+i+']').dataTable({
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bSort": false,
      "bInfo": false,
      "bAutoWidth": false,
      "fnDrawCallback": function( oSettings ) 
      {
        activate_datatable_events();
      }
    });
  };
}

function activate_datatable_events()
{
  $('.press').on("click", function( event ) 
  {
    event.preventDefault();
    analytics_detail_idg = $(this).attr("idg");
    analytics_detail_idg_tag = $(this).attr("idg_tag");
    analytics_detail_agrupation = $(this).attr("agrupation");
    analytics_detail_year = $(this).attr("year");
    analytics_detail_month = $(this).attr("month");
    analytics_detail_day = $(this).attr("day");

    if (typeof analytics_detail_idg === 'undefined' || analytics_detail_idg === 'undefined' || analytics_detail_idg == undefined) analytics_detail_idg = '';
    if (typeof analytics_detail_idg_tag === 'undefined' || analytics_detail_idg_tag === 'undefined' || analytics_detail_idg_tag == undefined) analytics_detail_idg_tag = '';
    if (typeof analytics_detail_agrupation === 'undefined' || analytics_detail_agrupation === 'undefined' || analytics_detail_agrupation == undefined) analytics_detail_agrupation = '';
    if (typeof analytics_detail_year === 'undefined' || analytics_detail_year === 'undefined' || analytics_detail_year == undefined) analytics_detail_year = '';
    if (typeof analytics_detail_month === 'undefined' || analytics_detail_month === 'undefined' || analytics_detail_month == undefined) analytics_detail_month = '';
    if (typeof analytics_detail_day === 'undefined' || analytics_detail_day === 'undefined' || analytics_detail_day == undefined) analytics_detail_day = '';

    localStorage.setItem("analytics_detail_idg", analytics_detail_idg);
    localStorage.setItem("analytics_detail_idg_tag", analytics_detail_idg_tag);
    localStorage.setItem("analytics_detail_agrupation", analytics_detail_agrupation);
    localStorage.setItem("analytics_detail_year", analytics_detail_year);
    localStorage.setItem("analytics_detail_month", analytics_detail_month);
    localStorage.setItem("analytics_detail_day", analytics_detail_day);

    window.location = $(this).attr("href");
  });

  $('.press').on("mousedown", function( event ) 
  {
    analytics_detail_idg = $(this).attr("idg");
    analytics_detail_idg_tag = $(this).attr("idg_tag");
    analytics_detail_agrupation = $(this).attr("agrupation");
    analytics_detail_year = $(this).attr("year");
    analytics_detail_month = $(this).attr("month");
    analytics_detail_day = $(this).attr("day");

    if (typeof analytics_detail_idg === 'undefined' || analytics_detail_idg === 'undefined' || analytics_detail_idg == undefined) analytics_detail_idg = '';
    if (typeof analytics_detail_idg_tag === 'undefined' || analytics_detail_idg_tag === 'undefined' || analytics_detail_idg_tag == undefined) analytics_detail_idg_tag = '';
    if (typeof analytics_detail_agrupation === 'undefined' || analytics_detail_agrupation === 'undefined' || analytics_detail_agrupation == undefined) analytics_detail_agrupation = '';
    if (typeof analytics_detail_year === 'undefined' || analytics_detail_year === 'undefined' || analytics_detail_year == undefined) analytics_detail_year = '';
    if (typeof analytics_detail_month === 'undefined' || analytics_detail_month === 'undefined' || analytics_detail_month == undefined) analytics_detail_month = '';
    if (typeof analytics_detail_day === 'undefined' || analytics_detail_day === 'undefined' || analytics_detail_day == undefined) analytics_detail_day = '';

    localStorage.setItem("analytics_detail_idg", analytics_detail_idg);
    localStorage.setItem("analytics_detail_idg_tag", analytics_detail_idg_tag);
    localStorage.setItem("analytics_detail_agrupation", analytics_detail_agrupation);
    localStorage.setItem("analytics_detail_year", analytics_detail_year);
    localStorage.setItem("analytics_detail_month", analytics_detail_month);
    localStorage.setItem("analytics_detail_day", analytics_detail_day);
  });
}

function get_month_name(month)
{
  switch (month) 
  {
    case 1:
      return 'ENERO'
    break;  
    case 2:
      return 'FEBRERO'
    break;  
    case 3:
      return 'MARZO'
    break;  
    case 4:
      return 'ABRIL'
    break;  
    case 5:
      return 'MAYO'
    break;  
    case 6:
      return 'JUNIO'
    break;  
    case 7:
      return 'JULIO'
    break;  
    case 8:
      return 'AGOSTO'
    break;  
    case 9:
      return 'SEPTIEMBRE'
    break;  
    case 10:
      return 'OCTUBRE'
    break;  
    case 11:
      return 'NOVIEMBRE'
    break;  
    case 12:
      return 'DICIEMBRE'
    break;  
  }
}

function success_detail(json)
{
  show_success("analitycs_data_alert","Datos obtenidos");

  var num = 0;

  var data;
  $.each(data = json.result, function (i) 
  {
    var trb = $("<tr></tr>").addClass('analitycs_table_detail_tbody_tr');

    var link_id = "<a target='_blank' href='" + baseurl + "/" + m_lang + "/cms/"+mtag+"/edit/" + data[i].id + "'>"+data[i].id+"</a>";

    var td_id = $("<td></td>").addClass('analitycs_table_detail_tbody_td').html(link_id);
    var td_name = $("<td></td>").addClass('analitycs_table_detail_tbody_td').text(data[i].proposal);
    var td_time = $("<td></td>").addClass('analitycs_table_detail_tbody_td').text(data[i].time);

    $(trb).append(td_id);
    $(trb).append(td_name);
    $(trb).append(td_time);

    $("#analitycs_table_detail tbody").append(trb);

    num++;
  });

  var trbt = $("<tr></tr>").addClass('analitycs_table_detail_tbody_trt');

  var td_id = $("<td></td>").addClass('analitycs_table_detail_tbody_tdt').text("TOTAL: "+num);
  var td_name = $("<td></td>").addClass('analitycs_table_detail_tbody_tdt').text("");
  var td_time = $("<td></td>").addClass('analitycs_table_detail_tbody_tdt').text("");

  $(trbt).append(td_id);
  $(trbt).append(td_name);
  $(trbt).append(td_time);

  $("#analitycs_table_detail tbody").append(trbt);

  $('#analitycs_table_detail').dataTable({
    "bPaginate": false,
    "bLengthChange": false,
    "bFilter": false,
    "bSort": false,
    "bInfo": false,
    "bAutoWidth": false
  });

  $('.analitycs_result_div').removeClass('hidden');

  hide_loading();
}

function error_detail(data)
{
  hide_loading();

  show_fail("analitycs_data_alert", data.error.message, true);
}