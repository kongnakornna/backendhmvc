<div class="modal fade" id="topic-action-<?php echo $topic['summary_topics']->idx; ?>" role="dialog">
	<div class="modal-trans-report modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
	  			<button type="button" class="close" data-dismiss="modal">&times;</button>
	  			<h4 class="modal-title"><?php echo $no; ?>. <?php echo $topic['summary_topics']->pms_name; ?></h4>
	  			
			</div>
			<div class="modal-body">
				<div class="table-responsive">
				<table class="table table-striped table-report-users">
					<thead>
						<tr class="thead-table-report">
							<th>วันที่</th>
							<th>ชื่อกิจกรรม</th>
							<th>กลุ่มเป้าหมาย</th>
							<th>จำนวนคน</th>
							<th>รายละเอียด</th>
							<th>ระยะเวลา</th>
							<th>อุปกรณ์</th>
						</tr>
					</thead>
					<tbody>
				<?php if($topic['trans_of_topic']){
					foreach($topic['trans_of_topic'] as $trans){ 
						$day_action = date('d', strtotime($trans->date_action));
						$month_action = date('m', strtotime($trans->date_action));
						$year_action = date('Y', strtotime($trans->date_action)); ?>
						<tr>
							<td><?php echo $controllers->paresDateThai($day_action, $month_action, $year_action); ?></td>
							<td><?php echo $trans->action_name; ?></td>
							<td><?php echo $trans->action_target; ?></td>
							<td class="text-center"><?php echo $trans->action_member_count; ?></td>
							<td><?php echo $trans->action_detail; ?></td>
							<td class="text-center"><?php echo $trans->action_duration; ?></td>
							<td><?php echo $trans->action_media; ?></td>
						</tr>
				<?php } // end foreach 
				}else{ ?>
						<tr>
							<td class="font-col-span text-center" colspan="7">ไม่มีบันทึกกิจกรรม</td>
						</tr>
				<?php } //end if?>
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
