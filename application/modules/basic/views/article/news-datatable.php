<?php 
	$language = $this->lang->language; 
	$i=0;
	$usedataTables = 1;
	$allnews = count($news_list);

	if(!isset($category_id)) $category_id = 0;
	if(!isset($listspage)) $listspage = 10;

	echo css_asset('font-awesome2.css');
	$iconpin = '<i class="icon-pushpin red"> %d </i>';

	//Debug($news_list);
	//die();
	//Debug($category_id);
	//Debug($subcategory_id);
	//$datainput = $this->input->post();
	//Debug($datainput);
?>
<style type="text/css">
.col-sm-6 {width: 100%;text-align: center;	}
#saveorder{cursor:pointer;}
.input-small{text-align: center;}
.hand{cursor:pointer;}
.sethighlight, .setmegamenu{color:#cccccc;}
</style>

<div class="row">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'filterjform');
	echo form_open('news', $attributes);
?>
		<div class="col-xs-12 col-lg-3">
				<span>
						<button type="button" class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('news/add') ?>';">
							<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['news']  ?>
						</button> <?php echo $allnews." of ".number_format($totalnews)." record." ?>						
				</span>
		</div>

		<div class="col-xs-12 col-lg-3">
				<span>
								<div class="col-xs-6">
									<?php echo $category_list?>
								</div>
								<div class="col-xs-6">
									<select class="form-control" id="subcategory_id" name="subcategory_id"></select>
								</div>
				</span>
		</div>

		<div class="col-xs-12 col-lg-3">
				<span>
								<div class="col-xs-6">
									<select class="form-control" name="sp">
											<option><?php echo $language['all']?></option>
											<option value="1" <?php if($this->input->post('sp') == 1) echo 'selected="selected"'?> ><?php echo $language['highlight']?></option>
											<option value="2" <?php if($this->input->post('sp') == 2) echo 'selected="selected"'?> >Mega menu</option>
									</select>	
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
								<button class="btn btn-sm btn-primary" type="submit">
										<i class="ace-icon fa fa-glass align-top bigger-125"></i>Filter
								</button>
				</span>
		</div>
<?php
	echo form_close();
?>
</div>

<div class="row">
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
											<i class="ace-icon glyphicon glyphicon-ok"></i>
											</strong><?php echo $success?>.
									<br>
							</div>
				</div>
<?php
			}
?>
<div class="col-xs-12">
		<div class="table-responsive">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open('news', $attributes);
