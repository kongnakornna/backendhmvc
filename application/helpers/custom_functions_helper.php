<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


if (!function_exists('_isdev')) {

    function _isdev() {
        if (ENVIRONMENT == "development")
            return true;
        else
            return false;
    }

}

/*
 * Print Recursive
 *
 * Simply wraps a print_r() in pre tags for debugging.
 *
 * @param mixed
 * @return string
 */
if (!function_exists('_pr')) {

    function _pr($a) {
        echo "<pre>";
        print_r($a);
        echo "</pre>";
    }

}

// ------------------------------------------------------------------------

/*
 * Variable Dump
 *
 * Simply wraps a var_dump() in pre tags for debugging.
 *
 * @param mixed
 * @return string
 */
if (!function_exists('_vd')) {

    function _vd($a) {
        echo "<pre>";
        var_dump($a);
        echo "</pre>";
    }

}

// ------------------------------------------------------------------------

/*
			    $server='203.144.185.224'; 
				$ftpUser='trueplookpanya_data';
				$ftpPassword='OaHsBKgzEp'; 
 * Array to Object
 *
 * Converts an array to an object
 *
 * @param array
 * @return object
 */
if (!function_exists('array_to_object')) {

    function array_to_object($array) {
        $Object = new stdClass();
        foreach ($array as $key => $value) {
            $Object->$key = $value;
        }

        return $Object;
    }

}

if (!function_exists('array_to_object_recursive')) {

    function array_to_object_recursive($array) {
        $Object = new stdClass();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $Object->$key = array_to_object_recursive($value);
            }
            return !empty($Object) ? $Object : null; //$Object;
        } else {
            return !empty($array) ? $array : null;
        }
    }

}

// ------------------------------------------------------------------------

/*
 * Object to Array
 *
 * Converts an object to an array
 *
 * @param object
 * @return array
 */
if (!function_exists('object_to_array')) {

    function object_to_array($Object) {
        $array = get_object_vars($Object);
        return $array;
    }

}

if (!function_exists('object_to_array_recursive')) {

    function object_to_array_recursive($Object) {
        $array = json_decode(json_encode($Object), true);
        return $array;
    }

}

// ------------------------------------------------------------------------

/*
 * Is Ajax
 *
 * Returns true if request is ajax protocol
 *
 * @return bool
 */
if (!function_exists('is_ajax')) {

    function is_ajax() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }

}

// ------------------------------------------------------------------------

/*
 * Image Thumb
 *
 * Creates an image thumbnail and caches the image
 *
 * @param string
 * @param int
 * @param int
 * @param bool
 * @param array
 * @return string
 */
if (!function_exists('image_thumb')) {

    function image_thumb($source_image, $width = 30, $height = 30, $crop = FALSE, $props = array()) {

        $CI = & get_instance();
        $CI->load->library('image_cache');

        $source_image = str_replace($CI->config->item('root_base_url'), $CI->config->item('root_base_path'), $source_image);
        $source_image = str_replace($CI->config->item('root_old_base_url'), $CI->config->item('root_old_base_path'), $source_image);

        $props['source_image'] = $source_image;
        $props['width'] = $width;
        $props['height'] = $height;
        $props['crop'] = $crop;
        $props['no_image_image'] = $CI->config->item('no_image_image');

        $CI->image_cache->initialize($props);
        $image = $CI->image_cache->image_cache();
        $CI->image_cache->clear();
        return $image;
    }

}
// ------------------------------------------------------------------------

/*
 * BR 2 NL
 *
 * Converts html <br /> to new line \n
 *
 * @param string
 * @return string
 */
if (!function_exists('br2nl')) {

    function br2nl($text) {
        return preg_replace('/<br\\s*?\/??>/i', '', $text);
    }

}




// ------------------------------------------------------------------------

/*
 * Option Array Value
 *
 * Returns single dimension array from an Array of objects with the key and value defined
 *
 * @param array
 * @param string
 * @param string
 * @param array
 * @return array
 */
