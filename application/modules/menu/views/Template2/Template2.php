<?php
$this->load->view('Template/Header2');	
$this->load->view('Template/Menu');	
#start: PAGE CONTENT  
################################## Body Content ################################
if(isset($content_view) && !isset($content_data)){$this->load->view($content_view); 
	}if(isset($content_view) && isset($content_data)){
		foreach($content_data as $key =>$value){$data[$key] = $value;}
		$this->load->view($content_view,$data);
	}
################################## Body Content ################################
#end:PAGE CONTENT 
$this->load->view('Template/Footer');


?>
