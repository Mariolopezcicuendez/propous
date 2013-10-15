<div class='content_page_left content'>

   <div class="div_content_info form-group">
      
      <h3 class='title_zone'><?php echo lang('p_delete_prop'); ?></h3>

      <div class="page_info_paragraph form-group">
        <div class="col-md-12">
          <p id='info_zone' class="info_zone form-control-static">
            <?php echo lang('p_confirm_delete_prop'); ?>
            <br/>
            <button class='btn btn-default deleteprop_delete_button'><?php echo lang('p_confirm_delete_prop_yes'); ?></button>
          </p>
        </div>
      </div>

      <div class="form-group hidden">
        <div class="col-md-12">
          <p id='deleteprop_div_return_myprops' class="deleteprop_div_return_myprops form-control-static">
            <button class='btn btn-link deleteprop_return_myprops'><?php echo lang('p_press_for_return_myprops'); ?></span>
          </p>
        </div>
      </div>

    </div>

</div>

<div class="form-group container hidden">
  <div class='deleteprop_data_alert_div col-md-12 pull-right'>
    <label class="col-md-3 control-label"></label>
    <div class="deleteprop_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
    <div class="deleteprop_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
  </div>
</div>