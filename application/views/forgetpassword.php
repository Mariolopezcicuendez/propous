<div class='content_page_left content'>

  <div class='div_content_forgetpassword_left col-md-2'></div>

  <div class='div_content_forgetpassword div_content_panel col-md-8 panel panel-default'>

    <div class='content_forgetpassword_title panel-heading'>
      <?php echo lang('p_forget_password_int'); ?><br/>
    </div>

    <div class='content_forgetpassword_body panel-body'>

      <?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

      	<div class="form-group">
          <label for="forgetpassword_login_zone" class="col-md-3 control-label"></label>
          <div class="col-md-9">
            <p id='forgetpassword_login_zone' class="forgetpassword_login_zone form-control-static">
              <?php echo lang('p_forget_password_input_email'); ?>
            </p>
          </div>
        </div>

      	<div class="form-group">
          <label for="forgetpassword_user_email" class="col-md-3 control-label"><?php echo lang('p_email'); ?>:</label>  
          <div class="col-md-9">
            <input type='text' id='forgetpassword_user_email' class='forgetpassword_user_email form-control'/> 
          </div>
          <div class='error_validation_forgetpassword_user_email hidden'>
            <label for="forgetpassword_user_email_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='forgetpassword_user_email_validation_error' class="span_validation_label forgetpassword_user_email_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        	<div class="form-group">
	          <label class="col-md-3 control-label"></label>  
	          <div class="col-md-9">
		          <div class='forgetpassword_image_captcha'>
								<?php echo $captcha['image']; ?>
							</div>
	          </div>
	        </div>

	        <div class="form-group">
	          <label class="col-md-3 control-label"></label>
	          <div class="col-md-9">
	            <p class="forgetpassword_reload_captcha_zone form-control-static">
	              <?php echo lang('p_reload_captcha'); ?> <img class='image_captcha_refresh' src='<?php echo $this->config->item('base_url') . 'assets/icons/refresh.png'; ?>'></img>
	            </p>
	          </div>
	        </div>

	        <div class="form-group">
	          <label for="forgetpassword_div_captcha" class="col-md-3 control-label"><?php echo lang('p_captcha'); ?>:</label>  
	          <div class="col-md-9">
		          <input type='text' id='forgetpassword_div_captcha' class='captcha_input forgetpassword_div_captcha form-control'/> 
	          </div>
	          <div class='error_validation_forgetpassword_captcha hidden'>
	            <label for="forgetpassword_captcha_validation_error" class="col-md-3 control-label"></label>
	            <div class="col-md-9">
	              <p id='forgetpassword_captcha_validation_error' class="span_validation_label forgetpassword_captcha_validation_error form-control-static"></p>
	            </div>
	          </div>
	        </div>

        <div class="form-group">
          <label for="forgetpassword_button_enter" class="col-md-3 control-label"></label>
          <div class="col-md-9">
            <p id='forgetpassword_button_enter' class="forgetpassword_button_enter form-control-static">
              <a class='btn btn-default btn-form-submit forgetpassword_button_enter_button'><?php echo lang('p_new_password'); ?></a>
            </p>
          </div>
        </div>

        <div class="form-group container hidden">
          <div class='forgetpassword_data_alert_div col-md-12 pull-right'>
            <label class="col-md-3 control-label"></label>
            <div class="forgetpassword_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
            <div class="forgetpassword_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          </div>
        </div>

      <?php echo form_close(); ?>

    </div>

  </div>

  <div class='div_content_forgetpassword_right col-md-2'></div>

</div>