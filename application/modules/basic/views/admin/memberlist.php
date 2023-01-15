<?php 
	$language = $this->lang->language; 
	$usedataTables = 1;
?>
<style type="text/css">
.user-photo {
    margin: -4px 8px 0px 0px;
    border-radius: 100%;
    border: 2px solid #FFF;
    max-width: 40px;
}	
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
<?php 
	$attributes = array('class' => 'form-horizontal', 'name' => 'adminform',  'id' => 'adminform');
	echo form_open_multipart('admin/memberlist', $attributes);
	//admin_type_id
?>


<div class="col-xs-12">
<button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('admin/memberadd') ?>';">
	 <i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add_member'] ?>
</button> 							
</div>
	<hr/>								
							<!-- PAGE CONTENT BEGINS -->
							<div class="col-xs-12">
										<h3 class="header smaller lighter blue"><?php echo $language['member_list'] ?></h3>
										<div class="table-header">
											<?php //echo $language['member_list'] ?>
										 
										<?php echo 'พบ สมาชิก จำนวน '.count($memberlist).' คน'; ?>
										<!-- <h3 class="header smaller lighter blue"><?php echo $language['member_list'] ?></h3>
										<div class="table-header">
											<?php echo $language['member_list'] ?>
										</div> -->
									</div>
							</div>
							

<?php echo form_close();?>

				<!-- <code><?php //echo $sql; ?></code> -->
<?php
				if(function_exists('Debug')){
					//Debug($this->db) ;
					//Debug($all_member);
					//Debug($memberlist);
					//Debug($getAdminType);
				}
				/*echo "<pre>";
				var_dump($this);
				echo "</pre>";*/

				/*$result_object = $all_member->result_object;
				if($result_object)
					foreach($result_object as $key ){
									echo $key.'<br>';
					}*/
			
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
		<div class="col-xs-12">
				<form method="post" action="" id="listFrm">
						<div class="table-responsive">
								<table id="dataTables-member" class="table table-striped table-bordered table-hover" width="100%">
									<thead>
										<tr>
											<th><?php echo $language['no'] ?></th>
                                            <th class="center">Picture</th>
											<th class="center"><?php echo $language['fullname'] ?></th>
											<th class="center"><?php echo $language['email'] ?></th>
											<th class="center hidden-480"><?php echo $language['admin_level'] ?></th>
											<th class="hidden-480"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i><?php echo $language['department'] ?></th>
											<th class="center hidden-480"><?php echo $language['status'] ?></th>
											<th class="center"><?php echo $language['action'] ?></th>
										</tr>
									</thead>

				<tbody>
