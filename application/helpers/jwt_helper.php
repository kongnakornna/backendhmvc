<?php
class JWT{
public static function decode($jwt, $key = null, $verify = true){
		$tks = explode('.', $jwt);
		if (count($tks) != 3) {
			//if you don't want to disclose more details
			return false;

			//throw new UnexpectedValueException('Wrong number of segments');
		}
		list($headb64, $bodyb64, $cryptob64) = $tks;
		if (null === ($header = JWT::jsonDecode(JWT::urlsafeB64Decode($headb64)))) {
			//if you don't want to disclose more details
			return false;

			//throw new UnexpectedValueException('Invalid segment encoding');
		}
		if (null === $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64))) {
			//if you don't want to disclose more details
			return false;

			//throw new UnexpectedValueException('Invalid segment encoding');
		}
		$sig = JWT::urlsafeB64Decode($cryptob64);
		if ($verify) {
			if (empty($header->alg)) {
				//if you don't want to disclose more details
				return false;

				//throw new DomainException('Empty algorithm');
			}
			if ($sig != JWT::sign("$headb64.$bodyb64", $key, $header->alg)) {
				throw new UnexpectedValueException('Signature verification failed');
			}
		}
		return $payload;
	}
public static function encode($payload, $key, $algo = 'HS256'){
		$header = array('typ' => 'JWT', 'alg' => $algo);

		$segments = array();
		$segments[] = JWT::urlsafeB64Encode(JWT::jsonEncode($header));
		$segments[] = JWT::urlsafeB64Encode(JWT::jsonEncode($payload));
		$signing_input = implode('.', $segments);

		$signature = JWT::sign($signing_input, $key, $algo);
		$segments[] = JWT::urlsafeB64Encode($signature);

		return implode('.', $segments);
	}
public static function sign($msg, $key, $method = 'HS256'){
		$methods = array(
			'HS256' => 'sha256',
			'HS384' => 'sha384',
			'HS512' => 'sha512',
		);
		if (empty($methods[$method])) {
			throw new DomainException('Algorithm not supported');
		}
		return hash_hmac($methods[$method], $msg, $key, true);
}
public static function jsonDecode($input){
		$obj = json_decode($input);
		if (function_exists('json_last_error') && $errno = json_last_error()) {
			JWT::_handleJsonError($errno);
		} else if ($obj === null && $input !== 'null') {
			throw new DomainException('Null result with non-null input');
		}
		return $obj;
}
public static function jsonEncode($input){
		$json = json_encode($input);
		if (function_exists('json_last_error') && $errno = json_last_error()) {
			JWT::_handleJsonError($errno);
		} else if ($json === 'null' && $input !== null) {
			throw new DomainException('Null result with non-null input');
		}
		return $json;
}
public static function urlsafeB64Decode($input){
		$remainder = strlen($input) % 4;
		if ($remainder) {
			$padlen = 4 - $remainder;
			$input .= str_repeat('=', $padlen);
		}
		return base64_decode(strtr($input, '-_', '+/'));
}
public static function urlsafeB64Encode($input){
		return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
}
private static function _handleJsonError($errno){
		$messages = array(
			JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
			JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
			JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON'
		);
		throw new DomainException(
			isset($messages[$errno])
			? $messages[$errno]
			: 'Unknown JSON error: ' . $errno
		);
}
#################
/*
const CONSUMER_KEY = 'YOUR_KEY'; // please replace YOUR_XX
const CONSUMER_SECRET = 'YOUR_SECRET'; // please replace YOUR_XX
const CONSUMER_TTL = 86400; // second | 1 day

###################******JWT&****##############
// application\libraries\JWT
// application\libraries\BeforeValidException
// application\libraries\ExpiredException
// application\libraries\SignatureInvalidException
// application\helpers\jwt_helper


$tokenData='Data Input ';
// encode JWT,Authorization
$this->load->helper('jwt');
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$time=60;
$issuedAt=time();
$expirationTime=$issuedAt+$time;  
// jwt valid for $time seconds from the issued time
$payload=array('data'=>$tokenData,
               'issued_at'=>$issuedAt,
               'expire'=>$expirationTime,
               'time_setting'=>$time);
$tokenjwt=JWT::encode($payload,$key,$algorithm);
$dataall['token']=$tokenjwt;

#######################################
#######################################
/*
// decode  JWT,Authorization
$this->load->helper('jwt');
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$datadecode=JWT::decode($tokenjwt,$key,array($algorithm)); 
$ttl_time=$datadecode->iat;
$exp_time=$datadecode->exp;
$now_time=time();

if(($now_time-$ttl_time)>$datadecode->time) {
     $dataall['decode']='Expired';
}else {
     $dataall['decode']=$datadecode;
 }
*/
#######################################
#######################################
###################******JWT&****##############
*/
################
}