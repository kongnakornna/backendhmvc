<?php 
		$language = $this->lang->language; 
		//$maxcat = count($dara_type);
		$backto = 'article';
		$ref_type = $this->input->get('ref_type');
		switch($ref_type){
				case 1: $backto = 'article'; break;
				/*case 2: $backto = 'column'; break;
				case 3: $backto = 'gallery'; break;
				case 4: $backto = 'clip'; break;*/
		}
?>
<div class="col-xs-12">

				<h3 class="header smaller lighter blue">Elvis</h3>

				<button class="btn btn-danger btn-sm btn-primary" onclick="window.location='<?php echo site_url($backto.'/gallery/'.$ref_id) ?>';">
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
	//echo $ref_id;
	
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

	<div class="page-header">
			<ul class="breadcrumb first">
			  <li class="active">Download Success.</li>
			</ul>
	</div>
	<!-- This is the image we're attaching Jcrop to -->
	<img src="<?php echo $upload_path."/".$elvis_view?>" data-src="<?php echo $elvis_view?>" data-folder="<?php echo $folder?>" id="cropbox" style='max-width:800px' />
	</div><!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->

