<h1>แก้ไขไฟล์แนบ</h1>
<form method="post">
  <div class="form">
    <div class="row">
      <label>ชื่อไฟล์</label>
      <input type="text" placeholder="ความยาวไม่เกิน 50 ตัวอักษร"/>
    </div>
<!--
    <div class="row">
      <label>ชนิด</label>
      <select>
        <option value="v">VIDEO</option>
        <option value="f">FLASH</option>
        <option value="d">DOCUMENT</option>
        <option value="a">AUDIO</option>
      </select>
    </div>
!-->
    <div class="row">
      <label>ไฟล์</label>
      <input type="file"/>
    </div>
    
    <div class="row">
      <label>Link</label>
      <input type="text"/>
      <a href="#" data-toggle="tooltip" data-placement="right" class="tip"
      data-html="true" title="" 
      data-original-title="ใส่ link ของ FTP หรือ จาก Youtube">?</a>
    </div>
<!--    
    <div class="row">
      <label>ความยาววีดีโอ</label>
      <input type="text" placeholder="00:00:00"/> 
      <a href="#" data-toggle="tooltip" data-placement="right" class="tip"
      data-html="true" title="" 
      data-original-title="ใส่ในรูปแบบ 00:00:00 <br/>ในวีดีโอ หรือ ไฟล์เสียง">?</a>
    </div>
!-->
    <div class="row" style="border-bottom:none;">
    <label>&nbsp;</label>
      <button type="submit" value="submit" name="submit" class="button">บันทึก</button>
      <button type="button" class="button" onclick="return confirm('คุณยืนยันที่จะยกเลิก'); parent.$.colorbox.close(); self.close()">ยกเลิก</button>
    </div>
  </div>
</form>

<script>
$("[data-toggle=tooltip]").tooltip();
</script>
<style>
div.tooltip-inner{padding:5px !important;}
a.tip{padding:0px 4px; border-radius: 10px; border: 2px solid #f33; color:#f33; font-weight: bold;    text-decoration: none;}
.breadcrumb {display:none;}
label.select{padding:3px 10px;}
label.select:hover{background-color:#EEE;}
h1{font-size:30px; border-bottom:1px solid #666; color:#666}
#content {padding-bottom: 10px;}
</style>