if (!function_exists('option_array_value')) {

    function option_array_value($object_array, $key, $value, $default = array()) {
        $option_array = array();

        if (!empty($default)) {
            $option_array = $default;
        }

        foreach ($object_array as $Object) {
            $option_array[$Object->$key] = $Object->$value;
        }

        return $option_array;
    }

}

// ------------------------------------------------------------------------

/*
 * In URI
 *
 * Checks if current uri segments exist in array of uri strings
 *
 * @param string or array
 * @param string
 * @param bool
 * @return bool
 */
if (!function_exists('in_uri')) {

    function in_uri($uri_array, $uri = null, $array_keys = FALSE) {
        if (!is_array($uri_array)) {
            $uri_array = array($segments);
        }

        $CI = & get_instance();

        if (!empty($uri)) {
            $uri_string = '/' . trim($uri, '/');
        } else {
            $uri_string = '/' . trim($CI->uri->uri_string(), '/');
        }

        $uri_array = ($array_keys) ? array_keys($uri_array) : $uri_array;

        foreach ($uri_array as $string) {
            if (strpos($uri_string, ($string != '') ? '/' . trim($string, '/') : ' ') === 0) {
                return true;
            }
        }

        return false;
    }

}

// ------------------------------------------------------------------------

/*
 * Theme Partial
 *
 * Load a theme partial
 *
 * @param string
 * @param array
 * @param bool
 * @return string
 */
if (!function_exists('theme_partial')) {

    function theme_partial($view, $vars = array(), $return = TRUE) {
        $CI = & get_instance();
        $CI->load->library('parser');
        return $CI->parser->parse_string($CI->load->theme($CI->template->theme . '/views/partials/' . trim($view, '/'), $vars, $return, $CI->template->theme_path), $vars, $return);
    }

}

// ------------------------------------------------------------------------

/*
 * Theme Url
 *
 * Create a url to the current theme
 *
 * @param string
 * @return string
 */
if (!function_exists('theme_url')) {

    function theme_url($uri = '') {
        $CI = & get_instance();
        // return base_url($CI->template->theme_path . '/' . $CI->template->theme . '/'  . trim($uri, '/'));
        return base_url() . 'canvas/themes/tppy';
    }

}

if (!function_exists('theme_assets')) {

    function theme_assets($uri = '') {
        $CI = & get_instance();
        return base_url('canvas/' . $CI->template->theme_path . '/' . $CI->template->theme . '/assets/' . trim($uri, '/'));
        // return base_url() . 'canvas/themes/tppy';
    }

}


// ------------------------------------------------------------------------

/*
 * Domain Name
 *
 * Returns the site domain name and tld
 *
 * @return string
 */
if (!function_exists('domain_name')) {

    function domain_name() {
        $CI = & get_instance();

        $info = parse_url($CI->config->item('base_url'));
        $host = $info['host'];
        $host_names = explode(".", $host);
        return $host_names[count($host_names) - 2] . "." . $host_names[count($host_names) - 1];
    }

}

// ------------------------------------------------------------------------

/*
 * Glob Recursive
 *
 * Run glob function recursivley on a directory
 *
 * @param string
 * @return array
 */
if (!function_exists('glob_recursive')) {

    // Does not support flag GLOB_BRACE

    function glob_recursive($pattern, $flags = 0) {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
        }

        return $files;
    }

}

// ------------------------------------------------------------------------

/*
 * URL Base64 Encode
 *
 * Encodes a string as base64, and sanitizes it for use in a CI URI.
 *
 * @param string
 * @return string
 */
if (!function_exists('url_base64_encode')) {

    function url_base64_encode(&$str = "") {
        return strtr(
                base64_encode($str), array(
            '+' => '.',
            '=' => '-',
            '/' => '~'
                )
        );
    }

}

// ------------------------------------------------------------------------

/*
 * URL Base64 Decode
 *
 * Decodes a base64 string that was encoded by ci_base64_encode.
 *
 * @param string
 * @return string
 */
