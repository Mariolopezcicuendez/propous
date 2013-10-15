<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Editprop extends CI_Controller 
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
    $this->load->view('editprop');
    $this->load->view('footer');
	}
}

/* End of file editprop.php */
/* Location: ./application/controllers/editprop.php */