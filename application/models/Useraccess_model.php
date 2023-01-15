<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class Useraccess_model extends CI_Model {
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
      $cachekey="key-menu-access-user-type-id-$user_type_id-main-menu-in-".$menuid;
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
      $last_query_sql=$this->db->last_query();
      $query_result=$query_get->result(); 
      $rs=$query_result;
      $dataresult=$rs;
      $cache_msg='Form SQL Query Results';
      $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg);
      $dataresult_all=array('rs'=>$dataresult,
                  'cache_msg'=>$cache_msg,
                  'cache_key'=>$cachekey,
                  'cachetime'=>$cachetime,
                  'cache_day'=>$cache_day,
                  'last_query_sql'=>$last_query_sql,
            );

      #echo'<hr> <pre>  $last_query_sql=>';print_r($last_query_sql);echo'<pre>'; 
      #echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'<pre>'; Die();
      
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
public function submenuv1($menu_id,$cache_type,$user_type_id,$deletekey){
      $ty=1;
      if($ty==1){
                  $dataresult_all=$this->submenu_sql($menu_id,$cache_type,$user_type_id,$deletekey);
            }else{  
                  $dataresult_all=$this->submenu_cache($menu_id,$cache_type,$user_type_id,$deletekey);
             }
      return $dataresult_all;
      }
public function submenu_sql($menu_id,$cache_type,$user_type_id,$deletekey){
      if($cache_type==''){$cache_type='0';}
      /*
      echo'<hr><pre>  menu_id=>';print_r($menu_id);echo'<pre> ';
      echo'<hr><pre>  cache_type=>';print_r($cache_type);echo'<pre>';
      echo'<hr><pre>  deletekey=>';print_r($deletekey);echo'<pre>'; die();
      */
     # $debug=0;
     # $debug=1;
      $debug=0; 
     #$menu_id=1;
     if($menu_id==null){$menuid='all';}else{$menuid=$menu_id;}
     $menuidmain=$menu_id;
     ################################ SQL  #################
            $this->db->cache_off();
            $this->db->select('*');
            $this->db->from('sd_user_menu as menu');
            if($menuidmain!==null){ $this->db->where('menu.parent',$menuidmain);}
            $this->db->where('menu.status',1); 
            $this->db->order_by("menu.order_by asc, menu.menu_id asc");
            $query_get=$this->db->get();
            $num_m1=$query_get->num_rows();
            $last_query_sql=$this->db->last_query();
            $query_result=$query_get->result(); 
            $menuresult=$query_result;
            $menuresult1=$menuresult;
            $menuresult1_count=count($menuresult1);
            
           #if($debug==1){ echo ' count=>'.$menuresult1_count; echo'<hr><pre> 1 sql=>'.$last_query_sql;  echo'<pre>  menu1=>';print_r($menuresult1);echo'</pre>';}
            
      ################################ SQL  #################\
            /*
            SELECT *  FROM `sd_user_access_menu` as `access_menu`
            WHERE `access_menu`.`menu_id` IN('7','35','36','74','6','8','64','31','32','33','43','70','71')
            AND `access_menu`.`user_type_id` = '7'
            ORDER BY `access_menu`.`user_access_id` asc
            */
            ################################*******************######################
            if($menuresult1_count>0){
                  ################################*******************######################
                  if(is_array($menuresult)){$arrmenu=array();$arr1=array();foreach ($menuresult as $key=> $value){ $menu_id=@$value->menu_id; $arr1['menu']=$menu_id;  $arrmenu[]=$arr1['menu']; }}
                  $arrmenu_implode=implode(',',$arrmenu);  
                   $arrmenu_explode=explode(',',$arrmenu_implode);   
                  #echo'<hr><pre> arrmenu_explode=>';print_r($arrmenu_explode);echo'</pre>';  die(); 
                  #echo'<hr><pre>  arrmenu_whereinarray=>';print_r($arrmenu_whereinarray);echo'</pre>';  die();
                  $arrmenu_whereinarray=array_map('intval', explode(',', $arrmenu_implode));
                  # echo'<hr><pre>  arrayac=>';print_r($arrmenu_whereinarray);echo'</pre>';  die();
                  ################sd_user_access_menu################ 
                  //select * from sd_user_access_menu where menu_id in (select menu_id from sd_user_menu where parent=2)
                  $this->db->cache_off();
                  $this->db->select('*');
                  $this->db->from('sd_user_access_menu as access_menu');
                  $this->db->where_in('access_menu.menu_id', $arrmenu_whereinarray);   
                  $this->db->where('access_menu.user_type_id',$user_type_id); 
                  $this->db->order_by("access_menu.user_access_id asc");
                  $access_menu_query_get=$this->db->get();
                  $access_menu_num=$access_menu_query_get->num_rows();
                  $access_menu_query_result=$access_menu_query_get->result(); 
                  $access_menu_last_query=$this->db->last_query();
                  #echo'<hr><pre>  access_menu_query_result=>';print_r($access_menu_query_result);echo'</pre>'; #die();
                  ################sd_user_access_menu################ 
                  if(is_array($access_menu_query_result)){
                        $arrmenu2=array();$arr2=array();
                        foreach ($access_menu_query_result as $key2=> $value2){
                            $menu_id2=@$value2->menu_id;
                            $arr2['menu']=$menu_id2;
                            $arrmenu2[]=$arr2['menu'];
                              }
                              }
                        $arrmenu_implode2=implode(',',$arrmenu2); 
                        $menu_id_where_in_array=array_map('intval', explode(',', $arrmenu_implode2));
                        $mecount=count($arrmenu2);
                        
                         if($debug==1){ 
                              echo'<hr><pre>  sql=>'.$access_menu_last_query; 
                              echo'<hr><pre>  array menu.parent in=>';print_r($arrmenu_whereinarray);echo'</pre>'; 
                              echo'<pre>  access_menu_query_result=>';print_r($access_menu_query_result);echo'</pre>'; 
                         }
                              
                  ##############################################
                        if($mecount>=1){
                                    $this->db->cache_off();
                                    $this->db->select('*');
                                    $this->db->from('sd_user_menu as menu');
                                    $this->db->where_in('menu.menu_id', $menu_id_where_in_array);   
                                    $this->db->where('menu.status',1); 
                                    $this->db->order_by("menu.order_by asc, menu.menu_id asc");
                                    $query_get=$this->db->get();
                                    $numm1=$query_get->num_rows();
                                    $query_result=$query_get->result(); 
                                    $lastquery=$this->db->last_query();
                                    $dataresultV2= $query_result;
                               }else{
                                    $this->db->cache_off();
                                    $this->db->select('*');
                                    $this->db->from('sd_user_menu as menu');
                                    if($menuidmain!==null){$this->db->where('menu.parent',$menuidmain);}
                                    $this->db->where('menu.status',1); 
                                    $this->db->order_by("menu.order_by asc, menu.menu_id asc");
                                    $query_get=$this->db->get();
                                    $numm1=$query_get->num_rows();
                                    $query_result=$query_get->result(); 
                                    $lastquery=$this->db->last_query();
                                    $dataresultV2= $query_result;
                                    }
                  ##############################################
                  ################################*******************######################
                  
                   if($debug==1){ echo'<hr>1==> sql=>'.$lastquery; echo'<pre> menu.parent in =>';print_r($menu_id_where_in_array);echo'</pre>';  echo' <br> mecount=>'.$mecount;echo'<br><pre>  dataresultV2=>';print_r($dataresultV2);echo'</pre>';die();}
                  $dataresult=$dataresultV2;
            }else{ 
                   
                   if($debug==1){ echo'<hr>0==>sql=>'.$last_query_sql; echo'<pre> menu.parent=>';print_r($menuidmain); echo'<br> num_m1=>'.$num_m1;echo'<br><pre>  dataresult=>';print_r($dataresult);echo'</pre>';die(); }
                  $dataresult=$menuresult1;                  
            }
           
      $dataall=$dataresult;
      $dataresult_all=array('rs'=>$dataall);
      #echo'<hr> <pre> dataresult_all=>';print_r($dataresult_all);echo'</pre>';Die();
      return $dataresult_all;
      }
