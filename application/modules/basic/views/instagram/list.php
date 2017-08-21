<?php 
		$language = $this->lang->language; 
		$i=0;
		$usedataTables = 1;
		$brand_id = 0;
		$category_iddb = 5;
		$allinstagram = count($instagram_list);

		/*if(isset($this->input->get('success'))){
			$success = $this->input->get('success');
		}*/
		//$maxcat = count($news_type);
		//die();
		
		//if(isset($this->input->post('brand_id'))) $brand_id = $this->input->post('brand_id');
		//Debug($type_id);
?>
<link rel="stylesheet" href="<?php echo site_url('ace-skins.min.css') ?>" />
<link rel="stylesheet" href="<?php echo site_url('ace-rtl.min.css') ?>" />
<style type="text/css">
.col-sm-6 {width: 100%;text-align: center;	}
#saveorder{cursor:pointer;}
.input-small{text-align: center;}
.hand{cursor:pointer;}
.sethighlight, .setmegamenu{color:#cccccc;}
.gray{color: #B3B3B3;}
</style>

<div class="row">
<?php 
				$attributes = array('class' => 'form-horizontal', 'id' => 'catform');
				echo form_open('instagram', $attributes);
?>
		<div class="col-xs-12 col-lg-3">
				<button type="button" class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('instagram/add') ?>';">
					<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['instagram']  ?>
				</button> <?php echo $allinstagram." of ".number_format($countinstagram)." record." ?>
		</div>
	
		<div class="col-xs-12 col-lg-3">
				<?php //echo $brand_list; ?>
		</div>

		<div class="col-xs-12 col-lg-3">
				<div class="col-xs-6">

				</div>
		</div>

		<div class="col-xs-12 col-lg-3">
						<div class="col-xs-6">
								<!-- <div class="input-group">
										<input class="form-control date-picker" id="id-date-picker-1" type="text"  name="create_date" value="<?php 
												if($this->input->server('REQUEST_METHOD') === 'POST'){
														if($this->input->post('create_date') != '') echo $this->input->post('create_date');
												}
										?>" />
												<span class="input-group-addon">
													<i class="fa fa-calendar bigger-110"></i>
												</span>
								</div> -->
								<select class="form-control" name="status">
										<option><?php echo $language['all']?></option>
										<option value="1" <?php if($this->input->post('status') == 1) echo 'selected="selected"'?> ><?php echo $language['publish']?></option>
										<option value="3" <?php if($this->input->post('status') == 3) echo 'selected="selected"'?> ><?php echo $language['unpublish']?></option>
								</select>
						</div>
						<div class="col-xs-6">
								<button class="btn btn-sm btn-primary" type="submit">
										<i class="ace-icon fa fa-glass align-top bigger-125"></i>Filter
								</button>
						</div>
<?php
				echo form_close();				
?>
		</div>


<?php
			//if(function_exists('Debug')) Debug($news);
			if(isset($error)){
?>
				<div class="col-xs-12">
							<div class="alert alert-danger">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon fa fa-times"></i>
											</strong><?php echo $error?>.
									<br>
							</div>
				</div>
<?php
			}
?>
<?php
			
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
//Debug($instagram_list);
?>
<div class="col-xs-12">
	<div class="table-responsive">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open('instagram', $attributes);
?>									
											<table id="dataTables-instagram" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>No.</th>
														<th class="hidden-480">
															<?php echo @($language['order_by']) ? $language['order_by'] : 'Order by' ?>
															&nbsp;<?php if($type_id > 0){ ?><i class="ace-icon fa fa-floppy-o bigger-120 blue" id="saveorder"></i><?php } ?>
														</th>
														<th>ID</th>
														<th><?php echo $language['picture'] ?></th>
														<th width="20%"><?php echo $language['title'] ?></th>
														<!-- <th class="hidden-480"><?php echo $language['type'] ?></th> -->
														<th width="10%" class="hidden-480">
															<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
															<?php echo $language['create_date'] ?>
														</th>
														<th ><?php echo $language['status'] ?></th>
														<th ><?php echo $language['approve'] ?></th>
														<th width="15%"><?php echo $language['action'] ?></th>
													</tr>
												</thead>
	<tbody>												
<?php
			$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
			$thumb = $megamenu = $highlight = '';

			
			if($instagram_list)
					for($i=0;$i<$allinstagram;$i++){
								
								//$pic = ($instagram_list[$i]['avatar'] == '') ? 'theme/assets/avatars/avatar3.png' : 'uploads/instagram/'.$instagram_list[$i]['avatar'];
								//$thumb = $pic;
								$instagram_id = $instagram_list[$i]['instagram_id'];
								$instagram_id2 = $instagram_list[$i]['instagram_id2'];

								//$full_name = $instagram_list[$i]['full_name'];

								$title = $instagram_list[$i]['title'];
								//$description = $instagram_list[$i]['description'];
								$order_by = $instagram_list[$i]['order_by'];
								$create_date = $instagram_list[$i]['create_date'];
								//$create_date = RenDateTime($create_date);
								$create_by_name = $instagram_list[$i]['create_by_name'];

								$countview = $instagram_list[$i]['countview'];

								$can_comment = $instagram_list[$i]['can_comment'];

								$status = $instagram_list[$i]['status'];
								$approve = $instagram_list[$i]['approve'];
								//$is_18 = $instagram_list[$i]['is_18'];

								//$news_highlight_id = $instagram_list[$i]['highlight_id'];
								//$megamenu_id = $instagram_list[$i]['megamenu_id'];

								if($instagram_list[$i]['file_name'] != ''){
										$thumb_img = base_url('uploads/thumb300/').'/'.$instagram_list[$i]['folder'].'/'.$instagram_list[$i]['file_name'];
										if(file_exists('uploads/thumb300/'.$instagram_list[$i]['folder'].'/'.$instagram_list[$i]['file_name']))
											$show_img = '<img src="'.$thumb_img.'" width="100" height="57">';
										else
											$show_img  = '<img src="'._IMG_NOTFOUND.'" width="100" height="57">';
								}else
										$show_img  = '<img src="'._IMG_NOTFOUND.'" width="100" height="57">';

								//$highlight = ($news_highlight_id > 0) ? '<i class="ace-icon fa fa-asterisk" data-rel="tooltip" style="color:#ffcc33;" title="'.$language['highlight'].'"></i>' : ''; 
								//$megamenu = ($megamenu_id > 0) ? '<i class="ace-icon glyphicon glyphicon-picture" data-rel="tooltip" style="color:#ff9900;" title="Mega menu"></i>' : '';

						/*$highlight = ($news_highlight_id > 0) ? '<i class="ace-icon fa fa-asterisk hand rmhighlight green" data-value="'.$instagram_id2.'" id="hl'.$instagram_id2.'" data-name="'.$title.'" data-rel="tooltip" title="'.$language['highlight'].'"></i>' : '<i class="ace-icon fa fa-asterisk hand sethighlight" data-value="'.$instagram_id2.'" id="hl'.$instagram_id2.'" data-name="'.$title.'" data-rel="tooltip" title="Set '.$language['highlight'].'"></i>';

						$megamenu = ($megamenu_id > 0) ? '<i class="ace-icon glyphicon glyphicon-picture hand rmmegamenu green" data-value="'.$instagram_id2.'" data-name="'.$title.'" data-cat="'.$brand_name.'"  data-catid="'.$brand_id.'" id="mg'.$instagram_id2.'" data-rel="tooltip" title="Mega menu"></i>' : '<i class="ace-icon glyphicon glyphicon-picture hand setmegamenu" data-value="'.$instagram_id2.'" data-name="'.$title.'" data-cat="'.$brand_name.'"  data-catid="'.$brand_id.'" id="mg'.$instagram_id2.'" data-rel="tooltip" title="Set Mega menu"></i>';*/
								
						$edit_instagram = site_url('instagram/edit/'.$instagram_id2);
						$pic_instagram = site_url('instagram/picture/'.$instagram_id2);
						$previewurl = $this->config->config['www']."/instagram/".$instagram_id2."/?preview=1";
?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" name="selectid[]"  />
										<span class="lbl"></span>
								</label>
						</td> -->
						<td><?=$order_by?></td>
						<td class="hidden-480">								
								<input class="input-small" name="order[]" type="text" id="order_by" placeholder="Not null" value="<?=$order_by?>">
						</td>
						<td><?=$instagram_id2?><input type="hidden" class="ace" name="selectid[]" value="<?php echo $instagram_id2?>" /></td>
						<td><a href="<?php echo $edit_instagram ?>"><?=$show_img?></a></td>
						<td>
								<a href="<?php echo $edit_instagram ?>"><?=$title?></a>
								<br>View : <?php echo number_format($countview) ?>
								<br><?php echo $language['create_by'] ?> : <?php echo $create_by_name?>
						</td>
						<!-- <td class="hidden-480"><?php //$brand_name?></td> -->
						<td class="hidden-480"><?php echo $create_date ?></td>
						<td class=""><span class="col-sm-6">
								<label class="pull-right inline" id="enable<?php echo $instagram_id2?>">
								<small class="muted"></small>
									<input type="checkbox" id="status<?=$instagram_id2?>" class="ace ace-switch ace-switch-6 status-buttons" data-value="<?=$instagram_id2?>"
									<?php if($status  == 1) echo 'checked';?>  value=1>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td class=""><span class="col-sm-6">
								<label class="pull-right inline" id="enable<?php echo $instagram_id2?>">
								<small class="muted"></small>
									<input type="checkbox" id="approve<?=$instagram_id2?>" class="ace ace-switch ace-switch-4 approve-buttons" data-value="<?=$instagram_id2?>"
									<?php if($approve  == 1) echo 'checked';?>  value=1 <?php if($this->session->userdata('admin_type') > 4) echo 'disabled' ?>>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td>

												<div class="hidden-sm hidden-xs action-buttons">

															<a href="javascript:void(0);" class="blue" ><i class="ace-icon fa fa-search-plus bigger-130 bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>" data-rel="tooltip" title="<?php echo $language['preview'] ?>"></i></a>

															<a href="<?php echo $pic_instagram ?>" data-rel="tooltip" title="Picture">
																	<i class="menu-icon fa fa-picture-o"></i>
															</a>
															<a class="green" href="<?php echo $edit_instagram ?>" data-rel="tooltip" title="Edit">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
															</a>
															<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm<?php echo $instagram_id2?>" data-value="<?=$instagram_id2?>" data-cat="<?=$brand_id?>" data-name="<?=$title?>" data-rel="tooltip" title="Delete">
																	<i class="ace-icon fa fa-trash-o bigger-130"></i>
															</a>
															<!-- <button id="bootbox-confirm" class="btn btn-info">Confirm</button> -->
												</div>

															<div class="hidden-md hidden-lg">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li>
																			<a href="javascript:void(0);<?php //echo $previewurl ?>" class="tooltip-info bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>" data-rel="tooltip" title="<?php echo $language['preview'] ?>">
																					<span class="blue">
																							<i class="ace-icon fa fa-search-plus bigger-120"></i>
																					</span>
																			</a>
																		</li>

																		<li>
																			<a href="<?php echo $pic_instagram ?>" class="tooltip-info" data-rel="tooltip" title="Picture">
																				<span class="blue">
																					<i class="ace-icon fa fa-picture-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="<?php echo $edit_instagram ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="javascript:void(0);" id="bx-confirm<?php echo $instagram_id2?>" class="tooltip-error del-confirm" data-value="<?=$instagram_id2?>" data-name="<?=$title?>" data-rel="tooltip" title="Delete">
																				<span class="red">
																					<i class="ace-icon fa fa-trash-o bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>						
						</td>
		</tr>
<?php
					}
?>
			</tbody>
		</table>
	</div>
</div>
<?php
	echo form_close();
?>
			<!-- <button class="btn btn-info" id="bootbox-confirm">Confirm</button> -->
			<!-- <button class="btn btn-primary" id="gritter-center">Center</button> -->
<?php 
	if($usedataTables == 0) echo $GenPage
?>

			</div><!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->

		<div class="center">
				<span class="action-buttons gray"> <?php echo $language['iconmain'] ?></span>
				<span class="action-buttons gray">
						<i class="ace-icon fa fa-search-plus"> <?php echo $language['preview'] ?></i> |
						<i class="ace-icon fa fa-picture-o"> <?php echo $language['picture'] ?></i> |
						<i class="ace-icon fa fa-pencil"> <?php echo $language['edit'] ?></i> |
						<i class="ace-icon fa fa-trash-o"> <?php echo $language['delete'] ?></i>
				</span>
		</div>
</div>

<script type="text/javascript">
$( document ).ready(function() {

		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		})
		
		/*$('#brand_id').on('change', function() {
				document.getElementById("catform").submit();
		});*/

		$('.status-buttons').on('click', function() {
				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);
				var instagramid = $(this).attr('data-value');

				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('instagram/status')?>/" + instagramid,
					cache: false,
					success: function(data){
							$("#msg_info").fadeOut();
							if(data == 1){
								$("#msg_success").attr('style','display:block;');
								//AlertSuccess	('Active And Generate json file.');
								AlertSuccess	('Active.');
							}else{
								$("#msg_error").attr('style','display:block;');
								AlertError('Inactive');
							}
					}
				});
		});

		$('.approve-buttons').on('click', function() {

				var id = $(this).attr('id');
				var n = id.length;
				var maxstr = n-6;
				var res = id.substr(6, maxstr);
				var instagramid = $(this).attr('data-value');
				//alert('approve-buttons ' + columnid);

<?php
				if($this->session->userdata('admin_type') <= 4){	
?>
						$.ajax({
							type: 'POST',
							url: "<?php echo base_url('instagram/approve')?>/" + instagramid,
							cache: false,
							success: function(data){
									//alert(data);
									if(data == 0){
										//$("#msg_my_alert ").attr('style','background: none repeat scroll 0 0 #cc0000;');
										//AlertMsg('Inactive');
										$("#msg_error").attr('style','display:block;');
										AlertError('Not approve');
									}else{
										//$("#msg_my_alert ").attr('style','background: none repeat scroll 0 0 #438eb9;');
										//AlertMsg('Active');
										$("#msg_success").attr('style','display:block;');
										AlertSuccess	('Approve');
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

		$('.del-confirm').on('click', function() {
				var id = $(this).attr('data-value');
				var catid = $(this).attr('data-cat');
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
							//alert('<?php echo base_url('column/delete')?>/' + id);
							window.location='<?php echo base_url('instagram/delete')?>/' + id + '?cat=' + catid;
						}
				});
		});

		$('.sethighlight').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				bootbox.confirm("<h4 class='row header smaller lighter purple'>Set Highlight </h4>" + title , function(result) {
					if(result) {
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('instagram/sethighlight')?>/" + id,
										data: {title: encodeURIComponent(title), op : 'set'},
										cache: false,
										success: function(data){
												if(data == 1){
													$('#hl' + id).attr('style', 'color:green');
													AlertSuccess	('Set Highlight ' + title);
												}else{
													AlertError('Not Set');
												}
										}
								});
						}
				});
		});

		$('.setmegamenu').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				var cat = $(this).attr('data-cat');
				var catid = $(this).attr('data-catid');
				bootbox.confirm("<h4 class='row header smaller lighter orange'>Set Megamenu </h4>" + title , function(result) {
					if(result) {
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('instagram/setmegamenu')?>/" + id,
										data: {title: encodeURIComponent(title), cat : cat, catid : catid, op : 'set'},
										cache: false,
										success: function(data){
												if(data == 1){
													$('#mg' + id).attr('style', 'color:green');
													AlertSuccess	('Set Megamenu ' + title);
												}else{
													AlertError('Not Set');
												}
										}
								});
						}
				});
		});

		$('.rmhighlight').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				bootbox.confirm("<h4 class='row header smaller lighter red'>Remove Highlight </h4>" + title , function(result) {
					if(result) {
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('instagram/sethighlight')?>/" + id,
										data: {title: encodeURIComponent(title), op : 'remove'},
										cache: false,
										success: function(data){
												if(data == 0){
													$('#hl' + id).attr('class', 'ace-icon fa fa-asterisk hand');
													AlertSuccess	('Remove Highlight ' + title);
												}else{
													AlertError('Not Set');
												}
										}
								});
						}
				});
		});

		$('.rmmegamenu').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				var cat = $(this).attr('data-cat');
				//var catid = $(this).attr('data-catid');
				bootbox.confirm("<h4 class='row header smaller lighter red'>Remove Megamenu </h4>" + title , function(result) {
					if(result) {
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('instagram/setmegamenu')?>/" + id,
										data: {title: encodeURIComponent(title), cat : cat, op : 'remove'},
										cache: false,
										success: function(data){
												if(data == 0){
													$('#mg' + id).attr('class', 'ace-icon glyphicon glyphicon-picture hand');
													AlertSuccess	('Remove Megamenu ' + title);
												}else{
													AlertError('Not Set');
												}
										}
								});
						}
				});

		});

		$('#saveorder').on('click', function() {
			document.getElementById("jform").submit();
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

		<?php if($usedataTables == 1){ ?> 
			//$('#dataTables-instagram').dataTable();
			$('#dataTables-instagram').dataTable( {
				"order": [[ 2, "desc" ]],
				stateSave: true
			} );
		<?php } ?>
});
</script>

<?php //echo js_asset('checkall.js'); ?>
