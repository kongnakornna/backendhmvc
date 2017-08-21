<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class KnowledgebaseV2 extends Public_Controller {

    public function __construct() {
        parent::__construct();
        ob_clean();
        $this->db = $this->load->database('select', TRUE);
        $this->db_edit = $this->load->database('edit', TRUE);
        header('Content-Type: application/json; charset=utf-8');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index($limit = 1, $mul_content_id = 0) {
        if ($this->getMethod() != "GET") {
            exit();
        }
        $this->db->select('*')->from('mul_content');
        if ($mul_content_id != 0) {
            $this->db->where('mul_content_id', $mul_content_id);
        }
        $this->db->order_by('rand()');
        $this->db->limit($limit);
        $resp = $this->db->get();
        $data = $resp->result_array();
        echo json_encode($data);
    }

    public function geteduimg($limit = 8, $edu_photo_id = NULL) {
        if ($this->getMethod() != "GET") {
            exit();
        }
        $this->db->select('*')->from('edu_photo')->where("appr_status", 1);
        if ($edu_photo_id != NULL) {
            $this->db->where('edu_photo_id', $edu_photo_id);
        }
        $this->db->limit($limit)->order_by('rand()');
        $resp = $this->db->get();
        $data = $resp->result_array();
        foreach ($data as $key => $value) {
            $resp2 = $this->db->select('*')->from('edu_photo_files')->where('edu_photo_id', $value['edu_photo_id'])->get();
            $data[$key]['edu_photo_file'] = $resp2->result_array();
        }
        echo json_encode($data);
    }

    public function getedusong($limit = 8, $cms_id = NULL) {
        if ($this->getMethod() != "GET") {
            exit();
        }
        $this->db->select('*')->from('cms')->where('cms_category_id', 67)->where('record_status', 1);
        if ($cms_id != NULL) {
            $this->db->where('cms_id', $cms_id);
        }
        $this->db->limit($limit)->order_by('rand()');
        $resp = $this->db->get();
        $data = $resp->result_array();
        foreach ($data as $key => $value) {
            $resp2 = $this->db->select('*')->from('cms_file')->where('cms_id', $value['cms_id'])->get();
            $data[$key]['cms_file_source'] = $resp2->result_array();
        }
        echo json_encode($data);
    }

    public function getcategory() {
        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }
        $this->db->select('*')->from('mul_category_2014');
        $this->db->where('MOD(mul_category_id,1000)=0');
//        $this->db->or_where('mul_category_id <',1000);
        $resp = $this->db->get();
        $data = $resp->result_array();
        echo json_encode($data);
    }

    public function getlevel() {
        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }
        $this->db->select('*')->from('mul_level')->where_in('mul_level_id', array(11, 12, 13, 21, 22, 23, 31, 32, 33, 41, 42, 43));
        $resp = $this->db->get();
        $data = $resp->result_array();
        echo json_encode($data);
    }

    public function getyoutube($limit = 8, $youtube_id = NULL) {
        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }
        $this->db->select('youtube_id,watch_code,embed_code,youtube_subject,youtube_detail_short_th')->from('youtube')->where('youtube_status', 1);
        if ($youtube_id != NULL) {
            $this->db->where('youtube_id', $youtube_id);
        }
        $this->db->order_by('rand()');
        $this->db->limit($limit);
        $resp = $this->db->get();
        $data = $resp->result_array();
        echo json_encode($data);
    }

    public function getyoutubefull($limit = 8, $youtube_id = NULL) {
        if ($this->getMethod() != "GET") {
            exit();
        }
        $this->db->select('youtube_id,watch_code,embed_code,youtube_subject,youtube_detail_short_th,youtube_detail_long_th')->from('youtube')->where('youtube_status', 1);
        if ($youtube_id != NULL) {
            $this->db->where('youtube_id', $youtube_id);
        }
        $this->db->order_by('rand()');
        $this->db->limit($limit);
        $resp = $this->db->get();
        $data = $resp->result_array();
        echo json_encode($data);
    }

    public function loadmore() {
        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }

        $req = [];

//        $req['subject_id'] = !empty($_GET['sid']) ? $_GET['sid'] : '0';
//        $req['level_id'] = !empty($_GET['lid']) ? $_GET['lid'] : '0';
//        $req['knowledge_id'] = !empty($_GET['cid']) ? $_GET['cid'] : '0';

        $req['subject_id'] = $_GET['sid'];

        $req['level_id'] = $_GET['lid'];
        $req['knowledge_id'] = $_GET['ssid'];
        //$req['knowledge_id'] = $_GET['cid'];
        $req['q'] = $_GET['q'];

        $req['start'] = $_GET['start'];
        $req['end'] = $_GET['end'];
        $req['type'] = $_GET['type'];

        $req['context_id'] = $_GET['cid'];

