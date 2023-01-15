<?php 
		$language = $this->lang->language; 
		$i=0;
		$access_level = $this->config->config['level'];
		//$maxcat = count($news_type);
		//Debug($columnist_list);			
		//die();		
?>
<div class="col-xs-12">
		<button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('columnist/add') ?>';">
				<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['columnist']  ?>
		</button>
<?php
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
											<i class="ace-icon fa fa-times"></i>
											</strong><?php echo $success?>.
									<br>
							</div>
				</div>
<?php
			}
?>
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open('columnist', $attributes);
?>
											<table id="sample-table-2" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<!-- <th class="center">
															<label class="position-relative">
																<input type="checkbox" class="ace" id="checkall" />
																<span class="lbl"></span>
															</label>
														</th> -->
														<th>ID</th>
														<th><?php echo $language['columnist'] ?></th>														
														
														<th class="hidden-480">
															<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
															<?php echo $language['create_date'] ?>
														</th>
														<th class="hidden-480"><?php echo $language['status'] ?></th>
														<th><?php echo $language['action'] ?></th>
													</tr>
												</thead>
	<tbody>												
<?php
			$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
			$thumb = '';

			$allcolumnist = count($columnist_list);
			if($columnist_list)
					for($i=0;$i<$allcolumnist;$i++){
								
						//$pic = ($columnist_list[$i]['avatar'] == '') ? 'theme/assets/avatars/avatar3.png' : 'uploads/columnist/'.$columnist_list[$i]['avatar'];

						$columnist_id = $columnist_list[$i]['columnist_id'];
						$columnist_name = $columnist_list[$i]['columnist_name'];
						$full_name = $columnist_list[$i]['full_name'];
						$create_date = $columnist_list[$i]['create_date'];
						//$can_comment = $columnist_list[$i]['can_comment'];
						$status = $columnist_list[$i]['status'];

						if($this->session->userdata('admin_type') > $access_level) 
							$edit_columnist = "javascript:alert('".$language['please_contact_admin'].".');";
						else
							$edit_columnist = site_url('columnist/edit/'.$columnist_id);
								
								
?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" name="selectid[]"  />
										<span class="lbl"></span>
								</label>
						</td> -->
						<td><?=$columnist_id?></td>
						<td><a href="<?php echo $edit_columnist ?>"><?=$columnist_name?></a></td>
						
						<td class="hidden-480"><?php echo RenDateTime($create_date)?></td>
						<td class="hidden-480"><span class="col-sm-6">
								<label class="pull-right inline" id="enable<?php echo $columnist_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="status"  id="status<?=$columnist_id?>" class="ace ace-switch ace-switch-4 status-buttons" 
									<?php if($status  == 1) echo 'checked';?>  value=1 <?php if($this->session->userdata('admin_type') > $access_level) echo 'disabled' ?>>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td>

												<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a> -->

																<a class="green" href="<?php echo $edit_columnist ?>">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>

																<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm<?=$columnist_id?>" data-value="<?=$columnist_id?>" data-name="<?=$columnist_name?>">
																	<i class="ace-icon fa fa-trash-o bigger-130"></i>
																</a>
												</div>

															<div class="hidden-md hidden-lg">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<!-- <li>
																			<a href="javascript:void(0);" class="tooltip-info" data-rel="tooltip" title="View">
																				<span class="blue">
																					<i class="ace-icon fa fa-search-plus bigger-120"></i>
																				</span>
																			</a>
																		</li> -->

																		<li>
																			<a href="<?php echo $edit_columnist ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="javascript:void(0);" id="bx-confirm<?=$columnist_id?>" class="tooltip-error del-confirm" data-value="<?=$columnist_id?>" data-name="<?=$columnist_name?>" data-rel="tooltip" title="Delete">
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
?>
									</ul>
								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
					</form>
<script type="text/javascript">
$( document ).ready(function() {
/*
<?php
		if($columnist_list)
		for($i=0;$i<count($columnist_list);$i++){
		//foreach($memberlist as $key => $arr_field){
	
?>
		$('#status<?=$columnist_list[$i]["columnist_id"]?>').on('click', function() {

				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);

				//alert($(this).attr('id'));
				//alert($(this).val());

				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('columnist/status/'.$columnist_list[$i]['columnist_id'])?>",
					//data: {id: res},
					cache: false,
					success: function(data){
							//alert(data);
							if(data == 0){
								//$("#msg_my_alert ").attr('style','background: none repeat scroll 0 0 #cc0000;');
								//AlertMsg('Inactive');
								$("#msg_error").attr('style','display:block;');
								AlertError('Inactive');
							}else{
								//$("#msg_my_alert ").attr('style','background: none repeat scroll 0 0 #438eb9;');
								//AlertMsg('Active');
								$("#msg_success").attr('style','display:block;');
								AlertSuccess	('Active');
							}
					}
				});
				
		});

		$('#bootbox-confirm<?=$columnist_list[$i]["columnist_id"]?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
						if(result) {
								//alert('del');
								window.location='<?php echo base_url('columnist/delete/'.$columnist_list[$i]['columnist_id'])?>';
						}
					});
		});

		$('#bx-confirm<?=$columnist_list[$i]["columnist_id"]?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
						if(result) {
								//alert('del');
								window.location='<?php echo base_url('columnist/delete/'.$columnist_list[$i]['columnist_id'])?>';
						}
					});
		});
<?php
				}	
?>*/


		$('.status-buttons').on('click', function() {
				var v = $(this).attr('data-value');
				//alert('status-buttons ' + v);
<?php
		if($this->session->userdata('admin_type') <= $access_level){
?>
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('columnist/status')?>/" + v,
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
<?php }else{
					//echo "AlertError('".$language['please_contact_admin']."');";
					echo "alert('".$language['please_contact_admin']."');";
} ?>
		});

		$('.approve-buttons').on('click', function() {

				var v = $(this).attr('data-value');
				//alert('approve-buttons ' + v);

				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('columnist/approve')?>/" + v,
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
		if($this->session->userdata('admin_type') <= 3){
?>
							window.location='<?php echo base_url('columnist/delete')?>/' + id ;
<?php }else{
							//echo "AlertError('".$language['please_contact_admin']."');";
							echo "alert('".$language['please_contact_admin']."');";
} ?>
						}
				});
		});
});
</script>

<?php //echo js_asset('checkall.js'); ?>
