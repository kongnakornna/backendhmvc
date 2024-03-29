<?php
class getRelate_model extends CI_Model {
    private $DBSelect, $DBEdit;
    public function __construct() {
        parent::__construct();
		$CI = & get_instance();
    }
	//-- NEW LEARNING start 
    public function _getContent_query_lite($type = 'v', $req = [], $select = null) {
		
		if ($req['level_id']) {
            if ($req['level_id'] >= 41 && $req['level_id'] <= 43) 
			{
                $lid = 40;
            } 
			else 
			{
                $lid = $req['level_id'];
            }
        }

        $criteria_inside = "";
		if($type!=""){
			if($type=="v"){
				$criteria_inside .=" and mul_type_id='v'";
			}
			elseif($type=="t"){
				$criteria_inside .=" and (mul_type_id is null or mul_type_id != 'v')";
			}
		}
		if($req['this_content_id'] != null && $req['this_content_id'] !="" ){
			$criteria_inside .= " and mc.mul_content_id=".$req['this_content_id'];
		}
		if($req["mul_content_id"] != null && $req["mul_content_id"] != ""){
			//$criteria_inside .= " and mc.mul_content_id!=".$req["mul_content_id"];
                        $criteria_inside .= "and mc.mul_content_id!=".$req["mul_content_id"]."
						and mc.mul_content_id in (select content_id from knowledge_context_2014_map where table_id=1 and context_id in (select knowledge_context_id from knowledge_context_2014 where (mul_level_id = (select mul_level_id from mul_content where mul_content_id=".$req["mul_content_id"].") or TRUNCATE(mul_level_id,-1) = (select TRUNCATE(mul_level_id,-1) from mul_content where mul_content_id=".$req["mul_content_id"].")) and TRUNCATE(mul_category_id,-3) = (select TRUNCATE(mul_category_id,-3) from mul_content where mul_content_id=".$req["mul_content_id"].")))
						";
                        
		}
		if($req["mul_source_id"] != null && $req["mul_source_id"] != ""){
			//$criteria_inside .= " and ms.mul_source_id!=".$req["mul_source_id"];
                        $criteria_inside .= "and (if(ms.mul_source_id is not null, ms.mul_source_id!=".$req["mul_source_id"].",ms.mul_source_id is null))";
		}
		if($req["isActivity"] == true && $req["isActivity"] != null){
			$criteria_inside .= " and (mc.mul_category_id<1000 or mc.mul_category_id>=9000) ";
			if($req["category_id"] != null && $req["category_id"] != ""){
				$criteria_inside .= " and mc.mul_category_id=".$req["category_id"]."";
			}
			if($req["level_id"] != null && $req["level_id"] != ""){
				$criteria_inside .= " and mc.mul_level_id=".$req["level_id"];
				$lid=null;
			}
		}
		
		$labeltag_id = $req['labeltagList'];
		if($labeltag_id!=null && $labeltag_id!=""){
			$criteria_inside .= " and (if(ms.mul_source_id is not null, ms.mul_source_id in (select content_id from label_tag_map where project_id=2 and label_tag_id in ($labeltag_id)),mc.mul_content_id in (select content_id from label_tag_map where project_id=1 and label_tag_id in ($labeltag_id))))";
		}
		
		$criteria_inside .=" order by date_post DESC";
		
		
		if($req['q'] != null && $req['q'] != ''){
			$criteria .=" and (`content_subject` like '%".$req['q']."%' or `content_subject`= '".$req['q']."')";
		}
		
		$criteria_in = "";
		if($req['knowledge_id'] != null){
			$criteria_in .= " and context_id in (select knowledge_context_id from knowledge_context_2014 where mul_category_id=".$req['knowledge_id'].")";
		}
		if($lid != null){
			if($lid % 10 == 0){
			  $lid_criteria = "TRUNCATE(mul_level_id,-1)=".$lid;
			}else{
			  $lid_criteria = "mul_level_id=".$lid;
			}
			$criteria_in .= " and context_id in (select knowledge_context_id from knowledge_context_2014 where ".$lid_criteria.")";
		}
		if($req['subject_id'] != null){
			$criteria_in .= " and context_id in (select knowledge_context_id from knowledge_context_2014 where TRUNCATE(mul_category_id,-3)=".$req['subject_id'].")";
		}	
		if($req['context_id'] != null){
			$criteria_in .= " and context_id=".$req['context_id'];
		}
		
		if($req["isUniverseContent"] == true && $req["isUniverseContent"] != null){
			$criteria_map = "";
		}else{
			$criteria_map = "and (if(ms.mul_source_id is not null,ms.mul_source_id in (select content_id from knowledge_context_2014_map where table_id=2 ".$criteria_in."),mc.mul_content_id in (select content_id from knowledge_context_2014_map where table_id=1 ".$criteria_in.")))";
		}
		
		if($req["record_status"] == "all"){
			$criteria_status = "";
		}else{
			$criteria_status = " and mc.mul_content_status=1 and (if(ms.mul_source_id is not null, ms.mul_source_status!=5,ms.mul_source_id is null))";
		}
		
		
		
		if($req['orderby']!=null){
			if($req['orderby']=='rand'){
				$criteria_inside .= " limit 200";
				$orderby_out = " order by rand()";
			}
		}else{
			$orderby_out = " order by date_post DESC";
		}
		
		$sql="select AA.*
,(select min(context_id) from knowledge_context_2014_map where if(AA.content_id_child is not null,table_id=2 and content_id=AA.content_id_child,table_id=1 and content_id=AA.content_id)  ".$criteria_in.") context_id

from (
select
 'mul_content' tb_name
 ,mc.mul_content_id as content_id
 ,ms.mul_source_id as content_id_child
 " . ($select == 'full' ? ',mc.mul_content_text as content_text' : '') . "
 , concat(mc.mul_content_subject,if(ifnull(ms.mul_title,'')!='',concat(' : ',ms.mul_title),'')) as content_subject
 , if(ifnull(mul_type_id,'')='','text',if(mul_type_id='v','vdo',if(mul_type_id='a','audio','doc'))) as content_type
 ,concat('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id,
 if(ms.mul_source_id is not null,concat( '-',ms.mul_source_id),'')) AS content_url
 ,(case ms.mul_type_id
  when 'v' then 
  if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/new/cutresize/re/520/440/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
  else if(nullif(ms.mul_source_id,'')!='' and nullif(ms.mul_type_id,'')!='','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_download.png','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_text.png')
  end
 ) thumbnail
 /*,(case ms.mul_type_id
								when 'v' then 
									if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/data/product/media/',ms.mul_destination,'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
								else if(nullif(ms.mul_source_id,'')!='' and nullif(ms.mul_type_id,'')!='','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_download.png','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_text.png')
							 end
							) thumbnail*/
 ,mc.mul_tag AS keyword
 ,mc.mul_update_date AS date_post
 ,mc.member_id AS display_name
 ,if(ifnull(ms.mul_source_id,'')='',mc.content_stage,ms.content_stage) as content_stage
 ,mc.view_count content_view_count
 ,ms.view_count child_view_count
 ,acc.psn_display_name as addBy
 ,acc.user_id as addBy_id
 ,acc.psn_display_image as addBy_image
 ,acc.psn_display_banner as addBy_banner
 ,'0' as addBy_follow_count
from mul_content mc
 left join `mul_source` ms on mc.mul_content_id=ms.mul_content_id
 left join `users_account` acc on mc.member_id = acc.member_id
where 1=1  
".$criteria_status."
".$criteria_map."
".$criteria_inside."
) AA 
		WHERE 1=1
	 ".$criteria."
	".$orderby_out."
	LIMIT " . ((isset($req['start']) && isset($req['end'])) ? $req['start'] . ',' . $req['end'] : '50');	

		// echo $sql;
		return $sql;
	}
	private function _getArrContent_info($mul_content_id, $context_id) {
															// ตอนนี้ไม่สนใจ $context_id แล้ว 
		$r = array();
		$getOld = false;
		
		$cache_key = "learning_info+$mul_content_id";
		//$this->tppymemcached->delete($cache_key);
		if (!$r = $this->tppymemcached->get($cache_key)) {
			
				// check map
				$sql = "select A.* 
				,(select mul_level_name from mul_level where mul_level_id=cat_level_id) cat_level_name 
				,(select mul_category_name from mul_category_2014 where mul_category_id=cat_id) cat_name 
				,(select TRUNCATE(cat_id,-3)) cat_super_id 
				,(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE(cat_id,-3))) cat_super_name 
				from (
					select 
					m.context_id context_id,c.knowledge_context_name context_name , c.mul_level_id cat_level_id, c.mul_category_id cat_id
					from knowledge_context_2014_map m
					left join knowledge_context_2014 c on m.context_id=c.knowledge_context_id
					where 1 and
					(table_id=1 and content_id=$mul_content_id)
					 or 
					(table_id=2 and content_id in (select mul_source_id from mul_source where mul_content_id=$mul_content_id))
					group by context_id, context_name , cat_level_id, cat_id
				) A";
				$query = $this->db->query($sql);
				if($query){
					if($query->num_rows()>0){ // มีการผูกตัวชี้วัด

						$arrResult = $query->result_array();
						foreach ($arrResult as $k => $v) {
							$r[$k]['context_id'] = $v['context_id'];
							$r[$k]['context_name'] = $v['context_name'];
							$r[$k]['context_type'] = "mapped";
							$r[$k]['cat_level_id'] = $v['cat_level_id'];
							$r[$k]['cat_id'] = $v['cat_id'];
							$r[$k]['cat_level_name'] = $v['cat_level_name'];
							$r[$k]['cat_super_id'] = $v['cat_super_id'];
							$r[$k]['cat_super_name'] = $v['cat_super_name'];
							$r[$k]['cat_name'] = $v['cat_name'];
						}
						
					}else{
						$getOld = true;
					}
				}else{
					$getOld = true;
				}
				
				// default content :: for not map and old content
				if($getOld){
					$sql = "select 
					'' context_id
					,'' context_name
					,mc.mul_category_id cat_id
					,cat.mul_category_name cat_name
					,mc.mul_level_id cat_level_id
					,lev.mul_level_name cat_level_name
					,(if(mc.mul_category_id between 1000 and 9000,TRUNCATE(mc.mul_category_id,-3),mc.mul_category_id)) cat_super_id
					,(select mul_category_name from mul_category where mul_category_id=(if(mc.mul_category_id between 1000 and 9000,TRUNCATE(mc.mul_category_id,-3),mc.mul_category_id)) ) cat_super_name
					from mul_content mc
					left join mul_category cat on mc.mul_category_id=cat.mul_category_id
					left join mul_level lev on mc.mul_level_id=lev.mul_level_id
					where mc.mul_content_id=$mul_content_id";
					$query = $this->db->query($sql);
					if($query){
						$arrResult = $query->result_array();
						if ($arrResult) {
							foreach ($arrResult as $k => $v) {
								$r[$k]['context_id'] = $v['context_id'];
								$r[$k]['context_name'] = $v['context_name'];
								$r[$k]['cat_level_id'] = $v['cat_level_id'];
								$r[$k]['cat_id'] = $v['cat_id'];
								$r[$k]['cat_level_name'] = $v['cat_level_name'];
								$r[$k]['cat_super_id'] = $v['cat_super_id'];
								$r[$k]['cat_super_name'] = $v['cat_super_name'];
								$r[$k]['cat_name'] = $v['cat_name'];
								$r[$k]['context_type'] = "old : not map";
							}
						}
					}
				}
			
				$this->tppymemcached->set($cache_key, $r,120);
		}
		
		
		return $r;
	}
	public function get_Learning_list($req = array()){
		$sql = $this->_getContent_query_lite($req['type'], $req, $req['select']);
		//return $sql;
		
		$cache_key = implode("_",$req); 
		// echo '===='.$cache_key;
		// $this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			// $this->db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $this->db->query($sql);
			// $this->db->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult, 1800);
		}
		
		//$query = $this->db->query($sql);
		if($arrResult){
			$this->load->model('blog/blogmodel');

			foreach($arrResult as $k => $v){
				$content_id = $v['content_id'];
				$context_id =$v['context_id'];
				
				$viewcount = ((int) $v['content_view_count'] ) + ((int) $v['child_view_count'] );
				$arrResult[$k]['viewcount'] = $viewcount;
				$arrResult[$k]['context_info'] = $this->_getArrContent_info($content_id, $context_id);
				$arrResult[$k]['addBy_follow_count'] = $this->blogmodel->countFollow($v['addBy_id']);
				$arrResult[$k]['labelTag_list'] = $this->getLabelTag_info(2,$v['content_id']);
			}
			return $arrResult;
		}else{
			return false;
		}
	}
	public function get_Learning_detail($content_id, $arrFilter=array(),$limitend=''){
		if(!isset($content_id)){
			echo 'content_id & type are required';
			exit();
		}
		
		$req['type'] = null;
		$req['select'] = 'full';
		$req['this_content_id'] = $content_id;
		$req["isUniverseContent"] = true;
		$req["record_status"] = $arrFilter['record_status'];
		if($req["isUniverseContent"]===true && $req["record_status"]=="all"){
			$cc = "+all";
		}
		
		
		$cache_key = "learning_detail+$content_id".$cc;
		// $this->tppymemcached->delete($cache_key);
		if (!$arr = $this->tppymemcached->get($cache_key)) {
			$arr = $this->get_Learning_list($req);
			if(is_array($arr)){
				$this->tppymemcached->set($cache_key, $arr,120);
				//$this->tppymemcached->set($cache_key, $arr,60);
			}
		}
		
		if(is_array($arr)){
			$arrReturn[] = $arr[0];

			foreach($arrReturn as $k => $v){
					$id = $v['content_id'];
					$id_child = $v['content_child_id'];
					
					//---------------------------- Add View ---------------------------//
					$this->tppy_utils->ViewNumberSet($id, 'mul_content');
					
					// node Social
					$arrReturn[$k]['og']['og:app_id'] = "704799662982418";
					$arrReturn[$k]['og']['og:title'] = $v['content_subject'];
					$arrReturn[$k]['og']['og:type'] = 'article';
					$arrReturn[$k]['og']['og:locale'] = 'th_TH';
					$arrReturn[$k]['og']['og:url'] = $v['content_url'];
					$arrReturn[$k]['og']['og:image'] = $v['thumbnail'];
					$arrReturn[$k]['og']['og:site_name'] = 'trueplookpanya.com';
					$arrReturn[$k]['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $v['short_description']))), 200);
					
					// node Files
					$arrReturn[$k]['files'] = $this->_getFile_learning($id);
					
					// node LabelTag
					$arrReturn[$k]['labelTag_list'] = $this->getLabelTag_info(2,$v['content_id']);
					
					// node Relate
					$id_child = (int) $id_child;
     if($limitend==Null){
      $limitend=3;
     }else{
      $limitend=$limitend;
     }
					$cache_key = 'learning_detail_relate+'.$id.'_'.$id_child.'_limit_'.$limitend;
					// $this->tppymemcached->delete($cache_key);
					if (!$arrRelate = $this->tppymemcached->get($cache_key)) {
						if($id_child>0){ 
							$reqRelate['mul_source_id'] = $id_child; 
						}else{
							$reqRelate['mul_content_id'] = $id;
						}
						$reqRelate['type'] = null;
						$reqRelate['select'] = '';
						$reqRelate['orderby'] = '';
						$reqRelate['start'] = 0;
      $reqRelate['end']=$limitend;
						$reqRelate["isUniverseContent"] = true;
						$arrRelate = $this->get_Learning_list($reqRelate);
						

						$this->tppymemcached->set($cache_key, $arrRelate,600);
							
					}
					$arrReturn[$k]['relate_content'] = $arrRelate;
					
					// if($id_child>0){ 
						// $reqRelate['mul_source_id'] = $id_child; 
					// }else{
						// $reqRelate['mul_content_id'] = $id;
					// }
					// $reqRelate['type'] = null;
					// $reqRelate['select'] = '';
					// $reqRelate['orderby'] = '';
					// $reqRelate['start'] = 0;
					// $reqRelate['end'] = 3;
					// $reqRelate["isUniverseContent"] = true;
					// $arrReturn[$k]['relate_content'] = $this->get_Learning_list($reqRelate);
			}
		}
		return $arrReturn;
	}
	private function _getFile_learning($mul_content_id){
		/*
		$sql = "select 
					 mul_source_id as file_id
					 ,mul_title as title
					 ,(case mul_type_id
					  when 'v' then 
					  if(mul_thumbnail_file != '' || mul_image_file != '',concat('http://www.trueplookpanya.com/new/cutresize/re/520/440/',replace(mul_destination,'/','-'),'/',if(mul_image_file!='',mul_image_file,mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(mul_destination,'/','-'),'/',if(mul_thumbnail_file!='',mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
					  else if(nullif(mul_source_id,'')!='' and nullif(mul_type_id,'')!='','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_download.png','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_text.png')
					  end
					 ) thumbnail
					 ,concat('http://www.trueplookpanya.com/data/product/media/',mul_destination,'/' ,mul_filename) as file_full_path
					 ,mul_destination as file_path
					 ,mul_filename as file_name
					 ,case mul_type_id 
						when 'v' then 'video'
						when 'a' then 'audio'
						else 'file'
					  end
					  as file_type
					 ,mul_filesize as file_size
					 ,'0' as vdo_duration
					 ,mul_source_update_datetime as addDateTime
					from mul_source 
					where mul_source_status!=5 and mul_content_id=".$mul_content_id." order by weight asc,mul_source_id asc";
		*/
		$sql = "select 
					 mul_source_id as file_id
					 ,mul_title as title
					 ,(case mul_type_id
					  when 'v' then 
					  if(mul_thumbnail_file != '' || mul_image_file != '',concat('http://www.trueplookpanya.com/new/cutresize/re/520/440/',replace(mul_destination,'/','-'),'/',if(mul_image_file!='',mul_image_file,mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(mul_destination,'/','-'),'/',if(mul_thumbnail_file!='',mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
					  else if(nullif(mul_source_id,'')!='' and nullif(mul_type_id,'')!='','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_download.png','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_text.png')
					  end
					 ) thumbnail
					 ,concat('http://static.trueplookpanya.com/trueplookpanya/media/',mul_destination,'/' ,mul_filename) as file_full_path2
					 ,concat('http://www.trueplookpanya.com/data/product/media/',mul_destination,'/' ,mul_filename) as file_full_path
					 ,mul_destination as file_path
					 ,mul_filename as file_name
					 ,case mul_type_id 
						when 'v' then 'video'
						when 'a' then 'audio'
						else 'file'
					  end
					  as file_type
					 ,mul_filesize as file_size
					 ,'0' as vdo_duration
					 ,mul_source_update_datetime as addDateTime
					from mul_source 
					where mul_source_status!=5 and mul_content_id=".$mul_content_id." order by weight asc,mul_source_id asc";
		
		$resp = $this->db->query($sql);
			if($resp){
				$arrReturn = $resp->result_array();
				foreach($arrReturn as $k => $v){
					if($v['file_type']=='video'){
						$filename = $v["file_name"];
						$file_full_paths=$v["file_full_path"];
						//$search='www.trueplookpanya.com/data/product/media/hash_cmsblog';
						$search='static.trueplookpanya.com/trueplookpanya/media/hash_cmsblog';
						$replace='static.trueplookpanya.com/hash_cmsblog';						
						$file_full_path=str_replace($search,$replace,$file_full_paths);	
						$arrReturn[$k]['file_full_path'] = $file_full_path;		
						$file_path=$v['file_path'];		
						$vdohash_cmsblog='http://static.trueplookpanya.com/'.$file_path.'/'.$filename;
						$arrReturn[$k]['url_vdo_play'] =$vdohash_cmsblog;
						if($file_full_path==$vdohash_cmsblog){
							$fullFileName=$file_full_path;
						$htmlFILE =' 
						<!-- START #player -->
						<link href="http://www.trueplookpanya.com/assets/video-js/video-js.css" rel="stylesheet" type="text/css" />  
						<script type="text/javascript" src="http://www.trueplookpanya.com/assets/video-js/video.js"></script>
                                        <strong>'. $v["file_title"] .'</strong>
                                        <div id="player_<?= $k ?>">
                                            <center>
                                                <video id="example_video_'. $v["file_id"] .'" class="video-js vjs-default-skin vjs-big-play-centered"
                                                       controls width="" height="350"
                                                       
                                                       data-setup=\'{ "controls": true, "autoplay": false, "preload": "auto" }\'>
                                                    <source src="'. $fullFileName .'" type="video/mp4" />                            
                                                    <p class="vjs-no-js">To view this<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                                                </video>
                                            </center>
                                        </div>
                                        <br>
                                        <!-- END #player -->
						';
						$arrReturn[$k]['HTML_tag'] = $htmlFILE;
						
						}else{
							$fullFileName = $this->trueplook->get_vdo_url($file_path, $v['file_name'],$hd=true,$type="learning");
						
				$htmlFILE =' 
						<!-- START #player -->
						<link href="http://www.trueplookpanya.com/assets/video-js/video-js.css" rel="stylesheet" type="text/css" />  
						<script type="text/javascript" src="http://www.trueplookpanya.com/assets/video-js/video.js"></script>
                                        <strong>'. $v["file_title"] .'</strong>
                                        <div id="player_<?= $k ?>">
                                            <center>
                                                <video id="example_video_'. $v["file_id"] .'" class="video-js vjs-default-skin vjs-big-play-centered"
                                                       controls width="" height="350"
                                                       poster="'. $v['thumbnail'] .'"
                                                       data-setup=\'{ "controls": true, "autoplay": false, "preload": "auto" }\'>
                                                    <source src="'. $fullFileName .'" type="video/mp4" />                            
                                                    <p class="vjs-no-js">To view this<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                                                </video>
                                            </center>
                                        </div>
                                        <br>
                                        <!-- END #player -->
						';
						$arrReturn[$k]['HTML_tag'] = $htmlFILE;
						
						}
						
						
						/*if(strpos($filename,".m4v")!==false || strpos($filename,".avi") || strpos($filename,".wmv")!==false){
							$filename=str_replace(".m4v",".mp4",$filename);
							$filename=str_replace(".avi",".mp4",$filename);
							$filename=str_replace(".wmv",".mp4",$filename);
							$fullFileName = "http://www.trueplookpanya.com/data/product/media/".$v["file_path"]."/".$filename;
						}
						
						// check file
						//$path_check = "/data/product/media/";
						$path_check_old = "/data/product/trueplookpanya/www/product/media/";
						$path_check_new = "/data/product/trueplookpanya/www/static/trueplookpanya/media/";
						$file_check_old = $path_check_old . $v["file_path"] . "/" . $v["file_name"];
						$file_check_new = $path_check_new . $v["file_path"] . "/" . $v["file_name"];
						//var_dump($file_check, file_exists($file_check));exit();
						if(file_exists($file_check_old)){
							// oold one
							//$fullFileName = $this->trueplook->get_vdo_url($v['file_path'], $v['file_name']);
							$fullFileName = "http://www.trueplookpanya.com/data/product/media/".$v["file_path"]."/".$filename;
						}elseif(file_exists($file_check)){
							// new one
							$fullFileName = "http://static.trueplookpanya.com/trueplookpanya/media/" . $v["file_path"] . "/" . $v["file_name"];
						}else{
							// no file exist
							
						}*/
							
							
						
						$arrReturn[$k]['vdo_url2play'] = $fullFileName;
						
						
					}elseif($v['file_type']=='audio'){
						$htmlFILE ='
						<style type="text/css" title="audiojs">.audiojs audio { position: absolute; left: -1px; }         .audiojs { width: 460px; height: 36px; background: #404040; overflow: hidden; font-family: monospace; font-size: 12px;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #444), color-stop(0.5, #555), color-stop(0.51, #444), color-stop(1, #444));           background-image: -moz-linear-gradient(center top, #444 0%, #555 50%, #444 51%, #444 100%);           -webkit-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); -moz-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3);           -o-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); }         .audiojs .play-pause { width: 25px; height: 40px; padding: 4px 6px; margin: 0px; float: left; overflow: hidden; border-right: 1px solid #000; }         .audiojs p { display: none; width: 25px; height: 40px; margin: 0px; cursor: pointer; }         .audiojs .play { display: block; }         .audiojs .scrubber { position: relative; float: left; width: 280px; background: #5a5a5a; height: 14px; margin: 10px; border-top: 1px solid #3f3f3f; border-left: 0px; border-bottom: 0px; overflow: hidden; }         .audiojs .progress { position: absolute; top: 0px; left: 0px; height: 14px; width: 0px; background: #ccc; z-index: 1;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ccc), color-stop(0.5, #ddd), color-stop(0.51, #ccc), color-stop(1, #ccc));           background-image: -moz-linear-gradient(center top, #ccc 0%, #ddd 50%, #ccc 51%, #ccc 100%); }         .audiojs .loaded { position: absolute; top: 0px; left: 0px; height: 14px; width: 0px; background: #000;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #222), color-stop(0.5, #333), color-stop(0.51, #222), color-stop(1, #222));           background-image: -moz-linear-gradient(center top, #222 0%, #333 50%, #222 51%, #222 100%); }         .audiojs .time { float: left; height: 36px; line-height: 36px; margin: 0px 0px 0px 6px; padding: 0px 6px 0px 12px; border-left: 1px solid #000; color: #ddd; text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.5); }         .audiojs .time em { padding: 0px 2px 0px 0px; color: #f9f9f9; font-style: normal; }         .audiojs .time strong { padding: 0px 0px 0px 2px; font-weight: normal; }         .audiojs .error-message { float: left; display: none; margin: 0px 10px; height: 36px; width: 400px; overflow: hidden; line-height: 36px; white-space: nowrap; color: #fff;           text-overflow: ellipsis; -o-text-overflow: ellipsis; -icab-text-overflow: ellipsis; -khtml-text-overflow: ellipsis; -moz-text-overflow: ellipsis; -webkit-text-overflow: ellipsis; }         .audiojs .error-message a { color: #eee; text-decoration: none; padding-bottom: 1px; border-bottom: 1px solid #999; white-space: wrap; }                 .audiojs .play { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -1px no-repeat; }         .audiojs .loading { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -31px no-repeat; }         .audiojs .error { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -61px no-repeat; }         .audiojs .pause { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -91px no-repeat; }                 .playing .play, .playing .loading, .playing .error { display: none; }         .playing .pause { display: block; }                 .loading .play, .loading .pause, .loading .error { display: none; }         .loading .loading { display: block; }                 .error .time, .error .play, .error .pause, .error .scrubber, .error .loading { display: none; }         .error .error { display: block; }         .error .play-pause p { cursor: auto; }         .error .error-message { display: block; }</style>
                                        <script src="http://www.trueplookpanya.com/new/assets/javascript/audiojs/audio.min.js"></script>   
                                        <script>
                                                        audiojs.events.ready(function () {
                                                            var as = audiojs.createAll();
                                                        });
                                        </script>
                                        <div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
                                            <center>
                                                <div style="width:auto; margin:30px auto" id="audio_area"><audio src="http://www.trueplookpanya.com/data/product/media/' . $v['file_path'] . '/' . $v['file_name'] .'" preload="auto" /></div>
                                            </center>
                                        </div>
						';
						$arrReturn[$k]['HTML_tag'] = $htmlFILE;
					}else{
						$htmlFILE ='
						<div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
                                            <div style="display: table;width:auto; border:1px solid #CCC; margin:20px auto; padding:10px">
                                                <div>
                                                    <div style="float:left"><a href="http://www.trueplookpanya.com/data/product/media/' . $v['file_path'] . '/' . $v['file_name'] .'" target="_blank" title="โหลดเอกสารเรื่อง '. $v['title'] .'" ><img src="http://www.trueplookpanya.com/new/assets/images/icon/icon_download.png" width="48" height="48" border="" alt=""  /></a></div>
                                                    <div style="float:left; margin-left:10px">
                                                        <div style="padding-left:10px"><a href="http://www.trueplookpanya.com/data/product/media/' . $v['file_path'] . '/' . $v['file_name'] .'" target="_blank" title="โหลดเอกสารเรื่อง '. $v['title'] .'" >เอกสารแนบ</a></div>
                                                        <div>
															<div style="float:left ; margin:5px 5px 0px 0px; "><img src="http://www.trueplookpanya.com/new/assets/images/icon/'. substr($v['file_name'], -3) .'.gif" border="0" alt="" height="30"  /></div>
															<div style="float:left; margin-top:5px">ขนาด : '. $this->trueplook->get_mb($v['file_size']) .' MB</div>
														</div>    
                                                    </div>
                                                </div>
                                            </div>
                       </div>
						';
						$arrReturn[$k]['HTML_tag'] = $htmlFILE;
					}
				}
				return $arrReturn;
			}else{
				return null;
			}
	}
	//-- NEW LEARNING end
	public function getDetail_content($content_id = null, $type = null){	//get detail of mul_content
		if(!isset($content_id) || !isset($type)){
			echo 'content_id & type are required';
			exit();
		}
		
		
		
		$DBSelect = $this->load->database('select', TRUE);	
		
		if($type == "mul_content")
		{
			$sql = "
					select AAA.* 
					,(select mul_level_name from mul_level where mul_level_id=AAA.mul_level_id) mul_level_name
					,(select TRUNCATE(AAA.scid,-3)) mul_category_id
					,(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE(AAA.scid,-3))) mul_category_name
					,AAA.scid sub_category_id 
					,(select mul_category_name from mul_category_2014 where mul_category_id=AAA.scid) sub_category_name
					from 
					(
						select
							'mul_content' type
							,mc.mul_content_id content_id
							,ms.mul_source_id content_child_id
							,COALESCE(NULLIF(mul_title,''), mul_content_subject) title
							,(case ms.mul_type_id
							  when 'v' then 
							  if(mul_thumbnail_file != '' || mul_image_file != '',concat('http://www.trueplookpanya.com/new/cutresize/re/520/440/',replace(mul_destination,'/','-'),'/',if(mul_image_file!='',mul_image_file,mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(mul_destination,'/','-'),'/',if(mul_thumbnail_file!='',mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
							  else if(nullif(mul_source_id,'')!='' and nullif(mul_type_id,'')!='','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_download.png','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_text.png')
							  end
							 ) thumbnail
							/*,(case ms.mul_type_id
								when 'v' then 
									if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/data/product/media/',ms.mul_destination,'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
								else if(nullif(ms.mul_source_id,'')!='' and nullif(ms.mul_type_id,'')!='','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_download.png','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_text.png')
							 end
							) thumbnail*/
							/*,concat('http://www.trueplookpanya.com/new/cms_detail/knowledge/',mc.mul_content_id,'-',ms.mul_source_id) url*/
							,CONCAT('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id,if(ms.mul_source_id is not null,concat( '/',ms.mul_source_id),'')) AS url
							,mc.add_date addDateTime
							,acc.user_username addBy
							,mc.view_count content_view_count
							,ms.view_count child_view_count
							,mc.mul_level_id lid
							,mc.mul_category_id cid
							,kmap.context_id context_id
                            ,(select knowledge_context_name from knowledge_context_2014 where knowledge_context_id=kmap.context_id) context_name
                            ,kc.mul_level_id mul_level_id
                            ,kc.mul_category_id scid
						from `mul_content` mc
                            left join `mul_source` ms on mc.mul_content_id=ms.mul_content_id and ms.mul_source_status != 5		
                            left join `knowledge_context_2014_map` kmap on if(NULLIF(ms.mul_source_id,'')='',kmap.table_id=1 and mc.mul_content_id=kmap.content_id,kmap.table_id=2 and ms.mul_source_id=kmap.content_id)
                            left join `knowledge_context_2014` kc on kmap.context_id=kc.knowledge_context_id
							left join `users_account` acc on mc.member_id = acc.member_id
						where mc.mul_content_status=1				
							and mc.mul_content_id=COALESCE(nullif(@content_id,'0'), mc.mul_content_id) limit 1
					) AAA";
						
		}
		
		$arrWhere = array(
					 '@content_id'=>$content_id
		);
		
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}
		
		$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
		$query = $DBSelect->query($sql, $arrWhere);
		$DBSelect->query("COMMIT");
		//print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		if($query)
			return $query->result_array();
		else
			return false;
	}
    public function getRelate_source($content_id = null, $limit = null, $offset = null, $orderby = null){	
    //same mu_content : get all source
		$DBSelect = $this->load->database('select', TRUE);
		
		if(!isset($content_id)){
			echo 'source_id is required';
			exit();
		}
		
		$criteria = "
					and mc.mul_content_id in (select mul_content_id from mul_source where mul_source_id= COALESCE(nullif(@content_id,'0'), mul_source_id))
					and ms.mul_source_id != COALESCE(nullif(@content_id,'0'), ms.mul_source_id)";
		
		
		if(!isset($orderby))
			$orderby = " order by title";
		
		$sql = "
					select AAA.* 
					,(select mul_level_name from mul_level where mul_level_id=AAA.mul_level_id) mul_level_name
					,(select TRUNCATE(AAA.scid,-3)) mul_category_id
					,(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE(AAA.scid,-3))) mul_category_name
					,AAA.scid sub_category_id 
					,(select mul_category_name from mul_category_2014 where mul_category_id=AAA.scid) sub_category_name
					from 
					(
						select
							'mul_source' type
							,mc.mul_content_id content_id
							,ms.mul_source_id content_child_id
							,COALESCE(NULLIF(mul_title,''), mul_content_subject) title
							,(case ms.mul_type_id
							  when 'v' then 
							  if(mul_thumbnail_file != '' || mul_image_file != '',concat('http://www.trueplookpanya.com/new/cutresize/re/520/440/',replace(mul_destination,'/','-'),'/',if(mul_image_file!='',mul_image_file,mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(mul_destination,'/','-'),'/',if(mul_thumbnail_file!='',mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
							  else if(nullif(mul_source_id,'')!='' and nullif(mul_type_id,'')!='','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_download.png','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_text.png')
							  end
							 ) thumbnail
							/*,(case ms.mul_type_id
								when 'v' then 
									if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/data/product/media/',ms.mul_destination,'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
								else 'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_download.png'
							 end
							) thumbnail*/
							/*,concat('http://www.trueplookpanya.com/new/cms_detail/knowledge/',mc.mul_content_id,'-',ms.mul_source_id) url*/
							,CONCAT('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id,if(ms.mul_source_id is not null,concat( '/',ms.mul_source_id),'')) AS url
							,mc.add_date addDateTime
							,acc.user_username addBy
							,mc.view_count content_view_count
							,ms.view_count child_view_count
							,mc.mul_level_id lid
							,mc.mul_category_id cid
							,kmap.context_id context_id
                            ,(select knowledge_context_name from knowledge_context_2014 where knowledge_context_id=kmap.context_id) context_name
                            ,kc.mul_level_id mul_level_id
                            ,kc.mul_category_id scid
						from `mul_content` mc
							inner join `mul_source` ms on mc.mul_content_id=ms.mul_content_id and ms.mul_source_status != 5
							left join `knowledge_context_2014_map` kmap on kmap.table_id=1 and mc.mul_content_id=kmap.content_id
                            left join `knowledge_context_2014` kc on kmap.context_id=kc.knowledge_context_id
							left join `users_account` acc on mc.member_id = acc.member_id
						where mc.mul_content_status=1				
							".$criteria."
					) AAA ".$orderby;
						
		
		$arrWhere = array(
					 '@content_id'=>$content_id
		);
		
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}
		
		// $query = $DBSelect->query($sql, $arrWhere);
		// //print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		// if($query)
			// return $query->result_array();
		// else
			// return false;
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$query = $DBSelect->query($sql, $arrWhere);
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult, 60);
		}
		
		return $arrResult;
	}
	public function getRelate_content($content_id = null, $limit = null, $offset = null, $order = null,$subject_id = null, $level_id = null, $context_id = null, $arrFilter = array()) 
	{	//different content : get source
		$DBSelect = $this->load->database('select', TRUE);
		
		// criteria 
		//$criteria = "  and kmap.context_id is not null";
		$criteria = "  ";
		if($content_id>0){
			$criteria .= " and mc.mul_content_id !=".$content_id."
								and (
								mc.mul_content_id in (select content_id from knowledge_context_2014_map where context_id in (select context_id from knowledge_context_2014_map where table_id=1 and context_id=kmap.context_id))
								or
								ms.mul_source_id in (select content_id from knowledge_context_2014_map where context_id in (select context_id from knowledge_context_2014_map where table_id=2 and context_id=kmap.context_id))
								)
						";
		}

		
		if(!is_null($level_id)){
			$criteria .= " and (
								mc.mul_content_id in (select content_id from knowledge_context_2014_map where table_id=1 and context_id in (select knowledge_context_id from knowledge_context_2014 where mul_level_id=".$level_id."))
								or
								ms.mul_source_id in (select content_id from knowledge_context_2014_map where table_id=2 and context_id in (select knowledge_context_id from knowledge_context_2014 where mul_level_id=".$level_id."))
								)
								";
		}			
		if(!is_null($subject_id)){
			$criteria .= " and (
								mc.mul_content_id in (select content_id from knowledge_context_2014_map where table_id=1 and context_id in (select knowledge_context_id from knowledge_context_2014 where (mul_category_id=".$subject_id." or (select TRUNCATE(mul_category_id,-3))=".$subject_id.")))
								or
								ms.mul_source_id in (select content_id from knowledge_context_2014_map where table_id=2 and context_id in (select knowledge_context_id from knowledge_context_2014 where (mul_category_id=".$subject_id." or (select TRUNCATE(mul_category_id,-3))=".$subject_id.")))
								)
								";
		}
		if(!is_null($context_id)){
			$criteria .= "  and (
								mc.mul_content_id in (select content_id from knowledge_context_2014_map where table_id=1 and context_id=".$context_id.")
								or
								ms.mul_source_id in (select content_id from knowledge_context_2014_map where table_id=2 and context_id=".$context_id.")
								)
						";
		}
		
		
		$join_kmap = " left join `knowledge_context_2014_map` kmap on kmap.table_id=1 and mc.mul_content_id=kmap.content_id";
		if($arrFilter['contentType']=="sourceOnly"){
			$join_kmap = " left join `knowledge_context_2014_map` kmap on kmap.table_id=2 and ms.mul_source_id=kmap.content_id";
			$criteria .= " and NULLIF(ms.mul_source_id,'')!='' ";
		}
		
		if($arrFilter['moreCriteria'] && $arrFilter['moreCriteria']!=""){
			$criteria .= $arrFilter['moreCriteria'];
		}
		
		// order by
		$order = trim(strtolower($order));
		// if($order=="view"){	// order by max view << [IGNORE for SLOW QUERY]
			// $criticalCriteria = " order by viewcount desc limit 200";
			// $orderby = "";
		// }
		if($order=='last'){
			$criticalCriteria = " order by mc.mul_content_id desc,ms.mul_content_id desc,ms.mul_source_id desc limit 200";
			$orderby = "";
		}
		else{	// order by random
			$criticalCriteria = " order by mc.mul_content_id desc,ms.mul_content_id desc,ms.mul_source_id desc limit 200";
			$orderby = " order by rand()";
		}
		
		if(!isset($orderby)){
			$orderby = " order by content_id desc";//" order by rand()";
		}		
		
		$criticalCriteria = "";
		
		$sql = "
					select AAA.* 
					,(select mul_level_name from mul_level where mul_level_id=AAA.mul_level_id) mul_level_name
					,(select TRUNCATE(AAA.scid,-3)) mul_category_id
					,(select mul_category_name from mul_category_2014 where mul_category_id=(select TRUNCATE(AAA.scid,-3))) mul_category_name
					,AAA.scid sub_category_id 
					,(select mul_category_name from mul_category_2014 where mul_category_id=AAA.scid) sub_category_name
					from 
					(
						select
							if(ms.mul_source_id is not null,'mul_source','mul_content') type
							,mc.mul_content_id content_id
							,ms.mul_source_id content_child_id
							,COALESCE(NULLIF(mul_title,''), mul_content_subject) title
							,(case ms.mul_type_id
							  when 'v' then 
							  if(mul_thumbnail_file != '' || mul_image_file != '',concat('http://www.trueplookpanya.com/new/cutresize/re/520/440/',replace(mul_destination,'/','-'),'/',if(mul_image_file!='',mul_image_file,mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(mul_destination,'/','-'),'/',if(mul_thumbnail_file!='',mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
							  else if(nullif(mul_source_id,'')!='' and nullif(mul_type_id,'')!='','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_download.png','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_text.png')
							  end
							 ) thumbnail
							/*,(case ms.mul_type_id
								when 'v' then 
									if(ms.mul_thumbnail_file != '' || ms.mul_image_file != '',concat('http://www.trueplookpanya.com/data/product/media/',ms.mul_destination,'/',if(ms.mul_image_file!='',ms.mul_image_file,ms.mul_thumbnail_file)),concat('http://www.trueplookpanya.com/new/cutresize/re/320/240/',replace(ms.mul_destination,'/','-'),'/',if(ms.mul_thumbnail_file!='',ms.mul_thumbnail_file,replace(mul_filename,right(mul_filename,4),'_320x240.png'))))
								else if(nullif(ms.mul_source_id,'')!='' and nullif(ms.mul_type_id,'')!='','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_download.png','http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_text.png')
							 end
							) thumbnail*/
							/*,concat('http://www.trueplookpanya.com/new/cms_detail/knowledge/',mc.mul_content_id,'-',ms.mul_source_id) url*/
							,CONCAT('http://www.trueplookpanya.com/knowledge/detail/', mc.mul_content_id,if(ms.mul_source_id is not null,concat( '/',ms.mul_source_id),'')) AS url
							,mc.add_date addDateTime
							,acc.user_username addBy
							,mc.view_count content_view_count
							,ms.view_count child_view_count
							,mc.mul_level_id lid
							,mc.mul_category_id cid
							,kmap.context_id context_id
                            ,(select knowledge_context_name from knowledge_context_2014 where knowledge_context_id=kmap.context_id) context_name
                            ,kc.mul_level_id mul_level_id
                            ,kc.mul_category_id scid
						from `mul_content` mc
							left join `mul_source` ms on mc.mul_content_id=ms.mul_content_id and ms.mul_source_status != 5
							".$join_kmap."
							/*LEFT JOIN `knowledge_context_2014_map` kmap ON  IF(ms.mul_source_id IS NOT NULL,kmap.table_id=2 and ms.mul_source_id is not null and ms.mul_source_id=kmap.content_id,kmap.table_id = 1  AND mc.mul_content_id = kmap.content_id)*/
                            left join `knowledge_context_2014` kc on kmap.context_id=kc.knowledge_context_id
							left join `users_account` acc on mc.member_id = acc.member_id
						where mc.mul_content_status=1				
						".$criteria.$criticalCriteria." 
					) AAA ".$orderby;
					
		
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}
		
		// $query = $DBSelect->query($sql, $arrWhere);
		// //print_r($DBSelect->queries); echo '<br><br>----<br>'; exit();
		// if($query)
			// return $query->result_array();
		// else
			// return false;
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql, $arrWhere);
			$DBSelect->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult, 60);
		}
		
		return $arrResult;
	}
	public function getRelate_cms($content_id = null, $limit = null, $offset = null, $order = null, $catid = null, $textSearch = null, $arrFilter = array()) 
	{	//same mul_category , mul_level 
		$DBSelect = $this->load->database('select', TRUE);
		
		$criteria = "";
		if($content_id>0){
			$criteria .= "and cms.cms_id != COALESCE(nullif(@content_id,'0'), cms.cms_id)
					 and cms.cms_category_id = (select cms_category_id from cms where cms_id=COALESCE(nullif(@content_id,'0'), cms_id))
					 ";
		}
		$criteria  .= " and cms.cms_category_id=COALESCE(nullif(@catid,'0'), cms.cms_category_id)";
		
		if($textSearch!=null){
			$criteria .= " and (cms.cms_subject='".$textSearch."' or cms.cms_subject like '%".$textSearch."%')";
		}
		
		if($arrFilter['groupIDFilter'] && $arrFilter['groupIDFilter']!=""){
			$criteria .= " and cms.cms_id in (".$arrFilter['groupIDFilter'].")";
		}
		
		if($arrFilter['moreCriteria'] && $arrFilter['moreCriteria']!=""){
			$criteria .= $arrFilter['moreCriteria'];
		}
		
		$arrWhere = array(
				 '@content_id'=>$content_id
				 ,'@catid'=>$catid
		);
		
		if($order=='last'){
			$criticalCriteria = " order by cms.cms_id desc limit 200";
			$orderby = "";
		}
		elseif($order=='still'){
			$criticalCriteria = "";
			$orderby = " ";
		}
		else{	// order by random
			$criticalCriteria = " order by cms.cms_id desc limit 200";
			$orderby = " order by rand()";
		}
		
		if(!isset($orderby)){
			$orderby = " order by content_id desc";//" order by rand()";
		}
		
		
		$sql = "select AA.*
					/*,(select max(v.mul_view_value) from mul_sum_view v where v.mul_view_table=21 and v.mul_content_id=AA.content_id limit 1) viewcount*/
					from (select
						'cms' type
						,cms.cms_id content_id
						,cf.cms_file_id content_child_id
						,cms.cms_subject title
						/*,concat('http://www.trueplookpanya.com/data/product/media/',
                         if(image_filename_l!='',concat(thumb_path,'/',image_filename_l),
                          if(image_filename_m!='',concat(thumb_path,'/',image_filename_m),
                           if(image_filename_s!='',concat(thumb_path,'/',image_filename_s),
                            replace(file_name,right(file_name,4),'_128x96.png')
                          )))
                        ) thumbnail*/
						,if(NULLIF(cms.image_filename_thumb,'')!=''
							,concat(cms.thumb_url,'/',image_filename_thumb)
							,concat('http://www.trueplookpanya.com/data/product/media/',
									 if(image_filename_l!='',concat(thumb_path,'/',image_filename_l),
									  if(image_filename_m!='',concat(thumb_path,'/',image_filename_m),
									   if(image_filename_s!='',concat(thumb_path,'/',image_filename_s),
										replace(file_name,right(file_name,4),'_128x96.png')
									  )))
									)
							) thumbnail
						,concat(
						  case cc.cms_parent_category_id
							when 2 then 'http://www.trueplookpanya.com/new/cms_detail/teacher/'
							when 3 then 'http://www.trueplookpanya.com/new/cms_detail/entertainment/'
                            when 6 then 'http://www.trueplookpanya.com/new/cms_detail/entertainment/'
							when 9 then 'http://www.trueplookpanya.com/new/cms_detail/guidance/'
							when 10 then 'http://www.trueplookpanya.com/new/cms_detail/news/'
							when 13 then 'http://www.trueplookpanya.com/new/cms_detail/news/'
							when 64 then 'http://www.trueplookpanya.com/new/cms_detail/entertainment/'
							when 135 then 'http://www.trueplookpanya.com/new/cms_detail/guidance/'
							when 142 then 'http://www.trueplookpanya.com/trueplookpanyablog/'
							when 140 then 'http://www.trueplookpanya.com/new/cms_detail/news/'
                            else 'http://www.trueplookpanya.com/new/cms_detail/general_knowledge/'
						   end
                         ,cms.cms_id) url
						
						,cms.add_date addDateTime
						,acc.user_username addBy
						,'' mul_level_id
						,'' mul_level_name
						,cms.cms_category_id mul_category_id
						,cc.cms_category_name mul_category_name
						,'' context_id
						,'' context_name
						,cms.cms_detail_short short_detail
						,view_count as viewcount
					from `cms`
					 left join `cms_file` cf on cms.cms_id = cf.cms_id and cf.status_delete='N'
					 left join `users_account` acc on cms.member_id = acc.member_id
					 left join `cms_category` cc on cms.cms_category_id=cc.cms_category_id
					where record_status=1
					".$criteria.$criticalCriteria." ) AA ".$orderby; 
		
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}
		
		$query = $DBSelect->query($sql, $arrWhere);
		//print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		if($query)
			return $query->result_array();
		else
			return false;
	}
	public function getRelate_admissionNews($content_id = null, $limit = null, $offset = null, $order = null, $facultyid = "", $textSearch = null, $arrFilter = array()) 
	{	//same mul_category , mul_level 
		$DBSelect = $this->load->database('select', TRUE);
		
		$criteria = "";
		if($content_id>0){
			// $criteria .= "and cms.cms_id != COALESCE(nullif(@content_id,'0'), cms.cms_id) ";
			$criteria .= "and content_id != COALESCE(nullif(@content_id,'0'), content_id) ";
		}
		
		if($facultyid!=""){
			// $criteria .= " and cms.cms_id in (select content_id from cvs_admissions_article where id in (select  admissions_article_id from cvs_admissions_article_faculty where faculty_id in (".$facultyid.")))";
			$criteria .= " and content_child_id in (select  admissions_article_id from cvs_admissions_article_faculty where faculty_id in (".$facultyid."))";
		}
		// if($arrFilter['groupFacultyFilter'] && $arrFilter['groupFacultyFilter']!=""){
			// $criteria .= " and am.id in (select admissions_article_id from cvs_admissions_article_faculty where faculty_id in (".$arrFilter['groupFacultyFilter']."))";
		// }
		
		if($textSearch!=null){
			//$criteria .= " and (cms.cms_subject='".$textSearch."' or cms.cms_subject like '%".$textSearch."%')";
			$criteria .= " and (title='".$textSearch."' or title like '%".$textSearch."%')";
		}
		
		if($arrFilter['groupIDFilter'] && $arrFilter['groupIDFilter']!=""){
			$criteria .= " and content_id in (".$arrFilter['groupIDFilter'].")";
		}
		
		if($arrFilter['moreCriteria'] && $arrFilter['moreCriteria']!=""){
			$criteria .= $arrFilter['moreCriteria'];
		}
		
		$arrWhere = array(
				 '@content_id'=>$content_id
		);
		
		if($order=='last'){
			// $criticalCriteria = " order by cms.cms_id desc limit 200";
			$criticalCriteria = " order by am.id desc limit 200";
			$orderby = "";
		}
		elseif($order=='still'){
			$criticalCriteria = "";
			$orderby = " ";
		}
		elseif($order=='sort'){
			$criticalCriteria = " order by am.sort desc, am.cdate desc";
			$orderby = " ";
		}
		else{	// order by random
			$criticalCriteria = " order by am.sort desc limit 200";
			$orderby = " order by rand()";
		}
		
		if(!isset($orderby)){
			$orderby = " order by content_id desc";//" order by rand()";
		}
		
		
		
		$strFieldContentID = "am.content_id";
		if($arrFilter['list4App']){
			$strFieldContentID = "case am.table_name
											 when 'tv_program_episode' then (am.content_id+1000000)
											 else am.content_id 
											 end ";
		}
		
		/*
		$sql = "select AA.*
					from (select
						'cms' type
						,cms.cms_id content_id
						,NULL as content_child_id
						,cms.cms_subject title
						,concat('http://www.trueplookpanya.com/data/product/media/',
                         if(image_filename_l!='',concat(thumb_path,'/',image_filename_l),
                          if(image_filename_m!='',concat(thumb_path,'/',image_filename_m),
                           if(image_filename_s!='',concat(thumb_path,'/',image_filename_s),
                            (select 
							  replace(file_name,right(file_name,4),'_128x96.png')
							from cms_file cf where cms.cms_id = cf.cms_id and cf.status_delete='N' limit 1
							)
                          )))
                        ) thumbnail
						,concat('http://www.trueplookpanya.com/admissions/article/detail/',cms.cms_id) url
						,cms.add_date addDateTime
						,acc.psn_display_name addBy
						,'' mul_level_id
						,'' mul_level_name
						,cms.cms_category_id mul_category_id
						,cc.cms_category_name mul_category_name
						,'' context_id
						,'' context_name
						,cms.cms_detail_short short_detail
						,view_count as viewcount
					from `cms`
					 inner join cvs_admissions_article am on am.table_name='cms' and status=1 and cms.cms_id=am.content_id
					 left join `users_account` acc on cms.member_id = acc.member_id
					 left join `cms_category` cc on cms.cms_category_id=cc.cms_category_id
					where record_status=1
					".$criteria.$criticalCriteria." ) AA ".$orderby; */
		
		/*  App ยังไม่พร้อม เพราะเค้าเรียก getCMSDetail ทำให้มันวิ่งไปดูที่ table cms อย่างเดียว ต้องไป mo ตัวนี้ก่อนด้วย */
		$sql = "
select AA.* from (
select
	am.table_name type
	,".$strFieldContentID." as content_id
	,am.id as content_child_id
	,case am.table_name 
     when 'cms' then cms.cms_subject 
     when 'cmsblog_detail' then cd.title
     when 'tv_program_episode' then tve.tv_episode_name
     end as title
	,case am.table_name 
     when 'cms' then 
		concat('http://www.trueplookpanya.com/data/product/media/',
		 if(cms.image_filename_l!='',concat(cms.thumb_path,'/',cms.image_filename_l),
		  if(cms.image_filename_m!='',concat(cms.thumb_path,'/',cms.image_filename_m),
		   if(cms.image_filename_s!='',concat(cms.thumb_path,'/',cms.image_filename_s),
			(select 
			  replace(cf.file_name,right(cf.file_name,4),'_128x96.png')
			from cms_file cf where cms.cms_id = cf.cms_id and cf.status_delete='N' limit 1
			)
		  )))
		)
	 when 'cmsblog_detail' then concat('http://static.trueplookpanya.com/',cd.thumb_path)
     when 'tv_program_episode' then 
		COALESCE(
		  case (select tvv.upload_type from tv_program_episode_vdo tvv where tvv.tv_episode_id=tve.tv_episode_id and tvv.record_status=1)
			when 'Youtube' then
				(select 
                concat('http://i3.ytimg.com/vi/',tvv.youtube_code,'/maxresdefault.jpg')
                from tv_program_episode_vdo tvv where tvv.tv_episode_id=tve.tv_episode_id and tvv.record_status=1)
			else
			  if(tve.tv_episode_thumbnail!=''
			  ,concat('http://www.trueplookpanya.com/new/cutresize/re/110/74/',replace(tv_episode_path,'/','-'),'/',tv_episode_thumbnail)
			  ,(select concat('http://www.trueplookpanya.com/new/cutresize/re/110/74/',replace(tv_path,'/','-'),'/',tv_thumbnail)
				from tv_program where tv_id=tve.tv_id)
              )
		  end
		,'http://www.trueplookpanya.com/new/cutresize/re/500/370/images/trueplook.png/TV/')
     end as thumbnail
	,am.url url
	,case am.table_name 
     when 'cms' then cms.add_date 
     when 'cmsblog_detail' then cd.create_date
     when 'tv_program_episode' then tve.add_date
     end as addDateTime
    ,case am.table_name 
     when 'cms' then (select psn_display_name from users_account where member_id=cms.member_id)
     when 'cmsblog_detail' then (select psn_display_name from users_account where user_id=cd.create_user_id)
     when 'tv_program_episode' then (select psn_display_name from users_account where member_id=tve.update_by)
     end as addBy
    ,case am.table_name 
     when 'cms' then (select user_id from users_account where member_id=cms.member_id)
     when 'cmsblog_detail' then (select user_id from users_account where user_id=cd.create_user_id)
     when 'tv_program_episode' then (select user_id from users_account where member_id=tve.update_by)
     end as addBy_UserId
	,'' mul_level_id
	,'' mul_level_name
	,'' mul_category_id
	,'' mul_category_name
	,'' context_id
	,'' context_name
	,case am.table_name 
     when 'cms' then cms.cms_detail_short
     when 'cmsblog_detail' then cd.description_short
     when 'tv_program_episode' then 'รายการดีๆจากช่องทรูปลูกปัญญา'
     end as short_detail
	,case am.table_name 
     when 'cms' then cms.view_count
     when 'cmsblog_detail' then cd.view_count
     when 'tv_program_episode' then (select sum(content_view_val) as viewcount from media_sum_view where content_view_id=1651 and content_view_table='tv_program_episode')
     end as viewcount
from cvs_admissions_article am
 left join cms on am.content_id=cms.cms_id and cms.record_status=1 and am.table_name='cms'
 left join cmsblog_detail cd on am.content_id=cd.idx and am.table_name='cmsblog_detail'
 left join tv_program_episode tve on am.content_id=tve.tv_episode_id and am.table_name='tv_program_episode'
where am.status=1 ".$criticalCriteria."
 ) AA where (title is not null and thumbnail is not null) 	
		".$criteria.$orderby; 
		
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}
		// echo $sql;
		// $query = $DBSelect->query($sql, $arrWhere);
		// //print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		// if($query)
			// return $query->result_array();
		// else
			// return false;
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql, $arrWhere);
			$DBSelect->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult, 300);
		}
		
		return $arrResult;
	}
	public function getRelate_exam($content_id = null, $limit  = null, $offset = null, $order = null,$subject_id = null, $level_id = null, $context_id = null) 
	{	//same mul_category , mul_level 
		$DBSelect = $this->load->database('select', TRUE);

		// criteria 
		$criteria = "";
		
		// $criteria .="
					// and (mul_category_id = COALESCE(nullif(@subject_id,'0'), mul_category_id) )
					// and knowledge_context_id = COALESCE(nullif(@context_id,'0'), knowledge_context_id)
					// and (ex.mul_level_id = COALESCE(nullif(@level_id,'0'), ex.mul_level_id) )";
		$arrwhere = array(
								 '@content_id'=>$content_id
								, '@level_id'=>$level_id								 
								, '@subject_id'=>$subject_id
								, '@context_id'=>$context_id
								);
		
		if($content_id>0){
			$criteria .= " and ex.id != ".$content_id."
						and ex.mul_level_id = (select mul_level_id from `cvs_course_examination` where id=".$content_id."
						and ex.mul_root_id = (select mul_root_id from `cvs_course_examination` where id=".$content_id."
						";
		}
		if(!is_null($subject_id)){
			$criteria .= " and 
			(
			ex.id in (select exam_id from cvs_course_examination_category where (mul_category_id = ".$subject_id." or (select TRUNCATE(mul_category_id,-3))=".$subject_id."))
			or
			ex.mul_root_id=".$subject_id."
			)";
		}
		if(!is_null($context_id)){
			// [P] wait until mapping exam vs context
			//$criteria .=" and ex.id in (select exam_id from cvs_course_examination_knowledge_context where knowledge_context_id = ".$context_id.") ";
		}
		if(!is_null($level_id)){
			$criteria .=" and (ex.mul_level_id=".$level_id."  or (select TRUNCATE(ex.mul_level_id,-1))=".$level_id.")";
		}
		
		// order by
		$order = trim(strtolower($order));
		// if($order=="view"){	// order by max view << [IGNORE for SLOW QUERY]
			// $criticalCriteria = " order by viewcount desc limit 200";
			// $orderby = "";
		// }
		if($order=='last'){	// order by last
			$criticalCriteria = " order by ex.id desc limit 200";
			$orderby = " ";
		}
		else{	// order by random
			$criticalCriteria = " order by ex.id desc limit 200";
			$orderby = " order by rand()";
		}
		
		
		$sql = "select AA.* 
					/*,(select max(v.view) from cvs_stat_all v where v.category_id=2 and v.content_id=AA.content_id limit 1) viewcount*/
						,0 as viewcount
					from (
						select
						'exam' type
						,ex.id content_id
						,'' content_child_id
						,ex.exam_name title
						,concat('http://static.trueplookpanya.com/trueplookpanya/media/home/icon_subject/',ex.mul_root_id,'.png') thumbnail
						,concat('http://www.trueplookpanya.com/examination/doexam/',ex.id) url
						,ex.exam_add_date addDateTime
						,acc.user_username addBy
						,ex.mul_level_id mul_level_id
						,lev.mul_level_name mul_level_name
						/*,(select mul_parent_id from mul_category_ where mul_category_id = (select mul_category_id from cvs_course_examination_category where exam_id=ex.id limit 1) limit 1) mul_category_id
						,(select mul_category_name from mul_category where mul_parent_id = (select mul_parent_id from mul_category where mul_category_id = (select mul_category_id from cvs_course_examination_category where exam_id=ex.id limit 1) limit 1) limit 1) mul_category_name*/
						,ex.mul_root_id mul_category_id
						,(select mul_category_name from mul_category_2014 where mul_category_id=ex.mul_root_id) mul_category_name
						,(select knowledge_context_id from cvs_course_examination_knowledge_context where exam_id=ex.id  limit 1 ) context_id
						,(select knowledge_context_name from knowledge_context where knowledge_context_id = (select knowledge_context_id from cvs_course_examination_knowledge_context where exam_id=ex.id and knowledge_context_id = COALESCE(nullif(@context_id,'0'), knowledge_context_id)  limit 1 )) context_name
					from `cvs_course_examination` ex
					 left join `users_account` acc on ex.member_id = acc.member_id
					 left join `mul_level` lev on ex.mul_level_id=lev.mul_level_id
					where ex.exam_status='yes' 
					".$criteria.$criticalCriteria." ) AA ".$orderby; 
		
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}
		
		// $query = $DBSelect->query($sql, $arrWhere);
		// //print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		// if($query)
			// return $query->result_array();
		// else
			// return false;
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql, $arrWhere);
			$DBSelect->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
				foreach($arrResult as $k=>$v){
					$sql="select max(v.view) cnt from cvs_stat_all v where v.category_id=2 and v.content_id=".$v["content_id"]." limit 1";
					$arrResult[$k]["viewcount"] = $DBSelect->query($sql)->row()->cnt;
				}
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult, 60);
		}
		
		return $arrResult;
	}
	
	public function getRelate_tvprogram($content_id = null, $limit = null, $offset = null, $order = null, $menuLevel1 = null , $menuLevel2 = null , $textSearch = null, $arrFilter = array()) 
	{	//same mul_category , mul_level 
		$DBSelect = $this->load->database('select', TRUE);
		$criteria = "";
		
		if($content_id>0){
			$criteria .= "and tv.tv_id != COALESCE(nullif(@content_id,'0'), tv.tv_id)
						and 
						tv.menu_level2_id = (select menu_level2_id from tv_program where tv_id=COALESCE(nullif(@content_id,'0'), tv_id))";
			$arrWhere = array(
						 '@content_id'=>$content_id
						);
		}
		
		if(!is_null($menuLevel1)){
			$criteria .= " and tv.menu_level1_id=".$menuLevel1;
		}
		if(!is_null($menuLevel2)){
			$criteria .=" and tv.menu_level2_id=".$menuLevel2;
		}
		if(!is_null($textSearch)){
			$criteria .= " and (tv.tv_name like '%".$textSearch."%' or tv.tv_name='".$textSearch."')";
		}
		if($arrFilter['groupIDFilter'] && $arrFilter['groupIDFilter']!=""){
			$criteria .= " and tv.tv_id in (".$arrFilter['groupIDFilter'].")";
		}
		
		// order by
		$order = trim(strtolower($order));
		if($order=='last'){	// order by last
			$criticalCriteria = " order by tv.tv_id desc";
			$orderby = " ";
		}
		elseif($order=='name'){
			$criticalCriteria = " order by tv.tv_name asc";
			$orderby = " ";
		}
		else{	// order by random
			$criticalCriteria = " order by tv.tv_id desc limit 1000";
			$orderby = " order by rand()";
		}
		
		if(!isset($orderby))
			$orderby = " order by rand()";
		
		$sql = "select AA.*
					/*,(select max(v.content_view_val) from media_sum_view v where v.content_view_table='tv_program' and v.content_view_id=AA.content_id limit 1) viewcount*/
					from (select
						'tv_program' type
						,tv.tv_id content_id
						,'' content_child_id
						,tv.tv_name title
						/*,COALESCE(concat('http://www.trueplookpanya.com/new/cutresize/re/110/74/',replace(tv_path,'/','-'),'/',tv_thumbnail),'http://www.trueplookpanya.com/new/cutresize/re/500/370/images/trueplook.png/TV/') thumbnail*/
						,COALESCE(concat('http://www.trueplookpanya.com/new/cutresize/re/718/400/',replace(tv_path,'/','-'),'/',tv_thumbnail),'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_vdo.png/') thumbnail
						,concat('http://www.trueplookpanya.com/new/tv_program_detail/',tv.tv_id) url
						,tv.add_date addDateTime
						,acc.user_username addBy
						,'' mul_level_id
						,'' mul_level_name
						,concat(cat1.menu_id,':',cat2.menu_id) mul_category_id
						,concat(cat1.menu_name,' : ',cat2.menu_name) mul_category_name
						,'' context_id
						,'' context_name
						,cat1.menu_id tv_menu_level1_id
						,cat1.menu_name tv_menu_level1_name
						,cat2.menu_id tv_menu_level2_id
						,cat2.menu_name tv_menu_level2_name
					from `tv_program` tv
					 left join `users_account` acc on tv.update_by = acc.member_id
					 left join `tv_category` cat1 on tv.menu_level1_id=cat1.menu_id and cat1.menu_type='level1' and cat1.record_status=1
					 left join `tv_category` cat2 on tv.menu_level2_id=cat2.menu_id and cat2.menu_type='level2' and cat2.record_status=1
					where tv.record_status=1 
					".$criteria.$criticalCriteria." ) AA ".$orderby; 
		
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}
		
		// $query = $DBSelect->query($sql, $arrWhere);
		// //print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		// if($query)
			// return $query->result_array();
		// else
			// return false;
		
		$cache_key = $sql;
		// $this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			// $DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql, $arrWhere);
			// $DBSelect->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
				if($arrResult){
					foreach($arrResult as $k => $v){
						$tvid = $v['content_id'];
						// Get Thumbnail
						/*$thumb = "";
						$this->load->model('mobile/tv_model');
						$result_gallery=$this->tv_model->get_tv_gallery($tvid);
						if($result_gallery){
							foreach ($result_gallery as $k => $v) {
								$path_img = $this->trueplook->image_resize(718, 400, $v['gallery_path'], $v['gallery_img_name']);
								// echo '-1='.$path_img;
								$arrResult[$k]['thumbnail'] = $path_img;
								break;
							}
						}*/
						
						// Get View
						$arrResult[$k]['viewcount'] = intval(str_replace(",","",$this->trueplook->getViewCenter($tvid,'tv_program','media')));
					}
				}
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult, 60);
		}
		
		return $arrResult;
	}
	
	public function getRelate_tvprogram_episode($content_id = null, $limit = null, $offset = null, $order = null, $menuLevel1 = null , $menuLevel2 = null, $arrFilter = array()) 
	{	//same mul_category , mul_level 
		$DBSelect = $this->load->database('select', TRUE);
		$criteria = "";
		
		if(!isset($content_id)){
			echo 'episode_id is required';
			exit();
		}
		
		$criteria .= "and tve.tv_episode_id != COALESCE(nullif(@content_id,'0'), tve.tv_episode_id)
				 and tve.tv_id = (select tv_id from tv_program_episode where tv_episode_id=COALESCE(nullif(@content_id,'0'), tv_episode_id))";
		$arrWhere = array(
					 '@content_id'=>$content_id
		);

		
		if(!is_null($menuLevel1)){
			$criteria .= " and tv.menu_level1_id=".$menuLevel1;
		}
		if(!is_null($menuLevel2)){
			$criteria .=" and tv.menu_level2_id=".$menuLevel2;
		}
		
		if($arrFilter['groupIDFilter'] && $arrFilter['groupIDFilter']!=""){
			$criteria .= " and tv.tv_id in (".$arrFilter['groupIDFilter'].")";
		}
		
		// order by
		$order = trim(strtolower($order));
		if($order=='last'){	// order by last
			$criticalCriteria = " order by tve.tv_episode_id desc limit 200";
			$orderby = " ";
		}
		elseif($order=='name'){
			$criticalCriteria = " order by tv.tv_name asc,tve.tv_episode_name asc limit 200";
			$orderby = " ";
		}
		else{	// order by random
			$criticalCriteria = " order by tve.tv_episode_id limit 200";
			$orderby = " order by rand()";
		}
		
		
		$sql = "select AA.*
					/*,(select max(v.content_view_val) from media_sum_view v where v.content_view_table='tv_program_episode' and v.content_view_id=AA.content_id limit 1) viewcount*/
					from (select
						'tv_program_episode' type
						,tv.tv_id content_id
						,tve.tv_episode_id content_child_id
						,concat(tv.tv_name,' : ',tve.tv_episode_name) title
						,COALESCE(
                          case tvv.upload_type
							when 'Youtube' then
								/*concat('http://i3.ytimg.com/vi/',tvv.youtube_code,'/mqdefault.jpg')*/
								concat('http://i3.ytimg.com/vi/',tvv.youtube_code,'/maxresdefault.jpg')
                            else
							  if(tve.tv_episode_thumbnail!=''
							  ,concat('http://www.trueplookpanya.com/new/cutresize/re/110/74/',replace(tv_episode_path,'/','-'),'/',tv_episode_thumbnail)
							  ,concat('http://www.trueplookpanya.com/new/cutresize/re/110/74/',replace(tv_path,'/','-'),'/',tv_thumbnail))
						  end
                        ,'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/default_vdo.png') thumbnail
						,concat('http://www.trueplookpanya.com/new/tv_program_detail/',tv.tv_id,'/',tve.tv_episode_id) url
						,tv.add_date addDateTime
						,acc.user_username addBy
						,'' mul_level_id
						,'' mul_level_name
						,concat(cat1.menu_id,':',cat2.menu_id) mul_category_id
						,concat(cat1.menu_name,' : ',cat2.menu_name) mul_category_name
						,'' context_id
						,'' context_name
						,cat1.menu_id tv_menu_level1_id
						,cat1.menu_name tv_menu_level1_name
						,cat2.menu_id tv_menu_level2_id
						,cat2.menu_name tv_menu_level2_name
					from `tv_program` tv
                     inner join `tv_program_episode` tve on tv.tv_id = tve.tv_id and tve.record_status=1
					 left join `users_account` acc on tve.update_by = acc.member_id
					 left join `tv_program_episode_vdo` tvv on tve.tv_episode_id = tvv.tv_episode_id and tvv.record_status=1
					 left join `tv_category` cat1 on tv.menu_level1_id=cat1.menu_id and cat1.menu_type='level1' and cat1.record_status=1
					 left join `tv_category` cat2 on tv.menu_level2_id=cat2.menu_id and cat2.menu_type='level2' and cat2.record_status=1
					where tv.record_status=1
					".$criteria.$criticalCriteria." ) AA ".$orderby; 
		
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}
		
		// $query = $DBSelect->query($sql, $arrWhere);
		// //print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		// if($query)
			// return $query->result_array();
		// else
			// return false;
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql, $arrWhere);
			$DBSelect->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
				if($arrResult){
					foreach($arrResult as $k => $v){
						$arrResult[$k]['viewcount'] = intval(str_replace(",","",$this->trueplook->getViewCenter($v['content_child_id'], 'tv_program_episode', 'media')));
					}
				}
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult, 60);
		}

		return $arrResult;
	}
	public function getRelate_ams_newscamp($content_id = null, $limit = null, $offset = null, $order = null, $arrFilter = array()) 
	{	//same mul_category , mul_level 
		$DBSelect = $this->load->database('select', TRUE);
		
		$criteria = "";
		if($content_id>0){
			$criteria .= "and c.camp_id != COALESCE(nullif(@content_id,'0'), c.camp_id)";
		}
		
		if($arrFilter['textSearch'] && $arrFilter['textSearch']!=""){
			$criteria .= " and (c.camp_title='".$arrFilter['textSearch']."' or c.camp_title like '%".$arrFilter['textSearch']."%')";
		}
		
		if($arrFilter['groupIDFilter'] && $arrFilter['groupIDFilter']!=""){
			$criteria .= " and c.camp_id in (".$arrFilter['groupIDFilter'].")";
		}
		
		$arrWhere = array(
				 '@content_id'=>$content_id
		);
		
		if($order=='last'){
			$criticalCriteria = "";
			$orderby = " order by c.camp_id desc";
		}
		else{	// order by random
			$criticalCriteria = "";
			$orderby = " order by rand()";
		}
		
		if(!isset($orderby)){
			$orderby = " order by content_id desc";//" order by rand()";
		}
		
		
		$sql = "select
						'news_camp' type
						,c.camp_id content_id
						,'' content_child_id
						,c.camp_title title
						,concat('http://static.trueplookpanya.com/trueplookpanya/',c.banner) thumbnail
						,concat('http://static.trueplookpanya.com/trueplookpanya/',c.banner) banner
						,'' url
						,c.viewcount viewcount
						,c.add_date addDateTime
						,acc.user_username addBy
						,c.short_description short_detail
						,c.camp_detail detail
						,c.camp_start_date camp_date_start
						,c.camp_end_date camp_date_end
						,c.register_date_start register_date_start
						,c.register_date_end register_date_end
						,c.announce_date announce_date
						,c.stay_type stay_type
						,c.people_receive people_receive
						,c.property_tag property_tag
						,c.expense expense
						,c.expense_remark expense_remark
						,c.location_detail location_detail
						,c.provider_detail provider_detail
						,c.link_web link_web
						,c.link_register link_register
					from `ams_news_camp` c
					 left join `users_account` acc on c.add_by = acc.user_id
					where c.isEnable=1
					".$criteria.$criticalCriteria.$orderby; 
		
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}
		
		// $query = $DBSelect->query($sql, $arrWhere);
		// //print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		// if($query)
			// return $query->result_array();
		// else
			// return false;
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql, $arrWhere);
			$DBSelect->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult, 60);
		}
		
		return $arrResult;
	}
	public function getRelate_lessonplan($content_id = null, $limit = 50, $offset = null, $order = null, $arrFilter = array()) 
	{	//same mul_category , mul_level 
		$DBSelect = $this->load->database('select', TRUE);
		
		$criteria = "";
		if($content_id>0){
			$criteria .= " and lp.lesson_plan_id!=".$content_id;
			$criteria .= " and lp.lesson_plan_id in (select lesson_plan_id from lesson_plan where mul_category_id in (select mul_category_id from lesson_plan where lesson_plan_id=".$content_id."))";
			$criteria .= " and lp.lesson_plan_id in (select lesson_plan_id from lesson_plan where mul_level_id in (select mul_level_id from lesson_plan where lesson_plan_id=".$content_id."))";
		}
		
		$textSearch = $arrFilter['textSearch'];
		if($textSearch!=null){
			$criteria .= " and ( (lp.lesson_subject='".$textSearch."' or lp.lesson_subject like '%".$textSearch."%') or (lp.lesson_sub_subject='".$textSearch."' or lp.lesson_sub_subject like '%".$textSearch."%') )";
		}
		$cat_id = $arrFilter['mul_category_id'];
		if($cat_id!=null){
			$criteria .= " and lp.mul_category_id=".$cat_id;
		}
		$level_id = $arrFilter['mul_level_id'];
		if($level_id!=null){
			$criteria .= " and lp.mul_level_id=".$level_id;
		}
		$context_id = $arrFilter['context_id'];
		if($context_id!=null){
			//$criteria .= " and l=".$context_id;
		}
		
		
		$this_content_id = $arrFilter['this_content_id'];
		if($this_content_id!=null){
			$criteria .= " and lp.lesson_plan_id=".$this_content_id;
		}
		
		$select_detail = "";
		if($arrFilter['select'] && $arrFilter['select']!=""){
			if($arrFilter['select']=='full'){
				$select_detail = ",lesson_object1,lesson_object2,lesson_object3,lesson_object4,lesson_object5,lesson_object6,lesson_object7,lesson_object8
										,credit_by_name,credit_by_url
										,if(ifnull(lesson_file1_name,'')!='',concat(lesson_path,'/',lesson_file1_name),null) as file1, lesson_file1_size as file1_size
										,if(ifnull(lesson_file2_name,'')!='',concat(lesson_path,'/',lesson_file2_name),null) as file2, lesson_file2_size as file2_size
										";
			}
		}
		
		if($arrFilter['moreCriteria'] && $arrFilter['moreCriteria']!=""){
			$criteria .= $arrFilter['moreCriteria'];
		}
		
		if($order=='last'){
			$criticalCriteria = "";
			$orderby = " order by lp.add_date desc";
		}
		elseif($order=='still'){
			$criticalCriteria = "";
			$orderby = " ";
		}
		else{	// order by random
			$criticalCriteria = " order by lp.lesson_plan_id desc limit 200";
			$orderby = " order by rand()";
		}
		
		if(!isset($orderby)){
			$orderby = " order by content_id desc";//" order by rand()";
		}
		
		
		$sql = "select AA.*
					from (select
						'lesson_plan' type
						,lp.lesson_plan_id content_id
						,NULL as content_child_id
						,concat(lp.lesson_subject,if(nullif(lesson_sub_subject,'')!='',concat(' : ',lesson_sub_subject),'')) topic
						,'http://static.trueplookpanya.com/trueplookpanya/images/tppy.png' as thumbnail
						,concat('http://www.trueplookpanya.com/true/lesson_plan_detail.php?lesson_plan_id=',lp.lesson_plan_id) web_url
						,lp.add_date addDateTime
						,'Plook Teacher' as addBy
						,'538691' as addBy_id
						,'http://static.trueplookpanya.com/tppy/member/m_537500_540000/538691/profile/profile.png' as addBy_image
						,NULL as addBy_banner
						,'0' as addBy_follow_count
						,lp.mul_level_id mul_level_id
						,ml.mul_level_name mul_level_name
						,lp.mul_category_id mul_category_id
						,mc.mul_category_name mul_category_name
						,'' context_id
						,'' context_name
						,NULL as short_detail
						,'0' as viewcount
						".$select_detail."
					from `lesson_plan` lp
					 left join mul_category_2014 mc on lp.mul_category_id=mc.mul_category_id
					 left join mul_level ml on lp.mul_level_id=ml.mul_level_id
					where record_status=1
					".$criteria.$criticalCriteria." ) AA ".$orderby; 
		
		if(isset($offset) && isset($limit)){
			if($offset>0 && $limit>0){
					$sql .= " limit ".$offset.",".$limit;
			}elseif ($limit>0){
					$sql .= " limit ".$limit;	
			}
		}elseif(isset($limit) && $limit>0){
			$sql .= " limit ".$limit;	
		}
		
		// $query = $DBSelect->query($sql);
		// //print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		// if($query){
			// $r = $query->result_array();
			// foreach($r as $k => $v){
				// $r[$k]['viewcount'] = $this->trueplook->getViewNumber($v['content_id'], 19); 
			// }
			// return $r;
		// }else{
			// return false;
		// }
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql);
			$DBSelect->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
				foreach($arrResult as $k => $v){
					$arrResult[$k]['viewcount'] = $this->trueplook->getViewNumber($v['content_id'], 19); 
				}
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult);
		}		
		return $arrResult;
	}
	public function getRelate_lessonplan_detail($content_id, $arrFilter=array())
	{
		if(!isset($content_id)){
			echo 'content_id are required';
			exit();
		}
		
		
		$req['select'] = 'full';
		$req['this_content_id'] = $content_id;
		$arr = $this->getRelate_lessonplan(null, 1, null, null, $req);
		if(is_array($arr)){
			$arrReturn[] = $arr[0];
			$this->load->model('blog/blogmodel');
			
			foreach($arrReturn as $k => $v){
					$id = $v['content_id'];
					
					$arrReturn[$k]['addBy_follow_count'] = $this->blogmodel->countFollow($v['addBy_id']);
					
					//---------------------------- Add View ---------------------------//
					$this->trueplook->addViewVal("19", $lesson_planid, 'mul');
					$this->tppy_utils->ViewNumberSet($id, 'lesson_plan');
					
					// node FUllDetail
					$LessonPlanSubject['1'] = "สาระสำคัญ / ความคิดรวบยอด";
					$LessonPlanSubject['2'] = "ตัวชี้วัด / จุดประสงค์การเรียนรู้";
					$LessonPlanSubject['3'] = "สาระการเรียนรู้";
					$LessonPlanSubject['4'] = "สมรรถนะสำคัญของผู้เรียน";
					$LessonPlanSubject['5'] = "คุณลักษณะอันพึงประสงค์";
					$LessonPlanSubject['6'] = "กิจกรรมการเรียนรู้";
					$LessonPlanSubject['7'] = "การวัดผลและประเมินผล";
					$LessonPlanSubject['8'] = "สื่อ / แหล่งการเรียนรู้";
					$strFullDetail = "";
					foreach( $LessonPlanSubject as $_key => $_val ) {
						$obj_title = $_val;
						$obj_data = $v['lesson_object'.$_key];
						$strFullDetail .= "<h2>".$obj_title."</h2>";
						$strFullDetail .= $obj_data;
					}
					$strFullDetail .= "<br>ขอขอบคุณเนื้อหาจาก ". ($v['credit_by_url']!=null ? "<a href='".$v['credit_by_url']."' target='_blank'>".$v['credit_by_name']."</a>" : $v['credit_by_name'] );
					$arrReturn[$k]['full_detail'] =$strFullDetail;
										
					// node Files
					$arrReturn[$k]['files'][0]['title'] = 'ไฟล์ใบงาน';
					$arrReturn[$k]['files'][0]['file_full_path'] = ($v['file1'] !=null ? site_url('/data/product/media/'.$v['file1']) : null );
					$arrReturn[$k]['files'][0]['file_size'] = $v['file1_size'];
					$arrReturn[$k]['files'][0]['file_type'] = 'file';
					$arrReturn[$k]['files'][1]['title'] = 'ไฟล์แบบทดสอบ';
					$arrReturn[$k]['files'][1]['file_full_path'] = ($v['file2'] !=null ? site_url('/data/product/media/'.$v['file2']): null );
					$arrReturn[$k]['files'][1]['file_size'] = $v['file2_size'];
					$arrReturn[$k]['files'][1]['file_type'] = 'file';
					
					// node Social
					$arrReturn[$k]['og']['og:app_id'] = "704799662982418";
					$arrReturn[$k]['og']['og:title'] = $v['topic'];
					$arrReturn[$k]['og']['og:type'] = 'article';
					$arrReturn[$k]['og']['og:locale'] = 'th_TH';
					$arrReturn[$k]['og']['og:url'] = $v['web_url'].$menu_code;
					$arrReturn[$k]['og']['og:image'] = $v['thumbnail'];
					$arrReturn[$k]['og']['og:site_name'] = 'trueplookpanya.com';
					$arrReturn[$k]['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $v['short_description']))), 200);
					
					// node Relate
					$arrReturn[$k]['relate_content'] = $this->getRelate_lessonplan($id, 3, null, null);
			}
		}
		return $arrReturn;
	}
       
	// -- cmsblog : start
	private function _getFile_cmsblog($cmsblogID){
		$cache_key = "CMSBLOG_File_".$cmsblogID;
	 #$this->tppymemcached->delete($cache_key);
		if (!$arrReturn = $this->tppymemcached->get($cache_key)) {	
			
			$CI = &get_instance();
			$this->load->library('videojs');
			$sql = "select 
						 cmsblog_file_id as file_id
						 ,file_title as title
						 ,file_thumb as thumbnail
       ,if(file_thumb=null,'',CONCAT('".$this->config->item('static_url')."',file_path,'/' ,file_thumb)) thumbnails
						 ,concat('".$this->config->item('static_url')."',file_path,'/' ,file_name) as file_full_path
						 ,file_path as file_path
						 ,file_name as file_name
						 ,case file_type 
							when 1 then 'video'
							when 2 then 'audio'
							else 'file'
						  end
						  as file_type
						 ,file_size as file_size
						 ,file_duration as vdo_duration
						 ,file_detail_json as file_detail_json
						 ,create_date as addDateTime
						from cmsblog_file 
						where record_status=1 and cmsblog_idx=".$cmsblogID." order by weight asc,file_type asc";
			$resp = $this->db->query($sql); 
				if($resp){
					$arrReturn = $resp->result_array();
					foreach($arrReturn as $k => $v){
						if($v['file_type']=='video'){
							/* ของเก่า ตอนเรียกจาก mediaconverter --------
							$arrReturn[$k]['vdo_url2play'] = $this->trueplook->get_vdo_url($v['file_path'], $v['file_name']);
							$vdData = $CI->videojs->convert_json($v['file_detail_json']);
							
							$vd=json_decode($v['file_detail_json']);
							if(is_null($vd) || $vd==""){
								$fileFullPath = $v["file_full_path"];
								$vd->ref_id = "CMSBLOG_".$cmsblogID."_".$v["file_id"];
								$vd->thumbnails = $v["thumbnail"];
								$vdData = $fileFullPath;
							}
							 // _vd($vd);
							 $video_data = array(
								'id' => $vd->ref_id,
								'width' => '100%',
								//'height' => 'auto',
								'thumb' => $vd->thumbnails,
								'data' => $vdData,
								'logo' => '',
								'logo_destination' => '',
								'default_res' => '480p',
							 );
							 // _vd($video_data);
							 // $htmlFILE = $CI->videojs->video_player($video_data);	แก้ให้ใช้ vdojs ที่นี่ ---
							 
							$htmlFILE =' 
							<!-- START #player -->
							<link href="http://www.trueplookpanya.com/assets/video-js/video-js.css" rel="stylesheet" type="text/css" />  
							<script type="text/javascript" src="http://www.trueplookpanya.com/assets/video-js/video.js"></script>
											<strong>'. $v["file_title"] .'</strong>
											<div id="player_<?= $k ?>">
												<center>
													<video id="example_video_<?= $k ?>" class="video-js vjs-default-skin vjs-big-play-centered"
														   controls width="" height="350"
														   poster="'. $v['thumbnail'] .'"
														   data-setup=\'{ "controls": true, "autoplay": false, "preload": "auto" }\'>
														<source src="'. $v["file_full_path"].'" type="video/mp4" />                            
														<p class="vjs-no-js">To view this<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
													</video>
												</center>
											</div>
											<br>
											<!-- END #player -->
							';
							
							$arrReturn[$k]['HTML_tag'] = $htmlFILE;
							*/
							
							$fullFileName = $this->trueplook->get_vdo_url($v['file_path'], $v['file_name'],$hd=true,$type="cmsblog");
							$arrReturn[$k]['vdo_url2play'] = $fullFileName;
							/*
							$htmlFILE =' 
							<!-- START #player -->
							<link href="http://www.trueplookpanya.com/assets/video-js/video-js.css" rel="stylesheet" type="text/css" />  
							<script type="text/javascript" src="http://www.trueplookpanya.com/assets/video-js/video.js"></script>
							<strong>'. $v["file_title"] .'</strong>
							<div id="player_<?= $k ?>">
								<center>
									<video id="example_video_<?= $k ?>" class="video-js vjs-default-skin vjs-big-play-centered"
										   controls width="" height="350"
										   poster="'. $v['thumbnail'] .'"
										   data-setup=\'{ "controls": true, "autoplay": false, "preload": "auto" }\'>
										<source src="'. $fullFileName .'" type="video/mp4" />                            
										<p class="vjs-no-js">To view this<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
									</video>
								</center>
							</div>
							<br>
							<!-- END #player -->
							';
							$arrReturn[$k]['HTML_tag'] = $htmlFILE;
							*/
							////START #player////
							$video_data = array(
								'id' => $v["file_id"],
								'width' => '100%',
								//'height' => 'auto',
								'thumb' => $v["thumbnail"],
								'data' => $v["file_full_path"],
								'logo' => '',
								'logo_destination' => '',
								'default_res' => '480p',
							 );
							 $htmlFILE = $CI->videojs->skin_video($title=$v["title"],$video_data);
							 ////END #player////
							 $arrReturn[$k]['HTML_tag'] = $htmlFILE;
						}elseif($v['file_type']=='audio'){
							$htmlFILE ='
							<style type="text/css" title="audiojs">.audiojs audio { position: absolute; left: -1px; }         .audiojs { width: 460px; height: 36px; background: #404040; overflow: hidden; font-family: monospace; font-size: 12px;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #444), color-stop(0.5, #555), color-stop(0.51, #444), color-stop(1, #444));           background-image: -moz-linear-gradient(center top, #444 0%, #555 50%, #444 51%, #444 100%);           -webkit-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); -moz-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3);           -o-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); }         .audiojs .play-pause { width: 25px; height: 40px; padding: 4px 6px; margin: 0px; float: left; overflow: hidden; border-right: 1px solid #000; }         .audiojs p { display: none; width: 25px; height: 40px; margin: 0px; cursor: pointer; }         .audiojs .play { display: block; }         .audiojs .scrubber { position: relative; float: left; width: 280px; background: #5a5a5a; height: 14px; margin: 10px; border-top: 1px solid #3f3f3f; border-left: 0px; border-bottom: 0px; overflow: hidden; }         .audiojs .progress { position: absolute; top: 0px; left: 0px; height: 14px; width: 0px; background: #ccc; z-index: 1;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ccc), color-stop(0.5, #ddd), color-stop(0.51, #ccc), color-stop(1, #ccc));           background-image: -moz-linear-gradient(center top, #ccc 0%, #ddd 50%, #ccc 51%, #ccc 100%); }         .audiojs .loaded { position: absolute; top: 0px; left: 0px; height: 14px; width: 0px; background: #000;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #222), color-stop(0.5, #333), color-stop(0.51, #222), color-stop(1, #222));           background-image: -moz-linear-gradient(center top, #222 0%, #333 50%, #222 51%, #222 100%); }         .audiojs .time { float: left; height: 36px; line-height: 36px; margin: 0px 0px 0px 6px; padding: 0px 6px 0px 12px; border-left: 1px solid #000; color: #ddd; text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.5); }         .audiojs .time em { padding: 0px 2px 0px 0px; color: #f9f9f9; font-style: normal; }         .audiojs .time strong { padding: 0px 0px 0px 2px; font-weight: normal; }         .audiojs .error-message { float: left; display: none; margin: 0px 10px; height: 36px; width: 400px; overflow: hidden; line-height: 36px; white-space: nowrap; color: #fff;           text-overflow: ellipsis; -o-text-overflow: ellipsis; -icab-text-overflow: ellipsis; -khtml-text-overflow: ellipsis; -moz-text-overflow: ellipsis; -webkit-text-overflow: ellipsis; }         .audiojs .error-message a { color: #eee; text-decoration: none; padding-bottom: 1px; border-bottom: 1px solid #999; white-space: wrap; }                 .audiojs .play { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -1px no-repeat; }         .audiojs .loading { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -31px no-repeat; }         .audiojs .error { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -61px no-repeat; }         .audiojs .pause { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -91px no-repeat; }                 .playing .play, .playing .loading, .playing .error { display: none; }         .playing .pause { display: block; }                 .loading .play, .loading .pause, .loading .error { display: none; }         .loading .loading { display: block; }                 .error .time, .error .play, .error .pause, .error .scrubber, .error .loading { display: none; }         .error .error { display: block; }         .error .play-pause p { cursor: auto; }         .error .error-message { display: block; }</style>
											<script src="http://www.trueplookpanya.com/new/assets/javascript/audiojs/audio.min.js"></script>   
											<script>
															audiojs.events.ready(function () {
																var as = audiojs.createAll();
															});
											</script>
											<div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
												<center>
													<div style="width:auto; margin:30px auto" id="audio_area"><audio src="' . $v['file_full_path'] .'" preload="auto" /></div>
												</center>
											</div>
							';
							$arrReturn[$k]['HTML_tag'] = $htmlFILE;
						}else{
							$htmlFILE ='
							<div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
												<div style="display: table;width:auto; border:1px solid #CCC; margin:20px auto; padding:10px">
													<div>
														<div style="float:left"><a href="' . $v['file_full_path'] .'" target="_blank" title="ดาวน์โหลดเอกสารเรื่อง '. $v['title'] .'" ><img src="http://www.trueplookpanya.com/new/assets/images/icon/icon_download.png" width="48" height="48" border="" alt=""  /></a></div>
														<div style="float:left; margin-left:5px">
															<div style="padding-left:5px"><a href="' . $v['file_full_path'] . '" target="_blank" title="โหลดเอกสารเรื่อง '. $v['title'] .'" >ดาวน์โหลด '.$v['title'].'</a></div>
															<div style="float:left; margin-top:5px;padding-left:5px">ขนาด : '. $this->trueplook->get_mb($v['file_size']) .' MB</div>    
														</div>
													</div>
												</div>
											</div>
							';
							$arrReturn[$k]['HTML_tag'] = $htmlFILE;
						}
					}
				}else{
					$arrReturn = null;
				}
				
				$this->tppymemcached->set($cache_key, $arrReturn,300);
		}
		
		return $arrReturn;
	}
	public function getDetail_cmsblog($cmsblogID=0, $arrFilter = array()){
			
			$criteria = "";
			
			$record_status = $arrFilter['record_status'];
			if($record_status=='all'){
				
			}elseif($record_status!=null){
				$criteria .= " and cms.record_status in (".$record_status.")";
			}else{
				$criteria .= " and cms.record_status=1";
			}
			
			//--start :  check Zone & Category
			$ckey = "";
			$zone_id = $arrFilter['zone_id'];
			if($zone_id!=null && $zone_id!=""){
				$criteria .= " and cms.idx in (select content_id from cmsblog_category_mapping where content_type=2 and category_id in (select category_id from cmsblog_category where zone_id in ($zone_id)))";
				$ckey .= "_".$zone_id;
			}
			$cat_id = $arrFilter['categoryList'];
			if($cat_id!=null && $cat_id!=""){
				$criteria .= " and cms.idx in (select content_id from cmsblog_category_mapping where content_type=2 and category_id in ($cat_id))";
				$ckey .= "_".$cat_id;
			}
			$menu = $arrFilter['menu'];
			if($menu!=null && $menu!=""){
				$criteria .= " and cms.idx in (select content_id from cmsblog_category_mapping where content_type=2 and category_id in ($menu))";
				$ckey .= "_".$menu;
			}	
			//--end :  check Zone & Category
		
			$sql = "select 
					'cmsblog' as content_type
					,cms.idx as content_id
					,cms.parent_idx as parent_id
					,cms.child_order as child_order
					,cms.title as topic
					,cms.description_short as short_detail
					,concat('http://www.trueplookpanya.com/knowledge/content/',cms.idx) as web_url
					,CONCAT('".$this->config->item('static_url')."', COALESCE(cms.thumb_path, 'cmsblog/default_thumbnail.png')) thumbnail
					,cms.view_count as viewcount
					,cms.start_date as addDateTime
					,acc.psn_display_name as addBy
					,cms.create_user_id as addBy_id
					,acc.psn_display_image as addBy_image
					,acc.psn_display_banner as addBy_banner
					,'0' as addBy_follow_count
					,if(cms.banner_path=null,'',CONCAT('".$this->config->item('static_url')."', banner_path)) banner
					,cms.description_long as long_detail
					,cms.credit_by as credit_detail
					,cms.hashtag as hashtag_detail
					,cms.event_date as event_date
					,cms.start_date as start_date
					,cms.end_date as end_date
					,cms.editor_picks as isEditorPick
					,cms.encyclopedia as isEncyclopedia
					,cms.record_status as record_status
					from cmsblog_detail cms
					 left join `users_account` acc on cms.create_user_id = acc.user_id
					where 1=1 ".$criteria."
					 and cms.idx=".$cmsblogID." ";
			
			// $this->db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");			
			// $resp = $this->db->query($sql);
			// $this->db->query("COMMIT");
			//print_r($this->db->queries); echo '<br><br>----<br>'; //exit();
			
			$cache_key = "cmsblog_detail_".$cmsblogID."_".$record_status.$ckey;
			// $this->tppymemcached->delete($cache_key);
			if (!$arrResult = $this->tppymemcached->get($cache_key)) {
				$this->db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");			
				$resp = $this->db->query($sql);
				$this->db->query("COMMIT");
				if($resp){
					$arrResult = $resp->result_array();
				}else{
					$arrResult = false;
				}
				$this->tppymemcached->set($cache_key, $arrResult,600);
				//print_r("set");
			}//		else{print_r("get");}
			
			
			
			if($arrResult){
				
				
				
				$this->load->model('blog/blogmodel');
				
				$arrReturn = $arrResult;
				foreach($arrReturn as $k => $v){
					$id = $v['content_id'];
					//---------------------------- Add View ---------------------------//
					$arrReturn[$k]['viewcount'] =  $this->tppy_utils->ViewNumberSet($id, 'cmsblog_detail');
					
					// more data
					$arrReturn[$k]['addBy_follow_count'] = $this->blogmodel->countFollow($v['addBy_id']);
					
					
					$filter = array();
					$filter['content_id'] = $id;
					$filter['currentZoneIdList'] = $zone_id;
					$arrReturn[$k]['category_list'] = $this->get_cmsblogCategoryMapping($filter);
					
					$menu_code = $this->get_cmsblogCategoryCode($id,0,$filter);
					$arrReturn[$k]['menu_code'] = $menu_code;
					
										
					
					// node Social
					$arrReturn[$k]['og']['og:app_id'] = "704799662982418";
					$arrReturn[$k]['og']['og:title'] = $v['topic'];
					$arrReturn[$k]['og']['og:type'] = 'article';
					$arrReturn[$k]['og']['og:locale'] = 'th_TH';
					$arrReturn[$k]['og']['og:url'] = $v['web_url'].$menu_code;
					$arrReturn[$k]['og']['og:image'] = $v['thumbnail'];
					$arrReturn[$k]['og']['og:site_name'] = 'trueplookpanya.com';
					$arrReturn[$k]['og']['og:description'] = $this->trueplook->limitText(strip_tags(str_replace('\'', ' ', str_replace('\"', ' ', $v['short_detail']))), 200);
					
					// node Files
					$arrReturn[$k]['files'] = $this->_getFile_cmsblog($id);
					
					// node labelTag
					$arrReturn[$k]['labelTag_list'] = $this->getLabelTag_info(9,$v['content_id']);
					
					// node child content
					$cache_key = "cmsblog_detail_child_".$id;
					//$this->tppymemcached->delete($cache_key);
					if (!$arrChild = $this->tppymemcached->get($cache_key)) {
						$arrF = array();
						$arrF['moreCriteria']=' and cms.parent_idx='.$id;
						$arrChild = $this->get_cmsblogList(999,null,'child_order_asc',$arrF);
						if(!$arrChild){
							$arrChild = array();
						}
						$this->tppymemcached->set($cache_key, $arrChild,3600);
						//print_r("set");
					}//		else{print_r("get");}
					$arrReturn[$k]['child_content'] = $arrChild;
					
					// node Relate
					$cache_key = "cmsblog_detail_relate_".$id;
					//$this->tppymemcached->delete($cache_key);
					if (!$arrRelate = $this->tppymemcached->get($cache_key)) {
						$arrRelate = $this->getRelate_cmsblog_forDetail($id);
						$this->tppymemcached->set($cache_key, $arrRelate,3600);
						//print_r("set");
					}//		else{print_r("get");}
					$arrReturn[$k]['relate_content'] = $arrRelate;
				}
				
				return $arrReturn;
			}else{
				return false;
			}
			
			
	}
	public function getRelate_cmsblog_forDetail($content_id = null, $arrFilter = array()) {
			//same mul_category , mul_level 
		$DBSelect = $this->load->database('select', TRUE);
		if(!isset($content_id)){
			echo 'content_id is required';
			exit();
		}
		
		$node1=array(); $node2=array();
		
		$node1 = $this->getRelate_cmsblog_forDetail_previousnext($content_id);
		//$node1 = $this->getRelate_cmsblog_forDetail_byUser($content_id,3,null,'rand',$filter=array());
		$node2 = $this->getRelate_cmsblog_forDetail_byCategory($content_id,4,null,'rand',$filter=array());
		
		$r['relate_by_content'] = $node1;
		$r['relate_by_menu'] = $node2;
		return $r;
	}
	public function getRelate_cmsblog_forDetail_previousnext($content_id = null){	
		$DBSelect = $this->load->database('select', TRUE);
		if(!isset($content_id)){
			echo 'content_id is required';
			exit();
		}
		
		$node1=array(); $node2=array();
		$parent_id = 0;
		$addBy_id = 0;
		$cover_link = "";
		
		$filter=array();
		$filter['this_content_id']=$content_id;
		$thisContent = $this->get_cmsblogList(1,null,null,$filter);
		//var_dump($thisContent[0]); exit();
		if($thisContent){
			$parent_id = (int) $thisContent[0]['parent_id'];
			$addBy_id = $thisContent[0]['addBy_id'];
			if($parent_id>0){
				// it's CHILD content
				$cover_link = site_url('/knowledge/content/'.$parent_id);
				$child_order = (int) $thisContent[0]['child_order'];
				
				// get Previous
				$arrF = array();
				$arrF['moreCriteria']=' and cms.idx!='.$content_id.' and cms.parent_idx='.$parent_id.' and cms.child_order<'.$child_order;
				$node1 = $this->get_cmsblogList(1,null,'child_order_desc',$arrF);
				// if(!$node1){ // not have previous by child oder , then get other content in same user
					// $arrF['moreCriteria']=' and cms.idx='.$parent_id;
					// $node1 = $this->get_cmsblogList(1,null,null,$arrF);
				// }
				
				// get Next
				$arrF = array();
				$arrF['moreCriteria']=' and cms.idx!='.$content_id.' and cms.parent_idx='.$parent_id.' and cms.child_order>'.$child_order;
				$node2 = $this->get_cmsblogList(1,null,'child_order_asc',$arrF);
				// if(!$node2){ // not have next by child oder , then get other content in same user
					// $arrF['moreCriteria']=' and cms.idx!='.$content_id.' and cms.create_user_id='.$addBy_id.' and cms.parent_idx!='.$parent_id.' and cms.idx>'.$content_id;
					// $node2 = $this->get_cmsblogList(1,null,null,$arrF);
				// }
			}else{
				// it's PARENT content
				return array();
				/* ---- ถ้าเป็น parent , จะไม่แสดง previous + next
				$cover_link = site_url('blog/blogger-content/'.$addBy_id);
				
				// get Previous
				$arrF = array();
				$arrF['moreCriteria']=' and cms.idx!='.$content_id.' and cms.create_user_id='.$addBy_id.' and cms.idx<'.$content_id;
				$node1 = $this->get_cmsblogList(1,null,null,$arrF);
				
				// get Next
				$arrF = array();
				$arrF['moreCriteria']=' and cms.idx!='.$content_id.' and cms.parent_idx='.$content_id;
				$node2 = $this->get_cmsblogList(1,null,'child_order_asc',$arrF);
				if(!$node2){ // not have next by child oder , then get other content in same user
					$arrF['moreCriteria']=' and cms.idx!='.$content_id.' and cms.create_user_id='.$addBy_id.' and cms.idx>'.$content_id;
					$node2 = $this->get_cmsblogList(1,null,null,$arrF);
				} */
			}
		}
		
		//$filter=array();
		//$arrChild = $this->get_cmsblogList(100,null,'last',$filter);
		//$r['child'] = $arrChild;
		//$node1 = $this->getRelate_cmsblog_forDetail_byUser($content_id,1,null,'rand',$filter=array());
		//$node2 = $this->getRelate_cmsblog_forDetail_byUser($content_id,1,null,'rand',$filter=array());
		
		
		$r['cover_link'] = $cover_link;
		$r['previous'] = $node1;
		$r['next'] = $node2;
		return $r;
	}
	public function getRelate_cmsblog_forDetail_byCategory($content_id = null, $limit =4, $offset=null, $order=null, $arrFilter = array()) 
	{	
		$DBSelect = $this->load->database('select', TRUE);
		if(!isset($content_id)){
			echo 'content_id is required';
			exit();
		}
		
		$filter['moreCriteria']=' and cms.idx!='.$content_id.' and cms.idx in (select content_id from cmsblog_category_mapping where  content_type=2 and category_id in (select category_id from cmsblog_category_mapping where content_type=2 and content_id='.$content_id.') and content_id not in (select idx from cmsblog_detail where parent_idx='.$content_id.'))';
		$r = $this->get_cmsblogList($limit,$offset,$order,$filter);

		return $r;
	}
	//-- Detail end
	
	//--วันนี้ในอดีด history และ อนาคต future
	public function get_cmsblog_history($dateNo=null,$monthNo=null,$yearNo=null, $arrFilter = array())
	{
		$filter = array();
		
		if($dateNo==null || $dateNo==""){
			$dateNo = date('d');
		}
		if($monthNo==null || $monthNo==""){
			$monthNo = date('m');
		}
		if($yearNo==null || $yearNo==""){
			$yearNo = date('Y');
		}
		
		$f_limit = $arrFilter['limit'];
		if($f_limit!=null){
			$limit = $f_limit;
		}else{
			$limit = 20;
		}
		$f_offlset = $arrFilter['offset'];
		if($f_offlset!=null){
			$offset = $f_offlset;
		}
        $order = "history";

		$q = $arrFilter['q'];
		if($q!=null){
			$filter['textSearch'] = $q;
		}
		
		// Node : Today
		$filter['categoryList'] = 1000003;
		$filter['currentCategoryIdList'] = $filter['categoryList'];
		$filter['moreCriteria'] = " and event_date is not null and SUBSTRING(event_date,6,2)='$monthNo' and SUBSTRING(event_date,9,2)='$dateNo'";
		//echo $arrFilter['moreCriteria'];
		$qResult = $this->get_cmsblogList($limit, $offset, $order, $filter);
        //var_dump($qResult);
        $r = array();
		$r['today'] = $qResult;
        return $r;
	}
	public function get_cmsblog_future($dateNo=null,$monthNo=null,$yearNo=null, $arrFilter = array())
	{
		$filter = array();
		
		if($dateNo==null || $dateNo==""){
			$dateNo = date('d');
		}
		if($monthNo==null || $monthNo==""){
			$monthNo = date('m');
		}
		if($yearNo==null || $yearNo==""){
			$yearNo = date('Y');
		}
		
		$f_limit = $arrFilter['limit'];
		if($f_limit!=null){
			$limit = $f_limit;
		}else{
			$limit = 20;
		}
		$f_offlset = $arrFilter['offset'];
		if($f_offlset!=null){
			$offset = $f_offlset;
		}
        $order = "future";

		$q = $arrFilter['q'];
		if($q!=null){
			$filter['textSearch'] = $q;
		}
		
		// Node : Future
		$filter['categoryList'] = 1000004;
		$filter['currentCategoryIdList'] = $filter['categoryList'];
		$filter['moreCriteria'] = " and event_date is not null and STR_TO_DATE(event_date, '%Y-%m-%d')>now()";
		$qResult = $this->get_cmsblogList($limit, $offset, $order, $filter);
        //var_dump($qResult);
		$r = array();
		$r['future'] = $qResult;
        return $r;
	}
	public function get_cmsblogList($limit = 50, $offset = null, $order = null, $arrFilter = array()) 
	{
		$DBSelect = $this->load->database('select', TRUE);
		
		$ignore_content_id = $arrFilter['ignore_content_id'];
		if($ignore_content_id!=null){
			$criteria .= " and cms.idx!=".$ignore_content_id;
		}
		
		$this_content_id = $arrFilter['this_content_id'];
		if($this_content_id!=null){
			$criteria .= " and (cms.idx in ($this_content_id))";
		}
		$user_id = $arrFilter['user_id'];
		if($user_id!=null){
			$criteria .= " and (cms.create_user_id=$user_id)";
		}
		$textSearch = $arrFilter['textSearch'];
		if($textSearch!=null){
			$criteria .= " and (cms.title='".$textSearch."' or cms.title like '%".$textSearch."%')";
		}
		$zone_id = $arrFilter['zone_id'];
		if($zone_id!=null && $zone_id!=""){
			$criteria .= " and cms.idx in (select content_id from cmsblog_category_mapping where content_type=2 and category_id in (select category_id from cmsblog_category where zone_id in ($zone_id)))";
		}
		$cat_id = $arrFilter['categoryList'];
		if($cat_id!=null && $cat_id!=""){
			$criteria .= " and cms.idx in (select content_id from cmsblog_category_mapping where content_type=2 and category_id in ($cat_id))";
		}
		$cat_id = $arrFilter['menu'];
		if($cat_id!=null && $cat_id!=""){
			$criteria .= " and cms.idx in (select content_id from cmsblog_category_mapping where content_type=2 and category_id in ($cat_id))";
		}	
		$exclude_cat_id = $arrFilter['excludeCategoryList'];
		if($exclude_cat_id!=null && $exclude_cat_id!=""){
			$criteria .= " and cms.idx not in (select content_id from cmsblog_category_mapping where content_type=2 and category_id in ($exclude_cat_id))";
		}
		
		$current_catList = $arrFilter['currentCategoryIdList'];	// ใช้ในการดึงชื่อ category ให้ตรงกับที่แสดงผล
		if($current_catList!=null && $current_catList!=""){
			$current_catList = $current_catList;
			$current_zoneList = $arrFilter['currentZoneIdList'];
		}else{
			$current_catList = $cat_id;
		}
		
		$parent_id = $arrFilter['parent_id'];
		if($parent_id!=null){
			$criteria .= " and cms.idx in (select idx from cmsblog_detail where parent_idx=$parent_id)";
		}
		$isEditorPick = $arrFilter['isEditorPick'];
		if($isEditorPick===true){
			$criteria .= " and (cms.editor_picks=1 or cms.encyclopedia=1)";
			$e=1;
		}
		$isEncyclopedia = $arrFilter['isEncyclopedia'];
		if($isEncyclopedia===true){
			$criteria .= " and (cms.encyclopedia=1)";
		}
		
		$labeltag_id = $arrFilter['labeltagList'];
		if($labeltag_id!=null && $labeltag_id!=""){
			$criteria .= " and cms.idx in (select content_id from label_tag_map where project_id=9 and label_tag_id in ($labeltag_id))";
		}
		
		$record_status = $arrFilter['record_status'];
		if($record_status!=null){
			$criteria .= " and cms.record_status in (".$record_status.")";
		}elseif($record_status=="all"){
			
		}else{
			$criteria .= " and cms.record_status=1 and now()>cms.start_date";
		}
		
		//-- ตัวชี้วัด start
		if($arrFilter['isMapContext']==true){
			$criteria .= " and cms.idx in (select content_id from cmsblog_detail_context_2014_map where table_id=9)";
			
			if($arrFilter['knowledge_id']!=null){
			  $ssid = $arrFilter['knowledge_id'];
			  $criteria .= " and cms.idx in (select content_id from cmsblog_detail_context_2014_map where table_id=9 and context_id in (select knowledge_context_id from knowledge_context_2014 where mul_category_id=".$ssid.") )";
			}
			if($arrFilter['level_id']!=null){
			  $lid = $arrFilter['level_id'];
			  if ($lid >= 41 && $lid <= 43) {
					$lid = 40;
			  }
			  if($lid % 10 == 0){
				  $lid_criteria = "TRUNCATE(mul_level_id,-1)=".$lid;
			  }else{
				  $lid_criteria = "mul_level_id=".$lid;
			  }
			  $criteria .= " and cms.idx in (select content_id from cmsblog_detail_context_2014_map where table_id=9 and context_id in (select knowledge_context_id from knowledge_context_2014 where ".$lid_criteria.") )";
			}
			if($arrFilter['subject_id']!=null){
			  $sid = $arrFilter['subject_id'];
			  $criteria .= " and cms.idx in (select content_id from cmsblog_detail_context_2014_map where table_id=9 and context_id in (select knowledge_context_id from knowledge_context_2014 where TRUNCATE(mul_category_id,-3)=".$sid.") )";
			}
			if($arrFilter['context_id']!=null){
			  $cid = $arrFilter['context_id'];
			  $criteria .= " and cms.idx in (select content_id from cmsblog_detail_context_2014_map where table_id=9 and context_id=".$cid." )";
			}
		}
		//-- ตัวชี้วัด end
		
		if($arrFilter['moreCriteria'] && $arrFilter['moreCriteria']!=""){
			$criteria .= $arrFilter['moreCriteria'];
		}
		
		if($order=='last'){
			$criticalCriteria = "";
			//$orderby = " order by cms.idx  desc";
			$orderby = " order by cms.start_date desc,cms.idx desc";
		}
		elseif($order=='view_desc'){
			$orderby = " order by cms.view_count desc,cms.idx desc ";
		}
		elseif($order=='view_asc'){
			$orderby = " order by cms.view_count asc,cms.idx desc ";
		}
		elseif($order=='still'){
			$orderby = " ";
		}
		elseif($order=='history'){
			$orderby = " ";
		}
		elseif($order=='future'){
			$orderby = " order by STR_TO_DATE(event_date, '%Y-%m-%d') asc";
		}
		elseif($order=='child_order_desc'){
			$orderby = " order by cms.child_order  desc";
		}
		elseif($order=='child_order_asc'){
			$orderby = " order by cms.child_order  asc";
		}
		elseif($order=='name_asc'){
			$orderby = " order by cms.title  asc";
		}
		elseif($order=='rand'){
			$orderby = " order by rand()";  // ป้องกันตาย
		}
		elseif($order=='fix_thiscontentid'){
			$orderby = " ORDER BY FIND_IN_SET(idx, '".$this_content_id."')";
		}
		else{
			$orderby = "";
		}
		
		if(!isset($orderby)){
			$orderby = " order by cms.idx desc";//" order by rand()";
		}
		
		
		$sql = "select 
					'cmsblog' as content_type
					,cms.idx as content_id
					,cms.parent_idx as parent_id
					,cms.child_order as child_order
					,cms.title as topic
					,cms.description_short as short_detail
					,concat('http://www.trueplookpanya.com/knowledge/content/',cms.idx) as web_url
					,CONCAT('".$this->config->item('static_url')."', COALESCE(cms.thumb_path, 'cmsblog/default_thumbnail.png')) thumbnail
					,cms.view_count as viewcount
					,cms.start_date as addDateTime
					,cms.event_date as event_date
					,acc.psn_display_name as addBy
					,cms.create_user_id as addBy_id
					,acc.psn_display_image as addBy_image
					,cms.record_status as record_status
					,cms.editor_picks as isEditorPick
					,cms.encyclopedia as isEncyclopedia
					,if(cms.encyclopedia=1,'Encyclopedia',if(cms.editor_picks=1,'Editor\'s Picks','') ) editorpick_text
					from cmsblog_detail cms
					 left join `users_account` acc on cms.create_user_id = acc.user_id
					where 1=1
					".$criteria.$orderby; 
		
		if($arrFilter["isCount"]===true){
			$cache_key="CountFor_".$sql;
			// $this->tppymemcached->delete($cache_key);
			if (!$arrResult = $this->tppymemcached->get($cache_key)) {
				$arrResult= $DBSelect->query($sql)->num_rows();
				$this->tppymemcached->set($cache_key, $arrResult,120);
			}
			return $arrResult;
		}else{
			
			if(isset($offset) && isset($limit)){
				if($offset>0 && $limit>0){
						$sql .= " limit ".$offset.",".$limit;
				}elseif ($limit>0){
						$sql .= " limit ".$limit;	
				}
			}elseif(isset($limit) && $limit>0){
				$sql .= " limit ".$limit;	
			}
			
		}
		
		//echo ($sql);
		$cache_key = $sql;
		// $this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql);
			$DBSelect->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
				// MENU data
				foreach($arrResult as $k => $v){
					$url_suffix = $this->get_cmsblogCategoryCode($v['content_id'], $categoryID = 0, $arrFilter = array()) ;
					$arrResult[$k]['category_name_code_full'] = $url_suffix;
					$arrResult[$k]['web_url'] = $arrResult[$k]['web_url'] . '/' . $url_suffix;
					
					$filter = array();
					$filter['content_id'] = $v['content_id'];
					$filter['currentZoneIdList'] = $zone_id;
					$arrResult[$k]['category_list'] = $this->get_cmsblogCategoryMapping($filter);
					
					$filter['currentCategoryIdList'] = $current_catList;
					$arrResult[$k]['category_show'] = $this->get_cmsblogCategoryMapping($filter);
					
					$arrResult[$k]['labelTag_list'] = $this->getLabelTag_info(9,$v['content_id']);
				}
			}else{
				$arrResult = false;
			}
			$this->tppymemcached->set($cache_key, $arrResult,120);
		}
		return $arrResult;		
	}
	public function get_cmsblogCategoryMapping($arrFilter = array()) 
	{
		$DBSelect = $this->load->database('select', TRUE);
		
		$criteria = "";
		
		$content_id = $arrFilter['content_id'];
		if($content_id!=null){
			$criteria .= " and (cat_map.content_id=$content_id)";
		}
		$cat_name_th = $arrFilter['cat_name_th'];
		if($cat_name_th!=null){
			$criteria .= " and (cms_cat.category_name_th='".$cat_name_th."' or cms_cat.category_name_th like '%".$cat_name_th."%')";
		}
		$cat_id = $arrFilter['menu'];
		if($cat_id!=null){
			$criteria .= " and cat_map.content_type=2 and cat_map.category_id in ($cat_id)";
		}
		$current_catList = $arrFilter['currentCategoryIdList'];
		if($current_catList!=null && $current_catList!=""){
			$criteria .= " and cat_map.category_id in ($current_catList)";
		}
		$current_zoneList = $arrFilter['currentZoneIdList'];
		if($current_zoneList!=null && $current_zoneList!=""){
			$criteria .= " and cat_map.category_id in (select category_id from cmsblog_category where zone_id in ($current_zoneList))";
		}
		$zone_id = $arrFilter['zone_id'];
		if($zone_id!=null && $zone_id!=""){
			$criteria .= " and cat_map.category_id in (select category_id from cmsblog_category where zone_id in ($zone_id))";
		}
		
		
		if($arrFilter['moreCriteria'] && $arrFilter['moreCriteria']!=""){
			$criteria .= $arrFilter['moreCriteria'];
		}
		
		$order = $arrFilter['order'];
		if($order=='sort'){
			$orderby = " order by cat.sort_order asc";
		}
		elseif($order=='name'){
			$criticalCriteria = "";
			$orderby = " cat.category_name_th asc ";
		}
		else{
			$orderby = "";
		}
		
		
		$sql = "select distinct
					cat.category_id as category_id
					,cat.category_name_th as category_name_th
					,cat.category_name_code as category_name_url
					,cat.category_name_code_short as category_name_code_short
					,cat.direct_link as url_path
					,cat.deep_level
					,cat.category_parent_id as category_parent_id
					from cmsblog_category_mapping cat_map
					 inner join cmsblog_category cat on cat_map.category_id=cat.category_id
					where cat.status=1
					".$criteria.$criticalCriteria.$orderby; 
		
		$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
		$query = $DBSelect->query($sql);
		$DBSelect->query("COMMIT");
		//print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		if($query){
			$r = $query->result_array();
			foreach($r as $k=>$v){
				$filter = array();
				$filter['zone_id'] = $zone_id;
				$categoryID = $v['category_id'];
				$r[$k]['category_name_code_full'] = $this->get_cmsblogCategoryCode($content_id, $categoryID, $filter) ;
			}
			return $r;
		}else{
			return false;
		}
			
	}
	public function get_cmsblogCountContent($arrFilter = array()) 
	{
		$DBSelect = $this->load->database('select', TRUE);
		
		$criteria = "";
		
		$cat_id = $arrFilter['menu'];
		if($cat_id!=null){
			$criteria .= " and C.category_id in ($cat_id)";
		}
		$zone_id = $arrFilter['zone_id'];
		if($zone_id!=null){
			$criteria .= " and A.zone_id in ($zone_id)";
		}
		
		
		if($arrFilter['moreCriteria'] && $arrFilter['moreCriteria']!=""){
			$criteria .= $arrFilter['moreCriteria'];
		}
		
		$sql_inside = "SELECT 
					A.category_id, A.category_name_th, A.category_name_code, A.child_category_id_list, A.zone_id
				  , B.category_id p_category_id, B.category_name_th p_category_name_th, B.category_name_code p_category_name_code
				  , COUNT(DISTINCT(D.idx)) content_count
				  , count(distinct(D.create_user_id)) count_addBy
				  , IFNULL(SUM(D.view_count), 0) sum_view_count
				  FROM cmsblog_category A
				  LEFT OUTER JOIN cmsblog_category B ON B.category_id=A.category_parent_id
				  LEFT OUTER JOIN cmsblog_category_mapping C ON C.category_id=A.category_id AND content_type=2
				  LEFT OUTER JOIN cmsblog_detail D ON D.idx=C.content_id AND D.record_status = 1 
				  Where 1=1 ".$criteria."
				  GROUP BY A.category_id
				  ORDER BY A.category_parent_id, A.category_id";
		
		$sql = "select 
								ifnull(sum(AA.content_count),0) total_content
								,ifnull(sum(AA.count_addBy),0) total_addBy
								,ifnull(sum(AA.sum_view_count),0) total_viewcount
							from (".$sql_inside.") AA";

		// $query = $DBSelect->query($sql);
		// //print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		// if($query){
			// $r['total_content_count'] = $DBSelect->query($cover_sql)->row()->total_content;
			// $r['total_user_count'] = $DBSelect->query($cover_sql)->row()->total_addBy;
			// $r['total_view_count'] = $DBSelect->query($cover_sql)->row()->total_viewcount;
			// $r['category_list'] = $query->result_array(); 
			// return $r;
		// }else{
			// return false;
		// }
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$r = $this->tppymemcached->get($cache_key)) {
			$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql);
			$DBSelect->query("COMMIT");
			if($query){
				$r['total_content_count'] = $query->row()->total_content;
				$r['total_user_count'] = $query->row()->total_addBy;
				$r['total_view_count'] = $query->row()->total_viewcount;
				$r['category_list'] = $DBSelect->query($sql_inside)->result_array(); 
			}else{
				$r = false;
			}
			$this->tppymemcached->set($cache_key, $r,60);
		}		
		return $r;
	}
	
	public function get_cmsblogCategoryInfo($category_id, $arrFilter = array()) 
	{
		$DBSelect = $this->load->database('select', TRUE);
		
		$criteria = "";
		
		if($category_id!=null){
			$criteria .= " and (cat.category_id in ($category_id))";
		}
		if($arrFilter['ignore_category_id'] && $arrFilter['ignore_category_id']!=""){
			$criteria .= " and (cat.category_id not in ('".$arrFilter['ignore_category_id']."'))";
		}
		if($arrFilter['menuCode'] && $arrFilter['menuCode']!=""){
			$criteria .= " and (cat.category_name_code='".$arrFilter['menuCode']."')";
		}
		if($arrFilter['deep_level'] && $arrFilter['deep_level']!=""){
			$criteria .= " and (cat.deep_level=".$arrFilter['deep_level'].")";
		}
		if($arrFilter['zone_id'] && $arrFilter['zone_id']!=""){
			$criteria .= " and (cat.zone_id=".$arrFilter['zone_id'].")";
		}
		
		if($arrFilter['moreCriteria'] && $arrFilter['moreCriteria']!=""){
			$criteria .= $arrFilter['moreCriteria'];
		}
		
		$order = $arrFilter['order'];
		if($order=='deep'){
			$orderby = " order by deep_level asc,category_parent_id asc";
		}
		elseif($order=='name'){
			$orderby = " cat.category_name_th asc ";
		}
		elseif($order=='manual'){
			$orderby = $arrFilter['manual_order'];
		}
		else{
			$orderby = " order by cat.sort_order asc";
		}
		
		
		$sql = "select *
						,(select count(distinct(idx)) from cmsblog_detail where record_status=1 and idx in (select content_id from cmsblog_category_mapping where category_id=cat.category_id)) count_content
						,(select count(distinct(create_user_id)) from cmsblog_detail where record_status=1 and idx in (select content_id from cmsblog_category_mapping where category_id=cat.category_id)) count_blogger
					from cmsblog_category cat
					where cat.status=1
					".$criteria.$orderby; 

		// $query = $DBSelect->query($sql);
		// //print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		// if($query){
			// $r = $query->result_array();
			// return $r;
		// }else{
			// return false;
		// }
		
		$cache_key = $sql;
		// $this->tppymemcached->delete($cache_key);
		if (!$r = $this->tppymemcached->get($cache_key)) {
			$query = $DBSelect->query($sql);
			if($query){
				$r = $query->result_array();
			}else{
				$r = false;
			}
			$this->tppymemcached->set($cache_key, $r,300);
		}		
		return $r;
			
	}
	public function get_cmsblogCategoryCode($cmsblogID = 0, $categoryID = 0, $arrFilter = array()) 
	{
		$r = "";
		$arrResult = array();
		$pipe = "-";
		
		$cmsblogID = (int) $cmsblogID;
		$categoryID = (int) $categoryID;
		$zone_id = $arrFilter['currentZoneIdList'];
		
		$cache_key = "cmsblogCategoryCode_".$cmsblogID."+".$categoryID."+".$zone_id;
		// $this->tppymemcached->delete($cache_key);
		if (!$r = $this->tppymemcached->get($cache_key)) {
		
				if($categoryID<1){
					// get all
					if($cmsblogID>1){
						$sql= "select (category_id) as cat_id from cmsblog_category_mapping where content_type=2 and content_id=".$cmsblogID;
						if($zone_id!=null || $zone_id!="")
						{
							$sql .= " and category_id in (select category_id from cmsblog_category where zone_id in (".$zone_id."))";
						}
						$arr1 = $this->db->query($sql);
						if($arr1){
							$arr_cat_id = $arr1->result_array();
							foreach ($arr_cat_id as $k => $v ){
								$this_cat_id = $v['cat_id'];
								$arrResult = array_merge($arrResult,$this->_getArray_cmsblogCategoryCode_recursive($this_cat_id));
							}
							$arrResult = array_unique($arrResult);
						}
						
					}else{
						return 'ERR1';
					}
				}else{
					// get only this cat
					$filter['menu'] = $categoryID;
					$filter['content_id'] = $cmsblogID;
					$arrResult = $this->_getArray_cmsblogCategoryCode_recursive($categoryID);
				}
				
				$r = $pipe.implode($pipe,$arrResult).$pipe;
				if($r==$pipe || ($r==$pipe.$pipe)){ $r="";}
				
				
				$this->tppymemcached->set($cache_key, $r,60000);
		}
		
		return $r;
	}
	private function _getArray_cmsblogCategoryCode_recursive($category_id){
		$arrReturn = array();
		$arr1 = $this->get_cmsblogCategoryInfo($category_id);
		if($arr1){
			$this_cat_id = $arr1[0]['category_parent_id'];
			$arrReturn[] = $arr1[0]['category_name_code_short'];
			$deep_level = (int)$arr1[0]['deep_level'];
			for($i=$deep_level;$i>1;$i--){
				$arr2 = $this->get_cmsblogCategoryInfo($this_cat_id);
				if($arr2){
					$this_cat_id = $arr2[0]['category_parent_id'];
					$arrReturn[] = $arr2[0]['category_name_code_short'];
				}
			}
		}
		return $arrReturn;
	}
	// -- cmsblog : end
	
	// -- Blog : start
	public function get_content_summary($arrFilter=array()){
		
		$DBSelect = $this->load->database('select', TRUE);
		
		$criteria = ""; $orderby = ""; $sqllimit = ""; $filter = array();
		
		$filter['menu'] = $arrFilter['menu'];
		$filter['zone_id'] = $arrFilter['zone_id'];
		
		$sumType = $arrFilter['sumType'];
		if($sumType=='daily'){
			$criteria .= " and cms.idx in (select idx from cmsblog_detail where start_date = CURDATE())";
		}elseif($sumType=='weekly'){
			$criteria .= " and cms.idx in (select idx from cmsblog_detail where start_date >= CURDATE() - INTERVAL 7 DAY)";
		}// or sumType='all' 
		
		$lastXdays = $arrFilter['lastXdays'];
		if($lastXdays!=null && (int)$lastXdays>0){
			$criteria .= " and cms.idx in (select idx from cmsblog_detail where start_date >= CURDATE() - INTERVAL $lastXdays DAY)";
		}
		
		// -- order by
		$orderby = "view_desc";
		
		// -- limit
		$limit = $arrFilter['limit'];
		$offset = $arrFilter['offset'];
		$filter['moreCriteria'] = $criteria . $arrFilter['moreCriteria'];
		
		$arrResult = $this->get_cmsblogList($limit, $offset, $orderby, $filter);
		return $arrResult;
	}
	public function get_blogger_summary($arrFilter=array()){
		
		$DBSelect = $this->load->database('select', TRUE);
		
		$criteria = ""; $orderby = ""; $sqllimit = "";
		
		$cat_id = $arrFilter['menu'];
		if($cat_id!=null){
			$criteria .= " and cat_map.category_id in ($cat_id)";
		}
		
		$sumType = $arrFilter['sumType'];
		if($sumType=='daily'){
			$criteria .= " and cms.idx in (select idx from cmsblog_detail where start_date = CURDATE())";
		}elseif($sumType=='weekly'){
			$criteria .= " and cms.idx in (select idx from cmsblog_detail where start_date >= CURDATE() - INTERVAL 7 DAY)";
		}// or sumType='all' 
		
		$lastXdays = $arrFilter['lastXdays'];
		if($lastXdays!=null && (int)$lastXdays>0){
			$criteria .= " and cms.idx in (select idx from cmsblog_detail where start_date >= CURDATE() - INTERVAL $lastXdays DAY)";
		}
		
		$zone_id = $arrFilter['zone_id'];
		if($zone_id!=null && (int)$zone_id>0){
			$criteria .= " and cat_map.category_id in (select category_id from cmsblog_category where zone_id in ($zone_id))";
		}
		
		if($arrFilter['moreCriteria'] && $arrFilter['moreCriteria']!=""){
			$criteria .= $arrFilter['moreCriteria'];
		}
		
		// -- order by
		$orderby = " view_count desc";
		
		// -- limit
		$limit = $arrFilter['limit'];
		if($limit==null){
			$limit = 5;
		}
		$offset = $arrFilter['offset'];
		if($offset==null){
			$offset = 0;
		}
		$sqllimit = " limit ".$offset.",".$limit;
		
		$sql = "select
					cms.create_user_id as user_id
					,u.psn_display_name as user_displayname
					,u.psn_display_image as user_image
					,COUNT(DISTINCT(cms.idx)) AS sum_contentcount
					,SUM(cms.view_count) as sum_viewcount
					,COUNT(DISTINCT(f.idx)) sum_followercount
					-- ,cms.idx as content_id
					,'0' as balloon_text
				from cmsblog_detail cms
				inner join cmsblog_category_mapping cat_map on cat_map.content_id = cms.idx AND cat_map.content_type = 2
				inner join users_account u on cms.create_user_id=u.user_id
				left join cmsblog_follow f on cms.create_user_id=f.user_id
				where cms.record_status=1 ".$criteria."
				group by cms.create_user_id
				order by ".$orderby.$sqllimit;
		
		//echo $sql;
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql);
			$DBSelect->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = array();
			}
			$this->tppymemcached->set($cache_key, $arrResult,60);
		}		
		return $arrResult;
	}
	// -- Blog : end
	
	public function getLabelTag_info($project_id=0,$content_id=0,$labeltag_id=null,$label_key=null){
		$project_id = (int)$project_id;
		$content_id = (int)$content_id;
		if($project_id==0){ return array('error'=>'pls define project_id');	}
		$DBSelect = $this->load->database('select', TRUE);
		$criteria = "";
		if($label_key!=null && $label_key!=""){
			$criteria .= " and lt.label_key in ($label_key)";
		}
		if($labeltag_id!=null && $labeltag_id!=""){
			$criteria .= " and lt.id in ($labeltag_id)";
		}
		
		$sql = "select
					lt.id as labelTag_id
					,lt.label_name as labelTag_name
					,lt.label_key as labelTag_key
					,tc.meaning as labelTag_Group
					,ltm.idx as map_id
					,ltm.project_id as map_project_id
					,ltm.content_id as map_content_id
					,ltm.addDateTime as map_dateTime
					from label_tag_map ltm
					inner join label_tag lt on ltm.label_tag_id=lt.id
					inner join tb_code tc on tc.tableName='LABEL_TAG' and tc.fieldName='LABEL_KEY' and lt.label_key=tc.fieldValue
					where ltm.project_id=$project_id and ltm.content_id=$content_id 
					".$criteria."
					order by lt.label_key";
		
		// $query = $DBSelect->query($sql);
		//print_r($DBSelect->queries); echo '<br><br>----<br>'; //exit();
		// if($query){
			// $r = $query->result_array();
			// return $r;
		// }else{
			// return false;
		// }
		
		$cache_key = $sql;
		//$this->tppymemcached->delete($cache_key);
		if (!$arrResult = $this->tppymemcached->get($cache_key)) {
			$DBSelect->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");
			$query = $DBSelect->query($sql);
			$DBSelect->query("COMMIT");
			if($query){
				$arrResult = $query->result_array();
			}else{
				$arrResult = array();
			}
			$this->tppymemcached->set($cache_key, $arrResult,120);
		}		
		return $arrResult;
		
	}
}	