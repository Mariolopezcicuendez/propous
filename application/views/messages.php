<div class='content_page_left content'>

  <div class='div_content_chat content panel panel-default'>

    <div class='content_chat_title col-md-12 content panel-heading'>
      <?php echo lang('p_messages'); ?><br/>
    </div>

    <?php echo form_open('#',array("class"=>"form-horizontal hidden","role"=>"form")); ?>
    <?php echo form_close(); ?>
    
    <div class='content_chat_body col-md-12 content panel-body'>

      <div class='div_content_chat_users col-md-3'>

        <div class='div_content_chat_users_list'>
          
          <div class='div_content_chat_users_list_div media-list'>
          </div>

        </div>
        
        <div class='div_content_chat_users_count content'>
          
          <div class="form-group">
            <div class="col-md-12 input-group">
              
              <span class='input_inline_3_elements_full'><?php echo lang('p_show_last'); ?>:</span>
              <select class='div_content_chat_users_count_select form-control input_inline_3_elements_full'>
                <option value='20'>20</option>
                <option value='50'>50</option>
                <option value='100'>100</option>
              </select>
              <span class='input_inline_3_elements_full'><?php echo lang('p_conversations'); ?></span>
              
            </div>
          </div>

        </div>

      </div>

      <div class='div_content_chat_messages content col-md-9'>

        <div class='div_content_chat_messages_title content'>

        </div>
        
        <div id='div_content_chat_messages_conversation' class='div_content_chat_messages_conversation'>

        </div>
        
        <div class='div_content_chat_messages_pagination content'>
          
          <div class="form-group">
            <div class="col-md-12 input-group">
              
              <span><?php echo lang('p_show'); ?>:</span>
              <select class='div_content_chat_messages_count_select form-control'>
                <option value='100'><?php echo lang('p_last_100_messages'); ?></option>
                <option value='500'><?php echo lang('p_last_500_messages'); ?></option>
                <option value='999999'><?php echo lang('p_all_messages'); ?></option>
              </select>

            </div>
          </div>

        </div>

    </div>

  </div>

  <div class='div_content_chat_messages_actions content'>

          <div class="form-group">
            <div class="col-md-12 input-group">

              <div class='div_content_chat_messages_actions_textbox content'>
                <textarea rows="4" cols="50" class='messages_send_textbox form-control'></textarea>
              </div>

              <div class='div_content_chat_messages_actions_sendbutton content'>
                <button class='btn btn-default messages_send_button' disabled><?php echo lang('p_send'); ?></button>
              </div>

            </div>
          </div>

        </div>

      </div>

</div>

<div class="form-group container hidden">
  <div class='messages_data_alert_div col-md-12 pull-right'>
    <label class="col-md-3 control-label"></label>
    <div class="messages_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
    <div class="messages_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
  </div>
</div>