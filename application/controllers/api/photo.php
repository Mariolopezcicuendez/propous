<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photo extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('photo_model'); 
	}

	// Devuelve todas la información de las fotos de un usuario
	public function all_from_user($user_id)
	{
		try
		{
			$this->photo_model->validate_id($user_id);
			$result = $this->photo_model->all_from_user($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve todas la información de las fotos de una propuesta
	public function all_from_proposal($proposal_id)
	{
		try
		{
			$this->photo_model->validate_id($proposal_id);
			$result = $this->photo_model->all_from_proposal($proposal_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Añade una foto (info+archivo) a un usuario
	public function add_for_user($user_id)
	{
		try
		{
			$this->photo_model->validate_id($user_id);
			$result = $this->photo_model->add_for_user($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Añade una foto (info+archivo) a una propuesta
	public function add_for_proposal($proposal_id)
	{
		try
		{
			$this->photo_model->validate_id($proposal_id);
			$result = $this->photo_model->add_for_proposal($proposal_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Elimina una foto (info+archivo) por su id
	public function delete($photo_id)
	{
		try
		{
			$this->photo_model->validate_id($photo_id);
			$result = $this->photo_model->delete($photo_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Setea la foto para que sea la principal del perfil de usuario
	public function set_photo_as_main($user_id, $photo_id)
	{
		try
		{
			$this->photo_model->validate_id($user_id);
			$this->photo_model->validate_id($photo_id);
			$result = $this->photo_model->set_photo_as_main($user_id,$photo_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Obtiene los datos de la foto principal del usuario
	public function get_main_from_user($user_id)
	{
		try
		{
			$this->photo_model->validate_id($user_id);
			$result = $this->photo_model->get_main_from_user($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file photo.php */
/* Location: ./application/controllers/api/photo.php */