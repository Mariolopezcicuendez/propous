<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
	}

	public function index()
	{
		$data_header = array();
    $data_header["post_title_page"] = " / " . lang('p_maintenance_page');

    $this->load->view('header', $data_header);
    $this->load->view('maintenance');
    $this->load->view('footer');
	}
}

/* End of file maintenance.php */
/* Location: ./application/controllers/maintenance.php */