<?php 
	$language = $this->lang->language;
	echo css_asset('font-awesome2.css');
?>
<style type="text/css">
<?php 
	if($this->session->userdata('admin_id') != 1){
?>
#alertorder{display:none;}	
#nestable-output{display:none;}	
<?php 
	}
?>
.tags{width: 80%;}
</style>
<div class="row">
	<div class="col-xs-12">
			<div class="row">

									<div class="page-header">
										<h1>
											<?php echo $language['news'] ?>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['edit'] ?>
											</small>
										</h1>
									</div>

									<div class="col-xs-12">
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

	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open_multipart('news/save', $attributes);

				if(function_exists('Debug')){
					//Debug($news_list);
				}
				
				$start_date = $expire_date = $pin_start_date = $pin_expire_date = '';
				
				echo '<input type="hidden" name="news_id" value="'.$news_list[0]['news_id2'].'">';
				echo '<input type="hidden" name="news_id_en" value="'.$news_list[0]['news_id'].'">';
				echo '<input type="hidden" name="news_id_th" value="'.$news_list[1]['news_id'].'">';
				
				if(trim($news_list[0]['start_date']) != '' && $news_list[0]['start_date'] != '0000-00-00') $start_date = DisplayDateRange($news_list[0]['start_date']); 
				if(trim($news_list[0]['expire_date']) != '' && $news_list[0]['expire_date'] != '0000-00-00') $expire_date = DisplayDateRange($news_list[0]['expire_date']); 

				if(trim($news_list[0]['pin_start_date']) != '' && $news_list[0]['pin_start_date'] != '0000-00-00') $pin_start_date = DisplayDateRange($news_list[0]['pin_start_date']); 
				if(trim($news_list[0]['pin_expire_date']) != '' && $news_list[0]['pin_expire_date'] != '0000-00-00') $pin_expire_date = DisplayDateRange($news_list[0]['pin_expire_date']); 
				// #mm/dd/yyyy
?>
			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['pin']?></label>
					<div class="col-sm-9">
							<select class="form-control" id="pin" name="pin">
							<option value="0" ><?php echo $language['pin']?></option>
<?php
							for($i=1;$i<=10;$i++){
									$sel = ($news_list[0]['pin'] == $i) ? 'selected' : '';
									echo '<option value="'.$i.'" '.$sel.'>'.$language['position'].' '.$i.'</option>';
							}
?>
							</select>
					</div>
			</div>


			<div class="form-group">
						<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['display_date']?></label>
						<div class="col-sm-6">
						<fieldset id="pin_set">
							<!-- <legend><?php echo $language['display_date']?>:</legend> -->
								<div class="input-group">
											<input type="text" id="pin-startdate-time" class="input-sm form-control" name="pin_start_date"  value="<?php echo ($pin_start_date != "00/00/0000 00:00:00") ? $pin_start_date : '' ?>" />
											<span class="input-group-addon">
													<i class="fa fa-exchange fa-clock-o bigger-110"></i>
											</span>
											<input type="text" id="pin-enddate-time" class="input-sm form-control" name="pin_expire_date" value="<?php echo ($pin_expire_date != "00/00/0000 00:00:00") ? $pin_expire_date : '' ?>"  />
								</div>
								<code><i class="menu-icon fa fa-info"></i> Date Format MM/DD/YYY h:i:s</code>
						</fieldset>
						</div>
			</div>

			<hr>

			<!-- #section:elements.form -->
			<div class="form-group">
						<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['category']?></label>
						<div class="col-sm-9">
								<?php echo $category_list?>
						</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['subcategory']?></label>
					<div class="col-sm-9">
							<!-- <select class="form-control" id="subcategory_id" name="subcategory_id"></select> -->
							<?php echo $subcategory_list?>
					</div>
			</div>


			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['sex']?></label>
					<div class="col-sm-9">
							<select class="form-control" id="gender" name="gender">
									<option value="m" <?php if($news_list[0]['gender'] == 'm') echo 'selected' ?>><?php echo $language['male']?></option>
									<option value="f" <?php if($news_list[0]['gender'] == 'f') echo 'selected' ?>><?php echo $language['female']?></option>
							</select>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['dara']?></label>
					<div class="col-sm-3">
						<a href="<?php echo site_url('dara/add'); ?>" target=_blank>
						<i class="ace-icon glyphicon glyphicon-plus"></i>
						<?php echo $language['add'].' '.$language['dara']?></a>
					</div>
					<div class="col-sm-9">
							<select class="chosen-select" id="dara_id" name="dara_id">
