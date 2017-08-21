<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */

class Member_model extends CI_Model{
    private $CI;
    private $edit;
    function __construct() {
    parent::__construct();
    $this->CI = & get_instance();
    $this->edit = $this->load->database('edit', TRUE);
    //$this->load->library('tppymemcached');
  } 
    function get_profile_by_googleid($google_id){
    $sql = "SELECT * FROM users_account WHERE acc_user_google=%google_id%";
    $query = $this->db->query($sql, array(
      '%google_id%'=> $google_id,
    ));
    if($query->num_rows()) {
      return $query->row_array();
    }
    return null;
  }
    function get_profile_by_facebookid($facebook_id){
    $sql = "SELECT * FROM users_account WHERE acc_user_facebook=%facebook_id%";
    $query = $this->db->query($sql, array(
      '%facebook_id%'=> $facebook_id,
    ));
    if($query->num_rows()) {
      return $query->row_array();
    }
    return null;
  }
    function get_profile_by_username_or_email_password($user_username_email, $user_password){
    $sql = "SELECT u.*, 'upload' as folder_path, b.blog_id FROM users_account u
    LEFT OUTER JOIN blog b ON b.member_id = u.member_id
    WHERE (u.user_username=%user_username_email%  AND u.user_password=%user_password%)
    OR (u.user_email=%user_username_email% AND u.user_password=%user_password%)
    ORDER BY u.user_status DESC";
    $query = $this->db->query($sql, array(
      '%user_username_email%'=> $user_username_email,
      '%user_password%'=> md5($user_password),
    ));
    
     if($query->num_rows()) {
        $result = $query->result_array();
          
        if(empty($result['blog_id'])){
          $blog_id = $this->insert_blog($result['user_id'], $result['member_id']);
          $result['blog_id'] = $blog_id;
        }
        return $result;
     }
    return null;
  }
    function get_profile_by_username_password($user_username, $user_password) {
    $sql = "SELECT u.*, 'upload' as folder_path, b.blog_id FROM users_account u
    LEFT OUTER JOIN blog b ON b.member_id = u.member_id
    WHERE u.user_username=%user_username% AND u.user_password=%user_password%";
    $query = $this->db->query($sql, array(
      '%user_username%'=> $user_username,
      '%user_password%'=> $user_password,
    ));
    if($query->num_rows()) {
      return $query->row_array();
    }
    return null;
  }
    function get_profile_by_username($user_username) {
    $sql = "SELECT u.*, 'upload' as folder_path, b.blog_id FROM users_account u
    LEFT OUTER JOIN blog b ON b.member_id = u.member_id
    WHERE user_username=%user_username%  AND user_status=1";
    $query = $this->db->query($sql, array(
      '%user_username%'=> $user_username,
    ));
    if($query->num_rows()) {
      return $query->row_array();
    }
    return null;
  }
    function get_profile_by_email($user_email) {
    $sql = "SELECT u.*, 'upload' as folder_path, b.blog_id FROM users_account u
    LEFT OUTER JOIN blog b ON b.member_id = u.member_id
    WHERE user_email=%user_email% AND user_status=1";
    $query = $this->db->query($sql, array(
      '%user_email%'=> $user_email,
    ));
    if($query->num_rows()) {
      
      
      
      return $query->row_array();
    }
    return null;
  }
    function insert_blog($user_id='', $member_id=''){
    
     $sql = "SELECT * FROM blog WHERE member_id='$member_id'";
     $result = $this->edit->query($sql);

    if($result->num_rows() == 0){

        $sql = "INSERT INTO blog (blog_id, member_id, blog_name, blog_status, blog_last_update, blog_last_login, blog_point, blog_created_date, blog_welcome_msg, setting_theme_name, blog_get_prize, cms_year_path)
        SELECT user_id AS blog_id,member_id AS member_id,psn_display_name AS blog_name,1 AS blog_status,NOW() AS blog_last_update, NOW() AS blog_last_login,0 AS blog_point,NOW() AS blog_created_date,'' AS blog_welcome_msg,'back_to_school' AS setting_theme_name,0 AS blog_get_prize,'product' AS cms_year_path
        FROM users_account WHERE user_id =$user_id";
        $this->edit->query($sql); 

        $sql = "SELECT * FROM blog WHERE member_id='$member_id'";
        $result = $this->edit->query($sql);
        return $result->row()->blog_id;
    }
  }
    function get_profile_by_userid($user_id) {
    $key = "memberprofile_" . $id;
    $this->tppymemcached->delete($key);
    if (!$member = $this->tppymemcached->get($key)) {
      $sql = "SELECT *, 'upload' as folder_path FROM users_account u
      LEFT OUTER JOIN blog ON blog.member_id = u.member_id
      WHERE user_id=%user_id%";
      $query = $this->db->query($sql, array(
        '%user_id%'=> $user_id,
      ));

      if($query->num_rows()) {
        $result = $query->row_array();
        //var_dump($result['blog_id']); die;
        
        if(empty($result['blog_id'])){
          $blog_id = $this->insert_blog($result['user_id'], $result['member_id']);
          $result['blog_id'] = $blog_id;
        }
        
        $this->tppymemcached->set($key, $result);
        return $result;
      }
      return null;
    }else{
       return $member;
    }
  }
    function get_profile_by_userid_noblog($user_id){
      $sql = "SELECT * FROM users_account u
      WHERE user_id=%user_id%";
      $query = $this->db->query($sql, array(
        '%user_id%'=> $user_id,
      ));

      if($query->num_rows()) {
        $result = $query->row_array();
        return $result;
      }
      return null;
  }
    function insert($data) {
    // users_account
    $this->edit->insert('users_account', $data);
    
    $insert_id = $this->edit->insert_id();
    // if(is_internal()){
      // echo 'ERROR : '.$this->edit->_error_message(); die;
    // }
      
    if($insert_id > 0){
      $member_id =sprintf("MEM%07d", $insert_id);
      $this->edit->update('users_account', 
        array('member_id'=>$member_id),
        array('user_id' =>$insert_id)
      );
      //$this->insert_into($insert_id);
    }
    return $insert_id;
  }
    function insert_into($user_id=0){
    $sql = "INSERT INTO member (primary_id, cvs_user_id, member_id, member_usrname, member_password, member_status, member_activate, member_delete_flag, group_code, date_register, date_activate, member_tmp_pwd)
    SELECT user_id, user_id, member_id, user_username, user_password, user_status, user_status, user_status, user_group, user_create_date, user_active_date, '' member_tmp_pwd
    FROM users_account WHERE user_id = $user_id";
    $this->edit->query($sql);
    
    $sql = "INSERT INTO cvs_users (id, member_id, member_usrname, member_password, member_status, member_activate, member_delete_flag, group_id, date_register, date_activate) 
    SELECT user_id ,member_id ,user_username ,user_password ,user_status ,user_status ,user_status ,user_group ,user_create_date ,user_active_date
    FROM users_account WHERE user_id = $user_id";
    $this->edit->query($sql);
    
    $sql = "INSERT INTO cvs_personnel (id, user_id, member_id, psn_email)
    SELECT user_id, user_id, member_id, user_email
    FROM users_account WHERE user_id = $user_id";
    $this->edit->query($sql);
    
    $sql = "INSERT INTO personnel (personnel_id, member_id, psn_email)
    SELECT user_id, member_id, user_email
    FROM users_account WHERE user_id = $user_id";
    $this->edit->query($sql);
  }
    function update($data, $user_id=0){
    

    $this->edit->update('users_account', $data, array('user_id'=> $user_id));
    $result = $this->edit->affected_rows();
    
    // if($user_id==500000){
      // _vd($this->edit);
      // _vd($data, $user_id);
      // die;
    // }
    
    if($result > 0){
      $result = $this->update_into($user_id);
      $result_into = $this->update_into($user_id);
    }

    return $result + $result_into;
    //var_dump($this->edit);
     //var_dump($result);
    //$this->update_users_account($user_id, $data['member_id'], $data['user_username'], $data['user_password'], $data['user_password_tmp'], $data['user_email'], $data['user_status'], $data['user_group'], $data['user_permission'], $data['user_create_date'], $data['user_active_date'], $data['user_update_date'], $data['user_login_date'], $data['user_question'], $data['user_answer'], $data['psn_display_name'], $data['psn_display_image'], $data['psn_firstname'], $data['psn_lastname'], $data['psn_sex'], $data['psn_address'], $data['psn_postcode'], $data['psn_province'], $data['psn_tel'], $data['psn_id_number'], $data['psn_birthdate'], $data['psn_public_status'], $data['job_name'], $data['job_address'], $data['job_edu_name'], $data['job_edu_level'], $data['job_edu_degree'], $data['acc_user_facebook'], $data['acc_user_google'], $data['acc_user_twitter']);
    //$this->update_member($user_id, $data['member_id'], $data['user_username'], $data['user_password'], $data['user_password_tmp'], $data['user_email'], $data['user_status'], $data['user_group'], $data['user_permission'], $data['user_create_date'], $data['user_active_date'], $data['user_update_date'], $data['user_login_date'], $data['user_question'], $data['user_answer'], $data['psn_display_name'], $data['psn_display_image'], $data['psn_firstname'], $data['psn_lastname'], $data['psn_sex'], $data['psn_address'], $data['psn_postcode'], $data['psn_province'], $data['psn_tel'], $data['psn_id_number'], $data['psn_birthdate'], $data['psn_public_status'], $data['job_name'], $data['job_address'], $data['job_edu_name'], $data['job_edu_level'], $data['job_edu_degree'], $data['acc_user_facebook'], $data['acc_user_google'], $data['acc_user_twitter']);
    //$this->update_cvs_users($user_id, $data['member_id'], $data['user_username'], $data['user_password'], $data['user_password_tmp'], $data['user_email'], $data['user_status'], $data['user_group'], $data['user_permission'], $data['user_create_date'], $data['user_active_date'], $data['user_update_date'], $data['user_login_date'], $data['user_question'], $data['user_answer'], $data['psn_display_name'], $data['psn_display_image'], $data['psn_firstname'], $data['psn_lastname'], $data['psn_sex'], $data['psn_address'], $data['psn_postcode'], $data['psn_province'], $data['psn_tel'], $data['psn_id_number'], $data['psn_birthdate'], $data['psn_public_status'], $data['job_name'], $data['job_address'], $data['job_edu_name'], $data['job_edu_level'], $data['job_edu_degree'], $data['acc_user_facebook'], $data['acc_user_google'], $data['acc_user_twitter']);
    //$this->update_cvs_personnel($user_id, $data['member_id'], $data['user_username'], $data['user_password'], $data['user_password_tmp'], $data['user_email'], $data['user_status'], $data['user_group'], $data['user_permission'], $data['user_create_date'], $data['user_active_date'], $data['user_update_date'], $data['user_login_date'], $data['user_question'], $data['user_answer'], $data['psn_display_name'], $data['psn_display_image'], $data['psn_firstname'], $data['psn_lastname'], $data['psn_sex'], $data['psn_address'], $data['psn_postcode'], $data['psn_province'], $data['psn_tel'], $data['psn_id_number'], $data['psn_birthdate'], $data['psn_public_status'], $data['job_name'], $data['job_address'], $data['job_edu_name'], $data['job_edu_level'], $data['job_edu_degree'], $data['acc_user_facebook'], $data['acc_user_google'], $data['acc_user_twitter']);
    //$this->update_personnel($user_id, $data['member_id'], $data['user_username'], $data['user_password'], $data['user_password_tmp'], $data['user_email'], $data['user_status'], $data['user_group'], $data['user_permission'], $data['user_create_date'], $data['user_active_date'], $data['user_update_date'], $data['user_login_date'], $data['user_question'], $data['user_answer'], $data['psn_display_name'], $data['psn_display_image'], $data['psn_firstname'], $data['psn_lastname'], $data['psn_sex'], $data['psn_address'], $data['psn_postcode'], $data['psn_province'], $data['psn_tel'], $data['psn_id_number'], $data['psn_birthdate'], $data['psn_public_status'], $data['job_name'], $data['job_address'], $data['job_edu_name'], $data['job_edu_level'], $data['job_edu_degree'], $data['acc_user_facebook'], $data['acc_user_google'], $data['acc_user_twitter']);
  }
    function update_into($user_id){
    $result = 0;
    // personnel
    $sql = "UPDATE personnel p INNER JOIN users_account a ON a.user_id = $user_id
    SET p.psn_email = a.user_email
    , p.psn_upd_date = a.user_update_date
    , p.psn_display_name = a.psn_display_name
    , p.psn_picture = a.psn_display_image
    , p.psn_name = a.psn_firstname
    , p.psn_lastname = a.psn_lastname
    , p.psn_sex = a.psn_sex
    , p.psn_address = a.psn_address
    , p.postal_code = a.psn_postcode
    , p.province_id = a.psn_province
    , p.psn_hometel = a.psn_tel
    , p.psn_id = a.psn_id_number
    , p.psn_birthdate = a.psn_birthdate
    , p.psn_profile_status = IF(a.psn_public_status = 1, 1, 0)
    , p.psn_delete_flag = IF(a.psn_public_status = 3, 1, 0)
    , p.occ_id = (SELECT occ_id from occupation WHERE occ_name = a.job_name)
    , p.company_name = a.job_address
    , p.school_name = a.job_edu_name
    , p.school_level = a.job_edu_level
    , p.qual_id = (SELECT qual_id from qualification WHERE qual_name = a.job_edu_degree)
    WHERE p.member_id = a.member_id";
    $this->edit->query($sql);
    $result = $result + $this->edit->affected_rows();
    //cvs_personnel
    $sql = "UPDATE cvs_personnel p INNER JOIN users_account a ON a.user_id = $user_id
    SET p.psn_email = a.user_email
    , p.psn_upd_date = a.user_update_date
    , p.psn_display_name = a.psn_display_name
    , p.psn_picture = a.psn_display_image
    , p.psn_name = a.psn_firstname
    , p.psn_lastname = a.psn_lastname
    , p.psn_sex = a.psn_sex
    , p.psn_address = a.psn_address
    , p.postal_code = a.psn_postcode
    , p.province_id = a.psn_province
    , p.psn_hometel = a.psn_tel
    , p.psn_id = a.psn_id_number
    , p.psn_birthdate = a.psn_birthdate
    , p.psn_profile_status = IF(a.psn_public_status = 1, 1, 0)
    , p.psn_delete_flag = IF(a.psn_public_status = 3, 1, 0)
    , p.occ_id = (SELECT occ_id from occupation WHERE occ_name = a.job_name)
    , p.company_name = a.job_address
    , p.school_name = a.job_edu_name
    , p.school_level = a.job_edu_level
    , p.qual_id = (SELECT qual_id from qualification WHERE qual_name = a.job_edu_degree)
    WHERE p.member_id = a.member_id";
    $this->edit->query($sql);
    $result = $result + $this->edit->affected_rows();
    
    // member
    $sql = "UPDATE member p INNER JOIN users_account a ON a.user_id = $user_id
    SET p.member_password = a.user_password
    ,p.member_tmp_pwd =a.user_password_tmp
    ,p.member_status =  IF(a.user_status = 1, 0, 1) -- 0 : REGISTER, 1 : NORMAL, 2: BAN, 3: DELETE
    ,p.member_activate = IF(a.user_status = 1, 1, 0)
    ,p.member_delete_flag = IF(a.user_status = 3, 1, 0)
    ,p.group_code =a.user_group
    ,p.date_register =a.user_create_date
    ,p.date_activate =a.user_active_date
    ,p.question_id =a.user_question
    ,p.member_answer =a.user_answer
    WHERE p.member_id = a.member_id";
    $this->edit->query($sql);
    $result = $result + $this->edit->affected_rows();
    
    $sql = "UPDATE cvs_users p INNER JOIN users_account a ON a.user_id = $user_id
    SET p.member_password = a.user_password
    ,p.member_tmp_pwd =a.user_password_tmp
    ,p.member_status =  IF(a.user_status = 1, 0, 1) -- 0 : REGISTER, 1 : NORMAL, 2: BAN, 3: DELETE
    ,p.member_activate = IF(a.user_status = 1, 1, 0)
    ,p.member_delete_flag = IF(a.user_status = 3, 1, 0)
    ,p.group_id =a.user_group
    ,p.date_register =a.user_create_date
    ,p.date_activate =a.user_active_date
    ,p.question_id =a.user_question
    ,p.member_answer =a.user_answer
    WHERE p.member_id = a.member_id";
    $this->edit->query($sql);
    $result = $result + $this->edit->affected_rows();
    
    return $result;
  }

