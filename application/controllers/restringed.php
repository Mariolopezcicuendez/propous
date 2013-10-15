<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restringed extends CI_Controller 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->load->view('header');
    $this->load->view('restringed');
    $this->load->view('footer');
  }
}

/* End of file restringed.php */
/* Location: ./application/controllers/restringed.php */