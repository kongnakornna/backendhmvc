<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Accessuser2_library {
private $_ci;
function __construct(){
      $this->CI=& get_instance(); 
      $CI =& get_instance();
      $CI->load->database();
      $this->CI->load->library('session');
      $this->CI->load->helper('cookie','session');
      $this->CI->load->database('default');
      $this->CI->load->model('','',TRUE);
      $this->CI->load->model('Useraccess_model','model_user',TRUE);
      $this->CI->load->model('Useraccess_model','Useraccess_model',TRUE);
      date_default_timezone_set('Asia/Bangkok');
     }   
// Function to get the client ip address  ### 
public function get_client_ip_env() {
    $ipaddress = '';
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
 
    return $ipaddress;
     }
// Function to get the client ip address
public function get_client_ip_server() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
     }
//Get User IP
public function get_user_ip(){
        $ip='';
        if (function_exists('file_get_contents')) {
            $ipify   = @file_get_contents('https://api.ipify.org');
            if (filter_var($ipify, FILTER_VALIDATE_IP)) {
                $ip = $ipify;
            }
        } else {
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote  = $_SERVER['REMOTE_ADDR'];
            if (filter_var($client, FILTER_VALIDATE_IP)) {
                $ip = $client;
            } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
                $ip = $forward;
            } else {
                $ip = $remote;
            }
        }
        return $ip;
    }
public function cache_db($sql,$cachekey,$cachetime,$cachetype,$deletekey){
     ############cache##################
     /*
     echo 'cachedb <hr> sql=> '.$sql; 
     echo '<br>cachekey=>'.$cachekey; 
     echo '<br>cachetime=>'.$cachetime; 
     echo '<br>cachetype=>'.$cachetype; 
     echo '<br>deletekey=>'.$deletekey; 
     echo '<hr>'; #Die();
     */ 
     if($cachetype==null){$cachetype=1;}
     if($cachetype==1){
     /* ############# 1-->cachedb  Start##############  */   
     if($deletekey==1){
     #$deletekeysdata=$this->CI->db->cache_delete($cachekey);
     $this->CI->db->cache_delete_all();
     }  
     ###########DB SQL query Start###########
     // Create Turn caching on
     $this->CI->db->cache_on(); 
     // Turn caching off for this one query
     // $this->CI->db->cache_off();
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     ###########DB SQL query End ###########
               $dataall=array('message'=>'Data form database query save to dbcache',
                              'status'=>FALSE, 
                              'list'=>$dataresult,
                              'count'=>(int)count($dataresult),
                              'time sec'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>null);                  



     /* ############# 1-->cachedb  End##############  */  
     return $dataall;
     }
     elseif($cachetype==2){
     /* ############# 2-->cachefile  Start##############  */ 
     $this->CI->load->driver('cache');
     $cache_is_supported=$this->cache->file->is_supported();
     if($deletekey==1){
     $deletekeysdata=$this->cache->file->delete($cachekey);
     }else{$deletekeysdata='null';}
     $path=$this->CI->config->item('cache_path');
     $get_metadata=$this->cache->file->get_metadata($cachekey);
     $dataresult=$this->cache->file->get($cachekey);
     if($dataresult){
               $status_msg='form cache file';
               $cache_info=$this->cache->file->cache_info($cachekey);
               $dataall=array('message'=>'Data form filecache',
                              'status'=>TRUE, 
                                   'list'=>$dataresult,
                                   'count'=>(int)count($dataresult),
                                   'time sec'=>(int)$cachetime,
                                   'cachekey'=>$cachekey,
                                   'cacheinfo'=>$cache_info);
          
     }elseif(!$dataresult){     
     ###########DB SQL query Start###########
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     ###########DB SQL query End ###########
     if($dataresult){$this->cache->file->save($cachekey,$dataresult,$cachetime);}
     $cache_info=$this->cache->file->cache_info($cachekey);
               $dataall=array('message'=>'Data form database query save to filecache',
                              'status'=>FALSE, 
                              'list'=>$dataresult,
                              'count'=>(int)count($dataresult),
                              'time sec'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>$cache_info);                  
     }
     #############################################
     /* ############# 1-->cachedb  End##############  */  
     return $dataall;
     }
     elseif($cachetype==3){
     $key=$cachekey;
     /* ############# 3-->Redis   Start##############  */  
     #################################################################
     $this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
     $connection_redis=$this->cache->redis->connection();
     $connection=$connection_redis['status'];
     $key=$cachekey;
     $getdata=$this->cache->redis->getkeysdata($key);

     $getkeysdata=$getdata['getdata'];
     if($deletekey==1){
     $deletekeysdata=$this->cache->redis->deletekeysdata($cachekey);
     }else{$deletekeysdata='null';}
     #############################################
     $redis_is_supported=$this->cache->redis->is_supported();
     $file_is_supported=$this->cache->file->is_supported();
     if($redis_is_supported==1 || $file_is_supported==1){
     #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
     #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
     }
     #############################################
     if(!$getkeysdata){ 
     ###########DB SQL query Start###########
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     #echo '<pre> $dataresult-> '; print_r($dataresult); echo '</pre>'; die();
     #$jsondata=json_encode($dataresult, JSON_NUMERIC_CHECK);
     $results_data=$this->cache->redis->setkeysdata($cachekey,$dataresult);
     //$redis_data=$results_data;
     $redis_data=$results_data['getdata'];
     $message='form db query data ';
     $status=0;
     ###########DB SQL query End ###########
     }elseif($getkeysdata){ 
     ###########form Redis Start ###########
     $redis_data=$getkeysdata;
     $status=1;
     $message='form Redis cache data';
     ###########form Redis End ###########
     }
     
     $dataall=array('message'=>$message,
                    'Connection'=>$connection,
                    'status'=>$status, 
                    'list'=>$redis_data,
                    'redis_is_supported'=>$redis_is_supported,
                    'file_is_supported'=>$file_is_supported,
                    'list_count'=>count($redis_data),
                    'cachekey'=>$cachekey,
                    );
                                   
                                   
                                   
     #############################################
     /* ############# 3-->Redis  End##############  */    
     return $dataall;
     }
     elseif($cachetype==4){
     $this->CI->load->library('Memcached_library');
     /* ############# 4-->Memory  memcached  Start##############  */  
     ##########*******memcache*******############
     //Load library
     $this->CI->load->library('Memcached_library');     
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
          $query=$this->CI->db->query($sql);
          $resultsdata=$query->result();
          ###########DB SQL query End ###########
          // Lets store the results
          //$this->memcached_library->add($cachekey,$resultsdata);
          $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
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
                              #'info'=>$cacheinfo
                              );
     }
     # echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
     ##########*******memcache*******############
     return $dataall;
     #return $resultsdata;
     ##################memcached_result###########################
     /* ############# 4-->Memory  memcached  End##############  */   
     }
     elseif($cachetype==5){
     /* ############# 4-->DB SQL Start##############  */    
     ########################################################
     ###########DB SQL query Start###########
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     ###########DB SQL query End ###########
     $dataall=array('message'=>'Data form database ',
                    'status'=>FALSE, 
                    'list'=>$dataresult,
                    'count'=>(int)count($dataresult),
                    );
     /* ############# 4-->DB SQL End##############  */  
     return $dataall;  
     }
     ############cache##################
     }
public function insert_data_log($arr_data=array()){  
     $session=$this->CI->load->library('session');
     $uname=$_COOKIE['uname'];
     $utype=$_COOKIE['utype'];
     $user_id=$_COOKIE['useridx'];
     //echo'<hr><pre> arr_data=>';print_r($arr_data);echo'<pre><hr>'; // Die();
     if($user_id==null){$user_id=$arr_data['user_id'];}
     /*
     $session=$this->CI->load->library('session');
     $uname=$_COOKIE['uname'];
     $utype=$_COOKIE['utype'];
     $user_id=$_COOKIE['useridx'];
     $user_type='0';
     $code='200';
     $modules='';
     $process='insert';
     $message='insert log';
     ############### user Historylog insert Start###################
     $insertdatalog=array('user_id'=>$user_id,
                    'user_type'=>$user_type,
                    'code'=> $code,
                    'modules'=>$modules,
                    'process'=>$process,
                    'message'=>$message,
               );
     $this->CI->load->model("Historylog_model"); 
     $datars=$this->Historylog_model->insert_data_log($insertdatalog);
     ############### user Historylog insert  End ###################
     */
     $user_type=$arr_data['user_type'];
     if($user_type==null ||$user_type!=1){
          $user_type='2';
          $status=2;
     }elseif($user_type==1){
          $status=1;
     }else{
          $status=0;
     }
     $code=$arr_data['code'];
     if($code==null){$code='200';}
     $modules=$arr_data['modules'];
     if($modules==null){$modules='user';}
     $message=$arr_data['message'];
     if($message==null){$message='ไม่ระบบค่ามา and process inser user log';}
     $process=$arr_data['process'];
     if($process==null){$process='process mode โดยไม่ระบบค่ามา ';}
     date_default_timezone_set('Asia/Bangkok');
     $date_time=date('Y-m-d H:i:s');
     $table_log='sd_history_log';
     $insertdatalog=array('user_id'=>$user_id,
                    'user_type'=>$user_type,
                    'date_time'=>$date_time,
                    'y'=>date('Y'),
                    'm'=>date('m'),
                    'd'=>date('d'),
                    'time'=>date('H:i:s'),
                    'code'=> $code,
                    'ip_addess'=>$this->get_user_ip(),
                    'modules'=>$modules,
                    'process'=>$process,
                    'message'=>$message,
                    'status'=>$status,
               );
     //echo'<hr><pre>  insertdatalog=>';print_r($insertdatalog);echo'<pre><hr>';  Die();
     /* 
     Note!! การใช้งาน Transaction ของ MySQL จะต้องกำหนดชนิดของ Database เป็นแบบ InnoDB
     MySQL Stored Procedure คือการจัดการกับความถูกต้องในการทำงานของ SQL Statement 
     ด้วย Transaction ความสามารถของ Transaction คือ หลังจากที่เรา Start Transaction 
     จะสามารถควบคุมการทำงานของ Query ที่เกิดขึ้น ในกรณีที่มีการ Error 
     หรือ เกิดเงื่อนไขที่ไม่ต้องการ เราจะสามารถทำการ Rollback 
     ย้อนกลับข้อมูลที่เกิดขึ้นระหว่างการทำงาน เช่น INSERT, UPDATE และ DELETE 
     ให้กลับมายังจุดก่อนที่ Stored Procedure ที่จะทำได้
     */
     $this->CI->db->trans_start();
     $this->CI->db->insert($table_log,$insertdatalog);
     $this->CI->db->trans_complete();
     $insert_data_log=$this->CI->db->insert_id();
     $affected_rows=$this->CI->db->affected_rows();
     //echo ' affected_rows=>'.$affected_rows; die();
     
     return $affected_rows; 

     }
public function select_data_log($wherearray=array()){   
     #echo'<hr><pre>wherearray=>';print_r($wherearray);echo'<pre> <hr>'; #die();
     $wherearray=@$wherearray;
     $date_start=$wherearray['date_start'];
     $date_end=$wherearray['date_end'];
     $date=$wherearray['date'];
     $month=$wherearray['month'];
     $year=$wherearray['year'];
     $ip_addess=$wherearray['ip_addess'];
     $order_by=$wherearray['orderby'];
     if($order_by==null){$order_by='DESC';}
     $status=$wherearray['status'];
     if($status==null){$status=2;}
     $user_id=@$wherearray['user_id'];
     if($user_id==null){$user_id=null;}
     $rec_count=$wherearray['rec_count'];
     $perpage=$wherearray['perpage'];
     $perpage=(int)$perpage;
     $page=$wherearray['page'];
     if($date_start!==null){
          $date_parts_date_start=explode(' ',$date_start);
          $day_start=$date_parts_date_start[0]; 
          $timestart=$date_parts_date_start[1];
          $time_start=str_replace(':','-',$timestart);
          $datestartkey=$day_start.'-'.$time_start;
          //echo'<pre>date_start_key =>';print_r($datestartkey);echo'</pre>';   
     }else{ $datestartkey=null;}
     if($date_end!==null){
          $date_parts_date_end= explode(' ',$date_end);
          $day_end=$date_parts_date_end[0]; 
          $time_end= $date_parts_date_end[1];
          $timeend= str_replace(':','-',$time_end);
          $dateendkey=$day_end.'-'.$timeend;  
     }else{ $dateendkey=null;}

     //echo'<pre> date_end_key =>';print_r($dateendkey);echo'</pre>';
     //echo' Form Cache <hr> <pre>    date_start =>';print_r($date_start);echo'</pre>'; die();
     ################################################ 
     ##Cach Toools Start######
     $deletekey=$wherearray['deletekey'];
     $cachekey="key-history-log-user-id".$user_id.'-date_start-'.$datestartkey.'-date_end-'.$dateendkey.'-day-'.$date.'-month-'.$month.'-year-'.$year.'-order_by-'.$order_by.'-user_id-'.$user_id.'-perpage-'.$perpage.'-page-'.$page;
     $cachetime='3600';
     //cachefile 
     $cachetype='2'; 
     $this->CI->load->model('Cachtool_model');
     $sql=null;
     $cachechk=$this->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
     $cachechklist=$cachechk['list'];
     //echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();


     
     if($cachechklist!=null){
          #echo' Form Cache <hr> <pre>  cachechk =>';print_r($cachechk);echo'</pre>';  
          $list=$cachechk['list'];
          #echo' Form Cache <hr> <pre>   list =>';print_r($list);echo'</pre>'; 
          $list=$cachechk['list'];
          $message=$cachechk['message'];
          $status=$cachechk['status'];
          $count=$cachechk['count'];
          $cachetime=$cachechk['cachetime'];
          $cache_key=$cachechk['cachekey'];
          $rs=$list;
          $dataresult=$rs;
     $cache_msg='Form Cache type file';
     }
     elseif($cachechklist==null){
     ################################
          $this->CI->db->cache_off();
          //$this->CI->db->cache_delete_all();
          $this->CI->db->select('*');
          $this->CI->db->from('sd_history_log as log');
          $this->CI->db->join('tbl_user as user', 'log.user_id=user.user_idx');
          if($user_id!==null){
          $this->CI->db->where('user.user_idx',$user_id);  
          }
          if($date!==null){
          $this->CI->db->where('log.d',$date);  
          }
          if($month!==null){
          $this->CI->db->where('log.m',$month);  
          }
          if($year!==null){
          $this->CI->db->where('log.y',$year);  
          }
          if($ip_addess!==null){
          $this->CI->db->where('log.ip_addess',$ip_addess);  
          }
          if($date_start!==null && $date_end!==null){
               $this->CI->db->where('log.date_time >=', $date_start);
               $this->CI->db->where('log.date_time <=', $date_end); 
          }
          $this->CI->db->where('log.status',$status); 
          $this->CI->db->order_by('log_id',$order_by);
          $this->CI->db->limit($perpage, $page);
          $query_get=$this->CI->db->get();
          $num=$query_get->num_rows();
          $query_result=$query_get->result(); 
          $rs=$query_result;
          $dataresult=$rs;
     ################################
     $this->CI->load->model('Cachtool_model');
     $sql=null;
     $cacheset=$this->Cachtool_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey);
     //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
     $rs=$cacheset['list'];
     $num=count($rs);
     $cache_msg=$cacheset['message'];;
     }
     ##Cach Toools END######
     ################################################ 
     $returndatars=array('page'=>$page,
                         'perpage'=>$perpage,
                         'rs'=>$rs,
                         'num_rows'=>count($rs),
                         'cache_msg'=>$cache_msg,
                         'where_array'=>$wherearray,);
     //echo'<hr><pre>   returndatars=>';print_r($returndatars);echo'<pre> <hr>';//die();
     return $returndatars; 
     }
public function select_data_log_rows($array=array()){   
     #echo'<hr><pre>   array=>';print_r($array);echo'<pre> <hr>';  die();
     $array=@$array;
     $date_start=$array['date_start'];
     $date_end=$array['date_end'];
     $date=$array['date'];
     $month=$array['month'];
     $year=$array['year'];
     $ip_addess=$array['year'];
     $status=$array['status'];
     if($status==null){$status=2;}
     $user_id=$array['user_id'];
     if($user_id==null){
          $user_id=null;
     }
     $this->CI->db->cache_off();
     //$this->CI->db->cache_delete_all();
     $this->CI->db->select('*');
     $this->CI->db->from('sd_history_log');
     $this->CI->db->join('tbl_user', 'sd_history_log.user_id=tbl_user.user_idx');
     $this->CI->db->where('sd_history_log.status',$status); 
     if($user_id!==null){
     $this->CI->db->where('tbl_user.user_idx',$user_id);  
     }
     if($date!==null){
     $this->CI->db->where('sd_history_log.d',$date);  
     }
     if($month!==null){
     $this->CI->db->where('sd_history_log.m',$month);  
     }
     if($year!==null){
     $this->CI->db->where('sd_history_log.y',$year);  
     }
     if($ip_addess!==null){
     $this->CI->db->where('log.ip_addess',$ip_addess);  
     }
     if($date_start!==null && $date_end!==null){

     $this->CI->db->where('sd_history_log.date_time >=', $date_start);
     $this->CI->db->where('sd_history_log.date_time <=', $date_end);
          
     }
     $num=$this->CI->db->count_all_results();
     //echo'<hr><pre>   $num=>';print_r($num);echo'<pre> <hr>';die();
     return $num; 
     }
     public function update_data_log($arr_data=array(),$wherearray=array()){ 
     $table_log='sd_history_log'; 
     $log_id=$wherearray['log_id'];
     $process=$arr_data['process'];
     $message=$arr_data['message'];
     $date_time=date('Y-m-d H;i:s');
     $data=array('process' => $process,
          'message' => $message,
          'date_time' => $date_time
     );
     $this->CI->db->where('log_id', $log_id);
     $this->CI->db->update($table_log,$data); 
          if ($this->CI->db->affected_rows() > 0){
          return TRUE;
          }else{
          return FALSE;
          }
     }
public function delete_data_log($log_id){    
     $table_log='sd_history_log';
     $query = $this->CI->db->delete($table_log, array('log_id'=>$log_id));
     //return $this->CI->db->affected_rows();
     return $query;
     }
public function truncate_data_log($code_confirm,$user_id){ 
     if($code_confirm==200 && $user_id!==null){
     $table='sd_history_log';
     $this->CI->db->from($table); 
     $queryprocess=$this->CI->db->truncate();  
     $query_process='action allowed ไม่อนุญาตให้ดำเนินการ ';
     $date_time=date('Y-m-d H:i:s');
     $session=$this->CI->load->library('session');
     $uname=$_COOKIE['uname'];
     $utype=$_COOKIE['utype'];
     $user_id=$_COOKIE['useridx'];
     $user_type='1';
     $code='200';
     $modules='truncate';
     $process='truncate_data_log';
     $message=' ล้างข้อมูลประวัติ table sd_history_log ';
     ############### user Historylog insert Start###################
               $insertdatalog=array('user_id'=>$user_id,
                               'user_type'=>$user_type,
                               'code'=> $code,
                               'modules'=>$modules,
                               'process'=>$process,
                               'message'=>$message,
                           );
               $datars=$this->insert_data_log($insertdatalog); 
     return $query_process;    
     }else{
          $query_process='No action allowed ไม่อนุญาตให้ดำเนินการ ';
     return $query_process; 
     } 
  
     }
public function cache_list_file(){
          $CI=& get_instance();
          $path = $CI->config->item('cache_path');
          $cache_path=($path == '') ? APPPATH.'cache/' : $path;
          $this->CI->load->helper('directory'); //load directory helper
          $dir= APPPATH. "APPPATH.'cache/";
          //echo'<hr><pre>  cache_path=>';print_r($cache_path);echo'<pre><hr>'; Die();
          $map = directory_map($cache_path);
          //echo "<select name='yourfiles'>";

          function show_dir_files($in,$path){
          foreach ($in as $k => $v){
          if (!is_array($v)){?>
          <option><?php 
          echo $path,$v 
          ?></option>
          <?php 
          }else{
               print_dir($v,$path.$k.DIRECTORY_SEPARATOR);
          }
          }
          }

          show_dir_files($map,'');  // call the function 
          echo "</select>";

     }
public function clear_all_cache_file(){
     $CI =& get_instance();
     $path = $CI->config->item('cache_path');
     //echo 'path=>'.$path; die();
     //$cache_path=($path == '') ? APPPATH.'cache/' : $path;
     $cache_path=null;
     if($cache_path==null){
          $cache_path=$path;
     }
     echo $cache_path;
     $handle = opendir($cache_path);
     while (($file = readdir($handle))!== FALSE) {
          //Leave the directory protection alone
     if ($file != '.htaccess' && $file != 'index.html'){
               @unlink($cache_path.'/'.$file);
          }
     }
     closedir($handle);
     }
public function cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey){
          ############cache##################
          /*
          echo '<hr>';
          echo '<br>cachekey=>'.$cachekey; 
          echo '<br>cachetime=>'.$cachetime; 
          echo '<br>cachetype=>'.$cachetype; 
          echo '<br>deletekey=>'.$deletekey; die();
          */

          ############cache##################
          if($cachetype!=1 || $cachetype!=5){$sql=null;}
          if($cachetype==null){$cachetype=4;}
          /*
          echo '<br> cachetype=>'.$cachetype; 
          echo '<br> sql=>'.$sql; 
          Die();
          */
          /* ############# cache DB Start##############  */  
          if($cachetype==1 && $sql!=null){
          /* ############# 1-->cachedb  Start##############  */   
          if($deletekey==1){$this->CI->db->cache_delete_all();}  
          ###########DB SQL query Start###########
          // Create Turn caching on
          $this->CI->db->cache_on(); 
          // Turn caching off for this one query
          // $this->CI->db->cache_off();
          $query=$this->CI->db->query($sql);
          $dataresult=$query->result();
          ###########DB SQL query End ###########
                    $dataall=array('message'=>'Data form database query save to dbcache',
                                   'status'=>FALSE, 
                                   'list'=>$dataresult,
                                   'count'=>(int)count($dataresult),
                                   'cachetime'=>(int)$cachetime,
                                   'cachekey'=>$cachekey,
                                   'cacheinfo'=>null);                  



          /* ############# 1-->cachedb  End##############  */  
          return $dataall;
          }
          /* ############# cache DB End##############  */  

          /* ############# cachefile Start##############  */  
          if($cachetype==2){
          /* ############# 2-->cachefile  Start##############  */ 
          $this->CI->load->driver('cache');
          $path=$this->config->item('cache_path');
          $cache_is_supported=$this->cache->file->is_supported();
          $dataresult=$this->cache->file->get($cachekey);

          if($deletekey==1){
               $deletekeysdata=$this->cache->file->delete($cachekey);
               #echo '<br>deletekey=>1';
          }else{
               $deletekeysdata=null;
               #echo '<br>deletekey=>0';
          }

          /*
          echo '<br> cachetype=>'.$cachetype; 
          echo'<hr><pre> data result=>';print_r($dataresult);echo'</pre>';; 
          Die();
          */
          if($dataresult){
               $status_msg='form cache file';
               $cache_info=$this->cache->file->cache_info($cachekey);
               $dataall=array('message'=>'Data form filecache',
                              'status'=>1, 
                              'list'=>$dataresult,
                              'count'=>(int)count($dataresult),
                              'cachetime'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>$cache_info);
               
          }
          elseif(!$dataresult){ 
                    $dataall=array('message'=>'Data filecache is null',
                                   'status'=>0, 
                                   'list'=>null,
                                   'count'=>0,
                                   'cachetime'=>(int)$cachetime,
                                   'cachekey'=>$cachekey,
                                   'cacheinfo'=>null,
                                   );                  
          }
          return $dataall;
          }
          /* ############# cachefile End##############  */  

          /* ############# cache Redis Start##############  */  
          if($cachetype==3){
          $key=$cachekey;
          /* ############# 3-->Redis   Start##############  */  
          #################################################################
          $this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
          $connection_redis=$this->cache->redis->connection();
          $connection=$connection_redis['status'];
          $key=$cachekey;
          $getdata=$this->cache->redis->getkeysdata($key);
          $getkeysdata=$getdata['getdata'];
          if($deletekey==1){
               $deletekeysdata=$this->cache->redis->deletekeysdata($cachekey);
          }else{
               $deletekeysdata=null;
          }
          #############################################
          $redis_is_supported=$this->cache->redis->is_supported();
          $file_is_supported=$this->cache->file->is_supported();
          if($redis_is_supported==1 || $file_is_supported==1){
          #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
          #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
          }
          #############################################
          if(!$getkeysdata){ 
               $message='Redis cache data is null ';
               $status=0;
               $dataall=array('message'=>$message,
                              'status'=>$status, 
                              'list'=>null,
                              'count'=>0,
                              'cachetime'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>null);
          }elseif($getkeysdata){ 
               $redis_data=$getkeysdata;
               $status=1;
               $message='form Redis cache data';
               $dataall=array('message'=>$message,
                              'status'=>$status, 
                              'list'=>$redis_data,
                              'count'=>count($redis_data),
                              'cachetime'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>null);
          } 
          return $dataall;
          }
          /* ############# cache Redis End##############  */  

          /* ############# cache Memcached Start##############  */  
          if($cachetype==4){
          $this->CI->load->library('Memcached_library');
          /* ############# 4-->Memory  memcached  Start##############  */  
          ##########*******memcache*******############
          //Load library
          $this->CI->load->library('Memcached_library');     
          ##########*******memcache*******############
          // Lets try to get the key
          $resultsdata=$this->memcached_library->get($cachekey);
          $type='items';
          $cache_info=$this->memcached_library->getstats($type);
          $cacheversion=$this->memcached_library->getversion();
          $cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
          # If the key does not exist it could mean the key was never set or expired
          if(!$resultsdata){
                    $message='memcached is null';
                    $dataall=array('message'=>$message,
                                   'status'=>0, 
                                   'list'=>null,
                                   'count'=>0,
                                   'time'=>(int)$cachetime,
                                   'cachekey'=>$cachekey,
                                   'sql'=>null,
                                   'info'=>null,
                                   );
          }
          else{
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
                                   'info'=>$cacheinfo,
                                   );
          }
          # echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
          ##########*******memcache*******############
          return $dataall;
          #return $resultsdata;
          ##################memcached_result###########################
          /* ############# 4-->Memory  memcached  End##############  */   
          }
          /* ############# cache Memcached End##############  */  

          if($cachetype==5 && $sql!=null){
          /* ############# 4-->DB SQL Start##############  */    
          ########################################################
          ###########DB SQL query Start###########
          $query=$this->CI->db->query($sql);
          $dataresult=$query->result();
          ###########DB SQL query End ###########
          $dataall=array('message'=>'Data form database ',
                         'status'=>FALSE, 
                         'list'=>$dataresult,
                         'count'=>(int)count($dataresult),
                         );
          /* ############# 4-->DB SQL End##############  */  
          return $dataall;  
          }
     
     }
