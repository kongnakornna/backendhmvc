<?php 
		$language = $this->lang->language; 
		$i=0;
		//$maxcat = count($dara_type);
		$allarticle = count($item_list);

		//Debug($article_list);
		//Debug($item_list);

		$article_id = $article_list[0]['article_id2'];
		$category_id = $article_list[0]['category_id'];
		$subcategory_id = $article_list[0]['subcategory_id'];

		$title_article = ($article_list[0]['title'] == '') ? $article_list[1]['title'] : $article_list[0]['title'];

		$previewurl = $this->config->config['www']."/article/".$category_id."/".$subcategory_id."/".$article_id."?/preview=1";
?>
<style type="text/css">
ul.ace-thumbnails li img{width: 250px; height: 170px;}
</style>
<!-- PAGE CONTENT BEGINS -->

<div class="col-xs-12" style="display:none;">
		<!-- <button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('article/addpic') ?>';">
			<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['picture']  ?>
		</button> -->
<?php

	//Debug($item_list);
	//Debug($article_list);

	if(count($item_list) != 0){
			if($item_list[0]->create_date != ''){
				list($create_date, $time) = explode(" ", $item_list[0]->create_date);
				$folder = str_replace("-", "", $create_date);
			}else
				$folder = '';

			//$title_article = $item_list[0]['title'];
	}else{
			$item_list = null;
			//$title_article = '';
			$folder = '';
	}

	if($title_article == ""){
		if(count($item_list) == 0){
				if(isset($article_list)){
						//if(count($article_list) > 1)
							//$title_article = ($article_list[0]['title'] == "") ? $article_list[1]['title'] : $article_list[0]['title'];

						list($date, $time) = explode(" ", $article_list[0]->create_date);
						list($yy, $mm, $dd) = explode("-", $date);
						$folder = $yy.$mm.$dd;
				}
		}else{

						/*list($date, $time) = explode(" ", $article_list[0]->create_date);
						list($yy, $mm, $dd) = explode("-", $date);
						$folder = $yy.$mm.$dd;*/
		
		}

	}

	$originalpic = '';
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open_multipart('article/upload_pic', $attributes);
?>
								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
										<div class="col-sm-9">
											<div class="col-xs-12">

													<div id="upload_avatar"><input type="file" id="picture_article" name="picture_article[]" multiple /></div>
													<?php echo $language['upload_max_filesize'].' = '.ini_get('upload_max_filesize');?>

											</div>
										</div>
								</div>
								<input type="hidden" name="caption" value="<?php echo $title_article?>">
								<input type="hidden" name="article_id" value="<?php echo $this->uri->segment(3)?>">
								<input type="hidden" name="create_date" value="<?php echo $folder?>">
								<?php if($allarticle == 0){ ?><input type="hidden" name="set_default" value="1"><?php } ?>

					<div style="clear: both;"></div>
					<div class="clearfix form-actions">
							<div class="col-md-offset-1 col-md-9">

										<button type="button" class="btn btn-purple bootbox-options preview">
											<i class="ace-icon fa fa-search-plus bigger-110"></i> <?php echo $language['preview'] ?>
										</button>

										<a href="<?php echo base_url('article/edit/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-primary"><i class="ace-icon fa fa-pencil align-top bigger-125"></i><?php echo $language['edit'] ?> <?php echo $language['article'] ?></button></a>

										<button type="submit" class="btn btn-info">
												<i class="ace-icon glyphicon glyphicon-upload bigger-110"></i><?php echo $language['upload'] ?>
										</button>

										<a href="<?php echo base_url('elvis/article/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-danger">
											<i class="ace-icon fa fa-cloud-download bigger-110"></i>Download From Elvis</button>
										</a>

										<a href="<?php echo base_url('article/picture_order/'.$this->uri->segment(3))?>">
												<button type="button" class="btn btn-success">
													<i class="ace-icon glyphicon glyphicon-align-right"></i><?php echo $language['order'] ?>
												</button>
										</a>

							</div>
					</div>
</div>
			
<div class="col-xs-12">

	<span class=""><?php Debug($language['picture'].$language['article']. ' ' .$title_article); //if($item_list) echo $caption = $item_list[0]['caption'];?></span>

	<div style="clear: both;"></div>
	<div class="clearfix form-actions">
		<div class="col-md-offset-1 col-md-9">
				<button type="button" class="btn btn-info bootbox-options additem" data-id="<?php echo $this->uri->segment(3) ?>">
					<i class="ace-icon glyphicon glyphicon-plus bigger-110"></i> <?php echo $language['add'] ?>
				</button>

				<button type="button" class="btn btn-purple bootbox-options preview">
					<i class="ace-icon fa fa-search-plus bigger-110"></i> <?php echo $language['preview'] ?>
				</button>

				<a href="<?php echo base_url('article/edit/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-primary"><i class="ace-icon fa fa-pencil align-top bigger-125"></i><?php echo $language['edit'] ?> <?php echo $language['article'] ?></button></a>

		</div>
	</div>
													
			<ul class="ace-thumbnails clearfix">
