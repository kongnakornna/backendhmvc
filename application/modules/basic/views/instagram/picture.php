<?php 
		$language = $this->lang->language; 
		$i=0;

		$allinstagram = count($picture_list);
		$datenow = date('Ymd');
		//$maxcat = count($dara_type);
		
		//Debug($instagram_list);
		//Debug($picture_list);
		//Debug($picture_list[0]['folder']);

		$folder = ($instagram_list[0]['folder'] != "") ? $instagram_list[0]['folder']:$datenow;
		//Debug($folder);

		$instagram_id = $instagram_list[0]['instagram_id'];
		$instagram_id2 = $instagram_list[0]['instagram_id2'];
		//$category_id = 19;
		//$brand_id = $instagram_list[0]['brand_id'];

		$title_instagram = ($instagram_list[0]['title'] == '') ? $instagram_list[1]['title'] : $instagram_list[0]['title'];

		$previewurl = $this->config->config['www']."/instagram/".$instagram_id2."/?preview=1";
?>
<div class="page-content-area">
		<div class="page-header">
				<h1>
					<?php echo $language['instagram'] ?>
					<small>
							<i class="ace-icon fa fa-angle-double-right"></i>
							<?php echo $title_instagram ?>
					</small>
				</h1>
		</div><!-- /.page-header -->

<div class="col-xs-12">
		<!-- <button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('instagram/addpic') ?>';">
			<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['picture']  ?>
		</button> -->
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open_multipart('instagram/upload_pic', $attributes);
?>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
										<div class="col-sm-9">
											<div class="col-xs-12">
													<div id="upload_avatar"><input type="file" id="picture_instagram" name="picture_instagram[]" multiple /></div>
													<?php echo $language['upload_max_filesize'].' = '.ini_get('upload_max_filesize');?>
											</div>
										</div>
								</div>
								<input type="hidden" name="caption" value="<?php echo $title_instagram ?>">
								<input type="hidden" name="instagram_id" value="<?php echo $this->uri->segment(3) ?>">
								<input type="hidden" name="create_date" value="<?php echo $folder ?>">
								<?php if($allinstagram == 0){ ?><input type="hidden" name="set_default" value="1"><?php } ?>

								<div style="clear: both;"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-1 col-md-9">

											<button type="button" class="btn btn-purple bootbox-options preview">
												<i class="ace-icon fa fa-search-plus bigger-110"></i> <?php echo $language['preview'] ?>
											</button>

											<a href="<?php echo base_url('instagram/edit/'.$this->uri->segment(3))?>">
												<button type="button" class="btn btn-primary">
													<i class="ace-icon fa fa-pencil align-top bigger-125"></i><?php echo $language['edit'] ?> <?php echo $language['instagram'] ?>
												</button>
											</a>

											<button type="submit" class="btn btn-info"><i class="ace-icon glyphicon glyphicon-upload bigger-110">
												</i><?php echo $language['upload'] ?>
											</button>

											<a href="<?php echo base_url('elvis/instagram/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-danger">
											<i class="ace-icon fa fa-cloud-download bigger-110"></i>Import From Elvis</button>
											</a>

											<a href="<?php echo base_url('instagram/picture_order/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-success"><i class="ace-icon glyphicon glyphicon-align-right"></i><?php echo $language['order'] ?></button>
											</a>
											<!-- <button type="reset" class="btn"><i class="ace-icon fa fa-undo bigger-110"></i>Reset</button> -->
										</div>
								</div>

		<!-- PAGE CONTENT BEGINS -->
		<div>
<?php //Debug($title_instagram); ?>
			<ul class="ace-thumbnails clearfix">
