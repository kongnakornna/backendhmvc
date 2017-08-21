<?php 
$language = $this->lang->language; 
$i=0;
//$maxcat = count($member_type);
//Debug($search_form);
$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
echo form_open('cremation/listview', $attributes);
if(!isset($search_form['sortby'])) $search_form['sortby'] = 'lastupdate_date';
##############

if(!isset($countries_id)) $countries_id = '';
if(!isset($geography_id)) $geography_id = '';
if(!isset($province_id_map)) $province_id_map = '';
if(!isset($amphur_id_map)) $amphur_id_map = '';
if(!isset($district_id_map)) $district_id_map = '';
if(!isset($village_id_map)) $village_id_map = '';
if(!isset($member_type)) $member_type ='';
##############
?>
<div class="col-xs-12">
		<div class="col-xs-2">
				<a href="<?php echo site_url('cremation/add') ?>"><button class="btn btn-sm btn-primary" type="button">
						<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['record'].' '.$language['member']  ?>
				</button></a> 
				<br><?=$member_all?> <?php echo $language['record'] ?>
		</div>
<?php #############################?>
<div class="col-xs-2"><?php echo $countries_list ?></div>
<div class="col-xs-2"><?php echo $geography_list ?></div>
<div class="col-xs-2"><?php echo $province_list ?> </div>
<div class="col-xs-2"><?php echo $amphur_list ?>   </div>
<div class="col-xs-2"><?php echo $district_list ?> </div>
<div class="col-xs-2"><?php echo $village_list ?>  </div>
<div class="col-xs-2"><?php echo $memberstatus ?>  </div> 

<div class="col-xs-1">
<select class="form-control" id="form-field-select-1" name="gender">
					<option <? //if(!isset($this->input->post()) echo 'selected'; ?>><?php echo $language['all']?></option>
					<option value="m" <?php echo ($this->input->post('gender') =='m') ? 'selected' : '' ?>><?php echo $language['male']?></option>
					<option value="f" <?php echo ($this->input->post('gender') =='f') ? 'selected' : '' ?>><?php echo $language['female']?></option>
</select>
</div>

 <div class="col-xs-1">
<select class="form-control" id="form-field-select-1" name="gender">
					<option <? //if(!isset($this->input->post()) echo 'selected'; ?>><?php echo $language['all']?></option>
					<option value="idcard" <?php echo ($this->input->post('idcard') =='idcard') ? 'selected' : '' ?>><?php echo $language['idcard']?></option>
					<option value="fullname" <?php echo ($this->input->post('fullname') =='fullname') ? 'selected' : '' ?>><?php echo $language['full_name']?></option>
</select>
</div>

 <div class="col-xs-3">
	<input type="text" class="col-xs-10 col-sm-6" placeholder="<?php echo $language['search']?>" id="searchkey" name="searchkey">
 </div>
 




<!--  
   <div class="col-sm-2">
	 <div class="input-group">
	<input class="form-control date-picker" id="id-date-picker-1" placeholder="<?php echo $language['startdate']?>" name="startdate" type="text" data-date-format="dd-mm-yyyy" />
	 </div>
    </div>
 
   <div class="col-sm-2">
		 <div class="input-group">
		 <input class="form-control date-picker" id="id-date-picker-1" placeholder="<?php echo $language['enddate']?>" name="enddate" type="text" data-date-format="dd-mm-yyyy" /> 
		 </div>
    </div>
 
-->

<!-- #########################
<div class="col-xs-2">
<select class="form-control" id="sortby" name="sortby">
					<option value="0">-</option>
					<option value="create_date" <?php echo ($search_form['sortby'] == "create_date") ? 'selected' : '' ?>><?php echo $language['create_date']?></option>
					<option value="lastupdate_date" <?php echo ($search_form['sortby'] == "lastupdate_date") ? 'selected' : '' ?>><?php echo $language['lastupdate']?></option>
					<option value="member_id_map" <?php echo ($search_form['sortby'] == "id") ? 'selected' : '' ?>>ID</option>
					<option value="first_name" <?php echo ($search_form['sortby'] == "first_name") ? 'selected' : '' ?>><?php echo $language['name']?></option>
					<option value="nick_name" <?php echo ($search_form['sortby'] == "nick_name") ? 'selected' : '' ?>><?php echo $language['nickname']?></option>
