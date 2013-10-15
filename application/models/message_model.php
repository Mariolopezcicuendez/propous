<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message_model extends CI_Model 
{
  protected $table = 'message';
	protected $message_user_from_id = null;
  protected $message_user_to_id = null;
  protected $message_time = null;
  protected $message_readen = 0;
  protected $message_message = null;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->load->model('user_model'); 
    $this->load->model('photo_model'); 
  }

  function validate_count_number($number)
  {
  	if (!is_numeric($number)) throw new Exception(lang('exception_error_1002'), 1002);
    if ($number < VALIDATE_NUMBER_MIN_VALUE) throw new Exception(lang('exception_error_1002'), 1002);
  }

  function validate_id($id)
  {
    if (!is_numeric($id)) throw new Exception(lang('exception_error_1001'), 1001);
    if ($id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1001'), 1001);
  }

  function validate_message()
  {
  	if (!is_numeric($this->message_user_from_id)) throw new Exception(lang('exception_error_1003'), 1003);
    if ($this->message_user_from_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1003'), 1003);

    if (!is_numeric($this->message_user_to_id)) throw new Exception(lang('exception_error_1004'), 1004);
    if ($this->message_user_to_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1004'), 1004);

    if ((strlen($this->message_message) === 0) || ($this->message_message === null)) throw new Exception(lang('exception_error_1005'), 1005);
  }

  function send()
  {
    $this->message_user_from_id = $this->input->post('user_from_id');
    $this->message_user_to_id = $this->input->post('user_to_id');
    $this->message_message = $this->input->post('message');
    
    $this->validate_message();

    $message = array();
    $message['user_from_id'] = $this->message_user_from_id;
    $message['user_to_id'] = $this->message_user_to_id;
    $message['time'] = date("Y-m-d H:i:s");
    $message['readen'] = $this->message_readen;
    $message['message'] = $this->message_message;

    $this->db->trans_begin();

    $this->db->insert('message', $message);

    $query = $this->db->query("
      SELECT id, user_from_id, user_to_id, time, readen, message 
      FROM `message` 
      WHERE user_from_id = {$this->message_user_from_id} 
      AND user_to_id = {$this->message_user_to_id} 
      ORDER BY id DESC 
      LIMIT 1
    ");
    $row = $query->row();
    $row->user_from_name = $this->user_model->get_user_name($row->user_from_id);
    $row->user_from_photo = $this->photo_model->get_user_photo($row->user_from_id);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception("Error sending message", 1000);
    }
    else
    {
      $this->db->trans_commit();
      return $row;
    }
  }

  function get_last_messaged_users($user_id, $num_users)
  {
  	$query = $this->db->query("
      SELECT DISTINCT(user_to_id) as user_talked, time
      FROM `message` 
      WHERE user_from_id = {$user_id}
      GROUP BY user_to_id 
      UNION
      SELECT DISTINCT(user_from_id) as user_talked, time
      FROM `message` 
      WHERE user_to_id = {$user_id}
      GROUP BY user_from_id 
      ORDER BY time DESC
      LIMIT 0 , {$num_users}
    ");

    if ($query->num_rows() > 0)
    {
      $results = array();
      $results_ids = array();
      foreach ($query->result() as $row)
      {
        if (!in_array($row->user_talked, $results_ids))
        {
          $user_talked = new StdClass();
          $user_talked->id = $row->user_talked;
          $user_talked->no_readen = $this->get_count_no_readen_messages_user($user_talked->id, $user_id);
          $user_talked->name = $this->user_model->get_user_name($user_talked->id);
          $user_talked->photo = $this->photo_model->get_user_photo($user_talked->id);
          $user_talked->connected = $this->user_model->get_is_online($user_talked->id);

          $results[] = $user_talked;
          $results_ids[] = $row->user_talked;
        }
      }
      return $results;
    }

    return array();
  }

  function get_user_conversation_data($user_from_id, $user_to_id)
  {
    $user_talked = new StdClass();
    $user_talked->id = $user_to_id;
    $user_talked->no_readen = $this->get_count_no_readen_messages_user($user_talked->id, $user_from_id);
    $user_talked->name = $this->user_model->get_user_name($user_talked->id);
    $user_talked->photo = $this->photo_model->get_user_photo($user_talked->id);
    $user_talked->connected = $this->user_model->get_is_online($user_talked->id);
    return $user_talked;
  }

  function set_all_message_readen($user_from_id, $user_to_id)
  {
  	$message['readen'] = 1;

  	$this->db->where('user_from_id', $user_from_id);
    $this->db->where('user_to_id', $user_to_id);
    $result = $this->db->update('message', $message);

    return $result;
  }

  function get_count_no_readen_messages($user_id)
  {
    $query = $this->db->query("SELECT id FROM message WHERE user_to_id = {$user_id} AND readen = 0");
    if ($query->num_rows() > 0)
    {
      return $query->num_rows();
    }
    return 0;
  }

  function get_count_no_readen_messages_user($user_from_id, $user_to_id)
  {
    $query = $this->db->query("SELECT id FROM message WHERE (user_from_id = {$user_from_id}) AND (user_to_id = {$user_to_id}) AND readen = 0");
    if ($query->num_rows() > 0)
    {
      return $query->num_rows();
    }
    return 0;
  }

  function check_user_writing($user_from_id, $user_to_id)
  {
    $query = $this->db->query("SELECT writing FROM message_writing WHERE user_from_id = {$user_from_id} AND user_to_id = {$user_to_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->writing;
    }
    else
    {
      $message_writing = array();
      $message_writing['user_from_id'] = $user_from_id;
      $message_writing['user_to_id'] = $user_to_id;
      $message_writing['writing'] = 0;

      $result = $this->db->insert('message_writing', $message_writing);
      if ($result)
      {
        return 0;
      }

      return 0;
    }
  }

  function set_user_writing($user_from_id, $user_to_id)
  {
    $query = $this->db->query("SELECT writing FROM message_writing WHERE user_from_id = {$user_from_id} AND user_to_id = {$user_to_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();

      if ($row->writing !== "1")
      {
        $message_writing = array();
        $message_writing['writing'] = 1;

        $this->db->where('user_from_id', $user_from_id);
        $this->db->where('user_to_id', $user_to_id);
        $result = $this->db->update('message_writing', $message_writing);
        
        return $result;
      }
      return 1;
    }
    else
    {
      $message_writing = array();
      $message_writing['user_from_id'] = $user_from_id;
      $message_writing['user_to_id'] = $user_to_id;
      $message_writing['writing'] = 0;

      $result = $this->db->insert('message_writing', $message_writing);
      if ($result)
      {
        return 1;
      }

      return 1;
    }
  }

  function set_user_no_writing($user_from_id, $user_to_id)
  {
    $query = $this->db->query("SELECT writing FROM message_writing WHERE user_from_id = {$user_from_id} AND user_to_id = {$user_to_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();

      if ($row->writing !== "0")
      {
        $message_writing = array();
        $message_writing['writing'] = 0;

        $this->db->where('user_from_id', $user_from_id);
        $this->db->where('user_to_id', $user_to_id);
        $result = $this->db->update('message_writing', $message_writing);
        
        return $result;
      }
      return 0;
    }
    else
    {    
      $message_writing = array();
      $message_writing['user_from_id'] = $user_from_id;
      $message_writing['user_to_id'] = $user_to_id;
      $message_writing['writing'] = 0;

      $result = $this->db->insert('message_writing', $message_writing);
      if ($result)
      {
        return 0;
      }

      return 0;
    }  
  }

  function get_conversation_from_id($user_from_id, $user_to_id)
  {
    $message_id = $this->input->post('message_id');
    $count = $this->input->post('count');

    $this->validate_id($message_id);
    $this->validate_count_number($count);

    $query = $this->db->query("
      SELECT id, user_from_id, user_to_id, time, readen, message
      FROM  `message` 
      WHERE user_from_id = {$user_from_id}
      AND user_to_id = {$user_to_id}
      AND id > {$message_id}
      UNION 
      SELECT id, user_from_id, user_to_id, time, readen, message 
      FROM  `message` 
      WHERE user_from_id = {$user_to_id}
      AND user_to_id = {$user_from_id}
      AND id > {$message_id}
      ORDER BY TIME DESC
      LIMIT 0, {$count}
    ");
    if ($query->num_rows() > 0)
    {
      $results = array();
      foreach ($query->result() as $row)
      {
        $row->user_from_name = $this->user_model->get_user_name($row->user_from_id);
        $row->user_to_name = $this->user_model->get_user_name($row->user_to_id);
        $row->user_from_photo = $this->photo_model->get_user_photo($row->user_from_id);
        $row->user_to_photo = $this->photo_model->get_user_photo($row->user_to_id);
        $results[] = $row;
      }
      return array_reverse($results);
    }
    return null;
  }

  function get_last_message_readen($user_from_id, $user_to_id)
  {
    $query = $this->db->query("SELECT id FROM message WHERE user_from_id = {$user_from_id} AND user_to_id = {$user_to_id} AND readen = 1 ORDER BY id DESC LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->id;
    }
    return 1;
  }

  function delete_conversation($user_from_id, $user_to_id)
  {
    $this->db->trans_begin();

    $this->db->where('user_from_id', $user_from_id);
    $this->db->where('user_to_id', $user_to_id);
    $this->db->delete('message');

    $this->db->where('user_from_id', $user_to_id);
    $this->db->where('user_to_id', $user_from_id);
    $this->db->delete('message');

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception(lang('exception_error_1901'), 1901);
    }
    else
    {
      $this->db->trans_commit();
      return $user_to_id;
    }
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

/* End of file message_model.php */
/* Location: ./application/models/message_model.php */