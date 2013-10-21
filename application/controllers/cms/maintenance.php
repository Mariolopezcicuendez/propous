<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends CI_Controller 
{
	protected $tag = 'maintenance';

	public function __construct()
	{
    parent::__construct();
    $this->load->model('maintenance_model');
	}

	public function index()
  {
    $user_data = $this->session->userdata('logged_in');
    if ($user_data['rol'] === null)
    {
      redirect('/' . getActLang() . '/restringed');
      exit();
    }    

    $data = $this->maintenance_model->on_maintenance();
    $data = array("data" => $data, 'back' => $this->tag);

    $data_header = array();
    $data_header["post_title_page"] = " / CMS";

    $this->load->view('header',$data_header);
    $this->load->view("cms/{$this->tag}",$data);
    $this->load->view('footer');
  }
}

/* End of file maintenance.php */
/* Location: ./application/controllers/cms/maintenance.php */