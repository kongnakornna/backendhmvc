<div id="page-sidebar">
  <a class="sidebar-toggler sb-toggle" href="#"><i class="fa fa-indent"></i></a>
	<div class="sidebar-wrapper">
<!--###################################-->
				<ul class="nav nav-tabs nav-justified" id="sidebar-tab">
					<li class="active">
						<a href="#users" role="tab" data-toggle="tab"><i class="fa fa-users"></i></a>
					</li>
					<li>
						<a href="#favorites" role="tab" data-toggle="tab"><i class="fa fa-heart"></i></a>
					</li>
					<li>
						<a href="#settings" role="tab" data-toggle="tab"><i class="fa fa-gear"></i></a>
					</li>
				</ul>
<!--###################################-->
				<div class="tab-content">
					<div class="tab-pane active" id="users">
						<div class="users-list">
							<h5 class="sidebar-title"><?php echo $this->lang->line('notify');?></h5>
							<ul class="media">							
								<i class="ace-icon fa fa-square bigger-110"></i>
								<?php echo $this->lang->line('name'); ?> <?php  echo "<b><font color='Red'> Debug Code </font></b>";?>
								<br/>
								<i class="ace-icon fa fa-square bigger-110"></i>
								Detail list
								<br/>
<?php 
$admin_id = $this->session->userdata('admin_id');
$admin_type = $this->session->userdata('admin_type');
$urlnodered=$this->config->item('urlnodered');
echo 'urlnodered->'.$urlnodered;
$input=@$this->input->post(); 
if($input==null){$input=@$this->input->get();}

if($admin_type==1){
$debug=$input['debug'];
$debug=2;
 if($debug==2){
 // $this->load->library('Sessioncookie_library');	    
 echo'<hr>';
	$SESSION=$_SESSION;
	echo'<pre> @SESSION =>';
	// print_r($SESSION);echo'</pre>';
	 var_dump($SESSION);
	$COOKIE=$_COOKIE;
	echo'<pre> @COOKIE =>';
	// print_r($COOKIE);echo'</pre>';
    var_dump($COOKIE);
 echo'<hr>';
 } 
 
}
?>
							</ul>
						</div>
					</div>
<!--###################################-->		
					<div class="tab-pane" id="favorites">
						<div class="users-list">
							<h5 class="sidebar-title">demo</h5>
							<ul class="media">								 
								<div class="sidebar-content">
								<label>
									<i class="ace-icon fa fa-square bigger-110"> var </i>  
									<br/>
									<i class="ace-icon fa fa-bullseye bigger-110">   demo  </i>								  
								</label>
								</div>
								<hr/>							 
							</ul>
						</div>
					</div>
<!--###################################-->					
					<div class="tab-pane" id="settings">						 
						<h5 class="sidebar-title"><?php echo $this->lang->line('licencedata'); ?></h5>
						<ul class="media-list">
							<li class="media">
							<!--###################################-->
								<div class="checkbox sidebar-content">
									<label>
										<i class="ace-icon fa fa-square bigger-110"> var </i> 
										<br/>
										<i class="ace-icon fa fa-bullseye bigger-110"> demo </i>    
									</label>
								</div>
							<!--###################################-->	
							</li>
						</ul>
					</div>
<!--###################################-->
	</div>
</div>