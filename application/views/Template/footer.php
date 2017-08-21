<?php  
$setting = GetConfig1();
$object = json_decode(json_encode($setting), TRUE);
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

$admin_id = 0;# 0=>เห็นทุกเมนู
$navbar_fix = $breadcrumb_fix = '';

$language = $this->lang->language;
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
?>
					</div><!-- /.page-content-area -->
				</div><!-- /.page-content -->
			</div><!-- /.main-content -->

			<div class="footer" style="clear:both;">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">&copy; <?php $year1=date('Y'); $year='2015'; echo $year;?></span>
							  <?php echo $company; echo '   '; echo $allrightsreserved;?> 
							  <br>
							  <?php echo $this->lang->line('copyright');?>
						</span>

						&nbsp; &nbsp;
						<!-- <span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
						</span> -->
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url('theme/assets/js/jquery.mobile.custom.min.js'); ?>'>"+"<"+"/script>");

			function AlertError(txt){
				$("#msg_txt").html(txt);
				$("#msg_error,#BG_overlay").fadeIn();
				$("#msg_error,#msg_info,#BG_overlay").delay(1000).fadeOut();
			}

			function AlertSuccess(txt){
				$("#msg_txt2").html(txt);
				$("#msg_success,#BG_overlay").fadeIn();
				$("#msg_success,#msg_info,#BG_overlay").delay(1000).fadeOut();
			}
			function Waiting(){
				$("#msg_txt3").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>Waiting...');
				$("#msg_info,#BG_overlay").fadeIn();
				//$("#msg_success,#msg_info,#BG_overlay").delay(1000).fadeOut();
			}
			function WaitingAlert(txt){
				if(txt == '') txt = 'Waiting';
				$("#msg_txt3").html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> ' + txt + '...');
				$("#msg_info,#BG_overlay").fadeIn();
				//$("#msg_success,#msg_info,#BG_overlay").delay(1000).fadeOut();
			}		
		</script>

		<?php echo js_asset('bootstrap.min.js'); ?>
		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>		 
		  <?php echo js_asset('excanvas.min.js'); ?>
		<![endif]-->
<?php 
		echo js_asset('jquery-ui.min.js'); 
		//echo js_asset('jquery-ui.custom.min.js'); 
		echo js_asset('jquery.ui.touch-punch.min.js'); 
		echo js_asset('jquery.colorbox-min.js');

		if(preg_match('~\b(memberadd|memberedit|profile)\b~i', strtolower($this->uri->segment(2)))){

				//echo "<!-- js member form -->";				
				echo js_asset('jquery.easypiechart.min.js'); 
				echo js_asset('date-time/bootstrap-datepicker.min.js'); 
				echo js_asset('jquery.hotkeys.min.js'); 
				echo js_asset('bootstrap-wysiwyg.min.js'); 
				echo js_asset('select2.min.js'); 

				echo js_asset('jquery.knob.min.js'); 
				echo js_asset('jquery.autosize.min.js'); 
				echo js_asset('bootstrap-tag.min.js'); 

				echo js_asset('chosen.jquery.min.js'); 
				echo js_asset('fuelux/fuelux.spinner.js'); 
				echo js_asset('x-editable/bootstrap-editable.min.js'); 
				echo js_asset('x-editable/ace-editable.min.js'); 
				echo js_asset('jquery.maskedinput.min.js'); 
				echo js_asset('jquery.validate.min.js'); 

				//echo js_asset('validate.js'); 
				//echo js_asset('member.js'); 

		}else if(preg_match('~\b(add|edit|import_csv|picture_edit|picture_edit_thumb)\b~i', strtolower($this->uri->segment(2)))){

				//echo "<!-- js form -->";
				echo js_asset('jquery-ui.custom.min.js'); 
				echo js_asset('jquery.ui.touch-punch.min.js'); 

				echo js_asset('jquery.autosize.min.js'); 
				//echo js_asset('bootstrap-tag.min.js'); 
				echo js_asset('uncompressed/bootstrap-tag.js'); 
				
				echo js_asset('chosen.jquery.min.js'); 
				echo js_asset('fuelux/fuelux.spinner.min.js'); 
				echo js_asset('date-time/bootstrap-datepicker.min.js'); 
				echo js_asset('date-time/bootstrap-timepicker.min.js'); 
				echo js_asset('date-time/moment.min.js'); 
				echo js_asset('date-time/daterangepicker.min.js'); 
				echo js_asset('date-time/bootstrap-datetimepicker.min.js'); 
				echo js_asset('bootstrap-colorpicker.min.js'); 
				echo js_asset('jquery.knob.min.js'); 
				echo js_asset('jquery.autosize.min.js'); 
				echo js_asset('jquery.inputlimiter.1.3.1.min.js'); 
				echo js_asset('jquery.maskedinput.min.js'); 

				/* if(preg_match('~\b(dara)\b~i', strtolower($this->uri->segment(1)))){
					//echo js_asset('jquery.tagbox.js'); 
				 }else 
					 echo js_asset('bootstrap-tag.min.js');*/

				echo js_asset('typeahead.jquery.min.js'); 
				echo js_asset('markdown/markdown.min.js'); 
				echo js_asset('markdown/bootstrap-markdown.min.js'); 
				echo js_asset('jquery.hotkeys.min.js'); 
				//echo js_asset('bootstrap-wysiwyg.min.js'); 

				//echo js_asset('jquery.nestable.min.js'); 
				echo js_asset('jquery.nestable.js'); 

		}else if(preg_match('~\b(order)\b~i', strtolower($this->uri->segment(1)))){

				echo js_asset('jquery.nestable.js'); 
}elseif(preg_match('~\b(xml)\b~i', strtolower($this->uri->segment(1)))){?>
<?php	$this->load->view('xml/xml'); ?>
<?php }else{//index
				
				echo js_asset('jquery.easypiechart.min.js'); 
				echo js_asset('jquery.sparkline.min.js'); 
				//echo js_asset('flot/jquery.flot.min.js'); 
				//echo js_asset('flot/jquery.flot.pie.min.js'); 
				//echo js_asset('flot/jquery.flot.resize.min.js'); 

		}
