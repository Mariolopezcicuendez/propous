</div>

<div class="modal fade" id="modal_loading" tabindex="-1" role="dialog" aria-labelledby="modal_loading_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal_loading_div modal-body">
        <img class='modal_loading_image' src='<?php echo $this->config->item('base_url') . 'assets/icons/ajax-loader.gif'; ?>'/>
        <?php echo lang('p_modal_loading_wait'); ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_popup" tabindex="-1" role="dialog" aria-labelledby="modal_popup_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header hidden">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id='modal_popup_title'></h4>
      </div>
      <div class="modal-body" id='modal_popup_body'></div>
      <div class="modal-footer">
        <button type="button" id='modal_popup_button_accept' class="btn btn-default" data-dismiss="modal"></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="modal_confirm_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header hidden">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id='modal_confirm_title'></h4>
      </div>
      <div class="modal-body" id='modal_confirm_body'></div>
      <div class="modal-footer">
        <button type="button" id='modal_confirm_button_cancel' class="btn btn-default" data-dismiss="modal"></button>
        <button type="button" id='modal_confirm_button_ok' class="btn btn-default hidden"></button>
      </div>
    </div>
  </div>
</div>

<div class='div_footer container'>

	<?php if ($this->uri->segment(2) !== "invalidnavigator") : ?>

		<div class='div_statistics container'>

			<div class='page_total_statistics'>
				<span class='page_total_props'></span> <?php echo lang('p_total_props'); ?>, <span class='page_total_users'></span> <?php echo lang('p_total_users'); ?>.
			</div> 

			<?php if ($this->session->userdata('logged_in')) : ?>
				<?php $user_data = $this->session->userdata('logged_in'); ?>
				<div class='page_total_statistics_state'>
					<?php echo lang('p_into'); ?> <?php echo $user_data['state_name']; ?>: <span class='page_total_props_state'></span> <?php echo lang('p_total_props'); ?> (<span class='page_total_props_state_today'></span> <?php echo lang('p_today'); ?>), <span class='page_total_users_state'></span> <?php echo lang('p_users'); ?> (<span class='page_total_users_state_online'></span> <?php echo lang('p_online'); ?>).
				</div>
			<?php endif; ?>

		</div>

	<?php endif; ?>

	<div class='div_footer_left_1 col-lg-2'>

	</div>	

	<div class='div_footer_left_2 col-lg-2'>

		<strong><?php echo lang('p_info'); ?></strong>
		<br/>
    <?php echo anchor('about', lang('p_about_prop'), array('id' => 'footer_link_about', 'class' => 'footer_main_link_single')); ?>
		<br/>
    <?php echo anchor('contact', lang('p_contact'), array('id' => 'footer_link_contact', 'class' => 'footer_main_link_single')); ?>
		<br/>
    <?php echo anchor('help', lang('p_help'), array('id' => 'footer_link_help', 'class' => 'footer_main_link_single')); ?>

	</div>

	<div class='div_footer_center col-lg-4'>

		<strong><?php echo lang('p_legal'); ?></strong>
		<br/>
    <?php echo anchor('useconditions', lang('p_use_conditions'), array('id' => 'footer_link_use_conditions', 'class' => 'footer_main_link_single')); ?>
		<br/>
    <?php echo anchor('privacy', lang('p_privacy_polity'), array('id' => 'footer_link_privacy_polity', 'class' => 'footer_main_link_single')); ?>
		<br/>
    <?php echo anchor('privacy/#cookies', lang('p_cookies_polity'), array('id' => 'footer_link_cookies_polity', 'class' => 'footer_main_link_single')); ?>

	</div>
		
	<div class='div_footer_right_2 col-lg-2'> 

		<div class='pull-right'>
			
			<strong><?php echo lang('p_share'); ?></strong>
			<br/>
			Face
			<br/>
			Tweet
			<br/>
			G+

		</div>

	</div>

	<div class='div_footer_right_1 col-lg-2'>

		

	</div>

</div>

</body>
</html>