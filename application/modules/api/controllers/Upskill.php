<?php defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . 'libraries/REST_Controller.php';
// clean the output buffer
//ob_clean();
ob_end_clean();
/*
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
*/
class Upskill extends REST_Controller{
function __construct(){
//ob_end_clean();
// Construct the parent class
parent::__construct();
$this->load->library('encrypt');
//Model_upskill
$this->load->model('Model_upskill');
$this->load->model('Model_examination');
}
public function categoryjwt_get(){
####################################################
ob_end_clean();
$module_name='upskill category jwt';
ob_end_clean();
$category_id=@$this->input->get('category_id');
if($category_id==''){$category_id='';}
$deletekey=@$this->input->get('deletekey');
$cachetype='4';
$dataformmodel=$this->Model_upskill->get_category($category_id,$deletekey,$cachetype);
###################******JWT&encode****##############
// encode JWT 
$token_data=$dataformmodel;
$this->load->helper('jwt');
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$time_setting=600;
$issued_at=time();
$issued_at_date=(date("Y-m-d H:i:s",$issued_at));
$expiration_time=$issued_at+$time_setting;  
$expiration_time_date=(date("Y-m-d H:i:s",$expiration_time));
$payload=array('token_data'=>$token_data,
               'issued_at'=>$issued_at,
               'issued_at_date'=>$issued_at_date,
               'expiration_time'=>$expiration_time,
               'expiration_time_date'=>$expiration_time_date,
               'time_setting'=>$time_setting,
               );
$token=JWT::encode($payload,$key,$algorithm);
###################******JWT&decode****##############
$data_decode_jwt=JWT::decode($token,$key,array($algorithm)); 
####JSON####              
if($token!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							//'data'=> $token_data,
                                   'token'=> $token,
                                   'token_decode'=> $data_decode_jwt,
                                   //'info_cache'=>$cacheinfo,
                                   ),200);
}
else{

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'token'=>null,
                                        //'info_cache'=>$cacheinfo,
                                        ),204);
}
die();
####JSON####  
}
public function jwtread_get(){
####################################################
ob_end_clean();
$module_name='JWT TEST';
$token=$this->input->get('token');
# if(!is_internal()){show_404();}
###################******JWT&decode****##############
// decode  JWT 
$this->load->helper('jwt');
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$data_decode_jwt=JWT::decode($token,$key,array($algorithm)); 
#echo'<hr> <pre> data_decode_jwt=>';print_r($data_decode_jwt);echo'<pre> <hr>'; //Die(); 
#echo(date("Y-m-d",$expiration_time));
$timenow_de=time();
$time_now_de=(date("Y-m-d H:i:s",$timenow_de));
$issued_at=$data_decode_jwt->issued_at;
$time_setting=$data_decode_jwt->time_setting;
$expiration_time=$data_decode_jwt->expiration_time;
$datadecode=array('token_data'=>$data_decode_jwt->token_data,
                  'issued_at'=>$issued_at,
                  'expiration_time'=>$expiration_time,
                  'time_setting'=>$time_setting,
                  'time_issued_at'=>(date("Y-m-d H:i:s",$issued_at)),
                  'time_expiration'=>(date("Y-m-d H:i:s",$expiration_time)),
                  'timenow'=>$timenow_de,
                  'time_now'=>$time_now_de,
                  );
#echo'<hr><pre> datadecode=>';print_r($datadecode);echo'<pre> <hr>'; Die();  
$now=time();
$now=(int)$now;
$issued_at=(int)$issued_at;
$timecul=($now-$issued_at);
if($timecul>$time_setting){
     $msg_time='Expired';
     $time_st=0;
     $datars=null;    
}
else{ 
     $msg_time='On time not Expired yet';
     $time_st=1;
     $datars=$datadecode;
}
if($time_st==1) {
     $dataalls=array('module'=>'jwt token',
                'data'=>$datars,
                'msg_time'=>$msg_time,
                'timecul'=>$timecul,
                'status_time'=>$time_st,
                'status'=>false,
                );
}
else{
     $dataalls=array('module'=>'jwt token',
                'data'=>null,
                'msg_time'=>$msg_time,
                'timecul'=>$timecul,
                'status_time'=>$time_st,
                'status'=>true,
                );           
 }
###################******JWT&****##############
####JSON####              
if($dataalls!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
                                   'data'=>$dataalls,
                                   'msg_time'=>$msg_time,
                                   'time_st'=>$time_st,
                                   //'info_cache'=>$cacheinfo,
                                   ),200);
}
else{
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=>null,
                                        'msg_time'=>$msg_time,
                                        'time_st'=>$time_st,
                                        //'info_cache'=>$cacheinfo,
                                        ),204);
}
die();
####JSON####  
}
public function index_get(){
ob_end_clean();
// Lets try to get the key
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('list'=>null,'stats'=>$cache_info,'version'=>$cacheversion);
$module_name='Examination';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>201), 
                                   'data'=> $cacheinfo),201);
/*
  $this->response(array('header'=>array('title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'REST_Controller::HTTP_NOT_FOUND',
								'status'=>FALSE, 
								'code'=>404), 
								'data'=> $cacheinfo),404);
                                        
 */
//$this->info_get();
}
public function category_get(){
####################################################
ob_end_clean();
$module_name='upskill category';
ob_end_clean();
$category_id=@$this->input->get('category_id');
if($category_id==''){$category_id='';}
$deletekey=@$this->input->get('deletekey');
$cachetype='4';
$dataformmodel=$this->Model_upskill->get_category($category_id,$deletekey,$cachetype);
###################******JWT&encode****##############
// encode JWT 
$token_data=$dataformmodel;
$this->load->helper('jwt');
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$time_setting=600;
$issued_at=time();
$issued_at_date=(date("Y-m-d H:i:s",$issued_at));
$expiration_time=$issued_at+$time_setting;  
$expiration_time_date=(date("Y-m-d H:i:s",$expiration_time));
$payload=array('token_data'=>$token_data,
               'issued_at'=>$issued_at,
               'issued_at_date'=>$issued_at_date,
               'expiration_time'=>$expiration_time,
               'expiration_time_date'=>$expiration_time_date,
               'time_setting'=>$time_setting,
               );
$token=JWT::encode($payload,$key,$algorithm);
###################******JWT&decode****##############
$data_decode_jwt=JWT::decode($token,$key,array($algorithm)); 
####JSON####              
if($token!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $token_data,
                                   //'token'=> $token,
                                   //'token_decode'=> $data_decode_jwt,
                                   //'info_cache'=>$cacheinfo,
                                   ),200);
}
else{

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'token'=>null,
                                        //'info_cache'=>$cacheinfo,
                                        ),204);
}
die();
####JSON####  
}
public function type_get(){
####################################################
ob_end_clean();
$module_name='upskill type';
$deletekey=@$this->input->get('deletekey');
$cachetype='4';
$dataformmodel=$this->Model_upskill->get_type($deletekey,$cachetype);
/*
###################******JWT&encode****##############
// encode JWT 
$token_data=$dataformmodel;
$this->load->helper('jwt');
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$time_setting=600;
$issued_at=time();
$issued_at_date=(date("Y-m-d H:i:s",$issued_at));
$expiration_time=$issued_at+$time_setting;  
$expiration_time_date=(date("Y-m-d H:i:s",$expiration_time));
$payload=array('token_data'=>$token_data,
               'issued_at'=>$issued_at,
               'issued_at_date'=>$issued_at_date,
               'expiration_time'=>$expiration_time,
               'expiration_time_date'=>$expiration_time_date,
               'time_setting'=>$time_setting,
               );
$token=JWT::encode($payload,$key,$algorithm);
###################******JWT&decode****##############
$data_decode_jwt=JWT::decode($token,$key,array($algorithm)); 
####JSON####     
*/         
if($dataformmodel!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataformmodel,
                                   ),200);
}
else{

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'token'=>null,
                                        ),204);
}
die();
####JSON####  
}
public function item_get(){
####################################################
ob_end_clean();
$module_name='upskill type';
$category_id=@$this->input->get('category_id');
if($category_id==null || $category_id==0){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>'Error category_id is null',
							'status'=>TRUE,
							'code'=>201), 
							'data'=>null),201);
Die();       
}
$user_id=@$this->input->get('user_id');
$type_id=@$this->input->get('type_id');
$item_id=@$this->input->get('item_id');
$page=@$this->input->get('page');
$perpage=@$this->input->get('perpage');
$deletekey=@$this->input->get('deletekey');
$cachetype='4';
$dataformmodel=$this->Model_upskill->get_ups_item($category_id,$type_id,$item_id,$page,$perpage,$user_id,$deletekey,$cachetype); 
if($dataformmodel!==''){   
##########################################
     $list=$dataformmodel['list'];
     $item_arr=array();
     if(is_array($list)){
     foreach($list as $k =>$v){
     $arr2=array();
       $item_id=(int)$v->item_id;
       $arr2['b']['category']=$v->category;
       $arr2['b']['category_id']=$v->category_id;
       $arr2['b']['item_content_id']=$v->item_content_id;
       $arr2['b']['item_id']=$v->item_id;
       $arr2['b']['item_content_id']=$v->item_content_id;
       $arr2['b']['item_id']=$v->item_id; 
       $arr2['b']['item_title']=$v->item_title; 
       $arr2['b']['sort']=$v->sort;  
       $arr2['b']['task_description']=$v->task_description;  
       $arr2['b']['task_title']=$v->task_title;  
       $arr2['b']['visited']=$v->visited;  
       $arr2['b']['log_start_date']=$v->log_start_date;
       $arr2['b']['log_due_date']=$v->log_due_date;
       $arr2['b']['log_create_date']=$v->log_create_date;
       $arr2['b']['log_update_date']=$v->log_update_date;
       $arr2['b']['log_title']=$v->log_title; 
     $item_arr[]=$arr2['b'];
      }
     }
#########################################
     $data=array('list'=>$item_arr,
                 'total_page'=>(int)$dataformmodel['total_page'],
                 'time'=>(int)$dataformmodel['time'],
                 'num_rows'=>(int)$dataformmodel['num_rows'],
                 'message'=>(int)$dataformmodel['message'],
                 'sql'=>$dataformmodel['sql'],
                 );
#########################################
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>'DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>$data,
                                   //'data2'=> $dataformmodel,
                                   ),200);
}
else{
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'token'=>null,
                                        ),204);
}
die();
}
public function updatesetlisttask_post(){   
$post=$this->input->post();
$list=$post['list'];
     $i=1; 
	foreach($list as $item){ 
			$id=(int)$item['task_id'];
			$order=$i;
			//echo '$id=>'.$id; 
			//echo '$order=>'.$order; 
			$data=array('sort' => $order);	
			//echo '<pre> $data=> '; print_r($data); echo '</pre>'; 	
    			$resultCount=$this->Model_upskill->get_update_sort_task($data,$id);
    			//echo '$resultCount=>'.$resultCount; 
		$i++;
	}
     
#echo 'resultCount=>'.$resultCount; 
#echo '<pre> post=> '; print_r($post); echo '</pre>';  die();
#echo 'Success';

     $module_name='list data';
     $message='Success';
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>$message,
                                   'post'=>$post,
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $resultCount,
                                   ),200);
die();
####################################
}
public function updatesetlistitem_post(){   
$post=$this->input->post();
$list=$post['list'];
     $i=1; 
	foreach($list as $item){ 
			$id=(int)$item['item_id'];
			$order=$i;
			//echo '$id=>'.$id; 
			//echo '$order=>'.$order; 
			$data=array('sort' => $order);	
			//echo '<pre> $data=> '; print_r($data); echo '</pre>'; 	
    			$resultCount=$this->Model_upskill->get_update_sort_item($data,$id);
    			//echo '$resultCount=>'.$resultCount; 
		$i++;
	}
     
#echo 'resultCount=>'.$resultCount; 
#echo '<pre> post=> '; print_r($post); echo '</pre>';  die();
#echo 'Success';

     $module_name='list data';
     $message='Success';
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>$message,
                                   'post'=>$post,
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $resultCount,
                                   ),200);
die();
####################################
}
public function upsloguseritem_get(){   
$deletekey=@$this->input->get('deletekey');
$ups_item_id=@$this->input->get('item_id');
if($ups_item_id==null){
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>'ups log user item',
							'message'=>'Error item_id is null',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> null,
                                   ),200);
die();
}
$due_date=@$this->input->get('due_date');
$ups_user_id=@$this->input->get('user_id');

# $start_date=@$this->input->get('start_date');
$y=@$this->input->get('y');
$m=@$this->input->get('m');
$d=@$this->input->get('d');
$inputdate=$y.'-'.$m.'-'.$d;
$start_date=$inputdate;
if($y==null || $d==null || $d==null){$start_date=null;}

if($ups_user_id==null){
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>'ups log user item',
							'message'=>'Error user id is null',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> null,
                                   ),200);
die();
}
$status=$this->input->get('status');

$itemslog=$this->Model_upskill->get_upsloguser($ups_user_id,$ups_item_id,$status,$start_date,$due_date,$deletekey);
$module_name='ups log user';
$message='Success have log';      
if($itemslog!==''){
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>$message,
                                   'date'=>$inputdate,
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $itemslog,
                                   ),200);
}
else{
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>$message,
                                   'date'=>$inputdate,
							'status'=>FALSE,
							'code'=>204), 
							'data'=> null,
                                   ),204);
}
die();
####################################
}
public function examuserlogscore_get(){
####################################################
ob_end_clean();
$module_name='upskill  exam user log';
$exam_id=@$this->input->get('exam_id');
if($exam_id==null || $exam_id==0){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>'Error exam_id is null',
							'status'=>TRUE,
							'code'=>201), 
							'data'=>null),201);
Die();       
}
$user_id=@$this->input->get('user_id');
if($user_id==null || $user_id==0){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>'Error user_id is null',
							'status'=>TRUE,
							'code'=>201), 
							'data'=>null),201);
