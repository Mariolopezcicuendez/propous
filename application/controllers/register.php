<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller 
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('maintenance_model');
  }

  public function index()
  {
    $data_header = array();
    $data_header["post_title_page"] = " / " . lang('p_register');

    if ($this->maintenance_model->on_maintenance())
    {
      redirect('/' . getActLang() . '/maintenance');
      exit();
    }

    $this->load->view('header', $data_header);
    $this->load->view('register');
    $this->load->view('footer');
  }
}

/* End of file register.php */
/* Location: ./application/controllers/register.php */