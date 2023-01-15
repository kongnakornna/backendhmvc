<?php 
		$language = $this->lang->language; 
		$i=0;
		$usedataTables = 1;
		//$maxcat = count($dara_type);
		//Debug($search_form);
		$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
		echo form_open('dara/listview', $attributes);

		if(!isset($search_form['sortby'])) $search_form['sortby'] = 'lastupdate_date';
		//Debug($dara_list);
		$admin_type = $this->session->userdata('admin_type');
		$admin_id = $this->session->userdata('admin_id');

		$datainput = $this->input->get();

		$access_level = $this->config->config['level'];
		if($this->input->get('success')) $success = $this->input->get('success');
		//Debug($notification_tags);
?>
<div class="col-xs-12">
		<div class="col-xs-2">
				<a href="<?php echo site_url('dara/add') ?>"><button class="btn btn-sm btn-primary" type="button">
						<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['dara']  ?>
				</button></a> 
				<br><?php echo number_format($dara_all)?> record
		</div>
		<div class="col-xs-2">
			<select class="form-control" id="sortby" name="sortby">
					<option value="0">-</option>
					<option value="create_date" <?php echo ($search_form['sortby'] == "create_date") ? 'selected' : '' ?>><?php echo $language['create_date']?></option>
					<option value="lastupdate_date" <?php echo ($search_form['sortby'] == "lastupdate_date") ? 'selected' : '' ?>><?php echo $language['lastupdate']?></option>
					<option value="dara_profile_id_map" <?php echo ($search_form['sortby'] == "id") ? 'selected' : '' ?>>ID</option>
					<option value="first_name" <?php echo ($search_form['sortby'] == "first_name") ? 'selected' : '' ?>><?php echo $language['name']?></option>
					<option value="nick_name" <?php echo ($search_form['sortby'] == "nick_name") ? 'selected' : '' ?>><?php echo $language['nickname']?></option>
			</select>		
		</div>
		<div class="col-xs-2">
			<select class="form-control" id="form-field-select-1" name="dara_type">
					<option value="0">-</option>
<?php 
				$datadara_type = $this->input->post('dara_type');
				$alltype = count($dara_type);
				if($dara_type)
						for($i = 0; $i < $alltype; $i++){
									$selected = ($datadara_type == $dara_type[$i]['dara_type_id_map']) ? 'selected' : '';
									echo '<option value="'.$dara_type[$i]['dara_type_id_map'].'" '.$selected.'>'.$dara_type[$i]['dara_type_name'].'</option>';
						}
?>
			</select>
		</div>
		<div class="col-xs-2">
			<select class="form-control" id="form-field-select-1" name="gender">
					<option <? //if(!isset($this->input->post()) echo 'selected'; ?>><?php echo $language['all']?></option>
					<option value="m" <?php echo ($this->input->post('gender') =='m') ? 'selected' : '' ?>><?php echo $language['male']?></option>
					<option value="f" <?php echo ($this->input->post('gender') =='f') ? 'selected' : '' ?>><?php echo $language['female']?></option>
			</select>
		</div>
		<div class="col-xs-2">

			<select class="form-control" name="dara-status">
					<option><?php echo $language['all']?></option>
					<option value="1" <?php if($this->input->post('dara-status') == 1) echo 'selected="selected"'?> ><?php echo $language['publish']?></option>
					<option value="3" <?php if($this->input->post('dara-status') == 3) echo 'selected="selected"'?> ><?php echo $language['unpublish']?></option>
			</select>	
		</div>
		<div class="col-xs-1">
			<button class="btn btn-sm btn-primary" type="submit">
					<i class="ace-icon fa fa-glass align-top bigger-125"></i>Filter
			</button>
		</div>
		<div class="col-xs-1">
			&nbsp;<a href="#<?php echo base_url('dara/listview'); ?>"><i class="ace-icon glyphicon glyphicon-list icon-only bigger-150" title="List View"></i></a>
			&nbsp;<a href="<?php echo base_url('dara/gridview'); ?>"><i class="ace-icon glyphicon glyphicon-th icon-only bigger-150" title="Grid View"></i></a>
		</div>
</div>
<?php
			//Debug($this->input->post());
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
											</strong><?php echo $success?>
									<br>
							</div>
				</div>
<?php
			}
?>
<div class="col-xs-12">
		<div>
