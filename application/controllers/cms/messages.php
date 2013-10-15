<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Controller 
{
  protected $tag = 'messages';

	public function __construct()
	{
    parent::__construct();
    $this->load->model('message_model');
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

    $textareas = array("message");

    $data = $this->message_model->cms_get($id);
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

    $textareas = array("message");

    $data = new StdClass();
    $data->id = null;
    $data->user_from_id = null;
    $data->user_to_id = null;
    $data->time = null;
    $data->readen = null;
    $data->message = null;

    $data = array("data" => $data, 'textareas' => $textareas, 'action' => 'new', 'back' => $this->tag);

    $this->load->view('header');
    $this->load->view("cms/{$this->tag}_edit",$data);
    $this->load->view('footer');
  }
}

/* End of file messages.php */
/* Location: ./application/controllers/cms/messages.php */