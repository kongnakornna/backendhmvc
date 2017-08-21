<?php
$language=$this->lang->language;
$lang=$this->lang->line('lang');
$langs=$this->lang->line('langs');
$dashboard=$this->lang->line('dashboard');
$welcome=$this->lang->line('welcome');
$settings=$this->lang->line('settings');
$preview=$this->lang->line('preview');
$website=$this->lang->line('website');
$profile=$this->lang->line('profile');
$logout=$this->lang->line('logout');
$titleweb=$this->lang->line('titleweb');
$apps=$this->lang->line('apps');
$company=$this->lang->line('company');
$login=$this->lang->line('login');
$username=$this->lang->line('username');
$password=$this->lang->line('password');
$remember=$this->lang->line('remember');
$forgot=$this->lang->line('forgot');
$email=$this->lang->line('email');
$sendemail=$this->lang->line('sendemail');
$register=$this->lang->line('register');
$reset=$this->lang->line('reset');
$petrieveassword=$this->lang->line('petrieveassword');
$enteryouremailandtoreceiveinstructions=$this->lang->line('enteryouremailandtoreceiveinstructions');
$backtologin=$this->lang->line('backtologin');
$newuserregistration=$this->lang->line('newuserregistration');
$enteryourdetailstobegin=$this->lang->line('enteryourdetailstobegin');
$useragreement=$this->lang->line('useragreement');
$iaccept=$this->lang->line('iaccept');
$dark=$this->lang->line('dark');
$blur=$this->lang->line('blur');
$light=$this->lang->line('light');
$allrightsreserved=$this->lang->line('allrightsreserved');
$home=$this->lang->line('home');
$admin=$this->lang->line('admin');
$togglesidebar=$this->lang->line('togglesidebar');
$web_title=$titleweb;
	if(!$this->session->userdata('user_name')) {					
		redirect('login/login');
		die();
	}else{
		$userinput=$this->session->userdata('user_name');
		$user_id= $this->session->userdata('admin_id');
		$user_name =$userinput;
		$admin_type=$this->session->userdata('admin_type');
		$name=$this->session->userdata('name');
		$lastname=$this->session->userdata('lastname');
		$email=$this->session->userdata('email');
		$domain=$this->session->userdata('domain');
		$department=$this->session->userdata('department');	
		$admin_password=$this->session->userdata('admin_password');
	}
	$total_execution_time_start=$this->benchmark->marker['total_execution_time_start'];
	$attr=array();

$web_title=$titleweb;
######################
if($lang=='th'){
	$langs_th='ภาษาไทย';
	$langs_en='ภาษาอังกถษ';
}else if($lang=='en'){
	$langs_th='Thai';
	$langs_en='English';
}
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
#######################
$strDate=date('Y-m-d H:i:s');
function DateThai($strDate){
		$strYear=date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut=Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
	}
		$strMonth1= date("n",strtotime($strDate));
		$strMonthCut1=Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai1=$strMonthCut1[$strMonth1];
	    $strYear2=date("Y",strtotime($strDate))+543;
		$strHour3= date("H",strtotime($strDate));
		$strMinute3= date("i",strtotime($strDate));
		$strSeconds3= date("s",strtotime($strDate));
        $timena=$strHour3.':'.$strMinute3.':'.$strSeconds3;
		$strYear4=date("Y",strtotime($strDate))+543;
		$strMonth4= date("n",strtotime($strDate));
		$strDay4= date("j",strtotime($strDate));
		$strMonthCut=Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai3=$strMonthCut[$strMonth4];
		$datena=$strDay4.' '.$strMonthThai3.' '.$strYear4;
	####################
	//echo $segment1.':'.$web_title.':'.$userinput;