Die();       
}
$deletekey=@$this->input->get('deletekey');
$cachetype='4';
$formmodellog=$this->Model_upskill->get_logscore_user_id($exam_id,$user_id,$cachetype,$deletekey);
$datalog=$formmodellog['list'];
if($datalog!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $datalog,
                                   ),200);
}
else{
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'token'=>null,
                                        ),204);
}
die();
}
public function ansquestion_get(){
####################################################
ob_end_clean();
$module_name='upskill question';
ob_end_clean();
$question_id=@$this->input->get('question_id');
$status=@$this->input->get('status');
$deletekey=@$this->input->get('deletekey');
$cachetype='4';
$datas=$this->Model_upskill->get_ans_question_true($question_id,$status,$cachetype,$deletekey); 
$data=$datas['list']; 
$cachekey=$datas['cachekey'];     
$message=$datas['message'];  
$question1=$data['0']; 
$examination_name=$question1->examination_name; 
$question=$question1->detail_question; 
$id_question=$question1->id_question; 
$dataall=array('examination_name'=>$examination_name,
               'question'=>$question,
               'question_id'=>$id_question,
               'choice'=>$data,
           );   
if($data!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200),
							'data'=>$dataall,
                                   'cachekey'=>$cachekey,
                                   'message'=>$message,
                                   ),200);
}
else{

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=>null,
                                        ),204);
}
die();
####JSON####  
}
public function configscoreuser_get(){
ob_end_clean();
// upskill/configscoreuser?user_id=543622&cat=5&score=75
$ups_user_id=@$this->input->get('user_id');
$cat=@$this->input->get('cat');
if($ups_user_id==null || $ups_user_id==0){
     $this->response(array('header'=>array(
					  'title'=>'REST_Controller::HTTP_OK',
                            'module'=>$module_name,
					   'message'=>'Error  user_id is null',
					   'status'=>TRUE,
					   'code'=>201), 
					   'data'=>null),201);
Die();       
}
$module_name='Config score user id '.$ups_user_id;
$type=@$this->input->get('type');
if($type==null){$type=3;}
$score=(int)$this->input->get('score');
if($ups_user_id==null || $ups_user_id==0){$score=null;}
if($score==null ||$score==''){
     $score=null;
}else{ 
$gettype=gettype($score);
/*
echo ' gettype=>'.$gettype.'<br>';
if(is_int($score)){echo "Value = ".$score;}else{echo "Not integer ".$score;}die();
*/
######################
if (is_int($score)){
      $score=(int)$score;
      #echo "$score is Integer<br>" ;
}else{
#echo "$var_name1 is not an Integer<br>" ;
        $this->response(array('header'=>array(
     					'title'=>'REST_Controller::HTTP_OK',
                              'module'=>$module_name,
     					'message'=>'Error score '.$score.' is not an Integer',
     					'status'=>TRUE,
     					'code'=>201), 
     					'data'=>null),201);     
     Die(); 
  }
}

$deletekey=@$this->input->get('deletekey');
//$user_config=$this->Model_upskill->get_upsloguser_config($ups_user_id,$score,$type,$deletekey);
$user_config=$this->Model_upskill->get_upsloguser_config($ups_user_id,$cat,$score,$type,$deletekey);
if($user_config!==null){
     $userconfig1=$user_config['0'];
     $score=$userconfig1->visited;
     if($score==0){
         $title='ยังไม่ได้ตั้งค่า config'; 
     }else{
       $title=$userconfig1->title;   
     }
     
     $ups_user_id=$userconfig1->ups_user_id;
     $userconfig=array('score'=>$score,'user_id'=>$ups_user_id,'title'=>$title);
     
}else{
     $score=null;
     $userconfig=array('score'=>$scor,
                       'user_id'=>$ups_user_id,
                       'title'=>'ไม่มีค่า config'
     );
}


if($user_config!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
                                   'data'=>$userconfig,
                                   //'data_user_score_all'=>$user_bar,
                                   //'logimplodeall'=> $logimplodeall,
                                   ),200);
                                   die();
}
else{
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'token'=>null,
                                        ),204);
                                        die();
}
}
public function loguserexamid_get(){
####################################################
ob_end_clean();
$module_name='upskill log user id';
$exam_id=@$this->input->get('exam_id');
$user_id=@$this->input->get('user_id');
$cachetype='4';
$deletekey=@$this->input->get('deletekey');
$exam_log=$this->Model_upskill->loguserexam_get($exam_id,$user_id,$cachetype,$deletekey);
if($exam_log!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200),
							'data'=>$exam_log,
                                   ),200);
}
else{
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=>null,
                                        ),204);
}
die();
####JSON####  
}
public function task_get(){
####################################################
ob_end_clean();
$module_name='upskill task';
$category_id=@$this->input->get('category_id');
if($category_id==null || $category_id==0){
     $this->response(array('header'=>array(
					  'title'=>'REST_Controller::HTTP_OK',
                            'module'=>$module_name,
					   'message'=>'Error category_id is null',
					   'status'=>TRUE,
					   'code'=>201), 
					   'data'=>null),201);
Die();       
}
$user_id=@$this->input->get('user_id');
if($user_id==null || $user_id==0){$user_id=null;}
/*
if($user_id==null || $user_id==0){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>'Error user_id is null',
							'status'=>TRUE,
							'code'=>201), 
							'data'=>null),201);
Die();       
}
*/
$type_id=@$this->input->get('type_id');
$item_id=@$this->input->get('item_id');
$page=@$this->input->get('page');
$perpage=@$this->input->get('perpage');
$deletekey=@$this->input->get('deletekey');
$cachetype='4';
$formmodel=$this->Model_upskill->get_ups_task($category_id,$item_id,$page,$perpage,$deletekey,$cachetype);
$task=$formmodel['list'];
$task_arr=array();
if(is_array($task)){
foreach($task as $key =>$val){
$arr=array();
  $task_id=(int)$val->task_id;
  $categoryid=(int)$val->ups_category_id;
  $arr['a']['category_id']=$category_id;
  $arr['a']['task_id']=$item_id;
  $arr['a']['user_id']=$user_id;
  //$arr['a']['type_id']=$type_id;
  $arr['a']['task_id']=$task_id;
  //$arr['a']['category_id']=$categoryid;
  $arr['a']['category']=$val->category;
  $arr['a']['task_title']=$val->task_title;
  $arr['a']['task_description']=$val->task_description;
  $arr['a']['sort']=$val->sort; 
  $arr['a']['status']=$val->status; 
  $arr['a']['create_by_ip']=$val->create_by_ip; 
  $arr['a']['update_by_ip']=$val->update_by_ip; 
  $arr['a']['create_by_user']=$val->create_by_user; 
  $arr['a']['update_by_user']=$val->update_by_user; 
  $arr['a']['create_date']=$val->create_date; 
  $arr['a']['update_date']=$val->update_date; 
/*
1 Exam 
2 Cmsblog
3 Learning
4 TV Program
5 Other  
*/
$typeid=null;
$itemid=null;
$item=$this->Model_upskill->get_ups_item($categoryid,$typeid,$task_id,$itemid,$page,$perpage,$user_id,$deletekey,$cachetype);
$itemlist=$item['list'];   
$sql=$item['sql']; 
####################################
  $item_arr=array();
     if(is_array($itemlist)){
     foreach($itemlist as $k =>$v){
     $arr2=array();
       $item_id=(int)$v->item_id;
       $arr2['b']['user_id']=$user_id;
       $arr2['b']['category']=$v->category;
       $arr2['b']['type_name']=$v->type_name;
       $ups_type_id=(int)$v->ups_type_id;
       $arr2['b']['type_id']=$ups_type_id;
       $arr2['b']['item_id']=$v->item_id;
       $exam_id=(int)$v->item_content_id;
       $arr2['b']['item_content_id']=$exam_id;
       $arr2['b']['item_title']=$v->item_title; 
       $arr2['b']['item_url']=$v->item_url; 
       $arr2['b']['sort']=$v->sort; 
       #$visited=$v->visited;    
if($ups_type_id==3){
if($user_id!==null){
$cachetype=4;
$datalist=$this->Model_upskill->get_log_item_user_type($item_id,$user_id,$ups_type_id,$cachetype,$deletekey);
$datalist=$datalist['list']; 
$datalistrs=$datalist['0'];
$visited=$datalistrs->visited;

if($visited==null){$visited=0;}
     $arr2['b']['visited']=(int)$visited;  
if($datalist==null){ 
     $arr2['b']['log']=null;  
}else{ 
     $arr2['b']['log']=$datalist['0']; 
}
}else{
     $arr2['b']['log']=null;
     $arr2['b']['visited']=0;
}
}else{$arr2['b']['log']=null;}   
if($user_id!==null){
if($exam_id!==null){
#$exam_log=$this->Model_upskill->get_logscore_user_id($exam_id,$user_id,$cachetype,$deletekey);
#$exam_log_list=$exam_log['list']; 
$exam_log_list=$this->Model_upskill->loguserexam_get($exam_id,$user_id,$cachetype,$deletekey);
$arr2['b']['logexam']=$exam_log_list;
}else{
$arr2['b']['log_exam']=null;  
}
}else{
$arr2['b']['log_exam']=null;  
}

     $item_arr[]=$arr2['b'];
      }
     }
$arr['a']['item']=$item_arr;
#################################
$item_count=(int)count($item_arr);
$arr['a']['item_count']=$item_count;
if($ups_type_id==3){ 
$visited_arr=array();
if(is_array($item_arr)){
     foreach($item_arr as $k =>$v){
     $arr2=array();
        $visited=(int)$v['visited'];
     if($visited>=1){
          $arrc['c']['visited']=$visited; 
          $visited_arr[]=$arrc['c'];
          
        } 
      }
     }  
     //$arr['a']['visited_arr']=$visited_arr;
     $item_count_visited=(int)count($visited_arr);
     $arr['a']['item_count_visited']=$item_count_visited;
     $percent_visited=($item_count_visited*100)/$item_count;
     $percentvisited=(int)round($percent_visited,0,PHP_ROUND_HALF_UP); 
     $arr['a']['visited_percent']=$percentvisited;
     $arr['a']['visit_percent']=$percentvisited.' %';
} 

#################################
  //$arr['a']['item_sql']=$sql;
####################################
  //$arr['a']['item_sql']=$item['sql'];  
  //$arr['a']['sql']=$dataformmodel['sql'];  
$task_arr[]=$arr['a'];
 }
}
if($task_arr!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $task_arr,
                                   ),200);
}
else{
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'token'=>null,
                                        ),204);
}
die();
}
public function task1_get(){
// api/upskill/task2?category_id=5&user_id=543622&deletekey=1
####################################################
ob_end_clean();
$module_name='upskill task';
$category_id=@$this->input->get('category_id');
if($category_id==null || $category_id==0){
     $this->response(array('header'=>array(
					  'title'=>'REST_Controller::HTTP_OK',
                            'module'=>$module_name,
					   'message'=>'Error category_id is null',
					   'status'=>TRUE,
					   'code'=>201), 
					   'data'=>null),201);
Die();       
}
$user_id=@$this->input->get('user_id');
if($user_id==null || $user_id==0){$user_id=null;}
/*
if($user_id==null || $user_id==0){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>'Error user_id is null',
							'status'=>TRUE,
							'code'=>201), 
							'data'=>null),201);
Die();       
}
*/
$type_id=@$this->input->get('type_id');
$item_id=@$this->input->get('item_id');
$page=@$this->input->get('page');
$perpage=@$this->input->get('perpage');
$deletekey=@$this->input->get('deletekey');
$cachetype='4';
$formmodel=$this->Model_upskill->get_ups_task($category_id,$item_id,$page,$perpage,$deletekey,$cachetype);
$task=$formmodel['list'];
$task_arr=array();
if(is_array($task)){
foreach($task as $keym =>$val){
$arr=array();
  $task_id=(int)$val->task_id;
  $categoryid=(int)$val->ups_category_id;
  $arr['a']['category_id']=$category_id;
  $arr['a']['task_id']=$task_id;
  $arr['a']['user_id']=$user_id;
  //$arr['a']['type_id']=$type_id;
  //$arr['a']['category_id']=$categoryid;
  $arr['a']['category']=$val->category;
  $arr['a']['task_title']=$val->task_title;
  $arr['a']['task_description']=$val->task_description;
  //$arr['a']['sort']=$val->sort; 
  $arr['a']['status']=$val->status; 
  $arr['a']['create_by_ip']=$val->create_by_ip; 
  //$arr['a']['update_by_ip']=$val->update_by_ip; 
  //$arr['a']['create_by_user']=$val->create_by_user; 
  //$arr['a']['update_by_user']=$val->update_by_user; 
  //$arr['a']['create_date']=$val->create_date; 
  //$arr['a']['update_date']=$val->update_date; 
/*
1 Exam 
2 Cmsblog
3 Learning
4 TV Program
5 Other  
*/
$typeid=null;
$itemid=null;
$item=$this->Model_upskill->get_ups_item($categoryid,$typeid,$task_id,$itemid,$page,$perpage,$user_id,$deletekey,$cachetype);
$itemlist=$item['list'];   
$sql=$item['sql']; 
####################################
$item_arr=array();
if(is_array($itemlist)){
foreach($itemlist as $k2 =>$v){
$arr2=array();
       $item_id=(int)$v->item_id;
       $arr2['b']['user_id']=$user_id;
       $arr2['b']['category']=$v->category;
       $arr2['b']['type_name']=$v->type_name;
       $ups_type_id=(int)$v->ups_type_id;
       $arr2['b']['type_id']=$ups_type_id;
       $arr2['b']['item_id']=$v->item_id;
       $exam_id=(int)$v->item_content_id;
       $arr2['b']['item_content_id']=$exam_id;
       $arr2['b']['item_title']=$v->item_title; 
       $arr2['b']['item_url']=$v->item_url; 
       $arr2['b']['sort']=$v->sort; 
       #$visited=$v->visited; 
     
    
        
#########type 3############
if($ups_type_id==3){
#####################
if($user_id!==null){
#########user_id############
$cachetype=4;
$datalist=$this->Model_upskill->get_log_item_user_type($item_id,$user_id,$ups_type_id,$cachetype,$deletekey);
$datalist=$datalist['list']; 
$datalistrs=$datalist['0'];
$visited=$datalistrs->visited;
if($visited==null){$visited=0;}
$arr2['b']['visited']=(int)$visited;  
if($datalist==null){$arr2['b']['log']=null;}else{$arr2['b']['log']=$datalist['0'];}


#########user_id############
}else{$arr2['b']['log']=null;$arr2['b']['visited']=0;}
#####################
}else{$arr2['b']['log']=null;} 
### type_id 1
if($ups_type_id==1){
     
if($user_id!==null){
     
$examlog=$this->Model_upskill->get_logscore_user_id($exam_id,$user_id,4,$deletekey);
$exam_log_list=$examlog['list']; 
#$arr2['b']['log_exam']=$exam_log_list;

$e_log_arr=array();
if(is_array($exam_log_list)){
foreach($exam_log_list as $k3 =>$v3){
$arr3=array();
$log_id=$v3->log_id;
     $arr3['c']['log_id']=$log_id;
     $arr3['c']['score_exam']=$v3->score_exam;
     $arr3['c']['score_user']=$v3->score_user;
      $chartdata=$this->qleu($exam_id,$user_id,$log_id,$deletekey);
      $arr3['c']['chart']=$chartdata['radar_chart'];
      $arr3['c']['radarchart']=$chartdata['radarchart'];
     $e_log_arr[]=$arr3['c'];
}}
#################################
$logarr=array();
if (is_array($e_log_arr)){foreach($e_log_arr as $ks =>$vs){$arrs=array();$arrs['e']=(int)$vs['log_id'];$logarr[]=$arrs['e'];}}
#################################
$logimplode=implode(",", $logarr);
$logexplode=explode(",", $logimplode);
$arr2['b']['log_implode']=$logimplode;
$arr2['b']['log_explode']=$logexplode;
$arr2['b']['log_exam']=$e_log_arr;
$arr2['b']['log_exam_count']=count($exam_log_list);
#####################################################
#####################################################             
}else{
          $arr2['b']['log_exam']=null;
          $arr2['b']['log_exam_count']=0;
    }
    
} 
### type_id 1
 $item_arr[]=$arr2['b'];
 }
}
if($ups_type_id==1){
     $arr['a']['item_count']=0;
     $arr['a']['item']=null;   
}else{
     $item_count=(int)count($item_arr);
     $arr['a']['item_count']=$item_count;
     $arr['a']['item']=$item_arr;    
}
#################################
$logarr1=array();
if (is_array($item_arr)){
foreach($item_arr as $ks1 =>$vs1){
     $arrs1=array();
     $arrs1['f']=(int)$vs1['item_content_id'];
     $logarr1[]=$arrs1['f'];
     }}
//$arr['a']['item_exam_id']=$logarr1;
$logimplode1=implode(",", $logarr1);
$arr['a']['item_implode']=$logimplode1; 
$logexplode1=explode(",", $logimplode1);
$arr['a']['item_explode']=$logexplode1; 
$exam_id_in=$logimplode1;
$qleuarray=$this->qleuarray($exam_id_in,$user_id,$deletekey);
$arr['a']['chart']=$qleuarray;

#################################

if($ups_type_id==3){ 
$visited_arr=array();
if(is_array($item_arr)){
     foreach($item_arr as $k =>$v){
     $arr2=array();
        $visited=(int)$v['visited'];
     if($visited>=1){
          $arrc['c']['visited']=$visited; 
          $visited_arr[]=$arrc['c'];
          
        } 
      }
     }  
     //$arr['a']['visited_arr']=$visited_arr;
     $item_count_visited=(int)count($visited_arr);
     $arr['a']['item_count_visited']=$item_count_visited;
     $percent_visited=($item_count_visited*100)/$item_count;
     $percentvisited=(int)round($percent_visited,0,PHP_ROUND_HALF_UP); 
     $arr['a']['visited_percent']=$percentvisited;
     $arr['a']['visit_percent']=$percentvisited.' %';
} 

#################################
  //$arr['a']['item_sql']=$sql;
####################################
  //$arr['a']['item_sql']=$item['sql'];  
  //$arr['a']['sql']=$dataformmodel['sql']; 
  if($type_id==null){
     if($ups_type_id==1){ $task_arr[]=$arr['a']; }  
  }else{
       if($type_id==1 ||$type_id==2 ||$type_id==3){
         if($ups_type_id==$type_id){ $task_arr[]=$arr['a']; }     
       }else{ $task_arr[]=$arr['a']; } 
  }

 }
}

$all_arr=array();
if(is_array($task_arr)){
foreach($task_arr as $k =>$v){
$arrc3=array();
     $arrc3['c']['category_id']=$v['category_id'];  
     $arrc3['c']['create_by_ip']=$v['create_by_ip'];  
     $arrc3['c']['user_id']=$v['user_id']; 
     $arrc3['c']['item_explode']=$v['item_explode'];  
     $arrc3['c']['status']=$v['status']; 
 $all_arr[]=$arrc3['c'];
 }
} 
$all_arr4=array();
if(is_array($all_arr)){
foreach($all_arr as $k2 =>$v2){
$arrc4=array();
 $all_arrimplode=$v2['item_explode']; 
 $arrc4['c']=implode(",", $all_arrimplode); 
 $all_arr4[]=$arrc4['c'];
 }
} 
$all_arr5=implode(",", $all_arr4);
$exam_id_in=$all_arr5;
########################
$qtotal=$this->Model_examination->where_question_total_exam_id_in($exam_id_in,$cachetype,$deletekey);
$qtotals=$qtotal['list']['0'];
$question_total=(int)$qtotals->question_total;
$sumscore=$this->Model_examination->where_exam_sumscore_member_in($exam_id_in,$user_id,$cachetype,$deletekey);
$sumscore=$sumscore['list']['0'];
$sum_score=(int)$sumscore->sum_score;


$score_percen=(($sum_score*100)/$question_total);
$scorepercenuser=(int)round($score_percen,0,PHP_ROUND_HALF_UP);  
     

$user_bar=array('total'=>$question_total,'sumscore'=>$sum_score,'percen'=>$scorepercenuser,'percen_msg'=>$scorepercenuser.' %','exam_id'=>$exam_id_in,);
########################
$dataall=$this->qleuarray($all_arr5,$user_id,$deletekey);
if($task_arr!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
                                   'data'=>$dataall,
                                   'data_user_score_all'=>$user_bar,
                                   //'logimplodeall'=> $logimplodeall,
                                   ),200);
}
else{
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'token'=>null,
                                        ),204);
}
die();
}
public function task2_get(){
// api/upskill/task2?category_id=5&user_id=543622&deletekey=1
####################################################
ob_end_clean();
$module_name='upskill task';
$category_id=@$this->input->get('category_id');
if($category_id==null || $category_id==0){
     $this->response(array('header'=>array(
					  'title'=>'REST_Controller::HTTP_OK',
                            'module'=>$module_name,
					   'message'=>'Error category_id is null',
					   'status'=>TRUE,
					   'code'=>201), 
					   'data'=>null),201);
Die();       
}
$user_id=@$this->input->get('user_id');
if($user_id==null || $user_id==0){$user_id=null;}
if($user_id==null || $user_id==0){
     $this->response(array('header'=>array(
					  'title'=>'REST_Controller::HTTP_OK',
                            'module'=>$module_name,
					   'message'=>'Error user_id is null',
					   'status'=>TRUE,
					   'code'=>201), 
					   'data'=>null),201);
Die();       
}

$type_id=@$this->input->get('type_id');
$item_id=@$this->input->get('item_id');
$page=@$this->input->get('page');
$perpage=@$this->input->get('perpage');
$deletekey=@$this->input->get('deletekey');
$cachetype='4';
#################################################
$formmodel=$this->Model_upskill->get_ups_task($category_id,$item_id,$page,$perpage,$deletekey,$cachetype);
$task=$formmodel['list'];
$task_arr=array();
if(is_array($task)){
foreach($task as $keym =>$val){
$arr=array();
  $task_id=(int)$val->task_id;
  $categoryid=(int)$val->ups_category_id;
  $arr['a']['category_id']=$category_id;
  $arr['a']['task_id']=$task_id;
  $arr['a']['user_id']=$user_id;
  //$arr['a']['type_id']=$type_id;
  //$arr['a']['category_id']=$categoryid;
  $arr['a']['category']=$val->category;
  $arr['a']['task_title']=$val->task_title;
  $arr['a']['task_description']=$val->task_description;
  //$arr['a']['sort']=$val->sort; 
  $arr['a']['status']=$val->status; 
  $arr['a']['create_by_ip']=$val->create_by_ip; 
  //$arr['a']['update_by_ip']=$val->update_by_ip; 
  //$arr['a']['create_by_user']=$val->create_by_user; 
  //$arr['a']['update_by_user']=$val->update_by_user; 
  //$arr['a']['create_date']=$val->create_date; 
  //$arr['a']['update_date']=$val->update_date; 
/*
1 Exam 
2 Cmsblog
3 Learning
4 TV Program
5 Other  
*/
$typeid=null;
$itemid=null;
$item=$this->Model_upskill->get_ups_item($categoryid,$typeid,$task_id,$itemid,$page,$perpage,$user_id,$deletekey,$cachetype);
$itemlist=$item['list'];   
$sql=$item['sql']; 
####################################
$item_arr=array();
if(is_array($itemlist)){
foreach($itemlist as $k2 =>$v){
$arr2=array();
       $item_id=(int)$v->item_id;
       $arr2['b']['user_id']=$user_id;
       $arr2['b']['category']=$v->category;
       $arr2['b']['type_name']=$v->type_name;
       $ups_type_id=(int)$v->ups_type_id;
       $arr2['b']['type_id']=$ups_type_id;
       $arr2['b']['item_id']=$v->item_id;
       $exam_id=(int)$v->item_content_id;
       $arr2['b']['item_content_id']=$exam_id;
       $arr2['b']['item_title']=$v->item_title; 
       $arr2['b']['item_url']=$v->item_url; 
       $arr2['b']['sort']=$v->sort; 
       #$visited=$v->visited; 
     
    
        
#########type 3############
if($ups_type_id==3){
#####################
if($user_id!==null){
#########user_id############
$cachetype=4;
$datalist=$this->Model_upskill->get_log_item_user_type($item_id,$user_id,$ups_type_id,$cachetype,$deletekey);
$datalist=$datalist['list']; 
$datalistrs=$datalist['0'];
$visited=$datalistrs->visited;
if($visited==null){$visited=0;}
$arr2['b']['visited']=(int)$visited;  
if($datalist==null){$arr2['b']['log']=null;}else{$arr2['b']['log']=$datalist['0'];}


#########user_id############
}else{$arr2['b']['log']=null;$arr2['b']['visited']=0;}
#####################
}else{$arr2['b']['log']=null;} 
### type_id 1
if($ups_type_id==1){
     
if($user_id!==null){
     
$examlog=$this->Model_upskill->get_logscore_user_id($exam_id,$user_id,4,$deletekey);
$exam_log_list=$examlog['list']; 
#$arr2['b']['log_exam']=$exam_log_list;

$e_log_arr=array();
if(is_array($exam_log_list)){
foreach($exam_log_list as $k3 =>$v3){
$arr3=array();
$log_id=$v3->log_id;
     $arr3['c']['log_id']=$log_id;
     $arr3['c']['score_exam']=$v3->score_exam;
     $arr3['c']['score_user']=$v3->score_user;
      $chartdata=$this->qleu($exam_id,$user_id,$log_id,$deletekey);
      $arr3['c']['chart']=$chartdata['radar_chart'];
      $arr3['c']['radarchart']=$chartdata['radarchart'];
     $e_log_arr[]=$arr3['c'];
}}
#################################
$logarr=array();
if (is_array($e_log_arr)){foreach($e_log_arr as $ks =>$vs){$arrs=array();$arrs['e']=(int)$vs['log_id'];$logarr[]=$arrs['e'];}}
#################################
$logimplode=implode(",", $logarr);
$logexplode=explode(",", $logimplode);
$arr2['b']['log_implode']=$logimplode;
$arr2['b']['log_explode']=$logexplode;
$arr2['b']['log_exam']=$e_log_arr;
$arr2['b']['log_exam_count']=count($exam_log_list);
#####################################################
#####################################################             
}else{
          $arr2['b']['log_exam']=null;
          $arr2['b']['log_exam_count']=0;
    }   
} 
### type_id 1
 $item_arr[]=$arr2['b'];
 }
}
if($ups_type_id==1){
     $arr['a']['item_count']=0;
     $arr['a']['item']=null;   
}else{
     $item_count=(int)count($item_arr);
     $arr['a']['item_count']=$item_count;
     $arr['a']['item']=$item_arr;    
}
#################################
$logarr1=array();
if (is_array($item_arr)){
foreach($item_arr as $ks1 =>$vs1){
     $arrs1=array();
     $arrs1['f']=(int)$vs1['item_content_id'];
     $logarr1[]=$arrs1['f'];
     }}
//$arr['a']['item_exam_id']=$logarr1;
$logimplode1=implode(",", $logarr1);
$arr['a']['item_implode']=$logimplode1; 
$logexplode1=explode(",", $logimplode1);
$arr['a']['item_explode']=$logexplode1; 
$exam_id_in=$logimplode1;
$qleuarray=$this->qleuarray($exam_id_in,$user_id,$deletekey);
$arr['a']['chart']=$qleuarray;
#################################
if($ups_type_id==3){ 
$visited_arr=array();
if(is_array($item_arr)){
     foreach($item_arr as $k =>$v){
     $arr2=array();
        $visited=(int)$v['visited'];
     if($visited>=1){
          $arrc['c']['visited']=$visited; 
          $visited_arr[]=$arrc['c']; 
        } 
      }
     }  
     //$arr['a']['visited_arr']=$visited_arr;
     $item_count_visited=(int)count($visited_arr);
     $arr['a']['item_count_visited']=$item_count_visited;
     $percent_visited=($item_count_visited*100)/$item_count;
     $percentvisited=(int)round($percent_visited,0,PHP_ROUND_HALF_UP); 
     $arr['a']['visited_percent']=$percentvisited;
     $arr['a']['visit_percent']=$percentvisited.' %';
} 

#################################
  //$arr['a']['item_sql']=$sql;
####################################
  //$arr['a']['item_sql']=$item['sql'];  
  //$arr['a']['sql']=$dataformmodel['sql']; 
  if($type_id==null){
     if($ups_type_id==1){ $task_arr[]=$arr['a']; }  
  }else{
       if($type_id==1 ||$type_id==2 ||$type_id==3){
         if($ups_type_id==$type_id){ $task_arr[]=$arr['a']; }     
       }else{ $task_arr[]=$arr['a']; } 
  }

 }
}
#################################################
$all_arr=array();
if(is_array($task_arr)){
foreach($task_arr as $k =>$v){
$arrc3=array();
     $arrc3['c']['category_id']=$v['category_id'];  
     $arrc3['c']['create_by_ip']=$v['create_by_ip'];  
     $arrc3['c']['user_id']=$v['user_id']; 
     $arrc3['c']['item_explode']=$v['item_explode'];  
     $arrc3['c']['status']=$v['status']; 
 $all_arr[]=$arrc3['c'];
 }
} 
$all_arr4=array();
if(is_array($all_arr)){
foreach($all_arr as $k2 =>$v2){
$arrc4=array();
 $all_arrimplode=$v2['item_explode']; 
 $arrc4['c']=implode(",", $all_arrimplode); 
 $all_arr4[]=$arrc4['c'];
 }
} 
$all_arr5=implode(",", $all_arr4);
$exam_id_in=$all_arr5;
########################
$qtotal=$this->Model_examination->where_question_total_exam_id_in($exam_id_in,$cachetype,$deletekey);
$qtotals=$qtotal['list']['0'];
$question_total=(int)$qtotals->question_total;
$sumscore=$this->Model_examination->where_exam_sumscore_member_in($exam_id_in,$user_id,$cachetype,$deletekey);
$sumscore=$sumscore['list']['0'];
$sum_score=(int)$sumscore->sum_score;
$score_percen=(($sum_score*100)/$question_total);
$scorepercenuser=(int)round($score_percen,0,PHP_ROUND_HALF_UP);  
$user_bar=array('total'=>$question_total,'sumscore'=>$sum_score,'percen'=>$scorepercenuser,'percen_msg'=>$scorepercenuser.' %','exam_id'=>$exam_id_in,);
########################
$exam_id_in=$all_arr5;
$dataall=$this->qleuarray2($exam_id_in,$user_id,$deletekey);
if($task_arr!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
                                   'data'=>$dataall,
                                   //'data_user_score_all'=>$user_bar,
                                   //'logimplodeall'=> $logimplodeall,
                                   ),200);
}
else{
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'token'=>null,
                                        ),204);
}
die();
}
public function qleuarray2($exam_id_in,$user_id,$deletekey){   
$this->load->model('Model_examination', 'Model_examination');
$module_name='examination id';
if($log_id==''){$log_id='';}
$order='asc';$limit='50';$log_id_in=null;
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member_in($exam_id_in,$user_id,$order,$limit,$log_id_in,$deletekey);
//echo'<hr><pre> $exam_scoress=>';print_r($exam_scoress);echo'<pre>';   Die();
$sql_arr=$exam_scoress['sql'];
$exam_list=$exam_scoress['list'];
$exam_data_arr=array();
if(is_array($exam_list)){
foreach($exam_list as $ky=>$v1){
$ar=array();
     $id=(int)$v1->id;
     $logid=(int)$v1->id;
     $exam_id=(int)$v1->exam_id;
      //$ar['v']['key']=$ky;
     $noky=(int)$ky;
     if($noky>=1){$noky=$noky-1;}else{$noky=0;}
     $score_value=$v1->score_value;
     $cnt=$v1->cnt;
     ##############
     $cnt2=$exam_list[$noky]->cnt;
     $score_value2=$exam_list[$noky]->score_value;
     $d_percen2=(($score_value2*100)/$cnt2);
     $percen1=(int)round($d_percen2,0,PHP_ROUND_HALF_UP); 
     $ar['v']['percen1']=$percen1;
     ##############
     $d_percen=(($score_value*100)/$cnt);
     $percen2=(int)round($d_percen,0,PHP_ROUND_HALF_UP); 
     $ar['v']['percen2']=$percen2;
     ##############
     $ar['v']['percen_msg']=$percen2.' %';   
     if($percen2>$percen1){ 
          $status_up='1';
     }elseif($percen2==$percen1){  
          $status_up='0';
     }elseif($percen2<$percen1){
          $status_up='-1';
     }
     $ar['v']['status_compare']=$status_up; 
      //$ar['v']['no']=$no;
      $ar['v']['log_id']=$id;
      $ar['v']['member_id']=$v1->member_id;
      $ar['v']['exam_id']=$exam_id;
      $ar['v']['score_user']=$v1->score_value;
      $ar['v']['date_update']=$v1->date_update;
      $ar['v']['duration_sec']=$v1->duration_sec;
      $ar['v']['exam_type']=$v1->exam_type;
      $ar['v']['user_id']=(int)$v1->user_id;
      $answer_value=$v1->answer_value;
      #$ar['v']['answer_value']=$answer_value;
      $ar['v']['total']=(int)$v1->total;
      $ar['v']['score_total']=(int)$v1->cnt;
      $ar['v']['exam_name']=$v1->exam_name;
      ############################## 
     $qml=$this->Model_examination->questionmaplesson($exam_id,$deletekey);
     $lesson_count=(int)$qml['count'];
     $ar['v']['lesson_count']=$lesson_count;
     //$ar['v']['lesson_sql']=$qml['sql'];
     //$ar['v']['sql']=$sql_arr;
     $ar['v']['exam_id_in']=$exam_id_in;
     ############################## 
     if($lesson_count>0){ $exam_data_arr[]=$ar['v']; }  
     //$exam_data_arr[]=$ar['v'];     
     ############################## 
 
 }
}else{$exam_data_arr=null;}
#######################

//usort($exam_data_arr,function($a,$b){return $a['log_id']-$b['log_id'];});
//usort($exam_data_arr,function($a,$b){return $a['exam_id']-$b['exam_id'];});
###############################
$exam_id_ar=array();
if(is_array($exam_data_arr)){
foreach($exam_data_arr as $k=>$vx){
$ar=array();
$ar['exa']=$vx['exam_id'];
$exam_id_ar[]=$ar['exa'];
}}
$exam_id_implode=implode(",", $exam_id_ar);
###############################
$lesson_data=array();
$lesson_data2=array();
if(is_array($exam_data_arr)){
foreach($exam_data_arr as $ky=>$vy){
$arl=array();
$log_id=(int)$vy['log_id'];
if($ky==0){$ky2=0;}else{ $ky2=$ky-1;}
$log_id1=(int)$exam_data_arr[$ky2]['log_id'];
$user_id=(int)$vy['user_id'];
$exam_id=(int)$vy['exam_id'];
$arl['em']['log_id']=$log_id;
$arl['em']['log_id1']=$log_id1;
$arl['em']['exam_id']=$exam_id;
$arl['em']['exam_name']=$vy['exam_name'];
$arl['em']['user_id']=$user_id;
$arl['em']['implode']=$exam_id_implode; 
################################
$order='asc';
$limit='50';
$exam_id_in=$exam_id_implode;
$log_id_in=$log_id;
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member_in($exam_id_in,$user_id,$order,$limit,$log_id_in,$deletekey);
# echo'<hr><pre> exam_scoress=>';print_r($exam_scoress);echo'<pre>'; Die();
$examscoress=$exam_scoress['list']['0'];
$sql_arr=$exam_scoress['sql'];   
################################   
     //$arl['em']['examscoress']=$examscoress;
$score_all=$examscoress->cnt;
$score_value=$examscoress->score_value;
$arl['em']['score_all']=(int)$score_all;
$arl['em']['score_user']=(int)$score_value;
$answer_value=$examscoress->answer_value;
$answervalue=unserialize($answer_value);  
#################################
$exam_scoress1=$this->Model_examination->where_cvs_course_exam_score_member_in($exam_id_in,$user_id,$order,$limit,$log_id1,$deletekey);
$examscoress1=$exam_scoress1['list']['0'];
$answer_value1=$examscoress1->answer_value;
$answervalue1=unserialize($answer_value1);  
#################################
//$arl['em']['answervalue']=$answervalue;
#################################################
$lessonlist=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_arrayexam_id($exam_id_implode,$deletekey);
#############################################
$lessonlist_arr=array();
     if(is_array($lessonlist)){
     foreach($lessonlist as $k4 =>$v4){
     $arr_l=array();
     $key4=(int)$k4;
     $arr_l['l']['key']=$key4;   
$arr_l['l']['log_id']=$log_id;
$arr_l['l']['log_id1']=$log_id1;
$no=$ky;
$arr_l['l']['no']=$ky;
if($no==0){$no2=0;}else{$no2=$no-1;}
$arr_l['l']['no2']=$no2;
$lesson_id=(int)$v4->lesson_id;
$arr_l['l']['lesson_id']=$lesson_id; 
/*
$lesson_id2=(int)$v4->lesson_id; 
$arr_l['l']['lesson_id2']=$lesson_id2;  
 */            
##################### A1 start       
$map_question=$this->Model_examination->where_mul_map_exam_question_lesson_id($lesson_id);
$map_question_arr=array();
if(is_array($map_question)){
foreach($map_question as $k5 =>$v5){
$arr_m=array();
$question_id=(int)$v5->question_id;
     $arr_m['m']['log_id']=$log_id;
     $arr_m['m']['question_id']=$question_id;
     $arr_m['m']['lesson_id']=(int)$v5->lesson_id;
     $arr_m['m']['exam_id']=(int)$v5->exam_id;         
$userans_arr=array();
if(is_array($answervalue)){
foreach($answervalue as $k=>$w1){
 $ansarr=array();
 $user_question_id=(int)$k;
 $user_ans_id=(int)$w1;
 if($question_id==$user_question_id){    
     $ansarr['ans']['user_question_id']=$user_question_id;
     $ansarr['ans']['user_ans_id']=$user_ans_id;
     $userans_arr[]=$ansarr['ans']; 
     }      
}}
###################################   
$arr_m['m']['userans_arr']=$userans_arr;    
$choice_rss_true=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
$choice_rss_true_list=$choice_rss_true['0'];
//$arr_m['m']['choice_true']=$choice_rss_true_list;
$id_ans_true=(int)$choice_rss_true_list->id_ans;
$id_question_true=(int)$choice_rss_true_list->id_question;
$arr_m['m']['id_question_true']=$id_question_true;
$arr_m['m']['id_ans_true']=$id_ans_true;
#########################
$useransarr=$userans_arr['0'];
$user_question_id=(int)$useransarr['user_question_id'];
$user_ans_id=(int)$useransarr['user_ans_id'];
#########################
$arr_m['m']['user_question_id']=$user_question_id;
$arr_m['m']['user_ans_id']=$user_ans_id;
if($user_ans_id==$id_ans_true){
     $arr_m['m']['status']=1;
     $arr_m['m']['user_mesage']='ตอบถูกได้คะแนน';
}else{
     $arr_m['m']['status']=0; 
     $arr_m['m']['user_mesage']='ตอบผิดไม่ได้คะแนน';}
     $userans_arr_count=count($userans_arr); 
if($userans_arr_count>0){
     $arr_m['m']['userans_arr_count']=$userans_arr_count;  
     $map_question_arr[]=$arr_m['m'];
     }
}}

#######################
$map_question_arr_1=array();
$answervalue_1=$answervalue1;
if(is_array($map_question)){
foreach($map_question as $k_16 =>$v6){
$arr_m_1=array();
$question_id=(int)$v6->question_id;
     $arr_m_1['m1']['log_id']=$log_id1;
     $arr_m_1['m1']['question_id']=$question_id;
     $arr_m_1['m1']['lesson_id']=(int)$v6->lesson_id;
     $arr_m_1['m1']['exam_id']=(int)$v6->exam_id;         
$userans_arr_1=array();
if(is_array($answervalue_1)){
foreach($answervalue_1 as $k_1=>$w_1){
 $ansarr=array();
 $user_question_id=(int)$k_1;
 $user_ans_id=(int)$w_1;
 if($question_id==$user_question_id){    
     $ansarr['ans']['user_question_id']=$user_question_id;
     $ansarr['ans']['user_ans_id']=$user_ans_id;
     $userans_arr_1[]=$ansarr['ans']; 
     }      
}}
###################################   
$arr_m_1['m1']['userans_arr']=$userans_arr_1;    
$choice_rss_true=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
$choice_rss_true_list=$choice_rss_true['0'];
//$arr_m_1['m1']['choice_true']=$choice_rss_true_list;
$id_ans_true=(int)$choice_rss_true_list->id_ans;
$id_question_true=(int)$choice_rss_true_list->id_question;
$arr_m_1['m1']['id_question_true']=$id_question_true;
$arr_m_1['m1']['id_ans_true']=$id_ans_true;
#########################
$useransarr=$userans_arr_1['0'];
$user_question_id=(int)$useransarr['user_question_id'];
$user_ans_id=(int)$useransarr['user_ans_id'];
#########################
$arr_m_1['m1']['user_question_id']=$user_question_id;
$arr_m_1['m1']['user_ans_id']=$user_ans_id;
if($user_ans_id==$id_ans_true){
     $arr_m_1['m1']['status']=1;
     $arr_m_1['m1']['user_mesage']='ตอบถูกได้คะแนน';
}else{
     $arr_m_1['m1']['status']=0; 
     $arr_m_1['m1']['user_mesage']='ตอบผิดไม่ได้คะแนน';}
     $userans_arr_1_count=count($userans_arr_1); 
if($userans_arr_1_count>0){
     $arr_m_1['m1']['userans_arr_count']=$userans_arr_1_count;  
     $map_question_arr_1[]=$arr_m_1['m1'];
     }
}}
$map_question_arr_1_count=(int)count($map_question_arr_1);

#######################

if($map_question_arr!==null){
$map_question_count=(int)count($map_question_arr);
    // $arr_l['l']['map_question']=$map_question_arr;
    // $arr_l['l']['map_question_count']=$map_question_count;
    // $arr_l['l']['map_question1']=$map_question_arr_1;
    // $arr_l['l']['map_question1_count']=$map_question_arr_1_count; 
#####################
$map_uans=array();
if(is_array($map_question_arr)){
foreach($map_question_arr as $kans=>$wans){
     $arranswer=array();
     $status=$wans['status'];
     $arranswer['a']['status']=$wans['status'];
     $arranswer['a']['user_mesage']=$wans['user_mesage'];
     $arranswer['a']['question_id']=$wans['question_id'];
     $arranswer['a']['user_ans_id']=$wans['user_ans_id'];
     $arranswer['a']['id_ans_true']=$wans['id_ans_true'];
     if($status==1){$map_uans[]=$arranswer['a'];} 
 }               
}
$user_score=(int)count($map_uans);
$arr_l['l']['user_score']=$user_score;
$map_uans_1=array();
if(is_array($map_question_arr_1)){
foreach($map_question_arr_1 as $kans_1=>$wans_1){
     $arranswer_1=array();
     $status1=$wans_1['status'];
     $arranswer_1['a1']['status']=$wans_1['status'];
     $arranswer_1['a1']['user_mesage']=$wans_1['user_mesage'];
     $arranswer_1['a1']['question_id']=$wans_1['question_id'];
     $arranswer_1['a1']['user_ans_id']=$wans_1['user_ans_id'];
     $arranswer_1['a1']['id_ans_true']=$wans_1['id_ans_true'];
     if($status1==1){$map_uans_1[]=$arranswer_1['a1'];} 
 }               
}
$user_score1=(int)count($map_uans_1);
$arr_l['l']['user_score1']=$user_score1;

$arr_l['l']['user_score_message']='ได้คะแนน '.$user_score.' คะแนน';
#####################     
}else{$arr_l['l']['map_question']=null;}
##################### A1 End
     $arr_l['l']['log_id']=$log_id;
     $arr_l['l']['exam_id']=$v4->exam_id;
     $arr_l['l']['lesson_name']=$v4->lesson_name; 
     $arr_l['l']['exam_name']=$v4->exam_name;
     $arr_l['l']['mul_level_id']=$v4->mul_level_id;
     $count_question=(int)$v4->count_question;
     $arr_l['l']['count_question']=$count_question;
     $arr_l['l']['question_lesson_all']=$map_question_count;
     $arr_l['l']['question_lesson_user_answer']=$user_score; 
     $user_percen=(($user_score*100)/$map_question_count);
     $userpercen=(int)round($user_percen,0,PHP_ROUND_HALF_UP); 

     $arr_l['l']['question_lesson_1_all']=$map_question_arr_1_count;
     $arr_l['l']['question_lesson_user_1_answer']=$user_score1; 
     $userpercen1=(($user_score1*100)/$map_question_arr_1_count);
     $user_percen1=(int)round($userpercen1,0,PHP_ROUND_HALF_UP); 
     
if($map_question_arr_1_count==0){
     $percen_mesage1=null;
}else{
     $percen_mesage1=$user_percen1.' %';
}

if($map_question_count==0){
          $map_question_count=null;
          $user_score=null;
          $userpercen=null;
          $map_question_count=null;
          $map_question_count=null;
          $trueall=null;
          $status_up=null;
          $percen_mesage=null;
}
else{
          $percen_mesage=$userpercen.' %';
          $trueall=$user_score.'/'.$map_question_count;  
          $percen1=$user_percen1;
          $percen2=$userpercen;
          if($percen2>$percen1){
               $status_up='1';
          }
          elseif($percen2==$percen1){
               $status_up='0';
          }
          elseif($percen2<$percen1){
               $status_up='-1';
          }  
} 

$arr_l['l']['percen1']=$percen1;   
$arr_l['l']['percen2']=$percen2;   

$arr_l['l']['score_all']=$score_all;
$arr_l['l']['score_value']=$score_value;
     $arr_l['l']['countall']=$map_question_count;
     $arr_l['l']['countanswer']=$user_score;
     $arr_l['l']['percen']=$percen2;   
     $arr_l['l']['percen_mesage']=$percen_mesage;
     $arr_l['l']['percen_mesage1']=$percen_mesage1;
     $arr_l['l']['trueall']=$trueall;
     if($map_question_count==null){$status_up=null;}
     
     if($ky==0 || $percen_mesage1==null){
           $arr_l['l']['status_compare']=null; 
     }else{
           $arr_l['l']['status_compare']=$status_up; 
     }

     $lessonlist_arr[]=$arr_l['l'];
}}
##################################################
##################################################
$arl['em']['lessonlist']=$lessonlist_arr;
##############################
$lesson_data[]=$arl['em']['lessonlist'];
$lesson_data2[]=$arl['em'];
}}

###############################

$lessonlist=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_arrayexam_id($exam_id_implode,$deletekey);
$lesson_name=array();
if (is_array($lessonlist)){foreach($lessonlist as $ke =>$vl){$arr=array();
$arr['d']['lesson_name']=$vl->lesson_name;
$lesson_name[]=$arr['d'];
}}

################################### 
/*   
$ans_percen_arr=array();
if(is_array($exam_data_arr)){
foreach($exam_data_arr as $keyval=>$wval){
     $arr_percen=array();
     $percen2=$wval['percen2'];
     $arr_percen['percen']['percen2']=(int)$percen2; 
     $ans_percen_arr[]=$arr_percen['percen']['percen2']; 
 }              
}
$user_percen=$ans_percen_arr;
*/

$score_total_arr=array();
if(is_array($exam_data_arr)){
foreach($exam_data_arr as $kar2=>$wa2){
     $arr_score_total=array();
     $total=$wa2['score_total'];
     $arr_score_total['total']=(int)$total; 
     $score_total_arr[]=$arr_score_total['total']; 
 }              
}

$score_user_arr=array();
if(is_array($exam_data_arr)){
foreach($exam_data_arr as $kar3=>$wa3){
     $arr_score_user=array();
     $score=$wa3['score_user'];
     $arr_score_user['score_user']=(int)$score; 
     $score_user_arr[]=$arr_score_user['score_user']; 
 }              
}
######################
$score_total_arr_plas=0;
foreach($score_total_arr as $m)
$score_total_arr_plas+=$m;
######################
$score_user_total=0;
foreach($score_user_arr as $v)
$score_user_total+=$v;
######################
$config_percen=(int)'55';
$user_score_percen_all=(($score_user_total*100)/$score_total_arr_plas);
$user_score_percen_all1=(int)round($user_score_percen_all,0,PHP_ROUND_HALF_UP); 
$percen_data=array('config_percen'=>$config_percen,
                   'user_percen'=>(int)$user_score_percen_all1,
                   'user_percen_msg'=>$user_score_percen_all1.' %',
                   //'user_total'=>(int)$score_total_arr,
                   'user_total_count'=>(int)$score_total_arr_plas,
                   //'user_score'=>(int)$score_user_arr,
                   'score_user_count'=>(int)$score_user_total,
                   );
###################################   
$scoreuserdesc=$this->Model_examination->where_exam_score_member_in_desc($exam_id_implode,$user_id,$deletekey);
$score_userdesc=$scoreuserdesc['list']['0'];
$score_user=(int)$score_userdesc->score_value_user;
$score_all=(int)$score_userdesc->score_exam_all;
$userpercen=(($score_user*100)/$score_all);
$userpercens=(int)round($userpercen,0,PHP_ROUND_HALF_UP); 
$score_user_desc=array('score_user'=>$score_user,
				   'score_all'=>$score_all,
                       'percen'=>$userpercens,
                       'percen_msg'=>$userpercens.' %',);

$dataall=array('percen_data'=>$percen_data,
               'exam_data'=>$exam_data_arr,
               //'lesson_data_all'=>$lesson_data2,
               'lesson_data'=>$lesson_data,
               'exam_id_implode'=>$exam_id_implode,
               'lesson_name'=>$lesson_name,
               'score_user_last'=>$score_user_desc,
               );
//echo'<hr><pre> $dataall=>';print_r($dataall);echo'<pre>';   Die();
 return $dataall;  
}
public function qleuarray($exam_id_in,$user_id,$deletekey){
 /*   
echo'<hr><pre> exam_id_in=>';print_r($exam_id_in);echo'<pre>';  
echo'<hr><pre> user_id=>';print_r($user_id);echo'<pre>';  
echo'<hr><pre> deletekey=>';print_r($deletekey);echo'<pre>';  //Die();
*/    
$this->load->model('Model_examination', 'Model_examination');
$module_name='examination id';
if($log_id==''){$log_id='';}
$order='asc';
$limit='50';
$log_id_in=null;
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member_in($exam_id_in,$user_id,$order,$limit,$log_id_in,$deletekey);
# echo'<hr><pre> exam_scoress=>';print_r($exam_scoress);echo'<pre>'; Die();
$examscoress=$exam_scoress['list'];
$sql_arr=$exam_scoress['sql'];
$arrym=array();
if(is_array($examscoress)){
$no_num=0;
foreach($examscoress as $k3 =>$v3){
$ar2=array();
     $exam_id=(int)$v3->exam_id;
     $log_id=(int)$v3->id;
     //$ar2['e']['log_id']=$log_id;
     //$ar2['e']['exam_id']=$exam_id;
     //$ar2['e']['score_total']=(int)$v3->cnt;
     //$ar2['e']['score_user']=(int)$v3->score_value;
     $answer_value=$v3->answer_value;
     //$ar2['e']['answer_value']=$answer_value;
     $answervalue=unserialize($answer_value);
     //$ar2['e']['answervalue']=$answervalue;
     
###################################
#####################questionrs#######################
$questionrs=$this->Model_examination->wherequestion($exam_id,$deletekey);
#echo '<pre> $questionrs=>'; print_r($questionrs); echo '</pre>';  Die();
$questionrslist=$questionrs['list']; 
$questionrs_doexam_score= array();
if (is_array($questionrslist)){
foreach($questionrslist as $ke =>$vl){
$arr=array();
     $question_id=(int)$vl->id;
     $arr['d']['question_id']=$question_id;
     $arr['d']['exam_id']=(int)$vl->exam_id;
     $arr['d']['exam_name']=$vl->exam_name;
     $arr['d']['question_detail']=$vl->question_detail;
     $arr['d']['question_encode']=$vl->question_encode;
     $arr['d']['question_score']=$vl->question_score;
     $arr['d']['question_skill']=$vl->question_skill;
     $arr['d']['standard_level']=$vl->standard_level;
$choice_rss_true=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
     $true_choice=$choice_rss_true['0'];
     #$arr['d']['true_choice']=$true_choice;
     $true_answer_id=(int)$true_choice->id_ans;
     $arr['d']['true_answer_id']=$true_answer_id;
     $true_question_id=(int)$true_choice->id_question;
     $arr['d']['true_question_id']=$true_question_id;
     $choice_rss=$this->Model_examination->where_map_choice($question_id,$deletekey);
     ####################
     $choicerss= array();
if (is_array($choice_rss)) {
foreach($choice_rss as $keys =>$w){
$arr2=array();
$ans_id=(int)$w->ans_id;
$answer=$w->answer;
if($answer=='true'){
               $true_ans_id=$ans_id;
               $arr2['a']['true_ans_id']=$ans_id;
               $arr2['a']['true_ans_message']='ตัวเลือกที่ถูกต้อง';
###################################    
$answervalues_arr=array();
if(is_array($answervalue)) {
foreach($answervalue as $key1 =>$w1){
               $arrw1=array();
               $user_q_id=(int)$key1;
               $user_ans_id=(int)$w1;
               $arrw1['b']['user_question_id']=$user_q_id;
               $arrw1['b']['user_ans_id']=$user_ans_id;
if($ans_id==$user_ans_id){
$arr2['a']['user_answer_mesage']='คุณเลือกข้อนี้';
     if($true_ans_id==$user_ans_id){
          $arr2['a']['user_ans_id']=$ans_id;  
          $status=1;
          $arr2['a']['status']=$status; 
          $user_mesage='ตอบถูกได้คะแนน';
          $arr2['a']['user_mesage']=$user_mesage;
          $answervalues_arr[]=$arrw1['b'];
     }               
   }        
}}
###################################   
}else{
$arr2['a']['true_ans_message']='ตัวเลือกที่ไม่ถูกต้อง';
###################################    
$answervalues_arr=array();
if(is_array($answervalue)){
foreach($answervalue as $key1 =>$w1){
               $arrw1=array();
               $user_q_id=(int)$key1;
               $user_ans_id=(int)$w1;
               $arrw1['b']['user_question_id']=$user_q_id;
               $arrw1['b']['user_ans_id']=$user_ans_id;
if($ans_id==$user_ans_id){
$arr2['a']['user_answer_mesage']='คุณเลือกข้อนี้';  
 if($true_ans_id!==$user_ans_id){
     $arr2['a']['user_ans_id']=$ans_id;  
     $status=0;
     $arr2['a']['status']=$status; 
     $user_mesage='ตอบผิดไม่ได้คะแนน';  
     $arr2['a']['user_mesage']=$user_mesage;  
     }              
}
}}
###################################   
}
##########################
          //$arr2['a']['answervalues_arr']=$answervalues_arr;
          $arr2['a']['ans_id']=$ans_id;
          $arr2['a']['answer']=$answer;
          $arr2['a']['detail']=$w->detail;
          $arr2['a']['question_detail']=$w->question_detail;
      $choicerss[]=$arr2['a'];
      }
     }else{$choicerss=null;}
     ####################
     $arr['d']['choice']=$choicerss;
     $arr['d']['score']=$status;
     $arr['d']['score_mesage']=$user_mesage;
 $questionrs_doexam_score[]=$arr['d'];
 }
}else{$questionrs_doexam_score=null;}
#####################questionrs#######################
$examinationrs1=$this->Model_examination->where_course_examination_id($exam_id,$deletekey);
$examinationr=$examinationrs1['list'];
$examinationrs=$examinationr['0'];

$lesson_data=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_arrayexam_id($exam_id_in,$deletekey);
//$lesson_data=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_exam_id($exam_id,$deletekey);
$lesson_arr=array();
if (is_array($lesson_data)){
foreach($lesson_data as $keyl =>$vl){
$arrs=array();
#############################
$exam_id=(int)$vl->exam_id;
$mul_level_id=(int)$vl->mul_level_id; 
$lesson_id=(int)$vl->lesson_id;
$mul_category_id=(int)$vl->mul_category_id;
$mul_education_book_id=(int)$vl->mul_education_book_id; 
$arrs['a']['exam_key']=$keyl;
$arrs['a']['exam_id']=$exam_id;
$arrs['a']['mul_level_id']=$mul_level_id; 
$arrs['a']['lesson_id']=$lesson_id;
$arrs['a']['mul_category_id']=$mul_category_id;
$arrs['a']['mul_education_book_id']=$mul_education_book_id; 
$arrs['a']['book_name']=$vl->book_name;
$arrs['a']['count_lesson']=$vl->count_lesson;
$arrs['a']['count_question']=$vl->count_question;
$arrs['a']['exam_name']=$vl->exam_name;
$arrs['a']['lesson_name']=$vl->lesson_name;
$arrs['a']['mul_category_name']=$vl->mul_category_name; 
$arrs['a']['mul_level_name']=$vl->mul_level_name; 
$map_question=$this->Model_examination->where_mul_map_exam_question_lesson_id($lesson_id);
$mapquestion_arr=array();
if (is_array($map_question)){
foreach($map_question as $keys =>$vlw){
     $ar=array();
     $id=(int)$vlw->id;
     $idx=(int)$vlw->idx;
     $map_lesson_id=(int)$vlw->lesson_id;
     $map_question_id=(int)$vlw->question_id;
     $ar['a']['id']=$id;
     $ar['a']['idx']=$idx;
     $ar['a']['map_lesson_id']=$map_lesson_id;
     $ar['a']['map_question_id']=$map_question_id;
     $ar['a']['standard_level']=$vlw->standard_level;
     $ar['a']['question_detail']=$vlw->question_detail;
     ###############################
################################
$qdoexamscore_arr=array();
if (is_array($questionrs_doexam_score)){
foreach($questionrs_doexam_score as $keys2 =>$vlw2){
     $keys2=$keys2+1;
     $ar3=array();
     $exam_id=(int)$vlw2['exam_id'];
     $question_id=(int)$vlw2['question_id'];
     $ar3['c']['no']=$keys2;
     $ar3['c']['exam_id']=$exam_id;
     $ar3['c']['question_id']=$question_id;
     $ar3['c']['score']=$vlw2['score'];
     $ar3['c']['score_mesage']=$vlw2['score_mesage'];
     if($map_question_id==$question_id){$qdoexamscore_arr[]=$ar3['c'];}
}}
################################
$qdoexamscore_arr_count=count($qdoexamscore_arr);
$qdoexamscorearr=$qdoexamscore_arr['0'];
#$ar['a']['doexamscore']=$qdoexamscorearr;
$ar['a']['no']=$qdoexamscorearr['no'];
$ar['a']['score']=$qdoexamscorearr['score'];
$ar['a']['score_mesage']=$qdoexamscorearr['score_mesage'];
##############################
if($qdoexamscore_arr_count>0){$mapquestion_arr[]=$ar['a'];}
 
 }
}else{$mapquestion_arr=null;}
$user_answer_count=(int)count($mapquestion_arr);
$arrs['a']['user_answer_count']=$user_answer_count;
$arrs['a']['user_answer']=$mapquestion_arr;
################################
$usersanscore_arr=array();
if(is_array($mapquestion_arr)){
foreach($mapquestion_arr as $k=>$n){
$ar3=array();
$score=(int)$n['score'];
$no=(int)$n['no'];
$a['d']['no']=$no;
$a['d']['score']=$score;
$a['d']['question_id']=$n['map_question_id'];
if($score==1){$usersanscore_arr[]=$a['d']; }
}}
################################
$useranswer_true_count=(int)count($usersanscore_arr);
#$arrs['a']['useranswer_true']=$usersanscore_arr;
$arrs['a']['useranswer_true_count']=$useranswer_true_count;
$arrs['a']['useranswer_all_count']=$useranswer_true_count;
$arrs['a']['useranswer_scoreall_true']=$useranswer_true_count.'/'.$user_answer_count;
$d_percen=(($useranswer_true_count*100)/$user_answer_count);
$useranswer_percen=(int)round($d_percen,0,PHP_ROUND_HALF_UP);  
$arrs['a']['useranswer_percen']=$useranswer_percen;
$arrs['a']['useranswer_percen_mesage']=$useranswer_percen.' %';
$map_content=$this->Model_examination->get_where_mul_map_content_lessonid_join($lesson_id);
$arrs['a']['lesson_content']=$map_content; 
#############################
$lesson_arr[]=$arrs['a'];
}}else{$lesson_arr=null;}
################################
$radarchart_arr=array();
if(is_array($lesson_arr)){
foreach($lesson_arr as $k1=>$n1){
$ar3=array();

$all_count=(int)$n1['user_answer_count'];
if($all_count==0){ $all_count=null;}
$user_answer_count=(int)$n1['useranswer_all_count'];
if($all_count==0 && $user_answer_count==0){ $user_answer_count=null;}
$percen=$n1['useranswer_percen'];
if($all_count==0 && $percen==0){ $percen=null;}
$percen_mesage=$n1['useranswer_percen_mesage'];
if($all_count==0 && $percen_mesage==0){ $percen_mesage=null;}
$trueall=$n1['useranswer_scoreall_true'];
if($all_count==0 && $trueall==0){ $trueall=null;}
$b['e']['key']=$k1;
$b['e']['lesson_name']=$n1['lesson_name'];
$b['e']['countanswer']=$user_answer_count;
$b['e']['countall']=$all_count;
$b['e']['percen']=$percen;
$b['e']['percen_mesage']=$percen_mesage;
$b['e']['trueall']=$trueall;
$b['e']['exam_name']=$n1['exam_name'];
$b['e']['count_question']=$n1['count_question'];
$b['e']['exam_id']=$n1['exam_id'];
$radarchart_arr[]=$b['e']; 
}}
################################
#########################################################


######################### poor_ar########################
$poor_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $vla1) {
        $percen1=(int)$vla1['percen'];
       if($percen1<=49){
           $poor['poor']['lesson_name']=$vla1['lesson_name'];
           $poor['poor']['percen']=$percen1;
           $poor['poor']['count_true']=$vla1['countanswer'];
           $poor['poor']['count_all']=$vla1['countall']; 
           $poor['poor']['msg']=$vla1['trueall']; 
           $poor['poor']['percen_mesage']=$vla1['percen_mesage'];
           $poor_ar[]=$poor['poor'];  
   }    
 }    
}
######################### fair_ar########################
$fair_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $vla2) {
        $percen2=(int)$vla2['percen'];
        if($percen2>=50 && $percen2<=79){
           $fair['fair']['lesson_name']=$vla2['lesson_name'];
           $fair['fair']['percen']=$percen2;
           $fair['fair']['count_true']=$vla2['countanswer'];
           $fair['fair']['count_all']=$vla2['countall']; 
           $fair['fair']['msg']=$vla2['trueall']; 
           $fair['fair']['percen_mesage']=$vla2['percen_mesage'];
           $fair_ar[]=$fair['fair'];  
   }    
 }    
}
######################### good###########################
$good_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $vla3) {
        $percen3=(int)$vla3['percen'];
        if($percen3>=80){
           $good['good']['lesson_name']=$vla3['lesson_name'];
           $good['good']['percen']=$percen3;
           $good['good']['count_true']=$vla3['countanswer'];
           $good['good']['count_all']=$vla3['countall']; 
           $good['good']['msg']=$vla3['trueall']; 
           $good['good']['percen_mesage']=$vla3['percen_mesage'];
           $good_ar[]=$good['good'];  
   }    
 }    
}
$datast=array('poor'=>$poor_ar,'fair'=>$fair_ar,'good'=>$good_ar);
$condition_chart=$datast;
#########################################################

