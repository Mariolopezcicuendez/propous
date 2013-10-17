<div class='content_page_left content'>

	<div class='content_viewprop_data content panel panel-default'>

		<div class='content_viewprop_title col-md-12 content panel-heading'>
			<?php echo lang('p_prop'); ?><br/>
		</div>

		<div class='content_viewprop_body col-md-12 content panel-body'>

			<h3 class='title_zone'><?php echo lang('p_data'); ?></h3>

			<div class="form-group">

				<div class='content_viewprop_data_left col-md-2'></div>

				<div class='content_viewprop_data_right col-md-10'>

					<?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

						<div class="form-group">
					    <label for="viewprop_prop_title" class="col-md-3 control-label"><?php echo lang('p_proposal_title'); ?>:</label>
					    <div class="col-md-9">
					      <p id='viewprop_prop_title' class="viewprop_prop_title form-control-static"></p>
					    </div>
					  </div>

					  <div class="form-group">
					    <label for="viewprop_prop_creationdate" class="col-md-3 control-label"><?php echo lang('p_proposal_creationdate'); ?>:</label>
					    <div class="col-md-9">
					      <p id='viewprop_prop_creationdate' class="viewprop_prop_creationdate form-control-static"></p>
					    </div>
					  </div>

					  <div class="form-group hidden">
					    <label for="viewprop_prop_description" class="col-md-3 control-label"><?php echo lang('p_proposal_description'); ?>:</label>
					    <div class="col-md-9">
					      <p id='viewprop_prop_description' class="viewprop_prop_description form-control-static"></p>
					    </div>
					  </div>

					  <div class="form-group">
					    <label for="viewprop_prop_region" class="col-md-3 control-label"><?php echo lang('p_localization'); ?>:</label>
					    <div class="col-md-9">
					      <p id='viewprop_prop_region' class="viewprop_prop_region form-control-static"></p>
					    </div>
					  </div>

					  <div class="form-group">
					    <label for="viewprop_prop_visited" class="col-md-3 control-label"><?php echo lang('p_visited'); ?>:</label>
					    <div class="col-md-9">
					      <p id='viewprop_prop_visited' class="viewprop_prop_visited form-control-static"></p>
					    </div>
					  </div>

					<?php echo form_close(); ?>

				</div>

			</div>

		<h3 class='title_zone'><?php echo lang('p_photos'); ?></h3>

		<div class="form-group">

			<div class='content_viewprop_photos_left col-md-2'></div>

			<div class='content_viewprop_photos_right col-md-10'>

				<?php echo form_open('#',array("class"=>"viewprop_upload_photo_form form-horizontal","role"=>"form", "enctype"=>"multipart/form-data")); ?>

					<div class="form-group">
						<label for="viewprop_photos_carousel" class="col-md-3 control-label"><?php echo lang('p_uploaded_photos'); ?>:</label>
						<div class="viewprop_photos_carousel image_carousel col-md-9">
						</div>
						<div class="col-md-9">
				      <p id='viewprop_photos_text_no_photos' class="viewprop_photos_text_no_photos form-control-static hidden"></p>
				    </div>
					</div>	

					<div class="form-group hidden">
						<div class='viewprop_photo_alert_div col-md-12 pull-right'>
							<label class="col-md-3 control-label"></label>
							<div class="viewprop_photo_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
							<div class="viewprop_photo_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
						</div>
					</div>

				<?php echo form_close(); ?>

			</div>

		</div>

		<h3 class='title_zone'><?php echo lang('p_categories'); ?></h3>

		<div class="form-group">

			<div class='content_viewprop_category_left col-md-2'>
				<img class='img_info' src='<?php echo $this->config->item('base_url') . 'assets/icons/info.png'; ?>'/>
			</div>

			<div class='content_viewprop_category_right col-md-10'>

				<?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('p_categories'); ?>:</label>	
						<div class="viewprop_category_images col-md-9 hidden">
						</div>
						<div class="col-md-9">
				      <p id='viewprop_category_text_no_categories' class="viewprop_category_text_no_categories form-control-static hidden"></p>
				    </div>
					</div>

					<div class="form-group hidden">
						<div class='viewprop_category_alert_div col-md-12 pull-right'>
							<label class="col-md-3 control-label"></label>
							<div class="viewprop_category_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
							<div class="viewprop_category_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
						</div>
					</div>

				<?php echo form_close(); ?>

			</div>

		</div>

	</div>

</div>

<div class='content_viewprop_buttons container'>
	<button type="button" class="btn btn-link viewprop_addfavorite_prop_link viewprop_addfavorite_prop_link_add"><?php echo lang('p_add_favorite'); ?></button>
	<button type="button" class="btn btn-link viewprop_viewuser_prop_link"><?php echo lang('p_view_user'); ?></button>
	<button type="button" class="btn btn-link viewprop_sendmessage_prop_link pull-right"><?php echo lang('p_send_message_to_user'); ?></button>
</div>

<div class="form-group container hidden">
	<div class='viewprop_data_alert_div col-md-12 pull-right'>
		<label class="col-md-3 control-label"></label>
		<div class="viewprop_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
		<div class="viewprop_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
	</div>
</div>