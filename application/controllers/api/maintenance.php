<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends CI_Controller 
{
  public function __construct()
  {
    parent::__construct();
     $this->load->model('maintenance_model'); 
  }

  // Devuelve el estado de mantenimiento del servidor
  public function on_maintenance()
  {
    try
    {
      $result = $this->maintenance_model->on_maintenance();
    } 
    catch (Exception $e)
    {
      echo  $this->jsonresponse->show_error($e);
      exit();
    }

    echo $this->jsonresponse->show($result);
  }

  public function cms_save()
  {
    try
    {
      $result = $this->maintenance_model->cms_save();
    } 
    catch (Exception $e)
    {
      echo  $this->jsonresponse->show_error($e);
      exit();
    }

    echo $this->jsonresponse->show($result);
  }
}

/* End of file maintenance.php */
/* Location: ./application/controllers/api/maintenance.php */