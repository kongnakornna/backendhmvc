<?php 
	$language = $this->lang->language;
	echo css_asset('font-awesome2.css');

	if($this->input->get('success')) $success = $this->input->get('success');

	$display_en = "style='display:none;'";
	$newcount = count($news_list);
	//echo "newcount = $newcount<br>";
	//Debug($news_list);
	//die();

	$news_id = $news_list[0]['news_id2'];
	$category_id = $news_list[0]['category_id'];
	$subcategory_id = $news_list[0]['subcategory_id'];

	$category_name = (count($news_list)>1) ? RewriteTitle($news_list[1]['category_name']) : RewriteTitle($news_list[0]['category_name']);
	$subcategory_name = (count($news_list)>1) ? RewriteTitle($news_list[1]['subcategory_name']) : RewriteTitle($news_list[0]['subcategory_name']);
	$title = (count($news_list)>1) ? RewriteTitle($news_list[1]['title']) : RewriteTitle($news_list[0]['title']);
	//$subcategory_name = RewriteTitle($news_list[1]['subcategory_name']);
	//$title  = RewriteTitle($news_list[1]['title']);

	$shear = $this->config->config['www']."/news/".$category_id."/".$subcategory_id."/".$news_id;

	//$previewurl = $this->config->config['www']."/news/".$category_id."/".$subcategory_id."/".$news_id."/".$category_name."/".$subcategory_name."/".$title."?preview=1";
	//$previewurl = $this->config->config['www']."/news/".$category_id."/".$subcategory_id."/".$news_id."/?preview=1";
	$previewurl = $shear."/?preview=1";

	//Debug($previewurl);

	if($newcount > 1){
			if($news_list[0]['lang'] == 'en'){

					$news_id_en = $news_list[0]['news_id'];
					$news_id_th = $news_list[1]['news_id'];
					$news_title_en = $news_list[0]['title'];
					$news_title_th = $news_list[1]['title'];

					$shear_title = shear_title($news_list[0]['title']);

			}else{

					$news_id_en = $news_list[1]['news_id'];
					$news_id_th = $news_list[0]['news_id'];
					$news_title_en = $news_list[1]['title'];
					$news_title_th = $news_list[0]['title'];

					$shear_title = shear_title($news_list[0]['title']);
			}
	}else{
			$news_id_th = $news_list[0]['news_id'];
			$news_title_th = $news_list[0]['title'];

			$shear_title = shear_title($news_list[0]['title']);
	}

?>
<style type="text/css">
#countitle, #countitle2 {style="margin-left: 4px; color: rgb(83, 95, 222);"}
<?php 
	if($this->session->userdata('admin_id') != 1){
?>
#alertorder{display:none;}	
#nestable-output{display:none;}	
<?php 
	}
?>
.tags{width: 80%;}
.reset_news, .sentmail{display:none;}
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

				<div style="clear: both;"></div>
						<div class="clearfix form-actions">
								<div class="col-md-offset-1 col-md-12 col-sm-12">

										<button type="button" class="btn btn-purple bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>">
											<i class="ace-icon fa fa-search-plus bigger-110" ></i> <?php echo $language['preview'] ?>
										</button>
