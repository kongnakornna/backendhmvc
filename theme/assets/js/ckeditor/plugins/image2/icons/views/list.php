<?php 
$CI=&get_instance();
// echo $CI->printTree($CI->createTree());
?>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/V3/assets/jquery-sumoselect/jquery.sumoselect.min.js"></script>
<link rel="stylesheet" type="text/css" href="/V3/assets/jquery-sumoselect/sumoselect.css" />
<script type="text/javascript" src="<?php echo theme_url('assets/js/jquery-colorbox/jquery.colorbox-min.js/');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/js/jquery-colorbox/example2/colorbox.css'); ?>" />

<form method="get">
<div class="filter">
  <div class="left">
  <div><label>คำค้น:</label></div> 
  <input type="text" name="q" value="<?=$this->input->get('q')?>">
  </div>

  <div class="left">
  <div><label>สถานะ:</label></div> 
  <?=form_dropdown('s', array(''=>'ทั้งหมด', '1'=>'เปิดใช้งาน', '0'=>'ไม่แสดงผล', '-1'=>'ดราฟ', '-2'=>'ถังขยะ', '-3'=>'รออนุมัติ'), $this->input->get('s'));?>
  </div>
  
   <div class="left filter_buttons">
  <button class="btn btn-default" type="submit"><span>Filter</span></button>
  <a href="/V3/sitemin/cmsblog/cms-list"><span  class="btn btn-default">Clear</span></a>
  <a href="/V3/sitemin/cmsblog/cms-detail"><span class="btn btn-default">ADD</span></a>
  </div>
  <div class="left" style="width: 800px">
  <div style="width: 800px"><label>หมวดหมู่:</label>
    <label style="float:right"></div> 
  <select style="width:100%" name="c" multiple="multiple" class="demo" id="selected_cat" <?=$this->input->get('non_cat') ? 'disabled' : '' ?>>
    <?=$CI->printTree($CI->createTree())?>
  </select>
  &nbsp; &nbsp;<label><input type="checkbox" name="non_cat" id="non_cat" value="1" <?=$this->input->get('non_cat') ? 'checked' : '' ?> /> ไม่เลือก</label>
  
  <script>
    $(function(){
      $('#non_cat').change( function(){
       if ($('#non_cat').is(":checked")) {
          // $('#selected_cat').prop("disabled", true);
          $('#selected_cat')[0].sumo.disable();
       } else {
          // $('#selected_cat').prop("disabled", false);  
          $('#selected_cat')[0].sumo.enable();
       }
         
      });
      // $('.demo').sumo.disable();
    });

  </script>
  
  </div>


 
  <div class="clear"></div>
</div>
</form>
<table class="list">
<thead>
  <tr>
    <th><a rel="c.idx" class="sortable" href="#">ID</a></th>
    <th>Parent ID</th>
    <th width="250"><a rel="c.title" class="sortable" href="#">รายละเอียด</a></th>
    <th>หมวดหมู่</th>
    <th><a rel="c.create_date" class="sortable" href="#">สร้าง</a> / <a rel="c.update_date" class="sortable" href="#">แก้ไข</a></th>
    <th>สถานะ</th>
    <th>ACTION</th>
  </tr>
