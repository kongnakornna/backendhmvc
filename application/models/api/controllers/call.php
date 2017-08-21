<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/Requests/Requests.php";
class Call extends CI_Controller {
    public function __construct()    {
        parent::__construct();
			 $this->load->helper('url'); 
			 $this->load->helper('file'); 
			 header('Content-Type: text/html; charset=utf-8');
        // include Requests
	 	Requests::register_autoloader();  
    }
    public function index(){  
			$apiurl=base_url();
			$url=$apiurl.'api/center/examinationlistall?exam_id=&limit=100&exam_name=';
			#echo $url;
			$request = Requests::get($url, array('Accept' => 'application/json'));
			$search=" ";$replace="";$string=$request;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request);  Die();
			$request=$request->body;
			$data_json_encode=$request;
			//print_r($data_json_encode); Die(); 
			$json_data = json_decode($request, true);
?>
	       <br> ตัวอย่าง  การใช้งาน  ด้วย CodeIgniter  --> https://codeigniter.com/ และ  Libery Requests --> https://github.com/kennethreitz/requests <br>
		   
require_once APPPATH."/third_party/Requests/Requests.php"; <br>
class Call extends CI_Controller { <br>
    public function __construct()    { <br>
        parent::__construct(); <br>
			 $this->load->helper('url');  <br>
			 $this->load->helper('file');  <br>
			 header('Content-Type: text/html; charset=utf-8'); <br>
        // include Requests <br>
	 	Requests::register_autoloader();   <br>
		   
			$apiurl=base_url(); <br>
			$url=$apiurl.'api/center/examinationlistall?exam_id=&limit=100'; <br>
			#echo $url; <br>
			$request = Requests::get($url, array('Accept' => 'application/json')); <br>
			$search=" ";$replace="";$string=$request; <br>
			//$request =str_replace($search,$replace,$string);  <br>
			// Check what we received <br>
			#var_dump($request);  Die(); <br>
			$request=$request->body; <br>
			$data_json_encode=$request; <br>
			//print_r($data_json_encode); Die();  <br>
			$json_data = json_decode($request, true); <br>
			
			<hr>ผลที่ได้มาจาก API <hr>
 
<?php
			 echo '<pre>'; print_r($json_data); echo '</pre>'; Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			if($status_code<>200){echo ' Code '.$status_code.' Status '.$status.' Can not call API => '; ?><a href="<?php echo  $url;?>"target="_blank"><?php echo $url;?> </a><?php Die();}
			 $remarks=$json_data['remarks'];
			 $items_data=$json_data['data'];
             $count = count($items_data);
			$data = array(
						"urlapi" => $url,
                        "Title"=> 'Api',
                        "count" => $count,
						"data" => $items_data,	
                        "content_view" => 'Api Rest',
			); 
           echo '<pre>'; print_r($data); echo '</pre>';  Die();
    }  
    
	    
    
    
} 