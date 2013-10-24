<div class='content_page_left content'>

  <div class='div_content_contact_left col-md-2'></div>

  <div class='div_content_contact div_content_panel col-md-8 panel panel-default'>

    <div class='content_contact_title panel-heading'>
      <?php echo lang('p_contact'); ?><br/>
    </div>

    <div class='content_contact_body panel-body'>

    	<h3 class='title_zone'><?php echo lang('p_title_contact_page'); ?></h3>

    	<div class="div_content_contact form-group">
        <div class="col-md-12">
          <p id='contact_zone' class="contact_zone form-control-static">
            <?php echo lang('p_paragraph_contact_1'); ?>
          </p>
        </div>
      </div>

      <h3 class='title_zone'><?php echo lang('p_title_send_form'); ?></h3>

      <?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

      	<div class="form-group">
          <label for="contact_user_name" class="col-md-3 control-label"><?php echo lang('p_username'); ?>:</label>  
          <div class="col-md-9">
            <input type='text' id='contact_user_name' class='contact_user_name form-control'/> 
          </div>
          <div class='error_validation_contact_user_name hidden'>
            <label for="contact_user_name_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='contact_user_name_validation_error' class="span_validation_label contact_user_name_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="contact_user_email" class="col-md-3 control-label"><?php echo lang('p_email'); ?>:</label>  
          <div class="col-md-9">
            <input type='text' id='contact_user_email' class='contact_user_email form-control'/> 
          </div>
          <div class='error_validation_contact_user_email hidden'>
            <label for="contact_user_email_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='contact_user_email_validation_error' class="span_validation_label contact_user_email_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

      	<div class="form-group">
          <label for="contact_select_country" class="col-md-3 control-label"><?php echo lang('p_country'); ?>:</label>  
          <div class="col-md-9 input-group">
            <select class='contact_select_country form-control'>
              <option value=''><?php echo lang('p_country_into_select'); ?></option>
            </select>
          </div>
          <div class='error_validation_contact_select_country hidden'>
            <label for="contact_select_country_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9 contact_select_country">
              <p id='contact_select_country_validation_error' class="span_validation_label contact_select_country_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
					<label for="comment_phone" class="col-md-3 control-label"><?php echo lang('p_phone'); ?>:</label>	
					<div class="col-md-9">
						<input type='text' id='comment_phone' class='comment_phone form-control'/> 
					</div>
				</div>

				<div class="form-group">
					<label for="contact_comment" class="col-md-3 control-label"><?php echo lang('p_comment'); ?>:</label>	
					<div class="col-md-9">
						<textarea rows="4" id='contact_comment' class='contact_comment form-control'></textarea>
					</div>
					<div class='error_validation_contact_comment hidden'>
						<label for="contact_comment_validation_error" class="col-md-3 control-label"></label>
						<div class="col-md-9">
				      <p id='contact_comment_validation_error' class="span_validation_label contact_comment_validation_error form-control-static"></p>
				    </div>
			    </div>
				</div>

        	<div class="form-group">
	          <label class="col-md-3 control-label"></label>  
	          <div class="col-md-9">
		          <div class='contact_image_captcha'>
								<?php echo $captcha['image']; ?>
							</div>
	          </div>
	        </div>

	        <div class="form-group">
	          <label class="col-md-3 control-label"></label>
	          <div class="col-md-9">
	            <p class="contact_reload_captcha_zone form-control-static">
	              <?php echo lang('p_reload_captcha'); ?> <img class='image_captcha_refresh' src='<?php echo $this->config->item('base_url') . 'assets/icons/refresh.png'; ?>'></img>
	            </p>
	          </div>
	        </div>

	        <div class="form-group">
	          <label for="contact_div_captcha" class="col-md-3 control-label"><?php echo lang('p_captcha'); ?>:</label>  
	          <div class="col-md-9">
		          <input type='text' id='contact_div_captcha' class='captcha_input contact_div_captcha form-control'/> 
	          </div>
	          <div class='error_validation_contact_captcha hidden'>
	            <label for="contact_captcha_validation_error" class="col-md-3 control-label"></label>
	            <div class="col-md-9">
	              <p id='contact_captcha_validation_error' class="span_validation_label contact_captcha_validation_error form-control-static"></p>
	            </div>
	          </div>
	        </div>

        <div class="form-group">
          <label for="contact_button_enter" class="col-md-3 control-label"></label>
          <div class="col-md-9">
            <p id='contact_button_enter' class="contact_button_enter form-control-static">
              <a class='btn btn-default btn-form-submit contact_button_enter_button'><?php echo lang('p_send'); ?></a>
            </p>
          </div>
        </div>

        <div class="form-group container hidden">
          <div class='contact_data_alert_div col-md-12 pull-right'>
            <label class="col-md-3 control-label"></label>
            <div class="contact_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
            <div class="contact_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          </div>
        </div>

      <?php echo form_close(); ?>

    </div>

  </div>

  <div class='div_content_contact_right col-md-2'></div>

</div>