  /*
  function update_users_account($user_id, $member_id, $user_username, $user_password, $user_password_tmp, $user_email, $user_status, $user_group, $user_permission, $user_create_date, $user_active_date, $user_update_date, $user_login_date, $user_question, $user_answer, $psn_display_name, $psn_display_image, $psn_firstname, $psn_lastname, $psn_sex, $psn_address, $psn_postcode, $psn_province, $psn_tel, $psn_id_number, $psn_birthdate, $psn_public_status, $job_name, $job_address, $job_edu_name, $job_edu_level, $job_edu_degree, $acc_user_facebook, $acc_user_google, $acc_user_twitter){
    if(isset($member_id)) $update['member_id'] = $member_id;
    if(isset($user_username)) $update['user_username'] = $user_username;
    if(isset($user_password)) $update['user_password'] = $user_password;
    if(isset($user_password_tmp)) $update['user_password_tmp'] = $user_password_tmp;
    if(isset($user_email)) $update['user_email'] = $user_email;
    if(isset($user_status)) $update['user_status'] = $user_status;
    if(isset($user_group)) $update['user_group'] = $user_group;
    if(isset($user_permission)) $update['user_permission'] = $user_permission;
    if(isset($user_create_date)) $update['user_create_date'] = $user_create_date;
    if(isset($user_active_date)) $update['user_active_date'] = $user_active_date;
    if(isset($user_update_date)) $update['user_update_date'] = $user_update_date;
    if(isset($user_login_date)) $update['user_login_date'] = $user_login_date;
    if(isset($user_question)) $update['user_question'] = $user_question;
    if(isset($user_answer)) $update['user_answer'] = $user_answer;
    if(isset($psn_display_name)) $update['psn_display_name'] = $psn_display_name;
    if(isset($psn_display_image)) $update['psn_display_image'] = $psn_display_image;
    if(isset($psn_firstname)) $update['psn_firstname'] = $psn_firstname;
    if(isset($psn_lastname)) $update['psn_lastname'] = $psn_lastname;
    if(isset($psn_sex)) $update['psn_sex'] = $psn_sex;
    if(isset($psn_address)) $update['psn_address'] = $psn_address;
    if(isset($psn_postcode)) $update['psn_postcode'] = $psn_postcode;
    if(isset($psn_province)) $update['psn_province'] = $psn_province;
    if(isset($psn_tel)) $update['psn_tel'] = $psn_tel;
    if(isset($psn_id_number)) $update['psn_id_number'] = $psn_id_number;
    if(isset($psn_birthdate)) $update['psn_birthdate'] = $psn_birthdate;
    if(isset($psn_public_status)) $update['psn_public_status'] = $psn_public_status;
    if(isset($job_name)) $update['job_name'] = $job_name;
    if(isset($job_address)) $update['job_address'] = $job_address;
    if(isset($job_edu_name)) $update['job_edu_name'] = $job_edu_name;
    if(isset($job_edu_level)) $update['job_edu_level'] = $job_edu_level;
    if(isset($job_edu_degree)) $update['job_edu_degree'] = $job_edu_degree;
    if(isset($acc_user_facebook)) $update['acc_user_facebook'] = $acc_user_facebook;
    if(isset($acc_user_google)) $update['acc_user_google'] = $acc_user_google;
    if(isset($acc_user_twitter)) $update['acc_user_twitter'] = $acc_user_twitter;

    return $this->edit->update('users_account', $update, array('user_id'=> $user_id));
  }
  function update_member($user_id, $member_id, $user_username, $user_password, $user_password_tmp, $user_email, $user_status, $user_group, $user_permission, $user_create_date, $user_active_date, $user_update_date, $user_login_date, $user_question, $user_answer, $psn_display_name, $psn_display_image, $psn_firstname, $psn_lastname, $psn_sex, $psn_address, $psn_postcode, $psn_province, $psn_tel, $psn_id_number, $psn_birthdate, $psn_public_status, $job_name, $job_address, $job_edu_name, $job_edu_level, $job_edu_degree, $acc_user_facebook, $acc_user_google, $acc_user_twitter) {
    if(isset($user_id)) $update['cvs_user_id'] = $user_id;
    if(isset($member_id)) $update['member_id'] = $member_id;
    if(isset($user_username)) $update['member_usrname'] = $user_username;
    if(isset($user_password)) $update['member_password'] = $user_password;
    if(isset($user_password_tmp)) $update['member_tmp_pwd'] = $user_password_tmp;
    if(isset($user_status)) $update['member_status'] = $user_status;
    if(isset($user_active_date)) $update['member_activate'] = $user_active_date;
    if(isset($user_status)) $update['member_delete_flag'] = $user_status;
    if(isset($user_group)) $update['group_code'] = $user_group;
    if(isset($user_create_date)) $update['date_register'] = $user_create_date;
    if(isset($user_active_date)) $update['date_activate'] = $user_active_date;
    if(isset($user_question)) $update['question_id'] = $user_question;
    if(isset($user_answer)) $update['user_answer'] = $user_answer;
    
    return $this->edit->update('member', $update, array('primary_id'=> $user_id));
  }
  function update_cvs_users($user_id, $member_id, $user_username, $user_password, $user_password_tmp, $user_email, $user_status, $user_group, $user_permission, $user_create_date, $user_active_date, $user_update_date, $user_login_date, $user_question, $user_answer, $psn_display_name, $psn_display_image, $psn_firstname, $psn_lastname, $psn_sex, $psn_address, $psn_postcode, $psn_province, $psn_tel, $psn_id_number, $psn_birthdate, $psn_public_status, $job_name, $job_address, $job_edu_name, $job_edu_level, $job_edu_degree, $acc_user_facebook, $acc_user_google, $acc_user_twitter) {
    if(isset($member_id)) $update['member_id'] = $member_id;
    if(isset($user_username)) $update['member_usrname'] = $user_username;
    if(isset($user_password)) $update['member_password'] = $user_password;
    if(isset($user_password_tmp)) $update['member_tmp_pwd'] = $user_password_tmp;
    if(isset($user_status)) $update['member_status'] = $user_status;
    if(isset($user_status)) $update['member_activate'] = $user_status;
    if(isset($user_status)) $update['member_delete_flag'] = $user_status;
    if(isset($user_group)) $update['group_id'] = $user_group;
    if(isset($user_create_date)) $update['date_register'] = $user_create_date;
    if(isset($user_active_date)) $update['date_activate'] = $user_active_date;
    if(isset($user_question)) $update['question_id'] = $user_question;
    if(isset($user_answer)) $update['user_answer'] = $user_answer;
    
    return $this->edit->update('cvs_users', $update, array('id'=> $user_id));
  }
  function update_cvs_personnel($user_id, $member_id, $user_username, $user_password, $user_password_tmp, $user_email, $user_status, $user_group, $user_permission, $user_create_date, $user_active_date, $user_update_date, $user_login_date, $user_question, $user_answer, $psn_display_name, $psn_display_image, $psn_firstname, $psn_lastname, $psn_sex, $psn_address, $psn_postcode, $psn_province, $psn_tel, $psn_id_number, $psn_birthdate, $psn_public_status, $job_name, $job_address, $job_edu_name, $job_edu_level, $job_edu_degree, $acc_user_facebook, $acc_user_google, $acc_user_twitter){
    //'user_id' => $user_id,
    if(isset($member_id)) $update['member_id'] = $member_id;
    if(isset($user_email)) $update['psn_email'] = $user_email;
    if(isset($user_update_date)) $update['psn_upd_date'] = $user_update_date;
    if(isset($psn_display_name)) $update['psn_display_name'] = $psn_display_name;
    if(isset($psn_display_image)) $update['psn_picture'] = $psn_display_image;
    if(isset($psn_firstname)) $update['psn_name'] = $psn_firstname;
    if(isset($psn_lastname)) $update['psn_lastname'] = $psn_lastname;
    if(isset($psn_sex)) $update['psn_sex'] = $psn_sex;
    if(isset($psn_address)) $update['psn_address'] = $psn_address;
    if(isset($psn_postcode)) $update['postal_code'] = $psn_postcode;
    if(isset($psn_province)) $update['province_id'] = $psn_province;
    if(isset($psn_tel)) $update['psn_hometel'] = $psn_tel;
    if(isset($psn_id_number)) $update['psn_id'] = $psn_id_number;
    if(isset($psn_birthdate)) $update['psn_birthdate'] = $psn_birthdate;
    if(isset($psn_public_status)) $update['psn_public_status'] = $psn_public_status;
    if(isset($psn_public_status)) $update['psn_delete_flag'] = $psn_public_status;
    if(isset($job_name)) $update['occ_id'] = $job_name;
    if(isset($job_edu_name)) $update['company_id'] = $job_edu_name;
    if(isset($job_edu_name)) $update['company_name'] = $job_edu_name;
    if(isset($job_edu_name)) $update['school_id'] = $job_edu_name;
    if(isset($job_edu_name)) $update['school_name'] = $job_edu_name;
    if(isset($job_edu_level)) $update['school_level'] = $job_edu_level;
    if(isset($job_edu_level)) $update['school_room'] = $job_edu_level;
    if(isset($job_edu_degree)) $update['qual_id'] = $job_edu_degree;
    
    return $this->edit->update('cvs_personnel', $update, array('user_id'=> $user_id));
  }
  function update_personnel($user_id, $member_id, $user_username, $user_password, $user_password_tmp, $user_email, $user_status, $user_group, $user_permission, $user_create_date, $user_active_date, $user_update_date, $user_login_date, $user_question, $user_answer, $psn_display_name, $psn_display_image, $psn_firstname, $psn_lastname, $psn_sex, $psn_address, $psn_postcode, $psn_province, $psn_tel, $psn_id_number, $psn_birthdate, $psn_public_status, $job_name, $job_address, $job_edu_name, $job_edu_level, $job_edu_degree, $acc_user_facebook, $acc_user_google, $acc_user_twitter){
    if(isset($member_id)) $update['member_id'] = $member_id;
    if(isset($user_email)) $update['psn_email'] = $user_email;
    if(isset($user_update_date)) $update['psn_upd_date'] = $user_update_date;
    if(isset($psn_display_name)) $update['psn_display_name'] = $psn_display_name;
    if(isset($psn_display_image)) $update['psn_picture'] = $psn_display_image;
    if(isset($psn_firstname)) $update['psn_name'] = $psn_firstname;
    if(isset($psn_lastname)) $update['psn_lastname'] = $psn_lastname;
    if(isset($psn_sex)) $update['psn_sex'] = $psn_sex;
    if(isset($psn_address)) $update['psn_address'] = $psn_address;
    if(isset($psn_postcode)) $update['postal_code'] = $psn_postcode;
    if(isset($psn_province)) $update['province_id'] = $psn_province;
    if(isset($psn_tel)) $update['psn_hometel'] = $psn_tel;
    if(isset($psn_id_number)) $update['psn_id'] = $psn_id_number;
    if(isset($psn_birthdate)) $update['psn_birthdate'] = $psn_birthdate;
    if(isset($psn_public_status)) $update['psn_public_status'] = $psn_public_status;
    if(isset($psn_public_status)) $update['psn_delete_flag'] = $psn_public_status;
    if(isset($job_name)) $update['occ_id'] = $job_name;
    if(isset($job_edu_name)) $update['company_id'] = $job_edu_name;
    if(isset($job_edu_name)) $update['company_name'] = $job_edu_name;
    if(isset($job_edu_name)) $update['school_id'] = $job_edu_name;
    if(isset($job_edu_name)) $update['school_name'] = $job_edu_name;
    if(isset($job_edu_level)) $update['school_level'] = $job_edu_level;
    if(isset($job_edu_level)) $update['school_room'] = $job_edu_level;
    if(isset($job_edu_degree)) $update['qual_id'] = $job_edu_degree;
    
    return $this->edit->update('personnel', $update, array('personnel_id'=> $user_id));
  }
  */
  function getCareer() {
    $this->db->select('*');
    $this->db->from('occupation');
    $this->db->order_by('occ_id', 'ASC');
    $query = $this->db->get();
    return  $query->result_array();
  }
  function getProvince() {
    $this->db->select('*');
    $this->db->from('province');
    $this->db->order_by('province_name', 'ASC');
    $query = $this->db->get();
    return  $query->result_array();
  }
  function getQualification() {
    $this->db->select('*');
    $this->db->from('qualification');
    $this->db->order_by('qual_name', 'ASC');
    $query = $this->db->get();
    return  $query->result_array();
  }
  function checkAvalaibleUsername($user_username=''){
    $sql = "SELECT * FROM users_account WHERE user_username=%user_username%";
    $query = $this->db->query($sql, array(
      '%user_username%'=> $user_username,
    ));

   if($query->num_rows()) {
      return $query->row_array();
    } else {
      return null;
    }
  }
  function checkAvalaibleEmail($user_email='') {
    $sql = "SELECT * FROM users_account WHERE user_email=%user_email% AND user_status IN (1, 2)";
    $query = $this->db->query($sql, array(
      '%user_email%'=> $user_email,
    ));

    if($query->num_rows()) {
      return $query->row_array();
    } else {
      return null;
    }
  }
}