<?php
				//Debug($memberlist);
				$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
				$i=0;
				if($memberlist)
				foreach($memberlist as $key => $arr_field){

							for($j=0;$j<count($getAdminType);$j++){

									$member_type = (isset($memberlist[$key]->_admin_type_id)) ? $memberlist[$key]->_admin_type_id : $memberlist->_admin_type_id;
									if($member_type == $getAdminType[$j]->admin_type_id){
										$admin_type = $getAdminType[$j]->admin_type_title;
										if($memberlist[$key]->_admin_type_id == 1 || $memberlist[$key]->_admin_type_id == 2)// Develop & admin
												$admin_type = '<span class="label label-success arrowed-in arrowed-in-right">'.$admin_type.'</span>';
										else if($memberlist[$key]->_admin_type_id == 3 || $memberlist[$key]->_admin_type_id == 4) //Manager & Assistant Manager
												$admin_type = '<span class="label label-info arrowed-in arrowed-in-right">'.$admin_type.'</span>';
										else if($memberlist[$key]->_admin_type_id == 5 || $memberlist[$key]->_admin_type_id == 6) //Content 
												$admin_type = '<span class="label label-danger arrowed-in arrowed-in-right">'.$admin_type.'</span>';
										else
												$admin_type = '<span class="label label-warning arrowed-in arrowed-in-right">'.$admin_type.'</span>';
									}											
							}

						 

							//.ace-nav .nav-user-photo
							$fullname=$memberlist[$key]->_admin_name;
							if($memberlist[$key]->_admin_avatar != ''){
								$avatar = $memberlist[$key]->_admin_avatar;
								if(file_exists('./uploads/admin/'.$avatar)){
									$avatar = base_url('uploads/admin/'.$avatar);
									$img_user = '<img class="user-photo" src="'.$avatar.'" height="40" alt="'.$fullname.'\'s Photo">';
								}else
									$img_user = '';
							}else
								$img_user = '';

?>
			<tr>

						<td class="center">
						<?php
						$admin_idna=$memberlist[$key]->_admin_id;
						# echo $admin_idna;
						$i2=$i+1;
						echo $i2;
						?>
						</td>
						<td class="center"><?=$img_user?></td>
						<td><?=$memberlist[$key]->_admin_name?> <?=$memberlist[$key]->_admin_lastname?></td>
						<td><?=$memberlist[$key]->_admin_email?></td>
						<td class="center hidden-480"><?=$admin_type?></td>
						<td class="hidden-480">
						<?php $department=$memberlist[$key]->_department; 
						if($department<>''){echo $department;}else if($department==''){echo '--------------';}
						?></td>
						<td class="hidden-480">
								<!-- <span class="col-sm-6">
								<label class="pull-right inline" id="enable<?=$memberlist[$key]->_admin_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="status"  id="status<?=$memberlist[$key]->_admin_id?>" class="ace ace-switch ace-switch-5" <?php if($memberlist[$key]->_status  == 1) echo 'checked';?>  value=1>
								<span class="lbl middle"></span>
								</label>
								</span> -->

								<label>
<?php 
//$this->session->userdata('admin_id');
$user_id=$memberlist[$key]->_admin_id;
if($user_id>=4 || $admin_type==1){ 
?>
<input name="status[]" id="status<?=$memberlist[$key]->_admin_id?>" class="ace ace-switch ace-switch-4 btn-empty" type="checkbox" <?php if($memberlist[$key]->_status == 1) echo 'checked';?>  value=1>
<?php }else{ echo $this->lang->line('fobidden'); }?>										
										<span class="lbl"></span>
								</label>
						</td>
						<td> 
	<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a> -->
<?php 
//$this->session->userdata('admin_id');
$user_id=$memberlist[$key]->_admin_id;
if($user_id>=1 || $admin_type==1){ 
?>
<a class="green" href="<?php echo site_url('admin/memberedit/'.$memberlist[$key]->_admin_id); ?>"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
<a class="red del-confirm" href="#" id="bx-confirm<?=$memberlist[$key]->_admin_id?>" data-value="<?=$memberlist[$key]->_admin_id?>" data-name="<?=$memberlist[$key]->_admin_name?>">
<i class="ace-icon fa fa-trash-o bigger-130"></i> 
</a>
<?php }else{ echo $this->lang->line('fobidden'); }?>
																<!-- 
																<a href="#" id="id-btn-dialog2" class="btn btn-info btn-sm">Confirm Dialog</a> 
																-->

																<!-- #dialog-confirm -->
																<!-- <div id="dialog-confirm" class="hide">
																	<div class="alert alert-info bigger-110">
																		These items will be permanently deleted and cannot be recovered.
																	</div>

																	<div class="space-6"></div>

																	<p class="bigger-110 bolder center grey">
																		<i class="ace-icon fa fa-hand-o-right blue bigger-120"></i>
																		Are you sure?
																	</p>
																</div> -->

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
<a class="green" href="<?php echo site_url('admin/memberedit/'.$memberlist[$key]->_admin_id); ?>"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
</li>
<li>
<a class="red del-confirm" href="#" id="bx-confirm<?=$memberlist[$key]->_admin_id?>" data-value="<?=$memberlist[$key]->_admin_id?>" data-name="<?=$memberlist[$key]->_admin_name?>">
<i class="ace-icon fa fa-trash-o bigger-130"></i> 
</a>
</li>

																	</ul>
																</div>
															</div>						
					</td>
			</tr>
<?
							$i++;
						//}
				}
?>
					</tbody>
				</table>
			</form>
			</div>
							</div>
							<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
	</div><!-- /.row -->
