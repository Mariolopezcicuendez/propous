<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language extends CI_Controller 
{
	protected $actual_language = null;

	public function __construct()
	{
    parent::__construct();
   	$this->load->model('language_model'); 
	}

	// Devuelve todos los lenguages
	public function all()
	{
		try
		{
			$result = $this->language_model->all();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve todos los lenguages que habla un usuario
	public function get_spoken($user_id)
	{
		// Example: 1:native
		try
		{
			$this->language_model->validate_id($user_id);
			$result = $this->language_model->get_spoken($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Asigna a un usuario un estado de habla de un lenguage
	public function save_spoken($user_id)
	{
		// Example: 4:medium
		try
		{
			$this->language_model->validate_id($user_id);
			$result = $this->language_model->save_spoken($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Elimina de un usuario un estado de habla de un lenguage
	public function delete_spoken($spoken_id)
	{
		try
		{
			$this->language_model->validate_id($spoken_id);
			$result = $this->language_model->delete_spoken($spoken_id);
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
			$result = $this->language_model->cms_all();
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
			$result = $this->language_model->cms_save($id);
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
			$this->language_model->validate_id($id);
			$result = $this->language_model->cms_delete($id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file language.php */
/* Location: ./application/controllers/api/language.php */