<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testmem extends MY_Controller {
    public function __construct()    {
        parent::__construct();
		//$this->load->library('memcache');
        
        /*if(!$this->session->userdata('is_logged_in')){
            redirect(base_url());
        }*/
    }

	public function index(){

			$this->load->library('memcached', 'memcached');
			//Debug($this->config->config['memcache_servers']);
			echo "Start<br>";

			//Debug($this);
			$this->memcached->connect();

			/*
			if( !$this->memcache->set( 'test1', 'data'. rand() )) {
			   echo "error from set";
			}

			if( !$this->memcache->get( 'test1' )) {
			   echo "error from get";
			}

			if( !$this->memcache->delete( 'test1' )) {
			   echo "error from delete";
			}*/

			/*$data = array(				
					"content_view" => 'memcache',
			);
			$this->load->view('template',$data);*/
	}

	public function connect(){
		//$this->load->library('memcache');
		$server = $this->config->config['memcache_servers'][0]['host'];
		$port = $this->config->config['memcache_servers'][0]['port'];

		//Debug($server);
		//Debug($port);
		if (class_exists('Memcached')) {
			$this->memcache = new Memcached;	
			$this->isMemcacheAvailable = $this->memcache->addServer($server, $port);
			echo "Connect Memcached";
		}else{
			if (class_exists('Memcache')) {
				$this->memcache = new Memcache();
				$this->isMemcacheAvailable = $this->memcache->connect($server);
				echo "Connect Memcached";
			}			
		}
	}

}