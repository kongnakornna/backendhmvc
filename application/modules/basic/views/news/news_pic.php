<?php 
		$language = $this->lang->language; 
		$i=0;
		//$maxcat = count($dara_type);
?>
<style type="text/css">
ul.ace-thumbnails li img{width: 250px; height: 170px;}
</style>
<!-- PAGE CONTENT BEGINS -->

<div class="col-xs-12">
		<!-- <button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('news/addpic') ?>';">
			<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['picture']  ?>
		</button> -->
<?php

	//Debug($picture_list);
	//Debug($news_list);

	if(count($picture_list) != 0){
			if($picture_list[0]['create_date'] != ''){
				list($create_date, $time) = explode(" ", $picture_list[0]['create_date']);
				$folder = str_replace("-", "", $create_date);
			}else
				$folder = '';

			$title_news = $picture_list[0]['title'];
	}else{
			$picture_list = null;
			$title_news = '';
			$folder = '';
	}

	if($title_news == ""){
		if(count($picture_list) == 0){
				if(isset($news_list)){
						if(count($news_list) > 1)
							$title_news = ($news_list[1]['title'] != "") ? $news_list[1]['title'] : $news_list[0]['title'];

						list($date, $time) = explode(" ", $news_list[0]['create_date']);
						list($yy, $mm, $dd) = explode("-", $date);
						$folder = $yy.$mm.$dd;
				}
		}else{

						/*list($date, $time) = explode(" ", $news_list[0]['create_date']);
						list($yy, $mm, $dd) = explode("-", $date);
						$folder = $yy.$mm.$dd;*/
		
		}

	}

	$originalpic = '';
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open_multipart('news/upload_pic', $attributes);
?>
								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
										<div class="col-sm-9">
											<div class="col-xs-12">

													<div id="upload_avatar"><input type="file" id="picture_news" name="picture_news[]" multiple /></div>
													<?php echo $language['upload_max_filesize'].' = '.ini_get('upload_max_filesize');?>

											</div>
										</div>
								</div>
								<input type="hidden" name="caption" value="<?php echo $title_news?>">
								<input type="hidden" name="news_id" value="<?php echo $this->uri->segment(3)?>">
								<input type="hidden" name="create_date" value="<?php echo $folder?>">

								<div style="clear: both;"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">

											<a href="<?php echo base_url('news/edit/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-primary"><i class="ace-icon fa fa-pencil align-top bigger-125"></i><?php echo $language['edit'] ?> <?php echo $language['news'] ?></button></a>
											&nbsp; &nbsp; &nbsp;
<?php
		if(isset($load_bk[0])){
				$bk = json_decode($load_bk[0]['picture']);
				$folder_img = $load_bk[0]['folder_img'];
				//Debug($bk[0]);
				$originalpic = $bk[0];
				//Debug($load_bk[0]['folder_img']);
		}

		if($originalpic != ''){
			//$encodeurl = urlencode($originalpic);
			$encodeurl = base64_encode($originalpic);
?>
											<a href="<?php echo base_url('tranfer/download/'.$this->uri->segment(3).'?folder='.$folder_img.'&url='.$encodeurl)?>"><button type="button" class="btn btn-warning"><i class="ace-icon fa bigger-110"></i>Download Old Picture</button></a>&nbsp; &nbsp; &nbsp;
<?php		
		}
?>
											<button type="submit" class="btn btn-info"><i class="ace-icon glyphicon glyphicon-upload bigger-110"></i>UPLOAD</button>
											&nbsp; &nbsp; &nbsp;
											<a href="<?php echo base_url('elvis/news/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-danger">
											<i class="ace-icon fa fa-cloud-download bigger-110"></i>Download From Elvis</button></a>&nbsp; &nbsp; &nbsp;
											<a href="<?php echo base_url('news/picture_order/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-success"><i class="ace-icon glyphicon glyphicon-align-right"></i>Picture Order</button></a>
										</div>
								</div>
			<div>

			<span class=""><?php //if($picture_list) echo $caption = $picture_list[0]['caption'];?></span>
			<ul class="ace-thumbnails clearfix">
