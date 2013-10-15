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
		if ($user_data['rol'] !== 'superadmin')
    {
      redirect('/' . getActLang() . '/restringed');
      exit();
    }  

    $this->load->view('header');
    $this->load->view('analytics/main');
    $this->load->view('footer');
	}
}

/* End of file main.php */
/* Location: ./application/controllers/analytics/main.php */