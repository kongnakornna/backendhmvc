<?php $CI = &get_instance(); ?>
<?php 	
$urlicss=base_url('/assets/css/');  
?>

<link rel="stylesheet" href="<?php echo $urlicss;?>/form_profile_edit.css" />
<script>
$(function() {

    var loading = function() {
        // add the overlay with loading image to the page
        var over = '<div id="overlay">' +
            '<img id="loading" src="https://media.giphy.com/media/3o85xvbL56JdWiclqw/giphy.gif">' +
            '</div>';
       
        $(over).appendTo('body');
        var offset_t = $(over).offset().top - $(window).scrollTop();
        return true;
        // click on the overlay to remove it
        //$('#overlay').click(function() {
        //    $(this).remove();
        //});

        // hit escape to close the overlay
        $(document).keyup(function(e) {
            if (e.which === 27) {
                $('#overlay').remove();
            }
        });
    };

    // you won't need this button click
    // just call the loading function directly
    // $('button').click(loading);
    $('#formprofile').submit(loading);
});
</script>




<?php 	
$urlaecthemassets=base_url('/assets/aecthemassets/');  
$urlimage22=base_url('/member/cropimage?user_id='.$user_id);  
?>

<div class="container form-profile" style="padding:30px;">
<h1 class="">MY PROFILE / ข้อมูลสมาชิก</h1>
    <form action="" method="POST" enctype="multipart/form-data" id="formprofile">
        <div class="row">
          <div class="col-md-3">
            <div class="col-md-12">
			


 
</head>
<body>
  <div id="qunit"></div>
  <div id="qunit-fixture"></div>
<?php ########################################?>
		<!-- bootstrap & fontawesome -->
		 
		<link rel="stylesheet" href="<?php echo $urlaecthemassets;?>/css/font-awesome.min.css" />
		<!-- page specific plugin styles -->
 
 
 
  
<?php ########################################
/*
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable" style="position: absolute; height: 700px; width: 700px; top: 80.5px; left: 250.5px; display: block;" tabindex="-1" role="dialog" aria-describedby="dialog-message" aria-labelledby="ui-id-1">
*/
?>
<?php $url=base_url('/assets/aecthemassets/'); ?>
<!-- 
<link rel="stylesheet" href="<?php echo $urlaecthemassets;?>/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo $urlaecthemassets;?>/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo $urlaecthemassets;?>/css/ace-fonts.css" />
-->
<!-- ace styles -->
 
<script type="text/javascript">
try{ace.settings.check('main-container' , 'fixed')}catch(e){}
</script>
 
<div class="form-group"> 
<?php 
			 
			$urlimage=base_url('/member/cropimage?user_id='.$user_id);  
			$widthimage=(int)$widthimage;
			$heightimage=(int)$heightimage;
			if($widthimage>=301){
			  echo '<center> โปรดคลิกครอบรูป </center>'; 
			  #echo '<pre> $widthimage=>';print_r($widthimage);echo '</pre>'; 
			  #echo '<pre> $heightimage=>';print_r($heightimage);echo '</pre>'; 
?>





<a href="#" id="id-btn-dialog1"><img src="<?php echo $psn_display_image; ?>" border="0" class="img-thumbnail" style="height:200px;width:200px;" /></a> 
			<?php }else{?>  
			  <img src="<?php echo $psn_display_image; ?>" border="0" class="img-thumbnail" style="height:200px;width:200px;" /> 
			<?php }?>  

<div id="dialog-message" class="hide">
<p> <div> 
    <object type="text/html" data="<?php echo $urlimage;?>" width="750px" height="600px" style="overflow:auto;  ridge white"></object>
</div></p>
											 
										</div><!-- #dialog-confirm -->
								 

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo $urlimage22;?>/js/jquery.min.js'>"+"<"+"/script>");
		</script>
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo $urlimage22;?>/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo $urlaecthemassets;?>/js/bootstrap.min.js"></script>
		<!-- page specific plugin scripts -->
		<script src="<?php echo $urlaecthemassets;?>/js/jquery-ui.min.js"></script>
		<script src="<?php echo $urlaecthemassets;?>/js/jquery.ui.touch-punch.min.js"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				//override dialog's title function to allow for HTML titles
				$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
				
					_title: function(title) {
						var $title = this.options.title || '&nbsp;'
						if( ("title_html" in this.options) && this.options.title_html == true )
							title.html($title);
						else title.text($title);
					}
				}));
			
				$( "#id-btn-dialog1" ).on('click', function(e) {
					e.preventDefault();
					var dialog = $( "#dialog-message" ).removeClass('hide').dialog({
						modal: true,
						title: "<div class='widget-header widget-header-small'> Crop Image</div>",
						title_html: true,
						 
					});
					$( "#dialog-message"  ).dialog( "option","minWidth",750 );
					$( "#dialog-message"  ).dialog( "option","minHeight",600 );

			
					 // alert(dialog);
					dialog.data( "uiDialog" )._title = function(title) {
						title.html( this.options.title );
					};
					 
					 
				});
					
			});
		</script>
 






 <?php echo form_upload(array('id' => 'psn_display_image_file', 'name' => 'psn_display_image_file', 'class'=>'form-control')); ?> 
        
