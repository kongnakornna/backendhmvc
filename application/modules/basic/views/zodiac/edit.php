<?php 
	$language = $this->lang->language;
	$display_en = "style='display:none;'";

	if($this->input->get('success')) $success = $this->input->get('success');
	
	$count_column = count($zodiac_list);

	$zd_id = $zodiac_list[0]['zid'];
	$zd_id_th = $zodiac_list[1]['zid'];
	$zd_id2 = $zodiac_list[0]['zid2'];

	//echo "count_column = $count_column";
	//Debug($zodiac_list);

	$title = $zodiac_list[0]['title'];
	//$previewurl = $this->config->config['www']."/column/".$category_id."/".$subcategory_id."/".$column_id2."/?preview=1";
	$previewurl = '';
?>
<style type="text/css">
#countitle, #countitle2 {style="margin-left: 4px; color: rgb(83, 95, 222);"}
<?php 
	if($this->session->userdata('admin_id') != 1){
?>
#alertorder{display:none;}	
#nestable-output{display:none;}	
#nestable-output2{display:none;}	
<?php 
	}
?>
	.input-icon {width: 80%;}
	ul.ace-thumbnails > li {max-width: 250px;width: auto;height:auto;}
	li{list-style: none;}
	#picture_cover .ace-thumbnails > li img{width: 100%;}
	.sentmail{display:none;}
	#nav-search{display:none;}
</style>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'ColumnForm', 'name' => 'ColumnForm');
	echo form_open_multipart('zodiac/save', $attributes);
?>
				<div class="page-header">
						<h1>
								<?php echo $language['zodiac'] ?>
								<small>
										<i class="ace-icon fa fa-angle-double-right"></i>
										<?php echo $language['edit'] ?>
								</small>
						</h1>
				</div>

				<div style="clear: both;"></div>
						<div class="clearfix form-actions">
								<div class="col-md-offset-1 col-md-12 col-sm-12">

										<button type="button" class="btn btn-purple bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>">
											<i class="ace-icon fa fa-search-plus bigger-110" ></i> <?php echo $language['preview'] ?>
										</button>
<?php
		if($zodiac_list[0]['approve'] == 0){
				$approve = 0;
?>
								<button type="button" class="btn btn-success admin_approve">
										<i class="ace-icon fa fa-check bigger-110"></i> Approve
								</button>
<?php
		}else{
				$approve = 1;
?>
								<button type="button" class="btn" disabled>
										<i class="ace-icon fa fa-check bigger-110"></i> Approved
								</button>
<?php
		}
?>
								<button type="button" class="btn btn-info submit_zodiac">
										<i class="ace-icon fa fa-floppy-o bigger-110"></i> <?php echo $language['save']?>
								</button>

								<button type="reset" class="btn reset_zodiac" id="reset_zodiac1">
										<i class="ace-icon fa fa-undo bigger-110"></i>Reset
								</button>

								<button type="button" class="btn btn-success gen_json" <?php if($approve == 0) echo "disabled" ?>>
									<i class="ace-icon glyphicon glyphicon-file bigger-110"></i> <?php echo $language['generate_catch']?>
								</button>

<?php
		if($zodiac_list[0]['approve'] == 0){
?>
								<button type="button" class="btn btn-pink sentmail" id="sentmail">
										<i class="ace-icon fa fa-envelope bigger-110"></i> <?php echo $language['sentmail_manager']?>
								</button>
<?php
		}
?>
						</div>
					</div>

					<div class="col-xs-12">
