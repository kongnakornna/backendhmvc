 <?php 
$CI=&get_instance();
$this->template->add_javascript(theme_assets('/plugins/ckeditor/ckeditor.js'));
// $this->template->add_javascript(theme_assets('/plugins/ckeditor/adapters/jquery.js'));
// $this->template->add_stylesheet(theme_assets('/plugins/ckeditor/skins/mono/ckeditor.js'));

$this->template->add_javascript(theme_assets('/plugins/bootstrap-select/dist/js/bootstrap-select.js'));
$this->template->add_stylesheet(theme_assets('/plugins/bootstrap-select/dist/css/bootstrap-select.css'));

$this->template->add_stylesheet('/assets/css/input-switch/css/style.css');
?>
  <form method="post">
<div class="container-fluid" style="background-color:#FFB03D">
  <div class="container">
    <div class="row text-center">
      <img src="http://placehold.it/1240x300" style="width:100%; height:auto; max-width:1240px;" class="img-responsive">
    </div>
  </div>
</div>

<div class="container nopadding ttf-textB ttf-s-md" style="margin-top:20px;">
  <div class="row nopadding">
    <div class="col-md-12 text-center">
     <button class="btn btn-plookpanya" id="previsewButton" onclick="CKEDITOR.tools.callFunction(61,$('#cms_detail_long'));return false;">Preview</button>
     <button class="btn btn-plookpanya" type="submit">Save</button>
     <button class="btn btn-plookpanya" type="reset">Close</button>
      <div class=" pull-right">
       <label class="switch  text-center">
        <input type="checkbox" class="switch-input switch-green">
        <span class="switch-label" data-on="On" data-off="Off"></span>
        <span class="switch-handle"></span>
        <span class=" ttf-s-sm ttf-round text-center">Publish</span>
        </label>

        <button type="button" class="glyphicon glyphicon-trash text-danger ttf-s-sm text-bottom btn-ghost" style="top: 6px; position: relative;" >
          <span class="ttf-s-sm ttf-round text-center" style="display:block; font-weight:bold;">Delete</span>
        </button>
      </div>
     </div>
    </div>
    
    <div class="col-md-12" id="content_menu_container">
      <label style="font-size: 150%; color:#333;">หมวดหมู่</label> เลือกอย่างน้อย 1 หมวดหมู่
      <select class="col-md-12 select" name="content_menu[]">
      <option value="">หมวดหมู่หลัก</option>

      <?=$menu_list;?>
      </select>
     <script>
     $(function(){
       var $ddl_template= '<select class="col-md-12 select" name="content_menu[]">' +
        '<option value="">หมวดหมู่รอง</option>' +
        '<?=$menu_list;?>'+
      '</select>';
      
      $(document).on('change', '.select',function(){
        var $set=$("#content_menu_container > .select");
        var len = $set.length;
        $.each($set, function(i, obj){
          if($(obj).val() == '' && (i !=0 || i != len -1)){
            $(obj).remove();
          }
        });
        if($("#content_menu_container > .select").length < 5){
          $('.select').parent().last('select').append($ddl_template);
        }
      });
     });
     
     
     </script>
    </div>
    <?php/* _vd($content); */?>
    <div class="col-md-12">
      <label for="title" style="font-size: 150%; color:#333;">Subject</label>
    
      <input type="text" name="title" class="form-control" value="<?=set_value('title', $content['title'])?>" />
    </div>

    <div class="col-md-12" style="margin-top:20px">
      <textarea name="description_long" id="description_long" rows="3" class="ckeditor_textarea" ><?=set_value('description_long', $content['description_long'])?></textarea>
    </div>

    <div class="col-md-12">
      <label for="hashtag" style="font-size: 150%; color:#333;">Tags</label>
      <input type="text" name="hashtag" class="form-control" value="<?=set_value('hashtag', $content['hashtag'])?>" />
    </div>
  
    <div class="col-md-12 text-center" style="margin-top:20px">
     <button class="btn btn-plookpanya" id="previsewButton" onclick="CKEDITOR.tools.callFunction(61,$('#cms_detail_long'));return false;">Preview</button>
     <button class="btn btn-plookpanya" type="submit">Save</button>
     <button class="btn btn-plookpanya" type="reset">Close</button>
     <span>
      </span>
    </div>
  
  </div>
