<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Hasher extends Public_Controller {

    public function __construct() {
        parent::__construct();
        ob_clean();
    }

    public function index() {
        $this->load->view('hasher');
    }

}