public function cachedbsetkey($sql,$queryrs=array(),$cachekey,$cachetime,$cachetype,$deletekey){
     $query_result=$queryrs;
     ############cache##################
     /*
     echo '<hr>';
     echo '<br>sql=>'.$sql; 
     echo '<br>cachekey=>'.$cachekey; 
     echo'<hr><pre> query_result=>';print_r($query_result);echo'<pre> <hr>';die();
     echo '<br>cachetime=>'.$cachetime; 
     echo '<br>cachetype=>'.$cachetype; 
     echo '<br>deletekey=>'.$deletekey; Die();
     */
     ############cache##################
     if($cachetype!=1 || $cachetype!=5){$sql=null;}
     if($cachetype==null){$cachetype=4;}
     /*
     echo '<br> cachetype=>'.$cachetype; 
     echo '<br> sql=>'.$sql; 
     Die();
     */
     /* ############# cache DB Start##############  */  
     if($cachetype==1 && $sql!=null){
     /* ############# 1-->cachedb  Start##############  */   
     if($deletekey==1){$this->CI->db->cache_delete_all();}  
     ###########DB SQL query Start###########
     // Create Turn caching on
     $this->CI->db->cache_on(); 
     // Turn caching off for this one query
     // $this->CI->db->cache_off();
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     ###########DB SQL query End ###########
               $dataall=array('message'=>'Data form database query save to dbcache',
                              'status'=>FALSE, 
                              'list'=>$dataresult,
                              'count'=>(int)count($dataresult),
                              'cachetime'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>null);                  



     /* ############# 1-->cachedb  End##############  */  
     return $dataall;
     }
     /* ############# cache DB End##############  */  

     /* ############# cachefile Start##############  */  
     if($cachetype==2){
     /* ############# 2-->cachefile  Start##############  */ 
     $this->CI->load->driver('cache');
     $path=$this->config->item('cache_path');
     $cache_is_supported=$this->cache->file->is_supported();
     $this->cache->file->save($cachekey,$query_result,$cachetime);
     $cache_info=$this->cache->file->cache_info($cachekey);
     $dataall=array('message'=>'Data form database sql query save to filecache',
                    'status'=>FALSE, 
                    'list'=>$query_result,
                    'count'=>(int)count($query_result),
                    'cachetime'=>(int)$cachetime,
                    'cachekey'=>$cachekey,
                    'cacheinfo'=>$cache_info,
                    );            
     return $dataall;
     }
     /* ############# cachefile End##############  */  

     /* ############# cache Redis Start##############  */  
     if($cachetype==3){
     $key=$cachekey;
     /* ############# 3-->Redis   Start##############  */  
     #################################################################
     $this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
     $connection_redis=$this->cache->redis->connection();
     $connection=$connection_redis['status'];
     $key=$cachekey;
     $getdata=$this->cache->redis->getkeysdata($key);
     $getkeysdata=$getdata['getdata'];
     if($deletekey==1){
          $deletekeysdata=$this->cache->redis->deletekeysdata($cachekey);
     }else{
          $deletekeysdata=null;
     }
     #############################################
     $redis_is_supported=$this->cache->redis->is_supported();
     $file_is_supported=$this->cache->file->is_supported();
     if($redis_is_supported==1 || $file_is_supported==1){
     #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
     #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
     }
     #############################################
     if(!$getkeysdata){ 
          $message='Redis cache data is null ';
          $status=0;
          $dataall=array('message'=>$message,
                         'status'=>$status, 
                         'list'=>null,
                         'count'=>0,
                         'cachetime'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>null);
     }elseif($getkeysdata){ 
          $redis_data=$getkeysdata;
          $status=1;
          $message='form Redis cache data';
          $dataall=array('message'=>$message,
                         'status'=>$status, 
                         'list'=>$redis_data,
                         'count'=>count($redis_data),
                         'cachetime'=>(int)$cachetime,
                         'cachekey'=>$cachekey,
                         'cacheinfo'=>null);
     } 
     return $dataall;
     }
     /* ############# cache Redis End##############  */  

     /* ############# cache Memcached Start##############  */  
     if($cachetype==4){
     $this->CI->load->library('Memcached_library');
     /* ############# 4-->Memory  memcached  Start##############  */  
     ##########*******memcache*******############
     //Load library
     $this->CI->load->library('Memcached_library');     
     ##########*******memcache*******############
     // Lets try to get the key
     $resultsdata=$this->memcached_library->get($cachekey);
     $type='items';
     $cache_info=$this->memcached_library->getstats($type);
     $cacheversion=$this->memcached_library->getversion();
     $cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
     # If the key does not exist it could mean the key was never set or expired
     if(!$resultsdata){
               $message='memcached is null';
               $dataall=array('message'=>$message,
                              'status'=>0, 
                              'list'=>null,
                              'count'=>0,
                              'time'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'sql'=>null,
                              'info'=>null,
                              );
     }
     else{
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
                              'info'=>$cacheinfo,
                              );
     }
     # echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
     ##########*******memcache*******############
     return $dataall;
     #return $resultsdata;
     ##################memcached_result###########################
     /* ############# 4-->Memory  memcached  End##############  */   
     }
     /* ############# cache Memcached End##############  */  

     if($cachetype==5 && $sql!=null){
     /* ############# 4-->DB SQL Start##############  */    
     ########################################################
     ###########DB SQL query Start###########
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     ###########DB SQL query End ###########
     $dataall=array('message'=>'Data form database ',
                    'status'=>FALSE, 
                    'list'=>$dataresult,
                    'count'=>(int)count($dataresult),
                    );
     /* ############# 4-->DB SQL End##############  */  
     return $dataall;  
     }
     }
public function cachedb($sql,$query_result=array(),$cachekey,$cachetime,$cachetype,$deletekey){
     if($query_result!=null){
          $queryresult=$query_result;
          /*
          echo '<hr> cachedb 1 sql=> '.$sql; 
          echo '<br>cachekey=>'.$cachekey; 
          echo '<br>cachetime=>'.$cachetime; 
          echo '<br>cachetype=>'.$cachetype; 
          echo '<br>deletekey=>'.$deletekey; 
          echo'<pre> 0->query_result=>';print_r($query_result);echo'</pre>'; 
          */
     }else{
          $query_result='0';
     /*
          echo '<hr> cachedb 2 sql=> '.$sql; 
          echo '<br>cachekey=>'.$cachekey; 
          echo '<br>cachetime=>'.$cachetime; 
          echo '<br>cachetype=>'.$cachetype; 
          echo '<br>deletekey=>'.$deletekey; 
          echo'<pre> 0->query_result=>';print_r($query_result);echo'</pre>'; 
     */
     }

     /*

     $sql=null;
     $query_result=array();
     $cachekey="key-xxx";
     $cachetime='3600';
     $cachetype='2'; //cachefile
     $deletekey=null;
     $this->CI->load->model('Cachtool_model');
     $dataformmodel=$this->Cachtool_model->cachedb($sql,$query_result,$cachekey,$cachetime,$cachetype,$deletekey);

     */


     if($cachetype==null){$cachetype=5;}
     if($cachetype==1){
     /* ############# 1-->cachedb  Start##############  */   
     if($deletekey==1){
     #$deletekeysdata=$this->CI->db->cache_delete($cachekey);
     $this->CI->db->cache_delete_all();
     }  
     
     ###########DB SQL query Start##########
     if($sql!==null){
          // Create Turn caching on
          $this->CI->db->cache_on(); 
          // Turn caching off for this one query
          // $this->CI->db->cache_off();
          $query=$this->CI->db->query($sql);
          $dataresult=$query->result();
     }else{
          $this->CI->db->cache_on(); 
          $dataresult=$query_result;  
     }
     ###########DB SQL query End ###########



               $dataall=array('message'=>'Data form database query save to dbcache',
                              'status'=>FALSE, 
                              'list'=>$dataresult,
                              'count'=>(int)count($dataresult),
                              'cachetime'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>null);                  



     /* ############# 1-->cachedb  End##############  */  
     return $dataall;
     }
     elseif($cachetype==2){
     /* ############# 2-->cachefile  Start##############  */ 
     $this->CI->load->driver('cache');
     $cache_is_supported=$this->cache->file->is_supported();
     if($deletekey==1){
     $deletekeysdata=$this->cache->file->delete($cachekey);
     }else{$deletekeysdata='null';}
     $path=$this->config->item('cache_path');
     $get_metadata=$this->cache->file->get_metadata($cachekey);
     $dataresult=$this->cache->file->get($cachekey);
     if($dataresult){
               $status_msg='form cache file';
               $cache_info=$this->cache->file->cache_info($cachekey);
               $dataall=array('message'=>'Data form filecache',
                              'status'=>TRUE, 
                                   'list'=>$dataresult,
                                   'count'=>(int)count($dataresult),
                                   'cachetime'=>(int)$cachetime,
                                   'cachekey'=>$cachekey,
                                   'cacheinfo'=>$cache_info);
          
     }elseif(!$dataresult){    
     
     ###########DB SQL query Start##########
     if($sql!==null){
          $query=$this->CI->db->query($sql);
          $dataresult=$query->result();  
          // echo '<pre>  dataresult1-> '; print_r($dataresult); echo '</pre>'; //die();
     }else{
          
          
     $get_metadata=$this->cache->file->get_metadata($cachekey);
     $dataresult=$this->cache->file->get($cachekey);
     // echo '<pre>get_key->'; print_r($dataresult); echo '</pre>';// die();
     $cache_is_supported=$this->cache->file->is_supported();

     if(!$dataresult){
          
          $this->cache->file->save($cachekey,$dataresult,$cachetime);
     /* 
          echo '<pre>A->cache_is_supported->'; print_r($cache_is_supported); echo '</pre>';
          echo '<pre>cachekey->'; print_r($cachekey); echo '</pre>';
          echo '<pre>get_metadata->'; print_r($get_metadata); echo '</pre>';
          echo '<pre>rs dataresult2->'; print_r($dataresult); echo '</pre>'; 
          //die();
     */
     }else{
     /*
          echo '<pre>B->cache_is_supported->'; print_r($cache_is_supported); echo '</pre>';
          echo '<pre>cachekey->'; print_r($cachekey); echo '</pre>';
          echo '<pre>get_metadata->'; print_r($get_metadata); echo '</pre>';
          echo '<pre>rs dataresult2->'; print_r($dataresult); echo '</pre>'; 
     */
          //die();
     }
     

     }
     ###########DB SQL query End ###########


     if(!$dataresult){$this->cache->file->save($cachekey,$dataresult,$cachetime);}
     $cache_info=$this->cache->file->cache_info($cachekey);
               $dataall=array('message'=>'Data form database query save to filecache',
                              'status'=>FALSE, 
                              'list'=>$dataresult,
                              'count'=>(int)count($dataresult),
                              'cachetime'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>$cache_info);                  
     }
     #############################################

     #echo '<pre>  dataall-> '; print_r($dataall); echo '</pre>'; die();
     /* ############# 1-->cachedb  End##############  */  
     return $dataall;
     }
     elseif($cachetype==3){
     $key=$cachekey;
     /* ############# 3-->Redis   Start##############  */  
     #################################################################
     $this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
     $connection_redis=$this->cache->redis->connection();
     $connection=$connection_redis['status'];
     $key=$cachekey;
     $getdata=$this->cache->redis->getkeysdata($key);

     $getkeysdata=$getdata['getdata'];
     if($deletekey==1){
     $deletekeysdata=$this->cache->redis->deletekeysdata($cachekey);
     }else{$deletekeysdata='null';}
     #############################################
     $redis_is_supported=$this->cache->redis->is_supported();
     $file_is_supported=$this->cache->file->is_supported();
     if($redis_is_supported==1 || $file_is_supported==1){
     #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
     #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
     }
     #############################################
     if(!$getkeysdata){ 
     ###########DB SQL query Start##########
     if($sql!==null){
          $query=$this->CI->db->query($sql);
          $dataresult=$query->result();
          #echo '<pre> $dataresult-> '; print_r($dataresult); echo '</pre>'; die();
          #$jsondata=json_encode($dataresult, JSON_NUMERIC_CHECK);
     }else{
          $dataresult=$query_result;  
     }
     ###########DB SQL query End ###########


     $results_data=$this->cache->redis->setkeysdata($cachekey,$dataresult);
     //$redis_data=$results_data;
     $redis_data=$results_data['getdata'];
     $message='form db query data ';
     $status=0;
     ###########DB SQL query End ###########
     }elseif($getkeysdata){ 
     ###########form Redis Start ###########
     $redis_data=$getkeysdata;
     $status=1;
     $message='form Redis cache data';
     ###########form Redis End ###########
     }
     
     $dataall=array('message'=>$message,
                    'Connection'=>$connection,
                    'status'=>$status, 
                    'list'=>$redis_data,
                    'redis_is_supported'=>$redis_is_supported,
                    'file_is_supported'=>$file_is_supported,
                    'list_count'=>count($redis_data),
                    'cachekey'=>$cachekey,
                    );
                                   
                                   
                                   
     #############################################
     /* ############# 3-->Redis  End##############  */    
     return $dataall;
     }
     elseif($cachetype==4){
     $this->CI->load->library('Memcached_library');
     /* ############# 4-->Memory  memcached  Start##############  */  
     ##########*******memcache*******############
     //Load library
     $this->CI->load->library('Memcached_library');     
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
          
     ###########DB SQL query Start##########
     if($sql!==null){
          $query=$this->CI->db->query($sql);
          $resultsdata=$query->result();
     }else{
          $resultsdata=$query_result;  
     }
     ###########DB SQL query End ###########

          // Lets store the results
          //$this->memcached_library->add($cachekey,$resultsdata);
          $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
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
                              #'info'=>$cacheinfo
                              );
     }
     # echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
     ##########*******memcache*******############
     return $dataall;
     #return $resultsdata;
     ##################memcached_result###########################
     /* ############# 4-->Memory  memcached  End##############  */   
     }
     elseif($cachetype==5){
     /* ############# 4-->DB SQL Start##############  */    

     ###########DB SQL query Start##########
     if($sql!==null){
          $query=$this->CI->db->query($sql);
          $dataresult=$query->result();
     }else{
          $dataresult=$query_result;  
     }
     ###########DB SQL query End ###########

     $dataall=array('message'=>'Data form database ',
                    'status'=>FALSE, 
                    'list'=>$dataresult,
                    'count'=>(int)count($dataresult),
                    );
     /* ############# 4-->DB SQL End##############  */  
     return $dataall;  
     }
     ############cache##################
     }
public function cachedbv1($sql,$cachekey,$cachetime,$cachetype,$deletekey){
     ############cache##################
     /*
     echo 'cachedb <hr> sql=> '.$sql; 
     echo '<br>cachekey=>'.$cachekey; 
     echo '<br>cachetime=>'.$cachetime; 
     echo '<br>cachetype=>'.$cachetype; 
     echo '<br>deletekey=>'.$deletekey; 
     echo '<hr>'; #Die();
     */ 
     if($cachetype==null){$cachetype=4;}
     if($cachetype==1){
     /* ############# 1-->cachedb  Start##############  */   
     if($deletekey==1){
     #$deletekeysdata=$this->CI->db->cache_delete($cachekey);
     $this->CI->db->cache_delete_all();
     }  
     ###########DB SQL query Start###########
     // Create Turn caching on
     $this->CI->db->cache_on(); 
     // Turn caching off for this one query
     // $this->CI->db->cache_off();
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     ###########DB SQL query End ###########
               $dataall=array('message'=>'Data form database query save to dbcache',
                              'status'=>FALSE, 
                              'list'=>$dataresult,
                              'count'=>(int)count($dataresult),
                              'cachetime'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>null);                  



     /* ############# 1-->cachedb  End##############  */  
     return $dataall;
     }
     elseif($cachetype==2){
     /* ############# 2-->cachefile  Start##############  */ 
     $this->CI->load->driver('cache');
     $cache_is_supported=$this->cache->file->is_supported();
     if($deletekey==1){
     $deletekeysdata=$this->cache->file->delete($cachekey);
     }else{$deletekeysdata='Null';}
     $path=$this->config->item('cache_path');
     $get_metadata=$this->cache->file->get_metadata($cachekey);
     $dataresult=$this->cache->file->get($cachekey);
     if($dataresult){
               $status_msg='form cache file';
               $cache_info=$this->cache->file->cache_info($cachekey);
               $dataall=array('message'=>'Data form filecache',
                              'status'=>TRUE, 
                                   'list'=>$dataresult,
                                   'count'=>(int)count($dataresult),
                                   'cachetime'=>(int)$cachetime,
                                   'cachekey'=>$cachekey,
                                   'cacheinfo'=>$cache_info);
          
     }elseif(!$dataresult){     
     ###########DB SQL query Start###########
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     ###########DB SQL query End ###########
     if($dataresult){$this->cache->file->save($cachekey,$dataresult,$cachetime);}
     $cache_info=$this->cache->file->cache_info($cachekey);
               $dataall=array('message'=>'Data form database query save to filecache',
                              'status'=>FALSE, 
                              'list'=>$dataresult,
                              'count'=>(int)count($dataresult),
                              'cachetime'=>(int)$cachetime,
                              'cachekey'=>$cachekey,
                              'cacheinfo'=>$cache_info);                  
     }
     #############################################
     /* ############# 1-->cachedb  End##############  */  
     return $dataall;
     }
     elseif($cachetype==3){
     $key=$cachekey;
     /* ############# 3-->Redis   Start##############  */  
     #################################################################
     $this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
     $connection_redis=$this->cache->redis->connection();
     $connection=$connection_redis['status'];
     $key=$cachekey;
     $getdata=$this->cache->redis->getkeysdata($key);

     $getkeysdata=$getdata['getdata'];
     if($deletekey==1){
     $deletekeysdata=$this->cache->redis->deletekeysdata($cachekey);
     }else{$deletekeysdata='Null';}
     #############################################
     $redis_is_supported=$this->cache->redis->is_supported();
     $file_is_supported=$this->cache->file->is_supported();
     if($redis_is_supported==1 || $file_is_supported==1){
     #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
     #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
     }
     #############################################
     if(!$getkeysdata){ 
     ###########DB SQL query Start###########
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     #echo '<pre> $dataresult-> '; print_r($dataresult); echo '</pre>'; die();
     #$jsondata=json_encode($dataresult, JSON_NUMERIC_CHECK);
     $results_data=$this->cache->redis->setkeysdata($cachekey,$dataresult);
     //$redis_data=$results_data;
     $redis_data=$results_data['getdata'];
     $message='form db query data ';
     $status=0;
     ###########DB SQL query End ###########
     }elseif($getkeysdata){ 
     ###########form Redis Start ###########
     $redis_data=$getkeysdata;
     $status=1;
     $message='form Redis cache data';
     ###########form Redis End ###########
     }
     
     $dataall=array('message'=>$message,
                    'Connection'=>$connection,
                    'status'=>$status, 
                    'list'=>$redis_data,
                    'redis_is_supported'=>$redis_is_supported,
                    'file_is_supported'=>$file_is_supported,
                    'list_count'=>count($redis_data),
                    'cachekey'=>$cachekey,
                    );
                                   
                                   
                                   
     #############################################
     /* ############# 3-->Redis  End##############  */    
     return $dataall;
     }
     elseif($cachetype==4){
     $this->CI->load->library('Memcached_library');
     /* ############# 4-->Memory  memcached  Start##############  */  
     ##########*******memcache*******############
     //Load library
     $this->CI->load->library('Memcached_library');     
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
          $query=$this->CI->db->query($sql);
          $resultsdata=$query->result();
          ###########DB SQL query End ###########
          // Lets store the results
          //$this->memcached_library->add($cachekey,$resultsdata);
          $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
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
                              #'info'=>$cacheinfo
                              );
     }
     # echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
     ##########*******memcache*******############
     return $dataall;
     #return $resultsdata;
     ##################memcached_result###########################
     /* ############# 4-->Memory  memcached  End##############  */   
     }
     elseif($cachetype==5){
     /* ############# 4-->DB SQL Start##############  */    
     ########################################################
     ###########DB SQL query Start###########
     $query=$this->CI->db->query($sql);
     $dataresult=$query->result();
     ###########DB SQL query End ###########
     $dataall=array('message'=>'Data form database ',
                    'status'=>FALSE, 
                    'list'=>$dataresult,
                    'count'=>(int)count($dataresult),
                    );
     /* ############# 4-->DB SQL End##############  */  
     return $dataall;  
     }
     ############cache##################
     }
public function session_cookie_get(){
      $this->CI->load->library('session');
      $this->CI->load->helper('cookie','session');
      $data=array('COOKIE'=>$_COOKIE,
                  'SESSION'=>$_SESSION,
                  );
     return $data; 
     }
public function sinin_user($user_idx){
      if($user_idx==null){
            $data='Error user_idx is null';
            return $data; 
            Die();
      }
      $time=60*60*60*24;
      $this->CI->db->cache_off();
      // $this->CI->db->cache_delete_all();
      $this->CI->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
      $this->CI->db->from('tbl_user_2018');
      $this->CI->db->join('sd_user_type', 'sd_user_type.user_type_id=tbl_user_2018.user_type_id');
      $this->CI->db->where('tbl_user_2018.user_idx',$user_idx); 
      $query_get=$this->CI->db->get();
      $num=$query_get->num_rows();
      $query_result=$query_get->result(); 
      $rs=$query_result;
      $dataresult=$rs['0'];
     #echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'</pre>';Die();
     $sesdata=array('userid'=>$dataresult->user_id,
                    'user_id'=>$dataresult->user_idx,
                    'useridx'=>$dataresult->user_idx,
                    'user_idx'=>$dataresult->user_idx,
                    'uname'=>$dataresult->username,
                    'user_name'=>$dataresult->username,
                    'fullname'=>$dataresult->name.' '.$dataresult->surname,
                    'position'=>$dataresult->position,
                    'user_type_id'=>$dataresult->user_type_id,
                    'user_type_name'=>$dataresult->user_type_title,
                    'utype'=>$dataresult->user_type_title,
                    'domain'=>base_url(),
                    );
     #echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'</pre>';Die();
     $this->session_set($sesdata);
     $this->setDataCookie('userid',$dataresult->user_id,$time);
     $this->setDataCookie('user_id',$dataresult->user_idx,$time);
     $this->setDataCookie('useridx',$dataresult->user_idx,$time);
     $this->setDataCookie('user_idx',$dataresult->user_idx,$time);
     $this->setDataCookie('uname',$dataresult->username,$time);
     $this->setDataCookie('user_name',$dataresult->username,$time);
     $this->setDataCookie('fullname',$dataresult->name.' '.$dataresult->surname,$time);
     $this->setDataCookie('user_type_id',$dataresult->user_type_id,$time);
     $this->setDataCookie('user_type_name',$dataresult->user_type_title,$time);
     $this->setDataCookie('utype',$dataresult->user_type_title,$time);
     #####################
     $data=array('COOKIE'=>$_COOKIE,
               'SESSION'=>$_SESSION,
               //'data'=>$dataresult,
               );
     return $data; 
     die();
     /*
     #################################################
     $cookie_userid=@$_COOKIE['userid'];
     $cookie_user_id=@$_COOKIE['user_id'];
     $cookie_useridx=@$_COOKIE['useridx'];
     $cookie_user_idx=@$_COOKIE['user_idx'];
     $cookie_uname=@$_COOKIE['uname'];
     $cookie_user_name=@$_COOKIE['user_name'];
     $cookie_fullname=@$_COOKIE['fullname'];
     $cookie_user_type_id=@$_COOKIE['user_type_id'];
     $cookie_user_type_name=@$_COOKIE['user_type_name'];
     $cookie_utype=@$_COOKIE['utype'];
          $this->deleteCookie('userid',$cookie_userid);
          $this->deleteCookie('user_id',$cookie_user_id);
          $this->deleteCookie('useridx',$cookie_useridx);
          $this->deleteCookie('user_idx',$cookie_user_idx);
          $this->deleteCookie('uname',$cookie_uname);
          $this->deleteCookie('user_name',$cookie_user_name);
          $this->deleteCookie('fullname',$cookie_fullname);
          $this->deleteCookie('user_type_id',$cookie_user_type_id);
          $this->deleteCookie('user_type_name',$cookie_user_type_name);
          $this->deleteCookie('utype',$cookie_utype);
          //session_destroy();
          $this->CI->session->sess_destroy();   
     #################################################
     $data2=array('COOKIE'=>$_COOKIE,
               'SESSION'=>$_SESSION,
               );
     echo'<hr><pre>  data2=>';print_r($data2);echo'</pre>';Die();
     #####################
     */
     }