<?php 
				echo '<option value=""> --- '.$language['please_select'].' --- </option>';

				$alllist = count($dara_list);
				if($dara_list)
						for($i = 0; $i < $alllist; $i++){
									$dara_name = $dara_list[$i]['nick_name'].' '.$dara_list[$i]['first_name'].' '.$dara_list[$i]['last_name'];
									if($dara_list[$i]['dara_profile_id'] == $news_list[0]['dara_id']) echo '<option value="'.$dara_list[$i]['dara_profile_id'].'" selected>'.$dara_name.'</option>';
									else echo '<option value="'.$dara_list[$i]['dara_profile_id'].'">'.$dara_name.'</option>';
						}
?>
							</select>
					</div>
			</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (EN) </label>
										<div class="col-sm-9">
											<input value="<?php echo $news_list[0]['title']?>" type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="title_en" name="title_en" maxlength="255">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (TH) </label>
										<div class="col-sm-9">
											<input value="<?php echo $news_list[1]['title']?>" type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="title_th" name="title_th" maxlength="255">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['highlight']?> </label>
										<div class="col-sm-9">
											<?php //Debug($highlight); ?>
											<input type="checkbox" name="highlight" <?php if((isset($highlight[0])) && ($highlight[0]->news_highlight_id > 0)) echo "checked" ?> >
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Keyword</label>
										<div class="col-sm-9">
											<?php echo $tags_list?>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['display_date']?></label>
										
										<div class="col-sm-9">
											<!-- <div class="input-daterange input-group">
												<input type="text" class="input-sm form-control"  name="start_date"  value="<?php //echo $start_date?>" />
												<span class="input-group-addon">
														<i class="fa fa-exchange"></i>
												</span>
												<input type="text" class="input-sm form-control" name="expire_date"  value="<?php //echo $expire_date?>" />
											</div> -->

											<div class="input-group">
													<input type="text" id="startdate-time" class="input-sm form-control" name="start_date" value="<?php echo ($start_date != "00/00/0000 00:00:00") ? $start_date : '' ?>" />
													<span class="input-group-addon">
															<i class="fa fa-exchange fa-clock-o bigger-110"></i>
													</span>
													<input type="text" id="enddate-time" class="input-sm form-control" name="expire_date" value="<?php echo ($expire_date != "00/00/0000 00:00:00") ? $expire_date : '' ?>"  />
											</div>
											<code><i class="menu-icon fa fa-info"></i> Date Format MM/DD/YYY h:i:s</code>
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

									$selected = ($credit_list[$i]['credit_id'] == $news_list[0]['credit_id']) ? 'selected' : '';
									echo '<option value="'.$credit_list[$i]['credit_id'].'" '.$selected.'>'.$credit_name.'</option>';

						}
