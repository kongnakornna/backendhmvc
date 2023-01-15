<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_Library
{
  private $CI;
  public $register_key="1e300bc3368121ffd251a8f4f10ee407";
  function __construct()
  {
      $this->CI =& get_instance();    
      // $config = array(
      // 'protocol' => 'smtp',
      // 'smtp_host'=> 'smtp2.truelife.com',
      // 'smtp_port' => 25,
      // 'smtp_user' => '',
      // 'smtp_pass' => '',
      // 'mailtype' => 'html',
      // 'charset' => 'utf-8',
      // 'smtp_timeout'=> 7,
      // 'wordwrap'=>false,
      // 'validate'=>true,
      // 'newline'=> "\r\n",
      // 'crlf'=>'\n',
    // );
    $this->CI->load->library('email');
  }

  public function sendActivateEmail($user_id, $user_email, $username='', $password='') {
    $encode = strtr($this->CI->encrypt->encode(json_encode(array('user_email'=>$user_email, 'user_id'=>$user_id)), $this->register_key), '+/', '-_');
    $data['activation_code'] = $encode;
    $data['username'] = $username;
    $data['password'] = $password;
    
    $email_message = $this->CI->load->view('/member/email_register', $data, TRUE);
    
    $this->CI->email->clear();
    $this->CI->email->from('admin@trueplookpanya.com', 'TruePlookpanya.Com');
    $this->CI->email->to($user_email);
    $this->CI->email->subject('Trueplookpanya.com - ยินดีต้อนรับสู่สมาชิกทรูปลูกปัญญาดอทคอม');
    $this->CI->email->message($email_message);
    return $this->CI->email->send();
  }
  
  public function sendResetPassword($user_username, $user_id, $user_email, $user_update_date) {
    $encode = strtr($this->CI->encrypt->encode(json_encode(
      array(  'user_email'=>$user_email,  'user_id'=>$user_id,  'user_update_date'=>$user_update_date,))
      ,$this->register_key)
      , '+/', '-_');
    $data['code'] = $encode;
    $data['user_id'] = $user_id;
    $data['user_username'] = $user_username;
    
    $message=$this->CI->load->view('/member/email_forgor', $data, TRUE);
    $this->CI->email->clear();
    $this->CI->email->to($user_email);
    $this->CI->email->from('admin@trueplookpanya.com', 'TruePlookpanya.Com');
    $this->CI->email->subject('Trueplookpanya.com - ลืมรหัสผ่านของสมาชิกทรูปลูกปัญญาดอทคอม');
    $this->CI->email->message($message);
    return $this->CI->email->send();
  }
  
  public function sendChangePassEmail($user_username, $user_password, $user_email, $psn_distplay_name) {

    $data = array(
      'user_username'=> $user_username,
      'user_password'=>$user_password,
      'user_email'=>$user_email,
      'psn_distplay_name'=>$psn_distplay_name,
    );
    $message=$this->CI->load->view('/member/email_changepass', $data, TRUE);
    // echo $message;
    $this->CI->email->clear();
    $this->CI->email->from('admin@trueplookpanya.com', 'TruePlookpanya.Com');
    $this->CI->email->to($user_email);
    $this->CI->email->subject('Trueplookpanya.com - ยินดีต้อนรับสู่สมาชิกทรูปลูกปัญญาดอทคอม');
    $this->CI->email->message($email_message);
    return $this->CI->email->send();
  }
  
}