<div class="container form-profile" style="padding:30px;">
<h1 class="">RESET PASSWORD / ตั้งรหัสผ่านใหม่</h1>
    <?php if(empty($error)) { ?>
      <form action="" method="POST">
              <div class="form-group">รหัสผ่าน : (Password)  <?php echo form_password(array('id' => 'user_password', 'name' => 'user_password', 'placeholder'=>'รหัสผ่าน', 'class'=>'form-control')); ?>
                <?php echo form_error('user_password', '<div class="error">','</div>'); ?>
              </div>
              <div class="form-group">ยืนยันรหัสผ่าน : (Password Confirm)  <?php echo form_password(array('id' => 'user_password_conf', 'name' => 'user_password_conf', 'placeholder'=>'รหัสผ่านอีกครั้ง', 'class'=>'form-control')); ?>
                <?php echo form_error('user_password_conf', '<div class="error">','</div>'); ?>
              </div>
              <div class="row text-center" style="margin-top:1em;">
                <?php echo form_button(array('name' => 'submit', 'id' => 'submit', 'value' => 'true', 'type' => 'submit', 'content' => 'เปลี่ยนรหัสผ่าน', 'class'=>'btn btn-warning')) ?>
                <?php /*echo form_button(array('name' => 'submit', 'id' => 'submit', 'value' => 'true', 'type' => 'reset', 'content' => 'ยกเลิก', 'class'=>'btn btn-info')) */?>
              </div>
      </form>
    <?php } else { ?>
        <div><?php echo $error?></div>
    <?php }?>
</div>