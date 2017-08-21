<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Ads extends TPPY_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('api/ads_model');
    }

    public function index() {
		 echo 'ADS';

    }
   


    public function cookieads(){ 
         $cookie_name = 'trueplookpanya_freenet';
         $cookie_value = 'yes';
         $timedata='86400';
         //$timedata='60';
         $day='1';
        $time=$timedata*$day;

		if (!isset($_COOKIE[$cookie_name])) {
		// do something because one of the cookies were not set
		       $ck = setcookie($cookie_name, $cookie_value, time() + ($time), '/' ); // 86400 = 1 day
			   // var_dump( $ck );
		         // echo 'set cookie'.$cookie_name.' cookie value '.$cookie_value.' Time '.$time.' hours';
				echo $ck;
		}
		if(isset($_COOKIE[$cookie_name])){
			// print_r("true");
			$dataretrundata=true;

		}else{
			// print_r("false");
		    $dataretrundata=false;
		}

	  return $dataretrundata;
    }

}

?>