<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message_model extends CI_Model 
{
  protected $table = 'message';
  protected $user_id = null;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->load->model('user_model'); 
    $this->load->model('photo_model'); 
  }

  function validate_id($id)
  {
    if (!is_numeric($id)) throw new Exception(lang('exception_error_1001'), 1001);
    if ($id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1001'), 1001);
  }


  function actualize_messages_info()
  {
    $this->user_id = $this->input->post('user_from_id');
    $user_to_id = $this->input->post('user_to_id');
    $users_list_count = $this->input->post('users_list_count');
    $messages_list_count = $this->input->post('messages_list_count');
    $message = $this->input->post('user_message');
    $writing = $this->input->post('writing');
    $delete_user_conversation = $this->input->post('delete_user_conversation');    

    $writing = (($writing === true) || ($writing === 'true') || ($writing === 1) || ($writing === '1')) ? 1 : 0 ;
    if (!isset($users_list_count) || ($users_list_count === null) || ($users_list_count === '') || ($users_list_count === 'null') || ($users_list_count == false)) $users_list_count = DEFAULT_NUMBER_USERS_MESSAGED_SHOWED;
    if (!isset($messages_list_count) || ($messages_list_count === null) || ($messages_list_count === '') || ($messages_list_count === 'null') || ($messages_list_count == false)) $messages_list_count = DEFAULT_NUMBER_MESSAGES_SHOWED;

    if (isset($message) && ($message !== null) && ($message !== '') && ($message !== 'null') && ($message != false))
    {
      $this->send_message($user_to_id,$message);
    }

    if (isset($delete_user_conversation) && ($delete_user_conversation !== null) && ($delete_user_conversation !== '') && ($delete_user_conversation !== 'null') && ($delete_user_conversation != false))
    {
      $this->delete_conversation($this->user_id,$delete_user_conversation);
      if ($delete_user_conversation === $user_to_id) $user_to_id = null;
    }

    $data = array();
    $data['users_list'] = $this->get_users_list_with_messages($users_list_count);

    if (isset($user_to_id) && ($user_to_id !== null) && ($user_to_id !== '') && ($user_to_id !== 'null') && ($user_to_id != false))
    {
      $this->set_writing($this->user_id,$user_to_id,$writing);
      $this->set_all_message_readen($user_to_id,$this->user_id);
      $data['no_readen_messages_from_user'] = $this->get_no_readen_messages_from_user($user_to_id);
      $data['user_chating_now'] = new StdClass();
      $data['user_chating_now']->id = $user_to_id;
      $data['user_chating_now']->name = $this->user_model->get_user_name($user_to_id);
      $data['user_chating_now']->no_readen = $this->get_no_readen_messages_from_user($user_to_id);
      $data['user_chating_now']->photo = $this->photo_model->get_user_photo($user_to_id);
      $data['user_chating_now']->connected = $this->user_model->get_is_online($user_to_id);
      $data['user_chating_now']->writing = $this->check_user_writing($user_to_id,$this->user_id);
      if (!in_array($data['user_chating_now'],$data['users_list']))
      {
        $data['users_list'][] = $data['user_chating_now'];
      }
      $data['user_chating_now_conversation'] = $this->get_conversation($this->user_id,$user_to_id,$messages_list_count);
    }
    $data['no_readen_messages'] = $this->get_no_readen_messages();

    return $data;
  }

  function get_no_readen_messages()
  {
    $query = $this->db->query("SELECT id FROM message WHERE user_to_id = {$this->user_id} AND readen = 0");
    if ($query->num_rows() > 0)
    {
      return $query->num_rows();
    }
    return 0;
  }

  function get_no_readen_messages_from_user($user_to_id)
  {
    $query = $this->db->query("SELECT id FROM message WHERE user_from_id = {$user_to_id} AND user_to_id = {$this->user_id} AND readen = 0");
    if ($query->num_rows() > 0)
    {
      return $query->num_rows();
    }
    return 0;
  }

  function get_users_list_with_messages($users_list_count)
  {
    $query = $this->db->query("
      SELECT DISTINCT(user_to_id) as user_talked, time
      FROM `message` 
      WHERE user_from_id = {$this->user_id}
      GROUP BY user_to_id 
      UNION
      SELECT DISTINCT(user_from_id) as user_talked, time
      FROM `message` 
      WHERE user_to_id = {$this->user_id}
      GROUP BY user_from_id 
      ORDER BY time DESC
      LIMIT 0 , {$users_list_count}
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
          $user_talked->name = $this->user_model->get_user_name($user_talked->id);
          $user_talked->no_readen = $this->get_no_readen_messages_from_user($user_talked->id);
          $user_talked->photo = $this->photo_model->get_user_photo($user_talked->id);
          $user_talked->connected = $this->user_model->get_is_online($user_talked->id);
          $user_talked->writing = $this->check_user_writing($user_talked->id,$this->user_id);

          $results[] = $user_talked;
          $results_ids[] = $row->user_talked;
        }
      }
      return $results;
    }
    return array();
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

  function get_conversation($user_from_id, $user_to_id, $count)
  {
    $query = $this->db->query("
      SELECT id, user_from_id, user_to_id, time, readen, message
      FROM  `message` 
      WHERE user_from_id = {$user_from_id}
      AND user_to_id = {$user_to_id}
      UNION 
      SELECT id, user_from_id, user_to_id, time, readen, message 
      FROM  `message` 
      WHERE user_from_id = {$user_to_id}
      AND user_to_id = {$user_from_id}
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

  function send_message($user_to_id, $message_text)
  {
    $this->db->trans_begin();

    $message = array();
    $message['user_from_id'] = $this->user_id;
    $message['user_to_id'] = $user_to_id;
    $message['time'] = date("Y-m-d H:i:s");
    $message['readen'] = 0;
    $message['message'] = $message_text;

    $this->db->insert('message', $message);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception("Error sending message", 1000);
    }
    else
    {
      $this->db->trans_commit();
    }
  }

  function set_all_message_readen($user_from_id, $user_to_id)
  {
    $message['readen'] = 1;

    $this->db->where('user_from_id', $user_from_id);
    $this->db->where('user_to_id', $user_to_id);
    $result = $this->db->update('message', $message);

    return $result;
  }

  function set_writing($user_from_id, $user_to_id, $writing)
  {
    $query = $this->db->query("SELECT writing FROM message_writing WHERE user_from_id = {$user_from_id} AND user_to_id = {$user_to_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();

      if ($row->writing !== "".$writing)
      {
        $message_writing = array();
        $message_writing['writing'] = $writing;

        $this->db->where('user_from_id', $user_from_id);
        $this->db->where('user_to_id', $user_to_id);
        $this->db->update('message_writing', $message_writing);
      }
    }
    else
    {
      $message_writing = array();
      $message_writing['user_from_id'] = $user_from_id;
      $message_writing['user_to_id'] = $user_to_id;
      $message_writing['writing'] = $writing;

      $this->db->insert('message_writing', $message_writing);
    }
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