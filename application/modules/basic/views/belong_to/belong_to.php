<?php 
		$language = $this->lang->language; 
		$i=0;
		$access_level = $this->config->config['level'];
		//$maxcat = count($belong_to_type);
		//Debug($belong_to_list);			
?>
<div class="col-xs-12">

								<button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('belong_to/add') ?>';">
										<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['belong_to']  ?>
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
	//Debug($belong_to_list);
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
														<th><?php echo $language['belong_to'] ?></th>														
														<th><?php echo $language['create_date'] ?></th>														
														<th><?php echo $language['create_by'] ?></th>														

														<th class="hidden-480"><?php echo $language['status'] ?></th>
														<th><?php echo $language['action'] ?></th>
													</tr>
												</thead>
	<tbody>												
<?php
			$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
			$thumb = '';

			$allbelong_to = count($belong_to_list);
			if(isset($belong_to_list))
					for($i=0;$i<$allbelong_to;$i++){

								//$pic = ($belong_to_list[$i]['avatar'] == '') ? 'theme/assets/avatars/avatar3.png' : 'uploads/belong_to/'.$belong_to_list[$i]['avatar'];
								//$thumb = $pic;

								$belong_to_id = $belong_to_list[$i]->belong_to_id;
								$title = $belong_to_list[$i]->belong_to;
								$create_date = $belong_to_list[$i]->create_date;
								//$create_by = $belong_to_list[$i]->create_by;
								$create_by = $belong_to_list[$i]->admin;
								$belong_to_status = $belong_to_list[$i]->status;

						if($this->session->userdata('admin_type') > $access_level) 
							$edit_belong = "javascript:alert('".$language['please_contact_admin'].".');";
						else
							$edit_belong = site_url('belong_to/edit/'.$belong_to_id) ;
?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" name="selectid[]" value="<?php echo $belong_to_id ?>"/>
										<span class="lbl"></span>
								</label>
						</td> -->
						<td><?=$belong_to_id?></td>
						<td><a href="<?php echo $edit_belong ?>"><?=$title?></a></td>
						<td><?=RenDateTime($create_date)?></td>
						<td><?=$create_by?></td>

						<td class="hidden-480"><span class="col-sm-6">
								<label class="pull-right inline" id="enable<?=$belong_to_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="status" data-value="<?=$belong_to_id?>" id="status<?=$belong_to_id?>" class="ace ace-switch ace-switch-4 status-buttons" 
									<?php if($belong_to_status  == 1) echo 'checked';?>  value=1 <?php if($this->session->userdata('admin_type') > $access_level) echo 'disabled' ?>>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td> 
						
															<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a> -->

																<a class="green" href="<?php echo $edit_belong ?>">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>

																<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm<?=$belong_to_id?>" data-value="<?=$belong_to_id?>" data-name="<?=$title?>">
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
																		<!-- <li>
																			<a href="#" class="tooltip-info" data-rel="tooltip" title="View">
																				<span class="blue">
																					<i class="ace-icon fa fa-search-plus bigger-120"></i>
																				</span>
																			</a>
																		</li> -->

																		<li>
																			<a href="<?php echo $edit_belong; ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="javascript:void(0);" id="bootbox-confirm" class="tooltip-error del-confirm" data-value="<?=$belong_to_id?>" data-name="<?=$title?>" data-rel="tooltip" title="Delete">
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
										<!-- <li>
											<a href="../assets/images/gallery/image-4.jpg" data-rel="colorbox">
												<img width="150" height="150" alt="150x150" src="../assets/images/gallery/thumb-4.jpg" />
												<div class="belong_to">
													<span class="label-holder">
														<span class="label label-info arrowed">fountain</span>
													</span>

													<span class="label-holder">
														<span class="label label-danger">recreation</span>
													</span>

												</div>
											</a>

											<div class="tools tools-top">
												<a href="#">
													<i class="ace-icon fa fa-link"></i>
												</a>

												<a href="#">
													<i class="ace-icon fa fa-paperclip"></i>
												</a>

												<a href="#">
													<i class="ace-icon fa fa-pencil"></i>
												</a>

												<a href="#">
													<i class="ace-icon fa fa-times red"></i>
												</a>
											</div>
										</li> -->

									</ul>
								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->

<script type="text/javascript">
$( document ).ready(function() {

		$('.status-buttons').on('click', function() {
				var v = $(this).attr('data-value');

				/*var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);*/

				//alert('status-buttons ' + v);
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('belong_to/status')?>/" + v,
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

		$('.del-confirm').on('click', function() {
				
				var id = $(this).attr('data-value');
				var name = $(this).attr('data-name');
				bootbox.confirm("<?php echo $language['are you sure to delete']?> " + name, function(result) {
					if(result) {
<?php
		if($this->session->userdata('admin_type') <= 3){
?>
							window.location='<?php echo base_url('belong_to/delete')?>/' + id ;
<?php }else{
							//echo "AlertError('".$language['please_contact_admin']."');";
							echo "alert('".$language['please_contact_admin']."');";
} ?>
						}
				});
		});

});

</script>

<?php echo js_asset('checkall.js'); ?>