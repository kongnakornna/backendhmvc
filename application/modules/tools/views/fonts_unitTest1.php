<!DOCTYPE html>
<html lang="th">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
	</head>

<!--<meta charset="UTF-8">-->
<style>
	@font-face 
    {
        font-family:'round-b';
        src: url('<?php echo base_url('assets/fontv2/OPTION4/TruePlookpanyaOnline-Bold.otf'); ?>') ;}
	@font-face 
    {
        font-family:'round-reg';
        src: url('<?php echo base_url('assets/fontv2/OPTION4/TruePlookpanyaOnline-Regular.otf'); ?>') ;}
	@font-face 
    {
        font-family:'truetext-b';
        src: url('<?php echo base_url('assets/fontv2/OPTION4/TrueTextBOnline-Bold.otf'); ?>') ;}
	@font-face 
    {
        font-family:'truetext-reg';
        src: url('<?php echo base_url('assets/fontv2/OPTION4/TrueTextBOnline-Regular.otf'); ?>') ;}
		
		
	.txtBold {
	    font-family: "round-b";
	}
	
	.txtH1 {
	    font-family: "round-b";
	    font-size: 32px;
	    /*font-weight: bold;*/
	    /*font-kerning: none;*/
	    /*font-weight: 600;*/
	    /*text-shadow: 1px 0px 0px #000;*/
	    /*letter-spacing: 0.0625em;*/
	}
	
	.clearfix::after {
		content: "";
		clear: both;
		display: table;
	}

</style>
<body>
<div class="clearfix" style="display: block;">
<div style="font-family:round-b;float: left;">
	<div>Original</div>
    <div>font name : TruePlookpanyaOnline-Bold.otf</div>
    <h1>H1 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h1>
    <h2>H2 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h2>
    <h3>H3 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h3>
    <p>Pปกติ เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</p>
	<b>ตัวหนา เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</b>
</div>
<div style="font-family:round-b;float: right;">
	<div>Modified</div>
    <div>font name : TruePlookpanyaOnline-Bold.otf</div>
    <p class="txtH1">H1 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</p>
    <h2>H2 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h2>
    <h3>H3 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h3>
    <p>Pปกติ เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</p>
	<p class="txtBold">ตัวหนา เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</p>
</div>
</div>
<br>
<hr>
<div style="font-family:round-reg;">
    <p>font name : TruePlookpanyaOnline-Regular.otf</p>
    <h1>round-reg  H1 : H1 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง </h1>
    <p class="txtH1">round-b size 32 : H1 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</p>
    <h2>H2 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h2>
    <h3>H3 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h3>
    <p>Pปกติ เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</p>
	<b> round-reg + bold : ตัวหนา เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</b>
	<p class="txtBold"> round-b : ตัวหนา เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</p>
</div>
<hr>
<div style="font-family:truetext-b;">
    <div>font name : TrueTextBOnline-Bold.otf</div>
    <h1>H1 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h1>
    <h2>H2 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h2>
    <h3>H3 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h3>
    <p>Pปกติ เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</p>
	<b>ตัวหนา เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</b>
</div>
<hr>
<div style="font-family:truetext-reg;">
    <p>font name : TrueTextBOnline-Regular.otf</p>
    <h1>H1 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง </h1>
    <h2>H2 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h2>
    <h3>H3 เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</h3>
    <p>Pปกติ เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</p>
	<b>ตัวหนา เชื่อ หรือไม่ การนั่งสมาธิให้ผลดีไม่แพ้การวิ่ง</b>
</div>
</body>
</html>