<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banneds extends CI_Controller 
{
  protected $tag = 'banneds';

	public function __construct()
	{
    parent::__construct();
    $this->load->model('banned_model');
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
    $this->load->view("cms/{$this->tag}");
    $this->load->view('footer');
	}

  public function edit($id)
  {
    $user_data = $this->session->userdata('logged_in');
    if ($user_data['rol'] === null)
    {
      redirect('/' . getActLang() . '/restringed');
      exit();
    }  

    $textareas = array();

    $data = $this->banned_model->cms_get($id);
    $data = array("data" => $data, 'textareas' => $textareas, 'action' => 'edit', 'back' => $this->tag);

    $data_header = array();
    $data_header["post_title_page"] = " / CMS";

    $this->load->view('header',$data_header);
    $this->load->view("cms/{$this->tag}_edit",$data);
    $this->load->view('footer');
  }

  public function create()
  {
    $user_data = $this->session->userdata('logged_in');
    if ($user_data['rol'] === null)
    {
      redirect('/' . getActLang() . '/restringed');
      exit();
    }  

    $textareas = array();

    $data = new StdClass();
    $data->id = null;
    $data->email = null;
    $data->time = null;
    $data->reason_tag = null;

    $data = array("data" => $data, 'textareas' => $textareas, 'action' => 'new', 'back' => $this->tag);

    $data_header = array();
    $data_header["post_title_page"] = " / CMS";

    $this->load->view('header',$data_header);
    $this->load->view("cms/{$this->tag}_edit",$data);
    $this->load->view('footer');
  }
}

/* End of file banneds.php */
/* Location: ./application/controllers/cms/banneds.php */