		<link rel="stylesheet" href="<?php echo base_url('theme/')?>assets/plugins/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>assets/plugins/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>assets/fonts/style.css">
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>assets/css/main.css">
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>assets/css/main-responsive.css">
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>assets/plugins/iCheck/skins/all.css">
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css">
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css">
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>assets/css/theme_light.css" type="text/css" id="skin_color">
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>assets/css/print.css" type="text/css" media="print"/>
<?php 
		$language = $this->lang->language; 
		$i=0;
		$maxcat = count($web_menu);
?>
<style type="text/css">
.col-sm-6 {width: 100%;text-align: center;	}
#saveorder{cursor:pointer;}
.input-small{text-align: center;}
</style>
						<!-- <div class="page-header">
							<h1>
							<?php //echo $HeadTitle?>
							<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								<?php //echo $HeadTitle?>
							</small>
							</h1>
						</div> -->

						<hr />	
<?php ################################***Draggable****################################?>
	<!-- start: MAIN CONTAINER -->
 
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-info">
								You can drag and drop to rearrange the order. It even works well on touch-screens.
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
						
						
							<!-- start: DRAGGABLE HANDLES 3 PANEL -->
						 
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="fa fa-reorder"></i>
									 <?php echo $language['draggable'];?>
									<div class="panel-tools">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
										<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
											<i class="fa fa-wrench"></i>
										</a>
										<a class="btn btn-xs btn-link panel-refresh" href="#">
											<i class="fa fa-refresh"></i>
										</a>
										<a class="btn btn-xs btn-link panel-expand" href="#">
											<i class="fa fa-resize-full"></i>
										</a>
										<a class="btn btn-xs btn-link panel-close" href="#">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								
								
								
<div class="panel-body">
	<div class="dd" id="nestable">
										<?php ####Main###?>
<?php
			# debug($web_menu);#Die();
			$count=count($web_menu);
			$i=1;
			if($count>=1){
			foreach ($web_menu  as $key=>$menu){
				$admin_menu_id=$menu['admin_menu_id'];
				$admin_menu_id2=$menu['admin_menu_id2'];
				$title=$menu['title'];
				$url=$menu['url'];
				$parent=$menu['parent'];
				$admin_menu_alt=$menu['admin_menu_alt'];
				$option=$menu['option'];
				$create_date=$menu['create_date'];
				$create_by=$menu['create_by'];
				$lastupdate_date=$menu['lastupdate_date'];
				$lastupdate_by=$menu['lastupdate_by'];
				$order_by=$menu['order_by'];
				$weight=$order_by;
				$icon=$menu['icon'];
				$params=$menu['params'];
				$status=$menu['status'];
				$lang=$menu['lang'];
				$count_sub=$menu['count_sub'];
				
				
				
			?>
			<ol class="dd-list"> 
				<li class="dd-item dd3-item" data-id="<?php echo $admin_menu_id2; ?>" value="<?php echo $weight; ?>" data="<?php echo $weight; ?>">
					<div class="dd-handle dd3-handle"></div>
						<div class="dd3-content"><i class="fa <?php echo $icon;?>"></i> <?php echo $title;?></div>
				<?php ####SUB###?>
				<?php 			
				$this->load->model('Admin_menu_model');
				$status_sub_menu=1;
				$web_menu_sub = $this->Admin_menu_model->getMenu_sub($admin_menu_id2,$status_sub_menu);
				$count_menu_sub=count($web_menu_sub);
			$j=1;
			if($count_menu_sub>=1){
			foreach ($web_menu_sub  as $key=>$menu_sub){
				$admin_menu_id_sub=$menu_sub['admin_menu_id'];
				$admin_menu_id2_sub=$menu_sub['admin_menu_id2'];
				$title_sub=$menu_sub['title'];
				$url_sub=$menu_sub['url'];
				$parent_sub=$menu_sub['parent'];
				$admin_menu_alt_sub=$menu_sub['admin_menu_alt'];
				$option_sub=$menu['option'];
				$create_date_sub=$menu_sub['create_date'];
				$create_by_sub=$menu_sub['create_by'];
				$lastupdate_date_sub=$menu_sub['lastupdate_date'];
				$lastupdate_by_sub=$menu_sub['lastupdate_by'];
				$order_by_sub=$menu_sub['order_by'];
				$weight_sub=$order_by;
				$icon_sub=$menu_sub['icon'];
				$params_sub=$menu_sub['params'];
				$status_sub=$menu_sub['status'];
				$lang_sub=$menu_sub['lang'];
				$count_sub_sub=$menu_sub['count_sub'];

?>
														<ol class="dd-list">
															<li class="dd-item dd3-item" data-id"<?php echo $admin_menu_id2_sub; ?>" value="<?php echo $weight_sub; ?>" data="<?php echo $weight_sub; ?>">
																<div class="dd-handle dd3-handle"></div>
																<div class="dd3-content">
																	<i class="fa <?php echo $icon_sub;?>"></i> <?php echo $title_sub;?>
																</div>
															</li>
														</ol>
<?php
			   $j++;
				}
			}										
			 ?>
														<?php ####SUB###?>
												</li>
											</ol>
			<?php
			   $i++;
				}
			}										
			 ?>
										<?php ####Main###?>
									</div>
								</div>
							</div>
							<!-- end: DRAGGABLE HANDLES 3 PANEL -->
						</div>
					</div>
					<!-- end: PAGE CONTENT-->
				</div>
			</div>
			<!-- end: PAGE -->
		
 
