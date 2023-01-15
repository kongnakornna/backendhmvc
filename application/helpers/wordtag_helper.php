<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('trendingtag')) {

    function trendingtag($key = '') {
        $CI = & get_instance();
        $CI->load->config('wordtag');
        $trending_now = $CI->config->item($key);
        if(isset($trending_now) and $trending_now) { 
            $sum_trending_now = count($trending_now);
            $html = '<div>';
            foreach ($trending_now as $key => $value) {
                $html .= (($key + 1) == $sum_trending_now) ? '<a target="_blank" href="'.$value['link'].'" title="'.$value['word'].'" alt="'.$value['word'].'">'.$value['word'].'</a>&nbsp;' : '<a target="_blank" href="'.$value['link'].'" title="'.$value['word'].'" alt="'.$value['word'].'">'.$value['word'].'</a>,&nbsp;';
            }
            $html .= '</div>';
            
            return $html;
        } else {
            return false;
        }
    }

}

if (!function_exists('full_trendingnow')) {

    function full_trendingnow() {
        $CI = & get_instance();
        $CI->load->config('wordtag');
        $trending_now = $CI->config->item('hashtag');
        if(isset($trending_now) and $trending_now) { 
            $sum_trending_now = count($trending_now);
            $html = '';
            foreach ($trending_now as $key => $value) {
                $html .= (($key + 1) == $sum_trending_now) ? '<a target="_blank" href="'.$value['link'].'" title="'.$value['word'].'" alt="'.$value['word'].'">'.$value['word'].'</a>&nbsp;' : '<a target="_blank" href="'.$value['link'].'" title="'.$value['word'].'" alt="'.$value['word'].'">'.$value['word'].'</a>,&nbsp;';
            }
        }
        
        return $html;
    }

}