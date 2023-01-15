<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Mulcontent extends REST_Controller{

    public function __construct() {
                parent::__construct();
                $this->load->model('Model_mulcontent');
    } 
	public function index_get(){
		ob_end_clean();
		/*
		  $base_url_key=base_url('api/Key/X-API-KEY/FOO');
		  $this->load->helper('url');
	      $this->load->library('rest');
	      $this->load->library('curl');
	      $this->rest->put($base_url_key);
		*/
       echo 'Modules Mulcontent API';
	}

	function testconnect_get(){
		ob_end_clean();
		$id = $this->session->userdata('id');
		$id = $this->session->userdata('apikey');
		var_dump($id);
		echo $id == false;
		// echo 'Total Results: ' . $query->num_rows();
	}

	function get_get(){
		echo 123;
	}
//data_get
	public function data_get(){
		ob_end_clean();
	// respond with information about a user
		#  /V3/restapi/Mulcontent/data/?format=html
		#  /V3/restapi/Mulcontent/data/?format=xml
		#  /V3/restapi/Mulcontent/data/?format=json
		$order_by='desc';
		$limit='10000';
		$lang='th';
		//$data = $this->Model_mulcontent->list_all_data($order_by,$limit);
		$data = $this->Model_mulcontent->list_all_data($order_by,$limit);
		if($data){
			    $count=count($data);				
			    $this->set_response([
	            	'code' => 200,
	                'status' => TRUE,
	                'Massage' => 'REST Done',
	                'remarks' => 'API REST  OK',
	                'count' => $count,
	                'data' => $data,
	            ],REST_Controller::HTTP_OK); 
				/*
				$this->set_response([
	            	$data
	            ],REST_Controller::HTTP_OK); 
				*/
			}else{
	            $this->set_response([
	            	'code' => 404,
	                'status' => FALSE,
	                'Massage' => 'Record could not be found',
	                'remarks' => 'Record could not be found',
	            ], REST_Controller::HTTP_NOT_FOUND); 
	        }
	}
//data_where_id_get
	public function where_id_get($id=1){
		   ob_end_clean();
			#  /V3/restapi/Mulcontent/where_id/1?format=html
			#  /V3/restapi/Mulcontent/where_id/1?format=xml
			#  /V3/restapi/Mulcontent/where_id/1?format=json
	        $data = $this->Model_mulcontent->read_where_id($id);
			if($data){
				    $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST Done',
		                'remarks' => 'API REST  OK',
		                'data' => $data,
		            ],REST_Controller::HTTP_OK); 
				}else{
		            $this->set_response([
		            	'code' => 404,
		                'status' => FALSE,
		                'Massage' => 'Record could not be found',
		                'remarks' => 'Record could not be found',
		                'data' => $data,
		            ], REST_Controller::HTTP_NOT_FOUND); 
		        }
	    }
//data_where_id_get
	public function where_id_lang_get($id=1,$lang='th'){
			ob_end_clean();
			#  /V3/restapi/Mulcontent/where_id_lang/1/th?format=html
			#  /V3/restapi/Mulcontent/where_id_lang/1/th?format=xml
			#  /V3/restapi/Mulcontent/where_id_lang/1/th?format=json
	        $data = $this->Model_mulcontent->read_where_lang_id($id,$lang);
	        $count=count($data);
			if($data){
				    $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST Done',
		                'remarks' => 'API REST  OK',
		                'count'=>$count,
		                'data' => $data,
		            ],REST_Controller::HTTP_OK); 
				}else{
		            $this->set_response([
		            	'code' => 404,
		                'status' => FALSE,
		                'Massage' => 'Record could not be found',
		                'remarks' => 'Record could not be found',
		                'data' => $data,
		            ], REST_Controller::HTTP_NOT_FOUND); 
		        }
  }
	    
