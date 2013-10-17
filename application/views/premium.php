<div class='content_page_left content'>

   <div class="div_content_info form-group">
      
      <h3 class='title_zone'><?php echo lang('p_title_premium_page'); ?></h3>

      <?php echo form_open('#',array("class"=>"form-horizontal hidden","role"=>"form")); ?>
    <?php echo form_close(); ?>
    
      <div class="page_info_paragraph form-group">
        <div class="col-md-12">
          <p id='info_zone' class="info_zone form-control-static">
            <?php echo lang('p_paragraph_premium_1'); ?>
          </p>
        </div>
      </div>

      <div class='content_page_premium_mods_div'>

      	<div class="page_info_paragraph form-group">
					<div class='content_premium_mod_div_free col-md-12'>
						free div
					</div>	
				</div>

				<div class="page_info_paragraph form-group">
					<div class='content_premium_mod_div content_premium_mod_div_premium col-md-12' premium_tag='premium'>
						premium div
					</div>	
				</div>

				<div class="page_info_paragraph form-group">
					<div class='content_premium_mod_div content_premium_mod_div_silver col-md-12' premium_tag='silver'>
						silver div
					</div>	
				</div>

				<div class="page_info_paragraph form-group">
					<div class='content_premium_mod_div content_premium_mod_div_gold col-md-12' premium_tag='gold'>
						gold div
					</div>	
				</div>

				<div class="page_info_paragraph form-group">
					<div class='content_premium_mod_div content_premium_mod_div_diamond col-md-12' premium_tag='diamond'>
						diamond div
					</div>		
				</div>
				
			</div>

    </div>

</div>

<div class="form-group container hidden">
  <div class='premium_data_alert_div col-md-12 pull-right'>
    <label class="col-md-3 control-label"></label>
    <div class="premium_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
    <div class="premium_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
  </div>
</div>