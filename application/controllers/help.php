<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
	}

	public function index()
	{
		$this->load->view('header');
    $this->load->view('help');
    $this->load->view('footer');
	}
}

/* End of file help.php */
/* Location: ./application/controllers/help.php */