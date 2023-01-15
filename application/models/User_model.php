<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class User_model extends CI_Model {
public function __construct() {
    	parent::__construct();
      $this->load->helper('cookie','session');
      //$this->load->helper('cookie');
      // Load form helper library
      $this->load->helper('form');
      // Load form validation library
      $this->load->library('form_validation');
      // Load session library
      $this->load->library('session');
          
    }
public function type_user_id($user_type_id,$cache_type,$deletekey){
      if($cache_type==''){$cache_type='0';}
      /*
      echo'<hr><pre>  user_type_id=>';print_r($user_type_id);echo'<pre> ';
      echo'<hr><pre>  cache_type=>';print_r($cache_type);echo'<pre>';
      echo'<hr><pre>  deletekey=>';print_r($deletekey);echo'<pre>';
      echo'<hr><pre>  cache_type=>';print_r($cache_type);echo'<pre>';
      */
      ##Cach Toools Start######
      if($user_type_id==null){$type_user_id_key='all';}else{$type_user_id_key=$user_type_id;}
                  $cachekey="key-type-user-id-".$type_user_id_key;
                  $cachetime=60*60*60*365;
                  $cache_day=$cachetime/(60*60*60*1);
                  //cachefile 
                  $cachetype='2'; 
                  $this->load->model('Cachtooluser_model');
                  $sql=null;
                  $dir='usertype';
                  $cachechk=$this->Cachtooluser_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
                  $cachechklist=$cachechk['list'];
                  # echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>';  Die();

      if($cachechklist!=null && $cache_type!=0){
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
                  $cache_msg='Form Cache File';
                  $dataresult_all=array('rs'=>$dataresult,
                              'cache_msg'=>$cache_msg,
                              'cache_key'=>$cache_key,
                              'cachetime'=>$cachetime,
                              'cache_day'=>$cache_day,
                              'sql'=>null,
                        );
      }elseif($cachechklist==null || $cache_type==0){
      ################################
      $this->db->cache_off();
      //$this->db->cache_delete_all();
      //$this->db->cache_delete('user', 'type');
      $this->db->select('*');
      $this->db->from('sd_user_type as type');
      //$this->db->join('sd_user_permission as permission', 'permission.user_type_id=type.user_type_id');
      if($user_type_id!==null){
            $this->db->where('type.user_type_id',$user_type_id);  
      }
      $order_by='asc';
      $this->db->order_by('type.user_type_id',$order_by);
      $query_get=$this->db->get();
      $num=$query_get->num_rows();
      $query_result=$query_get->result(); 
      $sql_last_query=$this->db->last_query();
      $rs=$query_result;
      $dataresult=$rs;
      $cache_msg='Form SQL Query';
      //$dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg);
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cachekey,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day,
                  'sql'=>$sql_last_query,
            );
      
       #echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'</pre>'; Die();
      
      ################################
      if($cache_type!=0){
                  $this->load->model('Cachtooluser_model');
                        $sql=null;
                        $dir='usertype';
                        $cacheset=$this->Cachtooluser_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
                        //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
                        $rs=$cacheset['list'];
                        $num=count($rs);
                        $cache_msg=$cacheset['message'];
                  }

      }
      ##Cach Toools END######
      ################################################ 
      #echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'</pre>'; Die();
      return $dataresult_all;
  }
