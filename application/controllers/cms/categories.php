<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller 
{
  protected $tag = 'categories';

	public function __construct()
	{
    parent::__construct();
    $this->load->model('category_model');
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

    $textareas = array();

    $data = $this->category_model->cms_get($id);
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

    $textareas = array();

    $data = new StdClass();
    $data->id = null;
    $data->tag = null;

    $langs_alias = $this->config->item('language_alias');
    foreach ($langs_alias as $key => $value)
    {
      $name_field = "I18n_{$key}_id";
      $data->$name_field = null;
      $name_field = "I18n_{$key}_name";
      $data->$name_field = null;
    }

    $data = array("data" => $data, 'textareas' => $textareas, 'action' => 'new', 'back' => $this->tag);

    $this->load->view('header');
    $this->load->view("cms/{$this->tag}_edit",$data);
    $this->load->view('footer');
  }
}

/* End of file categories.php */
/* Location: ./application/controllers/cms/categories.php */