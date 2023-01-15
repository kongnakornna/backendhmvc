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
			<form method="GET" action="<?php echo base_url('admin/ict/worksummary/'.$get_user->user_idx); ?>">
				<?php $this->load->view("layouts/filter-date"); ?>
			</form>
		</div>
	<!-- /.row -->
	</div>
</div>

<div>
	<div class="row">
		<div class="col-md-4 title-table-summary">
			<p>สรุปการทำงานเดือน<br><?php echo $get_months[$current_date['month']] ?> <?php echo $get_years[$current_date['year']] ?></p>
		</div>
		<div class="col-md-8">
			<table class="table table-summary" border="1">
				<thead>
					<tr>
						<th rowspan="2">จำนวนวันทั้งหมด<br>(นับถึงวันปัจจุบัน)</th>
						<th colspan="2">ยืนยันการทำงานแล้ว</th>
						<th rowspan="2">ยังไม่ยืนยันการทำงาน</th>
					</tr>
					<tr>
						<th>มีกิจกรรม</th>
						<th>ไม่มีกิจกรรม</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center"><?php echo $count_dayall; ?></td>
						<td class="text-center"><?php echo $work_summay->trans_confirm_topic; ?></td>
						<td class="text-center"><?php echo $work_summay->trans_confirm_notopic; ?></td>
						<td class="text-center"><?php echo $work_summay->trans_notconfirm; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	<!-- /.row -->
	</div>
</div>

<div>
	<div class="row">
		<div class="col-md-12">
			<span class="title-table-report">ตารางกิจกรรมยืนยันการทำงานแล้ว</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped table-report-users">
				<thead>
					<tr class="thead-table-report">
						<th>กิจกรรม</th>
						<th>จำนวนครั้ง</th>
					</tr>
				</thead>
				<tbody>
				<?php $no = 1;
				$result_summary = 0;
				foreach($summary_topics as $topic){ ?>
					<tr>
						<td>
							<a href="#" data-toggle="modal" data-target="#topic-action-<?php echo $topic['summary_topics']->idx; ?>"><?php echo $no; ?>. <?php echo $topic['summary_topics']->pms_name; ?></a>
							<?php $data['no'] = $no;
							$data['topic'] = $topic;
							$this->load->view("popup/worksummary-popup", $data); 
							?>
						</td>
						<td class="text-center"><?php echo $topic['summary_topics']->topic_summary; ?></td>
					</tr>
				<?php $no++;
					$result_summary += $topic['summary_topics']->topic_summary;
				} // end foreach ?>
					<tr class="result-table-report">
						<td class="text-right">จำนวนกิจกรรมทั้งหมด</td>
						<td class="text-center"><?php echo $result_summary; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	<!-- /.row -->
	</div>
</div>