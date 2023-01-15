<?php
			$language = $this->lang->language; 
			if (!$this->session->userdata('user_name')) {

			} else {
				$userinput = $this->session->userdata('user_name');
			}
   //echo '<pre> admin_menu=>';print_r($admin_menu);echo'</pre>';die();    
   
			//Debug($admin_menu);
			 $langn=$language['lang'];
?>
			<h1><?php //echo $headtxt; ?> <?php //echo $language['welcome'];  echo ' : '; echo $userinput; ?></h1>
			
<?php 
$alinkprofile=base_url('admin/profile'); 
//Debug($this->session->userdata); 
if($this->session->userdata['avatar'] != ''){
	 $avatar=base_url('uploads/admin/'.$this->session->userdata['avatar']);
	 //if(!file_exists($avatar)) $avatar=base_url('uploads/admin/'.$this->session->userdata['avatar']);
	 //if(!file_exists($avatar)) $avatar=base_url('theme/assets/avatars/user.jpg');
 }else{
  $avatar=base_url().'uploads/avatars/user.jpg';	
  //$avatar=base_url().'theme/assets/avatars/userna.jpg';
 }
 ?>
<?php ################################# btndashboard?>
<div id="paginator-content-1" class="alert alert-info">

  <H4>
  <img src="<?php  echo $avatar; ?>" class="circle-img" alt="<?php echo $userinput?>"height="45">
  <?php echo $language['welcome']; ?> 
  <?php 
  	echo $name=$this->session->userdata('name');
	echo'&nbsp;';
	echo $lastname=$this->session->userdata('lastname'); 
  	#echo' (';echo $this->session->userdata('user_name'); echo') ';
  ?>
  </H4> 
  

</div>
<?php #################################?>


<div class="row">
<!--
<?php  if($this->uri->segment(2) == "welcome"){ ?>
            <div class="col-sm-12">
				<p><?php echo $language['welcome']; ?>:</p>
				<code><?php echo $userinput; ?></code>
			</div>
<?php  }   ?>
-->
		<div class="col-sm-12">
					<?php
 						# echo'<hr><pre>  $admin_menu=>';print_r($admin_menu);echo'<pre> <hr>';  // Die();
						if($admin_menu!==Null){
						foreach($admin_menu as $key => $value){
							$admin_menu_id=@$value['admin_menu_id'];
							$title = $value['title_en'];
						#############################################
						#echo'<hr><pre>admin_menu_id=>';print_r($admin_menu_id);echo'</pre> '; echo'<pre>  title=>';print_r($title);echo'</pre>';
								if(isset($admin_menu_id)){
									if($admin_menu_id==1){}else{
											$icon = ($value['admin_menu_id'] == 28) ? 'fa '.$value['icon'] : 'fa '.$value['icon'];
											if($langn=='en'){
												$title = $value['title_en'];
												}else if($langn=='th'){
												$title = $value['title_th'];
												}
											$alink = (trim($value['url']) != '') ? base_url($value['url']) : '#';
											################################
											if($value['parent'] == 0 && $alink != '#'){
													echo '<a href="'.$alink.'"><button class="btn btn-app btn-primary">
														<i class="ace-icon '.$icon.' bigger-330"></i>
														'.$title.'
													</button>
													</a>';
												}
											################################
										}
						#############################################
								}
								#############################
						}		 
					 }
				?>
			</div>
</div>
<?php ##################################################################?>
									<br/>
									<div class="alert alert-warning">
										<button data-dismiss="alert" class="close" type="button">
											&times;
										</button>
										<h4 class="alert-heading"><i class="fa fa-check-circle"></i> 
										<span class="label label-info"> 
										<?php echo $language['selectmenu'] ?>!
										</span>
										</h4>
										<p>
										    <br />
											<?php echo $language['control'] ?>
										</p> 
									</div>
									
									
<?php 


$ci = get_instance(); // CI_Loader instance
$notificationtest=$ci->config->item('notificationtest');
/*
if($notificationtest==1){
?>
 
			<div class="jumbotron">
				<h1>Notification Test </h1>
				<p>
					<button type="button" id="btnstart" class="btn btn-primary btn-lg" role="button">Start</button>
					<button type="button" id="btnstop"  class="btn btn-danger btn-lg" role="button">Stop</button>
				</p>
			</div>

			<div class="row">
				<div class="col-md-12 col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3 class="panel-title">Make Your notification Test</h3>
						</div>
						<div class="panel-body">
						  <form id="preptoast" role="form" class="form-inline">
								<div class="form-group">
									<label class="sr-only" for="toastPriority">Toast Priority</label>
									<select class="form-control" id="toastPriority" placeholder="Priority">
										<option>&lt;use default&gt;</option>
										<option value="success">success</option>
										<option value="info">info</option>
										<option value="warning">warning</option>
										<option value="danger">danger</option>
									</select>
								</div>
								<div class="form-group">
									<label class="sr-only" for="toastTitle">Toast Title</label>
									<input type="text" class="form-control" id="toastTitle" placeholder="Title">
								</div>
								<div class="form-group">
									<label class="sr-only" for="toastMessage">Toast Message</label>
									<input type="text" class="form-control" id="toastMessage" placeholder="Message">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary">  Send Message </button>
								</div>
							</form>
							<br>
							<p>Code:</p>
							<div id="toastCode"></div>
						</div>
					</div>
				</div>
			</div>	 
<?php }*/
$this->load->view('template/notification');
?>
									
									<?php #################################?>
									<!--
									<div class="alert alert-block alert-success fade in">
										<button data-dismiss="alert" class="close" type="button">
											&times;
										</button>
										<img src="<?php #echo base_url();?>theme/assets/systmon.png"/>
										<h4 class="alert-heading">
										<i class="fa fa-check-circle">
										</i> <?php #echo $language['titleweb'] ?>!
										</h4>
											<?php #echo $language['copyright'] ?>
											
										<p>
										    <br/><?php #echo $language['descriptiontmon'] ?>
											<br/><?php #echo $language['author'] ?>
										</p> 
									</div>
									-->
									<?php #################################?>





<?php ############Flot Chat#####################?>
 