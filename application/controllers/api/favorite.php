<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Favorite extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('favorite_model'); 
	}

	// Obtiene todos las props favoritas de un usuario
	public function get_from_user($user_id)
	{
		try
		{
			$this->favorite_model->validate_id($user_id);
			$result = $this->favorite_model->get_from_user($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Añade una propuesta al listado de favoritos de un usuario
	public function add_favorite($user_id)
	{
		try
		{
			$this->favorite_model->validate_id($user_id);
			$result = $this->favorite_model->add_favorite($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Elimina una propuesta del listado de favoritos de un usuario
	public function delete_favorite($favorite_id)
	{
		try
		{
			$this->favorite_model->validate_id($favorite_id);
			$result = $this->favorite_model->delete_favorite($favorite_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file favorite.php */
/* Location: ./application/controllers/api/favorite.php */