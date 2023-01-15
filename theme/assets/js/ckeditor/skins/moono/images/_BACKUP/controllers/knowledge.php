<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed'); //header('Content-type: text/html; charset=utf-8');

    
//error_reporting(0);

class Knowledge extends TPPY_Controller {

    public function __construct() {
        header('Content-Type: text/html; charset=utf-8');
        parent::__construct();
//        if (!is_internal()) {
//            show_404();
//        }
    }

    public function checkeruser() {
        $param = $_COOKIE;
        echo '<pre>';
        print_r($param);
        echo '</pre>';

        echo '<hr>';
        echo 'USERNAME : ' . base64_decode(base64_decode(base64_decode($param['admin_member_usrname']))) . '<br>';
        echo 'ID : ' . base64_decode(base64_decode(base64_decode($param['admin_member_id']))) . '<br>';
        echo 'backurl : ' . base64_decode(base64_decode(base64_decode($param['backurl']))) . '<br>';
        echo '<hr>';

        $this->load->model('users/users_model');
        $member_id = base64_decode(base64_decode(base64_decode($param['admin_member_id'])));
        $data['member'] = $this->users_model->getUserProfile($member_id); //group_code
        echo '<pre>';
        print_r($data);
        echo '</pre>';

        echo '<hr>';

        $param = $_SESSION;
        echo '<pre>';
        print_r($param);
        echo '</pre>';

        echo '<hr>';

        $param = $this->session->userdata('user_session');
        echo '<pre>';
        print_r($param);
        echo '</pre>';
    }

