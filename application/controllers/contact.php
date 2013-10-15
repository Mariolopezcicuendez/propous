<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
    $this->load->model('captcha_model','',TRUE);
	}

	public function index()
	{
		$capcha = $this->captcha_model->get_captcha();
		$data = array("captcha" => $capcha);

		$this->load->view('header');
    $this->load->view('contact', $data);
    $this->load->view('footer');
	}
}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */