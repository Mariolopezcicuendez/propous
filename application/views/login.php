<div class='content_page_left content'>

  <div class='content_login_main_words'>
      <?php echo lang('p_login_page_words'); ?>
    </div>

  <div class='div_content_login div_content_panel col-md-4 panel panel-default pull-right'>

    <div class='content_login_title panel-heading'>
      <?php echo lang('p_login'); ?>
    </div>

    <div class='content_login_body panel-body'>

      <?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

        <div class="form-group">
          <label for="login_register_zone" class="col-md-3 control-label"></label>
          <div class="col-md-9">
            <p id='login_register_zone' class="login_register_zone form-control-static">
              <?php echo lang('p_enter_with_your_account',array(anchor('/' . getActLang() . '/register/',lang('p_register')))); ?>
            </p>
          </div>
        </div>

        <div class="form-group">
          <label for="login_user_email" class="col-md-3 control-label"><?php echo lang('p_email'); ?>:</label>  
          <div class="col-md-9">
            <input type='text' id='login_user_email' class='login_user_email form-control'/> 
          </div>
          <div class='error_validation_login_user_email hidden'>
            <label for="login_user_email_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='login_user_email_validation_error' class="span_validation_label login_user_email_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="login_user_password" class="col-md-3 control-label"><?php echo lang('p_password'); ?>:</label>  
          <div class="col-md-9">
            <input type='password' id='login_user_password' class='login_user_password form-control'/> 
          </div>
          <div class='error_validation_login_user_password hidden'>
            <label for="login_user_password_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='login_user_password_validation_error' class="span_validation_label login_user_password_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="login_user_remember" class="col-md-3 control-label"></label>
          <div class="col-md-9 checkbox">
            <label id='login_user_remember' title='<?php echo lang('p_remember_for_next_access'); ?>' class="login_user_remember form-control-static">
              <input type='checkbox' class='login_user_remember'/> <?php echo lang('p_remember_login'); ?>
            </label>
          </div>
        </div>

        <div class="form-group">
          <label for="login_forgetpassword_zone" class="col-md-3 control-label"></label>
          <div class="col-md-9">
            <p id='login_forgetpassword_zone' class="login_forgetpassword_zone form-control-static">
              <?php echo anchor('/' . getActLang() . '/forgetpassword/',lang('p_forget_password_int')); ?>
            </p>
          </div>
        </div>

        <div class="form-group">
          <label for="login_button_enter" class="col-md-3 control-label"></label>
          <div class="col-md-9">
            <p id='login_button_enter' class="login_button_enter form-control-static">
              <a class='btn btn-default btn-form-submit login_button_enter_button'><?php echo lang('p_enter'); ?></a>
            </p>
          </div>
        </div>

        <div class="form-group container hidden">
          <div class='login_data_alert_div col-md-12 pull-right'>
            <label class="col-md-3 control-label"></label>
            <div class="login_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
            <div class="login_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          </div>
        </div>

        <div class="form-group">
          <label for="login_accept_login" class="col-md-3 control-label"></label>
          <div class="col-md-12">
            <p id='login_accept_login' class="login_accept_login form-control-static">
              <?php 
              $data_continue = array(
                anchor('/' . getActLang() . '/useconditions',lang('p_use_conditions'),array('target' => '_blank')),
                anchor('/' . getActLang() . '/privacy',lang('p_privacy_polity'),array('target' => '_blank')),
                anchor('/' . getActLang() . '/privacy/#cookies',lang('p_cookies_polity'),array('target' => '_blank'))
              );
              ?>
              <?php echo lang('p_continue_and_accept_all',$data_continue); ?>
            </p>
          </div>
        </div>

      <?php echo form_close(); ?>

    </div>

  </div>

</div>