if($radarchart_arr){
   $statradarchart=array();
   foreach ($radarchart_arr as $vlalue) {
         $percen=$vlalue['percen'];
         $percentscore1=(int)round($percen,0,PHP_ROUND_HALF_UP);  
              $statradarchart[$vlalue['lesson_name']]=$percentscore1;
     }
}
$user_score=$examscoress->score_value;
$scoredisplayradarchart1=$statradarchart;
/*
$dataall=array('user_id'=>(int)$user_id,
               //'examination'=>$examinationrs,
               'exam_id'=>(int)$examinationrs->id,
               'log_id'=>(int)$log_id,
               'user_score'=>(int)$user_score,
               //'lesson'=>$lesson_arr,
               //'lesson_count'=>count($lesson_arr),
               'radarchart'=>$scoredisplayradarchart1,
               'radar_chart'=>$radarchart_arr,
               );
*/ 
#####################################################
//$ar2['e']['user_id']=(int)$user_id;
//$ar2['e']['exam_id']=(int)$examinationrs->id;
//$ar2['e']['log_id']=(int)$log_id;
//$ar2['e']['user_score']=(int)$user_score;
//$ar2['e']['user_id']=(int)$user_id;
//$ar2['e']['radarchart']=$scoredisplayradarchart1;
//$ar2['e']['radarchart_arr']=$radarchart_arr;
$radarchart_arr_count=count($radarchart_arr);
if($radarchart_arr_count>0){  
////////////////////
$rad=array();
if(is_array($radarchart_arr)) {
foreach($radarchart_arr as $k => $m) {
$na=array();
//$na['n']['key']=$k;
$na['n']['lesson_name']=$m['lesson_name'];
$na['n']['countanswer']=$m['countanswer'];
$na['n']['countall']=$m['countall'];
$na['n']['percen']=$m['percen'];
$na['n']['percen_mesage']=$m['percen_mesage'];
$na['n']['trueall']=$m['trueall'];
$na['n']['exam_name']=$m['exam_name'];
$na['n']['count_question']=$m['count_question'];
$na['n']['exam_id']=$m['exam_id'];
$rad[]=$na['n']; 
 }
}else{ $rad=null; }
     $ar3['f']['no']=$k;
     $ar3['f']['radarchart']=$rad;
     $arrym3[]=$ar3['f'];
}
$arrym3count=count($arrym3);
$rada=array();
foreach($arrym3 as $k1 => $m1) {
$da=array();
$da['n']['no']=$k1;
$ra=$m1['radarchart'];
##############
$rad2=array();
if(is_array($ra)) {
foreach($ra as $k2 => $m2) {
$na2=array();
$no=$k1;
$na2['n']['no']=$no;
if($no==0){
      $no1=(int)$no;
     $percen1=(int)$no1['percen'];
     $r1=array('percen'=>$percen1);  
}else{
     $no1=(int)$no-1;
     $percen1=(int)$no1['percen'];
     $r1=array('percen'=>$percen1);    
}
$percen2=$m2['percen'];
if($percen2==null){
  $na2['n']['status_compare']=null;   
}else{
 if($no>0){
     $percen2=(int)$percen2;
     if($percen2>$percen1){ 
          $status_up='1';
     }elseif($percen2==$percen1){ 
          $status_up='0';
     }elseif($percen2<$percen1){ 
          $status_up='-1';}
     $na2['n']['status_compare']=$status_up; 
   }else{$na2['n']['status_compare']=null;}    
}

$na2['n']['percen_'.$no1]=$percen1;
$na2['n']['lesson_name']=$m2['lesson_name'];
$na2['n']['countanswer']=$m2['countanswer'];
$na2['n']['countall']=$m2['countall'];
$na2['n']['percen']=$percen2;
$na2['n']['percen_mesage']=$m2['percen_mesage'];
$na2['n']['trueall']=$m2['trueall'];
$na2['n']['exam_name']=$m2['exam_name'];
$na2['n']['count_question']=$m2['count_question'];
$na2['n']['exam_id']=$m2['exam_id'];
$rad2[]=$na2['n']; 
}}
##############
$da['n']=$rad2;
$rada[]=$da['n']; 
 }
