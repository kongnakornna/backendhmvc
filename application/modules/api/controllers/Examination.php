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
class Examination extends REST_Controller{
function __construct(){
// Construct the parent class
parent::__construct();
$this->load->library('encrypt');
$this->load->model('Model_examination', 'Model_examination');
$this->load->model('Mul_level_model');
$this->load->model('Category2017_model');
//Load library
$this->load->library('Memcached_library');
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
###############Setup################
public function base64_encrypt_get($string, $key) {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result.=$char;
        }

        return base64_encode($result);
    }
public function base64_decrypt_get($string, $key) {
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
public function base64_encrypt_post($string, $key) {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result.=$char;
        }

        return base64_encode($result);
    }
public function base64_decrypt_post($string, $key) {
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
public function unserialize_get($dataset) {
        $result=unserialize($dataset);
        return $result;
}
public function implode_get($array) {
        $result=implode(",", $array);
        return $result;
}
public function explode_get($array) {
        $result=explode(",", $array);
        return $result;
}
public function scoreallanswer_get($scoreAllAnswer) {
      if($scoreAllAnswer) {
          foreach ($scoreAllAnswer as $value) {
              $statScore[$value['score_value']] = $value['total'];
          }
      }
      for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
      }
      
      $data['scoreArr'] = ($scoreArr);
        return $data;
}
####################################
public function examinationdatam1pre_get(){
ob_end_clean();
 #$array='13442,13444,13445,13448,13449';
 #$array='13452,13453,13451,13455,13456,13457,13458,13459,13460,13461,13462,13463';
 $array='13452,13453,13451,13455,13456,13457,13458,13459,13460,13461,13462,13463,13442,13444,13445,13448,13449';
	return $array; 
}
public function examinationdatam1post_get(){
ob_end_clean();
 $array='13442,13444,13445,13448,13449';
	return $array; 
}
##########*******memcache*******############
public function info_get(){
ob_end_clean();
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$module_name='info';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
if($cacheinfo!==''){$this->response(array('header'=>array(
										'title'=>'REST_Controller::HTTP_OK',
                                                  'module'=>$module_name,
										'message'=>' DATA OK',
										'status'=>TRUE,
										'code'=>200), 
										'data'=> $cacheinfo),200);
}elseif($cacheinfo==''){$this->response(array('header'=>array(
										'title'=>'HTTP_BAD_REQUEST',
                                                  'module'=>$module_name,
										'message'=>'Data could not be found',
										'status'=>FALSE, 
										'code'=>204), 
										'data'=> $cacheinfo),204);
}else{$this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ],404);//, REST_Controller::HTTP_NOT_FOUND);
}
die();
} 
public function list_get(){
ob_end_clean();
$array=$this->examinationdatam1pre_get();
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$deletekey=@$this->input->get('deletekey');
$dataall=$this->Model_examination->cvs_course_examination_array($array,$deletekey);
$module_name='list examination set id';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,'info_cache'=>$cacheinfo),204);
}else{
     
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
}
die();
}
#########byuser########
public function examinationid_get(){
ob_end_clean();
$exam_id=@$this->input->get('exam_id');
$deletekey=@$this->input->get('deletekey');
$cachetype=@$this->input->get('cachetype');
$module_name='examination id';
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
exit();
}
if($cachetype==''){$cachetype=4;}
$dataall=$this->Model_examination->where_course_examination($exam_id,$deletekey,$cachetype);
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='list examination set id';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,'info_cache'=>$cacheinfo),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
}
die();
}
######################  
public function examinationreport_old_get(){
ob_end_clean();
// http://www.trueplookpanya.com/webservice/api/examination/examinationreport?exam_id=13438&user_id=543622&deletekey=1
$exam_id=@$this->input->get('exam_id');
$user_id=@$this->input->get('user_id');
$deletekey=@$this->input->get('deletekey');
$log_id=@$this->input->get('log_id');
if($log_id==''){$log_id=null;}
/*
echo '<pre> exam_id=>'; print_r($exam_id); echo '</pre>'; 
echo '<pre> user_id=>'; print_r($user_id); echo '</pre>';  
echo '<pre> deletekey=>'; print_r($deletekey); echo '</pre>'; Die(); 
*/

$module_name='examination report by id and user_id';
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}

/*
if($user_id==null ||$user_id==0) {
$message='ไม่พบ user_id '.$user_id.' กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}
*/

if($user_id!==null){
$users_account=$this->Model_examination->users_account_id($user_id,$deletekey);
#echo '<pre> users_account=>'; print_r($users_account); echo '</pre>'; Die();   
}else{ $user_id==null;}

$orderby=@$this->input->get('orderby');
$$limit=@$this->input->get('limit');
$examscoreuserlog=$this->Model_examination->where_cvs_course_exam_score_user_id($exam_id,$user_id,$limit,$orderby,$log_id,$deletekey);
# echo '<pre> examscoreuserlog=>'; print_r($examscoreuserlog); echo '</pre>'; Die(); 
if($examscoreuserlog==null) {
$message='ไม่พบ ประวัติการข้อสอบ examination id '.$exam_id.' กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}


#echo '<pre> examscoreuserlog=>'; print_r($examscoreuserlog); echo '</pre>'; Die(); 
$count_log_user=count($examscoreuserlog);
if($count_log_user==0) {
$message='ไม่พบ user log ข้อสอบ examination id '.$exam_id.' user id '.$user_id.' กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}

$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examinationrs=$examinationrs['list'];
$cachekey=$examinationrs['cachekey'];
//echo '<pre> examinationrs=>'; print_r($examinationrs); echo '</pre>';  #Die(); 
if($examinationrs=='') {
$message='ไม่พบข้อสอบ examination id '.$exam_id.' กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}
$examination=$examinationrs['0'];
#echo '<pre> $examination=>'; print_r($examination); echo '</pre>';  
#echo '<pre> id=>'; print_r($examination->id); echo '</pre>'; Die();  
$question_id=(int)$examination->id;
#echo '<pre> $question_id=>'; print_r($question_id); echo '</pre>';  Die(); 
 
$questionrs=$this->Model_examination->wherequestion($question_id,$deletekey);
#echo '<pre> $questionrs=>'; print_r($questionrs); echo '</pre>';  Die();
$questionrslist=$questionrs['list']; 


##########
$order='desc';$limit='100';
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member($exam_id,$user_id,$order,$limit,$log_id);
$examscoress=$exam_scoress['list']['0'];
$answer_value=$examscoress->answer_value;
$answervalue=unserialize($answer_value);
############################################################


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
     ////$log_id
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
###############################
$resultsdatascore=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$score_value_exam_id=array(
       'scorearray'=>$scoreArray2,
       'score'=>$resultsdatascore
       );
############################################################
$user_score=$examscoress->score_value;
$percent_score=($user_score*100)/$question_total;
$question_time=$examscoress->duration_sec;


#######################

$exam_percent=$examination->exam_percent;
$exam_name=$examination->exam_name;
$level_name=$examination->level_name;
$category_name=$examination->category_name;
$percentscoreuser=(int)$percent_score;
if($percentscoreuser>=$exam_percent){
  $examscoremesage='สอบผ่าน';   
  $examscore_status=1;  
}else{
  $examscoremesage='สอบไมผ่าน';  
  $examscore_status=0;  
}
#######################

$dataall=array('examination'=>$examination,
               'profile_user'=>$users_account,
               'user_examination_log'=>$examscoreuserlog,
               'examination_percent'=>$exam_percent,
               'examination_user_mesage'=>$examscoremesage,
               'examination_user_status'=>$examscore_status,
               //'questionrs'=>$questionrslist,
               'user_id'=>$user_id,
               'question_data'=>$questionrs_doexam_score,
               'question_data_count'=>count($questionrslist),
               #'exam_score'=>$examscoress,
               'user_score'=>$user_score,
               'total_score'=>$question_total,
               'percent_score'=>(int)$percent_score,
               'percent'=>$percent_score.'%',
               'score'=>$user_score.' คะนนน',
               #'answervalue'=>$answervalue,
               'question_total'=>$question_total,
               'duration_time'=>$question_time,
               'scorebyexamid'=>$scoreArray2,
               'scorebyexamid2'=>$choicescorearr,
               'exam_score_count'=>count($exam_scoress),
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='list examination set id';
if($dataall!==''){
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
}elseif($dataall==''){
$this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,'info_cache'=>$cacheinfo),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
}
die();
}
public function examinationreport_get(){
ob_end_clean();
// http://www.trueplookpanya.com/webservice/api/examination/examinationreport?exam_id=13438&user_id=543622&deletekey=1
$exam_id=@$this->input->get('exam_id');
$user_id=@$this->input->get('user_id');
$deletekey=@$this->input->get('deletekey');
$log_id=@$this->input->get('log_id');
if($log_id==''){$log_id=null;}
/*
echo '<pre> exam_id=>'; print_r($exam_id); echo '</pre>'; 
echo '<pre> user_id=>'; print_r($user_id); echo '</pre>';  
echo '<pre> deletekey=>'; print_r($deletekey); echo '</pre>'; Die(); 
*/

$module_name='examination report by id and user_id';
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>$message,
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}

if($user_id==null ||$user_id==''){$user_id=null;}else{
 $users_account=$this->Model_examination->users_account_id($user_id,$deletekey);
#echo '<pre> users_account=>'; print_r($users_account); echo '</pre>'; Die();     
}

$orderby=@$this->input->get('orderby');
$$limit=@$this->input->get('limit');
$examscoreuserlog=$this->Model_examination->where_cvs_course_exam_score_user_id($exam_id,$user_id,$limit,$orderby,$log_id,$deletekey);
# echo '<pre> examscoreuserlog=>'; print_r($examscoreuserlog); echo '</pre>'; Die(); 
if($examscoreuserlog==null) {
     if($log_id!==''){
         $message='ไม่พบ ประวัติการข้อสอบ examination id '.$exam_id.' user id '.$user_id.'  กรุณาตรวจสอบ';
     }else{
         $message='ไม่พบ ประวัติการข้อสอบ examination id '.$exam_id.' log_id'.$log_id.'  กรุณาตรวจสอบ';
     }

 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}

#echo '<pre> examscoreuserlog=>'; print_r($examscoreuserlog); echo '</pre>'; Die(); 
$count_log_user=count($examscoreuserlog);
if($count_log_user==0) {
$message='ไม่พบ examination user log ข้อสอบ examination id '.$exam_id.'  กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}

$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examinationrs=$examinationrs['list'];
$cachekey=$examinationrs['cachekey'];
//echo '<pre> examinationrs=>'; print_r($examinationrs); echo '</pre>';  #Die(); 
if($examinationrs=='') {
$message='ไม่พบข้อสอบ examination id '.$exam_id.' กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}
$examination=$examinationrs['0'];
#echo '<pre> $examination=>'; print_r($examination); echo '</pre>';  
#echo '<pre> id=>'; print_r($examination->id); echo '</pre>'; Die();  
$question_id=(int)$examination->id;
#echo '<pre> $question_id=>'; print_r($question_id); echo '</pre>';  Die(); 
 
$questionrs=$this->Model_examination->wherequestion($question_id,$deletekey);
#echo '<pre> $questionrs=>'; print_r($questionrs); echo '</pre>';  Die();
$questionrslist=$questionrs['list']; 


##########
$order='desc';$limit='100';
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member($exam_id,$user_id,$order,$limit,$log_id);
$examscoress=$exam_scoress['list']['0'];
$answer_value=$examscoress->answer_value;
$answervalue=unserialize($answer_value);
############################################################


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
     ////$log_id
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
###############################
$resultsdatascore=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$score_value_exam_id=array(
       'scorearray'=>$scoreArray2,
       'score'=>$resultsdatascore
       );
############################################################
$user_score=$examscoress->score_value;
$percent_score=($user_score*100)/$question_total;
$question_time=$examscoress->duration_sec;
#######################
$exam_percent=$examination->exam_percent;
$exam_name=$examination->exam_name;
$level_name=$examination->level_name;
$category_name=$examination->category_name;
$percentscoreuser=(int)$percent_score;
if($percentscoreuser>=$exam_percent){
  $examscoremesage='สอบผ่าน';   
  $examscore_status=1;  
}
else{
  $examscoremesage='สอบไมผ่าน';  
  $examscore_status=0;  
}
#######################

$dataall=array('examination'=>$examination,
               'profile_user'=>$users_account,
               'user_examination_log'=>$examscoreuserlog,
               'examination_percent'=>$exam_percent,
               'examination_user_mesage'=>$examscoremesage,
               'examination_user_status'=>$examscore_status,
               //'questionrs'=>$questionrslist,
               'user_id'=>$user_id,
               'log_id'=>$log_id,
               'question_data'=>$questionrs_doexam_score,
               'question_data_count'=>count($questionrslist),
               #'exam_score'=>$examscoress,
               'user_score'=>$user_score,
               'total_score'=>$question_total,
               'percent_score'=>(int)$percent_score,
               'percent'=>$percent_score.'%',
               'score'=>$user_score.' คะนนน',
               #'answervalue'=>$answervalue,
               'question_total'=>$question_total,
               'duration_time'=>$question_time,
               'scorebyexamid'=>$scoreArray2,
               'scorebyexamid2'=>$choicescorearr,
               'exam_score_count'=>count($exam_scoress),
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='list examination set id';
if($dataall!==''){
$tokenData=$dataall;
###################******JWT Web token**##############
// load JWT
$this->load->helper('jwt');
$time=60;
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$issuedAt=time();
$expirationTime=$issuedAt+$time;  // jwt valid for $time seconds from the issued time
$payload=array('data' => $tokenData,
               'iat' => $issuedAt,
               'exp' => $expirationTime);
$tokenjwt=JWT::encode($payload, $key, $algorithm);
$token_encode=$tokenjwt;
$token_decode=JWT::decode($tokenjwt,$key,array($algorithm)); 
$datade=$token_decode;
$data=$datade->data;
###################******JWT Web token**##############
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $data,
                                   //'token'=> $token_encode,
                                   //'info_cache'=>$cacheinfo
                                   ),200);
}
elseif($dataall==''){
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> null,'info_cache'=>$cacheinfo),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
}
die();
}
######################
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
/*
if($user_id==null ||$user_id==0) {
$message='ไม่พบ user_id'.$user_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> null),200);
exit();
}
*/
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
$dpercen=(($useranswer_true_count*100)/$user_answer_count);
$d_percen=(int)round($dpercen,0,PHP_ROUND_HALF_UP); 
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


/*
######################### condition_chart########################
$poor_ar=array();
$fair_ar=array();
$good_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $va1) {
     $chart=array();
       $percen1=(int)$va1['percen'];
           $chart['conditionchart']['lesson_name']=$va1['lesson_name'];
           $chart['conditionchart']['percen']=$percen1;
           $chart['conditionchart']['count_true']=$va1['countanswer'];
           $chart['conditionchart']['count_all']=$va1['countall']; 
           $chart['conditionchart']['msg']=$va1['trueall']; 
           $chart['conditionchart']['percen_mesage']=$va1['percen_mesage'];
          if($percen1<50){
           $poor_ar[]=$chart['conditionchart'];  
          }elseif($percen1>50 && $percen1<=79){
           $fair_ar[]=$chart['conditionchart'];      
          }elseif($percen1>=80){
           $good_ar[]=$chart['conditionchart'];      
          }     
    }    
}
$condition_chart=array('poor'=>$poor_ar,'fair'=>$fair_ar,'good'=>$good_ar);
######################### condition_chart########################
*/
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
$dataall=array('user_id'=>$user_id,
               'examination'=>$examinationrs,
               'log_id'=>$log_id,
               'user_score'=>$user_score,
               'lesson'=>$lesson_arr,
               'lesson_count'=>count($lesson_arr),
               'radarchart'=>$scoredisplayradarchart1,
               'radar_chart'=>$radarchart_arr,
               'condition_chart'=>$condition_chart,
               'sqlexam'=>$sqlexam,
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
public function examinationanswerbymember_get(){
ob_end_clean();
$exam_id=@$this->input->get('exam_id');
$deletekey=@$this->input->get('deletekey');
$log_id=@$this->input->get('log_id');
$module_name='examination answer by member';
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>201), 
                                   ),201);
exit();
}
$user_id=@$this->input->get('user_id');

/*
if($user_id==null ||$user_id==0) {
$message='ไม่พบ user_id '.$user_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>201), 
                                   ),201);
exit();
}
*/

$pageid=@$this->input->get('pageid');
if($pageid==null){$pageid=1;}
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examinationrs=$examinationrs['list'];
$cachekey=$examinationrs['cachekey'];
//echo '<pre> examinationrs=>'; print_r($examinationrs); echo '</pre>';  #Die(); 
if($examinationrs=='') {
$message='ไม่พบข้อสอบ examination id '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
exit();
}
$examination=$examinationrs['0'];
#echo '<pre> $examination=>'; print_r($examination); echo '</pre>';  
#echo '<pre> id=>'; print_r($examination->id); echo '</pre>'; Die();  
$question_id=(int)$examination->id;
#echo '<pre> $question_id=>'; print_r($question_id); echo '</pre>';  Die(); 
$questionrs=$this->Model_examination->wherequestion($question_id,$deletekey);
#echo '<pre> $questionrs=>'; print_r($questionrs); echo '</pre>';  Die();
$questionrslist=$questionrs['list']; 
$order='desc';$limit='100';
$log_id=@$this->input->get('log_id');
if($log_id==''){$log_id='';}
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member($exam_id,$user_id,$order,$limit,$log_id);
$examscoress=$exam_scoress['list']['0'];
$answer_value=$examscoress->answer_value;
$answervalue=unserialize($answer_value);
############################################################


$questionrs_doexam_score= array();
if (is_array($questionrslist)){
foreach($questionrslist as $key =>$v){
     $arr=array();
     $question_id=(int)$v->id;
     $no=(int)$key+1;
if($no==$pageid){$active=1;}else{$active=0;}
if($active==1){
###########################################################
     $arr['d']['no']=$no;
     $arr['d']['pageid']=$pageid;
     $arr['d']['active']=$pageid;
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
     }else{
          $arr2['a']['user_ans_id']=null;
          $arr2['a']['user_answer_mesage']='คุณไม่ได้เลือกข้อนี้';
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
          
if($answer=='true'){
     $arr2['a']['active']=1;
}else{
     $arr2['a']['active']=0;
}
          
      $choicerss[]=$arr2['a'];
      }
     }else{$choicerss=null;}
     ####################
     $arr['d']['choice']=$choicerss;
     $answerdata=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
     $arr['d']['answerdata']=$answerdata['0'];
     $arr['d']['score']=$status;
     $arr['d']['score_mesage']=$user_mesage;
     $questionrs_doexam_score[]=$arr['d'];
}####################

 }
}else{$questionrs_doexam_score=null;}
###############################
$resultsdatascore=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$score_value_exam_id=array(
       'scorearray'=>$scoreArray2,
       'score'=>$resultsdatascore
       );
############################################################
$user_score=$examscoress->score_value;
$percent_scoresna=($user_score*100)/$question_total;
$percent_score=round($percent_scoresna,2,PHP_ROUND_HALF_UP);  
$question_time=$examscoress->duration_sec;

##################*****lesson*****##############
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
//$arrs['a']['user_answer']=$mapquestion_arr;
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
if($user_answer_count>0){
 $lesson_arr[]=$arrs['a'];    
}
}}else{$lesson_arr=null;}
##################*****lesson*****##############
$lessonbyexamid=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_exam_id_mul_content($exam_id,$deletekey);
###########################################################
###########################################################
$dataall=array('examination'=>$examination,
               'pageid'=>$pageid,
               'user_id'=>$user_id,
               'question_data'=>$questionrs_doexam_score,
               'question_data_count'=>count($questionrslist),
               'user_score'=>$user_score,
               'total_score'=>$question_total,
               'percent_score'=>(int)$percent_score,
               'percent'=>$percent_score.'%',
               'score'=>$user_score.' คะนนน',
               'question_total'=>$question_total,
               'duration_time'=>$question_time,
               'lesson'=>$lesson_arr,
               'lesson_count'=>count($lesson_arr),
               'lesson_by_examination'=>$lessonbyexamid,
               'exam_score_count'=>count($exam_scoress),
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='examination answer by member';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   //'info_cache'=>$cacheinfo,
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        //'info_cache'=>$cacheinfo,
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
}
die();
}
public function examinationanswerbymemberchoice_get(){
ob_end_clean();
$exam_id=@$this->input->get('exam_id');
$log_id=@$this->input->get('log_id');
$deletekey=@$this->input->get('deletekey');
$module_name='examination answer by member';
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>201), 
                                   'data'=> $cacheinfo),201);
exit();
}
$user_id=@$this->input->get('user_id');
$pageid=@$this->input->get('pageid');
if($pageid==null){$pageid=1;}
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examinationrs=$examinationrs['list'];
$cachekey=$examinationrs['cachekey'];
//echo '<pre> examinationrs=>'; print_r($examinationrs); echo '</pre>';  #Die(); 
if($examinationrs=='') {
$message='ไม่พบข้อสอบ examination id '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
exit();
}
$examination=$examinationrs['0'];
#echo '<pre> $examination=>'; print_r($examination); echo '</pre>';  
#echo '<pre> id=>'; print_r($examination->id); echo '</pre>'; Die();  
$question_id=(int)$examination->id;
#echo '<pre> $question_id=>'; print_r($question_id); echo '</pre>';  Die(); 
$questionrs=$this->Model_examination->wherequestion($question_id,$deletekey);
#echo '<pre> $questionrs=>'; print_r($questionrs); echo '</pre>';  Die();
$questionrslist=$questionrs['list']; 
$order='desc';$limit='100';
$log_id=@$this->input->get('log_id');
if($log_id==''){$log_id='';}
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member($exam_id,$user_id,$order,$limit,$log_id);
$examscoress=$exam_scoress['list']['0'];
$answer_value=$examscoress->answer_value;
$answervalue=unserialize($answer_value);
############################################################
$questionrs_doexam_score= array();
if (is_array($questionrslist)){
foreach($questionrslist as $key =>$v){
     $arr=array();
     $question_id=(int)$v->id;
     $no=(int)$key+1;
if($no==$pageid){$active=1;}else{$active=0;}
###########################################################
     $arr['d']['no']=$no;
     $arr['d']['pageid']=$no;
     $arr['d']['active']=(int)$active;
     $arr['d']['question_id']=$question_id;
     /*
     $arr['d']['exam_id']=(int)$v->exam_id;
     $arr['d']['exam_name']=$v->exam_name;
     
     $arr['d']['question_encode']=$v->question_encode;
     $arr['d']['question_score']=$v->question_score;
     $arr['d']['question_skill']=$v->question_skill;
     $arr['d']['standard_level']=$v->standard_level;
     */
     // $arr['d']['question_detail']=$v->question_detail;
     $choice_rss_true=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
     $true_choice=$choice_rss_true['0'];
     #$arr['d']['true_choice']=$true_choice;
     $true_answer_id=(int)$true_choice->id_ans;
    // $arr['d']['true_answer_id']=$true_answer_id;
     $true_question_id=(int)$true_choice->id_question;
    // $arr['d']['true_question_id']=$true_question_id;
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
          $user_mesage='ตอบถูก ได้ คะแนน';
          $arr2['a']['user_mesage']=$user_mesage;
          $answervalues_arr[]=$arrw1['b'];
     }else{
          $arr2['a']['user_ans_id']=null;
          $arr2['a']['user_answer_mesage']='คุณไม่ได้เลือกข้อนี้';
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
     $user_mesage='ตอบผิด ไม่ได้ คะแนน';  
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
          
if($answer=='true'){
     $arr2['a']['active']=1;
}else{
     $arr2['a']['active']=0;
}
          
      $choicerss[]=$arr2['a'];
      }
     }else{$choicerss=null;}
     ####################
     //$arr['d']['choice']=$choicerss;
     $answerdata=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
     //$arr['d']['answerdata']=$answerdata['0'];
     $arr['d']['score']=$status;
     $arr['d']['score_mesage']=$user_mesage;
     $questionrs_doexam_score[]=$arr['d'];
