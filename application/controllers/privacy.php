<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privacy extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
	}

	public function index()
	{
		$this->load->view('header');
    $this->load->view('privacy');
    $this->load->view('footer');
	}
}

/* End of file privacy.php */
/* Location: ./application/controllers/privacy.php */