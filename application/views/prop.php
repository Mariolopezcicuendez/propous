<div class='content_page_left content'>

  <div class='div_content_prop content panel panel-default'>

    <div class='content_prop_title col-md-12 content panel-heading'>
      <?php echo lang('p_props'); ?><br/>
    </div>

    <?php echo form_open('#',array("class"=>"form-horizontal hidden","role"=>"form")); ?>
    <?php echo form_close(); ?>

    <div class='prop_content_main_table col-md-12 content panel-body'>

      <div class="table-responsive">
        <table id="prop_main_table_props" class="table table-striped prop_main_table_props">
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
  <button type="button" class="btn btn-link prop_new_prop pull-right"><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/newprop.png'/> <?php echo lang('p_create_new_prop'); ?></button>
</div>