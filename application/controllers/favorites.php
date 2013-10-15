<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Favorites extends CI_Controller 
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
    $this->load->view('favorites');
    $this->load->view('footer');
	}
}

/* End of file favorites.php */
/* Location: ./application/controllers/favorites.php */