if (!function_exists('url_base64_decode')) {

    function url_base64_decode(&$str = "") {
        return base64_decode(strtr(
                        $str, array(
            '.' => '+',
            '-' => '=',
            '~' => '/'
                        )
        ));
    }

}

// ------------------------------------------------------------------------

/*
 * Output XML
 *
 * Sets the header content type to XML and
 * outputs the <?php xml tag
 *
 * @param string
 * @return string
 */
if (!function_exists('xml_output')) {

    function xml_output() {
        $CI = & get_instance();
        $CI->output->set_content_type('text/xml');
        $CI->output->set_output("<?xml version=\"1.0\"?>\r\n");
    }

}

// ------------------------------------------------------------------------

/*
 * JS Head Start
 *
 * Starts output buffering to place javascript in the <head> of the template
 *
 * @return void
 */
if (!function_exists('js_start')) {

    function js_start() {
        ob_start();
    }

}

// ------------------------------------------------------------------------

/*
 * JS Head End
 *
 * Ends output buffering to place javascript in the <head> of the template
 *
 * @return void
 */
if (!function_exists('js_end')) {

    function js_end() {
        $CI = & get_instance();
        $CI->template->add_script(ob_get_contents());
        ob_end_clean();
    }

}

// ------------------------------------------------------------------------

/*
 * String to Boolean
 *
 * This function analyzes a string and returns false if the string is empty, false, or 0
 * and true for everything else
 *
 * @param string
 * @return bool
 */
if (!function_exists('str_to_bool')) {

    function str_to_bool($str) {
        if (is_bool($str)) {
            return $str;
        }

        $str = (string) $str;

        if (in_array(strtolower($str), array('false', '0', ''))) {
            return false;
        } else {
            return true;
        }
    }

}

// ------------------------------------------------------------------------

/*
 * Is Inline Editable
 *
 * Returns true if inline editing is enabled, admin toolbar is enabled, and user is an administrator
 *
 * @return bool
 */
