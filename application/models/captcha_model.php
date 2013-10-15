<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Captcha_model extends CI_Model
{
  private $tabla_captcha = 'captcha';

  function __construct()
  {
    parent::__construct();
  }

  function get_captcha()
  {
    // Creacion del Captcha
    $this->load->helper('captcha');

    $vals = array(
      'img_path'   => $this->config->item('file_base_url') . 'captcha/',
      'img_url'    => $this->config->item('base_url') . 'captcha/',
      'font_path'  => $this->config->item('file_base_url') . 'assets/fonts/captcha.ttf',
      'font_size'  => CAPTCHA_FONT_SIZE,
      'img_width'  => CAPTCHA_IMAGE_WIDTH,
      'img_height' => CAPTCHA_IMAGE_HEIGHT,
      'expiration' => CAPTCHA_SECONDS_EXPIRATION_TIME
    );

    $cap = create_captcha($vals);

    // se agrega el captcha a la base de datos
    $captcha_info = array (
      'captcha_time' => $cap['time'],
      'ip_address' => $this->input->ip_address(),
      'session_id' => $this->session->userdata('session_id'),
      'word' => $cap['word']
    );

    $this->insert_captcha($captcha_info);

    return $cap;
  }

  function insert_captcha($data)
  {
    $query = $this->db->insert_string($this->tabla_captcha, $data);
    $this->db->query($query);
  }

  function delete_captcha($expiration)
  {
    $this->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);
  }

  function captcha_exist($word, $ip, $session_id)
  {
    $word = strtoupper($word);

    $expiration = time()-CAPTCHA_SECONDS_EXPIRATION_TIME;
    $this->delete_captcha($expiration);

    $sql = "SELECT COUNT(*) AS count FROM ".$this->tabla_captcha." WHERE UPPER(word) = '{$word}' AND ip_address = '{$ip}' AND session_id = '{$session_id}' AND captcha_time > {$expiration}";
    $query = $this->db->query($sql);
    $row = $query->row();

    if ($row->count == 0)
    {
      throw new Exception(lang('exception_error_1801'), 1801);
    }
    else
    {
      return true;
    }
  }
}