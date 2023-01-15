<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); header('Content-type: text/html; charset=utf-8');

class oauth_model extends CI_Model {
  
  private $edit;
  public function __construct() {
    parent::__construct();    
    $CI =& get_instance();
    $this->edit = $this->load->database('edit', TRUE, TRUE);
    // $this->load->library('tppymemcached');
  }
  

  public function set_token($user_id, $user_uuid, $scope, $app_id, $date_expire) {
    if(is_array($scope)) {
      asort($scope, SORT_REGULAR);
    }

    
    $query = $this->db->query("SELECT idx FROM users_token WHERE user_id=@user_id AND app_id=@app_id AND user_uuid=@user_uuid", array(
      '@user_id' =>$user_id,
      '@app_id'=>$app_id,
      '@user_uuid'=>$user_uuid,
    ));

    if($query->num_rows()>0) {
      $idx = $query->row()->idx;
      $this->db->where(array('idx' =>$idx))->update('users_token',array('date_expire'=>$date_expire, 'scopes'=>json_encode($scope)));
      return $idx;
    } else {
      $query = $this->edit->insert(
        'users_token', array(
        'user_id'=> $user_id,
        'user_uuid'=> $user_uuid,
        'scopes'=> json_encode($scope),
        'app_id'=> $app_id,
        'date_create'=> date('Y-m-d H:i:s', gmmktime()),
        'date_expire'=> $date_expire,
        'agent'=>$_SERVER['HTTP_USER_AGENT'],
      ));
      die;
      return $this->edit->insert_id();
    }
  }

  public function check_token_by_tokenid ($token_log_id){
    $query = $this->db->query('SELECT * FROM (`users_token`) WHERE `idx` =  @token_log_id', array('@token_log_id'=>$token_log_id), true);
    return $query->row();
  }
    public function get_appdata_by_appid ($app_id){
    $key="get_appdata_by_appid_$app_id";
    if(!$result = $this->tppymemcached->get($key)) {
      $query = $this->db->query('SELECT * FROM applications WHERE `app_id` =  %app_id%', array('%app_id%'=>$app_id), true);
      $result = $query->row();
      $this->tppymemcached->set($key, $result, 604800); // 7 DAYS
    }
    return $result;
  }

}