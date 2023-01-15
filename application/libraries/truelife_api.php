<?php

		 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
		
		class Truelife_api { 
		
			
			public function get_content_list($cat='movie',$offset=0,$limit=4,$order_by='published_date',$cat_id=null){
				$CI =& get_instance();
				$CI->load->library('memcache');
				$CI->load->library('convert_vdo_v3');
				//$CI->load->model('connect_db_model','cdm');
				$cache_time=3600*24;
				
				switch ($cat){
					
					case 'movie' : $truelife_XML='http://api.platform.truelife.com/cms/content/movies_content?condition=categories_in_all=1566&limit='.$limit.'&offset='.$offset.'&order_by='.$order_by;break;
					case 'travel' : $truelife_XML='http://api.platform.truelife.com/cms/content/travel?limit='.$limit.'&offset='.$offset.'&order_by='.$order_by.'&condition=categories_in_all='.$cat_id;break;
					case 'food' : $truelife_XML='http://api.platform.truelife.com/cms/content/food_health?limit='.$limit.'&offset='.$offset.'&order_by='.$order_by;break;
				
				}
				//$CI->memcache->delete('get_truelife_api_'.$cat.$cat_id.'_'.$limit.'_'.$offset.'_'.$order_by);
				
				if ($CI->memcache->get('get_truelife_api_'.$cat.$cat_id.'_'.$limit.'_'.$offset.'_'.$order_by)==''){
					$file = file_get_contents($truelife_XML);		
					$v =$CI->convert_vdo_v3->xml2array($file);
					
					$level1=$v['data']['contents'];		
					$no=0;		
					foreach ($level1['entry'] as $k=>$v){
						
						foreach ($v as $key=>$val){
							
							$data[$no][$key]=$val;
							
						}
						$no++;
							
					}
					
					$CI->memcache->set('get_truelife_api_'.$cat.$cat_id.'_'.$limit.'_'.$offset.'_'.$order_by,$data,$cache_time);
					
				}// Memcache
				
				$return_data=$CI->memcache->get('get_truelife_api_'.$cat.$cat_id.'_'.$limit.'_'.$offset.'_'.$order_by);
				return $return_data;
				
			}
			
			public function get_all_row($cat='movie',$offset=0,$limit=4,$order_by='published_date',$cat_id=null){
				$CI =& get_instance();
				$CI->load->library('memcache');
				$CI->load->library('convert_vdo_v3');
				//$CI->load->model('connect_db_model','cdm');
				
				switch ($cat){
					
					case 'movie' : $truelife_XML='http://api.platform.truelife.com/cms/content/movies_content?condition=categories_in_all=1566&limit='.$limit.'&offset='.$offset.'&order_by='.$order_by;break;
					case 'travel' : $truelife_XML='http://api.platform.truelife.com/cms/content/travel?limit='.$limit.'&offset='.$offset.'&order_by='.$order_by.'&condition=categories_in_all='.$cat_id;break;
					case 'food' : $truelife_XML='http://api.platform.truelife.com/cms/content/food_health?limit='.$limit.'&offset='.$offset.'&order_by='.$order_by;break;
				
				}
				//$CI->memcache->delete('get_truelife_api_allrow_'.$cat.$cat_id.'_'.$limit.'_'.$offset.'_'.$order_by);
				
				if ($CI->memcache->get('get_truelife_api_allrow_'.$cat.$cat_id.'_'.$limit.'_'.$offset.'_'.$order_by)==''){
					$file = file_get_contents($truelife_XML);		
					$v =$CI->convert_vdo_v3->xml2array($file);
					
					$total=$v['data']['total'];		
					
					
					$CI->memcache->set('get_truelife_api_allrow_'.$cat.$cat_id.'_'.$limit.'_'.$offset.'_'.$order_by,$total,$cache_time);
					
				}// Memcache
				
				$return_data=$CI->memcache->get('get_truelife_api_allrow_'.$cat.$cat_id.'_'.$limit.'_'.$offset.'_'.$order_by);
				return $return_data;
				
			}
			
			public function get_content_detail($id){
				$CI =& get_instance();
				$CI->load->library('memcache');
				$CI->load->library('convert_vdo_v3');
				$truelife_XML='http://api.platform.truelife.com/cms/content/'.$id;
				
				//$CI->memcache->delete('get_truelife_api_'.$id);
				
				if ($CI->memcache->get('get_truelife_api_'.$id)==''){
					$file = file_get_contents($truelife_XML);		
					$v =$CI->convert_vdo_v3->xml2array($file);
					
					$level1=$v['data']['contents'];
					$data['title']=$v['data']['title'];
					$data['thumbnail']=$v['data']['thumbnail_128x183'];
					$data['poster']=$v['data']['thumbnail'];
					$data['description']=$v['data']['description'];
					$data['rs_vdo']=$v['data']['trailer']['item']['files']['item'];
					$data['api_view']=$v['data']['views'];
					$data['api_type']=$v['data']['lifestyle'];
					$data['add_date']=$v['data']['created_date'];
				//	foreach ($rs_vdo as $kk=>$vv){
						
						//if ($vv['item']['quality']=='240p'){
							
							//$data['file_vdo']=$vv['item']['file'];
							//$data['duration']=$vv['item']['duration'];
							
					//	}
						
							
				//	}
									
					$CI->memcache->set('get_truelife_api_'.$id,$data,$cache_time);
					
				}// Memcache
				
				$return_data=$CI->memcache->get('get_truelife_api_'.$id);
				return $return_data;
				
			}
				
			
			
		}
		


		?>