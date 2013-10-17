<div class='content_page_left content'>

	<div class='content_newprop_data content panel panel-default'>

		<div class='content_newprop_title col-md-12 content panel-heading'>
			<?php echo lang('p_prop'); ?><br/>
		</div>

		<div class='content_newprop_body col-md-12 content panel-body'>

			<h3 class='title_zone'><?php echo lang('p_data'); ?></h3>

			<div class="form-group">

				<div class='content_newprop_data_left col-md-2'></div>

				<div class='content_newprop_data_right col-md-10'>

					<?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

						<div class="form-group">
							<label for="newprop_prop_title" class="col-md-3 control-label"><?php echo lang('p_proposal_title'); ?> (<?php echo lang('p_proposal_title_max_200'); ?>):</label>	
							<div class="col-md-9">
								<input type='text' id='newprop_prop_title' class='newprop_prop_title form-control'/> 
							</div>
							<div class='error_validation_newprop_prop_title hidden'>
								<label for="newprop_prop_title_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='newprop_prop_title_validation_error' class="span_validation_label newprop_prop_title_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

					  <div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('p_proposal_visible'); ?>:</label>	
							<div class="col-md-9 onoffswitch newprop_visibility_onoffswitch">
						    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
						    <label class="onoffswitch-label" for="myonoffswitch">
					        <div class="onoffswitch-inner"></div>
					        <div class="onoffswitch-switch"></div>
						    </label>
							</div>
						</div>

						<div class="form-group">
							<label for="newprop_prop_description" class="col-md-3 control-label"><?php echo lang('p_proposal_description'); ?>:</label>	
							<div class="col-md-9">
								<textarea rows="4" id='newprop_prop_description' class='newprop_prop_description form-control'></textarea>
							</div>
							<div class='error_validation_newprop_prop_description hidden'>
								<label for="newprop_prop_description_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='newprop_prop_description_validation_error' class="span_validation_label newprop_prop_description_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="newprop_select_country" class="col-md-3 control-label"><?php echo lang('p_localization'); ?>:</label>	
							<div class="col-md-9 input-group">
								<select class='newprop_select_country input_inline_2_elements_full form-control'>
								  <option value=''><?php echo lang('p_country_into_select'); ?></option>
								</select>
								<select class='newprop_select_state input_inline_2_elements_full form-control'>
								  <option value=''><?php echo lang('p_state_into_select'); ?></option>
								</select> 
							</div>
							<div class='error_validation_newprop_select_region hidden'>
								<label for="newprop_select_region_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9 newprop_select_region">
						      <p id='newprop_select_region_validation_error' class="span_validation_label newprop_select_region_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

					<?php echo form_close(); ?>

				</div>

			</div>

		<h3 class='title_zone'><?php echo lang('p_photos'); ?></h3>

		<div class="form-group">

			<div class='content_newprop_photos_left col-md-2'></div>

			<div class='content_newprop_photos_right col-md-10'>

				<?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

					<div class="form-group">
						<div class="col-md-9">
				      <p id='newprop_photos_text_no_photos' class="newprop_photos_text_no_photos form-control-static"><?php echo lang('p_photos_in_edit_proposal'); ?></p>
				    </div>
					</div>	

				<?php echo form_close(); ?>

			</div>

		</div>
		
		<h3 class='title_zone'><?php echo lang('p_categories'); ?></h3>

		<div class="form-group">

			<div class='content_newprop_category_left col-md-2'>
				<img class='img_info' src='<?php echo $this->config->item('base_url') . 'assets/icons/info.png'; ?>'/>
			</div>

			<div class='content_newprop_category_right col-md-10'>

				<?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

					<div class="form-group">
						<div class="col-md-9">
				      <p id='newprop_category_text_no_categories' class="newprop_category_text_no_categories form-control-static"><?php echo lang('p_categories_in_edit_proposal'); ?></p>
				    </div>
					</div>

				<?php echo form_close(); ?>

			</div>

		</div>

	</div>

</div>

<div class='content_newprop_buttons container'>
	<button type="button" class="btn btn-link newprop_save_prop_link pull-right"><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/newprop.png'/> <?php echo lang('p_create_prop'); ?></button>
</div>

<div class="form-group container hidden">
	<div class='newprop_data_alert_div col-md-12 pull-right'>
		<label class="col-md-3 control-label"></label>
		<div class="newprop_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
		<div class="newprop_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
	</div>
</div>