<?php defined('BASEPATH') or exit('Nodirectscriptaccessallowed.');

$config['useragent'] = 'PHPMailer';//Mailengineswitcher:'CodeIgniter'or'PHPMailer'
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp2.truelife.com';
$config['smtp_port'] = 25;
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['smtp_timeout'] = 7;
$config['wordwrap'] = false;
$config['validate'] = true;
$config['newline'] = "\r\n";
$config['smtp_crypto'] = '';
$config['smtp_debug'] = 0;
$config['wrapchars'] = 76;
$config['priority'] = 3;
$config['crlf'] = "\n";
$config['newline'] = "\n";
$config['bcc_batch_mode'] = false;
$config['bcc_batch_size'] = 200;
$config['encoding'] = '8bit';