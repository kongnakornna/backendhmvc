	<?php 
		$language = $this->lang->language; 
		$i=0;
		
		//$typecrop = 1; //สี่เหลี่ยมจตุรัส
		//$typecrop = 2; //สี่เหลี่ยมผืนผ้าแนวนอน
		//$typecrop = 3; //สี่เหลี่ยมผืนผ้าแนวตั้ง

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

		//Debug($item_list);			
		//Debug($article_item);			
		//Debug($article_item['title']);
		$allnews = count($item_list);
		$picture_id = $item_list[$i]->galleryset_id;
		//$caption = $item_list[$i]['caption'];
		$folder = $item_list[$i]->folder;

		$caption = (trim($item_list[$i]->caption) == '') ? $article_item['title'] : $item_list[$i]->caption;
		//echo $caption;
		$now = date('Y-m-d h:i:s');
?>
<style type="text/css">
.clearfix{clear:both;float: none;margin: 0;padding: 0;width: 100%;}
.areafix{font-size: 14px;border-radius: 0;padding: 10px;background-color: #dff0d8;border-color: #d6e9c6;color: #3c763d;box-sizing: border-box;line-height: 1.5;}
</style>
<div class="row">
		<div class="col-xs-12">
			<div class="page-header">
					<h1>
							<?php echo $language['article'] ?>
							<small>
							<i class="ace-icon fa fa-angle-double-right"></i>
							<?php echo $language['edit'] ?>&nbsp;
							<?php echo $language['picture'] ?>
							</small>
					</h1>
			</div>

			<button class="btn btn-danger btn-sm btn-primary" onclick="window.location='<?php echo site_url('article/gallery/'.$article_id) ?>';">
				<i class="bace-icon fa fa-reply icon-only igger-125"></i><?php echo $language['backto'].' '.$language['picture']  ?>
			</button>

			<!-- <button class="btn  btn-sm btn-primary" onclick="window.location='<?php //echo site_url('article/picture_edit/'.$this->uri->segment(3).'?article_id='.$article_id.'&Orientation=2') ?>';">
				<i class="bace-icon fa fa-edit icon-only igger-125"></i><?php //echo $language['orientation'].' '.$language['picture']  ?>
			</button> -->
		</div>

	<div class="col-xs-12">
<?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
		echo form_open('article/picture_watermark', $attributes);
					
					$status = ($item_list[$i]->status == 1) ? '<span class="label label-success arrowed">Enable</span>' : '<span class="label label-danger">Disable</span>';
					$preview_file = 'uploads/article/'.$folder.'/'.$item_list[$i]->file_name;

					//$file_name = ($item_list[$i]['file_name'] == '') ? 'uploads/article/'.$folder.'/'.$item_list[$i]['file_name'] : 'uploads/thumb/'.$folder.'/'.$item_list[$i]['file_name'];
								
					if($item_list[$i]->file_name != ''){
							$file_org = $item_list[$i]->file_name;

							/*$file_name = 'uploads/article/'.$folder.'/'.$file_org;
							if(!file_exists($file_name)){
									$file_name = 'uploads/tmp/'.$folder.'/'.$file_org;
							}*/

							$file_name = 'uploads/tmp2/'.$folder.'/'.$file_org;
							if(!file_exists($file_name)){
									$file_name = 'uploads/tmp/'.$folder.'/'.$file_org;
							}
							//$file_name = 'uploads/tmp/'.$folder.'/'.$file_org;

					}else $file_name = 'theme/assets/images/gallery/no-img.jpg';								
							$pic_del = base_url('article/picture_del/'.$picture_id)."?".$this->uri->segment(3);

					$imgsize = @getimagesize($file_name);
					if(!$imgsize){

						$file_nameorg = 'uploads/article/'.$folder.'/'.$file_org;
						$imgsize = @getimagesize($file_nameorg);

						//copy image
						if($imgsize){
							$src = fopen($file_nameorg, 'r');
							$dest = fopen($file_name, 'w');
							stream_copy_to_stream($src, $dest);
						}
						//Debug($file_name);
					}


					if($imgsize[0] > 660){
						$col_size1 = 'col-lg-12';
						$col_size2 = 'col-lg-12';
					}else{
						$col_size1 = 'col-lg-8';
						$col_size2 = 'col-lg-4';
					}

			$img_org = 'uploads/article/'.$folder.'/'.$file_org;
			$thumb120 = 'uploads/thumb120/'.$folder.'/'.$file_org;
			$thumb300 = 'uploads/thumb300/'.$folder.'/'.$file_org;
			$thumb340 = 'uploads/thumb340/'.$folder.'/'.$file_org;
			$thumb450 = 'uploads/thumb450/'.$folder.'/'.$file_org;
			$thumb620 = 'uploads/thumb620/'.$folder.'/'.$file_org;
			$thumb768 = 'uploads/thumb768/'.$folder.'/'.$file_org;

			$getinput = $this->input->get();

			if(isset($getinput['scale'])) 
					$get_scale = $getinput['scale'];

			if(!isset($get_scale)) $get_scale = '16:9';

?>
<div class="page-header"></div>
<div class="col-lg-6">

		<div class="form-group">
				<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Scale</label>
				<div class="col-sm-9">
						<div class="col-xs-4">
							<label class="inline">
									<input type="radio" class="ace setscale" value="1:1" id="scale" <?php if($get_scale == '1:1') echo 'checked'; ?>>
									<span class="lbl middle"> 1:1</span>
							</label>
						</div>
							
						<div class="col-xs-4">
							<label class="inline">
									<input type="radio" class="ace setscale" value="16:9" <?php if($get_scale == '16:9') echo 'checked'; ?>>
									<span class="lbl middle"> 16:9</span>
							</label>
						</div>
							
							<!-- <label class="inline">
									<input type="radio" class="ace setscale" value="9:16" <?php if($get_scale == '9:16') echo 'checked'; ?>>
									<span class="lbl middle"> 9:16</span>
							</label> -->

							<!-- <label class="inline">
									<input type="radio" class="ace setscale" value="4:3" <?php if($get_scale == '4:3') echo 'checked'; ?>>
									<span class="lbl middle"> 4:3</span>
							</label> -->

						<div class="col-xs-4">
							<label class="inline">
									<input type="radio" class="ace setscale" value="3:4" <?php if($get_scale == '3:4') echo 'checked'; ?>>
									<span class="lbl middle"> 3:4</span><br>
									<code>(อัตราส่วนนี้ ไม่จำเป็นต้องครอบภาพ ให้ดูตามความเหมาะสม)</code>
							</label>
						</div>
				</div>
		</div>

		<div class="form-group">
				<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['caption']?> </label>
				<div class="col-sm-9">
					<input type="text" name="caption"  class="col-sm-12" placeholder="<?php echo $language['caption']?>" maxlength="255" value="<?php echo $caption ?>">
				</div>
		</div>

		<div class="form-group">
				<label for="credit_id" class="col-sm-3 control-label no-padding-right"><?php echo $language['credit']?></label>
				<div class="col-sm-9">
						<?php
						//Debug($item_list);
						//Debug($credit_list);
						if($credit_list){
								echo '<select id="credit_id" name="credit_id" class="form-control">';
								echo '<option value="0"> --- '.$language['please_select'].' --- </option>';
								for($i=0;$i<count($credit_list);$i++){
										if($item_list[0]->credit_id == $credit_list[$i]['credit_id'])
											echo '<option value="'.$credit_list[$i]['credit_id'].'" selected="selected"> '.$credit_list[$i]['credit_name'].' </option>';
										else
											echo '<option value="'.$credit_list[$i]['credit_id'].'"> '.$credit_list[$i]['credit_name'].' </option>';
								}
								echo '</select>';
						}
						?>
				</div>
		</div>

		<div class="form-group">
				<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
				<div class="col-xs-3">
						<label>
								<input name="status" id="clip_status" class="ace ace-switch" type="checkbox" value=1  <?php if($item_list[0]->status == 1) echo "checked";?> />
								<span class="lbl"></span>
						</label>
				</div>
		</div>

		<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right"><?php echo $language['watermark']  ?></label>

				<div class="col-sm-9 areafix">

						<label class="inline clearfix">
								<input name="watermark" type="radio" class="ace" value="no">
								<span class="lbl middle"> <?php echo $language['nowatermark'] ?></span>
						</label>

						<label class="inline clearfix">
								<input name="watermark" type="radio" class="ace" value="center" checked>
								<span class="lbl middle"> <?php echo $language['center'] ?></span>
						</label>

						<label class="inline clearfix">
								<input name="watermark" type="radio" class="ace" value="horizontal">
								<span class="lbl middle"> <?php echo $language['horizontal']  ?></span>
						</label>

						<label class="inline clearfix">
								<input name="watermark" type="radio" class="ace" value="vertical">
								<span class="lbl middle"> <?php echo $language['vertical']  ?></span>
						</label>

						<!-- <label class="inline clearfix">
								<input name="watermark" type="radio" class="ace" value="logo">
								<span class="lbl middle"> <?php echo $language['bigsize']  ?></span>
						</label> -->

						<label class="inline clearfix">
								<input name="watermark" type="radio" class="ace" value="topleft">
								<span class="lbl middle"> <?php echo $language['topleft']  ?></span>
						</label>

						<label class="inline clearfix">
								<input name="watermark" type="radio" class="ace" value="buttomright">
								<span class="lbl middle"> <?php echo $language['buttomright']  ?></span>
						</label>

						<button class="btn btn-success btn-sm btn-primary" type="submit">
							<i class="bace-icon fa fa-edit icon-only igger-125"></i><?php echo $language['create'].' '.$language['watermark'].' & '.$language['save']  ?> 
						</button>
				</div>
		</div>

		<input type="hidden" name="id" value="<?php echo $this->uri->segment(3) ?>">
		<input type="hidden" name="picture_id" value="<?php echo $picture_id ?>">
		<input type="hidden" name="article_id" value="<?php echo $article_id?>">
		<input type="hidden" name="type" value="article">
		<input type="hidden" name="folder" value="<?php echo $folder?>">
		<input type="hidden" name="file" value="<?php echo $item_list[0]->file_name ?>">
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
				<h2><?php echo $language['please drag on the image'] ?></h2>
				<img src='<?php echo base_url($file_name)?>?<?=$now?>' id="photo" data-src="<?php echo $file_org?>" data-folder="<?php echo $folder?>" >
			</div>
			<div class="<?php echo $col_size2?> ace-thumbnails">

						<div id="thumb120">
								<?php if(file_exists($thumb120)){ 
									$imagesize = getimagesize($thumb120);
									Debug('<img src='.base_url($thumb120).' ><br><b>thumb120</b> '.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div>

						<div id="thumb300">
								<?php if(file_exists($thumb300)){ 
									$imagesize = getimagesize($thumb300);
									Debug('<img src='.base_url($thumb300).' ><br><b>thumb300</b> '.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div>

						<div id="thumb340">
								<?php if(file_exists($thumb340)){ 
									$imagesize = getimagesize($thumb340);
									Debug('<img src='.base_url($thumb340).' ><br><b>thumb340</b> '.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div>

						<div id="thumb450">
								<?php if(file_exists($thumb450)){ 
									$imagesize = getimagesize($thumb450);
									Debug('<img src='.base_url($thumb450).' ><br><b>thumb450</b> '.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div>

						<div id="thumb620">
								<?php if(file_exists($thumb620)){ 
									//echo "<br>".base_url($thumbs4)."<br>";
									$imagesize = getimagesize($thumb620);
									Debug('<img src='.base_url($thumb620).' ><br><b>thumb620</b> '.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div>

						<div id="thumb768">
								<?php if(file_exists($thumb768)){ 
									$imagesize = getimagesize($thumb768);
									Debug('<img src='.base_url($thumb768).' ><br><b>thumb768</b> '.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div>

						<a href="<?php echo base_url($img_org)?>" data-rel="colorbox">
						<div id="img_org">
								<?php if(file_exists($img_org)){
								$imagesize = getimagesize($img_org);
								Debug('<img src='.base_url($img_org).'?'.time().' ><br><b>org</b> '.$imagesize[0].'x'.$imagesize[1]); } ?>
						</div></a>

			</div>
</div>
<?php echo form_close();?>
</div>
</div>
<div id="showtxt" class="">
	
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

}

function crop_image11(){
			//thumb 1
			Waiting();
			$.ajax({
					type:"GET",
						url:"<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val()  + "&y1=" + $("#top").val() + "&mod=thumb120&folder=" + $("#photo").attr('data-folder')  + "&t_width=120&t_height=120",
						data: {t_width: 120, t_height : 120},
						cache:false,
						success:function(rsponse){
							//alert(rsponse);
						    $("#thumb120").html(rsponse);
							AlertSuccess	('Crop image success.');
						}
			});	
}

function crop_image34(){

						//alert('No size.');
						//Display article

						//alert("<?php echo base_url()?>picture/make_img?type=article&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val()  + "&y1=" + $("#top").val() + "&folder=" +$("#photo").attr('data-folder'));

						Waiting();
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_img?type=article&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val()  + "&y1=" + $("#top").val() + "&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
									//alert(rsponse);
								    $("#img_org").html(rsponse);
									AlertSuccess	('Crop image success.');
							}
						});	
}

function crop_image169(){
			Waiting();
			
						//thumb300
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val() + "&y1=" + $("#top").val() + "&mod=thumb300&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){

								    $("#thumb300").html(rsponse);
								    //$("#thumbs").html("");
									//$("#thumbs").html("<img src='" + rsponse + "' />");

							}
						});	

						//thumb340
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val() + "&y1=" + $("#top").val() + "&mod=thumb340&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#thumb340").html(rsponse);
							}
						});	

						//thumb450
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val() + "&y1=" + $("#top").val() + "&mod=thumb450&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#thumb450").html(rsponse);
							}
						});	

						//thumb620
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val()  + "&y1=" + $("#top").val() + "&mod=thumb620&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#thumb620").html(rsponse);
								    //$("#showtxt").html('thumb 2<br>' + rsponse);
							}
						});	

						//thumb768
						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val()  + "&y1=" + $("#top").val() + "&mod=thumb768&folder=" +$("#photo").attr('data-folder'),
							cache:false,
							success:function(rsponse){
								    $("#thumb768").html(rsponse);
								    //$("#showtxt").html('thumbmenu<br>' + rsponse);
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
					echo 'crop_image11();';
			else if($get_scale == '16:9')
					echo 'crop_image169();';
			else if($get_scale == '3:4')
					echo 'crop_image34();';
			//else
?>			
			//alert("<?php echo base_url()?>picture/make_thumb?t=ajax&img="+$("#photo").attr('data-src')+"&w=" + $("#width").val() + "&h="+ $("#height").val() + "&x1=" + $("#left").val() + "&y1=" + $("#top").val() + "&folder=" +$("#photo").attr('data-folder'));
    });

	$('#button_rotateL').click(function(){

		$('#save_results').html('saving...');
		$('#preview-pane').attr('style', 'display:none;');

		$.ajax({
		   type: "POST",
		   url: "<?php echo site_url('article/rotate/'.$this->uri->segment(3).'?folder='.$folder.'&rotate=l&file='.$item_list[0]->file_name) ?>",
		   //url: "<?php echo site_url('gallery/rotate') ?>",
		   //data: "gallery_id : <?php //echo $gallery_id?>, rotate : 'l', folder : '<?php echo $folder?>', file : '<?php //echo $item_list[0]->file_name ?>' ",
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
		   url: "<?php echo site_url('article/rotate/'.$this->uri->segment(3).'?folder='.$folder.'&rotate=r&file='.$item_list[0]->file_name ) ?>",
		   //url: "<?php echo site_url('gallery/rotate') ?>",
		   //data: "gallery_id : <?php //echo $gallery_id?>, rotate : 'r', folder : '<?php echo $folder?>', file : '<?php //echo $item_list[0]->file_name ?>' ",
		   success: function(msg){
	     		$('#save_results').html(msg);
				//alert(msg);
				window.location='<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>';
	   		}
	 	});
	});
