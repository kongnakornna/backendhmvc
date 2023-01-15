<script>
$(function(){
  getPercent();
});

function getPercent(){
  $.ajax({
    dataType: "json",
    url: "<?=site_url(ADMIN_PATH."/cmsblog/cmsblog-file-reconvert/$data->cmsblog_idx/$data->cmsblog_file_id?method=GETPERCENT")?>",
  }).done(function($data){
      
      if(typeof($data.error) == "undefined"){
      $('#percent').empty();
        var trHTML = '';
        $.each($data.files.item, function (i, item) {
            trHTML += '<tr><td><?=$data->file_name?>_' + item.quality + '</td><td>' + item.quality + '</td><td>' + item.percent + ' %</td></tr>';
        });
        $('#percent').append(trHTML);

       }else {
        $('#percent').empty();
        $('#percent').append('<tr><td colspan="3">ไม่พบข้อมูลการแปลงไฟล์</td></tr>');
      }
  });
}

function reconvert(){
    $.ajax({
    dataType: "json",
    url: "<?=site_url(ADMIN_PATH."/cmsblog/cmsblog-file-reconvert/$data->cmsblog_idx/$data->cmsblog_file_id?method=RECONVERT")?>",
  }).done(function($data){
     if($data == "true"){
      getPercent();
     }else{
      alert("เกิดข้อผิดพลาดในการแปลงไฟล์วีดีโอ");
     }
  });
}
</script>
<div style="font-size:200%">CONVERT VIDEO</div>
<hr style="margin:2px auto 10px auto" />
<table class="table  table-bordered">
  <thead>
    <tr class="info">
      <th>NAME</th>
      <th>QUALITY</th>
      <th>COMPLETE %</th>
    </tr>
   </thead>
   <tbody  id="percent"></tbody>
</table>


<button onclick="getPercent()" class="btn btn-default">REFRESH</button>
<button onclick="reconvert()" class="btn btn-default pull-right">RECONVERT</button>
<style>.breadcrumb{display:none;}</style>