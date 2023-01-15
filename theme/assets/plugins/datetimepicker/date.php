<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('theme');?>/assets/plugins/datetimepicker/jquery.datetimepicker.css"/>

<style type="text/css">

.custom-date-style {
	background-color: red !important;
}

</style>
<?php 
date_default_timezone_set("Asia/Bangkok");
$y=date('Y');
$m=date('m');
$d=date('d');
$h=date('H');
$i=date('i');
$s=date('s');
$datena=$_REQUEST['datena'];
?>
</head>
<body>
	 
	 
<h3>DateTimePicker</h3>
<form name="form1" method="post" action="index.php">
    <input name="datena" type="text" id="datetimepicker" value="<?php if($datena<>''){echo $datena;}?>"/> 
    <input type="submit" value="save">
</form>
	<?php 
	if($datena<>''){
	echo '$_REQUEST='.$datena;
	}
	?><br><br>
  <h3>DateTimePickers selected by class</h3>
	<input type="text" class="some_class" value="" id="some_class_1"/>
	<input type="text" class="some_class" value="" id="some_class_2"/>
	<h3>Mask DateTimePicker</h3>
	<input type="text" value="" id="datetimepicker_mask"/><br><br>
	<h3>TimePicker</h3>
	<input type="text" id="datetimepicker1"/><br><br>
	<h3>DatePicker</h3>
	<input type="text" id="datetimepicker2"/><br><br>
	<h3>Inline DateTimePicker</h3>
	<!--<div id="console" style="background-color:#fff;color:red">sdfdsfsdf</div>-->
	<input type="text" id="datetimepicker3"/><input type="button" onclick="$('#datetimepicker3').datetimepicker({value:'<?php echo $y;?>-<?php echo $m;?>-11 <?php echo $m;?>:00'})" value="set inline value <?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?> <?php echo $h;?>:<?php echo $i;?>"/><br><br>
	<h3>Button Trigger</h3>
	<input type="text" value="<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?> <?php echo $h;?>:<?php echo $i;?>" id="datetimepicker4"/><input id="open" type="button" value="open"/><input id="close" type="button" value="close"/><input id="reset" type="button" value="reset"/>
	<h3>TimePicker allows time</h3>
	<input type="text" id="datetimepicker5"/><br><br>
	<h3>Destroy DateTimePicker</h3>
	<input type="text" id="datetimepicker6"/><input id="destroy" type="button" value="destroy"/>
	<h3>Set options runtime DateTimePicker</h3>
	<input type="text" id="datetimepicker7"/>
	<p>If select day is Saturday, the minimum set <?php echo $h;?>:<?php echo $i;?>, otherwise <?php echo $h;?>:<?php echo $i;?></p>
	<h3>onGenerate</h3>
	<input type="text" id="datetimepicker8"/>
	<h3>disable all weekend</h3>
	<input type="text" id="datetimepicker9"/>
	<h3>Default date and time </h3>
	<input type="text" id="default_datetimepicker"/>
	<h3>Show inline</h3>
	<a href="javascript:void(0)" onclick="var si = document.getElementById('show_inline').style; si.display = (si.display=='none')?'block':'none';return false; ">Show/Hide</a>
	<div id="show_inline" style="display:none">
		<input type="text" id="datetimepicker10"/>
	</div>
	<h3>Disable Specific Dates</h3>
	<p>Disable the dates 2 days from now.</p>
	<input type="text" id="datetimepicker11"/>
	<h3>Custom Date Styling</h3>
	<p>Make the background of the date 2 days from now bright red.</p>
	<input type="text" id="datetimepicker<?php echo $m;?>"/>
	<h3>Dark theme</h3>
	<p>thank for this <a href="https://github.com/lampslave">https://github.com/lampslave</a></p>
	<input type="text" id="datetimepicker_dark"/>
</body>







<script src="<?php echo base_url('theme');?>/assets/plugins/datetimepicker/jquery.js"></script>
<script src="<?php echo base_url('theme');?>/assets/plugins/datetimepicker/jquery.datetimepicker.js"></script>
<script>/*
window.onerror = function(errorMsg) {
	$('#console').html($('#console').html()+'<br>'+errorMsg)
}*/
$('#datetimepicker').datetimepicker({
dayOfWeekStart : 1,
lang:'th',
disabledDates:['<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?> ','<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?> ','<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?> '],
startDate:	'<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?> '
});
$('#datetimepicker').datetimepicker({value:'<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?>  <?php echo $h;?>:<?php echo $i;?>',step:1});