####################

 }
}else{$questionrs_doexam_score=null;}
###############################
$user_score=$examscoress->score_value;
###########################################################
$dataall=array(//'examination'=>$examination,
               'question_user_awser'=>$questionrs_doexam_score,
               'question_user_awser_count'=>count($questionrslist),
               'user_score'=>$user_score,
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='examination answer by member';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   //'info_cache'=>$cacheinfo,
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        //'info_cache'=>$cacheinfo,
                                        ),204);
}else{
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ],404);//, REST_Controller::HTTP_NOT_FOUND);
}
die();
}
#########byschool########
public function schooluser_get(){
ob_end_clean();
##########################
$school_name=@$this->input->get('school_name');
$school_level=@$this->input->get('school_level');
if($school_level==''){$school_level=null;}
if($school_name==null ||$school_name=='') {
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
exit();
}
$deletekey=@$this->input->get('deletekey');
$userslist=$this->Model_examination->school_users($school_name,$school_level,$deletekey);
$dataall=array('school_name'=>$school_name,
               'school_level'=>$school_level,
               'user_list'=>$userslist,
               'user_count'=>count($userslist),
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='schoolexaminationlist';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,'info_cache'=>$cacheinfo),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
}
die();
}
public function schoolexaminationlist_get(){
ob_end_clean();
$exam_id=@$this->input->get('exam_id');
$deletekey=@$this->input->get('deletekey');
$array=$this->examinationdatam1pre_get();
$dataall=$this->Model_examination->cvs_course_examination_array($array,$deletekey);
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='schoolexaminationlist';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   #'info_cache'=>$cacheinfo,
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        #'info_cache'=>$cacheinfo,
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
}
die();
}
public function reportbyschoolexamid_get(){
ob_end_clean();
$exam_id=@$this->input->get('exam_id');
$school_name=@$this->input->get('school_name');
$usernamelike=@$this->input->get('usernamelike');
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
exit();
}
$deletekey=@$this->input->get('deletekey');
$array=$this->examinationdatam1pre_get();
$dataexamination=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$user_count=$this->Model_examination->job_edu_name_users_account_count($school_name,$deletekey);
$usercount=$user_count['0'];
##########################
if($usernamelike==''){$usernamelike='MT';}
if($school_name==null ||$school_name==''){
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
exit();
}
$edu=$this->Model_examination->job_edu_name_users_account($school_name,$deletekey);
#####################################
###############################
$resultsdatascore=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$score_value_exam_id=array(
       'scorearray'=>$scoreArray2,
       'score'=>$choicescorearr
       );
############################################################
$user_count=(int)$usercount->user_count;
$dataall=array('school_name'=>$school_name,
               'usernamelike'=>$usernamelike,
               'examination'=>$dataexamination['list']['0'],
               'count_user'=>$user_count,
               'eduroom'=>$edu,
               'scorevalue'=>$score_value_exam_id,
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='schoolexaminationlist';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,'info_cache'=>$cacheinfo),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
}
die();
}
public function examinationreportbyuser_get(){
ob_end_clean();
$exam_id=@$this->input->get('exam_id');
$user_id=@$this->input->get('user_id');
$deletekey=@$this->input->get('deletekey');
$module_name='examination report by id and user_id';
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
exit();
}if($user_id==null ||$user_id==0) {
$message='ไม่พบ user_id '.$user_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
exit();
}
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examinationrs=$examinationrs['list'];
$cachekey=$examinationrs['cachekey'];
//echo '<pre> examinationrs=>'; print_r($examinationrs); echo '</pre>';  #Die(); 
if($examinationrs=='') {
$message='ไม่พบข้อสอบ examination id '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
exit();
}
$examination=$examinationrs['0'];
#echo '<pre> $examination=>'; print_r($examination); echo '</pre>';  
#echo '<pre> id=>'; print_r($examination->id); echo '</pre>'; Die();  
$question_id=(int)$examination->id;
#echo '<pre> $question_id=>'; print_r($question_id); echo '</pre>';  Die(); 
$questionrs=$this->Model_examination->wherequestion($question_id,$deletekey);
#echo '<pre> $questionrs=>'; print_r($questionrs); echo '</pre>';  Die();
$questionrslist=$questionrs['list']; 
$order='desc';$limit='100';
$log_id=@$this->input->get('log_id');
if($log_id==''){$log_id='';}
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member($exam_id,$user_id,$order,$limit,$log_id);
$examscoress=$exam_scoress['list']['0'];
$answer_value=$examscoress->answer_value;
$answervalue=unserialize($answer_value);
############################################################
$resultsdatascore=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$score_value_exam_id=array(
       'scorearray'=>$scoreArray2,
       'score'=>$resultsdatascore
       );
############################################################
$user_score=$examscoress->score_value;
$percent_score=($user_score*100)/$question_total;
$question_time=$examscoress->duration_sec;
$exam_percent=$examination->exam_percent;
$exam_name=$examination->exam_name;
$level_name=$examination->level_name;
$category_name=$examination->category_name;
$percentscoreuser=(int)$percent_score;
if($percentscoreuser>=$exam_percent){
  $examscoremesage='สอบผ่าน';   
  $examscore_status=1;  
}else{
  $examscoremesage='สอบไมผ่าน';  
  $examscore_status=0;  
}
$dataall=array(#'examination'=>$examination,
               'exam_name'=>$exam_name,
               'level_name'=>$level_name,
               'category_name'=>$category_name,
               'user_id'=>$user_id,
               'user_score'=>$user_score,
               'total_score'=>$question_total,
               'percent_score'=>(int)$percent_score,
               'exam_percent'=>$exam_percent.'%',
               'exam_percents'=>$exam_percent,
               'percent'=>$percent_score.'%',
               'score'=>$user_score.' คะนนน',
               'score_mesage'=>$examscoremesage,
               'score_status'=>$examscore_status,
               'question_total'=>$question_total,
               'duration_time'=>$question_time,
               'exam_score_count'=>count($exam_scoress),
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='list examination set id';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   //'info_cache'=>$cacheinfo
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        //'info_cache'=>$cacheinfo
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
                                   /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ],404);//, REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
public function examinationanswerbyschooluser_get(){
ob_end_clean();
##########################
$exam_id=@$this->input->get('exam_id');
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examination=$examinationrs['list']['0'];
###############################
$school_name=@$this->input->get('school_name');
$school_level=@$this->input->get('school_level');
if($school_level==''){$school_level=null;}
if($school_name==null ||$school_name=='') {
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$deletekey=@$this->input->get('deletekey');
$userslist=$this->Model_examination->school_users($school_name,$school_level,$deletekey);
$user_list_arr=array();
if (is_array($userslist)){
foreach($userslist as $key=>$v){
$arr=array();
     $user_id=(int)$v->user_id;
     $arr['a']['user_id']=$user_id;
     $arr['a']['member_id']=$v->member_id;
     $arr['a']['firstname']=$v->firstname;
     $arr['a']['lastname']=$v->lastname;
     $arr['a']['user_fullname']=$v->user_fullname;
     $arr['a']['edu_name']=$v->edu_name;
     $arr['a']['edu_level']=$v->edu_level;
     $arr['a']['id_number']=$v->id_number;
     $arr['a']['job_name']=$v->job_name;
     $arr['a']['address']=$v->address;
     $arr['a']['birthdate']=$v->birthdate;
     $arr['a']['edu_degree']=$v->edu_degree;
     $arr['a']['email']=$v->email;
     $arr['a']['province']=$v->province;
     $arr['a']['postcode']=$v->psn_postcode;
     $arr['a']['sex']=$v->sex;
#####################################################
$urla='api/examination/examinationreportbyuser?exam_id='.$exam_id.'&user_id='.$user_id;
$url=base_url($urla);
$response=file_get_contents($url);
$dataallscore=json_decode($response);
$dataallscore_data=$dataallscore->data;
$user_score_test=$dataallscore_data->user_score;
if($user_score_test==''){$user_score_test=0;}
#######################################

$user_score_arrs=array('score'=>$dataallscore_data->score,
                       'score_mesage'=>$dataallscore_data->score_mesage,
                       'score_status'=>$dataallscore_data->score_status,
                       'exam_percent'=>$dataallscore_data->exam_percent,
                       'exam_percents'=>$dataallscore_data->exam_percents,
                       'user_score'=>$user_score_test,
                       'total_score'=>$dataallscore_data->total_score,
                       'user_id'=>$dataallscore_data->user_id,
                       'duration_time'=>$dataallscore_data->duration_time,
                       'percent'=>$dataallscore_data->percent,
                       'percent_score'=>$dataallscore_data->percent_score,
                       );
//$arr['a']['user_scores']=$user_score_arrs;

#######################################
$arr['a']['score']=$dataallscore_data->score;
$arr['a']['score_mesage']=$dataallscore_data->score_mesage;
$arr['a']['score_status']=$dataallscore_data->score_status;
$arr['a']['exam_percent']=$dataallscore_data->exam_percent;
$arr['a']['exam_percents']=$dataallscore_data->exam_percents;
$arr['a']['user_score']=$user_score_test;
$arr['a']['percent']=$dataallscore_data->percent;
$arr['a']['percent_score']=$dataallscore_data->percent_score;
$arr['a']['total_score']=$dataallscore_data->total_score;
$arr['a']['duration_time']=$dataallscore_data->duration_time;
#####################################################
#####################################################
#####################################################
if($user_score_test>0){$user_list_arr[]=$arr['a'];}
#####################################################
#####################################################
}}
$user_test_count=(int)count($user_list_arr);
$user_count_all=(int)count($userslist);
$user_not_test_count=(int)$user_count_all-$user_test_count;
$user_test_percent=($user_test_count*100)/$user_count_all;
#$user_test_percent=round($user_test_percent,2);
$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_UP);  
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_DOWN); 
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_EVEN); 
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_ODD);  
$user_not_test_percent=($user_not_test_count*100)/$user_count_all;
$user_score_avg=$this->Model_examination->score_avg($exam_id,$school_name,$school_level);
$userscoreavg=$user_score_avg['0'];
$score_avg=$userscoreavg->score_avg;
$question_total=$userscoreavg->question_total;
$score_avg=round($score_avg,2,PHP_ROUND_HALF_UP);  
###################################
$user_passanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key1=>$w1){
$arr=array();
     $user_id=(int)$w1['user_id'];
     $arrn['c']['user_id']=$user_id;
     $arrn['c']['member_id']=$w1['member_id'];
     $arrn['c']['firstname']=$w1['firstname'];
     $arrn['c']['lastname']=$w1['lastname'];
     $arrn['c']['user_fullname']=$w1['user_fullname'];
     $arrn['c']['edu_name']=$w1['edu_name'];
     $arrn['c']['edu_level']=$w1['edu_level'];
     $arrn['c']['id_number']=$w1['id_number'];
     $arrn['c']['job_name']=$w1['job_name'];
     $arrn['c']['address']=$w1['address'];
     $arrn['c']['birthdate']=$w1['birthdate'];
     $arrn['c']['edu_degree']=$w1['edu_degree'];
     $arrn['c']['email']=$w1['email'];
     $arrn['c']['province']=$w1['province'];
     $arrn['c']['postcode']=$w1['psn_postcode'];
     $arrn['c']['sex']=$w1['sex'];
     $arrn['c']['score_mesage']=$w1['score_mesage'];
     $score_status=(int)$w1['score_status'];
     $arrn['c']['score_status']=$score_status;
     $arrn['c']['score']=$w1['score'];
     $arrn['c']['percent']=$w1['percent'];
     $arrn['c']['percent_score']=$w1['percent_score'];
     if($score_status==1){$user_passanexam_arr[]=$arrn['c'];}
}}
$user_passanexam_count=count($user_passanexam_arr);
###################################
$user_passanexam_count=count($user_passanexam_arr);
$user_passanexam_percent=($user_passanexam_count*100)/$user_test_count;
$user_passanexam_percent=round($user_passanexam_percent,2,PHP_ROUND_HALF_UP); 
###################################
###################################
$usernotpassanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key2=>$w2){
$arr=array();
     $user_id=(int)$w2['user_id'];
     $arrm['d']['user_id']=$user_id;
     $arrm['d']['member_id']=$w2['member_id'];
     $arrm['d']['firstname']=$w2['firstname'];
     $arrm['d']['lastname']=$w2['lastname'];
     $arrm['d']['user_fullname']=$w2['user_fullname'];
     $arrm['d']['edu_name']=$w2['edu_name'];
     $arrm['d']['edu_level']=$w2['edu_level'];
     $arrm['d']['id_number']=$w2['id_number'];
     $arrm['d']['job_name']=$w2['job_name'];
     $arrm['d']['address']=$w2['address'];
     $arrm['d']['birthdate']=$w2['birthdate'];
     $arrm['d']['edu_degree']=$w2['edu_degree'];
     $arrm['d']['email']=$w2['email'];
     $arrm['d']['province']=$w2['province'];
     $arrm['d']['postcode']=$w2['psn_postcode'];
     $arrm['d']['sex']=$w2['sex'];
     $arrm['d']['score_mesage']=$w2['score_mesage'];
     $score_status=(int)$w2['score_status'];
     $arrm['d']['score_status']=$score_status;
     $arrm['d']['score']=$w2['score'];
     $arrm['d']['percent']=$w2['percent'];
     $arrm['d']['percent_score']=$w2['percent_score'];
     if($score_status==0){$usernotpassanexam_arr[]=$arrm['d'];}
}}
$usernotpassanexam_count=count($usernotpassanexam_arr);
$usernotpassanexam_percent=($usernotpassanexam_count*100)/$user_test_count;
$usernotpassanexam_percent=round($usernotpassanexam_percent,2,PHP_ROUND_HALF_UP); 
###################################
###############################
$resultsdatascore=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$areachart=array(
       'chart1'=>$choicescorearr,
       'chart2'=>$scoreArray2
       );
############################################################
$dataall=array('school_name'=>$school_name,
               'school_level'=>$school_level,
               'examination'=>$examination,
               'user_do_test'=>$user_list_arr,
               'user_do_test_count'=>$user_test_count,
               'user_list_all'=>$userslist,
               'user_count_all'=>$user_count_all,
               #'user_not_test_count'=>$user_not_test_count,
               'test_percent'=>(int)$user_test_percent,
               #'test_percents'=>(int)$user_test_percent.' %',
               'nottest_percent'=>(int)$user_not_test_percent,
               #'nottest_percents'=>(int)$user_not_test_percent.' %',
               'scoreavg'=>$score_avg,
               'question_total'=>$question_total,
               'user_passanexam_count'=>$user_passanexam_count,
               'user_passanexam_percent'=>$user_passanexam_percent,
               'user_passanexam'=>$user_passanexam_arr,
               #'user_not_passanexam_count'=>$usernotpassanexam_count,
               #'user_not_passanexam'=>$usernotpassanexam_arr,
               #'user_not_passanexam_percent'=>$usernotpassanexam_percent,
               'area_chart'=>$areachart,
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='schoolexaminationlist';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,'info_cache'=>$cacheinfo),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
######## school Setting ########
public function schoolloginpost_post(){
###############################################
//http://www.trueplookpanya.com/webservice/api/examination/schoolloginget?school_user=MT01&school_password=plook3391
$school_user=@$this->input->post('school_user'); 
$school_password=@$this->input->post('school_password'); 
###############################################
$data=array('schooluser'=>$school_user,'school_password'=>$school_password);
####################################
if($school_user=='' || $schoolpassword=''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>'school',
							'message'=>'Error school_user or school_password is null ',
							'status'=>TRUE,
							'code'=>201), 
							'data'=> null),201);
                                   die();
####################################
}

$school_data=array('school_user'=>$school_user,
                  'school_password'=>$school_password,
                  );
################################################
$schoolno0=array('school'=>'มัธยมป่ากลาง','school_user'=>'na','school_password'=>'na');
$schoolno1=array('school'=>'โรงเรียนทดสอบระบบ','school_user'=>'MT00','school_password'=>'it1234');
$schoolno2=array('school'=>'โรงเรียนวัดยานนาวา','school_user'=>'MT01','school_password'=>'plook3391');
$schoolno3=array('school'=>'โรงเรียนวัดพระยาสุเรนทร์ (บุญมีอนุกูล)','school_user'=>'MT02','school_password'=>'plook4486');
$schoolno4=array('school'=>'โรงเรียนวัดหงส์ปทุมาวาส','school_user'=>'MT03','school_password'=>'plook3321');
$schoolno5=array('school'=>'โรงเรียนพระแม่มารีพระโขนง','school_user'=>'MT04','school_password'=>'plook2456');
$schoolno6=array('school'=>'โรงเรียนวัดเทพลีลา','school_user'=>'MT05','school_password'=>'plook7769');
$schoolno7=array('school'=>'สาธิตม.บ้านสมเด็จฯ (ประถม)','school_user'=>'MT06','school_password'=>'plook7423');
$schoolno8=array('school'=>'โรงเรียนบีคอนเฮาส์แย้มสอาดรังสิต','school_user'=>'MT07','school_password'=>'plook4221');
$schoolno9=array('school'=>'โรงเรียนหนองจอกกงลิบฮัวเคียว','school_user'=>'MT08','school_password'=>'plook5542');
$schoolno10=array('school'=>'โรงเรียนอนุบาลนนทบุรี','school_user'=>'MT09','school_password'=>'plook7421');
$schoolno11=array('school'=>'โรงเรียนเซนต์โยเซฟ บางนา','school_user'=>'MT10','school_password'=>'plook110');
$schoolno12=array('school'=>'โรงเรียนวัดแสนเกษม','school_user'=>'','school_password'=>'plook1245');
$schoolno13=array('school'=>'โรงเรียนบ้านลำต้นกล้วย','school_user'=>'MT12','school_password'=>'plook9986');
$schoolno14=array('school'=>'โรงเรียนโชคชัยกระบี่','school_user'=>'MT13','school_password'=>'plook1333');
$schoolno15=array('school'=>'โรงเรียนโชคชัยลาดพร้าว','school_user'=>'MT14','school_password'=>'plook1356');
$schoolno16=array('school'=>'โรงเรียนพระหฤทัยดอนเมือง','school_user'=>'MT15','school_password'=>'plook153');
$schoolno17=array('school'=>'โรงเรียนเขมะสิริอนุสสรณ์','school_user'=>'MT16','school_password'=>'plook326');
$schoolno18=array('school'=>'โรงเรียนโชคชัยรังสิต','school_user'=>'MT17','school_password'=>'plook2777');
$schoolno19=array('school'=>'โรงเรียนวัดบึงทองหลาง','school_user'=>'MT18','school_password'=>'plook4432');
$schoolno20=array('school'=>'โรงเรียนวัดนิมมานรดี','school_user'=>'MT19','school_password'=>'plook2523');
$schoolno21=array('school'=>'โรงเรียนสามเสนนอก','school_user'=>'MT20','school_password'=>'plook3124');
$schoolno22=array('school'=>'โรงเรียนสายน้ำทิพย์','school_user'=>'MT21','school_password'=>'plook347');
$school_data_all=array('0'=>$schoolno0,
                       '1'=>$schoolno1,
                       '2'=>$schoolno2,
                       '3'=>$schoolno3,
                       '4'=>$schoolno4,
                       '5'=>$schoolno5,
                       '6'=>$schoolno6,
                       '7'=>$schoolno7,
                       '8'=>$schoolno8,
                       '9'=>$schoolno9,
                       '10'=>$schoolno10,
                       '11'=>$schoolno11,
                       '12'=>$schoolno12,
                       '13'=>$schoolno13,
                       '14'=>$schoolno14,
                       '15'=>$schoolno15,
                       '16'=>$schoolno16,
                       '17'=>$schoolno17,
                       '18'=>$schoolno18,
                       '19'=>$schoolno19,
                       '20'=>$schoolno20,
                       '21'=>$schoolno21,
                       '22'=>$schoolno22,
                       );
                       
# echo '<pre>  school_data=>'; print_r($school_data); echo '</pre>'; 
# echo '<pre>  school_data_all=>'; print_r($school_data_all); echo '</pre>';   die(); 
$chk=array();                  
if($school_data_all){
foreach ($school_data_all as $key => $value){
$arr=array();
$school=$value['school'];
$schooluser=$value['school_user'];
$schoolpassword=$value['school_password'];
     $arrn['login']['school']=$school;
     $arrn['login']['user']=$schooluser;
     #$arrn['login']['password']=$schoolpassword;
     $arrn['login']['status']=1;
     
if($school_user==$schooluser && $school_password==$schoolpassword){
$chk[]=$arrn['login'];   
}           
 }
}   

if($chk==null){
      ####################################
     $module_name='school login 1';
     $data=array('school'=>'','schooluser'=>'','status'=>0); 
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>201), 
							'data'=> $data),201);
                                   die();
     #################################### 
}else{
      ####################################
     $module_name='school login 2';
     $chk=$chk['0'];
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $chk),200);
                                   die();
     #################################### 
}
     


             
###############################################
}
public function schoolloginget_get(){
###############################################
//http://www.trueplookpanya.com/webservice/api/examination/schoolloginget?school_user=MT01&school_password=plook3391
$school_user=@$this->input->get('school_user'); 
$school_password=@$this->input->get('school_password'); 
###############################################
$data=array('schooluser'=>$school_user,'school_password'=>$school_password);
####################################
if($school_user=='' || $schoolpassword=''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>'school',
							'message'=>'Error school_user or school_password is null ',
							'status'=>TRUE,
							'code'=>201), 
							'data'=> null),201);
                                   die();
####################################
}

$school_data=array('school_user'=>$school_user,
                  'school_password'=>$school_password,
                  );
