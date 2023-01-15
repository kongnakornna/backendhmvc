<?php defined('BASEPATH') OR exit('No direct script access allowed');
#require APPPATH . 'libraries/REST_Controller.php';
class Management extends Connexted_Controller {

    public $user_type_name = "";

    function __construct()
    {
        parent::__construct();
        $this->load->model('management_model');
        $this->load->model('sp/sp_model');
        $this->load->library('connexted_library');
        $this->config->load('mainconf', TRUE);
    }

    public function index()
    {
        $this->lists();
    }

    public function lists()
    {
        $requests['user_type_id >'] = 2;
        $data['list_user_type'] = $this->management_model->get_table($table="sd_user_type",$requests);
        $data['user_status'] = $this->config->item('user_status', 'mainconf');

        if($this->input->get('user_type')){
            $requests_user['user_type_id'] = $this->input->get('user_type');
        }
        if($this->input->get('user_status')){
            $requests_user['user_status'] = $this->input->get('user_status');
        }
        if($this->input->get('user_status') == -1){
            $requests_user['user_status'] = 0;
        }
        if($this->input->get('q')){
            $requests_user['q'] = $this->input->get('q');
        }
        /* ------------------------ pagination --------------------------------- */
        $this->load->library('pagination');
        $config = $this->sp_model->get_initial_pagination_config(50);
        $config['page_query_string'] = TRUE;
        $config['base_url'] = base_url().'user/management/lists?user_type='.$this->input->get('user_type').'&user_status='.$this->input->get('user_status').'&q='.$this->input->get('q');
        $ofset = $this->sp_model->get_url_ofset(50);
        $requests_user['select'] = "concat(ifnull(salutation,''), ifnull(name,''), ' ', ifnull(surname,'')) as fullname";
        $requests_user['user_type_id >'] = 2;
        $requests_user['order_by'] = 'fullname';
        $total = $this->management_model->get_table($table="tbl_user_2018",$requests_user);
        $requests_user['limit'] = 50;
        $requests_user['ofset'] = $ofset;
        $lists = $this->management_model->get_table($table="tbl_user_2018",$requests_user);
        $config['total_rows'] = count($total);
        // echo '<pre>'; echo ($list_exam['data']); echo '</pre>';die;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['i'] = $this->sp_model->get_url_i(50);
        /* ------------------------ END pagination -------------------------------- */

        $data['lists'] = $lists;

        $this->template_connexed('/management/lists', $data);
    }

    public function resetPass($user_idx = null)
    {
        $url_redirect = $this->input->get('url_redirect');
        $reset_password = 'Cned#'.$user_idx;
        $password_encrypt = MD5($reset_password);
        $data_update = array(
            'table' => 'tbl_user_2018',
            'update' =>  array(
              'password_encrypt' => $password_encrypt,
            ),
            'where' =>  array(
              'user_idx' => $user_idx,
            ),
        );
        $update = $this->management_model->update_table($data_update);
        if($url_redirect){
            $urldirec = $url_redirect;
        }else{
            $urldirec = base_url('user/management/lists');
        }
        if($update){
            echo ("<script LANGUAGE='JavaScript'>
                window.alert('Password ได้ถูก reset เป็น $reset_password เรียบร้อยแล้ว');
                window.location.href='$urldirec';
                </script>");
        }else{
            echo ("<script LANGUAGE='JavaScript'>
                window.alert('Password ไม่สามารถ reset ได้ในขณะนี้ กรุณาลองใหม่อีกครั้ง');
                window.location.href='$urldirec';
                </script>");
        }
    }

    function checkAvalaibleValidPass($pass) {

        $r1='/[A-Z]/';  //Uppercase
        $r2='/[a-z]/';  //lowercase
        $r3='/[!@#$%^&*()\-_=+{}:,<.>]/';  // whatever you mean by 'special char'
        $r4='/[0-9]/';  //numbers
        $this->form_validation->set_message('checkAvalaibleValidPass', 'รหัสผ่านไม่ตรงตามเงื่อนไขที่กำหนด');
        if(preg_match_all($r1,$pass, $o)<1) return FALSE;

        if(preg_match_all($r2,$pass, $o)<1) return FALSE;

        if(preg_match_all($r3,$pass, $o)<1) return FALSE;

        if(preg_match_all($r4,$pass, $o)<1) return FALSE;

        if(strlen($pass)<8) return FALSE;

        // $this->form_validation->set_message('user_password', 'รหัสผ่านไม่ตรงตามเงื่อนไขที่กำหนด');

        return TRUE;
    }

}