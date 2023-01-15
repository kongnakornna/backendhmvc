<style>
	.loader {
  		border: 16px solid #f3f3f3;
  		border-radius: 50%;
  		border-top: 16px solid #3498db;
  		width: 120px;
  		height: 120px;
  		-webkit-animation: spin 2s linear infinite;
  		animation: spin 2s linear infinite;
  		position: relative;
    	left: 46%;
    	margin-top: 20px;
	}

	@-webkit-keyframes spin {
  		0% { -webkit-transform: rotate(0deg); }
  		100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
  		0% { transform: rotate(0deg); }
  		100% { transform: rotate(360deg); }
	}
</style>

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
			<form method="GET" action="<?php echo base_url('admin/ict/action/'.$get_user->user_idx); ?>">
				<?php $this->load->view("layouts/filter-date"); ?>
			</form>
		</div>
		
	<!-- /.row -->
	</div>
</div>

<div>
	<div class="row">
		<div class="col-md-12">
			<div id="table-diary" class="table-responsive">
				<div class="loader"></div>
				<?php /*$data['date_result']  = $date_result;
				$data['pms_topic'] = $pms_topic;
				$this->load->view('ajax-views/table-trans', $data);*/ ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center form-group">
			<a href="exportExcel">
				<button class="btn btn-success" style="width: 150px;">Export Excel</button>
			</a>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		table_trans();
	});

	function table_trans(){
		var month = '<?php echo $current_date['month'] ?>';
		var year = '<?php echo $current_date['year'] ?>';
		var user_id = '<?php echo $get_user->user_idx ?>';
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url("admin/ict/load_table_trans"); ?>',
			cache: false,
			dataType: 'json',
			data: {month, year, user_id},
			success: function(data){
				$("#table-diary").html(data);
			}
		});
	}	

	function confirm_trans(date_save){
		var user_id = '<?php echo $get_user->user_idx; ?>';
		swal({
			title: 'ยกเลิก ยืนยันบันทึกกิจกรรม',
		  	text: 'ยกเลิกการยืนยันบันทึกกิจกรรม '+date_save,
		  	type: 'warning',
		  	showCancelButton: true,
		  	confirmButtonColor: '#3085d6',
		  	cancelButtonColor: '#d33',
		  	confirmButtonText: 'ยืนยัน',
		  	cancelButtonText: 'ยกเลิก',
		}).then(function(){
  	    	$.ajax({
  	    		type: 'POST',
  	    		url: '<?php echo base_url("admin/ict/cancel_confirm_trans"); ?>',
  	    		dataType: 'json',
  	    		data: {date_save, user_id},
  	    		success: function(data){
  	    			if(data['status'] != false){
  		    			swal({
  							title: 'กำลังบันทึก',
  							text: 'โปรดรอสักครู่',
  							timer: 3000,
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
  								})
  							}
  						)
  					}else{
  		    			swal({
  							text: data['text_status'],
  							type: "error",
  						})
  					}
  	    		}
  	    	})
		})
	}
</script>