################################################
$schoolno0=array('school'=>'มัธยมป่ากลาง','school_user'=>'na','school_password'=>'na');
$schoolno1=array('school'=>'โรงเรียนทดสอบระบบ','school_user'=>'MT00','school_password'=>'it1234');
$schoolno2=array('school'=>'โรงเรียนวัดยานนาวา','school_user'=>'MT01','school_password'=>'plook3391');
$schoolno3=array('school'=>'โรงเรียนวัดพระยาสุเรนทร์ (บุญมีอนุกูล)','school_user'=>'MT02','school_password'=>'plook4486');
$schoolno4=array('school'=>'โรงเรียนวัดหงส์ปทุมาวาส','school_user'=>'MT03','school_password'=>'plook3321');
$schoolno5=array('school'=>'โรงเรียนพระแม่มารีพระโขนง','school_user'=>'MT04','school_password'=>'plook2456');
$schoolno6=array('school'=>'โรงเรียนวัดเทพลีลา','school_user'=>'MT05','school_password'=>'plook7769');
$schoolno7=array('school'=>'สาธิตม.บ้านสมเด็จฯ (ประถม)','school_user'=>'MT06','school_password'=>'plook7423');
$schoolno8=array('school'=>'โรงเรียนบีคอนเฮาส์แย้มสอาดรังสิต','school_user'=>'MT07','school_password'=>'plook4221');
$schoolno9=array('school'=>'โรงเรียนหนองจอกกงลิบฮัวเคียว','school_user'=>'MT08','school_password'=>'plook5542');
$schoolno10=array('school'=>'โรงเรียนอนุบาลนนทบุรี','school_user'=>'MT09','school_password'=>'plook7421');
$schoolno11=array('school'=>'โรงเรียนเซนต์โยเซฟ บางนา','school_user'=>'MT10','school_password'=>'plook110');
$schoolno12=array('school'=>'โรงเรียนวัดแสนเกษม','school_user'=>'','school_password'=>'plook1245');
$schoolno13=array('school'=>'โรงเรียนบ้านลำต้นกล้วย','school_user'=>'MT12','school_password'=>'plook9986');
$schoolno14=array('school'=>'โรงเรียนโชคชัยกระบี่','school_user'=>'MT13','school_password'=>'plook1333');
$schoolno15=array('school'=>'โรงเรียนโชคชัยลาดพร้าว','school_user'=>'MT14','school_password'=>'plook1356');
$schoolno16=array('school'=>'โรงเรียนพระหฤทัยดอนเมือง','school_user'=>'MT15','school_password'=>'plook153');
$schoolno17=array('school'=>'โรงเรียนเขมะสิริอนุสสรณ์','school_user'=>'MT16','school_password'=>'plook326');
$schoolno18=array('school'=>'โรงเรียนโชคชัยรังสิต','school_user'=>'MT17','school_password'=>'plook2777');
$schoolno19=array('school'=>'โรงเรียนวัดบึงทองหลาง','school_user'=>'MT18','school_password'=>'plook4432');
$schoolno20=array('school'=>'โรงเรียนวัดนิมมานรดี','school_user'=>'MT19','school_password'=>'plook2523');
$schoolno21=array('school'=>'โรงเรียนสามเสนนอก','school_user'=>'MT20','school_password'=>'plook3124');
$schoolno22=array('school'=>'โรงเรียนสายน้ำทิพย์','school_user'=>'MT21','school_password'=>'plook347');
$school_data_all=array('0'=>$schoolno0,
                       '1'=>$schoolno1,
                       '2'=>$schoolno2,
                       '3'=>$schoolno3,
                       '4'=>$schoolno4,
                       '5'=>$schoolno5,
                       '6'=>$schoolno6,
                       '7'=>$schoolno7,
                       '8'=>$schoolno8,
                       '9'=>$schoolno9,
                       '10'=>$schoolno10,
                       '11'=>$schoolno11,
                       '12'=>$schoolno12,
                       '13'=>$schoolno13,
                       '14'=>$schoolno14,
                       '15'=>$schoolno15,
                       '16'=>$schoolno16,
                       '17'=>$schoolno17,
                       '18'=>$schoolno18,
                       '19'=>$schoolno19,
                       '20'=>$schoolno20,
                       '21'=>$schoolno21,
                       '22'=>$schoolno22,
                       );
                       
# echo '<pre>  school_data=>'; print_r($school_data); echo '</pre>'; 
# echo '<pre>  school_data_all=>'; print_r($school_data_all); echo '</pre>';   die(); 
$chk=array();                  
if($school_data_all){
foreach ($school_data_all as $key => $value){
$arr=array();
$school=$value['school'];
$schooluser=$value['school_user'];
$schoolpassword=$value['school_password'];
     $arrn['login']['school']=$school;
     $arrn['login']['user']=$schooluser;
     #$arrn['login']['password']=$schoolpassword;
     $arrn['login']['status']=1;
     
if($school_user==$schooluser && $school_password==$schoolpassword){
$chk[]=$arrn['login'];   
}           
 }
}   

if($chk==null){
      ####################################
     $module_name='school login 1';
     $data=array('school'=>'','schooluser'=>'','status'=>0); 
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>201), 
							'data'=> $data),201);
                                   die();
     #################################### 
}else{
      ####################################
     $module_name='school login 2';
     $chk=$chk['0'];
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $chk),200);
                                   die();
     #################################### 
}
     


             
###############################################
}
public function schoolloout_post(){
@session_start();
$this->load->library('session');
$school_session=$this->session->userdata('school_session'); 
$school_session=$this->session->userdata('schoolexamination');   
session_destroy();
// จะลบตัวแปรเซซชั่นหมด
$module_name='school loout post';
$examscoreuserlog='logout';
if($examscoreuserlog!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $examscoreuserlog),200);
}elseif($examscoreuserlog==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $examscoreuserlog),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
 #$urldirect=site_url('event/simtest/exam/schoollogin'); 
 #header( "location: $urldirect" );exit();   
 
 
}
######## school Setting ########
##no.1
public function reportbyschool_get(){
ob_end_clean();
$module_name='reportbyschool';
##########################
$exam_id=@$this->input->get('exam_id');
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examination=$examinationrs['list']['0'];
$examination_percent=(int)$examination->exam_percent;   
###############################
$school_name=@$this->input->get('school_name');
$school_level=@$this->input->get('school_level');
if($school_level==''){$school_level=null;}
if($school_name==null ||$school_name=='') {
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$job_edu_name=$school_name;
$deletekey=@$this->input->get('deletekey');
$userslist=$this->Model_examination->school_users($school_name,$school_level,$deletekey);
$user_list_arr=array();
if (is_array($userslist)){
foreach($userslist as $key=>$v){
$arr=array();
     $user_id=(int)$v->user_id;
     $arr['a']['user_id']=$user_id;
     $arr['a']['member_id']=$v->member_id;
     $arr['a']['firstname']=$v->firstname;
     $arr['a']['lastname']=$v->lastname;
     /*
     $arr['a']['user_fullname']=$v->user_fullname;
     $arr['a']['edu_name']=$v->edu_name;
     $arr['a']['edu_level']=$v->edu_level;
     $arr['a']['id_number']=$v->id_number;
     $arr['a']['job_name']=$v->job_name;
     $arr['a']['address']=$v->address;
     $arr['a']['birthdate']=$v->birthdate;
     $arr['a']['edu_degree']=$v->edu_degree;
     $arr['a']['email']=$v->email;
     $arr['a']['province']=$v->province;
     $arr['a']['postcode']=$v->psn_postcode;
     $arr['a']['sex']=$v->sex;
     */
#####################################################
$urla='api/examination/examinationreportbyuser?exam_id='.$exam_id.'&user_id='.$user_id;
$url=base_url($urla);
$response=file_get_contents($url);
$dataallscore=json_decode($response);
$dataallscore_data=$dataallscore->data;
$user_score_test=$dataallscore_data->user_score;
if($user_score_test==''){$user_score_test=0;}
#######################################

$user_score_arrs=array('score'=>$dataallscore_data->score,
                       'score_mesage'=>$dataallscore_data->score_mesage,
                       'score_status'=>$dataallscore_data->score_status,
                       'exam_percent'=>$dataallscore_data->exam_percent,
                       'exam_percents'=>$dataallscore_data->exam_percents,
                       'user_score'=>$user_score_test,
                       'total_score'=>$dataallscore_data->total_score,
                       'user_id'=>$dataallscore_data->user_id,
                       'duration_time'=>$dataallscore_data->duration_time,
                       'percent'=>$dataallscore_data->percent,
                       'percent_score'=>$dataallscore_data->percent_score,
                       );
//$arr['a']['user_scores']=$user_score_arrs;

#######################################
$arr['a']['score']=$dataallscore_data->score;
$arr['a']['score_mesage']=$dataallscore_data->score_mesage;
$arr['a']['score_status']=$dataallscore_data->score_status;
$arr['a']['exam_percent']=$dataallscore_data->exam_percent;
$arr['a']['exam_percents']=$dataallscore_data->exam_percents;
$arr['a']['user_score']=$user_score_test;
$arr['a']['percent']=$dataallscore_data->percent;
$arr['a']['percent_score']=$dataallscore_data->percent_score;
$arr['a']['total_score']=$dataallscore_data->total_score;
$arr['a']['duration_time']=$dataallscore_data->duration_time;
#####################################################
#####################################################
#####################################################
if($user_score_test>0){$user_list_arr[]=$arr['a'];}
#####################################################
#####################################################
}}
$user_test_count=(int)count($user_list_arr);
$user_count_all=(int)count($userslist);
$user_not_test_count=(int)$user_count_all-$user_test_count;
$user_test_percent=($user_test_count*100)/$user_count_all;
#$user_test_percent=round($user_test_percent,2);
$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_UP);  
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_DOWN); 
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_EVEN); 
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_ODD);  
$user_not_test_percent=($user_not_test_count*100)/$user_count_all;
$user_score_avg=$this->Model_examination->score_avg($exam_id,$school_name,$school_level);
$userscoreavg=$user_score_avg['0'];
$score_avg=$userscoreavg->score_avg;
$question_total=$userscoreavg->question_total;
$score_avg=round($score_avg,2,PHP_ROUND_HALF_UP);  
###################################
$user_passanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key1=>$w1){
$arr=array();
     $user_id=(int)$w1['user_id'];
     $arrn['c']['user_id']=$user_id;
     $arrn['c']['member_id']=$w1['member_id'];
     $arrn['c']['firstname']=$w1['firstname'];
     $arrn['c']['lastname']=$w1['lastname'];
     $arrn['c']['user_fullname']=$w1['user_fullname'];
     $arrn['c']['edu_name']=$w1['edu_name'];
     $arrn['c']['edu_level']=$w1['edu_level'];
     $arrn['c']['id_number']=$w1['id_number'];
     $arrn['c']['job_name']=$w1['job_name'];
     $arrn['c']['address']=$w1['address'];
     $arrn['c']['birthdate']=$w1['birthdate'];
     $arrn['c']['edu_degree']=$w1['edu_degree'];
     $arrn['c']['email']=$w1['email'];
     $arrn['c']['province']=$w1['province'];
     $arrn['c']['postcode']=$w1['psn_postcode'];
     $arrn['c']['sex']=$w1['sex'];
     $arrn['c']['score_mesage']=$w1['score_mesage'];
     $score_status=(int)$w1['score_status'];
     $arrn['c']['score_status']=$score_status;
     $arrn['c']['score']=$w1['score'];
     $arrn['c']['percent']=$w1['percent'];
     $arrn['c']['percent_score']=$w1['percent_score'];
     if($score_status==1){$user_passanexam_arr[]=$arrn['c'];}
}}
$user_passanexam_count=count($user_passanexam_arr);
###################################
$user_passanexam_count=count($user_passanexam_arr);
$user_passanexam_percent=($user_passanexam_count*100)/$user_test_count;
$user_passanexam_percent=round($user_passanexam_percent,0,PHP_ROUND_HALF_UP); 
###################################
###################################
/*
$usernotpassanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key2=>$w2){
$arr=array();
     $user_id=(int)$w2['user_id'];
     $arrm['d']['user_id']=$user_id;
     $arrm['d']['member_id']=$w2['member_id'];
     $arrm['d']['firstname']=$w2['firstname'];
     $arrm['d']['lastname']=$w2['lastname'];
     $arrm['d']['user_fullname']=$w2['user_fullname'];
     $arrm['d']['edu_name']=$w2['edu_name'];
     $arrm['d']['edu_level']=$w2['edu_level'];
     $arrm['d']['id_number']=$w2['id_number'];
     $arrm['d']['job_name']=$w2['job_name'];
     $arrm['d']['address']=$w2['address'];
     $arrm['d']['birthdate']=$w2['birthdate'];
     $arrm['d']['edu_degree']=$w2['edu_degree'];
     $arrm['d']['email']=$w2['email'];
     $arrm['d']['province']=$w2['province'];
     $arrm['d']['postcode']=$w2['psn_postcode'];
     $arrm['d']['sex']=$w2['sex'];
     $arrm['d']['score_mesage']=$w2['score_mesage'];
     $score_status=(int)$w2['score_status'];
     $arrm['d']['score_status']=$score_status;
     $arrm['d']['score']=$w2['score'];
     $arrm['d']['percent']=$w2['percent'];
     $arrm['d']['percent_score']=$w2['percent_score'];
     if($score_status==0){$usernotpassanexam_arr[]=$arrm['d'];}
}}
$usernotpassanexam_count=count($usernotpassanexam_arr);
$usernotpassanexam_percent=($usernotpassanexam_count*100)/$user_test_count;
$usernotpassanexam_percent=round($usernotpassanexam_percent,0,PHP_ROUND_HALF_UP); 
*/
###################################
###############################
#$resultsdatascore1=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$resultsdatascore=$this->Model_examination->score_value_exam_id_job_edu_name($exam_id,$job_edu_name,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$areachart=array(
       //'chart1'=>$choicescorearr,
       'chart2'=>$scoreArray2
       );
