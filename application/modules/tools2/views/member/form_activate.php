<div class="container form-profile" style="padding:30px;">
<h1 class="">ยืนยันการสมัคร</h1>
<div><?=$message?></div>

<?php if($form){ ?>

  <form action="" method="post" style="margin: 20px">
           <div class="form-group"> * อีเมล : (Email) <?php echo form_input(array('id' => 'user_email', 'name' => 'user_email','value'=>set_value('user_email'), 'placeholder'=>'อีกเมลล์สำหรับยืนยันตัวตน', 'class'=>'form-control')); ?>
            <div style="color:red; font-size:12px; margin-left:15px"><?=$error?></div>
            </div>
            <div class="row text-center">
              <?php echo form_button(array('name' => 'submit', 'id' => 'submit', 'value' => 'true', 'type' => 'submit', 'content' => 'ส่งอีเมล', 'class'=>'btn btn-warning')) ?>
            </div>
  </form>
  
<?php }?>

<?php if($profile){ ?>
<div class="row text-center">
  <a href="/member/profile/" class="btn btn-success">ข้อมูลสมาชิก</a>
  <a href="/home" class="btn btn-default">แก้ไขภายหลัง</a>
</div>
<?php } ?>
</div>
