<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compare_mem extends CI_Controller {

	public function index(){

		if($this->config->config['use_memcache'] == true){

			/*echo "<pre>";
			print_r($this->config->config['memcache_servers'][0]);
			echo "</pre>";*/

			$host = $this->config->config['memcache_servers'][0]['host'];
			$port = $this->config->config['memcache_servers'][0]['port'];

			// manual connection to Mamcache
			$memcache = new Memcache;
			$memcache->connect($host, $port);
		
			echo "Server's version: " . $memcache->getVersion() . "<br />\n";

			Debug($memcache);
		
			/*$data = 'This is working';

			$Key = "key";
		
			$memcache->set($Key,$data,false,10);
			echo "cache expires in 10 seconds<br />\n";
		
			echo "Data from the cache: $Key <br />\n";  
			var_dump($memcache->get("key"));
			echo 'If this is all working, <a href="/compare_mem/go">click here</a> view comparisions';*/

		}
		
	}
	
	public function go(){

		$cachetime = 10; // number of seconds to cache for
		$data = array();
		$data['cachereset'] = 0;
		
		// cache calls
		$startTime = microtime(true);
		$this->load->driver('cache');

		$sum_viewcount = $this->cache->memcached->get('alluserscount');

		if (!$sum_viewcount){

			  $data['cachereset'] = 1;
			  $this->load->model('chart_model');
			  $sum_viewcount = $this->chart_model->sum_viewcount();
			  $this->cache->memcached->save('alluserscount',$sum_viewcount, $cachetime);
		}

		$data['cache_result'] = $sum_viewcount;
		$data['cache_time'] = microtime(true)-$startTime;
		
		// normal query calls
		$startTime = microtime(true);

		//$this->load->model('Users','',TRUE);
		$this->load->model('chart_model');

		//$data['normal_result'] = $this->Users->getAll(); 
		$data['normal_result'] = $this->chart_model->sum_viewcount();

		$data['normal_time'] = microtime(true)-$startTime;
		
		$this->load->view('comparision',$data);
		//var_dump($this->cache->memcached->cache_info());
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */