<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends CI_Controller 
{
  protected $tag = 'contacts';

	public function __construct()
	{
    parent::__construct();
    $this->load->model('contact_model');
	}

	public function index()
	{
  	$user_data = $this->session->userdata('logged_in');
  	if ($user_data['rol'] === null)
    {
      redirect('/' . getActLang() . '/restringed');
      exit();
    }  

    $this->load->view('header');
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

    $textareas = array("comment");

    $data = $this->contact_model->cms_get($id);
    $data = array("data" => $data, 'textareas' => $textareas, 'action' => 'edit', 'back' => $this->tag);

    $this->load->view('header');
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

    $textareas = array("comment");

    $data = new StdClass();
    $data->id = null;
    $data->name = null;
    $data->email = null;
    $data->phone = null;
    $data->country_id = null;
    $data->time = null;
    $data->comment = null;
    $data->in_progress = null;
    $data->solved = null;

    $data = array("data" => $data, 'textareas' => $textareas, 'action' => 'new', 'back' => $this->tag);

    $this->load->view('header');
    $this->load->view("cms/{$this->tag}_edit",$data);
    $this->load->view('footer');
  }
}

/* End of file props.php */
/* Location: ./application/controllers/cms/props.php */