public function menubytypeaccess($menu_id_in=array(),$user_type_id,$cache_type,$deletekey){
      if($cache_type==''){$cache_type='0';}
      /*
      echo'<hr><pre>  $menu_id_in=>';print_r($menu_id_in);echo'<pre> ';
      echo'<hr><pre>  cache_type=>';print_r($cache_type);echo'<pre>';
      echo'<hr><pre>  deletekey=>';print_r($deletekey);echo'<pre>'; die();
      */
      ##Cach Toools Start######
      $menu_id_inim=implode(",", $menu_id_in);
      $menu_id_in_replace=str_replace(",","-",$menu_id_inim);
      if($menu_id_in==null){$menuid='all';}else{$menuid=$menu_id_in_replace;}
      $cachekey="key-menu-access-user-typeid-$user_type_id-main-menu-in-".$menuid;
      $cachetime=60*60*60*365*10;
      $cache_day=$cachetime/(60*60*60*1);
      //cachefile 
      $cachetype='2'; 
      $this->load->model('Cachtooluser_model');
      $sql=null;
      $dir='usertype';
      $cachechk=$this->Cachtooluser_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
      $cachechklist=$cachechk['list'];
      //echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>'; //Die();
      if($cachechklist!=null && $cache_type!=0){
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
      $cache_msg='Form Cache File Results';
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cache_key,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day);
      }
      elseif($cachechklist==null){
      ################################
      $this->db->cache_off();
      $this->db->select('*');
      $this->db->from('sd_user_menu as menu');
      if($menu_id_in!==null){
            $this->db->where_in('menu.menu_id',$menu_id_in);  
      }
      $this->db->where('menu.parent',0); 
            $this->db->where('menu.status',1); 
            $this->db->order_by("menu.order_by asc, menu.menu_id asc");
      $query_get=$this->db->get();
      $num=$query_get->num_rows();
      $query_result=$query_get->result(); 
      $rs=$query_result;
      $dataresult=$rs;
      $cache_msg='Form SQL Query Results';
      $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg);
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cachekey,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day);
      
      //echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'<pre>'; Die();
      
      ################################
      if($cache_type!=0){
      $this->load->model('Cachtooluser_model');
            $sql=null;
            $dir='usertype';
            $cacheset=$this->Cachtooluser_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
            //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
            $rs=$cacheset['list'];
            $num=count($rs);
            $cache_msg=$cacheset['message'];
      }

      }
      ##Cach Toools END######
      ################################################ 
      return $dataresult_all;
  }
public function menu($menu_id,$cache_type,$deletekey){
      if($cache_type==''){$cache_type='0';}
      /*
      echo'<hr><pre>  menu_id=>';print_r($menu_id);echo'<pre> ';
      echo'<hr><pre>  cache_type=>';print_r($cache_type);echo'<pre>';
      echo'<hr><pre>  deletekey=>';print_r($deletekey);echo'<pre>'; die();
      */
      ##Cach Toools Start######
      if($menu_id==null){$menuid='all';}else{$menuid=$menu_id;}
      $cachekey="key-menu-id-".$menuid;
      $cachetime=60*60*60*365*10;
      $cache_day=$cachetime/(60*60*60*1);
      //cachefile 
      $cachetype='2'; 
      $this->load->model('Cachtooluser_model');
      $sql=null;
      $dir='usertype';
      $cachechk=$this->Cachtooluser_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
      $cachechklist=$cachechk['list'];
      //echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>'; //Die();
      if($cachechklist!=null && $cache_type!=0){
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
      $cache_msg='Form Cache File Results';
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cache_key,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day);
      }
      elseif($cachechklist==null){
      ################################
      $this->db->cache_off();
      $this->db->select('*');
      $this->db->from('sd_user_menu as menu');
      if($menu_id!==null){
            $this->db->where('menu.menu_id',$menu_id);  
      }
      $this->db->where('menu.parent',0); 
      $query_get=$this->db->get();
      $num=$query_get->num_rows();
      $query_result=$query_get->result(); 
      $rs=$query_result;
      $dataresult=$rs;
      $cache_msg='Form SQL Query Results';
      $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg);
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cachekey,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day);
      
      //echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'<pre>'; Die();
      
      ################################
      if($cache_type!=0){
      $this->load->model('Cachtooluser_model');
            $sql=null;
            $dir='usertype';
            $cacheset=$this->Cachtooluser_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
            //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
            $rs=$cacheset['list'];
            $num=count($rs);
            $cache_msg=$cacheset['message'];
      }

      }
      ##Cach Toools END######
      ################################################ 
      return $dataresult_all;
  }
