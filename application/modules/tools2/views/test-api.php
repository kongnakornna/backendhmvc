<?php
$post=@$this->input->post();
$user_username=@$post['user_username'];
$password=@$post['user_password'];

?>
<link href="https://lipis.github.io/bootstrap-social/bootstrap-social.css" rel="stylesheet">
<link href="https://lipis.github.io/bootstrap-social/assets/css/font-awesome.css" rel="stylesheet">

<div class="contrainer" style=" word-wrap:break-word; word-break:break-word; font-size:12px; color: #333; margin-bottom:30px; padding:30px;">
<div class="row">
  <div class="col-sm-8"> 
    <form method="post" class="form-horizontal">
     <div class="form-group">
        <label class="col-sm-3" for="user_username">USERNAME</label>
        <div class="col-sm-8">
          <input name="user_username" type="text" class="form-control" value="<?php echo $user_username;?>" />
        </div>
      </div>
       <div class="form-group">
        <label class="col-sm-3" for="user_password">PASSWORD</label>
        <div class="col-sm-8">
          <input name="user_password" type="password" class="form-control" value="<?php echo $password;?>" />
        </div>
      </div>
      <div class="form-group">
      <label class="col-sm-3" for="user_password"></label>
      <div class="col-sm-8">
      <button class="col-sm-3 btn btn-default "> LOGIN </button>
	  <hr/>
      <div>
      <a class="btn btn-block btn-social btn-facebook"><span class="fa fa-facebook"></span> Sign in with Facebook</a>
      </div>
	   <hr/>
      </div>
     
      </div>
    </form>
  </div>
  
  <?php
  
    $CI = & get_instance();
    $CI->config->load("facebook", TRUE);
    $config = $CI->config->item('facebook');
    $this->load->library('Facebook', $config);
    $userId = $this->facebook->getUser();
    // if ($userId == 0) {
      // $data['url'] = $this->facebook->getLoginUrl(array('scope' => 'email'));
    // }
  ?>
</div>
 YOUR IP ADDRESS :: <?=$this->input->ip_address(); ?>
