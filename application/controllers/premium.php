<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Premium extends CI_Controller 
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
    $this->load->view('premium');
    $this->load->view('footer');
	}
}

/* End of file premium.php */
/* Location: ./application/controllers/premium.php */