public function submenu($menu_id,$cache_type,$deletekey){
            if($cache_type==''){$cache_type='0';}
            /*
            echo'<hr><pre>  menu_id=>';print_r($menu_id);echo'<pre> ';
            echo'<hr><pre>  cache_type=>';print_r($cache_type);echo'<pre>';
            echo'<hr><pre>  deletekey=>';print_r($deletekey);echo'<pre>'; die();
            */
            ##Cach Toools Start######
            if($menu_id==null){$menuid='all';}else{$menuid=$menu_id;}
            $cachekey="key-seb-menu-id-".$menuid;
            $cachetime=60*60*60*365*10;
            $cache_day=$cachetime/(60*60*60*1);
            //cachefile 
            $cachetype='2'; 
            $this->load->model('Cachtooluser_model');
            $sql=null;
            $dir='usertype';
            $cachechk=$this->Cachtooluser_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
            $cachechklist=$cachechk['list'];
            //echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>'; //Die();
            if($cachechklist!=null && $cache_type!=0){
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
            $cache_msg='Form Cache File Results';
            $dataresult_all=array('rs'=>$dataresult,
                        'cache_msg'=>$cache_msg,
                        'cache_key'=>$cache_key,
                        'cachetime'=>$cachetime,
                        'cache_day'=>$cache_day);
            }
            elseif($cachechklist==null){
            ################################
            $this->db->cache_off();
            $this->db->select('*');
            $this->db->from('sd_user_menu as menu');
            if($menu_id!==null){
                  $this->db->where('menu.parent',$menu_id);  
            }
                  $this->db->where('menu.status',1); 
                  $this->db->order_by("menu.order_by asc, menu.menu_id asc");
            $query_get=$this->db->get();
            $num=$query_get->num_rows();
            $query_result=$query_get->result(); 
            $rs=$query_result;
            $dataresult=$rs;
            $cache_msg='Form SQL Query Results';
            $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg);
            $dataresult_all=array('rs'=>$dataresult,
                        'cache_msg'=>$cache_msg,
                        'cache_key'=>$cachekey,
                        'cachetime'=>$cachetime,
                        'cache_day'=>$cache_day);
            
            //echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'<pre>'; Die();
            
            ################################
            if($cache_type!=0){
            $this->load->model('Cachtooluser_model');
                  $sql=null;
                  $dir='usertype';
                  $cacheset=$this->Cachtooluser_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
                  //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
                  $rs=$cacheset['list'];
                  $num=count($rs);
                  $cache_msg=$cacheset['message'];
            }

            }
            ##Cach Toools END######
            ################################################ 
            return $dataresult_all;
  }
##############################
public function setDataCookie($cookie_name,$cookie_value,$time){
      // $this->session->set_userdata('userid',152);
      // set_cookie($this->setDataCookie('useridx', $query_result['useridx']));
      $this->load->helper('cookie');
      if($time==null){$time=3600;}
      $ip_address=$this->input->ip_address();
      $user_agent=$this->input->user_agent();
      $cookie=array(
                  'name' => $cookie_name,
                  'value' => $cookie_value,                            
                  'expire' => (time()+$time),      
                  'path' => "/",                                                                           
                  'secure' => false
            );
      $cookiert=$this->input->set_cookie($cookie);
      return $cookiert;
      } 
public function deleteCookie($cookie_name,$cookie_value){
      $this->load->helper('cookie');   	
      $cookie=array(
                  'name' => $cookie_name,
                  'value' => $cookie_value,        
                  'path' => "/",                                                                           
                  'secure' => false
            );
      delete_cookie($cookie);
      return $cookie;
      } 
public function cookie_get(){
      return $_COOKIE;
      }
public function session_get(){
      return $_SESSION;
      }  
public function cookie_session_get(){
      $data=array('COOKIE'=>$_COOKIE,
                  'SESSION'=>$_SESSION,
                  );
      return $data;
       
      }      
public function session_set($sesdata){
      $this->session->set_userdata($sesdata);
      } 
