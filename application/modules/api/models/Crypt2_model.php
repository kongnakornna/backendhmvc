<?php
class Crypt2_model extends CI_Model {
	
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
   ///////////////
   public function apikey(){
    	$key='@trueplookpanya'; // serverkey
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
	

	function encrypt($string, $key){
		/*
		$id= $_GET['id'];
		$encrypted = encrypt($id, "check");
		echo $encrypted ;
		*/
		   $result = '';
		   for($i=0; $i<strlen($string); $i++) {
			 $char = substr($string, $i, 1);
			 $keychar = substr($key, ($i % strlen($key))-1, 1);
			 $char = chr(ord($char)+ord($keychar));
			 $result.=$char;
		   }
		   return base64_encode($result);
	}
	function decrypt($string, $key) {
	 /*
	$id= $_GET['id'];
	$decrypted = decrypt($id, "check");
	echo $decrypted ; 
	 */
	   $result = '';
	   $string = base64_decode($string);
	   for($i=0; $i<strlen($string); $i++) {
		  $char = substr($string, $i, 1);
		  $keychar = substr($key, ($i % strlen($key))-1, 1);
		  $char = chr(ord($char)-ord($keychar));
		  $result.=$char;
	   }
	   return $result;
	}
}