<?php
$segment1=$this->uri->segment(1);
$segment2=$this->uri->segment(2);
$segment3=$this->uri->segment(3);
$segment4=$this->uri->segment(4);
$segment5=$this->uri->segment(5);
$segment6=$this->uri->segment(6);
$segment7=$this->uri->segment(7);
$segment8=$this->uri->segment(8);
$segment9=$this->uri->segment(9);
$segment10=$this->uri->segment(10);
if($this->uri->segment(2)!='control'){  
		$this->load->view('template/header-clip');	
	}else if($this->uri->segment(2)=='control'){ 
		$this->load->view('template/header-smart');  
	}else{  
		$this->load->view('template/header');
	}
#start: PAGE CONTENT  
################################## Body Content ################################
if(isset($content_view) && !isset($content_data)){
		$this->load->view($content_view); 
	}if(isset($content_view) && isset($content_data)){
		foreach($content_data as $key =>$value){
				$data[$key] = $value;
			}
		$this->load->view($content_view,$data);
	}
################################## Body Content ################################
#end:PAGE CONTENT 
if($this->uri->segment(2)!='control'){  
	$this->load->view('template/footer-clip');
}else if($this->uri->segment(2)=='control'){ 
	$this->load->view('template/footer-smart');
}else{ 
	$this->load->view('template/footer');
}

/*
//notification
$notification=$this->config->item(notification);
if($notification==1){
   $segmentna=$this->uri->segment(1);
   $segmentna2=$this->uri->segment(2);
 if($segmentna=='dashboard' || $segmentna=='Dashboard'||$segmentna2=='dashboard' || $segmentna2=='Dashboard'){
   $this->load->view('template/notification');
 }
}
*/

?>