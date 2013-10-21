<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deleteprop extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
    $this->load->model('user_model');
	}

	public function index()
	{
		if (!$this->user_model->logged())
		{
			redirect('/' . getActLang() . '/home');
			exit();
		}

		$data_header = array();
    $data_header["notifies"] = array();
    $data_header["post_title_page"] = " / " . lang('p_delete_prop');
    if ($this->user_model->logged())
    {
      $user_data = $this->session->userdata('logged_in');
      $notifies = $this->notify_model->get_all_no_readen_for_user($user_data['id']);
      $data_header["notifies"] = $notifies;
    }

		$this->load->view('header',$data_header);
    $this->load->view('deleteprop');
    $this->load->view('footer');
	}
}

/* End of file deleteprop.php */
/* Location: ./application/controllers/deleteprop.php */