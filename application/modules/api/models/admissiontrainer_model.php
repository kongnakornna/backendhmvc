<?php
class Admissiontrainer_model extends CI_Model{
private $DBSelect, $DBEdit;
public function __construct(){
parent::__construct();
		 header('Content-Type: text/html; charset=utf-8');
			$CI = & get_instance();
                
		  //$this->load->database('default');  
 
    //$ci->load->helper('common');
    //$this->load->helper('common');
    
    
	}
function encryptcode($q) {
    $cryptKey  = 'tyyptruemd5';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded);
}
function decryptcode($q){
    $cryptKey  = 'tyyptruemd5';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}
public function getSelectuniversity($u_id='',$name="u_parent_id",$default){	
  #Debug($u_parent_id); Debug($default); die();
   #$first = "--- please select subuniversity ---";
   $u_parent_id=(int)$default;
   $first = "--- กรุณาเลือก ---";
   if($u_parent_id!==''){
     $rows=$this->get_university_by_parent_id(0);
   }else{
     $rows=$this->get_university_by_parent_id($u_parent_id='0');
   }
		 #Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption((int)$row['u_id'], $row['u_name']);
	    	}
      /*
      echo'<hr><pre> default=>';print_r($default);echo'<pre> <hr>';  
      echo'<hr><pre> name=>';print_r($name);echo'<pre> <hr>';  
      echo'<hr><pre> opt=>';print_r($opt);echo'<pre> <hr>';  
      */
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
}  
public function get_university_by_id($u_id='',$delekey){
if($u_id==''){
$sql="SELECT * FROM ams_university WHERE u_parent_id=0 and record_status=1 order by u_id asc";
}else{
$sql="SELECT * FROM ams_university WHERE u_id=$u_id and u_parent_id=0 and record_status=1 order by u_id asc";
}
$cache_key = "get_university_by_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function getSelectuniversity_parent($u_id='',$name="u_id",$default){	
  #Debug($u_parent_id); Debug($default); die();
  //echo'<hr><pre>  u_id=>';print_r($u_id);echo'<pre> <hr>'; 
   #$first = "--- please select subuniversity ---";
   $u_parent_id=(int)$u_id;
   $first = "--- กรุณาเลือกคณะ ---";
   if($u_parent_id!==''){
     $rows=$this->get_university_by_parent_id($u_parent_id);
   }else{
     $rows=$this->get_university_by_parent_id($u_parent_id='0');
   }
		 #Debug($rows);
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=0;$i<count($rows);$i++){
	    		$row = @$rows[$i];
	    		$opt[]	= makeOption((int)$row['u_id'], $row['u_name']);
	    	}
      /*
      echo'<hr><pre> default=>';print_r($default);echo'<pre> <hr>';  
      echo'<hr><pre> name=>';print_r($name);echo'<pre> <hr>';  
      echo'<hr><pre> opt=>';print_r($opt);echo'<pre> <hr>';  
      */
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
}  
public function get_university_by_parent_id($u_parent_id='',$delekey){
$delekey;
if($u_parent_id==Null){
$sql="SELECT * FROM ams_university WHERE u_parent_id=0 and record_status=1 order by u_id asc";
}else{
$sql="SELECT * FROM ams_university WHERE u_parent_id=$u_parent_id and record_status=1 order by u_id asc";
}
$cache_key = "get_university_by_parent_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}

return $data; 
}
public function getSelectyear($year_id='',$name="year",$default){	
  #Debug($u_parent_id); Debug($default); die();
   #$first = "--- please select subuniversity ---";
   $year_id=(int)$default;
   $first = "--- กรุณาเลือกปี ---";
   $ydate=date('Y');
   $yf=$ydate+5;
   $yb=$ydate-15;
	    	$opt = array();
	    	$opt[]	= makeOption(0,$first);
	    	for($i=$yb;$i<=$yf;$i++){
	    		$row = $i;
	    		$opt[]	= makeOption((int)$i,$i);
	    	}
      /*
      echo'<hr><pre> default=>';print_r($default);echo'<pre> <hr>';  
      echo'<hr><pre> name=>';print_r($name);echo'<pre> <hr>';  
      echo'<hr><pre> opt=>';print_r($opt);echo'<pre> <hr>';  
      */
	    	return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);
}  
public function get_university_u_id_and_parent_id($u_parent_id,$delekey){
$delekey;
$sql="SELECT * FROM ams_university WHERE  u_parent_id=$u_parent_id and record_status=1 order by u_id asc";
$cache_key = "get_university_u_id_and_parent_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();
return $data; 
}
public function get_ams_entrance($id,$year,$config='',$delekey){
 if($config==''){
$sql="select ent_id,u_id,year_config,major_code,major_remark,gpax_weight,gpax_min,onet_weight_total,onet_min_total
,01_onet_weight,01_onet_mi,02_onet_weight,02_onet_min,03_onet_weight,03_onet_mint,04_onet_weight,04_onet_min
,05_onet_weight,05_onet_min,85_gat_weight,85_gat_min,85_gat_min_part2,71_pat1_weight,71_pat1_min,72_pat2_weight
,72_pat2_min,73_pat3_weight,73_pat3_min,74_pat4_weight,74_pat4_min,75_pat5_weight,75_pat5_min,76_pat6_weight
,76_pat6_min,77_pat71_weight,77_pat71_min,78_pat72_weight,78_pat72_min,79_pat73_weight,79_pat73_min
,80_pat74_weight,80_pat74_min,81_pat75_weight,81_pat75_min,82_pat76_weight,82_pat76_min
,special_remark,receive_amount,receive_amount_sharecode,lastupdate,config
,case config when '1' then 'เกณฑ์คะนนอย่างเดียว' when '2' then 'เกณฑ์การรับและเกรณ์คะนน' when '' then 'เกณฑ์การรับ' end as criterion
,(select score_min  from ams_entrance_score_history as h where h.year=ams_entrance.year_config and h.ent_id=ams_entrance .ent_id)as score_min
,(select score_max  from ams_entrance_score_history as h where h.year=ams_entrance.year_config and h.ent_id=ams_entrance .ent_id)as score_max
from ams_entrance 
where  u_id=$id and year_config=$year order by ent_id asc";
 }else{
  $sql="select ent_id,u_id,year_config,major_code,major_remark,gpax_weight,gpax_min,onet_weight_total,onet_min_total
,01_onet_weight,01_onet_mi,02_onet_weight,02_onet_min,03_onet_weight,03_onet_mint,04_onet_weight,04_onet_min
,05_onet_weight,05_onet_min,85_gat_weight,85_gat_min,85_gat_min_part2,71_pat1_weight,71_pat1_min,72_pat2_weight
,72_pat2_min,73_pat3_weight,73_pat3_min,74_pat4_weight,74_pat4_min,75_pat5_weight,75_pat5_min,76_pat6_weight
,76_pat6_min,77_pat71_weight,77_pat71_min,78_pat72_weight,78_pat72_min,79_pat73_weight,79_pat73_min
,80_pat74_weight,80_pat74_min,81_pat75_weight,81_pat75_min,82_pat76_weight,82_pat76_min
,special_remark,receive_amount,receive_amount_sharecode,lastupdate,config
,case config when '1' then 'เกณฑ์คะนนอย่างเดียว' when '2' then 'เกณฑ์การรับและเกรณ์คะนน' when '' then 'เกณฑ์การรับ' end as criterion
,(select score_min  from ams_entrance_score_history as h where h.year=ams_entrance.year_config and h.ent_id=ams_entrance .ent_id)as score_min
,(select score_max  from ams_entrance_score_history as h where h.year=ams_entrance.year_config and h.ent_id=ams_entrance .ent_id)as score_max
from ams_entrance 
where  u_id=$id and year_config=$year and config=$config order by ent_id asc";
 }

$cache_key = "get_ams_entrance_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();

return $data; 
}
public function get_ams_entrance_config($id,$year,$config='',$delekey){
 if($config==''){
$sql="select ent_id,u_id,year_config,major_code,major_remark,gpax_weight,gpax_min,onet_weight_total,onet_min_total
,01_onet_weight
,01_onet_mi
,02_onet_weight
,02_onet_min
,03_onet_weight
,03_onet_mint
,04_onet_weight
,04_onet_min
,05_onet_weight
,05_onet_min
,85_gat_weight
,85_gat_min
,85_gat_min_part2
,71_pat1_weight
,71_pat1_min
,72_pat2_weight
,72_pat2_min
,73_pat3_weight
,73_pat3_min
,74_pat4_weight
,74_pat4_min
,75_pat5_weight
,75_pat5_min
,76_pat6_weight
,76_pat6_min
,77_pat71_weight
,77_pat71_min
,78_pat72_weight
,78_pat72_min
,79_pat73_weight
,79_pat73_min
,80_pat74_weight
,80_pat74_min
,81_pat75_weight
,81_pat75_min
,82_pat76_weight
,82_pat76_min
,special_remark,receive_amount,receive_amount_sharecode,lastupdate,score,scoremin
,config
,case config when '1' then 'เกณฑ์คะนนอย่างเดียว' when '2' then 'เกณฑ์การรับและเกรณ์คะนน' when '' then 'เกณฑ์การรับ' end as criterion
 from ams_entrance where u_id=$id and year_config=$year and (config=1 or config=2)order by ent_id asc";
 }else{
  $sql="select ent_id,u_id,year_config,major_code,major_remark,gpax_weight,gpax_min,onet_weight_total,onet_min_total
,01_onet_weight
,01_onet_mi
,02_onet_weight
,02_onet_min
,03_onet_weight
,03_onet_mint
,04_onet_weight
,04_onet_min
,05_onet_weight
,05_onet_min
,85_gat_weight
,85_gat_min
,85_gat_min_part2
,71_pat1_weight
,71_pat1_min
,72_pat2_weight
,72_pat2_min
,73_pat3_weight
,73_pat3_min
,74_pat4_weight
,74_pat4_min
,75_pat5_weight
,75_pat5_min
,76_pat6_weight
,76_pat6_min
,77_pat71_weight
,77_pat71_min
,78_pat72_weight
,78_pat72_min
,79_pat73_weight
,79_pat73_min
,80_pat74_weight
,80_pat74_min
,81_pat75_weight
,81_pat75_min
,82_pat76_weight
,82_pat76_min
,special_remark,receive_amount,receive_amount_sharecode,lastupdate,score,scoremin
,config 
,case config when '1' then 'เกณฑ์คะนนอย่างเดียว' when '2' then 'เกณฑ์การรับและเกรณ์คะนน' when '' then 'เกณฑ์การรับ' end as criterion
 from ams_entrance where u_id=$id and year_config=$year and config=$config and (config=1 or config=2) order by ent_id asc";
 }

$cache_key = "get_ams_entrance_config_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################


$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();

return $data; 
}
public function datawherearrays($table,$datawhere,$delekey){       
$sql = "select * from $table where $datawhere ";
#echo '<pre> $sql=>';echo $sql;echo '</pre>';        
$cache_key = "datawherearrays_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################


$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
   $query=$this->db->query($sql);
   $data = $query->result_array();
   $num_rows=$query->num_rows();
   $this->tppymemcached->set($cache_key_encrypt,$rs,3600);
}

            $data=array();
            foreach ($rs as $key1 => $v1) {
             $u_id=$v1['u_id'];
             $table='ams_university';
             $datawhere='u_parent_id='.$u_id;
             $sql="select u_id from $table where $datawhere ";
		           $query=$this->db->query($sql);
		           $rs2=$query->result_array();
		           $num_rows2=$query->num_rows();
	            $data[$key1]['u_id']=$v1['u_id'];
	            $data[$key1]['u_parent_id']=$v1['u_parent_id'];
	            $data[$key1]['u_name']=$v1['u_name'];
	            $data[$key1]['thumbnail']=$v1['thumbnail'];
	            $data[$key1]['short_description']=$v1['short_description'];
	            $data[$key1]['detail']=$v1['detail']; 
	            $data[$key1]['u_group_id']=$v1['u_group_id'];
	            $data[$key1]['record_status']=$v1['record_status'];
	            $data[$key1]['add_timestamp']=$v1['add_timestamp'];
	            $data[$key1]['update_timestamp']=$v1['update_timestamp'];
	            $data[$key1]['list']=$rs2;
	            $data[$key1]['num_rows']=$num_rows2;
			  }
            $data1=array('sql' => $sql,'num_rows' => $num_rows,'data' => $data,);
            
            
            if($data1){  
            	return $data1;
            }else{
            	$data=null;
            	return $data;
            }
        }
