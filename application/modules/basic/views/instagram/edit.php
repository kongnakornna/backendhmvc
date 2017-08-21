<?php 
	$language = $this->lang->language;
	$display_en = "style='display:none;'";	

	/*if(isset($this->input->get('success'))){
		$success = $this->input->get('success');
	}*/
	//Debug($instagram_list);
	//Debug($tags_list);

	if($this->input->get('success')) $success = $this->input->get('success');

	$instagram_id = $instagram_list[0]['instagram_id'];
	$instagram_id2 = $instagram_list[0]['instagram_id2'];
	//$brand_id = $instagram_list[0]['brand_id'];
	$title = $instagram_list[0]['title'];

	//$previewurl = $this->config->config['www']."/instagram/".$category_id."/".$brand_id."/".$instagram_id2."/?preview=1";
	$previewurl = '';
	//$alllist = count($dara_list);
?>
<style type="text/css">
.fa-picture-o{font-size: x-large;}
#countitle, #countitle2 {style="margin-left: 4px; color: rgb(83, 95, 222);"}
.sentmail{display:none;}
</style>

<div class="row">
		<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
				<div class="row">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'instagramForm', 'name' => 'instagramForm');
	echo form_open_multipart('instagram/save', $attributes);
?>
			<div class="page-header">
					<h1>
							<?php echo $language['instagram'] ?>
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
								<a href="<?php echo base_url('instagram/picture/'.$this->uri->segment(3))?>">
									<button type="button" class="btn btn-primary"><i class="fa fa-picture-o align-top bigger-125"></i> <?php echo $language['edit'] ?> <?php echo $language['picture'] ?></button>
								</a>
