<div class='content_page_left content'>

	<?php $user_data = $this->session->userdata('logged_in'); ?>

	<input type='hidden' id='cms_action' value='list'>
	<input type='hidden' id='user_id' value='<?php echo $user_data['id']; ?>'>

   <div class="div_content_info form-group page-cms">
      
      <h3 class='title_zone'>Usuarios</h3>

      <div class='content link_back'>
      	<?php echo anchor('/' . getActLang() . '/cms/main', "<< Atrás"); ?>
    	</div>

      <div class='cms_main_table_div col-md-12 content'>
        
	      <div>
	        <table id="cms_main_table" class="cms_main_table">
	          <thead>
	            <tr>
	              <th>id</th>
	              <th>name</th>
	              <th>email</th>
	              <th>password</th>
	              <th>birthdate</th>
	              <th>registerdate</th>
	              <th>sex</th>
	              <th>country_id</th>
	              <th>state_id</th>
	              <th>nationality</th>
	              <th>description</th>
	              <th>dwelling</th>
	              <th>car</th>
	              <th>sexuality</th>
	              <th>children</th>
	              <th>partner</th>
	              <th>hobbies</th>
	              <th>occupation</th>
	              <th>phone</th>
	              <th>activated</th>
	              <th>change_password_token</th>
	              <th>activate_account_token</th>
	              <th>login_cookie_tag</th>
	              <th>rol</th> 
                <th>Photos</th>
	            </tr>
	          </thead>
	          <tbody>
	          </tbody>
	        </table>
	      </div>

	    </div>  

	    <?php	if (($user_data['rol'] === 'admin') || ($user_data['rol'] === 'superadmin')) : ?>
      <div class='cms_options'>
      	<button class='form-control' id='cms_new'>NUEVO</button>
      </div>	 
      <?php endif; ?>   

	    <div class="form-group container hidden">
        <div class='cms_data_alert_div col-md-12 pull-right'>
          <label class="col-md-3 control-label"></label>
          <div class="cms_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          <div class="cms_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
        </div>
      </div>

    </div>

</div>