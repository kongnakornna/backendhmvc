<?php 
	$language = $this->lang->language; 
	$i=0;
	$usedataTables = 1;
	$allnews = count($zodiac_list);

	if(!isset($category_id)) $category_id = 0;
	if(!isset($subcategory_id)) $subcategory_id = 0;

	//$category_id = (isset($this->input->post('category_id'))) ? $this->input->post('category_id') : 0;
	//$subcategory_id = (isset($this->input->post('subcategory_id'))) ? $this->input->post('subcategory_id') : 0;

	if(!isset($listspage)) $listspage = 10;
	//Debug($category_id);
	//Debug($subcategory_id);
	//Debug($zodiac_list);
	//Debug($zodiac_list);

	//echo css_asset('font-awesome2.css');
	//$iconpin = '<i class="icon-pushpin red"> %d </i>';
	if($this->input->get('success')) $success = $this->input->get('success');
?>
<style type="text/css">
.col-sm-6 {width: 100%;text-align: center;	}
#saveorder{cursor:pointer;}
.input-small{text-align: center;}
.hand{cursor:pointer;}
.sethighlight, .setmegamenu{color:#cccccc;}
#nav-search{display:none;}
.gray{color: #B3B3B3;}
</style>

<div class="row">
	<div class="col-xs-12">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'filterjform');
	echo form_open('zodiac', $attributes);
?>
		<div class="col-xs-12 col-lg-3">
				<span>
						<button type="button" class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('zodiac/add') ?>';">
								<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['zodiac']  ?>
						</button> <?php echo $allnews." of ".number_format($totalnews)." record." ?>
				</span>
		</div>

		<div class="col-xs-12 col-lg-3">
				<!-- <span>
						<div class="col-xs-6">
								<?php //echo $category_list?>
						</div>
						<div class="col-xs-6">
								<select class="form-control" id="subcategory_id" name="subcategory_id"></select>
						</div>				
				</span> -->
		</div>

		<div class="col-xs-12 col-lg-3">
				<span>
							<div class="col-xs-6">
									<!-- <select class="form-control" name="sp">
											<option><?php echo $language['all']?></option>
											<option value="1" <?php if($this->input->post('sp') == 1) echo 'selected="selected"'?> ><?php echo $language['highlight']?></option>
											<option value="2" <?php if($this->input->post('sp') == 2) echo 'selected="selected"'?> >Mega menu</option>
									</select>-->
							</div>

							<div class="col-xs-6">
									<!-- <label><?php echo $language['status'] ?></label> -->
									<select class="form-control" name="status">
											<option><?php echo $language['all']?></option>
											<option value="1" <?php if($this->input->post('status') == 1) echo 'selected="selected"'?> ><?php echo $language['publish']?></option>
											<option value="3" <?php if($this->input->post('status') == 3) echo 'selected="selected"'?> ><?php echo $language['unpublish']?></option>
									</select>	
							</div>				
				</span>
		</div>

		<div class="col-xs-12 col-lg-3">
				<span>


							<div class="col-xs-6">
									<div class="input-group">
											<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy" name="zodiac_date" value="<?php 
													if($this->input->server('REQUEST_METHOD') === 'POST'){
														if($this->input->post('zodiac_date') != '') echo $this->input->post('zodiac_date');
													} ?>" />
											<span class="input-group-addon">
													<i class="fa fa-calendar bigger-110"></i>
											</span>
									</div>
							</div>

							<div class="col-xs-6">
								<button class="btn btn-sm btn-primary" type="submit">
										<i class="ace-icon fa fa-glass align-top bigger-125"></i>Filter
								</button>				
							</div>

				</span>
		</div>
<?php
	echo form_close();
?>
</div>

<?php
			if(isset($error)){
?>
				<div class="col-xs-12">
						<div class="alert alert-danger">
								<button data-dismiss="alert" class="close" type="button">
									<i class="ace-icon fa fa-times"></i>
								</button>
								<strong>
									<i class="ace-icon fa fa-times"></i> </strong><?php echo $error ?>.
								<br>
						</div>
				</div>
<?php
			}

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
		//Debug($zodiac_list);
		//Debug($zodiac_view);
?>

<div class="col-xs-12">
	<div class="table-responsive">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open('zodiac', $attributes);
?>									
		<input type="hidden" name="category" value="<?php echo $category_id ?>">
				<table id="dataTables-zodiac" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>

									<th>No.</th>

									<th width="100">Pic</th>
									<th width="20%"><?php echo $language['title'] ?></th>
									<th class="hidden-480">ID</th>

									<th width="10%" class="hidden-480">
											<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
											<?php echo $language['create_date'] ?>
									</th>
									<th><?php echo $language['status'] ?></th>
									<th><?php echo $language['approve'] ?></th>
									<th width="15%"><?php echo $language['action'] ?></th>
							</tr>
						</thead>
						<tbody>												
<?php
			$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
			$thumb = '';
			$list_page = 10;
			$number = 0;

			$datainput = $this->input->get();
			if(isset($datainput['p'])){
				$p = ($datainput['p'] > 0) ? $datainput['p'] : 1;
				if(isset($datainput['list_page'])) $list_page = ($datainput['list_page'] > 0) ? $datainput['list_page'] : 10;
				if($p > 1){
						$number = ($p-1)*$list_page;
				}
			}else{
				$number = 0;
				$p = 1;
			}

			$allzodiac = count($zodiac_list);
			if($zodiac_list)
					for($i=0;$i<$allzodiac;$i++){
								$number++;
								
								//$pic = ($zodiac_list[$i]['avatar'] == '') ? 'theme/assets/avatars/avatar3.png' : 'uploads/zodiac/'.$zodiac_list[$i]['avatar'];
								//$thumb = $pic;
								$zodiac_id = $zodiac_list[$i]['zid'];
								$zid2 = $zodiac_list[$i]['zid2'];

								//$category_id = $zodiac_list[$i]['category_id'];
								//$subcategory_id = $zodiac_list[$i]['subcategory_id'];
								//$category_iddb = $zodiac_list[$i]['category_id'];
								//$subcategory_iddb = $zodiac_list[$i]['subcategory_id'];

								$category_name = $zodiac_list[$i]['category_name'];
								$subcategory_name = $zodiac_list[$i]['subcategory_name'];

								if($subcategory_name != '') $category_name = $category_name.'==>'.$subcategory_name;

								//$columnist_name = $zodiac_list[$i]['columnist_name'];
								//$full_name = $zodiac_list[$i]['full_name'];

								$title = $zodiac_list[$i]['title'];
								$description = $zodiac_list[$i]['description'];
								$order_by = $zodiac_list[$i]['order_by'];
								$create_date = $zodiac_list[$i]['create_date'];
								//$create_date = RenDateTime($create_date);
								$create_by_name = $zodiac_list[$i]['create_by_name'];
								$countview = $zodiac_list[$i]['countview'];

								$can_comment = $zodiac_list[$i]['can_comment'];

								$status = $zodiac_list[$i]['status'];
								$approve = $zodiac_list[$i]['approve'];
								$order_by = $zodiac_list[$i]['order_by'];

								//$news_highlight_id = $zodiac_list[$i]['news_highlight_id'];
								//$megamenu_id = $zodiac_list[$i]['megamenu_id'];

								if($zodiac_list[$i]['zodiac_id'] > 0){
											for($z=0;$z<count($zodiac_view);$z++){

														if($zodiac_view[$z]['zid'] == $zodiac_list[$i]['zodiac_id']){

																$title .= " : ".$zodiac_view[$z]['zodiac_name']."<br>[".DateTH($zodiac_list[$i]['zodiac_date'])."]";
																$zodiac_icon = "uploads/zodiac/".$zodiac_view[$z]['icon'];

														}
												
											}
								}

								/*if($zodiac_list[$i]['file_name'] != ''){
										if(file_exists('uploads/thumb/'.$zodiac_list[$i]['folder'].'/'.$zodiac_list[$i]['file_name'])){
												$thumb_img = base_url('uploads/thumb').'/'.$zodiac_list[$i]['folder'].'/'.$zodiac_list[$i]['file_name'];
												$show_img = '<img src="'.$thumb_img.'" width="100" height="57">';
										}else $show_img  = '';
								}else
										$show_img  = '';*/

						$show_img = '<img src="'.base_url($zodiac_icon).'" height="57">';										


						/*$highlight = ($news_highlight_id > 0) ? '<i class="ace-icon fa fa-asterisk hand rmhighlight green" data-value="'.$zid2.'" id="hl'.$zid2.'" data-name="'.$title.'" data-rel="tooltip" title="'.$language['highlight'].'"></i>' : '<i class="ace-icon fa fa-asterisk hand sethighlight" data-value="'.$zid2.'" id="hl'.$zid2.'" data-name="'.$title.'" data-rel="tooltip" title="Set '.$language['highlight'].'"></i>';

						$megamenu = ($megamenu_id > 0) ? '<i class="ace-icon glyphicon glyphicon-picture hand rmmegamenu green" data-value="'.$zid2.'" data-name="'.$title.'" data-cat="'.$category_name.'"  data-catid="'.$category_iddb.'" id="mg'.$zid2.'" data-rel="tooltip" title="Mega menu"></i>' : '<i class="ace-icon glyphicon glyphicon-picture hand setmegamenu" data-value="'.$zid2.'" data-name="'.$title.'" data-cat="'.$category_name.'"  data-catid="'.$category_iddb.'" id="mg'.$zid2.'" data-rel="tooltip" title="Set Mega menu"></i>';*/

								$edit_zodiac = site_url('zodiac/edit/'.$zid2);
								$pic_gallery = site_url('zodiac/picture/'.$zid2);
								
								//$previewurl = $this->config->config['www']."/zodiac/".$category_iddb."/".$subcategory_iddb."/".$zid2."/?preview=1";
								$previewurl = '';
?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" name="selectid[]"  />
										<span class="lbl"></span>
								</label>
						</td> -->
						<td><?=$order_by?></td>

						<!-- <td class="hidden-480">								
								<input class="input-small" name="order[]" type="text" id="order_by" placeholder="Not null" value="<?=$order_by?>">
						</td>-->					

						<td class="center"><a href="<?php echo $edit_zodiac ?>"><?=$show_img ?></a></td>
						<td>
							<a href="<?php echo $edit_zodiac ?>"><?=$title?></a>
							<br>View : <?php echo $countview?>
							<br><?php echo $language['create_by'] ?> : <?php echo $create_by_name?>
						</td>
						<td class="hidden-480"><?=$zid2?><input type="hidden" class="ace" name="selectid[]" value="<?php echo $zid2?>"/></td>

						<td class="hidden-480"><?php echo $create_date ?></td>
						<td>
								<span class="col-sm-12">
									<label class="pull-right inline" id="enable<?php echo $zid2?>">
									<small class="muted"></small>
										<input type="checkbox" name="status"  id="status<?=$zid2?>" class="ace ace-switch ace-switch-6 status-buttons" data-value="<?=$zid2?>"
										<?php if($status  == 1) echo 'checked';?>  value=1>
									<span class="lbl middle"></span>
									</label>
								</span>
						</td>

						<td>
								<span class="col-sm-12">
									<label class="pull-right inline" id="adminapprove<?php echo $zid2?>">
									<small class="muted"></small>
										<input type="checkbox" name="approve"  id="approve<?=$zid2?>" class="ace ace-switch ace-switch-4 approve-buttons" data-value="<?=$zid2?>"
										<?php if($approve  == 1) echo 'checked';?>  value=1 <?php if($this->session->userdata('admin_type') > 4) echo 'disabled' ?>>
									<span class="lbl middle"></span>
									</label>
								</span>
						</td>

						<td>

								<div class="hidden-sm hidden-xs action-buttons">

										<a href="javascript:void(0);" class="blue" ><i class="ace-icon fa fa-search-plus bigger-130 bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>" data-rel="tooltip" title="<?php echo $language['preview'] ?>"></i></a>

										<!-- <a href="<?php echo $pic_gallery ?>" data-rel="tooltip" title="Picture">
												<i class="menu-icon fa fa-picture-o tooltip-info" data-rel="tooltip" title="<?php echo $language['add_edit_picture'] ?>"></i>
										</a> -->
										<a class="green" href="<?php echo $edit_zodiac ?>" data-rel="tooltip" title="Edit">
												<i class="ace-icon fa fa-pencil bigger-130"></i>
										</a>
										<a class="red del-confirm" href="#" id="bootbox-confirm<?=$zid2?>" data-catid="<?php //echo $category_iddb?>" data-value="<?php echo $zid2?>" data-name="<?=$title?>" data-rel="tooltip" title="Delete">
												<i class="ace-icon fa fa-trash-o bigger-130"></i>
										</a>
								</div>

								<div class="hidden-md hidden-lg">
										<div class="inline position-relative">
												<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
													<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
												</button>

												<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
													<li>
															<a href="javascript:void(0);<?php //echo $previewurl ?>" class="tooltip-info bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>" data-rel="tooltip" title="<?php echo $language['preview'] ?>">
																	<span class="blue">
																			<i class="ace-icon fa fa-search-plus bigger-120"></i>
																	</span>
															</a>
													</li>
													<!-- <li>
															<a href="<?php echo $pic_gallery ?>" class="tooltip-success" data-rel="tooltip" title="<?php echo $language['add_edit_picture'] ?>">
																	<span class="green">
																			<i class="ace-icon fa fa-picture-o bigger-120"></i>
																	</span>
															</a>
													</li> -->

													<li>
															<a href="<?php echo $edit_zodiac ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																	<span class="green">
																			<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																	</span>
															</a>
													</li>

													<li>
															<a href="#" id="bx-confirm<?=$zid2?>" data-catid="<?php //echo $category_iddb?>" data-value="<?php echo $zid2?>" class="tooltip-error del-confirm" data-rel="tooltip" title="Delete">
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
	if($usedataTables == 0) echo $GenPage
?>								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->

<?php 
	echo form_close();
?>

		<div class="center">
				<span class="action-buttons gray"> <?php echo $language['iconmain'] ?></span>
				<span class="action-buttons gray">
						<i class="ace-icon fa fa-search-plus"> <?php echo $language['preview'] ?></i> |
						<i class="ace-icon fa fa-picture-o"> <?php echo $language['picture'] ?></i> |
						<i class="ace-icon fa fa-pencil"> <?php echo $language['edit'] ?></i> |
						<i class="ace-icon fa fa-trash-o"> <?php echo $language['delete'] ?></i>
				</span>
		</div>

</div>

<script type="text/javascript">
$( document ).ready(function() {

		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		})

		$('#category_id').change(function( ) {
				var catid = $(this).val();
				//alert('<?php echo base_url() ?>subcategory/list_dd/' + catid);
				$('#subcategory_id').load('<?php echo base_url() ?>subcategory/list_dd/' + catid);
		});

<?php
		if($category_id > 0){
?>
			//alert('<?php echo $subcategory_id?>');
			$('#subcategory_id').load('<?php echo base_url() ?>subcategory/list_dd/' + <?php echo $category_id?> + '/' + <?php echo $subcategory_id?>);
<?php
		}
?>

		$('.status-buttons').on('click', function() {
				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);
				var zodiacid = $(this).attr('data-value');
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('zodiac/status')?>/" + zodiacid,
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

		$('.approve-buttons').on('click', function() {

				var id = $(this).attr('id');
				var n = id.length;
				var maxstr = n-6;
				var res = id.substr(6, maxstr);
				var zodiacid = $(this).attr('data-value');
				//alert('approve-buttons ' + zodiacid);
<?php
				if($this->session->userdata('admin_type') <= 4){	
?>
						$.ajax({
							type: 'POST',
							url: "<?php echo base_url('zodiac/approve')?>/" + zodiacid,
							cache: false,
							success: function(data){
									//alert(data);
									if(data == 0){
										//$("#msg_my_alert ").attr('style','background: none repeat scroll 0 0 #cc0000;');
										//AlertMsg('Inactive');
										$("#msg_error").attr('style','display:block;');
										AlertError('Not approve');
									}else{
										//$("#msg_my_alert ").attr('style','background: none repeat scroll 0 0 #438eb9;');
										//AlertMsg('Active');
										$("#msg_success").attr('style','display:block;');
										AlertSuccess	('Approve');
									}
							}
						});
<?php
				}else{
?>
					 AlertError('Can not Approve');
<?php
				}
?>			
		});

		$('.del-confirm').on('click', function() {
				var id = $(this).attr('data-value');
				var catid = $(this).attr('data-catid');
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
							//alert('<?php echo base_url('zodiac/delete')?>/' + id + '?cat=' + catid);
							window.location='<?php echo base_url('zodiac/delete')?>/' + id + '?cat=' + catid ;
						}
				});
		});

		$('.sethighlight').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				bootbox.confirm("<h4 class='row header smaller lighter purple'>Set Highlight </h4>" + title , function(result) {
					if(result) {
							//alert('<?php echo base_url('news/sethighlight')?>/' + id + '?title=' + encodeURIComponent(title));
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('zodiac/sethighlight')?>/" + id,
										data: {title: encodeURIComponent(title), op : 'set'},
										cache: false,
										success: function(data){
												//alert(data);
												if(data == 1){
													$('#hl' + id).attr('style', 'color:green');
													AlertSuccess	('Set Highlight ' + title);
												}else{
													AlertError('Not Set');
												}
										}
								});
						}
				});
		});

		$('.setmegamenu').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				var cat = $(this).attr('data-cat');
				var catid = $(this).attr('data-catid');
				bootbox.confirm("<h4 class='row header smaller lighter orange'>Set Megamenu </h4>" + title , function(result) {
					if(result) {
							//alert('<?php echo base_url('zodiac/setmegamenu')?>/' + id + '?title=' + encodeURIComponent(title));
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('zodiac/setmegamenu')?>/" + id,
										data: {title: encodeURIComponent(title), cat : cat, catid : catid, op : 'set'},
										cache: false,
										success: function(data){
												//alert(data);
												if(data == 1){
													$('#mg' + id).attr('style', 'color:green');
													AlertSuccess	('Set Megamenu ' + title);
												}else{
													AlertError('Not Set');
												}
										}
								});
						}
				});
		});

		$('.rmhighlight').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				bootbox.confirm("<h4 class='row header smaller lighter red'>Remove Highlight </h4>" + title , function(result) {
					if(result) {
							//alert('<?php echo base_url('zodiac/sethighlight')?>/' + id + '?title=' + encodeURIComponent(title));
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('zodiac/sethighlight')?>/" + id,
										data: {title: encodeURIComponent(title), op : 'remove'},
										cache: false,
										success: function(data){
												//alert(data);
												if(data == 0){
													$('#hl' + id).attr('class', 'ace-icon fa fa-asterisk hand');
													AlertSuccess	('Remove Highlight ' + title);
												}else{
													AlertError('Not Set');
												}
										}
								});
						}
				});
		});

		$('.rmmegamenu').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				var cat = $(this).attr('data-cat');
				var catid = $(this).attr('data-catid');
				bootbox.confirm("<h4 class='row header smaller lighter red'>Remove Megamenu </h4>" + title , function(result) {
					if(result) {
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('zodiac/setmegamenu')?>/" + id,
										data: {title: encodeURIComponent(title), cat : cat, catid : catid, op : 'remove'},
										cache: false,
										success: function(data){
												//alert(data);
												if(data == 0){
													$('#mg' + id).attr('class', 'ace-icon glyphicon glyphicon-picture hand');
													AlertSuccess	('Remove Megamenu ' + title);
												}else{
													AlertError('Not Set');
												}
										}
								});
						}
				});
		});

		$('#saveorder').on('click', function() {
				document.getElementById("jform").submit();
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

		<?php if($usedataTables == 1){ ?>
			//$('#dataTables-zodiac').dataTable(); 
			$('#dataTables-zodiac').dataTable( {
				"order": [[ 4, "desc" ]],
				stateSave: true
			} );
		<?php } ?>
});
</script>