//        if(!empty($_GET['ssid'])){
//            $req['context_id'] = $_GET['ssid'];
//        }

        $remove = array(
            ['level_id' => 10, 'level_name' => "ป.ต้น"],
            ['level_id' => 20, 'level_name' => "ป.ปลาย"],
            ['level_id' => 30, 'level_name' => "ม.ต้น"],
            ['level_id' => 40, 'level_name' => "ม.ปลาย"]
        );

        $data = [];
        switch ($req['type']) {
            case 'all':
                //all
                $all_query = $this->_getContent_query($req['type'], $req, $select);
                $respvideo = $this->db->query($all_query);
                if ($respvideo->num_rows() > 0) {
                    $data['all'] = $respvideo->result_array();
                    $this->load->library('trueplook');
                    foreach ($data['all'] as $key => $value) {
                        $data['all'][$key]['viewcount'] = strval($this->trueplook->getViewNumber($value['content_id_child'], 7));
                        $data['all'][$key]['view_show'] = convertToK($data['all'][$key]['viewcount']);
                        $data['all'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');
                        $data['all'][$key]['date_short'] = $this->trueplook->data_format('small', 'th', $value['date_post']);
                    }
                } else {
                    $data['all'] = NULL;
                }
                break;
            case 'v':
                //video
                $video_query = $this->_getContent_query($req['type'], $req, $select);


                $respvideo = $this->db->query($video_query);
                if ($respvideo->num_rows() > 0) {
                    $data['clip'] = $respvideo->result_array();

                    $this->load->library('trueplook');
                    foreach ($data['clip'] as $key => $value) {


                        $data['clip'][$key]['viewcount'] = strval($this->trueplook->getViewNumber($value['content_id_child'], 7));
                        $data['clip'][$key]['view_show'] = convertToK($data['clip'][$key]['viewcount']);
                        $data['clip'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');
                        $data['clip'][$key]['date_short'] = $this->trueplook->data_format('small', 'th', $value['date_post']);

//                        foreach ($remove as $key2 => $value) {
//                            if ($data['clip'][$key]['cat_level_id'] == $value['level_id']) {
//                                $data['clip'][$key]['cat_level_name'] = $value['level_name'];
//                            }
//                        }
                    }
                } else {
                    $data['clip'] = NULL;
                }
                break;
            case 't':
                //content
                $text_query = $this->_getContent_query($req['type'], $req, $select);
                $resptext = $this->db->query($text_query);
                if ($resptext->num_rows() > 0) {
                    $data['other'] = $resptext->result_array();

                    $this->load->library('trueplook');
                    foreach ($data['other'] as $key => $value) {
                        //print_r($value['content_id_child']);
                        $data['other'][$key]['viewcount'] = $this->trueplook->getViewNumber(((int) $value['content_id']), 0);
                        $data['other'][$key]['view_show'] = convertToK($data['other'][$key]['viewcount']);
                        $data['other'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');
                        $data['other'][$key]['date_short'] = $this->trueplook->data_format('small', 'th', $value['date_post']);

//                        foreach ($remove as $key2 => $value) {
//                            if ($data['clip'][$key]['cat_level_id'] == $value['level_id']) {
//                                $data['clip'][$key]['cat_level_name'] = $value['level_name'];
//                            }
//                        }
                    }
                } else {
                    $data['other'] = NULL;
                }
                break;
            default:
                //ALL
                $video_query = $this->_getContent_query('all', $req, $select);
                //print_r($video_query);exit();
                $respvideo = $this->db->query($video_query);
                if ($respvideo->num_rows() > 0) {
                    $data['clip'] = $respvideo->result_array();

                    $this->load->library('trueplook');
                    foreach ($data['clip'] as $key => $value) {
                        $data['clip'][$key]['viewcount'] = strval($this->trueplook->getViewNumber($value['content_id_child'], 7));
                        $data['clip'][$key]['view_show'] = convertToK($data['clip'][$key]['viewcount']);
                        $data['clip'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');
                        $data['clip'][$key]['date_short'] = $this->trueplook->data_format('small', 'th', $value['date_post']);
                    }
                } else {
                    $data['clip'] = NULL;
                }
                //content
                $text_query = $this->_getContent_query('t', $req, $select);
                $resptext = $this->db->query($text_query);
                if ($resptext->num_rows() > 0) {
                    $data['other'] = $resptext->result_array();

                    $this->load->library('trueplook');
                    foreach ($data['other'] as $key => $value) {
                        //print_r($value['content_id_child']);
                        $data['other'][$key]['viewcount'] = $this->trueplook->getViewNumber(((int) $value['content_id']), 0);
                        $data['other'][$key]['view_show'] = convertToK($data['other'][$key]['viewcount']);
                        $data['other'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');
                        $data['other'][$key]['date_short'] = $this->trueplook->data_format('small', 'th', $value['date_post']);
                    }
                } else {
                    $data['other'] = NULL;
                }
                break;
        }
//		echo $data;
        echo json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    }

    //- P : start
    private function _getContent_query($type = 'v', $req = [], $select = null) {

        if ($req['level_id']) {
            if ($req['level_id'] >= 41 && $req['level_id'] <= 43) {
                $lid = 40;
            } else {
                $lid = $req['level_id'];
            }
        }

        $criteria_inside = "";
        if ($type != "") {
            if ($type == "v") {
                $criteria_inside .=" and mul_type_id='v'";
            } elseif ($type == "t") {
                $criteria_inside .=" and (mul_type_id is null or mul_type_id != 'v')";
            }
        }

        if ($req['q'] != null && $req['q'] != '') {
            $criteria .=" and (`content_subject` like ''%" . $req['q'] . "%' or `content_subject`= '" . $req['q'] . "')";
        }
        if ($req['subject_id'] != null && $req['subject_id'] != '') {
            $criteria .=" and (`cat_super_id` like ''%" . $req['q'] . "%' or `cat_super_id`= '" . $req['q'] . "')";
        }

        $criteria_in = "";
        if ($req['knowledge_id'] != null) {
            $criteria_in .= " and context_id in (select knowledge_context_id from knowledge_context_2014 where mul_category_id=" . $req['knowledge_id'] . ")";
        }
        if ($lid != null) {
            $criteria_in .= " and context_id in (select knowledge_context_id from knowledge_context_2014 where mul_level_id=" . $lid . ")";
        }
        if ($req['context_id'] != null) {
            $criteria_in .= " and context_id=" . $req['context_id'];
        }

        $sql = "select AA.*
,(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id)  " . $criteria_in . ") context_id

,(select mul_level_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " )) cat_level_id
,(select mul_category_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " )) cat_id

,(select mul_level_name from mul_level where mul_level_id=(select mul_level_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " ))) cat_level_name,
(select TRUNCATE((select mul_category_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " )),-3)) cat_super_id,
(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE((select mul_category_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " )),-3))) cat_super_name,
(select mul_category_name from mul_category_2014 where mul_category_id=(select mul_category_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " )) limit 1) cat_name

from (
select
 'mul_content' tb_name
 ,mc.mul_content_id as content_id
 ,ms.mul_source_id as content_id_child
 
 " . ($select == 'full' ? ',mc.mul_content_text as content_text' : '') . "
 , concat(mc.mul_content_subject,if(ifnull(ms.mul_title,'')!='',concat(' : ',ms.mul_title),'')) as content_subject
 , if(ifnull(mul_type_id,'')='','text',if(mul_type_id='v','vdo',if(mul_type_id='a','audio','doc'))) as content_type
 ,concat('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id,if(ms.mul_source_id is not null,concat( '-',ms.mul_source_id),'')) AS content_url
 ,(case ms.mul_type_id
								when 'v' then 
									if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/data/product/media/',ms.mul_destination,'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
								else 'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default-img.jpg'
							 end
							) thumbnail
 ,mc.mul_tag AS keyword
 ,mc.mul_update_date AS date_post
 ,mc.member_id AS display_name
 ,if(ifnull(ms.mul_source_id,'')='',mc.content_stage,ms.content_stage) as content_stage
 
from mul_content mc
 left join `mul_source` ms on mc.mul_content_id=ms.mul_content_id
 left join `users_account` acc on mc.member_id = acc.member_id
where 1=1
and (if(ms.mul_source_id is not null,ms.mul_source_id in(select content_id from knowledge_context_2014_map where table_id=2),mc.mul_content_id in (select content_id from knowledge_context_2014_map where table_id=1)))
" . $criteria_inside . "
) AA 
		WHERE 1=1
	 " . $criteria . "
	ORDER BY date_post DESC
	LIMIT " . ((isset($req['start']) && isset($req['end'])) ? $req['start'] . ',' . $req['end'] : '50');

        //echo $sql;
        return $sql;
    }

    private function _getContent_query_bak2($type = 'v', $req = [], $select = null) {

        if ($req['level_id']) {
            if ($req['level_id'] >= 41 && $req['level_id'] <= 43) {
                $lid = 40;
            } else {
                $lid = $req['level_id'];
            }
        }

        $criteria_in = "";
        if ($req['knowledge_id'] != null) {
            $criteria_in .= " and context_id in (select knowledge_context_id from knowledge_context_2014 where mul_category_id=" . $req['knowledge_id'] . ")";
        }
        if ($lid != null) {
            $criteria_in .= " and context_id in (select knowledge_context_id from knowledge_context_2014 where mul_level_id=" . $lid . ")";
        }
        if ($req['context_id'] != null) {
            $criteria_in .= " and context_id=" . $req['context_id'];
        }

        $union = "";
        if ($type == 't') {
            $union = "
SELECT AAAA.*
from (select 
	AAA.*,
	(select TRUNCATE(AAA.cat_id,-3)) cat_super_id,
	(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE(AAA.cat_id,-3))) cat_super_name,
	(select mul_category_name from mul_category_2014 where mul_category_id=AAA.cat_id limit 1) cat_name,
	(select mul_level_name from mul_level where mul_level_id=AAA.cat_level_id) cat_level_name
from (
		select 
       AA.*,
		
        (select mul_level_id from knowledge_context_2014 where knowledge_context_id=AA.context_id) cat_level_id,
        (select mul_category_id from knowledge_context_2014 where knowledge_context_id=AA.context_id) cat_id,
        '0' map_idx
		
from (
		select
        'mul_content' tb_name,
            mc.mul_content_id AS content_id,            
            '' AS content_id_child,
           " . ($select == 'full' ? 'mc.mul_content_text as content_text,' : '') . "
            mc.mul_content_subject AS content_subject,
            'text' content_type,
			CONCAT('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id) AS content_url,
			CONCAT('http://www.trueplookpanya.com/new/assets/images/icon/doc126x95px.jpg') AS thumbnail,
            mc.mul_tag AS keyword,
            mc.mul_update_date AS date_post,
            mc.member_id AS display_name,
			mc.content_stage AS content_stage,

			(select min(context_id) from knowledge_context_2014_map where table_id=1 and content_id=mc.mul_content_id  " . $criteria_in . ") context_id
FROM
        mul_content mc
where mc.mul_content_status=1 
 and mc.mul_content_id not in (select mul_content_id from  mul_source where mul_source_status!=5)
 and mc.mul_content_id in 
 (
	select content_id from knowledge_context_2014_map cm where table_id=1 and content_id=mc.mul_content_id " . $criteria_in . "
 ) 
) AA ) AAA	) AAAA UNION ALL ";
        }


        $sql = "SELECT * FROM (" . $union . "  
SELECT AAAA.*
from (select 
	AAA.*,
	(select TRUNCATE(AAA.cat_id,-3)) cat_super_id,
	(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE(AAA.cat_id,-3))) cat_super_name,
	(select mul_category_name from mul_category_2014 where mul_category_id=AAA.cat_id limit 1) cat_name,
	(select mul_level_name from mul_level where mul_level_id=AAA.cat_level_id) cat_level_name
from (
		select 
       AA.*,
		
        (select mul_level_id from knowledge_context_2014 where knowledge_context_id=AA.context_id) cat_level_id,
        (select mul_category_id from knowledge_context_2014 where knowledge_context_id=AA.context_id) cat_id,
        '0' map_idx
		
from (
		select
        'mul_source' tb_name,
            mc.mul_content_id AS content_id,            
            ms.mul_source_id AS content_id_child,
           " . ($select == 'full' ? 'mc.mul_content_text as content_text,' : '') . "
            CONCAT(mc.mul_content_subject, ' : ', COALESCE(ms.mul_title, '')) AS content_subject,
            IF(IFNULL(mul_type_id, '') = '', 'text', IF(mul_type_id = 'v', 'vdo', IF(mul_type_id = 'a', 'audio', 'doc'))) AS content_type,
			CONCAT('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id, '/', ms.mul_source_id) AS content_url,
			(case ms.mul_type_id
				when 'v' then 
					if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/data/product/media/',ms.mul_destination,'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
				else 'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default-img.jpg'
			 end
			) thumbnail,
            mc.mul_tag AS keyword,
            mc.mul_update_date AS date_post,
            mc.member_id AS display_name,
			ms.content_stage AS content_stage,

			(select min(context_id) from knowledge_context_2014_map where table_id=2 and content_id=ms.mul_source_id  " . $criteria_in . ") context_id
FROM
        mul_content mc
    inner join mul_source ms ON mc.mul_content_id = ms.mul_content_id
	   " . ($type != 'v' ? "AND ms.mul_type_id != 'v'" : "AND ms.mul_type_id = '$type'") . "
where mc.mul_content_status=1  and ms.mul_source_status!=5
 and ms.mul_source_id in 
 (
	select content_id from knowledge_context_2014_map cm where table_id=2 and content_id=ms.mul_source_id " . $criteria_in . "
 ) 
) AA ) AAA	) AAAA 
) AUN
WHERE 1=1
 AND `cat_super_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['subject_id'] ? $req['subject_id'] : '`cat_super_id`') . ")
 AND (`content_subject` like '%" . ($req['q'] != null ? $req['q'] : '') . "%' OR `content_subject` = '" . ($req['q'] != null ? $req['q'] : '') . "')                        
ORDER BY date_post DESC
LIMIT " . ((isset($req['start']) && isset($req['end'])) ? $req['start'] . ',' . $req['end'] : '50');
        //echo $sql;
        return $sql;
    }

    private function _getContent_query_bak($type = 'v', $req = [], $select = null) {
        $a = $this->_getContent_sql($type, $req, $select);
        echo $a;
        //union mode
        $union = '';

        if ($req['level_id']) {
            if ($req['level_id'] >= 41 && $req['level_id'] <= 43) {
                $lid = 40;
            } else {
                $lid = $req['level_id'];
            }
        }


        if ($type == 't') {
            $union = "SELECT 			
			'mul_content' tb_name,
			mc.mul_content_id AS content_id,            
            " . ($select == 'full' ? 'mc.mul_content_text as content_text,' : '') . "
            '' AS content_id_child,
            (select TRUNCATE(cc.mul_category_id,-3)) cat_super_id,
			(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE(cc.mul_category_id,-3))) cat_super_name,
            cc.mul_category_id AS cat_id,
            (select mul_category_name from mul_category_2014 where mul_category_id=cc.mul_category_id limit 1) AS cat_name,
            cc.mul_level_id AS cat_level_id,
            ml.mul_level_name AS cat_level_name,
            mc.mul_content_subject AS content_subject,
            'text' AS content_type,
            /*CONCAT('http://www.trueplookpanya.com/new/cms_detail/knowledge/', mc.mul_content_id) AS content_url,*/
			CONCAT('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id) AS content_url,
			CONCAT('http://www.trueplookpanya.com/new/assets/images/icon/doc126x95px.jpg') AS thumbnail,
            mc.mul_tag AS keyword,
            mc.mul_update_date AS date_post,
            mc.member_id AS display_name,
			mc.content_stage AS content_stage,
            
			(select context_id from knowledge_context_2014_map where table_id=1 and content_id=mc.mul_content_id limit 1) as context_id,
			(select idx from knowledge_context_2014_map where table_id=1 and content_id=mc.mul_content_id limit 1) as map_idx

    FROM
        mul_content mc
    INNER JOIN knowledge_context_2014_map cm ON cm.table_id = 1
        AND cm.content_id = mc.mul_content_id
        " . ($req['mul_content_id'] != null ? ' AND mc.mul_content_id = ' . $req['mul_content_id'] . ' ' : '') . "
	LEFT JOIN knowledge_context_2014 cc ON cm.context_id = cc.knowledge_context_id
    LEFT JOIN mul_category_2014 mcat ON cc.mul_category_id = mcat.mul_category_id
		" . ($req['knowledge_id'] != null ? ' AND mc.mul_category_id = ' . $req['knowledge_id'] . ' ' : '') . "
    LEFT JOIN mul_level ml ON cc.mul_level_id = ml.mul_level_id
    WHERE
        mc.mul_content_status = 1
            AND (mc.mul_category_id >= 1000  AND mc.mul_category_id < 9000)
            AND mc.mul_content_id NOT IN (SELECT mul_content_id FROM  mul_source)";
            if ($req['knowledge_id'] != null && $lid != null) {
                $union .=" and cm.context_id in (select knowledge_context_id from knowledge_context_2014 where mul_category_id=" . $req['knowledge_id'] . " and mul_level_id=" . $lid . ")";
            } else {
                if ($req['knowledge_id'] != null) {
                    $union .=" and cm.context_id in (select knowledge_context_id from knowledge_context_2014 where mul_category_id=" . $req['knowledge_id'] . ")  ";
                    //$query .= " and idx = (select min(idx) from knowledge_context_2014_map a3 where a3.table_id=1 and a3.content_id=mc.mul_content_id and a3.context_id in (select context_id from knowledge_context_2014 where  mul_category_id=".$req['knowledge_id'].") )";
                }
                if ($lid != null) {
                    $union .=" and cm.context_id in (select knowledge_context_id from knowledge_context_2014 where mul_level_id=" . $lid . ")";
                    //$query .= " and idx = (select min(idx) from knowledge_context_2014_map a3 where a3.table_id=1 and a3.content_id=mc.mul_content_id and a3.context_id in (select context_id from knowledge_context_2014 where  mul_level_id=".$lid.") )";
                }
                //$union .=" and cm.idx=(select min(idx) from knowledge_context_2014_map a3 where a3.table_id=1 and a3.content_id=mc.mul_content_id)";
            }
            $union .=" " . ($req['context_id'] != null ? ' AND cm.context_id = ' . $req['context_id'] : '');
            $union .= "  UNION ALL ";
        }

        //main query
        $query = "SELECT 
            *
            FROM
    (" . $union . " SELECT 
        'mul_source' tb_name,
            mc.mul_content_id AS content_id,            
            " . ($select == 'full' ? 'mc.mul_content_text as content_text,' : '') . "
            ms.mul_source_id AS content_id_child,
            (select TRUNCATE(cc.mul_category_id,-3)) cat_super_id,
			(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE(cc.mul_category_id,-3))) cat_super_name,
            
			cc.mul_category_id AS cat_id,
            (select mul_category_name from mul_category_2014 where mul_category_id=cc.mul_category_id limit 1) AS cat_name,
            
			cc.mul_level_id AS cat_level_id,
            ml.mul_level_name AS cat_level_name,
            CONCAT(mc.mul_content_subject, ' : ', COALESCE(ms.mul_title, '')) AS content_subject,
            IF(IFNULL(mul_type_id, '') = '', 'text', IF(mul_type_id = 'v', 'vdo', IF(mul_type_id = 'a', 'audio', 'doc'))) AS content_type,
            /*CONCAT('http://www.trueplookpanya.com/new/cms_detail/knowledge/', mc.mul_content_id, '/', ms.mul_source_id) AS content_url,*/
			CONCAT('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id, '/', ms.mul_source_id) AS content_url,
			(case ms.mul_type_id
								when 'v' then 
									if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/data/product/media/',ms.mul_destination,'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
								else 'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default-img.jpg'
							 end
							) thumbnail,
            mc.mul_tag AS keyword,
            mc.mul_update_date AS date_post,
            mc.member_id AS display_name,
			ms.content_stage AS content_stage,
            
			(select context_id from knowledge_context_2014_map where table_id=2 and content_id=ms.mul_source_id limit 1) as context_id,
			(select idx from knowledge_context_2014_map where table_id=2 and content_id=ms.mul_source_id limit 1) as map_idx

    FROM
        mul_content mc
    INNER JOIN mul_source ms ON mc.mul_content_id = ms.mul_content_id
        " . ($type != 'v' ? "AND ms.mul_type_id != 'v'" : "AND ms.mul_type_id = '$type'") . "
	INNER JOIN knowledge_context_2014_map cm ON cm.table_id = 2
        AND cm.content_id = ms.mul_source_id
        " . ($req['mul_source_id'] != null ? ' AND ms.mul_source_id = ' . $req['mul_source_id'] . ' ' : '') . "
    LEFT JOIN knowledge_context_2014 cc ON cm.context_id = cc.knowledge_context_id
    LEFT JOIN mul_level ml ON cc.mul_level_id = ml.mul_level_id
    WHERE
        mc.mul_content_status = 1
        AND (mc.mul_category_id >= 1000 AND mc.mul_category_id < 9000) ";
        if ($req['knowledge_id'] != null && $lid != null) {
            $query .=" and cm.context_id in (select knowledge_context_id from knowledge_context_2014 where mul_category_id=" . $req['knowledge_id'] . " and mul_level_id=" . $lid . ")";
        } else {
            if ($req['knowledge_id'] != null) {
                $query .=" and cm.context_id in (select knowledge_context_id from knowledge_context_2014 where mul_category_id=" . $req['knowledge_id'] . ")  ";
                //$query .= " and idx = (select min(idx) from knowledge_context_2014_map a3 where a3.table_id=2 and a3.content_id=ms.mul_source_id and a3.context_id in (select context_id from knowledge_context_2014 where  mul_category_id=".$req['knowledge_id'].") )";
            }
            if ($lid != null) {
                $query .=" and cm.context_id in (select knowledge_context_id from knowledge_context_2014 where mul_level_id=" . $lid . ")";
                //$query .= " and idx = (select min(idx) from knowledge_context_2014_map a3 where a3.table_id=2 and a3.content_id=ms.mul_source_id and a3.context_id in (select context_id from knowledge_context_2014 where  mul_level_id=".$lid.") )";
            }
        }
        $query .=" " . ($req['context_id'] != null ? ' AND cm.context_id = ' . $req['context_id'] : '') . "		
	) AA            
WHERE 1=1
        AND `cat_level_id` = COALESCE(NULLIF(NULL, '0'), " . ($lid ? $lid : '`cat_level_id`') . ")
        AND `cat_super_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['subject_id'] ? $req['subject_id'] : '`cat_super_id`') . ")
        AND `cat_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['knowledge_id'] ? $req['knowledge_id'] : '`cat_id`') . ")
        AND `context_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['context_id'] ? $req['context_id'] : '`context_id`') . ")
        AND (`content_subject` like '%" . ($req['q'] != null ? $req['q'] : '') . "%' OR `content_subject` = '" . ($req['q'] != null ? $req['q'] : '') . "')                        
ORDER BY date_post DESC
LIMIT " . ((isset($req['start']) && isset($req['end'])) ? $req['start'] . ',' . $req['end'] : '50');

        //echo $query;
        return $query;
    }

    //- P : end

    private function _getRelate_query($type = 'v', $req = [], $select = null) {
        //union mode
        $union = '';

        if ($type == 't') {
            $union = "SELECT 
        'mul_content' tb_name,
            mc.mul_content_id AS content_id,
            " . ($select == 'full' ? 'mc.mul_content_text as content_text,' : '') . "
            '' AS content_id_child,
            CONCAT(LEFT(mc.mul_category_id, 1), '000') cat_super_id,
            mc.mul_category_id AS cat_id,
            mcat.mul_category_name AS cat_name,
            mc.mul_level_id AS cat_level_id,
            ml.mul_level_name AS cat_level_name,
            mc.mul_tag AS keyword,
            mc.mul_update_date AS date_post,
            mc.member_id AS display_name,
            mc.mul_content_subject AS content_subject,
            'text' AS content_type,
            CONCAT('http://www.trueplookpanya.com/new/cms_detail/knowledge/', mc.mul_content_id) AS content_url,
            CONCAT('http://www.trueplookpanya.com/new/assets/images/icon/doc126x95px.jpg') AS thumbnail,
            mc.content_stage AS content_stage,
            cm.context_id context_id,
            cm.idx map_idx
    FROM
        mul_content mc
    INNER JOIN knowledge_context_2014_map cm ON cm.table_id = 1
        AND cm.content_id = mc.mul_content_id
        " . ($req['mul_content_id'] != null ? ' AND mc.mul_content_id = ' . $req['mul_content_id'] . ' ' : '') . "
    LEFT JOIN knowledge_context_2014 cc ON cm.context_id = cc.knowledge_context_id
    LEFT JOIN mul_category_2014 mcat ON cc.mul_category_id = mcat.mul_category_id
    LEFT JOIN mul_level ml ON cc.mul_level_id = ml.mul_level_id
    WHERE
        mc.mul_content_status = 1
            AND ((mc.mul_category_id >= 1000
            AND mc.mul_category_id < 5000)
            OR (mc.mul_category_id >= 8000
            AND mc.mul_category_id < 9000))
            AND mc.mul_content_id NOT IN (SELECT 
                mul_content_id
            FROM
                mul_source) UNION ALL";
        }

        //main query
        $query = "SELECT 
            *
            FROM
    (" . $union . " SELECT 
        'mul_source' tb_name,
            mc.mul_content_id AS content_id,            
            " . ($select == 'full' ? 'mc.mul_content_text as content_text,' : '') . "
            ms.mul_source_id AS content_id_child,
            CONCAT(LEFT(mc.mul_category_id, 1), '000') cat_super_id,
            mc.mul_category_id AS cat_id,
            mc.mul_tag AS keyword,
            mc.mul_update_date AS date_post,
            mc.member_id AS display_name,
            mcat.mul_category_name AS cat_name,
            mc.mul_level_id AS cat_level_id,
            ml.mul_level_name AS cat_level_name,
            CONCAT(mc.mul_content_subject, ' : ', COALESCE(ms.mul_title, '')) AS content_subject,
            IF(IFNULL(mul_type_id, '') = '', 'text', IF(mul_type_id = 'v', 'vdo', IF(mul_type_id = 'a', 'audio', 'doc'))) AS content_type,
            CONCAT('http://www.trueplookpanya.com/new/cms_detail/knowledge/', mc.mul_content_id, '/', ms.mul_source_id) AS content_url,
            (case ms.mul_type_id
								when 'v' then 
									if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/data/product/media/',ms.mul_destination,'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
								else 'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default-img.jpg'
							 end
							) thumbnail,
            ms.content_stage AS content_stage,
            cm.context_id context_id,
            cm.idx map_idx
    FROM
        mul_content mc
    INNER JOIN mul_source ms ON mc.mul_content_id = ms.mul_content_id
        " . ($type != 'v' ? "AND ms.mul_type_id != 'v'" : "AND ms.mul_type_id = '$type'") . "
    INNER JOIN knowledge_context_2014_map cm ON cm.table_id = 2
        AND cm.content_id = ms.mul_source_id
        " . ($req['mul_source_id'] != null ? ' AND ms.mul_source_id = ' . $req['mul_source_id'] . ' ' : '') . "
    LEFT JOIN knowledge_context_2014 cc ON cm.context_id = cc.knowledge_context_id
    LEFT JOIN mul_category_2014 mcat ON cc.mul_category_id = mcat.mul_category_id
    LEFT JOIN mul_level ml ON cc.mul_level_id = ml.mul_level_id
    WHERE
        mc.mul_content_status = 1
            AND ((mc.mul_category_id >= 1000
            AND mc.mul_category_id < 5000)
            OR (mc.mul_category_id >= 8000
            AND mc.mul_category_id < 9000))) AA            
WHERE
        `content_id` = COALESCE(NULLIF(NULL, '0'), `content_id`)
        AND `cat_level_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['level_id'] ? $req['level_id'] : '`cat_level_id`') . ")
        AND `cat_super_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['subject_id'] ? $req['subject_id'] : '`cat_super_id`') . ")
        AND `context_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['knowledge_id'] ? $req['knowledge_id'] : '`context_id`') . ")
        AND (`content_subject` like '%" . ($req['q'] != null ? $req['q'] : '') . "%' OR `content_subject` = '" . ($req['q'] != null ? $req['q'] : '') . "')                        
ORDER BY date_post DESC
LIMIT " . ((isset($req['start']) && isset($req['end'])) ? $req['start'] . ',' . $req['end'] : '50');

//        echo '<pre>';
//        print_r($query);
//        echo '</pre>';
        return $query;
    }

    private function _getContent_query_Fix($type = 'v', $req = [], $select = null) {
        //union mode
        $union = '';

        if ($req['level_id']) {
            if ($req['level_id'] >= 41 && $req['level_id'] <= 43) {
                $lid = 40;
            } else {
                $lid = $req['level_id'];
            }
        }

        if ($type == 't') {
            $union = "SELECT 			
			'mul_content' tb_name,
			mc.mul_content_id AS content_id,            
            " . ($select == 'full' ? 'mc.mul_content_text as content_text,' : '') . "
            '' AS content_id_child,
            (select TRUNCATE(cc.mul_category_id,-3)) cat_super_id,
			(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE(cc.mul_category_id,-3))) cat_super_name,
            cc.mul_category_id AS cat_id,
            mcat.mul_category_name AS cat_name,
            cc.mul_level_id AS cat_level_id,
            ml.mul_level_name AS cat_level_name,
            mc.mul_content_subject AS content_subject,
            'text' AS content_type,
            /*CONCAT('http://www.trueplookpanya.com/new/cms_detail/knowledge/', mc.mul_content_id) AS content_url,*/
			CONCAT('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id) AS content_url,
			CONCAT('http://www.trueplookpanya.com/new/assets/images/icon/doc126x95px.jpg') AS thumbnail,
            mc.mul_tag AS keyword,
            mc.mul_update_date AS date_post,
            mc.member_id AS display_name,
			mc.content_stage AS content_stage,
            cm.context_id context_id,
            cm.idx map_idx
			
    FROM
        mul_content mc
    INNER JOIN knowledge_context_2014_map cm ON cm.table_id = 1
        AND cm.content_id = mc.mul_content_id
        " . ($req['mul_content_id'] != null ? ' AND mc.mul_content_id = ' . $req['mul_content_id'] . ' ' : '') . "
    LEFT JOIN knowledge_context_2014 cc ON cm.context_id = cc.knowledge_context_id
    LEFT JOIN mul_category_2014 mcat ON cc.mul_category_id = mcat.mul_category_id
    LEFT JOIN mul_level ml ON cc.mul_level_id = ml.mul_level_id
    WHERE
        mc.mul_content_status = 1
            AND (mc.mul_category_id >= 1000  AND mc.mul_category_id < 9000)
            AND mc.mul_content_id NOT IN (SELECT mul_content_id FROM  mul_source) UNION ALL";
        }

        //main query
        $query = "SELECT 
            *
            FROM
    (" . $union . " SELECT 
        'mul_source' tb_name,
            mc.mul_content_id AS content_id,            
            " . ($select == 'full' ? 'mc.mul_content_text as content_text,' : '') . "
            ms.mul_source_id AS content_id_child,
            (select TRUNCATE(cc.mul_category_id,-3)) cat_super_id,
			(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE(cc.mul_category_id,-3))) cat_super_name,
            cc.mul_category_id AS cat_id,
            mcat.mul_category_name AS cat_name,
            cc.mul_level_id AS cat_level_id,
            ml.mul_level_name AS cat_level_name,
            CONCAT(mc.mul_content_subject, ' : ', COALESCE(ms.mul_title, '')) AS content_subject,
            IF(IFNULL(mul_type_id, '') = '', 'text', IF(mul_type_id = 'v', 'vdo', IF(mul_type_id = 'a', 'audio', 'doc'))) AS content_type,
            /*CONCAT('http://www.trueplookpanya.com/new/cms_detail/knowledge/', mc.mul_content_id, '/', ms.mul_source_id) AS content_url,*/
			CONCAT('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id, '/', ms.mul_source_id) AS content_url,
			(case ms.mul_type_id
								when 'v' then 
									if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/data/product/media/',ms.mul_destination,'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
								else 'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default-img.jpg'
							 end
							) thumbnail,
            mc.mul_tag AS keyword,
            mc.mul_update_date AS date_post,
            mc.member_id AS display_name,
			ms.content_stage AS content_stage,
            cm.context_id context_id,
            cm.idx map_idx
    FROM
        mul_content mc
    INNER JOIN mul_source ms ON mc.mul_content_id = ms.mul_content_id
        " . ($type != 'v' ? "AND ms.mul_type_id != 'v'" : "AND ms.mul_type_id = '$type'") . "
    INNER JOIN knowledge_context_2014_map cm ON cm.table_id = 2
        AND cm.content_id = ms.mul_source_id
        " . ($req['mul_source_id'] != null ? ' AND ms.mul_source_id = ' . $req['mul_source_id'] . ' ' : '') . "
    LEFT JOIN knowledge_context_2014 cc ON cm.context_id = cc.knowledge_context_id
    LEFT JOIN mul_category_2014 mcat ON cc.mul_category_id = mcat.mul_category_id
    LEFT JOIN mul_level ml ON cc.mul_level_id = ml.mul_level_id
    WHERE
        mc.mul_content_status = 1
            AND (mc.mul_category_id >= 1000 AND mc.mul_category_id < 9000)
			) AA            
WHERE 1=1
        AND `cat_level_id` = COALESCE(NULLIF(NULL, '0'), " . ($lid ? $lid : '`cat_level_id`') . ")
        AND `cat_super_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['subject_id'] ? $req['subject_id'] : '`cat_super_id`') . ")
        AND `cat_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['knowledge_id'] ? $req['knowledge_id'] : '`cat_id`') . ")
        AND `context_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['context_id'] ? $req['context_id'] : '`context_id`') . ")
        AND `content_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['mul_content_id'] ? $req['mul_content_id'] : '`content_id`') . ")
        AND `content_id_child` = COALESCE(NULLIF(NULL, '0'), " . ($req['mul_source_id'] ? $req['mul_source_id'] : '`content_id_child`') . ")
        AND (`content_subject` like '%" . ($req['q'] != null ? $req['q'] : '') . "%' OR `content_subject` = '" . ($req['q'] != null ? $req['q'] : '') . "')                        
ORDER BY date_post DESC
LIMIT " . ((isset($req['start']) && isset($req['end'])) ? $req['start'] . ',' . $req['end'] : '50');

        //echo $query;
        return $query;
    }

    private function _getContent_query_Fix2($type = 'v', $req = [], $select = null) {

        if ($req['level_id']) {
            if ($req['level_id'] >= 41 && $req['level_id'] <= 43) {
                $lid = 40;
            } else {
                $lid = $req['level_id'];
            }
        }

        $criteria_inside = "";
        if ($type != "") {
            if ($type == "v") {
                $criteria_inside .=" and mul_type_id='v'";
            } elseif ($type == "t") {
                $criteria_inside .=" and (mul_type_id is null or mul_type_id != 'v')";
            }
        }

        if ($req['q'] != null && $req['q'] != '') {
            $criteria .=" and (`content_subject` like ''%" . $req['q'] . "%' or `content_subject`= '" . $req['q'] . "')";
        }
        if ($req['subject_id'] != null && $req['subject_id'] != '') {
            $criteria .=" and (`cat_super_id` like ''%" . $req['q'] . "%' or `cat_super_id`= '" . $req['q'] . "')";
        }

        $criteria_in = "";
        if ($req['knowledge_id'] != null) {
            $criteria_in .= " and context_id in (select knowledge_context_id from knowledge_context_2014 where mul_category_id=" . $req['knowledge_id'] . ")";
        }
        if ($lid != null) {
            $criteria_in .= " and context_id in (select knowledge_context_id from knowledge_context_2014 where mul_level_id=" . $lid . ")";
        }
        if ($req['context_id'] != null) {
            $criteria_in .= " and context_id=" . $req['context_id'];
        }

        if ($req['mul_content_id'] != null) {

            if ($req['mul_source_id'] != null) {
                $sql_content = "AND `content_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['mul_content_id'] ? $req['mul_content_id'] : '`content_id`') . ")";
                $sql_mul_content = "AND `content_id_child` = COALESCE(NULLIF(NULL, '0'), " . ($req['mul_source_id'] ? $req['mul_source_id'] : '`content_id_child`') . ")";
            } else {
                $sql_content = "AND `content_id` = COALESCE(NULLIF(NULL, '0'), " . ($req['mul_content_id'] ? $req['mul_content_id'] : '`content_id`') . ")";
            }
        }

        $sql = "select AA.*
,(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id)  " . $criteria_in . ") context_id

,(select mul_level_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " )) cat_level_id
,(select mul_category_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " )) cat_id

,(select mul_level_name from mul_level where mul_level_id=(select mul_level_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " ))) cat_level_name,
(select TRUNCATE((select mul_category_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " )),-3)) cat_super_id,
(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE((select mul_category_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " )),-3))) cat_super_name,
(select mul_category_name from mul_category_2014 where mul_category_id=(select mul_category_id from knowledge_context_2014 where knowledge_context_id=(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id) " . $criteria_in . " )) limit 1) cat_name

from (
select
 'mul_content' tb_name
 ,mc.mul_content_id as content_id
 ,ms.mul_source_id as content_id_child
 ,ms.mul_destination AS path
,ms.mul_filename AS filename
 " . ($select == 'full' ? ',mc.mul_content_text as content_text' : '') . "
 , concat(mc.mul_content_subject,if(ifnull(ms.mul_title,'')!='',concat(' : ',ms.mul_title),'')) as content_subject
 , if(ifnull(mul_type_id,'')='','text',if(mul_type_id='v','vdo',if(mul_type_id='a','audio','doc'))) as content_type
 ,concat('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id,if(ms.mul_source_id is not null,concat( '-',ms.mul_source_id),'')) AS content_url
 ,(case ms.mul_type_id
								when 'v' then 
									if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/data/product/media/',ms.mul_destination,'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
								else 'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default-img.jpg'
							 end
							) thumbnail
 ,mc.mul_tag AS keyword
 ,mc.mul_update_date AS date_post
 ,mc.member_id AS display_name
 ,if(ifnull(ms.mul_source_id,'')='',mc.content_stage,ms.content_stage) as content_stage
 
from mul_content mc
 left join `mul_source` ms on mc.mul_content_id=ms.mul_content_id
 left join `users_account` acc on mc.member_id = acc.member_id
where 1=1
and (if(ms.mul_source_id is not null,ms.mul_source_id in(select content_id from knowledge_context_2014_map where table_id=2),mc.mul_content_id in (select content_id from knowledge_context_2014_map where table_id=1)))
" . $criteria_inside . "
) AA 
		WHERE 1=1
	 " . $criteria . "
         " . $sql_content . "
         " . $sql_mul_content . "
	ORDER BY date_post DESC
	LIMIT " . ((isset($req['start']) && isset($req['end'])) ? $req['start'] . ',' . $req['end'] : '50');

        //echo $sql;
        return $sql;
    }

    public function searching($select = NULL) {
        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }
        $req = [];
        $req['subject_id'] = $_GET['sid'];
        $req['level_id'] = $_GET['lid'];
        $req['knowledge_id'] = $_GET['cid'];
        $req['q'] = $_GET['q'];

        $all_query = $this->_getContent_query('all', $req, $select);
        $video_query = $this->_getContent_query('v', $req, $select);
        $text_query = $this->_getContent_query('t', $req, $select);

        $data = [];
        $respvideo = $this->db->query($video_query);



        if ($respvideo->num_rows() > 0) {
            $data['clip'] = $respvideo->result_array();

            $this->load->library('trueplook');
            foreach ($data['clip'] as $key => $value) {
                //print_r($value['content_id_child']);
                $data['clip'][$key]['viewcount'] = $this->trueplook->getViewNumber(((int) $value['content_id_child']), 0);
                $data['clip'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');


                //$data['clip'][$key]['date_post'] = $this->trueplook->data_format('small', 'th', $value['date_post']);
            }
        } else {
            $data['clip'] = [];
        }

        $resptext = $this->db->query($text_query);
        if ($resptext->num_rows() > 0) {
            $data['other'] = $resptext->result_array();

            $this->load->library('trueplook');
            foreach ($data['other'] as $key => $value) {
                //print_r($value['content_id_child']);
                $data['other'][$key]['viewcount'] = $this->trueplook->getViewNumber(((int) $value['content_id']), 0);
                $data['other'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');
                //$data['other'][$key]['date_post'] = $this->trueplook->data_format('small', 'th', $value['date_post']);
            }
        } else {
            $data['other'] = [];
        }
        echo json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    }

    public function content($mul_content_id = 0, $mul_source_id = '', $select = 'full') {

        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }

        $req = [];

        $req['mul_content_id'] = $mul_content_id;
        $req['mul_source_id'] = $mul_source_id;
        $text_query = $this->_getContent_query_Fix2('all', $req, $select);

        $data = [];
        $resptext = $this->db->query($text_query);
        if ($resptext->num_rows() > 0) {
            $data['other'] = $resptext->result_array();

            $this->load->library('trueplook');

            if ($data['other'][0]['content_type'] == 'vdo') {

                foreach ($data['other'] as $key => $value) {
                    //print_r($value['content_id_child']);
                    $data['other'][$key]['viewcount'] = strval($this->trueplook->getViewNumber($value['content_id_child'], 7));
                    $data['other'][$key]['view_show'] = convertToK($data['other'][$key]['viewcount']);
                    $data['other'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');
                    $data['other'][$key]['date_short'] = $this->trueplook->data_format('small', 'th', $value['date_post']);
//                    $sql_vdo = "SELECT ms.mul_destination,ms.mul_filename FROM mul_source ms WHERE ms.mul_source_id = " . ((int) $value['content_id_child']) . " AND ms.mul_type_id = 't';";
                    $sql_vdo = "SELECT ms.mul_destination,ms.mul_filename FROM mul_source ms WHERE ms.mul_source_id = " . ((int) $value['content_id_child']);
                    $q_vdo = $this->db->query($sql_vdo);
                    $rs_vdo = $q_vdo->result_array();
                    $data['other'][$key]['vdo_play'] = $this->trueplook->get_vdo_url($rs_vdo[0]['mul_destination'], $rs_vdo[0]['mul_filename']);
                    //$data['other'][$key]['check_hd2'] = $this->trueplook->get_vdo_url($rs_vdo[0]['mul_destination'], $rs_vdo[0]['mul_filename'], true);
                }
            } else {
                foreach ($data['other'] as $key => $value) {
                    //print_r($value['content_id_child']);
                    $data['other'][$key]['viewcount'] = strval($this->trueplook->getViewNumber($value['content_id'], 7));
                    $data['other'][$key]['view_show'] = convertToK($data['other'][$key]['viewcount']);
                    $data['other'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');
                    $data['other'][$key]['date_short'] = $this->trueplook->data_format('small', 'th', $value['date_post']);
                }
            }
        } else {
            $data['other'] = [];
        }

        echo json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    }

    public function vdocontent($mul_content_id = 0, $mul_source_id = '', $select = 'full') {

        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }

        $req = [];

        $req['mul_content_id'] = $mul_content_id;
        $req['mul_source_id'] = $mul_source_id;


        $text_query = $this->_getContent_query_Fix('v', $req, $select);
        $data = [];
        $resptext = $this->db->query($text_query);
        if ($resptext->num_rows() > 0) {
            $data['other'] = $resptext->result_array();

            $this->load->library('trueplook');

            foreach ($data['other'] as $key => $value) {
                //print_r($value['content_id_child']);

                $data['other'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');
                $data['other'][$key]['date_post'] = $this->trueplook->data_format('small', 'th', $value['date_post']);
                //$data['other'][$key]['vdo_play'] = $this->trueplook->get_vdo_url(((String)$value['vdo_path']),((String)$value['vdo_file']));

                $sql_vdo = "SELECT ms.mul_destination,ms.mul_filename FROM mul_source ms WHERE ms.mul_source_id = " . ((int) $value['content_id_child']) . " AND ms.mul_type_id = 't';";
                //$sql_vdo = "SELECT ms.mul_destination,ms.mul_filename FROM mul_source ms WHERE ms.mul_source_id = " . ((int) $value['content_id_child']);
                $q_vdo = $this->db->query($sql_vdo);
                $rs_vdo = $q_vdo->result_array();
                $view = $this->trueplook->getViewNumber($value['content_id_child'], 7);
                $data['other'][$key]['viewcount'] = empty($view) ? 0 : $view;
                $this->trueplook->addViewVal_old($value['content_id_child'], 7);

                $data['other'][$key]['vdo_play'] = $this->trueplook->get_vdo_url($rs_vdo[0]['mul_destination'], $rs_vdo[0]['mul_filename']);
                //$data['other'][$key]['check_hd2'] = $this->trueplook->get_vdo_url($rs_vdo[0]['mul_destination'], $rs_vdo[0]['mul_filename'], true);
            }
        } else {
            $data['other'] = [];
        }

        echo json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    }

    public function othercontent($mul_content_id = 0, $select = 'full') {
        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }
        $req = [];

        $req['mul_content_id'] = $mul_content_id;



        $text_query = $this->_getContent_query_Fix('t', $req, $select);
        $data = [];
        $resptext = $this->db->query($text_query);
        if ($resptext->num_rows() > 0) {
            $data['other'] = $resptext->result_array();

            $this->load->library('trueplook');
            foreach ($data['other'] as $key => $value) {
                //print_r($value['content_id_child']);
                $data['other'][$key]['viewcount'] = $this->trueplook->getViewNumber(((int) $value['content_id']), 0);
                $this->trueplook->addViewVal_old($value['content_id'], 0);
                $data['other'][$key]['display_name'] = $this->trueplook->get_display_name($value['display_name'], 'name');
                $data['other'][$key]['date_post'] = $this->trueplook->data_format('small', 'th', $value['date_post']);
            }
        } else {
            $data['other'] = [];
        }
        echo json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    }

    public function getcontext($limit = 0, $mul_category_id = 0, $mul_level_id = 0) {
        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }
        $this->db->select('*')->from('knowledge_context_2014 kc');
        $this->db->join('mul_category_2014 mc', 'kc.mul_category_id=mc.mul_category_id');
        if ($mul_category_id != 0) {
            $this->db->where('mc.mul_parent_id', $mul_category_id);
        }
        if ($mul_level_id != 0) {
            $this->db->where('kc.mul_level_id', $mul_level_id);
        }
        if ($limit != 0) {
            $this->db->limit($limit);
        }
        $resp = $this->db->get();
        $data = $resp->result_array();
        echo json_encode($data);
    }

    public function getCat($sid = 0, $lid = 0) {
        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }
        if (isset($_GET['sid'])) {
            $sid = $_GET['sid'];
        }
        if (isset($_GET['lid'])) {
            $lid = $_GET['lid'];

            if ($lid >= 41 && $lid <= 43) {
                $lid = 40;
            }
        }
        $this->db->distinct()->select('mc.*')->from('mul_category_2014 mc')->where('mc.mul_category_id >', 1000);

        if ($sid != 0) {
            $this->db->where('mc.mul_parent_id', $sid);
        }

        if ($lid != 0) {
            $this->db->join('knowledge_context_2014 kc', 'mc.mul_category_id=kc.mul_category_id')
                    ->where('kc.mul_level_id', $lid);
        }
        $req = $this->db->get();
        $data_check = $req->result_array();

        //
        foreach ($data_check as $key => $value) {
            if ($value['mul_category_id'] >= 1000 && $value['mul_category_id'] <= 8000) {
                if ($value['mul_category_id'] == $value['mul_parent_id']) {
                    unset($data_check[$key]);
                }
            }
        }
        $data = array_values($data_check);
        //


        if ($req->num_rows() > 0) {
            echo json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(NULL, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
        }
    }

    public function getactivities($category_id = 0, $mul_content_id = 0) {
        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }
        $this->db->select('*')
                ->from('mul_content')
                ->join('mul_source', 'mul_content.mul_content_id=mul_source.mul_content_id')
                ->where('mul_category_id', $category_id)
                ->where('mul_type_id', 'v')
                ->where('mul_content.mul_content_status', 1);
        if ($mul_content_id != 0) {
            $this->db->where('mul_content.mul_content_id', $mul_content_id);
        }
        if (isset($_GET['offset'])) {
            $this->db->limit($_GET['end'], $_GET['start']);
        } else {
            $this->db->limit(8);
        }
        $this->db->order_by('mul_content.mul_content_id', 'DESC');
        $resp = $this->db->get();
        $data = $resp->result_array();


        foreach ($data as $key => $value) {

            if ($value['mul_type_id'] == 'v') {
                $file_name = explode('.', $value['mul_filename']);
                $data[$key]['thumb'] = "http://www.trueplookpanya.com/new/cutresize/re/320/240/" . str_replace('/', '-', $value['mul_destination']) . "/" . $file_name[0] . "_320x240.png";
            } else {
                $data[$key]['thumb'] = "http://www.trueplookpanya.com/new/assets/images/icon/doc126x95px.jpg";
            }
        }

        echo json_encode($data);
    }

    public function getchildhood() {

        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }

        $this->db->select('*')
                ->from('mul_content')
                ->join('mul_source', 'mul_content.mul_content_id=mul_source.mul_content_id')
                ->where('mul_type_id', 'v')
                ->where('mul_content.mul_content_status', 1)
                ->where_in('mul_category_id', array(100, 300, 500, 700));

        $start = $this->input->get('s');
        $end = $this->input->get('e');

        $lid = $this->input->get('level_id');
        $q = $this->input->get('q');


        if (isset($q) && $q != null && $q != '') {
            $this->db->like('mul_content.mul_content_subject', $q);
        }
        if (isset($lid) && $lid != null && $lid != '') {
            $this->db->where('mul_content.mul_level_id', $lid);
        }

        if (!empty($end)) {
            $this->db->limit($end, $start);
        } else {
            $this->db->limit(8);
        }

        $this->db->order_by('mul_content.mul_content_id', 'DESC');
        $resp = $this->db->get();
        $data = $resp->result_array();

        foreach ($data as $key => $value) {
            if ($value['mul_type_id'] == 'v') {
                $file_name = explode('.', $value['mul_filename']);
                $data[$key]['thumb'] = "http://www.trueplookpanya.com/new/cutresize/re/320/240/" . str_replace('/', '-', $value['mul_destination']) . "/" . $file_name[0] . "_320x240.png";
            } else {
                $data[$key]['thumb'] = "http://www.trueplookpanya.com/new/assets/images/icon/doc126x95px.jpg";
            }
        }


        echo json_encode($data);
    }

    public function searchActivities($category_id = 0) {
        if ($this->getMethod() != "GET") {
            http_response_code(404);
            exit();
        }
        $this->db->select('*')->from('mul_content')
                ->join('mul_source', 'mul_content.mul_content_id=mul_source.mul_content_id')
                ->where('mul_category_id', $category_id)
                ->where('mul_type_id', 'v')
                ->where('mul_content.mul_content_status', 1);

        if (isset($_GET['q']) && $_GET['q'] != null && $_GET['q'] != '') {
            $this->db->like('mul_content.mul_content_subject', $_GET['q']);
        }
        if (isset($_GET['level_id']) && $_GET['level_id'] != null && $_GET['level_id'] != '') {
            $this->db->where('mul_content.mul_level_id', $_GET['level_id']);
        }
        if (isset($_GET['offset'])) {
            $this->db->limit($_GET['end'], $_GET['start']);
        } else {
            $this->db->limit(8);
        }

        $this->db->order_by('mul_content.mul_content_id', 'DESC');
        $resp = $this->db->get();
        $data = $resp->result_array();

        foreach ($data as $key => $value) {

            if ($value['mul_type_id'] == 'v') {
                $file_name = explode('.', $value['mul_filename']);
                $data[$key]['thumb'] = "http://www.trueplookpanya.com/new/cutresize/re/320/240/" . str_replace('/', '-', $value['mul_destination']) . "/" . $file_name[0] . "_320x240.png";
            } else {
                $data[$key]['thumb'] = "http://www.trueplookpanya.com/new/assets/images/icon/doc126x95px.jpg";
            }
        }

        echo json_encode($data);
    }

    function getInfographic() {
        if ($this->getMethod() != 'GET') {
            http_response_code(500);
            exit();
        }
        $this->db->select('*');
        $this->db->from('cvs_infographic');
        $this->db->where('infographic_cate_id !=', '0');
        $this->db->where('status', '1');
        $this->db->order_by('sort', 'desc');
        $this->db->order_by('cdate', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            $arr['title'] = $data['title'];
            $arr['thumbnail'] = get_file_url($data['image_full']);
            $arr['link'] = site_url('infographic/detail/' . $data['id'] . '/' . $data['title']);
            $arr['image'] = get_file_url($data['image_full']);
        }
        echo json_encode(array($arr), JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    }

    function getMethod() {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'PUT':
                    return 'PUT';
                    break;
                case 'DELETE':
                    return 'DELETE';
                    break;
                case 'POST':
                    return 'POST';
                    break;
                case 'GET':
                    return 'GET';
                    break;
                default :
                    return false;
                    break;
            }
        }
    }

}