?>
<!DOCTYPE html>
<!-- Template Name: Clip-One - Responsive Admin Template build with Twitter Bootstrap 3.x Version: 1.4 Author: ClipTheme -->
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="<?php echo $lang;?>" class="no-js">
	<!--<![endif]-->
	<!-- start: HEAD -->
	<head>
		<title><?php echo $this->lang->line('titleweb');?></title>
		<!-- start: META -->
		<meta charset="utf-8" />
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="descriptiontmon" name="<?php  echo $this->lang->line('descriptiontmon');?>" />
		<meta content="author" name="<?php  echo $this->lang->line('author');?>" />		
		<meta name="keyword" content="tmon,monitoring,hardware,control,acms,sensor,adruno" />
		<!-- end: META -->
		<!-- start: MAIN CSS -->
 
<!-- Basic Styles -->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url('theme');?>/assets/css/bootstrap.min.css">
<!--
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url('theme');?>/assets/css/font-awesome.min.css">
-->
<!-- SmartAdmin Styles  Start-->

 
	<!-- Start:MAIN CSS -->
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/plugins/bootstrap/css/bootstrap.css">
	<!--<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/plugins/font-awesome/css/font-awesome.min.css">-->
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/fonts/style.css">
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/css/main.css">
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/css/main-responsive.css">
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/plugins/iCheck/skins/all.css">
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css">
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css">
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/css/theme_light.css" type="text/css" id="skin_color">
<?php
 
			echo css_asset('bootstrap-editable.css');
			echo css_asset('validate.css'); 
			echo css_asset('datepicker.css');
			echo css_asset('bootstrap-timepicker.css');
			echo css_asset('daterangepicker.css');
			echo css_asset('bootstrap-datetimepicker.css');
			echo css_asset('colorpicker.css');
			echo css_asset('aceclip.css'); 
 
?>
	<!--
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/css/theme_dark.css" type="text/css" id="skin_color">
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/css/theme_green.css" type="text/css" id="skin_color">
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/css/theme_navy.css" type="text/css" id="skin_color">
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/css/theme_light.css" type="text/css" id="skin_color">
	-->
	
	<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/css/print.css" type="text/css" media="print"/>
	<!-- End: MAIN CSS TMON-->
	<!-- end: MAIN CSS -->
 <?php 	if(preg_match('~\b(sensor|sensorreport)\b~i', strtolower($this->uri->segment(1)))){?>
		<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('theme');?>/assets/plugins/select2/select2.css" />
		<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/plugins/DataTables/media/css/DT_bootstrap.css" />
		<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
 
<?php }else if(preg_match('~\b(sensor|sensorreport)\b~i', strtolower($this->uri->segment(1)))){?>
		<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
		<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/plugins/colorbox/example2/colorbox.css">
<?php }else if(preg_match('~\b(overview)\b~i', strtolower($this->uri->segment(1)))){?>

		<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url('theme');?>/assets/css/smartadmin-production.css">
 
<?php }?>
<!-- bootstrap & fontawesome -->
<?php 
		//echo css_asset('bootstrap.min.css'); 
		echo css_asset('font-awesome.min.css');
		/* page specific plugin styles */
		//Member
		if(preg_match('~\b(memberadd|memberedit|profile)\b~i', strtolower($this->uri->segment(2)))){

			echo css_asset('select2.css');
			//echo css_asset('chosen.css');
			echo css_asset('datepicker.css');
			echo css_asset('bootstrap-editable.css');
			echo css_asset('validate.css'); 

		}else if(preg_match('~\b(add|edit|import_csv)\b~i', strtolower($this->uri->segment(2)))){
		//Form
			//echo css_asset('chosen.css');
			echo css_asset('datepicker.css');
			echo css_asset('bootstrap-timepicker.css');
			echo css_asset('daterangepicker.css');
			echo css_asset('bootstrap-datetimepicker.css');
			echo css_asset('colorpicker.css');
		}else if(preg_match('~\b(chart)\b~i', strtolower($this->uri->segment(2)))){
			//echo css_asset('morris/morris-0.4.3.min.css');
/*
			echo js_asset('date-time/bootstrap-datepicker.min.js');
			echo js_asset('date-time/bootstrap-timepicker.min.js');
			echo js_asset('date-time/moment.min.js');
			echo js_asset('date-time/daterangepicker.min.js');
			echo js_asset('date-time/bootstrap-datetimepicker.min.js');
*/
?>

<link rel="stylesheet" href="<?php echo base_url('theme');?>/assets/plugins/fullcalendar/fullcalendar/fullcalendar.css">
<?php
		}
