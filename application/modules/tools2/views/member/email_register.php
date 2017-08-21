<html>
<meta charset="utf-8">
<body>
<table border="0"  cellspacing="0" cellpadding="0" style="font-size:12px; border:1px solid #E02125; color:#2D3649;">
  <tr>
    <td height="80" width="190"><img src="http://www.trueplookpanya.com/assets/tppy_v1/images/share/header/logo-true.png"/></td>
    <td style="background-color:#E02125">&nbsp;</td>
  </tr>
  <tr>
  <td colspan="2">
    <center><h2>การสมัครสมาชิกมาถึงขั้นตอนสุดท้ายแล้วค่ะ</h2></center>
  </td>
  </tr>
  <tr>
    <td colspan="2">
      <center><strong>กรุณายืนยันการสมัครสมาชิกเว็บไซต์ Trueplookpanya.com</strong></center>
      <br />
      <br />
      <ul>
          <li>การสมัครสมาชิกจะสมบูรณ์ และสามารถใช้บริการในเว็บไซต์ Trueplookpanya.com ได้ เมื่อคลิก <b style="font-size: 14px;">"ยืนยันการสมัครสมาชิก"</b> 
          ด้านล่างนี้</li>
          <li>หากคุณไม่ได้คลิกยืนยันการสมัครภายใน 30 วัน ชื่อสมาชิกของคุณจะถูกลบออกจากฐานข้อมูล</li>
          <li>เมื่อคลิกยืนยันแล้วกรุณารอ จนกว่าจะเห็นข้อความว่า <b>"ยินดีต้อนรับสมาชิกใหม่ของ Trueplookpanya.com"</b> เป็นอันเสร็จสมบูรณ์ ท่านสามารถล็อกอินเข้าสู่เว็บไซต์ได้ทันที</li>
          <li>มีปัญหาหรือข้อสงสัยกรุณาส่งข้อความมาที่ admin@trueplookpanya.com</li>
      </ul>
    </td>
  </tr>
<?php if($username !='') { ?>
  <tr>
    <td colspan="2" >
      <center>
        <table border="1" style="font-size: 12px;" bordercolor="#E4E8EB" cellspacing="0" cellpadding="0" width="80%">
            <tr>
                <td colspan="3" style="text-align: center; background-color: #BEF18C;">
                    <strong>ข้อมูลสมาชิกของคุณ</strong>
                </td>
            </tr>
            <tr>
                <td width="100px"> USERNAME</td>
                <td><strong><?= $username ?></strong></td>
            </tr>
            <tr>
                <td>PASSWORD</td>
                <td><strong><?= $password ?></strong></td>
            </tr>
        </table>
      </center>
      <br />
  </td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="2">
            <div style="background-color: #E4E8EB; color: #2D3649; text-align: center; font-size:12px; break-word;">
            <BR/>
            <a href="<?= site_url('/member/activate/'. $activation_code) ?>" style="font-size: 18px; color: #2D3649;">ยืนยันการสมัครสมาชิก คลิกที่นี่! </a>
            <BR/>
            หรือ    
            <BR/><?= site_url('/member/activate/'. $activation_code) ?> 
            <br />
            <br />
        </div>
    </td>
  </tr>
</table>
</body>
</html>

