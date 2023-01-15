<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Testapiget extends REST_Controller{
function __construct(){
parent::__construct();
}
public function index_get(){
$url1='http://www.trueplookpanya.com/webservice/api/upskill/task3_demo?category_id=5&user_id=543622';
$url2='http://www.trueplookpanya.com/webservice/api/upskill/task3_demo?category_id=5&user_id=543622';
$url='http://www.trueplookpanya.com/webservice/api/upskill/configscoreuser?user_id=543622';
$urltask='http://www.trueplookpanya.com/webservice/api/upskill/task?category_id=5';
########################
$urltask_https='https://www.trueplookpanya.com/webservice/api/upskill/configscoreuser?user_id=543622';
//$pathpem="/var/www/html/trueplookpanya.com/www/APACHE-ROOT/cacert.pem";
$pathpem=APPPATH.'/third_party/cassl/cacert.pem';
$file_pem=file_get_contents($pathpem, FILE_USE_INCLUDE_PATH);
$arrContextOptions=array(
    "ssl"=>array(
        //"cafile" => "/cacert.pem",
        //"cafile" =>$pathpem,
        "cafile" =>$file_pem,
        "verify_peer"=> true,
        "verify_peer_name"=> true,
    ),
);
$response=file_get_contents($urltask_https, false, stream_context_create($arrContextOptions));
$decode=json_decode($response);
echo '<hr> <pre> urltask_https =>'; print_r($urltask_https); echo '</pre>';  
echo '<hr> <pre> pathpem =>'; print_r($pathpem); echo '</pre>';  
if(file_exists($pathpem)){
// require_once $file;
echo '<hr> <pre> path pem have file =>'; print_r($pathpem); echo '</pre>';   
}else{
echo '<hr> <pre> path pem not have file =>'; print_r($pathpem); echo '</pre>';   
}
echo '<hr> <pre> https decode =>'; print_r($decode); echo '</pre>';   
echo '<hr> <pre>  read file_pem  file =>'; print_r($file_pem); echo '</pre>'; 
########################
}
function getServerStatistics($url) {
    $statisticsJson = file_get_contents($url);
    if ($statisticsJson === false) {
       return false;
    }
    $statisticsObj = json_decode($statisticsJson);
    if ($statisticsObj !== null) {
       return false;
    }
    return $statisticsObj;
}
public function call_get(){
$url1='http://www.trueplookpanya.com/webservice/api/upskill/task3_demo?category_id=5&user_id=543622';
$url2='http://www.trueplookpanya.com/webservice/api/upskill/task3_demo?category_id=5&user_id=543622';
$url='http://www.trueplookpanya.com/webservice/api/upskill/configscoreuser?user_id=543622';
$urltaskhtpps='https://www.trueplookpanya.com/webservice/api/upskill/task?category_id=5';
require_once APPPATH."/third_party/Requests/Requests.php";
Requests::register_autoloader(); 
$request=Requests::get($url, array('Accept' => 'application/json'));
$search=" ";$replace="";$string=$request;
//$request =str_replace($search,$replace,$string);
// Check what we received
//var_dump($request);  Die();
#print_r($request); Die(); 
#echo'<hr><pre> $request=>';print_r($request);echo'<pre>';   Die();
$request_body=$request->body;
echo'<hr><pre> $request_body=>';print_r($request_body);echo'<pre>';  
$decode=json_decode($request_body);
echo '<pre> call url api decode =>'; print_r($decode); echo '</pre>';   




####################
$request_hrpps=Requests::get($urltaskhtpps, array('Accept' => 'application/json'));
$searchtpps=" ";$replace="";$string=$request_hrpps;
//$request =str_replace($search,$replace,$string);
// Check what we received
//var_dump($request);  Die();
#print_r($request); Die(); 
#echo'<hr><pre> $request=>';print_r($request);echo'<pre>';   Die();
$request_body_htpps=$request_hrpps->body;
echo'<hr><pre> request_body_htpps=>';print_r($request_body_htpps);echo'<pre>';  
$decode_htpps=json_decode($request_body_htpps);
echo '<pre> call url htpps api decode =>'; print_r($decode_htpps); echo '</pre>';   



$pathpem=APPPATH.'/third_party/cassl/cacert.pem';
$arrContextOptions=array(
    "ssl"=>array(
        //"cafile" => "/cacert.pem",
        "cafile" =>$pathpem,
        "verify_peer"=> true,
        "verify_peer_name"=> true,
    ),
);
$response=file_get_contents($urltask_https, false, stream_context_create($arrContextOptions));
$decode=json_decode($response);

##################



}
public function info_get(){
     // Show all information, defaults to INFO_ALL
     phpinfo();
     // Show just the module information.
     // phpinfo(8) yields identical results.
     phpinfo(INFO_MODULES);   
     }

}