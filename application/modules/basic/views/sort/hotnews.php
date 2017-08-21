<?php 
		$language = $this->lang->language;
		$opt = array();
		$tmp = 0;
		echo css_asset('font-awesome2.css');
		$iconpin = '<i class="icon-pushpin red"> %d </i>';
//Debug($pin_list);
//Debug($news_list);
?>
<style type="text/css">
#alertorder{display:none;}	
#nestable-output{display:none;}	
.listorder{width: 90px;height:30px;}
</style>
<?php

if($news_list[0]['order_by'] == 0){ 
?>
<script type="text/javascript">
	window.location.reload;
</script>
<?php
}
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
										<?php echo $news_list[0]['category_name']; ?>
								</small>
						</h1>
				</div>

				<div class="col-xs-12">
<?php
				if(function_exists('Debug')){
					//Debug($news_list);
					//Debug($pin_list);
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
	/*if(isset($pin_list)){
		for($i=0;$i<count($pin_list);$i++){
					$pin[$pin_list[$i]->pin]['news_id2'] = $pin_list[$i]->news_id2;
					$pin[$pin_list[$i]->pin]['title'] = $pin_list[$i]->title;
					$pin[$pin_list[$i]->pin]['pin_start_date'] = $pin_list[$i]->pin_start_date;
					$pin[$pin_list[$i]->pin]['pin_expire_date'] = $pin_list[$i]->pin_expire_date;
		}
	}*/

//Debug($pin_list);
//Debug($news_list);
//echo "cookie('order_hotnews') = ".$this->input->cookie('order_hotnews');

	$count_pin = count($pin_list);

	$attributes = array('class' => 'form-horizontal', 'name' => 'hotnewsform');
	echo form_open_multipart('order/hotnews', $attributes);
	echo $emptyarr = '<pre id = "nestable-output"></pre>';

	$pathimg = '/uploads/thumb/';
	$showdebug = 1;
	$page_reload = 0;
?>
						<div class="row">
								<div class="col-sm-7">
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
															//$opt[]	= makeOption_pin($number, $number, $pin_list);
														}
														//Debug($opt);
														
														for($i=0;$i<$maxorder;$i++){

															//Debug($news_list[$i]);

															$news_id = $news_list[$i]['news_id2'];
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

															/**************PIN*******************/
															//Debug($order);
															//Debug($pin_list);
															$showsticker = $disable = '';
															//if(isset($pin_list)){
															if($count_pin > 0){
																	for($p=0;$p<$count_pin;$p++){
																			if($pin_list[$p]->pin == $order){
																			//if(($pin_list[$p]->pin == $order) && ($pin_list[$p]->title == $title)){
																					
																					$disable = 'disabled';
																					//echo "<br>(".$pin_list[$p]->news_id2." == $news_id)<br>";

																					if($pin_list[$p]->news_id2 == $news_id){
																						//Debug("(".$pin_list[$p]->title." == $title)");
																						$showsticker = $sticker;
																						//$disable = 'disabled';
																					}else{
																						$page_reload = 1 ;
																						//Debug("page_reload = ".$page_reload);
																					}
																			}

																			//Debug("(".$pin_list[$p]->news_id2." == $news_id)");
																			if($news_id == $pin_list[$p]->news_id2){
																					
																					if($pin_list[$p]->pin != $order){
																						//echo "$catid $news_id $order";
																						//echo "set_plusorder(".$pin_list[$p]->pin.", $order, $catid)<br>";
																						//echo "set_order(0, ".$pin_list[$p]->pin.", $catid, $news_id)";

																						$this->news_model->set_plusorder(intval($pin_list[$p]->pin), $order, $catid, 1);
																						$this->news_model->set_order(0, intval($pin_list[$p]->pin), $catid, $news_id, 1);
																					}
																			}
																	}															
															}

															//$a = $i + 1;
															//Debug("(".$a." != $order)");
															//Debug("disable = ".$disable);

															if(($tmp+1) != $order){
																	$order = $tmp+1;
															}

															if($disable == 'disabled'){
																$list_order = '
																<select name="listorder" class="input-small" disabled>
																	<option value="'.$order.'" selected>'.$order.'</option>
																</select>';
															}else
																$list_order = selectListOrder( $opt, 'news'.$news_id.'[]', 'list_order'.$news_id, 'class="input-small list_order" data-id="'.$news_id.'" data-number="'.$order.'" '.$disable, 'value', 'text', $order, $pin_list);

															//$input_order = '<input class="input-small center" name="order[]" id="order_by" placeholder="Not null" value="'.$order.'" type="text">';
															$newsid_hidden = '<input name="news_id[]" placeholder="Not null" value="'.$news_id.'" type="hidden">';

															echo '
															<li class="dd-item" data-id="'.$news_id.'" value="'.$order.'" data="'.$order.'">
																<div class="dd-handle">
																		<table padding>
																			<tr>'
																				.'<td width="210" height="90" >'.$list_order.' '.$newsid_hidden.' '.$displayimg.'</td>
																				<td style="padding: 5px;width: 62%;"> '.$title.'<br>'.$language['lastupdate'].' '.$lastupdate_date.'<br>
																				ID = '.$news_id.' <b>View</b> = '.$countview.' '.$showsticker.' Order  = '.$order.'
																				</td>
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
								<div class="col-sm-1">
								</div>
								<div class="col-sm-4">
								<?php
								//Debug($pin_list);
								if(isset($pin_list)){
										for($i=0;$i<count($pin_list);$i++){
													
													$lastupdate_date = $pin_list[$i]->lastupdate_date;
													$countview = $pin_list[$i]->countview;

													echo "<i class='icon-pushpin red bigger-110'> ".$pin_list[$i]->pin." </i> <br>";
													echo "<b>ID</b> : ".$pin_list[$i]->news_id2;
													echo "<br><b>News</b> : ".$pin_list[$i]->title;
													echo "<br><b>Date</b> : <br>";
													echo RenDateTime($pin_list[$i]->pin_start_date)." - <br>".RenDateTime($pin_list[$i]->pin_expire_date)."<hr>";
										}
								}
								?>
								</div>
								<?php //echo "page_reload = $page_reload"; ?>
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
<?php
//Debug($pin_list);
//Debug($news_list);
?>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->
<?php 
if($page_reload == 1){
	echo '
	<script type="text/javascript">
		window.location = "'.base_url('order/'.$this->uri->segment(2)).'";
	</script>';
}
?>
<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );

		document.title = '<?php echo $webtitle ?>';
