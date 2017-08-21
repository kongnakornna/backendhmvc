<div class="container form-profile" style="padding:30px;">
<h1 class="">CHANGE PASSWORD / เปลี่ยนรหัสผ่าน</h1>
    <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">รหัสผ่านเดิม : (Old Password)  <?php echo form_password(array('id' => 'old_user_password', 'name' => 'old_user_password','value'=>set_value('old_user_password'), 'placeholder'=>'รหัสผ่านเดิม', 'class'=>'form-control', 'data-toggle'=>'tooltip', 'data-placement'=>'bottom')); ?>
              <?php echo form_error('user_username', '<div class="error">','</div>'); ?>
            </div>
            <div class="form-group">รหัสผ่านใหม่ : (New Password)  <?php echo form_password(array('id' => 'user_password', 'name' => 'user_password', 'placeholder'=>'รหัสผ่าน', 'class'=>'form-control')); ?>
              <?php echo form_error('user_password', '<div class="error">','</div>'); ?>
            </div>
            <div class="form-group">ยืนยันรหัสผ่านใหม่ : (Confirm Password)  <?php echo form_password(array('id' => 'user_password_conf', 'name' => 'user_password_conf', 'placeholder'=>'ยืนยันรหัสผ่านใหม่', 'class'=>'form-control')); ?>
              <?php echo form_error('user_password_conf', '<div class="error">','</div>'); ?>
            </div>
            <?php if(true){ ?>
            <div class="row text-center" style="color:red; margin:0 5px">
              <?=$error?>
            </div>
            <?php } ?>
            <div class="row text-center">
              <script src='https://www.google.com/recaptcha/api.js?hl=th'></script>
              <center><div class="g-recaptcha col-center" data-sitekey="6LfhigkTAAAAAMF-vpTQKgzhIBUQLRn9dgV2LdSF"></div></center>
            </div>
            <?php echo form_error('g-recaptcha-response', '<div class="error">','</div>'); ?>
            <div class="row text-center" style="margin-top:1em;">
              <?php echo form_button(array('name' => 'submit', 'id' => 'submit', 'value' => 'true', 'type' => 'submit', 'content' => 'เปลี่ยนรหัสผ่าน', 'class'=>'btn btn-warning')) ?>
              <?php /*echo form_button(array('name' => 'submit', 'id' => 'submit', 'value' => 'true', 'type' => 'reset', 'content' => 'ยกเลิก', 'class'=>'btn btn-info')) */?>
            </div>
            
    </form>
</div>