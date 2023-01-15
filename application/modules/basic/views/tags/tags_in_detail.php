<?php 
		$language = $this->lang->language; 
		$i=0;
		//$maxcat = count($tags_type);
		//Debug($news_list);			
		//Debug($flash_message);			
		//die();

		$mod = ($this->uri->segment(2) != '') ? $this->uri->segment(2) : 'news';
		//Debug($mod);
?>
<div class="col-xs-12">
<?php
			if(isset($error)){
?>
							<div class="alert alert-danger">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon fa fa-times"></i>
											Oh snap! </strong><?php echo $error?>.
									<br>
							</div>
<?php
			}
?>
<div class="page-content-area">
		<div class="page-header">
			<h1>News</h1>
		</div>
											<table id="dataTables-tags" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>ID</th>
														<th>Title</th>
														<th class="hidden-480"><?php echo $language['status'] ?></th>
														<th style="display:none;"><?php echo $language['action'] ?></th>
													</tr>
												</thead>
	<tbody>												
<?php
			$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
			$thumb = '';
			$ref_type = 'news';

			$alltags = count($news_list);
			if($news_list)
					for($i=0;$i<$alltags;$i++){

								$listid = $news_list[$i]->listid;
								$title = $news_list[$i]->title;
								$status = $news_list[$i]->status;
								$approve = $news_list[$i]->approve;

								$tag_status = 1;

								$url = base_url($ref_type.'/edit/'.$listid);
?>
		<tr>
						<td><?php echo $listid?></td>
						<td><a href="<?=$url?>" target=_blank><?=$title?></a></td>
						<td class="hidden-480"><span class="col-sm-12">
								<label class="pull-right inline" id="enable<? //$tag_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="status"  class="ace ace-switch ace-switch-4 status-buttons" data-value="<?php echo $status?>" <?php if($status  == 1) echo 'checked';?>  value=1 disabled>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td style="display:none;"> 
						
															<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a> -->

																<a class="green" href="<?php //echo site_url('tags/edit/'.$tag_id) ?>">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>

																<a class="red del-confirm" href="#" id="bootbox-confirm<?//$pair_id?>" data-value="<?php //echo $pair_id?>" data-name="<? //$tag_text?>">
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
																			<a href="<?php //echo site_url('admin/memberedit/'.$memberlist[$key]->_admin_id); ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="#" id="bootbox-confirm" class="tooltip-error del-confirm" data-value="<?php //echo $pair_id?>" data-name="<? //$tag_text?>" data-rel="tooltip" title="Delete">
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

<!-- Column -->
		<div class="page-header">
			<h1>Column</h1>
		</div>
											<table id="dataTables-tags2" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>ID</th>
														<th>Title</th>
														<th class="hidden-480"><?php echo $language['status'] ?></th>
														<th style="display:none;"><?php echo $language['action'] ?></th>
													</tr>
												</thead>
	<tbody>												
<?php
			$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
			$thumb = '';
			$ref_type = 'column';

			$alltags = count($column_list);
			if($column_list)
					for($i=0;$i<$alltags;$i++){

								$listid = $column_list[$i]->listid;
								$title = $column_list[$i]->title;
								$status = $column_list[$i]->status;
								$approve = $column_list[$i]->approve;
								$tag_status = 1;

								$url = base_url($ref_type.'/edit/'.$listid);
?>
		<tr>
						<td><?php echo $listid?></td>
						<td><a href="<?=$url?>" target=_blank><?=$title?></a></td>
						<td class="hidden-480"><span class="col-sm-12">
								<label class="pull-right inline" id="enable<? //$tag_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="status"  class="ace ace-switch ace-switch-4 status-buttons" data-value="<?php echo $status?>" <?php if($status  == 1) echo 'checked';?>  value=1 disabled>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td style="display:none;"> 
						
															<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a> -->

																<a class="green" href="<?php //echo site_url('tags/edit/'.$tag_id) ?>">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>

																<a class="red del-confirm" href="#" id="bootbox-confirm<?//$pair_id?>" data-value="<?php //echo $pair_id?>" data-name="<? //$tag_text?>">
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
																			<a href="<?php //echo site_url('admin/memberedit/'.$memberlist[$key]->_admin_id); ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="#" id="bootbox-confirm" class="tooltip-error del-confirm" data-value="<?php //echo $pair_id?>" data-name="<? //$tag_text?>" data-rel="tooltip" title="Delete">
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

<!-- Gallery -->
		<div class="page-header">
			<h1>Gallery</h1>
		</div>

											<table id="dataTables-tags3" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>ID</th>
														<th>Title</th>
														<th class="hidden-480"><?php echo $language['status'] ?></th>
														<th style="display:none;"><?php echo $language['action'] ?></th>
													</tr>
												</thead>
	<tbody>												
<?php
			$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
			$thumb = '';
			$ref_type = 'gallery';

			$alltags = count($gallery_list);
			if($gallery_list)
					for($i=0;$i<$alltags;$i++){

								$listid = $gallery_list[$i]->listid;
								$title = $gallery_list[$i]->title;
								$status = $gallery_list[$i]->status;
								$approve = $gallery_list[$i]->approve;
								$tag_status = 1;

								$url = base_url($ref_type.'/edit/'.$listid);
?>
		<tr>
						<td><?php echo $listid?></td>
						<td><a href="<?=$url?>" target=_blank><?=$title?></a></td>
						<td class="hidden-480"><span class="col-sm-12">
								<label class="pull-right inline" id="enable<? //$tag_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="status"  class="ace ace-switch ace-switch-4 status-buttons" data-value="<?php echo $status?>" <?php if($status  == 1) echo 'checked';?>  value=1 disabled>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td style="display:none;"> 
						
															<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a> -->

																<a class="green" href="<?php //echo site_url('tags/edit/'.$tag_id) ?>">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>

																<a class="red del-confirm" href="#" id="bootbox-confirm<?//$pair_id?>" data-value="<?php //echo $pair_id?>" data-name="<? //$tag_text?>">
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
																			<a href="<?php //echo site_url('admin/memberedit/'.$memberlist[$key]->_admin_id); ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="#" id="bootbox-confirm" class="tooltip-error del-confirm" data-value="<?php //echo $pair_id?>" data-name="<? //$tag_text?>" data-rel="tooltip" title="Delete">
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


<!-- Clip -->
		<div class="page-header">
			<h1>Clip</h1>
		</div>
											<table id="dataTables-tags4" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>ID</th>
														<th>Title</th>
														<th class="hidden-480"><?php echo $language['status'] ?></th>
														<th style="display:none;"><?php echo $language['action'] ?></th>
													</tr>
												</thead>
	<tbody>												
<?php
			$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
			$thumb = '';
			$ref_type = 'column';

			$alltags = count($vdo_list);
			if($vdo_list)
					for($i=0;$i<$alltags;$i++){

								$listid = $vdo_list[$i]->listid;
								$title = $vdo_list[$i]->title;
								$status = $vdo_list[$i]->status;
								$approve = $vdo_list[$i]->approve;
								$tag_status = 1;

								$url = base_url($ref_type.'/edit/'.$listid);
?>
		<tr>
						<td><?php echo $listid?></td>
						<td><a href="<?=$url?>" target=_blank><?=$title?></a></td>
						<td class="hidden-480"><span class="col-sm-12">
								<label class="pull-right inline" id="enable<? //$tag_id?>">
								<small class="muted"></small>
									<input type="checkbox" name="status"  class="ace ace-switch ace-switch-4 status-buttons" data-value="<?php echo $status?>" <?php if($status  == 1) echo 'checked';?>  value=1 disabled>
								<span class="lbl middle"></span>
								</label>
								</span>
						</td>
						<td style="display:none;"> 
						
															<div class="hidden-sm hidden-xs action-buttons">
																<!-- <a class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a> -->

																<a class="green" href="<?php //echo site_url('tags/edit/'.$tag_id) ?>">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>

																<a class="red del-confirm" href="#" id="bootbox-confirm<?//$pair_id?>" data-value="<?php //echo $pair_id?>" data-name="<? //$tag_text?>">
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
																			<a href="<?php //echo site_url('admin/memberedit/'.$memberlist[$key]->_admin_id); ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="#" id="bootbox-confirm" class="tooltip-error del-confirm" data-value="<?php //echo $pair_id?>" data-name="<? //$tag_text?>" data-rel="tooltip" title="Delete">
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
								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->

<script type="text/javascript">
$( document ).ready(function() {

		$('#dataTables-tags').dataTable(); 
		$('#dataTables-tags2').dataTable(); 
		$('#dataTables-tags3').dataTable(); 
		$('#dataTables-tags4').dataTable(); 

		$('.status-buttons').on('click', function() {
				/*var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);
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
				});*/
		});

		$('.del-confirm').on('click', function() {				
				/*var id = $(this).attr('data-value');
				var name = $(this).attr('data-name');
				bootbox.confirm("<?php echo $language['are you sure to delete']?><br> id tag pair " + id + '<br>' + name, function(result) {
					if(result) {
							//alert('ยังไม่เปิดใช้งาน');
							window.location='<?php echo base_url('tags/delete')?>/' + id  + '?tag_text=' + name;
						}
				});*/
		});

		$('#saveorder').on('click', function() {
				//document.getElementById("jform").submit();
		});	
});
</script>

<?php //echo js_asset('checkall.js'); ?>