$('.some_class').datetimepicker();

$('#default_datetimepicker').datetimepicker({
	formatTime:'H:i',
	formatDate:'d.m.Y',
	//defaultDate:'<?php echo $d;?>.<?php echo $m;?>.<?php echo $y;?>', // it's my birthday
	defaultDate:'+<?php echo $d;?>.<?php echo $m;?>.<?php echo $y;?>', // it's my birthday
	defaultTime:'<?php echo $h;?>:<?php echo $i;?>',
	timepickerScrollbar:false
});

$('#datetimepicker10').datetimepicker({
	step:5,
	inline:true
});
$('#datetimepicker_mask').datetimepicker({
	mask:'<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?> <?php echo $h;?>:<?php echo $i;?>'
});

$('#datetimepicker1').datetimepicker({
	datepicker:false,
	format:'H:i',
	step:5
});
$('#datetimepicker2').datetimepicker({
	yearOffset:222,
	lang:'th',
	timepicker:true,
	format:'d-m-Y',
	formatDate:'Y-m-d',
	minDate:'-<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?>', // yesterday is minimum date
	maxDate:'+<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?>' // and tommorow is maximum date calendar
});
$('#datetimepicker3').datetimepicker({
	inline:true
});
$('#datetimepicker4').datetimepicker();
$('#open').click(function(){
	$('#datetimepicker4').datetimepicker('show');
});
$('#close').click(function(){
	$('#datetimepicker4').datetimepicker('hide');
});
$('#reset').click(function(){
	$('#datetimepicker4').datetimepicker('reset');
});
$('#datetimepicker5').datetimepicker({
	datepicker:true,
	allowTimes:['12:00','13:00','15:00','17:00','17:05','17:20','19:00','20:00'],
	step:5
});
$('#datetimepicker6').datetimepicker();
$('#destroy').click(function(){
	if( $('#datetimepicker6').data('xdsoft_datetimepicker') ){
		$('#datetimepicker6').datetimepicker('destroy');
		this.value = 'create';
	}else{
		$('#datetimepicker6').datetimepicker();
		this.value = 'destroy';
	}
});
var logic = function( currentDateTime ){
	if (currentDateTime && currentDateTime.getDay() == 6){
		this.setOptions({
			minTime:'11:00'
		});
	}else
		this.setOptions({
			minTime:'8:00'
		});
};
$('#datetimepicker7').datetimepicker({
	onChangeDateTime:logic,
	onShow:logic
});
$('#datetimepicker8').datetimepicker({
	onGenerate:function( ct ){
		$(this).find('.xdsoft_date')
			.toggleClass('xdsoft_disabled');
	},
	minDate:'-<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?>',
	maxDate:'+<?php echo $y;?>-<?php echo $m;?>-<?php echo $d;?>',
	timepicker:true
});
$('#datetimepicker9').datetimepicker({
	onGenerate:function( ct ){
		$(this).find('.xdsoft_date.xdsoft_weekend')
			.addClass('xdsoft_disabled');
	},
	weekends:['01.01.<?php echo $y;?>','02.01.<?php echo $y;?>','03.01.<?php echo $y;?>','04.01.<?php echo $y;?>','05.01.<?php echo $y;?>','06.01.<?php echo $y;?>'],
	timepicker:true
});
var dateToDisable = new Date();
	dateToDisable.setDate(dateToDisable.getDate() + 2);
$('#datetimepicker11').datetimepicker({
	beforeShowDay: function(date) {
		if (date.getMonth() == dateToDisable.getMonth() && date.getDate() == dateToDisable.getDate()) {
			return [false, ""]
		}

		return [true, ""];
	}
});
$('#datetimepicker<?php echo $m;?>').datetimepicker({
	beforeShowDay: function(date) {
		if (date.getMonth() == dateToDisable.getMonth() && date.getDate() == dateToDisable.getDate()) {
			return [true, "custom-date-style"];
		}

		return [true, ""];
	}
});
$('#datetimepicker_dark').datetimepicker({theme:'dark'})
</script>

</html>
