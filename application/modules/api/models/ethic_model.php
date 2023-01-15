<?php

class Ethic_model extends CI_Model {

    private $DBSelect, $DBEdit;

    public function __construct() {
        parent::__construct();
    }
    
    public function getNews($category = '', $order = '', $limit = 10, $offset = 0) {
        if(empty($category))
            return false;
        
        $DBSelect = $this->load->database('select', TRUE);
        
        $DBSelect->select('cms.*,file.file_type,file.file_path,file.cms_file_id');
        $DBSelect->join('cms_file file', 'cms.cms_id = file.cms_id', 'left');
        $DBSelect->where('cms.cms_category_id', $category); 
        $DBSelect->where('cms.record_status', 1); 
        if(isset($order) and $order)
            $DBSelect->order_by($order); 
        else
            $DBSelect->order_by('cms.cms_id DESC'); 
        
        if(isset($offset) and $offset)
            $DBSelect->limit($limit, $offset);
        else
            $DBSelect->limit($limit);
        
        $query = $DBSelect->get('cms');

        if($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
        
    }
    
}

?>