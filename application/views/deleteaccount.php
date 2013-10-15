<div class='content_page_left content'>

  <div class='div_content_deleteaccount_left col-md-2'></div>

  <div class='div_content_deleteaccount div_content_panel col-md-8 panel panel-default'>

    <div class='content_deleteaccount_title panel-heading'>
      <?php echo lang('p_delete_account'); ?><br/>
    </div>

    <div class='content_deleteaccount_body panel-body'>

      <form class="form-horizontal" role="form">

        <div class="form-group">
          <label for="deleteaccount_user_password" class="col-md-3 control-label"><?php echo lang('p_password'); ?>:</label>  
          <div class="col-md-9">
            <input type='password' id='deleteaccount_user_password' class='deleteaccount_user_password form-control'/> 
          </div>
          <div class='error_validation_deleteaccount_user_password hidden'>
            <label for="deleteaccount_user_password_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='deleteaccount_user_password_validation_error' class="span_validation_label deleteaccount_user_password_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <?php if (USE_CAPTHAS_IN_FORMS === "true") : ?>

          <div class="form-group">
            <label class="col-md-3 control-label"></label>  
            <div class="col-md-9">
              <div class='deleteaccount_image_captcha'>
                <?php echo $captcha['image']; ?>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p class="deleteaccount_reload_captcha_zone form-control-static">
                <?php echo lang('p_reload_captcha'); ?> <img class='image_captcha_refresh' src='<?php echo $this->config->item('base_url') . 'assets/icons/refresh.png'; ?>'></img>
              </p>
            </div>
          </div>

          <div class="form-group">
            <label for="deleteaccount_div_captcha" class="col-md-3 control-label"><?php echo lang('p_captcha'); ?>:</label>  
            <div class="col-md-9">
              <input type='text' id='deleteaccount_div_captcha' class='captcha_input deleteaccount_div_captcha form-control'/> 
            </div>
            <div class='error_validation_deleteaccount_captcha hidden'>
              <label for="deleteaccount_captcha_validation_error" class="col-md-3 control-label"></label>
              <div class="col-md-9">
                <p id='deleteaccount_captcha_validation_error' class="span_validation_label deleteaccount_captcha_validation_error form-control-static"></p>
              </div>
            </div>
          </div>

        <?php endif; ?>

        <div class="form-group">
          <label class="col-md-3 control-label"></label>  
          <div class="col-md-9">
            <p id='info_zone' class="info_zone form-control-static">
              <?php echo lang('p_confirm_delete_account'); ?>
              <br/>
              <a class='btn btn-default btn-form-submit deleteaccount_delete_button'><?php echo lang('p_confirm_delete_account_yes'); ?></a>
            </p>
          </div>
        </div>

        <div class="form-group hidden">
          <label class="col-md-3 control-label"></label>  
          <div class="col-md-9">
            <p id='deleteaccount_div_logout' class="deleteaccount_div_logout form-control-static">
              <a class='btn btn-link deleteaccount_press_for_logout'><?php echo lang('p_press_for_logout'); ?></a>
            </p>
          </div>
        </div>

      </form>

    </div>

  </div>

  <div class='div_content_chgpassword_right col-md-2'></div>

</div>

<div class="form-group container hidden">
  <div class='deleteaccount_data_alert_div col-md-12 pull-right'>
    <label class="col-md-3 control-label"></label>
    <div class="deleteaccount_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
    <div class="deleteaccount_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
  </div>
</div>