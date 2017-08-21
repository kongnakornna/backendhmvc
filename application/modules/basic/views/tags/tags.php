<?php 
		$language = $this->lang->language; 
		$i=0;
		$access_level = $this->config->config['level'];
		//$maxcat = count($tags_type);
		//Debug($tags_list);			
		//Debug($flash_message);			
		//die();
		if($this->input->get('success')) $success = $this->input->get('success');
		if($this->input->get('error')) $error = $this->input->get('error');
?>
<div class="col-xs-12">

								<button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('tags/add') ?>';">
										<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['tags']  ?>
								</button>

								<?php if(isset($count_tags)) echo number_format(count($count_tags)).' record'; ?>
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

			if(isset($success)){
?>
							<div class="alert alert-success">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="glyphicon glyphicon-ok"></i>
											</strong><?php echo $success?>.
									<br>
							</div>
<?php
			}
?>

<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open('tags', $attributes);
?>
											<table id="dataTables-tags" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<!-- <th class="center">
															<label class="position-relative">
																<input type="checkbox" class="ace" id="checkall" />
																<span class="lbl"></span>
															</label>
														</th> -->
														<th>ID</th>
														<th><?php echo $language['tags'] ?></th>
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

			$alltags = count($tags_list);
			if($tags_list)
					for($i=0;$i<$alltags;$i++){

								//$pic = ($tags_list[$i]['avatar'] == '') ? 'theme/assets/avatars/avatar3.png' : 'uploads/tags/'.$tags_list[$i]['avatar'];
								//$thumb = $pic;

								$tag_id = $tags_list[$i]['tag_id'];
								$title = $tags_list[$i]['tag_text'];
								$create_date = RenDateTime($tags_list[$i]['create_date']);
								$tag_status = $tags_list[$i]['status'];

						if($this->session->userdata('admin_type') > $access_level) 
							$edit_tag = "javascript:alert('".$language['please_contact_admin'].".');";
						else
							$edit_tag = site_url('tags/edit/'.$tag_id) ;
?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" name="selectid[]" value="<?php echo $tag_id ?>"/>
										<span class="lbl"></span>
								</label>
						</td> -->
						<td>
								<?=$tag_id?>
								<input type="hidden" class="ace" name="selectid[]" value="<?=$tag_id?>" />
						</td>
						<td><a href="<?php echo $edit_tag ?>"><?=$title?></a></td>
						<td class="hidden-480"><?php echo $create_date?></td>
						<td class="hidden-480"><span class="col-sm-12">
								<label class="pull-right inline" id="enable<?=$tag_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="status"  id="status<?=$tag_id?>" class="ace ace-switch ace-switch-4 status-buttons" data-value="<?php echo $tag_id?>" <?php if($tag_status  == 1) echo 'checked';?>  value=1 <?php if($this->session->userdata('admin_type') > $access_level) echo 'disabled' ?>>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td> 
						
															<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a> -->

																<a class="green" href="<?php echo $edit_tag ?>">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>

																<a class="red del-confirm" href="#" id="bootbox-confirm<?=$tag_id?>" data-value="<?php echo $tag_id?>" data-name="<?=$title?>">
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
																			<a href="#" class="tooltip-info" data-rel="tooltip" title="View">
																				<span class="blue">
																					<i class="ace-icon fa fa-search-plus bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="<?php echo $edit_tag ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="#" id="bootbox-confirm" class="tooltip-error del-confirm" data-value="<?php echo $tag_id?>" data-name="<?=$title?>" data-rel="tooltip" title="Delete">
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
</form>
										<!-- <li>
											<a href="../assets/images/gallery/image-4.jpg" data-rel="colorbox">
												<img width="150" height="150" alt="150x150" src="../assets/images/gallery/thumb-4.jpg" />
												<div class="tags">
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
				/*var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);*/
				var tagid = $(this).attr('data-value');

				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('tags/status')?>/" + tagid,
					//data: {id: res},
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
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
<?php
		if($this->session->userdata('admin_type') <= $access_level){
?>
							//alert('ยังไม่เปิดใช้งาน');
							window.location='<?php echo base_url('tags/delete')?>/' + id  + '?tag_text=' + name;
<?php }else{
							//echo "AlertError('".$language['please_contact_admin']."');";
							echo "alert('".$language['please_contact_admin']."');";
} ?>
						}
				});
		});

		$('#saveorder').on('click', function() {
				document.getElementById("jform").submit();
		});	

		$('#dataTables-tags').dataTable({
				"order": [[ 0, "desc" ]],
				stateSave: true
		});
});
</script>

<?php //echo js_asset('checkall.js'); ?>