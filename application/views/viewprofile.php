<div class='content_page_left content'>

	<div class='content_viewprofile_data content panel panel-default'>

		<div class='content_viewprofile_title col-md-12 content panel-heading'>
			<?php echo lang('p_profile'); ?><br/>
		</div>

		<div class='content_viewprofile_body col-md-12 content panel-body'>

			<h3 class='title_zone'><?php echo lang('p_personal_data'); ?></h3>

			<div class="form-group">
				
				<div class='content_viewprofile_data_left col-md-2'>
					<img style='width:<?php echo MAIN_PHOTO_WIDTH; ?>px; height:<?php echo MAIN_PHOTO_HEIGHT; ?>px;' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/photos/no_photo.png' class='content_viewprofile_main_photo'></img>
				</div>

				<div class='content_viewprofile_data_right col-md-10'>

					<form class="form-horizontal" role="form">

						<div class="form-group">
							<label for="viewprofile_user_name" class="col-md-3 control-label"><?php echo lang('p_username'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_user_name' class="viewprofile_user_name form-control-static"></p>
							</div>
						</div>

						<div class="form-group">
							<label for="viewprofile_age_data" class="col-md-3 control-label"><?php echo lang('p_age'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_age_data' class="viewprofile_age_data form-control-static"></p>							
							</div>
						</div>

						<div class="form-group">
							<label for="viewprofile_user_gender" class="col-md-3 control-label"><?php echo lang('p_gender'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_user_gender' class="viewprofile_user_gender form-control-static"></p>							
							</div>
						</div>

						<div class="form-group">
							<label for="viewprofile_user_region" class="col-md-3 control-label"><?php echo lang('p_localization'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_user_region' class="viewprofile_user_region form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_nationality" class="col-md-3 control-label"><?php echo lang('p_nationality'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_nationality' class="viewprofile_nationality form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_dwelling" class="col-md-3 control-label"><?php echo lang('p_dwelling'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_dwelling' class="viewprofile_dwelling form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_car" class="col-md-3 control-label"><?php echo lang('p_car'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_car' class="viewprofile_car form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_sexuality" class="col-md-3 control-label"><?php echo lang('p_sexuality'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_sexuality' class="viewprofile_sexuality form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_partner" class="col-md-3 control-label"><?php echo lang('p_partner'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_partner' class="viewprofile_partner form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_children" class="col-md-3 control-label"><?php echo lang('p_children'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_children' class="viewprofile_children form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_occupation" class="col-md-3 control-label"><?php echo lang('p_occupation'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_occupation' class="viewprofile_occupation form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_hobbies" class="col-md-3 control-label"><?php echo lang('p_hobbies'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_hobbies' class="viewprofile_hobbies form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_phone" class="col-md-3 control-label"><?php echo lang('p_phone'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_phone' class="viewprofile_phone form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_description" class="col-md-3 control-label"><?php echo lang('p_description'); ?>:</label>	
							<div class="col-md-9">
								<p id='viewprofile_description' class="viewprofile_description form-control-static"></p>							
							</div>
						</div>

						<div class="form-group hidden">
							<label for="viewprofile_select_spoken" class="col-md-3 control-label"><?php echo lang('p_spoken_languages'); ?>:</label>	
							<div class="viewprofile_spoken col-md-9">
								<select class='viewprofile_select_spoken form-control' size='5'>
								</select>
							</div>
						</div>

					</form>

				</div>	

			</div>

		<h3 class='title_zone'><?php echo lang('p_photos'); ?></h3>

		<div class="form-group">

			<div class='content_viewprofile_photos_left col-md-2'></div>

			<div class='content_viewprofile_photos_right col-md-10'>

				<form class="viewprofile_upload_photo_form form-horizontal" enctype="multipart/form-data" role="form">

					<div class="form-group">
						<label for="viewprofile_photos_carousel" class="col-md-3 control-label"><?php echo lang('p_uploaded_photos'); ?>:</label>
						<div class="viewprofile_photos_carousel image_carousel col-md-9">
						</div>
						<div class="col-md-9">
				      <p id='viewprofile_photos_text_no_photos' class="viewprofile_photos_text_no_photos form-control-static hidden"></p>
				    </div>
					</div>	

					<div class="form-group hidden">
						<div class='viewprofile_photo_alert_div col-md-12 pull-right'>
							<label class="col-md-3 control-label"></label>
							<div class="viewprofile_photo_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
							<div class="viewprofile_photo_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
						</div>
					</div>

				</form>

			</div>

		</div>

		<h3 class='title_zone'><?php echo lang('p_sociality'); ?></h3>

		<div class="form-group">
			<div class='content_viewprofile_sociality_left col-md-2'>
				<img class='img_info' src='<?php echo $this->config->item('base_url') . 'assets/icons/info.png'; ?>'/>
			</div>

			<div class='content_viewprofile_sociality_right col-md-10'>

				<form class="form-horizontal" role="form">

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('p_user_sociality'); ?>:</label>	
						<div class="viewprofile_sociality_images col-md-9 hidden">
						</div>
						<div class="col-md-9">
				      <p id='viewprofile_sociality_text_no_socialities' class="viewprofile_sociality_text_no_socialities form-control-static hidden"></p>
				    </div>
					</div>

					<div class="form-group hidden">
						<div class='viewprofile_sociality_alert_div col-md-12 pull-right'>
							<label class="col-md-3 control-label"></label>
							<div class="viewprofile_sociality_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
							<div class="viewprofile_sociality_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
						</div>
					</div>

				</form>

			</div>

		</div>	

	</div>

</div>

<div class='content_viewprofile_buttons container'>
	<button type="button" class="btn btn-link viewprofile_sendmessage_profile_link pull-right"><?php echo lang('p_send_message_to_user'); ?></button>
</div>

<div class="form-group hidden">
	<div class='viewprofile_data_alert_div col-md-12 pull-right'>
		<label class="col-md-3 control-label"></label>
		<div class="viewprofile_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
		<div class="viewprofile_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
	</div>
</div>