<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Useconditions extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
	}

	public function index()
	{
		$this->load->view('header');
    $this->load->view('useconditions');
    $this->load->view('footer');
	}
}

/* End of file useconditions.php */
/* Location: ./application/controllers/useconditions.php */