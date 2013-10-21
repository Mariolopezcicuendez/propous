<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model 
{
  protected $table = 'user';
  protected $user_name = 'no name';
  protected $user_email = null;
  protected $user_old_password = null;
  protected $user_password = null;
  protected $user_re_password = null;
  protected $user_birthdate = null;
  protected $user_sex = 'M';
  protected $user_country_id = null;
  protected $user_state_id = null;
  protected $user_nationality = null;
  protected $user_description = null;
  protected $user_dwelling = null;
  protected $user_car = null;
  protected $user_sexuality = null;
  protected $user_children = null;
  protected $user_partner = null;
  protected $user_hobbies = null;
  protected $user_occupation = null;
  protected $user_phone = null;
  protected $user_activated = 0;
  protected $user_token = null;
  protected $user_remember_credentials = null;
  protected $user_login_tag = null;
  protected $user_comment = null;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->load->model('premium_model'); 
    $this->load->model('state_model'); 
    $this->load->model('captcha_model'); 
    $this->load->model('photo_model');
    $this->load->model('emailsend');
    $this->load->model('country_model');
    $this->load->model('notify_model');
    $this->load->helper('cookie');
  }

  function validate_id($id)
  {
    if (!is_numeric($id)) throw new Exception(lang('exception_error_1001'), 1001);
    if ($id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1001'), 1001);
  }

  function get($user_id)
  {
    $lang = getActLang();

    $query = $this->db->query("
      SELECT id, name, email, birthdate, sex, country_id, state_id, nationality, description, dwelling, car, sexuality, children, partner, hobbies, occupation, phone, activated 
      FROM `user` 
      WHERE id = {$user_id}
    ");

    if ($query->num_rows() > 0)
    {
      $row = $query->row();

      $row->online = $this->get_is_online($user_id);

      $id_row_prop = $row->id;
      $id_row_country = $row->country_id;
      $id_row_state = $row->state_id;

      // including user sociality
      $row->sociality = null;
      $query_sociality = $this->db->query("SELECT id, user_id, sociality_id, show_in_proposal FROM user_sociality WHERE user_id = {$id_row_prop} ORDER BY id");
      if ($query_sociality->num_rows() > 0)
      {
        $sociality = array();
        foreach ($query_sociality->result() as $row_sociality)
        {
          $sociality[] = $row_sociality->sociality_id;
        }
        $row->sociality = $sociality;
      }

      // including social photos
      $row->photos = null;
      $query_photos = $this->db->query("SELECT id, name, route, user_id, main_for_user FROM photo WHERE user_id = {$id_row_prop} ORDER BY id");
      if ($query_photos->num_rows() > 0)
      {
        $photos = array();
        foreach ($query_photos->result() as $row_photos)
        {
          $photos[] = $row_photos->id;
        }
        $row->photos = $photos;
      }

      // including user spoken languages
      $row->spoken = null;
      $query_spoken = $this->db->query("SELECT * FROM user_language WHERE user_id = {$id_row_prop} ORDER BY id");
      if ($query_spoken->num_rows() > 0)
      {
        $spoken = array();
        foreach ($query_spoken->result() as $row_spoken)
        {
          $spoken[] = $row_spoken->language_id;
        }
        $row->spoken = $spoken;
      }

      // including user premium type
      $row->premium = $this->premium_model->get($user_id);

      // including user named localization
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

  function have_18_years_old($birthdate)
  {
    list($BirthYear,$BirthMonth,$BirthDay) = explode("-",$birthdate);
    $stampBirth = mktime(0, 0, 0, $BirthMonth, $BirthDay, $BirthYear); 

    $today['day']   = date('d'); 
    $today['month'] = date('m'); 
    $today['year']  = date('Y') - 18; 

    $stampToday = mktime(0, 0, 0, $today['month'], $today['day'], $today['year']); 

    return ($stampBirth < $stampToday);
  }

  function validate_register()
  {
    if (strlen($this->user_name) < USER_NAME_MIN_SIZE) throw new Exception(lang('exception_error_1101'), 1101);
    if (strlen($this->user_name) > USER_NAME_MAX_SIZE) throw new Exception(lang('exception_error_1102'), 1102);
    if (!preg_match("/[A-Za-z0-9_\-. áéíóúÁÉÍÓÚüÜñÑ]{1,".USER_NAME_MAX_SIZE."}/", $this->user_name)) throw new Exception(lang('exception_error_1103'), 1103);
    
    if (!preg_match("/[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/", $this->user_email)) throw new Exception(lang('exception_error_1104'), 1104);

    if (strlen($this->user_password) < USER_PASSWORD_MIN_SIZE) throw new Exception(lang('exception_error_1105'), 1105);
    if (strlen($this->user_password) > USER_PASSWORD_MAX_SIZE) throw new Exception(lang('exception_error_1106'), 1106);
    if (!preg_match("/[A-Za-z0-9_\-.#@%&áéíóúÁÉÍÓÚüÜñÑ]{1,".USER_PASSWORD_MAX_SIZE."}/", $this->user_password)) throw new Exception(lang('exception_error_1107'), 1107);
    if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{1,".USER_PASSWORD_MAX_SIZE."}$/", $this->user_password)) throw new Exception(lang('exception_error_1107'), 1107);

    if ($this->user_password !== $this->user_re_password) throw new Exception(lang('exception_error_1108'), 1108);

    if (!preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $this->user_birthdate)) throw new Exception(lang('exception_error_1109'), 1109);
    if (!$this->have_18_years_old($this->user_birthdate)) throw new Exception(lang('exception_error_1110'), 1110);

    if ($this->user_sex !== 'M' && $this->user_sex !== 'F') throw new Exception(lang('exception_error_1111'), 1111);

    if (!is_numeric($this->user_country_id)) throw new Exception(lang('exception_error_1112'), 1112);
    if ($this->user_country_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1112'), 1112);

    if (!is_numeric($this->user_state_id)) throw new Exception(lang('exception_error_1113'), 1113);
    if ($this->user_state_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1113'), 1113);
  }

  function validate_save()
  {
    if (strlen($this->user_name) < USER_NAME_MIN_SIZE) throw new Exception(lang('exception_error_1101'), 1101);
    if (strlen($this->user_name) > USER_NAME_MAX_SIZE) throw new Exception(lang('exception_error_1102'), 1102);
    if (!preg_match("/[A-Za-z0-9_\-. áéíóúÁÉÍÓÚüÜñÑ]{1,".USER_NAME_MAX_SIZE."}/", $this->user_name)) throw new Exception(lang('exception_error_1103'), 1103);

    if (!preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $this->user_birthdate)) throw new Exception(lang('exception_error_1109'), 1109);
    if (!$this->have_18_years_old($this->user_birthdate)) throw new Exception(lang('exception_error_1110'), 1110);

    if ($this->user_sex !== 'M' && $this->user_sex !== 'F') throw new Exception(lang('exception_error_1111'), 1111);

    if (!is_numeric($this->user_country_id)) throw new Exception(lang('exception_error_1112'), 1112);
    if ($this->user_country_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1112'), 1112);

    if (!is_numeric($this->user_state_id)) throw new Exception(lang('exception_error_1113'), 1113);
    if ($this->user_state_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1113'), 1113);

    if (strlen($this->user_nationality) > USER_NATIONALITY_MAX_SIZE) throw new Exception(lang('exception_error_1115'), 1115);

    if (strlen($this->user_dwelling) > USER_DWELLING_MAX_SIZE) throw new Exception(lang('exception_error_1116'), 1116);

    if (strlen($this->user_car) > USER_CAR_MAX_SIZE) throw new Exception(lang('exception_error_1117'), 1117);

    if (strlen($this->user_sexuality) > USER_SEXUALITY_MAX_SIZE) throw new Exception(lang('exception_error_1118'), 1118);

    if (strlen($this->user_children) > USER_CHILDREN_MAX_SIZE) throw new Exception(lang('exception_error_1119'), 1119);

    if (strlen($this->user_partner) > USER_PARTNER_MAX_SIZE) throw new Exception(lang('exception_error_1120'), 1120);

    if (strlen($this->user_occupation) > USER_OCCUPATION_MAX_SIZE) throw new Exception(lang('exception_error_1121'), 1121);

    if (strlen($this->user_phone) > USER_PHONE_MAX_SIZE) throw new Exception(lang('exception_error_1122'), 1122);
  }

  function validate_change_password()
  {
    if (strlen($this->user_password) < USER_PASSWORD_MIN_SIZE) throw new Exception(lang('exception_error_1105'), 1105);
    if (strlen($this->user_password) > USER_PASSWORD_MAX_SIZE) throw new Exception(lang('exception_error_1106'), 1106);
    if (!preg_match("/[A-Za-z0-9_\-.#@%&áéíóúÁÉÍÓÚüÜñÑ]{1,".USER_PASSWORD_MAX_SIZE."}/", $this->user_password)) throw new Exception(lang('exception_error_1107'), 1107);
    if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{1,".USER_PASSWORD_MAX_SIZE."}$/", $this->user_password)) throw new Exception(lang('exception_error_1107'), 1107);

    if ($this->user_password !== $this->user_re_password) throw new Exception(lang('exception_error_1108'), 1108);
  }

  function check_exists_email($email)
  {
    $query = $this->db->query("SELECT id FROM `user` WHERE email = '{$email}'");
    if ($query->num_rows() > 0)
    {
      throw new Exception(lang('exception_error_1123'), 1123);
    }
  }

  function register()
  {
    $this->user_name = $this->input->post('user_name');
    $this->user_email = $this->input->post('user_email');
    $this->user_password = $this->input->post('user_password');
    $this->user_re_password = $this->input->post('user_re_password');
    $this->user_birthdate = $this->input->post('user_birthdate');
    $this->user_sex = $this->input->post('user_sex');
    $this->user_country_id = $this->input->post('user_country_id');
    $this->user_state_id = $this->input->post('user_state_id');

    $this->validate_register();
    $this->check_exists_email($this->user_email);

    $user = array();
    $user['name'] = $this->user_name;
    $user['email'] = $this->user_email;
    $user['password'] = sha1($this->user_password);
    $user['birthdate'] = $this->user_birthdate;
    $user['sex'] = $this->user_sex;
    $user['country_id'] = $this->user_country_id;
    $user['state_id'] = $this->user_state_id;
    $user['registerdate'] = date("Y-m-d H:i:s");
    $user['activated'] = $this->user_activated;

    $this->db->trans_begin();

    $this->db->insert('user', $user);

    $user_mod_actacc = array();
    $user_mod_actacc['activate_account_token'] = sha1("account_".$this->user_email);
    $this->user_token = $user_mod_actacc['activate_account_token'];

    $this->db->where('email', $this->user_email);
    $this->db->update('user', $user_mod_actacc);

    // $this->send_activating_link_account_email();

    $row = array();
    $query = $this->db->query("
      SELECT id, name, email, birthdate, sex, country_id, state_id, nationality, description, dwelling, car, sexuality, children, partner, hobbies, occupation, phone, activated 
      FROM `user` 
      ORDER BY id DESC 
      LIMIT 1
    ");
    $row[] = $query->row();

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception(lang('exception_error_1124'), 1124);
    }
    else
    {
      $this->db->trans_commit();
      return $row;
    }
  }

  function save($user_id)
  {
    $user = array();
    $this->user_name = $this->input->post('user_name');
    $this->user_birthdate = $this->input->post('user_birthdate');
    $this->user_sex = $this->input->post('user_sex');
    $this->user_country_id = $this->input->post('user_country_id');
    $this->user_state_id = $this->input->post('user_state_id');

    $this->user_nationality = $this->input->post('user_nationality');
    $this->user_description = $this->input->post('user_description');
    $this->user_dwelling = $this->input->post('user_dwelling');
    $this->user_car = $this->input->post('user_car');
    $this->user_sexuality = $this->input->post('user_sexuality');
    $this->user_children = $this->input->post('user_children');
    $this->user_partner = $this->input->post('user_partner');
    $this->user_hobbies = $this->input->post('user_hobbies');
    $this->user_occupation = $this->input->post('user_occupation');
    $this->user_phone = $this->input->post('user_phone');

    $this->validate_save();

    $query = $this->db->query("
      SELECT id, country_id, state_id 
      FROM `user` 
      WHERE id = {$user_id}
    ");
    $row = $query->row();
    $last_country = $row->country_id;
    $last_state = $row->state_id;

    $user['name'] = $this->user_name;
    $user['birthdate'] = $this->user_birthdate;
    $user['sex'] = $this->user_sex;
    $user['country_id'] = $this->user_country_id;
    $user['state_id'] = $this->user_state_id;

    $user['nationality'] = ($this->user_nationality !== null && $this->user_nationality != false && $this->user_nationality !== '') ? $this->user_nationality : null ;
    $user['description'] = ($this->user_description !== null && $this->user_description != false && $this->user_description !== '') ? $this->user_description : null ;
    $user['dwelling'] = ($this->user_dwelling !== null && $this->user_dwelling != false && $this->user_dwelling !== '') ? $this->user_dwelling : null ;
    $user['car'] = ($this->user_car !== null && $this->user_car != false && $this->user_car !== '') ? $this->user_car : null ;
    $user['sexuality'] = ($this->user_sexuality !== null && $this->user_sexuality != false && $this->user_sexuality !== '') ? $this->user_sexuality : null ;
    $user['children'] = ($this->user_children !== null && $this->user_children != false && $this->user_children !== '') ? $this->user_children : null ;
    $user['partner'] = ($this->user_partner !== null && $this->user_partner != false && $this->user_partner !== '') ? $this->user_partner : null ;
    $user['hobbies'] = ($this->user_hobbies !== null && $this->user_hobbies != false && $this->user_hobbies !== '') ? $this->user_hobbies : null ;
    $user['occupation'] = ($this->user_occupation !== null && $this->user_occupation != false && $this->user_occupation !== '') ? $this->user_occupation : null ;
    $user['phone'] = ($this->user_phone !== null && $this->user_phone != false && $this->user_phone !== '') ? $this->user_phone : null ;

    $this->db->trans_begin();

    $this->db->where('id', $user_id);
    $this->db->update('user', $user);

    $row = array();
    $query = $this->db->query("
      SELECT id, name, email, birthdate, sex, country_id, state_id, nationality, description, dwelling, car, sexuality, children, partner, hobbies, occupation, phone, activated 
      FROM `user` 
      WHERE id = {$user_id}
    ");
    $row_temp = $query->row();
    $row[] = $row_temp;

    // Si hay un cambio de localización se lo comunicamos al usuario para que desloguee
    $country = $row_temp->country_id;
    $state = $row_temp->state_id;

    if (($last_country . "_" . $last_state) !== ($country . "_" . $state))
    {
      $notify_text = lang("p_change_location_detected_please_logout");
      $this->notify_model->save($user_id,$notify_text);
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception(lang('exception_error_1125'), 1125);
    }
    else
    {
      $this->db->trans_commit();
      return $row;
    }
  }  

  function delete($user_id)
  {
    $this->user_old_password = $this->input->post('user_password');

    $captcha_word = $this->input->post('captcha_word');
    $ip = $this->input->ip_address();
    $session_id = $this->session->userdata('session_id');

    $this->check_old_password($user_id);

    $this->captcha_model->captcha_exist($captcha_word, $ip, $session_id);
    
    $this->db->where('id', $user_id);
    $result = $this->db->delete('user');
    if ($result)
    {
      return $result;
    }

    throw new Exception(lang('exception_error_1126'), 1126);
  }  

  function active_account_linkpressed($token)
  {
    $query = $this->db->query("SELECT id FROM `user` WHERE activate_account_token = '{$token}' AND activated = 0 LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $user['activated'] = 1;
      $user['activate_account_token'] = null;

      $this->db->where('activate_account_token', $token);
      $result = $this->db->update('user', $user);
      if ($result)
      {
        return true;
      }
      else
      {
        throw new Exception(lang('exception_error_1127'), 1127);
      }
    }
    else
    {
      throw new Exception(lang('exception_error_1132'), 1132);
    }
  }  

  function change_password($user_id)
  {
    $this->user_old_password = $this->input->post('user_old_password');
    $this->user_password = $this->input->post('user_password');
    $this->user_re_password = $this->input->post('user_re_password');

    $captcha_word = $this->input->post('captcha_word');
    $ip = $this->input->ip_address();
    $session_id = $this->session->userdata('session_id');

    $this->validate_change_password();
    $this->check_old_password($user_id);
    
    $this->captcha_model->captcha_exist($captcha_word, $ip, $session_id);

    $user = array();
    $user['password'] = sha1($this->user_password);

    $this->db->trans_begin();

    $this->db->where('id', $user_id);
    $this->db->update('user', $user);

    $query = $this->db->query("
      SELECT id, name, email, birthdate, sex, country_id, state_id, nationality, description, dwelling, car, sexuality, children, partner, hobbies, occupation, phone, activated 
      FROM `user` 
      WHERE id = {$user_id}
    ");

    $this->db->trans_complete();

    $row[] = $query->row();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception(lang('exception_error_1128'), 1128);
    }
    else
    {
      $this->db->trans_commit();
      return $row;
    }
  } 

  function check_token()
  {
    if (!preg_match("/[A-Za-z0-9]{40}/", $this->user_token)) throw new Exception(lang('exception_error_1133'), 1133);

    $query = $this->db->query("SELECT id FROM `user` WHERE change_password_token = '{$this->user_token}' AND activated = 1 LIMIT 1");
    if ($query->num_rows() > 0)
    {
      return;
    }

    throw new Exception(lang('exception_error_1134'), 1134);
  }

  function change_password_token()
  {
    $this->user_token = $this->input->post('user_token');
    $this->user_password = $this->input->post('user_password');
    $this->user_re_password = $this->input->post('user_re_password');

    $captcha_word = $this->input->post('captcha_word');
    $ip = $this->input->ip_address();
    $session_id = $this->session->userdata('session_id');

    $this->validate_change_password();
    $this->check_token();
    
    $this->captcha_model->captcha_exist($captcha_word, $ip, $session_id);

    $user = array();
    $user['password'] = sha1($this->user_password);
    $user['change_password_token'] = null;

    $this->db->where('change_password_token', $this->user_token);
    $result = $this->db->update('user', $user);
    if ($result)
    {
      return true;
    }

    throw new Exception(lang('exception_error_1128'), 1128);
  }

  function check_old_password($user_id)
  {
    $old_password = sha1($this->user_old_password);
    
    $query = $this->db->query("SELECT password FROM `user` WHERE id = {$user_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      if ($row->password === $old_password) return;
    }

    throw new Exception(lang('exception_error_1129'), 1129);
  }

  function send_activating_link_account_email()
  {
    $email = new StdClass();
    $email->from = EMAIL_ADDRESS;
    $email->frominfo = EMAIL_ADDRESS_INFO;
    $email->to = $this->user_email;
    $email->subject = 'Activation account Propous';
    $email->message = "Hello {$this->user_name}.<br/>";
    $email->message .= "Activation link: " . $this->config->item('base_url') . getActLang()."/plink/activeaccount/{$this->user_token}";

    $this->emailsend->send($email);
  }

  function validate_login()
  {
    if (!preg_match("/[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/", $this->user_email)) throw new Exception(lang('exception_error_1104'), 1104);
  }

  function logged()
  {
    return (FALSE !== $this->session->userdata('logged_in'));
  }

  function login()
  {
    $this->user_email = $this->input->post('user_email');
    $this->user_password = $this->input->post('user_password');
    $this->user_remember_credentials = $this->input->post('user_remember');

    // $this->validate_login();

    // Check if user Banned
    $query = $this->db->query("SELECT reason_tag FROM banneds WHERE email = '{$this->user_email}' ORDER BY id LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      $reason = $row->reason_tag;
      throw new Exception(lang('banned_reason_'.$reason), 1137);
    }

    $query = $this->db->query("
      SELECT id, name, email, birthdate, sex, country_id, state_id, nationality, description, dwelling, car, sexuality, children, partner, hobbies, occupation, phone, activated, rol
      FROM `user` 
      WHERE email = '{$this->user_email}' AND password = '" . sha1($this->user_password) . "' AND activated = 1 
      LIMIT 1
    ");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();

      $sess_array = array(
       'id' => $row->id,
       'email' => $row->email,
       'name' => $row->name,
       'premium' => null,
       'country_id' => $row->country_id,
       'state_id' => $row->state_id,
       'state_name' => $this->state_model->get_state_name($row->state_id),
       'premium' => $this->premium_model->get($row->id),
       'rol' => $row->rol
      );
      $this->session->set_userdata('logged_in', $sess_array);

      // Miramos si quiere recordar usuario y contraseña para generar la cookie
      if ($this->user_remember_credentials === "true")
      {
        $rand = sha1(time());
        $user = array();
        $user['login_cookie_tag'] = $rand;

        $this->db->where('email', $this->user_email);
        $result = $this->db->update('user', $user);

        $this->generate_cookies($rand);
      }
      else
      {
        $user = array();
        $user['login_cookie_tag'] = null;

        $this->db->where('email', $this->user_email);
        $result = $this->db->update('user', $user);
      }

      return $row;
    }
    else
    {
      throw new Exception(lang('exception_error_1130'), 1130);
    }
  }

  function login_cookie()
  {
    $this->user_email = $_COOKIE['user_email'];
    $this->user_login_tag = $_COOKIE['user_login_tag'];

    $query = $this->db->query("
      SELECT id, name, email, birthdate, sex, country_id, state_id, nationality, description, dwelling, car, sexuality, children, partner, hobbies, occupation, phone, activated , rol
      FROM `user` 
      WHERE email = '{$this->user_email}' AND login_cookie_tag = '{$this->user_login_tag}' AND activated = 1 
      LIMIT 1
    ");

    if ($query->num_rows() > 0)
    {
      $row = $query->row();

      $sess_array = array(
       'id' => $row->id,
       'email' => $row->email,
       'name' => $row->name,
       'premium' => null,
       'country_id' => $row->country_id,
       'state_id' => $row->state_id,
       'state_name' => $this->state_model->get_state_name($row->state_id),
       'premium' => $this->premium_model->get($row->id),
       'rol' => $row->rol
      );
      $this->session->set_userdata('logged_in', $sess_array);

      // Miramos si quiere recordar usuario y contraseña para generar la cookie
      if ($this->user_remember_credentials)
      {
        $rand = sha1(time());
        $user = array();
        $user['login_cookie_tag'] = $rand;

        $this->db->where('email', $this->user_email);
        $result = $this->db->update('user', $user);

        $this->generate_cookies($rand);
      }

      redirect('/' . getActLang() . '/prop');
    }
    else
    {
      throw new Exception(lang('exception_error_1130'), 1130);
    }    
  }

  function generate_cookies($rand)
  {
    $cookie = array(
        'name'   => "user_email",
        'value'  => $this->user_email,
        'expire' => time()+(60*60*24*COOKIE_DAYS_EXPIRATION)
    );
    $this->input->set_cookie($cookie);

    $cookie = array(
        'name'   => "user_login_tag",
        'value'  => $rand,
        'expire' => time()+(60*60*24*COOKIE_DAYS_EXPIRATION)
    );
    $this->input->set_cookie($cookie);
  }

  function logout()
  {
    $this->session->unset_userdata('logged_in');
    $this->session->sess_destroy();
    delete_cookie("user_email");
    delete_cookie("user_login_tag");
    return true;
  }

  function validate_forgetpassword()
  {
    if (!preg_match("/[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/", $this->user_email)) throw new Exception(lang('exception_error_1104'), 1104);
  }

  function send_activating_link_forget_password()
  {
    $email = new StdClass();
    $email->from = EMAIL_ADDRESS;
    $email->frominfo = EMAIL_ADDRESS_INFO;
    $email->to = $this->user_email;
    $email->subject = 'Forget password Propous';
    $email->message = "Press " . $this->config->item('base_url') . getActLang()."/plink/forgetpassword/{$this->user_token} to Propous new password";

    $this->emailsend->send($email);
  }

  function forgetpassword()
  {
    $this->user_email = $this->input->post('user_email');

    $captcha_word = $this->input->post('captcha_word');
    $ip = $this->input->ip_address();
    $session_id = $this->session->userdata('session_id');

    $this->validate_forgetpassword();

    $this->captcha_model->captcha_exist($captcha_word, $ip, $session_id);

    $query = $this->db->query("SELECT id, email FROM `user` WHERE email = '{$this->user_email}' AND activated = 1 LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();

      $user = array();
      $user['change_password_token'] = sha1("forget_".$row->email);
      $this->user_token = $user['change_password_token'];

      $this->db->where('id', $row->id);
      $result = $this->db->update('user', $user);
      if ($result)
      {
        // $this->send_activating_link_forget_password();
        return true;
      } 
      throw new Exception(lang('exception_error_1131'), 1131);
    }
    else
    {
      throw new Exception(lang('exception_error_1130'), 1130);
    }
  }

  function forgetpassword_linkpressed($token)
  {
    $query = $this->db->query("SELECT id FROM `user` WHERE change_password_token = '{$token}' AND activated = 1 LIMIT 1");
    if ($query->num_rows() > 0)
    {
      return true;
    }
    else
    {
      throw new Exception(lang('exception_error_1134'), 1134);
    }
  }

  function get_total_users()
  {
    $sql = "
      SELECT COUNT( * ) AS num
      FROM user
      WHERE activated = 1 
    ";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->num;
    }

    return 0;
  }

  function get_total_users_state($country_id, $state_id)
  {
    $sql = "
      SELECT COUNT( * ) AS num
      FROM user
      WHERE activated = 1 
      AND country_id = {$country_id} 
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

  function get_total_users_state_online($country_id, $state_id)
  {
    $sql = "
      SELECT COUNT( * ) AS num
      FROM ci_sessions
      WHERE user_data <>  ''
      AND user_data REGEXP '(.)*country_id\";.:.:\"{$country_id}(.)*' 
      AND user_data REGEXP '(.)*state_id\";.:.:\"{$state_id}(.)*'
    ";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->num;
    }

    return 0;
  }

  function get_is_online($user_id)
  {
    $sql = "
      SELECT COUNT( * ) AS num
      FROM ci_sessions
      WHERE user_data <>  ''
      AND user_data REGEXP '(.)*\"id\";.:.:\"{$user_id}(.)*' 
    ";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      if ($row->num > 0)
      {
        return true;
      }
    }

    return false;
  }

  function validate_contact()
  {
    if (strlen($this->user_name) < USER_NAME_MIN_SIZE) throw new Exception(lang('exception_error_1101'), 1101);
    if (strlen($this->user_name) > USER_NAME_MAX_SIZE) throw new Exception(lang('exception_error_1102'), 1102);

    if (!preg_match("/[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/", $this->user_email)) throw new Exception(lang('exception_error_1104'), 1104);

    if (!is_numeric($this->user_country_id)) throw new Exception(lang('exception_error_1112'), 1112);
    if ($this->user_country_id < VALIDATE_ID_MIN_VALUE) throw new Exception(lang('exception_error_1112'), 1112);

    if (strlen($this->user_comment) < USER_CONTACT_MESSAGE_MIN_SIZE) throw new Exception(lang('exception_error_1136'), 1136);
  }

  function contact()
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

  function get_user_name($user_id)
  {
    $query = $this->db->query("SELECT name FROM `{$this->table}` WHERE id = {$user_id} LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->name;
    }
    return "no name";
  }

  function cms_get_user_rol($user_id)
  {
    $query = $this->db->query("SELECT rol FROM `{$this->table}` WHERE id = {$user_id} LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->rol;
    }
    return "";
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
        $row->photos = $this->photo_model->count_all_from_user($id);
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
      $row->photos = $this->photo_model->all_from_user($id);
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

    $sql_data['hour']['show'] = " HOUR( registerdate ) AS hora,";
    $sql_data['hour']['join'] = "";
    $sql_data['hour']['group'] = " , hora";
    $sql_data['hour']['order'] = " , hora";

    $sql_data['sex']['show'] = " sex AS idg, 'sexo' as idg_tag,";
    $sql_data['sex']['join'] = "";
    $sql_data['sex']['group'] = " , sex";
    $sql_data['sex']['order'] = " , groupd DESC";

    $sql_data['country']['show'] = " country_id AS idg, tag as idg_tag, tag,";
    $sql_data['country']['join'] = "LEFT JOIN country ON ( country.id = {$this->table}.country_id ) ";
    $sql_data['country']['group'] = " , country_id";
    $sql_data['country']['order'] = " , groupd DESC";

    $sql_data['city']['show'] = " state_id AS idg, name as idg_tag, name,";
    $sql_data['city']['join'] = "LEFT JOIN state ON ( state.id = {$this->table}.state_id ) ";
    $sql_data['city']['group'] = " , state_id";
    $sql_data['city']['order'] = " , groupd DESC";

    $sql_data['activated']['show'] = " activated AS idg, 'activado' as idg_tag,";
    $sql_data['activated']['join'] = "";
    $sql_data['activated']['group'] = " , activated";
    $sql_data['activated']['order'] = " , groupd DESC";

    $sql_data['birthdate']['show'] = " YEAR(birthdate) AS idg, 'Año nac.' as idg_tag,";
    $sql_data['birthdate']['join'] = "";
    $sql_data['birthdate']['group'] = " , YEAR(birthdate)";
    $sql_data['birthdate']['order'] = " , groupd DESC";

    $sql = "
      SELECT YEAR( registerdate ) AS anyo, MONTH( registerdate ) AS mes, DAY( registerdate ) AS dia,{$sql_data[$agrupation]['show']} COUNT( {$this->table}.id ) AS groupd
      FROM {$this->table} {$sql_data[$agrupation]['join']}
      WHERE DATE_SUB( NOW( ) , INTERVAL {$time} DAY ) <= registerdate
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
    $sql_data['hour']['and_agrupation'] = " AND HOUR(registerdate) = {$idg} ";

    $sql_data['sex']['join'] = "";
    $sql_data['sex']['and_agrupation'] = " AND sex = '{$idg}' ";

    $sql_data['country']['join'] = "";
    $sql_data['country']['and_agrupation'] = " AND country_id =  {$idg} ";

    $sql_data['city']['join'] = "";
    $sql_data['city']['and_agrupation'] = " AND state_id = {$idg} ";

    $sql_data['activated']['join'] = "";
    $sql_data['activated']['and_agrupation'] = " AND activated = {$idg} ";

    $sql_data['birthdate']['join'] = "";
    $sql_data['birthdate']['and_agrupation'] = " AND YEAR(birthdate) = {$idg} ";

    $sql_day_or_total = ($day !== '') ? " AND DAY( registerdate ) = {$day} " : "" ;
    $sql_agrupation_or_total = ($idg !== '' && $idg !== 'null') ? " {$sql_data[$agrupation]['and_agrupation']} " : "" ;

    $sql = "
      SELECT user.id, name, registerdate as time
      FROM user
      {$sql_data[$agrupation]['join']} 
      WHERE YEAR( registerdate ) = {$year}
      AND MONTH( registerdate ) = {$month}
      $sql_day_or_total
      $sql_agrupation_or_total
      ORDER BY registerdate
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

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */