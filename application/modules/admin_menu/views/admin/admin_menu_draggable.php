
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
								<?php echo $language['draggable'];?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<!-- start: DRAGGABLE HANDLES 3 PANEL -->
							<div class="panel panel-default">
								<div class="panel-heading">
									 
									 <?php #echo $language['draggable'];?>
									 Draggable
								</div>
<?php ###################?>
<!--<textarea id="nestable-output"></textarea>-->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-sm-6">
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
	<li class="dd-item" data-id="<?php echo $admin_menu_id2; ?>" value="<?php echo $weight; ?>" data="<?php echo $weight; ?>">
		<div class="dd-handle" align="left">
		<i class="fa <?php echo $icon;?>"></i> <?php echo $title;?>
		</div>
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
														<li class="dd-item" data-id="<?php echo $admin_menu_id2_sub; ?>" value="<?php echo $weight_sub; ?>" data="<?php echo $weight_sub; ?>">
															<div class="dd-handle" align="left">
																<i class="fa <?php echo $icon_sub;?>"></i> <?php echo $title_sub;?>
															</div>
														</li>
													</ol>
<?php
			   $j++;
				}
			}										
?>
			</li>
		</ol>
<?php
	$i++;
	}
}										
?>
										</div>
									</div>
								</div><!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div>
<?php  ################################***Draggable****################################?>			
<script type="text/javascript">
$( document ).ready(function() {
var list = e.length ? e : $(e.target), output = list.data('output');
 $.ajax({
<?php $urlUpdateOrder = base_url('admin_menu/updateblock'); ?>
                    method: "POST",
                    url: "<?php echo $urlUpdateOrder; ?>",
                    data: {
                        list: list.nestable('serialize')
                    }

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert("Unable to save new list order: " + errorThrown);
                });
/*
                swal({
                    title: "จัดเรียงข้อมูลเรียบร้อย!",
                    text: "<?php echo 'บันทึกข้อมูลสำเร็จ กด F5 หรือ Refash 1ครั้ง เพื่อดูการเปลี่ยนแปลง!!'; ?>",
                    timer: 1500,
                    showConfirmButton: false
                });
*/
                alert("จัดเรียงข้อมูลเรียบร้อย..!");  
                window.location.reload();

            };
            $('#nestable').nestable({
                group: 1,
                maxDepth: 7,
            }).on('change', updateOutput);
            ///////////////////////////////
		$('#saveorder').on('click', function() {
				document.getElementById("jform").submit();
		});		
});

</script>
		<!-- basic scripts -->
		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url('theme/')?>/assets/js/jquery.min.js'>"+"<"+"/script>");
		</script>
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url('theme/')?>/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
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
		<script src="<?php echo base_url('theme/')?>/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?php echo base_url('theme/')?>/assets/js/ace/ace.onpage-help.js"></script>
