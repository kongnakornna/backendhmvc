<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shorturl extends CI_Controller {

	function index(){

		echo "Start<br>";

		//$this->load->library('shorturl');
		if (! isset($this->shorturl)) {
            $this->load->library('shorturl');
        }

		echo "Load Class Shorturl success.";

	    /*echo "Start<br>";	    
		if($this->input->get('url')){
			$fullURL = $this->input->get('url');
			$make = 1;
		}else if($this->input->post('url')){
			$fullURL = $this->input->post('url');
			$make = 1;
		}else{
			$fullURL = 'http://www.siamdara.com';
			$make = 0;
		}

		if($make == 1){
				// Test: Shorten a URL
				Debug($this->googlapi);
				$shortDWName = $this->googlapi->shorten($fullURL);

				echo "shortDWName=".$shortDWName;

		}else{
				echo 'fail';
		}*/
	    
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */