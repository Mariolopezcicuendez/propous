<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plink extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
    $this->load->model('user_model'); 
	}

	public function forgetpassword($token)
	{
		try
		{
			$this->user_model->forgetpassword_linkpressed($token);
			redirect('/' . getActLang() . '/forgetpassword/newpassword/'.$token);
		} 
		catch (Exception $e)
		{
			$data = array(
				"result" => 0,
				"tag" => "forgetpassword",
				"message" => $e->getMessage()
			);
			$this->load->view('header');
			$this->load->view('plink',$data);
			$this->load->view('footer');
		}		
	}

	public function activeaccount($token)
	{
		try
		{
			$this->user_model->active_account_linkpressed($token);
			$data = array(
				"result" => 1,
				"tag" => "activeaccount",
				"message" => "Account activated, please logged in"
			);
			$this->load->view('header');
			$this->load->view('plink',$data);
			$this->load->view('footer');
		} 
		catch (Exception $e)
		{
			$data = array(
				"result" => 0,
				"tag" => "activeaccount",
				"message" => $e->getMessage()
			);
			$this->load->view('header');
			$this->load->view('plink',$data);
			$this->load->view('footer');
		}		
	}
}

/* End of file plink.php */
/* Location: ./application/controllers/plink.php */