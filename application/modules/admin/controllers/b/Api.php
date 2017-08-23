<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Develop by kongnakorn  jantakun email kongnakornna@gmail.com Mobile +66857365371  Thailand */
/**
 * @copyright kongnakorn  jantakun 2015
*/
class Api extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}

	function index($limitstart=Null,$limitend=Null,$orderby=Null)
	{
			$apiurl=$this->config->config['api'];
			$url=urldecode($apiurl);
			$json = file_get_contents($url);
            $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $json);
			$contents = utf8_encode($json);
			$data = json_decode($contents, true); 
			
			$json=str_replace(" ","",$json);
			//Debug($json);
			 Print_r($json);
			 var_dump($data);
			echo '<hr> Api url=>'.$base_url.'<hr><br>';
            $candidate_id=$data[0]['candidate_id'];
		    echo 'candidate_id==>'.$candidate_id;
		    exit();
 
		 foreach ($data as $datalist){ // Foreach categories
				  $idn[] =  $datalist;
			}
		$id= $data['0'];  
		$countid=(int)count($idn); 
		for($i=0;$i < $countid;$i++){ 	
				$id=$data[$i];
				$candidate_id=$candidate_id[$i];
				echo 'candidate_id=>'.$candidate_id;
			}	
 
	}
	
	public function get_sub_model()
	{
		$model_id = intval($_GET['model_id']);
		if ( ! $model_id) {
			exit;
		}

		echo json_encode($this->Api_model->get_sub_model($model_id));
	}

}

/* End of file api.php */
/* Location: ./application/controllers/api.php */