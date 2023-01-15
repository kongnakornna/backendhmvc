<?php
class Model_cache extends CI_Model{
#####################
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
     }
public function cachedbci($sql,$cachekey,$cachetime,$deletekey){ 
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
public function cachefile($dataresult,$cachekey,$cachetime,$deletekey){ 
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
                }
                #############################################
                /* ############# 1-->cachedb  End##############  */  
                return $dataresult;  exit();
        return $dataresult;  exit();
    }
public function redis($dataresult,$cachekey,$cachetime,$deletekey){ 
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
public function memcache($dataresult,$cachekey,$cachetime,$deletekey){ 
    if($dataresult==null){ $dataresult=null;}
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
    $results_data=$this->memcached_library->get($cachekey);
    $type='items';
    $cache_info=$this->memcached_library->getstats($type);
    $cacheversion=$this->memcached_library->getversion();
    $cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
    # If the key does not exist it could mean the key was never set or expired
    if(!$results_data){
         // Modify this Query to your liking!
         ###########DB SQL query Start###########
         $resultsdata=$dataresult;
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
                    $resultsdata=$results_data;
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
                #return $dataall;
            ##########*******memcache*******############
        return $resultsdata;
    }
public function sqlquery($dataresult,$cachekey,$cachetime,$deletekey){ 
        if($dataresult==null){ $int=(int)'0'; return $int; exit();}
            return $dataresult;
        }
}

    /* 
        $input=@$this->input->post();
        if($input==null){$input=@$this->input->get();}
        $deletekey=$input['deletekey'];
        if($deletekey==null){$deletekey=0;}
        // exam
        $this->load->model('Model_cache');
        $cachekey='key-ams-entrance-order_by';
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
            $datars=$this->Model_cache->memcache(null,$cachekey,$cachetime,$deletekey);
            if($datars==null || $datars==0){
                   # use load model
                    // $this->load->model('Model_upskill');
                    // $datars=$this->Model_upskill->get_type($deletekey,$cachetype);
                   # use load model
                #############**sql query data***#############
                    $rsdata=$this->db->query($sql);
                    $data=$rsdata->result_array(); 
                    #############**Set cache data***#############
                    $datars=$this->Model_cache->memcache($data,$cachekey,$cachetime,$deletekey);
                ######################
                }
             #############**memcache***#############
        return $datars;  
    */

?>