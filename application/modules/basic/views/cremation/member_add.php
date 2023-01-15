<?php $language = $this->lang->language; ?>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<div class="row">
									<!-- <form role="form" class="form-horizontal" action="category/save"> -->
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open('cremation/save', $attributes);
?>
									<div class="page-header">
										<h1>
											<?php echo $language['add'] ?>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['member'];echo $language['cremation']; ?>
											</small>
										</h1>
									</div>

									<div class="col-xs-12">
										<!-- <h3 class="header smaller lighter blue"><?php echo $language['category'] ?></h3> -->
										<!-- <div class="table-header">
											<?php echo $language['category'].$language['member'] ?>
										</div> -->

				<!-- <code><?php //echo $sql; ?></code> -->
<?php
				if(function_exists('Debug')){
					//Debug($this->db) ;
					//Debug($category );
					//Debug($this->lang->language);
				}
?>
<?php
			if(isset($error)){
?>
											<div class="alert alert-danger">
												<button data-dismiss="alert" class="close" type="button">
													<i class="ace-icon fa fa-times"></i>
												</button>
												<strong>
													<i class="ace-icon fa fa-times"></i>
													Oh snap!</strong><?php echo $error?>.
												<br>
											</div>
<?php
			}
?>
							
 <!-- #section:elements.form -->
 <div class="form-group">
	<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['startdate']?></label>
     <div class="col-sm-9">
		 <div class="input-group">
			 <input class="form-control date-picker" id="id-date-picker-1" placeholder="<?php echo $language['startdate']?>" name="startdate" type="text" data-date-format="dd-mm-yyyy" />
				 <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i> </span>
		 </div>
    </div>
</div>

<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status_type']?></label>
<div class="col-sm-9"><?php echo $memberstatus ?>  </div>
</div>
<hr />
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['idcard']?></label>
   <div class="col-sm-9">
	<input name="idcard" type="text" class="col-xs-10 col-sm-2" id="idcard" size="13" maxlength="13" placeholder="<?php echo $language['idcard']?>">
   </div>

</div>

<div class="form-group">
	<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['birthday']?></label>
     <div class="col-sm-9">
		 <div class="input-group">
			 <input class="form-control date-picker" id="id-date-picker-1"  placeholder="<?php echo $language['birthday']?>" name="birthday" type="text" data-date-format="dd-mm-yyyy" />
				 <span class="input-group-addon"> <i class="fa fa-calendar bigger-110"></i> </span>
		 </div>
    </div>
</div>
									
<?php ##########################?>
<div class="form-group">
 <label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['sex']?></label>
	 <div class="col-sm-9">
		 <select class="form-control" id="gender" name="gender">
			 <option value="m"><?php echo $language['male']?></option>
			 <option value="f"><?php echo $language['female']?></option>
		 </select>
	 </div>
</div>
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['name']?></label>
   <div class="col-sm-9">
	<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['name']?>" id="name" name="name">
   </div>
</div>
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['lastname']?></label>
   <div class="col-sm-9">
	<input type="text" class="col-xs-10 col-sm-7" placeholder="<?php echo $language['lastname']?>" id="lastname" name="lastname">
   </div>
</div>
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['minnane']?></label>
   <div class="col-sm-9">
	<input type="text" class="col-xs-10 col-sm-2" placeholder="<?php echo $language['minnane']?>" id="minnane" name="minnane">
   </div>
</div>
<hr />
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['address']?></label>
   <div class="col-sm-9">
	<input type="text" class="col-xs-10 col-sm-8" placeholder="<?php echo $language['address']?>" id="address" name="address">
   </div>
</div>

<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['countries']?></label>
<div class="col-sm-9"><?php echo $countries_list ?></div>
</div>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['geography']?></label>
<div class="col-sm-9"><?php echo $geography_list ?></div>
</div>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['province']?></label>
<div class="col-sm-9"><?php echo $province_list ?> </div>
</div>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['amphur']?></label>
<div class="col-sm-9"><?php echo $amphur_list ?>   </div>
</div>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['district']?></label>
<div class="col-sm-9"><?php echo $district_list ?> </div>
</div>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['village']?></label>
<div class="col-sm-9"><?php echo $village_list ?>  </div>
</div>
<hr />
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['phone']?></label>
   <div class="col-sm-3">
	<input type="text" class="col-xs-10 col-sm-8" placeholder="<?php echo $language['phone']?>" id="phone" name="phone">
   </div>