public function get_faculty_name_all_university($faculty_name,$delekey){
$sql="select u.*
,(select u_name  from  ams_university  where u_name!='' and u_id=u.u_parent_id and  record_status=1)as faculty_name
from ams_university  u where record_status=1 and u.u_name='$faculty_name' ";
$cache_key="get_faculty_name_all_university_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_branch_name_all_faculty_name($faculty_name,$delekey){
$sql="select (select u_name  from  ams_university  where u_name!='' and u_id=u.u_parent_id and  record_status=1)as faculty_name
,u.u_name as branch_name,u.u_id  as id,u.u_parent_id,u.thumbnail
from ams_university  u where record_status=1 and u.u_name='$faculty_name'";
$cache_key="get_get_branch_name_all_faculty_name_'.$sql.";

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_universitybyid($u_id,$delekey){
$sql="select (select u_name  from  ams_university  where u_name!='' and u_id=u.u_parent_id and  record_status=1)as faculty_name
,u.u_name as branch_name,u.u_id  as id,u.u_parent_id
,concat('http://static.trueplookpanya.com/trueplookpanya/',u.thumbnail) AS thumbnail
from ams_university  u where record_status=1 and u.u_id=$u_id";
$cache_key="get_universitybyid_'.$sql.";


 
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_group_m2m($u_group_id_source,$delekey){
$sql="select m2m.u_group_id_source as group_id,m2m.u_group_id_destination as fac_id
,(select u_name  from  ams_university  where u_id=fac_id and  record_status=1)as faculty_name
,(select u_id  from  ams_university  where u_id=fac_id and  record_status=1)as u_id
,(select u_parent_id  from  ams_university  where u_id=fac_id and  record_status=1)as u_parent_id
from ams_university_group_m2m as m2m where m2m.u_group_id_source=$u_group_id_source";
$cache_key="get_ams_university_group_m2m_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_u_parent_id($u_parent_id,$delekey){
$sql="select 
(select u_id from  ams_university  where u_id=p.u_parent_id)as university_id
,(select u_name from  ams_university  where u_id=p.u_parent_id)as university_name
,p.* from  ams_university as p where p.u_id=$u_parent_id";
$cache_key="get_ams_university_u_parent_id_'.$sql.";


 
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_u_id($u_id,$delekey){
$sql="select *  from  ams_university  where u_id=$u_id ";
$cache_key="get_ams_university_u_id_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_entrance_score_history_ent_id($ent_id,$delekey){
$sql="select  * from  ams_entrance_score_history  where ent_id=$ent_id order by year asc ";
$cache_key="get_ams_entrance_score_history_ent_id_'.$sql.";

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
//list box 
public function get_ams_university_group3($delekey){
$sql="select * from ams_university_group where u_group_type=3";
$cache_key="get_ams_university_group3_'.$sql.";

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
//list box 2 Var1
public function get_ams_university_group_m2m_typy3($u_group_id,$delekey){
$sql="select * from ams_university_group_m2m where u_group_id_source=$u_group_id";
$cache_key="get_ams_university_group_m2m_typy3_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
$data=array('data'=>$data,
										  'sql'=>$sql,);
return $data; 
}
//list box 2 Var2
public function get_ams_university_group_map_list_group_in($group_in,$delekey){
$sql="select *,(select u_name from  ams_university where u_id=map.u_id)as fac_name,(select u_parent_id from  ams_university where u_id=map.u_id)as u_parent_id from ams_university_group_map as map where map.u_group_id in ($group_in) order by idx asc";
$cache_key="get_ams_university_group_map_list_group_in_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
return $data; 
}
//list box 2 listdata form u_id
public function get_ams_entrance_list($u_id,$delekey){
$sql="select ams.*
,(select (select uu.u_id from  ams_university as uu where uu.u_id=u1.u_parent_id)as university_name from  ams_university as u1 where u1.u_id=u.u_parent_id)as university_id
,(select (select uu.u_name from  ams_university as uu where uu.u_id=u1.u_parent_id)as university_name from  ams_university as u1 where u1.u_id=u.u_parent_id)as university_name
,(select u_name from  ams_university where u_id=u.u_parent_id)as faculty_name
,(select u_name from  ams_university where u_id=ams.u_id)as branch_name
,u.u_parent_id,h.score_min,h.score_max
from  ams_entrance as ams 
left join  ams_university as u on u.u_id=ams.u_id
left join  ams_entrance_score_history as h on h.ent_id=ams.ent_id and   h.year=ams.year_config
where ams.u_id=$u_id";
$cache_key="get_ams_entrance_list_".$sql;

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_university_group_map_typy3($u_group_id_destination,$delekey){
$sql="select * from ams_university_group_map where u_group_id=$u_group_id_destination";
$cache_key="get_university_group_map_typy3_'.$sql.";

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
################# group3
public function get_ams_university_typy3($u_id,$delekey){
$sql="select * from ams_university where u_id=$u_id";
$cache_key="get_ams_university_typy3_'.$sql.";

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
################# group4
public function get_ams_university_group4($delekey){
$sql="select * from ams_university_group where u_group_type=4";
$cache_key="get_ams_university_group4_'.$sql.";

##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_group_id($u_group_id,$delekey){
$sql="select g.* from ams_university_group as g where g.u_group_id=$u_group_id";
$cache_key="get_ams_university_group_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_group_type4_u_group_id_list($u_group_id,$delekey){
$sql_old="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))) AS thumbnail
,g.u_group_name as group_name
,g.short_description as  group_description
,g.u_group_id	as group_id
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group as g
inner join ams_university_group_map  as m on g.u_group_id=m.u_group_id
inner join ams_university as amsu on amsu.u_id=m.u_id
where g.u_group_id=$u_group_id
order by amsu.u_id asc,m.idx asc";


$sql="select  amsu.u_name as branch_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))) AS thumbnail
,g.u_group_name as group_name
,g.short_description as  group_description
,g.u_group_id	as group_id
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group as g
inner join ams_university_group_map  as m on g.u_group_id=m.u_group_id
inner join ams_university as amsu on amsu.u_id=m.u_id
where g.u_group_id=$u_group_id 
order by amsu.u_id asc,m.idx asc";
$cache_key="get_group_type4_u_group_id_list_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
return $data; 
}
################# group3and4
public function get_ams_university_group_id_type34($ugroupid34,$delekey){
$sql="select g.u_group_id,g.u_group_name,g.short_description,g.u_group_type,g.record_status
,concat('http://static.trueplookpanya.com',g.thumbnail) as thumbnails 
from ams_university_group as g where g.u_group_id in ($ugroupid34) 
order by  g.u_group_type,g.u_group_id asc ";
$cache_key="get_ams_university_group_id_type34_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
################
################
public function get_ams_faculty_id($faculty_id,$delekey){
$sql="select un.u_id as faculty_id,un.u_name as faculty_name
from ams_university as un 
left join  ams_university as u on u.u_id=un.u_id
where un.u_id=$faculty_id";
$cache_key="get_ams_faculty_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_faculty_list($u_parent_id,$delekey){
$sql="select un.u_id as faculty_id,un.u_name as faculty_name
from ams_university as un 
left join  ams_university as u on u.u_id=un.u_id
where un.u_parent_id=$u_parent_id";
$cache_key="get_ams_faculty_list_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_university_faculty_branch_list($branch_id,$delekey){
$sql="select (select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
from  ams_university as un where un.u_id=$branch_id";
$cache_key="get_university_faculty_branch_list_'.$sql.";


##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
  $data=$this->db->query($sql)->result_array();
  $this->tppymemcached->set($cache_key_encrypt,$data,3600);
  }
return $data; 
}
public function get_ams_entrance_score_history_where($ent_id,$year='',$delekey){
 if($year==''){
  $sql="SELECT * FROM ams_entrance_score_history WHERE  ent_id=$ent_id order by year asc,idx desc";
 }else{
  $sql="SELECT * FROM ams_entrance_score_history WHERE  ent_id=$ent_id and year=$year order by year asc,idx desc";
 }
$cache_key = "get_ams_entrance_score_history_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();
return $data; 
}
public function get_ams_entrance_group_by_branch($year='',$delekey){
 if($year==''){
  $sql="select DISTINCT  ent.ent_id,un.u_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,ent.major_remark
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!=''  group by branch_id order by un.u_id asc";
 }else{
  $sql="select DISTINCT ent.ent_id,un.u_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,ent.major_remark
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!=''  and un.u_id!='' and ent.year_config=$year group by branch_id order by un.u_id asc";
 }
$cache_key = "get_ams_entrance_group_by_branch_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();
return $data; 
}
public function get_ams_entrance_group_by_university($year='',$delekey){
 if($year==''){
  $sql="select DISTINCT  ent.ent_id,un.u_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,ent.major_remark
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!=''  group by university_id order by un.u_id asc";
 }else{
  $sql="select DISTINCT ent.ent_id,un.u_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,un.u_parent_id as faculty_id
,un.u_id as branch_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,un.u_name as branch_name 
,ent.major_remark
from  ams_entrance as ent  
left join ams_university as un on ent.u_id=un.u_id
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.ent_id!=''  and ent.year_config=$year group by university_id order by un.u_id asc";
 }
$cache_key = "get_ams_entrance_group_by_university_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
//echo'<hr><pre> $sql=>';print_r($sql);echo'<pre><hr>'; echo'<pre> data=>';print_r($data);echo'<pre> <hr>';  die();
return $data; 
}
public function get_ams_university_u_parent_ido($delekey){
$sql="select  u_id,u_name from ams_university  where  u_parent_id=0 and u_name!='' order by u_id asc";
$cache_key="get_ams_university_u_parent_ido_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_group_type3_id_list($group_id,$delekey){
$sql="select * from ams_university_group where u_group_type=3 and u_group_id=$group_id";
$cache_key="get_group_type3_id_list_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){	
$data=$this->db->query($sql)->result_array();
#echo 'sql';
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}else{
$data=$this->tppymemcached->get($cache_key_encrypt);
//echo 'cache';
}
//echo '<pre>data=>';print_r($data); echo '</pre>';die();

return $data; 
}
public function get_ams_university_by_u_id($u_id,$delekey){
$sql="select u_id,u_group_id,u_name,short_description
,(select count(u_id) from  ams_university where u_parent_id=u.u_id)as faculty_count
,concat('http://static.trueplookpanya.com/trueplookpanya/',if(thumbnail is not null,concat('',thumbnail),'')) AS thumbnail_url
from  ams_university as u  where u.u_id=$u_id";
$cache_key="get_ams_university_by_u_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_by_u_parent_id($u_parent_id,$delekey){
$sql="select u_id,u_group_id,u_name,short_description
,concat('http://static.trueplookpanya.com/trueplookpanya/',if(thumbnail is not null,concat('',thumbnail),'')) AS thumbnail_url
from  ams_university  where u_parent_id=$u_parent_id";
$cache_key="get_ams_university_by_u_parent_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_list_all($year,$delekey=''){
if($year==''){
$year=(int)date('Y');
$year=(int)$year+543;
$year=(int)$year-1;
}
$sql="select u.u_id,u.u_group_id,u.u_name
,concat(if(ifnull(g.u_group_name,'')!='',concat(g.u_group_name),'')) as group_name
,(select count(u_id) from  ams_university where u_parent_id=u.u_id)as faculty_count
,(select count(ent.ent_id) as count_ams_university from  ams_entrance as ent left join  ams_university as unt on unt.u_id=ent.u_id  where ent.year_config=$year 
and  ent.u_id in (select u_id from  ams_university where u_parent_id in (select u_id from  ams_university where u_parent_id=u.u_id)))as ams_university_count
,concat('http://static.trueplookpanya.com/trueplookpanya/',if(u.thumbnail is not null,concat( '',u.thumbnail),'')) AS thumbnail_url
from  ams_university as u 
left join  ams_university_group as g on g.u_group_id=u.u_group_id
where u.u_parent_id=0 order by u_id asc";
$cache_key="get_ams_university_list_all_".$year.$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_dataset($dataset,$delekey){
$sql="select  u_id as branch_id,u_name as branch_name from  ams_university  where  u_parent_id in($dataset)order by u_id asc";
$cache_key="get_ams_university_dataset_2018_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
$dataall=$data;
/*
$branch_data=array();
 foreach($data as $key=> $value) {
                      $branch['branch_id']=$value['branch_id'];
                      $branch_data[] = $branch;
}
$num=0;
 foreach($branch_data as $k=>$v){
                   $id=$v['branch_id'];
                   $num++;
}
$branch_set=implode(',', array_map(function($v){ return $v['branch_id'];}, $branch_data ));
#########################
$dataall = array('sql'=$sql,'branch_set'=$branch_set,'data'=>$data);
echo '<hr><pre>  dataall=>'; print_r($dataall); echo '</pre>';die();
*/
//echo '<hr><pre>  dataall=>'; print_r($dataall); echo '</pre>';die();
return $dataall; 
}
public function get_ams_university_u_parent_id_to_u_id_dataset($dataset,$delekey){
$sql="select  uu.u_parent_id as uid
,(select u_name from  ams_university where u_id=uu.u_parent_id)as uname
from  ams_university as uu where  uu.u_id in($dataset)group by uu.u_parent_id order by uu.u_id asc";
$cache_key="get_ams_university_u_parent_id_to_u_id_dataset_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_u_id_to_u_parent_id_dataset($dataset,$delekey){
$sql="select  uu.u_id as uid
,(select u_name from  ams_university where u_id=uu.u_id)as uname
from  ams_university as uu where  uu.u_parent_id in($dataset)group by uu.u_id order by uu.u_id asc";
$cache_key="get_ams_university_u_parent_id_dataset_".$sql;
//echo '<pre>  $sql=>'; print_r($sql); echo '</pre>';die();
########################## 
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_entrance_to_u_parent_id_dataset($dataset,$year='2560',$delekey){
$sql="select ent.*
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
from  ams_entrance as ent
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year and  ent.u_id in($dataset)order by ent.ent_id asc";
$cache_key="get_ams_entrance_to_u_parent_id_datase_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_u_id_dataset($dataset,$delekey){
$sql="select  uu.u_id as uid,uu.u_name as uname
from  ams_university as uu where  uu.u_id in($dataset)group by uu.u_id order by uu.u_id asc";
$cache_key="get_ams_university_u_id_dataset_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
public function get_ams_university_ams_entrance_dataset($dataset,$year,$delekey){
$sql="select 
(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_id
,(select u_parent_id from  ams_university where u_id=ent.u_id)as faculty_id
,(select u_id from  ams_university where u_id=ent.u_id)as branch_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id))as faculty_name
,(select u_name from  ams_university where u_id=ent.u_id)as branch_name
,ent.*,h.score_min,h.score_max
from  ams_entrance as ent
left join ams_entrance_score_history  as h on h.ent_id=ent.ent_id and h.year=ent.year_config
where ent.u_id in ($dataset)and ent.year_config=$year order by ent.ent_id asc";
$cache_key="get_ams_university_ams_entrance_dataset_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
$dataall=$data;
#echo '<hr><pre>  dataall=>'; print_r($dataall); echo '</pre>';die();
return $dataall; 
}
//group_map_form_ams_entrance_branch_id
public function get_university_group_map_form_ams_entrance_branch_id_dataset($dataset,$delekey){
$sql="select m2m.u_group_id_source as group_id 
,(select u_group_name from  ams_university_group where  u_group_id=m2m.u_group_id_source)as group_name
from  ams_university_group_map as map 
left join  ams_university_group_m2m as m2m on m2m.u_group_id_destination=map.u_id
where m2m.u_group_id_source!='' and map.u_id in($dataset)
group by m2m.u_group_id_source order by m2m.u_group_id_source,map.idx asc";
$cache_key="get_university_group_map_form_ams_entrance_branch_id_dataset_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data; 
}
//แยก ams รายมหาวิทยาลัย รายสาขา
public function get_ams_university_ams_entrance_group_by_u_id_year($year_config,$u_id,$delekey=''){
$sql="select ent.*
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
from  ams_entrance as ent
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year_config and  ent.u_id in (select u.u_id from  ams_university as u where u.u_parent_id in (select u.u_id from  ams_university as u where u.u_parent_id=$u_id))
group by un.u_id order by  un.u_id asc ";
$cache_key="get_ams_university_ams_entrance_group_by_u_id_year".$year_config.$u_id;
##########################

//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();

$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
 #echo '<hr><pre>  dataall=>'; print_r($dataall); echo '</pre>';die();
return $data; 
}
#group
public function get_ams_university_group_map_group_id($group_id,$delekey){
$sql="select(select u_group_name from ams_university_group where u_group_id=m.u_group_id)as group_name
,(select u_name from ams_university where u_id=m.u_id)as b_name
,m.u_id,m.u_id as branch_id from ams_university_group_map as m where m.u_group_id=$group_id group by u_id order by u_id asc";
$cache_key="get_ams_university_group_map_group_id_".$group_id;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
 #echo '<hr><pre>  dataall=>'; print_r($dataall); echo '</pre>';die();
return $data; 
}
# entrance group u_id  year
public function get_ams_university_ams_entrance_group_by_u_id_year_setid($year_config,$u_id,$setid,$delekey){
$sql="select ent.*
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
from  ams_entrance as ent
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year_config and  ent.u_id in (select u.u_id from  ams_university as u where u.u_parent_id in 
(select u.u_id from  ams_university as u where u.u_parent_id=$u_id and ent.u_id in($setid)))
group by un.u_id order by  un.u_id asc";
$cache_key="get_ams_university_ams_entrance_group_by_u_id_year_setid_".$year_config.$u_id.$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}

 #echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';
 #echo '<hr><pre>dataall=>'; print_r($dataall); echo '</pre>';die();
 $data=array('sql'=>$sql,'data'=>$data,);

return $data; 
}
public function get_ams_university_ams_entrance_group_by_u_id_year_setid_faculty_name_branch_name($year_config,$u_id,$setid,$delekey){
$sql="select (select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
from  ams_entrance as ent
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year_config and  ent.u_id in (select u.u_id from  ams_university as u where u.u_parent_id in 
(select u.u_id from  ams_university as u where ent.u_id in($setid)))
group by un.u_id order by  un.u_id asc";
$cache_key="get_ams_university_ams_entrance_group_by_u_id_year_setid_faculty_name_branch_name_".$year_config.$u_id.$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}

 #echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';
 #echo '<hr><pre>dataall=>'; print_r($dataall); echo '</pre>';die();
 $datars=array('sql'=>$sql,'data'=>$data,);

return $datars; 
}
public function get_ams_university_ams_entrance_group_by_year_setid($year_config,$setid,$delekey){
$sql="select ent.*
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
from  ams_entrance as ent
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year_config and  ent.u_id in (select u.u_id from  ams_university as u where u.u_parent_id in 
(select u.u_id from  ams_university as u where ent.u_id in($setid)))
group by un.u_id order by  un.u_id asc";
$cache_key="get_ams_university_ams_entrance_group_by_year_setid_".$year_config.$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$datars=$this->tppymemcached->get($cache_key_encrypt);
if(!$datars) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$datars = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$datars,3600);
}
//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';
//echo '<hr><pre>datars=>'; print_r($datars); echo '</pre>';die();
$data=array('sql'=>$sql,'data'=>$datars,);
return $data;
}
public function get_profile_by_username_password($user_username,$user_password){
$user_password=MD5($user_password);
$sql="select u.*, 'upload' as folder_path, b.blog_id 
FROM users_account u 
LEFT OUTER JOIN blog b ON b.member_id = u.member_id 
where u.user_username='$user_username' AND u.user_password='$user_password'
";
//echo 'sql=>'.$sql; 
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data=$this->db->query($sql)->result_array();
return $data;
}
public function get_profile_by_user_id($user_id){
$user_password=MD5($user_password);
$sql="select u.*, 'upload' as folder_path, b.blog_id 
FROM users_account u 
LEFT OUTER JOIN blog b ON b.member_id = u.member_id 
where u.user_id='$user_id'
";
//echo 'sql=>'.$sql; 
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data=$this->db->query($sql)->result_array();
return $data;
}
public function get_setgroup_ams_news_directapply_university($setgroup){
$sql="select (select news_title from  ams_news_directapply where news_id=ud.news_id)as news_title
,ud.major_receivers as receivers
,ud.major_grade as gpax
,ud.major_gatpat AS gatpat
,ud.major_gnet AS req_gnet
,ud.major_portfolio AS req_portfolio
,ud.major_interview AS req_interview
,ud.major_matches AS req_matches
,ud.major_property AS req_property
,ud.major_receivers AS receiver_number
,(select u_name from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ud.u_id)))as university
,(select u_name from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ud.u_id))as fac_name
,(select u_name from  ams_university where  u_id=ud.u_id)as u_name
,(select u_parent_id from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ud.u_id))as university_id
,(select u_id from  ams_university where  u_id=ud.u_id)as faculty_id
,(select u_parent_id from  ams_university where  u_id=ud.u_id)as branch_id
from ams_news_directapply_university as ud
where ud.major_receivers!='' and ud.u_id in (select u_id from ams_university_group_map 
where u_group_id in ($setgroup)) group by branch_id";

//echo 'sql=>'.$sql; 

$cache_key="get_setgroup_ams_news_directapply_university_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
return $data;
}
public function get_ams_university_ams_entrance_group_by_u_id_year_branch_id($year_config,$u_id,$branch_id,$delekey){
$sql="select ent.*
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
from  ams_entrance as ent
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year_config and ent.u_id=$branch_id and ent.u_id in (select u.u_id from  ams_university as u where u.u_parent_id in (select u.u_id from  ams_university as u where u.u_parent_id=$u_id))
group by un.u_id order by  un.u_id asc ";
$cache_key="get_ams_university_ams_entrance_group_by_u_id_year_branch_id".$year_config.$u_id.$branch_id.$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$data = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$data,3600);
}
 //echo '$sql=>'.$sql.'<br>';echo '<pre>data=>';print_r($data); echo '</pre>';die();