?>
											</select>
										</div>
									</div>


								<div class="form-group">
									<!-- EDITOR 2 -->
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['shorttitle']?> (EN) 
										</label>

										<div class="col-sm-9">
												<div id="currentToolbar" style="display: none">
													<h2 class="samples">Current toolbar configuration</h2>
													<p>Below you can see editor with current toolbar definition.</p>
													<textarea cols="80" id="editorCurrent" name="editorCurrent" rows="10"></textarea>
												</div>

												<div id="fullToolbar">											
													<textarea cols="80" id="editorFull" name="description_en" rows="10"><?php echo $news_list[0]['description']?></textarea>
												</div>
										</div>
								</div>

								<div class="form-group">
									<!-- EDITOR 2 -->
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['shorttitle']?> (TH) 
										</label>
										<div class="col-sm-9">
												<div id="currentToolbar" style="display: none">
													<h2 class="samples">Current toolbar configuration</h2>
													<p>Below you can see editor with current toolbar definition.</p>
													<textarea cols="80" id="editorh2" name="editorCurrent2" rows="10"></textarea>
												</div>

												<div id="fullToolbar">											
													<textarea cols="80" id="editorFull2" name="description_th" rows="10"><?php echo $news_list[1]['description']?></textarea>
												</div>
										</div>
								</div>

								<div class="form-group">
								<!-- EDITOR 3 -->
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['detail']?> (EN) </label>
										<div class="col-sm-9">
												<div id="currentToolbar" style="display: none">
													<h2 class="samples">Current toolbar configuration</h2>
													<p>Below you can see editor with current toolbar definition.</p>
													<textarea cols="80" id="editorh3" name="editorh3" rows="10"></textarea>
												</div>

												<div id="fullToolbar">											
													<textarea cols="80" id="editorFull3" name="detail_en" rows="10"><?php echo $news_list[0]['detail']?></textarea>
												</div>
										</div>
								</div>

								<div class="form-group">
								<!-- EDITOR 4 -->
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['detail']?> (TH) </label>
										<div class="col-sm-9">
												<div id="currentToolbar" style="display: none">
													<h2 class="samples">Current toolbar configuration</h2>
													<p>Below you can see editor with current toolbar definition.</p>
													<textarea cols="80" id="editorh4" name="editorh4" rows="10"></textarea>
												</div>

												<div id="fullToolbar">											
													<textarea cols="80" id="editorFull4" name="detail_th" rows="10"><?php echo $news_list[1]['detail']?></textarea>
												</div>
										</div>
								</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"> <?php echo $language['search for tags news'] ?> </label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="search_news1" maxlength="255">
											<button class="btn btn-info btn-sm" type="button"><i class="ace-icon glyphicon glyphicon-search"></i></button>
										</div>
										<div class="col-sm-12" id="newslist_tags"></div>
								</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"> <?php echo $language['search for tags dara'] ?> </label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['dara']?>" id="search_dara" maxlength="255">
											<button class="btn btn-info btn-sm" type="button"><i class="ace-icon glyphicon glyphicon-search"></i></button>
										</div>
										<div class="col-sm-12" id="daralist_tags"></div>
								</div>

								<!-- <div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
										<div class="col-sm-9">
											<div class="col-xs-12">
								<?php 
								/*if($picture_list){
?>
													<div id='picture_use'>
															<span class="profile-picture">
																	<img class="editable img-responsive" alt="<?=$picture_list[0]['caption']?>" id="picture_list" src="
																	<?php echo base_url().'uploads/news/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name'] ?>" />
															</span>
															<a class="red" href="javascript:void(0);" id="bootbox-confirm" title="Delete" data-value="<?php echo $picture_list[0]['picture_id'] ?>" data-img="<?php echo 'uploads/news/'.$picture_list[0]['folder'].'/'.$picture_list[0]['file_name']?>"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
													</div>
<?
								}*/
								?>
													<div id="upload_avatar"><input type="file" id="picture_news" name="picture_news" /></div>
													<?php //echo $language['upload_max_filesize'].' = '.ini_get('upload_max_filesize');?>

											</div>
										</div>
								</div> -->


					<div style="clear: both;"></div>
					<div class="page-header">
							<h1><?php echo $language['news'] ?>
								<small><i class="ace-icon fa fa-angle-double-right"></i> Relate</small>
							</h1>
					</div>

					<div class="alert alert-success" id="alertorder">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon fa fa-check"></i>
											</strong><span id="msg">Init and Update</span>
									<br>
					</div>

						<div class="row">