?>
		<!-- page specific plugin styles -->
		<?php //echo css_asset('jquery-ui.custom.min.css'); ?>
		<?php echo css_asset('jquery-ui.min.css'); ?>

		<!-- text fonts -->
		<?php echo css_asset('ace-fonts.css'); ?>

<!-- ace styles 
<link rel="stylesheet" href="<?php echo base_url('theme/assets/css/ace.min.css'); ?>" id="main-ace-style" />
<?php 
		 echo css_asset('ace.css'); 
		 echo css_asset('ace.min.css'); 
?>
-->

		<!--[if lte IE 9]> 
			<?php echo css_asset('ace-part2.min.css'); ?>
		<![endif]-->

		<?php echo css_asset('chosen.css'); ?>
		<?php echo css_asset('ace-skins.min.css'); ?>
		<?php echo css_asset('ace-rtl.min.css'); ?>
		<?php echo css_asset('msgalert.css'); ?>

		<!--[if lte IE 9]>
		  <?php echo css_asset('ace-ie.min.css'); ?>
		<![endif]-->

		<!-- inline styles related to this page -->

		<?php echo css_asset('jquery.gritter.css'); ?>
		<?php echo css_asset('colorbox.css'); ?>
		<?php echo css_asset('dataTables/dataTables.bootstrap.css'); ?>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
<script language="javascript" type="text/javascript">
 function lanfTrans(lan){
   switch(lan){
	   case 'en': document.getElementById('dlang').value='en';document.langForm.submit(); break;
	   case 'th': document.getElementById('dlang').value='th'; document.langForm.submit(); break;
   } 
 }
</script>
<style type="text/css">
.dataTables_wrapper .row .col-sm-6{width: 50%;}
.topbar{top: 6px;position: absolute;right: 100px;line-height: 24px;}
#gotoweb{right: 190px;cursor:pointer;}
button[type="reset"] {display:none;}
/*#task_query, #task_segments {display:none;}*/
/* alert debug block */
</style>
		<!--[if lte IE 8]>
		<?php echo js_asset('html5shiv.min.js'); ?>
		<?php echo js_asset('respond.min.js'); ?>
		<![endif]-->
<link rel="shortcut icon" href="<?php echo base_url(); ?>/images/favicon.ico">

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url('theme/assets/js/jquery.min.js'); ?>'>"+"<"+"/script>");
		</script>
		<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

		<!-- ace settings handler -->		
		<?php echo js_asset('ace.min.js'); ?>
		<?php echo js_asset('ace-extra.min.js'); ?>

