<?php
	$language = $this->lang->language; 
?>
<style type="text/css">
/*textarea.form-control{height:200px;}*/
<?php

	if(trim($ads_arr['header']) != ""){
			echo "textarea#header{height:300px;}";
	}
	if(trim($ads_arr['leader_board_big']) != ""){
			echo "textarea#leader_board{height:300px;}";
	}
	if(trim($ads_arr['leader_board_mediem']) != ""){
			echo "textarea#leader_board{height:300px;}";
	}
	if(trim($ads_arr['leader_board_small']) != ""){
			echo "textarea#leader_board{height:300px;}";
	}
	if(trim($ads_arr['cover']) != ""){
			echo "textarea#cover{height:300px;}";
	}
	if(trim($ads_arr['skin1']) != ""){
			echo "textarea#skin1{height:150px;}";
	}
	if(trim($ads_arr['skin2']) != ""){
			echo "textarea#skin2{height:150px;}";
	}
	if(trim($ads_arr['skin3']) != ""){
			echo "textarea#skin3{height:150px;}";
	}
	if(trim($ads_arr['ads_1']) != ""){
			echo "textarea#ads_1{height:300px;}";
	}
	if(trim($ads_arr['ads_2']) != ""){
			echo "textarea#ads_2{height:300px;}";
	}
	if(trim($ads_arr['ads_3']) != ""){
			echo "textarea#ads_3{height:300px;}";
	}
	if(trim($ads_arr['ads_4']) != ""){
			echo "textarea#ads_4{height:300px;}";
	}
	if(trim($ads_arr['ads_5']) != ""){
			echo "textarea#ads_5{height:300px;}";
	}
	if(trim($ads_arr['footer']) != ""){
			echo "textarea#footer{height:300px;}";
	}
	/**********************mobile******************/
	if(trim($ads_arr['m_cover']) != ""){
			echo "textarea#m_cover{height:300px;}";
	}
	/*if(trim($ads_arr['m_head']) != ""){
			echo "textarea#m_head{height:300px;}";
	}

	if(trim($ads_arr['m_leader_board']) != ""){
			echo "textarea#m_leader_board{height:300px;}";
	}
	if(trim($ads_arr['m_ads_1']) != ""){
			echo "textarea#m_ads_1{height:300px;}";
	}
	if(trim($ads_arr['m_ads_2']) != ""){
			echo "textarea#m_ads_2{height:300px;}";
	}
	if(trim($ads_arr['m_ads_3']) != ""){
			echo "textarea#m_ads_3{height:300px;}";
	}
	if(trim($ads_arr['m_ads_4']) != ""){
			echo "textarea#m_ads_4{height:300px;}";
	}
	if(trim($ads_arr['m_ads_5']) != ""){
			echo "textarea#m_ads_5{height:300px;}";
	}
	if(trim($ads_arr['m_footer']) != ""){
			echo "textarea#m_footer{height:300px;}";
	}*/

?>
</style>
<?php
	//Debug($ads_arr);
	if($ads_arr['category_id'] == 0 && $ads_arr['subcategory_id'] == 0){
			$ads_title = "Home";
	}else if($ads_arr['category_id'] == 99 && $ads_arr['subcategory_id'] == 0){
			$ads_title = "Default";
	}else if($ads_arr['category_id'] == 98 && $ads_arr['subcategory_id'] == 0){
			$ads_title = "Default 18+";
	}else
			$ads_title = $ads_arr['category_name']." ".$ads_arr['subcategory_name'];
?>

<div class="row">
		<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
					<div class="row">
					<!-- <form role="form" class="form-horizontal" action="ads/save"> -->
<?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
		echo form_open('ads/save', $attributes);
?>
									<div class="page-header">
										<h1>
											<?php echo $language['ads'] ?>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												Desktop
											</small>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['edit'].' '.$ads_title ?> 
											</small>
										</h1>
									</div>

									<div class="col-xs-12">
										<!-- <h3 class="header smaller lighter blue"><?php echo $language['ads'] ?></h3> -->
										<!-- <div class="table-header">
											<?php echo $language['ads'] ?>
										</div> -->

				<!-- <code><?php //echo $sql; ?></code> -->
<?php
			if(function_exists('Debug')){
				//Debug($ads_arr);
				//Debug($subcategory_list);
				//die();
			}

			if(isset($ads_list)){
					//Debug($ads_list);
					/*foreach($ads_list[0] as $key => $val){
							if($key == 'skin1') $skin1 = $val;
					}*/
			}

			//$ads_arr = $ads_list[0];

			if(isset($error)){
?>
											<div class="alert alert-danger">
												<button data-dismiss="alert" class="close" type="button">
													<i class="ace-icon fa fa-times"></i>
												</button>
												<strong>
													<i class="ace-icon fa fa-times"></i>
													</strong><?php echo $error?>.
												<br>
											</div>
<?php
			}
?>
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
			</div>
<?php
		if($ads_arr['ads_id'] != 1 && $ads_arr['ads_id'] != 2 && $ads_arr['ads_id'] != 3){
?>
			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['category']?></label>
					<div class="col-sm-9">
							<?php echo $category_list?>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['subcategory']?></label>
					<div class="col-sm-9">
							<?php echo $subcategory_list?>							
					</div>
			</div>
<?php
			}
