<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('message_model'); 
	}

	// Envía un mensaje de un usuario a otro
	public function send()
	{
		try
		{
			$result = $this->message_model->send();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve de un usuario el listado de los n usuarios usuarios con los que ha estado hablando
	public function get_last_messaged_users($user_id, $num_users)
	{
		try
		{
			$this->message_model->validate_id($user_id);
			$this->message_model->validate_count_number($num_users);
			$result = $this->message_model->get_last_messaged_users($user_id, $num_users);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Asigna como leido un mensaje
	public function set_all_message_readen($user_from_id, $user_to_id)
	{
		try
		{
			$this->message_model->validate_id($user_from_id);
			$this->message_model->validate_id($user_to_id);
			$result = $this->message_model->set_all_message_readen($user_from_id, $user_to_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve el numero total de mensajes no leidos por un usuario
	public function get_count_no_readen_messages($user_id)
	{
		try
		{
			$this->message_model->validate_id($user_id);
			$result = $this->message_model->get_count_no_readen_messages($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve el numero total de mensajes de otro usuario no leidos por el usuario
	public function get_count_no_readen_messages_user($user_from_id, $user_to_id)
	{
		try
		{
			$this->message_model->validate_id($user_from_id);
			$this->message_model->validate_id($user_to_id);
			$result = $this->message_model->get_count_no_readen_messages_user($user_from_id, $user_to_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve si un usuario está escribiendo a otro en ese preciso momento
	public function check_user_writing($user_from_id, $user_to_id)
	{
		try
		{
			$this->message_model->validate_id($user_from_id);
			$this->message_model->validate_id($user_to_id);
			$result = $this->message_model->check_user_writing($user_from_id, $user_to_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Setea en estado de escribiendo de un usuario a otro
	public function set_user_writing($user_from_id, $user_to_id)
	{
		try
		{
			$this->message_model->validate_id($user_from_id);
			$this->message_model->validate_id($user_to_id);
			$result = $this->message_model->set_user_writing($user_from_id, $user_to_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Setea en estado de no escribiendo de un usuario a otro
	public function set_user_no_writing($user_from_id, $user_to_id)
	{
		try
		{
			$this->message_model->validate_id($user_from_id);
			$this->message_model->validate_id($user_to_id);
			$result = $this->message_model->set_user_no_writing($user_from_id, $user_to_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}	

	// Devuelve en formato para el chat info de user nuevo
	public function get_user_conversation_data($user_from_id, $user_to_id)
	{
		try
		{
			$this->message_model->validate_id($user_from_id);
			$this->message_model->validate_id($user_to_id);
			$result = $this->message_model->get_user_conversation_data($user_from_id, $user_to_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve todos los mensajes de chat con un usuario desde un id de mensaje
	public function get_conversation_from_id($user_from_id, $user_to_id)
	{
		try
		{
			$this->message_model->validate_id($user_from_id);
			$this->message_model->validate_id($user_to_id);
			$result = $this->message_model->get_conversation_from_id($user_from_id, $user_to_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve el id del ultimo mensaje leido
	function get_last_message_readen($user_from_id, $user_to_id)
	{
		try
		{
			$this->message_model->validate_id($user_from_id);
			$this->message_model->validate_id($user_to_id);
			$result = $this->message_model->get_last_message_readen($user_from_id, $user_to_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Elimina todas la conversacion de un usuario hacia otro usuario
	function delete_conversation($user_from_id, $user_to_id)
	{
		try
		{
			$this->message_model->validate_id($user_from_id);
			$this->message_model->validate_id($user_to_id);
			$result = $this->message_model->delete_conversation($user_from_id, $user_to_id);
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
			$result = $this->message_model->cms_all();
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
			$result = $this->message_model->cms_save($id);
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
			$this->message_model->validate_id($id);
			$result = $this->message_model->cms_delete($id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}


/* End of file message.php */
/* Location: ./application/controllers/api/message.php */