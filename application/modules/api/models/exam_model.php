<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Exam_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  function save_answer($question_id) {
    $DBSelect = $this->load->database('select', TRUE);
    foreach ($_POST as $key => $val) {
      if (is_array($val)) {
        foreach ($val as $index => $data) {
          echo 'ข้อที่แก้:' . $index . ' / ' . $data . '<br>';
          $DBSelect->update('ku_examination_answer', array('answer_detail' => $data), array('answer_order' => $index, "question_id" => $question_id));
        }
      } else {
        echo 'KEY:' . $key . ' VALUE:' . $val . '<br>';
        $DBSelect->update('ku_examination_question', array('question_detail' => $val), array("question_id" => $question_id));
      }
    }

    return true;
  }

  function get_question_by_level_subject_category($mul_level_id = 0, $mul_subject_id = 0, $mul_category_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $data = array();
    $sql = "SELECT qu.*, ca.mul_category_name, lv.mul_level_name
      FROM ku_examination_question qu
      INNER JOIN mul_category ca ON qu.mul_category_id = ca.mul_category_id
      INNER JOIN mul_level lv ON qu.mul_level_id = lv.mul_level_id
      WHERE 1=1 ";
    if ($mul_level_id !== 0) {
      $sql .= " AND qu.mul_level_id=?";
      $data[] = $mul_level_id;
    }
    if ($mul_subject_id !== 0) {
      $sql .= " AND qu.mul_subject_id=?";
      $data[] = $mul_subject_id;
    }
    if ($mul_category_id !== 0) {
      $sql .= " AND qu.mul_category_id=?";
      $data[] = $mul_category_id;
    }

    $result = $DBSelect->query($sql, $data);
    if ($result) {
      return $result->result_array();
    }
    return null;
  }

  function get_exam_by_level_subject_category($mul_level_id = 0, $mul_subject_id = 0, $mul_category_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $data = array();
    $sql = "SELECT qu.*, ca.mul_category_name, lv.mul_level_name
      FROM ku_examination_exam qu
      INNER JOIN mul_category ca ON qu.mul_category_id = ca.mul_category_id
      INNER JOIN mul_level lv ON qu.mul_level_id = lv.mul_level_id
      WHERE 1=1 ";
    if ($mul_level_id !== 0) {
      $sql .= " AND qu.mul_level_id=?";
      $data[] = $mul_level_id;
    }
    if ($mul_subject_id !== 0) {
      $sql .= " AND qu.mul_subject_id=?";
      $data[] = $mul_subject_id;
    }
    if ($mul_category_id !== 0) {
      $sql .= " AND qu.mul_category_id=?";
      $data[] = $mul_category_id;
    }
    $result = $DBSelect->query($sql, $data);
    if ($result) {
      return $result->result_array();
    }
    return null;
  }

  function get_exam_list($mul_level_id = 0, $mul_subject_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $key = 'ku_exam_get_list_' . $mul_level_id . "_" . $mul_subject_id;
    if (!$result = $this->tppymemcached->get($key)) {
      $data = array();
      $sql = "SELECT ex.mul_level_id, ex.mul_subject_id, ca.mul_category_name, lv.mul_level_name, SUM(exam_time_sec)  exam_time_sec, COUNT(exam_id) count_cat
      FROM ku_examination_exam ex
      INNER JOIN mul_category ca ON ex.mul_subject_id = ca.mul_category_id
      INNER JOIN mul_level lv ON ex.mul_level_id = lv.mul_level_id
      WHERE ex.status ='P' AND NOW() BETWEEN date_start AND date_end";
      if ($mul_level_id !== 0)
        $sql .= " AND ex.mul_level_id=$mul_level_id ";

      if ($mul_subject_id !== 0)
        $sql .= " AND ex.mul_subject_id=$mul_subject_id";
      $sql.=" GROUP BY ex.mul_level_id, ex.mul_subject_id ORDER BY ex.mul_level_id,
      CASE ca.mul_category_id
      WHEN 3000 THEN 1
      WHEN 2000 THEN 2
      WHEN 1000 THEN 3
      WHEN 6000 THEN 4
      WHEN 8000 THEN 5
      WHEN 4000 THEN 6
      WHEN 5000 THEN 7
      WHEN 7000 THEN 8 
      ELSE 0 END";

      $query = $DBSelect->query($sql, $data);
      $result = $query->result_array();
      $this->tppymemcached->set($key, $result);
    }
    return $result;
  }

  function get_detail_exam_list($mul_level_id = 0, $mul_subject_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $key = 'ku_exam_get_list_' . $mul_level_id . "_" . $mul_subject_id;
    $this->tppymemcached->delete($key);
    if (!$result = $this->tppymemcached->get($key)) {
      $data = array();
      $sql = "SELECT ex.mul_level_id, ex.mul_category_id, ca.mul_category_name, lv.mul_level_name, SUM(exam_time_sec)  exam_time_sec, COUNT(exam_id) count_cat
      FROM ku_examination_exam ex
      INNER JOIN mul_category ca ON ex.mul_category_id = ca.mul_category_id
      INNER JOIN mul_level lv ON ex.mul_level_id = lv.mul_level_id
      WHERE ex.status ='P' AND NOW() BETWEEN date_start AND date_end";

      if ($mul_level_id !== 0)
        $sql .= " AND ex.mul_level_id=$mul_level_id ";

      if ($mul_subject_id !== 0)
        $sql .= " AND ex.mul_subject_id=$mul_subject_id";

      $sql.= " GROUP BY ex.mul_level_id, ex.mul_category_id ORDER BY CHAR_LENGTH(ca.mul_category_name), ex.mul_level_id";

      $query = $DBSelect->query($sql, $data);
      $result = $query->result_array();
      $this->tppymemcached->set($key, $result);
    }
    return $result;
  }

  function get_exam_member_score($member_id = 0, $mul_level_id = 0, $mul_subject_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $data = array();
    $sql = "SELECT COUNT(DISTINCT(sc.exam_id)) count_data, COUNT(sc.log_id) count_result_data, sc.mul_level_id, sc.mul_subject_id FROM
    ku_examination_score sc 
    WHERE 1 ";
    if ($member_id !== 0)
      $sql .= " AND sc.create_by='$member_id'";
    if ($mul_level_id !== 0)
      $sql .= " AND sc.mul_level_id=$mul_level_id ";
    if ($mul_subject_id !== 0)
      $sql .= " AND sc.mul_subject_id=$mul_subject_id";

    $sql .= " GROUP BY sc.mul_level_id, sc.mul_subject_id ";

    //echo $sql; 
    $result = $DBSelect->query($sql, $data, TRUE);
    return $result->result_array();
  }

  function get_detail_exam_member_score($member_id = 0, $mul_level_id = 0, $mul_subject_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $data = array();
    $sql = "SELECT COUNT(DISTINCT(log_id)) count_data, sc.mul_level_id, sc.mul_category_id FROM
    ku_examination_score sc 
    WHERE 1";

    if ($member_id !== 0)
      $sql .= " AND sc.create_by='$member_id'";
    if ($mul_level_id !== 0)
      $sql .= " AND sc.mul_level_id=$mul_level_id ";
    if ($mul_subject_id !== 0)
      $sql .= " AND sc.mul_subject_id=$mul_subject_id";

    $sql .= " GROUP BY sc.mul_level_id, sc.mul_category_id ";

    //echo $sql; die;
    $result = $DBSelect->query($sql, $data, TRUE);
    return $result->result_array();
  }

  function get_category_list($mul_level_id = 0, $mul_subject_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $data = array();
    $sql = "SELECT COUNT(question_id) cnt, qu.mul_category_id, qu.exam_id, ca.mul_category_name, qu.mul_subject_id, qu.mul_level_id
    FROM ku_examination_question qu
    INNER JOIN mul_category ca ON qu.mul_category_id = ca.mul_category_id
    WHERE qu.mul_subject_id=$mul_subject_id
    AND qu.mul_level_id=$mul_level_id
    GROUP BY qu.exam_id";

    $result = $DBSelect->query($sql, $data);
    return $result->result_array();
  }

  function get_category_name($mul_category_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $data = array();
    $sql = "SELECT * FROM mul_category WHERE mul_category_id=$mul_category_id";

    $result = $DBSelect->query($sql, $data);
    return $result->result_array();
  }

  function get_category_list_score($mul_level_id = 0, $mul_subject_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $data = array();
    $key = 'ku_exam_get_category_list_' . $mul_level_id . "_" . $mul_subject_id;
    if (!$result = $this->tppymemcached->get($key)) {
      $data = array($mul_subject_id, $mul_level_id);
      $sql = "SELECT 
      COUNT(DISTINCT(qu.question_id)) cnt
      , IFNULL(MAX( `result_score` ),0) max_score, IFNULL(AVG( `result_score` ),0) avg_score
      ,  qu.mul_category_id
      , qu.exam_id, ca.mul_category_name
      , qu.mul_subject_id, qu.mul_level_id

      FROM ku_examination_question qu 
      LEFT JOIN ku_examination_score sc ON qu.exam_id = sc.exam_id
      INNER JOIN mul_category ca ON qu.mul_category_id = ca.mul_category_id
      WHERE qu.mul_subject_id=?
      AND qu.mul_level_id=?
      GROUP BY qu.exam_id";
      //echo $sql;
      $query = $DBSelect->query($sql, $data);
      $result = $query->result_array();
      $this->tppymemcached->set($key, $result, 80000);
    }
    return $result;
  }

  function get_member_score($mul_level_id = 0, $mul_subject_id = 0, $member_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $data = array();
    $sql = "SELECT sc.exam_id, sc.result_score mem_result_score, MAX(sc.result_score) mem_max_score, COUNT(distinct(sc.exam_id)) mem_count_result, COUNT(distinct(sc.log_id)) mem_count_result_data
    FROM ku_examination_score sc 
    WHERE sc.mul_subject_id=$mul_subject_id
    AND sc.mul_level_id=$mul_level_id
    AND sc.create_by='$member_id' 
    GROUP BY sc.exam_id";

    $result = $DBSelect->query($sql, $data);
    return $result->result_array();
  }

  function get_question($exam_id = 0, $question_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $sql = "SELECT * FROM ku_examination_question qu
    INNER JOIN ku_examination_answer an ON qu.question_id=an.question_id
    WHERE qu.question_id = $question_id AND exam_id=$exam_id";

    $result = $DBSelect->query($sql);
    return $result->result_array();
  }

  function get_question_list($exam_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $sql = "SELECT question_id, question_order FROM ku_examination_question qu
      WHERE qu.exam_id = $exam_id ORDER BY question_order ASC";

    $result = $DBSelect->query($sql);
    return $result->result_array();
  }

  function set_answer_score($data) {
    $DBEdit = $this->load->database('edit', TRUE);
    $insert_id =  $DBEdit->insert('ku_examination_score', $data);
    return $DBEdit->insert_id();
  }

  function get_count_by_examid_memberid($exam_id = 0, $member_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $sql = "SELECT COUNT(*) cnt FROM ku_examination_score WHERE exam_id=? AND create_by=?";
    $data = array($exam_id, $member_id);

    $result = $DBSelect->query($sql, $data);
    if ($result)
      return $result->row()->cnt;
    return null;
  }

  function get_count_exam($mul_subject_id = 0, $mul_level_id = 0, $member_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $sql = "SELECT count(distinct(es.exam_id)) exam_count, (SELECT COUNT(ex.exam_id) FROM ku_examination_exam ex WHERE 1";

    if ($mul_level_id !== 0)
      $sql.= " AND ex.mul_level_id = $mul_level_id";
    if ($mul_subject_id !== 0)
      $sql.= " AND ex.mul_subject_id =$mul_subject_id";

    $sql .=") exam_count_total FROM ku_examination_score es WHERE 1";

    if ($mul_level_id !== 0)
      $sql.= " AND es.mul_level_id = $mul_level_id";
    if ($mul_subject_id !== 0)
      $sql.= " AND es.mul_subject_id =$mul_subject_id";
    if ($member_id !== 0)
      $sql.= " AND es.create_by = '$member_id'";

    $query = $DBSelect->query($sql, TRUE);
    if ($query)
      return $query->result_array();
    return null;
  }

  function get_question_by_examid($exam_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $sql = "SELECT answer_order FROM ku_examination_question qu INNER JOIN ku_examination_answer an ON qu.question_id=an.question_id WHERE exam_id=? AND an.answer_score > 0 ORDER BY qu.question_order ASC";
    $data = array($exam_id);

    $result = $DBSelect->query($sql, $data);
    if ($result)
      return $result->result_array();
    return null;
  }

  function get_exam_by_examid($exam_id = 0) {
    $DBSelect = $this->load->database('select', TRUE);
    $sql = "SELECT ex.*, ex.exam_question_count question_count
    FROM ku_examination_exam ex WHERE ex.exam_id=? AND ex.status ='P' AND NOW() BETWEEN date_start AND date_end  ";
    $data = array($exam_id);

    $result = $DBSelect->query($sql, $data);
    if ($result)
      return $result->result_array();
    return null;
  }

  function get_knowledge_level_subject_category($mul_level_id = 0, $mul_subject_id = 0, $mul_category_id = 0, $show = 6, $limit = 30) {
    $DBSelect = $this->load->database('select', TRUE);
    $key = 'ku_exam_get_knowledge_' . $mul_level_id . "_" . $mul_subject_id . "_" . $mul_category_id . "_" . $limit;
    if (!$result_array = $this->tppymemcached->get($key)) {
      $data = array();
      $sql = " SELECT 
      MCON.year_path,MCON.*, PSN.psn_display_name, MS.mul_source_id, MS.mul_title, MS.mul_destination, MS.mul_type_id, 
      MS.mul_thumbnail_file, MS.mul_filename, MS.mul_source_update_datetime, 
      (SELECT COALESCE(MV.mul_view_value, 0) FROM mul_sum_view MV WHERE  MV.mul_content_id = MCON.mul_content_id AND MV.mul_view_table=7 LIMIT 1) views_count
      FROM mul_source MS 
      LEFT OUTER JOIN mul_content MCON ON MS.mul_content_id = MCON.mul_content_id 
      LEFT OUTER JOIN mul_content_knowledge_context MCKC ON MCON.mul_content_id = MCKC.mul_content_id 
      LEFT OUTER JOIN personnel PSN ON MCON.member_id = PSN.member_id 
      WHERE 1=1 AND MCON.mul_content_status = '1' AND MS.mul_type_id='v' ";

      if ($mul_level_id !== 0) {
        $sql .= " AND MCON.mul_level_id=$mul_level_id";
      }
      $sql .= " AND MCON.mul_category_id IN (";
      $sql .= "$mul_category_id, ";
      $sql .= "$mul_subject_id, ";
      $sql .= "0)";
      $sql .= " ORDER BY RAND() LIMIT $limit";

      //echo $sql; die;
      $result = $DBSelect->query($sql, $data);
      $result_array = $result->result_array();

      foreach ($result_array as $k => $v) {
        $image_url = explode(".", $v['mul_filename']);
        $link_url = "http://www.trueplookpanya.com/new/cms_detail/knowledge/" . $v['mul_content_id'] . "-" . $v['mul_source_id'];
        if (count($image_url) > 0) {
          $image_file = !empty($v['mul_thumbnail_file']) ? $v['mul_thumbnail_file'] : $image_url[0];
          switch ($v['mul_type_id']) {
            case 'v' :
              $image_url = "http://static.trueplookpanya.com/trueplookpanya/media/" . $v['mul_destination'] . '/' . $image_file . '_128x96.png';
              if (!$this->remoteFileExists($image_url)) {
                $image_url = "http://www.trueplookpanya.com/new/cutresize/re/110/74/images/trueplook.png/TV/";
              }
              break;
            default : $image_url = "http://www.trueplookpanya.com/new/assets/images/icon/doc126x95px.jpg";
              break;
          }
        } else {
          $image_url = 'default';
        }
        $result_array[$k]['link_url'] = $link_url;
        $result_array[$k]['image_url'] = $image_url;
      }
      $this->tppymemcached->set($key, $result_array, 86400);
    }

    //var_dump($result_array);

    if ($result_array) {
      shuffle($result_array);
      return array_slice($result_array, -$show);
    }
  }

  function remoteFileExists($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    $result = curl_exec($curl);
    $ret = false;
    if ($result !== false) {
      $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      if ($statusCode == 200) {
        $ret = true;
      }
    }
    curl_close($curl);
    return $ret;
  }

  function get_ranking_result_by_level_subject($mul_level_id = 0, $mul_subject_id = 0, $member_id = NULL, $result_count = NULL) {
    $DBSelect = $this->load->database('select', TRUE);
    $key = 'ku_exam_get_result_rank_' . $mul_level_id . "_" . $mul_subject_id;
    //$this->tppymemcached->delete($key);

    if (!empty($member_id) || !$result = $this->tppymemcached->get($key)) {
      if (empty($member_id))
        $suf = '';
      else
        $suf = '_mem';

      $suf = empty($member_id) ? '' : '_mem';

      $sql = "SELECT es.exam_id
      , es.mul_level_id
      , es.mul_subject_id
      , es.mul_category_id
      , (SELECT mul_category_name FROM mul_category ca WHERE es.mul_category_id = ca.mul_category_id) mul_category_name
      , MAX(es.result_score) exam_result_max$suf
      , AVG(es.result_score) exam_result_avg$suf
      , SUM(es.result_score) exam_result_sum$suf
      , COUNT(es.result_score)  exam_result_count$suf
      , es.exam_question_score exam_question_score$suf
      , es.exam_question_count exam_question_count$suf
      , es.result_ispass pass$suf
      , es.result_time_sec result_time_sec$suf
      FROM ku_examination_score es
      WHERE 1";
      if ($mul_level_id !== 0)
        $sql.= " AND es.mul_level_id = $mul_level_id";
      if ($mul_subject_id !== 0)
        $sql.= " AND es.mul_subject_id =$mul_subject_id";
      if ($member_id !== NULL)
        $sql.= " AND es.create_by = '$member_id'";
      if ($result_count !== NULL)
        $sql .=" AND es.result_count = $result_count";
      $sql .=" GROUP BY es.exam_id";

      $query = $DBSelect->query($sql);
      $result = $query->result_array();
      if (empty($member_id)) {
        $this->tppymemcached->set($key, $result, 6000);
      }
    }
    return $result;
  }

  function set_complete_exam_by_level_subject($mul_level_id, $mul_subject_id, $member_id) {
    $db = $this->load->database('edit', TRUE);

    $sql = "INSERT INTO ku_examination_complete  (`member_id`, `mul_level_id`, `mul_subject_id`, `result_score`, `max_score`, `result_time_sec`, `create_date`)
    SELECT * FROM
    (
     SELECT AA.create_by `member_id`,  AA.mul_level_id,  AA.mul_subject_id, SUM(AA.result_score) `result_score`, SUM(AA.max_score) `max_score`, SUM(AA.result_time_sec) `result_time_sec`,  NOW() `create_date`
		FROM
        (
		SELECT es.create_by , 
		es.mul_level_id , 
		es.mul_subject_id, 
		es.result_score, 
		MAX(es.result_score) max_score,
        result_time_sec,
        MIN(result_time_sec) min_time
		FROM ku_examination_score es
		WHERE es.create_by='$member_id' AND es.mul_subject_id=$mul_subject_id AND es.mul_level_id=$mul_level_id
		GROUP BY es.exam_id
		) AA
		GROUP BY AA.mul_subject_id,  AA.mul_level_id
    ) AS t
    ON duplicate key update result_score=t.result_score,max_score=t.max_score,result_time_sec=t.result_time_sec"; //", logs_id=LAST_INSERT_ID(logs_id)";

    $query = $db->query($sql);
    return $query;
  }

  function get_sumary_level($mul_level_id = 0, $mul_subject_id = 0, $member_id = '', $result_count = 'NULL') {
    $DBSelect = $this->load->database('select', TRUE);
    $key = 'ku_exam_get_result_rank_' . $mul_level_id . "_" . $mul_subject_id;
    $this->tppymemcached->delete($key);

    if (!empty($member_id) || !$result = $this->tppymemcached->get($key)) {
      if (empty($member_id))
        $suf = '';
      else
        $suf = '_mem';

      $suf = empty($member_id) ? '' : '_mem' . ($result_count === 'NULL' ? '' : "_$result_count");


      $sql = "SELECT mul_level_id
      , mul_subject_id
      , (SELECT mul_category_name FROM mul_category mc WHERE mc.mul_category_id=AA.mul_subject_id) mul_category_name
      , SUM(exam_question_count) `exam_question_count$suf`, SUM(exam_result_max) `exam_result_max$suf`, SUM(exam_result_avg) `exam_result_avg$suf`,COUNT(*) `exam_count_cat$suf`
      FROM (
      SELECT es.exam_id
      , es.mul_level_id
      , es.mul_subject_id
      , es.mul_category_id
      , (SELECT mul_category_name FROM mul_category ca WHERE es.mul_category_id = ca.mul_category_id) mul_category_name
      , MAX(es.result_score) exam_result_max
      , AVG(es.result_score) exam_result_avg
      , SUM(es.result_score) exam_result_sum
      , COUNT(es.result_score)  exam_result_count
      , es.exam_question_score exam_question_score
      , es.exam_question_count exam_question_count
      , es.result_ispass pass
      , es.result_time_sec result_time_sec
      FROM ku_examination_score es
      WHERE 1 ";
      if ($mul_level_id !== 0)
        $sql.= " AND es.mul_level_id = $mul_level_id";
      if ($mul_subject_id !== 0)
        $sql.= " AND es.mul_subject_id =$mul_subject_id";
      if ($member_id !== '')
        $sql.= " AND es.create_by = '$member_id'";
      if ($result_count !== 'NULL')
        $sql .=" AND es.result_count = $result_count";


      $sql .= " GROUP BY es.exam_id
      ) AA
      GROUP BY mul_level_id, mul_subject_id";


      $query = $DBSelect->query($sql);
      $result = $query->result_array();
      if (empty($member_id)) {
        $this->tppymemcached->set($key, $result, 6000);
      }
    }
    return $result;
  }

  function get_rank_by_level($mul_level_id, $member_id) {
    $DBSelect = $this->load->database('select', TRUE);
    // ครั่งแรก
    $sql = "SELECT create_by, SUM(ex.result_score) result_score, FIND_IN_SET(SUM(ex.result_score), (
        SELECT GROUP_CONCAT(DISTINCT(ex1.result_score) ORDER BY ex1.result_score DESC)
        FROM (	
            SELECT SUM(ex2.result_score) result_score
            FROM ku_examination_score ex2
            WHERE ex2.mul_level_id=$mul_level_id 
            AND ex2.result_count=0
            GROUP BY ex2.create_by
                  ORDER BY SUM(ex2.result_score) DESC
          ) ex1
      ) ) mem_rank 
      , (SELECT COUNT(DISTINCT(ex3.create_by)) FROM ku_examination_score ex3 WHERE ex3.mul_level_id=$mul_level_id AND ex3.result_count=0) total_member
      FROM ku_examination_score ex
      WHERE 1
      AND create_by='$member_id'  
      AND ex.mul_level_id = $mul_level_id 
      AND ex.result_count=0;";
    // echo $sql;
    $query = $DBSelect->query($sql);
    $result = $query->result_array();
    return $result;
  }
  function get_profile_by_memberid($member_id=0){
    $DBSelect = $this->load->database('select', TRUE);
    $sql ="
    SELECT p.psn_name
    ,p.psn_lastname
    ,m.member_usrname member_username
    ,p.school_name
    , CONCAT(p.psn_name,' ',p.psn_lastname) full_name
    , l.province_name province
    FROM personnel p 
    LEFT JOIN member m ON m.member_id=p.member_id 
    LEFT JOIN province l ON l.province_id=p.province_id
    WHERE p.member_id=?";
    $query = $DBSelect->query($sql, array($member_id));
    $result = $query->row_array();
    //var_dump($result); die;
    return $result;
  }  
}
