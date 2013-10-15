<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Props extends CI_Controller 
{
  protected $tag = 'props';

	public function __construct()
	{
    parent::__construct();
    $this->load->model('proposal_model');
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
      
    $textareas = array("description");

    $data = $this->proposal_model->cms_get($id);
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

    $textareas = array("description");

    $data = new StdClass();
    $data->id = null;
    $data->proposal = null;
    $data->time = null;
    $data->user_id = null;
    $data->visibility = null;
    $data->description = null;
    $data->visited = null;
    $data->country_id = null;
    $data->state_id = null;
    $data->moderated_invalid = null;

    $data = array("data" => $data, 'textareas' => $textareas, 'action' => 'new', 'back' => $this->tag);

    $this->load->view('header');
    $this->load->view("cms/{$this->tag}_edit",$data);
    $this->load->view('footer');
  }
}

/* End of file props.php */
/* Location: ./application/controllers/cms/props.php */