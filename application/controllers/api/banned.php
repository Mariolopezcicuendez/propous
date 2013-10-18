<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banned extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('banned_model'); 
	}

	public function cms_all()
	{
		try
		{
			$result = $this->banned_model->cms_all();
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
			$result = $this->banned_model->cms_save($id);
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
			$this->banned_model->validate_id($id);
			$result = $this->banned_model->cms_delete($id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file banned.php */
/* Location: ./application/controllers/api/banned.php */