<script>
$(document).ready(function(){
<?php
	if(isset($webtitle)){
		$webtitle=chkAscii($webtitle);
		$webtitle=str_replace("&#33;", "", $webtitle);
		$webtitle=str_replace("'", "", $webtitle);
		$webtitle=str_replace("\"", "", $webtitle);
		$webtitle=str_replace(";", "", $webtitle);
		$webtitle=$webtitle."  - ".$language['titleweb'];
		echo "document.title=\"".$webtitle."\";"; 
	}
?>
<?php if(isset($error) || isset($success)){ ?>
		setTimeout(function(){
				$(".alert").fadeOut("slow", function () {
					$(".alert").remove();
				});
		}, 7000);
<?php } ?>
		$('#BG_overlay').on('click', function() {
				$("#msg_success,#msg_error,#BG_overlay").delay(700).fadeOut();
		});
});
</script>
<!--
<script type="text/javascript" src="<?php echo base_url('theme/assets/ckeditor/ckeditor.js')?>"></script>  
-->
<script type="text/javascript" src="<?php echo base_url('plugins/ckeditor-integrated/ckeditor/ckeditor.js')?>"></script>
<!--
<script type="text/javascript" src="<?php echo base_url('theme/assets/js/main.js')?>"></script>
-->
<style type="text/css">
#gritter-notice-wrapper{display:none;}	
</style>
</head>




	<!-- end: HEAD -->
	<!-- start: BODY -->
	<body>
		<!-- start: HEADER -->
		<div class="navbar navbar-inverse navbar-fixed-top">
			<!-- start: TOP NAVIGATION CONTAINER -->
			<div class="container">
				<div class="navbar-header">
					<!-- start: RESPONSIVE MENU TOGGLER -->
					<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
						<span class="clip-list-2"></span>
					</button>
					<!-- end: RESPONSIVE MENU TOGGLER -->
					<!-- start: LOGO -->
					<a class="navbar-brand" href="<?php echo base_url();?>">
					<img src="<?=base_url('images/logobloe.png')?>" height="40"alt="<?php echo $this->session->userdata('titleweb');?>"/> 
                  <!-- 
				  <i class="clip-clip"></i> 
				  -->
					</a>
					<!-- end: LOGO -->
				</div>
				<div class="navbar-tools">
					<!-- start: TOP NAVIGATION MENU -->
					<ul class="nav navbar-right">
<?php							
						$listmax=sizeof($this->uri->segments);
						if($this->session->userdata('admin_id') == 1 || $this->session->userdata('admin_id') == 3){
?>
						<!-- start: TO-DO DROPDOWN -->
						<li class="dropdown">
							<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
								<i class="clip-list-5"></i>
								<span class="badge"> url</span>
							</a>
							<ul class="dropdown-menu todo">
<?php							
 $listmax=sizeof($this->uri->segments);
	 if($listmax > 0){
		 for($i=0;$i<$listmax;$i++){
			 $num=$i+1;
			 $displaytxt=($this->uri->segment($num)) ? $this->uri->segment($num) : '' ;
			 $HeadTitle=ucfirst($displaytxt);
/*
 if($i == ($listmax - 1)) 
		 echo '<li>'.$HeadTitle;
	else
		 echo '<li><a href="#">'.$HeadTitle.'</a>';
		 echo '</li>';
*/
?>
										<li>
											<a href="#">
												<!-- <img src="<?php echo base_url(); ?>assets/avatars/avatar.png" class="msg-photo" alt="" /> -->
												<span class="msg-body">
													<span class="msg-title">
														<span class="blue">Segments <?php echo $num?> :</span>
														<?php echo $HeadTitle?>
														$this->uri->segment(<?php echo $num?>)
													</span>

													<span class="msg-time">
														 <hr>
													</span>
												</span>
											</a>
										</li>
<?php
	 }
 }
?>
							</ul>
						</li>
						<!-- end: TO-DO DROPDOWN-->
<?php $num_queries=count($this->db->queries);?>
						<!-- start: NOTIFICATION DROPDOWN -->
						<li class="dropdown">
							<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
								<i class="clip-notification-2"></i>
								<span class="badge">SQL</span>
							</a>
							<ul class="dropdown-menu notifications">
								<li>
									<span class="dropdown-menu-title"> 
<code><?php echo "total_execution_time_start=".$total_execution_time_start?></code>
									</span>
								</li>
<?php
				if($this->db->queries){
						
						for($q=0;$q<$num_queries;$q++){
								$num=$q+1;
								echo '<li><a href="#"><pre>'.$num.'). '.$this->db->queries[$q].'</pre></a><code>'.$this->db->query_times[$q].'</code></li>';
						}
						
				}

				if(function_exists('Debug')){
						//Debug($this->db->queries) ;
				}
?> 
								<li class="view-all">
									<a href="javascript:void(0)">
										See all notifications <i class="fa fa-arrow-circle-o-right"></i>
									</a>
								</li>
							</ul>
						</li>
<?php } //$this->session->userdata('admin_id') 1,3  ?>
						<!-- end: NOTIFICATION DROPDOWN -->




