<?php 
	$language = $this->lang->language;
	$opt = array();
	$tmp = 0;
	$gen_url = base_url('json/gen_highlight');
	
	//Debug($highlight_list);
	//Debug($json_highlight);

	if($this->session->userdata('admin_id') != 1){
?>
<style type="text/css">
#alertorder{display:none;}
#nestable-output{display:none;}
</style>
<?php
	}	
?>
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<?php //echo "admin_id = ".$this->session->userdata('admin_id')?>
		<div class="row">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open_multipart('order/save_highlight', $attributes);
?>
			<div class="page-header">
					<h1>
							<?php echo $language['order'] ?>
							<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<?php echo $language['highlight'] ?>
							</small>
					</h1>
			</div>

			<div class="col-xs-12">
<?php
				if(function_exists('Debug')){
					//Debug($highlight_list);
					//die();
				}
				$msg = 'Init and Update';
?>
					<div class="alert alert-success" id="alertorder">
							<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
							</button>
							<strong>
									<i class="ace-icon fa fa-check"></i>
									</strong><span id="msg"><?php echo $msg?></span>
							<br>
					</div>

<?php 
//$relate_list
echo $emptyarr = '<pre id = "nestable-output"></pre>';
/*
$new_highlight = $highlight_list;

	if(isset($highlight_list)){
			$maxorder = count($highlight_list);
			for($i=0;$i<$maxorder;$i++){
					$number = $i+1;
					$new_highlight[$i] = $new_highlight[$i]->order;
					
			}
	}
*/
//Debug($highlight_list); 
//die();
$pathimg = '/uploads/thumb300/';
?>
						<div class="row">
								<div class="col-sm-1">
										<div class="" style="width:100%;height:5px;"></div>

<?php
								if(isset($highlight_list)){
										$maxorder = count($highlight_list);
										//$maxorder = (count($highlight_list) > 5) ? 5 : count($highlight_list);

										for($i=0;$i<$maxorder;$i++){
												$number = $i+1;

												if(isset($highlight_list[$i]->highlight_id)){
															
													$highlight_id = $highlight_list[$i]->highlight_id;
													$news_id = $highlight_list[$i]->article_id;
													$title = $highlight_list[$i]->title;

													$status = ($highlight_list[$i]->status == 1) ? 'Actived' : 'Inactived';
													$approve = ($highlight_list[$i]->approve == 1) ? 'approve' : 'no approved';

													$title=ClearTxt($title);
													$title=str_replace("-----"," ",$title);
													$title=str_replace("----"," ",$title);
													$title=str_replace("---"," ",$title);
													$title=str_replace("--"," ",$title);
													$title=str_replace("-"," ",$title);
																
													$button_del = '<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm'.$highlight_id.'" data-value="'.$highlight_id.'" data-name="'.$title.'" data-rel="tooltip" title="Delete"><i class="ace-icon fa fa-trash-o bigger-130" data-rel="tooltip" title="'.$language['delete'].'"></i></a>';
													echo '<div class="alert alert-info" style="width:100%;height:108px;margin: 1px 0px 5px 0px;" >'.$number.' '.$button_del.'</div>';
												}
										}
								}
