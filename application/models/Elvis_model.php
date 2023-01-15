<?php
class Elvis_model extends CI_Model {
 	private $username = "chaipat";
 	private $password = "chaipat@inspire";
    public function __construct(){
		parent::__construct();
    }
    
    public function login($username = null, $password = null){    
    
    	$encode = base64_encode("{$this->username}:{$this->password}");
    	//$encode = base64_encode("{$username}:{$password}");
    	
    	$elvis_login = "http://elvis.siamsport.co.th/services/login?username=".$this->username."&password=".$this->password."";
    	
    	//echo $json_url;
    	$obj = file_get_contents($elvis_login);
    	$obj = json_decode($obj);
    	return $obj;
    
    }

    public function search($keyword = "สยามดารา", $start = 0, $num = 10){
    
	    	$test = explode(" ", $keyword);
	    	
	    	if(sizeof($test) > 1){
	    		//redirect(base_url('elvis/news/'.$id));
	    		//die();
	    		return false;
	    	}

			$extension = "extension:jpg";

			$multiextension = '(extension:jpg%20OR%20extension:png%20OR%20extension:tif%20OR%20extension:psd)';

			//$sort = "name";
			$sort = "relevance";
			$return = "all";
			$format = "json";
			$selection = "image";
			
			//$config = $this->config->load('config');
			//$password = $this->config->load('elvis_pass');
			//debug($config);
			//$encode = base64_encode("{$username}:{$password}");

			$encode = base64_encode("{$this->username}:{$this->password}");
			
			//debug($config);

			$json_url = "http://elvis.siamsport.co.th/services/search?q=".urlencode($keyword)."%20AND%20".$multiextension."&start=".$start."&num=".$num."&sort=".$sort."&metadataToReturn=".$return."&format=".$format."&facet.assetDomain.selection=".$selection."&authcred={$encode}";

			//echo $json_url;

			$obj = file_get_contents($json_url);
			$obj = json_decode($obj);
			return $obj;
    
    }

    public function download($ref_elvis, $folder = '', $ref_type = 1, $username = null, $password = null){
    	
    	$this->load->helper('img');
		$type_folder = '';

		switch($ref_type){
				case 1: $type_folder = 'article'; break;
				/*case 2: $type_folder = 'column'; break;
				case 3: $type_folder = 'gallery'; break;
				case 4: $type_folder = 'cilp'; break;
				case 5: $type_folder = 'dara'; break;*/
				default: $type_folder = 'article'; break;
		}
    	
    	if($folder == '') $folder = date('Ymd');
    	
    	$upload_tmppath = './uploads/tmp/'.$folder;
    	$upload_path = './uploads/'.$type_folder.'/'.$folder;
    	
    	$encode = base64_encode("{$this->username}:{$this->password}");    
    	$fname =  $type_folder.date("YmdHi") . rand(000, 999);
    	$newtmp_filename = $upload_tmppath.'/'.$fname.'.jpg';
		$new_filename = $upload_path.'/'.$fname.'.jpg';
		
		$src = fopen($ref_elvis."&authcred={$encode}", 'r');
		$dest1 = fopen($newtmp_filename, 'w');
		
		stream_copy_to_stream($src, $dest1);
		
		resize_img($newtmp_filename, $newtmp_filename, 1000);
		//Copy to Tmp
		
		$src2 = fopen($newtmp_filename, 'r');
		$dest2 = fopen($new_filename, 'w');
		stream_copy_to_stream($src2, $dest2);
		resize_img($new_filename, $new_filename, 1000);
		//Copy to news
		
		//Debug("from $ref_type ==> $upload_path/".$fname.".jpg");

		return $fname.'.jpg';
    
    }
    
}
?>	
