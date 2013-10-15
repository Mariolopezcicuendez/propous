<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chgpassword extends CI_Controller 
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
    $this->load->view('chgpassword',$data);
    $this->load->view('footer');
	}
}

/* End of file chgpassword.php */
/* Location: ./application/controllers/chgpassword.php */