<?php 
if(!isset($mobile)){
		//***********************************$relate_list*******************
		echo $emptyarr = '<pre id = "nestable-output"></pre>';
		//Debug($emptyarr); 
		//Debug($relate_list); 

		$icondel = '<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm-relate" data-value="" data-name="">
										<i class="ace-icon fa fa-trash-o bigger-130"> '.$language['del relate'].'</i>
							</a>';
		echo $icondel;
?>
								<div class="col-sm-1">
								<?php
								if($relate_list)
										for($i=1;$i<=count($relate_list);$i++){
												echo '<div class="alert alert-info" style="width:100%;height:71px;margin: 1px 0px 5px 0px;" >'.$i.'</div>';
										}
								?>								
								</div>
								<div class="col-sm-6">
										<div class="dd" id="nestable">
											<ol class="dd-list" width="100%">
											<?php
												$sticker = '
														<span class="sticker">
															<span class="label label-success arrowed-in">
																<i class="ace-icon fa fa-check bigger-110"></i>
															</span>
														</span>';
												$showsticker = '';


												if($relate_list)
														for($i=0;$i<count($relate_list);$i++){

															$newsrelate_id = $relate_list[$i]->news_id2;
															$order = $relate_list[$i]->order;
															$title = DisplayTxt($relate_list[$i]->title, 60);
															$lastupdate_date = $relate_list[$i]->lastupdate_date;

															if($relate_list[$i]->file_name != ''){
																$img_src = base_url('uploads/thumb').'/'.$relate_list[$i]->folder.'/'.$relate_list[$i]->file_name;
																$display_img = (file_exists('uploads/thumb/'.$relate_list[$i]->folder.'/'.$relate_list[$i]->file_name)) ? "<img src=".$img_src." height='50'>" : "";
																//$display_img = $relate_list[$i]->file_name;
																$css_tag = 'style="width:78%;"';
															}else{
																$display_img = '';
																$css_tag = 'style="width:78%;margin-left: 90px;"';
															}

															$icondel = '<a class="red del-confirm" href="#" id="bootbox-confirm'.$newsrelate_id.'" data-value="" data-name="'.$title.'">
																				<i class="ace-icon fa fa-trash-o bigger-130" data-rel="tooltip" title="'.$language['delete'].' '.$title.'"></i>
																		</a>';
															$icondel = '';
																			
															echo '
															<li class="dd-item" data-id="'.$newsrelate_id.'" value="'.$order.'" data="'.$order.'">
																<div class="dd-handle" style="width:78%" >
																		'.$display_img.'
																		<div class="tags" '.$css_tag.'>
																			<span class="label-holder"><span class="label label-warning arrowed-in">'.$title.' ID : '.$newsrelate_id.'</span></span><br>
																			<span class="label-holder"><span class="label">'.$language['lastupdate'].' '.$lastupdate_date.'</span></span>'.$showsticker.'
																		</div>
																</div>
																'.$icondel.'																
															</li>';
														}
											?>
											</ol>
										</div>

								</div>
<?php } ?>

								<div class="col-sm-5">
								<?php //Debug($relate_list) ?>


									<div class="col-xs-12 col-sm-643 widget-container-col ui-sortable">
										<div class="widget-box ui-sortable-handle">
											<div class="widget-header">
												<h5 class="widget-title smaller">Relate News</h5>

												<div class="widget-toolbar">
													<!-- <span class="label label-success">
														16%
														<i class="ace-icon fa fa-arrow-up"></i>
													</span> -->
												</div>
											</div>