<?php
		if(function_exists('Debug')){
			//Debug($zodiac_list);
		}

		//$news_highlight_id = $zodiac_list[0]['news_highlight_id'];
		//$megamenu_id = $zodiac_list[0]['megamenu_id'];

		$start_date = $expire_date = '';	
		list($create_date, $time) = explode(" ", $zodiac_list[0]['create_date']);
		$create_date = str_replace("-", "", $create_date);

		echo '<input type="hidden" name="zid" value="'.$zd_id.'">';

		if($zodiac_list[0]['lang'] == 'en'){
			echo '<input type="hidden" name="zid_en" value="'.$zd_id.'">';
			echo '<input type="hidden" name="zid_th" value="'.$zd_id_th.'">';
		}else{
			echo '<input type="hidden" name="zid_en" value="'.$zd_id2.'">';
			echo '<input type="hidden" name="zid_th" value="'.$zd_id_th.'">';
		}

		echo '<input type="hidden" name="folder" value="'.$create_date.'">';
		//Debug($zodiac_list);

		if(trim($zodiac_list[0]['start_date']) != '' && $zodiac_list[0]['start_date'] != '0000-00-00 00:00:00'){
			$start_date = DisplayDateRange($zodiac_list[0]['start_date']); 
		}else{
			$start_date = '';
		}
		if(trim($zodiac_list[0]['expire_date']) != '' && $zodiac_list[0]['expire_date'] != '0000-00-00 00:00:00'){
			$expire_date = DisplayDateRange($zodiac_list[0]['expire_date']); 
		}else{
			$expire_date = '';
		}		
		//#mm/dd/yyyy
		
		$column_picture = site_url('zodiac/picture/'.$zd_id2);

			//Debug($credit_list);
			//Debug($relate_list);
			//Debug($relate_columnist_list);
			//die();

			if(isset($success)){
?>
				<div class="col-xs-12">
							<div class="alert alert-success">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon glyphicon glyphicon-ok"></i>
											</strong><?php echo $success?>.
									<br>
							</div>
				</div>
<?php
			}

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

			if($zodiac_list[0]['zodiac_date'] != '00-00-0000' && $zodiac_list[0]['zodiac_date'] != '0000-00-00' && $zodiac_list[0]['zodiac_date'] != '') 
				$zodiac_date = DisplayDateRange($zodiac_list[0]['zodiac_date'], 2);
			else
				$zodiac_date = '';
		
?>
			<input type="hidden" name="create_date" value="<?php echo $create_date?>">

			<div class="form-group zodiac" id="zodiac">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['zodiac']?></label>
					<div class="col-sm-9">
							<?php echo $zodiac_view?>
					</div>
			</div>

			<div class="form-group zodiac" id="zodiac_date">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['zodiac']?> <?php echo $language['date'] ?></label>
					<div class="col-sm-9">
							<div class="input-group">
									<input type="text" id="zodiac_date" class="input-sm form-control date-picker" name="zodiac_date" value="<?php echo $zodiac_date ?>"  data-date-format="dd-mm-yyyy" readonly />
									<code><i class="menu-icon fa fa-info"></i> Date Format DD-MM-YYYY</code>
							</div>
					</div>
			</div>

									<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (EN) </label>
										<div class="col-sm-9">
											<input value="<?php echo $zodiac_list[0]['title']?>" type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="title_en" name="title_en" maxlength="140">
											<div id="countitle"></div>
											<code><i class="menu-icon fa fa-info"></i> ไม่เกิน 140 ตัวอักษร</code>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (TH) </label>
										<div class="col-sm-9">
											<input value="<?php echo $zodiac_list[1]['title']?>" type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="title_th" name="title_th" maxlength="140">
											<div id="countitle2"></div>
											<code><i class="menu-icon fa fa-info"></i> ไม่เกิน 140 ตัวอักษร</code>
										</div>
									</div>


								<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['shorttitle']?> (EN) 
										</label>

										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea id="description_en" name="description_en" style="width:100%;height:150px"><?php echo $zodiac_list[0]['description']?></textarea>
													<?php //echo display_ckeditor($description_en); ?>
												</div>
										</div>
								</div>

								<div class="form-group">									
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['shorttitle']?> (TH) 
										</label>

										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea  id="description_th" name="description_th" style="width:100%;height:150px"><?php echo $zodiac_list[1]['description']?></textarea>
													<?php //echo display_ckeditor($description_th); ?>
												</div>
												<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="<?php echo $language['meta_description_seodoctor']?>" title="" data-original-title="<?php echo $language['meta_description']?>">?</span>
										</div>
								</div>

								<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['detail']?> (EN) </label>
										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea cols="80" id="detail_en" name="detail_en" rows="10"><?php echo $zodiac_list[0]['detail']?></textarea>
													<?php echo display_ckeditor($detail_en); ?>
												</div>
										</div>
								</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['detail']?> (TH) </label>
										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea cols="80" id="detail_th" name="detail_th" rows="10"><?php echo $zodiac_list[1]['detail']?></textarea>
													<?php echo display_ckeditor($detail_th); ?>
												</div>
										</div>
								</div>

									<!-- <div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['columnist']?></label>
										<div class="col-sm-9">
											<select class="chosen-select" id="columnist_id" name="columnist_id">
