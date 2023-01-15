<?php 
		$language = $this->lang->language;
?>
<style type="text/css">
.input-icon {width: 80%;}
li{list-style: none;}
</style>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<div class="row">
									<!-- <form role="form" class="form-horizontal" action="category/save"> -->
<?php
	$attributes = array('class' => 'form-horizontal', 'name' => 'DaraForm', 'id' => 'DaraForm', 'onsubmit' => 'chkform();return false;');
	echo form_open_multipart('dara/save', $attributes);
?>
									<div class="page-header">
										<h1>
											<?php echo $language['dara'] ?>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['add'] ?>
											</small>
										</h1>
									</div>

									<div class="col-xs-12">
<?php
				if(function_exists('Debug')){
					//Debug($dara);
					//Debug($dara_type);
					//Debug($belong_to) ;
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
													</strong><?php echo $error?>.
												<br>
											</div>
<?php
			}
?>
							
									<!-- #section:elements.form -->
									<div class="form-group">
											<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
											<div class="col-sm-9">
												<div class="col-xs-12">
														<input type="file" id="avatar" name="avatar" />
												</div>
											</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['pen_name']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['pen_name']?>" id="pen_name" name="pen_name" value="">
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle"><code>* <?php echo $language['require']?> ไม่ควรใส่แค่ชื่อเล่นอย่างเดียว </code></span>
											</span>
											<div id="listpenname" class="alert col-sm-5"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['first_name']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['first_name']?>" id="first_name" name="first_name" maxlength=50>
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle"><code>* <?php echo $language['require']?></code></span>
											</span>
											<div id="listname" class="alert alert-info col-sm-5"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['middle_name']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['middle_name']?>" id="middle_name" name="middle_name" maxlength=50>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['last_name']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['last_name']?>" id="last_name" name="last_name" maxlength=50>
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle"><code>* <?php echo $language['require']?></code></span>
											</span>
											<div id="listlastname" class="alert col-sm-5"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['nickname']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['nickname']?>" id="nick_name" name="nick_name">
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle"><code>* <?php echo $language['require']?></code></span>
											</span>
											<div id="listnickname" class="alert alert-info col-sm-5"></div>
										</div>
									</div>

									<!-- English name -->
									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['first_name']?> (EN)</label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['first_name']?>" id="first_name_en" name="first_name_en"  maxlength=50 >
											<div id="listname2" class="alert col-sm-5"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['middle_name']?> (EN)</label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['middle_name']?>" id="middle_name_en" name="middle_name_en" maxlength=50 >
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['last_name']?> (EN)</label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['last_name']?>" id="last_name_en" name="last_name_en" maxlength=50 >
											<div id="listlastname2" class="alert col-sm-5"></div>
										</div>
									</div>
									<!-- English name -->

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['belong_to']?></label>

										<div class="col-sm-9">
											<?php echo $belong_to;?>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['dara_type_name']?></label>

										<div class="col-sm-9">
											<!-- <input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['sex']?>" id="sex" name="sex"> -->
											<select class="form-control" id="dara_type_id" name="dara_type_id">
<?php 
				$alltype = count($dara_type);
				if($dara_type)
						for($i = 0; $i < $alltype; $i++){
									echo '<option value="'.$dara_type[$i]['dara_type_id_map'].'">'.$dara_type[$i]['dara_type_name'].'</option>';
						}