<?php ################################***Draggable****################################?>			

<?php ###########################################################?>
<script type="text/javascript">
$( document ).ready(function() {

<?php
		if($web_menu)		
				for($key=0;$key<$maxcat;$key++){
						$title = $web_menu[$key]['title'];
						$menu_id = $web_menu[$key]['admin_menu_id2'];
?>
		$('#status<?=$menu_id?>').on('click', function() {
				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);
				//alert($(this).attr('id'));
				//alert($(this).val());
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('admin_menu/status/'.$menu_id)?>",
					cache: false,
					success: function(data){
							////////
							if(data == 0){
									swal({
											title: "<?php echo $language['inactive'] ?>!",
											text: "<?php echo $language['savecomplate'] ?>",
											timer: 2000,
											showConfirmButton: false
										});
										//////
									}else{
										////////
										swal({
											title: "<?php echo $language['active'] ?>!",
											text: "<?php echo $language['savecomplate'] ?>", 
											timer: 2000,
											showConfirmButton: false
										});
									//////
									/*
										$("#msg_error").attr('style','display:block;');
										AlertError('<?php //echo $language['inactive'] ?>');
									}else{
										$("#msg_success").attr('style','display:block;');
										AlertSuccess	('<?php //echo $language['active'] ?>');
									*/
						}
					}
				});
		});

		$('#bootbox-confirm<?=$menu_id?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
						if(result) {
								window.location='<?php echo base_url('admin_menu/delete/'.$menu_id)?>';
						}
					});
		});

		$('#bx-confirm<?=$menu_id?>').on('click', function() {
					bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
						if(result) {
								window.location='<?php echo base_url('admin_menu/delete/'.$menu_id)?>';
						}
					});
		});

<?php
				}	
?>

		$('#saveorder').on('click', function() {
				document.getElementById("jform").submit();
		});		
});

</script>

 

 
		<script src="<?php echo base_url('theme/')?>/assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->
		<script src="<?php echo base_url('theme/')?>/assets/js/jquery.nestable.min.js"></script>

		<!-- ace scripts -->
		<script src="<?php echo base_url('theme/')?>/assets/js/ace-elements.min.js"></script>
		<script src="<?php echo base_url('theme/')?>/assets/js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($){

				$('.dd').nestable();
				$('.dd-handle a').on('mousedown', function(e){
					e.stopPropagation();
				});
				$('[data-rel="tooltip"]').tooltip();
			});
		</script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="<?php echo base_url('theme/')?>/docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '<?php echo base_url('theme/')?>'; </script>
		<script src="<?php echo base_url('theme/')?>/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?php echo base_url('theme/')?>/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?php echo base_url('theme/')?>/docs/assets/js/rainbow.js"></script>
		<script src="<?php echo base_url('theme/')?>/docs/assets/js/language/generic.js"></script>
		<script src="<?php echo base_url('theme/')?>/docs/assets/js/language/html.js"></script>
		<script src="<?php echo base_url('theme/')?>/docs/assets/js/language/css.js"></script>
		<script src="<?php echo base_url('theme/')?>/docs/assets/js/language/javascript.js"></script>