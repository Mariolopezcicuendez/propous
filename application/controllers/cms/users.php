<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller 
{
	protected $tag = 'users';

	public function __construct()
	{
    parent::__construct();
    $this->load->model('user_model');
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

		$textareas = array("description","hobbies");

		$data = $this->user_model->cms_get($id);
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

		$textareas = array("description","hobbies");

		$data = new StdClass();
		$data->id = null;
		$data->name = null;
		$data->email = null;
		$data->password = null;
		$data->birthdate = null;
		$data->registerdate = null;
		$data->sex = null;
		$data->country_id = null;
		$data->state_id = null;
		$data->nationality = null;
		$data->description = null;
		$data->dwelling = null;
		$data->car = null;
		$data->sexuality = null;
		$data->children = null;
		$data->partner = null;
		$data->hobbies = null;
		$data->occupation = null;
		$data->phone = null;
		$data->activated = null;
		$data->change_password_token = null;
		$data->activate_account_token = null;
		$data->login_cookie_tag = null;
		$data->rol = null;

		$data = array("data" => $data, 'textareas' => $textareas, 'action' => 'new', 'back' => $this->tag);

		$this->load->view('header');
    $this->load->view("cms/{$this->tag}_edit",$data);
    $this->load->view('footer');
	}
}

/* End of file users.php */
/* Location: ./application/controllers/cms/users.php */