public function sinout_user($user_idx){
      if($user_idx==null){
            $data='Error user_idx is null';
            return $data; Die();
      }
      $time=60*60*60*24;
      $this->CI->db->cache_off();
      // $this->CI->db->cache_delete_all();
      $this->CI->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
      $this->CI->db->from('tbl_user_2018');
      $this->CI->db->join('sd_user_type', 'sd_user_type.user_type_id=tbl_user_2018.user_type_id');
      $this->CI->db->where('tbl_user_2018.user_idx',$user_idx); 
      $query_get=$this->CI->db->get();
      $num=$query_get->num_rows();
      $query_result=$query_get->result(); 
      $rs=$query_result;
      $dataresult=$rs['0'];
     #echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'</pre>';Die();
     $sesdata=array('userid'=>$dataresult->user_id,
                    'user_id'=>$dataresult->user_idx,
                    'useridx'=>$dataresult->user_idx,
                    'user_idx'=>$dataresult->user_idx,
                    'uname'=>$dataresult->username,
                    'user_name'=>$dataresult->username,
                    'fullname'=>$dataresult->name.' '.$dataresult->surname,
                    'position'=>$dataresult->position,
                    'user_type_id'=>$dataresult->user_type_id,
                    'user_type_name'=>$dataresult->user_type_title,
                    'utype'=>$dataresult->user_type_title,
                    'domain'=>base_url(),
                    );
     #echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'</pre>';Die();
     $this->session_set($sesdata);
     $this->setDataCookie('userid',$dataresult->user_id,$time);
     $this->setDataCookie('user_id',$dataresult->user_idx,$time);
     $this->setDataCookie('useridx',$dataresult->user_idx,$time);
     $this->setDataCookie('user_idx',$dataresult->user_idx,$time);
     $this->setDataCookie('uname',$dataresult->username,$time);
     $this->setDataCookie('user_name',$dataresult->username,$time);
     $this->setDataCookie('fullname',$dataresult->name.' '.$dataresult->surname,$time);
     $this->setDataCookie('user_type_id',$dataresult->user_type_id,$time);
     $this->setDataCookie('user_type_name',$dataresult->user_type_title,$time);
     $this->setDataCookie('utype',$dataresult->user_type_title,$time);
     #####################
     $data=array('COOKIE'=>$_COOKIE,
               'SESSION'=>$_SESSION,
               'data'=>$dataresult,
               );
     echo'<hr><pre>  data=>';print_r($data);echo'</pre>';  Die();
     /*
     #################################################
     $cookie_userid=@$_COOKIE['userid'];
     $cookie_user_id=@$_COOKIE['user_id'];
     $cookie_useridx=@$_COOKIE['useridx'];
     $cookie_user_idx=@$_COOKIE['user_idx'];
     $cookie_uname=@$_COOKIE['uname'];
     $cookie_user_name=@$_COOKIE['user_name'];
     $cookie_fullname=@$_COOKIE['fullname'];
     $cookie_user_type_id=@$_COOKIE['user_type_id'];
     $cookie_user_type_name=@$_COOKIE['user_type_name'];
     $cookie_utype=@$_COOKIE['utype'];
          $this->deleteCookie('userid',$cookie_userid);
          $this->deleteCookie('user_id',$cookie_user_id);
          $this->deleteCookie('useridx',$cookie_useridx);
          $this->deleteCookie('user_idx',$cookie_user_idx);
          $this->deleteCookie('uname',$cookie_uname);
          $this->deleteCookie('user_name',$cookie_user_name);
          $this->deleteCookie('fullname',$cookie_fullname);
          $this->deleteCookie('user_type_id',$cookie_user_type_id);
          $this->deleteCookie('user_type_name',$cookie_user_type_name);
          $this->deleteCookie('utype',$cookie_utype);
          //session_destroy();
          $this->CI->session->sess_destroy();   
     #################################################
     $data2=array('COOKIE'=>$_COOKIE,
               'SESSION'=>$_SESSION,
               );
     echo'<hr><pre>  data2=>';print_r($data2);echo'</pre>';Die();
     #####################
     */
     return $data;
     }
public function session_set($sesdata){
      //echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'</pre>';Die();
      $this->CI->load->library('session');
      $this->CI->session->set_userdata($sesdata);
     } 
public function setDataCookie($cookie_name,$cookie_value,$time){
     $this->CI->load->helper('cookie');
     if($time==null){$time=3600;}
     $cookie=array(
               'name' => $cookie_name,
               'value' => $cookie_value,                            
               'expire' => (time()+$time),      
               'path' => "/",                                                                          
               'secure' =>false
          );
     $cookiert=$this->CI->input->set_cookie($cookie);
     }
public function deleteCookie($cookie_name,$cookie_value){
     $this->CI->load->helper('cookie');  	
     $cookie=array(
               'name' => $cookie_name,
               'value' => $cookie_value,        
               'path' => "/",                                                                           
               'secure' => false
          );
     delete_cookie($cookie);
     return $cookie;
     } 
public function singout(){
     ################################################
     $getcookie=$this->load->model_user->getcookie();  
     ################################################
     $userid_cookie=@$getcookie['userid'];
     if($userid_cookie!==null){$this->deleteCookie('userid',$userid_cookie);}
     $user_id_cookie=@$getcookie['user_id'];
     if($user_id_cookie!==null){$this->deleteCookie('user_id',$user_id_cookie);}
     $useridx_cookie=@$getcookie['useridx'];
     if($useridx_cookie!==null){$this->deleteCookie('useridx',$useridx_cookie);}
     $user_idx_cookie=@$getcookie['user_idx'];
     if($user_idx_cookie!==null){$this->deleteCookie('user_idx',$user_idx_cookie);}
     $uname_cookie=@$getcookie['uname'];
     if($uname_cookie!==null){$this->deleteCookie('uname',$uname_cookie);}
     $user_name_cookie=@$getcookie['user_name'];
     if($user_name_cookie!==null){$this->deleteCookie('user_name',$user_name_cookie);}
     $fullname_cookie=@$getcookie['fullname'];
     if($fullname_cookie!==null){$this->deleteCookie('fullname',$fullname_cookie);}
     $user_type_id_cookie=@$getcookie['user_type_id'];
     if($user_type_id_cookie!==null){$this->deleteCookie('user_type_id',$user_type_id_cookie);}
     $user_type_name_cookie=@$getcookie['user_type_name'];
     if($user_type_name_cookie!==null){$this->deleteCookie('user_type_name',$user_type_name_cookie);}
     $utype_cookie=@$getcookie['utype'];
     if($utype_cookie!==null){$this->deleteCookie('utype',$utype_cookie);}
     ################################################
     //$this->logout();
     ################################################
     //session_destroy();
     $this->session->sess_destroy();    
     }
public function sessdestroy(){
     //session_destroy();
     $this->CI->session->sess_destroy();      
     }
public function fooredirect(){
                    $this->CI->load->helper('url');
                    redirect();
     }
public function barbase_url(){echo $this->CI->config->item('base_url');}
public function menuusertype($user_type_id='',$cache_type='',$deletekey='',$urlnow=''){
	if($cache_type==null){$cache_type=1;     }
	if($user_type_id==null){$user_type_id=@$input['user_type_id'];}
	if($user_type_id==null){
		  $dataall=array('cache_msg'=>'Error  user_type_id is null',
				   'cache_key'=>null,
				   'cachetime'=>null,
				   'cache_day'=>null,
				   'list'=>null,
				  );
		  #echo'<hr><pre>menu_arr=>';print_r($dataall);echo'</pre>';Die();
		  return $dataall; die();
	     }
	$this->CI->load->model('User_model');
     $menuidin=$this->CI->User_model->user_type_id($user_type_id,$deletekey,$cache_type);
      #echo'<hr><pre>menu =>';print_r($menuidin);echo'</pre>';Die();
	$menuidin=$menuidin['rs'];
	if($menuidin==null){
				$dataall=array('cache_msg'=>'Error  user_type_id data base  is null',
				   'cache_key'=>null,
				   'cachetime'=>null,
				   'cache_day'=>null,
				   'list'=>null,
				  );
		  #echo'<hr><pre>menu_arr=>';print_r($dataall);echo'<pre>';Die();
		  return $dataall; die();
	     }
	//echo'<hr><pre> menuidin=>';print_r($menuidin);echo'<pre>'; //die();
	if(is_array($menuidin)) {
          $menuidin_arr=array();
				foreach($menuidin as $k =>$w){
				$ar=array();
					  $ar['b']=$w->menu_id;
					  $menuidin_arr[]=$ar['b'];
					 }                       
				}
	$menu_id_in=$menuidin_arr;
	$menurs=$this->CI->User_model->menubytypeaccess($menu_id_in,$user_type_id,$cache_type,$deletekey);
	$menu=$menurs['rs'];
     #echo'<hr><pre>menu=>';print_r($menu);echo'<pre>';
     /*
	$menu_arr=array();
	if(is_array($menu)) {
	foreach($menu as $key =>$w){
	$arr=array();
	$menu_id=(int)$w->menu_id;
		  $arr['a']['menu_id']=$menu_id;
		  $arr['a']['menu_id2']=$w->menu_id2;
		  $arr['a']['title']=$w->title;
		  $arr['a']['url']=$w->url;
		  $arr['a']['parent']=$w->parent;
		  $arr['a']['menu_alt']=$w->menu_alt;
		  $arr['a']['option']=$w->option;
		  $arr['a']['order_by']=$w->order_by;
		  $arr['a']['order_by2']=$w->order_by2;
		  $arr['a']['icon']=$w->icon;
		  $arr['a']['params']=$w->params;
		  $arr['a']['lang']=$w->lang;
		  #################################
		  $menu2rs=$this->CI->User_model->submenu($menu_id,$cache_type,$deletekey);
		  $menu2=$menu2rs['rs'];
				$menu22_arr=array();
				if(is_array($menu2)) {
				foreach($menu2 as $key2 =>$w2){
				$arr2=array();
				$menu2_id=$w2->menu_id;
					  $arr2['b']['menu_id']=$menu2_id;
					  $arr2['b']['menu_id2']=$w2->menu_id2;
					  $arr2['b']['title']=$w2->title;
					  $arr2['b']['url']=$w2->url;
					  $arr2['b']['parent']=$w2->parent;
					  $arr2['b']['menu_alt']=$w2->menu_alt;
					  $arr2['b']['option']=$w2->option;
					  $arr2['b']['order_by']=$w2->order_by;
					  $arr2['b']['order_by2']=$w2->order_by2;
					  $arr2['b']['icon']=$w2->icon;
					  $arr2['b']['params']=$w2->params;
					  $arr2['b']['lang']=$w2->lang;
					  $submenurs=$this->CI->User_model->submenu($menu2_id,$cache_type,$deletekey);
					  $submenu=$submenurs['rs'];
					  $arr2['b']['submenu']=$submenu;
					  $menu22_arr[]=$arr2['b'];
					 }                       
				}
		  #################################
		  $arr['a']['submenu']=$menu22_arr;
		  $menu_arr[]=$arr['a'];
		 }                       
     }
     */
      ############***********menu all start*********##################
     if(is_array($menu)){
               $access_status=0;
               $access_status_url=0;
               $menu_arr=array();
               foreach($menu as $key =>$w){
               $arr=array();
               $menu_id=(int)$w->menu_id;
                    $arr['a']['menu_id']=(int)$menu_id;
                    $arr['a']['menu_id2']=(int)$w->menu_id2;
                    $arr['a']['title']=$w->title;
                    $arr['a']['current_url']=$urlnow;
                    $urlnow_explode=explode('/',$urlnow);
                    $urlnow_explode_count= count(explode('/',$urlnow));
                    $urlnow_explode_ar_0=@$urlnow_explode['0'];
                    $urlnow_explode_ar_1=@$urlnow_explode['1'];
                    $urlnow_explode_ar_2=@$urlnow_explode['2'];
                    $urlnow_explode_ar_3=@$urlnow_explode['3'];
                    $urlnow_explode_ar_4=@$urlnow_explode['4'];
                    $urlnow_explode_ar_5=@$urlnow_explode['5'];
                    if($urlnow_explode_ar_1!==null){  $urlnow_explode_ar_1=$urlnow_explode['1']; }
                    if($urlnow_explode_ar_2!==null){  $urlnow_explode_ar_2=$urlnow_explode['2']; }
                    if($urlnow_explode_ar_3!==null){  $urlnow_explode_ar_3=$urlnow_explode['3']; }
                    if($urlnow_explode_ar_4!==null){  $urlnow_explode_ar_4=$urlnow_explode['4']; }
                    if($urlnow_explode_ar_5!==null){  $urlnow_explode_ar_5=$urlnow_explode['5']; }
                    $arr['a']['urlnow_explode_ar_0']=$urlnow_explode_ar_0;
                    $arr['a']['urlnow_explode_ar_1']=$urlnow_explode_ar_1;
                    $arr['a']['urlnow_explode_ar_2']=$urlnow_explode_ar_2;
                    $arr['a']['urlnow_explode_ar_3']=$urlnow_explode_ar_3;
                    $arr['a']['urlnow_explode_ar_4']=$urlnow_explode_ar_4;
                    $arr['a']['urlnow_explode_ar_5']=$urlnow_explode_ar_5;
                    $arr['a']['current_url_explode_count']=$urlnow_explode_count;
                    $arr['a']['current_url_explodet']=$urlnow_explode;
                    $arr['a']['url']=$w->url;
                    $url=$w->url;
                    $url_explode=explode('/',$url);
                    $url_explode_count= count(explode('/',$url));
                    if(is_array($url_explode)) {
                         $arn=array(); 
                         foreach($url_explode as $key => $value) {
                                        $arraa['b']['key']=$key;
                                        $arraa['b']['value']=$value;
                                        $arn[]=$arraa['b'];
                         }}
                    $arr['a']['url_explode_ar']=$arn;
                    $url_explode_ar_0=@$url_explode['0'];
                    $url_explode_ar_1=@$url_explode['1'];
                    $url_explode_ar_2=@$url_explode['2'];
                    $url_explode_ar_3=@$url_explode['3'];
                    $url_explode_ar_4=@$url_explode['4'];
                    $url_explode_ar_5=@$url_explode['5'];
                    if($url_explode_ar_1!=null){  $url_explode_ar_1=$url_explode['1']; }
                    if($url_explode_ar_2!=null){  $url_explode_ar_2=$url_explode['2']; }
                    if($url_explode_ar_3!=null){  $url_explode_ar_3=$url_explode['3']; }
                    if($url_explode_ar_4!=null){  $url_explode_ar_4=$url_explode['4']; }
                    if($url_explode_ar_5!=null){  $url_explode_ar_5=$url_explode['5']; }
                    $arr['a']['url_explode_ar_0']=$url_explode_ar_0;
                    $arr['a']['url_explode_ar_1']=$url_explode_ar_1;
                    $arr['a']['url_explode_ar_2']=$url_explode_ar_2;
                    $arr['a']['url_explode_ar_3']=$url_explode_ar_3;
                    $arr['a']['url_explode_ar_4']=$url_explode_ar_4;
                    $arr['a']['url_explode_ar_5']=$url_explode_ar_5;
                    $arr['a']['url_explode']=$url_explode;
                    $arr['a']['url_explode_count']=$url_explode_count;
                    $arr['a']['parent']=$w->parent;
                    $arr['a']['menu_alt']=$w->menu_alt;
                    $menu_alt=$w->menu_alt;
                    $menu_alt_explode=explode(',',$menu_alt);
                    $menu_alt_explode_count=count($menu_alt_explode);
                    if($menu_alt_explode_count==1){$menu_alt_explode_count=0;}
                    $arr['a']['menu_alt_explode_count']=(int)$menu_alt_explode_count;
                    if($menu_alt_explode_count==0){
                         $access_status=2;
                         $access_status_alt=2;
                         $arr['a']['access_status']=(int)$access_status; 
                    }else{
                         if(in_array($user_type_id,$menu_alt_explode)){
                              $access_status=1;
                              $access_status_alt=1;
                              $arr['a']['access_status']=(int)$access_status; 
                         }else{
                              $access_status=0;
                              $access_status_alt=0;
                              $arr['a']['access_status']=(int)$access_status;
                         }  
                    }
                    if($user_type_id==1 ||$user_type_id==2){$access_status_alt=2;}
                    $arr['a']['access_status_alt']=(int)$access_status_alt;
                    if($urlnow_explode_count>=4){  
                         $arr['a']['urlnow_explode_count_num']=4;
                         if($url_explode_ar_3==$urlnow_explode_ar_3){ $access_status_url=1; }elseif($url_explode_ar_1==$urlnow_explode_ar_1){  $access_status_url=1; }else{$access_status_url=0;}
                    }elseif($urlnow_explode_count==3){  
                         $arr['a']['urlnow_explode_count_num']=3;
                         if($url_explode_ar_2==$urlnow_explode_ar_2){ $access_status_url=1; 
                         }elseif($url_explode_ar_1==$urlnow_explode_ar_1){  $access_status_url=1;  }else{$access_status_url=0;}
                    }elseif($urlnow_explode_count==2){  
                         $arr['a']['urlnow_explode_count_num']=2;
                         if($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url=1;  }elseif($url_explode_ar_1==$urlnow_explode_ar_1){  $access_status_url=1;  }else{$access_status_url=0;}
                    }elseif($urlnow_explode_count==1){  
                         $arr['a']['urlnow_explode_count_num']=1;
                         if($url_explode_ar_0==$urlnow_explode_ar_0){ $access_status_url=1; }else{$access_status_url=0;}
                    }else{$access_status_url=0;}
                    if($url_explode_ar_0==$urlnow_explode_ar_0){
                         $access_status=1;
                         $arr['a']['access_status']=$access_status; 
                         $access_status_url=1;
                    }
                    $arr['a']['access_status_url']=$access_status_url;
                    $arr['a']['menu_alt_explode']=$menu_alt_explode; 
                    $arr['a']['user_type_id']=(int)$user_type_id;        
                    $arr['a']['option']=$w->option;
                    $arr['a']['order_by']=$w->order_by;
                    $arr['a']['order_by2']=$w->order_by2;
                    $arr['a']['icon']=$w->icon;
                    $arr['a']['params']=$w->params;
                    $arr['a']['lang']=$w->lang;
                    ############***********sub menu start*********#############
                    $menu2rs=$this->CI->Useraccess_model->submenuv1($menu_id,$cache_type,$user_type_id,$deletekey);
                    $menu2=$menu2rs['rs'];
                    if(is_array($menu2)) {
                         $menu22_arr=array();
                         $submenu_now_arr=array();
                         $arr2=array();
                         $access_status_url_b=0;
                         $access_status_b=0;
                         foreach($menu2 as $key2 =>$w2){
                         $menu2_id=$w2->menu_id;
                              $arr2['b']['menu_id']=$menu2_id;
                              $arr2['b']['menu_id2']=$w2->menu_id2;
                              $arr2['b']['title']=$w2->title;
                              
                              $arr2['b']['current_url']=$urlnow;
                              $urlnow_explode=explode('/',$urlnow);
                              $urlnow_explode_count= count(explode('/',$urlnow));
                              $urlnow_explode_ar_0=@$urlnow_explode['0'];
                              $urlnow_explode_ar_1=@$urlnow_explode['1'];
                              $urlnow_explode_ar_2=@$urlnow_explode['2'];
                              $urlnow_explode_ar_3=@$urlnow_explode['3'];
                              $urlnow_explode_ar_4=@$urlnow_explode['4'];
                              $urlnow_explode_ar_5=@$urlnow_explode['5'];
                              if($urlnow_explode_ar_1!==null){  $urlnow_explode_ar_1=$urlnow_explode['1']; }
                              if($urlnow_explode_ar_2!==null){  $urlnow_explode_ar_2=$urlnow_explode['2']; }
                              if($urlnow_explode_ar_3!==null){  $urlnow_explode_ar_3=$urlnow_explode['3']; }
                              if($urlnow_explode_ar_4!==null){  $urlnow_explode_ar_4=$urlnow_explode['4']; }
                              if($urlnow_explode_ar_5!==null){  $urlnow_explode_ar_5=$urlnow_explode['5']; }
                              $arr2['b']['urlnow_explode_ar_0']=$urlnow_explode_ar_0;
                              $arr2['b']['urlnow_explode_ar_1']=$urlnow_explode_ar_1;
                              $arr2['b']['urlnow_explode_ar_2']=$urlnow_explode_ar_2;
                              $arr2['b']['urlnow_explode_ar_3']=$urlnow_explode_ar_3;
                              $arr2['b']['urlnow_explode_ar_4']=$urlnow_explode_ar_4;
                              $arr2['b']['urlnow_explode_ar_5']=$urlnow_explode_ar_5;
                              if(is_array($urlnow_explode)) {
                                   $arn=array(); 
                                   foreach($urlnow_explode as $key => $value) {
                                                  $arraa['b']['key']=$key;
                                                  $arraa['b']['value']=$value;
                                                  $arn[]=$arraa['b'];
                                   }}
                              $arr2['b']['url_explode_ar']=$arn;
                              $arr2['b']['current_url_explode_count']=$urlnow_explode_count;
                              $arr2['b']['current_url_explodet']=$urlnow_explode;
                              $arr2['b']['url']=$w2->url;
                              $url=$w2->url;
                              $url_explode=explode('/',$url);
                              $url_explode_count= count(explode('/',$url));
                              $url_explode_ar_0=@$url_explode['0'];
                              $url_explode_ar_1=@$url_explode['1'];
                              $url_explode_ar_2=@$url_explode['2'];
                              $url_explode_ar_3=@$url_explode['3'];
                              $url_explode_ar_4=@$url_explode['4'];
                              $url_explode_ar_5=@$url_explode['5'];
                              if($url_explode_ar_1!=null){  $url_explode_ar_1=$url_explode['1']; }
                              if($url_explode_ar_2!=null){  $url_explode_ar_2=$url_explode['2']; }
                              if($url_explode_ar_3!=null){  $url_explode_ar_3=$url_explode['3']; }
                              if($url_explode_ar_4!=null){  $url_explode_ar_4=$url_explode['4']; }
                              if($url_explode_ar_5!=null){  $url_explode_ar_5=$url_explode['5']; }
                              $arr2['b']['url_explode_ar_0']=$url_explode_ar_0;
                              $arr2['b']['url_explode_ar_1']=$url_explode_ar_1;
                              $arr2['b']['url_explode_ar_2']=$url_explode_ar_2;
                              $arr2['b']['url_explode_ar_3']=$url_explode_ar_3;
                              $arr2['b']['url_explode_ar_4']=$url_explode_ar_4;
                              $arr2['b']['url_explode_ar_5']=$url_explode_ar_5;
                              $arr2['b']['url_explode']=$url_explode;
                              $arr2['b']['url_explode_count']=$url_explode_count;
                              $arr2['b']['parent']=$w2->parent;
                              $arr2['b']['menu_alt']=$w2->menu_alt;       
                              $arr2['b']['user_type_id']=(int)$user_type_id;         
                              $menu_alt_sub=$w2->menu_alt;           
                              $menu_alt_explode_sub=explode(',',$menu_alt_sub); 
                              $menu_alt_explode_count_sub=count($menu_alt_explode_sub);
                              if($menu_alt_explode_count_sub==1){$menu_alt_explode_count_sub=0;}
                              $arr2['b']['menu_alt_explode_count']=$menu_alt_explode_count_sub;
                              $arr2['b']['menu_alt_explode']=$menu_alt_explode_sub;
                              if($menu_alt_explode_count_sub==0){
                                   $access_status_b=2;
                                   $access_status_b_alt=2;
                                   $arr2['b']['access_status']=(int)$access_status_b; 
                              }else{
                                   if(in_array($user_type_id,$menu_alt_explode_sub)){
                                        $access_status_b=1;
                                        $access_status_b_alt=1;
                                        $arr2['b']['access_status']=(int)$access_status_b; 
                                   }else{
                                        $access_status_b=0;
                                        $access_status_b_alt=0;
                                        $arr2['b']['access_status']=(int)$access_status_b; 
                                        if($user_type_id==1 ||$user_type_id==2){$access_status_b_alt=1;}
                                   }  
                              }
                              
                              $arr2['b']['access_status_alt']=(int)$access_status_b_alt;
                              if($urlnow_explode_count>=4){  
                                   $arr2['b']['urlnow_explode_count_num']=4;
                                   if($url_explode_ar_3==$urlnow_explode_ar_3){ $access_status_url_b=1;  }elseif($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url_b=1;  }else{$access_status_url_b=0;}
                              }elseif($urlnow_explode_count==3){  
                                   $arr2['b']['urlnow_explode_count_num']=3;
                                   if($url_explode_ar_2==$urlnow_explode_ar_2){ $access_status_url_b=1;  }elseif($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url_b=1; }else{$access_status_url_b=0;}
                              }elseif($urlnow_explode_count==2){  
                                   $arr2['b']['urlnow_explode_count_num']=2;
                                   if($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url_b=1; }else{$access_status_url=0;}
                              }elseif($urlnow_explode_count==1){  
                                   $arr2['b']['urlnow_explode_count_num']=1;
                                   if($url_explode_ar_0==$urlnow_explode_ar_0){ $access_status_url_b=1; }else{$access_status_url=0;}
                              }else{$access_status_url_b=0;}
                              if($menu_alt==null){ 
                                   if($url_explode_ar_0==$urlnow_explode_ar_0){
                                        $access_status_b=1;
                                        $arr2['b']['access_status']=(int)$access_status_b; 
                                        $access_status_url_b=1;
                                   }else{
                                        $access_status_b=0;
                                        $arr2['b']['access_status']=(int)$access_status_b; 
                                        $access_status_url_b=0;
                                   }
                              }else{
                                        if(in_array($user_type_id,$menu_alt_explode)){
                                             if($url_explode_ar_0==$urlnow_explode_ar_0){
                                                  $arr2['b']['access_status']=1; 
                                                  $access_status_url_b=1;
                                             }
                                        }else{
                                             $arr2['b']['access_status']=0;
                                             $access_status_url_b=0;
                                        }  

                              }
                              $arr2['b']['access_status_url']=$access_status_url_b;
                              #echo'<pre> menu_alt_explode =>';print_r($menu_alt_explode);echo'</pre>'; 
                              $arr2['b']['option']=$w2->option;
                              $arr2['b']['order_by']=$w2->order_by;
                              $arr2['b']['order_by2']=$w2->order_by2;
                              $arr2['b']['icon']=$w2->icon;
                              $arr2['b']['params']=$w2->params;
                              $arr2['b']['lang']=$w2->lang;
                             # echo'<pre> deletekey =>';print_r($deletekey);echo'</pre>'; die();
                                   #$submenurs=$this->CI->Useraccess_model->submenu($menu2_id,$cache_type,$deletekey); 
                                   $submenurs=$this->CI->Useraccess_model->submenuv1($menu2_id,$cache_type,$user_type_id,$deletekey);
                                   $submenu=$submenurs['rs'];
                                   $arr2['b']['submenu']=$submenu;
                            $menu22_arr[]=$arr2['b'];   
                                   $submenu_now_arr[]=$arr2['b'];
                              }                       
                         }
                    ############***********sub menu start*********#############
                    $arr['a']['submenu_count']=count($menu22_arr);
                    $arr['a']['submenu']=$menu22_arr;
                    if($submenu_now_arr==''){$submenu_now_arr=null;}
                    $arr['a']['submenu_now']=$submenu_now_arr;
                    $menu_arr[]=$arr['a'];
               }                       
          }
     ############***********menu all end*********##################
	$cache_msg=$menurs['cache_msg'];
	$cache_key=$menurs['cache_key'];
	$cachetime=$menurs['cachetime'];
	$cache_day=$menurs['cache_day'];
	$dataall=array('cache_msg'=>$cache_msg,
				   'cache_key'=>$cache_key,
				   'cachetime'=>$cachetime,
				   'cache_day'=>$cache_day,
				   'list'=>$menu_arr,
				  );
	return $dataall; 
     }