?>
		<!-- ace scripts -->
<?php 
		echo js_asset('jquery.gritter.min.js'); 
		
		echo js_asset('dataTables/jquery.dataTables.js');
		echo js_asset('dataTables/dataTables.bootstrap.js');
				
		//echo js_asset('spin.min.js'); 
		echo js_asset('bootbox.min.js'); 
		echo js_asset('ace-elements.min.js'); 
		//echo js_asset('ace.min.js'); 
?>
<script type="text/javascript">
jQuery(function($) {

 
 

		$('#logout').on('click', function() {
				var url = '<?php echo base_url('admin/logout'); ?>';
				//alert('xxxx ' + url);
				//window.open(url);
				bootbox.confirm("<?php echo $logout; ?> ?", function(result) {
					if(result) {
							window.location =  url;
						}
				});
		});

		$('[data-rel=tooltip]').tooltip();
		$('[data-rel=popover]').popover({html:true});

				$("#bootbox-regular").on(ace.click_event, function() {
					bootbox.prompt("What is your name?", function(result) {
						if (result === null) {
							
						} else {
							
						}
						return false;
					});
				});
});
</script>

		<!-- inline scripts related to this page -->
<?php

		if(preg_match('~\b(memberadd|memberedit|profile)\b~i', strtolower($this->uri->segment(2)))){

				echo js_asset('profile.js'); 

		}else if((preg_match('~\b(dara)\b~i', strtolower($this->uri->segment(1)))) && (preg_match('~\b(add|edit)\b~i', strtolower($this->uri->segment(2))))){

				echo js_asset('form.js'); 
				echo js_asset('form-editor.js'); 

		}else if(preg_match('~\b(add|edit)\b~i', strtolower($this->uri->segment(2)))){

				echo js_asset('form.js'); 

		}else if(preg_match('~\b(chart|overview)\b~i', strtolower($this->uri->segment(1)))){

				//echo js_asset('chart.js'); 
				echo js_asset('morris/raphael-2.1.0.min.js'); 
				echo js_asset('morris/morris.js'); 
				//echo js_asset('morris/chart-data-morris.js');	//data morris.js Charts panel-primary
				//echo js_asset('morris/morris-demo.js');		//data Charts panel-default
				echo js_asset('morris/morris-ready.js');

		}else{
				//echo js_asset('index.js'); 
		}
		if(isset($js)){
				foreach($js as $val){
						echo js_asset($val);
				}
		}
?>
</body>
</html>