</div>
<style></style>
</form>

<script type="text/javascript">
  $(document).ready( function() {
    var ckeditor_config = { 
        toolbar : [
                        { name: 'basicstyles', items : ['Format', 'Bold','Italic','Underline','Subscript','Superscript','Strike','-','RemoveFormat', 'TextColor','BGColor' ] },
                        { name: 'insert', items : [ 'Image', 'Iframe', 'AddLayout', 'oembed', 'Table','HorizontalRule','SpecialChar', 'Link'] },
                        { name: 'view', items : [ 'Maximize', 'Preview' ] },
                    ],
        entities : true,

        extraPlugins : 'oembed,layoutmanager',
        height : '500px',
        resize_enabled: true,
        filebrowserBrowseUrl : '<?= theme_url('/assets/js/kcfinder/browse.php?type=files') ?>',
        filebrowserImageBrowseUrl : '<?= theme_url('/assets/js/kcfinder/browse.php?type=images')?>',
        filebrowserFlashBrowseUrl : '<?= theme_url('/assets/js/kcfinder/browse.php?type=flash')?>',
        filebrowserUploadUrl : '<?= theme_url('/assets/js/kcfinder/upload.php?type=files')?>',
        filebrowserImageUploadUrl : '<?= theme_url('/assets/js/kcfinder/upload.php?type=images') ?>',
        filebrowserFlashUploadUrl : '<?= theme_url('/assets/js/kcfinder/upload.php?type=flash') ?>'
    };
    ckeditor_config.specialChars = [ ['&#160','no-break space'], ['&#38','ampersand'], ['&#34','quotation mark'], ['&#162','cent sign'], ['&#8364','euro sign'], ['&#163','pound sign'], ['&#165','yen sign'], ['&#169','copyright sign'], ['&#174','registered sign'], ['&#8482','trade mark sign'], ['&#8240','per mille sign'], ['&#181','micro sign'], ['&#183','middle dot'], ['&#8226','bullet'], ['&#8230','three dot leader'], ['&#8242','minutes / feet'], ['&#8243','seconds / inches'], ['&#167','section sign'], ['&#182','paragraph sign'], ['&#223','sharp s / ess-zed'], ['&#8249','single left-pointing angle quotation mark'], ['&#8250','single right-pointing angle quotation mark'], ['&#171', 'left pointing guillemet'], ['&#187', 'right pointing guillemet'], ['&#8216', 'left single quotation mark'], ['&#8217', 'right single quotation mark'], ['&#8220', 'left double quotation mark'], ['&#8221', 'right double quotation mark'], ['&#8218', 'single low-9 quotation mark'], ['&#8222', 'double low-9 quotation mark'], ['&#60', 'less-than sign'], ['&#62', 'greater-than sign'], ['&#8804', 'less-than or equal to'], ['&#8805', 'greater-than or equal to'], ['&#8211', 'en dash'], ['&#8212', 'em dash'], ['&#175', 'macron'], ['&#8254', 'overline'], ['&#164', 'currency sign'], ['&#166', 'broken bar'], ['&#168', 'diaeresis'], ['&#161', 'inverted exclamation mark'], ['&#191', 'turned question mark'], ['&#710', 'circumflex accent'], ['&#732', 'small tilde'], ['&#176', 'degree sign'], ['&#8722', 'minus sign'], ['&#177', 'plus-minus sign'], ['&#247', 'division sign'], ['&#8260', 'fraction slash'], ['&#215', 'multiplication sign'], ['&#185', 'superscript one'], ['&#178', 'superscript two'], ['&#179', 'superscript three'], ['&#188', 'fraction one quarter'], ['&#189', 'fraction one half'], ['&#190', 'fraction three quarters'], ['&#402', 'function / florin'], ['&#8747', 'integral'], ['&#8721', 'n-ary sumation'], ['&#8734', 'infinity'], ['&#8730', 'square root'], ['&#8764', 'similar to'], ['&#8773', 'approximately equal to'], ['&#8776', 'almost equal to'], ['&#8800', 'not equal to'], ['&#8801', 'identical to'], ['&#8712', 'element of'], ['&#8713', 'not an element of'], ['&#8715', 'contains as member'], ['&#8719', 'n-ary product'], ['&#8743', 'logical and'], ['&#8744', 'logical or'], ['&#172', 'not sign'], ['&#8745', 'intersection'], ['&#8746', 'union'], ['&#8706', 'partial differential'], ['&#8704', 'for all'], ['&#8707', 'there exists'], ['&#8709', 'diameter'], ['&#8711', 'backward difference'], ['&#8727', 'asterisk operator'], ['&#8733', 'proportional to'], ['&#8736', 'angle'], ['&#180', 'acute accent'], ['&#184', 'cedilla'], ['&#170', 'feminine ordinal indicator'], ['&#186', 'masculine ordinal indicator'], ['&#8224', 'dagger'], ['&#8225', 'double dagger'], ['&#192', 'A - grave'], ['&#193', 'A - acute'], ['&#194', 'A - circumflex'], ['&#195', 'A - tilde'], ['&#196', 'A - diaeresis'], ['&#197', 'A - ring above'], ['&#198', 'ligature AE'], ['&#199', 'C - cedilla'], ['&#200', 'E - grave'], ['&#201', 'E - acute'], ['&#202', 'E - circumflex'], ['&#203', 'E - diaeresis'], ['&#204', 'I - grave'], ['&#205', 'I - acute'], ['&#206', 'I - circumflex'], ['&#207', 'I - diaeresis'], ['&#208', 'ETH'], ['&#209', 'N - tilde'], ['&#210', 'O - grave'], ['&#211', 'O - acute'], ['&#212', 'O - circumflex'], ['&#213', 'O - tilde'], ['&#214', 'O - diaeresis'], ['&#216', 'O - slash'], ['&#338', 'ligature OE'], ['&#352', 'S - caron'], ['&#217', 'U - grave'], ['&#218', 'U - acute'], ['&#219', 'U - circumflex'], ['&#220', 'U - diaeresis'], ['&#221', 'Y - acute'], ['&#376', 'Y - diaeresis'], ['&#222', 'THORN'], ['&#224', 'a - grave'], ['&#225', 'a - acute'], ['&#226', 'a - circumflex'], ['&#227', 'a - tilde'], ['&#228', 'a - diaeresis'], ['&#229', 'a - ring above'], ['&#230', 'ligature ae'], ['&#231', 'c - cedilla'], ['&#232', 'e - grave'], ['&#233', 'e - acute'], ['&#234', 'e - circumflex'], ['&#235', 'e - diaeresis'], ['&#236', 'i - grave'], ['&#237', 'i - acute'], ['&#238', 'i - circumflex'], ['&#239', 'i - diaeresis'], ['&#240', 'eth'], ['&#241', 'n - tilde'], ['&#242', 'o - grave'], ['&#243', 'o - acute'], ['&#244', 'o - circumflex'], ['&#245', 'o - tilde'], ['&#246', 'o - diaeresis'], ['&#248', 'o slash'], ['&#339', 'ligature oe'], ['&#353', 's - caron'], ['&#249', 'u - grave'], ['&#250', 'u - acute'], ['&#251', 'u - circumflex'], ['&#252', 'u - diaeresis'], ['&#253', 'y - acute'], ['&#254', 'thorn'], ['&#255', 'y - diaeresis'], ['&#913', 'Alpha'], ['&#914', 'Beta'], ['&#915', 'Gamma'], ['&#916', 'Delta'], ['&#917', 'Epsilon'], ['&#918', 'Zeta'], ['&#919', 'Eta'], ['&#920', 'Theta'], ['&#921', 'Iota'], ['&#922', 'Kappa'], ['&#923', 'Lambda'], ['&#924', 'Mu'], ['&#925', 'Nu'], ['&#926', 'Xi'], ['&#927', 'Omicron'], ['&#928', 'Pi'], ['&#929', 'Rho'], ['&#931', 'Sigma'], ['&#932', 'Tau'], ['&#933', 'Upsilon'], ['&#934', 'Phi'], ['&#935', 'Chi'], ['&#936', 'Psi'], ['&#937', 'Omega'], ['&#945', 'alpha'], ['&#946', 'beta'], ['&#947', 'gamma'], ['&#948', 'delta'], ['&#949', 'epsilon'], ['&#950', 'zeta'], ['&#951', 'eta'], ['&#952', 'theta'], ['&#953', 'iota'], ['&#954', 'kappa'], ['&#955', 'lambda'], ['&#956', 'mu'], ['&#957', 'nu'], ['&#958', 'xi'], ['&#959', 'omicron'], ['&#960', 'pi'], ['&#961', 'rho'], ['&#962', 'final sigma'], ['&#963', 'sigma'], ['&#964', 'tau'], ['&#965', 'upsilon'], ['&#966', 'phi'], ['&#967', 'chi'], ['&#968', 'psi'], ['&#969', 'omega'], ['&#8501', 'alef symbol'], ['&#982', 'pi symbol'], ['&#8476', 'real part symbol'], ['&#978', 'upsilon - hook symbol'], ['&#8472', 'Weierstrass p'], ['&#8465', 'imaginary part'], ['&#8592', 'leftwards arrow'], ['&#8593', 'upwards arrow'], ['&#8594', 'rightwards arrow'], ['&#8595', 'downwards arrow'], ['&#8596', 'left right arrow'], ['&#8629', 'carriage return'], ['&#8656', 'leftwards double arrow'], ['&#8657', 'upwards double arrow'], ['&#8658', 'rightwards double arrow'], ['&#8659', 'downwards double arrow'], ['&#8660', 'left right double arrow'], ['&#8756', 'therefore'], ['&#8834', 'subset of'], ['&#8835', 'superset of'], ['&#8836', 'not a subset of'], ['&#8838', 'subset of or equal to'], ['&#8839', 'superset of or equal to'], ['&#8853', 'circled plus'], ['&#8855', 'circled times'], ['&#8869', 'perpendicular'], ['&#8901', 'dot operator'], ['&#8968', 'left ceiling'], ['&#8969', 'right ceiling'], ['&#8970', 'left floor'], ['&#8971', 'right floor'], ['&#9001', 'left-pointing angle bracket'], ['&#9002', 'right-pointing angle bracket'], ['&#9674', 'lozenge'], ['&#9824', 'black spade suit'], ['&#9827', 'black club suit'], ['&#9829', 'black heart suit'], ['&#9830', 'black diamond suit'], ['&#8194', 'en space'], ['&#8195', 'em space'], ['&#8201', 'thin space'], ['&#8204', 'zero width non-joiner'], ['&#8205', 'zero width joiner'], ['&#8206', 'left-to-right mark'], ['&#8207', 'right-to-left mark'], ['&#173', 'soft hyphen']] ;
    ckeditor_config.allowedContent=true;
    ckeditor_config.layoutmanager_loadbootstrap = true;
    $('#description_long').each(function(index) {
        var editor = CKEDITOR.replace($(this).attr('id'), ckeditor_config); 
    });
  });
</script>

<script type="text/javascript">
    $(document).ready( function() {
        $('#previewButton').click( function () {
            var contents = $('#cms_detail_long').val();
            var mywin = window.open("", "ckeditor_preview", "location=0,status=0,scrollbars=0,width=500,height=500");

            $(mywin.document.body).html(contents);
        });
    });
</script>