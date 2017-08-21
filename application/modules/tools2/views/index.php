<h2>TOOLS LIST</h2>

<?php 
foreach($list as $k=>$v){
  if($v=='-')
    echo "<hr/><h3>$k</h3>";
  else 
    echo "&nbsp; - <a href='$v' target='_blank'>$k</a><br/>";
}
?>

<?php 
$logintestweb=$base_url.'tools2/logintest'; 
?>	
 <a href="<?php echo $logintestweb; ?>" target="_blank">   &nbsp; - Test login web/apps </a> 
 <?php 
$logintestweb=$base_url.'api/user/logout'; 
?>	
 <a href="<?php echo $logintestweb; ?>" target="_blank">   &nbsp; - Test logout </a> 