return $data; 
}
public function get_ams_entrance_user_by_ent_id_u_id_user_id($ent_id,$u_id,$user_id,$faculty_id,$delekey){
if($faculty_id==''){$faculty_id=0;}
$sql="select * from  ams_entrance_user where ent_id=$ent_id and u_id=$u_id and user_id=$user_id and directapply_faculty_id=$faculty_id";
/*
$query=$this->db->query($sql);
$datars=$query->result_array();
$num_rows=(int)$query->num_rows();
*/
 
###########cache###############
$cache_key="get_ams_entrance_user_by_ent_id_u_id_user_id_".$ent_id.'_'.$u_id.'_'.$user_id.'_'.$faculty_id;
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$datars=$this->tppymemcached->get($cache_key_encrypt);
if(!$datars) {
$query=$this->db->query($sql);
$datars=$query->result_array();
$num_rows=(int)$query->num_rows();
$this->tppymemcached->set($cache_key_encrypt,$datars,300);
$sql1='sql_query->'.$sql;
} 
###########cache###############
 if($datars){$sql1='sql_cache->'.$sql;}
if($num_rows!==0){$status=1;}else{ $status=0;}
$data=array('sql'=>$sql1,'data'=>$datars,'status'=>$status);
 #echo '<hr><pre> data=>'; print_r($data); echo '</pre>';die();
return $data;
}
public function deletedatatb3($table,$filddb1,$id1,$filddb2,$id2,$filddb3,$id3,$filddb4,$id4){

$this->db->where($filddb1, $id1);
$this->db->where($filddb2, $id2);
$this->db->where($filddb3, $id3);
$this->db->where($filddb4, $id4);
#########################
$user_id=$id1;
$u_id=$id2;
$ent_id=$id3;
$faculty_id=$id4;

$cache_key="get_ams_entrance_user_by_ent_id_u_id_user_id_".$ent_id.'_'.$u_id.'_'.$user_id.'_'.$faculty_id;
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
$this->tppymemcached->delete($cache_key_encrypt);


#########################
$result_data=$this->db-> delete($table);
if($result_data=='1'){$status='1';
$data = array('mesage' =>'Delete '.$filddb1.'=>'.$id1.' and  '.$filddb2.'=>'.$id2.' and '.$filddb3.'=> '.$id3.' and '.$filddb4.'=> '.$id4.' successful..',
'status' => $status,
);
$result_data=$data;
}else{
$status='0';
$data = array('mesage' =>'Delete '.$filddb1.'=>'.$id1.' and  '.$filddb2.'=>'.$id2.' and '.$filddb3.'=> '.$id3.' and '.$filddb4.'=> '.$id4.' unsuccessful..',
'status' => $status,
);
$result_data=$data;
}
return $result_data;
}
public function insertdatatb($table,$data){
$ent_id=$data['ent_id'];
$user_id=$data['user_id'];
$u_id=$data['u_id'];
$faculty_id=$data['faculty_id'];
$sql="select * from  ams_entrance_user where ent_id=$ent_id and user_id=$user_id and u_id=$u_id and directapply_faculty_id=$faculty_id";
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$rsdata->num_rows();
#echo '<pre>';echo ' numrows=>'; print_r($numrows); echo '</pre>'; 
#echo '<pre>';echo ' rsdata=>'; print_r($rsdata); echo '</pre>';die();
if($numrows>0){
	$resultdata='Have data';
	   	 $status='2';
	   	  $result_data = array(
	                'result' => $resultdata,
	                'data' => $rs,
	                'status' => $status,
	                );
	return $result_data;
	//Die();
}
$result_data_insert=$this->db->insert($table,$data);   
$DBSelect = $this->db;
if($result_data_insert==1){
$insert_id=$this->db->insert_id();

 
#########################
$cache_key="get_ams_entrance_user_by_ent_id_u_id_user_id_".$ent_id.'_'.$u_id.'_'.$user_id.'_'.$faculty_id;
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
$sql="select * from  ams_entrance_user  where ent_id=$ent_id and u_id=$u_id and  user_id=$user_id  and directapply_faculty_id=$faculty_id";
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$datars = $this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$datars,300);
#########################	
 

$resultdata='Insert Done ID.'.$insert_id;
$status='1';
$result_data = array('result' => $resultdata,
	                'data' =>null,
	                'status' => $status,
	                );
}else{
	   	 $resultdata='Error Insert';
	   	 $status='0';
$result_data = array('result' => $resultdata,
	                'data' =>null,
	                'status' => $status,
	                );
}
#echo'<pre> $result_data=>';print_r($result_data);echo'<pre>';
return $result_data;    
}
public function updatedatatb($data,$table,$id,$filddb){
		/*
		 echo '<pre> $table=>';print_r($table);   
		 echo '<pre> $filddb=>';print_r($filddb);  
		 echo '<pre> $id=>';print_r($id); 
		 echo '<pre> $data=>';print_r($data); echo '</pre>'; Die(); 
		*/	
		   $result_data=$this->db->where($filddb,$id);
		   $result_data=$this->db->update($table,$data);  
		   $resultdata=$result_data;
		   #debug($result_data);die();
		   if($result_data=='1'){
			$result_data='Update Data Done';
			$status='1';
			$result_data = array(
				  	'id' => $id,
	                'result' => $resultdata,
	                'status' => $status,
	                );
		   }else{
			$resultdata='Update Data Not Done';
			$status='0';
			$result_data = array(
				  	'id' => $id,
	                'result' => $resultdata,
	                'status' => $status,
	                );
		   }
		   return $result_data;    
}
public function detedatatb($table,$filddb,$id){
		    $this->db->where($filddb, $id);
  			$result_data=$this->db-> delete($table);
		   #debug($result_data);die();
		   if($result_data=='1'){
			      $status='1';
				  $data = array(
				  	'id' => $id,
	                'mesage' => 'Delete id '.$id.' successful..',
	                'status' => $status,
	                );
	                $result_data=$data;
		   }else{
			      $status='0';
				  $data = array(
				  	'id' => $id,
	                'mesage' => 'Delete id '.$id.' unsuccessful..',
	                'status' => $status,
	                );
	                $result_data=$data;
		   }
		   return $result_data;    

								            
}
public function detedatatb2($table,$filddb1,$id1,$filddb2,$id2,$filddb3,$id3){
		    $this->db->where($filddb1, $id1);
		    $this->db->where($filddb2, $id2);
		    $this->db->where($filddb3, $id3);
#########################
/*
$filddb1='user_id';
$id1=$user_id;
$filddb2='u_id';
$id2=$u_id;
$filddb3='ent_id';
$id3=$ent_id;
*/
$ent_id=$id3;
$u_id=$id2;
$user_id=$id1;
$cache_key="get_ams_entrance_user_by_ent_id_u_id_user_id_".$ent_id.'_'.$u_id.'_'.$user_id;
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
$this->tppymemcached->delete($cache_key_encrypt);
#########################
  			$result_data=$this->db-> delete($table);
		   #debug($result_data);die();
		   if($result_data=='1'){
			      $status='1';
				  $data = array(
	                'mesage' =>'Delete '.$filddb1.'=>'.$id1.' and  '.$filddb2.'=>'.$id2.' and '.$filddb3.'=> '.$id3.' successful..',
	                'status' => $status,
	                );
	                $result_data=$data;
		   }else{
			      $status='0';
				  $data = array(
	                'mesage' =>'Delete '.$filddb1.'=>'.$id1.' and  '.$filddb2.'=>'.$id2.' and '.$filddb3.'=> '.$id3.' unsuccessful..',
	                'status' => $status,
	                );
	                $result_data=$data;
		   }
		   return $result_data;    

								            
}
public function deletedatatb2($table,$filddb1,$id1,$filddb2,$id2,$filddb3,$id3){
		    $this->db->where($filddb1, $id1);
		    $this->db->where($filddb2, $id2);
		    $this->db->where($filddb3, $id3);
#########################
/*
$filddb1='user_id';
$id1=$user_id;
$filddb2='u_id';
$id2=$u_id;
$filddb3='ent_id';
$id3=$ent_id;
*/
$ent_id=$id3;
$u_id=$id2;
$user_id=$id1;
$cache_key="get_ams_entrance_user_by_ent_id_u_id_user_id_".$ent_id.'_'.$u_id.'_'.$user_id;
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
$this->tppymemcached->delete($cache_key_encrypt);
#########################
  			$result_data=$this->db-> delete($table);
		   #debug($result_data);die();
		   if($result_data=='1'){
			      $status='1';
				  $data = array(
	                'mesage' =>'Delete '.$filddb1.'=>'.$id1.' and  '.$filddb2.'=>'.$id2.' and '.$filddb3.'=> '.$id3.' successful..',
	                'status' => $status,
	                );
	                $result_data=$data;
		   }else{
			      $status='0';
				  $data = array(
	                'mesage' =>'Delete '.$filddb1.'=>'.$id1.' and  '.$filddb2.'=>'.$id2.' and '.$filddb3.'=> '.$id3.' unsuccessful..',
	                'status' => $status,
	                );
	                $result_data=$data;
		   }
		   return $result_data;    

								            
}
public function get_ams_entrance_set_user_active($user_id,$set,$delekey){
$sql="select map.u_group_id,ent.*,entuser.user_id
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_group_name from  ams_university_group where u_group_id=map.u_group_id)as  group_name 
from  ams_entrance as ent
inner join  ams_entrance_user as entuser on entuser.ent_id=ent.ent_id and entuser.u_id=ent.u_id 
inner join  ams_university_group_map as map on map.u_id=ent.u_id
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=2560 and entuser.user_id=$user_id and  ent.u_id in (select u.u_id from  ams_university as u where u.u_parent_id in 
(select u.u_id from  ams_university as u where ent.u_id in($set)))
group by ent.u_id order by  map.u_group_id asc";
$cache_key="get_ams_entrance_set_user_active_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$datars=$this->tppymemcached->get($cache_key_encrypt);
if(!$datars) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$datars = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$datars,3600);
}
//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';
//echo '<hr><pre>datars=>'; print_r($datars); echo '</pre>';die();
$data=array('sql'=>$sql,'data'=>$datars,);
return $data;
}
public function get_ams_entrance_year_user_id_set($year,$user_id,$set,$delekey){
$sql="select entuser.*,ent.major_remark
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
from  ams_entrance_user as entuser   
inner join  ams_entrance as ent on entuser.u_id=ent.u_id and entuser.ent_id=ent.ent_id  
inner join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year and entuser.user_id=$user_id and entuser.u_id in($set)group by  university_id order by  ent.u_id asc";
$cache_key="get_ams_entrance_year_user_id_set_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$datars=$this->tppymemcached->get($cache_key_encrypt);
if(!$datars) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$datars = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$datars,3600);
}
//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';
//echo '<hr><pre>datars=>'; print_r($datars); echo '</pre>';die();
$data=array('sql'=>$sql,'data'=>$datars,);
return $data;
}
public function get_ams_entrance_year_user_id_set2($year,$user_id,$set,$delekey){
$sql="select entuser.*,ent.major_remark
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
from  ams_entrance_user as entuser   
inner join  ams_entrance as ent on entuser.u_id=ent.u_id and entuser.ent_id=ent.ent_id  
inner join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year and entuser.user_id=$user_id and entuser.u_id in($set) group by  university_id order by  ent.u_id asc";
$cache_key="get_ams_entrance_year_user_id_set2_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$datars=$this->tppymemcached->get($cache_key_encrypt);
if(!$datars) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$datars = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$datars,3600);
}
//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';
//echo '<hr><pre>datars=>'; print_r($datars); echo '</pre>';die();
$data=array('sql'=>$sql,'data'=>$datars,);
return $data;
}
public function get_ams_entrance_year_set_user_active_entuser($year,$user_id,$set,$delekey){
$sql="select map.u_group_id,ent.*,entuser.user_id
,(select score_min from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_min
,(select score_max from  ams_entrance_score_history  as h where h.ent_id=ent.ent_id and h.year=ent.year_config)as score_max
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,(select u_group_name from  ams_university_group where u_group_id=map.u_group_id)as  group_name 
from  ams_entrance as ent
inner join  ams_entrance_user as entuser on entuser.ent_id=ent.ent_id and entuser.u_id=ent.u_id 
inner join  ams_university_group_map as map on map.u_id=ent.u_id
left join  ams_university as un on un.u_id=ent.u_id
where ent.year_config=$year and entuser.user_id=$user_id and  ent.u_id in (select u.u_id from  ams_university as u where u.u_parent_id in 
(select u.u_id from  ams_university as u where ent.u_id in($set)))
group by ent.u_id order by  map.u_group_id asc";
$cache_key="get_ams_entrance_year_set_user_active_entuser_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
$datars=$this->tppymemcached->get($cache_key_encrypt);
if(!$datars) {
#####################
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$datars = $this->db->query($sql)->result_array();
#####################
$this->tppymemcached->set($cache_key_encrypt,$datars,3600);
}
//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';
//echo '<hr><pre>datars=>'; print_r($datars); echo '</pre>';die();
$data=array('sql'=>$sql,'data'=>$datars,);
return $data;
}
###admission
public function get_ams_university_entrance_score_dataset_year($dataset,$year,$delekey){
$sql1="select (select u_id from  ams_university where u_id=uu.u_parent_id)as faculty_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=uu.u_parent_id)as faculty_name
,uu.u_id as branch_id,uu.u_name as branch_name,ent.*,score.score_min,score.score_max from  ams_university as uu 
inner join ams_entrance as ent  on ent.u_id=uu.u_id
inner join ams_entrance_score_history as score  on score.ent_id=ent.ent_id  and score.year=ent.year_config
where  uu.u_id in ($dataset)  and ent.year_config=$year order by  uu.u_parent_id,uu.u_id asc";

$sql="select (select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,(select u_id from  ams_university where u_id=uu.u_parent_id)as faculty_id 
,(select u_name from  ams_university where u_id=uu.u_parent_id)as faculty_name
,uu.u_id as branch_id,uu.u_name as branch_name,ent.*,score.score_min,score.score_max from  ams_university as uu 
inner join ams_entrance as ent  on ent.u_id=uu.u_id
inner join ams_entrance_score_history as score  on score.ent_id=ent.ent_id  and score.year=ent.year_config
where  uu.u_id in ($dataset)  and ent.year_config=$year  group by university_id order by  uu.u_parent_id,uu.u_id asc";


$cache_key="get_ams_university_entrance_score_dataset_year_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_ams_university_entrance_score_dataset_year_set_user_id($branch_id,$year,$instatus,$user_id,$amss_entset,$delekey){
$sql1="select (select u_id from  ams_university where u_id=uu.u_parent_id)as faculty_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=uu.u_parent_id)as faculty_name
,uu.u_id as branch_id,uu.u_name as branch_name,ent.*,score.score_min,score.score_max from  ams_university as uu 
inner join ams_entrance as ent  on ent.u_id=uu.u_id
inner join ams_entrance_score_history as score  on score.ent_id=ent.ent_id  and score.year=ent.year_config";
if($instatus==1){
$sql2="
inner join  ams_entrance_user as huser on huser.u_id!=ent.u_id and huser.ent_id!=ent.ent_id
where uu.u_id in ($branch_id)  and ent.year_config=$year and huser.user_id=$user_id and ent.ent_id not in ($amss_entset)
group by ent.ent_id order by  uu.u_parent_id,uu.u_id asc";
}elseif($instatus==2){
$sql2="
inner join  ams_entrance_user as huser on huser.u_id=ent.u_id and huser.ent_id=ent.ent_id
where uu.u_id in ($branch_id)  and ent.year_config=$year and huser.user_id=$user_id
order by  uu.u_parent_id,uu.u_id asc";
}else{
$sql2="
where uu.u_id in ($branch_id)  and ent.year_config=$year order by  uu.u_parent_id,uu.u_id asc";
}
$sql=$sql1.$sql2;
//echo'<pre> sql=>';print_r($sql);echo'<pre> <hr>'; die();

$cache_key="get_ams_university_entrance_score_dataset_year_set_user_id_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_ams_entrance_group_dirapply_year_branch_id_set_user_id($year,$branch_id_set,$user_id,$delekey){
$sql="select ent.ent_id
from  ams_university as uu 
inner join ams_entrance as ent  on ent.u_id=uu.u_id
inner join ams_entrance_score_history as score  on score.ent_id=ent.ent_id  and score.year=ent.year_config
inner join  ams_entrance_user as huser on huser.u_id=ent.u_id and huser.ent_id=ent.ent_id
where  ent.year_config=$year and  uu.u_id in ($branch_id_set)  and huser.user_id=$user_id
order by  uu.u_parent_id,uu.u_id asc";
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_entrance_group_dirapply_year_branch_id_set_user_id_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,60);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_university_entrance_score_dataset_year_in_user($dataset,$year,$ins,$user_id,$entset,$delekey){
$sql1="select (select u_id from  ams_university where u_id=uu.u_parent_id)as faculty_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,(select u_name from  ams_university where u_id=uu.u_parent_id)as faculty_name
,uu.u_id as branch_id,uu.u_name as branch_name,ent.*,score.score_min,score.score_max from  ams_university as uu 
inner join ams_entrance as ent  on ent.u_id=uu.u_id
inner join ams_entrance_score_history as score  on score.ent_id=ent.ent_id  and score.year=ent.year_config
";
if($ins==1){
$sql2="
inner join  ams_entrance_user as huser on huser.u_id!=ent.u_id and huser.ent_id!=ent.ent_id
where  uu.u_id in ($dataset)  and ent.year_config=$year and huser.user_id=$user_id and ent.ent_id not in ($entset)
group by ent.ent_id order by  uu.u_parent_id,uu.u_id asc ";
}elseif($ins==2){
$sql2="
inner join  ams_entrance_user as huser on huser.u_id=ent.u_id and huser.ent_id=ent.ent_id
where  uu.u_id in ($dataset)  and ent.year_config=$year and huser.user_id=$user_id
order by  uu.u_parent_id,uu.u_id asc";
}else{
$sql2="
where  uu.u_id in ($dataset)  and ent.year_config=$year 
order by  uu.u_parent_id,uu.u_id asc";
}
$sql=$sql1.$sql2;
#echo'<pre> sql>';print_r($sql);echo'<pre> <hr>'; die();
$cache_key="get_ams_university_entrance_score_dataset_year_in_user_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,60);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_ams_entrance_group_year_branch_id_set_alls($year,$branch_id_set,$delekey){
$sql="select ent.ent_id from ams_university as uu 
inner join ams_entrance as ent on ent.u_id=uu.u_id
where uu.u_id in ($branch_id_set)  and ent.year_config=$year 
order by uu.u_parent_id,uu.u_id asc";
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_entrance_group_year_branch_id_set_alls_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,60);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
###direct apply
public function get_ams_entrance_user_by_ent_id_u_id_user_id_top($ent_id,$u_id,$user_id,$faculty_id,$delekey){
if($faculty_id==''){$faculty_id=0;}
$sql="select * from  ams_entrance_user where ent_id=$ent_id and u_id=$u_id and user_id=$user_id and directapply_faculty_id=$faculty_id";
/*
$query=$this->db->query($sql);
$datars=$query->result_array();
$num_rows=(int)$query->num_rows();
*/

###########cache###############
$cache_key="get_ams_entrance_user_by_ent_id_u_id_user_id_top_".$ent_id.'_'.$u_id.'_'.$user_id.'_'.$faculty_id;
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$datars=$this->tppymemcached->get($cache_key_encrypt);
if(!$datars) {
$query=$this->db->query($sql);
$datars=$query->result_array();
$num_rows=(int)$query->num_rows();
$this->tppymemcached->set($cache_key_encrypt,$datars,30);
$sql1='sql_query_top->'.$sql;
} 
###########cache###############

 if($datars){$sql1='sql_cache->'.$sql;}
if($num_rows!==0){$status=1;}else{ $status=0;}
$data=array('sql'=>$sql1,'data'=>$datars,'status'=>$status);
 #echo '<hr><pre> data=>'; print_r($data); echo '</pre>';die();
return $data;
}
public function get_ams_entrance_dirapply_year_set($year,$set,$university_id,$delekey){
$sql="select distinct dirapply.news_id,dirapply.news_title,ent.u_id,ent.year_config as yearnews,ent.major_remark
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,dir.major_grade,dir.major_receivers,dirapply.ref_university_id,dirapply.*,dirapply.sonsart_year as dirapply_year
from   ams_entrance as ent
inner join  ams_university as un on un.u_id=ent.u_id
inner join  ams_news_directapply_university as dir on dir.u_id=(select u_id from  ams_university where  u_id=un.u_id)
inner join  ams_news_directapply as dirapply on dir.news_id=dirapply.news_id and dirapply.sonsart_year=ent.year_config
where ent.year_config=$year and ent.u_id in($set)and dirapply.ref_university_id=$university_id group by dir.news_id order by  ent.ent_id asc";
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_entrance_dirapply_year_set_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_entrance_dirapply_year_branch_id_user_id($year,$branch_id,$u_id,$user_id,$set,$ins,$delekey){
$sql1="select distinct dirapply.news_id,dirapply.news_title,ent.u_id,ent.year_config as yearnews,ent.major_remark
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,dir.major_grade,dir.major_receivers,dirapply.ref_university_id,dirapply.*,dirapply.sonsart_year as dirapply_year
,dir.major_grade,dir.major_gatpat,dir.major_gnet,dir.major_seven
from ams_entrance as ent
inner join  ams_university as un on un.u_id=ent.u_id
inner join  ams_news_directapply_university as dir on dir.u_id=(select u_id from  ams_university where  u_id=un.u_id)
inner join  ams_news_directapply as dirapply on dir.news_id=dirapply.news_id and dirapply.sonsart_year=ent.year_config";
if($ins==1){
$sql2=" 
inner join  ams_entrance_user as huser on huser.u_id!=dirapply.news_id
where ent.year_config=$year and ent.u_id in($branch_id)and dirapply.news_id not in($set)and dirapply.ref_university_id=$u_id and huser.user_id=$user_id
group by dir.news_id order by ent.ent_id asc";
}elseif($ins==2){
$sql2="
inner join  ams_entrance_user as huser on huser.u_id=dirapply.news_id and huser.directapply_faculty_id=ent.u_id
where ent.year_config=$year and ent.u_id in($branch_id)and dirapply.news_id in($set)and dirapply.ref_university_id=$u_id and huser.user_id=$user_id
group by dir.news_id order by ent.ent_id asc";
}else{
$sql2="
where ent.year_config=$year and ent.u_id in($branch_id)and  dirapply.ref_university_id=$u_id
group by dir.news_id order by ent.ent_id asc";
}
$sql=$sql1.$sql2;
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_entrance_dirapply_year_branch_id_user_id_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,30);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_entrance_group_dirapply_year_branch_in_user_id($year,$branch_id,$u_id,$user_id,$delekey){
$sql="select dirapply.news_id,dirapply.news_title from  ams_entrance as ent
inner join ams_university as un on un.u_id=ent.u_id
inner join ams_news_directapply_university as dir on dir.u_id=ent.u_id 
inner join ams_news_directapply as dirapply on dir.news_id=dirapply.news_id and dirapply.sonsart_year=ent.year_config
inner join ams_entrance_user as huser on huser.u_id=dirapply.news_id
where ent.year_config=$year and dir.u_id in($branch_id)and dirapply.ref_university_id=$u_id and huser.user_id=$user_id  
group by dir.news_id order by  dir.news_id asc";
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_entrance_group_dirapply_year_branch_in_user_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,60);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
###Top Tab
public function get_ams_entrance_user_by_ams_university_form_user_id($user_id,$type,$delekey){
//if($type==''){$type=1;}
if($type==1){
// admission and directapply
$sql="select (select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))) AS thumbnail 
from  ams_entrance_user as huser 
inner join  ams_university as uu on uu.u_id=huser.u_id 
where  huser.user_id=$user_id group by university_id";
}elseif($type==2){
// admission
$sql="select (select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))) AS thumbnail 
from  ams_entrance_user as huser 
inner join  ams_university as uu on uu.u_id=huser.u_id and huser.ent_id!=0 
where  huser.user_id=$user_id group by university_id";
}elseif($type==3){
// directapply
$sql="select uu.u_id as university_id
,(select u_name from  ams_university where u_id=uu.u_id)as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=uu.u_id)) AS thumbnail 
from ams_entrance_user as huser 
inner join  ams_news_directapply_university as d on d.news_id=huser.u_id
inner join  ams_university as uu on uu.u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=d.u_id)) 
where huser.user_id=$user_id group by university_id";
}

