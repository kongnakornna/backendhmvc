<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Core_controller extends CI_Controller {
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
##########Access#################
public function verifyAccessUrl($isNotAccess=false){
    $input=@$this->input->get();  
    if($input==null){ $input=@$this->input->post(); }
        $urlencrypt=null;
        $allow=null; 
        $debug=@$input['debug'];
       $deletekey=@$input['deletekey'];
       #echo'<hr><pre>  debug=>';print_r($debug);echo'</pre>'; 
       if($debug==1){echo'<hr> deletekey=>'.$deletekey;  }
        //$this->permisionbyurloratl($urlencrypt,$allow);
        $this->permisionbyurloratl($urlencrypt,$allow,$debug,$deletekey);
       //$this->verifyAccessUrlV1();
   } 
public function Access($isNotAccess=false){
    $input=@$this->input->get();  
    if($input==null){ $input=@$this->input->post(); }
        $urlencrypt=null;
        $allow=null; 
        $debug=@$input['debug'];
       $deletekey=@$input['deletekey'];
       #echo'<hr><pre>  debug=>';print_r($debug);echo'</pre>'; 
       if($debug==1){echo'<hr> deletekey=>'.$deletekey;  }
        //$this->permisionbyurloratl($urlencrypt,$allow);
        $this->permisionbyurloratl($urlencrypt,$allow,$debug,$deletekey);
       //$this->verifyAccessUrlV1();
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
##########Access###############
public function template_schoolPhase2($view = "", $data = array()){
		$user_idx = $this->user_idx;
		$username = $this->username;
		$user_type = $this->user_type;
		$user_type_id=$this->user_type_id;
		$user_type_name=$this->user_type_name;
		$project_name = "Company";
		
		//$data['isAdmin'] = $this->check_admin($user_idx);
		$this->load->view("Templates/".$project_name."/Header", $data);
		$this->load->view($view, $data);
		$this->load->view("Templates/".$project_name."/Footer", $data);		
	}

public function template($view = "", $data = array()){
		$user_idx = $this->user_idx;
		$username = $this->username;
		$user_type = $this->user_type;
		
		$data['isAdmin'] = $this->check_admin($this->user_type_id);
		$this->load->view("Template/Header", $data);
		$this->load->view($view, $data);
		$this->load->view("Template/Footer");
	}

public function template_connexted($view = "", $data = []){
		$assets_html = $this->set_assetsHtml();
		$this->title = "สานพลังประชารัฐ | ด้านการศึกษา";
		$data_template['assets_links'] = $assets_html['assets_link'];
		$data_template['assets_scripts'] = $assets_html['assets_script'];
		$data_template['isAdmin'] = $this->check_admin($this->user_type_id);
		echo "<!-- SEGMENT1: ".$this->uri->segment(1)." -->";
		$this->load->view("Templates/Connexted/Header", $data_template);
		$this->load->view($view, $data);
		$this->load->view("Templates/Connexted/Footer", $data_template);		
	}

public function template_connexed($view = "", $data = []){
		// name fail -_-
		$this->template_connexted($view, $data);
	}

public function check_admin($user_type_id){
		// $user_idx_admin = "4152";
        // $user_idx_admin2 = "4614";  
		// if($user_idx==$user_idx_admin || $user_idx==$user_idx_admin2){
		// 	$isAdmin = true;
		// }else{
		// 	$isAdmin = false;
		// }
		if($user_type_id == 1 || $user_type_id == 2){ 
			$isAdmin=true;
		}else{
			$isAdmin=false;
		}

		return $isAdmin;
	}

public function set_assetLink($link = ""){
        $this->links[] = $link;
    }

public function set_assetScript($script = ""){
        $this->scripts[] = $script;
    }

public function set_assetsHtml(){
		$data['assets_link'] = "";		
        foreach($this->links as $link) {
            $data['assets_link'] .= '<link rel="stylesheet" href="'.base_url($this->assets.$link).'">';
        }

        $data['assets_script'] = "";
        foreach($this->scripts as $script) {
            $data['assets_script'] .= '<script src="'.base_url($this->assets.$script).'"></script>';
		}
		
        return $data;
	}

protected function verifyAccessUrlold($isNotAccess = false){
		$this->load->model("connexted_model");
		#echo'<hr><pre>  $this->user_type_id=>';print_r($this->user_type_id);echo'<pre><hr>';  die();  
		$this->load->library('Accessuser_library');
        $session_cookie_get=$this->accessuser_library->session_cookie_get();
		$COOKIE=@$session_cookie_get['COOKIE'];
        $SESSION=@$session_cookie_get['SESSION'];
		$user_type_id_ses=@$SESSION['user_type_id']; 
		$requests=array('user_type_id' => $this->user_type_id);
		$menus_accessed = $this->connexted_model->getUserTypeAccessMenu($requests, $groups=array(), $selects=array());
		
		// echo'<hr><pre>  menus_accessed=>';print_r($menus_accessed);echo'</pre>';   die;
		
		$isAllowAccess = false;
		$max_segment_url = 0;
		foreach($menus_accessed as $menu){
			$explode_menu_module = count(explode('/', $menu->url));
			$explode_menu_alt = explode(',', $menu->menu_alt);

			$current_url = "";
			for($i = 1; $i <= $explode_menu_module; $i++){
				$prefix_url = $i > 1 && $this->uri->segment($i) ? "/" : "";
				$current_url .= $prefix_url.$this->uri->segment($i);
				#echo'<hr><pre>  current_url=>';print_r($current_url);echo'</pre>';
			}
			// echo'<hr><pre>  current_url=>';print_r($current_url);echo'</pre>';  die;
			if($current_url == $menu->url){
				$isAllowAccess = true;
			}
			// echo $isAllowAccess;
			// if(!$isAllowAccess){
			// 	foreach($explode_menu_alt as $alt){
			// 		if($alt == $user_type_id_ses){
			// 			$isAllowAccess = true;
			// 		} 
			// 	}  
			// }
			$max_segment_url = $explode_menu_module > $max_segment_url ? $explode_menu_module : $max_segment_url;
			// if(strpos(uri_string(), $menu->url) !== false){
			// 	$isAllowAccess = true;
			// }			
		}

		// echo'<hr><pre>  menus_accessed=>';print_r($isAllowAccess);echo'</pre>';

		
		
		//  die;
		
		if(!$isAllowAccess || $isNotAccess){
			$urldirec=base_url('user/dashboard/');
            echo ("<script LANGUAGE='JavaScript'>
                window.alert(' Forbidden, Access Denied ,ปฏิเสธการเข้าถึงระบบ ');
                window.location.href='$urldirec';
                </script>"
            ); 
            die(); 
		}
    }

public function permisionbyurloratlV2018($urlencrypt='',$allow='',$debug='',$deletekey=''){
    #$debug=0;
    #$debug=1;
    if($debug==null){ $debug=0; }else{ $debug=1; } 
        if($allow==1){$status_allow=1;}else{$status_allow=0;}
        if($urlencrypt==null){$urlencrypt=null;}
        /*
        $isAllowAccess=false;
        $statusaccess=false;
        $statusaccessadmin=false;
        $isNotAccess=false;
        */
        $isAllowAccess=0;
        $statusaccess=0;
        $statusaccessadmin=0;
        $isNotAccess=false;
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
        $segment1=$this->uri->segment(1);
        $segment2=$this->uri->segment(2);
        $segment3=$this->uri->segment(3);
        $segment4=$this->uri->segment(4);
        $segment5=$this->uri->segment(5);
        $segment6=$this->uri->segment(6);
        $segment7=$this->uri->segment(7);
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
            #echo'<pre>  urlencrypt=>';print_r($urlencrypt);echo'</pre>';
            #echo'<hr><pre>  urldecrypttime=>';print_r($urldecrypttime);echo'</pre>'; 
            #echo'<pre>  urlcuta2=>';print_r($urlcuta2);echo'</pre>'; 
        } 
        if($debug==1){
            echo'<pre>  isAllowAccess=>';print_r($isAllowAccess);echo'</pre>';
            echo'<pre>  urlcurrent_cut=>';print_r($urlcurrent_cut);echo'</pre>';
            echo'<pre>  urlcuta_segment_count=>';print_r($explode_menu_urlcut_count);echo'</pre>';
        }
        //ยกเว้น  ไม่ check  หรือ public URL
        $urlarray=array('user/dashboard',
                        '/user/dashboard',
                        'user/singout',
                        '/user/singout',
                        'user/historylog',
                        '/user/historylog',
                        'user/profile',
                        '/user/profile',
                    );
        if($urlarray){
            $isAllowAccess=1;
            $statusaccess=1;
            $statusaccessadmin=0;
            $isNotAccess=1;
            $msgacc=' URL ได้รับการยกเว้น อนุญาตการเข้าถึงระบบ';
        }
        if($debug==1){
        echo'<pre>  urlarray=>';print_r($urlarray);echo'</pre>';  
        echo'<pre>  msgacc=>';print_r($msgacc);echo'</pre>';  
        }
        ############################
        $this->load->model("connexted_model");
        $this->load->model("Useraccess_model");
        
        $this->load->library('Accessuser_library');
        $session_cookie_get=$this->accessuser_library->session_cookie_get();
        $COOKIE=@$session_cookie_get['COOKIE'];
        $SESSION=@$session_cookie_get['SESSION'];
        

        if($summon_user_type_id_ses==null){
            $user_type_id_ses=@$SESSION['user_type_id']; 
            if($user_type_id_ses==null){$user_type_id_ses=@$COOKIE['user_type_id']; }
        }else{
            $user_type_id_ses=$summon_user_type_id_ses; 
            if($user_type_id_ses==null){$user_type_id_ses=@$COOKIE['summon_user_type_id']; }
        }

       


        $requests=array('user_type_id' => $user_type_id_ses);
        #$menusaccessed1=$this->connexted_model->getUserTypeAccessMenu($requests, $groups=array(), $selects=array(),$debug,$deletekey);
        $menusaccessed=$this->Useraccess_model->getUserTypeAccessMenu($requests, $groups=array(), $selects=array(),$debug,$deletekey);
        ################################
        if(is_array($menusaccessed)){
            $arr_option=array();
            foreach($menusaccessed as $k=> $vl){
                $menu_id=$vl->menu_id;
                $title=$vl->title;
                $urlmodules=$vl->url;
                $menu_alt=$vl->menu_alt;
                $option=$vl->option;
                $arrss['data']['urlmodules']=$urlmodules;
                #$arrss['data']['option']=$option;
                if($option==2){
                    $arr_option[]=$arrss['data']['urlmodules'];
                }
            }
        }
        if($debug==1){ echo'<hr><pre>  arr_option=>';print_r($arr_option);echo'</pre>';   }
        if($arr_option){
            $isAllowAccess=1;
            $statusaccess=1;
            $statusaccessadmin=0;
            $isNotAccess=1;
            $msgoption='option = 2 อนุญาตการเข้าถึงระบบ';
            if($debug==1){ echo'<pre>  msgoption=>';print_r($msgoption);echo'</pre>';  }
        }
        

        if($debug==1){ echo'<hr><pre>  menusaccessed=>';print_r($menusaccessed);echo'</pre>';   }
        if(is_array($menusaccessed)){
        foreach($menusaccessed as $key=> $val){
            $menu_id=$val->menu_id;
            $menuid=$val->menu_id;
            $title=$val->title;
            $urlmodules=$val->url;
            $parent=$val->parent;
            $menu_alt=$val->menu_alt;
            $option=$val->option;
            $paramsmodules=$val->params;
            $status=$val->status;
            $menu_alt=$val->menu_alt;
            $url_explode= count(explode('/',$urlmodules));
            $menu_alt_explode=explode(',',$menu_alt);
            $explode_menu_module_count=count(explode('/',$urlmodules));			
            $explode_menu_alt=explode(',',$menu_alt);
            ########################################
            /*
            $current_url ="";
            for($i=1; $i <= $explode_menu_module_count; $i++){
                $prefix_url=$i > 1 && $this->uri->segment($i) ? "/" : "";
                $current_url .= $prefix_url.$this->uri->segment($i);
                 #echo'<pre>  currenturl=>';print_r($current_url);echo'</pre>';
            }
            */
            /*
            if($debug==1){
            echo'<pre>  current_url=>';print_r($current_url);echo'</pre>';
            echo'<hr><pre>  current_url=>';print_r($current_url);echo'</pre>';
            }
            */
            #########################################
            if(($urlcurrent_cut==$urlmodules) && ($option==null ||$option==1)){
                $statusaccess_menu_id=$menu_id;
                $statusaccess_msg='urlmodules';
                if($debug==1){
                    echo'<pre>  urlcut=>';print_r($urlcut);echo'</pre>'; 
                    echo'<pre>  menuid=>';print_r($menuid);echo'</pre>';
                    echo'<pre>  urlcurrent_cut=>';print_r($urlcurrent_cut);echo'</pre>';
                    echo'<pre>  urlmodules=>';print_r($urlmodules);echo'<pre> <hr>'; #die();
                }
                    if($user_type_id_ses==1 ||$user_type_id_ses==2){ 
                        $isAllowAccess=1; 
                        $statusaccess=1;
                    }else{
                        if(is_array($explode_menu_alt)){
                            foreach($explode_menu_alt as $alt){
                                $arralt=array();
                                    if($alt==$user_type_id_ses){ 
                                        $altstatus=$alt;
                                        #echo'<pre>  alt=>';print_r($alt);echo'</pre>';
                                        $isAllowAccess=1;
                                        $statusaccess=1;
                                    }
                                }
                        }
                        #echo'<pre> altstatus=>';print_r($altstatus);echo'<pre> <hr>'; #die();
                    }
                    $isAllowAccess=$isAllowAccess;
                    $statusaccess=$statusaccess;
                    if($debug==1){
                        echo'<hr> 0 menuid=>'.$menuid;echo'<br> '; 
                        echo'current urlmodules=>'.$urlmodules;echo'<br> '; 
                        echo'user_type_id_ses=>'.$user_type_id_ses;echo'<br> '; 
                        echo'urlmodules=>'.$urlmodules;echo'<br> '; 
                        echo'explode_menu_module_count=>'.$explode_menu_module_count;echo'<br> '; 
                        echo'urlcut=>'.$urlcut;echo'<br> '; 
                        echo'isAllowAccess=>'.$isAllowAccess;echo'<br> '; 
                        echo'statusaccess=>'.$statusaccess;
                    }	
            #exit();
            }elseif(($urlcurrent_cut!==$urlmodules) && ($option==null ||$option==1)){
                $statusaccess_menu_id=$menu_id;
                $statusaccess_msg='urlcutaallow';
                $urlcuta1="";
                $urlcutaallow="";
                for($i=1; $i <= $explode_menu_urlcut_count; $i++){
                    $segment_no= $i;
                    $prefixurl=$i > 1 && $this->uri->segment($i) ? "/" : "";
                    $urlcuta1 .= $prefixurl.$this->uri->segment($i);
                    if($explode_menu_urlcut_count==1){
                        $urlcutaallow=$segment1;
                    }elseif($explode_menu_urlcut_count==2){
                        #$urlcutaallow=$segment1.'/'.$segment2;
                        $urlcutaallow=$segment1;
                    }elseif($explode_menu_urlcut_count==3){
                        #$urlcutaallow=$segment1.'/'.$segment2.'/'.$segment3;
                        $urlcutaallow=$segment1.'/'.$segment2;
                    }else{
                        $urlcutaallow=$segment1.'/'.$segment2.'/'.$segment3;
                    }
                     #echo'<pre>  urlcuta1=>';print_r($urlcuta1);echo'</pre>';
                     #echo'<pre>  urlcutaallow=>';print_r($urlcutaallow);echo'</pre>';
                }

                #echo'<pre>  urlcuta1=>';print_r($urlcuta1);echo'</pre>';
                #echo'<pre>  urlcutaallow=>';print_r($urlcutaallow);echo'</pre>';

                if($explode_current_count>1){
                    if($explode_current_count==$explode_menu_module_count){
                        if($urlcurrent_cut==$urlcutaallow){
                            $isAllowAccess=1; 
                            $statusaccess=1;
                            if($debug==1){
                                echo'<pre>  1 urlcutaallow=>';print_r($urlcutaallow);echo'</pre>';
                                echo'<pre>  urlcurrent_cut=>';print_r($urlcurrent_cut);echo'</pre>';
                                echo'<pre>  url modules=>';print_r($urlmodules);echo'</pre>';
                                echo'<pre>  explode_current_count==>';print_r($explode_current_count);echo'</pre>';
                                echo'<pre>  explode_menu_module_count==>';print_r($explode_menu_module_count);echo'</pre>';
                            }
                        }
                    }
                    if($urlcutaallow==$urlmodules){
                        $isAllowAccess=1; 
                        $statusaccess=1;
                            if($debug==1){
                                    echo'<pre>  2 url cut aallow=>';print_r($urlcutaallow);echo'</pre>';
                                    echo'<pre>  url modules=>';print_r($urlmodules);echo'<pre><hr>';
                                    echo'<pre>  url cut=>';print_r($urlcut);echo'</pre>';
                                    echo'<pre>  explode urlcut count=>';print_r($explode_urlcut_count);echo'</pre>';
                                    echo'<pre>  explodemenu module count=>';print_r($explode_menu_module_count);echo'</pre>';
                                }
                        }
                } 
                #echo'<pre> ALS  isAllowAccess=>';print_r($isAllowAccess);echo'</pre>';
                #echo'<pre>  isAllowAccess=>';print_r($isAllowAccess);echo'</pre>';
            }
         }
        }
        #Die();
    ###########################################################################################################
    #$url='http://connexted.local/asm/material';$urlencrypt=$this->base64encrypttime($url,null,null);
    ########################
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
    if($isAllowAccess==0 || $statusaccess==0 || $statusaccessadmin==0|| $status_allow==0){ 
        if($user_type_id_ses==1 ||$user_type_id_ses==2){  $isAllowAccess=1;  $statusaccessadmin=3; }
        }
    if($isAllowAccess==1 || $statusaccess==1 || $statusaccessadmin==3 || $status_allow==1){ 
            if($debug==1){
                echo " <hr> Access Denied to the  modules ,สิทธิ์ของท่าน ถูกปฏิเสธการเข้าถึงระบบ ใน Moduls นี้ "; 
                echo'<pre>  isAllowAccess=>';print_r($isAllowAccess);echo'</pre>';
                echo'<pre>  statusaccess=>';print_r($statusaccess);echo'</pre>';
                echo'<pre>  statusaccessadmin=>';print_r($statusaccessadmin);echo'</pre>';
                echo'<pre>  status_allow=>';print_r($status_allow);echo'</pre>'; 
                echo'<pre>  statusaccess_msg=>';print_r($statusaccess_msg);echo'</pre>'; 
                echo'<pre>  statusaccess_menu_id=>';print_r($statusaccess_menu_id);echo'</pre>';  
                echo'<pre>  ajaxmsg=>';print_r($ajaxmsg);echo'</pre>';  
                die(); 
            }
        }else{
            if($debug==1){
                    echo " <hr> Access Denied to the  modules ,สิทธิ์ของท่าน ถูกปฏิเสธการเข้าถึงระบบ ใน Moduls นี้ ";  
                    echo'<pre>  isAllowAccess=>';print_r($isAllowAccess);echo'</pre>';
                    echo'<pre>  statusaccess=>';print_r($statusaccess);echo'</pre>';
                    echo'<pre>  statusaccessadmin=>';print_r($statusaccessadmin);echo'</pre>';
                    echo'<pre>  status_allow=>';print_r($status_allow);echo'</pre>';
                    echo'<pre>  statusaccess_msg=>';print_r($statusaccess_msg);echo'</pre>'; 
                    echo'<pre>  statusaccess_menu_id=>';print_r($statusaccess_menu_id);echo'</pre>';  
                    echo'<pre>  ajaxmsg=>';print_r($ajaxmsg);echo'</pre>';  
                    die(); 
            } 
                    ##########################################
                    $urldirec=base_url('user/dashboard/');
                    echo ("<script LANGUAGE='JavaScript'>
                        window.alert('Access Denied to the  modules ,สิทธิ์ของท่าน ถูกปฏิเสธการเข้าถึงระบบ ใน Moduls นี้ ');
                        window.location.href='$urldirec';
                        </script>"
                    ); 
                    die(); 
            ##########################################
        }
    ##############################################
    #die();
    }
public function get_gravatar($email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array() ) {
        $url= 'https://www.gravatar.com/avatar/';
        $url.= md5( strtolower( trim( $email ) ) );
        $url.= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }
public function defView($name="",$data=[]){
    $user_id=$this->session->userdata("user_id");
    if($user_id){
            $user=$this->db->query("Select * From tbl_user_2018 Where user_id = '$user_id'")->result_array();
            $email=$user[0]['email'];
            $data['gravatar']=$this->get_gravatar($email,35);
        }
        $userid=$this->session->userdata("userid");
        if(!$user_id){
            redirect("asm");  exit();
        }
		$this->load->view("theme/default/header",$data);
        $this->load->view("theme/default/navbar",$data);
        $this->load->view($name,$data);
        $this->load->view("theme/default/footer",$data);
    }
public function adView($name="",$data=[]){
        $role_id = $this->session->userdata("user_status");
        $user_id = $this->session->userdata("user_id");
        if(!$role_id == 1 || !$role_id == 2){
            redirect("user/login"); exit();
        }if($user_id){
            $user    = $this->db->query("Select * From tbl_user_2018 Where user_id = '$user_id'")->result_array();
            $email   =  $user[0]['email'];
            $data['gravatar'] = $this->get_gravatar($email,35);
        }else{
            redirect("user/login"); exit();
        } 
        $this->load->view("theme/admin/header",$data);
        $this->load->view("theme/admin/navbar",$data);
        $this->load->view($name,$data);
        $this->load->view("theme/admin/footer",$data);
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
}