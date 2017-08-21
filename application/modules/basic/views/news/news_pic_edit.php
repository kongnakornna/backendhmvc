<?php 
		$language = $this->lang->language; 
		$i=0;
		
		//$typecrop = 1; //สี่เหลี่ยมจตุรัส
		//$typecrop = 2; //สี่เหลี่ยมผืนผ้าแนวนอน
		//$typecrop = 3; //สี่เหลี่ยมผืนผ้าแนวตั้ง

		if($orientation == 1){
			//$typecrop = '4/3';
			//$typecrop = 'xsize / ysize';
			$typecrop = '16/9';

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
<style type="text/css">

/* Apply these styles only when #preview-pane has
   been placed within the Jcrop widget */
.jcrop-holder #preview-pane {
  display: block;
  position: absolute;
  z-index: 2000;
  top: 10px;
  right: -280px;
  padding: 6px;
  border: 1px rgba(0,0,0,.4) solid;
  background-color: white;

  -webkit-border-radius: 6px;
  -moz-border-radius: 6px;
  border-radius: 6px;

  -webkit-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
  box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
}

#preview-pane .preview-container {
  width: 250px;
  height: 170px;
  overflow: hidden;
}

</style>

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

			<button class="btn  btn-sm btn-primary" onclick="window.location='<?php echo site_url('news/picture_edit/'.$this->uri->segment(3).'?news_id='.$news_id.'&Orientation=2') ?>';">
				<i class="bace-icon fa fa-edit icon-only igger-125"></i><?php echo $language['orientation'].' '.$language['picture']  ?>
			</button>
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
							$file_name = 'uploads/news/'.$folder.'/'.$file_org;
							if(!file_exists($file_name)){
									$file_name = 'uploads/tmp/'.$folder.'/'.$file_org;
							}
					}else $file_name = 'theme/assets/images/gallery/no-img.jpg';								
							$pic_del = base_url('news/picture_del/'.$picture_id)."?".$this->uri->segment(3);
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
		<div id="save_results">
			<img src="<?php echo base_url().$file_name?>?<?=$now?>" data-src="<?php echo $file_org?>" data-folder="<?php echo $folder?>" id="cropbox" style="max-width:800px;" />
		</div>
		<form action="" method="post" onsubmit="return checkCoords();">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<!-- <input type="button" value="Crop Image" class="btn btn-large btn-inverse" /> -->
		</form>
		<div id="thumbs"></div>
</div>

<div style="clear:both;"></div>
<div id="preview-pane">
			<div class="preview-container">
				<img src="<?php echo base_url().$file_name?>" class="jcrop-preview" alt="Preview" />
			</div>
</div>
	
<?php echo form_close();?>
</div>
</div>

<script src="<?=base_url('theme/assets/jcrop')?>/js/jquery.min.js"></script>
<script src="<?=base_url('theme/assets/jcrop')?>/js/jquery.Jcrop.js"></script>
<link rel="stylesheet" href="<?=base_url('theme/assets/jcrop')?>/css/jquery.Jcrop.css" type="text/css" />
<script type="text/javascript" src="<?=base_url()?>/js/jQueryRotate.js"></script>
<script>
$(document).ready(function(){

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
	   		}
	 	});
	});
});

function updateAngle(image,direction,currentAngle)
{
	var rotateAngle = 0;
		
	switch (direction){
		case 'anticlockwise':
			if(currentAngle == 0){rotateAngle = -90;}
			else{rotateAngle = parseInt(currentAngle) - 90;}
		break;
		default:
			if(currentAngle == 0){rotateAngle = 90;}
			else{rotateAngle = parseInt(currentAngle) + 90;}
	}
		
	$('#currentAngle').val(rotateAngle); // sets currentAngle value
}

</script>

<script type="text/javascript">
  jQuery(function($){

    // Create variables (in this scope) to hold the API and image size
    var jcrop_api,
        boundx,
        boundy,

        // Grab some information about the preview pane
        $preview = $('#preview-pane'),
        $pcnt = $('#preview-pane .preview-container'),
        $pimg = $('#preview-pane .preview-container img'),

        xsize = $pcnt.width(),
        ysize = $pcnt.height();
        //xsize = 300,
        //ysize = 169;
    
    console.log('init',[xsize,ysize]);
    $('#cropbox').Jcrop({
	      onChange: updatePreview,
	      onSelect: updatePreview,
	      aspectRatio: <?php echo $typecrop ?>
    },function(){
	      // Use the API to get the real image size
	      var bounds = this.getBounds();
	      boundx = bounds[0];
	      boundy = bounds[1];
	      // Store the API in the jcrop_api variable
	      jcrop_api = this;
	
	      // Move the preview into the jcrop container for css positioning
	      $preview.appendTo(jcrop_api.ui.holder);
    });

    function updatePreview(c){
	      if (parseInt(c.w) > 0){
		        var rx = xsize / c.w;
		        var ry = ysize / c.h;
		
		        $pimg.css({
			          width: Math.round(rx * boundx) + 'px',
			          height: Math.round(ry * boundy) + 'px',
			          marginLeft: '-' + Math.round(rx * c.x) + 'px',
			          marginTop: '-' + Math.round(ry * c.y) + 'px'
		        });

		        //alert(c);
		        CropImg(c);		        
		        CropImg2(c);		        
	      }
    };
    
	function CropImg(obj){
		
			var x_axis = obj.x;
			var w_axis = obj.w;
			var y_axis = obj.y;
			var h_axis = obj.h;
	
			var thumb_width = w_axis;
			var thumb_height = h_axis;

			//$("#thumbs").html("<?php echo base_url()?>picture/crop_img?t=ajax&img="+$("#cropbox").attr('data-src')+"&w="+thumb_width+"&h="+thumb_height+"&x1="+x_axis+"&y1="+y_axis+"&folder="+$("#cropbox").attr('data-folder'));		
			
			$.ajax({
				
				type:"GET",
				url:"<?php echo base_url()?>picture/crop_img?t=ajax&img="+$("#cropbox").attr('data-src')+"&w="+thumb_width+"&h="+thumb_height+"&x1="+x_axis+"&y1="+y_axis+"&folder="+$("#cropbox").attr('data-folder'),
				cache:false,
				success:function(rsponse){

						if(rsponse == 'OK') $("#thumbs").html('Crop image success.');
	
						//alert(rsponse);						
						//$("#cropimage").hide();
						
					    //$("#thumbs").html("");
						//$("#thumbs").html("<img src='<?php echo base_url()?>uploads/thumb/"+rsponse+"' />");
						//$("#thumbs").attr("style", "width:100px;height:100px;margin-bottom: 10px;");
					}
			});	
	}

    
	function CropImg2(obj){
		
			var x_axis = obj.x;
			var w_axis = obj.w;
			var y_axis = obj.y;
			var h_axis = obj.h;
	
			var thumb_width = w_axis;
			var thumb_height = h_axis;
			
			$.ajax({
				type:"GET",
				url:"<?php echo base_url()?>picture/make_thumb2?t=ajax&img="+$("#cropbox").attr('data-src')+"&w="+thumb_width+"&h="+thumb_height+"&x1="+x_axis+"&y1="+y_axis+"&folder="+$("#cropbox").attr('data-folder'),
				cache:false,
				success:function(rsponse){
						if(rsponse == 'OK') $("#thumbs").html('Crop image success.');
				}
			});	
	}
	
  });


</script>