public function menuusertypev2($user_type_id='',$cache_type='',$deletekey='',$urlnow='',$user_idx='',$menu_id_active='',$debug=''){
     /*
          echo'<hr><pre> user_type_id=>';print_r($user_type_id);echo'</pre>'; 
          echo'<pre> cache_type=>';print_r($cache_type);echo'</pre>'; 
          echo'<pre> deletekey=>';print_r($deletekey);echo'</pre>'; 
          echo'<pre> urlnow=>';print_r($urlnow);echo'</pre>'; 
          echo'<pre> user_idx=>';print_r($user_idx);echo'</pre>'; 
          echo'<pre> menu_id_active=>';print_r($menu_id_active);echo'</pre><hr>'; die();
     */
     if($deletekey==null){$deletekey=0;}
     if($cache_type==null){$cache_type=1;}
     if($user_type_id==null){$user_type_id=@$input['user_type_id'];}
     if($user_type_id==null){
          $dataall=array('cache_msg'=>'Error  user_type_id is null',
                    'cache_key'=>null,
                    'cachetime'=>null,
                    'cache_day'=>null,
                    'list'=>null,
               );
               #echo'<hr><pre>menu_arr=>';print_r($dataall);echo'</pre>';Die();
               return $dataall; die();
          }
     $access_status=0;
     $access_status_url=0;
     $this->CI->load->model('Useraccess_model','',TRUE);
     $menuidins=$this->CI->Useraccess_model->user_type_id($user_type_id,$deletekey,$cache_type);
     $menuidin=$menuidins['rs'];  
     $menuidins_last_sql_query=$menuidins['last_query'];  
     #echo'<hr><pre>last_sql_query=>';print_r($last_sql_query);echo'</pre>'; 
     #echo'<pre>menuidin=>';print_r($menuidin);echo'</pre>'; 
    # echo'<hr><pre>menuidin=>';print_r($menuidin);echo'</pre>';Die();
     if($menuidin==null){
               $dataall=array('cache_msg'=>'Error  user_type_id data base  is null',
                    'cache_key'=>null,
                    'cachetime'=>null,
                    'cache_day'=>null,
                    'list'=>null,
               );
               #echo'<hr><pre>menu_arr=>';print_r($dataall);echo'</pre>';Die();
               return $dataall; die();
          }
     #echo'<hr><pre> menuidin=>';print_r($menuidin);echo'</pre>'; die();
     if(is_array($menuidin)){
               $menuidin_arr=array();
               foreach($menuidin as $k =>$w){
               $ar=array();
                    $ar['b']=$w->menu_id;
                    $menuidin_arr[]=$ar['b'];
                    }                       
               }
          $menu_id_in=$menuidin_arr;
     /* 
     echo'<hr><pre> menuidin_arr=>';print_r($menuidin_arr);echo'</pre>'; //die();
     echo'<hr><pre> user_type_id=>';print_r($user_type_id);echo'</pre>';
     echo'<hr><pre> menu_id_in=>';print_r($menu_id_in);echo'</pre>'; 
      */
      ############***********menu*********##################
     $menurs=$this->CI->Useraccess_model->menubytypeaccess($menu_id_in,$user_type_id,$cache_type,$deletekey);
     $menu=$menurs['rs'];
     #echo'<hr><pre>menu=>';print_r($menu);echo'</pre>';
     ############***********menu all start*********################## 
     /* $menu_arr */
     
     ##########################################################################
     $cachekey="key_menuusertypev2_user_type_id-".$user_type_id;
     $cachetime=60*60*60*365*1;
     $cache_day=$cachetime/(60*60*60*1);
     //cachefile 
     $cachetype='2'; 
     $this->CI=& get_instance(); 
     $this->CI->load->model('Cachtooluser_model');
     $sql=null;
     $dir='usertype';
     $cachechk=$this->CI->Cachtooluser_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
     $cachechklist=$cachechk['list'];
     #############Cache start#################
     if($cachechklist==''){
          $dataresult='na';
            $this->CI->load->model('Cachtooluser_model');
            $sql=null;
            $dir='usertype';
               ############################$menu_arr##################################
               if(is_array($menu)){
                    $menu_arr=array();
                    foreach($menu as $key =>$w){
                    $arr=array();
                    $menu_id=(int)$w->menu_id;
                         $arr['a']['menu_id']=(int)$menu_id;
                         $arr['a']['menu_id2']=(int)$w->menu_id2;
                         if($menu_id_active==null){$menu_active=0;}elseif($menu_id_active==$menu_id){$menu_active=1;}else{$menu_active=0;}
                         $arr['a']['menu_active']=$menu_active;  
                         $arr['a']['title']=$w->title;
                         $arr['a']['user_idx']=$user_idx;
                         $arr['a']['current_url']=$urlnow;
                         $urlnow_explode=explode('/',$urlnow);
                         $urlnow_explode_count= count(explode('/',$urlnow));
                         $urlnow_explode_ar_0=@$urlnow_explode['0'];
                         $urlnow_explode_ar_1=@$urlnow_explode['1'];
                         $urlnow_explode_ar_2=@$urlnow_explode['2'];
                         $urlnow_explode_ar_3=@$urlnow_explode['3'];
                         $urlnow_explode_ar_4=@$urlnow_explode['4'];
                         $urlnow_explode_ar_5=@$urlnow_explode['5'];
                         if($urlnow_explode_ar_0!==null){  $urlnow_explode_ar_0=$urlnow_explode['0']; }
                         if($urlnow_explode_ar_1!==null){  $urlnow_explode_ar_1=$urlnow_explode['1']; }
                         if($urlnow_explode_ar_2!==null){  $urlnow_explode_ar_2=$urlnow_explode['2']; }
                         if($urlnow_explode_ar_3!==null){  $urlnow_explode_ar_3=$urlnow_explode['3']; }
                         if($urlnow_explode_ar_4!==null){  $urlnow_explode_ar_4=$urlnow_explode['4']; }
                         if($urlnow_explode_ar_5!==null){  $urlnow_explode_ar_5=$urlnow_explode['5']; }
                         /*
                         $arr['a']['urlnow_explode_ar_0']=$urlnow_explode_ar_0;
                         $arr['a']['urlnow_explode_ar_1']=$urlnow_explode_ar_1;
                         $arr['a']['urlnow_explode_ar_2']=$urlnow_explode_ar_2;
                         $arr['a']['urlnow_explode_ar_3']=$urlnow_explode_ar_3;
                         $arr['a']['urlnow_explode_ar_4']=$urlnow_explode_ar_4;
                         $arr['a']['urlnow_explode_ar_5']=$urlnow_explode_ar_5;
                         */
                         $arr['a']['current_url_explode_count']=$urlnow_explode_count;
                         $arr['a']['current_url_explodet']=$urlnow_explode;
                         $arr['a']['url']=$w->url;
                         $url=$w->url;
                         $url_explode=explode('/',$url);
                         $url_explode_count= count(explode('/',$url));
                         if(is_array($url_explode)) {
                              $arn=array(); 
                              foreach($url_explode as $key => $value) {
                                             $arraa['b']['key']=$key;
                                             $arraa['b']['value']=$value;
                                             $arn[]=$arraa['b'];
                              }}
                         $arr['a']['url_explode_ar']=$arn;
                         $url_explode_ar_0=@$url_explode['0'];
                         $url_explode_ar_1=@$url_explode['1'];
                         $url_explode_ar_2=@$url_explode['2'];
                         $url_explode_ar_3=@$url_explode['3'];
                         $url_explode_ar_4=@$url_explode['4'];
                         $url_explode_ar_5=@$url_explode['5'];
                         if($url_explode_ar_0!=null){  $url_explode_ar_0=$url_explode['0']; }
                         if($url_explode_ar_1!=null){  $url_explode_ar_1=$url_explode['1']; }
                         if($url_explode_ar_2!=null){  $url_explode_ar_2=$url_explode['2']; }
                         if($url_explode_ar_3!=null){  $url_explode_ar_3=$url_explode['3']; }
                         if($url_explode_ar_4!=null){  $url_explode_ar_4=$url_explode['4']; }
                         if($url_explode_ar_5!=null){  $url_explode_ar_5=$url_explode['5']; }
                         $arr['a']['url_explode_ar_0']=$url_explode_ar_0;
                         $arr['a']['url_explode_ar_1']=$url_explode_ar_1;
                         $arr['a']['url_explode_ar_2']=$url_explode_ar_2;
                         $arr['a']['url_explode_ar_3']=$url_explode_ar_3;
                         $arr['a']['url_explode_ar_4']=$url_explode_ar_4;
                         $arr['a']['url_explode_ar_5']=$url_explode_ar_5;
                         $arr['a']['url_explode']=$url_explode;
                         $arr['a']['url_explode_count']=$url_explode_count;
                         $arr['a']['parent']=$w->parent;
                         $arr['a']['menu_alt']=$w->menu_alt;
                         $menu_alt=$w->menu_alt;
                         $menu_alt_explode=explode(',',$menu_alt);
                         $menu_alt_explode_count=count($menu_alt_explode);
                         if($menu_alt_explode_count==1){$menu_alt_explode_count=0;}
                         $arr['a']['menu_alt_explode_count']=(int)$menu_alt_explode_count;
                         if($menu_alt_explode_count==0){
                              # $access_status=1; $access_status_alt=1; 
                              $access_status_alt=0;
                              $arr['a']['access_status']=(int)$access_status; 
                         }else{
                              if(in_array($user_type_id,$menu_alt_explode)){
                                   $access_status=1;
                                   $access_status_alt=1;
                                   $arr['a']['access_status']=(int)$access_status; 
                              }else{
                                   $access_status=0;
                                   $access_status_alt=0;
                                   $arr['a']['access_status']=(int)$access_status;
                              }  
                         }
                         if($user_type_id==1 ||$user_type_id==2){$access_status_alt=2;}
                         $arr['a']['access_status_alt']=(int)$access_status_alt;
                         if($urlnow_explode_count>=4){  
                              $arr['a']['urlnow_explode_count_num']=4;
                              if($url_explode_ar_3==$urlnow_explode_ar_3){ $access_status_url=1; }elseif($url_explode_ar_1==$urlnow_explode_ar_1){  $access_status_url=1; }else{$access_status_url=0;}
                         }elseif($urlnow_explode_count==3){  
                              $arr['a']['urlnow_explode_count_num']=3;
                              if($url_explode_ar_2==$urlnow_explode_ar_2){ $access_status_url=1; 
                              }elseif($url_explode_ar_1==$urlnow_explode_ar_1){  $access_status_url=1;  }else{$access_status_url=0;}
                         }elseif($urlnow_explode_count==2){  
                              $arr['a']['urlnow_explode_count_num']=2;
                              if($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url=1;  }elseif($url_explode_ar_1==$urlnow_explode_ar_1){  $access_status_url=1;  }else{$access_status_url=0;}
                         }elseif($urlnow_explode_count==1){  
                              $arr['a']['urlnow_explode_count_num']=1;
                              if($url_explode_ar_0==$urlnow_explode_ar_0){ $access_status_url=1; }else{$access_status_url=0;}
                         }else{$access_status_url=0;}
                         if($url_explode_ar_0==$urlnow_explode_ar_0){
                              $access_status=1;
                              $arr['a']['access_status']=$access_status; 
                              $access_status_url=1;
                         }
                         $arr['a']['access_status_url']=$access_status_url;
                         $arr['a']['menu_alt_explode']=$menu_alt_explode; 
                         $arr['a']['user_type_id']=(int)$user_type_id;        
                         $arr['a']['option']=$w->option;
                         $arr['a']['order_by']=$w->order_by;
                         $arr['a']['order_by2']=$w->order_by2;
                         $arr['a']['icon']=$w->icon;
                         $arr['a']['params']=$w->params;
                         $arr['a']['lang']=$w->lang;
                         ############***********sub menu start*********#############
                         $menu2rs=$this->CI->Useraccess_model->submenuv1($menu_id,$cache_type,$user_type_id,$deletekey);
                         $menu2=$menu2rs['rs'];
                         if(is_array($menu2)) {
                              $menu22_arr=array();
                              $submenu_now_arr=array();
                              $arr2=array();
                              $access_status_url_b=0;
                              $access_status_b=0;
                              foreach($menu2 as $key2 =>$w2){
                              $menu2_id=$w2->menu_id;
                                   $arr2['b']['menu_id']=$menu2_id;
                                   $arr2['b']['menu_id2']=$w2->menu_id2;
                                   if($menu_id_active==null){$menu_active=0;}elseif($menu_id_active==$menu2_id){$menu_active=1;}else{$menu_active=0;}
                                   $arr2['b']['menu_active']=$menu_active;
                                   $arr2['b']['title']=$w2->title;
                                   $arr2['b']['user_idx']=$user_idx;
                                   $arr2['b']['current_url']=$urlnow;
                                   $urlnow_explode=explode('/',$urlnow);
                                   $urlnow_explode_count= count(explode('/',$urlnow));
                                   $urlnow_explode_ar_0=@$urlnow_explode['0'];
                                   $urlnow_explode_ar_1=@$urlnow_explode['1'];
                                   $urlnow_explode_ar_2=@$urlnow_explode['2'];
                                   $urlnow_explode_ar_3=@$urlnow_explode['3'];
                                   $urlnow_explode_ar_4=@$urlnow_explode['4'];
                                   $urlnow_explode_ar_5=@$urlnow_explode['5'];
                                   if($urlnow_explode_ar_0!==null){  $urlnow_explode_ar_0=$urlnow_explode['0']; }
                                   if($urlnow_explode_ar_1!==null){  $urlnow_explode_ar_1=$urlnow_explode['1']; }
                                   if($urlnow_explode_ar_2!==null){  $urlnow_explode_ar_2=$urlnow_explode['2']; }
                                   if($urlnow_explode_ar_3!==null){  $urlnow_explode_ar_3=$urlnow_explode['3']; }
                                   if($urlnow_explode_ar_4!==null){  $urlnow_explode_ar_4=$urlnow_explode['4']; }
                                   if($urlnow_explode_ar_5!==null){  $urlnow_explode_ar_5=$urlnow_explode['5']; }
                                   /*
                                   $arr2['b']['urlnow_explode_ar_0']=$urlnow_explode_ar_0;
                                   $arr2['b']['urlnow_explode_ar_1']=$urlnow_explode_ar_1;
                                   $arr2['b']['urlnow_explode_ar_2']=$urlnow_explode_ar_2;
                                   $arr2['b']['urlnow_explode_ar_3']=$urlnow_explode_ar_3;
                                   $arr2['b']['urlnow_explode_ar_4']=$urlnow_explode_ar_4;
                                   $arr2['b']['urlnow_explode_ar_5']=$urlnow_explode_ar_5;
                                   */
                                   if(is_array($urlnow_explode)) {
                                        $arn=array(); 
                                        foreach($urlnow_explode as $key => $value) {
                                                       $arraa['b']['key']=$key;
                                                       $arraa['b']['value']=$value;
                                                       $arn[]=$arraa['b'];
                                        }}
                                   $arr2['b']['url_explode_ar']=$arn;
                                   $arr2['b']['current_url_explode_count']=$urlnow_explode_count;
                                   $arr2['b']['current_url_explodet']=$urlnow_explode;
                                   $arr2['b']['url']=$w2->url;
                                   $url=$w2->url;
                                   $url_explode=explode('/',$url);
                                   $url_explode_count= count(explode('/',$url));
                                   $url_explode_ar_0=@$url_explode['0'];
                                   $url_explode_ar_1=@$url_explode['1'];
                                   $url_explode_ar_2=@$url_explode['2'];
                                   $url_explode_ar_3=@$url_explode['3'];
                                   $url_explode_ar_4=@$url_explode['4'];
                                   $url_explode_ar_5=@$url_explode['5'];
                                   if($url_explode_ar_0!=null){  $url_explode_ar_0=$url_explode['0']; }
                                   if($url_explode_ar_1!=null){  $url_explode_ar_1=$url_explode['1']; }
                                   if($url_explode_ar_2!=null){  $url_explode_ar_2=$url_explode['2']; }
                                   if($url_explode_ar_3!=null){  $url_explode_ar_3=$url_explode['3']; }
                                   if($url_explode_ar_4!=null){  $url_explode_ar_4=$url_explode['4']; }
                                   if($url_explode_ar_5!=null){  $url_explode_ar_5=$url_explode['5']; }
                                   $arr2['b']['url_explode_ar_0']=$url_explode_ar_0;
                                   $arr2['b']['url_explode_ar_1']=$url_explode_ar_1;
                                   $arr2['b']['url_explode_ar_2']=$url_explode_ar_2;
                                   $arr2['b']['url_explode_ar_3']=$url_explode_ar_3;
                                   $arr2['b']['url_explode_ar_4']=$url_explode_ar_4;
                                   $arr2['b']['url_explode_ar_5']=$url_explode_ar_5;
                                   $arr2['b']['url_explode']=$url_explode;
                                   $arr2['b']['url_explode_count']=$url_explode_count;
                                   $arr2['b']['parent']=$w2->parent;
                                   $arr2['b']['menu_alt']=$w2->menu_alt;       
                                   $arr2['b']['user_type_id']=(int)$user_type_id;         
                                   $menu_alt_sub=$w2->menu_alt;           
                                   $menu_alt_explode_sub=explode(',',$menu_alt_sub); 
                                   $menu_alt_explode_count_sub=count($menu_alt_explode_sub);
                                   if($menu_alt_explode_count_sub==1){$menu_alt_explode_count_sub=0;}
                                   $arr2['b']['menu_alt_explode_count']=$menu_alt_explode_count_sub;
                                   $arr2['b']['menu_alt_explode']=$menu_alt_explode_sub;
                                   if($menu_alt_explode_count_sub==0){
                                        $access_status_b=2;
                                        $access_status_b_alt=2;
                                        $arr2['b']['access_status']=(int)$access_status_b; 
                                   }else{
                                        if(in_array($user_type_id,$menu_alt_explode_sub)){
                                             $access_status_b=1;
                                             $access_status_b_alt=1;
                                             $arr2['b']['access_status']=(int)$access_status_b; 
                                        }else{
                                             $access_status_b=0;
                                             $access_status_b_alt=0;
                                             $arr2['b']['access_status']=(int)$access_status_b; 
                                             if($user_type_id==1 ||$user_type_id==2){$access_status_b_alt=1;}
                                        }  
                                   }
                                   
                                   $arr2['b']['access_status_alt']=(int)$access_status_b_alt;
                                   if($urlnow_explode_count>=4){  
                                        $arr2['b']['urlnow_explode_count_num']=4;
                                        if($url_explode_ar_3==$urlnow_explode_ar_3){ $access_status_url_b=1;  }elseif($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url_b=1;  }else{$access_status_url_b=0;}
                                   }elseif($urlnow_explode_count==3){  
                                        $arr2['b']['urlnow_explode_count_num']=3;
                                        if($url_explode_ar_2==$urlnow_explode_ar_2){ $access_status_url_b=1;  }elseif($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url_b=1; }else{$access_status_url_b=0;}
                                   }elseif($urlnow_explode_count==2){  
                                        $arr2['b']['urlnow_explode_count_num']=2;
                                        if($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url_b=1; }else{$access_status_url=0;}
                                   }elseif($urlnow_explode_count==1){  
                                        $arr2['b']['urlnow_explode_count_num']=1;
                                        if($url_explode_ar_0==$urlnow_explode_ar_0){ $access_status_url_b=1; }else{$access_status_url=0;}
                                   }else{$access_status_url_b=0;}
                                   if($menu_alt==null){ 
                                        if($url_explode_ar_0==$urlnow_explode_ar_0){
                                             $access_status_b=1;
                                             $arr2['b']['access_status']=(int)$access_status_b; 
                                             $access_status_url_b=1;
                                        }else{
                                             $access_status_b=0;
                                             $arr2['b']['access_status']=(int)$access_status_b; 
                                             $access_status_url_b=0;
                                        }
                                   }else{
                                             if(in_array($user_type_id,$menu_alt_explode)){
                                                  if($url_explode_ar_0==$urlnow_explode_ar_0){
                                                       $arr2['b']['access_status']=1; 
                                                       $access_status_url_b=1;
                                                  }
                                             }else{
                                                  $arr2['b']['access_status']=0;
                                                  $access_status_url_b=0;
                                             }  

                                   }
                                   $arr2['b']['access_status_url']=$access_status_url_b;
                                   #echo'<pre> menu_alt_explode =>';print_r($menu_alt_explode);echo'</pre>'; 
                                   $arr2['b']['option']=$w2->option;
                                   $arr2['b']['order_by']=$w2->order_by;
                                   $arr2['b']['order_by2']=$w2->order_by2;
                                   $arr2['b']['icon']=$w2->icon;
                                   $arr2['b']['params']=$w2->params;
                                   $arr2['b']['lang']=$w2->lang;
                                   # $submenurs=$this->CI->Useraccess_model->submenu($menu2_id,$cache_type,$deletekey);
                                   $submenurs=$this->CI->Useraccess_model->submenuv1($menu2_id,$cache_type,$user_type_id,$deletekey);
                                        $submenu=$submenurs['rs'];
                                        $arr2['b']['submenu']=$submenu;
                                        $menu22_arr[]=$arr2['b'];  
                                   #if($access_status_url==1){}
                                   #if($access_status==1){}
                                        
                                        if($access_status_b_alt==1){}else{ $submenu_now_arr[]=$arr2['b'];} 
                                   }                       
                              }
                         ############***********sub menu start*********#############
                         $submenu_count=(int)count($menu22_arr);
                         $arr['a']['submenu_count']=$submenu_count;

                         if($submenu_count==0){
                         # $accesse_url_arr_in_current_status=1;
                         # $access_status=1; 
                         }
                         
                              $arr['a']['submenu']=$menu22_arr;
                              if($submenu_now_arr==''){$submenu_now_arr=null;}
                              $arr['a']['submenu_now']=$submenu_now_arr;
                              $menu_arr[]=$arr['a'];
                         }                       
                    }
               ############################$menu_arr##################################
            $cacheset=$this->CI->Cachtooluser_model->cachedbsetkey($sql,$menu_arr,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
            //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
            $cache_msg=$cacheset['message'];
            $datacache=$cacheset['list'];
            $num=count($datacache);
            $menu_arr=$datacache;
          }else{ $menu_arr=$cachechklist; }
    #############Cache start#################
    # echo'<hr><pre>datacache=>';print_r($datacache);echo'</pre>';
     ##########################################################################
     ############***********menu all end*********##################
     ############***********menumain start*********#############
     if(is_array($menu)) {
          $menu_main_arr=null;
          $menu_main_arr=array();
         foreach($menu as $key =>$w){
                 $arr=array();
                 $menu_id=(int)$w->menu_id;
                     $arr['menumain']['menu_id']=$menu_id;
                     $arr['menumain']['menu_id2']=$w->menu_id2;
                     $arr['menumain']['title']=$w->title;
                     $arr['menumain']['user_type_id']=$user_type_id;
                     $arr['menumain']['user_idx']=$user_idx;
                     $arr['menumain']['current_url']=$urlnow;
                     $urlnow_explode=explode('/',$urlnow);
                     $urlnow_explode_count= count(explode('/',$urlnow));
                     $urlnow_explode_ar_0=@$urlnow_explode['0'];
                     $urlnow_explode_ar_1=@$urlnow_explode['1'];
                     $urlnow_explode_ar_2=@$urlnow_explode['2'];
                     $urlnow_explode_ar_3=@$urlnow_explode['3'];
                     if($urlnow_explode_ar_0!==null){  $urlnow_explode_ar_0=$urlnow_explode['0']; }
                     if($urlnow_explode_ar_1!==null){  $urlnow_explode_ar_1=$urlnow_explode['1']; }
                     if($urlnow_explode_ar_2!==null){  $urlnow_explode_ar_2=$urlnow_explode['2']; }
                     if($urlnow_explode_ar_3!==null){  $urlnow_explode_ar_3=$urlnow_explode['3']; }
                     $arr['menumain']['urlnow_explode_ar_0']=$urlnow_explode_ar_0;
                     $arr['menumain']['urlnow_explode_ar_1']=$urlnow_explode_ar_1;
                     $arr['menumain']['urlnow_explode_ar_2']=$urlnow_explode_ar_2;
                     $arr['menumain']['urlnow_explode_ar_3']=$urlnow_explode_ar_3;
                     $arr['menumain']['current_url_explode_count']=$urlnow_explode_count;
                     $arr['menumain']['current_url_explodet']=$urlnow_explode;
                     $arr['menumain']['url']=$w->url;
                     $url=$w->url;
                     $url_explode=explode('/',$url);
                     $url_explode_count= count(explode('/',$url));
                     if(is_array($url_explode)) {
                         $arn=array(); 
                         foreach($url_explode as $key => $value) {
                                         $arraa['b']['key']=$key;
                                         $arraa['b']['value']=$value;
                                         $arn[]=$arraa['b'];
                         }}
                     $arr['menumain']['url_explode_ar']=$arn;
                     $url_explode_ar_0=@$url_explode['0'];
                     $url_explode_ar_1=@$url_explode['1'];
                     $url_explode_ar_2=@$url_explode['2'];
                     $url_explode_ar_3=@$url_explode['3'];
                     if($url_explode_ar_0!=null){  $url_explode_ar_0=$url_explode['0']; }
                     if($url_explode_ar_1!=null){  $url_explode_ar_1=$url_explode['1']; }
                     if($url_explode_ar_2!=null){  $url_explode_ar_2=$url_explode['2']; }
                     if($url_explode_ar_3!=null){  $url_explode_ar_3=$url_explode['3']; }
                     $arr['menumain']['url_explode_ar_0']=$url_explode_ar_0;
                     $arr['menumain']['url_explode_ar_1']=$url_explode_ar_1;
                     $arr['menumain']['url_explode_ar_2']=$url_explode_ar_2;
                     $arr['menumain']['url_explode_ar_3']=$url_explode_ar_3;
                     $arr['menumain']['url_explode']=$url_explode;
                     $arr['menumain']['url_explode_count']=$url_explode_count;
                     $arr['menumain']['parent']=$w->parent;
                     $arr['menumain']['menu_alt']=$w->menu_alt;
                     $menu_alt=$w->menu_alt;
                     $menu_alt_explode=explode(',',$menu_alt);
                     $arr['menumain']['menu_alt_explode_count']=count($menu_alt_explode);
                     if($menu_alt==null){
                         $arr['menumain']['access_status']='2'; 
                     }else{
                         if(in_array($user_type_id,$menu_alt_explode)){
                                 $access_status=1; 
                                 $arr['menumain']['access_status']=$access_status; 
                         }else{
                                 $access_status=0; 
                                 $arr['menumain']['access_status']=$access_status; 
                         }  
                     }
                     if($urlnow_explode_count>=4){  
                         $arr['menumain']['urlnow_explode_count_num']=4;
                         if($url_explode_ar_3==$urlnow_explode_ar_3){ $access_status_url=1;}elseif($url_explode_ar_1==$urlnow_explode_ar_1){  $access_status_url=1;  }else{$access_status_url=0;}
                     }elseif($urlnow_explode_count==3){  
                         $arr['menumain']['urlnow_explode_count_num']=3;
                         if($url_explode_ar_2==$urlnow_explode_ar_2){ $access_status_url=1;  }elseif($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url=1; }else{$access_status_url=0;}
                     }elseif($urlnow_explode_count==2){  
                         $arr['menumain']['urlnow_explode_count_num']=2;
                         if($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url=1; }elseif($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url=1;  }else{$access_status_url=0;}
                     }elseif($urlnow_explode_count==1){  
                         $arr['menumain']['urlnow_explode_count_num']=1;
                         if($url_explode_ar_0==$urlnow_explode_ar_0){ $access_status_url=1; }else{$access_status_url=0;}
                     }else{$access_status_url=0;}
                     if($url_explode_ar_0==$urlnow_explode_ar_0){
                         $arr['menumain']['access_status']='1'; 
                         $access_status_url=1;
                     }
                     $access_status_url=(INT)$access_status_url;
                     $arr['menumain']['access_status_url']=$access_status_url;
                     $arr['menumain']['menu_alt_explode']=$menu_alt_explode; 
                     $arr['menumain']['user_type_id']=$user_type_id;        
                     $arr['menumain']['option']=$w->option;
                     $arr['menumain']['order_by']=$w->order_by;
                     $arr['menumain']['order_by2']=$w->order_by2;
                     $arr['menumain']['icon']=$w->icon;
                     $arr['menumain']['params']=$w->params;
                     $arr['menumain']['lang']=$w->lang;
                     #################################
                     if($access_status_url==1){ $menu_main_arr[]=$arr['menumain']; }
                    }
               }                       
     ############***********menumain end*********#############
     $mainlistnow=$menu_main_arr;
     $menu_main_arr_count=count($menu_main_arr);
     ############***********menu sub start*********#############
     if(is_array($menu)) {
          $menu_arr2=array();
          foreach($menu as $keym =>$wm){
                    $menu_id=(int)$wm->menu_id;
                    if($menu_id_active==null){$active=0;}elseif($menu_id_active==$menu_id){$active=1;}else{$active=0;}
                    $menu_arr2['ma']['menu_active']=(int)$active;
                    $menu_arr2['ma']['menu_id']=$menu_id; 
                    $menu_arr2['ma']['title']=$wm->title;
                    $menu_arr2['ma']['url']=$wm->url;
                    $menu_arr2['ma']['user_type_id']=$user_type_id;
                    $url_mn=$wm->url;
                    $url_explode_mn=explode('/',$url_mn);
                    $url_explode_count= count(explode('/',$url));
                    $url_explode_ar_mn_0=@$url_explode_mn['0'];
                    $url_explode_ar_mn_1=@$url_explode_mn['1'];
                    $url_explode_ar_mn_2=@$url_explode_mn['2'];
                    $url_explode_ar_mn_3=@$url_explode_mn['3'];
                    if($url_explode_ar_mn_0!=null){  $url_explode_ar_0=$url_explode_mn['0']; }
                    if($url_explode_ar_mn_1!=null){  $url_explode_ar_1=$url_explode_mn['1']; }
                    if($url_explode_ar_mn_2!=null){  $url_explode_ar_2=$url_explode_mn['2']; }
                    if($url_explode_ar_mn_3!=null){  $url_explode_ar_3=$url_explode_mn['3']; }
                    $menu_arr2['ma']['url_explode_ar_mn_0']=$url_explode_ar_mn_0;
                    $menu_arr2['ma']['url_explode_ar_mn_1']=$url_explode_ar_mn_1;
                    $urlnow_explode=explode('/',$urlnow);
                    $urlnow_explode_ar_0=@$urlnow_explode['0'];
                    $urlnow_explode_ar_1=@$urlnow_explode['1'];
                    $menu_arr2['ma']['urlnow_explode_ar_0']=$urlnow_explode_ar_0;
                    $menu_arr2['ma']['urlnow_explode_ar_1']=$urlnow_explode_ar_1;
                    $urlnow_explode_ar_2=@$urlnow_explode['2'];
                    $urlnow_explode_ar_3=@$urlnow_explode['3'];
                    if($urlnow_explode_ar_0!==null){  $urlnow_explode_ar_0=$urlnow_explode['0']; }
                    if($urlnow_explode_ar_1!==null){  $urlnow_explode_ar_1=$urlnow_explode['1']; }
                    if($urlnow_explode_ar_2!==null){  $urlnow_explode_ar_2=$urlnow_explode['2']; }
                    if($urlnow_explode_ar_3!==null){  $urlnow_explode_ar_3=$urlnow_explode['3']; }
               $menu2rs=$this->CI->Useraccess_model->submenuv1($menu_id,$cache_type,$user_type_id,$deletekey);
               $menu2=$menu2rs['rs'];
               $menu2_count=count($menu2);
               if(is_array($menu2)) {
                    $menu22_arr=array();
                    $submenu_now_arr=array();
                    $arr2=array();
                    foreach($menu2 as $key2 =>$w2){
                         $menu2_id=$w2->menu_id;
                         
                         $arrsubenu['submenu']['menu_id']=$menu2_id;
                         $arrsubenu['submenu']['menu_id2']=$w2->menu_id2;
                         if($menu_id_active==null){$active_sub=0;}elseif($menu_id_active==$menu_id){$active_sub=1;}else{$active_sub=0;}
                         $arrsubenu['submenu']['menu_active']=(int)$active_sub;
                         $arrsubenu['submenu']['title']=$w2->title;
                         $arrsubenu['submenu']['user_idx']=$user_idx;
                         $arrsubenu['submenu']['current_url']=$urlnow;
                         
                         $urlnow_explode=explode('/',$urlnow);
                         $urlnow_explode_ar_0=@$urlnow_explode['0'];
                         $urlnow_explode_ar_1=@$urlnow_explode['1'];
                         $urlnow_explode_ar_2=@$urlnow_explode['2'];
                         $urlnow_explode_ar_3=@$urlnow_explode['3'];
                         if($urlnow_explode_ar_0!==null){  $urlnow_explode_ar_0=$urlnow_explode['0']; }
                         if($urlnow_explode_ar_1!==null){  $urlnow_explode_ar_1=$urlnow_explode['1']; }
                         if($urlnow_explode_ar_2!==null){  $urlnow_explode_ar_2=$urlnow_explode['2']; }
                         if($urlnow_explode_ar_3!==null){  $urlnow_explode_ar_3=$urlnow_explode['3']; }
                         /*
                         $arrsubenu['submenu']['urlnow_explode_ar_0']=$urlnow_explode_ar_0;
                         $arrsubenu['submenu']['urlnow_explode_ar_1']=$urlnow_explode_ar_1;
                         $arrsubenu['submenu']['urlnow_explode_ar_2']=$urlnow_explode_ar_2;
                         $arrsubenu['submenu']['urlnow_explode_ar_3']=$urlnow_explode_ar_3;
                         */
                         $urlnow_explode_count= count(explode('/',$urlnow));
                         if(is_array($urlnow_explode)) {
                              $arn=array(); 
                              foreach($urlnow_explode as $key => $value) {
                                             $arraa['b']['key']=$key;
                                             $arraa['b']['value']=$value;
                                             $arn[]=$arraa['b'];
                              }}
                         #$arrsubenu['submenu']['url_explode_ar']=$arn;
                         #$arrsubenu['submenu']['current_url_explode_count']=$urlnow_explode_count;
                         #$arrsubenu['submenu']['current_url_explodet']=$urlnow_explode;
                         $arrsubenu['submenu']['url']=$w2->url;
                         $arrsubenu['submenu']['user_type_id']=$user_type_id;
                         $url=$w2->url;
                         $url_explode=explode('/',$url);
                         $url_explode_count= count(explode('/',$url));
                         $url_explode_ar_0=@$url_explode['0'];
                         $url_explode_ar_1=@$url_explode['1'];
                         $url_explode_ar_2=@$url_explode['2'];
                         $url_explode_ar_3=@$url_explode['3'];
                         if($url_explode_ar_0!=null){  $url_explode_ar_0=$url_explode['0']; }
                         if($url_explode_ar_1!=null){  $url_explode_ar_1=$url_explode['1']; }
                         if($url_explode_ar_2!=null){  $url_explode_ar_2=$url_explode['2']; }
                         if($url_explode_ar_3!=null){  $url_explode_ar_3=$url_explode['3']; }
                         /*
                         $arrsubenu['submenu']['url_explode_ar_0']=$url_explode_ar_0;
                         $arrsubenu['submenu']['url_explode_ar_1']=$url_explode_ar_1;
                         $arrsubenu['submenu']['url_explode_ar_2']=$url_explode_ar_2;
                         $arrsubenu['submenu']['url_explode_ar_3']=$url_explode_ar_3;
                         $arrsubenu['submenu']['url_explode']=$url_explode;
                         $arrsubenu['submenu']['url_explode_count']=$url_explode_count;
                         $arrsubenu['submenu']['parent']=$w2->parent;
                         $arrsubenu['submenu']['menu_alt']=$w2->menu_alt;       
                         $arrsubenu['submenu']['user_type_id']=$user_type_id;      
                         */   
                         $menu_alt=$w2->menu_alt;           
                         $menu_alt_explode=explode(',',$menu_alt); 
                         $arrsubenu['submenu']['menu_alt']=$menu_alt;
                         $arrsubenu['submenu']['menu_alt_explode_count']=count($menu_alt_explode);
                         $arrsubenu['submenu']['menu_alt_explode']=$menu_alt_explode;
                         if($menu_alt==null){
                              $access_status_url=(int)2; 
                         }else{
                              if(in_array($user_type_id,$menu_alt_explode)){
                                   $access_status_url=(int)1; 
                              }else{
                                   $access_status_url=(int)0; 
                              }  
                         }
                         if($urlnow_explode_count>=4){  
                         # $arrsubenu['submenu']['urlnow_explode_count_num']=4;
                              if($url_explode_ar_3==$urlnow_explode_ar_3){ $access_status_url=(int)1; }elseif($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url=1;  }else{$access_status_url=0;}
                         }elseif($urlnow_explode_count==3){  
                         # $arrsubenu['submenu']['urlnow_explode_count_num']=3;
                              if($url_explode_ar_2==$urlnow_explode_ar_2){ $access_status_url=(int)1;  }elseif($url_explode_ar_1==$urlnow_explode_ar_1){  $access_status_url=1; }else{$access_status_url=0;}
                         }elseif($urlnow_explode_count==2){  
                         # $arrsubenu['submenu']['urlnow_explode_count_num']=2;
                              if($url_explode_ar_1==$urlnow_explode_ar_1){ $access_status_url=(int)'1'; }else{$access_status_url=(int)'0';}
                         }elseif($urlnow_explode_count==1){  
                         # $arrsubenu['submenu']['urlnow_explode_count_num']=1;
                              if($url_explode_ar_0==$urlnow_explode_ar_0){ $access_status_url=(int)'1'; }else{$access_status_url=(int)'0';}
                         }else{$access_status_url=0;}

                         
                         if($menu_alt==null){ 
                              if($url_explode_ar_0==$urlnow_explode_ar_0){
                                   $arrsubenu['submenu']['access_status']='1'; 
                                   $access_status_url=(int)1;
                              }else{
                                   $arrsubenu['submenu']['access_status']='0'; 
                                   $access_status_url=(int)0;
                              }
                         }else{
                                   if(in_array($user_type_id,$menu_alt_explode)){
                                        if($url_explode_ar_0==$urlnow_explode_ar_0){
                                         $arrsubenu['submenu']['access_status']='1'; 
                                             $access_status_url=(int)1;
                                        }
                                   }else{
                                        $arrsubenu['submenu']['access_status']='0';
                                        $access_status_url=(int)0;
                                   }  

                         }
                         $arrsubenu['submenu']['access_status_url']=$access_status_url;
                         #echo'<pre> menu_alt_explode =>';print_r($menu_alt_explode);echo'</pre>'; 
                         /*
                         $arrsubenu['submenu']['option']=$w2->option;
                         $arrsubenu['submenu']['order_by']=$w2->order_by;
                         $arrsubenu['submenu']['order_by2']=$w2->order_by2;
                         $arrsubenu['submenu']['icon']=$w2->icon;
                         $arrsubenu['submenu']['params']=$w2->params;
                         $arrsubenu['submenu']['lang']=$w2->lang;
                         */
                         if($access_status_url>0){$menu22_arr[]=$arrsubenu['submenu'];}
                              
                         }                       
                    }
               #################################
                         $menu_arr2['ma']['sub']=$menu22_arr;

                    #if(($url_explode_ar_mn_0==$urlnow_explode_ar_0) && ($url_explode_ar_mn_1==$urlnow_explode_ar_1)){ $menuarr2[]=$menu_arr2['ma']; }else{$menuarr2=null;}
                    /*
                         if(($url_explode_ar_mn_0==$urlnow_explode_ar_0) && ($url_explode_ar_mn_1==$urlnow_explode_ar_1)){  
                               $menuarr2[]=$menu_arr2['ma']; 
                         }elseif($url_explode_ar_mn_0==$urlnow_explode_ar_0){ 
                              $menuarr2[]=$menu_arr2['ma'];   
                         }else{
                              $menuarr2=null;
                         }
                         */
                         $menuarr2[]=$menu_arr2['ma'];   
                         
                    }                       
               }

     ############***********menu sub End*********#############
     $submenulisturlnow=$menuarr2;
     $menuarr2count=count($menuarr2);
     if($menuarr2count==0){ 
           # $accesse_url_arr_in_current_status=1;$access_status=1;
      }

     if($submenulisturlnow>0){ $access_status=1;  }else{ if($menu_main_arr_count>0){ $access_status=1;}else{ $access_status=0;} } 
     if($menu_main_arr_count>0){ $access_status_url=1;}else{ $access_status_url=0;}
     $cache_msg=$menurs['cache_msg'];
     $cache_key=$menurs['cache_key'];
     $cachetime=$menurs['cachetime'];
     $cache_day=$menurs['cache_day'];
     if($user_type_id==1 || $user_type_id==2){ $access_status_url=1; $access_status=1; }
     ####################################
     $option=null;
     #$debug=null; 
     #$deletekey=1;
     $menusaccessed=$this->CI->Useraccess_model->usertypeaccessmenu($user_type_id,$option,$debug,$deletekey);
    ##############################################
     $menusaccessed_alt=$this->CI->Useraccess_model->usertypeaccessmenualt($user_type_id,$option,$debug,$deletekey);
     ###############ALT################
     if(is_array($menusaccessed_alt)) {
          $menu_access_alt=array();
          $access_alt=array();
          foreach($menusaccessed_alt as $keyalt =>$var_alt){
                    $menu_id=(int)$var_alt->menu_id;
                    $accessalt['ac_alt']['user_type_id']=$user_type_id;
                    $accessalt['ac_alt']['menu_id']=$menu_id;
                    $accessalt['ac_alt']['menu_id2']=$var_alt->menu_id2;
                    $accessalt['ac_alt']['title']=$var_alt->title;
                    $url=$var_alt->url;
                    $accessalt['ac_alt']['url']=$url;
                    $url_explode=explode('/',$url);
                    $url_menu_explode_arr_0=@$url_explode['0'];   
                    $url_menu_explode_arr_1=@$url_explode['1'];   
                    $url_menu_explode_arr_2=@$url_explode['2'];   
                    $url_menu_explode_arr_3=@$url_explode['3'];   
                    $url_menu_explode_arr_4=@$url_explode['4'];
                    $url_menu_explode_arr_5=@$url_explode['5'];     
                    $url_explode_count= count(explode('/',$url));                 
                    $accessalt['ac_alt']['url_menu_explode']=$url_explode;
                    $accessalt['ac_alt']['url_menu_explode_arr_0']=$url_menu_explode_arr_0;
                    $accessalt['ac_alt']['url_menu_explode_arr_1']=$url_menu_explode_arr_1;
                    $accessalt['ac_alt']['url_menu_explode_arr_2']=$url_menu_explode_arr_2;
                    $accessalt['ac_alt']['url_menu_explode_arr_3']=$url_menu_explode_arr_3;
                    $accessalt['ac_alt']['url_menu_explode_arr_4']=$url_menu_explode_arr_4;
                    $accessalt['ac_alt']['url_menu_explode_arr_5']=$url_menu_explode_arr_5;
                    $accessalt['ac_alt']['url_explode_count']=$url_explode_count;
                    $urlnow_explode=explode('/',$urlnow);
                    $urlnow_explode_count= count(explode('/',$urlnow));
                    $url_current_explode_arr_0=@$urlnow_explode['0'];
                    $url_current_explode_arr_1=@$urlnow_explode['1'];
                    $url_current_explode_arr_2=@$urlnow_explode['2'];
                    $url_current_explode_arr_3=@$urlnow_explode['3'];
                    $url_current_explode_arr_4=@$urlnow_explode['4'];
                    $url_current_explode_arr_5=@$urlnow_explode['5']; 
                    if($url_current_explode_arr_0!==null){  $url_current_explode_arr_0=$urlnow_explode['0']; }
                    if($url_current_explode_arr_1!==null){  $url_current_explode_arr_1=$urlnow_explode['1']; }
                    if($url_current_explode_arr_2!==null){  $url_current_explode_arr_2=$urlnow_explode['2']; }
                    if($url_current_explode_arr_3!==null){  $url_current_explode_arr_3=$urlnow_explode['3']; }
                    if($url_current_explode_arr_4!==null){  $url_current_explode_arr_4=$urlnow_explode['4']; }
                    if($url_current_explode_arr_5!==null){  $url_current_explode_arr_5=$urlnow_explode['5']; }
                    $accessalt['ac_alt']['url_current_explode_arr_0']=$url_current_explode_arr_0;
                    $accessalt['ac_alt']['url_current_explode_arr_1']=$url_current_explode_arr_1;
                    $accessalt['ac_alt']['url_current_explode_arr_2']=$url_current_explode_arr_2;
                    $accessalt['ac_alt']['url_current_explode_arr_3']=$url_current_explode_arr_3;
                    $accessalt['ac_alt']['url_current_explode_arr_4']=$url_current_explode_arr_4;
                    $accessalt['ac_alt']['url_current_explode_arr_5']=$url_current_explode_arr_5;
                    $accessalt['ac_alt']['url_current_explode_count']=$url_explode_count;
                    $accessalt['ac_alt']['parent']=$var_alt->parent;
                    $menu_alt=$var_alt->menu_alt;
                    $accessalt['ac_alt']['menu_alt']=$menu_alt;
                    $menu_alt_explode=explode(',',$menu_alt); 
                    $menu_alt_explode_count=count($menu_alt_explode);
                    if($menu_alt_explode_count==1){$menu_alt_explode_count=0;}
                    $accessalt['ac_alt']['menu_alt_explode_count']=$menu_alt_explode_count;
                    $accessalt['ac_alt']['menu_alt_explode']=$menu_alt_explode;
                    if($menu_alt_explode_count==0 || $user_type_id==1 || $user_type_id==2){
                         $access_status=1;
                         $accessalt['ac_alt']['access_status']=(int)$access_status; 
                    }else{
                         ##############################
                         if(in_array($user_type_id,$menu_alt_explode)){
                              $access_status=1;
                              $accessalt['ac_alt']['access_status']=(int)$access_status; 
                         }else{
                              $access_status=0;
                              $accessalt['ac_alt']['access_status']=(int)$access_status;
                         }  
                         ##############################
                    }
                    /*
                    $accessalt['ac_alt']['option']=$var_alt->option;
                    $accessalt['ac_alt']['create_date']=$var_alt->create_date;
                    $accessalt['ac_alt']['create_by']=$var_alt->create_by;
                    $accessalt['ac_alt']['lastupdate_date']=$var_alt->lastupdate_date;
                    $accessalt['ac_alt']['lastupdate_by']=$var_alt->lastupdate_by;
                    $accessalt['ac_alt']['order_by']=$var_alt->order_by;
                    $accessalt['ac_alt']['order_by2']=$var_alt->order_by2;
                    $accessalt['ac_alt']['icon']=$var_alt->icon;
                    $accessalt['ac_alt']['status']=$var_alt->status;
                    $accessalt['ac_alt']['lang']=$var_alt->lang;                    
                    $accessalt['ac_alt']['params']=$var_alt->params;
                    */

               #################
               if(in_array($user_type_id,$menu_alt_explode)){ $menu_access_alt[]=$accessalt['ac_alt'];   }
          }}
          if(is_array($menu_access_alt)) {
               $accesse_url_alt_arr=array(); 
               foreach($menu_access_alt as $key2_alt =>$var2_alt){ 
                    $url2_alt=$var2_alt['url']; 
                    $accesse_url_alt_arr[]=$url2_alt;
               }}
     ###############ALT#################
     $accesse_url=$menusaccessed;
     if($debug==1){
          echo'<hr><pre>  user_type_id=>';print_r($user_type_id);echo'<pre>';
          echo'<pre>  option=>';print_r($option);echo'<pre>';
          echo'<pre>  debug=>';print_r($debug);echo'<pre>';
          echo'<pre>  deletekey=>';print_r($deletekey);echo'<pre>';
          echo'<pre>  accesse_url=>';print_r($accesse_url);echo'<pre>';  #Die();
          }
     if(is_array($menusaccessed)) {
          $menu_access=array();
          $access=array();
          foreach($menusaccessed as $key =>$var){
                    $menu_id=(int)$var->menu_id;
                    $access['ac']['user_type_id']=$user_type_id;
                    $access['ac']['menu_id']=$menu_id;
                    $access['ac']['menu_id2']=$var->menu_id2;
                    $access['ac']['title']=$var->title;
                    $url=$var->url;
                    $access['ac']['url']=$url;
                    $url_explode=explode('/',$url);
                    $url_menu_explode_arr_0=@$url_explode['0'];   
                    $url_menu_explode_arr_1=@$url_explode['1'];   
                    $url_menu_explode_arr_2=@$url_explode['2'];   
                    $url_menu_explode_arr_3=@$url_explode['3'];   
                    $url_menu_explode_arr_4=@$url_explode['4'];
                    $url_menu_explode_arr_5=@$url_explode['5'];     
                    $url_explode_count= count(explode('/',$url));                    
                    $access['ac']['url_menu_explode']=$url_explode;
                    $access['ac']['url_menu_explode_arr_0']=$url_menu_explode_arr_0;
                    $access['ac']['url_menu_explode_arr_1']=$url_menu_explode_arr_1;
                    $access['ac']['url_menu_explode_arr_2']=$url_menu_explode_arr_2;
                    $access['ac']['url_menu_explode_arr_3']=$url_menu_explode_arr_3;
                    $access['ac']['url_menu_explode_arr_4']=$url_menu_explode_arr_4;
                    $access['ac']['url_menu_explode_arr_5']=$url_menu_explode_arr_5;
                    $access['ac']['url_explode_count']=$url_explode_count;
                    $urlnow_explode=explode('/',$urlnow);
                    $urlnow_explode_count= count(explode('/',$urlnow));
                    $url_current_explode_arr_0=@$urlnow_explode['0'];
                    $url_current_explode_arr_1=@$urlnow_explode['1'];
                    $url_current_explode_arr_2=@$urlnow_explode['2'];
                    $url_current_explode_arr_3=@$urlnow_explode['3'];
                    $url_current_explode_arr_4=@$urlnow_explode['4'];
                    $url_current_explode_arr_5=@$urlnow_explode['5']; 
                    if($url_current_explode_arr_0!==null){  $url_current_explode_arr_0=$urlnow_explode['0']; }
                    if($url_current_explode_arr_1!==null){  $url_current_explode_arr_1=$urlnow_explode['1']; }
                    if($url_current_explode_arr_2!==null){  $url_current_explode_arr_2=$urlnow_explode['2']; }
                    if($url_current_explode_arr_3!==null){  $url_current_explode_arr_3=$urlnow_explode['3']; }
                    if($url_current_explode_arr_4!==null){  $url_current_explode_arr_4=$urlnow_explode['4']; }
                    if($url_current_explode_arr_5!==null){  $url_current_explode_arr_5=$urlnow_explode['5']; }
                    $access['ac']['url_current_explode_arr_0']=$url_current_explode_arr_0;
                    $access['ac']['url_current_explode_arr_1']=$url_current_explode_arr_1;
                    $access['ac']['url_current_explode_arr_2']=$url_current_explode_arr_2;
                    $access['ac']['url_current_explode_arr_3']=$url_current_explode_arr_3;
                    $access['ac']['url_current_explode_arr_4']=$url_current_explode_arr_4;
                    $access['ac']['url_current_explode_arr_5']=$url_current_explode_arr_5;
                    $access['ac']['url_current_explode_count']=$url_explode_count;
                    $access['ac']['parent']=$var->parent;
                    $menu_alt=$var->menu_alt;
                    $access['ac']['menu_alt']=$menu_alt;
                    $menu_alt_explode=explode(',',$menu_alt); 
                    $menu_alt_explode_count=count($menu_alt_explode);
                    if($menu_alt_explode_count==1){$menu_alt_explode_count=0;}
                    $access['ac']['menu_alt_explode_count']=$menu_alt_explode_count;
                    $access['ac']['menu_alt_explode']=$menu_alt_explode;
                    if($menu_alt_explode_count==0 || $user_type_id==1 || $user_type_id==2){
                         $access_status=1;
                         $access['ac']['access_status']=(int)$access_status; 
                    }else{
                         ##############################
                         if(in_array($user_type_id,$menu_alt_explode)){
                              $access_status=1;
                              $access['ac']['access_status']=(int)$access_status; 
                         }else{
                              $access_status=0;
                              $access['ac']['access_status']=(int)$access_status;
                         }  
                         ##############################
                    }
                    
                    $access['ac']['option']=$var->option;
                    $access['ac']['create_date']=$var->create_date;
                    $access['ac']['create_by']=$var->create_by;
                    $access['ac']['lastupdate_date']=$var->lastupdate_date;
                    $access['ac']['lastupdate_by']=$var->lastupdate_by;
                    $access['ac']['order_by']=$var->order_by;
                    $access['ac']['order_by2']=$var->order_by2;
                    $access['ac']['icon']=$var->icon;
                    $access['ac']['status']=$var->status;
                    $access['ac']['lang']=$var->lang;
                    $access['ac']['params']=$var->params;

               #################
               $menu_access[]=$access['ac']; 
          }}
     ####################################
     ###################################
     if(is_array($accesse_url)) {
          $accesse_url_arr=array(); 
          foreach($accesse_url as $key2 =>$var2){ 
               $url2=$var2->url; 
               $accesse_url_arr[]=$url2;
          }}
     if(is_array($menu_arr)) {
               $menu_user_arr=array();
               foreach($menu_arr as $k =>$var3){ 
               $url3['menu_id']=(int)$var3['menu_id']; 
               $urlmenu=$var3['url'];
               if(in_array($urlmenu,$accesse_url_arr)){  $access_status_url=1;  }else{ $access_status_url=0; }
                    $url3['access_status_url']=$access_status_url; 
                    $url3['mainurl']=$var3['url']; 
                    $url3['alt']=$var3['menu_alt']; 
                    $submenu=$var3['submenu']; 
                    ################
                    if(is_array($submenu)) {
                         $menu_user_arr4=array();
                         foreach($submenu as $k4 =>$var4){ 
                              $url4['menu_id']=(int)$var4['menu_id']; 
                              $url4['url']=$var4['url']; 
                              $urlmenu=$var4['url']; 
                              if(in_array($urlmenu,$accesse_url_arr)){  $access_status_url=1;  }else{ $access_status_url=0; }
                              $url4['access_status_url']=$access_status_url; 
                              $url4['malt']=$var4['menu_alt']; 
                              $menu_user_arr4[]=$url4;
                         }}
                    ################
                    $url3['submenu']=$menu_user_arr4; 

                    $submenu_count=count($menu_user_arr4); 
                    if($submenu_count==0){
                        # $accesse_url_arr_in_current_status=1; $access_status=1;
                    
                    }

                    $url3['submenu_count']=$submenu_count; 
                    $menu_user_arr[]=$url3;
               }}
     ####################################
     $menusaccessed_user_count=count($menu_access);
     if(is_array($menusaccessed)) {
          $menu_access_url=array();
          $access_cl=array();
          foreach($menusaccessed as $key =>$var){
                    $menu_id=(int)$var->menu_id;
                    $access_cl['cl']['user_type_id']=$user_type_id;
                    $access_cl['cl']['menu_id']=$menu_id;
                    $access_cl['cl']['menu_id2']=$var->menu_id2;
                    $access_cl['cl']['title']=$var->title;
                    $url=$var->url;
                    $access_cl['cl']['url']=$url;
                    $url_explode=explode('/',$url);
                    $url_menu_explode_arr_0=@$url_explode['0'];   
                    $url_menu_explode_arr_1=@$url_explode['1'];   
                    $url_menu_explode_arr_2=@$url_explode['2'];   
                    $url_menu_explode_arr_3=@$url_explode['3'];   
                    $url_menu_explode_arr_4=@$url_explode['4'];
                    $url_menu_explode_arr_5=@$url_explode['5'];     
                    $url_explode_count= count(explode('/',$url));                    
                    $access_cl['cl']['url_menu_explode']=$url_explode;
                    
                    $access_cl['cl']['url_menu_explode_arr_0']=$url_menu_explode_arr_0;
                    $access_cl['cl']['url_menu_explode_arr_1']=$url_menu_explode_arr_1;
                    /*
                    $access_cl['cl']['url_menu_explode_arr_2']=$url_menu_explode_arr_2;
                    $access_cl['cl']['url_menu_explode_arr_3']=$url_menu_explode_arr_3;
                    $access_cl['cl']['url_menu_explode_arr_4']=$url_menu_explode_arr_4;
                    $access_cl['cl']['url_menu_explode_arr_5']=$url_menu_explode_arr_5;
                    */
                    $access_cl['cl']['url_explode_count']=$url_explode_count;
                    $urlnow_explode=explode('/',$urlnow);
                    $urlnow_explode_count= count(explode('/',$urlnow));
                    $url_current_explode_arr_0=@$urlnow_explode['0'];
                    $url_current_explode_arr_1=@$urlnow_explode['1'];
                    $url_current_explode_arr_2=@$urlnow_explode['2'];
                    $url_current_explode_arr_3=@$urlnow_explode['3'];
                    $url_current_explode_arr_4=@$urlnow_explode['4'];
                    $url_current_explode_arr_5=@$urlnow_explode['5']; 
                    if($url_current_explode_arr_0!==null){  $url_current_explode_arr_0=$urlnow_explode['0']; }
                    if($url_current_explode_arr_1!==null){  $url_current_explode_arr_1=$urlnow_explode['1']; }
                    if($url_current_explode_arr_2!==null){  $url_current_explode_arr_2=$urlnow_explode['2']; }
                    if($url_current_explode_arr_3!==null){  $url_current_explode_arr_3=$urlnow_explode['3']; }
                    if($url_current_explode_arr_4!==null){  $url_current_explode_arr_4=$urlnow_explode['4']; }
                    if($url_current_explode_arr_5!==null){  $url_current_explode_arr_5=$urlnow_explode['5']; }
               
                    $access_cl['cl']['url_current_explode_arr_0']=$url_current_explode_arr_0;
                    $access_cl['cl']['url_current_explode_arr_1']=$url_current_explode_arr_1;
                    /*
                    $access_cl['cl']['url_current_explode_arr_2']=$url_current_explode_arr_2;
                    $access_cl['cl']['url_current_explode_arr_3']=$url_current_explode_arr_3;
                    $access_cl['cl']['url_current_explode_arr_4']=$url_current_explode_arr_4;
                    $access_cl['cl']['url_current_explode_arr_5']=$url_current_explode_arr_5;
                    $access_cl['cl']['url_current_explode_count']=$url_explode_count;
                    */
                    $access_cl['cl']['parent']=$var->parent;
                    $menu_alt=$var->menu_alt;
                    $access_cl['cl']['menu_alt']=$menu_alt;
                    $menu_alt_explode=explode(',',$menu_alt); 
                    $menu_alt_explode_count=count($menu_alt_explode);
                    if($menu_alt_explode_count==1){$menu_alt_explode_count=0;}
                    $access_cl['cl']['menu_alt_explode_count']=$menu_alt_explode_count;
                    $access_cl['cl']['menu_alt_explode']=$menu_alt_explode;
                    $access_cl['cl']['menu_alt_explode_count']=count($menu_alt_explode);
                    if(in_array($user_type_id,$menu_alt_explode)){
                         $access_status_b=1;
                         $access_status_b_alt=1;
                    
                    }else{
                         $access_status_b=0;
                         $access_status_b_alt=0;
                         
                    
                    } 
                    if($user_type_id==1 ||$user_type_id==2){$access_status_b_alt=1;}
                    if($menu_alt_explode_count==0){$access_status_b_alt=2;}
                         $access_cl['cl']['access_status_alt']=(int)$access_status_b_alt;  
                    if($menu_alt_explode_count==0 || $user_type_id==1 || $user_type_id==2){
                         $access_status=1;
                         $access_cl['cl']['access_status']=(int)$access_status; 
                    }else{
                         ##############################
                         if(in_array($user_type_id,$menu_alt_explode)){
                              $access_status=1;
                              $access_cl['cl']['access_status']=(int)$access_status; 
                         }else{
                              $access_status=0;
                              $access_cl['cl']['access_status']=(int)$access_status;
                         }  
                         ##############################
                    }
                    
                    $access_cl['cl']['option']=$var->option;
                    /*
                    $access_cl['cl']['create_date']=$var->create_date;
                    $access_cl['cl']['create_by']=$var->create_by;
                    $access_cl['cl']['lastupdate_date']=$var->lastupdate_date;
                    $access_cl['cl']['lastupdate_by']=$var->lastupdate_by;
                    $access_cl['cl']['order_by']=$var->order_by;
                    $access_cl['cl']['order_by2']=$var->order_by2;
                    $access_cl['cl']['icon']=$var->icon;
                    $access_cl['cl']['status']=$var->status;
                    $access_cl['cl']['lang']=$var->lang;
                    $access_cl['cl']['params']=$var->params;
                    */

               #if($url_menu_explode_arr_0==$url_current_explode_arr_0){ $menu_access_url[]=$access_cl['cl']; }
               /*
               if($url_menu_explode_arr_1==''){
                    if($url_menu_explode_arr_0==$url_current_explode_arr_0){ 
                         $menu_access_url[]=$access_cl['cl']; 
                    }
                    
                    
               }else{
                    
                    
                    if(($url_menu_explode_arr_0==$url_current_explode_arr_0)&&($url_menu_explode_arr_1==$url_current_explode_arr_1)){ 
                         $menu_access_url[]=$access_cl['cl']; 
                    }
               }
               */
               
               if($url_menu_explode_arr_0==$url_current_explode_arr_0){ 
                    $menu_access_url[]=$access_cl['cl']; 
               }
               /*
               if(in_array($user_type_id,$menu_alt_explode)){
                                 $access_status=1; 
                                 $arr['menumain']['access_status']=$access_status; 
                         }
               */
               #if($url_current_explode_arr_1!==null){ if($url_menu_explode_arr_1==$url_current_explode_arr_1){ $menu_access_url[]=$access_cl['cl']; }}



          }}
     if($menusaccessed_user_count==0){$menu_access=null;$access_status=0;$access_status_url=0;}else{$access_status=1;$access_status_url=1;}
     $menusaccessed_user=$menu_access;
     $urlnow_explode=explode('/',$urlnow);
     $urlnow_explode_count= count(explode('/',$urlnow));
     $urlnow_explode_ar_0=@$urlnow_explode['0'];
     $urlnow_explode_ar_1=@$urlnow_explode['1'];
     $urlnow_explode_ar_2=@$urlnow_explode['2'];
     $urlnow_explode_ar_3=@$urlnow_explode['3'];
     $urlnow_explode_ar_4=@$urlnow_explode['4'];
     $urlnow_explode_ar_5=@$urlnow_explode['5'];
     if(is_array($menusaccessed)) {
          $accesse_url_arr_in_current=array(); 
          foreach($menusaccessed as $km =>$vm){ 
               $url=$vm->url; 
               $urlexplode=explode('/',$url);
               $url_explode_0=@$urlexplode['0'];
               $url_explode_1=@$urlexplode['1'];
               $url_explode_2=@$urlexplode['2'];
               $url_explode_3=@$urlexplode['3'];
               $url_explode_4=@$urlexplode['4'];
               $url_explode_5=@$urlexplode['5'];
               $url_explode_count= count(explode('/',$url));
               $u['url']=$url;
               $u['url_explode_count']=$url_explode_count;
               $u['urlnow']=$urlnow;
               $u['urlnow_explode_count']=$urlnow_explode_count;
               if($urlnow==$url){$st=1;}else{$st=0;}
               if($urlnow==$url){$st=1;}else{$st=0;}
               $u['st']=$st;
               if($st==1){$statusurl=1;}else{$statusurl=0;}
               $u['status_url']=$statusurl;
               if($url_explode_count==1){
                    if($url_explode_0==$urlnow){ $accesse_url_arr_in_current[]=$u; }
               }elseif($url_explode_count==2){
                    if($url_explode_0.'/'.$url_explode_1==$urlnow){ $accesse_url_arr_in_current[]=$u; }
               }elseif($url_explode_count==3){
                    $a1=$url_explode_0.'/'.$url_explode_1;
                    $b1=$urlnow_explode_ar_0.'/'.$urlnow_explode_ar_1;
                    if($a1==$b1){ $accesse_url_arr_in_current[]=$u;   }elseif($url_explode_0==$urlnow_explode_ar_0){  $accesse_url_arr_in_current[]=$u;  }
               }elseif($url_explode_0==$urlnow_explode_ar_0){
                      $accesse_url_arr_in_current[]=$u;  
               }
          }}
           $accesse_url_arr_in_current_count=(int)count($accesse_url_arr_in_current);
          if($accesse_url_arr_in_current_count>=1){ 
               $accesse_url_arr_in_current_status=1; 
          }else{ 
               $accesse_url_arr_in_current_status=0;
          }
          if($urlnow=='#'){ 
               $accesse_url_arr_in_current_status=1; 
          }
          $urlarray=array('user/dashboard',
                         '/user/dashboard',
                         'user/singout',
                         '/user/singout',
                         'user/historylog',
                         '/user/historylog',
                         'user/profile',
                         '/user/profile',
                    );
                         if(in_array($urlnow,$urlarray)){  
                              $accesse_url_arr_in_current_status=1; 
                         }

                    $accesse_url_alt_arr_count=count($accesse_url_alt_arr);
                    if($accesse_url_alt_arr_count>=1){
                         $accesse_url_all=array_merge($accesse_url_arr,$accesse_url_alt_arr);
                         
                         $urlnow_explode=explode('/',$urlnow);

                         $urlnow_explode_count=count($urlnow_explode);
                         #echo'<pre>  urlnow_explode_count=>';print_r($urlnow_explode_count);

                         $urlnow_explode_count= count(explode('/',$urlnow));
                         $urlnow_explode_ar_0=@$urlnow_explode['0'];
                         $urlnow_explode_ar_1=@$urlnow_explode['1'];
                         $urlnow_explode_ar_2=@$urlnow_explode['2'];
                         $urlnow_explode_ar_3=@$urlnow_explode['3'];
                         $urlnow_explode_ar_4=@$urlnow_explode['4'];
                         $urlnow_explode_ar_5=@$urlnow_explode['5'];
                          /*
                              $url_explode=explode('/',$accesse_url_all);
                              $url_menu_explode_arr_0=@$url_explode['0'];   
                              $url_menu_explode_arr_1=@$url_explode['1'];   
                              $url_menu_explode_arr_2=@$url_explode['2'];   
                              $url_menu_explode_arr_3=@$url_explode['3'];   
                              $url_menu_explode_arr_4=@$url_explode['4'];
                              $url_menu_explode_arr_5=@$url_explode['5'];          
                              echo'<hr><pre>  accesse_url_all=>';print_r($accesse_url_all);echo'<pre>';
                              */
                              if(is_array($accesse_url_all)) {
                                   $al=array(); 
                                   foreach($accesse_url_all as $kb =>$vb){  
                                             $url_explode=explode('/',$vb);
                                             $url_explode_count=count($url_explode);
                                             
                                             $url_menu_explode_arr_0=@$url_explode['0'];   
                                             $url_menu_explode_arr_0=str_replace('#!','', $url_menu_explode_arr_0);
                                             $url_menu_explode_arr_1=@$url_explode['1'];  
                                             $url_menu_explode_arr_1=str_replace('#!','', $url_menu_explode_arr_1);
                                             $url_menu_explode_arr_2=@$url_explode['2'];   
                                             $url_menu_explode_arr_2=str_replace('#!','', $url_menu_explode_arr_2);
                                             $url_menu_explode_arr_3=@$url_explode['3'];   
                                             $url_menu_explode_arr_3=str_replace('#!','', $url_menu_explode_arr_3);
                                             $url_menu_explode_arr_4=@$url_explode['4'];
                                             $url_menu_explode_arr_4=str_replace('#!','', $url_menu_explode_arr_4);
                                             $url_menu_explode_arr_5=@$url_explode['5'];  
                                             $url_menu_explode_arr_5=str_replace('#!','', $url_menu_explode_arr_5);
                                             /*
                                             echo'<hr><pre> 0 urlnow=>';print_r($urlnow_explode_ar_0);echo'<pre>'; 
                                             echo'<pre> 1 urlnow=>';print_r($urlnow_explode_ar_1);echo'<pre>'; 
                                             echo'<pre> 2 urlnow=>';print_r($urlnow_explode_ar_2);echo'<pre>'; 
                                             echo'<pre> 3 urlnow=>';print_r($urlnow_explode_ar_3);echo'<pre>'; 
                                             echo'<pre> 4 urlnow=>';print_r($urlnow_explode_ar_4);echo'<pre>'; 
                                             echo'<pre> 5 urlnow=>';print_r($urlnow_explode_ar_5);echo'<pre>'; 
                                             echo'<pre> 0 url_md=>';print_r($url_menu_explode_arr_0);echo'<pre>'; 
                                             echo'<pre> 1 url_md=>';print_r($url_menu_explode_arr_1);echo'<pre>'; 
                                             echo'<pre> 2 url_md=>';print_r($url_menu_explode_arr_2);echo'<pre>'; 
                                             echo'<pre> 3 url_md=>';print_r($url_menu_explode_arr_3);echo'<pre>'; 
                                             echo'<pre> 4 url_md=>';print_r($url_menu_explode_arr_4);echo'<pre>'; 
                                             echo'<pre> 5 url_md=>';print_r($url_menu_explode_arr_5);echo'<pre>'; 
                                             */
                                             

               if($urlnow==$vb){  $accesse_url_arr_in_current_status=1; 
                                # echo'<hr><pre> urlnow=>';print_r($urlnow);echo'<pre>'; 
                                # echo'<pre>  $vb=>';print_r($vb);echo'<pre>'; 
 
                }
                                             
                                                  if($urlnow_explode_count==5){

                                                       $url_md=$url_menu_explode_arr_0.'/'.$url_menu_explode_arr_1.'/'.$url_menu_explode_arr_2.'/'.$url_menu_explode_arr_3.'/'.$url_menu_explode_arr_4;
                                                       $url_now=$urlnow_explode_ar_0.'/'.$urlnow_explode_ar_1.'/'.$urlnow_explode_ar_2.'/'.$urlnow_explode_ar_3.'/'.$urlnow_explode_ar_4;
                                                       if($url_md==$url_now){
                                                            $accesse_url_arr_in_current_status=1; 
                                                       # echo'<hr><pre> 4 url_md=>';print_r($url_md);echo'<pre>'; echo'<pre>  url_now=>';print_r($url_now);echo'<pre>'; 
                                                       }
                                                  }elseif($urlnow_explode_count==4){

                                                  $url_md=$url_menu_explode_arr_0.'/'.$url_menu_explode_arr_1.'/'.$url_menu_explode_arr_2.'/'.$url_menu_explode_arr_3;
                                                  $url_now=$urlnow_explode_ar_0.'/'.$urlnow_explode_ar_1.'/'.$urlnow_explode_ar_2.'/'.$urlnow_explode_ar_3;
                                                  if($url_md==$url_now){
                                                       $accesse_url_arr_in_current_status=1; 
                                                      # echo'<hr><pre> 3 url_md=>';print_r($url_md);echo'<pre>'; echo'<pre>  url_now=>';print_r($url_now);echo'<pre>'; 
                                                  }
                                             }elseif($urlnow_explode_count==3){

                                                  $url_md=$url_menu_explode_arr_0.'/'.$url_menu_explode_arr_1.'/'.$url_menu_explode_arr_2;
                                                  $url_now=$urlnow_explode_ar_0.'/'.$urlnow_explode_ar_1.'/'.$urlnow_explode_ar_2;
                                                  if($url_md==$url_now){
                                                       $accesse_url_arr_in_current_status=1; 
                                                      # echo'<hr><pre> 2 url_md=>';print_r($url_md);echo'<pre>';  echo'<pre>  url_now=>';print_r($url_now);echo'<pre>'; 
                                                   }
                                             }elseif($urlnow_explode_count==2){
                                                  $url_md=$url_menu_explode_arr_0.'/'.$url_menu_explode_arr_1;
                                                  $url_now=$urlnow_explode_ar_0.'/'.$urlnow_explode_ar_1;

                                                  if($url_md==$url_now){
                                                       $accesse_url_arr_in_current_status=1; 
                                                     #  echo'<hr><pre> 1 url_md=>';print_r($url_md);echo'<pre>';  echo'<pre>  url_now=>';print_r($url_now);echo'<pre>'; 
                                                  }
                                             }else{
                                                  $url_md=$url_menu_explode_arr_0;
                                                  $url_now=$urlnow_explode_ar_0;

                                                  if($url_md==$url_now){
                                                       $accesse_url_arr_in_current_status=1; 
                                                      # echo'<hr><pre> 0 url_md=>';print_r($url_md);echo'<pre>';  echo'<pre>  url_now=>';print_r($url_now);echo'<pre>'; 
                                                  }
                                             }
                                              
                                           
                                             /*
                                             if(($url_menu_explode_arr_1==$urlnow_explode_ar_1)&& $url_menu_explode_arr_1!=''){$accesse_url_arr_in_current_status=1; 
                                                  
                                                  echo'<pre>  url_explode_count=>';print_r($url_explode_count);
                                                  echo'<pre>  url_menu_explode_arr_1=>';print_r($url_menu_explode_arr_1);
                                                  echo'<pre>'; echo'<pre>  urlnow_explode_ar_1=>';print_r($urlnow_explode_ar_1);echo'<pre>';
                                             }elseif(($url_menu_explode_arr_0==$urlnow_explode_ar_0)&& $url_menu_explode_arr_0!=''){$accesse_url_arr_in_current_status=1; 
                                                  echo'<pre>  url_explode_count=>';print_r($url_explode_count);
                                                  echo'<pre>  url_menu_explode_arr_0=>';print_r($url_menu_explode_arr_0);
                                                  echo'<pre>'; echo'<pre>  urlnow_explode_ar_0=>';print_r($urlnow_explode_ar_0);echo'<pre>';
                                             }
                                             **/
                                            
                                             
                                            
                                            
                                             #echo'<hr> ur now=>'.$urlnow.'::->>'.$vb; 
                                            


                                   }} 

                                   
                                    #echo'<hr><pre>  urlnow=>';print_r($urlnow);echo'<pre>';  Die();
                         
                         
                    }else{
                         $accesse_url_all=$accesse_url_arr;
                    } 

                   

     ####################################****************#########################################
          ####################################*******ALT2*********#########################################
          if(is_array($menusaccessed_alt)) {
               $menu_access_alt2=array();
               $accessalt2=array();
               foreach($menusaccessed_alt as $keyalt =>$var_alt){
                         $menu_id=(int)$var_alt->menu_id;
                         $accessalt2['ac_alt2']['user_type_id']=$user_type_id;
                         $accessalt2['ac_alt2']['menu_id']=$menu_id;
                         $submenurs=$this->CI->Useraccess_model->submenuv1($menu_id,$cache_type,$user_type_id,$deletekey);
                         $submenu=$submenurs['rs'];
                         $submenu_count=(int)count($submenu);
                         $accessalt2['ac_alt2']['submenu_count']=$submenu_count;
                         $accessalt2['ac_alt2']['menu_id2']=$var_alt->menu_id2;
                         $accessalt2['ac_alt2']['title']=$var_alt->title;
                         $url=$var_alt->url;
                         $accessalt2['ac_alt2']['url']=$url;
                         $url_explode=explode('/',$url);
                         $url_menu_explode_arr_0=@$url_explode['0'];   
                         $url_menu_explode_arr_1=@$url_explode['1'];      
                         $url_explode_count= count(explode('/',$url));                 
                    # $accessalt2['ac_alt2']['url_menu_explode']=$url_explode;
                         $accessalt2['ac_alt2']['url_menu_explode_arr_0']=$url_menu_explode_arr_0;
                    # $accessalt2['ac_alt2']['url_menu_explode_arr_1']=$url_menu_explode_arr_1;
                    # $accessalt2['ac_alt2']['url_explode_count']=$url_explode_count;
                         $urlnow_explode=explode('/',$urlnow);
                         $urlnow_explode_count= count(explode('/',$urlnow));
                         $url_current_explode_arr_0=@$urlnow_explode['0'];
                         $url_current_explode_arr_1=@$urlnow_explode['1'];
                         if($url_current_explode_arr_0!==null){  $url_current_explode_arr_2=$urlnow_explode['0']; }
                         if($url_current_explode_arr_1!==null){  $url_current_explode_arr_1=$urlnow_explode['1']; }
                         $accessalt2['ac_alt2']['url_current_explode_arr_0']=$url_current_explode_arr_0;
                    # $accessalt2['ac_alt2']['url_current_explode_arr_1']=$url_current_explode_arr_1;
                    # $accessalt2['ac_alt2']['url_current_explode_count']=$url_explode_count;
                    # $accessalt2['ac_alt2']['parent']=$var_alt->parent;
                         $menu_alt=$var_alt->menu_alt;
                         $accessalt2['ac_alt2']['menu_alt']=$menu_alt;
                         $menu_alt_explode=explode(',',$menu_alt); 
                         $menu_alt_explode_count=count($menu_alt_explode);
                         if($menu_alt_explode_count==1){$menu_alt_explode_count=0;}
                    # $accessalt2['ac_alt2']['menu_alt_explode_count']=$menu_alt_explode_count;
                    # $accessalt2['ac_alt2']['menu_alt_explode']=$menu_alt_explode;
                         if($menu_alt_explode_count==0 || $user_type_id==1 || $user_type_id==2){
                              $access_status=1;
                              $accessalt2['ac_alt2']['access_status']=(int)$access_status; 
                         }else{
                              ##############################
                              if(in_array($user_type_id,$menu_alt_explode)){
                                   $access_status=1;
                                   $accessalt2['ac_alt2']['access_status']=(int)$access_status; 
                              }else{
                                   $access_status=0;
                                   $accessalt2['ac_alt2']['access_status']=(int)$access_status;
                              }  
                              ##############################
                         }
                    
                    #################
                    if(in_array($user_type_id,$menu_alt_explode)){ $menu_access_alt2[]=$accessalt2['ac_alt2'];   }
               }}
          
          ####################################*******ALT2*********#########################################
          ####################################*******menusaccessed*********################################
          if(is_array($menusaccessed)) {
               $menu_accessv2=array();
               $accessv2=array();
               foreach($menusaccessed as $key =>$var){
                         $menu_id=(int)$var->menu_id;
                         $accessv2['ac']['user_type_id']=$user_type_id;
                         $accessv2['ac']['menu_id']=$menu_id;
                         #$accessv2['ac']['menu_id2']=$var->menu_id2;
                         $accessv2['ac']['title']=$var->title;
                         $accessv2['ac']['menu_alt']='';
                         $url=$var->url;
                         $accessv2['ac']['url']=$url;
                         $url_explode=explode('/',$url);
                         $url_menu_explode_arr_0=@$url_explode['0'];   
                         $url_menu_explode_arr_1=@$url_explode['1'];   
                         $url_menu_explode_arr_2=@$url_explode['2'];     
                         $url_explode_count= count(explode('/',$url));                    
                    # $accessv2['ac']['url_menu_explode']=$url_explode;
                         $accessv2['ac']['url_menu_explode_arr_0']=$url_menu_explode_arr_0;
                    # $accessv2['ac']['url_menu_explode_arr_1']=$url_menu_explode_arr_1;
                         #$accessv2['ac']['url_menu_explode_arr_2']=$url_menu_explode_arr_2;
                         #$accessv2['ac']['url_explode_count']=$url_explode_count;
                         $urlnow_explode=explode('/',$urlnow);
                         $urlnow_explode_count= count(explode('/',$urlnow));
                         $url_current_explode_arr_0=@$urlnow_explode['0'];
                         $url_current_explode_arr_1=@$urlnow_explode['1'];
                         $url_current_explode_arr_2=@$urlnow_explode['2'];
                         $accessv2['ac']['url_current_explode_arr_0']=$url_current_explode_arr_0;
                         #$accessv2['ac']['url_current_explode_arr_1']=$url_current_explode_arr_1;
                         #$accessv2['ac']['url_current_explode_arr_2']=$url_current_explode_arr_2;
                    $submenurs=$this->CI->Useraccess_model->submenuv1($menu_id,$cache_type,$user_type_id,$deletekey);
                    $submenu=$submenurs['rs'];
                    $submenu_count=(int)count($submenu);
                    $accessv2['ac']['submenu_count']=$submenu_count;
                    #$accessv2['ac']['submenu']=$submenu;
                    #################
                    #if($url_menu_explode_arr_0==$url_current_explode_arr_0){
                         if($submenu_count==0){
                              $menu_accessv2[]=$accessv2['ac'];
                         }
                    #}
                    
               }}
          ####################################*******menusaccessed*********################################
     ####################################****************#########################################
     $array_mergeall2=(array_merge($menu_accessv2,$menu_access_alt2));
     if(is_array($array_mergeall2)) {
          $accesse_url_merge1=array();
          $accesse_url_merge=array(); 
          foreach($array_mergeall2 as $key3 =>$var3){ 
               $ar['merge']['user_type_id']=$var3['user_type_id'];
               $ar['merge']['menu_id']=$var3['menu_id'];
               $ar['merge']['title']=$var3['title'];
               $ar['merge']['menu_alt']=$var3['menu_alt'];
               $ar['merge']['url']=$var3['url'];
               $url_menu_explode_arr_0=$var3['url_menu_explode_arr_0'];
               $url_current_explode_arr_0=$var3['url_current_explode_arr_0'];
               $ar['merge']['url_menu_explode_arr_0']=$url_menu_explode_arr_0;
               $ar['merge']['url_current_explode_arr_0']=$url_current_explode_arr_0;
               $ar['merge']['submenu_count']=$var3['submenu_count'];
               if($url_menu_explode_arr_0==$url_current_explode_arr_0){
                    $accesse_url_merge[]=$ar['merge'];
                    $accesse_url_arr_in_current_status=2;
               }
               $accesse_url_merge1[]=$ar['merge'];
          }}
               #echo'<hr><pre>  accesse_url_merge=>';print_r($accesse_url_merge);echo'</pre>'; #Die();
               #echo'<hr><pre>  menu_access_alt2=>';print_r($menu_access_alt2);echo'</pre>'; 
               #echo'<hr><pre>  menu_accessv2=>';print_r($menu_accessv2);echo'</pre>';  
                    
                   # echo'<hr><pre>  arall2=>';print_r($arall2);echo'</pre>'; 

                    #echo'<hr><pre>  accesse_url_arr_in_current_status=>';print_r($accesse_url_arr_in_current_status);echo'</pre>'; 
                   # echo'<pre>  access_status=>';print_r($access_status);echo'</pre>'; 
                    #echo'<pre>  access_status_url=>';print_r($access_status_url);echo'</pre>'; 
                    #echo'<pre>  accesse_url_all=>';print_r($accesse_url_all);echo'</pre>'; 
                    

     #echo'<hr><pre>  accesse_url_arr_in_current=>';print_r($accesse_url_arr_in_current);echo'<pre>';  Die();
     $dataall=array('cache_msg'=>$cache_msg,
                    'cache_key'=>$cache_key,
                    'cachetime'=>$cachetime,
                    'cache_day'=>$cache_day,
                    'list'=>$menu_arr,
                    'urlnow'=>$urlnow,
                    'urlnow_explode_ar_0'=>$urlnow_explode_ar_0,
                    'urlnow_explode_ar_1'=>$urlnow_explode_ar_1,
                    'urlnow_explode_ar_2'=>$urlnow_explode_ar_2,
                    'urlnow_explode_ar_3'=>$urlnow_explode_ar_3,
                    'urlnow_explode_ar_4'=>$urlnow_explode_ar_4,
                    'urlnow_explode_ar_5'=>$urlnow_explode_ar_5,
                    'mainlistnow'=>$mainlistnow,
                    'submenulisturlnow'=>$submenulisturlnow,
                    'access_status_url'=>(int)$access_status_url,
                    'access_status'=>(int)$access_status,
                    'user_type_id'=>$user_type_id,
                    'cache_type'=>$cache_type,
                    'deletekey'=>$deletekey,
                    'user_idx'=>$user_idx,
                    'menusaccessed_user'=>$menusaccessed_user,
                    'accesse_url_current'=>$accesse_url_merge,
                    'accesse_url_merge'=>$accesse_url_merge1,
                    'accesse_url'=>$accesse_url_all,
                    'accesse_url_all'=>$accesse_url_all,
                    'accesse_url_1'=>$accesse_url_arr,
                    'accesse_url_alt'=>$accesse_url_alt_arr,
                    'accesse_url_arr_in_current'=>$accesse_url_arr_in_current,
                    'accesse_url_arr_in_current_count'=>$accesse_url_arr_in_current_count,
                    'accesse_url_arr_in_current_status'=>$accesse_url_arr_in_current_status,
                    'menusaccessed_alt'=> $menu_access_alt,
                    'menu_user_arr'=>$menu_user_arr,
                    'menu_access_url'=>$menu_access_url,
                    'menuidins_last_sql_query'=>$menuidins_last_sql_query,
               );
              # echo'<hr><pre>  dataall=>';print_r($dataall);echo'<pre>';  Die();
               if($debug==1){
                    echo'<hr><pre>  dataall=>';print_r($dataall);echo'<pre>';  Die();
               }
          return $dataall; 
     }
##################################
public function encryptCookie($value){
     $this->_CI=&get_instance();
     $this->_CI->config->load('encryptkey'); 
     $key=$this->_CI->config->item('key'); // $keyed = 'connexted@true!#';
     $status=(int)$this->_CI->config->item('status'); 
     if($status==0){ return $value;}else{
          if(!$value){return false;}
          // $keyed = 'connexted@true!#';
          $text = $value;
          $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
          $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
          $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
          return trim(base64_encode($crypttext)); //encode for cookie
     }
  }  
public function decryptCookie($value){
     $this->_CI=&get_instance();
     $this->_CI->config->load('encryptkey'); 
     $key=$this->_CI->config->item('key');  // $keyed = 'connexted@true!#';
     $status=(int)$this->_CI->config->item('status'); 
     if($status==0){ return $value;}else{
     if(!$value){return false;}
     $crypttext = base64_decode($value); //decode cookie
     $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
     $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
     $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
     return trim($decrypttext);
     }
  }
public function encryptCookietrue($value){
     if(!$value){return false;}
     $this->_CI=&get_instance();
     $this->_CI->config->load('encryptkey'); 
     $key=$this->_CI->config->item('key'); // $keyed = 'connexted@true!#';
     $text = $value;
     $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
     $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
     $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
     return trim(base64_encode($crypttext)); //encode for cookie
  }  
public function decryptCookietrue($value){
     if(!$value){return false;}
     $this->_CI=&get_instance();
     $this->_CI->config->load('encryptkey'); 
     $key=$this->_CI->config->item('key'); // $keyed = 'connexted@true!#';
     $crypttext = base64_decode($value); //decode cookie
     $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
     $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
     $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
     return trim($decrypttext);
  }
public function encryptCookieV1($value){
     if(!$value){return false;}
     $this->_CI=&get_instance();
     $this->_CI->config->load('encryptkey'); 
     $key=$this->_CI->config->item('key'); // $keyed = 'connexted@true!#';
     $text = $value;
     $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
     $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
     $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
     return trim(base64_encode($crypttext)); //encode for cookie
  }  
public function decryptCookieV1($value){
     if(!$value){return false;}
     $this->_CI=&get_instance();
     $this->_CI->config->load('encryptkey'); 
     $key=$this->_CI->config->item('key'); // $keyed = 'connexted@true!#';
     $crypttext = base64_decode($value); //decode cookie
     $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
     $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
     $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
     return trim($decrypttext);
     /*
     
      var queryAPI = function (request_object,callback)
        {
            var app_key = 'sdffkjhdsjfhsdjkfhsdkj';
            var app_secret = 'hfszdhfkjzxjkcxzkjb';
            var app_url = 'http://www.veepiz.com/api/jsonp.php';
            var enc_request = $.toJSON(request_object);
            var ciphertext =encode64(Crypto.AES.encrypt(enc_request, app_secret, { mode: new Crypto.mode.ECB }));
            $.post(app_url,{'app_id':app_key,'enc_request':ciphertext},
            function (data)
            {
                console.log(data);
            },'jsonp');

        }
     */
  }
###########################################
public function set_session_cookie($sesdata){
     $this->CI->_CI=&get_instance();
      #echo'<hr><pre>  set_session_cookie=>';print_r($sesdata);echo'<pre>';Die();
      $this->CI->load->library('Accessuser_library');
      $session_cookie_get=$this->CI->accessuser_library->session_cookie_get();
      $_COOKIE=$session_cookie_get['COOKIE'];
      $SESSION=$session_cookie_get['SESSION'];
      $time=60*60*60*24;
      $set_session_data=array('userid'=>$sesdata['user_id'],
                              'company'=>$sesdata['company'],
                              'company_group'=>$sesdata['company_group'],
                              'user_id'=>$sesdata['user_id'],
                              'useridx'=>$sesdata['useridx'],
                              'user_idx'=>$sesdata['user_idx'],
                              'uname'=>$sesdata['uname'],
                              'user_name'=>$sesdata['user_name'],
                              'fullname'=>$sesdata['fullname'],
                              'position'=>$sesdata['position'],
                              'user_type_id'=>$sesdata['user_type_id'],
                              'user_type_name'=>$sesdata['user_type_name'],
                              'subtype'=>$sesdata['subtype'],
                              'utype'=>$sesdata['utype'],
                              'user_status'=>$sesdata['user_status'],
                              'school_code'=>$sesdata['school_code'],
                              'school_name'=>$sesdata['school_name'],
                              'school_province'=>$sesdata['school_province'],
                              'domain'=>$sesdata['domain'],
                              ); 
            /*
            $this->CI->accessuser_library->session_set($set_session_data);
            $this->CI->accessuser_library->setDataCookie('userid',$sesdata['userid'],$time); 
            $this->CI->accessuser_library->setDataCookie('company_group',$sesdata['company_group'],$time);
            $this->CI->accessuser_library->setDataCookie('user_id',$sesdata['user_id'],$time);
            $this->CI->accessuser_library->setDataCookie('useridx',$sesdata['user_idx'],$time);
            $this->CI->accessuser_library->setDataCookie('user_idx',$sesdata['user_idx'],$time);
            $this->CI->accessuser_library->setDataCookie('uname',$sesdata['uname'],$time);
            $this->CI->accessuser_library->setDataCookie('user_name',$sesdata['uname'],$time);
            $this->CI->accessuser_library->setDataCookie('fullname',$sesdata['fullname'],$time);
            $this->CI->accessuser_library->setDataCookie('user_type_id',$sesdata['user_type_id'],$time);
            $this->CI->accessuser_library->setDataCookie('user_type_name',$sesdata['user_type_name'],$time);
            $this->CI->accessuser_library->setDataCookie('utype',$sesdata['utype'],$time);
            $this->CI->accessuser_library->setDataCookie('subtype',$sesdata['subtype'],$time);
            $this->CI->accessuser_library->setDataCookie('user_status',$sesdata['user_status'],$time);
            */
            #########################encrypt############################################
            $this->CI->accessuser_library->session_set($set_session_data);
            $this->CI->accessuser_library->setDataCookie('userid',$this->CI->accessuser_library->encryptCookie($sesdata['userid']),$time); 
            $this->CI->accessuser_library->setDataCookie('company_group',$this->CI->accessuser_library->encryptCookie($sesdata['company_group']),$time);
            $this->CI->accessuser_library->setDataCookie('user_id',$this->CI->accessuser_library->encryptCookie($sesdata['user_id']),$time);
            $this->CI->accessuser_library->setDataCookie('useridx',$this->CI->accessuser_library->encryptCookie($sesdata['user_idx']),$time);
            $this->CI->accessuser_library->setDataCookie('user_idx',$this->CI->accessuser_library->encryptCookie($sesdata['user_idx']),$time);
            $this->CI->accessuser_library->setDataCookie('uname',$this->CI->accessuser_library->encryptCookie($sesdata['uname']),$time);
            $this->CI->accessuser_library->setDataCookie('user_name',$this->CI->accessuser_library->encryptCookie($sesdata['uname']),$time);
            $this->CI->accessuser_library->setDataCookie('fullname',$this->CI->accessuser_library->encryptCookie($sesdata['fullname']),$time);
            $this->CI->accessuser_library->setDataCookie('user_type_id',$this->CI->accessuser_library->encryptCookie($sesdata['user_type_id']),$time);
            $this->CI->accessuser_library->setDataCookie('user_type_name',$this->CI->accessuser_library->encryptCookie($sesdata['user_type_name']),$time);
            $this->CI->accessuser_library->setDataCookie('utype',$this->CI->accessuser_library->encryptCookie($sesdata['utype']),$time);
            $this->CI->accessuser_library->setDataCookie('subtype',$this->CI->accessuser_library->encryptCookie($sesdata['subtype']),$time);
            $this->CI->accessuser_library->setDataCookie('user_status',$this->CI->accessuser_library->encryptCookie($sesdata['user_status']),$time);
            $this->CI->accessuser_library->setDataCookie('position',$this->CI->accessuser_library->encryptCookie($sesdata['position']),$time);
            $this->CI->accessuser_library->setDataCookie('school_code',$this->CI->accessuser_library->encryptCookie($sesdata['school_code']),$time);
      }
public function setsessionandcookie_temp(){
     $this->CI->_CI=&get_instance();
      ###################################***temp***##################################
      $this->CI->load->library('Accessuser_library');
      $session_cookie_get=$this->CI->accessuser_library->session_cookie_get();
      $COOKIEsesdata=@$session_cookie_get['COOKIE'];
      $SESSIONsesdata=@$session_cookie_get['SESSION'];
      #echo'<hr><pre>  SESSIONsesdata=>';print_r($SESSIONsesdata);echo'<pre>';Die();
 
      /*
      [userid] => 59000000
      [user_code] => TRUE
      [company_group] => TRUE
      [user_id] => 59000000
      [useridx] => 4152
      [user_idx] => 4152
      [uname] => ICT00000000
      [user_name] => ICT00000000
      [fullname] => Super Admin
      [position] => 
      [user_type_id] => 1
      [user_type_name] => Developer
      [subtype] => Developer
      [utype] => Developer
      [user_status] => 1
      [domain] => http://connexted.local/
      */
 
      $time=60*60*60*24;  
      $temp_sesdata=array('temp_userid'=>$SESSIONsesdata['user_id'],
                              'temp_user_code'=>$SESSIONsesdata['user_code'],
                              'temp_company_group'=>$SESSIONsesdata['company_group'],
                              'temp_user_id'=>$SESSIONsesdata['user_id'],
                              'temp_useridx'=>$SESSIONsesdata['useridx'],
                              'temp_user_idx'=>$SESSIONsesdata['user_idx'],
                              'temp_uname'=>$SESSIONsesdata['uname'],
                              'temp_user_name'=>$SESSIONsesdata['user_name'],
                              'temp_fullname'=>$SESSIONsesdata['fullname'],
                              'temp_position'=>$SESSIONsesdata['position'],
                              'temp_user_type_id'=>$SESSIONsesdata['user_type_id'],
                              'temp_user_type_name'=>$SESSIONsesdata['user_type_name'],
                              'temp_subtype'=>$SESSIONsesdata['subtype'],
                              'temp_utype'=>$SESSIONsesdata['utype'],
                              'temp_user_status'=>$SESSIONsesdata['user_status'],
                              'temp_school_code'=>$SESSIONsesdata['school_code'],
                              'temp_school_name'=>$SESSIONsesdata['school_name'],
                              'temp_school_province'=>$SESSIONsesdata['school_province'],
                              'temp_domain'=>base_url(),
                        );
      //echo'<hr><pre>  sesdata=>';print_r($temp_sesdata);echo'<pre>';Die();
     
      

    
      /*
            $this->CI->accessuser_library->session_set($temp_sesdata);
            $this->CI->accessuser_library->setDataCookie('temp_userid',$temp_sesdata['temp_user_id'],$time); 
            $this->CI->accessuser_library->setDataCookie('temp_user_code',$temp_sesdata['temp_user_code'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_company_group',$temp_sesdata['temp_company_group'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_user_id',$temp_sesdata['temp_user_id'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_useridx',$temp_sesdata['temp_user_idx'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_user_idx',$temp_sesdata['temp_user_idx'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_uname',$temp_sesdata['temp_uname'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_user_name',$temp_sesdata['temp_uname'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_fullname',$temp_sesdata['temp_fullname'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_user_type_id',$temp_sesdata['temp_user_type_id'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_user_type_name',$temp_sesdata['temp_user_type_name'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_utype',$temp_sesdata['temp_utype'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_subtype',$temp_sesdata['temp_subtype'],$time);
            $this->CI->accessuser_library->setDataCookie('temp_user_status',$temp_sesdata['temp_user_status'],$time);
      */
                  $encryptdata=array('temp_user_id'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_user_id']),
                                    'temp_user_code'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_user_code']),
                                    'temp_company_group'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_company_group']),
                                    'temp_useridx'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_useridx']),
                                    'temp_user_idx'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_user_idx']),
                                    'temp_user_idx'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_user_idx']),
                                    'temp_uname'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_uname']),
                                    'temp_user_name'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_user_name']),
                                    'temp_fullname'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_fullname']),
                                    'temp_position'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_position']),
                                    'temp_user_type_id'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_user_type_id']),
                                    'temp_user_type_name'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_user_type_name']),
                                    'temp_subtype'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_subtype']),
                                    'temp_utype'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_utype']),
                                    'temp_user_status'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_user_status']),
                                    'temp_school_code'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_school_code']),
                                    'temp_school_name'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_school_name']),
                                    'temp_school_province'=>$this->CI->accessuser_library->encryptCookie($temp_sesdata['temp_school_province']),
                                    'temp_domain'=>base_url(),
                              );
                  $this->CI->accessuser_library->session_set($encryptdata);
                  $this->CI->accessuser_library->setDataCookie('temp_user_id',$encryptdata['temp_user_id'],$time); 
                  $this->CI->accessuser_library->setDataCookie('temp_user_code',$encryptdata['temp_user_code'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_company_group',$encryptdata['temp_company_group'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_user_id',$encryptdata['temp_user_id'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_useridx',$encryptdata['temp_user_idx'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_user_idx',$encryptdata['temp_user_idx'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_uname',$encryptdata['temp_uname'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_user_name',$encryptdata['temp_uname'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_fullname',$encryptdata['temp_fullname'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_position',$encryptdata['temp_position'],$time);
                  @$this->CI->accessuser_library->setDataCookie('temp_user_type_id',$temencryptdatap_sesdata['temp_user_type_id'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_user_type_name',$encryptdata['temp_user_type_name'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_utype',$encryptdata['temp_utype'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_subtype',$encryptdata['temp_subtype'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_user_status',$encryptdata['temp_user_status'],$time);
                  $this->CI->accessuser_library->setDataCookie('temp_school_code',$encryptdata['temp_school_code'],$time);
                  #echo'<hr><pre>  temp_sesdata=>';print_r($temp_sesdata);echo'<pre>';Die();
       #####################################################################
   }
public function setsessionandcookie_temp_ec($sesdata){
      $this->CI->_CI=&get_instance();
      $userid=$sesdata['userid'];
      $user_code=$sesdata['user_code'];
      $company_group=$sesdata['company_group'];
      $user_id=$sesdata['user_id'];
      $user_idx=$sesdata['user_idx'];
      $uname=$sesdata['uname'];
      $user_name=$sesdata['user_name'];
      $fullname=$sesdata['fullname'];
      $position=$sesdata['position'];
      $user_type_id=$sesdata['user_type_id'];
      $user_type_name=$sesdata['user_type_name'];
      $subtype=$sesdata['subtype'];
      $utype=$sesdata['utype'];
      $user_status=$sesdata['user_status'];
      $domain=$sesdata['domain']; 
      ###################################***temp***##################################
      $this->CI->load->library('Accessuser_library');
      $session_cookie_get=$this->CI->accessuser_library->session_cookie_get();
      $COOKIEone=@$session_cookie_get['COOKIE'];
      $SESSIONone=@$session_cookie_get['SESSION'];
      $time=60*60*60*24;  
      $temp_sesdata=array('temp_userid'=>$sesdata['userid'],
                              'temp_user_code'=>$sesdata['user_code'],
                              'temp_company_group'=>$sesdata['company_group'],
                              'temp_user_id'=>$sesdata['user_id'],
                              'temp_useridx'=>$sesdata['useridx'],
                              'temp_user_idx'=>$sesdata['user_idx'],
                              'temp_uname'=>$sesdata['uname'],
                              'temp_user_name'=>$sesdata['user_name'],
                              'temp_fullname'=>$sesdata['fullname'],
                              'temp_position'=>$sesdata['position'],
                              'temp_user_type_id'=>$sesdata['user_type_id'],
                              'temp_user_type_name'=>$sesdata['user_type_name'],
                              'temp_subtype'=>$sesdata['subtype'],
                              'temp_utype'=>$sesdata['utype'],
                              'temp_user_status'=>$sesdata['user_status'],
                              'temp_domain'=>base_url(),
                        );
      //echo'<hr><pre>  sesdata=>';print_r($temp_sesdata);echo'<pre>';Die();
      $this->CI->accessuser_library->session_set($temp_sesdata);
      $this->CI->accessuser_library->setDataCookie('temp_userid',$temp_sesdata['temp_user_id'],$time); 
      $this->CI->accessuser_library->setDataCookie('temp_user_code',$temp_sesdata['temp_user_code'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_company_group',$temp_sesdata['temp_company_group'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_user_id',$temp_sesdata['temp_user_id'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_useridx',$temp_sesdata['temp_user_idx'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_user_idx',$temp_sesdata['temp_user_idx'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_uname',$temp_sesdata['temp_uname'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_user_name',$temp_sesdata['temp_uname'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_fullname',$temp_sesdata['temp_fullname'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_user_type_id',$temp_sesdata['temp_user_type_id'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_user_type_name',$temp_sesdata['temp_user_type_name'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_utype',$temp_sesdata['temp_utype'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_subtype',$temp_sesdata['temp_subtype'],$time);
      $this->CI->accessuser_library->setDataCookie('temp_user_status',$temp_sesdata['temp_user_status'],$time);
       #####################################################################
   }
public function summon_setsessionandcookie_encrypt(){
     $this->CI->_CI=&get_instance();
      ###################################***temp***##################################
      $this->CI->load->library('Accessuser_library');
      # $encryptCookie=$this->CI->accessuser_library->encryptCookie($value);
      # $decryptCookie=$this->CI->accessuser_library->decryptCookie($value);
      $session_cookie_get=$this->CI->accessuser_library->session_cookie_get();
      $COOKIE_one=@$session_cookie_get['COOKIE'];
      $SESSION_one=@$session_cookie_get['SESSION'];
      $time=60*60*60*24;  
      $summon_sesdata=array('summon_userid'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['userid']),
                              'summon_user_code'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['user_code']),
                              'summon_company_group'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['company_group']),
                              'summon_user_id'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['user_id']),
                              'summon_useridx'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['useridx']),
                              'summon_user_idx'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['user_idx']),
                              'summon_uname'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['uname']),
                              'summon_user_name'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['user_name']),
                              'summon_fullname'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['fullname']),
                              'summon_position'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['position']),
                              'summon_user_type_id'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['user_type_id']),
                              'summon_user_type_name'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['user_type_name']),
                              'summon_subtype'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['subtype']),
                              'summon_utype'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['utype']),
                              'summon_user_status'=>$this->CI->accessuser_library->encryptCookie($SESSION_one['user_status']),
                              'summon_domain'=>base_url(),
                        );
            //echo'<hr><pre>  sesdata=>';print_r($summon_sesdata);echo'<pre>';Die();
            $this->CI->accessuser_library->session_set($summon_sesdata);
            $this->CI->accessuser_library->setDataCookie('summon_userid',$this->CI->accessuser_library->encryptCookie($summon_sesdata['user_id']),$time); 
            $this->CI->accessuser_library->setDataCookie('summon_user_code',$this->CI->accessuser_library->encryptCookie($summon_sesdata['user_code']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_company_group',$this->CI->accessuser_library->encryptCookie($summon_sesdata['company_group']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_user_id',$this->CI->accessuser_library->encryptCookie($summon_sesdata['user_id']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_useridx',$this->CI->accessuser_library->encryptCookie($summon_sesdata['user_idx']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_user_idx',$this->CI->accessuser_library->encryptCookie($summon_sesdata['user_idx']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_uname',$this->CI->accessuser_library->encryptCookie($summon_sesdata['uname']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_user_name',$this->CI->accessuser_library->encryptCookie($summon_sesdata['uname']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_fullname',$this->CI->accessuser_library->encryptCookie($summon_sesdata['fullname']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_user_type_id',$this->CI->accessuser_library->encryptCookie($summon_sesdata['user_type_id']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_user_type_name',$this->CI->accessuser_library->encryptCookie($summon_sesdata['user_type_name']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_utype',$this->CI->accessuser_library->encryptCookie($summon_sesdata['utype']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_subtype',$this->CI->accessuser_library->encryptCookie($summon_sesdata['subtype']),$time);
            $this->CI->accessuser_library->setDataCookie('summon_user_status',$this->CI->accessuser_library->encryptCookie($summon_sesdata['user_status']),$time);
  
 
              //decryptSESSION
              $SESSION_summon_userid=@$_SESSION['summon_userid'];
              $SESSION_summon_userid=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_userid);
              $SESSION_summon_user_code=@$_SESSION['summon_user_code'];
              $SESSION_summon_user_code=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_user_code);
              $SESSION_summon_company_group=@$_SESSION['summon_company_group'];
              $SESSION_summon_company_group=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_company_group);
              $SESSION_summon_user_id=@$_SESSION['summon_user_id'];
              $SESSION_summon_user_id=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_user_id);
              $SESSION_summon_useridx=@$_SESSION['summon_useridx'];
              $SESSION_summon_useridx=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_useridx);
              $SESSION_summon_user_idx=@$_SESSION['summon_user_idx'];
              $SESSION_summon_user_idx=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_user_idx);
              $SESSION_summon_uname=@$_SESSION['summon_uname'];
              $SESSION_summon_uname=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_uname);
              $SESSION_summon_user_name=@$_SESSION['summon_user_name'];
              $SESSION_summon_user_name=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_user_name);
              $SESSION_summon_fullname=@$_SESSION['summon_fullname'];
              $SESSION_summon_fullname=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_fullname);
              $SESSION_summon_user_type_id=@$_SESSION['summon_user_type_id'];
              $SESSION_summon_user_type_id=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_user_type_id);
              $SESSION_summon_user_type_name=@$_SESSION['summon_user_type_name'];
              $SESSION_summon_user_type_name=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_user_type_name);
              $SESSION_summon_utype=@$_SESSION['summon_utype'];
              $SESSION_summon_utype=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_utype);
              $SESSION_summon_subtype=@$_SESSION['summon_subtype'];
              $SESSION_summon_subtype=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_subtype);
              $SESSION_summon_user_status=@$_SESSION['summon_user_status'];
              $SESSION_summon_user_status=$this->CI->accessuser_library->decryptSESSION($SESSION_summon_user_status);
 
            //decryptCookie
            $Cookie_summon_userid=@$_COOKIE['summon_userid'];
            $Cookie_summon_userid=$this->CI->accessuser_library->decryptCookie($Cookie_summon_userid);
            $Cookie_summon_user_code=@$_COOKIE['summon_user_code'];
            $Cookie_summon_user_code=$this->CI->accessuser_library->decryptCookie($Cookie_summon_user_code);
            $Cookie_summon_company_group=@$_COOKIE['summon_company_group'];
            $Cookie_summon_company_group=$this->CI->accessuser_library->decryptCookie($Cookie_summon_company_group);
            $Cookie_summon_user_id=@$_COOKIE['summon_user_id'];
            $Cookie_summon_user_id=$this->CI->accessuser_library->decryptCookie($Cookie_summon_user_id);
            $Cookie_summon_useridx=@$_COOKIE['summon_useridx'];
            $Cookie_summon_useridx=$this->CI->accessuser_library->decryptCookie($Cookie_summon_useridx);
            $Cookie_summon_user_idx=@$_COOKIE['summon_user_idx'];
            $Cookie_summon_user_idx=$this->CI->accessuser_library->decryptCookie($Cookie_summon_user_idx);
            $Cookie_summon_uname=@$_COOKIE['summon_uname'];
            $Cookie_summon_uname=$this->CI->accessuser_library->decryptCookie($Cookie_summon_uname);
            $Cookie_summon_user_name=@$_COOKIE['summon_user_name'];
            $Cookie_summon_user_name=$this->CI->accessuser_library->decryptCookie($Cookie_summon_user_name);
            $Cookie_summon_fullname=@$_COOKIE['summon_fullname'];
            $Cookie_summon_fullname=$this->CI->accessuser_library->decryptCookie($Cookie_summon_fullname);
            $Cookie_summon_user_type_id=@$_COOKIE['summon_user_type_id'];
            $Cookie_summon_user_type_id=$this->CI->accessuser_library->decryptCookie($Cookie_summon_user_type_id);
            $Cookie_summon_user_type_name=@$_COOKIE['summon_user_type_name'];
            $Cookie_summon_user_type_name=$this->CI->accessuser_library->decryptCookie($Cookie_summon_user_type_name);
            $Cookie_summon_utype=@$_COOKIE['summon_utype'];
            $Cookie_summon_utype=$this->CI->accessuser_library->decryptCookie($Cookie_summon_utype);
            $Cookie_summon_subtype=@$_COOKIE['summon_subtype'];
            $Cookie_summon_subtype=$this->CI->accessuser_library->decryptCookie($Cookie_summon_subtype);
            $Cookie_summon_user_status=@$_COOKIE['summon_user_status'];
            $Cookie_summon_user_status=$this->CI->accessuser_library->decryptCookie($Cookie_summon_user_status);
  
      }
 ###########################################