<?php
			//Debug('ภาพข่าว '.$title_article);
			//echo $create_date;
			//Debug($item_list);			
			
			if($item_list)
					for($i=0;$i<$allarticle;$i++){
								
								$item_id = $item_list[$i]->galleryset_id;
								$default = $item_list[$i]->default;
								//$file_name = $item_list[$i]->file_name;
								$caption = $item_list[$i]->caption;
								$folder = $item_list[$i]->folder;
								$ref_type = $item_list[$i]->ref_type;

								$status = ($item_list[$i]->status == 1) ? '<span class="label label-success arrowed">Enable</span>' : '<span class="label label-danger">Disable</span>';

								$preview_file = 'uploads/article/'.$folder.'/'.$item_list[$i]->file_name;
								
								//$file_name = ($item_list[$i]['file_name'] == '') ? 'uploads/article/'.$folder.'/'.$item_list[$i]['file_name'] : 'uploads/thumb/'.$folder.'/'.$item_list[$i]['file_name'];
								if($item_list[$i]->file_name != ''){

										$file_name = 'uploads/thumb/'.$folder.'/'.$item_list[$i]->file_name;
										if(!file_exists($file_name)){
												$file_name = 'uploads/article/'.$folder.'/'.$item_list[$i]->file_name;
												if(!file_exists($file_name)) $file_name = 'theme/assets/images/gallery/no-img.jpg';
										}

								}else $file_name = 'theme/assets/images/gallery/no-img.jpg';

								$pic_attr = getimagesize($file_name);
								$pic_width = ($pic_attr[0] > 568) ? 568 : $pic_attr[0];

								$pic_del = base_url('article/picture_del/'.$item_id)."?".$this->uri->segment(3);
								
								//$attr_width = 'width="150" height="150" alt="150x150"';
								//$edit_picture = base_url('article/picture_edit/'.$item_id.'?'.$this->uri->segment(3));

								if($ref_type == 2){

									$edit_thumb = base_url('article/add_item/'.$this->uri->segment(3).'?item=vdo&item_id='.$item_id);

									$url_youtube = $item_list[$i]->url;
									$thumb_youtube = Img_Youtube($url_youtube);
									$alink = $thumb_youtube;
									//add_item/1?item=vdo
								}else{
									$edit_thumb = base_url('article/picture_edit/'.$item_id.'?article_id='.$this->uri->segment(3));
									$alink = base_url().$preview_file;
								}
								//$alink = ($ref_type == 1) ? base_url().$preview_file : '#';

?>
										<li>
											<a href="<?php echo $alink ?>" data-rel="colorbox">
												<?php if($ref_type == 1){ ?>
												<img src="<?php echo base_url().$file_name?>" />
												<?php }else{ ?>
												<img src="<?php echo $thumb_youtube ?>" />
												<i class="ace-icon glyphicon glyphicon-facetime-video bigger-230 orange"></i>
												<?php } ?>
												<div class="tags">
													<span class="label-holder">
														<?php echo $status?>
													</span>

													<span class="label-holder">
														<span class="label label-info arrowed"><?php echo $folder?></span>
													</span>

													<span class="label-holder">
														<span class="label label-danger"><?php echo $caption?></span>
													</span>

												<?php if($ref_type == 2){ ?>
													<span class="label-holder">
														<span class="label "><?php echo $url_youtube ?>"</span>
													</span>
												<?php } ?>

													<?php if($default == 1){ ?>
													<span class="label-holder">
														<span class="label label-warning arrowed-in"><?php echo $language['default']?></span>
													</span>
													<?php } ?>

												</div>
											</a>

											<div class="tools tools-top in">
												<!-- <a href="#">
													<i class="ace-icon fa fa-link"></i>
												</a> -->
												<!-- <a href="#">
													<i class="ace-icon fa fa-paperclip"></i>
												</a> -->
											<?php if($ref_type == 1){ ?>
												<a id="bootbox-confirmset<?=$item_id?>" href="javascript:void(0);" title="<?php echo $language['set_default']?>" data-file="<?php echo $file_name?>" data-width="<?php echo $pic_width?>" data-rel="tooltip">
													<i class="ace-icon glyphicon glyphicon-bookmark orange"></i>
												</a>
											<?php } ?>

												<a href="<?php echo $edit_thumb?>" title="<?php echo $language['edit'] ?>" data-rel="tooltip">
													<i class="ace-icon fa fa-pencil"></i>
												</a>

												<a id="bootbox-confirm<?=$item_id?>" href="javascript:void(0);<?php //$pic_del?>" data-file="<?php echo $file_name?>" data-width="<?php echo $pic_width?>" title="<?php echo $language['delete'].' '.$language['picture']?>" data-rel="tooltip">
													<i class="ace-icon fa fa-times red" ></i>
												</a>
											</div>
										</li>
<?php
					
					}
