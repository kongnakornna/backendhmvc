<h1><?= $this->input->get('method') == 'EDIT' ? 'แก้ไขไฟล์แนบ' : 'เพิ่มไฟล์แนบ' ?></h1>
<form method="post" enctype="multipart/form-data">
  
  
  <div class="form">
    <div class="row">
      <label for="file_title">ชื่อที่ใช้แสดง</label>
      <input type="text" name="file_title" placeholder="ความยาวไม่เกิน 50 ตัวอักษร"  value="<?=set_value('file_title', (!empty($file_title) ? $file_title : '') )?>"/>
    </div>
    <div class="row">
      <label  for="selecttext"><input type="radio" name="group"  id="selecttext"   style="float: right; top: 15px; position: relative;" /> Link / FTP</label>
      <input type="text" name="file_link" id="file_link" value="<?=set_value('file_link', (!empty($file_link) ? $file_link : '') )?>" />
      <a href="#" data-toggle="tooltip" data-placement="right" class="tip"
      data-html="true" title="" 
      data-original-title="ใส่ link ของ FTP ">?</a>
      
    </div>
    

<!--
    <div class="row">
      <label>ชนิด</label>
      <?php 
      $file_type_arr=array(
        'Doc-pdf'=>'PDF',
        'Link'=>'Link',
        'Flash'=>'FLASH',
        'Audio'=>'AUDIO',
        'Youtube'=>'Youtube',
        'Vimeo'=>'Vimeo',
        'Doc-zip'=>'ZIP',
        'Video'=>'Video',
        'File'=>'File',
      );
      ?>
      <?=form_dropdown('file_type', $file_type_arr, set_value('file_type', (!empty($file_type) ? $file_type : '') ))?>
      
    </div>
!-->
    <div class="row">
      <label  for="selectfile"><input type="radio" name="group" id="selectfile" style="float: right; top: 15px; position: relative;"  />ไฟล์</label>
      <input type="file" name="upload_file" id="upload_file" style="display: inline-block;"/>
      
    </div>
<!--
    <div class="row">
      <label>ความยาววีดีโอ</label>
      <input type="text" placeholder="00:00:00" name="file_duration" value="<?=set_value('file_duration', (!empty($file_duration) ? $file_duration : ''))?>" /> 
      <a href="#" data-toggle="tooltip" data-placement="right" class="tip"
      data-html="true" title="" 
      data-original-title="ใส่ในรูปแบบ 00:00:00 <br/>ในวีดีโอ หรือ ไฟล์เสียง">?</a>
    </div>
!-->    
    <div class="row">
      <label>สถานะ</label>
      <?php 
      $record_status_arr=array(
        '0'=>'ไม่เปิดใช้งาน',
        '1'=>'เปิดใช้งาน',
      );
      ?>
      <?=form_dropdown('record_status', $record_status_arr, set_value('record_status', (!empty($record_status) ? $record_status : '')))?>
    </div>
   <?php if(validation_errors()) { ?>
     <div class="row">
      <label>&nbsp;</label>
      <?=validation_errors()?>
    </div>
   <?php }?>
    <div class="row" style="border-bottom:none;">
    <label>&nbsp;</label>
      <button type="submit" value="submit" name="submit" class="button">บันทึก</button>
      <button type="button" class="button" onclick="return confirm('คุณยืนยันที่จะยกเลิก'); parent.$.colorbox.close(); self.close()">ยกเลิก</button>
    </div>
  </div>
</form>

<script>
  $('#selecttext').click(function() {
    $('#upload_file').prop('disabled', true); 
    $('#file_link').prop('disabled', false);
  });
  
  $('#selectfile').click(function() {
    $('#upload_file').prop('disabled', false); 
    $('#file_link').prop('disabled', true);
  });
  <?php if(set_value('file_link', (!empty($file_link) ? $file_link : '')) != '') { ?>
    $('#selecttext').click();
  <?php } else { ?>
  $('#selectfile').click();
  <?php }?>
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