</div> 
<!--[if !IE]> -->
<script type="text/javascript">
window.jQuery || document.write("<script src='<?php echo $url;?>/js/jquery.min.js'>"+"<"+"/script>");
</script>
<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo $url;?>/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="<?php echo $urlaecthemassets;?>/js/bootstrap.min.js"></script>
<script src="<?php echo $urlaecthemassets;?>/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo $urlaecthemassets;?>/js/chosen.jquery.min.js"></script>
		<!-- ace scripts -->
		<script src="<?php echo $urlaecthemassets;?>/js/ace-elements.min.js"></script>
		<script src="<?php echo $urlaecthemassets;?>/js/ace.min.js"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				$('.chosen-select').chosen({allow_single_deselect:true}); 
				//resize the chosen on window resize
				$('#id-input-file-1 , #id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
				//pre-show a file name, for example a previously selected file
				//$('#id-input-file-1').ace_file_input('show_file_list', ['myfile.txt'])
				$('#psn_display_image_file').ace_file_input({
					style:'well',
					btn_choose:'Uploads รูป',
					btn_change:null,
					//no_icon:'ace-icon fa fa-cloud-upload',
					no_icon:'ace-icon',
					droppable:true,
					thumbnail:'small'//large | fit 	| small
					//,icon_remove:null//set null, to hide remove/reset button
					/**,before_change:function(files, dropped) {
						//Check an example below
						//or examples/file-upload.html
						return true;
					}*/
					/**,before_remove : function() {
						return true;
					}*/
					,
					preview_error : function(filename, error_code) {
						//name of the file that failed
						//error_code values
						//1 = 'FILE_LOAD_FAILED',
						//2 = 'IMAGE_LOAD_FAILED',
						//3 = 'THUMBNAIL_FAILED'
						//alert(error_code);
					}
			
				}).on('change', function(){
					//console.log($(this).data('ace_input_files'));
					//console.log($(this).data('ace_input_method'));
				});
			});