<?php
			if($picture_list)
					for($i=0;$i<$allinstagram;$i++){
								
								$picture_id = $picture_list[$i]['picture_id'];
								$default = $picture_list[$i]['default'];
								//$file_name = $picture_list[$i]['file_name'];
								$caption = $picture_list[$i]['caption'];
								$folder = $picture_list[$i]['folder'];
								$status = ($picture_list[$i]['status'] == 1) ? '<span class="label label-success arrowed">Enable</span>' : '<span class="label label-danger">Disable</span>';

								$preview_file = 'uploads/instagram/'.$folder.'/'.$picture_list[$i]['file_name'];
								
								//$file_name = ($picture_list[$i]['file_name'] == '') ? 'uploads/instagram/'.$folder.'/'.$picture_list[$i]['file_name'] : 'uploads/thumb/'.$folder.'/'.$picture_list[$i]['file_name'];
								if($picture_list[$i]['file_name'] != ''){

									$file_name = 'uploads/thumb300/'.$folder.'/'.$picture_list[$i]['file_name'];									
									if(!file_exists($file_name)){
											$file_name = 'uploads/instagram/'.$folder.'/'.$picture_list[$i]['file_name'];
											if(!file_exists($file_name)) $file_name = _IMG_NOTFOUND2;
									}

								}else $file_name = _IMG_NOTFOUND2;

								$pic_attr = getimagesize($file_name);
								$pic_width = ($pic_attr[0] > 568) ? 568 : $pic_attr[0];
								
								$pic_del = base_url('instagram/picture_del/'.$picture_id)."?".$this->uri->segment(3);
								
								$attr_width = 'width="150" height="150" alt="150x150"';

								//$edit_picture = base_url('instagram/picture_edit/'.$picture_id.'?'.$this->uri->segment(3));
								$edit_picture = base_url('instagram/picture_edit/'.$picture_id.'?instagram_id='.$this->uri->segment(3));
?>
										<li>
											<a href="<?php echo base_url($preview_file) ?>" data-rel="colorbox">
												<img src="<?php echo base_url($file_name) ?>" <?php if($file_name == _IMG_NOTFOUND2) echo 'width="173"'; ?> />
												<div class="tags">
													<span class="label-holder">
														<?php echo $status?>
													</span>

													<!-- <span class="label-holder">
														<span class="label label-info arrowed"><?php echo $folder?></span>
													</span> -->

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

											<div class="tools tools-top in">
												<!-- <a href="#">
													<i class="ace-icon fa fa-link"></i>
												</a> -->

												<a id="bootbox-confirmset<?=$picture_id?>" class="setdefault" href="javascript:void(0);" title="<?php echo $language['set_default']?>" data-id="<?php echo $picture_id?>" data-file="<?php echo $file_name?>" data-width="<?php echo $pic_width?>" data-rel="tooltip">
													<i class="ace-icon glyphicon glyphicon-bookmark orange"></i>
												</a>

												<a href="<?php echo $edit_picture?>" title="<?php echo $language['edit'].' Thumb'?>" data-rel="tooltip">
													<i class="ace-icon fa fa-pencil"></i>
												</a>

												<a id="bootbox-confirm<?=$picture_id?>" class="delimg" href="javascript:void(0);<?php //$pic_del?>" data-id="<?php echo $picture_id?>" data-file="<?php echo $file_name?>" data-width="<?php echo $pic_width?>" title="<?php echo $language['delete'].' '.$language['picture']?>"  data-rel="tooltip">
													<i class="ace-icon fa fa-times red"></i>
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

</div>

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );

		/*$('.preview').on('click', function() {			
				window.open('<?php echo $previewurl ?>');
		});*/

		$(".bootbox-options").on(ace.click_event, function() {
					var url = '<?php echo $previewurl ?>';
					var title = '<?php echo $title_instagram ?>';
					bootbox.dialog({
						message: "<span class='bigger-110'><?php echo $language['preview'].$language['instagram'] ?> " + title + "</span>",
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

		$('#picture_instagram').ace_file_input({

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
					console.log($(this).data('ace_input_files'));
					console.log($(this).data('ace_input_method'));
		});

		$('.setdefault').on('click', function() {
					var f = $(this).attr('data-file');
					var id = $(this).attr('data-id');
					var w = $(this).attr('data-width');
					bootbox.confirm("<?php echo $language['are you sure to set default picture']?><br><img src='<?php echo base_url() ?>" + f + "' width='" + w + "'>", function(result) {
							if(result) {
									//alert('default ' + id);
									window.location='<?php echo base_url('instagram/set_default/?ref_id='.$this->uri->segment(3)) ?>&id=' + id;
							}
					});
		});

		$('.delimg').on('click', function() {
					var f = $(this).attr('data-file');
					var id = $(this).attr('data-id');
					var w = $(this).attr('data-width');
					bootbox.confirm("<?php echo $language['are you sure to delete']?><br><img src='<?php echo base_url() ?>" + f + "' width='" + w + "'>", function(result) {
							if(result) {
									//alert('del ' + id);
									window.location='<?php echo base_url('instagram/picture_del/?ref_id='.$this->uri->segment(3)) ?>&id=' + id;
							}
					});
		});

<?php
			//Debug($picture_list);			
			$allinstagram = count($picture_list);
			if($picture_list)
					for($i=0;$i<$allinstagram;$i++){
								
								$picture_id = $picture_list[$i]['picture_id'];
								//$file_name = $picture_list[$i]['file_name'];
								$caption = $picture_list[$i]['caption'];

								$pic_setdefault = base_url('instagram/set_default/?id='.$picture_id.'&ref_id='.$this->uri->segment(3));
								
								//$pic_del = base_url('instagram/picture_del/'.$picture_id.'?ref_id='.$this->uri->segment(3));
								$pic_del = base_url('instagram/picture_del/'.$picture_id)."?".$this->uri->segment(3);

?>
		/*$('#bootbox-confirmset<?=$picture_id?>').on('click', function() {
					var f = $(this).attr('data-file');
					var w = $(this).attr('data-width');
					bootbox.confirm("<?php echo $language['are you sure to set default picture']?><br><img src='<?php echo base_url() ?>" + f + "' width='" + w + "'>", function(result) {
							if(result) {
									window.location='<?php echo $pic_setdefault?>';
							}
					});
		});

		$('#bootbox-confirm<?=$picture_id?>').on('click', function() {
					var f = $(this).attr('data-file');
					var w = $(this).attr('data-width');
					bootbox.confirm("<?php echo $language['are you sure to delete']?><br><img src='<?php echo base_url() ?>" + f + "' width='" + w + "'>", function(result) {
						if(result) {
								//alert('<?php echo $pic_del?>');
								window.location='<?php echo $pic_del?>';
						}
					});
		});*/

<?php
			}
?>

});

</script>

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
})
</script>	