public function cleanall(){
    $this->_CI=&get_instance();
    $this->CI->load->library('Accessuser_library');
    $this->CI->accessuser_library->cleantempsessionandcookie();
    $this->CI->accessuser_library->cleansessionandcookie();
    $this->CI->accessuser_library->cleansummonsessionandcookie();
  }
public function cleantempsessionandcookie(){
          $this->_CI=&get_instance();
     #####################################################################
          $this->CI->load->library('Accessuser_library');
           $session_cookie_get=$this->CI->accessuser_library->session_cookie_get();
           $_COOKIE=$session_cookie_get['COOKIE'];
           $SESSION=$session_cookie_get['SESSION'];
           $temp_user_code=@$_COOKIE['temp_user_code'];
           $temp_company_group=@$_COOKIE['temp_company_group'];
           $temp_cookie_userid=@$_COOKIE['temp_userid'];
           $temp_cookie_user_id=@$_COOKIE['temp_user_id'];
           $temp_cookie_useridx=@$_COOKIE['temp_useridx'];
           $temp_cookie_user_idx=@$_COOKIE['temp_user_idx'];
           $temp_cookie_uname=@$_COOKIE['temp_uname'];
           $temp_cookie_user_name=@$_COOKIE['temp_user_name'];
           $temp_cookie_fullname=@$_COOKIE['temp_fullname'];
           $temp_cookie_user_type_id=@$_COOKIE['temp_user_type_id'];
           $temp_cookie_user_type_name=@$_COOKIE['temp_user_type_name'];
           $temp_cookie_utype=@$_COOKIE['temp_utype'];
           $temp_cookie_user_type_name=@$_COOKIE['temp_user_type_name'];
           $temp_cookie_subtype=@$_COOKIE['temp_subtype'];
           $temp_cookie_user_status=@$_COOKIE['temp_user_status'];
           $temp_school_code=@$_COOKIE['temp_school_code'];
           $temp_position=@$_COOKIE['temp_position']; 
          $this->CI->accessuser_library->deleteCookie('temp_user_code',$temp_user_code);
          $this->CI->accessuser_library->deleteCookie('temp_company_group',$temp_company_group);
          $this->CI->accessuser_library->deleteCookie('temp_userid',$temp_cookie_userid);
          $this->CI->accessuser_library->deleteCookie('temp_user_id',$temp_cookie_user_id);
          $this->CI->accessuser_library->deleteCookie('temp_useridx',$temp_cookie_useridx);
          $this->CI->accessuser_library->deleteCookie('temp_user_idx',$temp_cookie_user_idx);
          $this->CI->accessuser_library->deleteCookie('temp_uname',$temp_cookie_uname);
          $this->CI->accessuser_library->deleteCookie('temp_user_name',$temp_cookie_user_name);
          $this->CI->accessuser_library->deleteCookie('temp_fullname',$temp_cookie_fullname);
          $this->CI->accessuser_library->deleteCookie('temp_user_type_id',$temp_cookie_user_type_id);
          $this->CI->accessuser_library->deleteCookie('temp_user_type_name',$temp_cookie_user_type_name);
          $this->CI->accessuser_library->deleteCookie('temp_utype',$temp_cookie_utype);
          $this->CI->accessuser_library->deleteCookie('temp_subtype',$temp_cookie_subtype);
          $this->CI->accessuser_library->deleteCookie('temp_position',$temp_position);
          $this->CI->accessuser_library->deleteCookie('temp_user_status',$temp_cookie_user_status);
          $this->CI->accessuser_library->deleteCookie('temp_school_code',$temp_school_code);
          $this->CI->session->unset_userdata('temp_userid');
          $this->CI->session->unset_userdata('temp_user_code');
          $this->CI->session->unset_userdata('temp_company_group');
          $this->CI->session->unset_userdata('temp_useridx');
          $this->CI->session->unset_userdata('temp_uname');
          $this->CI->session->unset_userdata('temp_user_name');
          $this->CI->session->unset_userdata('temp_fullname');
          $this->CI->session->unset_userdata('temp_user_type_id');
          $this->CI->session->unset_userdata('temp_user_type_name');
          $this->CI->session->unset_userdata('temp_subtype');
          $this->CI->session->unset_userdata('temp_utype');
          $this->CI->session->unset_userdata('temp_user_status');
          $this->CI->session->unset_userdata('temp_domain');
          $this->CI->session->unset_userdata('temp_user_id');
          $this->CI->session->unset_userdata('temp_user_idx');
          $this->CI->session->unset_userdata('temp_position');
          $this->CI->session->unset_userdata('temp_school_code');
          $this->CI->session->unset_userdata('temp_school_name');
          $this->CI->session->unset_userdata('temp_school_province');
     #####################################################################
     }
