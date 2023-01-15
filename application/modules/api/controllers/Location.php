<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class Location extends REST_Controller {
function __construct(){
        // Construct the parent class
        parent::__construct();
        ob_clean();
		#header('Content-Type: application/json; charset=utf-8');
        #date_default_timezone_set('Asia/Bangkok');
        header("Access-Control-Allow-Origin: *");
                $this->load->model('Countries_model');
				$this->load->model('Geography_model');
                $this->load->model('Province_model');
                $this->load->model('Amphur_model');
                $this->load->model('District_model');	
		  
    }
public function index_get(){
    //$this->geography_get();
   
    $title='Location get';
    ######################
    $input=@$this->input->get();  
    if($input==null){$input=@$this->input->post(); }
    #$input=@$this->db->escape_str($input); 
    $lang=@$input['lang'];
    if($lang==null){
         // language
        $language=$this->lang->language;
        if($language==null){$language='th';}
    }else{
        $language=$lang;
    }
    if($language==null){ $language='th';}
    #################DATA START###########################
    $data='Rest get';
    $data=array('countries'=>base_url('api/location/countries'),
                'countries'=>base_url('api/location/countries'),
                );
    #################DATA END###########################
    #################REST START###########################
    $count=count($data);
    $data_all=array('list'=>$data,
                    'count'=>$count,
                    );
    if($data){
                $this->response(array('header'=>array(
                                               'title'=>$title,
                                               'message'=>'Success',
                                               'status'=>true,
                                               'datastatus'=>1,
                                               'code'=>200), 
                                               'data'=> $data_all),200);
            }else{
                $this->response(array('header'=>array(
                                               'title'=>$title,
                                               'message'=>'Unsuccess',
                                               'status'=>false, 
                                               'datastatus'=>0,
                                               'code'=>201), 
                                               'data'=> $data_all),201);
                       }
        #################REST END###########################
    
    }
################################
##############Location fc##########
function geographyget($input=array()){
    $geo_id=@$input['geo_id'];
    $geo_name=@$input['geo_name'];
    $deletekey=@$input['deletekey'];
    $arraydata=array('geo_id'=>$geo_id,
                     'geo_name'=>$geo_name,
                     'deletekey'=>$deletekey, 
                    );
    $this->load->model('Location_model');
    #######################Memcached_######################
    $this->load->library('Memcached_library');
    if($geo_id==null){
        $cachekey="geo_data";
    }else{
        $cachekey="geo_data_id_".$geo_id;
    }
    $cachetime=60*60*60*24*365;
    $datars=$this->memcached_library->get($cachekey);
         # $type='items';
         # $cache_info=$this->memcached_library->getstats($type);
         # $cacheversion=$this->memcached_library->getversion();
         # $cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
    if(!$datars){
                ###########DB SQL query Start###########
                $datars=$this->Location_model->smt_geography($arraydata);
                ###########DB SQL query End ###########
                // Lets store the results
                $this->memcached_library->add($cachekey,$datars,$cachetime);
                // RS
                $datars=$this->Location_model->smt_geography($arraydata);
                $cache_status='cache key=>'.$cachekey.' cachetime=>'.$cachetime;
           }else{
                    # Output
                    # Now let us delete the key for demonstration sake!
                    if($deletekey==1){$this->memcached_library->delete($cachekey);}
                    $datars=$datars;
                    $cache_status='sql key=>'.$cachekey.' cachetime=>'.$cachetime;
                    }
   #######################Memcached_######################
            $data=$datars;
            $data_count=count($data);
        return $data; 
    }
function provinceget($input=array()){
        $province_name=@$input['province_name'];
        $province_id=@$input['province_id'];
        $geo_id=@$input['geo_id'];
        $deletekey=@$input['deletekey'];
        $arraydata=array('province_name'=>$province_name,
                         'province_id'=>$province_id, 
                         'geo_id'=>$geo_id,
                         'deletekey'=>$deletekey, 
                        );
        $this->load->model('Location_model');
        #######################Memcached_######################
        $this->load->library('Memcached_library');
        if($geo_id!=null && $province_id==null){
            $cachekey="province_geo_id_".$geo_id;
        }elseif($geo_id==null && $province_id!=null){
            $cachekey="province_id_".$province_id;
        }elseif($geo_id!=null && $province_id!=null){
            $cachekey='province_geo_id_'.$geo_id.'province_id_'.$province_id;
        }else{
            $cachekey='province';
        }
        $cachetime=60*60*60*24*365;
        $datars=$this->memcached_library->get($cachekey);
             # $type='items';
             # $cache_info=$this->memcached_library->getstats($type);
             # $cacheversion=$this->memcached_library->getversion();
             # $cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
        if(!$datars){
                    ###########DB SQL query Start###########
                    $datars=$this->Location_model->smt_province($arraydata);
                    ###########DB SQL query End ###########
                    // Lets store the results
                    $this->memcached_library->add($cachekey,$datars,$cachetime);
                    // RS
                    $datars=$this->Location_model->smt_province($arraydata);
                    $cache_status='cache key=>'.$cachekey.' cachetime=>'.$cachetime;
               }else{
                        # Output
                        # Now let us delete the key for demonstration sake!
                        if($deletekey==1){$this->memcached_library->delete($cachekey);}
                        $datars=$datars;
                        $cache_status='sql key=>'.$cachekey.' cachetime=>'.$cachetime;
                        }
       #######################Memcached_######################
        $data=$datars;
        $data_count=count($data);
        if(is_array($data)){
            $geo_arr=array();
                foreach ($data as $key=> $value){
                        $arr1['data']['geo_id']=(int)$value->geo_id;
                        $arr1['data']['province_id']=(int)$value->province_id; 
                        $arr1['data']['province_code']=(int)$value->province_code; 
                        $arr1['data']['province_name']=$value->province_name; 
                        $arr1['data']['geo_name']=$value->geo_name;  
                    $geo_arr[]=$arr1['data'];
                 }
            }
            $data=$geo_arr;
            $data_count=count($data);
        return $data; 
    }
function amphurget($input=array()){
            $province_name=@$input['province_name'];
            $province_id=@$input['province_id'];
            $geo_id=@$input['geo_id'];
            $amphur_id=@$input['amphur_id'];
            $amphur_code=@$input['amphur_code'];
            $amphur_name=@$input['amphur_name'];
            $deletekey=@$input['deletekey'];
            $arraydata=array('geo_id'=>$geo_id,
                             'province_id'=>$province_id, 
                             'amphur_id'=>$amphur_id,
                             'amphur_code'=>$amphur_code,
                             'amphur_name'=>$amphur_name,
                             'deletekey'=>$deletekey, 
                            );
            $this->load->model('Location_model');
            #######################Memcached_######################
            $this->load->library('Memcached_library');
            if($geo_id!=null && $province_id==null && $amphur_id==null){
                $cachekey="amphur_province_geo_id_".$geo_id;
            }elseif($geo_id==null && $province_id!=null && $amphur_id==null){
                $cachekey="amphur_province_id_".$province_id;
            }elseif($geo_id!=null && $province_id!=null && $amphur_id!=null){
                $cachekey='amphur_province_geo_id_'.$geo_id.'province_id_'.$province_id.'_amphur_id'.$amphur_id;
            }else{
                $cachekey='amphur';
            }
            $cachetime=60*60*60*24*365;
            $datars=$this->memcached_library->get($cachekey);
                 # $type='items';
                 # $cache_info=$this->memcached_library->getstats($type);
                 # $cacheversion=$this->memcached_library->getversion();
                 # $cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
            if(!$datars){
                        ###########DB SQL query Start###########
                        $datars=$this->Location_model->sd_na_amphur($arraydata);
                        ###########DB SQL query End ###########
                        // Lets store the results
                        $this->memcached_library->add($cachekey,$datars,$cachetime);
                        // RS
                        $datars=$this->Location_model->sd_na_amphur($arraydata);
                        $cache_status='cache key=>'.$cachekey.' cachetime=>'.$cachetime;
                   }else{
                            # Output
                            # Now let us delete the key for demonstration sake!
                            if($deletekey==1){$this->memcached_library->delete($cachekey);}
                            $datars=$datars;
                            $cache_status='sql key=>'.$cachekey.' cachetime=>'.$cachetime;
                            }
           #######################Memcached_######################
            $data=$datars;
            $data_count=count($data);
            if(is_array($data)){
                $geo_arr=array();
                    foreach ($data as $key=> $value){
                            $arr1['data']['geo_id']=(int)$value->geo_id;
                            $arr1['data']['province_id']=(int)$value->province_id; 
                            $arr1['data']['amphur_id']=(int)$value->amphur_id; 
                            $arr1['data']['amphur_code']=$value->amphur_code; 
                            $arr1['data']['amphur_name']=$value->amphur_name; 
                            $arr1['data']['province_name']=$value->province_name;  
                            $arr1['data']['geo_name']=$value->geo_name;  
                            $arr1['data']['postcode']=(int)$value->POSTCODE;
                        $geo_arr[]=$arr1['data'];
                     }
                }
        $data=$geo_arr;
        return $data; 
    }
function districtget($input=array()){
    $province_name=@$input['province_name'];
    $province_id=@$input['province_id'];
    $geo_id=@$input['geo_id'];
    $district_id=@$input['district_id'];
    $amphur_id=@$input['amphur_id'];
    $amphur_code=@$input['amphur_code'];
    $amphur_name=@$input['amphur_name'];
    $deletekey=@$input['deletekey'];
    $arraydata=array('geo_id'=>$geo_id,
                     'province_id'=>$province_id, 
                     'amphur_id'=>$amphur_id,
                     'district_id'=>$district_id,
                     'amphur_code'=>$amphur_code,
                     'amphur_name'=>$amphur_name,
                     'deletekey'=>$deletekey, 
                    );
    $this->load->model('Location_model');
    #######################Memcached_######################
    $this->load->library('Memcached_library');
    if($geo_id!=null && $province_id==null && $amphur_id==null){
        $cachekey="district_amphur_province_geo_id_".$geo_id;
    }elseif($geo_id==null && $province_id!=null && $amphur_id==null){
        $cachekey="district_amphur_province_id_".$province_id;
    }elseif($geo_id!=null && $province_id!=null && $amphur_id!=null){
        $cachekey='district_amphur_province_geo_id_'.$geo_id.'province_id_'.$province_id.'_amphur_id'.$amphur_id;
    }elseif($geo_id!=null && $province_id!=null && $amphur_id!=null && $district_id!=null){
        $cachekey='district_amphur_province_geo_id_'.$geo_id.'province_id_'.$province_id.'_amphur_id'.$amphur_id.'_district_id_'.$district_id;
    }elseif($geo_id==null && $province_id==null && $amphur_id!=null && $district_id==null){
        $cachekey='district_amphur_id'.$amphur_id;
    }elseif($geo_id==null && $province_id==null && $amphur_id==null && $district_id!=null){
        $cachekey='district_id_'.$district_id;
    }else{
        $cachekey='district';
    }
    $cachetime=60*60*60*24*365;
    $datars=$this->memcached_library->get($cachekey);
         # $type='items';
         # $cache_info=$this->memcached_library->getstats($type);
         # $cacheversion=$this->memcached_library->getversion();
         # $cacheinfo=array('stats'=>$cache_info,'version'=>$cacheversion);
    if(!$datars){
                ###########DB SQL query Start###########
                $datars=$this->Location_model->smt_district($arraydata);
                ###########DB SQL query End ###########
                // Lets store the results
                $this->memcached_library->add($cachekey,$datars,$cachetime);
                // RS
                $datars=$this->Location_model->smt_district($arraydata);
                $cache_status='cache key=>'.$cachekey.' cachetime=>'.$cachetime;
           }else{
                    # Output
                    # Now let us delete the key for demonstration sake!
                    if($deletekey==1){$this->memcached_library->delete($cachekey);}
                    $datars=$datars;
                    $cache_status='sql key=>'.$cachekey.' cachetime=>'.$cachetime;
                    }
   #######################Memcached_######################
    $data=$datars;
    $data_count=count($data);
    if(is_array($data)){
        $geo_arr=array();
            foreach ($data as $key=> $value){
                    $arr1['data']['geo_id']=(int)$value->geo_id;
                    $arr1['data']['province_id']=(int)$value->province_id; 
                    $arr1['data']['amphur_id']=(int)$value->amphur_id; 
                    $arr1['data']['amphur_code']=(int)$value->amphur_code; 
                    $arr1['data']['district_id']=(int)$value->district_id; 
                    $arr1['data']['district_code']=(int)$value->district_code; 
                    $arr1['data']['district_name']=$value->district_name; 
                    $arr1['data']['amphur_name']=$value->amphur_name; 
                    $arr1['data']['province_name']=$value->province_name;  
                    $arr1['data']['geo_name']=$value->geo_name;  
                    $arr1['data']['postcode']=(int)$value->POSTCODE;
                $geo_arr[]=$arr1['data'];
             }
        }
        $data=$geo_arr;
        return $data; 
    }
##############Location fc##########
################################
public function countries_get(){
    # if(!$this->session->userdata('is_logged_in')){   echo' is login'; Die();  }else{  echo' not login '; Die();  }
        
        $title='Countries get';
        ######################
        $input=@$this->input->get();  
        if($input==null){$input=@$this->input->post(); }
        #$input=@$this->db->escape_str($input); 
        # echo'<hr> <pre>   input =>';print_r($input);echo'<pre>'; Die();
        $lang=@$input['lang'];
        if($lang==null){
             // language
            $language=$this->lang->language;
            $lang=$this->lang->line('lang');
		    $langs=$this->lang->line('langs');
            if($lang==null){$lang='th';}
        }else{
            $lang=$lang;
        }
        if($lang==null){ $lang='th';}
        #################DATA START###########################
        #echo'<hr> <pre>lang=>';print_r($lang);echo'<pre>'; Die();
    ######## Cach Toools Start  ################################################
            /////////////cache////////////
            $time_cach_set_min=$this->config->item('time_cach_set_min');
            $time_cach_set=$this->config->item('time_cach_set');
            #$cachetime=$time_cach_set_min;
            $cachetime=$this->config->item('cachetime8');
            $lang=$this->lang->line('lang'); 
            $langs=$this->lang->line('langs'); 
            $cachekey='Countries_lang_'.$lang;
            ##Cach Toools Start######
            //cachefile 
            $input=@$this->input->post(); 
            if($input==null){ $input=@$this->input->get();}
            $deletekey=@$input['deletekey'];
            if($deletekey==''){$deletekey=null;}else{$deletekey=1;}
            $cachetype='2'; 
            $this->load->model('Cachtool_model');
            $sql=null;
            $cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
            $cachechklist=$cachechk['list'];
            // echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechk);echo'</pre>'; Die();
            if($cachechklist!=null){    // IN CACHE
                $temp = $cachechklist;
                #echo'1 Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
            }else{                      // NOT IN CACHE
                ///// ***** เอา FUNCTION ที่ทำงานท่อนเดิม มาใส่ตรงนี้ ******
                 // model
            $this->load->model('Countries_model');
            $countries=$this->Countries_model->get_na_countries();
            #echo'<hr> <pre>countries=>';print_r($countries);echo'</pre>'; Die();
                $sql=null;
                $cachechklist=$this->Cachtool_model->cachedbsetkey($sql,$get_countries,$cachekey,$cachetime,$cachetype,$deletekey);
                #echo'2 Form SQL <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
                $countries=$this->Countries_model->get_na_countries();
                $cachechklist=$countries;
            }
            $dataresult=$cachechklist;
            /////////////cache////////////
        ######## Cach Toools END ################################################
   # echo'<hr> <pre>  dataresult=>';print_r($dataresult);echo'<pre>'; Die();   
        #################DATA END###########################
        #################REST START###########################
        $count=count($dataresult);
        $data_all=array('list'=>$dataresult,
                        'count'=>$count,
                        );
        if($data_all){
                    $this->response(array('header'=>array(
                                                   'title'=>$title,
                                                   'message'=>'Success',
                                                   'status'=>true,
                                                   'datastatus'=>1,
                                                   'code'=>200), 
                                                   'data'=> $data_all),200);
                }else{
                    $this->response(array('header'=>array(
                                                   'title'=>$title,
                                                   'message'=>'Unsuccess',
                                                   'status'=>false, 
                                                   'datastatus'=>0,
                                                   'code'=>201), 
                                                   'data'=> $data_all),201);
                           }
            #################REST END###########################
        }
################################
public function geography_get(){
    // http://localhost/backendhmvc/api/location/geography?lang=en
    ob_end_clean();
    $input=@$this->input->get();
    if($input==null){ $input=@$this->input->post();}
    //$district_id=@$input['district_id'];
    //$amphur_id=@$input['amphur_id'];
    //$province_id=@$input['province_id'];
    $geo_id=@$input['geo_id'];
    $geo_name=@$input['geo_name'];
    $language=@$input['lang'];
    if($language==null){ $language=$this->lang->language['lang'];
        if($language==null){  $language='th'; }   
        }
    $deletekey=@$input['deletekey'];
    $arraydata=array('geo_id'=>$geo_id,
                     'geo_name'=>$geo_name,
                     'lang'=>$language,
                     'deletekey'=>$deletekey, 
                    );
    $this->load->model('Location_model');
    #######################Memcached_######################
    if($geo_id==null){
        $cachekey="geo_data";
    }else{
        $cachekey="geo_data_id_".$geo_id;
    }
    $cachetime=60*60*60*24*365;
    $datars=$this->Location_model->sd_na_geography($arraydata);
   #######################Memcached_######################
   $data=$datars;
    $data_count=count($data);
    if(is_array($data)){
        $geo_arr=array();
            foreach ($data as $key=> $value){
                    $arr1['data']['geo_id']=(int)$value->geo_id_map;
                    $arr1['data']['geo_name']=$value->geo_name; 
                $geo_arr[]=$arr1['data'];
             }
        }
        $data=$geo_arr;
    if($data==null || $data_count==0){
        $this->response(array('header'=>array(
            'title'=>'REST_Controller::HTTP_NOT_FOUND',
            'module'=>'geography_get',
            'message'=>' DATA is Null',
            'status'=>FALSE,
            'code'=>200), 
            'data'=> null,'input'=> $input,),200);
            die();
         }else{
            $dataall=$data;
            $this->response(array('header'=>array(
                'title'=>'REST_Controller::HTTP_OK',
                'module'=>'geography_get',
                'message'=>' DATA OK',
                'status'=>TRUE,
                'code'=>200), 
                'data'=> $dataall,'data_count'=> $data_count,'input'=> $input,
                ),200);
                die();
            }
    }
public function province_get(){
    // http://localhost/backendhmvc/api/location/province?lang=th&geo_id=&deletekey=1
        ob_end_clean();
        $input=@$this->input->get();
        if($input==null){ $input=@$this->input->post();}
        $province_name=@$input['province_name'];
        $province_id=@$input['province_id'];
        $geo_id=@$input['geo_id'];
        $language=@$input['lang'];
        if($language==null){ $language=$this->lang->language['lang'];
            if($language==null){  $language='th'; }   
            }
        $deletekey=@$input['deletekey'];
        $arraydata=array('province_name'=>$province_name,
                         'province_id'=>$province_id, 
                         'geo_id'=>$geo_id,
                         'deletekey'=>$deletekey, 
                        );
        $this->load->model('Location_model');
        #######################Memcached_######################
        if($geo_id!=null && $province_id==null){
            $cachekey="province_geo_id_".$geo_id;
        }elseif($geo_id==null && $province_id!=null){
            $cachekey="province_id_".$province_id;
        }elseif($geo_id!=null && $province_id!=null){
            $cachekey='province_geo_id_'.$geo_id.'province_id_'.$province_id;
        }else{
            $cachekey='province';
        }
        $cachetime=60*60*60*24*365;
        $datars=$this->Location_model->sd_na_province($arraydata);
       #######################Memcached_######################
        $data=$datars;
        $data_count=count($data);
        if(is_array($data)){
            $geo_arr=array();
                foreach ($data as $key=> $value){
                        $arr1['data']['geo_id']=(int)$value->geo_id;
                        $arr1['data']['province_id']=(int)$value->province_id; 
                        $arr1['data']['province_code']=(int)$value->province_code; 
                        $arr1['data']['province_name']=$value->province_name; 
                        $arr1['data']['geo_name']=$value->geo_name;  
                    $geo_arr[]=$arr1['data'];
                 }
            }
            $data=$geo_arr;
        if($data==null || $data_count==0){
            $this->response(array('header'=>array(
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'module'=>'province',
                'message'=>' DATA is Null',
                'status'=>FALSE,
                'code'=>200), 
                'data'=> null,'input'=> $input,),200);
                die();
             }else{
                $dataall=$data;
                $this->response(array('header'=>array(
                    'title'=>'REST_Controller::HTTP_OK',
                    'module'=>'province',
                    'message'=>' DATA OK',
                    'status'=>TRUE,
                    'code'=>200), 
                    'data'=> $dataall,'data_count'=> $data_count,'input'=> $input,
                    ),200);
                    die();
                }
    }
public function amphur_get(){
        // http://localhost/backendhmvc/api/location/amphur?lang=th&province_id=1&deletekey=1
            ob_end_clean();
            $input=@$this->input->get();
            if($input==null){ $input=@$this->input->post();}
            $province_name=@$input['province_name'];
            $province_id=@$input['province_id'];
            $geo_id=@$input['geo_id'];
            $amphur_id=@$input['amphur_id'];
            $amphur_code=@$input['amphur_code'];
            $amphur_name=@$input['amphur_name'];
            $language=@$input['lang'];
            if($language==null){ $language=$this->lang->language['lang'];
                if($language==null){  $language='th'; }   
                }
            $deletekey=@$input['deletekey'];
            $arraydata=array('geo_id'=>$geo_id,
                             'province_id'=>$province_id, 
                             'amphur_id'=>$amphur_id,
                             'amphur_code'=>$amphur_code,
                             'amphur_name'=>$amphur_name,
                             'lang'=>$language,
                             'deletekey'=>$deletekey, 
                            );
            $this->load->model('Location_model');
            #######################Memcached_######################
            if($geo_id!=null && $province_id==null && $amphur_id==null){
                $cachekey="amphur_province_geo_id_".$geo_id;
            }elseif($geo_id==null && $province_id!=null && $amphur_id==null){
                $cachekey="amphur_province_id_".$province_id;
            }elseif($geo_id!=null && $province_id!=null && $amphur_id!=null){
                $cachekey='amphur_province_geo_id_'.$geo_id.'province_id_'.$province_id.'_amphur_id'.$amphur_id;
            }else{
                $cachekey='amphur';
            }
            $cachetime=60*60*60*24*365;
            $datars=$this->Location_model->sd_na_amphur($arraydata);
           #######################Memcached_######################
            $data=$datars;
            $data_count=count($data);
            if(is_array($data)){
                $geo_arr=array();
                    foreach ($data as $key=> $value){
                            $arr1['data']['geo_id']=(int)$value->geo_id;
                            $arr1['data']['province_id']=(int)$value->province_id; 
                            $arr1['data']['amphur_id']=(int)$value->amphur_id; 
                            $arr1['data']['amphur_code']=$value->amphur_code; 
                            $arr1['data']['amphur_name']=$value->amphur_name; 
                            $arr1['data']['province_name']=$value->province_name;  
                            $arr1['data']['geo_name']=$value->geo_name;  
                            $arr1['data']['postcode']=(int)$value->zipcode;
                        $geo_arr[]=$arr1['data'];
                     }
                }
                $data=$geo_arr;
         
            if($data==null || $data_count==0){
                $this->response(array('header'=>array(
                    'title'=>'REST_Controller::HTTP_NOT_FOUND',
                    'module'=>'amphur_get',
                    'message'=>' DATA is Null',
                    'status'=>FALSE,
                    'code'=>200), 
                    'data'=> null,'input'=> $input,),200);
                    die();
                 }else{
                    $dataall=$data;
                    $this->response(array('header'=>array(
                        'title'=>'REST_Controller::HTTP_OK',
                        'module'=>'amphur_get',
                        'message'=>' DATA OK',
                        'status'=>TRUE,
                        'code'=>200), 
                        'data'=> $dataall,'data_count'=> $data_count,'input'=> $input,
                        ),200);
                        die();
                    }
    }
public function district_get(){
    // http://localhost/backendhmvc/api/location/district?amphur_id=115&lang=th
    ob_end_clean();
    $input=@$this->input->get();
    if($input==null){ $input=@$this->input->post();}
    $province_name=@$input['province_name'];
    $province_id=@$input['province_id'];
    $geo_id=@$input['geo_id'];
    $district_id=@$input['district_id'];
    $amphur_id=@$input['amphur_id'];
    $amphur_code=@$input['amphur_code'];
    $amphur_name=@$input['amphur_name'];
    $deletekey=@$input['deletekey'];
    $arraydata=array('geo_id'=>$geo_id,
                     'province_id'=>$province_id, 
                     'amphur_id'=>$amphur_id,
                     'district_id'=>$district_id,
                     'amphur_code'=>$amphur_code,
                     'amphur_name'=>$amphur_name,
                     'lang'=>$language,
                     'deletekey'=>$deletekey, 
                    );
    $this->load->model('Location_model');
    #######################Memcached_######################
    $this->load->library('Memcached_library');
    if($geo_id!=null && $province_id==null && $amphur_id==null){
        $cachekey="district_amphur_province_geo_id_".$geo_id;
    }elseif($geo_id==null && $province_id!=null && $amphur_id==null){
        $cachekey="district_amphur_province_id_".$province_id;
    }elseif($geo_id!=null && $province_id!=null && $amphur_id!=null){
        $cachekey='district_amphur_province_geo_id_'.$geo_id.'province_id_'.$province_id.'_amphur_id'.$amphur_id;
    }elseif($geo_id!=null && $province_id!=null && $amphur_id!=null && $district_id!=null){
        $cachekey='district_amphur_province_geo_id_'.$geo_id.'province_id_'.$province_id.'_amphur_id'.$amphur_id.'_district_id_'.$district_id;
    }elseif($geo_id==null && $province_id==null && $amphur_id!=null && $district_id==null){
        $cachekey='district_amphur_id'.$amphur_id;
    }elseif($geo_id==null && $province_id==null && $amphur_id==null && $district_id!=null){
        $cachekey='district_id_'.$district_id;
    }else{
        $cachekey='district';
    }
    $cachetime=60*60*60*24*365;
    $datars=$this->Location_model->sd_na_district($arraydata);
   #######################Memcached_######################
    $data=$datars;
    $data_count=count($data);
    if(is_array($data)){
        $geo_arr=array();
            foreach ($data as $key=> $value){
                    $arr1['data']['geo_id']=(int)$value->geo_id;
                    $arr1['data']['province_id']=(int)$value->province_id; 
                    $arr1['data']['amphur_id']=(int)$value->amphur_id; 
                    $arr1['data']['amphur_code']=(int)$value->amphur_code; 
                    $arr1['data']['district_id']=(int)$value->district_id; 
                    $arr1['data']['district_code']=(int)$value->district_code; 
                    $arr1['data']['district_name']=$value->district_name; 
                    $arr1['data']['amphur_name']=$value->amphur_name; 
                    $arr1['data']['province_name']=$value->province_name;  
                    $arr1['data']['geo_name']=$value->geo_name;  
                    $arr1['data']['postcode']=(int)$value->postcode;
                $geo_arr[]=$arr1['data'];
             }
        }
        $data=$geo_arr;
    if($data==null || $data_count==0){
        $this->response(array('header'=>array(
            'title'=>'REST_Controller::HTTP_NOT_FOUND',
            'module'=>'amphur_get',
            'message'=>' DATA is Null',
            'status'=>FALSE,
            'code'=>200), 
            'data'=> null,'input'=> $input,),200);
            die();
         }else{
            $dataall=$data;
            $this->response(array('header'=>array(
                'title'=>'REST_Controller::HTTP_OK',
                'module'=>'amphur_get',
                'message'=>' DATA OK',
                'status'=>TRUE,
                'code'=>200), 
                'data'=> $dataall,'data_count'=> $data_count,'input'=> $input,
                ),200);
                die();
            }
    }
################################################################
################################
public function geography2_get(){
           // if(!$this->session->userdata('is_logged_in')){   echo' is login'; Die();   }else{   echo' not login '; Die();  }
            $title='Location geography';
            ######################
            $input=@$this->input->get();  
            if($input==null){$input=@$this->input->post(); }
                #$input=@$this->db->escape_str($input); 
                # echo'<hr> <pre>   input =>';print_r($input);echo'<pre>'; Die();
                $lang=@$input['lang'];
                if($lang==null){
                     // language
                    $language=$this->lang->language;
                    $lang=$this->lang->line('lang');
                    $langs=$this->lang->line('langs');
                    if($lang==null){$lang='th';}
                }else{
                    $lang=$lang;
                }
                if($lang==null){ $lang='th';}
                #################DATA START###########################
                $data='Rest get';
               // echo'<hr> <pre>lang=>'; print_r($lang); echo'</pre>'; Die();         
            ######## Cach Toools Start  ################################################
                    $countries_id=209;
                    $get_countries = $this->Countries_model->get_countries_by_id($countries_id);
                   // echo'<hr> <pre>get_countries>';print_r($get_countries);echo'</pre>'; Die();   
                    /////////////cache////////////
                    $time_cach_set_min=$this->config->item('time_cach_set_min');
                    $time_cach_set=$this->config->item('time_cach_set');
                    #$cachetime=$time_cach_set_min;
                    $cachetime=$this->config->item('cachetime8'); 
                    $cachekey='get_countries_by_id_'.$countries_id.'_lang_'.$lang;
                    ##Cach Toools Start######
                    //cachefile 
                    $input=@$this->input->post(); 
                    if($input==null){ $input=@$this->input->get();}
                    $deletekey=@$input['deletekey'];
                    if($deletekey==''){$deletekey=null;}else{$deletekey=1;}
                    $cachetype='2'; 
                    $this->load->model('Cachtool_model');
                    $sql=null;
                    $cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
                    $cachechklist=$cachechk['list'];
                    // echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechk);echo'</pre>'; Die();
                    if($cachechklist!=null){    // IN CACHE
                        $temp = $cachechklist;
                        #echo'1 Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
                    }else{                      // NOT IN CACHE
                        ///// ***** เอา FUNCTION ที่ทำงานท่อนเดิม มาใส่ตรงนี้ ******
                        $get_countries = $this->Countries_model->get_countries_by_id($countries_id);
                        $sql=null;
                        $cachechklist=$this->Cachtool_model->cachedbsetkey($sql,$get_countries,$cachekey,$cachetime,$cachetype,$deletekey);
                        #echo'2 Form SQL <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
                        $get_countries = $this->Countries_model->get_countries_by_id($countries_id);
                        $cachechklist=$get_countries;
                    }
                    $countries=$cachechklist['0'];
             // echo'<hr> <pre>countries>';print_r($countries);echo'</pre>'; Die();   
                    $countries_name=$countries->countries_name;
                    /////////////cache////////////
                ######## Cach Toools END ################################################
                # echo'<hr> <pre>  dataresult=>';print_r($dataresult);echo'<pre>'; Die();   
                /////////////cache////////////
                        $cachekey='geography_'.$lang;
                        ##Cach Toools Start######
                        if($deletekey==''){$deletekey=null;}else{$deletekey=1;}
                        $cachetype='2'; 
                        $this->load->model('Cachtool_model');
                        $sql=null;
                        $cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
                        $cachechklist=$cachechk['list'];
                        // echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechk);echo'</pre>'; Die();
                        if($cachechklist!=null){    // IN CACHE
                            $temp = $cachechklist;
                            #echo'1 Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
                        }else{                      // NOT IN CACHE
                            ///// ***** เอา FUNCTION ที่ทำงานท่อนเดิม มาใส่ตรงนี้ ******
                            $geography=$this->Geography_model->get_geography();
                            $sql=null;
                            $cachechklist=$this->Cachtool_model->cachedbsetkey($sql,$geography,$cachekey,$cachetime,$cachetype,$deletekey);
                            #echo'2 Form SQL <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
                            $geography=$this->Geography_model->get_geography();
                            $cachechklist=$geography;
                        }
                        $geography=$cachechklist;
                /////////////cache////////////
                #################DATA END###########################
                #################REST START###########################
                $count=count($geography);
                $data_all=array('countries'=>$countries,
                                'countries_name'=>$countries_name,
                                'geo'=>$geography,
                                'count'=>$count,
                                );
                if($data){
                            $this->response(array('header'=>array(
                                                           'title'=>$title,
                                                           'message'=>'Success',
                                                           'status'=>true,
                                                           'datastatus'=>1,
                                                           'code'=>200), 
                                                           'data'=> $data_all),200);
                        }else{
                            $this->response(array('header'=>array(
                                                           'title'=>$title,
                                                           'message'=>'Unsuccess',
                                                           'status'=>false, 
                                                           'datastatus'=>0,
                                                           'code'=>201), 
                                                           'data'=> $data_all),201);
                                   }
                    #################REST END###########################
        }
public function province2_get(){
            if(!$this->session->userdata('is_logged_in')){  
                #   echo' is login'; Die(); 
            }else{ 
                #   echo' not login '; Die(); 
            }
            $title='Location Province';
            ######################
            $input=@$this->input->get();  
            if($input==null){$input=@$this->input->post(); }
                #$input=@$this->db->escape_str($input); 
                # echo'<hr> <pre>   input =>';print_r($input);echo'<pre>'; Die();
                $lang=@$input['lang'];
                if($lang==null){
                     // language
                    $language=$this->lang->language;
                    $lang=$this->lang->line('lang');
                    $langs=$this->lang->line('langs');
                    if($lang==null){$lang='th';}
                }else{
                    $lang=$lang;
                }
                if($lang==null){ $lang='th';}


                #################DATA START###########################
                $geoid=@$input['geoid'];  
                $countries_id=@$input['countries_id']; 
                $this->load->model('Countries_model');
                if($countries_id==null){$countries_id=209;}
                $get_countries = $this->Countries_model->get_countries_by_id($countries_id);
                //Debug($get_countries);
                $object = json_decode(json_encode($get_countries), TRUE);
                $countries=$countriesname=$object[0]['countries_name'];
                

                if($geoid==null){
                    $this->response(array('header'=>array(
                                                   'title'=>$title,
                                                   'message'=>'Success',
                                                   'status'=>true,
                                                   'datastatus'=>1,
                                                   'code'=>200), 
                                                   'data'=>null),200);
                                                   die();
                }

                $geo_id	= $geoid; # ส่งค่าไป
                $this->load->model('Geography_model');
                $this->load->model('Province_model');
                $this->load->model('Amphur_model');
               // $this->load->model('District_model');	
                //$ListSelect = $this->Api_model_na->user_menu($this->session->userdata('admin_type'));
                $get_geography = $this->Geography_model->get_geography_by_id($geoid,$lang);
                 #Debug($get_geography); die();
                $object = json_decode(json_encode($get_geography), TRUE);
                if($this->input->server('REQUEST_METHOD') === 'POST'){
                        $search_form = $this->input->post();
                        //Debug($search_form);
                        //die();
                        if(isset($search_form['selectid'])){
                                $selectid = $search_form['selectid'];
                                $order = $search_form['order'];
                                $maxsel = count($selectid);
                                $tmp = 0;
    
                                for($i=0;$i<$maxsel;$i++){
    
                                        $this->Province_model->update_order($selectid[$i], $order[$i]);
                                        //Debug($this->db->last_query());
    
                                        if($tmp > $order[$i]){
                                                //Update ID ด้านหน้า
                                                //$this->Province_model->update_orderid_to_down($order[$i]);
                                        }
    
                                        if($tmp == 0 || $tmp != $order[$i]) $tmp = $order[$i];
                                        //echo "<hr>$tmp > ".$order[$i]."<hr>";
                                }
                        }
                        //die();
                }
                if($geo_id == 0) $geo_id= $id; else $geo_id =$geo_id ;
    
                /////////////cache////////////
                        $time_cach_set_min=$this->config->item('time_cach_set_min');
                        $time_cach_set=$this->config->item('time_cach_set');
                        #$cachetime=$time_cach_set_min;
                        #$ci=get_instance(); // CI_Loader instance
                        $cachetime=$this->config->item('cachetime8');
                        $cachekey="key-geography-geo-".$geo_id.'-'.$lang;
                        ##Cach Toools Start######
                        //cachefile 
                        $input=@$this->input->post(); 
                        if($input==null){ $input=@$this->input->get();}
                        $deletekey=@$input['deletekey'];
                        if($deletekey==''){$deletekey=null;}else{$deletekey=1;}
                        $cachetype='2'; 
                        $this->load->model('Cachtool_model');
                        $sql=null;
                        $cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
                        $cachechklist=$cachechk['list'];
                        // echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechk);echo'</pre>'; Die();
                        if($cachechklist!=null){    // IN CACHE
                            $temp = $cachechklist;
                            #echo'1 Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
                        }else{                      // NOT IN CACHE
                            ///// ***** เอา FUNCTION ที่ทำงานท่อนเดิม มาใส่ตรงนี้ ******
                            $geography=$this->Geography_model->get_geography_by_id($geo_id,$lang);
                            $sql=null;
                            $cachechklist=$this->Cachtool_model->cachedbsetkey($sql,$geography,$cachekey,$cachetime,$cachetype,$deletekey);
                            #echo'2 Form SQL <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
                            $geography=$this->Geography_model->get_geography_by_id($geo_id,$lang);
                            $cachechklist=$geography;
                        }
                        $geography=$cachechklist; 
                /////////////cache////////////
                
    
                ######## Cach Toools Start  ################################################
                        /////////////cache////////////
                        $time_cach_set_min=$this->config->item('time_cach_set_min');
                        $time_cach_set=$this->config->item('time_cach_set');
                        #$cachetime=$time_cach_set_min;
                        $cachetime=$this->config->item('cachetime8');
                        #$cachetime=$this->config->item('cachetime8');
                      
                        $cachekey="key-province-geo-".$geo_id.'-'.$lang;
                        ##Cach Toools Start######
                        //cachefile 
                        $input=@$this->input->post(); 
                        if($input==null){ $input=@$this->input->get();}
                        $deletekey=@$input['deletekey'];
                        if($deletekey==''){$deletekey=null;}else{$deletekey=1;}
                        $cachetype='2'; 
                        $this->load->model('Cachtool_model');
                        $sql=null;
                        $cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
                        $cachechklist=$cachechk['list'];
                        // echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechk);echo'</pre>'; Die();
                        if($cachechklist!=null){    // IN CACHE
                            $temp = $cachechklist;
                            $province=$cachechklist;
                            #echo'1 Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
                        }else{                      // NOT IN CACHE
                            ///// ***** เอา FUNCTION ที่ทำงานท่อนเดิม มาใส่ตรงนี้ ******
                            $province=$this->Province_model->get_province($geo_id,$lang);
                            $sql=null;
                            $cachedata=$this->Cachtool_model->cachedbsetkey($sql,$province,$cachekey,$cachetime,$cachetype,$deletekey);
                            #echo'2 Form SQL <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
                            $province=$this->Province_model->get_province($geo_id,$lang);
                            $cachechklist=$geography;
                            $province=$cachechklist;
                        }
                        
                ######## Cach Toools END ################################################
                
    
                $data = array(
                    "geo_id" => $get_geography[0]['geo_id'],
                    "geo_name" => $get_geography[0]['geo_name'],
                    "countries_id" => $get_geography[0]['countries_id'],
                    "lang" => $get_geography[0]['lang'],
                    "geo_id_map" => $get_geography[0]['geo_id_map'],
                    "geo_status" => $get_geography[0]['status'],
                    "province" => $province,
                );
    
                #################DATA END###########################



                #################REST START###########################
                $count=count($data);
                $data_all=array('list'=>$data,
                                'count'=>$count,
                                );
                if($data){
                            $this->response(array('header'=>array(
                                                           'title'=>$title,
                                                           'message'=>'Success',
                                                           'status'=>true,
                                                           'datastatus'=>1,
                                                           'code'=>200), 
                                                           'data'=> $data_all),200);
                        }else{
                            $this->response(array('header'=>array(
                                                           'title'=>$title,
                                                           'message'=>'Unsuccess',
                                                           'status'=>false, 
                                                           'datastatus'=>0,
                                                           'code'=>201), 
                                                           'data'=> $data_all),201);
                                   }
                    #################REST END###########################
    }
}