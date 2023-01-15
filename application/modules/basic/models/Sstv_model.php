<?php
class Sstv_model extends CI_Model {

    public function __construct(){
		parent::__construct();
    }

    public function download($sstv_url, $folder = '', $ref_type = 1, $img_size = 500){
    	
    	$this->load->helper('img');
		$type_folder = '';

		/*switch($ref_type){
				case 1: $type_folder = 'news'; break;
				case 2: $type_folder = 'column'; break;
				case 3: $type_folder = 'gallery'; break;
				case 4: $type_folder = 'vdo'; break;
				case 5: $type_folder = 'dara'; break;
				default: $type_folder = 'news'; break;
		}*/

		$ref_type = 4;
		$type_folder = 'vdo'; 
    	
    	if($folder == '') $folder = date('Ymd');
    	
    	$upload_tmppath = './uploads/tmp/'.$folder;
    	$upload_path = './uploads/'.$type_folder.'/'.$folder;
    	
    	$fname =  $type_folder.date("YmdHi") . rand(000, 999);
    	$newtmp_filename = $upload_tmppath.'/'.$fname.'.jpg';
		$new_filename = $upload_path.'/'.$fname.'.jpg';

		//echo "<br>$sstv_url  ==> $newtmp_filename<br>";
		
		$src = fopen($sstv_url, 'r');
		$dest1 = fopen($newtmp_filename, 'w');
		
		stream_copy_to_stream($src, $dest1);
		
		resize_img($newtmp_filename, $newtmp_filename, $img_size);
		//Copy to Tmp
		
		$src2 = fopen($newtmp_filename, 'r');
		$dest2 = fopen($new_filename, 'w');
		stream_copy_to_stream($src2, $dest2);
		resize_img($new_filename, $new_filename, $img_size);
		//Copy to news
		
		//Debug("from $ref_type ==> $upload_path/".$fname.".jpg");

		return $fname.'.jpg';
    
    }
    
}
?>	
