<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
echo'<hr>';
   #echo '<pre>key=>'; print_r($key); echo '</pre>';  
   echo 'Welcome Cache Tool <br>';
   #echo 'by page=> '.base_url().'cachetool/delete_cache_uri?uri=cachetool/test <br>';
  echo '<hr>'; ?><a href="<?php echo base_url('cachetool/clear_all_cache'); ?> ">
  <?php echo '<pre>';  print_r('Delete All '); echo '</pre>';  ?> </a> </b>
<?php
      # echo '<br>';
       $dir=$this->config->item('cache_path');
        //echo $dir;
        if(is_dir($dir)){
            if($dh = opendir($dir)){
             $i=1;
                while(($file = readdir($dh)) !== FALSE){    
                if($file =='..'||$file =='.'){}else{
                 //echo '<pre> '; echo $i.') file=>';   print_r($file); echo '</pre>'; 
                //echo '<br>'; ?>
                <a href="<?php echo base_url().'cachetool/delete_cache_file?uri=cachetool/testdriver&file='.$file; ?> "><?php //echo $i.') '.$file; 
                
                echo '<pre> Key=>';  print_r($file); echo '</pre>';  
                ?> </a><?php 
                }        
                $i++;
                }
                closedir($dh);
            }
        }
?>     