<?php 
		$language = $this->lang->language; 
		//Debug($ads_list);
		$use18up = $ads_list[0]['use18up'];
?>
<div class="col-xs-12">

					<div class="page-content-area">
						<div class="page-header">
							<h1> <?php echo $language['ads']; ?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<!-- (single &amp; multiple) --><?php echo $this->config->config['www']; ?>
								</small>
							</h1>
						</div>

						<div class="row">
							<div class="col-xs-6">
								<div class="row">
<?php
		$attributes = array('class' => 'form-horizontal', 'name' => 'AdsForm', 'id' => 'AdsForm');
		echo form_open_multipart('gen/save_ads', $attributes);
?>
										<div class="col-xs-12 col-sm-6">
											<div class="control-group">

												<h3 class="row header smaller lighter purple"><span class="col-sm-6"> <?php echo $language['ads']; ?> </span></h3>

												<span> คำแนะนำในหน้าสร้างแคชไฟล์  
												<li><b>ADS ปกติ</b> จะทำงานตามปกติ</li>
												<li><b>ADS18+</b> กรณีที่ หน้าแรกมี เนื้อ มีภาพ 18+ ควรเลือก ADS 18+ เพื่อปกป้องการ ถูกโดนแบน จาก Google ads</li></span>

												<div class="radio">
													<label>
														<input name="use" type="radio" class="ace" value=0 <?php if($use18up == 0) echo "checked" ?>>
														<span class="lbl"> Ads ปกติ</span>
													</label>
												</div>

												<div class="radio">
													<label>
														<input name="use" type="radio" class="ace" value=98 <?php if($use18up == 98) echo "checked" ?>>
														<span class="lbl"> Ads 18+</span>
													</label>
												</div>
												<button type="submit" class="btn btn-sm btn-success" id="genads">
														<?php echo $language['use']; ?>
														<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
												</button>

											</div>
										</div>
<?php echo form_close();?>

								</div>
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->

							<div class="col-xs-12">
								<div class="row" id="preview_data">
								</div>
							</div><!-- /.col -->

						</div><!-- /.row -->
					</div><!-- /.page-content-area -->

</div><!-- /.col -->

<?php 
	//echo js_asset('fuelux/data/fuelux.tree-sample-demo-data.js'); 
	//echo js_asset('fuelux/fuelux.tree.min.js'); 
?>
<!-- <link rel="stylesheet" href="../assets/css/ace.onpage-help.css" /> -->

<!-- inline scripts related to this page -->
<script type="text/javascript">
jQuery(function($){

		//$('[data-rel=tooltip]').tooltip();
		//$('[data-rel=popover]').popover({html:true});

});

function Gen_API(){
			//Gen_ADS();
}

function Gen_ADS(){

}

</script>