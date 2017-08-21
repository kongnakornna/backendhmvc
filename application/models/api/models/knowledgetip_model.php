<?php

class KnowledgeTip_model extends CI_Model {

    private $DBSelect, $DBEdit;

    public function __construct() {
        parent::__construct();
    }
    
	public function getTip($arrWhere = null, $limit = 10, $offset = null, $orderby = null) 
	{
		$DBSelect = $this->load->database('select', TRUE);

        $DBSelect->select('kt.*');
        $DBSelect->from('knowledge_tip kt');
		
		if(isset($arrWhere))
			$DBSelect->where($arrWhere);
        
        if(isset($offset) and $offset)
            $DBSelect->limit($limit, $offset);
        else
            $DBSelect->limit($limit);
			
		if(isset($orderby))
			$DBSelect->order_by($orderby);
        
        $query = $DBSelect->get();
		//var_dump($DBSelect); die;
		
		if(!$query) { return false; }
        if($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
	}
	
	public function get1tip()
	{
		
	}
}	