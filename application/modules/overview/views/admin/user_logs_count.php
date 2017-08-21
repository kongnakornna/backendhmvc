<?php 
	$language = $this->lang->language; 
	//Debug($logs_list);
	//Debug($this->db->last_query());
?>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('admin/memberlist') ?>';">
										<i class="ace-icon fa fa-users align-top bigger-125"></i><?php echo $language['member_list'] ?>
								</button>

								<div class="row">
									<div class="col-xs-12">
										<h3 class="header smaller lighter blue"><?php echo $language['activity_logs'].' '.$language['of'].' '.$memberlist->_admin_name.' '.$memberlist->_admin_lastname.' ('.$memberlist->_admin_nickname.')' ?> </h3>
										<div class="table-header">
											<?php echo $language['activity_logs'] ?> 
										</div>

<?php
				if(function_exists('Debug')){
					//Debug($this->db) ;
					//Debug($all_member);
					//Debug($memberlist);
				}
				/*echo "<pre>";
				var_dump($this);
				echo "</pre>";*/

				/*$result_object = $all_member->result_object;
				if($result_object)
					foreach($result_object as $key ){
									echo $key.'<br>';
					}*/
			
			if(isset($error)){
?>
				<div class="col-xs-12">
							<div class="alert alert-danger">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon fa fa-times"></i>
											</strong><?php echo $error?>.
									<br>
							</div>
				</div>
<?php
			}
?>
<?php
			if(isset($success)){
?>
				<div class="col-xs-12">
							<div class="alert alert-success">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon fa fa-times"></i>
											</strong><?php echo $success?>.
									<br>
							</div>
				</div>
<?php
			}
			//Debug($logs_list);
			//Debug($memberlist);
?>
	<div>

	<form method="post" action="" id="listFrm">
		<table id="dataTables-logs" class="table-responsive table table-striped table-bordered table-hover ">
				<thead>
				<tr>
						<th>NO.</th>
						<th><?php echo $language['type'] ?></th>
						<th><?php echo $language['add'] ?></th>
				</tr>
				</thead>

				<tbody>
<?php
/*Array(
    [news] => 54
    [column] => 16
    [gallery] => 1
    [video] => 0
    [dara] => 0
)*/
				$now_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
				$i=0;
				if($logs_list)
				foreach($logs_list as $key => $val){

					$i++;					

?>
				<tr>
						<td><?=$i ?></td>
						<td><?=$key ?></td>
						<td><?=$val?> รายการ</td>
				</tr>

<?php
				}
?>
			</tbody>
		</table>
		</form>
										</div>
									</div>
								</div>

						<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
			
		$('.del-confirm').on('click', function() {				
				var v = $(this).attr('data-value');
				var title = $(this).attr('data-name');

				bootbox.confirm("<?php echo $language['are you sure to delete']?>", function(result) {
					if(result) {
							//alert(img + ' '+ v);
							AlertError('ระดับการใช้งานของคุณไม่สามารถลบ ' + title + '  ID = '+ v + ' นี้ได้');
							/*$.ajax({
									type: 'POST',
									url: "<?php echo base_url() ?>activity_logs/delete_log/" + v,
									data : { title : title},
									cache: false,
									success: function(data){
											//alert(data);
											if(data = 'Yes'){
													$('#admin_avatar').attr('style', 'display:none');
													$('#upload_avatar').attr('style', 'display:block');
											}
									}
							});*/
						}
				});
		});

		$('#dataTables-logs').dataTable(); 

});
</script>