public function cleansessionandcookie(){
          $this->_CI=&get_instance();
          #####################################################################
          $this->CI->load->library('Accessuser_library');
           $session_cookie_get=$this->CI->accessuser_library->session_cookie_get();
           $_COOKIE=$session_cookie_get['COOKIE'];
           $SESSION=$session_cookie_get['SESSION'];
           $user_code=@$_COOKIE['user_code'];
           $company_group=@$_COOKIE['company_group'];
           $cookie_userid=@$_COOKIE['userid'];
           $cookie_user_id=@$_COOKIE['user_id'];
           $cookie_useridx=@$_COOKIE['useridx'];
           $cookie_user_idx=@$_COOKIE['user_idx'];
           $cookie_uname=@$_COOKIE['uname'];
           $cookie_user_name=@$_COOKIE['user_name'];
           $cookie_fullname=@$_COOKIE['fullname'];
           $cookie_user_type_id=@$_COOKIE['user_type_id'];
           $cookie_user_type_name=@$_COOKIE['user_type_name'];
           $cookie_utype=@$_COOKIE['utype'];
           $cookie_user_type_name=@$_COOKIE['user_type_name'];
           $cookie_subtype=@$_COOKIE['subtype'];
           $cookie_user_status=@$_COOKIE['user_status'];
           $cookie_position=@$_COOKIE['position']; 
           $cookie_school_code=@$_COOKIE['school_code']; 
          $this->CI->accessuser_library->deleteCookie('user_code',$user_code);
          $this->CI->accessuser_library->deleteCookie('company_group',$company_group);
          $this->CI->accessuser_library->deleteCookie('userid',$cookie_userid);
          $this->CI->accessuser_library->deleteCookie('user_id',$cookie_user_id);
          $this->CI->accessuser_library->deleteCookie('useridx',$cookie_useridx);
          $this->CI->accessuser_library->deleteCookie('user_idx',$cookie_user_idx);
          $this->CI->accessuser_library->deleteCookie('uname',$cookie_uname);
          $this->CI->accessuser_library->deleteCookie('user_name',$cookie_user_name);
          $this->CI->accessuser_library->deleteCookie('fullname',$cookie_fullname);
          $this->CI->accessuser_library->deleteCookie('user_type_id',$cookie_user_type_id);
          $this->CI->accessuser_library->deleteCookie('user_type_name',$cookie_user_type_name);
          $this->CI->accessuser_library->deleteCookie('utype',$cookie_utype);
          $this->CI->accessuser_library->deleteCookie('subtype',$cookie_subtype);
          $this->CI->accessuser_library->deleteCookie('user_status',$cookie_user_status); 
          $this->CI->accessuser_library->deleteCookie('position',$cookie_position); 
          $this->CI->accessuser_library->deleteCookie('school_code',$cookie_school_code); 
          $this->CI->session->unset_userdata('userid');
          $this->CI->session->unset_userdata('user_code');
          $this->CI->session->unset_userdata('company_group');
          $this->CI->session->unset_userdata('useridx');
          $this->CI->session->unset_userdata('uname');
          $this->CI->session->unset_userdata('user_name');
          $this->CI->session->unset_userdata('fullname');
          $this->CI->session->unset_userdata('user_type_id');
          $this->CI->session->unset_userdata('user_type_name');
          $this->CI->session->unset_userdata('subtype');
          $this->CI->session->unset_userdata('utype');
          $this->CI->session->unset_userdata('user_status');
          $this->CI->session->unset_userdata('domain');
          $this->CI->session->unset_userdata('user_id');
          $this->CI->session->unset_userdata('user_idx');
          $this->CI->session->unset_userdata('position');
          $this->CI->session->unset_userdata('school_code');
          $this->CI->session->unset_userdata('school_name');
          $this->CI->session->unset_userdata('school_province');
           #####################################################################
      }
