<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TPPY_Oauth {
  private $default_key = '0@+!2';
  private $rand_length=10;
  private $char_shift=7;
  private $CI;
  private $DBEdit;
  private $default_code_time = 30;
    
  function __construct(){
    $this->CI=& get_instance();
    $this->DBEdit = $this->CI->load->database('edit', TRUE);
    $this->CI->load->library('tppymemcached');
  }
  
  function encrypt($string, $key=null) {
    $key_ext=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $this->rand_length);
    if(empty($key)) {
      $key = $this->default_key;
    }
    $key=$key_ext.$key;
    $result = '';
    for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char) + (ord($keychar) % $this->char_shift));
      $result.=$char;
    }
    $result = strrev($result);
    $result = strtolower($result) ^ strtoupper($result) ^ $result;
    $result = strtr(base64_encode($result), '+/', '-_');
    return $key_ext.$result;
  }
  function decrypt($string, $key=null) {
    $key_ext = substr($string, 0, $this->rand_length);
    $string = substr($string, $this->rand_length, strlen($string));
    if(empty($key)) {
      $key = $this->default_key;
    }
    $key=$key_ext.$key;
    
    $result = '';

    $string = base64_decode(strtr($string, '-_', '+/'));
    $string = strtolower($string) ^ strtoupper($string) ^ $string;
    $string = strrev($string);
    
    for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char) - (ord($keychar) % $this->char_shift));
      $result.=$char;
    }
    return $result;
  }
  function get_user_uuid($refresh=false) {
    $CI=&get_instance();
    $client_request_id = $CI->input->cookie(md5('client_request_uuid'));
    if(!$client_request_id || $refresh)
    {
      $client_request_id = sprintf( 'WEB-%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
      );
      
      $cookie = array(
        'name'   => md5('client_request_uuid'),
        'value'  => $client_request_id,
        'expire' => 63072000, // 2 Year
        'secure' => FALSE
      );
      $CI->input->set_cookie($cookie);
    }

    return $client_request_id;
  } 

  function generate_code($user_id, $app_id, $app_secret, $user_uuid, $scopes, $ip_address) {
    $expired = gmmktime()+$this->default_code_time; 
    $client_data = array(
      'user_id'=>$user_id,
      'app_id' =>$app_id,
      'expired'=>$expired,
      'user_uuid'=>$user_uuid,
      'scopes'=>$scopes,
      'ip_address'=>$ip_address,
    );
    
    return strtr($this->encrypt(json_encode($client_data), $app_secret), '+/', '-_');
  }
  function parse_code($code, $app_secret){
     return  json_decode($this->decrypt($code, $app_secret), FALSE);
  }
  function generate_token($user_id, $member_id, $app_id, $scopes, $user_uuid, $app_expire){
    $start_time = gmmktime();
    $expire = $start_time+$app_expire;
    $query=$this->CI->db->query("SELECT idx FROM users_token WHERE user_id=$user_id AND app_id=$app_id AND user_uuid='$user_uuid'");
    if($query->num_rows()){
      $idx = $query->row()->idx;
      $this->DBEdit->update('users_token', array(
          'date_create'=>date('Y-m-d H:i:s', $start_time),
          'date_expire' => date('Y-m-d H:i:s', $expire),
          'int_date_create'=>$start_time,
          'int_date_expire'=>$expire,
        ), array('idx'=>$idx)
      );
    }else{
      $this->DBEdit->insert('users_token', array(
          'user_id'=>$user_id,
          'scopes'=>$scopes,
          'app_id'=>$app_id,
          'user_uuid'=>$user_uuid,
          'date_create'=>date('Y-m-d H:i:s', $start_time),
          'date_expire' => date('Y-m-d H:i:s', $expire),
          'int_date_create'=>$start_time,
          'int_date_expire'=>$expire,
          'agent'=>$_SERVER['HTTP_USER_AGENT'],
        )
      );
      $idx=$this->DBEdit->insert_id();
    }
    
    $token = array(
      'user_id'=>$user_id,
      // 'member_id'=>$member_id,
      'app_id'=>$app_id,
      // 'scopes'=>$scopes,
      'user_uuid'=>$user_uuid,
      'start'=>$start_time,
      // 'expire' => $expire,
      // 'app_expire'=>$app_expire,
      'idx'=>$idx,
    );

    $this->CI->tppymemcached->delete("token_$user_id$user_uuid");
    
    $token = json_encode($token, JSON_UNESCAPED_UNICODE);       
    $encode_token = $this->encrypt($token);
    return $encode_token;
  }
  function check_token($token_data='', $scopes=''){
    if($token_data) {
        $idx=$token_data->idx;
        // $key="token_$token_data->user_id$token_data->user_uuid";
        // if(!$result = $this->CI->tppymemcached->get($key)) {
          $query=$this->CI->db->query("SELECT * FROM users_token WHERE idx=$token_data->idx");
          $result = $query->row();
          // $this->CI->tppymemcached->set($key, $result, 600);
        // }

        $scopes_arr = explode(',', $result->scopes);
        $need_scopes_arr = explode(',', $scopes);
        if(abs($token_data->start - $result->int_date_create) > 10) { // IF NEW GENERATE
          return array('status'=>false,  'message'=>'token is changed ['.$result->int_date_create.'] : ['.$token_data->start.']', 'code'=>'419');
        } else if($result->int_date_expire < gmmktime() - 10) {  // IF TOKEN EXPIRED( ถ้าserver delay อาจช้าได้ 10 วิ   )
          return array('status'=>false,  'message'=>'token expired ['.$result->int_date_expire.'] : ['.(gmmktime() - 10).']', 'code'=>'419');
        } elseif($need_scopes_arr != array_intersect($scopes_arr, $need_scopes_arr)){  // IF INVALID SCOPE
          return array('status'=>false,  'message'=>'invalid scopes', 'code'=>'400');
        } else {
          return array('status'=>true,  'message'=>'success', 'code'=>'200');
        }
        
    } else {
      return array('status'=>false,  'message'=>'invalid token', 'code'=>'400');
    }
  }
  function parse_token($token=''){
    return  json_decode($this->decrypt($token), FALSE);
  }
  function parse_code_to_access_token($code){
    return json_decode(base64_decode(strtr($code, '-_', '+/')), FALSE);
  }

  function force_clear_token_by_userid($user_id){
    $sql="SELECT * FROM users_token WHERE user_id=$user_id";
    $query=$this->CI->db->query($sql);
    foreach($query->result_array() as $r){
      $key="token_".$r['user_uuid'];
      $rc = $this->CI->tppymemcached->delete($key);
    }
    $this->DBEdit->update('users_token',array('int_date_create'=>0, 'int_date_expire'=>0), array('user_id'=>$user_id));
    $key="token_$token_data->user_id$token_data->user_uuid";
  }
}
?>