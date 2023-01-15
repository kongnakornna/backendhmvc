<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
header('Content-type: text/html; charset=utf-8');
class Historyasmlog_model extends CI_Model {
private $add;
private $edit;
private $delete;
public function __construct(){
    parent::__construct();    
    $CI =& get_instance();
          $this->load->database();
		$this->load->helper('cookie');
		$this->load->helper('date');
    }
// Function to get the client ip address
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
public function cachedb($sql,$cachekey,$cachetime,$cachetype,$deletekey){
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
    #$deletekeysdata=$this->db->cache_delete($cachekey);
    $this->db->cache_delete_all();
    }  
    ###########DB SQL query Start###########
    // Create Turn caching on
    $this->db->cache_on(); 
    // Turn caching off for this one query
    // $this->db->cache_off();
    $query=$this->db->query($sql);
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
    $this->load->driver('cache');
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
                                'time sec'=>(int)$cachetime,
                                'cachekey'=>$cachekey,
                                'cacheinfo'=>$cache_info);
        
    }elseif(!$dataresult){     
    ###########DB SQL query Start###########
    $query=$this->db->query($sql);
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
    $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
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
    $query=$this->db->query($sql);
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
    $this->load->library('Memcached_library');
    /* ############# 4-->Memory  memcached  Start##############  */  
    ##########*******memcache*******############
    //Load library
    $this->load->library('Memcached_library');     
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
    $query=$this->db->query($sql);
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
    public function select_data_log($wherearray=array()){   
    #echo'<hr><pre>wherearray=>';print_r($wherearray);echo'<pre> <hr>';  die();
    $wherearray=@$wherearray;
    $date_start=$wherearray['date_start'];
    $date_end=$wherearray['date_end'];
    $date=$wherearray['date'];
    $month=$wherearray['month'];
    $year=$wherearray['year'];
    $process=$wherearray['process'];
    $ip_addess=$wherearray['ip_addess'];
    $order_by=$wherearray['orderby'];
    if($order_by==null){$order_by='DESC';}
    $status=@$wherearray['status'];
    if($status==null){$status=1;}
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
        //echo'<pre>date_start_key =>';print_r($datestartkey);echo'<pre>';   
    }else{ $datestartkey=null;}
    if($date_end!==null){
        $date_parts_date_end= explode(' ',$date_end);
        $day_end=$date_parts_date_end[0]; 
        $time_end= $date_parts_date_end[1];
        $timeend= str_replace(':','-',$time_end);
        $dateendkey=$day_end.'-'.$timeend;  
    }else{ $dateendkey=null;}

    //echo'<pre> date_end_key =>';print_r($dateendkey);echo'<pre>';
    //echo' Form Cache <hr> <pre>    date_start =>';print_r($date_start);echo'<pre>'; die();
    ################################################ 
    ##Cach Toools Start######
    $deletekey=$wherearray['deletekey'];
    $cachekey="key-history-asm-log-user-id".$user_id.'-date_start-'.$datestartkey.'-date_end-'.$dateendkey.'-day-'.$date.'-month-'.$month.'-year-'.$year.'-order_by-'.$order_by.'-user_id-'.$user_id.'-perpage-'.$perpage.'-page-'.$page.'-process-'.$process;
    $cachetime='30';
    //cachefile 
    $cachetype='2'; 
    $this->load->model('Cachtool_model');
    $sql=null;
    $cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
    $cachechklist=$cachechk['list'];

    #echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>'; Die();

    if($cachechklist!=null){
        #echo' Form Cache <hr> <pre>  cachechk =>';print_r($cachechk);echo'<pre>';  
        $list=$cachechk['list'];
        #echo' Form Cache <hr> <pre>   list =>';print_r($list);echo'<pre>'; 
        $list=$cachechk['list'];
        $message=$cachechk['message'];
        $status=$cachechk['status'];
        $count=$cachechk['count'];
        $cachetime=$cachechk['cachetime'];
        $cache_key=$cachechk['cachekey'];
        $rs=$list;
        $dataresult=$rs;
    $cache_msg='Form Cache type file';
    }elseif($cachechklist==null){
    ################################
    /*
    select  * from tbl_user_2018 where user_id='61110127'
    */
    $this->db->cache_off();
    // $this->db->cache_delete_all();
    /*
    $this->db->select('user.user_idx,user.user_id,user.company,user.company_group,user.email,user.salutation,user.name,user.surname,user.position,user.lastlogin,log.*,type.user_type_title');
    */
    $this->db->select('user.user_idx,user.user_id,user.company,user.company_group,user.email,user.salutation,user.name,user.surname,user.position,user.lastlogin,type.user_type_title,log.log_id,log.user_type,log.date_time,log.y,log.m,log.d,log.time,log.ip_addess,log.modules,log.process,log.message,log.status,log.code');
    // First Edited
    $this->db->select('user.user_type_id');
    // End First Edited
    $this->db->from('asm_history_ams_log as log');
    $this->db->join('tbl_user_2018 as user', 'log.user_id=user.user_idx');
    $this->db->join('sd_user_type as type', 'type.user_type_id=user.user_type_id');
        if($user_id!==null){
            $this->db->where('log.user_id',$user_id);  
        }
        if($date!==null){
            $this->db->where('log.d',$date);  
        }
        if($month!==null){
            $this->db->where('log.m',$month);  
        }
        if($year!==null){
            $this->db->where('log.y',$year);  
        }
        if($ip_addess!==null){
            $this->db->where('log.ip_addess',$ip_addess);  
        }
        if($process!==null){
            $this->db->where('log.process',$process);  
        }
        if($date_start!==null && $date_end!==null){
            $this->db->where('log.date_time >=', $date_start);
            $this->db->where('log.date_time <=', $date_end); 
        }
        if($status==null){
            //$status=1;
        }else{
            $this->db->where('log.status',$status); 
        }
        
        $this->db->order_by('log_id',$order_by);
        $this->db->limit($perpage, $page);
        $query_get=$this->db->get();
        $last_query=$this->db->last_query();
        $num=$query_get->num_rows();
        $query_result=$query_get->result(); 
        $rs=$query_result;
        $dataresult=$rs;
        
    //echo'<hr><pre>   last_query=>';print_r($last_query);echo'<pre> <hr>';
    //echo'<hr><pre>   dataresult=>';print_r($dataresult);echo'<pre> <hr>';die();
    
    ################################
    $this->load->model('Cachtool_model');
    $sql=null;
    $cacheset=$this->Cachtool_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey);
    #echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
    $rs=$cacheset['list'];
    $num=count($rs);
    $cache_msg=$cacheset['message'];;
    }
    ##Cach Toools END######
    ################################################ 
    $returndatars=array('page'=>$page,
                        //'last_query'=>$last_query,
                        'perpage'=>$perpage,
                        'rs'=>$rs,
                        'num_rows'=>count($rs),
                        'cache_msg'=>$cache_msg,
                        'where_array'=>$wherearray,);

    //echo'<hr><pre>   returndatars=>';print_r($returndatars);echo'<pre> <hr>';die();

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

    $user_id=$array['user_id'];
    if($user_id==null){
        $user_id=null;
    }
    
    $this->db->cache_off();
    //$this->db->cache_delete_all();
    $this->db->select('user.user_idx,log.*,type.user_type_title');
    $this->db->from('asm_history_ams_log as log');
    $this->db->join('tbl_user_2018 as user', 'log.user_id=user.user_idx');
    $this->db->join('sd_user_type as type', 'type.user_type_id=user.user_type_id');
    
    if($user_id!==null){
    $this->db->where('log.user_id',$user_id);  
    }
    if($date!==null){
    $this->db->where('log.d',$date);  
    }
    if($month!==null){
    $this->db->where('log.m',$month);  
    }
    if($year!==null){
    $this->db->where('log.y',$year);  
    }
    if($ip_addess!==null){
    $this->db->where('log.ip_addess',$ip_addess);  
    }
    if($date_start!==null && $date_end!==null){

    $this->db->where('log.date_time >=', $date_start);
    $this->db->where('log.date_time <=', $date_end);
            
    }
    if($status==null){
    //$status=1;
    }else{
        //$this->db->where('log.status',$status); 
    }
    $num=$this->db->count_all_results();
    //echo'<hr><pre>   $num=>';print_r($num);echo'<pre> <hr>';die();
    return $num; 
    }
    public function update_data_log($arr_data=array(),$wherearray=array()){ 
    $table_log='asm_history_ams_log'; 
    $log_id=$wherearray['log_id'];
    $process=$arr_data['process'];
    $message=$arr_data['message'];
    $date_time=date('Y-m-d H:i:s');
    $data=array('process' => $process,
            'message' => $message,
            'date_time' => $date_time
    );
    $this->db->where('log_id', $log_id);
    $this->db->update($table_log,$data); 
        if ($this->db->affected_rows() > 0){
        return TRUE;
        }else{
        return FALSE;
        }
    }
