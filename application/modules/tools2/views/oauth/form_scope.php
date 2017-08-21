<?php
if(!empty($scopes)){
 foreach($scopes as $v) {
  echo "<label><input type='checkbox' name='scope_".$v["scope"]."' />".$v["scope"]."<label><div>".$v["detail"]."</div>";
 }
}
?>