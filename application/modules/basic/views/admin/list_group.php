<?php $language = $this->lang->language; ?>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<!-- <button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('admin/memberadd') ?>';">
										<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add_member'] ?>
								</button> -->

								<div class="row">
									<div class="col-xs-12">
										<h3 class="header smaller lighter blue"><?php echo $language['admin_type'] ?></h3>
										<div class="table-header">
											<?php echo $language['admin_type'] ?>
										</div>

<?php
				if(function_exists('Debug')){
					//Debug($admintype);
				}
				/*$result_object = $all_member->result_object;
				if($result_object)
					foreach($result_object as $key ){
									echo $key.'<br>';
					}*/

		if(isset($error)){
?>
											<div class="alert alert-danger">
												<button data-dismiss="alert" class="close" type="button">
													<i class="ace-icon fa fa-times"></i>
												</button>

												<strong>
													<i class="ace-icon fa fa-times"></i>
													Oh snap!
												</strong>
												<?php echo $error?>.
												<br>
											</div>
<?php
		}

		if(isset($success)){
?>
										<div class="alert alert-block alert-success">
											<button data-dismiss="alert" class="close" type="button">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<p>
												<strong>
													<i class="ace-icon fa fa-check"></i>
													Update Member!
												</strong>
												You successfully read this important alert message.
											</p>
										</div>
<?php
		}
?>
				<div>
											<table id="sample-table-2" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<!-- <th class="center">
															<label class="position-relative">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</th> -->
														<th><?php echo $language['no'] ?></th>
														<th><?php echo $language['admin_type_title'] ?></th>
														<th class="hidden-480">
															<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
															<?php echo $language['create_date'] ?>
														</th>
														<!-- <th class="hidden-480">															
															<?php echo $language['lastupdate'] ?>
														</th> -->
														<th class="hidden-480"><?php echo $language['status'] ?></th>
														<th><?php echo $language['action'] ?></th>
													</tr>
												</thead>



	<tbody>
<?php
				$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));

				$i=0;
				if($admintype)
				foreach($admintype as $key => $arr_field){
						//if(trim($memberlist[$key]->_domain) != ""){

							//$displayexpire = ($displaydate < 0) ? '<span style="color:red">'.$expire_date.'</span>' : $expire_date;

							$admin_type_id = $admintype[$key]->admin_type_id;
							$admin_type_title = $admintype[$key]->admin_type_title;
							$create_date = $admintype[$key]->create_date;
							$create_by = $admintype[$key]->create_by;
							$lastupdate_date = $admintype[$key]->lastupdate_date;
							$lastupdate_by = $admintype[$key]->lastupdate_by;
							$status = $admintype[$key]->status;
?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" />
										<span class="lbl"></span>
								</label>
						</td> -->
						<td><?=$admin_type_id?><input type="hidden" name="admin_type_id" value="<?=$admin_type_id?>"></td>
						<td><?=$admin_type_title?></td>
						<td class="hidden-480"><?=$create_date?></td>
						<!-- <td class="hidden-480"><? //$lastupdate_date?></td> -->
						<td><span class="col-sm-6">
								<label class="pull-right inline" id="enable<?=$admin_type_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="status" id="status<?=$admin_type_id?>" class="ace ace-switch ace-switch-5" <?php if($status  == 1) echo 'checked';?>  value=1>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td> 
						
															<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a> -->

																<a class="green" href="<?php echo site_url('accessmenu/edit/'.$admin_type_id); ?>">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>
<!--
																<a class="red del-confirm" href="#" id="bootbox-confirm<?=$admin_type_id?>" data-value="<?=$admin_type_id?>" data-name="<?=$admin_type_title?>">
																	<i class="ace-icon fa fa-trash-o bigger-130"></i>
																</a>
-->
																<!-- <button id="bootbox-confirm" class="btn btn-info">Confirm</button> -->
															</div>

															<div class="hidden-md hidden-lg">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		 

																		<li>
																			<a href="<?php echo site_url('accessmenu/edit/'.$admin_type_id); ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																	 
																	</ul>
																</div>
															</div>						
						</td>
		</tr>
							<!-- 
									<td> &nbsp;<a href="<?php echo site_url('domains/show/'.$memberlist[$key]->_did); ?>"><b class="icon-pencil "></b> Edit</a> </td>
							</tr> -->
<?
							$i++;
						//}
				}
?>
	</tbody>
</table>
										</div>
									</div>
								</div>
						<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
<?php

	if($admintype)
	foreach($admintype as $key => $arr_field){
		$admin_type_id = $admintype[$key]->admin_type_id;
?>
		$('#status<?=$admin_type_id?>').on('click', function() {

				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);

				//$("#msg_info").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Please, wait...');
				//$("#msg_info,#BG_overlay").fadeIn();

				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('accessmenu/status/'.$admin_type_id)?>",
					//data: {id: res},
					cache: false,
					success: function(data){
							//$("#msg_info").fadeOut();
							$("#msg_error").attr('style','display:block;');
							AlertError('Can not Inactive');

							/*if(data == 0){
								$("#msg_error").attr('style','display:block;');
								AlertError('Inactive');
							}else{
								$("#msg_success").attr('style','display:block;');
								AlertSuccess	('Active.');
							}*/
					}
				});
		});
<?php

	}
?>
});
</script>