if (!function_exists('is_inline_editable')) {

    function is_inline_editable($content_type_id = null) {
        $CI = & get_instance();
        $CI->load->model('content_types_model');

        if ($CI->settings->enable_inline_editing && $CI->settings->enable_admin_toolbar && $CI->secure->group_types(array(ADMINISTRATOR))->is_auth()) {
            if (empty($content_type_id)) {
                return TRUE;
            }

            if ($CI->Group_session->type != SUPER_ADMIN) {
                // Check if we have already cached permissions for this content type
                if (!isset($CI->content_types_model->has_permission_cache[$content_type_id])) {
                    $Content_types_model = new Content_types_model();

                    // No permission for this content type has been cached yet.
                    // Query to see if current user has permission to this content type
                    $Content_type = $Content_types_model->group_start()
                            ->where('restrict_admin_access', 0)
                            ->or_where_related('admin_groups', 'group_id', $CI->Group_session->id)
                            ->group_end()
                            ->get_by_id($content_type_id);

                    $CI->content_types_model->has_permission_cache[$content_type_id] = ($Content_type->exists()) ? TRUE : FALSE;
                }

                return $CI->content_types_model->has_permission_cache[$content_type_id];
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

}

if (!function_exists('get_file_url')) {

    function get_file_url($file) {
        $CI = & get_instance();
        if (isset($file) and $file and file_exists($CI->config->item('root_path') . $file)) {
            return $CI->config->item('root_url') . $file;
        } else {
            return 'http://www.trueplookpanya.com/new/assets/images/icon/knowledge/menu_knowledge_0100.png';
        }
    }

}

if (!function_exists('time_format_thai')) {

    function time_format_thai($time) {
        $str = '';
        $arr = explode(':', $time);
        if (count($arr) == 2) {
            list($m, $s) = $arr;
            if ((int) $m != 0) {
                $str .= (int) $m . ' นาที ';
            }
            if ((int) $s != 0) {
                $str .= (int) $s . ' วินาที ';
            }
        } else if (count($arr) == 3) {
            list($h, $m, $s) = $arr;
            if ((int) $h != 0) {
                $str .= (int) $h . ' ชั่วโมง ';
            }
            if ((int) $m != 0) {
                $str .= (int) $m . ' นาที ';
            }
            if ((int) $s != 0) {
                $str .= (int) $s . ' วินาที ';
            }
        }
        return $str;
    }

}

if (!function_exists('date_format_thai')) {

    function date_format_thai($date_english, $return_format) {

        $array_month_short = array('01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.');
        $array_month_long = array('01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม');

        $array_day = array('0' => 'อาทิตย์', '1' => 'จันทร์', '2' => 'อังคาร', '3' => 'พุธ', '4' => 'พฤหัสบดี', '5' => 'ศุกร์', '6' => 'เสาร์');

        $content_datetime = new DateTime($date_english);

        switch ($return_format) {

            case 's' :

                $date_thai = date_format($content_datetime, 'j') . ' ' . $array_month_short[date_format($content_datetime, 'm')] . ' ' . (date_format($content_datetime, 'Y') + 543);

                break;

            case 'l' :

                $date_thai = date_format($content_datetime, 'j') . ' ' . $array_month_long[date_format($content_datetime, 'm')] . ' ' . (date_format($content_datetime, 'Y') + 543);

                break;

            case 't' :

                $date_thai = date_format($content_datetime, 'G:i');

                break;

            case 'd' :

                $date_thai = $array_day[date_format($content_datetime, 'w')];

                break;

            default :

                $date_thai = date_format($content_datetime, 'Y m j');

                break;
        }

        return $date_thai;
    }

}

if (!function_exists('date_format_ddmmyyyy')) {

    function date_format_ddmmyyyy($date) { //input 0000-00-00
        list($yyyy, $mm, $dd) = explode('-', $date);
        return $dd . '-' . $mm . '-' . $yyyy;
    }

}

if (!function_exists('date_format_yyyymmdd')) {

    function date_format_yyyymmdd($date) { //input 00-00-0000
        list($dd, $mm, $yyyy) = explode('-', $date);
        return $yyyy . '-' . $mm . '-' . $dd;
    }

}

if (!function_exists('get_level_label')) {

    function get_level_label($level_id, $key = 'sname') {
        $level_id = trim($level_id);
        $levelArr = unserialize(STEDENT_LEVEL);
        if ($level_id[1] == 0) {
            if (isset($levelArr[$level_id][$key]))
                return $levelArr[$level_id][$key];
        }elseif ($level_id[0] > 0)
            return $levelArr[$level_id[0] * 10]['level'][$level_id][$key];
        elseif ($level_id[0] == 0)
            return $levelArr['00']['level'][$level_id][$key];
    }

}

function thai_date($datetime, $format, $clock) {
    if (!$datetime)
        return false;
    list($date, $time) = explode(' ', $datetime);
    if (!$date and $datetime)
        $date = $datetime;
    list($Y, $m, $d) = explode('-', $date);
    $Y = $Y + 543;

    $month = array(
        '0' => array('01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฏาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤษจิกายน', '12' => 'ธันวาคม'),
        '1' => array('01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.')
    );
    if ($clock == false)
        return $d . ' ' . $month[$format][$m] . ' ' . $Y;
    else
        return $d . ' ' . $month[$format][$m] . ' ' . $Y . ' เวลา ' . $time;
}

function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}

function ip_client() {
    $ipaddress = '';
    if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    $ipaddress = strpos($ipaddress, ',') > 0 ? trim(reset(explode(',', $ipaddress))) : trim($ipaddress);

    return $ipaddress;
}

if (!function_exists('cutStr')) {

    function cutStr($str, $long, $holder = "...") {
        $str = trim(strip_tags($str));
        if (mb_strlen(($str)) > $long) {
            return (mb_substr($str, 0, $long, "UTF-8")) . $holder;
        } else {
            return $str;
        }
    }

}

function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function is_internal($ipaddress = null) {
    $is_internal = false;
    if (empty($ipaddress))
        $ipaddress = ip_client();

    if ($ipaddress == '127.0.0.1')
        return true; // FOR LOCAL SITE
    if (startsWith($ipaddress, '192.168.'))
        return true; // FOR DEV SITE
    if ($ipaddress == '110.170.16.46')
        return true; // TRUE PLOOKPANYA
    if ($ipaddress == '203.144.130.176')
        return true; // TRUE VISION
    if ($ipaddress == '110.170.192.111')
        return true; // VPN
    if ($ipaddress == '119.46.20.238')
        return true;

    return false;
}

function strpos_array($haystack, $needle) {
    if (!is_array($needle))
        $needle = array($needle);
    foreach ($needle as $what) {
        if (($pos = strpos($haystack, $what)) !== false)
            return $pos;
    }
    return false;
}

function sip() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    echo $ip;
}

if (!function_exists('social_media_share')) {

    function social_media_share() {
        include "$_SERVER[DOCUMENT_ROOT]/canvas/assets/social/share-campaign.php";
    }

}
if (!function_exists('social_media_share_left')) {

    function social_media_share_left() {
        include "$_SERVER[DOCUMENT_ROOT]/canvas/assets/social/share-campaign-left.php";
    }

}
if (!function_exists('social_media_share_left')) {

    function social_media_share_left() {
        include "$_SERVER[DOCUMENT_ROOT]/canvas/assets/social/share-campaign-left.php";
    }

}
if (!function_exists('social_media_share_default')) {

    function social_media_share_default() {
        include "$_SERVER[DOCUMENT_ROOT]/canvas/assets/social/share-campaign-default.php";
    }

}
if (!function_exists('social_media_share_hover')) {

    function social_media_share_hover() {
        include "$_SERVER[DOCUMENT_ROOT]/canvas/assets/social/share-hover.php";
    }

}
if (!function_exists('tppy_json_decode')) {

    function tppy_json_decode($json, $assoc = false, $dept = 512, $options = 0) {
        return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json), $assoc, $dept, $options);
    }

}

