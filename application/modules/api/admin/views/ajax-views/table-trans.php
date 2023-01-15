<table class="table table-striped" border="1" id="table-trans">
	<thead>
		<tr class="head-table">
			<th id="title-topic">กิจกรรม</th>
		<?php foreach($date_result as $key_daymonth => $daymonth){ ?>
			<th class="head-col <?php echo ($daymonth['date'] == date("Y-m-d") ? "box-today" : ""); ?>
					<?php echo (date("W", strtotime($daymonth['date'])) == date("W") && date("D", strtotime($daymonth['date'])) == "Mon") ? "box-toweek" : ""; ?>"
				data-container="body" rel="tooltip" data-placement="top" title="<?php echo $controllers->paresDateThai($daymonth['day'], $daymonth['month'], $daymonth['year']); ?>">
				<?php echo $daymonth['day']; ?></th>
		<?php } ?>
		</tr>
	</thead>
	<tbody>
	<?php $key_index = 1; ?>
	<?php foreach($pms_topic as $key_topic => $topic){ ?>
		<tr class="body-table">
			<td id="title-topic"><?php echo $key_index.". "; ?><?php echo $topic->pms_name; ?></td>
		<?php foreach($date_result as $key_daymonth => $daymonth){ 
			$trans_day = (!empty($topic->idx) && !empty($daymonth['date'])) ? $controllers->checkTransOfDay($topic->idx, $daymonth['date'], $get_user->user_idx) : "";
			$confirm_trans = (!empty($daymonth['date'])) ? $controllers->checkConfrimOfDate($daymonth['date'], $get_user->user_idx) : ""; ?>

			<?php //echo "<pre>";print_r($trans_day); ?>
			<td id="diary-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>" 
				class="td-diary <?php echo (!empty($trans_day)) ? "box-selected" : ""; ?>
					<?php echo (date("W", strtotime($daymonth['date'])) == date("W") && date("D", strtotime($daymonth['date'])) == "Mon") ? "box-toweek" : ""; ?>"
				data-toggle="modal" data-target="#myModal-<?php echo $key_topic; ?>-<?php echo $daymonth['day']; ?>"
				rel="tooltip" data-container="body" data-placement="top" title="<?php echo $controllers->paresDateThai($daymonth['day'], $daymonth['month'], $daymonth['year']); ?>"></td>
			
				<!-- Modal -->
				
				<?php $data['key_topic'] = $key_topic;
				$data['topic'] = $topic;
				$data['key_daymonth'] = $key_daymonth;
				$data['daymonth'] = $daymonth;
				$data['trans_day'] = $trans_day;
				$data['confirm_trans'] = $confirm_trans['status'];

				if($topic->pms_group_id == 2){
					$this->load->view("admin/popup/popup-group-2", $data); 
				}else{
					$this->load->view("admin/popup/popup-group-1", $data); 
				}
				?>
				
		<?php } //end foreach ?>
		</tr>
		<?php $key_index++; ?>
	<?php } //end foreach topic ?>
		<tr class="footer-table">
			<td id="status-trans" align="right">สถานะ</td>
		<?php foreach($date_result as $key_daymonth => $daymonth){ ?>
			<?php $confirm_trans = (!empty($key_topic) && !empty($daymonth['date'])) ? $controllers->checkConfrimOfDate($daymonth['date'], $get_user->user_idx) : ""; ?>
			<?php if($confirm_trans['status'] != false){ ?>
				<td class="confirm-false <?php echo (date("W", strtotime($daymonth['date'])) == date("W") && date("D", strtotime($daymonth['date'])) == "Mon") ? "box-toweek" : ""; ?>" align="center" onclick="confirm_trans('<?php echo $confirm_trans['data_trans']->date_save; ?>')">
					<i class="fa fa-circle fa-1x font-green"></i>
				</td>
			<?php }else{ ?>
				<td class="confirm-true <?php echo (date("W", strtotime($daymonth['date'])) == date("W") && date("D", strtotime($daymonth['date'])) == "Mon") ? "box-toweek" : ""; ?>" align="center">
					<i class="fa fa-circle fa-1x font-red"></i>
				</td>
			<?php } // end if ?>
		<?php } //end foreach ?>
		</tr>
	</tbody>
</table>

<script type="text/javascript">
	$('[rel="tooltip"]').tooltip(); 
</script>