<script type="text/javascript">
$( document ).ready(function() {
<?php
if($memberlist)
         foreach($memberlist as $key => $arr_field){
              $admin_id = $memberlist[$key]->_admin_id;
              $admin_name = $memberlist[$key]->_admin_name;
?>
		$('#status<?=$admin_id?>').on('click', function() {
				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);
				//alert($(this).attr('id'));
				//alert($(this).val());
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('admin/status'.$admin_id)?>",
					cache: false,
					success: function(data){
							if(data == 0){
								$("#msg_error").attr('style','display:block;');
								AlertError('<?php echo $language['inactive'] ?>');
							}else{
								$("#msg_success").attr('style','display:block;');
								AlertSuccess	('<?php echo $language['active'] ?>');
							}
					}
				});
		});

			 
		
		$('#bx-confirm<?=$admin_id?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?> <?php echo $admin_name;?>", function(result) {
						if(result) {
								window.location='<?php echo base_url('admin/deleteadmin/'.$admin_id)?>';
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
<script type="text/javascript">
$( document ).ready(function() {

			$('.ace-switch').on('click', function() {

					var id = $(this).attr('id');
					var n = id.length;
					var maxstr = n-6;
					var res = id.substr(6, maxstr);
				
					$.ajax({
							type: 'POST',
							url: "<?php echo base_url('admin/status')?>",
							data: {id: res},
							cache: false,
							success: function(data){
									//alert(data)
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

			$('#admin_type_id').on('change', function() {
					//var admin_type = $(this).val();
					//alert(admin_type);
					document.getElementById("adminform").submit();
			});

/*
				$('#gritter-regular').on(ace.click_event, function(){
					$.gritter.add({
						title: 'This is a regular notice!',
						text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" class="blue">magnis dis parturient</a> montes, nascetur ridiculus mus.',
						image: $path_assets+'/avatars/avatar1.png',
						sticky: false,
						time: '',
						class_name: (!$('#gritter-light').get(0).checked ? 'gritter-light' : '')
					});
			
					return false;
				});
			
				$('#gritter-sticky').on(ace.click_event, function(){
					var unique_id = $.gritter.add({
						title: 'This is a sticky notice!',
						text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" class="red">magnis dis parturient</a> montes, nascetur ridiculus mus.',
						image: $path_assets+'/avatars/avatar.png',
						sticky: true,
						time: '',
						class_name: 'gritter-info' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
					});
			
					return false;
				});
			
			
				$('#gritter-without-image').on(ace.click_event, function(){
					$.gritter.add({
						// (string | mandatory) the heading of the notification
						title: 'This is a notice without an image!',
						// (string | mandatory) the text inside the notification
						text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" class="orange">magnis dis parturient</a> montes, nascetur ridiculus mus.',
						class_name: 'gritter-success' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
					});
			
					return false;
				});
			
			
				$('#gritter-max3').on(ace.click_event, function(){
					$.gritter.add({
						title: 'This is a notice with a max of 3 on screen at one time!',
						text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" class="green">magnis dis parturient</a> montes, nascetur ridiculus mus.',
						image: $path_assets+'/avatars/avatar3.png',
						sticky: false,
						before_open: function(){
							if($('.gritter-item-wrapper').length >= 3)
							{
								return false;
							}
						},
						class_name: 'gritter-warning' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
					});
			
					return false;
				});
			
			
				$('#gritter-center').on(ace.click_event, function(){
					$.gritter.add({
						title: 'This is a centered notification',
						text: 'Just add a "gritter-center" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
						class_name: 'gritter-info gritter-center' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
					});
			
					return false;
				});
				
				$('#gritter-error').on(ace.click_event, function(){
					$.gritter.add({

						title: 'This is a warning notification',
						text: 'Just add a "gritter-light" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
						class_name: 'gritter-error' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
					});
			
					return false;
				});
					
			
				$("#gritter-remove").on(ace.click_event, function(){
					$.gritter.removeAll();
					return false;
				});
*/
			
		//$('#dataTables-member').dataTable(); 
<?php if($usedataTables == 1){ ?> 
		//$('#dataTables-article').dataTable();
		$('#dataTables-member').dataTable( {
			//"order": [[ 4, "desc" ]],
			stateSave: true
		} );
<?php } ?>
});

</script>



<?php echo js_asset('checkall.js'); ?>