############################################################
$dataall=array('school_name'=>$school_name,
               'school_level'=>$school_level,
               'examination'=>$examination,
               //'user_do_test'=>$user_list_arr,
               'user_do_test_count'=>$user_test_count,
               //'user_list_all'=>$userslist,
               'user_count_all'=>$user_count_all,
               //'user_not_test_count'=>$user_not_test_count,
               'test_percent'=>(int)$user_test_percent,
               //'test_percents'=>(int)$user_test_percent.' %',
               //'nottest_percent'=>(int)$user_not_test_percent,
               //'nottest_percents'=>(int)$user_not_test_percent.' %',
               'scoreavg'=>$score_avg,
               //'question_total'=>$question_total,
               'user_passanexam_count'=>$user_passanexam_count,
               'user_passanexam_percent'=>$user_passanexam_percent,
               //'user_passanexampercent'=>$user_passanexam_percent.' %',
               //'user_passanexam'=>$user_passanexam_arr,
               //'user_not_passanexam_count'=>$usernotpassanexam_count,
               //'user_not_passanexam'=>$usernotpassanexam_arr,
               //'user_not_passanexam_percent'=>$usernotpassanexam_percent,
               'area_chart'=>$areachart,
               'nod1'=>$examination,
               'nod2'=>$user_test_count.'/'.$user_count_all,
               'nod3'=>$user_passanexam_percent,
               'nod4'=>$score_avg,
               //'nod5'=>$choicescorearr,
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='schoolexaminationlist';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   //'info_cache'=>$cacheinfo,
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        //'info_cache'=>$cacheinfo,
                                        ),204);
                                        
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
##no.2
public function reportbyschoollevel_get(){
ob_end_clean();
$module_name='reportbyschool level';
$exam_id=@$this->input->get('exam_id');
$set=@$this->input->get('set');
$school_name=@$this->input->get('school_name');
$usernamelike=@$this->input->get('usernamelike');
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$deletekey=@$this->input->get('deletekey');
$array=$this->examinationdatam1pre_get();
$dataexamination=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$user_count=$this->Model_examination->job_edu_name_users_account_count($school_name,$deletekey);
$usercount=$user_count['0'];
##########################
if($usernamelike==''){$usernamelike='MT';}
if($school_name==null ||$school_name==''){
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$eduarray=$this->Model_examination->job_edu_name_users_account($school_name,$deletekey);
$edu=array();
if (is_array($eduarray)) {
foreach($eduarray as $keys =>$vw){
$ar=array();
$ar['adu']['school_name']=$school_name;
$school_level=$vw->job_edu_level;
$ar['adu']['school_level']=$school_level;
$ar['adu']['user_count']=$vw->user_count;  
##################### 
//if($set==null){}
####################
/*
$urlapi='api/examination/reportbyschool2?exam_id='.$exam_id.'&school_name='.$school_name.'&school_level='.$school_level.'&deletekey='.$deletekey;
$urlrest=base_url($urlapi);
$response=file_get_contents($urlrest);
$dataallscore=json_decode($response);
$dataallscore_data=$dataallscore->data;
$edulevel=array();
if (is_array($dataallscore_data)) {
foreach($dataallscore_data as $k1 =>$v1){
$ar1=array();
     $ar1['level']['nod1']=$v1->nod1;  
     $ar1['level']['nod2']=$v1->nod2;  
     $ar1['level']['nod3']=$v1->nod3;  
     $ar1['level']['nod4']=$v1->nod4;  
     $ar1['level']['nod5']=$v1->nod5;  
     $ar1['level']['areachart']=$v1->area_chart;  
$edulevel[]=$ar1['level'];
}}
$ar['adu']['leveldata']=$edulevel;  
*/
####################   
 $edu[]=$ar['adu'];
}}else{$edu=null;}

#####################################
###############################
//$resultsdatascore1=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$school_level='';
$resultsdatascore=$this->Model_examination->score_value_exam_id_users_group($exam_id,$school_name,$school_level,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$scorevalue=array(
       'scorearray'=>$scoreArray2,
       //'score'=>$choicescorearr
       );
############################################################
$user_count=(int)$usercount->user_count;
$dataall=array('school_name'=>$school_name,
               'usernamelike'=>$usernamelike,
               'examination'=>$dataexamination['list']['0'],
               'count_user'=>$user_count,
               'eduroom'=>$edu,
               'scorevalue'=>$scorevalue,
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='schoolexaminationlist';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   //'info_cache'=>$cacheinfo
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        //'info_cache'=>$cacheinfo
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
public function reportbyschool2_get(){
ob_end_clean();
$module_name='reportbyschool2';
##########################
$exam_id=@$this->input->get('exam_id');
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examination=$examinationrs['list']['0'];
###############################
$school_name=@$this->input->get('school_name');
$school_level=@$this->input->get('school_level');
if($school_level==''){$school_level=null;}
if($school_name==null ||$school_name=='') {
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$job_edu_name=$school_name;
$deletekey=@$this->input->get('deletekey');
$userslist=$this->Model_examination->school_users($school_name,$school_level,$deletekey);
$user_list_arr=array();
if (is_array($userslist)){
foreach($userslist as $key=>$v){
$arr=array();
     $user_id=(int)$v->user_id;
     $arr['a']['user_id']=$user_id;
     $arr['a']['member_id']=$v->member_id;
     $arr['a']['firstname']=$v->firstname;
     $arr['a']['lastname']=$v->lastname;
     $arr['a']['user_fullname']=$v->user_fullname;
     $arr['a']['edu_name']=$v->edu_name;
     $arr['a']['edu_level']=$v->edu_level;
     $arr['a']['id_number']=$v->id_number;
     $arr['a']['job_name']=$v->job_name;
     $arr['a']['address']=$v->address;
     $arr['a']['birthdate']=$v->birthdate;
     $arr['a']['edu_degree']=$v->edu_degree;
     $arr['a']['email']=$v->email;
     $arr['a']['province']=$v->province;
     $arr['a']['postcode']=$v->psn_postcode;
     $arr['a']['sex']=$v->sex;
#####################################################
$urla='api/examination/examinationreportbyuser?exam_id='.$exam_id.'&user_id='.$user_id;
$url=base_url($urla);
$response=file_get_contents($url);
$dataallscore=json_decode($response);
$dataallscore_data=$dataallscore->data;
$user_score_test=$dataallscore_data->user_score;
if($user_score_test==''){$user_score_test=0;}
#######################################

$user_score_arrs=array('score'=>$dataallscore_data->score,
                       'score_mesage'=>$dataallscore_data->score_mesage,
                       'score_status'=>$dataallscore_data->score_status,
                       'exam_percent'=>$dataallscore_data->exam_percent,
                       'exam_percents'=>$dataallscore_data->exam_percents,
                       'user_score'=>$user_score_test,
                       'total_score'=>$dataallscore_data->total_score,
                       'user_id'=>$dataallscore_data->user_id,
                       'duration_time'=>$dataallscore_data->duration_time,
                       'percent'=>$dataallscore_data->percent,
                       'percent_score'=>$dataallscore_data->percent_score,
                       );
//$arr['a']['user_scores']=$user_score_arrs;

#######################################
$arr['a']['score']=$dataallscore_data->score;
$arr['a']['score_mesage']=$dataallscore_data->score_mesage;
$arr['a']['score_status']=$dataallscore_data->score_status;
$arr['a']['exam_percent']=$dataallscore_data->exam_percent;
$arr['a']['exam_percents']=$dataallscore_data->exam_percents;
$arr['a']['user_score']=$user_score_test;
$arr['a']['percent']=$dataallscore_data->percent;
$arr['a']['percent_score']=$dataallscore_data->percent_score;
$arr['a']['total_score']=$dataallscore_data->total_score;
$arr['a']['duration_time']=$dataallscore_data->duration_time;
#####################################################
#####################################################
#####################################################
if($user_score_test>0){$user_list_arr[]=$arr['a'];}
#####################################################
#####################################################
}}
$user_test_count=(int)count($user_list_arr);
$user_count_all=(int)count($userslist);
$user_not_test_count=(int)$user_count_all-$user_test_count;
$user_test_percent=($user_test_count*100)/$user_count_all;
$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_UP);  
$user_not_test_percent=($user_not_test_count*100)/$user_count_all;
$user_score_avg=$this->Model_examination->score_avg($exam_id,$school_name,$school_level);
$userscoreavg=$user_score_avg['0'];
$score_avg=$userscoreavg->score_avg;
$question_total=$userscoreavg->question_total;
$score_avg=round($score_avg,2,PHP_ROUND_HALF_UP);  
###################################
$user_passanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key1=>$w1){
$arr=array();
     $user_id=(int)$w1['user_id'];
     $arrn['c']['user_id']=$user_id;
     $arrn['c']['member_id']=$w1['member_id'];
     $arrn['c']['firstname']=$w1['firstname'];
     $arrn['c']['lastname']=$w1['lastname'];
     $arrn['c']['user_fullname']=$w1['user_fullname'];
     $arrn['c']['edu_name']=$w1['edu_name'];
     $arrn['c']['edu_level']=$w1['edu_level'];
     $arrn['c']['id_number']=$w1['id_number'];
     $arrn['c']['job_name']=$w1['job_name'];
     $arrn['c']['address']=$w1['address'];
     $arrn['c']['birthdate']=$w1['birthdate'];
     $arrn['c']['edu_degree']=$w1['edu_degree'];
     $arrn['c']['email']=$w1['email'];
     $arrn['c']['province']=$w1['province'];
     $arrn['c']['postcode']=$w1['psn_postcode'];
     $arrn['c']['sex']=$w1['sex'];
     $arrn['c']['score_mesage']=$w1['score_mesage'];
     $score_status=(int)$w1['score_status'];
     $arrn['c']['score_status']=$score_status;
     $arrn['c']['score']=$w1['score'];
     $arrn['c']['percent']=$w1['percent'];
     $arrn['c']['percent_score']=$w1['percent_score'];
     if($score_status==1){$user_passanexam_arr[]=$arrn['c'];}
}}
$user_passanexam_count=count($user_passanexam_arr);
###################################
$user_passanexam_count=count($user_passanexam_arr);
$user_passanexam_percent=($user_passanexam_count*100)/$user_test_count;
$user_passanexam_percent=round($user_passanexam_percent,0,PHP_ROUND_HALF_UP); 
###################################

//$resultsdatascore=$this->Model_examination->score_value_exam_id_job_edu_name($exam_id,$job_edu_name,$deletekey);
$resultsdatascore=$this->Model_examination->score_value_exam_id_users_group($exam_id,$school_name,$school_level,$deletekey);

$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$areachart=array(
       'chart1'=>$choicescorearr,
       'chart2'=>$scoreArray2
       );
############################################################
$dataall=array('school_name'=>$school_name,
               'school_level'=>$school_level,
               'area_chart'=>$areachart,
               'nod1'=>$examination,
               'nod2'=>$user_test_count.'/'.$user_count_all,
               'nod3'=>$user_passanexam_percent,
               'nod4'=>$score_avg,
               'user_test_count'=>$user_test_count,
               'user_count_all'=>$user_count_all,
               //'nod5'=>$choicescorearr,
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='schoolexaminationlist';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   //'info_cache'=>$cacheinfo,
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        //'info_cache'=>$cacheinfo
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
##no.3
public function reportbyschool2level_get(){
ob_end_clean();
$module_name='reportbyschool2 level';
$exam_id=@$this->input->get('exam_id');
$set=@$this->input->get('set');
$school_name=@$this->input->get('school_name');
$usernamelike=@$this->input->get('usernamelike');
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$deletekey=@$this->input->get('deletekey');
$array=$this->examinationdatam1pre_get();
$dataexamination=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$user_count=$this->Model_examination->job_edu_name_users_account_count($school_name,$deletekey);
$usercount=$user_count['0'];
##########################
if($usernamelike==''){$usernamelike='MT';}
if($school_name==null ||$school_name==''){
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$eduarray=$this->Model_examination->job_edu_name_users_account($school_name,$deletekey);
$edu=array();
if (is_array($eduarray)) {
foreach($eduarray as $keys =>$vw){
$ar=array();
$ar['adu']['school_name']=$school_name;
$school_level=$vw->job_edu_level;
$ar['adu']['school_level']=$school_level;
$ar['adu']['user_count']=$vw->user_count;  
##################### 
#if($set==null){}
####################
$urlapi='api/examination/reportbyschool2?exam_id='.$exam_id.'&school_name='.$school_name.'&school_level='.$school_level.'&deletekey='.$deletekey;
$urlrest=base_url($urlapi);
$response=file_get_contents($urlrest);
$dataallscore=json_decode($response);
$dataallscore_data=$dataallscore->data;
/*
$edulevel=array();
if (is_array($dataallscore_data)) {
foreach($dataallscore_data as $k1 =>$v1){
$ar1=array();
     $ar1['level']['nod1']=$v1->nod1;  
     $ar1['level']['nod2']=$v1->nod2;  
     $ar1['level']['nod3']=$v1->nod3;  
     $ar1['level']['nod4']=$v1->nod4;  
     $ar1['level']['nod5']=$v1->nod5;  
     $ar1['level']['areachart']=$v1->area_chart;  
$edulevel[]=$ar1['level'];
}}
$ar['adu']['leveldata']=$edulevel;  
*/
####################
$ar['adu']['leveldata']=$dataallscore_data;     
 $edu[]=$ar['adu'];
}}else{$edu=null;}

#####################################
###############################
$resultsdatascore=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$score_value_exam_id=array(
       'scorearray'=>$scoreArray2,
       'score'=>$choicescorearr
       );
############################################################
$user_count=(int)$usercount->user_count;
$dataall=array('school_name'=>$school_name,
               'usernamelike'=>$usernamelike,
               'examination'=>$dataexamination['list']['0'],
               'count_user'=>$user_count,
               'eduroom'=>$edu,
               'scorevalue'=>$score_value_exam_id,
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='schoolexaminationlist';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   //'info_cache'=>$cacheinfo
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        //'info_cache'=>$cacheinfo
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
##no.4
public function reportschoolreportlevel_get(){
ob_end_clean();
$module_name='report school report level';
##########################
$exam_id=@$this->input->get('exam_id');
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examination=$examinationrs['list']['0'];
###############################
$school_name=@$this->input->get('school_name');
$school_level=@$this->input->get('school_level');
if($school_level==''){$school_level=null;}
if($school_name==null ||$school_name=='') {
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$job_edu_name=$school_name;
$deletekey=@$this->input->get('deletekey');


$userslist2=$this->Model_examination->school_users($school_name,$school_level,$deletekey);
$user_count_all=(int)count($userslist2);
$userslist_md=$this->Model_examination->school_users_exam_score($school_name,$school_level,1,$deletekey);
$userslist=$userslist_md['list'];
$userslist_count=$userslist_md['count'];

$user_list_arr=array();
if (is_array($userslist)){
foreach($userslist as $key=>$v){
$arr=array();
     $user_id=(int)$v->user_id;
     $arr['a']['user_id']=$user_id;
     $arr['a']['member_id']=$v->member_id;
     $arr['a']['firstname']=$v->firstname;
     $arr['a']['lastname']=$v->lastname;
     $arr['a']['user_fullname']=$v->user_fullname;
     $arr['a']['edu_name']=$v->edu_name;
     $arr['a']['edu_level']=$v->edu_level;
     $arr['a']['id_number']=$v->id_number;
     $arr['a']['job_name']=$v->job_name;
     $arr['a']['address']=$v->address;
     $arr['a']['birthdate']=$v->birthdate;
     $arr['a']['edu_degree']=$v->edu_degree;
     $arr['a']['email']=$v->email;
     $arr['a']['province']=$v->province;
     $arr['a']['postcode']=$v->psn_postcode;
     $arr['a']['sex']=$v->sex;
#####################################################
$urla='api/examination/examinationreportbyuser?exam_id='.$exam_id.'&user_id='.$user_id;
$url=base_url($urla);
$response=file_get_contents($url);
$dataallscore=json_decode($response);
$dataallscore_data=$dataallscore->data;
$user_score_test=$dataallscore_data->user_score;
if($user_score_test==''){$user_score_test=0;}
#######################################

$user_score_arrs=array('score'=>$dataallscore_data->score,
                       'score_mesage'=>$dataallscore_data->score_mesage,
                       'score_status'=>$dataallscore_data->score_status,
                       'exam_percent'=>$dataallscore_data->exam_percent,
                       'exam_percents'=>$dataallscore_data->exam_percents,
                       'user_score'=>$user_score_test,
                       'total_score'=>$dataallscore_data->total_score,
                       'user_id'=>$dataallscore_data->user_id,
                       'duration_time'=>$dataallscore_data->duration_time,
                       'percent'=>$dataallscore_data->percent,
                       'percent_score'=>$dataallscore_data->percent_score,
                       );
//$arr['a']['user_scores']=$user_score_arrs;

#######################################
$arr['a']['score']=$dataallscore_data->score;
$arr['a']['score_mesage']=$dataallscore_data->score_mesage;
$arr['a']['score_status']=$dataallscore_data->score_status;
$arr['a']['exam_percent']=$dataallscore_data->exam_percent;
$arr['a']['exam_percents']=$dataallscore_data->exam_percents;
$arr['a']['user_score']=$user_score_test;
$arr['a']['percent']=$dataallscore_data->percent;
$arr['a']['percent_score']=$dataallscore_data->percent_score;
$arr['a']['total_score']=$dataallscore_data->total_score;
$arr['a']['duration_time']=$dataallscore_data->duration_time;
#####################################################
#####################################################
#####################################################
if($user_score_test>0){$user_list_arr[]=$arr['a'];}
#####################################################
#####################################################
}}
$user_test_count=(int)count($user_list_arr);

$user_not_test_count=(int)$user_count_all-$userslist_count;
$user_test_percent=($userslist_count*100)/$user_count_all;
#$user_test_percent=round($user_test_percent,2);
$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_UP);  
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_DOWN); 
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_EVEN); 
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_ODD);  
$user_not_test_percent=($user_not_test_count*100)/$user_count_all;
$user_score_avg=$this->Model_examination->score_avg($exam_id,$school_name,$school_level);
$userscoreavg=$user_score_avg['0'];
$score_avg=$userscoreavg->score_avg;
$question_total=$userscoreavg->question_total;
$score_avg=round($score_avg,2,PHP_ROUND_HALF_UP);  
###################################
$user_passanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key1=>$w1){
$arr=array();
     $user_id=(int)$w1['user_id'];
     $arrn['c']['user_id']=$user_id;
     $arrn['c']['member_id']=$w1['member_id'];
     $arrn['c']['firstname']=$w1['firstname'];
     $arrn['c']['lastname']=$w1['lastname'];
     $arrn['c']['user_fullname']=$w1['user_fullname'];
     $arrn['c']['edu_name']=$w1['edu_name'];
     $arrn['c']['edu_level']=$w1['edu_level'];
     $arrn['c']['id_number']=$w1['id_number'];
     $arrn['c']['job_name']=$w1['job_name'];
     $arrn['c']['address']=$w1['address'];
     $arrn['c']['birthdate']=$w1['birthdate'];
     $arrn['c']['edu_degree']=$w1['edu_degree'];
     $arrn['c']['email']=$w1['email'];
     $arrn['c']['province']=$w1['province'];
     $arrn['c']['postcode']=$w1['psn_postcode'];
     $arrn['c']['sex']=$w1['sex'];
     $arrn['c']['score_mesage']=$w1['score_mesage'];
     $score_status=(int)$w1['score_status'];
     $arrn['c']['score_status']=$score_status;
     $arrn['c']['score']=$w1['score'];
     $arrn['c']['percent']=$w1['percent'];
     $arrn['c']['percent_score']=$w1['percent_score'];
     if($score_status==1){$user_passanexam_arr[]=$arrn['c'];}
}}
$user_passanexam_count=count($user_passanexam_arr);
###################################
$user_passanexam_count=count($user_passanexam_arr);
$user_passanexam_percent=($user_passanexam_count*100)/$user_test_count;
$user_passanexam_percent=round($user_passanexam_percent,0,PHP_ROUND_HALF_UP); 
###################################
###################################
$usernotpassanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key2=>$w2){
$arr=array();
     $user_id=(int)$w2['user_id'];
     $arrm['d']['user_id']=$user_id;
     $arrm['d']['member_id']=$w2['member_id'];
     $arrm['d']['firstname']=$w2['firstname'];
     $arrm['d']['lastname']=$w2['lastname'];
     $arrm['d']['user_fullname']=$w2['user_fullname'];
     $arrm['d']['edu_name']=$w2['edu_name'];
     $arrm['d']['edu_level']=$w2['edu_level'];
     $arrm['d']['id_number']=$w2['id_number'];
     $arrm['d']['job_name']=$w2['job_name'];
     $arrm['d']['address']=$w2['address'];
     $arrm['d']['birthdate']=$w2['birthdate'];
     $arrm['d']['edu_degree']=$w2['edu_degree'];
     $arrm['d']['email']=$w2['email'];
     $arrm['d']['province']=$w2['province'];
     $arrm['d']['postcode']=$w2['psn_postcode'];
     $arrm['d']['sex']=$w2['sex'];
     $arrm['d']['score_mesage']=$w2['score_mesage'];
     $score_status=(int)$w2['score_status'];
     $arrm['d']['score_status']=$score_status;
     $arrm['d']['score']=$w2['score'];
     $arrm['d']['percent']=$w2['percent'];
     $arrm['d']['percent_score']=$w2['percent_score'];
     if($score_status==0){$usernotpassanexam_arr[]=$arrm['d'];}
}}
$usernotpassanexam_count=count($usernotpassanexam_arr);
$usernotpassanexam_percent=($usernotpassanexam_count*100)/$user_test_count;
$usernotpassanexam_percent=round($usernotpassanexam_percent,0,PHP_ROUND_HALF_UP); 
###################################
###############################
#$resultsdatascore1=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$resultsdatascore=$this->Model_examination->score_value_exam_id_job_edu_name($exam_id,$job_edu_name,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$areachart=array(
       'chart1'=>$choicescorearr,
       'chart2'=>$scoreArray2
       );
############################################################

##############****lesson*******##################
$lesson_data=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_exam_id($exam_id,$deletekey);
$lesson_arr=array();
if (is_array($lesson_data)){
foreach($lesson_data as $key =>$vna2){
$arrs=array();
#############################
$exam_id=(int)$vna2->exam_id;
$mul_level_id=(int)$vna2->mul_level_id; 
$lesson_id=(int)$vna2->lesson_id;
$mul_category_id=(int)$vna2->mul_category_id;
$mul_education_book_id=(int)$vna2->mul_education_book_id; 
$arrs['a']['exam_id']=$exam_id;
$arrs['a']['mul_level_id']=$mul_level_id; 
$arrs['a']['lesson_id']=$lesson_id;
$arrs['a']['mul_category_id']=$mul_category_id;
$arrs['a']['mul_education_book_id']=$mul_education_book_id; 
$arrs['a']['book_name']=$vna2->book_name;
$arrs['a']['count_lesson']=$vna2->count_lesson;
$arrs['a']['count_question']=$vna2->count_question;
$arrs['a']['exam_name']=$vna2->exam_name;
$arrs['a']['lesson_name']=$vna2->lesson_name;
$arrs['a']['mul_category_name']=$vna2->mul_category_name; 
$arrs['a']['mul_level_name']=$vna2->mul_level_name; 
$map_question=$this->Model_examination->where_mul_map_exam_question_lesson_id($lesson_id);
#################################
############*******mapquestion_arr*******#############
$mapquestion_arr=array();
if (is_array($map_question)){
foreach($map_question as $no => $vw){
     $ar=array();
     $id=(int)$vw->id;
     $idx=(int)$vw->idx;
     $map_lesson_id=(int)$vw->lesson_id;
     $map_question_id=(int)$vw->question_id;
     //$ar['a']['id']=$id;
     $ar['a']['idx']=$idx;
     //$ar['a']['map_lesson_id']=$map_lesson_id;
     //$ar['a']['map_question_id']=$map_question_id;
     $question_id=(int)$map_question_id;
     $ar['a']['question_id']=$question_id;
     //$ar['a']['standard_level']=$vw->standard_level;
     //$ar['a']['question_detail']=$vw->question_detail;
     ##############################
     $choice_rss_true=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
     $true_choice=$choice_rss_true['0'];
     #$ar['a']['true_choice']=$true_choice;
     $ar['a']['true_choice_ans_id']=(int)$true_choice->id_ans;
     $choice_rss=$this->Model_examination->where_map_choice($question_id,$deletekey);
     ##############################
$choicerss=array();
if(is_array($choice_rss)){
foreach($choice_rss as $keys =>$w){
     $arr2=array();
     $ans_id=(int)$w->ans_id;
     $answer=$w->answer;
     if($answer=='true'){
     $true_ans_id=$ans_id;
     $arr2['ae']['true_ans_id']=$ans_id;
     $arr2['ae']['true_ans_message']='ตัวเลือกที่ถูกต้อง';  
     }else{$arr2['ae']['true_ans_message']='ตัวเลือกที่ไม่ถูกต้อง'; }
     if($answer=='true'){$arr2['ae']['active']=1;$arr2['ae']['answer']=$answer;}else{$arr2['ae']['active']=0;$answer='false';$arr2['ae']['answer']='false';}    
     $arr2['ae']['ans_id']=$ans_id;
     
     //$arr2['ae']['detail']=$w->detail;
     //$arr2['ae']['question_detail']=$w->question_detail;    
     $choicerss[]=$arr2['ae'];
}}   $ar['a']['choice']=$choicerss;
     ##############################
    $mapquestion_arr[]=$ar['a'];
}}
############*******mapquestion_arr*******#############
$arrs['a']['question']=$mapquestion_arr;
$arrs['a']['question_count']=(int)count($mapquestion_arr);
$lesson_arr[]=$arrs['a'];
}}else{$lesson_arr=null;}
##############****lesson*******##################
$dataall=array('school_name'=>$school_name,
               'school_level'=>$school_level,
               'examination'=>$examination,
               'examination_name'=>$examination->exam_name,
               'examination_time'=>$examination->exam_time,
               'examination_percent'=>$examination->exam_percent,
               //'user_do_test'=>$user_list_arr,
               'user_do_test_count'=>$userslist_count,
               //'user_list_all'=>$userslist,
               'user_count_all'=>$user_count_all,
               'user_not_test_count'=>$user_not_test_count,
               'test_percent'=>(int)$user_test_percent,
               'test_percents'=>(int)$user_test_percent.' %',
               'nottest_percent'=>(int)$user_not_test_percent,
               'nottest_percents'=>(int)$user_not_test_percent.' %',
               'scoreavg'=>$score_avg,
               'question_total'=>$question_total,
               'user_passanexam_count'=>$user_passanexam_count,
               'user_passanexam_percent'=>$user_passanexam_percent,
               'user_passanexampercent'=>$user_passanexam_percent.' %',
               'user_passanexam'=>$user_passanexam_arr,
               //'user_not_passanexam_count'=>$usernotpassanexam_count,
               //'user_not_passanexam'=>$usernotpassanexam_arr,
               //'user_not_passanexam_percent'=>$usernotpassanexam_percent,
               'area_chart'=>$areachart,
               'nod1'=>$examination,
               'nod2'=>$userslist_count.'/'.$user_count_all,
               'nod3'=>$user_passanexam_percent,
               'nod4'=>$score_avg,
               'nod5'=>$choicescorearr,
               //'lesson'=>$lesson_arr,
               //'lesson_count'=>(int)count($lesson_arr),
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='schoolexaminationlist';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   #'info_cache'=>$cacheinfo,
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        #'info_cache'=>$cacheinfo,
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
public function reportschoolreportlevellesson_get(){
ob_end_clean();
$exam_id=@$this->input->get('exam_id');
$deletekey=@$this->input->get('deletekey');
$module_name='report school report level lesson';
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}

##########################
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examination=$examinationrs['list']['0'];
###############################
$school_name=@$this->input->get('school_name');
$school_level=@$this->input->get('school_level');
if($school_level==''){$school_level=null;}
if($school_name==null ||$school_name=='') {
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$job_edu_name=$school_name;
 
################################################
################################################
$user_id=@$this->input->get('user_id');
if($user_id==null ||$user_id==0) {
$message='ไม่พบ user_id'.$user_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
#####################questionrs#######################
$questionrs=$this->Model_examination->wherequestion($exam_id,$deletekey);
#echo '<pre> $questionrs=>'; print_r($questionrs); echo '</pre>';  Die();
$questionrslist=$questionrs['list']; 
#########################**************############################
$userslist=$this->Model_examination->school_users($school_name,$school_level,$deletekey);
$user_list_arr=array();
if (is_array($userslist)){
foreach($userslist as $keys => $v){
$arr=array();
     $user_id=(int)$v->user_id;

     //$arr['a']['edu_name']=$v->edu_name;
     //$arr['a']['edu_level']=$v->edu_level;
     //$arr['a']['id_number']=$v->id_number;
     //$arr['a']['job_name']=$v->job_name;
     //$arr['a']['address']=$v->address;
     //$arr['a']['birthdate']=$v->birthdate;
     //$arr['a']['edu_degree']=$v->edu_degree;
     //$arr['a']['email']=$v->email;
     //$arr['a']['province']=$v->province;
     //$arr['a']['postcode']=$v->psn_postcode;
     $arr['a']['sex']=$v->sex;
     #####################################################
     $urla='api/examination/examinationreportbyuser?exam_id='.$exam_id.'&user_id='.$user_id;
     $url=base_url($urla);
     $response=file_get_contents($url);
     $dataallscore=json_decode($response);
     $dataallscore_data=$dataallscore->data;
     $user_score_test=$dataallscore_data->user_score;
     if($user_score_test==''){$user_score_test=0;}
     #####################################################
     $user_score_arrs=array('score'=>$dataallscore_data->score,
                            'score_mesage'=>$dataallscore_data->score_mesage,
                            'score_status'=>$dataallscore_data->score_status,
                            'exam_percent'=>$dataallscore_data->exam_percent,
                            'exam_percents'=>$dataallscore_data->exam_percents,
                            'user_score'=>$user_score_test,
                            'total_score'=>$dataallscore_data->total_score,
                            'user_id'=>$dataallscore_data->user_id,
                            'duration_time'=>$dataallscore_data->duration_time,
                            'percent'=>$dataallscore_data->percent,
                            'percent_score'=>$dataallscore_data->percent_score,
                            );


###################*****user****#########################
$order='asc';$limit='10';
$log_id=@$this->input->get('log_id');
if($log_id==''){$log_id='';}
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member($exam_id,$user_id,$order,$limit,$log_id);
$examscoress=$exam_scoress['list']['0'];
$answer_value=$examscoress->answer_value;
$answervalue=unserialize($answer_value);
#$arr['a']['answer_value']=$answer_value;
//$arr['a']['answervalue']=$answervalue;  
###################*****user****#########################

#####################****doexam_score_user****#######################
$questionrs_doexam_score= array();
     if (is_array($questionrslist)){
     foreach($questionrslist as $key =>$vna){
     $arr=array();
          $question_id=(int)$vna->id;
          
          //$arr['d']['user_id']=$user_id;
          $arr['d']['question_id']=$question_id;
          $arr['d']['exam_id']=(int)$vna->exam_id;
          #$arr['d']['exam_name']=$vna->exam_name;
          //$arr['d']['question_detail']=$vna->question_detail;
          //$arr['d']['question_encode']=$vna->question_encode;
          //$arr['d']['question_score']=$vna->question_score;
          //$arr['d']['question_skill']=$vna->question_skill;
          //$arr['d']['standard_level']=$vna->standard_level;
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
     foreach($choice_rss as $kn =>$w){
     $arr2=array();
     $ans_id=(int)$w->ans_id;
     $answer=$w->answer;
     if($answer=='true'){
                    $true_ans_id=$ans_id;
                    $arr2['ab']['true_ans_id']=$ans_id;
                    $arr2['ab']['true_ans_message']='ตัวเลือกที่ถูกต้อง';
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
     $arr2['ab']['user_answer_mesage']='คุณเลือกข้อนี้';
          if($true_ans_id==$user_ans_id){
               $arr2['ab']['user_ans_id']=$ans_id;  
               $status=1;
               $arr2['ab']['status']=$status; 
               $user_mesage='ตอบถูกได้คะแนน';
               $arr2['ab']['user_mesage']=$user_mesage;
               $answervalues_arr[]=$arrw1['b'];
          }               
        }        
     }}
     ###################################   
     }else{
     $arr2['ab']['true_ans_message']='ตัวเลือกที่ไม่ถูกต้อง';
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
     $arr2['ab']['user_answer_mesage']='คุณเลือกข้อนี้';  
      if($true_ans_id!==$user_ans_id){
          $arr2['ab']['user_ans_id']=$ans_id;  
          $status=0;
          $arr2['ab']['status']=$status; 
          $user_mesage='ตอบผิดไม่ได้คะแนน';  
          $arr2['ab']['user_mesage']=$user_mesage;  
          }              
     }
     }}
     ###################################   
     }
     ##########################
               //$arr2['ab']['answervalues_arr']=$answervalues_arr;
               $arr2['ab']['ans_id']=$ans_id;
               $arr2['ab']['answer']=$answer;
               //$arr2['ab']['detail']=$w->detail;
               //$arr2['ab']['question_detail']=$w->question_detail;
           $choicerss[]=$arr2['ab'];
           }
          }else{$choicerss=null;}
          ####################
          //$arr['d']['choice']=$choicerss;
          $arr['d']['score']=$status;
          $arr['d']['score_mesage']=$user_mesage;
          
/*         
$arr['d']['user_id']=$user_id;
$arr['d']['member_id']=$v->member_id;
$arr['d']['firstname']=$v->firstname;
$arr['d']['lastname']=$v->lastname;
$arr['d']['user_fullname']=$v->user_fullname;
$arr['d']['score']=$dataallscore_data->score;
$arr['d']['score_mesage']=$dataallscore_data->score_mesage;
$arr['d']['score_status']=$dataallscore_data->score_status;
$arr['d']['exam_percent']=$dataallscore_data->exam_percent;
$arr['d']['exam_percents']=$dataallscore_data->exam_percents;
$arr['d']['user_score']=$user_score_test;
$arr['d']['percent']=$dataallscore_data->percent;
$arr['d']['percent_score']=$dataallscore_data->percent_score;
$arr['d']['total_score']=$dataallscore_data->total_score;
$arr['d']['duration_time']=$dataallscore_data->duration_time;
*/   
          
      $questionrs_doexam_score[]=$arr['d'];
      }}