<?php 
				/*echo '<option value=""> --- '.$language['please_select'].' --- </option>';

				$alllist = count($columnist_list);
				if($columnist_list)
						for($i = 0; $i < $alllist; $i++){
									$columnist_name = $columnist_list[$i]['columnist_name'];

									$selected = ($columnist_list[$i]['columnist_id'] == $zodiac_list[0]['columnist_id']) ? 'selected' : '';

									echo '<option value="'.$columnist_list[$i]['columnist_id'].'" '.$selected.'>'.$columnist_name.'</option>';

						}*/
?>
											</select>
										</div>
									</div> -->

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['credit']?></label>
										<div class="col-sm-9">
											<?php echo $credit_list?>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['edit'].' Tags ' ?> </label>
										<div class="col-sm-9">												
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" name="update_keyword">
														<span class="lbl"><code> ติกเพื่อปรับปรุงแก้ไข Tags </code></span>
													</label>
												</div>
										</div>
									</div>

								<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Tags</label>
										<a href="<?php echo site_url('tags/add'); ?>" target=_blank>
										<i class="ace-icon glyphicon glyphicon-plus"></i>
										<?php echo $language['add'].' '.$language['tags']?></a>
										<div class="col-sm-9">
											<?php echo $tags_list?>
											<span class="middle">
												<code>* <?php echo $language['require']?> ควรใส่ 3 - 5 tag</code><br>
											</span>
										</div>
								</div>

								<!-- <div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['auto_tags'] ?> </label>
										<div class="col-sm-9">												
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" name="auto_tags">
														<span class="lbl"> <?php echo $language['auto_tags'].' ('.$language['dara'].')' ?></span>
													</label>
												</div>
										</div>
								</div> -->


								<!-- <div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"> <?php //echo $language['search for tags news'] ?> </label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="search_news1" maxlength="255">
											<button class="btn btn-purple btn-sm" type="button"><i class="ace-icon glyphicon glyphicon-search"></i></button>
										</div>
										<div class="col-sm-12" id="newslist_tags"></div>
								</div> -->

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
										<div class="col-xs-3">
													<label>
														<input name="status" id="column_status" class="ace ace-switch ace-switch-6" type="checkbox" value=1  <?php if($zodiac_list[0]['status'] == 1) echo "checked";?> />
														<span class="lbl"></span>
													</label>
										</div>
								</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['can_comment']?></label>
										<div class="col-xs-3">
													<label>
														<input name="can_comment" id="can_comment" class="ace ace-switch ace-switch-6" type="checkbox" value=1  <?php if($zodiac_list[0]['can_comment'] == 1) echo "checked";?> />
														<span class="lbl"></span>
													</label>
										</div>
								</div>

<?php
			echo form_close();
//***************Column Relate
?>
					<!-- <div class="form-group">
							<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
							<div class="col-xs-3">
									<label>
											<a href="<?php echo $column_picture ?>"><i class="menu-icon fa fa-picture-o bigger-160"></i></a>
									</label>
							</div>
					</div>-->		

				<div style="clear: both;"></div>
						<div class="clearfix form-actions">
								<div class="col-md-offset-1 col-md-12 col-sm-12">

										<button type="button" class="btn btn-purple bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>">
											<i class="ace-icon fa fa-search-plus bigger-110" ></i> <?php echo $language['preview'] ?>
										</button>