public function logout(){
      //session_destroy();
      $this->session->sess_destroy();    
      ################################################
      $getcookie=$this->getcookie();
      $userid_cookie=@$getcookie['userid'];
      if($userid_cookie!==null){
      $this->deleteCookie('userid',$userid_cookie);     
      }
      $uname_cookie=@$getcookie['uname'];
      if($uname_cookie!==null){
      $this->deleteCookie('uname',$uname_cookie);     
      }
      $utype_cookie=@$getcookie['utype'];
      if($utype_cookie!==null){
      $this->deleteCookie('utype',$utype_cookie);     
      }
      $fullname_cookie=@$getcookie['fullname'];
      if($fullname_cookie!==null){
      $this->deleteCookie('fullname',$fullname_cookie);     
      }
      $useridx_cookie=@$getcookie['useridx'];
      if($useridx_cookie!==null){
      $this->deleteCookie('useridx',$useridx_cookie);     
      }
      $pracharathschool_session=@$getcookie['pracharathschool_session_'];
      if($pracharathschool_session!==null){
      $this->deleteCookie('pracharathschool_session_',$pracharathschool_session);     
      }
      $getcookie2=$this->getcookie();

      //echo'<hr><pre> getcookie2=>';print_r($getcookie2);echo'<pre>';
      $session_get=$this->session_get();
      //echo'<hr><pre> session_get=>';print_r($session_get);echo'<pre>'; die();
      return 1;
      } 
public function singout(){
      $getcookie=$this->getcookie();  
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
############**user** #####################
public function user_rows($key_word,$type,$type_id,$deletekey,$cache_type=0){
      /*
      echo'<hr><pre>  key_word=>';print_r($key_word);echo'<pre> ';
      echo'<hr><pre>  type=>';print_r($type);echo'<pre>';
      echo'<hr><pre>  deletekey=>';print_r($deletekey);echo'<pre>'; //die();
      */

      ##Cach Toools Start######
      if($type_id==null){$typeid='all';}else{$typeid=$type_id;}
      $cachekey="key-tbl-user-2018-rows-keyword-".$key_word.'-type-'.$type.'-typeid-'.$typeid;
      $cachetime=60*60*60*1;
      $cache_day=$cachetime/(60*60*60*1);
      //cachefile 
      $cachetype=2; 
      $this->load->model('Cachtooluser_model');
      $sql=null;
      $cachechk=$this->Cachtooluser_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey,'usertype');
      $cachechklist=$cachechk['list'];
      //echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>'; //Die();
      if($cachechklist!=null && $cache_type!=0){
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
      $cache_msg='Form Cache File Results';
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cache_key,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day);
      }
      elseif($cachechklist==null || $cache_type==0){
      ################################
      $this->db->cache_off();
      //$this->db->cache_delete_all();
      $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
      $this->db->from('tbl_user_2018');
      $this->db->join('sd_user_type', 'sd_user_type.user_type_id=tbl_user_2018.user_type_id');
      if($type_id!==null){
      $this->db->where('sd_user_type.user_type_id',$type_id); 
      }  
      if($key_word!==null){
      $this->db->like('tbl_user_2018.name',$key_word); 
      $this->db->or_like('tbl_user_2018.surname',$key_word); 
      $this->db->or_like('tbl_user_2018.email',$key_word); 
      $this->db->or_like('tbl_user_2018.position',$key_word); 
      $this->db->or_like('tbl_user_2018.company',$key_word);
      $this->db->or_like('tbl_user_2018.company_group',$key_word);
      }
      $order_by='asc';
      $this->db->order_by('tbl_user_2018.user_idx',$order_by);
      $num=$this->db->count_all_results();
      //echo'<hr> <pre>num=>';print_r($num);echo'<pre>'; Die();
      $dataresult=$num;
      $cache_msg='Form SQL Query Results';
      $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg);
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cachekey,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day);
      
      //echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'<pre>'; Die();
      
      ################################
      if($cache_type!=0){
      $this->load->model('Cachtooluser_model');
            $sql=null;
            $dir='usertype';
            $cacheset=$this->Cachtooluser_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
            //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
            $rs=$cacheset['list'];
            $num=count($rs);
            $cache_msg=$cacheset['message'];
      }

      }
      ##Cach Toools END######
      ################################################ 
      return $dataresult_all;
      }
