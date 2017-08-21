<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends Rest_Controller {
  function __construct(){
    parent::__construct();
    $this->load->model('cmsblogmodel');
  }
  
  function editorpicks_get(){
    $menu_id=$this->get('menu_id', FALSE, 1);
    $limit=$this->get('limit', FALSE, 4);
    $offset=$this->get('offset', FALSE, 0);
    $result['response']=array("status"=>true, "message"=>"success", "code"=>200);
    $result['data']['editorpicks']=$this->cmsblogmodel->cmsblog_get_editorpicks_list($menu_id, $limit, $offset);
    $this->response($result);
  }
}
  
