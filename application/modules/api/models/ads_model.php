<?php if(!defined('BASEPATH'))exit('No direct script access allowed');
class Ads_model extends CI_Model {
    function __construct(){
        parent::__construct();
         $this->load->helper(array('form', 'url'));
         //Load codeigniter FTP class
          $this->load->library('ftp');
    } 
    public function index(){
    	$datakookieads=$this->kookieads();
		return $datakookieads; 
	}

	    public function banner_ads() {
			$imagebanner=base_url().'assets/images/img-for-lite/banner_freenet.jpg';
			$image_close=base_url().'assets/images/img-for-lite/button_close.png';
			$device = $this->ads_model->get_device();
			// var_dump(isset($_COOKIE['trueplookpanya_freenet']), $_COOKIE['trueplookpanya_freenet'],$_SESSION['trueplookpanya_freenet'],$_REQUEST['trueplookpanya_freenet']);
			if($device==='mobile'  && !isset($_COOKIE['trueplookpanya_freenet'])){
						$html = '<style type="text/css" media="screen">
						 #banner-freenet{

						 }
						 .close-ads{
						     display: block;
						     float: right;
						     position: relative;
						     top: -5px;
						     right: 15px;
						     height: 0;
						     font-size: 44px;
						     font-weight: 900;
						 }
						</style>

						<div class="row">
						 <div class="col-xs-12" id="banner-freenet">
						     <a href="#" class="close-ads"><img src="'.$image_close.'" alt="freenet"></a>
						     <a href="http://www.trueplookpanya.com/freenet" target="_blank">
						      <img class="img-responsive" src="'.$imagebanner.'" style="width: 100%;">
						     </a>
						 </div>
						</div>
						<script src="'. base_url() .'assets/jquery-1.11.3.min.js"></script>
						<script type="text/javascript">
					 		$(".close-ads").click(function() {
					  			event.preventDefault();
						  		$.ajax({url: "'. site_url("/api/ads/cookieads") .'", success: function(result){
						             //alert(result);
									 $("#banner-freenet").hide();
						        }});
							});
						</script>'; 
					}else{
				$html = '';
			}
			return $html ;

	    }
    public function kookieads(){ 
         $cookie_name = 'trueplookpanya_freenet';
         $cookie_value = 'yes';
         //$timedata='86400';
         $timedata='10';
         $day='90';
        $time=$timedata*$day;

		if (!isset($_COOKIE[$cookie_name])) {
		// do something because one of the cookies were not set
		       setcookie($cookie_name, $cookie_value, time() + ($time), '/'); // 86400 = 1 day
		         echo 'set cookie'.$cookie_name.' cookie value '.$cookie_value.' Time '.$time.' hours';
				//setcookie('showPopup','yes',strtotime( '+1 days' )); // 24 hours
				//setcookie('showPopup','yes',time() + 86400); // 24 hours

		}
		if(isset($_COOKIE[$cookie_name])){
		     //echo 'Not show pop';
		   // $banner=$this->bannertop(); 
		   // $dataretrundata=array('banner'=>$banner,
					// 			 'cookie_name'=>$cookie_name,
					// 			 'cookie_value'=>$cookie_value,
					// 			 'cookieday'=>$day,
					// 			 'device'=>$deviceretun,
					// 			);
			$dataretrundata=true;
		     
		}else{           
		    //echo 'หมดอายุ cookie';
		    //setcookie('showPopup','yes',time() + 24 * 3600*$day);
		    $dataretrundata=false;
		}
		
/**
 
setcookie("token", "value", time()+60*60*24*100, "/");
setcookie("secret", "value", time()+60*60*24*100, "/");
setcookie("key", "value", time()+60*60*24*100, "/");
if (!isset($_COOKIE['token']) || !isset($_COOKIE['secret']) || !isset($_COOKIE['key'])) {
// do something because one of the cookies were not set

}

*/	
	  return $dataretrundata;   
    }
	public function get_device(){
			$this->load->library('user_agent');
	        if($this->agent->is_mobile('ipad')){
	            $device="desktop";
	        }else if($this->agent->is_mobile()){
	            $device="mobile";
	        }else{
	            $device="desktop";
	        }
	        
	        if($this->input->get_post("device") !== FALSE){
	            $device = $this->input->get_post("device");
	        }
	        ///// Fix Device ////
	        //$device ="mobile";
	        return $device;
	    }
	public function bannertop($urlclose=''){
		if($urlclose==Null){
			$urlclose = $this->input->get_post("urlclose");
		}
		    
			$bannertopurl='http://static.trueplookpanya.com/ads/banner_freenet.png';
			$urlsubmit='http://www.trueplookpanya.com/freenet';
			if($urlclose==Null){
				$urlclose='api/ads/banner?device=mobile';
			}
			$urlbanner='http://static.trueplookpanya.com/ads/banner_freenet.png';
			$html='OK';
			//$device=$this->device_model->get_device();
			$device=$this->get_device();
	        if($device === "desktop"){
	             $deviceretun='desktop';
	             $htmlcode=Null;
				
	        }else{
	            $deviceretun='mobile';
	            $htmlcode=$html;
	        }
			$bannertopdata=array('name'=>'Bannerfreenet',
								 'urlbanner'=>$urlbanner,
								 'urlsubmit'=>$urlsubmit,
								 'urlclose'=>$urlclose,
								 'codeads'=>$htmlcode,
								 'device'=>$deviceretun,
								);
	        return $bannertopdata;
	    }
}