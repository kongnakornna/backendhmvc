<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class MenuFactory {
private $_ci;
function __construct(){
            //When the class is constructed get an instance of codeigniter so we can access it locally
            $this->_ci =& get_instance();
            $this->CI=& get_instance(); 
            $CI=& get_instance();
            $CI->load->database();
            //$this->CI->load->library('session');
            //$this->CI->load->helper('cookie','session');
            //Include the user_model so we can use it
            $this->_ci->load->model('Menu_model');
      }
public function getMenu($parent=0,$id=0,$admin_type=0,$cachetype=2,$deletekey='',$dev=0) {
            $lang=$this->_ci->lang->language['lang'];
            $status=1;
            $row = array();
            /*
                  echo '<pre>lang-> '; print_r($lang); echo '</pre>'; 
                  echo '<pre>parent-> '; print_r($parent); echo '</pre>'; 
                  echo '<pre>id-> '; print_r($id); echo '</pre>'; 
                  echo '<pre>admin_type-> '; print_r($admin_type); echo '</pre>'; 
                  echo '<pre>cachetype-> '; print_r($cachetype); echo '</pre>'; 
                  echo '<pre>deletekey-> '; print_r($deletekey); echo '</pre>'; 
                  die();   
            */ 
            if($admin_type>1){	
            //Getting all the users
            $sql=null;
            $orderby="order_by";
            $array_menu=array("parent" => $parent, "status" => $status, "lang" => $lang);
            $cachekey='Menu-Factory-getMenu-orderby-'.$orderby.'-parent-'.$parent.'-status-'.$status.'-lang-'.$lang.'-admin_type-'.$admin_type;
            $cachekeymem='MenuFactorygetMenu'.$lang;
            $cachetime=60*60*60*24*30;
            #echo '<pre>cachekey-> '; print_r($cachekey); echo '</pre>'; 
            #########################   
            if($cachetype==null){$cachetype=2;}
            if($cachetype==1){
            /* ############# 1-->cachedb  Start##############  */   
            if($deletekey==1){
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
            }
            else{
            // $this->CI->db->cache_on(); 
            $orderby = "order_by";
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where('admin_menu_id2<>','27')->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
            //$query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
            $last_query=$this->_ci->db->last_query();
                  
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  #return $users;
            }

            if($dev==1){
                  $data_result=array('data'=>$users,'message'=>'Data form database query');    
            }else{
                  $data_result=$users;    
            }
            }
            return $data_result;
            }
            elseif($cachetype==2){
            /* ############# 2-->cachefile  Start##############  */ 
            $this->CI->load->driver('cache');
            $cache_is_supported=$this->_ci->cache->file->is_supported();
            if($deletekey==1){
            $deletekeysdata=$this->_ci->cache->file->delete($cachekey);
            }
            else{
                  $deletekeysdata='null';
            }
            $path=$this->_ci->config->item('cache_path');
            //echo '<pre>path-> '; print_r($path); echo '</pre>';  die();   
            $get_metadata=$this->_ci->cache->file->get_metadata($cachekey);
            $dataresult=$this->_ci->cache->file->get($cachekey);
            if($dataresult){
                        $status_msg='form cache file';
                        $cache_info=$this->_ci->cache->file->cache_info($cachekey);
                        $dataall=array('message'=>'Data form filecache',
                                          'status'=>TRUE, 
                                          'list'=>$dataresult,
                                          'count'=>(int)count($dataresult),
                                          'cachetime'=>(int)$cachetime,
                                          'cachekey'=>$cachekey,
                                          'cacheinfo'=>$cache_info);   
            $message=$status_msg;
            if($dev==1){
                  $data_result=array('data'=>$dataresult,'message'=>$message);   
            }else{
                  $data_result=$dataresult;    
            }
            return $data_result;

            }
            elseif(!$dataresult){    
            ###########DB SQL query Start##########
                  //$this->CI->db->cache_on(); 
            $orderby = "order_by";
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where('admin_menu_id2<>','27')->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
            //$query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
            $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
            $this->_ci->cache->file->save($cachekey,$users,$cachetime);

            }else{ $users='';}
            $dataresult=$users;
            $message='form sql module cache file';

            if($dev==1){
                  $data_result=array('data'=>$dataresult,'message'=>$message);   
            }else{
                  $data_result=$users;    
            }
            return $data_result;
            }
            ###########DB SQL query End ###########               
            }
            elseif($cachetype==3){
            $key=$cachekey;
            /* ############# 3-->Redis   Start##############  */  
            #################################################################
            $this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
            $connection_redis=$this->_ci->cache->redis->connection();
            $connection=$connection_redis['status'];
            $key=$cachekey;
            $getdata=$this->_ci->cache->redis->getkeysdata($key);
            #echo '<pre>  getdata-> '; print_r($getdata); echo '</pre>'; die();
            $getkeysdata=$getdata['getdata'];
            if($deletekey==1){
            $deletekeysdata=$this->_ci->cache->redis->deletekeysdata($cachekey);
            }else{$deletekeysdata='null';}
            #############################################
            $redis_is_supported=$this->_ci->cache->redis->is_supported();
            $file_is_supported=$this->_ci->cache->file->is_supported();
            if($redis_is_supported==1 || $file_is_supported==1){
            #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
            #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
            }
            #############################################
            if(!$getkeysdata){ 
            ###########DB SQL query Start##########
            $orderby = "order_by";
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where('admin_menu_id2<>','27')->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
            //$query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
            $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  $dataresult=$users;
            }
            ###########DB SQL query End ###########
            $results_data=$this->_ci->cache->redis->setkeysdata($cachekey,$dataresult);
            //$redis_data=$results_data;
            $redis_data=$dataresult;
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

            if($dev==1){
                  $data_result=array('data'=>$redis_data,'message'=>$message);   
            }else{
                  $data_result=$redis_data;    
            }
            return $data_result;
            }
            elseif($cachetype==4){
            // $cachekey=$cachekeymem;
            /* ############# 4-->Memory  memcached  Start##############  */  
            ##########*******memcache*******############
            //Load library
            $this->CI->load->driver('cache');
            if($this->CI->cache->memcached->is_supported()){
                  $cache_info=@$this->CI->cache->memcached->cache_info();
                  //echo '<pre>  cache_info=>'; print_r($cache_info);echo '</pre>';
            }
            if($deletekey==1){$delete=@$this->CI->cache->memcached->delete($cachekey);}

            $cachedata=$this->CI->cache->memcached->get($cachekey);
            //$cachedata=$this->cache->memcached->get($cachekey);
            //echo '<pre> cachedata get=>'; print_r($cachedata);echo '</pre>'; #Die(); 
            if($cachedata==null){
            ###########DB SQL query Start##########
            $message='form sql database query for memcached';
            ###########DB SQL query Start##########
            $orderby = "order_by";
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where('admin_menu_id2<>','27')->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
            //$query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
            $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  $this->CI->cache->memcached->save($cachekey,$users,$cachetime);
                  $resultsdata=$users;
                  }else{$resultsdata=null;}
            ###########DB SQL query End ########### 
            }else{
            $message='form memcached';    
            $resultsdata=$cachedata;   
            }   
            if($dev==1){
                  $data_result=array('data'=>$resultsdata,'message'=>$message);   
            }else{
                  $data_result=$resultsdata;    
            }
            return $data_result;     
            }
            elseif($cachetype==5){
            /* ############# 4-->DB SQL Start##############  */    
            ###########DB SQL query Start##########
            // $this->CI->db->cache_on(); 
            $orderby = "order_by";
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where('admin_menu_id2<>','27')->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
            //$query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
            $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  #return $users;
            } 
            if($dev==1){
                  $data_result=array('data'=>$users,'message'=>'SQL DataBase');   
            }else{
                  $data_result=$users;    
            }
            return $data_result;  
            }
            ############cache##################

            }
            else{
            ######################### 	
            //Getting all the users
            $sql=null;
            $orderby="order_by";
            $array_menu=array("parent" => $parent, "status" => $status, "lang" => $lang);
            $cachekey='Menu-Factory-getMenu-orderby-'.$orderby.'-parent-'.$parent.'-status-'.$status.'-lang-'.$lang;
            $cachekeymem='MenuFactorygetMenu'.$lang;
            $cachetime=60*60*60*24*30;
            #echo '<pre>cachekey-> '; print_r($cachekey); echo '</pre>'; 
            #########################   
            if($cachetype==null){$cachetype=2;}
            if($cachetype==1){
            /* ############# 1-->cachedb  Start##############  */   
            if($deletekey==1){
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
            }
            else{
            // $this->CI->db->cache_on(); 
                  $query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
                  $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  #return $users;
            }

            if($dev==1){
                  $data_result=array('data'=>$users,'message'=>'Data form database query');    
            }else{
                  $data_result=$users;    
            }
            }
            return $data_result;
            }
            elseif($cachetype==2){
            /* ############# 2-->cachefile  Start##############  */ 
            $this->CI->load->driver('cache');
            $cache_is_supported=$this->_ci->cache->file->is_supported();
            if($deletekey==1){$deletekeysdata=$this->_ci->cache->file->delete($cachekey);}
            $path=$this->_ci->config->item('cache_path');
            $get_metadata=$this->_ci->cache->file->get_metadata($cachekey);
            $dataresult=$this->_ci->cache->file->get($cachekey);
            if($dataresult){
                        $status_msg='form cache file';
                        $cache_info=$this->_ci->cache->file->cache_info($cachekey);
                        $dataall=array('message'=>'Data form filecache',
                                          'status'=>TRUE, 
                                          'list'=>$dataresult,
                                          'count'=>(int)count($dataresult),
                                          'cachetime'=>(int)$cachetime,
                                          'cachekey'=>$cachekey,
                                          'cacheinfo'=>$cache_info);   
            $message=$status_msg;
            if($dev==1){
                  $data_result=array('data'=>$dataresult,'message'=>$message);   
            }else{
                  $data_result=$dataresult;    
            }
            return $data_result;

            }
            elseif(!$dataresult){    
            ###########DB SQL query Start##########
                  //$this->CI->db->cache_on(); 
                  $query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
                  $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
            $this->_ci->cache->file->save($cachekey,$users,$cachetime);

            }else{ $users='';}
            $dataresult=$users;
            $message='form sql module cache file';

            if($dev==1){
                  $data_result=array('data'=>$dataresult,'message'=>$message);   
            }else{
                  $data_result=$users;    
            }
            return $data_result;
            }
            ###########DB SQL query End ###########               
            }
            elseif($cachetype==3){
            $key=$cachekey;
            /* ############# 3-->Redis   Start##############  */  
            #################################################################
            $this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
            $connection_redis=$this->_ci->cache->redis->connection();
            $connection=$connection_redis['status'];
            $key=$cachekey;
            $getdata=$this->_ci->cache->redis->getkeysdata($key);
            #echo '<pre>  getdata-> '; print_r($getdata); echo '</pre>'; die();
            $getkeysdata=$getdata['getdata'];
            if($deletekey==1){
            $deletekeysdata=$this->_ci->cache->redis->deletekeysdata($cachekey);
            }else{$deletekeysdata='null';}
            #############################################
            $redis_is_supported=$this->_ci->cache->redis->is_supported();
            $file_is_supported=$this->_ci->cache->file->is_supported();
            if($redis_is_supported==1 || $file_is_supported==1){
            #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
            #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
            }
            #############################################
            if(!$getkeysdata){ 
            ###########DB SQL query Start##########
                  $query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
                  $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  $dataresult=$users;
            }
            ###########DB SQL query End ###########
            $results_data=$this->_ci->cache->redis->setkeysdata($cachekey,$dataresult);
            //$redis_data=$results_data;
            $redis_data=$dataresult;
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

            if($dev==1){
                  $data_result=array('data'=>$redis_data,'message'=>$message);   
            }else{
                  $data_result=$redis_data;    
            }
            return $data_result;
            }
            elseif($cachetype==4){
            // $cachekey=$cachekeymem;
            /* ############# 4-->Memory  memcached  Start##############  */  
            ##########*******memcache*******############
            //Load library
            $this->CI->load->driver('cache');
            if($this->CI->cache->memcached->is_supported()){
                  $cache_info=@$this->CI->cache->memcached->cache_info();
                  //echo '<pre>  cache_info=>'; print_r($cache_info);echo '</pre>';
            }
            if($deletekey==1){$delete=@$this->CI->cache->memcached->delete($cachekey);}

            $cachedata=$this->CI->cache->memcached->get($cachekey);
            //$cachedata=$this->cache->memcached->get($cachekey);
            //echo '<pre> cachedata get=>'; print_r($cachedata);echo '</pre>'; #Die(); 
            if($cachedata==null){
            ###########DB SQL query Start##########
            $message='form sql database query for memcached';
            ###########DB SQL query Start##########
                  $query=$this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
                  $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  $this->CI->cache->memcached->save($cachekey,$users,$cachetime);
                  $resultsdata=$users;
                  }else{$resultsdata=null;}
            ###########DB SQL query End ########### 
            }else{
            $message='form memcached';    
            $resultsdata=$cachedata;   
            }   
            if($dev==1){
                  $data_result=array('data'=>$resultsdata,'message'=>$message);   
            }else{
                  $data_result=$resultsdata;    
            }
            return $data_result;     
            }
            elseif($cachetype==5){
            /* ############# 4-->DB SQL Start##############  */    
            ###########DB SQL query Start##########
            // $this->CI->db->cache_on(); 
                  $query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
                  $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  #return $users;
            } 
            if($dev==1){
                  $data_result=array('data'=>$users,'message'=>'SQL DataBase');   
            }else{
                  $data_result=$users;    
            }
            return $data_result;  
            }
            ############cache##################
            //return false;
            }
      }   
