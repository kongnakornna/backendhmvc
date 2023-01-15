<?php 
		$language = $this->lang->language; 
		$i=0;
		$now = date('Y-m-d h:i:s');
		
		//$typecrop = 1; //สี่เหลี่ยมจตุรัส
		//$typecrop = 2; //สี่เหลี่ยมผืนผ้าแนวนอน
		//$typecrop = 3; //สี่เหลี่ยมผืนผ้าแนวตั้ง

		if(!isset($orientation)) $orientation = 1;

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

		$folder_thumb = './uploads/thumb/';
		if(!is_dir($folder_thumb)) mkdir($folder_thumb, 0777);

		$folder_thumb = './uploads/thumb/dara/';
		if(!is_dir($folder_thumb)) mkdir($folder_thumb, 0777);

		$folder_dara = 'dara/';

		//Debug($dara_list);
		//die();

		$dara_id = $dara_list['dara_profile_id'];
		$nick_name = $dara_list['nick_name'];
		$folder = './uploads/dara';
		$avatar = $dara_list['avatar'];

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

#cropbox{max-width:800px;}

</style>

<div class="row">
		<div class="col-xs-12">
			<div class="page-header">
					<h1>
							<?php echo $language['dara'] ?>
							<small>
							<i class="ace-icon fa fa-angle-double-right"></i>
							<?php echo $language['edit'] ?>&nbsp;
							<?php echo $language['picture'] ?>
							</small>
					</h1>
			</div>


			<button class="btn btn-danger btn-sm btn-primary" onclick="window.location='<?php echo site_url('dara/edit/'.$dara_id) ?>';">
				<i class="bace-icon fa fa-reply icon-only igger-125"></i><?php echo $language['backto'].' '.$language['picture']  ?>
			</button>

			<button class="btn  btn-sm btn-primary" onclick="window.location='<?php echo site_url('dara/picture_edit/'.$this->uri->segment(3).'?dara_id='.$dara_id.'&Orientation=2') ?>';">
				<i class="bace-icon fa fa-edit icon-only igger-125"></i><?php echo $language['orientation'].' '.$language['picture']  ?>
			</button>
			<div id="msgshow"></div>
		</div>
<?php
			if(isset($error)){
?>
											<div class="alert alert-danger">
												<button data-dismiss="alert" class="close" type="button">
													<i class="ace-icon fa fa-times"></i>
												</button>
												<strong>
													<i class="ace-icon fa fa-times"></i>Oh snap!</strong><?php echo $error?>.
												<br>
											</div>
<?php
			}
?>
		<div class="col-xs-12">
<?php

		$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
		echo form_open('dara/picture_watermark', $attributes);
					
					$status = ($dara_list['status'] == 1) ? '<span class="label label-success arrowed">Enable</span>' : '<span class="label label-danger">Disable</span>';

					$preview_file = $folder.'/'.$avatar;

					$file_name = ($avatar == '') ? $folder.'/'.$avatar : $folder_thumb.$avatar;
					//Debug($file_name);
								
					if($avatar != ''){

							//$file_name = 'uploads/dara/'.$folder.'/'.$avatar;
							$file_name = $folder.'/'.$avatar;
							if(!file_exists($file_name)){
									$file_name = $folder.'/'.$avatar;
							}

							//echo "file_name = $file_name";
					}else $file_name = 'theme/assets/images/gallery/no-img.jpg';								
							$pic_del = base_url('dara/picture_del/'.$dara_id)."?".$this->uri->segment(3);
?>
<div class="page-header">
<!-- <ul class="breadcrumb first">
  <li class="active">Live (Crop Image)</li>
</ul> -->
<!-- <h1>(Crop Image)</h1> -->
</div>

<!-- <div class="col-lg-6">
		<label for="form-field-username">Caption</label>
		<input type="text"  name="sutar" value="<?php //echo $caption = $picture_list[0]['caption'];?>">
</div> -->

