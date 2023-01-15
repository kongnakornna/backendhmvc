<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class User extends MY_Controller {
private $look_user_idx;
private $user_idx;
private $username;
private $user_type;
function __construct(){
        // Construct the parent class
        parent::__construct();
        header('Content-Type: text/html; charset=utf-8');
        $this->load->model("Admin_model");
        $this->load->model("ict/Ict_model");
        $this->load->library('main_library');
        date_default_timezone_set('Asia/Bangkok');
        #####################
        $this->load->model('Csv_model');  
        $this->load->library('Csvimport');  
	   $this->load->library('Xlsx');
        //$this->load->library('upload');
        #####################
        $this->user_idx = (!empty($_COOKIE['useridx']) ? $_COOKIE['useridx'] : "4152");
        $this->username = (!empty($_COOKIE['uname']) ? $_COOKIE['uname'] : "");
        $this->user_type = (!empty($_COOKIE['utype']) ? $_COOKIE['utype'] : "");
        $this->user_fullname = (!empty($_COOKIE['fullname']) ? $_COOKIE['fullname'] : "");
        $this->look_user_idx = (!empty($_COOKIE['look_user']) ? $_COOKIE['look_user'] : "");
        #####################
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
        $this->log();
}
public function logall() {
$session=$this->load->library('session');
/*
$uname=$_COOKIE['uname'];
$utype=$_COOKIE['utype'];
$user_id=$_COOKIE['useridx'];
*/
$input=@$this->input->get();
if($input==null){
$input=@$this->input->post();  
}
$user_id=@$input['user_id']; 
if($user_id==null){
   $user_id=@$input['user_id']; 
}
if($user_id==null){$user_id=null;}
$ip_addess=@$input['ip_addess'];
if($ip_addess==null){$ip_addess=null;}
$page=@$input['page'];
$date_start=@$input['date_start'];
if($date_start==null){$date_start=null;}
$date_end=@$input['date_end'];
if($date_end==null){$date_end=null;}
$date=@$input['date'];
if($date==null){$date=null;}
$month=@$input['month'];
if($month==null){$month=null;}
$year=@$input['year'];
if($year==null){$year=null;}
$status=@$input['status'];
if($status==null){$status=2;}
$perpage=@$input['perpage'];
if($perpage==null){$perpage=20;}
$limit=$perpage;
$orderby=@$input['orderby'];
if($orderby==null){$orderby=null;}

$search='user_id='.$user_id.'&date_start='.$date_start.'&date_end='.$date_end.'&date='.$date.'&month='.$month.'&year='.$year.'&status='.$status.'&orderby='.$orderby.'&perpage='.$perpage;
//$this->load->library("pagination");
$this->load->model("Historylog_model"); 
$wherearray=array();
$wherearray=array('date_start'=>$date_start,
                    'date_end'=>$date_end,
                    'date'=>$date,
                    'month'=>$month,
                    'year'=>$year,
                    'status'=>$status,
                    'user_id'=>$user_id);
$rec_count=$this->Historylog_model->select_data_log_rows($wherearray);
$total=$rec_count;
if($page==null){$page=1;}
$pages=@ceil($rec_count/$perpage);
$offset=($page-1)*$limit;
$start=$offset+1;
$end=@min(($offset+$limit),$total);
$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
        'options' => array(
            'default'   => 1,
            'min_range' => 1,
        ),
    )));
  // The "back" link
$userlogpage=base_url().'admin/user/logall';  
$prevlink=($page>1)?'<a href="'.$userlogpage.'?page=1&'.$search.'" title="First page">&laquo; หน้าแรก</a> <a href="?page=' . ($page-1) . '&'.$search.'" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
// The "forward" link    
$nextlink=($page<$pages)?'<a href="'.$userlogpage.'?page='.($page+1).'&'.$search.'" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '&'.$search.'" title="Last page"> หน้าสุดท้าย &raquo;</a>' : '<span class="disabled"> &rsaquo;</span> <span class="disabled">หน้าสุดท้าย &raquo;</span>';


 // Display the paging information
  // echo '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';




