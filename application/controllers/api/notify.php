<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notify extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('notify_model'); 
	}

	public function set_readen()
	{
		try
		{
			$result = $this->notify_model->set_readen();
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
			$result = $this->notify_model->cms_all();
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
			$result = $this->notify_model->cms_save($id);
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
			$this->notify_model->validate_id($id);
			$result = $this->notify_model->cms_delete($id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file notify.php */
/* Location: ./application/controllers/api/notify.php */