<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/Requests/Requests.php";
require_once APPPATH."/third_party/json2csv/json2csv.class.php";

class Wallets extends CI_Controller {
    public function __construct()    {
        parent::__construct();
	 //$this->load->helper("debug");
        // include Requests
	 Requests::register_autoloader(); 
	$this->load->library('session');	 
    }
    public function index(){  
         $apiurl=$this->config->config['api_url'];
	 $this->listview();  // Load function Listview    
    } 
 
    public function listview(){
            $session_id = $this->session->userdata('session_id');
                $user_id= $this->session->userdata('user_id');
                $username= $this->session->userdata('username');
                $firstname= $this->session->userdata('firstname');
                $lastname= $this->session->userdata('lastname');
                $nickname= $this->session->userdata('nickname');
                $user_level= $this->session->userdata('user_level');
                #echo $user_id;die();
                 if($user_id==''){
			 //redirect(base_url('login')); die();
			}
                        $apiurl=$this->config->config['restapi'];
                        $url=$apiurl.'Talentonline/wallet/dataall/?format=json';
                        #echo $url;
			$request = Requests::get($url, array('Accept' => 'application/json'));
			$search=" ";
			$replace="";
			$string=$request;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request);  Die();
			$request=$request->body;
			$json_data = json_decode($request, true);
			#echo '<pre>'; print_r($json_data); echo '</pre>'; //Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
                        $count = count($items_data);
                        
                         if($status_code<>200){
                             
                             echo 'Can not call API'; Die();
                         }
                        
                        /*
			echo '<hr>'; 
			echo 'code=>'.$status_code; echo '<Br>'; 
			echo 'status=>'.$status; echo '<Br>'; 
			echo 'error=>'.$error; echo '<Br>'; 
			echo 'remarks=>'.$remarks; echo '<Br>'; 
			echo 'data=>'; 
			echo '<hr>'; 
			*/
			// In case there are multiple 'items'
			# echo 'count='.$count;
			# echo '<hr>'; 
                        /*
			foreach ($items_data as $item){
				echo ' | id :'.$item['c4j_wallet_type_id2']; 
				echo ' | wallet type  :'.$item['wallet_type_name'];
				echo ' | date:'.$item['date']; echo ' | <br>';
			}
			echo '<hr>'; 
                         */
          
