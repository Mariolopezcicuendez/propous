<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restringed extends CI_Controller 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $data_header = array();
    $data_header["post_title_page"] = " / " . lang('p_page_restringed');

    $this->load->view('header', $data_header);
    $this->load->view('restringed');
    $this->load->view('footer');
  }
}

/* End of file restringed.php */
/* Location: ./application/controllers/restringed.php */