<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Api_jsonuser_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }    
    
    	function get_user_type() {
    		static $query;
    		$this->db->select('*');
    		$query = $this->db->get('sd_user_type');
    		if($query->num_rows() > 0) return $query->result();
    		else return FALSE;
    	}
     
    }
     
    /* End of file my_model.php */
    /* Location: ./system/application/models/my_model.php */