$url_link=$userlogpage;
$arraypage=array('url_link'=>$url_link,
                 'count_all'=>$rec_count,
                 'pages_all'=>$pages,
                 'offset'=>$offset,
                 'start'=>$start,
                 'end'=>$page,
                 'end'=>$end,
                 'page'=>$page,
                 'pages'=>$pages,
                 'total'=>$total,
                 'prevlink'=>$prevlink,
                 'nextlink'=>$nextlink,
                 );
$wherearray2=array();
$wherearray2=array('date_start'=>$date_start,
                    'date_end'=>$date_end,
                    'date'=>$date,
                    'month'=>$month,
                    'year'=>$year,
                    'status'=>2,
                    'user_id'=>$user_id,
                    'page'=>$offset,
                    'perpage'=>$limit,
                    'start'=>$status,
                    //'limit'=>$limit,
                    'rec_count'=>$rec_count,
                    'orderby'=>$orderby,
                    'ip_addess'=>$ip_addess);
  //echo'<hr><pre>  $wherearray2=>';print_r($wherearray2);echo'<pre><hr>';    
$log=$this->Historylog_model->select_data_log($wherearray2);
/* 
echo'<hr><pre> array page1=>';print_r($arraypage);echo'<pre><hr>'; 
echo'<hr><pre> log=>';print_r($log);echo'<pre><hr>';  
echo'<hr><pre> array page2=>';print_r($arraypage);echo'<pre><hr>'; Die();
*/
header('Content-Type: text/html; charset=utf-8');
$data_array['title_1'] = "History log all";
$data_array['title_2'] = "User ประวัติการใช้งานระบบ";
$data_array['base_crumb'][] = $this->main_library->set_basecrumb(base_url("ict"), "Home");
$data_array['base_crumb'][] = $this->main_library->set_basecrumb(base_url("pracharathschool/school"), "ข้อมูลโรงเรียน");
$data_array['arraypage']=$arraypage;
$data_array['log']=$log;
//$data_array=array('arraypage'=>$arraypage,'log'=>$log);
$this->template("admin/userlogall", $data_array);
}
public function log() {
$session=$this->load->library('session');
$uname=$_COOKIE['uname'];
$utype=$_COOKIE['utype'];
$user_id=$_COOKIE['useridx'];
$input=@$this->input->get();
if($input==null){
$input=@$this->input->post();  
}
//echo'<hr><pre>  input=>';print_r($input);echo'<pre><hr>';
if($user_id==null){
   $user_id=@$input['user_id']; 
}
if($user_id==null){$user_id=null;}
$ip_addess=@$input['ip_addess'];
if($ip_addess==null){$ip_addess=null;}
$page=@$input['page'];
$date_start=@$input['date_start'];
if($date_start==null){$date_start=null;}
$date_end=@$input['date_end'];
if($date_end==null){$date_end=null;}
$date=@$input['date'];
if($date==null){$date=null;}
$month=@$input['month'];
if($month==null){$month=null;}
$year=@$input['year'];
if($year==null){$year=null;}
$status=@$input['status'];
if($status==null){$status=2;}
$perpage=@$input['perpage'];
if($perpage==null){$perpage=30;}
$limit=$perpage;
$orderby=@$input['orderby'];
if($orderby==null){$orderby=null;}

$search='user_id='.$user_id.'&date_start='.$date_start.'&date_end='.$date_end.'&date='.$date.'&month='.$month.'&year='.$year.'&status='.$status.'&orderby='.$orderby.'&perpage='.$perpage;
//$this->load->library("pagination");
$this->load->model("Historylog_model"); 
$wherearray=array();
$wherearray=array('date_start'=>$date_start,
                    'date_end'=>$date_end,
                    'date'=>$date,
                    'month'=>$month,
                    'year'=>$year,
                    'status'=>$status,
                    'user_id'=>$user_id);
$rec_count=$this->Historylog_model->select_data_log_rows($wherearray);
$total=$rec_count;
if($page==null){$page=1;}
$pages=@ceil($rec_count/$perpage);
$offset=($page-1)*$limit;
$start=$offset+1;
$end=@min(($offset+$limit),$total);
$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
        'options' => array(
            'default'   => 1,
            'min_range' => 1,
        ),
    )));
  // The "back" link
