<?php 
$urlnodered=$this->config->item('urlnodered');
$input=@$this->input->post(); 
if($input==null){$input=@$this->input->get();}
$debug=$input['debug'];
ob_end_flush();
$input=@$this->input->get();  
if($input==null){$input=@$this->input->get(); } 
$debug=$input['debug'];
##############################################################################
##############################################################################
##############################################################################
$setting=GetConfig1();
$object=json_decode(json_encode($setting), TRUE);
#echo'<hr><pre>  $object=>';print_r($object);echo'<pre> <hr>';  Die();
$systemname_crna=$object['systemname'];
$description_crna=$object['description'];
$address_crna=$object['address'];
$registerno_crna=$object['registerno'];
$author_crna=$object['author'];
$phone_crna=$object['phone'];
$ip_crna=$object['ip'];
$macaddress_crna=$object['mac_address'];
$licencekey_crna=$object['licence_key'];
$version_crna=$object['version'];
$adminemail_crna=$object['admin_email'];
$mobile_crna=$object['mobile'];
$countries_crna=$object['countries'];
$geography_crna=$object['geography'];
$province_crna=$object['province'];
$amphur_crna=$object['amphur'];
$district_crna=$object['district'];
$moo_crna=$object['moo'];
$facebook_crna=$object['facebook'];
$twitter_crna=$object['twitter'];
$google_crna=$object['google'];
# แปลภาษา
# File THAI --> application\language\thai\app_lang.php
# File English --> application\language\english\app_lang.php	
$admin_id=0;# 0=>เห็นทุกเมนู
$navbar_fix='';
$breadcrumb_fix='';
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
##################################################################
##################################################################
		$admin_id = $this->session->userdata('admin_id');
		$admin_type = $this->session->userdata('admin_type');
		//$ListSelect = $this->Api_model->user_menu($admin_type);
			/////////////cache////////////
			$time_cach_set_min=$this->config->item('time_cach_set_min');
			$time_cach_set=$this->config->item('time_cach_set');
			#$cachetime=$time_cach_set_min;
			$cachetime=60*60*24*365;
			$lang=$this->lang->line('lang'); 
			$langs=$this->lang->line('langs'); 
			$cachekey='ListSelect_menu_'.$lang;
			##Cach Toools Start######
			//cachefile 
			$input=@$this->input->post(); 
			if($input==null){ $input=@$this->input->get();}
			$deletekey=@$input['deletekey'];
			if($deletekey==''){$deletekey=null;}else{$deletekey=1;}
			$cachetype='2'; 
			$this->load->model('Cachtool_model');
			$sql=null;
			$cachechk=$this->Cachtool_model->cachedbgetkey($sql,$cachekey,$cachetime,$cachetype,$deletekey);
			$cachechklist=$cachechk['list'];
			// echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechk);echo'</pre>'; Die();
			if($cachechklist!=null){    // IN CACHE
				$temp = $cachechklist;
				#echo'1 Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
			}else{
				// NOT IN CACHE
				///// ***** เอา FUNCTION ที่ทำงานท่อนเดิม มาใส่ตรงนี้ ******
				#$ListSelect=$this->Api_model_na->user_menu($this->session->userdata('admin_type'));
				$ListSelect= $this->Api_model_na->user_menu($admin_type);
				$sql=null;
				$cachechklist=$this->Cachtool_model->cachedbsetkey($sql,$ListSelect,$cachekey,$cachetime,$cachetype,$deletekey);
				#echo'2 Form SQL <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
				$cachechklist= $this->Api_model_na->user_menu($admin_type);
			}
			$ListSelect=$cachechklist;
	/////////////cache////////////
 #echo '<pre> ListSelect-> '; print_r($ListSelect); echo '</pre>'; die(); 
	$loadfile="admintype".$admin_type.".json";
	$admin_menu=LoadJSON($loadfile);
	#echo '<pre> admin_menu-> '; print_r($admin_menu); echo '</pre>'; die(); 
	
		if(!isset($breadcrumb)) $breadcrumb='';
		if(!isset($ListSelect)) $ListSelect=null;
		######## Cach Toools Start ################################################
				$cachetype=2; $dev=0; $deletekey=0;
				$lang=$this->lang->line('lang'); 
				//	$langs=$this->lang->line('langs'); 
					$cachekey='key-getMenu-'.'admin_type-'.$admin_type.'-lang-'.$lang;
					$cachetime=60*60*60*24*365;
					##Cach Toools Start######
					//cachefile 
					$input=@$this->input->post(); 
					if($input==null){ $input=@$this->input->get();}
					$deletekey=@$input['deletekey'];
					if($deletekey==''){$deletekey=null;}else{$deletekey=1;}
					/*
					$this->load->model('Cachtool_model');
					$cachechk=$this->Cachtool_model->cachedbgetkey($sql=null,$cachekey,$cachetime,$cachetype,$deletekey);
					$cachechklist=$cachechk['list'];
					// echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechk);echo'</pre>'; Die();
					if($cachechklist!=null){
						// IN CACHE
						$cachechklist=$cachechklist;
						#echo' Form Cache <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
					}else{                        
							// NOT IN CACHE
							// เอา FUNCTION ที่ทำงาน SQL มาใส่ตรงนี้ 
							$cachetypedev=1;
							$rs=$this->menufactory->getMenu(0,0,0,$cachetypedev,$deletekey,$dev);
							// ############################# 
							$cachechklist=$this->Cachtool_model->cachedbsetkey($sql=null,$rs,$cachekey,$cachetime,$cachetype,$deletekey);
							// เอา FUNCTION ที่ทำงาน SQL มาใส่ตรงนี้ 
							$cachechklist=$this->menufactory->getMenu(0,0,0,$cachetypedev,$deletekey,$dev);

							#echo' Form Sql <hr> <pre>   cachechklist =>';print_r($cachechklist);echo'</pre>'; Die();
					}
				$admin_menu=$cachechklist;
				$getMenu=$cachechklist;
				*/
		######## Cach Toools END ################################################
##################################################################
##################################################################
       $datenow=strtotime("now");
       $datetomorrow=strtotime("tomorrow");
       $yesterday=strtotime("yesterday");
       $date1day=strtotime("+1 day");
       $date1week=strtotime("+1 week");
       $lastweek=strtotime("lastweek");
       $date1week2day=strtotime("+1 week 2 days 4 hours 2 seconds");
       $datenextthursday=strtotime("next Thursday");
       $datenowlastmonday=strtotime("last Monday");
       $date2pmyesterday=strtotime("2pm yesterday");
       $date7am12daysago=strtotime("7am 12 days ago");
       $yesterday =date("Y-m-d", $yesterday); 
       $time=date('H:i:s');
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
$navbar_fix='navbar-fixed-top';
$breadcrumb_fix='breadcrumbs-fixed';
	//echo $segment1.':'.$web_title.':'.$userinput;
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
	
##############################################################################
##############################################################################
##############################################################################

?>
<!DOCTYPE html>
<html lang="<?php echo $lang;?>">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title> <?php echo $titleweb; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<meta content="" name="<?php  echo $this->lang->line('descriptiontmon');?>" />
		<meta content="" name="<?php  echo $this->lang->line('author');?>" />
		<meta name="keyword" content="tmon,monitoring,hardware,control,acms,sensor,adruno" />
		<!-- bootstrap & fontawesome -->
<?php 
		echo css_asset('bootstrap.min.css'); 
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
		}else if(preg_match('~\b(chart|overview)\b~i', strtolower($this->uri->segment(2)))){
			echo css_asset('morris/morris-0.4.3.min.css');
/*
			echo js_asset('date-time/bootstrap-datepicker.min.js');
			echo js_asset('date-time/bootstrap-timepicker.min.js');
			echo js_asset('date-time/moment.min.js');
			echo js_asset('date-time/daterangepicker.min.js');
			echo js_asset('date-time/bootstrap-datetimepicker.min.js');
*/
		}
?>
		<!-- page specific plugin styles -->
		<?php //echo css_asset('jquery-ui.custom.min.css'); ?>
		<?php echo css_asset('jquery-ui.min.css'); ?>

		<!-- text fonts -->
		<?php echo css_asset('ace-fonts.css'); ?>

		<!-- ace styles -->
 

<?php 
		 echo css_asset('ace.css'); 
		 echo css_asset('ace.min.css'); 
?>
		<!--[if lte IE 9]>
			<?php echo css_asset('ace-part2.min.css'); ?>
		<![endif]-->

		<?php echo css_asset('chosen.css'); ?>
		<?php //echo css_asset('ace-skins.min.css'); ?>
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
<!-- <script type="text/javascript" src="<?php echo base_url('theme/assets/ckeditor/ckeditor.js')?>"></script> -->
<script type="text/javascript" src="<?php echo base_url('plugins/ckeditor-integrated/ckeditor/ckeditor.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('theme/assets/js/main.js')?>"></script>
<style type="text/css">
#gritter-notice-wrapper{display:none;}	
</style>
</head>
<body class="no-skin">
<div id="msg_error" class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert">
		<i class="ace-icon fa fa-times"></i>
	</button>
		<strong><i class="ace-icon fa fa-times"></i></strong>
		<div id="msg_txt"></div>
</div>
<div id="msg_success" class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">
		<i class="ace-icon fa fa-times"></i>
	</button>
	<div id="msg_txt2"></div>
</div>
<div id="msg_info" class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert">
		<i class="ace-icon fa fa-times"></i>
	</button>
	<div id="msg_txt3"></div>
</div>
<div id="BG_overlay"></div>

<div id="gritter-notice-wrapper">
	<div id="gritter-item-5" class="gritter-item-wrapper gritter-info gritter-center" style="opacity: 0; overflow: hidden; height: 94px;">
		<div class="gritter-top"></div>
		<div class="gritter-item">
		<div class="gritter-close" style="display: none;"></div>
		<div class="gritter-without-image"><span class="gritter-title">This is a centered notification</span><p>Just add a "gritter-center" class_name to your $.gritter.add or globally to $.gritter.options.class_name</p></div>
		<div style="clear:both"></div></div>
		<div class="gritter-bottom"></div>
	</div>
</div>

		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default <?php echo $navbar_fix ?>">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
					<span class="sr-only"><?php echo $togglesidebar;?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left">
					<a href="<?php echo base_url('overview');?>" class="navbar-brand" id="tmon">
						<small>
							<img src="<?=base_url('images/logo-tmon.png')?>" height="25" />
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->
					<!-- #section:basics/navbar.toggle -->
					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