if($radarchart_arr_count>0){
     $no=(int)$no_num;
     $ar2['e']['key']=$k3;
     $ar2['e']['no']=$no;
     $ar2['e']['log_id']=$log_id;
     $ar2['e']['exam_id']=$exam_id;
     $ar2['e']['exam_name']=$v3->exam_name;
     $cnt=(int)$v3->cnt;
     $ar2['e']['score_total']=$cnt;
     $score_value=(int)$v3->score_value;
     $ar2['e']['score_user']=$score_value;
     $d_percen=(($score_value*100)/$cnt);
     $u_percen=(int)round($d_percen,0,PHP_ROUND_HALF_UP);  
     $ar2['e']['percen']=$u_percen;
     $ar2['e']['percen_msg']=$u_percen.' %';
     $arrym[]=$ar2['e'];
     $no_num++;
}
if($radarchart_arr_count>0){
  $lessonlist=$radarchart_arr;
   if($lessonlist){
   $lesson_name=array();
   foreach ($lessonlist as $vlalue) {
          $lesson_ar=array();
          $lesson_ar['ar']['lesson_name']=$vlalue['lesson_name'];
          $lesson_name[]=$lesson_ar['ar']; 
     }
}
     
     
}
// $ar2['e']['sql']=$sql_arr;
//$ar2['e']['qs']=$dataall;
################################### 
 
 }
}
  
