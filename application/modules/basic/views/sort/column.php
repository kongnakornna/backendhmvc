<?php 
		$language = $this->lang->language;
		$opt = array();
		$tmp = 0;
		echo css_asset('font-awesome2.css');
		$iconpin = '<i class="icon-pushpin red"> %d </i>';

		$redirect = '';
		//$curpage = $_SERVER["SERVER_NAME"].$_SERVER["REDIRECT_URL"];
		$curpage = $_SERVER["REDIRECT_URL"];
?>
<style type="text/css">
#alertorder{display:none;}	
#nestable-output{display:none;}	
</style>
<?php
	if(function_exists('Debug')){
			//Debug($news_list);
			//Debug($pin_list);
	}
?>
<?php
//if(isset($news_list))
/*	if($news_list[0]['order_by'] == 0){ 
?>
<script type="text/javascript">
	//window.location.reload;
	//window.location.href ='<?php echo $curpage ?>';
</script>
<?php
	}*/
?>
<div class="row">
	<div class="col-xs-12">
		<div class="row">

					<div class="page-header">
							<h1>
									<?php echo $language['order'] ?>
									<small>
										<i class="ace-icon fa fa-angle-double-right"></i>
										<?php //echo $language['news'] ?>
										<?php if(isset($news_list[0]['category_name'])) echo $news_list[0]['category_name']; ?>
									</small>
							</h1>
					</div>

					<div class="col-xs-12">
<?php
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
	/*if(isset($pin_list)){
		for($i=0;$i<count($pin_list);$i++){
					$pin[$pin_list[$i]->pin]['news_id2'] = $pin_list[$i]->news_id2;
					$pin[$pin_list[$i]->pin]['title'] = $pin_list[$i]->title;
					$pin[$pin_list[$i]->pin]['pin_start_date'] = $pin_list[$i]->pin_start_date;
					$pin[$pin_list[$i]->pin]['pin_expire_date'] = $pin_list[$i]->pin_expire_date;
		}
	}*/
	
	$attributes = array('class' => 'form-horizontal', 'name' => 'hotnewsform');
	echo form_open_multipart('order/hotnews', $attributes);

//$relate_list
echo $emptyarr = '<pre id = "nestable-output"></pre>';
//Debug($pin_list); 
//Debug($news_list); 