$userlogpage=base_url().'admin/user/log';  
$prevlink=($page>1)?'<a href="'.$userlogpage.'?page=1&'.$search.'" title="First page">&laquo; หน้าแรก</a> <a href="?page=' . ($page-1) . '&'.$search.'" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
// The "forward" link    
$nextlink=($page<$pages)?'<a href="'.$userlogpage.'?page='.($page+1).'&'.$search.'" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '&'.$search.'" title="Last page"> หน้าสุดท้าย &raquo;</a>' : '<span class="disabled"> &rsaquo;</span> <span class="disabled">หน้าสุดท้าย &raquo;</span>';


 // Display the paging information
  // echo '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';



for($i=1;$i<=$pages;$i++){
 $page_link=$i;
}
$url_link=$userlogpage;
$arraypage=array('url_link'=>$url_link,
                 'count_all'=>$rec_count,
                 'pages_all'=>$pages,
                 'offset'=>$offset,
                 'start'=>$start,
                 'end'=>$page,
                 'end'=>$end,
                 'page'=>$page,
                 'pages'=>$pages,
                 'total'=>$total,
                 'page_link'=>$page_link,
                 'prevlink'=>$prevlink,
                 'nextlink'=>$nextlink,
                 );
$wherearray2=array();
$wherearray2=array('date_start'=>$date_start,
                    'date_end'=>$date_end,
                    'date'=>$date,
                    'month'=>$month,
                    'year'=>$year,
                    'status'=>2,
                    'user_id'=>$user_id,
                    'page'=>$offset,
                    'perpage'=>$limit,
                    'start'=>$status,
                    //'limit'=>$limit,
                    'rec_count'=>$rec_count,
                    'orderby'=>$orderby,
                    'ip_addess'=>$ip_addess);
  //echo'<hr><pre>  $wherearray2=>';print_r($wherearray2);echo'<pre><hr>';    