<?php
		if($zodiac_list[0]['approve'] == 0){
?>
								<button type="button" class="btn btn-success admin_approve">
										<i class="ace-icon fa fa-check bigger-110"></i> Approve
								</button>
<?php
		}else{
?>
								<button type="button" class="btn" disabled>
										<i class="ace-icon fa fa-check bigger-110"></i> Approved
								</button>
<?php
		}
?>
								<button type="button" class="btn btn-info submit_zodiac" >
										<i class="ace-icon fa fa-floppy-o bigger-110"></i> <?php echo $language['save'] ?>
								</button>

								<button type="reset" class="btn reset_zodiac" id="reset_zodiac">
										<i class="ace-icon fa fa-undo bigger-110"></i>Reset
								</button>

								<button type="button" class="btn btn-success gen_json" id="gen_json" <?php if($approve == 0) echo "disabled" ?>>
									<i class="ace-icon glyphicon glyphicon-file bigger-110"></i> <?php echo $language['generate_catch']?>
								</button>
<?php
		if($zodiac_list[0]['approve'] == 0){
?>
								<button type="button" class="btn btn-pink sentmail">
										<i class="ace-icon fa fa-envelope bigger-110"></i> <?php echo $language['sentmail_manager']?>
								</button>
<?php
		}
?>
						</div>
					</div>

			</div>
		</div>
<?php 
	// แสดง Action ของ Article
	$showdata = array(
			"create_date" => $zodiac_list[0]['create_date'],
			"create_by_name" => $zodiac_list[0]['create_by_name'],
			"lastupdate_date" => $zodiac_list[0]['lastupdate_date'],
			"lastupdate_by_name" => $zodiac_list[0]['lastupdate_by_name'],
			"approve_date" => $zodiac_list[0]['approve_date'],
			"approve_by_name" => $zodiac_list[0]['approve_by_name'],
	);
	$this->box_model->DisplayLog($showdata); 
	$this->box_model->DisplayLogActivity($view_log); 
?>
	</div><!-- /.col -->
</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );	

		<?php 
			//if($subcategory_id != 39) echo "$('.zodiac').css('display', 'none');"; 
		?>

		$('#title_en').on('keypress', function(e){
				var len = $(this).val().length;
				$('#countitle').html(len);
		});

		$('#title_th').on('keypress', function(e){
				var len = $(this).val().length;
				$('#countitle2').html(len);
		});

		/*$('#subcategory_id').change(function( ) {
				var subcatid = $(this).val();
				if(subcatid == 39) $('.zodiac').css('display', 'block');
		});*/

<?php 
		//if($picture_list){ echo "$('#upload_avatar').attr('style', 'display:none');\n";	} 
