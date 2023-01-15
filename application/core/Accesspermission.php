<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Accesspermission extends CI_Controller {
protected $look_user_idx;
public $user_id;
public $user_idx;
protected $username;
public $user_fullname;
public $user_type;
public $user_avatar;
public $user_company;
    /*for company*/
public $user_code;
public $isAdmin;
/*for template*/
protected $title_1 = "";
protected $title_2 = "";
protected $base_crumb = [];	
public $title = "ConnextED";
public $links = [];
public $scripts = [];
public $assets = "assets/";
public $link_target;
public function __construct(){	
    parent::__construct();		
        $input=@$this->input->get();
        if($input==null){ $input=@$this->input->post(); }
        $debug=@$input['debug'];
        $deletekey=@$input['deletekey'];
		$this->load->library('session');
		$this->load->library('connexted_library');        
        $this->load->library('Accessuser_library');
		$this->load->library('user_agent');
		$this->load->model('connexted_model');
        $this->load->helper('url');
        $this->config->load('encryptkey'); 
        $setoption=$this->config->item('setoption'); 
        $session_cookie_get=$this->accessuser_library->session_cookie_get();
        # $encryptCookie=$this->accessuser_library->encryptCookie($value);
        # $decryptCookie=$this->accessuser_library->decryptCookie($value);
        $_COOKIE=@$session_cookie_get['COOKIE'];        
        $_SESSION=@$session_cookie_get['SESSION'];
        $COOKIE=@$session_cookie_get['COOKIE'];
        $SESSION=@$session_cookie_get['SESSION'];
        if($debug==1){ echo'<hr><pre> SESSION=>';print_r($SESSION);echo'</pre>';echo'<hr><pre> COOKIE=>';print_r($COOKIE);echo'</pre>'; }
        $SESSION_user_code=@$_SESSION['user_code'];
        $SESSION_company_group=@$_SESSION['company_group'];
        $SESSION_userid=@$_SESSION['userid'];
        $SESSION_useridx=@$_SESSION['useridx'];
        $SESSION_user_idx=@$_SESSION['user_idx'];
        $SESSION_uname=@$_SESSION['uname'];
        $SESSION_user_name=@$_SESSION['user_name'];
        $SESSION_fullname=@$_SESSION['fullname'];
        $SESSION_user_type_id=@$_SESSION['user_type_id'];
        $SESSION_user_type_name=@$_SESSION['user_type_name'];
        $SESSION_utype=@$_SESSION['utype'];
        $SESSION_subtype=@$_SESSION['subtype'];
        $SESSION_user_status=@$_SESSION['user_status'];
        $SESSION_user_id=@$_SESSION['user_id'];
     #echo'<hr><pre> SESSION_user_id=>';print_r($SESSION_user_id);echo'</pre>';  die;
        if($SESSION_user_id==null){ $SESSION_user_id=@$SESSION['summon_user_id'];}
        if($SESSION_user_id==null){
            $urldirec=base_url('user/login/');
            redirect($urldirec);  die(); 
            echo ("<script LANGUAGE='JavaScript'>
                window.alert(' กรุณา Login หมดอายุการใช้งานระบบ  ');
                window.location.href='$urldirec';
                </script>"); 
                die(); 
        }  
      
        $user_id=@$SESSION['user_id'];
        if($user_id==null){             
            $SESSION_user_id=@$SESSION['summon_user_id'];
             ################summon########################
             $COOKIE_summon_user_code=@$_COOKIE['summon_user_code'];
             $COOKIE_summon_company_group=@$_COOKIE['summon_company_group'];
             $COOKIE_summon_userid=@$_COOKIE['summon_userid'];
             $COOKIE_summon_user_id=@$_COOKIE['summon_user_id'];
             $COOKIE_summon_useridx=@$_COOKIE['summon_useridx'];
             $COOKIE_summon_user_idx=@$_COOKIE['summon_user_idx'];
             $COOKIE_summon_uname=@$_COOKIE['summon_uname'];
             $COOKIE_summon_user_name=@$_COOKIE['summon_user_name'];
             $COOKIE_summon_fullname=@$_COOKIE['summon_fullname'];
             $COOKIE_summon_user_type_id=@$_COOKIE['summon_user_type_id'];
             $COOKIE_summon_user_type_name=@$_COOKIE['summon_user_type_name'];
             $COOKIE_summon_utype=@$_COOKIE['summon_utype'];
             $COOKIE_summon_subtype=@$_COOKIE['summon_subtype'];
             $COOKIE_summon_user_status=@$_COOKIE['summon_user_status'];
             #######################################################
             $SESSION_summon_user_code=@$_SESSION['summon_user_code'];
             $SESSION_summon_company_group=@$_SESSION['summon_company_group'];
             $SESSION_summon_userid=@$_SESSION['summon_userid'];
             $SESSION_summon_user_id=@$_SESSION['summon_user_id'];
             $SESSION_summon_useridx=@$_SESSION['summon_useridx'];
             $SESSION_summon_user_idx=@$_SESSION['summon_user_idx'];
             $SESSION_summon_uname=@$_SESSION['summon_uname'];
             $SESSION_summon_user_name=@$_SESSION['summon_user_name'];
             $SESSION_summon_fullname=@$_SESSION['summon_fullname'];
             $SESSION_summon_user_type_id=@$_SESSION['summon_user_type_id'];
             $SESSION_summon_user_type_name=@$_SESSION['summon_user_type_name'];
             $SESSION_summon_utype=@$_SESSION['summon_utype'];
             $SESSION_summon_subtype=@$_SESSION['summon_subtype'];
             $SESSION_summon_user_status=@$_SESSION['summon_user_status'];
             #######################################################
             $user_idx=@$SESSION_summon_user_idx;
             if($user_idx==null){$user_idx= $COOKIE_summon_user_idx;}
             $userid=@$SESSION_summon_user_id; 
             if($userid==null){$userid= $COOKIE_summon_user_id;}
             $user_id=@$SESSION_summon_user_id; 
             if($user_id==null){$user_id= $COOKIE_summon_user_id;}
             $uname=@$SESSION_summon_uname; 
             if($uname==null){$uname= $COOKIE_summon_uname;}
             $username=@$SESSION_summon_uname; 
             if($username==null){$uname= $COOKIE_summon_uname;}
             $company_group=@$SESSION_summon_company_group; 
             if($company_group==null){$uname= $COOKIE_summon_company_group;}
             $fullname=@$SESSION_summon_fullname; 
             if($fullname==null){$fullname= $COOKIE_summon_fullname;}
             $user_type_id=$SESSION_summon_user_type_id; 
             if($user_type_id==null){$user_type_id= $COOKIE_summon_user_type_id;} 
             $user_type_name=@$SESSION_summon_user_type_name; 
             if($user_type_name==null){$user_type_name= $COOKIE_summon_user_type_name;} 
             ################summon########################
             $school_code=@$SESSION['summon_school_code'];
             if($school_code==null){ $school_code=@$_COOKIE['summon_school_code'];} 
             
             $school_name=@$SESSION['summon_school_name'];
             if($school_name==null){ $school_name=@$_COOKIE['summon_school_name'];} 
              
             $school_province=@$SESSION['summon_school_province'];
             if($school_province==null){ $school_province=@$_COOKIE['summon_school_province'];} 
              
             $position=@$SESSION['summon_position'];
             if($position==null){$position=@$_COOKIE['summon_position'];} 
        }else{
            $SESSION_user_id=@$SESSION['user_id'];
             #################################
             $SESSION_user_code=@$SESSION['user_code'];
             $SESSION_company_group=@$SESSION['company_group'];
             $SESSION_userid=@$SESSION['userid'];
             $SESSION_user_id=@$SESSION['user_id'];
             #echo'<hr><pre> SESSION_user_id=>';print_r($SESSION_user_id);echo'</pre>';  die;
             $SESSION_useridx=@$SESSION['useridx'];
             $SESSION_user_idx=@$SESSION['user_idx'];
             $SESSION_uname=@$SESSION['uname'];
             $SESSION_user_name=@$SESSION['user_name'];
             $SESSION_fullname=@$SESSION['fullname'];
             $SESSION_user_type_id=@$SESSION['user_type_id'];
             $SESSION_user_type_name=@$SESSION['user_type_name'];
             $SESSION_utype=@$SESSION['utype'];
             $SESSION_subtype=@$SESSION['subtype'];
             $SESSION_user_status=@$SESSION['user_status'];
             #################################
             $cookie_user_code=@$_COOKIE['user_code'];
             $cookie_company_group=@$_COOKIE['company_group'];
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
             $cookie_subtype=@$_COOKIE['subtype'];
             $cookie_user_status=@$_COOKIE['user_status'];
             #################################
                 $user_idx=$SESSION_user_idx;
                 if($user_idx==null){$user_idx=$cookie_user_idx;}
                 $userid=@$SESSION_user_id; 
                 if($userid==null){$userid=@$cookie_user_id;}
                 $user_id=@$SESSION_user_id; 
                 if($user_id==null){$user_id=@$cookie_user_id;}
                 $uname=@$SESSION_uname; 
                 if($uname==null){$uname=@$cookie_uname;}
                 $username=@$SESSION_uname; 
                 if($username==null){$uname=@$cookie_uname;}
                 $company_group=@$SESSION_company_group; 
                 if($company_group==null){$uname=@$cookie_company_group;}
                 $fullname=@$SESSION_fullname; 
                 if($fullname==null){$fullname=@$cookie_fullname;}
                 $user_type_id=@$SESSION_user_type_id; 
                 if($user_type_id==null){$user_type_id=@$cookie_user_type_id;} 


                 $user_type_name=@$SESSION_user_type_name; 
                 if($user_type_name==null){$user_type_name=@$cookie_user_type_name;} 


                 $position=@$SESSION['position'];
                 if($position==null){$position=@$_COOKIE['position'];} 

                 $school_code=@$SESSION['school_code'];
                if($school_code==null){ $school_code=@$_COOKIE['school_code'];} 
                
                $school_name=@$SESSION['school_name'];
                if($school_name==null){ $school_name=@$_COOKIE['school_name'];} 
                
                $school_province=@$SESSION['school_province'];
                if($school_province==null){ $school_province=@$_COOKIE['school_province'];} 
              
                 
        }

     #if($debug==1){  echo'<hr><pre> SESSION_user_id=>';print_r($SESSION_user_id);echo'</pre>'; }

     $this->config->load('encryptkey'); 
     $setoption=$this->config->item('setoption'); 
      
    ############################
     if($setoption==0){   
        if($debug==1){ echo' <hr> case 1 <pre> user_type_id=>';print_r($user_type_id);echo'</pre>'; }           

     }else{
        $user_type_id=$this->accessuser_library->decryptCookie($user_type_id);
        if($debug==1){ echo' <hr> case 2 <pre> user_type_id=>';print_r($user_type_id);echo'</pre>';  }
        
            ########################################################################
            $SESSION_summon_user_id=@$_SESSION['summon_user_id'];
            $SESSION_summon_user_id=$this->accessuser_library->decryptCookie($SESSION_summon_user_id);
           # echo'2<pre> SESSION_summon_user_id=>';print_r($SESSION_summon_user_id);echo'</pre>'; 
            if($SESSION_summon_user_id==null){
                #################################
                $SESSION_user_code=@$SESSION['user_code'];
                $SESSION_user_code=$this->accessuser_library->decryptCookie($SESSION_user_code);
                $SESSION_company_group=@$SESSION['company_group'];
                $SESSION_company_group=$this->accessuser_library->decryptCookie($SESSION_company_group);
                $SESSION_userid=@$SESSION['userid'];
                $SESSION_userid=$this->accessuser_library->decryptCookie($SESSION_userid);
                $SESSION_user_id=@$SESSION['user_id'];
                $SESSION_user_id=$this->accessuser_library->decryptCookie($SESSION_user_id);
                $SESSION_useridx=@$SESSION['useridx'];
                $SESSION_useridx=$this->accessuser_library->decryptCookie($SESSION_useridx);
                $SESSION_user_idx=@$SESSION['user_idx'];
                $SESSION_user_idx=$this->accessuser_library->decryptCookie($SESSION_user_idx);
                $SESSION_uname=@$SESSION['uname'];
                $SESSION_uname=$this->accessuser_library->decryptCookie($SESSION_uname);
                $SESSION_user_name=@$SESSION['user_name'];
                $SESSION_user_name=$this->accessuser_library->decryptCookie($SESSION_user_name);
                $SESSION_fullname=@$SESSION['fullname'];
                $SESSION_fullname=$this->accessuser_library->decryptCookie($SESSION_fullname);
                $SESSION_user_type_id=@$SESSION['user_type_id'];
                $SESSION_user_type_id=$this->accessuser_library->decryptCookie($SESSION_user_type_id);
                $SESSION_user_type_name=@$SESSION['user_type_name'];
                $SESSION_user_type_name=$this->accessuser_library->decryptCookie($SESSION_user_type_name);
                $SESSION_utype=@$SESSION['utype'];
                $SESSION_utype=$this->accessuser_library->decryptCookie($SESSION_utype);
                $SESSION_subtype=@$SESSION['subtype'];
                $SESSION_subtype=$this->accessuser_library->decryptCookie($SESSION_subtype);
                $SESSION_user_status=@$SESSION['user_status'];
                $SESSION_user_status=$this->accessuser_library->decryptCookie($SESSION_user_status);
                #################################
                $cookie_user_code=@$_COOKIE['user_code'];
                $cookie_user_code=$this->accessuser_library->decryptCookie($cookie_user_code);
                $cookie_company_group=@$_COOKIE['company_group'];
                $cookie_company_group=$this->accessuser_library->decryptCookie($cookie_company_group);
                $cookie_userid=@$_COOKIE['userid'];
                $cookie_userid=$this->accessuser_library->decryptCookie($cookie_userid);
                $cookie_user_id=@$_COOKIE['user_id'];
                $cookie_user_id=$this->accessuser_library->decryptCookie($cookie_user_id);
                $cookie_useridx=@$_COOKIE['useridx'];
                $cookie_useridx=$this->accessuser_library->decryptCookie($cookie_useridx);
                $cookie_user_idx=@$_COOKIE['user_idx'];
                $cookie_user_idx=$this->accessuser_library->decryptCookie($cookie_user_idx);
                $cookie_uname=@$_COOKIE['uname'];
                $cookie_uname=$this->accessuser_library->decryptCookie($cookie_uname);
                $cookie_user_name=@$_COOKIE['user_name'];
                $cookie_user_name=$this->accessuser_library->decryptCookie($cookie_user_name);
                $cookie_fullname=@$_COOKIE['fullname'];
                $cookie_fullname=$this->accessuser_library->decryptCookie($cookie_fullname);
                $cookie_user_type_id=@$_COOKIE['user_type_id'];
                $cookie_user_type_id=$this->accessuser_library->decryptCookie($cookie_user_type_id);
                $cookie_user_type_name=@$_COOKIE['user_type_name'];
                $cookie_user_type_name=$this->accessuser_library->decryptCookie($cookie_user_type_name);
                $cookie_utype=@$_COOKIE['utype'];
                $cookie_utype=$this->accessuser_library->decryptCookie($cookie_utype);
                $cookie_subtype=@$_COOKIE['subtype'];
                $cookie_subtype=$this->accessuser_library->decryptCookie($cookie_subtype);
                $cookie_user_status=@$_COOKIE['user_status'];
                $cookie_user_status=$this->accessuser_library->decryptCookie($cookie_user_status);
                #################################
                $school_code=@$SESSION['school_code'];
                $school_code=$this->accessuser_library->decryptCookie($school_code);
                
                $school_name=@$SESSION['school_name'];
                $school_name=$this->accessuser_library->decryptCookie($school_name);
                 
                $school_province=@$SESSION['school_province'];
                $school_province=$this->accessuser_library->decryptCookie($school_province);
                
                $position=@$SESSION['position'];
                if($position==null){$position=@$_COOKIE['position'];} 
                $position=$this->accessuser_library->decryptCookie($position);
                #################################
                    $user_idx=$SESSION_user_idx;
                    if($user_idx==null){$user_idx=$cookie_user_idx;}
                    $userid=@$SESSION_user_id; 
                    if($userid==null){$userid=@$cookie_user_id;}
                    $user_id=@$SESSION_user_id; 
                    if($user_id==null){$user_id=@$cookie_user_id;}
                    $uname=@$SESSION_uname; 
                    if($uname==null){$uname=@$cookie_uname;}
                    $username=@$SESSION_uname; 
                    if($username==null){$uname=@$cookie_uname;}
                    $company_group=@$SESSION_company_group; 
                    if($company_group==null){$uname=@$cookie_company_group;}
                    $fullname=@$SESSION_fullname; 
                    if($fullname==null){$fullname=@$cookie_fullname;}
                    $user_type_id=@$SESSION_user_type_id; 
                    if($user_type_id==null){$user_type_id=@$cookie_user_type_id;} 
                    $user_type_name=@$SESSION_user_type_name; 
                    if($user_type_name==null){$user_type_name=@$cookie_user_type_name;} 
                #################################
                }else{
                        ################summon########################
                        $COOKIE_summon_user_code=@$_COOKIE['summon_user_code'];
                        $COOKIE_summon_user_code=$this->accessuser_library->decryptCookie($COOKIE_summon_user_code);
                        $COOKIE_summon_company_group=@$_COOKIE['summon_company_group'];
                        $COOKIE_summon_company_group=$this->accessuser_library->decryptCookie($COOKIE_summon_company_group);
                        $COOKIE_summon_userid=@$_COOKIE['summon_userid'];
                        $COOKIE_summon_userid=$this->accessuser_library->decryptCookie($COOKIE_summon_userid);
                        $COOKIE_summon_user_id=@$_COOKIE['summon_user_id'];
                        $COOKIE_summon_user_id=$this->accessuser_library->decryptCookie($COOKIE_summon_user_id);
                        $COOKIE_summon_useridx=@$_COOKIE['summon_useridx'];
                        $COOKIE_summon_useridx=$this->accessuser_library->decryptCookie($COOKIE_summon_useridx);
                        $COOKIE_summon_user_idx=@$_COOKIE['summon_user_idx'];
                        $COOKIE_summon_user_idx=$this->accessuser_library->decryptCookie($COOKIE_summon_user_idx);
                        $COOKIE_summon_uname=@$_COOKIE['summon_uname'];
                        $COOKIE_summon_uname=$this->accessuser_library->decryptCookie($COOKIE_summon_uname);
                        $COOKIE_summon_user_name=@$_COOKIE['summon_user_name'];
                        $COOKIE_summon_user_name=$this->accessuser_library->decryptCookie($COOKIE_summon_user_name);
                        $COOKIE_summon_fullname=@$_COOKIE['summon_fullname'];
                        $COOKIE_summon_fullname=$this->accessuser_library->decryptCookie($COOKIE_summon_fullname);
                        $COOKIE_summon_user_type_id=@$_COOKIE['summon_user_type_id'];
                        $COOKIE_summon_user_type_id=$this->accessuser_library->decryptCookie($COOKIE_summon_user_type_id);
                        $COOKIE_summon_user_type_name=@$_COOKIE['summon_user_type_name'];
                        $COOKIE_summon_user_type_name=$this->accessuser_library->decryptCookie($COOKIE_summon_user_type_name);
                        $COOKIE_summon_utype=@$_COOKIE['summon_utype'];
                        $COOKIE_summon_utype=$this->accessuser_library->decryptCookie($COOKIE_summon_utype);
                        $COOKIE_summon_subtype=@$_COOKIE['summon_subtype'];
                        $COOKIE_summon_subtype=$this->accessuser_library->decryptCookie($COOKIE_summon_subtype);
                        $COOKIE_summon_user_status=@$_COOKIE['summon_user_status'];
                        $COOKIE_summon_user_status=$this->accessuser_library->decryptCookie($COOKIE_summon_user_status);
                        #######################################################
                        $SESSION_summon_user_code=@$_SESSION['summon_user_code'];
                        $SESSION_summon_user_code=$this->accessuser_library->decryptCookie($SESSION_summon_user_code);
                        $SESSION_summon_company_group=@$_SESSION['summon_company_group'];
                        $SESSION_summon_company_group=$this->accessuser_library->decryptCookie($SESSION_summon_company_group);
                        $SESSION_summon_userid=@$_SESSION['summon_userid'];
                        $SESSION_summon_userid=$this->accessuser_library->decryptCookie($SESSION_summon_userid);
                        $SESSION_summon_user_id=@$_SESSION['summon_user_id'];
                        $SESSION_summon_user_id=$this->accessuser_library->decryptCookie($SESSION_summon_user_id);
                        $SESSION_summon_useridx=@$_SESSION['summon_useridx'];
                        $SESSION_summon_useridx=$this->accessuser_library->decryptCookie($SESSION_summon_useridx);
                        $SESSION_summon_user_idx=@$_SESSION['summon_user_idx'];
                        $SESSION_summon_user_idx=$this->accessuser_library->decryptCookie($SESSION_summon_user_idx);
                        $SESSION_summon_uname=@$_SESSION['summon_uname'];
                        $SESSION_summon_uname=$this->accessuser_library->decryptCookie($SESSION_summon_uname);
                        $SESSION_summon_user_name=@$_SESSION['summon_user_name'];
                        $SESSION_summon_user_name=$this->accessuser_library->decryptCookie($SESSION_summon_user_name);
                        $SESSION_summon_fullname=@$_SESSION['summon_fullname'];
                        $SESSION_summon_fullname=$this->accessuser_library->decryptCookie($SESSION_summon_fullname);
                        $SESSION_summon_user_type_id=@$_SESSION['summon_user_type_id'];
                        $SESSION_summon_user_type_id=$this->accessuser_library->decryptCookie($SESSION_summon_user_type_id);
                        $SESSION_summon_user_type_name=@$_SESSION['summon_user_type_name'];
                        $SESSION_summon_user_type_name=$this->accessuser_library->decryptCookie($SESSION_summon_user_type_name);
                        $SESSION_summon_utype=@$_SESSION['summon_utype'];
                        $SESSION_summon_utype=$this->accessuser_library->decryptCookie($SESSION_summon_utype);
                        $SESSION_summon_subtype=@$_SESSION['summon_subtype'];
                        $SESSION_summon_subtype=$this->accessuser_library->decryptCookie($SESSION_summon_subtype);
                        $SESSION_summon_user_status=@$_SESSION['summon_user_status'];
                        $SESSION_summon_user_status=$this->accessuser_library->decryptCookie($SESSION_summon_user_status);
                        #######################################################

                        $user_idx=@$SESSION_summon_user_idx;
                        if($user_idx==null){$user_idx= $COOKIE_summon_user_idx;}
                        $userid=@$SESSION_summon_user_id; 
                        if($userid==null){$userid= $COOKIE_summon_user_id;}
                        $user_id=@$SESSION_summon_user_id; 
                        if($user_id==null){$user_id= $COOKIE_summon_user_id;}
                        $uname=@$SESSION_summon_uname; 
                        if($uname==null){$uname= $COOKIE_summon_uname;}
                        $username=@$SESSION_summon_uname; 
                        if($username==null){$uname= $COOKIE_summon_uname;}
                        $company_group=@$SESSION_summon_company_group; 
                        if($company_group==null){$uname= $COOKIE_summon_company_group;}
                        $fullname=@$SESSION_summon_fullname; 
                        if($fullname==null){$fullname= $COOKIE_summon_fullname;}
                        $user_type_id=$SESSION_summon_user_type_id; 
                        if($user_type_id==null){$user_type_id= $COOKIE_summon_user_type_id;} 
                        $user_type_name=@$SESSION_summon_user_type_name; 
                        if($user_type_name==null){$user_type_name= $COOKIE_summon_user_type_name;} 
                        ################summon########################
                        #################################
                        $school_code=@$SESSION['summon_school_code'];
                        $school_code=$this->accessuser_library->decryptCookie($school_code);
                         
                        $school_name=@$SESSION['summon_school_name'];
                        $school_name=$this->accessuser_library->decryptCookie($school_name);
                         
                        $school_province=@$SESSION['summon_school_province'];
                        $school_province=$this->accessuser_library->decryptCookie($school_province);
                         
                        $position=@$SESSION['summon_position'];
                        if($position==null){$position=@$_COOKIE['summon_position'];} 
                        $position=$this->accessuser_library->decryptCookie($position);
                        #################################
                    }
            ########################################################################                            

        }
    ############################  
		$arr_redirect = array('register','login','forgot_password');
		if (in_array($this->router->method, $arr_redirect) && isset($_SESSION['username'])){
			redirect(index_page(),'refresh');
			die;
		    }		
        #echo'<hr><pre> setoption=>';print_r($setoption);echo'</pre>'; 
        #echo'<hr><pre> school_code=>';print_r($school_code);echo'</pre>'; 
        #echo'<hr><pre> user_id=>';print_r($user_id);echo'</pre>'; 
		$is_Admin=@$this->check_admin($user_type_id);
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
        if($debug==1){  
        echo'<hr><pre>  setoption=>';print_r($setoption);echo'</pre>'; 
        echo'<hr><pre>  this->isAdmin=>';print_r($this->isAdmin);echo'</pre>'; 
        echo'<hr><pre>  this->user_type_id==>';print_r($this->user_type_id);echo'</pre>'; 
        echo'<hr><pre>  this->user_type_name=>';print_r($this->user_type_name);echo'</pre>'; 
        echo'<hr><pre>  this->user_id=>';print_r($this->user_id);echo'</pre>'; 
        echo'<hr><pre>  this->username=>';print_r($this->username);echo'</pre>'; 
        echo'<hr><pre>  this->user_type=>';print_r($this->user_type);echo'</pre>'; 
        echo'<hr><pre>  this->user_fullname=>';print_r($this->user_fullname);echo'</pre>';  
        echo'<hr><pre>  this->user_company=>';print_r($this->user_company);echo'</pre>'; 
        echo'<hr><pre>  this->user_code=>';print_r($this->user_code);echo'</pre>'; 
        echo'<hr><pre>  this->position=>';print_r($this->position);echo'</pre>'; 
        echo'<hr><pre>  this->school_code=>';print_r($this->school_code);echo'</pre>'; 
        echo'<hr><pre>  this->school_name=>';print_r($this->school_name);echo'</pre>'; 
        echo'<hr><pre>  this->school_province=>';print_r($this->school_province);echo'</pre>';  
        #die();
         }
        $this->Access();
		// $this->user_avatar = $this->connexted_library->checkImageExists("connexted/assets/images/user/icttalent/", $this->user_id, "png");
		$user_info = $this->connexted_model->queryUser(['user_id' => $this->user_id])->row();
		$this->user_avatar = $user_info->profile_image ? $user_info->profile_image : base_url('assets/connexted/assets/images/user/face_default.png');
		// echo'<!-- <pre>  user_info=>';print_r($this->user_avatar);echo'<pre>-->';  
		$this->link_target = $this->agent->is_mobile() ? "_self" : "_blank";
		// $this->set_assetLink('library/bootstrap-4.1.0/dist/css/bootstrap.min.css');
		$this->set_assetLink('library/sweetalert2-7.25.6/dist/sweetalert2.css');
		$this->set_assetLink('library/fontawesome/web-fonts-with-css/css/fontawesome-all.css');
		// $this->set_assetScript('library/jquery-3.3.1.min.js');
		// $this->set_assetScript('library/bootstrap-4.1.0/dist/js/bootstrap.min.js');
		$this->set_assetScript('library/sweetalert2-7.25.6/dist/sweetalert2.min.js');
    }
##########Access Start #################
protected function Access($isNotAccess=false){
        $input=@$this->input->get();  
        if($input==null){ $input=@$this->input->post(); }
             $urlencrypt=null;
             $allow=null; 
             $debug=@$input['debug'];
             $deletekey=@$input['deletekey'];
            if($debug==1){echo'<hr> deletekey=>'.$deletekey;  }
            $this->permisionbyurloratl($urlencrypt,$allow,$debug,$deletekey);
    }
public function verifyAccessUrl($isNotAccess=false){
        $input=@$this->input->get();  
        if($input==null){ $input=@$this->input->post(); }
            $urlencrypt=null;
            $allow=null; 
            $debug=@$input['debug'];
           $deletekey=@$input['deletekey'];
           #echo'<hr><pre>  debug=>';print_r($debug);echo'</pre>'; 
           if($debug==1){echo'<hr> deletekey=>'.$deletekey;  }
           $this->permisionbyurloratl($urlencrypt,$allow,$debug,$deletekey);
       }
public function permisionbyurloratl($urlencrypt='',$allow='',$debug='',$deletekey=''){
    if($debug==null){ $debug=0; }else{ $debug=1; } 
        if($allow==1){$status_allow=1;}else{$status_allow=0;}
        if($urlencrypt==null){$urlencrypt=null;}
        $isAllowAccess=0;
        $statusaccess=0;
        $statusaccessadmin=0;
        $isNotAccess=false;
        $statusaccess_msg=0;
        #echo'<hr><pre>  status_allow=>';print_r($status_allow);echo'</pre>';  Die();
        $baseurl=base_url(); //for base  URL
        $uri_string=base_url(uri_string());
        $currentURL=current_url(); //for simple URL
        $params=$_SERVER['QUERY_STRING']; //for parameters
        $fullURL=$currentURL . '?' . $params; //full URL with parameter
        $urlcut=str_replace($baseurl,'',$currentURL);
        $urlcurrent_cut=str_replace($baseurl,'',$currentURL);
        $explode_menu_urlcut_count=count(explode('/',$urlcut));
        $explode_current_count=count(explode('/',$urlcut));
        $explode_urlcut_count=count(explode('/',$urlcut));
         $this->load->model("connexted_model");
         $this->load->model("Useraccess_model");
        #########################******************************************#######################
         #########################******************************************#######################
         $this->load->library('Accessuser_library');
         $session_cookie_get=$this->accessuser_library->session_cookie_get();
         $COOKIE=@$session_cookie_get['COOKIE'];
         $SESSION=@$session_cookie_get['SESSION'];
         $_COOKIE=@$session_cookie_get['COOKIE'];
         $_SESSION=@$session_cookie_get['SESSION'];
         $summon_user_idx=@$SESSION['summon_user_idx'];
         $this->config->load('encryptkey'); 
         $setoption=$this->config->item('setoption'); 
         if($debug==1){
             echo'<pre>  setoption=>';print_r($setoption);echo'</pre>'; 
        }
         if($setoption==0){
                 if($summon_user_idx==null){
                                 $userid=@$SESSION['userid']; 
                                 if($userid==null){$userid=@$_COOKIE['userid'];}
                                 $user_id=@$SESSION['user_id']; 
                                 if($user_id==null){$user_id=@$_COOKIE['user_id'];}
                                 $uname=@$SESSION['uname']; 
                                 if($uname==null){$uname=@$_COOKIE['uname'];}
                                 $fullname=@$SESSION['fullname']; 
                                 if($fullname==null){$fullname=@$_COOKIE['fullname'];}
                                 $user_type_id=@$SESSION['user_type_id']; 
                                 if($user_type_id==null){$user_type_id=@$_COOKIE['user_type_id'];} 
                                 $user_type_name=@$SESSION['user_type_name']; 
                                 if($user_type_name==null){$user_type_name=@$_COOKIE['user_type_name'];}  
                                 $utype=@$SESSION['utype']; 
                                 if($utype==null){$utype=@$_COOKIE['utype'];}  
                                 $position=@$SESSION['position']; 
                                 if($position==null){$position=@$_COOKIE['position'];}  
                     
                                 $position=@$SESSION['position']; 
                                 if($position==null){$position=@$_COOKIE['position'];}  
                     
                                 $school_code=@$SESSION['school_code']; 
                                 if($school_code==null){$school_code=@$_COOKIE['school_code'];}  
                     
                                 $school_name=@$SESSION['school_name']; 
                                 if($school_name==null){$position=@$_COOKIE['school_name'];}  
 
                                $school_province=@$SESSION['school_province']; 
                                if($school_province==null){ $position=@$_COOKIE['school_province'];}  
                     
            }else{
                         $userid=@$SESSION['summon_userid']; 
                         if($userid==null){$userid=@$_COOKIE['summon_userid'];}
                         $user_id=@$SESSION['summon_user_id']; 
                         if($user_id==null){$user_id=@$_COOKIE['summon_user_id'];}
                         $uname=@$SESSION['summon_uname']; 
                         if($uname==null){$uname=@$_COOKIE['summon_uname'];}
                         $fullname=@$SESSION['summon_fullname']; 
                         if($fullname==null){$fullname=@$_COOKIE['summon_fullname'];}
                         $user_type_id=@$SESSION['summon_user_type_id']; 
                         if($user_type_id==null){$user_type_id=@$_COOKIE['summon_user_type_id'];} 
                         $user_type_name=@$SESSION['summon_user_type_name']; 
                         if($user_type_name==null){$user_type_name=@$_COOKIE['summon_user_type_name'];}  
                         $utype=@$SESSION['summon_utype']; 
                         if($utype==null){$utype=@$_COOKIE['summon_utype'];}  
                         
                         $position=@$SESSION['summon_position']; 
                         if($position==null){$position=@$_COOKIE['summon_position'];}  
 
                         $school_code=@$SESSION['summon_school_code']; 
                         if($school_code==null){$school_code=@$_COOKIE['summon_school_code'];}  
 
                         $school_name=@$SESSION['summon_school_name']; 
                         if($school_name==null){$position=@$_COOKIE['summon_school_name'];}  
 
                         $school_province=@$SESSION['summon_school_province']; 
                         if($school_province==null){$position=@$_COOKIE['summon_school_province'];}  
 
                     }
                     if($debug==1){  echo'<pre> 1 user_type_id=>';print_r($user_type_id);echo'</pre>'; }
         }else{
         ################################################
         
                if($summon_user_idx==null){
                        $userid=@$SESSION['userid']; 
                        if($userid==null){$userid=@$_COOKIE['userid'];}
                        $user_id=@$SESSION['user_id']; 
                        if($user_id==null){$user_id=@$_COOKIE['user_id'];}
                        $uname=@$SESSION['uname']; 
                        if($uname==null){$uname=@$_COOKIE['uname'];}
                        $fullname=@$SESSION['fullname']; 
                        if($fullname==null){$fullname=@$_COOKIE['fullname'];}
                        $user_type_id=@$SESSION['user_type_id']; 
                        if($user_type_id==null){$user_type_id=@$_COOKIE['user_type_id'];} 
                        $user_type_name=@$SESSION['user_type_name']; 
                        if($user_type_name==null){$user_type_name=@$_COOKIE['user_type_name'];}  
                        $utype=@$SESSION['utype']; 
                        if($utype==null){$utype=@$_COOKIE['utype'];}  
                        $position=@$SESSION['position']; 
                        if($position==null){$position=@$_COOKIE['position'];}  
                        #################### 
    
                        $school_code=@$SESSION['school_code']; 
                        if($school_code==null){$school_code=@$_COOKIE['school_code'];}  
    
                        $school_name=@$SESSION['school_name']; 
                        if($school_name==null){$position=@$_COOKIE['school_name'];}  
    
                        $school_province=@$SESSION['school_province']; 
                        if($school_province==null){$position=@$_COOKIE['school_province'];}  
    
                        $user_id=$this->accessuser_library->decryptCookie($user_id);
                        $uname=$this->accessuser_library->decryptCookie($uname);
                        $user_type_id=$this->accessuser_library->decryptCookie($user_type_id);
                        $user_type_name=$this->accessuser_library->decryptCookie($user_type_name);
                        $utype=$this->accessuser_library->decryptCookie($utype);
                        $position=$this->accessuser_library->decryptCookie($position);
                        $school_code=$this->accessuser_library->decryptCookie($school_code);
                        $school_name=$this->accessuser_library->decryptCookie($school_name);
                        $school_province=@$this->accessuser_library->decryptCookie($school_province);
                        $fullname=@$this->accessuser_library->decryptCookie($fullname);
                     }else{
                         $userid=@$SESSION['summon_userid']; 
                         if($userid==null){$userid=@$_COOKIE['summon_userid'];}
                         $user_id=@$SESSION['summon_user_id']; 
                         if($user_id==null){$user_id=@$_COOKIE['summon_user_id'];}
                         $uname=@$SESSION['summon_uname']; 
                         if($uname==null){$uname=@$_COOKIE['summon_uname'];}
                         $fullname=@$SESSION['summon_fullname']; 
                         if($fullname==null){$fullname=@$_COOKIE['summon_fullname'];}
                         $user_type_id=@$SESSION['summon_user_type_id']; 
                         if($user_type_id==null){$user_type_id=@$_COOKIE['summon_user_type_id'];} 
                         $user_type_name=@$SESSION['summon_user_type_name']; 
                         if($user_type_name==null){$user_type_name=@$_COOKIE['summon_user_type_name'];}  
                         $utype=@$SESSION['summon_utype']; 
                         if($utype==null){$utype=@$_COOKIE['summon_utype'];}  
                         $position=@$SESSION['position']; 
                         if($position==null){$position=@$_COOKIE['position'];}  
                        ####################
                         $school_code=@$SESSION['summon_school_code']; 
                         if($school_code==null){$school_code=@$_COOKIE['summon_school_code'];}  
                         $school_name=@$SESSION['summon_school_name']; 
                         if($school_name==null){$position=@$_COOKIE['summon_school_name'];}  
                         $school_province=@$SESSION['summon_school_province']; 
                         if($school_province==null){$position=@$_COOKIE['summon_school_province'];}  
                        $user_id=$this->accessuser_library->decryptCookie($user_id);
                        $uname=$this->accessuser_library->decryptCookie($uname);
                        $user_type_id=$this->accessuser_library->decryptCookie($user_type_id);
                        $user_type_name=$this->accessuser_library->decryptCookie($user_type_name);
                        $utype=$this->accessuser_library->decryptCookie($utype);
                        $position=$this->accessuser_library->decryptCookie($position);
                        $school_code=$this->accessuser_library->decryptCookie($school_code);
                        $school_name=$this->accessuser_library->decryptCookie($school_name);
                        $school_province=@$this->accessuser_library->decryptCookie($school_province);
                        $fullname=@$this->accessuser_library->decryptCookie($fullname);
                     }
         ################################################     
         if($debug==1){  echo'<pre> 2 user_type_id=>';print_r($user_type_id);echo'</pre>';  }
     }
    #########################******************************************#######################
    $user_type_id_ses=$user_type_id;
         if($user_type_id==null){
                    $urldirec=base_url('user/dashboard/');
                    echo ("<script LANGUAGE='JavaScript'>
                        window.alert(' user_type_id is null Forbidden, Access Denied ,ปฏิเสธการเข้าถึงระบบ ');
                        window.location.href='$urldirec';
                        </script>"
                    ); 
                    die(); 
                }
         if($debug==1){ echo' <pre>  user_type_id=>';print_r($user_type_id);echo'</pre>';  }
         $requests=array('user_type_id' => $user_type_id_ses);
         #$menusaccessed=$this->Useraccess_model->getUserTypeAccessMenu($requests, $groups=array(), $selects=array(),$debug,$deletekey);
        #echo'<pre>  explode_menu_urlcut_count=>';print_r($explode_menu_urlcut_count);echo'</pre>';
        if($urlencrypt==null){
            $urldecrypttime=null;
            $urlcuta2=null;
        }else{
            ########################
            #$url='http://connexted.local/asm/material';
            #$urlencrypt=$this->base64encrypttime($url,null,null);
            ########################
            $urldecrypttimect=$this->base64decrypttime($urlencrypt,null);
            $urldecrypttime=$urldecrypttimect['data'];
            $urlcuta2=str_replace($baseurl,'',$urldecrypttime); 
         } 
        if($debug==1){
            echo'<pre>  isAllowAccess=>';print_r($isAllowAccess);echo'</pre>';
            echo'<pre>  urlcurrent_cut=>';print_r($urlcurrent_cut);echo'</pre>';
            echo'<pre>  urlcuta_segment_count=>';print_r($explode_menu_urlcut_count);echo'</pre>';
         }
        ###########################################################################################################
        $urlcall=@$this->input->get('urlcall');
        if($urlcall==null){$urlnow=$urlcurrent_cut;}else{$urlnow=$urlcall;}
        $cache_type=2;
        if($debug==1){  echo'<hr><pre>cache_type=>';print_r($cache_type);echo'</pre>'; 
                        echo'<pre>deletekey=>';print_r($deletekey);echo'</pre>'; 
                        echo'<pre>urlnow=>';print_r($urlnow);echo'</pre>'; 
                        echo'<pre>  user_type_id=>';print_r($user_type_id);echo'</pre>';
                         }
        $menuidins=$this->accessuser_library->menuusertypev2($user_type_id,$cache_type,$deletekey,$urlnow);
        $menuidin=$menuidins['list'];
        $access_status=$menuidins['access_status'];
        $access_status_url=$menuidins['access_status_url'];
        $mainlistnow=$menuidins['mainlistnow'];
        $submenulisturlnow=$menuidins['submenulisturlnow'];
        $urlnow=$menuidins['urlnow'];
        $cache_msg=$menuidins['cache_msg'];
        $cache_key=$menuidins['cache_key'];
        $cachetime=$menuidins['cachetime'];
        $cache_day=$menuidins['cache_day'];
        $user_type_id=$menuidins['user_type_id'];
        $cache_type=$menuidins['cache_type'];
        $deletekey=$menuidins['deletekey'];
        $user_idx=$menuidins['user_idx']; 
        $menusaccessed_user=$menuidins['menusaccessed_user']; 
        $accesse_url=$menuidins['accesse_url']; 
        $menu_user_arr=$menuidins['menu_user_arr']; 
        $menu_access_url=$menuidins['menu_access_url']; 
        $urlnow_explode_ar_0=$menuidins['urlnow_explode_ar_0']; 
        $urlnow_explode_ar_1=$menuidins['urlnow_explode_ar_1']; 
        $urlnow_explode_ar_2=$menuidins['urlnow_explode_ar_2']; 
        $urlnow_explode_ar_3=$menuidins['urlnow_explode_ar_3']; 
        $urlnow_explode_ar_4=$menuidins['urlnow_explode_ar_4']; 
        $urlnow_explode_ar_5=$menuidins['urlnow_explode_ar_5']; 
        $accesse_url_arr_in_current=$menuidins['accesse_url_arr_in_current']; 
        $accesse_url_arr_in_current_status=$menuidins['accesse_url_arr_in_current_status']; 
        $accesse_url_current=$menuidins['accesse_url_current']; 
        $accesse_url_merge=$menuidins['accesse_url_merge']; 
        if($debug==1){
            $alss=array('url'=> $urlnow,
                        'urlnow_explode_ar_0'=> $urlnow_explode_ar_0,
                        'urlnow_explode_ar_1'=> $urlnow_explode_ar_1,
                        'urlnow_explode_ar_2'=> $urlnow_explode_ar_2,
                        'urlnow_explode_ar_3'=> $urlnow_explode_ar_3,
                        'urlnow_explode_ar_4'=> $urlnow_explode_ar_4,
                        'urlnow_explode_ar_5'=> $urlnow_explode_ar_5,
                        'user_type_id'=>(int)$user_type_id,
                        #'data'=>$menuidin,
                        # 'data_count'=>count($menuidin),
                        # 'mainlistnow'=>$mainlistnow,
                        # 'submenulisturlnow'=> $submenulisturlnow,
                        'access_status'=> $access_status,
                        'access_status_url'=> $access_status_url,
                        /*
                        'cache_msg'=> $cache_msg,
                        'cache_key'=> $cache_key,
                        'cachetime'=> $cachetime,
                        'cache_day'=> $cache_day,
                        'cache_type'=> $cache_type,
                        'deletekey'=> $deletekey,
                        'user_idx'=> $user_idx,
                        */
                        #'menusaccessed_user'=> $menusaccessed_user,
                        'menusaccessed_user_count'=>(int)count($menusaccessed_user),
                        'accesse_url'=>$accesse_url,
                        'accesse_url_arr_in_current_status'=>$accesse_url_arr_in_current_status,
                        'accesse_url_arr_in_current'=>$accesse_url_arr_in_current,
                        # 'menu_user_arr'=>$menu_user_arr,
                        'accesse_url_count'=>(int)count($accesse_url),
                        #'menu_access_url'=>$menu_access_url,
                    );
                    echo'<pre> alss=>';print_r($alss);echo'</pre>';
            }
            $access_status=$menuidins['access_status'];
            $access_status_url=$menuidins['access_status_url'];
            if($access_status==1){
                $isAllowAccess=$access_status;
            }if($access_status_url==1){
                $isAllowAccess=$access_status_url;
            }
            if($debug==1){ 
                echo'<pre>  access_status=>';print_r($access_status);echo'</pre>';
                echo'<pre>  access_status_url=>';print_r($access_status_url);echo'</pre>'; 
                echo'<pre>  isAllowAccess=>';print_r($isAllowAccess);echo'</pre>'; 
                }
        $isAllowAccess=$accesse_url_arr_in_current_status;
        if($accesse_url_arr_in_current_status==1){
            $statusaccess_msg=' Accessuser_library ->menuusertypev2 status==1';
        }else{
            $statusaccess_msg=' Accessuser_library ->menuusertypev2  status==0';
            }
    ###########################################################################################################
        if($urlencrypt==null){
                #echo'<pre>  urlcuta2=>null';
                $ajaxmsg='ajax access no';
        }else{
                $urldecrypttimect=$this->base64decrypttime($urlencrypt,null);
                $urldecrypttime=$urldecrypttimect['data'];
                $urlcuta2=str_replace($baseurl,'',$urldecrypttime);
                if($urlmodules==$urlcuta2){
                    #echo'<pre>  urlcuta2=>';print_r($urlcuta2);echo'</pre>';
                    $ajaxmsg='ajax access yes';
                    $isAllowAccess=1; $statusaccess=1;
                    }
            }
    ############################
        if($isAllowAccess==0 || $statusaccess==0 || $statusaccessadmin==0|| $status_allow==0){   if($user_type_id_ses==1 ||$user_type_id_ses==2){  $isAllowAccess=1;  $statusaccessadmin=3; }}
        ##########################################################################################################################################
            if($isAllowAccess==1 || $statusaccess==1 || $statusaccessadmin==3 || $status_allow==1 || $accesse_url_arr_in_current_status==2){ 
                    if($debug==1){
                        echo " <hr> Allow Access Menu อนุญาตการเข้าถึงระบบ"; 
                        echo'<pre>  isAllowAccess=>';print_r($isAllowAccess);echo'</pre>';
                        echo'<pre>  statusaccess=>';print_r($statusaccess);echo'</pre>';
                        echo'<pre>  statusaccessadmin=>';print_r($statusaccessadmin);echo'</pre>';
                        echo'<pre>  status_allow=>';print_r($status_allow);echo'</pre>'; 
                        echo'<pre>  statusaccess_msg=>';print_r($statusaccess_msg);echo'</pre>'; 
                        echo'<pre>  accesse_url_arr_in_current_status=>';print_r($accesse_url_arr_in_current_status);echo'</pre>';  
                        echo'<pre>  access_status=>';print_r($access_status);echo'</pre>';
                        echo'<pre>  access_status_url=>';print_r($access_status_url);echo'</pre>'; 
                        echo'<pre>  ajaxmsg=>';print_r($ajaxmsg);echo'</pre>';  
                        echo'<pre>  accesse_url_current=>';print_r($accesse_url_current);echo'</pre>'; 
                        echo'<pre>  accesse_url_merge=>';print_r($accesse_url_merge);echo'</pre>'; 
                        die(); 
                        }
            }else{
                if($debug==1){
                            echo " <hr> Forbidden, Access Denied ,ปฏิเสธการเข้าถึงระบบ";  
                            echo'<pre>  isAllowAccess=>';print_r($isAllowAccess);echo'</pre>';
                            echo'<pre>  statusaccess=>';print_r($statusaccess);echo'</pre>';
                            echo'<pre>  statusaccessadmin=>';print_r($statusaccessadmin);echo'</pre>';
                            echo'<pre>  status_allow=>';print_r($status_allow);echo'</pre>';
                            echo'<pre>  statusaccess_msg=>';print_r($statusaccess_msg);echo'</pre>'; 
                            echo'<pre>  accesse_url_arr_in_current_status=>';print_r($accesse_url_arr_in_current_status);echo'</pre>';  
                            echo'<pre>  access_status=>';print_r($access_status);echo'</pre>';
                            echo'<pre>  access_status_url=>';print_r($access_status_url);echo'</pre>'; 
                            echo'<pre>  ajaxmsg=>';print_r($ajaxmsg);echo'</pre>';  
                            die(); 
                        } 
                            ##########################################
                    $urldirec=base_url('user/dashboard/');
                            echo ("<script LANGUAGE='JavaScript'>
                                window.alert(' Forbidden, Access Denied ,Menu ปฏิเสธการเข้าถึง Module ระบบ ');
                                window.location.href='$urldirec';
                                </script>"
                            ); 
                            die(); 
                    ##########################################
                }
    ##########################################################################################################################################
    }
##########Access End###############
################################################
public function replacetext($find,$replace,$string) {
    $strreplace1=str_replace($find,$replace,$string,$count);
    $strreplace=str_replace($search,$replace,$string);
    // $search คือ คำที่ต้องการลบ
    // $replace คือ คำที่ต้องการใส่แทน
    // $string คือ ค่าตัวแปรที่ส่งมา 

    // รูปแบบ
    // ฟังก์ชัน str_replace()
    // แทนคำในสตริงด้วยคำที่ต้องการ ด้วยฟังก์ชัน str_replace()
    // ฟังก์ชัน str_ireplace() เป็น case-sensitive คือ สนใจตัวอักษรใหญ่และเล็ก
    // str_replace(find , replace , string , count)
    // find คือ คำที่ต้องการลบ
    // replace คือ คำที่ต้องการใส่แทน
    // string คือ สตริง 
    // count คือ ตัวแปรที่รับข้อมูลจำนวนคำที่แทนลงไป (นับเป็นคำ)
        return $strreplace;
    }
public function base64encrypttime($data,$time='',$key='') {
    if($key==null){
        $key=$this->config->item('jwt_key');
    }
    if($time==null){
        $time_setting=20; 
    }else{
        $time_setting=$time;
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
            $dataalls=array('message'=>' Error key ไม่ถูกต้อง','data'=>null,'status'=>0);
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
            $dataalls=array('message'=>$msg_time,'data'=>null,'status'=>0);
            return $dataalls;
            Die();
        }else{
            $msg_time='On time not Expired yet';
            $dataalls=array('message'=>$msg_time,'data'=>$data,'status'=>1);
            return $dataalls;
            Die();
        }
    }   
 
public function base64_encrypt($string, $key) {
		$result='';
		for ($i=0; $i < strlen($string); $i++) {
			$char=substr($string, $i, 1);
			$keychar=substr($key, ($i % strlen($key)) - 1, 1);
			$char=chr(ord($char) + ord($keychar));
			$result.=$char;
		}

		return base64_encode($result);
	}
public function base64_decrypt($string, $key) {
		$result='';
		$string=base64_decode($string);
		for ($i=0; $i < strlen($string); $i++) {
			$char=substr($string, $i, 1);
			$keychar=substr($key, ($i % strlen($key)) - 1, 1);
			$char=chr(ord($char) - ord($keychar));
			$result.=$char;
		}

		return $result;
	}
public function serialize($dataset) {
		$result=serialize($dataset);
		return $result;
	}
public function unserialize_get($dataset) {
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
public function get_gravatar($email, $s=80, $d='mp', $r='g', $img=false, $atts=array() ) {
            $url='https://www.gravatar.com/avatar/';
            $url .= md5( strtolower( trim( $email ) ) );
            $url .= "?s=$s&d=$d&r=$r";
            if ( $img ) {
                $url='<img src="' . $url . '"';
                foreach ( $atts as $key => $val )
                    $url .= ' ' . $key . '="' . $val . '"';
                $url .= ' />';
            }
            return $url;
        } 
public function cleantext($value){  
        # $value=@$this->db->escape_str($value); 
        $search = array("<", ">", "<input", "< input", "input", "onkeydown='", "alert(", " <script>", "<script src=", "</script>", 
    "type=", 
    "<input type=", 
    "Login", 
    "login", 
    "><", 
    "/>",
    "<?", 
    "(",")", 
    "<?php",
    "type=password>",
    "type=text>",'"/>','" />',' </ "',"&","$","@","!","*","_", 
    "?>");
    $replace="";
    $string=$value;
    $value=str_replace($search,$replace,$string);
    $value=str_replace('">','',$string);
    ########################
    #echo'<hr><pre>  value 2 =>';print_r($value);echo'</pre>';Die();
    //Security Class
        // Fix &entity\n;
        $data=$value;
        $data=str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data=preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data=preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data=preg_replace("/[^a-z\d]/i", '', $data);
        $data=preg_replace(array("/\^/", "/%/", "/\)/", "/\(/", "/{/"),"", $data);
        $data=preg_replace("/( )*/","", $data);
        $data=html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+[>\b]?#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do{
                // Remove really unwanted tags
                $old_data = $data;
                $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);
        return $data; 
    }
################################################
}