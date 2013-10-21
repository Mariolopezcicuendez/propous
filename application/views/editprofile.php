<div class='content_page_left content'>
	
	<div class='content_editprofile_data content panel panel-default'>
		
		<div class='content_editprofile_title col-md-12 content panel-heading'>
			<?php echo lang('p_profile'); ?><br/>
		</div>

		<div class='content_editprofile_body col-md-12 content panel-body'>

			<h3 class='title_zone'><?php echo lang('p_personal_data'); ?></h3>

			<div class="form-group">

				<div class='content_editprofile_data_left col-md-2'>
					<img style='width:<?php echo MAIN_PHOTO_WIDTH; ?>px; height:<?php echo MAIN_PHOTO_HEIGHT; ?>px;' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/photos/no_photo.png' class='content_editprofile_main_photo img-rounded'></img>
				</div>

				<div class='content_editprofile_data_right hidden col-md-10'>

					<?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

						<div class="form-group">
							<label for="editprofile_user_name" class="col-md-3 control-label"><?php echo lang('p_username'); ?>:</label>	
							<div class="col-md-9">
								<input type='text' id='editprofile_user_name' class='editprofile_user_name form-control'/> 
							</div>
							<div class='error_validation_editprofile_user_name hidden'>
								<label for="editprofile_user_name_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_user_name_validation_error' class="span_validation_label editprofile_user_name_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>
						
					  <div class="form-group">
							<label for="editprofile_select_day" class="col-md-3 control-label"><?php echo lang('p_birthdate'); ?>:</label>	
							<div class="col-md-9 input-group">
								<select class='editprofile_select_day input_inline_3_elements_full form-control'>
								  <option value=''><?php echo lang('p_day_into_select'); ?></option>
								  <?php for ($i=1; $i <= 31; $i++) : ?>
								    <?php $i_mod = "".$i; if (strlen($i_mod) === 1) $i_mod = "0".$i_mod; ?>
								    <option value='<?php echo $i_mod; ?>'><?php echo $i_mod; ?></option>
								  <?php endfor; ?>
								</select>
								<select class='editprofile_select_month input_inline_3_elements_full form-control'>
								  <option value=''><?php echo lang('p_month_into_select'); ?></option>
								  <?php for ($i=1; $i <= 12; $i++) : ?>
								    <?php $i_mod = "".$i; if (strlen($i_mod) === 1) $i_mod = "0".$i_mod; ?>
								    <option value='<?php echo $i_mod; ?>'><?php echo lang('cal_month_'.$i_mod); ?></option>
								  <?php endfor; ?>
								</select> 
								<select class='editprofile_select_year input_inline_3_elements_full form-control'>
								    <option value=''><?php echo lang('p_year_into_select'); ?></option>
								  <?php for ($i=1900; $i <= date("Y"); $i++) : ?>
								    <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
								  <?php endfor; ?>
								</select> 
							</div>
							<div class='error_validation_editprofile_select_year hidden'>
								<label for="editprofile_select_year_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_select_year_validation_error' class="span_validation_label editprofile_select_year_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
					    <label for="editprofile_user_age" class="col-md-3 control-label"><?php echo lang('p_age'); ?>:</label>
					    <div class="col-md-9">
					      <p id='editprofile_age_data' class="editprofile_age_data form-control-static"></p>
					    </div>
					  </div>
						
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('p_gender'); ?>:</label>	
							<div class="col-md-9">
								<label class="radio-inline">
							    <input type="radio" class='editprofile_radio_gender' name="editprofile_radio_gender" id="editprofile_radio_gender_M" value="M" checked>
							    <?php echo lang('p_male'); ?>
							  </label>
							  <label class="radio-inline">
							    <input type="radio" class='editprofile_radio_gender' name="editprofile_radio_gender" id="editprofile_radio_gender_F" value="F">
							    <?php echo lang('p_female'); ?>
							  </label>
							</div>
							<div class='error_validation_editprofile_radio_gender hidden'>
								<label for="editprofile_radio_gender_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_radio_gender_validation_error' class="span_validation_label editprofile_radio_gender_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_select_country" class="col-md-3 control-label"><?php echo lang('p_localization'); ?>:</label>	
							<div class="col-md-9 input-group">
								<select class='editprofile_select_country input_inline_2_elements_full form-control'>
								  <option value=''><?php echo lang('p_country_into_select'); ?></option>
								</select>
								<select class='editprofile_select_state input_inline_2_elements_full form-control'>
								  <option value=''><?php echo lang('p_state_into_select'); ?></option>
								</select> 
							</div>
							<div class='error_validation_editprofile_select_region hidden'>
								<label for="editprofile_select_region_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9 editprofile_select_region">
						      <p id='editprofile_select_region_validation_error' class="span_validation_label editprofile_select_region_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_nationality" class="col-md-3 control-label"><?php echo lang('p_nationality'); ?>:</label>	
							<div class="col-md-9">
								<input type='text' id='editprofile_nationality' class='editprofile_nationality form-control'/> 
							</div>
							<div class='error_validation_editprofile_nationality hidden'>
								<label for="editprofile_nationality_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_nationality_validation_error' class="span_validation_label editprofile_nationality_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_dwelling" class="col-md-3 control-label"><?php echo lang('p_dwelling'); ?>:</label>	
							<div class="col-md-9">
								<input type='text' id='editprofile_dwelling' class='editprofile_dwelling form-control'/> 
							</div>
							<div class='error_validation_editprofile_dwelling hidden'>
								<label for="editprofile_dwelling_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_dwelling_validation_error' class="span_validation_label editprofile_dwelling_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_car" class="col-md-3 control-label"><?php echo lang('p_car'); ?>:</label>	
							<div class="col-md-9">
								<input type='text' id='editprofile_car' class='editprofile_car form-control'/> 
							</div>
							<div class='error_validation_editprofile_car hidden'>
								<label for="editprofile_car_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_car_validation_error' class="span_validation_label editprofile_car_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_sexuality" class="col-md-3 control-label"><?php echo lang('p_sexuality'); ?>:</label>	
							<div class="col-md-9">
								<input type='text' id='editprofile_sexuality' class='editprofile_sexuality form-control'/> 
							</div>
							<div class='error_validation_editprofile_sexuality hidden'>
								<label for="editprofile_sexuality_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_sexuality_validation_error' class="span_validation_label editprofile_sexuality_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_partner" class="col-md-3 control-label"><?php echo lang('p_partner'); ?>:</label>	
							<div class="col-md-9">
								<input type='text' id='editprofile_partner' class='editprofile_partner form-control'/> 
							</div>
							<div class='error_validation_editprofile_partner hidden'>
								<label for="editprofile_partner_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_partner_validation_error' class="span_validation_label editprofile_partner_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_children" class="col-md-3 control-label"><?php echo lang('p_children'); ?>:</label>	
							<div class="col-md-9">
								<input type='text' id='editprofile_children' class='editprofile_children form-control'/> 
							</div>
							<div class='error_validation_editprofile_children hidden'>
								<label for="editprofile_children_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_children_validation_error' class="span_validation_label editprofile_children_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_occupation" class="col-md-3 control-label"><?php echo lang('p_occupation'); ?>:</label>	
							<div class="col-md-9">
								<input type='text' id='editprofile_occupation' class='editprofile_occupation form-control'/> 
							</div>
							<div class='error_validation_editprofile_occupation hidden'>
								<label for="editprofile_occupation_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_occupation_validation_error' class="span_validation_label editprofile_occupation_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_phone" class="col-md-3 control-label"><?php echo lang('p_phone'); ?>:</label>	
							<div class="col-md-9">
								<input type='text' id='editprofile_phone' class='editprofile_phone form-control'/> 
							</div>
							<div class='error_validation_editprofile_phone hidden'>
								<label for="editprofile_phone_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_phone_validation_error' class="span_validation_label editprofile_phone_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_hobbies" class="col-md-3 control-label"><?php echo lang('p_hobbies'); ?>:</label>	
							<div class="col-md-9">
								<textarea rows="4" id='editprofile_hobbies' class='editprofile_hobbies form-control'></textarea>
							</div>
							<div class='error_validation_editprofile_hobbies hidden'>
								<label for="editprofile_hobbies_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_hobbies_validation_error' class="span_validation_label editprofile_hobbies_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_description" class="col-md-3 control-label"><?php echo lang('p_description'); ?>:</label>	
							<div class="col-md-9">
								<textarea rows="4" id='editprofile_description' class='editprofile_description form-control'></textarea>
							</div>
							<div class='error_validation_editprofile_description hidden'>
								<label for="editprofile_description_validation_error" class="col-md-3 control-label"></label>
								<div class="col-md-9">
						      <p id='editprofile_description_validation_error' class="span_validation_label editprofile_description_validation_error form-control-static"></p>
						    </div>
					    </div>
						</div>

						<div class="form-group">
							<label for="editprofile_select_spoken" class="col-md-3 control-label"><?php echo lang('p_spoken_languages'); ?>:</label>	
							<div class="col-md-9">
								<select class='editprofile_select_spoken form-control' size='5'>
								</select>
								<button type="button" class='editprofile_link_delete_language_selected btn btn-default btn-block' disabled><?php echo lang('p_delete_language_selected'); ?></button>
							</div>
						</div>

						<div class="form-group">
							<label for="editprofile_select_spoken_add" class="col-md-3 control-label"><?php echo lang('p_add'); ?> <?php echo lang('p_language'); ?>:</label>
							<div class="col-md-9 input-group">
								<select class='editprofile_select_spoken_add input_inline_2_elements_full form-control'>
									<option value=''><?php echo lang('p_language'); ?></option>
								</select>
								<select class='editprofile_select_spoken_level input_inline_2_elements_full form-control'>
									<option value='low'><?php echo lang('p_level'); ?> <?php echo lang('p_low'); ?></option>
									<option value='medium'><?php echo lang('p_level'); ?> <?php echo lang('p_medium'); ?></option>
									<option value='high'><?php echo lang('p_level'); ?> <?php echo lang('p_high'); ?></option>
									<option value='native'><?php echo lang('p_level'); ?> <?php echo lang('p_native'); ?></option>
								</select>
								<span class="input-group-btn">
									<button type="button" class='editprofile_link_add_language btn btn-default' disabled><?php echo lang('p_add'); ?></button>
								</span>
							</div>						
						</div>

					<?php echo form_close(); ?>

				</div>

			</div>

		<h3 class='title_zone'><?php echo lang('p_photos'); ?></h3>

		<div class="form-group">

			<div class='content_editprofile_photos_left col-md-2'></div>

			<div class='content_editprofile_photos_right col-md-10'>

				<?php echo form_open('#',array("class"=>"editprofile_upload_photo_form form-horizontal","role"=>"form", "enctype"=>"multipart/form-data")); ?>

					<div class="form-group">
						<label for="editprofile_photos_carousel" class="col-md-3 control-label"><?php echo lang('p_uploaded_photos'); ?>:</label>
						<div class="editprofile_photos_carousel image_carousel col-md-9">
						</div>
						<div class="col-md-9">
				      <p id='editprofile_photos_text_no_photos' class="editprofile_photos_text_no_photos form-control-static hidden"></p>
				    </div>
					</div>	

					<div class="form-group">
						<label for="editprofile_upload_photo_inputfile" class="col-md-3 control-label"><?php echo lang('p_upload_photo'); ?>:</label>
						<div class="col-md-9 input-group">
							<input type="file" data-buttonText="<?php echo lang('p_browse'); ?>" name="upload" data-classButton="btn btn-primary" data-input="false" class='filestyle'/>
							<span class="input-group-btn">
								<a type="button" class='editprofile_upload_photo_button btn btn-default form-control' value="upload"><?php echo lang('p_add'); ?></a>
							</span>
						</div>
					</div>

					<div class="form-group hidden">
						<div class='editprofile_photo_alert_div col-md-12 pull-right'>
							<label class="col-md-3 control-label"></label>
							<div class="editprofile_photo_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
							<div class="editprofile_photo_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
						</div>
					</div>

				<?php echo form_close(); ?>

			</div>

		</div>

		<h3 class='title_zone'><?php echo lang('p_sociality'); ?></h3>

		<div class="form-group">

			<div class='content_editprofile_sociality_left col-md-2'>
				<img class='img_info' src='<?php echo $this->config->item('base_url') . 'assets/icons/info.png'; ?>'/>
			</div>

			<div class='content_editprofile_sociality_right hidden col-md-10'>

				<?php echo form_open('#',array("class"=>"form-horizontal","role"=>"form")); ?>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('p_user_sociality'); ?>:</label>	
						<div class="editprofile_sociality_images col-md-9 hidden">
						</div>
						<div class="col-md-9">
				      <p id='editprofile_sociality_text_no_socialities' class="editprofile_sociality_text_no_socialities form-control-static hidden"></p>
				    </div>
					</div>

	    		<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('p_sociality_select'); ?>:</label>	
						<div class="col-md-9 input-group">
							<select class='editprofile_sociality_select form-control' disabled>
							  <option value=''><?php echo lang('p_sociality_into_select'); ?></option>
							</select>
							<span class="input-group-btn">
								<button type="button" class='editprofile_sociality_select_add_button btn btn-default' disabled><?php echo lang('p_add'); ?></button>
							</span>
						</div>
					</div>	

					<div class="form-group hidden">
						<div class='editprofile_sociality_alert_div col-md-12 pull-right'>
							<label class="col-md-3 control-label"></label>
							<div class="editprofile_sociality_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
							<div class="editprofile_sociality_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
						</div>
					</div>

				<?php echo form_close(); ?>

			</div>

		</div>

	</div>

</div>

<div class='content_editprofile_buttons container'>
	<button type="button" class="btn btn-link editprofile_change_password_link"><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/change.png'/> <?php echo lang('p_change_password'); ?></button>
	<button type="button" class="btn btn-link editprofile_delete_account_link"><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/delete.png'/> <?php echo lang('p_delete_account'); ?></button>
	<button type="button" class="btn btn-link editprofile_save_profile_link pull-right"><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/save.png'/> <?php echo lang('p_save'); ?></button>
</div>

<div class="form-group container hidden">
	<div class='editprofile_data_alert_div col-md-12 pull-right'>
		<label class="col-md-3 control-label"></label>
		<div class="editprofile_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
		<div class="editprofile_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
	</div>
</div>