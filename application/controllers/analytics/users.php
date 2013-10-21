<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller 
{
	protected $tag = 'users';

	public function __construct()
	{
    parent::__construct();
	}

	public function index()
	{
		$user_data = $this->session->userdata('logged_in');
		if ($user_data['rol'] !== 'superadmin')
    {
      redirect('/' . getActLang() . '/restringed');
      exit();
    }  

    $data_header = array();
    $data_header["post_title_page"] = " / Analitycs";

    $this->load->view('header',$data_header);
    $this->load->view('analytics/'.$this->tag);
    $this->load->view('footer');
	}

	public function detail()
  {
    $user_data = $this->session->userdata('logged_in');
    if ($user_data['rol'] !== 'superadmin')
    {
      redirect('/' . getActLang() . '/restringed');
      exit();
    }

    $data = array('back' => $this->tag);

    $data_header = array();
    $data_header["post_title_page"] = " / Analitycs";

    $this->load->view('header',$data_header);
    $this->load->view("analytics/{$this->tag}_detail",$data);
    $this->load->view('footer');
  }
}

/* End of file users.php */
/* Location: ./application/controllers/analytics/users.php */