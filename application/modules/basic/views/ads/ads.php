<?php 
		$language = $this->lang->language; 
		$i=0;
		//$maxcat = count($ads_type);
		//Debug($ads_list);			
		//die();
		if($this->input->get('success')) $success = $this->input->get('success');
		if($this->input->get('error')) $error = $this->input->get('error');
?>
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
?>
<div class="row">

	<div class="col-xs-12">

								<button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('ads/add') ?>';">
										<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['ads']  ?>
								</button>
								<form method="post" action="" id="listFrm">
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
														<th><?php echo $language['category'] ?></th>
														<th><?php echo $language['subcategory'] ?></th>
														<th><?php echo $language['title'] ?></th>
														<th class="hidden-480">
															<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
															<?php echo $language['create_date'] ?>
														</th>
														<th class="hidden-480">
															<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
															<?php echo $language['lastupdate'] ?>
														</th>
														<!-- <th class="hidden-480"><?php echo $language['status'] ?></th> -->
														<th><?php echo $language['action'] ?></th>
													</tr>
												</thead>
	<tbody>												
<?php
			$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
			$thumb = '';

			$allads = count($ads_list);
			if($ads_list)
					for($i=0;$i<$allads;$i++){

								//$pic = ($ads_list[$i]['avatar'] == '') ? 'theme/assets/avatars/avatar3.png' : 'uploads/ads/'.$ads_list[$i]['avatar'];
								//$thumb = $pic;

								$ads_id = $ads_list[$i]['ads_id'];
								//$title = $ads_list[$i]['tag_text'];

								$category_name = $ads_list[$i]['category_name'];
								$subcategory_name = $ads_list[$i]['subcategory_name'];

								if($ads_list[$i]['category_id'] == 0){ //Home
										$title = "Home";
										$category_name = "";
										$subcategory_name = "";
								}else if($ads_list[$i]['category_id'] == 99){ //Default
										$title = "Default";
										$category_name = "";
										$subcategory_name = "";
								}else if($ads_list[$i]['category_id'] == 98){ //Default 18+
										$title = "Default 18 +";
										$category_name = "";
										$subcategory_name = "";
								}else $title = "-";

								$create_date = $ads_list[$i]['create_date'];
								$lastupdate_date = $ads_list[$i]['lastupdate_date'];
								$ads_status = $ads_list[$i]['status'];
?>
		<tr>
						<td><?=$ads_id?></td>
						<td><?=$category_name?></td>
						<td><?=$subcategory_name?></td>
						<td><?=$title?></td>
						<td class="hidden-480"><?php echo RenDateTime($create_date) ?></td>
						<td class="hidden-480"><?php echo RenDateTime($lastupdate_date) ?></td>
						<td> 
								<div class="hidden-sm hidden-xs action-buttons">
										<a class="green" href="<?php echo site_url('ads/edit/'.$ads_id) ?>">
												<i class="ace-icon fa fa-pencil bigger-130"></i>
										</a>

										<?php if($ads_list[$i]['ads_id'] > 3){ ?>
										<a class="red del-confirm" href="#" id="bootbox-confirm<?=$ads_id?>" data-value="<?php echo $ads_id ?>" data-name="<?php echo $category_name.' '.$subcategory_name ?>">
												<i class="ace-icon fa fa-trash-o bigger-130"></i>
										</a>
										<?php }else{ ?>
										<i class="ace-icon fa fa-trash-o bigger-130 gray"></i>
										<?php } ?>
								</div>

								<div class="hidden-md hidden-lg">
										<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li>
																			<a href="<?php echo site_url('ads/edit/'.$ads_id) ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>
																		<li>
																		<?php if($ads_list[$i]['ads_id'] > 3){ ?>
																			<a href="#" class="tooltip-error del-confirm" data-value="<?php echo $ads_id ?>" data-name="<?php echo $category_name.' '.$subcategory_name ?>" data-rel="tooltip" title="Delete">
																				<span class="red">
																					<i class="ace-icon fa fa-trash-o bigger-120"></i>
																				</span>
																			</a>
																		<?php }else{ ?>
																			<span class="red">
																				<i class="ace-icon fa fa-trash-o bigger-120"></i>
																			</span>
																		<?php } ?>
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

						<div class="col-xs-12">
							<div class="col-xs-2">
								<div class="row">
										<span id="gen_api" class="btn btn-success tooltip-success" style="float:right" data-rel="tooltip" title="" data-original-title="Generate" data-placement="top"><?php echo $language['generate_catch']; ?></span>
								</div>
							</div>
							<div class="col-xs-12">
								<div class="row" id="preview_data">
								</div>
							</div>
						</div><!-- /.row -->

		</div><!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
		$('.del-confirm').on('click', function() {
<?php if($this->session->userdata('admin_type') < 3){ ?>
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
							alert('<?php echo base_url('ads/delete')?>/' + id + '?title=' + title);
							//window.location='<?php echo base_url('ads/delete')?>/' + id + '?title=' + title;
						}
				});
<?php }else{ ?>
			alert('<?php echo $language['cannotaccess'] ?>');
<?php } ?>
		});

		$('.status-buttons').on('click', function() {
				/*var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);*/

				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');

				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('news/status')?>/" + res,
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

		$('#gen_api').click(function(){
				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				Gen_API();
		});

});

function Gen_API(){
			//Gen API
			$.getJSON( "<?php echo $GenADS ?>", function( data ) {					
					//$('#preview_data').html('<?php echo $language['waiting_for_generate']; ?>');

					//alert(data.header.resultcode);
					if(data.header.resultcode == 200){

							AlertSuccess	('Generate API success.');
							//$('#preview_data').html('<div class="col-xs-12"><?php echo $language['generate_catch']; ?> <small><i class="ace-icon fa fa-angle-double-right"></i> <b>Mega Menu</b> success.</small></div>');
							WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
							Gen_www();
					}else{
							AlertError('Can not generate API.');
							//$('#preview_data').html('<div class="col-xs-12">Can not generate API Mega Menu</div>');

					}
			});	
}

function Gen_www(){

			//Gen Home Page
			$.getJSON( "<?php echo $GenHome ?>", function( rsponse ) {
					if(rsponse.meta.code == 200){
							AlertSuccess	('Building <?php echo $this->config->config['www']; ?> success.');
					}else{
							AlertError('Can not generate <?php echo $this->config->config['www']; ?>');
					}
			});	

}
</script>

<?php //echo js_asset('checkall.js'); ?>