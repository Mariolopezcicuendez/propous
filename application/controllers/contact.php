<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
    $this->load->model('captcha_model','',TRUE);
    $this->load->model('user_model');
	}

	public function index()
	{
		try
    {
      $capcha = $this->captcha_model->get_captcha();
    } 
    catch (Exception $e)
    {
      redirect('/' . getActLang() . '/error');
      exit();
    } 
    
		$data = array("captcha" => $capcha);

		$data_header = array();
    $data_header["notifies"] = array();
    $data_header["post_title_page"] = " / " . lang('p_contact');
    if ($this->user_model->logged())
    {
      $user_data = $this->session->userdata('logged_in');
      $notifies = $this->notify_model->get_all_no_readen_for_user($user_data['id']);
      $data_header["notifies"] = $notifies;
    }

		$this->load->view('header',$data_header);
    $this->load->view('contact', $data);
    $this->load->view('footer');
	}
}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */