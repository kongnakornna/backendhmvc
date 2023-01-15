<div class="modal fade" id="myModal-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		<form>
			<div class="modal-header">
	  			<button type="button" class="close" data-dismiss="modal">&times;</button>
	  			<h4 class="modal-title">บันทึกกิจกรรม การทำงาน</h4>
	  			<h5><strong>หัวข้อเรื่อง:</strong> <?php echo $topic->pms_name; ?></h5>
	  			<h5><strong>วัน/เดือน/ปี:</strong> <?php echo $controllers->paresDateThai($daymonth['day'], $daymonth['month'], $daymonth['year']); ?></h5>
			</div>
			<div class="modal-body">
				<div class="row input-trans">
					<div class='col-md-4'>
						<label>ชื่อกิจกรรม</label><span style="color: red"> *</span>
					</div>
					<div class='col-md-8'>
						<input name="action_name-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>" type="text" class="form-control" 
						value="<?php echo (!empty($trans_day)) ? $trans_day->action_name : "รายงานรักษาความสะอาดชุดอุปกรณ์"; ?>"
						maxlength="90" required readonly>
					</div>
				</div>

				<div class="row input-trans">
					<div class="col-md-4">
						<label>รายละเอียดกิจกรรม</label><span style="color: red"> *</span>
					</div>
					<div class="col-md-8">
						<textarea class="form-control" name="action_detail-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>"
							maxlength="290" required><?php echo (!empty($trans_day)) ? $trans_day->action_detail : ""; ?></textarea>
					</div>
				</div>

				<input type="hidden" name="pms_trans_idx-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>" value="<?php echo (!empty($trans_day)) ? $trans_day->idx : ""; ?>">
				<input type="hidden" name="pms_topic_idx-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>" value="<?php echo ($topic->idx) ? $topic->idx : "";?>">
				<input type="hidden" name="date_action-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>" value="<?php echo ($daymonth['date']) ? $daymonth['date'] : "";?>">
			<!-- /.modal-body -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-form" data-dismiss="modal">Close</button>
			</div>
		</form>
		<!-- modal-content -->
		</div>
	<!-- /.modal-dialog -->
	</div>
<!-- /.modal -->
</div>

<script type="text/javascript">
	function save_trans_group2(topic_id, topic_day){
		var pms_trans_idx = $("[name=pms_trans_idx-"+topic_id+"-"+topic_day+"]").val();
		var action_name = $("[name=action_name-"+topic_id+"-"+topic_day+"]").val();
		var action_detail = $("[name=action_detail-"+topic_id+"-"+topic_day+"]").val();
		var pms_topic_idx = $("[name=pms_topic_idx-"+topic_id+"-"+topic_day+"]").val();
		var date_action = $("[name=date_action-"+topic_id+"-"+topic_day+"]").val();

		if(!action_name || !action_detail){
			swal({
				text: "กรุณากรอกข้อมูลให้ครบ",
				 type: "error",
				 confirmButtonText: 'OK'
			})
		}else{
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url("ict/save_trans"); ?>',
				dataType: 'json',
				data: {pms_trans_idx, action_name, action_detail, pms_topic_idx, date_action},
				success: function(data){
					if(data['status'] == true){
						//$('#cancel-modal').trigger('click');
						//$('#myModal-'+topic_id+'-'+topic_day).modal('hide');
						swal({
							title: 'กำลังบันทึก',
							text: 'โปรดรอสักครู่',
							timer: 2000,
							onOpen: function () {
							    swal.showLoading()
							    table_trans();
							}
						}).then(
							function(){
								// Exit loading
							},
							function (dismiss) {
							    swal({
									text: data['text_status'],
									type: "success",
									confirmButtonText: 'OK'
								}).then(
									function(){
										location.reload();
									}
								)
							}
						)
					}else{
						swal({
							 text: data['text_status'],
							 type: "error",
							 confirmButtonText: 'OK'
						}).then(function(){
							table_trans();
						})
					}
				}
			});
		}	
	}
</script>