public function user_list($key_word,$type,$type_id,$offset,$per_page,$deletekey,$cache_type=0){
      /*
      echo'<hr><pre>  key_word=>';print_r($key_word);echo'<pre> ';
      echo'<hr><pre>  type=>';print_r($type);echo'<pre>';
      echo'<hr><pre>  deletekey=>';print_r($deletekey);echo'<pre>';
      echo'<hr><pre>  offset=>';print_r($offset);echo'<pre>';
      echo'<hr><pre>  per_page=>';print_r($per_page);echo'<pre>'; //die();
      */
      ##Cach Toools Start######
      if($type_id==null){$typeid='all';}else{$typeid=$type_id;}
      $cachekey="key-tbl-user-2018-rows-keyword-".$key_word.'-type-'.$type.'-typeid-'.$typeid.'-offset-'.$offset.'-per_page-'.$per_page;
      $cachetime=60*60*60*1;
      $cache_day=$cachetime/(60*60*60*1);
      //cachefile 
      $cachetype='2'; 
      $this->load->model('Cachtooluser_model');
      $sql=null;
      $dir='usertype';
      $cachechk=$this->Cachtooluser_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
      $cachechklist=$cachechk['list'];
      //echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>'; //Die();
      if($cachechklist!=null && $cache_type!=0){
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
      $cache_msg='Form Cache File Results';
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cache_key,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day);
      }
      elseif($cachechklist==null || $cache_type==0){
      ################################
      $this->db->cache_off();
      //$this->db->cache_delete_all();
      $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
      $this->db->from('tbl_user_2018');
      $this->db->join('sd_user_type', 'sd_user_type.user_type_id=tbl_user_2018.user_type_id');
      if($type_id!==null){
      $this->db->where('sd_user_type.user_type_id',$type_id); 
      }  
      if($key_word!==null){
      $this->db->like('tbl_user_2018.name',$key_word); 
      $this->db->or_like('tbl_user_2018.surname',$key_word); 
      $this->db->or_like('tbl_user_2018.email',$key_word); 
      $this->db->or_like('tbl_user_2018.position',$key_word); 
      $this->db->or_like('tbl_user_2018.company',$key_word);
      $this->db->or_like('tbl_user_2018.company_group',$key_word);
      }
      $order_by='asc';
      $this->db->order_by('tbl_user_2018.user_idx',$order_by);

      $this->db->limit($per_page,$offset);
      $query_get=$this->db->get();
      $num=$query_get->num_rows();
      $query_result=$query_get->result(); 
      $rs=$query_result;
      $dataresult=$rs;
      ################################
      $cache_msg='Form SQL Query Results';
      $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg);
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cachekey,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day);
      
      //echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'<pre>'; Die();
      
      ################################
      if($cache_type!=0){
      $this->load->model('Cachtooluser_model');
            $sql=null;
            $dir='usertype';
            $cacheset=$this->Cachtooluser_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
            //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
            $rs=$cacheset['list'];
            $num=count($rs);
            $cache_msg=$cacheset['message'];
      }

      }
      ##Cach Toools END######
      ################################################ 
      return $dataresult_all;
      }
public function where_user_idx($user_idx){
      $this->db->cache_off();
      //$this->db->cache_delete_all();
      $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
      $this->db->from('tbl_user_2018');
      $this->db->join('sd_user_type', 'sd_user_type.user_type_id=tbl_user_2018.user_type_id');
      $this->db->where('tbl_user_2018.user_idx',$user_idx); 
      $query_get=$this->db->get();
      $num=$query_get->num_rows();
      $query_result=$query_get->result(); 
      $rs=$query_result;
      $dataresult=$rs;
      return $dataresult;
      }
public function where_user_chk_login($username,$password){
      $password_md5=md5($password);
      $this->db->cache_off();
      //$this->db->cache_delete_all();
      $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
      $this->db->from('tbl_user_2018');
      $this->db->join('sd_user_type', 'sd_user_type.user_type_id=tbl_user_2018.user_type_id');
      $this->db->where('tbl_user_2018.username',$username); 
      $this->db->where('tbl_user_2018.password',$password_md5);
      # $this->db->where('tbl_user_2018.user_status',1);
            $query_get=$this->db->get();
            $num=$query_get->num_rows();
            $query_result=$query_get->result(); 
            $rs=$query_result;
            $dataresult=$rs;
      return $dataresult;
      }
