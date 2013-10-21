<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Viewprop extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
    $this->load->model('user_model');
    $this->load->model('proposal_model');
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
     $data_header["post_title_page"] = "";
    if ($this->user_model->logged())
    {
      $user_data = $this->session->userdata('logged_in');
      $notifies = $this->notify_model->get_all_no_readen_for_user($user_data['id']);
      $data_header["notifies"] = $notifies;

      $prop_id = $this->input->get("id", true);
      $data_header["prop_id"] = $prop_id;
      $data_header["post_title_page"] = " / " . $this->proposal_model->get_proposal_title($prop_id);
    }

		$this->load->view('header',$data_header);
    $this->load->view('viewprop');
    $this->load->view('footer');
	}
}

/* End of file viewprop.php */
/* Location: ./application/controllers/viewprop.php */