<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Security_trueplook {

    private $encrypted_key = 'Trueplookpanyadotcom';
    public $encrypted_key_photo = 'encrypted_photo_upload';

    public function _check_permission() {

        $CI = & get_instance();
        $CI->load->library('session');

        $data = array(
            'admin_asktrue',
            'admin_id',
            'admin_name',
            'admin_permission',
            'admin_token'
        );

        $sql = "select * from plookpanya_admin where admin_id='" . $CI->session->userdata('admin_id') . "' ";
        $nums = $CI->cdm->_nums($sql, 'select');
        $result = $CI->cdm->_query_row($sql, 'select');
        if ($nums > 0) {

            $token = md5($result[0]['admin_name'] . $result[0]['admin_permission'] . $result[0]['token_code'] . $this->encrypted_key . $result[0]['admin_id'] . $result[0]['admin_username']);
            if ($token == $CI->session->userdata('admin_token') and ( $CI->session->userdata('admin_permission') == 'asktrue' or $CI->session->userdata('admin_permission') == 'super_asktrue')) {
                return 'true';
            } else {
                $CI->session->unset_userdata($data);
                return 'false';
            }
        } else {
            $CI->session->unset_userdata($data);
            return 'false';
        }
    }

    public function _check_login_admin($user = '', $pass = '') {
        $CI = & get_instance();
        $CI->load->library('session');
        $CI->load->model('connect_db_model', 'cdm');

        $sql = "select * from plookpanya_admin where admin_username='" . $user . "' and admin_password='" . md5($pass) . "' and record_status=1 and admin_permission in ('asktrue','super-asktrue')  ";
        $nums = $CI->cdm->_nums($sql, 'select');
        $result = $CI->cdm->_query_row($sql, 'select');
        if ($nums > 0) {

            $token = md5($result[0]['admin_name'] . $result[0]['admin_permission'] . $result[0]['token_code'] . $this->encrypted_key . $result[0]['admin_id'] . $result[0]['admin_username']);
            $data = array(
                'admin_asktrue' => "admin",
                'admin_id' => $result[0]['admin_id'],
                'admin_name' => $result[0]['admin_name'],
                'admin_permission' => $result[0]['admin_permission'],
                'admin_token' => $token
            );
            $CI->session->set_userdata($data);

            $data_update = array('login_num' => $result[0]['login_num'] + 1);
            $where = array('admin_id' => $result[0]['admin_id']);
            $result_update = $CI->cdm->_query_update($data_update, $where, 'plookpanya_admin', 'edit');
            return 'true';
        } else {

            return 'false';
        }
    }

    public function _check_permission_member() {
        $CI = & get_instance();
        $CI->load->library('trueplook');
        $CI->load->library('memcache');
        $CI->load->model('connect_db_model', 'cdm');
        if ($user = $CI->session->userdata('user_session')) {

            $sql = "SELECT *,cvs_personnel.id as personnel_id FROM cvs_personnel "
                    . "LEFT OUTER JOIN blog ON blog.member_id = cvs_personnel.member_id "
                    . "LEFT OUTER JOIN cvs_users ON cvs_personnel.user_id = cvs_users.id "
                    . "WHERE cvs_personnel.user_id = ?";
            $data_sql = array($user->id);
            if ($result = $CI->memcache->get('check_token_key_' . $user->member_id) == '') {
                $result = $CI->cdm->_query_row($sql, 'select', $data_sql);
                $CI->memcache->set('check_token_key_' . $user->member_id, $res);
            }

            $rs = $result[0];
            $token_check = md5($rs['personnel_id'] . $rs['province_id'] . $rs['blog_id'] . $rs['occ_id']);
            //print 'token'.$rs['personnel_id'] .'||'. $rs['province_id'] .'||'. $rs['blog_id'] .'||'.$rs['occ_id'] ;
            if ($user->token_key <> $token_check) {

                setcookie("group_code", '', -3600);
                setcookie("psn_display_name", '', -3600);
                setcookie("member_id", '', -3600);
                setcookie("member_usrname", '', -3600);
                setcookie("token_key", '', -3600);

                unset($_SESSION["member"]);
                unset($_SESSION['plook']['member_type']);
                unset($_SESSION['plook']['member_display']);
                unset($_SESSION['plook']['member_id']);
                unset($_SESSION['plook']['primary_id']);
                unset($_SESSION['plook']['member_usernameupload']);
                unset($_SESSION['plook']['member_firstcome']);
                unset($_SESSION['plook']['member_group']);

                return false;
            } else {

                return true;
            }
        } else {

            return false;
        }
    }

    public function _check_permission_member_v2() {
        $CI = & get_instance();
        $CI->load->library('trueplook');
        $CI->load->library('memcache');
        $CI->load->model('connect_db_model', 'cdm');
        echo "<br>member_id==>" . $CI->trueplook->get_cookie('member_id');
        if ($CI->trueplook->get_cookie('member_id') <> '') {

            $sql = "select p.personnel_id,p.province_id,p.occ_id,b.blog_id from personnel p left join blog b on p.member_id=b.member_id where p.member_id=? ";
            $sql2 = "select a.blog_id,m.member_id,m.member_usrname, m.group_code,p.personnel_id,p.psn_display_name,p.psn_birthdate,p.province_id,p.occ_id,p.folder_path,p.psn_picture from blog as a left join member as m on a.member_id=m.member_id left join personnel p on p.member_id = a.member_id where a.member_id=? ";
            $data_sql = array($CI->trueplook->get_cookie('member_id'));
            $result1 = $CI->cdm->_query_row($sql, 'select', $data_sql);
            echo $sql."1<pre>";
            print_r($result1);
            $result2 = $CI->cdm->_query_row($sql2, 'select', $data_sql);
            echo $sql2."2<pre>";
            print_r($result2);
            echo "<br>check_token_key_==>" . $CI->memcache->get('check_token_key_v2_' . $CI->trueplook->get_cookie('member_id'));
            
            if ($result = $CI->memcache->get('check_token_key_v2_' . $CI->trueplook->get_cookie('member_id')) == '') {
                $result = $CI->cdm->_query_row($sql, 'select', $data_sql);
                $CI->memcache->set('check_token_key_v2_' . $CI->trueplook->get_cookie('member_id'), $result);
                
                echo "<br>no cache==>";
            }

            $rs = $result[0];
            
            $token_check = md5($rs['personnel_id'] . $rs['province_id'] . $rs['blog_id'] . $rs['occ_id']);
            echo "<br>token_check==>" . $token_check;
            
            echo "<br>get_cookie token_check==>" . $CI->trueplook->get_cookie('token_key');
            
            echo "<pre>";
            print_r($_COOKIES);
            echo "</pre>";
            if ($CI->trueplook->get_cookie('token_key') <> $token_check) {
                echo "<br>check cookie false==>";
//                setcookie("group_code", '', -3600);
//                setcookie("psn_display_name", '', -3600);
//                setcookie("member_id", '', -3600);
//                setcookie("member_usrname", '', -3600);
//                setcookie("token_key", '', -3600);
//
//                unset($_SESSION["member"]);
//                unset($_SESSION['plook']['member_type']);
//                unset($_SESSION['plook']['member_display']);
//                unset($_SESSION['plook']['member_id']);
//                unset($_SESSION['plook']['primary_id']);
//                unset($_SESSION['plook']['member_usernameupload']);
//                unset($_SESSION['plook']['member_firstcome']);
//                unset($_SESSION['plook']['member_group']);

                return false;
            } else {
                echo "<br>check cookie true==>";
                return true;
            }
        } else {

            return false;
        }
    }

    public function _check_permission_admin() {

        $CI = & get_instance();
        $CI->load->library('trueplook');
        $CI->load->library('memcache');
        $CI->load->model('connect_db_model', 'cdm');
        $group_code = $CI->trueplook->_get_cookie($_COOKIE['admin_group_code']);
        $admin_id = $CI->trueplook->_get_cookie($_COOKIE['admin_member_id']);
        if ($admin_id <> '') {

            $sql = "select p.personnel_id,p.province_id,p.occ_id,b.blog_id from personnel p left join blog b on p.member_id=b.member_id where p.member_id=? ";
            $data_sql = array($admin_id);
            //if ($result = $CI->memcache->get('check_token_key_' . $admin_id) == '') {
            $result = $CI->cdm->_query_row($sql, 'select', $data_sql);
            //$CI->memcache->set('check_token_key_' . $admin_id, $result);
            //}

            $rs = $result[0];

            $token_check = md5($rs['personnel_id'] . $rs['province_id'] . $rs['blog_id'] . $rs['occ_id']);
            if ($CI->trueplook->_get_cookie($_COOKIE['admin_tokenkey']) <> $token_check) {
                $CI->trueplook->clear_logout();

                unset($_SESSION["member"]);
                unset($_SESSION['plook']['member_type']);
                unset($_SESSION['plook']['member_display']);
                unset($_SESSION['plook']['member_id']);
                unset($_SESSION['plook']['primary_id']);
                unset($_SESSION['plook']['member_usernameupload']);
                unset($_SESSION['plook']['member_firstcome']);
                unset($_SESSION['plook']['member_group']);

                redirect('administrator/index/logout/');
                exit();
            } else {
                if ($group_code == 'ADM' or $group_code == 'TDM' or $group_code == 'SUP' or $group_code == 'CSA') {
                    return true;
                } else {
                    redirect('administrator/index/logout/');
                    exit();
                }
            }
        } else {
            redirect('administrator/index/logout/');
            exit();
        }
    }

    public function _check_permission_csa() {
        $CI = & get_instance();
        $CI->load->library('trueplook');
        $CI->load->library('memcache');
        $CI->load->model('connect_db_model', 'cdm');
        $member_id = $CI->trueplook->get_cookie('member_id');
        $group_code = $CI->trueplook->get_cookie('group_code');
        $member_usrname = $CI->trueplook->get_cookie('member_usrname');

        if ($member_id <> '') {
            if ($group_code == 'TRU' or $group_code == 'CSA') {
                $sql = "select school_id from school_profile where username='" . $member_usrname . "' ";
                $nums = $this->cdm->_nums($sql, 'select');
                if ($nums == 0) {
                    echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงข้อมูล');location.href='" . base_url() . "csa/' </script>";
                    exit();
                }
            } else {
                echo "<script>alert('คุณไม่มีสิทธิ์เข้าถึงข้อมูล');location.href='" . base_url() . "csa/' </script>";
                redirect(base_url() . 'csa/', 'refresh');
                exit();
            }
        } else {
            echo "<script>alert('กรุณาเข้าสู่ระบบก่อนแก้ไขข้อมูล');location.href='" . base_url() . "csa/' </script>";
            redirect(base_url() . 'csa/', 'refresh');
            exit();
        }
    }

}

//END CLASS
?>