public function user_type_id($user_type_id,$deletekey,$cache_type=0){
      ##Cach Toools Start######
      if($user_type_id==null){$user_type_id='all';}else{$typeid=$user_type_id;}
      $cachekey="key-tbl-sd-user-type-id-".$user_type_id;
      $cachetime=60*60*60*1;
      $cache_day=$cachetime/(60*60*60*1);
      //cachefile 
      $cachetype=2; 
      $this->load->model('Cachtooluser_model');
      $sql=null;
      $cachechk=$this->Cachtooluser_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey,'usertype');
      $cachechklist=$cachechk['list'];
      //echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>'; //Die();
      if($cachechklist!=null && $cache_type!=0){
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
      $cache_msg='Form Cache File Results';
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cache_key,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day);
      }
      elseif($cachechklist==null || $cache_type==0){
      ################################
      $this->db->cache_off();
            //$this->db->cache_delete_all();
            $this->db->select('sd_user_access_menu.*,sd_user_type.user_type_title');
            $this->db->from('sd_user_access_menu');
            $this->db->join('sd_user_type', 'sd_user_access_menu.user_type_id=sd_user_type.user_type_id');
            // if user type == admin provide select all menu [Edited By First]            
            if($user_type_id != 1){
                  $this->db->where('sd_user_access_menu.user_type_id',$user_type_id); 
            }
            // END if user type == admin provide select all menu [Edited By First]
            $query_get=$this->db->get();
            $num=$query_get->num_rows();
            $query_result=$query_get->result(); 
            $rs=$query_result;
      $dataresult=$rs;
      $cache_msg='Form SQL Query Results';
      $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg);
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cachekey,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day);
      
      //echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'<pre>'; Die();
      
      ################################
      if($cache_type!=0){
      $this->load->model('Cachtooluser_model');
            $sql=null;
            $dir='usertype';
            $cacheset=$this->Cachtooluser_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey,$dir);
            //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
            $rs=$cacheset['list'];
            $num=count($rs);
            $cache_msg=$cacheset['message'];
      }

      }
      ##Cach Toools END######
      ################################################ 
      return $dataresult_all;
      }
############** user access menu** #####################
public function sd_user_access_menu_select($user_type_id='',$menu_id=''){
            $this->db->cache_off();
            $this->db->select('*');
            $this->db->from('sd_user_access_menu');
      if($user_type_id==null || $menu_id==null){
      ### 
      }elseif($user_type_id!==null && $menu_id!==null){
            $this->db->where('user_type_id',$user_type_id); 
            $this->db->where('menu_id',$menu_id); 
      }
            $query_get=$this->db->get();
            $last_query=$this->db->last_query();
            $num=$query_get->num_rows();
            $query_result=$query_get->result(); 
            return $query_result;     
      }
public function sd_user_access_insert($user_type_id,$menu_id){    
      $table='sd_user_access_menu';
      $insertdata=array('user_type_id'=>$user_type_id,
                  'menu_id'=>$menu_id,
                  );
      $this->db->trans_start();
      $this->db->insert($table,$insertdata);
      $this->db->trans_complete();
      $insert_data_log=$this->db->insert_id();
      $affected_rows=$this->db->affected_rows();
      $last_query=$this->db->last_query();
      //echo ' affected_rows=>'.$affected_rows; die();
            return $affected_rows;  
      }
