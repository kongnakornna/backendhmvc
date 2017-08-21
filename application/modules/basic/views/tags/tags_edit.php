<?php $language = $this->lang->language; ?>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<div class="row">
									<!-- <form role="form" class="form-horizontal" action="tags/save"> -->
<?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
		echo form_open('tags/save', $attributes);
?>
									<div class="page-header">
										<h1>
											<?php echo $language['tags'] ?>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['edit'] ?>
											</small>
										</h1>
									</div>

									<div class="col-xs-12">
										<!-- <h3 class="header smaller lighter blue"><?php echo $language['tags'] ?></h3> -->
										<!-- <div class="table-header">
											<?php echo $language['tags'] ?>
										</div> -->

				<!-- <code><?php //echo $sql; ?></code> -->
<?php
			if(function_exists('Debug')){
				//Debug($tags_arr);
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
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['tags']?></label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['tags']?>" id="tag_text" name="tag_text" value="<?php echo $tags_arr[0]['tag_text'] ?>">
										</div>
									</div>
<?php
		if(isset($tags_arr[0]['ref_type'])){
					
					//Debug(count($tags_arr));
					$display_tags = '';

					if($tags_arr[0]['ref_type'] == 5){ 
						$tags_this = "Tags dara";
						$display_tags = '<div class="col-sm-6">This tags is <a href="'.base_url('dara/edit/'.$tags_arr[0]['ref_id']).'" target=_blank>'.$tags_this.'</a></div>';
						//dara/edit/1404
					/*}else if($tags_arr[0]['ref_type'] == 1){ 
						$tags_this = "Tags news";
						$display_tags = '<div class="col-sm-6">This tags is <a href="'.base_url('news/edit/'.$tags_arr[0]['ref_id']).'" target=_blank>'.$tags_this.'</a></div>';
					}else if($tags_arr[0]['ref_type'] == 2){ 
						$tags_this = "Tags column";
						$display_tags = '<div class="col-sm-6">This tags is <a href="'.base_url('column/edit/'.$tags_arr[0]['ref_id']).'" target=_blank>'.$tags_this.'</a></div>';
					}else if($tags_arr[0]['ref_type'] == 3){ 
						$tags_this = "Tags gallery";
						$display_tags = '<div class="col-sm-6">This tags is <a href="'.base_url('gallery/edit/'.$tags_arr[0]['ref_id']).'" target=_blank>'.$tags_this.'</a></div>';
					}else if($tags_arr[0]['ref_type'] == 4){ 
						$tags_this = "Tags clip";
						$display_tags = '<div class="col-sm-6">This tags is <a href="'.base_url('vdo/edit/'.$tags_arr[0]['ref_id']).'" target=_blank>'.$tags_this.'</a></div>';*/
					}

?>
									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"></label>
<?php
		if(isset($tags_arr[0]['ref_type'])){	
				echo '<div class="col-sm-2">number of tags :'.count($tags_arr).'</div>';
				echo $display_tags ;
		}
?>
									</div>
<?php
		}
?>

									<div class="form-group">
											<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
											<div class="col-xs-3">
												<label>
														<!-- <input name="switch-field-1" class="ace ace-switch" type="checkbox"  name="status" <?php if($tags_arr[0]['status'] == 1) echo 'value=1 checked'?>/> -->
														<input type="checkbox" name="status" id="status" class="ace ace-switch ace-switch-4" value=1
														<?php if($tags_arr[0]['status'] == 1) echo ' checked'; else echo '' ?>>
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
									<input type="hidden" name="tag_id" value="<?php echo $tags_arr[0]['tag_id'] ?>">

								
							</div>
						<?php echo form_close();?>
						</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->
