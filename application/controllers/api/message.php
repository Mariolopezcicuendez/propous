<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('message_model'); 
	}

	public function actualize_messages_info()
	{
		try
		{
			$result = $this->message_model->actualize_messages_info();
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