?>
		<input type="hidden" name="category" value="<?php echo $category_id ?>">
											<table id="dataTables-news" class="table-responsive table table-striped table-bordered table-hover ">
												<thead>
													<tr>
														<!-- <th class="center">
															<label class="position-relative">
																<input type="checkbox" class="ace" id="checkall" />
																<span class="lbl"></span>
															</label>
														</th> -->
														<th>No.</th>
														<th class="hidden-480">
															<?php echo @($language['order_by']) ? $language['order_by'] : 'Order by' ?>
															<?php if($category_id > 0 && $subcategory_id == 0){ ?>
																&nbsp;<?php if($category_id > 0){ ?><i class="ace-icon fa fa-floppy-o bigger-120 blue" id="saveorder" data-rel="tooltip" title="Save Order"></i><?php } ?>
															<?php } ?>
														</th>
														<th></th>
														<th width="25%"><?php echo $language['title'] ?></th>
														<th class="hidden-480">ID</th>
														<th class="hidden-480">Category</th>
														<!-- <th class="hidden-480">SubCat</th> -->
														<th width="5%">HL & MG</th>
														<th class="hidden-480">
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

			if($news_list)
					for($i=0;$i<$allnews;$i++){
								
						//$pic = ($news_list[$i]['avatar'] == '') ? 'theme/assets/avatars/avatar3.png' : 'uploads/news/'.$news_list[$i]['avatar'];
						//$thumb = $pic;
						$number++;
						$news_id = $news_list[$i]['news_id'];
						$news_id2 = $news_list[$i]['news_id2'];
						$dara_id = $news_list[$i]['dara_id'];
						$category_iddb = $news_list[$i]['category_id'];
						$subcategory_iddb = $news_list[$i]['subcategory_id'];
						$category_name = $news_list[$i]['category_name'];
						$subcategory_name = $news_list[$i]['subcategory_name'];
						$countview = $news_list[$i]['countview'];

						$title = $news_list[$i]['title'];
						$description = $news_list[$i]['description'];
						$create_date = $news_list[$i]['create_date'];
						$create_by_name = $news_list[$i]['create_by_name'];
						$pin = $news_list[$i]['pin'];
								
						$news_status = $news_list[$i]['status'];
						$approve = $news_list[$i]['approve'];
						$order_by = $news_list[$i]['order_by'];

						$news_highlight_id = $news_list[$i]['news_highlight_id'];
						$megamenu_id = $news_list[$i]['megamenu_id'];

						$highlight = ($news_highlight_id > 0) ? '<i class="ace-icon fa fa-asterisk hand rmhighlight green" data-value="'.$news_id2.'" id="hl'.$news_id2.'" data-name="'.$title.'" data-rel="tooltip" title="'.$language['highlight'].'"></i>' : '<i class="ace-icon fa fa-asterisk hand sethighlight" data-value="'.$news_id2.'" id="hl'.$news_id2.'" data-name="'.$title.'" data-rel="tooltip" title="Set '.$language['highlight'].'"></i>';

						$megamenu = ($megamenu_id > 0) ? '<i class="ace-icon glyphicon glyphicon-picture hand rmmegamenu green" data-value="'.$news_id2.'" data-name="'.$title.'" data-cat="'.$category_name.'"  data-catid="'.$category_iddb.'" id="mg'.$news_id2.'" data-rel="tooltip" title="Mega menu"></i>' : '<i class="ace-icon glyphicon glyphicon-picture hand setmegamenu" data-value="'.$news_id2.'" data-name="'.$title.'" data-cat="'.$category_name.'"  data-catid="'.$category_iddb.'" id="mg'.$news_id2.'" data-rel="tooltip" title="Set Mega menu"></i>';

						if($news_list[$i]['file_name'] != ''){
								if(file_exists('uploads/thumb/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'])){
									$thumb_img = base_url('uploads/thumb').'/'.$news_list[$i]['folder'].'/'.$news_list[$i]['file_name'];
									$show_img = '<img src="'.$thumb_img.'" width="100" height="57">';
								}else $show_img  = '';
						}else
								$show_img  = '';

					$edit_news = site_url('news/edit/'.$news_id2);
					$pic_news = site_url('news/picture/'.$news_id2);


				$category_name = RewriteTitle($category_name);
				$subcategory_name = RewriteTitle($subcategory_name);
				$titleUrl = RewriteTitle($title);

				if($subcategory_name != '') $category_name = $category_name.'==>'.$subcategory_name;

				//$previewurl = $this->config->config['www']."/news/".$category_iddb."/".$subcategory_iddb."/".$news_id2."/".$category_name."/".$subcategory_name."/".$titleUrl."?preview=1";
				$previewurl = $this->config->config['www']."/news/".$category_iddb."/".$subcategory_iddb."/".$news_id2."/?preview=1";


?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" name="selectid[]" value="<?php echo $news_id2 ?>"/>
										<span class="lbl"></span>
								</label>
						</td> -->

						<td><?php echo intval($order_by)?></td>
						<td class="hidden-480">
							<input class="input-small" name="order[]" type="text" id="order_by" placeholder="Not null" value="<?php echo $order_by?>">
						</td>
						<td><a href="<?php echo $edit_news ?>"><?=$show_img ?></a> </td>
						<td>
							<a href="<?php echo $edit_news ?>"><?=$title?> <?php if($pin > 0) echo sprintf($iconpin, $pin)?></a>
							<br>View : <?php echo $countview?>
							<br><?php echo $language['create_by'] ?> : <?php echo $create_by_name?>
						</td>
						<td class="hidden-480"><?=$news_id2?><input type="hidden" class="ace" name="selectid[]" value="<?php echo $news_id2?>" /></td>
						<td class="hidden-480"><?=$category_name?></td>
						<!-- <td class="hidden-480"><?=$subcategory_name?></td> -->
						<td><?php echo $highlight.' '.$megamenu ?></td>
						<td class="hidden-480"><?php echo RenDateTime($create_date)?></td>
						<td class="center">
							<label class="pull-right inline" id="enable<?php echo $news_id2?>">
								<small class="muted"></small>
								<input type="checkbox" name="status"  id="status<?=$news_id2?>" class="ace ace-switch ace-switch-6 status-buttons" 
								<?php if($news_status  == 1) echo 'checked';?>  value=1>
								<span class="lbl middle"></span>
							</label>
						</td>
						<td class="center">
									<label class="pull-right inline" id="adminapprove<?php echo $news_id2?>">
									<small class="muted"></small>
										<input type="checkbox" name="approve"  id="approve<?=$news_id2?>" class="ace ace-switch ace-switch-4 approve-buttons" data-cat="<?php echo $category_iddb ?>" data-subcat="<?php echo $subcategory_iddb ?>"
										<?php if($approve  == 1) echo 'checked';?>  value=1 <?php if($this->session->userdata('admin_type') > 4) echo 'disabled' ?>>
									<span class="lbl middle"></span>
									</label>
						</td>
						<td>

												<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="<?php echo $previewurl ?>" data-rel="tooltip" title="Preview" target=_blank> -->
																	<a href="javascript:void(0);" class="blue" ><i class="ace-icon fa fa-search-plus bigger-130 bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>" data-rel="tooltip" title="<?php echo $language['preview'] ?>"></i></a>
																<!-- </a> --> 

																<a href="<?php echo $pic_news ?>" data-rel="tooltip" title="<?php echo $language['add_edit_picture'] ?>">
																<i class="menu-icon fa fa-picture-o tooltip-info" data-rel="tooltip" title="<?php echo $language['add_edit_picture'] ?>"></i></a>

																<a class="green" href="<?php echo $edit_news ?>" data-rel="tooltip" title="<?php echo $language['edit'] ?>">
																	<i class="ace-icon fa fa-pencil bigger-130" data-rel="tooltip" title="<?php echo $language['edit'] ?>"></i>
																</a>

																<!-- <a class="green" href="<?php echo $edit_news ?>">
																	<i class="ace-icon fa fa-pencil bigger-130" data-rel="tooltip" title="<?php echo $language['edit'] ?>"></i>
																</a> -->

																<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm<?=$news_id2?>" data-value="<?=$news_id2?>" data-name="<?=$title?>" data-rel="tooltip" title="Delete">
																	<i class="ace-icon fa fa-trash-o bigger-130" data-rel="tooltip" title="<?php echo $language['delete'] ?>"></i>
																</a>
												</div>

												<div class="hidden-md hidden-lg">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																		<li>
																			<a href="javascript:void(0);<?php //echo $previewurl ?>" class="tooltip-info" data-rel="tooltip" title="<?php echo $language['preview'] ?>">
																				<span class="blue">
																					<i class="ace-icon fa fa-search-plus bigger-120 bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="<?php echo $pic_news ?>" class="tooltip-success" data-rel="tooltip" title="<?php echo $language['add_edit_picture'] ?>">
																				<span class="blue">
																					<i class="ace-icon fa fa-picture-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="<?php echo $edit_news ?>" class="tooltip-success" data-rel="tooltip" title="<?php echo $language['edit'] ?>">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="javascript:void(0);" id="bx-confirm<?=$news_id2?>" class="tooltip-error del-confirm" data-value="<?=$news_id2?>" data-rel="tooltip" title="<?php echo $language['delete'] ?>">
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
</div>
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

