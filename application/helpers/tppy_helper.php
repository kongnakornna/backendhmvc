<?php

function get_url($url = '', $ver = '') {
    if (!$ver) {
        return site_url($url);
    } else {
        return site_url($ver . "/" . $url);
    }
}

//function get_vdo_thumb_url($old_path, $file_path, $file_id) {
//    //$pathCheckFile = "/data/product/trueplookpanya/www/static/trueplookpanya/media/";
//    if (getimagesize($old_path)) {
//        $url = $old_path;
//    } else {
//        $url = 'http://static.trueplookpanya.com/trueplookpanya/media/' . $file_path . '/CMS_' . $file_id . '_4_thumb.jpg';
//    }
//    return $url;
//}

function image_resize($path, $thumb, $width = 50, $heigth = 50) {
    $CI = & get_instance();
    if ($path) {
        $pathimg = $path . '/' . $thumb;
        $path_full = '/data/product/trueplookpanya/www/product/media/';
        $path_small = '/data/product/media/';

        //------------Static Data---------------
        $file_name_check = explode('_', $thumb);
        $path_full_new = '/data/product/trueplookpanya/www/static/trueplookpanya/media/';
        if (file_exists($path_full_new . $path) or 1) {
            if (file_exists($path_full_new . $path . '/' . $file_name_check[0] . '_4_thumb.jpg')) {
//                $path_full = $path_full_new;
                $pathimg = $path . '/' . $file_name_check[0] . '_4_thumb.jpg';
                //http://static.trueplookpanya.com/trueplookpanya/media/hash_knowledge/201401/40165/VDOA1000040165_6_thumb.jpg
                return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $pathimg;
            } else {
                return $CI->trueplook->image_resize($width, $heigth, $path, $thumb);
            }
        } else {
            return $CI->trueplook->image_resize($width, $heigth, $path, $thumb);
        }
        //------------Static Data---------------
    } else {
        return "http://static.trueplookpanya.com/trueplookpanya/media/home/images/default_img.png";
    }
}

function image_thumbnail($path = "", $thumb = "", $width = 50, $heigth = 50, $category) {

    $CI = & get_instance();
    $CI->load->library('simpleimage');
    $path_full_new = '/data/product/trueplookpanya/www/static/trueplookpanya/media/';

    if ($path and $thumb) {
        switch ($category) {
            case "mcov":
                $file_name_check = explode('_', $thumb);
                if (count($file_name_check) == 3)
                    $file_name_check[0] = $file_name_check[0] . "_" . $file_name_check[1];
                $ext = pathinfo($thumb, PATHINFO_EXTENSION);
                $newPathFileName = $path . '/' . $file_name_check[0] . "_" . $width . "_" . $heigth . "." . $ext; //.$ext
                $newFullPathFileName = $path_full_new . $newPathFileName;
                $fullPathFilename = $path_full_new . $path . '/' . $file_name_check[0] . '_4_thumb.jpg';
                $fullPathFilename2 = $path_full_new . $path . '/' . $file_name_check[0] . '_320x240.png';
                $ck = true;
                if (file_exists($newFullPathFileName) and $ck) {
                    return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $newPathFileName;
                } elseif (file_exists($fullPathFilename2)) {
                    $fullPathFilename = $fullPathFilename2;
//                $path_full = $path_full_new;
                    $pathimg = $path . '/' . $file_name_check[0] . '_320x240.png';
                    $CI->simpleimage->load($fullPathFilename);
//                $CI->simpleimage->resize_crop($width, $heigth)->save($newFullPathFileName);
                    $CI->simpleimage->thumbnail($width, $heigth)->save($newFullPathFileName);

                    if (file_exists($newFullPathFileName)) {
                        return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $newPathFileName;
                    } else {
                        return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $pathimg;
                    }
                } elseif (file_exists($fullPathFilename)) {
                    $pathimg = $path . '/' . $file_name_check[0] . '_4_thumb.jpg';
                    $CI->simpleimage->load($fullPathFilename);
//                $CI->simpleimage->resize_crop($width, $heigth)->fill()->save($newFullPathFileName);
                    $CI->simpleimage->thumbnail($width, $heigth)->save($newFullPathFileName);
                    if (file_exists($newFullPathFileName)) {
                        return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $newPathFileName;
                    } else {
                        return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $pathimg;
                    }
                }
                break;
            case "product":
                $product_rootpath = '/data/product/trueplookpanya/www/product/media/';
                $thumb_info = pathinfo($thumb);
                $newPathFileName = $path . "/" . ((isset($thumb_info['filename']) and $thumb_info['filename']) ? $thumb_info['filename'] : '') . "_" . $width . "_" . $heigth . "." . ((isset($thumb_info['extension']) and $thumb_info['extension']) ? $thumb_info['extension'] : ''); //.$ext

                $newFullPathFileName = $product_rootpath . $newPathFileName;

                $fullPathFileName = $product_rootpath . $path . '/' . $thumb;
                $ck = true;
                if (file_exists($newFullPathFileName) and $ck) {
                    return 'http://www.trueplookpanya.com/data/product/media/' . $newPathFileName;
                } elseif (file_exists($fullPathFileName)) {
                    $CI->simpleimage->load($fullPathFileName);
//                $CI->simpleimage->resize_crop($width, $heigth)->save($newFullPathFileName);
                    $CI->simpleimage->resize_crop($width, $heigth)->save($newFullPathFileName);

                    if (file_exists($newFullPathFileName)) {
                        return 'http://www.trueplookpanya.com/data/product/media/' . $newPathFileName;
                    } else {
                        return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $path . '/' . $thumb;
                    }
                }
                break;
            case "tppy":
                $product_rootpath = '/data/product/trueplookpanya/www/static/tppy/';
                $thumb_info = pathinfo($thumb);
                $path = $thumb_info['dirname'];
                $newPathFileName = $path . "/" . $thumb_info['filename'] . "_" . $width . "_" . $heigth . "." . $thumb_info['extension']; //.$ext
                $newFullPathFileName = $product_rootpath . $newPathFileName;

                $fullPathFileName = $product_rootpath . $thumb;
                $ck = true;
                if (file_exists($newFullPathFileName) and $ck) {
                    return 'http://static.trueplookpanya.com/tppy/' . $newPathFileName;
                } elseif (file_exists($fullPathFileName)) {
                    $CI->simpleimage->load($fullPathFileName);
                    $CI->simpleimage->resize_crop($width, $heigth)->save($newFullPathFileName);
                    if (file_exists($newFullPathFileName)) {
                        return 'http://static.trueplookpanya.com/tppy/' . $newPathFileName;
                    }
                }
                break;
            case "specialPath":
                return 'http://static.trueplookpanya.com/trueplookpanya/' . $path . $thumb;
                break;
        }
    }
    return "http://static.trueplookpanya.com/trueplookpanya/media/home/images/default_img.png";
}