<!-- start: TO-DO DROPDOWN2 -->
<li class="dropdown">
 <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
  <?php if($lang=='th'){?>
 <img src="<?=base_url('images/lang/th.png')?>">
 <?php }else if($lang=='en' || $lang==''){?>
 <img src="<?=base_url('images/lang/en.png')?>">
 <?php }?>
 <span class="badge"> <?php echo $lang;?></span>
 </a>
 <ul class="dropdown-menu todo">
<li>
	<div>
	 <a href="<?=base_url()?>lang/language?lang=english&uri=<?php print(uri_string()); ?>" > <img src="<?=base_url('theme/assets/images/lang/en.png')?>" onClick="lanfTrans('en');" height="25" title="To English"> <?php echo 'English'; ?></a>
	</div>
	</li>
	<li>
	<div>
	 <a href="<?=base_url()?>lang/language?lang=thai&uri=<?php print(uri_string()); ?>" ><img src="<?=base_url('theme/assets/images/lang/th.png')?>" onClick="lanfTrans('th');"  height="25" title="To Thai"> <?php echo 'Thai'; echo'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';?></a>
	</div>
	</li>
  </ul>
</li>
<!-- end: TO-DO DROPDOWN2-->



						<!-- start: USER DROPDOWN -->
<?php 
	$alinkprofile=base_url('admin/profile'); 
//Debug($this->session->userdata); 
if($this->session->userdata['avatar'] != ''){
	 $avatar=base_url('uploads/admin/'.$this->session->userdata['avatar']);
	 //if(!file_exists($avatar)) $avatar=base_url('uploads/admin/'.$this->session->userdata['avatar']);
	 //if(!file_exists($avatar)) $avatar=base_url('theme/assets/avatars/user.jpg');
 }else{
  $avatar=base_url().'theme/assets/avatars/user.jpg';	
  //$avatar=base_url().'theme/assets/avatars/userna.jpg';
 }
 ?>
						<li class="dropdown current-user">
							<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#"><img src="<?php  echo $avatar; ?>" class="circle-img" alt="<?php echo $userinput?>" height="30">
								<span class="username"> <?php //echo $welcome; ?>	<?php echo $userinput?></span>
								<i class="clip-chevron-down"></i>
							</a>