    public function index() {

        $data['IE_version'] = $this->check_IE();
        $data['isDetect'] = $this->Detect();

        /* Search */
        $data['category'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getcategory'));
//        $data['context'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getcontext'));
        $data['context'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getCat'));
        $data['level'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getlevel'));
        /* knowledge */
//        $data['show_youtube'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getyoutubefull/8'));
//        $data['show_song'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getedusong/8'));
//        $data['show_photo'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/geteduimg/8'));
        $data['media_v'] = json_decoder(file_get_contents(base_url() . 'api/knowledgebase/loadmore?type=v&q=&sid=&ssid=&cid=&lid=&start=0&end=8'));
        $data['media_t'] = json_decoder(file_get_contents(base_url() . 'api/knowledgebase/loadmore?type=t&q=&sid=&ssid=&cid=&lid=&start=0&end=8'));
        $data['media_all'] = json_decoder(file_get_contents(base_url() . 'api/knowledgebase/loadmore?type=all&q=&sid=&ssid=&cid=&lid=&start=0&end=8'));

        //_vd($data['media_t']);
        //$data['theme_banner'] = json_decode(file_get_contents(base_url() . 'api/banner/bannerV3/10'));
//        echo '<pre>';
//        print_r($data['media']);
//        echo '</pre>';

        /* Theme */
        //$data['theme'] = json_decode(file_get_contents(base_url() . 'api/campaigns/listThemeAll'));

        /* Theme */
        $data['theme'] = json_decode(file_get_contents(base_url() . 'api/banner/bannerV3/10'));

        /* skill */
        $data['media_out'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getactivities/9200'));
        $data['room_activity'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getactivities/9300'));
        $data['boyscout'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getactivities/9100'));

        $data['childhood'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getchildhood'));

        $this->load->model('knowledgedata_model');
        $data['webboard'] = $this->knowledgedata_model->getWebboard();
        $data['infographic'] = $this->knowledgedata_model->getInfographic();
        $data['word_link'] = json_decode(file_get_contents(base_url() . "api/wordlink/json/34"));
        $req['subject_id'] = $_GET['sid'];
        $req['level_id'] = $_GET['lid'];
        $req['knowledge_id'] = $_GET['cid'];
        $req['q'] = $_GET['q'];

        $data['select_default'] = $this->defaultselect($req, $data['category'], $data['level'], $data['context']);

//        echo '<pre>';
//        print_r($data['webboard']);
//        echo '</pre>';

        $this->template->meta_title = 'การศึกษา ความรู้ ภาษาอังกฤษ วิทยาศาสตร์ คณิตศาสตร์ ภาษาไทย onet o-net gat pat : ทรูปลูกปัญญา';
        $this->template->meta_keywords = 'มุมคุณครู, ข่าวครู, สอบครูผู้ช่วย, สอบราชการ, เทคนิคการสอน, แผนการสอน, ผลงานวิชาการ';
        $this->template->meta_description = 'รวมบทเรียน ข้อมูลการเรียนรู้ คลิปวีดิโอการเรียนการสอน ออนไลน์ ทุกวิชา ทุกระดับชั้น ลงลึกระดับตัวชี้วัด คลังความรู้ ทรูปลูกปัญญา';

        $this->template->meta_og_title = $this->template->meta_title;
        $this->template->meta_og_description = $this->template->meta_description;
        $this->template->meta_og_app_id = '704799662982418';

        //$this->template->meta_og_image ="";

        $this->template->view('data_index', $data);
    }

    public function search() {
        
        //$this->template->set_theme('trueplookpanya', 'default', 'themes');
        
        $data['IE_version'] = $this->check_IE();
        $data['isDetect'] = $this->Detect();
        $req['subject_id'] = $_GET['sid'];
        $req['level_id'] = $_GET['lid'];
        $req['knowledge_id'] = $_GET['ssid'];
        $req['q'] = $_GET['q'];

        /* Search */
        $data['category'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getcategory'));
        $data['level'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getlevel'));
        //$data['context'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getcontext'));
        $data['context'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getCat'));


        $data['word_link'] = json_decode(file_get_contents(base_url() . "api/wordlink/json/34"));
        $data['select_default'] = $this->defaultselect($req, $data['category'], $data['level'], $data['context']);


        //$data['media'] = json_decoder(file_get_contents(base_url() . 'api/knowledgebase/loadmore?sid=' . $_GET['sid'] . "&lid=" . $_GET['lid'] . "&ssid=" . $_GET['ssid'] . "&cid=" . $_GET['cid'] . "&q=" . $_GET['q'] . "&start=0&end=20"));

        $data['media_v'] = json_decoder(file_get_contents(base_url() . 'api/knowledgebase/loadmore?type=v&sid=' . $_GET['sid'] . "&lid=" . $_GET['lid'] . "&ssid=" . $_GET['ssid'] . "&cid=" . $_GET['cid'] . "&q=" . $_GET['q'] . "&start=0&end=20"));
        $data['media_t'] = json_decoder(file_get_contents(base_url() . 'api/knowledgebase/loadmore?type=t&sid=' . $_GET['sid'] . "&lid=" . $_GET['lid'] . "&ssid=" . $_GET['ssid'] . "&cid=" . $_GET['cid'] . "&q=" . $_GET['q'] . "&start=0&end=20"));
        $data['media_all'] = json_decoder(file_get_contents(base_url() . 'api/knowledgebase/loadmore?type=all&sid=' . $_GET['sid'] . "&lid=" . $_GET['lid'] . "&ssid=" . $_GET['ssid'] . "&cid=" . $_GET['cid'] . "&q=" . $_GET['q'] . "&start=0&end=20"));

        $data['exam_relate'] = json_decoder((file_get_contents(base_url() . "api/getrelate/exam/0/7/order=last?sid=" . $req['subject_id'] . "&lid=" . $req['level_id'] . "&cid=" . $req['knowledge_id'])));

        $this->load->model('knowledgedata_model');
        $data['webboard'] = $this->knowledgedata_model->getWebboardsearch();
        
        $title ="คลังความรู้ การศึกษา";
        $keyword = " ";
        
        if(!empty($data['select_default']['subject']['id'])!=0){
            $title .= " วิชา".$data['select_default']['subject']['nav_name'];
            $keyword .= "วิชา".$data['select_default']['subject']['nav_name'];
        }
        if(!empty($data['select_default']['level']['id'])!=0){
            $title .= " ระดับชั้น".$data['select_default']['level']['nav_name'];
            $keyword .= ", ระดับชั้น ".$data['select_default']['level']['nav_name'];
        }
        if(!empty($data['select_default']['knowledge']['id'])!=0){
            $title .= " สาระ".$data['select_default']['knowledge']['nav_name'];
            $keyword .= ",สาระ ".$data['select_default']['knowledge']['nav_name'];
        }
        if(!empty ($_GET['q'])){
            $title .= " คำค้นหา".$_GET['q'];
            $keyword .= ",".$_GET['q'];
        }
        //_vd($keyword);
        
        $this->template->meta_title = $title;
        $this->template->meta_description = 'รวมบทเรียน ข้อมูลการเรียนรู้ คลิปวีดิโอการเรียนการสอน ออนไลน์ ทุกวิชา ทุกระดับชั้น ลงลึกระดับตัวชี้วัด คลังความรู้ ทรูปลูกปัญญา';
        $this->template->meta_keywords = ($keyword!=""?$title.",มุมคุณครู, ข่าวครู, สอบครูผู้ช่วย, สอบราชการ, เทคนิคการสอน, แผนการสอน, ผลงานวิชาการ":"ทุกวิชา,ทุกระดับชั้น,แยกสาระเรียนรู้,ผูกตัวชี้วัด,คลังความรู้,ความรู้รอบตัว,ความรู้ทั่วไป,สาระน่ารู้,เกร็ดความรู้ ,ทรูปลูกปัญญา");

        $this->template->meta_og_title = $this->template->meta_title;
        $this->template->meta_og_description = $this->template->meta_description;
        $this->template->meta_og_app_id = "704799662982418";
        
        $this->template->view('data_search', $data);
    }

    function defaultselect($data, $category, $level, $conetxt) {
        $rs = [];
        if ($data['subject_id'] == 0 || $data['subject_id'] == "") {
            $rs['subject']['id'] = 0;
            $rs['subject']['name'] = "ค้นหาจากรายวิชา";
            $rs['subject']['nav_name'] = "ทุกรายวิชา";
        } else {
            $rs['subject']['id'] = $data['subject_id'];
            foreach ($category as $key => $value) {
                if ($rs['subject']['id'] == $value->mul_category_id) {
                    $rs['subject']['name'] = $value->mul_category_name;
                    $rs['subject']['nav_name'] = $value->mul_category_name;
                }
            }
        }

        if ($data['level_id'] == 0 || $data['level_id'] == "") {
            $rs['level']['id'] = 0;
            $rs['level']['name'] = "ค้นหาจากระดับชั้น";
            $rs['level']['nav_name'] = "ทุกระดับชั้น";
        } elseif($data['level_id'] == 1020) {
			$rs['level']['id'] = 1020;
            $rs['level']['name'] = "ค้นหาจากระดับชั้น";
            $rs['level']['nav_name'] = "ปฐมศึกษา";
        } elseif($data['level_id'] == 3040) {
			$rs['level']['id'] = 3040;
            $rs['level']['name'] = "ค้นหาจากระดับชั้น";
            $rs['level']['nav_name'] = "มัธยมศึกษา";
		} else {
            $rs['level']['id'] = $data['level_id'];
            foreach ($level as $key => $value) {
                if ($rs['level']['id'] == $value->mul_level_id) {
                    $rs['level']['name'] = $value->mul_level_name;
                    $rs['level']['nav_name'] = $value->mul_level_name;
                }
            }
        }

        if ($data['knowledge_id'] == 0 || $data['knowledge_id'] == "") {
            $rs['knowledge']['id'] = 0;
            $rs['knowledge']['name'] = "ค้นหาจากสาระ";
            $rs['knowledge']['nav_name'] = "ทุกสาระการเรียนรู้";
        } else {
            $rs['knowledge']['id'] = $data['knowledge_id'];
            foreach ($conetxt as $key => $value) {
                if ($rs['knowledge']['id'] == $value->mul_category_id) {
                    $rs['knowledge']['name'] = $value->mul_category_name;
                    $rs['knowledge']['nav_name'] = $value->mul_category_name;
                }
            }
        }
        $rs['text_search'] = $data['q'];

        if ($data['q'] == "") {
            $rs['nav_search'] = $rs['subject']['nav_name'] . " > " . $rs['level']['nav_name'] . " > " . $rs['knowledge']['nav_name'];
        } else {
            $rs['nav_search'] = $rs['subject']['nav_name'] . " > " . $rs['level']['nav_name'] . " > " . $rs['knowledge']['nav_name'] . " > คำค้นหา " . $rs['text_search'];
        }
        return $rs;
    }

    public function Detect() {
        $this->load->library('Mobile_Detect');
        $detect = new Mobile_Detect();
        if ($detect->isMobile() && !$detect->isTablet()) {
            $isDetect = "mobile";
        } else if ($detect->isTablet()) {
            $isDetect = "tablet";
        } else {
            $isDetect = NULL;
        }
        return $isDetect;
    }

    function check_IE() {
        $version = 0;
        if (preg_match('/(?i)msie [5-8]/', $_SERVER['HTTP_USER_AGENT'])) {
            $version = 'IE<=8';
        } else {
            $version = 'IE>8';
        }
        return $version;
    }

    public function activity() {

        $list = array();
        if (!empty($_GET['category_id'])) {
            if ($_GET['category_id'] == 10000) {
                $list['list_data'] = json_decode(file_get_contents((base_url() . 'api/knowledgebase/getchildhood?s=0&e=40&level_id=' . $_GET['level_id'] . '&category_id=' . $_GET['category_id'] . '&q=' . $_GET['q'])));
                if (!empty($list['list_data'])) {
                    $list['list_all'] = true;
                    $list['use_api'] = 'activi_child';
                    $list['use_loadmore'] = '4';
                } else {
                    $list['not_data'] = true;
                }
            } else {
                $list['list_data'] = json_decode(file_get_contents((base_url() . 'api/knowledgebase/searchActivities/' . $_GET['category_id'] . '?start=0&end=40&level_id=' . $_GET['level_id'] . '&q=' . $_GET['q'])));
                if (!empty($list['list_data'])) {
                    $list['list_all'] = true;
                    switch ($_GET['category_id']) {
                        case 9100:
                            $list['use_api'] = 'activi_9100';
                            $list['use_loadmore'] = '1';
                            break;
                        case 9200:
                            $list['use_api'] = 'activi_9200';
                            $list['use_loadmore'] = '2';
                            break;
                        case 9300:
                            $list['use_api'] = 'activi_9300';
                            $list['use_loadmore'] = '3';
                            break;
                        default:
                            break;
                    }
                } else {
                    $list['not_data'] = true;
                }
            }
        } else {
            $q = $_GET['q'];
            $cat = !empty($_GET["category_id"]) ? $_GET["category_id"] : '';
            $lev = !empty($_GET["level_id"]) ? $_GET["level_id"] : '';
            if (!empty($q)) {
                $list['list_q'] = TRUE;
                $list['use_api'] = 'activi_all';
                $list['use_loadmore'] = '1';
                $list["list_data"] = json_decode(file_get_contents((base_url() . 'api/knowledgebase/loadmore/?type=activity&category_id=' . $cat . '&level_id=' . $lev . '&q=' . $q . '&offset=&start=0&end=10')))->other;
                //_vd($list["list_data"]);
            } else {
                $list['list_all'] = false;
                $list['list_9100'] = json_decode(file_get_contents((base_url() . 'api/knowledgebase/getactivities/9100?offset=1&start=0&end=40')));
                $list['list_9200'] = json_decode(file_get_contents((base_url() . 'api/knowledgebase/getactivities/9200?offset=1&start=0&end=40')));
                $list['list_9300'] = json_decode(file_get_contents((base_url() . 'api/knowledgebase/getactivities/9300?offset=1&start=0&end=40')));
                $list['list_child'] = json_decode(file_get_contents((base_url() . 'api/knowledgebase/getchildhood?s=0&e=40')));
            }
        }

        //_vd($list);

        $this->template->meta_title = ('สื่อพัฒนานอกระบบ|กิจกรรมห้องเรียน|ลูกเสือ-เนตรนารี|' . ($_GET['q'] != '' ? '|' . $_GET['q'] : '') . '|ความรู้และกิจกรรมเสริมทักษะ');
        $this->template->meta_og_title = ('สื่อพัฒนานอกระบบ|กิจกรรมห้องเรียน|ลูกเสือ-เนตรนารี|' . ($_GET['q'] != '' ? '|' . $_GET['q'] : '') . '|ความรู้และกิจกรรมเสริมทักษะ');

        $this->template->view('data_page4', $list);
    }

    private function level_name($id) {
        $level = array();
        $level['00'] = 'ปฐมวัย';
        $level['01'] = 'อ.1';
        $level['02'] = 'อ.2';
        $level['03'] = 'อ.3';
        $level['10'] = 'ประถมต้น';
        $level['11'] = 'ป.1';
        $level['12'] = 'ป.2';
        $level['13'] = 'ป.3';
        $level['20'] = 'ประถมปลาย';
        $level['21'] = 'ป.4';
        $level['22'] = 'ป.5';
        $level['23'] = 'ป.6';
        $level['30'] = 'มัธยมต้น';
        $level['31'] = 'ม.1';
        $level['32'] = 'ม.2';
        $level['33'] = 'ม.3';
        $level['40'] = 'มัธยมปลาย';
        $level['41'] = 'ม.4';
        $level['42'] = 'ม.5';
        $level['43'] = 'ม.6';
        return $level[$id];
    }

    private function get_Length_Relate($data, $content_id) {
        $sum_data = count($data);

        $arr = [];
        $data_leng = 5;
        $loop = $data_leng - $sum_data;

//        echo 'sum:'.$sum_data.'<br>';
//        echo 'loop:'.$loop.'<br>';

        $data_loop = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/' . $content_id . '/' . $loop))['data'];
        foreach ($data as $key => $value) {
            $arr_new = object_to_array($value);
            $rs['topic'] = $arr_new['subject'];
            $rs['url'] = $arr_new['link'];
            $rs['thumbnail'] = $arr_new['thumb'];
            $rs['addDateTime'] = $arr_new['add_date'];
            $rs['mul_source_id'] = $arr_new['mul_source_id'];
            $arr[] = $rs;
        }

        foreach ($data_loop as $key => $value) {
            $arr[] = $value;
        }

        return $arr;
    }

    private function getNameContext($context_id = 0) {
        $data['context'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getcontext'));
        $text = new stdClass();

        foreach ($data['context'] as $key => $value) {
            if ($value->knowledge_context_id == $context_id) {
                $textL = $value->knowledge_context_name;
            }
        }

        $info = split(") ", $textL);
        $text->context_name = $info[1];
        $text->context_level = $info[0] . ")";
        return $text;
    }

    private function getNameSubject($subject_id = 0) {
        $data['subject'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getcategory'));
        $text = "";

        foreach ($data['subject'] as $key => $value) {
            if ($value->mul_category_id == $subject_id) {
                $text = $value->mul_category_name;
            }
        }

        return $text;
    }

    private function default_select($data, $category, $level, $conetxt) {
        $rs = [];
        if ($data['subject_id'] == 0 || $data['subject_id'] == "") {
            $rs['subject']['id'] = 0;
            $rs['subject']['name'] = "ค้นหาจากรายวิชา";
            $rs['subject']['nav_name'] = "ทุกรายวิชา";
        } else {
            $rs['subject']['id'] = $data['subject_id'];
            foreach ($category as $key => $value) {
                if ($rs['subject']['id'] == $value->mul_category_id) {
                    $rs['subject']['name'] = $value->mul_category_name;
                    $rs['subject']['nav_name'] = $value->mul_category_name;
                }
            }
        }

        if ($data['level_id'] == 0 || $data['level_id'] == "") {
            $rs['level']['id'] = 0;
            $rs['level']['name'] = "ค้นหาจากระดับชั้น";
            $rs['level']['nav_name'] = "ทุกระดับชั้น";
        } else {
            $rs['level']['id'] = $data['level_id'];
            foreach ($level as $key => $value) {
                if ($rs['level']['id'] == $value->mul_level_id) {
                    $rs['level']['name'] = $value->mul_level_name;
                    $rs['level']['nav_name'] = $value->mul_level_name;
                }
            }
        }

        if ($data['knowledge_id'] == 0 || $data['knowledge_id'] == "") {
            $rs['knowledge']['id'] = 0;
            $rs['knowledge']['name'] = "ค้นหาจากสาระ";
            $rs['knowledge']['nav_name'] = "ทุกสาระการเรียนรู้";
        } else {
            $rs['knowledge']['id'] = $data['knowledge_id'];
            foreach ($conetxt as $key => $value) {
                if ($rs['knowledge']['id'] == $value->knowledge_context_id) {
                    $rs['knowledge']['name'] = $value->knowledge_context_name;
                    $rs['knowledge']['nav_name'] = $value->knowledge_context_name;
                }
            }
        }
        $rs['text_search'] = $data['q'];

        if ($data['q'] == "") {
            $rs['nav_search'] = $rs['subject']['nav_name'] . " > " . $rs['level']['nav_name'] . " > " . $rs['knowledge']['nav_name'];
        } else {
            $rs['nav_search'] = $rs['subject']['nav_name'] . " > " . $rs['level']['nav_name'] . " > " . $rs['knowledge']['nav_name'] . " > " . $rs['text_search'];
        }
        return $rs;
    }

    function curl_data($url) {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
//        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'trueplookpanya');
        $query = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $query;
    }

    public function detail2($content_id = '', $source_id = '', $sec = '') {

        $param = $_COOKIE;
        $this->load->model('users/users_model');
        $member_id = base64_decode(base64_decode(base64_decode($param['admin_member_id'])));
        $member_data = array();
        $member_group = '';

        if ($member_id != '' && $member_id != NULL && $param['admin_member_id'] != '' && $param['admin_member_id'] != NULL) {
            $member_data = $this->users_model->getUserProfile($member_id); //group_code
            $member_group = $member_data->group_code;            
        }

        $is_ID = preg_match('/-/', $content_id) ? TRUE : FALSE;

        if ($is_ID) {
            $spit_id = explode('-', $content_id);
            $content_id = $spit_id[0];
            $source_id = $spit_id[1];
        }

        echo 'content_id:' . $content_id . '<br>';
        echo 'source_id:' . $source_id . '<br>';
        echo 'MEMBER:' . $member_group . '<br>';
        $x_open = '';        
        if ($member_group != 'Member' && $member_group != 'member' && $member_group != 'Members' && $member_group != 'members' && $member_group != NULL && $member_group != '') {
            $x_open = '?x=open';
        }

        $vdo = file_get_contents(base_url() . 'api/knowledgebase/content/' . $content_id . '/' . $source_id . $x_open);
        var_dump($vdo);
        echo '<hr>';
        var_dump(json_decoder($vdo));
        echo '<hr>';

        echo '<h2>with Curl</h2>';
        $resp = $this->curl_data((base_url() . 'api/knowledgebase/content/' . $content_id . '/' . $source_id . '?x=open'));
        var_dump($resp);
        echo '<hr>';
        var_dump(json_decoder($resp));
        echo '<hr>';
    }

    public function detail($content_id = '', $source_id = '', $sec = '') {

        $this->load->helper('text');

        $data['IE_version'] = $this->check_IE();
        $data['isDetect'] = $this->Detect();

        $data['top_banner'] = json_decode(file_get_contents(base_url() . 'api/banner/bannerV3/11'));
        
        //$source_id = ($source_id == '00'?NULL:$source_id);
        
        $is_ID = preg_match('/-/', $content_id) ? TRUE : FALSE;
        if ($is_ID) {
            $spit_id = explode('-', $content_id);
            $content_id = $spit_id[0];
            $source_id = $spit_id[1];
        }

        $param = $_COOKIE;
        $this->load->model('users/users_model');
        $member_id = base64_decode(base64_decode(base64_decode($param['admin_member_id'])));
        $member_data = array();
        $member_group = '';

        if ($member_id != '' && $member_id != NULL && $param['admin_member_id'] != '' && $param['admin_member_id'] != NULL) {
            $member_data = $this->users_model->getUserProfile($member_id); //group_code
            $member_group = $member_data->group_code;
        }
        $x_open = '';
        if ($member_group != 'Member' && $member_group != 'member' && $member_group != 'Members' && $member_group != 'members' && $member_group != NULL && $member_group != '' && $member_group != 'MEM') {
            $x_open = '?x=open';
        }
        //exit();
        $rs = [];
        if (!empty($source_id)) {
            //echo 'มีวิดีโอ';
            $vdo = json_decode(file_get_contents(base_url() . 'api/knowledgebase/content/' . $content_id . '/' . $source_id . '' . $x_open));
            if (empty($vdo->other)) {
                show_404();

//                if (!empty($content_id)) {
//                    $rs = json_decode(file_get_contents(base_url() . 'api/knowledgebase/othercontent/' . $content_id));
//                    if (empty($rs->other)) {
//                        show_404();
//                    }
//                    $data['info'] = $rs->other[0];
//                    $context = $this->getNameContext($rs->other[0]->context_id);
//                    $data['info']->context_name = $context->context_name;
//                    $data['info']->context_level = $context->context_level;
//                    $data['info']->cat_super_name = $this->getNameSubject($data['info']->cat_super_id);
//                } else {
//                    show_404();
//                }
                //show_404();
            } else {
                $data['info'] = $vdo->other[0];
                $context = $this->getNameContext($vdo->other[0]->context_id);
                $data['info']->context_name = $context->context_name;
                $data['info']->context_level = $context->context_level;
                //$data['info']->cat_super_name = $this->getNameSubject($data['info']->cat_super_id);

                if ($data['isDetect'] == '') {
                    $data['info']->vdo_width = 600;
                    $data['info']->vdo_height = 350;
                } else if ($data['isDetect'] == 'mobile') {
                    $data['info']->vdo_width = 350;
                    $data['info']->vdo_height = 250;
                } else if ($data['isDetect'] == 'tablet') {
                    $data['info']->vdo_width = 600;
                    $data['info']->vdo_height = 350;
                }
            }
            $this->template->meta_og_image = $data['info']->thumbnail;
        } else {
            //echo 'ไม่มีวิดีโอ';

            if (!empty($content_id)) {
                $rs = json_decode(file_get_contents(base_url() . 'api/knowledgebase/content/' . $content_id));
    //                _vd($rs);
                if (empty($rs->other)) {
                    show_404();
                }

                $data['info'] = $rs->other[0];
                $context = $this->getNameContext($rs->other[0]->context_id);
                $data['info']->context_name = $context->context_name;
                $data['info']->context_level = $context->context_level;
                //$data['info']->cat_super_name = $this->getNameSubject($data['info']->cat_super_id);
            } else {
                show_404();
            }
        }

        //audio 'http://static.trueplookpanya.com/trueplookpanya/media/hash_knowledge/2559/10559/AUDA1049010559.mp3'
        //doc 'http://www.trueplookpanya.com/data/product/media/hash_knowledge/3244/43244/DOCA3000043244.pdf'
        //reder -> context , Level ,subject
        $menu = $data['info']->context_all;
        //_vd($menu);
        if ($menu) {
            $data_lid = array();
            $data_sid = array();
            $data_cid = array();
            $data_ssid = array();

            foreach ($menu as $k => $v) {

                $data_lid[$k]['lid'] = $v->cat_level_id;
                $data_lid[$k]['lname'] = $v->cat_level_name;
                //           
                $data_sid[$k]['sid'] = $v->cat_super_id;
                $data_sid[$k]['sname'] = $v->cat_super_name;
                ///
                $data_cid[$k]['sid'] = $v->cat_super_id;
                $data_cid[$k]['cid'] = $v->cat_id;
                $data_cid[$k]['cname'] = $v->cat_name;
                ////
                $data_ssid[$k]['sid'] = $v->cat_super_id;
                $data_ssid[$k]['cid'] = $v->cat_id;
                //$data_ssid[$k]['ssname'] = $v->context_name;
                $data_ssid[$k]['ssid'] = $v->context_id;

                $info = split(") ", $v->context_name);
                $data_ssid[$k]['ssname'] = $info[0] . ")";
                $data_ssid[$k]['ssname_level'] = $info[1];
            }
            $data['menu_lid'] = array_unique($data_lid, SORT_REGULAR);
            $data['menu_sid'] = array_unique($data_sid, SORT_REGULAR);
            $data['menu_cid'] = array_unique($data_cid, SORT_REGULAR);
            $data['menu_ssid'] = array_unique($data_ssid, SORT_REGULAR);
        } else {
            $data_lid = array();
            $data_sid = array();
            $data_cid = array();
            
            $data_lid[0]['lid'] = $data['info']->cat_level_id;
            $data_lid[0]['lname'] = $data['info']->cat_level_name;

            $data_sid[0]['sid'] = $data['info']->cat_super_id;
            $data_sid[0]['sname'] = $data['info']->cat_super_name;
            
            $data_cid[0]['sid'] = $data['info']->cat_super_id;
            $data_cid[0]['cid'] = $data['info']->cat_id;
            $data_cid[0]['cname'] = $data['info']->cat_name;


            $data['menu_lid'] = $data_lid;
            $data['menu_sid'] = $data_sid;
            $data['menu_cid'] = $data_cid;
        }
        //_vd($data);
        //set Meta html
        $meta_title = $data['info']->content_subject . " : คลังความรู้ ทรูปลูกปัญญา";

        $meta_desc = $data['info']->content_subject . " "
                . "ระดับชั้น " . $data['info']->cat_level_name . " "
                . "วิชา " . $data['info']->cat_super_name . " "
                . "สาระ " . $data['info']->cat_name . " "
                . "ตัวชี้วัด " . $data['info']->context_level . " " . $data['info']->context_name . " "
                . " " . $data['info']->content_stage . " "
                . " " . $data['info']->cat_level_name . " "
                . " ติว แนวข้อสอบ education สาระ บทความ คลังความรู้ ทรูปลูกปัญญา";

        $this->template->meta_title = $meta_title;
        $this->template->meta_description = $meta_desc;
        $this->template->meta_keywords = $this->add_tag($data['info']->keyword) . ','
                . 'นานา สาระ,ความรู้ ,สาระน่ารู้ ,ความรู้รอบตัว,'
                . "ระดับชั้น " . $data['info']->cat_level_name . ","
                . "" . $data['info']->cat_super_name . ","
                . "" . $data['info']->cat_name . ","
                . "" . $data['info']->context_level . "," . $data['info']->context_name;
        
        
        
        $this->template->meta_og_app_id = "704799662982418";
        $this->template->meta_og_title = $meta_title;
        $this->template->meta_site_name = 'ทรูปลูกปัญญาดอทคอม';
        $this->template->meta_og_description = $meta_desc;
        $this->template->meta_og_image = $data['info']->thumbnail!="http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default-img.jpg"?$data['info']->thumbnail:"http://static.trueplookpanya.com/assets/thumbshare_default.jpg";

        $this->template->meta_twitter_card = 'summary';
        $this->template->meta_twitter_title = $meta_title;
        $this->template->meta_twitter_description = $meta_desc;




        $this->template->view('data_detail', $data);
    }

    public function ajax_exam($content_id) {
        $input = $this->input->get();
        $content_id = 0;
        $data['exam_relate'] = json_decoder((file_get_contents(base_url() . "api/getrelate/exam/" . $content_id . "/9/order=last?sid=" . $input['sid'] . "&lid=" . $input['lid'] . "&cid=" . $input['cid'])));

        $this->load->view('load_exam', $data);
    }

    public function ajax_upsale_crosssale($content_id) {
        $input = $this->input->get();
        $data['isDetect'] = $this->Detect();
        $data['upsale_relate'] = json_decoder((file_get_contents(base_url() . "api/getrelate/knowledgeupsale/" . $content_id . '/12/rand')));
        $data['crosssale_relate'] = json_decoder((file_get_contents(base_url() . "api/getrelate/knowledgecrosssale/" . $content_id . "/12/rand")));

//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';

        $this->load->view('load_up_cross', $data);
    }

    public function ajax_vdo($content_id = 0, $source_id = '') {

        $data['IE_version'] = $this->check_IE();
        $data['isDetect'] = $this->Detect();
        $rs = [];
        if (!empty($source_id)) {
            // echo 'มีวิดีโอ';
            $arr = json_decode(file_get_contents(base_url() . 'api/knowledgebase/content/' . $content_id));

            //echo intval($source_id);
            //$data['right_relate']['vdo'] = object_to_array($arr);
            //_vd($arr);
            if (!empty($arr)) {
                foreach ($arr->other as $key => $value) {
                    if ($value->content_id_child != intval($source_id)) {
                        $data['right_relate']['vdo'][] = object_to_array($value);
                    }
                }
            }
            //echo count($data['right_relate']['vdo']);
            if (count($data['right_relate']['vdo']) < 5) {
                $max = 5;
                $box_old = count($data['right_relate']['vdo']);
                $loop = $max - $box_old;
                $sid = $this->input->get('sid');
                $lid = $this->input->get('lid');
                $cid = $this->input->get('cid');
                if ($sid >= 1000 && $sid < 9000) {
                    $box2 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/0/' . $loop . '/?sid=' . $sid . "&lid=" . $lid));
                } else {
                    $box2 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/0/' . $loop . '/?sid=' . $sid . "&lid=" . $lid . "&mul_content_id=" . $content_id . $lid . "&mul_source_id=" . $source_id));
                }
                foreach ($box2['data'] as $key => $v) {
                    if ($v->content_id == $content_id) {
                        unset($box2['data'][$key]);
                    }
                }
                //$cal_1['data'] = array_merge($data['right_relate']['vdo'], $box2['data']);
                $data['right_relate']['content'] = $box2;
                //_pr($data['right_relate']);
                //$data['right_relate']['vdo'] = $this->get_Length_Relate($data['right_relate']['vdo'], $content_id, 5);
            }
            //$this->template->meta_og_image = $data['info']->thumbnail;
        } else {
            //  echo 'ไม่มีวิดีโอ';
            //$data['right_relate']['content'];
            $sid = $this->input->get('sid');
            $lid = $this->input->get('lid');
            $box1 = array();
            if ($sid >= 1000 && $sid < 9000) {
                //$box1 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/' . $content_id . '/5'));
                $box1 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/0/' . 5 . '/?sid=' . $sid . "&lid=" . $lid . "&mul_content_id=" . $content_id));
            } else {
                $box1 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/0/' . 5 . '/?sid=' . $sid . "&lid=" . $lid . "&mul_content_id=" . $content_id));
            }

            //$box1['data'][0]['ccccc'] = 55555555555555555555;
            //_pr($box1['data']);

            if (count($box1['data']) < 5) {
                $max = 5;
                $box_old = count($box1['data']);
                $loop = $max - $box_old;

                $box2 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/0/' . $loop . '/?sid=' . $sid . "&lid=" . $lid));

                foreach ($box2['data'] as $key => $v) {
                    if ($v->content_id == $content_id) {
                        unset($box2['data'][$key]);
                    }
                }
                // echo 'befor add';
                // _pr($box2);
                //echo 'หลัง ADD';
                $cal_1['data'] = array_merge($box1['data'], $box2['data']);
                // _pr($cal_1['data']);
                //echo 'รอบสอง ADD';
                if (count($cal_1['data']) < 5) {

                    $max = 5;
                    $box_old = count($cal_1['data']);
                    $loop = $max - $box_old;
                    $box3 = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/0/' . $loop . '/?sid=' . $sid));

                    foreach ($box3['data'] as $key => $v) {
                        if ($v->content_id == $content_id) {
                            unset($box3['data'][$key]);
                        }
                    }

                    $box_end['data'] = array_merge($cal_1['data'], $box3['data']);

                    //end
                    $data['right_relate']['content'] = $box_end;
                } else {
                    $data['right_relate']['content'] = $cal_1;
                }
            } else {
                $data['right_relate']['content'] = $box1;
            }
        }
        $this->load->view('load_vdo_relate', $data);
    }

    private function add_tag($param) {
        $cms_tag = explode(' ', $param);
        $cms_tag2 = implode(',', $cms_tag);
        return $cms_tag2;
    }

    private function get_Length_Relate2($data, $content_id, $size) {
        $sum_data = count($data);

        $arr = [];
        $data_leng = $size;
        $loop = $data_leng - $sum_data;

//        echo 'sum:'.$sum_data.'<br>';
//        echo 'loop:'.$loop.'<br>';

        $data_loop = json_decoder(file_get_contents(base_url() . 'api/getrelate/knowledgecontent/' . $content_id . '/' . $loop))['data'];

        $addArray = [];
        foreach ($data_loop as $key => $value) {
            //$data[] = $value;
            $addArray[$key]['content_id_child'] = "";
            $addArray[$key]['content_id'] = $value['content_id'];
            $addArray[$key]['thumbnail'] = $value['thumbnail'];
            $addArray[$key]['content_subject'] = $value['topic'];
            $addArray[$key]['content_url'] = $value['url'];
        }

        $arr = array_merge($data, $addArray);

        return $arr;
    }

    private function getNameContext2($context_id = 0) {
        $data['context'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getcontext'));
        $text = new stdClass();

        foreach ($data['context'] as $key => $value) {
            if ($value->knowledge_context_id == $context_id) {
                $textL = $value->knowledge_context_name;
            }
        }

        $info = split(") ", $textL);
        $text->context_name = $info[1];
        $text->context_level = $info[0] . ")";
        return $text;
    }

    private function getNameSubject2($subject_id = 0) {
        $data['subject'] = json_decode(file_get_contents(base_url() . 'api/knowledgebase/getcategory'));
        $text = "";

        foreach ($data['subject'] as $key => $value) {
            if ($value->mul_category_id == $subject_id) {
                $text = $value->mul_category_name;
            }
        }

        return $text;
    }

    private function default_select2($data, $category, $level, $conetxt) {
        $rs = [];
        if ($data['subject_id'] == 0 || $data['subject_id'] == "") {
            $rs['subject']['id'] = 0;
            $rs['subject']['name'] = "ค้นหาจากรายวิชา";
            $rs['subject']['nav_name'] = "ทุกรายวิชา";
        } else {
            $rs['subject']['id'] = $data['subject_id'];
            foreach ($category as $key => $value) {
                if ($rs['subject']['id'] == $value->mul_category_id) {
                    $rs['subject']['name'] = $value->mul_category_name;
                    $rs['subject']['nav_name'] = $value->mul_category_name;
                }
            }
        }

        if ($data['level_id'] == 0 || $data['level_id'] == "") {
            $rs['level']['id'] = 0;
            $rs['level']['name'] = "ค้นหาจากระดับชั้น";
            $rs['level']['nav_name'] = "ทุกระดับชั้น";
        } else {
            $rs['level']['id'] = $data['level_id'];
            foreach ($level as $key => $value) {
                if ($rs['level']['id'] == $value->mul_level_id) {
                    $rs['level']['name'] = $value->mul_level_name;
                    $rs['level']['nav_name'] = $value->mul_level_name;
                }
            }
        }

        if ($data['knowledge_id'] == 0 || $data['knowledge_id'] == "") {
            $rs['knowledge']['id'] = 0;
            $rs['knowledge']['name'] = "ค้นหาจากสาระการเรียนรู้ย่อย";
            $rs['knowledge']['nav_name'] = "ทุกสาระการเรียนรู้";
        } else {
            $rs['knowledge']['id'] = $data['knowledge_id'];
            foreach ($conetxt as $key => $value) {
                if ($rs['knowledge']['id'] == $value->knowledge_context_id) {
                    $rs['knowledge']['name'] = $value->knowledge_context_name;
                    $rs['knowledge']['nav_name'] = $value->knowledge_context_name;
                }
            }
        }
        $rs['text_search'] = $data['q'];

        if ($data['q'] == "") {
            $rs['nav_search'] = $rs['subject']['nav_name'] . " > " . $rs['level']['nav_name'] . " > " . $rs['knowledge']['nav_name'];
        } else {
            $rs['nav_search'] = $rs['subject']['nav_name'] . " > " . $rs['level']['nav_name'] . " > " . $rs['knowledge']['nav_name'] . " > " . $rs['text_search'];
        }
        return $rs;
    }

}
