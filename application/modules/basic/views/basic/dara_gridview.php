<?php 
		$language = $this->lang->language; 
		$i=0;
		//$maxcat = count($dara_type);
		//Debug($search_form);
		$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
		echo form_open('dara', $attributes);

		if($this->input->get('success')) $success = $this->input->get('success');

		if(!isset($search_form['sortby'])) $search_form['sortby'] = 'lastupdate_date';

		$admin_type = $this->session->userdata('admin_type');
		$admin_id = $this->session->userdata('admin_id');
		$access_level = $this->config->config['level'];
		//Debug($dara_list);
?>
<style type="text/css">
	.ace-thumbnails > li {height: 285px;}
</style>
<div class="col-xs-12">
		<div class="col-xs-1">
				<a href="<?php echo site_url('dara/add') ?>"><button class="btn btn-sm btn-primary" type="button">
						<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['dara']  ?>
				</button></a> <br>
				<?=$dara_all?> record
		</div>
		<div class="col-xs-1">
		</div>
		<div class="col-xs-2">
			<label><?php echo $language['sortby'] ?></label>
			<select class="form-control" id="sortby" name="sortby">
					<option value="create_date" <?php echo ($search_form['sortby'] == "create_date") ? 'selected' : '' ?>><?php echo $language['create_date']?></option>
					<option value="lastupdate_date" <?php echo ($search_form['sortby'] == "lastupdate_date") ? 'selected' : '' ?>><?php echo $language['lastupdate']?></option>
					<option value="dara_profile_id_map" <?php echo ($search_form['sortby'] == "id") ? 'selected' : '' ?>>ID</option>
					<option value="first_name" <?php echo ($search_form['sortby'] == "first_name") ? 'selected' : '' ?>><?php echo $language['name']?></option>
					<option value="nick_name" <?php echo ($search_form['sortby'] == "nick_name") ? 'selected' : '' ?>><?php echo $language['nickname']?></option>
			</select>		
		</div>
		<div class="col-xs-2">
			<label><?php echo $language['type'] ?></label>
			<select class="form-control" id="form-field-select-1" name="dara_type">
					<option value="0">-</option>
<?php 
				$alltype = count($dara_type);
				if($dara_type)
						for($i = 0; $i < $alltype; $i++){
									$selected = ($search_form['dara_type'] == $dara_type[$i]['dara_type_id_map']) ? 'selected' : '';
									echo '<option value="'.$dara_type[$i]['dara_type_id_map'].'" '.$selected.'>'.$dara_type[$i]['dara_type_name'].'</option>';
						}
?>
			</select>
		</div>
		<div class="col-xs-2">
			<label><?php echo $language['gender'] ?></label>
			<select class="form-control" id="form-field-select-1" name="gender">
					<option <? //if(!isset($this->input->post()) echo 'selected'; ?>><?php echo $language['all']?></option>
					<option value="m" <?php echo ($this->input->post('gender') =='m') ? 'selected' : '' ?>><?php echo $language['male']?></option>
					<option value="f" <?php echo ($this->input->post('gender') =='f') ? 'selected' : '' ?>><?php echo $language['female']?></option>
			</select>
		</div>
		<div class="col-xs-2">
			<label><?php echo $language['status'] ?></label>
			<select class="form-control" name="dara-status">
					<option><?php echo $language['all']?></option>
					<option value="1" <?php if($this->input->post('dara-status') == 1) echo 'selected="selected"'?> ><?php echo $language['status']?></option>
					<option value="3" <?php if($this->input->post('dara-status') == 3) echo 'selected="selected"'?> ><?php echo $language['unpublish']?></option>
			</select>	
		</div>
		<div class="col-xs-1">
			<button class="btn btn-sm btn-primary" type="submit">
					<i class="ace-icon fa fa-glass align-top bigger-125"></i>Filter
			</button>
		</div>
		<div class="col-xs-1">
			&nbsp;<a href="<?php echo base_url('dara/listview'); ?>"><i class="ace-icon glyphicon glyphicon-list icon-only bigger-150" title="List View"></i></a>
			&nbsp;<a href="#<?php echo base_url('dara/gridview'); ?>"><i class="ace-icon glyphicon glyphicon-th icon-only bigger-150" title="Grid View"></i></a>
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
											<i class="ace-icon fa fa-times"></i>
											</strong><?php echo $success?>.
									<br>
							</div>
				</div>
<?php
			}
?>
<div class="col-xs-12">
								<div>
									<ul class="ace-thumbnails clearfix">
