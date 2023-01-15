<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Testec extends CI_Controller{ 
function __construct(){
        // Construct the parent class
        parent::__construct();
        date_default_timezone_set('Asia/Bangkok');
        $this->load->model('Useraccess_model','User_model');
        $this->load->model('Useraccess_model','model_user');
        $this->db->cache_off();
        $this->load->helper('cookie', 'session');
        // Load form helper library
        $this->load->helper('form');
        // Load form validation library
        $this->load->library('form_validation');
        // Load session library
        $this->load->library('session');
        /*
            add field
            tbl_user_2018.password_encrypt varchar50
            run
            update tbl_user_2018 set  password_encrypt=md5(password)
             update tbl_user_2018 set  password=md5(password)
         */
           // How to Prevent SQL injection in Codeigniter
            # $this->db->escape($email);
            # $user_id = $this->db->escape(trim($user_id));
            //  $this->db->get_where('subscribers_tbl',array ('status'=> active','email' => 'info@arjun.net.in'));
            // SQL Injection Prevention
            // mysql_real_escape_string() 
            // $this->db->escape() 

    }
public function index(){$this->test(); }
public function encryptCookie($value){
    if(!$value){return false;}
    $this->_CI=&get_instance();
    $this->_CI->config->load('encryptkey'); 
    $key=$this->_CI->config->item('key'); // $keyed = 'connexted@true!#';
    $text = $value;
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
    return trim(base64_encode($crypttext)); //encode for cookie
 }  
public function decryptCookie($value){
    if(!$value){return false;}
    $this->_CI=&get_instance();
    $this->_CI->config->load('encryptkey'); 
    $key=$this->_CI->config->item('key'); // $keyed = 'connexted@true!#';
    $crypttext = base64_decode($value); //decode cookie
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
    return trim($decrypttext);
    /*
    
     var queryAPI = function (request_object,callback)
       {
           var app_key = 'sdffkjhdsjfhsdjkfhsdkj';
           var app_secret = 'hfszdhfkjzxjkcxzkjb';
           var app_url = 'http://www.veepiz.com/api/jsonp.php';
           var enc_request = $.toJSON(request_object);
           var ciphertext =encode64(Crypto.AES.encrypt(enc_request, app_secret, { mode: new Crypto.mode.ECB }));
           $.post(app_url,{'app_id':app_key,'enc_request':ciphertext},
           function (data)
           {
               console.log(data);
           },'jsonp');

       }
    */
 }
public function base64_encrypt_get($string, $key) {
      $result = '';
      for ($i = 0; $i < strlen($string); $i++) {
          $char = substr($string, $i, 1);
          $keychar = substr($key, ($i % strlen($key)) - 1, 1);
          $char = chr(ord($char) + ord($keychar));
          $result.=$char;
      }

      return base64_encode($result);
  } 
public function string_encrypt($string, $key) {
      $crypted_text = mcrypt_encrypt(
                          MCRYPT_RIJNDAEL_128, 
                          $key, 
                          $string, 
                          MCRYPT_MODE_ECB
                      );
      return base64_encode($crypted_text);
  }
  
public function string_decrypt($encrypted_string, $key) {
      $decrypted_text = mcrypt_decrypt(
                          MCRYPT_RIJNDAEL_128, 
                          $key, 
                          base64_decode($encrypted_string), 
                          MCRYPT_MODE_ECB
                      );
      return trim($decrypted_text);
  }
public function test() {

            #$this->_CI->config->load('encryptkey'); 
            #$key=$this->_CI->config->item('key');
            $this->config->load('encryptkey'); 
            $key=$this->config->item('key');  

            $this->load->library('Accessuser_library');
            $session_cookie_get=$this->accessuser_library->session_cookie_get();
            $value='57';
            $encryptCookie=$this->accessuser_library->encryptCookie($value);
            $decryptCookie=$this->accessuser_library->decryptCookie($encryptCookie);
echo 'encryptCookie: '.$encryptCookie; echo '<br />';
echo 'decryptCookie: '.$decryptCookie; echo '<br />';
echo 'Provided Text: '.$test_str = $value;
echo '<br />';
echo 'Encyrpted Value:  '.$enc_str = $this->string_encrypt($test_str, $key);   
echo '<br />';
echo 'Decrypted Value:  '.$this->string_decrypt($enc_str, $key);                               
echo '<br />';
 //https://programmer.help/blogs/5c2f928117a41.html
 ?>
<script src="<?php echo base_url();?>assets/jsmcrypt/aes.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jsmcrypt/md5.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jsmcrypt/pad-zeropadding.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jsmcrypt/crypto-helper-zeropadd.js" type="text/javascript"></script> 

<script type="text/javascript">

var data = '111111';
var encode = encrypt(data);
console.log('encode is =======>'+encode);
var decode = decrypt(encode);
console.log('decode is =======>'+decode);
alert('encode is ====>'+encode+',decode is ====>'+decode);
</script>
  <?php
        }
}