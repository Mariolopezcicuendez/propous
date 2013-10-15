<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proposal extends CI_Controller 
{
	public function __construct()
	{
    parent::__construct();
   	$this->load->model('proposal_model'); 
	}

	// Crea una propuesta
	public function create_proposal()
	{
		try
		{
			$result = $this->proposal_model->create();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve los datos de una propuesta
	public function read_proposal($proposal_id)
	{
		try
		{
			$this->proposal_model->validate_id($proposal_id);
			$result = $this->proposal_model->read($proposal_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Cambia los datos de una propuesta
	public function update_proposal($proposal_id)
	{
		try
		{
			$this->proposal_model->validate_id($proposal_id);
			$result = $this->proposal_model->update($proposal_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Elimina una propuesta
	public function delete_proposal($proposal_id)
	{
		try
		{
			$this->proposal_model->validate_id($proposal_id);
			$result = $this->proposal_model->delete($proposal_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Lista las propuestas existentes y visibles según los filtros que se envíen
	public function list_proposals()
	{
		try
		{
			$result = $this->proposal_model->list_proposals();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Define si una propuesta es visible o no
	public function set_proposal_visibility($proposal_id)
	{
		try
		{
			$this->proposal_model->validate_id($proposal_id);
			$result = $this->proposal_model->set_proposal_visibility($proposal_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Añade una visita más a una propuesta
	public function add_one_visited($proposal_id)
	{
		try
		{
			$this->proposal_model->validate_id($proposal_id);
			$result = $this->proposal_model->add_one_visited($proposal_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve el número total de proposiciones existentes y visibles
	public function get_total_props()
	{
		try
		{
			$result = $this->proposal_model->get_total_props();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}	

	// Devuelve el número total de proposiciones existentes y visibles en una ciudad
	public function get_total_props_state($country_id, $state_id)
	{
		try
		{
			$this->proposal_model->validate_id($country_id);
			$this->proposal_model->validate_id($state_id);
			$result = $this->proposal_model->get_total_props_state($country_id, $state_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}

	// Devuelve el número total de proposiciones existentes y visibles en una ciudad en el día actual
	public function get_total_props_state_today($country_id, $state_id)
	{
		try
		{
			$this->proposal_model->validate_id($country_id);
			$this->proposal_model->validate_id($state_id);
			$result = $this->proposal_model->get_total_props_state_today($country_id, $state_id);
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}	

	public function get_total_from_user($user_id)
	{
		try
		{
			$this->proposal_model->validate_id($user_id);
			$result = $this->proposal_model->get_total_from_user($user_id);
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
			$result = $this->proposal_model->cms_all();
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
			$result = $this->proposal_model->cms_save($id);
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
			$this->proposal_model->validate_id($id);
			$result = $this->proposal_model->cms_delete($id);
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
			$result = $this->proposal_model->analitycs_stats();
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
			$result = $this->proposal_model->analitycs_detail();
		} 
		catch (Exception $e)
		{
			echo  $this->jsonresponse->show_error($e);
			exit();
		}

		echo $this->jsonresponse->show($result);
	}
}

/* End of file proposal.php */
/* Location: ./application/controllers/api/proposal.php */