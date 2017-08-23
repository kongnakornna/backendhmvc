<?php 
if(preg_match('~\b(overview)\b~i', strtolower($this->uri->segment(1)))){
?>

		

<script type="text/javascript">
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
	$(document).ready(function() {
			
			pageSetUp();
  $(function(){
      function load_hw1_json(){
      $('#s1_humi').load('<?php echo base_url();?>hwdata/overview/data_s1_humi.php',function(data){
      });

      $('#s1_temp').load('<?php echo base_url();?>hwdata/overview/data_s1_temp.php',function(data){
      });

      $('#s2_temp').load('<?php echo base_url();?>hwdata/overview/data_s2_temp.php',function(data){
      });

      $('#s3_temp').load('<?php echo base_url();?>hwdata/overview/data_s3_temp.php',function(data){
      });

      $('#s4_temp').load('<?php echo base_url();?>hwdata/overview/data_s4_temp.php',function(data){
      });

      $('#s5_temp').load('<?php echo base_url();?>hwdata/overview/data_s5_temp.php',function(data){
      });

      $('#s6_temp').load('<?php echo base_url();?>hwdata/overview/data_s6_temp.php',function(data){
      });

      $('#s7_temp').load('<?php echo base_url();?>hwdata/overview/data_s7_temp.php',function(data){
      });

      $('#s21_humi').load('<?php echo base_url();?>hwdata/overview/data_s21_humi.php',function(data){
      });

      $('#s21_temp').load('<?php echo base_url();?>hwdata/overview/data_s21_temp.php',function(data){
      });

      $('#s22_temp').load('<?php echo base_url();?>hwdata/overview/data_s22_temp.php',function(data){
      });

      $('#s23_temp').load('<?php echo base_url();?>hwdata/overview/data_s23_temp.php',function(data){
      });

      $('#s24_temp').load('<?php echo base_url();?>hwdata/overview/data_s24_temp.php',function(data){
      });

      $('#s25_temp').load('<?php echo base_url();?>hwdata/overview/data_s25_temp.php',function(data){
      });

      $('#s26_temp').load('<?php echo base_url();?>hwdata/overview/data_s26_temp.php',function(data){
      });

      $('#s27_temp').load('<?php echo base_url();?>hwdata/overview/data_s27_temp.php',function(data){
      });

    }
    load_hw1_json();
     setInterval(load_hw1_json,1000);
  });
 



 
	$('#load_sensor_config').load('<?php echo base_url();?>hwdata/overview/loadsensorconfig.php',function(datahw4){
	});

	$('#emailconf').load('<?php echo base_url();?>hwdata/overview/loademailconfig.php',function(datahw4){
	});

	$('#smslists').load('<?php echo base_url();?>hwdata/overview/loadersmslist.php',function(datahw4){
	});

	$('#emaillist').load('<?php echo base_url();?>hwdata/overview/loaderemaillist.php',function(datahw4){
	});



    $(function(){
	      function load_hw3_json(){
	      $('#load_hw3').load('<?php echo base_url();?>hwdata/overview/loadhw3json.php',function(datahw4){
	      });
	    }
	    load_hw3_json();
	     setInterval(load_hw3_json,500);
	}); 

	$(function(){
	      function load_hw4_json(){
	      $('#load_hw4').load('<?php echo base_url();?>hwdata/overview/loadhw4json.php',function(datahw4){
	      });
	    }
	    load_hw4_json();
	     setInterval(load_hw4_json,500);
	}); 
 

	/* Alert Log */
 
				function getDataFromDb()
				{
				  $.ajax({ 
				        url: "<?php echo base_url();?>hwdata/overview/getData.php" ,
				        type: "POST",
				        data: ''
				      })
				      .success(function(result) { 
				        var obj = jQuery.parseJSON(result);
				          if(obj != '')
				          {
				              $("#myBody").empty();
				              $.each(obj, function(key, val) {

				                  if(val["alert_status"] == 'Alert'){
				                    var label_x = "<span class='label label-danger'>";
				                  }else if(val["alert_status"] == 'Warning' ){
				                    var label_x = "<span class='label label-warning'>";
				                  }else{
				                    var label_x = "<span class='label label-success'>";
				                  }
				                    var tr = "<tr>";
				                    tr = tr + "<td>" + val["sensor_alert_log_id"] + "</td>";
                            tr = tr + "<td>" + val["sensor_hwname"] + "</td>";
				                    tr = tr + "<td>" + val["sensor_name"] + "</td>";
				                    tr = tr + "<td>" + val["sensor_type"] + "</td>";
				                    tr = tr + "<td>" + val["sensor_value"] + "</span></td>";
				                    tr = tr + "<td>" + label_x + val["alert_status"] + "</span></td>";
				                    tr = tr + "<td>" + val["datetime_alert"]+ "</td>";
				                    tr = tr + "</tr>";
				                  $('#myTable > tbody:last').append(tr);
				              });
				          }

				      });

				}

				setInterval(getDataFromDb, 1000); // 1000 = 1 second
  
				/* Alert Log */



	});
</script>



	
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE overview ONLY -->
		<script src="<?php echo base_url('theme');?>/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url('theme');?>/assets/plugins/jQRangeSlider/jQAllRangeSliders-min.js"></script>
		<script src="<?php echo base_url('theme');?>/assets/plugins/jQuery-Knob/js/jquery.knob.js"></script>
		<script src="<?php echo base_url('theme');?>/assets/js/ui-sliders.js"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE overview ONLY -->
		<script>
			jQuery(document).ready(function() {
				Index.init();
				//Main.init();
				UISliders.init();
			});
		</script>
		
<?php
}
?>