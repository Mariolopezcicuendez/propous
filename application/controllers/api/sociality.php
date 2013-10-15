<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sociality extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('sociality_model'); 
	}

	// Devuelve todos los estados sociales
	public function all()
	{
		try
		{
			$result = $this->sociality_model->all();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve las socializaciones de un usuario
	public function get_social($user_id)
	{
		try
		{
			$this->sociality_model->validate_id($user_id);
			$result = $this->sociality_model->get_social($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Asigna una socialización a un usuario
	public function save_social($user_id)
	{
		try
		{
			$this->sociality_model->validate_id($user_id);
			$result = $this->sociality_model->save_social($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Elimina una socialización de un usuario
	public function delete_social($social_id)
	{
		try
		{
			$this->sociality_model->validate_id($social_id);
			$result = $this->sociality_model->delete_social($social_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Hace que un estado social aparezca como información en las propuestas de ese usuario
	public function show_in_proposal($user_id, $sociality_id)
	{
		try
		{
			$this->sociality_model->validate_id($user_id);
			$this->sociality_model->validate_id($sociality_id);
			$result = $this->sociality_model->show_in_proposal($user_id, $sociality_id);
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
			$result = $this->sociality_model->cms_all();
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
			$result = $this->sociality_model->cms_save($id);
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
			$this->sociality_model->validate_id($id);
			$result = $this->sociality_model->cms_delete($id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	public function cms_save_icon($id)
	{
		try
		{
			$this->sociality_model->validate_id($id);
			$result = $this->sociality_model->cms_save_icon($id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file sociality.php */
/* Location: ./application/controllers/api/sociality.php */