?>

		$('#bootbox-confirm').click(function( ) {
				var v = $(this).attr('data-value');
				var img = $(this).attr('data-img');
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
				if(result){
									//alert('del');
									$.ajax({
											type: 'POST',
											url: "<?php echo base_url() ?>picture/remove_img/" + v,
											data : { img : img, v : v},
											cache: false,
											success: function(data){
													//alert(data);
													if(data = 'Yes'){
															//$('#picture_use').attr('style', 'display:none');
															$('#picture_cover').attr('style', 'display:none');
															$('#upload_avatar').attr('style', 'display:block');
													}
											}
									});
						}
				});
		}); 

		/*$('#category_id').change(function( ) {
				var catid = $(this).val();
				$('#subcategory_id').load('<?php echo base_url() ?>subcategory/list_dd/' + catid);
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
		/*$('#modal-form').on('shown.bs.modal', function () {
					$(this).find('.chosen-container').each(function(){
						$(this).find('a:first-child').css('width' , '210px');
						$(this).find('.chosen-drop').css('width' , '210px');
						$(this).find('.chosen-search input').css('width' , '200px');
					});
		})*/

		/**
		//or you can activate the chosen plugin after modal is shown
		//this way select element becomes visible with dimensions and chosen works as expected
		$('#modal-form').on('shown', function () {
					$(this).find('.modal-chosen').chosen();
		})*/

		//or you can activate the chosen plugin after modal is shown
		//this way select element becomes visible with dimensions and chosen works as expected

		$('#modal-form').on('shown', function () {
				$(this).find('.modal-chosen').chosen();
		})

		/*$('#search_news1').on('change', function(e){
				var v = $(this).val();
				$('#newslist_tags').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
				$.ajax({
						type: 'GET',
						url: "<?php echo base_url('zodiac/search')?>",
						data: {kw : v},
						cache: false,
						success: function(data){
								$('#newslist_tags').html(data);
								//$('#newslist_tags').html($(this).val());
								//$("#alertorder").fadeIn();
								//$("#msg").html(data);

						}
				});
		});*/

		/*$('#search_column').on('change', function(e){
				var v = $(this).val();
				var columnid = $(this).attr('data-value');
				
				$('#newslist_relate').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
				$.ajax({
						type: 'GET',
						url: "<?php echo base_url('zodiac/search_relate')?>",
						data: {kw : v, columnid : columnid},
						cache: false,
						success: function(data){
								$('#newslist_relate').html(data);
						}
				});
		});*/

		$('.preview').on('click', function() {			
			//document.getElementById("ColumnForm").submit();
			window.open('<?php echo $previewurl ?>');
		});	

		$('.admin_approve').on('click', function() {
				//AlertError('Click');
<?php
				if($this->session->userdata('admin_type') <= 4){	
?>
						$("#msg_info").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Please, wait...');
						$("#msg_info,#BG_overlay").fadeIn();

						$.ajax({
							type: 'POST',
							url: "<?php echo base_url('zodiac/approve/'.$zd_id2)?>",
							cache: false,
							success: function(data){
									$("#msg_info").fadeOut();
									if(data == 0){
										$("#msg_error").attr('style','display:block;');
										AlertError('Not approve');
									}else{
										$("#msg_success").attr('style','display:block;');
										$('.admin_approve').attr('disabled','disabled');
										$('.gen_json').removeAttr('disabled');
										$('.sentmail').attr('style','display:none;');
										
										//AlertSuccess	('Approve And Generate json file.');
										AlertSuccess	('Approve.');
										//Gencatch();
									}
							}
						});
<?php
				}else{
?>
					 AlertError('Can not Approve');
<?php
				}
?>
		});

		$('.submit_zodiac').on('click', function() {
				//alert('Under constrction');
				var frm = document.ColumnForm;
				//document.getElementById("ColumnForm").submit();
				//alert('Under constrction');
				chkform(frm);
		});

		$('.reset_zodiac').on('click', function() {
				document.getElementById("ColumnForm").reset();
		});

		$('.sentmail').on('click', function(e){
				alert('Under Constrction.');
				/*$.ajax({
						type: 'POST',
						data: {id : <?php //echo $zodiac_list[0]['column_id2']?>},
						url: "<?php //echo base_url('column/sentmail')?>",
						cache: false,
						success: function(data){
								AlertSuccess	(data);
						}
				});*/
		});

		$('.gen_json').on('click', function(e){
				 alert('Under constrction');
				 //Gencatch();
		});

		/*$('#bx-del-relate-all').on('click', function() {
				var v = $(this).attr('data-value');
				alert(v);
		});*/

		var updateOutput = function(e){
				var list   = e.length ? e : $(e.target),
					output = list.data('output');
				if (window.JSON) {
					//output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
					output.html(window.JSON.stringify(list.nestable('serialize')));
					UpdateRelate(window.JSON.stringify(list.nestable('serialize')));
				} else {
					output.html('JSON browser support required for this demo.');
					UpdateRelate(window.JSON.stringify(list.nestable('serialize')));
				}
		};

		$('.dd').nestable({
			group: 1
		}).on('change', updateOutput);

		updateOutput($('.dd').data('output', $('#nestable-output')));

		/*************************************************
		var updateOutput2 = function(e){
				var list   = e.length ? e : $(e.target),
					output = list.data('output');
				if (window.JSON) {
					//output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
					output2.html(window.JSON.stringify(list.nestable2('serialize')));
					UpdateRelate2(window.JSON.stringify(list.nestable('serialize')));
				} else {
					output2.html('JSON browser support required for this demo.');
					UpdateRelate2(window.JSON.stringify(list.nestable('serialize')));
				}
		};

		$('.dd2').nestable({
			group: 1
		}).on('change', updateOutput2);

		updateOutput2($('.dd').data('output', $('#nestable-output2')));
		*************************************************/

		$(".bootbox-options").on(ace.click_event, function() {
					var url = $(this).attr('data-value');
					var title = $(this).attr('data-name');
					bootbox.dialog({
						message: "<span class='bigger-110'><?php echo $language['preview'] ?> " + title + "</span>",
						buttons: 			
						{
							"success" :
							 {
								"label" : "<i class='ace-icon fa fa-desktop'></i> Desktop ",
								"className" : "btn-sm btn-success",
								"callback": function() {
									window.open(url + '&device=desktop');
								}
							},

							"click" :
							{
								"label" : "<i class='ace-icon fa fa-laptop'></i> Mobile ",
								"className" : "btn-sm btn-primary",
								"callback": function() {
									window.open(url + '&device=mobile');
								}
							}
						}
					});
		});

});

