<div class='content_page_left content'>

	<input type='hidden' class='forgetpasswordnew_token' value='<?php echo $token; ?>'/>

  <div class='div_content_forgetpasswordnew_left col-md-2'></div>

  <div class='div_content_forgetpassword div_content_panel col-md-8 panel panel-default'>

    <div class='content_forgetpasswordnew_title panel-heading'>
      <?php echo lang('p_new_password'); ?><br/>
    </div>

    <div class='content_forgetpasswordnew_body panel-body'>

      <?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

      	<div class="form-group">
          <label for="forgetpasswordnew_user_password" class="col-md-3 control-label"><?php echo lang('p_password'); ?>:</label>  
          <div class="col-md-9">
            <input type='password' id='forgetpasswordnew_user_password' class='forgetpasswordnew_user_password form-control'/> 
          </div>
          <div class='error_validation_forgetpasswordnew_user_password hidden'>
            <label for="forgetpasswordnew_user_password_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='forgetpasswordnew_user_password_validation_error' class="span_validation_label forgetpasswordnew_user_password_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="forgetpasswordnew_user_repassword" class="col-md-3 control-label"><?php echo lang('p_repassword'); ?>:</label>  
          <div class="col-md-9">
            <input type='password' id='forgetpasswordnew_user_repassword' class='forgetpasswordnew_user_repassword form-control'/> 
          </div>
          <div class='error_validation_forgetpasswordnew_user_repassword hidden'>
            <label for="forgetpasswordnew_user_repassword_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='forgetpasswordnew_user_repassword_validation_error' class="span_validation_label forgetpasswordnew_user_repassword_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        	<div class="form-group">
	          <label class="col-md-3 control-label"></label>  
	          <div class="col-md-9">
		          <div class='forgetpasswordnew_image_captcha'>
								<?php echo $captcha['image']; ?>
							</div>
	          </div>
	        </div>

	        <div class="form-group">
	          <label class="col-md-3 control-label"></label>
	          <div class="col-md-9">
	            <p class="forgetpasswordnew_reload_captcha_zone form-control-static">
	              <?php echo lang('p_reload_captcha'); ?> <img class='image_captcha_refresh' src='<?php echo $this->config->item('base_url') . 'assets/icons/refresh.png'; ?>'></img>
	            </p>
	          </div>
	        </div>

	        <div class="form-group">
	          <label for="forgetpasswordnew_div_captcha" class="col-md-3 control-label"><?php echo lang('p_captcha'); ?>:</label>  
	          <div class="col-md-9">
		          <input type='text' id='forgetpasswordnew_div_captcha' class='captcha_input forgetpasswordnew_div_captcha form-control'/> 
	          </div>
	          <div class='error_validation_forgetpasswordnew_captcha hidden'>
	            <label for="forgetpasswordnew_captcha_validation_error" class="col-md-3 control-label"></label>
	            <div class="col-md-9">
	              <p id='forgetpasswordnew_captcha_validation_error' class="span_validation_label forgetpasswordnew_captcha_validation_error form-control-static"></p>
	            </div>
	          </div>
	        </div>

        <div class="form-group">
          <label for="forgetpasswordnew_button_enter" class="col-md-3 control-label"></label>
          <div class="col-md-9">
            <p id='forgetpasswordnew_button_enter' class="forgetpasswordnew_button_enter form-control-static">
              <a class='btn btn-default btn-form-submit forgetpasswordnew_button_enter_button'><?php echo lang('p_save_password'); ?></a>
            </p>
          </div>
        </div>

        <div class="form-group hidden">
          <div class="col-md-12">
            <p id='forgetpasswordnew_div_login' class="forgetpasswordnew_div_login form-control-static">
              <?php echo anchor('' . getActLang() . '/login/',lang('p_init_session')); ?>
            </p>
          </div>
        </div>

        <div class="form-group container hidden">
          <div class='forgetpasswordnew_data_alert_div col-md-12 pull-right'>
            <label class="col-md-3 control-label"></label>
            <div class="forgetpasswordnew_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
            <div class="forgetpasswordnew_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          </div>
        </div>

      <?php echo form_close(); ?>

    </div>

  </div>

  <div class='div_content_forgetpasswordnew_right col-md-2'></div>

</div>