#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_entrance_user_by_ams_university_form_user_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
#echo '<hr><pre>dataall=>'; print_r($dataall); echo '</pre>';die();
return $dataall;
}
public function get_ams_entrance_user_by_ams_university_form_user_id_union($user_id,$type,$delekey){
//if($type==''){$type=1;}
if($type==1){
// admission and directapply
$sql="select (select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))) AS thumbnail 
from  ams_entrance_user as huser 
inner join  ams_university as uu on uu.u_id=huser.u_id and huser.ent_id!=0 
UNION 
select uu.u_id as university_id
,(select u_name from  ams_university where u_id=uu.u_id)as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=uu.u_id)) AS thumbnail 
from ams_entrance_user as huser 
inner join  ams_news_directapply_university as d on d.news_id=huser.u_id
inner join  ams_university as uu on uu.u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from ams_university where u_id=d.u_id)) 
where  huser.user_id=$user_id group by university_id";
}elseif($type==2){
// admission
$sql="select (select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))) AS thumbnail 
from  ams_entrance_user as huser 
inner join  ams_university as uu on uu.u_id=huser.u_id and huser.ent_id!=0 
where  huser.user_id=$user_id group by university_id";
}elseif($type==3){
// directapply
$sql="select uu.u_id as university_id
,(select u_name from  ams_university where u_id=uu.u_id)as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=uu.u_id)) AS thumbnail 
from ams_entrance_user as huser 
inner join  ams_news_directapply_university as d on d.news_id=huser.u_id
inner join  ams_university as uu on uu.u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=d.u_id)) 
where huser.user_id=$user_id group by university_id";
}

#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_entrance_user_by_ams_university_form_user_id_union_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
#echo '<hr><pre>dataall=>'; print_r($dataall); echo '</pre>';die();
return $dataall;
}
public function get_ams_university_by_u_parentid($u_parent_id,$delekey){
$sql="select u1.u_id,u1.u_name from  ams_university as u1 where u1.u_parent_id=$u_parent_id order by u1.u_id asc";
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_university_by_u_parentid_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_university_by_u_set_all($set,$delekey){
$sql="select u.u_id,u.u_name from  ams_university as u where u.u_parent_id in($set) group by u.u_id order by u.u_id asc";
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_university_by_u_set_all_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
####admissiontrainer by admission ####
public function get_ams_university_by_u_set_id($set,$delekey){
$sql="select u.u_id,u.u_name from  ams_university as u  
inner join  ams_entrance_user as huser on huser.u_id=u.u_id or huser.directapply_faculty_id=u.u_id
where u.u_parent_id in($set) group by u.u_id order by u.u_id asc";
//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_university_by_u_set_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_faculty_up_form_branch_entrance_user_set_id($setid,$delekey){
$sql="select u.u_parent_id as faculty_id 
,(select u_name from  ams_university where u_id=u.u_parent_id)as faculty_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_parent_id))as university_name
from  ams_university as u 
inner join  ams_entrance_user as huser on huser.u_id=u.u_id 
where u.u_id in($setid) group by u.u_parent_id order by u.u_id asc";
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_faculty_up_form_branch_entrance_user_set_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);
}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_faculty_up_form_branchsetdata_admission_user($year,$branch_in,$user_id,$delekey){
$sql1="select ent.ent_id,u.u_parent_id as faculty_id 
,(select u_name from  ams_university where u_id=u.u_parent_id)as faculty_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_parent_id))as university_name
,ent.year_config as year
from  ams_university as u 
inner join  ams_entrance_user as huser on huser.u_id=u.u_id 
inner join  ams_entrance as ent on ent.u_id=huser.u_id or ent.ent_id=huser.ent_id
where u.u_id in($branch_in) and huser.user_id=$user_id and ent.year_config=$year
group by faculty_id order by u.u_id asc";

$sql2="select ent.ent_id,u.u_parent_id as faculty_id 
,(select u_name from  ams_university where u_id=u.u_parent_id)as faculty_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_parent_id))as university_name
,ent.year_config as year
from  ams_university as u 
inner join  ams_entrance_user as huser on huser.u_id=u.u_id 
inner join  ams_entrance as ent on ent.u_id=huser.u_id and ent.ent_id=huser.ent_id
where u.u_id in($branch_in) and huser.user_id=$user_id and ent.year_config=$year
group by faculty_id order by u.u_id asc";

