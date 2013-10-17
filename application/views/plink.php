<div class='content_page_left content'>

   <div class="div_content_plink form-group">
      
      <h3 class='title_zone'><?php echo lang('p_title_'.$tag); ?></h3>

      <?php echo form_open('#',array("class"=>"form-horizontal hidden","role"=>"form")); ?>
    <?php echo form_close(); ?>
    
      <div class="div_content_plink form-group">
        <div class="col-md-9">
          <p id='plink_zone' class="plink_zone form-control-static">
            <?php if (($result === 1) && ($tag === 'activeaccount')) : ?>
              <?php echo anchor('/' . getActLang() . '/login/',lang('p_init_session')); ?> 
            <?php endif; ?>
          </p>
        </div>
      </div>

      <div class="form-group container">
        <div class='plink_data_alert_div col-md-12 pull-right'>
          <label class="col-md-3 control-label"></label>
          <?php if ($result === 1) : ?>
            <div class="plink_data_alert_success alert alert-success"><?php echo $message; ?><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          <?php endif; ?>
          <?php if ($result !== 1) : ?>
            <div class="plink_data_alert_fail alert alert-danger"><?php echo $message; ?><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          <?php endif; ?>
        </div>
      </div>

    </div>

</div>