<?php 

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
					UpdateNewsOrder(id, v);
		});

<?php
		/*if($news_list)
				for($i=0;$i<count($news_list);$i++){
						$news_id = $news_list[$i]['news_id2'];
?>
		$('#list_order<?php echo $news_id?>').change(function( ) {
					var v = $(this).val();
					//alert(v);
<?php
			if(isset($pin_list)){
					for($j=0;$j<count($pin_list);$j++){								
								echo ' if(v == '.$pin_list[$j]->pin.'){ alert("this item is fix."); return false; } ';
					}
			}	
?>			//document.hotnewsform.submit();

				UpdateNewsOrder(<?php echo $news_id?>, v);
		}); 

<?php
		}*/
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

function UpdateNewsOrder(newsid, val){
				//alert(newsid+ ' ' + val);
				$.ajax({
						type: 'POST',
						url: "<?php echo base_url() ?>order/hotnews_update/",
						data : { newsid : newsid, val : val, catid : 1, pin_list : <?php echo json_encode($pin_list) ?>},
						cache: false,
						success: function(data){
								//alert(data);
								window.location='<?php echo base_url("order/hotnews") ?>';
								/*if(data = 'Yes'){
										$('#picture_use').attr('style', 'display:none');
										$('#upload_avatar').attr('style', 'display:block');
								}*/
						}
				});

}

function UpdateOrder(json){
		
			alert(json);
			$.ajax({
					type: 'POST',
					url: "<?php echo base_url('order/update_news')?>",
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

