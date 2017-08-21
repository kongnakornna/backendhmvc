<script src="/assets/tppy_v1/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/assets/tppy_v1/bower_components/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>


<link href='//fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="assets/bootstrap-tagsinput/dist/bootstrap-tagsinput.css"/>
<script src="/assets/bootstrap3-typeahead/bootstrap3-typeahead.js"></script>
<script src="/assets/bootstrap-tagsinput/dist/bootstrap-tagsinput.js"></script>

  
<script src="/assets/colorbox/jquery.colorbox-min.js"></script>
<link rel="stylesheet" href="/assets/colorbox/theme3/colorbox.css"/>

<div class="container">
<h1>รายชื่อผู้ติดต่อ</h1>
     <hr />
    <table width="100%">
      <tr>
        <td width="25%" valign="top">
          <ul class="list-group">
            <li class="list-group-item"><a class="" href="/member/group-contract"><img src="/assets/images/all_group.png" style="width:12px;" /> รายชื่อติดต่อทั้งหมด</li>
            <?php foreach($group_list as $k=>$v) { ?>
              <li class="list-group-item <?=$v['group_id']==$group['group_id']? 'list-group-active' : ''?>" ><a href="/member/group-contract/<?=$v['group_id']?> "style="margin-left:15px"><img src="/assets/images/group.png" style="width:12px;" /> <?=$v['group_name']?> (<?=$v['count_member']?>)</a></li>
            <?php } ?>
            <li class="list-group-item"><a class='inline' href="#group_new"><img src="/assets/images/add_group.png" style="width:12px;" /> สร้างกลุ่มใหม่</a></li>
          </ul>
        </td>
        <td width="3%"></td>
        <td width="72%">
        <form method="post">
          <table class="table table-no-padding tb">
            <thead>
              <tr>
                <th>
                  <h2>
                    <?php if($group['group_id'] > 0) { ?>
                      <a class='aoveray inline' href="#group_edit" title="แก้ไขข้อมูลกลุ่ม"> <?=$group['group_name']?> (<?=!empty($list) ? count($list) : 0?>)&nbsp;&nbsp;<i class="glyphicon glyphicon-pencil"></i></a>
                    <?php } else { ?>
                      <div style="color:#999; top:0" ><?=$group['group_name']?> (<?=!empty($list) ? count($list) : 0?>) </div>
                    <?php }?>
                  </h2>
                </th>
                <th align="right">
                  <h2>
                    <?php if($group['group_id'] > 0) { ?>
                     <a class='aoveray inline pull-right' href="#group_insert" title="เพิ่มผู้ติดต่อ">เพิ่มผู้ติดต่อ <i class="glyphicon glyphicon-user"></i></a>
                    <?php } ?>
                  </h2>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php if($list){ ?>
                  <tr>
                    <td colspan="2">
                    <div class="tb" style="display: table;width: 100%;"> 
                    <?php foreach($list as $v) { ?>
                        <label>
                            <div>
                              <input type="checkbox" name="users[]" value="<?=$v['user_id']?>"/> 
                              <img class="fimg" src="<?=$v['psn_display_image']?>" />
                              <span><?=$v['contract_user_nick']?></span>
                              <span><?=$v['user_email']?></span>
                            </div>
                        </label>
                    <?php } ?>
                    </div>
                    </td>
                  </tr>
              <?php }else{ ?>
                <tr><td align="center"><h2>ไม่มีผู้ติดต่ออยู่ในกลุ่มนี้<h2>เมื่อต้องการเพิ่มผู้ติดต่อ ให้คลิกปุ่ม "เพิ่มผู้ติดต่อ" หรือค้นหาคนที่ต้องการ</td></tr>
              <?php } ?>
            </tbody>
            <tfoot style="height:50px">
              <tr>
                <td align="left"  colspan="2">
                  <button class='btn aoveray pull-left del-user'  id="tfoot-del-user" style="display:none;" name="DEL" value="sad"> ลบผู้ติดต่อ <i class="glyphicon glyphicon-trash"></i></button>
                </a></td>
              </tr>
            </tfoot>
          </table>
        </td>
      </tr>
    </table>