<?php if($admin_type=='1'){?>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo base_url('overview'); ?>"><i class="clip-screen"></i>
										&nbsp;<?php echo $this->lang->line('overview'); ?></a>
								</li>
								<li>
									<a href="<?php echo base_url('setting'); ?>"><i class="clip-settings"></i>
										&nbsp;<?php echo $this->lang->line('settings'); ?></a>
								</li>
								
								<li>
									<a href="<?php echo base_url('userlogs'); ?>">
										<i class="clip-calendar"></i>
										&nbsp; <?php echo $this->lang->line('userlogs'); ?>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url('admin/memberlist'); ?>">
										<i class="clip-users-2"></i>
										&nbsp; <?php echo $this->lang->line('member'); ?>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url('accessmenu'); ?>">
										<i class="clip-list-2"></i>
										&nbsp;<?php echo $this->lang->line('admin_menu'); ?>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url('user_guide'); ?>"target="_blank">
										<i class="clip-book"></i>
										&nbsp; <?php echo $this->lang->line('help');?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<!-- <a href="<?php echo base_url('hwdata'); ?>"target="_blank">  --->
										<a href="http://127.0.0.1/tmonci/hwdata/index.php"target="_blank">
										<i class="fa fa-tint"></i>
										</i><?php echo'&nbsp;';?><?php echo $this->lang->line('hwdata');?> 
									</a>
								</li> 
 
								<li>
									<a href="<?php echo base_url('lock_screen'); ?>">
										<i class="clip-switch"></i>
										&nbsp; <?php echo $this->lang->line('lock_screen'); ?>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url();?>admin/logout" id="logout">
										<i class="clip-exit"></i>
										&nbsp; <?php echo $logout; ?>
									</a>
								</li>
							</ul>
<?php }else if($admin_type=='2'){?>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo $alinkprofile; echo'/'.$user_id; ?>">
										<i class="clip-user-2"></i>
										&nbsp; <?php //echo $profile;?> <?php echo $userinput?>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url('userlogs'); ?>">
										<i class="clip-calendar"></i>
										&nbsp; <?php echo $this->lang->line('userlogs'); ?>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url('admin/memberlist'); ?>">
										<i class="clip-users-2"></i>
										&nbsp; <?php echo $this->lang->line('member'); ?>
									</a>
								</li>
 
								<li>
									<a href="<?php echo base_url('admin/memberlist'); ?>"><i class="clip-locked"></i>
										&nbsp;<?php echo $this->lang->line('member'); ?></a>
								</li>
 
								<li>
									<a href="<?php echo base_url('lock_screen'); ?>">
										<i class="clip-switch"></i>
										&nbsp; <?php echo $this->lang->line('lock_screen'); ?>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url();?>admin/logout<?php echo base_url();?>admin/logout" id="logout">
										<i class="clip-exit"></i>
										&nbsp; <?php echo $logout; ?>
									</a>
								</li>
							</ul>
<?php }else if($admin_type<>'1' || $admin_type<>'2'){?>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo $alinkprofile; echo'/'.$user_id; ?>">
										<i class="clip-user-2"></i>
										&nbsp; <?php //echo $profile;?> <?php echo $userinput?>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url('userlogs'); ?>">
										<i class="clip-calendar"></i>
										&nbsp; <?php echo $this->lang->line('userlogs'); ?>
									</a>
								</li>
 
								<li>
									<a href="<?php echo base_url('lock_screen'); ?>">
										<i class="clip-switch"></i>
										&nbsp; <?php echo $this->lang->line('lock_screen'); ?>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url();?>admin/logout" id="logout">
										<i class="clip-exit"></i>
										&nbsp; <?php echo $logout; ?>
									</a>
								</li>
							</ul>
<?php }?>
						</li>
						<!-- end: USER DROPDOWN -->
						<!-- start: PAGE SIDEBAR TOGGLE -->
						<li>
							<a class="sb-toggle" href="#"><i class="fa fa-outdent"></i></a>
						</li>
						<!-- end: PAGE SIDEBAR TOGGLE -->
					</ul>
					<!-- end: TOP NAVIGATION MENU -->
				</div>
			</div>
			<!-- end: TOP NAVIGATION CONTAINER -->
		</div>
		<!-- end: HEADER -->




		<!-- start: MAIN CONTAINER -->
		<div class="main-container">
			<div class="navbar-content">
				<!-- start: SIDEBAR -->
				<div class="main-navigation navbar-collapse collapse">
					<!-- start: MAIN MENU TOGGLER BUTTON -->
					<div class="navigation-toggler">
						<i class="clip-chevron-left"></i>
						<i class="clip-chevron-right"></i>
					</div>
					<!-- end: MAIN MENU TOGGLER BUTTON -->




<!-- start: MAIN NAVIGATION MENU -->
<ul class="main-navigation-menu">
<?php
/***********Menu**************/
// loads form api_model
	echo $ListSelect;
?>
</ul>
<!-- end: MAIN NAVIGATION MENU -->





				</div>
				<!-- end: SIDEBAR -->
			</div>




<?php ################################?>

<!-- start: PAGE -->
			<div class="main-content">
				<!-- start: PANEL CONFIGURATION MODAL FORM -->
				<div class="modal fade" id="panel-config" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									&times;
								</button>
								<h4 class="modal-title">Panel Configuration</h4>
							</div>
							<div class="modal-body">
								Here will be a configuration form
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">
									Close
								</button>
								<button type="button" class="btn btn-primary">
									Save changes
								</button>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->
				<!-- end: SPANEL CONFIGURATION MODAL FORM -->
				
				
				
				<div class="container">
					<!-- start: PAGE HEADER -->
					<div class="row">
						<div class="col-sm-12">
