<?php

	//Debug($_REQUEST);
	//Debug($elvis_list);
	//die();
	$i = 0;
	if($elvis_list){
?>
				<!-- PAGE CONTENT BEGINS -->
				<div>
					<ul class="ace-thumbnails clearfix">
<?php		
		
		$item_id = $this->input->get('item_id');
		$folder = $this->input->get('folder');
		$ref_type = $this->input->get('ref_type');

		/*Debug($item_id);
		Debug($folder);
		Debug($ref_type);*/

		//Debug($elvis_list);
		$tags_arr = array();

		if($elvis_list){
			
			$pic_list = $elvis_list->hits;
			//Debug($pic_list);
			$allitem = count($pic_list);
			if($pic_list)
					for($i=0;$i<$allitem;$i++){
								
								$show_tags = '';
								$elvis_id = $pic_list[$i]->id;
								$permissions = $pic_list[$i]->permissions;
								$originalUrl = $pic_list[$i]->originalUrl;
								//$thumbnailUrl = $pic_list[$i]->thumbnailUrl;

								$thumbnail = (file_exists($pic_list[$i]->thumbnailUrl)) ? 'theme/assets/images/gallery/no-img.jpg' : $pic_list[$i]->thumbnailUrl;
								//$cameraManufacturer = $pic_list[$i]->cameraManufacturer;
								//$cameraModel = $pic_list[$i]->metadata->cameraModel;
								//$dimensionMm = $pic_list[$i]->dimensionMm;

								if(@$pic_list[$i]->description) $description = $pic_list[$i]->description;
								//if(@$pic_list[$i]->metadata->tags) $tags = $pic_list[$i]->metadata['tags'];

								unset($tags_arr);
								@$tags_arr = $pic_list[$i]->metadata->tags;
								//Debug($tags_arr);

								if($tags_arr) 
									for($t=0;$t<count($tags_arr);$t++){
										if($t == 0) 
											$show_tags = "<i class='ace-icon glyphicon glyphicon-tags'> ".$tags_arr[$t]."</i>";
										else
											$show_tags .= ", <i class='ace-icon glyphicon glyphicon-tags'> ".$tags_arr[$t]."</i>";
									}

								$assetFileModifier = $pic_list[$i]->metadata->assetFileModifier;
								$assetPath = $pic_list[$i]->metadata->assetPath;
								$mimeType = $pic_list[$i]->metadata->mimeType;
								$width = $pic_list[$i]->metadata->width;
								$height = $pic_list[$i]->metadata->height;
								$dimension = $pic_list[$i]->metadata->dimension;
								$filename = $pic_list[$i]->metadata->filename;
								$name = $pic_list[$i]->metadata->name;
								

								list($fname, $exten) = explode(".", $pic_list[$i]->metadata->name);
								//$originalUrl = ($exten == "jpg") ? $originalUrl : 'javascript:void(0);'; 								
								//$colorbox = ($exten == "jpg") ? 'data-rel="colorbox"' : '';
								$colorbox =  'data-rel="colorbox"' ;

								if($width > $height) $styleimg = 'style="transform: translate(-18%, 0%);"';
								else $styleimg = '';

								if($exten == "tif" || $exten == "psd"){

										$originalUrl = str_replace("thumbnail", "preview", $pic_list[$i]->thumbnailUrl);
										$originalUrl = str_replace("////", "/previews/maxWidth_1600_maxHeight_1600.jpg////", $originalUrl);
								
								}
								$ref_elvis = base64_encode($originalUrl);

?>
								<li>
											<a href="<?php echo $originalUrl?>" <?php echo $colorbox?>  class="cboxElement">
												<img src="<?php echo $thumbnail?>" <?php echo $styleimg ?> />
												<div class="tags">

													<span class="label-holder">
														<span class="label label-warning arrowed-in"><?php echo $show_tags; ?></span>
													</span>

													<span class="label-holder">
														<span class="label label-info arrowed"><?php echo $dimension?></span>
													</span>

													<span class="label-holder">
														<span class="label label-success"><?php echo $name?></span>
													</span>

													<!-- <span class="label-holder">
														<span class="label label-danger"><?php echo $elvis_id?></span>
													</span> -->
												</div>
											</a>

											<?php //if($exten == "jpg" || $exten == "png"){ ?>
											<div class="tools tools-top">
													<a href="<?php if(@$item_id > 0) echo base_url('elvis/download/'.$item_id.'?folder='.$folder.'&ref_type='.$ref_type.'&ref_id='.$ref_elvis); else echo '#'?>">
														<i class="ace-icon fa fa-cloud-download"></i>
													</a>
											</div>
											<?php //} ?>
								</li>
<?php
					
					}
					//$search_form = $this->input->post();
					//echo "keyword = ".$search_form['keyword'];
		}

		$nextitem = $i+1;
?>
						</ul>
				</div>
				<!-- <div id="More<?php echo $lastid?>" ></div> -->
				<!-- .<?php echo $nextitem ?> -->

<?php } ?>