$log=$this->Historylog_model->select_data_log($wherearray2);
/* 
echo'<hr><pre> array page1=>';print_r($arraypage);echo'<pre><hr>'; 
echo'<hr><pre> log=>';print_r($log);echo'<pre><hr>';  
echo'<hr><pre> array page2=>';print_r($arraypage);echo'<pre><hr>'; Die();
*/
header('Content-Type: text/html; charset=utf-8');
$data_array['title_1'] = "History log";
$data_array['title_2'] = "User ประวัติการใช้งานระบบ";
$data_array['base_crumb'][] = $this->main_library->set_basecrumb(base_url("ict"), "Home");
$data_array['base_crumb'][] = $this->main_library->set_basecrumb(base_url("pracharathschool/school"), "school");
$data_array['arraypage']=$arraypage;
$data_array['log']=$log;
//$data_array=array('arraypage'=>$arraypage,'log'=>$log);
$this->template("admin/userlog", $data_array);
}
public function report_users(){
     header('Content-Type: text/html; charset=utf-8');
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
public function importstudent(){
header('Content-Type: text/html; charset=utf-8');
$data['title_1'] = "ข้อมูลนักเรียน";
$data['title_2'] = "ข้อมูลนักเรียน รายโรงเรียน";
$data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("ict"), "Home");
$data['base_crumb'][] = $this->main_library->set_basecrumb(base_url("admin/importstudent"), "importstudent");
$limit='10';
$orderby='desc';
$datars=$this->Csv_model->select_school_student($limit,$orderby); 
$data['studentlist']=$datars['rs']; 
$data['student_row']=$datars['num_rows']; 
$data['remark_pmo_current'] = "";
$get=$this->input->get();
$this->template("admin/importstudent", $data);
}  
public function importdata(){
header('Content-Type: text/html; charset=utf-8');
$this->db->cache_delete('tbl_school_student');
$this->db->cache_delete('tbl_school_student_temp');
/*ข้อมูล student ให้ import ไปที่ tbl_school_student เริ่มจาก school code เป็นต้นไป*/
$data['studentlist'] = $this->Csv_model->get_tbl_school_student(); 
$data['error'] = '';    //initialize image upload error array to empty
ob_clean();
$filepath = FCPATH . 'uploads/';
#$filepath='./uploads/';
$config['upload_path']=$filepath; // โฟลเดอร์ ตำแหน่งเดียวกับ root ของโปรเจ็ค
#$config['allowed_types'] = '*';
$config['allowed_types']='csv|xls'; // ปรเเภทไฟล์ 
$config['max_size']= '0';  // ขนาดไฟล์ (kb)  0 คือไม่จำกัด ขึ้นกับกำหนดใน php.ini ปกติไม่เกิน 2MB
$config['file_name']= time();
$config['client_name']= time();
//$config['max_width']= '1024';  // ความกว้างรูปไม่เกิน
//$config['max_height'] = '768'; // ความสูงรูปไม่เกิน
//$config['file_name'] = 'mypicture';  // ชื่อไฟล์ ถ้าไม่กำหนดจะเป็นตามชื่อเพิม
//$this->upload->initialize($config);
$this->load->library('upload', $config);
// upload or error
if(!$this->upload->do_upload()) {
    $data['error'] = $this->upload->display_errors();
    $this->template("admin/importstudent", $data);
}
//prosses upload
$file_data=$this->upload->data();
$file_ext=$file_data['file_ext'];
if($file_ext=='.xls' || $file_ext=='.csv'){} else{
$msg=' ขออภับระบบรองรับ File .xls หรือ .csv เท่านั้น.';
$msg2=' กรุณาแก้ไขให้ถูกต้อง...';
echo $msg;   
$host_url = $this->config->item('base_url'); 
?>
<script src="<?php echo $host_url."assets/js/bootstrap.min.js"; ?>"></script>
<!-- Include Libraly Sweet alert -->
<script src="<?php echo $host_url."assets/library/sweetalert/dist/sweetalert2.min.js"; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $host_url."assets/library/sweetalert/dist/sweetalert2.css"; ?>">
<?php
$urlredirec=$host_url.'admin/importstudent';
?>
<script>
swal({ title: "Error ไม่อนุญาตให้ upload file ประเภท <?php echo $file_ext;?>!",
 text: "<?php echo $msg;?>!",
 type: "error"}).then(okay => {
   if(okay) {window.location.href = "<?php echo $urlredirec;?>";}
});
</script>
<?php
}

//echo'<hr><pre>  file_ext=>';print_r($file_ext);echo'<pre>'; die();
if($file_data){
//prosses upload csv berhasil serta memproses insert data ke database
 $file_name=$file_data['file_name'];
 $file_type=$file_data['file_type'];
 $file_path=$file_data['file_path'];
 $full_path=$file_data['full_path'];
 $raw_name=$file_data['raw_name'];
 $orig_name=$file_data['orig_name'];
 $client_name=$file_data['client_name'];
 $file_ext=$file_data['file_ext'];
 $file_size=$file_data['file_size'];
 $is_image=$file_data['is_image'];
 $image_width=$file_data['image_width'];
 $image_height=$file_data['image_height'];
 $image_type=$file_data['image_type'];
 $image_size_str=$file_data['image_size_str'];
$fileext =str_replace('.','',$file_ext);
$file_path_old=$full_path;   
$datenow=date('y-m-d-h-i-s'); 
$file_new_name=$filepath.'Student_'.$datenow.$file_ext;  
$file_new_name2=$filepath.'Student_'.$datenow.".".pathinfo($file_path, PATHINFO_EXTENSION); 
$file_path=$file_new_name.$file_ext;
$file_path_client=$filepath.$client_name;

// rename($file_path_old,$file_new_name);
$new_file = @rename($file_path_client,$file_new_name);
$final_file_name=$file_new_name;

// echo'<hr><pre>  file_data>';print_r($new_file);echo'<pre>';
// die(); 
#echo'<hr><pre>  file_path_old=>';print_r($file_path_old);echo'<pre>';  
#echo'<hr><pre>  file_new_name=>';print_r($file_path);echo'<pre>'; 
#echo'<hr><pre>  file_path=>';print_r($file_new_name);echo'<pre>'; 
$datacsvarray=$this->csvimport->get_array($file_new_name);
#echo'<hr><pre>  readfile data=>';print_r($datacsvarray);echo'<pre>';die(); 
#######################################################    
$file_path=$file_new_name;

// file_exists(FCPATH".$full_path.");

#$FCPATH=FCPATH;
#$filepath=$FCPATH.'uploads/';
//$pf=FCPATH."upload/$orig_name";
$pf="./upload/$orig_name";
if( file_exists($pf)){
#echo "มีไฟล์ ".$pf;
}else{ 
#echo "ไม่มีไฟล์ ".$pf;
}

/*
$csvarray=$this->csvimport->get_array($file_path, "", TRUE);
//echo'<hr><pre> csvarray=>';print_r($csvarray);echo'<pre> <hr>'; Die();
  
if(file_exists(".$full_path.")){   
   
     echo'<hr><pre> file_data=>';print_r($file_data);echo'<pre> '; // die();
     echo'<hr><pre> file_name=>';print_r($file_name);echo'<pre>'; 
     echo'<hr><pre> file_ext=>';print_r($file_ext);echo'<pre>'; 
     echo'<hr><pre> fileext=>';print_r($fileext);echo'<pre>'; 
     echo'<hr><pre> file_size=>';print_r($file_size);echo'<pre>';
     echo'<hr><pre> file_path=>';print_r($file_path);echo'<pre>'; 
     echo "<hr>The file exists  มี file";  
  
}else{
  
     echo'<hr><pre> file_name=>';print_r($file_name);echo'<pre>';
     echo'<hr><pre> final_file_name=>';print_r($final_file_name);echo'<pre>';  
     echo'<hr><pre> file_ext=>';print_r($file_ext);echo'<pre>'; 
     echo'<hr><pre> fileext=>';print_r($fileext);echo'<pre>'; 
     echo'<hr><pre> file_size=>';print_r($file_size);echo'<pre>';
     echo'<hr><pre> file_path=>';print_r($file_path);echo'<pre>'; 
     echo "<hr>The file does not exist  ไม่มี file";  
    
}
########################################################
//Die();
*/
		
if($fileext=='xls'){
//echo' type=>excel <hr>'; 
list($header,$values)=$this->xlsx->convert($file_path); 
$header_excel=$header['1'];
#################
#echo'<hr><pre> header_excel=>';print_r($header_excel);echo'<pre> <hr>';
//echo'<hr><pre> $values_excel=>';print_r($values);echo'<pre> <hr>';die();

$savevalues=array();
foreach($values as $item){
	#echo count($item);echo'<hr><pre>item=>';print_r($item);echo'<pre> <hr>';die();	
	$ar['a']['school_code']=$item['A'];
	$ar['a']['ref_school_name']=$item['B'];
	$ar['a']['citizen_id']=$item['C'];
	$ar['a']['std_grade']=$item['D'];
	$ar['a']['std_class']=$item['E'];
	$ar['a']['std_code']=$item['F'];
	$ar['a']['std_sex']=$item['G'];
	$ar['a']['std_prefixname_th']=$item['H'];
	$ar['a']['std_firstname_th']=$item['I'];
	$ar['a']['std_lastname_th']=$item['J'];
	$ar['a']['std_firstname_en']=$item['K'];
	$ar['a']['std_lastname_en']=$item['L'];
	$ar['a']['std_birthdate']=$item['M'];
	$ar['a']['std_age_y']=$item['N'];
	$ar['a']['std_age_m']=$item['O'];
	$ar['a']['std_blood_group']=$item['P'];
	$ar['a']['std_ethnicity']=$item['Q'];
	$ar['a']['std_nationality']=$item['R'];
	$ar['a']['std_religion']=$item['S'];
	$ar['a']['std_brother_older']=$item['T'];
	$ar['a']['std_brother_younger']=$item['U'];
	$ar['a']['std_sister_older']=$item['V'];
	$ar['a']['std_sister_younger']=$item['W'];
	$ar['a']['std_kids_order']=$item['X'];
	$ar['a']['parent_status']=$item['Y'];
     $ar['a']['father_citizen_id']=$item['Z'];
	$ar['a']['father_prefixname_th']=$item['AA'];
	$ar['a']['father_firstname_th']=$item['AB'];
	$ar['a']['father_lastname_th']=$item['AC'];
	$ar['a']['father_salary']=$item['AD'];
     $ar['a']['father_phone']=$item['AE'];
	$ar['a']['mother_citizen_id']=$item['AF'];
	$ar['a']['mother_prefixname_th']=$item['AG'];
	$ar['a']['mother_firstname_th']=$item['AH'];
	$ar['a']['mother_lastname_th']=$item['AI'];
	$ar['a']['mother_salary']=$item['AJ'];
	$ar['a']['mother_phone']=$item['AK'];
	$ar['a']['parent_mode']=$item['AL'];
	$ar['a']['parent_citizen_id']=$item['AM'];
     $ar['a']['parent_prefixname_th']=$item['AN'];
	$ar['a']['parent_firstname_th']=$item['AO'];
	$ar['a']['parent_lastname_th']=$item['AP'];
	$ar['a']['parent_salary']=$item['AQ'];
	$ar['a']['parent_phone']=$item['AR'];
	$ar['a']['home_code']=$item['AS'];
	$ar['a']['home_address']=$item['AT'];
	$ar['a']['home_moo']=$item['AU'];
	$ar['a']['home_street']=$item['AV'];
	$ar['a']['home_district']=$item['AW'];
	$ar['a']['home_city']=$item['AX'];
	$ar['a']['home_province']=$item['AY'];
	$ar['a']['home_postcode']=$item['AZ'];
	$ar['a']['home_phone']=$item['BA'];
	$ar['a']['current_home_code']=$item['BB'];
	$ar['a']['current_home_address']=$item['BC'];
	$ar['a']['current_home_moo']=$item['BD'];
	$ar['a']['current_home_street']=$item['BE'];
	$ar['a']['current_home_district']=$item['BF'];
	$ar['a']['current_home_city']=$item['BG'];
     $ar['a']['current_home_province']=$item['BH'];
	$ar['a']['current_home_postcode']=$item['BI'];
	$ar['a']['current_home_phone']=$item['BJ'];
	$ar['a']['std_weight']=$item['BK'];
	$ar['a']['std_height']=$item['BL'];
	$ar['a']['rich_type']=$item['BM'];
	$ar['a']['how_sleep']=$item['BN'];
	$ar['a']['how_shirt']=$item['BO'];
	$ar['a']['how_equip']=$item['BP'];
	$ar['a']['how_study']=$item['BQ'];
	$ar['a']['how_lunch']=$item['BR'];
	$ar['a']['how_disable_person']=$item['BS'];
	$ar['a']['distance_to_school_old']=$item['BT'];
	$ar['a']['distance_to_school_new']=$item['BU'];
	$ar['a']['distance_to_school_water']=$item['BV'];
	$ar['a']['time_to_school']=$item['BW'];
	$ar['a']['go_to_school']=$item['BX'];
	$ar['a']['student_type']=$item['BY'];
	$ar['a']['how_magazine']=$item['BZ'];
//echo count($ar['a']);
//echo'<hr><pre>values=>';print_r($ar['a']);echo'<pre> <hr>';die();	
$insert_data=$ar['a'];
$item=$ar['a'];
//echo'<hr><pre>$insert_data=>';print_r($insert_data);echo'<pre> <hr>';die();
$prosces=$this->Csv_model->insert_csv($item);
//$savevalues[]=$insert_data;
 //$savevalues[] = iconv('TIS-620', 'UTF-8', $item);
}
$this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
$file=$file_path;
if (!unlink($file)){
	echo ("Error deleting $file");
}
else{
   echo ("Deleted $file");
}

############### user Historylog insert Start###################
$session=$this->load->library('session');
$uname=$_COOKIE['uname'];
$utype=$_COOKIE['utype'];
$user_id=$_COOKIE['useridx'];
$user_type='2';
$code='200';
$modules='Import data csv student';
$process='insert db';
$message='user_id '.$user_id.' user '.$uname.' user type '.$utype.' insert log Import data csv to tbl_school_student file '.$file_new_name;
############### user Historylog insert Start###################
$insertdatalog=array('user_id'=>$user_id,
                'user_type'=>$user_type,
                'code'=> $code,
                'modules'=>$modules,
                'process'=>$process,
                'message'=>$message,
            );
$this->load->model("Historylog_model"); 
$datars=$this->Historylog_model->insert_data_log($insertdatalog);
############### user Historylog insert  End ###################


redirect(base_url().'admin/importdata');
/*
echo'<hr><pre>savevalues=>';print_r($savevalues);echo'<pre> <hr>'; 
echo'<hr><pre>values=>';print_r($values);echo'<pre> <hr>';die();
*/
}
if($fileext=='csv'){
/*
          echo' type=>csv'; 
          echo'<hr><pre> file_name=>';print_r($file_name);echo'<pre>'; 
		echo'<hr><pre> file_ext=>';print_r($file_ext);echo'<pre>'; 
		echo'<hr><pre> fileext=>';print_r($fileext);echo'<pre>'; 
		echo'<hr><pre> file_size=>';print_r($file_size);echo'<pre>';
		echo'<hr><pre> file_path=>';print_r($file_path);echo'<pre>'; 
          Die();
*/ 
$csvarray=$this->csvimport->get_array($file_path, "", TRUE);
 #echo'<hr><pre> $csvarray=>';print_r($csvarray);echo'<pre>';  Die();

$arrayName = array();
$arraycolum = array(
	0 => 'school_code',
	1 => 'ref_school_name',
	2 => 'citizen_id',
	3 => 'std_grade',
	4 => 'std_class',
	5 => 'std_code',
	6 => 'std_sex',
	7 => 'std_prefixname_th',
	8 => 'std_firstname_th',
	9 => 'std_lastname_th',
	10 => 'std_firstname_en',
	11 => 'std_lastname_en',
	12 => 'std_birthdate',
	13 => 'std_age_y',
	14 => 'std_age_m',
	15 => 'std_blood_group',
	16 => 'std_ethnicity',
	17 => 'std_nationality',
	18 => 'std_religion',
	19 => 'std_brother_older',
	20 => 'std_brother_younger',
	21 => 'std_sister_older',
	22 => 'std_sister_younger',
	23 => 'std_kids_order',
	24 => 'parent_status',
     25 => 'father_citizen_id',
	26 => 'father_prefixname_th',
	27 => 'father_firstname_th',
	28 => 'father_lastname_th',
	29 => 'father_salary',
     30 => 'father_phone',
	31 => 'mother_citizen_id',
	32 => 'mother_prefixname_th',
	33 => 'mother_firstname_th',
	34 => 'mother_lastname_th',
	35 => 'mother_salary',
	36 => 'mother_phone',
	37 => 'parent_mode',
	38 => 'parent_citizen_id',
     39 => 'parent_prefixname_th',
	40 => 'parent_firstname_th',
	41 => 'parent_lastname_th',
	42 => 'parent_salary',
	43 => 'parent_phone',
	44 => 'home_code',
	45 => 'home_address',
	46 => 'home_moo',
	47 => 'home_street',
	48 => 'home_district',
	49 => 'home_city',
	50 => 'home_province',
	51 => 'home_postcode',
	52 => 'home_phone',
	53 => 'current_home_code',
	54 => 'current_home_address',
	55 => 'current_home_moo',
	56 => 'current_home_street',
	57 => 'current_home_district',
	58 => 'current_home_city',
     59 => 'current_home_province',
	60 => 'current_home_postcode',
	61 => 'current_home_phone',
	62 => 'std_weight',
	63 => 'std_height',
	64 => 'rich_type',
	65 => 'how_sleep',
	66 => 'how_shirt',
	67 => 'how_equip',
	68 => 'how_study',
	69 => 'how_lunch',
	70 => 'how_disable_person',
	71 => 'distance_to_school_old',
	72 => 'distance_to_school_new',
	73 => 'distance_to_school_water',
	74 => 'time_to_school',
	75 => 'go_to_school',
	76 => 'student_type',
	77 => 'how_magazine'
);

/*
echo'<hr><pre> count csv_array=>';echo count($csvarray);echo'<pre>';
echo'<hr><pre> csvarray=>';print_r($csvarray);echo'<pre>'; Die();  
*/
foreach($csvarray as $k => $v){
	$i=0;
	foreach ($v as $key => $value) {
		// $setk = iconv(mb_detect_encoding($key, mb_detect_order(), true), "UTF-8", $key);
		$setk = $arraycolum[$i];
		$arrayName[$k][$setk] = iconv(mb_detect_encoding($value, mb_detect_order(), true), "UTF-8", $value);
		$i++;
	}
}
 /*
echo'<hr><pre> count arrayName=>';echo count($arrayName);echo'<pre> ';
echo'<hr><pre> arrayName=>';print_r($arrayName);echo'<pre>';  Die();
 */

foreach($arrayName as $item) {

//echo'<hr><pre>$insert_data=>';print_r($insert_data);echo'<pre> <hr>';die();
$prosces=$this->Csv_model->insert_csv($item);
#$prosces_temp=$this->Csv_model->insert_csv_temp($item);

//$savevalues[]=$insert_data;
 //$savevalues[] = iconv('TIS-620', 'UTF-8', $item);
}

$file=$file_path;
if($file!==null){
 if (!unlink($file)){
	//echo ("Error deleting $file");
}else{
   //echo ("Deleted $file");
}    
}
#echo'<hr><pre> prosces=>';print_r($prosces);echo'<pre> <hr>';die();
#######################           
if ($prosces=='') {

                
$this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
#echo'<hr><pre> 2file_path=>';print_r($file_path);echo'<pre> <hr>'; 				
$file = $file_path;
if (!unlink($file)){
	echo ("Error deleting $file");
}else{
   echo ("Deleted $file");
}

######################################
$data_error['error'] = "Error occured";
$data_error['title_1'] = "ข้อมูลนักเรียน";
$data_error['title_2'] = "ข้อมูลนักเรียน รายโรงเรียน";
############### user Historylog insert Start###################
$session=$this->load->library('session');
$uname=$_COOKIE['uname'];
$utype=$_COOKIE['utype'];
$user_id=$_COOKIE['useridx'];
$user_type='2';
$code='200';
$modules='Error Import data csv student';
$process='Error insert db';
$message='user_id '.$user_id.' user '.$uname.' user type '.$utype.' Error insert log Import data csv to tbl_school_student file '.$file_new_name;
############### user Historylog insert Start###################
$insertdatalog=array('user_id'=>$user_id,
                'user_type'=>$user_type,
                'code'=> $code,
                'modules'=>$modules,
                'process'=>$process,
                'message'=>$message,
            );
$this->load->model("Historylog_model"); 
$datars=$this->Historylog_model->insert_data_log($insertdatalog);
############### user Historylog insert  End ###################
#echo'<hr><pre> data_error=>';print_r($data_error);echo'<pre> <hr>'; #die();
redirect(base_url().'admin/importstudent');
#######################################  

}else{ 
$this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
#echo'<hr><pre> 2file_path=>';print_r($file_path);echo'<pre> <hr>'; 				
$file = $file_path;
if (!unlink($file)){
	echo ("Error deleting $file");
}else{
   echo ("Deleted $file");
}
############### user Historylog insert Start###################
$session=$this->load->library('session');
$uname=$_COOKIE['uname'];
$utype=$_COOKIE['utype'];
$user_id=$_COOKIE['useridx'];
$user_type='2';
$code='200';
$modules='Import data csv student';
$process='insert db';
$message='user_id '.$user_id.' user '.$uname.' user type '.$utype.' insert log Import data csv to tbl_school_student file '.$file_new_name;
############### user Historylog insert Start###################
$insertdatalog=array('user_id'=>$user_id,
                'user_type'=>$user_type,
                'code'=> $code,
                'modules'=>$modules,
                'process'=>$process,
                'message'=>$message,
            );
$this->load->model("Historylog_model"); 
$datars=$this->Historylog_model->insert_data_log($insertdatalog);
############### user Historylog insert  End ###################


redirect(base_url().'admin/importstudent');
//echo "<pre>"; print_r($insert_data);

 
}  
######################				
}	
###########################
  }
 } 
}