$sql=$sql1;
#$sql=$sql2;
//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_faculty_up_form_branchsetdata_admission_user_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
####admissiontrainer by directapply ####
public function get_faculty_up_form_branchsetdata_directapply_user($year,$branch_in,$user_id,$delekey){
$sql="select major.news_id as news_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id))as faculty_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id)))as university_name
,dir.year as year
from v_directapply as dir
inner join ams_news_directapply_university as major on major.news_id=dir.content_id 
inner join ams_entrance_user as huser on huser.directapply_faculty_id=major.u_id
where dir.year=$year and dir.record_status=1  and major.u_id in($branch_in) and huser.user_id=$user_id
group by faculty_id order by dir.date_study_start asc";
//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_faculty_up_form_branchsetdata_directapply_user_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
################
####directapply####
public function get_directapply_year_news_id_branch_id($year,$news_id,$branch_id,$delekey){
$sql="select dir.content_id as news_id,dir.topic,major.major_receivers as receiver,dir.receiver_number as receiver_all,major.u_id as branch_id
,(select u_name from  ams_university where u_id=major.u_id)as branch_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id))as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id)))as university_name
,major.major_grade,major.major_gatpat,major.major_gnet,major.major_seven
from v_directapply as dir
inner join ams_news_directapply_university as major on major.news_id=dir.content_id 
where dir.year=$year and dir.record_status=1 and dir.content_id=$news_id and major.u_id=$branch_id
group by branch_id order by dir.date_study_start asc";
//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_directapply_year_news_id_branch_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_directapply_year_in_branch_id($year,$branch_in,$delekey){
$sql="select dir.content_id as news_id,dir.topic,major.major_receivers as receiver,dir.receiver_number as receiver_all,major.u_id as branch_id
,(select u_name from  ams_university where u_id=major.u_id)as branch_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id))as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=major.u_id)))as university_name
,major.major_grade,major.major_gatpat,major.major_gnet,major.major_seven
from v_directapply as dir
inner join ams_news_directapply_university as major on major.news_id=dir.content_id 
inner join ams_entrance_user as huser on huser.directapply_faculty_id=major.u_id
where dir.year=$year and dir.record_status=1  and major.u_id in($branch_in)
group by branch_id order by dir.date_study_start asc";
//echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_directapply_year_in_branch_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
####directapply####
public function get_ams_entrance_dirapply_year_set_user_id($year,$set,$user_id,$delekey){
/*
$sql="select Distinct dirapply.news_id,dirapply.news_title,ent.u_id,ent.year_config as yearnews,ent.major_remark
,(select u_id from  ams_university where  u_id=un.u_id)as branch_id
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,dir.major_grade,dir.major_receivers,dirapply.ref_university_id
from   ams_entrance as ent
inner join ams_entrance_user as users on users.u_id=ent.u_id 
inner join  ams_university as un on un.u_id=ent.u_id
inner join  ams_news_directapply_university as dir on dir.u_id=(select u_id from  ams_university where  u_id=un.u_id)
inner join  ams_news_directapply as dirapply on dir.news_id=dirapply.news_id
where users.u_id in($set) and ent.year_config=$year and users.user_id=$user_id group by dir.news_id order by dir.news_id asc";
*/
$sql="select Distinct dirapply.news_id,dirapply.ref_university_id as university_id,ent.year_config as yearnews,dirapply.news_title,ent.major_remark
,(select u_name from  ams_university where  u_id=un.u_id)as branch_name
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
,dir.major_grade,dir.major_receivers
from   ams_entrance as ent
inner join ams_entrance_user as users on users.u_id=ent.u_id 
inner join  ams_university as un on un.u_id=ent.u_id
inner join  ams_news_directapply_university as dir on dir.u_id=(select u_id from  ams_university where  u_id=un.u_id)
inner join  ams_news_directapply as dirapply on dir.news_id=dirapply.news_id
where users.u_id in($set) and ent.year_config=$year and users.user_id=$user_id group by dir.news_id order by dir.news_id asc";
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_entrance_dirapply_year_set_user_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_university_all_u_parent_ido($delekey){
$sql="select u_id,u_parent_id,u_name,concat('http://www.trueplookpanya.com/api/knowledgebase/content/',thumbnail) AS thumbnail,u_group_id from  ams_university where u_parent_id=0";
$cache_key="get_ams_university_all_u_parent_ido_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall; 
}
public function get_ams_entrance_user_innerjoin_ams_university_where_user_id($user_id,$delekey){
$sql="select entuser.*
,(select major_remark from  ams_entrance where ent_id=entuser.ent_id)as entrance_remark
,un.u_name as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name 
from  ams_entrance_user as entuser  inner join  ams_university as un on un.u_id=entuser.u_id 
where entuser.user_id=$user_id group by entuser.u_id order by  entuser.u_id asc";
$cache_key="get_ams_entrance_user_innerjoin_ams_university_where_user_id_".$sq;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall; 
}
public function get_faculty_set_branch_id($set_branch_id,$delekey){
$sql="select u_parent_id as faculty_id,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name  from  ams_university as un  where un.u_id in($set_branch_id)";
$cache_key="get_faculty_set_branch_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,60);
}
return $data; 
}
public function get_group_university_set_groupfaculty_id($set_groupfaculty_id,$delekey){
$sql="select (select u_id from  ams_university where u_id=un.u_parent_id)as university_id,(select u_name from  ams_university where u_id=un.u_parent_id)as university_name from  ams_university as un  where un.u_id in($set_groupfaculty_id) group by university_id";
$cache_key="get_group_university_set_groupfaculty_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,60);
}
return $data; 
}
public function get_ams_university_group_map_set_group($setgroup,$delekey){
$sql="select un.u_id,un.u_name
from ams_university_group_map as map 
inner join  ams_university as un on un.u_id=(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id))))
where map.u_id in ($setgroup) group by un.u_id order by idx asc";
$cache_key="get_ams_university_group_map_set_group_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,60);
}
return $data; 
}
public function get_ams_entrance_user_university_user_id_where($user_id,$where,$delekey){
$sql="select entuser.*
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))) AS thumbnail 
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name 
from  ams_entrance_user as entuser  inner join  ams_university as un on un.u_id=entuser.u_id 
where entuser.user_id=$user_id  group by university_id order by  entuser.u_id asc";
$cache_key="get_ams_entrance_user_university_user_id_where_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall; 
}
public function get_ams_university_u_parent_id_where($u_parent_id,$delekey){
$sql="select * from  ams_university where u_parent_id=$u_parent_id order by  u_id asc";
$cache_key="get_ams_university_u_parent_id_where_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
#20180328
public function get_ams_university_u_parent_id_where_u_group_id($u_parent_id,$u_group_id,$delekey){
$sql="select ent.ent_id,u.*
from  ams_university as u 
inner join ams_entrance as ent on ent.u_id=u.u_id
inner join ams_university_group_map as map on map.u_id=ent.u_id
inner join ams_university_group as groups on map.u_group_id=groups.u_group_id and groups.u_group_type=4
where u.u_parent_id=$u_parent_id  and groups.u_group_id=$u_group_id
group by u.u_id order by  u.u_id asc";
$cache_key="get_ams_university_u_parent_id_where_u_group_id_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_users_entrance_score_history_set($set,$year,$delekey){
$sql="select (select u_id from  ams_university where u_id=uu.u_parent_id)as faculty_id 
,(select u_name from  ams_university where u_id=uu.u_parent_id)as faculty_name,uu.u_id as branch_id,uu.u_name as branch_name,ent.*,score.score_min,score.score_max from  ams_university as uu 
inner join ams_entrance_user as users  on uu.u_id=users.u_id
inner join ams_entrance as ent  on ent.ent_id=users.ent_id 
inner join ams_entrance_score_history as score  on score.ent_id=ent.ent_id  and score.year=ent.year_config
where  uu.u_id in ($set)  and ent.year_config=$year  order by  uu.u_parent_id,uu.u_id asc";
$cache_key="get_ams_users_entrance_score_history_set_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,600);
}
$dataall=array('sql'=>$sql,'data'=>$data);
return $dataall; 
}
public function get_ams_all_entrance_score_history_set($set,$year,$delekey){
$sql="select (select u_parent_id from  ams_university where u_id=uu.u_parent_id)as university_id 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name 
,(select u_id from  ams_university where u_id=uu.u_parent_id)as faculty_id 
,(select u_name from  ams_university where u_id=uu.u_parent_id)as faculty_name
,uu.u_id as branch_id,uu.u_name as branch_name
,ent.*,score.score_min,score.score_max from  ams_university as uu 
inner join ams_entrance as ent on ent.u_id=uu.u_id
inner join ams_entrance_score_history as score on score.ent_id=ent.ent_id  and score.year=ent.year_config
where  uu.u_id in ($set) and ent.year_config=$year order by  uu.u_parent_id,uu.u_id asc";
$cache_key="get_ams_all_entrance_score_history_set_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall; 
}
public function get_ams_university_ams_university_year($year,$delekey){
$sql="select un.u_parent_id,un.u_group_id,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name ,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))) AS thumbnail from  ams_entrance as ent  inner join  ams_university as un on un.u_id=ent.u_id where ent.year_config=$year group by university_id order by university_id asc";
$cache_key="get_ams_university_ams_university_year_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;

}
public function get_ams_university_ams_university_ams_news_directapply_university($datasetu_id,$delekey){
$sql="select (select news_title from  ams_news_directapply where news_id=ud.news_id)as news_title
,ud.major_receivers as receivers
,ud.major_grade as gpax
,ud.major_gatpat AS gatpat
,ud.major_gnet AS req_gnet
,ud.major_portfolio AS req_portfolio
,ud.major_interview AS req_interview
,ud.major_matches AS req_matches
,ud.major_property AS req_property
,ud.major_receivers AS receiver_number
,(select u_name from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ud.u_id)))as university
,(select u_name from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ud.u_id))as fac_name
,(select u_name from  ams_university where  u_id=ud.u_id)as u_name
,(select u_parent_id from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ud.u_id))as university_id
,(select u_id from  ams_university where  u_id=ud.u_id)as faculty_id
,(select u_parent_id from  ams_university where  u_id=ud.u_id)as branch_id
from ams_news_directapply_university as ud
where ud.major_receivers!='' and ud.u_id in (select u_id from ams_university_group_map 
where ud.u_id in ($datasetu_id)) group by branch_id";
$cache_key="get_ams_university_ams_university_ams_news_directapply_university_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_university_entrance_score_dataset_year_by_user_id($dataset,$year,$user_id,$delekey){
$sql="select (select u_id from  ams_university where u_id=uu.u_parent_id)as faculty_id 
,(select u_name from  ams_university where u_id=uu.u_parent_id)as faculty_name,uu.u_id as branch_id
,uu.u_name as branch_name,ent.*,score.score_min,score.score_max from  ams_university as uu 
inner join ams_entrance as ent  on ent.u_id=uu.u_id
inner join ams_entrance_score_history as score  on score.ent_id=ent.ent_id  and score.year=ent.year_config
inner join ams_entrance_user as users on users.u_id=ent.u_id 
where  uu.u_id in ($dataset)  and ent.year_config=$year and users.user_id=$user_id 
order by  uu.u_parent_id,uu.u_id asc";
$cache_key="get_ams_university_entrance_score_dataset_year_by_user_id_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_groupby_university_map_join_ams_entrance($dataset,$delekey){
$sql="select(select u_group_name from ams_university_group where u_group_id=m.u_group_id)as group_name
,(select u_name from ams_university where u_id=m.u_id)as b_name
,un.u_name as faculty_name,un.u_id as faculty_id,m.u_id,m.u_id as branch_id ,m.u_group_id 
from ams_university_group_map as m 
inner join ams_entrance as ent on ent.u_id=m.u_id 
inner join ams_university as un on un.u_id=(select u_parent_id from  ams_university where u_id=(select u_id from  ams_university where u_id=m.u_id)) 
where m.u_group_id in($dataset) 
group by m.u_group_id order by u_id asc";
$cache_key="get_groupby_university_map_join_ams_entrance_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_groupby_university_map_join_ams_entrance_user($dataset,$user_id,$delekey){
$sql="select(select u_group_name from ams_university_group where u_group_id=m.u_group_id)as group_name
,(select u_name from ams_university where u_id=m.u_id)as b_name
,un.u_name as faculty_name,un.u_id as faculty_id,m.u_id,m.u_id as branch_id ,m.u_group_id 
from ams_university_group_map as m 
inner join ams_entrance as ent on ent.u_id=m.u_id 
inner join ams_entrance_user as users on users.u_id=m.u_id 
inner join ams_university as un on un.u_id=(select u_parent_id from  ams_university where u_id=(select u_id from  ams_university where u_id=m.u_id)) 
where users.user_id=$user_id and m.u_group_id in($dataset) 
group by m.u_group_id order by u_id asc";
$cache_key="get_groupby_university_map_join_ams_entrance_user_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_groupby_university_map_join_ams_entrance_byuid($dataset,$delekey){
$sql="select m.u_id,m.u_id as branch_id ,m.u_group_id ,un.u_id as university_id,un.u_name as university,concat('http://static.trueplookpanya.com/trueplookpanya/',un.thumbnail) AS thumbnail 
from ams_university_group_map as m 
inner join ams_entrance as ent on ent.u_id=m.u_id
inner join ams_university as un on un.u_id=(select u_parent_id from  ams_university where u_id=(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))
where m.u_group_id in($dataset) group by university_id order by un.u_id asc";
/*
$sql1="select m.u_id,m.u_id as branch_id,m.u_group_id,un.u_id as university_id
,(select u_group_name from ams_university_group where u_group_id=m.u_group_id)as group_name
,(select u_name from ams_university where u_id=m.u_id)as branch_name,un.u_name as university,concat('http://static.trueplookpanya.com/trueplookpanya/',un.thumbnail) AS thumbnail 
from ams_university_group_map as m 
inner join ams_entrance as ent on ent.u_id=m.u_id
inner join ams_university as un on un.u_id=(select u_parent_id from  ams_university where u_id=(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))
where m.u_group_id in($dataset) 
group by university_id order by un.u_id asc";
*/
$cache_key="get_groupby_university_map_join_ams_entrance_byuid_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_groupby_university_map_join_ams_entrance_byuid_user_id($dataset,$userid,$delekey){
$sql="select m.u_id,m.u_id as branch_id ,m.u_group_id ,un.u_id as university_id,un.u_name as university
,concat('http://static.trueplookpanya.com/trueplookpanya/',un.thumbnail) AS thumbnail 
from ams_university_group_map as m 
inner join ams_entrance as ent on ent.u_id=m.u_id
inner join ams_entrance_user as users on users.u_id=m.u_id 
inner join ams_university as un on un.u_id=(select u_parent_id from  ams_university where u_id=(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))
where users.user_id=$userid and m.u_group_id in($dataset) group by university_id order by un.u_id asc";
$cache_key="get_groupby_university_map_join_ams_entrance_byuid_user_id_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_groupby_university_map_join_ams_entrance_user_id($dataset,$user_id,$delekey){
$sql="select(select u_group_name from ams_university_group where u_group_id=m.u_group_id)as group_name
,(select u_name from ams_university where u_id=m.u_id)as b_name
,un.u_name as faculty_name,un.u_id as faculty_id,m.u_id,m.u_id as branch_id ,m.u_group_id 
from ams_university_group_map as m 
inner join ams_entrance as ent on ent.u_id=m.u_id 
inner join ams_entrance_user as users on users.u_id=m.u_id 
inner join ams_university as un on un.u_id=(select u_parent_id from  ams_university where u_id=(select u_id from  ams_university where u_id=m.u_id)) 
where m.u_group_id in($dataset) and users.user_id=$user_id
group by m.u_group_id order by u_id asc";
$cache_key="get_groupby_university_map_join_ams_entrance_user_id_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data_'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'cache_data_'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_ams_entrance_major_code($major_code,$delekey){
$sql="select ams.major_remark,ams.receive_amount,ams.special_remark,ams.receive_amount_sharecode as sharecode,ams.u_id as branch_id
,(select u_name from  ams_university where  u_id=ams.u_id)as branch_name
,(select u_name from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ams.u_id))as faculty_name
,(select u_name from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=ams.u_id)))as university_name
from  ams_entrance as ams where ams.major_code=$major_code";
$cache_key="get_ams_entrance_major_code_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data_'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'cache_data_'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_ams_entrance_sharecode($major_code,$delekey){
$sql="select ams.major_remark,ams.receive_amount,ams.receive_amount_sharecode as sharecode
from  ams_entrance as ams where ams.major_code=$major_code or ams.receive_amount_sharecode=$major_code";
$cache_key="get_ams_entrance_sharecode_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data_'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'cache_data_'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_entrance_branch_group_map_year($groupid,$year,$delekey){
$sql="select m.u_id as branch_id ,groups.u_group_name as group_name,unn.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id))as faculty_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_id
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=unn.u_parent_id))) AS thumbnail 
from ams_university_group_map as m 
inner join ams_university_group as groups on groups.u_group_id=m.u_group_id
inner join ams_university as unn on unn.u_id=m.u_id
inner join ams_entrance as ent on ent.u_id=m.u_id
where ent.year_config=$year and m.u_group_id in($groupid)
group by university_id order by m.u_id asc";
$cache_key="get_entrance_branch_group_map_year_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data_'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'cache_data_'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
########################
public function get_grouptype4_entrance_university_groupid_year($groupid,$year,$delekey){
$sql1="select map.*,un.u_parent_id,un.u_id as university_id,un.u_name as university_name
from ams_university_group_map as map 
inner join ams_university as un on un.u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id))
inner join ams_entrance as ent on ent.u_id=map.u_id
where map.u_group_id in ($groupid) and ent.year_config=$year
group by un.u_id order by map.idx asc";

