<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sociality_model extends CI_Model 
{
  protected $table = 'sociality';

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

  function all()
  {
    $lang = getActLang();
    $query = $this->db->query("SELECT `sociality`.id, tag, name FROM `sociality` JOIN sociality_i18n ON ( sociality.id = sociality_i18n.sociality_id ) WHERE lang = '{$lang}' ORDER BY name");
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

  function get_social($user_id)
  {
    $lang = getActLang();
    $query = $this->db->query("
      SELECT user_sociality.id, user_sociality.user_id, user_sociality.sociality_id, name, tag, show_in_proposal
      FROM  `user_sociality` 
      JOIN sociality_i18n ON ( user_sociality.sociality_id = sociality_i18n.sociality_id ) 
      JOIN sociality ON ( sociality.id = user_sociality.sociality_id ) 
      WHERE user_id = {$user_id}
      AND lang = '{$lang}'
      ORDER BY user_sociality.id
    ");
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

  function save_social($user_id)
  {
    $query = $this->db->query("SELECT id FROM `user_sociality` WHERE user_id = {$user_id}");
    if ($query->num_rows() >= MAX_SOCIALITIES_IN_USER)
    {
      throw new Exception(lang('exception_error_1703'), 1703);
    }

  	$sociality_id = $this->input->post('user_sociality_id');

		$query = $this->db->query("SELECT id FROM `user_sociality` WHERE user_id = {$user_id} AND sociality_id = {$sociality_id}");
    if ($query->num_rows() == 0)
    {
      $this->db->trans_begin();

    	$user_sociality = array();
			$user_sociality['user_id'] = $user_id;
			$user_sociality['sociality_id'] = $sociality_id;

			$this->db->insert('user_sociality', $user_sociality);

      $this->db->trans_complete();

      if ($this->db->trans_status() === FALSE)
      {
        $this->db->trans_rollback();
        throw new Exception(lang('exception_error_1704'), 1704);
      }
      else
      {
        $this->db->trans_commit();
        return true;
      }
    }
    else
    {
      return true;
    }
  }

  function delete_social($social_id)
  {
  	$this->db->where('id', $social_id);
    $result = $this->db->delete('user_sociality');
    if ($result)
    {
      return $result;
    }

    throw new Exception(lang('exception_error_1701'), 1701);
  }

  function show_in_proposal($user_id, $sociality_id)
  {
    $sociality_clean = array();
    $sociality_clean['show_in_proposal'] = 0;

    $this->db->trans_begin();

    $this->db->where('user_id', $user_id);
    $this->db->update('user_sociality', $sociality_clean);

    $sociality = array();
    $sociality['show_in_proposal'] = 1;

    $this->db->where('user_id', $user_id);
    $this->db->where('sociality_id', $sociality_id);
    $this->db->update('user_sociality', $sociality);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception(lang('exception_error_1702'), 1702);
    }
    else
    {
      $this->db->trans_commit();
      return true;
    }
  }

  function cms_all()
  {
    $query = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
    if ($query->num_rows() > 0)
    {
      $langs_alias = $this->config->item('language_alias');

      $results = array();
      foreach ($query->result() as $row)
      {
        $row_id = $row->id;

        foreach ($langs_alias as $key => $value)
        {
          $query_i18n = $this->db->query("SELECT * FROM `{$this->table}_i18n` where {$this->table}_id = {$row_id} AND lang = '{$key}' ORDER BY id DESC");
          if ($query_i18n->num_rows() > 0)
          {
            $row_i18n = $query_i18n->row();
            
            $row_name = "I18n_{$key}_id";
            $row->{$row_name} = $row_i18n->id;

            $row_name = "I18n_{$key}_name";
            $row->{$row_name} = $row_i18n->name;
          }
        }

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
      $langs_alias = $this->config->item('language_alias');

      $row = $query->row();
      $row_id = $row->id;

      foreach ($langs_alias as $key => $value)
      {
        $query_i18n = $this->db->query("SELECT * FROM `{$this->table}_i18n` where {$this->table}_id = {$row_id} AND lang = '{$key}' ORDER BY id DESC");
        if ($query_i18n->num_rows() > 0)
        {
          $row_i18n = $query_i18n->row();

          $row_name = "I18n_{$key}_id";
          $row->{$row_name} = $row_i18n->id;

          $row_name = "I18n_{$key}_name";
          $row->{$row_name} = $row_i18n->name;
        }
      }

      return $row;
    }

    return null;
  }

  function cms_save($id = null)
  {
    $data = $this->input->post();
    $data_save = $this->input->post();

    foreach ($data as $key => $value) 
    {
      if (($value === '') || (substr($key, 0, 5) === 'I18n_'))
      {
        unset($data[$key]);
      }
    }

    $this->db->trans_begin();

    if ((!isset($id)) ||($id === '') || ($id === null))
    {
      $result = $this->db->insert($this->table, $data);

      $query = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT 1");
      $row = $query->row();
      $data['id'] = $row->id;
    }
    else
    {
      $this->db->where('id', $id);
      $this->db->update($this->table, $data);
    }

    $data_i18n = array();

    foreach ($data_save as $key => $value) 
    {
      if (substr($key, 0, 5) === 'I18n_')
      {
        $a_lang = substr($key, 5, 2);

        if (!isset($data_i18n[$a_lang])) $data_i18n[$a_lang] = array();

        if (substr($key, 5) === "{$a_lang}_id")
        {
          $data_i18n[$a_lang]['id'] = $value;
        }

        $data_i18n[$a_lang]["{$this->table}_id"] = $data['id'];

        if (substr($key, 5) === "{$a_lang}_name")
        {
          $data_i18n[$a_lang]['name'] = $value;
        }

        $data_i18n[$a_lang]['lang'] = $a_lang;

        unset($data_save[$key]);
      }

      if ($value === '')
      {
        unset($data_save[$key]);
      }
    }

    if ((!isset($id)) ||($id === '') || ($id === null))
    {
      $query = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT 1");

      $row = $query->row();
      $row_id = $row->id;

      foreach ($data_i18n as $key => $value) 
      {
        $value["{$this->table}_id"] = $row_id;
        $this->db->insert("{$this->table}_i18n", $value);
      }
    }
    else
    {
      $new_id = $data['id'];

      foreach ($data_i18n as $key => $value) 
      {
        $this->db->where("{$this->table}_id", $new_id);
        $this->db->where("lang", $key);
        $this->db->update("{$this->table}_i18n", $value);
      }
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
  }

  function cms_delete($id)
  {
    $query = $this->db->query("SELECT id, tag FROM `{$this->table}` WHERE id = {$id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      $icon_name = substr(strtolower($row->tag),1);
      if (file_exists(BASEPATH . '../assets/icons/'.$this->table.'/'.$icon_name.".png"))
      {
        unlink(BASEPATH . '../assets/icons/'.$this->table.'/'.$icon_name.".png");
      }
    }

    $this->db->where('id', $id);
    $result = $this->db->delete($this->table);
    if ($result)
    {
      return $result;
    }

    throw new Exception("Error al eliminar", 1);
  }

  function cms_save_icon($id)
  {
    $query = $this->db->query("SELECT id, tag FROM `{$this->table}` WHERE id = {$id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      $icon_name = substr(strtolower($row->tag),1);
    }
    else
    {
      return;
    }

    if (file_exists(BASEPATH . '../assets/icons/'.$this->table.'/'.$icon_name.".png"))
    {
      unlink(BASEPATH . '../assets/icons/'.$this->table.'/'.$icon_name.".png");
    }

    $config['upload_path'] = BASEPATH . '../assets/icons/'.$this->table.'/';
    $config['allowed_types'] = PHOTO_ALLOWED_TYPES;
    $config['max_size']  = PHOTO_MAX_SIZE;
    $config['max_width'] = PHOTO_MAX_WIDTH;
    $config['max_height'] = PHOTO_MAX_HEIGHT;
    $config['overwrite'] = PHOTO_OVERWRITE;
    $config['file_name'] = $icon_name;
    $this->load->library('upload', $config);
    $this->upload->initialize($config);

    if ( ! $this->upload->do_upload('upload'))
    {
      throw new Exception($this->upload->display_errors(), 1);
    }
  }
}

/* End of file sociality_model.php */
/* Location: ./application/models/sociality_model.php */