<?php  if($admin_type=='1'){?>
							<!-- start: STYLE SELECTOR BOX -->
							<div id="style_selector" class="hidden-xs">
								<div id="style_selector_container">
									<div class="style-main-title">
										Style Selector
									</div>
									<div class="box-title">
										Choose Your Layout Style
									</div>
									<div class="input-box">
										<div class="input">
											<select name="layout">
												<option value="default">Wide</option><option value="boxed">Boxed</option>
											</select>
										</div>
									</div>
									<div class="box-title">
										Choose Your Header Style
									</div>
									<div class="input-box">
										<div class="input">
											<select name="header">
												<option value="fixed">Fixed</option><option value="default">Default</option>
											</select>
										</div>
									</div>
									<div class="box-title">
										Choose Your Footer Style
									</div>
									<div class="input-box">
										<div class="input">
											<select name="footer">
												<option value="default">Default</option><option value="fixed">Fixed</option>
											</select>
										</div>
									</div>
									<div class="box-title">
										Backgrounds for Boxed Version
									</div>
									<div class="images boxed-patterns">
										<a id="bg_style_1" href="#"><img alt="" src="<?php echo base_url('theme');?>/assets/images/bg.png"></a>
										<a id="bg_style_2" href="#"><img alt="" src="<?php echo base_url('theme');?>/assets/images/bg_2.png"></a>
										<a id="bg_style_3" href="#"><img alt="" src="<?php echo base_url('theme');?>/assets/images/bg_3.png"></a>
										<a id="bg_style_4" href="#"><img alt="" src="<?php echo base_url('theme');?>/assets/images/bg_4.png"></a>
										<a id="bg_style_5" href="#"><img alt="" src="<?php echo base_url('theme');?>/assets/images/bg_5.png"></a>
									</div>
									<div class="box-title">
										5 Predefined Color Schemes
									</div>
									<div class="images icons-color">
										<a id="light" href="#"><img class="active" alt="" src="<?php echo base_url('theme');?>/assets/images/lightgrey.png"></a>
										<a id="dark" href="#"><img alt="" src="<?php echo base_url('theme');?>/assets/images/darkgrey.png"></a>
										<a id="black_and_white" href="#"><img alt="" src="<?php echo base_url('theme');?>/assets/images/blackandwhite.png"></a>
										<a id="navy" href="#"><img alt="" src="<?php echo base_url('theme');?>/assets/images/navy.png"></a>
										<a id="green" href="#"><img alt="" src="<?php echo base_url('theme');?>/assets/images/green.png"></a>
									</div>
									<div class="box-title">
										Style it with LESS
									</div>
									<div class="images">
										<div class="form-group">
											<label>
												Basic
											</label>
											<input type="text" value="#ffffff" class="color-base">
											<div class="dropdown">
												<a class="add-on dropdown-toggle" data-toggle="dropdown"><i style="background-color: #ffffff"></i></a>
												<ul class="dropdown-menu pull-right">
													<li>
														<div class="colorpalette"></div>
													</li>
												</ul>
											</div>
										</div>
										<div class="form-group">
											<label>
												Text
											</label>
											<input type="text" value="#555555" class="color-text">
											<div class="dropdown">
												<a class="add-on dropdown-toggle" data-toggle="dropdown"><i style="background-color: #555555"></i></a>
												<ul class="dropdown-menu pull-right">
													<li>
														<div class="colorpalette"></div>
													</li>
												</ul>
											</div>
										</div>
										<div class="form-group">
											<label>
												Elements
											</label>
											<input type="text" value="#007AFF" class="color-badge">
											<div class="dropdown">
												<a class="add-on dropdown-toggle" data-toggle="dropdown"><i style="background-color: #007AFF"></i></a>
												<ul class="dropdown-menu pull-right">
													<li>
														<div class="colorpalette"></div>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<div style="height:25px;line-height:25px; text-align: center">
										<a class="clear_style" href="#">
											Clear Styles
										</a>
										<a class="save_style" href="#">
											Save Styles
										</a>
									</div>
								</div>
								<div class="style-toggle close"></div>
							</div>
							<!-- end: STYLE SELECTOR BOX -->
							
	<?php }?>
							
							<!-- start: PAGE TITLE & BREADCRUMB -->
							<ol class="breadcrumb">
								<li>
									<i class="clip-home-2"></i>
										<a href="<?php echo base_url() ?>"><?php echo $home;?></a>
								</li>
								
