<?php
/*
$this->load->helper('Asset_helper');
$this->load->helper('Clipone_helper');
$this->load->helper('Common_helper');
$this->load->helper('Cache_helper');
$this->load->helper('Json_helper');
*/
$this->load->view('template/header-clip');	

#start: PAGE CONTENT  
################################## Body Content ################################
if(isset($content_view) && !isset($content_data)){$this->load->view($content_view); 
	}if(isset($content_view) && isset($content_data)){
		foreach($content_data as $key =>$value){$data[$key] = $value;}
		$this->load->view($content_view,$data);
	}
################################## Body Content ################################


#end:PAGE CONTENT 

$this->load->view('template/footer-clip');


?>