function image_resize_dev($path, $thumb, $width = 50, $heigth = 50) {
    $CI = & get_instance();
    if ($path) {
        $pathimg = $path . '/' . $thumb;
        $path_full = '/data/product/trueplookpanya/www/product/media/';
        $path_small = '/data/product/media/';

        //------------Static Data---------------
        $file_name_check = explode('_', $thumb);
        $path_full_new = '/data/product/trueplookpanya/www/static/trueplookpanya/media/';
        $ext = pathinfo($thumb, PATHINFO_EXTENSION);
        $newPathFileName = $path . '/' . $file_name_check[0] . "_" . $width . "_" . $heigth . "." . $ext; //.$ext
        $newFullPathFileName = $path_full_new . $newPathFileName;
//        echo "path<br>";
//        echo $path_full_new . $path;
        if (file_exists($newFullPathFileName)) {
            return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $newPathFileName;
        } else {//if (is_dir($path_full_new . $path))
            $fullPathFilename = $path_full_new . $path . '/' . $file_name_check[0] . '_4_thumb.jpg';
            $fullPathFilename2 = $path_full_new . $path . '/' . $file_name_check[0] . '_320x240.png';
            $fullPathFilename3 = $path_full_new . $path . '/' . $file_name_check;
//            echo "0<br>";
//            echo $fullPathFilename2;
            if (file_exists($fullPathFilename2)) {
                $fullPathFilename = $fullPathFilename2;
//                $path_full = $path_full_new;
                $pathimg = $path . '/' . $file_name_check[0] . '_320x240.png';
                //http://static.trueplookpanya.com/trueplookpanya/media/hash_knowledge/201401/40165/VDOA1000040165_6_thumb.jpg
                //crop and resize
//                echo "1<br>";
//                echo $fullPathFilename;
//                echo "<br>";
//                echo $newFullPathFileName;
                $CI->simpleimage->load($fullPathFilename);
                $CI->simpleimage->resize_crop($width, $heigth)->save($newFullPathFileName);
                if (file_exists($newFullPathFileName)) {
                    return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $newPathFileName;
                } else {
                    return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $pathimg;
                }
            } elseif (file_exists($fullPathFilename)) {
//                $path_full = $path_full_new;
                $pathimg = $path . '/' . $file_name_check[0] . '_4_thumb.jpg';
                //http://static.trueplookpanya.com/trueplookpanya/media/hash_knowledge/201401/40165/VDOA1000040165_6_thumb.jpg
                //crop and resize
//                echo "2<br>";
//                echo $fullPathFilename;
//                echo "<br>";
                $CI->simpleimage->load($fullPathFilename);
                $CI->simpleimage->resize_crop($width, $heigth)->save($newFullPathFileName);
                if (file_exists($newFullPathFileName)) {
                    return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $newPathFileName;
                } else {
                    return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $pathimg;
                }
            } elseif (file_exists($fullPathFilename3)) {
//                $path_full = $path_full_new;
                $pathimg = $path . '/' . $fullPathFilename3;
                $file_name_check = explode('.', $thumb);
                $newPathFileName = $path . '/' . $file_name_check[0] . "_" . $width . "_" . $heigth . "." . $ext; //.$ext
                $newFullPathFileName = $path_full_new . $newPathFileName;
                //http://static.trueplookpanya.com/trueplookpanya/media/hash_knowledge/201401/40165/VDOA1000040165_6_thumb.jpg
                //crop and resize
//                echo "2<br>";
//                echo $fullPathFilename;
//                echo "<br>";
                $CI->simpleimage->load($fullPathFilename);
                $CI->simpleimage->resize_crop($width, $heigth)->save($newFullPathFileName);
                if (file_exists($newFullPathFileName)) {
                    return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $newPathFileName;
                } else {
                    return 'http://static.trueplookpanya.com/trueplookpanya/media/' . $pathimg;
                }
            } else {
                return $CI->trueplook->image_resize($width, $heigth, $path, $thumb);
            }
        }
    } else {
        return "http://static.trueplookpanya.com/trueplookpanya/media/home/images/default_img.png";
    }
}

function check_exists($path, $file, $main_path = '') {
    if ($main_path == '') {
        $main_path = '/data/product/trueplookpanya/www/product/media';
    }
    if (file_exists($main_path . '/' . $path . '/' . $file)) {
        return $file;
    } else {
        return false;
    }
}

function encode_security($value) {
    $encrypted_key = 'TrueplookpanyaDotComLINK';
    $CI = & get_instance();
    $CI->load->library('encrypt');
    $encrypted = $CI->encrypt->encode($value, $encrypted_key);
    return $encrypted;
}

function decode_security($value) {
    $encrypted_key = 'TrueplookpanyaDotComLINK';
    $CI = & get_instance();
    $CI->load->library('encrypt');
    $decode = $CI->encrypt->decode($value, $encrypted_key);
    return $decode;
}



function date_th($format, $timestamp=0){
  $D = array('อา', 'จ', 'อ',  'พ', 'พฤ', 'ศ', 'ส');
  $l = array('อาทิตย์', 'จันทร์', 'อังคาร',  'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์');
  $M = array('ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.');
  $F = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
  $timestamp = intval($timestamp, 0) > 0 ? $timestamp : time();
  $format = str_replace('Y', date('Y', $timestamp) + 543, $format);
  $format = str_replace('y', substr(date('Y', $timestamp) + 543, -2), $format);
  $format = str_replace('M', $M[date('m', $timestamp)-1], $format);
  $format = str_replace('F', $F[date('m', $timestamp)-1], $format);
  $format = str_replace('l', $l[date('w', $timestamp)], $format);
  $format = str_replace('D', $D[date('w', $timestamp)], $format);
  return date($format, $timestamp);
}

function curl_data($url, $curl_setopt=array()) {
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'trueplookpanya');

    foreach($curl_setopt as $k=>$v){
      curl_setopt($curl_handle, $k, $v);
    }

    $query = curl_exec($curl_handle);
    curl_close($curl_handle);
    return $query;
}

function getFBShare($url=null){
  $share_count=json_decode(curl_data("https://graph.facebook.com/?id=". urlencode(!empty($url) ?  $url : current_url())."&access_token=704799662982418|Nn4C3e5uaHRPmdmLBlpYGPG5x48", array(CURLOPT_TIMEOUT=>1000)))->share;
  return intval($share_count->share_count, 0);
}
