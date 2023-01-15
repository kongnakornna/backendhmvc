<?php 
	$language = $this->lang->language;
	$opt = array();
	$tmp = 0;
	$type = 'Clip';
	$ref_typ = 4;
	//$gen_url = base_url('json/gen_highlight');
	//Debug($clipded_list);
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
	echo form_open_multipart('order/save_clipded', $attributes);
?>
			<div class="page-header">
					<h1>
							<?php echo $language['order'] ?>
							<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Clipded<?php //echo $language['highlight'] ?>
							</small>
					</h1>
			</div>

			<div class="col-xs-12">
<?php
				if(function_exists('Debug')){
					//Debug($clipded_list);
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
$new_highlight = $clipded_list;

	if(isset($clipded_list)){
			$maxorder = count($clipded_list);
			for($i=0;$i<$maxorder;$i++){
					$number = $i+1;
					$new_highlight[$i] = $new_highlight[$i]->order;
					
			}
	}
*/
//Debug($clipded_list);
//die();
$pathimg = '/uploads/thumb/';
?>
						<div class="row">
								<div class="col-sm-1">
										<div class="" style="width:100%;height:5px;"></div>

<?php
								if(isset($clipded_list)){
										$maxorder = count($clipded_list);
										for($i=0;$i<$maxorder;$i++){
												$number = $i+1;

												if(isset($clipded_list[$i]->clipded_id)){
															
													$clipded_id = $clipded_list[$i]->clipded_id;
													$clip_id = $clipded_list[$i]->clip_id;
													$title = $clipded_list[$i]->title;
													$order = $clipded_list[$i]->order;

													//$status = ($clipded_list[$i]->status == 1) ? 'Actived' : 'Inactived';
													//$approve = ($clipded_list[$i]->approve == 1) ? 'approve' : 'no approved';

													$title=ClearTxt($title);
													$title=str_replace("-----"," ",$title);
													$title=str_replace("----"," ",$title);
													$title=str_replace("---"," ",$title);
													$title=str_replace("--"," ",$title);
													$title=str_replace("-"," ",$title);
													$title=str_replace("#34","",$title);
																
													$button_del = '<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm'.$clipded_id.'" data-value="'.$clip_id.'" data-name="'.$title.'" data-rel="tooltip" title="Delete">
																		<i class="ace-icon fa fa-trash-o bigger-130" data-rel="tooltip" title="'.$language['delete'].'"></i>
																	</a>';

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
												//Debug($clipded_list);

												if(isset($clipded_list))

														$maxorder = count($clipded_list);

														for($i=0;$i<$maxorder;$i++){
															$number = $i+1;
															$opt[]	= makeOption($number, $number);
														}
														
														for($i=0;$i<$maxorder;$i++){
														//foreach($clipded_list as $i => $obj){
															$number = $i + 1;
															$type = "";

															if(isset($clipded_list[$i]->clipded_id)){

																$highlight_id = $clipded_list[$i]->clipded_id;
																$clip_id = $clipded_list[$i]->clip_id;

																$order = $clipded_list[$i]->order;
																$title = $clipded_list[$i]->title;

																//$status = ($clipded_list[$i]->status == 1) ? 'Actived' : 'Inactived';
																//$approve = ($clipded_list[$i]->approve == 1) ? 'approve' : 'no approved';

																//$category_name = (isset($clipded_list[$i]->category_name)) ? $clipded_list[$i]->category_name : '';

																$lastupdate_date = $clipded_list[$i]->lastupdate_date;
																$create_date = $clipded_list[$i]->create_date;
																$countview = $clipded_list[$i]->countview;
																$file_name = $clipded_list[$i]->file_name;
																$folder = $clipded_list[$i]->folder;

																//$type = (isset($clipded_list[$i]->type)) ? $clipded_list[$i]->type : "Clip";
																switch($type){
																		case "News" : $pathpic = base_url("uploads/news/".$folder."/".$file_name); break;
																		case "Column" : $pathpic = base_url("uploads/column/".$folder."/".$file_name); break;
																		case "Gallery" : $pathpic = base_url("uploads/gallery/".$folder."/".$file_name); break;
																		case "Clip" : $pathpic = base_url("uploads/vdo/".$folder."/".$file_name); break;
																		default : $pathpic = base_url("uploads/news/".$folder."/".$file_name); break;
																}
																$clipded_list[$i]->picture = $pathpic;

																switch($ref_typ){
																		case 4 : 
																			$type='Clip'; 
																			$sticker = '
																				<span class="sticker">
																					<span class="label label-danger arrowed-in">
																						<i class="ace-icon fa fa-paperclip bigger-110"> '.$type.'</i>
																					</span>
																				</span>';
																		break;
																}


																if($file_name != ''){
																		$displayimg = base_url($pathimg.$folder.'/'.$file_name);
																		$displayimg = '<img src="'.$displayimg.'" width="100" border="0" alt="">';
																}else
																		$displayimg = '';

																//$showsticker = '';
																$showsticker = $sticker;

																//'.$status .' '.$approve.'

																//if(!isset($category_name)) $category_name = "Clip";
																echo '
																<li class="dd-item" data-id="'.$clip_id.'" value="'.$order.'" data="'.$order.'">
																	<div class="dd-handle">
																			<table padding>
																				<tr>
																					<td width="25%"> '.$displayimg.' </td>
																					<td width="75%" style="padding: 5px;">'.DisplayTxt($title, 55).'<br><br>'.$lastupdate_date.'<br>view '.$countview.'  '.$showsticker.'</td>
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
		$disable = '';
		//Debug($json_highlight);
		//Debug($json_highlight['body']['item']);
		//$default_order = (isset($json_highlight['body']['item'])) ? count($json_highlight['body']['item']) : 4;
?>
					<!-- <div style="clear: both;"></div>
							<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> Display </label>
										<div class="col-sm-9">
											<?php //echo $list_order = selectListOrder( $opt, 'number', 'list_order', 'class="input-small" '.$disable, 'value', 'text', $default_order); ?>
										</div>
							</div>
					</div> -->

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
					//Debug($clipded_list);
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
				bootbox.confirm("<?php echo $language['are you sure to delete']?> <br><b>clipded id " + id + "</b> <span class='green'>" + title + "</span>", function(result) {
					if(result) {
							//alert('<?php echo base_url('order/delete_highlight')?>/' + id);
							title = title.replace("!", ""); 
							title = title.replace("?", ""); 
							title = title.replace("&", ""); 
							title = title.replace(".", ""); 
							window.location='<?php echo base_url('order/delete_clipded')?>/' + id + '/' + encodeURI(title);
						}
				});
		});

		$('#gen_json').on('click', function(e){
				//var list_order = $('#list_order').val();
<?php
				//$('#gen_data').html('<iframe frameborder="0"scrolling="Yes" src="http://daraapi.siamsport.co.th/api/main.php?method=NewsHilight&amp;key=mMs3dAkM&amp;lang=th&amp;gen_file=1" width="100%" height=100px></iframe>');
?>
				//alert(list_order);				
				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				//gen_clipded(list_order);
				Gen_www();
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
					url: "<?php echo base_url('order/update_clipded')?>",
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

function gen_clipded(list_order){
			
			//alert('xxx' + list_order);

			//Gen Home Page
			/*$.getJSON( "<?php echo base_url('json/gen_clipded') ?>?number=" + list_order, function( rsponse ) {
					//alert(rsponse);
					if(rsponse.header.resultcode == 200){
							AlertSuccess	('Generate highlight success.');
							WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
							//Gen_www();
					}else{
							//AlertError('Can not generate <?php echo $this->config->config['www']; ?>');
					}
			});	*/

}
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