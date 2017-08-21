<?php 
		$language = $this->lang->language; 
		$i=0;
		
		//$typecrop = 1; //����������������
		//$typecrop = 2; //�����������׹����ǹ͹
		//$typecrop = 3; //�����������׹����ǵ��

		if($orientation == 1){
			//$typecrop = '4/3';
			$typecrop = 'xsize / ysize';
		}else if($orientation == 2){
			//$typecrop = '3/4';
			$typecrop = 'ysize / xsize';
		}else{
			//$typecrop = '1';
			$typecrop = 'xsize / ysize';
		}

		//Debug($picture_list);			
		//Debug($news_item['title']);
		$allnews = count($picture_list);
		$picture_id = $picture_list[$i]['picture_id'];
		//$caption = $picture_list[$i]['caption'];
		$folder = $picture_list[$i]['folder'];

		$caption = (trim($picture_list[$i]['caption']) == '') ? $news_item['title'] : $picture_list[$i]['caption'];
		//echo $caption;
		$now = date('Y-m-d h:i:s');
?>
<div class="row">
		<div class="col-xs-12">
			<div class="page-header">
					<h1>
							<?php echo $language['news'] ?>
							<small>
							<i class="ace-icon fa fa-angle-double-right"></i>
							<?php echo $language['edit'] ?>&nbsp;
							<?php echo $language['picture'] ?>
							</small>
					</h1>
			</div>

			<button class="btn btn-danger btn-sm btn-primary" onclick="window.location='<?php echo site_url('news/picture/'.$news_id) ?>';">
				<i class="bace-icon fa fa-reply icon-only igger-125"></i><?php echo $language['backto'].' '.$language['picture']  ?>
			</button>

			<!-- <button class="btn  btn-sm btn-primary" onclick="window.location='<?php //echo site_url('news/picture_edit/'.$this->uri->segment(3).'?news_id='.$news_id.'&Orientation=2') ?>';">
				<i class="bace-icon fa fa-edit icon-only igger-125"></i><?php //echo $language['orientation'].' '.$language['picture']  ?>
			</button> -->
		</div>

	<div class="col-xs-12">
<?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
		echo form_open('news/picture_watermark', $attributes);
					
					$status = ($picture_list[$i]['status'] == 1) ? '<span class="label label-success arrowed">Enable</span>' : '<span class="label label-danger">Disable</span>';
					$preview_file = 'uploads/news/'.$folder.'/'.$picture_list[$i]['file_name'];

					//$file_name = ($picture_list[$i]['file_name'] == '') ? 'uploads/news/'.$folder.'/'.$picture_list[$i]['file_name'] : 'uploads/thumb/'.$folder.'/'.$picture_list[$i]['file_name'];
								
					if($picture_list[$i]['file_name'] != ''){
							$file_org = $picture_list[$i]['file_name'];

							/*$file_name = 'uploads/news/'.$folder.'/'.$file_org;
							if(!file_exists($file_name)){
									$file_name = 'uploads/tmp/'.$folder.'/'.$file_org;
							}*/

							$file_name = 'uploads/tmp2/'.$folder.'/'.$file_org;
							if(!file_exists($file_name)){
									$file_name = 'uploads/tmp/'.$folder.'/'.$file_org;
							}
							//$file_name = 'uploads/tmp/'.$folder.'/'.$file_org;

					}else $file_name = 'theme/assets/images/gallery/no-img.jpg';								
							$pic_del = base_url('news/picture_del/'.$picture_id)."?".$this->uri->segment(3);

					$imgsize = getimagesize($file_name);
					//Debug($imgsize);

					if($imgsize[0] > 660){
						$col_size1 = 'col-lg-12';
						$col_size2 = 'col-lg-12';
					}else{
						$col_size1 = 'col-lg-8';
						$col_size2 = 'col-lg-4';
					}

					$thumbs = 'uploads/thumb/'.$folder.'/'.$file_org;
					$thumbs2 = 'uploads/thumb2/'.$folder.'/'.$file_org;
					$thumbs3 = 'uploads/thumb3/'.$folder.'/'.$file_org;
					$img_org = 'uploads/news/'.$folder.'/'.$file_org;
					$img_highlight = 'uploads/highlight/'.$folder.'/'.$file_org;

			$getinput = $this->input->get();

			if(isset($getinput['scale'])) 
					$get_scale = $getinput['scale'];

			if(!isset($get_scale)) $get_scale = '16:9';

