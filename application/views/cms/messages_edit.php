<div class='content_page_left content'>

  <?php $input_disabled = 'disabled'; ?>
  <?php $user_data = $this->session->userdata('logged_in'); ?>
  <?php if (($user_data['rol'] === 'admin') || ($user_data['rol'] === 'superadmin')) : ?>
    <?php $input_disabled = ''; ?>
  <?php endif; ?>

  <?php echo form_open('#',array("class"=>"form-horizontal hidden","role"=>"form")); ?>
    <?php echo form_close(); ?>
    
  <input type='hidden' id='cms_action' value='edit'>

   <div class="div_content_info form-group page-cms">
      
      <?php if ($action === 'new') : ?>
        <h3 class='title_zone'>Nuevo Mensaje</h3>
      <?php endif; ?>
      <?php if ($action === 'edit') : ?>
        <h3 class='title_zone'>Mensaje <?php echo $data->id; ?></h3>
      <?php endif; ?>

      <div class='content link_back'>
        <?php echo anchor('/' . getActLang() . '/cms/'.$back, "<< Atrás"); ?>
      </div>

      <div class='cms_main_table_div col-md-12 content'>

        <div>
          <?php foreach ($data as $key => $value) :  ?>
            <div class="form-group">
              <label class="col-md-3 control-label"><?php echo $key; ?>:</label>  
              <div class="col-md-9">
                <?php if (in_array($key, $textareas)) : ?>
                  <textarea <?php echo $input_disabled; ?> class='form-control cms_input_change' tag_id='<?php echo $key; ?>'><?php echo $value; ?></textarea>
                <?php else : ?>
                  <input type='text' <?php echo $input_disabled; ?> class='form-control cms_input_change' tag_id='<?php echo $key; ?>' value='<?php echo $value; ?>'/>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      </div>  

      <?php if (($user_data['rol'] === 'admin') || ($user_data['rol'] === 'superadmin')) : ?>
      <div class='cms_options input-group'>
        <?php if ($action === 'new') : ?>
          <button class='form-control cms_options_button_one' tag_id='<?php echo $data->id; ?>' id='cms_save'>GUARDAR</button>
        <?php endif; ?>
        <?php if ($action === 'edit') : ?>
          <button class='form-control cms_options_button_three' tag_id='<?php echo $data->id; ?>' id='cms_save'>GUARDAR</button>
          <button class='form-control cms_options_button_three' tag_id='<?php echo $data->id; ?>' id='cms_delete'>ELIMINAR</button>
          <button class='form-control cms_options_button_three' id='cms_new'>NUEVO</button>
        <?php endif; ?>
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