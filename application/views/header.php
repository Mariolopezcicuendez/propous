<!DOCTYPE html>
<html lang="<?php echo DEFAULT_LANGUAGE; ?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?php echo NAME_PROJECT . $post_title_page; ?></title>

  <link rel="icon" type="image/png" href="<?php echo  $this->config->item('file_base_url_relative'); ?>assets/icons/favicon.png" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />

  <meta name="description" content="<?php echo lang('p_meta_description'); ?>" />
  <meta name="keywords" content="<?php echo lang('p_meta_keywords'); ?>" />
  <meta name="Robots" content="index, follow">

  <?php $user_data = $this->session->userdata('logged_in'); ?>

  <!-- CSS -->
  <?php if (in_array($this->uri->segment(2),$this->config->item('pages_with_carousel'))) : ?>
    <?php echo '<link rel="stylesheet" href="' . $this->config->item('file_base_url_relative') . 'js/libraries/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />'; ?>
  <?php endif; ?>

  <?php if (in_array($this->uri->segment(2),$this->config->item('pages_with_datatable'))) : ?>
    <?php echo '<link rel="stylesheet" type="text/css" href="' . $this->config->item('file_base_url_relative') . 'js/libraries/DataTables/media/css/jquery.dataTables.css" />'; ?>
  <?php endif; ?>
  
  <?php echo '<link rel="stylesheet" type="text/css" href="' . $this->config->item('file_base_url_relative') . 'css/bootstrap.css" />'; ?>
  <?php echo '<link rel="stylesheet" type="text/css" href="' . $this->config->item('file_base_url_relative') . 'css/main.css" />'; ?>

  <?php if ((($this->uri->segment(2)) == 'cms') && ($user_data['rol'] !== null)) : ?>
    <?php if (file_exists($this->config->item('file_base_url') . 'js/cms/' . $this->uri->segment(3) . '.js')) : ?>
      <?php echo '<link rel="stylesheet" type="text/css" href="' . $this->config->item('file_base_url_relative') . 'css/cms.css" />'; ?>
    <?php endif; ?>
  <?php endif; ?>
  <?php if ((($this->uri->segment(2)) == 'analytics') && ($user_data['rol'] === 'superadmin')) : ?>
    <?php if (file_exists($this->config->item('file_base_url') . 'js/analytics/' . $this->uri->segment(3) . '.js')) : ?>
      <?php echo '<link rel="stylesheet" type="text/css" href="' . $this->config->item('file_base_url_relative') . 'css/analytics.css" />'; ?>
    <?php endif; ?>
  <?php endif; ?>

</head>
<body>

<script type="text/javascript" src="<?php echo $this->config->item('file_base_url_relative'); ?>js/browser.js"></script>

<?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/libraries/jquery.js"></script>'; ?>
<?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/libraries/bootstrap.js"></script>'; ?>

<?php require '' . $this->config->item('file_base_url') . 'js/constants_js.php'; ?>
<?php require '' . $this->config->item('file_base_url') . 'js/languages_js.php'; ?>

<?php if ($this->uri->segment(2) !== "invalidnavigator") : ?>
  <?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/main.js"></script>'; ?>
<?php endif; ?>

<?php if ($user_data['rol'] !== null) : ?>
  <?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/rol.js"></script>'; ?>
<?php endif; ?>  

<?php if (in_array($this->uri->segment(2),$this->config->item('pages_with_file_input'))) : ?>
  <?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/libraries/bootstrap-filestyle.js"></script>'; ?>
<?php endif; ?>

<?php if (in_array($this->uri->segment(2),$this->config->item('pages_with_carousel'))) : ?>
  <?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/libraries/carouFredSel/jquery.carouFredSel-6.2.1.js"></script>'; ?>
  <?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/libraries/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>'; ?>
<?php endif; ?> 

<?php if (in_array($this->uri->segment(2),$this->config->item('pages_with_datatable'))) : ?>
  <?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/libraries/DataTables/media/js/jquery.dataTables.js"></script>'; ?>
<?php endif; ?>   

<?php if (file_exists($this->config->item('file_base_url') . 'js/' . $this->uri->segment(2) . '.js')) : ?>
  <?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/' . $this->uri->segment(2) . '.js"></script>'; ?>
<?php endif; ?>

<?php if ((($this->uri->segment(2)) == 'cms') && ($user_data['rol'] !== null)) : ?>
  <?php if (file_exists($this->config->item('file_base_url') . 'js/cms/' . $this->uri->segment(3) . '.js')) : ?>
    <?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/cms/' . $this->uri->segment(3) . '.js"></script>'; ?>
  <?php endif; ?>
<?php endif; ?>
<?php if ((($this->uri->segment(2)) == 'analytics') && ($user_data['rol'] === 'superadmin')) : ?>
  <?php if (file_exists($this->config->item('file_base_url') . 'js/analytics/' . $this->uri->segment(3) . '.js')) : ?>
    <?php echo '<script type="text/javascript" src="' . $this->config->item('file_base_url_relative') . 'js/analytics/' . $this->uri->segment(3) . '.js"></script>'; ?>
  <?php endif; ?>
<?php endif; ?>