public function submenu_cache($menu_id,$cache_type,$user_type_id,$deletekey){
            if($cache_type==''){$cache_type='0';}
            /*
            echo'<hr><pre>  menu_id=>';print_r($menu_id);echo'<pre> ';
            echo'<hr><pre>  cache_type=>';print_r($cache_type);echo'<pre>';
            echo'<hr><pre>  deletekey=>';print_r($deletekey);echo'<pre>'; die();
            */
           # $debug=0;
           # $debug=1;
           $debug=0;
           $deletekey=0;
                  ##Cach Toools Start######
                  if($menu_id==null){$menuid='all';}else{$menuid=$menu_id;}
                  $menuidmain=$menu_id;
                  $cachekey="key-seb-menu-id-parent-".$menuid.'-user_type_id-'.$user_type_id;
                  $cachetime=3600;
                  $cache_day=$cachetime/(60*60*60*1);
                  //cachefile 
                  $cachetype=2; 
                  //cachefile 
                  $this->load->model('Cachtool_model');
                  $sql=null;
                  $cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
                  $cachechklist=$cachechk['list'];
                 
          
     if($cachechklist==null){
           ################################ SQL  #################
                  $this->db->cache_off();
                  $this->db->select('*');
                  $this->db->from('sd_user_menu as menu');
                  if($menuidmain!==null){ $this->db->where('menu.parent',$menuidmain);}
                  $this->db->where('menu.status',1); 
                  $this->db->order_by("menu.order_by asc, menu.menu_id asc");
                  $query_get=$this->db->get();
                  $num_m1=$query_get->num_rows();
                  $last_query_sql=$this->db->last_query();
                  $query_result=$query_get->result(); 
                  $menuresult=$query_result;
                  $menuresult1=$menuresult;
                 if($debug==1){ echo'<hr><pre>  sql=>'.$last_query_sql;  echo'<pre>  menuresult1=>';print_r($menuresult1);echo'</pre>';}
                  
            ################################ SQL  #################
                  ################################*******************######################
                  if($num_m1>=1){
                        ################################*******************######################
                        if(is_array($menuresult)){$arrmenu=array();$arr1=array();foreach ($menuresult as $key=> $value){ $menu_id=@$value->menu_id; $arr1['menu']=$menu_id;  $arrmenu[]=$arr1['menu']; }}
                        $arrmenu_implode=implode(',',$arrmenu);   $arrmenu_explode=explode(',',$arrmenu_implode);  $arrmenu_whereinarray = array($arrmenu_implode);
                        #echo'<hr><pre>  arrmenu_whereinarray=>';print_r($arrmenu_whereinarray);echo'</pre>'; #die();
                        ################sd_user_access_menu################ 
                        //select * from sd_user_access_menu where menu_id in (select menu_id from sd_user_menu where parent=2)
                        $this->db->cache_off();
                        $this->db->select('*');
                        $this->db->from('sd_user_access_menu as access_menu');
                        $this->db->where_in('access_menu.menu_id', $arrmenu_whereinarray);   
                        $this->db->where('access_menu.user_type_id',$user_type_id); 
                        $this->db->order_by("access_menu.user_access_id asc");
                        $access_menu_query_get=$this->db->get();
                        $access_menu_num=$access_menu_query_get->num_rows();
                        $access_menu_query_result=$access_menu_query_get->result(); 
                        $access_menu_last_query=$this->db->last_query();
                        #echo'<hr><pre>  access_menu_query_result=>';print_r($access_menu_query_result);echo'</pre>'; #die();
                        ################sd_user_access_menu################ 
                        if(is_array($access_menu_query_result)){
                              $arrmenu2=array();$arr2=array();
                              foreach ($access_menu_query_result as $key2=> $value2){
                                  $menu_id2=@$value2->menu_id;
                                  $arr2['menu']=$menu_id2;
                                  $arrmenu2[]=$arr2['menu'];
                                    }
                                    }
                              $arrmenu_implode2=implode(',',$arrmenu2); 
                              $menu_id_where_in_array = array($arrmenu_implode2); 
                              $mecount=count($arrmenu2);
                              
                               if($debug==1){ echo'<hr><pre>  sql=>'.$access_menu_last_query;  echo'<pre>  menu_id_where_in_array=>';print_r($menu_id_where_in_array);echo'</pre>';  }
                                    
                        ##############################################
                              if($mecount>=1){
                                          $this->db->cache_off();
                                          $this->db->select('*');
                                          $this->db->from('sd_user_menu as menu');
                                          $this->db->where_in('menu.menu_id', $menu_id_where_in_array);   
                                          $this->db->where('menu.status',1); 
                                          $this->db->order_by("menu.order_by asc, menu.menu_id asc");
                                          $query_get=$this->db->get();
                                          $numm1=$query_get->num_rows();
                                          $query_result=$query_get->result(); 
                                          $lastquery=$this->db->last_query();
                                          $dataresultV2= $query_result;
                                     }else{
                                          $this->db->cache_off();
                                          $this->db->select('*');
                                          $this->db->from('sd_user_menu as menu');
                                          if($menuidmain!==null){$this->db->where('menu.parent',$menuidmain);}
                                          $this->db->where('menu.status',1); 
                                          $this->db->order_by("menu.order_by asc, menu.menu_id asc");
                                          $query_get=$this->db->get();
                                          $numm1=$query_get->num_rows();
                                          $query_result=$query_get->result(); 
                                          $lastquery=$this->db->last_query();
                                          $dataresultV2= $query_result;
                                          }
                        ##############################################
                        ################################*******************######################
                        
                         if($debug==1){ echo'<hr>1==> sql=>'.$lastquery; echo' <br> mecount=>'.$mecount;echo'<br><pre>  dataresultV2=>';print_r($dataresultV2);echo'</pre>';die();}
                        $dataresult=$dataresultV2;
                        }else{ 
                              
                              if($debug==1){ echo'<hr>0==>sql=>'.$last_query_sql; echo'<br> num_m1=>'.$num_m1;echo'<br><pre>  dataresult=>';print_r($dataresult);echo'</pre>';die(); }
                              $dataresult=$menuresult1;                  
                        }
                  ################################*******************######################
                  ################################ SQL  #################
                        $this->load->model('Cachtool_model');
                        $sql=null;
                        $cacheset=$this->Cachtool_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey);
                        //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
                        $dataall=$cacheset['list'];
                        $num=count($dataall);
                        $cache_msg=$cacheset['message'];
            }else{ 
                       
                                          #echo' Form Cache <hr> <pre>  cachechk =>';print_r($cachechk);echo'<pre>';  
                                          $dataall=$cachechklist;
                                          $list=$cachechk['list'];
                                          #echo' Form Cache <hr> <pre>   list =>';print_r($list);echo'<pre>'; 
                                          $list=$cachechk['list'];
                                          $message=$cachechk['message'];
                                          $status=$cachechk['status'];
                                          $count=$cachechk['count'];
                                          $cachetime=$cachechk['cachetime'];
                                          $cache_key=$cachechk['cachekey'];
                                          $rs=$list;
                                          $dataall=$rs;
                                    $cache_msg='Form Cache type file';
                         
                  }
             ##Cach Toools END######
            ################################################ 
            #echo'<hr> <pre> submenu_all=>';print_r($dataall);echo'</pre>'; Die();
         
      $dataresult_all=array('rs'=>$dataall,
                              'cache_msg'=>$cache_msg,
                              'cache_key'=>$cachekey,
                              'cachetime'=>$cachetime,
                              'cache_day'=>$cache_day);
            #echo'<hr> <pre> dataresult_all=>';print_r($dataresult_all);echo'</pre>';Die();
      return $dataresult_all;
      }
