<script src='https://www.google.com/recaptcha/api.js?hl=th'></script>

<script type="text/javascript" src="http://thrilleratplay.github.io/jquery-validation-bootstrap-tooltip/js/jquery.validate-1.14.0.min.js" /></script>
<script type="text/javascript" src="http://thrilleratplay.github.io/jquery-validation-bootstrap-tooltip/js/jquery-validate.bootstrap-tooltip.js" /></script>

<div class="container form-profile" style="padding:30px;">
	<h1 class="">Register / สมัครสมาชิก</h1>
	<form action="" method="post" name="registerForm" id="registerForm">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">* Username  <?php echo form_input(array('id' => 'user_username', 'name' => 'user_username','value'=>set_value('user_username', substr($email, 0, strpos($email, '@'))), 'placeholder'=>'Username', 'class'=>'form-control required', 'data-toggle'=>'tooltip')); ?>
					<?php echo form_error('user_username', '<div class="error">','</div>'); ?>
					<p class="warning" id='wmember_usrname'><!--ชื่อผู้ใช้งานต้องเป็น ภาษาอังกฤษและตัวเลขเท่านั้น ความยาว 3-16 ตัวอักษร--><?php echo form_error('user_username'); ?></p>
				</div>
				<div class="form-group">* รหัสผ่าน : (Password)  <?php echo form_password(array('id' => 'user_password', 'name' => 'user_password', 'placeholder'=>'รหัสผ่าน', 'class'=>'form-control required')); ?>
					<?php echo form_error('user_password', '<div class="error">','</div>'); ?>
				</div>
				<div class="form-group">* ยืนยันรหัสผ่าน : (Re-Password)  <?php echo form_password(array('id' => 'user_password_conf', 'name' => 'user_password_conf', 'placeholder'=>'รหัสผ่านอีกครั้ง', 'class'=>'form-control required')); ?>
					<?php echo form_error('user_password_conf', '<div class="error">','</div>'); ?>
				</div>
				<div class="form-group"> * อีเมล : (Email) <?php echo form_input(array('id' => 'user_email', 'name' => 'user_email','value'=>set_value('user_email', $email), 'placeholder'=>'อีกเมลล์สำหรับยืนยันตัวตน', 'class'=>'form-control required email', 'data-trigger'=>'focus')); ?>
					<p class="warning" id='wuser_email'><?php echo form_error('user_email', '<div class="error">','</div>'); ?></p>
				</div>
				<div class="form-group">* ชื่อที่แสดง : (Display name) <?php echo form_input(array('id' => 'psn_display_name', 'name' => 'psn_display_name','value'=>set_value('psn_display_name', $name), 'placeholder'=>'ชื่อที่ใช้แสดงผล (เปลี่ยนได้ในภายหลัง)', 'class'=>'form-control required')); ?>
					<?php echo form_error('psn_display_name', '<div class="error">','</div>'); ?>
				</div>
				<?php echo form_hidden(array('social_type' => set_value('social_type', $type) , 'social_id' => set_value('social_id', $id))) ?>

				<div class="form-group"> * <?php echo form_checkbox(array('name' => 'accept', 'id' => 'accept', 'checked' => FALSE)); ?>
					ข้าพเจ้าได้อ่าน <a href="http://www.trueplookpanya.com/conditions/" target="_blank" class="error">เงื่อนไขการสมัครสมาชิก</a>, <a href="http://www.trueplookpanya.com/true/terms.php/" target="_blank" class="error">Terms of Service</a> และ <a href="http://www.trueplookpanya.com/true/privacypolicy.php" target="_blank" class="error">Privacy Policy</a> ก่อนสมัครสมาชิกแล้ว</div>
					<div class="g-recaptcha col-center" data-sitekey="6LfhigkTAAAAAMF-vpTQKgzhIBUQLRn9dgV2LdSF"></div>
					<?php echo form_error('g-recaptcha-response', '<div class="error">','</div>'); ?>
					<div class="row text-center">
						<?php echo form_button(array('name' => 'submit', 'id' => 'submit', 'value' => 'true', 'type' => 'submit', 'content' => 'สมัครสมาชิก', 'class'=>'btn btn-warning')) ?>
						<?php /*echo form_button(array('name' => 'submit', 'id' => 'submit', 'value' => 'true', 'type' => 'reset', 'content' => 'ยกเลิก', 'class'=>'btn btn-info')) */?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="col-md-12 text-center">
						<p>
							<b>เข้าระบบผ่าน Facebook / Google+</b>
							<div class="clearfix"></div>
							<span>เพื่อความสะดวกในการใช้งาน เข้าสู่ระบบได้โดยใช้บัญชี ของ facebook หรือ google+</span>
						</p>
						<div class="clearfix"></div>
						<br>
						<a onclick='flogin();' style="padding: 6px 0; margin:1em auto;"><img src="assets/users/images/btn_fb.png"/></a>
						<div class="clearfix"></div>
						<a onclick='glogin();' style="padding: 6px 0; margin:1em auto;"><img src="assets/users/images/btn_gp.png"/></a>
					</div>
					<div class="col-md-12">
						<br><br>
					</div>
					<ul class="ul-step"><h1>ขั้นตอนการสมัครสมาชิก</h1>
						<li>กรุณากรอกรายละเอียดให้ครบถ้วนและถูกต้องเป็นความจริง <span class="error"> กรุณากรอกอีเมลที่ใช้งานจริงเพื่อที่ ระบบจะส่งข้อมูลยืนยันการสมัครสมาชิก ไปให้ทางอีเมลที่ท่านได้กรอกไว้</span></li>
						<li>ระบบจะส่ง อีเมล ไปให้ท่านยืนยันการสมัครสมาชิก ผ่านทางอีเมล ที่ท่านได้กรอกไว้ตอนสมัคร</li>
						<li>คลิกยืนยันการสมัครสมาชิกในอีเมล หากไม่พบ ให้ตรวจสอบใน Junk mail (อีเมลขยะ)</li>
						<li>หลังจากนั้นท่านจะสามารถล๊อกอินเข้าสู่เว็บไซต์ได้ตามปกติ</li>
					</ul>
				</div>
			</div>
		</form>
	</div>
	<style>
	.form-profile{font-size:small; font-weight:700; }
	.vcenter { display: inline-block; vertical-align: middle; float: none; }
	.col-centered {display:inline-block;    float:none;    /* reset the text-align */    text-align:left;    /* inline-block space fix */    margin-right:-4px;; }
	.row-centered {    text-align:center;}
	.error{color:#f00;}
	.ok{color:#01B2A0;}
	ul.ul-step {list-style-type: circle;}
	</style>

	<script>

	function flogin() {
		var _url = 'http://www.trueplookpanya.com/member/facebookConnect/?type=popup&<?php echo $query_string; ?>';
		var win = window.open(_url, "windowname1", 'width=800, height=600');
	}

	function glogin1(){
		var _url = 'http://www.trueplookpanya.com/member/googleConnect/?type=popup&<?php echo $query_string; ?>';
		var win = window.open(_url, "windowname1", 'width=800, height=600');
	}

	function glogin() {
		var OAUTHURL = 'https://accounts.google.com/o/oauth2/auth?';
		var VALIDURL = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=';
		var SCOPE = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';
		var CLIENTID = '783425256556-01nddfjoj72eqvd9tise3e1l8nv9tjdf.apps.googleusercontent.com';
		var REDIRECT = '<?php echo urlencode('http://www.trueplookpanya.com/member/googleConnect?type=popup') ?>';
		var LOGOUT = 'http://accounts.google.com/Logout';
		var TYPE = 'code';
		var _url = OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENTID + '&redirect_uri=' + REDIRECT + '&response_type=' + TYPE + '&state=<?php echo urlencode($query_string) ?>';
		var acToken;
		var tokenType;
		var expiresIn;
		// var user;
		// var loggedIn = false;

		var win = window.open(_url, "windowname1", 'width=800, height=600');
		var pollTimer = window.setInterval(function() {
			try {
				console.log(win.document.URL);
				if (win.document.URL.indexOf(REDIRECT) != -1) {
					var url = win.document.URL;
					acToken = gup(url, 'access_token');
					tokenType = gup(url, 'token_type');
					expiresIn = gup(url, 'expires_in');
					parent.document.location.href = REDIRECT + "?type=popup&<?php echo $query_string; ?>&access_token="+acToken;
					win.close();
				}
				window.clearInterval(pollTimer);
			} catch (e) {
			}
		}, 500);
	}

	function gup(url, name) {
		name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		var regexS = "[\\#&]" + name + "=([^&#]*)";
		var regex = new RegExp(regexS);
		var results = regex.exec(url);
		if (results == null)
		return "";
		else
		return results[1];
	}


	function set_error(message){
		$('#error').html(message);
	}

	$(function(){


		$('#user_username').blur(function(){

			$.ajax({
				url : '<?=BASE_URL?>member/checkAvalaibleUsername/'+$(this).val()+'/JSON',
				cache : false,
				success : function(response){
					if(response == 'ok'){
						$('#wmember_usrname').removeClass('warning');
						$('#wmember_usrname').addClass('info_email');
						$('#wmember_usrname').show();
						$('#wmember_usrname').html('ชื่อสมาชิกนี้สามารถใช้งานได้ค่ะ');
					}
					else if (response == 'invalid'){
						$('#wmember_usrname').removeClass('info_email');
						$('#wmember_usrname').addClass('warning');
						$('#wmember_usrname').show();
						$('#wmember_usrname').html('ชื่อผู้ใช้งานต้องเป็น ภาษาอังกฤษและตัวเลขเท่านั้น ความยาว 3-16 ตัวอักษร');
						$('#user_username').val('');
					}
					else {
						$('#wmember_usrname').removeClass('info_email');
						$('#wmember_usrname').addClass('warning');
						$('#wmember_usrname').show();
						$('#wmember_usrname').html('Username นี้มีในระบบแล้ว ลองเติมตัวเลขหรือตัวอักษรภาษาอังกฤษ');
						//$('#user_username').val('');
					}
				}
			});



			// if($(this).val().length > 3){
			// $.get('<?=BASE_URL?>member/checkAvalaibleUsername/'+$(this).val()+'/JSON', function(data) {
			// if(data == 'true'){
			// $('#user_username').removeClass('error').addClass('ok');
			// } else {
			// alert('ใช้ไม่ได้');
			// $('#user_username').removeClass('ok').addClass('error');
			// }
			// });
			// }
		});

		$('#user_email').blur(function(){
			if($(this).val().length > 3){
				$.get('<?=BASE_URL?>member/checkAvalaibleEmail/'+$(this).val()+'/JSON', function(data) {
					if(data == 'true'){
						$('#user_email').removeClass('error').addClass('ok');
					} else {
						//alert('ใช้ไม่ได้');
						$('#user_email').removeClass('ok').addClass('error');
					}
				});
			}


			$.ajax({
				url : '<?=BASE_URL?>member/checkAvalaibleEmail/'+$(this).val()+'/JSON',
				cache : false,
				success : function(response){
					if(response == 'ok'){
						$('#wuser_email').removeClass('warning');
						$('#wuser_email').addClass('info_email');
						$('#wuser_email').show();
						$('#wuser_email').html('อีเมลล์นี้สามารถใช้งานได้ค่ะ');
					}
					else if (response == 'invalid'){
						$('#wuser_email').removeClass('info_email');
						$('#wuser_email').addClass('warning');
						$('#wuser_email').show();
						$('#wuser_email').html('รูปแบบของอีเมลผิดพลาดกรุณาตรวจสอบค่ะ');
						$('#user_email').val('');
					}
					else {
						$('#wuser_email').removeClass('info_email');
						$('#wuser_email').addClass('warning');
						$('#wuser_email').show();
						$('#wuser_email').html('อีเมลลืนี้ถูกใช้งานแล้วค่ะ');
						$('#user_email').val('');
					}
				}
			});

		});

		$('#user_password_conf').blur(function(){
			if($('#user_password_conf').val() == $('#user_password').val()){
				$('#user_password_conf').removeClass('error');
			} else {
				//alert('ใช้ไม่ได้');
				$('#user_password_conf').addClass('error');
			}
		});

		$("#registerForm").validate({
			rules: {
				example4: {email:true, required: true},
				example5: {required: true}
			},
			messages: {
				example5: "Just check the box<h5 class='text-danger'>You aren't going to read the EULA</h5>"
			},
			tooltip_options: {
				example4: {trigger:'focus'},
				example5: {placement:'right',html:true}
			},
		});

	});
	</script>
	<style>
	.info_email{color:green; font-weight: normal !important;}
	.warning{color:red; font-weight: normal !important;}
	</style>