public function delete_data_log($log_id){    
    $table_log='asm_history_ams_log';
    $query = $this->db->delete($table_log, array('log_id'=>$log_id));
        //return $this->db->affected_rows();
        return $query;
    }
public function truncate_data_log($code_confirm,$user_id){ 
    if($code_confirm==200 && $user_id!==null){
        $table='asm_history_ams_log';
        $this->db->from($table); 
        $queryprocess=$this->db->truncate();  
        $query_process='action allowed ไม่อนุญาตให้ดำเนินการ ';
        $date_time=date('Y-m-d H:i:s');
        $session=$this->load->library('session');
        $uname=@$_COOKIE['uname'];
        $utype=@$_COOKIE['utype'];
        $user_id=@$_COOKIE['useridx'];
        $user_type='1';
        $code='200';
        $modules='truncate';
        $process='truncate_data_log';
        $message=' ล้างข้อมูลประวัติ table asm_history_ams_log ';
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
public function update_lastuser_login_log($log_id){ 
    $table_log='tbl_user_2018'; ;
    $date_time=date('Y-m-d H:i:s');
    $data=array('lastlogin' => $date_time);
    $this->db->where('user_idx', $log_id);
    $this->db->update($table_log,$data); 
        if ($this->db->affected_rows() > 0){
        return TRUE;
        }else{
        return FALSE;
        }
    }
# data_log
public function select_user_data_log($wherearray=array()){   
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
        //echo'<pre>date_start_key =>';print_r($datestartkey);echo'<pre>';   
    }else{ $datestartkey=null;}
    if($date_end!==null){
        $date_parts_date_end= explode(' ',$date_end);
        $day_end=$date_parts_date_end[0]; 
        $time_end= $date_parts_date_end[1];
        $timeend= str_replace(':','-',$time_end);
        $dateendkey=$day_end.'-'.$timeend;  
    }else{ $dateendkey=null;}

    //echo'<pre> date_end_key =>';print_r($dateendkey);echo'<pre>';
    //echo' Form Cache <hr> <pre>    date_start =>';print_r($date_start);echo'<pre>'; die();
    ################################################ 
    ##Cach Toools Start######
    $deletekey=$wherearray['deletekey'];
    $cachekey="key-history-user-log-user-id".$user_id.'-date_start-'.$datestartkey.'-date_end-'.$dateendkey.'-day-'.$date.'-month-'.$month.'-year-'.$year.'-order_by-'.$order_by.'-user_id-'.$user_id.'-perpage-'.$perpage.'-page-'.$page;
    $cachetime='30';
    //cachefile 
    $cachetype='2'; 
    $this->load->model('Cachtool_model');
    $sql=null;
    $cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
    $cachechklist=$cachechk['list'];
    //echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>'; Die();
    if($cachechklist!=null){
        #echo' Form Cache <hr> <pre>  cachechk =>';print_r($cachechk);echo'<pre>';  
        $list=$cachechk['list'];
        #echo' Form Cache <hr> <pre>   list =>';print_r($list);echo'<pre>'; 
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
        $this->db->cache_off();
        //$this->db->cache_delete_all();
        $this->db->select('*');
        $this->db->from('asm_history_ams_log as log');
        $this->db->join('tbl_user_2018 as user', 'log.user_id=user.user_idx');
        if($user_id!==null){
            $this->db->where('user.user_idx',$user_id);  
        }
        if($date!==null){
            $this->db->where('log.d',$date);  
        }
        if($month!==null){
            $this->db->where('log.m',$month);  
        }
        if($year!==null){
            $this->db->where('log.y',$year);  
        }
        if($ip_addess!==null){
            $this->db->where('log.ip_addess',$ip_addess);  
        }
        if($date_start!==null && $date_end!==null){
            $this->db->where('log.date_time >=', $date_start);
            $this->db->where('log.date_time <=', $date_end); 
        }
        $this->db->where('log.status',$status); 
        $this->db->order_by('log_id',$order_by);
        $this->db->limit($perpage, $page);
        $query_get=$this->db->get();
        $num=$query_get->num_rows();
        $query_result=$query_get->result(); 
        $rs=$query_result;
        $dataresult=$rs;
        
    ################################
    $this->load->model('Cachtool_model');
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
public function select_user_data_log_rows($array=array()){   
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
    $this->db->cache_off();
    //$this->db->cache_delete_all();
    $this->db->select('*');
    $this->db->from('asm_history_ams_log');
    $this->db->join('tbl_user_2018', 'asm_history_ams_log.user_id=tbl_user_2018.user_idx');
    $this->db->where('asm_history_ams_log.status',$status); 
    if($user_id!==null){
    $this->db->where('tbl_user_2018.user_idx',$user_id);  
    }
    if($date!==null){
    $this->db->where('asm_history_ams_log.d',$date);  
    }
    if($month!==null){
    $this->db->where('asm_history_ams_log.m',$month);  
    }
    if($year!==null){
    $this->db->where('asm_history_ams_log.y',$year);  
    }
    if($ip_addess!==null){
    $this->db->where('log.ip_addess',$ip_addess);  
    }
    if($date_start!==null && $date_end!==null){

    $this->db->where('asm_history_ams_log.date_time >=', $date_start);
    $this->db->where('asm_history_ams_log.date_time <=', $date_end);
            
    }
    $num=$this->db->count_all_results();
    //echo'<hr><pre>   $num=>';print_r($num);echo'<pre> <hr>';die();
    return $num; 
    }
# history_user_log
public function select_data_history_user_log($wherearray=array(), $selects = []){   
    #echo'<hr><pre>wherearray=>';print_r($wherearray);echo'<pre> <hr>'; #die();
    $wherearray=@$wherearray;
    $date_start=$wherearray['date_start'];
    $date_end=$wherearray['date_end'];
    $date=$wherearray['date'];
    $month=$wherearray['month'];
    $year=$wherearray['year'];
    $process=$wherearray['process'];
    $ip_addess=$wherearray['ip_addess'];
    $order_by=$wherearray['orderby'];
    if($order_by==null){$order_by='DESC';}
    $status=@$wherearray['status'];
    if($status==null){$status=1;}
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
        //echo'<pre>date_start_key =>';print_r($datestartkey);echo'<pre>';   
    }else{ $datestartkey=null;}
    if($date_end!==null){
        $date_parts_date_end= explode(' ',$date_end);
        $day_end=$date_parts_date_end[0]; 
        $time_end= $date_parts_date_end[1];
        $timeend= str_replace(':','-',$time_end);
        $dateendkey=$day_end.'-'.$timeend;  
    }else{ $dateendkey=null;}

    //echo'<pre> date_end_key =>';print_r($dateendkey);echo'<pre>';
    //echo' Form Cache <hr> <pre>    date_start =>';print_r($date_start);echo'<pre>'; die();
    ################################################ 
    ##Cach Toools Start######
    $deletekey=$wherearray['deletekey'];
    $cachekey="key-sd-history-user_log-user-id".$user_id.'-date_start-'.$datestartkey.'-date_end-'.$dateendkey.'-day-'.$date.'-month-'.$month.'-year-'.$year.'-order_by-'.$order_by.'-user_id-'.$user_id.'-perpage-'.$perpage.'-page-'.$page.'-process-'.$process;
    $cachetime='30';
    //cachefile 
    $cachetype='2'; 
    $this->load->model('Cachtool_model');
    $sql=null;
    $cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
    $cachechklist=$cachechk['list'];
    //echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>'; Die();


    
    if($cachechklist!=null){
        #echo' Form Cache <hr> <pre>  cachechk =>';print_r($cachechk);echo'<pre>';  
        $list=$cachechk['list'];
        #echo' Form Cache <hr> <pre>   list =>';print_r($list);echo'<pre>'; 
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



        $this->db->cache_off();
        //$this->db->cache_delete_all();
        // var_dump($selects); die;
        if(!empty($selects)){
            $this->db->select($selects);
        }else{
            $this->db->select('user.user_idx,user.user_id,user.company,user.company_group,user.email,user.salutation,user.name,user.surname,user.position,user.lastlogin,type.user_type_title,log.log_id,log.user_type,log.date_time,log.y,log.m,log.d,log.time,log.ip_addess,log.modules,log.process,log.message,log.status,log.code');
            // First Edited
            $this->db->select('user.user_type_id');
            // End First Edited
        }
        $this->db->from('asm_history_ams_log as log');
        $this->db->join('tbl_user_2018 as user', 'log.user_id=user.user_idx');
        $this->db->join('sd_user_type as type', 'type.user_type_id=user.user_type_id');
        if($user_id!==null){
        $this->db->where('log.user_id',$user_id);  
        }
        if($date!==null){
        $this->db->where('log.d',$date);  
        }
        if($month!==null){
        $this->db->where('log.m',$month);  
        }
        if($year!==null){
        $this->db->where('log.y',$year);  
        }
        if($ip_addess!==null){
        $this->db->where('log.ip_addess',$ip_addess);  
        }
        if($process!==null){
            $this->db->where('log.process',$process);  
        }
        if($date_start!==null && $date_end!==null){
            $this->db->where('log.date_time >=', $date_start);
            $this->db->where('log.date_time <=', $date_end); 
        }
        $this->db->where('log.status',$status); 
        $this->db->order_by('log_id',$order_by);
        $this->db->limit($perpage, $page);
        $query_get=$this->db->get();
        $last_query=$this->db->last_query();
        $num=$query_get->num_rows();
        $query_result=$query_get->result(); 
        $rs=$query_result;
        $dataresult=$rs;
    //echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'<pre> <hr>';die();
    ################################
    $this->load->model('Cachtool_model');
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
    #echo'<hr><pre>   returndatars=>';print_r($returndatars);echo'<pre> <hr>'; die();
    return $returndatars; 
    }
    public function select_data_history_user_log_rows($array=array()){   
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
    $this->db->cache_off();
    //$this->db->cache_delete_all();
    $this->db->select('*');
    $this->db->from('asm_history_ams_log');
    $this->db->join('tbl_user_2018', 'asm_history_ams_log.user_id=tbl_user_2018.user_idx');
    $this->db->join('sd_user_type as type', 'type.user_type_id=tbl_user_2018.user_type_id');
    $this->db->where('asm_history_ams_log.status',$status); 
    if($user_id!==null){
    $this->db->where('asm_history_ams_log.user_id',$user_id);  
    }
    if($date!==null){
    $this->db->where('asm_history_ams_log.d',$date);  
    }
    if($month!==null){
    $this->db->where('asm_history_ams_log.m',$month);  
    }
    if($year!==null){
    $this->db->where('asm_history_ams_log.y',$year);  
    }
    if($ip_addess!==null){
    $this->db->where('log.ip_addess',$ip_addess);  
    }
    if($date_start!==null && $date_end!==null){

    $this->db->where('asm_history_ams_log.date_time >=', $date_start);
    $this->db->where('asm_history_ams_log.date_time <=', $date_end);
            
    }
    $num=$this->db->count_all_results();
    //echo'<hr><pre>   $num=>';print_r($num);echo'<pre> <hr>';die();
    return $num; 
    }
    public function update_data_history_user_log($arr_data=array(),$wherearray=array()){ 
    $table_log='asm_history_ams_log'; 
    $log_id=$wherearray['log_id'];
    $process=$arr_data['process'];
    $message=$arr_data['message'];
    $date_time=date('Y-m-d H:i:s');
    $data=array('process' => $process,
            'message' => $message,
            'date_time' => $date_time
    );
    $this->db->where('log_id', $log_id);
    $this->db->update($table_log,$data); 
        if ($this->db->affected_rows() > 0){
        return TRUE;
        }else{
        return FALSE;
        }
    }
public function insert_data_logs($arr_data=array()){  
    $this->load->library('Accessuser_library');
    $session_cookie_get=$this->accessuser_library->session_cookie_get();
    $COOKIE=@$session_cookie_get['COOKIE'];
    $SESSION=@$session_cookie_get['SESSION'];
    $user_type_id=@$SESSION['user_type_id'];
    $session=$this->load->library('session');
    $uname=$_COOKIE['uname'];
    $utype=$_COOKIE['utype'];
    $user_id=@$_COOKIE['useridx'];
    //echo'<hr><pre> arr_data=>';print_r($arr_data);echo'<pre><hr>'; // Die();
    if($user_id==null){$user_id=$arr_data['user_id'];}
    /*
    $session=$this->load->library('session');
    $uname=@$_COOKIE['uname'];
    $utype=@$_COOKIE['utype'];
    $user_id=@$_COOKIE['useridx'];
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
    $this->load->model("Historyasmlog_model"); 
    $datars=$this->Historyasmlog_model->insert_data_log($insertdatalog);
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
    $table_log='asm_history_ams_log';
    $insertdatalog=array('user_id'=>$user_id,
                    'user_type'=>$user_type,
                    'date_time'=>$date_time,
                    'y'=>date('Y'),
                    'm'=>date('m'),
                    'd'=>date('d'),
                    'time'=>date('H:i:s'),
                    'code'=> $code,
                    'ip_addess'=>null,//$this->get_user_ip(),
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
    /*
        $this->db->trans_start();
        $this->db->insert($table_log,$insertdatalog);
        $this->db->trans_complete();
        $insert_data_log=$this->db->insert_id();
        $affected_rows=$this->db->affected_rows();
        //echo ' affected_rows=>'.$affected_rows; die();
        
    return $affected_rows; 
    */
    $this->db->insert($table_log,$insertdatalog);
    $last_id = $this->db->insert_id();
    return $last_id; 

    }
public function insert_data_log($arr_data=array()){  
    $this->load->library('Accessuser_library');
    $session_cookie_get=$this->accessuser_library->session_cookie_get();
    $COOKIE=@$session_cookie_get['COOKIE'];
    $SESSION=@$session_cookie_get['SESSION'];
    $user_type_id=@$SESSION['user_type_id'];
    $session=$this->load->library('session');
    $uname=@$_COOKIE['uname'];
    $utype=@$_COOKIE['utype'];
    $user_id=@$_COOKIE['useridx'];
    //echo'<hr><pre> arr_data=>';print_r($arr_data);echo'<pre><hr>'; // Die();
    if($user_id==null){$user_id=$arr_data['user_id'];}
    /*
    $session=$this->load->library('session');
    $uname=@$_COOKIE['uname'];
    $utype=@$_COOKIE['utype'];
    $user_id=@$_COOKIE['useridx'];
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
    $this->load->model("Historyasmlog_model"); 
    $datars=$this->Historyasmlog_model->insert_data_log($insertdatalog);
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
    $table_log='asm_history_ams_log';
    $insertdatalog=array('user_id'=>$user_id,
                    'user_type'=>$user_type,
                    'date_time'=>$date_time,
                    'y'=>date('Y'),
                    'm'=>date('m'),
                    'd'=>date('d'),
                    'time'=>date('H:i:s'),
                    'code'=> $code,
                    'ip_addess'=>null,//$this->get_user_ip(),
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
    /*
        $this->db->trans_start();
        $this->db->insert($table_log,$insertdatalog);
        $this->db->trans_complete();
        $insert_data_log=$this->db->insert_id();
        $affected_rows=$this->db->affected_rows();
        //echo ' affected_rows=>'.$affected_rows; die();
        
    return $affected_rows; 
    */
    $this->db->insert($table_log,$insertdatalog);
    $last_id = $this->db->insert_id();
    return $last_id; 
    }
public function insert_user_data_logs($arr_data=array()){  
    $this->load->library('Accessuser_library');
    $session_cookie_get=$this->accessuser_library->session_cookie_get();
    $COOKIE=@$session_cookie_get['COOKIE'];
    $SESSION=@$session_cookie_get['SESSION'];
    $user_type_id=@$SESSION['user_type_id'];
    $session=$this->load->library('session');
    $uname=$COOKIE['uname'];
    $utype=$COOKIE['utype'];
    $user_id=$COOKIE['useridx'];
    //echo'<hr><pre> arr_data=>';print_r($arr_data);echo'<pre><hr>'; // Die();
    if($user_id==null){$user_id=$arr_data['user_id'];}
    /*
    $session=$this->load->library('session');
    $uname=@$_COOKIE['uname'];
    $utype=@$_COOKIE['utype'];
    $user_id=@$_COOKIE['useridx'];
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
    $this->load->model("Historyasmlog_model"); 
    $datars=$this->Historyasmlog_model->insert_user_data_logs($insertdatalog);
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
    $table_log='asm_history_ams_log';
    $insertdatalog=array('user_id'=>$user_id,
                    'user_type'=>$user_type_id,
                    'date_time'=>$date_time,
                    'y'=>date('Y'),
                    'm'=>date('m'),
                    'd'=>date('d'),
                    'time'=>date('H:i:s'),
                    'code'=> $code,
                    'ip_addess'=>null,//$this->get_user_ip(),
                    'modules'=>$modules,
                    'process'=>$process,
                    'message'=>$message,
                    'status'=>$status,
                );
    //echo'<hr><pre>  insertdatalog=>';print_r($insertdatalog);echo'<pre><hr>';  Die();

    $this->db->insert($table_log,$insertdatalog);
    $last_id = $this->db->insert_id();
    return $last_id; 

    }
public function insert_user_data_log($arr_data=array()){  
    $session=$this->load->library('session');
    $uname=@@$_COOKIE['uname'];
    $utype=@@$_COOKIE['utype'];
    $user_id=@@$_COOKIE['useridx'];
    //echo'<hr><pre> arr_data=>';print_r($arr_data);echo'<pre><hr>'; // Die();
    if($user_id==null){$user_id=$arr_data['user_id'];}
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
    $table_log='asm_history_ams_log';
    $insertdatalog=array('user_id'=>$user_id,
                    'user_type'=>$user_type,
                    'date_time'=>$date_time,
                    'y'=>date('Y'),
                    'm'=>date('m'),
                    'd'=>date('d'),
                    'time'=>date('H:i:s'),
                    'code'=> $code,
                    'ip_addess'=>null,//$this->get_user_ip(),
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
        $this->db->trans_start();
        $this->db->insert($table_log,$insertdatalog);
        $this->db->trans_complete();
        $insert_data_log=$this->db->insert_id();
        $affected_rows=$this->db->affected_rows();
        //echo ' affected_rows=>'.$affected_rows; die();
        
    return $affected_rows; 
    
    }
public function insert_asm_history_ams_log($arr_data=array()){  
    $this->load->library('Accessuser_library');
    $session_cookie_get=$this->accessuser_library->session_cookie_get();
    $COOKIE=@$session_cookie_get['COOKIE'];
    $SESSION=@$session_cookie_get['SESSION'];

    /*
    echo'<hr><pre>  COOKIE=>';print_r($COOKIE);echo'<pre><hr>';   
    echo'<hr><pre>  SESSION=>';print_r($SESSION);echo'<pre><hr>';  Die();
    */

    $user_idx=@$SESSION['user_idx'];
    if($user_idx==null){$user_idx=@$COOKIE['user_idx'];}
    $userid=@$SESSION['user_id']; 
    if($userid==null){$userid=@$COOKIE['user_id'];}
    $user_id=@$SESSION['user_id']; 
    if($user_id==null){$user_id=@$COOKIE['user_id'];}
    $uname=@$SESSION['uname']; 
    if($uname==null){$uname=@$COOKIE['uname'];}
    $username=@$SESSION['uname']; 
    if($username==null){$uname=@$COOKIE['uname'];}
    $company_group=@$SESSION['company_group']; 
    if($company_group==null){$uname=@$COOKIE['company_group'];}
    $fullname=@$SESSION['fullname']; 
    if($fullname==null){$fullname=@$COOKIE['fullname'];}
    $user_type_id=@$SESSION['user_type_id']; 
    if($user_type_id==null){$user_type_id=@$COOKIE['user_type_id'];} 
    $user_type_name=@$SESSION['user_type_name']; 
    if($user_type_name==null){$user_type_name=@$COOKIE['user_type_name'];}  
    $utype=$user_type_name;
    $user_type=$user_type_id;
    ###################################
    $code=$arr_data['code'];
    if($code==null){$code='200';}
    $modules=$arr_data['modules'];
    if($modules==null){$modules='user';}
    $process=$arr_data['process'];
    if($process==null){$process='process mode โดยไม่ระบบค่ามา ';}
    $message=$arr_data['message'];
    if($message==null){$message='ไม่ระบบค่ามา and process inser user log';}
    date_default_timezone_set('Asia/Bangkok');
    $date_time=date('Y-m-d H:i:s');
    $insertdatalog=array('user_id'=>$user_idx,
                    'user_type'=>$user_type_id,
                    'date_time'=>$date_time,
                    'y'=>date('Y'),
                    'm'=>date('m'),
                    'd'=>date('d'),
                    'time'=>date('H:i:s'),
                    'code'=> $code,
                    'ip_addess'=>'127.0.0.1',//$this->get_user_ip(),
                    'modules'=>$modules,
                    'process'=>$process,
                    'message'=>$message,
                    'status'=>1,
                );
    //echo'<hr><pre>  insertdatalog=>';print_r($insertdatalog);echo'<pre><hr>';  Die();
    $table_log='asm_history_ams_log';
    $this->db->insert($table_log,$insertdatalog);
    $last_id = $this->db->insert_id();
    return $last_id; 
    /*
    ############## user Historyasmlog insert Start###################
    $code='200';
    $modules='Asset Management';
    $process='View data';
    $message='Asset Management View';
    $insertdatalog=array('code'=> $code, 
                        'modules'=>$modules, // ชื่อ  modules / function 
                        'process'=>$process, // การเข้าไปดำเนินการ  เช่น เพิ่ม , ลบ,แก้ไข
                        'message'=>$message, // อธิบายเพิ่ม การทำงาน
                        );
    $this->load->model("Historyasmlog_model"); 
    $datars=$this->Historyasmlog_model->insert_asm_history_ams_log($insertdatalog);
    ############### user Historylog insert  End ###################

    */
    }
public function insert_amshistoryams_log($arr_data=array()){  
    ###################################
    $session_cookie_get=$this->accessuser_library->session_cookie_get();
    $COOKIE=@$session_cookie_get['COOKIE'];
    $SESSION=@$session_cookie_get['SESSION'];
    $user_idx=@$SESSION['user_idx'];
    if($user_idx==null){$user_idx=@$COOKIE['user_idx'];}
    $userid=@$SESSION['user_id']; 
    if($userid==null){$userid=@$COOKIE['user_id'];}
    $user_id=@$SESSION['user_id']; 
    if($user_id==null){$user_id=@$COOKIE['user_id'];}
    $uname=@$SESSION['uname']; 
    if($uname==null){$uname=@$COOKIE['uname'];}
    $username=@$SESSION['uname']; 
    if($username==null){$uname=@$COOKIE['uname'];}
    $company_group=@$SESSION['company_group']; 
    if($company_group==null){$uname=@$COOKIE['company_group'];}
    $fullname=@$SESSION['fullname']; 
    if($fullname==null){$fullname=@$COOKIE['fullname'];}
    $user_type_id=@$SESSION['user_type_id']; 
    if($user_type_id==null){$user_type_id=@$COOKIE['user_type_id'];} 
    $user_type_name=@$SESSION['user_type_name']; 
    if($user_type_name==null){$user_type_name=@$COOKIE['user_type_name'];}  
    $utype=$user_type_name;
    $user_type=$user_type_id;
    ###################################
    $code=$arr_data['code'];
    if($code==null){$code='200';}
    $modules=$arr_data['modules'];
    if($modules==null){$modules='user';}
    $process=$arr_data['process'];
    if($process==null){$process='process mode โดยไม่ระบบค่ามา ';}
    $message=$arr_data['message'];
    if($message==null){$message='ไม่ระบบค่ามา and process inser user log';}
    date_default_timezone_set('Asia/Bangkok');
    $date_time=date('Y-m-d H:i:s');
    $insertdatalog=array('user_id'=>$user_idx,
                    'user_type'=>$user_type_id,
                    'date_time'=>$date_time,
                    'y'=>date('Y'),
                    'm'=>date('m'),
                    'd'=>date('d'),
                    'time'=>date('H:i:s'),
                    'code'=> $code,
                    'ip_addess'=>null,//$this->get_user_ip(),
                    'modules'=>$modules,
                    'process'=>$process,
                    'message'=>$message,
                    'status'=>1,
                );
    //echo'<hr><pre>  insertdatalog=>';print_r($insertdatalog);echo'<pre><hr>';  Die();
    $table_log='asm_history_ams_log';
    $this->db->insert($table_log,$insertdatalog);
    $last_id = $this->db->insert_id();
    return $last_id; 
    /*
    ############## user Historyasmlog insert Start###################
    $code='200';
    $modules='Asset Management';
    $process='View data';
    $message='Asset Management View';
    $insertdatalog=array('code'=> $code, 
                        'modules'=>$modules, // ชื่อ  modules / function 
                        'process'=>$process, // การเข้าไปดำเนินการ  เช่น เพิ่ม , ลบ,แก้ไข
                        'message'=>$message, // อธิบายเพิ่ม การทำงาน
                        );
    $this->load->model("Historyasmlog_model"); 
    $datars=$this->Historyasmlog_model->insert_asm_history_ams_log($insertdatalog);
    ############### user Historylog insert  End ###################
    */

    }
public function add_history_log($arr_data=array()){  
    $this->load->library('Accessuser_library');
    $session_cookie_get=$this->accessuser_library->session_cookie_get();
    $COOKIE=@$session_cookie_get['COOKIE'];
    $SESSION=@$session_cookie_get['SESSION'];

    /*
    echo'<hr><pre>  COOKIE=>';print_r($COOKIE);echo'<pre><hr>';   
    echo'<hr><pre>  SESSION=>';print_r($SESSION);echo'<pre><hr>';  Die();
    */
    $user_idx=@$SESSION['user_idx'];
    if($user_idx==null){$user_idx=@$COOKIE['user_idx'];}
    $userid=@$SESSION['user_id']; 
    if($userid==null){$userid=@$_COOKIE['user_id'];}
    $user_id=@$SESSION['user_id']; 
    if($user_id==null){$user_id=@$_COOKIE['user_id'];}
    $uname=@$SESSION['uname']; 
    if($uname==null){$uname=@$_COOKIE['uname'];}
    $username=@$SESSION['uname']; 
    if($username==null){$uname=@$_COOKIE['uname'];}
    $company_group=@$SESSION['company_group']; 
    if($company_group==null){$uname=@$_COOKIE['company_group'];}
    $fullname=@$SESSION['fullname']; 
    if($fullname==null){$fullname=@$_COOKIE['fullname'];}
    $user_type_id=@$SESSION['user_type_id']; 
    if($user_type_id==null){$user_type_id=@$_COOKIE['user_type_id'];} 
    $user_type_name=@$SESSION['user_type_name']; 
    if($user_type_name==null){$user_type_name=@$_COOKIE['user_type_name'];}  
    $utype=$user_type_name;
    $user_type=$user_type_id;
    ###################################
    $code=$arr_data['code'];
    if($code==null){$code='200';}
    $modules=$arr_data['modules'];
    if($modules==null){$modules='user';}
    $process=$arr_data['process'];
    if($process==null){$process='process mode โดยไม่ระบบค่ามา ';}
    $message=$arr_data['message'];
    if($message==null){$message='ไม่ระบบค่ามา and process inser user log';}
    date_default_timezone_set('Asia/Bangkok');
    $date_time=date('Y-m-d H:i:s');
    $insertdatalog=array('user_id'=>$user_idx,
                    'user_type'=>$user_type_id,
                    'date_time'=>$date_time,
                    'y'=>date('Y'),
                    'm'=>date('m'),
                    'd'=>date('d'),
                    'time'=>date('H:i:s'),
                    'code'=> $code,
                    'ip_addess'=>null,//$this->get_user_ip(),
                    'modules'=>$modules,
                    'process'=>$process,
                    'message'=>$message,
                    'status'=>1,
                );
    //echo'<hr><pre>  insertdatalog=>';print_r($insertdatalog);echo'<pre><hr>';  Die();
    $table_log='asm_history_ams_log';
    $this->db->insert($table_log,$insertdatalog);
    $last_id = $this->db->insert_id();
    return $last_id; 
    /*
    ############## user Historyasmlog insert Start###################
    $code='200';
    $modules='Asset Management';
    $process='View data';
    $message='Asset Management View';
    $insertdatalog=array('code'=> $code, 
                        'modules'=>$modules, // ชื่อ  modules / function 
                        'process'=>$process, // การเข้าไปดำเนินการ  เช่น เพิ่ม , ลบ,แก้ไข
                        'message'=>$message, // อธิบายเพิ่ม การทำงาน
                        );
    $this->load->model("Historyasmlog_model"); 
    $datars=$this->Historyasmlog_model->insert_asm_history_ams_log($insertdatalog);
    ############### user Historylog insert  End ###################

    */
    }
public function add_history_user_log($arr_data=array()){  
    ###################################
    $session_cookie_get=$this->accessuser_library->session_cookie_get();
    $COOKIE=@$session_cookie_get['COOKIE'];
    $SESSION=@$session_cookie_get['SESSION'];
    $user_idx=@$SESSION['user_idx'];
    if($user_idx==null){$user_idx=@$COOKIE['user_idx'];}
    $userid=@$SESSION['user_id']; 
    if($userid==null){$userid=@$_COOKIE['user_id'];}
    $user_id=@$SESSION['user_id']; 
    if($user_id==null){$user_id=@$_COOKIE['user_id'];}
    $uname=@$SESSION['uname']; 
    if($uname==null){$uname=@$_COOKIE['uname'];}
    $username=@$SESSION['uname']; 
    if($username==null){$uname=@$_COOKIE['uname'];}
    $company_group=@$SESSION['company_group']; 
    if($company_group==null){$uname=@$_COOKIE['company_group'];}
    $fullname=@$SESSION['fullname']; 
    if($fullname==null){$fullname=@$_COOKIE['fullname'];}
    $user_type_id=@$SESSION['user_type_id']; 
    if($user_type_id==null){$user_type_id=@$_COOKIE['user_type_id'];} 
    $user_type_name=@$SESSION['user_type_name']; 
    if($user_type_name==null){$user_type_name=@$_COOKIE['user_type_name'];}  
    $utype=$user_type_name;
    $user_type=$user_type_id;
    ###################################
    $code=$arr_data['code'];
    if($code==null){$code='200';}
    $modules=$arr_data['modules'];
    if($modules==null){$modules='user';}
    $process=$arr_data['process'];
    if($process==null){$process='process mode โดยไม่ระบบค่ามา ';}
    $message=$arr_data['message'];
    if($message==null){$message='ไม่ระบบค่ามา and process inser user log';}
    date_default_timezone_set('Asia/Bangkok');
    $date_time=date('Y-m-d H:i:s');
    $insertdatalog=array('user_id'=>$user_idx,
                    'user_type'=>$user_type_id,
                    'date_time'=>$date_time,
                    'y'=>date('Y'),
                    'm'=>date('m'),
                    'd'=>date('d'),
                    'time'=>date('H:i:s'),
                    'code'=> $code,
                    'ip_addess'=>null,//$this->get_user_ip(),
                    'modules'=>$modules,
                    'process'=>$process,
                    'message'=>$message,
                    'status'=>1,
                );
    //echo'<hr><pre>  insertdatalog=>';print_r($insertdatalog);echo'<pre><hr>';  Die();
    $table_log='asm_history_ams_log';
    $this->db->insert($table_log,$insertdatalog);
    $last_id = $this->db->insert_id();
    return $last_id; 
    /*
    ############## user Historyasmlog insert Start###################
    $code='200';
    $modules='Asset Management';
    $process='View data';
    $message='Asset Management View';
    $insertdatalog=array('code'=> $code, 
                        'modules'=>$modules, // ชื่อ  modules / function 
                        'process'=>$process, // การเข้าไปดำเนินการ  เช่น เพิ่ม , ลบ,แก้ไข
                        'message'=>$message, // อธิบายเพิ่ม การทำงาน
                        );
    $this->load->model("Historyasmlog_model"); 
    $datars=$this->Historyasmlog_model->insert_asm_history_ams_log($insertdatalog);
    ############### user Historylog insert  End ###################
    */

    }
public function distinctHistoryUserLog($requests = []){   
        $table = "asm_history_ams_log";
        $filters = [];
        $this->db->select("DISTINCT(process)");
        $query = $this->db->get_where($table, $filters);

        return $query;
    }
public function distinctHistoryLog($requests = []){   
        $table = "asm_history_ams_log";
        $filters = [];
        $this->db->select("DISTINCT(process)");
        $query = $this->db->get_where($table, $filters);

        return $query;
    }
}