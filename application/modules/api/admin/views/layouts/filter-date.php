<div class="row">
	<div class="col-md-5 col-xs-6 select-search-report">
		<select name="month" class="form-control">
			<option>-- เลือกเดือน --</option>
		<?php $get = $this->input->get();

		foreach($get_months as $key_month => $value_month){ ?>
			<option value="<?php echo $key_month; ?>" <?php echo ($current_date['month'] == $key_month ? "selected" : ""); ?>><?php echo $value_month; ?></option>
		<?php } //end foreach ?>
		</select>
	</div>
	<div class="col-md-5 col-xs-6">
		<select name="year" class="form-control">
			<option>-- เลือกปี --</option>
		<?php foreach($get_years as $key_year => $value_year){ ?>
			<option value="<?php echo $key_year; ?>" <?php echo ($current_date['year'] == $key_year ? "selected" : ""); ?>><?php echo $value_year; ?></option>
		<?php } //end foreach ?>
		</select>
	</div>
	<div class="col-md-2 col-xs-12 btn-search-report">
		<button class="btn btn-default">ค้นหา</button>
	</div>
<!-- /.row -->
</div>