<?php $language = $this->lang->language; ?>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<div class="row">
									<!-- <form role="form" class="form-horizontal" action="brand/save"> -->
<?php
		//$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
		//echo form_open('brand/save', $attributes);
		$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
		echo form_open_multipart('brand/save', $attributes);

?>
									<div class="page-header">
										<h1>
											<?php echo $language['brand'] ?>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['edit'] ?>
											</small>
										</h1>
									</div>

									<div class="col-xs-12">
										<!-- <h3 class="header smaller lighter blue"><?php echo $language['brand'] ?></h3> -->
										<!-- <div class="table-header">
											<?php echo $language['brand'] ?>
										</div> -->

				<!-- <code><?php //echo $sql; ?></code> -->
<?php
			if(function_exists('Debug')){
				//Debug($brand_arr);
			}

			$brand_id = $brand_arr[0]->brand_id;
			$brand_name = $brand_arr[0]->brand_name;
			$logo = $brand_arr[0]->logo;

			if(isset($error)){
?>
											<div class="alert alert-danger">
												<button data-dismiss="alert" class="close" type="button">
													<i class="ace-icon fa fa-times"></i>
												</button>
												<strong>
													<i class="ace-icon fa fa-times"></i>
													Oh snap!</strong><?php echo $error?>.
												<br>
											</div>
<?php
			}
?>
									<!-- #section:elements.form -->
<?php
		/*echo $logo;
		if(file_exists($logo)){
?>
			<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-tags"></label>
					<div class="col-sm-9">
							<div id='picture_use'>
									<span class="profile-picture">
											<img class="editable img-responsive" id="logo" src="<?php echo $logo ?>" />
									</span>
									<!-- <a class="red" href="javascript:void(0);" id="bootbox-confirm" title="Delete" data-value="<?php echo $channel_arr[0]['channel_id2'] ?>" data-img="<?php echo $channel_icon?>"><i class="ace-icon fa fa-trash-o bigger-130"></i></a> -->
							</div>
					</div>
			</div>
<?php
			}*/
			$file_logo = base_url('uploads/magazine/'.$logo);
?>		
			<div class="form-group">
					<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
					<div class="col-sm-9">
							<div class="col-xs-12">
							<?php
										if($logo != ''){
											//echo '<img src="'.$channel_icon.'" >';
							?>
													<div id='picture_use'>
															<span class="profile-picture">
																	<img class="editable img-responsive" id="logo" src="<?php echo $file_logo ?>" />
															</span>
															<a class="red" href="javascript:void(0);" id="bootbox-confirm" title="Delete" data-value="<?php echo $brand_id ?>" data-img="<?php echo $logo ?>">
															<i class="ace-icon fa fa-trash-o bigger-130"></i></a>
													</div>
							<?php
										}else{
							?>
							<!-- <div class="form-group">
									<div class="col-sm-9">												
											<div class="checkbox">
													<label>
															<input type="checkbox" class="ace" name="resize">
															<span class="lbl"> Resize</span>
													</label>
											</div>
									</div>
							</div> -->
							<?php } ?>
							<div id="upload_icon"><input type="file" id="upload_logo" name="logo" /></div>
							<code><i class="menu-icon fa fa-info"></i> ภาพขนาดที่ดีที่สุด 200x256 px เท่านั้น</code>
							</div>
				</div>
			</div>


									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['brand']?></label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['brand']?>" id="brand_name" name="brand_name" value="<?php echo $brand_name ?>">
										</div>
									</div>


									<div class="form-group">
											<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
											<div class="col-xs-3">
												<label>
														<!-- <input name="switch-field-1" class="ace ace-switch" type="checkbox"  name="status" <?php //if($brand_arr[0]->status == 1) echo 'value=1 checked'?>/> -->
														<input type="checkbox" name="status" id="cat_status" class="ace ace-switch ace-switch-4" value=1
														<?php if($brand_arr[0]->status == 1) echo ' checked'; else echo '' ?>>
														<span class="lbl"></span>
												</label>
											</div>
									</div>

									<div style="clear: both;"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn btn-info">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>

											&nbsp; &nbsp; &nbsp;
											<button type="reset" class="btn">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div>
									<input type="hidden" name="brand_id" value="<?php echo $brand_arr[0]->brand_id ?>">

								
							</div>
						<?php echo form_close();?>
						</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
<?php 
		if($logo != ''){ echo "$('#upload_icon').attr('style', 'display:none');\n";	} 
?>
		//Del Icon
		$('#bootbox-confirm').click(function( ) {
					var v = $(this).attr('data-value');
					var img = $(this).attr('data-img');

					bootbox.confirm("<?php echo $language['are you sure to delete'].$language['picture'] ?>  ", function(result) {
							if(result) {
										$.ajax({
												type: 'POST',
												url: "<?php echo base_url() ?>brand/remove_img/" + v,
												data : { img : img, v : v},
												cache: false,
												success: function(data){
														//alert(data);
														if(data = 'Yes'){
																$('#picture_use').attr('style', 'display:none');
																$('#upload_icon').attr('style', 'display:block');
														}
												}
										});
							}
					});
		}); 

		$('#upload_logo').ace_file_input({
					style:'well',
					btn_choose:'<?php echo $language['upload_file'] ?>',
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
});
</script>
