<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activateaccount extends CI_Controller 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->load->view('header');
    $this->load->view('activateaccount');
    $this->load->view('footer');
  }
}

/* End of file activateaccount.php */
/* Location: ./application/controllers/activateaccount.php */