//echo'<hr><pre> $arrym=>';print_r($arrym);echo'<pre>';   Die();
 usort($arrym,function($a, $b){
    return $a['log_id']-$b['log_id'];
});

$arrym_ar=array();
if($arrym){ 
foreach($arrym as $kw=> $vw){    
     $ar['ar']['percen_'.$no]=$percen2; 
     $ar['ar']['exam_id']=$vw['exam_id'];
     $ar['ar']['exam_name']=$vw['exam_name'];
     $ar['ar']['key']=$kw;
     $ar['ar']['log_id']=$vw['log_id']; 
     $percen1=$vw['percen']; 
     $ar['ar']['percen']=$percen1; 
     if($kw==0){$na=0;}else{$na=$kw-1;}
     $percen2=$arrym[$na]['percen'];
     $ar['ar']['percen2']=$percen2; 
/*
if($percen2==null){
  $ar['ar']['status_compare']=null;   
}else{
 if($no>0){
     $percen2=(int)$percen2;
     if($percen2>$percen1){ 
          $status_up='1';
     }elseif($percen2==$percen1){ 
          $status_up='0';
     }elseif($percen2<$percen1){ 
          $status_up='-1';}
     $ar['ar']['status_compare']=$status_up; 
   }else{$ar['ar']['status_compare']=null;}    
}
     
*/     

     $ar['ar']['percen_msg']=$vw['percen_msg']; 
     $ar['ar']['score_total']=$vw['score_total']; 
     $ar['ar']['score_user']=$vw['score_user']; 

##############################         
 $arrym_ar[]=$ar['ar'];  
 }    
}

