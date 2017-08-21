<?php 
		$language = $this->lang->language; 
		$i=0;
		//$maxcat = count($dara_type);
		$allcolumn = count($picture_list);

		$count_column = count($column_list);

		if($count_column == 2){
			$column_id = $column_list[0]['column_id'];
			$column_id_th = $column_list[1]['column_id'];
			$column_id2 = $column_list[0]['column_id2'];
			$category_id = $column_list[0]['category_id'];
			$subcategory_id = $column_list[0]['subcategory_id'];
		}else{
			$column_id = $column_list[0]['column_id'];
			//$column_id_th = $column_list[1]['column_id'];
			$column_id2 = $column_list[0]['column_id2'];
			$category_id = $column_list[0]['category_id'];
			$subcategory_id = $column_list[0]['subcategory_id'];
		}
		//Debug($column_list);

		$title_column = ($column_list[0]['title'] == '') ? $column_list[1]['title'] : $column_list[0]['title'];

		$previewurl = $this->config->config['www']."/column/".$category_id."/".$subcategory_id."/".$column_id2."/?preview=1";
?>
<style type="text/css">
ul.ace-thumbnails li img{width: 250px; height: 170px;}
#nav-search{display:none;}
</style>
<!-- PAGE CONTENT BEGINS -->

<div class="col-xs-12">
		<!-- <button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('column/addpic') ?>';">
			<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['picture']  ?>
		</button> -->
<?php
	list($create_date, $time) = explode(" ", $column_list[0]['create_date']);
	$create_date = str_replace("-", "", $create_date);

	//$title_column = $column_list[0]['title'];

	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open_multipart('column/upload_pic', $attributes);
?>
								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
										<div class="col-sm-9">
											<div class="col-xs-12">

													<div id="upload_avatar"><input type="file" id="picture_column" name="picture_column[]" multiple /></div>
													<?php echo $language['upload_max_filesize'].' = '.ini_get('upload_max_filesize');?>

											</div>
										</div>
								</div>
								<input type="hidden" name="caption" value="<?php if($picture_list) echo $caption = $picture_list[0]['caption']?>">
								<input type="hidden" name="column_id" value="<?php echo $this->uri->segment(3)?>">
								<input type="hidden" name="create_date" value="<?php echo $create_date?>">
								<?php if($allcolumn == 0){ ?><input type="hidden" name="set_default" value="1"><?php } ?>

								<div style="clear: both;"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-1 col-md-9">
											<button type="button" class="btn btn-purple bootbox-options preview" data-name="<?=$title_column?>">
												<i class="ace-icon fa fa-search-plus bigger-110"></i> <?php echo $language['preview'] ?>
											</button>

											<a href="<?php echo base_url('column/edit/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-primary"><i class="ace-icon fa fa-pencil align-top bigger-125"></i><?php echo $language['edit'] ?> <?php echo $language['column'] ?></button></a>

											<button type="submit" class="btn btn-info">
												<i class="ace-icon glyphicon glyphicon-upload bigger-110"></i><?php echo $language['upload'] ?>
											</button>

											<a href="<?php echo base_url('elvis/column/'.$this->uri->segment(3))?>">
												<button type="button" class="btn btn-danger"><i class="ace-icon fa fa-cloud-download bigger-110"></i>Download From Elvis</button>
											</a>

											<a href="<?php echo base_url('column/picture_order/'.$this->uri->segment(3))?>">
												<button type="button" class="btn btn-success"><i class="ace-icon glyphicon glyphicon-align-right"></i><?php echo $language['order'] ?></button>
											</a>

										</div>
								</div>
			<div>

			<span class=""><?php //if($picture_list) echo $caption = $picture_list[0]['caption'];?></span>
			<ul class="ace-thumbnails clearfix">
