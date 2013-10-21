<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
	}

	public function index()
	{
		$user_data = $this->session->userdata('logged_in');
		if ($user_data['rol'] === null)
    {
      redirect('/' . getActLang() . '/restringed');
      exit();
    }

    $data_header = array();
    $data_header["post_title_page"] = " / CMS";

    $this->load->view('header',$data_header);
    $this->load->view('cms/main');
    $this->load->view('footer');
	}
}

/* End of file main.php */
/* Location: ./application/controllers/cms/main.php */