public function getMenuParem($id=0,$dev=0,$deletekey='') {
      $lang=$this->_ci->lang->language['lang'];
      $status=1;
      /*
      $query = $this->_ci->db->select("*")->from("_admin_menu")->where(array("admin_menu_id2" => $id))->get();
      $resultdta=$query->result();
      return $resultdta;
      */
      $sql=null;
      $cachekey='Menu-Factory-getMenuParem-admin_menu_id2-'.$id.'-lang-'.$lang;
      $cachetime=60*60*60*24*30;
      #echo '<pre>cachekey-> '; print_r($cachekey); echo '</pre>'; 
      #########################   
      $cachetype=2;
      if($cachetype==null){$cachetype=2;}
      if($cachetype==1){
      /* ############# 1-->cachedb  Start##############  */   
      if($deletekey==1){
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
      }
      else{
      // $this->CI->db->cache_on(); 
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where(array("admin_menu_id2" => $id))->get();
            $last_query=$this->_ci->db->last_query();
            //echo 'last_query-> '.$last_query;
            $datars=$query->result();  	
      }
      if($dev==1){$data_result=array('data'=>$datars,'message'=>'Data form database query');    
      }else{$data_result=$datars;}
      return $data_result;
      }
      elseif($cachetype==2){
      /* ############# 2-->cachefile  Start##############  */ 
      $this->CI->load->driver('cache');
      $cache_is_supported=$this->_ci->cache->file->is_supported();
      if($deletekey==1){$deletekeysdata=$this->_ci->cache->file->delete($cachekey);}
      $path=$this->_ci->config->item('cache_path');
      $get_metadata=$this->_ci->cache->file->get_metadata($cachekey);
      $dataresult=$this->_ci->cache->file->get($cachekey);
      if($dataresult){
                  $status_msg='form cache file';
                  $cache_info=$this->_ci->cache->file->cache_info($cachekey);
                  $dataall=array('message'=>'Data form filecache',
                                    'status'=>TRUE, 
                                    'list'=>$dataresult,
                                    'count'=>(int)count($dataresult),
                                    'cachetime'=>(int)$cachetime,
                                    'cachekey'=>$cachekey,
                                    'cacheinfo'=>$cache_info);   
      $message=$status_msg;
      if($dev==1){
            $data_result=array('data'=>$dataresult,'message'=>$message);   
      }else{
            $data_result=$dataresult;    
      }
      return $data_result;

      }
      elseif(!$dataresult){    
      ###########DB SQL query Start##########
            //$this->CI->db->cache_on(); 
      $query = $this->_ci->db->select("*")->from("_admin_menu")->where(array("admin_menu_id2" => $id))->get();
      $resultdta=$query->result();
      $dataresult=$resultdta;
      $message='form sql module cache file';

      if($dev==1){
            $data_result=array('data'=>$dataresult,'message'=>$message);   
      }else{
            $data_result=$dataresult;    
      }
      return $data_result;
      }
      ###########DB SQL query End ###########               
      }
      elseif($cachetype==3){
      $key=$cachekey;
      /* ############# 3-->Redis   Start##############  */  
      #################################################################
      $this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
      $connection_redis=$this->_ci->cache->redis->connection();
      $connection=$connection_redis['status'];
      $key=$cachekey;
      $getdata=$this->_ci->cache->redis->getkeysdata($key);
      #echo '<pre>  getdata-> '; print_r($getdata); echo '</pre>'; die();
      $getkeysdata=$getdata['getdata'];
      if($deletekey==1){
      $deletekeysdata=$this->_ci->cache->redis->deletekeysdata($cachekey);
      }else{$deletekeysdata='null';}
      #############################################
      $redis_is_supported=$this->_ci->cache->redis->is_supported();
      $file_is_supported=$this->_ci->cache->file->is_supported();
      if($redis_is_supported==1 || $file_is_supported==1){
      #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
      #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
      }
      #############################################
      if(!$getkeysdata){ 
      ###########DB SQL query Start##########
      $query = $this->_ci->db->select("*")->from("_admin_menu")->where(array("admin_menu_id2" => $id))->get();
      $resultdta=$query->result();
      $dataresult=$resultdta;
      ###########DB SQL query End ###########
      $results_data=$this->_ci->cache->redis->setkeysdata($cachekey,$dataresult);
      //$redis_data=$results_data;
      $redis_data=$dataresult;
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

      if($dev==1){
            $data_result=array('data'=>$redis_data,'message'=>$message);   
      }else{
            $data_result=$redis_data;    
      }
      return $data_result;
      }
      elseif($cachetype==4){
      // $cachekey=$cachekeymem;
      /* ############# 4-->Memory  memcached  Start##############  */  
      ##########*******memcache*******############
      //Load library
      $this->CI->load->driver('cache');
      if($this->CI->cache->memcached->is_supported()){
            $cache_info=@$this->CI->cache->memcached->cache_info();
            //echo '<pre>  cache_info=>'; print_r($cache_info);echo '</pre>';
      }
      if($deletekey==1){$delete=@$this->CI->cache->memcached->delete($cachekey);}

      $cachedata=$this->CI->cache->memcached->get($cachekey);
      //$cachedata=$this->cache->memcached->get($cachekey);
      //echo '<pre> cachedata get=>'; print_r($cachedata);echo '</pre>'; #Die(); 
      if($cachedata==null){
      ###########DB SQL query Start##########
      $message='form sql database query for memcached';
      ###########DB SQL query Start##########
      $query = $this->_ci->db->select("*")->from("_admin_menu")->where(array("admin_menu_id2" => $id))->get();
      $resultdta=$query->result();
      $resultsdata=$resultdta;
      ###########DB SQL query End ########### 
      }else{
      $message='form memcached';    
      $resultsdata=$cachedata;   
      }   
      if($dev==1){
            $data_result=array('data'=>$resultsdata,'message'=>$message);   
      }else{
            $data_result=$resultsdata;    
      }
      return $data_result;     
      }
      elseif($cachetype==5){
      /* ############# 4-->DB SQL Start##############  */    
      ###########DB SQL query Start##########
      $query = $this->_ci->db->select("*")->from("_admin_menu")->where(array("admin_menu_id2" => $id))->get();
      $resultdta=$query->result();
      $resultsdata=$resultdta;
      if($dev==1){
            $data_result=array('data'=>$resultsdata,'message'=>'SQL DataBase');   
      }else{
            $data_result=$resultsdata;    
      }
      return $data_result;  
      }
      ############cache##################
      }  
      public function getAccessMenu($id=0,$dev=0,$deletekey='') {
      //SELECT * FROM `Siamdara`.`sd_admin_access_menu` WHERE `admin_type_id` = '2' LIMIT 0, 100;
      $lang = $this->_ci->lang->language['lang'];
      $status = 1;
      $orderby = "admin_menu_id";
      $cachetype=2;  
      $sql=null;
      $cachekey='Menu-Factory-getAccessMenu-admin_menu_id-'.$id.'-lang-'.$lang;
      $cachetime=60*60*60*1; 
      if($cachetype==2){
      /* ############# 2-->cachefile  Start##############  */ 
      $this->CI->load->driver('cache');
      $cache_is_supported=$this->_ci->cache->file->is_supported();
      if($deletekey==1){$deletekeysdata=$this->_ci->cache->file->delete($cachekey);}
      $path=$this->_ci->config->item('cache_path');
      $get_metadata=$this->_ci->cache->file->get_metadata($cachekey);
      $dataresult=$this->_ci->cache->file->get($cachekey);
      if($dataresult){
                  $status_msg='form cache file';
                  $cache_info=$this->_ci->cache->file->cache_info($cachekey);
                  $dataall=array('message'=>'Data form filecache',
                                    'status'=>TRUE, 
                                    'list'=>$dataresult,
                                    'count'=>(int)count($dataresult),
                                    'cachetime'=>(int)$cachetime,
                                    'cachekey'=>$cachekey,
                                    'cacheinfo'=>$cache_info);   
      $message=$status_msg;
      if($dev==1){
            $data_result=array('data'=>$dataresult,'message'=>$message);   
      }else{
            $data_result=$dataresult;    
      }
      return $data_result;

      }
      elseif(!$dataresult){    
      ###########DB SQL query Start##########
                  $query = $this->_ci->db->select("*")->from("_admin_access_menu")->where(array("admin_type_id" => $id))->order_by($orderby, 'ASC')->get();
                  //Check if any results were returned
                  if ($query->num_rows() > 0) {
                        //Create an array to store users
                        $users_access = array();
                        //Loop through each row returned from the query
                        foreach ($query->result() as $row) {
                              //Pass the row data to our local function which creates a new user object with the data provided and add it to the users array
                              $users_access[] = $row;
                        }
                        //Return the users array
                  }else{ $dataresult=null;  }
      $dataresult=$users_access;
      $message='form sql module cache file';

      if($dev==1){
            $data_result=array('data'=>$dataresult,'message'=>$message);   
      }else{
            $data_result=$users;    
      }
      return $data_result;
      }
      ###########DB SQL query End ###########               
      }
      else{
                  $query = $this->_ci->db->select("*")->from("_admin_access_menu")->where(array("admin_type_id" => $id))->order_by($orderby, 'ASC')->get();
                  //Check if any results were returned
                  if ($query->num_rows() > 0) {
                        //Create an array to store users
                        $users_access = array();
                        //Loop through each row returned from the query
                        foreach ($query->result() as $row) {
                              //Pass the row data to our local function which creates a new user object with the data provided and add it to the users array
                              $users_access[] = $row;
                        }
                        //Return the users array
                        return $users_access;
                  }else{return false;  }
                  
      }
    }
public function updateMenu($obj = array()){

				/*echo "<pre>";
				print_r($obj);
				echo "</pre>";*/
				//echo "did = ".$obj['did'];

				/*$this->_ci->db
					->set('cover', 'CASE WHEN `image_id` = $is_cover THEN 1 ELSE 0 END', FALSE)
					->where('album_id', $album_id)
					->update('images');*/

				//$data=array('last_login'=>current_login,'current_login'=>date('Y-m-d H:i:s'));

				$this->_ci->db->where('admin_menu_id2',$obj['admin_menu_id2']);
				$this->_ci->db->update('_admin_menu',$obj);

    		//return false;	
	
	}
public function accessMenu($obj){
      //echo '<pre>$obj-> '; print_r($obj); echo '</pre>'; die();
		$i=0;
		$this->_ci->db->where('admin_type_id', $obj[0]['admin_type_id']);
		$this->_ci->db->delete('_admin_access_menu');
		$this->_ci->db->insert_batch("_admin_access_menu",$obj);		
	}	
public function getSearch($keyword = "") {

			//$orderby = "did";
			$orderby = "admin_menu_id2";

			$this->_ci->db->from('_admin_menu');
			//$this->_ci->db->from('_products');
			//$this->_ci->db->join('_products', 'thc_domains.domain = thc_products.domian ', 'left');

			$this->_ci->db->order_by($orderby, 'ASC');
			//$this->_ci->db->limit(25, 0);
			$this->_ci->db->select('*');

			if ($keyword){

				   $searchkey = "(`title` like '%$keyword%')";

				   //$this->_ci->db->like('domain', $keyword);
				   //$this->_ci->db->or_like('register_date', $keyword);
				   //$this->_ci->db->or_like('expire_date', $keyword);
				   //$this->_ci->db->or_like('nextdue_date', $keyword);
			 }

			//$where = "_products`.`domian` = `thc_domains`.`domain` $searchkey";
			$where = $searchkey;
			$this->_ci->db->where($where);

			$query = $this->_ci->db->get();  


    		//Check if any results were returned
    		if ($query->num_rows() > 0) {
    			//Create an array to store users
    			$uses = array();
    			//Loop through each row returned from the query
    			foreach ($query->result() as $row) {
    				//Pass the row data to our local function which creates a new user object with the data provided and add it to the users array
    				$uses[] = $this->createObjectFromData($row);
    			}
    			//Return the users array
    			return $uses;
    		}
    		return false;	
	}
public function createObjectFromData($row){

			$menus = new Menu_Model();
			$menus->setObj($row);
			return $menus;
    }
public function getMenu1($parent = 0 ,$id = 0, $admin_type = 0) {
    	//Are we getting an individual user or are we getting them all
     echo'parent='.$parent;;echo'id='.$id;echo'admin_type='.$admin_type;
	 Die();
		$lang = $this->_ci->lang->language['lang'];
		$status = 1;
		$row = array();
		
    	if ($admin_type > 1) {

    		//Getting an individual user
    		//$query = $this->_ci->db->get_where("_admin_menu", array("admin_menu_id" => $id, "parent" => $parent, "status" => $status, "lang" => $lang));
    		
    		$sql = 'SELECT
			sd_admin_menu.admin_menu_id,	 sd_admin_menu.admin_menu_id2,
			sd_admin_menu.title, sd_admin_menu.url,	sd_admin_menu.parent,	sd_admin_menu.order_by,
			sd_admin_menu.status,	sd_admin_menu.lang,	sd_admin_access_menu.admin_access_id,
			sd_admin_access_menu.admin_type_id
			FROM sd_admin_menu Inner Join sd_admin_access_menu ON (sd_admin_access_menu.admin_menu_id = sd_admin_menu.admin_menu_id2 AND sd_admin_menu.status = 1)
			WHERE admin_type_id='.$admin_type;
    		
    		$query = $this->_ci->db->query($sql);
			//Debug($this->_ci->db->last_query());
			
    		//Check if any results were returned
    		if ($query->num_rows() > 0) {
    			//Pass the data to our local function to create an object for us and return this new object
    			foreach ($query->result() as $data) {
    				if($lang == $data->lang) $row[] = $data;
    			}
    			
    			//Debug($query->row());
    			//Debug($row);
    			//die();
    			return $row;
    			//return $this->createObjectFromData($query->row());
    		}
    		return false;
    	} else {
    		
    		//Getting all the users
			$orderby = "order_by";
    		$query = $this->_ci->db->select("*")->from("_admin_menu")->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
			//echo $this->_ci->db->last_query();

    		//Check if any results were returned
    		if ($query->num_rows() > 0) {
    			//Create an array to store users
    			$users = array();
    			//Loop through each row returned from the query
    			
    			foreach ($query->result() as $row) {
    				//Pass the row data to our local function which creates a new user object with the data provided and add it to the users array
    				$users[] = $this->createObjectFromData($row);
    			}
    			
    			/*foreach ($query->result() as $data) {
    				if($lang == $data->lang) $row[] = $data;
    			}*/
    			    			
    			//Return the users array
    			//Debug($users);
    			//die();
    			return $users;
    		}
    		return false;
    	}
    }
public function getMenu2($parent=0,$id=0,$admin_type=0,$cachetype=2,$deletekey='',$dev=0) {
            $lang=$this->_ci->lang->language['lang'];
            $status=1;
            $row = array();
            /*
            echo '<pre>lang-> '; print_r($lang); echo '</pre>'; 
            echo '<pre>parent-> '; print_r($parent); echo '</pre>'; 
            echo '<pre>id-> '; print_r($id); echo '</pre>'; 
            echo '<pre>admin_type-> '; print_r($admin_type); echo '</pre>'; 
            die();   
            */ 
            if($admin_type>1){	
            //Getting all the users
            $sql=null;
            $orderby="order_by";
            $array_menu=array("parent" => $parent, "status" => $status, "lang" => $lang);
            $cachekey='Menu-Factory-getMenu-orderby-'.$orderby.'-parent-'.$parent.'-status-'.$status.'-lang-'.$lang.'-admin_type-'.$admin_type;
            $cachekeymem='MenuFactorygetMenu'.$lang;
            $cachetime=60*60*60*24*30;
            #echo '<pre>cachekey-> '; print_r($cachekey); echo '</pre>'; 
            #########################   
            if($cachetype==null){$cachetype=2;}
            if($cachetype==1){
            /* ############# 1-->cachedb  Start##############  */   
            if($deletekey==1){
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
            }
            else{
            // $this->CI->db->cache_on(); 
            $orderby = "order_by";
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where('admin_menu_id2<>','27')->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
            //$query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
            $last_query=$this->_ci->db->last_query();
                  
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  #return $users;
            }

            if($dev==1){
                  $data_result=array('data'=>$users,'message'=>'Data form database query');    
            }else{
                  $data_result=$users;    
            }
            }
            return $data_result;
            }
            elseif($cachetype==2){
            /* ############# 2-->cachefile  Start##############  */ 
            $this->CI->load->driver('cache');
            $cache_is_supported=$this->_ci->cache->file->is_supported();
            if($deletekey==1){
            $deletekeysdata=$this->_ci->cache->file->delete($cachekey);
            }
            else{
                  $deletekeysdata='null';
            }
            $path=$this->_ci->config->item('cache_path');
            $get_metadata=$this->_ci->cache->file->get_metadata($cachekey);
            $dataresult=$this->_ci->cache->file->get($cachekey);
            if($dataresult){
                        $status_msg='form cache file';
                        $cache_info=$this->_ci->cache->file->cache_info($cachekey);
                        $dataall=array('message'=>'Data form filecache',
                                          'status'=>TRUE, 
                                          'list'=>$dataresult,
                                          'count'=>(int)count($dataresult),
                                          'cachetime'=>(int)$cachetime,
                                          'cachekey'=>$cachekey,
                                          'cacheinfo'=>$cache_info);   
            $message=$status_msg;
            if($dev==1){
                  $data_result=array('data'=>$dataresult,'message'=>$message);   
            }else{
                  $data_result=$dataresult;    
            }
            return $data_result;

            }
            elseif(!$dataresult){    
            ###########DB SQL query Start##########
                  //$this->CI->db->cache_on(); 
            $orderby = "order_by";
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where('admin_menu_id2<>','27')->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
            //$query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
            $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
            $this->_ci->cache->file->save($cachekey,$users,$cachetime);

            }else{ $users='';}
            $dataresult=$users;
            $message='form sql module cache file';

            if($dev==1){
                  $data_result=array('data'=>$dataresult,'message'=>$message);   
            }else{
                  $data_result=$users;    
            }
            return $data_result;
            }
            ###########DB SQL query End ###########               
            }
            elseif($cachetype==3){
            $key=$cachekey;
            /* ############# 3-->Redis   Start##############  */  
            #################################################################
            $this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
            $connection_redis=$this->_ci->cache->redis->connection();
            $connection=$connection_redis['status'];
            $key=$cachekey;
            $getdata=$this->_ci->cache->redis->getkeysdata($key);
            #echo '<pre>  getdata-> '; print_r($getdata); echo '</pre>'; die();
            $getkeysdata=$getdata['getdata'];
            if($deletekey==1){
            $deletekeysdata=$this->_ci->cache->redis->deletekeysdata($cachekey);
            }else{$deletekeysdata='null';}
            #############################################
            $redis_is_supported=$this->_ci->cache->redis->is_supported();
            $file_is_supported=$this->_ci->cache->file->is_supported();
            if($redis_is_supported==1 || $file_is_supported==1){
            #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
            #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
            }
            #############################################
            if(!$getkeysdata){ 
            ###########DB SQL query Start##########
            $orderby = "order_by";
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where('admin_menu_id2<>','27')->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
            //$query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
            $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  $dataresult=$users;
            }
            ###########DB SQL query End ###########
            $results_data=$this->_ci->cache->redis->setkeysdata($cachekey,$dataresult);
            //$redis_data=$results_data;
            $redis_data=$dataresult;
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

            if($dev==1){
                  $data_result=array('data'=>$redis_data,'message'=>$message);   
            }else{
                  $data_result=$redis_data;    
            }
            return $data_result;
            }
            elseif($cachetype==4){
            // $cachekey=$cachekeymem;
            /* ############# 4-->Memory  memcached  Start##############  */  
            ##########*******memcache*******############
            //Load library
            $this->CI->load->driver('cache');
            if($this->CI->cache->memcached->is_supported()){
                  $cache_info=@$this->CI->cache->memcached->cache_info();
                  //echo '<pre>  cache_info=>'; print_r($cache_info);echo '</pre>';
            }
            if($deletekey==1){$delete=@$this->CI->cache->memcached->delete($cachekey);}

            $cachedata=$this->CI->cache->memcached->get($cachekey);
            //$cachedata=$this->cache->memcached->get($cachekey);
            //echo '<pre> cachedata get=>'; print_r($cachedata);echo '</pre>'; #Die(); 
            if($cachedata==null){
            ###########DB SQL query Start##########
            $message='form sql database query for memcached';
            ###########DB SQL query Start##########
            $orderby = "order_by";
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where('admin_menu_id2<>','27')->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
            //$query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
            $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  $this->CI->cache->memcached->save($cachekey,$users,$cachetime);
                  $resultsdata=$users;
                  }else{$resultsdata=null;}
            ###########DB SQL query End ########### 
            }else{
            $message='form memcached';    
            $resultsdata=$cachedata;   
            }   
            if($dev==1){
                  $data_result=array('data'=>$resultsdata,'message'=>$message);   
            }else{
                  $data_result=$resultsdata;    
            }
            return $data_result;     
            }
            elseif($cachetype==5){
            /* ############# 4-->DB SQL Start##############  */    
            ###########DB SQL query Start##########
            // $this->CI->db->cache_on(); 
            $orderby = "order_by";
            $query = $this->_ci->db->select("*")->from("_admin_menu")->where('admin_menu_id2<>','27')->where(array("parent" => $parent, "status" => $status, "lang" => $lang))->order_by($orderby, 'ASC')->get();
            //$query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
            $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  #return $users;
            } 
            if($dev==1){
                  $data_result=array('data'=>$users,'message'=>'SQL DataBase');   
            }else{
                  $data_result=$users;    
            }
            return $data_result;  
            }
            ############cache##################

            }
            else{
            ######################### 	
            //Getting all the users
            $sql=null;
            $orderby="order_by";
            $array_menu=array("parent" => $parent, "status" => $status, "lang" => $lang);
            $cachekey='Menu-Factory-getMenu-orderby-'.$orderby.'-parent-'.$parent.'-status-'.$status.'-lang-'.$lang;
            $cachekeymem='MenuFactorygetMenu'.$lang;
            $cachetime=60*60*60*24*30;
            #echo '<pre>cachekey-> '; print_r($cachekey); echo '</pre>'; 
            #########################   
            if($cachetype==null){$cachetype=2;}
            if($cachetype==1){
            /* ############# 1-->cachedb  Start##############  */   
            if($deletekey==1){
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
            }
            else{
            // $this->CI->db->cache_on(); 
                  $query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
                  $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  #return $users;
            }

            if($dev==1){
                  $data_result=array('data'=>$users,'message'=>'Data form database query');    
            }else{
                  $data_result=$users;    
            }
            }
            return $data_result;
            }
            elseif($cachetype==2){
            /* ############# 2-->cachefile  Start##############  */ 
            $this->CI->load->driver('cache');
            $cache_is_supported=$this->_ci->cache->file->is_supported();
            if($deletekey==1){$deletekeysdata=$this->_ci->cache->file->delete($cachekey);}
            $path=$this->_ci->config->item('cache_path');
            $get_metadata=$this->_ci->cache->file->get_metadata($cachekey);
            $dataresult=$this->_ci->cache->file->get($cachekey);
            if($dataresult){
                        $status_msg='form cache file';
                        $cache_info=$this->_ci->cache->file->cache_info($cachekey);
                        $dataall=array('message'=>'Data form filecache',
                                          'status'=>TRUE, 
                                          'list'=>$dataresult,
                                          'count'=>(int)count($dataresult),
                                          'cachetime'=>(int)$cachetime,
                                          'cachekey'=>$cachekey,
                                          'cacheinfo'=>$cache_info);   
            $message=$status_msg;
            if($dev==1){
                  $data_result=array('data'=>$dataresult,'message'=>$message);   
            }else{
                  $data_result=$dataresult;    
            }
            return $data_result;

            }
            elseif(!$dataresult){    
            ###########DB SQL query Start##########
                  //$this->CI->db->cache_on(); 
                  $query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
                  $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
            $this->_ci->cache->file->save($cachekey,$users,$cachetime);

            }else{ $users='';}
            $dataresult=$users;
            $message='form sql module cache file';

            if($dev==1){
                  $data_result=array('data'=>$dataresult,'message'=>$message);   
            }else{
                  $data_result=$users;    
            }
            return $data_result;
            }
            ###########DB SQL query End ###########               
            }
            elseif($cachetype==3){
            $key=$cachekey;
            /* ############# 3-->Redis   Start##############  */  
            #################################################################
            $this->CI->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
            $connection_redis=$this->_ci->cache->redis->connection();
            $connection=$connection_redis['status'];
            $key=$cachekey;
            $getdata=$this->_ci->cache->redis->getkeysdata($key);
            #echo '<pre>  getdata-> '; print_r($getdata); echo '</pre>'; die();
            $getkeysdata=$getdata['getdata'];
            if($deletekey==1){
            $deletekeysdata=$this->_ci->cache->redis->deletekeysdata($cachekey);
            }else{$deletekeysdata='null';}
            #############################################
            $redis_is_supported=$this->_ci->cache->redis->is_supported();
            $file_is_supported=$this->_ci->cache->file->is_supported();
            if($redis_is_supported==1 || $file_is_supported==1){
            #echo '<pre>  redis_is_supported-> '; print_r($redis_is_supported); echo '</pre>';
            #echo '<pre>  file_is_supported-> '; print_r($file_is_supported); echo '</pre>'; die();   
            }
            #############################################
            if(!$getkeysdata){ 
            ###########DB SQL query Start##########
                  $query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
                  $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  $dataresult=$users;
            }
            ###########DB SQL query End ###########
            $results_data=$this->_ci->cache->redis->setkeysdata($cachekey,$dataresult);
            //$redis_data=$results_data;
            $redis_data=$dataresult;
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

            if($dev==1){
                  $data_result=array('data'=>$redis_data,'message'=>$message);   
            }else{
                  $data_result=$redis_data;    
            }
            return $data_result;
            }
            elseif($cachetype==4){
            // $cachekey=$cachekeymem;
            /* ############# 4-->Memory  memcached  Start##############  */  
            ##########*******memcache*******############
            //Load library
            $this->CI->load->driver('cache');
            if($this->CI->cache->memcached->is_supported()){
                  $cache_info=@$this->CI->cache->memcached->cache_info();
                  //echo '<pre>  cache_info=>'; print_r($cache_info);echo '</pre>';
            }
            if($deletekey==1){$delete=@$this->CI->cache->memcached->delete($cachekey);}

            $cachedata=$this->CI->cache->memcached->get($cachekey);
            //$cachedata=$this->cache->memcached->get($cachekey);
            //echo '<pre> cachedata get=>'; print_r($cachedata);echo '</pre>'; #Die(); 
            if($cachedata==null){
            ###########DB SQL query Start##########
            $message='form sql database query for memcached';
            ###########DB SQL query Start##########
                  $query=$this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
                  $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  $this->CI->cache->memcached->save($cachekey,$users,$cachetime);
                  $resultsdata=$users;
                  }else{$resultsdata=null;}
            ###########DB SQL query End ########### 
            }else{
            $message='form memcached';    
            $resultsdata=$cachedata;   
            }   
            if($dev==1){
                  $data_result=array('data'=>$resultsdata,'message'=>$message);   
            }else{
                  $data_result=$resultsdata;    
            }
            return $data_result;     
            }
            elseif($cachetype==5){
            /* ############# 4-->DB SQL Start##############  */    
            ###########DB SQL query Start##########
            // $this->CI->db->cache_on(); 
                  $query = $this->_ci->db->select("*")->from("_admin_menu")->where($array_menu)->order_by($orderby, 'ASC')->get();
                  $last_query=$this->_ci->db->last_query();
                  //echo 'last_query-> '.$last_query;
                  if ($query->num_rows()>0){
                  $users=array();
                  $rs=$query->result();
                  foreach ($rs as $key=> $row) {
                        $users[]=$this->createObjectFromData($row);
                  }
                  #return $users;
            } 
            if($dev==1){
                  $data_result=array('data'=>$users,'message'=>'SQL DataBase');   
            }else{
                  $data_result=$users;    
            }
            return $data_result;  
            }
            ############cache##################
            //return false;
            }
      }   
