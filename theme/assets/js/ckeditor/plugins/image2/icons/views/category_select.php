<?php
$CI=&get_instance();
?>
<h1>หมวดหมู่
  <span style="float:right; margin-bottom:5px;">
    <button type="submit" value="submit" name="submit" onclick="select_cat_form.submit()" class="btn btn-default btn-primary"  style="margin-left:30px;">SUBMIT</button>
    <button type="button" value="submit" onclick="parent.location.reload();parent.$.fn.colorbox.close();" class="btn btn-default btn-danger" style="margin-left:30px;">CLOSE</button>
  </span>
</h1> 


<div class="row" style="margin: 5px auto 5px -5px;">
  <label for="search_cate">กรองหมวดหมู่ <input type="text" id="search_cate" name="search_cate" placeholder="กรองหมวดหมู่" onkeyup="eachData()" /></label> 
  <?php $cats=$this->db->get_where('cmsblog_category', array('category_parent_id'=>'0', 'status'=>'1'))->result_array();?>
  <select id="ddl_cat" onchange="eachData()">
  <option value="">--ทั้งหมด--</option>
  <?php foreach($cats as $cat) { ?>
    <option value="<?=$cat['category_name_th']?>"><?=$cat['category_name_th']?></option>
  <?php } ?>
  </select>
</div>
   
<form method="post" name="select_cat_form">
  <div class="row">
    <?=$CI->printTreeCheckbox($CI->createTree(), 0, null, '', $selected)?>
  </div>
</form>
<script>
$(function(){
  $('#search_cate').focus();
  
  // $('#search_cate').keyup(function(){
    // var $txt=$('#search_cate').val();
    // var $cat=$('#ddl_cat>option:selected');

  // });
  

  
});

  function eachData(){
    var $txt=$('#search_cate').val();
    var $cat=$('#ddl_cat>option:selected').val();
    
    
    $('input[type=checkbox]').each(function(){
      if($(this).attr('rel').search($txt) < 0 || $(this).attr('rel').startsWith($cat)==false){
        $(this).parent().hide();
      }else{
      $(this).parent().show();
      }
    });
  }
</script>

<style>
.breadcrumb {display:none;}
label.select{padding:3px 10px;}
label.select:hover{background-color:#EEE;}
h1{font-size:30px; border-bottom:1px solid #666; color:#666}
#content {padding-bottom: 10px;}
</style>