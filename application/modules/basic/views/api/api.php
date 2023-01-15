<?php 
	$language = $this->lang->language; 
	//Debug($logs_list);
	//Debug($this->db->last_query());
	//die();
	$admin_type=$this->session->userdata('admin_type');
	//echo '$admin_type='.$admin_type;
?>
<div class="row">
 <div class="col-xs-12">
	<table width="100%" class="table table-striped table-bordered table-hover" id="sample-table-1">
		 <thead>
			 <tr>
				<th width="89%"><?php echo $language['api'] ?></th>
				<th width="11%" class="hidden-480"><?php echo $language['status'] ?></th>
			</tr>
		</thead>

<tbody>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>	
		<a href="<?php echo base_url('apihw/admintype/1');?>" target="_blank">Admin Type</a>				</td>
	<td class="hidden-480">
		<span class="label label-sm label-warning">OK</span>										
	</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/alertconfig');?>" target="_blank">Alert Config</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/condition');?>" target="_blank">Condition</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-warning">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/conditiongroup');?>" target="_blank">Condition group</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>													</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/conditiontype');?>" target="_blank">Condition Type</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-warning">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/email_alert_log');?>" target="_blank">Email Alert log</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/email_config');?>" target="_blank">Email Config</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-warning">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/email_lists');?>" target="_blank">Email List</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/general_setting');?>" target="_blank">General Setting</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/hardware');?>" target="_blank">Hardware</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/hardware_access');?>" target="_blank">Hardware Acces</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/hardware_access_log');?>" target="_blank">Hardware Acces Log</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/hardware_alert_log');?>" target="_blank">Hardware Alert Log</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/hardware_control');?>" target="_blank">Hardware Control</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/hardware_port');?>" target="_blank">Hardware Port</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/hardware_type');?>" target="_blank">Hardware Type</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<?php 
$startdate='2015-01-01';
$datenow=date('Y-m-d');
$limitstart='0';
$limitend='100'
?> 
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/sensor_alert_log/'.$startdate.'/'.$datenow.'/'.$limitstart.'/'.$limitend.'');?>" target="_blank">Sensor Alert Log</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/sensor_config');?>" target="_blank">Sensor Config</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<?php 

$startdate='2015-01-01';
$datenow=date('Y-m-d');
$order='desc';
$limitstart='0';
$limitend='100'

// http://localhost/tmonci/apihw/sensor_log/HW1/$hardware='';1/2015-04-26 21:08:20/2015-05-07 01:08:20/0/100/asc/
?> 
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/sensor_log/'.$startdate.'/'.$datenow.'/'.$order.'/'.$limitstart.'/'.$limitend.'');?>" target="_blank">Sensor  Log</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
 

<?php #######################################?>
<?php 
$hardware='HW1';
$sensor='sensor1';
$startdate='2015-01-01';
$datenow=date('Y-m-d');
$order='desc';
$limitstart='0';
$limitend='100'

// http://localhost/tmonci/apihw/sensor_log/HW1/$hardware='';1/2015-04-26 21:08:20/2015-05-07 01:08:20/0/100/asc/
?> 
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/sensor_log_all/'.$hardware.'/'.$sensor.'/'.$startdate.'/'.$datenow.'/'.$order.'/'.$limitstart.'/'.$limitend.'');?>" target="_blank">Sensor  Log All</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/sensor_type');?>" target="_blank">Sensor Type</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>

<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/sms_lists');?>" target="_blank">SMS</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/waterleak_log_all');?>" target="_blank">Waterleak Log All</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>
<?php #######################################?>
<?php 

$startdate='2015-01-01';
$datenow=date('Y-m-d');
$order='desc';
$limitstart='0';
$limitend='100'

// http://localhost/tmonci/apihw/sensor_log/HW1/$hardware='';1/2015-04-26 21:08:20/2015-05-07 01:08:20/0/100/asc/
?> 
<tr>
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>		
		<a href="<?php echo base_url('apihw/waterleak_log/'.$startdate.'/'.$datenow.'/'.$order.'/'.$limitstart.'/'.$limitend.'');?>" target="_blank">Waterleak Log</a>			</td>
	<td class="hidden-480">
	<span class="label label-sm label-success">OK</span>												</td>
</tr>

<?php 
$ipaddress='192.168.10.221';
?>
<tr> 
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>	
		<a href="<?php echo base_url('apihw/hw/'.$ipaddress.'');?>" target="_blank">HW1 Sensor Temp&Humi</a>				</td>
	<td class="hidden-480">
		<span class="label label-sm label-warning">OK</span>										
	</td>
</tr>

<?php 
$ipaddress='192.168.10.222';
?>
<tr> 
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>	
		<a href="<?php echo base_url('apihw/hw/'.$ipaddress.'');?>" target="_blank">HW2 Sensor Temp&Humi</a>				</td>
	<td class="hidden-480">
		<span class="label label-sm label-warning">OK</span>										
	</td>
</tr>
<?php 
$ipaddress='192.168.10.224';
?>
<tr> 
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>	
		<a href="<?php echo base_url('apihw/hw/'.$ipaddress.'');?>" target="_blank">HW4 Sensor Wetor leak</a>				</td>
	<td class="hidden-480">
		<span class="label label-sm label-warning">OK</span>										
	</td>
</tr>
<?php 
$ipaddress='192.168.10.223';
?>
<tr> 
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>	
		<a href="<?php echo base_url('apihw/hw2/'.$ipaddress.'');?>" target="_blank">HW3 Reley IO</a>				</td>
	<td class="hidden-480">
		<span class="label label-sm label-warning">OK</span>										
	</td>
</tr>

<tr> 
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i><?php echo' ';?>	
		<a href="<?php echo base_url('apihw/settings_company');?>" target="_blank">Settings Company</a>				</td>
	<td class="hidden-480">
		<span class="label label-sm label-warning">OK</span>										
	</td>
</tr>

<?php
		date_default_timezone_set('Asia/Bangkok');
		$hwname='HW2';
		$sensorname='sensor1';
		//$group='year';
		//$group='month';
		//$group='day';
		$group='hour';
		//$group='minute';
		//$group='second';
		$countdate='1';
		$startdate=date("Y-m-d", strtotime("-$countdate days"));
		$enddate=date('Y-m-d');
		$limit='48';
		$getall=$hwname.'/'.$sensorname.'/'.$group.'/'.$startdate.'/'.$enddate.'/'.$limit;
			
?>


<tr> 
	<td><i class="ace-icon fa fa-search-plus bigger-120"></i>	
		<a href="<?php echo base_url('apihw/sensor_chart/'.$getall);?>" target="_blank">Sensor Chart</a>				</td>
	<td class="hidden-480">
		<span class="label label-sm label-warning">OK</span>										
	</td>
</tr>



</tbody>
</table>
	</div><!-- /.span -->
</div>