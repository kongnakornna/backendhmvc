<?php
if($this->uri->segment(1)=='calendar'|| $this->uri->segment(1)=='events'|| $this->uri->segment(1)=='basic'|| $this->uri->segment(1)=='activity_logsna'|| $this->uri->segment(1)=='databasemanage'|| $this->uri->segment(1)=='calerdarconjobs'|| $this->uri->segment(1)=='uploadplan'|| $this->uri->segment(1)=='hardware'|| $this->uri->segment(1)=='hardwareconfig'|| $this->uri->segment(1)=='hardwaretype'|| $this->uri->segment(1)=='hardwaretype'|| $this->uri->segment(1)=='carebase'|| $this->uri->segment(1)=='setingworktime'|| $this->uri->segment(1)=='setingeventalerm'|| $this->uri->segment(1)=='emailsetting'|| $this->uri->segment(1)=='smsseting'|| $this->uri->segment(1)=='sensormanage'|| $this->uri->segment(1)=='emailalertlog'|| $this->uri->segment(1)=='smsalertlog'|| $this->uri->segment(1)=='sensorlogs'|| $this->uri->segment(1)=='province'|| $this->uri->segment(1)=='amphur'|| $this->uri->segment(1)=='district'|| $this->uri->segment(1)=='village'|| $this->uri->segment(1)=='setingworktime'|| $this->uri->segment(1)=='alarmwaterleakreport'|| $this->uri->segment(1)=='apihw'|| $this->uri->segment(1)=='userlogs'|| $this->uri->segment(1)=='setting'|| $this->uri->segment(1)=='activity_logs'|| $this->uri->segment(1)=='sensorreport'||$this->uri->segment(1)=='alarmlogreport'|| $this->uri->segment(1)=='sensorconfig'|| $this->uri->segment(1)=='team'||$this->uri->segment(1)=='geography'||$this->uri->segment(1)=='countries'|| $this->uri->segment(1)=='report'|| $this->uri->segment(1)=='sensor'){ 
$this->load->view('template/header-clip');	
}else if($this->uri->segment(1)=='overview'|| $this->uri->segment(1)=='workflow'|| $this->uri->segment(1)=='floorplan'|| $this->uri->segment(1)=='control'|| $this->uri->segment(1)=='locationmonitor'|| $this->uri->segment(1)=='flot'){ 
$this->load->view('template/header-smart');  
}else{  
$this->load->view('template/header');
}

#start: PAGE CONTENT  
################################## Body Content ################################
if(isset($content_view) && !isset($content_data)){$this->load->view($content_view); 
	}if(isset($content_view) && isset($content_data)){
		foreach($content_data as $key =>$value){$data[$key] = $value;}
		$this->load->view($content_view,$data);
	}
################################## Body Content ################################


#end:PAGE CONTENT 
if($this->uri->segment(1)=='calendar'|| $this->uri->segment(1)=='events'|| $this->uri->segment(1)=='basic'|| $this->uri->segment(1)=='activity_logsna'|| $this->uri->segment(1)=='databasemanage'|| $this->uri->segment(1)=='calerdarconjobs'|| $this->uri->segment(1)=='uploadplan'|| $this->uri->segment(1)=='hardware'|| $this->uri->segment(1)=='hardwareconfig'|| $this->uri->segment(1)=='hardwaretype'|| $this->uri->segment(1)=='callsetup'|| $this->uri->segment(1)=='carebase'|| $this->uri->segment(1)=='setingworktime'|| $this->uri->segment(1)=='setingeventalerm'|| $this->uri->segment(1)=='emailsetting'|| $this->uri->segment(1)=='smsseting'|| $this->uri->segment(1)=='sensormanage'|| $this->uri->segment(1)=='emailalertlog'|| $this->uri->segment(1)=='smsalertlog'|| $this->uri->segment(1)=='sensorlogs'|| $this->uri->segment(1)=='province'|| $this->uri->segment(1)=='amphur'|| $this->uri->segment(1)=='district'|| $this->uri->segment(1)=='village'|| $this->uri->segment(1)=='setingworktime'|| $this->uri->segment(1)=='alarmwaterleakreport'|| $this->uri->segment(1)=='apihw'|| $this->uri->segment(1)=='userlogs'|| $this->uri->segment(1)=='setting'|| $this->uri->segment(1)=='activity_logs'|| $this->uri->segment(1)=='sensorreport' ||$this->uri->segment(1)=='alarmlogreport'|| $this->uri->segment(1)=='sensorconfig'|| $this->uri->segment(1)=='team'||$this->uri->segment(1)=='geography'||$this->uri->segment(1)=='countries'||$this->uri->segment(1)=='report'|| $this->uri->segment(1)=='sensor'){ 
$this->load->view('template/footer-clip');
}else if($this->uri->segment(1)=='overview' || $this->uri->segment(1)=='workflow'|| $this->uri->segment(1)=='control'|| $this->uri->segment(1)=='locationmonitor'){ 
$this->load->view('template/footer-smart');
}else if($this->uri->segment(1)=='floorplan' || $this->uri->segment(1)=='flot' ){ 
$this->load->view('template/footer-smart');
}else{ 
$this->load->view('template/footer');
}
?>
