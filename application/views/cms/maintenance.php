<div class='content_page_left content'>

  <?php $input_disabled = 'disabled'; ?>
  <?php $user_data = $this->session->userdata('logged_in'); ?>
  <?php if (($user_data['rol'] === 'admin') || ($user_data['rol'] === 'superadmin')) : ?>
    <?php $input_disabled = ''; ?>
  <?php endif; ?>

   <div class="div_content_info form-group page-cms">
      
      <h3 class='title_zone'>Mantenimiento</h3>

      <div class='content link_back'>
        <?php echo anchor('/' . getActLang() . '/cms/main', "<< Atrás"); ?>
      </div>

      <div class='cms_main_table_div col-md-12 content'>

        <div>
            <div class="form-group">
              <label class="col-md-3 control-label">on_maintenance:</label>  
              <div class="col-md-9">
                <input <?php echo $input_disabled; ?> type='text' class='form-control cms_input_change' tag_id='on_maintenance' value='<?php echo $data; ?>'/>
              </div>
            </div>
        </div>

      </div>  

      <?php if (($user_data['rol'] === 'admin') || ($user_data['rol'] === 'superadmin')) : ?>
      <div class='cms_options'>
        <button class='form-control cms_options_button_one' tag_id='on_maintenance' id='cms_save'>GUARDAR</button>
      </div>
      <?php endif; ?>  

      <div class="form-group container hidden">
        <div class='cms_data_alert_div col-md-12 pull-right'>
          <label class="col-md-3 control-label"></label>
          <div class="cms_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          <div class="cms_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
        </div>
      </div>

    </div>

</div>