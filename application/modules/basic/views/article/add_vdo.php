<?php 
		$language = $this->lang->language; 
		$i=0;
		//$maxcat = count($dara_type);
		$allarticle = count($item_list);
		//Debug($article_list);

		$article_id = $article_list[0]['article_id2'];
		$category_id = $article_list[0]['category_id'];
		$subcategory_id = $article_list[0]['subcategory_id'];

		$title_article = ($article_list[0]['title'] == '') ? $article_list[1]['title'] : $article_list[0]['title'];
		$previewurl = $this->config->config['www']."/article/".$category_id."/".$subcategory_id."/".$article_id."/?preview=1";
?>
<style type="text/css">
ul.ace-thumbnails li img{width: 250px; height: 170px;}
</style>
<!-- PAGE CONTENT BEGINS -->

<div class="col-xs-12">
		<!-- <button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('article/addpic') ?>';">
			<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['picture']  ?>
		</button> -->
<?php

	//Debug($item_list);
	//Debug($article_list);

	if(count($item_list) != 0){
			$list_date = $item_list[0];
			if($list_date->create_date != ''){
				list($create_date, $time) = explode(" ", $list_date->create_date);
				$folder = str_replace("-", "", $create_date);
			}else
				$folder = '';

			//$title_article = $list_date->title;
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

							list($date, $time) = explode(" ", $article_list[0]['create_date']);
							list($yy, $mm, $dd) = explode("-", $date);
							$folder = $yy.$mm.$dd;
					}
			}else{
							/*list($date, $time) = explode(" ", $article_list[0]['create_date']);
							list($yy, $mm, $dd) = explode("-", $date);
							$folder = $yy.$mm.$dd;*/
			}
	}
	//Debug($itemedit_list);

	if($itemedit_list){

			$galleryset_id = $itemedit_list[0]->galleryset_id;
			$caption = $itemedit_list[0]->caption;
			$url_youtube = $itemedit_list[0]->url;
			$status_item = $itemedit_list[0]->status;

			//list($p1,$p2) = explode("=", $url);
			//$thumb_youtube = 'http://img.youtube.com/vi/'.$p2.'/0.jpg';
			$thumb_youtube = $this->api_model->Img_Youtube($url_youtube);

	}

	$originalpic = '';
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open_multipart('article/save_clip', $attributes);
?>
								<!-- <div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
										<div class="col-sm-9">
											<div class="col-xs-12">
													<div id="upload_avatar"><input type="file" id="picture_article" name="picture_article[]" multiple /></div>
													<?php echo $language['upload_max_filesize'].' = '.ini_get('upload_max_filesize');?>
											</div>
										</div>
								</div> -->
								<?php
									if(isset($galleryset_id)) echo '<input type="hidden" name="galleryset_id" value="'.$galleryset_id.'">';
								?>

								<?php if(isset($thumb_youtube)){ ?>
								<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['youtube']?> </label>
										<div class="col-sm-9">
											<img src="<?php echo $thumb_youtube ?>" border="0">
										</div>
								</div>
								<? } ?>

								<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['caption']?> </label>
										<div class="col-sm-9">
											<input type="text" name="caption"  class="col-sm-12" placeholder="<?php echo $language['caption']?>" maxlength="150" value="<?php if(isset($caption)) echo $caption ?>">
											<code><i class="menu-icon fa fa-info"></i> ไม่เกิน 150 ตัวอักษร</code>
										</div>
								</div>
		
								<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['url_clip']?> </label>
										<div class="col-sm-9">
											<input  type="text" class="col-xs-10 col-sm-12" placeholder="<?php echo $language['url_clip'] ?>" id="url" name="url" value="<?php if(isset($url_youtube)) echo $url_youtube ?>">
											<div id="countitle"></div>
											<code><i class="menu-icon fa fa-info"></i> กรุณาใส่ URL ของ คลิป VDO ของท่าน <br>ตัวอย่างเช่น <li>https://www.youtube.com/watch?v=opji5DgE_nQ</li></code>
										</div>
								</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
										<div class="col-xs-3">
													<label>
														<input name="status" id="clip_status" class="ace ace-switch" type="checkbox" value=1  <?php if(isset($status_item) && $status_item == 1) echo "checked";?> />
														<span class="lbl"></span>
													</label>
										</div>
								</div>

								<!-- <input type="hidden" name="caption" value="<?php echo $title_article?>"> -->
								<input type="hidden" name="article_id" value="<?php echo $this->uri->segment(3)?>">
								<input type="hidden" name="create_date" value="<?php echo $folder?>">
								<input type="hidden" name="type" value="2">
								<?php if($allarticle == 0){ ?><input type="hidden" name="set_default" value="1"><?php } ?>

								<div style="clear: both;"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-1 col-md-9">

										<!-- <button type="button" class="btn btn-purple bootbox-options preview">
											<i class="ace-icon fa fa-search-plus bigger-110"></i> <?php echo $language['preview'] ?>
										</button> -->

										<!-- <a href="<?php echo base_url('article/edit/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-primary"><i class="ace-icon fa fa-pencil align-top bigger-125"></i><?php echo $language['edit'] ?> <?php echo $language['article'] ?></button></a> -->
<?php
		if(isset($load_bk[0])){
				$bk = json_decode($load_bk[0]['picture']);
				$folder_img = $load_bk[0]['folder_img'];
				//Debug($bk[0]);
				$originalpic = $bk[0];
				//Debug($load_bk[0]['folder_img']);
		}
?>
											<button type="submit" class="btn btn-info">
												<i class="ace-icon fa fa-floppy-o bigger-110"></i><?php echo $language['save'] ?>
											</button>

											<!-- <button type="submit" class="btn btn-info">
												<i class="ace-icon glyphicon glyphicon-upload bigger-110"></i><?php echo $language['upload'] ?>
											</button> -->

											<!-- <a href="<?php echo base_url('elvis/article/'.$this->uri->segment(3))?>"><button type="button" class="btn btn-danger">
											<i class="ace-icon fa fa-cloud-download bigger-110"></i>Download From Elvis</button></a> -->

											<!-- <a href="<?php echo base_url('article/picture_order/'.$this->uri->segment(3))?>">
												<button type="button" class="btn btn-success">
													<i class="ace-icon glyphicon glyphicon-align-right"></i><?php echo $language['order'] ?>
												</button>
											</a> -->

										</div>
								</div>
</div>
			
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
						$picture_id = $item_list[$i]->galleryset_id;

						$pic_setdefault = base_url('article/set_default/?id='.$picture_id.'&ref_id='.$this->uri->segment(3));
						$pic_del = base_url('article/picture_del/'.$picture_id.'?ref_id='.$this->uri->segment(3));
						//$pic_del = base_url('article/picture_del/'.$picture_id)."?".$this->uri->segment(3);
?>
		$('#bootbox-confirmset<?=$picture_id?>').on('click', function() {
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

