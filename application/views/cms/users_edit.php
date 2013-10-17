<div class='content_page_left content'>

  <?php $input_disabled = 'disabled'; ?>
  <?php $user_data = $this->session->userdata('logged_in'); ?>
  <?php if (($user_data['rol'] === 'admin') || ($user_data['rol'] === 'superadmin')) : ?>
    <?php $input_disabled = ''; ?>
  <?php endif; ?>

  <?php echo form_open('#',array("class"=>"form-horizontal hidden","role"=>"form")); ?>
    <?php echo form_close(); ?>
    
	<input type='hidden' id='cms_action' value='edit'>
  <input type='hidden' id='entity_id' value='<?php echo $data->id; ?>'>
  <input type='hidden' id='user_id' value='<?php echo $user_data['id']; ?>'>

   <div class="div_content_info form-group page-cms">
      
      <?php if ($action === 'new') : ?>
      	<h3 class='title_zone'>Nuevo Usuario</h3>
      <?php endif; ?>
      <?php if ($action === 'edit') : ?>
      	<h3 class='title_zone'>Usuario <?php echo $data->id; ?></h3>
      <?php endif; ?>

      <div class='content link_back'>
      	<?php echo anchor('/' . getActLang() . '/cms/'.$back, "<< Atrás"); ?>
    	</div>

      <div class='cms_main_table_div col-md-12 content'>

      	<div>
	      	<?php foreach ($data as $key => $value) :	?>
          <?php if (($key !== 'rol') || ($user_data['rol'] === 'superadmin')) : ?>
            <?php if ($key !== 'photos') : ?>
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
            <?php endif; ?>
            <?php endif; ?>
	        <?php endforeach; ?>

          <?php if ($action === 'edit') : ?>
          <div class="form-group">
            <label class="col-md-3 control-label">Photos:</label>  

            <div class="cms_image_carousel col-md-9<?php if (count($data->photos) === 0) : ?> hidden<?php endif; ?>">
              <div id="cms_image_carousel">
                <?php foreach ($data->photos as $key => $value) : ?>
                <div class='thumbnail form-inline'>
                  <a class="fancybox" rel="group" href="<?php echo $this->config->item('file_base_url_relative').$value->route; ?>">
                    <img class='cms_photos_carousel_img' id='cms_photos_carousel_img<?php echo $value->id; ?>' src='<?php echo $this->config->item('file_base_url_relative').$value->thumbnail; ?>'></img>
                  </a>
                  <?php if (($user_data['rol'] === 'admin') || ($user_data['rol'] === 'superadmin')) : ?>
                  <div class='caption'>
                    <p>
                      <a tagid='<?php echo $value->id; ?>' class='btn btn-default btn-block cms_photos_carousel_img_btn_delete'>Eliminar</a>
                    </p>
                  </div>
                  <?php endif; ?>
                </div>
                <?php endforeach; ?>
              </div>
              <div class="clearfix"></div>
              <a class="prev" id="foo1_prev" href="#"><span>prev</span></a>
              <a class="next" id="foo1_next" href="#"><span>next</span></a>
            </div>

            <div class="col-md-9">
              <p id='cms_photos_text_no_photos' class="cms_photos_text_no_photos form-control-static<?php if (count($data->photos) !== 0) : ?> hidden<?php endif; ?>">No existen fotos</p>
            </div>
          </div>
          <?php endif; ?>

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