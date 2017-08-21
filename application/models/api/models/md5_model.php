<?php
class Md5_model extends CI_Model {
	
    public function __construct() {
    	header('Content-Type: text/html; charset=utf-8');
        parent::__construct();
    }
    
   #Key
   public function key(){
    	$key='-0@+!2'; // serverkey
    	$key1='23423423423@425bte343344'; // serverkey
    	$key2='-0@+!2#44(lmkrt'; // serverkey
    	return $key2;
    }
   ///////////////
   public function base64_encrypt($string,$key) {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result.=$char;
        }
        #echo '<pre> $string==>'; print_r($string); echo '</pre>';
        #echo '<pre> $key==>'; print_r($key); echo '</pre>';
		#echo '<pre> $result==>'; print_r($result); echo '</pre>';Die();
        return base64_encode($result);
    }
   public function base64_decrypt($string,$key) {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result.=$char;
        }
        #echo '<pre> $string==>'; print_r($string); echo '</pre>';
        #echo '<pre> $key==>'; print_r($key); echo '</pre>';
		#echo '<pre> $result==>'; print_r($result); echo '</pre>';
        return $result;
    }
    /////////// 
    
    
   /* 
	/// เข้า
   public function  postbase64test1(){
    	$key='-0@+!2'; // serverkey
    	$string=$this->base64_encrypt(time());
    	$post=@$this->input->post();
    	$data['start_time'] = $this->base64_encrypt($string,$key);
    }
    /// ออก
   public function  postbase64test2(){
    	$key="-0@+!2";
    	$string=$this->input->post('start_time', TRUE);
    	$post=@$this->input->post();
    	$data['start_time'] = $this->base64_decrypt($string,$key);
        $end_time = time();
        $diff_time = abs($start_time - $end_time);
    }
	*/
	/*
  public function  use_md5(){
	  $this->load->model('api/md5_model');
	  $data_key = $this->md5_model->key();
	  $data_decrypt = $this->md5_model->base64_encrypt($string,$key);
	  $data_decrypt = $this->md5_model->base64_decrypt($string,$key);
	}
  */
}