<?php
			$alldara = count($dara_list);
			if($dara_list)
					for($i=0;$i<$alldara;$i++){
								
								if(isset($dara_list[$i]['avatar'])){
									//echo $dara_list[$i]['avatar'];

									if($dara_list[$i]['avatar'] != "" && $dara_list[$i]['avatar'] != "-"){

											$avatar = 'uploads/dara/'.$dara_list[$i]['avatar'];
											$thumb1 = 'uploads/thumb/dara/'.$dara_list[$i]['avatar'];
											$thumb3 = 'uploads/thumb3/dara/'.$dara_list[$i]['avatar'];

											//$img_size = ' width="80" height="80" alt="80x80"';
											//$avatar = (!file_exists($thumb3)) ? $thumb1 : $thumb3;
											//if (!file_exists($thumb3)){ $img_size = ' width="170" '; }

											if (!file_exists($thumb1)){
												 $img_size = ' width="250" height="170" alt="250x170"';
											}else{
												 $avatar = $thumb1;
												 $img_size = ' width="170"';
											}

											$fulimg = 'uploads/dara/'.$dara_list[$i]['avatar'];

									}else{
											$fulimg = $avatar = 'theme/assets/avatars/avatar3.png';
											$thumb1 = $thumb3 = "";
											unset($img_size);
									}

								}else{
									$fulimg = $avatar = 'theme/assets/avatars/avatar3.png';
									$thumb1 = $thumb3 = "";
									unset($img_size);
								}
								$thumb = $avatar;

								$dara_profile_id = $dara_list[$i]['dara_profile_id'];
								//$full_name = $dara_list[$i]['first_name'];
								$full_name = trim($dara_list[$i]['first_name'].' '.$dara_list[$i]['last_name']);
								$nickname = $dara_list[$i]['nick_name'];
								$dara_type_id = $dara_list[$i]['dara_type_id'];
								$dara_type_name = $dara_list[$i]['dara_type_name'];

								$belong_to = $dara_list[$i]['belong_to'];
								$dara_status = $dara_list[$i]['status'];
								$dara_approve = $dara_list[$i]['approve'];

								//$status = ($dara_list[$i]['status'] == 1) ? $language['status'] : $language['unpublish'];

								if($dara_list[$i]['status'] == 1){
										$status = $language['publish'];
										$status_class = 'label label-success arrowed-in';
								}else{
										$status = $language['unpublish'];
										$status_class = 'label label-danger arrowed-in';
								}
								$lastupdate_date = RenDateTime($dara_list[$i]['lastupdate_date']);

								/*if($admin_type <= $access_level){
										$lnk_dara = base_url('dara/edit/'.$dara_profile_id);
								}else
										$lnk_dara = "javascript:alert('".$language['please_contact_admin'].".');";*/

								$url_preview = $this->config->config['www'].'/dara/'.$dara_type_id.'/'.$dara_profile_id.'/'.RewriteTitle($dara_type_name).'/'.RewriteTitle($full_name).'/?preview=1';

								if($dara_status == 2){
										$lnk_dara = "javascript:alert('this is deleted.');";
								}elseif($admin_type <= $access_level){
										$lnk_dara = base_url('dara/edit/'.$dara_profile_id);
								}else
										$lnk_dara = "javascript:alert('".$language['please_contact_admin'].".');";

?>
										<li>
											<a href="<?php echo base_url().$fulimg?>" data-rel="colorbox">
												<img <?php if(isset($img_size)) echo $img_size?> src="<?php echo base_url($thumb)?>" />
											</a>
												<div class="tags">
													<span class="label-holder"><?php echo $nickname?> <?php echo $full_name?></span>
													<span class="label-holder"><?php echo $language['type'].':'.$dara_type_name?></span>

													<!-- <span class="label-holder" style="max-width:170px;overflow: hidden;">
														<span class="label"><?php echo $nickname?> <?php echo $full_name?></span>
													</span>
					
													<span class="label-holder">
														<span class="label"><?php echo $language['lastupdate'].' '.$lastupdate_date?></span>
													</span>
													<span class="label-holder">
														<span class="label"><?php echo $dara_type_name?></span>
													</span> -->
													<span class="label-holder">
														<span class="<?php echo $status_class ?>"><?php echo $status?></span>
													</span>
													<span class="label-holder"><?php echo $language['lastupdate'].':'.$lastupdate_date?></span>
												</div>

											<div class="tools tools-top in">

												<a href="javascript:void(0);" class="blue" >
												<i class="ace-icon fa fa-search-plus bootbox-options" data-value="<?=$url_preview?>" data-name="<?=$full_name?>" data-rel="tooltip" title="<?php echo $language['preview'] ?>"></i>
												</a>

												<!-- <a href="#">
													<i class="ace-icon fa fa-paperclip"></i>
												</a> -->

												<a href="<?php echo $lnk_dara ?>" data-rel="tooltip" title="<?php echo $language['edit'] ?>">
													<i class="ace-icon fa fa-pencil"></i>
												</a>

												<a href="javascript:void(0);" id="bootbox-confirm<?=$dara_profile_id?>" class="del-confirm" data-value="<?php echo $dara_profile_id ?>" data-name="<?php echo $nickname." ".$full_name ?>" data-rel="tooltip" title="<?php echo $language['delete'] ?>">
													<i class="ace-icon fa fa-times red"></i>
												</a>
											</div>
										</li>
<?php
					
					}
?>
				</ul>
			</div><!-- PAGE CONTENT ENDS -->
<?php
	echo form_close();
?>
	</div><!-- /.col -->
</div>

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
		//$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange'></i>");//let's add a custom loading icon

		$('.del-confirm').on('click', function() {				
				var id = $(this).attr('data-value');
				var name = $(this).attr('data-name');

				bootbox.confirm("<?php echo $language['are you sure to delete']?> " + name, function(result) {
					if(result) {
<?php
		if($admin_type <= 3){
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

})
</script>