<html>
<head>
<title>Upload Form</title>
</head>
<body>

<h3>Your file was successfully uploaded!</h3>

<?php //echo $error;?>
<?php //echo $upload_status;?>

<ul>
<?php foreach ($upload_data as $item => $value):?>
<li><?php echo $item;?>: <?php echo $value;?></li>
<?php endforeach; ?>
</ul>

<p><?php echo anchor('upload', 'Upload Another File!'); ?></p>

<?php 
	/*echo "<pre>";
	print_r($this->upload->data());
	echo "</pre>";*/
?>		

</body>
</html>
