<?php 
		$language = $this->lang->language;
		$opt = array();
		$tmp = 0;
		echo css_asset('font-awesome2.css');
		$iconpin = '<i class="icon-pushpin red"> %d </i>';
?>
<style type="text/css">
#alertorder{display:none;}	
#nestable-output{display:none;}	
</style>

<div class="row">
	<div class="col-xs-12">
		<div class="row">

									<div class="page-header">
										<h1>
											<?php echo $language['order'] ?>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['gallery'] ?>
												<?php //echo $gallery_list[0]['category_name']; ?>
											</small>
										</h1>
									</div>

									<div class="col-xs-12">
<?php
				if(function_exists('Debug')){
					//Debug($gallery_list);
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
	
	$attributes = array('class' => 'form-horizontal', 'name' => 'hotnewsform');
	echo form_open_multipart('order/gallery', $attributes);

//$relate_list
echo $emptyarr = '<pre id = "nestable-output"></pre>';
//Debug($pin_list); 
//Debug($gallery_list); 
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

												if(isset($gallery_list))
														
														$maxorder = count($gallery_list);

														for($l=0;$l<$maxorder;$l++){
															$number = $l+1;
															$opt[]	= makeOption($number, $number);
														}
														
														for($i=0;$i<$maxorder;$i++){

															$gallery_id = $gallery_list[$i]['gallery_id2'];
															$order = $gallery_list[$i]['order_by'];
															$title = $gallery_list[$i]['title'];
															$category_name = $gallery_list[$i]['gallery_type_name'];

															$create_date = RenDateTime($gallery_list[$i]['create_date']);
															$lastupdate_date = RenDateTime($gallery_list[$i]['lastupdate_date']);
															$countview = $gallery_list[$i]['countview'];

															if($gallery_list[$i]['file_name'] != ''){
																	$folder = $gallery_list[$i]['folder'];
																	$displayimg = base_url($pathimg.$folder.'/'.$gallery_list[$i]['file_name']);
																	$displayimg = '<img src="'.$displayimg.'" width="100" border="0" alt="">';
															}else
																	$displayimg = '<img src="'.base_url('images/no_img.jpg').'" width="100" border="0" alt="">';
																	//$displayimg = '';

															$showsticker = $disable = '';
															/*if(isset($pin_list)){
																	for($p=0;$p<count($pin_list);$p++){
																			if($pin_list[$p]->pin == $order){
																				$showsticker = $sticker;
																				$disable = 'disabled';
																			}
																	}															
															}*/

															if(($tmp+1) != $order){
																$order = $tmp+1;
																//$this->gallery_model->set_order(0, intval($order), 1,$gallery_id);
															}

															$list_order = selectListOrder( $opt, 'gallery'.$gallery_id.'[]', 'list_order'.$gallery_id, 'class="input-small" '.$disable, 'value', 'text', $order);
															//$input_order = '<input class="input-small center" name="order[]" id="order_by" placeholder="Not null" value="'.$order.'" type="text">';
															$galleryid_hidden = '<input name="news_id[]" placeholder="Not null" value="'.$gallery_id.'" type="hidden">';

															echo '
															<li class="dd-item" data-id="'.$gallery_id.'" value="'.$order.'" data="'.$order.'">
																<div class="dd-handle">
																		<table padding>
																			<tr>
																				<td width="210" height="90" >'.$list_order.' '.$galleryid_hidden.' '.$displayimg.'</td>
																				<td style="padding: 5px;width: 62%;"> id '.$gallery_id.'. '.$title.'<br>'.$language['lastupdate'].' '.$lastupdate_date.'<br>
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
								/*if(isset($pin_list)){
										for($i=0;$i<count($pin_list);$i++){
													
													$lastupdate_date = $pin_list[$i]->lastupdate_date;
													$countview = $pin_list[$i]->countview;

													echo "<i class='icon-pushpin red bigger-110'> ".$pin_list[$i]->pin." </i> <br>";
													echo "News : ".$pin_list[$i]->title;
													echo "<br>Date : ";
													echo $pin_list[$i]->pin_start_date." - ".$pin_list[$i]->pin_expire_date."<hr>";
										}
								}*/
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

<?php

		if($gallery_list)
				for($i=0;$i<count($gallery_list);$i++){
						$gallery_id = $gallery_list[$i]['gallery_id2'];
?>
		$('#list_order<?php echo $gallery_id?>').change(function( ) {
					var v = $(this).val();
					//alert(v);
<?php
			/*if(isset($pin_list)){
					for($j=0;$j<count($pin_list);$j++){								
								echo ' if(v == '.$pin_list[$j]->pin.'){ alert("this item is fix."); return false; } ';
					}
			}*/
?>			//document.hotgalleryform.submit();

				UpdategalleryOrder(<?php echo $gallery_id?>, v);
		}); 

<?php
				}
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
					//UpdateOrder(window.JSON.stringify(list.nestable('serialize')));
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

function UpdategalleryOrder(galleryid, val){

				//alert(galleryid + ' ' + val);
				$.ajax({
						type: 'POST',
						url: "<?php echo base_url() ?>order/gallery_update/",
						data : { galleryid : galleryid, val : val},
						cache: false,
						success: function(data){
								//alert(data);
								window.location='<?php echo base_url("order/gallery") ?>';
								//if(data = 'Yes'){
								//		$('#picture_use').attr('style', 'display:none');
								//		$('#upload_avatar').attr('style', 'display:block');
								//}
						}
				});

}

/*function UpdateOrder(json){
		//alert(json);
			$.ajax({
					type: 'POST',
					url: "<?php echo base_url('order/update_gallery')?>",
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

}*/
</script>

