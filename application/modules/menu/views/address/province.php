<?php 
		
		$language = $this->lang->language; 
		$i=0;
		
		$geo= $this->uri->segment(3); 
		$countries_id= $this->uri->segment(4); 
		$maxpid = count($province);
		$display_province = '';
       
$base_url=base_url('geography');   
if($geo==''){  
?>
<META HTTP-EQUIV="Refresh" CONTENT="0;URL=<?php echo $base_url; ?>">
<?php
exit();
 } 
?>
		

<style type="text/css">
.col-sm-6 {width: 100%;text-align: center;	}
#saveorder{cursor:pointer;}
.input-small{text-align: center;}
</style>
						<!-- <div class="page-header">
							<h1>
							<?php //echo $HeadTitle?>
							<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								<?php //echo $HeadTitle?>
							</small>
							</h1>
						</div> -->

						<div class="row">
							<div class="col-xs-12">
								 

								<div class="row">
									<div class="col-xs-12">

									  <h3  class="header smaller lighter blue">
                                        <a href="<?php echo site_url('geography'); ?>"><?php echo $language['geography']?> </a>
											
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
											<?php 
											 											?>
											</small>
									  </h3>



				<!-- <code><?php //echo $sql; ?></code> -->
<?php
				if(function_exists('Debug')){
					//Debug($this->db) ;
					//Debug($province_name, 'province_name');
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
											Oh snap!</strong><?php echo $error?>.
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
	echo form_open('province/pid/'.$this->uri->segment(3), $attributes);
?>
											<table width="100%" class="table table-striped table-bordered table-hover" id="sample-table-2">
												<thead>
													<tr>
														<!-- <th class="center">
															<label class="position-relative">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</th> -->
														<th width="8%"><ID</th>
														<th width="8%"><?php echo $language['no'] ?></th>
														<th width="78%"><?php echo $language['name'] ?></th>
														<th width="14%"><?php echo $language['amphur'] ?></th>
													</tr>
												</thead>



	<tbody>
<?php
				$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));

				$i=1;
				//$maxpid = count($province);
				if($province)
				for($key=0;$key<$maxpid;$key++){
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
						<td>
								<?=$province[$key]['province_id']?>
								<input type="hidden" class="ace" name="selectid[]" value="<?=$province[$key]['province_id_map']?>" />
						</td>
						<td>
								<?php echo $i;?>
						</td>
						<td>
						
						
<a href="<?php echo site_url('amphur/pid/'.$province[$key]['province_id_map'].'/'.$geo.'/'.$countries_id.''); ?>"><?=$province[$key]['province_name']?> </a>
						
<?php 

      $province_id_map=$province[$key]['province_id_map']; 
      $districtcount = $this->Amphur_model->amphur_count_id($province_id_map,$language); 
	  echo ' ('.$districtcount.')';
?> 					
							
							
						</td>
						<td> 
						
															<div class="hidden-sm hidden-xs action-buttons">
																 <?php  echo $districtcount; ?>
 
															</div>

															<div class="hidden-md hidden-lg">
																<div class="inline position-relative">
	 <?php  echo $districtcount; ?>

															  </div>
															</div>						
						</td>
		</tr>
							<!-- 
									<td> &nbsp;<a href="<?php echo site_url('domains/show/'.$memberlist[$key]->_did); ?>"><b class="icon-pencil "></b> Edit</a> </td>
							</tr> -->
<?php
							$i++;
						//}
				}
?>
	</tbody>
</table>
<input type="hidden" class="ace" name="province_id" value="<?=$this->uri->segment(3)?>" />
<?php
	echo form_close();
?>
									  </div>
									</div>
								</div>
						<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
<?php //Debug($province); ?>
<script type="text/javascript">
$( document ).ready(function() {
<?php
		if($province)		
				for($key=0;$key<$maxpid;$key++){
	
?>
		$('#status<?=$province[$key]['province_id_map']?>').on('click', function() {

				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);
		    
				//alert($(this).attr('id'));
				//alert($(this).val());
				
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('province/status/'.$province[$key]['province_id_map'])?>",
					//data: {id: res},
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

		$('#bootbox-confirm<?=$province[$key]["province_id_map"]?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
						if(result) {
								//alert('del');
								window.lopidion='<?php echo base_url('province/delete/'.$province[$key]['province_id_map'])?>';
						}
					});
		});

		$('#bx-confirm<?=$province[$key]["province_id_map"]?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
						if(result) {
								//alert('del');
								window.lopidion='<?php echo base_url('province/delete/'.$province[$key]['province_id_map'])?>';
						}
					});
		});

<?php
				}	
?>
		$('#saveorder').on('click', function() {
			document.getElementById("jform").submit();
		});		
});

</script>