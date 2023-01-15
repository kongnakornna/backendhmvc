<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class Api extends REST_Controller {
function __construct(){
        // Construct the parent class
        parent::__construct();
        ob_clean();
		#header('Content-Type: application/json; charset=utf-8');
        #date_default_timezone_set('Asia/Bangkok');
        #header("Access-Control-Allow-Origin: *");
    }
public function index_get(){
        $title='Api get';
        #################DATA START###########################
        $data='Api get';
        $data=null;
        #################DATA END###########################
        #################REST START###########################
        $count=count($data);
        $data_all=array('list'=>$data,
                        'count'=>$count,
                        );
        if($data){
                    $this->response(array('header'=>array(
                                                   'title'=>$title,
                                                   'message'=>'Success',
                                                   'status'=>true,
                                                   'datastatus'=>1,
                                                   'code'=>200), 
                                                   'data'=> $data_all),200);
                }else{
                    $this->response(array('header'=>array(
                                                   'title'=>$title,
                                                   'message'=>'Unsuccess',
                                                   'status'=>false, 
                                                   'datastatus'=>0,
                                                   'code'=>201), 
                                                   'data'=> $data_all),201);
                           }
            #################REST END###########################
        }
}