</select>		
</div>
-->
<!--
<div class="col-xs-2"><select class="form-control" id="form-field-select-1" name="member_type"><option value="0"><?php echo $language['member_type']?></option>
<?php
 /*
				$datamember_type = $this->input->post('member_type');
				$alltype = count($member_type);
				if($member_type)
						for($i = 0; $i < $alltype; $i++){
									$selected = ($datamember_type == $member_type[$i]['member_type_id_map']) ? 'selected' : '';
									echo '<option value="'.$member_type[$i]['member_type_id_map'].'" '.$selected.'>'.$member_type[$i]['member_type_name'].'</option>';
						}
*/
?>
			</select>
</div>
-->
 
<div class="col-xs-1">
			<button class="btn btn-sm btn-primary" type="submit">
					<i class="ace-icon fa fa-glass bigger-55"></i>Filter
			</button>
			 
</div>
<!-- #########################-->
</div>
<?php
			// Debug($this->input->post());
			//if(function_exists('Debug')) Debug($news);
			if(isset($error)){
?>
				<div class="col-xs-12">
							<div class="alert alert-danger">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon fa fa-times"></i>
											Oh snap!</strong><?php echo $error?>.
									<br>
							</div>
				</div>
<?php
			}
?>
<?php
			if(isset($success)){
?>
				<div class="col-xs-12">
							<div class="alert alert-success">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon fa fa-times"></i>
											</strong><?php echo $success?>.
									<br>
							</div>
				</div>
<?php
			}
?>
<div class="col-xs-12">
		<div>
<?php
//Debug($member_list);
?> 
		<form method="post" action="" id="listFrm">
											<table width="100%" class="table-responsive table table-striped table-bordered table-hover " id="dataTables-member">
												<thead>
													<tr>
														<th width="51"><?php echo $language['no'] ?>.</th>
														<th width="104"><?php echo $language['idcard'] ?></th>
														<!-- <th width="129"><?php echo $language['image'] ?></th> -->
														<th width="184"><?php echo $language['full_name'] ?></th>
														<th width="58" class="hidden-480"><?php echo $language['sex'] ?></th>
														<th width="73" class="hidden-480"><?php echo $language['balance'] ?></th>
														<th width="149" class="hidden-480"> <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i> <?php echo $language['register'] ?> </th>
														<th width="130" class="hidden-480">
															<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
															<?php echo $language['dateactive'] ?>														</th>
														<th class="hidden-480" width="300"><?php echo $language['status'] ?></th>
														<th width="121"><?php echo $language['member_type'] ?></th>
														<th width="86"><?php echo $language['action'] ?></th>
													</tr>
												</thead>
	<tbody>												