<?php
		if($news_list[0]['approve'] == 0){
				$approve = 0;
?>
											<button type="button" class="btn btn-success admin_approve" id="admin_approve1">
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
											<button type="button" class="btn btn-info submit_news" id="submit_news1">
												<i class="ace-icon fa fa-floppy-o bigger-110"></i> Submit
											</button>

											<button type="button" class="btn reset_news" id="reset_news1">
												<i class="ace-icon fa fa-undo bigger-110"></i> Reset
											</button>

											<button type="button" class="btn btn-success gen_json" <?php if($approve == 0) echo "disabled" ?>>
												<i class="ace-icon glyphicon glyphicon-file bigger-110"></i> <?php echo $language['generate_catch']?>
											</button>

<?php
		if($news_list[0]['approve'] == 1){
?>
											<a href="javascript:void(0);" class="twitter-share-button" data-url="<?php echo $shear ?>" data-text="<?php echo $shear_title?>" data-via="Siamdara" data-dnt="true">
											<button type="button" class="btn btn-info share" >
												<i class="middle ace-icon fa fa-twitter-square white"> ทวีต</i>
											</button>
											</a>	
<?php
		}
?>										

<?php
		if($news_list[0]['approve'] == 0){
?>
											<button type="button" class="btn btn-pink sentmail" id="sentmail1">
												<i class="ace-icon fa fa-envelope bigger-110"></i> <?php echo $language['sentmail_manager']?>
											</button>
<?php
		}
?>
										</div>
								</div>
						</div>


						<div class="col-xs-12">

<?php
	if(isset($success)){
?>
							<div class="alert alert-success">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon glyphicon glyphicon-ok"></i>
											</strong><?php echo $success?>.
									<br>
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

	$attributes = array('class' => 'form-horizontal', 'id' => 'jform',  'name' => 'NewsForm');
	echo form_open_multipart('news/save', $attributes);

				if(function_exists('Debug')){
					//Debug($news_list);
				}

				$news_highlight_id = $news_list[0]['news_highlight_id'];
				$megamenu_id = $news_list[0]['megamenu_id'];
				$start_date = $expire_date = $pin_start_date = $pin_expire_date = '';
				


				echo '<input type="hidden" name="news_id" value="'.$news_list[0]['news_id2'].'">';
				if(isset($news_id_en)) echo '<input type="hidden" name="news_id_en" value="'.$news_id_en.'">';
				echo '<input type="hidden" name="news_id_th" value="'.$news_id_th.'">';
				
				if(trim($news_list[0]['start_date']) != '' && $news_list[0]['start_date'] != '0000-00-00') $start_date = DisplayDateRange($news_list[0]['start_date']); 
				if(trim($news_list[0]['expire_date']) != '' && $news_list[0]['expire_date'] != '0000-00-00') $expire_date = DisplayDateRange($news_list[0]['expire_date']); 

				if(trim($news_list[0]['pin_start_date']) != '' && $news_list[0]['pin_start_date'] != '0000-00-00') $pin_start_date = DisplayDateRange($news_list[0]['pin_start_date']); 
				if(trim($news_list[0]['pin_expire_date']) != '' && $news_list[0]['pin_expire_date'] != '0000-00-00') $pin_expire_date = DisplayDateRange($news_list[0]['pin_expire_date']); 

				$pic_gallery = site_url('news/picture/'.$news_list[0]['news_id2']);
				//#mm/dd/yyyy
?>
			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['pin']?></label>
					<div class="col-sm-9">
							<select class="form-control" id="pin" name="pin">
							<option value="0" ><?php echo $language['pin']?></option>
<?php
							for($i=1;$i<=15;$i++){
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
											<input type="text" id="pin-enddate-time" class="input-sm form-control" name="pin_expire_date" value="<?php echo ($pin_expire_date != "00/00/0000 00:00:00") ? $pin_expire_date : '' ?>" />
								</div>
								<code><i class="menu-icon fa fa-info"></i> ใส่เมื่อต้องการกำหนดวันแสดง และ สิ้นสุด Date Format MM/DD/YYY h:i:s</code>
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


			<!-- <div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['sex']?></label>
					<div class="col-sm-9">
							<select class="form-control" id="gender" name="gender">
									<option value="m" <?php if($news_list[0]['gender'] == 'm') echo 'selected' ?>><?php echo $language['male']?></option>
									<option value="f" <?php if($news_list[0]['gender'] == 'f') echo 'selected' ?>><?php echo $language['female']?></option>
							</select>
					</div>
			</div> -->

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
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['display_vdo']?> </label>
										<div class="col-sm-9">
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" name="icon_vdo" <?php if($news_list[0]['icon_vdo'] > 0) echo "checked='checked'" ?>>
														<span class="lbl"> <?php echo $language['use']?></span>
													</label>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['highlight']?> </label>
										<div class="col-sm-9">												
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" name="highlight" <?php if($news_highlight_id > 0) echo "checked='checked'" ?> >
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
														<input type="checkbox" class="ace" name="megamenu" <?php if($megamenu_id > 0) echo "checked='checked'" ?> >
														<span class="lbl"> <?php echo $language['use']?></span>
													</label>
												</div>
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
											<?php echo $credit_list?>
											<!-- <select class="chosen-select" id="credit_id" name="credit_id"> -->
<?php 
				/*echo '<option value=""> --- '.$language['please_select'].' --- </option>';

				$alllist = count($credit_list);
				if($credit_list)
						for($i = 0; $i < $alllist; $i++){
									$credit_name = $credit_list[$i]['credit_name'];

									$selected = ($credit_list[$i]['credit_id'] == $news_list[0]['credit_id']) ? 'selected' : '';
									echo '<option value="'.$credit_list[$i]['credit_id'].'" '.$selected.'>'.$credit_name.'</option>';

						}*/
?>
											<!-- </select> -->
										</div>
									</div>

									<hr>
									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['edit'].' Tags ' ?> </label>
										<div class="col-sm-9">												
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" name="auto_keyword">
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

									<hr>
									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['auto_tags'] ?> </label>
										<div class="col-sm-9">												
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" name="auto_tags">
														<span class="lbl"> <?php echo $language['auto_tags'].' ('.$language['dara'].')' ?></span>
													</label>
												</div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">เนื้อหานี้ 18+</label>
										<div class="col-sm-9">												
												<div class="checkbox">
													<label>
														<input type="checkbox" class="ace" name="up18" value=1 <?php if(isset($get_up18[0]->ref_id)) echo "checked" ?>>
														<span class="lbl"> เป็น 18+</span>
													</label>
												</div>
										</div>
									</div>
<?php
			if($newcount > 1){

					/*if($news_list[0]['lang'] == 'en'){
							$news_title_en = $news_list[0]['title'];
							$news_title_th = $news_list[1]['title'];
					}else{
							$news_title_en = $news_list[1]['title'];			
							$news_title_th = $news_list[0]['title'];
					}*/
?>
									<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (EN) </label>
										<div class="col-sm-9">
											<input value="<?php echo $news_title_en?>" type="text" class="col-xs-10 col-sm-10" placeholder="<?php echo $language['title']?>" id="title_en" name="title_en" maxlength="140">
											<div id="countitle"></div>
											<code><i class="menu-icon fa fa-info"></i>ไม่เกิน 140 ตัวอักษร</code>
										</div>
									</div>
<?php
			}else{
					
					//$news_title_th = $news_list[0]['title'];

			}
?>
									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (TH) </label>
										<div class="col-sm-9">
											<input value="<?php echo $news_title_th?>" type="text" class="col-xs-10 col-sm-10" placeholder="<?php echo $language['title']?>" id="title_th" name="title_th" maxlength="140">
											<div id="countitle2"></div>
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle">
													<code>* <?php echo $language['require']?></code><br>
													<code><i class="menu-icon fa fa-info"></i> ไม่เกิน 140 ตัวอักษร</code></span>
											</span>		
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['other_link']?></label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-12 col-sm-12" placeholder="<?php echo $language['other_link']?>" id="other_link" name="other_link" value="<?php echo $news_list[0]['other_link']?>" maxlength="255">
										</div>
									</div>

<?php
			if($newcount > 1){

					if($news_list[0]['lang'] == 'en'){
							$description_en = $news_list[0]['description'];
							$description_th = $news_list[1]['description'];
					}else{
							$description_en = $news_list[1]['description'];			
							$description_th = $news_list[0]['description'];
					}
?>
								<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['shorttitle']?> (EN) 
										</label>

										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea id="editorFull" name="description_en" style="width:100%;height:150px"><?php echo $description_en?></textarea>
													<?php //echo display_ckeditor($ckeditor); ?>
												</div>
										</div>
								</div>
<?php
			}else{
					$description_th = $news_list[0]['description'];
			}
?>
								<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">
										<?php echo $language['shorttitle']?> (TH) 
										</label>
										<div class="col-sm-9">
												<div id="fullToolbar">
													<textarea  id="description_th" name="description_th" style="width:100%;height:150px"><?php echo $description_th?></textarea>
													<?php //echo display_ckeditor($ckeditor2); ?>
												</div>
												<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="<?php echo $language['meta_description_seodoctor']?>" title="" data-original-title="<?php echo $language['meta_description']?>">?</span>
										</div>
								</div>


<?php
			if($newcount > 1){

					if($news_list[0]['lang'] == 'en'){
							$detail_en = $news_list[0]['detail'];
							$detail_th = $news_list[1]['detail'];
					}else{
							$detail_en = $news_list[1]['detail'];			
							$detail_th = $news_list[0]['detail'];
					}
?>
								<div class="form-group" <?php echo $display_en ?>>
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['detail']?> (EN) </label>
										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea id="detail_en" name="detail_en" style="width:100%;height:350px">
													<?php echo $detail_en?></textarea>
													<?php echo display_ckeditor($ckeditor3); ?>
												</div>
										</div>
								</div>
<?php
			}else{
					
					$detail_th = $news_list[0]['detail'];

			}
?>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['detail']?> (TH) </label>
										<div class="col-sm-9">
												<div id="fullToolbar">											
													<textarea id="detail_th" name="detail_th" style="width:100%;height:350px"><?php echo $detail_th?></textarea>
													<?php echo display_ckeditor($ckeditor4); ?>
												</div>
										</div>
								</div>


								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"> <?php echo $language['search for tags news'] ?> </label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="search_news1" maxlength="255">
											<button class="btn btn-purple btn-sm" type="button"><i class="ace-icon glyphicon glyphicon-search"></i></button>
										</div>
										<div class="col-sm-12" id="newslist_tags"></div>
								</div>

								<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"> <?php echo $language['search for tags dara'] ?> </label>
										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['dara']?>" id="search_dara" maxlength="255">
											<button class="btn btn-purple btn-sm" type="button"><i class="ace-icon glyphicon glyphicon-search"></i></button>
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

						<div class="form-group">
								<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
								<div class="col-xs-3">
										<label>
											<input name="status" id="cat_status" class="ace ace-switch ace-switch-6" type="checkbox" value=1 <?php if($news_list[0]['status'] == 1) echo "checked";?> />
											<span class="lbl"></span>
										</label>
								</div>
						</div>

						<div class="form-group">
								<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['can_comment']?></label>
								<div class="col-xs-3">
										<label>
											<input name="can_comment" id="can_comment" class="ace ace-switch ace-switch-6" type="checkbox" value=1 <?php if($news_list[0]['can_comment'] == 1) echo "checked";?> />
											<span class="lbl"></span>
										</label>
								</div>
						</div>

						<div class="form-group">
								<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['picture']?></label>
								<div class="col-xs-3">
										<label>
											<a href="<?php echo $pic_gallery ?>"><i class="menu-icon fa fa-picture-o bigger-160"></i></a>
										</label>
								</div>
						</div>

						<input type="hidden" name="relate_list" value="<?php echo count($relate_list) ?>">
<?php
		echo form_close();
?>

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
					</div>

					<div class="form-group">
								<label for="form-relate" class="col-sm-3 control-label no-padding-right"><?php echo $language['relate']?></label>

<?php 
	$attributes = array('class' => 'form-horizontal', 'id' => 'Orderform');
	echo form_open_multipart('news/saveorder', $attributes);
	echo '<input type="hidden" name="news_id" value="'.$news_list[0]['news_id2'].'">';

	//if(!isset($mobile)){
		//***********************************$relate_list*******************
		echo $emptyarr = '<pre id = "nestable-output"></pre>';
		//Debug($emptyarr); 
		//Debug($relate_list); 

		/*$icondel = '<a class="red del-confirm" href="javascript:void(0);" id="bx-del-relate-all" data-value="" data-name="">
										<i class="ace-icon fa fa-trash-o bigger-130"> '.$language['del relate'].'</i>
							</a>';*/

		$icondelall = '<button class="btn btn-danger btn-sm del-confirm" type="button" id="bx-del-relate-all" data-value="'.$news_list[0]['news_id2'].'" data-name="">
								<i class="ace-icon fa fa-trash-o bigger-110"> '.$language['del relate'].'</i>
							</button>';

		$icondel = '';
?>
								<div class="col-sm-2">
<?php
								if($relate_list){

										echo '<div style="width:100%;height:5px;"></div>';

										for($i=0;$i<count($relate_list);$i++){
												
												$running_number = $i+1;

												$newsrelate_id = $relate_list[$i]->news_id2;
												$title = $relate_list[$i]->title;
												$order = $relate_list[$i]->order;

												//if($i == 0) $relate_box .= $newsrelate_id;
												//else $relate_box .= ", ".$newsrelate_id;

												$icondel = '<a class="red del-confirm" href="javascript:void(0);" id="del-relate'.$newsrelate_id.'" data-value="'.$newsrelate_id.'" data-name="'.$title.'"><i class="ace-icon fa fa-trash-o bigger-130" data-rel="tooltip" title="'.$language['delete'].'"></i></a>';

												$relatenewsid = '<input type="hidden" name="relate_id[]" value="'.$newsrelate_id.'" class="col-xs-12" style="text-align: center;">';
												$textbox = '<input type="text" name="orderid[]" value="'.$running_number.'" class="col-xs-8" style="text-align: center;">';

												echo '<div id="widget'.$newsrelate_id.'" class="alert alert-info" style="width:100%;height:71px;margin: 1px 0px 5px 0px;" >'.$textbox.' '.$relatenewsid.' '.$icondel.'</div>';
										}
								}
?>
								</div>
								<div class="col-sm-7">
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
																$display_img = (file_exists('uploads/thumb/'.$relate_list[$i]->folder.'/'.$relate_list[$i]->file_name)) ? "<img src=".$img_src."  width='90' height='50'>" : "";
																//$display_img = $relate_list[$i]->file_name;
																$css_tag = 'style="width:78%;"';
															}else{
																$display_img = '';
																$css_tag = 'style="width:78%;margin-left: 90px;"';
															}

															/*$icondel = '<a class="red del-confirm" href="#" id="bootbox-confirm'.$newsrelate_id.'" data-value="" data-name="'.$title.'">
																				<i class="ace-icon fa fa-trash-o bigger-130" data-rel="tooltip" title="'.$language['delete'].' '.$title.'"></i>
																		</a>';*/
															$icondel = '';
																			
															echo '
															<li class="dd-item" id="newsrelate'.$newsrelate_id.'" data-id="'.$newsrelate_id.'" value="'.$order.'" data="'.$order.'">
																<div class="dd-handle" style="width:100%" >
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
											<!-- <button class="btn btn-info btn-sm" type="submit">
													<i class="ace-icon fa fa-floppy-o bigger-110"></i>
													<?php echo $language['save_relate']?>
											</button> -->
											<?php echo $icondelall?>

									</div>
							</div>
<?php //} ?>
<?php echo form_close();?>
						</div>
				</div>

				<!-- Relate -->
				<div class="col-xs-12">
							<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"> <?php echo $language['search for relate'] ?> </label>
							<div class="col-sm-9">
									<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['title']?>" id="search_news2" data-value="<?php echo $news_list[0]['news_id2']?>" maxlength="255">
									<button class="btn btn-purple btn-sm" type="button"><i class="ace-icon glyphicon glyphicon-search"></i></button>
							</div>
							<div class="col-sm-12" id="newslist_relate"></div>
				</div>

				<div style="clear: both;"></div>
								<div class="clearfix form-actions">
										<div class="col-md-offset-1 col-md-12 col-sm-12">

										<button type="button" class="btn btn-purple bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>">
											<i class="ace-icon fa fa-search-plus bigger-110" ></i> <?php echo $language['preview'] ?>
										</button>
<?php
		if($news_list[0]['approve'] == 0){
?>
											<button type="button" class="btn btn-success admin_approve" id="admin_approve">
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
											<button type="button" class="btn btn-info submit_news" id="submit_news">
												<i class="ace-icon fa fa-floppy-o bigger-110"></i> Submit
											</button>

											<button type="button" class="btn reset_news" id="reset_news">
												<i class="ace-icon fa fa-undo bigger-110"></i> Reset
											</button>

											<button type="button" class="btn btn-success gen_json" id="gen_json"  <?php if($approve == 0) echo "disabled" ?>>
												<i class="ace-icon glyphicon glyphicon-file bigger-110"></i> <?php echo $language['generate_catch']?>
											</button>
<?php
		if($news_list[0]['approve'] == 1){
?>
											<a href="javascript:void(0);" class="twitter-share-button" data-url="<?php echo $shear ?>" data-text="<?php echo $shear_title?>" data-via="Siamdara" data-dnt="true">
											<button type="button" class="btn btn-info share" >
												<i class="middle ace-icon fa fa-twitter-square white"> ทวีต</i>
											</button>
											</a>	
<?php
		}
?>
<?php
		if($news_list[0]['approve'] == 0){
?>
											<button type="button" class="btn btn-pink sentmail" id="sentmail">
												<i class="ace-icon fa fa-envelope bigger-110"></i> <?php echo $language['sentmail_manager']?>
											</button>
<?php
		}
?>
										</div>
								</div>
						</div>
		</div>

		<div class="row">
				<div class="col-sm-12">
							<div id="gen_data"></div>
				</div>
		</div>

<?php 
	// แสดง Action ของ News
	$showdata = array(
			"create_date" => $news_list[0]['create_date'],
			"create_by_name" => $news_list[0]['create_by_name'],
			"lastupdate_date" => $news_list[0]['lastupdate_date'],
			"lastupdate_by_name" => $news_list[0]['lastupdate_by_name'],
			"approve_date" => $news_list[0]['approve_date'],
			"approve_by_name" => $news_list[0]['approve_by_name'],
	);
	$this->box_model->DisplayLog($showdata); 
	$this->box_model->DisplayLogActivity($view_log); 
?>
				</div>
		</div>
</div>
<!-- ace scripts -->
<!-- <script src="<?php echo base_url('theme/assets/js/ace.min.js') ?>"></script> -->

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );		

		$('#title_en').on('keypress', function(e){
				var len = $(this).val().length;
				$('#countitle').html(len);
		});

		$('#title_th').on('keypress', function(e){
				var len = $(this).val().length;
				$('#countitle2').html(len);
		});

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

		/*$('#bootbox-confirm-relate').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
						if(result) {
								window.location='<?php echo base_url("news/delrelate")."/".$news_list[0]["news_id2"] ?>' ;
						}
					});
		});*/

<?php

		if($relate_list)
				for($i=0;$i<count($relate_list);$i++){	

					$newsrelate_id = $relate_list[$i]->news_id2;
?>
					$('#del-relate<?php echo $newsrelate_id?>').on('click', function() {

								var v = $(this).attr('data-value');
								var title = $(this).attr('data-name');

								bootbox.confirm("<?php echo $language['are you sure to delete']?> Relate " + title, function(result) {
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
															$('#newsrelate<?php echo $newsrelate_id?>').css('display','none');

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

		var updateOutput = function(e){
				var list   = e.length ? e : $(e.target),
					output = list.data('output');
				if (window.JSON) {
					//output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
					output.html(window.JSON.stringify(list.nestable('serialize')));
					UpdateRelate(window.JSON.stringify(list.nestable('serialize')));
				} else {
					output.html('JSON browser support required for this demo.');
					//UpdateRelate(window.JSON.stringify(list.nestable('serialize')));
				}
		};

		$('.dd').nestable({
			group: 1
		}).on('change', updateOutput);

		updateOutput($('.dd').data('output', $('#nestable-output')));

		//or you can activate the chosen plugin after modal is shown
		//this way select element becomes visible with dimensions and chosen works as expected
		/*$('#modal-form').on('shown', function () {
			$(this).find('.modal-chosen').chosen();
		})*/

		$('#pin').on('change', function(e){
				var v = $(this).val();
				if(v > 0){
					$('#pin_set').removeAttr('disabled');
					$('#pin-startdate-time').val('');
					$('#pin-enddate-time').val('');
				}else{
					$('#pin_set').attr('disabled', 'disabled');
					$('#pin-startdate-time').val('');
					$('#pin-enddate-time').val('');
				}
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

		$('.preview').on('click', function() {			
			//document.getElementById("jform").submit();
			//chkform();
			window.open('<?php echo $previewurl ?>');
		});		

		$('.submit_news').on('click', function() {			
			//document.getElementById("jform").submit();
			chkform();
		});		

		$('.reset_news').on('click', function() {
			document.getElementById("jform").reset();
		});	

		$('.twitter-share-button').on('click', function(e){
				var url = $(this).attr('data-url');
				var text = $(this).attr('data-text');
				var via = $(this).attr('data-via');
				window.open('https://twitter.com/intent/tweet?text=' + text + '&url=' + url + '&via=' + via);				
		});	

		$('.sentmail').on('click', function(e){
				alert('Under Constrction.');
				/*$.ajax({
						type: 'POST',
						data: {id : <?php echo $news_list[0]['news_id2']?>},
						url: "<?php echo base_url('dara/sentmail')?>",
						cache: false,
						success: function(data){
								AlertSuccess	(data);
						}
				});*/
		});

		$('.gen_json').on('click', function(e){
<?php
				//http://daraapi.siamdara.com/api/web-api.php?method=DetailNews&key=mMs3dAkM&news_id=54558&gen_file=1

				//$('#gen_data').html('<iframe frameborder="0"scrolling="Yes" src="http://daraapi.siamsport.co.th/api/web-api.php?method=DetailNews&amp;key=mMs3dAkM&amp;news_id=$news_id&amp;gen_file=1" width="100%" height=100px></iframe>');

				//alert('Generate.....');
?>
				//alert('Under construction.');
				Gencatch();

		});

		$('#bx-del-relate-all').on('click', function() {
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
							window.location='<?php echo base_url("news/delrelate")."/".$news_list[0]["news_id2"] ?>' ;
					}
				});
		});

		$('.admin_approve').on('click', function() {
<?php
				if($this->session->userdata('admin_type') <= 4){	
?>
				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);

				$("#msg_info").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Please, wait...');
				$("#msg_info,#BG_overlay").fadeIn();

				$.ajax({
						type: 'POST',
						url: "<?php echo base_url('news/approve/'.$news_list[0]['news_id2'])?>",
						//data: {id: res},
						cache: false,
						success: function(data){

								$("#msg_info").fadeOut();

								if(data == 0){
									//$("#msg_error").html(data);
									//$("#msg_error").fadeIn();
									//$("#msg_info").fadeOut();
									$("#msg_error").attr('style','display:block;');
									AlertError('Not approve');
								}else{
									//$("#msg_info").html(data);
									//$("#msg_info").fadeIn();							
									//$("#msg_error").fadeOut();
									$("#msg_success").attr('style','display:block;');
									//AlertSuccess	('Approve');
									$('.admin_approve').attr('disabled','disabled');
									$('.gen_json').removeAttr('disabled');
									$('.sentmail').attr('style','display:none;');

									//WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
									Gencatch();
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

function chkform(){

		var category_id = document.NewsForm.category_id.value;
		var subcategory_id = document.NewsForm.subcategory_id.value;
		
		//alert('category_id = ' + category_id);
		//alert('xxx');

		//if(category_id > 0 && subcategory_id > 0){
		if(category_id > 0){

				if(document.getElementById("title_th").value == ''){
					alert('กรุณาใส่ <?php echo $language['title']?> ด้วยครับ');
					document.NewsForm.title_th.focus();
				}else if(document.getElementById("description_th").value == ''){
					alert('กรุณาใส่ <?php echo $language['shorttitle']?> ด้วยครับ');
					document.NewsForm.description_th.focus();
				}else if(document.getElementById("tag_id").value == ''){
					alert('กรุณาใส่ Tags ด้วยครับ');
					document.NewsForm.auto_keyword.focus();
				}else
					document.NewsForm.submit();

		}else alert('<?php echo $language['please_select_cat']?>');
		
}

function Gencatch(){
				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				$.ajax({
						type: 'POST',
						url: "<?php echo $this->config->config['api']; ?>/api/web-api.php",
						data: {method : 'DetailNews', key : 'mMs3dAkM', news_id : <?php echo $news_id ?>, gen_file : 1},
						cache: false,
						success: function(v){
							//alert(v.header.message);
							txt = 'Approve and Genarate news ID ' + v.body.item[0].news_id + ' ' + v.header.message;
							AlertSuccess(txt);
						},
				});
}
</script>