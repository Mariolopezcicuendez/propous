<div class='content_page_left content'>

  <?php $user_data = $this->session->userdata('logged_in'); ?>
  
  <input type='hidden' id='cms_action' value='list'>

   <div class="div_content_info form-group page-cms">
      
      <h3 class='title_zone'>Mensajes</h3>

      <div class='content link_back'>
        <?php echo anchor('/' . getActLang() . '/cms/main', "<< Atrás"); ?>
      </div>

      <div class='cms_main_table_div col-md-12 content'>

        <div>
          <table id="cms_main_table" class="cms_main_table hidden">
            <thead>
              <tr>
                <th>id</th>
                <th>user_from_id</th>
                <th>user_to_id</th>
                <th>time</th>
                <th>readen</th>
                <th>message</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

      </div>  

      <?php if (($user_data['rol'] === 'admin') || ($user_data['rol'] === 'superadmin')) : ?>
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