</div>
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['mobile']?></label>
   <div class="col-sm-3">
	<input type="text" class="col-xs-10 col-sm-8" placeholder="<?php echo $language['mobile']?>" id="mobile" name="mobile">
   </div>
</div>
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['remark']?></label>
   <div class="col-sm-9">
	<input type="text" class="col-xs-10 col-sm-8" placeholder="<?php echo $language['remark']?>" id="mobile" name="remark">
   </div>
</div>

<hr />
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['email']?></label>
   <div class="col-sm-6">
	<input type="text" class="col-xs-10 col-sm-8" placeholder="<?php echo $language['email']?>" id="email" name="email">
   </div>
</div>
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['username']?></label>
   <div class="col-sm-4">
	<input type="text" class="col-xs-10 col-sm-8" placeholder="<?php echo $language['username']?>" id="username" name="username">
   </div>
</div>
<?php ##########################?>
<div class="form-group">
<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['password']?></label>
   <div class="col-sm-4">
	<input type="password" class="col-xs-10 col-sm-8" placeholder="<?php echo $language['password']?>" id="password" name="password">
   </div>
</div>
<?php ##########################?>
<hr />
<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>

										<div class="col-xs-3">
													<label>
														<input name="status" type="checkbox"  class="ace ace-switch ace-switch-4 btn-empty"  value=1 checked="checked">
														<span class="lbl"></span>
													</label>
												</div>
										</div>
									</div>

									<div style="clear: both;"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn btn-info">
												<i class="ace-icon fa fa-check bigger-110"></i>
												<?php echo $language['save']?>
											</button>
											&nbsp; &nbsp; &nbsp;
											<button type="reset" class="btn">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												<?php echo $language['reset']?>
											</button>
										</div>
									</div>

							</div>
						<?php echo form_close();?>
						</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
<?php
		if($countries_id > 0){
		$countries_id='209';
		$geography_id='2';
?>
			$('#geography_id').load('<?php echo base_url() ?>geography/list_dd/' + <?php echo $countries_id?> + '/' + <?php echo $geography_id?>);
<?php
		}
 
?>
 
////////////////////////  Country --geography	
 
		$('#countries_id').change(function( ) {
				var id = $(this).val();
				  //alert('<?php echo base_url() ?>geography/list_ddi/' + id);
				 $('#geography_id').load('<?php echo base_url() ?>geography/list_ddi/' + id);
		});		
 
////////////////////////  geography--province	

		$('#geography_id').change(function( ) {
				var id = $(this).val();
				 //alert('<?php echo base_url() ?>province/list_dd/' + id);
				 $('#province_id_map').load('<?php echo base_url() ?>province/list_dd/' + id);
		});		
////////////////////////  province---amphur		
		$('#province_id_map').change(function( ) {
				var id = $(this).val();
				//  alert('<?php echo base_url() ?>amphur/list_dd/' + id);
				 $('#amphur_id_map').load('<?php echo base_url() ?>amphur/list_dd/' + id);
		});		
////////////////////////  amphur---district		
		$('#amphur_id_map').change(function( ) {
				var id = $(this).val();
				 // alert('<?php echo base_url() ?>district/list_dd/' + id);
				 $('#district_id_map').load('<?php echo base_url() ?>district/list_dd/' + id);
		});		
////////////////////////  district---village		
		$('#district_id_map').change(function( ) {
				var id = $(this).val();
				  //alert('<?php echo base_url() ?>village/list_dd/' + id);
				 $('#village_id_map').load('<?php echo base_url() ?>village/list_dd/' + id);
		});		
////////////////////////
		console.log( "ready!" );

		$('#enable9').click(function( ) {
				alert($(this).attr('id'));
				/*$.ajax({
						url: "http://search.twitter.com/search.json",
						data: {
						q: query
						},
						dataType: "jsonp",
						success: defer.resolve,
						error: defer.reject
				});*/
		});
});

</script>