public function submenu($menu_id,$cache_type,$deletekey){
           
      /*
      
            ##Cach Toools Start######
            if($menu_id==null){$menuid='all';}else{$menuid=$menu_id;}
            $cachekey="key-seb-menu-id-parent".$menuid;
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
      */

   
     # echo'<pre> deletekey =>';print_r($deletekey);echo'</pre>'; Die();
      if($cache_type==''){$cache_type=2;}
      ##Cach Toools Start######
      if($menu_id==null){$menuid='all';}else{$menuid=$menu_id;}
      $cachekey="key-sebmenu-parent-id-".$menuid;
      $cachetime=3600;
      $cache_day=$cachetime/(60*60*60*1);
      //cachefile 
      $cachetype='2'; 
      $this->load->model('Cachtool_model');
      $sql=null;
      $cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
      $cachechklist=$cachechk['list'];
      //echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'<pre>'; Die();
      if($cachechklist!=null){
                  #echo' Form Cache <hr> <pre>  cachechk =>';print_r($cachechk);echo'<pre>';  
                  $list=$cachechklist;
                  #echo' Form Cache <hr> <pre>   list =>';print_r($list);echo'<pre>'; 
                  $message=$cachechk['message'];
                  $status=$cachechk['status'];
                  $count=$cachechk['count'];
                  $cachetime=$cachechk['cachetime'];
                  $cache_key=$cachechk['cachekey'];
                  $rs=$list;
                  $dataresult=$rs;
                  $result_menus_access=$rs;
            $cache_msg='Form Cache type file';
            $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg);
            /*  $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg,'cache_key'=>$cachekey,'cachetime'=>$cachetime,'cache_day'=>$cache_day);  */
            
      }elseif($cachechklist==null){
            ################################ SQL  #################
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
            /*  $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg,'cache_key'=>$cachekey,'cachetime'=>$cachetime,'cache_day'=>$cache_day);  */
            
            //echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'<pre>'; Die();
            ################################ SQL  #################
            $this->load->model('Cachtool_model');
            $sql=null;
            $cacheset=$this->Cachtool_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey);
            //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
            $result_menus_access=$cacheset['list'];
            $num=count($result_menus_access);
            $cache_msg=$cacheset['message'];
      }
      ##Cach Toools END######
      return $dataresult_all;

  }
