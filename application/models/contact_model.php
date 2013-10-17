<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_model extends CI_Model 
{
	protected $table = 'contact';

	function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->load->model('captcha_model');
  }

  function validate_id($id)
  {
    if (!is_numeric($id)) throw new Exception(lang('exception_error_1001'), 1001);
    if ($id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1001'), 1001);
  }

  function validate_contact()
  {
    return true;
  }

  function send()
  {
    $this->user_name = $this->input->post('user_name');
    $this->user_email = $this->input->post('user_email');
    $this->user_phone = $this->input->post('user_phone');
    $this->user_country_id = $this->input->post('user_country_id');
    $this->user_comment = $this->input->post('user_comment');

    $captcha_word = $this->input->post('captcha_word');
    $ip = $this->input->ip_address();
    $session_id = $this->session->userdata('session_id');

    $this->validate_contact();

    $this->captcha_model->captcha_exist($captcha_word, $ip, $session_id);

    $contact = array();
    $contact['name'] = $this->user_name;
    $contact['email'] = $this->user_email;
    $contact['phone'] = $this->user_phone;
    $contact['country_id'] = $this->user_country_id;
    $contact['comment'] = $this->user_comment;
    $contact['time'] = date("Y-m-d H:i:s");

    $result = $this->db->insert('contact', $contact);
    if ($result)
    {
      return true;
    }

    throw new Exception(lang('exception_error_1135'), 1135);
  }

  function cms_all()
  {
    $query = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
    if ($query->num_rows() > 0)
    {
      $results = array();
      foreach ($query->result() as $row)
      {
        $results[] = $row;
      }
      return $results;
    }

    return array();
  }

  function cms_get($id)
  {
    $query = $this->db->query("SELECT * FROM {$this->table} WHERE id={$id} ORDER BY id DESC");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row;
    }

    return null;
  }

  function cms_save($id = null)
  {
    $data = $this->input->post();
    
    foreach ($data as $key => $value) 
    {
      if ($value === '')
      {
        unset($data[$key]);
      }
    }

    $this->db->trans_begin();

    if ((!isset($id)) ||($id === '') || ($id === null))
    {
      $this->db->insert($this->table, $data);
      
      $query = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT 1");
      $row = $query->row();
      $data['id'] = $row->id;
    }
    else
    {
      $this->db->where('id', $id);
      $this->db->update($this->table, $data);
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception("Error al guardar", 1);
    }
    else
    {
      $this->db->trans_commit();
      return $data['id'];
    }

    throw new Exception("Error al guardar", 1);
  }

  function cms_delete($id)
  {
    $this->db->where('id', $id);
    $result = $this->db->delete($this->table);
    if ($result)
    {
      return $result;
    }

    throw new Exception("Error al eliminar", 1);
  }
}

/* End of file contact_model.php */
/* Location: ./application/models/contact_model.php */