$sql="select map.*,groups.u_group_name,groups.short_description
,(select u_name from  ams_university where u_id=map.u_id)as branch_name
,un.u_parent_id,un.u_id as university_id,un.u_name as university_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as faculty_name
,groups.u_group_name,groups.short_description
,concat('http://static.trueplookpanya.com/trueplookpanya/',un.thumbnail) AS thumbnail
,ent.major_remark,ent.ent_id,ent.u_id as ent_u_id
from ams_university_group_map as map 
inner join ams_university as un on un.u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id))
inner join ams_entrance as ent on ent.u_id=map.u_id
inner join ams_university_group as groups on groups.u_group_id=map.u_group_id
where map.u_group_id in ($groupid) and ent.year_config=$year group by un.u_id order by un.u_id asc";
$cache_key="get_grouptype4_entrance_university_groupid_year_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data_'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'cache_data_'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
 #echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
public function get_grouptype4_entrance_branch_groupid_year($groupid,$year,$delekey){
$sql="select map.*,un.u_id as branch_id,un.u_name as branch_name
,(select u_id from  ams_university where u_id=un.u_parent_id)as faculty_id
,(select u_name from  ams_university where u_id=un.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_parent_id))as university_name
from ams_university_group_map as map 
inner join ams_university as un on un.u_parent_id=(select u_parent_id from  ams_university where u_id=map.u_id)
inner join ams_entrance as ent on ent.u_id=map.u_id
where map.u_group_id in ($groupid) and ent.year_config=$year 
group by university_id order by map.idx asc";
$cache_key="get_grouptype4_entrance_branch_groupid_year_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data_'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'cache_data_'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
//echo'<pre>cache $dataall=>';print_r($dataall);echo'<pre> <hr>'; die();
return $dataall;
}
########################
#API TOP 
public function get_count_entrance_year_user($year,$user_id,$delekey){
$sql="select count(huser.u_id)as count_entrance  from  ams_entrance_user as huser 
inner join  ams_entrance as ent on ent.ent_id=huser.ent_id
where huser.directapply_faculty_id=0 and ent.year_config=$year and huser.user_id=$user_id ";
$cache_key="get_count_entrance_year_user_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,30);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_count_directapply_year_user($year,$user_id,$delekey){
$sql="select count(huser.u_id) as count_directapply from  ams_news_directapply as directapply 
inner join  ams_entrance_user as huser on huser.u_id=directapply.news_id 
where huser.directapply_faculty_id!=0 and directapply.sonsart_year=$year and huser.user_id=$user_id";
$cache_key="get_count_directapply_year_user_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_branch_year_user($year,$user_id,$delekey){
$sql="select uu.u_id as branch_id
,(select u_name from  ams_university where u_id=huser.u_id)as branch_name
,huser.ent_id,huser.directapply_faculty_id,huser.year
from  ams_entrance_user as huser 
inner join  ams_university as uu on uu.u_id=(select u_id from  ams_university where u_id=huser.u_id) 
inner join  ams_entrance as ent on ent.ent_id=huser.ent_id and huser.directapply_faculty_id=0 and ent.year_config=huser.year
union
select uu.u_id as branch_id
,(select u_name from  ams_university where u_id=uu.u_id)as branch_name
,huser.ent_id,huser.directapply_faculty_id,huser.year
from ams_entrance_user as huser 
inner join  ams_news_directapply_university as d on d.news_id=huser.u_id and d.u_id=huser.directapply_faculty_id
inner join  ams_university as uu on uu.u_id=huser.directapply_faculty_id 
inner join  ams_entrance as ent on ent.u_id=directapply_faculty_id and ent.year_config=huser.year
where huser.year=$year and huser.user_id=$user_id  group by branch_id";
$cache_key="get_branch_year_user_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_branch_group_year_user($year,$user_id,$delekey){
$sql="select uu.u_id as branch_id,(select u_name from  ams_university where u_id=huser.u_id)as branch_name,huser.year
from  ams_entrance_user as huser 
inner join  ams_university as uu on uu.u_id=(select u_id from  ams_university where u_id=huser.u_id) 
inner join  ams_entrance as ent on ent.ent_id=huser.ent_id and huser.directapply_faculty_id=0 and ent.year_config=huser.year
union select huser.directapply_faculty_id as branch_id,(select u_name from  ams_university where u_id=uu.u_id)as branch_name,huser.year
from ams_entrance_user as huser 
inner join  ams_news_directapply_university as d on d.news_id=huser.u_id and d.u_id=huser.directapply_faculty_id
inner join  ams_university as uu on uu.u_id=huser.directapply_faculty_id 
inner join  ams_entrance as ent on ent.u_id=directapply_faculty_id and ent.year_config=huser.year
where huser.year=$year and huser.user_id=$user_id  group by branch_id";
$cache_key="get_branch_group_year_user_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_branch_group_type4_map($branch_id,$delekey){
$sql="select gmap.u_group_id,m2m.u_group_id_source,groups.u_group_name,groups.record_status
,concat('http://static.trueplookpanya.com',groups.thumbnail) AS thumbnail 
from ams_university_group_map as gmap 
inner join  ams_university_group_m2m as m2m on m2m.u_group_id_destination=gmap.u_id
inner join  ams_university_group as groups on groups.u_group_id=gmap.u_group_id
where  gmap.u_id in($branch_id) and groups.u_group_type=4";
$cache_key="get_branch_group_type4_map_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_branch_faculty_university_branch_id_in($branch_id_in,$delekey){
$sql="select u.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_id))as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_id)))as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_id)))) AS thumbnail 
from ams_university as u 
where  u.u_id in($branch_id_in)";
$cache_key="get_branch_faculty_university_branch_id_in_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_branch_faculty_university_branch_id_in_ent_year($branch_id_in,$year,$delekey){
$sql="select u.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_id))as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_id)))as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=u.u_id)))) AS thumbnail 
from ams_university as u 
inner join  ams_entrance as ent on ent.u_id=u.u_id
where  u.u_id in($branch_id_in)  and ent.year_config=$year group by university_id order by university_id asc";
$cache_key="get_branch_faculty_university_branch_id_in_ent_year_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
## grup by  ams_entrance and ams_university year
public function get_ams_university_entrance_group_map($u_group_id,$year,$delekey){
$sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))) AS thumbnail
,g.u_group_name as group_name
,g.short_description as group_description
,g.u_group_id	as group_id
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
from ams_university_group  as g
inner join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
inner join  ams_university  as amsu on amsu.u_id=m.u_id
inner join  ams_entrance as ent on ent.u_id=amsu.u_id
where  g.u_group_id=$u_group_id and ent.year_config=$year  group by university_id order by university_id asc 
";
$cache_key="get_ams_university_entrance_group_map_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_branch_group_type4_map_year_by_user($year,$userid,$delekey){
$sql="select groups.u_group_id,uu.u_id as branch_id
,groups.u_group_name as branch_name
,huser.year 
,groups.u_group_type
,concat('http://static.trueplookpanya.com',groups.thumbnail) as thumbnails
,huser.user_id
from ams_entrance_user as huser 
inner join ams_university as uu on uu.u_id=(select u_id from  ams_university where u_id=huser.u_id) 
inner join ams_entrance as ent on ent.ent_id=huser.ent_id and huser.directapply_faculty_id=0 and ent.year_config=huser.year
inner join ams_university_group_map as gmap on gmap.u_id=uu.u_id 
inner join ams_university_group_m2m as m2m on m2m.u_group_id_destination=gmap.u_id
inner join ams_university_group as groups on groups.u_group_id=gmap.u_group_id and groups.u_group_type=4 and groups.record_status=1
union 
select groups.u_group_id,huser.directapply_faculty_id as branch_id
,groups.u_group_name as branch_name
,huser.year
,groups.u_group_type
,concat('http://static.trueplookpanya.com',groups.thumbnail) as thumbnails
,huser.user_id
from ams_entrance_user as huser 
inner join ams_news_directapply_university as d on d.news_id=huser.u_id and d.u_id=huser.directapply_faculty_id
inner join ams_university as uu on uu.u_id=huser.directapply_faculty_id 
inner join ams_entrance as ent on ent.u_id=directapply_faculty_id and ent.year_config=huser.year
inner join ams_university_group_map as gmap on gmap.u_id=uu.u_id 
inner join ams_university_group_m2m as m2m on m2m.u_group_id_destination=gmap.u_id
inner join ams_university_group as groups on groups.u_group_id=gmap.u_group_id and groups.u_group_type=4 and groups.record_status=1
where huser.year=$year and huser.user_id=$userid  
group by branch_id order by  u_group_id asc";
$cache_key="get_branch_group_type4_map_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_group_type34_university_year_by_groupid($year,$group_type3r4_id,$delekey){
$sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))) AS thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
,ent.year_config as year
from ams_university_group  as g
inner join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
inner join  ams_university  as amsu on amsu.u_id=m.u_id
inner join  ams_entrance  as ent on ent.u_id=amsu.u_id
where ent.year_config=$year and g.u_group_id=$group_type3r4_id group by university_id order by amsu.u_id asc,m.idx asc
";
$cache_key="get_group_type34_university_year_by_groupid_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_group_type34_university_year_by_groupid_tab2($year,$group_type3r4_id,$delekey){
$sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))) AS thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
,ent.year_config as year
from ams_university_group  as g
inner join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
inner join  ams_university  as amsu on amsu.u_id=m.u_id
inner join  ams_entrance  as ent on ent.u_id=amsu.u_id
where ent.year_config=$year and g.u_group_id=$group_type3r4_id group by university_id order by amsu.u_id asc,m.idx asc
";
$cache_key="get_group_type34_university_year_by_groupid_tab2_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_group_type34_university_year_by_groupid_user_branch_id_in($year,$group_type3r4_id,$branch_id,$delekey){
$sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))) AS thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
,ent.year_config as year
from ams_university_group  as g
inner join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
inner join  ams_university  as amsu on amsu.u_id=m.u_id
inner join  ams_entrance as ent on ent.u_id=amsu.u_id
inner join  ams_entrance_user  as huser on huser.u_id=ent.u_id
where ent.year_config=$year and g.u_group_id=$group_type3r4_id and amsu.u_id in($branch_id) group by university_id order by amsu.u_id asc,m.idx asc";
$cache_key="get_group_type34_university_year_by_groupid_user_branch_id_in_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_group_type34_university_year_by_groupid_user_branch_id($year,$group_type3r4_id,$branch_id,$delekey){
$sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))) AS thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
,ent.year_config as year
from ams_university_group  as g
inner join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
inner join  ams_university  as amsu on amsu.u_id=m.u_id
inner join  ams_entrance as ent on ent.u_id=amsu.u_id
inner join  ams_entrance_user  as huser on huser.u_id=ent.u_id
where ent.year_config=$year and g.u_group_id=$group_type3r4_id and amsu.u_id=$branch_id group by university_id order by amsu.u_id asc,m.idx asc";
$cache_key="get_group_type34_university_year_by_groupid_user_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_group_type34_university_year_by_groupid_user($year,$group_type3r4_id,$delekey){
$sql="select  amsu.u_name as branch_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_id))as faculty_name	
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))as university_name	
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=amsu.u_parent_id))) AS thumbnail
,g.u_group_name as group_name
,g.short_description	 as  group_description
,g.u_group_id	as group_id
,(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_parent_id
,(select u_parent_id from  ams_university where u_id=amsu.u_parent_id)as university_id
,amsu.u_parent_id as  faculty_id
,amsu.u_id as branch_id
,m.idx as map_id
,g.u_group_type
,ent.year_config as year
from ams_university_group  as g
inner join  ams_university_group_map  as m on g.u_group_id=m.u_group_id
inner join  ams_university  as amsu on amsu.u_id=m.u_id
inner join  ams_entrance as ent on ent.u_id=amsu.u_id
inner join  ams_entrance_user  as huser on huser.u_id=ent.u_id
where ent.year_config=$year and g.u_group_id=$group_type3r4_id group by university_id order by amsu.u_id asc,m.idx asc";
$cache_key="get_group_type34_university_year_by_groupid_user_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
//Bookmark
public function get_ams_entrance_user_by_ent_id_u_id_user_id2($ent_id,$u_id,$user_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4,$delekey){
if($faculty_id==''){$faculty_id=0;}
$sql="select * from  ams_entrance_user where ent_id=$ent_id and u_id=$u_id and user_id=$user_id and directapply_faculty_id=$faculty_id and year=$year and u_group_id_type3=$u_group_id_type3 and u_group_id_type4=$u_group_id_type4";
/*
$query=$this->db->query($sql);
$datars=$query->result_array();
$num_rows=(int)$query->num_rows();
*/
 
###########cache###############
$cache_key="get_ams_entrance_user_by_ent_id_u_id_user_id_".$ent_id.'_'.$u_id.'_'.$user_id.'_'.$faculty_id.'_'.$year.'_'.$u_group_id_type3.'_'.$u_group_id_type4;
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
$datars=$this->tppymemcached->get($cache_key_encrypt);
if(!$datars) {
$query=$this->db->query($sql);
$datars=$query->result_array();
$num_rows=(int)$query->num_rows();
$this->tppymemcached->set($cache_key_encrypt,$datars,300);
$sql1='sql_query->'.$sql;
} 
###########cache###############
 
 if($datars){$sql1='sql_cache->'.$sql;}
if($num_rows!==0){$status=1;}else{ $status=0;}
$data=array('sql'=>$sql1,'data'=>$datars,'status'=>$status);
//echo '<hr><pre> data=>'; print_r($data); echo '</pre>';die();
return $data;
}
public function deleteams_entrance_user($ent_id,$user_id,$u_id,$faculty_id,$year,$u_group_id_type3,$u_group_id_type4){
$this->db->where('ent_id', $ent_id);
$this->db->where('user_id', $user_id);
$this->db->where('u_id', $u_id);
$this->db->where('directapply_faculty_id', $faculty_id);
$this->db->where('year', $year);
$this->db->where('u_group_id_type3', $u_group_id_type3);
$this->db->where('u_group_id_type4', $u_group_id_type4);
$result=$this->db->delete('ams_entrance_user');
#########################
if($result=='1'){
$cache_key="get_ams_entrance_user_by_ent_id_u_id_user_id_".$ent_id.'_'.$u_id.'_'.$user_id.'_'.$faculty_id.'_'.$year.'_'.$u_group_id_type3.'_'.$u_group_id_type4;
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
$this->tppymemcached->delete($cache_key_encrypt);
$status='1';
$result_data=array('mesage' =>'Delete successful..','cache' => 'cache delete','status' => $status);
}else{
$status='0';
$result_data=array('mesage' =>'Delete  unsuccessful..','cache' => 'cache not delete','status' => $status);
}
return $result_data;
}
public function insertamsentranceuser($data){
$ent_id=$data['ent_id'];
$user_id=$data['user_id'];
$u_id=$data['u_id'];
$faculty_id=$data['directapply_faculty_id'];
$year=$data['year'];
$u_group_id_type3=$data['u_group_id_type3'];
$u_group_id_type4=$data['u_group_id_type4'];
$sql="select * from  ams_entrance_user where ent_id=$ent_id and user_id=$user_id and u_id=$u_id and directapply_faculty_id=$faculty_id and year=$year and u_group_id_type3=$u_group_id_type3 and u_group_id_type4=$u_group_id_type4";
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$rsdata->num_rows();

#echo '<pre>';echo ' numrows=>'; print_r($numrows); echo '</pre>';
#echo '<pre>';echo ' data=>'; print_r($data); echo '</pre>'; 

if($numrows>0){
$resultdata='Have data';$status='2';
$result_data = array('result' => $resultdata,'data' => $rs,'status' => $status,);
return $result_data;
Die();
}
$result_data_insert=$this->db->insert('ams_entrance_user',$data);
$DBSelect=$this->db;
#echo '<pre>';echo ' $result_data_insert=>'; print_r($result_data_insert); echo '</pre>'; 
if($result_data_insert==1){
$insert_id=$this->db->insert_id();
#########################
$cache_key="get_ams_entrance_user_by_ent_id_u_id_user_id_".$ent_id.'_'.$u_id.'_'.$user_id.'_'.$faculty_id.'_'.$year.'_'.$u_group_id_type3.'_'.$u_group_id_type4;
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
$sql="select * from  ams_entrance_user  where ent_id=$ent_id and u_id=$u_id and  user_id=$user_id  and directapply_faculty_id=$faculty_id and year=$year and u_group_id_type3=$u_group_id_type3 and u_group_id_type4=$u_group_id_type4";
$rsdata= $this->db->query($sql);
$rs=$rsdata->result_array();
$numrows=$this->db->query($sql)->num_rows();
$datars = $this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$datars,300);
#########################	
$resultdata='Insert Done ID.'.$insert_id;
$status='1';
$result_data = array('result' => $resultdata,
	                'data' =>null,
	                'status' => $status,
	                );
}else{
	   	 $resultdata='Error Insert';
	   	 $status='0';
$result_data = array('result' => $resultdata,
	                'data' =>null,
	                'status' => $status,
	                );
}
#echo'<pre> $result_data=>';print_r($result_data);echo'<pre>';
return $result_data;
}
#####
public function get_ams_university_map_group_type_u_parent_id_where($u_parent_id,$year,$u_group_id_type,$delekey){
$sql="select u.*,groups.u_group_name,groups.u_group_type,groups.u_group_id from ams_university  as u
inner join  ams_entrance as ent on ent.u_id=u.u_id
inner join  ams_university_group_map as map on map.u_id=u.u_id
inner join  ams_university_group as groups on map.u_group_id=groups.u_group_id and groups.u_group_type=4
where u.u_parent_id=$u_parent_id and ent.year_config=$year and groups.u_group_id=$u_group_id_type group by u.u_id order by  u.u_id asc";
$cache_key="get_ams_university_map_group_type_u_parent_id_where_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,3600);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
##top tab 
public function get_top_ams_entrance_user_group_map($year,$user_id,$delekey){
$sql_old="select huser.u_id as branch_id,huser.u_group_id_type3,huser.u_group_id_type4
,(select u_group_name from  ams_university_group where u_group_id=huser.u_group_id_type3)as group_type3_name
,groups.u_group_name as group_type4_name
,concat('http://static.trueplookpanya.com',(select thumbnail from  ams_university_group where u_group_id=huser.u_group_id_type3)) AS thumbnail 
,groups.short_description,groups.u_group_type,groups.record_status
,huser.user_id,huser.year,huser.ent_id,huser.directapply_faculty_id,huser.u_id
from ams_entrance_user as huser
inner join ams_entrance as ent on ent.ent_id=huser.ent_id
inner join ams_university  as u on ent.u_id=u.u_id
inner join ams_university_group_map as map on map.u_id=u.u_id
inner join ams_university_group as groups on map.u_group_id=groups.u_group_id and groups.u_group_type=4 and groups.record_status=1
where ent.year_config=$year and huser.user_id=$user_id
group by huser.u_id order by huser.u_group_id_type3 asc";

$sql="select huser.u_id as branch_id,huser.u_group_id_type3,huser.u_group_id_type4
,(select u_group_name from  ams_university_group where u_group_id=huser.u_group_id_type3)as group_type3_name
,groups.u_group_name as group_type4_name
,concat('http://static.trueplookpanya.com',(select thumbnail from  ams_university_group where u_group_id=huser.u_group_id_type3)) AS thumbnail 
,groups.short_description,groups.u_group_type,groups.record_status
,huser.user_id,huser.year,huser.ent_id,huser.directapply_faculty_id,huser.u_id
from ams_entrance_user as huser
inner join ams_entrance as ent on ent.u_id=huser.u_id
inner join ams_university  as u on ent.u_id=u.u_id
inner join ams_university_group_map as map on map.u_id=u.u_id
inner join ams_university_group as groups on map.u_group_id=groups.u_group_id and groups.u_group_type=4 and groups.record_status=1
where ent.year_config=$year and huser.user_id=$user_id
group by huser.u_group_id_type4 order by huser.u_group_id_type3 asc";
$cache_key="get_top_ams_entrance_user_group_map_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_entrance_u_master_id_year_user_id($u_id,$year,$user_id,$delekey){
$sql="select u.*,huser.directapply_faculty_id,huser.year,huser.user_id from  ams_university as u
inner join  ams_entrance_user as huser  on huser.u_id=u.u_id
where u.u_parent_id=(select u_parent_id from  ams_university where u_id=$u_id) and huser.year=$year and huser.user_id=$user_id group by u.u_id order by u.u_id asc";
$cache_key="get_ams_entrance_u_master_id_year_user_id_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_entrance_user_year_user_id_directapply($branch_id_set,$year,$user_id,$delekey){
$sql="select * from ams_entrance_user as huser where huser.u_id=$branch_id_set and huser.directapply_faculty_id=1 and huser.year=$year and huser.user_id=$user_id";
$cache_key="get_ams_entrance_user_year_user_id_directapply_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_news_directapply_branch_id_year_sum($branch_id,$year,$delekey){
$sql="select sum(major.major_receivers) as major_receivers_all
from ams_news_directapply as dir
inner join ams_news_directapply_university as major on major.news_id=dir.news_id 
inner join  ams_university as uu on uu.u_id=major.u_id
where dir.sonsart_year=$year and dir.record_status=1 and major.u_id=$branch_id order by dir.news_id asc ";
$cache_key="get_ams_news_directapply_branch_id_year_sum_".$sql;
##########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}

#################cache#################
return $dataall;
}
public function get_ams_university_by_u_set_in_user_year($faculty_set,$user_id,$year,$delekey){
$sql="select u.u_id,u.u_name,huser.year,huser.u_group_id_type3,huser.u_group_id_type4
from  ams_university as u inner join ams_entrance_user as huser on huser.u_id=u.u_id
where u.u_parent_id in($faculty_set) and huser.user_id=$user_id and huser.year=$year and huser.directapply_faculty_id=0 group by u.u_id order by u.u_id asc";
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_university_by_u_set_in_user_year_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#echo'<pre>$dataall>';print_r($dataall);echo'<pre> <hr>'; die();
#################cache#################
return $dataall;
}
public function get_ams_university_by_u_set_in_user_year_allstatus($faculty_set,$user_id,$year,$delekey){
$sql="select u.u_id,u.u_name,huser.year,huser.u_group_id_type3,huser.u_group_id_type4
from  ams_university as u inner join ams_entrance_user as huser on huser.u_id=u.u_id
where u.u_parent_id in($faculty_set) and huser.user_id=$user_id and huser.year=$year 
group by u.u_id order by u.u_id asc";
#echo '<hr><pre>sql=>'; print_r($sql); echo '</pre>';die();
$cache_key="get_ams_university_by_u_set_in_user_year_allstatus_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#echo'<pre>$dataall>';print_r($dataall);echo'<pre> <hr>'; die();
#################cache#################
return $dataall;
}
public function get_ams_university_faculty_branch_in_uid($setuid,$delekey){
$sql="select(select u_id from  ams_university where u_id=u.u_id)as id
,(select u_name from  ams_university  where u_id=u.u_id)as name
from  ams_university as u
where u.u_parent_id in($setuid)";
$cache_key="get_ams_university_faculty_branch_in_uid_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);
}
#echo'<pre>$dataall>';print_r($dataall);echo'<pre> <hr>'; die();
#################cache#################
return $dataall;
}
public function get_ams_university_entrance_user_branch_in_id($setuid,$user_id,$year,$statusvar,$delekey){
$sql="select u.u_id,u.u_name,huser.user_id,huser.year,huser.ent_id 
from  ams_university as u 
inner join  ams_entrance_user as huser on huser.u_id=u.u_id and huser.directapply_faculty_id=$statusvar 
where u.u_id in($setuid) and huser.year=$year and huser.user_id=$user_id group by  u.u_id  order by u.u_id asc ";
$cache_key="get_ams_university_entrance_user_branch_in_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_university_entrance_branch_in_id($setuid,$delekey){
$sql="select u.u_id,u.u_name,huser.user_id,huser.year,huser.ent_id 
from  ams_university as u 
inner join  ams_entrance_user as huser on huser.u_id=u.u_id and huser.directapply_faculty_id=0 
where u.u_id in($setuid) and huser.year=2560 and huser.user_id=543622 group by  u.u_id  order by u.u_id asc ";
$cache_key="get_ams_university_entrance_user_branch_in_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_university_entrance_faculty_top_in_id($setuid,$user_id,$year,$delekey){
$sql="select  huser.*
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=huser.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=huser.u_id))as faculty_name
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=huser.u_id)))as university_name
from ams_entrance_user as huser
where huser.u_id in($setuid) and huser.user_id=$user_id and  huser.year=$year group by faculty_id order by  faculty_id asc ";
$cache_key="get_ams_university_entrance_faculty_top_in_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_university_entrance_list_all($delekey){
$sql="select (select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_id
,ent.ent_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))as university_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=uu.u_parent_id))) AS thumbnail 
from  ams_entrance as ent 
inner join  ams_university as uu on uu.u_id=ent.u_id 
group by university_id order by university_id asc";
$cache_key="get_ams_university_entrance_list_all_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_entrance_counts_branch_set($branch_set){
$sql="select count(ent.ent_id) as counts from  ams_entrance as ent where ent.u_id in($branch_set)";
$cache_key="get_ams_entrance_counts_branch_set_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_entrance_ams_university_group_map_id($u_group_id,$year,$delekey){
$sql="select ent.* from  ams_university_group_map as map 
inner join ams_entrance as ent on ent.u_id=map.u_id
where map.u_group_id=$u_group_id and ent.year_config=$year group by map.u_id order by ent.ent_id asc";
$cache_key="get_ams_entrance_ams_university_group_map_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_entrance_user_university_group_map_id($u_group_id,$year,$type,$delekey){
if($type==1){
$sql="select map.u_group_id 
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id)))as university_name
,ent.*
from  ams_university_group_map as map 
inner join ams_entrance as ent on ent.u_id=map.u_id
inner join ams_entrance_user as huser on huser.u_id!=ent.u_id and huser.directapply_faculty_id=1 and huser.u_group_id_type4=map.u_group_id
where map.u_group_id=$u_group_id and ent.year_config=$year
group by university_id order by ent.ent_id asc";
}else{
$sql="select map.u_group_id 
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id)))as university_name
,ent.*
from  ams_university_group_map as map 
inner join ams_entrance as ent on ent.u_id=map.u_id
inner join ams_entrance_user as huser on huser.u_id=ent.u_id and huser.directapply_faculty_id=1 and huser.u_group_id_type4=map.u_group_id
where map.u_group_id=$u_group_id and ent.year_config=$year
group by university_id order by ent.ent_id asc";
}
$cache_key="get_ams_entrance_user_university_group_map_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_entrance_university_group_branch_set_id($u_group_id,$year,$branch_set_id,$delekey){
$sql="select ent.ent_id,ent.major_remark,map.u_group_id,ent.u_id,ent.u_id as branch_id
,(select u_name from  ams_university where u_id=ent.u_id)as branch_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id))as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_name
,groups.u_group_type
from  ams_university_group_map as map 
inner join ams_entrance as ent on ent.u_id=map.u_id
inner join ams_entrance_user as huser on huser.u_id!=ent.u_id and huser.directapply_faculty_id=1 and huser.u_group_id_type4=map.u_group_id
inner join  ams_university_group as groups on map.u_group_id=groups.u_group_id and groups.u_group_type=4
where map.u_group_id=$u_group_id and ent.year_config=$year and ent.u_id in($branch_set_id)
group by university_id order by university_id asc";

