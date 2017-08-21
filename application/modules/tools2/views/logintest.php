<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p>logintest</p>
<?php
$url=site_url("/api/user/logincheck"); 
// Model  key
$this->load->model('api/crypt_model');
$key= $this->crypt_model->key();
$apikey=$this->crypt_model->apikey();
$hash=$apikey;
$data_encrypt = $this->crypt_model->base64_encrypt($hash,$key);
?>
<form id="form1" name="form1" method="post" action="<?php echo $url;?>">
  User :
  <label>
  <input name="user_username" type="text" id="user_username" />
  </label> 
   Password :
   <label>
   <input name="user_password" type="password" id="user_password" />
   </label>
   <label>
   <input name="hashkey" type="hidden" id="hashkey" value="<?php echo $data_encrypt;?>" />
   <input type="submit" name="Submit" value="Login" />
   </label>
</form>
<p>&nbsp; </p>
</body>
</html>