?>
<div class="page-header"></div>
<div class="col-lg-6">
		<div class="form-group">
				<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['caption']?> </label>
				<div class="col-sm-9">
					<input type="text" name="caption"  class="col-sm-12" placeholder="<?php echo $language['caption']?>" maxlength="255" value="<?php echo $caption ?>">
				</div>
		</div>

		<div class="form-group">
				<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Scale</label>
				<div class="col-sm-9">

						&nbsp; &nbsp; &nbsp;
						<label class="inline">
								<input type="radio" class="ace" value="1:1"  id="scale" <?php if($get_scale == '1:1') echo 'checked'; ?>>
								<span class="lbl middle"> 1:1</span>
						</label>						
						
						<label class="inline">
								<input type="radio" class="ace" value="16:9" id="scale2" <?php if($get_scale == '16:9') echo 'checked'; ?>>
								<span class="lbl middle"> 16:9</span>
						</label>

						&nbsp; &nbsp; &nbsp;
						<label class="inline">
								<input type="radio" class="ace" value="4:3"  id="scale3" <?php if($get_scale == '4:3') echo 'checked'; ?>>
								<span class="lbl middle"> 4:3</span>
						</label>

				</div>
		</div>

		<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right"><?php echo $language['watermark']  ?></label>

				<div class="col-sm-9">
						<label class="inline">
								<input name="watermark" type="radio" class="ace" value="center" checked>
								<span class="lbl middle"> <?php echo $language['center'] ?></span>
						</label>

						&nbsp; &nbsp; &nbsp;
						<label class="inline">
								<input name="watermark" type="radio" class="ace" value="horizontal">
								<span class="lbl middle"> <?php echo $language['horizontal']  ?></span>
						</label>

						&nbsp; &nbsp; &nbsp;
						<label class="inline">
								<input name="watermark" type="radio" class="ace" value="vertical">
								<span class="lbl middle"> <?php echo $language['vertical']  ?></span>
						</label>

						&nbsp; &nbsp; &nbsp;
						<label class="inline">
								<input name="watermark" type="radio" class="ace" value="logo">
								<span class="lbl middle"> <?php echo $language['bigsize']  ?></span>
						</label>
				</div>
				
					<button class="btn btn-success btn-sm btn-primary" type="submit">
						<i class="bace-icon fa fa-edit icon-only igger-125"></i><?php echo $language['create'].' '.$language['watermark']  ?>
					</button>
		</div>

		<input type="hidden" name="id" value="<?php echo $this->uri->segment(3) ?>">
		<input type="hidden" name="picture_id" value="<?php echo $picture_id ?>">
		<input type="hidden" name="news_id" value="<?php echo $news_id?>">
		<input type="hidden" name="type" value="news">
		<input type="hidden" name="folder" value="<?php echo $folder?>">
		<input type="hidden" name="file" value="<?php echo $picture_list[$i]['file_name']?>">
</div>
<div class="col-lg-6">
		<div id="controls">
			<a href="javascript:void(0);"><img src="<?php echo site_url('images/clockwise.png')?>" id="button_rotateL"></a> 
			<a href="javascript:void(0);"><img src="<?php echo site_url('images/anticlockwise.png')?>" id="button_rotateR"></a> 
		</div>
</div>