?>
											</select>

										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['sex']?></label>

										<div class="col-sm-9">
											<!-- <input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['sex']?>" id="sex" name="sex"> -->
											<select class="form-control" id="gender" name="gender">
													<option value="m"><?php echo $language['male']?></option>
													<option value="f"><?php echo $language['female']?></option>
											</select>

										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['birthday']?></label>
										<div class="col-sm-9">
																<div class="input-group">
																	<input class="form-control date-picker" id="id-date-picker-1" name="birth_date" type="text" data-date-format="dd-mm-yyyy" readonly />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
																</div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"></label>
										<div class="col-sm-9">
												<code>format date = DD-MM-YYYY (ปีให้ใส่เป็น ค.ศ.)</code>
										</div>
									</div>

									<!-- ภูมิลำเนา  -->
									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['address']?></label>

										<div class="col-sm-9">
												<textarea placeholder="<?php echo $language['address']?>" id="address" name="address" class="form-control"></textarea>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['weight']?></label>

										<div class="col-sm-9">
											<input type="text" class="input-mini spinner-input form-control spinner" id="weight" name="weight" maxlength="3"  value="">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['height']?></label>

										<div class="col-sm-9">
											<input type="text" class="input-mini spinner-input form-control spinner" id="height" name="height" maxlength="3"  value="">
										</div>
									</div>


									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['education']?></label>

										<div class="col-sm-9">
											<!-- <input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['sex']?>" id="sex" name="sex"> -->
											<select class="form-control" id="form-field-select-2" name="education">
												<option value=""> - </option>
												<option value="1"><?php echo $language['grade1']?></option>
												<option value="2"><?php echo $language['grade2']?></option>
												<option value="3"><?php echo $language['grade3']?></option>
												<option value="4"><?php echo $language['grade4']?></option>
											</select>

										</div>
									</div>


								<div class="form-group">
									<!-- EDITOR 2 -->
									
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['hobby']?>
										</label>

										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea cols="80" id="hobby" name="hobby" rows="10"></textarea>
													<?php echo display_ckeditor($ckeditor); ?>
												</div>
										</div>
								</div>

								<div class="form-group">
								<!-- EDITOR 3 -->
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['profile']?></label>
										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea cols="80" id="profile_background" name="profile_background" rows="10"></textarea>
													<?php echo display_ckeditor($ckeditor2); ?>
												</div>
										</div>
								</div>

								<div class="form-group">
								<!-- EDITOR 4 -->
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['portfolio']?></label>

										<div class="col-sm-9">
											<div id="fullToolbar">											
												<textarea cols="80" id="portfolio" name="portfolio" rows="10"></textarea>
												<?php echo display_ckeditor($ckeditor3); ?>
											</div>
										</div>
								</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['lastportfolio']?></label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="<?php echo $language['lastportfolio']?>" id="last_portfolio" name="last_portfolio">
										</div>
									</div>

							<div class="form-group">
									<label for="form-field-facebook" class="col-sm-3 control-label no-padding-right">Facebook</label>

									<div class="col-sm-9">
											<span class="input-icon">
													<input type="text" id="form-field-facebook" name="facebook" placeholder="Full URL" class="col-xs-12 col-sm-12" value="">
													<i class="ace-icon fa fa-facebook blue"></i>
											</span>
									</div>
							</div>
							<div class="form-group">
									<label for="form-field-twitter" class="col-sm-3 control-label no-padding-right">Twitter</label>

									<div class="col-sm-9">
											<span class="input-icon">
													<input type="text" id="form-field-twitter" name="twitter" placeholder="Full URL" class="col-xs-12 col-sm-12" value="">
													<i class="ace-icon fa fa-twitter light-blue"></i>
											</span>
									</div>
							</div>

							<div class="form-group">
									<label for="form-field-instragram" class="col-sm-3 control-label no-padding-right">Instagram</label>

									<div class="col-sm-9">
											<span class="input-icon">
													<input type="text" id="form-field-instragram" name="instagram" placeholder="Full URL" class="col-xs-12 col-sm-12" value="">
													<i class="ace-icon fa fa-instagram" style="color:#9c6b53;"></i>
											</span>
									</div>
							</div>

							<div class="form-group">
									<label for="form-field-googleplus" class="col-sm-3 control-label no-padding-right">Google+</label>

									<div class="col-sm-9">
											<span class="input-icon">
													<input type="text" id="form-field-googleplus" name="googleplus" placeholder="Full URL" class="col-xs-12 col-sm-12" value="">
													<i class="ace-icon fa fa-google-plus-square red"></i>
											</span>
									</div>
							</div>

							<div class="form-group">
									<label for="form-field-gplus" class="col-sm-3 control-label no-padding-right">Youtube</label>

									<div class="col-sm-9">
											<span class="input-icon">
													<input type="text" id="form-field-gplus" name="youtube_channel" placeholder="Full URL" class="col-xs-12 col-sm-12"value="">
													<i class="ace-icon fa fa-youtube red"></i>
											</span>
									</div>
							</div>

							<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['credit']?></label>
										<div class="col-sm-9">
											<select class="chosen-select" id="credit_id" name="credit_id">
<?php 
				echo '<option value=""> --- '.$language['please_select'].' --- </option>';

				$alllist = count($credit_list);
				if($credit_list)
						for($i = 0; $i < $alllist; $i++){
									$credit_name = $credit_list[$i]['credit_name'];

									//if($credit_list[$i]['status'] == 1) 
									echo '<option value="'.$credit_list[$i]['credit_id'].'">'.$credit_name.'</option>';

						}
?>
											</select>
										</div>
							</div>


								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>

										<div class="col-xs-3">
													<label>
														<input name="status" id="cat_status" class="ace ace-switch ace-switch-6" type="checkbox" value=1 checked />
														<span class="lbl"></span>
													</label>
												</div>
										</div>
								</div>

									<div style="clear: both;"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn btn-info"  id="form_submit">
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

		$('#enable9').click(function( ) {
				//alert($(this).attr('id'));
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

		$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true
		})

		$('.spinner').ace_spinner({value:0,min:0,max:200,step:1, btn_up_class:'btn-info' , btn_down_class:'btn-info'})
				.on('change', function(){
					//alert(this.value)
		});

		$('#avatar').ace_file_input({
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
});

function chkform(){
		var dara_type_id = document.DaraForm.dara_type_id.value;

		if(dara_type_id > 0){

				if(document.getElementById("pen_name").value == ''){

					alert('กรุณาใส่ <?php echo $language['pen_name']?> ด้วย');
					document.DaraForm.pen_name.focus();

				}else if(document.getElementById("first_name").value == ''){

					alert('กรุณาใส่ <?php echo $language['first_name']?> ด้วย');
					document.DaraForm.first_name.focus();
					//document.getElementById("first_name").focus();	

				}else if(document.getElementById("last_name").value == ''){

					alert('กรุณาใส่ <?php echo $language['last_name']?> ด้วย');
					document.DaraForm.last_name.focus();

				}else if(document.getElementById("nick_name").value == ''){

					alert('กรุณาใส่ <?php echo $language['nickname']?> ด้วย');
					document.DaraForm.nick_name.focus();

				}else{
					$('#form_submit').attr('disabled', 'disabled');
					document.DaraForm.submit();
				}

		}else{
				alert('<?php echo $language['please_select_cat']?>');
				document.getElementById("dara_type_id").focus();	
		}
}

</script>