#####################****doexam_score_user****#######################
#####################****doexam_score_user****#######################
     
     $arr['a']['doexam']=$questionrs_doexam_score;
     $arr['a']['user_id']=$user_id;
     $arr['a']['member_id']=$v->member_id;
     $arr['a']['firstname']=$v->firstname;
     $arr['a']['lastname']=$v->lastname;
     $arr['a']['user_fullname']=$v->user_fullname;
     $arr['a']['score']=$dataallscore_data->score;
     $arr['a']['score_mesage']=$dataallscore_data->score_mesage;
     $arr['a']['score_status']=$dataallscore_data->score_status;
     $arr['a']['exam_percent']=$dataallscore_data->exam_percent;
     $arr['a']['exam_percents']=$dataallscore_data->exam_percents;
     $arr['a']['user_score']=$user_score_test;
     $arr['a']['percent']=$dataallscore_data->percent;
     $arr['a']['percent_score']=$dataallscore_data->percent_score;
     $arr['a']['total_score']=$dataallscore_data->total_score;
     $arr['a']['duration_time']=$dataallscore_data->duration_time;
     #$arr['a']['answervalue']=$answervalue;  
     
     if($user_score_test>0){$user_list_arr[]=$arr['a'];}
     //echo '<pre>$questionrs_doexam_score=>';print_r($questionrs_doexam_score);echo '</pre>';Die(); 
     #####################################################
}}
#########################**************############################
//echo '<pre> $user_list_arr=>'; print_r($user_list_arr); echo '</pre>';  Die();

#####################questionrs#######################
$examinationrs1=$this->Model_examination->where_course_examination_id($exam_id,$deletekey);
$examinationr=$examinationrs1['list'];
$examinationrs=$examinationr['0'];

##############****lesson*******##################
##############****lesson*******##################
$lesson_data=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_exam_id($exam_id,$deletekey);
$lesson_arr=array();
if (is_array($lesson_data)){
foreach($lesson_data as $key =>$vna2){
$arrs=array();
#############################
$exam_id=(int)$vna2->exam_id;
$mul_level_id=(int)$vna2->mul_level_id; 
$lesson_id=(int)$vna2->lesson_id;
$mul_category_id=(int)$vna2->mul_category_id;
$mul_education_book_id=(int)$vna2->mul_education_book_id; 
$arrs['a']['exam_id']=$exam_id;
$arrs['a']['mul_level_id']=$mul_level_id; 
$arrs['a']['lesson_id']=$lesson_id;
$arrs['a']['mul_category_id']=$mul_category_id;
$arrs['a']['mul_education_book_id']=$mul_education_book_id; 
$arrs['a']['book_name']=$vna2->book_name;
$arrs['a']['count_lesson']=$vna2->count_lesson;
$arrs['a']['count_question']=$vna2->count_question;
$arrs['a']['exam_name']=$vna2->exam_name;
$arrs['a']['lesson_name']=$vna2->lesson_name;
$arrs['a']['mul_category_name']=$vna2->mul_category_name; 
$arrs['a']['mul_level_name']=$vna2->mul_level_name; 
$map_question=$this->Model_examination->where_mul_map_exam_question_lesson_id($lesson_id);

#################################
############*******mapquestion_arr*******#############
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
}}
################mapquestion_arr####################
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
     ##############################
}}else{$mapquestion_arr=null;}
############*******mapquestion_arr*******#############
#################################
$user_answer_count=(int)count($mapquestion_arr);
$arrs['a']['mapquestion_arr_count']=$user_answer_count;
$arrs['a']['mapquestion_arr']=$mapquestion_arr;
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
/*
$map_content=$this->Model_examination->get_where_mul_map_content_lessonid_join($lesson_id);
$arrs['a']['lesson_content']=$map_content; 
*/
#############################
$lesson_arr[]=$arrs['a'];
}}else{$lesson_arr=null;}
##############****lesson*******##################
##############****lesson*******##################


#########################################################
$dataall=array('school_name'=>$school_name,
               'school_level'=>$school_level,
               //'examination'=>$examinationrs,
               'examination_name'=>$examinationrs->exam_name,
               'lesson'=>$lesson_arr,
               'lesson_count'=>count($lesson_arr),
               'user_list'=>$user_list_arr,
               );
#####################################################



/*
########################*******chart************#########################
########################*******chart************#########################
########################*******chart************#########################
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
########################*******chart************#########################
########################*******chart************#########################
$dataall=array('school_name'=>$school_name,
               'school_level'=>$school_level,
               //'examination'=>$examinationrs,
               'examination_name'=>$examinationrs->exam_name,
               'lesson'=>$lesson_arr,
               'lesson_count'=>count($lesson_arr),
               'user_list'=>$user_list_arr,
               'radarchart'=>$radarchart_arr,
               'condition_chart'=>$condition_chart,
               //'questionrs_doexam_score'=>$questionrs_doexam_score,
               );
               
########################*******chart************#########################
*/

// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   //'info_cache'=>$cacheinfo,
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        //'info_cache'=>$cacheinfo,
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
public function schoolreportleveluser_get(){
ob_end_clean();
$module_name='school report level user';
##########################
$exam_id=@$this->input->get('exam_id');
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examination=$examinationrs['list']['0'];
###############################
$school_name=@$this->input->get('school_name');
$school_level=@$this->input->get('school_level');
if($school_level==''){$school_level=null;}
if($school_name==null ||$school_name=='') {
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$job_edu_name=$school_name;
$deletekey=@$this->input->get('deletekey');
#$userslist1=$this->Model_examination->school_users($school_name,$school_level,$deletekey);
$userslistall_md=$this->Model_examination->school_users_exam_score($school_name,$school_level,null,$deletekey);
$userslistall=$userslistall_md['list'];
$userslistall_count=$userslistall_md['count'];
$userslistall_sql=$userslistall_md['sql'];
$userslistall_cachekey=$userslistall_md['cachekey'];
/*
echo '<pre> $userslistall=>'; print_r($userslistall); echo '</pre>';  
echo '<pre> $userslistall_count=>'; print_r($userslistall_count); echo '</pre>';  
echo '<pre> $userslistall_sql=>'; print_r($userslistall_sql); echo '</pre>'; 
echo '<pre> $userslistall_cachekey=>'; print_r($userslistall_cachekey); echo '</pre>'; Die();
**/



$userall_list_arr=array();
if (is_array($userslistall)){
foreach($userslistall as $key=>$v){
$arrna=array();
     $user_id=(int)$v->user_id;
     $arrna['na']['user_id']=$user_id;
     $arrna['na']['member_id']=$v->member_id;
     $arrna['na']['firstname']=$v->firstname;
     $arrna['na']['lastname']=$v->lastname;
     $arrna['na']['user_fullname']=$v->user_fullname;
     $arrna['na']['edu_name']=$v->edu_name;
     $arrna['na']['edu_level']=$v->edu_level;
     $arrna['na']['sex']=$v->sex;
     $answer_value=$v->answer_value;
     $arrna['na']['answer_value']=$answer_value;
     $arrna['na']['score_value']=$v->score_value;
     $answervalue=unserialize($answer_value);
     //$arrna['na']['answervalue']=$answervalue;
     $arrna['na']['exam_id']=$v->exam_id;
#####################################################
$urla='api/examination/examinationreportbyuser?exam_id='.$exam_id.'&user_id='.$user_id;
$url=base_url($urla);
$response=file_get_contents($url);
$dataallscore=json_decode($response);
$dataallscore_data=$dataallscore->data;
$user_score_test=$dataallscore_data->user_score;
if($user_score_test==''){$user_score_test=0;}
#######################################
$user_score_arrs=array('score'=>$dataallscore_data->score,
                       'score_mesage'=>$dataallscore_data->score_mesage,
                       'score_status'=>$dataallscore_data->score_status,
                       'exam_percent'=>$dataallscore_data->exam_percent,
                       'exam_percents'=>$dataallscore_data->exam_percents,
                       'user_score'=>$user_score_test,
                       'total_score'=>$dataallscore_data->total_score,
                       'user_id'=>$dataallscore_data->user_id,
                       'duration_time'=>$dataallscore_data->duration_time,
                       'percent'=>$dataallscore_data->percent,
                       'percent_score'=>$dataallscore_data->percent_score,
                       );
//$arrna['na']['user_scores']=$user_score_arrs;
#######################################
$arrna['na']['score']=$dataallscore_data->score;
$arrna['na']['score_mesage']=$dataallscore_data->score_mesage;
$arrna['na']['score_status']=$dataallscore_data->score_status;
$arrna['na']['exam_percent']=$dataallscore_data->exam_percent;
$arrna['na']['exam_percents']=$dataallscore_data->exam_percents;
$arrna['na']['user_score']=$user_score_test;
$arrna['na']['percent']=$dataallscore_data->percent;
$arrna['na']['percent_score']=$dataallscore_data->percent_score;
$arrna['na']['total_score']=$dataallscore_data->total_score;
$arrna['na']['duration_time']=$dataallscore_data->duration_time;

#####################################################
#####################################################
#####################################################
$userall_list_arr[]=$arrna['na'];
#####################################################
#####################################################
}}

//echo '<pre> $userslistall=>'; print_r($userslistall); echo '</pre>';  
//echo '<pre> $userslistall_count=>'; print_r($userslistall_count); echo '</pre>';  
//echo '<pre> $userslistall_sql=>'; print_r($userslistall_sql); echo '</pre>'; 
//echo '<pre> $userslistall_cachekey=>'; print_r($userslistall_cachekey); echo '</pre>'; 
//echo '<pre> $userall_list_arr=>'; print_r($userall_list_arr); echo '</pre>'; Die();

$userslist_md=$this->Model_examination->school_users_exam_score($school_name,$school_level,1,$deletekey);
$userslist=$userslist_md['list'];
$userslist_count=$userslist_md['count'];
$userslist_sql=$userslist_md['sql'];
$userslist_cachekey=$userslist_md['cachekey'];
#echo '<pre> $userslist=>'; print_r($userslist); echo '</pre>';  Die();
$user_list_arr=array();
if (is_array($userslist)){
foreach($userslist as $keyma=>$vma){
$arr=array();
     $user_id=(int)$vma->user_id;
     $arr['a']['user_id']=$user_id;
     $arr['a']['member_id']=$vma->member_id;
     $arr['a']['firstname']=$vma->firstname;
     $arr['a']['lastname']=$vma->lastname;
     $arr['a']['user_fullname']=$vma->user_fullname;
     $arr['a']['edu_name']=$vma->edu_name;
     $arr['a']['edu_level']=$vma->edu_level;
     $arr['a']['id_number']=$vma->id_number;
     $arr['a']['job_name']=$vma->job_name;
     //$arr['a']['address']=$vma->address;
     $arr['a']['birthdate']=$vma->birthdate;
     $arr['a']['edu_degree']=$vma->edu_degree;
     $arr['a']['email']=$vma->email;
     //$arr['a']['province']=$vma->province;
     //$arr['a']['postcode']=$vma->psn_postcode;
     $arr['a']['sex']=$vma->sex;
     $answer_value=$vma->answer_value;
     $arr['a']['answer_value']=$answer_value;
     $arr['a']['score_value']=$vma->score_value;
     $answervalue=unserialize($answer_value);
     //$arr['a']['answervalue']=$answervalue;

     $arr['a']['exam_id']=$vma->exam_id;
#####################################################
$urla='api/examination/examinationreportbyuser?exam_id='.$exam_id.'&user_id='.$user_id;
$url=base_url($urla);
$response=file_get_contents($url);
$dataallscore=json_decode($response);
$dataallscore_data=$dataallscore->data;
$user_score_test=$dataallscore_data->user_score;
if($user_score_test==''){$user_score_test=0;}
#######################################
$user_score_arrs=array('score'=>$dataallscore_data->score,
                       'score_mesage'=>$dataallscore_data->score_mesage,
                       'score_status'=>$dataallscore_data->score_status,
                       'exam_percent'=>$dataallscore_data->exam_percent,
                       'exam_percents'=>$dataallscore_data->exam_percents,
                       'user_score'=>$user_score_test,
                       'total_score'=>$dataallscore_data->total_score,
                       'user_id'=>$dataallscore_data->user_id,
                       'duration_time'=>$dataallscore_data->duration_time,
                       'percent'=>$dataallscore_data->percent,
                       'percent_score'=>$dataallscore_data->percent_score,
                       );
//$arr['a']['user_scores']=$user_score_arrs;

#######################################
$arr['a']['score']=$dataallscore_data->score;
$arr['a']['score_mesage']=$dataallscore_data->score_mesage;
$arr['a']['score_status']=$dataallscore_data->score_status;
$arr['a']['exam_percent']=$dataallscore_data->exam_percent;
$arr['a']['exam_percents']=$dataallscore_data->exam_percents;
$arr['a']['user_score']=$user_score_test;
$arr['a']['percent']=$dataallscore_data->percent;
$arr['a']['percent_score']=$dataallscore_data->percent_score;
$arr['a']['total_score']=$dataallscore_data->total_score;
$arr['a']['duration_time']=$dataallscore_data->duration_time;
#####################################################
#####################################################
#####################################################
#if($user_score_test>0){
     $user_list_arr[]=$arr['a'];
#}
#####################################################
#####################################################
}}
$user_test_count=(int)count($user_list_arr);
$user_count_all=(int)count($userall_list_arr);
$user_not_test_count=(int)$user_count_all-$user_test_count;
$user_test_percent=($user_test_count*100)/$user_count_all;
#$user_test_percent=round($user_test_percent,2);
$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_UP);  
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_DOWN); 
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_EVEN); 
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_ODD);  
$user_not_test_percent=($user_not_test_count*100)/$user_count_all;
$user_score_avg=$this->Model_examination->score_avg($exam_id,$school_name,$school_level);
$userscoreavg=$user_score_avg['0'];
$score_avg=$userscoreavg->score_avg;
$question_total=$userscoreavg->question_total;
$score_avg=round($score_avg,2,PHP_ROUND_HALF_UP);  
###################################
$user_passanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key1=>$w1){
$arr=array();
     $user_id=(int)$w1['user_id'];
     $arrn['c']['user_id']=$user_id;
     $arrn['c']['member_id']=$w1['member_id'];
     $arrn['c']['firstname']=$w1['firstname'];
     $arrn['c']['lastname']=$w1['lastname'];
     $arrn['c']['user_fullname']=$w1['user_fullname'];
     $arrn['c']['edu_name']=$w1['edu_name'];
     $arrn['c']['edu_level']=$w1['edu_level'];
     $arrn['c']['id_number']=$w1['id_number'];
     $arrn['c']['job_name']=$w1['job_name'];
     $arrn['c']['address']=$w1['address'];
     $arrn['c']['birthdate']=$w1['birthdate'];
     $arrn['c']['edu_degree']=$w1['edu_degree'];
     $arrn['c']['email']=$w1['email'];
     $arrn['c']['province']=$w1['province'];
     $arrn['c']['postcode']=$w1['psn_postcode'];
     $arrn['c']['sex']=$w1['sex'];
     $arrn['c']['score_mesage']=$w1['score_mesage'];
     $score_status=(int)$w1['score_status'];
     $arrn['c']['score_status']=$score_status;
     $arrn['c']['score']=$w1['score'];
     $arrn['c']['percent']=$w1['percent'];
     $arrn['c']['percent_score']=$w1['percent_score'];
     $arrn['c']['answer_value']=$w1['answer_value'];
     $arrn['c']['exam_id']=$w1['exam_id'];
     if($score_status==1){$user_passanexam_arr[]=$arrn['c'];}
}}
$user_passanexam_count=count($userslist);
###################################
$user_passanexam_count=count($user_passanexam_arr);
$user_passanexam_percent=($user_passanexam_count*100)/$user_test_count;
$user_passanexam_percent=round($user_passanexam_percent,0,PHP_ROUND_HALF_UP); 
###################################
###################################
$usernotpassanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key2=>$w2){
$arr=array();
     $user_id=(int)$w2['user_id'];
     $arrm['d']['user_id']=$user_id;
     $arrm['d']['member_id']=$w2['member_id'];
     $arrm['d']['firstname']=$w2['firstname'];
     $arrm['d']['lastname']=$w2['lastname'];
     $arrm['d']['user_fullname']=$w2['user_fullname'];
     $arrm['d']['edu_name']=$w2['edu_name'];
     $arrm['d']['edu_level']=$w2['edu_level'];
     $arrm['d']['id_number']=$w2['id_number'];
     $arrm['d']['job_name']=$w2['job_name'];
     $arrm['d']['address']=$w2['address'];
     $arrm['d']['birthdate']=$w2['birthdate'];
     $arrm['d']['edu_degree']=$w2['edu_degree'];
     $arrm['d']['email']=$w2['email'];
     $arrm['d']['province']=$w2['province'];
     $arrm['d']['postcode']=$w2['psn_postcode'];
     $arrm['d']['sex']=$w2['sex'];
     $arrm['d']['score_mesage']=$w2['score_mesage'];
     $score_status=(int)$w2['score_status'];
     $arrm['d']['score_status']=$score_status;
     $arrm['d']['score']=$w2['score'];
     $arrm['d']['percent']=$w2['percent'];
     $arrm['d']['percent_score']=$w2['percent_score'];
     $arrm['d']['answer_value']=$w2['answer_value'];
     $arrm['d']['exam_id']=$w2['exam_id'];
     if($score_status==0){$usernotpassanexam_arr[]=$arrm['d'];}
}}
$usernotpassanexam_count=count($usernotpassanexam_arr);
$usernotpassanexam_percent=($usernotpassanexam_count*100)/$user_test_count;
$usernotpassanexam_percent=round($usernotpassanexam_percent,0,PHP_ROUND_HALF_UP); 
###################################
###############################
#$resultsdatascore1=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$resultsdatascore=$this->Model_examination->score_value_exam_id_job_edu_name($exam_id,$job_edu_name,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$areachart=array(
       'chart1'=>$choicescorearr,
       'chart2'=>$scoreArray2
       );
############################################################

##############****lesson*******##################
$lesson_data=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_exam_id($exam_id,$deletekey);
$lesson_arr=array();
if (is_array($lesson_data)){
foreach($lesson_data as $key =>$vna2){
$arrs=array();
#############################
$exam_id=(int)$vna2->exam_id;
$mul_level_id=(int)$vna2->mul_level_id; 
$lesson_id=(int)$vna2->lesson_id;
$mul_category_id=(int)$vna2->mul_category_id;
$mul_education_book_id=(int)$vna2->mul_education_book_id; 
$arrs['a']['exam_id']=$exam_id;
$arrs['a']['mul_level_id']=$mul_level_id; 
$arrs['a']['lesson_id']=$lesson_id;
$arrs['a']['mul_category_id']=$mul_category_id;
$arrs['a']['mul_education_book_id']=$mul_education_book_id; 
$arrs['a']['book_name']=$vna2->book_name;
$arrs['a']['count_lesson']=$vna2->count_lesson;
$arrs['a']['count_question']=$vna2->count_question;
$arrs['a']['exam_name']=$vna2->exam_name;
$arrs['a']['lesson_name']=$vna2->lesson_name;
$arrs['a']['mul_category_name']=$vna2->mul_category_name; 
$arrs['a']['mul_level_name']=$vna2->mul_level_name; 
$map_question=$this->Model_examination->where_mul_map_exam_question_lesson_id($lesson_id);
#################################
############*******mapquestion_arr*******#############
$mapquestion_arr=array();
if (is_array($map_question)){
foreach($map_question as $no => $vw){
     $ar=array();
     $id=(int)$vw->id;
     $idx=(int)$vw->idx;
     $map_lesson_id=(int)$vw->lesson_id;
     $map_question_id=(int)$vw->question_id;
     //$ar['a']['id']=$id;
     $ar['a']['idx']=$idx;
     //$ar['a']['map_lesson_id']=$map_lesson_id;
     //$ar['a']['map_question_id']=$map_question_id;
     $question_id=(int)$map_question_id;
     $ar['a']['question_id']=$question_id;
     //$ar['a']['standard_level']=$vw->standard_level;
     //$ar['a']['question_detail']=$vw->question_detail;
     ##############################
     $choice_rss_true=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
     $true_choice=$choice_rss_true['0'];
     #$ar['a']['true_choice']=$true_choice;
     $ar['a']['true_choice_ans_id']=(int)$true_choice->id_ans;
     $choice_rss=$this->Model_examination->where_map_choice($question_id,$deletekey);
     ##############################
$choicerss=array();
if(is_array($choice_rss)){
foreach($choice_rss as $keys =>$w){
     $arr2=array();
     $ans_id=(int)$w->ans_id;
     $answer=$w->answer;
     if($answer=='true'){
     $true_ans_id=$ans_id;
     $arr2['ae']['true_ans_id']=$ans_id;
     $arr2['ae']['true_ans_message']='ตัวเลือกที่ถูกต้อง';  
     }else{$arr2['ae']['true_ans_message']='ตัวเลือกที่ไม่ถูกต้อง'; }
     if($answer=='true'){$arr2['ae']['active']=1;$arr2['ae']['answer']=$answer;}else{$arr2['ae']['active']=0;$answer='false';$arr2['ae']['answer']='false';}    
     $arr2['ae']['ans_id']=$ans_id;
     
     //$arr2['ae']['detail']=$w->detail;
     //$arr2['ae']['question_detail']=$w->question_detail;    
     $choicerss[]=$arr2['ae'];
}}   $ar['a']['choice']=$choicerss;
     ##############################
    $mapquestion_arr[]=$ar['a'];
}}
############*******mapquestion_arr*******#############
$arrs['a']['question']=$mapquestion_arr;
$arrs['a']['question_count']=(int)count($mapquestion_arr);
$lesson_arr[]=$arrs['a'];
}}else{$lesson_arr=null;}
##############****lesson*******##################

