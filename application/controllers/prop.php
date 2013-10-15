<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prop extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
    $this->load->model('user_model');
	}

	public function index()
	{
    if (!$this->user_model->logged())
    {
      redirect('/' . getActLang() . '/home');
      exit();
    }

		$this->load->view('header');
    $this->load->view('search');
    $this->load->view('prop');
    $this->load->view('footer');
	}

  function logout()
  {
    $this->session->unset_userdata('logged_in');
    session_destroy();
    redirect('/' . getActLang() . 'home', 'refresh');
  }
}

/* End of file prop.php */
/* Location: ./application/controllers/prop.php */