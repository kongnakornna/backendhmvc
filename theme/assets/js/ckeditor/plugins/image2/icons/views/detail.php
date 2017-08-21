 <?php 
$CI=&get_instance();
$CI->template->add_package(array('ckeditor'));




$this->template->add_stylesheet(site_url('/assets/input-switch/css/style.css'));

?>
<script type="text/javascript" src="<?php echo theme_url('assets/js/jquery-colorbox/jquery.colorbox-min.js/');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo theme_url('assets/js/jquery-colorbox/example2/colorbox.css'); ?>" />

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css">
<script src="/V3/assets/tageditor/jquery.tag-editor.min.js"></script>
<script src="/V3/assets/tageditor/jquery.caret.min.js"></script>

<link rel="stylesheet" type="text/css" href="/V3/assets/tageditor/jquery.tag-editor.css">

<div class="box">
  <div class="heading">
  <h1><img alt="" src="/V3/application/themes/admin/assets/images/review.png">แก้ไขข้อมูล</h1>
  <div class="buttons">
    <a class="button save" href="<?=$this->input->get('back')?$this->input->get('back') : site_url(ADMIN_PATH.'/cmsblog/cms-list/') ?> "><span>กลับ</span></a>
  </div>
  </div>
  <div class="content">
    <form  method="post" accept-charset="utf-8" enctype="multipart/form-data" >        
    <div class="tabs">
      <div id="tab-1" class="tab" style="display: block;">
        <div class="form">
          <!-- banner_path !-->
            <div <?= form_error('banner_path','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="banner_path"><span class="required">*</span> BANNER:</label>
              <div style="width:1280px; height:480px; overflow:hidden;">
              <?=form_upload_image('banner_path', isset($banner_path) ? $banner_path : '' , isset($banner_path) ? $this->config->item('static_url'). $banner_path : '' , 1280, 480, "style='width:100%;' class='img-responsive'"); ?>
              </div>
            </div>  
            <!-- thumb_path !-->
         
            <div <?= form_error('thumb_path','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="thumb_path"><span class="required">*</span> Thumbnail:</label>
              <?=form_upload_image('thumb_path', isset($thumb_path) ? $thumb_path : '' , isset($thumb_path) ? $this->config->item('static_url'). $thumb_path : '' , 610, 340, "style='width:610px;height:340px;' class='img-responsive'"); ?>
            </div>   
           
            <!-- title !-->
            <div <?= form_error('title','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="title"><span class="required">*</span> หัวข้อ:</label>
              <input type="text" name="title" maxlength="255" id="title" value="<?=set_value('title', isset($title) ? $title : '')?>" />
            </div>
            <!-- description_short !-->
<!--
            <div <?= form_error('description_short','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="description_short"><span class="required">*</span> รายละเอียดย่อ:</label>
              <textarea name="description_short" maxlength="500" id="description_short" rows="3"><?=set_value('description_short', isset($description_short) ? $description_short : '')?></textarea>
            </div>
            32:9
            16:9
!-->
            <!-- description_long !-->
            <div <?= form_error('description_long','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="description_long"><span class="required">*</span> รายละเอียด:</label>
              <textarea name="description_long" id="description_long" rows="3" class="ckeditor_textarea" ><?=set_value('description_long', isset($description_long) ? $description_long : '')?></textarea>
            </div>
            <!-- seo_keywords !-->
<!--              
            <div <?= form_error('seo_keywords','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="seo_keywords"><span class="required">*</span> Keyword:</label>
              <input type="text" name="seo_keywords" maxlength="150" id="seo_keywords" value="<?=set_value('seo_keywords', isset($seo_keywords) ? $seo_keywords : '')?>" />
            </div>
!-->
            <!-- seo_description !-->
<!-- 
            <div <?= form_error('seo_description','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
            <label for="seo_description"><span class="required">*</span> Keyword Descriptions:</label>
              <input type="text" name="seo_description" maxlength="150" id="seo_description" value="<?=set_value('seo_description', isset($seo_description) ? $seo_description : '')?>" />
            </div>
!-->
            <!-- credit_by !-->
            <div <?= form_error('credit_by','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?> id="credit_parent">
              <label>Credit:</label>
              <button class="btn btn-warning btn-xs glyphicon glyphicon-plus" onclick="addCredit()" type="button"></button>
              <?php if(isset($credit_by) && $credit_by_arr=json_decode($credit_by)) { ?>
                <?php foreach($credit_by_arr as $k=>$v) { ?>
                  <?php $rand = md5(uniqid(rand(), true));?>
                  <div style="margin-left:198px; margin-top:10px;" id="div_credit_<?=$rand?>">
                  <input type="text" name="credit_by_display[]" id="name_<?=$rand?>" onkeyup="showexam('name_<?=$rand?>', 'link_<?=$rand?>', 'ex_<?=$rand?>')" value="<?=$v->name?>" />
                  <input type="text" name="credit_by_link[]"  id="link_<?=$rand?>"  onkeyup="showexam('name_<?=$rand?>', 'link_<?=$rand?>', 'ex_<?=$rand?>')" value="<?=$v->link?>" />
                  &nbsp;&nbsp;&nbsp;<span class="example" id="ex_<?=$rand?>"></span>
                  <?php if($v != reset($credit_by_arr)) { ?>
                    &nbsp;<button class="btn btn-danger btn-xs save glyphicon glyphicon-minus pull-right" onclick="removeCredit('div_credit_<?=$rand?>')" type="button"></button>
                  <?php } ?>
                  </div>
                <?php } ?>
              <?php } else { ?>
                <div style="margin-left:198px; margin-top:10px;" id="div_credit_0">
                  <input type="text" name="credit_by_display[]"  id="name_0" onkeyup="showexam('name_0', 'link_0', 'ex_0')" />
                  <input type="text" name="credit_by_link[]"  id="link_0"  onkeyup="showexam('name_0', 'link_0', 'ex_0')" />
                  &nbsp;&nbsp;&nbsp;<span class="example" id="ex_0"></span>
                </div>
              <?php }?>
              
              <script>
                function removeCredit(div_credit_id){
                  var parent = document.getElementById("credit_parent");
                  var child = document.getElementById(div_credit_id);
                  parent.removeChild(child);
                }
                function addCredit(){
                  var index=Math.random().toString(36).substring(7);
                  var parent = document.getElementById("credit_parent");
                  var innerText='<div style="margin-left:198px; margin-top:10px;" id="div_credit_'+index+'">' + 
                      '<input type="text" name="credit_by_display[]" id="name_'+index+'" onkeyup="showexam(\'name_'+index+'\', \'link_'+index+'\', \'ex_'+index+'\')" />'+
                      '&nbsp;<input type="text" name="credit_by_link[]"  id="link_'+index+'"  onkeyup="showexam(\'name_'+index+'\', \'link_'+index+'\', \'ex_'+index+'\')" />'+
                      '&nbsp;<button class="btn btn-danger btn-xs save glyphicon glyphicon-minus pull-right" onclick="removeCredit(\'div_credit_'+index+'\')" type="button"></button>'+
                      '&nbsp;&nbsp;&nbsp;<span class="example" id="ex_'+index+'"></span>'+
                  '</div>';
                  parent.insertAdjacentHTML('beforeend', innerText);
                }
                
                function showexam(name_id, link_id, exam_id){
                  var name_id_o = document.getElementById(name_id);
                  var link_id_o = document.getElementById(link_id);
                  var exam_id_o = document.getElementById(exam_id);
                  
                  var link = document.createElement("a");

                  link.setAttribute("href", link_id_o.value);
                  link.innerHTML = name_id_o.value;
                  link.setAttribute('target', '_blank');

                  exam_id_o.textContent='';
                  exam_id_o.appendChild(link);
                }
              </script>
            </div>
            
            <!-- start_date !-->
            <div <?= form_error('event_date','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="event_date"> Event Date:</label>
              <input type="text" name="event_date" id="event_date" value="<?=set_value('event_date', isset($event_date) && $event_date != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($event_date)) : '' );?>" />
            </div>
            
            <!-- start_date !-->
<!--            
            <div <?= form_error('end_date','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="end_date"><span class="required">*</span> วันสิ้นสุด:</label>
              <input type="text" name="end_date" id="end_date" value="<?=set_value('end_date', isset($end_date) ? date('Y-m-d', strtotime($end_date)) : date('Y-m-d', strtotime('+50 years'))) ;?>" />
            </div>
!-->
            <!-- start_date !-->
            <div <?= form_error('hashtag','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="hashtag"> Hashtag:</label>
              <input type="text" name="hashtag" id="hashtag"  value="<?=set_value('hashtag',  isset($hashtag) ? $hashtag : '');?>" />
            </div>
                        
            <!-- start_date !-->
            <div <?= form_error('record_status','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="record_status"> สถานะ:</label>
              <select name="record_status">
                <option value="0" <?=set_value('record_status',  ((isset($record_status) && $record_status=='0') ? 'selected' : ''))?> >ปิด</option>
                <option value="1" <?=set_value('record_status',  ((isset($record_status) && $record_status=='1') ? 'selected' : '' ));?> >เปิดใช้งาน</option>
                <option value="-1" <?=set_value('record_status',  ((isset($record_status) && $record_status=='-1') ? 'selected' : '' ));?> >ดราฟ</option>
                <option value="-2" <?=set_value('record_status',  ((isset($record_status) && $record_status=='-2') ? 'selected' : '' ));?> >ถังขยะ</option>
              </select>
            </div>
            
            <!-- start_date !-->
            <div <?= form_error('start_date','data-toggle="tooltip" style="background-color:#FDD"  title="','"'); ?>>
              <label for="start_date"><span class="required">*</span> วันที่แสดงผล:</label>
              <input type="text" name="start_date" id="start_date" value="<?=set_value('start_date', isset($start_date) ? date('Y-m-d', strtotime($start_date)) : date('Y-m-d') );?>" />
            </div>
            
            <div>
              <label for="hashtag"><span class="required">*</span> Editor's Picks:</label>
              <label class="switch  text-center" style="width:65px;height:20px">
              <input type="checkbox" class="switch-input switch-green " name="editor_picks" value="1" style="height:30px" <?=( isset($editor_picks)  && $editor_picks=='1') ? 'checked' : '' ?>>
              <span class="switch-label" data-on="On" data-off="Off"></span>
              <span class="switch-handle"></span>
              </label>  
            </div>
            
            <div>
              <label for="hashtag"><span class="required">*</span> สารานุกรม:</label>
              <label class="switch  text-center" style="width:65px;height:20px">
              <input type="checkbox" class="switch-input switch-green" name="encyclopedia" value="1"  style="height:30px" <?=( isset($encyclopedia)  && $encyclopedia=='1') ? 'checked' : '' ?>>
              <span class="switch-label" data-on="On" data-off="Off"></span>
              <span class="switch-handle"></span>
              </label>  
            </div>
            
            <div>
              <label>&nbsp;</label>
              <!-- HIDDEN !-->
              <!-- member_id, create_user_id, update_user_id !-->
              <input type="hidden" name="member_id" value="<?=set_value('member_id', $this->secure->get_user_session()->member_id)?>" />
              <input type="hidden" name="create_user_id" value="<?=set_value('create_user_id', $this->secure->get_user_session()->user_id)?>" />
              <input type="hidden" name="update_user_id" value="<?=$this->secure->get_user_session()->user_id;?>" />
            
              <button class="button save"><span>บันทึกข้อมูล</span></button>
            </div>
            
            <!--
            <br/> -- title	varchar	255
            <br/> -- description_short	text	0
            <br/> -- description_long	mediumtext	0
            <br/> -- seo_keywords	varchar	150
            <br/> -- seo_description	varchar	200
            <br/> -- thumb_path	varchar	50
            <br/> -- image_filename_s	varchar	30
            <br/> -- image_filename_m	varchar	150
            <br/> -- thumb_path	varchar	30
            <br/> -- credit_by	text	0
            <br/> -- record_status	char	2
            <br/> -- is_move	varchar	20
            <br/> -- start_date	date	0
            <br/> -- end_date	date	0
            <br/> -- hashtag	text	0
            
            <br/> dd ++ create_user_id	varchar	10
            <br/> dd ++ update_user_id	varchar	10
            <br/> dd -- member_id	varchar	10
            
            <br/> dd add_date	datetime	0
            <br/> dd last_update_date	timestamp	0
            <br/> dd cms_year_path	varchar	50
            <br/> dd littlemonk_year	varchar	150
            
            <br/> 00 approve_by	varchar	10
            
            <br/> xx show_date	date	0
            <br/> xx credit_by_name	varchar	50
            <br/> xx credit_by_url	varchar	100
            <br/> xx security_code	smallint	4
            !-->
                        
          </div>
          
        </div>
      
    </form>

    <?php if ($this->input->get('id') != '') { ?>
      <div style="margin:30px auto 15px auto;" title="เพิ่มไฟล์แนบ"><span style="font-size:18px; font-weight:bold;">ไฟล์แนบ</span>
        <a class="button save iframe cboxElement pull-right" style="background: crimson;" href="<?=$this->input->get('id') ? site_url(ADMIN_PATH.'/cmsblog/cmsblog-file-edit/'.$this->input->get('id')) :  '#'?> " ><span>เพิ่มไฟล์แนบ</span></a>
      </div> 
      <div>
        <table width="100%" border="1" class="list">
        <thead>
          <tr>
            <td>ชนิด</td>
            <td>ชื่อไฟล์</td>
            <td>ขนาดไฟล์</td>
            <td>สถานะ</td>
            <td width="100px;" >แก้ไข</td>
          </tr>
        </thead>
        <tbody>
        <?php 
        function formatBytes($size, $precision = 2) { 
          $base = log($size, 1024);
          $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   
          return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
        }
        ?>
        <?php if(isset($files)){ ?>
          <?php foreach($files as $r) { ?>
            <tr>
              <td><?=$r['file_type']?></td>
              <td>[<a href="<?=$this->config->item('static_url').$r['file_path'].$r['file_name']?>">ดาวน์โหลด</a>] <?=$r['file_title']?> </td>
              <td><?=formatBytes(intval($r['file_size'], 0))?></td>
              <td><?=$r['record_status']=='1' ? '<span style="color:green">เปิดใช้งาน</span>' : '<span style="color:red">ไม่เปิดใช้งาน</span>'?></td>
              <td align="right">
              <?php if($r['file_type']=='Video') { ?>
              <span title="reconvert"><a  class="btn btn-primary btn-xs iframe cboxElement" href="<?=$this->input->get('back') ? $this->input->get('back'): '/V3/'.ADMIN_PATH.'/cmsblog/cmsblog-file-reconvert/'.$id."/".$r['cmsblog_file_id']?> " ><i class="glyphicon glyphicon-facetime-video	Try it"></i></a></span>
              <?php } ?>
              <span title="แก้ไขไฟล์"><a class="btn btn-warning btn-xs iframe cboxElement" href="<?=$this->input->get('back')?$this->input->get('back'): '/V3/'.ADMIN_PATH.'/cmsblog/cmsblog-file-edit/'.$id."/".$r['cmsblog_file_id']."?method=EDIT"?> " ><i class="glyphicon glyphicon-cog"></i></a></span>
              <span title="delete"><a class="btn btn-danger btn-xs iframe cboxElement" href="<?=$this->input->get('back')?$this->input->get('back'): '/V3/'.ADMIN_PATH.'/cmsblog/cmsblog-file-edit/'.$id?> " ><i class="glyphicon glyphicon-trash"></i></a></span>
              </td>
            </tr>
          <?php } ?>
        <?php }else{ ?>
          <tr><td colspan="6" align="center">ไม่มีข้อมูล</td></tr>
        <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6" ></td>
          </tr>
        </tfoot>
      </table>
      </div>
    <?php }?>

  </div>

</div>
<div class="clear"></div>
<script type="text/javascript">
  $(document).ready( function() {
  var $tags_source =<?=$CI->tags()?>;
      $('#hashtag').tagEditor({
      autocomplete: {
        delay: 0, // show suggestions immediately
        position: { collision: 'flip' }, // automatic menu position up/down
        source: $tags_source
      },
      beforeTagSave: function(field, editor, tags, tag, val) {
        if(false){  /* ถ้าไม่ให้เพิ่ม tag อื่นๆ ที่มีอยู่ ให้ใส่ true */
          var found=false;
          $.each($tags_source, function(index, value){
            if(val.toLowerCase() == value.toLowerCase()){
              found=true;
              return true;
            }
          });
          if(!found){
            return false;
          }
        }
      },
    });
    
    
    $('#start_date, #end_date, #event_date').datepicker({dateFormat:'yy-mm-dd'});
    $('[data-toggle="tooltip"]').tooltip(); 
    
    var ckeditor_config = { 
        toolbar : [
                        { name: 'basicstyles', items : ['Format', 'Bold','Italic','Underline','JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',/*'Subscript','Superscript','Strike','-',*/'RemoveFormat'/*, 'TextColor','BGColor'*/ ] },
                        { name: 'insert', items : [ 'Image', 'Iframe', 'AddLayout', 'oembed', 'Table',/*'HorizontalRule',*/'SpecialChar', 'Link'] },
                        { name: 'view', items : [ 'Maximize', 'Preview', 'Source' /*,'Styles', 'Format', 'Font', 'FontSize', 'About', 'WidgetTemplateMenu'*/] },
                    ],
        entities : true,
        extraPlugins : 'oembed,image2,layoutmanager,imageresponsive',
        resize_enabled: true,
        filebrowserBrowseUrl : '<?= theme_url('/assets/js/kcfinder/browse.php?type=files') ?>',
        filebrowserImageBrowseUrl : '<?= theme_url('/assets/js/kcfinder/browse.php?type=images&mediastock=true')?>',
        filebrowserFlashBrowseUrl : '<?= theme_url('/assets/js/kcfinder/browse.php?type=flash')?>',
        filebrowserUploadUrl : '<?= theme_url('/assets/js/kcfinder/upload.php?type=files')?>',
        filebrowserImageUploadUrl : '<?= theme_url('/assets/js/kcfinder/upload.php?type=images') ?>',
        filebrowserFlashUploadUrl : '<?= theme_url('/assets/js/kcfinder/upload.php?type=flash') ?>'
    };
    // SpecialChar
    ckeditor_config.specialChars = [ ['&#160','no-break space'], ['&#38','ampersand'], ['&#34','quotation mark'], ['&#162','cent sign'], ['&#8364','euro sign'], ['&#163','pound sign'], ['&#165','yen sign'], ['&#169','copyright sign'], ['&#174','registered sign'], ['&#8482','trade mark sign'], ['&#8240','per mille sign'], ['&#181','micro sign'], ['&#183','middle dot'], ['&#8226','bullet'], ['&#8230','three dot leader'], ['&#8242','minutes / feet'], ['&#8243','seconds / inches'], ['&#167','section sign'], ['&#182','paragraph sign'], ['&#223','sharp s / ess-zed'], ['&#8249','single left-pointing angle quotation mark'], ['&#8250','single right-pointing angle quotation mark'], ['&#171', 'left pointing guillemet'], ['&#187', 'right pointing guillemet'], ['&#8216', 'left single quotation mark'], ['&#8217', 'right single quotation mark'], ['&#8220', 'left double quotation mark'], ['&#8221', 'right double quotation mark'], ['&#8218', 'single low-9 quotation mark'], ['&#8222', 'double low-9 quotation mark'], ['&#60', 'less-than sign'], ['&#62', 'greater-than sign'], ['&#8804', 'less-than or equal to'], ['&#8805', 'greater-than or equal to'], ['&#8211', 'en dash'], ['&#8212', 'em dash'], ['&#175', 'macron'], ['&#8254', 'overline'], ['&#164', 'currency sign'], ['&#166', 'broken bar'], ['&#168', 'diaeresis'], ['&#161', 'inverted exclamation mark'], ['&#191', 'turned question mark'], ['&#710', 'circumflex accent'], ['&#732', 'small tilde'], ['&#176', 'degree sign'], ['&#8722', 'minus sign'], ['&#177', 'plus-minus sign'], ['&#247', 'division sign'], ['&#8260', 'fraction slash'], ['&#215', 'multiplication sign'], ['&#185', 'superscript one'], ['&#178', 'superscript two'], ['&#179', 'superscript three'], ['&#188', 'fraction one quarter'], ['&#189', 'fraction one half'], ['&#190', 'fraction three quarters'], ['&#402', 'function / florin'], ['&#8747', 'integral'], ['&#8721', 'n-ary sumation'], ['&#8734', 'infinity'], ['&#8730', 'square root'], ['&#8764', 'similar to'], ['&#8773', 'approximately equal to'], ['&#8776', 'almost equal to'], ['&#8800', 'not equal to'], ['&#8801', 'identical to'], ['&#8712', 'element of'], ['&#8713', 'not an element of'], ['&#8715', 'contains as member'], ['&#8719', 'n-ary product'], ['&#8743', 'logical and'], ['&#8744', 'logical or'], ['&#172', 'not sign'], ['&#8745', 'intersection'], ['&#8746', 'union'], ['&#8706', 'partial differential'], ['&#8704', 'for all'], ['&#8707', 'there exists'], ['&#8709', 'diameter'], ['&#8711', 'backward difference'], ['&#8727', 'asterisk operator'], ['&#8733', 'proportional to'], ['&#8736', 'angle'], ['&#180', 'acute accent'], ['&#184', 'cedilla'], ['&#170', 'feminine ordinal indicator'], ['&#186', 'masculine ordinal indicator'], ['&#8224', 'dagger'], ['&#8225', 'double dagger'], ['&#192', 'A - grave'], ['&#193', 'A - acute'], ['&#194', 'A - circumflex'], ['&#195', 'A - tilde'], ['&#196', 'A - diaeresis'], ['&#197', 'A - ring above'], ['&#198', 'ligature AE'], ['&#199', 'C - cedilla'], ['&#200', 'E - grave'], ['&#201', 'E - acute'], ['&#202', 'E - circumflex'], ['&#203', 'E - diaeresis'], ['&#204', 'I - grave'], ['&#205', 'I - acute'], ['&#206', 'I - circumflex'], ['&#207', 'I - diaeresis'], ['&#208', 'ETH'], ['&#209', 'N - tilde'], ['&#210', 'O - grave'], ['&#211', 'O - acute'], ['&#212', 'O - circumflex'], ['&#213', 'O - tilde'], ['&#214', 'O - diaeresis'], ['&#216', 'O - slash'], ['&#338', 'ligature OE'], ['&#352', 'S - caron'], ['&#217', 'U - grave'], ['&#218', 'U - acute'], ['&#219', 'U - circumflex'], ['&#220', 'U - diaeresis'], ['&#221', 'Y - acute'], ['&#376', 'Y - diaeresis'], ['&#222', 'THORN'], ['&#224', 'a - grave'], ['&#225', 'a - acute'], ['&#226', 'a - circumflex'], ['&#227', 'a - tilde'], ['&#228', 'a - diaeresis'], ['&#229', 'a - ring above'], ['&#230', 'ligature ae'], ['&#231', 'c - cedilla'], ['&#232', 'e - grave'], ['&#233', 'e - acute'], ['&#234', 'e - circumflex'], ['&#235', 'e - diaeresis'], ['&#236', 'i - grave'], ['&#237', 'i - acute'], ['&#238', 'i - circumflex'], ['&#239', 'i - diaeresis'], ['&#240', 'eth'], ['&#241', 'n - tilde'], ['&#242', 'o - grave'], ['&#243', 'o - acute'], ['&#244', 'o - circumflex'], ['&#245', 'o - tilde'], ['&#246', 'o - diaeresis'], ['&#248', 'o slash'], ['&#339', 'ligature oe'], ['&#353', 's - caron'], ['&#249', 'u - grave'], ['&#250', 'u - acute'], ['&#251', 'u - circumflex'], ['&#252', 'u - diaeresis'], ['&#253', 'y - acute'], ['&#254', 'thorn'], ['&#255', 'y - diaeresis'], ['&#913', 'Alpha'], ['&#914', 'Beta'], ['&#915', 'Gamma'], ['&#916', 'Delta'], ['&#917', 'Epsilon'], ['&#918', 'Zeta'], ['&#919', 'Eta'], ['&#920', 'Theta'], ['&#921', 'Iota'], ['&#922', 'Kappa'], ['&#923', 'Lambda'], ['&#924', 'Mu'], ['&#925', 'Nu'], ['&#926', 'Xi'], ['&#927', 'Omicron'], ['&#928', 'Pi'], ['&#929', 'Rho'], ['&#931', 'Sigma'], ['&#932', 'Tau'], ['&#933', 'Upsilon'], ['&#934', 'Phi'], ['&#935', 'Chi'], ['&#936', 'Psi'], ['&#937', 'Omega'], ['&#945', 'alpha'], ['&#946', 'beta'], ['&#947', 'gamma'], ['&#948', 'delta'], ['&#949', 'epsilon'], ['&#950', 'zeta'], ['&#951', 'eta'], ['&#952', 'theta'], ['&#953', 'iota'], ['&#954', 'kappa'], ['&#955', 'lambda'], ['&#956', 'mu'], ['&#957', 'nu'], ['&#958', 'xi'], ['&#959', 'omicron'], ['&#960', 'pi'], ['&#961', 'rho'], ['&#962', 'final sigma'], ['&#963', 'sigma'], ['&#964', 'tau'], ['&#965', 'upsilon'], ['&#966', 'phi'], ['&#967', 'chi'], ['&#968', 'psi'], ['&#969', 'omega'], ['&#8501', 'alef symbol'], ['&#982', 'pi symbol'], ['&#8476', 'real part symbol'], ['&#978', 'upsilon - hook symbol'], ['&#8472', 'Weierstrass p'], ['&#8465', 'imaginary part'], ['&#8592', 'leftwards arrow'], ['&#8593', 'upwards arrow'], ['&#8594', 'rightwards arrow'], ['&#8595', 'downwards arrow'], ['&#8596', 'left right arrow'], ['&#8629', 'carriage return'], ['&#8656', 'leftwards double arrow'], ['&#8657', 'upwards double arrow'], ['&#8658', 'rightwards double arrow'], ['&#8659', 'downwards double arrow'], ['&#8660', 'left right double arrow'], ['&#8756', 'therefore'], ['&#8834', 'subset of'], ['&#8835', 'superset of'], ['&#8836', 'not a subset of'], ['&#8838', 'subset of or equal to'], ['&#8839', 'superset of or equal to'], ['&#8853', 'circled plus'], ['&#8855', 'circled times'], ['&#8869', 'perpendicular'], ['&#8901', 'dot operator'], ['&#8968', 'left ceiling'], ['&#8969', 'right ceiling'], ['&#8970', 'left floor'], ['&#8971', 'right floor'], ['&#9001', 'left-pointing angle bracket'], ['&#9002', 'right-pointing angle bracket'], ['&#9674', 'lozenge'], ['&#9824', 'black spade suit'], ['&#9827', 'black club suit'], ['&#9829', 'black heart suit'], ['&#9830', 'black diamond suit'], ['&#8194', 'en space'], ['&#8195', 'em space'], ['&#8201', 'thin space'], ['&#8204', 'zero width non-joiner'], ['&#8205', 'zero width joiner'], ['&#8206', 'left-to-right mark'], ['&#8207', 'right-to-left mark'], ['&#173', 'soft hyphen']] ;
    

    
    // Text Format
    ckeditor_config.format_tags = 'p;h1;h2;h3';
    // Bootstrap Layout
    ckeditor_config.layoutmanager_loadbootstrap = true;
    ckeditor_config.allowedContent=true;
    // ลบ Style เมื่อมาวาง
    // ดูเพิ่มเติมที่นี่ http://docs.ckeditor.com/#!/guide/dev_advanced_content_filter 
     // ckeditor_config.allowedContent = 'h1 h2 h3 p blockquote strong figure figcaption em; a[!href]; img(left,right)[!src,alt,width,height]; div(*)';
    // ckeditor_config.disallowedContent = '*[style*] {style*} {class*}';
        // CSS
    // ckeditor_config.contentsCss = '<?=theme_url('/assets/js/ckeditor/contents.css')?>';
    
    $('.ckeditor_textarea').each(function(index) {
        var editor = CKEDITOR.replace($(this).attr('id'), ckeditor_config); 
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
    
  });
</script>
<style>
p.error{dsisplay:none;}
.tag-editor { border: 1px solid #aaaaaa; }
.cboxTitle{display: none;}
</style>