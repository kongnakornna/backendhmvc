<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>

<body>
<?php 
	
echo $this->lang->line('hi');echo '<hr>';
$base_url=$this->config->item('base_url');
echo $base_url;
echo '<hr>';
$uri_string=uri_string();
?>
 

 
<p><a href="<?php echo $base_url;?>/lang/language?lang=english&uri=<?php print(uri_string()); ?>" > English</a></p>
<p><a href="<?php echo $base_url;?>/lang/language?lang=thai&uri=<?php print(uri_string()); ?>" > Thai</a></p>
 
</body>
</html>
