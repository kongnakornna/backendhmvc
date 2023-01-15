<?php
$this->load->view('template/header-smart');  
#start: PAGE CONTENT  
################################## Body Content ################################
if(isset($content_view) && !isset($content_data)){$this->load->view($content_view); 
	}if(isset($content_view) && isset($content_data)){
		foreach($content_data as $key =>$value){$data[$key] = $value;}
		$this->load->view($content_view,$data);
	}
################################## Body Content ################################
#end:PAGE CONTENT 
$this->load->view('template/footer-smart');
//notification
$notification=$this->config->item(notification);
if($notification==1){
   $segmentna=$this->uri->segment(1);
   $segmentna2=$this->uri->segment(2);
 if($segmentna=='dashboard' || $segmentna=='Dashboard'||$segmentna2=='dashboard' || $segmentna2=='Dashboard'){
   $this->load->view('template/notification');
 }
}


?>
