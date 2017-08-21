<?php 
		$language = $this->lang->language;
		$display_en = "style='display:block;'";
?>
<style type="text/css">
#countitle, #countitle2 {style="margin-left: 4px; color: rgb(83, 95, 222);"}
#nav-search{display:none;}
</style>

<div class="row">
		<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<div class="row">
							<!-- <form role="form" class="form-horizontal" action="category/save"> -->
<?php
	$attributes = array('class' => 'form-horizontal', 'name' => 'ColumnForm', 'onsubmit' => 'chkform();return false;');
	echo form_open_multipart('zodiac/save', $attributes);
?>
									<div class="page-header">
										<h1>
											<?php echo $language['zodiac'] ?>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['add'] ?>
											</small>
										</h1>
									</div>

									<div class="col-xs-12">
<?php
			if(function_exists('Debug')){
					//Debug($tags_list) ;
					//Debug($column);
					//Debug($columnist_list);
			}

			$now_date = date('Y-m-d');
			if(isset($error)){
?>
					<div class="alert alert-danger">
							<button data-dismiss="alert" class="close" type="button">
									<i class="ace-icon fa fa-times"></i>
							</button>
							<strong>
									<i class="ace-icon fa fa-times"></i>
							</strong><?php echo $error?>.
							<br>
					</div>
<?php
			}
			echo '<input type="hidden" name="zd_id" value=0>';
