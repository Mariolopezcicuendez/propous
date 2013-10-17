<div class='content_page_left content'>

  <input type='hidden' id='analitycs_action' value='list'>

   <div class="div_content_info form-group page-analitycs">
      
      <h3 class='title_zone'>Usuarios</h3>

      <?php echo form_open('#',array("class"=>"form-horizontal hidden","role"=>"form")); ?>
    <?php echo form_close(); ?>
    
      <div class='content link_back'>
        <?php echo anchor('/' . getActLang() . '/analytics/main', "<< Atrás"); ?>
      </div>

      Mostrar agrupación por: 
      <select class='analitycs_select_agrupation input_inline_3_elements_full form-control'>
        <option selected value='day'>Dias</option>
        <option value='hour'>Horas</option>
        <option value='sex'>Sexo</option>
        <option value='country'>País</option>
        <option value='city'>Ciudad</option>
        <option value='activated'>Activación</option>
        <option value='birthdate'>Año nac.</option>
      </select>
      en los 
      <select class='analitycs_select_range input_inline_3_elements_full form-control'>
        <option value='3'>últimos 3 dias</option>
        <option value='7'>últimos 7 dias</option>
        <option value='15'>últimos 15 dias</option>
        <option selected value='30'>últimos 30 dias (1 mes)</option>
        <option value='60'>últimos 60 dias (2 meses)</option>
        <option value='180'>últimos 180 dias (6 meses)</option>
        <option value='365'>últimos 365 dias (1 año)</option>
        <option value='720'>últimos 720 dias (2 años)</option>
      </select>
      <button class='btn btn-default analitycs_button_get_stats'>Enviar...</button>

      <div class='analitycs_result_div hidden'>
      </div>

      <div class="form-group container hidden">
        <div class='analitycs_data_alert_div col-md-12 pull-right'>
          <label class="col-md-3 control-label"></label>
          <div class="analitycs_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          <div class="analitycs_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
        </div>
      </div>

    </div>

</div>