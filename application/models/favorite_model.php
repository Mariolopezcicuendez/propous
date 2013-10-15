<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Favorite_model extends CI_Model 
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  function validate_id($id)
  {
    if (!is_numeric($id)) throw new Exception(lang('exception_error_1001'), 1001);
    if ($id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1001'), 1001);
  }

  function get_from_user($user_id)
  {
    $query = $this->db->query("SELECT id, proposal_id FROM `favorite` WHERE user_id = {$user_id}");
    if ($query->num_rows() > 0)
    {
      $results = array();
      foreach ($query->result() as $row)
      {
        $results[] = $row;
      }
      return $results;
    }
    return null;
  }

  function add_favorite($user_id)
	{
		$proposal_id = $this->input->post('proposal_id');

		$this->validate_id($proposal_id);

		$query = $this->db->query("SELECT id FROM `favorite` WHERE user_id = {$user_id} AND proposal_id = {$proposal_id}");
    if ($query->num_rows() == 0)
    {
      $this->db->trans_begin();

    	$favorite = array();
			$favorite['user_id'] = $user_id;
			$favorite['proposal_id'] = $proposal_id;

			$this->db->insert('favorite', $favorite);
      $query = $this->db->query("SELECT id, user_id, proposal_id FROM `favorite` WHERE user_id = {$user_id} AND proposal_id = {$proposal_id}");

      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE)
      {
        $this->db->trans_rollback();
        throw new Exception(lang('exception_error_1252'), 1252);
      }
      else
      {
        $this->db->trans_commit();
        return $query->row();
      }
    }
    else
    {
      return null;
    }
	}

	function delete_favorite($favorite_id)
	{
		$this->db->where('id', $favorite_id);
    $result = $this->db->delete('favorite');
    if ($result)
    {
      return $favorite_id;
    }

    throw new Exception(lang('exception_error_1251'), 1251);
	}
}

/* End of file proposal_model.php */
/* Location: ./application/models/proposal_model.php */