<?php
			$allmember = count($member_list);
			if($member_list)
					for($i=0;$i<$allmember;$i++){
								$number = $i+1;
/////////////////////////
$member_id_map=$member_list[$i]['member_id_map'];
$member_id_map=$member_list[$i]['member_id_map'];
$member_id_map=$member_list[$i]['member_id_map'];
$idcard=$member_list[$i]['idcard'];
$idcard2=$member_list[$i]['idcard2'];
$gender=$member_list[$i]['gender'];
$name=$member_list[$i]['name'];
$lastname=$member_list[$i]['lastname'];
$minnane=$member_list[$i]['minnane'];
$full_name=$name.' '.$lastname;
$birthday=$member_list[$i]['birthday'];
$startdate=$member_list[$i]['startdate'];
$activedate=$member_list[$i]['activedate'];
$diedate=$member_list[$i]['diedate'];
$usedate=$member_list[$i]['usedate'];
$status=$member_list[$i]['status'];
$balance=$member_list[$i]['balance'];
$phone=$member_list[$i]['phone'];
$mobile=$member_list[$i]['mobile'];
$email=$member_list[$i]['email'];
$username=$member_list[$i]['username'];
$password=$member_list[$i]['password'];
$create_by=$member_list[$i]['create_by'];
$update_by=$member_list[$i]['update_by'];
$create_date=$member_list[$i]['create_date'];
$update_date=$member_list[$i]['update_date'];
$address=$member_list[$i]['address'];
$countries_id=$member_list[$i]['countries_id'];
$geography_id=$member_list[$i]['geography_id'];
$province_id_map=$member_list[$i]['province_id_map'];
$amphur_id_map=$member_list[$i]['amphur_id_map'];
$district_id_map=$member_list[$i]['district_id_map'];
$village_id_map=$member_list[$i]['village_id_map'];
$remark=$member_list[$i]['remark'];
$lang=$member_list[$i]['lang'];
/////////////////////////
$member_type_name=$member_list[$i]['member_type_name'];
$status_type_name=$member_list[$i]['status_type_name'];
/////////////////////////
$countries_name=$member_list[$i]['countries_name'];
$geo_name=$member_list[$i]['geo_name'];
$province_name=$member_list[$i]['province_name'];
$amphur_name=$member_list[$i]['amphur_name'];
$district_name=$member_list[$i]['district_name'];
$village_name=$member_list[$i]['village_name'];

?>
		<tr>
						<!-- <td class="center">
								<label class="position-relative">
										<input type="checkbox" class="ace" name="selectid[]" value="<?php //echo $tag_id ?>"/>
										<span class="lbl"></span>
								</label>
						</td> -->
						<td>
								<?=$number?>
								<input type="hidden" class="ace" name="selectid[]" value="<?=$member_id_map?>" />						</td>
						<td><a href="#">
						  <?=$idcard?>
						  </a></td>
						<td><a href="#"><?=$full_name?></a></td>
						<td class="hidden-180">
						<?php 
						if($gender=='m'){
						echo $language['male'];
						}else if($gender=='f'){
						echo $language['female'];
						}
						?>
						</td>
						<td class="hidden-180"> <?php echo $balance?></td>
						<td class="hidden-180"> 					
<?php 
        $strDate = $startdate;
		$strMonth1= date("n",strtotime($strDate));
		$strMonthCut1 = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai1=$strMonthCut1[$strMonth1];
	    $strYear2 = date("Y",strtotime($strDate))+543;
		$strHour3= date("H",strtotime($strDate));
		$strMinute3= date("i",strtotime($strDate));
		$strSeconds3= date("s",strtotime($strDate));
        $timena=$strHour3.':'.$strMinute3.':'.$strSeconds3;
		$strYear4 = date("Y",strtotime($strDate))+543;
		$strMonth4= date("n",strtotime($strDate));
		$strDay4= date("j",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai3=$strMonthCut[$strMonth4];
		$datena=$strDay4.' '.$strMonthThai3.' พ.ศ.'.$strYear4;
	####################
$lang=$this->lang->line('lang');
if($lang=='th'){
echo "<span style='color: red;' /> $datena </span>";
}else if($lang=='en'){
echo "<span style='color: red;' /> $startdate </span>";
}
?>						</td>
						<td class="hidden-180"> 
<?php 
        $strDate = $activedate;
		$strMonth1= date("n",strtotime($strDate));
		$strMonthCut1 = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai1=$strMonthCut1[$strMonth1];
	    $strYear2 = date("Y",strtotime($strDate))+543;
		$strHour3= date("H",strtotime($strDate));
		$strMinute3= date("i",strtotime($strDate));
		$strSeconds3= date("s",strtotime($strDate));
        $timena=$strHour3.':'.$strMinute3.':'.$strSeconds3;
		$strYear4 = date("Y",strtotime($strDate))+543;
		$strMonth4= date("n",strtotime($strDate));
		$strDay4= date("j",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai3=$strMonthCut[$strMonth4];
		$datena2=$strDay4.' '.$strMonthThai3.' พ.ศ.'.$strYear4;
	####################
$lang=$this->lang->line('lang');
if($lang=='th'){
echo "<span style='color: green;' />  $datena2 </span>";
}else if($lang=='en'){
echo "<span style='color: green;' /> $activedate </span>";
}
?>						</td>
						<td class="hidden-580"><span class="col-sm-12"><?php
						if($status==1){
						echo "<span style='color: blue;' /> $status_type_name </span>";
                        }else{echo $status_type_name;}
						 ?>						</td>
						<td><?=$member_type_name?></td>
						<td> 
										<div class="hidden-sm hidden-xs action-buttons">
													<a class="blue" href="#" target=_blank>
															<i class="ace-icon fa fa-search-plus bigger-130"></i>													</a>

													<a class="green" href="#">
															<i class="ace-icon fa fa-pencil bigger-130"></i>													</a>

													<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm<?php echo $member_id_map?>" data-value="<?php echo $member_id_map?>" data-name="<?php echo $full_name; ?>">
															<i class="ace-icon fa fa-trash-o bigger-130"></i>													</a>
													<!-- <button id="bootbox-confirm" class="btn btn-info">Confirm</button> -->
										</div>

										<div class="hidden-md hidden-lg">
													<div class="inline position-relative">
																	<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
																		<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>																	</button>


<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
<li>
<a href="<?php echo base_url('cremation/detail/'.$member_id_map)?>" class="tooltip-info" data-rel="tooltip" title="Preview" target=_blank> <span class="blue">
<i class="ace-icon fa fa-search-plus bigger-120"></i></span></a>																		</li>

<li>
<a href="<?php echo base_url('cremation/edit/'.$member_id_map)?>" class="tooltip-success" data-rel="tooltip" title="Edit"> <span class="green">
<i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span></a>
</li>


<li>
<a href="javascript:void(0);" id="bx-confirm<?=$member_id_map?>" class="tooltip-error del-confirm" data-value="<?php echo $member_id_map?>" data-name="<?php echo $idcard?> - <?php echo $full_name?>" data-rel="tooltip" title="Delete"><span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></a>
</li>
</ul>
							 </div>
						  </div>						</td>
		</tr>
<?php
					}
?>
	</tbody>
</table>
</form>
		</div><!-- PAGE CONTENT ENDS -->
<?php
	echo form_close();
?>
</div><!-- /.col -->

<script type="text/javascript">
///////////////////////////
$( document ).ready(function() {
////////////////////////  Country --geography	
        $('#dataTables-member').dataTable();
		$('#countries_id').change(function( ) {
				var id = $(this).val();
				  //alert('<?php echo base_url() ?>geography/list_ddi/' + id);
				 $('#geography_id').load('<?php echo base_url() ?>geography/list_ddi/' + id);
		});		
 
////////////////////////  geography--province	

		$('#geography_id').change(function( ) {
				var id = $(this).val();
				// alert('<?php echo base_url() ?>province/list_dd/' + id);
				 $('#province_id_map_map').load('<?php echo base_url() ?>province/list_dd/' + id);
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


		$('#saveorder').on('click', function() {
				document.getElementById("jform").submit();
		});		
});
///////////////////////////
jQuery(function($) {

		var $overflow = '';
		var colorbox_params = {
			rel: 'colorbox',
			reposition:true,
			scalePhotos:true,
			scrolling:false,
			previous:'<i class="ace-icon fa fa-arrow-left"></i>',
			next:'<i class="ace-icon fa fa-arrow-right"></i>',
			close:'&times;',
			current:'{current} of {total}',
			maxWidth:'100%',
			maxHeight:'100%',
			onOpen:function(){
				$overflow = document.body.style.overflow;
				document.body.style.overflow = 'hidden';
			},
			onClosed:function(){
				document.body.style.overflow = $overflow;
			},
			onComplete:function(){
				$.colorbox.resize();
			}
		};
		$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
		$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange'></i>");//let's add a custom loading icon
		$('.status-buttons').on('click', function() {

				var id = $(this).attr('id');
		    	var n = id.length;
			    var maxstr = n-6;
			    var res = id.substr(6, maxstr);

				//alert('status-buttons ' + res);

				$.ajax({
					type: 'POST',
					url: "<?php echo base_url('member/status')?>/" + res,
					//data: {id: res},
					cache: false,
					success: function(data){
							$("#msg_info").fadeOut();
							if(data == 1){
								$("#msg_success").attr('style','display:block;');
								//AlertSuccess	('Active And Generate json file.');
								AlertSuccess	('Active.');
							}else{
								$("#msg_error").attr('style','display:block;');
								AlertError('Inactive');
							}
					}
				});
		});

		$('.del-confirm').on('click', function() {				
				var id = $(this).attr('data-value');
				var name = $(this).attr('data-name');
				bootbox.confirm("<?php echo $language['are you sure to delete']?> " + name, function(result) {
					if(result) {
							//alert('<?php echo base_url('member/delete')?>/' + id + '');
							window.location='<?php echo base_url('member/delete')?>/' + id ;
						}
				});
		});
		
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

})


/////////////////////////////////

</script>
<?php  //echo js_asset('checkall.js'); ?>