<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Country_model extends CI_Model 
{
  protected $table = 'country';

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
    $query = $this->db->query("SELECT `country`.id, tag, name FROM `country` JOIN country_i18n ON ( country.id = country_i18n.country_id ) WHERE lang = '{$lang}' ORDER BY name");
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

  function get_country_name($country_id)
  {
    $lang = getActLang();
    $query = $this->db->query("SELECT name FROM `country_i18n` WHERE country_id = {$country_id} AND lang = '{$lang}'");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->name;
    }

    return null;
  }

  function get_country_tag($country_id)
  {
    $query = $this->db->query("SELECT tag FROM `country` WHERE id = {$country_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->tag;
    }

    return null;
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
    $this->db->where('id', $id);
    $result = $this->db->delete($this->table);
    if ($result)
    {
      return $result;
    }

    throw new Exception("Error al eliminar", 1);
  }
}

/* End of file country_model.php */
/* Location: ./application/models/country_model.php */