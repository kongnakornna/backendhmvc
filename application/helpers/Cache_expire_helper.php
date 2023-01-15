<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$fpath=FCPATH.'file/cache/static/';
//define("CACHE_PATH_STATIC", $fpath);
//define("CACHE_DIR", $fpath);
//define("CACHE_DIR_APPPATH", APPPATH.'cache/');
#echo '<pre>CACHE_DIR=>'; print_r(CACHE_DIR); echo '</pre>';
#echo '<pre>CACHE_DIR=>'; print_r(CACHE_DIR); echo '</pre>';
#echo '<pre>CACHE_DIR_APPPATH=>'; print_r(CACHE_DIR_APPPATH); echo '</pre>';
#echo '<pre>CACHE_PATH_STATIC=>'; print_r(CACHE_PATH_STATIC); echo '</pre>'; Die();
if (!function_exists('is_cache_valid')) {
    function is_cache_valid($cache_name,$lifespan) {

        if (file_exists(CACHE_DIR.$cache_name)) {
            $last_date = file_get_contents(CACHE_DIR.$cache_name);
            if (abs($last_date - time()) < $lifespan) {
                return true;
            } else {
                file_put_contents(CACHE_DIR.$cache_name,time());
                return false;
            }
        } else {
            file_put_contents(CACHE_DIR.$cache_name,time());
            return true;
        }

    }
}

/**
$date = str_replace( ':', '', $date);
if (!is_dir('uploads/'.$date)) {
    mkdir('./uploads/' . $date, 0777, TRUE);
}
* 
*/