<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activateaccount extends CI_Controller 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $data_header = array();
    $data_header["post_title_page"] = " / " . lang('p_activate_account');

    $this->load->view('header', $data_header);
    $this->load->view('activateaccount');
    $this->load->view('footer');
  }
}

/* End of file activateaccount.php */
/* Location: ./application/controllers/activateaccount.php */