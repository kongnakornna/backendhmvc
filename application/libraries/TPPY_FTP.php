<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class TPPY_FTP {
  
    function __construct()
    {
        $this->CI = &get_instance();
        $this->ftp = $this->CI->load->library('ftp');
    }
    
    
    public function doUpload($filepath=null, $filedata=null, $ftp_upload='swiftserve'){
      $ftp_config = $this->CI->config->item('ftp_upload');
      $config= $ftp_config[$ftp_upload];
      $this->ftp->connect($ftp_config[$ftp_upload]);
      $list = $this->ftp->list_files('/');
      
      
      $this->ftp->upload($filepath, $filedata, 'UTF-8', 0775);
      
       print_r($list);

      // $this->ftp->close();
    }
}