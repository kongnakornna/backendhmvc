<?php
class Model_cache extends CI_Model{
private $add;
private $edit;
private $delete;
private $update;
private $key;
public function __construct(){
parent::__construct();
   //$cachetime=$this->_ci->config->item('cachetime');
        $this->config->load('cacheconfig');
        $cachetime=$this->config->item('cachetime');
        $time_cach_5_min=$this->config->item('time_cach_5_min');
        $time_cach_1h=$this->config->item('time_cach_1h');
        $time_cach_2h=$this->config->item('time_cach_2h');
        $time_cach_1day=$this->config->item('time_cach_1day');
        $time_cach_3day=$this->config->item('time_cach_3day');
        $time_cach_7day=$this->config->item('time_cach_7day');
        $time_cach_15day=$this->config->item('time_cach_15day');
        $time_cach_30day=$this->config->item('time_cach_30day');
        $time_cach_365day=$this->config->item('time_cach_365day');
        $path=$this->config->item('cache_path');
        $this->load->library('encrypt');
     }
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
public function encode($secret_data){
    $this->load->library('encrypt');
    $encripted_string=$this->encrypt->encode($secret_data);
	return $encripted_string;
    }
public function decode($encripted_string){
    $this->load->library('encrypt');
    $decode_string=$this->encrypt->decode($encripted_string);
	return $decodestring;
    }
public function encode_decode_test($secret_data){
        $this->load->library('encrypt');
        $encripted_string=$this->encrypt->encode($secret_data);
        $decode_string=$this->encrypt->decode($encripted_string);
        $reed=>array('data'=>$secret_data,
                    'encode'=>$encripted_string,
                    'decode'=>$decode_string,
                );
        return $decodreedestring;
    }
public function encodekey($string,$key=''){
		$this->load->library('encrypt');
        if($key==null){$key='na@5344-key';}
		$encrypt=$this->encrypt->encode($string, $key);
		return  $encrypt;
	}
public function decodekey($encrypt,$key=''){
        $this->load->library('encrypt');
        if($key==null){$key='na@5344-key';}
        $decrypt=$this->encrypt->decode($encrypt, $key);
        return  $decrypt;
	}
public function encode_decode_key($secret_data){
            $key='secret-key-in-config';
            $this->load->library('encrypt');
            if($key==null){$key='na@5344-key';}
            $encrypt=$this->encrypt->encode($secret_data, $key);
            $decrypt=$this->encrypt->decode($encrypt, $key);
            $allrs=>array('data'=>$secret_data,
                        'encode'=>$encrypt,
                        'decode'=>$decrypt,
                        );
            return $allrs;
        }
public function base64encrypttime($data,$time='',$key='') {
            if($key==null){
                $key=$this->config->item('jwt_key');
            }
            if($key==null){
                $time_setting=20; 
            }
            $issued_at=time();
            $issued_at_date=(date("Y-m-d H:i:s",$issued_at));
            $expiration_time=$issued_at+$time_setting;  
            $expiration_time_date=(date("Y-m-d H:i:s",$expiration_time));
            #echo '<hr><pre>data=>'; print_r($data); echo '</pre><pre>expiration_time_date=>'; print_r($expiration_time_date); echo '</pre>';die();
            $data=array('data'=>$data,
                        'time_issued_at'=>(date("Y-m-d H:i:s",$issued_at)),
                        'time_expiration'=>(date("Y-m-d H:i:s",$expiration_time)),
                        'key'=>$key,
                        'time_setting'=>$time_setting
                    );
            $dataalls=base64_encode(serialize($data)); 
            #echo '<hr><pre>base64_encode=>'; print_r($dataalls); die();
            return $dataalls;
            #Die();
            }
public function base64decrypttime($data,$key='') {
            if($key==null){
                $key=$this->config->item('jwt_key');
            }
            $dataalls=unserialize(base64_decode($data));
            $data=$dataalls['data'];
            $keyrs=$dataalls['key'];
            $time_issued_at=$dataalls['time_issued_at'];
            $time_expiration=$dataalls['time_expiration'];
            $time_setting=(int)$dataalls['time_setting'];
            if($keyrs!==$key){
                    $dataalls=array('message'=>' Error key ไม่ถูกต้อง','data'=>null,);
                     return $dataalls;
                     Die();
                }
            $issued_at=strtotime($time_issued_at);
            $issued_expiration=strtotime($time_expiration);
            $now=time();
            $now=(int)$now;
            $issued_at=(int)$issued_at;
            $timecul=($now-$issued_at);
            $issued_at=$issued_at+$time_setting;   
            /*
            echo '<hr><pre>timenow=>'; print_r($now);
            echo '<pre>issued_at=>'; print_r($issued_at);
            echo '<pre>expiration=>'; print_r($issued_expiration);
            echo '<hr><pre>timecul=>'; print_r($timecul); 
            echo '<hr><pre>time_setting=>'; print_r($time_setting); 
            echo '<hr><pre>dataalls=>'; print_r($dataalls);die();
            */
                if($timecul>$time_setting){
                    $msg_time='Expired หมดเวลา Seesion มีอายุ '.$time_setting.' วินาที';
                    $dataalls=array('message'=>$msg_time,'data'=>null,);
                    return $dataalls;
                    Die();
                }else{
                    $msg_time='On time not Expired yet';
                    $dataalls=array('message'=>$msg_time,'data'=>null,);
                    return $dataalls;
                    Die();
                }
        }   
public function base64_encrypt($string, $key) {
                $result = '';
                for ($i = 0; $i < strlen($string); $i++) {
                    $char = substr($string, $i, 1);
                    $keychar = substr($key, ($i % strlen($key)) - 1, 1);
                    $char = chr(ord($char) + ord($keychar));
                    $result.=$char;
                }
        
                return base64_encode($result);
    }
public function base64_decrypt($string, $key) {
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
public function serialize($dataset) {
                $result=serialize($dataset);
                return $result;
        }
public function unserialize($dataset) {
                $result=unserialize($dataset);
                return $result;
            }
public function implode($array) {
                $result=implode(",", $array);
                return $result;
            }
public function explode($array) {
                $result=explode(",", $array);
                return $result;
            } 
public function httpGet($url){
		$ch = curl_init($url); // such as http://example.com/example.xml
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
      }
/////////////////////////
public function cachci($sql=array(),$cachekey,$cachetime,$deletekey){ 
    if($sql==null){$sql=null;}
    if($cachekey==null){ $int=(int)'0'; return $int; exit();}
    if($cachetime==null){ $int=(int)'0'; return $int; exit();}
    if($deletekey==null){ $deletekey='0';}else{$deletekey='1';}
    $this->config->load('cacheconfig');
    $path=$this->config->item('cache_path');
    $cache_time=$this->config->item('cachetime');
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
          $dataresult=$dataresult;
          ###########DB SQL query End ###########
          /*
          $dataall=array('message'=>'Data form database query save to dbcache',
                        'status'=>1, 
                        'list'=>$dataresult,
                        'count'=>(int)count($dataresult),
                        'time sec'=>>null,
                        'cachekey'=>null,
                        'cacheinfo'=>null);                  
                        */
    /* ############# 1-->cachedb  End##############  */  
    # return $dataall;
    return $dataresult;
    }
public function cachefile($dataresult=array(),$cachekey,$cachetime,$deletekey){ 
        if($dataresult==null){ $dataresult=null;}
        if($cachekey==null){ $int=(int)'0'; return $int; exit();}
        if($deletekey==null){ $deletekey=(int)'0';}else{$deletekey=(int)'1';}
        $this->config->load('cacheconfig');
        $path=$this->config->item('cache_path');
        $cache_time=$this->config->item('cachetime');
        if($cachetime==null){ $cachetime=$cache_time;}
        if($cachetime==null){ $cachetime=300;}
        ################################################
          $this->load->driver('cache');
          $cache_is_supported=$this->cache->file->is_supported();
          if($deletekey==1){ $deletekeysdata=$this->cache->file->delete($cachekey);  } 
          $path=$this->config->item('cache_path');
          $get_metadata=$this->cache->file->get_metadata($cachekey);
          $data_result=$this->cache->file->get($cachekey);
          if($data_result){
            $status_msg='form cache file';
            $cache_info=$this->cache->file->cache_info($cachekey);
            /*
                    $dataall=array('message'=>'Data form filecache',
                                'status'=>TRUE, 
                                        'list'=>$dataresult,
                                        'count'=>(int)count($dataresult),
                                        'time sec'=>(int)$cachetime,
                                        'cachekey'=>$cachekey,
                                        'cacheinfo'=>$cache_info);
             */
                return $dataresult;  exit();
            }elseif(!$data_result){     
                if($dataresult){  $this->cache->file->save($cachekey,$dataresult,$cachetime);  }
                $cache_info=$this->cache->file->cache_info($cachekey);
                        /* 
                            $dataall=array('message'=>'Data form database query save to filecache',
                                        'status'=>FALSE, 
                                        'list'=>$dataresult,
                                        'count'=>(int)count($dataresult),
                                        'time sec'=>(int)$cachetime,
                                        'cachekey'=>$cachekey,
                                        'cacheinfo'=>$cache_info);    
                          */              
                #############################################
                /* ############# 1-->cachedb  End##############  */  
                return $dataresult;  exit();
            }
        return $dataresult;  exit();
    }
public function redis($dataresult=array(),$cachekey,$cachetime,$deletekey){ 
    if($dataresult==null){ $dataresult=null;}
    if($cachekey==null){ $int=(int)'0'; return $int; exit();}
    if($cachetime==null){ $int=(int)'0'; return $int; exit();}
    if($deletekey==null){ $deletekey='0';}else{$deletekey='1';}
    $this->config->load('cacheconfig');
    $path=$this->config->item('cache_path');
    $cachetime=$this->config->item('cachetime');
 
       /* ############# 3-->Redis   Start##############  */  
          #################################################################
          $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
          $connection_redis=$this->cache->redis->connection();
          $connection=$connection_redis['status'];
          $key=$cachekey;
          $getdata=$this->cache->redis->getkeysdata($cachekey);
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
          /*
          $dataall=array('message'=>$message,
                         'Connection'=>$connection,
                         'status'=>$status, 
                         'list'=>$redis_data,
                         'redis_is_supported'=>$redis_is_supported,
                         'file_is_supported'=>$file_is_supported,
                         'list_count'=>count($redis_data),
                         'cachekey'=>$cachekey,
                         );

        */                            
          #############################################
          /* ############# 3-->Redis  End##############  */    
          
        return $redis_data;
    }
public function memcache($dataresult=array(),$cachekey,$cachetime,$deletekey){ 
    if($dataresult==null){ $dataresult=array();}
    if($cachekey==null){ $int=(int)'0'; return $int; exit();}
    if($cachetime==null){ $int=(int)'0'; return $int; exit();}
    if($deletekey==null){ $deletekey='0';}else{$deletekey='1';}
    $this->config->load('cacheconfig');
    $path=$this->config->item('cache_path');
    $cache_time=$this->config->item('cachetime');
        if($cachetime==null){ $cachetime=$cache_time;}
        if($cachetime==null){ $cachetime=300;}
    /* ############# 4-->Memory  memcached  Start##############  */  
    ##########*******memcache*******############
    //Load library
    $this->load->library('Memcached_library');     
    ##########*******memcache*******############
    // Lets try to get the key
    if($dataresult==null && $cachekey!=null){
        $results_data=$this->memcached_library->get($cachekey);
        $k='1';
        return null; exit();
    }else{
        $results_data=$this->memcached_library->get($cachekey);
        $k='0';
    }
    /*
        echo '<hr><pre> k=>'; print_r($k);echo '</pre> ';
        echo '<pre> dataresult=>'; print_r($dataresult);echo '</pre> ';
        echo '<pre> cachetime=>'; print_r($cachetime);echo '</pre> ';
        echo '<pre> deletekey=>'; print_r($deletekey);echo '</pre> ';
    */
    $type='items';
    $cache_info=$this->memcached_library->getstats($type);
    $cacheversion=$this->memcached_library->getversion();
    $cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
    # If the key does not exist it could mean the key was never set or expired
    if($deletekey==1){$this->memcached_library->delete($cachekey);}
    if($results_data==null){
         // Modify this Query to your liking!
         ###########DB SQL query Start###########
         $resultsdata=$dataresult;
         ###########DB SQL query End ###########
         // Lets store the results
         //$this->memcached_library->add($cachekey,$resultsdata);
         $this->memcached_library->add($cachekey,$dataresult,$cachetime);
         // Output a basic msg
              $message='form sql database query';
              $dataall=array('message'=>$message,
                             'status'=>0, 
                             'list'=>$resultsdata,
                             'count'=>(int)count($resultsdata),
                             'time'=>(int)$cachetime,
                             'cachekey'=>$cachekey,
                             'deletekey'=>$deletekey,
                             'sql'=>$sql,
                             );
            }else{
                    # Output
                    # Now let us delete the key for demonstration sake!
                   
                    $resultsdata=$results_data;
                    $message='form memcached';
                    $dataall=array('message'=>$message,
                                    'status'=>1, 
                                    'list'=>$resultsdata,
                                    'count'=>(int)count($resultsdata),
                                    'time'=>(int)$cachetime,
                                    'cachekey'=>$cachekey,
                                    'deletekey'=>$deletekey,
                                    'sql'=>null,
                                    #'info'=>$cacheinfo
                                    );
                }
    #echo '<hr> Model_cache public function memcache=>'; echo '<pre> dataall=>'; print_r($dataall);echo '</pre> <hr> '; //Die(); 
    return $dataall;
    ##########*******memcache*******############
    # return $resultsdata;
    }
public function memcacheencode($dataresult=array(),$cachekey,$cachetime,$deletekey){ 
        $cachekey_org=$cachekey;
        if($dataresult==null){ $dataresult=array();}
        if($cachekey==null){ $int=(int)'0'; return $int; exit();}
        if($cachetime==null){ $int=(int)'0'; return $int; exit();}
        if($deletekey==null){ $deletekey='0';}else{$deletekey='1';}
        $this->config->load('cacheconfig');
        $path=$this->config->item('cache_path');
        $cache_time=$this->config->item('cachetime');
            if($cachetime==null){ $cachetime=$cache_time;}
            if($cachetime==null){ $cachetime=300;}
        /* ############# 4-->Memory  memcached  Start##############  */  
        ##########*******memcache*******############
        $this->load->library('encrypt');
        $encripted_cachekey=$this->encrypt->encode($cachekey);
        $decode_cachekey$this->encrypt->decode($encripted_cachekey);
        $reed=>array('cachekey'=>$cachekey,
                    'encode'=>$encripted_cachekey,
                    'decode'=>$decode_cachekey,
                );
        //Load library
        $this->load->library('Memcached_library');     
        $cachekey=$encripted_cachekey;
        $decode_cachekey$this->encrypt->decode($cachekey);
        ##########*******memcache*******############
        // Lets try to get the key
        if($dataresult==null && $cachekey!=null){
            $results_data=$this->memcached_library->get($cachekey);
            $k='1';
            return null; exit();
        }else{
            $results_data=$this->memcached_library->get($cachekey);
            $k='0';
        }
        /*
            echo '<hr><pre> k=>'; print_r($k);echo '</pre> ';
            echo '<pre> dataresult=>'; print_r($dataresult);echo '</pre> ';
            echo '<pre> cachetime=>'; print_r($cachetime);echo '</pre> ';
            echo '<pre> deletekey=>'; print_r($deletekey);echo '</pre> ';
        */
        $type='items';
        $cache_info=$this->memcached_library->getstats($type);
        $cacheversion=$this->memcached_library->getversion();
        $cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
        # If the key does not exist it could mean the key was never set or expired
        if($deletekey==1){$this->memcached_library->delete($cachekey);}
        if($results_data==null){
             // Modify this Query to your liking!
             ###########DB SQL query Start###########
             $resultsdata=$dataresult;
             ###########DB SQL query End ###########
             // Lets store the results
             //$this->memcached_library->add($cachekey,$resultsdata);
             $this->memcached_library->add($cachekey,$dataresult,$cachetime);
             // Output a basic msg
                  $message='form sql database query';
                  $dataall=array('message'=>$message,
                                 'status'=>0, 
                                 'list'=>$resultsdata,
                                 'count'=>(int)count($resultsdata),
                                 'time'=>(int)$cachetime,
                                 'cachekey'=>$cachekey_org,                                 
                                 'encripted_cachekey'=>$encripted_cachekey,
                                 'decode_cachekey'=>$decode_cachekey,
                                 'deletekey'=>$deletekey,
                                 'sql'=>$sql,
                                 );
                }else{
                        # Output
                        # Now let us delete the key for demonstration sake!
                       
                        $resultsdata=$results_data;
                        $message='form memcached';
                        $dataall=array('message'=>$message,
                                        'status'=>1, 
                                        'list'=>$resultsdata,
                                        'count'=>(int)count($resultsdata),
                                        'time'=>(int)$cachetime,
                                        'cachekey'=>$cachekey_org,                                 
                                        'encripted_cachekey'=>$encripted_cachekey,
                                        'decode_cachekey'=>$decode_cachekey,
                                        'deletekey'=>$deletekey,
                                        'sql'=>null,
                                        #'info'=>$cacheinfo
                                        );
                    }
        #echo '<hr> Model_cache public function memcache=>'; echo '<pre> dataall=>'; print_r($dataall);echo '</pre> <hr> '; //Die(); 
        return $dataall;
        ##########*******memcache*******############
        # return $resultsdata;
        }
public function sqlquery($dataresult=array(),$cachekey,$cachetime,$deletekey){ 
        if($dataresult==null){ $int=(int)'0'; return $int; exit();}
            return $dataresult;
        }
public function cvs_course_examination_array($array,$deletekey){
    /*
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
                      $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
                      //$this->memcached_library->add($cachekey,$resultsdata,$cachetime);
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
    */
    }
public function cachedbgetkey($sql,$cachekey,$cachetime,$cachetype=2,$deletekey){
    ############cache##################
    #echo '<hr>';echo '<br>sql=>'.$sql;  echo '<br>cachekey=>'.$cachekey; echo '<br>cachetime=>'.$cachetime;  echo '<br>cachetype=>'.$cachetype; echo '<br>deletekey=>'.$deletekey; die();
    ############cache##################
    if($cachetype!=1 || $cachetype!=5){$sql=null;}
            if($cachetype==null){$cachetype=2;}
            /*
            echo '<br> cachetype=>'.$cachetype; 
            echo '<br> sql=>'.$sql; 
            Die();
            */
            /* ############# cache DB Start##############  */  
            if($cachetype==1 && $sql!=null){
            /* ############# 1-->cachedb  Start##############  */   
            if($deletekey==1){$this->db->cache_delete_all();}  
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
            $this->load->driver('cache');
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
            echo'<hr><pre> data result=>';print_r($dataresult);echo'<pre>';; 
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
                                'cacheinfo'=>null,//$cache_info
                        );
                
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
            $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
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
        } else{
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
    }
/////////////////////////
public function cachedb($sql,$query_result=array(),$cachekey,$cachetime,$cachetype=2,$deletekey){
        if($query_result!=null){
             $queryresult=$query_result;
            # echo '<hr> cachedb 1 sql=> '.$sql; 
            # echo '<br>cachekey=>'.$cachekey; 
            # echo '<br>cachetime=>'.$cachetime; 
            # echo '<br>cachetype=>'.$cachetype; 
            # echo '<br>deletekey=>'.$deletekey; 
            # echo'<pre> 0->query_result=>';print_r($query_result);echo'<pre>'; 
             
            }else{
                $query_result='0';
            
                # echo '<hr> cachedb 2 sql=> '.$sql; 
                # echo '<br>cachekey=>'.$cachekey; 
                # echo '<br>cachetime=>'.$cachetime; 
                # echo '<br>cachetype=>'.$cachetype; 
                # echo '<br>deletekey=>'.$deletekey; 
                # echo'<pre> 0->query_result=>';print_r($query_result);echo'<pre>'; 
            }
        #$sql=null;
        #$query_result=array();
        #$cachekey="key-xxx";
        #$cachetime='3600';
        #$cachetype='2'; //cachefile
        #$deletekey=null;
        #$this->load->model('Cachtool_model');
        #$dataformmodel=$this->Cachtool_model->cachedb($sql,$query_result,$cachekey,$cachetime,$cachetype,$deletekey);
    if($cachetype==null){$cachetype=2;}
    if($cachetype==1){
            ############# 1-->cachedb  Start##############    
            if($deletekey==1){  #$deletekeysdata=$this->db->cache_delete($cachekey);
                $this->db->cache_delete_all();
            }  
            
            ###########DB SQL query Start##########
            if($sql!==null){
                // Create Turn caching on
                $this->db->cache_on(); 
                // Turn caching off for this one query
                // $this->db->cache_off();
                $query=$this->db->query($sql);
                $dataresult=$query->result();
            }else{
                $this->db->cache_on(); 
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
    if($cachetype==2){
            ############# 2-->cachefile  Start##############
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
                                        'cachetime'=>(int)$cachetime,
                                        'cachekey'=>$cachekey,
                                        'cacheinfo'=>$cache_info);
                
            }elseif(!$dataresult){
            
            ###########DB SQL query Start##########
            if($sql!==null){
                $query=$this->db->query($sql);
                $dataresult=$query->result();  
                // echo '<pre>  dataresult1-> '; print_r($dataresult); echo '</pre>'; //die();
                }else{
                    $get_metadata=$this->cache->file->get_metadata($cachekey);
                    $dataresult=$this->cache->file->get($cachekey);
                    // echo '<pre>get_key->'; print_r($dataresult); echo '</pre>';// die();
                    $cache_is_supported=$this->cache->file->is_supported();
                    if(!$dataresult){
                        $this->cache->file->save($cachekey,$dataresult,$cachetime);
                        # echo '<pre>A->cache_is_supported->'; print_r($cache_is_supported); echo '</pre>';
                        # echo '<pre>cachekey->'; print_r($cachekey); echo '</pre>';
                        # echo '<pre>get_metadata->'; print_r($get_metadata); echo '</pre>';
                        # echo '<pre>rs dataresult2->'; print_r($dataresult); echo '</pre>'; 
                        //die();
                
                        }else{
                            # echo '<pre>B->cache_is_supported->'; print_r($cache_is_supported); echo '</pre>';
                            # echo '<pre>cachekey->'; print_r($cachekey); echo '</pre>';
                            # echo '<pre>get_metadata->'; print_r($get_metadata); echo '</pre>';
                            # echo '<pre>rs dataresult2->'; print_r($dataresult); echo '</pre>'; 
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
            ############# 1-->cachedb  End##############
            return $dataall;
        }
    if($cachetype==3){
            $key=$cachekey;
            ############# 3-->Redis   Start############## 
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
            ###########DB SQL query Start##########
            if($sql!==null){
                $query=$this->db->query($sql);
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
    if($cachetype==4){
            ############# 4-->Memory  memcached  Start############## 
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
                ###########DB SQL query Start##########
                if($sql!==null){
                    $query=$this->db->query($sql);
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
    if($cachetype==5){
            /* ############# 4-->DB SQL Start##############  */    
            ###########DB SQL query Start##########
            if($sql!==null){
                    $query=$this->db->query($sql);
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
public function cacheencodedecode($sql,$query_result=array(),$cachekey,$cachetime,$cachetype=2,$deletekey){
		$cachekey_org=$cachekey;
		$this->load->library('encrypt');
        $encripted_cachekey=$this->encrypt->encode($cachekey);
        $decode_cachekey=$this->encrypt->decode($encripted_cachekey);
        $reed=>array('cachekey'=>$cachekey,
                    'encode'=>$encripted_cachekey,
                    'decode'=>$decode_cachekey,
                );	
		$cachekey=$encripted_cachekey;
		$cachekey_org=$cachekey;
		$decodecachekey=$decode_cachekey;
		if($query_result!=null){
             $queryresult=$query_result;
            # echo '<hr> cachedb 1 sql=> '.$sql; 
            # echo '<br>cachekey=>'.$cachekey; 
            # echo '<br>cachetime=>'.$cachetime; 
            # echo '<br>cachetype=>'.$cachetype; 
            # echo '<br>deletekey=>'.$deletekey; 
            # echo'<pre> 0->query_result=>';print_r($query_result);echo'<pre>'; 
             
            }else{
                $query_result='0';
            
                # echo '<hr> cachedb 2 sql=> '.$sql; 
                # echo '<br>cachekey=>'.$cachekey; 
                # echo '<br>cachetime=>'.$cachetime; 
                # echo '<br>cachetype=>'.$cachetype; 
                # echo '<br>deletekey=>'.$deletekey; 
                # echo'<pre> 0->query_result=>';print_r($query_result);echo'<pre>'; 
            }
        #$sql=null;
        #$query_result=array();
        #$cachekey="key-xxx";
        #$cachetime='3600';
        #$cachetype='2'; //cachefile
        #$deletekey=null;
        #$this->load->model('Cachtool_model');
        #$dataformmodel=$this->Cachtool_model->cachedb($sql,$query_result,$cachekey,$cachetime,$cachetype,$deletekey);
    if($cachetype==null){$cachetype=2;}
    if($cachetype==1){
            ############# 1-->cachedb  Start##############    
            if($deletekey==1){  #$deletekeysdata=$this->db->cache_delete($cachekey);
                $this->db->cache_delete_all();
				}              
            ###########DB SQL query Start##########
            if($sql!==null){
                // Create Turn caching on
                $this->db->cache_on(); 
                // Turn caching off for this one query
                // $this->db->cache_off();
                $query=$this->db->query($sql);
                $dataresult=$query->result();
            }else{
                $this->db->cache_on(); 
                $dataresult=$query_result;  
            }
            ###########DB SQL query End ###########
                    $dataall=array('message'=>'Data form database query save to dbcache',
                                    'status'=>FALSE, 
                                    'list'=>$dataresult,
                                    'count'=>(int)count($dataresult),
                                    'cachetime'=>(int)$cachetime,
                                    'cachekey'=>$cachekey,
									'cachekey_org'=>$cachekey_org,
                                    'encripted_cachekey'=>$encripted_cachekey,
								    'decodecachekey'=>$decodecachekey,
                                    'cacheinfo'=>null);                  
    
    
    
            /* ############# 1-->cachedb  End##############  */  
            return $dataall;
        }
    if($cachetype==2){
            ############# 2-->cachefile  Start##############
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
                                        'cachetime'=>(int)$cachetime,
                                        'cachekey'=>$cachekey,
										'cachekey_org'=>$cachekey_org,
                                        'encripted_cachekey'=>$encripted_cachekey,
										'decodecachekey'=>$decodecachekey,
										'cacheinfo'=>$cache_info,
										);
                
				}elseif(!$dataresult){				
					###########DB SQL query Start##########
					if($sql!==null){
						$query=$this->db->query($sql);
						$dataresult=$query->result();  
						// echo '<pre>  dataresult1-> '; print_r($dataresult); echo '</pre>'; //die();
						}else{
							$get_metadata=$this->cache->file->get_metadata($cachekey);
							$dataresult=$this->cache->file->get($cachekey);
							// echo '<pre>get_key->'; print_r($dataresult); echo '</pre>';// die();
							$cache_is_supported=$this->cache->file->is_supported();
							if(!$dataresult){
								$this->cache->file->save($cachekey,$dataresult,$cachetime);
								# echo '<pre>A->cache_is_supported->'; print_r($cache_is_supported); echo '</pre>';
								# echo '<pre>cachekey->'; print_r($cachekey); echo '</pre>';
								# echo '<pre>get_metadata->'; print_r($get_metadata); echo '</pre>';
								# echo '<pre>rs dataresult2->'; print_r($dataresult); echo '</pre>'; 
								//die();
						
								}else{
									# echo '<pre>B->cache_is_supported->'; print_r($cache_is_supported); echo '</pre>';
									# echo '<pre>cachekey->'; print_r($cachekey); echo '</pre>';
									# echo '<pre>get_metadata->'; print_r($get_metadata); echo '</pre>';
									# echo '<pre>rs dataresult2->'; print_r($dataresult); echo '</pre>'; 
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
												'cachekey_org'=>$cachekey_org,
												'encripted_cachekey'=>$encripted_cachekey,
												'decodecachekey'=>$decodecachekey,
												'cacheinfo'=>$cache_info,
												);                 
				}
				#############################################
				#echo '<pre>  dataall-> '; print_r($dataall); echo '</pre>'; die();
				############# 1-->cachedb  End##############
            return $dataall;
        }
    if($cachetype==3){
            $key=$cachekey;
            ############# 3-->Redis   Start############## 
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
            ###########DB SQL query Start##########
            if($sql!==null){
                $query=$this->db->query($sql);
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
										'cachekey_org'=>$cachekey_org,
                                        'encripted_cachekey'=>$encripted_cachekey,
										'decodecachekey'=>$decodecachekey,
										'cacheinfo'=>$cache_info,
										);
                                            
                                            
                                            
                #############################################
                /* ############# 3-->Redis  End##############  */    
                return $dataall;
            }
    if($cachetype==4){
            ############# 4-->Memory  memcached  Start############## 
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
                ###########DB SQL query Start##########
                if($sql!==null){
                    $query=$this->db->query($sql);
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
										'cachekey_org'=>$cachekey_org,
                                        'encripted_cachekey'=>$encripted_cachekey,
										'decodecachekey'=>$decodecachekey,
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
										'cachekey_org'=>$cachekey_org,
                                        'encripted_cachekey'=>$encripted_cachekey,
										'decodecachekey'=>$decodecachekey,
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
    if($cachetype==5){
            /* ############# 4-->DB SQL Start##############  */    
            ###########DB SQL query Start##########
            if($sql!==null){
                    $query=$this->db->query($sql);
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
/////////////////////////
public function clear_all_sess_cache_file(){
        $CI =& get_instance();
        $path = $CI->config->item('sess_save_path');
        //$path = $CI->config->item('cache_path');
        //echo 'path=>'.$path; die();
        $cache_path=($path == '') ? APPPATH.'/' : $path;
        //echo 'cache_path=>'.$cache_path; die();
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
public function clearfilecache(){
        $CI =& get_instance();
        $path = $CI->config->item('cache_path');
        //echo 'path=>'.$path; die();
        $cache_path=($path == '') ? APPPATH.'/' : $path;
        //echo 'cache_path=>'.$cache_path; die();
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
public function clearfilecachesession(){
        $CI =& get_instance();
        $path = $CI->config->item('sess_save_path');
        //echo 'path=>'.$path; die();
        $cache_path=($path == '') ? APPPATH.'/' : $path;
        //echo 'cache_path=>'.$cache_path; die();
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
/////////////////////////
public function clear_all_cache_file(){
        $CI =& get_instance();
        $path = $CI->config->item('cache_path');
        //echo 'path=>'.$path; die();
        //$cache_path=($path == '') ? APPPATH.'cache/' : $path;
        $cache_path=null;
        if($cache_path==null){
             $cache_path=$path;
        }
        #echo $cache_path;
        echo '.';
        $handle = opendir($cache_path);
        while (($file = readdir($handle))!== FALSE) {
             //Leave the directory protection alone
        if ($file != '.htaccess' && $file != 'index.html'){
                  @unlink($cache_path.'/'.$file);
             }
        }
        closedir($handle);
        //$this->clear_all_sess_cache_file;
    }
}
#################################
    /*
        $this->load->library('encrypt');
        $encripted_string=$this->encrypt->encode($secret_data);
        $this->encrypt->decode($encripted_string);
        $encrypted_password = 'r5WEX++ZKggg7d6fQYAZfFOm/z3nTJmxQA00zVWhhn7cvmrSrIm/NYI51o9372qf6JtYQEil72b4JzszVo+oPg==';
        $key = 'secret-key-in-config';
        $decrypted_string = $this->encrypt->decode($encrypted_password, $key);
    */
    /* 
     ###############################################################################
          ###############################################################################
          $input=@$this->input->post();
          if($input==null){$input=@$this->input->get();}
          $deletekey=$input['deletekey'];
          if($deletekey==null){$deletekey=0;}
          // exam
          if($category_id==null){ $cachekey="key-upskill-ups-category-status-1"; }elseif($category_id!==null){ $cachekey="key-upskill-ups-category-id-".$category_id."-status-1";   }
          $cachetime='300';
          if($cachetime==null){
                  $this->config->load('cacheconfig');
                  $path=$this->config->item('cache_path');
                  $cache_time=$this->config->item('cachetime');
                  if($cachetime==null){ $cachetime=$cache_time;}
                  if($cachetime==null){ $cachetime=300;}
              }
              #############**memcache***#############
              #############**Get cache data***#############
              $this->load->model('Model_cache');
              $datarssay=array();
              $datars=$this->Model_cache->memcache($datarssay,$cachekey,$cachetime,$deletekey);
              if($datars==null || $datars==0){
                     # use load model
                      // $this->load->model('Model_upskill');
                      // $datars=$this->Model_upskill->get_type($deletekey,$cachetype);
                     # use load model
                     #############**sql query data***#############
                         ###########DB SQL query Start###########
                        //
                        //       if($category_id==null){
                        //            $sql="select * from ups_category where status=1";
                        //            $cachekey="key-upskill-ups-category-status-1";  
                        //       }elseif($category_id!==null){
                        //            $sql="select * from ups_category where category_id=$category_id and status=1";
                        //            $cachekey="key-upskill-ups-category-id-".$category_id."-status-1";  
                        //       }
                        //       $query=$this->db->query($sql);
                        //      $resultsdata=$query->result();
                         //
                         //Model_upskill
                         $this->load->model('Model_upskill');
                         $datars=$this->Model_upskill->getcategory($category_id);
                         ###########DB SQL query End ###########
                      #############**Set cache data***#############
                      $datarsall=$this->Model_cache->memcache($datars,$cachekey,$cachetime,$deletekey);
                      $resultsdata=$datarsall['list'];
                        # echo '<pre>  form sql-> '; print_r($datarsall); echo '</pre>';  
                  ######################
                  }else{ 
                       $datars=$datars;
                       #echo '<pre> form cache -> '; print_r($datars); echo '</pre>'; 
               }
               $dataformmodel=$datars;
               #############**memcache***#############
          ###############################################################################
          ###############################################################################

    */

	/*
	
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
	
          /*

          $this->load->library('Memcached_library');
           ############# 4-->Memory  memcached  Start##############    
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
                                   'type'=>'Memcached',
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
                                   'type'=>'Memcached',
                                   #'info'=>$cacheinfo
                                   );
          }
          #echo '<pre>$dataall=>'; print_r($dataall);echo '</pre>'; Die(); 
          ##########*******memcache*******############
          return $dataall;


          */

    /*

     $type_id=@$this->input->get('type_id');
     $item_id=@$this->input->get('item_id');
     $page=@$this->input->get('page');
     $perpage=@$this->input->get('perpage');
     $deletekey=@$this->input->get('deletekey');
     ################################################################################################################
          $input=@$this->input->post();
          if($input==null){$input=@$this->input->get();}
          $deletekey=$input['deletekey'];
          if($deletekey==null){$deletekey=0;}
          // exam
           $cachekey="task2categoryid-".$category_id."-userid-".$user_id;    
          $cachetime='3600';
          $cachekey_main=$cachekey;
          #############**Get cache data***#############
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
                 $resultsdata=$this->task2data($category_id,$user_id,$item_id,$page,$perpage,$deletekey);
                 ###########DB SQL query End ###########
                      // Lets store the results
                      $this->memcached_library->add($cachekey,$resultsdata,$cachetime);
                    // Output a basic msg
                      $message='form sql database query';
                      $from_data='form sql database query';
                      $datars=$resultsdata;
            }else{
                      # Output
                      # Now let us delete the key for demonstration sake!
                      if($deletekey==1){$this->memcached_library->delete($cachekey);}
                      $from_data='form memcached';
                    $message='form memcached';
                    $datars=$resultsdata;
            }
            $data=$datars; 
          #############**memcache***#############
    ################################################################################################################
    */

?>