<?php
			Debug('ภาพข่าว '.$title_news);
			//echo $create_date;
			//Debug($picture_list);			
			$allnews = count($picture_list);
			if($picture_list)
					for($i=0;$i<$allnews;$i++){
								
								$picture_id = $picture_list[$i]['picture_id'];
								$default = $picture_list[$i]['default'];
								//$file_name = $picture_list[$i]['file_name'];
								$caption = $picture_list[$i]['caption'];
								$folder = $picture_list[$i]['folder'];
								$status = ($picture_list[$i]['status'] == 1) ? '<span class="label label-success arrowed">Enable</span>' : '<span class="label label-danger">Disable</span>';

								$preview_file = 'uploads/news/'.$folder.'/'.$picture_list[$i]['file_name'];
								
								//$file_name = ($picture_list[$i]['file_name'] == '') ? 'uploads/news/'.$folder.'/'.$picture_list[$i]['file_name'] : 'uploads/thumb/'.$folder.'/'.$picture_list[$i]['file_name'];
								if($picture_list[$i]['file_name'] != ''){

										$file_name = 'uploads/thumb/'.$folder.'/'.$picture_list[$i]['file_name'];
										if(!file_exists($file_name)){
												$file_name = 'uploads/news/'.$folder.'/'.$picture_list[$i]['file_name'];
										}

								}else $file_name = 'theme/assets/images/gallery/no-img.jpg';
								
								$pic_del = base_url('news/picture_del/'.$picture_id)."?".$this->uri->segment(3);
								
								//$attr_width = 'width="150" height="150" alt="150x150"';

								//$edit_picture = base_url('news/picture_edit/'.$picture_id.'?'.$this->uri->segment(3));

								$edit_thumb = base_url('news/picture_edit_thumb/'.$picture_id.'?news_id='.$this->uri->segment(3));
								//$edit_picture = base_url('news/picture_edit/'.$picture_id.'?news_id='.$this->uri->segment(3));

?>
										<li>
											<a href="<?php echo base_url().$preview_file?>" data-rel="colorbox">
												<img src="<?php echo base_url().$file_name?>" />
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
													<?php if($default == 1){ ?>
													<span class="label-holder">
														<span class="label label-warning arrowed-in"><?php echo $language['default']?></span>
													</span>
													<?php } ?>

												</div>
											</a>

											<div class="tools tools-top">
												<!-- <a href="#">
													<i class="ace-icon fa fa-link"></i>
												</a> -->
												<!-- <a href="#">
													<i class="ace-icon fa fa-paperclip"></i>
												</a> -->
												<a id="bootbox-confirmset<?=$picture_id?>" href="#" title="<?php echo $language['set_default']?>">
													<i class="ace-icon glyphicon glyphicon-bookmark"></i>
												</a>

												<a href="<?php echo $edit_thumb?>" title="<?php echo $language['edit'].' Thumb'?>">
													<i class="ace-icon fa fa-pencil"></i>
												</a>

												<a id="bootbox-confirm<?=$picture_id?>" href="#<?=$pic_del?>">
													<i class="ace-icon fa fa-times red" title="<?php echo $language['delete'].' '.$language['picture']?>"></i>
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
		$('#picture_news').ace_file_input({
					style:'well',
					btn_choose:'Drop files here or click to choose',
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
			//Debug($picture_list);			
			$allnews = count($picture_list);
			if($picture_list)
				for($i=0;$i<$allnews;$i++){
						$picture_id = $picture_list[$i]['picture_id'];

						$pic_setdefault = base_url('news/set_default/?id='.$picture_id.'&ref_id='.$this->uri->segment(3));
						$pic_del = base_url('news/picture_del/'.$picture_id.'?ref_id='.$this->uri->segment(3));
						//$pic_del = base_url('news/picture_del/'.$picture_id)."?".$this->uri->segment(3);
?>
		$('#bootbox-confirmset<?=$picture_id?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to set default picture']?> ", function(result) {
						if(result) {
								//alert('del');
								window.location='<?php echo $pic_setdefault?>';

								/*$.ajax({
									type: 'POST',
									url: "<?php echo base_url('news/set_default')?>",
									data: {id: <?php echo $picture_id ?>, ref_id : <?php echo $this->uri->segment(3) ?>},
									cache: false,
									success: function(data){
											alert(data);
											$("#msg_info").fadeOut();
											if(data == 0){
												$("#msg_error").attr('style','display:block;');
												AlertError('Inactive');
											}else{
												$("#msg_success").attr('style','display:block;');
												AlertSuccess	('Active And Generate json file.');
											}
									}
								});*/

						}
					});
		});

		$('#bootbox-confirm<?=$picture_id?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?> ", function(result) {
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