<div class="col-lg-12">
		<div class="col-lg-8">
			<button class="btn btn-sm btn-primary" type="button" id="crop_image">
				<i class="bace-icon fa fa-edit icon-only igger-125"></i><?php echo $language['crop image']  ?>
			</button>
		</div>
		<div class="col-lg-1">
			<div id="left"></div>
		</div>
		<div class="col-lg-1">
			<div id="top"></div>
		</div>
		<div class="col-lg-1">
			<div id="width"></div>
		</div>
		<div class="col-lg-1">
			<div id="height"></div>
		</div>
</div>

<div class="col-lg-12">
			<div class="<?php echo $col_size1?>">
				<h2><?php echo $language['please drag on the image'] ?></h2><img src='<?php echo base_url($file_name)?>?<?=$now?>' id="photo" data-src="<?php echo $file_org?>" data-folder="<?php echo $folder?>" >
			</div>
			<div class="<?php echo $col_size2?> ace-thumbnails">

						<div id="thumbs3">
								<?php if(file_exists($thumbs3)){ 
									$imagesize = getimagesize($thumbs3);
									Debug('<img src='.base_url($thumbs3).' ><br>'.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div>
						<div id="thumbs">
								<?php if(file_exists($thumbs)){ 
									$imagesize = getimagesize($thumbs);
									Debug('<img src='.base_url($thumbs).' ><br>'.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div>
						<div id="thumbs2">
								<?php if(file_exists($thumbs2)){ 
									$imagesize = getimagesize($thumbs2);
									Debug('<img src='.base_url($thumbs2).' ><br>'.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div>						
						<a href="<?php echo base_url($img_org)?>" data-rel="colorbox">
						<div id="img_org">
								<?php if(file_exists($img_org)){
								$imagesize = getimagesize($img_org);
								Debug('<img src='.base_url($img_org).' ><br>'.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div></a>

						<a href="<?php echo base_url($img_highlight)?>" data-rel="colorbox">
						<div id="img_highlight">
								<?php if(file_exists($img_highlight)){
								$imagesize = getimagesize($img_highlight);
								Debug('<img src='.base_url($img_highlight).' ><br>'.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div></a>

			</div>
</div>
<?php echo form_close();?>
</div>
</div>
<?php
	
	//Debug($_SERVER);
	//Debug($_SERVER['REQUEST_URI']);
	//$_SERVER['HTTP_HOST'];
	echo css_asset('imgareaselect-animated.css');
?>
<?php
	echo js_asset('jquery.min.img.js'); 
	echo js_asset('jquery.imgareaselect.pack.js'); 
?>
<script type="text/javascript">
function getSizes(im,obj){
		var x_axis = obj.x1;
		var x2_axis = obj.x2;
		var y_axis = obj.y1;
		var y2_axis = obj.y2;

		var thumb_width = obj.width;
		var thumb_height = obj.height;

		$("#left").val(x_axis);
		$("#top").val(y_axis);

		$("#width").val(thumb_width);
		$("#height").val(thumb_height);
		
		/*$("#left").html("left:" + x_axis);
		$("#top").html("top:" +y_axis);

		$("#width").html("width:" + thumb_width);
		$("#height").html("height:" + thumb_height);*/
		
		if(thumb_width > 10){
			$('#crop_image').removeAttr('disabled');
		}
		
		/*if(thumb_width > 10){
				if(confirm("Do you want to save image..!")){
						//thumb 1
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + thumb_width + "&h="+ thumb_height + "&x1=" + x_axis + "&y1=" + y_axis + "&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#thumbs").html(rsponse);
								    //$("#thumbs").html("");
									//$("#thumbs").html("<img src='" + rsponse + "' />");
							}
						});	

						//thumb 2
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_thumb2?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + thumb_width + "&h="+ thumb_height + "&x1=" + x_axis + "&y1=" + y_axis + "&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#thumbs2").html(rsponse);
							}
						});	

						//Big Size
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_img?type=news&img="+$("#photo").attr('data-src')+"&w=" + thumb_width + "&h="+ thumb_height + "&x1=" + x_axis + "&y1=" + y_axis + "&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#img_org").html(rsponse);
							}
						});	
					}
			}else
				alert("Please select portion..!");*/
}

function crop_image1(){
						//thumb 1
			$.ajax({
					type:"GET",
						url:"<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val()  + "&y1=" + $("#top").val() + "&folder=" + $("#photo").attr('data-folder')  + "&t_width=120&t_height=120",
						data: {t_width: 120, t_height : 120},
						cache:false,
						success:function(rsponse){
						    $("#thumbs3").html(rsponse);
							AlertSuccess	('Crop image success.');
						}
			});	

			//alert("<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val() + "&y1=" + $("#top").val() + "&folder=" + $("#photo").attr('data-folder') + "&t_width=120&t_height=120");
}

function crop_image4(){
				alert('No size.');
}

function crop_image16(){

						//thumb 1
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val()  + "&y1=" + $("#top").val() + "&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#thumbs").html(rsponse);
							}
						});	

						//thumb 2
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_thumb2?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val()  + "&y1=" + $("#top").val() + "&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#thumbs2").html(rsponse);
							}
						});	

						//Big Size
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_img?type=news&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val()  + "&y1=" + $("#top").val() + "&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#img_org").html(rsponse);
									//AlertSuccess	('Crop image success.');
							}
						});	

						//highlight
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_highlight?type=highlight&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val()  + "&y1=" + $("#top").val() + "&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#img_highlight").html(rsponse);
									AlertSuccess	('Crop image success.');
							}
						});	
}

