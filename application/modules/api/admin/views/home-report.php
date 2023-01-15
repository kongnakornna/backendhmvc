<!-- Head -->
<div>
	<div class="row form-group">
		<form class="form-filter-remark" method="get" action="<?php echo base_url('admin/ict'); ?>">
		<div class="col-md-offset-3 col-md-3 col-xs-6">
			<select name="remark_pmo" class="form-control">
				<option value="">-- เลือกผู้ดูแล --</option>
			<?php foreach($get_remark_pmo as $value){ ?>
				<option value="<?php echo $value->remark_PMO; ?>" <?php echo (isset($_SESSION['filter_users']['remark_pmo']) && $value->remark_PMO == $_SESSION['filter_users']['remark_pmo'] ? "selected" : ""); ?>><?php echo $value->remark_PMO; ?></option>
			<?php } //end foreach ?>
			</select>
		</div>
		<div class="col-md-4 col-xs-6">
			<?php $q_filter = isset($_SESSION['filter_users']['q']) && $_SESSION['filter_users']['q'] ? $_SESSION['filter_users']['q'] : ""; ?>
			<input type="text" name="q" class="form-control" value="<?php echo $q_filter; ?>" placeholder="คำค้น(ชื่อ-สกุล, โรงเรียน, จังหวัด)">
		</div>
		<div class="col-md-2 col-xs-12">
			<button type="submit" class="btn btn-default btn-100-per">ค้นหา</button>
		</div>
		</form>
	<!-- /.row -->
	</div>
</div>
<div >
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table table-striped table-report-users">
				<thead>
					<tr>
						<th>ลำดับ</th>
						<th>ชื่อ นามสกุล</th>
						<TH>โรงเรียนที่ดูแล</TH>
						<th>จังหวัด</th>
						<th>ผู้ดูแล</th>
						<th>Trans Count</th>
						<th>บันทึกกิจกรรม</th>
						<th>ส่งรายงานประจำวัน</th>
						<th>Work Summary</th>
						<th>Monthly Report</th>
						<th>แอบส่องการทำงาน</th>
					</tr>
				</thead>
				<tbody>
				<?php $no = 1;
				foreach($user_reports as $user){ 
					// var_dump($user->username); die;
					if($user->username != "ICT00000000" && $user->username != "ICT59040126"){ ?>
					<tr>
						<td class="text-center"><?php echo $no; ?></td>
						<td nowrap><?php echo $user->user_realname; ?></td>
						<td class="<?php echo ($user->school_name) ? "" : "text-center"; ?>">
							<?php echo ($user->school_name) ? "โรงเรียน".$user->school_name : "-"; ?>
						</td>
						<td class="<?php echo ($user->province) ? "" : "text-center"; ?>">
							<?php echo ($user->province) ? $user->province : "-"; ?>
						</td>
						<td nowrap><?php echo ($user->remark_PMO) ? $user->remark_PMO : "-"; ?></td>
						<td class="text-center"><?php echo $user->total_trans; ?></td>
						<td class="text-center"><?php echo $user->action_trans; ?></td>
						<td class="text-center"><?php echo $user->save_trans; ?></td>
						<td class="text-center"><a href="<?php echo base_url('admin/ict/worksummary/'.$user->user_idx); ?>" class="btn btn-default"><i class="fa fa-file-text" aria-hidden="true"></i></a></td>
						<td class="text-center"><a href="<?php echo base_url('admin/ict/monthly/'.$user->user_idx); ?>" class="btn btn-default"><i class="fa fa-file-text" aria-hidden="true"></i></a></td>
						<td class="text-center"><a href="<?php echo base_url('admin/ict/action/'.$user->user_idx); ?>" class="btn btn-default"><i class="fa fa-file-text" aria-hidden="true"></i></a></td>
					</tr>
				<?php $no++;
					} //end if
				} // end foreach ?>
				</tbody>
			</table>
		</div>
	<!-- /.row -->
	</div>
<!-- /.container -->	
</div>