<div class="col-lg-6">

		<!-- <a href="<?php //echo site_url('dara/picture_watermark').'/'.$this->uri->segment(3).'?dara_id='.$dara_id.'&folder='.$folder.'&file='.$dara_list['file_name'] ?>">
			<button class="btn btn-success btn-sm btn-primary">
				<i class="bace-icon fa fa-edit icon-only igger-125"></i><?php //echo $language['watermark'].' '.$language['center'] ?>
			</button>
		</a>

		<a href="<?php //echo site_url('dara/picture_watermark1').'/'.$this->uri->segment(3).'?dara_id='.$dara_id.'&folder='.$folder.'&file='.$dara_list['file_name'] ?>">
			<button class="btn btn-success btn-sm btn-primary">
				<i class="bace-icon fa fa-edit icon-only igger-125"></i><?php //echo $language['watermark'].' '.$language['horizontal']  ?>
			</button>
		</a>

		<a href="<?php //echo site_url('dara/picture_watermark2').'/'.$this->uri->segment(3).'?dara_id='.$dara_id.'&folder='.$folder.'&file='.$dara_list['file_name'] ?>">
			<button class="btn btn-success btn-sm btn-primary">
				<i class="bace-icon fa fa-edit icon-only igger-125"></i><?php //echo $language['watermark'].' '.$language['vertical']  ?>
			</button>
		</a> -->

		<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right"><?php echo $language['watermark']  ?></label>

				<div class="col-sm-9">
						<label class="inline">
								<input name="watermark" type="radio" class="ace" value="center">
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
		<input type="hidden" name="dara_id" value="<?php echo $dara_id?>">
		<input type="hidden" name="folder" value="<?php echo $folder?>">
		<input type="hidden" name="file" value="<?php echo $avatar?>">
</div>
<div class="col-lg-6">
		<div id="controls">
			<a href="javascript:void(0);"><img src="<?php echo site_url('images/clockwise.png')?>" id="button_rotateL"></a> 
			<a href="javascript:void(0);"><img src="<?php echo site_url('images/anticlockwise.png')?>" id="button_rotateR"></a> 
		</div>
</div>

<div class="col-lg-12">
		<div id="save_results">
			<img src="<?php echo base_url().$file_name?>?<?=$now?>" data-src="<?php echo $avatar?>" data-folder="<?php echo $folder_dara?>" id="cropbox" />
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
		   url: "<?php echo site_url('dara/rotate/'.$this->uri->segment(3).'?folder='.$folder.'&rotate=l&file='.$avatar) ?>",
		   //url: "<?php echo site_url('gallery/rotate') ?>",
		   //data: "gallery_id : <?php //echo $gallery_id?>, rotate : 'l', folder : '<?php echo $folder?>', file : '<?php //echo $dara_list['file_name']?>' ",
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
		   url: "<?php echo site_url('dara/rotate/'.$this->uri->segment(3).'?folder='.$folder.'&rotate=r&file='.$avatar) ?>",
		   //url: "<?php echo site_url('gallery/rotate') ?>",
		   //data: "gallery_id : <?php //echo $gallery_id?>, rotate : 'r', folder : '<?php echo $folder?>', file : '<?php //echo $dara_list['file_name']?>' ",
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

						//if(rsponse == 'OK') $("#thumbs").html('Crop image success.');
	
						//alert(rsponse);						
						//$("#cropimage").hide();

						//$("#msgshow").html("Crop Success.");
						$("#msgshow").html(rsponse);
						$("#msgshow").attr("style", "font-size: 14px;border-radius: 0;background-color: #f2dede;border-color: #ebccd1;color: #a94442;");
						
					    //$("#thumbs").html("");
						//$("#thumbs").html("<img src='<?php echo base_url()?>uploads/thumb/"+rsponse+"' />");
						//$("#thumbs").attr("style", "width:100px;height:100px;margin-bottom: 10px;");
					}
			});	
	}
	
  });


</script>