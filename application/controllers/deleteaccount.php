<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deleteaccount extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
    $this->load->model('user_model');
    $this->load->model('captcha_model','',TRUE);
	}

	public function index()
	{
		if (!$this->user_model->logged())
		{
			redirect('/' . getActLang() . '/home');
			exit();
		}

    $capcha = $this->captcha_model->get_captcha();
    $data = array("captcha" => $capcha);

		$this->load->view('header');
    $this->load->view('deleteaccount', $data);
    $this->load->view('footer');
	}
}

/* End of file deleteaccount.php */
/* Location: ./application/controllers/deleteaccount.php */