$dataall=array('exam_data'=>$arrym_ar,
               'lesson_data'=>$rada,
               'lesson_name'=>$lesson_name);
#echo'<hr><pre> $dataall=>';print_r($dataall);echo'<pre>';   Die();
 return $dataall;  
}
public function qleuarrays($exam_id_in,$user_id,$deletekey){
$dataall=$this->qleuarray($exam_id_in,$user_id,$deletekey);
 return $dataall; 
}
public function qleu($exam_id,$user_id,$log_id,$deletekey){
$this->load->model('Model_examination', 'Model_examination');
$module_name='examination id';
if($log_id==''){$log_id='';}
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member($exam_id,$user_id,$order,$limit,$log_id);
$examscoress=$exam_scoress['list']['0'];
$sqlexam=$exam_scoress['sql'];
$answer_value=$examscoress->answer_value;
$answervalue=unserialize($answer_value);

#####################questionrs#######################
$questionrs=$this->Model_examination->wherequestion($exam_id,$deletekey);
#echo '<pre> $questionrs=>'; print_r($questionrs); echo '</pre>';  Die();
$questionrslist=$questionrs['list']; 
$questionrs_doexam_score= array();
if (is_array($questionrslist)){
foreach($questionrslist as $ke =>$vl){
$arr=array();
     $question_id=(int)$vl->id;
     $arr['d']['question_id']=$question_id;
     $arr['d']['exam_id']=(int)$vl->exam_id;
     $arr['d']['exam_name']=$vl->exam_name;
     $arr['d']['question_detail']=$vl->question_detail;
     $arr['d']['question_encode']=$vl->question_encode;
     $arr['d']['question_score']=$vl->question_score;
     $arr['d']['question_skill']=$vl->question_skill;
     $arr['d']['standard_level']=$vl->standard_level;
$choice_rss_true=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
     $true_choice=$choice_rss_true['0'];
     #$arr['d']['true_choice']=$true_choice;
     $true_answer_id=(int)$true_choice->id_ans;
     $arr['d']['true_answer_id']=$true_answer_id;
     $true_question_id=(int)$true_choice->id_question;
     $arr['d']['true_question_id']=$true_question_id;
     $choice_rss=$this->Model_examination->where_map_choice($question_id,$deletekey);
     ####################
     $choicerss= array();
if (is_array($choice_rss)) {
foreach($choice_rss as $keys =>$w){
$arr2=array();
$ans_id=(int)$w->ans_id;
$answer=$w->answer;
if($answer=='true'){
               $true_ans_id=$ans_id;
               $arr2['a']['true_ans_id']=$ans_id;
               $arr2['a']['true_ans_message']='ตัวเลือกที่ถูกต้อง';
###################################    
$answervalues_arr=array();
if(is_array($answervalue)) {
foreach($answervalue as $key1 =>$w1){
               $arrw1=array();
               $user_q_id=(int)$key1;
               $user_ans_id=(int)$w1;
               $arrw1['b']['user_question_id']=$user_q_id;
               $arrw1['b']['user_ans_id']=$user_ans_id;
if($ans_id==$user_ans_id){
$arr2['a']['user_answer_mesage']='คุณเลือกข้อนี้';
     if($true_ans_id==$user_ans_id){
          $arr2['a']['user_ans_id']=$ans_id;  
          $status=1;
          $arr2['a']['status']=$status; 
          $user_mesage='ตอบถูกได้คะแนน';
          $arr2['a']['user_mesage']=$user_mesage;
          $answervalues_arr[]=$arrw1['b'];
     }               
   }        
}}
###################################   
}else{
$arr2['a']['true_ans_message']='ตัวเลือกที่ไม่ถูกต้อง';
###################################    
$answervalues_arr=array();
if(is_array($answervalue)){
foreach($answervalue as $key1 =>$w1){
               $arrw1=array();
               $user_q_id=(int)$key1;
               $user_ans_id=(int)$w1;
               $arrw1['b']['user_question_id']=$user_q_id;
               $arrw1['b']['user_ans_id']=$user_ans_id;
if($ans_id==$user_ans_id){
$arr2['a']['user_answer_mesage']='คุณเลือกข้อนี้';  
 if($true_ans_id!==$user_ans_id){
     $arr2['a']['user_ans_id']=$ans_id;  
     $status=0;
     $arr2['a']['status']=$status; 
     $user_mesage='ตอบผิดไม่ได้คะแนน';  
     $arr2['a']['user_mesage']=$user_mesage;  
     }              
}
}}
###################################   
}
##########################
          //$arr2['a']['answervalues_arr']=$answervalues_arr;
          $arr2['a']['ans_id']=$ans_id;
          $arr2['a']['answer']=$answer;
          $arr2['a']['detail']=$w->detail;
          $arr2['a']['question_detail']=$w->question_detail;
      $choicerss[]=$arr2['a'];
      }
     }else{$choicerss=null;}
     ####################
     $arr['d']['choice']=$choicerss;
     $arr['d']['score']=$status;
     $arr['d']['score_mesage']=$user_mesage;
 $questionrs_doexam_score[]=$arr['d'];
 }
}else{$questionrs_doexam_score=null;}
#####################questionrs#######################
$examinationrs1=$this->Model_examination->where_course_examination_id($exam_id,$deletekey);
$examinationr=$examinationrs1['list'];
$examinationrs=$examinationr['0'];
$lesson_data=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_exam_id($exam_id,$deletekey);
$lesson_arr=array();
if (is_array($lesson_data)){
foreach($lesson_data as $keyl =>$vl){
$arrs=array();
#############################
$exam_id=(int)$vl->exam_id;
$mul_level_id=(int)$vl->mul_level_id; 
$lesson_id=(int)$vl->lesson_id;
$mul_category_id=(int)$vl->mul_category_id;
$mul_education_book_id=(int)$vl->mul_education_book_id; 
$arrs['a']['exam_id']=$exam_id;
$arrs['a']['mul_level_id']=$mul_level_id; 
$arrs['a']['lesson_id']=$lesson_id;
$arrs['a']['mul_category_id']=$mul_category_id;
$arrs['a']['mul_education_book_id']=$mul_education_book_id; 
$arrs['a']['book_name']=$vl->book_name;
$arrs['a']['count_lesson']=$vl->count_lesson;
$arrs['a']['count_question']=$vl->count_question;
$arrs['a']['exam_name']=$vl->exam_name;
$arrs['a']['lesson_name']=$vl->lesson_name;
$arrs['a']['mul_category_name']=$vl->mul_category_name; 
$arrs['a']['mul_level_name']=$vl->mul_level_name; 
$map_question=$this->Model_examination->where_mul_map_exam_question_lesson_id($lesson_id);
$mapquestion_arr=array();
if (is_array($map_question)){
foreach($map_question as $keys =>$vlw){
     $ar=array();
     $id=(int)$vlw->id;
     $idx=(int)$vlw->idx;
     $map_lesson_id=(int)$vlw->lesson_id;
     $map_question_id=(int)$vlw->question_id;
     $ar['a']['id']=$id;
     $ar['a']['idx']=$idx;
     $ar['a']['map_lesson_id']=$map_lesson_id;
     $ar['a']['map_question_id']=$map_question_id;
     $ar['a']['standard_level']=$vlw->standard_level;
     $ar['a']['question_detail']=$vlw->question_detail;
     ###############################
################################
$qdoexamscore_arr=array();
if (is_array($questionrs_doexam_score)){
foreach($questionrs_doexam_score as $keys2 =>$vlw2){
     $keys2=$keys2+1;
     $ar3=array();
     $exam_id=(int)$vlw2['exam_id'];
     $question_id=(int)$vlw2['question_id'];
     $ar3['c']['no']=$keys2;
     $ar3['c']['exam_id']=$exam_id;
     $ar3['c']['question_id']=$question_id;
     $ar3['c']['score']=$vlw2['score'];
     $ar3['c']['score_mesage']=$vlw2['score_mesage'];
     if($map_question_id==$question_id){$qdoexamscore_arr[]=$ar3['c'];}
}}
################################
$qdoexamscore_arr_count=count($qdoexamscore_arr);
$qdoexamscorearr=$qdoexamscore_arr['0'];
#$ar['a']['doexamscore']=$qdoexamscorearr;
$ar['a']['no']=$qdoexamscorearr['no'];
$ar['a']['score']=$qdoexamscorearr['score'];
$ar['a']['score_mesage']=$qdoexamscorearr['score_mesage'];
##############################
if($qdoexamscore_arr_count>0){$mapquestion_arr[]=$ar['a'];}
 
 }
}else{$mapquestion_arr=null;}
$user_answer_count=(int)count($mapquestion_arr);
$arrs['a']['user_answer_count']=$user_answer_count;
$arrs['a']['user_answer']=$mapquestion_arr;
################################
$usersanscore_arr=array();
if(is_array($mapquestion_arr)){
foreach($mapquestion_arr as $k=>$n){
$ar3=array();
$score=(int)$n['score'];
$no=(int)$n['no'];
$a['d']['no']=$no;
$a['d']['score']=$score;
$a['d']['question_id']=$n['map_question_id'];
if($score==1){$usersanscore_arr[]=$a['d']; }
}}
################################
$useranswer_true_count=(int)count($usersanscore_arr);
#$arrs['a']['useranswer_true']=$usersanscore_arr;
$arrs['a']['useranswer_true_count']=$useranswer_true_count;
$arrs['a']['useranswer_all_count']=$useranswer_true_count;
$arrs['a']['useranswer_scoreall_true']=$useranswer_true_count.'/'.$user_answer_count;
$d_percen=(($useranswer_true_count*100)/$user_answer_count);
$arrs['a']['useranswer_percen']=$d_percen;
$arrs['a']['useranswer_percen_mesage']=$d_percen.'%';
$map_content=$this->Model_examination->get_where_mul_map_content_lessonid_join($lesson_id);
$arrs['a']['lesson_content']=$map_content; 
#############################
$lesson_arr[]=$arrs['a'];
}}else{$lesson_arr=null;}
################################
$radarchart_arr=array();
if(is_array($lesson_arr)){
foreach($lesson_arr as $k1=>$n1){
$ar3=array();
$all_count=(int)$n1['user_answer_count'];
$user_answer_count=(int)$n1['useranswer_all_count'];
$b['e']['lesson_name']=$n1['lesson_name'];
$b['e']['countanswer']=$user_answer_count;
$b['e']['countall']=$all_count;
$b['e']['percen']=$n1['useranswer_percen'];
$b['e']['percen_mesage']=$n1['useranswer_percen_mesage'];
$b['e']['trueall']=$n1['useranswer_scoreall_true'];
$radarchart_arr[]=$b['e']; 
}}
################################
#########################################################


######################### poor_ar########################
$poor_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $vla1) {
        $percen1=(int)$vla1['percen'];
       if($percen1<=49){
           $poor['poor']['lesson_name']=$vla1['lesson_name'];
           $poor['poor']['percen']=$percen1;
           $poor['poor']['count_true']=$vla1['countanswer'];
           $poor['poor']['count_all']=$vla1['countall']; 
           $poor['poor']['msg']=$vla1['trueall']; 
           $poor['poor']['percen_mesage']=$vla1['percen_mesage'];
           $poor_ar[]=$poor['poor'];  
   }    
 }    
}
######################### fair_ar########################
$fair_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $vla2) {
        $percen2=(int)$vla2['percen'];
        if($percen2>=50 && $percen2<=79){
           $fair['fair']['lesson_name']=$vla2['lesson_name'];
           $fair['fair']['percen']=$percen2;
           $fair['fair']['count_true']=$vla2['countanswer'];
           $fair['fair']['count_all']=$vla2['countall']; 
           $fair['fair']['msg']=$vla2['trueall']; 
           $fair['fair']['percen_mesage']=$vla2['percen_mesage'];
           $fair_ar[]=$fair['fair'];  
   }    
 }    
}
######################### good###########################
$good_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $vla3) {
        $percen3=(int)$vla3['percen'];
        if($percen3>=80){
           $good['good']['lesson_name']=$vla3['lesson_name'];
           $good['good']['percen']=$percen3;
           $good['good']['count_true']=$vla3['countanswer'];
           $good['good']['count_all']=$vla3['countall']; 
           $good['good']['msg']=$vla3['trueall']; 
           $good['good']['percen_mesage']=$vla3['percen_mesage'];
           $good_ar[]=$good['good'];  
   }    
 }    
}
$datast=array('poor'=>$poor_ar,'fair'=>$fair_ar,'good'=>$good_ar);
$condition_chart=$datast;
#########################################################

