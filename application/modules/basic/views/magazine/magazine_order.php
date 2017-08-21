<?php 
	$language = $this->lang->language; 
	$i=0;
	//$maxcat = count($dara_type);
?>
<style type="text/css">
<?php 
	if($this->session->userdata('admin_id') != 1){
?>
#alertorder{display:none;}	
#nestable-output{display:none;}	
<?php 
	}
?>
.tags{width: 250px;}
ol.dd-list li .dd-handle img{width: 120px;}
</style>

<?php
	$msg = 'Init and Update';
?>
<div class="row">
	<div class="col-sm-12">
			<div class="alert alert-success" id="alertorder">
				<button data-dismiss="alert" class="close" type="button">
					<i class="ace-icon fa fa-times"></i>
				</button>
				<strong>
					<i class="ace-icon fa fa-check"></i>
				</strong><span id="msg"><?php echo $msg?></span><br>
			</div>
	</div>
<?php 
//$relate_list
echo $emptyarr = '<pre id = "nestable-output"></pre>';
//Debug($magazine_list); 
$main = $picture_list[0];
?>
	<div class="col-sm-12">
		<div class="col-sm-8">
					<div class="widget-box">

								<div class="widget-header widget-header-flat">
									<h4 class="widget-title"><?php echo $language['order']?> (<?php echo $language['drag and drop']?>)</h4>
								</div>

								<div class="widget-body">
									<div class="widget-main">
										<div class="row">

											<div class="col-sm-2">
													<div class="" style="width:100%;height:1px;margin: 1px 0px 2px 0px;" ></div>
											<?php
											if($picture_list)
													for($i=1;$i<=count($picture_list);$i++){
															//if($i==1) echo '<div class="alert alert-info" style="width:100%;height:86px;margin: 1px 0px 5px 0px;" >'.$i.'</div>';
															//else  echo '<div class="alert alert-info" style="width:100%;height:100px;margin: 1px 0px 5px 0px;" >'.$i.'</div>';
															echo '<div class="alert alert-info" style="width:100%;height:86px;margin: 1px 0px 5px 0px;" >'.$i.'</div>';
													}
											?>								
											</div>
											<div class="col-sm-10">
			<span class=""><?php if($main['title'] != '') echo $caption = $main['title'];?></span>

			<div class="dd" id="nestable">
			<ol class="dd-list">
			<!-- <ul class="ace-thumbnails clearfix"> -->
<?php
			//Debug($picture_list);			
			$allmagazine = count($picture_list);
			if($picture_list)
					for($i=0;$i<$allmagazine;$i++){
								$num =$i + 1;
								$picture_id = $picture_list[$i]['picture_id'];
								//$file_name = $picture_list[$i]['file_name'];
								$caption = $picture_list[$i]['caption'];
								$folder = $picture_list[$i]['folder'];
								$order = $picture_list[$i]['order'];
								$status = ($picture_list[$i]['status'] == 1) ? '<span class="label label-success arrowed">Enable</span>' : '<span class="label label-danger">Disable</span>';

								$preview_file = 'uploads/magazine/'.$folder.'/'.$picture_list[$i]['file_name'];
								
								//$file_name = ($picture_list[$i]['file_name'] == '') ? 'uploads/magazine/'.$folder.'/'.$picture_list[$i]['file_name'] : 'uploads/thumb/'.$folder.'/'.$picture_list[$i]['file_name'];
								if($picture_list[$i]['file_name'] != ''){

									$file_name = 'uploads/thumb300/'.$folder.'/'.$picture_list[$i]['file_name'];
									
									if(!file_exists($file_name)){
											$file_name = 'uploads/magazine/'.$folder.'/'.$picture_list[$i]['file_name'];
											if(!file_exists($file_name)) $file_name = _IMG_NOTFOUND;
									}

								}else $file_name = _IMG_NOTFOUND;
								
								$pic_del = base_url('magazine/picture_del/'.$picture_id)."?".$this->uri->segment(3);
								
								$attr_width = 'width="150"';

								//$edit_picture = base_url('magazine/picture_edit/'.$picture_id.'?'.$this->uri->segment(3));
								$edit_picture = base_url('magazine/picture_edit/'.$picture_id.'?magazine_id='.$this->uri->segment(3));
								
?>
										<li <?php echo 'class="dd-item" data-id="'.$picture_id.'" value="'.$order.'" data="'.$order.'"' ?>>
											<div class="dd-handle">
													<img src="<?php echo base_url($file_name) ?>" <?php echo $attr_width?>/> <?php echo $caption ?>
													<!-- <div class="tags">
															<span class="label-holder">
																<span class="label label-danger"><?php echo $order?></span>
															</span>
															<span class="label-holder">
																<span class="label label-warning arrowed-in"><?php //echo $order ?></span>
															</span>
													</div> -->
											</div>
										</li>
<?php
					
					}
?>
			</ol>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		</div>
</div>

<script type="text/javascript">
/*$( document ).ready(function() {
		console.log( "ready!" );
});*/
</script>

<script type="text/javascript">
jQuery(function($) {
		
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

});

function UpdateOrder(json){
		
			$.ajax({
					type: 'POST',
					url: "<?php echo base_url('magazine/set_order_picture')?>",
					data: {json: json, magazineid : <?php echo $this->uri->segment(3)?>},
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

<?php echo js_asset('jquery.nestable.js'); ?>