$(document).ready(function () {

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

	$('#crop_image').attr('disabled', 'disabled');

	$('img#photo').imgAreaSelect({
        aspectRatio: '<?php echo $get_scale ?>',
        onSelectEnd: getSizes
    });

	$('#crop_image').click(function(){
<?php
			if($get_scale == '1:1')
					echo 'crop_image1();';
			else if($get_scale == '4:3')
					echo 'crop_image4();';
			else
					echo 'crop_image16();';
?>			
			//alert("<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val() + "&y1=" + $("#top").val() + "&folder=" +$("#photo").attr('data-folder'));
    });

	$('#button_rotateL').click(function(){
		$('#save_results').html('saving...');
		$('#preview-pane').attr('style', 'display:none;');

		$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('news/rotate/'.$this->uri->segment(3).'?folder='.$folder.'&rotate=l&file='.$picture_list[$i]['file_name']) ?>",
		   //url: "<?php echo site_url('gallery/rotate') ?>",
		   //data: "gallery_id : <?php //echo $gallery_id?>, rotate : 'l', folder : '<?php echo $folder?>', file : '<?php //echo $picture_list[$i]['file_name']?>' ",
		   success: function(msg){
	     		$('#save_results').html(msg);
				//alert(msg);
				window.location='<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>';
	   		}
	 	});
	});

	$('#button_rotateR').click(function(){
		$('#save_results').html('saving...');
		$('#preview-pane').attr('style', 'display:none;');

		$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('news/rotate/'.$this->uri->segment(3).'?folder='.$folder.'&rotate=r&file='.$picture_list[$i]['file_name']) ?>",
		   //url: "<?php echo site_url('gallery/rotate') ?>",
		   //data: "gallery_id : <?php //echo $gallery_id?>, rotate : 'r', folder : '<?php echo $folder?>', file : '<?php //echo $picture_list[$i]['file_name']?>' ",
		   success: function(msg){
	     		$('#save_results').html(msg);
				//alert(msg);
				window.location='<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>';
	   		}
	 	});
	});

	$('#scale').click(function(){
				var v = $(this).val();
				//alert(v);
				window.location='<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&scale=' + v;
	});

	$('#scale2').click(function(){
				var v = $(this).val();
				//alert(v);
				window.location='<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&scale=' + v;
	});

	$('#scale3').click(function(){
				var v = $(this).val();
				//alert(v);
				window.location='<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&scale=' + v;
	});

});
</script>