<?php 
		$language = $this->lang->language; 
		//$maxcat = count($dara_type);
?>
<div class="col-xs-12">

				<h3 class="header smaller lighter blue">Elvis</h3>

				<button class="btn btn-danger btn-sm btn-primary" onclick="window.location='<?php echo site_url('news/picture/'.$news_id) ?>';">
					<i class="bace-icon fa fa-reply icon-only igger-125"></i><?php echo $language['backto'].' '.$language['picture']  ?>
				</button>
<?php
	//$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	//echo form_open('elvis', $attributes);
?><!-- onclick="window.location='<?php echo site_url('elvis/add') ?>';" -->

			<!-- <button class="btn btn-sm btn-primary" >
					<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php //echo $language['search'].' '.$language['elvis']  ?>
			</button> -->

<?php
	if($folder == '') $folder = date('Ymd');
	$upload_path = '../../uploads/tmp/'.$folder;
	//echo form_close();
	//echo $news_id;
	
	/*if(!$elvis_list){
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
	}*/
?>
								<!-- PAGE CONTENT BEGINS -->
								<div>
<?php
				//Debug($elvis_view);
?>
										<!-- <div style="margin:0 auto; width:600px">
												<img id="photo" src="<?php echo $upload_path."/".$elvis_view?>" style='max-width:600px' >
												<div id="thumbs" style="padding:5px; max-width:600px"></div>
												<div style="width:600px">
													<form id="cropimage" method="post" enctype="multipart/form-data">
														Upload your image <input type="file" name="photoimg" id="photoimg" />
														<input type="hidden" name="image_name" id="image_name" value="<?php echo($elvis_view)?>" />
														<input type="submit" name="submit" value="Submit" />
													</form>
												</div>
										</div> -->


<div class="container">
<div class="row">
<div class="span12">
<div class="jc-demo-box">

<div class="page-header">
<!-- <ul class="breadcrumb first">
  <li class="active">Live (Crop Image)</li>
</ul> -->
<h1>(Crop Image)</h1>
</div>

		<!-- This is the image we're attaching Jcrop to -->
		<img src="<?php echo $upload_path."/".$elvis_view?>" data-src="<?php echo $elvis_view?>" data-folder="<?php echo $folder?>" id="cropbox" style='max-width:800px' />

		<!-- This is the form that our event handler fills -->
		<form action="" method="post" onsubmit="return checkCoords();">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<!-- <input type="button" value="Crop Image" class="btn btn-large btn-inverse" /> -->
		</form>

		<div id="thumbs"></div>

	</div>
	</div>
	</div>
	</div>

								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->


  <script src="<?=base_url('theme/assets/jcrop')?>/js/jquery.min.js"></script>
  <script src="<?=base_url('theme/assets/jcrop')?>/js/jquery.Jcrop.js"></script>

  <link rel="stylesheet" href="<?=base_url('theme/assets/jcrop')?>/main.css" type="text/css" />
  <link rel="stylesheet" href="<?=base_url('theme/assets/jcrop')?>/demos.css" type="text/css" />
  <link rel="stylesheet" href="<?=base_url('theme/assets/jcrop')?>/css/jquery.Jcrop.css" type="text/css" />

<script type="text/javascript">

  $(function(){
		$('#cropbox').Jcrop({
			aspectRatio: 2,
			onSelect: getSizes
		});
  });

  function updateCoords(c){
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
  };

  function checkCoords(){
    if (parseInt($('#w').val())) return true;
    alert('Please select a crop region then press submit.');
    return false;
  };

	function getSizes(obj)	{

		updateCoords(obj);

		var x_axis = obj.x;
		var w_axis = obj.w;
		var y_axis = obj.y;
		var h_axis = obj.h;

		var thumb_width = w_axis;
		var thumb_height = h_axis;

		if(thumb_width > 0){
				if(confirm("Do you want to save image..!")){

						$.ajax({
							type:"GET",
							url:"<?php echo base_url()?>picture/crop_img?t=ajax&img="+$("#cropbox").attr('data-src')+"&w="+thumb_width+"&h="+thumb_height+"&x1="+x_axis+"&y1="+y_axis+"&folder="+$("#cropbox").attr('data-folder'),
							cache:false,
							success:function(rsponse){
									//alert(rsponse);
									//$("#cropimage").hide();
								    $("#thumbs").html("");
									$("#thumbs").html("<img src='<?php echo base_url()?>uploads/thumb/"+rsponse+"' />");
									$("#thumbs").attr("style", "width:100px;height:100px;margin-bottom: 10px;");
								}
						});
					}
			}
		else
			alert("Please select portion..!");
	}
</script>
