<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jsonresponse extends CI_Model 
{
  protected $message = null;
  protected $file = null;
  protected $line = null;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  function set_message($msg)
  {
    $this->message = $msg;
  }

  function set_file($msg)
  {
    $this->file = $msg;
  }

  function set_line($msg)
  {
    $this->line = $msg;
  }

  function show($result)
  {
    set_status_header(200);

  	$json_result = new StdClass();
  	$json_result->status = 200;
  	$json_result->count = count($result);
    $json_result->result = $result;

    header("Content-Type: text/json");
  	return json_encode($json_result);
  }

  function show_error(Exception $e)
  {
    set_status_header(200);

  	$error = new StdClass();
  	$error->code = $e->getCode(); 
 	  $error->message = $e->getMessage();

    if (SHOW_DETAILED_ERROR_INFO)
    {
      $error->file = ($this->file !== null) ? $this->file : $e->getFile() ;
      $error->line = ($this->line !== null) ? $this->line : $e->getLine() ;

      if ($this->message !== null)
      {
        $error->message = $e->getMessage() . ": " . $this->message;
      }
    }

  	$json_result = new StdClass();
    $json_result->status = $error->code;
  	$json_result->error = $error;

    header("Content-Type: text/json");
  	return json_encode($json_result);
  }  
}

/* End of file jsonresponse.php */
/* Location: ./application/models/jsonresponse.php */