?>

			</ul>
	</div><!-- PAGE CONTENT ENDS -->
<?php echo form_close();?>
</div><!-- /.col -->

<?php
echo js_asset('jquery-ui.min.js'); 
echo js_asset('jquery.ui.touch-punch.min.js'); 
?>

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );
		
		/*$('.preview').on('click', function() {			
			window.open('<?php echo $previewurl ?>');
		});	*/

		$(".preview").on(ace.click_event, function() {
					var url = '<?php echo $previewurl ?>';
					var title = '<?php echo $title_article ?>';
					bootbox.dialog({
						message: "<span class='bigger-110'><?php echo $language['preview'] ?> " + title + "</span>",
						buttons: 			
						{
							"success" :
							 {
								"label" : "<i class='ace-icon fa fa-desktop'></i> Desktop ",
								"className" : "btn-sm btn-success",
								"callback": function() {
									window.open(url + '&device=desktop');
								}
							},

							"click" :
							{
								"label" : "<i class='ace-icon fa fa-laptop'></i> Mobile ",
								"className" : "btn-sm btn-primary",
								"callback": function() {
									window.open(url + '&device=mobile');
								}
							}
						}
					});
		});

		$(".additem").on(ace.click_event, function() {
					var url = '<?php echo base_url('article/add_item/'.$this->uri->segment(3)) ?>';
					var title = '<?php echo $title_article ?>';
					bootbox.dialog({
						message: "<span class='bigger-110'><?php echo $language['add'].$language['gallery'] ?> " + title + "</span>",
						buttons:{
							"success" :
							 {
								"label" : "<i class='ace-icon glyphicon glyphicon-picture'></i> <?php echo $language['picture'] ?> ",
								"className" : "btn-sm btn-warning",
								"callback": function() {
									//alert('<?php echo $language['picture'] ?>');
									window.location = url + '?item=picture';
								}
							},
							"click" :
							{
								"label" : "<i class='ace-icon glyphicon glyphicon-facetime-video'></i> <?php echo $language['vdo'] ?> ",
								"className" : "btn-sm btn-danger",
								"callback": function() {
									//alert('<?php echo $language['vdo'] ?>');
									window.location = url + '?item=vdo';
								}
							}
						}
					});
		});

		$('#picture_article').ace_file_input({
					style:'well',
					btn_choose:'<?php echo $language['upload_file']?>',
					btn_change:null,
					no_icon:'ace-icon fa fa-cloud-upload',
					droppable:true,
					thumbnail:'small'//large | fit
					//,icon_remove:null//set null, to hide remove/reset button
					/**,before_change:function(files, dropped) {
						//Check an example below
						//or examples/file-upload.html
						return true;
					}*/
					/**,before_remove : function() {
						return true;
					}*/
					,
					preview_error : function(filename, error_code) {
						//name of the file that failed
						//error_code values
						//1 = 'FILE_LOAD_FAILED',
						//2 = 'IMAGE_LOAD_FAILED',
						//3 = 'THUMBNAIL_FAILED'
						//alert(error_code);
					}
			
		}).on('change', function(){
					//console.log($(this).data('ace_input_files'));
					//console.log($(this).data('ace_input_method'));
		});

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
<?php
			//Debug($item_list);			
			$allarticle = count($item_list);
			if($item_list)
				for($i=0;$i<$allarticle;$i++){
						$item_id = $item_list[$i]->galleryset_id;

						$pic_setdefault = base_url('article/set_default/?id='.$item_id.'&ref_id='.$this->uri->segment(3));
						$pic_del = base_url('article/picture_del/'.$item_id.'?ref_id='.$this->uri->segment(3));
						//$pic_del = base_url('article/picture_del/'.$item_id)."?".$this->uri->segment(3);
?>
		$('#bootbox-confirmset<?=$item_id?>').on('click', function() {
					var f = $(this).attr('data-file');
					var w = $(this).attr('data-width');

					bootbox.confirm("<?php echo $language['are you sure to set default picture']?><br><img src='<?php echo base_url() ?>" + f + "' width='" + w + "'>", function(result) {
						if(result) {
								window.location='<?php echo $pic_setdefault?>';
						}
					});
		});

		$('#bootbox-confirm<?=$item_id?>').on('click', function() {
					var f = $(this).attr('data-file');
					var w = $(this).attr('data-width');

					//bootbox.confirm("<?php echo $language['are you sure to delete']?> ", function(result) {
					bootbox.confirm("<?php echo $language['are you sure to delete']?><br><img src='<?php echo base_url() ?>" + f + "' width='" + w + "'>", function(result) {
						if(result) {
								//alert('<?php echo $pic_del?>');
								window.location='<?php echo $pic_del?>';
						}
					});
		});

<?php
			}
?>

})
</script>	

