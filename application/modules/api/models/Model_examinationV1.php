<?php
class Model_examination extends CI_Model{
#####################
public function __construct(){
parent::__construct();
	#$this->load->database('api'); // load database name api
     //Load library
     $this->load->library('Memcached_library');
}
public function encryptcode($q) {
    $cryptKey  = 'tyyptruemd5';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded);
}
public function decryptcode($q){
    $cryptKey  = 'tyyptruemd5';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}
public function examinationlistwhere_array($array,$deletekey){
##################memcached ci3 ###########################
$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
if($this->cache->memcached->is_supported()){
#######Var##########
$sql="SELECT * from cvs_course_examination where id in ($array)  order by id asc";
#################
$cachekey='key-where-array-cvs_course_examination-'.$sql;
$cachetime='300';
if($deletekey==1){$dataall=$this->cache->memcached->delete($cachekey);}
$data=$this->cache->memcached->get($cachekey);
if($data==null){
###########DB SQL query Start###########
$query=$this->db->query($sql);
$dataresult=$query->result();
###########DB SQL query End ###########
           $this->cache->memcached->save($cachekey,$dataresult,$cachetime);
           $cache_info=$this->cache->memcached->cache_info();
           $dataall=array('message'=>'Data form database query save to memcached',
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'time sec'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>$cache_info);
        }else{
             $cache_info=$this->cache->memcached->cache_info();
             $dataall=array('message'=>'Data form memcached',
					    'status'=>TRUE, 
					     'list'=>$data,
                              'count'=>(int)count($data),
                              'time sec'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>$cache_info);
        }
 }
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##################memcached ci3 ###########################
 return $dataall;
}
public function memcache_manual($array,$deletekey){
// Load library
// manual connection to Mamcache
$memcache=new Memcache;
#$memcachehost=$this->config->load['memcachehost'];
#$memcachepost=$this->config->load['memcachepost'];
$memcache->connect('TPPYMem1.ppm.in.th','11211');
$cache_info=null;//$memcache->getStats();
     $cachetime='3600';
     $sql="SELECT * from cvs_course_examination where id in ($array)  order by id asc";
     $cachekey="key-where-array-cvs_course_examination-".$sql;
     //if($deletekey==1){ $memcache->delete($cachekey); }

        $dataresult=$memcache->get($cachekey);
        if($dataresult!=null){
            $status='cache manual Data form database query save to memcached'.$cachetime.' seconds ';
            $dataall=array('message'=>$status,
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'time sec'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>$cache_info);
        }else{
             
            #######Var##########
		  $query = $this->db->query($sql);
		  $dataresult = $query->result();
            $memcache->set($cachekey,$dataresult,false,$cachetime); 
            // 10 seconds
            $status='cache manual Real data set time cache key'.$cachetime.' seconds';
            $dataall=array('message'=>$status,
					'status'=>FALSE, 
					'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         'time sec'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>$cache_info);
        }
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die();     
return $dataall;
}
public function cvs_course_examination_array($array,$deletekey){
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
$sql1="SELECT * from cvs_course_examination where id in ($array) order by id asc";
$sql="SELECT * from cvs_course_examination where id in ($array) order by FIND_IN_SET (id,'$array')";
$search=',';
$replace='-';
$string=$array;
$array2=str_replace($search,$replace,$string);
$cachekey="key-where-array-cvs_course_examination-set-examid-".$array2;
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         #'info'=>$cacheinfo
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         #'info'=>$cacheinfo
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
}
public function where_course_examination($exam_id,$deletekey){
$sql="select *,(select mul_category_name from  mul_category_2017  where mul_category_id=exa.mul_root_id)as category_name
,(select mul_level_name from  mul_level where mul_level_id=exa.mul_level_id)as level_name
,(select count(id) from cvs_course_question where exam_id=exa.id)as question_total 
from cvs_course_examination as exa where exa.id=$exam_id";
$cachekey="key-cvs-course-question-id-".$exam_id;

##########*******memcache*******############
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         #'info'=>$cacheinfo
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         #'info'=>$cacheinfo
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
}
public function wherequestion($question_id,$deletekey){ 
#echo '<pre>question_id=>';print_r($question_id);echo '</pre>';   
#echo '<pre>deletekey=>';print_r($deletekey);echo '</pre>';  Die();
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
$sql="select distinct q.*,(select exam_name from  cvs_course_examination where id=q.exam_id)as exam_name from cvs_course_question as q where q.exam_id=$question_id order by q.id asc";
$cachekey="key-where-question-id-".$question_id;
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
}
public function where_cvs_course_exam_score_member($exam_id,$user_id,$order='desc',$limit='1'){
/*
echo '<pre>$exam_id=>';print_r($exam_id);echo '</pre>';   
echo '<pre>$user_id=>';print_r($user_id);echo '</pre>'; 
echo '<pre>$order=>';print_r($order);echo '</pre>'; 
echo '<pre>$limit=>';print_r($limit);echo '</pre>';  Die();
*/
//Load library
$this->load->library('Memcached_library');
$cachetime='600';

if($user_id==Null){
#################################### 
if($order=='asc'){
$sql="select distinct *,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id=$exam_id  
order by score.id asc limit 1";
}else{
$sql="select distinct *
,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id=$exam_id  
order by score.id desc limit 1"; 
}
#################################### 
}else{
#################################### 
if($order=='asc'){
$sql="select distinct *,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id=$exam_id and score.user_id=$user_id 
order by score.id asc limit 1";
}else{
$sql="select distinct *,(select count( score_value )from cvs_course_exam_score where exam_id=score.exam_id)as total
,(select count(*) from cvs_course_question where exam_id=score.exam_id)as cnt
from cvs_course_exam_score as score  
where score.exam_id=$exam_id and score.user_id=$user_id 
order by score.id desc limit 1";
}
#################################### 
}
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
$cachekey="key-cvs-course-exam-score-exam_id-".$exam_id.'-userid-'.$user_id;
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $dataall;
}
public function where_course_examination_id($id,$deletekey){
$sql="select * 
,(select mul_category_name from  mul_category_2017  where mul_category_id=exa.mul_root_id)as category_name
,(select mul_level_name from  mul_level where mul_level_id=exa.mul_level_id)as level_name
,(select count(id) from cvs_course_question where exam_id=exa.id)as question_total
from cvs_course_examination as exa where exa.id=$id";
$cachekey="key-where_course-examination-category2017-level-id".$id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############
return $dataall;
}
public function where_map_choice_true($id,$deletekey){
$sql="select question_id as id_question,ans.id as id_ans,ans.answer_detail as detail_answer,ans.answer_ans as status_answer,answer_comment
,(select question_detail from  cvs_course_question where id=ans.question_id)as detail_question
from cvs_course_answer as ans where question_id=$id  and answer_ans='true' order by id asc";
$cachekey="key-cvs-course-answer-true-id".$id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
}
public function where_map_choice($id,$deletekey){
$sql="select ans.id as ans_id,ans.answer_detail as detail,ans.answer_ans as answer
,(select question_detail from  cvs_course_question where id=ans.question_id)as question_detail
from cvs_course_answer as ans where question_id='$id' order by id asc";
$cachekey="key-where-map-choice-id".$id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
}
public function score_value_exam_id($exam_id,$deletekey){
$sql="SELECT score_value, count( score_value ) as total 
FROM cvs_course_exam_score WHERE exam_id=$exam_id GROUP BY score_value";
$cachekey = "score-value-exam-id-".$exam_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function score_value_exam_id_users_group($exam_id,$school_name,$school_level,$deletekey){ 
$sql1="SELECT score.score_value,count(score.score_value) as total FROM cvs_course_exam_score as score "; 
/*
$sql1="SELECT score.score_value,count(score.score_value) as total
,users.user_id,users.psn_firstname,users.psn_lastname
,users.job_edu_name as edu_name
,users.job_edu_level as edu_level
FROM cvs_course_exam_score as score
";  
*/   
if($school_name==null && $school_level==null){
//$sql2=" left join users_account as users on score.user_id=users.user_id WHERE score.exam_id=$exam_id  and users.psn_firstname!='' GROUP BY score.score_value";   
$sql2=" WHERE score.exam_id=$exam_id GROUP BY score.score_value";   
$cachekey="score-value-exam-id-users-group".$exam_id;
}elseif($school_name!==null && $school_level==null){
$sql2=" left join users_account as users on score.user_id=users.user_id
WHERE score.exam_id=$exam_id  and users.psn_firstname!='' and users.job_edu_name like '$school_name%' GROUP BY score.score_value";  
$cachekey="score-value-exam-id-users-group-".$exam_id.'-school_name-'.$school_name;
}elseif($school_name!==null && $school_level!==null){
$sql2=" left join users_account as users on score.user_id=users.user_id
WHERE score.exam_id=$exam_id  and users.psn_firstname!='' and users.job_edu_name like '$school_name%' and users.job_edu_level like '$school_level%' GROUP BY score.score_value";   
$cachekey="score-value-exam-id-users-group-".$exam_id.'-school_name-'.$school_name.'-job-edu-level-'.$school_level;
}       
$sql=$sql1.$sql2;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'sql'=>$sql,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'sql'=>$sql,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############
//echo'<pre> $dataall=>';print_r($dataall);echo'</pre>';Die(); 
#return $dataall;
return $resultsdata;
#return $data;
}
public function score_value_exam_id_job_edu_name($exam_id,$job_edu_name,$deletekey){
$sql="SELECT score_value, count( score_value ) as total 
FROM cvs_course_exam_score 
WHERE exam_id=$exam_id and user_id in (select user_id from users_account where job_edu_name like'$job_edu_name%')
GROUP BY score_value";
$cachekey = "score-value-exam-id-".$exam_id.'-job-edu-name-'.$job_edu_name;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'sql'=>$sql,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'sql'=>$sql,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function score_count_cnt($exam_id,$deletekey){
$sql = "SELECT COUNT(*) cnt FROM cvs_course_question WHERE exam_id=$exam_id";
$cachekey = "QuestionCNT-ScoreDisplay-exam_score-member-".$exam_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function where_count_lesson_mul_map_exam_question_lesson_exam_id($id,$deletekey){
$sql="select distinct map_eql.lesson_id 
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as count_lesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
,(select name from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as book_name
,(select mul_level_id from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_id
,(select mul_category_id from mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_id
,(select mul_education_book_id from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as mul_education_book_id
,(select id from  cvs_course_examination where id=exam.id)as exam_id
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
where exam.id=$id";
#echo '<pre>  $sql=>';print_r($sql); echo '<pre>'; 
#$cache_key = "where_count_lesson_mul_map_exam_question_lesson_exam_id_".$sql;
$cachekey="where-count-lesson-mul-map-exam-question-lesson-exam-id-".$id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function where_mul_map_exam_question_lesson_id($lesson_id){
$sql="select * from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.lesson_id=$lesson_id";
$cachekey="where-mul-map-exam-question-lesson-id-".$lesson_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function where_mul_map_exam_question_lesson_id_exam_id($lesson_id,$exam_id,$deletekey){
$sql="select * from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.lesson_id=$lesson_id and  q.exam_id=$exam_id";
$cachekey="where-mul-map-exam-question-lesson-id-".$lesson_id.'-exam-id-'.$exam_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############

#return $dataall;
return $resultsdata;
#return $data;
}
public function get_where_mul_map_content_lessonid_join($lesson_id,$deletekey){
$sql="select distinct content.mul_content_id,ls.lesson_name,ls.lesson_id,ls.lesson_parent_id,content.mul_content_subject,content.mul_content_text,concat('http://www.trueplookpanya.com/learning/detail/',content.mul_content_id) AS content_url
,concat('http://www.trueplookpanya.com/api/knowledgebase/content/',content.mul_content_id) AS content_api_url 
,concat('http://static.trueplookpanya.com/',content.thumbnail_path,content.thumbnail_name) AS thumbnail 
,(select view_count from mul_content where mul_content_id=content.mul_content_id)as view_count
,(select view_count from mul_content where mul_content_id=content.mul_content_id)as view
from mul_map_content_lesson map 
left join mul_lesson ls on map.lesson_id=ls.lesson_id 
left join mul_content  content on content.mul_content_id=map.mul_content_id 
where map.lesson_id=$lesson_id";
$cachekey="get-where-mul-map-content-lessonid-join-".$lesson_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function job_edu_name_users_account($school_name,$deletekey){
$sql1="select distinct(users.job_edu_level)  
,(select count(user_id) from users_account where job_edu_level=users.job_edu_level and job_edu_name=users.job_edu_name)as user_count
from  users_account as users   
where users.job_edu_name='$school_name'and job_edu_level is not null";
$sql2="select distinct(users.job_edu_level)  
,(select count(user_id) from users_account where job_edu_level=users.job_edu_level and job_edu_name=users.job_edu_name)as user_count
from  users_account as users   
where users.job_edu_name like '$school_name%' and job_edu_level is not null";
$sql=$sql2;
$cachekey="key-job-edu-name-users-account-all-school_name-".$school_name; 
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function job_edu_name_users_account_count($school_name,$deletekey){
$sql1="select  count(user_id)  as user_count from  users_account as users  
where users.job_edu_name='$school_name' and job_edu_level is not null";
$sql2="select  count(user_id)  as user_count from  users_account as users  
where users.job_edu_name like '$school_name%'and job_edu_level is not null";
$sql=$sql2;
$cachekey="key-job-edu-name-users-account-all-count-school_name-".$school_name; 
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function school_examination_distinct_edu_name_edu_level_exam_id2($exam_id,$deletekey){
$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree
,users.user_email as email,exam.exam_name,exam.id as exam_id,exam.exam_percent 
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
,(select max(score_value) from cvs_course_exam_score where exam_id=exam.id and  user_id=users.user_id)as exam_score_user
,(select avg(score_value) from cvs_course_exam_score where exam_id=score.exam_id)as score_value_avg
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and score.exam_id=$exam_id order by users.user_id asc,score.id desc";
$cachekey="key-schools-where-examination-distinct-edu-name-edu-level-exam_id2-".$exam_id;
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function school_users($school_name,$school_level,$deletekey){
if($school_level==''||$school_level==null){
 $sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree,users.user_email as email
from  users_account as users   
where  users.job_edu_name like '$school_name%' order by users.user_id asc";   
$cachekey="key-school-users-".$school_name; 
}elseif($school_level!==''){
$sql="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree,users.user_email as email
from  users_account as users   
where  users.job_edu_name like '$school_name%' and users.job_edu_level='$school_level' order by users.user_id asc";
$cachekey="key-school-users-".$school_name.'-school-level-'.$school_level;
}

##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         );
}
##########*******memcache*******############
#return $dataall;
return $resultsdata;
#return $data;
}
public function school_users_exam_score($school_name,$school_level,$scorestatus,$deletekey){

if($school_level==''||$school_level==null){
$sql1="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree,users.user_email as email
,score.answer_value,score.score_value,score.exam_id,score.duration_sec
from  users_account as users   
left join cvs_course_exam_score as  score on score.user_id=users.user_id ";
if($scorestatus==''){
$sql2=" where  users.job_edu_name like '$school_name%' group by users.user_id asc"; 
$scorestatus=0; 
}else{
$sql2=" where  users.job_edu_name like '$school_name%' and score.exam_id!='' group by users.user_id asc"; 
  
}
$sql=$sql1.$sql2;
$cachekey="key-school-exam-score-users-".$school_name.'-status'.$scorestatus; 
}elseif($school_level!==''){
$sql1="select distinct users.user_id,users.member_id,psn_firstname as firstname,users.psn_lastname as lastname
,COALESCE(NULLIF(concat(users.psn_firstname,' ',users.psn_lastname),''),users.psn_display_name) as user_fullname
,case users.psn_sex when '1' then 'ชาย' when '2' then 'หญิง' when '' then 'ไม่ระบุเพศ' end as sex
,users.psn_address as address,users.psn_address as address
,users.psn_postcode,users.psn_province as province,users.psn_id_number as id_number,users.psn_birthdate as birthdate
,users.job_name,users.job_edu_name as edu_name,users.job_edu_level as edu_level,users.job_edu_degree as edu_degree,users.user_email as email
,score.answer_value,score.score_value,score.exam_id,score.duration_sec
from  users_account as users   
left join cvs_course_exam_score as  score on score.user_id=users.user_id ";
if($scorestatus==''){
$sql2=" where  users.job_edu_name like '$school_name%' and users.job_edu_level='$school_level' group by users.user_id asc";  
$scorestatus=0; 
}else{
$sql2=" where  users.job_edu_name like '$school_name%' and users.job_edu_level='$school_level' and score.exam_id!='' group by users.user_id asc"; 
  
}
$sql=$sql1.$sql2;
$cachekey="key-school-exam-score-users-".$school_name.'-school-level-'.$school_level.'-status'.$scorestatus; 
}
##########*******memcache*******############
$this->load->library('Memcached_library');
$cachetime='600';
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired

if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata1=$query->result();
          $resultsdata=$resultsdata1;
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
##########*******memcache*******############
return $dataall;
//return $resultsdata;
#return $data;
}
public function score_avg($exam_id,$school_name,$school_level,$deletekey){
/*
echo '<pre>$exam_id=>';print_r($exam_id);echo '</pre>';   
echo '<pre>$school_name=>';print_r($school_name);echo '</pre>'; 
echo '<pre>$school_level=>';print_r($school_level);echo '</pre>'; 
*/
//Load library
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
if($school_name==''&& $school_name=='' && $school_level==''){
$sql="select ROUND(avg(score.score_value)) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and  score.exam_id=$exam_id  
order by users.user_id asc,score.id desc";
$cachekey="key-score-avg-examid-".$exam_id;
}elseif($school_name!==''&& $school_level==''){
$sql="select ROUND(avg(score.score_value)) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and  score.exam_id=$exam_id and users.job_edu_name like'$school_name%'
order by users.user_id asc,score.id desc"; 
$cachekey="key-score-avg-examid-".$exam_id.'-jobedu_name-'.$school_name;
}elseif($school_name!=='' && $school_level!==''){
$sql="select ROUND(avg(score.score_value)) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000  and  score.exam_id=$exam_id and users.job_edu_name like'$school_name%' and users.job_edu_level like'$school_level%'
order by users.user_id asc,score.id desc"; 
$cachekey="key-score-avg-examid-".$exam_id.'-jobedu_name-'.$school_name.'-edu_level-'.$school_level;
}
#################################### 
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();

##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
##########*******memcache*******############
return $resultsdata;
}
public function lesson_examination_by_exam_id($exam_id,$deletekey){
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 

$sql="select distinct map_eql.lesson_id 
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as count_lesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
,(select name from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as book_name
,(select mul_level_id from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_id
,(select mul_category_id from mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_id
,(select mul_education_book_id from  mul_education_book where mul_education_book_id=lesson.mul_education_book_id)as mul_education_book_id
,(select id from  cvs_course_examination where id=exam.id)as exam_id
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
where exam.id=$exam_id";
$cachekey="key-lesson-examination-by-exam_id-".$exam_id;

#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
##########*******memcache*******############
return $resultsdata;
}
public function lesson_examination_question_true_by_exam_id($exam_id,$lesson_id,$deletekey){
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$sql="select q.exam_id,map.*,q.question_detail as question,q.question_detail as question_detail,ans.id as id_ans,ans.answer_ans,ans.answer_detail  as answer
,lesson.lesson_name,exam.exam_name
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select lesson_name from mul_lesson where lesson_id=map.lesson_id)as lesson_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
from mul_map_exam_question_lesson as map   
left join cvs_course_question as q on q.id=map.question_id
left join cvs_course_examination as exam on exam.id=q.exam_id
left join cvs_course_answer as ans on q.id=ans.question_id
left join mul_lesson as lesson on lesson.lesson_id=map.lesson_id
where q.exam_id=$exam_id and ans.answer_ans='true' and map.lesson_id=$lesson_id";
$cachekey="key-lesson-examination-question-true-by-exam_id-".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function mul_map_exam_question_lesson_by_lesson_id($lesson_id,$deletekey){
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$sql="select * from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.lesson_id=$lesson_id";
$cachekey="key-mul-map-exam-question-lesson-by-lesson_id-".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function total_answer_true($result_set,$deletekey) {
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$sql="select COUNT(*) cnt FROM cvs_course_answer WHERE id IN($result_set2) AND answer_ans='true'";
$search=',';
$replace='-';
$string=$result_set;
$result_set2=str_replace($search,$replace,$string);
$cachekey="key-total-answer-true-".$result_set2;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function count_user_edu_name_level($job_edu_name,$job_edu_level,$deletekey){
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$sql="select count(user_id)as count_user from  users_account as users where users.job_edu_name='$job_edu_name' and users.job_edu_level='$job_edu_level'";
$cachekey="key-count-user-edu_name-'.$job_edu_name.'-level-".$job_edu_level;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function chart_question_lesson_name_exam_id($exam_id,$deletekey){
$sql="select distinct map_eql.lesson_id 
,(select count(lesson_id)as count_lesson from mul_map_exam_question_lesson where lesson_id=map_eql.lesson_id)as count_question_lesson
,(select lesson_name from mul_lesson where lesson_id=map_eql.lesson_id)as lesson_name
,(select exam_name from cvs_course_examination  where id=exam.id)as exam_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
from  mul_map_exam_question_lesson as map_eql   
left join mul_lesson as lesson on lesson.lesson_id=map_eql.lesson_id
left join cvs_course_question as question on question.id=map_eql.question_id
left join cvs_course_examination as exam on exam.id=question.exam_id
where exam.id=$exam_id";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$cachekey="key-chart-question-lesson_name-exam-id-".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function where_in_question_lesson_id_ans_true($coursequestionid,$lesson_id,$deletekey){
     
 /*
$sql="select *
,(select id from cvs_course_answer where question_id=map.question_id and answer_ans='true')as id_ans_true
from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.question_id IN($coursequestionid) and map.lesson_id='$lesson_id' ";
*/

$sql="select exam_id,question_id
,(select id from cvs_course_answer where question_id=map.question_id and answer_ans='true')as id_ans_true
,lesson_id
from mul_map_exam_question_lesson as map  
left join cvs_course_question as q on q.id=map.question_id
where map.question_id IN($coursequestionid) and map.lesson_id='$lesson_id' ";
 
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$coursequestionid;
$coursequestionid=str_replace($search,$replace,$string);
$cachekey="key-where-in-question-lesson-id'.$lesson_id.'-ans-true-in".$coursequestionid;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function count_scoredisplay_exam_score($exam_id,$deletekey){
$sql = "SELECT COUNT(*) cnt FROM cvs_course_question WHERE exam_id=$exam_id";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$coursequestionid;
$coursequestionid=str_replace($search,$replace,$string);
$cachekey="key-count-scoredisplay-exam-score-exam-id".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function scoredisplay_exam_score_school($exam_id,$job_edu_name,$job_edu_level,$deletekey){
if($job_edu_name==Null&& $job_edu_level==Null){
$sql="SELECT score_value, count( score_value ) as total FROM cvs_course_exam_score WHERE exam_id='$exam_id' GROUP BY score_value";
 }else if($job_edu_name!==Null && $job_edu_level==Null){
$sql="select score_value,count( score_value )as total 
from cvs_course_exam_score 
where exam_id=$exam_id and user_id in (select user_id from users_account where job_edu_name like'$job_edu_name%')
group by score_value ";
}else if($job_edu_name!==Null && $job_edu_level!==Null){
$sql="SELECT score_value, count( score_value ) as total 
FROM cvs_course_exam_score 
WHERE exam_id='$exam_id' and user_id in (select user_id from users_account where job_edu_name like'$job_edu_name' and job_edu_level='$job_edu_level')
GROUP BY score_value";
} 
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$coursequestionid;
$coursequestionid=str_replace($search,$replace,$string);
$cachekey="key-scoredisplay-exam-score-school-exam-id".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function avg_school_level_exam_id($job_edu_name,$job_edu_level,$exam_id,$deletekey){
$sql="select avg(score.score_value) as score_avg
,(select count(id) from cvs_course_question where exam_id=exam.id)as question_total
from  users_account as users   
left join cvs_course_exam_score score on score.user_id=users.user_id
left join cvs_course_examination exam on exam.id=score.exam_id
where score.id>24000000 and users.job_edu_name='$job_edu_name' and  users.job_edu_level='$job_edu_level'and score.exam_id=$exam_id order by users.user_id asc,score.id desc ";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$coursequestionid;
$coursequestionid=str_replace($search,$replace,$string);
$cachekey="key-avg-schools-level-exam-id".$exam_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
###school###
public function school_name_examination($schoolset,$deletekey){
if($schoolset==''){
$schoolset=" 'โรงเรียนวัดยานนาวา','โรงเรียนวัดพระยาสุเรนทร์ (บุญมีอนุกูล)','โรงเรียนวัดหงส์ปทุมาวาส','โรงเรียนพระแม่มารีพระโขนง','โรงเรียนวัดเทพลีลา','สาธิตม.บ้านสมเด็จฯ (ประถม)','โรงเรียนบีคอนเฮาส์แย้มสอาดรังสิต','โรงเรียนหนองจอกกงลิบฮัวเคียว','โรงเรียนอนุบาลนนทบุรี','โรงเรียนเซนต์โยเซฟ บางนา','โรงเรียนวัดแสนเกษม','โรงเรียนบ้านลำต้นกล้วย','โรงเรียนโชคชัยกระบี่','โรงเรียนโชคชัยลาดพร้าว','โรงเรียนพระหฤทัยดอนเมือง','โรงเรียนเขมะสิริอนุสสรณ์','โรงเรียนโชคชัยรังสิต','โรงเรียนวัดบึงทองหลาง','โรงเรียนวัดนิมมานรดี','โรงเรียนสามเสนนอก','โรงเรียนสายน้ำทิพย์','โรงเรียนสากลศึกษา บางบัวทอง','โรงเรียนวัดเวฬุวนาราม(สินทรัพย์อนุสรณ์)' ";        
}
$sql="select distinct users.job_edu_name
,(select count(user_id) from users_account where job_edu_name=users.job_edu_name)as user_total
,(select count(distinct score.user_id) as score_count from cvs_course_exam_score  as score left join users_account as usr on usr.member_id=score.member_id where  usr.job_edu_name=users.job_edu_name)as score_total
from users_account as users 
where users.job_edu_name in ($schoolset) order by users.user_id asc";
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$schoolset;
$schoolset=str_replace($search,$replace,$string);
$cachekey="key-school-name-examination-".$schoolset;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
public function where_map_examquestion_examlessontrue($exam_id,$lesson_id,$deletekey){
$sql="select q.exam_id,map.*,q.question_detail as question,q.question_detail as question_detail,ans.id as id_ans,ans.answer_ans,ans.answer_detail  as answer
,lesson.lesson_name,exam.exam_name
,(select count(id)as count_lesson from cvs_course_question where exam_id=exam.id)as count_question
,(select lesson_name from mul_lesson where lesson_id=map.lesson_id)as lesson_name
,(select mul_level_name from  mul_level where mul_level_id=lesson.mul_level_id)as mul_level_name
,(select mul_category_name from  mul_category_2017 where mul_category_id=lesson.mul_category_id)as mul_category_name
from mul_map_exam_question_lesson as map   
left join cvs_course_question as q on q.id=map.question_id
left join cvs_course_examination as exam on exam.id=q.exam_id
left join cvs_course_answer as ans on q.id=ans.question_id
left join mul_lesson as lesson on lesson.lesson_id=map.lesson_id
where q.exam_id=$exam_id and ans.answer_ans='true' and map.lesson_id=$lesson_id";
 
$this->load->library('Memcached_library');
$cachetime='600';
#################################### 
$search=',';
$replace='-';
$string=$schoolset;
$schoolset=str_replace($search,$replace,$string);
$cachekey="key-where-mul-map-exam-questionss-exam-id-'.$exam_id.'-lesson-id-true-".$lesson_id;
#################################### 
#echo '<pre>$sql=>';print_r($sql);echo '</pre>';  Die();
#################################### 
##########*******memcache*******############
// Lets try to get the key
$resultsdata=$this->memcached_library->get($cachekey);
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
# If the key does not exist it could mean the key was never set or expired
if(!$resultsdata){
     // Modify this Query to your liking!
     ###########DB SQL query Start###########
          $query=$this->db->query($sql);
          $resultsdata=$query->result();
     ###########DB SQL query End ###########
            // Lets store the results
            $this->memcached_library->add($cachekey,$resultsdata);
     // Output a basic msg
          $message='form sql database query';
          $dataall=array('message'=>$message,
					'status'=>0, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}else{
            # Output
            # Now let us delete the key for demonstration sake!
            if($deletekey==1){$this->memcached_library->delete($cachekey);}
            $message='form memcached';
            $dataall=array('message'=>$message,
					'status'=>1, 
					'list'=>$resultsdata,
                         'count'=>(int)count($resultsdata),
                         'time'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'sql'=>$sql,
                         );
}
#echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
##########*******memcache*******############
return $resultsdata;
}
### insert
public function inserttabledata($table,$data){
$this->db->insert($table,$data); 
$rows=$this->db->affected_rows(); 
$insert_id=$this->db->insert_id(); 
if($rows!=1){
     $insert=0;
     $insert_msg='false';
}else{
     $insert=1; 
     $insert_msg='true';
}
$dataall=array('insert_id'=>$insert_id,
               'insert'=>$insert,
               'insert_msg'=>$insert_msg,
               );
return $dataall;
}
### update
public function updatetabledata($table,$data,$fild,$value){
$this->db->where($fild, $value);
$this->db->update($table, $data);
return ($this->db->affected_rows() > 0);
}
### delete
public function deletetabledata($table,$fild,$value){
$this->db->where($fild,$value);
$this->db->delete($table);
return ($this->db->affected_rows()<=0);
}
#####################
}
?>