?>								
								</div>

								<div class="col-sm-9">
										<div class="dd" id="nestable">
											<ol class="dd-list">
											<?php
												$sticker = '
														<span class="sticker">
															<span class="label label-success arrowed-in">
																<i class="ace-icon fa fa-check bigger-110"></i>
															</span>
														</span>';
												//Debug($highlight_list);

												if(isset($highlight_list))

														$maxopt = (count($highlight_list) > 5) ? 5 : count($highlight_list);
														//for($i=0;$i<$maxorder;$i++){
														for($i=0;$i<$maxopt;$i++){
															$number = $i+1;
															$opt[]	= makeOption($number, $number);
														}
														$type = '';
														
														for($i=0;$i<$maxorder;$i++){
														//foreach($highlight_list as $i => $obj){
															$number = $i + 1;
															$type = "";
															/*foreach($obj as $field => $val){
																	if($field == "news_highlight_id") $highlight_id = $val;
																	if($field == "news_id") $news_id = $val;
																	if($field == "ref_type") $ref_type = $val;
																	if($field == "order") $order = $val;
																	if($field == "title") $title = $val;
																	if($field == "category_name") $category_name = $val;
																	if($field == "lastupdate_date") $lastupdate_date = $val;
																	if($field == "create_date") $create_date = $val;
																	if($field == "countview") $countview = $val;

																	if($field == "file_name") $file_name = $val;
																	if($field == "folder") $folder = $val;
																	if($field == "type") $type = $val;
															}*/

															if(isset($highlight_list[$i]->highlight_id)){

																$highlight_id = $highlight_list[$i]->highlight_id;
																$news_id = $highlight_list[$i]->article_id;
																$ref_type = $highlight_list[$i]->ref_type;
																$order = $highlight_list[$i]->order;
																$title = $highlight_list[$i]->title;

																$status = ($highlight_list[$i]->status == 1) ? 'Actived' : 'Inactived';
																$approve = ($highlight_list[$i]->approve == 1) ? 'approve' : 'no approved';

																$category_name = (isset($highlight_list[$i]->category_name)) ? $highlight_list[$i]->category_name : '';
																$subcategory_name = (isset($highlight_list[$i]->subcategory_name)) ? ' => '.$highlight_list[$i]->subcategory_name : '';

																$lastupdate_date = $highlight_list[$i]->lastupdate_date;
																$create_date = $highlight_list[$i]->create_date;
																$countview = $highlight_list[$i]->countview;
																$file_name = $highlight_list[$i]->file_name;
																$folder = $highlight_list[$i]->folder;

																$type = (isset($highlight_list[$i]->type)) ? $highlight_list[$i]->type : "Gallery";
																//if($type == "") $highlight_list[$i]->type = "Gallery";

																$pathpic = base_url($pathimg."/".$folder."/".$file_name);

																switch($type){
																		/*case "News" : $pathpic = base_url("uploads/news/".$folder."/".$file_name); break;
																		case "Column" : $pathpic = base_url("uploads/column/".$folder."/".$file_name); break;
																		case "Gallery" : $pathpic = base_url("uploads/gallery/".$folder."/".$file_name); break;
																		case "Clip" : $pathpic = base_url("uploads/vdo/".$folder."/".$file_name); break;
																		default : $pathpic = base_url("uploads/thumb300/".$folder."/".$file_name); break;*/
																}
																//Debug($pathpic);
																$highlight_list[$i]->picture = $pathpic;
																//Debug($highlight_list[$i]);

																switch(intval($ref_type)){
																		default : 
																			$type='Article'; 
																			$sticker = '
																				<span class="sticker">
																					<span class="label label-success arrowed-in">
																						<i class="ace-icon fa fa-gavel bigger-110"> '.$type.'</i>
																					</span>
																				</span>';
																		break;
																		/*case 1 : 
																			$type='News'; 
																			$sticker = '
																				<span class="sticker">
																					<span class="label label-success arrowed-in">
																						<i class="ace-icon fa fa-gavel bigger-110"> '.$type.'</i>
																					</span>
																				</span>';
																		break;
																		case 2 : 
																			$type='Column'; 
																			$sticker = '
																				<span class="sticker">
																					<span class="label label-info arrowed-in">
																						<i class="ace-icon fa fa-pencil-square-o bigger-110"> '.$type.'</i>
																					</span>
																				</span>';
																		break;
																		case 3 : 
																			$type='Gallery'; 
																			$sticker = '
																				<span class="sticker">
																					<span class="label label-warning arrowed-in">
																						<i class="ace-icon fa fa-picture-o bigger-110"> '.$type.'</i>
																					</span>
																				</span>';
																		break;
																		case 4 : 
																			$type='Clip'; 
																			$sticker = '
																				<span class="sticker">
																					<span class="label label-danger arrowed-in">
																						<i class="ace-icon fa fa-paperclip bigger-110"> '.$type.'</i>
																					</span>
																				</span>';
																		break;*/
																}

																//echo "<hr>";

																//Debug($highlight_list[$i]);
																/*$highlight_id = $highlight_list[$i]->news_highlight_id;
																$news_id = $highlight_list[$i]->news_id;
																$ref_type = $highlight_list[$i]->ref_type;

																$order = $highlight_list[$i]->order;
																$title = $highlight_list[$i]->title;
																$category_name = $highlight_list[$i]->category_name;
																$lastupdate_date = RenDateTime($highlight_list[$i]->lastupdate_date);
																$create_date = $highlight_list[$i]->create_date;
																$countview = $highlight_list[$i]->countview;*/

																//list($create_date, $time) = explode(" ", $highlight_list[$i]->folder_news);
																//$folder_news = str_replace("-", "", $create_date);

																if($file_name != ''){
																		$displayimg = base_url($pathimg.$folder.'/'.$file_name);
																		$displayimg = '<img src="'.$displayimg.'" width="100" border="0" alt="">';
																}else
																		$displayimg = '';

																//$showsticker = '';
																$showsticker = $sticker;

																//'.$status .' '.$approve.'

																//if(!isset($category_name)) $category_name = "Gallery";
																echo '
																<li class="dd-item" data-id="'.$ref_type.'-'.$news_id.'" value="'.$order.'" data="'.$order.'">
																	<div class="dd-handle">
																			<table padding>
																				<tr>
																					<td width="25%"> '.$displayimg.' </td>
																					<td width="75%" style="padding: 5px;">'.DisplayTxt($title, 55).'<br>'.$category_name.$subcategory_name.'<br>'.$lastupdate_date.'<br>view '.$countview.'  '.$showsticker.'</td>
																				</tr>
																			</table>
																	</div>
																</li>';
															}

														}
											?>
											</ol>
										</div>

								</div>
					</div>
<?php 
		$disable = $default_order = '';
		//Debug($json_highlight);
		//Debug($json_highlight['body']['item']);

		if(isset($json_highlight['body']['item']['article'])){
			$default_order = (count($json_highlight['body']['item']['article'])>5) ? 5 : count($json_highlight['body']['item']['article']);
		}else
			$default_order = 5;
