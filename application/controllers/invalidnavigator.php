<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InvalidNavigator extends CI_Controller 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $data_header = array();
    $data_header["post_title_page"] = " / " . lang('p_invalid_navigator');

    $this->load->view('header', $data_header);
    $this->load->view('invalidnavigator');
    $this->load->view('footer');
  }
}

/* End of file invalidnavigator.php */
/* Location: ./application/controllers/invalidnavigator.php */