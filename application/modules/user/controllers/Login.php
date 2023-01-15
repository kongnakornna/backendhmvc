<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller{ 
function __construct(){
        // Construct the parent class
        parent::__construct();
        date_default_timezone_set('Asia/Bangkok');
        $this->load->model('Useraccess_model','User_model');
        $this->load->model('Useraccess_model','model_user');
        $this->db->cache_off();
        $this->load->helper('cookie', 'session');
        // Load form helper library
        $this->load->helper('form');
        // Load form validation library
        $this->load->library('form_validation');
        // Load session library
        $this->load->library('session');
    }
########################################################
public function index(){
      $this->load->library('Accessuser_library');  
      $session_cookie_get=$this->accessuser_library->session_cookie_get();
      $SESSION=@$session_cookie_get['SESSION'];
      $SESSION_user_id=@$SESSION['user_id'];
            if($SESSION_user_id==null){ 
                  $this->load->view('login-official'); 
            }else{ 
                  $urldirec=base_url('user/dashboard');
                  redirect($urldirec);die(); 
            } 
      }
public function  userschoolmap($user_id){
        $item_parent_id=@$wherearray['item_parent_id'];
        $this->db->cache_off();
        $this->db->select('user.user_idx,user.user_id,user.user_type_id,user.salutation,user.name,user.surname,user.position,user.company,user.company_group,user.email');
        $this->db->select('usertype.user_type_title as user_type');
        $this->db->select('master.school_code');
        $this->db->select('master.school_name');
        $this->db->select('master.school_name');
        $this->db->select('master.address');
        $this->db->select('master.address_moo');
        $this->db->select('master.address_street');
        $this->db->select('master.distric');
        $this->db->select('master.city');
        $this->db->select('master.province');
        $this->db->select('master.area');
        $this->db->select('master.post_code');
        $this->db->select('master.school_grade');
        $this->db->from('tbl_user_2018 as user ');
        $this->db->join('tbl_user_school_map as map', 'user.user_id=map.user_id', 'left'); 
        $this->db->join('tbl_school_master as master', 'master.school_code=map.school_code', 'left');   
        $this->db->join('sd_user_type as usertype', 'usertype.user_type_id=user.user_type_id', 'left');
        $wherearray = array('user.user_id' => $user_id);
        $query_get=$this->db->get();
        $num=$query_get->num_rows();
        $query_result=$query_get->result(); 
        $sql_last_query=$this->db->last_query();

    #echo'<hr><pre>  num=>';print_r($num);echo'</pre>';  echo'<hr><pre>  sql_last_query=>';print_r($sql_last_query);echo'</pre>'; echo'<hr><pre>  query_result=>';print_r($query_result);echo'<pre>'; echo'<hr><pre>  count_rows=>';print_r($count_rows);echo'<pre>';  die();
            if($num>=1){return $query_result['0']; }else{return null;}
    }
public function singin(){
        $input=@$this->input->post(); 
        if($input==null){$input=@$this->input->get();   }
        $username=@$input['username'];
        $password=@$input['password'];
        //Security Class
        $username=$this->db->escape_str($username);
        $password=$this->db->escape_str($password);
        $username=$this->security->xss_clean($username);
        $password=$this->security->xss_clean($password);
        //Security Class
        if($username==null || $password==null){echo 0; die();} 
        if($username==null && $password==null){
            //echo 'username and  password is null ';Die();
            $urldirec=base_url('user/login');
            redirect($urldirec);die(); 
        }
        $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
        $this->db->from('tbl_user_2018');
        $this->db->join('sd_user_type','sd_user_type.user_type_id=tbl_user_2018.user_type_id');
        $this->db->where('tbl_user_2018.username',$username);
    # $this->db->where('tbl_user_2018.password',$password);
        ##############
        $passwordmd5=md5($password);
        $this->db->where('tbl_user_2018.password_encrypt',$passwordmd5);
        #echo'<hr><pre> passwordmd5=>';print_r($passwordmd5);echo'<pre>'; die();
        ###############
        #$this->db->or_where('tbl_user_2018.password_encrypt',$passwordmd5);
        $this->db->where('tbl_user_2018.user_status',1);
        $query_get=$this->db->get();
        $query_result=@$query_get->result();
        $query_results=@$query_result['0'];
        $dataresult=$query_results;
        $num=$query_get->num_rows();
        if($num==0){ echo 0; die();}
        $user_idx=@$query_results->user_idx;
        $user_id=@$query_results->user_id;
        
        #echo'<hr><pre> userschoolmap=>';print_r($userschoolmap);echo'</pre>';
        $this->load->model('api/Asmitem_model');
        $schoolmaster=@$this->Asmitem_model->school_master_school_code($user_id); 
        if($schoolmaster==null){
            $school_map=@$this->Asmitem_model->user_school_map($user_id); 
            #echo'<hr><pre> schoolmaster=>';print_r($schoolmaster);echo'</pre>';  echo'<hr><pre> school_map=>';print_r($school_map);echo'</pre>'; 
            $school_code=@$school_map->school_code;
            $school_name=@$school_map->school_name;   
            $user_id=@$school_map->user_id;
            $name=@$school_map->name; 
            $school_province=@$school_map->province;    
            
            }else{
                            $school_code=@$schoolmaster->school_code;
                            $school_name=@$schoolmaster->school_name;  
                            $school_province=@$schoolmaster->province;                  
            }
        $last_query=$this->db->last_query();
       /*
        echo $num; 
        echo'<hr><pre> query_results=>';print_r($query_results);echo'<pre>';
        echo'<hr><pre> dataresult=>';print_r($dataresult);echo'<pre>';
        echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>';
        Die();
        */
        # $user_idx_admin="4152";
        # $user_idx_admin2="4614";
        if($user_idx==null){
        $input=@$this->input->post(); //deletekey=>1 = delete
        if($input==null){$input=@$this->input->get();} $user_idx=@$input['user_idx'];
        }
        if($user_idx==null){
            $urldirec=base_url('user/login');
            /*
            echo ("<script LANGUAGE='JavaScript'>
                window.alert('Username หรือ password ผิด');
                window.location.href='$urldirec';
                </script>");
            die(); 
            */
            redirect($urldirec); 
            echo 'Error user_idx is null';Die();
        }
        $user_type_id=$dataresult->user_type_id;
        $time=60*60*60*24;
        //echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'<pre>';Die();
        #####################################################################
        $log_id=$dataresult->user_idx; 
        $this->load->model("Historylog_model"); 
        $datars=$this->Historylog_model->update_lastuser_login_log($log_id);
        #####################################################################
        $this->load->library('Accessuser_library');
        $session_cookie_get=$this->accessuser_library->session_cookie_get();
        $_COOKIE=$session_cookie_get['COOKIE'];
        $_SESSION=$session_cookie_get['SESSION'];
        $COOKIE=@$session_cookie_get['COOKIE'];
        $SESSION=@$session_cookie_get['SESSION'];
        if($_COOKIE!==null || $SESSION!==null){}
        #$this->load->library('Accessuser_library');
        #$session_cookie_get=$this->accessuser_library->session_cookie_get();
        #$COOKIE=@$session_cookie_get['COOKIE'];
        #$SESSION=@$session_cookie_get['SESSION'];
        /*
         
        $this->isAdmin=$is_Admin;
		$this->user_type_id=$user_type_id;
		$this->user_type_name=$user_type_name;
		$this->user_id=$userid;
		$this->user_idx=$user_idx;
		$this->username=$username;
		$this->user_type=$user_type_name;
		$this->user_code=$company_group;
		$this->user_fullname=$fullname;
        $this->user_company=$company_group;
        $this->school_code=$school_code;
        $this->school_name=$school_name;
        $this->school_province=$school_province;
        $this->position=$position;

        */
        $sesdata1=array('userid'=>$this->accessuser_library->encryptCookie($dataresult->user_id),
                        'user_code'=>$this->accessuser_library->encryptCookie($dataresult->company_group),
                        'company_group'=>$this->accessuser_library->encryptCookie($dataresult->company_group),
                        'user_id'=>$this->accessuser_library->encryptCookie($dataresult->user_id),
                        #'useridx'=>$this->accessuser_library->encryptCookie($dataresult->user_idx),
                        'user_idx'=>$this->accessuser_library->encryptCookie($dataresult->user_idx),
                        'uname'=>$this->accessuser_library->encryptCookie($dataresult->username),
                        #'user_name'=>$this->accessuser_library->encryptCookie($dataresult->username),
                        'fullname'=>$this->accessuser_library->encryptCookie($dataresult->name.' '.$dataresult->surname),
                        'position'=>$this->accessuser_library->encryptCookie($dataresult->position),
                        'user_type_id'=>$this->accessuser_library->encryptCookie($dataresult->user_type_id),
                        #'user_type_name'=>$this->accessuser_library->encryptCookie($dataresult->user_type_title),
                        #'subtype'=>$this->accessuser_library->encryptCookie($dataresult->user_type_title),
                        #'utype'=>$this->accessuser_library->encryptCookie($dataresult->user_type_title),
                        #'user_status'=>$this->accessuser_library->encryptCookie($dataresult->user_status),
                        'school_code'=>$this->accessuser_library->encryptCookie($school_code),
                        'school_name'=>$this->accessuser_library->encryptCookie($school_name),
                        'school_province'=>$this->accessuser_library->encryptCookie($school_province),
                        #'domain'=>base_url(),
                    );
        $sesdata2=array('userid'=>$dataresult->user_id,
                        'user_code'=>$dataresult->company_group,
                        'company_group'=>$dataresult->company_group,
                        'user_id'=>$dataresult->user_id,
                        #'useridx'=>$dataresult->user_idx,
                        'user_idx'=>$dataresult->user_idx,
                        'uname'=>$dataresult->username,
                        #'user_name'=>$dataresult->username,
                        'fullname'=>$dataresult->name.' '.$dataresult->surname,
                        'position'=>$dataresult->position,
                        'user_type_id'=>$dataresult->user_type_id,
                        #'user_type_name'=>$dataresult->user_type_title,
                        #'subtype'=>$dataresult->user_type_title,
                        #'utype'=>$dataresult->user_type_title,
                        #'user_status'=>$dataresult->user_status,
                        'school_code'=>$school_code,
                        'school_name'=>$school_name,
                        'school_province'=>$school_province,
                        #'domain'=>base_url(),
                );
        

        $this->config->load('encryptkey'); 
        $setoption=$this->config->item('setoption');  
        if($setoption==0){$sesdata=$sesdata2;}else{$sesdata=$sesdata1;}
        #echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'<pre>';Die();
        $this->accessuser_library->session_set($sesdata);
        #$this->accessuser_library->setDataCookie('userid',$sesdata['user_id'],$time); 
      $this->accessuser_library->setDataCookie('user_code',$sesdata['user_code'],$time);
      $this->accessuser_library->setDataCookie('company_group',$sesdata['company_group'],$time);
        $this->accessuser_library->setDataCookie('user_id',$sesdata['user_id'],$time);
        #$this->accessuser_library->setDataCookie('useridx',$sesdata['user_idx'],$time);
        $this->accessuser_library->setDataCookie('user_idx',$sesdata['user_idx'],$time);
        //$this->accessuser_library->setDataCookie('uname',$sesdata['uname'],$time);
        #$this->accessuser_library->setDataCookie('user_name',$sesdata['uname'],$time);
        //$this->accessuser_library->setDataCookie('fullname',$sesdata['fullname'],$time);
        $this->accessuser_library->setDataCookie('user_type_id',$sesdata['user_type_id'],$time);
        #$this->accessuser_library->setDataCookie('user_type_name',$sesdata['user_type_name'],$time);
        #$this->accessuser_library->setDataCookie('utype',$sesdata['utype'],$time);
        #$this->accessuser_library->setDataCookie('subtype',$sesdata['subtype'],$time);
        #$this->accessuser_library->setDataCookie('user_status',$sesdata['user_status'],$time);
        //$this->accessuser_library->setDataCookie('school_code',$sesdata['school_code'],$time);
       // $this->accessuser_library->setDataCookie('school_name',$sesdata['school_name'],$time);
        #####################################################################
        ############### user Historylog login loggout insert  Start###################
        $code='200';
        $modules='User/Login';
        $process='Login';
        $datelogin=date('Y-m-d H:I:S');
        $message='User Login '.$datelogin;
        $insertdatalog=array('code'=> $code, // 200
            'modules'=>$modules, // ชื่อ  modules / function 
            'process'=>$process, // การเข้าไปดำเนินการ  เช่น เพิ่ม , ลบ,แก้ไข
            'message'=>$message, // อธิบายเพิ่ม การทำงาน
        );
        $this->load->model("Historylog_model"); 
        $datars=$this->Historylog_model->insert_sd_history_user_log($insertdatalog);
        ############### user Historylog login loggout insert  End ###################
        $this->load->model('connexted_model');
        $home_url_user = @$this->connexted_model->queryUserTypePermission(['user_type_id' => $user_type_id])->row()->home_url;
        $home_url_user = $home_url_user ? $home_url_user : "user/dashboard";
        $urldirec = base_url($home_url_user);    
        echo $urldirec; die();
    }
public function singinencrypt(){
        $input=@$this->input->post(); 
        if($input==null){$input=@$this->input->get();   }
        $username=@$input['username'];
        $password=@$input['password'];
        if($username==null || $password==null){
            echo 'username and password is null'; die();
      
       } 
        if($username==null && $password==null){
            //echo 'username and  password is null ';Die();
            $urldirec=base_url('user/login');
            redirect($urldirec);die(); 
        }
        $this->load->library('Accessuser2_library'); 
        $session_cookie_get=$this->accessuser2_library->session_cookie_get();
        $_COOKIE=$session_cookie_get['COOKIE'];
        $SESSION=$session_cookie_get['SESSION'];
        if($_COOKIE!==null || $SESSION!==null){}
        $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
        $this->db->from('tbl_user_2018');
        $this->db->join('sd_user_type','sd_user_type.user_type_id=tbl_user_2018.user_type_id');
        $this->db->where('tbl_user_2018.username',$username);
       # $this->db->where('tbl_user_2018.password',$password);
        ##############
         $passwordmd5=md5($password);
         $this->db->where('tbl_user_2018.password_encrypt',$passwordmd5);
        #echo'<hr><pre> passwordmd5=>';print_r($passwordmd5);echo'<pre>'; die();
        ###############
        #$this->db->or_where('tbl_user_2018.password_encrypt',$passwordmd5);
        $this->db->where('tbl_user_2018.user_status',1);
        $query_get=$this->db->get();
        $query_result=@$query_get->result();
        $query_results=@$query_result['0'];
        $dataresult=$query_results;
        $num=$query_get->num_rows();
        $user_idx=@$query_results->user_idx;
        $user_id=@$query_results->user_id;
        $last_query=$this->db->last_query();
        if($num==0){echo 0; die();}
     
       # echo $num; echo'<hr><pre> query_results=>';print_r($query_results);echo'<pre>';echo'<hr><pre> dataresult=>';print_r($dataresult);echo'<pre>';echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>'; Die();
      
        if($user_idx==null){
        $input=@$this->input->post(); //deletekey=>1 = delete
        if($input==null){$input=@$this->input->get();} $user_idx=@$input['user_idx'];
        }
        if($user_idx==null){
            $urldirec=base_url('user/login');
            /*
            echo ("<script LANGUAGE='JavaScript'>
                window.alert('Username หรือ password ผิด');
                window.location.href='$urldirec';
                </script>");
            die(); 
            */
            redirect($urldirec); 
            echo 'Error user_idx is null';Die();
        }
        $user_type_id=$dataresult->user_type_id;
        $time=60*60*60*24;
      #echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'<pre>';   
       $this->load->library('Accessuser2_library'); 
      $session_cookie_get=$this->accessuser2_library->session_cookie_get();
      $this->load->model('api/Asmitem_model');
      $user_id=$dataresult->user_id;
      $schoolmaster=@$this->Asmitem_model->school_master_school_code($user_id); 
      if($schoolmaster==null){
            $school_map=@$this->Asmitem_model->user_school_map($user_id); 
            #echo'<hr><pre> schoolmaster=>';print_r($schoolmaster);echo'</pre>';  echo'<hr><pre> school_map=>';print_r($school_map);echo'</pre>'; 
            $school_code=@$school_map->school_code;
            $school_name=@$school_map->school_name;   
            $user_id=@$school_map->user_id;
            $name=@$school_map->name; 
            $school_province=@$school_map->province;    
           $sesdata=array('userid'=>$dataresult->user_id,
                          'user_code'=>$dataresult->company_group,
                          'company_group'=>$dataresult->company_group,
                          'user_id'=>$dataresult->user_id,
                          'useridx'=>$dataresult->user_idx,
                          'user_idx'=>$dataresult->user_idx,
                          'uname'=>$dataresult->username,
                          'user_name'=>$dataresult->username,
                          'fullname'=>$dataresult->name.' '.$dataresult->surname,
                          'position'=>$dataresult->position,
                          'user_type_id'=>$dataresult->user_type_id,
                          'user_type_name'=>$dataresult->user_type_title,
                          'subtype'=>$dataresult->user_type_title,
                          'utype'=>$dataresult->user_type_title,
                          'user_status'=>$dataresult->user_status,
                          'school_code'=>$school_code,
                          'school_name'=>$school_name,
                          'school_province'=>$school_province,
                          'domain'=>base_url(),
                          );
                          #echo'<hr><pre>  sesdata1=>';print_r($sesdata);echo'</pre>';   
                 }else{
                          $school_code=@$schoolmaster->school_code;
                          $school_name=@$schoolmaster->school_name;  
                          $school_province=@$schoolmaster->province;   
                          #echo'<hr><pre> schoolmaster=>';print_r($schoolmaster);echo'</pre>';  
                                $sesdata=array('userid'=>$dataresult->user_id,
                                                  'user_code'=>$dataresult->company_group,
                                                  'company_group'=>$dataresult->company_group,
                                                  'user_id'=>$dataresult->user_id,
                                                  'useridx'=>$dataresult->user_idx,
                                                  'user_idx'=>$dataresult->user_idx,
                                                  'uname'=>$dataresult->username,
                                                  'user_name'=>$dataresult->username,
                                                  'fullname'=>$dataresult->name.' '.$dataresult->surname,
                                                  'position'=>$dataresult->position,
                                                  'user_type_id'=>$dataresult->user_type_id,
                                                  'user_type_name'=>$dataresult->user_type_title,
                                                  'subtype'=>$dataresult->user_type_title,
                                                  'utype'=>$dataresult->user_type_title,
                                                  'user_status'=>$dataresult->user_status,
                                                  'school_code'=>$school_code,
                                                  'school_name'=>$school_name,
                                                  'school_province'=>$school_province,
                                                  'domain'=>base_url(),
                                                  );
                                                  #echo'<hr><pre>  sesdata2=>';print_r($sesdata);echo'</pre>';   
        }
      #echo'<hr><pre>  school_map=>';print_r($school_map);echo'</pre>'; echo'<hr><pre> schoolmaster=>';print_r($schoolmaster);echo'</pre>';  die();
    
              #echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'</pre>';  die();
                # $this->accessuser_library->session_set($sesdata);
                 $sesdataCookie=array('userid'=>$this->accessuser2_library->encryptCookie($sesdata['userid']),
                              'user_code'=>$this->accessuser2_library->encryptCookie($sesdata['user_code']),
                              #'company_group'=>$this->accessuser2_library->encryptCookie($sesdata['company_group']),
                              'user_id'=>$this->accessuser2_library->encryptCookie($sesdata['user_id']),
                              'useridx'=>$this->accessuser2_library->encryptCookie($sesdata['useridx']),
                              'user_idx'=>$this->accessuser2_library->encryptCookie($sesdata['user_idx']),
                              #'uname'=>$this->accessuser2_library->encryptCookie($sesdata['uname']),
                              'user_name'=>$this->accessuser2_library->encryptCookie($sesdata['user_name']),
                              'fullname'=>$this->accessuser2_library->encryptCookie($sesdata['fullname']),
                              'position'=>$this->accessuser2_library->encryptCookie($sesdata['position']),
                              'user_type_id'=>$this->accessuser2_library->encryptCookie($sesdata['user_type_id']),
                              'user_type_name'=>$this->accessuser2_library->encryptCookie($sesdata['user_type_name']),
                              #'subtype'=>$this->accessuser2_library->encryptCookie($sesdata['subtype']),
                              #'utype'=>$this->accessuser2_library->encryptCookie($sesdata['utype']),
                              #'user_status'=>$this->accessuser2_library->encryptCookie($sesdata['user_status']),
                              'school_code'=>$this->accessuser2_library->encryptCookie($sesdata['school_code']),
                              'school_name'=>$this->accessuser2_library->encryptCookie($sesdata['school_name']),
                              'domain'=>base_url(),
                        );
                 $this->accessuser_library->sesdataCookie($sesdataCookie);
                  $this->accessuser2_library->setDataCookie('userid',$sesdataCookie['user_id'],$time); 
                  $this->accessuser2_library->setDataCookie('user_code',$sesdataCookie['user_code'],$time);
                  #$this->accessuser2_library->setDataCookie('company_group',$sesdataCookie['company_group'],$time);
                  $this->accessuser2_library->setDataCookie('user_id',$sesdataCookie['user_id'],$time);
                  $this->accessuser2_library->setDataCookie('useridx',$sesdataCookie['user_idx'],$time);
                  $this->accessuser2_library->setDataCookie('user_idx',$sesdataCookie['user_idx'],$time);
                  #$this->accessuser2_library->setDataCookie('uname',$sesdataCookie['uname'],$time);
                  $this->accessuser2_library->setDataCookie('user_name',$sesdataCookie['uname'],$time);
                  $this->accessuser2_library->setDataCookie('fullname',$sesdataCookie['fullname'],$time);
                  $this->accessuser2_library->setDataCookie('user_type_id',$sesdataCookie['user_type_id'],$time);
                  $this->accessuser2_library->setDataCookie('user_type_name',$sesdataCookie['user_type_name'],$time);
                  #$this->accessuser2_library->setDataCookie('utype',$sesdataCookie['utype'],$time);
                  #$this->accessuser2_library->setDataCookie('subtype',$sesdataCookie['subtype'],$time);
                  #$this->accessuser2_library->setDataCookie('user_status',$sesdataCookie['user_status'],$time);
                  $this->accessuser2_library->setDataCookie('position',$sesdataCookie['position'],$time);  
                  $this->accessuser2_library->setDataCookie('school_code',$sesdataCookie['school_code'],$time);  
                  $this->accessuser2_library->setDataCookie('school_name',$sesdataCookie['school_name'],$time);  
                   
          ############### user Historylog login loggout insert  Start###################
          $code='200';
          $modules='User/Login';
          $process='Login';
          $datelogin=date('Y-m-d H:I:S');
          $message='User Login '.$datelogin;
          $insertdatalog=array('code'=> $code, // 200
              'modules'=>$modules, // ชื่อ  modules / function 
              'process'=>$process, // การเข้าไปดำเนินการ  เช่น เพิ่ม , ลบ,แก้ไข
              'message'=>$message, // อธิบายเพิ่ม การทำงาน
          );
          $this->load->model("Historylog_model"); 
          $datars=$this->Historylog_model->insert_sd_history_user_log($insertdatalog);
          ############### user Historylog login loggout insert  End ###################
          $this->load->model('connexted_model');
          $home_url_user = @$this->connexted_model->queryUserTypePermission(['user_type_id' => $user_type_id])->row()->home_url;
          $home_url_user = $home_url_user ? $home_url_user : "user2/dashboard";
          $urldirec = base_url($home_url_user);   
          $rt=array('numrows'=>$num,'urldirec'=>$urldirec);
          echo $urldirec; die();
      }
 
public function summonsingin($id=''){
      $userid=$id;
      if($userid==null){
      ?> 
      <a href="#" onclick="alert('Error user_id is null');history.go(-1);return false">Back</a>
      <?php die();
            /*
                  $urldirec=base_url('user/login');
                  echo ("<script LANGUAGE='JavaScript'>
                  window.alert('Username หรือ password ผิด');
                  window.location.href='$urldirec';
                  </script>");
                  die(); 
            */
        }
      $this->load->library('Accessuser_library');
      $session_cookie_get=$this->accessuser_library->session_cookie_get();
      $_COOKIE=$session_cookie_get['COOKIE'];
      $SESSION=$session_cookie_get['SESSION'];
      if($_COOKIE!==null || $SESSION!==null){}
      $input=@$this->input->post(); 
      if($input==null){$input=@$this->input->get();   }
      $username=@$input['username'];
      $password=@$input['password'];       
      $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
      $this->db->from('tbl_user_2018');
      $this->db->join('sd_user_type','sd_user_type.user_type_id=tbl_user_2018.user_type_id');
      $this->db->or_where('tbl_user_2018.user_id',$userid);
      $this->db->where('tbl_user_2018.user_status',1);
      $query_get=$this->db->get();
      $query_result=@$query_get->result();
      $query_results=@$query_result['0'];
      $dataresult=$query_results;
      $num=$query_get->num_rows();
      if($num==0){ echo 0;  die();}
      $user_idx=@$query_results->user_idx;
      $user_id=@$query_results->user_id;
      $last_query=$this->db->last_query();
      if($num==0){
           # echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>';
            echo 0; 
            die();}
           /*
                  echo $num; 
                  echo'<hr><pre> query_results=>';print_r($query_results);echo'<pre>';
                  echo'<hr><pre> dataresult=>';print_r($dataresult);echo'<pre>';
                  echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>';
                  Die();
            */
      if($user_idx==null){
       $input=@$this->input->post(); //deletekey=>1 = delete
      if($input==null){$input=@$this->input->get();} $user_idx=@$input['user_idx'];}
      if($user_idx==null){ ?>
       <a href="#" onclick="alert('Error user_idx is null');history.go(-1);return false">Back</a> 
       <?php  die(); 
      }
      $user_type_id=$dataresult->user_type_id;
      $time=60*60*60*24;
      //echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'<pre>';Die();
      #####################################################################
      $this->load->library('Accessuser_library');
      $session_cookie_get=$this->accessuser_library->session_cookie_get();
      $COOKIE=@$session_cookie_get['COOKIE'];
      $SESSION=@$session_cookie_get['SESSION'];

      $this->load->model('api/Asmitem_model');
      $user_id=$dataresult->user_id;
      $schoolmaster=@$this->Asmitem_model->school_master_school_code($user_id); 
      if($schoolmaster==null){
            $school_map=@$this->Asmitem_model->user_school_map($user_id); 
            #echo'<hr><pre> schoolmaster=>';print_r($schoolmaster);echo'</pre>';  echo'<hr><pre> school_map=>';print_r($school_map);echo'</pre>'; 
            $school_code=@$school_map->school_code;
            $school_name=@$school_map->school_name;   
            $user_id=@$school_map->user_id;
            $name=@$school_map->name; 
            $school_province=@$school_map->province;    
           
          }else{
                          $school_code=@$schoolmaster->school_code;
                          $school_name=@$schoolmaster->school_name;  
                          $school_province=@$schoolmaster->province;                  
         }


                  $this->config->load('encryptkey'); 
                  $setoption=$this->config->item('setoption');  
                  if($setoption==0){                        
                        $sesdata=array('userid'=>$dataresult->user_id,
                                    'summon_user_code'=>$dataresult->company_group,
                                    'summon_company_group'=>$dataresult->company_group,
                                    'summon_user_id'=>$dataresult->user_id,
                                    #'summon_useridx'=>$dataresult->user_idx,
                                    'summon_user_idx'=>$dataresult->user_idx,
                                    'summon_uname'=>$dataresult->username,
                                    'summon_user_name'=>$dataresult->username,
                                    'summon_fullname'=>$dataresult->name.' '.$dataresult->surname,
                                    'summon_position'=>$dataresult->position,
                                    'summon_user_type_id'=>$dataresult->user_type_id,
                                    #'summon_user_type_name'=>$dataresult->user_type_title,
                                    #'summon_subtype'=>$dataresult->user_type_title,
                                    #'summon_utype'=>$dataresult->user_type_title,
                                    #'summon_user_status'=>$dataresult->user_status,
                                    'summon_school_code'=>$school_code,
                                    'summon_school_name'=>$school_name,
                                    'summon_domain'=>base_url(),
                                    );
                  
                  
                  }else{ 
                        //$this->accessuser_library->encryptCookie($cc),
                        $sesdata=array('userid'=>$this->accessuser_library->encryptCookie($dataresult->user_id),
                                          'summon_user_code'=>$this->accessuser_library->encryptCookie($dataresult->company_group),
                                          'summon_company_group'=>$this->accessuser_library->encryptCookie($dataresult->company_group),
                                          'summon_user_id'=>$this->accessuser_library->encryptCookie($dataresult->user_id),
                                          #'summon_useridx'=>$this->accessuser_library->encryptCookie($dataresult->user_idx),
                                          'summon_user_idx'=>$this->accessuser_library->encryptCookie($dataresult->user_idx),
                                          'summon_uname'=>$this->accessuser_library->encryptCookie($dataresult->username),
                                          'summon_user_name'=>$this->accessuser_library->encryptCookie($dataresult->username),
                                          'summon_fullname'=>$this->accessuser_library->encryptCookie($dataresult->name.' '.$dataresult->surname),
                                          'summon_position'=>$this->accessuser_library->encryptCookie($dataresult->position),
                                          'summon_user_type_id'=>$this->accessuser_library->encryptCookie($dataresult->user_type_id),
                                          #'summon_user_type_name'=>$this->accessuser_library->encryptCookie($dataresult->user_type_title),
                                          #'summon_subtype'=>$this->accessuser_library->encryptCookie($dataresult->user_type_title),
                                          #'summon_utype'=>$this->accessuser_library->encryptCookie($dataresult->user_type_title),
                                          #'summon_user_status'=>$this->accessuser_library->encryptCookie($dataresult->user_status),
                                          'summon_school_code'=>$this->accessuser_library->encryptCookie($school_code),
                                          'summon_school_name'=>$this->accessuser_library->encryptCookie($school_name),
                                          'summon_domain'=>base_url(),
                                          );
                  }

      //echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'<pre>';Die();
      $this->accessuser_library->session_set($sesdata);
      #$this->accessuser_library->setDataCookie('summon_userid',$sesdata['summon_user_id'],$time); 
      #$this->accessuser_library->setDataCookie('summon_user_code',$sesdata['summon_user_code'],$time);
      #$this->accessuser_library->setDataCookie('summon_company_group',$sesdata['summon_company_group'],$time);
      @$this->accessuser_library->setDataCookie('summon_user_id',$sesdata['summon_user_id'],$time);
      @$this->accessuser_library->setDataCookie('summon_useridx',$sesdata['summon_user_idx'],$time);
     # @$this->accessuser_library->setDataCookie('summon_user_idx',$sesdata['summon_user_idx'],$time);
     # @$this->accessuser_library->setDataCookie('summon_uname',$sesdata['summon_uname'],$time);
     # @$this->accessuser_library->setDataCookie('summon_user_name',$sesdata['summon_uname'],$time);
     # @$this->accessuser_library->setDataCookie('summon_fullname',$sesdata['summon_fullname'],$time);
      @$this->accessuser_library->setDataCookie('summon_user_type_id',$sesdata['summon_user_type_id'],$time);
      #$this->accessuser_library->setDataCookie('summon_user_type_name',$sesdata['summon_user_type_name'],$time);
      #$this->accessuser_library->setDataCookie('summon_utype',$sesdata['summon_utype'],$time);
      #$this->accessuser_library->setDataCookie('summon_subtype',$sesdata['summon_subtype'],$time);
      #$this->accessuser_library->setDataCookie('summon_user_status',$sesdata['summon_user_status'],$time);
      #####################################################################
     # @$this->accessuser_library->setDataCookie('summon_school_code',$sesdata['summon_school_code'],$time);
     # @$this->accessuser_library->setDataCookie('summon_school_name',$sesdata['summon_school_name'],$time);
	  
	 
          # settemp
         # $this->setsessionandcookie_temp();
          ##########################*************setsessionandcookie_temp*******##########################
          ###################################***temp***##################################
	$time=60*60*60*24;  
	$COOKIE_one=@$session_cookie_get['COOKIE'];
      $SESSION_one=@$session_cookie_get['SESSION'];
      $temp_sesdata=array('temp_userid'=>$SESSION_one['userid'],
                                      'temp_user_code'=>$SESSION_one['user_code'],
                                      'temp_company_group'=>$SESSION_one['company_group'],
                                      'temp_user_id'=>$SESSION_one['user_id'],
                                      #'temp_useridx'=>$SESSION_one['useridx'],
                                      'temp_user_idx'=>$SESSION_one['user_idx'],
                                      'temp_uname'=>$SESSION_one['uname'],
                                      'temp_user_name'=>$SESSION_one['user_name'],
                                      'temp_fullname'=>$SESSION_one['fullname'],
                                      'temp_position'=>$SESSION_one['position'],
                                      'temp_user_type_id'=>$SESSION_one['user_type_id'],
                                      'temp_user_type_name'=>$SESSION_one['user_type_name'],
                                      #'temp_subtype'=>$SESSION_one['subtype'],
                                      #'temp_utype'=>$SESSION_one['utype'],
                                      #'temp_user_status'=>$SESSION_one['user_status'],
                                      'temp_school_code'=>$SESSION_one['school_code'],
                                      'temp_school_name'=>$SESSION_one['school_name'],
                                      'temp_domain'=>base_url(),
                              );
         #echo'<hr><pre>  sesdata=>';print_r($temp_sesdata);echo'<pre>';Die();
              $this->accessuser_library->session_set($temp_sesdata);
             # @$this->accessuser_library->setDataCookie('temp_userid',$temp_sesdata['temp_user_id'],$time); 
              #$this->accessuser_library->setDataCookie('temp_user_code',$temp_sesdata['temp_user_code'],$time);
              #$this->accessuser_library->setDataCookie('temp_company_group',$temp_sesdata['temp_company_group'],$time);
              @$this->accessuser_library->setDataCookie('temp_user_id',$temp_sesdata['temp_user_id'],$time);
              @$this->accessuser_library->setDataCookie('temp_useridx',$temp_sesdata['temp_user_idx'],$time);
              #@$this->accessuser_library->setDataCookie('temp_user_idx',$temp_sesdata['temp_user_idx'],$time);
              #@$this->accessuser_library->setDataCookie('temp_uname',$temp_sesdata['temp_uname'],$time);
             # @$this->accessuser_library->setDataCookie('temp_user_name',$temp_sesdata['temp_uname'],$time);
              #@$this->accessuser_library->setDataCookie('temp_fullname',$temp_sesdata['temp_fullname'],$time);
              @$this->accessuser_library->setDataCookie('temp_user_type_id',$temp_sesdata['temp_user_type_id'],$time);
             # @$this->accessuser_library->setDataCookie('temp_user_type_name',$temp_sesdata['temp_user_type_name'],$time);
              #$this->accessuser_library->setDataCookie('temp_utype',$temp_sesdata['temp_utype'],$time);
              #$this->accessuser_library->setDataCookie('temp_subtype',$temp_sesdata['temp_subtype'],$time);
              #$this->accessuser_library->setDataCookie('temp_user_status',$temp_sesdata['temp_user_status'],$time);
             # @$this->accessuser_library->setDataCookie('temp_school_code',$temp_sesdata['temp_school_code'],$time);
             # @$this->accessuser_library->setDataCookie('temp_school_name',$temp_sesdata['temp_school_name'],$time);
      
      #####################################################################
      
                  if($setoption==0){                        
                        $sesdata=array('userid'=>$dataresult->user_id,
                                    'user_code'=>$dataresult->company_group,
                                    'company_group'=>$dataresult->company_group,
                                    'user_id'=>$dataresult->user_id,
                                    'useridx'=>$dataresult->user_idx,
                                    'user_idx'=>$dataresult->user_idx,
                                    'uname'=>$dataresult->username,
                                    'user_name'=>$dataresult->username,
                                    'fullname'=>$dataresult->name.' '.$dataresult->surname,
                                    'position'=>$dataresult->position,
                                    'user_type_id'=>$dataresult->user_type_id,
                                    #'user_type_name'=>$dataresult->user_type_title,
                                    #'subtype'=>$dataresult->user_type_title,
                                    #'utype'=>$dataresult->user_type_title,
                                    #'user_status'=>$dataresult->user_status,
                                    'school_code'=>$school_code,
                                    'school_name'=>$school_name,
                                    'domain'=>base_url(),
                                    );
                  }else{ 
                        //$this->accessuser_library->encryptCookie($cc), 
                        $sesdata=array('userid'=>$this->accessuser_library->encryptCookie($dataresult->user_id),
                                    'user_code'=>$this->accessuser_library->encryptCookie($dataresult->company_group),
                                    'company_group'=>$this->accessuser_library->encryptCookie($dataresult->company_group),
                                    'user_id'=>$this->accessuser_library->encryptCookie($dataresult->user_id),
                                    'useridx'=>$this->accessuser_library->encryptCookie($dataresult->user_idx),
                                    'user_idx'=>$this->accessuser_library->encryptCookie($dataresult->user_idx),
                                    'uname'=>$this->accessuser_library->encryptCookie($dataresult->username),
                                    'user_name'=>$this->accessuser_library->encryptCookie($dataresult->username),
                                    'fullname'=>$this->accessuser_library->encryptCookie($dataresult->name.' '.$dataresult->surname),
                                    'position'=>$this->accessuser_library->encryptCookie($dataresult->position),
                                    'user_type_id'=>$this->accessuser_library->encryptCookie($dataresult->user_type_id),
                                    #'user_type_name'=>$this->accessuser_library->encryptCookie($dataresult->user_type_title),
                                    #'subtype'=>$this->accessuser_library->encryptCookie($dataresult->user_type_title),
                                    #'utype'=>$this->accessuser_library->encryptCookie($dataresult->user_type_title),
                                    #'user_status'=>$this->accessuser_library->encryptCookie($dataresult->user_status),
                                    'school_code'=>$this->accessuser_library->encryptCookie($school_code),
                                    'school_name'=>$this->accessuser_library->encryptCookie($school_name),
                                    'domain'=>base_url(),
                                    );
                  }

                  //echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'<pre>';Die();
                  $this->accessuser_library->session_set($sesdata);
                  #@$this->accessuser_library->setDataCookie('userid',$sesdata['user_id'],$time); 
                  #@$this->accessuser_library->setDataCookie('user_code',$sesdata['user_code'],$time);
                  #@$this->accessuser_library->setDataCookie('company_group',$sesdata['company_group'],$time);
                  @$this->accessuser_library->setDataCookie('user_id',$sesdata['user_id'],$time);
                  #@$this->accessuser_library->setDataCookie('useridx',$sesdata['user_idx'],$time);
                  @$this->accessuser_library->setDataCookie('user_idx',$sesdata['user_idx'],$time);
                  #$this->accessuser_library->setDataCookie('uname',$sesdata['uname'],$time);
                  #@$this->accessuser_library->setDataCookie('user_name',$sesdata['uname'],$time);
                  #@$this->accessuser_library->setDataCookie('fullname',$sesdata['fullname'],$time);
                  @$this->accessuser_library->setDataCookie('user_type_id',$sesdata['user_type_id'],$time);
                  #$this->accessuser_library->setDataCookie('user_type_name',$sesdata['user_type_name'],$time);
                  #$this->accessuser_library->setDataCookie('utype',$sesdata['utype'],$time);
                  #$this->accessuser_library->setDataCookie('subtype',$sesdata['subtype'],$time);
                  #$this->accessuser_library->setDataCookie('user_status',$sesdata['user_status'],$time);
                 # @$this->accessuser_library->setDataCookie('school_code',$sesdata['school_code'],$time);
                 # @$this->accessuser_library->setDataCookie('school_name',$sesdata['school_name'],$time);
      #####################################################################
      /*
      $this->load->model('api/Asmitem_model');
        $user_id=$dataresult->user_id;
        $schoolmaster=@$this->Asmitem_model->school_master_school_code($user_id); 
        if($schoolmaster==null){
              $school_map=@$this->Asmitem_model->user_school_map($user_id); s
              $school_code=@$school_map->school_code;
              $school_name=@$school_map->school_name;
              $user_id=@$school_map->user_id;
              $name=@$school_map->name; 
              $school_province=@$school_map->province;             
            }else{
                            $school_code=@$schoolmaster->school_code;
                            $school_name=@$schoolmaster->school_name;  
                            $school_province=@$schoolmaster->province;                  
           }
      */
      #####################################################################
      ############### user Historylog login loggout insert  Start###################
      $code='200';
      $modules='Login Summon ';
      $process='Login Summon user_id '.$sesdata['summon_user_id'].' summon_fullname '.$sesdata['summon_fullname'];
      $datelogin=date('Y-m-d H:I:S');
      $message='User Login Summon  user_id'.$sesdata['summon_user_id'].' summon_fullname '.$sesdata['summon_fullname'].' '.$datelogin;
      $insertdatalog=array('code'=> $code, // 200
          'modules'=>$modules, // ชื่อ  modules / function 
          'process'=>$process, // การเข้าไปดำเนินการ  เช่น เพิ่ม , ลบ,แก้ไข
          'message'=>$message, // อธิบายเพิ่ม การทำงาน
      );
      $this->load->model("Historylog_model"); 
      $datars=$this->Historylog_model->insert_sd_history_user_log($insertdatalog);
      ############### user Historylog login loggout insert  End ###################
      $this->load->model('connexted_model');
      $home_url_user = @$this->connexted_model->queryUserTypePermission(['user_type_id' => $user_type_id])->row()->home_url;
      $home_url_user = $home_url_user ? $home_url_user : "user/dashboard";
      $urldirecac = base_url($home_url_user);   
      $rt=array('numrows'=>$num,'urldirec'=>$urldirecac);
      #echo $urldirecac; 
      $urldirec=base_url('user/dashboard');
      redirect($urldirec); 
      Die();
  }
public function summonsingout($usertypeid=''){
      $time=60*60*60*24;  
      $this->load->library('Accessuser_library');
      $session_cookie_get=$this->accessuser_library->session_cookie_get();
      $_COOKIE=$session_cookie_get['COOKIE'];
      $SESSION=$session_cookie_get['SESSION'];
            /*
            echo'<hr><pre> SESSION=>';print_r($SESSION);echo'<pre>';
            echo'<hr><pre> COOKIE=>';print_r($_COOKIE);echo'<pre>'; die();
            */
            /*
             COOKIE=>Array(
                  [pracharathschool_session_] => c9519l52gc86j2sctj11rgtl9hda119f
                  [summon_user_id] => companybbl
                  [summon_useridx] => 4623
                  [summon_user_type_id] => 5
                  [temp_user_id] => 59000000
                  [temp_useridx] => 4152
                  [temp_user_type_id] => 1
                  [user_id] => companybbl
                  [user_idx] => 4623
                  [user_type_id] => 5
                  )
             */
            ##########################################################
            //$user_code=@$_COOKIE['summon_user_code'];
            #$company_group=@$_COOKIE['summon_company_group'];
            //$cookie_userid=@$_COOKIE['summon_userid'];
            $cookie_user_id=@$_COOKIE['summon_user_id'];
            $cookie_useridx=@$_COOKIE['summon_useridx'];
            $cookie_user_type_id=@$_COOKIE['summon_user_type_id'];
            $this->accessuser_library->deleteCookie('summon_user_id',$cookie_user_id);
            $this->accessuser_library->deleteCookie('summon_useridx',$cookie_useridx);
            $this->accessuser_library->deleteCookie('summon_user_type_id',$cookie_user_type_id);

            /*
            $cookie_user_idx=@$_COOKIE['summon_user_idx'];
            #$cookie_uname=@$_COOKIE['summon_uname'];
            $cookie_user_name=@$_COOKIE['summon_user_name'];
            $cookie_fullname=@$_COOKIE['summon_fullname'];
           
            #$cookie_user_type_name=@$_COOKIE['summon_user_type_name'];
            #$cookie_utype=@$_COOKIE['summon_utype'];
            $cookie_user_type_name=@$_COOKIE['summon_user_type_name'];
            #$cookie_subtype=@$_COOKIE['summon_subtype'];
            #$cookie_user_status=@$_COOKIE['summon_user_status'];

            $cookie_summon_school_code=@$_COOKIE['summon_school_code'];
            $cookie_summon_school_name=@$_COOKIE['summon_school_name'];
            
            $this->accessuser_library->deleteCookie('summon_user_code',$user_code);
            #$this->accessuser_library->deleteCookie('summon_company_group',$company_group);
            $this->accessuser_library->deleteCookie('summon_userid',$cookie_userid);
           
            
            //$this->accessuser_library->deleteCookie('summon_user_idx',$cookie_user_idx);
            //$this->accessuser_library->deleteCookie('summon_uname',$cookie_uname);
            //$this->accessuser_library->deleteCookie('summon_user_name',$cookie_user_name);
            //$this->accessuser_library->deleteCookie('summon_fullname',$cookie_fullname);
            
            #$this->accessuser_library->deleteCookie('summon_user_type_name',$cookie_user_type_name);
            #$this->accessuser_library->deleteCookie('summon_utype',$cookie_utype);
            #$this->accessuser_library->deleteCookie('summon_subtype',$cookie_subtype);
            #$this->accessuser_library->deleteCookie('summon_user_status',$cookie_user_status);
            //$this->accessuser_library->deleteCookie('summon_school_code',$cookie_summon_school_code);
            //$this->accessuser_library->deleteCookie('summon_school_name',$cookie_summon_school_name);
            //session_destroy();
            #$this->accessuser_library->sessdestroy();
            ##########################################################
            */
             
      $this->load->library('Accessuser_library');
      $session_cookie_get=$this->accessuser_library->session_cookie_get();
      $COOKIE=@$session_cookie_get['COOKIE'];
      $SESSION=@$session_cookie_get['SESSION'];
      $summon_user_type_id=@$SESSION['summon_user_type_id'];
      $user_type_id=$summon_user_type_id;
      if($user_type_id==null){$summon_user_type_id=@$COOKIE['summon_user_type_id'];}
      #######################
      $summon_userid=@$SESSION['summon_userid'];
      if($summon_userid==null){$summon_userid=@$COOKIE['summon_userid']; } 
      #######################
      $summon_user_idx=@$SESSION['summon_user_idx'];
      if($summon_user_idx==null){$summon_userid=@$COOKIE['summon_user_idx'];}  
      

      ############### user Historylog insert Start###################
      $session=$this->load->library('session');
      $summon_uname=$SESSION['summon_user_id'];
      $summon_utype=$SESSION['summon_user_type_id'];
      $summon_user_id=$SESSION['summon_user_id'];
      $summon_user_type=$summon_user_type_id;
      $code='200';
      $modules='Logout Summon';
      $process='Logout Summon user_id '.$summon_user_id.' user '.$summon_uname.' summon_fullname '.$cookie_fullname;
      $datelogin=date('Y-m-d H:I:S');
   
      $message='Logout Summon user_id '.$summon_user_id.' user '.$summon_uname.' summon_fullname '.$cookie_fullname.' user type '.$summon_utype.' Logout Summon '.$datelogin;
      $insertdatalog=array('user_id'=>$summon_user_id,
                        'user_type'=>$summon_user_type,
                        'code'=> $code,
                        'modules'=>$modules,
                        'process'=>$process,
                        'message'=>$message,
                  );
      $this->load->model("Historylog_model"); 
      $datars=$this->Historylog_model->insert_sd_history_user_log($insertdatalog);
      ############### user Historylog insert  End ###################
            $this->session->unset_userdata('userid');
            $this->session->unset_userdata('summon_user_code');
            $this->session->unset_userdata('summon_company_group');
            $this->session->unset_userdata('summon_useridx');
            $this->session->unset_userdata('summon_uname');
            $this->session->unset_userdata('summon_user_name');
            $this->session->unset_userdata('summon_fullname');
            $this->session->unset_userdata('summon_user_type_id');
            #$this->session->unset_userdata('summon_user_type_name');
            #$this->session->unset_userdata('summon_subtype');
            #$this->session->unset_userdata('summon_utype');
            #$this->session->unset_userdata('summon_user_status');
            $this->session->unset_userdata('summon_domain');
            $this->session->unset_userdata('summon_user_id');
            $this->session->unset_userdata('summon_user_idx');
            $this->session->unset_userdata('summon_position');
            $this->session->unset_userdata('summon_school_code');
            $this->session->unset_userdata('summon_school_name');
            #echo'<hr><pre> SESSION=>';print_r($SESSION);echo'<pre>';
            #echo'<hr><pre> COOKIE=>';print_r($_COOKIE);echo'<pre>'; die();
		#########################################
				$this->session->unset_userdata('temp_userid');
				#$this->session->unset_userdata('temp_user_code');
				#$this->session->unset_userdata('temp_company_group');
				$this->session->unset_userdata('temp_useridx');
				#$this->session->unset_userdata('temp_uname');
				$this->session->unset_userdata('temp_user_name');
				$this->session->unset_userdata('temp_fullname');
				$this->session->unset_userdata('temp_user_type_id');
				$this->session->unset_userdata('temp_user_type_name');
				#$this->session->unset_userdata('temp_subtype');
				#$this->session->unset_userdata('temp_utype');
				#$this->session->unset_userdata('temp_user_status');
				#$this->session->unset_userdata('temp_domain');
				$this->session->unset_userdata('temp_user_id');
				$this->session->unset_userdata('temp_user_idx');
                        $this->session->unset_userdata('temp_position');
                        $this->session->unset_userdata('temp_school_code');
                        $this->session->unset_userdata('temp_school_name');
                        #########################################



                        $temp_userid=$SESSION['temp_userid'];
                        $company_group=$SESSION['company_group'];
                        $temp_user_id=$SESSION['temp_user_id'];
                        $temp_user_idx=$SESSION['temp_user_idx'];
                        $temp_uname=$SESSION['temp_uname'];
                        $temp_fullname=$SESSION['temp_fullname'];
                        $position=$SESSION['temp_position'];
                        $temp_user_type_id=$SESSION['temp_user_type_id'];
                        $temp_user_type_name=$SESSION['temp_user_type_name'];
                        $temp_user_status=$SESSION['temp_user_status'];
                        $temp_school_code=$SESSION['temp_school_code'];
                        $temp_school_name=$SESSION['temp_school_name'];

                        #################################################
                           $sesdata=array('userid'=>$temp_userid,
                                          #'user_code'=>$company_group,
                                          'company_group'=>$company_group,
                                          'user_id'=>$temp_user_id,
                                          'useridx'=>$temp_user_idx,
                                          'user_idx'=>$temp_user_idx,
                                          'uname'=>$temp_uname,
                                          #'user_name'=>$temp_uname,
                                          'fullname'=>$temp_fullname,
                                          'position'=>$position,
                                          'user_type_id'=>$temp_user_type_id,
                                          'user_type_name'=>$temp_user_type_name,
                                          #'subtype'=>$temp_user_type_name,
                                          #'utype'=>$temp_user_type_name,
                                          #'user_status'=>$temp_user_status,
                                          'school_code'=>$temp_school_code,
                                          'school_name'=>$temp_school_name,
                                          #'domain'=>base_url(),
                                    );
                                    //echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'<pre>';Die();
                                    $this->accessuser_library->session_set($sesdata);
                                    //$this->accessuser_library->setDataCookie('userid',$sesdata['user_id'],$time); 
                                    #$this->accessuser_library->setDataCookie('user_code',$sesdata['user_code'],$time);
                                    #$this->accessuser_library->setDataCookie('company_group',$sesdata['company_group'],$time);
                                    $this->accessuser_library->setDataCookie('user_id',$sesdata['user_id'],$time);
                                    //$this->accessuser_library->setDataCookie('useridx',$sesdata['user_idx'],$time);
                                    $this->accessuser_library->setDataCookie('user_idx',$sesdata['user_idx'],$time);
                                    #$this->accessuser_library->setDataCookie('uname',$sesdata['uname'],$time);
                                    //$this->accessuser_library->setDataCookie('user_name',$sesdata['uname'],$time);
                                    //$this->accessuser_library->setDataCookie('fullname',$sesdata['fullname'],$time);
                                    $this->accessuser_library->setDataCookie('user_type_id',$sesdata['user_type_id'],$time);
                                   // $this->accessuser_library->setDataCookie('user_type_name',$sesdata['user_type_name'],$time);
                                    #$this->accessuser_library->setDataCookie('utype',$sesdata['utype'],$time);
                                    #$this->accessuser_library->setDataCookie('subtype',$sesdata['subtype'],$time);
                                    #$this->accessuser_library->setDataCookie('user_status',$sesdata['user_status'],$time);
                                    //$this->accessuser_library->setDataCookie('school_code',$sesdata['school_code'],$time);
                                    //$this->accessuser_library->setDataCookie('school_name',$sesdata['school_name'],$time);
                       
                       
                                    $temp_user_id=@$_COOKIE['temp_user_id'];
                                    $temp_useridx=@$_COOKIE['temp_useridx'];
                                    $temp_user_type_id=@$_COOKIE['temp_user_type_id'];
            
                                    #$user_code=@$_COOKIE['temp_user_code'];
                                    //$company_group=@$_COOKIE['temp_company_group'];
                                    //$temp_userid=@$_COOKIE['temp_userid'];
                                    //$temp_user_idx=@$_COOKIE['temp_user_idx'];
                                    //$temp_uname=@$_COOKIE['temp_uname'];
                                    //$temp_user_name=@$_COOKIE['temp_user_name'];
                                    //$temp_fullname=@$_COOKIE['temp_fullname'];
                                    //$temp_user_type_name=@$_COOKIE['temp_user_type_name'];
                                    //$temp_utype=@$_COOKIE['temp_utype'];
                                    //$temp_user_type_name=@$_COOKIE['temp_user_type_name'];
                                    //$temp_subtype=@$_COOKIE['temp_subtype'];
                                    //$temp_user_status=@$_COOKIE['temp_user_status'];                        
                                    //$temp_school_code=@$_COOKIE['temp_school_code'];
                                    //$temp_school_name=@$_COOKIE['temp_school_name'];                       
                               
                       
                                    ##################################################
				#$this->accessuser_library->deleteCookie('temp_company_group',$company_group);
				//$this->accessuser_library->deleteCookie('temp_userid',$temp_userid);
				$this->accessuser_library->deleteCookie('temp_user_id',$temp_user_id);
				$this->accessuser_library->deleteCookie('temp_useridx',$temp_useridx);
				//$this->accessuser_library->deleteCookie('temp_user_idx',$temp_user_idx);
				//$this->accessuser_library->deleteCookie('temp_uname',$temp_uname);
				//$this->accessuser_library->deleteCookie('temp_user_name',$temp_user_name);
				//$this->accessuser_library->deleteCookie('temp_fullname',$temp_fullname);
				$this->accessuser_library->deleteCookie('temp_user_type_id',$temp_user_type_id);
				//$this->accessuser_library->deleteCookie('temp_user_type_name',$temp_user_type_name);
				#$this->accessuser_library->deleteCookie('temp_utype',$temp_utype);
				#$this->accessuser_library->deleteCookie('temp_subtype',$temp_subtype);
				#$this->accessuser_library->deleteCookie('temp_user_status',$temp_user_status);			
                        //$this->accessuser_library->deleteCookie('temp_school_code',$temp_school_code);
                        //$this->accessuser_library->deleteCookie('temp_school_name',$temp_school_name);
			
      echo 'Logout Summon'; 
      #$urldirec=base_url('user/dashboard');
      if($usertypeid==null){
        $urldirec=base_url('user/management');
      }else{
        $urldirec=base_url('user/management').'?user_type='.$usertypeid.'&user_status=1';
      }
      
      redirect($urldirec); 
      Die();
    }
########################################################
public function sessioncookieset($user_idx=''){  
            // http://connexted.local/user/test?user_idx=4614
            $this->load->library('Accessuser_library');
            # $user_idx_admin="4152";
            # $user_idx_admin2="4614";
            if($user_idx==null){
            $input=@$this->input->post(); //deletekey=>1 = delete
            if($input==null){$input=@$this->input->get();} $user_idx=@$input['user_idx'];}
            if($user_idx==null){echo 'Error user_idx is null';Die();}
                  $time=60*60*60*24;
                  $this->db->cache_off();
                  // $this->db->cache_delete_all();
                  $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
                  $this->db->from('tbl_user_2018');
                  $this->db->join('sd_user_type', 'sd_user_type.user_type_id=tbl_user_2018.user_type_id');
                  $this->db->where('tbl_user_2018.user_idx',$user_idx); 
                  $this->db->where('tbl_user_2018.user_status',1);
                  $query_get=$this->db->get();
                  $num=$query_get->num_rows();
                  $query_result=$query_get->result(); 
                  $rs=$query_result;
                  $dataresult=$rs['0'];
            #echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'<pre>';Die();
            #####################################################################
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
                  $this->accessuser_library->session_set($sesdata);
            #####################################################################
                  $this->accessuser_library->setDataCookie('userid',$sesdata['user_id'],$time);
                  $this->accessuser_library->setDataCookie('user_id',$sesdata['user_idx'],$time);
                  $this->accessuser_library->setDataCookie('useridx',$sesdata['user_idx'],$time);
                  $this->accessuser_library->setDataCookie('user_idx',$sesdata['user_idx'],$time);
                  $this->accessuser_library->setDataCookie('uname',$sesdata['uname'],$time);
                  $this->accessuser_library->setDataCookie('user_name',$sesdata['uname'],$time);
                  $this->accessuser_library->setDataCookie('fullname',$sesdata['name'].' '.$sesdata['surname'],$time);
                  $this->accessuser_library->setDataCookie('user_type_id',$sesdata['user_type_id'],$time);
                  $this->accessuser_library->setDataCookie('user_type_name',$sesdata['user_type_title'],$time);
                  $this->accessuser_library->setDataCookie('utype',$sesdata['user_type_title'],$time);
            #####################################################################
            //  $cookie_name='test';
            //  $cookie_value='test';
            //  $time=60*60*60*1;
            //  $setDataCookie=$this->accessuser_library->setDataCookie($cookie_name,$cookie_value,$time);
            $urldirec=base_url('user/sessioncookieget');
            redirect($urldirec); 
            die();
     }
public function sessioncookieget(){  
      $this->load->library('Accessuser_library');
      $session_cookie_get=$this->accessuser_library->session_cookie_get();
    # echo'<hr><pre>session_cookie_get=>';print_r($session_cookie_get);echo'<pre>';
    }
public function sessioncookiedelete(){  
      $this->load->library('Accessuser_library');
      $session_cookie_get=$this->accessuser_library->session_cookie_get();
      $_COOKIE=$session_cookie_get['COOKIE'];
      $SESSION=$session_cookie_get['SESSION'];
      /*
      echo'<hr><pre> SESSION=>';print_r($SESSION);echo'<pre>';
      echo'<hr><pre> COOKIE=>';print_r($_COOKIE);echo'<pre>'; die();
      */
      ################################################
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
            $this->accessuser_library->deleteCookie('userid',$cookie_userid);
            $this->accessuser_library->deleteCookie('user_id',$cookie_user_id);
            $this->accessuser_library->deleteCookie('useridx',$cookie_useridx);
            $this->accessuser_library->deleteCookie('user_idx',$cookie_user_idx);
            $this->accessuser_library->deleteCookie('uname',$cookie_uname);
            $this->accessuser_library->deleteCookie('user_name',$cookie_user_name);
            $this->accessuser_library->deleteCookie('fullname',$cookie_fullname);
            $this->accessuser_library->deleteCookie('user_type_id',$cookie_user_type_id);
            $this->accessuser_library->deleteCookie('user_type_name',$cookie_user_type_name);
            $this->accessuser_library->deleteCookie('utype',$cookie_utype);
            //session_destroy();
            $this->accessuser_library->sessdestroy();   
      #################################################
            echo 'Logout'; 
            $urldirec=base_url('user/login');
            redirect($urldirec);Die();
      }
public function loginweb(){
     // Login 
     $this->load->view('Login');    
     }
public function logincompany(){
     // Login 
     $this->load->view('Logincompany');    
    }
########################################################	
public function singintest(){
            $input=@$this->input->post(); 
            if($input==null){
                  $input=@$this->input->get();
            }
            $username=@$input['username'];
            $password=@$input['password'];
            if($username==null || $password==null){echo 0; die();} 
            if($username==null && $password==null){echo 'username and  password is null ';Die();}
            /*
            echo'<hr><pre> username=>';print_r($username);echo'<pre>';
            echo'<hr><pre> password=>';print_r($password);echo'<pre>'; Die(); 
            */
            $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
            $this->db->from('tbl_user_2018');
            $this->db->join('sd_user_type','sd_user_type.user_type_id=tbl_user_2018.user_type_id');
            $this->db->where('tbl_user_2018.username',$username);
            #$this->db->where('tbl_user_2018.password',$password);
            $passwordmd5=md5($password);
            #echo'<hr><pre> passwordmd5=>';print_r($passwordmd5);echo'<pre>'; die();
            $this->db->where('tbl_user_2018.password',$passwordmd5);
            $this->db->where('tbl_user_2018.user_status',1);
            $query_get=$this->db->get();
            $query_result=@$query_get->result();
            $query_results=@$query_result['0'];
            $dataresult=$query_results;
            $num=$query_get->num_rows();
            $user_idx=@$query_results->user_idx;
            $last_query=$this->db->last_query();
            #echo'<hr><pre> password=>';print_r($password);echo'</pre> <pre> last_query=>';print_r($last_query);echo'</pre>'; die();
            if($num==0){echo 0; die();}
            if($user_idx==null){
            $input=@$this->input->post(); //deletekey=>1 = delete
            if($input==null){$input=@$this->input->get();} $user_idx=@$input['user_idx'];
            }
            if($user_idx==null){echo 'Error user_idx is null';Die();}
            $user_type_id=$dataresult->user_type_id;
            $time=60*60*60*24;

             #echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'<pre>';Die();
            #####################################################################
            $log_id=$dataresult->user_idx; 
            $this->load->model("Historylog_model"); 
            $datars=$this->Historylog_model->update_lastuser_login_log($log_id);
            #####################################################################
            $this->load->library('Accessuser_library');
            $session_cookie_get=$this->accessuser_library->session_cookie_get();
            $COOKIE=@$session_cookie_get['COOKIE'];
            $SESSION=@$session_cookie_get['SESSION'];
                  $sesdata=array('userid'=>$dataresult->user_id,
                                    'user_code'=>$dataresult->company_group,
                                    'company_group'=>$dataresult->company_group,
                                    'user_id'=>$dataresult->user_id,
                                    'useridx'=>$dataresult->user_idx,
                                    'user_idx'=>$dataresult->user_idx,
                                    'uname'=>$dataresult->username,
                                    'user_name'=>$dataresult->username,
                                    'fullname'=>$dataresult->name.' '.$dataresult->surname,
                                    'position'=>$dataresult->position,
                                    'user_type_id'=>$dataresult->user_type_id,
                                    'user_type_name'=>$dataresult->user_type_title,
                                    'subtype'=>$dataresult->user_type_title,
                                    'utype'=>$dataresult->user_type_title,
                                    'user_status'=>$dataresult->user_status,
                                    'domain'=>base_url(),
                                    );
            //echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'<pre>';Die();
                  $this->accessuser_library->session_set($sesdata);
            #####################################################################
                  $this->accessuser_library->setDataCookie('userid',$sesdata['user_id'],$time); 
                  $this->accessuser_library->setDataCookie('user_code',$sesdata['user_code'],$time);
                  $this->accessuser_library->setDataCookie('company_group',$sesdata['company_group'],$time);
                  $this->accessuser_library->setDataCookie('user_id',$sesdata['user_id'],$time);
                  $this->accessuser_library->setDataCookie('useridx',$sesdata['user_idx'],$time);
                  $this->accessuser_library->setDataCookie('user_idx',$sesdata['user_idx'],$time);
                  $this->accessuser_library->setDataCookie('uname',$sesdata['uname'],$time);
                  $this->accessuser_library->setDataCookie('user_name',$sesdata['uname'],$time);
                  $this->accessuser_library->setDataCookie('fullname',$sesdata['fullname'],$time);
                  $this->accessuser_library->setDataCookie('user_type_id',$sesdata['user_type_id'],$time);
                  $this->accessuser_library->setDataCookie('user_type_name',$sesdata['user_type_name'],$time);
                  $this->accessuser_library->setDataCookie('utype',$sesdata['utype'],$time);
                  $this->accessuser_library->setDataCookie('subtype',$sesdata['subtype'],$time);
                  $this->accessuser_library->setDataCookie('user_status',$sesdata['user_status'],$time);
                  
            #####################################################################
            //  $cookie_name='test';
            //  $cookie_value='test';
            //  $time=60*60*60*1;
            //  $setDataCookie=$this->accessuser_library->setDataCookie($cookie_name,$cookie_value,$time);

            $user_type_id=@$SESSION['user_type_id'];
            if($user_type_id==null){$user_type_id=@$COOKIE['user_type_id'];}
            #######################
            $userid=@$SESSION['userid'];
            if($userid==null){$userid=@$COOKIE['userid']; } 
            #######################
            $user_idx=@$SESSION['user_idx'];
            if($user_idx==null){$userid=@$COOKIE['user_idx'];}  
            /*
            
            echo $num; 
            echo'<hr><pre> query_results=>';print_r($query_results);echo'<pre>';
            echo'<hr><pre> dataresult=>';print_r($dataresult);echo'<pre>';
            echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>';
            Die(); 
            */
            
            }
public function sestest(){
            $this->load->library('Accessuser_library');
            $session_cookie_get=$this->accessuser_library->session_cookie_get();
            $COOKIE=@$session_cookie_get['COOKIE'];
            $SESSION=@$session_cookie_get['SESSION'];
              echo'<!--<hr><pre> SESSION=>';print_r($SESSION);echo'<pre>-->';echo'<!--<hr><pre> COOKIE=>';print_r($COOKIE);echo'<pre>-->';  die();
      }
public function singintestform(){
      ?>
      <form id="form1" name="form1" method="post" action="<?php echo base_url('user/login/singintest');?>">
      User:
      <label>
      <input name="username" type="text" id="username" />
      </label> 
      Pass
      <label>
      <input name="password" type="password" id="password" />
      </label>

      <input type="submit" name="Submit" value="Login" />
      </form>
      <?php
      }
########################################################
public function sinincompany(){
      // http://connexted.local/user/sinincompany?username=companybbl&password=bbl1234
        $input=@$this->input->post(); 
        if($input==null){$input=@$this->input->get();   }
        //echo'<hr><pre>   input=>';print_r($input);echo'<pre>';
        $username=@$input['username'];
        $password=@$input['password'];
        if($username==null && $password==null){    
        /*
        echo'<hr><pre>  username=>';print_r($username);echo'<pre>';
        echo'<hr><pre>  password=>';print_r($password);echo'<pre>';
        */
        #echo 'username and  password is null ';Die();
              $urldirec=base_url('user/logincompany');
              redirect($urldirec); 
              die(); 
        }
              //echo'<hr><pre>  username=>';print_r($username);echo'<pre>';echo'<hr><pre>  password=>';print_r($password);echo'<pre>';Die();
  
              $this->db->cache_off();
              //$this->db->cache_delete_all();
              $this->db->select('tbl_company.*');
              $this->db->from('tbl_company');
              $this->db->where('tbl_company.username',$username); 
              $this->db->where('tbl_company.password',$password);
              $this->db->limit(1);
              $query_get=$this->db->get();
              $num=$query_get->num_rows();
              $query_result=$query_get->result(); 
              $rs=$query_result;
              $dataresult=$rs;
              if($dataresult==null){
              $dataresult=0;
              $urldirec=base_url('login');
              redirect($urldirec); 
              Die();
              }
              #echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'<pre>'; die();
              if($dataresult!==0 || $dataresult!==null){$datars=$dataresult[0];}     
              $dataall['user_idx']=$datars->company_id;    
              $dataall['userid']=$datars->company_id;
              $dataall['username']=$datars->username;
              $dataall['utype']='COMPANY';
              $dataall['user_type_name']='COMPANY';
              $dataall['user_type_id']=5;
              $dataall['fullname']=$datars->company_code;
              $dataall['useridx']=$datars->company_id; 
              $query_result=$dataall;
              #echo'<hr><pre>  $query_result=>';print_r($query_result);echo'<pre>'; die();
              $time=60*60*60*24;
        #####################################################################
              $this->load->library('Accessuser_library');       
              $sesdata=array('userid'=>$query_result['useridx'],
                                'user_id'=>$query_result['useridx'],
                                'useridx'=>$query_result['useridx'],
                                'user_idx'=>$query_result['useridx'],
                                'uname'=>$query_result['username'],
                                'user_name'=>$query_result['username'],
                                'fullname'=>$query_result['fullname'],
                                'user_status'=>1,
                                'user_type_id'=>5,
                                'user_type_name'=>$query_result['utype'],
                                'utype'=>$query_result['utype'],
                                'domain'=>base_url(),
                                );                        
        //echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'<pre>'; die();  
              $this->accessuser_library->session_set($sesdata);
              $time=60*60*60*1;
              #####################################################################
              $this->accessuser_library->setDataCookie('userid',$sesdata['user_id'],$time);
              $this->accessuser_library->setDataCookie('user_id',$sesdata['user_idx'],$time);
              $this->accessuser_library->setDataCookie('useridx',$sesdata['user_idx'],$time);
              $this->accessuser_library->setDataCookie('user_idx',$sesdata['user_idx'],$time);
              $this->accessuser_library->setDataCookie('uname',$sesdata['uname'],$time);
              $this->accessuser_library->setDataCookie('user_name',$sesdata['uname'],$time);
              $this->accessuser_library->setDataCookie('fullname',$sesdata['fullname'],$time);
              $this->accessuser_library->setDataCookie('user_type_id',$sesdata['user_type_id'],$time);
              $this->accessuser_library->setDataCookie('user_type_name',$sesdata['user_type_name'],$time);
              $this->accessuser_library->setDataCookie('utype',$sesdata['utype'],$time);
              #####################################################################
              $session_cookie_get=$this->accessuser_library->session_cookie_get();    
        #####################################################################
        //  $cookie_name='test';
        //  $cookie_value='test';
        //  $time=60*60*60*1;
        //  $setDataCookie=$this->accessuser_library->setDataCookie($cookie_name,$cookie_value,$time);
        $urldirec=base_url('company');
        redirect($urldirec); 
        die();
      }
public function checkin(){
      $this->load->library('Accessuser_library');
      $session_cookie_get=$this->accessuser_library->session_cookie_get();
      $_COOKIE=$session_cookie_get['COOKIE'];
      $SESSION=$session_cookie_get['SESSION'];
      if($_COOKIE!==null || $SESSION!==null){}
      $input=@$this->input->post(); 
      if($input==null){$input=@$this->input->get();   }
      $username=@$input['username'];
      $password=@$input['password'];
      if($username==null || $password==null){echo 0; die();} 
      if($username==null && $password==null){
      //echo 'username and  password is null ';Die();
      $urldirec=base_url('user/login');
      redirect($urldirec);die(); 
      }
      $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
      $this->db->from('tbl_user_2018');
      $this->db->join('sd_user_type','sd_user_type.user_type_id=tbl_user_2018.user_type_id');
      $this->db->where('tbl_user_2018.username',$username);
  
      # $this->db->where('tbl_user_2018.password',$password);
      ##############
      $passwordmd5=md5($password);
      $this->db->where('tbl_user_2018.password_encrypt',$passwordmd5);
      #echo'<hr><pre> passwordmd5=>';print_r($passwordmd5);echo'<pre>'; die();
      #$this->db->or_where('tbl_user_2018.password_encrypt',$passwordmd5);
      $this->db->where('tbl_user_2018.user_status',1);
      $query_get=$this->db->get();
      $query_result=@$query_get->result();
      $query_results=@$query_result['0'];
      $dataresult=$query_results;
      $num=$query_get->num_rows();
      $user_idx=@$query_results->user_idx;
      $last_query=$this->db->last_query();
      if($num==0){echo 0; die();}
      /*
      echo $num; 
      echo'<hr><pre> query_results=>';print_r($query_results);echo'<pre>';
      echo'<hr><pre> dataresult=>';print_r($dataresult);echo'<pre>';
      echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>';
      Die();
      */

      # $user_idx_admin="4152";
      # $user_idx_admin2="4614";
      if($user_idx==null){
      $input=@$this->input->post(); //deletekey=>1 = delete
      if($input==null){$input=@$this->input->get();} $user_idx=@$input['user_idx'];
      }
      if($user_idx==null){
      $urldirec=base_url('user/login');
      /*
      echo ("<script LANGUAGE='JavaScript'>
            window.alert('Username หรือ password ผิด');
            window.location.href='$urldirec';
            </script>");
      die(); 
      */
      redirect($urldirec); 
      echo 'Error user_idx is null';Die();
      }
      $user_type_id=$dataresult->user_type_id;
      $time=60*60*60*24;
      //echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'<pre>';Die();
      #####################################################################
      $log_id=$dataresult->user_idx; 
      $this->load->model("Historylog_model"); 
      $datars=$this->Historylog_model->update_lastuser_login_log($log_id);
      #####################################################################
      $this->load->library('Accessuser_library');
      $session_cookie_get=$this->accessuser_library->session_cookie_get();
      $COOKIE=@$session_cookie_get['COOKIE'];
      $SESSION=@$session_cookie_get['SESSION'];
            $sesdata=array('userid'=>$dataresult->user_id,
                              'user_code'=>$dataresult->company_group,
                              'company_group'=>$dataresult->company_group,
                              'user_id'=>$dataresult->user_id,
                              'useridx'=>$dataresult->user_idx,
                              'user_idx'=>$dataresult->user_idx,
                              'uname'=>$dataresult->username,
                              'user_name'=>$dataresult->username,
                              'fullname'=>$dataresult->name.' '.$dataresult->surname,
                              'position'=>$dataresult->position,
                              'user_type_id'=>$dataresult->user_type_id,
                              'user_type_name'=>$dataresult->user_type_title,
                              'subtype'=>$dataresult->user_type_title,
                              'utype'=>$dataresult->user_type_title,
                              'user_status'=>$dataresult->user_status,
                              'domain'=>base_url(),
                              );
      //echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'<pre>';Die();
            $this->accessuser_library->session_set($sesdata);
            $this->accessuser_library->setDataCookie('userid',$sesdata['user_id'],$time); 
            $this->accessuser_library->setDataCookie('user_code',$sesdata['user_code'],$time);
            $this->accessuser_library->setDataCookie('company_group',$sesdata['company_group'],$time);
            $this->accessuser_library->setDataCookie('user_id',$sesdata['user_id'],$time);
            $this->accessuser_library->setDataCookie('useridx',$sesdata['user_idx'],$time);
            $this->accessuser_library->setDataCookie('user_idx',$sesdata['user_idx'],$time);
            $this->accessuser_library->setDataCookie('uname',$sesdata['uname'],$time);
            $this->accessuser_library->setDataCookie('user_name',$sesdata['uname'],$time);
            $this->accessuser_library->setDataCookie('fullname',$sesdata['fullname'],$time);
            $this->accessuser_library->setDataCookie('user_type_id',$sesdata['user_type_id'],$time);
            $this->accessuser_library->setDataCookie('user_type_name',$sesdata['user_type_name'],$time);
            $this->accessuser_library->setDataCookie('utype',$sesdata['utype'],$time);
            $this->accessuser_library->setDataCookie('subtype',$sesdata['subtype'],$time);
            $this->accessuser_library->setDataCookie('user_status',$sesdata['user_status'],$time);
      #####################################################################
      ############### user Historylog login loggout insert  Start###################
      $code='200';
      $modules='User/Login';
      $process='Login';
      $datelogin=date('Y-m-d H:I:S');
      $message='User Login '.$datelogin;
      $insertdatalog=array('code'=> $code, // 200
                                    'modules'=>$modules, // ชื่อ  modules / function 
                                    'process'=>$process, // การเข้าไปดำเนินการ  เช่น เพิ่ม , ลบ,แก้ไข
                                    'message'=>$message, // อธิบายเพิ่ม การทำงาน
                              );
      $this->load->model("Historylog_model"); 
      $datars=$this->Historylog_model->insert_sd_history_user_log($insertdatalog);
      ############### user Historylog login loggout insert  End ###################
      if($user_type_id==1){
            $urldirec=base_url('user/dashboard'); 
      }elseif($user_type_id==2){
            $urldirec=base_url('user/dashboard');      
      }elseif($user_type_id==3){
            $urldirec=base_url('sp');  
      }elseif($user_type_id==4){
            $urldirec=base_url('user/dashboard'); 
      }elseif($user_type_id==5){
            $urldirec=base_url('company'); 
      }elseif($user_type_id==6){
            $urldirec=base_url('icttalent'); 
      }elseif($user_type_id==7){
            $urldirec=base_url('user/dashboard');  
      }elseif($user_type_id==8){
            $urldirec=base_url('user/dashboard'); 
      }elseif($user_type_id==9){
            $urldirec=base_url('icttalent/coach'); 
      }else{
            $urldirec=base_url('user/dashboard');  
      }
      //header("location: $urldirec");
      
      #echo $num; 
      #echo $urldirec; 
      // echo'<hr><pre> urldirec=>';print_r($urldirec);echo'<pre>';
      // echo'<hr><pre> query_results=>';print_r($query_results);echo'<pre>';
      // echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>';
      // Die();
      $rt=array('numrows'=>$num,'urldirec'=>$urldirec);
      echo $urldirec; die();
      }
public function checkin2(){
            $this->load->library('Accessuser_library');
            $session_cookie_get=$this->accessuser_library->session_cookie_get();
            $_COOKIE=$session_cookie_get['COOKIE'];
            $SESSION=$session_cookie_get['SESSION'];
            if($_COOKIE!==null || $SESSION!==null){}
            // http://connexted.local/user/sinin?username=ICTadmindev&password=admindev@1234
            // http://connexted.local/user/sinin?user_idx=4614
            // ICTadmindev	admindev@1234
            $input=@$this->input->post(); 
            if($input==null){$input=@$this->input->get();   }
            //echo'<hr><pre>   input=>';print_r($input);echo'<pre>';
            $username=@$input['username'];
            $password=@$input['password'];

            # echo'<hr><pre>  username=>';print_r($username);echo'<pre>';
            # echo'<hr><pre>  password=>';print_r($password);echo'<pre>'; #die();
            if($username==null || $password==null){echo 0; die();} 
            if($username==null && $password==null){
            /*
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('Username หรือ password ว่าง');
            window.location.href='$urldirec';
            </script>");
            */
            echo 'username and  password is null ';Die();
            $urldirec=base_url('user/login');
            redirect($urldirec);die(); 
            }
            /*
            echo'<hr><pre>  username=>';print_r($username);echo'<pre>';
            echo'<hr><pre>  password=>';print_r($password);echo'<pre>';Die();
            */
            $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
            $this->db->from('tbl_user_2018');
            $this->db->join('sd_user_type','sd_user_type.user_type_id=tbl_user_2018.user_type_id');
            $this->db->where('tbl_user_2018.username',$username);
            
            # $this->db->where('tbl_user_2018.password',$password);
            ##############
            $passwordmd5=md5($password);
            $this->db->where('tbl_user_2018.password_encrypt',$passwordmd5);
            #echo'<hr><pre> passwordmd5=>';print_r($passwordmd5);echo'<pre>'; die();
            $this->db->where('tbl_user_2018.user_status',1);
            $query_get=$this->db->get();
            $query_result=@$query_get->result();
            $query_results=@$query_result['0'];
            $dataresult=$query_results;
            $num=$query_get->num_rows();
            $user_idx=@$query_results->user_idx;
            $last_query=$this->db->last_query();
            if($num==0){echo 0; die();}
            /*
            echo $num; 
            echo'<hr><pre> query_results=>';print_r($query_results);echo'<pre>';
            echo'<hr><pre> dataresult=>';print_r($dataresult);echo'<pre>';
            echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>';
            Die();
            */

            # $user_idx_admin="4152";
            # $user_idx_admin2="4614";
            if($user_idx==null){
            $input=@$this->input->post(); //deletekey=>1 = delete
            if($input==null){$input=@$this->input->get();} $user_idx=@$input['user_idx'];
            }
            if($user_idx==null){
            $urldirec=base_url('user/login');
            /*
            echo ("<script LANGUAGE='JavaScript'>
                  window.alert('Username หรือ password ผิด');
                  window.location.href='$urldirec';
                  </script>");
            die(); 
            */
            redirect($urldirec); 
            echo 'Error user_idx is null';Die();
            }
            $time=60*60*60*24;
            //echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'<pre>';Die();
            #####################################################################
            $log_id=$dataresult->user_idx; 
            $this->load->model("Historylog_model"); 
            $datars=$this->Historylog_model->update_lastuser_login_log($log_id);
            #####################################################################
                  $sesdata=array('userid'=>$dataresult->user_id,
                                    'user_code'=>$dataresult->company_group,
                                    'company_group'=>$dataresult->company_group,
                                    'user_id'=>$dataresult->user_id,
                                    'useridx'=>$dataresult->user_idx,
                                    'user_idx'=>$dataresult->user_idx,
                                    'uname'=>$dataresult->username,
                                    'user_name'=>$dataresult->username,
                                    'fullname'=>$dataresult->name.' '.$dataresult->surname,
                                    'position'=>$dataresult->position,
                                    'user_type_id'=>$dataresult->user_type_id,
                                    'user_type_name'=>$dataresult->user_type_title,
                                    'subtype'=>$dataresult->user_type_title,
                                    'utype'=>$dataresult->user_type_title,
                                    'user_status'=>$dataresult->user_status,
                                    'domain'=>base_url(),
                                    );
                  $this->accessuser_library->session_set($sesdata);
            #####################################################################
                  $this->accessuser_library->setDataCookie('userid',$sesdata['user_id'],$time); 
                  $this->accessuser_library->setDataCookie('user_code',$sesdata['user_code'],$time);
                  $this->accessuser_library->setDataCookie('company_group',$sesdata['company_group'],$time);
                  $this->accessuser_library->setDataCookie('user_id',$sesdata['user_idx'],$time);
                  $this->accessuser_library->setDataCookie('useridx',$sesdata['user_idx'],$time);
                  $this->accessuser_library->setDataCookie('user_idx',$sesdata['user_idx'],$time);
                  $this->accessuser_library->setDataCookie('uname',$sesdata['uname'],$time);
                  $this->accessuser_library->setDataCookie('user_name',$sesdata['uname'],$time);
                  $this->accessuser_library->setDataCookie('fullname',$sesdata['fullname'],$time);
                  $this->accessuser_library->setDataCookie('user_type_id',$sesdata['user_type_id'],$time);
                  $this->accessuser_library->setDataCookie('user_type_name',$sesdata['user_type_name'],$time);
                  $this->accessuser_library->setDataCookie('utype',$sesdata['utype'],$time);
                  $this->accessuser_library->setDataCookie('subtype',$sesdata['subtype'],$time);
                  $this->accessuser_library->setDataCookie('user_status',$sesdata['user_status'],$time);
                  
            #####################################################################
            ############### user Historylog login loggout insert  Start###################
            $code='200';
            $modules='User/Login';
            $process='Login';
            $datelogin=date('Y-m-d H:I:S');
            $message='User Login '.$datelogin;
            $insertdatalog=array('code'=> $code, // 200
                                          'modules'=>$modules, // ชื่อ  modules / function 
                                          'process'=>$process, // การเข้าไปดำเนินการ  เช่น เพิ่ม , ลบ,แก้ไข
                                          'message'=>$message, // อธิบายเพิ่ม การทำงาน
                                    );
            $this->load->model("Historylog_model"); 
            $datars=$this->Historylog_model->insert_sd_history_user_log($insertdatalog);
            ############### user Historylog login loggout insert  End ###################
                  //  $cookie_name='test';
            //  $cookie_value='test';
            //  $time=60*60*60*1;
            //  $setDataCookie=$this->accessuser_library->setDataCookie($cookie_name,$cookie_value,$time);
            $this->load->library('Accessuser_library');
            $session_cookie_get=$this->accessuser_library->session_cookie_get();
            $COOKIE=@$session_cookie_get['COOKIE'];
            $SESSION=@$session_cookie_get['SESSION'];
            $user_type_id=@$SESSION['user_type_id'];
            if($user_type_id==null){$user_type_id=@$COOKIE['user_type_id'];}
            #######################
            $userid=@$SESSION['userid'];
            if($userid==null){$userid=@$COOKIE['userid']; } 
            #######################
            $user_idx=@$SESSION['user_idx'];
            if($user_idx==null){$userid=@$COOKIE['user_idx'];}  

            /*
            echo $num; 
            echo'<hr><pre> query_results=>';print_r($query_results);echo'<pre>';
            echo'<hr><pre> dataresult=>';print_r($dataresult);echo'<pre>';
            echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>';
            Die(); 
            */

            /*
            echo'<hr><pre>user_type_id=>';print_r($user_type_id);echo'<pre>';
            echo'<hr><pre>COOKIE=>';print_r($COOKIE);echo'<pre>';
            echo'<hr><pre>SESSION=>';print_r($SESSION);echo'<pre>';die();
            */
            if($user_type_id==1){
                  $urldirec=base_url('user/dashboard'); 
            }elseif($user_type_id==2){
                  $urldirec=base_url('user/dashboard');      
            }elseif($user_type_id==3){
                  $urldirec=base_url('sp');  
            }elseif($user_type_id==4){
                  $urldirec=base_url('user/dashboard'); 
            }elseif($user_type_id==5){
                  $urldirec=base_url('company'); 
            }elseif($user_type_id==6){
                  $urldirec=base_url('icttalent'); 
            }elseif($user_type_id==7){
                  $urldirec=base_url('user/dashboard');  
            }elseif($user_type_id==8){
                  $urldirec=base_url('user/dashboard'); 
            }elseif($user_type_id==9){
                  $urldirec=base_url('icttalent/coach'); 
            }else{
                  $urldirec=base_url('user/dashboard');  
            }
            //header("location: $urldirec");

            #echo $num; 
            #echo $urldirec; 
            // echo'<hr><pre> urldirec=>';print_r($urldirec);echo'<pre>';
            // echo'<hr><pre> query_results=>';print_r($query_results);echo'<pre>';
            // echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>';
            // Die();
            $rt=array('numrows'=>$num,'urldirec'=>$urldirec);

            //redirect($urldirec);die();

            ##########################################################
            $this->load->library('Accessuser_library');
            $session_cookie_get=$this->accessuser_library->session_cookie_get();
            $COOKIE=@$session_cookie_get['COOKIE'];
            $SESSION=@$session_cookie_get['SESSION'];
            $user_type_id=@$SESSION['user_type_id'];
            if($user_type_id==null){$user_type_id=@$COOKIE['user_type_id'];}
            #######################
            $userid=@$SESSION['userid'];
            if($userid==null){$userid=@$COOKIE['userid']; } 
            #######################
            $user_idx=@$SESSION['user_idx'];
            if($user_idx==null){$userid=@$COOKIE['user_idx'];}  
            echo $urldirec; die();
            }
########################################################
public function vsSESSION(){
    $this->load->library('Accessuser2_library'); 
   $session_cookie_get=$this->accessuser2_library->session_cookie_get();
   $_COOKIE=$session_cookie_get['COOKIE'];
   $SESSION=$session_cookie_get['SESSION'];
   $input=@$this->input->post(); 
   if($input==null){$input=@$this->input->get();   }
   $usermd=@$input['usermd'];
   if($usermd=='5371@na'){
   echo'<hr><pre> SESSION=>';print_r($SESSION);echo'<pre>'; echo'<hr><pre> COOKIE=>';print_r($_COOKIE);echo'<pre>'; 
    # $encryptCookie=$this->accessuser2_library->encryptCookie($value);
    # $decryptCookie=$this->accessuser2_library->decryptCookie($value);

   #$userid_encryptCookie=@$this->accessuser2_library->decryptCookie($value);
   #echo'<hr><pre>userid_encryptCookie=>';print_r($userid_encryptCookie);echo'<pre>'; 
   
   # $this->cleanall();
   }
   ###################################
   # $this->accessuser2_library->setsessionandcookie_temp();
   #$this->accessuser2_library->cleantempsessionandcookie();
   #$this->cleansessionandcookie();
   #$this->cleansummonsessionandcookie();
   ###################################
   die();
   }
public function cleanall(){
    $this->load->library('Accessuser2_library'); 
    $this->accessuser2_library->cleantempsessionandcookie();
    $this->accessuser2_library->cleansessionandcookie();
    $this->accessuser2_library->cleansummonsessionandcookie();
    # die();
    }
########################################################
public function singintestdb(){
    $input=@$this->input->post(); 
    if($input==null){$input=@$this->input->get();   }
    $username=@$input['username'];
    $password=@$input['password'];
        //Security Class
        $username=$this->db->escape_str($username);
        $password=$this->db->escape_str($password);
        $username=$this->security->xss_clean($username);
        $password=$this->security->xss_clean($password);
        //Security Class
    if($username==null || $password==null){echo 0; die();} 
    if($username==null && $password==null){
        //echo 'username and  password is null ';Die();
        $urldirec=base_url('user/login');
        redirect($urldirec);die(); 
    }
    $this->db->select('tbl_user_2018.*,sd_user_type.user_type_title');
    $this->db->from('tbl_user_2018');
    $this->db->join('sd_user_type','sd_user_type.user_type_id=tbl_user_2018.user_type_id');
    $this->db->where('tbl_user_2018.username',$username);
    # $this->db->where('tbl_user_2018.password',$password);
    ##############
        $passwordmd5=md5($password);
        $this->db->where('tbl_user_2018.password_encrypt',$passwordmd5);
    #echo'<hr><pre> passwordmd5=>';print_r($passwordmd5);echo'<pre>'; die();
    ###############
    #$this->db->or_where('tbl_user_2018.password_encrypt',$passwordmd5);
    $this->db->where('tbl_user_2018.user_status',1);
    $query_get=$this->db->get();
    $query_result=@$query_get->result();
    $query_results=@$query_result['0'];
    $dataresult=$query_results;
    $num=$query_get->num_rows();
    $user_idx=@$query_results->user_idx;
    $user_id=@$query_results->user_id;
    $last_query=$this->db->last_query();
    if($num==0){
      echo 0;  # die();
      echo $num; echo'<hr><pre> query_results=>';print_r($query_results);echo'<pre>';
      echo'<hr><pre> dataresult=>';print_r($dataresult);echo'<pre>';echo'<hr><pre> last_query=>';print_r($last_query);echo'<pre>'; Die();
      
      }
    if($user_idx==null){
    $input=@$this->input->post(); //deletekey=>1 = delete
    if($input==null){$input=@$this->input->get();} $user_idx=@$input['user_idx'];
    }
    if($user_idx==null){
        $urldirec=base_url('user/login');
        /*
        echo ("<script LANGUAGE='JavaScript'>
            window.alert('Username หรือ password ผิด');
            window.location.href='$urldirec';
            </script>");
        die(); 
        */
        redirect($urldirec); 
        echo 'Error user_idx is null';Die();
    }
    $user_type_id=$dataresult->user_type_id;
    $time=60*60*60*24;
    #echo'<hr><pre>  dataresult=>';print_r($dataresult);echo'<pre>';   
    $this->load->library('Accessuser2_library'); 
    $session_cookie_get=$this->accessuser2_library->session_cookie_get();
    $this->load->model('api/Asmitem_model');
    $user_id=$dataresult->user_id;
    $schoolmaster=@$this->Asmitem_model->school_master_school_code($user_id); 
    if($schoolmaster==null){
        $school_map=@$this->Asmitem_model->user_school_map($user_id); 
        #echo'<hr><pre> schoolmaster=>';print_r($schoolmaster);echo'</pre>';  echo'<hr><pre> school_map=>';print_r($school_map);echo'</pre>'; 
        $school_code=@$school_map->school_code;
        $school_name=@$school_map->school_name;   
        $user_id=@$school_map->user_id;
        $name=@$school_map->name; 
        $school_province=@$school_map->province;    
        $sesdata=array('userid'=>$dataresult->user_id,
                        'user_code'=>$dataresult->company_group,
                        'company_group'=>$dataresult->company_group,
                        'user_id'=>$dataresult->user_id,
                        'useridx'=>$dataresult->user_idx,
                        'user_idx'=>$dataresult->user_idx,
                        'uname'=>$dataresult->username,
                        'user_name'=>$dataresult->username,
                        'fullname'=>$dataresult->name.' '.$dataresult->surname,
                        'position'=>$dataresult->position,
                        'user_type_id'=>$dataresult->user_type_id,
                        'user_type_name'=>$dataresult->user_type_title,
                        'subtype'=>$dataresult->user_type_title,
                        'utype'=>$dataresult->user_type_title,
                        'user_status'=>$dataresult->user_status,
                        'school_code'=>$school_code,
                        'school_name'=>$school_name,
                        'school_province'=>$school_province,
                        'domain'=>base_url(),
                        );
                        #echo'<hr><pre>  sesdata1=>';print_r($sesdata);echo'</pre>';   
            }else{
                        $school_code=@$schoolmaster->school_code;
                        $school_name=@$schoolmaster->school_name;  
                        $school_province=@$schoolmaster->province;   
                        #echo'<hr><pre> schoolmaster=>';print_r($schoolmaster);echo'</pre>';  
                            $sesdata=array('userid'=>$dataresult->user_id,
                                                'user_code'=>$dataresult->company_group,
                                                'company_group'=>$dataresult->company_group,
                                                'user_id'=>$dataresult->user_id,
                                                'useridx'=>$dataresult->user_idx,
                                                'user_idx'=>$dataresult->user_idx,
                                                'uname'=>$dataresult->username,
                                                'user_name'=>$dataresult->username,
                                                'fullname'=>$dataresult->name.' '.$dataresult->surname,
                                                'position'=>$dataresult->position,
                                                'user_type_id'=>$dataresult->user_type_id,
                                                'user_type_name'=>$dataresult->user_type_title,
                                                'subtype'=>$dataresult->user_type_title,
                                                'utype'=>$dataresult->user_type_title,
                                                'user_status'=>$dataresult->user_status,
                                                'school_code'=>$school_code,
                                                'school_name'=>$school_name,
                                                'school_province'=>$school_province,
                                                'domain'=>base_url(),
                                                );
                                            #  echo'<hr><pre>  sesdata2=>';print_r($sesdata);echo'</pre>';   
    }
    # echo'<hr><pre>  school_map=>';print_r($school_map);echo'</pre>'; echo'<hr><pre> schoolmaster=>';print_r($schoolmaster);echo'</pre>';  die();

            #echo'<hr><pre>  sesdata=>';print_r($sesdata);echo'</pre>';  die();
    $this->load->library('Accessuser2_library');  
    $session_cookie_get=$this->accessuser2_library->session_cookie_get();
    $_COOKIE=$session_cookie_get['COOKIE'];
    $SESSION=$session_cookie_get['SESSION'];
    if($_COOKIE!==null || $SESSION!==null){}
                $this->accessuser2_library->session_set($sesdata);
                $sesdataCookie=array('userid'=>$this->accessuser2_library->encryptCookie($sesdata['userid']),
                            'user_code'=>$this->accessuser2_library->encryptCookie($sesdata['user_code']),
                            'company_group'=>$this->accessuser2_library->encryptCookie($sesdata['company_group']),
                            'user_id'=>$this->accessuser2_library->encryptCookie($sesdata['user_id']),
                            'useridx'=>$this->accessuser2_library->encryptCookie($sesdata['useridx']),
                            'user_idx'=>$this->accessuser2_library->encryptCookie($sesdata['user_idx']),
                            'uname'=>$this->accessuser2_library->encryptCookie($sesdata['uname']),
                            'user_name'=>$this->accessuser2_library->encryptCookie($sesdata['user_name']),
                            'fullname'=>$this->accessuser2_library->encryptCookie($sesdata['fullname']),
                            'position'=>$this->accessuser2_library->encryptCookie($sesdata['position']),
                            'user_type_id'=>$this->accessuser2_library->encryptCookie($sesdata['user_type_id']),
                            'user_type_name'=>$this->accessuser2_library->encryptCookie($sesdata['user_type_name']),
                            'subtype'=>$this->accessuser2_library->encryptCookie($sesdata['subtype']),
                            'utype'=>$this->accessuser2_library->encryptCookie($sesdata['utype']),
                            'user_status'=>$this->accessuser2_library->encryptCookie($sesdata['user_status']),
                            'domain'=>base_url(),
                    );
                $this->accessuser2_library->setDataCookie('userid',$sesdataCookie['user_id'],$time); 
                $this->accessuser2_library->setDataCookie('user_code',$sesdataCookie['user_code'],$time);
                $this->accessuser2_library->setDataCookie('company_group',$sesdataCookie['company_group'],$time);
                $this->accessuser2_library->setDataCookie('user_id',$sesdataCookie['user_id'],$time);
                $this->accessuser2_library->setDataCookie('useridx',$sesdataCookie['user_idx'],$time);
                $this->accessuser2_library->setDataCookie('user_idx',$sesdataCookie['user_idx'],$time);
                $this->accessuser2_library->setDataCookie('uname',$sesdataCookie['uname'],$time);
                $this->accessuser2_library->setDataCookie('user_name',$sesdataCookie['uname'],$time);
                $this->accessuser2_library->setDataCookie('fullname',$sesdataCookie['fullname'],$time);
                $this->accessuser2_library->setDataCookie('user_type_id',$sesdataCookie['user_type_id'],$time);
                $this->accessuser2_library->setDataCookie('user_type_name',$sesdataCookie['user_type_name'],$time);
                $this->accessuser2_library->setDataCookie('utype',$sesdataCookie['utype'],$time);
                $this->accessuser2_library->setDataCookie('subtype',$sesdataCookie['subtype'],$time);
                $this->accessuser2_library->setDataCookie('user_status',$sesdataCookie['user_status'],$time);
                $this->accessuser2_library->setDataCookie('position',$sesdataCookie['position'],$time);  
    
    
    $urldirec=base_url().'user/login/vsSESSION?usermd=5371@na';
    redirect($urldirec); 
    }
public function testform(){
   //http://connexted.local/user/login/testform
   ?>
   <form id="form1" name="form1" method="post" action="<?php echo base_url('user/login/singintestdb');?>">
   <div>
   <div>username</div>
   </div>
   <input name="username" type="text" id="username" />
   <div>
   <div>password</div>
   </div>
   <label>
   <input name="password" type="password" id="password" />
   </label>
   <input name="login" type="submit" id="login" value="login" />
   </form>
   <?php
   }
########################################################
}