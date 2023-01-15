<?php
/*
* Einstein's Eyes custom form_helper
*/

// Override form_hidden to implement an id attribute
if ( ! function_exists('form_hidden'))
{
    function form_hidden($name, $value = '', $id = false)
    {
        if ( ! is_array($name))
        {
            return '<input type="hidden" id="'.($id ? $id : $name).'" name="'.$name.'" value="'.form_prep($value).'" />';
        }

        $form = '';

        foreach ($name as $name => $value)
        {
            $form .= "\n";
            $form .= '<input type="hidden"  id="'.($id ? $id : $name).'" name="'.$name.'" value="'.form_prep($value).'" />';
        }

        return $form;
    }
}


if(!function_exists('form_upload_image')){
  /* 
  * EXAMPLE
  * $params = array( 
  * 'name'=>'image_filename_l', 
  * 'file'=>set_value('image_filename_l_file'), 
  * 'image'=>set_value('image_filename_l_url'), 
  * width'=>1024,
  * 'height'=>600, );
  */
  
  function form_upload_image($image_name='', $image_name_file='', $image_name_url=null, $width=200, $height=120, $extra='') {
    $form='';
    // $width=$params['width'];
    // $height=$params['height'];
    
    $no_image='http://dummyimage.com/'.$width.'x'.$height.'/333/fff.png';
    // $no_image=
    // $image_name=$params['name'];
    // $image_name_file=$params['file'];
    // $image_name_url=$image_name_url ? $image_name_url : $no_image;
    
    // $c=array_shift($params);
    // _vd($c);
    // die;
    //$source_image, $width = 0, $height = 0, $crop = FALSE, $props = array()
    // $image_name_url=image_thumb($image_name_url, $width, $height);
// _vd($image_name_file);

    $form .= '<img src="'.($image_name_file ? $image_name_url : $no_image).'" id="'.$image_name.'_img" onclick="$(\'#'.$image_name.'_file\').click();" '.$extra.' />';
    $form .= '<input type="file" name="'.$image_name.'_file" id="'.$image_name.'_file" value="'.$image_name_file.'" class="form-control" style="display:none;" onchange="'.$image_name.'_readURL(this)" />';
    $form .= '<input type="hidden" name="'.$image_name.'_url" id="'.$image_name.'_url" value="'.$image_name_file.'" />';
    $form .= '<span id="'.$image_name.'_x" onclick="if(confirm(\'ยืนยันการลบ\')){ $(\'#'.$image_name.'_img\').attr(\'src\',\''.$no_image.'\'); $(\'#'.$image_name.'_url\').val(\'\'); $(\'#'.$image_name.'_file\').val(\'\'); $(this).hide(); }" style="display:none;">[X]</span>';
    $form .="
    <script>
    function ".$image_name."_readURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              $('#".$image_name."_img').attr('src', e.target.result); //.height($height);
          };
          reader.readAsDataURL(input.files[0]);
      } else {
        var img = '$no_image';
        $('#".$image_name."_img').attr('src',img); //.height($height);
      }
      $(\"#".$image_name."_x\").show().css(\"margin-right\",\"10px\");
    }";
    if($image_name_file) {
      $form .="$(\"#".$image_name."_x\").show().css(\"margin-right\",\"10px\");";
    }
    $form .="</script>";
    return $form;
  }
}

// Override validaton_errors to add error class to paragraph tags
if ( ! function_exists('validation_errors'))
{
	function validation_errors($prefix = '', $suffix = '')
	{
		if (FALSE === ($OBJ =& _get_validation_object()))
		{
			return '';
		}

        if($prefix == '' && $suffix == '')
        {
            $prefix = '<p class="error">';
            $suffix = '</p>';
        }

		return $OBJ->error_string($prefix, $suffix);
	}
}