//data_update_put
	public function update_put(){
			ob_end_clean();
			// create a new Mulcontent and respond with a status/Massages
		$data = $this->input->input_stream();
		$id = $this->uri->segment(4);
		//echo '$id=>'.$id;
        $result=$this->Model_mulcontent->update($data,$id);
		if($result){
				    $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST Done',
		                'remarks' => 'API REST Update Done',
		                'data' => $data,
		            ],REST_Controller::HTTP_OK); 
				}else{
		            $this->set_response([
		            	'code' => 404,
		                'status' => FALSE,
		                'Massage' => 'Record could not be found',
		                'remarks' => 'Record could not be found',
		                'data' => $data,
		            ], REST_Controller::HTTP_NOT_FOUND); 
		        }
			
		}
//data_update_post
	public function update_post(){
			ob_end_clean();
		 $id = $this->uri->segment(4); //echo '$id=>'.$id;
         $data = array(
				'wallet_typepackage_id'=> $this->post('wallet_Mulcontent_id'),
				'wallet_package_name'=> $this->post('wallet_Mulcontent_id'),
				'startsalary'=> $this->post('wallet_Mulcontent_id'),
				'endsalary'=> $this->post('wallet_Mulcontent_id'),
				'normal'=> $this->post('wallet_Mulcontent_id'),
				'special'=> $this->post('wallet_Mulcontent_id'),
				'status'=> $this->post('wallet_Mulcontent_id'),
				'useoption'=> $this->post('wallet_Mulcontent_id'),
				'date'=> date('Y-m-d H:i:s'),
                );
		//debug($data); die();
        $result=$this->Model_mulcontent->update($data,$id);
		if($result){
				    $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST Done',
		                'remarks' => 'API REST Update Post Done',
		                'data' => $data,
		            ],REST_Controller::HTTP_OK); 
				}else{
		            $this->set_response([
		            	'code' => 404,
		                'status' => FALSE,
		                'Massage' => 'Massage Update Post found',
		                'remarks' => 'Record could can not be Update Post',
		                'data' => $data,
		            ], REST_Controller::HTTP_NOT_FOUND); 
		        }
    }
    public function tr_get(){
		ob_end_clean();
	// respond with information about a user
		#  /V3/restapi/Mulcontent/data/?format=html
		#  /V3/restapi/Mulcontent/data/?format=xml
		#  /V3/restapi/Mulcontent/data/?format=json
		$order_by='desc';
		$limit='10000';
		$lang='th';
		//$data = $this->Model_mulcontent->list_all_data($order_by,$limit);
		$data = $this->Model_mulcontent->list_all_data($order_by,$limit);
		if($data){
			    $count=count($data);
				$tb=['idx' => 'idx',
						'tableName' => 'tableName',
						'fieldName' => 'fieldName',
						'fieldValue' => 'fieldValue',
						'meaning' => 'meaning'];
				    $this->set_response([
		            	$tb,
		                $data,
		            ],REST_Controller::HTTP_OK); 
			    /*$this->set_response([
	            	'code' => 200,
	                'status' => TRUE,
	                'Massage' => 'REST Done',
	                'remarks' => 'API REST  OK',
	                'count' => $count,
	                'data' => $data,
	            ],REST_Controller::HTTP_OK); 
				*/
				/*
				$this->set_response([
	            	$data
	            ],REST_Controller::HTTP_OK); 
				*/
			}else{
	            $this->set_response([
	            	'code' => 404,
	                'status' => FALSE,
	                'Massage' => 'Record could not be found',
	                'remarks' => 'Record could not be found',
	            ], REST_Controller::HTTP_NOT_FOUND); 
	        }
	}
	public function update_lang_post(){
			ob_end_clean();
		 $id = $this->uri->segment(4); //echo '$id=>'.$id;
		 $lang = $this->post('lang');
         $data = array(
				'wallet_typepackage_id'=> $this->post('wallet_Mulcontent_id'),
				'wallet_package_name'=> $this->post('wallet_Mulcontent_id'),
				'startsalary'=> $this->post('wallet_Mulcontent_id'),
				'endsalary'=> $this->post('wallet_Mulcontent_id'),
				'normal'=> $this->post('wallet_Mulcontent_id'),
				'special'=> $this->post('wallet_Mulcontent_id'),
				'status'=> $this->post('wallet_Mulcontent_id'),
				'useoption'=> $this->post('wallet_Mulcontent_id'),
				'date'=> date('Y-m-d H:i:s'),
                );
		//debug($data); die();
        $result=$this->Model_mulcontent->update_lang($data,$id,$lang);
		if($result){
				    $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST Done',
		                'remarks' => 'API REST Update Post Done',
		                'data' => $data,
		            ],REST_Controller::HTTP_OK); 
				}else{
		            $this->set_response([
		            	'code' => 404,
		                'status' => FALSE,
		                'Massage' => 'Massage Update Post found',
		                'remarks' => 'Record could can not be Update Post',
		                'data' => $data,
		            ], REST_Controller::HTTP_NOT_FOUND); 
		        }
    }
    