         $data = array(
			"title" => 'Wallet',
                        "subtitle"=> 'Wallet Balance',
                        "count" => $count,
			"data" => $items_data,	
                        "content_view" => 'Wallet/Wallet',
			); 
         #echo '<pre>'; print_r($data); echo '</pre>';  Die();
	  $this->load->view('template/template',$data);
         #$this->load->view('welcome_message');                     
    }
    public function listcompany(){
        
                $session_id = $this->session->userdata('session_id');
                $user_id= $this->session->userdata('user_id');
                $username= $this->session->userdata('username');
                $firstname= $this->session->userdata('firstname');
                $lastname= $this->session->userdata('lastname');
                $nickname= $this->session->userdata('nickname');
                $user_level= $this->session->userdata('user_level');
                #echo $user_id;die();
                 if($user_id==''){
			 //redirect(base_url('login')); die();
			}
        
                        $apiurl=$this->config->config['restapi'];
                        $url=$apiurl.'Talentonline/wallet/dataall_employer/?format=json';
                        #echo $url;
			$request = Requests::get($url, array('Accept' => 'application/json'));
			$search=" ";
			$replace="";
			$string=$request;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request);  Die();
             //echo '<pre>'; print_r($request); echo '</pre>'; // Die();
			 
			 //echo 'headers==> <pre>'; print_r($request->headers); echo '</pre>'; 
			 //$headers=$request->headers;
	/*
			 echo 'raw==> <pre>'; print_r($request->raw); echo '</pre>';   
			 echo 'body==> <pre>'; print_r($request->body); echo '</pre>';   
			 echo 'status_code==> <pre>'; print_r($request->status_code); echo '</pre>';   
			 echo 'protocol_version==>  <pre>'; print_r($request->protocol_version); echo '</pre>';  
			 echo 'success==>  <pre>'; print_r($request->success); echo '</pre>';  Die();
	*/ 
			$request=$request->body;
			$json_data = json_decode($request, true);
			#echo '<pre>'; print_r($json_data); echo '</pre>'; //Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
                        $count = count($items_data);
                        
                         if($status_code==200){
                            $items_data=$json_data['data'];
                            $count = count($items_data);
                        }else if($status_code==201){
                            $items_data=$json_data['data'];
                            $count = 0;
                        }else{ echo 'Can Not Call API Code '.$status_code; Die();} 
                        
                        /*
			echo '<hr>'; 
			echo 'code=>'.$status_code; echo '<Br>'; 
			echo 'status=>'.$status; echo '<Br>'; 
			echo 'error=>'.$error; echo '<Br>'; 
			echo 'remarks=>'.$remarks; echo '<Br>'; 
			echo 'data=>'; 
			echo '<hr>'; 
			*/
			// In case there are multiple 'items'
			# echo 'count='.$count;
			# echo '<hr>'; 
                        /*
			foreach ($items_data as $item){
				echo ' | id :'.$item['c4j_wallet_type_id2']; 
				echo ' | wallet type  :'.$item['wallet_type_name'];
				echo ' | date:'.$item['date']; echo ' | <br>';
			}
			echo '<hr>'; 
                         */
          
         $data = array(
			"title" => 'Wallet',
                        "subtitle"=> 'Wallet Balance',
                        "count" => $count,
			"data" => $items_data,	
                        "content_view" => 'Wallet/Walletcompany',
			); 
          #echo '<pre>'; print_r($data); echo '</pre>';  Die();
	  $this->load->view('template/template',$data);
         #$this->load->view('welcome_message');                     
    }	
     public function listcompanycsv(){
        
                $session_id = $this->session->userdata('session_id');
                $user_id= $this->session->userdata('user_id');
                $username= $this->session->userdata('username');
                $firstname= $this->session->userdata('firstname');
                $lastname= $this->session->userdata('lastname');
                $nickname= $this->session->userdata('nickname');
                $user_level= $this->session->userdata('user_level');
                #echo $user_id;die();
                 if($user_id==''){
			 //redirect(base_url('login')); die();
			}
        
                        $apiurl=$this->config->config['restapi'];
                        $url=$apiurl.'Talentonline/wallet/dataall_employer/?format=json';
                        #echo $url;
			$request = Requests::get($url, array('Accept' => 'application/json'));
			$search=" ";
			$replace="";
			$string=$request;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request);  Die();
                        #echo '<pre>'; print_r($request); echo '</pre>'; //Die();
			$request=$request->body;
			$json_data = json_decode($request, true);
			#echo '<pre>'; print_r($json_data); echo '</pre>'; //Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
                        $count = count($items_data);
                        
                         if($status_code==200){
                            $items_data=$json_data['data'];
                            $count = count($items_data);
                        }else if($status_code==201){
                            $items_data=$json_data['data'];
                            $count = 0;
                        }else{ echo 'Can Not Call API Code '.$status_code; Die();} 
                        
                        /*
			echo '<hr>'; 
			echo 'code=>'.$status_code; echo '<Br>'; 
			echo 'status=>'.$status; echo '<Br>'; 
			echo 'error=>'.$error; echo '<Br>'; 
			echo 'remarks=>'.$remarks; echo '<Br>'; 
			echo 'data=>'; 
			echo '<hr>'; 
			*/
			// In case there are multiple 'items'
			# echo 'count='.$count;
			# echo '<hr>'; 
                        /*
			foreach ($items_data as $item){
				echo ' | id :'.$item['c4j_wallet_type_id2']; 
				echo ' | wallet type  :'.$item['wallet_type_name'];
				echo ' | date:'.$item['date']; echo ' | <br>';
			}
			echo '<hr>'; 
                         */
                        
                        //echo '<pre>'; print_r($items_data); echo '</pre>'; Die();
			ob_end_clean();
                        // Read the file from disk
                        $filename = date('Y_m_d_H_i').".csv";
                        header("Content-type: application/csv");
                        header("Cache-Control: public"); 
                        header("Content-Type: application/octet-stream");
                        header("content-type:application/csv;charset=UTF-8");
                        header("Content-Disposition: attachment; filename=\"Employer_".$filename."");
                        header("Pragma: no-cache");
                        header("Expires: 0");
                        $handle = fopen('php://output', 'w');
                        foreach ($items_data as $data) {
                            fputcsv($handle, $data);
                        }
                            fclose($handle);
                        exit();
		 
                           
    }	
    public function add(){
        
                        $segment1=$this->uri->segment(1);
                        $segment2=$this->uri->segment(2);
                        $segment3=$this->uri->segment(3);
                        $segment4=$this->uri->segment(4);
                        $segment5=$this->uri->segment(5);
                        $segment6=$this->uri->segment(6);
                        $segment7=$this->uri->segment(7);
                        $segment8=$this->uri->segment(8);
                        $segment9=$this->uri->segment(9);
                        $segment10=$this->uri->segment(10);
                        $apiurl=$this->config->config['restapi'];
                        
                        $url=$apiurl.'Talentonline/wallet/where_employer_wallet_id/'.$segment3.'?format=json';
                        #echo $url;
			$request = Requests::get($url, array('Accept' => 'application/json'));
			$search=" ";
			$replace="";
			$string=$request;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request);  Die();
			$request=$request->body;
			$json_data = json_decode($request, true);
			#echo '<pre>'; print_r($json_data); echo '</pre>'; //Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
                        $count = count($items_data);
                        
                         if($status_code==200){
                            $items_data=$json_data['data'];
                            $count = count($items_data);
                        }else if($status_code==201){
                            $items_data=$json_data['data'];
                            $count = 0;
                        }else{ 
                            $count = 0;
                            #echo 'Can Not Call API Code '.$status_code; Die();
                            
                        } 
 
        //echo $segment2;
                        
                        $url2=$apiurl.'Talentonline/wallet/where_employer_id/'.$segment3.'?format=json';
                        #echo $url;
			$request2 = Requests::get($url2, array('Accept' => 'application/json'));
			$search2=" ";
			$replace2="";
			$string2=$request2;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request2);  Die();
			$request2=$request2->body;
			$json_data2 = json_decode($request2, true);
			#echo '<pre>'; print_r($json_data2); echo '</pre>'; //Die();
			$status_code2=$json_data2['code'];
			$status2=$json_data2['status'];
			$error2=$json_data2['error'];
			$remarks2=$json_data2['remarks'];
			$items_data2=$json_data2['data'];
                        $count2 = count($items_data2);
                        
                         if($status_code2==200){
                            $items_data2=$json_data2['data'];
                            $count2 = count($items_data2);
                        }else if($status_code2==201){
                            $items_data2=$json_data2['data'];
                            $count2 = 0;
                        }else{ 
                            $count2 = 0;
                            #echo 'Can Not Call API Code '.$status_code2; Die();
                            
                        } 
                      
 
        //echo $segment3;
                        
                        $url3=$apiurl.'Talentonline/wallet_type/Typepackagedata/desc/100/th?format=json';
                        #echo $url;
			$request3 = Requests::get($url3, array('Accept' => 'application/json'));
			$search3=" ";
			$replace3="";
			$string3=$request3;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request3);  Die();
			$request3=$request3->body;
			$json_data3 = json_decode($request3, true);
			#echo '<pre>'; print_r($json_data3); echo '</pre>'; //Die();
			$status_code3=$json_data3['code'];
			$status3=$json_data3['status'];
			$error3=$json_data3['error'];
			$remarks3=$json_data3['remarks'];
			$items_data3=$json_data3['data'];
                        $count3 = count($items_data3);
                        
                         if($status_code3==200){
                            $items_data3=$json_data3['data'];
                            $count3 = count($items_data3);
                        }else if($status_code3==201){
                            $items_data3=$json_data3['data'];
                            $count3 = 0;
                        }else{ 
                            $count3 = 0;
                            #echo 'Can Not Call API Code '.$status_code3; Die();
                            
                        } 
                         
                        
                        

        if($segment3=='' && $segment3==''){
                    redirect('wallets/listcompany/'); Die();
        }
        $itemsdata = array(
                        "employer_id" => $segment3,
                        "user_id" => $segment4,
			); 
        
         $data = array(
			"title" => 'Wallet Add',
                        "subtitle"=> 'Add Wallet Balance',
                        "data" => $itemsdata,
                        "code" => $status_code,
                        "count" => $count,
                        "count2" => $count2,
                        "count3" => $count3,
                        "data_employer" => $items_data,
                        "data_employer1" => $items_data2,
                        "data_employer3" => $items_data3,
                        #"employer_id" => $segment3,
                        #"user_id" => $segment4,
                        "content_view" => 'Wallet/Add',
			); 
            #echo '<pre>'; print_r($data); echo '</pre>';  Die();
	  $this->load->view('template/template',$data);
         #$this->load->view('welcome_message');                     
    }
     public function buy(){
        
                        $segment1=$this->uri->segment(1);
                        $segment2=$this->uri->segment(2);
                        $segment3=$this->uri->segment(3);
                        $segment4=$this->uri->segment(4);
                        $segment5=$this->uri->segment(5);
                        $segment6=$this->uri->segment(6);
                        $segment7=$this->uri->segment(7);
                        $segment8=$this->uri->segment(8);
                        $segment9=$this->uri->segment(9);
                        $segment10=$this->uri->segment(10);
                        $apiurl=$this->config->config['restapi'];
                        
                        $url=$apiurl.'Talentonline/wallet/where_employer_wallet_id/'.$segment3.'?format=json';
                        #echo $url;
			$request = Requests::get($url, array('Accept' => 'application/json'));
			$search=" ";
			$replace="";
			$string=$request;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request);  Die();
			$request=$request->body;
			$json_data = json_decode($request, true);
			#echo '<pre>'; print_r($json_data); echo '</pre>'; //Die();
			$status_code=$json_data['code'];
                        
                        #echo '<pre>'; print_r($status_code); echo '</pre>';  Die();
                        
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
                        $count = count($items_data);
                        
                         if($status_code==200){
                            $items_data=$json_data['data'];
                            $count = count($items_data);
                        }else if($status_code==201){
                            $items_data=$json_data['data'];
                            $count = 0;
                        }else if($status_code==404){
                            $items_data=0;
                            $count = 0;
                        }else{ 
                            $count = 0;
                            #echo 'Can Not Call API Code '.$status_code; Die();
                            
                        } 
 
        //echo $segment2;
                        
                        $url2=$apiurl.'Talentonline/wallet/where_employer_id/'.$segment3.'?format=json';
                        #echo $url;
			$request2 = Requests::get($url2, array('Accept' => 'application/json'));
			$search2=" ";
			$replace2="";
			$string2=$request2;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request2);  Die();
			$request2=$request2->body;
			$json_data2 = json_decode($request2, true);
			#echo '<pre>'; print_r($json_data2); echo '</pre>'; //Die();
			$status_code2=$json_data2['code'];
			$status2=$json_data2['status'];
			$error2=$json_data2['error'];
			$remarks2=$json_data2['remarks'];
			$items_data2=$json_data2['data'];
                        $count2 = count($items_data2);
                        
                         if($status_code2==200){
                            $items_data2=$json_data2['data'];
                            $count2 = count($items_data2);
                        }else if($status_code2==201){
                            $items_data2=$json_data2['data'];
                            $count2 = 0;
                        }else{ 
                            $count2 = 0;
                            #echo 'Can Not Call API Code '.$status_code2; Die();
                            
                        } 
                        