<?php if($this->input->post()) { ?>
  <div style=" word-wrap:break-word; word-break:break-word; font-size:12px; color: #333;">
    <div class="api_group">
      <h1>ACCOUNT</h1>
      <div class="api">
        <h3>AUTHORIZE [GET]</h3>
        <div><?=site_url("/tools2/member/api/authorize?username=%user_username%&password=MD5(%user_password)%&app_id=%app_id%&scopes=%scopes%&redirect_uri=%redirect_uri%&uuid=%uuid%");?></div>
        <div><a href="<?=$authorize_url?>" target="_blank">VIEW RESPONSE</a></div>
      </div>
    
      <div class="api">
        <h3>TOKEN  [GET] <button class="btn btn-default btn-xs glyphicon glyphicon-refresh" style="top:0px" id="renew"></button></h3>
        <div><?=site_url("/tools2/member/api/accesstoken?code=%code%&app_id=%app_id%&redirect_uri=%redirect_uri%&state=%state%&app_secret=%app_secret%")?></div>
        <div><a href="<?=$token_url?>" target="_blank">VIEW RESPONSE</a></div>
      </div>
      
      <div class="api">
        <h3>PROFILE  [GET]</h3>
        <div><?=site_url("/tools2/member/api/profile?token=%token%")?></div>
        <div><a href="<?=$profile_url?>" target="_blank">VIEW RESPONSE</a></div>
        
        <div><?=site_url("/api/user/profile?token=%token%")?></div>
		<?php 
		$this->load->model('api/crypt_model');
		// key
		$key=$this->crypt_model->key();
		$id= $token;
		#echo '<pre> $key=>'; print_r($key); echo '</pre>'; Die();
		#echo '<pre> $id=>'; print_r($id); echo '</pre>'; Die();
		$hashkey=$this->crypt_model->base64_encrypt($id,$key);
		echo '<pre> $hashkey=>'; print_r($hashkey); echo '</pre>'; #Die();
		$profile_url_api_apps=site_url("/api/user/profile?token=$token&hashkey=$hashkey");
		 ?>
        <div><a href="<?php echo $profile_url_api_apps;?>" target="_blank">VIEW RESPONSE API Apps</a></div>
		
			<?php  
		 $token_data = $token;
		 $token_data = $this->tppy_oauth->parse_token($token);
		// Model
		$this->load->model('api/crypt_model');
		// key
		$key=$this->crypt_model->key();
		$id= $token_data->user_id;
		#echo '<pre> $key=>'; print_r($key); echo '</pre>'; Die();
		#echo '<pre> $id=>'; print_r($id); echo '</pre>'; Die();
		$hashkey=$this->crypt_model->base64_encrypt($id,$key);
		echo '<pre> $hashkey=>'; print_r($hashkey); echo '</pre>'; 
		$profile_url_api_web=site_url("/api/user/profile?id=$id&hashkey=$hashkey");
		 ?>
		
		<div><a href="<?php echo $profile_url_api_web;?>" target="_blank">VIEW RESPONSE API Web</a></div>
		
		<br />
		
<?php 

 $token_data = $token;
 $token_data = $this->tppy_oauth->parse_token($token);
 #echo '<pre> $token=>'; print_r($token); echo '</pre>';
 #echo '<pre> $token_data=>'; print_r($token_data); echo '</pre>';// Die();
$profile_url_api2=site_url("/api/user/profile"); 
// Model
$this->load->model('api/crypt_model');
// key
$key= $this->crypt_model->key();
$id= $token_data->user_id;
#echo '<pre> $id=>'; print_r($id); echo '</pre>'; Die();
$hash = $token; //$this->input->post('hash');
$data_encrypt = $this->crypt_model->base64_encrypt($hash,$key);
$data_encrypt2=$this->crypt_model->base64_encrypt($id,$key);
?>	
Apps <hr />
		<form action="<?php echo $profile_url_api2; ?>" method="post" class="form-horizontal">
		 <div class="form-group">
			<div class="col-sm-8"> 
			   <input name="hash" type="text" class="form-control" id="hash"  value="<?php echo $data_encrypt; ?>" size="10"/>
				<input name="token" type="text" class="form-control" id="token"  value="<?php echo $token; ?>" size="100"/>
				
				<button class="col-sm-3 btn btn-default "> Post Api token</button>
			</div>
		  </div>
		</form>
		
		
<hr />		
<h3>webboardsubjectpost</h3>
<form action="<?php echo 'http://www.trueplookpanya.com/api/center/webboardsubjectpost'; ?>" method="post" class="form-horizontal">
		 <div class="form-group">
			<div class="col-sm-8"> 
			  <input name="token" type="text" class="form-control" id="token"  value="<?php echo $token; ?>" size="100"/>
			 
				<br />
				title <input name="title" type="text" class="form-control" id="title"  value=" " size="100"/>
				<br />
				description <input name="description_long" type="text" class="form-control" id="description_long"  value=" " size="100"/>
				<br />
				<button class="col-sm-3 btn btn-default "> Post</button>
			</div>
		  </div>
		</form>		
		
<hr />	
<?php
$wb_category_id='1';

?>	
<h3>webboardsubjectpost</h3>
<form action="<?php echo 'http://www.trueplookpanya.com/api/center/webboardreplypost'; ?>" method="post" class="form-horizontal">
		 <div class="form-group">
			<div class="col-sm-8"> 
			    
				
				category_id <input name="wb_category_id" type="text" class="form-control" id="wb_category_id"  value="<?php echo $wb_category_id; ?>" size="10"/>
				<br />
				
				<input name="token" type="text" class="form-control" id="token"  value="<?php echo $token; ?>" size="100"/>
				<br />
				comment <input name="comment" type="text" class="form-control" id="comment"  value=" **" size="100"/>
				<br />
				post_id <input name="post_id" type="text" class="form-control" id="post_id"  value="21049" size="10"/>
				<br />
				reply_type<input name="reply_type" type="text" class="form-control" id="reply_type"  value="1" size="2"/> 
				// ( 0 = text , 1 = sticker)
				<br /> 
		
				
<?php
 $sticker_url='http://trueplookpanya.appmanager.biz/image/images/sticker_icon/sticker_icon_image/sticker_icon_id/6/1493140906';				
 $postArray=array("sticker_list_id" =>11,
      				"sticker_icon_id" => 7,
					"sticker_url" =>$sticker_url,
    				); 
	//   you might need to process any other post fields you have..
	//  {"sticker_list_id":"11","sticker_icon_id":"7"}
$json=json_encode($postArray);
				 
				?>
				
				sticker 
				<textarea name="sticker" cols="100" rows="3" class="form-control" id="sticker"><?php echo $json;?></textarea> 
				<br /> 
				<br /> 
				
				<button class="col-sm-3 btn btn-default "> Post</button>
			</div>
		  </div>
		</form>				
		
<hr />Web <hr />
		<form action="<?php echo $profile_url_api2; ?>" method="post" class="form-horizontal">
		 <div class="form-group">
			<div class="col-sm-8"> 
			   <input name="hash" type="text" class="form-control" id="hash"  value="<?php echo $data_encrypt2; ?>" size="10"/>
				<input name="id" type="text" class="form-control" id="id"  value="<?php echo $id; ?>" size="10"/>
				
				<button class="col-sm-3 btn btn-default "> Post Api ID</button>
			</div>
		  </div>
		</form>
<hr />

		
		
		
        <div class="form-group">
          <div class="input-group">
          <input type="text" id="my_token" class="form-control"  value="<?=$token?>"/>
              <span class="input-group-btn">
          <button class="btn btn-default" id="copy_to_clipboard">COPY</button>
          </span>
          </div>
        </div>
		<br />
		<form id="form1" name="form1" method="post" action="fsfs">
		  Test md5 
		  <input name="start_time" type="text" id="start_time" />
                <input type="submit" name="Submit" value="Submit" />
		</form>
		<br />
        <script language="javascript">

        $(function(){
          $('#renew').click(function(){
            var $oldtoken=$('#my_token').val();
            var $url ='<?=site_url('/tools/member/api/renewtoken?uuid='.$this->tppy_oauth->get_user_uuid().'&token=')?>'+$oldtoken;
            $.getJSON($url , function( ret ) {
              if(ret.response.status == true){
                $('#my_token').val(   ret.data.accesstoken.token);
              }
            });
          });
        });
        
          document.getElementById("copy_to_clipboard").addEventListener("click", function() {
            copyToClipboard(document.getElementById("my_token"));
          });
          
          function copyToClipboard(elem) {
              // create hidden text element, if it doesn't already exist
              var targetId = "_hiddenCopyText_";
              var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
              var origSelectionStart, origSelectionEnd;
              if (isInput) {
                  // can just use the original source element for the selection and copy
                  target = elem;
                  origSelectionStart = elem.selectionStart;
                  origSelectionEnd = elem.selectionEnd;
              } else {
                  // must use a temporary form element for the selection and copy
                  target = document.getElementById(targetId);
                  if (!target) {
                      var target = document.createElement("textarea");
                      target.style.position = "absolute";
                      target.style.left = "-9999px";
                      target.style.top = "0";
                      target.id = targetId;
                      document.body.appendChild(target);
                  }
                  target.textContent = elem.textContent;
              }
              // select the content
              var currentFocus = document.activeElement;
              target.focus();
              target.setSelectionRange(0, target.value.length);
              
              // copy the selection
              var succeed;
              try {
                  succeed = document.execCommand("copy");
              } catch(e) {
                  succeed = false;
              }
              // restore original focus
              if (currentFocus && typeof currentFocus.focus === "function") {
                  currentFocus.focus();
              }
              
              if (isInput) {
                  // restore prior selection
                  elem.setSelectionRange(origSelectionStart, origSelectionEnd);
              } else {
                  // clear temporary content
                  target.textContent = "";
              }
              return succeed;
          }
        </script>
        
      </div>
    </div>
    <?php 
    /*
    <div class="api_group">
      <h1>FAVORITE</h1>
      <div class="api">
        <h3>AUTHORIZE </h3>
        <div><?=site_url("/tools2/member/favoite/getFavorite?token=%token%");?></div>
        <div><a href="<?=$getFavorite_url?>" target="_blank">VIEW RESPONSE</a></div>
      </div>
    </div>
    */
    ?>
  </div>
</div>
<?php } ?>

<?php 
/*
$url='http://www.trueplookpanya.com/api/center/topicdetail?topicid=20582&type=mobile';
$res = file_get_contents($url);
$result = json_decode($res);
echo '<pre> json_decode=>'; print_r($result); echo '</pre>'; 
#var_dump($result);
*/
?>



<style>
 
  .xdebug-var-dump{word-wrap: break-word; word-break: break-word; padding: 0px; margin:0px}
  h3 a{ font-size:10px;}
  .api_group {margin-top:25px;}
  .api_group h1 { font-size:18px; font-weight:bold}
  .api {border-bottom:1px solid #666; margin-top:5px;}
  .api a {color:#0280ff;}
  .api a:hover {color:#2f8fef;}
</style>