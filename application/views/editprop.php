<div class='content_page_left content'>

	<div class='content_editprop_data content panel panel-default'>

		<div class='content_editprop_title col-md-12 content panel-heading'>
			<?php echo lang('p_prop'); ?><br/>
		</div>

		<div class='content_editprop_body col-md-12 content panel-body'>

			<h3 class='title_zone'><?php echo lang('p_data'); ?></h3>

			<div class="form-group">

				<div class='content_editprop_data_left col-md-2'></div>

				<div class='content_editprop_data_right col-md-10'>

					<?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

						<div class="form-group">
							<label for="editprop_prop_title" class="col-md-3 control-label"><?php echo lang('p_proposal_title'); ?> (<?php echo lang('p_proposal_title_max_200'); ?>):</label>	
							<div class="col-md-9">
								<input type='text' id='editprop_prop_title' class='editprop_prop_title form-control'/> 
							</div>
							<div class='error_validation_editprop_prop_title hidden'>
								<label for="editprop_prop_title_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprop_prop_title_validation_error' class="span_validation_label editprop_prop_title_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
					    <label for="editprop_prop_creationdate" class="col-md-3 control-label"><?php echo lang('p_proposal_creationdate'); ?>:</label>
					    <div class="col-md-9">
					      <p id='editprop_prop_creationdate' class="editprop_prop_creationdate form-control-static"></p>
					    </div>
					  </div>

					  <div class="form-group hidden">
							<label class="col-md-3 control-label"><?php echo lang('p_proposal_visible'); ?>:</label>	
							<div class="col-md-9 onoffswitch editprop_visibility_onoffswitch">
						    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
						    <label class="onoffswitch-label" for="myonoffswitch">
					        <div class="onoffswitch-inner"></div>
					        <div class="onoffswitch-switch"></div>
						    </label>
							</div>
						</div>

						<div class="form-group">
							<label for="editprop_prop_description" class="col-md-3 control-label"><?php echo lang('p_proposal_description'); ?>:</label>	
							<div class="col-md-9">
								<textarea rows="4" id='editprop_prop_description' class='editprop_prop_description form-control'></textarea>
							</div>
							<div class='error_validation_editprop_prop_description hidden'>
								<label for="editprop_prop_description_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprop_prop_description_validation_error' class="span_validation_label editprop_prop_description_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprop_select_country" class="col-md-3 control-label"><?php echo lang('p_localization'); ?>:</label>	
							<div class="col-md-9 input-group">
								<select class='editprop_select_country input_inline_2_elements_full form-control'>
								  <option value=''><?php echo lang('p_country_into_select'); ?></option>
								</select>
								<select class='editprop_select_state input_inline_2_elements_full form-control'>
								  <option value=''><?php echo lang('p_state_into_select'); ?></option>
								</select> 
							</div>
							<div class='error_validation_editprop_select_region hidden'>
								<label for="editprop_select_region_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9 editprop_select_region">
						      <p id='editprop_select_region_validation_error' class="span_validation_label editprop_select_region_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
					    <label for="editprop_prop_visited" class="col-md-3 control-label"><?php echo lang('p_visited'); ?>:</label>
					    <div class="col-md-9">
					      <p id='editprop_prop_visited' class="editprop_prop_visited form-control-static"></p>
					    </div>
					  </div>

					<?php echo form_close(); ?>

				</div>

			</div>

		<h3 class='title_zone'><?php echo lang('p_photos'); ?></h3>

		<div class="form-group">

			<div class='content_editprop_photos_left col-md-2'></div>

			<div class='content_editprop_photos_right col-md-10'>

				<?php echo form_open('#',array("class"=>"editprop_upload_photo_form form-horizontal","role"=>"form", "enctype"=>"multipart/form-data")); ?>

					<div class="form-group">
						<label for="editprop_photos_carousel" class="col-md-3 control-label"><?php echo lang('p_uploaded_photos'); ?>:</label>
						<div class="editprop_photos_carousel image_carousel col-md-9">
						</div>
						<div class="col-md-9">
				      <p id='editprop_photos_text_no_photos' class="editprop_photos_text_no_photos form-control-static hidden"></p>
				    </div>
					</div>	

					<div class="form-group">
						<label for="editprop_upload_photo_inputfile" class="col-md-3 control-label"><?php echo lang('p_upload_photo'); ?>:</label>
						<div class="col-md-9 input-group">
							<input type="file" data-buttonText="<?php echo lang('p_browse'); ?>" name="upload" data-classButton="btn btn-primary" data-input="false" class='filestyle'/>
							<span class="input-group-btn">
								<a type="button" class='editprop_upload_photo_button btn btn-default form-control' value="upload"><?php echo lang('p_add'); ?></a>
							</span>
						</div>
					</div>

					<div class="form-group hidden">
						<div class='editprop_photo_alert_div col-md-12 pull-right'>
							<label class="col-md-3 control-label"></label>
							<div class="editprop_photo_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
							<div class="editprop_photo_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
						</div>
					</div>

				<?php echo form_close(); ?>

			</div>

		</div>
		
		<h3 class='title_zone'><?php echo lang('p_categories'); ?></h3>

		<div class="form-group">

			<div class='content_editprop_category_left col-md-2'>
				<img class='img_info' src='<?php echo $this->config->item('base_url') . 'assets/icons/info.png'; ?>'/>
			</div>

			<div class='content_editprop_category_right col-md-10'>

				<?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('p_categories'); ?>:</label>	
						<div class="editprop_category_images col-md-9 hidden">
						</div>
						<div class="col-md-9">
				      <p id='editprop_category_text_no_categories' class="editprop_category_text_no_categories form-control-static hidden"></p>
				    </div>
					</div>

	    		<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('p_category_select'); ?>:</label>	
						<div class="col-md-9 input-group">
							<select class='editprop_category_select form-control' disabled>
							  <option value=''><?php echo lang('p_category_into_select'); ?></option>
							</select>
							<span class="input-group-btn">
								<button type="button" class='editprop_category_select_add_button btn btn-default' disabled><?php echo lang('p_add'); ?></button>
							</span>
						</div>
					</div>	

					<div class="form-group hidden">
						<div class='editprop_category_alert_div col-md-12 pull-right'>
							<label class="col-md-3 control-label"></label>
							<div class="editprop_category_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
							<div class="editprop_category_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
						</div>
					</div>

				<?php echo form_close(); ?>

			</div>

		</div>

	</div>

</div>

<div class='content_editprop_buttons container'>
	<button type="button" class="btn btn-link editprop_delete_prop_link"><?php echo lang('p_delete_prop'); ?></button>
	<button type="button" class="btn btn-link editprop_save_prop_link pull-right"><?php echo lang('p_save_prop'); ?></button>
</div>

<div class="form-group container hidden">
	<div class='editprop_data_alert_div col-md-12 pull-right'>
		<label class="col-md-3 control-label"></label>
		<div class="editprop_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
		<div class="editprop_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
	</div>
</div>