<?php
								$relate_box = '';
								if($relate_list)
										for($i=0;$i<count($relate_list);$i++){
												$newsrelate_id = $relate_list[$i]->news_id2;
												$title = DisplayTxt($relate_list[$i]->title, 70);
												$order = $relate_list[$i]->order;
												$lastupdate_date = $relate_list[$i]->lastupdate_date;
												$file_name = $relate_list[$i]->file_name;
												$folder = $relate_list[$i]->folder;

												if($i == 0) $relate_box .= $newsrelate_id;
												else $relate_box .= ", ".$newsrelate_id;

												if($file_name != ''){
														$img_src = base_url('uploads/thumb').'/'.$folder.'/'.$file_name;
														$display_img = (file_exists('uploads/thumb/'.$folder.'/'.$file_name)) ? "<img src=".$img_src." height='50'>" : "";
														//$css_tag = '';
												}else{
														$display_img = '';
														//$css_tag = 'style="margin-left: 75px;"';
												}

												$icondel = '<a class="red del-confirm" href="javascript:void(0);" id="del-relate'.$newsrelate_id.'" data-value="'.$newsrelate_id.'" data-name="'.$title.'">
																				<i class="ace-icon fa fa-trash-o bigger-130" data-rel="tooltip" title="'.$language['delete'].' '.$title.'"></i>
																		</a>';

												//echo '<button class="btn" type="button">'.$newsrelate_id.'. '.$title.'</button>';
												/*echo '
												<div class="dd-item" style="clear:both;">
													<input type="text" name="orderid[]" value="'.$order.'" class="col-xs-1" >
													<input type="hidden" name="relate_id[]" value="'.$newsrelate_id.'" title="'. $newsrelate_id .'">'.$title .'<br>Last Update : '.$lastupdate_date.'
												</div>';*/
												//<img src="'. base_url().$file_name.'" '.$attr_width.'/>

														/*<!-- <button class="btn btn-xs btn-danger"> -->
														<i class="ace-icon fa fa-times"></i>
														<span class="bigger-110">Delete</span>
														<!-- </button> -->*/
												echo '
												<div class="widget-body" id="widget'. $newsrelate_id .'">
													<div class="widget-main padding-6">
														<div class="alert alert-info"> 
															<input type="text" name="orderid[]" value="'.$order.'" class="col-xs-1" style="text-align: center;">
															'.$display_img.'
															<input type="hidden" name="relate_id[]" value="'.$newsrelate_id.'" title="'. $newsrelate_id .'">'.$title .'<br>Last Update : '.$lastupdate_date.' 
															'.$icondel.'
														</div>
													</div>
												</div>';

										}
										//echo $relate_box ;


?><!-- <input value="<?php echo $relate_box ?>" type="text" class="col-xs-10 col-sm-6" name = "relate_box"  id = "relate_box" maxlength="255"> -->
											<div class="widget-body" id="relate_more"></div>

											<button class="btn btn-info" type="submit">
													<i class="ace-icon fa fa-check bigger-110"></i>
													<?php echo $language['save_relate']?>
											</button>
										</div>
									</div>


								</div>
						</div>

						<!-- Relate -->
						<div class="form-group">
							<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"> <?php echo $language['search for relate'] ?> </label>
							<div class="col-sm-9">
									<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="search_news2" data-value="<?php echo $news_list[0]['news_id2']?>" maxlength="255">
									<button class="btn btn-info btn-sm" type="button"><i class="ace-icon glyphicon glyphicon-search"></i></button>
							</div>
							<div class="col-sm-12" id="newslist_relate"></div>
						</div>


						<div class="form-group">
								<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
								<div class="col-xs-3">
										<label>
											<input name="status" id="cat_status" class="ace ace-switch ace-switch-4" type="checkbox" value=1 <?php if($news_list[0]['status'] == 1) echo "checked";?> />
											<span class="lbl"></span>
										</label>
								</div>
						</div>



						<div style="clear: both;"></div>
								<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn btn-info">
												<i class="ace-icon fa fa-check bigger-110"></i> Submit
											</button>
											&nbsp; &nbsp; &nbsp;
											<button type="reset" class="btn">
												<i class="ace-icon fa fa-undo bigger-110"></i> Reset
											</button>
										</div>
								</div>
						</div>
<?php echo form_close();?>
				</div>
		</div>
</div>

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );
<?php 

		//if($picture_list){ echo "$('#upload_avatar').attr('style', 'display:none');\n";	} 
		if($news_list[0]['pin'] == 0){ echo "$('#pin_set').attr('disabled', 'disabled');\n";	} 

?>
		$('#bootbox-confirm').click(function( ) {
				var v = $(this).attr('data-value');
				var img = $(this).attr('data-img');
				//alert(v);
				$.ajax({
						type: 'POST',
						url: "<?php echo base_url() ?>picture/remove_img/" + v,
						data : { img : img, v : v},
						cache: false,
						success: function(data){
								//alert(data);
								if(data = 'Yes'){
										$('#picture_use').attr('style', 'display:none');
										$('#upload_avatar').attr('style', 'display:block');
								}
						}
				});
		}); 

		$('#category_id').change(function( ) {				
				var catid = $(this).val();
				$('#subcategory_id').load('<?php echo base_url() ?>subcategory/list_dd/' + catid);
		});

		$('#bootbox-confirm-relate').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
						if(result) {
								window.location='<?php echo base_url("news/delrelate")."/".$news_list[0]["news_id2"] ?>' ;
						}
					});
		});