$pathimg = '/uploads/thumb/';
?>
						<div class="row">
								<div class="col-sm-8">
										<div class="dd" id="nestable">
											<ol class="dd-list">
											<?php
												
												//if($pin > 0) echo sprintf($iconpin, $pin)
												$sticker = '
														<span class="sticker">
															<span class="label label-warning arrowed-in">
																<i class="icon-pushpin red bigger-110"></i>
															</span>
														</span>';

												if(isset($news_list))
														
														$maxorder = count($news_list);

														for($l=0;$l<$maxorder;$l++){
															$number = $l+1;
															$opt[]	= makeOption($number, $number);
														}
														
														for($i=0;$i<$maxorder;$i++){

															$column_id = $news_list[$i]['column_id2'];
															$order = $news_list[$i]['order_by'];
															$title = $news_list[$i]['title'];
															$category_name = $news_list[$i]['category_name'];

															$create_date = RenDateTime($news_list[$i]['create_date']);
															$lastupdate_date = RenDateTime($news_list[$i]['lastupdate_date']);
															$countview = $news_list[$i]['countview'];

															if($news_list[$i]['file_name'] != ''){
																	$folder = $news_list[$i]['folder'];
																	$displayimg = base_url($pathimg.$folder.'/'.$news_list[$i]['file_name']);
																	$displayimg = '<img src="'.$displayimg.'" width="100" border="0" alt="">';
															}else
																	$displayimg = '<img src="'.base_url('images/no_img.jpg').'" width="100" border="0" alt="">';
																	//$displayimg = '';

															$showsticker = $disable = '';
															if(isset($pin_list)){
																	for($p=0;$p<count($pin_list);$p++){
																			if($pin_list[$p]->pin == $order){
																				$showsticker = $sticker;
																				$disable = 'disabled';
																			}
																	}															
															}

															if(($tmp+1) != $order){
																	$order = $tmp+1;
																	$this->column_model->set_order(0, intval($order), $catid,$column_id);
																	$redirect = $_SERVER["SERVER_NAME"].$_SERVER["REDIRECT_URL"];
															}

															$list_order = selectListOrder( $opt, 'news'.$column_id.'[]', 'list_order'.$column_id, 'class="input-small list_order" data-id="'.$column_id.'" data-number="'.$order.'" '.$disable, 'value', 'text', $order);
															//$input_order = '<input class="input-small center" name="order[]" id="order_by" placeholder="Not null" value="'.$order.'" type="text">';
															$newsid_hidden = '<input name="news_id[]" placeholder="Not null" value="'.$column_id.'" type="hidden">';

															echo '
															<li class="dd-item" data-id="'.$column_id.'" value="'.$order.'" data="'.$order.'">
																<div class="dd-handle">
																		<table padding>
																			<tr>
																				<td width="210" height="90" >'.$list_order.' '.$newsid_hidden.' '.$displayimg.'</td>
																				<td style="padding: 5px;width: 62%;"> '.$title.'<br>'.$language['lastupdate'].' '.$lastupdate_date.'<br>
																				view '.$countview.' '.$showsticker.'</td>
																			</tr>
																		</table>
																</div>
															</li>';

															if($tmp == 0 || $tmp != $order) $tmp = $order;
														}
											?>
											</ol>
										</div>

								</div>
								<div class="col-sm-2">
								</div>
								<div class="col-sm-4">
								<?php
								//Debug($pin_list);
								if(isset($pin_list)){
										for($i=0;$i<count($pin_list);$i++){
													
													$lastupdate_date = $pin_list[$i]->lastupdate_date;
													$countview = $pin_list[$i]->countview;

													echo "<i class='icon-pushpin red bigger-110'> ".$pin_list[$i]->pin." </i> <br>";
													echo "News : ".$pin_list[$i]->title;
													echo "<br>Date : ";
													echo $pin_list[$i]->pin_start_date." - ".$pin_list[$i]->pin_expire_date."<hr>";
										}
								}
								?>
								</div>

						</div>

						<!-- <button type="button" class="btn btn-info" id="Update"><i class="ace-icon fa fa-check bigger-110"></i>Update Order</button> -->
									<!-- <div style="clear: both;"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn btn-info">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
											&nbsp; &nbsp; &nbsp;
											<button type="reset" class="btn">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div> -->

							</div>
						<?php echo form_close();?>
						</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );

<?php
		//if($redirect != '') echo "window.location='$redirect';";
		//if($picture_list){ echo "$('#upload_avatar').attr('style', 'display:none');\n";	} 
?>
		$('#bootbox-confirm').click(function( ) {
				var v = $(this).attr('data-value');
				var img = $(this).attr('data-img');

				//alert(v);
				$.ajax({
						type: 'POST',
						url: "<?php echo base_url() ?>picture/remove_img/" + v,
						data : { img : img, v : v},
						cache: false,
						success: function(data){
								//alert(data);
								if(data = 'Yes'){
										$('#picture_use').attr('style', 'display:none');
										$('#upload_avatar').attr('style', 'display:block');
								}
						}
				});
		}); 

		$('.list_order').change(function( ) {
					var id = $(this).attr('data-id');
					var number = $(this).attr('data-number');
					var v = $(this).val();
					//alert(id + ' ' + number + ' ==> ' + v);
					UpdateChangeOrder(id, number, v);
		});

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

function UpdateChangeOrder(column_id, old, val){

				$.ajax({
						type: 'POST',
						url: "<?php echo base_url() ?>order/column_update",
						data : { column_id : column_id, val : val,  old : old, catid : <?php echo $catid?>},
						//data : { column_id : column_id, val : val, catid : <?php echo $catid?>, pin_list : <?php //echo json_encode($pin_list) ?>},
						cache: false,
						success: function(data){
								//alert(data);
								window.location='<?php echo base_url($curpage) ?>';
						}
				});

}

function UpdateOrder(json){
		
		//alert(json);
			$.ajax({
					type: 'POST',
					url: "<?php echo base_url('order/update_column')?>",
					data: {json: json},
					cache: false,
					success: function(data){
							$("#alertorder").fadeIn();
							$("#msg").html(data);
					}
			});

			setTimeout(function(){
					$("#alertorder").fadeOut("slow", function () {});
			}, 3000);

}
</script>