<div class='div_header container'>

  <div class='div_header_left col-lg-3'>

    <?php echo anchor('home', NAME_PROJECT, array('class' => 'main_logo_link')); ?>

  </div>
  
  <input type='hidden' class='gdata_user_id' value='<?php echo $user_data['id']; ?>'/> 
  <input type='hidden' class='gdata_country_id' value='<?php echo $user_data['country_id']; ?>'/>
  <input type='hidden' class='gdata_state_id' value='<?php echo $user_data['state_id']; ?>'/>
  <input type='hidden' class='actual_lang_tag' value='<?php echo getActLang(); ?>'/>
  <input type='hidden' class='v_prop_id' value='<?php if (isset($prop_id)) echo $prop_id; ?>'/>
  <input type='hidden' class='v_profile_id' value='<?php if (isset($profile_id)) echo $profile_id; ?>'/>

  <div class='div_header_center col-lg-6'> 

    <?php if ($this->session->userdata('logged_in')) : ?>

      <div class='header_main_buttons btn-group btn-group-justified'> 

        <a class='btn btn-default header_main_buttons_button_single' id='header_button_props'><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/props.png'/> <?php echo lang('p_props'); ?></a>
        <a class='btn btn-default header_main_buttons_button_single' id='header_button_messages'><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/messages.png'/> <?php echo lang('p_messages'); ?> <span class='badge messages_main_link_page_text_messages_noreaden hidden'></span></a>
        <div class="btn-group header_main_buttons_button_group">
            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/more.png'/> <?php echo lang('p_more'); ?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a id='header_button_profile'><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/profile.png'/> <?php echo lang('p_profile'); ?></a></li>
              <li><a id='header_button_myprops'><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/myprops.png'/> <?php echo lang('p_my_props'); ?> <span class='menu_myprops_button_count badge pull-right'></span></a></li>
              <li><a id='header_button_favorites'><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/favorite_on.png'/> <?php echo lang('p_favorites'); ?> <span class='menu_favorites_button_count badge pull-right'></span></a></li>
              <li><a id='header_button_premium'><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/premium.png'/> <?php echo lang('p_premium'); ?></a></li>
              <?php if ($user_data['rol'] !== null) : ?>
                <li><a href='<?php echo $this->config->item('base_url'); ?><?php echo getActLang(); ?>/cms/main' id='header_button_cms'><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/cms.png'/> CMS</a></li>
                <?php if ($user_data['rol'] === 'superadmin') : ?>
                  <li><a href='<?php echo $this->config->item('base_url'); ?><?php echo getActLang(); ?>/analytics/main' id='header_button_analytics'><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/analitycs.png'/> Analitycs</a></li>
                <?php endif; ?>
              <?php endif; ?>
            </ul>

        </div>

      </div>

    <?php endif; ?>

  </div>

  <div class='div_header_right col-lg-3 pull-right'>

    <?php if (!$this->session->userdata('logged_in')) : ?>

      <div class='div_header_right_content pull-right'>
        <select title='<?php echo lang('p_change_language_here'); ?>' class='home_select_language form-control'>
          <option <?php if (getActLang() === 'en') { echo 'selected'; } ?> value='en'><?php echo lang('lang_english'); ?></option>
          <option <?php if (getActLang() === 'es') { echo 'selected'; } ?> value='es'><?php echo lang('lang_spanish'); ?></option>
        </select>
      </div>

    <?php endif; ?>

    <?php if ($this->session->userdata('logged_in')) : ?>
      <div class='div_header_right_content pull-right'>
        <?php 
        $user_data = $this->session->userdata('logged_in'); 
        $user_premium = ($user_data['premium'] !== null) ? $user_data['premium'] : lang('p_without_premium') ;
        ?>
        <a class='link' title='<?php echo lang('p_user_premium_you_are'); ?>' href='<?php echo $this->config->item('base_url'); ?><?php echo getActLang(); ?>/premium' id='header_button_premium'><img class='image_icon' src='<?php echo $this->config->item('file_base_url_relative'); ?>assets/icons/actions/premium.png'/> <?php echo lang('p_user') . " " . $user_premium; ?></a>
        <br/>
        <a class='logout_link'><?php echo lang('p_close_session'); ?></a>
      </div>
    <?php endif; ?>

  </div>

</div>

<?php if ($this->session->userdata('logged_in')) : ?>

  <div class='content_div_notification_to_user container col-md-12 content <?php if (!isset($notifies) || ($notifies === null) || (count($notifies) === 0)) : ?>hidden<?php endif; ?>'>
    <?php 
    if (isset($notifies) && ($notifies !== null))
    {
      foreach ($notifies as $key => $notify) 
      {
        echo "<div class='div_notify_text' id='div_notify_text_".$notify->id."'>
        <li id='notify_text_".$notify->id."' class='notify_text'><strong>".lang('p_admin_notification').":</strong> ".$notify->notification."</li>
        <button id='notify_button_".$notify->id."' class='notify_button_accept btn btn-default'>".lang('p_understand_will_not_do')."</button>
        </div>";        
      }
    }
    ?>
  </div>

<?php endif; ?>

<div class='content_page container'>