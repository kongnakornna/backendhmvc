<?php 
# แปลภาษา
# File THAI --> application\language\thai\app_lang.php
# File English --> application\language\english\app_lang.php	

$lang=$this->lang->line('lang');
$langs=$this->lang->line('langs');
$dashboard=$this->lang->line('dashboard');
$welcome=$this->lang->line('welcome');
$settings=$this->lang->line('settings');
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

$web_title=$titleweb;
######################
if($lang=='th'){
$langs_th='ภาษาไทย';
$langs_en='ภาษาอังกถษ';
}else if($lang=='en'){
$langs_th='Thai';
$langs_en='English';
}
#######################

if($lang == 'en'){
	redirect(base_url('lang/language?lang=thai'));
	die();
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo $titleweb; ?></title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
<?php 
		echo css_asset('bootstrap.min.css'); 
		echo css_asset('font-awesome.min.css');
?>

		<!-- text fonts -->
<?php echo css_asset('ace-fonts.css'); ?>
		<!-- ace styles -->
<?php echo css_asset('ace.min.css'); ?>

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-part2.min.css" />
		<![endif]-->
<?php echo css_asset('ace-rtl.min.css'); ?>

		<!--[if lte IE 9]>
<?php echo css_asset('ace-ie.min.css'); ?>
		<![endif]-->
<?php echo css_asset('ace.onpage-help.css'); ?>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<?php echo js_asset('html5shiv.js'); ?>
		<?php echo js_asset('respond.min.js'); ?>
		<![endif]-->
	<link rel="shortcut icon" href="<?php echo base_url('images/favicon.ico'); ?>">

</head>
<body class="login-layout light-login">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h2>
									<img src="<?=base_url('images/logo.png')?>"  />
								</h2>
								<h4 class="blue" id="id-company-text"><?php if($lang=='en'|| $lang==''){$year=date('Y');}else if($lang=='th'){ $year1=date('Y'); $year=$year1+543;}echo $year;?>&copy; <?php echo $company;?></h4>
							</div>
							<div class="space-6"></div>
							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
												<h5 class="header blue lighter bigger">
													<i class="ace-icon fa fa-users  green"></i><?php echo $login;?>								
												</h5>
											<div class="space-6">
											</div>

<?php 
	$attributes = array('class' => 'form-signin');
	echo form_open('auth', $attributes);
?>
											<fieldset>
											<?php
											      if(isset($message_error)){
													  $show_message = ($message_error != 1) ? $message_error : ' Change a few things up and try submitting again.';
													  echo '<div class="alert alert-danger">
														<strong>
															<i class="ace-icon fa fa-times"></i>
														</strong>'.$show_message.'
														<br></div>';
												  }
											?>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="user_name" class="form-control" placeholder="<?php echo $username;?>" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<select class="form-control" id="form-field-select-1" name="domain" >
																<option value="siamsport.co.th">@siamsport.co.th</option>
																<option value="inspire.co.th" selected>@inspire.co.th</option>
															</select>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="password"  class="form-control" placeholder="<?php echo $password;?>" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<div class="space"></div>
													<div class="clearfix">
														<!-- <label class="inline">
															<input type="checkbox" class="ace" name="remember" value=1 />
															<span class="lbl"> <?php echo $remember;?></span>
														</label> -->

														<button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110"><?php echo $login;?></span>
														</button>
													</div>
													<div class="space-4"></div>
													<input type="hidden" name="backto" value="<?php echo base_url()?>">
												</fieldset>
											<?php echo form_close();?>

										</div><!-- /.widget-main -->

										<div class="toolbar clearfix" style="display:none;">
											<div>
												<a href="#" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													 <?php echo $forgot;?>
												</a>
											</div>

											<div style="display:none;">
												<a href="#" data-target="#signup-box" class="user-signup-link">
													 <?php echo $register;?>
													<i class="ace-icon fa fa-arrow-right"></i>
												</a>
											</div>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->


							</div><!-- /.position-relative -->

						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url(); ?>theme/assets/js/jquery.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo base_url(); ?>theme/assets/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url(); ?>theme/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {

				//$('body').attr('class', 'login-layout light-login');
				//$('#id-text2').attr('class', 'grey');
				//$('#id-company-text').attr('class', 'blue');

				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				e.preventDefault();

				 $(document).on('click', '.toolbar a[data-target]', function(e) {
						e.preventDefault();
						var target = $(this).data('target');
						$('.widget-box.visible').removeClass('visible');//hide others
						$(target).addClass('visible');//show target
				 });
			});
						
			//you don't need this, just used for changing background
			jQuery(function($) {
				 $('#btn-login-dark').on('click', function(e) {
					$('body').attr('class', 'login-layout');
					$('#id-text2').attr('class', 'white');
					$('#id-company-text').attr('class', 'blue');
					e.preventDefault();
				 });
				 $('#btn-login-light').on('click', function(e) {
					$('body').attr('class', 'login-layout light-login');
					$('#id-text2').attr('class', 'grey');
					$('#id-company-text').attr('class', 'blue');
					e.preventDefault();
				 });
				 $('#btn-login-blur').on('click', function(e) {
					$('body').attr('class', 'login-layout blur-login');
					$('#id-text2').attr('class', 'white');
					$('#id-company-text').attr('class', 'light-blue');
					e.preventDefault();
				 });
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				e.preventDefault();			 
			});
		</script>
  </body>
</html>    
    