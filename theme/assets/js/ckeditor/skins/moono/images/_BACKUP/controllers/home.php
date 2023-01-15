<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Home extends Public_Controller {

    function __construct() {

        parent::__construct();
        if (_isdev()) {
            $this->output->enable_profiler(TRUE);
        }

    }

    public function index() {
    	$this->load->model('mul_content');
    	
    	header('Content-Type: text/html; charset=utf-8');
    	$data = $this->mul_content->getKnowledgeByCateId(43,1000,'last',8);
    	print "Last <BR>";
    	print '<pre>';print_r($data);
    	$data = $this->mul_content->getKnowledgeByCateId(40,2000,'hit',8);
    	print "HIT <BR>";
    	print '<pre>';print_r($data);
    }
   }