if($radarchart_arr){
   $statradarchart=array();
   foreach ($radarchart_arr as $vlalue) {
         $percen=$vlalue['percen'];
         $percentscore1=(int)round($percen,0,PHP_ROUND_HALF_UP);  
              $statradarchart[$vlalue['lesson_name']]=$percentscore1;
     }
}
$user_score=$examscoress->score_value;
$scoredisplayradarchart1=$statradarchart;
$dataall=array('user_id'=>(int)$user_id,
               //'examination'=>$examinationrs,
               'exam_id'=>(int)$examinationrs->id,
               'log_id'=>(int)$log_id,
               'user_score'=>(int)$user_score,
               //'lesson'=>$lesson_arr,
               //'lesson_count'=>count($lesson_arr),
               'radarchart'=>$scoredisplayradarchart1,
               'radar_chart'=>$radarchart_arr,
               );
#####################################################

 return $dataall;   
}
public function questionlessonexamuser_get(){
ob_end_clean();
$this->load->model('Model_examination', 'Model_examination');
$exam_id=@$this->input->get('exam_id');
$log_id=@$this->input->get('log_id');
$deletekey=@$this->input->get('deletekey');
$module_name='examination id';
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> null),200);
exit();
}
$user_id=@$this->input->get('user_id');
$log_id=@$this->input->get('log_id');
if($log_id==''){$log_id='';}
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member($exam_id,$user_id,$order,$limit,$log_id);
$examscoress=$exam_scoress['list']['0'];
$sqlexam=$exam_scoress['sql'];
$answer_value=$examscoress->answer_value;
$answervalue=unserialize($answer_value);
#####################questionrs#######################
$questionrs=$this->Model_examination->wherequestion($exam_id,$deletekey);
#echo '<pre> $questionrs=>'; print_r($questionrs); echo '</pre>';  Die();
$questionrslist=$questionrs['list']; 
$questionrs_doexam_score= array();
if (is_array($questionrslist)){
foreach($questionrslist as $key =>$v){
$arr=array();
     $question_id=(int)$v->id;
     $arr['d']['question_id']=$question_id;
     $arr['d']['exam_id']=(int)$v->exam_id;
     $arr['d']['exam_name']=$v->exam_name;
     $arr['d']['question_detail']=$v->question_detail;
     $arr['d']['question_encode']=$v->question_encode;
     $arr['d']['question_score']=$v->question_score;
     $arr['d']['question_skill']=$v->question_skill;
     $arr['d']['standard_level']=$v->standard_level;
$choice_rss_true=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
     $true_choice=$choice_rss_true['0'];
     #$arr['d']['true_choice']=$true_choice;
     $true_answer_id=(int)$true_choice->id_ans;
     $arr['d']['true_answer_id']=$true_answer_id;
     $true_question_id=(int)$true_choice->id_question;
     $arr['d']['true_question_id']=$true_question_id;
     $choice_rss=$this->Model_examination->where_map_choice($question_id,$deletekey);
     ####################
     $choicerss= array();
if (is_array($choice_rss)) {
foreach($choice_rss as $keys =>$w){
$arr2=array();
$ans_id=(int)$w->ans_id;
$answer=$w->answer;
if($answer=='true'){
               $true_ans_id=$ans_id;
               $arr2['a']['true_ans_id']=$ans_id;
               $arr2['a']['true_ans_message']='ตัวเลือกที่ถูกต้อง';
###################################    
$answervalues_arr=array();
if(is_array($answervalue)) {
foreach($answervalue as $key1 =>$w1){
               $arrw1=array();
               $user_q_id=(int)$key1;
               $user_ans_id=(int)$w1;
               $arrw1['b']['user_question_id']=$user_q_id;
               $arrw1['b']['user_ans_id']=$user_ans_id;
if($ans_id==$user_ans_id){
$arr2['a']['user_answer_mesage']='คุณเลือกข้อนี้';
     if($true_ans_id==$user_ans_id){
          $arr2['a']['user_ans_id']=$ans_id;  
          $status=1;
          $arr2['a']['status']=$status; 
          $user_mesage='ตอบถูกได้คะแนน';
          $arr2['a']['user_mesage']=$user_mesage;
          $answervalues_arr[]=$arrw1['b'];
     }               
   }        
}}
###################################   
}else{
$arr2['a']['true_ans_message']='ตัวเลือกที่ไม่ถูกต้อง';
###################################    
$answervalues_arr=array();
if(is_array($answervalue)){
foreach($answervalue as $key1 =>$w1){
               $arrw1=array();
               $user_q_id=(int)$key1;
               $user_ans_id=(int)$w1;
               $arrw1['b']['user_question_id']=$user_q_id;
               $arrw1['b']['user_ans_id']=$user_ans_id;
if($ans_id==$user_ans_id){
$arr2['a']['user_answer_mesage']='คุณเลือกข้อนี้';  
 if($true_ans_id!==$user_ans_id){
     $arr2['a']['user_ans_id']=$ans_id;  
     $status=0;
     $arr2['a']['status']=$status; 
     $user_mesage='ตอบผิดไม่ได้คะแนน';  
     $arr2['a']['user_mesage']=$user_mesage;  
     }              
}
}}
###################################   
}
##########################
          //$arr2['a']['answervalues_arr']=$answervalues_arr;
          $arr2['a']['ans_id']=$ans_id;
          $arr2['a']['answer']=$answer;
          $arr2['a']['detail']=$w->detail;
          $arr2['a']['question_detail']=$w->question_detail;
      $choicerss[]=$arr2['a'];
      }
     }else{$choicerss=null;}
     ####################
     $arr['d']['choice']=$choicerss;
     $arr['d']['score']=$status;
     $arr['d']['score_mesage']=$user_mesage;
 $questionrs_doexam_score[]=$arr['d'];
 }
}else{$questionrs_doexam_score=null;}
#####################questionrs#######################
$examinationrs1=$this->Model_examination->where_course_examination_id($exam_id,$deletekey);
$examinationr=$examinationrs1['list'];
$examinationrs=$examinationr['0'];
$lesson_data=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_exam_id($exam_id,$deletekey);
$lesson_arr=array();
if (is_array($lesson_data)){
foreach($lesson_data as $key =>$v){
$arrs=array();
#############################
$exam_id=(int)$v->exam_id;
$mul_level_id=(int)$v->mul_level_id; 
$lesson_id=(int)$v->lesson_id;
$mul_category_id=(int)$v->mul_category_id;
$mul_education_book_id=(int)$v->mul_education_book_id; 
$arrs['a']['exam_id']=$exam_id;
$arrs['a']['mul_level_id']=$mul_level_id; 
$arrs['a']['lesson_id']=$lesson_id;
$arrs['a']['mul_category_id']=$mul_category_id;
$arrs['a']['mul_education_book_id']=$mul_education_book_id; 
$arrs['a']['book_name']=$v->book_name;
$arrs['a']['count_lesson']=$v->count_lesson;
$arrs['a']['count_question']=$v->count_question;
$arrs['a']['exam_name']=$v->exam_name;
$arrs['a']['lesson_name']=$v->lesson_name;
$arrs['a']['mul_category_name']=$v->mul_category_name; 
$arrs['a']['mul_level_name']=$v->mul_level_name; 
$map_question=$this->Model_examination->where_mul_map_exam_question_lesson_id($lesson_id);
$mapquestion_arr=array();
if (is_array($map_question)){
foreach($map_question as $keys =>$vw){
     $ar=array();
     $id=(int)$vw->id;
     $idx=(int)$vw->idx;
     $map_lesson_id=(int)$vw->lesson_id;
     $map_question_id=(int)$vw->question_id;
     $ar['a']['id']=$id;
     $ar['a']['idx']=$idx;
     $ar['a']['map_lesson_id']=$map_lesson_id;
     $ar['a']['map_question_id']=$map_question_id;
     $ar['a']['standard_level']=$vw->standard_level;
     $ar['a']['question_detail']=$vw->question_detail;
     ###############################
################################
$qdoexamscore_arr=array();
if (is_array($questionrs_doexam_score)){
foreach($questionrs_doexam_score as $keys2 =>$vw2){
     $keys2=$keys2+1;
     $ar3=array();
     $exam_id=(int)$vw2['exam_id'];
     $question_id=(int)$vw2['question_id'];
     $ar3['c']['no']=$keys2;
     $ar3['c']['exam_id']=$exam_id;
     $ar3['c']['question_id']=$question_id;
     $ar3['c']['score']=$vw2['score'];
     $ar3['c']['score_mesage']=$vw2['score_mesage'];
     if($map_question_id==$question_id){$qdoexamscore_arr[]=$ar3['c'];}
}}
################################
$qdoexamscore_arr_count=count($qdoexamscore_arr);
$qdoexamscorearr=$qdoexamscore_arr['0'];
#$ar['a']['doexamscore']=$qdoexamscorearr;
$ar['a']['no']=$qdoexamscorearr['no'];
$ar['a']['score']=$qdoexamscorearr['score'];
$ar['a']['score_mesage']=$qdoexamscorearr['score_mesage'];
##############################
if($qdoexamscore_arr_count>0){$mapquestion_arr[]=$ar['a'];}
 
 }
}else{$mapquestion_arr=null;}
$user_answer_count=(int)count($mapquestion_arr);
$arrs['a']['user_answer_count']=$user_answer_count;
$arrs['a']['user_answer']=$mapquestion_arr;
################################
$usersanscore_arr=array();
if(is_array($mapquestion_arr)){
foreach($mapquestion_arr as $k=>$n){
$ar3=array();
$score=(int)$n['score'];
$no=(int)$n['no'];
$a['d']['no']=$no;
$a['d']['score']=$score;
$a['d']['question_id']=$n['map_question_id'];
if($score==1){$usersanscore_arr[]=$a['d']; }
}}
################################
$useranswer_true_count=(int)count($usersanscore_arr);
#$arrs['a']['useranswer_true']=$usersanscore_arr;
$arrs['a']['useranswer_true_count']=$useranswer_true_count;
$arrs['a']['useranswer_all_count']=$useranswer_true_count;
$arrs['a']['useranswer_scoreall_true']=$useranswer_true_count.'/'.$user_answer_count;
$d_percen=(($useranswer_true_count*100)/$user_answer_count);
$arrs['a']['useranswer_percen']=$d_percen;
$arrs['a']['useranswer_percen_mesage']=$d_percen.'%';
$map_content=$this->Model_examination->get_where_mul_map_content_lessonid_join($lesson_id);
$arrs['a']['lesson_content']=$map_content; 
#############################
$lesson_arr[]=$arrs['a'];
}}else{$lesson_arr=null;}
################################
$radarchart_arr=array();
if(is_array($lesson_arr)){
foreach($lesson_arr as $k1=>$n1){
$ar3=array();
$all_count=(int)$n1['user_answer_count'];
$user_answer_count=(int)$n1['useranswer_all_count'];
$b['e']['lesson_name']=$n1['lesson_name'];
$b['e']['countanswer']=$user_answer_count;
$b['e']['countall']=$all_count;
$b['e']['percen']=$n1['useranswer_percen'];
$b['e']['percen_mesage']=$n1['useranswer_percen_mesage'];
$b['e']['trueall']=$n1['useranswer_scoreall_true'];
$radarchart_arr[]=$b['e']; 
}}
################################
#########################################################


