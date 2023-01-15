<?php if(!defined('BASEPATH'))exit('No direct script access allowed');
class Public_model extends CI_Model {
    function __construct(){
        parent::__construct();
         $this->load->helper(array('form', 'url'));
         //Load codeigniter FTP class
          $this->load->library('ftp');
    } 
    public function uploads($data,$FILES){//echo '<pre> $FILES=>'; print_r($FILES); echo '</pre>';   
		    //echo '<pre> $datauploads=>'; print_r($data); echo '</pre>'; #Die();
		   ######################## Directory
								 	   $fullpath=$data['fullpath'];
									   $dir=$data['dir'];
									   if($fullpath==Null){
									   	$fullpath= "/data/product/trueplookpanya/www/product/media/";
									   	//$fullpath= "D:/UwAmp/www/static.trueplookpanya.com/";
									   }if($dir==Null){
									   	$dir= "testdata/na/";
									   }
									   $mkdir=$this->mkdir($fullpath,$dir);
									   //echo '<pre> $mkdir=>'; print_r($mkdir); echo '</pre>';
			######################## UPLOADS Setting
									 $file=$FILES;
                                      #echo '<pre> $file=>'; print_r($file); echo '</pre>';
                                     $filename=$file['name'];
                                     $filetype=$file['type'];
                                     $filetmp_name=$file['tmp_name'];
                                     $filesize=$file['size'];
                                     $fileerror=$file['error'];
                                     $info = pathinfo($filename,PATHINFO_EXTENSION);
                                     $infocheck=$this->filetype($info);
                                     $filetypearrow=(int)$this->filetypearrow($info);
                                     $size=$this->bytesToSize($filesize);
                                     $todayTST = (date("Y-m-d H:i:s", time()));
                                     $filenamenew='file_'.$i.$todayTST.'.'.$info;
									 ####################################
										$datalist= array('filename' => $filename,
							                'filenamenew' => $filenamenew,
							                'filetype' => $filetype,
							                'filetmp_name' => $filetmp_name,
							                'info' => $info,
							                'filetypename' => $infocheck,
							                'filetypearrow' => $filetypearrow,
							                'filesize' => $filesize,
							                'size' => $size,
							                'mkdir' =>$mkdir,
							                );
									    echo '<pre> $datalist=>'; print_r($datalist); echo '</pre>'; 
						###################
						##UPLADS START
						######################## UPLOADS CONFIG     
					   $todayTST = (date("YmdHis", time()));
					   $filenew_name='file_'.$i.'_'.$todayTST.$filesize.'.'.$info;
					   $filename=$file['name'];
				       $filetype= $file['type'];
				       $filetmp_name= $file['tmp_name'];
				       $fileerror= $file['error'];
				       $filesize= $file['size'];
					   $fullpaths=$fullpath.$dir;
						$uploads=$this->uploadsfiledata($fullpaths,$filetmp_name,$filenew_name);
						$dataretrun= array('fileinfo' => $datalist,
							     		   'uploads' => $uploads,
							     		   'data' => $data,
							              );
				
						return $dataretrun;   
    }
    public function uploads2($data,$FILES){
 	
 	
 	         #echo '<pre> $data=>'; print_r($data); echo '</pre>';  	
		     #echo '<pre> $FILES=>'; print_r($FILES); echo '</pre>';  Die();
 	
     /*	
             	$pathurl=$this->config->item("static");							 
				$static_path=$this->config->item("static_path");
		
				$file_name = $FILES["fileuploads"]["name"];
		        $file_size = $FILES["fileuploads"]["size"];
		        $file_type = $FILES["fileuploads"]["type"];
		        $tmp_name = $FILES["fileuploads"]["tmp_name"];
		        $fullpath = "hash_lesson_plan";
		        $dir='media';
		        $data = array(
		                'mul_level_id' => $this->input->post('mul_level_id'),
		                'namefile' => $this->input->post('namefile'),
		                'fullpath' => $fullpath,
		                'dir' => $dir,
		                'file_name' => ($file_name),
		                'file_size' => ($file_size),
		                'file_type' => ($file_type),
		        );
		        $this->public_model->uploads($data,$FILES);
		*/
		     
		   
		   $datauploads = array(
		                'namefile' => $data['namefile'],
		                'fullpath' => $data['fullpath'],
		                'dir' => $data['dir'],
		                'file_name' => $data['file_name'],
		                'file_size' => $data['file_size'],
		                'file_type' => $data['file_type'],
		        ); 
		        
		        
		   echo '<pre> $datauploads=>'; print_r($datauploads); echo '</pre>';  	
		   //echo '<pre> $FILES=>'; print_r($FILES); echo '</pre>'; # Die();
		    
		    
									######################## Directory
								 	   $fullpath=$data['fullpath'];
									   $dir=$data['dir'];
									   if($fullpath==Null){
									   	#$fullpath= "/data/product/trueplookpanya/www/product/media/";
									   	$fullpath= "D:/UwAmp/www/static.trueplookpanya.com/";
									   }if($dir==Null){
									   	$dir= "testdata/";
									   }
									  
									    $file=$filenamenew;
									    $mkdir=$this->mkdir($fullpath,$dir);
									    echo '<pre> $mkdir=>'; print_r($mkdir); echo '</pre>';
								    ######################## UPLOADS Setting
		    $i=1;	
		    echo '<hr>';
		    foreach ($FILES as $key=> $file) {
                                    # echo '<pre> $file=>'; print_r($file); echo '</pre>';
                                     $filename=$file['name'];
                                     $filetype=$file['type'];
                                     $filetmp_name=$file['tmp_name'];
                                     $filesize=$file['size'];
                                     $fileerror=$file['error'];
                                     $info = pathinfo($filename,PATHINFO_EXTENSION);
                                     $infocheck=$this->filetype($info);
                                     $filetypearrow=(int)$this->filetypearrow($info);
                                     $size=$this->bytesToSize($filesize);
                                     $todayTST = (date("Y-m-d H:i:s", time()));
                                     $filenamenew=$todayTST.'.'.$info;
                                     
                                     
                                     
                                     
                                     echo 'No.'.$i.') ';echo '<br>'; 
                                     echo '$filename=>'.$filename; echo '<br>';
                                     echo '$filenamenew=>'.$filenamenew; echo '<br>';
                                     echo '$filetype=>'.$filetype; echo '<br>';
                                     echo '$filetmp_name=>'.$filetmp_name; echo '<br>';
                                     echo '$info=>'.$info; echo '<br>';
                                     echo '$infocheck=>'.$infocheck; echo '<br>';
                                     echo '$filetypearrow=>'.$filetypearrow; echo '<br>';
                                     echo '$size=>'.$size; echo '<br>';
                                     if($filetypearrow==0){
									 	echo 'file type not arrow'; Die();
									 }else{
									 	echo 'file type  arrow'; echo '<br>'; 
									 }
                                     echo '$filesize=>'.$filesize; echo '<hr>';
                                   
                                   $i++;  
                                }
		    Die();
		   
		   
		   $file_name = $FILES["fileuploads"]["name"];
		   $file_size = $FILES["fileuploads"]["size"];
		   $file_type = $FILES["fileuploads"]["type"];
		   $tmp_name = $FILES["fileuploads"]["tmp_name"];       
		   $todayTST = (date("Y-m-d H:i:s", time()));
		   
		   
		   
		   $filenew_name='file_'.$todayTST;
		   
		   
	 	  
	        $size='1024';
	        $set='500';
	        $max_size=(int)$size*$set;

	        $folder = ($fullpath . DIRECTORY_SEPARATOR . $dir);
            $configuploads['upload_path'] = $folder; // โฟลเดอร์ ตำแหน่งเดียวกับ root ของโปรเจ็ค
            $configuploads['max_size'] = $max_size; // ขนาดไฟล์ (kb)  0 คือไม่จำกัด ขึ้นกับกำหนดใน php.ini ปกติไม่เกิน 2MB
            $configuploads['allowed_types'] = '*';
            #$configuploads['max_width'] = '1024';  // ความกว้างรูปไม่เกิน
			#$configuploads['max_height'] = '768'; // ความสูงรูปไม่เกิน
            $configuploads['remove_spaces'] = TRUE;
            $configuploads['overwrite'] = TRUE;
            $configuploads['file_name'] = $filenew_name; // ชื่อไฟล์ ถ้าไม่กำหนดจะเป็นตามชื่อเพิม
            $fileinput=$configuploads['file_name'];
           
            $this->upload->initialize($configuploads); // เรียกใช้การตั้งค่า  
            $this->load->library("upload", $configuploads); 
            
	      ######################## UPLOADS
	       if ($this->upload->do_upload('fileuploads')) { // ทำการอัพโหลดไฟล์จาก input file ชื่อ fileuploads
                // Files Upload Success
                $status='Uploads Done!';
                $errors='';
                $file_name=$data['file_name'];
                $ext = end(explode(".", $file_name));
				$new_file2_name = $file. '.' .$ext;
				$new2 = $new_file2_name;
                
                $datareturn= array(
		                'namefile' => $data['namefile'],
		                'fullpath' => $data['fullpath'],
		                'dir' => $data['dir'],
		                'file_name' => $new2, //$data['file_name'],
		                'file_size' => $data['file_size'],
		                'file_type' => $data['file_type'],
		                'status' => $status,
		                'error' => $errors,
		        );   
	            return $datareturn;
	            //SQL
            }else{
                // Files Upload Not Success!!
                  $errors = $this->upload->display_errors();
                  $datareturn= array(
		                'namefile' => $data['namefile'],
		                'fullpath' => $data['fullpath'],
		                'dir' => $data['dir'],
		                'file_name' => $data['file_name'],
		                'file_size' => $data['file_size'],
		                'file_type' => $data['file_type'],
		                'status' => 'error',
		                'error' => $errors,
		        ); 
                 return $datareturn;
            }
	      
	      
	      
	      
	      
	     /*
			if($this->upload->do_upload()){
				$data = array('upload_data' => $this->upload->data());
				$status='Uploads Done!';
	            return $status;
			}else{
				$error = array('error' => $this->upload->display_errors());
				 $status=$error;
	             return $status;
			}
		*/
 /**
 <?php echo $error;?> <!-- Error Message will show up here -->
<?php echo form_open_multipart('uploads');?>
<?php echo "<input type='file' name='userfile' size='20' />"; ?>
<?php echo "<input type='submit' name='submit' value='upload' /> ";?>
<?php echo "</form>"?>
 */
	     
	    /**
	     //DB
		$newdata = array(
	        'service_title' => $this->input->post('service_title'),
	        'service_detail' => $this->input->post('service_detail'),
	        'service_img' => $file_upload,
	        'service_update' => date("Y-m-d H:i:s")
	    );
    	return $this->db->update('tbl_service', $newdata,array('service_id'=>$id));
		*/ 
    }
    public function mutliuploads($data,$FILES){
    	
######################TEST###################
/*

    	//echo '<pre> $_FILES=>'; print_r($_FILES); echo '</pre>';  
    	//echo '<pre> $data=>'; print_r($data); echo '</pre>'; Die();
    	########## File 1
        $f1n_n1 = $_FILES["lesson_file1"]["name"];
        ##########
        //echo '<pre> $f1n_n1=>'; print_r($f1n_n1); echo '</pre>';
        $info =pathinfo($f1n_n1,PATHINFO_EXTENSION);
        $infocheck=$this->public_model->filetype($info);
        $tabbledb='lesson_plan';
        $tabbleid='lesson_plan_id';
        $selectdesc=$this->public_model->selectdesc($tabbledb,$tabbleid);
		$lastid=$selectdesc[$tabbleid];
		$last_id=$lastid+1;
        $filenamenew='LESSON_PLAN_1_'.$last_id.'.'.$info;
        $f1n=$filenamenew;
        ##########
        $f1s = $_FILES["lesson_file1"]["size"];
        $f1t = $_FILES["lesson_file1"]["type"];
        $f1p = $_FILES["lesson_file1"]["tmp_name"];
        ########## File 2
        $f2n_n1 = $_FILES["lesson_file2"]["name"];
        ##########
        $info2=pathinfo($f2n_n1,PATHINFO_EXTENSION);
        $infocheck2=$this->public_model->filetype($info2);
        $filenamenew2='LESSON_PLAN_2_'.$last_id.'.'.$info2;
        $f2n=$filenamenew2;
        ##########
        $f2s = $_FILES["lesson_file2"]["size"];
        $f2t = $_FILES["lesson_file2"]["type"];
        $f2p = $_FILES["lesson_file2"]["tmp_name"];
        ////ref from set of data in db
        $lesson_path = "hash_lesson_plan";
        
        
        $filetypearrow1=(int)$this->public_model->filetypearrow($info);
        $filetypearrow2=(int)$this->public_model->filetypearrow($info2);
        if($filetypearrow1==0 || $filetypearrow2==0){
			$message=' ไม่อนุญาติให้ Uploads File .'.$info.' หรือ .'.$info2; 
			echo '<script>alert("' . $message . '"); window.history.back();</script>';
			Die();
		}
        
        // redirect('sitemin/teacher/alllessonplan');
        
        //'lesson_plan_id' => $this->input->post('lesson_plan_id'),
        $data = array(
                'mul_level_id' => $this->input->post('mul_level_id'),
                'mul_category_id' => $this->input->post('mul_category_id'),
                'lesson_plan_no' => $this->input->post('lesson_plan_no'),
                'lesson_unit_no' => $this->input->post('lesson_unit_no'),
                'lesson_subject' => $this->input->post('lesson_subject'),
                'lesson_sub_subject' => $this->input->post('lesson_sub_subject'),
                'lesson_time_hour' => $this->input->post('lesson_time_hour'),
                'lesson_object1' => $this->input->post('detail1'),
                'lesson_object2' => $this->input->post('detail2'),
                'lesson_object3' => $this->input->post('detail3'),
                'lesson_object4' => $this->input->post('detail4'),
                'lesson_object5' => $this->input->post('detail5'),
                'lesson_object6' => $this->input->post('detail6'),
                'lesson_object7' => $this->input->post('detail7'),
                'lesson_object8' => $this->input->post('detail8'),
                'credit_by_name' => $this->input->post('credit_by_name'),
                'credit_by_url' => $this->input->post('credit_by_url'),
                'lesson_path' => $lesson_path,
                'lesson_file1_name' => ($f1n),
                'lesson_file1_size' => ($f1s),
                'lesson_file1_type' => ($f1t),
                'lesson_file2_name' => ($f2n),
                'lesson_file2_size' => ($f2s),
                'lesson_file2_type' => ($f2t),
                'member_id' => $this->input->post('member_id'),
                'add_date' => date('Y-m-d H:i:s'),//$this->input->post('add_date'),
                'last_update_date'=> date('Y-m-d H:i:s'),//=> $this->input->post('last_update_date'),
                'is_move' => $this->input->post('is_move'),
                'year_path' => $this->input->post('year_path'),
                'record_status' => $this->input->post('status'),
        );
        //_vd($data);
		########## File Setting
		$fullpath= "/data/product/trueplookpanya/www/product/media/";
		// $fullpath='D:/UwAmp/www/static.trueplookpanya.com/testdata/';
		$dir1='hash_lesson_plan/'.$last_id.'/';
		$dir='hash_lesson_plan/'.$last_id.'/'.$last_id.'/';
		$_FILES["fullpath"]=$fullpath;
        $_FILES["dir"]= $dir;
        
        if($fullpath==null || $dir==Null){
			$message=' กรุณาระบบ   fullpath และ Dir '; 
			echo '<script>alert("' . $message . '"); window.history.back();</script>';
			Die();
		}
        
        ######################## Directory
        if($statusfileexists2==0){
		$mkdir1=$this->public_model->mkdir($fullpath,$dir1);
		}
		#$statusmkdir1=$mkdir1['status'];
		$mesagemkdir1=$mkdir1['mesage'];
		#echo  $mesagemkdir1; echo '<br>';
		$mkdir=$this->public_model->mkdir($fullpath,$dir);
		#$statusmkdir=$mkdir['status'];
		$mesagemkdir=$mkdir['mesage'];
		# echo  $mesagemkdir; echo '<br>';
        $table='lesson_plan';
        $_FILES["tabbledb"]= $table;
		$_FILES["tabbleid"]= 'lesson_plan_id';
		$_FILES["fildupdate"]= 'lesson_path';
		#### Rename
		$file1='LESSON_PLAN_1_'.$last_id;
		$file2= 'LESSON_PLAN_2_'.$last_id;
        $_FILES["lesson_file1"]["file_newname"]=$file1;
        $_FILES["lesson_file2"]["file_newname"]=$file2;
        #### 
        
        $fileexists1=$this->public_model->fileexists($fullpath.$dir,$file1.'.'.$info);
        $statusfileexists1=$fileexists1['status'];
        if($statusfileexists1=='1'){
			$filedelete1=$this->public_model->filedelete($fullpath.$dir,$file1.'.'.$info);
			#echo '<pre> $filedelete1=>';  print_r($filedelete1); echo '</pre>'; echo'<br>'; 
		}
        #echo'$statusfileexists1=>'.$statusfileexists1; echo'<br>'; 
        $fileexists2=$this->public_model->fileexists($fullpath.$dir,$file2.'.'.$info2);
        $statusfileexists2=(int)$fileexists2['status'];
         if($statusfileexists2=='1'){
			$filedelete2=$this->public_model->filedelete($fullpath.$dir,$file2.'.'.$info2);
			 #echo '<pre> $filedelete2=>'; print_r($filedelete2); echo '</pre>'; echo'<br>'; 
		}
      
       # echo'$statusfileexists2=>'.$statusfileexists2; echo'<br>'; 
       # echo '</pre>'; print_r($fullpath.$dir.$file1.'.'.$info); echo '</pre>';echo'<br>';  
       # echo '</pre>$fileexists1=>'; print_r($fileexists1); echo '</pre>'; echo'<br>'; 
       # echo '</pre>'; print_r($fullpath.$dir.$file2.'.'.$info2); echo '</pre>'; echo'<br>'; 
       # echo '</pre> $fileexists2=>'; print_r($fileexists2); echo '</pre>'; echo'<br>'; 
       # Die();
       $mutliuploads=$this->public_model->mutliuploads($data,$_FILES); 
       #echo '<pre> $mutliuploads=>'; print_r($mutliuploads); echo '</pre>'; Die();
        $datainsert = array(
                'member_id' => $this->input->post('member_id')
                , 'mul_level_id' => $this->input->post('mul_level_id')
                , 'mul_category_id' => $this->input->post('mul_category_id')
                , 'lesson_plan_no' => $this->input->post('lesson_plan_no')
                , 'lesson_unit_no' => $this->input->post('lesson_unit_no')
                , 'lesson_subject' => $this->input->post('lesson_subject')
                , 'lesson_sub_subject' => $this->input->post('lesson_sub_subject')
                , 'lesson_time_hour' => $this->input->post('lesson_time_hour')
                , 'lesson_object1' => $this->input->post('lesson_object1')
                , 'lesson_object2' => $this->input->post('lesson_object2')
                , 'lesson_object3' => $this->input->post('lesson_object3')
                , 'lesson_object4' => $this->input->post('lesson_object4')
                , 'lesson_object5' => $this->input->post('lesson_object5')
                , 'lesson_object6' => $this->input->post('lesson_object6')
                , 'lesson_object7' => $this->input->post('lesson_object7')
                , 'lesson_object8' => $this->input->post('lesson_object8')
                , 'credit_by_name' => $this->input->post('credit_by_name')
                , 'credit_by_url' => $this->input->post('credit_by_url')
                , 'lesson_path' => $dir
                , 'lesson_file1_name' => ($f1n)
                , 'lesson_file1_size' => ($f1s)
                , 'lesson_file2_name' => ($f2n)
                , 'lesson_file2_size' => ($f2s)
                , 'add_date' => date('Y-m-d H:i:s') //$todayTST
                , 'last_update_date' => date('Y-m-d H:i:s') //$todayTST
                , 'year_path' => 'product'
                , 'is_move' => $this->input->post('is_move')
				, 'record_status' => $this->input->post('record_status')
        );
       	 $lastinsert=$this->public_model->insertdata($table,$datainsert);
        #echo '<pre> $lastinsert=>'; print_r($lastinsert); echo '</pre>';echo '<pre>';print_r($data); echo '</pre>';
		//$insertdata=$this->public_model->insertdata($table,$data);
		//echo '<pre> $_FILES=>'; print_r($_FILES); echo '</pre>'; echo '<pre> $data=>'; print_r($data); echo '</pre>'; Die();
		
         //$this->public_model->uploads($data,$_FILES); 	Die();
         //$this->teacher_model->addsave_v3($data, $_FILES); Die();
        //$this->uploadv3($id, $yp, $no, $img );
        redirect('sitemin/teacher/alllessonplan');
 	
*/
######################TEST###################
    	 #echo '<pre> Public_model $FILES=>'; print_r($FILES); echo '</pre>';   
		 #echo '<pre> Public_model  $datauploads=>'; print_r($data); echo '</pre>';   Die();
		   
		   ######################## Directory
								 	   $fullpath=$FILES['fullpath'];
									   $dir=$FILES['dir'];	
									   if($fullpath==Null){
									   	#echo 'Error Not have fullpath'; Die();
				 					    //$fullpath= "/data/product/trueplookpanya/www/product/media/";
									   	//$fullpath= "D:/UwAmp/www/static.trueplookpanya.com/";
									   }if($dir==Null){
									   	//$dir= "testdata/na/";
									   	#echo 'Error Not have dir'; Die();
									   }
									   $mkdir=$this->mkdir($fullpath,$dir);
									   //echo '<pre> $mkdir=>'; print_r($mkdir); echo '</pre>';
			######################## UPLOADS Setting
			 $i=1;
			 $arr_result = array();
			 foreach ($FILES as $key=> $file) {
			 						#$file=array();
                                    # echo '<pre> $file=>'; print_r($file); echo '</pre>';
                                     $filename=@$file['name'];
                                     #echo '<pre> $filename=>'; print_r($filename); echo '</pre>';
                                     $filetype=@$file['type'];
                                     $filetmp_name=@$file['tmp_name'];
                                     $file_newname=@$file['file_newname'];
                                     $filesize=@$file['size'];
                                     $fileerror=@$file['error'];
                                     $info = pathinfo($filename,PATHINFO_EXTENSION);
                                     $infocheck=$this->filetype($info);
                                     $filetypearrow=(int)$this->filetypearrow($info);
                                     $size=$this->bytesToSize($filesize);
                                     $todayTST = (date("YmdHis", time()));
                                     if($last_id==Null){
									 	$last_id='0';
									 }
                                     $filenamenew=$file_newname.'.'.$info; //'file_'.$i.$todayTST.'.'.$info;
									 ####################################
									 $datalist= array('filename' => $filename,
							                'filenamenew' => $filenamenew,
							                'filetype' => $filetype,
							                'filetmp_name' => $filetmp_name,
							                'info' => $info,
							                'filetypename' => $infocheck,
							                'filetypearrow' => $filetypearrow,
							                'filesize' => $filesize,
							                'size' => $size,
							                'mkdir' =>$mkdir,
							                );
									  // echo '<pre> $datalist=>'; print_r($datalist); echo '</pre>'; 
						###################
						##UPLADS START
						######################## UPLOADS CONFIG     
					   $todayTST = (date("YmdHis", time()));
					   $filenew_name=$filenamenew; 
					   //'file_'.$infocheck.$i.$todayTST.$filesize.'.'.$info;
					    
				        
				       
				        
				       
					   $fullpaths=$fullpath.$dir;
						$uploads=$this->uploadsfiledata($fullpaths,$filetmp_name,$filenew_name);
						$dataretrun= array('fileinfo' => $datalist,
							     		   'uploads' => $uploads,
							     		   'listdata' => $data,
							              );
						 #echo '<pre> $dataretrun=>'; print_r($dataretrun); echo '</pre>';  
						
					 $arr_result['data'][$i] = $dataretrun;	
						 
				$i++;
		}
		return $arr_result;   
    }
    public function mutliuploads2($data,$FILES){
    	  ######################## Directory
								 	   $fullpath=$FILES['fullpath'];
									   $dir=$FILES['dir'];	
									   if($fullpath==Null){
									   	#echo 'Error Not have fullpath'; Die();
				 					    //$fullpath= "/data/product/trueplookpanya/www/product/media/";
									   	//$fullpath= "D:/UwAmp/www/static.trueplookpanya.com/";
									   }if($dir==Null){
									   	//$dir= "testdata/na/";
									   	#echo 'Error Not have dir'; Die();
									   }
									   $mkdir=$this->mkdir($fullpath,$dir);
									   //echo '<pre> $mkdir=>'; print_r($mkdir); echo '</pre>';
			######################## UPLOADS Setting
			 $i=1;
			 $arr_result = array();
			 foreach ($FILES as $key=> $file) {
			 						#$file=array();
                                    # echo '<pre> $file=>'; print_r($file); echo '</pre>';
                                     $filename=@$file['name'];
                                     #echo '<pre> $filename=>'; print_r($filename); echo '</pre>';
                                     $filetype=@$file['type'];
                                     $filetmp_name=@$file['tmp_name'];
                                     $file_newname=@$file['file_newname'];
                                     $filesize=@$file['size'];
                                     $fileerror=@$file['error'];
                                     $info = pathinfo($filename,PATHINFO_EXTENSION);
                                     $infocheck=$this->filetype($info);
                                     $filetypearrow=(int)$this->filetypearrow($info);
                                     $size=$this->bytesToSize($filesize);
                                     $todayTST = (date("YmdHis", time()));
                                     if($last_id==Null){
									 	$last_id='0';
									 }
                                     $filenamenew=$file_newname.'.'.$info; //'file_'.$i.$todayTST.'.'.$info;
									 ####################################
									 $datalist=array('filename' => $filename,
							                'filenamenew' => $filename, //$filenamenew,
							                'filetype' => $filetype,
							                'filetmp_name' => $filetmp_name,
							                'info' => $info,
							                'filetypename' => $infocheck,
							                'filetypearrow' => $filetypearrow,
							                'filesize' => $filesize,
							                'size' => $size,
							                'mkdir' =>$mkdir,
							                );
									  // echo '<pre> $datalist=>'; print_r($datalist); echo '</pre>'; 
						###################
						##UPLADS START
						######################## UPLOADS CONFIG     
					   $todayTST = (date("YmdHis", time()));
					  // $filenew_name=$filenamenew; 
					   //'file_'.$infocheck.$i.$todayTST.$filesize.'.'.$info;
					   $fullpaths=$fullpath.$dir;
					   $filenew_name=$filename;
					   $uploads=$this->uploadsfiledata($fullpaths,$filetmp_name,$filenew_name);
					   $dataretrun= array('fileinfo' => $datalist,
							     		   'uploads' => $uploads,
							     		   'listdata' => $data,
							              );
						 #echo '<pre> $dataretrun=>'; print_r($dataretrun); echo '</pre>';  
					 $arr_result['data'][$i]=$dataretrun;	
						 
				$i++;
		}
		return $arr_result;   
    }
    public function filetype($ext){
	 	  ########################
	      if(in_array($ext, array('jpg', 'gif', 'png', 'jpeg','JPG', 'GIF', 'PNG', 'JPEG'))){
	      	$ext='image';
	        return $ext;	
		  }if(in_array($ext, array('txt','csv','xls','xlsx','ppt','doc','docx'))){
	      	$ext='document';
	        return $ext;	
		  }if(in_array($ext, array('pdf', 'application/pdf'))){
	      	$ext='pdf';
	        return $ext;	
		  }if(in_array($ext, array('swf', 'flv'))){
	        $ext='flash';
	        return $ext;	
	      }if(in_array($ext, array('mp3', 'wma', 'wav', '3gp'))){
	        $ext='audio';
	        return $ext;	
	      }if(in_array($ext, array('mp4', 'wmv', 'mpg', 'mpeg', 'm4v'))){
	        $ext='video';
	        return $ext;
	      }if(in_array($ext, array('zip','rar'))){
		  	$ext='zip';
	        return $ext;
	      }
	      ########################  
    }
    public function filetypearrow($ext){
	 	  ########################
	      ########################
	      if(in_array($ext, array('jpg', 'gif', 'png', 'jpeg','JPG', 'GIF', 'PNG', 'JPEG'))){
	      	$ext='image';
	        $exts=1;
		  }else if(in_array($ext, array('txt','csv','xls','xlsx','ppt','doc','docx'))){
	      	$ext='document';
	        $exts=2;
		  }else if(in_array($ext, array('pdf', 'application/pdf'))){
	      	$ext='pdf';
	        $exts=3;
		  }else if(in_array($ext, array('swf', 'flv'))){
	        $ext='flash';
	       	$exts=4;
	      }else if(in_array($ext, array('mp3', 'wma', 'wav', '3gp'))){
	        $ext='audio';
	        $exts=5;
	      }else if(in_array($ext, array('mp4', 'wmv', 'mpg', 'mpeg', 'm4v'))){
	        $ext='video';
	        $exts=6;
	      }else if(in_array($ext, array('zip','rar'))){
		  	$ext='zip';
	        $exts=7;
	      }else{
	      	$ext='Uuknow';
		  	 $exts=0;
		  } 
	       //return $ext;
	       return (int)$exts;	
	      ########################  
    }
    public function filetypearrowall($ext){
	 	   ########################
	      if(in_array($ext, array('jpg', 'gif', 'png', 'jpeg','JPG', 'GIF', 'PNG', 'JPEG'))){
	      	$ext='image';
	        $exts=1;
		  }else if(in_array($ext, array('txt','csv','xls','xlsx','ppt','doc','docx'))){
	      	$ext='document';
	        $exts=2;
		  }else if(in_array($ext, array('pdf', 'application/pdf'))){
	      	$ext='pdf';
	        $exts=3;
		  }else if(in_array($ext, array('swf', 'flv'))){
	        $ext='flash';
	       	$exts=4;
	      }else if(in_array($ext, array('mp3', 'wma', 'wav', '3gp'))){
	        $ext='audio';
	        $exts=5;
	      }else if(in_array($ext, array('mp4', 'wmv', 'mpg', 'mpeg', 'm4v'))){
	        $ext='video';
	        $exts=6;
	      }else if(in_array($ext, array('zip','rar'))){
		  	$ext='zip';
	        $exts=7;
	      }else{
	      	$ext='Uuknow';
		  	 $exts=0;
		  } 
	       //return $ext;
	       return (int)$exts;	
	      ########################  
    }
    public function bytesToSize($bytes) {
                $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                if ($bytes == 0) return 'n/a';
                $i = intval(floor(log($bytes) / log(1024)));
                if ($i == 0) return $bytes . ' ' . $sizes[$i]; 
                return round(($bytes / pow(1024, $i)),2,PHP_ROUND_HALF_UP). ' ' . $sizes[$i];
	}
	public function mkdir($fullpath,$dir){
 		if (!is_dir($fullpath.$dir)){
 			   #echo '<pre> $fullpath.$dir=>'; print_r($fullpath.$dir); echo '</pre>'; Die();
				  mkdir($fullpath . DIRECTORY_SEPARATOR . $dir);
				  chmod($fullpath . DIRECTORY_SEPARATOR . $dir, 0777);
				  $status=1;
				  $mesage='Create direrory '.$fullpath.'/'.$dir.' Done!';
				  $mkdirdata = array(
	                'status' => $status,
	                'mesage' => $mesage,
	                );
				 return $mkdirdata;
				}else{
				  $status=0;
				  $mesage='Dir '.$fullpath.'/'.$dir.' have already!';
				  $mkdirdata = array(
	                'status' => $status,
	                'mesage' => $mesage,
	                );
				 return $mkdirdata;
								            
				} 
	}
	public function fileexists($fullpath,$file){
        $full=$fullpath.$file;
        //echo "<pre> full=>"; print_r($full); echo "</pre>";
 		  if(file_exists($full)){
		  	      $status=1;
				  $mesage='Path '.$fullpath.' Have file <b>'.$file.'</b> !';
				  $data = array(
	                'status' => $status,
	                'mesage' => $mesage,
	                );
				 return $data;
				}else{
				  $status=0;
				  $mesage='Path '.$fullpath.' File  <b>'.$file.'</b> not found!';
				  $data = array(
	                'status' => $status,
	                'mesage' => $mesage,
	                );
				 return $data;				            
				} 
	}
	public function filedelete($fullpath,$file){
 		  if(unlink($fullpath.$file)){
 		  		 $status=1;
				  $mesage='Delete file '.$file.'!';
				  $data = array(
	                'status' => $status,
	                'mesage' => $mesage,
	                );
				 return $data;
				}else{
				  $status=0;
				  $mesage='File  '.$file.'not delete!';
				  $data = array(
	                'status' => $status,
	                'mesage' => $mesage,
	                );
				 return $data;
								            
				} 
	}
    public function filedeleteone($file){
 		  if(unlink($file)){
 		  		 $status=1;
				  $mesage='Delete file '.$file.'!';
				  $data = array(
	                'status' => $status,
	                'mesage' => $mesage,
	                );
				 return $data;
				}else{
				  $status=0;
				  $mesage='File  '.$file.'not delete!';
				  $data = array(
	                'status' => $status,
	                'mesage' => $mesage,
	                );
				 return $data;
								            
				} 
	}  
    public function selectasc($table,$filddb) {
			$this->db->select('*');
			$this->db->from($table);
			$this->db->order_by($filddb, 'ASC');
			$this->db->limit(1); 
			$result_array = $this->db->get()->result_array();
		    $data=$result_array[0];
            return $data;
        } 
    public function selectdesc($table,$filddb) {
			$this->db->select('*');
			$this->db->from($table);
			$this->db->order_by($filddb, 'DESC');
			$this->db->limit(1); 
			$result_array = $this->db->get()->result_array();
		    $data=$result_array[0];
            return $data;
        }    
    public function selectgetdatawhereextensejoin($table,$fields,$data,$wherefile='',$wheredata='') {
	    //pega os campos passados para o select
	    /**
		 //exsample
		$data_field = array(
		        'NameProduct' => 'product.IdProduct',
		        'IdProduct' => 'product.NameProduct',
		        'NameCategory' => 'category.NameCategory',
		        'IdCategory' => 'category.IdCategory'
		        );
	    $data_join = array
	                    ( 'product' => 'product_category.IdProduct = product.IdProduct',
	                      'category' => 'product_category.IdCategory = category.IdCategory',
	                      'product' => 'product_category.IdProduct = product.IdProduct'
	                    );
	    $table='product_category';
	    $fields=$data_field;
	    $data=$data_join;
	    $wherefile='product.IdProduct';
	    $wheredata='1'
    	$product_category = $this->public_model->selectgetdatawhereextensejoin($table,$fields,$data,$wherefile,$wheredata);
		*/
	    
	    foreach($fields as $coll => $value){
	        $this->db->select($value);
	    }
	    //pega a tabela
	    $this->db->from($table);
	    //pega os campos do join
	    foreach($data as $coll => $value){
	        $this->db->join($coll, $value);
	    }
	    
	    // where
	    if($wherefile!==Null && $wheredata!==Null){
			$this->db->where($wherefile,$wheredata);
		}

	    
	    //obtem os valores
	    $query = $this->db->get();
	    //retorna o resultado
	    return $query->result();
	}
    public function datawhere($table,$filewhere,$datawhere){
            $sql = "select * from $table where  $filewhere=$datawhere ";
             #echo '</pre> $sql=>';echo $sql;echo '</pre>'; Die(); 
            $data = $this->db->query($sql)->result_array();
            if($data){  
            	return $data['0'];
            }else{
            	$data=null;
            	return $data;
            }
        }
	public function insertdata($table,$data){
    	//echo 'table=>'.$table;echo'<pre> $data=>';print_r($data);echo'<pre>'; #Die();
        $result_data_insert=$this->db->insert($table, $data);    
	   if($result_data_insert==1){
	   	$result_data=$this->db->insert_id();
	   }else{
	   		$result_data=0;
	   }
	    return $result_data;    
							            
	}
	public function updatedata($data,$table,$id,$filddb){
		/*
		 echo '<pre> $table=>';print_r($table);   
		 echo '<pre> $filddb=>';print_r($filddb);  
		 echo '<pre> $id=>';print_r($id); 
		 echo '<pre> $data=>';print_r($data); echo '</pre>'; Die(); 
		*/	
		   $result_data=$this->db->where($filddb,$id);
		   $result_data=$this->db->update($table,$data);  
		   #debug($result_data);die();
		   if($result_data=='1'){
			$result_data='1';
		   }else{
			$result_data='0';
		   }
		   return $result_data;    
		}
	public function detedata($table,$filddb,$id){
		    $this->db->where($filddb, $id);
  			$result_data=$this->db-> delete($table);
		   #debug($result_data);die();
		   if($result_data=='1'){
			      $status='1';
				  $data = array(
				  	'id' => $id,
	                'mesage' => 'Delete id '.$id.' successful..',
	                'status' => $status,
	                );
	                $result_data=$data;
		   }else{
			      $status='0';
				  $data = array(
				  	'id' => $id,
	                'mesage' => 'Delete id '.$id.' unsuccessful..',
	                'status' => $status,
	                );
	                $result_data=$data;
		   }
		   return $result_data;    

								            
	}
	public function deletedir($dirPath) {
	    if (! is_dir($dirPath)) {
	        throw new InvalidArgumentException("$dirPath must be a directory");
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($dirPath);
	}
	public function delete_folder($dirPath) {  
	          //$dirPath = $_POST['dirpath'];
	          $TrackDir=opendir($dirPath);
	          $i = 0;
	           while ($file = readdir($TrackDir)) { 
	                if ($file == "." || $file == "..") { } 
	                    else {
	                    $narray[$i]=$file;  
	                    $i++;               
	                } 
	            }     
	            $count1 = count($narray);

	            if($count1 > 0){       
	                $r=0;
	                while($r < $count1){
	                    $Filename = $narray[$r];
	                    $myFile = $dirPath."/".$Filename;
	                    unlink($myFile);                        
	                    $r++;
	                }   
	                rmdir($dirPath);
	            }else{
	                rmdir($dirPath);
	            }
	            closedir($TrackDir);
	}
	public function scandir($dir) { 
		$objScan = scandir($dir);
		$i=1;
		$arr_result = array();
		foreach ($objScan as $key=> $value) {
		   // echo "folder : $value <br>";
		    $dataretrun= array('no' => $i,
							   'folder' => $value,
							   );
		    $arr_result['scandir'][$i] = $dataretrun;	
			$i++;
		}
		return $arr_result;  
	}
	public function opendir($dir) { 
		if (is_dir($dir)) {
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
		            echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
		        }
		        closedir($dh);
		    }
		}
	}
	public function createfile($dir,$file){
		$strFileName = $dir.$file;
		$objCreate = fopen($strFileName, 'w');
		if($objCreate){
			 $mesage="File Created.";
			 $status='1';
				  $data = array(
	                'mesage' => $mesage,
	                'status' => $status,
	                );
			return $data;
			
		}else{
			$mesage="File Not Create.";
			 $status='1';
				  $data = array(
	                'mesage' => $mesage,
	                'status' => $status,
	                );
			return $data;
		}
		fclose($objCreate);
	}
	public function uploadsfiledata($fullpath,$filetmp_name,$filenew_name){
		
		
					   #echo '<pre> $fullpath=>'; print_r($fullpath); echo '</pre>'; 
					   #echo '<pre> $filetmp_name=>'; print_r($filetmp_name); echo '</pre>'; 
					   #echo '<pre> $filenew_name=>';  print_r($filenew_name); echo '</pre>'; Die();
					   
						$uploaddir=$fullpath;
						//$uploadfile=$uploaddir.basename($filenew_name);
						//$filenew_name=iconv('UTF-8','windows-874',$filenew_name);
						$uploadfile=$uploaddir.$filenew_name;
						
						
						if (move_uploaded_file($filetmp_name,$uploadfile)){
						    $mesage="File  $filenew_name   is valid, and was successfully uploaded!";
						     $status=(int)'1';
						     $dataall=array('status' => $status,
						     			'mesage' => $mesage,
						                );
						     return $dataall;
						}else{
						     $mesage="Possible file  $filenew_name upload attack!";
						     $status=(int)'0';
						     $dataall=array('status' => $status,
						     			'mesage' => $mesage,
						                );
							 return $dataall;
						}
	}
	############# resizeimage Start#############
	public function resizeimage($fullpath='',$dir='',$file='',$filenew_name='',$width='',$height='') {
		
	/* 
	 183x103  $width='183';$height='103';
	 285x160  $width='285';$height='160';
	 386x217  $width='386';$height='217';
	 590x332  $width='590';$height='322';
	 792x446  $width='792';$height='446';
	 800x450  $width='800';$height='450';
	 1200x675  $width='1200';$height='675';
	 1280x720  $width='1280';$height='720';
	 1920x1080  $width='1920';$height='1080';
	*/	
	 $setdebug=0;
		
      if($setdebug==1){
      	echo '<pre> <hr> Function resizeimage <hr> $fullpath=>'; print_r($fullpath); echo '</pre>'; 
      	echo '<pre> $dir=>'; print_r($dir); echo '</pre>';
      	##########################
      	echo '<pre> $file=>'; print_r($file); echo '</pre>';
        echo '<pre> $filenew_name=>'; print_r($filenew_name); echo '</pre>';
      	##########################
	   	echo '<pre> $width=>'; print_r($width); echo '</pre>'; 
       	echo '<pre> $height=>'; print_r($height); echo '</pre>';
        ##########################
        //$full_dir1=$full_dir1=$this->config->item("static_path").$dir;
        #Die();
	  }
        ##check directory
        if($dir==Null){
			#########
			 $filecheck=$fullpath.$file;
			 $full_dir1=$fullpath;
		}else{
			###################
			 $full_dir1=$fullpath.$dir;
	        	if (!is_dir($full_dir1.$dir)){
					#echo ' มี '.$full_dir1;
				}else{
					#echo ' ไม่มีี '.$full_dir1;
					
					if(!is_dir($full_dir1)) {
				              mkdir($full_dir1,0777, true);
				              chmod($full_dir1,0777);
				            }
					
				}
		     $filecheck=$full_dir1.$file;		
			###################	
		}
		##check File
		if(file_exists($filecheck)){
		  	$status_file=1;
		  	$mesage_file='have file '.$filecheck;
		}else{
			$status_file=0;
			$mesage_file='file is'.$filecheck.' not found';
		}
		####
		
		##########
		$size_mg = getimagesize($full_dir1.$file); //ที่อยู่ของไฟล์รูปภาพ
		$size=$size_mg;
		$width_mg=(int)$size_mg[0];// จะได้ความกว้างของรูปภาพ
		$originalWidth=$width_mg;
		$height_mg=(int)$size_mg[1];// จะได้ความสูงของรูปภาพ 
		$originalHeight=$height_mg;
		$width_mg2=$width_mg/2;
		$width2=$width/2;	
		$ratio=$originalWidth/$originalHeight;
		$targetHeight = min($size,max($originalWidth, $originalHeight));
	if($setdebug==1){
		echo '<pre> <hr> $ratio=>'; print_r($ratio); echo '</pre>';
      	echo '<pre> <hr> $targetHeight=>'; print_r($targetHeight); echo '</pre>'; 
      	echo '<pre> $size_mg=>'; print_r($size_mg); echo '</pre>';
       
	  }
	 
      if($setdebug==1){
			echo '<pre> $width_mg=>'; print_r($width_mg); echo '</pre>'; 
			echo '<pre> $height_mg=>'; print_r($height_mg); echo '</pre>'; 
			echo '<pre> $width_mg2=>'; print_r($width_mg2); echo '</pre>';
			echo '<pre> $width2=>'; print_r($width2); echo '</pre>';	
		}
		########## 
		 
		if($width_mg>$height_mg){
			#echo '<br>'; echo $file.' ภาพแนวนอน ';  //echo'<br>'; 
			$detailimg='ภาพแนวนอน';
			$detailimgstatus='1';
			if($height_mg>$width){
				#echo '1. กรุณาใส่ค่า ความกว้าง มากกว่า ความสูง ';   Die();
			}
			#echo'<br>'; 
			if($height>$width){
				//echo '2. กรุณาใส่ค่า <b> ความกว้าง </b> มากกว่า ความสูง '; Die(); 
			}
			
		}elseif($width_mg<$height_mg){
			//echo'<br>';  echo $file.' ภาพแนวตั้ง '; //echo'<br>'; 
			$detailimg='ภาพแนวตั้ง';
			$detailimgstatus='2';
			if($width_mg<$height){
				#echo '1. กรุณาใส่ค่า  ความสูง มากกว่า  ความกว้าง';   Die();
			}
			#echo'<br>'; 
			if($width<$height){
				//echo '2. กรุณาใส่ค่า  <b> ความสูง </b> มากกว่า  ความกว้าง';  Die();
			}
			
		}
		###########
		
		
		
	    $info = pathinfo($file,PATHINFO_EXTENSION);
        $infocheck=$this->filetype($info);
	    /*
	    echo '<pre> $status_file=>'; print_r($status_file); echo '</pre>';
	    echo '<pre> $mesage_file=>'; print_r($mesage_file); echo '</pre>';
	    echo '<pre> $info=>'; print_r($info); echo '</pre>';
	    echo '<pre> $fulenew_name=>'; print_r($fulenew_name.'.'.$info); echo '</pre>';
        echo '<pre> $filecheck=>'; print_r($filecheck); echo '</pre>';
        Die();
        */
		    
				
	//////////////
						$newfile = $full_dir1.$filenew_name.'.'.$info;
						$exp = explode('.' , $file);
						$orgf = substr($file , 0 , -(strlen($exp[count($exp)-1])+1));
						$orgfile=$full_dir1.$orgf.'_org.'.$info;
						copy($filecheck,$orgfile);
						//echo '<pre> $orgfile=>'; print_r($orgfile); echo '</pre>';/Die();
						copy($filecheck,$newfile);
						$copy_status=1;
	/////////////
	
	
	
		ini_set('max_execution_time', 0);
	    $ImagesDirectory = $orgfile;
	    $DestImagesDirectory = $newfile;
	    $NewImageWidth = $width;
	    $NewImageHeight = $height;
	    $Quality = 100;
	    $imagePath = $ImagesDirectory;
	    $destPath = $DestImagesDirectory;
	    $checkValidImage = getimagesize($imagePath);
	    if(file_exists($imagePath) && $checkValidImage){
	    	 	/////Resize////   
				$SrcImage=$orgfile;
				$DestImage=$newfile;
				$thumb_width=$width;  
				$thumb_height=$height;  
				$Quality='100';
				$this->resizeImages($SrcImage,$DestImage, $thumb_width,$thumb_height,$Quality);     
				 
				/////Resize////   
	    }else{
			/////Resize////   
				$SrcImage=$orgfile;
				$DestImage=$newfile;
				$thumb_width=$width;  
				$thumb_height=$height;  
				$Quality='100';
				$this->resizeImages($SrcImage,$DestImage, $thumb_width,$thumb_height,$Quality);     
				 
				/////Resize////  
		}
	

	#echo '<pre> $images=>'; print_r($images); echo '</pre>';
	#echo '<pre> $images_fin=>'; print_r($images_fin); echo '</pre>';
	#echo '<pre> $new_images=>'; print_r($new_images); echo '</pre>';
	#echo '<pre> $filecheck=>'; print_r($filecheck); echo '</pre>';
	    $fileresize=array('fullpath'=>$fullpath,
	    				  'dir'=>$dir,
	    				  'file'=>$file,
	    				  'filenew'=>$filenew_name,
	    				  'width'=>$width,
	    				  'height'=>$height,
	    				  'mesage_file'=>$mesage_file,
	    				  'mesage_filenew'=>$newfile,
	    				  'status_file'=>$status_file,
	    				  'status_copy'=>$copy_status,
	    				  'message'=>'Resize successful',
	    				  'detailimg'=>$detailimg,
						  'detailimgstatus'=>$detailimgstatus,
	    				  
	   					 );
	    
	     return $fileresize;
	} 
	public function resizeImages($SrcImage,$DestImage, $thumb_width,$thumb_height,$Quality){
	    list($width,$height,$type) = getimagesize($SrcImage);
	    switch(strtolower(image_type_to_mime_type($type)))
	    {
	        case 'image/gif':
	            $NewImage = imagecreatefromgif($SrcImage);
	            break;
	        case 'image/png':
	            $NewImage = imagecreatefrompng($SrcImage);
	            break;
	        case 'image/jpeg':
	            $NewImage = imagecreatefromjpeg($SrcImage);
	            break;
	        case 'image/jpg':
	            $NewImage = imagecreatefromjpeg($SrcImage);
	            break;
	       	case 'image/pjpeg':
	            $NewImage = imagecreatefromjpeg($SrcImage);
	            break;
	        default:
	            return false;
	            break;
	    }
	    $original_aspect = $width / $height;
	    $positionwidth = 0;
	    $positionheight = 0;
	    if($original_aspect > 1)    {
	        $new_width = $thumb_width;
	        $new_height = $new_width/$original_aspect;
	        while($new_height > $thumb_height) {
	            $new_height = $new_height - 0.001111;
	            $new_width  = $new_height * $original_aspect;
	            while($new_width > $thumb_width) {
	                $new_width = $new_width - 0.001111;
	                $new_height = $new_width/$original_aspect;
	            }

	        }
	    } else {
	        $new_height = $thumb_height;
	        $new_width = $new_height/$original_aspect;
	        while($new_width > $thumb_width) {
	            $new_width = $new_width - 0.001111;
	            $new_height = $new_width/$original_aspect;
	            while($new_height > $thumb_height) {
	                $new_height = $new_height - 0.001111;
	                $new_width  = $new_height * $original_aspect;
	            }
	        }
	    }
	    if($width < $new_width && $height < $new_height){
	        $new_width = $width;
	        $new_height = $height;
	        $positionwidth = ($thumb_width - $new_width) / 2;
	        $positionheight = ($thumb_height - $new_height) / 2;
	    }elseif($width < $new_width && $height > $new_height){
	        $new_width = $width;
	        $positionwidth = ($thumb_width - $new_width) / 2;
	        $positionheight = 0;
	    }elseif($width > $new_width && $height < $new_height){
	        $new_height = $height;
	        $positionwidth = 0;
	        $positionheight = ($thumb_height - $new_height) / 2;
	    } elseif($width > $new_width && $height > $new_height){
	        if($new_width < $thumb_width) {
	            $positionwidth = ($thumb_width - $new_width) / 2;
	        } elseif($new_height < $thumb_height) {
	            $positionheight = ($thumb_height - $new_height) / 2;
	        }
	    }
	    $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
	    
	    
	    ##########NA
	    $targetImage = imagecreatetruecolor($thumb_width, $thumb_height);
	    $white  = imagecolorallocate($thumb,255,255,255);
		 $white=$thumb;
	    /********************* FOR WHITE BACKGROUND  *************************/
	        //$white = imagecolorallocate($thumb, 255,255,255);
	        //imagefill($thumb, 0, 0, $white);
	    if(imagecopyresampled($white, $NewImage,$positionwidth, $positionheight,0, 0, $new_width, $new_height, $width, $height)) {
	        if(imagejpeg($thumb,$DestImage,$Quality)) {
	            imagedestroy($thumb);
	            return true;
	        }
	    }
	}
	public function resize($source,$destination,$newWidth,$newHeight){
	    ini_set('max_execution_time', 0);
	    $ImagesDirectory = $source;
	    $DestImagesDirectory = $destination;
	    $NewImageWidth = $newWidth;
	    $NewImageHeight = $newHeight;
	    $Quality = 100;
	    $imagePath = $ImagesDirectory;
	    $destPath = $DestImagesDirectory;
	    $checkValidImage = getimagesize($imagePath);
	    if(file_exists($imagePath) && $checkValidImage)
	    {
	        if(resizeImages($imagePath,$destPath,$NewImageWidth,$NewImageHeight,$Quality))
	            echo " --> ".$source.'  --> Resize Successful!<BR><BR>';
	        else
	            echo " --> ".$source.'  --> Resize Failed!<BR><BR>';
	    }
	}
	public function getDirContents($filter = '', &$results = array()) {
	    // Source FOLDER
	    $files = scandir($_SERVER['DOCUMENT_ROOT'].'/imageresize/img/');
	    $fileCount = 1;
	    foreach($files as $key => $value){
	        $ext = explode(".",$value);
	        $fname = $ext[0].round(microtime(true)*1000);
	        $filename = $fname.".".$ext[1];
	        // Source PATH
	        $path = realpath($_SERVER['DOCUMENT_ROOT'].'/imageresize/img/'.$value); 

	        if(!is_dir($path)) {
	            if(empty($filter) || preg_match($filter, $path)){ 
	                echo "Image # ".$fileCount;
	                $results[] = $path;
	                // Destination PATH
	                $destination = $_SERVER['DOCUMENT_ROOT'].'/imageresize/resizedImage/'.$value;

	                // Change the desired "WIDTH" and "HEIGHT"
	                $newWidth = 400; // Desired WIDTH
	                $newHeight = 350; // Desired HEIGHT

	                resize($path,$destination,$newWidth,$newHeight);
	                $fileCount++;
	            }
	        } elseif($value != "." && $value != "..") {
	            getDirContents($path, $filter, $results);
	        }
	    }
	    return $results;
	} 
	############# resizeimage End#############
	############# FTP Congig Start#################
	public function ftpconect($ftpserver='',$ftpuser='',$ftppassword='',$ftpport='21',$pathfileftp='',$dir=''){
		
		if($ftpserver==Null){
			$ftpserver='203.144.185.179';
		}if($ftpuser==Null){
			$ftpuser='trueplookpanya_data';
		}if($ftppassword==Null){
			$ftppassword='OaHsBKgzEp';
		}if($ftpport==Null){
			$ftpport='21';
		}if($pathfileftp==Null){
			$pathfileftp='';
		}if($dir==Null){
			$dir='';
		}
		/*
		$ftpserver='203.144.185.179';
		$ftpuser='trueplookpanya_data';
		$ftppassword='OaHsBKgzEp';
		$ftpport='21';
		$pathfileftp='';
		$dir='';
		$this->load->model('api/public_model');
		$ftpconect=$this->public_model->ftpconect($ftpserver='',$ftpuser='',$ftppassword='',$ftpport='21',$pathfileftp='',$dir='');
		echo '<pre> $ftpconect==>'; print_r($ftpconect); echo '</pre>';   Die();
		*/ 
		$this->load->library('ftp');
		$config['hostname'] = $ftpserver;//'ใส่ไอพีนะ ไม่ไช่ localhost';
		$config['username'] = $ftpuser;//'ใส่ ftp username';
		$config['password'] = $ftppassword;//'ใส่ ftp password';
		$config['port']     = $ftpport; // ใส่ port ปกติ ftp จะใช้พอร์ต 21 ตรวจสอบเองว่าไช่ไหม
		$config['debug']	= TRUE;
		$connect=$this->ftp->connect($config); // ลองเชื่อมต่อดู ได้ไหม
		
		if($dir==Null){ $pathfile=$pathfileftp; }else{ $pathfile=$pathfileftp.$dir; }
		$list = $this->ftp->list_files($pathfile);
		//echo '<pre> $list==>'; print_r($list); echo '</pre>';
		if($connect==1){
			 $message='Connet ftp successful';
			 $code='200';
			 $dataretrun= array('ftpserver' => $ftpserver,
							     'connect_status' => $connect,
							     'code' => $code,
							     'message' => $message,
							     'pathfile' => $pathfile,
							     'data' => $list,
							    );
				
		   return $dataretrun;   
		}else{
			 $message='Connet ftp unsuccessful';
			 $code='404';
			 $dataretrun= array('ftpserver' => $ftpserver,
							     'connect_status' => $connect,
							     'code' => $code,
							     'message' => $message,
							     'pathfile' => Null,
							     'data' => Null,
							    );
				
		   return $dataretrun; 
		}
	}
	public function ftpmkdir($pathfileftp,$dir){
		if($dir==Null){
			$target_dir1=$pathfileftp;
		}else{
			$target_dir1=$pathfileftp.$dir;
		}
		$dirstatus=$this->ftp->list_files($target_dir1);
		if($dirstatus==Null){
            $this->ftp->mkdir($target_dir1,0777);
			$dirstatuscmd=$this->ftp->chmod($target_dir1,0777); 
			 $message='ftp mkdir successful';
			 $code='200';
			 $dataretrun= array('code' => $code,
							     'message' => $message,
							     'pathfile' => $target_dir1,
							     'data' => $dir,
							    );
				
		   return $dataretrun;   
		}else if($pathfileftp==Null ||$dir==Null){
			 $message='ftp mkdir unsuccessful or Directory '.$dir.' is Duplicate  ';
			 $code='404';
			 if($dir==Null){
				$target_dir1=$pathfileftp;
				$dir==Null;
			}else{
				$target_dir1=$pathfileftp;
			}
			 
			 $dataretrun= array('code' => $code,
							     'message' => $message,
							     'pathfile' => $target_dir1,
							     'data' => $dir,
							    );
				
		   return $dataretrun; 
		}else{
			 $message='ftp mkdir unsuccessful or Directory '.$dir.' is Duplicate  ';
			 $code='201';
			 $dataretrun= array('code' => $code,
							     'message' => $message,
							     'pathfile' => $target_dir1,
							     'data' => $dir,
							    );
				
		   return $dataretrun; 
		}
	 
	}
	public function ftpupload($data,$file,$newname){
		/**
		 echo '<pre> $data=>'; print_r($data); echo '</pre>'; 
		 echo '<pre> $file=>'; print_r($file); echo '</pre>'; 
		 echo '<pre> $newname=>'; print_r($newname); echo '</pre>'; Die(); 
		*/
		$fileinputname=@$data['fileinputname'];
		$filename=@$data['file_name'];
		$filesize=@$data['file_size'];
		$filetmp_name=@$data['tmp_name'];
		$file_type=@$data['file_type'];
		$fileerror=@$file['error'];
		$info = pathinfo($filename,PATHINFO_EXTENSION);
		$infocheck=$this->filetype($info);
		$new_name=$newname.'.'.$info;
		 /*
		 echo '<pre> $new_name=>'; print_r($new_name); echo '</pre>';  
		 echo '<pre> $data=>'; print_r($data); echo '</pre>'; 
         echo '<pre> $file=>'; print_r($file); echo '</pre>'; 
		 echo '<pre> $info=>'; print_r($info); echo '</pre>';  #Die();
		 */
		    $path=$data['path'];
		    $dir=$data['dir'];
			$upload_path=$path.$dir;
			#echo '<pre> $upload_path=>'; print_r($upload_path); echo '</pre>'; #Die();
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = '*';
            $uploadlibrary=$this->load->library('upload', $config);
            # echo '<pre> $uploadlibrary=>'; print_r($uploadlibrary); echo '</pre>'; 
            $fileuploads=$this->upload->do_upload($fileinputname);
             #echo '<pre> $fileuploads=>'; print_r($fileuploads); echo '</pre>'; 
            $upload_data = $this->upload->data();
             #echo '<pre> $upload_data=>'; print_r($upload_data); echo '</pre>'; #Die();
            $source_file_path=$upload_data['file_path'];
            $source_client_name=$upload_data['client_name'];
            $source = $upload_path.$source_client_name;
              #echo '<pre> $source=>'; print_r($source); echo '</pre>'; 
              #echo '<pre> $upload_path.$new_name=>'; print_r($upload_path.$new_name); echo '</pre>'; #Die();
           $destination=$upload_path.$filename;
           
           $fileexists=$this->fileexists($upload_path,$filename);
           $status=$fileexists['status'];
          #echo '<pre> $fileexists=>'; print_r($fileexists); echo '</pre>'; //Die();
           if($status==1){
		   	//$delete_file=$this->ftp->delete_file($destination);
		   	#echo '<pre> $delete_file=>'; print_r($delete_file); echo '</pre>'; 
		   }
           #Die();
            
           
          $fileNamesource = $upload_data['full_path']; 
          #echo '<pre> $fileNamesource=>'; print_r($fileNamesource); echo '</pre>';
          #echo '<pre> $destination=>'; print_r($destination); echo '</pre>'; #Die();
          $dofile=$this->ftp->upload($fileNamesource,$destination,0777);
          #echo '<pre> $dofile=>'; print_r($dofile); echo '</pre>';   Die();
        $ftprename=$this->ftp->rename($source,$upload_path.$new_name);
          #echo '<pre> $ftprename=>'; print_r($ftprename); echo '</pre>';  Die();	
          if($dofile==1 &&  $ftprename==1){
			 $message='ftp uploads successful';
			 $code='200';
			 $dataretrun= array('ftpserver' => $ftpserver,
							     'connect_status' => $connect,
							     'code' => $code,
							     'message' => $message,
							     'oldfile' => $destination,
							     'newfile' => $upload_path.$new_name,
							    );
				
		   return $dataretrun;   
		}else{
			 $message='Connet ftp unsuccessful';
			 $code='404';
			 $dataretrun= array('ftpserver' => $ftpserver,
							     'connect_status' => $connect,
							     'code' => $code,
							     'message' => $message,
							     'oldfile' => Null,
							     'newfile' => Null,
							    );
				
		   return $dataretrun; 
		}
          
	 
	}
	############# FTP Congig Start#################
	public function httpcurlpost($url,$params){
		  $postData='';
		   //create name value pairs seperated by &
		   foreach($params as $k => $v){ 
		      $postData .= $k . '='.$v.'&'; 
		   }
		   $postData = rtrim($postData, '&');
		    $ch = curl_init();  
		    curl_setopt($ch,CURLOPT_URL,$url);
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($ch,CURLOPT_HEADER, false); 
		    curl_setopt($ch, CURLOPT_POST, count($postData));
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
		    $output=curl_exec($ch);
		    if($output === false){
		        echo "Error Number:".curl_errno($ch)."<br>";
		        echo "Error String:".curl_error($ch);
		    }
		    curl_close($ch);
	return $output;	 
			/* 
			$params = array(
		   "name" => "Ravishanker Kusuma",
		   "age" => "32",
		   "location" => "India"
			);
			 
			echo httpcurlpost("http://xxx/",$params);
			*/
	}
	public function httpcurlget($url){
	    $ch = curl_init();  
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	 	#curl_setopt($ch,CURLOPT_HEADER, false); 
	    $output=curl_exec($ch);
	    if($output === false){
	        echo "Error Number:".curl_errno($ch)."<br>";
	        echo "Error String:".curl_error($ch);
	    }
	    curl_close($ch);
	return $output; 
	}
	public function httpgetcontents($url){
		$json_string=$url;
		$jsondata = file_get_contents($json_string);
		$output = json_decode($jsondata);
	 return $output; 
	}
	public function usersaccount($user_id){
		$query = $this->db->select('user_id as userid,member_id,user_group,user_username,psn_firstname')
             ->from('users_account')
             ->where('user_id',$user_id)
             //->like('$user_id','member_id','user_username')
             ->get();
	$result = $query->result_array();
	 return $result; 
	}
	// Debug
	public function debug($object,$title='',$default = FALSE){
		if ( ! isset($object) ){
			return $default;
		}
		if($title==Null){
			$title='Debug';
		}
		header('Content-Type: text/html; charset=utf-8');
		if($title) echo "<code>$title</code>";
		echo '<pre> Debug_code=>';print_r($object); echo '</pre>';
		//return $array[$item];
	}
 }