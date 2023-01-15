<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';
class Call extends CI_Controller {
    public function __construct()    {
        parent::__construct();
			 $this->load->helper('url'); 
			 $this->load->helper('file'); 
			//$this->load->helper("debug");
        // include Requests
	 	Requests::register_autoloader(); 
         //$this->load->library('session'); 
		 
    }
    public function index(){  
			$apiurl=$this->config->config['restapi'];
			$jsondir=$this->config->config['jsondir'];
			$url=$apiurl.'Mapping/all?format=json';
			#echo $url;
			 
			
			$request = Requests::get($url, array('Accept' => 'application/json'));
			$search=" ";$replace="";$string=$request;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request);  Die();
			$request=$request->body;
			$data_json_encode=$request;
			//print_r($data_json_encode); Die(); 
			########
			$file_name='tb_code';
			$dir='./json/';
			$filename=$dir.$file_name.'.json';  
			//$json_files = "json/".date('y-m-d').".json";
			$this->load->helper('file');
			if ($filename!==''){@unlink($filename);} 
		    if (!write_file($filename, $data_json_encode)){ $filewrite='Unable to write the file';}else{$filewrite='File written!';}
			########
			$json_data = json_decode($request, true);
			//echo '<pre>'; print_r($json_data); echo '</pre>'; Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			if($status_code<>200){echo ' Code '.$status_code.' Status '.$status.' Can not call API => '; ?><a href="<?php echo  $url;?>"target="_blank"><?php echo $url;?> </a><?php Die();}
             
			 $remarks=$json_data['remarks'];
			 $items_data=$json_data['data'];
             $count = count($items_data);
			
			$data = array(
						"urlapi" => $url,
						"filewrite" => $filewrite,
						"tb" => 'tb_code',
                        "Title"=> 'Api',
                        "count" => $count,
						"data" => $items_data,	
                        "content_view" => 'Tb/Mul_leveltb_code',
			); 
           echo '<pre>'; print_r($data); echo '</pre>';  Die();
    }  
    
	    
    
    
} 