//package
                                                
                        $url3=$apiurl.'Talentonline/Wallet_package/list_all_data_status_show/?format=json';
                        #echo $url;
			$request3 = Requests::get($url3, array('Accept' => 'application/json'));
			$search3=" ";
			$replace3="";
			$string3=$request3;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request3);  Die();
			$request3=$request3->body;
			$json_data3 = json_decode($request3, true);
			#echo '<pre>'; print_r($json_data2); echo '</pre>'; //Die();
			$status_code3=$json_data3['code'];
			$status3=$json_data3['status'];
			$error3=$json_data3['error'];
			$remarks3=$json_data3['remarks'];
			$items_data3=$json_data3['data'];
                        $count3 = count($items_data3);
                        
                         if($status_code3==200){
                            $items_data3=$json_data3['data'];
                            $count3 = count($items_data3);
                        }else if($status_code3==201){
                            $items_data3=$json_data3['data'];
                            $count3 = 0;
                        }else{ 
                            $count3 = 0;
                            #echo 'Can Not Call API Code '.$status_code2; Die();
                            
                        } 
                        
                        
                        
                        
                        
        if($segment3=='' && $segment3==''){
                    redirect('wallets/listcompany/'); Die();
        }
        $itemsdata = array(
                        "employer_id" => $segment3,
                        "user_id" => $segment4,
			); 
        
         $data = array(
			"title" => 'Wallet Buy',
                        "subtitle"=> 'Buy Package',
                        "data" => $itemsdata,
                        "code" => $status_code,
                        "count" => $count,
                        "count2" => $count2,
                        "count3" => $count3,
                        "data_employer" => $items_data,
                        "data_employer1" => $items_data2,
                        "data_package" => $items_data3,
                        #"employer_id" => $segment3,
                        #"user_id" => $segment4,
                        "content_view" => 'Wallet/Buy',
			); 
 #echo '<pre>'; print_r($data); echo '</pre>';  Die();
	  $this->load->view('template/template',$data);
         #$this->load->view('welcome_message');                     
    }
    
    public function walletsinsert(){
        
                $segment1=$this->uri->segment(1);
                $segment2=$this->uri->segment(2);
                $segment3=$this->uri->segment(3);
                $segment4=$this->uri->segment(4);
                $segment5=$this->uri->segment(5);
                $segment6=$this->uri->segment(6);
                $segment7=$this->uri->segment(7);
                $segment8=$this->uri->segment(8);
                $segment9=$this->uri->segment(9);
                $segment10=$this->uri->segment(10);       
		$post=$this->input->post();		
		//echo '<pre>';print_r($post);echo '</pre>'; Die();
				
		$employer_id=$post['employer_id'];
		$apiurl=$this->config->config['restapi'];
                $url=$apiurl.'Talentonline/wallet/updatewalletss/';
                //echo $url;
                $request = Requests::post($url, array(), $post);
		#echo '<br> Retrun ==>  <pre>'; print_r($request); echo '</pre>';  Die();
                $request=$request->body;
		$json_data = json_decode($request, true);
		 #echo '<pre>'; print_r($json_data); echo '</pre>';  Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
              $date=date('Y-m-d-H-i-s');        
              $filename = 'wallet_'.$employer_id.'_'.$date;
              $path='wallet/employer_id_'.$employer_id.'/';
              //SaveJSON($items_data,$filename,true,$path);
                        
                        $count = count($items_data);
                        
                         if($status_code==200){
                            $items_data=$json_data['data'];
                            $count = count($items_data);
                             redirect('wallets/listcompany'); die();
                        }else if($status_code==201){
                            $items_data=$json_data['data'];
                             redirect('wallets/listcompany'); die();
                            $count = 0;
                        }else{ 
                            $count = 0;
                             redirect('wallets/listcompany'); die();
                            #echo 'Can Not Call API Code '.$status_code; Die();
                            
                        } 
                  
    }
     public function wallets_insert_add(){
        
                $segment1=$this->uri->segment(1);
                $segment2=$this->uri->segment(2);
                $segment3=$this->uri->segment(3);
                $segment4=$this->uri->segment(4);
                $segment5=$this->uri->segment(5);
                $segment6=$this->uri->segment(6);
                $segment7=$this->uri->segment(7);
                $segment8=$this->uri->segment(8);
                $segment9=$this->uri->segment(9);
                $segment10=$this->uri->segment(10);       
		$post=$this->input->post();		
		#echo '<pre>';print_r($post);echo '</pre>'; Die();
				
		$employer_id=$post['employer_id'];
		$apiurl=$this->config->config['restapi'];
                $url=$apiurl.'Talentonline/wallet/insert_walletss/';
                //echo $url;
                $request = Requests::post($url, array(), $post);
		#echo '<br> Retrun ==>  <pre>'; print_r($request); echo '</pre>';  Die();
                $request=$request->body;
		$json_data = json_decode($request, true);
		 #echo '<pre>'; print_r($json_data); echo '</pre>';  Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
              $date=date('Y-m-d-H-i-s');        
              $filename = 'wallet_'.$employer_id.'_'.$date;
              $path='wallet/employer_id_'.$employer_id.'/';
              //SaveJSON($items_data,$filename,true,$path);
                        $count = count($items_data);
                        
                         if($status_code==200){
                            $items_data=$json_data['data'];
                            $count = count($items_data);
                             redirect('wallets/listcompany'); die();
                        }else if($status_code==201){
                            $items_data=$json_data['data'];
                             redirect('wallets/listcompany'); die();
                            $count = 0;
                        }else{ 
                            $count = 0;
                             redirect('wallets/listcompany'); die();
                            #echo 'Can Not Call API Code '.$status_code; Die();
                            
                        } 
    }
     //point 
     public function wallets_update(){
        
                $segment1=$this->uri->segment(1);
                $segment2=$this->uri->segment(2);
                $segment3=$this->uri->segment(3);
                $segment4=$this->uri->segment(4);
                $segment5=$this->uri->segment(5);
                $segment6=$this->uri->segment(6);
                $segment7=$this->uri->segment(7);
                $segment8=$this->uri->segment(8);
                $segment9=$this->uri->segment(9);
                $segment10=$this->uri->segment(10);       
		$post=$this->input->post();
                $wallet_package_id=$post['wallet_package_id'];
		#echo '<pre>';print_r($wallet_package_id);echo '</pre>'; Die();
                       $apiurl=$this->config->config['restapi'];
                       $url3=$apiurl.'Talentonline/wallet_package/where_id/'.$wallet_package_id.'?format=json';
                        #echo $url;
			$request3 = Requests::get($url3, array('Accept' => 'application/json'));
			$search3=" ";
			$replace3="";
			$string3=$request3;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request3);  Die();
			$request3=$request3->body;
			$json_data = json_decode($request3, true);
			#echo '<pre>'; print_r($json_data); echo '</pre>'; //Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
                        $count = count($items_data);
                        $items_data=$items_data['0'];
                        
                        $wallet_package_id=$items_data['wallet_package_id'];
                        $wallet_typepackage_id=$items_data['wallet_typepackage_id'];
                        $wallet_package_name=$items_data['wallet_package_name'];
                        $price=(int)$items_data['price'];
                        $point=(int)$items_data['point'];
                        $wallet_package_expire_day=$items_data['wallet_package_expire_day'];
                        $status=$items_data['status'];
                        #echo '<pre>'; print_r($items_data); echo '</pre>';  Die();
                        $candidate_id=$post['candidate_id'];
                        $resume_id=$post['resume_id'];
                        $employer_user_id=$post['employer_user_id'];
                        $lang=$post['lang'];
                        $employer_id=$post['employer_id'];
                        $user_id=$post['user_id'];
                        $wallet_type_id=$post['wallet_type_id'];
                        ///////////
      			$url_em=$apiurl.'Talentonline/Wallet/where_employer_id/'.$employer_id.'?format=json';
                        #echo $url;
			$request_em = Requests::get($url_em, array('Accept' => 'application/json'));
			$search_em=" ";
			$replace_em="";
			$string_em=$request_em;
			// Check what we received
			#var_dump($request_em);  Die();
			$request_em=$request_em->body;
			$json_data_em = json_decode($request_em, true);
			#echo '<pre>'; print_r($json_data_em); echo '</pre>'; //Die();
			$status_code_em=$json_data_em['code'];
			$status_em=$json_data_em['status'];
			$error_em=$json_data_em['error'];
			$remarks_em=$json_data_em['remarks'];
			$items_data_em=$json_data_em['data'];
                        $count_em=count($items_data_em);
                        $items_data_em=$items_data_em['0'];    
                       #echo '<pre>';print_r($items_data_em);echo '</pre>'; Die();
                       $employer_money=(int)$items_data_em['money'];
                       $employer_point=(int)$items_data_em['point'];
                       #echo '<pre>';print_r($employer_point);echo '</pre>'; Die();
                      if($employer_point<$point){
                          
                          echo  'Your Balance '.$employer_point.' System requirement '.$point.' Please check your point wallet,Not enough balance Point ,ยอดไม่พอตัดกรุณาเติม Wallet ก่อนทำรายการ <hr>';  
                          ?> <a href="<?php echo base_url('wallets/listcompany');?>">Back </a> <?php Die();
                      }
                        
                 $postdata = array(
			"candidate_id" => $candidate_id,
                        "resume_id"=> $resume_id,
                        "employer_user_id" => $employer_user_id,
                        "lang" => $lang,
                        "employer_id" => $employer_id,
                        "user_id" => $user_id,
                        "wallet_type_id" => $wallet_type_id,
                        "wallet_package_id" => $wallet_package_id,
                        "balance_money"=>0,
                        "balance_point" => $point,
			); 
             
                
		  #echo '<pre>';print_r($post);echo '</pre>';   echo '<pre>';print_r($postdata);echo '</pre>'; Die();
                 
		$apiurl=$this->config->config['restapi'];
                $url=$apiurl.'Talentonline/wallet/update/';
                //echo $url;
                $request = Requests::post($url, array(), $postdata);
		#echo '<br> Retrun ==>  <pre>'; print_r($request); echo '</pre>';  Die();
                $request=$request->body;
		$json_data = json_decode($request, true);
		 #echo '<pre>'; print_r($json_data); echo '</pre>';  Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
              $date=date('Y-m-d-H-i-s');        
              $filename = 'wallet_'.$employer_id.'_'.$date;
              $path='wallet/employer_id_'.$employer_id.'/';
              //SaveJSON($items_data,$filename,true,$path);
                        $count = count($items_data);
                        
                         if($status_code==200){
                            $items_data=$json_data['data'];
                            $count = count($items_data);
                             redirect('wallets/listcompany'); die();
                        }else if($status_code==201){
                            $items_data=$json_data['data'];
                             redirect('wallets/listcompany'); die();
                            $count = 0;
                        }else{ 
                            $count = 0;
                             redirect('wallets/listcompany'); die();
                            #echo 'Can Not Call API Code '.$status_code; Die();
                            
                        } 
    }
    // money
    public function wallets_update_money(){
        
                $segment1=$this->uri->segment(1);
                $segment2=$this->uri->segment(2);
                $segment3=$this->uri->segment(3);
                $segment4=$this->uri->segment(4);
                $segment5=$this->uri->segment(5);
                $segment6=$this->uri->segment(6);
                $segment7=$this->uri->segment(7);
                $segment8=$this->uri->segment(8);
                $segment9=$this->uri->segment(9);
                $segment10=$this->uri->segment(10);       
		$post=$this->input->post();
                $wallet_package_id=$post['wallet_package_id'];
		#echo '<pre>';print_r($wallet_package_id);echo '</pre>'; Die();
                       $apiurl=$this->config->config['restapi'];
                       $url3=$apiurl.'Talentonline/wallet_package/where_id/'.$wallet_package_id.'?format=json';
                        #echo $url;
			$request3 = Requests::get($url3, array('Accept' => 'application/json'));
			$search3=" ";
			$replace3="";
			$string3=$request3;
			//$request =str_replace($search,$replace,$string);
			// Check what we received
			#var_dump($request3);  Die();
			$request3=$request3->body;
			$json_data = json_decode($request3, true);
			#echo '<pre>'; print_r($json_data); echo '</pre>'; //Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
                        $count = count($items_data);
                        $items_data=$items_data['0'];
                        
                        $wallet_package_id=$items_data['wallet_package_id'];
                        $wallet_typepackage_id=$items_data['wallet_typepackage_id'];
                        $wallet_package_name=$items_data['wallet_package_name'];
                        $price=(int)$items_data['price'];
                        $point=(int)$items_data['point'];
                        $wallet_package_expire_day=$items_data['wallet_package_expire_day'];
                        $status=$items_data['status'];
                        #echo '<pre>'; print_r($items_data); echo '</pre>';  Die();
                        $candidate_id=$post['candidate_id'];
                        $resume_id=$post['resume_id'];
                        $employer_user_id=$post['employer_user_id'];
                        $lang=$post['lang'];
                        $employer_id=$post['employer_id'];
                        $user_id=$post['user_id'];
                        $wallet_type_id=$post['wallet_type_id'];
                        ///////////
      			$url_em=$apiurl.'Talentonline/Wallet/where_employer_id/'.$employer_id.'?format=json';
                        #echo $url;
			$request_em = Requests::get($url_em, array('Accept' => 'application/json'));
			$search_em=" ";
			$replace_em="";
			$string_em=$request_em;
			// Check what we received
			#var_dump($request_em);  Die();
			$request_em=$request_em->body;
			$json_data_em = json_decode($request_em, true);
			#echo '<pre>'; print_r($json_data_em); echo '</pre>'; //Die();
			$status_code_em=$json_data_em['code'];
			$status_em=$json_data_em['status'];
			$error_em=$json_data_em['error'];
			$remarks_em=$json_data_em['remarks'];
			$items_data_em=$json_data_em['data'];
                        $count_em=count($items_data_em);
                        $items_data_em=$items_data_em['0'];    
                       #echo '<pre>';print_r($items_data_em);echo '</pre>'; Die();
                       $employer_money=(int)$items_data_em['money'];
                       $employer_point=(int)$items_data_em['point'];
                       #echo '<pre>';print_r($employer_point);echo '</pre>'; Die();
                      if($employer_money<$price){
                          
                          echo  'Your Balance '.$employer_money.' System requirement '.$price.' Please check your money wallet,Not enough balance Point ,ยอดไม่พอตัดกรุณาเติม Wallet ก่อนทำรายการ <hr>';  
                          ?> <a href="<?php echo base_url('wallets/listcompany');?>">Back </a> <?php Die();
                      }
                         
                 $postdata = array(
			"candidate_id" => $candidate_id,
                        "resume_id"=> $resume_id,
                        "employer_user_id" => $employer_user_id,
                        "lang" => $lang,
                        "employer_id" => $employer_id,
                        "user_id" => $user_id,
                        "wallet_type_id" => $wallet_type_id,
                        "wallet_package_id" => $wallet_package_id,
                        "balance_money"=>$price,
                        "balance_point" =>0,
			); 
             
                
		  #echo '<pre>';print_r($post);echo '</pre>';   echo '<pre>';print_r($postdata);echo '</pre>'; Die();
                 
		$apiurl=$this->config->config['restapi'];
                $url=$apiurl.'Talentonline/wallet/update/';
                //echo $url;
                $request = Requests::post($url, array(), $postdata);
		#echo '<br> Retrun ==>  <pre>'; print_r($request); echo '</pre>';  Die();
                $request=$request->body;
		$json_data = json_decode($request, true);
		 #echo '<pre>'; print_r($json_data); echo '</pre>';  Die();
			$status_code=$json_data['code'];
			$status=$json_data['status'];
			$error=$json_data['error'];
			$remarks=$json_data['remarks'];
			$items_data=$json_data['data'];
              $date=date('Y-m-d-H-i-s');        
              $filename = 'wallet_'.$employer_id.'_'.$date;
              $path='wallet/employer_id_'.$employer_id.'/';
              //SaveJSON($items_data,$filename,true,$path);
                        $count = count($items_data);
                        
                         if($status_code==200){
                            $items_data=$json_data['data'];
                            $count = count($items_data);
                             redirect('wallets/listcompany'); die();
                        }else if($status_code==201){
                            $items_data=$json_data['data'];
                             redirect('wallets/listcompany'); die();
                            $count = 0;
                        }else{ 
                            $count = 0;
                             redirect('wallets/listcompany'); die();
                            #echo 'Can Not Call API Code '.$status_code; Die();
                            
                        } 
    }
 }  