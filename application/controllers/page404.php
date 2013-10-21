<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page404 extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
	}

	public function index()
	{
		$data_header = array();
    $data_header["post_title_page"] = " / 404";

		$user_data = $this->session->userdata('logged_in');

		if ($user_data !== null)
		{
			if ((($this->uri->segment(2)) == 'cms') && ($user_data['rol'] !== null))
			{
				redirect('/es/cms/'.$this->uri->segment(3));
		    exit();
			}
			if ((($this->uri->segment(2)) == 'analytics') && ($user_data['rol'] === 'superadmin'))
			{
				redirect('/es/analytics/main');
		    exit();
			}
		}

		if (("/".$this->uri->segment(2)) == REQUEST_URL_API)
		{
			$e = new Exception(lang('exception_error_1844'), 1844);
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		if ($this->uri->segment(2) == '')
		{
			redirect('/' . getActLang() . '/home');
			exit();
		}

		$this->load->view('header', $data_header);
    $this->load->view('404');
    $this->load->view('footer');
	}
}

/* End of file 404.php */
/* Location: ./application/controllers/404.php */