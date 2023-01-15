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
						value="<?php echo (!empty($trans_day)) ? $trans_day->action_name : ""; ?>"
						maxlength="90" required>
					</div>
				</div>

				<div class="row input-trans">
					<div class="col-md-4">
						<label>กลุ่มเป้าหมาย</label><span style="color: red"> *</span>
					</div>
					<div class="col-md-8">
						<select class="form-control" name="action_target-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>">
							<option value="">-- เลือกกลุ่มเป้าหมาย --</option>
							<option value="นักเรียน" <?php echo (!empty($trans_day) && $trans_day->action_target == "นักเรียน") ? "selected" : ""; ?> >นักเรียน</option>
							<option value="ครู" <?php echo (!empty($trans_day) && $trans_day->action_target == "ครู") ? "selected" : ""; ?>>ครู</option>
							<option value="ชุมชน" <?php echo (!empty($trans_day) && $trans_day->action_target == "ชุมชน") ? "selected" : ""; ?>>ชุมชน</option>
							<option value="นักเรียน+ครู" <?php echo (!empty($trans_day) && $trans_day->action_target == "นักเรียน+ครู") ? "selected" : ""; ?>>นักเรียน+ครู</option>
							<option value="อื่นๆ" <?php echo (!empty($trans_day) && $trans_day->action_target == "อื่นๆ") ? "selected" : ""; ?>>อื่นๆ</option>
						</select><!-- trans_day->action_target -->
					</div>
				</div>

				<div class="row input-trans">
					<div class="col-md-4">
						<label>จำนวนคนที่เข้าร่วม</label><span style="color: red"> *</span>
					</div>
					<div class="col-md-4">
						<input type="number" oninput="if(this.value.length > 4) this.value = this.value.slice(0,4);"
						class="form-control" name="action_member_count-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>"
						value="<?php echo (!empty($trans_day)) ? $trans_day->action_member_count : ""; ?>"
						max="9999" required>
					</div>
					<div class="col-md-4">
						<label>คน</label>
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

				<div class="row input-trans">
					<div class="col-md-4">
						<label>ระยะเวลาที่ใช้</label><span style="color: red"> *</span>
					</div>
					<div class="col-md-4">
						<input type="number" class="form-control" name="action_duration-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>"
						value="<?php echo (!empty($trans_day)) ? $trans_day->action_duration : ""; ?>"
						max="9999" oninput="if(this.value.length > 4) this.value = this.value.slice(0,4);" required>
					</div>
					<div class="col-md-4">
						<label>นาที</label>
					</div>
				</div>

				<div class="row input-trans">
					<div class="col-md-4">
						<label>สื่อ/อุปกรณ์ที่ใช้</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="action_media-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>"
						value="<?php echo (!empty($trans_day)) ? $trans_day->action_media : ""; ?>"
						maxlength="90" required>
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
	function save_trans_group1(topic_id, topic_day){
		var pms_trans_idx = $("[name=pms_trans_idx-"+topic_id+"-"+topic_day+"]").val();
		var action_name = $("[name=action_name-"+topic_id+"-"+topic_day+"]").val();
		var action_target = $("[name=action_target-"+topic_id+"-"+topic_day+"]").val();
		var action_member_count = $("[name=action_member_count-"+topic_id+"-"+topic_day+"]").val();
		var action_detail = $("[name=action_detail-"+topic_id+"-"+topic_day+"]").val();
		var action_duration = $("[name=action_duration-"+topic_id+"-"+topic_day+"]").val();
		var action_media = $("[name=action_media-"+topic_id+"-"+topic_day+"]").val();
		var pms_topic_idx = $("[name=pms_topic_idx-"+topic_id+"-"+topic_day+"]").val();
		var date_action = $("[name=date_action-"+topic_id+"-"+topic_day+"]").val();

		if(!action_name || !action_target || !action_member_count || !action_detail || !action_duration){
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
				data: {pms_trans_idx, action_name, action_target, action_member_count, action_detail, action_duration, action_media, pms_topic_idx, date_action},
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