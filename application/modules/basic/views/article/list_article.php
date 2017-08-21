<?php 
	$language = $this->lang->language; 
	$i=0;
	$usedataTables = 1;
	$allarticle = count($article_list);

	if(!isset($category_id)) $category_id = 0;
	if(!isset($listspage)) $listspage = 10;

	echo css_asset('font-awesome2.css');
	$iconpin = '<i class="icon-pushpin red"> %d </i>';

	//Debug($article_list);
	//die();
	//Debug($category_id);
	//Debug($this->session->userdata);
	
	//$datainput = $this->input->post();
	//Debug($datainput);
	//echo 'access_cat';
	//Debug($access_cat);
?>
<style type="text/css">
.col-sm-6 {width: 100%;text-align: center;	}
#saveorder{cursor:pointer;}
.input-small{text-align: center;}
.hand{cursor:pointer;}
.sethighlight, .setmegamenu, .seteditor_picks{color:#cccccc;}
.gray{color: #B3B3B3;}
</style>

<div class="row">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'filterjform');
	echo form_open('article', $attributes);
?>
		<div class="col-xs-12 col-lg-3">
				<span>
						<button type="button" class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('article/add') ?>';">
							<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add'].' '.$language['article']  ?>
						</button> <?php echo $allarticle." of ".number_format($totalarticle)." record." ?>						
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
											<option value="1" <?php if($this->input->post('sp') == 1) echo 'selected="selected"'?> >Highlight</option>
											<option value="2" <?php if($this->input->post('sp') == 2) echo 'selected="selected"'?> >Mega menu</option>
											<option value="3" <?php if($this->input->post('sp') == 3) echo 'selected="selected"'?> >Editor's Picks</option>
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
	echo form_open('article', $attributes);
?>
		<input type="hidden" name="category" value="<?php echo $category_id ?>">
											<table id="dataTables-article" class="table-responsive table table-striped table-bordered table-hover ">
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

			if($article_list)
					for($i=0;$i<$allarticle;$i++){
								
						//$pic = ($article_list[$i]['avatar'] == '') ? 'theme/assets/avatars/avatar3.png' : 'uploads/article/'.$article_list[$i]['avatar'];
						//$thumb = $pic;
						$number++;
						$article_id = $article_list[$i]['article_id'];
						$article_id2 = $article_list[$i]['article_id2'];
						//$dara_id = $article_list[$i]['dara_id'];
						$category_iddb = $article_list[$i]['category_id'];
						$subcategory_iddb = $article_list[$i]['subcategory_id'];
						$category_name = $article_list[$i]['category_name'];
						$subcategory_name = $article_list[$i]['subcategory_name'];
						$countview = $article_list[$i]['countview'];

						$title = $article_list[$i]['title'];
						$description = $article_list[$i]['description'];
						$create_date = $article_list[$i]['create_date'];
						//$create_date = RenDateTime($create_date);
						$create_by_name = $article_list[$i]['create_by_name'];
						$pin = $article_list[$i]['pin'];
								
						$article_status = $article_list[$i]['status'];
						$approve = $article_list[$i]['approve'];
						$order_by = $article_list[$i]['order_by'];
						//$is_18 = $article_list[$i]['is_18'];
						$is_18 = $article_list[$i]['up18'];

						if(isset($article_list[$i]['highlight_id']))
							$highlight_id = $article_list[$i]['highlight_id'];
						else
							$highlight_id = '';

						if(isset($article_list[$i]['megamenu_id']))
							$megamenu_id = $article_list[$i]['megamenu_id'];
						else
							$megamenu_id = '';

						if(isset($article_list[$i]['editor_picks_id']))
							$editor_picks_id = $article_list[$i]['editor_picks_id'];
						else
							$editor_picks_id = '';

						$highlight = ($highlight_id > 0) ? '<i class="ace-icon fa fa-asterisk hand rmhighlight green" data-value="'.$article_id2.'" id="hl'.$article_id2.'" data-name="'.$title.'" data-rel="tooltip" title="'.$language['highlight'].'"></i>' : '<i class="ace-icon fa fa-asterisk hand sethighlight" data-value="'.$article_id2.'" id="hl'.$article_id2.'" data-name="'.$title.'" data-rel="tooltip" title="Set '.$language['highlight'].'"></i>';

						$megamenu = ($megamenu_id > 0) ? '<i class="ace-icon glyphicon glyphicon-picture hand rmmegamenu green" data-value="'.$article_id2.'" data-name="'.$title.'" data-cat="'.$category_name.'"  data-catid="'.$category_iddb.'" id="mg'.$article_id2.'" data-rel="tooltip" title="Mega menu"></i>' : '<i class="ace-icon glyphicon glyphicon-picture hand setmegamenu" data-value="'.$article_id2.'" data-name="'.$title.'" data-cat="'.$category_name.'"  data-catid="'.$category_iddb.'" id="mg'.$article_id2.'" data-rel="tooltip" title="Set Mega menu"></i>';

						$editor_picks = ($editor_picks_id > 0) ? '<i class="ace-icon fa fa-flask hand rmeditor_picks green" data-value="'.$article_id2.'" id="ed'.$article_id2.'" data-name="'.$title.'" data-rel="tooltip" title="Editor\'s Picks"></i>' : '<i class="ace-icon fa  fa-flask hand seteditor_picks" data-value="'.$article_id2.'" id="ed'.$article_id2.'" data-name="'.$title.'" data-rel="tooltip" title="Set Editor\'s Picks"></i>';

						if($article_list[$i]['file_name'] != ''){
								if(file_exists('uploads/thumb300/'.$article_list[$i]['folder'].'/'.$article_list[$i]['file_name'])){
									$thumb_img = base_url('uploads/thumb300').'/'.$article_list[$i]['folder'].'/'.$article_list[$i]['file_name'];
									$show_img = '<img src="'.$thumb_img.'" width="100" height="57">';
								}else
									$show_img  = '<img src="'._IMG_NOTFOUND.'" width="100" height="57">';
						}else
								$show_img  = '<img src="'._IMG_NOTFOUND.'" width="100" height="57">';

				$edit_article = site_url('article/edit/'.$article_id2);
				$gallery_article = site_url('article/gallery/'.$article_id2);

				$category_name = RewriteTitle($category_name);
				$subcategory_name = RewriteTitle($subcategory_name);
				$titleUrl = RewriteTitle($title);

				if($subcategory_name != '') $category_name = $category_name.'==>'.$subcategory_name;

				//$previewurl = $this->config->config['www']."/article/".$category_iddb."/".$subcategory_iddb."/".$article_id2."/".$category_name."/".$subcategory_name."/".$titleUrl."?preview=1";
				$previewurl = $this->config->config['www']."/article/".$category_iddb."/".$subcategory_iddb."/".$article_id2."/?preview=1";
?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" name="selectid[]" value="<?php echo $article_id2 ?>"/>
										<span class="lbl"></span>
								</label>
						</td> -->

						<td><?php echo intval($order_by)?></td>
						<td class="hidden-480">
							<input class="input-small" name="order[]" type="text" id="order_by" placeholder="Not null" value="<?php echo $order_by?>">
						</td>
						<td><a href="<?php echo $edit_article ?>" data-rel="tooltip" title="<?php echo $language['edit'] ?>"><?=$show_img ?></a> </td>
						<td>
							<a href="<?php echo $edit_article ?>" data-rel="tooltip" title="<?php echo $language['edit'] ?>"><?=$title?> 
							<span class="pull-right badge badge-danger"><?php if($is_18 > 0) echo "18+" ?></span>
							<?php if($pin > 0) echo sprintf($iconpin, $pin)?></a>
							<br>View : <?php echo $countview?>
							<br><?php echo $language['create_by'] ?> : <?php echo $create_by_name?>
						</td>
						<td class="hidden-480"><?=$article_id2?><input type="hidden" class="ace" name="selectid[]" value="<?php echo $article_id2?>" /></td>
						<td class="hidden-480"><?=$category_name?></td>
						<!-- <td class="hidden-480"><?=$subcategory_name?></td> -->
						<td><?php echo $highlight.' '.$megamenu.' '.$editor_picks ?></td>
						<td class="hidden-480"><?php echo $create_date?></td>
						<td class="center">
							<label class="pull-right inline" id="enable<?php echo $article_id2?>">
								<small class="muted"></small>
								<input type="checkbox" name="status"  id="status<?=$article_id2?>" class="ace ace-switch ace-switch-6 status-buttons" 
								<?php if($article_status  == 1) echo 'checked';?>  value=1>
								<span class="lbl middle"></span>
							</label>
						</td>
						<td class="center">
									<label class="pull-right inline" id="adminapprove<?php echo $article_id2?>">
									<small class="muted"></small>
										<input type="checkbox" name="approve"  id="approve<?=$article_id2?>" class="ace ace-switch ace-switch-4 approve-buttons" data-cat="<?php echo $category_iddb ?>" data-subcat="<?php echo $subcategory_iddb ?>"
										<?php if($approve  == 1) echo 'checked';?>  value=1 <?php if($this->session->userdata('admin_type') > 4) echo 'disabled' ?>>
									<span class="lbl middle"></span>
									</label>
						</td>
						<td>

							<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="<?php echo $previewurl ?>" data-rel="tooltip" title="Preview" target=_blank> -->
																	<a href="javascript:void(0);" class="blue" ><i class="ace-icon fa fa-search-plus bigger-130 bootbox-options" data-value="<?=$previewurl?>" data-name="<?=$title?>" data-rel="tooltip" title="<?php echo $language['preview'] ?>"></i></a>
																<!-- </a> --> 

																<a href="<?php echo $gallery_article ?>" data-rel="tooltip" title="<?php echo $language['gallery'] ?>">
																<i class="menu-icon fa fa-picture-o tooltip-info" data-rel="tooltip" title="<?php echo $language['gallery'] ?>"></i></a>

																<a class="green" href="<?php echo $edit_article ?>" data-rel="tooltip" title="<?php echo $language['edit'] ?>">
																	<i class="ace-icon fa fa-pencil bigger-130" data-rel="tooltip" title="<?php echo $language['edit'] ?>"></i>
																</a>

																<!-- <a class="green" href="<?php echo $edit_article ?>">
																	<i class="ace-icon fa fa-pencil bigger-130" data-rel="tooltip" title="<?php echo $language['edit'] ?>"></i>
																</a> -->

																<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm<?=$article_id2?>" data-value="<?=$article_id2?>" data-name="<?=$title?>" data-rel="tooltip" title="Delete">
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
																			<a href="<?php echo $gallery_article ?>" class="tooltip-success" data-rel="tooltip" title="<?php echo $language['gallery'] ?>">
																				<span class="blue">
																					<i class="ace-icon fa fa-picture-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="<?php echo $edit_article ?>" class="tooltip-success" data-rel="tooltip" title="<?php echo $language['edit'] ?>">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="javascript:void(0);" id="bx-confirm<?=$article_id2?>" class="tooltip-error del-confirm" data-value="<?=$article_id2?>" data-rel="tooltip" title="<?php echo $language['delete'] ?>">
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
</div></div>
<?php
	
	if($usedataTables == 0) echo $GenPage ;

	$userdata = $this->session->all_userdata();
	//Debug($userdata);
	if(($userdata['admin_type'] == 1) || ($userdata['admin_id'] <= 3)){ 
			if($category_id > 0){
?>
		<a href="<?php echo base_url('article/auto_order/'.$category_id) ?>" target=_blank>
				<i class="ace-icon glyphicon glyphicon-align-left"> Auto Sort</i>
		</a>
<?php 
			}
	}
?>

<div class="center">
	<span class="action-buttons gray"> <?php echo $language['iconmain'] ?></span>
	<span class="action-buttons gray">
		<i class="ace-icon fa fa-search-plus"> <?php echo $language['preview'] ?></i> |
		<i class="ace-icon fa fa-picture-o"> <?php echo $language['gallery'] ?></i> |
		<i class="ace-icon fa fa-pencil"> <?php echo $language['edit'] ?></i> |
		<i class="ace-icon fa fa-trash-o"> <?php echo $language['delete'] ?></i>
	</span>
</div>

</div><!-- PAGE CONTENT ENDS -->

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
					url: "<?php echo base_url('article/status')?>/" + res,
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
						url: "<?php echo base_url('article/approve')?>/" + res,
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
									//Gencatch(res);

								}else{
									GenFile = 0;
									$("#msg_error").attr('style','display:block;');
									AlertError('Not approve.');
									//DeleteMemCatch(res);
									//DeleteCatch(catid, subcatid, res);
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
							//alert('<?php echo base_url('article/delete')?>/' + id + '?cat=<?php echo $category_id ?>');
							window.location='<?php echo base_url('article/delete')?>/' + id + '?cat=<?php echo $category_id ?>' ;
						}
				});
		});

		$('.sethighlight').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				bootbox.confirm("<h4 class='row header smaller lighter purple'>Set Highlight </h4>" + title , function(result) {
					if(result) {
							//alert('<?php echo base_url('article/sethighlight')?>/' + id + '?title=' + encodeURIComponent(title));
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('article/sethighlight')?>/" + id,
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
							//alert('<?php echo base_url('article/setmegamenu')?>/' + id + '?title=' + encodeURIComponent(title));
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('article/setmegamenu')?>/" + id,
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

		$('.seteditor_picks').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				bootbox.confirm("<h4 class='row header smaller lighter purple'>Set editor'picks </h4>" + title , function(result) {
					if(result) {
							//alert('<?php echo base_url('article/seteditor_picks')?>/' + id + '?title=' + title);
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('article/seteditor_picks')?>/" + id,
										data: {title: encodeURIComponent(title), op : 'set'},
										cache: false,
										success: function(data){
												//alert(data);
												if(data == 1){
													$('#ed' + id).attr('style', 'color:green');
													AlertSuccess	('Set editor\'picks ' + title);
												}else{
													AlertError('Not Set');
												}
										}
								});
						}
				});
		});

		$('.rmeditor_picks').on('click', function() {
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				//alert('<?php echo base_url('article/seteditor_picks')?>/' + id + '?op=remove&title=' + title);
				bootbox.confirm("<h4 class='row header smaller lighter red'>Remove editor'picks </h4>" + title , function(result) {
					if(result) {
							//alert('<?php echo base_url('article/seteditor_picks')?>/' + id + '?op=remove&title=' + encodeURIComponent(title));
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('article/seteditor_picks')?>/" + id,
										data: {title: encodeURIComponent(title), op : 'remove'},
										cache: false,
										success: function(data){
												//alert(data);
												if(data == 0){
													$('#ed' + id).attr('class', 'ace-icon fa  fa-flask hand');
													AlertSuccess	('Remove editor\'s picks' + title);
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
							//alert('<?php echo base_url('article/sethighlight')?>/' + id + '?title=' + encodeURIComponent(title));
							$.ajax({
										type: 'POST',
										url: "<?php echo base_url('article/sethighlight')?>/" + id,
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
										url: "<?php echo base_url('article/setmegamenu')?>/" + id,
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
		/*if($article_list)
		for($i=0;$i<count($article_list);$i++){
				$article_id = $article_list[$i]["article_id2"];*/
?>

		/*$('#bootbox-confirm<?php //echo $article_id?>').on('click', function() {
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
							//alert('del');
							window.location='<?php //echo base_url('article/delete/'.$article_list[$i]['article_id2'])?>?cat=<?php //echo $category_id ?>' ;
						}
				});
		});

		$('#bx-confirm<?php //echo $article_id?>').on('click', function() {
				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
							//alert('del');
							window.location='<?php //echo base_url('article/delete/'.$article_list[$i]['article_id2'])?>?cat=<?php //echo $category_id ?>' ;
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
		//$('#dataTables-article').dataTable();
		$('#dataTables-article').dataTable( {
			"order": [[ 4, "desc" ]],
			stateSave: true
		} );
<?php } ?>
});

function Gencatch(id){
				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				$.ajax({
						type: 'POST',
						url: "<?php //echo $this->config->config['api']; ?>/api/web-api.php",
						data: {method : 'Detailarticle', key : '<?php //echo $this->config->config['api_key']; ?>', article_id : id, gen_file : 1},
						cache: false,
						success: function(v){
							//alert(v.header.message);

							if(v.header.resultcode == 200){
									txt = 'Approve and Genarate article ID ' + v.body.item[0].article_id + ' ' + v.header.message;
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
						url: "<?php //echo $this->config->config['api']; ?>/api/web-api.php",
						data: {method : 'DeleteCache', key : '<?php //echo $this->config->config['api_key']; ?>', id : id, type: 'article', gen_file : 1},
						cache: false,
						success: function(v){
							//alert(v.header.message);

							if(v.header.resultcode == 200){
									//txt = 'Approve and Genarate article ID ' + v.body.item[0].article_id + ' ' + v.header.message;
									//AlertSuccess(txt);
									AlertError('Delete Memcatch article ID ' + id + ' success.');
							}
						},
				});
}

function DeleteCatch(catid, subcatid, id){
//http://dara.siamdara.com/catching/main/?access_token=lUW4Ju2ei6&name=article_1_1_55740&url=http://dara.siamdara.com/article/1/1/55740&device=desktop&opt=del
				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');

				var delurl = '<?php //echo $this->config->config['www']; ?>/article/' + catid + '/' + subcatid + '/' + id + '&device=desktop&opt=del';
				var namedel = 'article_'+ catid +'_' + subcatid + '_' + id;
				$.ajax({
						type: 'POST',
						url: "<?php //echo $this->config->config['www']; ?>/catching/main",
						data: {name : namedel, access_token : '<?php echo $this->config->config['access_token_www']; ?>', url : delurl},
						cache: false,
						success: function(v){
							//alert(v.header.message);

							if(v.header.resultcode == 200){
									//txt = 'Approve and Genarate article ID ' + v.body.item[0].article_id + ' ' + v.header.message;
									//AlertSuccess(txt);
									AlertError('Delete Memcatch article ID ' + id + ' success.');
							}
						},
				});
}

</script>

<?php //echo js_asset('checkall.js'); ?>
