<?php 
$CI=&get_instance();
// echo $CI->printTree($CI->createTree());
?>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/V3/assets/jquery-sumoselect/jquery.sumoselect.min.js"></script>
<link rel="stylesheet" type="text/css" href="/V3/assets/jquery-sumoselect/sumoselect.css" />
<script type="text/javascript" src="<?php echo theme_url('assets/js/jquery-colorbox/jquery.colorbox-min.js/');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/js/jquery-colorbox/example2/colorbox.css'); ?>" />
<div class="filter">
  <div class="left" style="width:12%;text-align:center">
  <div><label>เนื้อหาทั้งหมด:</label></div> 
  <?=$content['count_content']?>
  </div>
  
  <div class="left" style="width:12%;text-align:center">
  <div><label>เปิดใช้งาน:</label></div> 
  <?=$content['status_open']?>
  </div>

  <div class="left" style="width:12%;text-align:center">
  <div><label>ไม่แสดงผล:</label></div> 
  <?=$content['status_close']?>
  </div>
  
 <div class="left" style="width:12%;text-align:center">
  <div><label>ดราฟ:</label></div> 
  <?=$content['status_draft']?>
  </div>
  <div class="left" style="width:12%;text-align:center">
  <div><label>ถังขยะ:</label></div> 
  <?=$content['status_delete']?>
  </div>
  <div class="left" style="width:12%;text-align:center">
  <div><label>รออนุมัติ:</label></div> 
  <?=$content['status_waitapprove']?>
  </div>
  <div class="left" style="width:12%;text-align:center">
  <div><label>ยังไม่ได้ผูกเมนู:</label></div> 
  <?=$content['non_category']?>
  </div>
 
  <div class="clear"></div>
</div>
<table class="list">
<thead>
  <tr>
    <th><a rel="c.idx" class="sortable" href="#">Category ID</a></th>
    <th>Category Name</th>
    <th>Parent Name</th>
    <th>จำนวนบทความ</th>
    <th>จำนวนผู้เข้าชมรวม</th>
    <th>ACTION</th>
  </tr>
</thead>
<tbody>
  <?php if($lists) { ?>
    <?php foreach($lists as $row) {?>
    <tr>
      <td id="CAT_ID_<?=$row['category_id']?>"><?=$row['category_id']?></td>
      <td><a href="<?=trueplook_url('/cmsblog/index/'.$row['category_name_code'])?>" target="_blank"><?=$row['category_name_th']?></a></td>
      <td><a href="#CAT_ID_<?=$row['p_category_id']?>"><?=$row['p_category_name_th']?></a></td>
      <td><?=$row['count_content']?></td>
      <td><?=$row['sum_view_count']?></td>
      <td><a href="<?=site_url(ADMIN_PATH."/cmsblog?c=".$row['category_id'])?>">SHOW</a></td>
    </tr>
    <?php }?>
  <?php } else {?>
    <tr><td colspan="6">NODATA</td></tr>
  <?php }?>
</tbody>
<tfoot>
  <tr><td colspan="6" align="center"><?=isset($pagination) ?  $pagination->create_links() : '' ?></td></tr>
</tfoot>
</table>
<script>
$(function(){
  
  $(function(){
    //$('.demo').fSelect();
    $('#selected_cat').SumoSelect({search :true, csvSepChar: '-',outputAsCSV: true,});
  });
  
  $(".iframe").colorbox({
      iframe:true, 
      fixed:true,
      width:"99%", 
      height:"99%",
  });
  
  $(document).bind('cbox_open', function(){
    $('body').css({overflow:'hidden'});
  }).bind('cbox_closed', function(){
     $('body').css({ overflow: '' });
  });
  
   $(document).ready(function() {
        // Sort By
        $('.sortable').click( function() {
            sort = $(this);
			if (sort.hasClass('asc')) {
                window.location.href = "<?= site_url(ADMIN_PATH.'/cmsblog/cms-list') . '?'; ?>&sort=" + sort.attr('rel') + "&order=desc";
            } else {
                window.location.href = "<?= site_url(ADMIN_PATH.'/cmsblog/cms-list') . '?';  ?>&sort=" + sort.attr('rel') + "&order=asc";
            }
            return false;
        });

        <?php if ($sort = $this->input->get('sort')): ?>
            $('a.sortable[rel="<?php echo $sort; ?>"]').addClass('<?php echo ($this->input->get('order')) ? $this->input->get('order') : 'asc' ?>');
        <?php else: ?>
            $('a.sortable[rel="c.idx"]').addClass('desc');
        <?php endif; ?>
        
        // $("[data-toggle=tooltip]").tooltip();
    });
  
});

</script>
<style>
a.sortable{color:#999;}
a.sortable{color:#666;}
b.red{color:#F00}
b.green{color:#0F0}
b.blue{color:#00F}
b.grey{color:#666}
b.yellow{color:#F0F}
#colorbox, #cboxOverlay{position:fixed; top:0; left:0; z-index:9999; overflow:hidden;}
#cboxWrapper{}
#cboxContent{margin-top:0px; overflow:visible; background:#000;}
#cboxClose { background-position: -50px 0px; right: 20px; top: 10px;}
#cboxTitle{display: none !important;}
.SumoSelect {width:715px;}
figure {
    /* display: table; */
    width:250px;
}
img, figcaption {
    /* display: table-cell; */
    /* vertical-align: bottom; */
}
figcaption {
    padding-left: 8px;
    text-overflow:ellipsis;
    width: 250px;
}
</style>