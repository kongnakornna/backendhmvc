<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Member {

    public $CI;

    function __construct() {
        $this->CI = & get_instance();
    }

    #$table = 0; means use table mul_content
    #$table = 1; means use table post
    #$table = 2; means use table exam
    #$table = 3; means use table edu_photo_gallery
    #$table = 4; means use table blog diary
    #$table = 5; means use table blog exam
    #$table = 6; means use table etc content
    #$table = 7; means use table blog
    #$table = 8; means use table personnel
    #$table = 12; means use table tv_espisode
    #$table = 18; means use table Survey
    #$table = 24; means use table ตอบคำถามจาก Magazine
    /*
      " คะแนนจากการสร้างสื่อการเรียนการสอน "   type = 1  val = 30
      "คะแนนจากการสร้างข้อสอบ"  type = 2 val = 30
      "คะแนนที่ได้จากการสร้าง Diary"    type = 3 val = 30
      "คะแนนจากการอัพโหลดอัลบั้มภาพถ่ายเพื่อการศึกษา"    type = 4 val = 10
      " คะแนนจากการอัพโหลดรูปถ่ายส่วนตัว"    type = 5 val = 10
      "คะแนนจากการทำข้อสอบ" type = 6  val = 10
      "คะแนนจากการกรอกข้อมูลส่วนตัวได้ครบถ้วน"   type =7 val = 100

      "คะแนนจากเนื้อหาที่ถูกโหวต"   type = 8 val = 1-5
      "คะแนนจากการโหวตให้ผู้อื่น"    type = 9 val = 5
      "คะแนนจากเนื้อหาที่มีการแสดงความคิดเห็นมากที่สุด "    type = 10 val = 20
      "คะแนนจากเนื้อหาที่ได้รับการโหวตมากที่สุด "   type = 11   val = 20
      "คะแนนที่ได้จากการแจ้งลบความคิดเห็นหรือเนื้อหาที่ไม่เหมาะสม "   type = 12   val = 20
      "Recommend"   type = 13   val = 10
      "คะแนนจากการโหวตเนื้อหาล่าสุด"   type =14 val  = 10

      "extra"   type =15 val = 1
      "คะแนนจากการแนะนำเพื่อนให้มาสมัครสมาชิก"   type =16 val = 30
      "คะแนนตอบรับการสมัครเป็นสมาชิกจากการแนะนำของเพื่อน"   type =17 val = 20
     */

//    function addUserScore($table, $content_id, $member_id, $type, $val, &$DBSelect, &$DBEdit) {
    function addUserScore($table, $content_id, $member_id, $type, $val) {
//        $pointArr = array(1=>30,2=>30,3=>30,4=>10,5=>10,6=>10,7=>100,8=>'',9=>5);
        switch ($type) {
            case "1" :
                $val = 30;
                break;
            case "2" :
                $val = 30;
                break;
            case "3" :
                $val = 30;
                break;
            case "4":
                $val = 10;
                break;
            case "5" :
                $val = 10;
                break;
            case "6" :
                $val = 10;
                break;
            case "7" :
                $val = 100;
                break;
            case "8" :
                $val = $val;
                break;
            case "9" :
                $val = 5;
                break;
            case "10" :
                $val = 20;
                break;
            case "11" :
                $val = 20;
                break;
            case "12" :
                $val = 20;
                break;
            case "13" :
                $val = 10;
                break;
            case "14" :
                $val = 10;
                break;
            case "15" :
                $val = 1;
                break;
            case "16" :
                $val = 30;
                break;
            case "17" :
                $val = 20;
                break;
        }

        $sql = "INSERT INTO user_scorekeep  SET scorekeep_content_id = '" . $content_id . "', scorekeep_value = " . $val . " , ";
        $sql .= "scorekeep_table = '" . $table . "', member_id = '" . $member_id . "', scorekeep_type = '" . $type . "' ,";
        $sql .= "user_ip = '" . $this->CI->input->ip_address() . "', scorekeep_datetime = '" . date('Y-m-d H:i:s') . "' ";

        $query = $this->CI->db->query($sql);
        if ($query) {
            $sql = "UPDATE blog SET blog_point = blog_point + " . $val . " WHERE member_id = '" . $member_id . "' ";
            $this->CI->db->query($sql);
        }
    }

}