<?php
			//Debug($dara_list);
?>
			<table id="dataTables-dara" class="table table-striped table-bordered table-hover">
				<thead>
						<tr>
							<th>No.</th>
							<th width="15%"></th>
							<th><?php echo $language['full_name'] ?></th>
							<th><?php echo $language['nickname'] ?></th>
							<th class="hidden-480"><?php echo $language['dara_type'] ?></th>
							<th class="hidden-480">
								<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
								<?php echo $language['lastupdate'] ?>
							</th>
							<th class="hidden-480" width="100"><?php echo $language['status'] ?></th>
							<th class="hidden-480" width="100"><?php echo $language['approve'] ?></th>
							<th><?php echo $language['action'] ?></th>
						</tr>
				</thead>
	<tbody>
<?php
			$number = 0;
			if(isset($datainput['p'])){
				$number = ($list_page*($datainput['p'] - 1));
			}
			$alldara = count($dara_list);
			if($dara_list)
					for($i=0;$i<$alldara;$i++){
								
								$number += 1;
								if(isset($dara_list[$i]['avatar'])){
									if(trim($dara_list[$i]['avatar']) != '' && trim($dara_list[$i]['avatar']) != '-'){
											$picture = (!file_exists('uploads/thumb/dara/'.$dara_list[$i]['avatar'])) ? 'uploads/dara/'.$dara_list[$i]['avatar'] : 'uploads/thumb/dara/'.$dara_list[$i]['avatar'];
											//$fulimg = 'uploads/dara/'.$dara_list[$i]['avatar'];
											$picture = img_src($picture);
									}else
											$picture = img_src('theme/assets/avatars/avatar3.png', 48, 48);
								}else{
									$picture = img_src('theme/assets/avatars/avatar3.png', 48, 48);
									//$fulimg = 'theme/assets/avatars/avatar3.png';
								}

								$dara_profile_id = $dara_list[$i]['dara_profile_id'];
								//$full_name = $dara_list[$i]['first_name'];
								$full_name = $dara_list[$i]['first_name'].' '.$dara_list[$i]['last_name'];
								$nickname = $dara_list[$i]['nick_name'];
								$dara_type_id = $dara_list[$i]['dara_type_id'];
								$dara_type_name = $dara_list[$i]['dara_type_name'];

								$belong_to = $dara_list[$i]['belong_to'];
								$dara_status = $dara_list[$i]['status'];
								$dara_approve = $dara_list[$i]['approve'];
								//$dara_status = ($dara_list[$i]['status'] == 1) ? $language['status'] : $lang['unpublish'];
								$lastupdate_date = $dara_list[$i]['lastupdate_date'];

								$url_preview = $this->config->config['www'].'/dara/'.$dara_type_id.'/'.$dara_profile_id.'/'.RewriteTitle($dara_type_name).'/'.RewriteTitle($full_name).'/?preview=1';

								if($dara_status == 2){
										$lnk_dara = "javascript:alert('this is deleted.');";
								}elseif($admin_type <= $access_level){
										$lnk_dara = base_url('dara/edit/'.$dara_profile_id);
								}else
										$lnk_dara = "javascript:alert('".$language['please_contact_admin'].".');";

?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" name="selectid[]" value="<?php //echo $tag_id ?>"/>
										<span class="lbl"></span>
								</label>
						</td> -->
						<td>
								<?=$number?>
								<input type="hidden" class="ace" name="selectid[]" value="<?=$dara_profile_id?>" />
						</td>
						<td><?php if($picture != '') echo '<a href="'.$lnk_dara.'">'.$picture.'</a>' ?></td>
						<td><a href="<?=$lnk_dara?>"><?=$full_name?></a></td>
						<td><a href="<?=$lnk_dara?>"><?=$nickname?></a></td>
						<td class="hidden-480"><?=$dara_type_name?></td>
						<td class="hidden-480"><?php echo RenDateTime($lastupdate_date)?></td>
						<td class="center hidden-480"><span class="col-sm-12">
								<label class="pull-right inline" id="enable<?=$dara_profile_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="status" class="ace ace-switch ace-switch-6 status-buttons" data-value="<?=$dara_profile_id?>" 
									<?php if($dara_status  == 1) echo 'checked';?>  value=1>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td class="center hidden-480"><span class="col-sm-12">
								<label class="pull-right inline" id="adminapprove<?=$dara_profile_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="approve" class="ace ace-switch ace-switch-4 approve-buttons" data-value="<?=$dara_profile_id?>" 
									<?php if($dara_approve  == 1) echo 'checked';?>  value=1 <?php if($this->session->userdata('admin_type') > 3) echo 'disabled' ?> >
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td> 
										<div class="hidden-sm hidden-xs action-buttons">
													<!-- <a class="blue" href="<?php echo $url_preview ?>" target=_blank>
															<i class="ace-icon fa fa-search-plus bigger-130"></i>
													</a> -->
													<a href="javascript:void(0);" class="blue" ><i class="ace-icon fa fa-search-plus bigger-130 bootbox-options" data-value="<?=$url_preview?>" data-name="<?=$full_name?>" data-rel="tooltip" title="<?php echo $language['preview'] ?>"></i></a>

													<a class="green" href="<?=$lnk_dara?>">
															<i class="ace-icon fa fa-pencil bigger-130"></i>
													</a>

													<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm<?php echo $dara_profile_id?>" data-value="<?php echo $dara_profile_id?>" data-name="<?php echo $nickname." ".$full_name?>">
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
																			<a href="javascript:void(0);<?php //echo $previewurl ?>" class="tooltip-info bootbox-options" data-value="<?=$url_preview?>" data-name="<?=$full_name?>" data-rel="tooltip" title="<?php echo $language['preview'] ?>">
																					<span class="blue">
																							<i class="ace-icon fa fa-search-plus bigger-120"></i>
																					</span>
																			</a>
																		</li>

																		<li>
																			<a href="<?php echo base_url('dara/edit/'.$dara_profile_id)?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="javascript:void(0);" id="bx-confirm<?=$dara_profile_id?>" class="tooltip-error del-confirm" data-value="<?php echo $dara_profile_id?>" data-name="<?php echo $nickname." ".$full_name?>" data-rel="tooltip" title="Delete">
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

<?php
	echo form_close();
	if($usedataTables == 0) echo $GenPage ;
?>
		</div><!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->

<script type="text/javascript">
jQuery(function($) {

		var $overflow = '';
		var colorbox_params = {
			rel: 'colorbox',
			reposition:true,
			scalePhotos:true,
			scrolling:false,
			previous:'<i class="ace-icon fa fa-arrow-left"></i>',
			next:'<i class="ace-icon fa fa-arrow-right"></i>',
			close:'&times;',
			current:'{current} of {total}',
			maxWidth:'100%',
			maxHeight:'100%',
			onOpen:function(){
				$overflow = document.body.style.overflow;
				document.body.style.overflow = 'hidden';
			},
			onClosed:function(){
				document.body.style.overflow = $overflow;
			},
			onComplete:function(){
				$.colorbox.resize();
			}
		};
		$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
		$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange'></i>");//let's add a custom loading icon

		$('.status-buttons').on('click', function() {
				var v = $(this).attr('data-value');
				//alert('status-buttons ' + v);
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('dara/status')?>/" + v,
					//data: {id: v},
					cache: false,
					success: function(data){
							$("#msg_info").fadeOut();
							if(data == 1){
								$("#msg_success").attr('style','display:block;');
								AlertSuccess	('Active.');
							}else{
								$("#msg_error").attr('style','display:block;');
								AlertError('Inactive');
							}
					}
				});
		});

		$('.approve-buttons').on('click', function() {

				var v = $(this).attr('data-value');
				//alert('approve-buttons ' + v);
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('dara/approve')?>/" + v,
					//data: {id: v},
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

		$('.del-confirm').on('click', function() {
				
				var id = $(this).attr('data-value');
				var name = $(this).attr('data-name');
				bootbox.confirm("<?php echo $language['are you sure to delete']?> " + name, function(result) {
					if(result) {
<?php
		if($admin_type <= $access_level){
?>
							window.location='<?php echo base_url('dara/delete')?>/' + id ;
<?php }else{
							//echo "AlertError('".$language['please_contact_admin']."');";
							echo "alert('".$language['please_contact_admin']."');";
} ?>
						}
				});
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
		//$('#dataTables-dara').dataTable();
		$('#dataTables-dara').dataTable( {
			"order": [[ 0, "asc" ]],
			stateSave: true
		} );
<?php } ?>

})
</script>