</form>
    <div style='display:none'>
      <div id="group_new" style='padding:25px; background:#fff;'>
        <h1>กลุ่มใหม่</h1>
        <hr/>
        <form method="post" >
          <div class="form-group">
            <label for="new_group_name">โปรดใส่ชื่อกลุ่ม</label>
            <input type="text" name="new_group_name" placeholder="ชื่อกลุ่ม" class="form-control" maxlength="150" required />
             <div class="clearfix"></div>
             <div class="pull-right">
              <button class="btn btn-default cbox_closed" type="button" onclick="$.colorbox.close();">ยกเลิก</button>
              <button class="btn btn-primary" type="submit">ตกลง</button>
            </div>
            <div class="clearfix"></div>
          </div>
        </form>
      </div>
		</div>
    
    <div style='display:none'>
      <div id="group_edit" style='padding:25px; background:#fff;'>
        <h1>แก้ไขข้อมูลกลุ่ม</h1>
        <hr/>
        <form method="post" >
          <div class="form-group">
            <label for="edit_group_name">โปรดใส่ชื่อกลุ่ม</label>
            <input type="text" name="edit_group_name" placeholder="ชื่อกลุ่ม" class="form-control" value="<?=$group['group_name']?>" maxlength="150" required  />
            <a class="error" onclick="return confirm('คุณต้องการลงกลุ่มนี้ใช่หรือไม่')" href="/member/group-contract/<?=$group['group_id']?>?DELGROUP=1">ลบกลุ่มนี้</a>
             <div class="clearfix"></div>
             <div class="pull-right">
              <button class="btn btn-default cbox_closed" type="button" onclick="$.colorbox.close();">ยกเลิก</button>
              <button class="btn btn-primary" type="submit">ตกลง</button>
            </div>
            <div class="clearfix"></div>
          </div>
        </form>
      </div>
		</div>
    
    <div style='display:none'>
      <div id="group_insert" style='padding:25px; background:#fff;'>
        <h1>เพิ่มผู้ติดต่อ</h1>
        <hr/>
        <form method="post" >
           <div class="form-group">
              <label for="new_user" class="control-label">รายชื่อติดต่อ</label><div class="clearfix"></div>
              <textarea  name="new_user" id="new_user" placeholder="ชื่อกลุ่ม" class="form-control bootstrap-tagsinput" style="width:300px !important; height:200px;" >dasd</textarea>
            </div>
             <div class="clearfix"></div>
             <div class="pull-right">
              <button class="btn btn-default cbox_closed" type="button" onclick="$.colorbox.close();">ยกเลิก</button>
              <button class="btn btn-primary" type="submit">ตกลง</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <script>
          var $elt = $('#new_user');
          $elt.tagsinput({
            minLength:2,
            itemText: 'contract_user_nick',
            itemValue: 'user_id',
            typeahead: {
              afterSelect: function(val) { this.$element.val(""); },
              source: function(query) {
                return $.getJSON('/member/group-contract-search?q='+query);
              },
              updater: function (item) {
                var pos = this.source.indexOf(item);
                if(pos != -1) {
                  var newSource =
                    this.source.slice(0,pos)
                    .concat(this.source.slice(pos+1));
                  this.source = newSource;
                }
                return item;
              },
              
            },
            /*autoSelect: true*/
          });
        </script>
      </div>
		</div>
    
</div>
<style>
  .table-no-padding tbody > tr > td { padding: 0;}
  .aoveray{ color:#999; top:0 }
  .aoveray:hover{ color:#333 }
  .list-group-item a { margin: 0px; display: block; width: 100%; height: 100%; }
  
  .error{color:red; font-size:80%; }
  #cboxLoadedContent { border: 0 !important; background: #fff; }
  #cboxOverlay{opacity: 0.5 !important;}
  .fimg {width: 25px; height: 25px; border-radius: 10px;}
  .tb{line-height: 3;}
  .tb label { display:table-row-group !important;   width: 100%; font-size:12px; font-weight:normal; margin: 0px; width: 100%; height: 100%; cursor:pointer; padding:8px; }
  .tb label:hover {  background-color:#f2f2f2;}
  .tb label > div {    display: table-row; padding:12px; border-top:1px solid #F2F2F2; margin:12px;}
  .tb label > div > span {    display: table-cell;    padding: 3px;}
  .tb label > div > input {    display: table-cell;  margin-right : 5px}
  .list-group-item a { color:#000;}
  .list-group-item a:hover{ color:#666;}
  .list-group-active a {color:#68a;}
  .del-user { outline: none; border:0px; background:none; }
  .del-user :active { vertical-align: top;padding-top: 8px; }
</style>

<script>
  $(function(){
    $('.inline').colorbox({inline:true, width:"700px", closeButton:false, overlayClose:false, title:false, height:"300px" });
    $("input[type='checkbox'][name='users[]']").change(function(){
      var values = new Array();
      $.each($("input[type='checkbox'][name='users[]']:checked"), function() {
        values.push($(this).val());
      });
      if(values.length > 0){
        $('#tfoot-del-user').show('blind');
      }else{
         $('#tfoot-del-user').hide('blind');
      }
    });
  });
</script>