<?php defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . 'libraries/REST_Controller.php';
// clean the output buffer
// ob_clean();
ob_end_clean();
/*
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
*/
class Dictionary extends REST_Controller{
function __construct(){
// Construct the parent class
parent::__construct();
$this->load->library('encrypt');
$this->load->model('Model_dictionary');
//Load library
$this->load->library('Memcached_library');
}
public function index_get(){
ob_end_clean();
// Lets try to get the key
$type='items';
$cache_info=$this->memcached_library->getstats($type);
$cacheversion=$this->memcached_library->getversion();
$cacheinfo=array('list'=>null,'stats'=>$cache_info,'version'=>$cacheversion);
$module_name='Dictionary';
  $this->response(array('header'=>array('title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'REST_Controller::HTTP_NOT_FOUND',
								'status'=>FALSE, 
								'code'=>404), 
								'data'=> $cacheinfo),404);
 
//$this->info_get();
}
public function search_get(){
ob_end_clean();
$word=@$this->input->get('keyword');
$sort_by=@$this->input->get('sort_by');
if($sort_by==''){$sort_by='asc';}
$page=@$this->input->get('page');
if($page==''){$page=1;}
$perpage=@$this->input->get('perpage');
if($perpage==''){$perpage=100;}
$deletekey=@$this->input->get('deletekey');
$cachetype=4;
/*
echo'<hr><pre> word=>';print_r($word);echo'<pre>';  
echo'<hr><pre> startpage=>';print_r($limitstart);echo'<pre>';  
echo'<hr><pre> limitend=>';print_r($limitend);echo'<pre>';  
*/
$datars=$this->Model_dictionary->search_word($word,$page,$perpage,$sort_by,$deletekey,$cachetype);
#echo'<hr><pre> datars=>';print_r($datars);echo'<pre>'; die();
$datars_list=$datars['list'];
$searchcount=$this->Model_dictionary->search_count($word,$deletekey,$cachetype);
$search_counts=$searchcount['list'];
$search_count=$search_counts['0'];
$searchcount=$search_count->count_rows;
/*
$this->load->library('pagination');
$config['base_url'] = 'http://www.trueplookpanya.com/webservice/api/dictionary/search?keyword='.$word;
$config['total_rows']=$searchcount;
$config['per_page']=20;
$this->pagination->initialize($config);
$create_links=$this->pagination->create_links();
*/
//http://www.trueplookpanya.com/webservice/api/dictionary/search?perpage=10&page=1&keyword=d
$totalpage=$searchcount/$perpage;
$total_page=round($totalpage,0); 
$total_page=$total_page;
if($total_page==0){$total_page=1;}
$left_rec=$searchcount-($page*$perpage);
if($page>0){$last=$page-2;
}else if($page==0){$page=$page;
}else if($left_rec < $perpage ){$last=$page-2;}
$array_a_to_z=$this->Model_dictionary->array_a_to_z();
$array_kor_to_hor=$this->Model_dictionary->array_kor_to_hor();
$datatag=array('en'=>$array_a_to_z,'th'=>$array_kor_to_hor);
if($last<=0){$last=0;}
$sort_bys=array('1'=>'asc','2'=>'desc');
$dataall=array('list'=>$datars_list,
               //'tag'=>$datatag,
               'sort_by'=>$sort_by,
               'sort_by_list'=>$sort_bys,
               'sql'=>$datars['sql'],
               'cachetime'=>$datars['cachetime'],
               'cachekey'=>$datars['cachekey'],
               'message'=>$datars['message'],
               'page'=>(int)$page,
               'last'=>(int)$last,
               'perpage'=>(int)$perpage,
               'totalpage'=>(int)$total_page,
               'countall'=>(int)$searchcount,
               'keyword'=>$word,
               );
#####################################################
$module_name='dictionary';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall),200);
}
elseif($dataall==''){
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall),204);
}
else{
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
}
die();
}
public function autosearch_get(){
ob_end_clean();
$word=@$this->input->get('keyword');
$sort_by=@$this->input->get('sort_by');
if($sort_by==''){$sort_by='asc';}
$page=@$this->input->get('page');
if($page==''){$page=1;}
$perpage=@$this->input->get('perpage');
if($perpage==''){$perpage=1000;}
$deletekey=@$this->input->get('deletekey');
$cachetype=4;
/*
echo'<hr><pre> word=>';print_r($word);echo'<pre>';  
echo'<hr><pre> startpage=>';print_r($limitstart);echo'<pre>';  
echo'<hr><pre> limitend=>';print_r($limitend);echo'<pre>';  
*/
$datars=$this->Model_dictionary->autosearch_word($word,$page,$perpage,$sort_by,$deletekey,$cachetype);
#echo'<hr><pre> datars=>';print_r($datars);echo'<pre>'; die();
$datars_list=$datars['list'];
$searchcount=$this->Model_dictionary->search_count($word,$deletekey,$cachetype);
$search_counts=$searchcount['list'];
$search_count=$search_counts['0'];
$searchcount=$search_count->count_rows;
/*
$this->load->library('pagination');
$config['base_url'] = 'http://www.trueplookpanya.com/webservice/api/dictionary/search?keyword='.$word;
$config['total_rows']=$searchcount;
$config['per_page']=20;
$this->pagination->initialize($config);
$create_links=$this->pagination->create_links();
*/
//http://www.trueplookpanya.com/webservice/api/dictionary/search?perpage=10&page=1&keyword=d
$totalpage=$searchcount/$perpage;
$total_page=round($totalpage, 0); 
$total_page=$total_page-1;
$left_rec=$searchcount-($page*$perpage);
if($page>0) {$last=$page-2;
}else if($page==0){$page=$page;
}else if($left_rec < $perpage ){$last=$page-2;}
$array_a_to_z=$this->Model_dictionary->array_a_to_z();
$array_kor_to_hor=$this->Model_dictionary->array_kor_to_hor();
$datatag=array('en'=>$array_a_to_z,'th'=>$array_kor_to_hor);
if($last<=0){$last=0;}
$sort_bys=array('1'=>'asc','2'=>'desc');
$dataall=array('list'=>$datars_list,
               //'tag'=>$datatag,
               //'sort_by'=>$sort_by,
               //'sort_by_list'=>$sort_bys,
               //'sql'=>$datars['sql'],
               'cachetime'=>$datars['cachetime'],
               'cachekey'=>$datars['cachekey'],
               'message'=>$datars['message'],
               'page'=>(int)$page,
               'last'=>(int)$last,
               'perpage'=>(int)$perpage,
               'totalpage'=>(int)$total_page-1,
               'countall'=>(int)$searchcount,
               'keyword'=>$word,
               );
#####################################################
$module_name='dictionary';
if($dataall!==''){
     $this->response(array('header'=>array(
							'title'=>'REST_Controller::HTTP_OK',
                                   'module'=>$module_name,
							'message'=>' DATA OK',
							'status'=>TRUE,
							'code'=>200), 
							'data'=> $dataall),200);
}
elseif($dataall==''){
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>204), 
								'data'=> $dataall),204);
}
else{
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
}
die();
}
public function tagsearch_get(){
ob_end_clean();
$array_a_to_z=$this->Model_dictionary->array_a_to_z();
$array_kor_to_hor=$this->Model_dictionary->array_kor_to_hor();
$dataall=array('en'=>$array_a_to_z,'th'=>$array_kor_to_hor);
$module_name='Dictionary tagsearch';
if($dataall!==''){
     $this->response(array('header'=>array(
								'title'=>'HTTP_BAD_REQUEST',
                                        'module'=>$module_name,
								'message'=>'Data could not be found',
								'status'=>FALSE, 
								'code'=>200), 
								'data'=> $dataall),200);
}else{
     $this->set_response([
                'status'=>FALSE,
                'module'=>$module_name,
                'title'=>'REST_Controller::HTTP_NOT_FOUND',
                'message'=>'User could not be found',
                'code'=>404
            ], REST_Controller::HTTP_NOT_FOUND);
}
}
}