if (!function_exists('json_decoder')) {

    function json_decoder($text) {
        $bom = pack('H*', 'EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return json_decode($text, TRUE);
    }

}

if (!function_exists('DateToThai')) {

    function DateToThai($strDate) {
        date_default_timezone_set("Asia/Bangkok");

        $array_month_short = array('01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.');
        $array_month_long = array('01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม');
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
//                $strHour= date("H",strtotime($strDate));
//		$strMinute= date("i",strtotime($strDate));
//		$strSeconds= date("s",strtotime($strDate));
        $strMonthCut = Array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];
        //return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
        return "$strDay $strMonthThai $strYear";
    }

}
if (!function_exists('convertToK')) {

    function convertToK($number, $precision = 0, $divisors = null) {

        // Setup default $divisors if not provided
        if (!isset($divisors)) {
            $divisors = array(
                pow(1000, 0) => '', // 1000^0 == 1
                pow(1000, 1) => 'K', // Thousand
                pow(1000, 2) => 'M', // Million
                pow(1000, 3) => 'B', // Billion
                pow(1000, 4) => 'T', // Trillion
                pow(1000, 5) => 'Qa', // Quadrillion
                pow(1000, 6) => 'Qi', // Quintillion
            );
        }

        // Loop through each $divisor and find the
        // lowest amount that matches
        foreach ($divisors as $divisor => $shorthand) {
            if ($number < ($divisor * 1000)) {
                // We found a match!
                break;
            }
        }

        // We found our match, or there were no matches.
        // Either way, use the last defined value for $divisor.
        return number_format($number / $divisor, $precision) . $shorthand;
    }

}

