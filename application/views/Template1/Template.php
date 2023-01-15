<?php
//?lang=english&uri=langs
//?lang=thai&uri=langs
$post=@$this->input->post(); 
$get=@$this->input->get();
$SESSION=$_SESSION;		
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
$data_var= array(
			 "SESSION" => $SESSION,
			 "segment1" => $segment1,
			 "segment2" => $segment2,
			 "segment3" => $segment3,
			 "segment4" => $segment4,
			 "segment5" => $segment5,
			 "segment6" => $segment6,
			 "segment7" => $segment7,
			 "segment7" => $segment7,
			 "segment8" => $segment8,
			 "segment9" => $segment9,
			 "segment10" => $segment10,
			); 
//echo '<pre>data_var=>'; print_r($data_var); echo '</pre>';	

$this->load->view('Template/Header');	
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
