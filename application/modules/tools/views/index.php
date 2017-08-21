<h2>TOOLS LIST</h2>

<?php 
foreach($list as $k=>$v){
  if($v=='-')
    echo "<hr/><h3>$k</h3>";
  else 
    echo "&nbsp; - <a href='$v' target='_blank'>$k</a><br/>";
}
?>