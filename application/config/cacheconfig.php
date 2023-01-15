<?php defined('BASEPATH') OR exit('No direct script access allowed');
ob_clean();
############################
$url='/webservice/';
$base_url='https://'.$_SERVER['HTTP_HOST'].$url;
//if domain
//$base_url='https://www.trueplookpanya.com/webservice/';
$config['base_url']=$base_url;
//$config['base_url'] .= preg_replace('@/+$@', '', dirname($_SERVER['SCRIPT_NAME'])).'';
############################
$pathnow=__DIR__;
$FCPATH=FCPATH;
$filepathroot=$FCPATH; 
$config['base_path']=$filepathroot;
$config['root_path']=$filepathroot;
$config['cache_static_path']=$filepathroot.'file/cache/';
$config['cache_path']=$filepathroot.'file/cache/';
$config['upskill']='upskill/';
$config['now_path']=$pathnow;
############################
$time_cach=300;
$time_cach_min=(60*60*5); //5 min
$time_cach=(60*60*60*24*1); //1 day  (60*5); //5 min
$time_cach1=(60*60*60*1); //1 h
$time_cach2=(60*60*60*2); //2 h
$time_cach3=(60*60*60*24*1); //1 day
$time_cach4=(60*60*60*24*3); //3 day
$time_cach5=(60*60*60*24*7); //7 day
$time_cach6=(60*60*60*24*15); //15 day
$time_cach7=(60*60*60*24*30); //30 day
$time_cach8=(60*60*60*24*365*1000); //100 year
$time_cach_1_year=(60*60*60*24*365*1); //365 day  1 year
############################
$config['cachetime']=$time_cach;
$config['time_cach_5_min']=$time_cach_min; //5 min
$config['time_cach_1h']=$time_cach1; //1 h
$config['time_cach_2h']=$time_cach2; //2 h
$config['time_cach_1day']=$time_cach3; //1 day
$config['time_cach_3day']=$time_cach4; //3 day
$config['time_cach_7day']=$time_cach5; //7 day
$config['time_cach_15day']=$time_cach6; //15 day
$config['time_cach_30day']=$time_cach7; //30 day
$config['time_cach_365day']=$time_cach8; //365 day
############################