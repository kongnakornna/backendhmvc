<?php 
	$user_typena=$this->session->userdata('user_type');
	$user_idnana=$this->session->userdata('user_id');
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
<hr/>
<!--	
<div class="col-xs-12">

<button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('modules_user/memberadd') ?>';">
	 <i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add_member'] ?>
</button> 
						
</div>
<hr/>	
-->	
<?php 
	$attributes = array('class' => 'form-horizontal', 'name' => 'userform',  'id' => 'userform');
	echo form_open_multipart('modules_user/index', $attributes);
	//user_type_id
?>						
							<!-- PAGE CONTENT BEGINS -->
							<div class="col-xs-12">
                                   
									 <br>
									<div class="table-header">
                                                  
										<?php echo $language['member_list'] ?>
										<?php echo $language['total'] ?>
										<?php echo ' '.count($memberlist).' '; ?>
										<?php echo $language['record'] ?>
										<!-- <h3 class="header smaller lighter blue"><?php echo $language['member_list'] ?></h3>
										<div class="table-header">
											<?php echo $language['member_list'] ?>
										</div> -->
                                              </div>
									
									</div>
                                            
							</div>
<right>                        
 <div class="col-xs-3 right"><?php echo $user_type_list ?></div>	
 		
</right>  
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
				
<?php ###############?>
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="clip-cog-2"></i><?php echo $language['member_list'] ?>
									<div class="panel-tools">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
								
									</div>
								</div>
<hr />
<?php ###############?>
<div class="table-responsive">
				<table width="100%"  class="table table-striped table-bordered table-hover" id="sample-table-2">
				
									<thead>
										<tr>
											<th><i class="ace-icon fa fa-mobile-phone bigger-110 hidden-480"></i> <?php echo $language['mobile'] ?></th>
											<th><?php echo $language['no'] ?></th>
                                            <th class="center">Picture</th>
											<th class="center"><i class="ace-icon fa fa-user bigger-110 hidden-480"></i><?php echo $language['fullname'] ?></th>
											<th class="center"><i class="ace-icon fa fa-envelope bigger-110 hidden-480"></i><?php echo $language['email'] ?></th>
											<th class="center hidden-480"><?php echo $language['user_level'] ?></th>
											<!--<th class="hidden-480"><i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i><?php echo $language['department'] ?></th> -->
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

			$user_id=$memberlist[$key]['user_id'];
            $user_id2=$memberlist[$key]['user_id2'];
            $user_type_id=$memberlist[$key]['user_type_id'];
            $user_team_id=$memberlist[$key]['user_team_id'];
            $user_username=$memberlist[$key]['user_username'];
            $user_password=$memberlist[$key]['user_password'];
            $avatar=$memberlist[$key]['user_avatar'];
            $user_name=$memberlist[$key]['user_name'];
            $user_lastname=$memberlist[$key]['user_lastname'];
            $user_email=$memberlist[$key]['user_email'];
            $phone=$memberlist[$key]['phone'];
            $mobile=$memberlist[$key]['mobile'];
            $department=$memberlist[$key]['department'];
            $address=$memberlist[$key]['address'];
            $create_date=$memberlist[$key]['create_date'];
            $create_by=$memberlist[$key]['create_by'];
            $lastupdate_date=$memberlist[$key]['lastupdate_date'];
            $lastupdate_by=$memberlist[$key]['lastupdate_by'];
            $lastlogin=$memberlist[$key]['lastlogin'];
            $status=$memberlist[$key]['status'];
            $lang=$memberlist[$key]['lang'];
            $user_type_title=$memberlist[$key]['user_type_title'];
			
			
			
							for($j=0;$j<count($getAdminType);$j++){

									$member_type = (isset($user_type_id)) ? $user_type_id : $user_type_id;
									if($member_type == $getAdminType[$j]->$user_type_id){
										$user_type = $getAdminType[$j]->$user_type_title;
										$user_type = '<span class="label label-warning arrowed-in arrowed-in-right">'.$user_type.'</span>';
									}											
							}

						 

							//.ace-nav .nav-user-photo
							$fullname=$user_name;
							if($avatar != ''){
								if(file_exists('./uploads/user/'.$avatar)){
									$avatar = base_url('uploads/user/'.$avatar);
									$img_user = '<img class="user-photo" src="'.$avatar.'" height="40" alt="'.$fullname.'\'s Photo">';
									}else{ 	$imguserna=base_url().'theme/system/systmon.png'; 
											$img_user = '<img class="user-photo" src="'.$imguserna.'" height="40" alt="'.$fullname.'\'s Photo">';
											} 
							}else{$imguserna=base_url().'theme/system/systmon.png';  //$img_user = '';
									$img_user = '<img class="user-photo" src="'.$imguserna.'" height="40" alt="'.$fullname.'\'s Photo">';
									}
							//////////////////////////////////////
							$fobidden=$this->lang->line('fobidden');
