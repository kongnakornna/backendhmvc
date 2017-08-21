<?php $query_string = empty($_SERVER['QUERY_STRING']) ? '' : trim($_SERVER['QUERY_STRING']) ; ?>

<div class="container">
  <fieldset class="login-border">
      <legend class="login-border" >เข้าสู่ระบบ</legend>
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
        <?php $CI=&get_instance(); ?>
        <form action="<?php echo base_url().ltrim($_SERVER['REQUEST_URI'], '/');?>" id="loginform" name="loginform" role="form" method="post">
          <div class="form-group">
            <?php echo form_label(' ชื่อสมาชิก (Username):', 'user_username')?>
            <?php echo form_input(array('id' => 'user_username', 'name' => 'user_username','value'=> (!empty($user_username)?$user_username:'') , 'placeholder'=>'อีเมล หรือ ชื่อสมาชิก', 'class'=>'form-control')); ?>
          </div>
          <div class="form-group">
            <?php echo form_label(' รหัสผ่าน (Password):', 'user_password')?>
            <?php echo form_password(array('id' => 'user_password', 'name' => 'user_password','value'=>(!empty($user_password)?$user_password:''), 'placeholder'=>'รหัสผ่าน', 'class'=>'form-control')); ?>
          </div>
          <div class="form-group error" id="error">
            <?php echo empty($error) ? '' : $error;?>
          </div>
          <div class="form-group">
            <div class="row row-centered text-center">
              <div class="col-md-10">
                <?php echo form_error('user_username', '<span class="red">', '</span>'); ?>
                <?php echo form_button(array('name' => 'submit', 'id' => 'submit', 'value' => 'true', 'type' => 'submit', 'content' => 'เข้าสู่ระบบ', 'class'=>'btn btn-primary btn-lg')) ?>
                <div class="clearfix" style="margin:1em auto;"></div>
                <small>
                  <b>เข้าระบบผ่าน Facebook / Google+</b> 
                  <div class="clearfix"></div>
                  <span>เพื่อความสะดวกในการใช้งาน เข้าสู่ระบบได้โดยใช้บัญชี ของ facebook หรือ google+</span>
                </small>
                <div class="clearfix"></div>
                <a onclick='flogin();' style="padding: 6px 0; margin:1em auto;"><img src="assets/users/images/btn_fb.png"/></a>
                <div class="clearfix"></div>

                <a onclick='glogin();' style="padding: 6px 0; margin:1em auto;"><img src="assets/users/images/btn_gp.png"/></a>
                <hr/>
                <div class="btn btn-default outline"><a href="/register<?=$this->input->get('display') ? '?display='.$this->input->get('display') : ''?>">สมัครสมาชิกใหม่</a></div>
                <div class="clearfix"></div>
                <a href="/member/forgot<?=$this->input->get('display') ? '?display='.$this->input->get('display') : ''?>">ลืมรหัสผ่าน</a> | <a href="/member/activate<?=$this->input->get('display') ? '?display='.$this->input->get('display') : ''?>">ยืนยันการสมัคร</a>
              </div>
            </div>
          </div>
          <?php echo form_close();?>
        </div>
      </div>
  </fieldset>
