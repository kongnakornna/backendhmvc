<?php
require_once APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;
class Authorization{
public static function validateToken($token){
        $CI =& get_instance();
        $key = $CI->config->item('jwt_key');
        $algorithm = $CI->config->item('jwt_algorithm');
        return JWT::decode($token, $key, array($algorithm));
    }
public static function generateToken($data){
        $CI =& get_instance();
        $key = $CI->config->item('jwt_key');
        return JWT::encode($data, $key);
    }    
#################
public function jwtdecode($data){
     $CI =& get_instance();
     $key = $CI->config->item('jwt_key');
     $jwt_decode=JWT::decode($data, $key);
return $jwt_decode;
}   
public function jwtencode($token,$time){
     if($time==''){$time=60;}
     $CI =& get_instance();
     $key = $CI->config->item('jwt_key');
     $issuedAt=time();
     $expirationTime=$issuedAt+$time;  
     // jwt valid for time seconds from the issued time
     $payload= array(
       'token'=>$token,
       'iat'=>$issuedAt,
       'exp'=>$expirationTime
     );
     $jwt_encode=JWT::encode($payload,$key);
return $jwt_encode;
}
#################
}


/*

$issuedAt = time();
$expirationTime = $issuedAt + 60;  // jwt valid for 60 seconds from the issued time
$payload = array(
  'userid' => $userid,
  'iat' => $issuedAt,
  'exp' => $expirationTime
);
$key = JWT_SECRET;
$alg = 'HS256';
$jwtencode = JWT::encode($payload, $key, $alg);
expirationTime 
 
*/ 
// https://www.dyclassroom.com/json-web-tokens/jwt-project-firebase-php-jwt-generating-jwt
/*
* 
* 
JWT::decode($jwtencode, $key, array('HS256'));

* 
*/