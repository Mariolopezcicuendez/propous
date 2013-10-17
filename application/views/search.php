<?php if ($this->session->userdata('logged_in')) : ?>
  <div class='content panel panel-default'>

    <div class='content_search_body content_page_menu_div_search col-md-12 content panel-body'>

      <?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>
        <div class="row container form-group">
          <label class="col-md-3 control-label"><?php echo lang('p_search_any_text'); ?>: </label>  
          <div class="col-md-4">
            <input type='text' id='search_simple_input' class='search_simple_input form-control'/> 
          </div>
          <div class='col-md-3'>
            <button id='search_button_at_simple' class='btn btn-default search_button'><?php echo lang('p_search'); ?></button>
          </div>
          <a class='advanced_search_link advanced_search_link_show pull-right'><?php echo lang('p_advanced_search_show'); ?></a>
          <a class='advanced_search_link advanced_search_link_hide pull-right hidden'><?php echo lang('p_advanced_search_hide'); ?></a>
        </div>
      <?php echo form_close(); ?>

      <div class='row container content_page_menu_div_advanced_search hidden'>
      
        <?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

          <div class='content_page_menu_div_advanced_search_left pull-left'>

            <div class="form-group">
              <label class="col-md-3 control-label"><?php echo lang('p_category'); ?>:</label>  
              <div class="col-md-9">
                <select multiple class="advanced_search_categories_select form-control">
                  <option value=""><?php echo lang('p_category_into_select'); ?></option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label"><?php echo lang('p_localization'); ?>:</label> 
              <div class="col-md-9 input-group">
                <select class='advanced_search_country input_inline_2_elements_full form-control' disabled>
                  <option value=''><?php echo lang('p_country_into_select'); ?></option>
                </select> 
                <select class='advanced_search_state input_inline_2_elements_full form-control' disabled>
                  <option value=''><?php echo lang('p_state_into_select'); ?></option>
                </select>
              </div>
            </div>

          </div>

          <div class='content_page_menu_div_advanced_search_right pull-right'>

            <div class="form-group">
              <label class="col-md-3 control-label"><?php echo lang('p_date_from'); ?>:</label> 
              <div class="col-md-9 input-group">
                
                  <select class='advanced_search_select_day_from input_inline_3_elements_full form-control'>
                    <option value=''><?php echo lang('p_day_into_select'); ?></option>
                    <?php for ($i=1; $i <= 31; $i++) : ?>
                      <?php $i_mod = "".$i; if (strlen($i_mod) === 1) $i_mod = "0".$i_mod; ?>
                      <option value='<?php echo $i_mod; ?>'><?php echo $i_mod; ?></option>
                    <?php endfor; ?>
                  </select> 
                  <select class='advanced_search_select_month_from input_inline_3_elements_full form-control'>
                    <option value=''><?php echo lang('p_month_into_select'); ?></option>
                    <?php for ($i=1; $i <= 12; $i++) : ?>
                      <?php $i_mod = "".$i; if (strlen($i_mod) === 1) $i_mod = "0".$i_mod; ?>
                      <option value='<?php echo $i_mod; ?>'><?php echo lang('cal_month_'.$i_mod); ?></option>
                    <?php endfor; ?>
                  </select> 
                  <select class='advanced_search_select_year_from input_inline_3_elements_full form-control'>
                      <option value=''><?php echo lang('p_year_into_select'); ?></option>
                    <?php for ($i=2013; $i <= date("Y"); $i++) : ?>
                      <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
                    <?php endfor; ?>
                  </select>

              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label"><?php echo lang('p_date_to'); ?>:</label> 
              <div class="col-md-9 input-group">
                
                <select class='advanced_search_select_day_to input_inline_3_elements_full form-control'>
                  <option value=''><?php echo lang('p_day_into_select'); ?></option>
                  <?php for ($i=1; $i <= 31; $i++) : ?>
                    <?php $i_mod = "".$i; if (strlen($i_mod) === 1) $i_mod = "0".$i_mod; ?>
                    <option value='<?php echo $i_mod; ?>'><?php echo $i_mod; ?></option>
                  <?php endfor; ?>
                </select> 
                <select class='advanced_search_select_month_to input_inline_3_elements_full form-control'>
                  <option value=''><?php echo lang('p_month_into_select'); ?></option>
                  <?php for ($i=1; $i <= 12; $i++) : ?>
                    <?php $i_mod = "".$i; if (strlen($i_mod) === 1) $i_mod = "0".$i_mod; ?>
                    <option value='<?php echo $i_mod; ?>'><?php echo lang('cal_month_'.$i_mod); ?></option>
                  <?php endfor; ?>
                </select> 
                <select class='advanced_search_select_year_to input_inline_3_elements_full form-control'>
                    <option value=''><?php echo lang('p_year_into_select'); ?></option>
                  <?php for ($i=2013; $i <= date("Y"); $i++) : ?>
                    <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
                  <?php endfor; ?>
                </select>

              </div>
            </div>

          </div>

        <?php echo form_close(); ?>

      </div> 

      <div class='row container pull-right hidden'>
        <button id='search_button_at_advance' class='btn btn-default search_button'><?php echo lang('p_search'); ?></button>
      </div>

    </div>

  </div> 

<?php endif; ?> 