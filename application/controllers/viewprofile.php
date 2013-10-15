<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Viewprofile extends CI_Controller 
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
    $this->load->view('viewprofile');
    $this->load->view('footer');
	}
}

/* End of file viewprofile.php */
/* Location: ./application/controllers/viewprofile.php */