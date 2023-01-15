<div>
	<div class="row">
		<div class="col-md-5">
			<h3><?php //echo $bread_crumb; ?></h3>
		</div>
		<div class=" col-md-7">
			<?php $this->load->view("layouts/link-report"); ?>
		</div>
	</div>
</div>

<div>
	<div class="row filter-box text-profile-user">
		<div class="col-md-5">
			<?php $this->load->view("layouts/info-user"); ?>
		</div>

		<div class="col-md-6 col-md-offset-1">
			<form method="GET" action="<?php echo base_url('admin/ict/monthly/'.$get_user->user_idx); ?>">
				<?php $this->load->view("layouts/filter-date"); ?>
			</form>
		</div>
	<!-- /.row -->
	</div>
</div>

<div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table table-striped table-report-users">
				<thead>
					<tr>
						<th>วันที่บันทึก</th>
						<th>มีกิจกรรมบันทึกหรือไม่</th>
						<th>วันที่กดยืนยันยัน</th>
						<th style="width: 20%;">สถานะยืนยัน</th>
						<th>จำนวนกิจกรรม</th>
						<th>สถานะบันทึก</th>
						<th>จัดการสถานะบันทึก</th>
					</tr>
				</thead>
				<tbody>
				<?php $count_trans = 0;
				$no = 1;
				foreach($user_log as $key_log => $value_log){ 
					$user_id = isset($value_log['data_trans']->user_idx) ? $value_log['data_trans']->user_idx : "";
					$date_save = isset($value_log['data_trans']->date_save) ? $value_log['data_trans']->date_save : ""; 
					$day_str = date("D", strtotime($value_log['full_date'])); ?>
					<tr class="<?php echo ($day_str == "Mon") ? "border-monday" : "";  ?>">
						<td>
							<?php $start_a = "";
							$end_a = "";
							if($value_log['data_trans']){
								$start_a = "<a href='#' data-toggle='modal' data-target='#trans-report-modal-".$key_log."'>";
								$end_a = "</a>";
							}
							echo $start_a;
							echo $value_log['pares_date']; 
							echo $end_a; ?>

							<div class="modal fade" id="trans-report-modal-<?php echo $key_log; ?>" role="dialog">
								<div class="modal-dialog modal-trans-report">
									<!-- Modal content-->
									<div class="modal-content">
										<div class="modal-header">
								  			<h4>กิจกรรมบันทึก <?php echo $value_log['pares_date']; ?></h4>
										</div>
										<div class="modal-body">
											<div class="table-responsive">
											<table class="table table-report-trans">
												<thead>
													<tr>
														<th>ลำดับ</th>
														<th>กิจกรรม</th>
														<th>ชื่อกิจกรรม</th>
														<th>กลุ่มเป้าหมาย</th>
														<th>จำนวนคนที่เข้าร่วม</th>
														<th>รายละเอียดกิจกรรม</th>
														<th>ระยะเวลาที่ใช้</th>
														<th>สื่อ/อุปกรณ์ที่ใช้</th>
													</tr>
												</thead>
												<tbody>
											<?php  $no_trans_all = 1;
											if($value_log['data_trans_all']){
												foreach($value_log['data_trans_all'] as $value_trans_all){ ?>
													<tr>
														<td class="text-center"><?php echo $no_trans_all; ?></td>
														<td><?php echo $value_trans_all->pms_name; ?></td>
														<td><?php echo $value_trans_all->action_name; ?></td>
														<td><?php echo $value_trans_all->action_target; ?></td>
														<td><?php echo $value_trans_all->action_member_count; ?></td>
														<td><?php echo $value_trans_all->action_detail; ?></td>
														<td><?php echo $value_trans_all->action_duration; ?></td>
														<td><?php echo $value_trans_all->action_media; ?></td>
													</tr>
												<?php $no_trans_all++;
												} //end foreach 
											}else{ ?>
												<tr>
													<td class="font-col-span text-center" colspan="8">ไม่มีบันทึกกิจกรรม</td>
												</tr>
											<?php } //end if ?>
												</tbody>
											</table>
											<!-- /.table-reponsive -->
											</div>
										</div>
									<!-- modal-content -->
									</div>
								<!-- /.modal-dialog -->
								</div>
							<!-- /.modal -->
							</div>
						</td>
						<td class="text-center"><?php echo ($value_log['data_trans'] ? "มี" : "ไม่มี"); ?></td>
						<td class="<?php echo ($value_log['data_trans'] ? "" : "text-center"); ?>"><?php echo ($value_log['data_trans'] ? $value_log['pares_date_save'] : "-"); ?></td>
						<td>
							<?php $status_confirm = "";
							switch ($value_log['status_confirm']['id']) {
								case '1':
									$status_confirm = "green";
									break;
								case '2':
									$status_confirm = "orange";
									break;
								case '3':
									$status_confirm = "brown";
									break;
								case '4':
									$status_confirm = "red";
									break;
							} ?>
							<span style="color: <?php echo $status_confirm; ?> ;"><?php echo $value_log['status_confirm']['name']; ?></span>
						</td>
						<td class="text-center"><?php echo ($value_log['data_trans'] ? $value_log['data_trans']->count_action : "-"); ?></td>
						<td  class="text-center"><?php echo (isset($value_log['data_trans']->date_save) ? "<i class='fa fa-circle fa-1x font-green'></i>" : "<i class='fa fa-circle fa-1x font-red'></i>"); ?></td>
						<td class="text-center btn-table-detail" style="width: 13%;">
							<button class="btn btn-danger" <?php echo (isset($value_log['data_trans']->date_save) && $value_log['data_trans']->date_save ? "" : "disabled"); ?> onclick="unsubmit(this, '<?php echo $user_id; ?>', '<?php echo $date_save; ?>')">unsubmit</button>
						</td>
					</tr>
				<?php $count_trans += ($value_log['data_trans']) ? $value_log['data_trans']->count_action : 0;
					$no++;
				} //end foreach ?>
					<tr>
						<td class="title-bottom-report" colspan="6">จำนวนกิจกรรมบันทึก</td>
						<td class="result-bottom-report"><?php echo $count_trans; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	<!-- /.row -->
	</div>
</div>

<script type="text/javascript">
	function unsubmit(e, user_id, date_save)
	{
		swal({
			title: 'ยืนยัน Unsubmit',
		  	text: "unsubmit รหัสผู้ใช้ "+user_id+" วันที่ "+date_save,
		  	type: 'warning',
		  	showCancelButton: true,
		  	confirmButtonColor: '#3085d6',
		  	cancelButtonColor: '#d33',
		  	confirmButtonText: 'ยืนยัน',
		  	cancelButtonText: 'ยกเลิก',
		}).then(function(){
  	    	$.ajax({
  	    		type: 'POST',
  	    		url: '<?php echo base_url("admin/ict/unsubmit"); ?>',
  	    		dataType: 'json',
  	    		data: {user_id, date_save},
  	    		success: function(data){
  	    			if(data != false){
  	    				swal({
							title: 'กำลัง unsubmit',
							text: 'โปรดรอสักครู่',
							timer: 1000,
							onOpen: function () {
							    swal.showLoading()
							}
						}).then(
							function(){
								// Exit loading
							},
							function (dismiss) {
							    swal({
									text: "unsubmit สำเร็จ",
									type: "success",
									confirmButtonText: 'OK'
								}).then(
									function(){
										// $(e).attr("disabled", true);
										location.reload();
									}
								)
							}
						)
  	    			}
  	    		}
  	    	});
  	    });
	}
</script>