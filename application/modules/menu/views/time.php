<table width="353" border="0" cellpadding="2" cellspacing="1" bgcolor="#CCCCCC">
  <tr bgcolor="#FFFFFF">
    <td width="105" bgcolor="#FFFFFF"><span class="style1">Server Time </span></td>
    <td width="237" bgcolor="#FFFFFF">
<span class="style1">
<!---------------- Server Time ---------------------->
<div id="server_time" style="background-color:#339999">&nbsp;</div>
<script language="JavaScript1.2">
<!--
function server_date(now_time) {

    current_time1 = new Date(now_time);
    current_time2 = current_time1.getTime() + 1000;
    current_time = new Date(current_time2);
 
    server_time.innerHTML = current_time.getDate() + "/" + (current_time.getMonth()+1) + "/" + current_time.getFullYear() + " " + current_time.getHours() + ":" + current_time.getMinutes() + ":" +current_time.getSeconds();
 
    setTimeout("server_date(current_time.getTime())",1000);
}
 
setTimeout("server_date('<?php echo $current_server_time?>')",1000);
//-->
</script>
<!---------------- Server Time ---------------------->
</span>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td bgcolor="#FFFFFF"><span class="style1">Your Time </span></td>
    <td>
<span class="style1">
<!---------------- Local Time ---------------------->
<div id="local_time" style="background-color:#FFCC00">&nbsp;</div>
<script language="JavaScript1.2">
<!--
function local_date(now_time) {
    current_local_time = new Date();
 
    local_time.innerHTML = current_local_time.getDate() + "/" + (current_local_time.getMonth()+1) + "/" + current_local_time.getFullYear() + " " + current_local_time.getHours() + ":" + current_local_time.getMinutes() + ":" +current_local_time.getSeconds();
 
    setTimeout("local_date()",1000);
}
 
setTimeout("local_date()",1000);
//-->
</script>
<!---------------- Local Time ---------------------->
</span>
    </td>
  </tr>
</table>
 
<center>
<a href="<?php echo base_url('server/gettime') ?>">refresh</a>
</center>