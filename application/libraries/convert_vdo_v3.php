<?php

		 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
		
		class Convert_vdo_v3 { 
		

		public $year_path='2012';
				
		
		
		function xml2array($contents, $get_attributes=1, $priority = 'tag') {
			if(!$contents) return array();
		
			if(!function_exists('xml_parser_create')) {
				//print "'xml_parser_create()' function not found!";
				return array();
			}
		
			//Get the XML parser of PHP - PHP must have this module for the parser to work
			$parser = xml_parser_create('');
			xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, trim($contents), $xml_values);
			xml_parser_free($parser);
		
			if(!$xml_values) return;//Hmm...
		
			//Initializations
			$xml_array = array();
			$parents = array();
			$opened_tags = array();
			$arr = array();
		
			$current = &$xml_array; //Refference
		
			//Go through the tags.
			$repeated_tag_index = array();//Multiple tags with same name will be turned into an array
			foreach($xml_values as $data) {
				unset($attributes,$value);//Remove existing values, or there will be trouble
		
				//This command will extract these variables into the foreach scope
				// tag(string), type(string), level(int), attributes(array).
				extract($data);//We could use the array by itself, but this cooler.
		
				$result = array();
				$attributes_data = array();
		
				if(isset($value)) {
					if($priority == 'tag') $result = $value;
					else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
				}
		
				//Set the attributes too.
				if(isset($attributes) and $get_attributes) {
					foreach($attributes as $attr => $val) {
						if($priority == 'tag') $attributes_data[$attr] = $val;
						else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
					}
				}
		
				//See tag status and do the needed.
				if($type == "open") {//The starting of the tag '<tag>'
					$parent[$level-1] = &$current;
					if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
						$current[$tag] = $result;
						if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
						$repeated_tag_index[$tag.'_'.$level] = 1;
		
						$current = &$current[$tag];
		
					} else { //There was another element with the same tag name
		
						if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
							$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
							$repeated_tag_index[$tag.'_'.$level]++;
						} else {//This section will make the value an array if multiple tags with the same name appear together
							$current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
							$repeated_tag_index[$tag.'_'.$level] = 2;
		
							if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
								$current[$tag]['0_attr'] = $current[$tag.'_attr'];
								unset($current[$tag.'_attr']);
							}
		
						}
						$last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
						$current = &$current[$tag][$last_item_index];
					}
		
				} elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
					//See if the key is already taken.
					if(!isset($current[$tag])) { //New Key
						$current[$tag] = $result;
						$repeated_tag_index[$tag.'_'.$level] = 1;
						if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;
		
					} else { //If taken, put all things inside a list(array)
						if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
		
							// ...push the new element into that array.
							$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
		
							if($priority == 'tag' and $get_attributes and $attributes_data) {
								$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
							}
							$repeated_tag_index[$tag.'_'.$level]++;
		
						} else { //If it is not an array...
							$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
							$repeated_tag_index[$tag.'_'.$level] = 1;
							if($priority == 'tag' and $get_attributes) {
								if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
		
									$current[$tag]['0_attr'] = $current[$tag.'_attr'];
									unset($current[$tag.'_attr']);
								}
		
								if($attributes_data) {
									$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
								}
							}
							$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
						}
					}
		
				} elseif($type == 'close') { //End of tag '</tag>'
					$current = &$parent[$level-1];
				}
			}
		
			return($xml_array);
		}
		
			public function ConvertVideoFileV3( $inputFileName, $fileCat,  $contentId) {
				$CI =& get_instance();
				$CI->load->library('trueplook');
				$url = "http://vdoconvert3.truelife.com/api.php";
				$CI->trueplook->year_path;
				
				
				switch ($fileCat){
					
				case "tv_program":
				 
				$list_id=explode('#',$contentId);
				$contentId=$list_id[0];
				$tv_vdo_id=$list_id[1];
				$input_path=$CI->trueplook->set_media_path_full('vdo');
				$_new_path = "hash_tv_program/episode/".((int)$contentId%4000)."/".(int)$contentId;
				
				$post_data = '<?xml version="1.0" encoding="UTF-8" ?>
					<request app_name="'.$fileCat.'" id="'.$contentId.'#'.$tv_vdo_id.'">
					<input_file>'.$input_path.$_new_path.'/'.$inputFileName.'</input_file>
					<output_dir>'.$input_path.$_new_path.'/</output_dir>
					<notification_url>http://www.trueplookpanya.com/new/notify_convert/</notification_url>
					<delete_input_when_done>true</delete_input_when_done>
					</request>
				';
				break;
				
				case "campaign_work":
					$list_id=explode('#',$contentId);
					$campaign_id=$list_id[0];
					$work_id=$list_id[1];
					$media_id=$list_id[2];
					$input_path=$CI->trueplook->set_media_path_full('vdo');
					
					$_new_path = "hash_campaign/".((int)$campaign_id%4000)."/".(int)$campaign_id."/".$work_id;
					
					$post_data = '<?xml version="1.0" encoding="UTF-8" ?>
						<request app_name="'.$fileCat.'" id="'.$campaign_id.'#'.$work_id.'#'.$media_id.'">
						<input_file>'.$input_path.$_new_path.'/'.$inputFileName.'</input_file>
						<output_dir>'.$input_path.$_new_path.'/</output_dir>
						<notification_url>http://www.trueplookpanya.com/new/notify_convert/</notification_url>
						<delete_input_when_done>true</delete_input_when_done>
						</request>
					';
				break;
				
				case "asktrueplookpanya":
					
					$list_id=explode('#',$contentId);
					$id=$list_id[0];
					$type=$list_id[1];
					$input_path=$CI->trueplook->set_media_path_full('vdo');
					
					$_new_path = "hash_asktrueplookpanya/".$type."/".((int)$id%4000)."/".$id;
					
					$post_data = '<?xml version="1.0" encoding="UTF-8" ?>
						<request app_name="'.$fileCat.'" id="'.$id.'#'.$type.'">
						<input_file>'.$input_path.$_new_path.'/'.$inputFileName.'</input_file>
						<output_dir>'.$input_path.$_new_path.'/</output_dir>
						<notification_url>http://www.trueplookpanya.com/new/notify_convert/</notification_url>
						<delete_input_when_done>true</delete_input_when_done>
						</request>
					';
				break;	
				
				case "project":
					
					$list_id=explode('#',$contentId);
					$id=$list_id[0];
					$project_name=$list_id[1];
					$input_path=$CI->trueplook->set_media_path_full('vdo');
					
					$_new_path="hash_project/".$project_name."/".((int)$id%4000)."/".(int)$id;
										
					$post_data = '<?xml version="1.0" encoding="UTF-8" ?>
						<request app_name="'.$fileCat.'" id="'.$id.'#'.$project_name.'">
						<input_file>'.$input_path.$_new_path.'/'.$inputFileName.'</input_file>
						<output_dir>'.$input_path.$_new_path.'/</output_dir>
						<notification_url>http://www.trueplookpanya.com/new/notify_convert/</notification_url>
						<delete_input_when_done>true</delete_input_when_done>
						</request>
					';
					
				break;
				
				case "KNOWLEDGE5":

					$_new_path=$CI->trueplook->year_path."/hash_knowledge/".((int)$contentId%4000)."/".(int)$contentId;
					$post_data = '<?xml version="1.0" encoding="UTF-8" ?>
						<request app_name="'.$fileCat.'" id="'.$contentId.'">
						<input_file>/data/product/trueplookpanya/www/product/source/'.$_new_path.'/'.$inputFileName.'</input_file>
						<output_dir>/data/product/trueplookpanya/www/product/media/'.$_new_path.'/</output_dir>
						<notification_url>http://www.trueplookpanya.com/new/notify_convert/</notification_url>
						<delete_input_when_done>false</delete_input_when_done>
						</request>
					';

				break;
				
				case "CMS1":

					$_new_path=$CI->trueplook->year_path."/hash_cms/".((int)$contentId%4000)."/".(int)$contentId;
					$post_data = '<?xml version="1.0" encoding="UTF-8" ?>
						<request app_name="'.$fileCat.'" id="'.$contentId.'">
						<input_file>/data/product/trueplookpanya/www/product/source/'.$_new_path.'/'.$inputFileName.'</input_file>
						<output_dir>/data/product/trueplookpanya/www/product/media/'.$_new_path.'/</output_dir>
						<notification_url>http://www.trueplookpanya.com/new/notify_convert/</notification_url>
						<delete_input_when_done>false</delete_input_when_done>
						</request>
					';

				break;
				
				
				}
				
		
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array (
					"Content-Type: text/xml"
				));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		
				$response = curl_exec($ch);
		
				if (curl_errno($ch)) {
				return false;
				} else {
				curl_close($ch);
				}
				
				
				$input_img_path=$CI->trueplook->set_media_path_full('image');
				if($fileCat=="tv_program"){					
					$_new_path = "hash_tv_program/episode/".((int)$contentId%4000)."/".(int)$contentId;	
				}else if($fileCat=="campaign_work"){
					$_new_path = "hash_campaign/".((int)$campaign_id%4000)."/".(int)$campaign_id."/".$work_id;	
				}else if($fileCat=="asktrueplookpanya"){
					$_new_path = "hash_asktrueplookpanya/".$type."/".((int)$id%4000)."/".$id;
				}else if ($fileCat=='project'){
					$_new_path=	"hash_project/".$project_name."/".((int)$id%4000)."/".(int)$id;
				}else if  ($fileCat=='KNOWLEDGE5'){
					$_new_path="hash_knowledge/".((int)$contentId%4000)."/".(int)$contentId;
				}else if  ($fileCat=='CMS1'){
					$_new_path="hash_cms/".((int)$contentId%4000)."/".(int)$contentId;
				}// IF fileCat
				
						sleep(5);
						$inputFileName_nosurname=substr($inputFileName,0,-4);
						$this->convertImg($input_img_path.$_new_path."/".$inputFileName_nosurname.".png", $input_img_path.$_new_path."/");
						sleep(5);
						$dp = opendir($input_img_path.$_new_path."/");
						$i=0;
			
						while(false != ($file = readdir($dp)))
						{
								if ($file <> '.' and $file <> '..' and $file<>'.DS_Store'){
								
								if(is_dir("$dirname\$file"))  
										 echo "<b>[Dir]</b>";
									
									//$all_file[]=$file;
									if( !preg_match('/_320x240\.png/', $file) and !preg_match('/_128x96\.png/', $file) and !preg_match('/_70x70\.png/', $file) and !preg_match('/'.$inputFileName_nosurname.'\.mp4/', $file) ) {
									unlink($dirname.$file);
									}
									
										 
								}
								
						}//while
				
			
		
		//		header("content-type: text/xml");
				return $response;
		
			} // END ConvertVideoFileV3
				
				
				public function convertImg($master_file,$target_path){
			
			
						list($file_name,$file_type)=explode(".",basename($master_file));
						
						$file_name_320=$target_path.$file_name."_320x240";
						$file_name_128=$target_path.$file_name."_128x96";
						$file_name_72=$target_path.$file_name."_70x70";
						$quality=9;
						
						list($master_w,$master_h)=getimagesize($master_file);
						if ($file_type=='jpg'){
								$img_master_file=imagecreatefromjpeg($master_file);
						}else if ($file_type=='png'){
								$img_master_file=imagecreatefrompng($master_file);
						}
					
						
						$calculate_file=$master_w/$master_h;
						$file_type_png="png";
						
						if($calculate_file >= 1.1 and $calculate_file <=1.4){
							
							
							// Convert 320*240	
							$w320=320;
							$h240=240;	
							$img_320_240=imagecreatetruecolor($w320,$h240);
							imagecopyresampled($img_320_240, $img_master_file, 0, 0, 0, 0, $w320,$h240, $master_w, $master_h);
							
									imagepng($img_320_240,$file_name_320.".".$file_type_png,$quality);
							
							
							// Convert 128*96
							
							$w128=128;
							$h96=96;	
							$img_128_96=imagecreatetruecolor($w128,$h96);
							imagecopyresampled($img_128_96, $img_master_file, 0, 0, 0, 0, $w128,$h96, $master_w, $master_h);
							
									imagepng($img_128_96,$file_name_128.".".$file_type_png,$quality);
							
							
							// Convert 72*72
							$w72=70;
							$h72=70;	
							
							$percent=$w72/$master_w; 
							$h72_real=($master_h * $percent);
							
							$margin=($h72-$h72_real)/2;
							
							$img_72_72=imagecreatetruecolor($w72,$h72);
							$img_resize_72_72=imagecreatetruecolor($w72,$h72);
							$black=imagecolorallocate($img_72_72,0,0,0);
							imagecopyresampled($img_resize_72_72, $img_master_file, 0, 0, 0, 0, $w72,$h72_real, $master_w, $master_h);
							imagecopymerge($img_72_72,$img_resize_72_72,0,$margin,0,0,$w72,$h72,100);
							
									imagepng($img_72_72,$file_name_72.".".$file_type_png,$quality);
							
					
													
						}else if ($calculate_file < 1.1){
							
					
							// Convert 320*240	
							$w320=320;
							$h240=240;
					
							$percent=$h240/$master_h; 
							$w320_real=($master_w * $percent);
							$margin=($w320-$w320_real)/2;
							
							$img_320_240=imagecreatetruecolor($w320,$h240);
							$img_resize_320_240=imagecreatetruecolor($w320,$h240);
							$black=imagecolorallocate($img_320_240,0,0,0);
							imagecopyresampled($img_resize_320_240, $img_master_file, 0, 0, 0, 0, $w320_real,$h240, $master_w, $master_h);
							imagecopymerge($img_320_240,$img_resize_320_240,$margin,0,0,0,$w320,$h240,100);
							
									imagepng($img_320_240,$file_name_320.".".$file_type_png,$quality);
							
							
							// Convert 128*96
							
							$w128=128;
							$h96=96;	
							
							$percent=$h96/$master_h; 
							$w128_real=($master_w * $percent);
							$margin=($w128-$w128_real)/2;
							
							
							$img_128_96=imagecreatetruecolor($w128,$h96);
							$img_resize_128_96=imagecreatetruecolor($w128,$h96);
							$black=imagecolorallocate($img_128_96,0,0,0);
							imagecopyresampled($img_resize_128_96, $img_master_file, 0, 0, 0, 0, $w128_real,$h96, $master_w, $master_h);
							imagecopymerge($img_128_96,$img_resize_128_96,$margin,0,0,0,$w128,$h96,100);
							
									imagepng($img_128_96,$file_name_128.".".$file_type_png,$quality);
							
							
							// Convert 72*72
							$w72=70;
							$h72=70;	
							
							$img_72_72=imagecreatetruecolor($w72,$h72);
							imagecopyresampled($img_72_72, $img_master_file, 0, 0, 0, 0, $w72,$h72, $master_w, $master_h);
							
									imagepng($img_72_72,$file_name_72.".".$file_type_png,$quality);
						
									
						}else if ($calculate_file > 1.4 and $calculate_file <= 1.6 ){
						
							
							// Convert 320*240	
							$w320=320;
							$h240=240;
					
							$percent=$w320/$master_w; 
							$h240_real=($master_h * $percent);
							$margin=($h240-$h240_real)/2;
							
							$img_320_240=imagecreatetruecolor($w320,$h240);
							$img_resize_320_240=imagecreatetruecolor($w320,$h240);
							$black=imagecolorallocate($img_320_240,0,0,0);
							imagecopyresampled($img_resize_320_240, $img_master_file, 0, 0, 0, 0, $w320,$h240_real, $master_w, $master_h);
							imagecopymerge($img_320_240,$img_resize_320_240,0,$margin,0,0,$w320,$h240,100);
					
									imagepng($img_320_240,$file_name_320.".".$file_type_png,$quality);
							
							
							// Convert 128*96
							
							$w128=128;
							$h96=96;	
							
							$percent=$w128/$master_w; 
							$h96_real=($master_h * $percent);
							$margin=($h96-$h96_real)/2;
							
							
							$img_128_96=imagecreatetruecolor($w128,$h96);
							$img_resize_128_96=imagecreatetruecolor($w128,$h96);
							$black=imagecolorallocate($img_128_96,0,0,0);
							imagecopyresampled($img_resize_128_96, $img_master_file, 0, 0, 0, 0, $w128,$h96_real, $master_w, $master_h);
							imagecopymerge($img_128_96,$img_resize_128_96,0,$margin,0,0,$w128,$h96,100);
					
									imagepng($img_128_96,$file_name_128.".".$file_type_png,$quality);
					
							
							// Convert 72*72
							$w72=70;
							$h72=70;	
							
							$percent=$w72/$master_w; 
							$h72_real=($master_h * $percent)+10;
							
							$margin=($h72-$h72_real)/2;
							
							$img_72_72=imagecreatetruecolor($w72,$h72);
							$img_resize_72_72=imagecreatetruecolor($w72,$h72);
							$black=imagecolorallocate($img_72_72,0,0,0);
							imagecopyresampled($img_resize_72_72, $img_master_file, 0, 0, 0, 0, $w72,$h72_real, $master_w, $master_h);
							imagecopymerge($img_72_72,$img_resize_72_72,0,$margin,0,0,$w72,$h72,100);
					
									imagepng($img_72_72,$file_name_72.".".$file_type_png,$quality);
					
											
						}else if( $calculate_file > 1.6){
							
							// Convert 320*240	
							$w320=320;
							$h240=240;
					
							$percent=$h240/$master_h; 
							$w320_real=($master_w * $percent);
							$margin=($w320_real-$w320)/2;
							
							$img_320_240=imagecreatetruecolor($w320,$h240);
							$img_resize_320_240=imagecreatetruecolor($w320_real,$h240);
							$black=imagecolorallocate($img_320_240,0,0,0);
							imagecopyresampled($img_resize_320_240, $img_master_file, 0, 0, 0, 0, $w320_real,$h240, $master_w, $master_h);
							imagecopymerge($img_320_240,$img_resize_320_240,0,0,$margin,0,$w320,$h240,100);
							
									imagepng($img_320_240,$file_name_320.".".$file_type_png,$quality);
							
							
							// Convert 128*96
							
							$w128=128;
							$h96=96;	
							
							$percent=$h96/$master_h; 
							$w128_real=($master_w * $percent);
							$margin=($w128_real-$w128)/2;
							
							
							$img_128_96=imagecreatetruecolor($w128,$h96);
							$img_resize_128_96=imagecreatetruecolor($w128_real,$h96);
							$black=imagecolorallocate($img_128_96,0,0,0);
							imagecopyresampled($img_resize_128_96, $img_master_file, 0, 0, 0, 0, $w128_real,$h96, $master_w, $master_h);
							imagecopymerge($img_128_96,$img_resize_128_96,0,0,$margin,0,$w128,$h96,100);
							
									imagepng($img_128_96,$file_name_128.".".$file_type_png,$quality);
							
							
							// Convert 72*72
							$w72=70;
							$h72=70;	
							
							$percent=$h72/$master_h; 
							$w72_real=($master_w * $percent);
							$margin=($w72_real-$w72)/2;
							
							$img_72_72=imagecreatetruecolor($w72,$h72);
							$img_resize_72_72=imagecreatetruecolor($w72_real,$h72);
							$black=imagecolorallocate($img_72_72,0,0,0);
							imagecopyresampled($img_resize_72_72, $img_master_file, 0, 0, 0, 0, $w72_real,$h72, $master_w, $master_h);
							imagecopymerge($img_72_72,$img_resize_72_72,0,0,$margin,0,$w72,$h72,100);
							
									imagepng($img_72_72,$file_name_72.".".$file_type_png,$quality);
							
							
						}
			
			
			
			} // END convertImg;
			
		}


		?>