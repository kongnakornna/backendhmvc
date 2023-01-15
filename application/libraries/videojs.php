<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CMS Canvas
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://cmscanvas.com
 */

class VideoJS {
  /**
  * @example
      $vd = array (
        'id'=>'html_video_id',
        'width'=>640,
        'height'=>264,
        'thumb'=> 'http://www.graham-kendall.com/twitter/images/GXK/graham.jpg',
        'data' => array(
          array('url'=>'http://static.trueplookpanya.com/trueplookpanya/data/product/truespiritnews/video/video_1.mp4', 'resolution'=>720),
          array('url'=>'http://static.trueplookpanya.com/trueplookpanya/data/product/truespiritnews/video/video_3.mp4', 'resolution'=>480),
          array('url'=>'http://static.trueplookpanya.com/trueplookpanya/data/product/truespiritnews/video/video_6.mp4', 'resolution'=>240),
        ),
        'logo'=>'/assets/jwplayer/logo_player.png'
        'logo_destination': 'http://www.trueplookpanya.com/'
      );
  */
  private $is_init = false;
  public function video_player($vdo_data=null ){
// _vd($vdo_data);
    $video_id = !empty($vdo_data['id']) ? $vdo_data['id'] :  'video_id_'.time();
    $width = !empty($vdo_data['width']) ? $vdo_data['width'] : '640';
    $height = !empty($vdo_data['height']) ? $vdo_data['height'] : '480';
    $video_thumb = !empty($vdo_data['thumb']) ? $vdo_data['thumb'] : '';
    $video_url = !empty($vdo_data['data']) ? $vdo_data['data'] : array();
    $logo = !empty($vdo_data['logo']) ? $vdo_data['logo'] : '/assets/jwplayer/logo_player.png';
    $logo_destination = !empty($vdo_data['logo_destination']) ? $vdo_data['logo_destination'] : 'http://www.trueplookpanya.com/';
    $defaullt_res = !empty($vdo_data['default_res']) ? "default_res:'".$vdo_data['default_res']."'": ''; 
  
  $result="";
  
  if(!defined("VIDEOJS_PLAYERR")){
    // echo "
   
    // <script src='//code.jquery.com/jquery-1.11.3.min.js'></script>
    // <script src='".site_url('/assets/video-js/video.js')."'></script>
    // <script src='".site_url('/assets/video-js/videojs.logobrand.js')."'></script>  
    // <script src='".site_url('/assets/video-js/video-quality-selector.js')."'></script>
    // <script src='".site_url('/assets/video-js/videojs-playlists.js')."'></script>

    // <link href='".site_url('/assets/video-js/video-js.css')."' rel='stylesheet' type='text/css'>
    // <link href='".site_url('/assets/video-js/videojs.logobrand.css')."' rel='stylesheet' type='text/css'>
    // <link href='".site_url('/assets/video-js/video-quality-selector.css')."' rel='stylesheet' type='text/css'>
    // <link href='".site_url('/assets/video-js/vsg-skin.css')."' rel='stylesheet' type='text/css'>
    // <script src=\"https://cdn.rawgit.com/videojs/videojs-youtube/master/src/Youtube.js\"></script>
    // ";
    

  $result.= '<!-- VIDEOJS CSS  -->
    <link rel="stylesheet" media="screen" href="http://vjs.zencdn.net/5.4.6/video-js.min.css">
    <!-- VSG Skin  -->
    <link rel="stylesheet" media="screen" href="vsg-skin.css">
    
    <!-- VIDEOJS JS -->
    <script src="http://vjs.zencdn.net/5.4.6/video.min.js"></script>
    <script src="https://cdn.rawgit.com/videojs/videojs-youtube/master/src/Youtube.js"></script>
    ';
    // $result.= " <link href='".site_url('/assets/video-js/vsg-skin.css')."' rel='stylesheet' type='text/css'>";
    
    // $this->is_init = true;
    define("VIDEOJS_PLAYERR", TRUE);
  }
$result.= '
  <style type="text/css" media="screen">
    .video-js .vjs-big-play-button {
        top: 47% !important;
        left: 50% !important;
        margin-left: -1em;
        width: 3em !important;
        border: none;
        color: #fff;
        -webkit-transition: border-color .4s, outline .4s, background-color .4s;
        -moz-transition: border-color .4s, outline .4s, background-color .4s;
        -ms-transition: border-color .4s, outline .4s, background-color .4s;
        -o-transition: border-color .4s, outline .4s, background-color .4s;
        transition: border-color .4s, outline .4s, background-color .4s;
        background-color: rgba(0, 0, 0, .5);
        font-size: 3em !important;
        border-radius: 50% !important;
        height: 3em !important;
        line-height: 3em !important;
        margin-top: -1em !important
    }

    .video-js .vjs-big-play-button:before, .video-js .vjs-control:before, .video-js .vjs-modal-dialog, .vjs-modal-dialog .vjs-modal-dialog-content{
      font-size: 2em;
    }
  </style>
  ';

  $result.= "
    <video id='$video_id' class='video-js vjs-fluid vjs-default-skin' 
    width='$width' height='$height' controls preload='none' 
    data-setup='{\"customControlsOnMobile\": true, \"controls\": true, \"autoplay\": false, \"preload\": \"auto\"}'
    poster='$video_thumb'>";
    if(is_array($video_url)) {
      foreach ($video_url as $k=>$v) {
        $result.= "<source src='".$v['url']."' type='video/mp4'  data-res='".$v['resolution']."'  />";
      }
    }else{
      $result.= "<source src='$video_url' type='video/mp4' />";
    }
    $result.= "</video> 
    <script>
      videojs('#$video_id',{} , function() {
        // var player = this;
        // alert('ds');
        // this.relatedCarousel([
          // { imageSrc: '/assets/img-ex-ku/arrow-down.png', url: '/video1-url.html', title: 'video 1 title' },
          // { imageSrc: '/assets/img-ex-ku/clock-off.png', url: '/video2-url.html', title: 'video 2 title' }
        // ]);
      })
      .logobrand({
        image: '$logo',
        destination: '$logo_destination'
      })
      .resolutionSelector({ $defaullt_res },function() {
        this.on( 'changeRes', function() {
          console.log( 'Current Res is: ' + player.getCurrentRes() );
        });
      });
    </script>
    
    
    ";
    return $result;
  }
  
    public function convert_json($json_c){
    $CI =&get_instance();
    $video_data=json_decode($json_c); 
    $data=array();
    if($video_data){
      foreach($video_data->video as $conv){
        $data[] = array(
          'resolution'=>$conv->quality,
          'url'=>str_replace($CI->config->item('static_path'), $CI->config->item('static_url'), $conv->output_file),
        );
      }
    }
    return $data;
  }

  public function skin_video($title = null, $vdo_data = null){
    $data = '<center><b>' . $title . '</b></center>';
    $data .= $this->video_player($vdo_data);
    return $data;
  }
  
}


