<?php
$segment1=$this->uri->segment(1);
$segment2=$this->uri->segment(2);
$segment3=$this->uri->segment(3);
$segment4=$this->uri->segment(4);
$segment5=$this->uri->segment(5);
$segment6=$this->uri->segment(6);
$segment7=$this->uri->segment(7);
$segment8=$this->uri->segment(8);
$segment9=$this->uri->segment(9);
$segment10=$this->uri->segment(10);


?>
Header <?php echo $this->lang->line('titleweb'); ?>
		<p>
<?php 
	
echo $this->lang->line('hi');echo '<hr>';
$base_url=$this->config->item('base_url');
echo $base_url;
echo '<hr>';
$uri_string=uri_string();
?>


 
<p><a href="<?php echo $base_url;?>/lang/language?lang=english&uri=<?php print(uri_string()); ?>" > English</a></p>
<p><a href="<?php echo $base_url;?>/lang/language?lang=thai&uri=<?php print(uri_string()); ?>" > Thai</a></p>
 
		</p>
<Hr>