if (!function_exists('get_img_url')) {

    function get_img_url($file) {
        $file_return = "";

        if (empty($file)) {

            $file_return = "http://static.trueplookpanya.com/trueplookpanya/media/home/images/default_img.png";
        } else {

            $img = getimagesize($file);

            if ($img == '') {
                $file_return = "http://static.trueplookpanya.com/trueplookpanya/media/home/images/default_img.png";
            } else {
                $file_return = $file;
            }
        }
        return $file_return;
    }

}

if (!function_exists('base64encrypt')) {

    function base64encrypt($string, $key = 'AYnaPKoolPEurT') {
        $key_ext = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        // if(empty($key)) {
        // $key = $this->default_key;
        // }
        $key = $key_ext . $key;
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + (ord($keychar) % 7));
            $result.=$char;
        }
        $result = strrev($result);
        $result = strtolower($result) ^ strtoupper($result) ^ $result;
        $result = strtr(base64_encode($result), '+/', '-_');
        return $key_ext . $result;
    }

}

if (!function_exists('base64decrypt')) {

    function base64decrypt($string, $key = 'AYnaPKoolPEurT') {
        $key_ext = substr($string, 0, 6);
        $string = substr($string, 6, strlen($string));
        // if(empty($key)) {
        // $key = $this->default_key;
        // }
        $key = $key_ext . $key;

        $result = '';

        $string = base64_decode(strtr($string, '-_', '+/'));
        $string = strtolower($string) ^ strtoupper($string) ^ $string;
        $string = strrev($string);

        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - (ord($keychar) % 7));
            $result.=$char;
        }
        return $result;
    }

}

if (!function_exists('str_friendly')) {

    function str_friendly($string) {
        $string = preg_replace("`\[.*\]`U", "", $string);
        $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $string);
        $string = str_replace('%', '-percent', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i", "\\1", $string);
        $string = preg_replace(array("`[^a-z0-9ก-๙เ-า]`i", "`[-]+`"), "-", $string);
        return strtolower(trim($string, '-'));
    }

}

function form_upload_image($image_name = '', $image_name_file = '', $image_name_url = null, $width = 200, $height = 120, $extra = '') {
    $form = '';
    // $width=$params['width'];
    // $height=$params['height'];

    $no_image = 'http://dummyimage.com/' . $width . 'x' . $height . '/333/fff.png';
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

    $form .= '<img src="' . ($image_name_file ? $image_name_url : $no_image) . '" id="' . $image_name . '_img" onclick="$(\'#' . $image_name . '_file\').click();" ' . $extra . ' />';
    $form .= '<input type="file" name="' . $image_name . '_file" id="' . $image_name . '_file" value="' . $image_name_file . '" class="form-control" style="display:none;" onchange="' . $image_name . '_readURL(this)" />';
    $form .= '<input type="hidden" name="' . $image_name . '_url" id="' . $image_name . '_url" value="' . $image_name_file . '" />';
    $form .= '<span id="' . $image_name . '_x" onclick="if(confirm(\'ยืนยันการลบ\')){ $(\'#' . $image_name . '_img\').attr(\'src\',\'' . $no_image . '\'); $(\'#' . $image_name . '_url\').val(\'\'); $(\'#' . $image_name . '_file\').val(\'\'); $(this).hide(); }" style="display:none;">[X]</span>';
    $form .="
    <script>
    function " . $image_name . "_readURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              $('#" . $image_name . "_img').attr('src', e.target.result); //.height($height);
          };
          reader.readAsDataURL(input.files[0]);
      } else {
        var img = '$no_image';
        $('#" . $image_name . "_img').attr('src',img); //.height($height);
      }
      $(\"#" . $image_name . "_x\").show().css(\"margin-right\",\"10px\");
    }";
    if ($image_name_file) {
        $form .="$(\"#" . $image_name . "_x\").show().css(\"margin-right\",\"10px\");";
    }
    $form .="</script>";
    return $form;
}