##############################
public function menuusertypev2($user_type_id='',$cache_type='',$deletekey='',$urlnow='',$user_idx='',$menu_id_active=''){
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
            $access_status=0;
            $access_status_url=0;
            $this->CI->load->model('Useraccess_model','',TRUE);
            $menuidin=$this->CI->Useraccess_model->user_type_id($user_type_id,$deletekey,$cache_type);
            $menuidin=$menuidin['rs'];
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
            if(is_array($menu)){
                  $access_status=0;
                  $access_status_url=0;
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
                                    if($user_type_id==1 ||$user_type_id==2){$access_status_alt=2;}
                              }  
                        }
                        
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
                        $menu2rs=$this->CI->Useraccess_model->submenu($menu_id,$cache_type,$deletekey);
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
                                          $submenurs=$this->CI->Useraccess_model->submenu($menu2_id,$cache_type,$deletekey);
                                          $submenu=$submenurs['rs'];
                                          $arr2['b']['submenu']=$submenu;
                                    if($access_status_b_alt==1){}else{ $menu22_arr[]=$arr2['b']; 
                                    } 
      
                                    #if($access_status_url==1){}
                                    #if($access_status==1){}
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
                        if($access_status_url==1){$menu_main_arr[]=$arr['menumain'];}
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
                        if($urlnow_explode_ar_1!==null){  $urlnow_explode_ar_1=$urlnow_explode['1']; }
                        if($urlnow_explode_ar_2!==null){  $urlnow_explode_ar_2=$urlnow_explode['2']; }
                        if($urlnow_explode_ar_3!==null){  $urlnow_explode_ar_3=$urlnow_explode['3']; }
                  $menu2rs=$this->CI->Useraccess_model->submenu($menu_id,$cache_type,$deletekey);
                  $menu2=$menu2rs['rs'];
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
                              if(($url_explode_ar_mn_0==$urlnow_explode_ar_0) && ($url_explode_ar_mn_1==$urlnow_explode_ar_1)){  
                                    $menuarr2[]=$menu_arr2['ma']; 
                              }elseif($url_explode_ar_mn_0==$urlnow_explode_ar_0){ 
                                    $menuarr2[]=$menu_arr2['ma'];   
                              }else{
                                    $menuarr2=null;
                              }
                              
                        }                       
                  }
      
            ############***********menu sub End*********#############
            $submenulisturlnow=$menuarr2;
            $menuarr2count=count($menuarr2);
            if($submenulisturlnow>0){ $access_status=1;  }else{ if($menu_main_arr_count>0){ $access_status=1;}else{ $access_status=0;} } 
            if($menu_main_arr_count>0){ $access_status_url=1;}else{ $access_status_url=0;}
            $cache_msg=$menurs['cache_msg'];
            $cache_key=$menurs['cache_key'];
            $cachetime=$menurs['cachetime'];
            $cache_day=$menurs['cache_day'];
            if($user_type_id==1 || $user_type_id==2){ $access_status_url=1; $access_status=1; }
            ####################################
            $option=null;$debug=null;
            $menusaccessed=$this->CI->Useraccess_model->usertypeaccessmenu($user_type_id,$option,$debug,$deletekey);
            $accesse_url=$menusaccessed;
            $menu_access=array();
            if(is_array($menusaccessed)) {
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
                  
                  if($url_current_explode_arr_1==$url_menu_explode_arr_1){  
                              $menu_access[]=$access['ac']; 
                              }else{  
                              if($url_current_explode_arr_0==$url_menu_explode_arr_0){ 
                                    # $menu_access[]=$access['ac']; 
                                    }
                  }
                  
            }}
            ####################################
            if(is_array($accesse_url)) {
            $accesse_url_arr=array();
            foreach($accesse_url as $key2 =>$var2){ $url2=$var2->url; $accesse_url_arr[]=$url2;
            }}
            if(is_array($menu_arr)) {
                  $menu_user_arr=array();
                  foreach($menu_arr as $k =>$var3){ 
                        $url3['mainurl']=$var3['url']; 
                        $submenu=$var3['submenu']; 
                        ################
                        if(is_array($submenu)) {
                              $menu_user_arr4=array();
                              foreach($submenu as $k4 =>$var4){ 
                                    $url4['url']=$var4['url']; 
                                    $menu_user_arr4[]=$url4['url'];
                              }}
                        ################
                        $url3['submenu']=$menu_user_arr4; 
                        $url3['submenu_count']=count($menu_user_arr4); 
                        $menu_user_arr[]=$url3;
                  }}
            ####################################
            $menusaccessed_user_count=count($menu_access);
            if($menusaccessed_user_count==0){$menu_access=null;$access_status=0;$access_status_url=0;}else{$access_status=1;$access_status_url=1;}
            $menusaccessed_user=$menu_access;
            $dataall=array('cache_msg'=>$cache_msg,
                        'cache_key'=>$cache_key,
                        'cachetime'=>$cachetime,
                        'cache_day'=>$cache_day,
                        'list'=>$menu_arr,
                        'urlnow'=>$urlnow,
                        'mainlistnow'=>$mainlistnow,
                        'submenulisturlnow'=>$submenulisturlnow,
                        'access_status_url'=>(int)$access_status_url,
                        'access_status'=>(int)$access_status,
                        'user_type_id'=>$user_type_id,
                        'cache_type'=>$cache_type,
                        'deletekey'=>$deletekey,
                        'user_idx'=>$user_idx,
                        'menusaccessed_user'=>$menusaccessed_user,
                        'accesse_url'=>$accesse_url_arr,
                        'menu_user_arr'=>$menu_user_arr,
                  );
            return $dataall; 
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
      
      # echo'<hr> <pre> user_type_id=>';print_r($user_type_id);echo'<pre>'; 
      # echo'<hr> <pre> deletekey=>';print_r($deletekey);echo'<pre>'; 
      # echo'<hr> <pre> cache_type=>';print_r($cache_type);echo'<pre>'; #Die();
       
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
                        'cache_day'=>$cache_day,
                        'last_query'=>"SELECT `sd_user_access_menu`.*, `sd_user_type`.`user_type_title`, `sd_user_menu`.`title`, `sd_user_menu`.`url`, `sd_user_menu`.`status`, `sd_user_menu`.`option`
                        FROM `sd_user_access_menu`
                        JOIN `sd_user_type` ON `sd_user_type`.`user_type_id`=`sd_user_access_menu`.`user_type_id`
                        JOIN `sd_user_menu` ON `sd_user_menu`.`menu_id`=`sd_user_access_menu`.`menu_id`
                        WHERE `sd_user_access_menu`.`user_type_id` = $user_type_id",
                  );
            #echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'<pre>'; Die();
      }elseif($cachechklist==null || $cache_type==0){
            ################################
            $this->db->cache_off();
                  //$this->db->cache_delete_all();
                  $this->db->select('sd_user_access_menu.*,sd_user_type.user_type_title,sd_user_menu.title,sd_user_menu.url,sd_user_menu.status,sd_user_menu.option');
                  $this->db->from('sd_user_access_menu');
                  $this->db->join('sd_user_type', 'sd_user_type.user_type_id=sd_user_access_menu.user_type_id');
                  $this->db->join('sd_user_menu', 'sd_user_menu.menu_id=sd_user_access_menu.menu_id');
                  // if user type == admin provide select all menu [Edited By First]            
                 if($user_type_id==null){}else{
                        $this->db->where('sd_user_access_menu.user_type_id',$user_type_id); 
                 }
            
                  // END if user type == admin provide select all menu [Edited By First]
                  
                  $query_get=$this->db->get();
                  $num=$query_get->num_rows();
                  $query_result=$query_get->result(); 
                  $last_query=$this->db->last_query();
                  $rs=$query_result;
            $dataresult=$rs;
            $cache_msg='Form SQL Query Results';
            $dataresult_all=array('rs'=>$dataresult,'cache_msg'=>$cache_msg);
            $dataresult_all=array('rs'=>$dataresult,
                        'cache_msg'=>$cache_msg,
                        'cache_key'=>$cachekey,
                        'cachetime'=>$cachetime,
                        'cache_day'=>$cache_day,
                        'last_query'=>$last_query,
                        );
            
            #echo'<hr> <pre>  $dataresult_all=>';print_r($dataresult_all);echo'<pre>'; Die();
            
            ################################
            if($cache_type!=0){
           # echo'<hr> <pre>  $cache_type=>';print_r($cache_type);echo'<pre>'; Die();
            
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

public function getUserTypeAccessMenu($requests = [], $groups = [], $selects = [],$debug='',$deletekey=''){
      $table = 'sd_user_access_menu uam';
      $filters['1'] = 1;
      $data = [];
      if(isset($requests['user_type_id']) && $requests['user_type_id']){
          if($requests['user_type_id'] == 1 || $requests['user_type_id'] == 2){
              $this->db->group_by('uam.menu_id');
          }else{
              $filters['uam.user_type_id'] = $requests['user_type_id'];
          }         
      }
      if(isset($requests['status']) && $requests['status']){
          $filters['um.status'] = $requests['status'];
      }
      $selects = !empty($selects) ? $selects : [
          'uam.*'
          ,'um.title'
          ,'um.url'
      ];
      
      $this->db->select($selects);
      $this->db->join('sd_user_menu um', 'uam.menu_id = um.menu_id');        
      $this->db->group_by($groups);
      $this->db->where($filters);
      $this->db->or_where('uam.user_type_id', 0);
      $query = $this->db->get($table);
      $result_menus_access = $query->result_array();
      $sql_last_query=$this->db->last_query();
      if($debug==1){
            
            echo'<pre>  sql=>';print_r($sql_last_query);echo'<pre>';
            #echo'<pre>  result_menus_access=>';print_r($result_menus_access);echo'<pre>';
       }
      // var_dump($filters);
      // var_dump($this->db->last_query()); die;
      if(!empty($result_menus_access)){
          $result_menu_id = array_column($result_menus_access, 'menu_id');
          $implode_result_menu_id1 = implode(',', $result_menu_id);
          $implode_result_menu_id='0,'.$implode_result_menu_id1;
              $this->db->cache_off();
              $this->db->select('*');
              $this->db->from('sd_user_menu'); 
              //$this->db->where_in('menu_id',$result_menu_id);
              $this->db->where_in('parent',$result_menu_id);
              $this->db->or_where_in('menu_id',$result_menu_id);
              $this->db->where('parent',0);
              // OR  (parent=0 and menu_id IN('47', '2', '4', '5', '21'))
              $query_get=$this->db->get();
              $num=$query_get->num_rows();
              $result_menu_detail=$query_get->result(); 
              $sql_last_query=$this->db->last_query();
              if($debug==1){
            
                  echo'<pre>  sql=>';print_r($sql_last_query);echo'<pre>';
                  #echo'<pre>  result_menu_detail=>';print_r($result_menu_detail);echo'<pre>';
             } 
          foreach($result_menu_detail as $menu_detail){
              $explode_menu_detail_user = explode(',', $menu_detail->menu_alt);                
              $isUserAccess = false;
              foreach($explode_menu_detail_user as $menu_detail_user){
                  if($menu_detail_user == $requests['user_type_id'] || !$menu_detail_user){
                      $isUserAccess = true;
                  }
              }
              if($isUserAccess){
                  $data[] = $menu_detail;
              }
          }
      }        
      
      // var_dump($data); die;
      return $data;
  }
public function getUserTypeAccessMenuold($requests = [], $groups = [], $selects = []){
      $table = 'sd_user_access_menu uam';
      $filters['1'] = 1;
      $data = [];
      if(isset($requests['user_type_id']) && $requests['user_type_id']){
          if($requests['user_type_id'] == 1 || $requests['user_type_id'] == 2){
              $this->db->group_by('uam.menu_id');
          }else{
              $filters['uam.user_type_id'] = $requests['user_type_id'];
          }         
      }
      if(isset($requests['status']) && $requests['status']){
          $filters['um.status'] = $requests['status'];
      }
      $selects = !empty($selects) ? $selects : [
          'uam.*'
          ,'um.title'
          ,'um.url'
      ];
      
      $this->db->select($selects);
      $this->db->join('sd_user_menu um', 'uam.menu_id = um.menu_id');        
      $this->db->group_by($groups);
      $this->db->where($filters);
      $this->db->or_where('uam.user_type_id', 0);
      $query = $this->db->get($table);
      $result_menus_access = $query->result_array();
      // var_dump($filters);
      // var_dump($this->db->last_query()); die;
      if(!empty($result_menus_access)){
          $result_menu_id = array_column($result_menus_access, 'menu_id');
          $implode_result_menu_id1 = implode(',', $result_menu_id);
          $implode_result_menu_id='0,'.$implode_result_menu_id1;
          
          $query = $this->db->where_in('parent', $result_menu_id)
              ->get('sd_user_menu');
          $result_menu_detail = $query->result();
           /*

              $this->db->cache_off();
              $this->db->select('*');
              $this->db->from('sd_user_menu'); 
              //$this->db->where_in('menu_id',$result_menu_id);
              $this->db->where_in('parent',$result_menu_id);
              $this->db->or_where('parent',0);
              $query_get=$this->db->get();
              $num=$query_get->num_rows();
              $result_menu_detail=$query_get->result(); 
              $sql_last_query=$this->db->last_query();
           */
          // var_dump($query->result()); die;
          foreach($result_menu_detail as $menu_detail){
              $explode_menu_detail_user = explode(',', $menu_detail->menu_alt);                
              $isUserAccess = false;
              foreach($explode_menu_detail_user as $menu_detail_user){
                  if($menu_detail_user == $requests['user_type_id'] || !$menu_detail_user){
                      $isUserAccess = true;
                  }
              }
              if($isUserAccess){
                  $data[] = $menu_detail;
              }
          }
      }        
      
      // var_dump($data); die;
      return $data;
  }
public function usertypeaccessmenu($type,$option='',$debug='',$deletekey=''){
      ##Cach Toools Start######
      $cachekey="key-user-typeaccessmenu-type-".$type;
      $cachetime=60*60*60;
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
                  $result_menus_access=$rs;
            $cache_msg='Form Cache type file';
      }elseif($cachechklist==null){
            ################################ SQL  #################
            $this->db->cache_off();
            $this->db->select('access.user_access_id,access.user_type_id,menu.*');
            $this->db->from('sd_user_access_menu as access');
            $this->db->join('sd_user_menu as menu', 'menu.menu_id=access.menu_id');
            $this->db->where('access.user_type_id',$type); 
            $this->db->where('menu.status',1); 
             if($option!=null){$this->db->where('menu.option',$option); }
             $this->db->order_by('menu.menu_id','asc');
             $query_get=$this->db->get();
             $last_query=$this->db->last_query();
             if($debug==1){ 
                  echo'<hr><pre> last_query main menu =>';print_r($last_query);echo'<pre>';  
                  #echo'<pre> result_menus_access=>';print_r($result_menus_access);echo'<pre>';  
             }
             $result_menus_access=$query_get->result(); 
             $num=$query_get->num_rows();
            #echo'<hr><pre>   last_query=>';print_r($last_query);echo'<pre>';echo'<hr><pre>  result_menus_alt=>';print_r($result_menus_alt);echo'<pre>';  Die();
            $dataresult=$result_menus_access;
            ################################ SQL  #################
            $this->load->model('Cachtool_model');
            $sql=null;
            $cacheset=$this->Cachtool_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey);
            //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
            $result_menus_access=$cacheset['list'];
            $num=count($result_menus_access);
            $cache_msg=$cacheset['message'];
      }
      ##Cach Toools END######
      
      if($result_menus_access==null){
            $result_menus_access=null;
            }elseif($result_menus_access!=null){
                  if(is_array($result_menus_access)){
                        $acc_arr= array(); 
                        foreach($result_menus_access as $k=> $vl){
                                    $menu_id=$vl->menu_id;
                                    $arrss['data']['menu_id']=$menu_id;
                                    $acc_arr[]=$arrss['data']['menu_id'];
                              }
                        } 
                        $implode_result_menu_id1 = implode(',', $acc_arr);
                        #echo'<pre> implode_result_menu_id1=>';print_r($implode_result_menu_id1);
                        #####################################
                              ##Cach Toools Start######
                              $cachekey="key-user-typeaccessmenu-sub2-type-".$type;
                              $cachetime=60*60*60;
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
                                          $result_menus_parent=$rs;
                                    $cache_msg='Form Cache type file';
                              }elseif($cachechklist==null){
                                    ################################ SQL  #################
                                    $this->db->cache_off();
                                    $this->db->select('menu.*');
                                    $this->db->from('sd_user_menu as menu');
                                    $explode_result_menu_id1=explode(',', $implode_result_menu_id1);
                                    $this->db->where_in('menu.parent',$explode_result_menu_id1);
                                    $this->db->order_by('menu.menu_id','asc');
                                    $query_get=$this->db->get();
                                    $last_query_parent=$this->db->last_query();
                                    if($debug==1){ 
                                          echo'<hr><pre> last_query_parent 1=>';print_r($last_query_parent);echo'<pre><hr>'; 
                                    }
                                    $result_menus_parent=$query_get->result(); 
                                    $num=$query_get->num_rows();
                                    $dataresult=$result_menus_parent;
                                    ################################ SQL  #################
                                    $this->load->model('Cachtool_model');
                                    $sql=null;
                                    $cacheset=$this->Cachtool_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey);
                                    //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
                                    $result_menus_access=$cacheset['list'];
                                    $num=count($result_menus_access);
                                    $cache_msg=$cacheset['message'];
                              }
                              ##Cach Toools END######
                        #########################################
                        if(is_array($result_menus_parent)){
                              $accarr= array(); 
                              foreach($result_menus_parent as $k=> $v){
                                          $menuid=$v->menu_id;
                                          $arrs['var']['menu_id']=$menuid;
                                          $accarr[]=$arrs['var']['menu_id'];
                                    }
                              } 
                  if($result_menus_parent==null){$implode_result_menu_id2=null;}else{$implode_result_menu_id2 = implode(',', $accarr);}
                  if($implode_result_menu_id2==null){
                        $implode_result_menu_id_all=$implode_result_menu_id1;
                  }else{
                        $implode_result_menu_id_all=$implode_result_menu_id1.','.$implode_result_menu_id2;
                  }
                  $explode_result_menu_id_all=explode(',', $implode_result_menu_id_all);
                  if($debug==1){ 
                        echo'<pre> implode_result_menu_id1=>';print_r($implode_result_menu_id1);echo'<pre>'; 
                        echo'<pre> implode_result_menu_id2=>';print_r($implode_result_menu_id2);echo'<pre>'; 
                        echo'<pre> implode_result_menu_id_all=>';print_r($implode_result_menu_id_all);echo'<pre>';   
                        echo'<pre> explode_result_menu_id_all=>';print_r($explode_result_menu_id_all);echo'<pre>';   
                  } 
                  $this->db->cache_off();
                  $this->db->select('*');
                  $this->db->from('sd_user_menu');  
                  $this->db->or_where_in('menu_id',$explode_result_menu_id_all);
                  /*
                   ###############################
                  $this->db->where_in('parent',$implode_result_menu_id1);
                  $this->db->or_where_in('menu_id',$implode_result_menu_id2);
                  $this->db->where('parent',0);
                  */
                   ###############################
                  $this->db->order_by('menu_id','asc');
                  $query_get=$this->db->get();
                  $num=$query_get->num_rows();
                  $last_query_all=$this->db->last_query();
                  $result_menu_detail=$query_get->result(); 
                  if($debug==1){ 
                        echo'<pre> last_query_all=>';print_r($last_query_all);echo'<pre>'; 
                        #echo'<pre> result_menu_detail=>';print_r($result_menu_detail);echo'<pre>';  
                  }  
              $data=$result_menu_detail;
            }else{
              $data=null;
            }   
      return $data;
  }

