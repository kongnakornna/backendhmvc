<?php 
		$language = $this->lang->language;
?>
		<div class="row">
					<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->
							<div class="row">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open_multipart('brand/save', $attributes);
?>
				<div class="page-header">
							<h1>
									<?php echo $language['brand'] ?>
									<small>
										<i class="ace-icon fa fa-angle-double-right"></i>
										<?php echo $language['add'] ?>
									</small>
							</h1>
				</div>
				<div class="col-xs-12">
<?php
			if(function_exists('Debug')){
					//Debug($this->db) ;
					//Debug($news);
					//Debug($news_type);
			}
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
								<div class="form-group">
										<label for="channel_icon" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
										<div class="col-sm-9">
											<div class="col-xs-12">
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
													<input type="file" id="upload_logo" name="logo" />
													<code><i class="menu-icon fa fa-info"></i> ภาพขนาดที่ดีที่สุด 200x256 px เท่านั้น</code>
											</div>
										</div>
								</div>

								<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['brand']?></label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['brand']?>" id="brand_name" name="brand_name">
										</div>
								</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
										<div class="col-xs-3">
												<label>
													<input name="status" id="brand_status" class="ace ace-switch ace-switch-4" type="checkbox" value=1 />
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

				</div>
						<?php echo form_close();?>
						</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );

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

<script src="<?php echo base_url()?>/theme/assets/ckeditor/editor.js" language="javascript" type="text/javascript" ></script>
