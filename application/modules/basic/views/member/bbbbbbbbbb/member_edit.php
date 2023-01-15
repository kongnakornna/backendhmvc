<?php 
		$language = $this->lang->language;
		//$extra = ($dara_list['belong_to_id'] == 0) ? 'selected' : '';
		$html = "";
?>

<style type="text/css">
	.input-icon {width: 80%;}
	ul.ace-thumbnails > li {max-width: 250px;width: auto;height:auto;}
	li{list-style: none;}

	#dara_avatar .ace-thumbnails > li img{width: 100%;}	
	div.row {
		padding: 10px;
	}
  
	div.row label {
		font-weight: bold;
		display: block;
		padding: 0px 0px 10px;
	}
</style>

<div class="row">
		<div class="col-xs-12">
				<div class="row">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open_multipart('dara/save', $attributes);
?>
									<div class="page-header">
										<h1>
											<?php echo $language['dara'] ?>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['edit'] ?>
											</small>
										</h1>
									</div>

									<div class="col-xs-12">
<?php
		if(function_exists('Debug')){
				//Debug($dara_list);
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

		if($dara_list['avatar'] != ''){
				$avatar = (!file_exists('uploads/dara/'.$dara_list['avatar'])) ? 'theme/assets/avatars/avatar3.png' : 'uploads/dara/'.$dara_list['avatar'];
				$img_dara = '<img src="'.base_url().$avatar.'" >';
		}

?>
			<!-- <div>
											<h3 class="header smaller lighter purple">
												Bootstrap Modals
												<small>(Bootbox.js)</small>
											</h3>
											<p>
												<button class="btn" id="bootbox-regular">Regular Dialog</button>
												<button class="btn btn-info" id="bootbox-confirm">Confirm</button>
												<button class="btn btn-success" id="bootbox-options">More Options</button>
											</p>
				</div> -->

				<!-- #section:elements.form -->
								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
										<div class="col-sm-9">
											<div class="col-xs-12">
													<?php

															if($dara_list['avatar'] != ''){

																	$url_edit_pic = base_url('dara/picture').'/'.$this->uri->segment(3);

																	echo '<div id="dara_avatar">';
																	echo '<ul class="ace-thumbnails clearfix"><li>';
																	echo $img_dara;
																	echo '<div class="tools tools-top">
																	<a href="'.$url_edit_pic.'">
																		<i class="ace-icon fa fa-pencil"></i>
																	</a>
																	</div>
																	</li></ul>';

																	/*<!-- <a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm" title="Delete" data-value="'.$dara_list['dara_profile_id'].'" data-name="'.$dara_list['avatar'].'">
																	<i class="ace-icon fa fa-trash-o bigger-130"></i></a> -->*/

																	echo '<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm" title="Delete" data-value="'.$dara_list['dara_profile_id'].'" data-name="'.$dara_list['avatar'].'">
																	<i class="ace-icon fa fa-trash-o bigger-130"></i></a></div>';

																	$backup_img = base_url('uploads/tmp/dara').'/'.$dara_list['avatar'];
																	echo '<a href="'.$backup_img.'" target=_blank>Backup Picture</a>';
															}else
																	echo '';
													?>
													<div id="upload_avatar"><input type="file" id="avatar" name="avatar" /></div>
											</div>
										</div>
								</div>
<?php
													$tags_dara = '';
													if($sel_tags)
															for($i=0;$i<count($sel_tags);$i++){
																	//echo "<code>".$sel_tags[$i]->tag_text."</code>";
																	//echo '<span class="label label-info arrowed-right arrowed-in">'.$sel_tags[$i]->tag_text.'</span>';
																	if($i == 0)
																		$tags_dara = $sel_tags[$i]->tag_text;
																	else
																		$tags_dara .= ','.$sel_tags[$i]->tag_text;

															}
?>
									<!-- <div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['tags']?></label>

										<div class="col-sm-9"><div class="row">

											<input type="text" id="jquery-tagbox-text" name="tags" value="<?php echo $tags_dara?>"/>
											<input type="hidden" name="tags_remove">
										</div></div>
									</div> -->
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-tags"><?php echo $language['tags']?></label>
										<div class="col-sm-9">
											<div class="inline">
												<input type="text" name="tags" id="form-field-tags" value="<?php echo $tags_dara?>" placeholder="Enter tags ..." />
											</div>

										</div>
									</div>												
											

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['first_name']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['first_name']?>" id="first_name" name="first_name" value="<?php echo $dara_list['first_name']?>">
											<div id="listname" class="alert col-sm-5"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['middle_name']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['middle_name']?>" id="middle_name" name="middle_name" value="<?php echo $dara_list['middle_name']?>">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['last_name']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['last_name']?>" id="last_name" name="last_name" value="<?php echo $dara_list['last_name']?>">
											<div id="listlastname" class="alert col-sm-5"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['nickname']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['nickname']?>" id="nick_name" name="nick_name" value="<?php echo $dara_list['nick_name']?>">
											<div id="listnickname" class="alert col-sm-5"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['pen_name']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['pen_name']?>" id="pen_name" name="pen_name" value="<?php echo $dara_list['pen_name']?>">
											<div id="listpenname" class="alert col-sm-5"></div>
										</div>
									</div>

									<div class="form-group">
										<label  for="belong_to_id" class="col-sm-3 control-label no-padding-right"><?php echo $language['belong_to']?></label>
										<div class="col-sm-9">
										<?php 

											echo $belong_to_list;

											//if(!isset($belong_to_id)) $belong_to_id = 0;
											//else $belong_to_id = $dara_list['belong_to_id'];

											//$html = "\n<select name=\"belong_to_id[]\" class='chosen-select' id='belong_to_id' data-placeholder=\"Choose a item...\" multiple>";
											/*$html = "\n<select name=\"belong_to_id\" class='' id='belong_to_id' data-placeholder=\"Choose a item...\">";											
											$first = "--- ".$language['please_select']." ---";
											$html .= "\n\t<option value=\"0\" $extra >" . $first . "</option>";

											if($belong_to_list)
											for($i=0;$i<count($belong_to_list);$i++){
													foreach($belong_to_list[$i] as $key => $val){
															if($key == "belong_to_id") $value = $val;
															if($key == "belong_to") $tname = $val;
													}
													$extra = (intval($value) == intval($dara_list['belong_to_id'])) ? " selected=\"selected\"" : "";
													$html .= "\n\t<option value=" . $value . " $extra >" . $tname . "</option>";
											}

											$html .= "\n</select>\n";*/
											//echo $html;
											?>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['dara_type_name']?></label>

										<div class="col-sm-9">
											<!-- <input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['sex']?>" id="sex" name="sex"> -->
											<select class="form-control" id="form-field-select-1" name="dara_type_id">
<?php 
				$alltype = count($dara_type);
				if($dara_type)
						for($i = 0; $i < $alltype; $i++){
									$sel = ($dara_list['dara_type_id'] == $dara_type[$i]['dara_type_id_map']) ? 'selected' : '';
									echo '<option value="'.$dara_type[$i]['dara_type_id_map'].'" '.$sel.'>'.$dara_type[$i]['dara_type_name'].'</option>';
						}
?>
											</select>

										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['sex']?></label>
										<div class="col-sm-9">
											<!-- <input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['sex']?>" id="sex" name="sex"> -->
											<select class="form-control" id="form-field-select-1" name="gender">
													<option value="m" <?php echo ($dara_list['gender'] =='m') ? 'selected' : '' ?>><?php echo $language['male']?></option>
													<option value="f" <?php echo ($dara_list['gender'] =='f') ? 'selected' : '' ?>><?php echo $language['female']?></option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['birthday']?></label>
										<div class="col-sm-9">
																<div class="input-group">

																	<input class="form-control date-picker" id="id-date-picker-1" name="birth_date" type="text" data-date-format="dd-mm-yyyy" value="<?php echo ($dara_list['birth_date'] != '0000-00-00') ? DisplayDate($dara_list['birth_date']) : '' ?>" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
																</div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['address']?></label>

										<div class="col-sm-9">
												<textarea placeholder="<?php echo $language['address']?>" id="address" name="address" class="form-control"><?php echo $dara_list['address']?></textarea>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['weight']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['weight']?>" id="weight" name="weight" value="<?php echo $dara_list['weight']?>" >
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['height']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['height']?>" id="height" name="height" value="<?php echo $dara_list['height']?>" >
										</div>
									</div>


									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['education']?></label>

										<div class="col-sm-9">
											<!-- <input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['sex']?>" id="sex" name="sex"> -->
											<select class="form-control" id="form-field-select-2" name="education">
												<option value=""> - </option>
												<option value="1" <?php echo ($dara_list['education'] =='1') ? 'selected' : '' ?>><?php echo $language['grade1']?></option>
												<option value="2" <?php echo ($dara_list['education'] =='2') ? 'selected' : '' ?>><?php echo $language['grade2']?></option>
												<option value="3" <?php echo ($dara_list['education'] =='3') ? 'selected' : '' ?>><?php echo $language['grade3']?></option>
												<option value="4" <?php echo ($dara_list['education'] =='4') ? 'selected' : '' ?>><?php echo $language['grade4']?></option>
											</select>

										</div>
									</div>


								<div class="form-group">
									<!-- EDITOR 1 -->
									
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['hobby']?>
										</label>

										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea cols="80" id="hobby" name="hobby" rows="10"><?php echo $dara_list['hobby']?></textarea>
													<?php echo display_ckeditor($ckeditor); ?>
												</div>
										</div>
								</div>

								<div class="form-group">
								<!-- EDITOR 2 -->
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['profile']?></label>
										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea cols="80" id="profile_background" name="profile_background" rows="10"><?php echo $dara_list['profile_background']?></textarea>
													<?php echo display_ckeditor($ckeditor2); ?>
												</div>
										</div>
								</div>

								<div class="form-group">
								<!-- EDITOR 3 -->
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['portfolio']?></label>

										<div class="col-sm-9">
											<div id="fullToolbar">											
												<textarea cols="80" id="portfolio" name="portfolio" rows="10"><?php echo $dara_list['portfolio']?></textarea>
													<?php echo display_ckeditor($ckeditor3); ?>
											</div>
										</div>
								</div>

							<div class="form-group">
									<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['lastportfolio']?></label>
									<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['lastportfolio']?>" id="last_portfolio" name="last_portfolio" value="<?php echo $dara_list['last_portfolio']?>" >
									</div>
							</div>

							<div class="form-group">
									<label for="form-field-facebook" class="col-sm-3 control-label no-padding-right">Facebook</label>
									<div class="col-sm-9">
											<span class="input-icon">
													<input type="text" id="form-field-facebook" name="facebook" placeholder="Full URL" class="col-xs-12 col-sm-12" value="<?php echo $dara_list['facebook']?>">
													<i class="ace-icon fa fa-facebook blue"></i>
											</span>
									</div>
							</div>

							<div class="form-group">
									<label for="form-field-twitter" class="col-sm-3 control-label no-padding-right">Twitter</label>
									<div class="col-sm-9">
											<span class="input-icon">
													<input type="text" id="form-field-twitter" name="twitter" placeholder="Full URL" class="col-xs-12 col-sm-12" value="<?php echo $dara_list['twitter']?>">
													<i class="ace-icon fa fa-twitter light-blue"></i>
											</span>
									</div>
							</div>

							<div class="form-group">
									<label for="form-field-instragram" class="col-sm-3 control-label no-padding-right">Instagram</label>
									<div class="col-sm-9">
											<span class="input-icon">
													<input type="text" id="form-field-instragram" name="instagram" placeholder="Full URL" class="col-xs-12 col-sm-12" value="<?php echo $dara_list['instagram']?>">
													<i class="ace-icon fa fa-instagram" style="color:#9c6b53;"></i>
											</span>
									</div>
							</div>

							<div class="form-group">
									<label for="form-field-googleplus" class="col-sm-3 control-label no-padding-right">Google+</label>
									<div class="col-sm-9">
											<span class="input-icon">
													<input type="text" id="form-field-googleplus" name="googleplus" placeholder="Full URL" class="col-xs-12 col-sm-12" value="<?php echo $dara_list['googleplus']?>">
													<i class="ace-icon fa fa-google-plus-square red"></i>
											</span>
									</div>
							</div>

							<div class="form-group">
									<label for="form-field-gplus" class="col-sm-3 control-label no-padding-right">Youtube</label>
									<div class="col-sm-9">
											<span class="input-icon">
													<input type="text" id="form-field-gplus" name="youtube_channel" placeholder="Full URL" class="col-xs-12 col-sm-12" value="<?php echo $dara_list['youtube_channel']?>">
													<i class="ace-icon fa fa-youtube red"></i>
											</span>
									</div>
							</div>

							<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
										<div class="col-xs-3">
													<label>
														<input name="status" id="cat_status" class="ace ace-switch ace-switch-4" type="checkbox" value=1 <?php echo ($dara_list['status'] == '1') ? 'checked' : '' ?> />
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
							<input type="hidden" name="dara_profile_id" value="<?php echo $dara_list['dara_profile_id']?>" >
							<!-- <input type="hidden" name="dara_profile_id_map" value="<?php //echo $dara_list['dara_profile_id_map']?>" > -->

							</div>
						<?php echo form_close();?>
						</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->

<?php
		//echo js_asset('bootbox.min.js'); 
		//echo js_asset('jquery.tagbox.js'); 
?>

<script type="text/javascript">
$( document ).ready(function() {
		//console.log( "ready!" );
<?php 

		if($dara_list['avatar'] != ''){ echo "$('#upload_avatar').attr('style', 'display:none');\n";	} 

?>
		/*$('.tagBox-input').click(function( ) {
				var v = $(this).val();
				alert(v);
		});*/
		$('#listname').css('display', 'none');
		$('#listnickname').css('display', 'none');

		$('#first_name').blur(function( ) {
				var v = $(this).val();
				//alert(v);
				if(v != ''){
						$.ajax({
								type: 'POST',
								url: "<?php echo base_url() ?>dara/chkname",
								data : { first_name : v},
								cache: false,
								success: function(data){
										//alert(data);
										$('#listname').html(data);
										$('#listname').css('display', 'block');

										//$('#listname').attr('class', 'alert alert-info col-sm-5');

										if(data == '<li><b>สามารถเพิ่มรายชื่อนี้ได้ ยังไม่มีข้อมูล</b></li>')
											$('#listname').attr('class', 'alert alert-danger col-sm-5');
										else
											$('#listname').attr('class', 'alert alert-info col-sm-5');

										/*if(data = 'Yes'){
												$('#dara_avatar').attr('style', 'display:none');
												$('#listname').html(data);
										}*/
								}
						});
				}
		});

		$('#last_name').blur(function( ) {
				var v = $(this).val();
				//alert(v);
				if(v != ''){
						$.ajax({
								type: 'POST',
								url: "<?php echo base_url() ?>dara/chkname",
								data : { last_name : v},
								cache: false,
								success: function(data){
										//alert(data);
										$('#listlastname').html(data);
										$('#listlastname').css('display', 'block');

										//$('#listname').attr('class', 'alert alert-info col-sm-5');
										if(data == '<li><b>สามารถเพิ่มรายชื่อนี้ได้ ยังไม่มีข้อมูล</b></li>')
											$('#listlastname').attr('class', 'alert alert-danger col-sm-5');
										else
											$('#listlastname').attr('class', 'alert alert-info col-sm-5');

										/*if(data = 'Yes'){
												$('#dara_avatar').attr('style', 'display:none');
												$('#listname').html(data);
										}*/
								}
						});
				}
		});

		$('#nick_name').blur(function( ) {
				var v = $(this).val();
				//alert(v);
				if(v != ''){
						$.ajax({
								type: 'POST',
								url: "<?php echo base_url() ?>dara/chkname",
								data : { nick : v},
								cache: false,
								success: function(data){
										//alert(data);
										$('#listnickname').html(data);
										$('#listnickname').css('display', 'block');

										//$('#listnickname').attr('class', 'alert alert-info col-sm-5');
										if(data == "<li><b>สามารถเพิ่มรายชื่อนี้ได้ ยังไม่มีข้อมูล</b></li>")
											$('#listnickname').attr('class', 'alert alert-danger col-sm-5');
										else
											$('#listnickname').attr('class', 'alert alert-info col-sm-5');

										/*if(data = 'Yes'){
												$('#dara_avatar').attr('style', 'display:none');
												$('#listname').html(data);
										}*/
								}
						});
				}
		});

		$('#pen_name').blur(function( ) {
				var v = $(this).val();
				//alert(v);
				if(v != ''){
						$.ajax({
								type: 'POST',
								url: "<?php echo base_url() ?>dara/chkname",
								data : { pen_name : v},
								cache: false,
								success: function(data){
										//alert(data);
										$('#listpenname').html(data);
										$('#listpenname').css('display', 'block');

										//$('#listnickname').attr('class', 'alert alert-info col-sm-5');
										if(data == "<li><b>สามารถเพิ่มรายชื่อนี้ได้ ยังไม่มีข้อมูล</b></li>")
											$('#listpenname').attr('class', 'alert alert-danger col-sm-5');
										else
											$('#listpenname').attr('class', 'alert alert-info col-sm-5');

										/*if(data = 'Yes'){
												$('#dara_avatar').attr('style', 'display:none');
												$('#listname').html(data);
										}*/
								}
						});
				}
		});

		$('#bootbox-confirm').click(function( ) {
				/*var v = $(this).attr('data-value');
				var nn = $(this).attr('data-name');
				$.ajax({
						type: 'POST',
						url: "<?php echo base_url() ?>dara/remove_img/" + v,
						data : { name : nn},
						cache: false,
						success: function(data){
								if(data = 'Yes'){										
										$('#dara_avatar').attr('style', 'display:none');
										$('#upload_avatar').attr('style', 'display:block');
								}
						}
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
		});

		$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true
		})


		$('#avatar').ace_file_input({
					style:'well',
					btn_choose:'Drop files here or click to choose',
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

					
				$("#bootbox-confirm").on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
						if(result) {
									var v = $(this).attr('data-value');
									var nn = $(this).attr('data-name');
									$.ajax({
											type: 'POST',
											url: "<?php echo base_url() ?>dara/remove_img/" + v,
											data : { name : nn},
											cache: false,
											success: function(data){
													if(data = 'Yes'){										
															$('#dara_avatar').attr('style', 'display:none');
															$('#upload_avatar').attr('style', 'display:block');
													}
											}
									});
						}
					});
				});
					
				$("#bootbox-options").on(ace.click_event, function() {
					bootbox.dialog({
						message: "<span class='bigger-110'>I am a custom dialog with smaller buttons</span>",
						buttons: 			
						{
							"success" :
							 {
								"label" : "<i class='ace-icon fa fa-check'></i> Success!",
								"className" : "btn-sm btn-success",
								"callback": function() {
									//Example.show("great success");
								}
							},
							"danger" :
							{
								"label" : "Danger!",
								"className" : "btn-sm btn-danger",
								"callback": function() {
									//Example.show("uh oh, look out!");
								}
							}, 
							"click" :
							{
								"label" : "Click ME!",
								"className" : "btn-sm btn-primary",
								"callback": function() {
									//Example.show("Primary button");
								}
							}, 
							"button" :
							{
								"label" : "Just a button...",
								"className" : "btn-sm"
							}
						}
					});
				});

<?php
/*
$tags_dara = '';
if($sel_tags)
	for($i=0;$i<count($sel_tags);$i++){
			//echo "<code>".$sel_tags[$i]->tag_text."</code>";
			//echo '<span class="label label-info arrowed-right arrowed-in">'.$sel_tags[$i]->tag_text.'</span>';
			if($i == 0)
				$tags_dara = $sel_tags[$i]->tag_text;
			else
				$tags_dara .= ','.$sel_tags[$i]->tag_text;
	}
*/	
?>
});

function removetagsdara(n){

			$.ajax({
					type: 'POST',
					url: '<?php echo base_url() ?>dara/delete_tags/' + encodeURI(n),
					//data : { name : nn},
					cache: false,
					success: function(data){
							//alert(data);
							//if(data = 'Yes'){
							//		$('#dara_avatar').attr('style', 'display:none');
							//		$('#upload_avatar').attr('style', 'display:block');
							//}
					}
			});
}
</script>

<!-- <script src="<?php echo base_url()?>/theme/assets/ckeditor/editor.js" language="javascript" type="text/javascript" ></script> -->