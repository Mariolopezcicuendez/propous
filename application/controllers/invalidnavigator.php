<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InvalidNavigator extends CI_Controller 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->load->view('header');
    $this->load->view('invalidnavigator');
    $this->load->view('footer');
  }
}

/* End of file invalidnavigator.php */
/* Location: ./application/controllers/invalidnavigator.php */