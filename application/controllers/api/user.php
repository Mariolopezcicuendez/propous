<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('user_model'); 
	}

	// Devuelve la información de un usuario
	public function get($user_id)
	{
		try
		{
			$this->user_model->validate_id($user_id);
			$result = $this->user_model->get($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Registra a un usuario
	public function register()
	{
		try
		{
			$result = $this->user_model->register();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Modifica la información de un usuario
	public function save($user_id)
	{
		try
		{
			$this->user_model->validate_id($user_id);
			$result = $this->user_model->save($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Elimina un usuario
	public function delete($user_id)
	{
		try
		{
			$this->user_model->validate_id($user_id);
			$result = $this->user_model->delete($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Cambia la contraseña de un usuario
	public function change_password($user_id)
	{
		try
		{
			$this->user_model->validate_id($user_id);
			$result = $this->user_model->change_password($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Cambia la contraseña del usuario que posee el token
	public function change_password_token()
	{
		try
		{
			$result = $this->user_model->change_password_token();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Comprueba si un usuario está logueado en la aplicación
	public function logged()
	{
		try
		{
			$result = $this->user_model->logged();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Loguea a un usuario en la aplicación
	public function login()
	{
		try
		{
			$result = $this->user_model->login();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Desloguea una sesión de usuario
	public function logout()
	{
		try
		{
			$result = $this->user_model->logout();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// envía un enlace para reestablecer la contraseña de un usuario que la ha olvidado
	public function forgetpassword()
	{
		try
		{
			$result = $this->user_model->forgetpassword();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve el número total de usuarios activos
	public function get_total_users()
	{
		try
		{
			$result = $this->user_model->get_total_users();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve el número total de usuarios activos en una ciudad
	public function get_total_users_state($country_id, $state_id)
	{
		try
		{
			$this->user_model->validate_id($country_id);
			$this->user_model->validate_id($state_id);
			$result = $this->user_model->get_total_users_state($country_id, $state_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve el número total de usuarios activos online en una ciudad 
	public function get_total_users_state_online($country_id, $state_id)
	{
		try
		{
			$this->user_model->validate_id($country_id);
			$this->user_model->validate_id($state_id);
			$result = $this->user_model->get_total_users_state_online($country_id, $state_id);
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
			$result = $this->user_model->cms_all();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	public function cms_save($user_id = null)
	{
		try
		{
			$result = $this->user_model->cms_save($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	public function cms_delete($user_id)
	{
		try
		{
			$this->user_model->validate_id($user_id);
			$result = $this->user_model->cms_delete($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	public function cms_get_user_rol($user_id)
	{
		try
		{
			$this->user_model->validate_id($user_id);
			$result = $this->user_model->cms_get_user_rol($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	public function analitycs_stats()
	{
		try
		{
			$result = $this->user_model->analitycs_stats();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	public function analitycs_detail()
	{
		try
		{
			$result = $this->user_model->analitycs_detail();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file user.php */
/* Location: ./application/controllers/api/user.php */