<?php
	
	if($usedataTables == 0) echo $GenPage ;
	$userdata = $this->session->all_userdata();
	//Debug($userdata);
	if(($userdata['admin_type'] == 1) || ($userdata['admin_id'] <= 3)){ 
			if($category_id > 0){
?>
		<a href="<?php echo base_url('news/auto_order/'.$category_id) ?>" target=_blank>
				<i class="ace-icon glyphicon glyphicon-align-left"> Auto Sort</i>
		</a>
<?php 
			}
	}
?>
								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
<?php
	echo form_close();
?>
</div>
<!-- ace scripts -->
<!-- <script src="<?php echo base_url('theme/assets/js/ace.min.js') ?>"></script> -->

<script type="text/javascript">
$( document ).ready(function(){
<?php
		if($category_id > 0){
?>
			$('#subcategory_id').load('<?php echo base_url() ?>subcategory/list_dd/' + <?php echo $category_id?> + '/' + <?php echo $subcategory_id?>);
<?php
		}
?>

		$('#category_id').change(function( ) {
				var catid = $(this).val();
				//alert('<?php echo base_url() ?>subcategory/list_dd/' + catid);
				$('#subcategory_id').load('<?php echo base_url() ?>subcategory/list_dd/' + catid);
				$('#category').val(catid);
		});

		$('.status-buttons').on('click', function() {
				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);

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

		$('.approve-buttons').on('click', function() {
<?php
				if($this->session->userdata('admin_type') <= 4){	
?>
				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-7;
			    var res = id.substr(7, maxstr);
		    	var GenFile = 0;

				var catid = $(this).attr('data-cat');
				var subcatid = $(this).attr('data-subcat');

				$("#msg_info").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Please, wait...');
				$("#msg_info,#BG_overlay").fadeIn();

				$.ajax({
						type: 'POST',
						url: "<?php echo base_url('news/approve')?>/" + res,
						//data: {id: res},
						cache: false,
						success: function(data){
								if(data == 1){
									//$("#msg_info").html(data);
									//$("#msg_info").fadeIn();
									//$("#msg_error").fadeOut();
									GenFile = 1;
									$("#msg_success").attr('style','display:block;');
									AlertSuccess	('Approve.');
									//AlertSuccess	('Approve And Generate json file.');
									Gencatch(res);

								}else{
									GenFile = 0;
									$("#msg_error").attr('style','display:block;');
									AlertError('Not approve');
									DeleteMemCatch(res);
									DeleteCatch(catid, subcatid, res);
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
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
							//alert('<?php echo base_url('news/delete')?>/' + id + '?cat=<?php echo $category_id ?>');
							window.location='<?php echo base_url('news/delete')?>/' + id + '?cat=<?php echo $category_id ?>' ;
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
										url: "<?php echo base_url('news/sethighlight')?>/" + id,
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
							//alert('<?php echo base_url('news/setmegamenu')?>/' + id + '?title=' + encodeURIComponent(title));
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('news/setmegamenu')?>/" + id,
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
							//alert('<?php echo base_url('news/sethighlight')?>/' + id + '?title=' + encodeURIComponent(title));
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('news/sethighlight')?>/" + id,
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
										url: "<?php echo base_url('news/setmegamenu')?>/" + id,
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

<?php
		/*if($news_list)
		for($i=0;$i<count($news_list);$i++){
				$news_id = $news_list[$i]["news_id2"];*/
?>

		/*$('#bootbox-confirm<?php //echo $news_id?>').on('click', function() {
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
							//alert('del');
							window.location='<?php //echo base_url('news/delete/'.$news_list[$i]['news_id2'])?>?cat=<?php //echo $category_id ?>' ;
						}
				});
		});

		$('#bx-confirm<?php //echo $news_id?>').on('click', function() {
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
							//alert('del');
							window.location='<?php //echo base_url('news/delete/'.$news_list[$i]['news_id2'])?>?cat=<?php //echo $category_id ?>' ;
					}
				});
		});*/

<?php
		//}	//for loop
?>
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
									//Example.show("great success");
									window.open(url + '&device=desktop');
								}
							},
							/*"danger" :
							{
								"label" : "Danger!",
								"className" : "btn-sm btn-danger",
								"callback": function() {
									//Example.show("uh oh, look out!");
								}
							}, */
							"click" :
							{
								"label" : "<i class='ace-icon fa fa-laptop'></i> Mobile ",
								"className" : "btn-sm btn-primary",
								"callback": function() {
									//Example.show("Primary button");
									window.open(url + '&device=mobile');
								}
							}, 
							/*"button" :
							{
								"label" : "Just a button...",
								"className" : "btn-sm"
							}*/
						}
					});
		});
<?php if($usedataTables == 1){ ?> 
		$('#dataTables-news').dataTable();
<?php } ?>
});

function Gencatch(id){
				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				$.ajax({
						type: 'POST',
						url: "<?php echo $this->config->config['api']; ?>/api/web-api.php",
						data: {method : 'DetailNews', key : '<?php echo $this->config->config['api_key']; ?>', news_id : id, gen_file : 1},
						cache: false,
						success: function(v){
							//alert(v.header.message);

							if(v.header.resultcode == 200){
									txt = 'Approve and Genarate news ID ' + v.body.item[0].news_id + ' ' + v.header.message;
									AlertSuccess(txt);
							//}else if(v.header.resultcode == 404){
							}else{
									AlertError(v.header.message);
							}
						},
				});
}

function DeleteMemCatch(id){
				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				$.ajax({
						type: 'POST',
						url: "<?php echo $this->config->config['api']; ?>/api/web-api.php",
						data: {method : 'DeleteCache', key : '<?php echo $this->config->config['api_key']; ?>', id : id, type: 'news', gen_file : 1},
						cache: false,
						success: function(v){
							//alert(v.header.message);

							if(v.header.resultcode == 200){
									//txt = 'Approve and Genarate news ID ' + v.body.item[0].news_id + ' ' + v.header.message;
									//AlertSuccess(txt);
									AlertError('Delete Memcatch news ID ' + id + ' success.');
							}
						},
				});
}

function DeleteCatch(catid, subcatid, id){
//http://dara.siamdara.com/catching/main/?access_token=lUW4Ju2ei6&name=news_1_1_55740&url=http://dara.siamdara.com/news/1/1/55740&device=desktop&opt=del
				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');

				var delurl = '<?php echo $this->config->config['www']; ?>/news/' + catid + '/' + subcatid + '/' + id + '&device=desktop&opt=del';
				var namedel = 'news_'+ catid +'_' + subcatid + '_' + id;
				$.ajax({
						type: 'POST',
						url: "<?php echo $this->config->config['www']; ?>/catching/main",
						data: {name : namedel, access_token : '<?php echo $this->config->config['access_token_www']; ?>', url : delurl},
						cache: false,
						success: function(v){
							//alert(v.header.message);

							if(v.header.resultcode == 200){
									//txt = 'Approve and Genarate news ID ' + v.body.item[0].news_id + ' ' + v.header.message;
									//AlertSuccess(txt);
									AlertError('Delete Memcatch news ID ' + id + ' success.');
							}
						},
				});
}

</script>

<?php //echo js_asset('checkall.js'); ?>
