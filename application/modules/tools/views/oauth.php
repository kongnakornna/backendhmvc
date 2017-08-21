<div class="contrainer" >

  <form method="post">
   <div class="form-group">
    <label>USERNAME</label><input type="text" name="user_username" class="form-control" />
    </div>
     <div class="form-group">
    <label>PASSWORD</label><input type="password" name="user_password" class="form-control" />
    </div>
    <button class="btn btn-default"> LOGIN </button>
  </form>

<?php if($this->input->post()) { ?>
  <div style=" word-wrap:break-word; word-break:break-word; font-size:12px; color: #333;">
    <div class="api_group">
      <h1>ACCOUNT</h1>
      <div class="api">
        <h3>AUTHORIZE [GET]</h3>
        <div><?=site_url("/member/api/authorize?username=%user_username%&password=MD5(%user_password)%&app_id=%app_id%&scopes=%scopes%&redirect_uri=%redirect_uri%&uuid=%uuid%");?></div>
        <div><a href="<?=$authorize_url?>" target="_blank">VIEW RESPONSE</a></div>
      </div>
    
      <div class="api">
        <h3>TOKEN  [GET] <button class="btn btn-default btn-xs glyphicon glyphicon-refresh" style="top:0px" id="renew"></button></h3>
        <div><?=site_url("/member/api/accesstoken?code=%code%&app_id=%app_id%&redirect_uri=%redirect_uri%&state=%state%&app_secret=%app_secret%")?></div>
        <div><a href="<?=$token_url?>" target="_blank">VIEW RESPONSE</a></div>
      </div>
      
      <div class="api">
        <h3>PROFILE  [GET]</h3>
        <div><?=site_url("/member/api/profile?token=%token%")?></div>
        <div><a href="<?=$profile_url?>" target="_blank">VIEW RESPONSE</a></div>
        
      
        <div class="form-group">
          <div class="input-group">
          <input type="text" id="my_token" class="form-control"  value="<?=$token?>"/>
              <span class="input-group-btn">
          <button class="btn btn-default" id="copy_to_clipboard">COPY</button>
          </span>
          </div>
        </div>
        <script language="javascript">

        $(function(){
          $('#renew').click(function(){
            var $oldtoken=$('#my_token').val();
            var $url ='<?=site_url('/member/api/renewtoken?uuid='.$this->tppy_oauth->get_user_uuid().'&token=')?>'+$oldtoken;
            $.getJSON($url , function( ret ) {
              if(ret.response.status == true){
                $('#my_token').val(   ret.data.token);
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
    <div class="api_group">
      <h1>FAVORITE</h1>
      <div class="api">
        <h3>AUTHORIZE </h3>
        <div><?=site_url("/member/favoite/getFavorite?token=%token%");?></div>
        <div><a href="<?=$getFavorite_url?>" target="_blank">VIEW RESPONSE</a></div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<style>
 
  .xdebug-var-dump{word-wrap: break-word; word-break: break-word; padding: 0px; margin:0px}
  h3 a{ font-size:10px;}
  .api_group {margin-top:25px;}
  .api_group h1 { font-size:18px; font-weight:bold}
  .api {border-bottom:1px solid #666; margin-top:5px;}
  .api a {color:#0280ff;}
  .api a:hover {color:#2f8fef;}
</style>