<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance_model extends CI_Model 
{
  protected $table = 'maintenance';

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  function on_maintenance()
  {
  	$query = $this->db->query("SELECT on_maintenance FROM maintenance");
    if ($query->num_rows() > 0)
    {
    	$row = $query->row();
    	return $row->on_maintenance;
    }

    return false;
  }

  function cms_save()
  {
    $data = $this->input->post();
    
    $result = $this->db->update($this->table, $data);

    if ($result)
    {
      return $data['on_maintenance'];
    }

    throw new Exception("Error al guardar", 1);
  }
}

/* End of file maintenance_model.php */
/* Location: ./application/models/maintenance_model.php */