</div>
<style>
  fieldset.login-border{border:1px solid #999!important;padding:0 1.4em 1.4em!important;margin:0 0 1.5em!important;border-radius:5px}
  legend.login-border{font-size:1.8em!important;font-weight:700!important;text-align:left!important;width:auto;padding:0 10px;border-radius:3px;background-color:#aa1e13;color:#fff;border:1px solid #999!important}
  .col-centered {display:inline-block;    float:none;    /* reset the text-align */    text-align:left;    /* inline-block space fix */    margin-right:-4px;; }
  .row-centered {    text-align:center;}
  .error{color:#f00;}
</style>
<script>
  function flogin() {
    var _url = 'http://www.trueplookpanya.com/tools2/member/facebookConnect2017/?type=popup&<?php echo $query_string; ?>';
    var win = window.open(_url, "windowname1", 'width=800, height=600');
  }
  
  function glogin1(){
        var _url = 'http://www.trueplookpanya.com/tools2/member/googleConnect/?type=popup&<?php echo $query_string; ?>';
    var win = window.open(_url, "windowname1", 'width=800, height=600');
  }
  
  function glogin() {
    var OAUTHURL = 'https://accounts.google.com/o/oauth2/auth?';
    var VALIDURL = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=';
    var SCOPE = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';
    var CLIENTID = '783425256556-01nddfjoj72eqvd9tise3e1l8nv9tjdf.apps.googleusercontent.com';
    var REDIRECT = '<?php echo urlencode('http://www.trueplookpanya.com/tools2/member/googleConnect?type=popup') ?>';
    var LOGOUT = 'http://accounts.google.com/Logout';
    var TYPE = 'code';
    var _url = OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENTID + '&redirect_uri=' + REDIRECT + '&response_type=' + TYPE + '&state=<?php echo urlencode($query_string) ?>';
    var acToken;
    var tokenType;
    var expiresIn;
    // var user;
    // var loggedIn = false;
    
    var win = window.open(_url, "windowname1", 'width=800, height=600');
    var pollTimer = window.setInterval(function() {
      try {
        console.log(win.document.URL);
        if (win.document.URL.indexOf(REDIRECT) != -1) {
          var url = win.document.URL;
          acToken = gup(url, 'access_token');
          tokenType = gup(url, 'token_type');
          expiresIn = gup(url, 'expires_in');
          parent.document.location.href = REDIRECT + "?type=popup&<?php echo $query_string; ?>&access_token="+acToken;
          win.close();
        }
        window.clearInterval(pollTimer);
      } catch (e) {
      }
    }, 500);
  }
  
  function gup(url, name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\#&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(url);
    if (results == null)
      return "";
    else
      return results[1];
  }

  
  function set_error(message){
    $('#error').html(message);
  }
  </script>


<script>  


  // function glogin() {
    
    
    // var OAUTHURL = 'https://accounts.google.com/o/oauth2/auth?';
    // var VALIDURL = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=';
    // var SCOPE = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';
    // var CLIENTID = '783425256556-cnj0iqeerh4h27vfj2024042f1sk56ji.apps.googleusercontent.com';
    // var REDIRECT = <?php echo urlencode('http://www.trueplookpanya.com/tools2/member/googleConnect/?type=popup&page_url=/home') ?>
    // var LOGOUT = 'http://accounts.google.com/Logout';
    // var TYPE = 'token';
    // var _url = OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENTID + '&redirect_uri=' + REDIRECT + '&response_type=' + TYPE;
    // var acToken;
    // var tokenType;
    // var expiresIn;
    // var user;
    // var loggedIn = false;
    
    // var win = window.open(_url, "windowname1", 'width=800, height=600');

    // // var pollTimer = window.setInterval(function() {
      // // try {
        // // console.log(win.document.URL);
        // // if (win.document.URL.indexOf(REDIRECT) != -1) {
          // // window.clearInterval(pollTimer);
          // // var url = win.document.URL;
          // // acToken = gup(url, 'access_token');
          // // tokenType = gup(url, 'token_type');
          // // expiresIn = gup(url, 'expires_in');
          // // win.close();

          // // validateToken(acToken);
        // // }
      // // } catch (e) {
      // // }
    // // }, 500);
  // }

  // function validateToken(token) {
    // $.ajax({
      // url: VALIDURL + token,
      // data: null,
      // success: function(responseText) {
        // getUserInfo();
        // loggedIn = true;
        // $('#loginText').hide();
        // $('#logoutText').show();
      // },
      // dataType: "jsonp"
    // });
  // }

  // function getUserInfo() {
    // $.ajax({
      // url: 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' + acToken,
      // data: null,
      // success: function(resp) {
        // user = resp;
        // console.log(user);

        // document.getElementById("name").value = user.name;
        // document.getElementById("picture").value = user.picture;
        // document.getElementById("gemail").value = user.email;
        // document.getElementById("fname").value = user.given_name;
        // document.getElementById("lname").value = user.family_name;
        // document.getElementById("gender").value = user.gender;
        // document.getElementById("formgoogle").submit();

      // },
      // dataType: "jsonp"
    // });
  // }

  //credits: http://www.netlobo.com/url_query_string_javascript.html
  // function gup(url, name) {
    // name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    // var regexS = "[\\#&]" + name + "=([^&#]*)";
    // var regex = new RegExp(regexS);
    // var results = regex.exec(url);
    // if (results == null)
      // return "";
    // else
      // return results[1];
  // }



</script>