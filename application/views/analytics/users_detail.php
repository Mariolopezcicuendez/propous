<div class='content_page_left content'>

  <input type='hidden' id='analitycs_action' value='detail'>

   <div class="div_content_info form-group page-analitycs">

      <h3 class='title_zone'></h3>

      <?php echo form_open('#',array("class"=>"form-horizontal hidden","role"=>"form")); ?>
    <?php echo form_close(); ?>
    
      <div class='content link_back'>
        <?php echo anchor('/' . getActLang() . '/analytics/'.$back, "<< Atrás"); ?>
      </div>

      <div class='analitycs_result_div hidden'>

        <div class='analitycs_div_content_table'>

          <table id="analitycs_table_detail" class="table table-striped analitycs_table_detail">
            <thead>
              <tr>
                <th>id</th>
                <th>User</th>
                <th>Registerdate</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

        </div>

      </div>

      <div class="form-group container hidden">
        <div class='analitycs_data_alert_div col-md-12 pull-right'>
          <label class="col-md-3 control-label"></label>
          <div class="analitycs_data_alert_success alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
          <div class="analitycs_data_alert_fail alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a></div>
        </div>
      </div>

    </div>

</div> 