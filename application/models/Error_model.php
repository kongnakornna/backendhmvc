<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed'); 
header('Content-type: text/html; charset=utf-8');
class Error_model extends CI_Model {
public function show_404($page='',$log_error=TRUE){
        if (is_cli()){
            $heading = 'Not Found';
            $message = 'The controller/method pair you requested was not found.';
        }else{
            $heading = '404 Page Not Found';
            $message = 'The page you requested was not found.';
        }

        
        if ($log_error){
            log_message('error', $heading.': '.$page);
        }
        
        if( is_cli()) {

            echo $this->show_error($heading, $message, 'error_404', 404);

        }else{
            header("HTTP/1.0 404 Not Found");
            
            if($page==null){
               $error404=base_url('error404'); 
            }else{
                 $error404=$page;
            }
            header('location:'. $error404.'');
        }
        exit(4); // EXIT_UNKNOWN_FILE
    }  
}