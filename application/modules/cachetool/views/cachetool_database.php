<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$language = $this->lang->language; 
$lang=$language['lang']; 
//$data_input = $this->input->post();
$data_input=@$this->input->get();
$dirfile=@$data_input['dirfile'];
if($dirfile==Null){
$dir=$this->config->item('cache_path_db');
}else{
$dir1=$this->config->item('cache_path_db');
$dirfile=str_replace(' ','+',$dirfile);
$dir=$dir1.$dirfile;
}
 

	 
		date_default_timezone_set("Asia/Bangkok");
		$y=date('Y');
		$m=date('m');
		$d=date('d');
		$h=date('H');
		$i=date('i');
		$s=date('s');
		
		 ##########
       $datenow=strtotime("now");
       $datetomorrow=strtotime("tomorrow");
       $yesterday=strtotime("yesterday");
       $date1day=strtotime("+1 day");
       $date1week=strtotime("+1 week");
       $lastweek=strtotime("lastweek");
       $date1week2day=strtotime("+1 week 2 days 4 hours 2 seconds");
       $datenextthursday=strtotime("next Thursday");
       $datenowlastmonday=strtotime("last Monday");
       $date2pmyesterday=strtotime("2pm yesterday");
       $date7am12daysago=strtotime("7am 12 days ago");
       $yesterday =date("Y-m-d", $yesterday); 
       $time=date('H:i:s');
       ##########
       $yesterday=$yesterday.' '.$time; 
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
.col-sm-6 {width: 100%;text-align: center;	}
#saveorder{cursor:pointer;}
.input-small{text-align: center;}
</style>
<br />
<div class="col-xs-12">
<script>
	function confirmcmsdeleteall() {
        swal({
            title: " <?php echo $language['delete'];?> Cache all",
            text: " <?php echo $language['are you sure to delete'];?>  File Cache all ",  
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "<?php echo $language['yes'];?>!",
            cancelButtonColor: "#DD6B55",
            cancelButtonText: "<?php echo $language['notyes'];?>!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        }, function () {
            setTimeout(function () {
                // Javascript URL redirection 
				<?php $confirmcmsblogfile=base_url('cachetool/clear_all_cache_db'); ?>
                window.location.replace("<?php echo $confirmcmsblogfile; ?>");
            }, 50);
        });
    };
</script>
<a href="#"onclick="confirmcmsdeleteall()"title=" File Cache all "> 
<button class="btn btn-app btn-danger btn-sm">
<i class="ace-icon fa fa-trash-o bigger-200"></i>
<?php echo $language['delete'].' '.$language['all']; ?>
</button>
</a>  


 							
</div>
<hr>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								 

								<div class="row">
									<div class="col-xs-12">
<?php
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


<div>
<?php

Debug($dir);
	
?>
<?php ###############?>
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="clip-cog-2"></i><?php echo $language['cachetool']; ?>
									<div class="panel-tools">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
								
									</div>
								</div>

<div class="table-responsive">
	<table width="100%"  class="table table-striped table-bordered table-hover" id="sample-table-2">
<!--
<div>
	<table>-->
<thead>
		<tr>
				<th width="3%"><?php echo $language['no']; ?></th>
				<th width="76%"><?php echo $language['cachekey']; ?></th>
				<th width="9%" class="hidden-480"><?php echo $language['activity']; ?></th>
				<th width="9%" class="hidden-480">
				<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
				<?php echo $language['access']; ?>				</th>
		</tr>
</thead>
				<tbody>
<?php
 
        if(is_dir($dir)){
            if($dh = opendir($dir)){
             $i=-1;
                while(($file = readdir($dh)) !== FALSE){    
                if($file =='..'||$file =='.'){}else{
    ?>

		<tr>
						<td><?php echo $i; ?></td>
						<td>
						<a href="<?php echo base_url().'cachetool/database2?dirfile='.$file; ?>"><?php echo $file;?> </a> 
						
						</td>
						<td class="hidden-480">
 
						 
						 
<script>
<?php $cmsblog_file_id=$i; ?>
	function confirmcmsblogfile<?php echo $cmsblog_file_id;?>() {
        swal({
            title: " <?php echo $language['delete'];?> File <?php echo  $file;?> ",
            text: " <?php echo $language['are you sure to delete'];?>  File <?php echo  $file;?>  ",  
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "<?php echo $language['yes'];?>!",
            cancelButtonColor: "#DD6B55",
            cancelButtonText: "<?php echo $language['notyes'];?>!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        }, function () {
            setTimeout(function () {
                // Javascript URL redirection 
				<?php $confirmcmsblogfile=base_url().'cachetool/delete_cache_file_db?uri=cachetool/testdriver&file='.$file.'&dirfile='.$dirfile; ?>
                window.location.replace("<?php echo $confirmcmsblogfile; ?>");
            }, 50);
        });
    };
</script> 			
 <a href="#"onclick="confirmcmsblogfile<?php echo $cmsblog_file_id;?>()"title="<?php echo $language['delete'];?> <?php echo $file;?>">
 <button class="btn btn-app btn-danger btn-sm">
<i class="ace-icon fa fa-trash-o bigger-200"></i>
<?php echo $language['delete']; ?>
</button>
 </a>  
	<?php #$delete=$language['delete'];echo "<b><font color='red'> $delete </font></b>";?>					 
					    </td>
						<td class="hidden-480"> 
						
						 
<?php
$cache_path=$this->config->item('base_url');
?>						 
<a class="btn btn-app btn-success" href="<?php echo $cache_path.'file/dbcache/'.$dirfile.'/'.$file;?>"title="<?php  echo $language['detail']; ?> <?php echo $file;?>"target="_blank">
<i class="ace-icon fa fa-refresh bigger-230"></i>
 <?php  echo $language['detail']; ?>
</a>
						</td>
		</tr>
											  
	
<?php 
                }        
                $i++;
                }
                closedir($dh);
            }
        }
?>
		</tbody>
	</table>
</div>

 <!--
<center>
	<div class="panel-body"><?php //echo $pagination;?></div> 
</center> 
-->

<?php

?></div>
										</div>
									</div>
								</div>
 
						<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->





