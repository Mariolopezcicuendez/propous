<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class State extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('state_model'); 
	}

	// Devuelve todos los estados de un determinado pais
	public function all_from_country($country_id)
  {
    try
		{
			$this->state_model->validate_id($country_id);
			$result = $this->state_model->all_from_country($country_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
  }

  // Devuelve el nombre del estado
  public function get_state_name($state_id)
  {
    try
    {
      $this->state_model->validate_id($state_id);
      $result = $this->state_model->get_state_name($state_id);
    } 
    catch (Exception $e)
    {
      echo  $this->jsonresponse->show_error($e);
      exit();
    }

    echo $this->jsonresponse->show($result);
  }

  public function cms_all()
  {
    try
    {
      $result = $this->state_model->cms_all();
    } 
    catch (Exception $e)
    {
      echo  $this->jsonresponse->show_error($e);
      exit();
    }

    echo $this->jsonresponse->show($result);
  }

  public function cms_save($id = null)
  {
    try
    {
      $result = $this->state_model->cms_save($id);
    } 
    catch (Exception $e)
    {
      echo  $this->jsonresponse->show_error($e);
      exit();
    }

    echo $this->jsonresponse->show($result);
  }

  public function cms_delete($id)
  {
    try
    {
      $this->state_model->validate_id($id);
      $result = $this->state_model->cms_delete($id);
    } 
    catch (Exception $e)
    {
      echo  $this->jsonresponse->show_error($e);
      exit();
    }

    echo $this->jsonresponse->show($result);
  }
}

/* End of file state.php */
/* Location: ./application/controllers/api/state.php */