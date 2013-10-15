<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('captcha_model'); 
	}

	public function get_new_captcha()
	{
		try
		{
			$capcha = $this->captcha_model->get_captcha();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($capcha['image']);
	}
}

/* End of file captcha.php */
/* Location: ./application/controllers/api/captcha.php */