$cache_key="get_ams_entrance_university_group_branch_set_id_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_entrance_university_group_branch_set_id_tab2($u_group_id,$year,$branch_set_id,$delekey){
$sql="select ent.ent_id,ent.major_remark,map.u_group_id,ent.u_id,ent.u_id as branch_id
,(select u_name from  ams_university where u_id=ent.u_id)as branch_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id))as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_name
,groups.u_group_type,map.u_group_id
from  ams_university_group_map as map 
inner join ams_entrance as ent on ent.u_id=map.u_id
inner join ams_entrance_user as huser on huser.u_id!=ent.u_id  
inner join  ams_university_group as groups on map.u_group_id=groups.u_group_id and groups.u_group_type=4
where map.u_group_id=$u_group_id and ent.year_config=$year and ent.u_id in($branch_set_id)
group by university_id order by university_id asc";
$cache_key="get_ams_entrance_university_group_branch_set_id_tab2".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_entrance_university_group_branch_set_id_tab2v2($u_group_id,$year,$branch_set_id,$delekey){
$sql="select ent.ent_id,ent.major_remark,map.u_group_id,ent.u_id,ent.u_id as branch_id
,(select u_name from  ams_university where u_id=ent.u_id)as branch_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id))as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))as university_name
,groups.u_group_type,map.u_group_id
from  ams_university_group_map as map 
inner join ams_entrance as ent on ent.u_id=map.u_id
inner join  ams_university_group as groups on map.u_group_id=groups.u_group_id and groups.u_group_type=4
where map.u_group_id=$u_group_id and ent.year_config=$year and ent.u_id in($branch_set_id)
group by university_id order by university_id asc";
$cache_key="get_ams_entrance_university_group_branch_set_id_tab2v2".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
# count
public function get_ams_entrance_ams_university_group_admission_year($u_group_id,$year,$delekey){
$sql="select map.u_group_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id)))as university_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as faculty_name
,ent.*
from  ams_university_group_map as map 
inner join ams_entrance as ent on ent.u_id=map.u_id
inner join ams_entrance_user as huser on huser.u_id!=ent.u_id 
where map.u_group_id=$u_group_id and ent.year_config=$year and huser.directapply_faculty_id=1 and huser.u_group_id_type4=map.u_group_id
group by ent.ent_id order by ent.ent_id asc";

$cache_key="get_ams_entrance_ams_university_group_admission_year_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_university_group_id_type_year($ugroupid,$year,$type,$delekey){
$sql1="select map.*
,groups.* 
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id)))as u_id
,concat('http://static.trueplookpanya.com',groups.thumbnail) as groups_thumbnails
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id)))) as thumbnails 
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id)))as university_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id)))as university_id
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=map.u_id))as faculty_name
,(select u_name from  ams_university where u_id=map.u_id)as branch_name
,map.u_id as branch_id
,ent.major_code,ent.major_remark,ent.year_config
from  ams_university_group_map as map 
inner join ams_entrance as ent on ent.u_id=map.u_id
";
if($type==1){
$sql2="inner join ams_entrance_user as huser on huser.u_id!=ent.u_id  and huser.u_group_id_type4=map.u_group_id
inner join ams_university_group as groups on groups.u_group_id=map.u_group_id
";
}else{
$sql2="inner join ams_entrance_user as huser on huser.u_id=ent.u_id  and huser.u_group_id_type4=map.u_group_id
inner join ams_university_group as groups on groups.u_group_id=map.u_group_id
";
}
$sql3="where map.u_group_id in($ugroupid) and ent.year_config=$year and groups.record_status=1
group by map.u_id order by ent.ent_id asc";
$sql=$sql1.$sql2.$sql3;
$cache_key="get_ams_university_group_id_type_year_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_branch_in_ent_faculty_year($setuid,$year,$delekey){
$sql="select u.u_id as branch_id,u_name as branch_name,ent.major_remark from  ams_university as u 
inner join ams_entrance as ent on ent.u_id=u.u_id
where u.u_parent_id in($setuid) and ent.year_config=$year 
group by ent.u_id order by u.u_id asc";
$cache_key="get_branch_in_ent_faculty_year_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);
}
#echo'<pre>$dataall>';print_r($dataall);echo'<pre> <hr>'; die();
#################cache#################
return $dataall;
}
public function get_groupmapyearbranch($group_id,$setuid,$delekey){
$sql="select g.u_group_id as group_id,amsu.u_parent_id as faculty_id
,(select u_name from  ams_university where u_id=amsu.u_parent_id)as faculty_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=m.u_id)))as university_name
,amsu.u_id as branch_id,m.idx as map_id,g.u_group_type
,amsu.u_name as branch_name,g.u_group_name as group_name
from ams_university_group as g
inner join ams_university_group_map  as m on g.u_group_id=m.u_group_id
inner join ams_university as amsu on amsu.u_id=m.u_id
where g.u_group_id=$group_id and amsu.u_id in($setuid)  
group by  amsu.u_parent_id order by amsu.u_parent_id asc";
$cache_key="get_groupmapyearbranch_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
				   'cache_key'=>$cache_key_encrypt,
				  
	);
}
#echo'<pre>$dataall>';print_r($dataall);echo'<pre> <hr>'; die();
#################cache#################
return $dataall;
}
public function get_faculty_set_university_id($setid,$delekey){
$sql="select un.u_id as faculty_id,un.u_name as faculty_name  
from  ams_university as un  where un.u_parent_id in ($setid)";
$cache_key="get_faculty_set_university_id_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,60);
}
return $data; 
}
public function get_branch_set_university_id_group_maps($setid,$year,$u_group_id,$gruuptype,$delekey){
$sql1="select un.u_id as branch_id,un.u_name as branch_name,ent.ent_id,ent.major_remark,groups.u_group_name  
from  ams_university as un  
inner join ams_entrance as ent on ent.u_id=un.u_id
inner join ams_university_group_map as map on map.u_id=ent.u_id
inner join ams_university_group as groups on groups.u_group_id=map.u_group_id
where un.u_parent_id in ($setid) and ent.year_config=$year
and map.u_group_id=$u_group_id
";
if($gruuptype==1){
$sql2="group by ent.ent_id";
}else{
$sql2="group by un.u_id";
}
$sql=$sql1.$sql2;
$cache_key="get_branch_set_university_id_group_maps_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
###########
public function get_top_branch_user_group_id_type4($year,$user_id,$u_group_id_type4,$delekey){
$sql="select huser.u_id as branch_id,huser.u_group_id_type3,huser.u_group_id_type4
,(select u_group_name from  ams_university_group where u_group_id=huser.u_group_id_type3)as group_type3_name
,groups.u_group_name as group_type4_name
from ams_entrance_user as huser
inner join ams_entrance as ent on ent.u_id=huser.u_id
inner join ams_university  as u on ent.u_id=u.u_id
inner join ams_university_group_map as map on map.u_id=u.u_id
inner join ams_university_group as groups on map.u_group_id=groups.u_group_id and groups.u_group_type=4 and groups.record_status=1
where ent.year_config=$year and huser.user_id=$user_id and huser.u_group_id_type4=$u_group_id_type4 
group by u.u_id order by huser.u_group_id_type3 asc";
$cache_key="get_top_branch_user_group_id_type4_'.$sql.";
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
#########################
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data) {
$data=$this->db->query($sql)->result_array();
$this->tppymemcached->set($cache_key_encrypt,$data,60);
}
return $data; 
}
public function get_count_directapply_user_id_year($user_id,$year,$delekey){
$sql="select count(huser.u_id) as count_directapply 
from  ams_entrance_user as huser 
where huser.directapply_faculty_id!=0 and huser.user_id=$user_id and huser.year=$year";
$cache_key="get_count_directapply_user_id_year_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_count_admission_user_id_year($user_id,$year,$delekey){
$sql="select count(huser.u_id) countbookmark from  ams_entrance_user as huser 
where  huser.directapply_faculty_id=0 and huser.user_id=$user_id and huser.year=$year";
$cache_key="get_count_admission_user_id_year_".$sql;
##########################
$cache_key_encrypt=$this->encryptcode($cache_key);
$cache_key_decryptcode=$this->decryptcode($cache_key_encrypt);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
#########################
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	//echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	//echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_entrance_year_branchse_user_id($year,$branchset,$user_id,$delekey){
$sql="select ent.ent_id,ent.major_remark as entrance_name
,concat('http://static.trueplookpanya.com',groups.thumbnail) AS thumbnail_groups 
,groups.u_group_name as group_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))) AS thumbnail_university 
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id )))as university_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id))as faculty_name
,ent.u_id as branch_id
,(select u_name from  ams_university where u_id=ent.u_id)as branch_name
,MAX(history.score_min) as score_min
,MAX(history.score_max) as score_max
,huser.u_group_id_type3,huser.u_group_id_type4
from ams_entrance_user as huser
inner join ams_entrance as ent on ent.u_id=huser.u_id
inner join ams_entrance_score_history as history on history.ent_id=huser.ent_id and history.year=huser.year 
inner join ams_university as un on un.u_id=ent.u_id
inner join ams_university_group as groups on groups.u_group_id=huser.u_group_id_type3
where ent.year_config=$year and ent.u_id in($branchset) and huser.user_id=$user_id
group by huser.u_id order by ent.ent_id asc";
$cache_key="get_ams_entrance_year_branchse_user_idsss_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	# echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	# echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_university_group_type3or4($ugroupid34,$delekey){
$sql="select g.u_group_id,g.u_group_name,g.short_description,g.u_group_type,g.record_status
,concat('http://static.trueplookpanya.com',g.thumbnail) as thumbnails 
from ams_university_group as g where g.u_group_id=$ugroupid34
order by  g.u_group_type,g.u_group_id asc ";
$cache_key="get_ams_university_group_type3or4_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	# echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	# echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_ams_entrance_user_year_id_type4_user_id($year,$u_group_id_type4,$user_id,$delekey){
$sql="select ent.ent_id,ent.major_remark as entrance_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))) AS thumbnail_university 
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id )))as university_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id))as faculty_name
,ent.u_id as branch_id
,(select u_name from  ams_university where u_id=huser.u_id)as branch_name
,huser.u_group_id_type3,huser.u_group_id_type4
from ams_entrance_user as huser
inner join ams_entrance as ent on ent.u_id=huser.u_id
inner join ams_university as un on un.u_id=ent.u_id
inner join ams_university_group as groups on groups.u_group_id=huser.u_group_id_type3
where ent.year_config=$year and huser.u_group_id_type4 in($u_group_id_type4) and huser.user_id=$user_id
group by huser.u_id order by ent.ent_id asc";
$cache_key="get_ams_entrance_user_year_id_type4_user_id_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	# echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	# echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_entranceuseryeardashboard($year,$branchset,$user_id,$delekey){
$sql="select ent.ent_id,ent.major_remark as entrance_name
,concat('http://static.trueplookpanya.com',groups.thumbnail) AS thumbnail_groups 
,groups.u_group_name as group_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))) AS thumbnail_university 
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id )))as university_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id))as faculty_name
,ent.u_id as branch_id
,(select u_name from  ams_university where u_id=ent.u_id)as branch_name
,history.score_min as score_min
,history.score_max as score_max
,huser.u_group_id_type3,huser.u_group_id_type4,ent.ent_id,ent.major_code,ent.major_remark,ent.year_config
,ent.gpax_weight,ent.gpax_min,ent.onet_weight_total,ent.onet_min_total,ent.01_onet_weight,ent.01_onet_mi
,ent.02_onet_weight,ent.02_onet_min,ent.03_onet_weight,ent.03_onet_mint,ent.04_onet_weight,ent.04_onet_min
,ent.05_onet_weight,ent.05_onet_min,ent.85_gat_weight
,ent.85_gat_min,ent.85_gat_min_part2,ent.71_pat1_weight,ent.71_pat1_min,ent.72_pat2_weight,ent.72_pat2_min
,ent.73_pat3_weight,ent.73_pat3_min,ent.74_pat4_weight,ent.74_pat4_min,ent.75_pat5_weight,ent.75_pat5_min
,ent.76_pat6_weight,ent.76_pat6_min,ent.77_pat71_weight,ent.77_pat71_min
,ent.78_pat72_weight,ent.78_pat72_min,ent.79_pat73_min,ent.80_pat74_min,ent.81_pat75_weight,ent.81_pat75_min
,ent.82_pat76_weight,ent.82_pat76_min,ent.special_remark,ent.receive_amount,ent.receive_amount_sharecode
from ams_entrance_user as huser
inner join ams_entrance as ent on ent.ent_id=huser.ent_id
inner join ams_entrance_score_history as history on history.ent_id=huser.ent_id and history.year=huser.year 
inner join ams_university as un on un.u_id=ent.u_id
inner join ams_university_group as groups on groups.u_group_id=huser.u_group_id_type3
where ent.year_config=$year and ent.u_id in($branchset) and huser.user_id=$user_id and huser.directapply_faculty_id=0
group by ent.ent_id order by ent.ent_id asc";
$cache_key="get_entranceuseryeardashboard_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	# echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	# echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_entrance_user_year_id_type4_user_id_faculty($year,$u_group_id_type4,$user_id,$branch_id,$delekey){
$sql="select ent.ent_id,ent.major_code,ent.major_remark as entrance_name
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=ent.u_id)))) AS thumbnail_university 
,concat('http://static.trueplookpanya.com',groups.thumbnail) as groups_thumbnails 
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id)))as university_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id )))as university_name
,(select u_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id))as faculty_id
,(select u_name from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=un.u_id))as faculty_name
,ent.u_id as branch_id
,(select u_name from  ams_university where u_id=huser.u_id)as branch_name
,huser.u_group_id_type3,huser.u_group_id_type4
,history.score_min as score_min,history.score_max as score_max
,ent.u_id,ent.year_config,ent.gpax_weight,ent.gpax_min,ent.onet_weight_total,ent.onet_min_total,ent.01_onet_weight,ent.01_onet_mi
,ent.02_onet_weight,ent.02_onet_min,ent.03_onet_weight,ent.03_onet_mint,ent.04_onet_weight,ent.04_onet_min
,ent.05_onet_weight,ent.05_onet_min,ent.85_gat_weight
,ent.85_gat_min,ent.85_gat_min_part2,ent.71_pat1_weight,ent.71_pat1_min,ent.72_pat2_weight,ent.72_pat2_min
,ent.73_pat3_weight,ent.73_pat3_min,ent.74_pat4_weight,ent.74_pat4_min,ent.75_pat5_weight,ent.75_pat5_min
,ent.76_pat6_weight,ent.76_pat6_min,ent.77_pat71_weight,ent.77_pat71_min
,ent.78_pat72_weight,ent.78_pat72_min,ent.79_pat73_min,ent.80_pat74_min,ent.81_pat75_weight,ent.81_pat75_min
,ent.82_pat76_weight,ent.82_pat76_min,ent.special_remark,ent.receive_amount,ent.receive_amount_sharecode
from ams_entrance_user as huser
inner join ams_entrance as ent on ent.u_id=huser.u_id
inner join ams_entrance_score_history as history on history.ent_id=huser.ent_id and history.year=huser.year 
inner join ams_university as un on un.u_id=ent.u_id
inner join ams_university_group as groups on groups.u_group_id=huser.u_group_id_type3
where ent.year_config=$year and huser.u_group_id_type4 in($u_group_id_type4) and huser.user_id=$user_id and huser.u_id=$branch_id 
group by ent.ent_id order by ent.ent_id asc";
$cache_key="get_entrance_user_year_id_type4_user_id_faculty_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	# echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	# echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_news_directapply_user_id_faculty($branch_id,$date,$delekey){
#$date=date('Y-m-d');
$sql="select du.idx,dir.news_id as newsid,dir.news_title as title,dir.news_detail as detail
,du.major_receivers as receivers,dir.news_all_receivers
,du.major_grade as gpax
,du.major_gatpat AS gatpat
,du.major_gnet AS req_gnet
,du.major_portfolio AS req_portfolio
,du.major_interview AS req_interview
,du.major_matches AS req_matches
,du.major_property AS req_property
,dir.news_start_date,dir.news_end_date
,dir.interview_start_date,dir.interview_end_date
,dir.doexam_start_date,dir.doexam_end_date
,(select u_name from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=du.u_id)))as university
,(select u_name from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=du.u_id))as faculty_name
,(select u_name from  ams_university where  u_id=du.u_id)as u_name
,(select u_parent_id from  ams_university where  u_id=(select u_parent_id from  ams_university where  u_id=du.u_id))as university_id
,du.u_id as faculty_id
,(select u_parent_id from  ams_university where  u_id=du.u_id)as branch_id
,concat('http://static.trueplookpanya.com/trueplookpanya/',dir.news_banner_file) as news_thumbnails 
,concat('http://static.trueplookpanya.com/trueplookpanya/',(select thumbnail from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=(select u_parent_id from  ams_university where u_id=du.u_id)))) AS thumbnail_university 
,dir.news_thumbnail,dir.news_link_web,dir.news_link_pdf,dir.news_path,dir.news_view
from ams_news_directapply_university as du 
inner join ams_news_directapply as dir on du.news_id=dir.news_id
where du.u_id=$branch_id and dir.record_status=1 and dir.interview_end_date>='$date'
group by dir.news_id  order by dir.interview_end_date asc";
$cache_key="get_news_directapply_user_id_faculty_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	# echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	# echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
public function get_news_directapply_user_branch_id_year($branch_id,$year,$user_id,$delekey){
#$date=date('Y-m-d');
$sql="select huser.* from ams_entrance_user as huser where huser.year=$year  and huser.user_id=$user_id and huser.directapply_faculty_id=1  and huser.u_id=$branch_id";
$cache_key="get_news_directapply_user_branch_id_year_".$sql;
#################cache#################
$cache_key_encrypt=$this->encryptcode($cache_key);
if($delekey!=''){ $this->tppymemcached->delete($cache_key_encrypt); }
//$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
$data=$this->tppymemcached->get($cache_key_encrypt);
if(!$data){
	$query=$this->db->query($sql);
	$data = $query->result_array();
	$this->tppymemcached->set($cache_key_encrypt,$data,300);
	# echo'<pre>sql query data=>';print_r($data);echo'<pre> <hr>'; die();
	$dataall=array('sql'=>'query_sql_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>count($data),
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}else{
	# echo'<pre>cache data=>';print_r($data);echo'<pre> <hr>'; die();
	$cache_key_decrypt=$this->decryptcode($cache_key_encrypt);
	$dataall=array('sql'=>'cache_data->'.$sql,
				   'data'=>$data,
                       'data_count'=>0,
				   'cache_key'=>$cache_key_encrypt,
				  
	);

}
#################cache#################
return $dataall;
}
###########
}