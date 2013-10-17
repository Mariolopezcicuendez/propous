<div class='content_page_left content'>

   <div class="div_content_activateaccount form-group">
      
      <h3 class='title_zone'><?php echo lang('p_title_activateaccount'); ?></h3>

      <?php echo form_open('#',array("class"=>"form-horizontal hidden","role"=>"form")); ?>
    <?php echo form_close(); ?>
    
      <div class="div_content_activateaccount form-group">
        <div class="col-md-9">
          <p id='activateaccount_zone' class="activateaccount_zone form-control-static">
            <?php echo lang('p_account_activation_look_email'); ?>
            <br/>
            <?php echo anchor('/' . getActLang() . '/login/',lang('p_init_session')); ?> 
          </p>
        </div>
      </div>

    </div>

</div>