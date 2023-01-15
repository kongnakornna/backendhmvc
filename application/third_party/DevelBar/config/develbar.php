<?php defined('BASEPATH') or die('No direct script access.');
/*
|--------------------------------------------------------------------------
| Developer's Toolbar
|--------------------------------------------------------------------------
|
| This option allows you to enable the developer's Toolbar
|
*/
$this->ci = &get_instance();
$this->ci->load->library('session');
 if(!$this->ci->session->userdata('is_logged_in')){
   $config['enable_develbar'] = FALSE;
 }else{
    $userdata=$this->ci->session->userdata();
    #echo'<hr><pre>  $userdata=>';print_r($userdata);echo'<pre> <hr>'; #die(); 
    $admin_id = $this->ci->session->userdata('admin_id');
	$admin_type = $this->ci->session->userdata('admin_type');
   #echo'<hr><pre>  $admin_type=>';print_r($admin_type);echo'<pre> <hr>'; die(); 
    // FALSE; //TRUE; 
   if($admin_type==1){
     $config['enable_develbar'] = TRUE; 
   }else{
     $config['enable_develbar'] = FALSE;
   }
 }
/*
|--------------------------------------------------------------------------
| Check for update
|--------------------------------------------------------------------------
|
| This option allows you to check if there is any new version for CodeIgniter
| if this option is set to TRUE, it will slow down the page loading
|
*/
$config['check_update'] = TRUE;

$config['profiler_key_expiration_time'] = 1800; // sec

$config['documentation_link'] = 'http://www.codeigniter.com/userguide3/';

$config['ci_website'] = 'http://www.codeigniter.com';

$config['ci_download_link'] = 'http://www.codeigniter.com/download';

$config['ci_update_uri'] = 'https://raw.githubusercontent.com/bcit-ci/CodeIgniter/develop/system/core/CodeIgniter.php';

$config['develbar_update_uri'] = 'https://raw.githubusercontent.com/JCSama/CodeIgniter-develbar/master/version.json';

$config['develbar_download_link'] = 'https://github.com/JCSama/CodeIgniter-develbar';

/*
|--------------------------------------------------------------------------
| Debug Sections
|--------------------------------------------------------------------------
|
| This option allows you to enable specific sections into the Developer's Toolbar
|
*/
$config['develbar_sections'] = array(
	'Benchmarks' 		=> TRUE,
    'Memory Usage'	   	=> TRUE,
    'Request'   		=> TRUE,
    'Database'			=> TRUE,
    'Hooks'				=> TRUE,
    'Ajax' 			    => TRUE,
    'Libraries'			=> TRUE,
    'Helpers' 			=> TRUE,
    'Views' 			=> TRUE,
    'Config' 			=> TRUE,
    'Session' 			=> TRUE,
    'Models' 			=> TRUE,
);