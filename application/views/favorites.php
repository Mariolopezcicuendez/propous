<div class='content_page_left content'>

  <div class='div_content_prop content panel panel-default'>

    <div class='content_prop_title col-md-12 content panel-heading'>
      <?php echo lang('p_favorites'); ?><br/>
    </div>

    <?php echo form_open('#',array("class"=>"form-horizontal hidden","role"=>"form")); ?>
    <?php echo form_close(); ?>
    
    <div class='prop_content_main_table col-md-12 content panel-body'>

      <div class="table-responsive">
        <table id="favorites_main_table_props" class="table table-striped favorites_main_table_props">
          <thead>
            <tr>
              <th><?php echo lang('p_props'); ?></th>
              <th></th>
              <th></th>
              <th><?php echo lang('p_social'); ?></th>
              <th><?php echo lang('p_actions'); ?></th>
              <th class='hidden'></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </div>

  </div>

</div>

<div class='content_prop_buttons container'>
  &nbsp;
</div>

<div class="form-group hidden">
  <div class='props_data_alert col-md-12 pull-right'>
    <label class="col-md-3 control-label"></label>
    <div class="props_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
    <div class="props_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
  </div>
</div>