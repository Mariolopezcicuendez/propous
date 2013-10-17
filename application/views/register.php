<div class='content_page_left content'>

  <div class='div_content_register_left col-md-2'></div>

  <div class='div_content_register div_content_panel col-md-8 panel panel-default'>

    <div class='content_register_title panel-heading'>
      <?php echo lang('p_register'); ?><br/>
    </div>

    <div class='content_register_body panel-body'>

      <?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

        <div class="form-group">
          <label for="register_login_zone" class="col-md-3 control-label"></label>
          <div class="col-md-9">
            <p id='register_login_zone' class="register_login_zone form-control-static">
              <?php echo lang('p_register_or'); ?><?php echo anchor('/' . getActLang() . '/login/',lang('p_init_session')); ?>
            </p>
          </div>
        </div>

        <div class="form-group">
          <label for="register_user_name" class="col-md-3 control-label"><?php echo lang('p_username'); ?>:</label>  
          <div class="col-md-9">
            <input type='text' id='register_user_name' class='register_user_name form-control'/> 
          </div>
          <div class='error_validation_register_user_name hidden'>
            <label for="register_user_name_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='register_user_name_validation_error' class="span_validation_label register_user_name_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="register_user_email" class="col-md-3 control-label"><?php echo lang('p_email'); ?>:</label>  
          <div class="col-md-9">
            <input type='text' id='register_user_email' class='register_user_email form-control'/> 
          </div>
          <div class='error_validation_register_user_email hidden'>
            <label for="register_user_email_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='register_user_email_validation_error' class="span_validation_label register_user_email_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="register_user_password" class="col-md-3 control-label"><?php echo lang('p_password'); ?>:</label>  
          <div class="col-md-9">
            <input type='password' id='register_user_password' class='register_user_password form-control'/> 
          </div>
          <div class='error_validation_register_user_password hidden'>
            <label for="register_user_password_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='register_user_password_validation_error' class="span_validation_label register_user_password_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="register_user_repassword" class="col-md-3 control-label"><?php echo lang('p_repassword'); ?>:</label>  
          <div class="col-md-9">
            <input type='password' id='register_user_repassword' class='register_user_repassword form-control'/> 
          </div>
          <div class='error_validation_register_user_repassword hidden'>
            <label for="register_user_repassword_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='register_user_repassword_validation_error' class="span_validation_label register_user_repassword_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="register_select_day" class="col-md-3 control-label"><?php echo lang('p_birthdate'); ?>:</label>  
          <div class="col-md-9 input-group">
            <select class='register_select_day input_inline_3_elements_full form-control'>
              <option value=''><?php echo lang('p_day_into_select'); ?></option>
              <?php for ($i=1; $i <= 31; $i++) : ?>
                <?php $i_mod = "".$i; if (strlen($i_mod) === 1) $i_mod = "0".$i_mod; ?>
                <option value='<?php echo $i_mod; ?>'><?php echo $i_mod; ?></option>
              <?php endfor; ?>
            </select>
            <select class='register_select_month input_inline_3_elements_full form-control'>
              <option value=''><?php echo lang('p_month_into_select'); ?></option>
              <?php for ($i=1; $i <= 12; $i++) : ?>
                <?php $i_mod = "".$i; if (strlen($i_mod) === 1) $i_mod = "0".$i_mod; ?>
                <option value='<?php echo $i_mod; ?>'><?php echo lang('cal_month_'.$i_mod); ?></option>
              <?php endfor; ?>
            </select> 
            <select class='register_select_year input_inline_3_elements_full form-control'>
                <option value=''><?php echo lang('p_year_into_select'); ?></option>
              <?php for ($i=1900; $i <= date("Y"); $i++) : ?>
                <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
              <?php endfor; ?>
            </select> 
          </div>
          <div class='error_validation_register_select_year hidden'>
            <label for="register_select_year_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='register_select_year_validation_error' class="span_validation_label register_select_year_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="register_select_country" class="col-md-3 control-label"><?php echo lang('p_localization'); ?>:</label>  
          <div class="col-md-9 input-group">
            <select class='register_select_country input_inline_2_elements_full form-control'>
              <option value=''><?php echo lang('p_country_into_select'); ?></option>
            </select>
            <select class='register_select_state input_inline_2_elements_full form-control' disabled>
              <option value=''><?php echo lang('p_state_into_select'); ?></option>
            </select> 
          </div>
          <div class='error_validation_register_select_region hidden'>
            <label for="register_select_region_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9 register_select_region">
              <p id='register_select_region_validation_error' class="span_validation_label register_select_region_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-3 control-label"><?php echo lang('p_gender'); ?>:</label>  
          <div class="col-md-9">
            <label class="radio-inline">
              <input type="radio" class='register_radio_gender' name="register_radio_gender" id="register_radio_gender_M" value="M" checked>
              <?php echo lang('p_male'); ?>
            </label>
            <label class="radio-inline">
              <input type="radio" class='register_radio_gender' name="register_radio_gender" id="register_radio_gender_F" value="F">
              <?php echo lang('p_female'); ?>
            </label>
          </div>
          <div class='error_validation_register_radio_gender hidden'>
            <label for="register_radio_gender_validation_error" class="col-md-3 control-label"></label>
            <div class="col-md-9">
              <p id='register_radio_gender_validation_error' class="span_validation_label register_radio_gender_validation_error form-control-static"></p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="register_button_enter" class="col-md-3 control-label"></label>
          <div class="col-md-9">
            <p id='register_button_enter' class="register_button_enter form-control-static">
              <a class='btn btn-default btn-form-submit register_button_enter_button'><?php echo lang('p_enter'); ?></a>
            </p>
          </div>
        </div>

        <div class="form-group container hidden">
          <div class='register_data_alert_div col-md-12 pull-right'>
            <label class="col-md-3 control-label"></label>
            <div class="register_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
            <div class="register_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          </div>
        </div>

        <div class="form-group">
          <label for="register_accept_register" class="col-md-3 control-label"></label>
          <div class="col-md-12">
            <p id='register_accept_register' class="register_accept_register form-control-static">
              <?php echo lang('p_continue_and_accept'); ?>
              <?php echo anchor('/' . getActLang() . '/useconditions',lang('p_use_conditions'),array('target' => '_blank')); ?>, 
              <?php echo anchor('/' . getActLang() . '/privacy',lang('p_privacy_polity'),array('target' => '_blank')); ?> 
              <?php echo lang('p_and'); ?>
              <?php echo anchor('/' . getActLang() . '/privacy/#cookies',lang('p_cookies_polity'),array('target' => '_blank')); ?> 
            </p>
          </div>
        </div>

      <?php echo form_close(); ?>

    </div>

  </div>

  <div class='div_content_register_right col-md-2'></div>

</div>