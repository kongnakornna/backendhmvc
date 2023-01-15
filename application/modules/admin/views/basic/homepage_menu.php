<?php 
		$language = $this->lang->language; 
		$i=0;
		$maxcat = count($web_menu);
?>
<style type="text/css">
.col-sm-6 {width: 100%;text-align: center;	}
#saveorder{cursor:pointer;}
.input-small{text-align: center;}
</style>


						<div class="row">
							<div class="col-xs-12">

								<button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('homepage_menu/add') ?>';">
										<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'] ?>
								</button>

								<div class="row">
									<div class="col-xs-12">
										<h3 class="header smaller lighter blue"><?php echo $language['homepage_menu'] ?></h3>
										<div class="table-header">
											<?php echo $language['homepage_menu'] ?>
										</div>

				<!-- <code><?php //echo $sql; ?></code> -->
<?php
				if(function_exists('Debug')){
					//Debug($this->db) ;
					//Debug($this->session->userdata);
					//Debug($this->lang->language);
				}
?>
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
				<div>

<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open('homepage_menu', $attributes);
?>		
											<table id="sample-table-2" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<!-- <th class="center">
															<label class="position-relative">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</th> -->
														<th>ID</th>
														<th width="40%"><?php echo $language['name'] ?></th>
														<th><?php echo $language['submenu'] ?></th>
														<th>
															<?php echo @($language['order_by']) ? $language['order_by'] : 'Order by' ?>
															&nbsp;<i class="ace-icon fa fa-floppy-o bigger-120 blue" id="saveorder"></i>
														</th>
														<th>
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

				$i=0;
				//$maxcat = count($category);
				if($web_menu)
				for($key=0;$key<$maxcat;$key++){
						//if(trim($memberlist[$key]->_domain) != ""){

							//$displayexpire = ($displaydate < 0) ? '<span style="color:red">'.$expire_date.'</span>' : $expire_date;
							/*if($memberlist[$key]->_admin_type_id == 1){
								$admin_type_id = "Superadmin";
							}else if($memberlist[$key]->_admin_type_id == 2){
								$admin_type_id = "Admin";
							}else if($memberlist[$key]->_admin_type_id == 3){
								$admin_type_id = "Manager";
							}else
								$admin_type_id = "Web content";*/
?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" />
										<span class="lbl"></span>
								</label>
						</td> -->
						<td><?=$web_menu[$key]['web_menu_id']?><input type="hidden" class="ace" name="selectid[]" value="<?php echo $web_menu[$key]['web_menu_id']?>" /></td>
						<td><a href="<?php echo site_url('homepage_menu/view/'.$web_menu[$key]['web_menu_id2']); ?>"><?=$web_menu[$key]['title']?></a></td>
						<td><?=$web_menu[$key]['count_sub']?></td>
						<td><input class="input-small" name="order[]" type="text" id="order_by" placeholder="Not null" value="<?=$web_menu[$key]['order_by']?>"></td>
						<td><?=$web_menu[$key]['create_date']?></td>
						<td><span class="col-sm-6">
								<small class="muted"></small>
									<input type="checkbox" name="status" id="status<?=$web_menu[$key]['web_menu_id2']?>" class="ace ace-switch ace-switch-4" <?php if($web_menu[$key]['status'] == 1) echo 'checked';?>  value=1 >
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td> 
						
															<div class="hidden-sm hidden-xs action-buttons">																

																<a class="green" href="<?php echo site_url('homepage_menu/edit/'.$web_menu[$key]['web_menu_id2']); ?>">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>

																<a class="red del-confirm" href="#" id="bootbox-confirm<?=$web_menu[$key]['web_menu_id2']?>" data-value="<?=$web_menu[$key]['web_menu_id']?>" data-name="<?=$web_menu[$key]['title']?>">
																	<i class="ace-icon fa fa-trash-o bigger-130"></i>
																</a>
															</div>
															<!-- hidden-lg -->
															<div class="hidden-md hidden-lg">
																<div class="inline position-relative">

																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">

																		<li>
																			<a href="<?php echo site_url('homepage_menu/edit/'.$web_menu[$key]['web_menu_id2']); ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="#" id="bx-confirm<?=$web_menu[$key]['web_menu_id2']?>" class="tooltip-error" data-rel="tooltip" title="Delete">
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
							<!-- 
									<td> &nbsp;<a href="<?php //echo site_url('domains/show/'.$memberlist[$key]->_did); ?>"><b class="icon-pencil "></b> Edit</a> </td>
							</tr> -->
<?php
							$i++;
						//}
				}
