<?php 
	$language = $this->lang->language;
	if($this->input->get('error')) $error = $this->input->get('error');
?>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<div class="row">
									<!-- <form role="form" class="form-horizontal" action="category/save"> -->
<?php
	$attributes = array('class' => 'form-horizontal', 'name' => 'CatForm', 'id' => 'CatForm', 'onsubmit' => 'chkform();return false;');
	echo form_open_multipart('category/save', $attributes);
?>
									<div class="page-header">
										<h1>
											<?php echo $language['category'] ?>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['type_of_column'] ?>
											</small>
										</h1>
									</div>

									<div class="col-xs-12">
										<!-- <h3 class="header smaller lighter blue"><?php echo $language['category'] ?></h3> -->
										<!-- <div class="table-header">
											<?php echo $language['category'] ?>
										</div> -->

				<!-- <code><?php //echo $sql; ?></code> -->
<?php
				if(function_exists('Debug')){
					//Debug($this->db) ;
					//Debug($category );
					//Debug($this->lang->language);
				}
?>
<?php
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
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['category_name']?>(EN)</label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="Category" id="category_en" name="category_en">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['category_name']?>(TH)</label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="Category" id="category_th" name="category_th">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Title</label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="title" id="title" name="title" value="" maxlength="140">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Description</label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="description" id="description" name="description" value="" maxlength="255">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Keyword</label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="keyword" id="keyword" name="keyword" value="" maxlength="255">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Stat Truehits</label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="Stat Truehits" id="stat_truehits" name="stat_truehits" value="" maxlength="150">
										</div>
									</div>

									<div class="form-group">
											<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?> extra small 480x63</label>
											<div class="col-sm-9">
												<div class="col-xs-12">
														<input type="file" name="background_extra_small" class="file_upload" />
												</div>
											</div>
									</div>

									<div class="form-group">
											<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?> small 768x100</label>
											<div class="col-sm-9">
												<div class="col-xs-12">
														<input type="file" name="background_small" class="file_upload" />
												</div>
											</div>
									</div>

									<div class="form-group">
											<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?> medium 1280x100</label>
											<div class="col-sm-9">
												<div class="col-xs-12">
														<input type="file" name="background_medium" class="file_upload" />
												</div>
											</div>
									</div>

									<div class="form-group">
											<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?> large 1920x100</label>
											<div class="col-sm-9">
												<div class="col-xs-12">
														<input type="file" name="background_large" class="file_upload" />
												</div>
											</div>
									</div>

									<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>

										<div class="col-xs-3">
													<label>
														<input name="status"  class="ace ace-switch ace-switch-4 btn-empty" type="checkbox"  value=1 checked>
														<span class="lbl"></span>
													</label>
												</div>
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

		$('#enable9').click(function( ) {
				alert($(this).attr('id'));
				/*$.ajax({
						url: "http://search.twitter.com/search.json",
						data: {
						q: query
						},
						dataType: "jsonp",
						success: defer.resolve,
						error: defer.reject
				});*/
		});

		$('#id-input-file-1 , #id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
		});

		$('.file_upload').ace_file_input({
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
						alert(error_code);
					}
			
		}).on('change', function(){
					//console.log($(this).data('ace_input_files'));
					//console.log($(this).data('ace_input_method'));
		});

});
function chkform(){
		document.CatForm.submit();
}
</script>