?>
			<!-- #section:elements.form -->
			<!-- <div class="form-group">
						<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
								<div class="col-sm-9">
											<div class="col-xs-12">
													<input type="file" id="picture" name="picture" />
													<code>กว้างไม่เกิน 600 Px ใช้ได้เฉพาะ .jpg</code>
											</div>
								</div>
			</div> -->

			<div class="form-group zodiac" id="zodiac">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['zodiac']?></label>
					<div class="col-sm-9">
							<?php echo $zodiac_list?>
					</div>
			</div>

			<div class="form-group zodiac" id="zodiac_date">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['zodiac']?> <?php echo $language['date'] ?></label>
					<div class="col-sm-9">
							<?php //echo $zodiac_date?>
							<div class="input-group">
										<input type="text" id="zodiac_date" class="input-sm form-control date-picker" name="zodiac_date" data-date-format="dd-mm-yyyy" readonly />
										<code><i class="menu-icon fa fa-info"></i> Date Format DD-MM-YYYY</code>
							</div>
					</div>
			</div>

									<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (EN) </label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="title_en" name="title_en" maxlength="140">
											<div id="countitle"></div>
											<code><i class="menu-icon fa fa-info"></i> ไม่เกิน 140 ตัวอักษร</code>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (TH) </label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="title_th" name="title_th" maxlength="140">
											<div id="countitle2"></div>
											<code><i class="menu-icon fa fa-info"></i> ไม่เกิน 140 ตัวอักษร</code>
										</div>
									</div>

								<!-- EDITOR 1 -->									
								<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['shorttitle']?> (EN) 
										</label>
										<div class="col-sm-9">

												<div id="fullToolbar">											
													<textarea  id="description_en" name="description_en" style="width:100%;height:150px"></textarea>
													<?php //echo display_ckeditor($description_en); ?>
												</div>
										</div>
								</div>

								<!-- EDITOR 2 -->									
								<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['shorttitle']?> (TH) 
										</label>
										<div class="col-sm-9">
												<div id="fullToolbar">
													<textarea id="description_th" name="description_th" style="width:100%;height:150px"></textarea>
													<?php //echo display_ckeditor($description_th); ?>
												</div>
												<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="<?php echo $language['meta_description_seodoctor']?>" title="" data-original-title="<?php echo $language['meta_description']?>">?</span>
										</div>
								</div>

								<!-- EDITOR 3 -->
								<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['detail']?> (EN) </label>
										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea cols="80" id="detail_en" name="detail_en" rows="10"></textarea>
													<?php echo display_ckeditor($detail_en); ?>
												</div>
										</div>
								</div>

								<!-- EDITOR 4 -->
								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['detail']?> (TH) </label>
										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea cols="80" id="detail_th" name="detail_th" rows="10"></textarea>
													<?php echo display_ckeditor($detail_th); ?>
												</div>
										</div>
								</div>

	
			<div class="form-group">
						<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['credit']?></label>
						<div class="col-sm-9">
									<?php echo $credit_list?>
						</div>
			</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Tags</label>
											<a href="<?php echo site_url('tags/add'); ?>" target=_blank>
											<i class="ace-icon glyphicon glyphicon-plus"></i>
											<?php echo $language['add'].' '.$language['tags']?></a>
										<div class="col-sm-9">
											<!-- <select multiple="" class="chosen-select" id="form-field-select-5" data-placeholder="Choose a State...">
														<option value="test">test</option>
														<option value="test2">test2</option>
														<option value="test3">test3</option>
											</select> -->
											<?php echo $tags_list?>
												<span class="middle">
													<code>* <?php echo $language['require']?> ควรใส่ 3 - 5 tag</code><br>
												</span>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> </label>
										<div class="col-sm-9">
												<button type="button" class="btn btn-sm btn-info chk_tags">
													Check Tags
												</button>
										</div>
									</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>

										<div class="col-xs-3">
													<label>
														<input name="status" id="column_status" class="ace ace-switch ace-switch-6" type="checkbox" value=1 />
														<span class="lbl"></span>
													</label>
												</div>
										</div>
								</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['can_comment']?></label>
										<div class="col-xs-3">
													<label>
														<input name="can_comment" id="can_comment" class="ace ace-switch ace-switch-6" type="checkbox" value=1 />
														<span class="lbl"></span>
													</label>
										</div>
								</div>

									<div style="clear: both;"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn btn-info" id="form_submit">
												<i class="ace-icon fa fa-floppy-o bigger-110"></i>
												<?php echo $language['save']?>
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
		
		//$('.zodiac').css('display', 'none');
		$('#title_en').on('keypress', function(e){
				var len = $(this).val().length;
				$('#countitle').html(len);
		});

		$('#title_th').on('keypress', function(e){
				var len = $(this).val().length;
				$('#countitle2').html(len);
		});

		/*$('#category_id').change(function( ) {
				var catid = $(this).val();
				$('#subcategory_id').load('<?php echo base_url() ?>subcategory/list_dd/' + catid);
		});

		$('#subcategory_id').change(function( ) {
				var subcatid = $(this).val();
				//if(subcatid == 39) $('#zodiac').css('display', 'block');
				if(subcatid == 39) $('.zodiac').css('display', 'block');
		});*/

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

		$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true
		})

		$('#picture').ace_file_input({
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
						//alert(error_code);
					}
			
		}).on('change', function(){
					//console.log($(this).data('ace_input_files'));
					//console.log($(this).data('ace_input_method'));
		});

		$('.chosen-select').chosen({allow_single_deselect:true}); 
				//resize the chosen on window resize
				$(window)
				.off('resize.chosen')
				.on('resize.chosen', function() {
					$('.chosen-select').each(function() {
						 var $this = $(this);
						 $this.next().css({'width': $this.parent().width()});
					})
		}).trigger('resize.chosen');
			
		$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
					 else $('#form-field-select-4').removeClass('tag-input-style');
		});

		//chosen plugin inside a modal will have a zero width because the select element is originally hidden
		//and its width cannot be determined.
		//so we set the width after modal is show
		$('#modal-form').on('shown.bs.modal', function () {
					$(this).find('.chosen-container').each(function(){
						$(this).find('a:first-child').css('width' , '210px');
						$(this).find('.chosen-drop').css('width' , '210px');
						$(this).find('.chosen-search input').css('width' , '200px');
					});
		})

		/*
		//or you can activate the chosen plugin after modal is shown
		//this way select element becomes visible with dimensions and chosen works as expected
		$('#modal-form').on('shown', function () {
					$(this).find('.modal-chosen').chosen();
		})*/
		$('.chk_tags').on('click', function(e){
				chkform();
		});
});

function chkform(){

		//var category_id = document.ColumnForm.category_id.value;

		//if(category_id > 0){

				if(document.getElementById("title_th").value == ''){
					alert('กรุณาใส่ <?php echo $language['title']?> ด้วยครับ');
					document.ColumnForm.title_th.focus();
					retrun false;
				}else if(document.getElementById("description_th").value == ''){
					alert('กรุณาใส่ <?php echo $language['shorttitle']?> ด้วยครับ');
					document.ColumnForm.description_th.focus();
					retrun false;
				}else if(document.getElementById("tag_id").value == ''){
					alert('กรุณาใส่ Tags ด้วยครับ');
					document.getElementById("tag_id").focus();	
					retrun false;
				}else{
					$('#form_submit').attr('disabled', 'disabled');
					document.ColumnForm.submit();
				}
				//document.getElementById("jform").submit();
		/*}else{
			alert('<?php echo $language['please_select_cat']?>');
					document.getElementById("category_id").focus();	
		}*/
		
}
</script>