?>
	</tbody>
</table>
<?php
	echo form_close();
?>
										</div>
									</div>

				<div style="clear: both;"></div>
						<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
											&nbsp; &nbsp; &nbsp;

											<button type="button" class="btn btn-success" id="gen_json">
												<i class="ace-icon glyphicon glyphicon-file bigger-110"></i> <?php echo $language['generate_catch']; ?>
											</button>
									</div>
						</div>
				</div>

				<div class="col-sm-12">
							<div id="gen_data"></div>
				</div>

			</div>
			<!-- PAGE CONTENT ENDS -->

							</div><!-- /.col -->
						</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
<?php
		if($web_menu)		
				for($key=0;$key<$maxcat;$key++){
						
						$title = $web_menu[$key]['title'];
?>
		$('#status<?=$web_menu[$key]['web_menu_id2']?>').on('click', function() {

				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);
		    
				//alert($(this).attr('id'));
				//alert($(this).val());
				
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('homepage_menu/status/'.$web_menu[$key]['web_menu_id2'])?>",
					cache: false,
					success: function(data){
							if(data == 0){
								$("#msg_error").attr('style','display:block;');
								AlertError('Inactive');
							}else{
								$("#msg_success").attr('style','display:block;');
								AlertSuccess	('Active');
							}
					}
				});				
		});

		$('#bootbox-confirm<?php echo $web_menu[$key]['web_menu_id2']?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete'].' '.$title?>", function(result) {
						if(result) {
								//alert('del');
								window.location='<?php echo base_url('homepage_menu/delete/'.$web_menu[$key]['web_menu_id2'])?>';
						}
					});
		});

		$('#bx-confirm<?=$web_menu[$key]['web_menu_id2']?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete'].' '.$title?>", function(result) {
						if(result) {
								//alert('del');
								window.location='<?php echo base_url('homepage_menu/delete/'.$web_menu[$key]['web_menu_id2'])?>';
						}
					});
		});

<?php
				}	
?>
		$('#saveorder').on('click', function() {
			document.getElementById("jform").submit();
		});		
<?php
//http://daraapi.siamsport.co.th/api/web-api.php?method=MegaMenu&key=mMs3dAkM&gen_file=1 
//Mega	
?>
		$('#gen_json').on('click', function(e){

				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');

				//$('#gen_data').html('<iframe frameborder="0"scrolling="Yes" src="<?php echo $this->config->config['api']; ?>/api/web-api.php?method=MegaMenu&amp;key=<?php echo $this->config->config['api_key']; ?>&amp;gen_file=1" width="100%" height=100px></iframe>');

				/*$.ajax({
						type: 'GET',
						url: "<?php echo $this->config->config['api']; ?>/api/web-api.php?method=MegaMenu&key=<?php echo $this->config->config['api_key']; ?>&gen_file=1",
						success: function(data){
							alert(data);
							//$('#gen_data').html(data);
						}
				});*/

				$.getJSON( "<?php echo $this->config->config['api']; ?>/api/web-api.php?method=MegaMenu&key=<?php echo $this->config->config['api_key']; ?>&gen_file=1", function( rsponse ) {
						if(rsponse.header.resultcode == 200){
								AlertSuccess	('Generate megamenu success.');
								WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
								Gen_www();
						}else{
								AlertError('Can not generate <?php echo $this->config->config['www']; ?>');
						}
				});	

		});
});


function Gen_www(){

			//Gen Home Page
			$.getJSON( "<?php echo $home ?>", function( rsponse ) {
					if(rsponse.meta.code == 200){
							AlertSuccess	('Building <?php echo $this->config->config['www']; ?> success.');
					}else{
							AlertError('Can not generate <?php echo $this->config->config['www']; ?>');
					}
			});	

}
</script>