public function cleansummonsessionandcookie(){
          $this->_CI=&get_instance();
           #####################################################################
          $this->CI->load->library('Accessuser_library');
           $session_cookie_get=$this->CI->accessuser_library->session_cookie_get();
           $_COOKIE=$session_cookie_get['COOKIE'];
           $SESSION=$session_cookie_get['SESSION'];
           $user_code=@$_COOKIE['summon_user_code'];
           $company_group=@$_COOKIE['summon_company_group'];
           $cookie_userid=@$_COOKIE['summon_userid'];
           $cookie_user_id=@$_COOKIE['summon_user_id'];
           $cookie_useridx=@$_COOKIE['summon_useridx'];
           $cookie_user_idx=@$_COOKIE['summon_user_idx'];
           $cookie_uname=@$_COOKIE['summon_uname'];
           $cookie_user_name=@$_COOKIE['summon_user_name'];
           $cookie_fullname=@$_COOKIE['summon_fullname'];
           $cookie_user_type_id=@$_COOKIE['summon_user_type_id'];
           $cookie_user_type_name=@$_COOKIE['summon_user_type_name'];
           $cookie_utype=@$_COOKIE['summon_utype'];
           $cookie_user_type_name=@$_COOKIE['summon_user_type_name'];
           $cookie_subtype=@$_COOKIE['summon_subtype'];
           $cookie_user_status=@$_COOKIE['summon_user_status'];
           $cookie_school_code=@$_COOKIE['summon_school_code'];
          $this->CI->accessuser_library->deleteCookie('summon_user_code',$user_code);
          $this->CI->accessuser_library->deleteCookie('summon_company_group',$company_group);
          $this->CI->accessuser_library->deleteCookie('summon_userid',$cookie_userid);
          $this->CI->accessuser_library->deleteCookie('summon_user_id',$cookie_user_id);
          $this->CI->accessuser_library->deleteCookie('summon_useridx',$cookie_useridx);
          $this->CI->accessuser_library->deleteCookie('summon_user_idx',$cookie_user_idx);
          $this->CI->accessuser_library->deleteCookie('summon_uname',$cookie_uname);
          $this->CI->accessuser_library->deleteCookie('summon_user_name',$cookie_user_name);
          $this->CI->accessuser_library->deleteCookie('summon_fullname',$cookie_fullname);
          $this->CI->accessuser_library->deleteCookie('summon_user_type_id',$cookie_user_type_id);
          $this->CI->accessuser_library->deleteCookie('summon_user_type_name',$cookie_user_type_name);
          $this->CI->accessuser_library->deleteCookie('summon_utype',$cookie_utype);
          $this->CI->accessuser_library->deleteCookie('summon_subtype',$cookie_subtype);
          $this->CI->accessuser_library->deleteCookie('summon_user_status',$cookie_user_status);
          $this->CI->accessuser_library->deleteCookie('summon_school_code',$cookie_school_code);
           #####################################################################
          $this->CI->session->unset_userdata('userid');
          $this->CI->session->unset_userdata('summon_user_code');
          $this->CI->session->unset_userdata('summon_company_group');
          $this->CI->session->unset_userdata('summon_useridx');
          $this->CI->session->unset_userdata('summon_uname');
          $this->CI->session->unset_userdata('summon_user_name');
          $this->CI->session->unset_userdata('summon_fullname');
          $this->CI->session->unset_userdata('summon_user_type_id');
          $this->CI->session->unset_userdata('summon_user_type_name');
          $this->CI->session->unset_userdata('summon_subtype');
          $this->CI->session->unset_userdata('summon_utype');
          $this->CI->session->unset_userdata('summon_user_status');
          $this->CI->session->unset_userdata('summon_domain');
          $this->CI->session->unset_userdata('summon_user_id');
          $this->CI->session->unset_userdata('summon_user_idx');
          $this->CI->session->unset_userdata('summon_position');
          $this->CI->session->unset_userdata('summon_school_code');
          $this->CI->session->unset_userdata('summon_school_name');
          $this->CI->session->unset_userdata('summon_school_province');
           #####################################################################
           /*
          $this->CI->accessuser_library->deleteCookie('user_code_encryptCookie',$_COOKIE['user_code_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('company_group_encryptCookie',$_COOKIE['company_group_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('userid_encryptCookie',$_COOKIE['userid_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('user_id_encryptCookie',$_COOKIE['user_id_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('useridx_encryptCookie',$_COOKIE['useridx_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('user_idx_encryptCookie',$_COOKIE['user_idx_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('uname_encryptCookie',$_COOKIE['uname_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('user_name_encryptCookie',$_COOKIE['user_name_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('fullname_encryptCookie',$_COOKIE['fullname_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('user_type_id_encryptCookie',$_COOKIE['user_type_id_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('user_type_name_encryptCookie',$_COOKIE['user_type_name_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('utype_encryptCookie',$_COOKIE['utype_encryptCookie']);
          $this->CI->accessuser_library->deleteCookie('subtype_encryptCookie',$_COOKIE['userid_ensubtype_encryptCookiecryptCookie']);
          $this->CI->accessuser_library->deleteCookie('user_status_encryptCookie',$_COOKIE['user_status_encryptCookie']);
           */
       }
 ###########################################
}