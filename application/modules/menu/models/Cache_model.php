<?php
class Cache_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
		//$this->load->database();
		$this->cache_file = 'json/chart/';
		$this->lang = 'th';
		$this->folder = '';
    }

	function chk_cachefile($cache_file){
		//echo 'check '.$this->cache_file.$cache_file.'.json<br>';
		//@unlink($this->cache_file.$cache_file.'.json');
		if(!file_exists($this->cache_file.$cache_file.'.json')){  
			$result = TRUE;
		}else{
			$result = FALSE;	
		}
		
		return $result;
	}
	//////////////////////////////////////
	function set_cachefile($cache_file, $data){
		
		if(file_exists($this->cache_file.$cache_file.'.json')){
				unlink($this->cache_file.$cache_file.'.json');
		}

		$edata = json_decode($data);
		//unset($data);
		//debug($edata);
		$total_rows = sizeof($edata->body);

		$header = array(
				'resultcode' => 200,
				'message' => 'success',
				'total_rows' => $total_rows,
		);
		
		$rdata["header"] = $header;
		//$rdata["body"] = $edata;

		//debug($edata);
		//$rdata["header"] = $edata->header;
		$rdata["body"] = $edata->body;

		//$rdata["remak"] = "cache_file";
		$code = $header['resultcode'];
		$data = json_encode($rdata);
		//debug($data);
		
		if($code === 200){
			file_put_contents($this->cache_file.$cache_file.'.json',$data);
			//SaveJSON($data, $cache_file, 0, 'chart/');
		}
	}

	function set_cachefile_data($cache_file, $data){
		
		if(file_exists($this->cache_file.$cache_file.'.json')){
				unlink($this->cache_file.$cache_file.'.json');
		}

		$total_rows = sizeof($data);
		$header = array(
				'resultcode' => 200,
				'message' => 'success',
				'total_rows' => $total_rows,
		);
		
		$rdata["header"] = $header;
		$rdata["body"] = $data;

		//$rdata["remak"] = "cache_file";
		$code = $header['resultcode'];
		$data = json_encode($rdata);
		//debug($data);
		
		if($code === 200){
			file_put_contents($this->cache_file.$cache_file.'.json',$data);
			//SaveJSON($data, $cache_file, 0, 'chart/');
		}
	}	
	//////////////////////////////////////
	public function get_cachefile($cache_file){
		
		if(file_exists($this->cache_file.$cache_file.'.json')){
			return file_get_contents($this->cache_file.$cache_file.'.json');
		}else{
			return "file not found";
		}
	}
	
	////////////////////////////////////
	public function all_delete($mask){
		
	    chdir('json/chart');
   		array_map( "unlink", glob( $mask ) );	
		
	} 
}
?>	
