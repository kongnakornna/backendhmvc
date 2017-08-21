<div class="container form-profile" style="padding:30px;">
<h1>FORGOT PASSWORD / ลืมรหัสผ่าน</h1>
<hr/>
  <div class="row">
     <?php if(isset($success)) {?>
     <div class="col-md-12"> 
      <?=$success?>
     </div>
      <?php } else {?>
     <form action="" method="POST" enctype="multipart/form-data">
      <div class="col-md-6"> 
      <?php
      /*
        <div class="form-group">* ชื่อสมาชิก : (Username)  <?php echo form_input(array('id' => 'user_username', 'name' => 'user_username','value'=>set_value('user_username'), 'placeholder'=>'ชื่อผู้ใช้งาน', 'class'=>'form-control', 'data-toggle'=>'tooltip', 'data-placement'=>'bottom', 'title'=>'กรุณากรอกอีเมลจริงเพื่อใช้ในการยืนยันการสมัครสมาชิก')); ?>
        <?php echo form_error('user_username', '<div class="error">','</div>'); ?>
        </div>
      */
       ?>

        <div class="form-group"><label> อีเมล : (Email) </label><?php echo form_input(array('id' => 'user_email', 'name' => 'user_email','value'=>set_value('user_email'), 'placeholder'=>'อีกเมลที่ลงทะเบียนไว้', 'class'=>'form-control')); ?>
          <?php echo form_error('user_email', '<div class="error">','</div>'); ?>
        </div>
        <div class="row text-center">
          <?php echo $error;?>
        </div>
        <div class="row text-center">
          <script src='https://www.google.com/recaptcha/api.js?hl=th'></script>
          <center><div class="g-recaptcha col-center" data-sitekey="6LfhigkTAAAAAMF-vpTQKgzhIBUQLRn9dgV2LdSF"></div></center>
        </div>
        <?php echo form_error('g-recaptcha-response', '<div class="error">','</div>'); ?>
        <div class="row text-center" style="margin-top:1em;">
          <?php echo form_button(array('name' => 'submit', 'id' => 'submit', 'value' => 'true', 'type' => 'submit', 'content' => 'ลืมรหัสผ่าน', 'class'=>'btn btn-warning')) ?>
          <?php /*echo form_button(array('name' => 'submit', 'id' => 'submit', 'value' => 'true', 'type' => 'reset', 'content' => 'ยกเลิก', 'class'=>'btn btn-info')) */?>
        </div>
      </div>
      <div class="col-md-6"> 
        <ol class="ul-step">
          <h1>ขั้นตอนการปฏิบัติเมื่อลืมรหัสผ่าน</h1>
          <li>กรอกอีเมลที่ท่านได้ลงทะเบียนไว้ หลังจากนั้นกดที่ปุ่ม "ลืมรหัสผ่าน" (หากท่านลืมอีเมล กรุณาติดต่อทีมงานทรูปลูกปัญญาที่ admin@trueplookpanya.com)</li>
          <li>ระบบจะส่งข้อความยืนยันการเปลี่ยนรหัสผ่านใหม่ไปที่อีเมลที่ท่านกรอก หากไม่พบอีเมลให้ตรวจสอบใน Junk mail (อีเมลขยะ) </li>
          <li>ทำการเปลี่ยนรหัสผ่านใหม่ตามขั้นตอนในอีเมล </li>
          <li>ล็อกอินเข้าสู่เว็บไซต์โดยใช้รหัสผ่านที่ตั้งขึ้นใหม่</li>
        </ol>
      </div>
    </form>
    <?php } ?>
  </div>
</div>