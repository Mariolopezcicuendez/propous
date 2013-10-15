<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('contact_model'); 
	}

	// Envia un mensaje de comentario en el formulario de contacto
	public function send()
	{
		try
		{
			$result = $this->contact_model->send();
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
			$result = $this->contact_model->cms_all();
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
			$result = $this->contact_model->cms_save($user_id);
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
			$this->contact_model->validate_id($user_id);
			$result = $this->contact_model->cms_delete($user_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file contact.php */
/* Location: ./application/controllers/api/contact.php */