/*
echo '<pre> $userslistall=>'; print_r($userslistall); echo '</pre>';  
echo '<pre> $userslistall_count=>'; print_r($userslistall_count); echo '</pre>';  
echo '<pre> $userslistall_sql=>'; print_r($userslistall_sql); echo '</pre>'; 
echo '<pre> $userslistall_cachekey=>'; print_r($userslistall_cachekey); echo '</pre>'; 
echo '<pre> $userall_list_arr=>'; print_r($userall_list_arr); echo '</pre>'; Die();
*/
$dataall=array('school_name'=>$school_name,
               'school_level'=>$school_level,
               'examination'=>$examination,
               'examination_name'=>$examination->exam_name,
               'examination_time'=>$examination->exam_time,
               'examination_percent'=>$examination->exam_percent,
               'user_do_test'=>$user_list_arr,
               'user_do_test_count'=>$userslist_count,
               'user_list_all'=>$userall_list_arr,
               'user_count_all'=>$userslistall_count,
               'user_not_test_count'=>$user_not_test_count,
               'test_percent'=>(int)$user_test_percent,
               'test_percents'=>(int)$user_test_percent.' %',
               'nottest_percent'=>(int)$user_not_test_percent,
               'nottest_percents'=>(int)$user_not_test_percent.' %',
               'scoreavg'=>$score_avg,
               'question_total'=>$question_total,
               'user_passanexam_count'=>$user_passanexam_count,
               'user_passanexam_percent'=>$user_passanexam_percent,
               'user_passanexampercent'=>$user_passanexam_percent.' %',
               'user_passanexam'=>$user_passanexam_arr,
               'user_not_passanexam_count'=>$usernotpassanexam_count,
               'user_not_passanexam'=>$usernotpassanexam_arr,
               'user_not_passanexam_percent'=>$usernotpassanexam_percent,
               'area_chart'=>$areachart,
               //'nod1'=>$examination,
               //'nod2'=>$user_test_count.'/'.$user_count_all,
               //'nod3'=>$user_passanexam_percent,
               //'nod4'=>$score_avg,
               //'nod5'=>$choicescorearr,
               'lesson'=>$lesson_arr,
               'lesson_count'=>(int)count($lesson_arr),
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   #'info_cache'=>$cacheinfo,
                                   ),200);
}elseif($dataall==''){
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        #'info_cache'=>$cacheinfo,
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
public function schoolreportleveluserchart_get(){
ob_end_clean();
$module_name='school report level user chart';
##########################
$exam_id=@$this->input->get('exam_id');
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examination=$examinationrs['list']['0'];
$examination_percent=(int)$examination->exam_percent;   
###############################
$school_name=@$this->input->get('school_name');
$school_level=@$this->input->get('school_level');
if($school_level==''){$school_level=null;}
if($school_name==null ||$school_name=='') {
$message='ไม่พบ school_name '.$school_name.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$job_edu_name=$school_name;
$deletekey=@$this->input->get('deletekey');
#$userslist1=$this->Model_examination->school_users($school_name,$school_level,$deletekey);
$userslistall_md=$this->Model_examination->school_users_exam_score($school_name,$school_level,null,$deletekey);
$userslistall=$userslistall_md['list'];
$userslistall_count=$userslistall_md['count'];
$userslistall_sql=$userslistall_md['sql'];
$userslistall_cachekey=$userslistall_md['cachekey'];
/* 
echo '<pre> $userslistall=>'; print_r($userslistall); echo '</pre>';  
echo '<pre> $userslistall_count=>'; print_r($userslistall_count); echo '</pre>';  
echo '<pre> $userslistall_sql=>'; print_r($userslistall_sql); echo '</pre>'; 
echo '<pre> $userslistall_cachekey=>'; print_r($userslistall_cachekey); echo '</pre>'; Die();
*/

$userall_list_arr=array();
if (is_array($userslistall)){
foreach($userslistall as $key=>$v){
$arrna=array();
     $user_id=(int)$v->user_id;
     $arrna['na']['user_id']=$user_id;
     //$arrna['na']['member_id']=$v->member_id;
     $arrna['na']['firstname']=$v->firstname;
     $arrna['na']['lastname']=$v->lastname;
     $arrna['na']['user_fullname']=$v->user_fullname;
     //$arrna['na']['edu_name']=$v->edu_name;
     //$arrna['na']['edu_level']=$v->edu_level;
     //$arrna['na']['sex']=$v->sex;
     $answer_value=$v->answer_value;
     $arrna['na']['answer_value']=$answer_value;
     $arrna['na']['score_value']=$v->score_value;
     $answervalue=unserialize($answer_value);
     //$arrna['na']['answervalue']=$answervalue;
     $arrna['na']['exam_id']=$v->exam_id;
#####################################################
$urla='api/examination/examinationreportbyuser?exam_id='.$exam_id.'&user_id='.$user_id;
$url=base_url($urla);
$response=file_get_contents($url);
$dataallscore=json_decode($response);
$dataallscore_data=$dataallscore->data;
$user_score_test=$dataallscore_data->user_score;
if($user_score_test==''){$user_score_test=0;}
#######################################
$user_score_arrs=array('score'=>$dataallscore_data->score,
                       'score_mesage'=>$dataallscore_data->score_mesage,
                       'score_status'=>$dataallscore_data->score_status,
                       'exam_percent'=>$dataallscore_data->exam_percent,
                       'exam_percents'=>$dataallscore_data->exam_percents,
                       'user_score'=>$user_score_test,
                       'total_score'=>$dataallscore_data->total_score,
                       'user_id'=>$dataallscore_data->user_id,
                       'duration_time'=>$dataallscore_data->duration_time,
                       'percent'=>$dataallscore_data->percent,
                       'percent_score'=>$dataallscore_data->percent_score,
                       );
$arrna['na']['user_scores']=$user_score_arrs;
#######################################
$arrna['na']['score']=$dataallscore_data->score;
$arrna['na']['score_mesage']=$dataallscore_data->score_mesage;
$arrna['na']['score_status']=$dataallscore_data->score_status;
$arrna['na']['exam_percent']=$dataallscore_data->exam_percent;
$arrna['na']['exam_percents']=$dataallscore_data->exam_percents;
$arrna['na']['user_score']=$user_score_test;
$arrna['na']['percent']=$dataallscore_data->percent;
$arrna['na']['percent_score']=$dataallscore_data->percent_score;
$arrna['na']['total_score']=$dataallscore_data->total_score;
$arrna['na']['duration_time']=$dataallscore_data->duration_time;
/*
$arrna['na']['score_user']=$dataallscore_data->user_score;
$arrna['na']['score_all']=$dataallscore_data->total_score;
*/
#####################################################
#####################################################
#####################################################
$userall_list_arr[]=$arrna['na'];
#####################################################
#####################################################
}}

//echo '<pre> $userslistall=>'; print_r($userslistall); echo '</pre>';  
//echo '<pre> $userslistall_count=>'; print_r($userslistall_count); echo '</pre>';  
//echo '<pre> $userslistall_sql=>'; print_r($userslistall_sql); echo '</pre>'; 
//echo '<pre> $userslistall_cachekey=>'; print_r($userslistall_cachekey); echo '</pre>'; 
//echo '<pre> $userall_list_arr=>'; print_r($userall_list_arr); echo '</pre>'; Die();

#################################################
#################################################
##############****lesson*******##################
$lesson_data=$this->Model_examination->where_count_lesson_mul_map_exam_question_lesson_exam_id($exam_id,$deletekey);
$lesson_arr=array();
if (is_array($lesson_data)){
foreach($lesson_data as $key =>$vna2){
$arrs=array();
#############################
$exam_id=(int)$vna2->exam_id;
$mul_level_id=(int)$vna2->mul_level_id; 
$lesson_id=(int)$vna2->lesson_id;
$mul_category_id=(int)$vna2->mul_category_id;
$mul_education_book_id=(int)$vna2->mul_education_book_id; 
$arrs['a']['exam_id']=$exam_id;
$arrs['a']['mul_level_id']=$mul_level_id; 
$arrs['a']['lesson_id']=$lesson_id;
$arrs['a']['mul_category_id']=$mul_category_id;
$arrs['a']['mul_education_book_id']=$mul_education_book_id; 
$arrs['a']['book_name']=$vna2->book_name;
$arrs['a']['count_lesson']=$vna2->count_lesson;
$arrs['a']['count_question']=$vna2->count_question;
$arrs['a']['exam_name']=$vna2->exam_name;
$arrs['a']['lesson_name']=$vna2->lesson_name;
$arrs['a']['mul_category_name']=$vna2->mul_category_name; 
$arrs['a']['mul_level_name']=$vna2->mul_level_name; 
#$map_question2=$this->Model_examination->where_mul_map_exam_question_lesson_id($lesson_id);
$map_question=$this->Model_examination->where_map_examquestion_examlessontrue($exam_id,$lesson_id,$deletekey);
#################################
############*******mapquestion_arr*******#############
$mapquestion_arr=array();
if (is_array($map_question)){
foreach($map_question as $no => $vw){
     $ar=array();
     $id=(int)$vw->id;
     $idx=(int)$vw->idx;
     $map_lesson_id=(int)$vw->lesson_id;
     $map_question_id=(int)$vw->question_id;
     //$ar['a']['id']=$id;
     $ar['a']['idx']=$idx;
     //$ar['a']['map_lesson_id']=$map_lesson_id;
     //$ar['a']['map_question_id']=$map_question_id;
     $question_id=(int)$map_question_id;
     $ar['a']['question_id']=$question_id;
     //$ar['a']['standard_level']=$vw->standard_level;
     //$ar['a']['question_detail']=$vw->question_detail;
     ##############################
     $choice_rss_true=$this->Model_examination->where_map_choice_true($question_id,$deletekey);
     $true_choice=$choice_rss_true['0'];
     #$ar['a']['true_choice']=$true_choice;
     $ar['a']['true_choice_ans_id']=(int)$true_choice->id_ans;
     $choice_rss=$this->Model_examination->where_map_choice($question_id,$deletekey);
     ##############################
$choicerss=array();
if(is_array($choice_rss)){
foreach($choice_rss as $keys =>$w){
     ######################
     $arr2=array();
     $answer=$w->answer;
     if($answer=='true'){
          $ans_id=(int)$w->ans_id;
          $true_ans_id=$ans_id;
          $arr2['ae']['true_ans_id']=$ans_id;
          $arr2['ae']['true_ans_message']='ตัวเลือกที่ถูกต้อง';  
          $arr2['ae']['active']=1;
          $arr2['ae']['answer']=$answer;
     }else{
          $ans_id=(int)$w->ans_id;
          $arr2['ae']['true_ans_message']='ตัวเลือกที่ไม่ถูกต้อง'; 
          $arr2['ae']['active']=0;
          $answer='false';
          $arr2['ae']['answer']='false';
     }
     ######################
     $arr2['ae']['ans_id']=$ans_id;
     
     //$arr2['ae']['detail']=$w->detail;
     //$arr2['ae']['question_detail']=$w->question_detail;    
     $choicerss[]=$arr2['ae'];
}}   $ar['a']['choice']=$choicerss;
     ##############################
    $mapquestion_arr[]=$ar['a'];
}}
############*******mapquestion_arr*******#############
$arrs['a']['question']=$mapquestion_arr;
$arrs['a']['question_count']=(int)count($mapquestion_arr);
$lesson_arr[]=$arrs['a'];
}}else{$lesson_arr=null;}
##############****lesson*******##################
#################################################
#################################################
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
$userslist_md=$this->Model_examination->school_users_exam_score($school_name,$school_level,1,$deletekey);
$userslist=$userslist_md['list'];
$userslist_count=$userslist_md['count'];
$userslist_sql=$userslist_md['sql'];
$userslist_cachekey=$userslist_md['cachekey'];
#echo '<pre> $userslist=>'; print_r($userslist); echo '</pre>';  Die();
$user_list_arr=array();
if (is_array($userslist)){
foreach($userslist as $keyma=>$vma){
$arr=array();
     $user_id=(int)$vma->user_id;
     $answer_value=$vma->answer_value;
     $answervalue=unserialize($answer_value);
     $score_value=$vma->score_value;
     
##########lesson#############

$lessonarr2=array();
if (is_array($lesson_arr)){
foreach($lesson_arr as $k => $les){
$lesarr=array();
     $lesson_id=(int)$les['lesson_id'];
     //$lesarr['le']['answervalue']=$answervalue;
     $lesarr['le']['lesson_id']=$lesson_id;
     $lesarr['le']['lesson_name']=$les['lesson_name'];
     $question=$les['question'];
     ######################
     $question_arr2=array();
     if(is_array($question)){
     foreach($question as $kle2=>$les2){
     $arr=array();
          $question_id=(int)$les2['question_id'];
          $true_choice_ans_id=(int)$les2['true_choice_ans_id'];
          /*
          $lesarr['le']['question_id']=$question_id;
          $lesarr['le']['score_value']=$score_value;
          $lesarr['le']['idx']=$les2['idx'];
          $lesarr['le']['true_choice_ans_id']=$true_choice_ans_id;
          */
##################################################
$answervalues_arr=array();
if(is_array($answervalue)){
foreach($answervalue as $question_key =>$val_ans_id){
               $arrw1=array();
               $user_question_id=(int)$question_key;
               $user_ans_id=(int)$val_ans_id;
               //$arrw1['b']['user_question_id']=$user_question_id;
              // $arrw1['b']['user_ans_id']=$user_ans_id;
     if($question_id==$user_question_id){  
          #########################
          if($true_choice_ans_id==$user_ans_id){
               $score=1;
               $les3['le3']['user_ans_id']=$user_ans_id;
               $les3['le3']['score']=$score;
               #$user_mesage='ตอบถูก ได้คะแนน';  
               //$les3['le3']['user_mesage']=$user_mesage;  
               $question_arr2[]=$les3['le3'];
          }

          #########################
     }    
}}
##################################################       
     
     }}
     ######################
     //$lesarr['le']['question']=$question_arr2;
     $question_all_count=(int)count($question);
     $question_score_count=(int)count($question_arr2);
     $usertestpercen=($question_score_count*100)/$question_all_count;
     $usertestpercen=round($usertestpercen,2,PHP_ROUND_HALF_UP);  
     $lesarr['le']['usertestpercen']=$usertestpercen;
     $lesarr['le']['user_test_percen']=$usertestpercen.' % ';
     
     if($usertestpercen<$examination_percent){
           $statustest=0; 
     }else{
          $statustest=1;
     }
     $lesarr['le']['status']=$statustest;
     $lesarr['le']['question_all_count']=$question_all_count;
     $lesarr['le']['question_score_count']=$question_score_count;
     
     
     
     
$lessonarr2[]=$lesarr['le'];
}}
######################

$arr['a']['lesson']=$lessonarr2;
##########lesson#############

     //$arr['a']['member_id']=$vma->member_id;
     $arr['a']['firstname']=$vma->firstname;
     $arr['a']['lastname']=$vma->lastname;
     $arr['a']['user_fullname']=$vma->user_fullname;
     //$arr['a']['edu_name']=$vma->edu_name;
     //$arr['a']['edu_level']=$vma->edu_level;
     //$arr['a']['id_number']=$vma->id_number;
     //$arr['a']['job_name']=$vma->job_name;
     //$arr['a']['address']=$vma->address;
     //$arr['a']['birthdate']=$vma->birthdate;
     //$arr['a']['edu_degree']=$vma->edu_degree;
     //$arr['a']['email']=$vma->email;
     //$arr['a']['province']=$vma->province;
     //$arr['a']['postcode']=$vma->psn_postcode;
     $arr['a']['sex']=$vma->sex;
     //$arr['a']['answer_value']=$answer_value;
     //$arr['a']['answervalue']=$answervalue;
     $arr['a']['exam_id']=$vma->exam_id;
#####################################################
$urla='api/examination/examinationreportbyuser?exam_id='.$exam_id.'&user_id='.$user_id;
$url=base_url($urla);
$response=file_get_contents($url);
$dataallscore=json_decode($response);
$dataallscore_data=$dataallscore->data;
$user_score_test=$dataallscore_data->user_score;
if($user_score_test==''){$user_score_test=0;}
#######################################
$user_score_arrs=array('scores'=>$dataallscore_data->score,
                       'score_mesage'=>$dataallscore_data->score_mesage,
                       'score_status'=>$dataallscore_data->score_status,
                       'exam_percent'=>$dataallscore_data->exam_percent,
                       'exam_percents'=>$dataallscore_data->exam_percents,
                       'user_score'=>$user_score_test,
                       'total_score'=>$dataallscore_data->total_score,
                       'user_id'=>$dataallscore_data->user_id,
                       'duration_time'=>$dataallscore_data->duration_time,
                       'percent'=>$dataallscore_data->percent,
                       'percent_score'=>$dataallscore_data->percent_score,
                       );
//$arr['a']['user_scores']=$user_score_arrs;

#######################################
$arr['a']['scores']=$dataallscore_data->score;
$arr['a']['score']=$dataallscore_data->user_score;
$arr['a']['score_mesage']=$dataallscore_data->score_mesage;
$arr['a']['score_status']=$dataallscore_data->score_status;
$arr['a']['exam_percent']=$dataallscore_data->exam_percent;
$arr['a']['exam_percents']=$dataallscore_data->exam_percents;
$arr['a']['user_score']=$user_score_test;
$arr['a']['percent']=$dataallscore_data->percent;
$arr['a']['percent_score']=$dataallscore_data->percent_score;
$total_score=$dataallscore_data->total_score;
$arr['a']['total_score']=$total_score;
$arr['a']['duration_time']=$dataallscore_data->duration_time;
$arr['a']['scorepertotal']=$score_value.'/'.$total_score;
$arr['a']['user_id']=$user_id;
#####################################################
#####################################################
#####################################################
#if($user_score_test>0){
     $user_list_arr[]=$arr['a'];
#}
#####################################################
#####################################################
}}
$user_test_count=(int)count($user_list_arr);
$user_count_all=(int)count($userall_list_arr);
$user_not_test_count=(int)$user_count_all-$user_test_count;
$user_test_percent=($user_test_count*100)/$user_count_all;
#$user_test_percent=round($user_test_percent,2);
$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_UP);  
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_DOWN); 
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_EVEN); 
#$user_test_percent=round($user_test_percent,2,PHP_ROUND_HALF_ODD);  
$user_not_test_percent=($user_not_test_count*100)/$user_count_all;
$user_score_avg=$this->Model_examination->score_avg($exam_id,$school_name,$school_level);
$userscoreavg=$user_score_avg['0'];
$score_avg=$userscoreavg->score_avg;
$question_total=$userscoreavg->question_total;
$score_avg=round($score_avg,2,PHP_ROUND_HALF_UP);  
###################################
$user_passanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key1=>$w1){
$arr=array();
     $user_id=(int)$w1['user_id'];
     $arrn['c']['user_id']=$user_id;
     $arrn['c']['firstname']=$w1['firstname'];
     $arrn['c']['lastname']=$w1['lastname'];
     $arrn['c']['user_fullname']=$w1['user_fullname'];
     $arrn['c']['sex']=$w1['sex'];
     $arrn['c']['score_mesage']=$w1['score_mesage'];
     $score_status=(int)$w1['score_status'];
     $arrn['c']['score_status']=$score_status;
     $score=$w1['score'];
     $arrn['c']['score']=$score;
     $arrn['c']['percent']=$w1['percent'];
     $arrn['c']['percent_score']=$w1['percent_score'];
     $arrn['c']['exam_id']=$w1['exam_id'];
     $arrn['c']['scorepertotal']=$score.'/'.$question_total;
     if($score_status==1){$user_passanexam_arr[]=$arrn['c'];}
}}
$user_passanexam_count=count($userslist);
###################################
$user_passanexam_count=count($user_passanexam_arr);
$user_passanexam_percent=($user_passanexam_count*100)/$user_test_count;
$user_passanexam_percent=round($user_passanexam_percent,0,PHP_ROUND_HALF_UP); 
###################################
$usernotpassanexam_arr=array();
if (is_array($user_list_arr)){
foreach($user_list_arr as $key2=>$w2){
$arr=array();
     $user_id=(int)$w2['user_id'];
     $arrm['d']['user_id']=$user_id;
     $arrm['d']['firstname']=$w2['firstname'];
     $arrm['d']['lastname']=$w2['lastname'];
     $arrm['d']['user_fullname']=$w2['user_fullname'];
     $arrm['d']['sex']=$w2['sex'];
     $arrm['d']['score_mesage']=$w2['score_mesage'];
     $score_status=(int)$w2['score_status'];
     $arrm['d']['score_status']=$score_status;
     $score=(int)$w2['score'];
     $arrm['d']['score']=$score;
     $arrm['d']['percent']=$w2['percent'];
     $arrm['d']['percent_score']=$w2['percent_score'];
     $arrm['d']['exam_id']=$w2['exam_id'];
     $arrm['d']['scorepertotal']=$score.'/'.$question_total;
     if($score_status==0){$usernotpassanexam_arr[]=$arrm['d'];}
}}
$usernotpassanexam_count=count($usernotpassanexam_arr);
$usernotpassanexam_percent=($usernotpassanexam_count*100)/$user_test_count;
$usernotpassanexam_percent=round($usernotpassanexam_percent,0,PHP_ROUND_HALF_UP); 
###################################
###############################
#$resultsdatascore1=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$resultsdatascore=$this->Model_examination->score_value_exam_id_job_edu_name($exam_id,$job_edu_name,$deletekey);

#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
     }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
/*
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$areachart=array(
       'chart1'=>$choicescorearr,
       'chart2'=>$scoreArray2
       );
*/
############################################################


/*
echo '<pre> $userslistall=>'; print_r($userslistall); echo '</pre>';  
echo '<pre> $userslistall_count=>'; print_r($userslistall_count); echo '</pre>';  
echo '<pre> $userslistall_sql=>'; print_r($userslistall_sql); echo '</pre>'; 
echo '<pre> $userslistall_cachekey=>'; print_r($userslistall_cachekey); echo '</pre>'; 
echo '<pre> $userall_list_arr=>'; print_r($userall_list_arr); echo '</pre>'; Die();
*/
$lesson_na_arr=array();
if (is_array($lesson_arr)){
foreach($lesson_arr as $dna=>$vna){
$arrm=array();
     $lesson_id=(int)$vna['lesson_id'];
     $arrm['dna']['exam_id']=(int)$vna['exam_id']; 
     $arrm['dna']['lesson_id']=$lesson_id;
     $arrm['dna']['exam_name']=$vna['exam_name'];
     $arrm['dna']['lesson_name']=$vna['lesson_name'];
     $arrm['dna']['count_question']=$vna['count_question'];
     $arrm['dna']['count_lesson']=(int)$vna['count_lesson']; 
     $arrm['dna']['question_count']=(int)$vna['question_count']; 
     ################################
     $userdotest_arr=array();
     if (is_array($user_list_arr)){
     foreach($user_list_arr as $dotest=>$valdo){
     $arrms=array();
          $user_id=$valdo['user_id'];
          $arrms['do']['user_id']=$user_id;
          $arrms['do']['user_fullname']=$valdo['user_fullname'];
          $arrms['do']['firstname']=$valdo['firstname'];
          $arrms['do']['lastname']=$valdo['lastname'];
          $lesson=$valdo['lesson']; 
          #############################
          $userdo1testlesson_arr=array();
          if (is_array($lesson)){
          foreach($lesson as $do1test=>$valdo1){
          $arn=array();
                $lessonid=(int)$valdo1['lesson_id'];
               if($lesson_id==$lessonid){
                    $arn['do1']['lesson_id']=$lessonid;
                    $arn['do1']['lesson_name']=$valdo1['lesson_name'];
                    $arn['do1']['question_all_count']=$valdo1['question_all_count'];
                    $arn['do1']['question_score_count']=$valdo1['question_score_count'];
                    $exam_percent=(int)$examination->exam_percent;
                    $arn['do1']['exam_percent']=$exam_percent; 
                    $score_all=$valdo1['question_all_count'];
                    $score_user=$valdo1['question_score_count'];
                    $arn['do1']['score_all']=$score_all;
                    $arn['do1']['score_user']=$score_user;
                    $score_lesion_percent=(int)($score_user*100)/$score_all;
                    $arn['do1']['score_lesion_percent']=$score_lesion_percent; 
               if($score_lesion_percent>=$exam_percent){
                  $arn['do1']['score_mesage']='สอบผ่าน '.$valdo1['lesson_name'];
                  $arn['do1']['score_past_status']=1;  
               }elseif($score_lesion_percent<$exam_percent){
                 $arn['do1']['score_mesage']='สอบไมผ่าน '.$valdo1['lesson_name'];
                 $arn['do1']['score_past_status']=0; 
               }
               $userdo1testlesson_arr[]=$arn['do1'];
               }
          }} 
          $userdo1testlesson=$userdo1testlesson_arr['0'];
          //$arrms['do']['scorelist']=$userdo1testlesson;
          $exam_percent=$examination->exam_percent;
          $score_lesion_percent=($score_user*100)/$score_all;
          $arrms['do']['score_lesion_percent']=$score_lesion_percent;
          $arrms['do']['score_percent']=$score_lesion_percent.' %';
          $arrms['do']['score_user']=$userdo1testlesson['score_user'];
          $arrms['do']['score_all']=$userdo1testlesson['score_all'];
          $arrms['do']['score_mesage']=$userdo1testlesson['score_mesage'];
          $arrms['do']['score_past_status']=$userdo1testlesson['score_past_status'];
          #############################
     $userdotest_arr[]=$arrms['do'];   
     }} 
     //$arrm['dna']['user']=$userdotest_arr;
     $user_count=(int)count($userdotest_arr);
     $arrm['dna']['user_count']=$user_count;
     ################################
     $userdo2testlessonarr=array();
     if (is_array($userdotest_arr)){
     foreach($userdotest_arr as $dt=>$v2){
     $arn_na=array();
          $score_past_status=$v2['score_past_status'];
          $exam_percent=$examination->exam_percent;
          $arn_na['do2']['core_past_status']=$score_past_status;
          $arn_na['do2']['exam_percent']=$exam_percent;
          $arn_na['do2']['user_id']=$v2['user_id'];
          $arn_na['do2']['score_user']=$v2['score_user'];
          $arn_na['do2']['user_fullname']=$v2['user_fullname'];
          $arn_na['do2']['firstname']=$v2['firstname'];
          $arn_na['do2']['lastname']=$v2['lastname'];
          $arn_na['do2']['score_all']=$v2['score_all'];
      if($score_past_status==1){ 
      $userdo2testlessonarr[]=$arn_na['do2']; 
      }  
     }} 
     $exam_percent=$examination->exam_percent;
     $arrm['dna']['exam_percent']=$exam_percent;
     //$arrm['dna']['userdopast']=$userdo2testlessonarr;
     $user_score_past=(int)count($userdo2testlessonarr);
     $userpercentpast=($user_score_past*100)/$user_count;
     $user_percent_past=round($userpercentpast,0,PHP_ROUND_HALF_UP); 
     $arrm['dna']['user_score_past']=$user_score_past;
     $arrm['dna']['user_percent_past']=$user_percent_past;
     $arrm['dna']['user_percent_past_mesage']=$user_percent_past.' %';
     if($user_percent_past>=$exam_percent){
          $user_status_past=1;
          $user_status_past_mesage='ผ่าน';
     }else{
          $user_status_past=0;
          $user_status_past_mesage='ไม่ผ่าน';
     }
     $arrm['dna']['user_status_past']=$user_status_past;
     $arrm['dna']['user_status_past_mesage']=$user_status_past_mesage;
     ################################  
$lesson_na_arr[]= $arrm['dna'];   
}} 
$lesson_chart_sum_column=array('user_column'=>'คะแนนรวม',
                               'percent'=>$user_passanexam_percent,
                               'percent_mesage'=>$user_passanexam_percent.' %',
                               );
                               
                               
                               