</thead>
<tbody>
  <?php if($lists) { ?>
    <?php foreach($lists as $row) {?>
    <tr>
      <td>
        <?php if(empty($row['parent_idx'])) {?>
          <a href="<?=site_url(ADMIN_PATH."/cmsblog/cms-list-children?pid=".$row['idx'])?>"><?=$row['idx']?></a>
        <?php }else{ ?>  
          <?=$row['idx']?>
        <?php } ?>
      </td>
      <td>
        <?php if(!empty($row['parent_idx'])) {?>
        <a href="<?=site_url(ADMIN_PATH."/cmsblog/cms-list-children?pid=".$row['parent_idx'])?>"><?=$row['parent_idx']?></a>
        <?php } ?>
      </td>
      <td>
          <?php
           //var_dump($row);
          /*
                  <?php if($row['image_filename_s']) { ?> <img style="width:16px;height:16px" src="<?=trim($row['thumb_url'],'/').'/'.$row['image_filename_s']; ?>" /> <?php } else {?><img style="width:16px;height:16px" src="http://www.fahmui.com/media/wysiwyg/upload/instagram.png" /><?php } ?>
                  <?php if($row['image_filename_m']) { ?>  <img style="width:16px;height:16px" src="<?=trim($row['thumb_url'],'/').'/'.$row['image_filename_m']; ?>" /> <?php } else {?><img style="width:16px;height:16px" src="http://www.fahmui.com/media/wysiwyg/upload/instagram.png" /><?php } ?>
                  <?php if($row['image_filename_l']) { ?> <img style="width:16px;height:16px" src="<?=trim($row['thumb_url'],'/').'/'.$row['image_filename_l']; ?>" /> <?php } else {?><img style="width:16px;height:16px" src="http://www.fahmui.com/media/wysiwyg/upload/instagram.png" /><?php } ?>
          */
          ?>
        <div><?php /*<div class="col-lg-3 col-md-3 col-sm-6"> */ ?>
          <a href="<?=trueplook_url("cmsblog/preview/".$row['idx'])?>" title="<?=$row['title']?>" target="_blank">
            <figure class="grid-thumbnail-caption" style="position:relative">
            <?php if($row['editor_picks']) { ?><span class="label label-success" style="position:absolute; left:5px; top:5px;"><i class="glyphicon glyphicon-thumbs-up"></i></span> <?php } ?>
            <?php if($row['encyclopedia']) { ?><span class="label label-danger" style="position:absolute; right:5px; top:5px;"><i class="glyphicon glyphicon-book"></i></span><?php } ?>
            <?php if($row['thumb_path']) { ?> <img class="img-responsive" src="<?=$this->config->item('static_url').$row['thumb_path']; ?>" /> <?php } else {?><img class="img-responsive" src="http://dummyimage.com/250x140/333/fff?text=No%20Thumbnail" /><?php } ?>
            <figcaption>
              <?=$row['title'] //mb_strcut($row['title'],0,50)?>
              </figcaption>
          </figure>
          </a>
        </div> 
      </td>
      
      <td nowrap>
        <a class='iframe' href="<?=site_url(ADMIN_PATH.'/cmsblog/cms-category-select?id='.$row['idx'])?>" title="<?=$row['category_id']?>">แก้ไข [<?=$row['category_id'] ? count(explode(',', $row['category_id'])) : 0 ?>]</a> 
        <ul>
        <?php foreach(explode(',', $row['category_name_th']) as $categoryname) {?>
          <li><?=$categoryname?></li>
        <?php }?>
        </ul>
      </td>
      <td>
     
        <b>สร้างโดย: </b><?=$row['psn_display_name']?> <b>เมื่อ : </b><?=date('Y-m-d', strtotime($row['create_date']))?>
        </br>
        <b>แก้ไขโดย: </b><?=$row['u_psn_display_name']?> <b>เมื่อ: </b><?=date('Y-m-d', strtotime($row['update_date']))?>
        </td>
      <td nowrap><?php
      switch($row['record_status']){
        case '1' : echo '<b class="green">เปิดใช้งาน</b>'; break;
        case '0' : echo '<b class="blue">ไม่แสดงผล</b>'; break;
        case '-1' : echo '<b class="grey">ดราฟ</b>'; break;
        case '-2' : echo '<b class="red">ถังขยะ</b>'; break;
        case '-3' : echo '<b class="yellow">รออนุมัติ</b>'; break;
      }
       ?>
      </td>
      <td nowrap>
        <?php if(empty($row['parent_idx'])) {?>
        <a href="<?=site_url(ADMIN_PATH.'/cmsblog/cms-detail?parent_idx='.$row['idx'])?>">+ CHILD</a> | 
        <?php } ?>
        <a href="<?=site_url(ADMIN_PATH.'/cmsblog/cms-detail?id='.$row['idx'])?>">EDIT</a>
      </td>
    </tr>
    <?php }?>
  <?php } else {?>
  <tr><td colspan="6">NODATA</td></tr>
  <?php }?>
</tbody>
<tfoot>
  <tr><td colspan="6" align="center"><?php echo $pagination->create_links() ?></td></tr>
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