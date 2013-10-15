<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photo_model extends CI_Model 
{
	protected $photo_name = null;
  protected $photo_route = null;
  protected $photo_user_id = null;
  protected $photo_proposal_id = null;

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

  function all_from_user($user_id)
  {
  	$query = $this->db->query("SELECT id, name, route, thumbnail, user_id, main_for_user, route_main_for_user FROM `photo` WHERE user_id = {$user_id} ORDER BY id DESC");
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

  function count_all_from_user($user_id)
  {
    $query = $this->db->query("SELECT count(id) as num FROM `photo` WHERE user_id = {$user_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->num;
    }

    return 0;
  }

  function all_from_proposal($proposal_id)
  {
  	$query = $this->db->query("SELECT id, name, route, thumbnail, proposal_id FROM `photo` WHERE proposal_id = {$proposal_id} ORDER BY id DESC");
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

  function count_all_from_proposal($proposal_id)
  {
    $query = $this->db->query("SELECT count(id) as num FROM `photo` WHERE proposal_id = {$proposal_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->num;
    }

    return 0;
  }

  function add_for_user($user_id)
  {
    $this->photo_user_id = $user_id;

    $this->db->trans_begin();

    $query = $this->db->query("SELECT id, name FROM `photo` WHERE user_id = {$user_id} ORDER BY name DESC LIMIT 1");
    if ($query->num_rows() > 0)
    {
    	$row = $query->row();
    	$last_index_photo = ($row->name + 0) - (PHOTO_NAME_MULTIPLIER * $user_id) + 1;
    }
    else
    {
    	$last_index_photo = 0;
    }

    $photo_name = PHOTO_NAME_MULTIPLIER * $user_id;
    $photo_name += $last_index_photo;
    $this->photo_name = $photo_name;

  	$config['upload_path'] = BASEPATH . '../assets/photos/user/';
		$config['allowed_types'] = PHOTO_ALLOWED_TYPES;
		$config['max_size']	= PHOTO_MAX_SIZE;
		$config['max_width'] = PHOTO_MAX_WIDTH;
		$config['max_height'] = PHOTO_MAX_HEIGHT;
		$config['overwrite'] = PHOTO_OVERWRITE;
		$config['file_name'] = $this->photo_name;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload('upload'))
		{
			throw new Exception($this->upload->display_errors(), 1351);
		}
    else
    {
      $file_data = $this->upload->data();
      $extension = $file_data['file_ext'];
    }
   	
    $this->photo_route = "/assets/photos/user/{$photo_name}{$extension}";

    // Creating the Thumbnail

    $upload_width = $file_data['image_width'];
    $upload_height = $file_data['image_height'];

    if ($upload_width < $upload_height)
    {
      $crop_h = floor($upload_height/2) - floor($upload_width/2);
      $crop_w = 0;
      $new_h = $upload_width;
      $new_w = $upload_width;
    }
    else if ($upload_width > $upload_height)
    {
      $crop_h = 0;
      $crop_w = floor($upload_width/2) - floor($upload_height/2);
      $new_h = $upload_height;
      $new_w = $upload_height;
    }
    else // height = width
    {
      $crop_h = 0;
      $crop_w = 0;
      $new_h = $upload_height;
      $new_w = $upload_height;
    }

    $config_tmb['image_library'] = 'gd2';
    $config_tmb['source_image'] = BASEPATH . '..' . $this->photo_route;
    $config_tmb['create_thumb'] = TRUE;
    $config_tmb['x_axis'] = $crop_w;
    $config_tmb['y_axis'] = $crop_h;
    $config_tmb['width'] = $new_w;
    $config_tmb['height'] = $new_h;
    $config_tmb['maintain_ratio'] = FALSE;

    $this->load->library('image_lib', $config_tmb); 
    if ( ! $this->image_lib->crop())
    {
      throw new Exception($this->image_lib->display_errors(), 1351);
    }

    // Finish creating the Thumbnail

    // Creating the Main_for_User

    $upload_width = $file_data['image_width'];
    $upload_height = $file_data['image_height'];

    if ($upload_width < $upload_height)
    {
      if (($upload_width / $upload_height) > 0.75) // Conversion ratio for image 600x800
      {
        // Image is Height > Width
        $new_h = $upload_height;
        $new_w = floor($new_h * 0.75);
        $crop_w = floor(floor($upload_width - $new_w) / 2);
        $crop_h = 0;
      }
      elseif (($upload_width / $upload_height) < 0.75)
      {
        // Image is Width > Height
        $new_w = $upload_width;
        $new_h = floor($new_w * 1.33);
        $crop_w = 0;
        $crop_h = floor(floor($upload_height - $new_h) / 2);
      }
      else // Same Conversion ratio, image of 600x800 or similar
      {
        $new_w = $upload_width;
        $new_h = $upload_height;
        $crop_w = 0;
        $crop_h = 0;
      }
    }
    else if ($upload_width > $upload_height)
    {
      $new_w = floor(($upload_height * 100) / 133);
      $new_h = $upload_height;
      $crop_w = floor(floor($upload_width/2) - floor($upload_height/2)) * 1.33;
      $crop_h = 0;
    }
    else // height = width
    {
      $new_w = floor(($upload_height * 100) / 133);
      $new_h = $upload_height;
      $crop_w = floor($upload_width/2) - floor($upload_height/2);
      $crop_h = 0;
    }

    // exit();
 
    $config_mfu['image_library'] = 'gd2';
    $config_mfu['source_image'] = BASEPATH . '..' . $this->photo_route;
    $config_mfu['create_thumb'] = TRUE;
    $config_mfu['x_axis'] = $crop_w;
    $config_mfu['y_axis'] = $crop_h;
    $config_mfu['width'] = $new_w;
    $config_mfu['height'] = $new_h;
    $config_mfu['maintain_ratio'] = FALSE;
    $config_mfu['thumb_marker'] = "_main";

    $this->image_lib->initialize($config_mfu); 
    if ( ! $this->image_lib->crop())
    {
      throw new Exception($this->image_lib->display_errors(), 1351);
    }

    // Finish creating the Main_for_User

   	$photo = array();
    $photo['name'] = $this->photo_name;
    $photo['route'] = $this->photo_route;
    $photo['thumbnail'] = "/assets/photos/user/{$photo_name}_thumb{$extension}";
    $photo['user_id'] = $this->photo_user_id;
    $photo['proposal_id'] = $this->photo_proposal_id;
    $photo['route_main_for_user'] = "/assets/photos/user/{$photo_name}_main{$extension}";

    $this->db->insert('photo', $photo);
    
    $row = array();
    $query = $this->db->query("SELECT id, name, route, thumbnail, user_id, main_for_user, route_main_for_user FROM `photo` WHERE user_id = {$user_id} ORDER BY id DESC LIMIT 1");
    $row[] = $query->row();

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
      throw new Exception(lang('exception_error_1401'), 1401);
    }
    else
    {
      $this->db->trans_commit();
      return $row;
    }
  }

  function add_for_proposal($proposal_id)
  {
  	$this->photo_proposal_id = $proposal_id;

    $query = $this->db->query("SELECT id, name FROM `photo` WHERE proposal_id = {$proposal_id} ORDER BY name DESC LIMIT 1");
    if ($query->num_rows() > 0)
    {
    	$row = $query->row();
    	$last_index_photo = ($row->name + 0) - (PHOTO_NAME_MULTIPLIER * $proposal_id) + 1;
    }
    else
    {
    	$last_index_photo = 0;
    }

    $photo_name = PHOTO_NAME_MULTIPLIER * $proposal_id;
    $photo_name += $last_index_photo;
    $this->photo_name = $photo_name;

    $config['upload_path'] = BASEPATH . '../assets/photos/proposal/';
    $config['allowed_types'] = PHOTO_ALLOWED_TYPES;
    $config['max_size'] = PHOTO_MAX_SIZE;
    $config['max_width'] = PHOTO_MAX_WIDTH;
    $config['max_height'] = PHOTO_MAX_HEIGHT;
    $config['overwrite'] = PHOTO_OVERWRITE;
		$config['file_name'] = $this->photo_name;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload('upload'))
		{
			throw new Exception($this->upload->display_errors(), 1351);
		}
    else
    {
      $file_data = $this->upload->data();
      $extension = $file_data['file_ext'];
    }

   	$this->photo_route = "/assets/photos/proposal/{$photo_name}{$extension}";

    // Creating the Thumbnail

    $upload_width = $file_data['image_width'];
    $upload_height = $file_data['image_height'];

    if ($upload_width < $upload_height)
    {
      $crop_h = floor($upload_height/2) - floor($upload_width/2);
      $crop_w = 0;
      $new_h = $upload_width;
      $new_w = $upload_width;
    }
    else if ($upload_width > $upload_height)
    {
      $crop_h = 0;
      $crop_w = floor($upload_width/2) - floor($upload_height/2);
      $new_h = $upload_height;
      $new_w = $upload_height;
    }
    else // height = width
    {
      $crop_h = 0;
      $crop_w = 0;
      $new_h = $upload_height;
      $new_w = $upload_height;
    }
 
    $config_tmb['image_library'] = 'gd2';
    $config_tmb['source_image'] = BASEPATH . '..' . $this->photo_route;
    $config_tmb['create_thumb'] = TRUE;
    $config_tmb['x_axis'] = $crop_w;
    $config_tmb['y_axis'] = $crop_h;
    $config_tmb['width'] = $new_w;
    $config_tmb['height'] = $new_h;
    $config_tmb['maintain_ratio'] = FALSE;

    $this->load->library('image_lib', $config_tmb); 
    if ( ! $this->image_lib->crop())
    {
      throw new Exception($this->image_lib->display_errors(), 1351);
    }

    // Finish creating the Thumbnail

   	$photo = array();
    $photo['name'] = $this->photo_name;
    $photo['route'] = $this->photo_route;
    $photo['thumbnail'] = "/assets/photos/proposal/{$photo_name}_thumb{$extension}";
    $photo['user_id'] = $this->photo_user_id;
    $photo['proposal_id'] = $this->photo_proposal_id;

    $result = $this->db->insert('photo', $photo);
    if ($result)
    {
      $query = $this->db->query("SELECT id, name, route, thumbnail, proposal_id FROM `photo` WHERE proposal_id = {$proposal_id} ORDER BY id DESC LIMIT 1");
      $row = array();
      $row[] = $query->row();
      return $row;
    }

    throw new Exception(lang('exception_error_1401'), 1401);
  }

  function delete($photo_id)
  {
    $query = $this->db->query("SELECT route, thumbnail, route_main_for_user FROM `photo` WHERE id = {$photo_id}");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();

      $route = $row->route;
      $route = substr($route,1);
      if (file_exists(BASEPATH . '../' . $route))
      {
        unlink(BASEPATH . '../' . $route);
      }

      $thumbnail = $row->thumbnail;
      $thumbnail = substr($thumbnail,1);
      if (file_exists(BASEPATH . '../' . $thumbnail))
      {
        unlink(BASEPATH . '../' . $thumbnail);
      }

      $route_main_for_user = $row->route_main_for_user;
      if ($route_main_for_user !== null)
      {
        $route_main_for_user = substr($route_main_for_user,1);
        if (file_exists(BASEPATH . '../' . $route_main_for_user))
        {
          unlink(BASEPATH . '../' . $route_main_for_user);
        }
      }
    }

  	$this->db->where('id', $photo_id);
    $result = $this->db->delete('photo');
    if ($result)
    {
      return $photo_id;
    }

    throw new Exception(lang('exception_error_1402'), 1402);
  }

  function set_photo_as_main($user_id, $photo_id)
  {
    $query = $this->db->query("SELECT id FROM `photo` WHERE user_id = {$user_id} AND id = {$photo_id} LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $photo = array();
      
      $photo['main_for_user'] = 0;

      $this->db->where('user_id', $user_id);
      $this->db->where('main_for_user', 1);
      $result = $this->db->update('photo',$photo);

      $photo['main_for_user'] = 1;

      $this->db->where('id', $photo_id);
      $this->db->where('user_id', $user_id);
      $result = $this->db->update('photo',$photo);
      if ($result)
      {
        return $photo_id;
      }

      throw new Exception(lang('exception_error_1404'), 1404);
    }
    else
    {
      throw new Exception(lang('exception_error_1403'), 1403);
    }
  }

  function get_main_from_user($user_id)
  {
    $query = $this->db->query("SELECT id, name, route, user_id, main_for_user, route_main_for_user FROM `photo` WHERE user_id = {$user_id} AND main_for_user = 1 LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row;
    }

    $photo = new StdClass();
    $photo->id = "0";
    $photo->name = DEFAULT_NO_PHOTO_NAME;
    $photo->route = "/assets/photos/".DEFAULT_NO_PHOTO_NAME;
    $photo->route_main_for_user = "/assets/photos/".DEFAULT_NO_PHOTO_NAME;
    $photo->user_id = null;
    $photo->proposal_id = null;
    $photo->main_for_user = "0";

    return $photo;
  }

  function get_user_photo($user_id)
  {
    $query = $this->db->query("SELECT thumbnail FROM `photo` WHERE user_id = {$user_id} AND main_for_user = 1 LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->thumbnail;
    }
    return "/assets/photos/".DEFAULT_NO_PHOTO_NAME;
  }
}

/* End of file photo_model.php */
/* Location: ./application/models/photo_model.php */