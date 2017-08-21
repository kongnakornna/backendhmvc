<script>//window.location='http://elvis.siamsport.co.th/services/login?username=chaipat&password=inspire';</script>
<?php
		//Debug($this->session->userdata);
?>
<style type="text/css">
span.logo-elvis {
    background-image: url("images/logo-elvis.svg");
	background-color: transparent;
	padding: 0px;
	white-space: nowrap;
	background-repeat: repeat-x;
}	
</style>
								<div class="col-sm-6">
										<div class="widget-box">
											<div class="widget-header">
												<span class="logo-elvis"></span><h4 class="widget-title">Elvis</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<form class="form-inline" action="http://elvis.siamsport.co.th/services/login" method="get" name="loginform" >
														<input type="text" class="input-small" placeholder="Username" name="username" />
														<input type="password" class="input-small" placeholder="Password" name="password" />
														<button type="button" class="btn btn-info btn-sm" id="login_submit">
															<i class="ace-icon fa fa-key bigger-110"></i>Login
														</button>
													</form>
												</div>
											</div>
										</div>
								</div>

								<div class="col-sm-6">&nbsp;</div>

								<div class="col-sm-6" id="respone-code">
										
								</div>

<script type="text/javascript">
$(document).ready(function () {

		$('#login_submit').click(function(){
				
			var user = document.loginform.username.value;
			var pass = document.loginform.password.value;
			
			//$('#save_results').html('saving...');
			//$('#preview-pane').attr('style', 'display:none;');

			$('#respone-code').html('<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>Login...!</strong><br></div>');

			$.ajax({
			   type: "POST",
			   url: "http://elvis.siamsport.co.th/services/login?username=" + user + "&password=" + pass,
			   //data: "username : user, password : pass ",
			   success: function(data){
					//$('#save_results').html(data);
					//loginSuccess
					//alert(data.getJSON());
					var items = [];
					  $.each( data, function( key, val ) {

								//items.push( "<li id='" + key + "'>" + val + "</li>" );
								//$('#respone-code').append(key + ' = ' + val + '<br>');
								if(key == "loginSuccess"){
										//if(val == "true") alert('Login success.');
										//else alert('Login failed.');
										if(val == true) 
												$('#respone-code').html('<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>Login.....!</strong><br>Login success.<br></div>');
										else 
												$('#respone-code').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>Login.....!</strong><br>Login failed.<br></div>');
								}
					  });
					  /*$( "<ul/>", {
							"class": "my-new-list",
							html: items.join( "" )
						}).appendTo( "#respone-code" );*/
						//window.location='<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>';
				}
			});
		});

});
</script>