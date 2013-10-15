<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgetpassword extends CI_Controller 
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('captcha_model','',TRUE);
  }

  // Muestra la pantalla principal de olvidar contraseña
  public function index()
  {
    $capcha = $this->captcha_model->get_captcha();
    $data = array("captcha" => $capcha);

    $this->load->view('header');
    $this->load->view('forgetpassword',$data);
    $this->load->view('footer');
  }

  // Muestra la pantalla para dar de alta una nueva contraseña de un usuario que la olvidó
  public function newpassword($token)
  {
    $capcha = $this->captcha_model->get_captcha();

    // Ejemplo de link: http://xxxxxx/propous/plink/forgetpassword/{token}
    $data = array("token" => $token, "captcha" => $capcha);
    $this->load->view('header');
    $this->load->view('forgetpasswordnew',$data);
    $this->load->view('footer');
  }
}

/* End of file forgetpassword.php */
/* Location: ./application/controllers/forgetpassword.php */