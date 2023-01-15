<?php

			$language = $this->lang->language; 
			if (!$this->session->userdata('user_name')) {

			} else {
				$userinput = $this->session->userdata('user_name');
			}
			//Debug($admin_menu);
			 $langn=$language['lang'];
?>
			<h1><?php //echo $headtxt; ?> <?php //echo $language['welcome'];  echo ' : '; echo $userinput; ?></h1>
			

<div class="row">
<!--
<?php  if($this->uri->segment(2) == "welcome"){ ?>
            <div class="col-sm-12">
				<p><?php echo $language['welcome']; ?>:</p>
				<code><?php echo $userinput; ?></code>
			</div>
<?php  }   ?>
-->
			<div class="col-sm-12">
				<?php
					/*if($session)
						foreach($session as $key => $v){
								echo "$key => $v <br>";
						}*/
#Debug($admin_menu);
					//if($this->uri->segment(1) == "dashboard"){
						if($admin_menu)
								for($i=0;$i<count($admin_menu);$i++){
										
										if(isset($admin_menu[$i]['admin_menu_id'])){
											$icon = ($admin_menu[$i]['admin_menu_id'] == 28) ? 'fa '.$admin_menu[$i]['icon'] : 'fa '.$admin_menu[$i]['icon'];
											if($langn=='en'){
											$title = $admin_menu[$i]['title_en'];
											}else if($langn=='th'){
											$title = $admin_menu[$i]['title_th'];
											}
											$alink = (trim($admin_menu[$i]['url']) != '') ? base_url($admin_menu[$i]['url']) : '#';
											
											if($admin_menu[$i]['parent'] == 0 && $alink != '#'){
													echo '<a href="'.$alink.'"><button class="btn btn-app btn-primary">
														<i class="ace-icon '.$icon.' bigger-330"></i>
														'.$title.'
													</button>
													 
													
													</a>';
													//Debug($admin_menu[$i]);
											}
										}
								
								}
					//}
				?>
			</div>


 <!-- Dialog 
<?php if($this->session->userdata('admin_type') >= 7 || $this->session->userdata('admin_id') == 1 ){ ?>
            <div class="col-sm-12">
							<div class="alert alert-info">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>
											<strong> เลือกเมนูที่ต้องการใช้งาน  </strong><br>
 
							</div>
			</div>
<?php } ?>
 -->
</div>
