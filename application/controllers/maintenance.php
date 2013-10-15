<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
	}

	public function index()
	{
		$this->load->view('header');
    $this->load->view('maintenance');
    $this->load->view('footer');
	}
}

/* End of file maintenance.php */
/* Location: ./application/controllers/maintenance.php */