<?php
			 //Debug($breadcrumb);
			if(!$breadcrumb){
								$listmax=sizeof($this->uri->segments);
								if($listmax > 0){
										for($i=0;$i<$listmax;$i++){
											$num=$i+1;
											$displaytxt=($this->uri->segment($num)) ? $this->uri->segment($num) : '' ;

											$HeadTitle=ucfirst($displaytxt);
											$lnk=strtolower($displaytxt);

											if($i == ($listmax - 1)) 
												echo '<li>'.$HeadTitle;
											else
												echo '<li><a href="'.base_url($lnk).'">'.$HeadTitle.'</a>';

											echo '</li>';
											
										}
								}
			}else{
								$maxbreadcrumb=sizeof($breadcrumb);
								if($breadcrumb){
										for($i=0;$i<$maxbreadcrumb;$i++){

											//$HeadTitle=ucfirst($displaytxt);
											//$lnk=strtolower($displaytxt);
											echo' <i class="clip-arrow-right-3"></i> ';
											echo '<li>'.$breadcrumb[$i].'</li>';
											
											
											
										}
								}
			}
?>
								
									 
								</li>
								
<?php 
		$keyword='';
		$search_form=$this->input->post();
		
		if($search_form){
			//$keyword=$this->input->post($keyword);			
			$keyword=@$search_form['keyword'];
		}
		//Debug($_SERVER);
		
		if($this->uri->segment(1) == "elvis")
			$action_form=@$_SERVER['PATH_INFO'];
		else
			$action_form=$this->uri->segment(1);

		$method =$this->uri->segment(1);

		switch($method){
				case "news" : $method=$language['news']; break;
				case "column" : $method=$language['column']; break;
				case "gallery" : $method=$language['gallery']; break;
				case "vdo" : $method=$language['vdo']; break;
				case "programtv" : $method=$language['programtv']; break;
				case "dara" : $method=$language['dara']; break;
				case "elvis" : $method='Elvis'; break;
				default : $method=''; break;		
		}

		if($action_form){
?>
								<li class="search-box">
									
									<form method="post" class="sidebar-search" action="<?php echo base_url($action_form);?>">
										<div class="form-group">
											<input  value="<?php echo $keyword?>" name="keyword" placeholder="<?php echo  $language['search'].$method ?>..."class="nav-search-input" id="nav-search-input" autocomplete="on">
											<button class="submit">
												<i class="clip-search-3"></i>
											</button>
										</div>
<?php
		if($this->uri->segment(2) != ''){
				$mod=($this->uri->segment(2) != '') ? $this->uri->segment(2) : 'news';
				echo '<input type="hidden" name="mod" value="'.trim($mod).'">';
		}	
?>
									</form>
								</li>
<?php } ?>
</ol>
<?php /*?>
<div class="page-header">
<h1>
<?php   //echo $HeadTitle?>
<?php 	$modules=$language['modules'];
		echo " <span style=\"color: #030;\">$modules</span> "; 
?>
<small>
<i class="ace-icon fa fa-angle-double-right"></i>
<?php 
if($this->uri->segment(1)=='admin_menu'|| $this->uri->segment(2)=='admin_menu'){
	echo $language['admin_menu'];
}else if($this->uri->segment(1)=='team'|| $this->uri->segment(2)=='team'){
	echo $language['admin_menu'];
}else{
	echo ':::::::::::::::';
}
?> 
</small>
</h1>
</div>
<?php */?>
						<!-- end: PAGE TITLE & BREADCRUMB -->
						</div>
					</div>
					<!-- end: PAGE HEADER -->
					<!-- start: PAGE CONTENT -->
