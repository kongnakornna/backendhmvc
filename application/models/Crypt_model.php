<?php
class Crypt_model extends CI_Model {
public function __construct() {
    	header('Content-Type: text/html; charset=utf-8');
        parent::__construct();
    }
#Key 
public function key(){
    	$key='-0@+!2'; // serverkey
    	$key1='23423423423@425bte343344'; // serverkey
    	$key2='-0@+!2#44(lmkrt'; // serverkey
    	$key4 = "@doo-plookpanya@";
    	return $key;
    }
public function key2(){
    	$key='CaLl@!truEpLOOkPaYa'; // serverkey
    	return $key;
    }
public function apikey(){
    	$key='@trueplookpanya'; // serverkey
    	return $key;
    }
public function mobilekey(){
    	$key='@doo-plookpanya@'; // serverkey
    	return $key;
    }
public function uploads(){
    	$key='@uploads-plookpanya@'; // serverkey
    	return $key;
    }
public function base64_encrypt($string,$key) {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result.=$char;
        }
         #echo '<pre> base64_encrypt $string==>'; print_r($string); echo '</pre>';
         #echo '<pre> base64_encrypt $key==>'; print_r($key); echo '</pre>';
		 #echo '<pre> base64_encrypt $result==>'; print_r($result); echo '</pre>';  Die();
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
         #echo '<pre> base64_decrypt $string==>'; print_r($string); echo '</pre>';
         #echo '<pre> base64_decrypt $key==>'; print_r($key); echo '</pre>';
		 #echo '<pre> base64_decrypt $result==>'; print_r($result); echo '</pre>'; Die();
        return $result;
    }
public function get_profile_username($user_username,$user_password) {
				//echo '<pre> $user_username=>'; print_r($user_username); echo '</pre>';  
				//echo '<pre> $user_password=>'; print_r($user_password); echo '</pre>';  die();
				 $limit=1;
				 $this->db->select('*');
                 $this->db->from('users_account'); 
                 $this->db->where('user_username',$user_username);
				 $this->db->where('user_password',$user_password);
                 //$this->db->limit($limit);
                 $query = $this->db->get();
				 $num_rows=$query->num_rows(); 
				 $datacheck=$query->result();
				 if($datacheck){$data=$datacheck['0'];}else{$data=0;}
				 //$datarows=$num_rows;
				 //echo '<pre> $query=>'; print_r($query); echo '</pre>';  die();
                
            return $data;
        }
public function get_profile_member_id($member_id) {
		 //echo '<pre> $user_username=>'; print_r($user_username); echo '</pre>';  
				//echo '<pre> $user_password=>'; print_r($user_password); echo '</pre>';  die();
				 $limit=1;
				 $this->db->select('*');
                 $this->db->from('users_account'); 
                 $this->db->where('member_id',$member_id); 
                 //$this->db->limit($limit);
                 $query = $this->db->get();
				 $num_rows=$query->num_rows(); 
				 //$data = $query->result();
				 $data=$num_rows;
				 //echo '<pre> $query=>'; print_r($query); echo '</pre>';  die();
                
            return $data;
        }
public function get_ip_address() {
			if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
			   $ipaddress = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
			else
				$ipaddress = 'UNKNOWN';
			$ip=$ipaddress;
			$ip = strpos($ip, ',') > 0 ? trim(reset(explode(',', $ip))) : trim($ip);
        return $ip;
    }
public function md5($datamd5) {
        $md5=md5($datamd5);
        return $md5;
}
function encode($string,$key) {
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($string,$i,1));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
    }
    return $hash;
}
function decode($string,$key) {
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    for ($i = 0; $i < $strLen; $i+=2) {
        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    return $hash;
}
public function base64encrypt($string, $key) {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result.=$char;
        }

        return base64_encode($result);
    }
public function base64decrypt($string, $key) {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result.=$char;
        }

        return $result;
    }
public function serialize_get($dataset) {
        $result=serialize($dataset);
        return $result;
}
public function unserialize($dataset) {
        $result=unserialize($dataset);
        return $result;
}
public function implode($array) {
        $result=implode(",", $array);
        return $result;
}
public function explode($array) {
        $result=explode(",", $array);
        return $result;
}
public function replace($array) {
        $resultrs=explode(",", $array);
        $result=str_replace(",","-",$resultrs);
        return $result;
}
#################################		
}
/*
// echo md5(md5(md5("password")));
// echo  md5(base64_encode(md5(md5("password"))));
// echo md5(base64_encode(md5(sha1("password")))) ;
*/