<?php
			//list($cur_url) = explode("?", "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			list($cur_url) = explode("?", $_SERVER['REQUEST_URI']);
			$cur_url .= $cur_url."?article_id=".$article_id;
?>
	/*$('#scale').click(function(){
				var v = $(this).val();
				//alert(v);
				window.location='<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&scale=' + v;
	});

	$('#scale2').click(function(){
				var v = $(this).val();
				//alert(v);
				window.location='<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&scale=' + v;
	});*/

	$('.setscale').click(function(){
				var v = $(this).val();
				//alert(v);
				window.location='<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&scale=' + v;
				//window.location='<?php echo base_url($cur_url) ?>&scale=' + v;

	});

	$('#delete-hl').click(function(){

			var v = $(this).attr('data-value');
			//alert(v);
			$.ajax({
				   type: "POST",
				   url: "<?php echo site_url('article/remove_pic') ?>",
				   data: {src: v},
				   success: function(msg){

					  //alert(msg);
						$('#a_highlight').attr('style', 'display:none;');
						$('#delete-hl').attr('style', 'display:none;');
						alert('Remove Picture success.');

					  /*if(msg == "Yes"){
							$('#a_highlight').attr('style', 'display:none');
							//$(this).attr('style', 'display:none');
							alert('Remove Picture success.');
					   }else
							alert('no Picture.');*/

					}

			});

	});

});
</script>