<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailsend extends CI_Model 
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

    $this->load->library('email');

    $config['protocol'] = 'sendmail';
    $config['mailpath'] = '/usr/sbin/sendmail';
    $config['charset'] = 'utf-8';
    $config['wordwrap'] = TRUE;

    $this->email->initialize($config);
  }

  function send($email)
  {
    $this->email->from($email->from, $email->frominfo);
    $this->email->to($email->to); 
    $this->email->subject($email->subject);
    $this->email->message($email->message);  
    $this->email->send();
    //echo $this->email->print_debugger();
    echo $email->message;
  }
}

/* End of file emailsend.php */
/* Location: ./application/models/emailsend.php */