</script>

 	  
<?php ########################################?>	





			  </div>
          </div>

	  
          <div class="col-md-9"> 
          
            <div class="form-group"> Username <div class="form-control col-md-9"><?php echo $user_username?> <a href="/member/change" class='btn btn-danger btn-xs pull-right'>เปลี่ยนรหัสผ่าน</a></div></div>
            <div class="form-group"> อีเมลล์ : (Email name) <div class="form-control"><?php echo $user_email?></div></div>
            <div class="form-group"> <span style="color:red">*</span> ชื่อที่แสดง : (Display name) <?php echo form_input(array('id' => 'psn_display_name', 'name' => 'psn_display_name','value'=> $psn_display_name, 'placeholder'=>'ชื่อที่แสดง', 'class'=>'form-control', 'maxlength'=>'100')); ?></div>
            <div class="form-group"> <span style="color:red">*</span> เพศ : (Gender)  <span style="color:red"><?php   echo !empty($error) ? $error : ''; ?></span>
            <?php $gender = array(
                array('gender_id'=>'', 'gender_name'=>'เลือกเพศ'),
                array('gender_id'=>'1', 'gender_name'=>'ชาย'),
                array('gender_id'=>'2', 'gender_name'=>'หญิง'), 
              ); ?>
                <?php echo '<select class="form-selectpicker form-control" id="psn_sex" name="psn_sex">'; ?>
                  <?php foreach($gender as $v) {?>
                  <?php echo '<option value="'.$v['gender_id'].'" '. ($v['gender_id']==$psn_sex ?  'selected' : '' ).'>' . $v['gender_name'].'</option>'; ?>
                  <?php } ?>
                <?php echo '</select>'; ?>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6"> 
                <div class="form-group">ชื่อ : (First name) 	 <?php echo form_input(array('id' => 'psn_firstname', 'name' => 'psn_firstname','value'=>$psn_firstname, 'placeholder'=>'ชื่อ', 'class'=>'form-control', 'maxlength'=>'100')); ?></div>
          </div>
          <div class="col-md-6"> 
              <div class="form-group">นามสกุล : (Family name) <?php echo form_input(array('id' => 'psn_lastname', 'name' => 'psn_lastname','value'=>$psn_lastname, 'placeholder'=>'นามสกุล', 'class'=>'form-control', 'maxlength'=>'100')); ?></div>
          </div>
        </div>

        <?php
          function to_thai($psn_birthdate){
            if(strtotime($psn_birthdate)){
              list($Y, $m, $d) = explode('-', $psn_birthdate);
              $Y = $Y + 543;
              return "$d-$m-$Y";
            }else{
              $psn_birthdate = date('Y-m-d', time());
              list($Y, $m, $d) = explode('-', $psn_birthdate);
              $Y = $Y + 543;
              return "$d-$m-$Y";
            }
          }
          function to_time($psn_birthdate){
            if(strtotime($psn_birthdate)){
              return $psn_birthdate;
            }else{
              $psn_birthdate = date('Y-m-d', time());
              return $psn_birthdate;
            }
          }
          ?>
          
       <div class="row">
          <div class="form-group col-md-4">เลขที่บัตรประชาชน : (ID card number) <?php echo form_input(array('id' => 'psn_id_number', 'name' => 'psn_id_number','value'=>$psn_id_number, 'placeholder'=>'เลขที่บัตรประชาชน', 'class'=>'form-control', 'maxlength'=>'13')); ?></div>
          <div class="form-group col-md-4">วันเกิด : (Date of birth) 
          <?php echo form_input(array('id' => 'psn_birthdate_d', 'name' => 'psn_birthdate_d','value'=>to_thai($psn_birthdate), 'placeholder'=>'วันเกิด', 'class'=>'form-control', 'maxlength'=>'10', 'readonly'=>true, 'style'=>'cursor:pointer; background-color: #FFFFFF')); ?>
          <?php echo form_hidden(array('psn_birthdate'=>to_time($psn_birthdate))); ?>
          </div>
          
          
          <div class="form-group col-md-4">เบอร์โทรติดต่อ : (Phone) <?php echo form_input(array('id' => 'psn_tel', 'name' => 'psn_tel','value'=>$psn_tel, 'placeholder'=>'เบอร์โทรติดต่อ', 'class'=>'form-control', 'maxlength'=>'40')); ?></div>
        </div>
          
        <div class="row">
          <div class="col-md-6"> 
              <div class="form-group">ที่อยู่ : (Postal address) <?php echo form_textarea(array('id' => 'psn_address', 'name' => 'psn_address','value'=>$psn_address, 'placeholder'=>'ที่อยู่', 'class'=>'form-control', 'rows'=>'5')); ?></div>
          </div>
          <div class="col-md-6"> 
              <div class="form-group">จังหวัด : (Province)  
                <?php echo '<select class="form-selectpicker form-control" id="psn_province" name="psn_province">'; ?>
                  <?php foreach($CI->member_model->getProvince() as $v) {?>
                  <?php echo '<option value="'.$v['province_id'].'" '. ($v['province_id']==$psn_province ?  'selected' : '' ).'>' . $v['province_name'].'</option>'; ?>
                  <?php } ?>
                <?php echo '</select>'; ?>
               </div>
              <div class="form-group">รหัสไปรษณีย์ : (Postal code) <?php echo form_input(array('id' => 'psn_postcode', 'name' => 'psn_postcode','value'=>$psn_postcode, 'placeholder'=>'รหัสไปรษณีย์', 'class'=>'form-control', 'maxlength'=>'5')); ?></div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group"> อาชีพ : (Career)
              <?php echo '<select class="form-selectpicker form-control" id="job_name" name="job_name">'; ?>
              
                <?php foreach($CI->member_model->getCareer() as $v) { ?>
                <?php echo '<option value="'.$v['occ_name'].'" '.  ($v['occ_name']==$job_name ?  'selected' : '' ).'>' . $v['occ_name'].'</option>'; ?>
                <?php } ?>
              <?php echo '</select>'; ?>
              </select>
            </div>
            <div class="form-group">วุฒิการศึกษา : (Educational background
              <?php echo '<select class="form-selectpicker form-control" id="job_edu_degree" name="job_edu_degree">'; ?>
                <?php foreach($CI->member_model->getQualification() as $v) { ?>
                <?php echo '<option value="'.$v['qual_name'].'" '. ($v['qual_name']==$job_edu_degree ?  'selected' : '' ) . '>' . $v['qual_name'].'</option>'; ?>
                <?php } ?>
              <?php echo '</select>'; ?>
            </div>
          </div>
        
          <div class="col-md-6">
            <div class="form-group">โรงเรียน : (School name) <?php echo form_input(array('id' => 'job_edu_name', 'name' => 'job_edu_name','value'=>$job_edu_name, 'placeholder'=>'ชื่อโรงเรียน', 'class'=>'form-control', 'maxlength'=>'100')); ?></div>
            <div class="form-group">ระดับชั้น : (School Level) <?php echo form_input(array('id' => 'job_edu_level', 'name' => 'job_edu_level','value'=>$job_edu_level, 'placeholder'=>'ป. 1/1', 'class'=>'form-control', 'maxlength'=>'100')); ?></div>
            <?php /*<div class="form-group">ชื่อบริษัท : (Work at) <?php echo form_input(array('id' => 'job_edu_level', 'name' => 'job_edu_level','value'=>set_value('job_edu_level'), 'placeholder'=>'อีเมล หรือ ชื่อสมาชิก', 'class'=>'form-control')); ?></div> */?>
          </div>
        </div>
        <div class="row text-center">
          <?php echo form_button(array('type' => 'submit', 'content' => 'บันทึกข้อมูล', 'class'=>'btn btn-success')) ?>
          <?php echo form_button(array('type' => 'reset', 'content' => 'ยกเลิก', 'class'=>'btn btn-info')) ?>
        </div>
    </form>
</div>


  <div>
      
  
    
    
    

 <?php /*   ข้อความต้อนรับ : (Welcome message) <?php echo form_input(array('id' => 'user_username', 'name' => 'user_username','value'=>$user_username, 'placeholder'=>'อีเมล หรือ ชื่อสมาชิก', 'class'=>'form-control')); ?> */ ?>
</div>
<style>
.form-profile{font-size:small; font-weight:700; }
.vcenter { display: inline-block; vertical-align: middle; float: none; }
</style>

<script>
// $(function(){
    // $('#psn_birthdate').datetimepicker();
// });  
</script>

<script>
$(function(){  
    var dateBefore=null;  
    $("#psn_birthdate_d").datepicker({  
        dateFormat: 'dd-mm-yy',  
        dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],   
        monthNamesShort: ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'],  
        changeMonth: true,  
        changeYear: true,  
        yearRange: "-100:+0",
        beforeShow:function(){    
            if($(this).val()!=""){  
                var arrayDate=$(this).val().split("-");       
                arrayDate[2]=parseInt(arrayDate[2])-543;  
                $(this).val(arrayDate[0]+"-"+arrayDate[1]+"-"+arrayDate[2]);  
                $('#psn_birthdate').val((parseInt(arrayDate[2])-543)+'-'+arrayDate[1]+'-'+arrayDate[0]); 
            }  
            setTimeout(function(){  
                $.each($(".ui-datepicker-year option"),function(j,k){  
                    var textYear=parseInt($(".ui-datepicker-year option").eq(j).val())+543;  
                    $(".ui-datepicker-year option").eq(j).text(textYear);  
                });               
            },50);  
        },  
        onChangeMonthYear: function(){  
            setTimeout(function(){  
                $.each($(".ui-datepicker-year option"),function(j,k){  
                    var textYear=parseInt($(".ui-datepicker-year option").eq(j).val())+543;  
                    $(".ui-datepicker-year option").eq(j).text(textYear);  
                });               
            },50);        
        },  
        onClose:function(){  
            if($(this).val()!="" && $(this).val()==dateBefore){
                var arrayDate=dateBefore.split("-");  
                arrayDate[2]=parseInt(arrayDate[2])+543;  
                $(this).val(arrayDate[0]+"-"+arrayDate[1]+"-"+arrayDate[2]);
                $('#psn_birthdate').val((parseInt(arrayDate[2])-543)+'-'+arrayDate[1]+'-'+arrayDate[0]);           
            }         
        },  
        onSelect: function(dateText, inst){   
            dateBefore=$(this).val();  
            var arrayDate=dateText.split("-");  
            arrayDate[2]=parseInt(arrayDate[2])+543;  
            $(this).val(arrayDate[0]+"-"+arrayDate[1]+"-"+arrayDate[2]);  
            $('#psn_birthdate').val((parseInt(arrayDate[2])-543)+'-'+arrayDate[1]+'-'+arrayDate[0]); 
        }     
    });  
});  
</script>  
<style>
.ui-datepicker select {
  color:#333; font-size:0.8em;
}
</style>