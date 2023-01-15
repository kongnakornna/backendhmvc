<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nosql extends CI_Controller {
    public function __construct()    {
        parent::__construct();
       /* if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }*/
    }

	public function index(){

			//header('Access-Control-Allow-Origin: *');
			//header('Content-Type: text/html  charset=utf-8');

			$this->load->library("Nosql_Redis");
			echo "Start Redis.<br>";

			try{
					//$keyrand = "TestKey".rand(111,999);
					$keyrand = "TestKey123";

					$key = (isset($_REQUEST["key"]))?$_REQUEST["key"]:$keyrand;
					echo "Key: " . $key."<br>";					
					$redisObj = $this->nosql_redis->openRedisConnection();
				  
					$value = $redisObj->get("key");
					if(!$value){
							$redisObj->setex('key', 60, 'value' );
							$value = $redisObj->get("key");
							echo "Set & Get Key ".$value;
					}else
							echo "Get Key ".$value;

					echo "<br>";
					var_dump($value);

					//$val = $redisObj->del( $key );				
					//echo "isKey" . $redisObj->exists ( $key );

			}catch( Exception $e ){ 
					echo "<br>".$e->getMessage(); 
			} 
	}

	public function test(){

			//header('Access-Control-Allow-Origin: *');
			//header('Content-Type: text/html  charset=utf-8');			
			echo "Start Redis.<br>";
			if($this->config->config['use_redis'] == true){

					$this->load->library("Nosql_Redis", "redis");
					echo "Use.<br>";
					$hostName = $this->config->config['redis_servers'][0]['host'];
					$port = $this->config->config['redis_servers'][0]['port'];
					try{	
							//$keyrand = "TestKey".rand(111,999);
							$keyrand = "TestKey123";

							$key = (isset($_REQUEST["key"]))?$_REQUEST["key"]:$keyrand;
							echo "Key: " . $key;
							
							$redisObj = new Redis();
							$redisObj->connect( $hostName, $port );
							echo "<br>Connection to server ".$hostName." successfully<br>";
						  
							$value = $redisObj->get("key");
							if(!$value){
								$redisObj->setex('key', 60, 'value123' );
								$value = $redisObj->get("key");
								echo "Set & Get Key ".$value;
							}else
								echo "Get Key ".$value;

							echo "<br>";
							var_dump($value);
					}catch( Exception $e ){ 
							echo "<br>".$e->getMessage(); 
					}
			}
	}

}