?>
					<div style="clear: both;"></div>
							<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> Display </label>
										<div class="col-sm-9">
											<?php echo $list_order = selectListOrder( $opt, 'number', 'list_order', 'class="input-small" '.$disable, 'value', 'text', $default_order); ?>
										</div>
							</div>
					</div>

					<div style="clear: both;"></div>
							<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
												&nbsp; &nbsp; &nbsp;
												<button type="button" class="btn btn-success" id="gen_json">
													<i class="ace-icon glyphicon glyphicon-file bigger-110"></i> <?php echo $language['generate_catch']; ?></span>
												</button>
										</div>
										<!-- <a href="http://daraapi.siamsport.co.th/api/main.php?method=Navigator&key=mMs3dAkM&lang=th&gen_file=1" target=_blank>Generate</a> -->
							</div>
					</div>

					<div class="col-sm-12">
								<div id="gen_data"></div>
					</div>
					<?php 
					//Debug($highlight_list);
					?>
				</div>
				<?php echo form_close();?>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );

		$('.del-confirm').on('click', function() {				
				var id = $(this).attr('data-value');
				var title = $(this).attr('data-name');
				bootbox.confirm("<?php echo $language['are you sure to delete']?> <br><b>highlight id " + id + "</b> <span class='green'>" + title + "</span>", function(result) {
					if(result) {

							title = title.replace("!", ""); 
							title = title.replace("?", ""); 
							title = title.replace("&", ""); 
							title = title.replace(".", ""); 
							//alert('<?php echo base_url('order/delete_highlight')?>/' + id + '/' + encodeURI(title));
							window.location='<?php echo base_url('order/delete_highlight')?>/' + id + '/' + encodeURI(title);
						}
				});
		});

		$('#gen_json').on('click', function(e){
				var list_order = $('#list_order').val();
				//alert(list_order);
<?php
				//$('#gen_data').html('<iframe frameborder="0"scrolling="Yes" src="http://daraapi.siamsport.co.th/api/main.php?method=NewsHilight&amp;key=mMs3dAkM&amp;lang=th&amp;gen_file=1" width="100%" height=100px></iframe>');
?>
				//window.location.href ='<?php echo base_url('json/gen_highlight') ?>?number=' + list_order;
				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				gen_highlight(list_order);
		});

<?php 
		//http://daraapi.siamsport.co.th/api/main.php?method=Navigator&key=mMs3dAkM&lang=th&gen_file=1
		//http://daraapi.siamsport.co.th/api/main.php?method=NewsHilight&key=mMs3dAkM&lang=th

		//if($picture_list){ echo "$('#upload_avatar').attr('style', 'display:none');\n";	} 
?>
		$('#modal-form').on('shown.bs.modal', function () {
				$(this).find('.chosen-container').each(function(){
						$(this).find('a:first-child').css('width' , '210px');
						$(this).find('.chosen-drop').css('width' , '210px');
						$(this).find('.chosen-search input').css('width' , '200px');
				});
		})

<?php
		$datainput = $this->input->get();			
		if(isset($datainput['auto'])){
?>
		var updateOutput = function(e){
				var list   = e.length ? e : $(e.target),
					output = list.data('output');
				if (window.JSON) {
					output.html(window.JSON.stringify(list.nestable('serialize')));
					UpdateOrder(window.JSON.stringify(list.nestable('serialize')));
				} else {
					output.html('JSON browser support required for this demo.');
					$("#alertorder").fadeIn();
					$("#msg").html('JSON browser support required for this demo.');
				}
		};
		$('.dd').nestable({
			group: 1
		}).on('change', updateOutput);
		updateOutput($('.dd').data('output', $('#nestable-output')));
<?php } ?>

});

function UpdateOrder(json){
		
			//alert(json);
			$.ajax({
					type: 'POST',
					url: "<?php echo base_url('order/update_hl')?>",
					data: {json: json},
					cache: false,
					success: function(data){
							//alert(data);
							$("#alertorder").fadeIn();
							$("#msg").html(data);
					}
			});

			setTimeout(function(){
					$("#alertorder").fadeOut("slow", function () {});
			}, 3000);
}

function gen_highlight(list_order){

			//Gen Home Page
			//$.getJSON( "<?php echo base_url('json/gen_highlight') ?>?number=" + list_order, function( rsponse ) {
			$.getJSON( "<?php echo $gen_highlight ?>&num=" + list_order, function( rsponse ) {
					//alert(rsponse);
					if(rsponse.header.resultcode == 200){
							AlertSuccess	('Generate highlight success.');
							//Gen_www();
					}else{
							//AlertError('Can not generate <?php echo $this->config->config['www']; ?>');
					}
			});	

}

function Gen_www(){
			WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
			//Gen Home Page
			$.getJSON( "<?php echo $gen_home ?>", function( rsponse ) {
					if(rsponse.meta.code == 200){
							AlertSuccess	('Building <?php echo $this->config->config['www']; ?> success.');
					}else{
							AlertError('Can not generate <?php echo $this->config->config['www']; ?>');
					}
			});	
}
</script>