<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
	}

	public function index()
	{
		$data_header = array();
    $data_header["post_title_page"] = " / " . lang('p_error');

		$this->load->view('header',$data_header);
    $this->load->view('error');
    $this->load->view('footer');
	}
}

/* End of file error.php */
/* Location: ./application/controllers/error.php */