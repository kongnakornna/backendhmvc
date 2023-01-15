<?php
$get = $this->input->get();
?>
<span class="link-report">
	<a href="<?php echo base_url("admin/ict/worksummary/".$get_user->user_idx."".((isset($get['month']) && $get['month']) && (isset($get['year']) && $get['year']) ? "?month=".$get['month']."&year=".$get['year'] : "")); ?>">Work Summary</a> | 
	<a href="<?php echo base_url("admin/ict/monthly/".$get_user->user_idx."".((isset($get['month']) && $get['month']) && (isset($get['year']) && $get['year']) ? "?month=".$get['month']."&year=".$get['year'] : "")); ?>">Monthly Report</a> | 
	<a href="<?php echo base_url("admin/ict/action/".$get_user->user_idx."".((isset($get['month']) && $get['month']) && (isset($get['year']) && $get['year']) ? "?month=".$get['month']."&year=".$get['year'] : "")); ?>">แอบส่องการทำงาน</a>
</span>