function UpdateRelate(json){
			//alert(json);
			$.ajax({
					type: 'POST',
					url: "<?php echo base_url('column/set_order_relate')?>",
					data: {json: json, columnid : <?php echo $zodiac_list[0]['zid2']?>},
					cache: false,
					success: function(data){
							$("#alertorder").fadeIn();
							$("#msg").html(data);
					}
			});

			setTimeout(function(){
					$("#alertorder").fadeOut("slow", function () {});
			}, 3000);
}

function UpdateRelate2(json){
			//alert(json);
			$.ajax({
					type: 'POST',
					url: "<?php echo base_url('zodiac/set_order_relate2')?>",
					data: {json: json, zodiacid : <?php echo $zodiac_list[0]['zid2']?>},
					cache: false,
					success: function(data){
							$("#alertorder2").fadeIn();
							$("#msg2").html(data);
					}
			});

			setTimeout(function(){
					$("#alertorder2").fadeOut("slow", function () {});
			}, 3000);
}

function chkform(frm){
		
		var zid = frm.zid.value;
		var title_th = frm.title_th.value;
		var zodiac_date = frm.zodiac_date.value;

		if(zid == "0"){
				alert('กรุณาเลือกราศีด้วยครับ');
				frm.zid.focus();
		}else if(zodiac_date == ''){
				alert('กรุณาใส่ <?php echo $language["date"]?> ด้วยครับ');
				frm.zodiac_date.focus();
		}else if(title_th == ''){
				alert('กรุณาใส่ <?php echo $language["title"]?> ด้วยครับ');
				frm.title_th.focus();
		}else{
				frm.submit();
		}
		
}

/*function Gencatch(){
				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				//alert('Under Constrction.');
				$.ajax({
						type: 'POST',
						url: "<?php //echo $this->config->config['api']; ?>/api/web-api.php",
						data: {method : 'DetailColumn', key : 'mMs3dAkM', zodiac_id : <?php //echo $zodiac_id2 ?>, gen_file : 1},
						cache: false,
						success: function(v){
							//alert(v.header.message);
							txt = 'Approve and Genarate zodiac ID ' + v.body.item[0].column_id + ' ' + v.header.message;
							AlertSuccess(txt);
						},
				});
}*/
</script>