if (!function_exists('build_nav')) {

    function build_nav($menu, $separator = ' &gt; ') {
        $result = '';
        if ($menu) {
            foreach ($menu as $k => $v) {
                if (empty($v) OR $v == '#') {
                    $result .= $k;
                } else {
                    $result .= "<a href=\"$v\">$k</a>";
                }
                if ($v != end($menu)) {
                    $result .= $separator;
                }
            }
        }
        return $result;
    }

}
if (!function_exists('convertShowDate')) {

    function convertShowDate($d, $d2) {
        $thai_month_arr_short = array(
            0 => "",
            1 => "ม.ค.",
            2 => "ก.พ.",
            3 => "มี.ค.",
            4 => "เม.ย.",
            5 => "พ.ค.",
            6 => "มิ.ย.",
            7 => "ก.ค.",
            8 => "ส.ค.",
            9 => "ก.ย.",
            10 => "ต.ค.",
            11 => "พ.ย.",
            12 => "ธ.ค."
        );

        $date = Date('d', strtotime($d));
        $month = Date('n', strtotime($d));
        $year = Date('Y', strtotime($d));
        if ($year < 2500) {
            $year = $year + 543;
        }
        $year = substr($year, -2);
        $r1 = $date . ' ' . $thai_month_arr_short[$month] . ' ' . $year;

        if (!empty($d2)) {
            $date2 = Date('d', strtotime($d2));
            $month2 = Date('n', strtotime($d2));
            $year2 = Date('Y', strtotime($d2));
            if ($year2 < 2500) {
                $year2 = $year2 + 543;
            }
            $year2 = substr($year2, -2);
            $r2 = $date2 . ' ' . $thai_month_arr_short[$month2] . ' ' . $year2;

            if ($year == $year2 && $month == $month2 && $date == $date2) {
                $r = $r1;
            } elseif ($year == $year2 && $month == $month2) {
                $r = $date . ' - ' . $date2 . ' ' . $thai_month_arr_short[$month] . ' ' . $year;
            } elseif ($year == $year2) {
                $r = $date . ' ' . $thai_month_arr_short[$month] . ' - ' . $date2 . ' ' . $thai_month_arr_short[$month2] . ' ' . $year;
            } else {
                $r = $r1 . ' - ' . $r2;
            }
        } else {
            $r = $r1;
        }
        return $r;
    }

}

if (!function_exists('checkFacebookImage')) {

    function checkFacebookImage($url_img, $url_img_default = 'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/trueplookpanya_thumbshare.jpg') {
        $data = NULL;
        //echo $width."X".$height.'<br>';
        if (@getimagesize($url_img) !== false && @getimagesize($url_img_default) !== false) {// check file exits
            list($width, $height) = @getimagesize($url_img);
            list($width_df, $height_df) = @getimagesize($url_img_default);
            if ($width >= 200 && $height >= 200) {
                //check IMG OG default
                $data = $url_img;
            } else {
                //check OG default
                if ($width_df >= 200 && $height_df >= 200) {
                    $data = $url_img_default;
                } else {
                    $data = 'http://www.trueplookpanya.com/canvas/themes/tppy/assets/images/trueplookpanya_thumbshare.jpg';
                }
            }
        } else {
            $msg = 'NotFile:';
            $msg .= @getimagesize($url_img) == false ? ' $url_img ' : '';
            $msg .= @getimagesize($url_img_default) == false ? ' $url_img_default ' : '';
            $data = $msg;
        }
        return $data;
    }

}

if (!function_exists('do_upload')) {

    function do_upload($element_name, $config) {
        $CI = & get_instance();
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        if (!isset($CI->upload)) {
            $CI->load->library('upload');
        }
        $CI->upload->initialize($config);
        if ($CI->upload->do_upload($element_name)) {
            $result = $CI->upload->data();
            // $result['absolute_path'] = '/' . $config['upload_path'] . '/' . $result['file_name'];
            // _vd($result);
            // die;
            return $result;
        } else {
            $error = array('error' => $CI->upload->display_errors());
            return $error;
        }
    }
}