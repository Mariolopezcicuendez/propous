<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('category_model'); 
	}

	// Devuelve todas las categorías que se pueden asignar a una propuesta
	public function all()
	{
		try
		{
			$result = $this->category_model->all();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve las categorizaciones de una propuesta
	public function get_category($proposal_id)
	{
		try
		{
			$this->category_model->validate_id($proposal_id);
			$result = $this->category_model->get_category($proposal_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Asigna a una propuesta una categorización
	public function save_category($proposal_id)
	{
		try
		{
			$this->category_model->validate_id($proposal_id);
			$result = $this->category_model->save_category($proposal_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Elimina una categorización de una propuesta
	public function delete_category($category_id)
	{
		try
		{
			$this->category_model->validate_id($category_id);
			$result = $this->category_model->delete_category($category_id);
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
			$result = $this->category_model->cms_all();
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
			$result = $this->category_model->cms_save($id);
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
			$this->category_model->validate_id($id);
			$result = $this->category_model->cms_delete($id);
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
			$this->category_model->validate_id($id);
			$result = $this->category_model->cms_save_icon($id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file category.php */
/* Location: ./application/controllers/api/category.php */