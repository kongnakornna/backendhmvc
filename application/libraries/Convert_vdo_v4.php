<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Convert_vdo_v4 { 
  
  
  /*
  * params 
  * file_source --> [filename] : /data/product/trueplookpanya/www/static/trueplookpanya/data/product/truespiritnews/video/video_59.mp4
  * output_file_path --> [path video] : /data/product/trueplookpanya/www/static/trueplookpanya/data/product/truespiritnews/video/convert_video/
  * output_thumbnail_path --> [path thumbnail] : /data/product/trueplookpanya/www/static/trueplookpanya/data/product/truespiritnews/video/convert_thumb/
  * ref_id --> [uniqe id] : truelittlemonk#59
  * notification_url --> [callback when convert complete] : http://www.trueplookpanya.com/new/notify_convert/
  
  EX : 
  function testupload() {
    $this->load->library('convert_vdo_v4');
    $this->load->config('path');

    $config = $this->config->item('truespiritnews_attech_path');
    $this->full_path_true_spiritnews = $config;
    $config = $this->config->item('truespiritnews_attech_url');
    $this->full_url_true_spiritnews = $config;


    print_r($this->convert_vdo_v4->ConvertVideoFile(
    $this->full_url_true_spiritnews . 'truespiritnews/video/video_145.mp4',
    $this->full_url_true_spiritnews . 'truespiritnews/video/converted/',
    'truelittlemonk#59',
    'truelittlemonk'));
  }
  );
  
  */
  
  
  public function ConvertVideoFile($file_source, $output_file_path, $output_thumbnail_path, $ref_id, $notification_url='http://www.trueplookpanya.com/new/notify_convert/') {

    if(is_file ($file_source)) {
      $url = "http://mediaconverter.trueplookpanya.com/api";     
      $params = array(
        'ref_id' => $ref_id,
        'file_source' => $file_source,
        'output_file_path' =>$output_file_path,
        'output_thumbnail_path' =>$output_thumbnail_path,
        'notification_url' => $notification_url,
        'file_type' => 'media',
        'format' => 'json'
      );

      if(!is_dir($output_file_path)) { $umask = umask(0); mkdir( $output_file_path, 0777, true ); umask($umask); }
      if(!is_dir($output_thumbnail_path)) { $umask = umask(0);mkdir( $output_thumbnail_path, 0777, true );  umask($umask); }
      
      $opts = array(
        CURLOPT_CONNECTTIMEOUT=>120,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_TIMEOUT=>120,
        CURLOPT_POST  => 1,
        CURLOPT_POSTFIELDS=>$params,
        CURLOPT_URL=>$url,
      );
      
      $ch = curl_init();
      curl_setopt_array($ch, $opts);
      
      $result = curl_exec($ch);
      $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      curl_close($ch);

      $output = array(
        'result'=>$result,
        'response'=>$response,
      );

      return $output;
    }
    return null;
  }

}


?>