#####################**radarchart**###########################  
$radarchart_arr=array();
if(is_array($lesson_na_arr)){
foreach($lesson_na_arr as $k1=>$n1){
$b=array();
$all_count=(int)$n1['question_count'];
$user_count=(int)$n1['user_count'];
$b['e']['lesson_name']=$n1['lesson_name'];
$b['e']['count_user']=$user_count;
$b['e']['countall']=$all_count;
$user_percent_past=$n1['user_percent_past'];
$user_percent_past2=round($user_percent_past,0,PHP_ROUND_HALF_UP); 
$b['e']['percen']=$user_percent_past2;
$b['e']['user_status_past_mesage']=$n1['user_percent_past_mesage'];
$b['e']['user_count']=$n1['user_count'];
$b['e']['user_score_past']=$n1['user_score_past'];
$radarchart_arr[]=$b['e']; 
}}
#####################**radarchart**###########################  

######################### condition_chart########################
$poor_ar=array();
$fair_ar=array();
$good_ar=array();
if($radarchart_arr){ 
foreach($radarchart_arr as $key=> $va1) {
     $chart=array();
        $percen1=(int)$va1['percen'];
           $chart['conditionchart']['lesson_name']=$va1['lesson_name'];
           $chart['conditionchart']['percen']=$percen1;
           $chart['conditionchart']['msg']=$va1['user_score_past'].'/'.$va1['countall'];
           $chart['conditionchart']['percen_mesage']=$va1['user_status_past_mesage'];
          /*
          if($percen1<50){
           $poor_ar[]=$chart['conditionchart'];  
          }elseif($percen1>50 || $percen1<80){
           $fair_ar[]=$chart['conditionchart'];      
          }elseif($percen1>=80){
           $good_ar[]=$chart['conditionchart'];      
          }  
          */ 
          if($percen1<50){
           $poor_ar[]=$chart['conditionchart'];  
          }elseif($percen1>50 || $percen1<=79){
           $fair_ar[]=$chart['conditionchart'];      
          }elseif($percen1>=80){
           $good_ar[]=$chart['conditionchart'];      
          }  
    }    
}
$condition_chart=array('poor'=>$poor_ar,'fair'=>$fair_ar,'good'=>$good_ar);
######################### condition_chart########################

if($radarchart_arr){
   $statradarchart = array();
   foreach ($radarchart_arr as $value) {
              $statradarchart[$value['lesson_name']] = $value['percen'];
     }
   }
$radarchart=$statradarchart;    
      
#########################################################                            
$dataall=array('school_name'=>$school_name,
               'school_level'=>$school_level,
               //'examination'=>$examination,
               'examination_name'=>$examination->exam_name,
               'examination_time'=>$examination->exam_time,
               'examination_percent'=>$examination_percent,
               'user_do_test'=>$user_list_arr,
               'user_do_test_count'=>$userslist_count,
               //'user_list_all'=>$userall_list_arr,
               //'user_count_all'=>$userslistall_count,
               'user_not_test_count'=>$user_not_test_count,
               //'test_percent'=>(int)$user_test_percent,
               //'test_percents'=>(int)$user_test_percent.' %',
               'nottest_percent'=>(int)$user_not_test_percent,
               'nottest_percents'=>(int)$user_not_test_percent.' %',
               'scoreavg'=>$score_avg,
               'question_total'=>$question_total,
               'user_passanexam_count'=>$user_passanexam_count,
               'user_passanexam_percent'=>$user_passanexam_percent,
               'user_passanexampercent'=>$user_passanexam_percent.' %',
               //'user_passanexam'=>$user_passanexam_arr,
               'user_not_passanexam_count'=>$usernotpassanexam_count,
               'user_not_passanexam'=>$usernotpassanexam_arr,
               'user_not_passanexam_percent'=>$usernotpassanexam_percent,
               //'area_chart'=>$areachart,
               //'nod1'=>$examination,
               //'nod2'=>$user_test_count.'/'.$user_count_all,
               //'nod3'=>$user_passanexam_percent,
               //'nod4'=>$score_avg,
               //'nod5'=>$choicescorearr,
               'lesson_chart'=>$lesson_na_arr,
               'lesson_chart_sum_column'=>$lesson_chart_sum_column,
               'lesson_chart_count'=>(int)count($lesson_na_arr),
               //'lesson'=>$lesson_arr,
               'lesson_count'=>(int)count($lesson_arr),
               //'radarchart_arr'=>$radarchart_arr,
               'radarchart'=>$radarchart,
               'condition_chart'=>$condition_chart,
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################



if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,
                                   #'info_cache'=>$cacheinfo,
                                   ),200);
}elseif($dataall==''){
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,
                                        #'info_cache'=>$cacheinfo,
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
public function examinationid_post(){
ob_end_clean();
#######################
$exam_id=@$this->input->post('exam_id');
$member_id=@$this->input->post('member_id');
$score_value=@$this->input->post('score_value');
$date_update=@$this->input->post('date_update');
$duration_sec=@$this->input->post('duration_sec');
$exam_type=@$this->input->post('exam_type');
$user_id=@$this->input->post('user_id');
$answer_value=@$this->input->post('answer_value');
########################
$module_name='examination id';
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$data=array('member_id'=>$member_id,
            'exam_id'=>$exam_id,
            'score_value'=>$score_value,
            'date_update'=>$date_update,
            'duration_sec'=>$duration_sec,
            'exam_type'=>$exam_type,
            'user_id'=>$user_id,
            'answer_value'=>$answer_value,
           );
$table='cvs_course_exam_score';
$dataall=$this->Model_examination->inserttabledata($table,$data);
$module_name='insert cvs_course_exam_score';

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
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
public function recordcounttable_get(){
ob_end_clean();
$module_name='record count table';
#$user_id=@$this->input->post('user_id');
$user_id=@$this->input->get('user_id');
$limit=@$this->input->get('limit');
$datelog=@$this->input->get('datelog');
$deletekey=@$this->input->get('deletekey');
if($limit==''){$limit=100;}
########################
if($user_id==null) {
$message='ไม่พบข้อสอบ '.$user_id.' กรุณาตรวจสอบ';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
exit();
}
$dataall=$this->Model_examination->record_users_account($user_id,$datelog,$limit,$deletekey);
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
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
public function level_get(){
ob_end_clean();
$level_id=@$this->input->get('level_id');
$deletekey=@$this->input->get('deletekey');
$module_name='level all';
$dataarrays=$this->Mul_level_model->get_level_all($deletekey);
$dataarray=$dataarrays['list'];
$dataall=array();
if(is_array($dataarray)){
foreach($dataarray as $key1=>$w1){
$arr=array();
     $mul_level_id=(int)$w1['mul_level_id'];
     $arrn['c']['level_id']=$mul_level_id;
     $arrn['c']['level_name']=$w1['mul_level_name'];
     $arrn['c']['mul_level_parent_id']=$w1['mul_level_parent_id'];
     if($$level_id!=='' && $level_id==$mul_level_id){
      $arrn['c']['active']='1';
     }else{
      $arrn['c']['active']='0';   
     }
$dataall[]=$arrn['c'];
}}
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'dataall'=>$dataarrays),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,'dataall'=>$dataarrays),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
     /*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
     */
}
die();
}
public function category2017_get(){
ob_end_clean();
$level_id=@$this->input->get('level_id');
$deletekey=@$this->input->get('deletekey');
$module_name='category all';
$rs=$this->Category2017_model->get_category_by_parent_id_all($level_id,$deletekey);
$dataarray=$rs['list'];
$sql=$rs['sql'];
if(is_array($dataarray)){
foreach($dataarray as $key1=>$w1){
$arr=array();
     $mul_category_id=(int)$w1['mul_category_id'];
     $arrn['category']['mul_category_id']=$mul_category_id;
     $arrn['category']['mul_parent_id']=(int)$w1['mul_parent_id'];
     $arrn['category']['mul_category_name']=$w1['mul_category_name'];
     $arrn['category']['level']=$w1['level'];
$dataall[]=$arrn['category'];
}}
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
                                   //'rs'=>$rs,
							'data'=>$dataall),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
public function searchall_get(){
ob_end_clean();
$this->load->model('Model_examination'); 
$perpage=@$this->input->get('per_page');  
$searchtype=@$this->input->get('searchtype');  
if($perpage==''){$perpage=10;}
$keyword=@$this->input->get('keyword');
if($keyword==''){$keyword=null;}
$page=@$this->input->get('page');
if($page==''){$page=1;}
$level_id=@$this->input->get('level_id');
if($level_id==''){$level_id=null;}
$category_id=@$this->input->get('category_id');
if($category_id==''){$category_id=null;}
$deletekey=@$this->input->get('deletekey');
if($deletekey==''){$deletekey=null;}
$module_name='search examination all';

##########################

$datalist=$this->Model_examination->get_searchall($keyword,$level_id,$category_id,$perpage,$page,$searchtype,$deletekey);
$dataall=$datalist;
$list=$datalist['list'];
$total_rows=$dataallcountrows->coun_rows;
#$totalrows1=$total_rows; 
$totalrow=$this->Model_examination->get_searchall_count($keyword,$level_id,$category_id,$searchtype,$deletekey);
$totalrows=$totalrow->coun_rows;
$this->load->library('pagination');
$config['api_url'] = base_url('api/examination/searchall').'?keyword='.$keyword.'&level_id='.$level_id.'&category_id='.$category_id.'&per_page='.$perpage.'&page='.$page.'&deletekey=';
$config['base_url'] = 'http://www.trueplookpanya.com/upskill?keyword='.$keyword.'&level_id='.$level_id.'&category_id='.$category_id.'&per_page='.$perpage;
//base_url().'searchall?page='.$page;
$config['total_rows']=$totalrows;
$config['per_page']=$perpage;
$pagetotal=$totalrows/$perpage;
$pagetotals=round($pagetotal,0,PHP_ROUND_HALF_UP);  
$config['page_total']=$pagetotals;
$this->pagination->initialize($config);
$create_links=$this->pagination->create_links();
#echo '$create_links'.$create_links; die(); 
##########################
$databyrootid=$this->Model_examination->get_searchrootid($keyword,$level_id,$category_id,$perpage,$page,$searchtype,$deletekey);
$exaarr=array();
if(is_array($databyrootid)){
  foreach($databyrootid as $key=> $varl){ 
     $mul_root_id=(int)$varl->mul_root_id;
     $ar['a']['mul_root_id']=$mul_root_id;
     $ar['a']['subject_name']=$varl->subject_name;
$exam=$this->Model_examination->get_searchall_root_id($keyword,$level_id,$category_id,$perpage,$page,$searchtype,$mul_root_id,$deletekey);
     $ar['a']['examdata']=$exam;
     $exam_count=count($exam);
     $ar['a']['examdata_count']=$exam_count;
     if($exam_count>0){
         $exaarr[]=$ar['a'];  
     }
                    
  }
}else{$exaarr=null;}
$dataexa=$exaarr;
$datasearch=array('keyword'=>$keyword,
                  'level_id'=>$level_id,
                  'category_id'=>$category_id,
                  );
                                   
if($dataexa!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
                                   'datasearch'=>$datasearch,
                                   'page'=>$page,
                                   'total_rows'=>$totalrows,
                                   //'links'=>$create_links,
                                   //'config'=>$config,
                                   'sql'=>$dataall['sql'],
                                   'data'=>$dataexa,
							//'dataall'=>$list,
                                   ),200);
}elseif($dataexa==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
                                        'datasearch'=>$datasearch,
                                        'page'=>$page,
                                        //'total_rows'=>$totalrows,
                                        //'links'=>$create_links,
                                        //'config'=>$config,
								'data'=> $dataall),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
public function searexaminationcategory_get(){
ob_end_clean();
$this->load->model('Model_examination'); 
$perpage=@$this->input->get('per_page');  
$searchtype=@$this->input->get('searchtype');  
if($perpage==''){$perpage=100;}
$keyword=@$this->input->get('keyword');
if($keyword==''){$keyword=null;}
$page=@$this->input->get('page');
if($page==''){$page=1;}
$level_id=@$this->input->get('level_id');
if($level_id==''){$level_id=null;}
$category_id=@$this->input->get('category_id');
if($category_id==''){$category_id=null;}
$deletekey=@$this->input->get('deletekey');
if($deletekey==''){$deletekey=null;}
$module_name='search examination all';
##########################
$datalist=$this->Model_examination->get_searchall($keyword,$level_id,$category_id,$perpage,$page,$searchtype,$deletekey);
$dataall=$datalist;
$list=$datalist['list'];
$titles=$list['0'];
$title=$titles->subject_name;    
$level_name=$titles->level_name;   
$total_rows=$dataallcountrows->coun_rows;
$totalrow=$this->Model_examination->get_searchall_count($keyword,$level_id,$category_id,$searchtype,$deletekey);
$totalrows=$totalrow->coun_rows;
$this->load->library('pagination');
$config['api_url'] = base_url('api/examination/searchall').'?keyword='.$keyword.'&level_id='.$level_id.'&category_id='.$category_id.'&per_page='.$perpage.'&page='.$page.'&deletekey=';
$config['base_url'] = 'http://www.trueplookpanya.com/upskill?keyword='.$keyword.'&level_id='.$level_id.'&category_id='.$category_id.'&per_page='.$perpage;
//base_url().'searchall?page='.$page;
$config['total_rows']=$totalrows;
$config['per_page']=$perpage;
$pagetotal=$totalrows/$perpage;
$pagetotals=round($pagetotal,0,PHP_ROUND_HALF_UP);  
$config['page_total']=$pagetotals;
$config['title']=$title;
$config['level_name']=$level_name;
$this->pagination->initialize($config);
$create_links=$this->pagination->create_links();
#echo '$create_links'.$create_links; die(); 
##########################
$datasearch=array('keyword'=>$keyword,
                  'level_id'=>$level_id,
                  'category_id'=>$category_id,
                  );       
if($list!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
                                   'datasearch'=>$datasearch,
                                   'page'=>$page,
                                   'total_rows'=>$totalrows,
                                   'links'=>$create_links,
                                   'config'=>$config,
                                   //'sql'=>$dataall['sql'],
                                   'data'=>$list,
                                   'sql'=>$datalist['sql'],
                                   'cachekey'=>$datalist['cachekey'],
							'title'=>$title,
                                   ),200);
}elseif($list==''){
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
                                        'datasearch'=>$datasearch,
                                        'page'=>$page,
                                        'total_rows'=>$totalrows,
                                        'links'=>$create_links,
                                        'config'=>$config,
								'data'=>$list,
                                        'sql'=>$datalist['sql'],
                                        'cachekey'=>$datalist['cachekey'],
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
public function searhexaminationcategory_get(){
ob_end_clean();
$this->load->model('Model_examination'); 
$perpage=@$this->input->get('per_page');  
$searchtype=@$this->input->get('searchtype');  
if($perpage==''){$perpage=100;}
$keyword=@$this->input->get('keyword');
if($keyword==''){$keyword=null;}
$page=@$this->input->get('page');
if($page==''){$page=1;}
$level_id=@$this->input->get('level_id');
if($level_id==''){$level_id=null;}
$category_id=@$this->input->get('category_id');
if($category_id==''){$category_id=null;}
$deletekey=@$this->input->get('deletekey');
if($deletekey==''){$deletekey=null;}
$module_name='search examination all';
##########################
$datalist=$this->Model_examination->get_searchall($keyword,$level_id,$category_id,$perpage,$page,$searchtype,$deletekey);
$dataall=$datalist;
$list=$datalist['list'];
$titles=$list['0'];
$title=$titles->subject_name;    
$total_rows=$dataallcountrows->coun_rows;
$totalrow=$this->Model_examination->get_searchall_count($keyword,$level_id,$category_id,$searchtype,$deletekey);
$totalrows=$totalrow->coun_rows;
$this->load->library('pagination');
$config['api_url'] = base_url('api/examination/searchall').'?keyword='.$keyword.'&level_id='.$level_id.'&category_id='.$category_id.'&per_page='.$perpage.'&page='.$page.'&deletekey=';
$config['base_url'] = 'http://www.trueplookpanya.com/upskill?keyword='.$keyword.'&level_id='.$level_id.'&category_id='.$category_id.'&per_page='.$perpage;
//base_url().'searchall?page='.$page;
$config['total_rows']=$totalrows;
$config['per_page']=$perpage;
$pagetotal=$totalrows/$perpage;
$pagetotals=round($pagetotal,0,PHP_ROUND_HALF_UP);  
$config['page_total']=$pagetotals;
$config['title']=$title;
$this->pagination->initialize($config);
$create_links=$this->pagination->create_links();
#echo '$create_links'.$create_links; die(); 
##########################
$datasearch=array('keyword'=>$keyword,
                  'level_id'=>$level_id,
                  'category_id'=>$category_id,
                  );       
if($list!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
                                   'datasearch'=>$datasearch,
                                   'page'=>$page,
                                   'total_rows'=>$totalrows,
                                   'links'=>$create_links,
                                   'config'=>$config,
                                   //'sql'=>$dataall['sql'],
                                   'data'=>$list,
                                   'sql'=>$datalist['sql'],
                                   'cachekey'=>$datalist['cachekey'],
							'title'=>$title,
                                   ),200);
}elseif($list==''){
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
                                        'datasearch'=>$datasearch,
                                        'page'=>$page,
                                        'total_rows'=>$totalrows,
                                        'links'=>$create_links,
                                        'config'=>$config,
								'data'=>$list,
                                        'sql'=>$datalist['sql'],
                                        'cachekey'=>$datalist['cachekey'],
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
public function views_get(){
ob_end_clean();
$this->load->model('Model_examination'); 
$content_id=@$this->input->get('id');  
$dataall=$this->Model_examination->get_views_user($content_id);
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>$dataall),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
#examinationbyid
public function examinationbyid_get(){
ob_end_clean();
####################################################
$exam_id=@$this->input->get('exam_id');
$deletekey=@$this->input->get('deletekey');
$dataall=$this->Model_examination->get_examid($exam_id,$deletekey);
$module_name='examination id';
if($dataall!==''){  
##############################################
$content_id=$exam_id;
$table='cvs_course_examination';
$cachetime='300';
$this->load->model('Model_view');
$datainfo=$this->Model_view->ViewNumberGet($content_id,$table,$cachetime,$deletekey);
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>$dataall,
                                   'Model'=>'Model_view',
                                   'datainfo'=>$datainfo),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,'info'=>$datainfo),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
public function examinationbyid1_get(){
ob_end_clean();
####################################################
$exam_id=@$this->input->get('exam_id');
$deletekey=@$this->input->get('deletekey');
$dataall=$this->Model_examination->get_examid($exam_id,$deletekey);
$module_name='examination id';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall,'info_cache'=>$cacheinfo),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
##########*******doxamination******############
public function formdoexamination_get(){
ob_end_clean();
$this->load->model('Model_examination');
####################################################
$exam_id=@$this->input->get('exam_id');
$deletekey=@$this->input->get('deletekey');
$exam=$this->Model_examination->get_examid($exam_id,$deletekey);
$doexamination=$this->Model_examination->wherequestion($exam_id,$deletekey);
$formdoexamination=$doexamination['list'];
#######################################
$examqans=$this->Model_examination->where_examination_question_answer($exam_id,$deletekey);
$result_true=array();
if(is_array($examqans)){
foreach($examqans as $k=>$wm){
$arrm=array();
     $arrm['m']=$wm->id_ans;
$result_true[]=$arrm['m'];
}}
################################# 
$formdoexa=array();
if(is_array($formdoexamination)){
foreach($formdoexamination as $key1=>$w1){
$arr=array();
     $question_id=(int)$w1->id;
     $arrn['c']['exam_id']=$w1->exam_id;
     $arrn['c']['question_id']=$question_id;
     $arrn['c']['question_detail']=$w1->question_detail;
     $choice_rss=$this->Model_examination->where_map_choice($question_id,$deletekey);
     $arrn['c']['choice']=$choice_rss;
$formdoexa[]=$arrn['c'];
}}
#######################################
$formdoexa2=array();
if(is_array($formdoexa)){
foreach($formdoexa as $k => $w){
$arr=array();
     $question_id=(int)$w['question_id'];
     //$arr['a']['exam_id']=$w['exam_id'];
     $arr['a']['question_id']=$question_id;
$formdoexa2[]=$arr['a'];
}}
#######################################
$question=array();
if(is_array($formdoexa2)){
foreach($formdoexa2 as $kn1=>$wn1){
$arrn2=array();
     $question_id1=(int)$wn1['question_id'];
     $arrn2['c1']=$question_id1;
$question[]=$arrn2['c1'];
}}
$question_implode=implode(",", $question);
#######################################
$result_true_implode=implode(",",$result_true);
$result_true_serialize=serialize($result_true);
$result_true_implode=implode(",",$result_true);
$dataall=array('exam'=>$exam['0'],
               'formquestionid'=>$formdoexa2,
               'formdo'=>$formdoexa,
               'formdo_count'=>count($formdoexa),
              //'true_serialize'=>$result_true_serialize,
               //'question'=>$question,
               //'question_implode'=>$question_implode,
               //'true_implode'=>$result_true_implode,
               //'true'=>$result_true,
               );
$module_name='form do examination';
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
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
public function formchoice_get(){
ob_end_clean();
$this->load->model('Model_examination');
####################################################
$question_id=@$this->input->get('question_id');
$deletekey=@$this->input->get('deletekey');
$choice_rss=$this->Model_examination->where_map_choice($question_id,$deletekey);
#######################################
$formchoice=array();
if(is_array($choice_rss)){
foreach($choice_rss as $key =>$w1){
$arr=array();
     $ans_id=(int)$w1->ans_id;
     $arrn['choice']['ans_id']=$w1->ans_id;
     $arrn['choice']['question_id']=$question_id;
     $arrn['choice']['detail']=$w1->detail;
     $arrn['choice']['question_detail']=$w1->question_detail;
$formchoice[]=$arrn['choice'];
}}
#######################################
$dataall=$formchoice;
#######################################
$module_name='choice '.$question_id;
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
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
#################################
##########*******memcache*******############
public function doexaminationsendresultpost_post(){
ob_end_clean();
$module_name='do examination send result';
$this->load->model('Model_examination');
####################################################
$post=@$this->input->post(); 
#echo '<pre> $post=>'; print_r($post); echo '</pre>';Die();
if($post==null ||$post==0){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' ..เข่ ไม่ส่งค่าอะไรมาเลย ',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>null),200);
Die();       
}
$exam_id=@$this->input->post('exam_id');





if($exam_id==null ||$exam_id==0){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' ..เข่ ไม่ส่งค่า exam_id มา ',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>null),200);
Die();       
}
$user_id=@$this->input->post('user_id');
######################
$start_time=@$this->input->post('start_time');
$question_set=@$this->input->post('question_set');
$answer_set=@$this->input->post('answer_set');
if($start_time==null){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' ไม่ส่งค่า start_time มา ',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>null),200);
Die();       
}
if($question_set==null){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' ไม่ส่งค่า question_set มา ',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>null),200);
Die();       
}
if($answer_set==null){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' ไม่ส่งค่า answer_set มา ',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>null),200);
Die();       
}
################################################
if($user_id==null ||$user_id==0){
     $user_id=null;
     $member_id=null;
}
else{
$sql_users_account="select member_id from users_account where user_id=$user_id";
$query_users_account=$this->db->query($sql_users_account);
$results_users_account=$query_users_account->result();
$rsusersaccount=$results_users_account['0'];
//echo '<pre> $rsusersaccount=>'; print_r($rsusersaccount); echo '</pre>'; 
$member_id=$rsusersaccount->member_id;
//echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>';Die();
//$memberid=@$this->input->post('member_id');  
}
################################################
//echo '<pre> $post=>'; print_r($post); echo '</pre>';
//echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>'; 
$sql="SELECT COUNT(*) cnt FROM cvs_course_answer WHERE id IN($answer_set) AND answer_ans='true'";
$query=$this->db->query($sql);
$results=$query->result();
$resultsdata=$results['0'];
$total_answer_true=$resultsdata;
$cnt=$total_answer_true->cnt;
//echo '<pre> $cnt=>'; print_r($cnt); echo '</pre>'; 
$question_set_explode=explode(',',$question_set);
$answer_set_explode=explode(',',$answer_set);
$result_set_arr=array_combine($question_set_explode,$answer_set_explode);
$resultsetarr=serialize($result_set_arr);

//echo '<pre> question_set_explode=>'; print_r($question_set_explode); echo '</pre>'; 
//echo '<pre> answer_set_explode=>'; print_r($answer_set_explode); echo '</pre>'; 
//echo '<pre> result_set_arr=>'; print_r($result_set_arr); echo '</pre>'; 
//echo '<pre> resultsetarr=>'; print_r($resultsetarr); echo '</pre>';Die();

$time=time()-$this->base64_decrypt_post($start_time,"-0@+!2");
//echo '<pre> time=>'; print_r($time); echo '</pre>'; 
if($member_id==''||$member_id==0||$member_id==null){
$insertarray=array(
          'member_id'=>null,
          'exam_id'=>$exam_id,
          'score_value'=>$cnt,
          'date_update'=>date('Y-m-d H:i:s'),
          'duration_sec'=>$time,
          'exam_type'=>'out',
          'user_id'=>$user_id,
          'answer_value'=>$resultsetarr);   
  
}else{
$insertarray=array(
          'member_id'=>$member_id,
          'exam_id'=>$exam_id,
          'score_value'=>$cnt,
          'date_update'=>date('Y-m-d H:i:s'),
          'duration_sec'=>$time,
          'exam_type'=>'out',
          'user_id'=>$user_id,
          'answer_value'=>$resultsetarr);   
}

//echo '<pre> $insertarray=>'; print_r($insertarray); echo '</pre>'; Die();


#############################################################
$table='cvs_course_exam_score';
$this->db->insert($table,$insertarray); 
$rows=$this->db->affected_rows(); 
$insert_id=$this->db->insert_id(); 
if($insert_id>1){
     $dataall=array('message'=>'insert id '.$insert_id,'insertarray'=>$insertarray,'exam_id'=>$exam_id,'log_id'=>$insert_id); 
}
else{
    $dataall=array('message'=>'not insert','insertarray'=>$insertarray); 
}
/*
echo '<pre> $insertarray=>'; print_r($insertarray); echo '</pre>';
echo '<pre> $dataall=>'; print_r($dataall); echo '</pre>'; Die();
*/
#############################################################
$module_name='insert exam_id '.$exam_id.' answer by user  '.$user_id;
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall),200);
}
elseif($dataall==''){
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall),204);
}
else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
public function doexaminationsendresultget_get(){
ob_end_clean();
$this->load->model('Model_examination');
####################################################
$get=@$this->input->get();
$exam_id=@$this->input->get('exam_id');
$start_time=@$this->input->get('start_time');
$user_id=@$this->input->get('user_id');
$question_set=@$this->input->get('question_set');
$answer_set=@$this->input->get('answer_set');
################################################

################################################
if($user_id==null) {
$message='ไม่พบ user_id '.$user_id.' กรุณาตรวจสอบ';
$module_name='do examination send result';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
exit();
}
########################
$sql_users_account="select member_id from users_account where user_id=$user_id";
$query_users_account=$this->db->query($sql_users_account);
$results_users_account=$query_users_account->result();
$rsusersaccount=$results_users_account['0'];
//echo '<pre> $rsusersaccount=>'; print_r($rsusersaccount); echo '</pre>'; 
$member_id=$rsusersaccount->member_id;
//echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>';Die();
//$memberid=@$this->input->get('member_id');
//echo '<pre> $member_id=>'; print_r($member_id); echo '</pre>';Die();
########################
################################################

################################################
if($exam_id==null ) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
$module_name='do examination send result';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
exit();
}if($member_id==null) {
$message='ไม่พบ  member_id '.$member_id.' กรุณาตรวจสอบ';
$module_name='do examination send result';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
exit();
}if($question_set==null) {
$message='ไม่พบ  question_set '.$question_set.' กรุณาตรวจสอบ';
$module_name='do examination send result';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
exit();
}if($answer_set==null) {
$message='ไม่พบ  ansewr result_set '.$answer_set.' กรุณาตรวจสอบ';
$module_name='do examination send result';
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
exit();
}
#############################################################
$sql="SELECT COUNT(*) cnt FROM cvs_course_answer WHERE id IN($answer_set) AND answer_ans='true'";
$query=$this->db->query($sql);
$results=$query->result();
$resultsdata=$results['0'];
$total_answer_true=$resultsdata;
$cnt=$total_answer_true->cnt;
#echo '<pre> $cnt=>'; print_r($cnt); echo '</pre>'; Die();
$time=time()-$this->base64_decrypt_get($start_time,"-0@+!2");

$question_set_explode=explode(',',$question_set);
$answer_set_explode=explode(',',$answer_set);
$result_set_arr=array_combine($question_set_explode,$answer_set_explode);
$resultsetarr=serialize($result_set_arr);


$insertarray=array(
          'member_id'=>$member_id,
          'exam_id'=>$exam_id,
          'score_value'=>$cnt,
          'date_update'=>date('Y-m-d H:i:s'),
          'duration_sec'=>$time,
          'exam_type'=>'out',
          'user_id'=>$user_id,
          'answer_value'=>$resultsetarr);   
#echo '<pre> $insertarray=>'; print_r($insertarray); echo '</pre>'; Die();
#############################################################
$table='cvs_course_exam_score';
$this->db->insert($table,$insertarray); 
$rows=$this->db->affected_rows(); 
$insert_id=$this->db->insert_id(); 

if($insert_id>1){
     $dataall=array('message'=>'insert id '.$insert_id,'insertarray'=>$insertarray); 
}else{
    $dataall=array('message'=>'not insert','insertarray'=>$insertarray); 
}
#############################################################
$module_name='insert exam_id '.$exam_id.' answer by user  '.$user_id;
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
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
public function examscoreuserlog_get(){
ob_end_clean();
$module_name='exam score user log ';
$this->load->model('Model_examination');
####################################################
$exam_id=@$this->input->get('exam_id');
$orderby=@$this->input->get('orderby');
$log_id=@$this->input->get('log_id');
if($log_id==''){$log_id='';}
$log_id=(int)$log_id;
$deletekey=@$this->input->get('deletekey');


if($exam_id==null ||$exam_id==0){
     $examscoreuserlog=null;
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' exam_id ไม่ส่งค่าส่งมา  ',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>null),200);
Die();       
}
$limit=@$this->input->get('limit');
$user_id=@$this->input->get('user_id');
if($user_id==null ||$user_id==0){
     $user_id==null; $limit=1;
}else{
     if($limit==null ){$limit=100;}
}

/*

if userid<0 && logid<0 then return false
elseif userid>0 and logid<=0 then ดึงทั้งหมดของ user นั้น 
elseif logid>0 then ดึงอันเดียวของ logid นั้น
endif

*/
/*
$user_id=(int)$user_id;
if($user_id==null ||$user_id==0){
     #################################################
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' ไม่พบ user_id หรือ ไม่ส่งค่า user_id ส่งมา ',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>null),200);
     #################################################                              
Die();       
}
*/
if(($user_id==null ||$user_id==0) && ($log_id==null ||$log_id==0)){   
   $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' ไม่พบ user_id หรือ ไม่พบ  log_id ส่งมา ',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>null),200);
                                   Die();   
     #################################################
}
/*
echo '<pre>exam_id=>';print_r($exam_id);echo '</pre>';  
echo '<pre>user_id=>';print_r($user_id);echo '</pre>'; 
echo '<pre>limit=>';print_r($limit);echo '</pre>';  
echo '<pre>orderby=>';print_r($orderby);echo '</pre>';  
echo '<pre>log_id=>';print_r($log_id);echo '</pre>';  
echo '<pre>deletekey=>';print_r($deletekey);echo '</pre>';Die();
*/
$examscoreuser_log=$this->Model_examination->where_cvs_course_exam_score_user_id($exam_id,$user_id,$limit,$orderby,$log_id,$deletekey);

################################# 
$examscoreuserlog=array();
if(is_array($examscoreuser_log)){
foreach($examscoreuser_log as $key=>$value){
$arrn=array();
if($key==0){
 $key2=$key;  
 $value2=$value;
 //$arrn['c']['value2']=$value2;
 $score2=(int)$value->score_value;
 $arrn['c']['score2']=$score2;
}else{
$key2=$key-1; 
$value2=$examscoreuser_log[$key2];
//$arrn['c']['value2']=$value2;
$score2=(int)$value2->score_value;
$arrn['c']['score2']=$score2;
}
     $id=(int)$value->id;
     //$arrn['c']['key']=$key;
     $arrn['c']['key2']=$key2;
     $arrn['c']['exam_id']=$value->exam_id;
     $arrn['c']['id']=$id;
     $arrn['c']['exam_name']=$value->exam_name;
     $arrn['c']['date_update']=$value->date_update;
     $arrn['c']['exam_type']=$value->exam_type;
     $arrn['c']['duration_sec']=$value->duration_sec;
     $arrn['c']['firstname']=$value->firstname;
     $arrn['c']['lastname']=$value->lastname;
     $arrn['c']['user_username']=$value->user_username;
     $arrn['c']['user_id']=$value->user_id;
     $score1=(int)$value->score_value;
     $arrn['c']['score1']=$score1;
     if($score1>$score2){
          $rating_after=(int)'1';
     }else if($score1<$score2){
          $rating_after=(int)'-1';
     }else if($score1=$score2){
          $rating_after=(int)'0';
     }
     $arrn['c']['rating_after']=$rating_after;
     $arrn['c']['score_value']=$value->score_value;
$examscoreuserlog[]=$arrn['c'];
}}
#######################################

if($examscoreuserlog!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
                                   //'log'=> $examscoreuser_log,
							'data'=> $examscoreuserlog),200);
}elseif($examscoreuserlog==''){
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $examscoreuserlog),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
#################################
#examinationbyid
####JWT TEST####
public function examinationbyid2_get(){
ob_end_clean();
####################################################
$exam_id=@$this->input->get('exam_id');
$deletekey=@$this->input->get('deletekey');
$dataall=$this->Model_examination->get_examid($exam_id,$deletekey);
$module_name='examination id';
if($dataall!==''){ 
##############################################
$content_id=$exam_id;
$table='cvs_course_examination';
$cachetime='300';
$cache_info=$this->Model_examination->ViewNumberGet($content_id,$table,$cachetime,$deletekey);
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=>$dataall,
                                   'Model'=>'Model_examination->ViewNumberGet',
                                   'info_cache'=>$cache_info
                                   ),200);
}elseif($dataall==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall
                                        ),204);
}else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
/*
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
*/
}
die();
}
public function jwtencode_get(){
ob_end_clean();

# webservice/api/examination/jwtencode?exam_id=13438&user_id=543622&deletekey=1

$exam_id=@$this->input->get('exam_id');
$user_id=@$this->input->get('user_id');
$deletekey=@$this->input->get('deletekey');
$log_id=@$this->input->get('log_id');
if($log_id==''){$log_id=null;}
$module_name='examination report by id and user_id jwt decode';
if($exam_id==null ||$exam_id==0) {
$message='ไม่พบข้อสอบ '.$exam_id.' กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>$message,
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}
if($user_id==null ||$user_id==''){$user_id=null;}else{
 $users_account=$this->Model_examination->users_account_id($user_id,$deletekey);
#echo '<pre> users_account=>'; print_r($users_account); echo '</pre>'; Die();     
}

$orderby=@$this->input->get('orderby');
$$limit=@$this->input->get('limit');
$examscoreuserlog=$this->Model_examination->where_cvs_course_exam_score_user_id($exam_id,$user_id,$limit,$orderby,$log_id,$deletekey);
# echo '<pre> examscoreuserlog=>'; print_r($examscoreuserlog); echo '</pre>'; Die(); 
if($examscoreuserlog==null) {
     if($log_id!==''){
         $message='ไม่พบ ประวัติการข้อสอบ examination id '.$exam_id.' user id '.$user_id.'  กรุณาตรวจสอบ';
     }else{
         $message='ไม่พบ ประวัติการข้อสอบ examination id '.$exam_id.' log_id'.$log_id.'  กรุณาตรวจสอบ';
     }

 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}

#echo '<pre> examscoreuserlog=>'; print_r($examscoreuserlog); echo '</pre>'; Die(); 
$count_log_user=count($examscoreuserlog);
if($count_log_user==0) {
$message='ไม่พบ examination user log ข้อสอบ examination id '.$exam_id.'  กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}
$examinationrs=$this->Model_examination->where_course_examination($exam_id,$deletekey);
$examinationrs=$examinationrs['list'];
$cachekey=$examinationrs['cachekey'];
//echo '<pre> examinationrs=>'; print_r($examinationrs); echo '</pre>';  #Die(); 
if($examinationrs=='') {
$message='ไม่พบข้อสอบ examination id '.$exam_id.' กรุณาตรวจสอบ';
 $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall,'info_cache'=>$cacheinfo),200);
exit();
}
$examination=$examinationrs['0'];
#echo '<pre> $examination=>'; print_r($examination); echo '</pre>';  
#echo '<pre> id=>'; print_r($examination->id); echo '</pre>'; Die();  
$question_id=(int)$examination->id;
#echo '<pre> $question_id=>'; print_r($question_id); echo '</pre>';  Die(); 
 
$questionrs=$this->Model_examination->wherequestion($question_id,$deletekey);
#echo '<pre> $questionrs=>'; print_r($questionrs); echo '</pre>';  Die();
$questionrslist=$questionrs['list']; 

##########
$order='desc';$limit='100';
$exam_scoress=$this->Model_examination->where_cvs_course_exam_score_member($exam_id,$user_id,$order,$limit,$log_id);
$examscoress=$exam_scoress['list']['0'];
$answer_value=$examscoress->answer_value;
$answervalue=unserialize($answer_value);
############################################################


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
     ////$log_id
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
###############################
$resultsdatascore=$this->Model_examination->score_value_exam_id($exam_id,$deletekey);
$question_totals=$this->Model_examination->score_count_cnt($exam_id,$deletekey);
$question_total1=$question_totals['0'];
$question_total=$question_total1->cnt;
#echo '<pre> $resultsdatascore=>'; print_r($resultsdatascore); echo '</pre>';  
#echo '<pre> $question_total=>'; print_r($question_total); echo '</pre>';  Die();
if($resultsdatascore) {
    foreach($resultsdatascore as $value) {
              $statScore[$value->score_value] = $value->total;
  }
}
#echo '<pre> $statScore=>'; print_r($statScore); echo '</pre>';  Die();
for($i = 0; $i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
}
$scoreArray2=($scoreArr);
$choicescorearr=array();
for($i=0;$i <= $question_total; $i++) {
          $total = (isset($statScore[$i]) ? (int) $statScore[$i] : 0);
          $scoreArr[$i] = $total;
          $arrs['z']['qno']=$i;
          $arrs['z']['total']=$total;
          $choicescorearr[]=$arrs['z'];
}
#echo '<pre> scoreArray2=>'; print_r($scoreArray2); echo '</pre>';  Die();
$score_value_exam_id=array(
       'scorearray'=>$scoreArray2,
       'score'=>$resultsdatascore
       );
############################################################
$user_score=$examscoress->score_value;
$percent_score=($user_score*100)/$question_total;
$question_time=$examscoress->duration_sec;
#######################
$exam_percent=$examination->exam_percent;
$exam_name=$examination->exam_name;
$level_name=$examination->level_name;
$category_name=$examination->category_name;
$percentscoreuser=(int)$percent_score;
if($percentscoreuser>=$exam_percent){
  $examscoremesage='สอบผ่าน';   
  $examscore_status=1;  
}
else{
  $examscoremesage='สอบไมผ่าน';  
  $examscore_status=0;  
}
#######################

$dataalls=array('examination'=>$examination,
               'profile_user'=>$users_account,
               'user_examination_log'=>$examscoreuserlog,
               'examination_percent'=>$exam_percent,
               'examination_user_mesage'=>$examscoremesage,
               'examination_user_status'=>$examscore_status,
               //'questionrs'=>$questionrslist,
               'user_id'=>$user_id,
               'log_id'=>$log_id,
               'question_data'=>$questionrs_doexam_score,
               'question_data_count'=>count($questionrslist),
               #'exam_score'=>$examscoress,
               'user_score'=>$user_score,
               'total_score'=>$question_total,
               'percent_score'=>(int)$percent_score,
               'percent'=>$percent_score.'%',
               'score'=>$user_score.' คะนนน',
               #'answervalue'=>$answervalue,
               'question_total'=>$question_total,
               'duration_time'=>$question_time,
               'scorebyexamid'=>$scoreArray2,
               'scorebyexamid2'=>$choicescorearr,
               'exam_score_count'=>count($exam_scoress),
               );
#####################################################
// Lets try to get the key
$cache_info=$this->memcached_library->getstats('items');
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
#####################################################
$module_name='list examination set id jwt decode';

###################******JWT&****##############
/*
// application\libraries\JWT
// application\libraries\BeforeValidException
// application\libraries\ExpiredException
// application\libraries\SignatureInvalidException
// application\helpers\jwt_helper
*/

$tokenData=$dataalls;
// encode JWT,Authorization
$this->load->helper('jwt');
$time=60;
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$issuedAt=time();
$expirationTime=$issuedAt+$time;  // jwt valid for $time seconds from the issued time
$payload=array('data' => $tokenData,
               'iat' => $issuedAt,
               'exp' => $expirationTime,
               'time' => $time);
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

if($dataalls!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'list'=> $tokenjwt,'info_cache'=>$cacheinfo),200);
}
elseif($dataalls==''){

     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> null,'info_cache'=>$cacheinfo),204);
}
else{
$this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_NOT_FOUND',
                                   'module'=>$message,
							'message'=>' DATA OK',
							'status'=>FALSE,
							'code'=>404), 
                                   'data'=> null),404);
}
die();
}
public function jwttest_post(){
####################################################
ob_end_clean();
$module_name='JWT TEST';
###################******JWT&encode****##############
// encode JWT 
$name='Mr Kongnakorn';
$lastname='Jantakun';
$token_data=array('name'=>$name,'lastname'=>$lastname);
$this->load->helper('jwt');
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$time_setting=120;
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
#echo' <pre> token=>';print_r($token);echo'<pre>'; #Die();  



###################******JWT&decode****##############
// decode  JWT 
//$this->load->helper('jwt');
//$key=$this->config->item('jwt_key');
//$algorithm='HS256';
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
     
}else{ 
     $msg_time='On time not Expired yet';
     $time_st=1;
}
/*
echo'<hr><pre> now=>';print_r($now);echo'<pre> <hr>';    
echo'<hr><pre> issued_at=>';print_r($issued_at);echo'<pre>';  
echo'<hr><pre> timecul=>';print_r($timecul);echo'<pre> ';     
echo'<hr><pre> time_setting=>';print_r($time_setting);echo'<pre>'; 
echo'<hr><pre> msg_time=>';print_r($msg_time);echo'<pre>'; 
echo'<hr><pre> time_st=>';print_r($time_st);echo'<pre>';     die();   
*/
if($time_st==1) {
     $dataalls=array('module'=>'jwt token',
                'token'=> $token,
                'jwtencode'=>$payload,
                'jwtdecode'=>$datadecode,
                'msg_time'=>$msg_time,
                'timecul'=>$timecul,
                'status_time'=>$time_st,
                'status'=>false,
                );
}
else{
     $dataalls=array('module'=>'jwt token',
                'token'=> $token,
                'jwtencode'=>$payload,
                'jwtdecode'=>$datadecode,
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
							//'token'=> $token,
                                   'data'=> $dataalls,
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
                                        //'info_cache'=>$cacheinfo,
                                        ),204);
}
die();
####JSON####  
}
####################################################
public function jwtgen_get(){
####################################################
ob_end_clean();
$module_name='JWT TEST';
###################******JWT&encode****##############
// encode JWT 
$name='Mr Kongnakorn';
$lastname='Jantakun';
$token_data=array('name'=>$name,'lastname'=>$lastname);
$this->load->helper('jwt');
$key=$this->config->item('jwt_key');
$algorithm='HS256';
$time_setting=60;
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
####JSON####              
if($token!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'token'=> $token,
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
}