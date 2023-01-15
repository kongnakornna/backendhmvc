<?php

function knowledge_img_url($mul_filename, $mul_thumbnail_file) {

    if ($row_related['mul_thumbnail_file'] == "") {

        $filename_only = substr($row_related['mul_filename'], 0, -4);
        $fileImage['v'] = get_vdo_thumb_knowledge_url("../../data/" . $row_related['year_path'] . "/media/" . $row_related['mul_destination'] . "/" . $filename_only . "_128x96.png", $row_related['mul_destination'], $filename_only);
    } else {
        $fileImage['v'] = "http://static.trueplookpanya.com/trueplookpanya/media/" . $row_related['mul_destination'] . '/' . $row_related['mul_thumbnail_file'];
    }
    //$fileImage['v'] = "../../data/".$row_related['year_path']."/media/ImageSizeMedia.php?img=".$row_related['mul_destination'].'/'.$row_related['mul_thumbnail_file'];
}

function get_vdo_thumb_url($old_path, $file_path, $file_id) {
    if (file_exists($old_path) or getimagesize($old_path)) {
        $url = $old_path;
    } else if ($file_path and $file_id and file_exists("/data/product/trueplookpanya/www/static/trueplookpanya/media/" . $file_path . '/CMS_' . $file_id . '.png')) {
        $url = 'http://static.trueplookpanya.com/trueplookpanya/media/' . $file_path . '/CMS_' . $file_id . '.png';
    } else if (getimagesize('http://static.trueplookpanya.com/trueplookpanya/media/' . $file_path . '/' . $file_id . '_4_thumb.jpg')) {
        $url = 'http://static.trueplookpanya.com/trueplookpanya/media/' . $file_path . '/' . $file_id . '_4_thumb.jpg';
    } else if (getimagesize('http://static.trueplookpanya.com/trueplookpanya/media/' . $file_path . '/' . $file_id . '.png')) {
        $url = 'http://static.trueplookpanya.com/trueplookpanya/media/' . $file_path . '/' . $file_id . '.png';
    } else {
        $url = 'http://static.trueplookpanya.com/trueplookpanya/media/' . $file_path . '/' . $file_id . '_128x96.png';
    }
    return $url;
}

//($row->mul_destination,$row->mul_thumbnail_file,$row->mul_filename);
function getThumbnail($file_path = "", $file_name = "", $thumbnail = "") {
    $CI = & get_instance();
    $CI->config->item('base_url');
    $filenameArr = pathinfo($file_name);
    $file_pre = $filenameArr['filename'];

    if (!$file_path or ! $file_name) {
        return $CI->config->item('root_url_kl_default');
    } else if ($thumbnail) {
        return $CI->config->item('root_url_kl') . $file_path . '/' . $thumbnail;
    } else if (file_exists($CI->config->item('root_path_kl') . $file_path . '/' . $file_pre . '_4_thumb.jpg')) {
        return $CI->config->item('root_url_kl') . $file_path . '/' . $file_pre . '_4_thumb.jpg';
    } else if (file_exists($CI->config->item('root_path_kl') . $file_path . '/' . $file_pre . '.png')) {
        return $CI->config->item('root_url_kl') . $file_path . '/' . $file_pre . '.png';
    } else if (file_exists($CI->config->item('root_path_kl') . $file_path . '/' . $file_pre . '_128x96.png')) {
        return $CI->config->item('root_url_kl') . $file_path . '/' . $file_pre . '_128x96.png';
    } else {
        return $CI->config->item('root_url_kl_default');
    }
}

function getExaminationActechFile($file = "" ,$ck = true){
    if(!$file)
        return false;
    $CI = & get_instance();
    $filepath = $CI->config->item('examination_attech_path').$file;
    if(file_exists($filepath) or $ck){
        return $CI->config->item('examination_attech_url').$file;
    }
}

function jwplayer($config = array()) {
    $CI = & get_instance();
    if (!strstr($_SERVER['HTTP_USER_AGENT'],'Android') && !strstr($_SERVER['HTTP_USER_AGENT'],'webOS') && !strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') && !strstr($_SERVER['HTTP_USER_AGENT'],'iPad') && !strstr($_SERVER['HTTP_USER_AGENT'],'iPod')) {
        $CI->template->add_javascript('assets/jwplayer/jwplayer.js');
        if ((isset($config['id']) and $config['id']) and (isset($config['file']) and $config['file'])) {
            echo "<script type='text/javascript'>";
            echo "$(function(){";
            echo "jwplayer('".$config['id']."').setup({";
            echo "'flashplayer': 'http://www.trueplookpanya.com/assets/jwplayer/jw_player.swf',";
            
            if (isset($config['width']) and $config['width']) {
                echo "'width': '".$config['width']."',";
            } else {
                echo "'width': '500',";
                $config['width'] = 500;
            }

            if (isset($config['height']) and $config['height']) {
                echo "'height': '".$config['height']."',";
            } else {
                echo "'height': '318',";
                $config['height'] = 318;
            }
            
            echo "'file': '".$config['file']."',";
            
            if (isset($config['image']) and $config['image']) {
                echo "'image': '".$config['image']."',";
            }
            
            if (isset($config['autostart']) and $config['autostart']) {
                echo "'autostart': ".$config['autostart'].",";
            }
            
            echo "'skin': 'http://www.trueplookpanya.com/assets/jwplayer/skin/newtubedark.zip',";
            echo "'controlbar': 'bottom',";

            if (isset($config['plugins']) and $config['plugins']) {
                echo "'plugins': {";
            }
                if (isset($config['plugins']['hd-2']) and $config['plugins']['hd-2']) {
                    echo "'hd-2': { file: '".$config['plugins']['hd-2']."'},";
                }
            if (isset($config['plugins']) and $config['plugins']) {
                echo "}";
            }

            echo "'logo.file': 'http://www.trueplookpanya.com/assets/jwplayer/logo_player.png',";
            echo "'logo.link': 'http://www.trueplookpanya.com',";
            echo "'logo.linktarget': '_blank',";
            echo "'logo.position': 'top-left',";
            echo "'logo.margin':'15',";
            echo "'logo.out':'0.6',";
            echo "'logo.hide': false,";
            echo "});";
            echo "});";
            echo "</script>";
            
            echo '<video id="'.$config['id'].'" width="'.$config['width'].'" height="'.$config['height'].'" controls >';
            echo '<source src="'.$config['file'].'" type="video/mp4">';
            echo '</video>';
        }
    } else {
        if (empty($config['width'])) {
            $config['width'] = 500;
        }

        if (empty($config['height'])) {
            $config['height'] = 318;
        }
        echo '<video id="'.$config['id'].'" width="'.$config['width'].'" height="'.$config['height'].'" controls >';
        echo '<source src="'.$config['file'].'" type="video/mp4">';
        echo '</video>';
    }
}

function audiojs($file = '') {
    $CI = & get_instance();
    $CI->template->add_javascript('assets/audiojs/audio.min.js');
    $CI->template->add_script('$(function() {
        audiojs.events.ready(function() {
            var as = audiojs.createAll();
        });
    });');
    echo '<audio src="'.$file.'" preload="auto" />';
}
