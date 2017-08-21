<?php

echo image_asset('logo-ga-400.png');
echo '<br>Google Library version '.$this->google->getLibraryVersion();
echo "<hr>";
/*
try {
	if( $result_new->getRows() ) {
		echo "pageviews<br>";
		//echo json_encode($result->getRows());
		echo "<pre>";
		print_r( $result_new->getRows() );
		echo "</pre>";
		echo "<br><hr>";
	}
} catch(Exception $e) {
	echo 'There was an error : - ' . $e->getMessage();
}*/
$getRows = count($result_top_view->getRows());
?>

<div class="row">
	<div class="col-sm-5">
		<div class="widget-box transparent">
			<div class="widget-header widget-header-flat">
				<h4 class="widget-title lighter">
					<i class="ace-icon fa fa-star orange"></i>
					Popular View <?php echo $getRows ?> ย้อนหลัง 7 วัน
				</h4>

				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main no-padding">
					<table class="table table-bordered table-striped">
						<thead class="thin-border-bottom">
						<tr>
							<th>
								<i class="ace-icon fa fa-caret-right blue"></i>Page
							</th>
							<th>
								<i class="ace-icon fa fa-caret-right blue"></i>View
							</th>

						</tr>
						</thead>

						<tbody>
<?php
$attributes = array('target' => '_blank');
try {
	if( $result_top_view->getRows() ) {
		$listobj = $result_top_view->getRows();
		for($i=0;$i<count($listobj);$i++){
			$pagename = $listobj[$i][0];
			$pageview = number_format($listobj[$i][1]);

			if($pagename == "/"){
				$pagename = "siamdara.com";
				$pagename = anchor($this->config->config['www'], $pagename, $attributes);
			}else
				$pagename = anchor($this->config->config['www'].$pagename, $pagename, $attributes);

			echo '<tr>
					<td>'.$pagename.'</td>
					<td>
						<b class="green">'.$pageview.'</b>
					</td>
				</tr>';
		}
	}
} catch(Exception $e) {
	echo 'There was an error : - ' . $e->getMessage();
}
?>
						</tbody>
					</table>
				</div><!-- /.widget-main -->
			</div><!-- /.widget-body -->
		</div><!-- /.widget-box -->
	</div><!-- /.col -->

</div>