<?php
		if($instagram_list[0]['approve'] == 0){
								$approve = 0;
?>
								<button type="button" class="btn btn-success admin_approve" data-value="<?php echo $instagram_id2?>">
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
								<button type="button" class="btn btn-info submit_instagram">
									<i class="ace-icon fa fa-floppy-o bigger-110"></i> <?php echo $language['save'] ?>
								</button>

								<button type="reset" class="btn reset_instagram" id="reset_instagram1">
									<i class="ace-icon fa fa-undo bigger-110"></i> Reset
								</button>

								<button type="button" class="btn btn-success gen_json" id="gen_json1" <?php if($approve == 0) echo "disabled" ?>>
									<i class="ace-icon glyphicon glyphicon-file bigger-110"></i> <?php echo $language['generate_catch']?>
								</button>
<?php
		if($instagram_list[0]['approve'] == 0){
?>
								<button type="button" class="btn btn-pink sentmail">
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
					//Debug($tags_list) ;
					//Debug($instagram_list);
					//Debug($instagramist_list);
		}
		$now_date = date('Y-m-d');

		//$news_highlight_id = $instagram_list[0]['news_highlight_id'];
		//$megamenu_id = $instagram_list[0]['megamenu_id'];

		echo '<input type="hidden" name="instagram_id" value="'.$instagram_list[0]['instagram_id2'].'">';
		echo '<input type="hidden" name="instagram_id_en" value="'.$instagram_list[0]['instagram_id'].'">';
		echo '<input type="hidden" name="instagram_id_th" value="'.$instagram_list[1]['instagram_id'].'">';

		if(isset($error)){
?>
				<div class="col-xs-12">
							<div class="alert alert-danger">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon fa fa-times"></i>
											</strong> <?php echo $error?>.
									<br>
							</div>
				</div>
<?php
		}
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
		$pic_instagram = site_url('instagram/picture/'.$instagram_list[0]['instagram_id2']);
?>
			<!-- #section:elements.form -->
			<!-- <div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['brand']?></label>
					<div class="col-sm-9">
							<?php //echo $brand_list?>
					</div>
			</div> -->

									<!-- <div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['highlight']?> </label>
										<div class="col-sm-9">												
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" name="highlight" <?php //if($news_highlight_id > 0) echo "checked='checked'" ?> >
														<span class="lbl"> <?php echo $language['use']?></span>
													</label>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Mega menu</label>
										<div class="col-sm-9">												
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" name="megamenu" <?php //if($megamenu_id > 0) echo "checked='checked'" ?> >
														<span class="lbl"> <?php echo $language['use']?></span>
													</label>
												</div>
										</div>
									</div> -->

									<!-- <div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['credit']?></label>
										<div class="col-sm-9">
											<?php //echo $credit_list?>
										</div>
									</div> -->

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['edit'].' Tags ' ?> </label>
										<div class="col-sm-3">
										</div>
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
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">เนื้อหานี้ 18+</label>
										<div class="col-sm-9">								
												<?php //debug($get_up18)?>				
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" name="up18" value=1 <?php //if(isset($get_up18[0]->ref_id)) echo "checked" ?>>
														<span class="lbl"> เป็น 18+</span>
													</label>
												</div>
										</div>
									</div> -->

								<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (EN) </label>
										<div class="col-sm-9">
											<input value="<?php echo $instagram_list[0]['title']?>" type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="title_en" name="title_en" maxlength="140">
											<div id="countitle"></div>
											<code><i class="menu-icon fa fa-info"></i> ไม่เกิน 140 ตัวอักษร</code>
										</div>
								</div>

								<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (TH) </label>
										<div class="col-sm-9">
											<input value="<?php echo $instagram_list[1]['title']?>" type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="title_th" name="title_th" maxlength="140">
											<div id="countitle2"></div>
											<code><i class="menu-icon fa fa-info"></i> ไม่เกิน 140 ตัวอักษร</code>
										</div>
								</div>

								<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['other_link']?></label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-12 col-sm-12" placeholder="<?php echo $language['other_link']?>" id="other_link" name="other_link" value="<?php echo $instagram_list[0]['other_link']?>" maxlength="255">
										</div>
								</div>

								<!-- EDITOR 1 -->									
								<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['detail']?> (EN) 
										</label>
										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea cols="80" id="detail_en" name="detail_en" rows="10"><?php echo $instagram_list[0]['detail']?></textarea>
													<?php echo display_ckeditor($detail_en); ?>
												</div>
										</div>
								</div>

								<!-- EDITOR 2 -->									
								<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['detail']?> (TH) 
										</label>

										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea cols="80" id="detail_th" name="detail_th" rows="10"><?php echo $instagram_list[1]['detail']?></textarea>
													<?php echo display_ckeditor($detail_th); ?>
												</div>
										</div>
								</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"> <?php echo $language['search for tags article'] ?> </label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="search_article1" maxlength="255">
											<button class="btn btn-purple btn-sm" type="button"><i class="ace-icon glyphicon glyphicon-search"></i></button>
										</div>
										<div class="col-sm-12" id="articlelist_tags"></div>
								</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>

										<div class="col-xs-3">
													<label>
														<input name="status" id="instagram_status" class="ace ace-switch ace-switch-6" type="checkbox" value=1 <?php if($instagram_list[0]['status'] == 1) echo "checked";?> />
														<span class="lbl"></span>
													</label>
										</div>
								</div>

								<!-- <input type="hidden" name="relate_list" value="<?php //echo count($get_relate) ?>"> -->
<?php echo form_close();?>
								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
										<div class="col-xs-3">
												<a href="<?php echo $pic_instagram ?>"><i class="menu-icon fa fa-picture-o"></i></a>
										</div>
								</div>

				<div style="clear: both;"></div>
						<div class="clearfix form-actions">
								<div class="col-md-offset-1 col-md-12 col-sm-12">

								<button type="button" class="btn btn-purple bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>">
									<i class="ace-icon fa fa-search-plus bigger-110" ></i> <?php echo $language['preview'] ?>
								</button>

								<a href="<?php echo base_url('instagram/picture/'.$this->uri->segment(3))?>">
									<button type="button" class="btn btn-primary"><i class="fa fa-picture-o align-top bigger-125"></i> <?php echo $language['edit'] ?> <?php echo $language['picture'] ?></button>
								</a>
<?php
		if($instagram_list[0]['approve'] == 0){
?>
								<button type="button" class="btn btn-success admin_approve" data-value="<?php echo $instagram_id2?>">
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
								<button type="button" class="btn btn-info submit_instagram">
									<i class="ace-icon fa fa-floppy-o bigger-110"></i> <?php echo $language['save'] ?>
								</button>

								<button type="reset" class="btn reset_instagram" id="reset_instagram">
									<i class="ace-icon fa fa-undo bigger-110"></i>
									Reset
								</button>

								<button type="button" class="btn btn-success gen_json" id="gen_json" <?php if($approve == 0) echo "disabled" ?>>
									<i class="ace-icon glyphicon glyphicon-file bigger-110"></i> <?php echo $language['generate_catch']?>
								</button>
<?php
		if($instagram_list[0]['approve'] == 0){
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
<?php //echo form_close();?>
</div>

<?php
	// แสดง Action ของ instagram
	$showdata = array(
			"create_date" => $instagram_list[0]['create_date'],
			"create_by_name" => $instagram_list[0]['create_by_name'],
			"lastupdate_date" => $instagram_list[0]['lastupdate_date'],
			"lastupdate_by_name" => $instagram_list[0]['lastupdate_by_name'],
			"approve_date" => $instagram_list[0]['approve_date'],
			"approve_by_name" => $instagram_list[0]['approve_by_name'],
	);
	$this->box_model->DisplayLog($showdata); 
	$this->box_model->DisplayLogActivity($view_log); 
?>

		</div>
</div>

		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
		
		$('#title_en').on('keypress', function(e){
				var len = $(this).val().length;
				$('#countitle').html(len);
		});

		$('#title_th').on('keypress', function(e){
				var len = $(this).val().length;
				$('#countitle2').html(len);
		});

		$('#search_article1').on('change', function(e){
				var v = $(this).val();
				$('#articlelist_tags').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
				$.ajax({
						type: 'GET',
						url: "<?php echo base_url('article/search')?>",
						data: {kw : v},
						cache: false,
						success: function(data){

								$('#articlelist_tags').html(data);
								//$('#articlelist_tags').html($(this).val());
								//$("#alertorder").fadeIn();
								//$("#msg").html(data);
						}
				});
		});

		$('#search_instagram').on('change', function(e){
				var v = $(this).val();
				var instagramid = $(this).attr('data-value');
				$('#list_relate').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
				$.ajax({
						type: 'GET',
						url: "<?php echo base_url('instagram/search_relate')?>",
						data: {kw : v, instagramid : instagramid},
						cache: false,
						success: function(data){
								$('#list_relate').html(data);
						}
				});
		});

		$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true
		})

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
		$('#modal-form').on('shown.bs.modal', function () {
					$(this).find('.chosen-container').each(function(){
						$(this).find('a:first-child').css('width' , '210px');
						$(this).find('.chosen-drop').css('width' , '210px');
						$(this).find('.chosen-search input').css('width' , '200px');
					});
		})

		$('.preview').on('click', function() {			
			alert('Under Constrction.');
			//window.open('<?php echo $previewurl ?>');
		});		

		$('.submit_instagram').on('click', function() {			
			//document.instagramForm.submit();
			chkform();
		});		

		$('.reset_instagram').on('click', function() {
			document.getElementById("instagramForm").reset();
		});	

		$('.sentmail').on('click', function(e){
				alert('Under Constrction.');
				/*$.ajax({
						type: 'POST',
						data: {id : <?php //echo $news_list[0]['news_id2']?>},
						url: "<?php echo base_url('dara/sentmail')?>",
						cache: false,
						success: function(data){
								AlertSuccess	(data);
						}
				});*/
		});

		$('.gen_json').on('click', function(e){

				Gencatch();

		});

		$('.del-confirm').on('click', function(e){
				var v = $(this).attr('data-value');
				var ref = $(this).attr('data-ref-value');
				var title = $(this).attr('data-name');
				if(v){
						bootbox.confirm("<?php echo $language['are you sure to delete']?> instagram " + title, function(result) {
							if(result) {
								//alert('del relate id ' + v);
								window.location='<?php echo base_url('instagram/delete_relate/'.$instagram_id2)?>?picture_id=' + v;
							}
						});
				}else{
						bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
							if(result) {
								//alert('del all relate ref_id ' + ref);
								window.location='<?php echo base_url('instagram/delete_relate/'.$instagram_id2)?>?ref_id=' + ref;
							}
						});
				}
		});

		$('.admin_approve').on('click', function() {
<?php
			if($this->session->userdata('admin_type') <= 4){	
?>
				var id = $(this).attr('data-value');
				//alert(id);
				$("#msg_info").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Please, wait...');
				$("#msg_info,#BG_overlay").fadeIn();

				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('instagram/approve/'.$instagram_id2)?>",
					//data: {id: id},
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
								AlertSuccess	('Approve');
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
					url: "<?php echo base_url('instagram/set_order_relate')?>",
					data: {json: json, instagramid : <?php echo $instagram_id2?>},
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

function chkform(){

		//var brand_id = document.instagramForm.brand_id.value;

		//if(brand_id > 0){

				if(document.getElementById("title_th").value == ''){

					alert('กรุณาใส่ <?php echo $language['title']?> ด้วย');
					document.instagramForm.title_th.focus();
					return false;

				}else if(document.getElementById("tag_id").value == ''){

					alert('กรุณาใส่ Tags ด้วย');
					document.instagramForm.update_keyword.focus();
					return false;

				}else
					document.instagramForm.submit();

		/*}else{
			alert('<?php echo $language['please_select_cat']?>');
			return false;
		}*/
		
}

function Gencatch(){
				alert('Under Constrction.');
				/*WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				$.ajax({
						type: 'POST',
						url: "<?php echo $this->config->config['api']; ?>/api/web-api.php",
						data: {method : 'Detailinstagram', key : 'mMs3dAkM', instagram_id : <?php echo $instagram_id2 ?>, gen_file : 1},
						cache: false,
						success: function(v){
							//alert(v.header.message);
							txt = 'Approve and Genarate instagram ID ' + v.body.item[0].instagram_id + ' ' + v.header.message;
							AlertSuccess(txt);
							GeninstagramCatch();
						},
				});*/
}

function GeninstagramCatch(){
				alert('Under Constrction.');
				/*WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				$.ajax({
						type: 'POST',
						url: "<?php echo $geninstagram ?>",
						cache: false,
						success: function(v){
							//alert(v.header.message);
							if(v.meta.code == 200){
								txt = 'Genarate category instagram success.';
								AlertSuccess(txt);
							}else{
								AlertError('Can not Genarate category instagram.');
							}

						},
				});*/
}
</script>

<!-- <script src="<?php echo base_url()?>/theme/assets/ckeditor/editor.js" language="javascript" type="text/javascript" ></script> -->
