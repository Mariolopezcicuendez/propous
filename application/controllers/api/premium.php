<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Premium extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('premium_model'); 
	}

	// Añade una tarifa premium a un usuario desde una fecha determinada
	public function add()
	{
		try
		{
			$result = $this->premium_model->add();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve de un usuario y según la fecha actual la tarifa mayor que posee
	public function get($user_id)
	{
		try
		{
			$this->premium_model->validate_id($user_id);
			$result = $this->premium_model->get($user_id);
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
			$result = $this->premium_model->cms_all();
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
			$result = $this->premium_model->cms_save($id);
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
			$this->premium_model->validate_id($id);
			$result = $this->premium_model->cms_delete($id);
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
			$result = $this->premium_model->analitycs_stats();
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
			$result = $this->premium_model->analitycs_detail();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file premium.php */
/* Location: ./application/controllers/api/premium.php */