######################### poor_ar########################
$poor_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $va1) {
        $percen1=(int)$va1['percen'];
       if($percen1<=49){
           $poor['poor']['lesson_name']=$va1['lesson_name'];
           $poor['poor']['percen']=$percen1;
           $poor['poor']['count_true']=$va1['countanswer'];
           $poor['poor']['count_all']=$va1['countall']; 
           $poor['poor']['msg']=$va1['trueall']; 
           $poor['poor']['percen_mesage']=$va1['percen_mesage'];
           $poor_ar[]=$poor['poor'];  
   }    
 }    
}
######################### fair_ar########################
$fair_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $va2) {
        $percen2=(int)$va2['percen'];
        if($percen2>=50 && $percen2<=79){
           $fair['fair']['lesson_name']=$va2['lesson_name'];
           $fair['fair']['percen']=$percen2;
           $fair['fair']['count_true']=$va2['countanswer'];
           $fair['fair']['count_all']=$va2['countall']; 
           $fair['fair']['msg']=$va2['trueall']; 
           $fair['fair']['percen_mesage']=$va2['percen_mesage'];
           $fair_ar[]=$fair['fair'];  
   }    
 }    
}
######################### good###########################
$good_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $va3) {
        $percen3=(int)$va3['percen'];
        if($percen3>=80){
           $good['good']['lesson_name']=$va3['lesson_name'];
           $good['good']['percen']=$percen3;
           $good['good']['count_true']=$va3['countanswer'];
           $good['good']['count_all']=$va3['countall']; 
           $good['good']['msg']=$va3['trueall']; 
           $good['good']['percen_mesage']=$va3['percen_mesage'];
           $good_ar[]=$good['good'];  
   }    
 }    
}
$datast=array('poor'=>$poor_ar,'fair'=>$fair_ar,'good'=>$good_ar);
$condition_chart=$datast;
#########################################################

if($radarchart_arr){
   $statradarchart=array();
   foreach ($radarchart_arr as $value) {
         $percen=$value['percen'];
         $percentscore1=(int)round($percen,0,PHP_ROUND_HALF_UP);  
              $statradarchart[$value['lesson_name']]=$percentscore1;
     }
}
$user_score=$examscoress->score_value;
$scoredisplayradarchart1=$statradarchart;
$dataall=array('user_id'=>(int)$user_id,
               //'examination'=>$examinationrs,
               'exam_id'=>(int)$examinationrs->id,
               'exam_name'=>(int)$examinationrs->exam_name,
               'log_id'=>(int)$log_id,
               'user_score'=>(int)$user_score,
               //'lesson'=>$lesson_arr,
               //'lesson_count'=>count($lesson_arr),
               'radarchart'=>$scoredisplayradarchart1,
               'radar_chart'=>$radarchart_arr,
               //'condition_chart'=>$condition_chart,
          //'sqlexam'=>$sqlexam,
          //'questionrs_doexam_score'=>$questionrs_doexam_score,
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='question lesson exam user';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall),200);
}elseif($dataall==''){
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>201), 
                                   'data'=> null),201);   
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>201), 
                                   ),201);      
}
die();
}
public function MergeArrays($Arr1, $Arr2) {
        foreach ($Arr2 as $key => $Value) {
            if (array_key_exists($key, $Arr1) && is_array($Value))
                $Arr1[$key] = $this->MergeArrays($Arr1[$key], $Arr2[$key]);
            else
                $Arr1[$key] = $Value;
        }

        return $Arr1;
    }
public function task3_demo_get(){
// api/upskill/task3?category_id=5&user_id=543622&deletekey=1
####################################################
ob_end_clean();
$module_name='upskill task';
$category_id=@$this->input->get('category_id');
if($category_id==null || $category_id==0){
     $this->response(array('header'=>array(
					  'title'=>'REST_Controller::HTTP_OK',
                            'module'=>$module_name,
					   'message'=>'Error category_id is null',
					   'status'=>TRUE,
					   'code'=>201), 
					   'data'=>null),201);
Die();       
}
$user_id=@$this->input->get('user_id');
if($user_id==null || $user_id==0){$user_id=null;}
$type_id=@$this->input->get('type_id');
$item_id=@$this->input->get('item_id');
$page=@$this->input->get('page');
$perpage=@$this->input->get('perpage');
$deletekey=@$this->input->get('deletekey');
$cachetype='4';
#################################################
$formmodel=$this->Model_upskill->get_ups_task($category_id,$item_id,$page,$perpage,$deletekey,$cachetype);
$task=$formmodel['list'];
$task_arr=array();
if(is_array($task)){
foreach($task as $keym =>$val){
$arr=array();
  $task_id=(int)$val->task_id;
  $categoryid=(int)$val->ups_category_id;
  $arr['a']['category_id']=$category_id;
  $arr['a']['task_id']=$task_id;
  $arr['a']['user_id']=$user_id;
  //$arr['a']['type_id']=$type_id;
  //$arr['a']['category_id']=$categoryid;
  $arr['a']['category']=$val->category;
  $arr['a']['task_title']=$val->task_title;
  $arr['a']['task_description']=$val->task_description;
  //$arr['a']['sort']=$val->sort; 
  $arr['a']['status']=$val->status; 
  $arr['a']['create_by_ip']=$val->create_by_ip; 
  //$arr['a']['update_by_ip']=$val->update_by_ip; 
  //$arr['a']['create_by_user']=$val->create_by_user; 
  //$arr['a']['update_by_user']=$val->update_by_user; 
  //$arr['a']['create_date']=$val->create_date; 
  //$arr['a']['update_date']=$val->update_date; 
/*
1 Exam 
2 Cmsblog
3 Learning
4 TV Program
5 Other  
*/
$typeid=null;
$itemid=null;
$item=$this->Model_upskill->get_ups_item($categoryid,$typeid,$task_id,$itemid,$page,$perpage,$user_id,$deletekey,$cachetype);
$itemlist=$item['list'];   
$sql=$item['sql']; 
####################################
$item_arr=array();
if(is_array($itemlist)){
foreach($itemlist as $k2 =>$v){
$arr2=array();
       $item_id=(int)$v->item_id;
       $arr2['b']['user_id']=$user_id;
       $arr2['b']['category']=$v->category;
       $arr2['b']['type_name']=$v->type_name;
       $ups_type_id=(int)$v->ups_type_id;
       $arr2['b']['type_id']=$ups_type_id;
       $arr2['b']['item_id']=$v->item_id;
       $exam_id=(int)$v->item_content_id;
       $arr2['b']['item_content_id']=$exam_id;
       $arr2['b']['item_title']=$v->item_title; 
       $arr2['b']['item_url']=$v->item_url; 
       $arr2['b']['sort']=$v->sort; 
       #$visited=$v->visited; 
     
    
        
#########type 3############
if($ups_type_id==3){
#####################
if($user_id!==null){
#########user_id############
$cachetype=4;
$datalist=$this->Model_upskill->get_log_item_user_type($item_id,$user_id,$ups_type_id,$cachetype,$deletekey);
$datalist=$datalist['list']; 
$datalistrs=$datalist['0'];
$visited=$datalistrs->visited;
if($visited==null){$visited=0;}
$arr2['b']['visited']=(int)$visited;  
if($datalist==null){$arr2['b']['log']=null;}else{$arr2['b']['log']=$datalist['0'];}


#########user_id############
}else{$arr2['b']['log']=null;$arr2['b']['visited']=0;}
#####################
}else{$arr2['b']['log']=null;} 
### type_id 1
if($ups_type_id==1){
     
if($user_id!==null){
     
$examlog=$this->Model_upskill->get_logscore_user_id($exam_id,$user_id,4,$deletekey);
$exam_log_list=$examlog['list']; 
#$arr2['b']['log_exam']=$exam_log_list;

$e_log_arr=array();
if(is_array($exam_log_list)){
foreach($exam_log_list as $k3 =>$v3){
$arr3=array();
$log_id=$v3->log_id;
     $arr3['c']['log_id']=$log_id;
     $arr3['c']['score_exam']=$v3->score_exam;
     $arr3['c']['score_user']=$v3->score_user;
      $chartdata=$this->qleu($exam_id,$user_id,$log_id,$deletekey);
      $arr3['c']['chart']=$chartdata['radar_chart'];
      $arr3['c']['radarchart']=$chartdata['radarchart'];
     $e_log_arr[]=$arr3['c'];
}}
#################################
$logarr=array();
if (is_array($e_log_arr)){foreach($e_log_arr as $ks =>$vs){$arrs=array();$arrs['e']=(int)$vs['log_id'];$logarr[]=$arrs['e'];}}
#################################
$logimplode=implode(",", $logarr);
$logexplode=explode(",", $logimplode);
$arr2['b']['log_implode']=$logimplode;
$arr2['b']['log_explode']=$logexplode;
$arr2['b']['log_exam']=$e_log_arr;
$arr2['b']['log_exam_count']=count($exam_log_list);
#####################################################
#####################################################             
}else{
          $arr2['b']['log_exam']=null;
          $arr2['b']['log_exam_count']=0;
    }   
} 
### type_id 1
 $item_arr[]=$arr2['b'];
 }
}
if($ups_type_id==1){
     $arr['a']['item_count']=0;
     $arr['a']['item']=null;   
}else{
     $item_count=(int)count($item_arr);
     $arr['a']['item_count']=$item_count;
     $arr['a']['item']=$item_arr;    
}
#################################
$logarr1=array();
if (is_array($item_arr)){
foreach($item_arr as $ks1 =>$vs1){
     $arrs1=array();
     $arrs1['f']=(int)$vs1['item_content_id'];
     $logarr1[]=$arrs1['f'];
     }}
//$arr['a']['item_exam_id']=$logarr1;
$logimplode1=implode(",", $logarr1);
$arr['a']['item_implode']=$logimplode1; 
$logexplode1=explode(",", $logimplode1);
$arr['a']['item_explode']=$logexplode1; 
$exam_id_in=$logimplode1;
$qleuarray=$this->qleuarray($exam_id_in,$user_id,$deletekey);
$arr['a']['chart']=$qleuarray;
#################################
if($ups_type_id==3){ 
$visited_arr=array();
if(is_array($item_arr)){
     foreach($item_arr as $k =>$v){
     $arr2=array();
        $visited=(int)$v['visited'];
     if($visited>=1){
          $arrc['c']['visited']=$visited; 
          $visited_arr[]=$arrc['c']; 
        } 
      }
     }  
     //$arr['a']['visited_arr']=$visited_arr;
     $item_count_visited=(int)count($visited_arr);
     $arr['a']['item_count_visited']=$item_count_visited;
     $percent_visited=($item_count_visited*100)/$item_count;
     $percentvisited=(int)round($percent_visited,0,PHP_ROUND_HALF_UP); 
     $arr['a']['visited_percent']=$percentvisited;
     $arr['a']['visit_percent']=$percentvisited.' %';
} 

#################################
  //$arr['a']['item_sql']=$sql;
####################################
  //$arr['a']['item_sql']=$item['sql'];  
  //$arr['a']['sql']=$dataformmodel['sql']; 
  if($type_id==null){
     if($ups_type_id==1){ $task_arr[]=$arr['a']; }  
  }else{
       if($type_id==1 ||$type_id==2 ||$type_id==3){
         if($ups_type_id==$type_id){ $task_arr[]=$arr['a']; }     
       }else{ $task_arr[]=$arr['a']; } 
  }

 }
}
#################################################
$all_arr=array();
if(is_array($task_arr)){
foreach($task_arr as $k =>$v){
$arrc3=array();
     $arrc3['c']['category_id']=$v['category_id'];  
     $arrc3['c']['create_by_ip']=$v['create_by_ip'];  
     $arrc3['c']['user_id']=$v['user_id']; 
     $arrc3['c']['item_explode']=$v['item_explode'];  
     $arrc3['c']['status']=$v['status']; 
 $all_arr[]=$arrc3['c'];
 }
} 
$all_arr4=array();
if(is_array($all_arr)){
foreach($all_arr as $k2 =>$v2){
$arrc4=array();
 $all_arrimplode=$v2['item_explode']; 
 $arrc4['c']=implode(",", $all_arrimplode); 
 $all_arr4[]=$arrc4['c'];
 }
} 
$all_arr5=implode(",", $all_arr4);
$exam_id_in=$all_arr5;
########################
$qtotal=$this->Model_examination->where_question_total_exam_id_in($exam_id_in,$cachetype,$deletekey);
$qtotals=$qtotal['list']['0'];
$question_total=(int)$qtotals->question_total;
$sumscore=$this->Model_examination->where_exam_sumscore_member_in($exam_id_in,$user_id,$cachetype,$deletekey);
$sumscore=$sumscore['list']['0'];
$sum_score=(int)$sumscore->sum_score;
$score_percen=(($sum_score*100)/$question_total);
$scorepercenuser=(int)round($score_percen,0,PHP_ROUND_HALF_UP);  
$user_bar=array('total'=>$question_total,'sumscore'=>$sum_score,'percen'=>$scorepercenuser,'percen_msg'=>$scorepercenuser.' %','exam_id'=>$exam_id_in,);
########################
$exam_id_in=$all_arr5;
$dataall=$this->qleuarray2($exam_id_in,$user_id,$deletekey);
if($task_arr!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
                                   'data'=>$dataall,
                                   //'data_user_score_all'=>$user_bar,
                                   //'logimplodeall'=> $logimplodeall,
                                   ),200);
}
else{
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'token'=>null,
                                        ),204);
}
die();
}
### END
}