?>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Header</label>
					<div class="col-sm-9">
							<?php
							if(isset($ads_arr['header'])){
								//echo "มี Script Ads แล้ว";
								//echo "<code>".$ads_arr['leader_board']."</code>";
							}
							?>
							<textarea class="form-control" placeholder="Script Header" id="header" name="header"><?php echo $ads_arr['header'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Cover</label>
					<div class="col-sm-9">
							<?php
							if(isset($ads_arr['cover'])){
								//echo "มี Script Ads แล้ว";
								//echo "<code>".$ads_arr['leader_board']."</code>";
							}
							?>
							<textarea class="form-control" placeholder="Script Cover" id="cover" name="cover"><?php echo $ads_arr['cover'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Leader board</label>
					<div class="col-sm-9">
							<?php
							if(isset($ads_arr['leader_board_big'])){
								//echo "มี Script Ads แล้ว";
								//echo "<code>".$ads_arr['leader_board']."</code>";
							}
							?>
							<textarea class="form-control" placeholder="Script Leader board" id="leader_board_big" name="leader_board_big"><?php echo $ads_arr['leader_board_big'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Leader board 2</label>
					<div class="col-sm-9">
							<?php
							if(isset($ads_arr['leader_board_mediem'])){
								//echo "มี Script Ads แล้ว";
								//echo "<code>".$ads_arr['leader_board']."</code>";
							}
							?>
							<textarea class="form-control" placeholder="Script Leader board" id="leader_board_mediem" name="leader_board_mediem"><?php echo $ads_arr['leader_board_mediem'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Leader board 3</label>
					<div class="col-sm-9">
							<?php
							if(isset($ads_arr['leader_board_small'])){
								//echo "มี Script Ads แล้ว";
								//echo "<code>".$ads_arr['leader_board']."</code>";
							}
							?>
							<textarea class="form-control" placeholder="Script Leader board" id="leader_board_small" name="leader_board_small"><?php echo $ads_arr['leader_board_small'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Skin 1</label>
					<div class="col-sm-9">
							<?php
							//Debug($skin1);
							//if(isset($ads_arr['leader_board'])){
							/*if($skin1){
								echo "มี Script Ads แล้ว";
								echo "<code>".$skin1."</code>";
							}*/
							?>
							<textarea class="form-control" placeholder="Script" id="skin1" name="skin1" ><?php echo $ads_arr['skin1'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Skin 2</label>
					<div class="col-sm-9">
							<?php //if(isset($ads_arr['skin2'])) echo "มี Script Ads แล้ว" ?>
							<textarea class="form-control" placeholder="Script" id="skin2" name="skin2"><?php echo $ads_arr['skin2'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Skin 3</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="skin3" name="skin3"><?php echo $ads_arr['skin3'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Ads 1<br>(Rectang 1)</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="ads_1" name="ads_1"><?php echo $ads_arr['ads_1'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Ads 2<br>(Rectang 2)</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="ads_2" name="ads_2"><?php echo $ads_arr['ads_2'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Ads 3<br>(Rectang 3)</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="ads_3" name="ads_3"><?php echo $ads_arr['ads_3'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Ads 4</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="ads_4" name="ads_4"><?php echo $ads_arr['ads_4'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Ads 5<br>(Rectang 4)</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="ads_5" name="ads_5"><?php echo $ads_arr['ads_5'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Footer</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="footer" name="footer"><?php echo $ads_arr['footer'] ?></textarea>
					</div>
			</div>

<!-- Mobile -->
			<div class="page-header">
					<h1>
							<?php echo $language['ads'] ?>
							<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Mobile
							</small>
					</h1>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">M Cover</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script Cover" id="m_cover" name="m_cover"><?php echo $ads_arr['m_cover'] ?></textarea>
					</div>
			</div>

<!-- Mobile -->
			<div class="page-header">
					<h1>
							<?php echo $language['ads'] ?>
							<small>
								<i class="ace-icon fa fa-angle-double-right"></i> Video
							</small>
					</h1>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Pre roll video ads</label>
					<div class="col-sm-9">
							<?php
							if(isset($ads_arr['pre_roll'])){
								//echo "มี Script Ads แล้ว";
								//echo "<code>".$ads_arr['leader_board']."</code>";
							}
							?>
							<textarea class="form-control" placeholder="Script Pre roll" id="pre_roll" name="pre_roll"><?php echo $ads_arr['pre_roll'] ?></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Overlay video ads</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script Overlay" id="overlay" name="overlay"><?php echo $ads_arr['overlay'] ?></textarea>
					</div>
			</div>

			<!-- <div class="form-group">
					<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
					<div class="col-xs-3">
							<label>
								<input name="status" id="ads_status" class="ace ace-switch" type="checkbox" value=1 />
								<span class="lbl"></span>
							</label>
					</div>
			</div> -->
			<input name="status" type="hidden" value=1 />
			<input name="ads_id" type="hidden" value=<?php echo $ads_arr['ads_id'] ?> />

			<div style="clear: both;"></div>
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
			</div>

		</div>
<?php echo form_close();?>

		</div>
	<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->

</div><!-- /.row -->

<div class="row">
<?php 
	// แสดง Action ของ ads
	$showdata = array(
			"create_date" => $ads_arr['create_date'],
			"create_by_name" => $ads_arr['create_by_name'],
			"lastupdate_date" => $ads_arr['lastupdate_date'],
			"lastupdate_by_name" => $ads_arr['lastupdate_by_name']
	);
	$this->box_model->DisplayLog($showdata); 
?>
</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );
		$('#category_id').change(function( ) {
				var catid = $(this).val();
				$('#subcategory_id').load('<?php echo base_url() ?>subcategory/list_dd/' + catid);
		});

});

</script>