public function cache() {
            /////////////cache////////////
			$input=@$this->input->post(); 
			if($input==null){ $input=@$this->input->get();}
			$deletekey=@$input['deletekey'];
			if($deletekey==''){$deletekey=null;}else{$deletekey=1;}
			$cachetype='2'; 
                  $time_cach_set_min=$this->ci->config->item('time_cach_set_min');
                  $time_cach_set=$this->_ci->config->item('time_cach_set');
                  $cachetime=$time_cach_set_min;
                  #$cachetime=60*60*4;
                  $lang=$this->_ci->lang->line('lang'); 
                  $langs=$this->_ci->lang->line('langs'); 
                  $cachekey='test_'.$lang;
                  ##Cach Toools Start######
                  //cachefile 
                  $deletekey='0';
                  $cachetype='2'; 
                  $this->_ci->load->model('Cachtool_model');
                  $sql=null;
                  $cachechk=$this->_ci->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
                  $cachechklist=$cachechk['list'];
                  // echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechk);echo'<pre>'; Die();
                  if($cachechklist!=null){    // IN CACHE
                        $temp = $cachechklist;
                  }else{                      // NOT IN CACHE
                        ///// ***** เอา FUNCTION ที่ทำงานท่อนเดิม มาใส่ตรงนี้ ******
                        $ListSelect=$this->_ci->Api_model_na->user_menu($this->_ci->session->userdata('admin_type'));
                        $sql=null;
                        $cachechklist=$this->_ci->Cachtool_model->cachedbsetkey($sql,$ListSelect,$cachekey,$cachetime,$cachetype,$deletekey);
                  }
            /////////////cache////////////
            }
}
 