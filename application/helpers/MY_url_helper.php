<?php

  function site_url($uri = '') {
    $CI = & get_instance();
    
    $uri = (substr('/', 0, strlen($uri)) === $uri) ? $uri : "/$uri";
    
    return $CI->config->site_url($uri);
  }

  function base_url($uri = '') {
    $CI = & get_instance();
    $uri = (substr('/', 0, strlen($uri)) === $uri) ? $uri : "/$uri";
    return $CI->config->base_url($uri);
  }
  
  function static_url($uri = ''){
    $CI = & get_instance();
    $uri=ltrim($uri, '/');
    $root = $CI->config->item('root_base_url');
    // _vd(startsWith($uri, 'data/product/')); die;
    if(startsWith($uri, 'data/product/')) {
      $root = base_url();
    }
    return $root.$uri;
  }
  


?>