<?php							
						$listmax=sizeof($this->uri->segments);
						if($this->session->userdata('admin_id') == 1){
?>
						<!-- Task Mail -->
						<li class="brown"  id="task_segments">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-info-circle"></i>
								<span class="badge badge-success"><?php echo $listmax?></span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="ace-icon fa fa-info-circle"></i>
									Segments
								</li>

								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar">
						<?php							
								$listmax=sizeof($this->uri->segments);
								if($listmax > 0){
										for($i=0;$i<$listmax;$i++){
											$num=$i+1;
											$displaytxt=($this->uri->segment($num)) ? $this->uri->segment($num) : '' ;
											$HeadTitle=ucfirst($displaytxt);
											/*if($i == ($listmax - 1)) 
												echo '<li>'.$HeadTitle;
											else
												echo '<li><a href="#">'.$HeadTitle.'</a>';
											echo '</li>';*/
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
														<i class="ace-icon fa fa-clock-o"></i>
														<span>a moment ago</span>
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

								<!-- <li class="dropdown-footer">
									<a href="inbox.html">
										See all messages
										<i class="ace-icon fa fa-arrow-right"></i>
									</a>
								</li> -->
							</ul>
						</li>

<?php $num_queries=count($this->db->queries);?>
						<!-- Task -->
						<li class="grey" id="task_query">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-tasks"></i>
								<span class="badge badge-grey"><?php echo $num_queries ?></span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close" style="width:800px">
								<li class="dropdown-header">
									<i class="ace-icon fa fa-check"></i>
									SQL Query
								</li>

								<li><code><?php echo "total_execution_time_start=".$total_execution_time_start?></code></li>
 
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
								<li class="dropdown-footer">
									<a href="#">
										See tasks with details
										<i class="ace-icon fa fa-arrow-right"></i>
									</a>
								</li>
							</ul>
						</li>
<?php } //$this->session->userdata('admin_id') 1,3  ?>
<?php
/*****************Notifications Lang*********************/
?>
		<li class="purple">  
				<a data-toggle="dropdown" class="dropdown-toggle" href="#"> 
					 <?php if($lang=='th'){?>
						<img src="<?=base_url('images/lang/th.png')?>">
					 <?php }else if($lang=='en' || $lang==''){?>
						<img src="<?=base_url('images/lang/en.png')?>">
					 <?php }?>:
					 <?php echo $langs;?>
				 </a>
				<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<div align="center">
								<a href="<?=base_url()?>lang/language?lang=english&uri=<?php print(uri_string()); ?>" > <img src="<?=base_url('theme/assets/images/lang/en.png')?>" onClick="lanfTrans('en');" height="25" title="To English"> <?php echo 'English'; ?></a>
							</div>
						</li>
						<li class="divider"></li>
						<li>
							<div align="center">
								<a href="<?=base_url()?>lang/language?lang=thai&uri=<?php print(uri_string()); ?>" ><img src="<?=base_url('theme/assets/images/lang/th.png')?>" onClick="lanfTrans('th');"  height="25" title="To Thai"> <?php echo 'Thai'; echo'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';?></a>
							</div>
						</li>
				</ul>
		</li>
						<!-- #section:basics/navbar.user_menu -->
						<?php
						//Debug($this->session->userdata);

						if($this->session->userdata['avatar'] != ''){
									$avatar=base_url('uploads/admin/'.$this->session->userdata['avatar']);
									//if(!file_exists($avatar)) $avatar=base_url('uploads/admin/'.$this->session->userdata['avatar']);
									//if(!file_exists($avatar)) $avatar=base_url('theme/assets/avatars/user.jpg');
						}else if($this->session->userdata['avatar'] == ''){
									$avatar=base_url().'theme/system/systmon.png';	
						}else{
						    $avatar=base_url().'theme/system/systmon.png';	
						}	
						?>
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="<?php echo $avatar; ?>" alt="<?php echo $userinput?>'s Photo" />
								<span class="user-info">
									<small> <?php echo $welcome; ?> </small>
									<?php echo $userinput?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<!-- <li>
									<a href="<?php echo $this->config->config['www']; ?>" target=_blank>
										<i class="ace-icon fa fa-globe"></i><?php echo $website; ?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?php echo base_url('setting'); ?>">
										<i class="ace-icon fa fa-cog"></i><?php echo $settings; ?>
									</a>
								</li>
								<li class="divider"></li> -->
<?php 
	$alinkprofile=base_url('admin/profile'); 
?>
<?php if($admin_type=='1'){?>
								<li>
									<a href="<?php echo base_url('setting'); ?>">
										<i class="ace-icon fa fa-cog"></i><?php echo $settings; ?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?php echo base_url('accessmenu'); ?>">
										<i class="ace-icon fa fa-globe"></i><?php echo $this->lang->line('admin_menu'); ?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?php echo base_url('admin/memberlist'); ?>">
										<i class="ace-icon fa fa-globe"></i><?php echo $this->lang->line('member'); ?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?php echo base_url('userlogs'); ?>">
										<i class="ace-icon fa fa-globe"></i><?php echo $this->lang->line('userlogs'); ?>
									</a>
								</li>
								<li class="divider"></li>
                               <li>
									<a href="<?php echo base_url('user_guide'); ?>"target="_blank">
										<i class="ace-icon fa fa-user"></i><?php echo $this->lang->line('help');;?> 
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


								<li class="divider"></li>
								<li>
									<a href="<?php echo base_url('lock_screen'); ?>">
										<i class="ace-icon fa fa-key"></i><?php echo $this->lang->line('lock_screen'); ?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#" id="logout">
										<i class="ace-icon fa fa-power-off"></i><?php echo $logout; ?>
									</a>
								</li>
<?php }else if($admin_type=='2'){?>
								<li>
									<a href="<?php echo $alinkprofile; echo'/'.$user_id; ?>">
										<i class="ace-icon fa fa-user"></i><?php echo $profile;?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?php echo base_url('admin/memberlist'); ?>">
										<i class="ace-icon fa fa-globe"></i><?php echo $this->lang->line('member'); ?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?php echo base_url('userlogs'); ?>">
										<i class="ace-icon fa fa-globe"></i><?php echo $this->lang->line('userlogs'); ?>
									</a>
								</li>
								<li class="divider"></li>
                               <li>
									<a href="<?php echo base_url('user_guide'); ?>"target="_blank">
										<i class="ace-icon fa fa-user"></i><?php echo $this->lang->line('help');;?> 
									</a>
								</li> 
								<li class="divider"></li>
								<li>
									<a href="<?php echo base_url('lock_screen'); ?>">
										<i class="ace-icon fa fa-key"></i><?php echo $this->lang->line('lock_screen'); ?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#" id="logout">
										<i class="ace-icon fa fa-power-off"></i><?php echo $logout; ?>
									</a>
								</li>
<?php }else if($admin_type<>'1' || $admin_type<>'2'){?>
								<li>
									<a href="<?php echo $alinkprofile; echo'/'.$user_id; ?>">
										<i class="ace-icon fa fa-user"></i><?php echo $profile;?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?php echo base_url('lock_screen'); ?>">
										<i class="ace-icon fa fa-key"></i><?php echo $this->lang->line('lock_screen'); ?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#" id="logout">
										<i class="ace-icon fa fa-power-off"></i><?php echo $logout; ?>
									</a>
								</li>
<?php }?>
							</ul>
						</li>
						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>
				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>

<?php
		//echo "total_execution_time_start=".$total_execution_time_start;
		if(function_exists('Debug')){
			//Debug($notification_birthday) ;
			//Debug($this->session->all_userdata());
			//Debug($this->benchmark->marker) ;
		}
		//die();
?>
		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar sidebar-fixed responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

<?php if($this->session->userdata['admin_type'] <= 2){ ?>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success" onClick="window.location='<?php echo site_url('overview') ?>';">
							<i class="ace-icon fa fa-signal"></i>
						</button>
						<?php if($this->session->userdata['admin_type'] == 1){ ?>
						<button class="btn btn-info" onclick="window.location='<?php echo site_url('accessmenu') ?>';">
						<?}else if($this->session->userdata['admin_type'] <> 1){ ?>
						<button class="btn btn-info" onClick="window.location='#';">
						<?}?>
							<i class="ace-icon fa fa-pencil"  ></i>
						</button>
						<?php if($this->session->userdata['admin_type'] == 1 || $this->session->userdata['admin_type'] == 2){ ?>
						<button class="btn btn-warning"  onclick="window.location='<?php echo site_url('admin/memberlist') ?>';">
						<?}else if($this->session->userdata['admin_type'] <> 1 ||$this->session->userdata['admin_type'] <> 2){ ?>
						<button class="btn btn-danger" onClick="window.location='#';">
						<?}?>
							<i class="ace-icon fa fa-users"></i>
						</button>

						<?php if($this->session->userdata['admin_type'] == 1){ ?>
						<button class="btn btn-danger" onclick="window.location='<?php echo site_url('setting') ?>';">
						<?}else if($this->session->userdata['admin_type'] <> 1){ ?>
						<button class="btn btn-danger" onClick="window.location='#';">
						<?}?>
						
							<i class="ace-icon fa fa-cog"></i>
						</button>
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
							<span class="btn btn-success"></span>
							<span class="btn btn-info"></span>
							<span class="btn btn-warning"></span>
							<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

<?php } ?>

				<ul class="nav nav-list">
<?php
/***********Menu**************/
	echo $ListSelect;
?>
				</ul><!--/.nav-list-->

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

<?php
/*
			if (!$this->session->userdata('user_name')) {

			} else {
				$userinput=$this->session->userdata('user_name');
	            <li>
		              <a href=" echo base_url(); member/profile">Member :  echo $userinput</a>
	            </li>

			}
	            <li><a href=" echo base_url(); admin/logout">Logout</a></li>
*/
?>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs <?php echo $breadcrumb_fix ?>" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
					</script>

					<ul class="breadcrumb">
						<li>
							<i class="ace-icon fa fa-home home-icon"></i>
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
											echo '<li>'.$breadcrumb[$i].'</li>';
										}
								}
			}