<?php

		if($relate_list)
				for($i=0;$i<count($relate_list);$i++){	

					$newsrelate_id = $relate_list[$i]->news_id2;
?>
					$('#del-relate<?php echo $newsrelate_id?>').on('click', function() {
								var v = $(this).attr('data-value');
								var name = $(this).attr('data-name');
								bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
									if(result) {
											$.ajax({
													type: 'POST',
													url: "<?php echo base_url('news/DelRelateID')?>",
													data: {id: v, name : name, newid : <?php echo $news_list[0]['news_id2']?>},
													cache: false,
													success: function(data){
															//$("#alertorder").fadeIn();
															//$("#msg").html(data);
															$('#widget<?php echo $newsrelate_id?>').css('display','none');

													}
											});

									}
								});
					});
<?php
				}
?>
		$('.add_relate').on('click', function() {
					var v = $(this).attr('data-value');
					//var name = $(this).attr('data-name');
					alert(v);
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
		});

		$('#picture_news').ace_file_input({
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

		/*$('.chosen-select').chosen({allow_single_deselect:true}); 
				//resize the chosen on window resize
				$(window)
				.off('resize.chosen')
				.on('resize.chosen', function() {
					$('.chosen-select').each(function() {
						 var $this = $(this);
						 $this.next().css({'width': $this.parent().width()});
					})
		}).trigger('resize.chosen');*/
			
		/*$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
					 else $('#form-field-select-4').removeClass('tag-input-style');
		});*/

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

		//$('.dd').nestable();
			
		/*$('.dd-handle a').on('mousedown', function(e){
			e.stopPropagation();
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

		//or you can activate the chosen plugin after modal is shown
		//this way select element becomes visible with dimensions and chosen works as expected
		$('#modal-form').on('shown', function () {
				$(this).find('.modal-chosen').chosen();
		})
});

function UpdateRelate(json){
		
		//alert(json);
			$.ajax({
					type: 'POST',
					url: "<?php echo base_url('news/set_order_relate')?>",
					data: {json: json, newsid : <?php echo $news_list[0]['news_id2']?>},
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

		$('#pin').on('change', function(e){
				var v = $(this).val();
				if(v > 0){
					$('#pin_set').removeAttr('disabled');
					$('#pin-startdate-time').val('');
					$('#pin-enddate-time').val('');
				}else
					$('#pin_set').attr('disabled', 'disabled');
		});

		$('#search_dara').on('change', function(e){

				var v = $(this).val();
				$('#daralist_tags').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
				$.ajax({
						type: 'GET',
						url: "<?php echo base_url('dara/search')?>",
						data: {kw : v},
						cache: false,
						success: function(data){
								$('#daralist_tags').html(data);

						}
				});
		});

		$('#search_news1').on('change', function(e){

				var v = $(this).val();
				$('#newslist_tags').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');
				$.ajax({
						type: 'GET',
						url: "<?php echo base_url('news/search')?>",
						data: {kw : v},
						cache: false,
						success: function(data){

								$('#newslist_tags').html(data);

								//$('#newslist_tags').html($(this).val());
								//$("#alertorder").fadeIn();
								//$("#msg").html(data);

						}
				});
		});

		$('#search_news2').on('change', function(e){

				var v = $(this).val();
				var newsid = $(this).attr('data-value');

				$('#newslist_relate').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Loading...');

				$.ajax({
						type: 'GET',
						url: "<?php echo base_url('news/search_relate')?>",
						data: {kw : v, newsid : newsid},
						cache: false,
						success: function(data){
								$('#newslist_relate').html(data);
						}
				});
		});
</script>

<script src="<?php echo base_url()?>/theme/assets/ckeditor/editor.js" language="javascript" type="text/javascript" ></script>
