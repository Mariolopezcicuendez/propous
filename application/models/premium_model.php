<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Premium_model extends CI_Model 
{
  protected $table = 'premium';
	protected $user_id = null;
	protected $type_id = null;
  protected $force_premium = null;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->load->model('notify_model');
  }

  function validate_id($id)
  {
    if (!is_numeric($id)) throw new Exception(lang('exception_error_1001'), 1001);
    if ($id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1001'), 1001);
  }  

  function validate_premium()
  {
  	if (!is_numeric($this->user_id)) throw new Exception(lang('exception_error_1001'), 1001);
    if ($this->user_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1001'), 1001);

    $premium_model = array(
    	"premium",
    	"silver", 
    	"gold", 
    	"diamond"
   	);

    if (!in_array($this->type_id, $premium_model)) throw new Exception(lang('exception_error_1501'), 1501);
  }

  function add()
	{
		$this->user_id = $this->input->post('user_id');
		$time = date("Y-m-d H:i:s");
		$this->type_id = $this->input->post('type');
    $this->force_premium = $this->input->post('force');

    if (($this->force_premium === null) || ($this->force_premium === ''))
    {
      $this->force_premium = false;
    }
    else if (($this->force_premium === 1) || ($this->force_premium === '1') || ($this->force_premium === 'true'))
    {
      $this->force_premium = true;
    }

		$this->validate_premium();

    if (!$this->force_premium)
    {
      if ($this->get($this->user_id) !== null)
      {
        throw new Exception(lang('exception_error_1502'), 1502);
      }
    }

    $this->db->trans_begin();

    $premium = array();
		$premium['user_id'] = $this->user_id;
		$premium['time'] = $time;
		$premium['type'] = $this->type_id;
		$this->db->insert('premium', $premium);

    $notify_text = lang("p_change_premium_detected_please_logout");
    $this->notify_model->save($this->user_id,$notify_text);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception(lang('exception_error_1503'), 1503);
    }
    else
    {
      $this->db->trans_commit();
      return $premium['type'];
    }
	}

	function get($user_id)
	{
		$premium_type = null;

		// Comprobamos si el usuario es diamond
		$query = $this->db->query("
			SELECT id, type 
			FROM premium
			WHERE user_id = {$user_id}
			AND DATE_SUB(NOW() , INTERVAL ". (PREMIUM_PREMIUM_DIAMOND_DURATION * 24 * 60 * 60) ." SECOND) <= time
			ORDER BY time DESC
		");
		if ($query->num_rows() > 0)
    {
    	foreach ($query->result() as $row)
    	{
    		if ($row->type === 'diamond') return 'diamond';
    	}
    }

		// Comprobamos si el usuario es premium, silver o gold
		$query = $this->db->query("
			SELECT id, type 
			FROM premium
			WHERE user_id = {$user_id}
			AND DATE_SUB(NOW() , INTERVAL ". (PREMIUM_PREMIUM_DURATION * 24 * 60 * 60) ." SECOND) <= time
			ORDER BY time DESC
		");
		if ($query->num_rows() > 0)
    {
    	foreach ($query->result() as $row)
    	{
    		if ($row->type === 'gold') return 'gold';
    		if ($row->type === 'silver') $premium_type = 'silver';
    		if (($row->type === 'premium') && ($premium_type === null)) $premium_type = 'premium';
    	}
    }

    return $premium_type;
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

    $sql_data['type']['show'] = " type AS idg, type as idg_tag,";
    $sql_data['type']['join'] = "";
    $sql_data['type']['group'] = " , type";
    $sql_data['type']['order'] = " , groupd DESC";

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

    $sql_data['type']['join'] = "";
    $sql_data['type']['and_agrupation'] = " AND type =  '{$idg}' ";

    $sql_day_or_total = ($day !== '') ? " AND DAY( TIME ) = {$day} " : "" ;
    $sql_agrupation_or_total = ($idg !== '' && $idg !== 'null') ? " {$sql_data[$agrupation]['and_agrupation']} " : "" ;

    $sql = "
      SELECT premium.id, type, time
      FROM premium
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

/* End of file premium_model.php */
/* Location: ./application/models/premium_model.php */