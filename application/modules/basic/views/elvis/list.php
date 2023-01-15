<?php 
		$language = $this->lang->language; 
		$i=0;
		//$maxcat = count($dara_type);
		//Debug($this->input);
		//Debug($item_list);
		//Debug($elvis_list);

		//$loginelvis = "login=window.open('http://elvis.siamsport.co.th/services/login?username=chaipat&password=inspire');login.close();parent.reload();";
		$loginelvis = base_url('elvis/login');
		//$loginelvis = "login=window.open('".$loginelvis."');login.close();parent.reload();";
		$loginelvis = "window.open('".$loginelvis."')";

		$elvis = "window.open('http://elvis.siamsport.co.th/web')";

		if($this->input->post('keyword') == "") $keyword = "girl"; else $keyword = trim($this->input->post('keyword'));
		//Debug($this->session->all_userdata());
?>
<div class="col-xs-12">
				<h3 class="header smaller lighter blue">Elvis to <?php echo $item_list[0]['title']; ?></h3>
<?php
	//$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	//echo form_open('elvis', $attributes);

	$backto =  site_url($this->uri->segment(2).'/gallery/'.$this->uri->segment(3));

?><!-- onclick="window.location='<?php echo site_url('dara/add') ?>';" -->


				<button class="btn btn-danger btn-sm btn-primary" onclick="window.location='<?php echo $backto ?>';">
				<i class="bace-icon fa fa-reply icon-only igger-125"></i><?php echo $language['back']  ?></button>

				<!-- <button class="btn btn-sm btn-primary"  onclick="<?php echo $loginelvis?>">
					<i class="ace-icon fa fa-key align-top bigger-125"></i><?php echo $language['login'].' Elvis'  ?>
				</button>  -->

				<button class="btn btn-sm btn-primary"  onclick="<?php echo $elvis?>">
					<i class="ace-icon fa fa-key align-top bigger-125"></i><?php echo 'Elvis siamsport web'  ?>
				</button> 
<?php
	//echo form_close();
	//echo $item_id;
	
	list($folder, $time) = explode(" ", $item_list[0]['create_date']);
	$folder = str_replace("-", "", $folder);
	//echo $folder;
	
	if(!$elvis_list){

 ?>
					<div class="alert alert-danger">
					<button data-dismiss="alert" class="close" type="button">
					<i class="ace-icon fa fa-times"></i>
					</button>
					
					<strong>
					<i class="ace-icon fa fa-times"></i>
					<?php echo $language['please_new_keyword']?>!
					</strong>
					<?php echo $language['file_not_found']?>
					<br>
					</div>
<?php

	}
?>
				<!-- PAGE CONTENT BEGINS -->
				<div>
					<ul class="ace-thumbnails clearfix">
<?php
	
	//Debug($item_list);
	$tags_arr = array();
	if($elvis_list){
			
		//Debug($elvis_list->hits);
		if(@$elvis_list->errorcode == 401){
			
		}else{

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


								//http://elvis.siamsport.co.th/thumbnail/Ftvfk3LxqQpBfY-1AoOYYc////s05-014_1cmyk_thumb.jpg?_=1
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

										//http://elvis.siamsport.co.th/thumbnail/Ftvfk3LxqQpBfY-1AoOYYc////s05-014_1cmyk_thumb.jpg?_=1
										//http://elvis.siamsport.co.th/preview/Ftvfk3LxqQpBfY-1AoOYYc/previews/maxWidth_1600_maxHeight_1600.jpg////s05-014_1cmyk_preview.jpg?_=1
										$originalUrl = str_replace("thumbnail", "preview", $pic_list[$i]->thumbnailUrl);
										$originalUrl = str_replace("////", "/previews/maxWidth_1600_maxHeight_1600.jpg////", $originalUrl);
								
								}
								
								$ref_elvis = base64_encode($originalUrl);
								//transform: translate(-25%, 0%);
								//$getsize = getimagesize($thumbnail);
								//Debug($getsize);
?>
								<li>
											<a href="<?php echo $originalUrl?>" <?php echo $colorbox?> class="cboxElement">
												<img src="<?php echo $thumbnail?>" <?php echo $styleimg ?>  />
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
												<!-- <a href="#">
													<i class="ace-icon fa fa-link"></i>
												</a>
												<a href="#">
													<i class="ace-icon fa fa-times red"></i>
												</a> -->
											</div>
											<?php //} ?>
								</li>
<?php
					
					}
					//$search_form = $this->input->post();
					//echo "keyword = ".$search_form['keyword'];
		}

		$nextitem = $i+1;

	}
?>
						</ul>

				<div id="More"></div>
				<div id="iconload" ></div>
<?php if($nextitem >= 50){ ?>
				<p><button class="btn btn-success btn-block loadmore" id="btn-loadmore" value="<?php echo $nextitem?>">Load more</button></p>
<?php } ?>
								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->

<?php

					/*if($pic_list)
					foreach($pic_list as $key => $v){
								
								echo "$key => <br>";
								Debug($v);

								echo "<hr>";
					}*/
?>
<script type="text/javascript">
jQuery(function($) {
	var $overflow = '';
	var colorbox_params = {
		rel: 'colorbox',
		reposition:true,
		scalePhotos:true,
		scrolling:false,
		previous:'<i class="ace-icon fa fa-arrow-left"></i>',
		next:'<i class="ace-icon fa fa-arrow-right"></i>',
		close:'&times;',
		current:'{current} of {total}',
		maxWidth:'100%',
		maxHeight:'100%',
		onOpen:function(){
			$overflow = document.body.style.overflow;
			document.body.style.overflow = 'hidden';
		},
		onClosed:function(){
			document.body.style.overflow = $overflow;
		},
		onComplete:function(){
			$.colorbox.resize();
		}
	};

	$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
	$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange'></i>");//let's add a custom loading icon

	$('.loadmore').click(function(){
		var lastid = $(this).val();
		var nextid = parseInt(lastid) + 50;

		$("#iconload").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
<?php
		/*if(nextid == 101) $("#More").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
		else if(nextid == 151) $("#More101").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
		else if(nextid == 201) $("#More151").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
		else if(nextid == 251) $("#More201").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
		else if(nextid == 301) $("#More251").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
		else if(nextid == 351) $("#More301").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');*/
?>

		$.ajax({
		   type: "GET",
		   url: "<?php echo site_url('elvis/loadmore') ?>/" + lastid + "/<?php echo urlencode($keyword)?>/?item_id=<?php echo $item_id?>&folder=<?php echo $folder?>&ref_type=<?php echo $ref_type?>" ,
		   //data: "lastid : lastid, keyword : '<?php echo $keyword?>'",
		   success: function(msg){

			   //alert(msg);

			   if(nextid == 101)
					$("#More").html(msg);
			   else
					$("#More").append(msg);

			   $("#iconload").html('');
<?php

			   /*else if(nextid == 151)
					$("#More101").html(msg);
			   else if(nextid == 201)
					$("#More151").html(msg);
			   else if(nextid == 251)
					$("#More201").html(msg);
			   else if(nextid == 301)
					$("#More251").html(msg);
			   else if(nextid == 351)
					$("#More301").html(msg);*/

				//$("#nextload").html(nextid + ' == ');
?>
				$("#btn-loadmore").val(nextid);

	   		}
			//$("#iconload").html('');
	 	});
	});

})
</script>
