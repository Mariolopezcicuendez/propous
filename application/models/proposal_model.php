<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proposal_model extends CI_Model 
{
  protected $table = 'proposal';
  protected $proposal_proposal = null;
  protected $proposal_time = null;
  protected $proposal_user_id = null;
  protected $proposal_visibility = null;
  protected $proposal_description = null;
  protected $proposal_visited = 0;
  protected $proposal_country_id = null;
  protected $proposal_state_id = null;

  protected $search_showed = null;
  protected $search_from_id = null;
  protected $search_to_id = null;
  protected $search_user_id = null;
  protected $search_user_not_id = null;
  protected $search_categories = null;
  protected $search_search = null;
  protected $search_from_date = null;
  protected $search_to_date = null;
  protected $search_country_id = null;
  protected $search_state_id = null;
  protected $search_visibility = null;
  protected $search_moderated_invalid = null;

  protected $search_favorites = null;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->load->model('premium_model');
    $this->load->model('country_model');
    $this->load->model('photo_model');
    $this->load->model('state_model');
    $this->load->model('user_model');
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

  function validate_proposal()
  {
    if (strlen($this->proposal_proposal) < PROPOSAL_NAME_MIN_SIZE) throw new Exception(lang('exception_error_1601'), 1601);
    if (strlen($this->proposal_proposal) > PROPOSAL_NAME_MAX_SIZE) throw new Exception(lang('exception_error_1602'), 1602);

    if (!is_numeric($this->proposal_user_id)) throw new Exception(lang('exception_error_1603'), 1603);
    if ($this->proposal_user_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1603'), 1603);

    if (!is_numeric($this->proposal_country_id)) throw new Exception(lang('exception_error_1604'), 1604);
    if ($this->proposal_country_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1604'), 1604);

    if (!is_numeric($this->proposal_state_id)) throw new Exception(lang('exception_error_1605'), 1605);
    if ($this->proposal_state_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1605'), 1605);
  }

  function create()
  {
    $this->proposal_user_id = $this->input->post('proposal_user_id');

    $this->check_user_can_create_proposal();

    $this->proposal_proposal = $this->input->post('proposal_text');
    $this->proposal_description = $this->input->post('proposal_description');

    $this->proposal_country_id = $this->input->post('proposal_country_id');
    $this->proposal_state_id = $this->input->post('proposal_state_id');

    $this->proposal_visibility = $this->input->post('proposal_visibility');

    $this->validate_proposal();

    $proposal = array();
    $proposal['proposal'] = $this->proposal_proposal;
    $proposal['time'] = date("Y-m-d H:i:s");
    $proposal['user_id'] = $this->proposal_user_id;
    $proposal['visibility'] = $this->proposal_visibility;
    $proposal['description'] = ($this->proposal_description !== null && $this->proposal_description != false && $this->proposal_description !== '') ? $this->proposal_description : null ;
    $proposal['visited'] = 0;
    $proposal['country_id'] = $this->proposal_country_id;
    $proposal['state_id'] = $this->proposal_state_id;

    $this->db->trans_begin();

    $this->db->insert('proposal', $proposal);

    $row = array();
    $query = $this->db->query("SELECT id, proposal, time, user_id, visibility, description, visited, country_id, state_id FROM `proposal` ORDER BY id DESC LIMIT 1");
    $row[] = $query->row();

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception(lang('exception_error_1607'), 1607);
    }
    else
    {
      $this->db->trans_commit();
      return $row;
    }
  }

  function read($proposal_id)
  {
    $lang = getActLang();
    
    $query = $this->db->query("
      SELECT id, proposal, time, user_id, description, visited, country_id, state_id, visibility, moderated_invalid, DATE_SUB( NOW( ) , INTERVAL ".PROPOSAL_SECONDS_TO_MAKE_EDITABLE." SECOND ) > TIME AS editable
      FROM `proposal` 
      WHERE id = {$proposal_id}
    ");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      $id_row_country = $row->country_id;
      $id_row_state = $row->state_id;

      // including proposal named localization
      $row->country = null;
      $query_country = $this->db->query("SELECT name FROM country_i18n WHERE country_id = {$id_row_country} AND lang = '{$lang}'");
      if ($query_country->num_rows() > 0)
      {
        $country = array();
        foreach ($query_country->result() as $row_country)
        {
          $country[] = $row_country;
        }
        $row->country = $country;
      }
      $row->state = null;
      $query_state = $this->db->query("SELECT name FROM state WHERE id = {$id_row_state}");
      if ($query_state->num_rows() > 0)
      {
        $state = array();
        foreach ($query_state->result() as $row_state)
        {
          $state[] = $row_state;
        }
        $row->state = $state;
      }

      $row->country_tag = $this->country_model->get_country_tag($id_row_country);

      return $row;
    }

    return array();
  }

  function check_if_proposal_moderated_invalid($proposal_id)
  {
    $query = $this->db->query("SELECT moderated_invalid FROM proposal WHERE id = {$proposal_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      $moderated_invalid = $row->moderated_invalid;

      if ($moderated_invalid == '1') throw new Exception(lang('exception_error_1623'), 1623);
    }
  }

  function update($proposal_id)
  {
    $this->check_if_proposal_moderated_invalid($proposal_id);

    $this->proposal_proposal = $this->input->post('proposal_text');
    $this->proposal_user_id = $this->input->post('proposal_user_id');
    $this->proposal_description = $this->input->post('proposal_description');
    $this->proposal_country_id = $this->input->post('proposal_country_id');
    $this->proposal_state_id = $this->input->post('proposal_state_id');
    $this->proposal_visibility = $this->input->post('proposal_visibility');

    $this->check_if_new_proposal($proposal_id);
    $this->validate_proposal();

    $proposal = array();
    $proposal['proposal'] = $this->proposal_proposal;
    $proposal['user_id'] = $this->proposal_user_id;
    $proposal['description'] = ($this->proposal_description !== null && $this->proposal_description != false && $this->proposal_description !== '') ? $this->proposal_description : null ;
    $proposal['country_id'] = $this->proposal_country_id;
    $proposal['state_id'] = $this->proposal_state_id;
    $proposal['visibility'] = $this->proposal_visibility;

    $this->db->trans_begin();

    $this->db->where('id', $proposal_id);
    $this->db->update('proposal', $proposal);

    $row = array();
    $query = $this->db->query("SELECT id, proposal, time, user_id, visibility, description, visited, country_id, state_id FROM `proposal` WHERE id = {$proposal_id}");
    $row[] = $query->row();

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception(lang('exception_error_1608'), 1608);
    }
    else
    {
      $this->db->trans_commit();
      return $row;
    }
  }

  function check_if_new_proposal($proposal_id)
  {
    $query = $this->db->query("
      SELECT id, proposal, time, user_id, visited, country_id, state_id 
      FROM `proposal` 
      WHERE id = {$proposal_id} 
      AND DATE_SUB(NOW( ) , INTERVAL ".PROPOSAL_SECONDS_TO_MAKE_EDITABLE." SECOND) <= time
    ");
    if ($query->num_rows() > 0) // Proposal no editable
    {
      $row = $query->row();
      $proposal = $row->proposal;
      $country_id = $row->country_id;
      $state_id = $row->state_id;

      if ($proposal !== $this->proposal_proposal) throw new Exception(lang('exception_error_1624'), 1624);
      if ($country_id !== $this->proposal_country_id) throw new Exception(lang('exception_error_1625'), 1625);
      if ($state_id !== $this->proposal_state_id) throw new Exception(lang('exception_error_1625'), 1625);
    }
  }

  function delete($proposal_id)
  {
    $this->db->where('id', $proposal_id);
    $result = $this->db->delete('proposal');
    if ($result)
    {
      return $result;
    }

    throw new Exception(lang('exception_error_1609'), 1609);
  }

  function set_proposal_visibility($proposal_id)
  {
    $this->proposal_visibility = $this->input->post('proposal_visibility');

    if (!is_numeric($this->proposal_visibility)) throw new Exception(lang('exception_error_1610'), 1610);
    if (($this->proposal_visibility < 0) || ($this->proposal_visibility > 1)) throw new Exception(lang('exception_error_1610'), 1610);

    $proposal = array();
    $proposal['visibility'] = $this->proposal_visibility;

    $this->db->where('id', $proposal_id);
    $result = $this->db->update('proposal', $proposal);
    if ($result)
    {
      $query = $this->db->query("SELECT id, proposal, time, user_id, visibility, description, visited, country_id, state_id FROM `proposal` WHERE id = {$proposal_id}");
      $row[] = $query->row();
      return $row;
    }

    throw new Exception(lang('exception_error_1611'), 1611);
  }

  function add_one_visited($proposal_id)
  {
    $visited = 0;
    $query = $this->db->query("SELECT visited FROM `proposal` WHERE id = {$proposal_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      $visited = $row->visited;
    }

    $proposal = array();
    $proposal['visited'] = $visited + 1;

    $this->db->where('id', $proposal_id);
    $result = $this->db->update('proposal', $proposal);
    if ($result)
    {
      $row = $query->row();
      return $row;
    }

    throw new Exception(lang('exception_error_1612'), 1612);
  }

  function validate_search()
  {
    if (($this->search_showed !== null) && ($this->search_showed !== false) && ($this->search_showed !== ''))
    {
      if (!is_numeric($this->search_showed)) throw new Exception(lang('exception_error_1613'), 1613);
      if ($this->search_showed < 0) throw new Exception(lang('exception_error_1613'), 1613);
    }
    
    if (($this->search_to_id !== null) && ($this->search_to_id !== false) && ($this->search_to_id !== ''))
    {
      if (!is_numeric($this->search_to_id)) throw new Exception(lang('exception_error_1614'), 1614);
      if ($this->search_to_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1614'), 1614);
    }

    if (($this->search_country_id !== null) && ($this->search_country_id !== false) && ($this->search_country_id !== ''))
    {
      if (!is_numeric($this->search_country_id)) throw new Exception(lang('exception_error_1615'), 1615);
      if ($this->search_country_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1615'), 1615);
    }

    if (($this->search_state_id !== null) && ($this->search_state_id !== false) && ($this->search_state_id !== ''))
    {
      if (!is_numeric($this->search_state_id)) throw new Exception(lang('exception_error_1616'), 1616);
      if ($this->search_state_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1616'), 1616);
    }

    if (($this->search_user_id !== null) && ($this->search_user_id !== false) && ($this->search_user_id !== ''))
    {
      if (!is_numeric($this->search_user_id)) throw new Exception(lang('exception_error_1618'), 1618);
      if ($this->search_user_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1618'), 1618);
    }

    if (($this->search_categories !== null) && ($this->search_categories !== false) && ($this->search_categories !== ''))
    {
      if (!preg_match("/[0-9]+(:[0-9]+)*/", $this->search_categories)) throw new Exception(lang('exception_error_1619'), 1619);
    }

    if (($this->search_from_date !== null) && ($this->search_from_date !== false) && ($this->search_from_date !== ''))
    {
      if (!preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $this->search_from_date)) throw new Exception(lang('exception_error_1620'), 1620);
    }

    if (($this->search_to_date !== null) && ($this->search_to_date !== false) && ($this->search_to_date !== ''))
    {
      if (!preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $this->search_to_date)) throw new Exception(lang('exception_error_1621'), 1621);
    }
  }

  function list_proposals()
  {
    $lang = getActLang();

    $this->search_favorites = $this->input->post('favorites');
    $this->search_visibility = $this->input->post('visibility');

    $this->search_user_not_id = $this->input->post('user_not_id');

    $this->search_showed = $this->input->post('showed');
    $this->search_from_id = $this->input->post('from_id');
    $this->search_to_id = $this->input->post('to_id');
    $this->search_user_id = $this->input->post('user_id');
    $this->search_categories = $this->input->post('categories');
    $this->search_search = $this->input->post('search');
    $this->search_from_date = $this->input->post('from_date');
    $this->search_to_date = $this->input->post('to_date');
    $this->search_country_id = $this->input->post('country_id');
    $this->search_state_id = $this->input->post('state_id');
    $this->search_moderated_invalid = $this->input->post('moderated_invalid');

    if (($this->search_from_id === null) || ($this->search_from_id === false) || ($this->search_from_id === ''))
    {
      $this->search_from_id = 1;
    }

    $moderated_invalid = " AND (moderated_invalid = 0)";
    if (($this->search_moderated_invalid !== null) && ($this->search_moderated_invalid !== false) && ($this->search_moderated_invalid !== ''))
    {
      if ($this->search_moderated_invalid === 'all')
      {
        $moderated_invalid = "";
      }
      else
      {
        $moderated_invalid = " AND (moderated_invalid = {$this->search_moderated_invalid})";
      }
    }

    $visibility = " AND (visibility = 1)";
    if (($this->search_visibility !== null) && ($this->search_visibility !== false) && ($this->search_visibility !== ''))
    {
      if ($this->search_visibility === 'all')
      {
        $visibility = "";
      }
      else
      {
        $visibility = " AND (visibility = {$this->search_visibility})";
      }
    }

    $favorites_join = "";
    $favorites_user_select = "";
    if (($this->search_favorites !== null) && ($this->search_favorites !== false) && ($this->search_favorites !== ''))
    {
      $favorites_join = " JOIN favorite ON (favorite.proposal_id = proposal.id) ";
      $favorites_user_select = " AND favorite.user_id = {$this->search_favorites} ";
    }

    $this->validate_search();

    $conditions = "WHERE (`proposal`.id >= {$this->search_from_id}){$visibility}{$moderated_invalid} ";

    if (($this->search_country_id !== null) && ($this->search_country_id !== false) && ($this->search_country_id !== ''))
    {
      $conditions .= "AND (country_id = {$this->search_country_id}) ";
    }
    if (($this->search_state_id !== null) && ($this->search_state_id !== false) && ($this->search_state_id !== ''))
    {
      $conditions .= "AND (state_id = {$this->search_state_id}) ";
    }

    if (($this->search_user_id !== null) && ($this->search_user_id !== false) && ($this->search_user_id !== '')) 
    {
      $conditions .= "AND (user_id = {$this->search_user_id}) ";
    }
    else if (($this->search_user_not_id !== null) && ($this->search_user_not_id !== false) && ($this->search_user_not_id !== '')) 
    {
      $conditions .= "AND (user_id <> {$this->search_user_not_id}) ";
    }

    if (($this->search_to_id !== null) && ($this->search_to_id !== false) && ($this->search_to_id !== '')) $conditions .= "AND (`proposal`.id <= {$this->search_to_id}) ";
    if (($this->search_search !== null) && ($this->search_search !== false) && ($this->search_search !== '')) $conditions .= "AND ((proposal like '%{$this->search_search}%') OR (description like '%{$this->search_search}%')) ";
    if (($this->search_from_date !== null) && ($this->search_from_date !== false) && ($this->search_from_date !== '')) $conditions .= "AND (time >= '{$this->search_from_date}') ";
    if (($this->search_to_date !== null) && ($this->search_to_date !== false) && ($this->search_to_date !== '')) $conditions .= "AND (time <= '{$this->search_to_date}') ";
    
    $limits = (($this->search_showed !== null) && ($this->search_showed !== false) && ($this->search_showed !== '')) ? "LIMIT {$this->search_showed}" : "";

    $sql = "
      SELECT `proposal`.id, `proposal`.proposal, `proposal`.time, `proposal`.user_id, `proposal`.visibility, `proposal`.description, `proposal`.visited, `proposal`.country_id, `proposal`.state_id , `proposal`.moderated_invalid
      FROM `proposal` 
      {$favorites_join}
      {$conditions}
      {$favorites_user_select}
      ORDER BY TIME DESC 
      {$limits}
    ";

    if (($this->search_categories !== null) && ($this->search_categories !== false) && ($this->search_categories !== ''))
    {
      $categories_arr = explode(":",$this->search_categories);
      if (count($categories_arr) > 0)
      {
        $conditions .= "AND (";
        foreach ($categories_arr as $category_id) 
        {
          $conditions .= "(category_id = {$category_id}) OR ";
        }
        $conditions = substr($conditions, 0, -4) . ") ";

        $sql = "
          SELECT DISTINCT (`proposal`.id), `proposal`.proposal, `proposal`.time, `proposal`.user_id, `proposal`.visibility, `proposal`.description, `proposal`.visited, `proposal`.country_id, `proposal`.state_id
          FROM `proposal` 
          JOIN proposal_category ON (`proposal`.id = proposal_category.proposal_id) 
          {$favorites_join}
          {$conditions}
          {$favorites_user_select}
          ORDER BY TIME DESC 
          {$limits}
        ";
      }
    }

    $query = $this->db->query($sql);
    // echo $this->db->last_query();
    if ($query->num_rows() > 0)
    {
      $results = array();
      foreach ($query->result() as $row)
      {
        $id_row_prop = $row->id;
        $id_row_user = $row->user_id;
        $id_row_country = $row->country_id;
        $id_row_state = $row->state_id;

        // including localization info
        $row->country_tag = $this->country_model->get_country_tag($id_row_country);
        $row->country = $this->country_model->get_country_name($id_row_country);
        $row->state = $this->state_model->get_state_name($id_row_state);

        // including proposal categories
        $row->categories = null;
        $query_categories = $this->db->query("SELECT proposal_category.category_id, tag, name FROM proposal_category JOIN category ON (category.id = proposal_category.category_id) JOIN category_i18n ON (category_i18n.category_id = proposal_category.category_id) WHERE proposal_category.proposal_id = {$id_row_prop} AND lang = '{$lang}' ORDER BY proposal_category.id");
        if ($query_categories->num_rows() > 0)
        {
          $categories = array();
          foreach ($query_categories->result() as $row_categories)
          {
            $categories[] = $row_categories;
          }
          $row->categories = $categories;
        }

        // including proposal photos
        $row->photos = null;
        $query_photos = $this->db->query("SELECT id, name, route, proposal_id FROM photo WHERE proposal_id = {$id_row_prop} ORDER BY id");
        if ($query_photos->num_rows() > 0)
        {
          $photos = array();
          foreach ($query_photos->result() as $row_photos)
          {
            $photos[] = $row_photos->id;
          }
          $row->photos = $photos;
        }

        // including user info
        $row->user = null;
        $query_user = $this->db->query("SELECT name, id, sex FROM user WHERE id = {$id_row_user}");
        if ($query_user->num_rows() > 0)
        {
          $user = array();
          foreach ($query_user->result() as $row_user)
          {
            $user['id'] = $row_user->id;
            $user['sex'] = $row_user->sex;
            $user['name'] = $row_user->name;
            $user['connected'] = $this->user_model->get_is_online($user['id']);
          }
          $row->user = $user;
        }

        // including user info sociality show_in_proposal
        $query_social = $this->db->query("SELECT user_sociality.sociality_id, tag, name FROM user_sociality JOIN sociality ON (sociality.id = user_sociality.sociality_id) JOIN sociality_i18n ON (sociality_i18n.sociality_id = user_sociality.sociality_id) WHERE user_id = {$id_row_user} AND lang = '{$lang}' AND show_in_proposal = 1");
        if ($query_social->num_rows() > 0)
        {
          foreach ($query_social->result() as $row_social)
          {
            $row->user['show_in_proposal'] = $row_social;
          }
        }

        $results[] = $row;
      }

      return $results;
    }

    return array();
  }

  function check_user_can_create_proposal()
  {
    $user_type_premium = $this->premium_model->get($this->proposal_user_id);

    $query = $this->db->query("
      SELECT id 
      FROM proposal
      WHERE user_id = {$this->proposal_user_id}
      AND DATE_SUB(NOW( ) , INTERVAL ".PROPOSAL_SECONDS_TIME_TO_MAKE_FREE_PROPOSALS." SECOND) <= time
      ORDER BY time DESC
    ");
    $proposals_today = $query->num_rows();

    switch ($user_type_premium) 
    {
      case 'diamond':
          $proposals_admited = PREMIUM_PREMIUM_DIAMOND_PROPOSALS;
        break;
      case 'gold':
          $proposals_admited = PREMIUM_PREMIUM_GOLD_PROPOSALS;
        break;
      case 'silver':
          $proposals_admited = PREMIUM_PREMIUM_SILVER_PROPOSALS;
        break;
      case 'premium':
          $proposals_admited = PREMIUM_PREMIUM_PROPOSALS;
        break;      
      default:
          $proposals_admited = PREMIUM_FREE_PROPOSALS;
        break;
    }

    if ($proposals_today >= $proposals_admited) throw new Exception(lang('exception_error_1622'), 1622);
  }

  function get_total_props()
  {
    $sql = "
      SELECT COUNT( * ) AS num
      FROM proposal
    ";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->num;
    }

    return 0;
  }

  function get_total_props_state($country_id, $state_id)
  {
    $sql = "
      SELECT COUNT( * ) AS num
      FROM proposal
      WHERE country_id = {$country_id} 
      AND state_id = {$state_id}
    ";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->num;
    }

    return 0;
  }

  function get_total_props_state_today($country_id, $state_id)
  {
    $sql = "
      SELECT COUNT( * ) AS num
      FROM proposal
      WHERE country_id = {$country_id} 
      AND state_id = {$state_id}
      AND DATE_SUB(NOW() , INTERVAL 86400 SECOND) <= time
    ";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->num;
    }

    return 0;
  }

  function get_total_from_user($user_id)
  {
    $sql = "
      SELECT COUNT( * ) AS num
      FROM proposal
      WHERE user_id = {$user_id}
    ";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->num;
    }

    return 0;
  }

  function cms_all()
  {
    $query = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
    if ($query->num_rows() > 0)
    {
      $results = array();
      foreach ($query->result() as $row)
      {
        $id = $row->id;
        $row->photos = $this->photo_model->count_all_from_proposal($id);
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
      $id = $row->id;
      $row->photos = $this->photo_model->all_from_proposal($id);
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

  function analitycs_stats()
  {
    $agrupation = $this->input->post('agrupation');
    $time = $this->input->post('time');

    $sql_data = array();

    $sql_data['day']['show'] = "";
    $sql_data['day']['join'] = "";
    $sql_data['day']['group'] = "";
    $sql_data['day']['order'] = "";

    $sql_data['hour']['show'] = " HOUR( TIME ) AS hora,";
    $sql_data['hour']['join'] = "";
    $sql_data['hour']['group'] = " , hora";
    $sql_data['hour']['order'] = " , hora";

    $sql_data['user']['show'] = " user_id AS idg, 'usuario' as idg_tag,";
    $sql_data['user']['join'] = "";
    $sql_data['user']['group'] = " , user_id";
    $sql_data['user']['order'] = " , groupd DESC";

    $sql_data['country']['show'] = " country_id AS idg, tag as idg_tag, tag,";
    $sql_data['country']['join'] = "LEFT JOIN country ON ( country.id = {$this->table}.country_id ) ";
    $sql_data['country']['group'] = " , country_id";
    $sql_data['country']['order'] = " , groupd DESC";

    $sql_data['city']['show'] = " state_id AS idg, name as idg_tag, name,";
    $sql_data['city']['join'] = "LEFT JOIN state ON ( state.id = {$this->table}.state_id ) ";
    $sql_data['city']['group'] = " , state_id";
    $sql_data['city']['order'] = " , groupd DESC";

    $sql_data['category']['show'] = " category_id AS idg, tag as idg_tag, tag,";
    $sql_data['category']['join'] = "LEFT JOIN proposal_category ON ( {$this->table}.id = proposal_category.proposal_id ) LEFT JOIN category ON ( proposal_category.category_id = category.id ) ";
    $sql_data['category']['group'] = " , category_id";
    $sql_data['category']['order'] = " , groupd DESC";

    $sql = "
      SELECT YEAR( TIME ) AS anyo, MONTH( TIME ) AS mes, DAY( TIME ) AS dia,{$sql_data[$agrupation]['show']} COUNT( {$this->table}.id ) AS groupd
      FROM {$this->table} {$sql_data[$agrupation]['join']}
      WHERE DATE_SUB( NOW( ) , INTERVAL {$time} DAY ) <= TIME
      GROUP BY anyo DESC , mes DESC , dia DESC {$sql_data[$agrupation]['group']} 
      ORDER BY anyo DESC , mes DESC , dia DESC {$sql_data[$agrupation]['order']} 
    ";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0)
    {
      $results = array();
      foreach ($query->result() as $row)
      {
        $results[] = $row;
      }

      $results["actual_day"] = date("d");
      $results["actual_month"] = date("m");
      $results["actual_year"] = date("Y");

      $fecha = date_create("{$results["actual_year"]}-{$results["actual_month"]}-{$results["actual_day"]}");
      date_sub($fecha, date_interval_create_from_date_string("{$time} days"));
      list($results["from_year"],$results["from_month"],$results["from_day"]) = explode("-",date_format($fecha, 'Y-m-d'));

      return $results;
    }
    return array();
  }

  function analitycs_detail()
  {
    $agrupation = $this->input->post('agrupation');
    $idg = $this->input->post('idg');
    $year = $this->input->post('year');
    $month = $this->input->post('month');
    $day = $this->input->post('day');

    $sql_data = array();

    $sql_data['day']['join'] = "";
    $sql_data['day']['and_agrupation'] = "";

    $sql_data['hour']['join'] = "";
    $sql_data['hour']['and_agrupation'] = " AND HOUR(TIME) = {$idg} ";

    $sql_data['user']['join'] = "";
    $sql_data['user']['and_agrupation'] = " AND user_id = {$idg} ";

    $sql_data['country']['join'] = "";
    $sql_data['country']['and_agrupation'] = " AND country_id =  {$idg} ";

    $sql_data['city']['join'] = "";
    $sql_data['city']['and_agrupation'] = " AND state_id = {$idg} ";

    $sql_data['category']['join'] = "LEFT JOIN proposal_category ON ( {$this->table}.id = proposal_category.proposal_id ) ";
    $sql_data['category']['and_agrupation'] = " AND category_id = {$idg} ";

    $sql_day_or_total = ($day !== '') ? " AND DAY( TIME ) = {$day} " : "" ;
    $sql_agrupation_or_total = ($idg !== '' && $idg !== 'null') ? " {$sql_data[$agrupation]['and_agrupation']} " : "" ;

    $sql = "
      SELECT proposal.id, proposal, time
      FROM proposal
      {$sql_data[$agrupation]['join']} 
      WHERE YEAR( TIME ) = {$year}
      AND MONTH( TIME ) = {$month}
      $sql_day_or_total
      $sql_agrupation_or_total
      ORDER BY TIME
    ";
    $query = $this->db->query($sql);
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
}

/* End of file proposal_model.php */
/* Location: ./application/models/proposal_models.php */