//data_insert_put
	public function insert_put(){
			ob_end_clean();
	    // http://localhost/api/talentonline/type/update
        $data = $this->input->input_stream();
		#debug($data); die();
		//echo '$id=>'.$id;
        $result=$this->Model_mulcontent->insert($data);
		if($result){
				    $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST Done',
		                'remarks' => 'API REST Insert Put Done',
		                'data' => $data,
		            ],REST_Controller::HTTP_OK); 

				}else{
		            $this->set_response([
		            	'code' => 404,
		                'status' => FALSE,
		                'Massage' => 'Massage Insert Put found',
		                'remarks' => 'Record could can not be insert put',
		                'data' => $data,
		            ], REST_Controller::HTTP_NOT_FOUND); 
		        }
    }
//data_insert_post
    public function insert_post(){
		 ob_end_clean();
         $data = array(
				'wallet_typepackage_id'=> $this->post('wallet_Mulcontent_id'),
				'wallet_package_name'=> $this->post('wallet_Mulcontent_id'),
				'startsalary'=> $this->post('wallet_Mulcontent_id'),
				'endsalary'=> $this->post('wallet_Mulcontent_id'),
				'normal'=> $this->post('wallet_Mulcontent_id'),
				'special'=> $this->post('wallet_Mulcontent_id'),
				'status'=> $this->post('wallet_Mulcontent_id'),
				'useoption'=> $this->post('wallet_Mulcontent_id'),
				'date'=> date('Y-m-d H:i:s'),
                );
		//debug($data); die();
        $result= $this->Model_mulcontent->insert($data);
        //debug($result); die();
		if($result==1){
				    $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST Done',
		                'remarks' => 'API REST Insert Post Done',
		                'data' => $data,
		            ],REST_Controller::HTTP_OK); 
				}else{
					$data=$result;
		            $this->set_response([
		            	'code' => 404,
		                'status' => FALSE,
		                'Massage' => 'Massage Insert Post found',
		                'remarks' => 'Record could can not be insert Post',
		                'data' => $data,
		            ], REST_Controller::HTTP_NOT_FOUND); 
		        }
    }
//data_delete_delete
	public function delete_data_delete(){
		 ob_end_clean();
			#  /api/talentonline/wallet_type/delete_data/1?format=html
			#  /api/talentonline/wallet_type/delete_data/1?format=xml
			#  /api/talentonline/wallet_type/delete_data/1?format=json
        if($id==''){
			$id = $this->uri->segment(4);
		}
        
		//echo '$id ==>'.$id; Die();
        if($id==''){
            $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST ID cannot be empty',
		                'remarks' => 'API REST ID cannot be empty',
		                'data' => 'ID : '.$id,
		            ],REST_Controller::HTTP_OK); 
        }
        $result = $this->Model_mulcontent->delete($id);
        //echo '$result==>';Debug($result);Die();
        $data='ID :'.$id;
        if($result==1){
				    $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST Done',
		                'remarks' => 'API REST Delete Done',
		                'data' => $data,
		            ],REST_Controller::HTTP_OK); 
				}else{
		            $this->set_response([
		            	'code' => 404,
		                'status' => FALSE,
		                'Massage' => 'Massage Delete found',
		                'remarks' => 'Record could can not be delete',
		                'data' => 'Not have data '.$data,
		            ], REST_Controller::HTTP_NOT_FOUND); 
		        }
	}