?>
			<tr>
						<td class="left">
						<span class="label label-info">  <i class="ace-icon fa fa-mobile bigger-110 hidden-480"></i> 
						<?php echo $mobile;?></span></td>
						<td class="center"><?php $i2=$i+1; echo $i2; ?> </td>
						<td class="center"><a role="menuitem" tabindex="-1" href="<?php echo site_url('user/memberedit/'.$user_id); ?>"><?=$img_user?></a></td>
						<td><?php #if($user_id==2){  echo "<b><font color='Red'> System </font></b>";}else{?>
						 <i class="ace-icon fa fa-user  bigger-110 hidden-480"></i>
						<a role="menuitem" tabindex="-1" href="<?php echo site_url('user/memberedit/'.$user_id2); ?>">
						<?=$user_name?> <?=$user_lastname?>
						</a> 
						<?php # }?>
						</td>
						<td><i class="ace-icon fa fa-envelope-o bigger-110 hidden-480"></i> <?=$user_email?></td>
						<td class="center hidden-480"> <?=$user_type_title?> </td>
						
						<!--
						<td class="hidden-480">
						<span class="label label-success">  
						<?php  //$department; if($department<>''){echo $department;}else if($department==''){echo '--------------';}?></span>
						</td>
						-->
						
						<td class="hidden-480">
								 
								<label>
 		 
<input name="status[]" id="status<?=$user_id2?>" class="ace ace-switch ace-switch-5 btn-empty" type="checkbox" <?php if($status == 1) echo 'checked';?>  value=1>  
 									
										<span class="lbl"></span>
								</label>
						</td>
						<td> 
						
						
						
				 
												<div>
													<div class="btn-group">
													<!--
														<a class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
															<i class="fa fa-cog"></i> <span class="caret"></span>
														</a>
														
														
													<ul role="menu" class="dropdown-menu pull-right">
													        
															<li role="presentation">
																<a role="menuitem" tabindex="-1" href="<?php echo site_url('user/memberedit/'.$user_id); ?>">
																	<i class="fa fa-edit"></i> <?php $edit=$language['edit']; echo "<b><font color='blue'> $edit </font></b>";?>
																</a>
															</li>
															 
															<li role="presentation">
															 
											 <a role="menuitem" tabindex="-1" href="#"id="bx-confirm<?=$user_id?>" class="tooltip-error" data-rel="tooltip" title="Delete">
														<i class="fa fa-times"></i><?php $delete=$language['delete'];echo "<b><font color='red'> $delete </font></b>";?>
																</a> 
															</li>
												 
														</ul>
														-->
														กำลังจัดทำ
													</div>
												</div>	
												 
													 		
					</td>
			</tr>
<?php
							$i++;
						//}
				}
?>
					</tbody>
				</table>
			</form>
			</div>
							 
					 <!-- PAGE CONTENT ENDS -->
			 <!-- /.col -->
	 <!-- /.row -->
<script type="text/javascript">
$( document ).ready(function() {
<?php
if($memberlist)
         foreach($memberlist as $key => $arr_field){
              $user_id = $memberlist[$key]['user_id2'];
              $user_name = $memberlist[$key]['user_name'];
?>
$('#status<?=$user_id?>').on('click', function() {
				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);
				 //alert('Save Complate');
				 //alert('<?php echo $language['savecomplate'] ?>');
				 //alert($(this).attr('id'));			
				 //alert($(this).val());

				$.ajax({
					type: 'POST',
					url:  '<?php echo base_url('modules_user/status2/'.$user_id)?>',
					cache: false,
					success: function(data){
							if(data == 0){
                                ////////
                            	swal({
                            		title: "<?php echo $language['inactive'] ?>!",
                            		text: "<?php echo $language['savecomplate'] ?>",
                            		timer: 2000,
                            		showConfirmButton: false
                            	});
                                //////
							}else{
                                ////////
                            	swal({
                            		title: "<?php echo $language['active'] ?>!",
                                    text: "<?php echo $language['savecomplate'] ?>", 
                            		timer: 2000,
                            		showConfirmButton: false
                            	});
                                //////
							}
					}
				});
		});
			 
		
		$('#bx-confirm<?=$user_id?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?> <?php echo $user_name;?>", function(result) {
						if(result) {
								window.location='<?php echo base_url('modules_user/deleteuser/'.$user_id)?>';
						}
					});
		});

	

<?php
				}	
?>
 
			$('#user_type_id').on('change', function() {
					//var user_type = $(this).val();
					//alert(user_type);
					document.getElementById("userform").submit();
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