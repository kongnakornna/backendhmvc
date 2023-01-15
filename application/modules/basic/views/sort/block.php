<?php 
	$language = $this->lang->language;
	$opt = array();
	$tmp = 0;
	$gen_url = base_url('json/gen_block');
	
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
	echo form_open_multipart('news/save_block', $attributes);
?>
			<div class="page-header">
					<h1>
							<?php echo $language['order'] ?>
							<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<?php echo $language['block'] ?>
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
//Debug($highlight_list); 
$pathimg = '/uploads/thumb/';
?>
						<div class="row">

								<div class="col-sm-2">
										<div class="" style="width:100%;height:5px;"></div>

<?php
								if(isset($block_list)){

										$maxorder = count($block_list);
										for($i=0;$i<$maxorder;$i++){

												$block_id = $block_list[$i]->block_id;
												$title = $block_list[$i]->title;
												$order = $block_list[$i]->order;
												$number = $i+1;

												//if($i == 0) $relate_box .= $newsrelate_id;
												//else $relate_box .= ", ".$newsrelate_id;

												/*$icondel = '<a class="red del-confirm" href="javascript:void(0);" id="del-relate'.$newsrelate_id.'" data-value="'.$newsrelate_id.'" data-name="'.$title.'"><i class="ace-icon fa fa-trash-o bigger-130" data-rel="tooltip" title="'.$language['delete'].'"></i></a>';

												$relatenewsid = '<input type="hidden" name="relate_id[]" value="'.$newsrelate_id.'" class="col-xs-12" style="text-align: center;">';
												$textbox = '<input type="text" name="orderid[]" value="'.$order.'" class="col-xs-8" style="text-align: center;">';*/

												echo '<div class="alert alert-info" style="width:100%;height:71px;margin: 1px 0px 5px 0px;" >'.$number.'</div>';
										}
								}
?>								
								</div>

								<div class="col-sm-6">
										<div class="dd" id="nestable">
											<ol class="dd-list">
											<?php
												$sticker = '
														<span class="sticker">
															<span class="label label-success arrowed-in">
																<i class="ace-icon fa fa-check bigger-110"></i>
															</span>
														</span>';

												$showsticker = '';

												if(isset($block_list))

														//Debug($block_list);
														$maxorder = count($block_list);

														/*for($l=0;$l<$maxorder;$l++){
															$number = $l+1;
															$opt[]	= makeOption($number, $number);
														}*/
														
														for($i=0;$i<$maxorder;$i++){

															$block_id = $block_list[$i]->block_id;
															$title = $block_list[$i]->title;
															$order = $block_list[$i]->order;
															$number = $i+1;

															echo '
															<li class="dd-item" data-id="'.$block_id.'" value="'.$order.'" data="'.$order.'">
																<div class="dd-handle" style="height:71px;">
																		<table >
																			<tr>
																				<td></td>
																				<td style="padding: 5px;">'.$title.'<br>'.$showsticker.'</td>
																			</tr>
																		</table>
																</div>
															</li>';

															}
											?>
											</ol>
										</div>

								</div>
						</div>


				<div style="clear: both;"></div>
						<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
											&nbsp; &nbsp; &nbsp;

											<button type="button" class="btn btn-success" id="gen_json">
												<i class="ace-icon glyphicon glyphicon-file bigger-110"></i> <?php echo $language['generate_catch']?>
											</button>
									</div>
						</div>
				</div>

				<div class="col-sm-12">
							<div id="gen_data"></div>
				</div>

			</div>

							</div>
						<?php echo form_close();?>
						</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );
		$('#gen_json').on('click', function(e){

				WaitingAlert('<?php echo $language['waiting_for_generate']; ?>');
				//$('#gen_data').html('<iframe frameborder="0"scrolling="Yes" src="<?php echo $this->config->config['api']; ?>/api/web-api.php?method=Block&amp;key=<?php echo $this->config->config['api_key']; ?>&amp;gen_file=1" width="100%" height=100px></iframe>');
				//AlertSuccess	('Generate block success.');
				//$('#gen_data').hide();
				Get_API();

		});
<?php 
		//http://daraapi.siamsport.co.th/api/web-api.php?method=Block&key=mMs3dAkM&gen_file=1
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

		/*f($block_list)
				for($i=0;$i<count($block_list);$i++){	
?>

<?php
				}*/
?>

		//chosen plugin inside a modal will have a zero width because the select element is originally hidden
		//and its width cannot be determined.
		//so we set the width after modal is show
		$('#modal-form').on('shown.bs.modal', function () {
					$(this).find('.chosen-container').each(function(){
						$(this).find('a:first-child').css('width' , '210px');
						$(this).find('.chosen-drop').css('width' , '210px');
						$(this).find('.chosen-search input').css('width' , '200px');
					});
		})

<?php
		//$datainput = $this->input->get();			
		//if(isset($datainput['auto'])){
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
<?php //} ?>

});

function UpdateOrder(json){
		
		//alert(json);
						$.ajax({
								type: 'POST',
								url: "<?php echo base_url('order/update_block')?>",
								data: {json: json},
								cache: false,
								success: function(data){
										$("#alertorder").fadeIn();
										$("#msg").html(data);
										//alert(data);
								}
						});

			setTimeout(function(){
					$("#alertorder").fadeOut("slow", function () {});
			}, 3000);

}

function Get_API(){

			$.getJSON( "<?php echo $this->config->config['api']; ?>/api/web-api.php?method=Block&key=<?php echo $this->config->config['api_key']; ?>&gen_file=1", function( rsponse ) {
					if(rsponse.header.resultcode == 200){
							//AlertSuccess	('Generate block success.');
							Gen_www();
							//$('#preview_data').append('<div class="col-xs-12"><?php echo $language['generate_catch']; ?> <small><i class="ace-icon fa fa-angle-double-right"></i> <b><?php echo $this->config->config['www']; ?></b> success.</small></div>');
					}else{
							AlertError('Can not generate Block <?php echo $this->config->config['www']; ?>');
							//$('#preview_data').append('<div class="col-xs-12">Can not generate <?php echo $this->config->config['www']; ?></div>');
					}
			});	
}


function Gen_www(){

			//Gen Home Page
			$.getJSON( "<?php echo $home ?>", function( rsponse ) {
					if(rsponse.meta.code == 200){
							AlertSuccess	('Generate block and build <?php echo $this->config->config['www']; ?> success.');
							//$('#preview_data').append('<div class="col-xs-12"><?php echo $language['generate_catch']; ?> <small><i class="ace-icon fa fa-angle-double-right"></i> <b><?php echo $this->config->config['www']; ?></b> success.</small></div>');
					}else{
							AlertError('Can not generate <?php echo $this->config->config['www']; ?>');
							//$('#preview_data').append('<div class="col-xs-12">Can not generate <?php echo $this->config->config['www']; ?></div>');
					}
			});	

}
</script>