public function usertypeaccessmenualt($type,$option='',$debug='',$deletekey=''){
    ##Cach Toools Start######
    $cachekey="key-usertypeaccessmenualt-type-".$type;
    $cachetime=60*60*60;
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
                  $dataall=$rs;
            $cache_msg='Form Cache type file';
      }elseif($cachechklist==null){
            ################################ SQL  #################
            $this->db->cache_off();
            $this->db->select('menu.*');
            $this->db->from('sd_user_menu as menu', 'menu.menu_id=access.menu_id');
            $this->db->where('menu.status',1); 
            $this->db->where('menu.menu_alt!=""');
             if($option!=null){$this->db->where('menu.option',$option); }
             $this->db->order_by('menu.menu_id','asc');
             $query_get=$this->db->get();
             $last_query=$this->db->last_query();
             $result_menus_alt=$query_get->result(); 
             $num=$query_get->num_rows();
             #echo'<hr><pre>   last_query=>';print_r($last_query);echo'<pre>';echo'<hr><pre>  result_menus_alt=>';print_r($result_menus_alt);echo'<pre>';  Die();
             $dataresult=$result_menus_alt;
            ################################ SQL  #################
            $this->load->model('Cachtool_model');
            $sql=null;
            $cacheset=$this->Cachtool_model->cachedbsetkey($sql,$dataresult,$cachekey,$cachetime,$cachetype,$deletekey);
            //echo'<hr><pre>  $cacheset=>';print_r($cacheset);echo'<pre> <hr>';die();
            $dataall=$cacheset['list'];
            $num=count($dataall);
            $cache_msg=$cacheset['message'];
      }
      ##Cach Toools END######
       return $dataall;
   }
 ##################END########### 
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