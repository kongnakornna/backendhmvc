<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//error_reporting(E_ALL);

class Catching extends CI_Controller {
    public function __construct()    {
        parent::__construct();

		$this->load->library('NosqlRedis');

       /* if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }*/
    }

	public function index(){

		if($this->config->config['use_redis']){
			echo "Start Redis.<br>";
			$key = 'view-siamdara_sum_news_66267';
			$data = $this->nosqlredis->getValue($key);

			Debug($key.'='.$data);
		}

	}

    public function getdataNews($key){

		if($this->config->config['use_redis']){
			echo "Start getdata Redis.<br>";
			$key = 'view-siamdara_sum_news_'.$key;

			$redisObj = $this->nosqlredis->openRedisConnection();

			$data = $this->nosqlredis->getValue($key, $redisObj);
			Debug($key.'='.$data);
		}
    }
    public function info(){

		if($this->config->config['use_redis']){
			$data = $this->nosqlredis->info();
			Debug($data);
		}
    }
}