?>
					</ul><!--.breadcrumb-->

					<!-- #section:basics/content.searchbox -->
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
				case "tmon" : $method=$language['tmon']; break;
				case "column" : $method=$language['column']; break;

				case "elvis" : $method='Elvis'; break;
				default : $method=''; break;		
		}

		if($action_form){
?>					
					<!-- <div class="topbar" id="gotoweb" <?php if($user_id != 1 && $user_id != 3) echo "style='display:none;'"; ?>><i class="menu-icon fa fa-desktop"></i></div> -->
					<div class="nav-search" id="nav-search">
						<form method="post" class="form-search" action="<?php echo base_url($action_form);?>">
							<span class="input-icon">
								<input type="text"  value="<?php echo $keyword?>" name="keyword" placeholder="<?php echo  $language['search'].$method ?>..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
								<i class="ace-icon fa fa-search nav-search-icon"></i>
							</span>
<?php
		if($this->uri->segment(2) != ''){
				$mod=($this->uri->segment(2) != '') ? $this->uri->segment(2) : 'news';
				echo '<input type="hidden" name="mod" value="'.trim($mod).'">';
		}	
?>
						</form>
					</div><!-- /.nav-search -->
					<!--#nav-search-->
<?php } ?>
				</div>

				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content">
					<!-- #section:settings.box -->
