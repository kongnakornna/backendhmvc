<?php

class Banner_model extends CI_Model {

    private $DBSelect, $DBEdit;

    public function __construct() {
        parent::__construct();
    }
    
    function bannerV3($banner_id = 0,$limit = 9) {
      $key = "get_banner_v3_".$banner_id;
      $this->tppymemcached->delete($key);
      if ($data = $this->tppymemcached->get($key)) {
          return array_slice($data, 0, $limit);
      } else {
          $result = array();
          $query = $this->db
                  ->select('*')
                  ->from('cvs_banner')
                  ->where('banner_categories_id =', $banner_id)
                  ->where('status', 1)
                  ->order_by('sort', 'desc')
                  ->get();
          if ($query->num_rows() > 0) {
              $result = $query->result_array();
              foreach($result as $k=>&$v){
                $v['thumbnail']= get_file_url($v['image']);
              }
              $this->tppymemcached->set($key, $result);
          }
          return array_slice($result, 0, $limit);
      }
    }
	
	public function bannerV2($groupid = 0 ,$limit = 10)
	{
		$DBSelect = $this->load->database('select', TRUE);	
		$query = $this->db
                        ->select("b.banner_desc title, concat(('http://www.trueplookpanya.com/new/cutresize/re/746/226/'),(replace((b.banner_image_path),('/'),('-'))),('/'),(b.banner_image)) thumbnail ,b.banner_link link")
						->from("recommend_center_group_list r")
						->join("banner_center b","r.recommend_id=b.banner_id")
						->where("r.record_status",1)
						->where("b.record_status",1)
						->where("r.group_id",$groupid)
						->order_by("position_no","asc")
						->limit($limit)
                        ->get();
		if ($query->num_rows() > 0) {
                    return $query->result_array();
		}else{
			return false;
		}
	}
	
	public function admission_banner($banner = '') {
        $arr = [];
        switch ($banner) {
            case 'top':
                $arr['d']['title'] = "Admissions Home";
                $arr['d']['thumbnail'] = "http://static.trueplookpanya.com/tppy/app/images/banner_admissions.png";
                $arr['d']['url'] = "";
				
				// $arrBanner[0]['title'] = "Admissions Home";
                // $arrBanner[0]['thumbnail'] = "http://static.trueplookpanya.com/tppy/app/images/banner_admissions.png";
                // $arrBanner[0]['url'] = "";
				
				// $arrBanner[1]['title'] = "Admissions Gang";
                // $arrBanner[1]['thumbnail'] = "http://static.trueplookpanya.com/tppy/banner/banner/57a1c6fbe770a966853312.png";
                // $arrBanner[1]['url'] = "";
				
                return $arr;
                break;
			case 'topV3':
				$arrBanner=$this->bannerV3(13, 5);
				foreach ($arrBanner as $k=>$v){
					$arr[$k]['title'] = $v['title'];
					$arr[$k]['thumbnail'] = 'http://static.trueplookpanya.com/tppy/'.$v['image'];
					$arr[$k]['url'] = $v['link'];
				}
				
                return $arr;
                break;
            case 'search':
                $arr['d']['title'] = "ค้นหาข่าวรับตรง";
                $arr['d']['thumbnail'] = "http://static.trueplookpanya.com/tppy/app/images/banner_directapplynews.png";
                $arr['d']['url'] = "";
                return $arr;
                break;
            default :
                return null;
                break;
        }
    }
	
}