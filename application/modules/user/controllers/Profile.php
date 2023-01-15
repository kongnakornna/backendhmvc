<?php

class Profile extends Connexted_Controller{ 
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    // public function urlImage($t = ""){
    //     $content = file_get_contents(base_url("api/user/getUser/$t"));
    //     $data    = (array)json_decode($content);

    //     return @$data['profile_image'];
    // }

    // public function index(){
    //     $t = @$this->input->get("t");
    //     $defurl = '';

    //     if($t && $t != -1){
    //         $defurl = $this->urlImage($t);
    //     }

    //     $data['url'] = $defurl;
    //     $this->template_connexed("profile/index" , $data);
    // }

    public function index(){
        $this->template_connexed("profile/user");
    }

    public function passwordChange(){
       $input = $this->input->post();
    //    var_dump(@$input['oldpassword']);
        
    }

    public function edit($user_id=""){
        $data['user_id'] = $user_id;
        
        $user_type = $this->session->userdata('user_type_id');
        $data['admin'] = ($user_type == 1 || $user_type == 2);

        $sql = "select username,password from tbl_user_2018 where user_id=$user_id";
        $q = $this->db->query($sql)->result_array();

        $data['username'] = $q[0]['username'];
        $data['password'] = $q[0]['password'];

        $this->template_connexed("profile/edit",$data);
    }

    public function checkAvalaibleValidPass($pass) {

        $r1='/[A-Z]/';  //Uppercase
        $r2='/[a-z]/';  //lowercase
        $r3='/[!@#$%^&*()\-_=+{};:,<.>]/';  // whatever you mean by 'special char'
        $r4='/[0-9]/';  //numbers

        if(preg_match_all($r1,$pass, $o) < 1) return FALSE;
        if(preg_match_all($r2,$pass, $o) < 1) return FALSE;
        if(preg_match_all($r3,$pass, $o) < 1) return FALSE;
        if(preg_match_all($r4,$pass, $o) < 1) return FALSE;
        if(strlen($pass) < 8) return FALSE;

        return TRUE;
     }
}