<?php
			if($this->config->config['mod_debug'] == 'Yes' || $admin_type=='1'){
##############################  Tools Box
?>
				<div class="ace-settings-container" id="ace-settings-container">
						<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
							<i class="ace-icon fa fa-cog bigger-150"></i>
						</div>

						<div class="ace-settings-box clearfix" id="ace-settings-box">
							<div class="pull-left width-50">
								<div class="ace-settings-item">
									<div class="pull-left">
										<select id="skin-colorpicker" class="hide">
											<option data-skin="no-skin" value="#438EB9">#438EB9</option>
											<option data-skin="skin-1" value="#222A2D">#222A2D</option>
											<option data-skin="skin-2" value="#C6487E">#C6487E</option>
											<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
										</select>
									</div>
									<span>&nbsp; Choose Skin</span>
								</div>

								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
									<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
								</div>

								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
									<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
								</div>

								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
									<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
								</div>

								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
									<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
								</div>

								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
									<label class="lbl" for="ace-settings-add-container">
										Inside
										<b>.container</b>
									</label>
								</div>
							</div>

							<div class="pull-left width-50">
								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
									<label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
								</div>

								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
									<label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
								</div>

								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
									<label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
								</div>

								
							</div>
						</div>
					</div>			
<?php
############################## Tools Box
}
?>
<!-- /section:settings.box -->
<div class="page-content-area">		