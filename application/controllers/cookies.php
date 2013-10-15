<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cookies extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
	}

	public function index()
	{
		$this->load->view('header');
    $this->load->view('cookies');
    $this->load->view('footer');
	}
}

/* End of file cookies.php */
/* Location: ./application/controllers/cookies.php */