public function sd_user_access_menu_add($user_type_id,$menu_id){
      $this->db->cache_off();
      $this->db->select('*');
      $this->db->from('sd_user_access_menu');
      $this->db->where('user_type_id',$user_type_id); 
      $this->db->where('menu_id',$menu_id); 
      $query_get=$this->db->get();
      $last_query=$this->db->last_query();
      $num=$query_get->num_rows();
            $query_result=$query_get->result(); 
      if($num<=0){
      $table='sd_user_access_menu';
      $insertdata=array('user_type_id'=>$user_type_id,
                  'menu_id'=>$menu_id,
                  );
      $this->db->trans_start();
      $this->db->insert($table,$insertdata);
      $this->db->trans_complete();
      $insert_data_log=$this->db->insert_id();
      $affected_rows=$this->db->affected_rows();
      $last_query=$this->db->last_query();
      //echo ' affected_rows=>'.$affected_rows; die();
            return $affected_rows;  
      }else{
            $table='sd_user_access_menu';
            $this->db->where('user_type_id',$user_type_id); 
            $this->db->where('menu_id',$menu_id); 
            $this->db->delete('sd_user_access_menu');
            $lastquery=$this->db->last_query();     
            $table='sd_user_access_menu';
            $insertdata=array('user_type_id'=>$user_type_id,
                        'menu_id'=>$menu_id,
                        );
            $this->db->trans_start();
            $this->db->insert($table,$insertdata);
            $this->db->trans_complete();
            $insert_data_log=$this->db->insert_id();
            $affected_rows=$this->db->affected_rows();
            $last_query=$this->db->last_query();
            //echo ' affected_rows=>'.$affected_rows; die();
            return $affected_rows;     
      }

      }
public function sd_user_access_menu_update($user_type_id,$menu_id){
      $this->db->set('user_type_id',$user_type_id);  
      $this->db->set('menu_id',$menu_id);
      $this->db->where('user_type_id',$user_type_id);
      $this->db->where('menu_id',$menu_id);
      $this->db->update('sd_user_access_menu'); 
            $last_query=$this->db->last_query();
            $num=$query_get->num_rows();
            $query_result=$query_get->result(); 
            if ($this->db->_error_message()) {
            return FALSE; // Or do whatever you gotta do here to raise an error
            } else {
            return $this->db->affected_rows();
            }
      }
public function sd_user_access_menu_delete($user_type_id,$menu_id){    
      $table='sd_user_access_menu';
      $query = $this->db->delete($table, array('user_type_id'=>$user_type_id,'menu_id'=>$menu_id));
      //return $this->db->affected_rows();
      return $query;
      }
############** user  menu** #####################
public function insert_tb_array($table='sd_user_menu',$data=array()){    
    $this->db->trans_start();
    $this->db->insert($table,$data);
    $this->db->trans_complete();
    $insert_data_log=$this->db->insert_id();
    $affected_rows=$this->db->affected_rows();
    $last_query=$this->db->last_query();
    //echo ' affected_rows=>'.$affected_rows; die();
      return $affected_rows;  
      }
public function delete_tb_array($table='sd_user_menu',$data=array()){    
      $query=$this->db->delete($table,$data);
      //return $this->db->affected_rows();
      return $query;
      }

}

/*
$this->load->library('Accessuser_library');
$session_cookie_get=$this->accessuser_library->session_cookie_get();
$COOKIE=@$session_cookie_get['COOKIE'];
$SESSION=@$session_cookie_get['SESSION'];
$user_type_id=@$SESSION['user_type_id']; 
if($user_type_id==null){$user_type_id=@$_COOKIE['user_type_id'];} 
$user_type_name=@$SESSION['user_type_name']; 
if($user_type_name==null){$user_type_name=@$_COOKIE['user_type_name'];}  
$is_Admin=@$this->check_admin($user_idx);
$user_idx=@$SESSION['user_idx'];
if($user_idx==null){$user_idx=@$COOKIE['user_idx'];}
if($user_idx==null){
           $urldirec=base_url('user/login/');
            echo ("<script LANGUAGE='JavaScript'>
                window.alert(' กรุณา Login ');
                window.location.href='$urldirec';
                </script>"); die(); 
}
$user_type_id=$this->user_type_id;
$user_type_name=$this->user_type_name;
if($user_type_id==1 || $user_type_id==2){}else{
           $urldirec=base_url('user/dashboard/');
            echo ("<script LANGUAGE='JavaScript'>
                window.alert(' Forbidden, Access Denied ,ปฏิเสธการเข้าถึงระบบ ');
                window.location.href='$urldirec';
                </script>"); die(); 
}



*/