<?php
			Debug('ภาพข่าว '.$title_column);
			//echo $create_date;
			//Debug($picture_list);			
			
			if($picture_list)
					for($i=0;$i<$allcolumn;$i++){
								
								$picture_id = $picture_list[$i]['picture_id'];
								$default = $picture_list[$i]['default'];
								//$file_name = $picture_list[$i]['file_name'];
								$caption = $picture_list[$i]['caption'];
								$folder = $picture_list[$i]['folder'];
								$status = ($picture_list[$i]['status'] == 1) ? '<span class="label label-success arrowed">Enable</span>' : '<span class="label label-danger">Disable</span>';

								$preview_file = 'uploads/column/'.$folder.'/'.$picture_list[$i]['file_name'];
								
								//$file_name = ($picture_list[$i]['file_name'] == '') ? 'uploads/column/'.$folder.'/'.$picture_list[$i]['file_name'] : 'uploads/thumb/'.$folder.'/'.$picture_list[$i]['file_name'];
								if($picture_list[$i]['file_name'] != ''){

										$file_name = 'uploads/thumb/'.$folder.'/'.$picture_list[$i]['file_name'];
										if(!file_exists($file_name)){
											$file_name = 'uploads/column/'.$folder.'/'.$picture_list[$i]['file_name'];
											if(!file_exists($file_name)) $file_name = 'theme/assets/images/gallery/no-img.jpg';
										}

								}else $file_name = 'theme/assets/images/gallery/no-img.jpg';

								$pic_attr = getimagesize($file_name);
								$pic_width = ($pic_attr[0] > 568) ? 568 : $pic_attr[0];
								
								$pic_del = base_url('column/picture_del/'.$picture_id)."?".$this->uri->segment(3);
								
								//$attr_width = 'width="150" height="150" alt="150x150"';
								//picture_edit/1?picture_id=179

								//$edit_thumb = base_url('column/picture_edit/'.$picture_id.'?column_id='.$this->uri->segment(3));
								$edit_thumb = base_url('column/picture_edit/'.$this->uri->segment(3).'?picture_id='.$picture_id);

?>
										<li>
											<a href="<?php echo base_url($preview_file) ?>" data-rel="colorbox">
												<img src="<?php echo base_url($file_name) ?>" />
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

											<div class="tools tools-top in">
												<!-- <a href="#">
													<i class="ace-icon fa fa-link"></i>
												</a> -->
												<!-- <a href="#">
													<i class="ace-icon fa fa-paperclip"></i>
												</a> -->
												<a id="bootbox-confirmset<?=$picture_id?>" href="javascript:void(0);" title="<?php echo $language['set_default']?>" data-file="<?php echo $file_name?>" data-width="<?php echo $pic_width?>" data-rel="tooltip">
													<i class="ace-icon glyphicon glyphicon-bookmark orange"></i>
												</a>

												<a href="<?php echo $edit_thumb?>" title="<?php echo $language['edit'].' Thumb'?>" data-rel="tooltip">
													<i class="ace-icon fa fa-pencil"></i>
												</a>

												<a id="bootbox-confirm<?=$picture_id?>" href="javascript:void(0);<?php //echo $pic_del?>" data-file="<?php echo $file_name?>" data-width="<?php echo $pic_width?>" title="<?php echo $language['delete'].' '.$language['picture']?>" data-rel="tooltip">
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

		/*$('.preview').on('click', function() {			
			window.open('<?php echo $previewurl ?>');
		});	*/

		$(".bootbox-options").on(ace.click_event, function() {
					var url = '<?php echo $previewurl ?>';
					var title = $(this).attr('data-name');
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

		$('#picture_column').ace_file_input({
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
			//Debug($picture_list);			
			$allcolumn = count($picture_list);
			if($picture_list)
				for($i=0;$i<$allcolumn;$i++){
						$picture_id = $picture_list[$i]['picture_id'];

						$pic_setdefault = base_url('column/set_default/?id='.$picture_id.'&ref_id='.$this->uri->segment(3));
						//$pic_del = base_url('column/picture_del/'.$picture_id.'?ref_id='.$this->uri->segment(3));
						$pic_del = base_url('column/picture_del/'.$picture_id)."?".$this->uri->segment(3);
?>
		$('#bootbox-confirmset<?=$picture_id?>').on('click', function() {
					var f = $(this).attr('data-file');
					var w = $(this).attr('data-width');

					bootbox.confirm("<?php echo $language['are you sure to set default picture']?><br><img src='<?php echo base_url() ?>" + f + "' width='" + w + "'>", function(result) {
						if(result) {
								//alert('del');
								window.location='<?php echo $pic_setdefault?>';

								/*$.ajax({
									type: 'POST',
									url: "<?php echo base_url('column/set_default')?>",
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
					var f = $(this).attr('data-file');
					var w = $(this).attr('data-width');

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

