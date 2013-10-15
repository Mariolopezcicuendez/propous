<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Country extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('country_model'); 
	}

	// Devuelve todos los paises
	public function all()
	{
		try
		{
			$result = $this->country_model->all();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve el nombre del país
	public function get_country_name($country_id)
	{
		try
		{
			$this->country_model->validate_id($state_id);
			$result = $this->country_model->get_country_name($country_id);
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
			$result = $this->country_model->cms_all();
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
			$result = $this->country_model->cms_save($id);
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
			$this->country_model->validate_id($id);
			$result = $this->country_model->cms_delete($id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file country.php */
/* Location: ./application/controllers/api/country.php */