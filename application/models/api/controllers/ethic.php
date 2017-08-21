<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Ethic extends Public_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function genimg(){

    $val = $_GET['a'];
    $max = $_GET['b'];
    
    $image = imagecreatetruecolor(80, 80);
    $gray = imagecolorallocate($image, 128, 128, 128);
    $red = imagecolorallocate($image, 255, 0, 0);
    $transparency = imagecolorallocatealpha($image, 0, 0, 0, 127);
    $black = imagecolorallocatealpha($image, 0, 0, 0, 0);
     

    // Transparent Background
    imagealphablending($image, false);
    imagefill($image, 0, 0, $transparency);
    imagesavealpha($image, true);

    $degree  = (($max-$val) * 360 )/$max;
    $percent = round((($val*100)/$max) ,0);
    
    
    imagefilledarc($image, 40, 40, 78, 78, 0, 0, $gray, IMG_ARC_EDGED);
    imagefilledarc($image, 40, 40, 78, 78, $degree+270 , 270, $red, IMG_ARC_EDGED);
    imagefilledarc($image, 40, 40, 50, 50, 0, 0, $transparency, IMG_ARC_EDGED);
    $string = $percent;
    imagestring($image, 0, 30, 30, $string.' %',$black);
    

    imagepng($image);
    imagedestroy( $image );
  }
    
    public function news($order = 'last', $limit = 10) {
        $key = 'ethic_news_'.$order.'_'.$limit.'_'.date('Y-m-d');
        $json = $this->tppymemcached->get($key);
        if (!$json) {
            $this->load->model('ethic_model');
            $limit = ($limit > 100) ? 100 : $limit;
            $news = $this->ethic_model->getNews(86, '', $limit);
            $json['header']['title'] = 'ข่าวและกิจกรรมธรรมะ';
            $json['header']['link_all'] = base_url().'true/ethic_list.php?cms_category_id=86';
            $arr_news = array();
            if(isset($news) and $news) {
                foreach ($news as $v_news) {
                    $arr = array();
                    $arr['id'] = $v_news['cms_id'];
                    $arr['title'] = htmlspecialchars($v_news['cms_subject']);
                    $arr['thumbnail'] = get_vdo_thumb_url('http://www.trueplookpanya.com/data/' . $v_news['cms_year_path'] . '/media/' . $v_news['thumb_path'] . '/' . $v_news['image_filename_m'], $v_news['file_path'], $v_news['cms_file_id']);
                    $arr['link'] = 'http://www.trueplookpanya.com/true/ethic_detail.php?cms_id='.$v_news['cms_id'];
                    $arr_news[] = $arr;
                }
            }
            $json['data'] = $arr_news;
            $this->tppymemcached->set($key, $json, 259200);
        }
        header('Content-Type: application/json');
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    }
    
    public function vdo($order = 'last', $limit = 10) {
        $key = 'ethic_vdo_'.$order.'_'.$limit.'_'.date('Y-m-d');
        $json = $this->tppymemcached->get($key);
        if (!$json) {
            $this->load->model('ethic_model');
            $limit = ($limit > 100) ? 100 : $limit;
            $news = $this->ethic_model->getNews(72, '', $limit);
            $json['header']['title'] = 'ธรรมะวิดีโอ';
            $json['header']['link_all'] = base_url().'true/ethic_list.php?cms_category_id=72';
            $arr_news = array();
            if(isset($news) and $news) {
                foreach ($news as $v_news) {
                    $arr = array();
                    $arr['id'] = $v_news['cms_id'];
                    $arr['title'] = htmlspecialchars($v_news['cms_subject']);
                    $img = ($v_news['image_filename_m'] <> '') ? $v_news['image_filename_m'] : $v_news['image_filename_s'];
                    $arr['thumbnail'] = image_thumbnail($v_news['file_path'], $img,306, 172,'mcov');
                    $arr['link'] = 'http://www.trueplookpanya.com/true/ethic_detail.php?cms_id='.$v_news['cms_id'];
                    $arr_news[] = $arr;
                }
            }
            $json['data'] = $arr_news;
            $this->tppymemcached->set($key, $json, 259200);
        }
        header('Content-Type: application/json');
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    }
    
// บทความ ธรรมมะ 74
// นิทานธรรม 82
// ธรรมะวิดีโอ 72
// ปุจฉา-วิสัชนา  77
    public function vdoGetOne($category_list='74,82,72,77', $order='last', $limit=3)
    {
   
//die;
      $key = 'ethic_vdo_get_one_'.$order.'_'.$limit.'_'.date('Y-m-d');
      $this->tppymemcached->set($key, $data, 0);
      if(!$data=$this->tppymemcached->get($key))
      {
        $this->load->model('ethic_model');
        $category = explode(',', $category_list);
        $limit = ($limit > 100) ? 100 : $limit;

        foreach($category as $k=>$v)
        {
          $news = $this->ethic_model->getNews($v, 'cms.cms_id DESC', $limit);
          if(isset($news) and $news) {
                $arr_news = array();
                foreach ($news as $v_news) {
                    //var_dump($v_news);
                    $arr = array();
                    $arr['id'] = $v_news['cms_id'];
                    $arr['title'] = htmlspecialchars($v_news['cms_subject']);
                    if($v_news['file_type'] != '')
                    {
                      $img = ($v_news['image_filename_m'] <> '') ? $v_news['image_filename_m'] : $v_news['image_filename_s'];
                      $arr['thumbnail'] = image_thumbnail($v_news['file_path'], $img,306, 172,'mcov');
                    }
                    else
                    {
                      $arr['thumbnail'] = get_vdo_thumb_url('http://www.trueplookpanya.com/data/' . $v_news['cms_year_path'] . '/media/' . $v_news['thumb_path'] . '/' . $v_news['image_filename_m'], $v_news['file_path'], $v_news['cms_file_id']);
                    }
                    $arr['link'] = 'http://www.trueplookpanya.com/true/ethic_detail.php?cms_id='.$v_news['cms_id'];
                    $arr_news[] = $arr;
                }
            }
            $data[]= $arr_news;
        }
        $this->tppymemcached->set($key, $data, 259200);
      }
       foreach($data as $k=>$v)
      {
        shuffle($v);
       $c[]= array_slice($v, -1)[0];
      }
// echo '<pre>';
// var_dump($c);
// echo '</pre>';
      $json['header']['title'] = 'สื่อธรรมะจากทรูปลูกปัญญา';
      $json['header']['link_all'] = 'http://www.trueplookpanya.com/true/ethic.php';
      $json['data'] = $c;
      header('Content-Type: application/json');
      echo json_encode($json, JSON_UNESCAPED_UNICODE);
    }
}