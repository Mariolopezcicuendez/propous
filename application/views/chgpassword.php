<div class='content_page_left content'>

  <div class='div_content_chgpassword_left col-md-2'></div>

  <div class='div_content_chgpassword div_content_panel col-md-8 panel panel-default'>

    <div class='content_chgpassword_title panel-heading'>
      <?php echo lang('p_chgpassword'); ?><br/>
    </div>

    <div class='content_chgpassword_body panel-body'>

      <?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

      	<div class="form-group">
          <label for="chgpassword_user_oldpassword" class="col-md-3 control-label"><?php echo lang('p_oldpassword'); ?>:</label>  
          <div class="col-md-9">
            <input type='password' id='chgpassword_user_oldpassword' class='chgpassword_user_oldpassword form-control'/> 
          </div>
          <div class='error_validation_chgpassword_user_oldpassword hidden'>
            <label for="chgpassword_user_oldpassword_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='chgpassword_user_oldpassword_validation_error' class="span_validation_label chgpassword_user_oldpassword_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

      	<div class="form-group">
          <label for="chgpassword_user_password" class="col-md-3 control-label"><?php echo lang('p_password'); ?>:</label>  
          <div class="col-md-9">
            <input type='password' id='chgpassword_user_password' class='chgpassword_user_password form-control'/> 
          </div>
          <div class='error_validation_chgpassword_user_password hidden'>
            <label for="chgpassword_user_password_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='chgpassword_user_password_validation_error' class="span_validation_label chgpassword_user_password_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="chgpassword_user_repassword" class="col-md-3 control-label"><?php echo lang('p_repassword'); ?>:</label>  
          <div class="col-md-9">
            <input type='password' id='chgpassword_user_repassword' class='chgpassword_user_repassword form-control'/> 
          </div>
          <div class='error_validation_chgpassword_user_repassword hidden'>
            <label for="chgpassword_user_repassword_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='chgpassword_user_repassword_validation_error' class="span_validation_label chgpassword_user_repassword_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        	<div class="form-group">
	          <label class="col-md-3 control-label"></label>  
	          <div class="col-md-9">
		          <div class='chgpassword_image_captcha'>
								<?php echo $captcha['image']; ?>
							</div>
	          </div>
	        </div>

	        <div class="form-group">
	          <label class="col-md-3 control-label"></label>
	          <div class="col-md-9">
	            <p class="chgpassword_reload_captcha_zone form-control-static">
	              <?php echo lang('p_reload_captcha'); ?> <img class='image_captcha_refresh' src='<?php echo $this->config->item('base_url') . 'assets/icons/refresh.png'; ?>'></img>
	            </p>
	          </div>
	        </div>

	        <div class="form-group">
	          <label for="chgpassword_div_captcha" class="col-md-3 control-label"><?php echo lang('p_captcha'); ?>:</label>  
	          <div class="col-md-9">
		          <input type='text' id='chgpassword_div_captcha' class='captcha_input chgpassword_div_captcha form-control'/> 
	          </div>
	          <div class='error_validation_chgpassword_captcha hidden'>
	            <label for="chgpassword_captcha_validation_error" class="col-md-3 control-label"></label>
	            <div class="col-md-9">
	              <p id='chgpassword_captcha_validation_error' class="span_validation_label chgpassword_captcha_validation_error form-control-static"></p>
	            </div>
	          </div>
	        </div>

        <div class="form-group">
          <label for="chgpassword_button_enter" class="col-md-3 control-label"></label>
          <div class="col-md-9">
            <p id='chgpassword_button_enter' class="chgpassword_button_enter form-control-static">
              <a class='btn btn-default btn-form-submit chgpassword_button_enter_button'><?php echo lang('p_save'); ?></a>
            </p>
          </div>
        </div>

        <div class="form-group hidden">
          <div class="col-md-12">
            <p id='chgpassword_div_logout' class="chgpassword_div_logout form-control-static">
              <button class='btn btn-link chgpassword_press_for_logout'><?php echo lang('p_press_for_logout'); ?></span>
            </p>
          </div>
        </div>

        <div class="form-group container hidden">
          <div class='chgpassword_data_alert_div col-md-12 pull-right'>
            <label class="col-md-3 control-label"></label>
            <div class="chgpassword_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
            <div class="chgpassword_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          </div>
        </div>

      <?php echo form_close(); ?>

    </div>

  </div>

  <div class='div_content_chgpassword_right col-md-2'></div>

</div>