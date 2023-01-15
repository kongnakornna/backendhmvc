<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';
/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Ict extends MY_Controller{
    private $look_user_idx;
    private $user_idx;
    private $username;
    private $user_type;
    function __construct(){
        // Construct the parent class
        parent::__construct();
        $this->load->model("Admin_model");
        $this->load->model("ict/Ict_model");
        $this->load->library('main_library');
        date_default_timezone_set('Asia/Bangkok');
        
        
        $this->load->model('csv_model'); //meload csv_model
        $this->load->library('csvimport'); //meload library csvimport.php
	   $this->load->library('xlsx');
        

        $this->user_idx = (!empty($_COOKIE['useridx']) ? $_COOKIE['useridx'] : "4152");
        $this->username = (!empty($_COOKIE['uname']) ? $_COOKIE['uname'] : "");
        $this->user_type = (!empty($_COOKIE['utype']) ? $_COOKIE['utype'] : "");
        $this->user_fullname = (!empty($_COOKIE['fullname']) ? $_COOKIE['fullname'] : "");
        $this->look_user_idx = (!empty($_COOKIE['look_user']) ? $_COOKIE['look_user'] : "");

        $is_Admin = $this->check_admin($this->user_idx);
        if($is_Admin == false){
            show_404();
            // redirect("http://connexted.org/ireport/ict/");
        }
    }
    public function get_filter(){
        $data['q'] = $this->input->get("q") ? $this->input->get("q") : "";
        $data['remark_pmo'] = $this->input->get("remark_pmo") ? $this->input->get("remark_pmo") : ""; 
        // var_dump($data); die;
        return $data;
    }
    public function index(){
        $this->report_users();
    }
    public function report_users(){
        $this->db->cache_delete('admin');

        $data['title_1'] = "รายงานการบันทึก";
        $data['title_2'] = "รายงานการทำงานของ ICT Talent";
        $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("ict"), "Home");
         $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("admin/ict"), "รายงานการบันทึก");

        $data['user_reports'] = $this->Admin_model->getReportUsers();
        $data['get_remark_pmo'] = $this->Admin_model->getRemarkPmaByGroupInUser();
        $data['remark_pmo_current'] = "";

        $get = $this->input->get();
        // var_dump($data['user_reports']); die;
        $filter = $this->get_filter();
        $arr_filter = array_filter($filter);
        
        $get = $this->input->get();
        if((isset($get['q']) && $get['q'] == "") && (isset($get['remark_pmo']) && $get['remark_pmo'] == "")){
            $_SESSION['filter_users'] = "";
        }else if(empty($arr_filter) && (isset($_SESSION['filter_users']['q']) || isset($_SESSION['filter_users']['remark_pmo']))){
            $filter = $_SESSION['filter_users'];
        }else{
            $_SESSION['filter_users'] = $filter;
        }
        
        $data['user_reports'] = $this->Admin_model->getReportUsers($filter);

        $this->url_fill_user = base_url($this->uri->uri_string());
        // echo "<pre>"; print_r($data['user_reports']); die;

        $this->template("admin/home-report", $data);
    }
    public function monthly($user_id){
        $this->db->cache_delete('admin');
        $data['title_1'] = "Monthly Report";
        $data['title_2'] = "สรุปการทำงาน และกดยืนยันการทำงานในแต่ละเดือน";
        $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("ict"), "Home");
        $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("admin/ict"), "รายงานการบันทึก");
        $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("admin/ict/monthly/".$user_id), "Monthly Report");

        $bread_title = "<a href=".base_url('admin/ict').">Admin</a>";
        $bread_this = "<a href=".base_url('admin/ict/monthly/'.$user_id).">Monthly Report</a>";
        $data['bread_crumb'] = $bread_title." > ".$bread_this;

        $data['get_user'] = $this->Admin_model->getUsers($user_id)[0];
        $data['get_years'] = $this->main_library->getYears();
        $data['get_months'] = $this->main_library->getMonths();
        $data['controller'] = $this;
        $data_trans = $this->Admin_model->searchTransByMonthYear($user_id, date('m'), date('Y'));
        $month_days = $this->getDayAllMonth(date('m'), date('Y'));
        $data['user_log'] = $this->mergeDayAllMonthWithTrans($month_days, $data_trans);
        $data['current_date']['month'] = date('m');
        $data['current_date']['year'] = date('Y');
        $data['controllers'] = $this;
        /* for serach */
        $get = $this->input->get();
        if($get){
            $data_trans = $this->Admin_model->searchTransByMonthYear($user_id, $get['month'], $get['year']);
            $month_days = $this->getDayAllMonth($get['month'], $get['year']);
            $data['user_log'] = $this->mergeDayAllMonthWithTrans($month_days, $data_trans);
            $data['current_date']['month'] = $get['month'];
            $data['current_date']['year'] = $get['year'];
        }
        // echo "<pre>"; print_r($data['user_log']); die;
    
        $this->template("monthly-report", $data);   
    }
    public function action($user_id){
        $this->db->cache_delete("admin");

        $data['title_1'] = "แอบส่องการทำงาน";
        $data['title_2'] = "";
        $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("ict"), "Home");
        $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("admin/ict"), "รายงานการบันทึก");
        $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("admin/ict/action/".$user_id), "แอบส่องการทำงาน");
        
        $bread_title = "<a href=".base_url('admin/ict').">Admin</a>";
        $bread_this = "<a href=".base_url('admin/ict/action/'.$user_id).">แอบส่องการทำงาน</a>";
        $data['bread_crumb'] = $bread_title." > ".$bread_this;

        $data['months'] = $this->main_library->getMonths();
        $data['years'] = $this->main_library->getYears();
        $data['get_target'] = $this->Ict_model->getActionTarget();
        $data['pms_topic'] = $this->Ict_model->getPmsTopic($this->user_idx);
        $data['pms_trans'] = $this->Ict_model->getPmsTrans($this->user_idx);
        $data['controllers'] = $this;
        $data['get_user'] = $this->Admin_model->getUsers($user_id)[0];
        $data['get_years'] = $this->main_library->getYears();
        $data['get_months'] = $this->main_library->getMonths();

        $data['date_result'] = $this->getDateAgo(date('m'), date('Y'));
        $data['current_date']['month'] = date('m');
        $data['current_date']['year'] = date('Y');

        $get = $this->input->get();
        if($get){
            $data['date_result'] = $this->getDateAgo($get['month'], $get['year']);
            $data['current_date']['month'] = $get['month'];
            $data['current_date']['year'] = $get['year'];
        }
        //echo "<pre>"; print_r($trans); die;

        $this->template("action-report", $data);
    }
    public function worksummary($user_id){
        $this->db->cache_delete("admin");
        $data['title_1'] = "Work Summary";
        $data['title_2'] = "สรุปรวมการทำงาน และทำกิจกรรม";
        $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("ict"), "Home");
        $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("admin/ict"), "รายงานการบันทึก");
        $data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("admin/ict/worksummary/".$user_id), "Work Summary");

        $bread_title = "<a href=".base_url('admin/ict').">Admin</a>";
        $bread_this = "<a href=".base_url('admin/ict/worksummary/'.$user_id).">Work Summary</a>";
        $data['bread_crumb'] = $bread_title." > ".$bread_this;

        $data['get_user'] = $this->Admin_model->getUsers($user_id)[0];
        $data['get_years'] = $this->main_library->getYears();
        $data['get_months'] = $this->main_library->getMonths();

        $data['current_date']['month'] = date('m');
        $data['current_date']['year'] = date('Y');
        $data['count_dayall'] = date("d");
        // strtotime("last day of this month", date('Y-m-d'));
        
        $data['work_summay'] = $this->Admin_model->getSummaryWorkByMonthYear($user_id, date('m'), date('Y'))[0];
        $summary_topics = $this->Admin_model->getTopicAndSummary($user_id, date('m'), date('Y'));
        $data['summary_topics'] = $this->topic_summary($user_id, date('m'), date('Y'), $summary_topics);
        $data['controllers'] = $this;

        $get = $this->input->get();
        if($get){
            $date_get = $get['year']."-".$get['month']."-01";
            $count_dayall = date("t", strtotime($date_get));
            if($get['month'] == date("m")){
                $count_dayall = date("d");
            }else if($get['year'] > date("Y")){
                $count_dayall = "0";
            }
            $data['count_dayall'] = $count_dayall;
            
            $data['current_date']['month'] = $get['month'];
            $data['current_date']['year'] = $get['year'];
            $data['work_summay'] = $this->Admin_model->getSummaryWorkByMonthYear($user_id, $get['month'], $get['year'])[0];
            $summary_topics = $this->Admin_model->getTopicAndSummary($user_id, $get['month'], $get['year']);
            $data['summary_topics'] = $this->topic_summary($user_id, $get['month'], $get['year'], $summary_topics);
        }
        // echo "<pre>"; print_r($data['count_dayall']); die;

        $this->template("worksummary-report", $data);
    }
    public function unsubmit(){
        $status = false;
        $post = $this->input->post();
        if($post){
            $user_id = $post['user_id'];
            $date_save = $post['date_save'];

            $get_unsubmit = $this->Admin_model->getUnsubmit($user_id, $date_save);
            $unsubmit = $this->Admin_model->unsubmitTrans($user_id, $date_save);
            if($unsubmit != false){
                $data_log['user_idx'] = $this->user_idx;
                $data_log['menu'] = "ICT Admin : Daily";
                
                $this->user_log($get_unsubmit, $data_log);

                $status = true;
            }

            echo json_encode($status);
        }else{
            redirect(base_url('admin/ict'));
        }
    }
    public function load_table_trans(){
        $this->db->cache_delete("admin");
        $get = $this->input->get();
        $month = ($get && $get['month']) ? $get['month'] : date('m');
        $year = ($get && $get['year']) ? $get['year'] : date('Y');
        $user_id = $get['user_id'];
        $data['get_user'] = $this->Admin_model->getUsers($user_id)[0];
        $data['date_result'] = $this->getDateAgo($month, $year);
        $data['get_target'] = $this->Ict_model->getActionTarget();
        $data['controllers'] = $this;
        $data['pms_topic'] = $this->Ict_model->getPmsTopic();
        //echo "<pre>"; print_r($trans); die;

        //$this->template("ajax-views/table-trans", $data);
        $view_table_diary = $this->load->view("ajax-views/table-trans", $data, true);

        echo json_encode($view_table_diary);
    }
    public function paresDateThai($day, $month, $year){
        $date_thai = "";
        $get_months = $this->main_library->getMonths();
        $get_years = $this->main_library->getYears();
        if($month && $year){
            $date_thai = $day." ".$get_months[$month]." พ.ศ.".$get_years[$year];
        }

        return $date_thai;
    }
    private function getDayAllMonth($month, $year){
        $dayArray = [];
        $date = new DateTime($year."-".$month."-01");
        //First day of month
        $date->modify('first day of this month');
        $firstday= $date->format('Y-m-d');
        //Last day of month
        $date->modify('last day of this month');
        $lastday = $date->format('d');
        for ($i=1; $i <= $lastday; $i++) { 
            $dateCreaet = date_create($year."-".$month."-".$i);
            $key = ((string)$i);
            if($i < 10){
                $key = "0".((string)$i);
            }
            $dayArray[$key] = $dateCreaet->format('Y-m-d');
        }

        return $dayArray;
    }
    private function mergeDayAllMonthWithTrans($month_days, $data_trans){
        $user_logs = [];
        foreach($month_days as $key_day => $value_day){
            $day = date('d', strtotime($value_day));
            $month = date('m', strtotime($value_day));
            $year = date('Y', strtotime($value_day));

            $user_logs[$key_day]['full_date'] = $value_day;
            $user_logs[$key_day]['pares_date'] = $this->paresDateThai($day, $month, $year);
            $user_logs[$key_day]['data_trans'] = null;
            $user_logs[$key_day]['data_trans_all'] = null;
            $user_logs[$key_day]['pares_date_save'] = null;
            $user_logs[$key_day]['status_confirm'] = $this->checkStatusComfirm($value_day);
            foreach($data_trans as $value_trans){
                $user_id = $value_trans->user_idx;
                $date_action = $value_trans->date_action;
                if($value_day == date('Y-m-d', strtotime($value_trans->date_action))){
                    $user_logs[$key_day]['data_trans'] = $value_trans;
                    $user_logs[$key_day]['data_trans_all'] = $this->Admin_model->getTransOfDate($user_id, $date_action);
                    $user_logs[$key_day]['status_confirm'] = $this->checkStatusComfirm($value_day, $value_trans->date_save);
                    
                    if($value_trans->date_save){
                        $day_save = date('d', strtotime($value_trans->date_save));
                        $month_save = date('m', strtotime($value_trans->date_save));
                        $year_save = date('Y', strtotime($value_trans->date_save));

                        $user_logs[$key_day]['pares_date_save'] = $this->paresDateThai($day_save, $month_save, $year_save);
                    }
                }
            }
        }
        // echo "<pre>"; print_r($user_logs); die;

        return $user_logs;
    }
    private function checkStatusComfirm($date_action = null, $date_confrim = null){
        $status_confirm['id'] = "5";
        $status_confirm['name'] = "ยังไม่กดยืนยัน";

        if($date_confrim && $date_action){
            $set_date_action = date('Y-m-d', strtotime($date_action));
            $set_date_confrim = date('Y-m-d', strtotime($date_confrim));

            $strtime_action = strtotime($set_date_action);
            $strtime_confrim = strtotime($set_date_confrim);

            // this week
            $this_mon = strtotime('last monday -1 week', $strtime_action);
            $this_sun = strtotime('last sunday +1 week', $strtime_action);
            // +1 week
            $next_mon = strtotime('last monday +1 week', $strtime_action);
            $next_sun = strtotime('last sunday +2 week', $strtime_action);
            
            if($strtime_confrim == $strtime_action){
                $status_confirm['id'] = "1";
                $status_confirm['name'] = "ยืนยันตรงกับวันทำงาน";
            }else if($strtime_confrim >= $this_mon && $strtime_confrim <= $this_sun){
                $status_confirm['id'] = "2";
                $status_confirm['name'] = "ยืนยันไม่ตรงกับวันทำงาน แต่อยู่ภายในสัปดาห์";
            }else if($strtime_confrim >= $next_mon && $strtime_confrim <= $next_sun){
                $status_confirm['id'] = "3";
                $status_confirm['name'] = "ยืนยันไม่ตรงกับวันทำงาน และเกินสัปดาห์ (เลท) แต่อยู่ภายใน 2 สัปดาห์";
            }
        }else if($date_action && !$date_confrim){
            $set_date_action = date('Y-m-d', strtotime($date_action));
            
            $strtime_action = strtotime($set_date_action);
            $strtime_today = strtotime(date('Y-m-d')); 
            // +2 week is over
            $weekover_mon = strtotime('last monday +2 week', $strtime_action);
            
            if($strtime_today > $weekover_mon){
                $status_confirm['id'] = "4";
                $status_confirm['name'] = "ไม่ยืนยันภายในระยะเวลา 2 สัปดาห์";
            }
        }
        // var_dump($status_confirm); die;

        return $status_confirm;
    }
    private function getDateAgo($month, $year){
        $date_result = [];
        $month_year = $year."-".$month;
        $start_month = strtotime($month_year.'-1');
        $end_month = strtotime('last day of this month', $start_month);
    
        while($start_month <= $end_month){
            $date_result[] = [
                "day" => date("d", $start_month),
                "month" => date("m", $start_month),
                "year" => date("Y", $start_month),
                "date" => date("Y-m-d", $start_month),
            ];

            $start_month = strtotime('+1 days', $start_month);
        }
        // echo "<pre>"; print_r($date_result); die;
        return $date_result;
    }
    public function checkTransOfDay($topic_id, $trans_date, $user_id){
        $status = "";
        $trans_day = $this->Ict_model->getPmsTransByTopicIdAndDate($topic_id, $trans_date, $user_id);
        if($trans_day){
            $status = $trans_day[0];
        }
        // echo "<pre>"; print_r($status); die;
        return $status;
    }
    public function checkConfrimOfDate($trans_date, $user_id){
        $data['status'] = false;
        $data['data_trans'] = "";

        $confirm_trans = $this->Ict_model->getPmsTransByDate($trans_date, $user_id);
        $count_confirm_trans = count($confirm_trans);
        $num_compare = 1;
        foreach($confirm_trans as $key_trans => $trans){
            if($trans->date_save){
                $num_compare++;
                if($count_confirm_trans < $num_compare){
                    $data['status'] = true;
                    $data['data_trans'] = $trans;
                }
            }
        }
        // echo "<pre>"; print_r($data);
        return $data;
    }
    public function cancel_confirm_trans(){
        $status['status'] = false;
        $status['text_status'] = "ไม่สามารถยืนยันบันทึกกิจกรรม";
        $post = $this->input->post();
        // var_dump($post); die;
        if($post){
            $user_id = $post['user_id'];
            $date_save = $post['date_save'];

            $get_unsubmit = $this->Admin_model->getUnsubmit($user_id, $date_save);
            $unsubmit = $this->Admin_model->unsubmitTrans($user_id, $date_save);
            if($unsubmit != false){
                $data_log['user_idx'] = $this->user_idx;
                $data_log['menu'] = "ICT Admin : แอบส่อง";
                $this->user_log($get_unsubmit, $data_log);

                $status['status'] = true;
                $status['text_status'] = "ยืนยันบันทึกกิจกรรม สำเร็จ";
            }
        }
        //echo "<pre>"; print_r($topic1day); die;

        echo json_encode($status);
    }
    private function user_log($data_unsubmites, $data_log){
        foreach($data_unsubmites as $data_unsubmit){
            $user_idx = $data_log['user_idx'];
            $task = "unsubmit";
            $ref_id = $data_unsubmit->idx;
            $menu = $data_log['menu'];
            $detail = "ยกเลิกยืนยันบันทึกกิจกรรม รหัสกิจกรรม: ".$ref_id." วันที่: ".date('Y-m-d', strtotime($data_unsubmit->date_action))." ของพนักงาน ICT Talent รหัสพนักงาน: ".$data_unsubmit->user_idx." โดย Admin รหัสพนักงาน: ".$user_idx;

            $this->main_library->userlog($user_idx, $task, $ref_id, $menu, $detail);
        }
    }
    private function topic_summary($user_id, $month, $year, $topics){
        $topic_summary = [];
        $data_query['user_idx'] = $user_id;
        $data_query['date_action_m'] = $month;
        $data_query['date_action_y'] = $year;
        foreach($topics as $topic){
            $data_query['pms_topic_idx'] = $topic->idx;
            $topic_summary[] = [
                'summary_topics' => $topic,
                'trans_of_topic' => $this->Admin_model->getActionOfTopic($data_query)
            ];
        }
        // echo "<pre>"; print_r($topic_summary); die;

        return $topic_summary;
    }
}