//data_delete get
    public function delete_data_get($id){
		 ob_end_clean();
			#  /api/talentonline/wallet_type/delete_data/1?format=html
			#  /api/talentonline/wallet_type/delete_data/1?format=xml
			#  /api/talentonline/wallet_type/delete_data/1?format=json
        if($id==''){
			$id = $this->uri->segment(4);
		}
        
		//echo '$id ==>'.$id; Die();
        if($id==''){
            $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST ID cannot be empty',
		                'remarks' => 'API REST ID cannot be empty',
		                'data' => 'ID : '.$id,
		            ],REST_Controller::HTTP_OK); 
        }
        $result = $this->Model_mulcontent->delete($id);
        //echo '$result==>';Debug($result);Die();
        $data='ID :'.$id;
        if($result==1){
				    $this->set_response([
		            	'code' => 200,
		                'status' => TRUE,
		                'Massage' => 'REST Done',
		                'remarks' => 'API REST Delete Done',
		                'data' => $data,
		            ],REST_Controller::HTTP_OK); 
				}else{
		            $this->set_response([
		            	'code' => 404,
		                'status' => FALSE,
		                'Massage' => 'Massage Delete found',
		                'remarks' => 'Record could can not be delete',
		                'data' => 'Not have data '.$data,
		            ], REST_Controller::HTTP_NOT_FOUND); 
		        }
    }

  
	######### Client mod
	public function native_curl($new_name, $new_email){
		 ob_end_clean();
		$username = 'admin';
		$password = '1234';
		// Alternative JSON version
		// $url = 'http://twitter.com/statuses/update.json';
		// Set up and execute the curl process
		$data_url='index.php/example_api/user/id/1/format/json';
		$base_url=base_url($data_url);
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $base_url);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_POST, 1);
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
			'name' => $new_name,
			'email' => $new_email
		));
		// Optional, delete this line if your API is open
		curl_setopt($curl_handle, CURLOPT_USERPWD, $username . ':' . $password);
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);
		$result = json_decode($buffer);
		if(isset($result->status) && $result->status == 'success'){
			echo 'User has been updated.';
		}else{
			echo 'Something has gone wrong';
		}
	}
	
	public function ci_curl($new_name, $new_email){
		 ob_end_clean();
		$username = 'admin';
		$password = '1234';
		$data_url='index.php/example_api/user/id/1/format/json';
		$base_url=base_url($data_url);
		$this->load->library('curl');
		$this->curl->create($base_url);
		// Optional, delete this line if your API is open
		$this->curl->http_login($username, $password);
		$this->curl->post(array(
			'name' => $new_name,
			'email' => $new_email
		));
		$result = json_decode($this->curl->execute());
		if(isset($result->status) && $result->status == 'success'){
			echo 'User has been updated.';
		}else{
			echo 'Something has gone wrong';
		}
	}
	public function rest_client_call($id){
		 ob_end_clean();
	        $data_url='restserver/index.php/example_api/';
		    $base_url=base_url($data_url);
			$this->load->library('rest', array(
				'server' => $base_url,
				'http_user' => 'admin',
				'http_pass' => '1234',
				'http_auth' => 'basic' // or 'digest'
			));
			$user = $this->rest->get('user', array('id' => $id), 'json');
			echo $user->name;
	}		
}