<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
    $this->load->model('user_model'); 
    $this->load->model('maintenance_model');
	}

	public function index()
	{
    if ($this->maintenance_model->on_maintenance())
    {
      redirect('/' . getActLang() . '/maintenance');
      exit();
    }

    if ($this->user_model->logged())
    {
    	redirect('/' . getActLang() . '/prop');
    }
    else
    {
      // Comprobamos si tiene cookie de recordar usuario
      if (isset($_COOKIE['user_email']) && isset($_COOKIE['user_login_tag']))
      {
        if (($_COOKIE['user_email'] !== "") && ($_COOKIE['user_login_tag'] !== ""))
        {
          try
          {
            $this->user_model->login_cookie();  
          } 
          catch (Exception $e)
          {
            redirect('/' . getActLang() . '/login');
          }
        }
        else
        {
          redirect('/' . getActLang() . '/login');
        }
      }
      else
      {
        redirect('/' . getActLang() . '/login');
      }
    }
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */