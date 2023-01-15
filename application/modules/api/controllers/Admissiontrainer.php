<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . 'libraries/REST_Controller.php';
class Admissiontrainer extends REST_Controller {
public function __construct(){
  parent::__construct();  
  $this->CI = & get_instance();   
  ob_clean();
  date_default_timezone_set('asia/bangkok');
  $this->load->model('admissiontrainer_model', 'model_trainer');
  $this->load->model('mobile/admission_model', 'amm');
  $this->load->model('api/getRelate_model');
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json; charset=utf-8');
  $debug=@$this->input->get('debug');
  if (isset($debug) && $debug==1){
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  }
  
 
// Member //
$this->load->library('TPPY_Oauth');
$this->load->model('oauth_model');
$this->load->library('TPPY_Member');
// Member //

//users_favorite
$this->load->model('favorite/favorite_model');
//admissions
$this->load->model('admissions/admissions_models', 'am');
$this->load->model('mobile/admission_model', 'amm');


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
function encryptcode_get($q) {
    $cryptKey  = 'tyyptruemd5';
    $qEncoded = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded);
}
function decryptcode_get($q){
    $cryptKey  = 'tyyptruemd5';
    $qDecoded = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}
public function index_get() {  
 $data='admissiontrainer';
			$count=count($data);
			 if($count<=0 ||$count==Null){
				$this->response(array('header'=>array(
										'title'=>'admissiontrainer Modules',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> Null),404);
										Die();
			 }
			 if($data){
				$this->response(array('header'=>array(
										'title'=>'admissiontrainer Modules',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'admissiontrainer Modules',
										'message'=>'Unsuccess',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
			
			
	}
public function universityreport_get(){
 //http://www.trueplookpanya.com/api/admissiontrainer/universityreport?u_id=1&year=2560&delekey=delekey&config=1
$this->load->model('admissiontrainer_model', 'model_trainer');
$delekey=@$this->input->get('delekey');
$config=@$this->input->get('config');
if($config==''){
 $config=null;
}
if($delekey==''){$delekey=1;}
$year=$this->input->get('year');
if($year==''){$year=date('Y');}
$u_id=$this->input->get('u_id');
if($u_id==''||$u_id==0){$u_id='';}
$u_parent_id=@$this->input->get('u_parent_id'); 
if($u_parent_id==''){$u_parent_id=0;}
$uid=$u_id;
//echo'<hr><pre>year=>';print_r($year);echo'<pre> <hr>';   
//echo'<hr><pre>u_parent_id=>';print_r($u_parent_id);echo'<pre> <hr>'; Die();  

/*
if($u_id==null){
 #$university_list = '<select class="form-control" id="u_parent_id" name="u_parent_id"><option value="0" selected="selected">--- กรุณาเลือกมหาวิทยาลัย---</option></select>';
 $university_list = $this->model_trainer->getSelectuniversity($uid=0,$name="u_id",0);
}else{
$university_list = $this->model_trainer->getSelectuniversity($uid=0,$name="u_id",$u_id);
}

echo'<hr><pre> university_list=>';print_r($university_list);echo'<pre> <hr>'; Die();  
*/
/*
if($u_id==null){
$university_parent_list='<select class="form-control" id="u_parent_id" name="u_parent_id"><option value="0" selected="selected">--- กรุณาเลือกคณะ ---</option></select>';
}else{
$university_parent_list=$this->model_trainer->getSelectuniversity_parent($u_id,$name="u_id",$u_parent_id);
}
*/
#echo'<hr><pre> university_parent_list=>';print_r($university_parent_list);echo'<pre> <hr>'; //Die();  
/*
if($year==null){
 $year=date('Y');
}
$year_list = $this->model_trainer->getSelectyear($year_id='',$name="year",$year);
*/
//echo'<hr><pre> $year_list=>';print_r($year_list);echo'<pre> <hr>';
#echo'<hr><pre> university_list=>';print_r($university_list);echo'<pre> <hr>';
if($u_id==null){
$query_sql = "SELECT * FROM ams_university WHERE u_parent_id=0 and record_status=1 ";
}else{
$query_sql= "SELECT * FROM ams_university WHERE u_id=$u_id and record_status=1 ";
}  

$cache_key = "get_ams_university_search_'.$query_sql.";
if($delekey=='delkey'){ $this->tppymemcached->delete($cache_key); }
$datars=$this->tppymemcached->get($cache_key);
if(!$datars){
$datars=$this->db->query($query_sql)->result_array();
$this->tppymemcached->set($cache_key,$data,3600);
}
$data1=$datars;
#echo '$datars=>';echo'<pre>';print_r($datars);echo'<pre>';  Die();   
$data = array();
   foreach ($datars as $key => $v) {
   	    $u_parent_id=$v['u_parent_id'];
   	    $data[$key]['u_id'] =$v['u_id'];;
   $data[$key]['u_parent_id'] = $u_parent_id;
   $data[$key]['u_name'] = $v['u_name'];
   $data[$key]['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/'.$v['thumbnail'];
   $data[$key]['short_description'] = $v['short_description'];
   $data[$key]['detail'] = $v['detail'];
   $data[$key]['u_group_id'] = $v['u_group_id'];
   $data[$key]['record_status'] = $v['record_status'];
   $data[$key]['add_timestamp'] = $v['add_timestamp'];
   $data[$key]['update_timestamp'] = $v['update_timestamp'];
    $table='ams_university';
$id=$v['u_id'];
/*
$id1=$v['u_id'];
$id2=$u_id;
if($u_id==''){$id=$id1;}else{$id=$id2;}
 */
$datawhere='u_parent_id='.$id;
   
    /*
    echo'<pre> $id1=>';print_r($id1);echo'<pre>';
    echo'<pre> $id2=>';print_r($id2);echo'<pre>';
    echo'<hr><pre> $table=>';print_r($table);echo'<pre> <hr>';
    echo'<hr><pre> datawhere=>';print_r($datawhere);echo'<pre> <hr>';Die();   
    */
    $university1=$this->model_trainer->datawherearrays($table,$datawhere);
    $num_rows1=$university1['num_rows'];
    $data[$key]['count'] = $num_rows1;
    $data[$key]['university_name'] =$v['u_name'];
    if($university1==null){
$faculty=0;
    }
    $data[$key]['faculty_count'] = $num_rows1;
    $faculty=$university1['data'];
    #$data[$key]['faculty1']=$faculty;
		   $data2 = array();
foreach ($faculty as $key2 => $vv) {
  #################
  $data2[$key2]['u_id'] = $vv['u_id'];
  $data2[$key2]['u_parent_id'] = $vv['u_parent_id'];
  $data2[$key2]['faculty_name'] = $vv['u_name'];
  
  $thumbnail=$vv['thumbnail'];
  if($thumbnail==Null){
   $thumbnail=$v['thumbnail'];
  }
  $data2[$key2]['thumbnail'] = 'http://static.trueplookpanya.com/trueplookpanya/'.$thumbnail;
  $data2[$key2]['short_description'] = $vv['short_description'];
  $data2[$key2]['detail'] = $vv['detail'];
 #################
$u_parent_id=$vv['u_id'];
$u_list=$this->model_trainer->get_university_u_id_and_parent_id($u_parent_id);
 #################
 $data3 = array();
 foreach ($u_list as $key3 => $vv3) {
   $data3[$key3]['branch_id']=$vv3['u_id'];
   $data3[$key3]['branch_parent_id']=$vv3['u_parent_id'];
   $thumbnail=$vv3['thumbnail'];
   if($thumbnail==Null){
    $thumbnail= 'http://static.trueplookpanya.com/trueplookpanya/'.$v['thumbnail'];
   }
   $data3[$key3]['branch_thumbnail']=$thumbnail;
   $data3[$key3]['branch_faculty_name']=$vv['u_name'];;
   $data3[$key3]['branch_name']=$vv3['u_name'];
   $id=$vv3['u_id'];
   $ams_entrance=$this->model_trainer->get_ams_entrance($id,$year,$config,$delekey);  
   $ams_entrance_count=count($ams_entrance);
   $data3[$key3]['ams_entrance_count']=$ams_entrance_count;
    #################
    $ams_entrance=$this->model_trainer->get_ams_entrance($id,$year,$config,$delekey); 
    $data4 = array();
foreach ($ams_entrance as $key4 => $vv4) {
 $ent_id=$vv4['ent_id'];
 $data4[$key4]['ent_id']=$ent_id;
 $data4[$key4]['u_id']=$vv4['u_id'];
 $data4[$key4]['year_config']=$vv4['year_config'];
 $data4[$key4]['major_code']=$vv4['major_code'];
 $data4[$key4]['major_remark']=$vv4['major_remark'];
 $data4[$key4]['gpax_weight']=$vv4['gpax_weight'];
 $data4[$key4]['gpax_min']=$vv4['gpax_min'];
 $data4[$key4]['onet_weight_total']=$vv4['onet_weight_total'];
 $data4[$key4]['onet_min_total']=$vv4['onet_min_total'];
 $data4[$key4]['01_onet_weight']=$vv4['01_onet_weight'];
 $data4[$key4]['01_onet_mi']=$vv4['01_onet_mi'];
 $data4[$key4]['02_onet_weight']=$vv4['02_onet_weight'];
 $data4[$key4]['02_onet_min']=$vv4['02_onet_min'];
 $data4[$key4]['03_onet_weight']=$vv4['03_onet_weight'];
 $data4[$key4]['03_onet_mint']=$vv4['03_onet_mint'];
 $data4[$key4]['04_onet_weight']=$vv4['04_onet_weight'];
 $data4[$key4]['04_onet_min']=$vv4['04_onet_min'];
 $data4[$key4]['05_onet_weight']=$vv4['05_onet_weight'];
 $data4[$key4]['05_onet_min']=$vv4['05_onet_min'];
 $data4[$key4]['85_gat_weight']=$vv4['85_gat_weight'];
 $data4[$key4]['85_gat_min']=$vv4['85_gat_min'];
 $data4[$key4]['85_gat_min_part2']=$vv4['85_gat_min_part2'];
 $data4[$key4]['71_pat1_weight']=$vv4['71_pat1_weight'];
 $data4[$key4]['71_pat1_min']=$vv4['71_pat1_min'];
 $data4[$key4]['72_pat2_weight']=$vv4['72_pat2_weight'];
 $data4[$key4]['72_pat2_min']=$vv4['72_pat2_min'];
 $data4[$key4]['73_pat3_weight']=$vv4['73_pat3_weight'];
 $data4[$key4]['73_pat3_min']=$vv4['73_pat3_min'];
 $data4[$key4]['74_pat4_weight']=$vv4['74_pat4_weight'];
 $data4[$key4]['74_pat4_min']=$vv4['74_pat4_min'];
 $data4[$key4]['75_pat5_weight']=$vv4['75_pat5_weight'];
 $data4[$key4]['75_pat5_min']=$vv4['75_pat5_min'];
 $data4[$key4]['76_pat6_weight']=$vv4['76_pat6_weight'];
 $data4[$key4]['76_pat6_min']=$vv4['76_pat6_min'];
 $data4[$key4]['77_pat71_weight']=$vv4['77_pat71_weight'];
 $data4[$key4]['77_pat71_min']=$vv4['77_pat71_min'];
 $data4[$key4]['78_pat72_weight']=$vv4['78_pat72_weight'];
 $data4[$key4]['78_pat72_min']=$vv4['78_pat72_min'];
 $data4[$key4]['79_pat73_weight']=$vv4['79_pat73_weight'];
 $data4[$key4]['79_pat73_min']=$vv4['79_pat73_min'];
 $data4[$key4]['80_pat74_weight']=$vv4['80_pat74_weight'];
 $data4[$key4]['80_pat74_min']=$vv4['80_pat74_min'];
 $data4[$key4]['81_pat75_weight']=$vv4['81_pat75_weight'];
 $data4[$key4]['81_pat75_min']=$vv4['81_pat75_min'];
 $data4[$key4]['82_pat76_weight']=$vv4['82_pat76_weight'];
 $data4[$key4]['82_pat76_min']=$vv4['82_pat76_min'];
 $data4[$key4]['special_remark']=$vv4['special_remark'];
 $data4[$key4]['receive_amount']=$vv4['receive_amount'];
 $data4[$key4]['receive_amount_sharecode']=$vv4['receive_amount_sharecode'];
 $data4[$key4]['lastupdate']=$vv4['lastupdate'];
 $data4[$key4]['config']=$vv4['config'];
$ams_entrance_score_history=$this->model_trainer->get_ams_entrance_score_history_ent_id($ent_id,$delekey);
$score_historye_count=count($ams_entrance_score_history);
 $data4[$key4]['score_history_count']=$score_historye_count;
 $data4[$key4]['score_history']=$ams_entrance_score_history;
 #################
    }
    $data3[$key3]['ams_entrance']=$data4;
   //$data3[$key3]['ams_entrance']=$ams_entrance;
}
$data2[$key2]['branch_count']=count($data3);
$data2[$key2]['branch'] =$data3;
#################   
}
$data[$key]['faculty_count']=count($data2);
$data[$key]['faculty']=$data2;

   
}

 #echo'<hr><pre>  data=>';print_r($data);echo'<pre> <hr>';Die();  
 
 		
			$count=count($data);
			 if($count<=0 ||$count==Null){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> Null),404);
										Die();
			 }
			 
			 if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function universitylistgroup4university_get(){
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=$this->input->get('u_group_id');
if($u_group_id==''){
				  $this->response(array('header'=>array(
										'title'=>'University list group4',
										'message'=>'u_group_id is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
				}
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_group3_lists=$this->model_trainer->get_ams_university_group_m2m_typy3($u_group_id,$delekey);
$university_group3_list=$university_group3_lists['data'];
$university_group3_sql=$university_group3_lists['sql'];
$university_group3_list_count=count($university_group3_list);

if($university_group3_list_count<=0){
				  $this->response(array('header'=>array(
										'title'=>'University list group4',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
				}


#echo'<hr><pre> $university_group3_list=>';print_r($university_group3_list);echo'<pre> <hr>'; 
$datars=array();
 foreach($university_group3_list as $key => $value) {
 //$idx=$value['idx'];
 //$datars[$key]['idx']=$idx;
 //$datars[$key]['u_group_id_source']=$value['u_group_id_source'];
 $datars[$key]['u_group_id_destination']=$value['u_group_id_destination'];
 #################
}

//$array_m1pre=explode(',', $datars);
$num=0;
foreach($datars as $k=>$v){
 $id=$v['u_group_id_destination'];
 $num++;
}
$result_set=implode(',', array_map(function($v){ return $v['u_group_id_destination'];}, $datars ));
////////////////
$sql="select 
(select (select w.u_id from  ams_university as w where w.u_id=w1.u_parent_id)as university_name from  ams_university as w1 where w1.u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as university_id
,(select (select w.u_name from  ams_university as w where w.u_id=w1.u_parent_id)as university_name from  ams_university as w1 where w1.u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as university_name
from ams_university_group_map as map 
where map.u_group_id in ($result_set) 
group by university_id 
order by idx asc";
#echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>';  die();
$cache_key="get_ams_university_group_map_list_ams_university_group_in_".$sql;
if($delekey=='delkey'){ $this->tppymemcached->delete($cache_key); }
$rs=$this->tppymemcached->get($cache_key);
if(!$data==$rs){$group_in_data=$rs;}else{   
$group_in_data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key,$data,3600);
}
////////////////
$group_in_data_count=count($group_in_data);
$data=array(
										'count'=>$group_in_data_count,
'list_set'=>$result_set,
										'list'=>$group_in_data,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function universitylistall_get(){
$this->load->model('admissiontrainer_model', 'model_trainer');
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_list=$this->model_trainer->get_ams_university_list_all($delekey);
$count=count($university_list);
if($count<=0){
				  $this->response(array('header'=>array(
										'title'=>'University list',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
				} 
$datars=array();
 foreach($university_list as $keydata => $value) {
 $u_id=$value['u_id'];
 $thumbnail=$value['thumbnail_url'];
 $data_set['d'][$keydata]['u_id']=$u_id;
 $data_set['d'][$keydata]['u_name']=$value['u_name'];
 $data_set['d'][$keydata]['thumbnail']=$thumbnail;
 $data_set['d'][$keydata]['u_group_id']=$value['u_group_id'];
 $data_set['d'][$keydata]['group_name']=$value['group_name'];
 
 $faculty=$this->model_trainer->get_ams_university_by_u_parent_id($u_id,$delekey);
   $arr_faculty = array();
   if (is_array($faculty)) {
  foreach ($faculty as $key => $v) {
 $arr = array();
 $faculty_id=$v['u_id'];
 $arr['d'][$key]['faculty_id'] = $faculty_id;
 $arr['d'][$key]['u_group_id'] = $v['u_group_id'];
 $arr['d'][$key]['faculty_name'] = $v['u_name'];
 $arr['d'][$key]['thumbnail'] = $v['thumbnail_url'];
 
 #########################
 $branch=$this->model_trainer->get_ams_university_by_u_parent_id($faculty_id,$delekey);
 $arr_branch= array();
 if (is_array($branch)) {
foreach ($branch as $keys => $vs) {
    $arrs = array();
    $arrs['e'][$keys]['branch_id'] = $vs['u_id'];
    $arrs['e'][$keys]['u_group_id'] = $vs['u_group_id'];
    $arrs['e'][$keys]['branch_name'] = $vs['u_name'];
    $arrs['e'][$keys]['thumbnail'] = $vs['thumbnail_url'];
    $arr_branch[] = $arrs['e'][$keys];
}
   $branch_count=count($arr_branch);
   $branch=$arr_branch;
  }else{
   $branch_coun==0;
   $branch=null;
  }
  #########################
 $arr['d'][$key]['branch_count'] = $branch_count;
 $arr['d'][$key]['branch'] = $branch;
 $arr_faculty[] = $arr['d'][$key];
  }
   $data_set['d'][$keydata]['faculty_count']=count($arr_faculty);
   $data_set['d'][$keydata]['faculty']=$arr_faculty;
   }else{
   $data_set['d'][$keydata]['faculty_count']=0;
   $data_set['d'][$keydata]['faculty']=null;
   }

    
    
 $dataset=$data_set['d'];
 #################
}
$university_list_count=count($dataset);
$data=array(
										'count'=>$university_list_count,
										'list'=>$dataset,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'University list',
										'message'=>'Success',
										'status'=>true,
										'count'=>$university_list_count,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
#############
}
public function universitylistgroup3_get(){
	
$user_id = $this->tppy_member->get_member_profile()->user_id;
	
$this->load->model('admissiontrainer_model', 'model_trainer');
$type=$this->input->get('type');
$u_group_id=@$this->input->get('u_group_id');
if($u_group_id==''){
$active_id=@$this->input->get('active_id');	
}else{
$active_id=$u_group_id;	
}


$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_group3_list=$this->model_trainer->get_ams_university_group3($delekey);
$university_group3_list_count=count($university_group3_list);
if($university_group3_list_count<=0){
				  $this->response(array('header'=>array(
										'title'=>'University list group3',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
				} 
#echo'<hr><pre> $university_group3_list=>';print_r($university_group3_list);echo'<pre> <hr>'; 
$datars=array();
 foreach($university_group3_list as $key => $value) {
  
 $u_group_id=$value['u_group_id'];
 $thumbnail=$value['thumbnail'];
 
 if($active_id==$u_group_id){
   $datars[$key]['active']=1;
 }else{
   $datars[$key]['active']=0;
 }
 $datars[$key]['u_group_id']=$u_group_id;
 
 
 $datars[$key]['u_group_name']=$value['u_group_name'];
 $datars[$key]['short_description']=$value['short_description'];
 $datars[$key]['thumbnail']='http://static.trueplookpanya.com'.$thumbnail;
 $datars[$key]['u_group_type']=$value['u_group_type'];
 #################
}
$data=array(
										'count'=>$university_group3_list_count,
										'list'=>$datars,
										'user_id'=>$user_id,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function universitylistgroup4_get(){
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');
$group_id=@$this->input->get('group_id');
$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
if($u_group_id==''){
$ams_university_u_parento=$this->model_trainer->get_ams_university_u_parent_ido($delekey);
$num=0;
foreach($ams_university_u_parento as $k=>$v){
$id=$v['u_id'];
$num++;
}
$result_set_u=implode(',', array_map(function($v){ return $v['u_id'];}, $ams_university_u_parento ));

$sqlr1="select  u_id,u_name from ams_university  where  u_parent_id in($result_set_u) order by u_id asc";
$cache_key="get_ams_university_u_parent_wherein_".$sqlr1;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
##########################
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
#########################
$datain=$this->tppymemcached->get($cachekeyencoded);
if(!$datain){
$datain=$this->db->query($sqlr1)->result_array();
$this->tppymemcached->set($cachekeyencoded,$datain,3600);
}
$num2=0;
foreach($datain as $k=>$v){
$id=$v['u_id'];
$num2++;
}
$result_set_uin=implode(',', array_map(function($v){ return $v['u_id'];}, $datain ));
#echo'<hr><pre> $result_set_uin=>';print_r($result_set_uin);echo'<pre> <hr>'; die();
$sqlr2="select  u_id,u_name from ams_university  where  u_parent_id in($result_set_uin) order by u_id asc";
$cache_key="get_ams_university_u_parent_wherein2_".$sqlr2;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded2= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
##########################
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded2); }
#########################
$rs=$this->tppymemcached->get($cachekeyencoded2);
if(!$datain2==$rs){$datain2=$rs;}else{   
$datain2=$this->db->query($sqlr2)->result_array();
$this->tppymemcached->set($cachekeyencoded2,$datain2,3600);
}
$num3=0;
foreach($datain2 as $k=>$v){
$id2=$v2['u_id'];
$num3++;
}
$result_set_uin2=implode(',', array_map(function($v2){ return $v2['u_id'];}, $datain2 ));

if(($groupby=='university'&& $u_group_id=='')||($groupby==''&& $u_group_id=='')){
$group_by='group by university_id';
$group_name='university';
$group_short_name='university';
}else if($groupby=='branch'&& $u_group_id==''){
$group_by='group by branch_id';
$group_name='branch';
$group_short_name='branch';
}else{
 $group_by='group by university_id,branch_id';
 $group_name='university & branch';
$group_short_name='university & branch';
}
 
$sql5="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id 
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set_uin2)  
$group_by order by university_id asc,m.idx asc";
$cache_key="get_ams_university_group_map_list_group_in_group0_".$sql;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
//$this->tppymemcached->delete($cachekeyencoded);
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
$group_in_data5=$this->tppymemcached->get($cachekeyencoded);
if(!$group_in_data5){
$group_in_data5=$this->db->query($sql5)->result_array();
$this->tppymemcached->set($cachekeyencoded,$group_in_data5,3600);
}
$arr_result5 = array();
if (is_array($group_in_data5)) {
  foreach ($group_in_data5 as $key5 => $val5) {
   #################
$university_id=$val5['university_id'];
if($university_id==''){}else{
################
$branch_id=$val5['branch_id'];
$data5 = array();
$data5['d']['university_parent_id']=$val5['university_parent_id'];
$data5['d']['university_id']=$val5['university_id'];
$data5['d']['branch_id']=$branch_id;
$data5['d']['faculty_id']=$val5['faculty_id'];
$data5['d']['map_id']=$val5['map_id'];
$data5['d']['group_type']=$val5['u_group_type'];
$groupid=$val5['group_id'];
$data5['d']['group_id']=$groupid;
$data5['d']['university_name']=$val5['university_name'];
$thumbnail=$val5['thumbnail'];
$data5['d']['thumbnail']='http://static.trueplookpanya.com/trueplookpanya/'.$thumbnail;
$data5['d']['branch_name']=$val5['branch_name'];
$data5['d']['faculty_name']=$val5['faculty_name'];
$data5['d']['group_name']=$val5['group_name'];
$data5['d']['group_description']=$val5['group_description'];
if($groupid==$group_id){
    $data5['d']['active']=1;
 }else{
    $data5['d']['active']=0;
 }
 $arr_result5[] = $data5['d'];
################ 
}

   }#################
 }else{ $arr_result5=null; }
$list5=$arr_result5;
$list_count=count($list5);
$data=array('list'=>$list5,
'count'=>$list_count,
'group_name'=>$group_name,
'group_short_name'=>$group_short_name,
'sql'=>$sql5,
);
#################################  
$this->response(array('header'=>array('title'=>'University list group4',
										'message'=>'Data is University group4',
										'status'=>true, 
										'code'=>200), 
'data'=>$data,)
,200);
Die();
}
$university_group3_lists=$this->model_trainer->get_ams_university_group_m2m_typy3($u_group_id,$delekey);
//echo'<hr><pre> university_group3_list=>';print_r($university_group3_list);echo'<pre><hr>';  die();
$university_group3_list=$university_group3_lists['data'];
$sql1=$university_group3_lists['sql'];
$university_group3_list_count=count($university_group3_list);
if($university_group3_list_count<=0){
				  $this->response(array('header'=>array(
										'title'=>'University list group4',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
}
#echo'<hr><pre> $university_group3_list=>';print_r($university_group3_list);echo'<pre> <hr>'; 
$datars=array();
 foreach($university_group3_list as $key => $value) {
 //$idx=$value['idx'];
 //$datars[$key]['idx']=$idx;
 //$datars[$key]['u_group_id_source']=$value['u_group_id_source'];
 $datars[$key]['u_group_id_destination']=$value['u_group_id_destination'];
 #################
}

//$array_m1pre=explode(',', $datars);
$num=0;
foreach($datars as $k=>$v){
 $id=$v['u_group_id_destination'];
 $num++;
}
$result_set=implode(',', array_map(function($v){ return $v['u_group_id_destination'];}, $datars ));
$groupby=@$this->input->get('group_by');
if($groupby=='university'&& $u_group_id!=''){
 $sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set)  
group by group_id,university_id  order by university_id asc,m.idx asc";

}elseif($groupby=='branch'&& $u_group_id!=''){
 $sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set)  
group by group_id  order by m.idx asc";
}else if($groupby==''){
 $sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set)  
group by group_id order by amsu.u_id asc,m.idx asc";
}
#echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>';  die();
$cache_key="get_ams_university_group_map_list_group_in_".$sql;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
$group_in_data=$this->tppymemcached->get($cachekeyencoded);
if(!$group_in_data){
$group_in_data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cachekeyencoded,$data,600);
}
###############################
$arr_result4 = array();
if (is_array($group_in_data)) {
  foreach ($group_in_data as $key4 => $val4) {
   #################
$university_id=$val4['university_id'];
if($university_id==''){}else{
################
$branch_id=$val4['branch_id'];
$data4 = array();
//$data4['d']['university_parent_id']=$val4['university_parent_id'];
//$data4['d']['university_id']=$val4['university_id'];
//$data4['d']['branch_id']=$branch_id;
//$data4['d']['faculty_id']=$val4['faculty_id'];
//$data4['d']['map_id']=$val4['map_id'];
$data4['d']['group_type']=$val4['u_group_type'];
$groupid=$val4['group_id'];
$data4['d']['group_id']=$groupid;
//$data4['d']['university_name']=$val4['university_name'];
//$thumbnail=$val4['thumbnail'];
//$data4['d']['thumbnail']='http://static.trueplookpanya.com/trueplookpanya/'.$thumbnail;
$data4['d']['branch_name']=$val4['branch_name'];
$data4['d']['faculty_name']=$val4['faculty_name'];
$data4['d']['group_name']=$val4['group_name'];
$data4['d']['group_description']=$val4['group_description'];
if($groupid==$group_id){
    $data4['d']['active']=1;
 }else{
    $data4['d']['active']=0;
 }
 $arr_result4[] = $data4['d'];
################ 
}

   }#################
 }else{ $arr_result4=null; }
$list=$arr_result4;
$group_in_data_count=count($list);
##############################
$group_type3name=$this->model_trainer->get_group_type3_id_list($u_group_id,$delekey);
$group_type3_name=$group_type3name['0'];
$data=array('count'=>$group_in_data_count,
#'list_set'=>$result_set,
'group_name'=>$group_type3_name['u_group_name'],
'group_short_name'=>$group_type3_name['short_description'],
'list'=>$list,
#'sql2'=>$sql,
#'sql1'=>$sql1,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'universitylistgroup4',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'universitylistgroup4',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function universitylistgroup4listgroupid_get(){
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');
if($u_group_id<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_group=$this->model_trainer->get_ams_university_group_id($u_group_id,$delekey);
//echo'<pre> university_group=>';print_r($university_group);echo'<pre>';  die();
$university_group4=$university_group;
$sql1=$university_group['sql'];
$university_group4_count=count($university_group4);
if($university_group4_count<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>201), 
									  'data'=> Null),201);
Die();
}
#echo'<hr><pre> $university_group4=>';print_r($university_group4);echo'<pre> <hr>'; 
$datalist=array();
 foreach($university_group4 as $key => $value) {
  $u_group_id=$value['u_group_id'];
  $datars[$key]['group_id']=$u_group_id;
  $datars[$key]['u_group_name']=$value['u_group_name'];
  $datars[$key]['short_description']=$value['short_description'];
  $datars[$key]['thumbnail']=$value['thumbnail'];
  $datars[$key]['detail']=$value['detail'];
  $datars[$key]['u_group_type']=$value['u_group_type'];
  $datars[$key]['record_status']=$value['record_status'];
  $datars[$key]['add_timestamp']=$value['add_timestamp'];
  $datars[$key]['update_timestamp']=$value['update_timestamp'];
 #################
 $datalist=$datars[$key];
}
#echo'<pre>datalist=>';print_r($datalist);echo'<pre>';  //die();
$university_group4=$university_group4['0'];
#echo'<pre>$university_group4=>';print_r($university_group4);echo'<pre>';  //die();
$u_group_id=$university_group4['u_group_id'];
$u_group_name=$university_group4['u_group_name'];
$short_description=$university_group4['short_description'];
#echo'<pre>u_group_id=>';print_r($u_group_id);echo'<pre>';  
$group_in_data=$this->model_trainer->get_group_type4_u_group_id_list($u_group_id,$delekey);
//echo'<pre> group_in_data=>';print_r($group_in_data);echo'<pre>';  die();
###############################
$arr_result4 = array();
if (is_array($group_in_data)) {
  foreach ($group_in_data as $key4 => $val4) {
   #################
$university_id=$val4['university_id'];
if($university_id==''){}else{
################
$branchid=$val4['branch_id'];
$data4 = array();
###########################################################################

###########################################################################
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year; }
#################
$ams=$this->model_trainer->get_ams_university_ams_entrance_group_by_u_id_year_branch_id($year,$university_id,$branchid,$delekey);
#echo '<hr><pre>  $ams=>'; print_r($ams); echo '</pre>';die();
if(!$ams){
	$group_ams_data_count=0;
}
#################
$datasetamss = array();
if (is_array($ams)) {
  foreach ($ams as $keydatas => $val) {
 $ent_id=$val['ent_id'];
 $dataset['dd'][$keydatas]['year_config']=$val['year_config'];
 $dataset['dd'][$keydatas]['major_remark']=$val['major_remark'];
 $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
 $dataset['dd'][$keydatas]['score_history']=$score_history;
 $dataset['dd'][$keydatas]['ent_id']=$val['ent_id'];
 #################
 $dataset['dd'][$keydatas]['01_onet_mi']=$val['01_onet_mi'];
 $dataset['dd'][$keydatas]['01_onet_weight']=$val['01_onet_weight'];
 $dataset['dd'][$keydatas]['02_onet_min']=$val['02_onet_min'];
 $dataset['dd'][$keydatas]['02_onet_weight']=$val['02_onet_weight'];
 $dataset['dd'][$keydatas]['03_onet_mint']=$val['03_onet_mint'];
 $dataset['dd'][$keydatas]['03_onet_weight']=$val['03_onet_weight'];
 $dataset['dd'][$keydatas]['04_onet_min']=$val['04_onet_min'];
 $dataset['dd'][$keydatas]['04_onet_weight']=$val['04_onet_weight'];
 $dataset['dd'][$keydatas]['05_onet_min']=$val['05_onet_min'];
 $dataset['dd'][$keydatas]['05_onet_weight']=$val['05_onet_weight'];
 $dataset['dd'][$keydatas]['71_pat1_min']=$val['71_pat1_min'];
 $dataset['dd'][$keydatas]['71_pat1_weight']=$val['71_pat1_weight'];
 $dataset['dd'][$keydatas]['72_pat2_min']=$val['72_pat2_min'];
 $dataset['dd'][$keydatas]['72_pat2_weight']=$val['72_pat2_weight'];
 $dataset['dd'][$keydatas]['73_pat3_min']=$val['73_pat3_min'];
 $dataset['dd'][$keydatas]['73_pat3_weight']=$val['73_pat3_weight'];
 $dataset['dd'][$keydatas]['74_pat4_min']=$val['74_pat4_min'];
 $dataset['dd'][$keydatas]['74_pat4_weight']=$val['74_pat4_weight'];
 $dataset['dd'][$keydatas]['75_pat5_min']=$val['75_pat5_min'];
 $dataset['dd'][$keydatas]['75_pat5_weight']=$val['75_pat5_weight'];
 $dataset['dd'][$keydatas]['76_pat6_min']=$val['76_pat6_min'];
 $dataset['dd'][$keydatas]['76_pat6_weight']=$val['76_pat6_weight'];
 $dataset['dd'][$keydatas]['77_pat71_min']=$val['77_pat71_min'];
 $dataset['dd'][$keydatas]['77_pat71_weight']=$val['77_pat71_weight'];
 $dataset['dd'][$keydatas]['78_pat72_min']=$val['78_pat72_min'];
 $dataset['dd'][$keydatas]['78_pat72_weight']=$val['78_pat72_weight'];
 $dataset['dd'][$keydatas]['79_pat73_min']=$val['79_pat73_min'];
 $dataset['dd'][$keydatas]['79_pat73_weight']=$val['79_pat73_weight'];
 $dataset['dd'][$keydatas]['80_pat74_min']=$val['80_pat74_min'];
 $dataset['dd'][$keydatas]['80_pat74_weight']=$val['80_pat74_weight'];
 $dataset['dd'][$keydatas]['81_pat75_min']=$val['81_pat75_min'];
 $dataset['dd'][$keydatas]['81_pat75_weight']=$val['81_pat75_weight'];
 $dataset['dd'][$keydatas]['82_pat76_min']=$val['82_pat76_min'];
 $dataset['dd'][$keydatas]['82_pat76_weight']=$val['82_pat76_weight'];
 $dataset['dd'][$keydatas]['85_gat_min']=$val['85_gat_min'];
 $dataset['dd'][$keydatas]['85_gat_min_part2']=$val['85_gat_min_part2'];
 $dataset['dd'][$keydatas]['85_gat_weight']=$val['85_gat_weight'];
 $dataset['dd'][$keydatas]['branch_id']=$val['branch_id'];
 $dataset['dd'][$keydatas]['branch_name']=$val['branch_name'];
 $dataset['dd'][$keydatas]['config']=$val['config'];
 $dataset['dd'][$keydatas]['faculty_id']=$val['faculty_id'];
 $dataset['dd'][$keydatas]['faculty_name']=$val['faculty_name'];
 $dataset['dd'][$keydatas]['gpax_min']=$val['gpax_min'];
 $dataset['dd'][$keydatas]['gpax_weight']=$val['gpax_weight'];
 $dataset['dd'][$keydatas]['major_code']=$val['major_code'];
 $dataset['dd'][$keydatas]['major_remark']=$val['major_remark'];
 $dataset['dd'][$keydatas]['onet_min_total']=$val['onet_min_total'];
 $dataset['dd'][$keydatas]['onet_weight_total']=$val['onet_weight_total'];
 $dataset['dd'][$keydatas]['receive_amount']=$val['receive_amount'];
 $dataset['dd'][$keydatas]['receive_amount_sharecode']=$val['receive_amount_sharecode'];
 $dataset['dd'][$keydatas]['score_max']=$val['score_max'];
 $dataset['dd'][$keydatas]['score_min']=$val['score_min'];
 $dataset['dd'][$keydatas]['special_remark']=$val['special_remark'];
 $dataset['dd'][$keydatas]['lastupdate']=$val['lastupdate'];
 $datasetamss[]=$dataset['dd'][$keydatas];
  }
}else{ $datasetamss=null; }
$group_ams_data_count=count($datasetamss);
###########################################################################
###########################################################################
if($datasetamss==null){}else{
$data4['d']['entrance_list']=$datasetamss;
$data4['d']['entrance_list_count']=$group_ams_data_count;
$data4['d']['university_parent_id']=$val4['university_parent_id'];
$data4['d']['university_id']=$val4['university_id'];
$data4['d']['branch_id']=$val4['branch_id'];
$data4['d']['faculty_id']=$val4['faculty_id'];
$data4['d']['map_id']=$val4['map_id'];
$data4['d']['group_type']=$val4['u_group_type'];
$data4['d']['group_id']=$val4['group_id'];
$data4['d']['university_name']=$val4['university_name'];
$thumbnail=$val4['thumbnail'];
$data4['d']['thumbnail']=$thumbnail;
$data4['d']['branch_name']=$val4['branch_name'];
$data4['d']['faculty_name']=$val4['faculty_name'];
$data4['d']['group_name']=$val4['group_name'];
$data4['d']['group_description']=$val4['group_description'];
################ 
$arr_result4[] = $data4['d'];
}
###########################################################################
 
################ 
}

   }#################
 }else{ $arr_result4=null; }
$list=$arr_result4;
$group_in_data_count=count($list);
##############################

$group_type3name=$this->model_trainer->get_group_type3_id_list($u_group_id,$delekey);
$group_type3_name=$group_type3name['0'];
$data=array('count'=>$group_in_data_count,
			'u_group_id'=>$u_group_id,
			'group_name'=>$u_group_name,
	   'list'=>$list);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
##############
public function universitylistgroup4count_get(){
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');
$group_id=@$this->input->get('group_id');
$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
if($u_group_id==''){
$ams_university_u_parento=$this->model_trainer->get_ams_university_u_parent_ido($delekey);
$num=0;
foreach($ams_university_u_parento as $k=>$v){
$id=$v['u_id'];
$num++;
}
$result_set_u=implode(',', array_map(function($v){ return $v['u_id'];}, $ams_university_u_parento ));

$sqlr1="select  u_id,u_name from ams_university  where  u_parent_id in($result_set_u) order by u_id asc";
$cache_key="get_ams_university_u_parent_wherein_".$sqlr1;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
##########################
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
#########################
$datain=$this->tppymemcached->get($cachekeyencoded);
if(!$datain){
$datain=$this->db->query($sqlr1)->result_array();
$this->tppymemcached->set($cachekeyencoded,$datain,3600);
}
$num2=0;
foreach($datain as $k=>$v){
$id=$v['u_id'];
$num2++;
}
$result_set_uin=implode(',', array_map(function($v){ return $v['u_id'];}, $datain ));
#echo'<hr><pre> $result_set_uin=>';print_r($result_set_uin);echo'<pre> <hr>'; die();
$sqlr2="select  u_id,u_name from ams_university  where  u_parent_id in($result_set_uin) order by u_id asc";
$cache_key="get_ams_university_u_parent_wherein2_".$sqlr2;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded2= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
##########################
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded2); }
#########################
$rs=$this->tppymemcached->get($cachekeyencoded2);
if(!$datain2==$rs){$datain2=$rs;}else{   
$datain2=$this->db->query($sqlr2)->result_array();
$this->tppymemcached->set($cachekeyencoded2,$datain2,3600);
}
$num3=0;
foreach($datain2 as $k=>$v){
$id2=$v2['u_id'];
$num3++;
}
$result_set_uin2=implode(',', array_map(function($v2){ return $v2['u_id'];}, $datain2 ));

if(($groupby=='university'&& $u_group_id=='')||($groupby==''&& $u_group_id=='')){
$group_by='group by university_id';
$group_name='university';
$group_short_name='university';
}else if($groupby=='branch'&& $u_group_id==''){
$group_by='group by branch_id';
$group_name='branch';
$group_short_name='branch';
}else{
 $group_by='group by university_id,branch_id';
 $group_name='university & branch';
$group_short_name='university & branch';
}
 
$sql5="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id 
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set_uin2)  
$group_by order by university_id asc,m.idx asc";
$cache_key="get_ams_university_group_map_list_group_in_group0_".$sql;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
//$this->tppymemcached->delete($cachekeyencoded);
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
$group_in_data5=$this->tppymemcached->get($cachekeyencoded);
if(!$group_in_data5){
$group_in_data5=$this->db->query($sql5)->result_array();
$this->tppymemcached->set($cachekeyencoded,$group_in_data5,3600);
}
$arr_result5 = array();
if (is_array($group_in_data5)) {
  foreach ($group_in_data5 as $key5 => $val5) {
   #################
$university_id=$val5['university_id'];
if($university_id==''){}else{
################
$branch_id=$val5['branch_id'];
$data5 = array();
$data5['d']['university_parent_id']=$val5['university_parent_id'];
$data5['d']['university_id']=$val5['university_id'];
$data5['d']['branch_id']=$branch_id;
$data5['d']['faculty_id']=$val5['faculty_id'];
$data5['d']['map_id']=$val5['map_id'];
$data5['d']['group_type']=$val5['u_group_type'];
$groupid=$val5['group_id'];
$data5['d']['group_id']=$groupid;
$data5['d']['university_name']=$val5['university_name'];
$thumbnail=$val5['thumbnail'];
$data5['d']['thumbnail']='http://static.trueplookpanya.com/trueplookpanya/'.$thumbnail;
$data5['d']['branch_name']=$val5['branch_name'];
$data5['d']['faculty_name']=$val5['faculty_name'];
$data5['d']['group_name']=$val5['group_name'];
$data5['d']['group_description']=$val5['group_description'];
if($groupid==$group_id){
    $data5['d']['active']=1;
 }else{
    $data5['d']['active']=0;
 }
 $arr_result5[] = $data5['d'];
################ 
}

   }#################
 }else{ $arr_result5=null; }
$list5=$arr_result5;
$list_count=count($list5);
$data=array('list'=>$list5,
'count'=>$list_count,
'group_name'=>$group_name,
'group_short_name'=>$group_short_name,
'sql'=>$sql5,
);
#################################  
$this->response(array('header'=>array('title'=>'University list group4',
										'message'=>'Data is University group4',
										'status'=>true, 
										'code'=>200), 
'data'=>$data,)
,200);
Die();
}
$university_group3_lists=$this->model_trainer->get_ams_university_group_m2m_typy3($u_group_id,$delekey);
//echo'<hr><pre> university_group3_list=>';print_r($university_group3_list);echo'<pre><hr>';  die();
$university_group3_list=$university_group3_lists['data'];
$sql1=$university_group3_lists['sql'];
$university_group3_list_count=count($university_group3_list);
if($university_group3_list_count<=0){
				  $this->response(array('header'=>array(
										'title'=>'University list group4',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
}
#echo'<hr><pre> $university_group3_list=>';print_r($university_group3_list);echo'<pre> <hr>'; 
$datars=array();
 foreach($university_group3_list as $key => $value) {
 //$idx=$value['idx'];
 //$datars[$key]['idx']=$idx;
 //$datars[$key]['u_group_id_source']=$value['u_group_id_source'];
 $datars[$key]['u_group_id_destination']=$value['u_group_id_destination'];
 #################
}

//$array_m1pre=explode(',', $datars);
$num=0;
foreach($datars as $k=>$v){
 $id=$v['u_group_id_destination'];
 $num++;
}
$result_set=implode(',', array_map(function($v){ return $v['u_group_id_destination'];}, $datars ));
$groupby=@$this->input->get('group_by');
if($groupby=='university'&& $u_group_id!=''){
 $sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set)  
group by group_id,university_id  order by university_id asc,m.idx asc";

}elseif($groupby=='branch'&& $u_group_id!=''){
 $sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set)  
group by group_id  order by m.idx asc";
}else if($groupby==''){
 $sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set)  
group by group_id order by amsu.u_id asc,m.idx asc";
}
#echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>';  die();
$cache_key="get_ams_university_group_map_list_group_in_".$sql;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
$group_in_data=$this->tppymemcached->get($cachekeyencoded);
if(!$group_in_data){
$group_in_data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cachekeyencoded,$data,600);
}
###############################
$arr_result4 = array();
if (is_array($group_in_data)) {
  foreach ($group_in_data as $key4 => $val4) {
   #################
$university_id=$val4['university_id'];
if($university_id==''){}else{
################
$branch_id=$val4['branch_id'];
$data4 = array();
//$data4['d']['university_parent_id']=$val4['university_parent_id'];
//$data4['d']['university_id']=$val4['university_id'];
//$data4['d']['branch_id']=$branch_id;
//$data4['d']['faculty_id']=$val4['faculty_id'];
//$data4['d']['map_id']=$val4['map_id'];
$data4['d']['group_type']=$val4['u_group_type'];
$groupid=$val4['group_id'];
$data4['d']['group_id']=$groupid;
//$data4['d']['university_name']=$val4['university_name'];
//$thumbnail=$val4['thumbnail'];
//$data4['d']['thumbnail']='http://static.trueplookpanya.com/trueplookpanya/'.$thumbnail;
$data4['d']['branch_name']=$val4['branch_name'];
$data4['d']['faculty_name']=$val4['faculty_name'];
$data4['d']['group_name']=$val4['group_name'];
$data4['d']['group_description']=$val4['group_description'];
if($groupid==$group_id){
    $data4['d']['active']=1;
 }else{
    $data4['d']['active']=0;
 }
 $arr_result4[] = $data4['d'];
################ 
}

   }#################
 }else{ $arr_result4=null; }
$list=$arr_result4;
$group_in_data_count=count($list);
##############################
$group_type3name=$this->model_trainer->get_group_type3_id_list($u_group_id,$delekey);
$group_type3_name=$group_type3name['0'];
$data=array('count'=>$group_in_data_count,
'list_set'=>$result_set,
'group_name'=>$group_type3_name['u_group_name'],
'group_short_name'=>$group_type3_name['short_description'],
'list'=>$list,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
####branch group4listgroupidams TAB branch###
public function u4amsold_get(){
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$member_id=@$this->tppy_member->get_member_profile()->member_id;
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$u_group_id=@$this->input->get('u_group_id_type4');
if($u_group_id<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
$tab=@$this->input->get('tab');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_group=$this->model_trainer->get_ams_university_group_id($u_group_id,$delekey);
//echo'<pre> university_group=>';print_r($university_group);echo'<pre>';  die();
$university_group4=$university_group;
$sql1=$university_group['sql'];
$university_group4_count=count($university_group4);
if($university_group4_count<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>201), 
									  'data'=> Null),201);
Die();
}
#echo'<hr><pre> $university_group4=>';print_r($university_group4);echo'<pre> <hr>'; 
$datalist=array();
 foreach($university_group4 as $key => $value) {
 	  $datars=array();
       $u_group_id=$value['u_group_id'];
       $datars['na']['group_id']=$u_group_id;
       $datars['na']['u_group_name']=$value['u_group_name'];
       $datars['na']['short_description']=$value['short_description'];
       $datars['na']['thumbnail']=$value['thumbnail'];
       $datars['na']['detail']=$value['detail'];
       $datars['na']['u_group_type']=$value['u_group_type'];
       $datars['na']['record_status']=$value['record_status'];
       $datars['na']['add_timestamp']=$value['add_timestamp'];
       $datars['na']['update_timestamp']=$value['update_timestamp'];
 #################
 $datalist[]=$datars['na'];
}
#echo'<pre>datalist=>';print_r($datalist);echo'<pre>';  //die();
$university_group4=$university_group4['0'];
#echo'<pre>$university_group4=>';print_r($university_group4);echo'<pre>';  //die();
$u_group_id=$university_group4['u_group_id'];
$u_group_name=$university_group4['u_group_name'];
$short_description=$university_group4['short_description'];
#echo'<pre>u_group_id=>';print_r($u_group_id);echo'<pre>';  
$group_in_data=$this->model_trainer->get_group_type4_u_group_id_list($u_group_id,$delekey);
//echo'<pre> group_in_data=>';print_r($group_in_data);echo'<pre>';  die();
###########################################################################
###########################################################################
###########################################################################

$arr_result4 = array();
if (is_array($group_in_data)) {
	foreach ($group_in_data as $key4 => $val4) {
#################
$data4 = array();
$university_id=$val4['university_id'];
if($university_id==''){}else{
################
$branchid=$val4['branch_id'];
###########################################################################
###########################################################################
###########################################################################
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
#################
$amss=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branchid,$year,$delekey);
$ams=$amss['data'];
if(!$ams){$group_ams_data_count=0;}
#################
$datasetamss = array();
if (is_array($ams)) {
foreach ($ams as $keydatas => $val) {
$dataset=array();
$ent_id=$val['ent_id'];
$datasetamss[]=$dataset['dd'];
}
}else{ $datasetamss=null; }
$group_ams_data_count=count($datasetamss);
###########################################################################
if($datasetamss==null){}else{
	$university_id=(int)$val4['university_id'];
	$data4['d']['university_id']=$university_id;
	$data4['d']['university_parent_id']=$val4['university_parent_id'];
	$faculty_id=(int)$val4['faculty_id'];
	$data4['d']['faculty']['faculty_id']=$val4['faculty_id'];
	$data4['d']['faculty']['faculty_name']=$val4['faculty_name'];
     
	$branchdata=$this->model_trainer->get_ams_university_u_parent_id_where($faculty_id,$delekey);
     //$data4['d']['faculty']['branch_sql']=$branchdata['sql'];
     //echo 'branchdata=>';echo'<pre>';print_r($branchdata);echo'<pre>'; die();
	$branch_data=$branchdata['data'];
$arr_branch = array();
if (is_array($branch_data)){
foreach ($branch_data as $kb =>$vab){
     $brancharr = array();
	$branch_id=$vab['u_id'];
	$brancharr['dn']['branch_id']=$branch_id;
	$brancharr['dn']['university_name']=$val4['university_name'];
	$brancharr['dn']['faculty_name']=$val4['faculty_name'];
	$brancharr['dn']['branch_name']=$vab['u_name'];
#######################	

$amss_set=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_id_set_user_id($year,$branch_id,$user_id,$delekey);
$amss_set_data=$amss_set['data'];
if($amss_set_data==null){
$amss_entset=null;
}else{
$amss_entset=implode(',', array_map(function($amss_set_data){ return $amss_set_data['ent_id'];}, $amss_set_data ));
}

//$amss=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branch_id,$year,$delekey);
//$ams=$amss['data'];
if($amss_entset==null){
$instatus=0;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,0,$delekey);
}else{
$instatus=1;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,$amss_entset,$delekey);
}

$amsset=$amssets['data'];
$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keydatas => $val) {
$dataset=array();
$ent_id=$val['ent_id'];

############################
$branch_id=$val['branch_id'];
$faculty_id=0;
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$branch_id,$user_id,0,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
$ent_id_user=$ams_entrance_user1['ent_id'];
if($user_id==''){
     $dataset['dd']['bookmark_user']=null;
     $bookmark_active=0;
}else{
     $bookmark_active=$ams_entrance_user1;
     if($ent_id_user==$ent_id){
       $bookmark_active=1;
     }else{
      $bookmark_active=0;
     }
}
//$dataset['dd']['bookmark_users']=$ams_entrance_users;
$dataset['dd']['bookmark_active']=$bookmark_active;

############################
      
      $dataset['dd']['year_config']=$val['year_config'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
      $dataset['dd']['score_history']=$score_history;
      $dataset['dd']['ent_id']=$val['ent_id'];
     
      $dataset['dd']['01_onet_mi']=$val['01_onet_mi'];
      $dataset['dd']['01_onet_weight']=$val['01_onet_weight'];
      $dataset['dd']['02_onet_min']=$val['02_onet_min'];
      $dataset['dd']['02_onet_weight']=$val['02_onet_weight'];
      $dataset['dd']['03_onet_mint']=$val['03_onet_mint'];
      $dataset['dd']['03_onet_weight']=$val['03_onet_weight'];
      $dataset['dd']['04_onet_min']=$val['04_onet_min'];
      $dataset['dd']['04_onet_weight']=$val['04_onet_weight'];
      $dataset['dd']['05_onet_min']=$val['05_onet_min'];
      $dataset['dd']['05_onet_weight']=$val['05_onet_weight'];
      $dataset['dd']['71_pat1_min']=$val['71_pat1_min'];
      $dataset['dd']['71_pat1_weight']=$val['71_pat1_weight'];
      $dataset['dd']['72_pat2_min']=$val['72_pat2_min'];
      $dataset['dd']['72_pat2_weight']=$val['72_pat2_weight'];
      $dataset['dd']['73_pat3_min']=$val['73_pat3_min'];
      $dataset['dd']['73_pat3_weight']=$val['73_pat3_weight'];
      $dataset['dd']['74_pat4_min']=$val['74_pat4_min'];
      $dataset['dd']['74_pat4_weight']=$val['74_pat4_weight'];
      $dataset['dd']['75_pat5_min']=$val['75_pat5_min'];
      $dataset['dd']['75_pat5_weight']=$val['75_pat5_weight'];
      $dataset['dd']['76_pat6_min']=$val['76_pat6_min'];
      $dataset['dd']['76_pat6_weight']=$val['76_pat6_weight'];
      $dataset['dd']['77_pat71_min']=$val['77_pat71_min'];
      $dataset['dd']['77_pat71_weight']=$val['77_pat71_weight'];
      $dataset['dd']['78_pat72_min']=$val['78_pat72_min'];
      $dataset['dd']['78_pat72_weight']=$val['78_pat72_weight'];
      $dataset['dd']['79_pat73_min']=$val['79_pat73_min'];
      $dataset['dd']['79_pat73_weight']=$val['79_pat73_weight'];
      $dataset['dd']['80_pat74_min']=$val['80_pat74_min'];
      $dataset['dd']['80_pat74_weight']=$val['80_pat74_weight'];
      $dataset['dd']['81_pat75_min']=$val['81_pat75_min'];
      $dataset['dd']['81_pat75_weight']=$val['81_pat75_weight'];
      $dataset['dd']['82_pat76_min']=$val['82_pat76_min'];
      $dataset['dd']['82_pat76_weight']=$val['82_pat76_weight'];
      $dataset['dd']['85_gat_min']=$val['85_gat_min'];
      $dataset['dd']['85_gat_min_part2']=$val['85_gat_min_part2'];
      $dataset['dd']['85_gat_weight']=$val['85_gat_weight'];
      $dataset['dd']['branch_id']=$val['branch_id'];
      $dataset['dd']['branch_name']=$val['branch_name'];
      $dataset['dd']['config']=$val['config'];
      $dataset['dd']['university_name']=$val['university_name'];
      $dataset['dd']['faculty_id']=$val['faculty_id'];
      $dataset['dd']['faculty_name']=$val['faculty_name'];
      $dataset['dd']['gpax_min']=$val['gpax_min'];
      $dataset['dd']['gpax_weight']=$val['gpax_weight'];
      $dataset['dd']['major_code']=$val['major_code'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      $dataset['dd']['onet_min_total']=$val['onet_min_total'];
      $dataset['dd']['onet_weight_total']=$val['onet_weight_total'];
      $sharecode=$val['receive_amount_sharecode'];
      $dataset['dd']['receive_amount_sharecode']= $sharecode;
      if($sharecode==''){
        $dataset['dd']['sharecode_data']=null;
        $dataset['dd']['receive_amount']=(int)$val['receive_amount'];
        $dataset['dd']['sharecode_detail']=null;
        $dataset['dd']['sharecode_set']=null;
         $dataset['dd']['sharecode_msg']=null;
      }else{
       $majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
       $majorcodeshare_data=$majorcodeshare['data']['0'];
       $receiveamount=(int)$majorcodeshare_data['receive_amount'];
       $dataset['dd']['sharecode_data']=$majorcodeshare_data;
       $dataset['dd']['receive_amount']=$receiveamount;
       $sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
       $sharecode_data=$sharecode['data'];
       $dataset['dd']['detail']=$sharecode_data;
       $sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
       $dataset['dd']['sharecode_set']=$sharecode_data_set;
      $faculty_name_all=$val['faculty_name'];
       $dataset['dd']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
      }
      $dataset['dd']['score_max']=$val['score_max'];
      $dataset['dd']['score_min']=$val['score_min'];
      $dataset['dd']['special_remark']=$val['special_remark'];
      $dataset['dd']['lastupdate']=$val['lastupdate'];
      $datasetamss[]=$dataset['dd'];
  }
}else{ $datasetamss=null;}
#######################	
if($tab=='directapply'){}elseif($tab==null ||$tab=='admission'){
    # $brancharr['dn']['admission_sql']=$amssets['sql'];
     $brancharr['dn']['admission_set']=$amss_entset;
     $brancharr['dn']['admission']=$datasetamss;
     $admission_count=(int)count($datasetamss);
}



$brancharr['dn']['admission_count']=$admission_count;
$university_id=(int)$val4['university_id'];



#$dirapplyall=$this->model_trainer->get_ams_entrance_dirapply_year_set($year,$branch_id,$university_id,$delekey);
#$dirapplyall=$dirapply['data'];

$dirapplyin=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_in_user_id($year,$branch_id,$university_id,$user_id,$delekey);
$dirapplyinnews=$dirapplyin['data'];
if($dirapplyinnews==''){
$newsidnotinset=null;
}else{
$newsidnotinset=implode(',', array_map(function($dirapplyinnews){ return $dirapplyinnews['news_id'];}, $dirapplyinnews ));
}
if($newsidnotinset==null){
$instatus=0;
$dirapplyrs=$this->model_trainer->get_ams_entrance_dirapply_year_branch_id_user_id($year,$branch_id,$university_id,$user_id,null,0,$delekey);
$dirapply_data=$dirapplyrs['data'];
}else{
$instatus=1;
$dirapplyrs=$this->model_trainer->get_ams_entrance_dirapply_year_branch_id_user_id($year,$branch_id,$university_id,$user_id,$newsidnotinset,$instatus,$delekey);
$dirapply_data=$dirapplyrs['data'];
}
//echo'<pre> dirapply_data=>';print_r($dirapply_data);echo'<pre> <hr>'; die();
$dirapply_count=(int)count($dirapply_data);
$dirapplysql=$dirapplyrs['sql'];	

$dirapply_lists = array();
if (is_array($dirapply_data)){
	foreach ($dirapply_data as $key => $value) {
		$datanews = array();	
		$news_id=(int)$value['news_id'];
		$datanews['news']['news_id']=$news_id;
############################
$branch_id=$val['branch_id'];
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id(0,$news_id,$user_id,$branch_id,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
$u_id_user=(int)$ams_entrance_user1['u_id'];
if($user_id==''){
     $datanews['news']['bookmark_user']=null;
     $bookmark_active2=0;
}else{
     $datanews['news']['bookmark_user']=$ams_entrance_user1;
     if($news_id==$u_id_user){
      $bookmark_active2=1;
     }else{
      $bookmark_active2=0;
     }
}
$datanews['news']['bookmark_active']=$bookmark_active2;
############################
		$datanews['news']['news_title']=$value['news_title'];
		$datanews['news']['u_id']=(int)$value['u_id'];
		$datanews['news']['year']=(int)$value['yearnews'];
		$datanews['news']['remark']=$value['major_remark'];
		$datanews['news']['branch_id']=$branch_id;
		$datanews['news']['faculty_id']=(int)$value['faculty_id'];
		$datanews['news']['university_id']=(int)$value['university_id'];
		$datanews['news']['branch_name']=$value['branch_name'];
		$datanews['news']['faculty_name']=$value['faculty_name'];
		$datanews['news']['university_name']=$value['university_name'];
		$datanews['news']['interview_start_date']=$value['interview_start_date'];
		$datanews['news']['interview_end_date']=$value['interview_end_date'];
		$datanews['news']['receiver_all']=(int)$value['news_all_receivers'];
          $datanews['news']['receiver']=(int)$value['major_receivers'];
          $datanews['news']['grade']=$value['major_grade'];
          $datanews['news']['gatpat']=$value['major_gatpat'];
          $datanews['news']['gnet']=$value['major_gnet'];
          $datanews['news']['seven']=$value['major_seven'];
		$datanews['news']['news_receivers_text']=$value['news_receivers_text'];
		#################
		$dirapply_lists[]=$datanews['news'];
	}
}else{ $dirapply_lists=null; }
if($tab==null || $tab=='directapply'){
    # $brancharr['dn']['dirapply_sql']=$dirapplysql;
     $brancharr['dn']['dirapplyuser_set']=$newsidnotinset;
     $brancharr['dn']['dirapply']=$dirapply_lists;
     $brancharr['dn']['dirapply_count']=$dirapply_count;
}elseif($tab==null ||$tab=='admission'){}

     
     
     
     if($dirapply_count>0 || $admission_count>0){
      $arr_branch[]=$brancharr['dn'];
     }
     
     
     }
}
$branch_count=(int)count($arr_branch);
$data4['d']['faculty']['branch_count']=$branch_count;

$branch_set=implode(',', array_map(function($arr_branch){ return $arr_branch['branch_id'];}, $arr_branch ));
	
#$faculty_count=(int)count($group_in_data);
#$data4['d']['faculty_count']=$faculty_count;
     $data4['d']['faculty']['branch_set']=$branch_set;
	$data4['d']['faculty']['branch']=$arr_branch;
     $data4['d']['branch_count']=$branch_count;
	$data4['d']['group_type']=$val4['u_group_type'];
	$data4['d']['group_id']=$val4['group_id'];
	$data4['d']['university_name']=$val4['university_name'];
	$data4['d']['thumbnail']=$val4['thumbnail'];
	$data4['d']['group_name']=$val4['group_name'];
	$data4['d']['group_description']=$val4['group_description'];
	################ 
if($branch_count>0){ $arr_result4[] = $data4['d']; }	
}
###########################################################################
}}#################
}else{ $arr_result4=null; }
$list=$arr_result4;
$group_type3name=$this->model_trainer->get_group_type3_id_list($u_group_id,$delekey);
$group_type3_name=$group_type3name['0'];
$data=array('u_group_id'=>$u_group_id,
			'group_name'=>$u_group_name,
	 		'list'=>$list,
	 		'user_id'=>$user_id,);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
     
#############
}
public function u4amsV1_get(){
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$member_id=@$this->tppy_member->get_member_profile()->member_id;
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');


$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');

 
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
$universitygroup4=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);
$university_group4=$universitygroup4['0'];
$group_type4_name=$university_group4['u_group_name'];
$u_group_id=@$this->input->get('u_group_id_type4');
if($u_group_id<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
$tab=@$this->input->get('tab');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_group=$this->model_trainer->get_ams_university_group_id($u_group_id,$delekey);
//echo'<pre> university_group=>';print_r($university_group);echo'<pre>';  die();
$university_group4=$university_group;
$sql1=$university_group['sql'];
$university_group4_count=count($university_group4);
if($university_group4_count<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>201), 
									  'data'=> Null),201);
Die();
}
#echo'<hr><pre> $university_group4=>';print_r($university_group4);echo'<pre> <hr>'; 
$datalist=array();
 foreach($university_group4 as $key => $value) {
 	  $datars=array();
       $u_group_id=$value['u_group_id'];
       $datars['na']['group_id']=$u_group_id;
       $datars['na']['u_group_name']=$value['u_group_name'];
       $datars['na']['short_description']=$value['short_description'];
       $datars['na']['thumbnail']=$value['thumbnail'];
       $datars['na']['detail']=$value['detail'];
       $datars['na']['u_group_type']=$value['u_group_type'];
       $datars['na']['record_status']=$value['record_status'];
       $datars['na']['add_timestamp']=$value['add_timestamp'];
       $datars['na']['update_timestamp']=$value['update_timestamp'];
 #################
 $datalist[]=$datars['na'];
}
#echo'<pre>datalist=>';print_r($datalist);echo'<pre>';  //die();
$university_group4=$university_group4['0'];
#echo'<pre>$university_group4=>';print_r($university_group4);echo'<pre>';  //die();
$u_group_id=$university_group4['u_group_id'];
$u_group_name=$university_group4['u_group_name'];
$short_description=$university_group4['short_description'];
#echo'<pre>u_group_id=>';print_r($u_group_id);echo'<pre>';  
$group_in_data=$this->model_trainer->get_group_type4_u_group_id_list($u_group_id,$delekey);
//echo'<pre> group_in_data=>';print_r($group_in_data);echo'<pre>';  die();
###########################################################################
###########################################################################
###########################################################################
$branchsetmas=implode(',', array_map(function($group_in_data){ return $group_in_data['branch_id'];}, $group_in_data ));
#echo'<pre> group_in_data=>';print_r($group_in_data);echo'<pre>';  die();
$arr_result4 = array();
if (is_array($group_in_data)) {
	foreach ($group_in_data as $key4 => $val4) {
#################
$data4 = array();
$university_id=$val4['university_id'];
if($university_id==''){}else{
################
$branchid=$val4['branch_id'];
###########################################################################
###########################################################################
###########################################################################
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
#################
$amss=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branchid,$year,$delekey);
$ams=$amss['data'];
if(!$ams){$group_ams_data_count=0;}
#################
$datasetamss = array();
if (is_array($ams)) {
foreach ($ams as $keydatas => $val) {
$dataset=array();
$ent_id=$val['ent_id'];
$datasetamss[]=$dataset['dd'];
}
}else{ $datasetamss=null; }
$group_ams_data_count=count($datasetamss);
###########################################################################
if($datasetamss==null){}else{
	$university_id=(int)$val4['university_id'];
	$data4['d']['university_id']=$university_id;
	$data4['d']['university_parent_id']=$val4['university_parent_id'];
	$faculty_id=(int)$val4['faculty_id'];
	$data4['d']['faculty']['faculty_id']=$val4['faculty_id'];
	$data4['d']['faculty']['faculty_name']=$val4['faculty_name'];
     $data4['d']['year']=(int)$year;
     
#$branchdata1=$this->model_trainer->get_ams_university_u_parent_id_where($faculty_id,$delekey);
$branchdata=$this->model_trainer->get_ams_university_u_parent_id_where_u_group_id($faculty_id,$u_group_id_type4,$delekey);
     //$data4['d']['faculty']['branch_sql']=$branchdata['sql'];
     //echo 'branchdata=>';echo'<pre>';print_r($branchdata);echo'<pre>'; die();
	$branch_data=$branchdata['data'];
$arr_branch = array();
if (is_array($branch_data)){
foreach ($branch_data as $kb =>$vab){
     $brancharr = array();
	$branch_id=$vab['u_id'];
	$brancharr['dn']['branch_id']=$branch_id;
	$brancharr['dn']['university_name']=$val4['university_name'];
	$brancharr['dn']['faculty_name']=$val4['faculty_name'];
	$brancharr['dn']['branch_name']=$vab['u_name'];
#######################	

$amss_set=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_id_set_user_id($year,$branch_id,$user_id,$delekey);
$amss_set_data=$amss_set['data'];
if($amss_set_data==null){
$amss_entset=null;
}else{
$amss_entset=implode(',', array_map(function($amss_set_data){ return $amss_set_data['ent_id'];}, $amss_set_data ));
}

//$amss=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branch_id,$year,$delekey);
//$ams=$amss['data'];
if($amss_entset==null){
$instatus=0;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,0,$delekey);
}else{
$instatus=1;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,$amss_entset,$delekey);
}

$amsset=$amssets['data'];
$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keydatas => $val) {
$dataset=array();
$ent_id=$val['ent_id'];

############################
$branch_id=$val['branch_id'];
$faculty_id=0;
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$branch_id,$user_id,0,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
$ent_id_user=$ams_entrance_user1['ent_id'];
if($user_id==''){
     $dataset['dd']['bookmark_user']=null;
     $bookmark_active=0;
}else{
     $bookmark_active=$ams_entrance_user1;
     if($ent_id_user==$ent_id){
       $bookmark_active=1;
     }else{
      $bookmark_active=0;
     }
}
//$dataset['dd']['bookmark_users']=$ams_entrance_users;
$dataset['dd']['bookmark_active']=$bookmark_active;

############################
      
      $dataset['dd']['year_config']=$val['year_config'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
      $dataset['dd']['score_history']=$score_history;
      $dataset['dd']['ent_id']=$val['ent_id'];
     
      $dataset['dd']['01_onet_mi']=$val['01_onet_mi'];
      $dataset['dd']['01_onet_weight']=$val['01_onet_weight'];
      $dataset['dd']['02_onet_min']=$val['02_onet_min'];
      $dataset['dd']['02_onet_weight']=$val['02_onet_weight'];
      $dataset['dd']['03_onet_mint']=$val['03_onet_mint'];
      $dataset['dd']['03_onet_weight']=$val['03_onet_weight'];
      $dataset['dd']['04_onet_min']=$val['04_onet_min'];
      $dataset['dd']['04_onet_weight']=$val['04_onet_weight'];
      $dataset['dd']['05_onet_min']=$val['05_onet_min'];
      $dataset['dd']['05_onet_weight']=$val['05_onet_weight'];
      $dataset['dd']['71_pat1_min']=$val['71_pat1_min'];
      $dataset['dd']['71_pat1_weight']=$val['71_pat1_weight'];
      $dataset['dd']['72_pat2_min']=$val['72_pat2_min'];
      $dataset['dd']['72_pat2_weight']=$val['72_pat2_weight'];
      $dataset['dd']['73_pat3_min']=$val['73_pat3_min'];
      $dataset['dd']['73_pat3_weight']=$val['73_pat3_weight'];
      $dataset['dd']['74_pat4_min']=$val['74_pat4_min'];
      $dataset['dd']['74_pat4_weight']=$val['74_pat4_weight'];
      $dataset['dd']['75_pat5_min']=$val['75_pat5_min'];
      $dataset['dd']['75_pat5_weight']=$val['75_pat5_weight'];
      $dataset['dd']['76_pat6_min']=$val['76_pat6_min'];
      $dataset['dd']['76_pat6_weight']=$val['76_pat6_weight'];
      $dataset['dd']['77_pat71_min']=$val['77_pat71_min'];
      $dataset['dd']['77_pat71_weight']=$val['77_pat71_weight'];
      $dataset['dd']['78_pat72_min']=$val['78_pat72_min'];
      $dataset['dd']['78_pat72_weight']=$val['78_pat72_weight'];
      $dataset['dd']['79_pat73_min']=$val['79_pat73_min'];
      $dataset['dd']['79_pat73_weight']=$val['79_pat73_weight'];
      $dataset['dd']['80_pat74_min']=$val['80_pat74_min'];
      $dataset['dd']['80_pat74_weight']=$val['80_pat74_weight'];
      $dataset['dd']['81_pat75_min']=$val['81_pat75_min'];
      $dataset['dd']['81_pat75_weight']=$val['81_pat75_weight'];
      $dataset['dd']['82_pat76_min']=$val['82_pat76_min'];
      $dataset['dd']['82_pat76_weight']=$val['82_pat76_weight'];
      $dataset['dd']['85_gat_min']=$val['85_gat_min'];
      $dataset['dd']['85_gat_min_part2']=$val['85_gat_min_part2'];
      $dataset['dd']['85_gat_weight']=$val['85_gat_weight'];
      $dataset['dd']['branch_id']=$val['branch_id'];
      $dataset['dd']['branch_name']=$val['branch_name'];
      $dataset['dd']['config']=$val['config'];
      $dataset['dd']['university_name']=$val['university_name'];
      $dataset['dd']['faculty_id']=$val['faculty_id'];
      $dataset['dd']['faculty_name']=$val['faculty_name'];
      $dataset['dd']['gpax_min']=$val['gpax_min'];
      $dataset['dd']['gpax_weight']=$val['gpax_weight'];
      $dataset['dd']['major_code']=$val['major_code'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      $dataset['dd']['onet_min_total']=$val['onet_min_total'];
      $dataset['dd']['onet_weight_total']=$val['onet_weight_total'];
      $sharecode=$val['receive_amount_sharecode'];
      $dataset['dd']['receive_amount_sharecode']= $sharecode;
      if($sharecode==''){
        $dataset['dd']['sharecode_data']=null;
        $dataset['dd']['receive_amount']=(int)$val['receive_amount'];
        $dataset['dd']['sharecode_detail']=null;
        $dataset['dd']['sharecode_set']=null;
         $dataset['dd']['sharecode_msg']=null;
      }else{
       $majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
       $majorcodeshare_data=$majorcodeshare['data']['0'];
       $receiveamount=(int)$majorcodeshare_data['receive_amount'];
       $dataset['dd']['sharecode_data']=$majorcodeshare_data;
       $dataset['dd']['receive_amount']=$receiveamount;
       $sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
       $sharecode_data=$sharecode['data'];
       $dataset['dd']['detail']=$sharecode_data;
       $sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
       $dataset['dd']['sharecode_set']=$sharecode_data_set;
      $faculty_name_all=$val['faculty_name'];
       $dataset['dd']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
      }
      $dataset['dd']['score_max']=$val['score_max'];
      $dataset['dd']['score_min']=$val['score_min'];
      $dataset['dd']['special_remark']=$val['special_remark'];
      $dataset['dd']['lastupdate']=$val['lastupdate'];
      $datasetamss[]=$dataset['dd'];
  }
}else{ $datasetamss=null;}
#######################	
if($tab=='directapply'){}elseif($tab==null ||$tab=='admission'){
    # $brancharr['dn']['admission_sql']=$amssets['sql'];
     $brancharr['dn']['admission_set']=$amss_entset;
     $brancharr['dn']['admission']=$datasetamss;
     $admission_count=(int)count($datasetamss);
}



$brancharr['dn']['admission_count']=$admission_count;
$university_id=(int)$val4['university_id'];

if($tab==null ||$tab=='directapply'){
#################****directapply******###################
##############****directapply setting****#################

$directapplyset=$this->model_trainer->get_ams_entrance_user_year_user_id_directapply($branch_id,$year,$user_id,$delekey);
$directapplyset_data=$directapplyset['data'];
$directapplyset_data_count=(int)count($directapplyset_data);
$brancharr['dn']['directapply_set_count']=$directapplyset_data_count;
if($directapplyset_data_count==0){$directapplystatus=0;}else{$directapplystatus=1;}
$brancharr['dn']['directapply_active']=$directapplystatus;
$sumdirectapplyset=$this->model_trainer->get_ams_news_directapply_branch_id_year_sum($branch_id,$year,$delekey);
$brancharr['dn']['directapply_sum']=(int)$sumdirectapplyset['data']['0']['major_receivers_all'];
##############****directapply setting****##################
##################****directapply******###################
}

########################################################
########################################################
########################################################
#$dirapplyall=$this->model_trainer->get_ams_entrance_dirapply_year_set($year,$branch_id,$university_id,$delekey);
#$dirapplyall=$dirapply['data'];
/*
$dirapplyin=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_in_user_id($year,$branch_id,$university_id,$user_id,$delekey);
$dirapplyinnews=$dirapplyin['data'];
if($dirapplyinnews==''){
$newsidnotinset=null;
}else{
$newsidnotinset=implode(',', array_map(function($dirapplyinnews){ return $dirapplyinnews['news_id'];}, $dirapplyinnews ));
}

if($newsidnotinset==null){
$instatus=0;
$dirapplyrs=$this->model_trainer->get_ams_entrance_dirapply_year_branch_id_user_id($year,$branch_id,$university_id,$user_id,null,0,$delekey);
$dirapply_data=$dirapplyrs['data'];
}else{
$instatus=1;
$dirapplyrs=$this->model_trainer->get_ams_entrance_dirapply_year_branch_id_user_id($year,$branch_id,$university_id,$user_id,$newsidnotinset,$instatus,$delekey);
$dirapply_data=$dirapplyrs['data'];
}
//echo'<pre> dirapply_data=>';print_r($dirapply_data);echo'<pre> <hr>'; die();
$dirapply_count=(int)count($dirapply_data);
$dirapplysql=$dirapplyrs['sql'];	
####################################################
$dirapply_lists = array();
if (is_array($dirapply_data)){
	foreach ($dirapply_data as $key => $value) {
		$datanews = array();	
		$news_id=(int)$value['news_id'];
		$datanews['news']['news_id']=$news_id;
############################
$branch_id=$val['branch_id'];
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id(0,$news_id,$user_id,$branch_id,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
$u_id_user=(int)$ams_entrance_user1['u_id'];
if($user_id==''){
     $datanews['news']['bookmark_user']=null;
     $bookmark_active2=0;
}else{
     $datanews['news']['bookmark_user']=$ams_entrance_user1;
     if($news_id==$u_id_user){
      $bookmark_active2=1;
     }else{
      $bookmark_active2=0;
     }
}
$datanews['news']['bookmark_active']=$bookmark_active2;
############################
		$datanews['news']['news_title']=$value['news_title'];
		$datanews['news']['u_id']=(int)$value['u_id'];
		$datanews['news']['year']=(int)$value['yearnews'];
		$datanews['news']['remark']=$value['major_remark'];
		$datanews['news']['branch_id']=$branch_id;
		$datanews['news']['faculty_id']=(int)$value['faculty_id'];
		$datanews['news']['university_id']=(int)$value['university_id'];
		$datanews['news']['branch_name']=$value['branch_name'];
		$datanews['news']['faculty_name']=$value['faculty_name'];
		$datanews['news']['university_name']=$value['university_name'];
		$datanews['news']['interview_start_date']=$value['interview_start_date'];
		$datanews['news']['interview_end_date']=$value['interview_end_date'];
		$datanews['news']['receiver_all']=(int)$value['news_all_receivers'];
          $datanews['news']['receiver']=(int)$value['major_receivers'];
          $datanews['news']['grade']=$value['major_grade'];
          $datanews['news']['gatpat']=$value['major_gatpat'];
          $datanews['news']['gnet']=$value['major_gnet'];
          $datanews['news']['seven']=$value['major_seven'];
		$datanews['news']['news_receivers_text']=$value['news_receivers_text'];
		#################
		$dirapply_lists[]=$datanews['news'];
	}
}else{ $dirapply_lists=null; }

if($tab==null || $tab=='directapply'){
    # $brancharr['dn']['dirapply_sql']=$dirapplysql;
     #$brancharr['dn']['dirapplyuser_set']=$newsidnotinset;
     #$brancharr['dn']['dirapply']=$dirapply_lists;
     #$brancharr['dn']['dirapply_count']=$dirapply_count;
}elseif($tab==null ||$tab=='admission'){}
*/
########################################################
########################################################
########################################################
     if($admission_count>0){
      $arr_branch[]=$brancharr['dn'];
     }
     
     
     }
}
$branch_count=(int)count($arr_branch);
$data4['d']['faculty']['branch_count']=$branch_count;

$branch_set=implode(',', array_map(function($arr_branch){ return $arr_branch['branch_id'];}, $arr_branch ));
	
#$faculty_count=(int)count($group_in_data);
#$data4['d']['faculty_count']=$faculty_count;
     $data4['d']['faculty']['branch_set']=$branch_set;
	$data4['d']['faculty']['branch']=$arr_branch;
     $data4['d']['branch_count']=$branch_count;
	$data4['d']['group_type']=$val4['u_group_type'];
	$data4['d']['group_id']=$val4['group_id'];
	$data4['d']['university_name']=$val4['university_name'];
	$data4['d']['thumbnail']=$val4['thumbnail'];
	$data4['d']['group_name']=$val4['group_name'];
	$data4['d']['group_description']=$val4['group_description'];
	################ 
if($branch_count>0){ $arr_result4[] = $data4['d']; }	
}
###########################################################################
}}#################
}else{ $arr_result4=null; }
#$list=$arr_result4;
 
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
#################################################
function array_unique_multidimensional($input){
    $serialized = array_map('serialize', $input);
    $unique = array_unique($serialized);
    return array_intersect_key($input, $unique);
}
$list=array_unique_multidimensional($arr_result4);
#################################################
$data=array('u_group_id'=>$u_group_id,
			'u_group_id_type3'=>$u_group_id_type3,
               'u_group_id_type4'=>$u_group_id_type4,
               'group_name'=>$u_group_name,
               'group_type3_name'=>$group_type3_name,
               'group_type3_thumbnail'=>$group_type3_thumbnail,
               'group_type4_name'=>$group_type4_name,
               'year'=>(int)$year,
	 		'list'=>$list,
               'branchsetmas'=>$branchsetmas,
               'list_count'=>count($list),
	 		'user_id'=>$user_id,);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
     
#############
}
public function udamsdirectapply_get(){
###########################################################################
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
#################
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group directapply',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$member_id=@$this->tppy_member->get_member_profile()->member_id;
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
$universitygroup4=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);
$university_group4=$universitygroup4['0'];
$group_type4_name=$university_group4['u_group_name'];
$u_group_id=@$this->input->get('u_group_id_type4');
if($u_group_id<=0){
$this->response(array('header'=>array('title'=>'ams entrance group directapply',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
$tab=@$this->input->get('tab');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey='';}else{$delekey='1';}
#$university_groupold=$this->model_trainer->get_ams_university_group_id($u_group_id,$delekey);
$university_group=$this->model_trainer->get_ams_university_group_id_type_year($u_group_id,$year,1,$delekey);
#echo'<pre> university_group=>';print_r($university_group);echo'<pre>';  die();
$university_group4=$university_group['data'];
$university_group4_data_count=(int)$university_group['data_count'];
$sql1=$university_group['sql'];
$university_group4_count=count($university_group4);
if($university_group4_count<=0){
$this->response(array('header'=>array('title'=>'ams entrance group directapply',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>201), 
									  'data'=> Null),201);
Die();
}
 //echo'<hr><pre> $university_group4=>';print_r($university_group4);echo'<pre> <hr>'; 
$datalist=array();
 foreach($university_group4 as $key => $value) {
 	  $datars=array();
       $u_group_id=$value['u_group_id'];
       $datars['na']['group_id']=$u_group_id;
       $datars['na']['branch_name']=$value['branch_name'];
       $datars['na']['faculty_name']=$value['faculty_name'];
       $datars['na']['university_name']=$value['university_name'];
       $datars['na']['thumbnail']=$value['thumbnails'];
       $datars['na']['group_name']=$value['u_group_name'];
       $datars['na']['group_description']=$value['short_description'];
       $datars['na']['university_id']=$value['university_id'];
       $datars['na']['faculty_id']=$value['faculty_id'];
       $datars['na']['branch_id']=$value['branch_id'];
       $datars['na']['map_id']=$value['idx'];
       $datars['na']['u_group_type']=$value['u_group_type'];
       $datars['na']['major_remark']=$value['major_remark'];
       $datars['na']['year_config']=$value['year_config'];
       $datars['na']['groups_thumbnails']=$value['groups_thumbnails'];
       $datars['na']['record_status']=$value['record_status'];
 
 #################
 $datalist[]=$datars['na'];
}
#echo'<pre>datalist=>';print_r($datalist);echo'<pre>';  //die();
$university_group4=$university_group4['0'];
#echo'<pre>$university_group4=>';print_r($university_group4);echo'<pre>';  //die();
$u_group_id=$university_group4['u_group_id'];
$u_group_name=$university_group4['u_group_name'];
$short_description=$university_group4['short_description'];
#echo'<pre>u_group_id=>';print_r($u_group_id);echo'<pre>';  
$group_in_data=$this->model_trainer->get_group_type4_u_group_id_list($u_group_id,$delekey);
#echo'<pre> group_in_data=>';print_r($group_in_data);echo'<pre>';  die();
###########################################################################
###########################################################################
###########################################################################
#echo'<pre> $datalist=>';print_r($datalist);echo'<pre>';  die();

$group_in_data=$datalist;
$arr_result4 = array();
if (is_array($group_in_data)) {
	foreach ($group_in_data as $key4 => $val4) {
#################
$data4 = array();
$university_id=$val4['university_id'];
if($university_id==''){}else{
################
$branchid=$val4['branch_id'];
###########################################################################
###########################################################################

$amss=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branchid,$year,$delekey);
$ams=$amss['data'];
$ams_count=(int)count($ams);
if(!$ams){$group_ams_data_count=0;}
#################
$datasetamss = array();
if (is_array($ams)) {
foreach ($ams as $keydatas => $val) {
$dataset=array();
$ent_id=$val['ent_id'];
$datasetamss[]=$dataset['dd'];
}
}else{ $datasetamss=null; }
$group_ams_data_count=count($datasetamss);
###########################################################################
if($datasetamss==null){}else{
	$university_id=(int)$val4['university_id'];
	$data4['d']['university_id']=$university_id;
	$faculty_id=(int)$val4['faculty_id'];
	$data4['d']['faculty']['faculty_id']=$val4['faculty_id'];
	$data4['d']['faculty']['faculty_name']=$val4['faculty_name'];
     $data4['d']['year']=(int)$year;
     
#$branchdata2=$this->model_trainer->get_ams_university_u_parent_id_where($faculty_id,$delekey);
$branchdata=$this->model_trainer->get_ams_university_u_parent_id_where_u_group_id($faculty_id,$u_group_id_type4,$delekey);
     //$data4['d']['faculty']['branch_sql']=$branchdata['sql'];
     //echo 'branchdata=>';echo'<pre>';print_r($branchdata);echo'<pre>'; die();
	$branch_data=$branchdata['data'];
$arr_branch = array();
if (is_array($branch_data)){
foreach ($branch_data as $kb =>$vab){
     $brancharr = array();
	$branch_id=$vab['u_id'];
	$brancharr['dn']['branch_id']=$branch_id;
	$brancharr['dn']['university_name']=$val4['university_name'];
	$brancharr['dn']['faculty_name']=$val4['faculty_name'];
	$brancharr['dn']['branch_name']=$vab['u_name'];
     $brancharr['dn']['branch_major_count']=$ams_count;
#######################	

$amss_set=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_id_set_user_id($year,$branch_id,$user_id,$delekey);
$amss_set_data=$amss_set['data'];
if($amss_set_data==null){
$amss_entset=null;
}else{
$amss_entset=implode(',', array_map(function($amss_set_data){ return $amss_set_data['ent_id'];}, $amss_set_data ));
}

//$amss=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branch_id,$year,$delekey);
//$ams=$amss['data'];
if($amss_entset==null){
$instatus=0;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,0,$delekey);
}else{
$instatus=1;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,$amss_entset,$delekey);
}

$amsset=$amssets['data'];
$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keydatas => $val) {
$dataset=array();
$ent_id=$val['ent_id'];

############################
$branch_id=$val['branch_id'];
$faculty_id=0;

      $dataset['dd']['major_remark']=$val['major_remark'];
      $datasetamss[]=$dataset['dd'];
  }
}else{ $datasetamss=null;}
#######################	
if($tab=='directapply'){}elseif($tab==null ||$tab=='admission'){
    # $brancharr['dn']['admission_sql']=$amssets['sql'];
     #$brancharr['dn']['admission_set']=$amss_entset;
     #$brancharr['dn']['admission']=$datasetamss;
     $admission_count=(int)count($datasetamss);
}



#$brancharr['dn']['admission_count']=$admission_count;
$university_id=(int)$val4['university_id'];

if($tab==null ||$tab=='directapply'){
#################****directapply******###################
##############****directapply setting****#################

$directapplyset=$this->model_trainer->get_ams_entrance_user_year_user_id_directapply($branch_id,$year,$user_id,$delekey);
$directapplyset_data=$directapplyset['data'];
$directapplyset_data_count=(int)count($directapplyset_data);
$brancharr['dn']['directapply_set_count']=$directapplyset_data_count;
if($directapplyset_data_count==0){$directapplystatus=0;}else{$directapplystatus=1;}
$brancharr['dn']['directapply_active']=$directapplystatus;
$sumdirectapplyset=$this->model_trainer->get_ams_news_directapply_branch_id_year_sum($branch_id,$year,$delekey);
$brancharr['dn']['directapply_sum']=(int)$sumdirectapplyset['data']['0']['major_receivers_all'];
##############****directapply setting****##################
##################****directapply******###################
}

########################################################
########################################################
########################################################
########################################################
     if($admission_count>0){
      $arr_branch[]=$brancharr['dn'];
     }
     
     
     }
}
$branch_count=(int)count($arr_branch);
$data4['d']['faculty']['branch_count']=$branch_count;

$branch_set=implode(',', array_map(function($arr_branch){ return $arr_branch['branch_id'];}, $arr_branch ));
	
#$faculty_count=(int)count($group_in_data);
#$data4['d']['faculty_count']=$faculty_count;
     $data4['d']['faculty']['branch_set']=$branch_set;
	$data4['d']['faculty']['branch']=$arr_branch;
     $data4['d']['branch_count']=$branch_count;
	$data4['d']['group_type']=$val4['u_group_type'];
	$data4['d']['group_id']=$val4['group_id'];
	$data4['d']['university_name']=$val4['university_name'];
	$data4['d']['thumbnail']=$val4['thumbnail'];
	$data4['d']['group_name']=$val4['group_name'];
	$data4['d']['group_description']=$val4['group_description'];
	################ 
if($branch_count>0){ 

//if($directapplyset_data_count==0){
$arr_result4[] = $data4['d']; 
//}
 

}	
}
###########################################################################
}}#################
}else{ $arr_result4=null; }
$list=$arr_result4;
 
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];

$data=array('u_group_id'=>$u_group_id,
			'u_group_id_type3'=>$u_group_id_type3,
               'u_group_id_type4'=>$u_group_id_type4,
               'group_name'=>$u_group_name,
               'group_type3_name'=>$group_type3_name,
               'group_type3_thumbnail'=>$group_type3_thumbnail,
               'group_type4_name'=>$group_type4_name,
               'year'=>(int)$year,
	 		'list'=>$list,
               'list_count'=>count($list),
	 		'user_id'=>$user_id,);
if($data){
				$this->response(array('header'=>array(
										'title'=>'ams entrance group directapply',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'ams entrance group directapply',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
     
#############
}
##############TAB1####
#2560-03-30
public function universitylistgroup4listgroupidams_get(){
$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
$tab=@$this->input->get('tab');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$u_group_id=@$this->input->get('u_group_id');
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
######################################################
if($tab==''){ $tab=null; }
if($delekey==''){$delekey='';}else{$delekey='1';}
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
if($u_group_id_type3=='' ||$u_group_id_type4==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'u_group_id_type 3 or 4= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$member_id=@$this->tppy_member->get_member_profile()->member_id;
$this->load->model('admissiontrainer_model', 'model_trainer');
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
$universitygroup4=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);
$university_group4=$universitygroup4['0'];
$group_type4_name=$university_group4['u_group_name'];
$u_group_id=@$this->input->get('u_group_id_type4');
if($u_group_id<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$university_group=$this->model_trainer->get_ams_university_group_id($u_group_id,$delekey);
$university_group4=$university_group;
$sql1=$university_group['sql'];
$university_group4_count=count($university_group4);
if($university_group4_count<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>201), 
									  'data'=> Null),201);
Die();
}
$datalist=array();
 foreach($university_group4 as $key => $value) {
 	  $datars=array();
       $u_group_id=$value['u_group_id'];
       $datars['na']['group_id']=$u_group_id;
       $datars['na']['u_group_name']=$value['u_group_name'];
       $datars['na']['short_description']=$value['short_description'];
       $datars['na']['thumbnail']=$value['thumbnail'];
       $datars['na']['detail']=$value['detail'];
       $datars['na']['u_group_type']=$value['u_group_type'];
       $datars['na']['record_status']=$value['record_status'];
       $datars['na']['add_timestamp']=$value['add_timestamp'];
       $datars['na']['update_timestamp']=$value['update_timestamp'];
 #################
 $datalist[]=$datars['na'];
}
#echo'<pre>datalist=>';print_r($datalist);echo'<pre>';die();
$groupsid=(int)$datalist['0']['group_id'];
$universityname=$this->model_trainer->get_grouptype4_entrance_university_groupid_year($groupsid,$year,$delekey);
#echo'<pre> universityname=>';print_r($universityname);echo'<pre> <hr>'; die();
$universityname_data=$universityname['data'];
$university_set=implode(',', array_map(function($universityname_data){ return $universityname_data['university_id'];}, $universityname_data ));
$universityname_sql=$universityname['sql'];
####################*****university*******#############################
$arr_result4 = array();
if (is_array($universityname_data)) {
  foreach ($universityname_data as $key4 => $val4) {
  $university_id=(int)$val4['university_id'];
  $data4['d']= array();
	$data4['d']['university_id']=$university_id;
     $data4['d']['u_group_id_type3']=$u_group_id_type3;
     $data4['d']['u_group_id_type4']=$u_group_id_type4;
     $data4['d']['university_parent_id']=(int)$val4['u_parent_id'];
     $university_name=$val4['university_name'];
	$data4['d']['university_name']=$val4['university_name'];
     $group_id=(int)$val4['u_group_id'];
     $data4['d']['group_id']=$group_id;
     $data4['d']['group_name']=$val4['u_group_name'];
     $data4['d']['faculty_id']=(int)$val4['faculty_id'];
     $data4['d']['faculty_name']=$val4['faculty_name'];
     $data4['d']['branch_name']=(int)$val4['ent_u_id'];
     $branch_name=$val4['branch_name'];
     $group_description=$val4['short_description'];
     $data4['d']['branch_name']=$branch_name;
     $data4['d']['group_description']=$group_description;
     
     
     $data4['d']['thumbnail']=$val4['thumbnail'];
     $data4['d']['idx']=(int)$val4['idx'];
     $data4['d']['year']=(int)$year;
####################*****faculty start*******#############################
$faculty_data=$this->model_trainer->get_ams_faculty_list($university_id,$delekey);
$faculty_set=implode(',', array_map(function($faculty_data){ return $faculty_data['faculty_id'];}, $faculty_data ));
//$data4['d']['faculty_set']=$faculty_set;
####################*****branch start*******#############################
$branchdata=$this->model_trainer->get_branch_in_ent_faculty_year($faculty_set,$year,$delekey);
$branchset=$branchdata['data'];
$branch_set=implode(',', array_map(function($branchset){ return $branchset['branch_id'];}, $branchset ));
//$data4['d']['branch_ent_set']=$branch_set;
####################*****branch end*******#############################
####################*****faculty end*******#############################

####################*****faculty amss start*******#############################
$faculty_amss=$this->model_trainer->get_groupmapyearbranch($group_id,$branch_set,$delekey);
$faculty_amss_data=$faculty_amss['data'];
$dataset_faculty_amss = array();
if (is_array($faculty_amss_data)) {
foreach ($faculty_amss_data as $keydatas => $val) {
  $arr['faculty']=array();
  $faculty_id=(int)$val['faculty_id'];
  $arr['faculty']['faculty_id']=$faculty_id;
  $faculty_name=$val['faculty_name'];
  $arr['faculty']['faculty_name']=$val['faculty_name'];
  $arr['faculty']['thumbnail']=$group_type3_thumbnail;
  $arr['faculty']['branch_name']=$val4['branch_name'];
  $arr['faculty']['short_description']=$val4['short_description'];
$binf=$this->model_trainer->get_ams_university_u_parent_id_where_u_group_id($faculty_id,$u_group_id_type4,$delekey);
$binfdata=$binf['data'];
$arr['faculty']['branch_count']=(int)count($binfdata);
  ####################*****faculty amss End*******#############################
  $branch_set=implode(',', array_map(function($binfdata){ return $binfdata['u_id'];}, $binfdata ));
  $arr['faculty']['branch_set']=$branch_set;
  ####################*****brancharr  End*******#############################
  $brancharr = array();
if (is_array($binfdata)) {
foreach ($binfdata as $kb => $vb) {
  $arr['branch']=array();
  $branch_id=(int)$vb['u_id'];
  $arr['branch']['branch_id']=$branch_id;
  $arr['branch']['branch_name']=$vb['u_name'];
  $arr['branch']['thumbnail']=$group_type3_thumbnail;
  $arr['branch']['university_name']=$university_name;
  $arr['branch']['faculty_name']=$faculty_name;
############################################################################
############################################################################
####################*****admission start*******###########################
#$amssuser_user=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_id_set_user_id($year,$branch_id,$user_id,$delekey);
$amssuser=$this->model_trainer->get_ams_entrance_group_year_branch_id_set_alls($year,$branch_id,$delekey);
$amss_set_data=$amssuser['data'];
if($amss_set_data==null){$amss_entset=null;
}else{
$amss_entset=implode(',', array_map(function($amss_set_data){ return $amss_set_data['ent_id'];}, $amss_set_data ));
}
$arr['branch']['admission_set_user']=$amss_entset;
if($amss_entset==null){
$instatus=0;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,0,$delekey);
}else{
$instatus=3;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,$amss_entset,$delekey);
}
$amsset=$amssets['data'];
###############################################
$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keyent => $varent) {
$dataset['ent']=array();
$ent_id=$varent['ent_id'];
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$branch_id,$user_id,0,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
$ent_id_user=$ams_entrance_user1['ent_id'];
if($user_id==''){$dataset['ent']['bookmark_user']=null;$bookmark_active=0;}else{$bookmark_active=$ams_entrance_user1;if($ent_id_user==$ent_id){$bookmark_active=1;}else{$bookmark_active=0;}}
$dataset['ent']['bookmark_active']=$bookmark_active;
$dataset['ent']['year_config']=$varent['year_config'];
$dataset['ent']['major_remark']=$varent['major_remark'];
$score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
$dataset['ent']['score_history']=$score_history;
$dataset['ent']['ent_id']=$varent['ent_id'];
/*
$dataset['ent']['01_onet_mi']=$varent['01_onet_mi'];
$dataset['ent']['01_onet_weight']=$varent['01_onet_weight'];
$dataset['ent']['02_onet_min']=$varent['02_onet_min'];
$dataset['ent']['02_onet_weight']=$varent['02_onet_weight'];
$dataset['ent']['03_onet_mint']=$varent['03_onet_mint'];
$dataset['ent']['03_onet_weight']=$varent['03_onet_weight'];
$dataset['ent']['04_onet_min']=$varent['04_onet_min'];
$dataset['ent']['04_onet_weight']=$varent['04_onet_weight'];
$dataset['ent']['05_onet_min']=$varent['05_onet_min'];
$dataset['ent']['05_onet_weight']=$varent['05_onet_weight'];
$dataset['ent']['71_pat1_min']=$varent['71_pat1_min'];
$dataset['ent']['71_pat1_weight']=$varent['71_pat1_weight'];
$dataset['ent']['72_pat2_min']=$varent['72_pat2_min'];
$dataset['ent']['72_pat2_weight']=$varent['72_pat2_weight'];
$dataset['ent']['73_pat3_min']=$varent['73_pat3_min'];
$dataset['ent']['73_pat3_weight']=$varent['73_pat3_weight'];
$dataset['ent']['74_pat4_min']=$varent['74_pat4_min'];
$dataset['ent']['74_pat4_weight']=$varent['74_pat4_weight'];
$dataset['ent']['75_pat5_min']=$varent['75_pat5_min'];
$dataset['ent']['75_pat5_weight']=$varent['75_pat5_weight'];
$dataset['ent']['76_pat6_min']=$varent['76_pat6_min'];
$dataset['ent']['76_pat6_weight']=$varent['76_pat6_weight'];
$dataset['ent']['77_pat71_min']=$varent['77_pat71_min'];
$dataset['ent']['77_pat71_weight']=$varent['77_pat71_weight'];
$dataset['ent']['78_pat72_min']=$varent['78_pat72_min'];
$dataset['ent']['78_pat72_weight']=$varent['78_pat72_weight'];
$dataset['ent']['79_pat73_min']=$varent['79_pat73_min'];
$dataset['ent']['79_pat73_weight']=$varent['79_pat73_weight'];
$dataset['ent']['80_pat74_min']=$varent['80_pat74_min'];
$dataset['ent']['80_pat74_weight']=$varent['80_pat74_weight'];
$dataset['ent']['81_pat75_min']=$varent['81_pat75_min'];
$dataset['ent']['81_pat75_weight']=$varent['81_pat75_weight'];
$dataset['ent']['82_pat76_min']=$varent['82_pat76_min'];
$dataset['ent']['82_pat76_weight']=$varent['82_pat76_weight'];
$dataset['ent']['85_gat_min']=$varent['85_gat_min'];
$dataset['ent']['85_gat_min_part2']=$varent['85_gat_min_part2'];
$dataset['ent']['85_gat_weight']=$varent['85_gat_weight'];
$dataset['ent']['branch_id']=$varent['branch_id'];
$dataset['ent']['branch_name']=$varent['branch_name'];
$dataset['ent']['config']=$varent['config'];
$dataset['ent']['university_name']=$varent['university_name'];
$dataset['ent']['faculty_id']=$varent['faculty_id'];
$dataset['ent']['faculty_name']=$varent['faculty_name'];
$dataset['ent']['gpax_min']=$varent['gpax_min'];
$dataset['ent']['gpax_weight']=$varent['gpax_weight'];
$dataset['ent']['major_code']=$varent['major_code'];
$dataset['ent']['major_remark']=$varent['major_remark'];
$dataset['ent']['onet_min_total']=$varent['onet_min_total'];
$dataset['ent']['onet_weight_total']=$varent['onet_weight_total'];
*/
      $dataset['ent']['gpax_min']=$varent['gpax_min'];
      $dataset['ent']['gpax_weight']=$varent['gpax_weight'];
 
      $d_01_onet_mi=$varent['01_onet_mi'];
      $d_01_onet_weight=$varent['01_onet_weight'];
      $d_02_onet_min=$varent['02_onet_min'];
      $d_02_onet_weight=$varent['02_onet_weight'];
      $d_03_onet_mint=$varent['03_onet_mint'];
      $d_03_onet_weight=$varent['03_onet_weight'];
      $d_04_onet_min=$varent['04_onet_min'];
      $d_04_onet_weight=$varent['04_onet_weight'];
      $d_05_onet_min=$varent['05_onet_min'];
      $d_05_onet_weight=$varent['05_onet_weight'];
      $d_71_pat1_min=$varent['71_pat1_min'];
      $d_71_pat1_weight=$varent['71_pat1_weight'];
      $d_72_pat2_min=$varent['72_pat2_min'];
      $d_72_pat2_weight=$varent['72_pat2_weight'];
      $d_73_pat3_min=$varent['73_pat3_min'];
      $d_73_pat3_weight=$varent['73_pat3_weight'];
      $d_74_pat4_min=$varent['74_pat4_min'];
      $d_74_pat4_weight=$varent['74_pat4_weight'];
      $d_75_pat5_min=$varent['75_pat5_min'];
      $d_75_pat5_weight=$varent['75_pat5_weight'];
      $d_76_pat6_min=$varent['76_pat6_min'];
      $d_76_pat6_weight=$varent['76_pat6_weight'];
      $d_77_pat71_min=$varent['77_pat71_min'];
      $d_77_pat71_weight=$varent['77_pat71_weight'];
      $d_78_pat72_min=$varent['78_pat72_min'];
      $d_78_pat72_weight=$varent['78_pat72_weight'];
      $d_79_pat73_min=$varent['79_pat73_min'];
      $d_79_pat73_weight=$varent['79_pat73_weight'];
      $d_80_pat74_min=$varent['80_pat74_min'];
      $d_80_pat74_weight=$varent['80_pat74_weight'];
      $d_81_pat75_min=$varent['81_pat75_min'];
      $d_81_pat75_weight=$varent['81_pat75_weight'];
      $d_82_pat76_min=$varent['82_pat76_min'];
      $d_82_pat76_weight=$varent['82_pat76_weight'];
      $d_85_gat_min=$varent['85_gat_min'];
      $d_85_gat_min_part2=$varent['85_gat_min_part2'];
      $d_85_gat_weight=$varent['85_gat_weight'];
      $d_onet_min_total=$varent['onet_min_total'];
      $d_onet_weight_total=$varent['onet_weight_total'];
      
      if($d_01_onet_mi>=1){
       $dataset['ent']['onet']['01_onet_mi']=$d_01_onet_mi;
      }if($d_01_onet_weight>=1){
      $dataset['ent']['onet']['01_onet_weight']=$d_01_onet_weight;
      }if($d_02_onet_min>=1){
      $dataset['ent']['onet']['02_onet_min']=$d_02_onet_min;
      }if($d_02_onet_weight>=1){
      $dataset['ent']['onet']['02_onet_weight']=$d_02_onet_weight;
       }if($d_03_onet_mint>=1){
      $dataset['ent']['onet']['03_onet_mint']=$d_03_onet_mint;
       }if($d_03_onet_weight>=1){
      $dataset['ent']['onet']['03_onet_weight']=$d_03_onet_weight;
       }if($d_04_onet_min>=1){
      $dataset['ent']['onet']['04_onet_min']=$d_04_onet_min;
       }if($d_04_onet_weight>=1){
      $dataset['ent']['onet']['04_onet_weight']=$d_04_onet_weight;
       }if($d_05_onet_min>=1){
      $dataset['ent']['onet']['05_onet_min']=$d_05_onet_min;
       }if($d_05_onet_weight>=1){
      $dataset['ent']['onet']['05_onet_weight']=$d_05_onet_weight;
       }if($d_onet_min_total>=1){
      $dataset['ent']['onet']['onet_min_total']=$d_onet_min_total;
       }if($d_onet_weight_total>=1){
      $dataset['ent']['onet']['onet_weight_total']=$d_onet_weight_total;
      }if($d_71_pat1_min>=1){
      $dataset['ent']['pat']['71_pat1_min']=$d_71_pat1_min;
      }if($d_71_pat1_weight>=1){
      $dataset['ent']['pat']['71_pat1_weight']=$d_71_pat1_weight;
      }if($d_72_pat2_min>=1){
      $dataset['ent']['pat']['72_pat2_min']=$d_72_pat2_min;
      }if($d_72_pat2_weight>=1){
      $dataset['ent']['pat']['72_pat2_weight']=$d_72_pat2_weight;
      }if($d_73_pat3_min>=1){
      $dataset['ent']['pat']['73_pat3_min']=$d_73_pat3_min;
      }if($d_73_pat3_weight>=1){
      $dataset['ent']['pat']['73_pat3_weight']=$d_73_pat3_weight;
      }if($d_74_pat4_min>=1){
      $dataset['ent']['pat']['74_pat4_min']=$d_74_pat4_min;
      }if($d_74_pat4_weight>=1){
      $dataset['ent']['pat']['74_pat4_weight']=$d_74_pat4_weight;
      }if($d_75_pat5_min>=1){
      $dataset['ent']['pat']['75_pat5_min']=$d_75_pat5_min;
      }if($d_75_pat5_weight>=1){
      $dataset['ent']['pat']['75_pat5_weight']=$d_75_pat5_weight;
      }if($d_76_pat6_min>=1){
      $dataset['ent']['pat']['76_pat6_min']=$d_76_pat6_min;
      }if($d_76_pat6_weight>=1){
      $dataset['ent']['pat']['76_pat6_weight']=$d_76_pat6_weight;
      }if($d_77_pat71_min>=1){
      $dataset['ent']['pat']['77_pat71_min']=$d_77_pat71_min;
      }if($d_77_pat71_weight>=1){
      $dataset['ent']['pat']['77_pat71_weight']=$d_77_pat71_weight;
      }if($d_78_pat72_min>=1){
      $dataset['ent']['pat']['78_pat72_min']=$d_78_pat72_min;
      }if($d_78_pat72_weight>=1){
      $dataset['ent']['pat']['78_pat72_weight']=$d_78_pat72_weight;
      }if($d_79_pat73_min>=1){
      $dataset['ent']['pat']['79_pat73_min']=$d_79_pat73_min;
      }if($d_79_pat73_weight>=1){
      $dataset['ent']['pat']['79_pat73_weight']=$d_79_pat73_weight;
      }if($d_80_pat74_min>=1){
      $dataset['ent']['pat']['80_pat74_min']=$d_80_pat74_min;
      }if($d_80_pat74_weight>=1){
      $dataset['ent']['pat']['80_pat74_weight']=$d_80_pat74_weight;
      }if($d_81_pat75_min>=1){
      $dataset['ent']['pat']['81_pat75_min']=$d_81_pat75_min;
      }if($d_81_pat75_weight>=1){
      $dataset['ent']['pat']['81_pat75_weight']=$d_81_pat75_weight;
      }if($d_82_pat76_min>=1){
      $dataset['ent']['pat']['82_pat76_min']=$d_82_pat76_min;
      }if($d_82_pat76_weight>=1){
      $dataset['ent']['pat']['82_pat76_weight']=$d_82_pat76_weight;
      }if($d_85_gat_min>=1){
      $dataset['ent']['gat']['85_gat_min']=$d_85_gat_min;
      }if($d_85_gat_min_part2>=1){
      $dataset['ent']['gat']['85_gat_min_part2']=$d_85_gat_min_part2;
      }if($d_85_gat_weight>=1){
      $dataset['ent']['gat']['85_gat_weight']=$d_85_gat_weight;
      }


$sharecode=$varent['receive_amount_sharecode'];
$dataset['ent']['receive_amount_sharecode']= $sharecode;
if($sharecode==''){
$dataset['ent']['sharecode_data']=null;
$dataset['ent']['receive_amount']=(int)$varent['receive_amount'];
$dataset['ent']['sharecode_detail']=null;
$dataset['ent']['sharecode_set']=null;
$dataset['ent']['sharecode_msg']=null;
}else{
$majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
$majorcodeshare_data=$majorcodeshare['data']['0'];
$receiveamount=(int)$majorcodeshare_data['receive_amount'];
$dataset['ent']['sharecode_data']=$majorcodeshare_data;
$dataset['ent']['receive_amount']=$receiveamount;
$sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
$sharecode_data=$sharecode['data'];
$dataset['ent']['detail']=$sharecode_data;
$sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
$dataset['ent']['sharecode_set']=$sharecode_data_set;
$faculty_name_all=$varent['faculty_name'];
$dataset['ent']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
}
$dataset['ent']['score_max']=$varent['score_max'];
$dataset['ent']['score_min']=$varent['score_min'];
 
$special_remark=$varent['special_remark'];
$search='--';$replace='';$string=$special_remark;
$special_remark=str_replace($search,$replace,$string);
$dataset['ent']['special_remark']=$special_remark;

$dataset['ent']['lastupdate']=$varent['lastupdate'];
$datasetamss[]=$dataset['ent'];
 }
}else{ $datasetamss=null;}
###############################################
$arr['branch']['admission']=$datasetamss;
$admission_count=(int)count($datasetamss);
$arr['branch']['admission_count']=$admission_count;
$arr['branch']['admission_set']=$amss_entset;

####################*****admission End*******#############################
############################################################################
############################################################################
if($tab=='directapply'||$tab==''){
####################*****directapply start*******###########################
##############****directapply setting****#################
$directapplyset=$this->model_trainer->get_ams_entrance_user_year_user_id_directapply($branch_id,$year,$user_id,$delekey);
$directapplyset_data=$directapplyset['data'];
$directapplyset_data_count=(int)count($directapplyset_data);
$arr['branch']['directapply_set_count']=$directapplyset_data_count;
if($directapplyset_data_count==0){$directapplystatus=0;}else{$directapplystatus=1;}
$arr['branch']['directapply_active']=$directapplystatus;
$sumdirectapplyset=$this->model_trainer->get_ams_news_directapply_branch_id_year_sum($branch_id,$year,$delekey);
$arr['branch']['directapply_sum']=(int)$sumdirectapplyset['data']['0']['major_receivers_all'];
##############****directapply setting****##################
####################*****directapply End*******#############################
}
$brancharr[]=$arr['branch'];
 }
}
$branch_count=(int)count($brancharr);
$arr['faculty']['branch_count']=$branch_count;


if($tab=='admission'){
     
 $arr['faculty']['branch']=$brancharr;
$brancharr_total=count($brancharr);
$adstotal = array();
foreach ($brancharr as $kc => $vc){
$a['count']= array();
$admission_count=(int)$vc['admission_count'];
$a['count']['admission_count']=$admission_count;
$adstotal[]=$a['count'];
}
$admission_total_data=implode(',', array_map(function($adstotal){ return $adstotal['admission_count'];},$adstotal ));
$arr['faculty']['admission_total_data']=$admission_total_data;

}elseif($tab!==''){
$arr['faculty']['branch']=$brancharr;
/*
$brancharr_total=count($brancharr);
$adstotal = array();
foreach ($brancharr as $kc => $vc){
$a['count']= array();
$admission_count=(int)$vc['admission_count'];
$a['count']['admission_count']=$admission_count;
$adstotal[]=$a['count'];
}
$admission_total_data=implode(',', array_map(function($adstotal){ return $adstotal['admission_count'];},$adstotal ));
$arr['faculty']['admission_total_data']=$admission_total_data;
*/
}
 
 

  ####################*****brancharr End*******#############################
  
  if($branch_count>0){
       $dataset_faculty_amss[]=$arr['faculty'];
  }
  
  
  
 }
}


#######################
$faculty_count=(int)count($dataset_faculty_amss);
#if($faculty_count>0 ||$branch_count>0){}
if($branch_count>0){
#############branch_count##########
$dataset_faculty_total=count($dataset_faculty_amss);
$total=0;
foreach($dataset_faculty_amss as $value){
$branchtotal = $total + $value['branch_count'];
}
$data4['d']['branch_total']=(int)$branchtotal;
$data4['d']['faculty']=$dataset_faculty_amss;

#############branch_count##########
####################*****university*******#############################
/*
if($tab=='admission'){
     if($admission_count>0){$arr_result4[]=$data4['d'];}
}elseif($tab!=='admission'){
     if($admission_count==0){}else{$arr_result4[]=$data4['d'];}
}
*/
$arr_result4[]=$data4['d'];
}
#######################
 }
}
######################################################################
$list=$arr_result4;
/*
function array_unique_multidimensional($input){
    $serialized = array_map('serialize', $input);
    $unique = array_unique($serialized);
    return array_intersect_key($input, $unique);
}
$list=array_unique_multidimensional($arr_result4);
*/


$faculty_setrs=$this->model_trainer->get_faculty_set_university_id($university_set,$delekey);
$faculty_set=implode(',', array_map(function($faculty_setrs){ return $faculty_setrs['faculty_id'];}, $faculty_setrs ));
$entsetrs=$this->model_trainer->get_branch_set_university_id_group_maps($faculty_set,$year,$u_group_id_type4,1,$delekey);
$entcountdata=$entsetrs['data_count'];
$direct_apply=$this->model_trainer->get_branch_set_university_id_group_maps($faculty_set,$year,$u_group_id_type4,2,$delekey);
$direct_apply_count=$direct_apply['data_count'];
$data=array('u_group_id'=>$u_group_id,
			'u_group_id_type3'=>$u_group_id_type3,
               'u_group_id_type4'=>$u_group_id_type4,
               'group_name'=>$u_group_name,
               'group_type3_name'=>$group_type3_name,
               'group_type3_thumbnail'=>$group_type3_thumbnail,
               'group_type4_name'=>$group_type4_name,
               'year'=>(int)$year,
	 		'list'=>$list,
               #'university_set'=>$university_set,
               #'faculty_set'=>$faculty_set,
               'ent_count'=>(int)$entcountdata,
               'direct_apply_count'=>(int)$direct_apply_count,
               #'university_sql'=>$universityname_sql,
               
               'list_count'=>count($list),
	 		'user_id'=>$user_id,);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
     
#############
}
public function universitylistgroup4listgroupidamsdirectapply_get(){
$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
$tab=@$this->input->get('tab');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$u_group_id=@$this->input->get('u_group_id');
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
######################################################
if($tab==''){ $tab=null; }
if($delekey==''){$delekey='';}else{$delekey='1';}
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

$member_id=@$this->tppy_member->get_member_profile()->member_id;
$this->load->model('admissiontrainer_model', 'model_trainer');
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
$universitygroup4=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);
$university_group4=$universitygroup4['0'];
$group_type4_name=$university_group4['u_group_name'];
$u_group_id=@$this->input->get('u_group_id_type4');
if($u_group_id<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$university_group=$this->model_trainer->get_ams_university_group_id($u_group_id,$delekey);
$university_group4=$university_group;
$sql1=$university_group['sql'];
$university_group4_count=count($university_group4);
if($university_group4_count<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>201), 
									  'data'=> Null),201);
Die();
}
$datalist=array();
 foreach($university_group4 as $key => $value) {
 	  $datars=array();
       $u_group_id=$value['u_group_id'];
       $datars['na']['group_id']=$u_group_id;
       $datars['na']['u_group_name']=$value['u_group_name'];
       $datars['na']['short_description']=$value['short_description'];
       $datars['na']['thumbnail']=$value['thumbnail'];
       $datars['na']['detail']=$value['detail'];
       $datars['na']['u_group_type']=$value['u_group_type'];
       $datars['na']['record_status']=$value['record_status'];
       $datars['na']['add_timestamp']=$value['add_timestamp'];
       $datars['na']['update_timestamp']=$value['update_timestamp'];
 #################
 $datalist[]=$datars['na'];
}
#echo'<pre>datalist=>';print_r($datalist);echo'<pre>';die();
$groupsid=(int)$datalist['0']['group_id'];
$universityname=$this->model_trainer->get_grouptype4_entrance_university_groupid_year($groupsid,$year,$delekey);
#echo'<pre> universityname=>';print_r($universityname);echo'<pre> <hr>'; die();
$universityname_data=$universityname['data'];
$universityname_sql=$universityname['sql'];
####################*****university*******#############################
$arr_result4 = array();
if (is_array($universityname_data)) {
  foreach ($universityname_data as $key4 => $val4) {
  $university_id=(int)$val4['university_id'];
  $data4['d']= array();
	$data4['d']['university_id']=$university_id;
     $data4['d']['u_group_id_type3']=$u_group_id_type3;
     $data4['d']['u_group_id_type4']=$u_group_id_type4;
     $data4['d']['university_parent_id']=(int)$val4['u_parent_id'];
     $university_name=$val4['university_name'];
	$data4['d']['university_name']=$val4['university_name'];
     $group_id=(int)$val4['u_group_id'];
     $data4['d']['group_id']=$group_id;
     $data4['d']['group_name']=$val4['u_group_name'];
     $data4['d']['faculty_id']=(int)$val4['faculty_id'];
     $data4['d']['faculty_name']=$val4['faculty_name'];
     $data4['d']['branch_name']=(int)$val4['ent_u_id'];
     $branch_name=$val4['branch_name'];
     $group_description=$val4['short_description'];
     $data4['d']['branch_name']=$branch_name;
     $data4['d']['group_description']=$group_description;
     
     
     $data4['d']['thumbnail']=$val4['thumbnail'];
     $data4['d']['idx']=(int)$val4['idx'];
     $data4['d']['year']=(int)$year;
####################*****faculty start*******#############################
$faculty_data=$this->model_trainer->get_ams_faculty_list($university_id,$delekey);
$faculty_set=implode(',', array_map(function($faculty_data){ return $faculty_data['faculty_id'];}, $faculty_data ));
#$data4['d']['faculty_set']=$faculty_set;
####################*****branch start*******#############################
$branchdata=$this->model_trainer->get_branch_in_ent_faculty_year($faculty_set,$year,$delekey);
$branchset=$branchdata['data'];
$branch_set=implode(',', array_map(function($branchset){ return $branchset['branch_id'];}, $branchset ));
#$data4['d']['branch_ent_set']=$branch_set;
####################*****branch end*******#############################
####################*****faculty end*******#############################

####################*****faculty amss start*******#############################
$faculty_amss=$this->model_trainer->get_groupmapyearbranch($group_id,$branch_set,$delekey);
$faculty_amss_data=$faculty_amss['data'];
$dataset_faculty_amss = array();
if (is_array($faculty_amss_data)) {
foreach ($faculty_amss_data as $keydatas => $val) {
  $arr['faculty']=array();
  $faculty_id=(int)$val['faculty_id'];
  $arr['faculty']['faculty_id']=$faculty_id;
  $faculty_name=$val['faculty_name'];
  $arr['faculty']['faculty_name']=$val['faculty_name'];
  $arr['faculty']['thumbnail']=$group_type3_thumbnail;
  $arr['faculty']['branch_name']=$val4['branch_name'];
  $arr['faculty']['short_description']=$val4['short_description'];
$binf=$this->model_trainer->get_ams_university_u_parent_id_where_u_group_id($faculty_id,$u_group_id_type4,$delekey);
$binfdata=$binf['data'];
$arr['faculty']['branch_count']=(int)count($binfdata);
  ####################*****faculty amss End*******#############################
  $branch_set=implode(',', array_map(function($binfdata){ return $binfdata['u_id'];}, $binfdata ));
  $arr['faculty']['branch_set']=$branch_set;
  ####################*****brancharr  End*******#############################
  $brancharr = array();
if (is_array($binfdata)) {
foreach ($binfdata as $kb => $vb) {
  $arr['branch']=array();
  $branch_id=(int)$vb['u_id'];
  $arr['branch']['branch_id']=$branch_id;
  $arr['branch']['branch_name']=$vb['u_name'];
  $arr['branch']['thumbnail']=$group_type3_thumbnail;
  $arr['branch']['university_name']=$university_name;
  $arr['branch']['faculty_name']=$faculty_name;
############################################################################
####################*****directapply start*******###########################
##############****directapply setting****#################
$directapplyset=$this->model_trainer->get_ams_entrance_user_year_user_id_directapply($branch_id,$year,$user_id,$delekey);
$directapplyset_data=$directapplyset['data'];
$directapplyset_data_count=(int)count($directapplyset_data);
$arr['branch']['directapply_set_count']=$directapplyset_data_count;
if($directapplyset_data_count==0){$directapplystatus=0;}else{$directapplystatus=1;}
$arr['branch']['directapply_active']=$directapplystatus;
$sumdirectapplyset=$this->model_trainer->get_ams_news_directapply_branch_id_year_sum($branch_id,$year,$delekey);
$arr['branch']['directapply_sum']=(int)$sumdirectapplyset['data']['0']['major_receivers_all'];
##############****directapply setting****##################
####################*****directapply End*******#############################

  $brancharr[]=$arr['branch'];
 }
}
 $arr['faculty']['branch']=$brancharr;
 

 
  ####################*****brancharr End*******#############################
  $dataset_faculty_amss[]=$arr['faculty'];
 }
}
$data4['d']['faculty']=$dataset_faculty_amss;
#############branch_count##########
$dataset_faculty_total=count($dataset_faculty_amss);
$total=0;
foreach($dataset_faculty_amss as $value){
$branchtotal = $total + $value['branch_count'];
}
$data4['d']['branch_count']=(int)$branchtotal;
#############branch_count##########
####################*****university*******#############################
$arr_result4[]=$data4['d'];
 }
}
######################################################################
$list=$arr_result4;
/*
function array_unique_multidimensional($input){
    $serialized = array_map('serialize', $input);
    $unique = array_unique($serialized);
    return array_intersect_key($input, $unique);
}
$list=array_unique_multidimensional($arr_result4);
*/
$data=array('u_group_id'=>$u_group_id,
			'u_group_id_type3'=>$u_group_id_type3,
               'u_group_id_type4'=>$u_group_id_type4,
               'group_name'=>$u_group_name,
               'group_type3_name'=>$group_type3_name,
               'group_type3_thumbnail'=>$group_type3_thumbnail,
               'group_type4_name'=>$group_type4_name,
               'year'=>(int)$year,
	 		'list'=>$list,
               #'university_sql'=>$universityname_sql,
               
               'list_count'=>count($list),
	 		'user_id'=>$user_id,);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
     
#############
}
##############TAB1####
## searcbbytype34
####AMS TAB Branch START###
public function universitylistgroup34listgroupidams_get(){
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$member_id=@$this->tppy_member->get_member_profile()->member_id;
###########################################################
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
###########################################################
if($u_group_id_type3=='' && $u_group_id_type4==''){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
$tab=@$this->input->get('tab');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey='';}else{$delekey='1';}
$type34[0]['u_group_id']=(int)$u_group_id_type3;
$type34[1]['u_group_id']=(int)$u_group_id_type4;
#echo'<hr><pre> $type34=>';print_r($type34);echo'<pre> <hr>'; 
$ugroupid34=implode(',', array_map(function($type34){ return $type34['u_group_id'];}, $type34 ));
#echo'<hr><pre> $ugroupid34=>';print_r($ugroupid34);echo'<pre> <hr>'; 
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
#echo'<pre> $university_group3=>';print_r($university_group3);echo'<pre>';  die();
$university_group=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);

$grouparr=array();
if (is_array($university_group)){
foreach ($university_group as $kg =>$vg){
     $garr = array();
	$u_group_id=(int)$vg['u_group_id'];
	$garr['group']['group_id']=$u_group_id;
     $garr['group']['u_group_id_type3']=(int)$u_group_id_type3;
     $garr['group']['u_group_id_type4']=(int)$u_group_id_type4;
     $garr['group']['group_type3_name']=$university_group3['u_group_name'];
     $garr['group']['group_type3_thumbnail']=$university_group3['thumbnails'];
	$garr['group']['group_name']=$vg['u_group_name'];
     $u_group_type=(int)$vg['u_group_type'];
	$garr['group']['u_group_type']=$u_group_type;
     if($u_group_type==3){
      $thumbnail=$vg['thumbnails'];
$universitygroup=$this->model_trainer->get_group_type34_university_year_by_groupid_tab2($year,$u_group_id_type3,$delekey);
$university_group=$universitygroup['data'];
     }else{
      $thumbnail='http://www.trueplookpanya.com/assets/images/img-icon/fac_group.png';
$universitygroup=$this->model_trainer->get_group_type34_university_year_by_groupid_tab2($year,$u_group_id_type4,$delekey);
     $garr['group']['thumbnail']=$thumbnail;
$university_group=$universitygroup['data'];
     }	
#echo'<pre> $university_group=>';print_r($university_group);echo'<pre>';  die();
#############################
####*******university*****###
#############################
$university_arr=array();
if(is_array($university_group)){
foreach ($university_group as $key3 => $v3) {
$arr2=array();
$university_id=(int)$v3['university_id'];
     $arr2['university']['university_id']=$university_id;
     $university_name=$v3['university_name'];
	$arr2['university']['university_name']=$university_name;
	$arr2['university']['faculty_name']=$v3['faculty_name'];
     $arr2['university']['branch_name']=$v3['branch_name'];
     $arr2['university']['thumbnail']=$v3['thumbnail'];
     //$arr2['university']['group_name']=$v3['group_name'];
     $arr2['university']['university_parent_id']=$v3['university_parent_id'];
     $faculty_id=$v3['faculty_id'];
     $arr2['university']['faculty_id']=(int)$faculty_id;
     $arr2['university']['branch_id']=(int)$v3['branch_id'];
     $arr2['university']['map_id']=(int)$v3['map_id'];
     $arr2['university']['group_type']=(int)$v3['u_group_type'];
     $arr2['university']['year']=(int)$v3['year'];
############faculty#######################
     $faculty_array=$this->model_trainer->get_ams_faculty_id($faculty_id,$delekey);
      #echo'<pre> $faculty_array=>';print_r($faculty_array);echo'<pre> <hr>'; die();
     $facultyarray=array();
     if(is_array($faculty_array)){
     foreach ($faculty_array as $key4 => $v4) {
     $arr3=array();
          $faculty_id=(int)$v4['faculty_id'];
          $arr4['faculty']['faculty_id']=$faculty_id;
          $faculty_name=$v4['faculty_name'];
          $arr4['faculty']['faculty_name']=$faculty_name;
          $arr4['faculty']['faculty_thumbnail']=$thumbnail;
//$branchrs1=$this->model_trainer->get_ams_university_u_parent_id_where($faculty_id,$delekey);
$branchrs=$this->model_trainer->get_ams_university_map_group_type_u_parent_id_where($faculty_id,$year,$u_group_id_type4,$delekey);
//echo'<pre> $branchrs=>';print_r($branchrs);echo'<pre> <hr>'; //die();
$branch_data_master=$branchrs['data'];
$branch_set_master=implode(',', array_map(function($branch_data_master){ return $branch_data_master['u_id'];}, $branch_data_master ));
 #echo'<pre> $branch_set_master=>';print_r($branch_set_master);echo'<pre> <hr>'; die();
          $arr4['faculty']['branch_set']=$branch_set_master;
          #$arr4['faculty']['branch_sql']=$branchrs['sql'];
#$branchdataV1=$this->model_trainer->get_ams_entrance_university_group_branch_set_id_tab2($u_group_id_type4,$year,$branch_set_master,$delekey);
$branchdata=$this->model_trainer->get_ams_entrance_university_group_branch_set_id_tab2v2($u_group_id_type4,$year,$branch_set_master,$delekey);
$branch_data=$branchdata['data'];
/*
echo'u_group_id_type4=>'.$u_group_id_type4;
echo'<br> year=>'.$year;
echo'<br> branch_set_master=>'.$branch_set_master;
echo'<pre> $branch_data=>';print_r($branch_data);echo'<pre> <hr>'; die();
*/
#################***branch***#################
$arr_branch = array();
if (is_array($branch_data)){
foreach ($branch_data as $kb =>$vab){
$brancharr = array();
	$branch_id=$vab['u_id'];
	$brancharr['dn']['branch_id']=$branch_id;
	$brancharr['dn']['university_name']=$university_name;
	$brancharr['dn']['faculty_name']=$faculty_name;
	$brancharr['dn']['branch_name']=$vab['branch_name'];
     $brancharr['dn']['branch_thumbnail']=$thumbnail;
     $brancharr['dn']['u_group_id_type3']=(int)$u_group_id_type3;
     $brancharr['dn']['u_group_id_type4']=(int)$u_group_id_type4;
########################***admission***#################################
$amss_set=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_id_set_user_id($year,$branch_id,$user_id,$delekey);
$amss_set_data=$amss_set['data'];
#$brancharr['dn']['admission_sql1']=$amss_set['sql'];
if($amss_set_data==null){
$amss_entset=null;
}else{
$amss_entset=implode(',', array_map(function($amss_set_data){ return $amss_set_data['ent_id'];}, $amss_set_data ));
}


if($amss_entset==null){
$instatus=0;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,0,$delekey);
}else{
$instatus=3;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,$amss_entset,$delekey);
}
$amsset=$amssets['data'];
#$brancharr['dn']['admission_sql']=$amssets['sql'];
$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keydatas => $val) {
$dataset=array();
$ent_id=$val['ent_id'];

############################
$branch_id=$val['branch_id'];
$faculty_id=0;
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$branch_id,$user_id,0,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
$ent_id_user=$ams_entrance_user1['ent_id'];
if($user_id==''){
     $dataset['dd']['bookmark_user']=null;
     $bookmark_active=0;
}else{
     $bookmark_active=$ams_entrance_user1;
     if($ent_id_user==$ent_id){
       $bookmark_active=1;
     }else{
      $bookmark_active=0;
     }
}
//$dataset['dd']['bookmark_users']=$ams_entrance_users;
$dataset['dd']['bookmark_active']=$bookmark_active;
############################
      $dataset['dd']['u_group_id_type3']=(int)$u_group_id_type3;
      $dataset['dd']['u_group_id_type4']=(int)$u_group_id_type4;
      $dataset['dd']['year_config']=$val['year_config'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
      $dataset['dd']['score_history']=$score_history;
      $dataset['dd']['ent_id']=$val['ent_id'];
/*
      $dataset['dd']['01_onet_mi']=$val['01_onet_mi'];
      $dataset['dd']['01_onet_weight']=$val['01_onet_weight'];
      $dataset['dd']['02_onet_min']=$val['02_onet_min'];
      $dataset['dd']['02_onet_weight']=$val['02_onet_weight'];
      $dataset['dd']['03_onet_mint']=$val['03_onet_mint'];
      $dataset['dd']['03_onet_weight']=$val['03_onet_weight'];
      $dataset['dd']['04_onet_min']=$val['04_onet_min'];
      $dataset['dd']['04_onet_weight']=$val['04_onet_weight'];
      $dataset['dd']['05_onet_min']=$val['05_onet_min'];
      $dataset['dd']['05_onet_weight']=$val['05_onet_weight'];
      $dataset['dd']['71_pat1_min']=$val['71_pat1_min'];
      $dataset['dd']['71_pat1_weight']=$val['71_pat1_weight'];
      $dataset['dd']['72_pat2_min']=$val['72_pat2_min'];
      $dataset['dd']['72_pat2_weight']=$val['72_pat2_weight'];
      $dataset['dd']['73_pat3_min']=$val['73_pat3_min'];
      $dataset['dd']['73_pat3_weight']=$val['73_pat3_weight'];
      $dataset['dd']['74_pat4_min']=$val['74_pat4_min'];
      $dataset['dd']['74_pat4_weight']=$val['74_pat4_weight'];
      $dataset['dd']['75_pat5_min']=$val['75_pat5_min'];
      $dataset['dd']['75_pat5_weight']=$val['75_pat5_weight'];
      $dataset['dd']['76_pat6_min']=$val['76_pat6_min'];
      $dataset['dd']['76_pat6_weight']=$val['76_pat6_weight'];
      $dataset['dd']['77_pat71_min']=$val['77_pat71_min'];
      $dataset['dd']['77_pat71_weight']=$val['77_pat71_weight'];
      $dataset['dd']['78_pat72_min']=$val['78_pat72_min'];
      $dataset['dd']['78_pat72_weight']=$val['78_pat72_weight'];
      $dataset['dd']['79_pat73_min']=$val['79_pat73_min'];
      $dataset['dd']['79_pat73_weight']=$val['79_pat73_weight'];
      $dataset['dd']['80_pat74_min']=$val['80_pat74_min'];
      $dataset['dd']['80_pat74_weight']=$val['80_pat74_weight'];
      $dataset['dd']['81_pat75_min']=$val['81_pat75_min'];
      $dataset['dd']['81_pat75_weight']=$val['81_pat75_weight'];
      $dataset['dd']['82_pat76_min']=$val['82_pat76_min'];
      $dataset['dd']['82_pat76_weight']=$val['82_pat76_weight'];
      $dataset['dd']['85_gat_min']=$val['85_gat_min'];
      $dataset['dd']['85_gat_min_part2']=$val['85_gat_min_part2'];
      $dataset['dd']['85_gat_weight']=$val['85_gat_weight'];
      $dataset['dd']['branch_id']=$val['branch_id'];
      $dataset['dd']['branch_name']=$val['branch_name'];
      $dataset['dd']['config']=$val['config'];
      $dataset['dd']['university_name']=$val['university_name'];
      $dataset['dd']['faculty_id']=$val['faculty_id'];
      $dataset['dd']['faculty_name']=$val['faculty_name'];
      $dataset['dd']['gpax_min']=$val['gpax_min'];
      $dataset['dd']['gpax_weight']=$val['gpax_weight'];
      $dataset['dd']['major_code']=$val['major_code'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      $dataset['dd']['onet_min_total']=$val['onet_min_total'];
      $dataset['dd']['onet_weight_total']=$val['onet_weight_total'];
*/

      $dataset['dd']['gpax_min']=$val['gpax_min'];
      $dataset['dd']['gpax_weight']=$val['gpax_weight'];
 
      $d_01_onet_mi=$val['01_onet_mi'];
      $d_01_onet_weight=$val['01_onet_weight'];
      $d_02_onet_min=$val['02_onet_min'];
      $d_02_onet_weight=$val['02_onet_weight'];
      $d_03_onet_mint=$val['03_onet_mint'];
      $d_03_onet_weight=$val['03_onet_weight'];
      $d_04_onet_min=$val['04_onet_min'];
      $d_04_onet_weight=$val['04_onet_weight'];
      $d_05_onet_min=$val['05_onet_min'];
      $d_05_onet_weight=$val['05_onet_weight'];
      $d_71_pat1_min=$val['71_pat1_min'];
      $d_71_pat1_weight=$val['71_pat1_weight'];
      $d_72_pat2_min=$val['72_pat2_min'];
      $d_72_pat2_weight=$val['72_pat2_weight'];
      $d_73_pat3_min=$val['73_pat3_min'];
      $d_73_pat3_weight=$val['73_pat3_weight'];
      $d_74_pat4_min=$val['74_pat4_min'];
      $d_74_pat4_weight=$val['74_pat4_weight'];
      $d_75_pat5_min=$val['75_pat5_min'];
      $d_75_pat5_weight=$val['75_pat5_weight'];
      $d_76_pat6_min=$val['76_pat6_min'];
      $d_76_pat6_weight=$val['76_pat6_weight'];
      $d_77_pat71_min=$val['77_pat71_min'];
      $d_77_pat71_weight=$val['77_pat71_weight'];
      $d_78_pat72_min=$val['78_pat72_min'];
      $d_78_pat72_weight=$val['78_pat72_weight'];
      $d_79_pat73_min=$val['79_pat73_min'];
      $d_79_pat73_weight=$val['79_pat73_weight'];
      $d_80_pat74_min=$val['80_pat74_min'];
      $d_80_pat74_weight=$val['80_pat74_weight'];
      $d_81_pat75_min=$val['81_pat75_min'];
      $d_81_pat75_weight=$val['81_pat75_weight'];
      $d_82_pat76_min=$val['82_pat76_min'];
      $d_82_pat76_weight=$val['82_pat76_weight'];
      $d_85_gat_min=$val['85_gat_min'];
      $d_85_gat_min_part2=$val['85_gat_min_part2'];
      $d_85_gat_weight=$val['85_gat_weight'];
      $d_onet_min_total=$val['onet_min_total'];
      $d_onet_weight_total=$val['onet_weight_total'];
      
      if($d_01_onet_mi>=1){
       $dataset['dd']['onet']['01_onet_mi']=$d_01_onet_mi;
      }if($d_01_onet_weight>=1){
      $dataset['dd']['onet']['01_onet_weight']=$d_01_onet_weight;
      }if($d_02_onet_min>=1){
      $dataset['dd']['onet']['02_onet_min']=$d_02_onet_min;
      }if($d_02_onet_weight>=1){
      $dataset['dd']['onet']['02_onet_weight']=$d_02_onet_weight;
       }if($d_03_onet_mint>=1){
      $dataset['dd']['onet']['03_onet_mint']=$d_03_onet_mint;
       }if($d_03_onet_weight>=1){
      $dataset['dd']['onet']['03_onet_weight']=$d_03_onet_weight;
       }if($d_04_onet_min>=1){
      $dataset['dd']['onet']['04_onet_min']=$d_04_onet_min;
       }if($d_04_onet_weight>=1){
      $dataset['dd']['onet']['04_onet_weight']=$d_04_onet_weight;
       }if($d_05_onet_min>=1){
      $dataset['dd']['onet']['05_onet_min']=$d_05_onet_min;
       }if($d_05_onet_weight>=1){
      $dataset['dd']['onet']['05_onet_weight']=$d_05_onet_weight;
       }if($d_onet_min_total>=1){
      $dataset['dd']['onet']['onet_min_total']=$d_onet_min_total;
       }if($d_onet_weight_total>=1){
      $dataset['dd']['onet']['onet_weight_total']=$d_onet_weight_total;
      }if($d_71_pat1_min>=1){
      $dataset['dd']['pat']['71_pat1_min']=$d_71_pat1_min;
      }if($d_71_pat1_weight>=1){
      $dataset['dd']['pat']['71_pat1_weight']=$d_71_pat1_weight;
      }if($d_72_pat2_min>=1){
      $dataset['dd']['pat']['72_pat2_min']=$d_72_pat2_min;
      }if($d_72_pat2_weight>=1){
      $dataset['dd']['pat']['72_pat2_weight']=$d_72_pat2_weight;
      }if($d_73_pat3_min>=1){
      $dataset['dd']['pat']['73_pat3_min']=$d_73_pat3_min;
      }if($d_73_pat3_weight>=1){
      $dataset['dd']['pat']['73_pat3_weight']=$d_73_pat3_weight;
      }if($d_74_pat4_min>=1){
      $dataset['dd']['pat']['74_pat4_min']=$d_74_pat4_min;
      }if($d_74_pat4_weight>=1){
      $dataset['dd']['pat']['74_pat4_weight']=$d_74_pat4_weight;
      }if($d_75_pat5_min>=1){
      $dataset['dd']['pat']['75_pat5_min']=$d_75_pat5_min;
      }if($d_75_pat5_weight>=1){
      $dataset['dd']['pat']['75_pat5_weight']=$d_75_pat5_weight;
      }if($d_76_pat6_min>=1){
      $dataset['dd']['pat']['76_pat6_min']=$d_76_pat6_min;
      }if($d_76_pat6_weight>=1){
      $dataset['dd']['pat']['76_pat6_weight']=$d_76_pat6_weight;
      }if($d_77_pat71_min>=1){
      $dataset['dd']['pat']['77_pat71_min']=$d_77_pat71_min;
      }if($d_77_pat71_weight>=1){
      $dataset['dd']['pat']['77_pat71_weight']=$d_77_pat71_weight;
      }if($d_78_pat72_min>=1){
      $dataset['dd']['pat']['78_pat72_min']=$d_78_pat72_min;
      }if($d_78_pat72_weight>=1){
      $dataset['dd']['pat']['78_pat72_weight']=$d_78_pat72_weight;
      }if($d_79_pat73_min>=1){
      $dataset['dd']['pat']['79_pat73_min']=$d_79_pat73_min;
      }if($d_79_pat73_weight>=1){
      $dataset['dd']['pat']['79_pat73_weight']=$d_79_pat73_weight;
      }if($d_80_pat74_min>=1){
      $dataset['dd']['pat']['80_pat74_min']=$d_80_pat74_min;
      }if($d_80_pat74_weight>=1){
      $dataset['dd']['pat']['80_pat74_weight']=$d_80_pat74_weight;
      }if($d_81_pat75_min>=1){
      $dataset['dd']['pat']['81_pat75_min']=$d_81_pat75_min;
      }if($d_81_pat75_weight>=1){
      $dataset['dd']['pat']['81_pat75_weight']=$d_81_pat75_weight;
      }if($d_82_pat76_min>=1){
      $dataset['dd']['pat']['82_pat76_min']=$d_82_pat76_min;
      }if($d_82_pat76_weight>=1){
      $dataset['dd']['pat']['82_pat76_weight']=$d_82_pat76_weight;
      }if($d_85_gat_min>=1){
      $dataset['dd']['gat']['85_gat_min']=$d_85_gat_min;
      }if($d_85_gat_min_part2>=1){
      $dataset['dd']['gat']['85_gat_min_part2']=$d_85_gat_min_part2;
      }if($d_85_gat_weight>=1){
      $dataset['dd']['gat']['85_gat_weight']=$d_85_gat_weight;
      }
      $sharecode=$val['receive_amount_sharecode'];
      $dataset['dd']['receive_amount_sharecode']= $sharecode;
      if($sharecode==''){
        $dataset['dd']['sharecode_data']=null;
        $dataset['dd']['receive_amount']=(int)$val['receive_amount'];
        $dataset['dd']['sharecode_detail']=null;
        $dataset['dd']['sharecode_set']=null;
         $dataset['dd']['sharecode_msg']=null;
      }else{
       $majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
       $majorcodeshare_data=$majorcodeshare['data']['0'];
       $receiveamount=(int)$majorcodeshare_data['receive_amount'];
       $dataset['dd']['sharecode_data']=$majorcodeshare_data;
       $dataset['dd']['receive_amount']=$receiveamount;
       $sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
       $sharecode_data=$sharecode['data'];
       $dataset['dd']['detail']=$sharecode_data;
       $sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
       $dataset['dd']['sharecode_set']=$sharecode_data_set;
      $faculty_name_all=$val['faculty_name'];
       $dataset['dd']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
      }
      $dataset['dd']['score_max']=$val['score_max'];
      $dataset['dd']['score_min']=$val['score_min'];
      $special_remark=$val['special_remark'];
$search='--';$replace='';$string=$special_remark;
$special_remark=str_replace($search,$replace,$string);
      $dataset['dd']['special_remark']=$special_remark;
      $dataset['dd']['lastupdate']=$val['lastupdate'];
      $datasetamss[]=$dataset['dd'];
  }
}else{ $datasetamss='';}
$admission_count=(int)count($datasetamss);
$brancharr['dn']['admission_count']=$admission_count;
$brancharr['dn']['admission']=$datasetamss;
########################***admission***#################################
if($admission_count==0){$brancharr['dn']['admission_check']=0;}elseif($admission_count!==0){
     


##################****directapply******###################
##############****directapply setting****#################

$directapplyset=$this->model_trainer->get_ams_entrance_user_year_user_id_directapply($branch_id,$year,$user_id,$delekey);
$directapplyset_data=$directapplyset['data'];
$directapplyset_data_count=(int)count($directapplyset_data);
$brancharr['dn']['directapply_set_count']=$directapplyset_data_count;
if($directapplyset_data_count==0){$directapplystatus=0;}else{$directapplystatus=1;}
$brancharr['dn']['directapply_active']=$directapplystatus;
$sumdirectapplyset=$this->model_trainer->get_ams_news_directapply_branch_id_year_sum($branch_id,$year,$delekey);
$brancharr['dn']['directapply_sum']=(int)$sumdirectapplyset['data']['0']['major_receivers_all'];
##############****directapply setting****##################
##################****directapply******###################
 
#######################################################################################
     
$arr_branch[]=$brancharr['dn'];
                                                  }
   }
}

#echo'<pre> $arr_branch=>';print_r($arr_branch);echo'<pre>';  die();

$branch_count=(int)count($arr_branch);
 $arr4['faculty']['branch_count']=$branch_count;
#################***branch***#################
#if($branch_count==0){}elseif($branch_count!==0){
     $arr4['faculty']['branch']=$arr_branch;
     
#}
     $facultyarray[]=$arr4['faculty'];
      }
     }
############faculty#######################
     $arr2['university']['faculty']=$facultyarray;
    if($branch_count==0){}elseif($branch_count!==0){
     $university_arr[]=$arr2['university'];
    } 

 }
}

//echo'<pre> $university_arr=>';print_r($university_arr);echo'<pre>';  die();

$university_list=$university_arr;
$university_list_count=(int)count($university_arr);
#############################
####*******university*****###
#############################
$garr['group']['university']=$university_list;
$garr['group']['university_count']=$university_list_count;
/*
$university_set=implode(',', array_map(function($university_list){ return $university_list['university_id'];}, $university_list ));
$garr['group']['university_set']=$university_set;
$faculty_setrs=$this->model_trainer->get_faculty_set_university_id($university_set,$delekey);
$faculty_set=implode(',', array_map(function($faculty_setrs){ return $faculty_setrs['faculty_id'];}, $faculty_setrs ));
$garr['group']['faculty_set']=$faculty_set;
*/

if($university_list_count<=0){}else{
$grouparr[]=$garr['group'];	
}
 }
}
$group_type3_name=$group_type3name['0'];
$data=array('list'=>$grouparr,'list_count'=>count($grouparr),'university_count'=>$university_list_count,'user_id'=>$user_id,'year'=>$year);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
     
#############
}
public function universitylistgroup34listgroupidamstop_get(){
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group top',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$member_id=@$this->tppy_member->get_member_profile()->member_id;
###########################################################
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
###########################################################
$delekey=$this->input->get('delekey');
$tab=@$this->input->get('tab');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey='';}else{$delekey='1';}
$top_university_group=$this->model_trainer->get_top_ams_entrance_user_group_map($year,$user_id,$delekey);
#echo'<hr><pre> top_university_group=>';print_r($top_university_group);echo'<pre> <hr>';die();
$top_university_group_data=$top_university_group['data'];
$u_group_id_type3=(int)$top_university_group_data['0']['u_group_id_type3'];
$u_group_id_type4=(int)$top_university_group_data['0']['u_group_id_type4'];


$top_university_group_sql=$top_university_group['sql'];
############top_university_group#######################
#echo'<hr><pre> $top_university_group_data=>';print_r($top_university_group_data);echo'<pre> <hr>';die();
$grouptype4array=array();
if(is_array($top_university_group_data)){
foreach ($top_university_group_data as $keysss=>$va1) {
$arrgt4=array();
 
     $u_group_id_type3=(int)$va1['u_group_id_type3'];
     $u_group_id_type4=(int)$va1['u_group_id_type4'];
     
     $branch_id=(int)$va1['branch_id'];
     $arrgt4['gp']['u_group_id_type3']=$u_group_id_type3;
     $arrgt4['gp']['u_group_id_type4']=$u_group_id_type4;
     $arrgt4['gp']['group_type3_name']=$va1['group_type3_name'];
     $arrgt4['gp']['group_type4_name']=$va1['group_type4_name'];
     $thumbnail=$va1['thumbnail'];
     $arrgt4['gp']['thumbnail']=$thumbnail;
     $arrgt4['gp']['short_description']=$va1['short_description'];
     $arrgt4['gp']['u_group_type']=$va1['u_group_type'];
     $arrgt4['gp']['user_id']=(int)$va1['user_id'];
     $arrgt4['gp']['year']=(int)$va1['year'];
     $arrgt4['gp']['ent_id']=(int)$va1['ent_id'];
     $arrgt4['gp']['directapply_faculty_id']=(int)$va1['directapply_faculty_id'];
     $arrgt4['gp']['u_id']=(int)$va1['u_id'];
###############################################################

#############################
####*******university*****###
#############################

$branchgroup=$this->model_trainer->get_top_branch_user_group_id_type4($year,$user_id,$u_group_id_type4,$delekey);
$branchgroup_set=implode(',', array_map(function($branchgroup){ return $branchgroup['branch_id'];}, $branchgroup ));
$arrgt4['gp']['branchgroup_set']=$branchgroup_set;
#$universitygroup=$this->model_trainer->get_group_type34_university_year_by_groupid_user_branch_id($year,$u_group_id_type4,$branch_id,$delekey);
$universitygroup=$this->model_trainer->get_group_type34_university_year_by_groupid_user_branch_id_in($year,$u_group_id_type4,$branchgroup_set,$delekey);
#echo'<hr><pre> $universitygroup=>';print_r($universitygroup);echo'<pre> <hr>';die();
$university_group=$universitygroup['data'];
$university_group_sql=$universitygroup['sql'];
#$arrgt4['gp']['university_group_sql']=$university_group_sql;
$university_arr=array();
if(is_array($university_group)){
foreach ($university_group as $key3 => $v3) {
$arr2=array(); 
$university_id=(int)$v3['university_id'];
     $arr2['university']['university_id']=$university_id;
     $university_name=$v3['university_name'];
	$arr2['university']['university_name']=$university_name;
	$arr2['university']['faculty_name']=$v3['faculty_name'];
     $arr2['university']['branch_name']=$v3['branch_name'];
     $arr2['university']['thumbnail']=$v3['thumbnail'];
     //$arr2['university']['group_name']=$v3['group_name'];
     $arr2['university']['university_parent_id']=$v3['university_parent_id'];
     $faculty_id=$v3['faculty_id'];
     $arr2['university']['faculty_id']=(int)$faculty_id;
     $arr2['university']['branch_id']=(int)$v3['branch_id'];
     $arr2['university']['map_id']=(int)$v3['map_id'];
     $arr2['university']['group_type']=(int)$v3['u_group_type'];
     $arr2['university']['year']=(int)$v3['year'];
############faculty#######################
     $faculty_array=$this->model_trainer->get_ams_faculty_id($faculty_id,$delekey);
     $facultyarray=array();
     if(is_array($faculty_array)){
     foreach ($faculty_array as $key4 => $v4) {
     $arr3=array();
          $faculty_id=(int)$v4['faculty_id'];
          $arr4['faculty']['faculty_id']=$faculty_id;
          $faculty_name=$v4['faculty_name'];
          $arr4['faculty']['faculty_name']=$faculty_name;
          $arr4['faculty']['faculty_thumbnail']=$thumbnail;
//$branchrs1=$this->model_trainer->get_ams_university_u_parent_id_where($faculty_id,$delekey);
$branchrs=$this->model_trainer->get_ams_university_map_group_type_u_parent_id_where($faculty_id,$year,$u_group_id_type4,$delekey);
          $branch_data=$branchrs['data'];
          $branch_set=implode(',', array_map(function($branch_data){ return $branch_data['u_id'];}, $branch_data ));
          $arr4['faculty']['branch_set']=$branch_set;
          //$arr4['faculty']['branch_sql']=$branchrs['sql'];
#################***branch***#################
$arr_branch = array();
if (is_array($branch_data)){
foreach ($branch_data as $kb =>$vab){
$brancharr = array();
	$branch_id=(int)$vab['u_id'];
	$brancharr['dn']['branch_id']=$branch_id;
$branch_userdata=$this->model_trainer->get_ams_entrance_u_master_id_year_user_id($branch_id,$year,$user_id,$delekey);
$branchudata=$branch_userdata['data'];
$branchusql=$branch_userdata['sql'];
#$brancharr['dn']['branch_user_data_sql']=$branchusql;
	$brancharr['dn']['university_name']=$university_name;
	$brancharr['dn']['faculty_name']=$faculty_name;
	$brancharr['dn']['branch_name']=$vab['u_name'];
     $brancharr['dn']['branch_thumbnail']=$thumbnail;
     $u_group_id_type3=(int)$u_group_id_type3;
     $u_group_id_type4=(int)$u_group_id_type4;
     $brancharr['dn']['u_group_id_type3']=$u_group_id_type3;
     $brancharr['dn']['u_group_id_type4']=$u_group_id_type4;
     
     
     
     
#######################################################################################
###################################admission#######################################
####################*****admission start*******###########################
$instatus=2;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,$amss_entset,$delekey);
$amsset=$amssets['data'];
###############################################
$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keyent => $varent) {
$dataset['ent']=array();
$ent_id=$varent['ent_id'];
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$branch_id,$user_id,0,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
$ent_id_user=$ams_entrance_user1['ent_id'];
if($user_id==''){$dataset['ent']['bookmark_user']=null;$bookmark_active=0;}else{$bookmark_active=$ams_entrance_user1;if($ent_id_user==$ent_id){$bookmark_active=1;}else{$bookmark_active=0;}}
$dataset['ent']['bookmark_active']=$bookmark_active;
$dataset['ent']['year_config']=$varent['year_config'];
$dataset['ent']['major_remark']=$varent['major_remark'];
$score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
$dataset['ent']['score_history']=$score_history;
$dataset['ent']['ent_id']=$varent['ent_id'];
/*
$dataset['ent']['01_onet_mi']=$varent['01_onet_mi'];
$dataset['ent']['01_onet_weight']=$varent['01_onet_weight'];
$dataset['ent']['02_onet_min']=$varent['02_onet_min'];
$dataset['ent']['02_onet_weight']=$varent['02_onet_weight'];
$dataset['ent']['03_onet_mint']=$varent['03_onet_mint'];
$dataset['ent']['03_onet_weight']=$varent['03_onet_weight'];
$dataset['ent']['04_onet_min']=$varent['04_onet_min'];
$dataset['ent']['04_onet_weight']=$varent['04_onet_weight'];
$dataset['ent']['05_onet_min']=$varent['05_onet_min'];
$dataset['ent']['05_onet_weight']=$varent['05_onet_weight'];
$dataset['ent']['71_pat1_min']=$varent['71_pat1_min'];
$dataset['ent']['71_pat1_weight']=$varent['71_pat1_weight'];
$dataset['ent']['72_pat2_min']=$varent['72_pat2_min'];
$dataset['ent']['72_pat2_weight']=$varent['72_pat2_weight'];
$dataset['ent']['73_pat3_min']=$varent['73_pat3_min'];
$dataset['ent']['73_pat3_weight']=$varent['73_pat3_weight'];
$dataset['ent']['74_pat4_min']=$varent['74_pat4_min'];
$dataset['ent']['74_pat4_weight']=$varent['74_pat4_weight'];
$dataset['ent']['75_pat5_min']=$varent['75_pat5_min'];
$dataset['ent']['75_pat5_weight']=$varent['75_pat5_weight'];
$dataset['ent']['76_pat6_min']=$varent['76_pat6_min'];
$dataset['ent']['76_pat6_weight']=$varent['76_pat6_weight'];
$dataset['ent']['77_pat71_min']=$varent['77_pat71_min'];
$dataset['ent']['77_pat71_weight']=$varent['77_pat71_weight'];
$dataset['ent']['78_pat72_min']=$varent['78_pat72_min'];
$dataset['ent']['78_pat72_weight']=$varent['78_pat72_weight'];
$dataset['ent']['79_pat73_min']=$varent['79_pat73_min'];
$dataset['ent']['79_pat73_weight']=$varent['79_pat73_weight'];
$dataset['ent']['80_pat74_min']=$varent['80_pat74_min'];
$dataset['ent']['80_pat74_weight']=$varent['80_pat74_weight'];
$dataset['ent']['81_pat75_min']=$varent['81_pat75_min'];
$dataset['ent']['81_pat75_weight']=$varent['81_pat75_weight'];
$dataset['ent']['82_pat76_min']=$varent['82_pat76_min'];
$dataset['ent']['82_pat76_weight']=$varent['82_pat76_weight'];
$dataset['ent']['85_gat_min']=$varent['85_gat_min'];
$dataset['ent']['85_gat_min_part2']=$varent['85_gat_min_part2'];
$dataset['ent']['85_gat_weight']=$varent['85_gat_weight'];
*/
      $dataset['ent']['gpax_min']=$varent['gpax_min'];
      $dataset['ent']['gpax_weight']=$varent['gpax_weight'];
 
      $d_01_onet_mi=$varent['01_onet_mi'];
      $d_01_onet_weight=$varent['01_onet_weight'];
      $d_02_onet_min=$varent['02_onet_min'];
      $d_02_onet_weight=$varent['02_onet_weight'];
      $d_03_onet_mint=$varent['03_onet_mint'];
      $d_03_onet_weight=$varent['03_onet_weight'];
      $d_04_onet_min=$varent['04_onet_min'];
      $d_04_onet_weight=$varent['04_onet_weight'];
      $d_05_onet_min=$varent['05_onet_min'];
      $d_05_onet_weight=$varent['05_onet_weight'];
      $d_71_pat1_min=$varent['71_pat1_min'];
      $d_71_pat1_weight=$varent['71_pat1_weight'];
      $d_72_pat2_min=$varent['72_pat2_min'];
      $d_72_pat2_weight=$varent['72_pat2_weight'];
      $d_73_pat3_min=$varent['73_pat3_min'];
      $d_73_pat3_weight=$varent['73_pat3_weight'];
      $d_74_pat4_min=$varent['74_pat4_min'];
      $d_74_pat4_weight=$varent['74_pat4_weight'];
      $d_75_pat5_min=$varent['75_pat5_min'];
      $d_75_pat5_weight=$varent['75_pat5_weight'];
      $d_76_pat6_min=$varent['76_pat6_min'];
      $d_76_pat6_weight=$varent['76_pat6_weight'];
      $d_77_pat71_min=$varent['77_pat71_min'];
      $d_77_pat71_weight=$varent['77_pat71_weight'];
      $d_78_pat72_min=$varent['78_pat72_min'];
      $d_78_pat72_weight=$varent['78_pat72_weight'];
      $d_79_pat73_min=$varent['79_pat73_min'];
      $d_79_pat73_weight=$varent['79_pat73_weight'];
      $d_80_pat74_min=$varent['80_pat74_min'];
      $d_80_pat74_weight=$varent['80_pat74_weight'];
      $d_81_pat75_min=$varent['81_pat75_min'];
      $d_81_pat75_weight=$varent['81_pat75_weight'];
      $d_82_pat76_min=$varent['82_pat76_min'];
      $d_82_pat76_weight=$varent['82_pat76_weight'];
      $d_85_gat_min=$varent['85_gat_min'];
      $d_85_gat_min_part2=$varent['85_gat_min_part2'];
      $d_85_gat_weight=$varent['85_gat_weight'];
      $d_onet_min_total=$varent['onet_min_total'];
      $d_onet_weight_total=$varent['onet_weight_total'];
      
      if($d_01_onet_mi>=1){
       $dataset['ent']['onet']['01_onet_mi']=$d_01_onet_mi;
      }if($d_01_onet_weight>=1){
      $dataset['ent']['onet']['01_onet_weight']=$d_01_onet_weight;
      }if($d_02_onet_min>=1){
      $dataset['ent']['onet']['02_onet_min']=$d_02_onet_min;
      }if($d_02_onet_weight>=1){
      $dataset['ent']['onet']['02_onet_weight']=$d_02_onet_weight;
       }if($d_03_onet_mint>=1){
      $dataset['ent']['onet']['03_onet_mint']=$d_03_onet_mint;
       }if($d_03_onet_weight>=1){
      $dataset['ent']['onet']['03_onet_weight']=$d_03_onet_weight;
       }if($d_04_onet_min>=1){
      $dataset['ent']['onet']['04_onet_min']=$d_04_onet_min;
       }if($d_04_onet_weight>=1){
      $dataset['ent']['onet']['04_onet_weight']=$d_04_onet_weight;
       }if($d_05_onet_min>=1){
      $dataset['ent']['onet']['05_onet_min']=$d_05_onet_min;
       }if($d_05_onet_weight>=1){
      $dataset['ent']['onet']['05_onet_weight']=$d_05_onet_weight;
       }if($d_onet_min_total>=1){
      $dataset['ent']['onet']['onet_min_total']=$d_onet_min_total;
       }if($d_onet_weight_total>=1){
      $dataset['ent']['onet']['onet_weight_total']=$d_onet_weight_total;
      }if($d_71_pat1_min>=1){
      $dataset['ent']['pat']['71_pat1_min']=$d_71_pat1_min;
      }if($d_71_pat1_weight>=1){
      $dataset['ent']['pat']['71_pat1_weight']=$d_71_pat1_weight;
      }if($d_72_pat2_min>=1){
      $dataset['ent']['pat']['72_pat2_min']=$d_72_pat2_min;
      }if($d_72_pat2_weight>=1){
      $dataset['ent']['pat']['72_pat2_weight']=$d_72_pat2_weight;
      }if($d_73_pat3_min>=1){
      $dataset['ent']['pat']['73_pat3_min']=$d_73_pat3_min;
      }if($d_73_pat3_weight>=1){
      $dataset['ent']['pat']['73_pat3_weight']=$d_73_pat3_weight;
      }if($d_74_pat4_min>=1){
      $dataset['ent']['pat']['74_pat4_min']=$d_74_pat4_min;
      }if($d_74_pat4_weight>=1){
      $dataset['ent']['pat']['74_pat4_weight']=$d_74_pat4_weight;
      }if($d_75_pat5_min>=1){
      $dataset['ent']['pat']['75_pat5_min']=$d_75_pat5_min;
      }if($d_75_pat5_weight>=1){
      $dataset['ent']['pat']['75_pat5_weight']=$d_75_pat5_weight;
      }if($d_76_pat6_min>=1){
      $dataset['ent']['pat']['76_pat6_min']=$d_76_pat6_min;
      }if($d_76_pat6_weight>=1){
      $dataset['ent']['pat']['76_pat6_weight']=$d_76_pat6_weight;
      }if($d_77_pat71_min>=1){
      $dataset['ent']['pat']['77_pat71_min']=$d_77_pat71_min;
      }if($d_77_pat71_weight>=1){
      $dataset['ent']['pat']['77_pat71_weight']=$d_77_pat71_weight;
      }if($d_78_pat72_min>=1){
      $dataset['ent']['pat']['78_pat72_min']=$d_78_pat72_min;
      }if($d_78_pat72_weight>=1){
      $dataset['ent']['pat']['78_pat72_weight']=$d_78_pat72_weight;
      }if($d_79_pat73_min>=1){
      $dataset['ent']['pat']['79_pat73_min']=$d_79_pat73_min;
      }if($d_79_pat73_weight>=1){
      $dataset['ent']['pat']['79_pat73_weight']=$d_79_pat73_weight;
      }if($d_80_pat74_min>=1){
      $dataset['ent']['pat']['80_pat74_min']=$d_80_pat74_min;
      }if($d_80_pat74_weight>=1){
      $dataset['ent']['pat']['80_pat74_weight']=$d_80_pat74_weight;
      }if($d_81_pat75_min>=1){
      $dataset['ent']['pat']['81_pat75_min']=$d_81_pat75_min;
      }if($d_81_pat75_weight>=1){
      $dataset['ent']['pat']['81_pat75_weight']=$d_81_pat75_weight;
      }if($d_82_pat76_min>=1){
      $dataset['ent']['pat']['82_pat76_min']=$d_82_pat76_min;
      }if($d_82_pat76_weight>=1){
      $dataset['ent']['pat']['82_pat76_weight']=$d_82_pat76_weight;
      }if($d_85_gat_min>=1){
      $dataset['ent']['gat']['85_gat_min']=$d_85_gat_min;
      }if($d_85_gat_min_part2>=1){
      $dataset['ent']['gat']['85_gat_min_part2']=$d_85_gat_min_part2;
      }if($d_85_gat_weight>=1){
      $dataset['ent']['gat']['85_gat_weight']=$d_85_gat_weight;
      }

$dataset['ent']['branch_id']=$varent['branch_id'];
$dataset['ent']['branch_name']=$varent['branch_name'];
$dataset['ent']['config']=$varent['config'];
$dataset['ent']['university_name']=$varent['university_name'];
$dataset['ent']['faculty_id']=$varent['faculty_id'];
$dataset['ent']['faculty_name']=$varent['faculty_name'];
$dataset['ent']['gpax_min']=$varent['gpax_min'];
$dataset['ent']['gpax_weight']=$varent['gpax_weight'];
$dataset['ent']['major_code']=$varent['major_code'];
$dataset['ent']['major_remark']=$varent['major_remark'];

$sharecode=$varent['receive_amount_sharecode'];
$dataset['ent']['receive_amount_sharecode']= $sharecode;
if($sharecode==''){
$dataset['ent']['sharecode_data']=null;
$dataset['ent']['receive_amount']=(int)$varent['receive_amount'];
$dataset['ent']['sharecode_detail']=null;
$dataset['ent']['sharecode_set']=null;
$dataset['ent']['sharecode_msg']=null;
}else{
$majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
$majorcodeshare_data=$majorcodeshare['data']['0'];
$receiveamount=(int)$majorcodeshare_data['receive_amount'];
$dataset['ent']['sharecode_data']=$majorcodeshare_data;
$dataset['ent']['receive_amount']=$receiveamount;
$sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
$sharecode_data=$sharecode['data'];
$dataset['ent']['detail']=$sharecode_data;
$sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
$dataset['ent']['sharecode_set']=$sharecode_data_set;
$faculty_name_all=$varent['faculty_name'];
$dataset['ent']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
}
$dataset['ent']['score_max']=$varent['score_max'];
$dataset['ent']['score_min']=$varent['score_min'];


$special_remark=$varent['special_remark'];
$search='--';$replace='';$string=$special_remark;
$special_remark=str_replace($search,$replace,$string);
$dataset['ent']['special_remark']=$special_remark;


$dataset['ent']['lastupdate']=$varent['lastupdate'];
$datasetamss[]=$dataset['ent'];
 }
}else{ $datasetamss=null;}
###############################################
$brancharr['dn']['admission']=$datasetamss;
$admission_count=(int)count($datasetamss);
####################*****admission End*******#############################
###################################admission#######################################
     
     
     
$brancharr['dn']['branch_id']=$branch_id;
$branch_userdata=$this->model_trainer->get_ams_entrance_u_master_id_year_user_id($branch_id,$year,$user_id,$delekey);
#echo'<hr><pre> $branch_userdata=>';print_r($branch_userdata);echo'<pre> <hr>';die();
$branch_user_data=$branch_userdata['data'];
//$brancharr['dn']['branch_user_data']=$branch_userdata;
$branch_user_data_set=implode(',', array_map(function($branch_user_data){ return $branch_user_data['u_id'];}, $branch_user_data ));
$brancharr['dn']['branch_user_data_set']=$branch_user_data_set;
$ent_user_data_set=implode(',', array_map(function($branch_user_data){ return $branch_user_data['ent_id'];}, $branch_user_data ));
$brancharr['dn']['entuser_data_set']=$ent_user_data_set;
//echo'<pre>$branch_user_data_set=>';print_r($branch_user_data_set);echo'<pre> <hr>'; die();
#

##############****directapply setting****##################

$directapplyset=$this->model_trainer->get_ams_entrance_user_year_user_id_directapply($branch_id,$year,$user_id,$delekey);
$directapplyset_data=$directapplyset['data'];
$directapplyset_data_count=(int)count($directapplyset_data);
$brancharr['dn']['directapply_set_count']=$directapplyset_data_count;
if($directapplyset_data_count==0){$directapplystatus=0;}else{$directapplystatus=1;}
$brancharr['dn']['directapply_active']=$directapplystatus;
$sumdirectapplyset=$this->model_trainer->get_ams_news_directapply_branch_id_year_sum($branch_id,$year,$delekey);
$brancharr['dn']['directapply_sum']=(int)$sumdirectapplyset['data']['0']['major_receivers_all'];
##############****directapply setting****##################

$arr_branch[]=$brancharr['dn'];
 }
}
$branch_count=(int)count($arr_branch);
$arr4['faculty']['branch_count']=$branch_count;
#################***branch***#################
if($branch_count==0){}elseif($branch_count!==0){
$arr4['faculty']['branch']=$arr_branch;
}
if($branch_count==0){}elseif($branch_count!==0){
$facultyarray[]=$arr4['faculty'];
}
}
}
############faculty#######################
$facultyarray_count=count($facultyarray);
$arr2['university']['faculty']=$facultyarray;
#echo'<pre>$facultyarray=>';print_r($facultyarray);echo'<pre> <hr>'; die();
if($facultyarray_count==0){}elseif($facultyarray_count!==0){
     $university_arr[]=$arr2['university'];
}
     
      

 }
}
$university_list=$university_arr;
$university_list_count=(int)count($university_arr);

#############################
####*******university*****###
#############################
###############################################################
$arrgt4['gp']['university']=$university_list;
$arrgt4['gp']['university_count']=$university_list_count;
###############################################################
###############################################################
#echo'<pre>$arrgt4=>';print_r($arrgt4);echo'<pre> <hr>'; die();

 if($university_list_count==0){}elseif($university_list_count!==0){
     $grouptype4array[]=$arrgt4['gp'];
 }
//echo'<pre>$grouptype4array=>';print_r($grouptype4array);echo'<pre> <hr>'; die();
 }
}


//echo'<hr><pre> $grouptype4array=>';print_r($grouptype4array);echo'<pre> <hr>';die();

############$top_university_group#######################
$data=array('list'=>$grouptype4array,
//'sql'=>$top_university_group_sql,
'list_count'=>count($grouptype4array),
'user_id'=>$user_id,
'year'=>$year,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'universitylistgroup34listgroupidamstop',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'universitylistgroup34listgroupidamstop',
										'message'=>'ERROR',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
     
#############
}
####AMS TAB Branch END ###
####group4
public function listgroup4listgroupidamsbranchgroup_get(){
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'user_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$member_id=@$this->tppy_member->get_member_profile()->member_id;
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');
$delekey=$this->input->get('delekey');
if($u_group_id<=0){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
$branchgroup=$this->model_trainer->get_entrance_branch_group_map_year($u_group_id,$year,$delekey);
$branchgroup_data=$branchgroup['data'];
$branchgroup_sql=$branchgroup['sql'];
$arr_branch = array();
if (is_array($branchgroup_data)){
foreach ($branchgroup_data as $keys=>$val){
     $brancharr = array();
	$branch_id=(int)$val['branch_id'];
	$brancharr['arr']['branch_id']=$branch_id;
	$brancharr['arr']['group_name']=$val['group_name'];
	$brancharr['arr']['branch_name']=$val['branch_name'];
	$brancharr['arr']['faculty_name']=$val['faculty_name'];
     $brancharr['arr']['university_name']=$val['university_name'];
     $university_id=(int)$val['university_id'];
     $brancharr['arr']['university_id']=$university_id;
     $brancharr['arr']['thumbnail']=$val['thumbnail'];

##################****admission******###################
#$amss=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branch_id,$year,$delekey);
#$ams=$amss['data'];
$amss_set=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_id_set_user_id($year,$branch_id,$user_id,$delekey);
$amss_set_data=$amss_set['data'];
if($amss_set_data==null){
$amss_entset=null;
}else{
$amss_entset=implode(',', array_map(function($amss_set_data){ return $amss_set_data['ent_id'];}, $amss_set_data ));
}
#echo'<pre>amss_entset=>';print_r($amss_entset);echo'<pre> <hr>'; die();

if($amss_entset==null){
$instatus=0;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_set_user_id($branch_id,$year,$instatus,$user_id,0,$delekey);
$amsset=$amssets['data'];
}else{
$instatus=1;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_set_user_id($branch_id,$year,$instatus,$user_id,$amss_entset,$delekey);
$amsset=$amssets['data'];
}
#echo'<pre>amssets=>';print_r($amssets);echo'<pre> <hr>'; die();

$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keydatas => $valna) {
  	$dataset=array();
      $ent_id=$valna['ent_id'];
      $dataset['dd']['year_config']=$valna['year_config'];
      $dataset['dd']['major_remark']=$valna['major_remark'];
      $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
      $dataset['dd']['score_history']=$score_history;
      $dataset['dd']['ent_id']=$valna['ent_id'];
     $branch_id=$valna['branch_id'];
############################
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$branch_id,$user_id,0,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
$ent_id_user=$ams_entrance_user1['ent_id'];
if($user_id==''){
     $dataset['dd']['bookmark_user']=null;
     $dataset['dd']['bookmark_active']=0;
}else{
     $dataset['dd']['bookmark_user']=$ams_entrance_user1;
     if($ent_id_user==$ent_id){
       $dataset['dd']['bookmark_active']=1;
     }else{
      $dataset['dd']['bookmark_active']=0;
     }
}
############################
      $dataset['dd']['01_onet_mi']=$valna['01_onet_mi'];
      $dataset['dd']['01_onet_weight']=$valna['01_onet_weight'];
      $dataset['dd']['02_onet_min']=$valna['02_onet_min'];
      $dataset['dd']['02_onet_weight']=$valna['02_onet_weight'];
      $dataset['dd']['03_onet_mint']=$valna['03_onet_mint'];
      $dataset['dd']['03_onet_weight']=$valna['03_onet_weight'];
      $dataset['dd']['04_onet_min']=$valna['04_onet_min'];
      $dataset['dd']['04_onet_weight']=$valna['04_onet_weight'];
      $dataset['dd']['05_onet_min']=$valna['05_onet_min'];
      $dataset['dd']['05_onet_weight']=$valna['05_onet_weight'];
      $dataset['dd']['71_pat1_min']=$valna['71_pat1_min'];
      $dataset['dd']['71_pat1_weight']=$valna['71_pat1_weight'];
      $dataset['dd']['72_pat2_min']=$valna['72_pat2_min'];
      $dataset['dd']['72_pat2_weight']=$valna['72_pat2_weight'];
      $dataset['dd']['73_pat3_min']=$valna['73_pat3_min'];
      $dataset['dd']['73_pat3_weight']=$valna['73_pat3_weight'];
      $dataset['dd']['74_pat4_min']=$valna['74_pat4_min'];
      $dataset['dd']['74_pat4_weight']=$valna['74_pat4_weight'];
      $dataset['dd']['75_pat5_min']=$valna['75_pat5_min'];
      $dataset['dd']['75_pat5_weight']=$valna['75_pat5_weight'];
      $dataset['dd']['76_pat6_min']=$valna['76_pat6_min'];
      $dataset['dd']['76_pat6_weight']=$valna['76_pat6_weight'];
      $dataset['dd']['77_pat71_min']=$valna['77_pat71_min'];
      $dataset['dd']['77_pat71_weight']=$valna['77_pat71_weight'];
      $dataset['dd']['78_pat72_min']=$valna['78_pat72_min'];
      $dataset['dd']['78_pat72_weight']=$valna['78_pat72_weight'];
      $dataset['dd']['79_pat73_min']=$valna['79_pat73_min'];
      $dataset['dd']['79_pat73_weight']=$valna['79_pat73_weight'];
      $dataset['dd']['80_pat74_min']=$valna['80_pat74_min'];
      $dataset['dd']['80_pat74_weight']=$valna['80_pat74_weight'];
      $dataset['dd']['81_pat75_min']=$valna['81_pat75_min'];
      $dataset['dd']['81_pat75_weight']=$valna['81_pat75_weight'];
      $dataset['dd']['82_pat76_min']=$valna['82_pat76_min'];
      $dataset['dd']['82_pat76_weight']=$valna['82_pat76_weight'];
      $dataset['dd']['85_gat_min']=$valna['85_gat_min'];
      $dataset['dd']['85_gat_min_part2']=$valna['85_gat_min_part2'];
      $dataset['dd']['85_gat_weight']=$valna['85_gat_weight'];
      $dataset['dd']['branch_id']=$valna['branch_id'];
      $dataset['dd']['branch_name']=$valna['branch_name'];
      $dataset['dd']['config']=$valna['config'];
      $dataset['dd']['university_name']=$valna['university_name'];
      $dataset['dd']['faculty_id']=$valna['faculty_id'];
      $dataset['dd']['faculty_name']=$valna['faculty_name'];
      $dataset['dd']['gpax_min']=$valna['gpax_min'];
      $dataset['dd']['gpax_weight']=$valna['gpax_weight'];
      $dataset['dd']['major_code']=$valna['major_code'];
      $dataset['dd']['major_remark']=$valna['major_remark'];
      $dataset['dd']['onet_min_total']=$valna['onet_min_total'];
      $dataset['dd']['onet_weight_total']=$valna['onet_weight_total'];
      $sharecode=$valna['receive_amount_sharecode'];
      $dataset['dd']['receive_amount_sharecode']= $sharecode;
      if($sharecode==''){
        $dataset['dd']['sharecode_data']=null;
        $dataset['dd']['receive_amount']=(int)$valna['receive_amount'];
        $dataset['dd']['sharecode_detail']=null;
        $dataset['dd']['sharecode_set']=null;
         $dataset['dd']['sharecode_msg']=null;
      }else{
       $majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
       $majorcodeshare_data=$majorcodeshare['data']['0'];
       $receiveamount=(int)$majorcodeshare_data['receive_amount'];
       $dataset['dd']['sharecode_data']=$majorcodeshare_data;
       $dataset['dd']['receive_amount']=$receiveamount;
       $sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
       $sharecode_data=$sharecode['data'];
       $dataset['dd']['detail']=$sharecode_data;
       $sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
       $dataset['dd']['sharecode_set']=$sharecode_data_set;
      $faculty_name_all=$valna['faculty_name'];
       $dataset['dd']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
      }
      $dataset['dd']['score_max']=$valna['score_max'];
      $dataset['dd']['score_min']=$valna['score_min'];
      $dataset['dd']['special_remark']=$valna['special_remark'];
      $dataset['dd']['lastupdate']=$valna['lastupdate'];
      $datasetamss[]=$dataset['dd'];
  }
}else{ $datasetamss=null;}
$admission_count=(int)count($datasetamss);
     //$brancharr['arr']['admission_sql']=$amssets['sql'];
     $brancharr['arr']['admission_set']=$amss_entset;
     $brancharr['arr']['admission']=$datasetamss;
     $brancharr['arr']['admission_count']=$admission_count;

##################****admission******###################


##################****directapply******###################
#$dirapply=$this->model_trainer->get_ams_entrance_dirapply_year_set($year,$branch_id,$university_id,$delekey);
#$dirapply=$dirapply['data'];



$dirapplyin=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_in_user_id($year,$branch_id,$university_id,$user_id,$delekey);
$dirapplyinnews=$dirapplyin['data'];
if($dirapplyinnews==''){
$newsidnotinset=null;
}else{
$newsidnotinset=implode(',', array_map(function($dirapplyinnews){ return $dirapplyinnews['news_id'];}, $dirapplyinnews ));
}
if($newsidnotinset==null){
$instatus=0;
$dirapplyrs=$this->model_trainer->get_ams_entrance_dirapply_year_branch_id_user_id($year,$branch_id,$university_id,$user_id,null,0,$delekey);
$dirapply_data=$dirapplyrs['data'];
}else{
$instatus=1;
$dirapplyrs=$this->model_trainer->get_ams_entrance_dirapply_year_branch_id_user_id($year,$branch_id,$university_id,$user_id,$newsidnotinset,$instatus,$delekey);
$dirapply_data=$dirapplyrs['data'];
}
//echo'<pre> dirapply_data=>';print_r($dirapply_data);echo'<pre> <hr>'; die();
$dirapply_count=(int)count($dirapply_data);
$dirapplysql=$dirapplyrs['sql'];	

	
$dirapply_lists = array();
if (is_array($dirapply_data)){
	foreach ($dirapply_data as $key => $value) {
		$datanews = array();	
		$news_id=$value['news_id'];
		$datanews['news']['news_id']=(int)$news_id;
############################
$branch_id=(int)$value['branch_id'];
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id(0,$news_id,$user_id,$branch_id,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
#$datanews['news']['bookmark_user_sql']=$ams_entrance_users['sql'];
$u_id_user=(int)$ams_entrance_user1['u_id'];
if($user_id==''){
     $datanews['news']['bookmark_user']=null;
     $datanews['news']['bookmark_active']=0;
}else{
     $datanews['news']['bookmark_user']=$ams_entrance_user1;
     if($news_id==$u_id_user){
      $datanews['news']['bookmark_active']=1;
     }else{
      $datanews['news']['bookmark_active']=0;
     }
}
############################
		$datanews['news']['news_title']=$value['news_title'];
		$datanews['news']['u_id']=(int)$value['u_id'];
		$datanews['news']['year']=(int)$value['yearnews'];
		$datanews['news']['remark']=$value['major_remark'];
		$datanews['news']['branch_id']=$branch_id;
		$datanews['news']['faculty_id']=(int)$value['faculty_id'];
		$datanews['news']['university_id']=(int)$value['university_id'];
		$datanews['news']['branch_name']=$value['branch_name'];
		$datanews['news']['faculty_name']=$value['faculty_name'];
		$datanews['news']['university_name']=$value['university_name'];
		$datanews['news']['interview_start_date']=$value['interview_start_date'];
		$datanews['news']['interview_end_date']=$value['interview_end_date'];
		$datanews['news']['news_all_receivers']=(int)$value['news_all_receivers'];
		$datanews['news']['news_receivers_text']=$value['news_receivers_text'];
		#################
		$dirapply_lists[]=$datanews['news'];
	}
}else{ $dirapply_lists=null; }
$brancharr['arr']['dirapply_count']=$dirapply_count;
$brancharr['arr']['dirapply']=$dirapply_lists;
//$brancharr['arr']['dirapply_sql']=$dirapplysql;
$brancharr['arr']['dirapply_user_set']=$newsidnotinset;
##################****directapply******###################
     $arr_branch[]=$brancharr['arr'];
     }
}
#######################	
$branch_set=implode(',', array_map(function($arr_branch){ return $arr_branch['branch_id'];}, $arr_branch ));


$data=array('u_group_id'=>$u_group_id,
            'groupname'=>$arr_branch['0']['group_name'],
            'university_count'=>count($arr_branch),
           // 'sql'=>$branchgroup_sql,
            'branch_set'=>$branch_set,
		  'list'=>$arr_branch,
	 	  'user_id'=>$user_id,
            'year'=>$year,);
if($data){
				$this->response(array('header'=>array(
										'title'=>'ams entrance group',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'ams entrance group',
										'message'=>'Error',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
}
#############
}
####Top TAB 1 university && directapply ###
# topuniversityentranceuseruserid?user_id=543622&year=2560&delekey=1
# topuniversityentranceuseruserid?user_id=543622&year=2560&tab=directapply&delekey=1
public function topuniversityentranceuseruserid_get(){
$user_id=@$this->input->get('user_id');
$delekey=@$this->input->get('delekey');
$tab=@$this->input->get('tab');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey=null;}
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'top ams entrance universitygroup',
									  'message'=>'Error user_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}

$u_group_id_type3=@$this->input->get('u_group_id_type3');
/*
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
*/
$u_group_id_type4=@$this->input->get('u_group_id_type4');
/*
$universitygroup4=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);
$university_group4=$universitygroup4['0'];
$group_type4_name=$university_group4['u_group_name'];
if($u_group_id_type3==''||$u_group_id_type4==''){
$this->response(array('header'=>array('title'=>'top ams entrance u_group_id_type3 or 4',
									  'message'=>'Error user_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}


*/

if($tab=='adminsion'){
/*
$typeid=2;
$universityname=$this->model_trainer->get_ams_entrance_user_by_ams_university_form_user_id($user_id,$typeid,$delekey);
$universityname_data=$universityname['data'];
$universityname_sql=$universityname['sql'];
*/
$sql="select (select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))) AS thumbnail 
from  ams_entrance_user as huser 
inner join  ams_university as uu on uu.u_id=huser.u_id 
where  huser.user_id=$user_id group by university_id";
#################cache#################
$cache_key="get_ams_entrance_user_by_ams_university_form_user_id_".$sql;
$cache_key_encrypt=$this->encryptcode_get($cache_key);
$cache_key_decryptcode=$this->decryptcode_get($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$cache_key_encrypt=$this->encryptcode_get($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data=$query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
}
$universityname_data=$data;
#echo '<hr><pre>universityname_data=>'; print_r($universityname_data); echo '</pre>';die();
#################cache#################
$universityname_sql=$sql;
}elseif($tab==''||$tab=='directapply'){
$typeid=1;
$universityname=$this->model_trainer->get_ams_entrance_user_by_ams_university_form_user_id($user_id,$typeid,$delekey);
$universityname_data=$universityname['data'];
$universityname_sql=$universityname['sql'];
}

 #echo '<hr><pre> universityname=>'; print_r($universityname); echo '</pre>';die();
#echo '<hr><pre>tab=>'; print_r($tab); echo '</pre>';
 #echo '<hr><pre>universityname_data=>'; print_r($universityname_data); echo '</pre>';die();

$arr_university = array();
if (is_array($universityname_data)){
foreach ($universityname_data as $keys=>$valu){
$arr = array();
	$university_id=(int)$valu['university_id'];
	$arr['arr']['university_id']=$university_id;
    

     /*
     $arr['arr']['group_type3_name']=$group_type3_name;
     $arr['arr']['group_type4_name']=$group_type4_name;
     $arr['arr']['group_type3_thumbnail']=$group_type3_thumbnail;
     */
	$arr['arr']['university_name']=$valu['university_name'];
	$arr['arr']['thumbnail']=$valu['thumbnail'];
########################
$faculty=$this->model_trainer->get_ams_university_by_u_parentid($university_id,$delekey);
$faculty_data=$faculty['data'];
$faculty_sql=$faculty['sql'];
#echo '<hr><pre> faculty=>'; print_r($faculty); echo '</pre>';die();
$arr_faculty = array();
if (is_array($faculty_data)){
foreach ($faculty_data as $k=>$v){
$arr_fac = array();
	$faculty_id=(int)$v['u_id'];
	$arr_fac['fac']['faculty_id']=$faculty_id;
     $arr_fac['fac']['faculty_name']=$v['u_name'];
$arr_faculty[]=$arr_fac['fac'];
 }
}else{$arr_faculty=null;}

#echo '<hr><pre> arr_faculty=>'; print_r($arr_faculty); echo '</pre>';die();

if($arr_faculty==null){$faculty_set=null;}else{
$faculty_set=implode(',', array_map(function($arr_faculty){ return $arr_faculty['faculty_id'];}, $arr_faculty ));
##########################
#$arr['arr']['faculty1']=$arr_faculty;
$facultys_count=(int)count($arr_faculty);
$arr['arr']['facultys_count']=$facultys_count;
$arr['arr']['facultys_set']=$faculty_set;
##########################
#echo '<hr><pre> $faculty_set=>'; print_r($faculty_set); echo '</pre>';die();
 
$branchall=$this->model_trainer->get_ams_university_by_u_set_all($faculty_set,$delekey);
#$branch1=$this->model_trainer->get_ams_university_by_u_set_in_user_year($faculty_set,$user_id,$year,$delekey);
$branch2=$this->model_trainer->get_ams_university_by_u_set_in_user_year_allstatus($faculty_set,$user_id,$year,$delekey);
$branch=$branch2;

#$arr['arr']['facultys_branchall']=$branchall;
$u_group_id_type3=(int)$branch['data']['0']['u_group_id_type3'];
$u_group_id_type4=(int)$branch['data']['0']['u_group_id_type4'];
     #$arr['arr']['u_group_id_type3']=$u_group_id_type3;
     #$arr['arr']['u_group_id_type4']=$u_group_id_type4;

//echo '<hr><pre> $branch=>'; print_r($branch); echo '</pre>';die();
$branch_data=$branch['data'];
$branch_sql=$branch['sql'];
#$arr['arr']['branch_sql']=$branch_sql;
#$arr['arr']['branch_1']=$branch1;
#$arr['arr']['branch_all']=$branchall;
$arrbranch=array();
if (is_array($branch_data)){
foreach ($branch_data as $k2=>$v2){
     $arr_branch=array();
	$branch_id=(int)$v2['u_id'];
	$arr_branch['branch']['branch_id']=$branch_id;
     $arr_branch['branch']['branch_name']=$v2['u_name'];
     $arrbranch[]=$arr_branch['branch'];
 }
}else{$branch_set=null;}
############**branch**##############
#$arr['arr']['branch_in']=$arrbranch;
$arr['arr']['branch_in_count']=(int)count($arrbranch);
############**branch**##############

##########******branch2*****###########
if($arrbranch==''){
$branch_set=null;
#$arr['arr']['branch_in_set']=$branch_set;
}else{
$branch_set=implode(',', array_map(function($arrbranch){ return $arrbranch['branch_id'];}, $arrbranch ));
#echo '<hr><pre> $branch_set=>'; print_r($branch_set); echo '</pre>';die();

$arr['arr']['branch_in_set']=$branch_set;
$faculty_form_branch=$this->model_trainer->get_faculty_up_form_branchsetdata_admission_user($year,$branch_set,$user_id,$delekey);
#$faculty_form_branch=$this->model_trainer->get_ams_university_entrance_faculty_top_in_id($branch_set,$user_id,$year,$delekey);
$facultydata1=$faculty_form_branch['data'];
##############################
if(is_array($facultydata1)){
$arrbranchfaculty=array();
foreach ($facultydata1 as $keyy=>$vdata){
$arr_branch_faculty=array();
$arr_branch_faculty['bn']['ent_id']=$vdata['ent_id'];
$arr_branch_faculty['bn']['faculty_id']=$vdata['faculty_id'];
$arr_branch_faculty['bn']['faculty_name']=$vdata['faculty_name'];
$arr_branch_faculty['bn']['university_name']=$vdata['university_name'];
$arr_branch_faculty['bn']['year']=$vdata['year'];
$arrbranchfaculty[]=$arr_branch_faculty['bn'];
}
}else{$arrbranchfaculty=null;}



$facultydata1=$arrbranchfaculty;
##############################
#echo'<pre> admission data=>';print_r($arrbranchfaculty);echo'<pre> <hr>'; 


if($facultydata1==null){
//$faculty_form_branchold=$this->model_trainer->get_faculty_up_form_branchsetdata_directapply_user($year,$branch_set,$user_id,$delekey);

$faculty_form_branch=$this->model_trainer->get_ams_university_entrance_faculty_top_in_id($branch_set,$user_id,$year,$delekey);

$facultydata2=$faculty_form_branch['data'];
##############################
if(is_array($facultydata2)){
$arrbranchfaculty_n=array();
foreach ($facultydata2 as $keym=>$vdatan){
$arr_branch_faculty_n=array();
$arr_branch_faculty_n['bm']['ent_id']=$vdatan['ent_id'];
$arr_branch_faculty_n['bm']['faculty_id']=$vdatan['faculty_id'];
$arr_branch_faculty_n['bm']['faculty_name']=$vdatan['faculty_name'];
$arr_branch_faculty_n['bm']['university_name']=$vdatan['university_name'];
$arr_branch_faculty_n['bm']['year']=$vdatan['year'];
$u_group_id_type3=(int)$vdatan['u_group_id_type3'];
$u_group_id_type4=(int)$vdatan['u_group_id_type4'];
$arr_branch_faculty_n['bm']['u_group_id_type3']=(int)$vdatan['u_group_id_type3'];
$arr_branch_faculty_n['bm']['u_group_id_type4']=(int)$vdatan['u_group_id_type4'];



$arrbranchfaculty_n[]=$arr_branch_faculty_n['bm'];
}
}else{$arrbranchfaculty_n=null;}
$facultydata1=$arrbranchfaculty_n;
##############################
//echo'<pre> directapply  data=>';print_r($arrbranchfaculty_n);echo'<pre> <hr>'; 

$arr['arr']['faculty_form']='directapply ';
}else{
$arr['arr']['faculty_form']='admission ';
}
$faculty_form_branch_data=$facultydata1;


//echo'<pre> faculty_form_branch data=>';print_r($faculty_form_branch);echo'<pre> <hr>'; die();

if($faculty_form_branch==''){
   $arr['arr']['faculty']=null;
   $arr['arr']['faculty_branc_implode']=null;
}else{

$fac_branchset=implode(',', array_map(function($faculty_form_branch_data){ return $faculty_form_branch_data['faculty_id'];}, $faculty_form_branch_data ));
$arr['arr']['faculty_branc_implode']=$fac_branchset;


######################################
$arrfaculty3=array();
if (is_array($faculty_form_branch_data)){
foreach ($faculty_form_branch_data as $k3=>$v3){

$arr_faculty3=array();
$faculty_id=(int)$v3['faculty_id'];
#########################
$branch_user=$this->model_trainer->get_ams_university_by_u_set_id($faculty_id,$delekey);
$branch_user_data=$branch_user['data'];
//$arr_faculty3['faculty']['branch_sql']=$branch_user['sql'];
$arrbranch4=array();
if(is_array($branch_user_data)){
   foreach($branch_user_data as $k4=>$v4){
     $arr_branch4=array();
          $branch_id=(int)$v4['u_id'];
          
          $arr_branch4['branch']['u_group_id_type3']=$u_group_id_type3;
          $arr_branch4['branch']['u_group_id_type4']=$u_group_id_type4;
          $arr_branch4['branch']['branch_id']=$branch_id;
          $arr_branch4['branch']['branch_name']=$v4['u_name'];
          $arr_branch4['branch']['thumbnail']='http://www.trueplookpanya.com/assets/images/img-icon/ico_facGroup.png';
          if($tab=='directapply'){
          $arr_branch4['branch']['branch_form']='directapply ';
          }else{
          $arr_branch4['branch']['branch_form']='admission and  directapply';
          }
#######################################################################################

###################################admission#######################################
          #########################admission##############################
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,2,$user_id,null,$delekey);
$amsset=$amssets['data'];
#$arr_branch4['branch']['admission_sql']=$amssets['sql'];
$amss_entset=implode(',', array_map(function($amsset){ return $amsset['u_id'];}, $amsset ));
$arr_branch4['branch']['admission_set']=$amss_entset;


$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keydatas => $val) {
$dataset=array();
$ent_id=$val['ent_id'];

############################
$branch_id=$val['branch_id'];
/*
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$branch_id,$user_id,0,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
*/

$sql="select * from  ams_entrance_user where ent_id=$ent_id and u_id=$branch_id and user_id=$user_id and directapply_faculty_id=0 limit 1";
#echo '<hr><pre> $sql=>'; print_r($sql); echo '</pre>'; 

###########cache###############
$cache_key='get_ams_entrance_user_by_ent_id_u_id_user_id_top_0_'.$news_id.'_'.$user_id.'_'.$branch_id;
$cache_key_encrypt=$this->encryptcode_get($cache_key);
$cache_key_decryptcode=$this->decryptcode_get($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$ams_entrance_user1=$this->tppymemcached->get($cache_key_encrypt);
if(!$ams_entrance_user1) {
$query=$this->db->query($sql);
$amsentranceusers=$query->result_array();
$ams_entrance_user1=$amsentranceusers;
$num_rows=(int)$query->num_rows();
$this->tppymemcached->set($cache_key_encrypt,$ams_entrance_user1,300);
} 
###########cache###############
#$dataset['dd']['bookmark_user_sql']=$sql;
$ent_id_user=$ams_entrance_user1['0']['ent_id'];

if($user_id==''){
     $dataset['dd']['bookmark_user']=null;
     $dataset['dd']['bookmark_active']=0;
}else{
     $dataset['dd']['bookmark_user']=$ams_entrance_user1;
     if($ent_id_user==$ent_id){
       $dataset['dd']['bookmark_active']=1;
     }else{
      $dataset['dd']['bookmark_active']=0;
     }
}
############################
      $dataset['dd']['year_config']=$val['year_config'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
      $dataset['dd']['score_history']=$score_history;
      $ent_id=$val['ent_id'];
      $dataset['dd']['ent_id']=$ent_id;
     
      $dataset['dd']['major_code']=$val['major_code'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      
      $dataset['dd']['gpax_min']=$val['gpax_min'];
      $dataset['dd']['gpax_weight']=$val['gpax_weight'];
 
      $d_01_onet_mi=$val['01_onet_mi'];
      $d_01_onet_weight=$val['01_onet_weight'];
      $d_02_onet_min=$val['02_onet_min'];
      $d_02_onet_weight=$val['02_onet_weight'];
      $d_03_onet_mint=$val['03_onet_mint'];
      $d_03_onet_weight=$val['03_onet_weight'];
      $d_04_onet_min=$val['04_onet_min'];
      $d_04_onet_weight=$val['04_onet_weight'];
      $d_05_onet_min=$val['05_onet_min'];
      $d_05_onet_weight=$val['05_onet_weight'];
      $d_71_pat1_min=$val['71_pat1_min'];
      $d_71_pat1_weight=$val['71_pat1_weight'];
      $d_72_pat2_min=$val['72_pat2_min'];
      $d_72_pat2_weight=$val['72_pat2_weight'];
      $d_73_pat3_min=$val['73_pat3_min'];
      $d_73_pat3_weight=$val['73_pat3_weight'];
      $d_74_pat4_min=$val['74_pat4_min'];
      $d_74_pat4_weight=$val['74_pat4_weight'];
      $d_75_pat5_min=$val['75_pat5_min'];
      $d_75_pat5_weight=$val['75_pat5_weight'];
      $d_76_pat6_min=$val['76_pat6_min'];
      $d_76_pat6_weight=$val['76_pat6_weight'];
      $d_77_pat71_min=$val['77_pat71_min'];
      $d_77_pat71_weight=$val['77_pat71_weight'];
      $d_78_pat72_min=$val['78_pat72_min'];
      $d_78_pat72_weight=$val['78_pat72_weight'];
      $d_79_pat73_min=$val['79_pat73_min'];
      $d_79_pat73_weight=$val['79_pat73_weight'];
      $d_80_pat74_min=$val['80_pat74_min'];
      $d_80_pat74_weight=$val['80_pat74_weight'];
      $d_81_pat75_min=$val['81_pat75_min'];
      $d_81_pat75_weight=$val['81_pat75_weight'];
      $d_82_pat76_min=$val['82_pat76_min'];
      $d_82_pat76_weight=$val['82_pat76_weight'];
      $d_85_gat_min=$val['85_gat_min'];
      $d_85_gat_min_part2=$val['85_gat_min_part2'];
      $d_85_gat_weight=$val['85_gat_weight'];
      $d_onet_min_total=$val['onet_min_total'];
      $d_onet_weight_total=$val['onet_weight_total'];
      
      if($d_01_onet_mi>=1){
       $dataset['dd']['onet']['01_onet_mi']=$d_01_onet_mi;
      }if($d_01_onet_weight>=1){
      $dataset['dd']['onet']['01_onet_weight']=$d_01_onet_weight;
      }if($d_02_onet_min>=1){
      $dataset['dd']['onet']['02_onet_min']=$d_02_onet_min;
      }if($d_02_onet_weight>=1){
      $dataset['dd']['onet']['02_onet_weight']=$d_02_onet_weight;
       }if($d_03_onet_mint>=1){
      $dataset['dd']['onet']['03_onet_mint']=$d_03_onet_mint;
       }if($d_03_onet_weight>=1){
      $dataset['dd']['onet']['03_onet_weight']=$d_03_onet_weight;
       }if($d_04_onet_min>=1){
      $dataset['dd']['onet']['04_onet_min']=$d_04_onet_min;
       }if($d_04_onet_weight>=1){
      $dataset['dd']['onet']['04_onet_weight']=$d_04_onet_weight;
       }if($d_05_onet_min>=1){
      $dataset['dd']['onet']['05_onet_min']=$d_05_onet_min;
       }if($d_05_onet_weight>=1){
      $dataset['dd']['onet']['05_onet_weight']=$d_05_onet_weight;
       }if($d_onet_min_total>=1){
      $dataset['dd']['onet']['onet_min_total']=$d_onet_min_total;
       }if($d_onet_weight_total>=1){
      $dataset['dd']['onet']['onet_weight_total']=$d_onet_weight_total;
      }if($d_71_pat1_min>=1){
      $dataset['dd']['pat']['71_pat1_min']=$d_71_pat1_min;
      }if($d_71_pat1_weight>=1){
      $dataset['dd']['pat']['71_pat1_weight']=$d_71_pat1_weight;
      }if($d_72_pat2_min>=1){
      $dataset['dd']['pat']['72_pat2_min']=$d_72_pat2_min;
      }if($d_72_pat2_weight>=1){
      $dataset['dd']['pat']['72_pat2_weight']=$d_72_pat2_weight;
      }if($d_73_pat3_min>=1){
      $dataset['dd']['pat']['73_pat3_min']=$d_73_pat3_min;
      }if($d_73_pat3_weight>=1){
      $dataset['dd']['pat']['73_pat3_weight']=$d_73_pat3_weight;
      }if($d_74_pat4_min>=1){
      $dataset['dd']['pat']['74_pat4_min']=$d_74_pat4_min;
      }if($d_74_pat4_weight>=1){
      $dataset['dd']['pat']['74_pat4_weight']=$d_74_pat4_weight;
      }if($d_75_pat5_min>=1){
      $dataset['dd']['pat']['75_pat5_min']=$d_75_pat5_min;
      }if($d_75_pat5_weight>=1){
      $dataset['dd']['pat']['75_pat5_weight']=$d_75_pat5_weight;
      }if($d_76_pat6_min>=1){
      $dataset['dd']['pat']['76_pat6_min']=$d_76_pat6_min;
      }if($d_76_pat6_weight>=1){
      $dataset['dd']['pat']['76_pat6_weight']=$d_76_pat6_weight;
      }if($d_77_pat71_min>=1){
      $dataset['dd']['pat']['77_pat71_min']=$d_77_pat71_min;
      }if($d_77_pat71_weight>=1){
      $dataset['dd']['pat']['77_pat71_weight']=$d_77_pat71_weight;
      }if($d_78_pat72_min>=1){
      $dataset['dd']['pat']['78_pat72_min']=$d_78_pat72_min;
      }if($d_78_pat72_weight>=1){
      $dataset['dd']['pat']['78_pat72_weight']=$d_78_pat72_weight;
      }if($d_79_pat73_min>=1){
      $dataset['dd']['pat']['79_pat73_min']=$d_79_pat73_min;
      }if($d_79_pat73_weight>=1){
      $dataset['dd']['pat']['79_pat73_weight']=$d_79_pat73_weight;
      }if($d_80_pat74_min>=1){
      $dataset['dd']['pat']['80_pat74_min']=$d_80_pat74_min;
      }if($d_80_pat74_weight>=1){
      $dataset['dd']['pat']['80_pat74_weight']=$d_80_pat74_weight;
      }if($d_81_pat75_min>=1){
      $dataset['dd']['pat']['81_pat75_min']=$d_81_pat75_min;
      }if($d_81_pat75_weight>=1){
      $dataset['dd']['pat']['81_pat75_weight']=$d_81_pat75_weight;
      }if($d_82_pat76_min>=1){
      $dataset['dd']['pat']['82_pat76_min']=$d_82_pat76_min;
      }if($d_82_pat76_weight>=1){
      $dataset['dd']['pat']['82_pat76_weight']=$d_82_pat76_weight;
      }if($d_85_gat_min>=1){
      $dataset['dd']['gat']['85_gat_min']=$d_85_gat_min;
      }if($d_85_gat_min_part2>=1){
      $dataset['dd']['gat']['85_gat_min_part2']=$d_85_gat_min_part2;
      }if($d_85_gat_weight>=1){
      $dataset['dd']['gat']['85_gat_weight']=$d_85_gat_weight;
     }
      $dataset['dd']['branch_id']=$val['branch_id'];
      $dataset['dd']['branch_name']=$val['branch_name'];
      $dataset['dd']['config']=$val['config'];
      $dataset['dd']['university_name']=$val['university_name'];
      $dataset['dd']['faculty_id']=$val['faculty_id'];
      $dataset['dd']['faculty_name']=$val['faculty_name'];
      
      $sharecode=$val['receive_amount_sharecode'];
      $dataset['dd']['receive_amount_sharecode']= $sharecode;
      if($sharecode==''){
        $dataset['dd']['sharecode_data']=null;
        $dataset['dd']['receive_amount']=(int)$val['receive_amount'];
        $dataset['dd']['sharecode_detail']=null;
        $dataset['dd']['sharecode_set']=null;
         $dataset['dd']['sharecode_msg']=null;
      }else{
       $majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
       $majorcodeshare_data=$majorcodeshare['data']['0'];
       $receiveamount=(int)$majorcodeshare_data['receive_amount'];
       $dataset['dd']['sharecode_data']=$majorcodeshare_data;
       $dataset['dd']['receive_amount']=$receiveamount;
       $sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
       $sharecode_data=$sharecode['data'];
       $dataset['dd']['detail']=$sharecode_data;
       $sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
       $dataset['dd']['sharecode_set']=$sharecode_data_set;
      $faculty_name_all=$val['faculty_name'];
       $dataset['dd']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
      }
      $dataset['dd']['score_max']=$val['score_max'];
      $dataset['dd']['score_min']=$val['score_min'];
      
      
$special_remark=$val['special_remark'];
$search='--';$replace='';$string=$special_remark;
$special_remark=str_replace($search,$replace,$string);
$dataset['dd']['special_remark']=$special_remark;

      $dataset['dd']['lastupdate']=$val['lastupdate'];
      $datasetamss[]=$dataset['dd'];
  }
}else{ $datasetamss=null;}
#######################	

$admission_count=(int)count($datasetamss);
     if($tab==null){
          $arr_branch4['branch']['admission']=$datasetamss;
          $arr_branch4['branch']['admission_count']=$admission_count;
     } 
          ##########################admission######################################
###################################admission#######################################
#######################################################################################

 
##################****directapply******###################
##############****directapply setting****#################

$directapplyset=$this->model_trainer->get_ams_entrance_user_year_user_id_directapply($branch_id,$year,$user_id,$delekey);
$directapplyset_data=$directapplyset['data'];
$directapplyset_data_count=(int)count($directapplyset_data);
 $arr_branch4['branch']['directapply_set_count']=$directapplyset_data_count;
if($directapplyset_data_count==0){$directapplystatus=0;}else{$directapplystatus=1;}
 $arr_branch4['branch']['directapply_active']=$directapplystatus;
$sumdirectapplyset=$this->model_trainer->get_ams_news_directapply_branch_id_year_sum($branch_id,$year,$delekey);
 $arr_branch4['branch']['directapply_sum']=(int)$sumdirectapplyset['data']['0']['major_receivers_all'];
##############****directapply setting****##################
##################****directapply******###################
 
#######################################################################################
          $arr['arr']['year']=$year;
          
               $arrbranch4[]=$arr_branch4['branch'];
               

  }
}else{$arrbranch4=null;}
/*
if($tab=='directapply'){
     if($directapplystatus>0){
           $arr_faculty3['faculty']['branch']=$arrbranch4;
           $arr_faculty3['faculty']['branch_count']=count($arrbranch4);
           ##########################
           $arr_faculty3['faculty']['faculty_id']=$faculty_id;
           $arr_faculty3['faculty']['faculty_name']=$v3['faculty_name'];
           ##########################
           $arrfaculty3[]=$arr_faculty3['faculty'];
     }
}else{}
*/ 
          $arr_faculty3['faculty']['branch']=$arrbranch4;
          $arr_faculty3['faculty']['branch_count']=count($arrbranch4);
          ##########################
          $arr_faculty3['faculty']['faculty_id']=$faculty_id;
          $arr_faculty3['faculty']['faculty_name']=$v3['faculty_name'];
          ##########################
          $arrfaculty3[]=$arr_faculty3['faculty'];


}
}else{$arrfaculty3=null;}
######################################
$faculty_branch_count_set=implode(',', array_map(function($arrfaculty3){ return $arrfaculty3['branch_count'];}, $arrfaculty3 ));
$arr['arr']['faculty_branch_count_set']=$faculty_branch_count_set;
$faculty_from_branch_set=implode(',', array_map(function($arrfaculty3){ return $arrfaculty3['faculty_id'];}, $arrfaculty3 ));
     $arr['arr']['faculty_set']=$faculty_from_branch_set;
     $arr['arr']['faculty']=$arrfaculty3;
     $faculty_count=(int)count($arrfaculty3);
     $arr['arr']['faculty_count']=$faculty_count;
 }
}
    # echo '<hr><pre> $arr=>'; print_r($arr); echo '</pre>';die();
##########******branch2*****###########
##########################
##########################
}
########################
#if($faculty_count<=0){}else{$arr_university[]=$arr['arr'];}
 if($faculty_count>0){ $arr_university[]=$arr['arr'];}
}
########################
}else{$arr_university=null;}
/**
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
*/
$university_set=implode(',', array_map(function($arr_university){ return $arr_university['university_id'];}, $arr_university ));
$faculty_setrs=$this->model_trainer->get_faculty_set_university_id($university_set,$delekey);
$faculty_set=implode(',', array_map(function($faculty_setrs){ return $faculty_setrs['faculty_id'];}, $faculty_setrs ));
$branch_user=$this->model_trainer->get_ams_university_by_u_set_id($faculty_set,$delekey);
$branch_user_data=$branch_user['data'];
$branch_set=implode(',', array_map(function($branch_user_data){ return $branch_user_data['u_id'];}, $branch_user_data ));
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_set,$year,2,$user_id,null,$delekey);
$amsset=$amssets['data'];
$data=array('university'=>$arr_university,
            'university_set'=>$university_set,
            'faculty_set'=>$faculty_set,
            'branch_set'=>$branch_set,
            'amsset_count'=>(int)count($amsset),
            //'amsset_count'=>$amsset,
            'university_count'=>count($arr_university),
            #'university_sql'=>$universityname_sql,
            'tab'=>$tab,
            'u_group_id_type3'=>$u_group_id_type3,
            'u_group_id_type4'=>$u_group_id_type4,
	 	  'user_id'=>$user_id,
            'year'=>$year,
             );
if($data){
$this->response(array('header'=>array('title'=>'top ams entrance universitygroup',
							   'message'=>'Success',
							   'status'=>true,
							   'code'=>200), 
							   'data'=> $data),200);
}else{
$this->response(array('header'=>array('title'=>'top ams entrance universitygroup',
							   'message'=>'Error',
							   'status'=>true, 
							   'code'=>404), 
							   'data'=> $data),404);
}
}
public function topuniversityentranceuseruseridadminsion_get(){
$user_id=@$this->input->get('user_id');
$delekey=@$this->input->get('delekey');
$tab=@$this->input->get('tab');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey=null;}
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'top ams entrance universitygroup adminsion',
									  'message'=>'Error user_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}

$u_group_id_type3=@$this->input->get('u_group_id_type3');
/*
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
*/
$u_group_id_type4=@$this->input->get('u_group_id_type4');
/*
$universitygroup4=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);
$university_group4=$universitygroup4['0'];
$group_type4_name=$university_group4['u_group_name'];
*/




$sql="select (select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))) AS thumbnail 
from  ams_entrance_user as huser 
inner join  ams_university as uu on uu.u_id=huser.u_id and huser.ent_id!=0 
where  huser.user_id=$user_id group by university_id";
#################cache#################
$cache_key="get_ams_entrance_user_by_ams_university_form_user_id_".$sql;
$cache_key_encrypt=$this->encryptcode_get($cache_key);
$cache_key_decryptcode=$this->decryptcode_get($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$cache_key_encrypt=$this->encryptcode_get($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data=$query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
}
$universityname_data=$data;
#echo '<hr><pre>universityname_data=>'; print_r($universityname_data); echo '</pre>';die();
#################cache#################

/*
$typeid=2;
$universityname=$this->model_trainer->get_ams_entrance_user_by_ams_university_form_user_id($user_id,$typeid,$delekey);
$universityname_data=$universityname['data'];
$universityname_sql=$universityname['sql'];
*/

$arr_university = array();
if (is_array($universityname_data)){
foreach ($universityname_data as $keys=>$valu){
$arr = array();
	$university_id=(int)$valu['university_id'];
	$arr['arr']['university_id']=$university_id;
	$arr['arr']['university_name']=$valu['university_name'];
	$arr['arr']['thumbnail']=$valu['thumbnail'];
########################
 




$faculty=$this->model_trainer->get_ams_university_by_u_parentid($university_id,$delekey);
$faculty_data=$faculty['data'];
$faculty_sql=$faculty['sql'];
//echo '<hr><pre> faculty=>'; print_r($faculty); echo '</pre>';die();
$arr_faculty = array();
if (is_array($faculty_data)){
foreach ($faculty_data as $k=>$v){
$arr_fac = array();
	$faculty_id=(int)$v['u_id'];
	$arr_fac['fac']['faculty_id']=$faculty_id;
     $arr_fac['fac']['faculty_name']=$v['u_name'];
$arr_faculty[]=$arr_fac['fac'];
 }
}else{$arr_faculty=null;}

if($arr_faculty==null){$faculty_set=null;}else{
$faculty_set=implode(',', array_map(function($arr_faculty){ return $arr_faculty['faculty_id'];}, $arr_faculty ));
##########################
#$arr['arr']['faculty1']=$arr_faculty;
$arr['arr']['facultys_count']=(int)count($arr_faculty);
#$arr['arr']['facultys_set']=$faculty_set;
##########################
$branchall=$this->model_trainer->get_ams_university_by_u_set_all($faculty_set,$delekey);
//echo '<hr><pre> $branchall=>'; print_r($branchall); echo '</pre>';die();

$branch1=$this->model_trainer->get_ams_university_by_u_set_in_user_year($faculty_set,$user_id,$year,$delekey);
$branch=$branchall;

     $arr['arr']['u_group_id_type3']=(int)$branch1['data']['0']['u_group_id_type3'];
     $arr['arr']['u_group_id_type4']=(int)$branch1['data']['0']['u_group_id_type4'];

$branch_data=$branch['data'];
$branch_sql=$branch['sql'];
$arrbranch=array();
if (is_array($branch_data)){
foreach ($branch_data as $k2=>$v2){
     $arr_branch=array();
	$branch_id=(int)$v2['u_id'];
	$arr_branch['branch']['branch_id']=$branch_id;
     $arr_branch['branch']['branch_name']=$v2['u_name'];
     $arrbranch[]=$arr_branch['branch'];
 }
}else{$branch_set=null;}
############**branch**##############
#$arr['arr']['branch_in']=$arrbranch;
$arr['arr']['branch_in_count']=(int)count($arrbranch);
############**branch**##############
##########******branch2*****###########
if($arrbranch==''){
     $branch_set=null;
#$arr['arr']['branch_in_set']=$branch_set;
}else{
$branch_set=implode(',', array_map(function($arrbranch){ return $arrbranch['branch_id'];}, $arrbranch ));
#$arr['arr']['branch_in_set']=$branch_set;
$faculty_form_branch=$this->model_trainer->get_faculty_up_form_branchsetdata_admission_user($year,$branch_set,$user_id,$delekey);
$facultydata1=$faculty_form_branch['data'];
##############################
if(is_array($facultydata1)){
$arrbranchfaculty=array();
foreach ($facultydata1 as $keyy=>$vdata){
$arr_branch_faculty=array();
$arr_branch_faculty['bn']['ent_id']=$vdata['ent_id'];
$arr_branch_faculty['bn']['faculty_id']=$vdata['faculty_id'];
$arr_branch_faculty['bn']['faculty_name']=$vdata['faculty_name'];
$arr_branch_faculty['bn']['university_name']=$vdata['university_name'];
$arr_branch_faculty['bn']['year']=$vdata['year'];
$arrbranchfaculty[]=$arr_branch_faculty['bn'];
}
}else{$arrbranchfaculty=null;}
$facultydata1=$arrbranchfaculty;
##############################
//echo'<pre> admission data=>';print_r($arrbranchfaculty);echo'<pre> <hr>'; 
if($facultydata1==null){
$faculty_form_branch=$this->model_trainer->get_faculty_up_form_branchsetdata_directapply_user($year,$branch_set,$user_id,$delekey);
$facultydata2=$faculty_form_branch['data'];
##############################
if(is_array($facultydata2)){
$arrbranchfaculty_n=array();
foreach ($facultydata2 as $keym=>$vdatan){
$arr_branch_faculty_n=array();
$arr_branch_faculty_n['bm']['news_id']=$vdatan['news_id'];
$arr_branch_faculty_n['bm']['faculty_id']=$vdatan['faculty_id'];
$arr_branch_faculty_n['bm']['faculty_name']=$vdatan['faculty_name'];
$arr_branch_faculty_n['bm']['university_name']=$vdatan['university_name'];
$arr_branch_faculty_n['bm']['year']=$vdatan['year'];
$arrbranchfaculty_n[]=$arr_branch_faculty_n['bm'];
}
}else{$arrbranchfaculty_n=null;}
$facultydata1=$arrbranchfaculty_n;
##############################
//echo'<pre> directapply  data=>';print_r($arrbranchfaculty_n);echo'<pre> <hr>'; 

$arr['arr']['faculty_form']='directapply ';
}else{
$arr['arr']['faculty_form']='admission ';
}
$faculty_form_branch_data=$facultydata1;


//echo'<pre> faculty_form_branch data=>';print_r($faculty_form_branch);echo'<pre> <hr>'; die();

if($faculty_form_branch==''){
   $arr['arr']['faculty']=null;
   $arr['arr']['faculty_branc_implode']=null;
}else{

$fac_branchset=implode(',', array_map(function($faculty_form_branch_data){ return $faculty_form_branch_data['faculty_id'];}, $faculty_form_branch_data ));
$arr['arr']['faculty_branc_implode']=$fac_branchset;


######################################
$arrfaculty3=array();
if (is_array($faculty_form_branch_data)){
foreach ($faculty_form_branch_data as $k3=>$v3){

$arr_faculty3=array();
$faculty_id=(int)$v3['faculty_id'];
#########################
$branch_user=$this->model_trainer->get_ams_university_by_u_set_id($faculty_id,$delekey);
$branch_user_data=$branch_user['data'];
//$arr_faculty3['faculty']['branch_sql']=$branch_user['sql'];
$arrbranch4=array();
if(is_array($branch_user_data)){
   foreach($branch_user_data as $k4=>$v4){
     $arr_branch4=array();
          $branch_id=(int)$v4['u_id'];
          $arr_branch4['branch']['branch_id']=$branch_id;
          $arr_branch4['branch']['branch_name']=$v4['u_name'];
          $arr_branch4['branch']['thumbnail']='http://www.trueplookpanya.com/assets/images/img-icon/ico_facGroup.png';
         
#######################################################################################
###################################admission#######################################
          #########################admission##############################
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,2,$user_id,null,$delekey);
$amsset=$amssets['data'];
//$arr_branch4['branch']['admission_sql']=$amssets['sql'];
$amss_entset=implode(',', array_map(function($amsset){ return $amsset['u_id'];}, $amsset ));
$arr_branch4['branch']['admission_set']=$amss_entset;


$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keydatas => $val) {
$dataset=array();
$ent_id=$val['ent_id'];

############################
$branch_id=$val['branch_id'];

/*
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$branch_id,$user_id,0,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];

*/


$sql="select * from  ams_entrance_user where ent_id=$ent_id and u_id=$branch_id and user_id=$user_id and directapply_faculty_id=0 limit 1";
#echo '<hr><pre> $sql=>'; print_r($sql); echo '</pre>'; 
###########cache###############
$cache_key='get_ams_entrance_user_by_ent_id_u_id_user_id_top_0_'.$news_id.'_'.$user_id.'_'.$branch_id;
$cache_key_encrypt=$this->encryptcode_get($cache_key);
$cache_key_decryptcode=$this->decryptcode_get($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$ams_entrance_user1=$this->tppymemcached->get($cache_key_encrypt);
if(!$ams_entrance_user1) {
$query=$this->db->query($sql);
$amsentranceusers=$query->result_array();
$ams_entrance_user1=$amsentranceusers;
$num_rows=(int)$query->num_rows();
$this->tppymemcached->set($cache_key_encrypt,$ams_entrance_user1,300);
} 
###########cache###############
$dataset['dd']['bookmark_user_sql']=$sql;
$ent_id_user=$ams_entrance_user1['0']['ent_id'];
if($user_id==''){
     $dataset['dd']['bookmark_user']=null;
     $dataset['dd']['bookmark_active']=0;
}else{
     $dataset['dd']['bookmark_user']=$ams_entrance_user1;
     if($ent_id_user==$ent_id){
       $dataset['dd']['bookmark_active']=1;
     }else{
      $dataset['dd']['bookmark_active']=0;
     }
}
############################
      $dataset['dd']['year_config']=$val['year_config'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
      $dataset['dd']['score_history']=$score_history;
      $dataset['dd']['ent_id']=$val['ent_id'];
/*
      $dataset['dd']['01_onet_mi']=$val['01_onet_mi'];
      $dataset['dd']['01_onet_weight']=$val['01_onet_weight'];
      $dataset['dd']['02_onet_min']=$val['02_onet_min'];
      $dataset['dd']['02_onet_weight']=$val['02_onet_weight'];
      $dataset['dd']['03_onet_mint']=$val['03_onet_mint'];
      $dataset['dd']['03_onet_weight']=$val['03_onet_weight'];
      $dataset['dd']['04_onet_min']=$val['04_onet_min'];
      $dataset['dd']['04_onet_weight']=$val['04_onet_weight'];
      $dataset['dd']['05_onet_min']=$val['05_onet_min'];
      $dataset['dd']['05_onet_weight']=$val['05_onet_weight'];
      $dataset['dd']['71_pat1_min']=$val['71_pat1_min'];
      $dataset['dd']['71_pat1_weight']=$val['71_pat1_weight'];
      $dataset['dd']['72_pat2_min']=$val['72_pat2_min'];
      $dataset['dd']['72_pat2_weight']=$val['72_pat2_weight'];
      $dataset['dd']['73_pat3_min']=$val['73_pat3_min'];
      $dataset['dd']['73_pat3_weight']=$val['73_pat3_weight'];
      $dataset['dd']['74_pat4_min']=$val['74_pat4_min'];
      $dataset['dd']['74_pat4_weight']=$val['74_pat4_weight'];
      $dataset['dd']['75_pat5_min']=$val['75_pat5_min'];
      $dataset['dd']['75_pat5_weight']=$val['75_pat5_weight'];
      $dataset['dd']['76_pat6_min']=$val['76_pat6_min'];
      $dataset['dd']['76_pat6_weight']=$val['76_pat6_weight'];
      $dataset['dd']['77_pat71_min']=$val['77_pat71_min'];
      $dataset['dd']['77_pat71_weight']=$val['77_pat71_weight'];
      $dataset['dd']['78_pat72_min']=$val['78_pat72_min'];
      $dataset['dd']['78_pat72_weight']=$val['78_pat72_weight'];
      $dataset['dd']['79_pat73_min']=$val['79_pat73_min'];
      $dataset['dd']['79_pat73_weight']=$val['79_pat73_weight'];
      $dataset['dd']['80_pat74_min']=$val['80_pat74_min'];
      $dataset['dd']['80_pat74_weight']=$val['80_pat74_weight'];
      $dataset['dd']['81_pat75_min']=$val['81_pat75_min'];
      $dataset['dd']['81_pat75_weight']=$val['81_pat75_weight'];
      $dataset['dd']['82_pat76_min']=$val['82_pat76_min'];
      $dataset['dd']['82_pat76_weight']=$val['82_pat76_weight'];
      $dataset['dd']['85_gat_min']=$val['85_gat_min'];
      $dataset['dd']['85_gat_min_part2']=$val['85_gat_min_part2'];
      $dataset['dd']['85_gat_weight']=$val['85_gat_weight'];
      */


 
      
      $dataset['ent']['gpax_min']=$val['gpax_min'];
      $dataset['ent']['gpax_weight']=$val['gpax_weight'];
 
      $d_01_onet_mi=$val['01_onet_mi'];
      $d_01_onet_weight=$val['01_onet_weight'];
      $d_02_onet_min=$val['02_onet_min'];
      $d_02_onet_weight=$val['02_onet_weight'];
      $d_03_onet_mint=$val['03_onet_mint'];
      $d_03_onet_weight=$val['03_onet_weight'];
      $d_04_onet_min=$val['04_onet_min'];
      $d_04_onet_weight=$val['04_onet_weight'];
      $d_05_onet_min=$val['05_onet_min'];
      $d_05_onet_weight=$val['05_onet_weight'];
      $d_71_pat1_min=$val['71_pat1_min'];
      $d_71_pat1_weight=$val['71_pat1_weight'];
      $d_72_pat2_min=$val['72_pat2_min'];
      $d_72_pat2_weight=$val['72_pat2_weight'];
      $d_73_pat3_min=$val['73_pat3_min'];
      $d_73_pat3_weight=$val['73_pat3_weight'];
      $d_74_pat4_min=$val['74_pat4_min'];
      $d_74_pat4_weight=$val['74_pat4_weight'];
      $d_75_pat5_min=$val['75_pat5_min'];
      $d_75_pat5_weight=$val['75_pat5_weight'];
      $d_76_pat6_min=$val['76_pat6_min'];
      $d_76_pat6_weight=$val['76_pat6_weight'];
      $d_77_pat71_min=$val['77_pat71_min'];
      $d_77_pat71_weight=$val['77_pat71_weight'];
      $d_78_pat72_min=$val['78_pat72_min'];
      $d_78_pat72_weight=$val['78_pat72_weight'];
      $d_79_pat73_min=$val['79_pat73_min'];
      $d_79_pat73_weight=$val['79_pat73_weight'];
      $d_80_pat74_min=$val['80_pat74_min'];
      $d_80_pat74_weight=$val['80_pat74_weight'];
      $d_81_pat75_min=$val['81_pat75_min'];
      $d_81_pat75_weight=$val['81_pat75_weight'];
      $d_82_pat76_min=$val['82_pat76_min'];
      $d_82_pat76_weight=$val['82_pat76_weight'];
      $d_85_gat_min=$val['85_gat_min'];
      $d_85_gat_min_part2=$val['85_gat_min_part2'];
      $d_85_gat_weight=$val['85_gat_weight'];
      $d_onet_min_total=$val['onet_min_total'];
      $d_onet_weight_total=$val['onet_weight_total'];
      
      if($d_01_onet_mi>=1){
       $dataset['dd']['onet']['01_onet_mi']=$d_01_onet_mi;
      }if($d_01_onet_weight>=1){
      $dataset['dd']['onet']['01_onet_weight']=$d_01_onet_weight;
      }if($d_02_onet_min>=1){
      $dataset['dd']['onet']['02_onet_min']=$d_02_onet_min;
      }if($d_02_onet_weight>=1){
      $dataset['dd']['onet']['02_onet_weight']=$d_02_onet_weight;
       }if($d_03_onet_mint>=1){
      $dataset['dd']['onet']['03_onet_mint']=$d_03_onet_mint;
       }if($d_03_onet_weight>=1){
      $dataset['dd']['onet']['03_onet_weight']=$d_03_onet_weight;
       }if($d_04_onet_min>=1){
      $dataset['dd']['onet']['04_onet_min']=$d_04_onet_min;
       }if($d_04_onet_weight>=1){
      $dataset['dd']['onet']['04_onet_weight']=$d_04_onet_weight;
       }if($d_05_onet_min>=1){
      $dataset['dd']['onet']['05_onet_min']=$d_05_onet_min;
       }if($d_05_onet_weight>=1){
      $dataset['dd']['onet']['05_onet_weight']=$d_05_onet_weight;
       }if($d_onet_min_total>=1){
      $dataset['dd']['onet']['onet_min_total']=$d_onet_min_total;
       }if($d_onet_weight_total>=1){
      $dataset['dd']['onet']['onet_weight_total']=$d_onet_weight_total;
      }if($d_71_pat1_min>=1){
      $dataset['dd']['pat']['71_pat1_min']=$d_71_pat1_min;
      }if($d_71_pat1_weight>=1){
      $dataset['dd']['pat']['71_pat1_weight']=$d_71_pat1_weight;
      }if($d_72_pat2_min>=1){
      $dataset['dd']['pat']['72_pat2_min']=$d_72_pat2_min;
      }if($d_72_pat2_weight>=1){
      $dataset['dd']['pat']['72_pat2_weight']=$d_72_pat2_weight;
      }if($d_73_pat3_min>=1){
      $dataset['dd']['pat']['73_pat3_min']=$d_73_pat3_min;
      }if($d_73_pat3_weight>=1){
      $dataset['dd']['pat']['73_pat3_weight']=$d_73_pat3_weight;
      }if($d_74_pat4_min>=1){
      $dataset['dd']['pat']['74_pat4_min']=$d_74_pat4_min;
      }if($d_74_pat4_weight>=1){
      $dataset['dd']['pat']['74_pat4_weight']=$d_74_pat4_weight;
      }if($d_75_pat5_min>=1){
      $dataset['dd']['pat']['75_pat5_min']=$d_75_pat5_min;
      }if($d_75_pat5_weight>=1){
      $dataset['dd']['pat']['75_pat5_weight']=$d_75_pat5_weight;
      }if($d_76_pat6_min>=1){
      $dataset['dd']['pat']['76_pat6_min']=$d_76_pat6_min;
      }if($d_76_pat6_weight>=1){
      $dataset['dd']['pat']['76_pat6_weight']=$d_76_pat6_weight;
      }if($d_77_pat71_min>=1){
      $dataset['dd']['pat']['77_pat71_min']=$d_77_pat71_min;
      }if($d_77_pat71_weight>=1){
      $dataset['dd']['pat']['77_pat71_weight']=$d_77_pat71_weight;
      }if($d_78_pat72_min>=1){
      $dataset['dd']['pat']['78_pat72_min']=$d_78_pat72_min;
      }if($d_78_pat72_weight>=1){
      $dataset['dd']['pat']['78_pat72_weight']=$d_78_pat72_weight;
      }if($d_79_pat73_min>=1){
      $dataset['dd']['pat']['79_pat73_min']=$d_79_pat73_min;
      }if($d_79_pat73_weight>=1){
      $dataset['dd']['pat']['79_pat73_weight']=$d_79_pat73_weight;
      }if($d_80_pat74_min>=1){
      $dataset['dd']['pat']['80_pat74_min']=$d_80_pat74_min;
      }if($d_80_pat74_weight>=1){
      $dataset['dd']['pat']['80_pat74_weight']=$d_80_pat74_weight;
      }if($d_81_pat75_min>=1){
      $dataset['dd']['pat']['81_pat75_min']=$d_81_pat75_min;
      }if($d_81_pat75_weight>=1){
      $dataset['dd']['pat']['81_pat75_weight']=$d_81_pat75_weight;
      }if($d_82_pat76_min>=1){
      $dataset['dd']['pat']['82_pat76_min']=$d_82_pat76_min;
      }if($d_82_pat76_weight>=1){
      $dataset['dd']['pat']['82_pat76_weight']=$d_82_pat76_weight;
      }if($d_85_gat_min>=1){
      $dataset['dd']['gat']['85_gat_min']=$d_85_gat_min;
      }if($d_85_gat_min_part2>=1){
      $dataset['dd']['gat']['85_gat_min_part2']=$d_85_gat_min_part2;
      }if($d_85_gat_weight>=1){
      $dataset['dd']['gat']['85_gat_weight']=$d_85_gat_weight;
      }
       
      $dataset['dd']['branch_id']=$val['branch_id'];
      $dataset['dd']['branch_name']=$val['branch_name'];
      $dataset['dd']['config']=$val['config'];
      $dataset['dd']['university_name']=$val['university_name'];
      $dataset['dd']['faculty_id']=$val['faculty_id'];
      $dataset['dd']['faculty_name']=$val['faculty_name'];
      $dataset['dd']['gpax_min']=$val['gpax_min'];
      $dataset['dd']['gpax_weight']=$val['gpax_weight'];
      $dataset['dd']['major_code']=$val['major_code'];
      $dataset['dd']['major_remark']=$val['major_remark'];

      $sharecode=$val['receive_amount_sharecode'];
      $dataset['dd']['receive_amount_sharecode']= $sharecode;
      if($sharecode==''){
        $dataset['dd']['sharecode_data']=null;
        $dataset['dd']['receive_amount']=(int)$val['receive_amount'];
        $dataset['dd']['sharecode_detail']=null;
        $dataset['dd']['sharecode_set']=null;
         $dataset['dd']['sharecode_msg']=null;
      }else{
       $majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
       $majorcodeshare_data=$majorcodeshare['data']['0'];
       $receiveamount=(int)$majorcodeshare_data['receive_amount'];
       $dataset['dd']['sharecode_data']=$majorcodeshare_data;
       $dataset['dd']['receive_amount']=$receiveamount;
       $sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
       $sharecode_data=$sharecode['data'];
       $dataset['dd']['detail']=$sharecode_data;
       $sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
       $dataset['dd']['sharecode_set']=$sharecode_data_set;
      $faculty_name_all=$val['faculty_name'];
       $dataset['dd']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
      }
      $dataset['dd']['score_max']=$val['score_max'];
      $dataset['dd']['score_min']=$val['score_min'];
      #$dataset['dd']['special_remark']=$val['special_remark'];
$special_remark=$val['special_remark'];
$search='--';$replace='';$string=$special_remark;
$special_remark=str_replace($search,$replace,$string);
$dataset['dd']['special_remark']=$special_remark;
      $dataset['dd']['lastupdate']=$val['lastupdate'];
      $datasetamss[]=$dataset['dd'];
  }
}else{ $datasetamss=null;}
#######################	
if($tab==null ||$tab=='admission'){ }



$admission_count=(int)count($datasetamss);
     if($tab==null ||$tab=='admission'){
          $arr_branch4['branch']['admission']=$datasetamss;
          $arr_branch4['branch']['admission_count']=$admission_count;
     }elseif($tab=='directapply'){
          $arr_branch4['branch']['admission']=null;
          $admission_count=0;
          $arr_branch4['branch']['admission_count']=$admission_count;
     }
          ##########################admission######################################
###################################admission#######################################
#######################################################################################
          $arr['arr']['year']=$year;
          if($direct_apply_count==0 && $admission_count==0){}else{
               $arrbranch4[]=$arr_branch4['branch'];
               }

  }
}else{$arrbranch4=null;}
$arr_faculty3['faculty']['branch']=$arrbranch4;
##########################
$arr_faculty3['faculty']['faculty_id']=$faculty_id;
$arr_faculty3['faculty']['faculty_name']=$v3['faculty_name'];
##########################
$arrfaculty3[]=$arr_faculty3['faculty'];
}
}else{$arrfaculty3=null;}
######################################
$faculty_from_branch_set=implode(',', array_map(function($arrfaculty3){ return $arrfaculty3['faculty_id'];}, $arrfaculty3 ));
     $arr['arr']['faculty_set']=$faculty_from_branch_set;
     $arr['arr']['faculty']=$arrfaculty3;
     $arr['arr']['faculty_count']=(int)count($arrfaculty3);
 }
}
    # echo '<hr><pre> $arr=>'; print_r($arr); echo '</pre>';die();
##########******branch2*****###########
##########################
##########################
}
########################
if($direct_apply_count==0 && $admission_count==0){}else{
$arr_university[]=$arr['arr'];
}



 }
}else{$arr_university=null;}

$data=array('university'=>$arr_university,
            'university_count'=>count($arr_university),
            #'university_sql'=>$universityname_sql,
	 	  'user_id'=>$user_id,
            'year'=>$year,
             );
if($data){
$this->response(array('header'=>array('title'=>'top ams entrance universitygroup adminsion',
							   'message'=>'Success',
							   'status'=>true,
							   'code'=>200), 
							   'data'=> $data),200);
}else{
$this->response(array('header'=>array('title'=>'top ams entrance universitygroup adminsion',
							   'message'=>'Error',
							   'status'=>true, 
							   'code'=>404), 
							   'data'=> $data),404);
}
}
public function topuniversitycount_get(){
$user_id=@$this->input->get('user_id');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$delekey=@$this->input->get('delekey');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey=null;}
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
     
$data=array('university_count'=>0,'university'=> Null);
$this->response(array('header'=>array('title'=>'top ent count list',
									  'message'=>'Error user_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=>$data),404);
Die();
}
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
/*
$universityname=$this->model_trainer->get_ams_entrance_user_by_ams_university_form_user_id($user_id,1,$delekey);
$universityname_data=$universityname['data'];
$universityname_data_count=$universityname['data_count'];
$universityname_sql=$universityname['sql'];
*/

#######################################
$topuniversityapi= file_get_contents('http://www.trueplookpanya.com/api/admissiontrainer/topuniversityentranceuseruserid?user_id='.$user_id.'&year='.$year.'&delekey=1');
$topuniversityde=json_decode($topuniversityapi, true);
$universityname_data=$topuniversityde['data']['university'];
$universityname_data_count=$topuniversityde['data']['university_count'];


//echo'<hr><pre>$universityname=>';print_r($universityname);echo'<pre><hr>'; Die();
if($universityname_data==null){
$data=array('university_count'=>0,
            'admission_count'=>0,
            'directapply_count'=>0,
            'branch_count'=>0,
            //'university'=>$universityname_data,
            );
$this->response(array('header'=>array('title'=>'top ent count list ',
							   'message'=>'Success',
							   'status'=>true,
							   'code'=>200), 
							   'data'=> $data),200);
                                      die();
}   

$topuniversityapitab2= file_get_contents('http://www.trueplookpanya.com/api/admissiontrainer/universitylistgroup34listgroupidamstop?year='.$year.'&user_id='.$user_id.'&delekey=1');
$topuniversitytab2de=json_decode($topuniversityapitab2, true);
$topuniversitytab2=$topuniversitytab2de['data']['list'];
$top_university_data_count=$topuniversitytab2de['data']['list_count'];


##############################
$topdirectapply= file_get_contents('http://www.trueplookpanya.com/api/admissiontrainer/topuniversityentranceuseruserid?user_id='.$user_id.'&year='.$year.'&tab=directapply&delekey=1');
$topdirectapplyde=json_decode($topdirectapply, true);
$directapply_count_all=$topdirectapplyde['data']['amsset_count'];

$directapply_count_bookmarks=$this->model_trainer->get_count_directapply_user_id_year($user_id,$year,$delekey);
$directapply_count_bookmark=$directapply_count_bookmarks['data']['0']['count_directapply'];
##############################
/*
$sql2="select count(huser.u_id) countbookmark from  ams_entrance_user as huser 
where  huser.directapply_faculty_id=0 and huser.user_id=$user_id and huser.year=$year ";
$query2=$this->db->query($sql2);
$data_admissions= $query2->result_array();
$admission_count=$data_admissions['0']['countbookmark'];
*/
$admission_bookmarks=$this->model_trainer->get_count_admission_user_id_year($user_id,$year,$delekey);
$admission_count=$admission_bookmarks['data']['0']['countbookmark'];
##############################
if($universityname_data){
$data=array('university_count'=>(int)$universityname_data_count,
            'admission_count'=>(int)$admission_count,
            'directapply_count'=>(int)$directapply_count_bookmark,
            'directapply_count_all'=>(int)$directapply_count_all,
            'branch_count'=>(int)$top_university_data_count,
            #'admission_bookmarks'=>$admission_bookmarks,
            #'directapply_count_bookmarks'=>$directapply_count_bookmarks,
            );
$this->response(array('header'=>array('title'=>'top ent count list ',
							   'message'=>'Success',
							   'status'=>true,
							   'code'=>200), 
							   'data'=> $data),200);
}else{
$data=array('university_count'=>0,'university'=> Null);
$this->response(array('header'=>array('title'=>'top ent count list',
							   'message'=>'Error',
							   'status'=>true, 
							   'code'=>404), 
							   'data'=>$data),404);
}
}
public function amsuniversitycount_get(){
$user_id=@$this->input->get('user_id');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
/*
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
$universitygroup4=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);
$university_group4=$universitygroup4['0'];
$group_type4_name=$university_group4['u_group_name'];
*/
$delekey=@$this->input->get('delekey');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey=null;}
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$data=array('university_count'=>0,'university'=> Null);
$this->response(array('header'=>array('title'=>'ent count list',
									  'message'=>'Error user_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=>$data),404);
Die();
}
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
$groupid=$u_group_id_type4;
#######################################
$un= file_get_contents('http://www.trueplookpanya.com/api/admissiontrainer/universitylistgroup4listgroupidams?u_group_id_type3='.$u_group_id_type3.'&u_group_id_type4='.$u_group_id_type4.'&year='.$year.'&user_id='.$user_id.'&delekey=1');
$undcode=json_decode($un, true);
$universityname_data=$undcode['data'];
#######################################
$branchapi=file_get_contents('http://www.trueplookpanya.com/api/admissiontrainer/universitylistgroup34listgroupidams?u_group_id_type3='.$u_group_id_type3.'&u_group_id_type4='.$u_group_id_type4.'&year='.$year.'&user_id='.$user_id.'&delekey=1');
$branch_dc=json_decode($branchapi, true);
$branch_count=$branch_dc['data']['list_count'];
$list_count=$undcode['data']['list_count'];
$admission_count=$undcode['data']['ent_count'];
$directapply_count=$undcode['data']['direct_apply_count'];
#######################################
if($universityname_data==null){
$data=array('university_count'=>0,'admission_count'=>0,'directapply_count'=>0,'branch_count'=>0,);
$this->response(array('header'=>array('title'=>'ent count list',
							   'message'=>'Success',
							   'status'=>true,
							   'code'=>200), 
							   'data'=> $data),200);
die();
}
if($universityname_data){
$un=$undcode['data']['list_count'];
$data=array('university_count'=>$un,
            'admission_count'=>$admission_count,
            'directapply_count'=>$directapply_count,
            'branch_count'=>$branch_count,
            );
$this->response(array('header'=>array('title'=>'ent count list ',
							   'message'=>'Success',
							   'status'=>true,
							   'code'=>200), 
							   'data'=> $data,
                                      ),200);
die();
}else{
$data=array('university_count'=>0,'university'=> Null);
$this->response(array('header'=>array('title'=>'ent count list',
							   'message'=>'Error',
							   'status'=>true, 
							   'code'=>404), 
							   'data'=>$data),404);
die();
}
}
##############
public function universitylistgrouplist_get(){
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');
$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$year=@$this->input->get('year');
if($year==''){
 $yearn=date('Y');
 $year=$yearn-1;
}

$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
######################################
######################################
######################################
if($u_group_id==''){
$ams_university_u_parento=$this->model_trainer->get_ams_university_u_parent_ido($delekey);
$num=0;
foreach($ams_university_u_parento as $k=>$v){
$id=$v['u_id'];
$num++;
}
$result_set_u=implode(',', array_map(function($v){ return $v['u_id'];}, $ams_university_u_parento ));
$sqlr1="select  u_id,u_name from ams_university  where  u_parent_id in($result_set_u) order by u_id asc";
$cache_key="get_ams_university_u_parent_wherein_".$sqlr1;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
##########################
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
#########################
$datain=$this->tppymemcached->get($cachekeyencoded);
if(!$datain){
$datain=$this->db->query($sqlr1)->result_array();
$this->tppymemcached->set($cachekeyencoded,$datain,3600);
}
$num2=0;
foreach($datain as $k=>$v){
$id=$v['u_id'];
$num2++;
}
$result_set_uin=implode(',', array_map(function($v){ return $v['u_id'];}, $datain ));
#echo'<hr><pre> $result_set_uin=>';print_r($result_set_uin);echo'<pre> <hr>'; die();
$sqlr2="select  u_id,u_name from ams_university  where  u_parent_id in($result_set_uin) order by u_id asc";
$cache_key="get_ams_university_u_parent_wherein2_".$sqlr2;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded2= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
##########################
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded2); }
#########################
$rs=$this->tppymemcached->get($cachekeyencoded2);
if($rs){$datain2=$rs;}else{   
$datain2=$this->db->query($sqlr2)->result_array();
$this->tppymemcached->set($cachekeyencoded2,$datain2,3600);
}
$num3=0;
foreach($datain2 as $k=>$v){
$id2=$v2['u_id'];
$num3++;
}
$result_set_uin2=implode(',', array_map(function($v2){ return $v2['u_id'];}, $datain2 ));
$group_by='group by university_id';
$group_name='university data';
$group_short_name='university data';
$sql5="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set_uin2)  
$group_by order by university_id asc,m.idx asc";




$cache_key="get_ams_university_group_map_list_group_in_group01_".$sql;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
$group_in_data5=$this->tppymemcached->get($cachekeyencoded);
if(!$group_in_data5){
$group_in_data5=$this->db->query($sql5)->result_array();
$this->tppymemcached->set($cachekeyencoded,$data,3600);
}
$arr_result5 = array();
if (is_array($group_in_data5)) {
  foreach ($group_in_data5 as $key5 => $val5) {
   #################
$university_id=$val5['university_id'];
if($university_id==''){}else{
################
$branch_id=$val5['branch_id'];
$data5 = array();
$data5['d']['university_parent_id']=$val5['university_parent_id'];
$data5['d']['university_id']=$val5['university_id'];
$data5['d']['branch_id']=$branch_id;
$faculty_id=$val5['faculty_id'];
$data5['d']['faculty_id']=$faculty_id;

#############################################################
#############################################################
#############################################################
$sqlr1="select(select u_id from  ams_university where u_id=map.u_parent_id)as u_id1
,map.u_id as u_id2
,(select u_name from  ams_university where u_id=map.u_parent_id)as u_name1
,map.u_name as u_name2
from  ams_university as map where map.u_parent_id=$faculty_id";
$cache_key="get_amsuniversityuparent_u_id2_".$sqlr1;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
##########################
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
#########################
$datain=$this->tppymemcached->get($cachekeyencoded);
if(!$datain){
$datain=$this->db->query($sqlr1)->result_array();
$this->tppymemcached->set($cachekeyencoded,$datain,3600);
}
$group_in_data_count=count($datain);
$datars=array();
foreach($datain as $key => $value) {
 $datars[$key]['u_id2']=$value['u_id2'];
#################
}
$i=0;
foreach($datars as $k=>$v){
 $id=$v['u_id2'];
 $i++;
}
$result_set=implode(',', array_map(function($v){ return $v['u_id2'];}, $datars ));
$data5['d']['group_faculty_id']=$result_set;
#############################################################
#############################################################
#############################################################
$sql_ams5="select un.u_id,ent.*
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,h.score_min,h.score_max 
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!='' and ent.u_id in ($result_set)  and ent.year_config=$year";
$cache_key="get_amsentrancelistfacultybranch_year_no1_".$sql;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
##########################
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
#########################
$data_ams5=$this->tppymemcached->get($cachekeyencoded);
if(!$data_ams5){
$data_ams5=$this->db->query($sql_ams5)->result_array();
$this->tppymemcached->set($cachekeyencoded,$data_ams5,3600);
}

#################
$datasetamss = array();
if (is_array($data_ams5)) {
  foreach ($data_ams5 as $keydatas => $val) {
 $ent_id=$val['ent_id'];
 $dataset['d'][$keydatas]['year_config']=$val['year_config'];
 $dataset['d'][$keydatas]['major_remark']=$val['major_remark'];
 $branch_id=$val['u_id'];
 $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
 $dataset['d'][$keydatas]['score_history']=$score_history;
 $dataset['d'][$keydatas]['ent_id']=$val['ent_id'];
 #################
 $dataset['d'][$keydatas]['01_onet_mi']=$val['01_onet_mi'];
 $dataset['d'][$keydatas]['01_onet_weight']=$val['01_onet_weight'];
 $dataset['d'][$keydatas]['02_onet_min']=$val['02_onet_min'];
 $dataset['d'][$keydatas]['02_onet_weight']=$val['02_onet_weight'];
 $dataset['d'][$keydatas]['03_onet_mint']=$val['03_onet_mint'];
 $dataset['d'][$keydatas]['03_onet_weight']=$val['03_onet_weight'];
 $dataset['d'][$keydatas]['04_onet_min']=$val['04_onet_min'];
 $dataset['d'][$keydatas]['04_onet_weight']=$val['04_onet_weight'];
 $dataset['d'][$keydatas]['05_onet_min']=$val['05_onet_min'];
 $dataset['d'][$keydatas]['05_onet_weight']=$val['05_onet_weight'];
 $dataset['d'][$keydatas]['71_pat1_min']=$val['71_pat1_min'];
 $dataset['d'][$keydatas]['71_pat1_weight']=$val['71_pat1_weight'];
 $dataset['d'][$keydatas]['72_pat2_min']=$val['72_pat2_min'];
 $dataset['d'][$keydatas]['72_pat2_weight']=$val['72_pat2_weight'];
 $dataset['d'][$keydatas]['73_pat3_min']=$val['73_pat3_min'];
 $dataset['d'][$keydatas]['73_pat3_weight']=$val['73_pat3_weight'];
 $dataset['d'][$keydatas]['74_pat4_min']=$val['74_pat4_min'];
 $dataset['d'][$keydatas]['74_pat4_weight']=$val['74_pat4_weight'];
 $dataset['d'][$keydatas]['75_pat5_min']=$val['75_pat5_min'];
 $dataset['d'][$keydatas]['75_pat5_weight']=$val['75_pat5_weight'];
 $dataset['d'][$keydatas]['76_pat6_min']=$val['76_pat6_min'];
 $dataset['d'][$keydatas]['76_pat6_weight']=$val['76_pat6_weight'];
 $dataset['d'][$keydatas]['77_pat71_min']=$val['77_pat71_min'];
 $dataset['d'][$keydatas]['77_pat71_weight']=$val['77_pat71_weight'];
 $dataset['d'][$keydatas]['78_pat72_min']=$val['78_pat72_min'];
 $dataset['d'][$keydatas]['78_pat72_weight']=$val['78_pat72_weight'];
 $dataset['d'][$keydatas]['79_pat73_min']=$val['79_pat73_min'];
 $dataset['d'][$keydatas]['79_pat73_weight']=$val['79_pat73_weight'];
 $dataset['d'][$keydatas]['80_pat74_min']=$val['80_pat74_min'];
 $dataset['d'][$keydatas]['80_pat74_weight']=$val['80_pat74_weight'];
 $dataset['d'][$keydatas]['81_pat75_min']=$val['81_pat75_min'];
 $dataset['d'][$keydatas]['81_pat75_weight']=$val['81_pat75_weight'];
 $dataset['d'][$keydatas]['82_pat76_min']=$val['82_pat76_min'];
 $dataset['d'][$keydatas]['82_pat76_weight']=$val['82_pat76_weight'];
 $dataset['d'][$keydatas]['85_gat_min']=$val['85_gat_min'];
 $dataset['d'][$keydatas]['85_gat_min_part2']=$val['85_gat_min_part2'];
 $dataset['d'][$keydatas]['85_gat_weight']=$val['85_gat_weight'];
 $dataset['d'][$keydatas]['branch_id']=$val['branch_id'];
 $dataset['d'][$keydatas]['branch_name']=$val['branch_name'];
 $dataset['d'][$keydatas]['config']=$val['config'];
 $dataset['d'][$keydatas]['faculty_id']=$val['faculty_id'];
 $dataset['d'][$keydatas]['faculty_name']=$val['faculty_name'];
 $dataset['d'][$keydatas]['gpax_min']=$val['gpax_min'];
 $dataset['d'][$keydatas]['gpax_weight']=$val['gpax_weight'];
 $dataset['d'][$keydatas]['major_code']=$val['major_code'];
 $dataset['d'][$keydatas]['major_remark']=$val['major_remark'];
 $dataset['d'][$keydatas]['onet_min_total']=$val['onet_min_total'];
 $dataset['d'][$keydatas]['onet_weight_total']=$val['onet_weight_total'];
 $dataset['d'][$keydatas]['receive_amount']=$val['receive_amount'];
 $dataset['d'][$keydatas]['receive_amount_sharecode']=$val['receive_amount_sharecode'];
 $dataset['d'][$keydatas]['score_max']=$val['score_max'];
 $dataset['d'][$keydatas]['score_min']=$val['score_min'];
 $dataset['d'][$keydatas]['special_remark']=$val['special_remark'];
 $dataset['d'][$keydatas]['university_id']=$val['university_id'];
 $dataset['d'][$keydatas]['university_name']=$val['university_name'];
 $dataset['d'][$keydatas]['lastupdate']=$val['lastupdate'];
 $datasetamss[]=$dataset['d'];
  }
}else{ $datasetamss=null; }
$group_ams_data_count=count($datasetamss);
$data5['d']['ams_entrance_sql']=$sql_ams5;
$data5['d']['ams_entrance_count']=$group_ams_data_count;
$data5['d']['ams_entrance_list']=$datasetamss;
#################
#############################################################
#############################################################
#############################################################
$data5['d']['map_id']=$val5['map_id'];
$data5['d']['group_type']=$val5['u_group_type'];
$data5['d']['university_name']=$val5['university_name'];
$thumbnail=$val5['thumbnail'];
$data5['d']['thumbnail']='http://static.trueplookpanya.com/trueplookpanya/'.$thumbnail;
$data5['d']['branch_name']=$val5['branch_name'];
$data5['d']['faculty_name']=$val5['faculty_name'];
$data5['d']['group_name']=$val5['group_name'];
$data5['d']['group_description']=$val5['group_description'];
if($active_id==$branch_id){
    $data5['d']['active']=1;
 }else{
    $data5['d']['active']=0;
 }
 $arr_result5[] = $data5['d'];
################ 
}

   }#################
 }else{ $arr_result5=null; }
$list5=$arr_result5;
$list_count=count($list5);
#################################  
				  $this->response(array('header'=>array(
										'title'=>'University list group4',
										'message'=>'Data is University group4',
										'status'=>true, 
										'code'=>200), 
'list'=>$list5,
'count'=>$list_count,
'group_name'=>$group_name,
'group_short_name'=>$group_short_name,
'sql'=>$sql5,
//'data_set'=>$result_set_u,
//'data_set_in'=>$result_set_uin,
//'data_set_in2'=>$result_set_uin2,
										//'data'=>$ams_university_u_parento
)
,200);
Die();
}
######################################
######################################
######################################
$university_group3_lists=$this->model_trainer->get_ams_university_group_m2m_typy3($u_group_id,$delekey);
//echo'<hr><pre> university_group3_list=>';print_r($university_group3_list);echo'<pre><hr>';  die();
$university_group3_list=$university_group3_lists['data'];
$sql1=$university_group3_lists['sql'];
$university_group3_list_count=count($university_group3_list);
if($university_group3_list_count<=0){
				  $this->response(array('header'=>array(
										'title'=>'University list group4',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
				}
#echo'<hr><pre> $university_group3_list=>';print_r($university_group3_list);echo'<pre> <hr>'; 
$datars=array();
 foreach($university_group3_list as $key => $value) {
   $datars[$key]['u_group_id_destination']=$value['u_group_id_destination'];
 #################
}
$num=0;
foreach($datars as $k=>$v){
 $id=$v['u_group_id_destination'];
 $num++;
}
$result_set=implode(',', array_map(function($v){ return $v['u_group_id_destination'];}, $datars ));
$groupby=@$this->input->get('group_by');
if($u_group_id!=''){
 $sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set)  
group by university_id  order by university_id asc,m.idx asc";

}else{
 $sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id																																			
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
left join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
left join  ams_university  as amsu on amsu.u_id=m.u_id
where  amsu.u_name!=''  and g.u_group_id in ($result_set)  
group by university_id  order by university_id asc,m.idx asc";
}
$cache_key="get_ams_university_group_map_list_group_in_".$sql;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
$group_in_data=$this->tppymemcached->get($cachekeyencoded);
if(!$group_in_data){ 
$group_in_data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cachekeyencoded,$data,600);
}
###############################
$arr_result4 = array();
if (is_array($group_in_data)) {
  foreach ($group_in_data as $key4 => $val4) {
   #################
$university_id=$val4['university_id'];
if($university_id==''){}else{
################
$branch_id=$val4['branch_id'];
$data4 = array();
$data4['d']['university_parent_id']=$val4['university_parent_id'];
$data4['d']['university_id']=$val4['university_id'];
$data4['d']['branch_id']=$branch_id;
$faculty_id=$val4['faculty_id'];
$data4['d']['faculty_id']=$faculty_id;

#############################################################
#############################################################
#############################################################
$sqlrh2="select(select u_id from  ams_university where u_id=map.u_parent_id)as u_id1
,map.u_id as u_id2
,(select u_name from  ams_university where u_id=map.u_parent_id)as u_name1
,map.u_name as u_name2
from  ams_university as map where map.u_parent_id=$faculty_id";
$cache_key="get_amsuniversityuparent_u_id2_".$sqlrh2;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
##########################
if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
#########################
$datainrs=$this->tppymemcached->get($cachekeyencoded);
if(!$datainrs){
$datainrs=$this->db->query($sqlrh2)->result_array();
$this->tppymemcached->set($cachekeyencoded,$datainrs,600);
}
$group_in_data_count=count($datainrs);
$datars2=array();
foreach($datainrs as $k2eys1 => $v2alues1) {
 $datars2[$k2eys1]['u_id2']=$v2alues1['u_id2'];
#################
}
$i=0;
foreach($datars2 as $k2=>$v2){
 $id=$v2['u_id2'];
 $i++;
}
$result_set2s=implode(',', array_map(function($v2){ return $v2['u_id2'];}, $datars2 ));
$data4['d']['group_faculty_id']=$result_set2s;
#############################################################
#############################################################
#############################################################
$sql_ams="select un.u_id,ent.*
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,h.score_min,h.score_max 
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!='' and ent.u_id in ($result_set2s)  and ent.year_config=$year";
$cache_key="get_amsentrancelistfacultybranch_year_".$faculty_id.$sql;
$cryptKey  = 'tyyptruemd5';
$cachekeyencoded= base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
##########################

if($delekey==''){}else{ $this->tppymemcached->delete($cachekeyencoded); }
#########################
$data_ams=$this->tppymemcached->get($cachekeyencoded);
if(!$data_ams){ 
$data_ams=$this->db->query($sql_ams)->result_array();
$this->tppymemcached->set($cachekeyencoded,$data_ams,600);
}

//$data_ams=$this->db->query($sql_ams)->result_array();
$group_ams_data_count=count($data_ams);
$data4['d']['ams_entrance_sql']=$sql_ams;
$data4['d']['ams_entrance_count']=$group_ams_data_count;
#################
$datasetams = array();
if (is_array($data_ams)) {
  foreach ($data_ams as $keydata => $value) {
 $ent_id=$value['ent_id'];
 $data_set['d'][$keydata]['year_config']=$value['year_config'];
 $data_set['d'][$keydata]['major_remark']=$value['major_remark'];
 $branch_id=$value['u_id'];
 $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
 $data_set['d'][$keydata]['score_history']=$score_history;
 $data_set['d'][$keydata]['ent_id']=$value['ent_id'];
 #################
 $data_set['d'][$keydata]['01_onet_mi']=$value['01_onet_mi'];
 $data_set['d'][$keydata]['01_onet_weight']=$value['01_onet_weight'];
 $data_set['d'][$keydata]['02_onet_min']=$value['02_onet_min'];
 $data_set['d'][$keydata]['02_onet_weight']=$value['02_onet_weight'];
 $data_set['d'][$keydata]['03_onet_mint']=$value['03_onet_mint'];
 $data_set['d'][$keydata]['03_onet_weight']=$value['03_onet_weight'];
 $data_set['d'][$keydata]['04_onet_min']=$value['04_onet_min'];
 $data_set['d'][$keydata]['04_onet_weight']=$value['04_onet_weight'];
 $data_set['d'][$keydata]['05_onet_min']=$value['05_onet_min'];
 $data_set['d'][$keydata]['05_onet_weight']=$value['05_onet_weight'];
 $data_set['d'][$keydata]['71_pat1_min']=$value['71_pat1_min'];
 $data_set['d'][$keydata]['71_pat1_weight']=$value['71_pat1_weight'];
 $data_set['d'][$keydata]['72_pat2_min']=$value['72_pat2_min'];
 $data_set['d'][$keydata]['72_pat2_weight']=$value['72_pat2_weight'];
 $data_set['d'][$keydata]['73_pat3_min']=$value['73_pat3_min'];
 $data_set['d'][$keydata]['73_pat3_weight']=$value['73_pat3_weight'];
 $data_set['d'][$keydata]['74_pat4_min']=$value['74_pat4_min'];
 $data_set['d'][$keydata]['74_pat4_weight']=$value['74_pat4_weight'];
 $data_set['d'][$keydata]['75_pat5_min']=$value['75_pat5_min'];
 $data_set['d'][$keydata]['75_pat5_weight']=$value['75_pat5_weight'];
 $data_set['d'][$keydata]['76_pat6_min']=$value['76_pat6_min'];
 $data_set['d'][$keydata]['76_pat6_weight']=$value['76_pat6_weight'];
 $data_set['d'][$keydata]['77_pat71_min']=$value['77_pat71_min'];
 $data_set['d'][$keydata]['77_pat71_weight']=$value['77_pat71_weight'];
 $data_set['d'][$keydata]['78_pat72_min']=$value['78_pat72_min'];
 $data_set['d'][$keydata]['78_pat72_weight']=$value['78_pat72_weight'];
 $data_set['d'][$keydata]['79_pat73_min']=$value['79_pat73_min'];
 $data_set['d'][$keydata]['79_pat73_weight']=$value['79_pat73_weight'];
 $data_set['d'][$keydata]['80_pat74_min']=$value['80_pat74_min'];
 $data_set['d'][$keydata]['80_pat74_weight']=$value['80_pat74_weight'];
 $data_set['d'][$keydata]['81_pat75_min']=$value['81_pat75_min'];
 $data_set['d'][$keydata]['81_pat75_weight']=$value['81_pat75_weight'];
 $data_set['d'][$keydata]['82_pat76_min']=$value['82_pat76_min'];
 $data_set['d'][$keydata]['82_pat76_weight']=$value['82_pat76_weight'];
 $data_set['d'][$keydata]['85_gat_min']=$value['85_gat_min'];
 $data_set['d'][$keydata]['85_gat_min_part2']=$value['85_gat_min_part2'];
 $data_set['d'][$keydata]['85_gat_weight']=$value['85_gat_weight'];
 $data_set['d'][$keydata]['branch_id']=$value['branch_id'];
 $data_set['d'][$keydata]['branch_name']=$value['branch_name'];
 $data_set['d'][$keydata]['config']=$value['config'];
 $data_set['d'][$keydata]['faculty_id']=$value['faculty_id'];
 $data_set['d'][$keydata]['faculty_name']=$value['faculty_name'];
 $data_set['d'][$keydata]['gpax_min']=$value['gpax_min'];
 $data_set['d'][$keydata]['gpax_weight']=$value['gpax_weight'];
 $data_set['d'][$keydata]['major_code']=$value['major_code'];
 $data_set['d'][$keydata]['major_remark']=$value['major_remark'];
 $data_set['d'][$keydata]['onet_min_total']=$value['onet_min_total'];
 $data_set['d'][$keydata]['onet_weight_total']=$value['onet_weight_total'];
 $data_set['d'][$keydata]['receive_amount']=$value['receive_amount'];
 $data_set['d'][$keydata]['receive_amount_sharecode']=$value['receive_amount_sharecode'];
 $data_set['d'][$keydata]['score_max']=$value['score_max'];
 $data_set['d'][$keydata]['score_min']=$value['score_min'];
 $data_set['d'][$keydata]['special_remark']=$value['special_remark'];
 $data_set['d'][$keydata]['university_id']=$value['university_id'];
 $data_set['d'][$keydata]['university_name']=$value['university_name'];
 $data_set['d'][$keydata]['lastupdate']=$value['lastupdate'];
 $datasetams=$data_set['d'];
  }
}else{ $datasetams=null; }
$data4['d']['ams_entrance_list']=$datasetams;
#################
#############################################################
#############################################################
#############################################################
$data4['d']['map_id']=$val4['map_id'];
$data4['d']['group_type']=$val4['u_group_type'];
$data4['d']['university_name']=$val4['university_name'];
$thumbnail=$val4['thumbnail'];
$data4['d']['thumbnail']='http://static.trueplookpanya.com/trueplookpanya/'.$thumbnail;
$data4['d']['branch_name']=$val4['branch_name'];
$data4['d']['faculty_name']=$val4['faculty_name'];
$data4['d']['group_name']=$val4['group_name'];
$data4['d']['group_description']=$val4['group_description'];
if($active_id==$branch_id){
    $data4['d']['active']=1;
 }else{
    $data4['d']['active']=0;
 }
 $arr_result4[] = $data4['d'];
################ 
}
   }#################
 }else{ $arr_result4=null; }
$list=$arr_result4;
$group_in_data_count=count($list4);
##############################
$group_type3name=$this->model_trainer->get_group_type3_id_list($u_group_id,$delekey);
$group_type3_name=$group_type3name['0'];
$data=array(
										'count'=>$group_in_data_count,
'list_set'=>$result_set,
										'group_name'=>$group_type3_name['u_group_name'],
'group_short_name'=>$group_type3_name['short_description'],
'list'=>$list,
'sql2'=>$sql,
'sql1'=>$sql1,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function universityss_get(){
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');
$active_id=@$this->input->get('active_id');
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}

if($u_group_id==''){
 #############################################
  $sql="select distinct (select (select w.u_name 
from  ams_university as w where w.u_id=w1.u_parent_id)as university_name from  ams_university as w1 where w1.u_id=(select u_parent_id 
from  ams_university where u_id=map.u_id))as university_name
,(select (select uu.u_name from  ams_university as uu where uu.u_id=u1.u_parent_id)as university_name from  ams_university as u1 where u1.u_id=map.u_id)as faculty_name
,(select u_name from  ams_university where u_id=map.u_id)as branch_name
,(select u_group_name from ams_university_group where u_group_id=map.u_group_id)as u_group_name
,(select (select w.u_id from  ams_university as w where w.u_id=w1.u_parent_id)as university_name 
from  ams_university as w1 where w1.u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as university_id
,map.*,amsu.u_parent_id
from ams_university_group_map as map  
left join  ams_university as amsu on amsu.u_id=map.u_id
where map.u_group_id!=''
group by university_id  order by university_id asc ";
 $cache_key="get_ams_university_group_map_list_group_in_".$sql;
if($delekey=='delkey'){ $this->tppymemcached->delete($cache_key); }
$rs=$this->tppymemcached->get($cache_key);
if($rs){$ams_university_data=$rs;}else{   
$ams_university_data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key,$data,3600);
}
#################
 $arr_result = array();
 foreach($ams_university_data as $key => $value) {
 $branch_name=$value['branch_name'];
 $faculty_name=$value['faculty_name'];
 $idx=$value['idx'];
 $u_group_id=$value['u_group_id'];
 $u_group_name=$value['u_group_name'];
 $u_id=$value['u_id'];
 $u_parent_id=$value['u_parent_id'];
 $university_id=$value['university_id'];
 $university_name=$value['university_name'];
 if($university_name==''){}else{
  if($active_id==$u_group_id){
  $datars['d']['active']=1;
  }else{
   $datars['d']['active']=0;
  }
 $datars['d']['u_group_id']=$u_group_id;
 $datars['d']['u_group_name']=$u_group_name;
 $datars['d']['branch_name']=$branch_name;
 $datars['d']['faculty_name']=$faculty_name;
 $datars['d']['idx']=$idx;
 $datars['d']['u_id']=$u_id;
 $datars['d']['u_parent_id']=$u_parent_id;
 $datars['d']['university_id']=$university_id;
 $datars['d']['university_name']=$university_name;
 $arr_result[] = $datars['d'];
 }
#################
}

////////////////
$group_in_data_count=count($arr_result);
$data=array(
										'count'=>$group_in_data_count,
'sql'=>$sql,
'list_set'=>null,
										'list'=>$arr_result,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
 #############################################
 Die();
}
$university_group3_lists=$this->model_trainer->get_ams_university_group_m2m_typy3($u_group_id,$delekey);
$university_group3_list=$university_group3_lists['data'];
$university_group3_sql=$university_group3_lists['sql'];
$university_group3_list_count=count($university_group3_list);
if($university_group3_list_count<=0){
				  $this->response(array('header'=>array(
										'title'=>'University list group4',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
				}
#echo'<hr><pre> $university_group3_list=>';print_r($university_group3_list);echo'<pre> <hr>'; 
$datars=array();
 foreach($university_group3_list as $key => $value) {
 //$idx=$value['idx'];
 //$datars[$key]['idx']=$idx;
 //$datars[$key]['u_group_id_source']=$value['u_group_id_source'];
 $datars[$key]['u_group_id_destination']=$value['u_group_id_destination'];
 #################
}
//$array_m1pre=explode(',', $datars);
$num=0;
foreach($datars as $k=>$v){
 $id=$v['u_group_id_destination'];
 $num++;
}
$result_set=implode(',', array_map(function($v){ return $v['u_group_id_destination'];}, $datars ));

$groupby=@$this->input->get('group_by');
if($groupby=='university'){
  $sql="select distinct (select (select w.u_name 
from  ams_university as w where w.u_id=w1.u_parent_id)as university_name from  ams_university as w1 where w1.u_id=(select u_parent_id 
from  ams_university where u_id=map.u_id))as university_name
,(select (select uu.u_name from  ams_university as uu where uu.u_id=u1.u_parent_id)as university_name from  ams_university as u1 where u1.u_id=map.u_id)as faculty_name
,(select u_name from  ams_university where u_id=map.u_id)as branch_name
,(select u_group_name from ams_university_group where u_group_id=map.u_group_id)as u_group_name
,(select (select w.u_id from  ams_university as w where w.u_id=w1.u_parent_id)as university_name 
from  ams_university as w1 where w1.u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as university_id
,map.*,amsu.u_parent_id
from ams_university_group_map as map  
left join  ams_university as amsu on amsu.u_id=map.u_id
where map.u_group_id!=''
group by university_id  order by idx asc ";

/*
 $sql="select 
(select (select w.u_id from  ams_university as w where w.u_id=w1.u_parent_id)as university_name from  ams_university as w1 where w1.u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as university_id
,(select (select w.u_name from  ams_university as w where w.u_id=w1.u_parent_id)as university_name from  ams_university as w1 where w1.u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as university_name
,(select (select uu.u_name from  ams_university as uu where uu.u_id=u1.u_parent_id)as university_name from  ams_university as u1 where u1.u_id=map.u_id)as faculty_name
,(select u_name from  ams_university where u_id=map.u_id)as branch_name
,(select u_group_name from ams_university_group where u_group_id=map.u_group_id)as u_group_name
,(select u_parent_id from  ams_university where u_id=map.u_id)as u_parent_id
,map.*
from ams_university_group_map as map where map.u_group_id in ($result_set)group by university_id  order by idx asc";
*/

}elseif($groupby=='branch'){
 $sql="select 
(select (select w.u_id from  ams_university as w where w.u_id=w1.u_parent_id)as university_name from  ams_university as w1 where w1.u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as university_id
,(select (select w.u_name from  ams_university as w where w.u_id=w1.u_parent_id)as university_name from  ams_university as w1 where w1.u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as university_name
,(select (select uu.u_name from  ams_university as uu where uu.u_id=u1.u_parent_id)as university_name from  ams_university as u1 where u1.u_id=map.u_id)as faculty_name
,(select u_name from  ams_university where u_id=map.u_id)as branch_name
,(select u_group_name from ams_university_group where u_group_id=map.u_group_id)as u_group_name
,(select u_parent_id from  ams_university where u_id=map.u_id)as u_parent_id
,map.*
from ams_university_group_map as map where map.u_group_id in ($result_set) group by u_group_id  order by idx asc";
}else{
$sql="select 
(select (select w.u_id from  ams_university as w where w.u_id=w1.u_parent_id)as university_name from  ams_university as w1 where w1.u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as university_id
,(select (select w.u_name from  ams_university as w where w.u_id=w1.u_parent_id)as university_name from  ams_university as w1 where w1.u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as university_name
,(select (select uu.u_name from  ams_university as uu where uu.u_id=u1.u_parent_id)as university_name from  ams_university as u1 where u1.u_id=map.u_id)as faculty_name
,(select u_name from  ams_university where u_id=map.u_id)as branch_name
,(select u_group_name from ams_university_group where u_group_id=map.u_group_id)as u_group_name
,(select u_parent_id from  ams_university where u_id=map.u_id)as u_parent_id
,map.*
from ams_university_group_map as map where map.u_group_id in ($result_set) order by university_id asc";
}
#echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>';  die();
$cache_key="get_ams_university_group_map_list_group_in_".$sql;
if($delekey=='delkey'){ $this->tppymemcached->delete($cache_key); }
$rs=$this->tppymemcached->get($cache_key);
if($rs){$group_in_data=$rs;}else{   
$group_in_data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key,$data,3600);
}
////////////////
$group_in_data_count=count($group_in_data);
$data=array(
										'count'=>$group_in_data_count,
'sql'=>$sql,
'list_set'=>$result_set,
										'list'=>$group_in_data,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function branchbyuniversityyear_get(){
$this->load->model('admissiontrainer_model', 'model_trainer');
$university_id=$this->input->get('university_id');
if($university_id==''){
				  $this->response(array('header'=>array(
										'title'=>'branchbyuniversityyear',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
										Die();
				}
$year=@$this->input->get('year');
if($year==''){
 $year=(int)date('Y');
 $year=(int)$year+543;
 $year=(int)$year-1;
}else{
$year=(int)$year;
}
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$faculty_list=$this->model_trainer->get_ams_faculty_list($university_id,$delekey);
$faculty_listcount=count($faculty_list);
if($faculty_listcount<=0){
				  $this->response(array('header'=>array(
										'title'=>'Amsentrance list faculty branch',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
				}

 #echo'<hr><pre> $faculty_list=>';print_r($faculty_list);echo'<pre> <hr>';die();
$datars=array();
 foreach($faculty_list as $key => $value) {
$faculty_id=(int)$value['faculty_id'];
$sqlif="select u_id from ams_university where u_id=$faculty_id";
$cache_key="get_amsentrancelistfacultybranch_ams_university_id_".$sqlif;
if($delekey=='delkey'){ $this->tppymemcached->delete($cache_key); }
$if_data=$this->tppymemcached->get($cache_key);
if(!$if_data){
 $if_data=$this->db->query($sqlif)->result_array();
 $this->tppymemcached->set($cache_key,$if_data,600);
   }
  #echo'<pre> $if_data=>';print_r($if_data);echo'<pre>';
  #echo'<pre> $faculty_id=>';print_r($faculty_id);echo'<pre>'; #Die();
  $if_data_id=$if_data['0']['u_id'] ;
//echo'<pre> if_data_id=>';print_r($if_data_id);echo'<pre>'; 
//echo'<pre>  faculty_id=>';print_r($faculty_id);echo'<pre>'; 
 if($if_data_id==$faculty_id){
//echo'<pre> $faculty_id=>';print_r($faculty_id);echo'<pre> ';
   $array_rs[$key]['faculty_id']=$faculty_id;
   // $datars[$key]['faculty_name']=$value['faculty_name'];
   $datars[]=$array_rs[$key];
 }
}


//$array_m1pre=explode(',', $datars);
$num=0;
foreach($datars as $k=>$v){
 $id=$v['faculty_id'];
 $num++;
}
$result_set=implode(',', array_map(function($v){ return $v['faculty_id'];}, $datars ));
#echo'<pre>  result_set=>';print_r($result_set);echo'<pre>'; Die();

$sql="select un.u_id,ent.*
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,h.score_min,h.score_max 
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!='' and un.u_parent_id in ($result_set)  and ent.year_config=$year"; 
#echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>';die();
$cache_key="get_amsentrancelistfacultybranch_als_".$sql;
if($delekey=='delkey'){ $this->tppymemcached->delete($cache_key); }
$group_in_data=$this->tppymemcached->get($cache_key);
if(!$group_in_data){
$group_in_data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key,$group_in_data,3600);
}
////////////////
$group_in_data_count=count($group_in_data);
#echo'<hr><pre> $group_in_data=>';print_r($group_in_data);echo'<pre><hr>';  die();
#################
$dataset = array();
foreach ($group_in_data as $keydata => $value) {
  $ent_id=$value['ent_id'];
  $data_set['d'][$keydata]['year_config']=$value['year_config'];
  $data_set['d'][$keydata]['major_remark']=$value['major_remark'];
  $branch_id=$value['u_id'];
  $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
  $data_set['d'][$keydata]['score_history']=$score_history;
  $data_set['d'][$keydata]['ent_id']=$value['ent_id'];
  #################
  $data_set['d'][$keydata]['01_onet_mi']=$value['01_onet_mi'];
$data_set['d'][$keydata]['01_onet_weight']=$value['01_onet_weight'];
$data_set['d'][$keydata]['02_onet_min']=$value['02_onet_min'];
$data_set['d'][$keydata]['02_onet_weight']=$value['02_onet_weight'];
$data_set['d'][$keydata]['03_onet_mint']=$value['03_onet_mint'];
$data_set['d'][$keydata]['03_onet_weight']=$value['03_onet_weight'];
$data_set['d'][$keydata]['04_onet_min']=$value['04_onet_min'];
$data_set['d'][$keydata]['04_onet_weight']=$value['04_onet_weight'];
$data_set['d'][$keydata]['05_onet_min']=$value['05_onet_min'];
$data_set['d'][$keydata]['05_onet_weight']=$value['05_onet_weight'];
$data_set['d'][$keydata]['71_pat1_min']=$value['71_pat1_min'];
$data_set['d'][$keydata]['71_pat1_weight']=$value['71_pat1_weight'];
$data_set['d'][$keydata]['72_pat2_min']=$value['72_pat2_min'];
$data_set['d'][$keydata]['72_pat2_weight']=$value['72_pat2_weight'];
$data_set['d'][$keydata]['73_pat3_min']=$value['73_pat3_min'];
$data_set['d'][$keydata]['73_pat3_weight']=$value['73_pat3_weight'];
$data_set['d'][$keydata]['74_pat4_min']=$value['74_pat4_min'];
$data_set['d'][$keydata]['74_pat4_weight']=$value['74_pat4_weight'];
$data_set['d'][$keydata]['75_pat5_min']=$value['75_pat5_min'];
$data_set['d'][$keydata]['75_pat5_weight']=$value['75_pat5_weight'];
$data_set['d'][$keydata]['76_pat6_min']=$value['76_pat6_min'];
$data_set['d'][$keydata]['76_pat6_weight']=$value['76_pat6_weight'];
$data_set['d'][$keydata]['77_pat71_min']=$value['77_pat71_min'];
$data_set['d'][$keydata]['77_pat71_weight']=$value['77_pat71_weight'];
$data_set['d'][$keydata]['78_pat72_min']=$value['78_pat72_min'];
$data_set['d'][$keydata]['78_pat72_weight']=$value['78_pat72_weight'];
$data_set['d'][$keydata]['79_pat73_min']=$value['79_pat73_min'];
$data_set['d'][$keydata]['79_pat73_weight']=$value['79_pat73_weight'];
$data_set['d'][$keydata]['80_pat74_min']=$value['80_pat74_min'];
$data_set['d'][$keydata]['80_pat74_weight']=$value['80_pat74_weight'];
$data_set['d'][$keydata]['81_pat75_min']=$value['81_pat75_min'];
$data_set['d'][$keydata]['81_pat75_weight']=$value['81_pat75_weight'];
$data_set['d'][$keydata]['82_pat76_min']=$value['82_pat76_min'];
$data_set['d'][$keydata]['82_pat76_weight']=$value['82_pat76_weight'];
$data_set['d'][$keydata]['85_gat_min']=$value['85_gat_min'];
$data_set['d'][$keydata]['85_gat_min_part2']=$value['85_gat_min_part2'];
$data_set['d'][$keydata]['85_gat_weight']=$value['85_gat_weight'];
$data_set['d'][$keydata]['branch_id']=$value['branch_id'];
$data_set['d'][$keydata]['branch_name']=$value['branch_name'];
$data_set['d'][$keydata]['config']=$value['config'];
$data_set['d'][$keydata]['faculty_id']=$value['faculty_id'];
$data_set['d'][$keydata]['faculty_name']=$value['faculty_name'];
$data_set['d'][$keydata]['gpax_min']=$value['gpax_min'];
$data_set['d'][$keydata]['gpax_weight']=$value['gpax_weight'];
$data_set['d'][$keydata]['major_code']=$value['major_code'];
$data_set['d'][$keydata]['major_remark']=$value['major_remark'];
$data_set['d'][$keydata]['onet_min_total']=$value['onet_min_total'];
$data_set['d'][$keydata]['onet_weight_total']=$value['onet_weight_total'];
$data_set['d'][$keydata]['receive_amount']=$value['receive_amount'];
$data_set['d'][$keydata]['receive_amount_sharecode']=$value['receive_amount_sharecode'];
$data_set['d'][$keydata]['score_max']=$value['score_max'];
$data_set['d'][$keydata]['score_min']=$value['score_min'];
$data_set['d'][$keydata]['special_remark']=$value['special_remark'];
$data_set['d'][$keydata]['university_id']=$value['university_id'];
$data_set['d'][$keydata]['university_name']=$value['university_name'];
$data_set['d'][$keydata]['lastupdate']=$value['lastupdate'];
$dataset[]=$data_set['d'];
}
#echo'<pre>dataset=>';print_r($dataset);echo'<pre>';die();
$university_list=$this->model_trainer->get_ams_university_by_u_id($university_id,$delekey);
$data=array('sql'=>$sql,
										'count'=>$group_in_data_count,
'university_list'=>$university_list,
'list_set'=>$result_set,
										'list'=>$dataset,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'amsentrancelistfacultybranch',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'amsentrancelistfacultybranch',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}    
#############
}
public function groupamsentrancelistfacultybranchyear_get(){
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');
$group_id=@$this->input->get('group_id');
if($group_id==''){
 #####################
   if($u_group_id<=0){
  				  $this->response(array('header'=>array(
  										'title'=>'Group  faculty branch year',
  										'message'=>'Data is null',
  										'status'=>true, 
  										'code'=>201), 
  										'data'=> Null),201);
  Die();
  				}
########################
}

$year=@$this->input->get('year');
if($year==''){
 $year=(int)date('Y');
 $year=(int)$year+543;
 $year=(int)$year-1;
}else{
$year=(int)$year;
}
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
if($group_id==''){

#########################################
$university_group3_lists=$this->model_trainer->get_ams_university_group_m2m_typy3($u_group_id,$delekey);
$university_group3_list=$university_group3_lists['data'];
$u_sql=$university_group3_lists['sql'];
$university_group3_list_count=count($university_group3_list);
if($university_group3_list_count<=0){
				  $this->response(array('header'=>array(
										'title'=>'Group  faculty branch year',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
				}
#echo'<hr><pre> $university_group3_list=>';print_r($university_group3_list);echo'<pre> <hr>'; 
$datars=array();
 foreach($university_group3_list as $key => $value) {
 //$idx=$value['idx'];
 //$datars[$key]['idx']=$idx;
 //$datars[$key]['u_group_id_source']=$value['u_group_id_source'];
 $datars[$key]['u_group_id_destination']=$value['u_group_id_destination'];
 #################
}

//$array_m1pre=explode(',', $datars);
$num=0;
foreach($datars as $k=>$v){
 $id=$v['u_group_id_destination'];
 $num++;
}
$result_set=implode(',', array_map(function($v){ return $v['u_group_id_destination'];}, $datars ));
 
}else{
 $result_set=$group_id;
  }
 #########################################
$sql="select un.u_id,ent.*
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,h.score_min,h.score_max 
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!='' and un.u_parent_id in ($result_set)  and ent.year_config=$year"; 
#echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>';  die();
$cache_key="get_amsentrancelistfacultybranch_".$sql;
if($delekey=='delkey'){ $this->tppymemcached->delete($cache_key); }
$rs=$this->tppymemcached->get($cache_key);
if($rs){$group_in_data=$rs;}else{   
$group_in_data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key,$data,3600);
}
////////////////
$group_in_data_count=count($group_in_data);
#################
$dataset = array();
foreach ($group_in_data as $keydata => $value) {
  $ent_id=$value['ent_id'];
  $data_set['d'][$keydata]['year_config']=$value['year_config'];
  $data_set['d'][$keydata]['major_remark']=$value['major_remark'];
  $branch_id=$value['u_id'];
  $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
  $data_set['d'][$keydata]['score_history']=$score_history;
  $data_set['d'][$keydata]['ent_id']=$value['ent_id'];

#################
$data_set['d'][$keydata]['01_onet_mi']=$value['01_onet_mi'];
$data_set['d'][$keydata]['01_onet_weight']=$value['01_onet_weight'];
$data_set['d'][$keydata]['02_onet_min']=$value['02_onet_min'];
$data_set['d'][$keydata]['02_onet_weight']=$value['02_onet_weight'];
$data_set['d'][$keydata]['03_onet_mint']=$value['03_onet_mint'];
$data_set['d'][$keydata]['03_onet_weight']=$value['03_onet_weight'];
$data_set['d'][$keydata]['04_onet_min']=$value['04_onet_min'];
$data_set['d'][$keydata]['04_onet_weight']=$value['04_onet_weight'];
$data_set['d'][$keydata]['05_onet_min']=$value['05_onet_min'];
$data_set['d'][$keydata]['05_onet_weight']=$value['05_onet_weight'];
$data_set['d'][$keydata]['71_pat1_min']=$value['71_pat1_min'];
$data_set['d'][$keydata]['71_pat1_weight']=$value['71_pat1_weight'];
$data_set['d'][$keydata]['72_pat2_min']=$value['72_pat2_min'];
$data_set['d'][$keydata]['72_pat2_weight']=$value['72_pat2_weight'];
$data_set['d'][$keydata]['73_pat3_min']=$value['73_pat3_min'];
$data_set['d'][$keydata]['73_pat3_weight']=$value['73_pat3_weight'];
$data_set['d'][$keydata]['74_pat4_min']=$value['74_pat4_min'];
$data_set['d'][$keydata]['74_pat4_weight']=$value['74_pat4_weight'];
$data_set['d'][$keydata]['75_pat5_min']=$value['75_pat5_min'];
$data_set['d'][$keydata]['75_pat5_weight']=$value['75_pat5_weight'];
$data_set['d'][$keydata]['76_pat6_min']=$value['76_pat6_min'];
$data_set['d'][$keydata]['76_pat6_weight']=$value['76_pat6_weight'];
$data_set['d'][$keydata]['77_pat71_min']=$value['77_pat71_min'];
$data_set['d'][$keydata]['77_pat71_weight']=$value['77_pat71_weight'];
$data_set['d'][$keydata]['78_pat72_min']=$value['78_pat72_min'];
$data_set['d'][$keydata]['78_pat72_weight']=$value['78_pat72_weight'];
$data_set['d'][$keydata]['79_pat73_min']=$value['79_pat73_min'];
$data_set['d'][$keydata]['79_pat73_weight']=$value['79_pat73_weight'];
$data_set['d'][$keydata]['80_pat74_min']=$value['80_pat74_min'];
$data_set['d'][$keydata]['80_pat74_weight']=$value['80_pat74_weight'];
$data_set['d'][$keydata]['81_pat75_min']=$value['81_pat75_min'];
$data_set['d'][$keydata]['81_pat75_weight']=$value['81_pat75_weight'];
$data_set['d'][$keydata]['82_pat76_min']=$value['82_pat76_min'];
$data_set['d'][$keydata]['82_pat76_weight']=$value['82_pat76_weight'];
$data_set['d'][$keydata]['85_gat_min']=$value['85_gat_min'];
$data_set['d'][$keydata]['85_gat_min_part2']=$value['85_gat_min_part2'];
$data_set['d'][$keydata]['85_gat_weight']=$value['85_gat_weight'];
$data_set['d'][$keydata]['branch_id']=$value['branch_id'];
$data_set['d'][$keydata]['branch_name']=$value['branch_name'];
$data_set['d'][$keydata]['config']=$value['config'];
$data_set['d'][$keydata]['faculty_id']=$value['faculty_id'];
$data_set['d'][$keydata]['faculty_name']=$value['faculty_name'];
$data_set['d'][$keydata]['gpax_min']=$value['gpax_min'];
$data_set['d'][$keydata]['gpax_weight']=$value['gpax_weight'];
$data_set['d'][$keydata]['major_code']=$value['major_code'];
$data_set['d'][$keydata]['major_remark']=$value['major_remark'];
$data_set['d'][$keydata]['onet_min_total']=$value['onet_min_total'];
$data_set['d'][$keydata]['onet_weight_total']=$value['onet_weight_total'];
$data_set['d'][$keydata]['receive_amount']=$value['receive_amount'];
$data_set['d'][$keydata]['receive_amount_sharecode']=$value['receive_amount_sharecode'];
$data_set['d'][$keydata]['score_max']=$value['score_max'];
$data_set['d'][$keydata]['score_min']=$value['score_min'];
$data_set['d'][$keydata]['special_remark']=$value['special_remark'];
$data_set['d'][$keydata]['university_id']=$value['university_id'];
$data_set['d'][$keydata]['university_name']=$value['university_name'];
$data_set['d'][$keydata]['lastupdate']=$value['lastupdate'];
$dataset=$data_set['d'];
}
$data=array('sql'=>$sql,
  'sql1'=>$u_sql,
										  'count'=>$group_in_data_count,
  'list_set'=>$result_set,
										  'list'=>$dataset,
);
if($data){
				$this->response(array('header'=>array(
										'title'=>'amsentrancelistfacultybranch',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'amsentrancelistfacultybranch',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}    
#############
}
public function university_get(){
$year=@$this->input->get('year');
if($year==''){
 $year=(int)date('Y');
 $year=(int)$year+543;
 $year=(int)$year-1;
}else{
$year=(int)$year;
}
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_group3_list=$this->model_trainer->get_ams_entrance_group_by_university($year,$delekey);
$university_group3_list_count=count($university_group3_list);
$group_in_data_count=count($group_in_data);
$data=array('count'=>$university_group3_list_count,
										  'list'=>$university_group3_list,
 );
if($data){
				$this->response(array('header'=>array(
										'title'=>'admissions trainer university',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function branch_get(){
$year=@$this->input->get('year');
if($year==''){
 $year=(int)date('Y');
 $year=(int)$year+543;
 $year=(int)$year-1;
}else{
$year=(int)$year;
}
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_group3_list=$this->model_trainer->get_ams_entrance_group_by_branch($year,$delekey);
$university_group3_list_count=count($university_group3_list);
$group_in_data_count=count($group_in_data);
$data=array('count'=>$university_group3_list_count,
										  'list'=>$university_group3_list,
 );
if($data){
				$this->response(array('header'=>array(
										'title'=>'admissions trainer branch',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function universitylistallams_get(){
$this->load->model('admissiontrainer_model', 'model_trainer');
$delekey=$this->input->get('delekey');
$year=$this->input->get('year');
if($year==''){
 $yeardc=date('Y');
 $yearac=(int)$yeardc+543;
 $year=$yearac;
}
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_list=$this->model_trainer->get_ams_university_list_all($delekey);
$count=count($university_list);
if($count<=0){
				  $this->response(array('header'=>array(
										'title'=>'University Ams list',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
				} 
#####################
$arr_university= array();
foreach ($university_list as $k=> $v) {
################**university**##################
   $u_id=$v['u_id'];
   $u_name=$v['u_name'];
   $thumbnail=$v['thumbnail_url'];
   $u_group_id=$v['u_group_id'];
   $group_name=$v['group_name'];
   if($group_name==null){
    $group_name='ยังไม่ได้จัดกลุ่มมหาลัย';
   }
if($u_name==''||$u_name=='0'){}else{
################**university if**##################
   $data_set['d'][$k]['university_id']=$u_id;
   $data_set['d'][$k]['university_name']=$u_name;
   $data_set['d'][$k]['thumbnail']=$thumbnail;
   $data_set['d'][$k]['u_group_id']=$u_group_id;
   $data_set['d'][$k]['group_name']=$group_name;
###########################
###########******faculty******##########
$faculty=$this->model_trainer->get_ams_university_by_u_parent_id($u_id,$delekey);
if($faculty==null || $faculty==''){
$data_set['d'][$k]['set_faculty']=null;
$data_set['d'][$k]['faculty_count']=null;
$data_set['d'][$k]['faculty']=null;
}else{
$faculty_datars_na=array();
foreach($faculty as $keyfna => $valuefna) {$datarsf['faculty_id']=$valuefna['u_id'];$faculty_datars_na[] = $datarsf;}
$num=0;foreach($faculty_datars_na as $kfna=>$vfna){$idfna=$vfna['faculty_id'];$num++;}
$faculty_set=implode(',', array_map(function($vfna){ return $vfna['faculty_id'];}, $faculty_datars_na ));
$data_set['d'][$k]['set_faculty'] = $faculty_set;

#######################################
/*
$arr_faculty = array();
if(is_array($faculty)){
   foreach ($faculty as $keyl => $val) {
$arr = array();
$faculty_id=$val['u_id'];
$arr['f'][$keyl]['faculty_id'] = $faculty_id;
$arr['f'][$keyl]['faculty_name'] = $val['u_name'];
$arr['f'][$keyl]['faculty_description'] = $val['short_description'];
############################
$branch_data=$this->model_trainer->get_ams_university_by_u_parent_id($faculty_id,$delekey);
if($branch_data==''){ $arr['f'][$keyl]['branch']=null; }else{
$arr_branch = array();
###########################
if(is_array($branch_data)){
   foreach ($branch_data as $keyll => $vll) {
$arr_ss = array();
$branch_u_id=$vll['u_id'];
$arr_ss['l'][$keyll]['branch_id'] = $branch_u_id;
$arr_ss['l'][$keyll]['branch_name'] = $vll['u_name'];
$arr_ss['l'][$keyll]['branch_lescription'] = $vll['short_lescription'];
$arr_branch[]=$arr_ss['l'][$keyll];
   }
 }
###########################
$arr['f'][$keyl]['branch']=$arr_branch;
}
############################
$arr_faculty[]=$arr['f'][$keyl];
  }
}
$data_set['d'][$k]['faculty_count']=count($arr_faculty);
$data_set['d'][$k]['faculty']=$arr_faculty;
*/
#######################################

}
###########******faculty******##########
if($faculty==null || $faculty==''){
$data_set['d'][$k]['set_branch']=null;
$data_set['d'][$k]['data_ams_entrance']=null;
$data_ams_entrance_count=null;
$data_set['d'][$k]['data_ams_entrance_count']=$data_ams_entrance_count;
$data_set['d'][$k]['set_ams_entrance']=null;
//$data_set['d'][$k]['set_ams_entrance_sql']=null;
}else{
$data_fac=$this->model_trainer->get_ams_university_dataset($faculty_set,$delekey);
 if($data_fac==null || $data_fac==''){
$data_set['d'][$k]['set_branch'] = 'null';
}else{
 
$branch_datars_na2=array();
foreach($data_fac as $key_branch2 => $value_branch2) {
$value_branch2['branch_id']=$value_branch2['branch_id'];
$branch_datars_na2[] = $value_branch2;
}$num=0;foreach($branch_datars_na2 as $kb=>$vb){$id_b=$vb['branch_id'];$num++;}
$branch_set=implode(',', array_map(function($vb){ return $vb['branch_id'];}, $branch_datars_na2 ));
$data_set['d'][$k]['set_branch'] = $branch_set;

############################
$sql="select(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_id
,(select u_parent_id from  ams_university where u_id=ent.u_id)as faculty_id
,(select u_id from  ams_university where u_id=ent.u_id)as branch_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id))as faculty_name
,(select u_name from  ams_university where u_id=ent.u_id)as branch_name
,ent.*,h.score_min,h.score_max
from  ams_entrance as ent
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.u_id in ($branch_set)and ent.year_config=$year order by ent.ent_id asc";
$cache_key="get_ams_university_set_ams_entrance_".$sql;
$cryptKey='tyyptruemd5';
$cachekeyencoded=base64_encode(mcrypt_encrypt( MCRYPT_RIJNDAEL_256,md5($cryptKey),$cache_key,MCRYPT_MODE_CBC,md5(md5($cryptKey))));
#$this->tppymemcached->delete($cachekeyencoded);
##########################
if($delekey==''){}else{$this->tppymemcached->delete($cachekeyencoded);}
############cache#############
$data_ams_entrance=$this->tppymemcached->get($cachekeyencoded);
if(!$data_ams_entrance){ 
#########################
$rsdata= $this->db->query($sql);
$data_ams_entrance=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
############################
$this->tppymemcached->set($cachekeyencoded,$data_ams_entrance,3600);
}
############cache#############
$dataset = array();
foreach ($data_ams_entrance as $keyns => $value_n1) {
  $ent_id=$value_n1['ent_id'];
  $data_set_ns['h'][$keyns]['year_config']=$value_n1['year_config'];
  $data_set_ns['h'][$keyns]['major_remark']=$value_n1['major_remark'];
  $branch_id=$value_n1['u_id'];
  $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
  $data_set_ns['h'][$keyns]['score_history']=$score_history;
  $data_set_ns['h'][$keyns]['ent_id']=$value_n1['ent_id'];
#################
/*
$data_set_ns['h'][$keyns]['01_onet_mi']=$value_n1['01_onet_mi'];
$data_set_ns['h'][$keyns]['01_onet_weight']=$value_n1['01_onet_weight'];
$data_set_ns['h'][$keyns]['02_onet_min']=$value_n1['02_onet_min'];
$data_set_ns['h'][$keyns]['02_onet_weight']=$value_n1['02_onet_weight'];
$data_set_ns['h'][$keyns]['03_onet_mint']=$value_n1['03_onet_mint'];
$data_set_ns['h'][$keyns]['03_onet_weight']=$value_n1['03_onet_weight'];
$data_set_ns['h'][$keyns]['04_onet_min']=$value_n1['04_onet_min'];
$data_set_ns['h'][$keyns]['04_onet_weight']=$value_n1['04_onet_weight'];
$data_set_ns['h'][$keyns]['05_onet_min']=$value_n1['05_onet_min'];
$data_set_ns['h'][$keyns]['05_onet_weight']=$value_n1['05_onet_weight'];
$data_set_ns['h'][$keyns]['71_pat1_min']=$value_n1['71_pat1_min'];
$data_set_ns['h'][$keyns]['71_pat1_weight']=$value_n1['71_pat1_weight'];
$data_set_ns['h'][$keyns]['72_pat2_min']=$value_n1['72_pat2_min'];
$data_set_ns['h'][$keyns]['72_pat2_weight']=$value_n1['72_pat2_weight'];
$data_set_ns['h'][$keyns]['73_pat3_min']=$value_n1['73_pat3_min'];
$data_set_ns['h'][$keyns]['73_pat3_weight']=$value_n1['73_pat3_weight'];
$data_set_ns['h'][$keyns]['74_pat4_min']=$value_n1['74_pat4_min'];
$data_set_ns['h'][$keyns]['74_pat4_weight']=$value_n1['74_pat4_weight'];
$data_set_ns['h'][$keyns]['75_pat5_min']=$value_n1['75_pat5_min'];
$data_set_ns['h'][$keyns]['75_pat5_weight']=$value_n1['75_pat5_weight'];
$data_set_ns['h'][$keyns]['76_pat6_min']=$value_n1['76_pat6_min'];
$data_set_ns['h'][$keyns]['76_pat6_weight']=$value_n1['76_pat6_weight'];
$data_set_ns['h'][$keyns]['77_pat71_min']=$value_n1['77_pat71_min'];
$data_set_ns['h'][$keyns]['77_pat71_weight']=$value_n1['77_pat71_weight'];
$data_set_ns['h'][$keyns]['78_pat72_min']=$value_n1['78_pat72_min'];
$data_set_ns['h'][$keyns]['78_pat72_weight']=$value_n1['78_pat72_weight'];
$data_set_ns['h'][$keyns]['79_pat73_min']=$value_n1['79_pat73_min'];
$data_set_ns['h'][$keyns]['79_pat73_weight']=$value_n1['79_pat73_weight'];
$data_set_ns['h'][$keyns]['80_pat74_min']=$value_n1['80_pat74_min'];
$data_set_ns['h'][$keyns]['80_pat74_weight']=$value_n1['80_pat74_weight'];
$data_set_ns['h'][$keyns]['81_pat75_min']=$value_n1['81_pat75_min'];
$data_set_ns['h'][$keyns]['81_pat75_weight']=$value_n1['81_pat75_weight'];
$data_set_ns['h'][$keyns]['82_pat76_min']=$value_n1['82_pat76_min'];
$data_set_ns['h'][$keyns]['82_pat76_weight']=$value_n1['82_pat76_weight'];
$data_set_ns['h'][$keyns]['85_gat_min']=$value_n1['85_gat_min'];
$data_set_ns['h'][$keyns]['85_gat_min_part2']=$value_n1['85_gat_min_part2'];
$data_set_ns['h'][$keyns]['85_gat_weight']=$value_n1['85_gat_weight'];
*/
$data_set_ns['h'][$keyns]['branch_id']=$value_n1['branch_id'];
$data_set_ns['h'][$keyns]['branch_name']=$value_n1['branch_name'];
$data_set_ns['h'][$keyns]['config']=$value_n1['config'];
$data_set_ns['h'][$keyns]['faculty_id']=$value_n1['faculty_id'];
$data_set_ns['h'][$keyns]['faculty_name']=$value_n1['faculty_name'];
$data_set_ns['h'][$keyns]['gpax_min']=$value_n1['gpax_min'];
$data_set_ns['h'][$keyns]['gpax_weight']=$value_n1['gpax_weight'];
$data_set_ns['h'][$keyns]['major_code']=$value_n1['major_code'];
$data_set_ns['h'][$keyns]['onet_min_total']=$value_n1['onet_min_total'];
$data_set_ns['h'][$keyns]['onet_weight_total']=$value_n1['onet_weight_total'];
$data_set_ns['h'][$keyns]['receive_amount']=$value_n1['receive_amount'];
$data_set_ns['h'][$keyns]['receive_amount_sharecode']=$value_n1['receive_amount_sharecode'];
$data_set_ns['h'][$keyns]['score_max']=$value_n1['score_max'];
$data_set_ns['h'][$keyns]['score_min']=$value_n1['score_min'];
$data_set_ns['h'][$keyns]['special_remark']=$value_n1['special_remark'];
$data_set_ns['h'][$keyns]['university_id']=$value_n1['university_id'];
$data_set_ns['h'][$keyns]['university_name']=$value_n1['university_name'];
$data_set_ns['h'][$keyns]['lastupdate']=$value_n1['lastupdate'];
$dataset=$data_set_ns['h'];
}
$data_set['d'][$k]['data_ams_entrance']=$dataset;
$data_ams_entrance_count=count($dataset);
$data_set['d'][$k]['data_ams_entrance_count']=$data_ams_entrance_count;
//$data_set['d'][$k]['set_ams_entrance_sql']=$sql;
$branch_set_ams_na=array();
foreach($data_ams_entrance as $kn => $va) {$dn['branch_id']=$va['branch_id'];$branch_set_ams_na[] = $dn;}
$num=0;foreach($branch_set_ams_na as $knna=>$vnna){$idna=$vnna['branch_id'];$num++;}
$branchsetams=implode(',', array_map(function($vnna){ return $vnna['branch_id'];}, $branch_set_ams_na ));
$data_set['d'][$k]['set_ams_entrance']=$branchsetams;
}
###########################
$arr_university[]=$data_set['d'][$k];
}
################**university if**##################
}

}################**university**##################
$data=array('count'=>count($arr_university),
										  'list'=>$arr_university,
  'year'=>$year
  );
if($data){
				$this->response(array('header'=>array(
										'title'=>'University Ams list',
										'message'=>'Success',
										'status'=>true,
										'count'=>$university_list_count,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
}
#############
}
public function universitylistyearamsfaculty_get(){
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$year=@$this->input->get('year');
if($year==''){
$year=(int)date('Y');
$year=(int)$year+543;
$year=(int)$year-1;
}else{$year=(int)$year;}
//if(is_internal()){}
if(is_internal()){//echo'<hr><pre>$ams=>';print_r($ams);echo'</pre>';die();
}
$university_list=$this->model_trainer->get_ams_university_list_all($year,$delekey);
#echo '<hr><pre>   university_list=>'; print_r($university_list); echo '</pre>';die();
$arr_result = array();
if (is_array($university_list)) {
foreach ($university_list as $key => $v) {
$arr = array();
	########################
	$u_id=(int)$v['u_id'];
	$u_group_id=(int)$v['u_group_id'];
	$u_name=$v['u_name'];
	$group_name=$v['group_name'];
	$faculty_count=(int)$v['faculty_count'];
	$thumbnail_url=$v['thumbnail_url'];
	$ams_university_count=(int)$v['ams_university_count'];
	#################################
	if($ams_university_count==0){}else{
		$arr['d']['u_id'] =$u_id;
		$arr['d']['u_group_id'] =$u_group_id;
		$arr['d']['u_name'] =$u_name;
		$arr['d']['group_name'] =$group_name;
		$arr['d']['faculty_count'] =$faculty_count;
		$arr['d']['thumbnail_url'] =$thumbnail_url;
		$arr['d']['year'] =$year;
		$arr['d']['ams_count'] =$ams_university_count;
		$arr_result[] = $arr['d'];	
	}
	#################################
	}
}
#echo '<pre>$arr_result=>'; print_r($arr_result); echo '</pre>'; die();
$data=array('list'=>$arr_result,);
if($data){
				$this->response(array('header'=>array(
										'title'=>'university list year ams faculty ',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function universitylistyearamsbyidyear_get(){
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$year=@$this->input->get('year');
$u_id=@$this->input->get('u_id');
if($u_id==null){
 $this->response(array('header'=>array('title'=>'session',
										'message'=>'u_id is null ',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
										die();
}
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year; }
#################
$university_list=$this->model_trainer->get_ams_university_by_u_id($u_id,$delekey);
$ams=$this->model_trainer->get_ams_university_ams_entrance_group_by_u_id_year($year,$u_id,$delekey);
#echo '<hr><pre>  $ams=>'; print_r($ams); echo '</pre>';die();
if(!$ams){
	$this->response(array('header'=>array(
										'title'=>'university ams',
										'message'=>'Success',
										'year'=>$year,
										'status'=>true,
										'code'=>201), 
										'data'=> null),201);
Die();
}
#################
$datasetamss = array();
if (is_array($ams)) {
  foreach ($ams as $keydatas => $val) {
 $ent_id=$val['ent_id'];
 $dataset['d'][$keydatas]['year_config']=$val['year_config'];
 $dataset['d'][$keydatas]['major_remark']=$val['major_remark'];
 $branch_id=$val['u_id'];
 $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
 $dataset['d'][$keydatas]['score_history']=$score_history;
 $dataset['d'][$keydatas]['ent_id']=$val['ent_id'];
 #################
 $dataset['d'][$keydatas]['01_onet_mi']=$val['01_onet_mi'];
 $dataset['d'][$keydatas]['01_onet_weight']=$val['01_onet_weight'];
 $dataset['d'][$keydatas]['02_onet_min']=$val['02_onet_min'];
 $dataset['d'][$keydatas]['02_onet_weight']=$val['02_onet_weight'];
 $dataset['d'][$keydatas]['03_onet_mint']=$val['03_onet_mint'];
 $dataset['d'][$keydatas]['03_onet_weight']=$val['03_onet_weight'];
 $dataset['d'][$keydatas]['04_onet_min']=$val['04_onet_min'];
 $dataset['d'][$keydatas]['04_onet_weight']=$val['04_onet_weight'];
 $dataset['d'][$keydatas]['05_onet_min']=$val['05_onet_min'];
 $dataset['d'][$keydatas]['05_onet_weight']=$val['05_onet_weight'];
 $dataset['d'][$keydatas]['71_pat1_min']=$val['71_pat1_min'];
 $dataset['d'][$keydatas]['71_pat1_weight']=$val['71_pat1_weight'];
 $dataset['d'][$keydatas]['72_pat2_min']=$val['72_pat2_min'];
 $dataset['d'][$keydatas]['72_pat2_weight']=$val['72_pat2_weight'];
 $dataset['d'][$keydatas]['73_pat3_min']=$val['73_pat3_min'];
 $dataset['d'][$keydatas]['73_pat3_weight']=$val['73_pat3_weight'];
 $dataset['d'][$keydatas]['74_pat4_min']=$val['74_pat4_min'];
 $dataset['d'][$keydatas]['74_pat4_weight']=$val['74_pat4_weight'];
 $dataset['d'][$keydatas]['75_pat5_min']=$val['75_pat5_min'];
 $dataset['d'][$keydatas]['75_pat5_weight']=$val['75_pat5_weight'];
 $dataset['d'][$keydatas]['76_pat6_min']=$val['76_pat6_min'];
 $dataset['d'][$keydatas]['76_pat6_weight']=$val['76_pat6_weight'];
 $dataset['d'][$keydatas]['77_pat71_min']=$val['77_pat71_min'];
 $dataset['d'][$keydatas]['77_pat71_weight']=$val['77_pat71_weight'];
 $dataset['d'][$keydatas]['78_pat72_min']=$val['78_pat72_min'];
 $dataset['d'][$keydatas]['78_pat72_weight']=$val['78_pat72_weight'];
 $dataset['d'][$keydatas]['79_pat73_min']=$val['79_pat73_min'];
 $dataset['d'][$keydatas]['79_pat73_weight']=$val['79_pat73_weight'];
 $dataset['d'][$keydatas]['80_pat74_min']=$val['80_pat74_min'];
 $dataset['d'][$keydatas]['80_pat74_weight']=$val['80_pat74_weight'];
 $dataset['d'][$keydatas]['81_pat75_min']=$val['81_pat75_min'];
 $dataset['d'][$keydatas]['81_pat75_weight']=$val['81_pat75_weight'];
 $dataset['d'][$keydatas]['82_pat76_min']=$val['82_pat76_min'];
 $dataset['d'][$keydatas]['82_pat76_weight']=$val['82_pat76_weight'];
 $dataset['d'][$keydatas]['85_gat_min']=$val['85_gat_min'];
 $dataset['d'][$keydatas]['85_gat_min_part2']=$val['85_gat_min_part2'];
 $dataset['d'][$keydatas]['85_gat_weight']=$val['85_gat_weight'];
 $dataset['d'][$keydatas]['branch_id']=$val['branch_id'];
 $dataset['d'][$keydatas]['branch_name']=$val['branch_name'];
 $dataset['d'][$keydatas]['config']=$val['config'];
 $dataset['d'][$keydatas]['faculty_id']=$val['faculty_id'];
 $dataset['d'][$keydatas]['faculty_name']=$val['faculty_name'];
 $dataset['d'][$keydatas]['gpax_min']=$val['gpax_min'];
 $dataset['d'][$keydatas]['gpax_weight']=$val['gpax_weight'];
 $dataset['d'][$keydatas]['major_code']=$val['major_code'];
 $dataset['d'][$keydatas]['major_remark']=$val['major_remark'];
 $dataset['d'][$keydatas]['onet_min_total']=$val['onet_min_total'];
 $dataset['d'][$keydatas]['onet_weight_total']=$val['onet_weight_total'];
 $dataset['d'][$keydatas]['receive_amount']=$val['receive_amount'];
 $dataset['d'][$keydatas]['receive_amount_sharecode']=$val['receive_amount_sharecode'];
 $dataset['d'][$keydatas]['score_max']=$val['score_max'];
 $dataset['d'][$keydatas]['score_min']=$val['score_min'];
 $dataset['d'][$keydatas]['special_remark']=$val['special_remark'];
 $dataset['d'][$keydatas]['lastupdate']=$val['lastupdate'];
 $datasetamss[]=$dataset['d'][$keydatas];
  }
}else{ $datasetamss=null; }
$group_ams_data_count=count($datasetamss);
##############
$faculty_datars_na=array();
foreach($datasetamss as $keyfna => $valuefna) {$datarsf['branch_id']=$valuefna['branch_id'];$faculty_datars_na[] = $datarsf;}
$num=0;foreach($faculty_datars_na as $kfna=>$vfna){$idfna=$vfna['branch_id'];$num++;}
$databranch_set=implode(',', array_map(function($vfna){ return $vfna['branch_id'];}, $faculty_datars_na ));
##############

$university_list=$this->model_trainer->get_ams_university_by_u_id($u_id,$delekey);
$group_list=$this->model_trainer->get_university_group_map_form_ams_entrance_branch_id_dataset($databranch_set,$delekey);
#########faculty_list#############
$faculty_list=$this->model_trainer->get_ams_university_u_parent_id_to_u_id_dataset($databranch_set,$delekey);
if(is_array($faculty_list)){
   foreach($faculty_list as $keyset => $va) {
  $arr=array();
  $u_id=(int)$va['uid'];
  $arr['m'][$keyset]['u_id']=$u_id;
  $arr['m'][$keyset]['faculty_set']=$u_parent_id_dataset_data;
  $arr['m'][$keyset]['faculty_name']=$va['uname'];
  $arr['m'][$keyset]['year']=$year;
  $u_parent_id_dataset_data=$this->model_trainer->get_ams_university_u_id_to_u_parent_id_dataset($u_id,$delekey);
  ##############
  $set_data_n1=array();
  foreach($u_parent_id_dataset_data as $keyset_n1 => $valueset) {$dataset_n1['uid']=$valueset['uid'];$set_data_n1[] = $dataset_n1;}
  $num=0;foreach($set_data_n1 as $k_n1=>$v_n1){$idn1=$v_n1['uid'];$num++;}
  $data_set=implode(',', array_map(function($v_n1){ return $v_n1['uid'];}, $set_data_n1 ));
  $arr['m'][$keyset]['faculty_set']=$data_set;
##############
$branch_data=$this->model_trainer->get_ams_university_u_id_dataset($data_set,$delekey);
##########################
if(is_array($branch_data)){
foreach($branch_data as $keyset2 => $va2) {
$arr2=array();
$uid2=(int)$va2['uid'];
$ams_entrance_data=$this->model_trainer->get_ams_university_ams_entrance_dataset($uid2,$year,$delekey);
$ams_entrance_data_count=count($ams_entrance_data);
############################
if($ams_entrance_data_count==''){}else{
   $arr2['a'][$keyset2]['branch_id']=$uid2;
   $arr2['a'][$keyset2]['branch_name']=$va2['uname'];
   $arr2['a'][$keyset2]['entrance_data']=$ams_entrance_data;
   $arr2['a'][$keyset2]['entrance_count']=$ams_entrance_data_count;
   $arr2_b[]=$arr2['a'][$keyset2]; 
}
############################
}
}
##########################
  $arr['m'][$keyset]['branch_data']=$arr2_b;
  $arr_faculty[]=$arr['m'][$keyset];
}
  }
#########faculty_list#############
$data=array('branch_count'=>$group_ams_data_count,
			'branch_list'=>$datasetamss,
  'branch_set'=>$databranch_set,
  'group_list'=>$group_list,
  'faculty_list'=>$arr_faculty,
  'university'=>$university_list['0'],
 );
if($data){
				$this->response(array('header'=>array(
										'title'=>'university ams',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'year'=>$year,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function universitylistyearamsfacultygroup4_get(){
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$group_id=@$this->input->get('group_id');
$year=@$this->input->get('year');
if($year==''){
 $year=(int)date('Y');
 $year=(int)$year+543;
 $year=(int)$year-1;
}else{
$year=(int)$year;
}

if($group_id==null){
 $this->response(array('header'=>array('title'=>'session',
										'message'=>' group_id is null ',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
										die();
}


$user_id=@$this->tppy_member->get_member_profile()->user_id;
$user_id=(int)$user_id;
$university_list=$this->model_trainer->get_ams_university_list_all($delekey);
#echo '<hr><pre>   university_list=>'; print_r($university_list); echo '</pre>';die();
$arr_university = array();
if(is_array($university_list)){
   foreach($university_list as $keyset => $va) {
$arr=array();
$u_id=$va['u_id'];

$datasetamss=$this->model_trainer->get_ams_university_group_map_group_id($group_id,$delekey);
##############
$groupname=$datasetamss['0']['group_name'];
$faculty_datars_na=array();
foreach($datasetamss as $keyfna => $valuefna) {$datarsf['branch_id']=$valuefna['branch_id'];$faculty_datars_na[] = $datarsf;}
$num=0;foreach($faculty_datars_na as $kfna=>$vfna){$idfna=$vfna['branch_id'];$num++;}
$databranch_set=implode(',', array_map(function($vfna){ return $vfna['branch_id'];}, $faculty_datars_na ));
##############
$dirapply=$this->model_trainer->get_ams_entrance_dirapply_year_set($year,$databranch_set,$delekey);
$dirapply=$dirapply['data'];
$dirapply_count=count($dirapply);
$dirapplysal=$dirapply['sql'];
$ams=$this->model_trainer->get_ams_university_ams_entrance_group_by_u_id_year_setid($year,$u_id,$databranch_set,$delekey);
$ams_count=count($ams['data']);

$ams_faculty_name_branch_name=$this->model_trainer->get_ams_university_ams_entrance_group_by_u_id_year_setid_faculty_name_branch_name($year,$u_id,$databranch_set,$delekey);
$entrancegy=$this->model_trainer->get_ams_university_ams_entrance_group_by_year_setid($year,$databranch_set,$delekey);
$facultydata=$ams_faculty_name_branch_name;
if($facultydata==''){$facultydata=null;}else{$facultydata=$facultydata['data']['0']['faculty_name'];}

if($ams_count==''){}else{
  $arr['m'][$keyset]['u_id']=$u_id;
  $arr['m'][$keyset]['groupname']=$groupname;
  $arr['m'][$keyset]['databranch_set']=$databranch_set;
  $arr['m'][$keyset]['u_name']=$va['u_name'];
  $arr['m'][$keyset]['u_group_id']=$va['u_group_id'];
  $arr['m'][$keyset]['group']=$va['group_name'];
  //$arr['m'][$keyset]['faculty_count']=$va['faculty_count'];
  $arr['m'][$keyset]['thumbnail_url']=$va['thumbnail_url'];
  $arr['m'][$keyset]['ams_entrance_count']=$ams_count;
  $arr['m'][$keyset]['facultyname']=$facultydata;
  //$arr['m'][$keyset]['ams_entrance']=$ams['data'];
  //$arr['m'][$keyset]['ams_entrance_sql']=$ams['sql'];
  $arr_university[]=$arr['m'][$keyset];
}
  }
} 
$arr_university_count=count($arr_university);
$entrancegy_list=$entrancegy['data'];
$datans = array();
if (is_array($entrancegy_list)) {
	foreach ($entrancegy_list as $key => $value) {
		$datans = array();
		$ent_id=$value['ent_id'];
		$u_id=$value['u_id'];
		$datans['ns']['ent_id']=(int)$ent_id;
		$datans['ns']['u_id']=(int)$u_id;
		
		
 
 if($user_id==null ||$user_id==''){
$ams_entrance_user=null;
}else{
$ams_entrance_user=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$u_id,$user_id,0,$delekey);
$ams_entrance_user=$ams_entrance_user['data']['0'];
$ent_id2=(int)$ams_entrance_user['ent_id'];
$u_id2=(int)$ams_entrance_user['u_id'];
$user_id2=(int)$ams_entrance_user['user_id'];
}
$datans['ns']['ams_entrance_user']=$ams_entrance_user;	
if($ams_entrance_user==''||$ams_entrance_user==NULL){
	$datans['ns']['active']=0;	
}else{
	if($ent_id2==''){
		$datans['ns']['active']=0;	
	}else{
		$datans['ns']['active']=1;	
	}
	
}
################
 if($user_id==null ||$user_id==''){
$ams_entrance_user_new=null;
}else{
$ams_entrance_user_new=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id(0,$u_id,$user_id,0,$delekey);
$ams_entrance_user_new=$ams_entrance_user_new['data']['0'];
$ent_id3=(int)$ams_entrance_user_new['ent_id'];
$u_id3=(int)$ams_entrance_user_new['u_id'];
$user_id3=(int)$ams_entrance_user_new['user_id'];
}
$datans['ns']['ams_entrance_user_news']=$ams_entrance_user_new;	
if($ams_entrance_user_new==''||$ams_entrance_user_new==NULL){
	$datans['ns']['active_news']=0;	
}else{
	if($u_id3==''){
		$datans['ns']['active_news']=0;	
	}else{
		$datans['ns']['active_news']=1;	
	}
	
}
##############
	
		$datans['ns']['year_config']=(int)$value['year_config'];
		$datans['ns']['major_code']=$value['major_code'];
		$datans['ns']['major_remark']=$value['major_remark'];
		$datans['ns']['gpax_weight']=$value['gpax_weight'];
		$datans['ns']['gpax_min']=$value['gpax_min'];
/**/
		$datans['ns']['onet_weight_total']=$value['onet_weight_total'];
		$datans['ns']['onet_min_total']=$value['onet_min_total'];
		$datans['ns']['01_onet_weight']=$value['01_onet_weight'];
		$datans['ns']['01_onet_mi']=$value['01_onet_mi'];
		$datans['ns']['02_onet_weight']=$value['02_onet_weight'];
		$datans['ns']['02_onet_min']=$value['02_onet_min'];
		$datans['ns']['03_onet_weight']=$value['03_onet_weight'];
		$datans['ns']['03_onet_mint']=$value['03_onet_mint'];
		$datans['ns']['04_onet_weight']=$value['04_onet_weight'];
		$datans['ns']['04_onet_min']=$value['04_onet_min'];
		$datans['ns']['05_onet_weight']=$value['05_onet_weight'];
		$datans['ns']['05_onet_min']=$value['05_onet_min'];
		$datans['ns']['85_gat_weight']=$value['85_gat_weight'];
		$datans['ns']['85_gat_min']=$value['85_gat_min'];
		$datans['ns']['85_gat_min_part2']=$value['85_gat_min_part2'];
		$datans['ns']['71_pat1_weight']=$value['71_pat1_weight'];
		$datans['ns']['71_pat1_min']=$value['71_pat1_min'];
		$datans['ns']['72_pat2_weight']=$value['72_pat2_weight'];
		$datans['ns']['72_pat2_min']=$value['72_pat2_min'];
		$datans['ns']['73_pat3_weight']=$value['73_pat3_weight'];
		$datans['ns']['73_pat3_min']=$value['73_pat3_min'];
		$datans['ns']['74_pat4_weight']=$value['74_pat4_weight'];
		$datans['ns']['74_pat4_min']=$value['74_pat4_min'];
		$datans['ns']['75_pat5_weight']=$value['75_pat5_weight'];
		$datans['ns']['75_pat5_min']=$value['75_pat5_min'];
		$datans['ns']['76_pat6_weight']=$value['76_pat6_weight'];
		$datans['ns']['76_pat6_min']=$value['76_pat6_min'];
		$datans['ns']['77_pat71_weight']=$value['77_pat71_weight'];
		$datans['ns']['77_pat71_min']=$value['77_pat71_min'];
		$datans['ns']['78_pat72_weight']=$value['78_pat72_weight'];
		$datans['ns']['78_pat72_min']=$value['78_pat72_min'];
		$datans['ns']['79_pat73_weight']=$value['79_pat73_weight'];
		$datans['ns']['79_pat73_min']=$value['79_pat73_min'];
		$datans['ns']['80_pat74_weight']=$value['80_pat74_weight'];
		$datans['ns']['80_pat74_min']=$value['80_pat74_min'];
		$datans['ns']['81_pat75_weight']=$value['81_pat75_weight'];
		$datans['ns']['81_pat75_min']=$value['81_pat75_min'];
		$datans['ns']['82_pat76_weight']=$value['82_pat76_weight'];
		$datans['ns']['82_pat76_min']=$value['82_pat76_min'];
/**/
		$datans['ns']['special_remark']=$value['special_remark'];
		$datans['ns']['receive_amount']=$value['receive_amount'];
		$datans['ns']['receive_amount_sharecode']=$value['receive_amount_sharecode'];
		$datans['ns']['lastupdate']=$value['lastupdate'];
		$datans['ns']['config']=$value['config'];
		$datans['ns']['score_min']=$value['score_min'];
		$datans['ns']['score_max']=$value['score_max'];
		$datans['ns']['branch_id']=(int)$value['branch_id'];
		$datans['ns']['branch_name']=$value['branch_name'];
		$datans['ns']['faculty_id']=(int)$value['faculty_id'];
		$datans['ns']['faculty_name']=$value['faculty_name'];
		$datans['ns']['university_id']=(int)$value['university_id'];
		$datans['ns']['university_name']=$value['university_name'];
	#################
	$entrancegy_lists[]=$datans['ns'];
	}
}else{
	$entrancegy_lists=null;
}

 
$datanews = array();
if (is_array($dirapply)) {
foreach ($dirapply as $key => $value) {
$datanews = array();
$news_id=$value['news_id'];
 if($user_id==null ||$user_id==''){
$ams_entrance_user2=null;
}else{
$entid=0;
$ams_entrance_user2=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($entid,$news_id,$user_id,$delekey);
$ams_entrance_user2=$ams_entrance_user2['data']['0'];
$ent_id3=(int)$ams_entrance_user2['ent_id'];
$u_id3=(int)$ams_entrance_user2['u_id'];
$user_id3=(int)$ams_entrance_user2['user_id'];
}
$datanews['news']['ams_entrance_user']=$ams_entrance_user2;	
if($ams_entrance_user2==''||$ams_entrance_user2==NULL){
	$datanews['news']['active']=0;	
}else{
	if($u_id3==''){
		$datanews['news']['active']=0;	
	}else{
		$datanews['news']['active']=1;	
	}
	
}
$datanews['news']['news_id']=$news_id;
$datanews['news']['news_title']=$value['news_title'];
$datanews['news']['u_id']=$value['u_id'];
$datanews['news']['year']=$value['yearnews'];
$datanews['news']['remark']=$value['major_remark'];
$datanews['news']['branch_id']=$value['branch_id'];
$datanews['news']['faculty_id']=$value['faculty_id'];
$datanews['news']['university_id']=$value['university_id'];
$datanews['news']['branch_name']=$value['branch_name'];
$datanews['news']['faculty_name']=$value['faculty_name'];
$datanews['news']['university_name']=$value['university_name'];
#################
	$dirapply_lists[]=$datanews['news'];
}
}else{
	$dirapply_lists=null;
}
$data=array('user_id'=>$user_id,
			'faculty'=>$facultydata,
			'databranch_set'=>$databranch_set,
			'university_list'=>$arr_university,
			'university_count'=>$arr_university_count,
			'entrance_list'=>$entrancegy_lists,
			'entrance_count'=>count($entrancegy_lists),
			'dirapply_list'=>$dirapply_lists,
			'dirapply_count'=>$dirapply_count,
 );
if($data){
				$this->response(array('header'=>array(
										'title'=>'university list year ams faculty ',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}

#############
}
public function userprofile_get(){
 //http://www.trueplookpanya.dev/api/admissiontrainer/userprofile?username=adminna&password=123456 
$user_username=@$this->input->get('username');
$user_password=@$this->input->get('password');
$user_list=$this->model_trainer->get_profile_by_username_password($user_username,$user_password);
$user_list_count=count($user_list);
if($user_list_count<=0){
$this->response(array('header'=>array('title'=>'User profile',
										'message'=>'Null',
										'status'=>true,
										'code'=>200), 
										'data'=> Null),200);
die();
}
$arr_user=array();
if(is_array($user_list)){
   foreach($user_list as $keyset => $va) {
$arr=array();
$user_id=$va['user_id'];
  $arr['m'][$keyset]['user_id']=$user_id;
  $arr['m'][$keyset]['member_id']=$va['member_id'];
  $arr['m'][$keyset]['user']=$va['user_username'];
  $arr['m'][$keyset]['email']=$va['user_email'];
  $arr['m'][$keyset]['user_status']=$va['user_status'];
  $arr['m'][$keyset]['user_group']=$va['user_group'];
  $arr['m'][$keyset]['user_permission']=$va['user_permission'];
  $arr['m'][$keyset]['psn_display_image']=$va['psn_display_image'];
  $arr['m'][$keyset]['blog_id']=$va['blog_id'];
  $arr_user[]=$arr['m'][$keyset];
  }
} 

# $this->load->library('session');
# $this->session->set_userdata($arr_user);
# $session_user_data=$this->session->userdata();
/*
$this->CI->session->set_userdata($arr_user);
$session_user_data=$this->CI->session->userdata();
*/
//ลบที่ละตัวแปร
// $user_id_rm=$this->session->unset_userdata('user_id');
// unset($_SESSION['user_id']);
#$userdata = array('user_id','member_id','user','email','user_status','user_group','user_permission','psn_display_image','blog_id');
#$this->CI->session->unset_userdata($userdata);

$data=array('count'=>$user_list_count,
			'list'=>$arr_user['0'],
			//'session_data'=>$session_user_data,
 );
if($data){
				$this->response(array('header'=>array(
										'title'=>'User profile',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
										die();
}else{
				  $this->response(array('header'=>array(
										'title'=>'User profile',
										'message'=>'Error',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
										die();
 }
}
public function userprofileid_get(){
 //http://www.trueplookpanya.dev/api/admissiontrainer/userprofileid?user_id=510325 
$user_id=@$this->input->get('user_id');
$user_list=$this->model_trainer->get_profile_by_user_id($user_id);
$user_list_count=count($user_list);
if($user_list_count<=0){
$this->response(array('header'=>array('title'=>'User profile',
										'message'=>'Null',
										'status'=>true,
										'code'=>200), 
										'data'=> Null),200);
die();
}
$arr_user=array();
if(is_array($user_list)){
   foreach($user_list as $keyset => $va) {
$arr=array();
$user_id=$va['user_id'];
  $arr['m'][$keyset]['user_id']=$user_id;
  $arr['m'][$keyset]['member_id']=$va['member_id'];
  $arr['m'][$keyset]['user']=$va['user_username'];
  $arr['m'][$keyset]['email']=$va['user_email'];
  $arr['m'][$keyset]['user_status']=$va['user_status'];
  $arr['m'][$keyset]['user_group']=$va['user_group'];
  $arr['m'][$keyset]['user_permission']=$va['user_permission'];
  $arr['m'][$keyset]['psn_display_image']=$va['psn_display_image'];
  $arr['m'][$keyset]['blog_id']=$va['blog_id'];
  $arr_user[]=$arr['m'][$keyset];
  }
} 
 
$data=array('count'=>$user_list_count,
			'list'=>$arr_user['0'],
			//'session_data'=>$session_user_data,
 );
if($data){
				$this->response(array('header'=>array(
										'title'=>'User profile',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
										die();
}else{
				  $this->response(array('header'=>array(
										'title'=>'User profile',
										'message'=>'Error',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
										die();
 }
}
public function amsentranceuseradd_get(){
//$user_id=@$this->tppy_member->get_member_profile()->user_id;
/**
*
user_id 123 สาขาชื่อ 4447 
ถ้าสนใจแต่ entrance ก็ save ent_id 75 ลงไป
ถ้าสนใจรับตรงด้วย ก็ save ent_id 0 ลงไป
ดังนั้นตอน query
select * from ams_entrance_user
ถ้า ent_id > 0 ก็ไป select จาก entrance
ถ้า eng_id =0 ให้ไป select ข่าวรับตรงของ u_id นั้นมาหมด

ถ้าเลือกแต่ entrance (ไม่เลือกข่าวรับตรง) 
ก็จะ save แต่ record1
user_id , u_id , ent_id
123 , 4447 , 75

ถ้าเลือกแต่ข่าวรับตรง (ไม่เลือก entrance)
ก้จะ save แต่ record 2
user_id , u_id , ent_id
123 , 4447 , 0
* 
*/

# http://www.trueplookpanya.dev/api/admissiontrainer/amsentranceuseradd?ent_id=200&user_id=543622&u_id=65
####################
$ent_id=@$this->input->get('ent_id');
$user_id=@$this->input->get('user_id');
$u_id=@$this->input->get('u_id');
####################
$table='ams_entrance_user';
$data_insert=array('ent_id'=>$ent_id,'user_id'=>$user_id,'u_id'=>$u_id);
$insertdatatb=$this->model_trainer->insertdatatb($table,$data_insert);
$data=array('ent_id'=>$ent_id,'user_id'=>$user_id,'u_id'=>$u_id,
			'result'=>$insertdatatb['result'],
			'data'=>$insertdatatb['data'],
			'insertstatus'=>$insertdatatb['status'],
			);
$status=$insertdatatb['status'];
if($status==1){
				$this->response(array('header'=>array(
										'title'=>'insert ams_entrance_user',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
}elseif($status==2){
				  $this->response(array('header'=>array(
										'title'=>'Have data ams_entrance_user',
										'message'=>'Have data',
										'status'=>false, 
										'code'=>404), 
										'data'=> $data),404);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'insert ams_entrance_user',
										'message'=>'Error',
										'status'=>false, 
										'code'=>404), 
										'data'=> $data),404);
				}

}
public function amsentranceuserremove_get(){
# http://www.trueplookpanya.dev/api/admissiontrainer/amsentranceuserremove?ent_id=200&user_id=543622&u_id=65
//$user_id=@$this->tppy_member->get_member_profile()->user_id;
$ent_id=@$this->input->get('ent_id');
$user_id=@$this->input->get('user_id');
$u_id=@$this->input->get('u_id');
$table='ams_entrance_user';
$filddb1='user_id';
$id1=$user_id;
$filddb2='u_id';
$id2=$u_id;
$filddb3='ent_id';
$id3=$ent_id;
$datarm=array('ent_id'=>$ent_id,
				   'user_id'=>$user_id,
				   'u_id'=>$u_id,
				);
$remove_status=$this->model_trainer->deletedatatb2($table,$filddb1,$id1,$filddb2,$id2,$filddb3,$id3);
//echo '<pre>remove_status=>';print_r($remove_status); echo '</pre>';die();
$data=array('data'=>$datarm,
			'remove'=>$remove_status,
			'status'=>$remove_status['status'],
 );

if($statusrs==1){
				$this->response(array('header'=>array(
										'title'=>'remove ams_entrance_user',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'remove ams_entrance_user',
										'message'=>'Error',
										'status'=>false, 
										'code'=>404), 
										'data'=> $data),404);
				}

}
public function entranceuseid_get(){
# http://www.trueplookpanya.com/api/admissiontrainer/entranceuseid?user_id=543622
####################
$user_id=@$this->input->get('user_id');
$amsentranceusers=$this->model_trainer->get_ams_entrance_user_innerjoin_ams_university_where_user_id($user_id,$delekey);
$amsentranceuser=$amsentranceusers['data'];
$amsentranceuser_sql=$amsentranceusers['sql'];
$num=0;
foreach($amsentranceuser as $k=>$v){
$id=$v['u_id'];
$num++;
}
$result_set=implode(',', array_map(function($v){ return $v['u_id'];}, $amsentranceuser ));
#################################
if (is_array($amsentranceuser)) {
foreach ($amsentranceuser as $key => $v) {
$arr = array();
	########################
	$ent_id=(int)$v['ent_id'];
	$user_id=(int)$v['user_id'];
	$u_id=(int)$v['u_id'];
	$entrance_remark=$v['entrance_remark'];
	$faculty_id=(int)$v['faculty_id'];
	$faculty_name=$v['faculty_name'];
	$branch_name=$v['branch_name'];
	$university_id=(int)$v['university_id'];
	$university_name=$v['university_name'];
	#################################
		$arr['d']['ent_id'] =$ent_id;
		$arr['d']['user_id'] =$user_id;
		$arr['d']['branch_id'] =$u_id;
		$setgroup=$u_id;
		$amsentranceusers=$this->model_trainer->get_ams_university_group_map_set_group($setgroup,$delekey);
		$arr['d']['amsentranceusers'] =$amsentranceusers;
		$arr['d']['u_count'] =(int)count($amsentranceusers);
		$arr['d']['faculty_id'] =$faculty_id;
		$arr['d']['university_id'] =$university_id;
		$arr['d']['faculty_name'] =$faculty_name;
		$arr['d']['branch_name'] =$branch_name;
		$arr['d']['university_name'] =$university_name;
		if($entrance_remark==''){
			$entrance_remark='ข่าวรับตรง '.$branch_name;
		}
		$arr['d']['remark'] =$entrance_remark;
		$arr_result[] = $arr['d'];	
	#################################
	}
}
#################################
$dataall=array('dataset'=>$result_set,'count'=>count($amsentranceuser),'data'=>$arr_result,'sql'=>$amsentranceuser_sql);
if($amsentranceuser!==''){
				$this->response(array('header'=>array(
										'title'=>'insert ams_entrance_user',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $dataall),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'insert ams_entrance_user',
										'message'=>'Error',
										'status'=>false, 
										'code'=>404), 
										'data'=> $amsentranceuser),404);
				}

}
public function amsentranceuseruniversityuserid_get(){
# http://www.trueplookpanya.com/api/admissiontrainer/amsentranceuseruniversityuserid?user_id=543622&year=2560&delekey=
####################
$user_id=@$this->tppy_member->get_member_profile()->user_id;
$user_id=(int)$user_id;
$member_id=@$this->tppy_member->get_member_profile()->member_id;
$year=@$this->input->get('year');
if($year==''){
	$y=date('Y');
	$y=$y+543;
	$year=$y-1;
}
$year=(int)$year;
$user_id=@$this->input->get('user_id');
$where=@$this->input->get('where');
$delekey=@$this->input->get('delekey');
if($delekey==''){$delekey='';}
$amsbyuniversity=$this->model_trainer->get_ams_entrance_user_university_user_id_where($user_id,$where,$delekey);
$rs=$amsbyuniversity['data'];
$sql=$amsbyuniversity['sql'];
if(is_array($rs)){
foreach ($rs as $key => $v) {
$arr = array();
	########################
	$ent_id=(int)$v['ent_id'];
	$user_id=(int)$v['user_id'];
	$u_id=(int)$v['u_id'];
	$university_id=$v['university_id'];
	$university_name=$v['university_name'];
	$universityparent1=$this->model_trainer->get_ams_university_u_parent_id_where($university_id,$delekey);
	$universityparent1_data=$universityparent1['data'];
	$num=0;
	foreach($universityparent1_data as $k2=>$v2){
	$id=$v2['u_id'];
	$num++;
	}
	$universityparent1_set=implode(',', array_map(function($v2){ return $v2['u_id'];}, $universityparent1_data ));
#################################
	$thumbnail=$v['thumbnail'];
	#################################
	if(is_array($universityparent1_data)){
	foreach ($universityparent1_data as $key3 => $v3) {
	$arr2=array();
		########################
		$faculty_id=(int)$v3['u_id'];
		$u_parent_id=(int)$v3['u_parent_id'];
		$faculty_name=$v3['u_name'];
		$thumbnailss=$v3['thumbnail'];
		$u_group_id=$v3['u_group_id'];
		$record_status=$v3['record_status'];
	######################	
	$branch=$this->model_trainer->get_ams_university_u_parent_id_where($faculty_id,$delekey);
	$branch_data=$branch['data'];
	$num=0;
	foreach($branch_data as $k31=>$v31){
	$id3=$v31['u_id'];
	$num++;
	}
	$branch_set=implode(',', array_map(function($v31){ return $v31['u_id'];}, $branch_data ));
#################################
$entrancedata=$this->model_trainer->get_ams_users_entrance_score_history_set($branch_set,$year,$delekey);
$entrance_data=$entrancedata['data'];
//$arr2['e']['entrance_sql'] =$entrancedata['sql'];
if(is_array($entrance_data)){
foreach ($entrance_data as $keyss=>$vv4){
	$dataarr = array();
	$ent_id=$vv4['ent_id'];
	$dataarr['e4']['ent_id']=$ent_id;
	$dataarr['e4']['u_id']=$vv4['u_id'];
	$dataarr['e4']['faculty_id']=$vv4['faculty_id'];
	$dataarr['e4']['faculty_name']=$vv4['faculty_name'];
	$dataarr['e4']['branch_id']=$vv4['branch_id'];
	$dataarr['e4']['branch_name']=$vv4['branch_name'];
	$dataarr['e4']['special_remark']=$vv4['special_remark'];
	$dataarr['e4']['year_config']=$vv4['year_config'];
	$dataarr['e4']['major_code']=$vv4['major_code'];
	$dataarr['e4']['major_remark']=$vv4['major_remark'];
	$dataarr['e4']['gpax_weight']=$vv4['gpax_weight'];
	$dataarr['e4']['gpax_min']=$vv4['gpax_min'];
	/*
	$dataarr['e4']['onet_weight_total']=$vv4['onet_weight_total'];
	$dataarr['e4']['onet_min_total']=$vv4['onet_min_total'];
	$dataarr['e4']['01_onet_weight']=$vv4['01_onet_weight'];
	$dataarr['e4']['01_onet_mi']=$vv4['01_onet_mi'];
	$dataarr['e4']['02_onet_weight']=$vv4['02_onet_weight'];
	$dataarr['e4']['02_onet_min']=$vv4['02_onet_min'];
	$dataarr['e4']['03_onet_weight']=$vv4['03_onet_weight'];
	$dataarr['e4']['03_onet_mint']=$vv4['03_onet_mint'];
	$dataarr['e4']['04_onet_weight']=$vv4['04_onet_weight'];
	$dataarr['e4']['04_onet_min']=$vv4['04_onet_min'];
	$dataarr['e4']['05_onet_weight']=$vv4['05_onet_weight'];
	$dataarr['e4']['05_onet_min']=$vv4['05_onet_min'];
	$dataarr['e4']['85_gat_weight']=$vv4['85_gat_weight'];
	$dataarr['e4']['85_gat_min']=$vv4['85_gat_min'];
	$dataarr['e4']['85_gat_min_part2']=$vv4['85_gat_min_part2'];
	$dataarr['e4']['71_pat1_weight']=$vv4['71_pat1_weight'];
	$dataarr['e4']['71_pat1_min']=$vv4['71_pat1_min'];
	$dataarr['e4']['72_pat2_weight']=$vv4['72_pat2_weight'];
	$dataarr['e4']['72_pat2_min']=$vv4['72_pat2_min'];
	$dataarr['e4']['73_pat3_weight']=$vv4['73_pat3_weight'];
	$dataarr['e4']['73_pat3_min']=$vv4['73_pat3_min'];
	$dataarr['e4']['74_pat4_weight']=$vv4['74_pat4_weight'];
	$dataarr['e4']['74_pat4_min']=$vv4['74_pat4_min'];
	$dataarr['e4']['75_pat5_weight']=$vv4['75_pat5_weight'];
	$dataarr['e4']['75_pat5_min']=$vv4['75_pat5_min'];
	$dataarr['e4']['76_pat6_weight']=$vv4['76_pat6_weight'];
	$dataarr['e4']['76_pat6_min']=$vv4['76_pat6_min'];
	$dataarr['e4']['77_pat71_weight']=$vv4['77_pat71_weight'];
	$dataarr['e4']['77_pat71_min']=$vv4['77_pat71_min'];
	$dataarr['e4']['78_pat72_weight']=$vv4['78_pat72_weight'];
	$dataarr['e4']['78_pat72_min']=$vv4['78_pat72_min'];
	$dataarr['e4']['79_pat73_weight']=$vv4['79_pat73_weight'];
	$dataarr['e4']['79_pat73_min']=$vv4['79_pat73_min'];
	$dataarr['e4']['80_pat74_weight']=$vv4['80_pat74_weight'];
	$dataarr['e4']['80_pat74_min']=$vv4['80_pat74_min'];
	$dataarr['e4']['81_pat75_weight']=$vv4['81_pat75_weight'];
	$dataarr['e4']['81_pat75_min']=$vv4['81_pat75_min'];
	$dataarr['e4']['82_pat76_weight']=$vv4['82_pat76_weight'];
	$dataarr['e4']['82_pat76_min']=$vv4['82_pat76_min'];
	*/
	$dataarr['e4']['special_remark']=$vv4['special_remark'];
	$dataarr['e4']['receive_amount']=$vv4['receive_amount'];
	$dataarr['e4']['receive_amount_sharecode']=$vv4['receive_amount_sharecode'];
	$dataarr['e4']['lastupdate']=$vv4['lastupdate'];
	$dataarr['e4']['config']=$vv4['config'];
	$dataarr['e4']['score_min']=$vv4['score_min'];
	$dataarr['e4']['score_max']=$vv4['score_max'];
	$arr_entrance_data[] =$dataarr['e4'];	
#################
	}
}
		if($entrance_data!=''){
		$arr2['e']['entrance_data'] =$arr_entrance_data;
		$arr2['e']['branch_set'] =$branch_set;
		$arr2['e']['faculty_id'] =$faculty_id;
		$arr2['e']['university_id'] =$u_parent_id;
		$arr2['e']['university_name'] =$university_name;
		$arr2['e']['faculty_name'] =$faculty_name;
		$arr2['e']['u_group_id'] =$u_group_id;
		$arr2['e']['thumbnails'] =$thumbnailss;
		$arr2_result[] = $arr2['e'];	
		#################################
		}else{
			$arr2_result[] = null;
		}	
}

	}
	#################################
	
	$arr['d']['faculty_set']=$universityparent1_set;
	$arr['d']['faculty']=$arr2_result;
	//$arr['d']['universityparent1_sql']=$universityparent1['sql'];
		$arr['d']['ent_id'] =$ent_id;
		$arr['d']['user_id'] =$user_id;
		$arr['d']['branch_id'] =$u_id;
		$arr['d']['university_id'] =$university_id;
		$arr['d']['thumbnail'] =$thumbnail;
		$arr['d']['university_name'] =$university_name;
		$arr_result[] = $arr['d'];	
	#################################
	}
}
#################################
$dataall=array('data'=>$arr_result,'sql'=>$sql);
if($amsentranceuser!==''){
				$this->response(array('header'=>array(
										'title'=>'insert ams_entrance_user',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $dataall),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'insert ams_entrance_user',
										'message'=>'Error',
										'status'=>false, 
										'code'=>404), 
										'data'=> $amsentranceuser),404);
				}

}
public function amsentranceuniversity_get(){
# http://www.trueplookpanya.com/api/admissiontrainer/amsentranceuniversity?year=2560&delekey=
####################
$user_id=@$this->tppy_member->get_member_profile()->user_id;
$user_id=(int)$user_id;
$member_id=@$this->tppy_member->get_member_profile()->member_id;

$year=@$this->input->get('year');
if($year==''){
	$y=date('Y');
	$y=$y+543;
	$year=$y-1;
}
$year=(int)$year;
$delekey=@$this->input->get('delekey');
if($delekey==''){$delekey='';}
$amsbyuniversity=$this->model_trainer->get_ams_university_ams_university_year($year,$delekey);
 #echo'<hr><pre>amsbyuniversity=>';print_r($amsbyuniversity);echo'<pre> <hr>'; Die();
$rs=$amsbyuniversity['data'];
$sql=$amsbyuniversity['sql'];
$cache_key=$amsbyuniversity['cache_key'];
$cache_key_decrypt=$amsbyuniversity['cache_key_decrypt'];
$arr_result=array();
if(is_array($rs)){
foreach ($rs as $key => $v) {
################***university#################
$arr = array();
	########################
	$u_group_id=(int)$v['u_group_id'];
	$u_parent_id=(int)$v['u_parent_id'];
	$u_id=(int)$v['university_id'];
	$university_id=(int)$v['university_id'];
	$university_name=$v['university_name'];
	$universityparent1=$this->model_trainer->get_ams_university_u_parent_id_where($university_id,$delekey);
	#echo'<hr><pre>universityparent1=>';print_r($universityparent1);echo'<pre> <hr>'; Die();
	$universityparent1_data=$universityparent1['data'];
	$num=0;
	foreach($universityparent1_data as $k2=>$v2){
	$id=$v2['u_id'];
	$num++;
	}
	$universityparent1_set=implode(',', array_map(function($v2){ return $v2['u_id'];}, $universityparent1_data ));
#################################
	$thumbnail=$v['thumbnail'];
	#################################
	if(is_array($universityparent1_data)){
	foreach ($universityparent1_data as $key3 => $v3) {
	$arr2=array();
		########################
		$faculty_id=(int)$v3['u_id'];
		$u_parent_id=(int)$v3['u_parent_id'];
		$faculty_name=$v3['u_name'];
		$thumbnailss=$v3['thumbnail'];
		$u_group_id=$v3['u_group_id'];
		$record_status=$v3['record_status'];
	######################	
	$branch=$this->model_trainer->get_ams_university_u_parent_id_where($faculty_id,$delekey);
	$branch_data=$branch['data'];
	$num=0;
	foreach($branch_data as $k31=>$v31){
	$id3=$v31['u_id'];
	$num++;
	}
	$branch_set=implode(',', array_map(function($v31){ return $v31['u_id'];}, $branch_data ));
#################################

$entrancedata=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branch_set,$year,$delekey);
//echo'<hr><pre>$entrancedata=>';print_r($entrancedata);echo'<pre>'; Die();

$entrance_data=$entrancedata['data'];
$entrance_count=count($entrance_data);
//$arr2['e']['entrance_sql'] =$entrancedata['sql'];
if(is_array($entrance_data)){
foreach ($entrance_data as $keyss=>$vv4){
	$dataarr = array();
	$ent_id=$vv4['ent_id'];
	
	$dataarr['e4']['ent_id']=$ent_id;
	$dataarr['e4']['u_id']=$vv4['u_id'];
	$dataarr['e4']['university_name']=$university_name;
	$dataarr['e4']['faculty_id']=$vv4['faculty_id'];
	$dataarr['e4']['faculty_name']=$vv4['faculty_name'];
	$dataarr['e4']['branch_id']=$vv4['branch_id'];
	$dataarr['e4']['branch_name']=$vv4['branch_name'];
	$dataarr['e4']['special_remark']=$vv4['special_remark'];
	$dataarr['e4']['year_config']=$vv4['year_config'];
	$dataarr['e4']['major_code']=$vv4['major_code'];
	$dataarr['e4']['major_remark']=$vv4['major_remark'];
	$dataarr['e4']['gpax_weight']=$vv4['gpax_weight'];
	$dataarr['e4']['gpax_min']=$vv4['gpax_min'];
	/*
	$dataarr['e4']['onet_weight_total']=$vv4['onet_weight_total'];
	$dataarr['e4']['onet_min_total']=$vv4['onet_min_total'];
	$dataarr['e4']['01_onet_weight']=$vv4['01_onet_weight'];
	$dataarr['e4']['01_onet_mi']=$vv4['01_onet_mi'];
	$dataarr['e4']['02_onet_weight']=$vv4['02_onet_weight'];
	$dataarr['e4']['02_onet_min']=$vv4['02_onet_min'];
	$dataarr['e4']['03_onet_weight']=$vv4['03_onet_weight'];
	$dataarr['e4']['03_onet_mint']=$vv4['03_onet_mint'];
	$dataarr['e4']['04_onet_weight']=$vv4['04_onet_weight'];
	$dataarr['e4']['04_onet_min']=$vv4['04_onet_min'];
	$dataarr['e4']['05_onet_weight']=$vv4['05_onet_weight'];
	$dataarr['e4']['05_onet_min']=$vv4['05_onet_min'];
	$dataarr['e4']['85_gat_weight']=$vv4['85_gat_weight'];
	$dataarr['e4']['85_gat_min']=$vv4['85_gat_min'];
	$dataarr['e4']['85_gat_min_part2']=$vv4['85_gat_min_part2'];
	$dataarr['e4']['71_pat1_weight']=$vv4['71_pat1_weight'];
	$dataarr['e4']['71_pat1_min']=$vv4['71_pat1_min'];
	$dataarr['e4']['72_pat2_weight']=$vv4['72_pat2_weight'];
	$dataarr['e4']['72_pat2_min']=$vv4['72_pat2_min'];
	$dataarr['e4']['73_pat3_weight']=$vv4['73_pat3_weight'];
	$dataarr['e4']['73_pat3_min']=$vv4['73_pat3_min'];
	$dataarr['e4']['74_pat4_weight']=$vv4['74_pat4_weight'];
	$dataarr['e4']['74_pat4_min']=$vv4['74_pat4_min'];
	$dataarr['e4']['75_pat5_weight']=$vv4['75_pat5_weight'];
	$dataarr['e4']['75_pat5_min']=$vv4['75_pat5_min'];
	$dataarr['e4']['76_pat6_weight']=$vv4['76_pat6_weight'];
	$dataarr['e4']['76_pat6_min']=$vv4['76_pat6_min'];
	$dataarr['e4']['77_pat71_weight']=$vv4['77_pat71_weight'];
	$dataarr['e4']['77_pat71_min']=$vv4['77_pat71_min'];
	$dataarr['e4']['78_pat72_weight']=$vv4['78_pat72_weight'];
	$dataarr['e4']['78_pat72_min']=$vv4['78_pat72_min'];
	$dataarr['e4']['79_pat73_weight']=$vv4['79_pat73_weight'];
	$dataarr['e4']['79_pat73_min']=$vv4['79_pat73_min'];
	$dataarr['e4']['80_pat74_weight']=$vv4['80_pat74_weight'];
	$dataarr['e4']['80_pat74_min']=$vv4['80_pat74_min'];
	$dataarr['e4']['81_pat75_weight']=$vv4['81_pat75_weight'];
	$dataarr['e4']['81_pat75_min']=$vv4['81_pat75_min'];
	$dataarr['e4']['82_pat76_weight']=$vv4['82_pat76_weight'];
	$dataarr['e4']['82_pat76_min']=$vv4['82_pat76_min'];
	*/
	$dataarr['e4']['special_remark']=$vv4['special_remark'];
	$dataarr['e4']['receive_amount']=$vv4['receive_amount'];
	$dataarr['e4']['receive_amount_sharecode']=$vv4['receive_amount_sharecode'];
	$dataarr['e4']['lastupdate']=$vv4['lastupdate'];
	$dataarr['e4']['config']=$vv4['config'];
	$dataarr['e4']['score_min']=$vv4['score_min'];
	$dataarr['e4']['score_max']=$vv4['score_max'];
	
$u_id=$vv4['u_id'];
 if($user_id==null ||$user_id==''){
$ams_entrance_user=null;
}else{
$ams_entrance_user=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$u_id,$user_id,$delekey);
$ams_entrance_user=$ams_entrance_user['data']['0'];
$ent_id2=(int)$ams_entrance_user['ent_id'];
$u_id2=(int)$ams_entrance_user['u_id'];
$user_id2=(int)$ams_entrance_user['user_id'];
}
$dataarr['e4']['ams_entrance_user']=$ams_entrance_user;	
if($ams_entrance_user==''||$ams_entrance_user==NULL){
	$dataarr['e4']['active']=0;	
}else{
	if($ent_id2==''){
		$dataarr['e4']['active']=0;	
	}else{
		$dataarr['e4']['active']=1;	
	}
	
}

	
	$arr_entrance_data[] =$dataarr['e4'];	
#################
	}
}
if($entrance_count>0){
#################################
$n=0;
foreach($arr_entrance_data as $kn=>$v32){$id32=$v32['u_id'];$n++;}
$entranceset=implode(',', array_map(function($v32){ return $v32['u_id'];}, $arr_entrance_data ));
if($entrance_set!==''){
$directapply=$this->model_trainer->get_ams_university_ams_university_ams_news_directapply_university($entranceset,$delekey);
$directapply_data=$directapply['data'];	
$directapply_sql=$directapply['sql'];	
if(is_array($directapply_data)){
foreach ($directapply_data as $keyk=> $vk) {
$arr5 = array();
	########################
	$arr5['f']['news_title']=$vk['news_title'];
	$arr5['f']['receivers']=$vk['receivers'];
	$arr5['f']['gpax']=$vk['gpax'];
	$arr5['f']['gatpat']=$vk['gatpat'];
	$arr5['f']['req_portfolio']=$vk['req_portfolio'];
	$arr5['f']['req_interview']=$vk['req_interview'];
	$arr5['f']['req_matches']=$vk['req_matches'];
	$arr5['f']['req_property']=$vk['req_property'];
	$arr5['f']['receiver_number']=$vk['receiver_number'];
	$arr5['f']['university']=$vk['university'];
	$arr5['f']['u_name']=$vk['u_name'];
	$arr5['f']['req_gnet']=$vk['req_gnet'];
	$university_id=(int)$vk['university_id'];
	$arr5['f']['university_id']=$university_id;
	$faculty_id=(int)$vk['faculty_id'];
	$arr5['f']['faculty_id']=$faculty_id;
################

 if($user_id==null ||$user_id==''){
$ams_entrance_user_new=null;
}else{
$ams_entrance_user_new=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id(0,$faculty_id,$user_id,$delekey);
$ams_entrance_user_new=$ams_entrance_user_new['data']['0'];
$ent_id3=(int)$ams_entrance_user_new['ent_id'];
$u_id3=(int)$ams_entrance_user_new['u_id'];
$user_id3=(int)$ams_entrance_user_new['user_id'];
}
$arr5['f']['ams_entrance_user_news']=$ams_entrance_user_new;	
if($ams_entrance_user_new==''||$ams_entrance_user_new==NULL){
	$arr5['f']['active_news']=0;	
}else{
	if($u_id3==''){
		$arr5['f']['active_news']=0;	
	}else{
		$arr5['f']['active_news']=1;	
	}
	
}

##############
	
	
	$arr5['f']['branch_id']=$vk['branch_id'];
	$arr5_result[] = $arr5['f'];	
	#################################
	}
}
}
if($arr5_result==null){
	$arr5_result=null;
}
		$arr2['e']['entrance_set'] =$entranceset;
		$arr2['e']['entrance_data'] =$arr_entrance_data;
		$arr2['e']['directapply_data']=$arr5_result;
		$arr2['e']['directapply_sql']=$directapply_sql;
		$arr2['e']['branch_set'] =$branch_set;
		$arr2['e']['faculty_id'] =$faculty_id;
		$arr2['e']['university_id'] =$u_parent_id;
		$arr2['e']['university_name'] =$university_name;
		$arr2['e']['faculty_name'] =$faculty_name;
		$arr2['e']['u_group_id'] =$u_group_id;
		$arr2['e']['thumbnails'] =$thumbnailss;
		$arr2_result[] = $arr2['e'];	
		#################################
		}
}
}
	#################################
		$arr['d']['faculty_set']=$universityparent1_set;
		$arr['d']['faculty']=$arr2_result;
		//$arr['d']['universityparent1_sql']=$universityparent1['sql'];
		$arr['d']['university_id'] =$university_id;
		$arr['d']['thumbnail'] =$thumbnail;
		$arr['d']['university_name'] =$university_name;
		$arr_result[] = $arr['d'];	
################***university#################
 }
}
#################################
$dataall=array('data'=>$arr_result,
				'sql'=>$sql,
				'member_id'=>$member_id,
				'user_id'=>$user_id,
				'cache_key'=>$cache_key,
				'cache_key_decrypt'=>$cache_key_decrypt,
				);
if($arr_result!==''){
				$this->response(array('header'=>array(
										'title'=>' ams_entrance_user',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $dataall),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>' ams_entrance_user',
										'message'=>'Error',
										'status'=>false, 
										'code'=>404), 
										'data'=> $amsentranceuser),404);
				}

}
public function entranceuniversity_get(){
# http://www.trueplookpanya.com/api/admissiontrainer/entranceuniversity?year=2560&delekey=
$profile=@$this->tppy_member->get_member_profile();
$user_id=@$profile->user_id;
$user_username=@$profile->user_username;
$user_email=@$profile->user_email;
$psn_firstname=@$profile->psn_firstname;
$psn_lastname=@$profile->psn_lastname;
$psn_display_image=@$profile->psn_display_image;
####################
$year=@$this->input->get('year');
if($year==''){
	$y=date('Y');
	$y=$y+543;
	$year=$y-1;
}
$year=(int)$year;
$delekey=@$this->input->get('delekey');
if($delekey==''){$delekey='';}
$amsbyuniversity=$this->model_trainer->get_ams_university_ams_university_year($year,$delekey);
 #echo'<hr><pre>amsbyuniversity=>';print_r($amsbyuniversity);echo'<pre> <hr>'; Die();
$rs=$amsbyuniversity['data'];
$sql=$amsbyuniversity['sql'];
$arr_result=array();
if(is_array($rs)){
foreach ($rs as $key => $v) {
$arr = array();
	########################
	$u_group_id=(int)$v['u_group_id'];
	$u_parent_id=(int)$v['u_parent_id'];
	$u_id=(int)$v['university_id'];
	$university_id=(int)$v['university_id'];
	$university_name=$v['university_name'];
	$thumbnail=$v['thumbnail'];
	
	$universityparent1=$this->model_trainer->get_ams_university_u_parent_id_where($university_id,$delekey);
	#echo'<hr><pre>universityparent1=>';print_r($universityparent1);echo'<pre> <hr>'; Die();
	$universityparent1_data=$universityparent1['data'];
	$num=0;
	foreach($universityparent1_data as $k2=>$v2){
	$id=$v2['u_id'];
	$num++;
	}
	$universityparent1_set=implode(',', array_map(function($v2){ return $v2['u_id'];}, $universityparent1_data ));
#################################
	
##########arr2_result#######################
$arr2_result=array();
if(is_array($universityparent1_data)){
foreach ($universityparent1_data as $key3 => $v3) {
$arr2=array();
########################
$faculty_id=(int)$v3['u_id'];
$u_parent_id=(int)$v3['u_parent_id'];
$faculty_name=$v3['u_name'];
$thumbnailss=$v3['thumbnail'];
$u_group_id=$v3['u_group_id'];
$record_status=$v3['record_status'];

$arr2['e']['faculty_id'] =$faculty_id;
$arr2['e']['faculty_name'] =$faculty_name;
$arr2['e']['faculty_thumbnail'] =$thumbnailss;

######################	
$branch=$this->model_trainer->get_ams_university_u_parent_id_where($faculty_id,$delekey);
$branch_data=$branch['data'];
$num=0;foreach($branch_data as $k31=>$v31){$id3=$v31['u_id'];$num++;}
$branch_set=implode(',', array_map(function($v31){ return $v31['u_id'];}, $branch_data ));
$arr2['e']['branch_set'] =$branch_set;
#####################
$entrancedata=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branch_set,$year,$delekey);
$entrance_data=$entrancedata['data'];
$entrance_sql=$entrancedata['sql'];
$entrance_count=count($entrance_data);		
if($entrance_count==0){
$entrancedata=null;
}else{
	$entrancedata=$entrance_data;
######****************################	
#################
/*
$entrancedata_arr=array();
if(is_array($entrance_data)){
foreach ($entrance_data as $keyss=>$vv4){
$dataarr = array();
	$ent_id=(int)$vv4['ent_id'];
	$dataarr['e4']['ent_id']=$ent_id;
	$dataarr['e4']['u_id']=$vv4['u_id'];
	$dataarr['e4']['university_name']=$university_name;
	$dataarr['e4']['faculty_id']=$vv4['faculty_id'];
	$dataarr['e4']['faculty_name']=$vv4['faculty_name'];
	$dataarr['e4']['branch_id']=$vv4['branch_id'];
	$dataarr['e4']['branch_name']=$vv4['branch_name'];
	$dataarr['e4']['special_remark']=$vv4['special_remark'];
	$dataarr['e4']['year_config']=$vv4['year_config'];
	$dataarr['e4']['major_code']=$vv4['major_code'];
	$dataarr['e4']['major_remark']=$vv4['major_remark'];
	$dataarr['e4']['gpax_weight']=$vv4['gpax_weight'];
	$dataarr['e4']['gpax_min']=$vv4['gpax_min'];
	$dataarr['e4']['special_remark']=$vv4['special_remark'];
	$dataarr['e4']['receive_amount']=$vv4['receive_amount'];
	$dataarr['e4']['receive_amount_sharecode']=$vv4['receive_amount_sharecode'];
	$dataarr['e4']['lastupdate']=$vv4['lastupdate'];
	$dataarr['e4']['config']=$vv4['config'];
	$dataarr['e4']['score_min']=$vv4['score_min'];
	$dataarr['e4']['score_max']=$vv4['score_max'];
	$entrancedata_arr[] =$dataarr['e4'];	
 }
}
*/#################
######****************################		
}
#echo'<pre> $entrancedata_arr=>';print_r($entrancedata_arr);echo'<pre> <hr>'; die();
###############
if($entrance_count!==0){
	
 
	$arr2['e']['entrance_data'] =$entrancedata;
	$arr2_result[]=$arr2['e'];
}
#############	
	
 }
}
##########arr2_result#######################
	$arr['d']['faculty_set']=$universityparent1_set;
	$arr['d']['faculty']=$arr2_result;
	//$arr['d']['universityparent1_sql']=$universityparent1['sql'];
	$arr['d']['university_id'] =$university_id;
	$arr['d']['thumbnail'] =$thumbnail;
	$arr['d']['university_name'] =$university_name;
	$arr_result[] = $arr['d'];	
	#################################
	
}
}
#################################
$dataall=array('data'=>$arr_result,'user_id'=>$user_id);
if($arr_result!==''){
				$this->response(array('header'=>array(
										'title'=>' entranceuniversity',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $dataall),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>' entranceuniversity',
										'message'=>'Error',
										'status'=>false, 
										'code'=>404), 
										'data'=> $amsentranceuser),404);
				}
}
# userstatus
public function entranceuserstatus_get(){
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
$active=@$this->input->get('active');
$ent_id=@$this->input->get('ent_id');
$u_id=@$this->input->get('u_id');
$user_id=@$this->input->get('user_id');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$faculty_id=@$this->input->get('faculty_id');
if($faculty_id==''){
$faculty_id=@$this->input->get('directapply_faculty_id');
}

if($ent_id==''||$u_id==''||$user_id==''||$u_group_id_type3==''||$u_group_id_type4=='' ||$faculty_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'ent_id or u_id or user_id or u_group_id_type3 or u_group_id_type4 or  faculty_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

if($faculty_id==null ||$faculty_id==''){$faculty_id=0;}
$faculty_id=(int)$faculty_id;
$delekey=@$this->input->get('delekey');
if($delekey==''){$delekey=1;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id2($ent_id,$u_id,$user_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4,$delekey);
$ams_entrance_user_data=$ams_entrance_users['data'];
$status_rs=(int)$ams_entrance_users['status'];
$sqlrs1=$ams_entrance_users['sql'];
# echo'<pre>$active=>';print_r($active);echo'<pre> <hr>';
# echo'<pre>  ams_entrance_users=>';print_r($ams_entrance_users);echo'<pre> ';die();
//echo'<pre> status=>';print_r($status_rs);echo'<pre> active=>';print_r($active);echo'<pre> ';die();
#####################	
if($status_rs==0 && $active==1){
$table='ams_entrance_user';
$data_insert=array('ent_id'=>$ent_id,
                   'user_id'=>$user_id,
                   'u_id'=>$u_id,
                   'directapply_faculty_id'=>$faculty_id,
                   'year'=>$year,
                   'u_group_id_type3'=>$u_group_id_type3,
                   'u_group_id_type4'=>$u_group_id_type4,);
$insertdatatb=$this->model_trainer->insertamsentranceuser($data_insert);
$statusac='insert';
#######################
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id2($ent_id,$u_id,$user_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4,$delekey);
$ams_entrance_user_data=$ams_entrance_users['data']['0'];
$status_rs=(int)$ams_entrance_users['status'];
$sqlrs=$ams_entrance_users['sql'];
$rs=array(//'sql'=>$sqlrs,
          'rs'=>$ams_entrance_user_data,
          'status_rs'=>$status_rs,
          );
#######################

/*
echo'<hr>';

echo'<hr><pre>table_insert=>';print_r($table);echo'<pre> ';
echo'<pre>data_insert=>';print_r($data_insert);echo'<pre> ';	
echo'<pre>status_rs=>';print_r($status_rs);echo'<pre> ';	
echo'<pre>ams_entrance_user_data=>';print_r($ams_entrance_user_data);echo'<pre> ';

echo'<pre>insertdatatb=>';print_r($insertdatatb);echo'<pre>'; 
echo'<hr>';
Die();
*/

}elseif($status_rs==1&& $active==0){
//$remove_status=$this->model_trainer->deletedatatb3('ams_entrance_user','user_id',$id1,'u_id',$id2,'ent_id',$id3,'directapply_faculty_id',$id4);
$remove_status=$this->model_trainer->deleteams_entrance_user($ent_id,$user_id,$u_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4);
// echo'<pre>remove_status=>';print_r($remove_status);echo'<pre> <hr>'; Die();
$statusac='delete';
#######################
$rs=$remove_status;
#######################
}else{ 
#################################
$ams_entrance_user=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id2($ent_id,$u_id,$user_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4,$delekey);
//echo'<pre>ams_entrance_user=>';print_r($ams_entrance_user);echo'<pre> <hr>'; Die();
#################################
$sqlrs=$ams_entrance_user['sql'];
$ams_entrance_user=$ams_entrance_user['data']['0'];
$ent_id2=(int)$ams_entrance_user['ent_id'];
$u_id2=(int)$ams_entrance_user['u_id'];
$user_id2=(int)$ams_entrance_user['user_id'];
$faculty_id=(int)$ams_entrance_user['directapply_faculty_id'];
if($ams_entrance_user==null){
     $active=null;
     $statusac='select';
}else{ 
     $active=null;
     $statusac='select';
}
#################################
#######################
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id2($ent_id,$u_id,$user_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4,$delekey);
$ams_entrance_user_data=$ams_entrance_users['data']['0'];
$status_rs=(int)$ams_entrance_users['status'];
$sqlrs=$ams_entrance_users['sql'];
$rs=array(//'sql'=>$sqlrs,
          'datars'=>$ams_entrance_user_data,
          'status_rs'=>$status_rs,
          );
#######################
#################################
}
$dataall=array('active'=>$active,'message status'=>$statusac,'rs'=>$rs);
if($amsentranceuser!==''){
				$this->response(array('header'=>array('title'=>'ams_entrance_user',
										'message'=>'Success',
										'status'=>true,
										'code'=>200),
										'data'=>$dataall,'active'=>$active),200);
}else{
				  $this->response(array('header'=>array('title'=>'ams_entrance_user',
										'message'=>'Error',
										'status'=>false, 
										'code'=>404), 
										'data'=> null),404);
	}
}
public function entranceuserstatus_post(){
$year=@$this->input->post('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
$active=@$this->input->post('active');
$ent_id=@$this->input->post('ent_id');
$u_id=@$this->input->post('u_id');
$user_id=@$this->input->post('user_id');
$u_group_id_type3=@$this->input->post('u_group_id_type3');
$u_group_id_type4=@$this->input->post('u_group_id_type4');
$faculty_id=@$this->input->post('faculty_id');
if($faculty_id==''){
$faculty_id=@$this->input->post('directapply_faculty_id');
}

if($ent_id==''||$u_id==''||$user_id==''||$u_group_id_type3==''||$u_group_id_type4=='' ||$faculty_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'ent_id or u_id or user_id or u_group_id_type3 or u_group_id_type4 or  faculty_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

if($faculty_id==null ||$faculty_id==''){$faculty_id=0;}
$faculty_id=(int)$faculty_id;
$delekey=@$this->input->get('delekey');
if($delekey==''){$delekey=1;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id2($ent_id,$u_id,$user_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4,$delekey);
$ams_entrance_user_data=$ams_entrance_users['data'];
$status_rs=(int)$ams_entrance_users['status'];
$sqlrs1=$ams_entrance_users['sql'];
# echo'<pre>$active=>';print_r($active);echo'<pre> <hr>';
# echo'<pre>  ams_entrance_users=>';print_r($ams_entrance_users);echo'<pre> ';die();
//echo'<pre> status=>';print_r($status_rs);echo'<pre> active=>';print_r($active);echo'<pre> ';die();
#####################	
if($status_rs==0 && $active==1){
$table='ams_entrance_user';
$data_insert=array('ent_id'=>$ent_id,
                   'user_id'=>$user_id,
                   'u_id'=>$u_id,
                   'directapply_faculty_id'=>$faculty_id,
                   'year'=>$year,
                   'u_group_id_type3'=>$u_group_id_type3,
                   'u_group_id_type4'=>$u_group_id_type4,);
$insertdatatb=$this->model_trainer->insertamsentranceuser($data_insert);
$statusac='insert';
#######################
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id2($ent_id,$u_id,$user_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4,$delekey);
$ams_entrance_user_data=$ams_entrance_users['data']['0'];
$status_rs=(int)$ams_entrance_users['status'];
$sqlrs=$ams_entrance_users['sql'];
$rs=array(//'sql'=>$sqlrs,
          'rs'=>$ams_entrance_user_data,
          'status_rs'=>$status_rs,
          );
#######################

/*
echo'<hr>';

echo'<hr><pre>table_insert=>';print_r($table);echo'<pre> ';
echo'<pre>data_insert=>';print_r($data_insert);echo'<pre> ';	
echo'<pre>status_rs=>';print_r($status_rs);echo'<pre> ';	
echo'<pre>ams_entrance_user_data=>';print_r($ams_entrance_user_data);echo'<pre> ';

echo'<pre>insertdatatb=>';print_r($insertdatatb);echo'<pre>'; 
echo'<hr>';
Die();
*/

}elseif($status_rs==1&& $active==0){
//$remove_status=$this->model_trainer->deletedatatb3('ams_entrance_user','user_id',$id1,'u_id',$id2,'ent_id',$id3,'directapply_faculty_id',$id4);
$remove_status=$this->model_trainer->deleteams_entrance_user($ent_id,$user_id,$u_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4);
// echo'<pre>remove_status=>';print_r($remove_status);echo'<pre> <hr>'; Die();
$statusac='delete';
#######################
$rs=$remove_status;
#######################
}else{ 
#################################
$ams_entrance_user=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id2($ent_id,$u_id,$user_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4,$delekey);
//echo'<pre>ams_entrance_user=>';print_r($ams_entrance_user);echo'<pre> <hr>'; Die();
#################################
$sqlrs=$ams_entrance_user['sql'];
$ams_entrance_user=$ams_entrance_user['data']['0'];
$ent_id2=(int)$ams_entrance_user['ent_id'];
$u_id2=(int)$ams_entrance_user['u_id'];
$user_id2=(int)$ams_entrance_user['user_id'];
$faculty_id=(int)$ams_entrance_user['directapply_faculty_id'];
if($ams_entrance_user==null){
     $active=null;
     $statusac='select';
}else{ 
     $active=null;
     $statusac='select';
}
#################################
#######################
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id2($ent_id,$u_id,$user_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4,$delekey);
$ams_entrance_user_data=$ams_entrance_users['data']['0'];
$status_rs=(int)$ams_entrance_users['status'];
$sqlrs=$ams_entrance_users['sql'];
$rs=array(//'sql'=>$sqlrs,
          'datars'=>$ams_entrance_user_data,
          'status_rs'=>$status_rs,
          );
#######################
#################################
}
$dataall=array('active'=>$active,'message status'=>$statusac,'rs'=>$rs);
if($amsentranceuser!==''){
				$this->response(array('header'=>array('title'=>'ams_entrance_user',
										'message'=>'Success',
										'status'=>true,
										'code'=>200),
										'data'=>$dataall,'active'=>$active),200);
}else{
				  $this->response(array('header'=>array('title'=>'ams_entrance_user',
										'message'=>'Error',
										'status'=>false, 
										'code'=>404), 
										'data'=> null),404);
	}
}
# userstatus
public function group3ams_get(){
$user_id = $this->tppy_member->get_member_profile()->user_id;
$this->load->model('admissiontrainer_model', 'model_trainer');
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_group3_list=$this->model_trainer->get_ams_university_group3($delekey);
$university_group3_list_count=count($university_group3_list);
if($university_group3_list_count<=0){
					$this->response(array('header'=>array(
										'title'=>'University list group3',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
} 
#echo'<hr><pre> $university_group3_list=>';print_r($university_group3_list);echo'<pre> <hr>'; 
##################################
$num=0;foreach($university_group3_list as $k2=>$v2){$id=$v2['u_group_id'];$num++;}
$dataset=implode(',', array_map(function($v2){ return $v2['u_group_id'];}, $university_group3_list ));
$groupbyumaps=$this->model_trainer->get_groupby_university_map_join_ams_entrance($dataset,$delekey);
$groupbyumap=$groupbyumaps['data'];
$n=0;foreach($groupbyumap as $k3=>$v3){$v3['u_group_id'];$n++;}
$groupbyumapset=implode(',', array_map(function($v3){ return $v3['u_group_id'];}, $groupbyumap ));

$datagrup=array();
foreach($groupbyumap as $key => $value) {
	$datars=array();
#################
$u_group_id=(int)$value['u_group_id'];
$branch_id=(int)$value['branch_id'];
$datars['na']['group_id']=$u_group_id;
$group_university_map=$this->model_trainer->get_groupby_university_map_join_ams_entrance_byuid($u_group_id,$delekey);
$group_university_map_data=$group_university_map['data'];
$datars['na']['university']=$group_university_map_data;
$datars['na']['university_count']=count($group_university_map_data);
$datars['na']['faculty_id']=(int)$value['faculty_id'];
$datars['na']['branch_id']=$branch_id;
$datars['na']['group_name']=$value['group_name'];
$datars['na']['faculty_name']=$value['faculty_name'];
$datars['na']['branch_name']=$value['b_name'];
$datagrup[]=$datars['na'];
#################
}
$data=array('dataset'=>$dataset,
			'groupbyumapset'=>$groupbyumapset,
			'count'=>count($datagrup),
			'list'=>$datagrup,
			'user_id'=>$user_id,
			);
if($data){		$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
#############
}
public function group3amsuserlog_get(){
# http://www.trueplookpanya.com/api/admissiontrainer/group3amsuserlog?delekey=1
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=(int)$this->tppy_member->get_member_profile()->user_id;}else{$user_id=(int)$user_id;}
$this->load->model('admissiontrainer_model', 'model_trainer');
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_group3_list=$this->model_trainer->get_ams_university_group3($delekey);
$university_group3_list_count=count($university_group3_list);
if($university_group3_list_count<=0){
					$this->response(array('header'=>array(
										'title'=>'University list group3 ams user log',
										'message'=>'Data is null',
										'status'=>true, 
										'code'=>201), 
										'data'=> Null),201);
Die();
} 
#echo'<hr><pre> $university_group3_list=>';print_r($university_group3_list);echo'<pre> <hr>'; 
##################################
$num=0;foreach($university_group3_list as $k2=>$v2){$id=$v2['u_group_id'];$num++;}
$dataset=implode(',', array_map(function($v2){ return $v2['u_group_id'];}, $university_group3_list ));
#echo'<hr><pre>dataset=>';print_r($dataset);echo'<pre><hr>'; Die();
$groupbyumaps=$this->model_trainer->get_groupby_university_map_join_ams_entrance_user_id($dataset,$user_id,$delekey);
$groupbyumap_sql=$groupbyumaps['sql'];
#echo'<hr><pre>$groupbyumaps=>';print_r($groupbyumaps);echo'<pre><hr>'; Die();
$groupbyumap=$groupbyumaps['data'];
$n=0;foreach($groupbyumap as $k3=>$v3){$v3['u_group_id'];$n++;}
$groupbyumapset=implode(',', array_map(function($v3){ return $v3['u_group_id'];}, $groupbyumap ));

$datagrup=array();
foreach($groupbyumap as $key => $value) {
#################
$datars=array();
$u_group_id=(int)$value['u_group_id'];
$branch_id=(int)$value['branch_id'];
$datars['na']['group_id']=$u_group_id;
$group_university_map=$this->model_trainer->get_groupby_university_map_join_ams_entrance_byuid_user_id($u_group_id,$user_id,$delekey);
$group_university_map_data=$group_university_map['data'];
//$datars['na']['university_sql']=$group_university_map['sql'];
$datars['na']['university']=$group_university_map_data;
$datars['na']['university_count']=count($group_university_map_data);
$datars['na']['faculty_id']=(int)$value['faculty_id'];
$datars['na']['branch_id']=$branch_id;
$datars['na']['group_name']=$value['group_name'];
$datars['na']['faculty_name']=$value['faculty_name'];
$datars['na']['branch_name']=$value['b_name'];
$datagrup[]=$datars['na'];
#################
}
$data=array('dataset'=>$dataset,
			'groupbyumapset'=>$groupbyumapset,
			'count'=>count($datagrup),
			'list'=>$datagrup,
			//'sql'=>$groupbyumap_sql,
			'user_id'=>$user_id,
			);
if($data){		$this->response(array('header'=>array(
										'title'=>'University list group3 ams user log',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'University list group3 ams user log',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
#############
}
public function entrancefaculty_get(){
$profile=@$this->tppy_member->get_member_profile();
$user_id=@$profile->user_id;
$member_id=@$profile->member_id;
$user_username=@$profile->user_username;
$user_email=@$profile->user_email;
$psn_firstname=@$profile->psn_firstname;
$psn_lastname=@$profile->psn_lastname;
$psn_display_image=@$profile->psn_display_image;
####################
$year=@$this->input->get('year');
if($year==''){
	$y=date('Y');
	$y=$y+543;
	$year=$y-1;
}
$year=(int)$year;
$delekey=@$this->input->get('delekey');
if($delekey==''){$delekey='';}
$amsbyuniversity=$this->model_trainer->get_ams_university_ams_university_year($year,$delekey);
 #echo'<hr><pre>amsbyuniversity=>';print_r($amsbyuniversity);echo'<pre> <hr>'; Die();
$rs=$amsbyuniversity['data'];
$sql=$amsbyuniversity['sql'];
$arr_university = array();
if(is_array($rs)){
foreach ($rs as $key => $v) {
	$arr = array();
	########################
	$u_group_id=(int)$v['u_group_id'];
	$u_parent_id=(int)$v['u_parent_id'];
	$u_id=(int)$v['university_id'];
	$university_id=(int)$v['university_id'];
	$university_name=$v['university_name'];
	$thumbnail=$v['thumbnail'];
	
$universityparent1=$this->model_trainer->get_ams_university_u_parent_id_where($university_id,$delekey);
#echo'<hr><pre>universityparent1=>';print_r($universityparent1);echo'<pre> <hr>'; Die();
$universityparent1_data=$universityparent1['data'];
$universityparent1_set=implode(',', array_map(function($universityparent1_data){ return $universityparent1_data['u_id'];}, $universityparent1_data ));


#echo'<hr><pre>arna_result=>';print_r($arna_result);echo'<pre> <hr>';  
#echo'<hr><pre>universityparent1_set=>';print_r($universityparent1_set);echo'<pre> <hr>'; Die();
#################################

##########arr2_result#######################
$arr_faculty = array();
if(is_array($universityparent1_data)){
foreach ($universityparent1_data as $key3 => $v3) {
	$arr2=array();
	########################
	$faculty_id=(int)$v3['u_id'];
	$u_parent_id=(int)$v3['u_parent_id'];
	$faculty_name=$v3['u_name'];
	$thumbnailss=$v3['thumbnail'];
	$u_group_id=$v3['u_group_id'];
	$record_status=$v3['record_status'];
	$arr2['e']['faculty_id'] =$faculty_id;
	$arr2['e']['university_name'] =$university_name;
	$arr2['e']['faculty_name'] =$faculty_name;
	$arr2['e']['faculty_thumbnail'] =$thumbnailss;
	######################	
	$branch=$this->model_trainer->get_ams_university_u_parent_id_where($faculty_id,$delekey);
	$branchdata=$branch['data'];
	#####################
	$arr_ds = array();
	if(is_array($branchdata)){
	  foreach ($branchdata as $keyb => $val) {
		$arr_rs=array();
		#############
		$arr_rs['branch']['university_name']=$university_name;
		$arr_rs['branch']['faculty_id']=$val['u_parent_id'];
		$arr_rs['branch']['faculty_name']=$faculty_name;
		$arr_rs['branch']['branch_id']=$val['u_id'];
		$arr_rs['branch']['branch_name']=$val['u_name'];
		$arr_rs['branch']['u_group_id']=$val['u_group_id'];
		$arr_rs['branch']['branch_status']=$val['record_status'];
		#############
	  $arr_ds[]=$arr_rs['branch'];
	  }
	}
	$branch_data=$arr_ds;
	#####################
	$branch_sql=$branch['sql'];
	$branch_set=implode(',', array_map(function($branch_data){ return $branch_data['branch_id'];}, $branch_data ));
	$arr2['e']['branch_data']=$branch_data;
	$arr2['e']['branch_set']=$branch_set;
	
	
if($user_id==''){
$entrancedata=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branch_set,$year,$delekey);
}else{
$entrancedata=$this->model_trainer->get_ams_university_entrance_score_dataset_year_by_user_id($branch_set,$year,$user_id,$delekey);
}	
$entrance_data=$entrancedata['data'];
$entrance_sql=$entrancedata['sql'];
$entrance_count=count($entrance_data);		
if($entrance_count==0){$entrancedata=null;}else{
################################
$datasetamss = array();
if (is_array($entrance_data)) {
	foreach ($entrance_data as $kas => $vaams) {
	$dataset = array();
		$ent_id=$vaams['ent_id'];
		$dataset['ams'][$kas]['year_config']=$vaams['year_config'];
		$dataset['ams'][$kas]['major_remark']=$vaams['major_remark'];
		$branch_id=$vaams['u_id'];
		$score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
		$dataset['ams'][$kas]['score_history']=$score_history;
		$dataset['ams'][$kas]['ent_id']=$vaams['ent_id'];
		/*
		$dataset['ams'][$kas]['01_onet_mi']=$vaams['01_onet_mi'];
		$dataset['ams'][$kas]['01_onet_weight']=$vaams['01_onet_weight'];
		$dataset['ams'][$kas]['02_onet_min']=$vaams['02_onet_min'];
		$dataset['ams'][$kas]['02_onet_weight']=$vaams['02_onet_weight'];
		$dataset['ams'][$kas]['03_onet_mint']=$vaams['03_onet_mint'];
		$dataset['ams'][$kas]['03_onet_weight']=$vaams['03_onet_weight'];
		$dataset['ams'][$kas]['04_onet_min']=$vaams['04_onet_min'];
		$dataset['ams'][$kas]['04_onet_weight']=$vaams['04_onet_weight'];
		$dataset['ams'][$kas]['05_onet_min']=$vaams['05_onet_min'];
		$dataset['ams'][$kas]['05_onet_weight']=$vaams['05_onet_weight'];
		$dataset['ams'][$kas]['71_pat1_min']=$vaams['71_pat1_min'];
		$dataset['ams'][$kas]['71_pat1_weight']=$vaams['71_pat1_weight'];
		$dataset['ams'][$kas]['72_pat2_min']=$vaams['72_pat2_min'];
		$dataset['ams'][$kas]['72_pat2_weight']=$vaams['72_pat2_weight'];
		$dataset['ams'][$kas]['73_pat3_min']=$vaams['73_pat3_min'];
		$dataset['ams'][$kas]['73_pat3_weight']=$vaams['73_pat3_weight'];
		$dataset['ams'][$kas]['74_pat4_min']=$vaams['74_pat4_min'];
		$dataset['ams'][$kas]['74_pat4_weight']=$vaams['74_pat4_weight'];
		$dataset['ams'][$kas]['75_pat5_min']=$vaams['75_pat5_min'];
		$dataset['ams'][$kas]['75_pat5_weight']=$vaams['75_pat5_weight'];
		$dataset['ams'][$kas]['76_pat6_min']=$vaams['76_pat6_min'];
		$dataset['ams'][$kas]['76_pat6_weight']=$vaams['76_pat6_weight'];
		$dataset['ams'][$kas]['77_pat71_min']=$vaams['77_pat71_min'];
		$dataset['ams'][$kas]['77_pat71_weight']=$vaams['77_pat71_weight'];
		$dataset['ams'][$kas]['78_pat72_min']=$vaams['78_pat72_min'];
		$dataset['ams'][$kas]['78_pat72_weight']=$vaams['78_pat72_weight'];
		$dataset['ams'][$kas]['79_pat73_min']=$vaams['79_pat73_min'];
		$dataset['ams'][$kas]['79_pat73_weight']=$vaams['79_pat73_weight'];
		$dataset['ams'][$kas]['80_pat74_min']=$vaams['80_pat74_min'];
		$dataset['ams'][$kas]['80_pat74_weight']=$vaams['80_pat74_weight'];
		$dataset['ams'][$kas]['81_pat75_min']=$vaams['81_pat75_min'];
		$dataset['ams'][$kas]['81_pat75_weight']=$vaams['81_pat75_weight'];
		$dataset['ams'][$kas]['82_pat76_min']=$vaams['82_pat76_min'];
		$dataset['ams'][$kas]['82_pat76_weight']=$vaams['82_pat76_weight'];
		$dataset['ams'][$kas]['85_gat_min']=$vaams['85_gat_min'];
		$dataset['ams'][$kas]['85_gat_min_part2']=$vaams['85_gat_min_part2'];
		$dataset['ams'][$kas]['85_gat_weight']=$vaams['85_gat_weight'];
		*/
		$dataset['ams'][$kas]['branch_id']=$vaams['branch_id'];
		$dataset['ams'][$kas]['branch_name']=$vaams['branch_name'];
		$dataset['ams'][$kas]['config']=$vaams['config'];
		$dataset['ams'][$kas]['faculty_id']=$vaams['faculty_id'];
		$dataset['ams'][$kas]['faculty_name']=$vaams['faculty_name'];
		$dataset['ams'][$kas]['gpax_min']=$vaams['gpax_min'];
		$dataset['ams'][$kas]['gpax_weight']=$vaams['gpax_weight'];
		$dataset['ams'][$kas]['major_code']=$vaams['major_code'];
		$dataset['ams'][$kas]['major_remark']=$vaams['major_remark'];
		$dataset['ams'][$kas]['onet_min_total']=$vaams['onet_min_total'];
		$dataset['ams'][$kas]['onet_weight_total']=$vaams['onet_weight_total'];
		$dataset['ams'][$kas]['receive_amount']=$vaams['receive_amount'];
		$dataset['ams'][$kas]['receive_amount_sharecode']=$vaams['receive_amount_sharecode'];
		$dataset['ams'][$kas]['score_max']=$vaams['score_max'];
		$dataset['ams'][$kas]['score_min']=$vaams['score_min'];
		$dataset['ams'][$kas]['special_remark']=$vaams['special_remark'];
		//$dataset['ams'][$kas]['lastupdate']=$vaams['lastupdate'];
	$datasetamss[]=$dataset['ams'][$kas];
	}
}
################################
$entrancedata=$datasetamss;	
}
$arr2['e']['entrancedata']=$entrancedata;
$arr2['e']['branch_sql']=$branch_sql;
$arr2['e']['entrance_data'] =$entrancedata;
if($user_id==''){
$dirapply=$this->model_trainer->get_ams_entrance_dirapply_year_set($year,$branch_set,$delekey);
}else{
$dirapply=$this->model_trainer->get_ams_entrance_dirapply_year_set_user_id($year,$branch_set,$user_id,$delekey);
}	
$dirapply_data=$dirapply['data'];
$dirapply_sql=$dirapply['sql'];
	#####################
	$arr_dirapply = array();
	if(is_array($dirapply_data)){
	foreach ($dirapply_data as $keyap => $vap) {
		$dirapply=array();
		#############
		$dirapply['app']['news_id']=$vap['news_id'];
		$dirapply['app']['news_title']=$vap['news_title'];
		$dirapply['app']['major_remark']=$vap['major_remark'];
		$dirapply['app']['yearnews']=$vap['yearnews'];
		$dirapply['app']['faculty_name']=$vap['faculty_name'];
		$dirapply['app']['branch_name']=$vap['branch_name'];
		$dirapply['app']['university_name']=$vap['university_name'];
		$dirapply['app']['branch_status']=$vap['record_status'];
		#############
	$arr_dirapply[]=$dirapply['app'];
	}
	}else{ $arr_dirapply=null;}
	$dirapply_data=$arr_dirapply;
	#####################
$arr2['e']['entrance_dirapply_data'] =$dirapply_data;
	//$arr2['e']['entrance_sql'] =$entrance_sql;
	$arr_faculty[]=$arr2['e'];
	#############	
	}
}

#########################################################################
	//$arr['d']['universityparent1_data']=$universityparent1_data;
	$arr['d']['faculty_set']=$universityparent1_set;
	$arr['d']['faculty']=$arr_faculty;
	$arr['d']['universityparent1_sql']=$universityparent1['sql'];
	$arr['d']['university_id'] =$university_id;
	$arr['d']['thumbnail'] =$thumbnail;
	$arr['d']['university_name'] =$university_name;
	$arr_university[] = $arr['d'];	
#########################################################################	
 }
}
#################################
$university_group3_list=$this->model_trainer->get_ams_university_group3($delekey);
$university_group3_list_count=count($university_group3_list);
if($university_group3_list_count<=0){$data_university_group=null;} 
#echo'<hr><pre> $university_group3_list=>';print_r($university_group3_list);echo'<pre> <hr>'; 
##################################
$num=0;foreach($university_group3_list as $k2=>$v2){$id=$v2['u_group_id'];$num++;}
$dataset=implode(',', array_map(function($v2){ return $v2['u_group_id'];}, $university_group3_list ));
$groupbyumaps=$this->model_trainer->get_groupby_university_map_join_ams_entrance($dataset,$delekey);
$groupbyumap=$groupbyumaps['data'];
$arr_groupbyumap=array();
if(is_array($groupbyumap)){
foreach ($groupbyumap as $keyg=> $vg) {
	$arrg=array();
	$arrg['map']['u_group_id'] =$vg['u_group_id'];
	$arr_groupbyumap[]=$arrg['map'];	
 }
}
$groupbyumapset=implode(',', array_map(function($arr_groupbyumap){ return $arr_groupbyumap['u_group_id'];}, $arr_groupbyumap ));

$datagroup=array();
foreach($groupbyumap as $key => $value) {
#################
$datars=array();
$u_group_id=(int)$value['u_group_id'];
$branch_id=(int)$value['branch_id'];
$datars['na']['group_id']=$u_group_id;
$group_university_map=$this->model_trainer->get_groupby_university_map_join_ams_entrance_byuid($u_group_id,$delekey);
$group_university_map_data=$group_university_map['data'];
$datars['na']['university']=$group_university_map_data;
$datars['na']['university_count']=count($group_university_map_data);
$datars['na']['faculty_id']=(int)$value['faculty_id'];
$datars['na']['branch_id']=$branch_id;
$datars['na']['group_name']=$value['group_name'];
$datars['na']['faculty_name']=$value['faculty_name'];
$datars['na']['branch_name']=$value['b_name'];
$datagroup[]=$datars['na'];
#################
}
$data_university_group=array('dataset'=>$dataset,
			'groupbyumapset'=>$groupbyumapset,
			'count'=>count($datagroup),
			'list'=>$datagroup,
			'user_id'=>$user_id,
			);
			

$dataall=array('university_list'=>$arr_university,
				'data_university_group'=>$data_university_group,
				'user_id'=>$user_id,
				'member_id'=>$member_id
				);

if($dataall){		
$this->response(array('header'=>array(
										'title'=>'entrancefaculty',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $dataall),200);
}else{
$this->response(array('header'=>array(
										'title'=>'entrancefaculty',
										'message'=>'Error404',
										'status'=>true, 
										'code'=>404), 
										'data'=> $dataall),404);
}
#############
}
public function universitylistgroup4listgroupidams2018_get(){
$user_id=@$this->tppy_member->get_member_profile()->user_id;
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
$member_id=@$this->tppy_member->get_member_profile()->member_id;
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id=@$this->input->get('u_group_id');
if($u_group_id<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
if($delekey==''){$delekey='';}else{$delekey='1';}
$university_group=$this->model_trainer->get_ams_university_group_id($u_group_id,$delekey);
//echo'<pre> university_group=>';print_r($university_group);echo'<pre>';  die();
$university_group4=$university_group;
$sql1=$university_group['sql'];
$university_group4_count=count($university_group4);
if($university_group4_count<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>201), 
									  'data'=> Null),201);
Die();
}
#echo'<hr><pre> $university_group4=>';print_r($university_group4);echo'<pre> <hr>'; 
$datalist=array();
 foreach($university_group4 as $key => $value) {
 	$datars=array();
       $u_group_id=$value['u_group_id'];
       $datars[$key]['group_id']=$u_group_id;
       $datars[$key]['u_group_name']=$value['u_group_name'];
       $datars[$key]['short_description']=$value['short_description'];
       $datars[$key]['thumbnail']=$value['thumbnail'];
       $datars[$key]['detail']=$value['detail'];
       $datars[$key]['u_group_type']=$value['u_group_type'];
       $datars[$key]['record_status']=$value['record_status'];
       $datars[$key]['add_timestamp']=$value['add_timestamp'];
       $datars[$key]['update_timestamp']=$value['update_timestamp'];
 #################
 $datalist=$datars[$key];
}
#echo'<pre>datalist=>';print_r($datalist);echo'<pre>';  //die();
$university_group4=$university_group4['0'];
#echo'<pre>$university_group4=>';print_r($university_group4);echo'<pre>';  //die();
$u_group_id=$university_group4['u_group_id'];
$u_group_name=$university_group4['u_group_name'];
$short_description=$university_group4['short_description'];
#echo'<pre>u_group_id=>';print_r($u_group_id);echo'<pre>';  
$group_in_data=$this->model_trainer->get_group_type4_u_group_id_list($u_group_id,$delekey);
//echo'<pre> group_in_data=>';print_r($group_in_data);echo'<pre>';  die();
###############################
$arr_result4 = array();
if (is_array($group_in_data)) {
	foreach ($group_in_data as $key4 => $val4) {
#################
	$data4 = array();
     $university_id=$val4['university_id'];
     if($university_id==''){}else{
     ################
     $branchid=$val4['branch_id'];
     
###########################################################################

###########################################################################
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year; }
#################
$amss=$this->model_trainer->get_ams_university_entrance_score_dataset_year($branchid,$year,$delekey);
$ams=$amss['data'];
#echo '<hr><pre>  $ams=>'; print_r($ams); echo '</pre>';die();
if(!$ams){
	$group_ams_data_count=0;
}
#################
$datasetamss = array();
if (is_array($ams)) {
  foreach ($ams as $keydatas => $val) {
  	$dataset=array();
      $ent_id=$val['ent_id'];
      $dataset['dd'][$keydatas]['year_config']=$val['year_config'];
      $dataset['dd'][$keydatas]['major_remark']=$val['major_remark'];
      $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
      $dataset['dd'][$keydatas]['score_history']=$score_history;
      $dataset['dd'][$keydatas]['ent_id']=$val['ent_id'];
      #################
      $dataset['dd'][$keydatas]['01_onet_mi']=$val['01_onet_mi'];
      $dataset['dd'][$keydatas]['01_onet_weight']=$val['01_onet_weight'];
      $dataset['dd'][$keydatas]['02_onet_min']=$val['02_onet_min'];
      $dataset['dd'][$keydatas]['02_onet_weight']=$val['02_onet_weight'];
      $dataset['dd'][$keydatas]['03_onet_mint']=$val['03_onet_mint'];
      $dataset['dd'][$keydatas]['03_onet_weight']=$val['03_onet_weight'];
      $dataset['dd'][$keydatas]['04_onet_min']=$val['04_onet_min'];
      $dataset['dd'][$keydatas]['04_onet_weight']=$val['04_onet_weight'];
      $dataset['dd'][$keydatas]['05_onet_min']=$val['05_onet_min'];
      $dataset['dd'][$keydatas]['05_onet_weight']=$val['05_onet_weight'];
      $dataset['dd'][$keydatas]['71_pat1_min']=$val['71_pat1_min'];
      $dataset['dd'][$keydatas]['71_pat1_weight']=$val['71_pat1_weight'];
      $dataset['dd'][$keydatas]['72_pat2_min']=$val['72_pat2_min'];
      $dataset['dd'][$keydatas]['72_pat2_weight']=$val['72_pat2_weight'];
      $dataset['dd'][$keydatas]['73_pat3_min']=$val['73_pat3_min'];
      $dataset['dd'][$keydatas]['73_pat3_weight']=$val['73_pat3_weight'];
      $dataset['dd'][$keydatas]['74_pat4_min']=$val['74_pat4_min'];
      $dataset['dd'][$keydatas]['74_pat4_weight']=$val['74_pat4_weight'];
      $dataset['dd'][$keydatas]['75_pat5_min']=$val['75_pat5_min'];
      $dataset['dd'][$keydatas]['75_pat5_weight']=$val['75_pat5_weight'];
      $dataset['dd'][$keydatas]['76_pat6_min']=$val['76_pat6_min'];
      $dataset['dd'][$keydatas]['76_pat6_weight']=$val['76_pat6_weight'];
      $dataset['dd'][$keydatas]['77_pat71_min']=$val['77_pat71_min'];
      $dataset['dd'][$keydatas]['77_pat71_weight']=$val['77_pat71_weight'];
      $dataset['dd'][$keydatas]['78_pat72_min']=$val['78_pat72_min'];
      $dataset['dd'][$keydatas]['78_pat72_weight']=$val['78_pat72_weight'];
      $dataset['dd'][$keydatas]['79_pat73_min']=$val['79_pat73_min'];
      $dataset['dd'][$keydatas]['79_pat73_weight']=$val['79_pat73_weight'];
      $dataset['dd'][$keydatas]['80_pat74_min']=$val['80_pat74_min'];
      $dataset['dd'][$keydatas]['80_pat74_weight']=$val['80_pat74_weight'];
      $dataset['dd'][$keydatas]['81_pat75_min']=$val['81_pat75_min'];
      $dataset['dd'][$keydatas]['81_pat75_weight']=$val['81_pat75_weight'];
      $dataset['dd'][$keydatas]['82_pat76_min']=$val['82_pat76_min'];
      $dataset['dd'][$keydatas]['82_pat76_weight']=$val['82_pat76_weight'];
      $dataset['dd'][$keydatas]['85_gat_min']=$val['85_gat_min'];
      $dataset['dd'][$keydatas]['85_gat_min_part2']=$val['85_gat_min_part2'];
      $dataset['dd'][$keydatas]['85_gat_weight']=$val['85_gat_weight'];
      $dataset['dd'][$keydatas]['branch_id']=$val['branch_id'];
      $dataset['dd'][$keydatas]['branch_name']=$val['branch_name'];
      $dataset['dd'][$keydatas]['config']=$val['config'];
      $dataset['dd'][$keydatas]['university_name']=$val['university_name'];
      $dataset['dd'][$keydatas]['faculty_id']=$val['faculty_id'];
      $dataset['dd'][$keydatas]['faculty_name']=$val['faculty_name'];
      $dataset['dd'][$keydatas]['gpax_min']=$val['gpax_min'];
      $dataset['dd'][$keydatas]['gpax_weight']=$val['gpax_weight'];
      $dataset['dd'][$keydatas]['major_code']=$val['major_code'];
      $dataset['dd'][$keydatas]['major_remark']=$val['major_remark'];
      $dataset['dd'][$keydatas]['onet_min_total']=$val['onet_min_total'];
      $dataset['dd'][$keydatas]['onet_weight_total']=$val['onet_weight_total'];
      $dataset['dd'][$keydatas]['receive_amount']=$val['receive_amount'];
      $dataset['dd'][$keydatas]['receive_amount_sharecode']=$val['receive_amount_sharecode'];
      $dataset['dd'][$keydatas]['score_max']=$val['score_max'];
      $dataset['dd'][$keydatas]['score_min']=$val['score_min'];
      $dataset['dd'][$keydatas]['special_remark']=$val['special_remark'];
      $dataset['dd'][$keydatas]['lastupdate']=$val['lastupdate'];
      $datasetamss[]=$dataset['dd'][$keydatas];
  }
}else{ $datasetamss=null; }
$group_ams_data_count=count($datasetamss);
###########################################################################
###########################################################################
if($datasetamss==null){}else{
     $data4['d']['entrance_list']=$datasetamss;
     $data4['d']['entrance_list_count']=$group_ams_data_count;
     $data4['d']['university_parent_id']=$val4['university_parent_id'];
     $data4['d']['university_id']=$val4['university_id'];
     $data4['d']['branch_id']=$val4['branch_id'];
     $data4['d']['faculty_id']=$val4['faculty_id'];
     $data4['d']['map_id']=$val4['map_id'];
     $data4['d']['group_type']=$val4['u_group_type'];
     $data4['d']['group_id']=$val4['group_id'];
     $data4['d']['university_name']=$val4['university_name'];
     $thumbnail=$val4['thumbnail'];
     $data4['d']['thumbnail']=$thumbnail;
     $data4['d']['branch_name']=$val4['branch_name'];
     $data4['d']['faculty_name']=$val4['faculty_name'];
     $data4['d']['group_name']=$val4['group_name'];
     $data4['d']['group_description']=$val4['group_description'];
     ################ 
     $arr_result4[] = $data4['d'];
}
###########################################################################
      
     ################ 
     }
     
   }#################
 }else{ $arr_result4=null; }
$list=$arr_result4;
$group_in_data_count=count($list);
##############################
$branch_set=implode(',', array_map(function($list){ return $list['branch_id'];}, $list ));
#$faculty_id_id_set=implode(',', array_map(function($list){ return $list['faculty_id'];}, $list ));

$group_type3name=$this->model_trainer->get_group_type3_id_list($u_group_id,$delekey);
$group_type3_name=$group_type3name['0'];
$data=array('count'=>$group_in_data_count,
			'u_group_id'=>$u_group_id,
			'group_name'=>$u_group_name,
			'branch_set'=>$branch_set,
	 		'list'=>$list,
	 		'user_id'=>$user_id,);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										#'count'=>$count,
										'code'=>200), 
										'data'=> $data),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
     
#############
}
public function facebookshareviewcount_get(){
$delekey=@$this->input->get('delekey');
$url=@$this->input->get('url');
$urlcall="http://www.trueplookpanya.com/knowledge/content/65845/-blo-scibio-sci-";
$url = 'http://www.texashillcountry.com';
$apiUrl = 'https://graph.facebook.com/?ids='.$urlcall;
//open connection
$ch = curl_init();
$timeout=5;
//set the url
curl_setopt($ch,CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); 
//execute post
$result = curl_exec($ch);
//close connection
curl_close($ch);
$data = json_decode($result,true);
echo '<pre>';print_r($data);echo '</pre>';
echo '<p>Likes: '.number_format($data[$url]['share']['share_count']).'</p>'; die();


$str = file_get_contents($apiUrl);
$json=json_decode($str, true);
echo'<pre>apiUrl=>';print_r($apiUrl);echo'<pre> <hr>';
echo'<pre>$json=>';print_r($json);echo'<pre> <hr>'; die();
 
//echo'<pre>$url=>';print_r($url);echo'<pre> <hr>'; die();
$cache_key="get_facebook_viewcount_url".$url;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
     $apiUrl="https://graph.facebook.com/?ids=".$url;
     //echo'<pre>api_Url=>';print_r($apiUrl);echo'<pre> <hr>';
     $str=file_get_contents($apiUrl);
	$json=json_decode($str,true);
     //echo'<pre>jsondata=>';print_r($json);echo'<pre> <hr>'; die();
	$facebookshareviewcount=$json->share->share_count;
	$data=$facebookshareviewcount;
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('data'=>$data);
}else{
	$dataall=array('data'=>$data);
}
#############################
if($dataall){
				$this->response(array('header'=>array(
										'title'=>'facebook viewcount',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $viewcount),200);
				}else{
				  $this->response(array('header'=>array(
										'title'=>'facebook viewcount',
										'message'=>'Error',
										'status'=>true, 
										'code'=>404), 
										'data'=> null),404);
				}
}  
#api top branch
public function topbranchuniversityentranceuserall_get(){
$user_id=@$this->input->get('user_id');
$delekey=@$this->input->get('delekey');
$tab=@$this->input->get('tab');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey=null;}
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'top count branch university',
									  'message'=>'Error user_id is null',
									  'status'=>true, 
									  'code'=>404), 
                                               'count'=>0,
									  'data'=> Null),404);
Die();
}
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
$typeid=1;
$branchgroup=$this->model_trainer->get_branch_group_type4_map_year_by_user($year,$user_id,$delekey);
$branchgroup_data=$branchgroup['data'];
$branchgroup_sql=$branchgroup['sql'];
if (is_array($branchgroup_data)) {
$branchm=array();
foreach($branchgroup_data as $key => $value) {
$data_branchm=array();

     $branch_id=(int)$value['branch_id'];
     $data_branchm['na']['branch_id']=$branch_id;
     $data_branchm['na']['group_id']=$value['u_group_id'];
     $data_branchm['na']['branch_name']=$value['branch_name'];
     $data_branchm['na']['group_type']=$value['u_group_type'];
     $data_branchm['na']['thumbnails']=$value['thumbnails'];
     $data_branchm['na']['year']=$value['year'];
$branchm[]=$data_branchm['na'];
 }
}else{$branchm=null;}
$branchmaster_set=implode(',', array_map(function($branchm){ return $branchm['branch_id'];}, $branchm ));

$dataall=array('branchmaster'=>$branchm,
               'branchgroup_sql'=>$branchgroup_sql,
               'branchmaster_count'=>count($branchm),
               'branchmaster_set'=>$branchmaster_set,
               'user_id'=>$user_id,
               );
if($branchgroup_data){
$this->response(array('header'=>array('title'=>'top count branch university ',
							   'message'=>'Success',
							   'status'=>true,
							   'code'=>200), 
                                      'data'=> $dataall),200);
}else{
$this->response(array('header'=>array('title'=>'top count branch university',
							   'message'=>'Error',
							   'status'=>true, 
							   'code'=>404), 
							   'data'=> null),404);
 }
}
#2560-03-30
public function universitylistgroup4listgroupidamstest_get(){
$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
$tab=@$this->input->get('tab');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$u_group_id=@$this->input->get('u_group_id');
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
######################################################
if($tab==''){ $tab=null; }
if($delekey==''){$delekey='';}else{$delekey='1';}
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'ams entrance group',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

$member_id=@$this->tppy_member->get_member_profile()->member_id;
$this->load->model('admissiontrainer_model', 'model_trainer');
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
$universitygroup4=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);
$university_group4=$universitygroup4['0'];
$group_type4_name=$university_group4['u_group_name'];
 
if($u_group_id<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$university_group=$this->model_trainer->get_ams_university_group_id($u_group_id,$delekey);
$university_group4=$university_group;
$sql1=$university_group['sql'];
$university_group4_count=count($university_group4);
if($university_group4_count<=0){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>201), 
									  'data'=> Null),201);
Die();
}
$datalist=array();
 foreach($university_group4 as $key => $value) {
 	  $datars=array();
       $u_group_id=$value['u_group_id'];
       $datars['na']['group_id']=$u_group_id;
       $datars['na']['u_group_name']=$value['u_group_name'];
       $datars['na']['short_description']=$value['short_description'];
       $datars['na']['thumbnail']=$value['thumbnail'];
       $datars['na']['detail']=$value['detail'];
       $datars['na']['u_group_type']=$value['u_group_type'];
       $datars['na']['record_status']=$value['record_status'];
       $datars['na']['add_timestamp']=$value['add_timestamp'];
       $datars['na']['update_timestamp']=$value['update_timestamp'];
 #################
 $datalist[]=$datars['na'];
}
#echo'<pre>datalist=>';print_r($datalist);echo'<pre>';die();
$groupsid=(int)$datalist['0']['group_id'];
$universityname=$this->model_trainer->get_grouptype4_entrance_university_groupid_year($groupsid,$year,$delekey);
#echo'<pre> universityname=>';print_r($universityname);echo'<pre> <hr>'; die();
$universityname_data=$universityname['data'];
$universityname_sql=$universityname['sql'];
####################*****university*******#############################
$arr_result4 = array();
if (is_array($universityname_data)) {
  foreach ($universityname_data as $key4 => $val4) {
  $university_id=(int)$val4['university_id'];
  $data4['d']= array();
	$data4['d']['university_id']=$university_id;
     $data4['d']['u_group_id_type3']=$u_group_id_type3;
     $data4['d']['u_group_id_type4']=$u_group_id_type4;
     $data4['d']['university_parent_id']=(int)$val4['u_parent_id'];
	$data4['d']['university_name']=$val4['university_name'];
     $group_id=(int)$val4['u_group_id'];
     $data4['d']['group_id']=$group_id;
     $data4['d']['group_name']=$val4['u_group_name'];
     $data4['d']['faculty_id']=(int)$val4['faculty_id'];
     $data4['d']['faculty_name']=$val4['faculty_name'];
     $data4['d']['branch_name']=(int)$val4['ent_u_id'];
     $data4['d']['branch_name']=$val4['branch_name'];
     $data4['d']['group_description']=$val4['short_description'];
     $data4['d']['thumbnail']=$val4['thumbnail'];
     $data4['d']['idx']=(int)$val4['idx'];
     $data4['d']['year']=(int)$year;
####################*****faculty start*******#############################
$faculty_data=$this->model_trainer->get_ams_faculty_list($university_id,$delekey);
$faculty_set=implode(',', array_map(function($faculty_data){ return $faculty_data['faculty_id'];}, $faculty_data ));
$data4['d']['faculty_set']=$faculty_set;
####################*****branch start*******#############################
$branchdata=$this->model_trainer->get_branch_in_ent_faculty_year($faculty_set,$year,$delekey);
$branchset=$branchdata['data'];
$branch_set=implode(',', array_map(function($branchset){ return $branchset['branch_id'];}, $branchset ));
$data4['d']['branch_ent_set']=$branch_set;
####################*****branch end*******#############################
####################*****faculty end*******#############################

####################*****faculty amss start*******#############################
$faculty_amss=$this->model_trainer->get_groupmapyearbranch($group_id,$branch_set,$delekey);
$faculty_amss_data=$faculty_amss['data'];
$dataset_faculty_amss = array();
if (is_array($faculty_amss_data)) {
foreach ($faculty_amss_data as $keydatas => $val) {
  $arr['faculty']=array();
  $faculty_id=(int)$val['faculty_id'];
  $arr['faculty']['faculty_id']=$faculty_id;
  $arr['faculty']['faculty_name']=$val['faculty_name'];
  $arr['faculty']['thumbnail']=$group_type3_thumbnail;
$binf=$this->model_trainer->get_ams_university_u_parent_id_where_u_group_id($faculty_id,$u_group_id_type4,$delekey);
$binfdata=$binf['data'];
  ####################*****faculty amss End*******#############################
  $branch_set=implode(',', array_map(function($binfdata){ return $binfdata['u_id'];}, $binfdata ));
  $arr['faculty']['branch_set']=$branch_set;
  ####################*****brancharr  End*******#############################
  $brancharr = array();
if (is_array($binfdata)) {
foreach ($binfdata as $kb => $vb) {
  $arr['branch']=array();
  $branch_id=(int)$vb['u_id'];
  $arr['branch']['branch_id']=$branch_id;
  $arr['branch']['branch_name']=$vb['u_name'];
  $arr['branch']['thumbnail']=$group_type3_thumbnail;
############################################################################
############################################################################
####################*****admission start*******###########################
$amssuser=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_id_set_user_id($year,$branch_id,$user_id,$delekey);
$amss_set_data=$amssuser['data'];
if($amss_set_data==null){$amss_entset=null;
}else{
$amss_entset=implode(',', array_map(function($amss_set_data){ return $amss_set_data['ent_id'];}, $amss_set_data ));
}
$arr['branch']['admission_set_user']=$amss_entset;
if($amss_entset==null){
$instatus=0;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,0,$delekey);
}else{
$instatus=1;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,$amss_entset,$delekey);
}
$amsset=$amssets['data'];
###############################################
$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keyent => $varent) {
$dataset['ent']=array();
$ent_id=$varent['ent_id'];
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$branch_id,$user_id,0,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
$ent_id_user=$ams_entrance_user1['ent_id'];
if($user_id==''){$dataset['ent']['bookmark_user']=null;$bookmark_active=0;}else{$bookmark_active=$ams_entrance_user1;if($ent_id_user==$ent_id){$bookmark_active=1;}else{$bookmark_active=0;}}
$dataset['ent']['bookmark_active']=$bookmark_active;
$dataset['ent']['year_config']=$varent['year_config'];
$dataset['ent']['major_remark']=$varent['major_remark'];
$score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
$dataset['ent']['score_history']=$score_history;
$dataset['ent']['ent_id']=$varent['ent_id'];
$dataset['ent']['01_onet_mi']=$varent['01_onet_mi'];
$dataset['ent']['01_onet_weight']=$varent['01_onet_weight'];
$dataset['ent']['02_onet_min']=$varent['02_onet_min'];
$dataset['ent']['02_onet_weight']=$varent['02_onet_weight'];
$dataset['ent']['03_onet_mint']=$varent['03_onet_mint'];
$dataset['ent']['03_onet_weight']=$varent['03_onet_weight'];
$dataset['ent']['04_onet_min']=$varent['04_onet_min'];
$dataset['ent']['04_onet_weight']=$varent['04_onet_weight'];
$dataset['ent']['05_onet_min']=$varent['05_onet_min'];
$dataset['ent']['05_onet_weight']=$varent['05_onet_weight'];
$dataset['ent']['71_pat1_min']=$varent['71_pat1_min'];
$dataset['ent']['71_pat1_weight']=$varent['71_pat1_weight'];
$dataset['ent']['72_pat2_min']=$varent['72_pat2_min'];
$dataset['ent']['72_pat2_weight']=$varent['72_pat2_weight'];
$dataset['ent']['73_pat3_min']=$varent['73_pat3_min'];
$dataset['ent']['73_pat3_weight']=$varent['73_pat3_weight'];
$dataset['ent']['74_pat4_min']=$varent['74_pat4_min'];
$dataset['ent']['74_pat4_weight']=$varent['74_pat4_weight'];
$dataset['ent']['75_pat5_min']=$varent['75_pat5_min'];
$dataset['ent']['75_pat5_weight']=$varent['75_pat5_weight'];
$dataset['ent']['76_pat6_min']=$varent['76_pat6_min'];
$dataset['ent']['76_pat6_weight']=$varent['76_pat6_weight'];
$dataset['ent']['77_pat71_min']=$varent['77_pat71_min'];
$dataset['ent']['77_pat71_weight']=$varent['77_pat71_weight'];
$dataset['ent']['78_pat72_min']=$varent['78_pat72_min'];
$dataset['ent']['78_pat72_weight']=$varent['78_pat72_weight'];
$dataset['ent']['79_pat73_min']=$varent['79_pat73_min'];
$dataset['ent']['79_pat73_weight']=$varent['79_pat73_weight'];
$dataset['ent']['80_pat74_min']=$varent['80_pat74_min'];
$dataset['ent']['80_pat74_weight']=$varent['80_pat74_weight'];
$dataset['ent']['81_pat75_min']=$varent['81_pat75_min'];
$dataset['ent']['81_pat75_weight']=$varent['81_pat75_weight'];
$dataset['ent']['82_pat76_min']=$varent['82_pat76_min'];
$dataset['ent']['82_pat76_weight']=$varent['82_pat76_weight'];
$dataset['ent']['85_gat_min']=$varent['85_gat_min'];
$dataset['ent']['85_gat_min_part2']=$varent['85_gat_min_part2'];
$dataset['ent']['85_gat_weight']=$varent['85_gat_weight'];
$dataset['ent']['branch_id']=$varent['branch_id'];
$dataset['ent']['branch_name']=$varent['branch_name'];
$dataset['ent']['config']=$varent['config'];
$dataset['ent']['university_name']=$varent['university_name'];
$dataset['ent']['faculty_id']=$varent['faculty_id'];
$dataset['ent']['faculty_name']=$varent['faculty_name'];
$dataset['ent']['gpax_min']=$varent['gpax_min'];
$dataset['ent']['gpax_weight']=$varent['gpax_weight'];
$dataset['ent']['major_code']=$varent['major_code'];
$dataset['ent']['major_remark']=$varent['major_remark'];
$dataset['ent']['onet_min_total']=$varent['onet_min_total'];
$dataset['ent']['onet_weight_total']=$varent['onet_weight_total'];
$sharecode=$varent['receive_amount_sharecode'];
$dataset['ent']['receive_amount_sharecode']= $sharecode;
if($sharecode==''){
$dataset['ent']['sharecode_data']=null;
$dataset['ent']['receive_amount']=(int)$varent['receive_amount'];
$dataset['ent']['sharecode_detail']=null;
$dataset['ent']['sharecode_set']=null;
$dataset['ent']['sharecode_msg']=null;
}else{
$majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
$majorcodeshare_data=$majorcodeshare['data']['0'];
$receiveamount=(int)$majorcodeshare_data['receive_amount'];
$dataset['ent']['sharecode_data']=$majorcodeshare_data;
$dataset['ent']['receive_amount']=$receiveamount;
$sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
$sharecode_data=$sharecode['data'];
$dataset['ent']['detail']=$sharecode_data;
$sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
$dataset['ent']['sharecode_set']=$sharecode_data_set;
$faculty_name_all=$varent['faculty_name'];
$dataset['ent']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
}
$dataset['ent']['score_max']=$varent['score_max'];
$dataset['ent']['score_min']=$varent['score_min'];
$dataset['ent']['special_remark']=$varent['special_remark'];
$dataset['ent']['lastupdate']=$varent['lastupdate'];
$datasetamss[]=$dataset['ent'];
 }
}else{ $datasetamss=null;}
###############################################
$arr['branch']['admission']=$datasetamss;
####################*****admission End*******#############################
############################################################################
############################################################################
####################*****directapply start*******###########################
##############****directapply setting****#################
$directapplyset=$this->model_trainer->get_ams_entrance_user_year_user_id_directapply($branch_id,$year,$user_id,$delekey);
$directapplyset_data=$directapplyset['data'];
$directapplyset_data_count=(int)count($directapplyset_data);
$arr['branch']['directapply']['directapply_set_count']=$directapplyset_data_count;
if($directapplyset_data_count==0){$directapplystatus=0;}else{$directapplystatus=1;}
$arr['branch']['directapply']['directapply_active']=$directapplystatus;
$sumdirectapplyset=$this->model_trainer->get_ams_news_directapply_branch_id_year_sum($branch_id,$year,$delekey);
$arr['branch']['directapply']['directapply_sum']=(int)$sumdirectapplyset['data']['0']['major_receivers_all'];
##############****directapply setting****##################
####################*****directapply End*******#############################
  $brancharr[]=$arr['branch'];
 }
}
 $arr['faculty']['branch']=$brancharr;
  ####################*****brancharr End*******#############################
  $dataset_faculty_amss[]=$arr['faculty'];
 }
}
$data4['d']['faculty']=$dataset_faculty_amss;
####################*****university*******#############################
$arr_result4[]=$data4['d'];
 }
}
######################################################################
$list=$arr_result4;
/*
function array_unique_multidimensional($input){
    $serialized = array_map('serialize', $input);
    $unique = array_unique($serialized);
    return array_intersect_key($input, $unique);
}
$list=array_unique_multidimensional($arr_result4);
*/
$data=array('u_group_id'=>$u_group_id,
			'u_group_id_type3'=>$u_group_id_type3,
               'u_group_id_type4'=>$u_group_id_type4,
               'group_name'=>$u_group_name,
               'group_type3_name'=>$group_type3_name,
               'group_type3_thumbnail'=>$group_type3_thumbnail,
               'group_type4_name'=>$group_type4_name,
               'year'=>(int)$year,
	 		'list'=>$list,
               #'university_sql'=>$universityname_sql,
               
               'list_count'=>count($list),
	 		'user_id'=>$user_id,);
if($data){
				$this->response(array('header'=>array(
										'title'=>'countfollow',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'session',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
     
#############
}
public function universitylistgroup34listgroupidamstest_get(){
$user_id=@$this->input->get('user_id');
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'Ams entrance group 34',
									  'message'=>'user_id= is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$member_id=@$this->tppy_member->get_member_profile()->member_id;
###########################################################
$this->load->model('admissiontrainer_model', 'model_trainer');
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
###########################################################
if($u_group_id_type3=='' && $u_group_id_type4==''){
$this->response(array('header'=>array('title'=>'University list group4',
									  'message'=>'Data is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}

$active_id=@$this->input->get('active_id');
$groupby=@$this->input->get('group_by');
$delekey=$this->input->get('delekey');
$tab=@$this->input->get('tab');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey='';}else{$delekey='1';}
$type34[0]['u_group_id']=(int)$u_group_id_type3;
$type34[1]['u_group_id']=(int)$u_group_id_type4;
#echo'<hr><pre> $type34=>';print_r($type34);echo'<pre> <hr>'; 
$ugroupid34=implode(',', array_map(function($type34){ return $type34['u_group_id'];}, $type34 ));
#echo'<hr><pre> $ugroupid34=>';print_r($ugroupid34);echo'<pre> <hr>'; 
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
#echo'<pre> $university_group3=>';print_r($university_group3);echo'<pre>';  die();
$university_group=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);

$grouparr=array();
if (is_array($university_group)){
foreach ($university_group as $kg =>$vg){
     $garr = array();
	$u_group_id=(int)$vg['u_group_id'];
	$garr['group']['group_id']=$u_group_id;
     $garr['group']['u_group_id_type3']=(int)$u_group_id_type3;
     $garr['group']['u_group_id_type4']=(int)$u_group_id_type4;
     $garr['group']['group_type3_name']=$university_group3['u_group_name'];
     $garr['group']['group_type3_thumbnail']=$university_group3['thumbnails'];
	$garr['group']['group_name']=$vg['u_group_name'];
     $u_group_type=(int)$vg['u_group_type'];
	$garr['group']['u_group_type']=$u_group_type;
     if($u_group_type==3){
      $thumbnail=$vg['thumbnails'];
$universitygroup=$this->model_trainer->get_group_type34_university_year_by_groupid_tab2($year,$u_group_id_type3,$delekey);
$university_group=$universitygroup['data'];
     }else{
      $thumbnail='http://www.trueplookpanya.com/assets/images/img-icon/fac_group.png';
$universitygroup=$this->model_trainer->get_group_type34_university_year_by_groupid_tab2($year,$u_group_id_type4,$delekey);
     $garr['group']['thumbnail']=$thumbnail;
$university_group=$universitygroup['data'];
     }	
#echo'<pre> $university_group=>';print_r($university_group);echo'<pre>';  die();


#############################
####*******university start*****###
#############################
$university_arr=array();
if(is_array($university_group)){
foreach ($university_group as $key3 => $v3) {
$arr2=array();
$university_id=(int)$v3['university_id'];
     $arr2['university']['university_id']=$university_id;
     $university_name=$v3['university_name'];
	$arr2['university']['university_name']=$university_name;
	$arr2['university']['faculty_name']=$v3['faculty_name'];
     $arr2['university']['branch_name']=$v3['branch_name'];
     $arr2['university']['thumbnail']=$v3['thumbnail'];
     //$arr2['university']['group_name']=$v3['group_name'];
     $arr2['university']['university_parent_id']=$v3['university_parent_id'];
     $faculty_id=$v3['faculty_id'];
     $arr2['university']['faculty_id']=(int)$faculty_id;
     $arr2['university']['branch_id']=(int)$v3['branch_id'];
     $arr2['university']['map_id']=(int)$v3['map_id'];
     $arr2['university']['group_type']=(int)$v3['u_group_type'];
     $arr2['university']['year']=(int)$v3['year'];
############faculty#######################
############faculty#######################
############faculty#######################
     $faculty_array=$this->model_trainer->get_ams_faculty_id($faculty_id,$delekey);
      #echo'<pre> $faculty_array=>';print_r($faculty_array);echo'<pre> <hr>'; die();
     $facultyarray=array();
     if(is_array($faculty_array)){
     foreach ($faculty_array as $key4 => $v4) {
     $arr4=array();
          $faculty_id=(int)$v4['faculty_id'];
          $arr4['faculty']['faculty_id']=$faculty_id;
          $faculty_name=$v4['faculty_name'];
          $arr4['faculty']['faculty_name']=$faculty_name;
          $arr4['faculty']['faculty_thumbnail']=$thumbnail;
          
#################***branch***#################
#################***branch***#################
#################***branch***#################
$branchrs=$this->model_trainer->get_ams_university_map_group_type_u_parent_id_where($faculty_id,$year,$u_group_id_type4,$delekey);
$branch_data_master=$branchrs['data'];
$branch_set_master=implode(',', array_map(function($branch_data_master){ return $branch_data_master['u_id'];}, $branch_data_master ));
$arr4['faculty']['branch_set']=$branch_set_master;
#$arr4['faculty']['branch_sql']=$branchrs['sql'];
#$branchdatav1=$this->model_trainer->get_ams_entrance_university_group_branch_set_id_tab2($u_group_id_type4,$year,$branch_set_master,$delekey);
$branchdata=$this->model_trainer->get_ams_entrance_university_group_branch_set_id_tab2v2($u_group_id_type4,$year,$branch_set_master,$delekey);
$branch_data=$branchdata['data'];
$branch_data_sql=$branchdata['sql'];
#$arr4['faculty']['branch_data']=$branch_data;
#$arr4['faculty']['branch_data_sql']=$branch_data_sql;
#$arr4['faculty']['branch_count1']=count($branch_count);
$arr_branch = array();
if (is_array($branch_data)){
foreach ($branch_data as $kb =>$vab){
$brancharr = array();
	$branch_id=$vab['u_id'];
	$brancharr['dn']['branch_id']=$branch_id;
	$brancharr['dn']['university_name']=$university_name;
	$brancharr['dn']['faculty_name']=$faculty_name;
	$brancharr['dn']['branch_name']=$vab['branch_name'];
     $brancharr['dn']['branch_thumbnail']=$thumbnail;
     $brancharr['dn']['u_group_id_type3']=(int)$u_group_id_type3;
     $brancharr['dn']['u_group_id_type4']=(int)$u_group_id_type4;
########################***admission***#################################
########################***admission***#################################
########################***admission***#################################
$amss_set=$this->model_trainer->get_ams_entrance_group_dirapply_year_branch_id_set_user_id($year,$branch_id,$user_id,$delekey);
$amss_set_data=$amss_set['data'];
#$brancharr['dn']['admission_sql1']=$amss_set['sql'];
if($amss_set_data==null){
$amss_entset=null;
}else{
$amss_entset=implode(',', array_map(function($amss_set_data){ return $amss_set_data['ent_id'];}, $amss_set_data ));
}


if($amss_entset==null){
$instatus=0;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,0,$delekey);
}else{
$instatus=3;
$amssets=$this->model_trainer->get_ams_university_entrance_score_dataset_year_in_user($branch_id,$year,$instatus,$user_id,$amss_entset,$delekey);
}
$amsset=$amssets['data'];
#$brancharr['dn']['admission_sql']=$amssets['sql'];
$datasetamss = array();
if (is_array($amsset)){
  foreach ($amsset as $keydatas => $val) {
$dataset=array();
$ent_id=$val['ent_id'];

############################
$branch_id=$val['branch_id'];
$faculty_id=0;
$ams_entrance_users=$this->model_trainer->get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$branch_id,$user_id,0,$delekey);
$ams_entrance_user1=$ams_entrance_users['data']['0'];
$ent_id_user=$ams_entrance_user1['ent_id'];
if($user_id==''){
     $dataset['dd']['bookmark_user']=null;
     $bookmark_active=0;
}else{
     $bookmark_active=$ams_entrance_user1;
     if($ent_id_user==$ent_id){
       $bookmark_active=1;
     }else{
      $bookmark_active=0;
     }
}
//$dataset['dd']['bookmark_users']=$ams_entrance_users;
$dataset['dd']['bookmark_active']=$bookmark_active;
############################
      $dataset['dd']['u_group_id_type3']=(int)$u_group_id_type3;
      $dataset['dd']['u_group_id_type4']=(int)$u_group_id_type4;
      $dataset['dd']['year_config']=$val['year_config'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
      $dataset['dd']['score_history']=$score_history;
      $dataset['dd']['ent_id']=$val['ent_id'];
      $dataset['dd']['01_onet_mi']=$val['01_onet_mi'];
      $dataset['dd']['01_onet_weight']=$val['01_onet_weight'];
      $dataset['dd']['02_onet_min']=$val['02_onet_min'];
      $dataset['dd']['02_onet_weight']=$val['02_onet_weight'];
      $dataset['dd']['03_onet_mint']=$val['03_onet_mint'];
      $dataset['dd']['03_onet_weight']=$val['03_onet_weight'];
      $dataset['dd']['04_onet_min']=$val['04_onet_min'];
      $dataset['dd']['04_onet_weight']=$val['04_onet_weight'];
      $dataset['dd']['05_onet_min']=$val['05_onet_min'];
      $dataset['dd']['05_onet_weight']=$val['05_onet_weight'];
      $dataset['dd']['71_pat1_min']=$val['71_pat1_min'];
      $dataset['dd']['71_pat1_weight']=$val['71_pat1_weight'];
      $dataset['dd']['72_pat2_min']=$val['72_pat2_min'];
      $dataset['dd']['72_pat2_weight']=$val['72_pat2_weight'];
      $dataset['dd']['73_pat3_min']=$val['73_pat3_min'];
      $dataset['dd']['73_pat3_weight']=$val['73_pat3_weight'];
      $dataset['dd']['74_pat4_min']=$val['74_pat4_min'];
      $dataset['dd']['74_pat4_weight']=$val['74_pat4_weight'];
      $dataset['dd']['75_pat5_min']=$val['75_pat5_min'];
      $dataset['dd']['75_pat5_weight']=$val['75_pat5_weight'];
      $dataset['dd']['76_pat6_min']=$val['76_pat6_min'];
      $dataset['dd']['76_pat6_weight']=$val['76_pat6_weight'];
      $dataset['dd']['77_pat71_min']=$val['77_pat71_min'];
      $dataset['dd']['77_pat71_weight']=$val['77_pat71_weight'];
      $dataset['dd']['78_pat72_min']=$val['78_pat72_min'];
      $dataset['dd']['78_pat72_weight']=$val['78_pat72_weight'];
      $dataset['dd']['79_pat73_min']=$val['79_pat73_min'];
      $dataset['dd']['79_pat73_weight']=$val['79_pat73_weight'];
      $dataset['dd']['80_pat74_min']=$val['80_pat74_min'];
      $dataset['dd']['80_pat74_weight']=$val['80_pat74_weight'];
      $dataset['dd']['81_pat75_min']=$val['81_pat75_min'];
      $dataset['dd']['81_pat75_weight']=$val['81_pat75_weight'];
      $dataset['dd']['82_pat76_min']=$val['82_pat76_min'];
      $dataset['dd']['82_pat76_weight']=$val['82_pat76_weight'];
      $dataset['dd']['85_gat_min']=$val['85_gat_min'];
      $dataset['dd']['85_gat_min_part2']=$val['85_gat_min_part2'];
      $dataset['dd']['85_gat_weight']=$val['85_gat_weight'];
      $dataset['dd']['branch_id']=$val['branch_id'];
      $dataset['dd']['branch_name']=$val['branch_name'];
      $dataset['dd']['config']=$val['config'];
      $dataset['dd']['university_name']=$val['university_name'];
      $dataset['dd']['faculty_id']=$val['faculty_id'];
      $dataset['dd']['faculty_name']=$val['faculty_name'];
      $dataset['dd']['gpax_min']=$val['gpax_min'];
      $dataset['dd']['gpax_weight']=$val['gpax_weight'];
      $dataset['dd']['major_code']=$val['major_code'];
      $dataset['dd']['major_remark']=$val['major_remark'];
      $dataset['dd']['onet_min_total']=$val['onet_min_total'];
      $dataset['dd']['onet_weight_total']=$val['onet_weight_total'];
      $sharecode=$val['receive_amount_sharecode'];
      $dataset['dd']['receive_amount_sharecode']= $sharecode;
      if($sharecode==''){
        $dataset['dd']['sharecode_data']=null;
        $dataset['dd']['receive_amount']=(int)$val['receive_amount'];
        $dataset['dd']['sharecode_detail']=null;
        $dataset['dd']['sharecode_set']=null;
         $dataset['dd']['sharecode_msg']=null;
      }else{
       $majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
       $majorcodeshare_data=$majorcodeshare['data']['0'];
       $receiveamount=(int)$majorcodeshare_data['receive_amount'];
       $dataset['dd']['sharecode_data']=$majorcodeshare_data;
       $dataset['dd']['receive_amount']=$receiveamount;
       $sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
       $sharecode_data=$sharecode['data'];
       $dataset['dd']['detail']=$sharecode_data;
       $sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
       $dataset['dd']['sharecode_set']=$sharecode_data_set;
      $faculty_name_all=$val['faculty_name'];
       $dataset['dd']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
      }
      $dataset['dd']['score_max']=$val['score_max'];
      $dataset['dd']['score_min']=$val['score_min'];
      $dataset['dd']['special_remark']=$val['special_remark'];
      $dataset['dd']['lastupdate']=$val['lastupdate'];
      $datasetamss[]=$dataset['dd'];
  }
}else{ $datasetamss='';}
$admission_count=(int)count($datasetamss);
$brancharr['dn']['admission_count']=$admission_count;
$brancharr['dn']['admission']=$datasetamss;
########################***admission***#################################
########################***admission***#################################
########################***admission***#################################
##################****directapply******###################
##################****directapply******###################
##############****directapply setting****#################
$directapplyset=$this->model_trainer->get_ams_entrance_user_year_user_id_directapply($branch_id,$year,$user_id,$delekey);
$directapplyset_data=$directapplyset['data'];
$directapplyset_data_count=(int)count($directapplyset_data);
$brancharr['dn']['directapply_set_count']=$directapplyset_data_count;
if($directapplyset_data_count==0){$directapplystatus=0;}else{$directapplystatus=1;}
$brancharr['dn']['directapply_active']=$directapplystatus;
$sumdirectapplyset=$this->model_trainer->get_ams_news_directapply_branch_id_year_sum($branch_id,$year,$delekey);
$brancharr['dn']['directapply_sum']=(int)$sumdirectapplyset['data']['0']['major_receivers_all'];
##############****directapply setting****##################
##################****directapply******###################
##################****directapply******###################
#######################################################################################
$arr_branch[]=$brancharr['dn'];
  }
}
#################***branch***#################
     $arr4['faculty']['branch']=$arr_branch;
     $branch_count=(int)count($arr_branch);
     $arr4['faculty']['branch_count']=$branch_count;
     #################***branch***#################
     $facultyarray[]=$arr4['faculty'];
  }
 }

############faculty#######################
############faculty#######################
$arr2['university']['faculty']=$facultyarray;
############faculty#######################
 $university_arr[]=$arr2['university'];
 }
}
$university_list=$university_arr;
$university_list_count=(int)count($university_arr);
#############################
####*******university*****###
#############################


$garr['group']['university']=$university_list;
$garr['group']['university_count']=$university_list_count;
$university_set=implode(',', array_map(function($university_list){ return $university_list['university_id'];}, $university_list ));
$garr['group']['university_set']=$university_set;
if($university_list_count<=0){}else{
$grouparr[]=$garr['group'];	
}
 }
}
$group_type3_name=$group_type3name['0'];
$data=array('list'=>$grouparr,'list_count'=>count($grouparr),'university_count'=>$university_list_count,'user_id'=>$user_id,'year'=>$year);
if($data){
				$this->response(array('header'=>array(
										'title'=>'Ams entrance group 34',
										'message'=>'Success',
										'status'=>true,
										'code'=>200), 
										'data'=> $data),200);
}else{
				  $this->response(array('header'=>array(
										'title'=>'Ams entrance group 34',
										'message'=>'Success',
										'status'=>true, 
										'code'=>404), 
										'data'=> $data),404);
				}
     
#############
}
//2018-04-04
public function dashboard_get(){
$user_id=@$this->input->get('user_id');
$delekey=@$this->input->get('delekey');
$tab=@$this->input->get('tab');
if($tab==''){ $tab=null; }
if($delekey==''){$delekey=null;}
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'dashboard',
									  'message'=>'Error user_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$universityname=$this->model_trainer->get_ams_entrance_user_by_ams_university_form_user_id($user_id,1,$delekey);
$universityname_data=$universityname['data'];
$universityname_sql=$universityname['sql'];
$university_set=implode(',', array_map(function($universityname_data){ return $universityname_data['university_id'];}, $universityname_data ));
$faculty_setrs=$this->model_trainer->get_faculty_set_university_id($university_set,$delekey);
$faculty_set=implode(',', array_map(function($faculty_setrs){ return $faculty_setrs['faculty_id'];}, $faculty_setrs ));
$branch_user=$this->model_trainer->get_ams_university_by_u_set_id($faculty_set,$delekey);
$branch_user_data=$branch_user['data'];
$branchset=implode(',', array_map(function($branch_user_data){ return $branch_user_data['u_id'];}, $branch_user_data ));
$entranceuserbranch=$this->model_trainer->get_entranceuseryeardashboard($year,$branchset,$user_id,$delekey);
$entranceuserbranch_data=$entranceuserbranch['data'];
$entranceuserbranch_sql=$entranceuserbranch['sql'];
$data=array(//'university_set'=>$university_set,
            //'faculty_set'=>$faculty_set,
            'branch_set'=>$branchset,
	 	  'user_id'=>$user_id,
            'year'=>$year,
            //'sql'=>$entranceuserbranch_sql,
            'list'=>$entranceuserbranch_data,
            'list_count'=>(int)count($entranceuserbranch_data),
            );
     if($entranceuserbranch_data){
     $this->response(array('header'=>array('title'=>'dashboard',
     							   'message'=>'Success',
     							   'status'=>true,
     							   'code'=>200), 
     							   'data'=> $data),200);
     }else{
     $this->response(array('header'=>array('title'=>'dashboard',
     							   'message'=>'Error or data is null',
     							   'status'=>true, 
     							   'code'=>404), 
     							   'data'=> $data),404);
     }
}
public function detailbytype34v1_get(){
$user_id=@$this->input->get('user_id');
$delekey=@$this->input->get('delekey');
if($delekey==''){$delekey=null;}
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'Detail by type34',
									  'message'=>'Error user_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
if($u_group_id_type3==''|| $u_group_id_type4==''){
$this->response(array('header'=>array('title'=>'Detail by type34',
									  'message'=>'Error u_group_id_type3 or u_group_id_type4 is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$u_group_id_type3=(int)$u_group_id_type3;
$u_group_id_type4=(int)$u_group_id_type4;
/*
$sql="select g.u_group_id,g.u_group_name,g.short_description,g.u_group_type,g.record_status
,concat('http://static.trueplookpanya.com',g.thumbnail) as thumbnails 
from ams_university_group as g where g.u_group_id=$u_group_id_type3 order by  g.u_group_type,g.u_group_id asc";
$cache_key="get_ams_university_group_type3or4_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode_get($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode_get($cache_key_encrypt);
$universitygroup3=$this->tppymemcached->get($cache_key_encrypt);
if(!$universitygroup3){
	$query=$this->db->query($sql);
	$universitygroup3 = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$universitygroup3,300);
}else{$universitygroup3;}
 
$sql2="select g.u_group_id,g.u_group_name,g.short_description,g.u_group_type,g.record_status
,concat('http://static.trueplookpanya.com',g.thumbnail) as thumbnails 
from ams_university_group as g where g.u_group_id=$u_group_id_type4 order by  g.u_group_type,g.u_group_id asc";
$cache_key2="get_ams_university_group_type3or4s_".$sql2;
#################cache#################
$cache_key_encrypt2=$this->encryptcode_get($cache_key2);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt2); }
$universitygroup4=$this->tppymemcached->get($cache_key_encrypt2);
if(!$universitygroup4){
	$query2=$this->db->query($sql2);
	$universitygroup4=$query2->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$universitygroup4,300);
}else{$universitygroup4;}
 
*/
$this->load->model('admissiontrainer_model', 'model_trainer');
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
$universitygroup4=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);
$university_group4=$universitygroup4['0'];
$group_type4_name=$university_group4['u_group_name'];

$universitylist=$this->model_trainer->get_ams_entrance_user_year_id_type4_user_id($year,$u_group_id_type4,$user_id,$delekey);
$universitylist_data=$universitylist['data'];
$universitylist_data_count=$universitylist['data_count'];

$data=array('u_group_id_type3'=>$u_group_id_type3,
            'u_group_id_type4'=>$u_group_id_type4,
	 	  'user_id'=>(int)$user_id,
            'year'=>(int)$year,
            'group3_name'=>$group_type3_name, 
            'group3_thumbnails'=>$group_type3_thumbnail,
            'group4_name'=>$group_type4_name,
            'branch_list'=>$universitylist_data,
            'branch_count'=>$universitylist_data_count,
            );
     if($data){
     $this->response(array('header'=>array('title'=>'Detail by type34',
     							   'message'=>'Success',
     							   'status'=>true,
     							   'code'=>200), 
     							   'data'=> $data),200);
     }else{
     $this->response(array('header'=>array('title'=>'Detail by type34',
     							   'message'=>'Error or data is null',
     							   'status'=>true, 
     							   'code'=>404), 
     							   'data'=> $data),404);
     }
}
public function detailbytype4userfaculty_get(){
// http://www.trueplookpanya.com/api/admissiontrainer/detailbytype4userfaculty?user_id=543622&year=2560&u_group_id_type3=4&u_group_id_type4=57&faculty_id=46&branch_id=74&ent_id=149&delekey=1
$user_id=@$this->input->get('user_id');
$year=@$this->input->get('year');
if($year==''){ $year=(int)date('Y'); $year=(int)$year+543; $year=(int)$year-1;
}else{ $year=(int)$year;}
$u_group_id_type3=@$this->input->get('u_group_id_type3');
$u_group_id_type4=@$this->input->get('u_group_id_type4');
$faculty_id=@$this->input->get('faculty_id');
$branch_id=@$this->input->get('branch_id');
$ent_id=@$this->input->get('ent_id');
$delekey=@$this->input->get('delekey');
if($delekey==''){$delekey=null;}
if($user_id==''){$user_id=@$this->tppy_member->get_member_profile()->user_id;}
if($user_id==''){$user_id=null;}else{$user_id=(int)$user_id;}
if($user_id==''){
$this->response(array('header'=>array('title'=>'Detail by type34',
									  'message'=>'Error user_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
if($u_group_id_type3==''|| $u_group_id_type4=='' || $faculty_id==''){
$this->response(array('header'=>array('title'=>'Detail by type34',
									  'message'=>'Error u_group_id_type3 or u_group_id_type4  or faculty_id is null',
									  'status'=>true, 
									  'code'=>404), 
									  'data'=> Null),404);
Die();
}
$u_group_id_type3=(int)$u_group_id_type3;
$u_group_id_type4=(int)$u_group_id_type4;
$this->load->model('admissiontrainer_model', 'model_trainer');
$universitygroup3=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type3,$delekey);
$university_group3=$universitygroup3['0'];
$group_type3_name=$university_group3['u_group_name'];
$group_type3_thumbnail=$university_group3['thumbnails'];
$universitygroup4=$this->model_trainer->get_ams_university_group_id_type34($u_group_id_type4,$delekey);
$university_group4=$universitygroup4['0'];
$group_type4_name=$university_group4['u_group_name'];
/*
$universitylist=$this->model_trainer->get_ams_entrance_user_year_id_type4_user_id($year,$u_group_id_type4,$user_id,$delekey);
$universitylist_data=$universitylist['data'];
$universitylist_data_count=$universitylist['data_count'];
*/



$directapplyinuser=$this->model_trainer->get_news_directapply_user_branch_id_year($branch_id,$year,$user_id,$delekey);
$directapplyinuser_data=$directapplyinuser['data'];
$directapplyinuser_count=(int)$directapplyinuser['data_count'];

if($directapplyinuser_count>0){
$date=date('Y-m-d');
$newsdirectapplyrs=$this->model_trainer->get_news_directapply_user_id_faculty($branch_id,$date,$delekey);
$newsdirectapply=$newsdirectapplyrs['data'];

if(is_array($newsdirectapply)){
foreach ($newsdirectapply as $keydir => $valdirectapply) {
$directapply_arr=array();
$idx=$valdirectapply['idx'];
$newsid=$valdirectapply['newsid'];
  $datadir['dir']['idx']=$idx;
  $datadir['dir']['newsid']=$newsid;
  $datadir['dir']['title']=$valdirectapply['title'];
 
  $datadir['dir']['detail']=$valdirectapply['detail'];
  $datadir['dir']['receivers']=$valdirectapply['receivers'];
  $datadir['dir']['news_all_receivers']=$valdirectapply['news_all_receivers'];
  $datadir['dir']['gpax']=$valdirectapply['gpax'];
  $datadir['dir']['gatpat']=$valdirectapply['gatpat'];
  $datadir['dir']['req_gnet']=$valdirectapply['req_gnet'];
  $datadir['dir']['req_portfolio']=$valdirectapply['req_portfolio'];
  $datadir['dir']['req_interview']=$valdirectapply['req_interview'];
  $datadir['dir']['req_matches']=$valdirectapply['req_matches'];
  $datadir['dir']['req_property']=$valdirectapply['req_property'];
  $datadir['dir']['news_start_date']=$valdirectapply['news_start_date'];
  $datadir['dir']['news_end_date']=$valdirectapply['news_end_date'];
  
  $datadir['dir']['interview_start_date']=$valdirectapply['interview_start_date'];
  $datadir['dir']['interview_end_date']=$valdirectapply['interview_end_date'];
  $datadir['dir']['doexam_start_date']=$valdirectapply['doexam_start_date'];
  $datadir['dir']['doexam_end_date']=$valdirectapply['doexam_end_date'];
  $datadir['dir']['news_thumbnail']=$valdirectapply['news_thumbnail'];
  $datadir['dir']['news_link_web']=$valdirectapply['news_link_web'];
  $datadir['dir']['news_link_pdf']=$valdirectapply['news_link_pdf'];
  $datadir['dir']['news_path']=$valdirectapply['news_path'];
  $datadir['dir']['news_view']=$valdirectapply['news_view'];
 
  $datadir['dir']['university']=$valdirectapply['university'];
 // $datadir['dir']['news_thumbnails']=$valdirectapply['news_thumbnails'];
  $datadir['dir']['thumbnail_university']=$valdirectapply['thumbnail_university'];
  $datadir['dir']['faculty_name']=$valdirectapply['faculty_name'];
  $datadir['dir']['u_name']=$valdirectapply['u_name'];
  $datadir['dir']['university_id']=$valdirectapply['university_id'];
  $datadir['dir']['faculty_id']=$valdirectapply['faculty_id'];
  $datadir['dir']['branch_id']=$valdirectapply['branch_id'];
#$detail_news=$this->amm->_get_directapplynews_viewc(array('limit' => 4, 'id' => $newsid))['data']['directapplynews'];


        $datasearch['u']=$valdirectapply['university_id'];
        $datasearch['y']=$year;
        $datasearch['group_id_type3']=$u_group_id_type3;
        $datasearch['group_id_type4']=$u_group_id_type4;
        $datasearch['newsid']=$newsid;
        
   #$datadir['dir']['total_row']= $this->amm->_get_directapplynews_view($arrParams = NULL,$isCount = true);
   #$datadir['dir']['fav_directapplynews'] = $this->amm->_get_directapplynews_view(array('fav' => 1));
   #$datadir['dir']['campnews']=json_decode($this->am->get_campnews(4));
   #$datadir['dir']['fav_directapplynews_count'] = count($data['fav_directapplynews']);
   
    $detail_news=$this->amm->admissiontrainer($datasearch);
   $datadir['dir']['detail']=$detail_news['0']['sub_content'];
   $datadir['dir']['detail_all']=$detail_news;
   
   if($detail_news==''){}else{$newsdirectapplydata[]=$datadir['dir'];}
  
 }
}

$newsdirectapply_count=$newsdirectapplyrs['data_count'];
}else{
$newsdirectapply=null;
$newsdirectapply_count=0;
}
$amslist=$this->model_trainer->get_entrance_user_year_id_type4_user_id_faculty($year,$u_group_id_type4,$user_id,$branch_id,$delekey);
$amslist_data=$amslist['data'];
$amslist_count=(int)$amslist['data_count'];

if($amslist_count>0){
$thumbnail_university=$amslist['data']['0']['thumbnail_university'];
$groups_thumbnails=$group_type3_thumbnail;
$university_name=$amslist['data']['0']['university_name'];
$faculty_name=$amslist['data']['0']['faculty_name'];
$branch_name=$amslist['data']['0']['branch_name'];
$university_id=$amslist['data']['0']['university_id'];
$faculty_id=$amslist['data']['0']['faculty_id'];
$branch_id=$amslist['data']['0']['branch_id'];
$dataarray0=array('thumbnail_university'=>$thumbnail_university,
                  'groups_thumbnails'=>$groups_thumbnails,
                  'university_name'=>$university_name,
                  'faculty_name'=>$faculty_name,
                  'branch_name'=>$branch_name,
                  'university_id'=>$university_id,
                  'faculty_id'=>$faculty_id,
                  'branch_id'=>$branch_id,
                  );
}else if($newsdirectapply_count>0){
$thumbnail_university=$newsdirectapply['data']['0']['thumbnail_university'];
$groups_thumbnails=$group_type3_thumbnail;
$university_name=$newsdirectapply['data']['0']['university'];
$faculty_name=$newsdirectapply['data']['0']['faculty_name'];
$branch_name=$newsdirectapply['data']['0']['u_name'];
$university_id=$newsdirectapply['data']['0']['university_id'];
$faculty_id=$newsdirectapply['data']['0']['faculty_id'];
$branch_id=$newsdirectapply['data']['0']['branch_id'];
$dataarray0=array('thumbnail_university'=>$thumbnail_university,
                  'groups_thumbnails'=>$groups_thumbnails,
                  'university_name'=>$university_name,
                  'faculty_name'=>$faculty_name,
                  'branch_name'=>$branch_name,
                  'university_id'=>$university_id,
                  'faculty_id'=>$faculty_id,
                  'branch_id'=>$branch_id,
                  );
}else{
$dataarray0=array('thumbnail_university'=>'"http://static.trueplookpanya.com/trueplookpanya/media/admissions/major_group/C_4.png',
                  'groups_thumbnails'=>'"http://static.trueplookpanya.com/trueplookpanya/media/admissions/major_group/C_4.png',
                  'university_name'=>'ไม่มีมหาวิทยาลัย',
                  'faculty_name'=>'ไม่มีคณะ',
                  'branch_name'=>'ไม่มีสาขา',
                  'university_id'=>0,
                  'faculty_id'=>0,
                  'branch_id'=>0,
                  );
}



#######################################################################################
#########################admission##############################
$datasetamss = array();
if(is_array($amslist_data)){
foreach ($amslist_data as $keydatas => $val) {
$dataset=array();
      $entid=(int)$val['ent_id'];
      $dataset['dd']['ent_id']=$entid;
      $branch_id=$val['branch_id'];
      if($ent_id==$entid){
      $dataset['dd']['active']=1;
      }else{
      $dataset['dd']['active']=0;
      }
      $dataset['dd']['entrance_name']=$val['entrance_name'];
      $dataset['dd']['thumbnail_university']=$val['thumbnail_university'];
      $dataset['dd']['groups_thumbnails']=$val['groups_thumbnails'];
      $dataset['dd']['university_id']=$val['university_id'];
      $dataset['dd']['university_name']=$val['university_name'];
      $dataset['dd']['u_group_id_type3']=$val['u_group_id_type3'];
      $dataset['dd']['u_group_id_type4']=$val['u_group_id_type4'];
      $dataset['dd']['faculty_id']=$val['faculty_id'];
      $dataset['dd']['faculty_name']=$val['faculty_name'];
      $dataset['dd']['branch_id']=$val['branch_id'];
      $dataset['dd']['branch_name']=$val['branch_name'];
      $dataset['dd']['major_code']=$val['major_code'];
      $dataset['dd']['major_remark']=$val['entrance_name'];
      $score_history=$this->model_trainer->get_ams_entrance_score_history_where($ent_id,Null,$delekey);
      $dataset['dd']['score_history']=$score_history;
      //$dataset['dd']['ent_id']=$val['ent_id'];
      /*
      $dataset['dd']['01_onet_mi']=$val['01_onet_mi'];
      $dataset['dd']['01_onet_weight']=$val['01_onet_weight'];
      $dataset['dd']['02_onet_min']=$val['02_onet_min'];
      $dataset['dd']['02_onet_weight']=$val['02_onet_weight'];
      $dataset['dd']['03_onet_mint']=$val['03_onet_mint'];
      $dataset['dd']['03_onet_weight']=$val['03_onet_weight'];
      $dataset['dd']['04_onet_min']=$val['04_onet_min'];
      $dataset['dd']['04_onet_weight']=$val['04_onet_weight'];
      $dataset['dd']['05_onet_min']=$val['05_onet_min'];
      $dataset['dd']['05_onet_weight']=$val['05_onet_weight'];
      $dataset['dd']['71_pat1_min']=$val['71_pat1_min'];
      $dataset['dd']['71_pat1_weight']=$val['71_pat1_weight'];
      $dataset['dd']['72_pat2_min']=$val['72_pat2_min'];
      $dataset['dd']['72_pat2_weight']=$val['72_pat2_weight'];
      $dataset['dd']['73_pat3_min']=$val['73_pat3_min'];
      $dataset['dd']['73_pat3_weight']=$val['73_pat3_weight'];
      $dataset['dd']['74_pat4_min']=$val['74_pat4_min'];
      $dataset['dd']['74_pat4_weight']=$val['74_pat4_weight'];
      $dataset['dd']['75_pat5_min']=$val['75_pat5_min'];
      $dataset['dd']['75_pat5_weight']=$val['75_pat5_weight'];
      $dataset['dd']['76_pat6_min']=$val['76_pat6_min'];
      $dataset['dd']['76_pat6_weight']=$val['76_pat6_weight'];
      $dataset['dd']['77_pat71_min']=$val['77_pat71_min'];
      $dataset['dd']['77_pat71_weight']=$val['77_pat71_weight'];
      $dataset['dd']['78_pat72_min']=$val['78_pat72_min'];
      $dataset['dd']['78_pat72_weight']=$val['78_pat72_weight'];
      $dataset['dd']['79_pat73_min']=$val['79_pat73_min'];
      $dataset['dd']['79_pat73_weight']=$val['79_pat73_weight'];
      $dataset['dd']['80_pat74_min']=$val['80_pat74_min'];
      $dataset['dd']['80_pat74_weight']=$val['80_pat74_weight'];
      $dataset['dd']['81_pat75_min']=$val['81_pat75_min'];
      $dataset['dd']['81_pat75_weight']=$val['81_pat75_weight'];
      $dataset['dd']['82_pat76_min']=$val['82_pat76_min'];
      $dataset['dd']['82_pat76_weight']=$val['82_pat76_weight'];
      $dataset['dd']['85_gat_min']=$val['85_gat_min'];
      $dataset['dd']['85_gat_min_part2']=$val['85_gat_min_part2'];
      $dataset['dd']['85_gat_weight']=$val['85_gat_weight'];
      */
      

      $dataset['dd']['gpax_min']=$val['gpax_min'];
      $dataset['dd']['gpax_weight']=$val['gpax_weight'];
 
      $d_01_onet_mi=$val['01_onet_mi'];
      $d_01_onet_weight=$val['01_onet_weight'];
      $d_02_onet_min=$val['02_onet_min'];
      $d_02_onet_weight=$val['02_onet_weight'];
      $d_03_onet_mint=$val['03_onet_mint'];
      $d_03_onet_weight=$val['03_onet_weight'];
      $d_04_onet_min=$val['04_onet_min'];
      $d_04_onet_weight=$val['04_onet_weight'];
      $d_05_onet_min=$val['05_onet_min'];
      $d_05_onet_weight=$val['05_onet_weight'];
      $d_71_pat1_min=$val['71_pat1_min'];
      $d_71_pat1_weight=$val['71_pat1_weight'];
      $d_72_pat2_min=$val['72_pat2_min'];
      $d_72_pat2_weight=$val['72_pat2_weight'];
      $d_73_pat3_min=$val['73_pat3_min'];
      $d_73_pat3_weight=$val['73_pat3_weight'];
      $d_74_pat4_min=$val['74_pat4_min'];
      $d_74_pat4_weight=$val['74_pat4_weight'];
      $d_75_pat5_min=$val['75_pat5_min'];
      $d_75_pat5_weight=$val['75_pat5_weight'];
      $d_76_pat6_min=$val['76_pat6_min'];
      $d_76_pat6_weight=$val['76_pat6_weight'];
      $d_77_pat71_min=$val['77_pat71_min'];
      $d_77_pat71_weight=$val['77_pat71_weight'];
      $d_78_pat72_min=$val['78_pat72_min'];
      $d_78_pat72_weight=$val['78_pat72_weight'];
      $d_79_pat73_min=$val['79_pat73_min'];
      $d_79_pat73_weight=$val['79_pat73_weight'];
      $d_80_pat74_min=$val['80_pat74_min'];
      $d_80_pat74_weight=$val['80_pat74_weight'];
      $d_81_pat75_min=$val['81_pat75_min'];
      $d_81_pat75_weight=$val['81_pat75_weight'];
      $d_82_pat76_min=$val['82_pat76_min'];
      $d_82_pat76_weight=$val['82_pat76_weight'];
      $d_85_gat_min=$val['85_gat_min'];
      $d_85_gat_min_part2=$val['85_gat_min_part2'];
      $d_85_gat_weight=$val['85_gat_weight'];
      $d_onet_min_total=$val['onet_min_total'];
      $d_onet_weight_total=$val['onet_weight_total'];
      
      if($d_01_onet_mi>=1){
       $dataset['dd']['onet']['01_onet_mi']=$d_01_onet_mi;
      }if($d_01_onet_weight>=1){
      $dataset['dd']['onet']['01_onet_weight']=$d_01_onet_weight;
      }if($d_02_onet_min>=1){
      $dataset['dd']['onet']['02_onet_min']=$d_02_onet_min;
      }if($d_02_onet_weight>=1){
      $dataset['dd']['onet']['02_onet_weight']=$d_02_onet_weight;
       }if($d_03_onet_mint>=1){
      $dataset['dd']['onet']['03_onet_mint']=$d_03_onet_mint;
       }if($d_03_onet_weight>=1){
      $dataset['dd']['onet']['03_onet_weight']=$d_03_onet_weight;
       }if($d_04_onet_min>=1){
      $dataset['dd']['onet']['04_onet_min']=$d_04_onet_min;
       }if($d_04_onet_weight>=1){
      $dataset['dd']['onet']['04_onet_weight']=$d_04_onet_weight;
       }if($d_05_onet_min>=1){
      $dataset['dd']['onet']['05_onet_min']=$d_05_onet_min;
       }if($d_05_onet_weight>=1){
      $dataset['dd']['onet']['05_onet_weight']=$d_05_onet_weight;
       }if($d_onet_min_total>=1){
      $dataset['dd']['onet']['onet_min_total']=$d_onet_min_total;
       }if($d_onet_weight_total>=1){
      $dataset['dd']['onet']['onet_weight_total']=$d_onet_weight_total;
      }if($d_71_pat1_min>=1){
      $dataset['dd']['pat']['71_pat1_min']=$d_71_pat1_min;
      }if($d_71_pat1_weight>=1){
      $dataset['dd']['pat']['71_pat1_weight']=$d_71_pat1_weight;
      }if($d_72_pat2_min>=1){
      $dataset['dd']['pat']['72_pat2_min']=$d_72_pat2_min;
      }if($d_72_pat2_weight>=1){
      $dataset['dd']['pat']['72_pat2_weight']=$d_72_pat2_weight;
      }if($d_73_pat3_min>=1){
      $dataset['dd']['pat']['73_pat3_min']=$d_73_pat3_min;
      }if($d_73_pat3_weight>=1){
      $dataset['dd']['pat']['73_pat3_weight']=$d_73_pat3_weight;
      }if($d_74_pat4_min>=1){
      $dataset['dd']['pat']['74_pat4_min']=$d_74_pat4_min;
      }if($d_74_pat4_weight>=1){
      $dataset['dd']['pat']['74_pat4_weight']=$d_74_pat4_weight;
      }if($d_75_pat5_min>=1){
      $dataset['dd']['pat']['75_pat5_min']=$d_75_pat5_min;
      }if($d_75_pat5_weight>=1){
      $dataset['dd']['pat']['75_pat5_weight']=$d_75_pat5_weight;
      }if($d_76_pat6_min>=1){
      $dataset['dd']['pat']['76_pat6_min']=$d_76_pat6_min;
      }if($d_76_pat6_weight>=1){
      $dataset['dd']['pat']['76_pat6_weight']=$d_76_pat6_weight;
      }if($d_77_pat71_min>=1){
      $dataset['dd']['pat']['77_pat71_min']=$d_77_pat71_min;
      }if($d_77_pat71_weight>=1){
      $dataset['dd']['pat']['77_pat71_weight']=$d_77_pat71_weight;
      }if($d_78_pat72_min>=1){
      $dataset['dd']['pat']['78_pat72_min']=$d_78_pat72_min;
      }if($d_78_pat72_weight>=1){
      $dataset['dd']['pat']['78_pat72_weight']=$d_78_pat72_weight;
      }if($d_79_pat73_min>=1){
      $dataset['dd']['pat']['79_pat73_min']=$d_79_pat73_min;
      }if($d_79_pat73_weight>=1){
      $dataset['dd']['pat']['79_pat73_weight']=$d_79_pat73_weight;
      }if($d_80_pat74_min>=1){
      $dataset['dd']['pat']['80_pat74_min']=$d_80_pat74_min;
      }if($d_80_pat74_weight>=1){
      $dataset['dd']['pat']['80_pat74_weight']=$d_80_pat74_weight;
      }if($d_81_pat75_min>=1){
      $dataset['dd']['pat']['81_pat75_min']=$d_81_pat75_min;
      }if($d_81_pat75_weight>=1){
      $dataset['dd']['pat']['81_pat75_weight']=$d_81_pat75_weight;
      }if($d_82_pat76_min>=1){
      $dataset['dd']['pat']['82_pat76_min']=$d_82_pat76_min;
      }if($d_82_pat76_weight>=1){
      $dataset['dd']['pat']['82_pat76_weight']=$d_82_pat76_weight;
      }if($d_85_gat_min>=1){
      $dataset['dd']['gat']['85_gat_min']=$d_85_gat_min;
      }if($d_85_gat_min_part2>=1){
      $dataset['dd']['gat']['85_gat_min_part2']=$d_85_gat_min_part2;
      }if($d_85_gat_weight>=1){
      $dataset['dd']['gat']['85_gat_weight']=$d_85_gat_weight;
      }
      
      
      $dataset['dd']['branch_id']=$val['branch_id'];
      $dataset['dd']['branch_name']=$val['branch_name'];
      //$dataset['dd']['config']=$val['config'];

      $dataset['dd']['gpax_min']=$val['gpax_min'];
      $dataset['dd']['gpax_weight']=$val['gpax_weight'];
      $dataset['dd']['onet_min_total']=$val['onet_min_total'];
      $dataset['dd']['onet_weight_total']=$val['onet_weight_total'];
      $sharecode=$val['receive_amount_sharecode'];
      $dataset['dd']['receive_amount_sharecode']= $sharecode;
      if($sharecode==''){
        $dataset['dd']['sharecode_data']=null;
        $dataset['dd']['receive_amount']=(int)$val['receive_amount'];
        $dataset['dd']['sharecode_detail']=null;
        $dataset['dd']['sharecode_set']=null;
         $dataset['dd']['sharecode_msg']=null;
      }else{
       $majorcodeshare=$this->model_trainer->get_ams_entrance_major_code($sharecode,$delekey);
       $majorcodeshare_data=$majorcodeshare['data']['0'];
       $receiveamount=(int)$majorcodeshare_data['receive_amount'];
       $dataset['dd']['sharecode_data']=$majorcodeshare_data;
       $dataset['dd']['receive_amount']=$receiveamount;
       $sharecode=$this->model_trainer->get_ams_entrance_sharecode($sharecode,$delekey);
       $sharecode_data=$sharecode['data'];
       $dataset['dd']['detail']=$sharecode_data;
       $sharecode_data_set=implode(',', array_map(function($sharecode_data){ return $sharecode_data['major_remark'];}, $sharecode_data ));
     $dataset['dd']['sharecode_set']=$sharecode_data_set;
     $faculty_name_all=$val['faculty_name'];
     $dataset['dd']['sharecode_msg']=$faculty_name_all.' กลุ่ม ('.$sharecode_data_set.') รับรวมจำนวน '.$receiveamount.' คน';
      }
      $dataset['dd']['score_max']=$val['score_max'];
      $dataset['dd']['score_min']=$val['score_min'];
      $dataset['dd']['special_remark']=$val['special_remark'];
      $dataset['dd']['lastupdate']=$val['lastupdate'];
      $datasetamss[]=$dataset['dd'];
  }
}else{ $datasetamss=null;}
###################################admission#######################################
#######################################################################################

$data=array('u_group_id_type3'=>$u_group_id_type3,
            'u_group_id_type4'=>$u_group_id_type4,
	 	  'ent_id'=>(int)$ent_id,
            'date'=>$date,
            'branch_id'=>(int)$branch_id,
            'faculty_id'=>(int)$faculty_id,
            'year'=>(int)$year,
            'user_id'=>(int)$user_id,
            'group3_name'=>$group_type3_name, 
            'group3_thumbnails'=>$group_type3_thumbnail,
            'group4_name'=>$group_type4_name,
            'tab1'=>$dataarray0,
            //'amslist_data1'=>$amslist_data,
            'amslist_data'=>$datasetamss,
            'amslist_count'=>$amslist_count,
            #'directapplyinuser_data'=>$directapplyinuser_data, //$directapplyinuser,
            #'directapplyinuser_count'=>$directapplyinuser_count,
            'newsdirectapply'=>$newsdirectapplydata,//$newsdirectapply,
            'newsdirectapply_count'=>$newsdirectapply_count,
            #'branch_list'=>$universitylist_data,
            #'branch_count'=>$universitylist_data_count,
            );
     if($data){
     $this->response(array('header'=>array('title'=>'Detail by type34',
     							   'message'=>'Success',
     							   'status'=>true,
     							   'code'=>200), 
     							   'data'=> $data),200);
     }else{
     $this->response(array('header'=>array('title'=>'Detail by type34',
     							   'message'=>'Error or data is null',
     							   'status'=>true, 
     							   'code'=>204), 
     							   'data'=> $data),204);
     }
}
////
}