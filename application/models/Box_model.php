<?php
class Box_model extends CI_Model {
 
    public function __construct(){
		parent::__construct();
    }

    public function DisplayLog($data){
		$language = $this->lang->language;
?>							
		<div class="col-sm-12">
				<div class="col-sm-4">
										<div class="widget-box">
											<div class="widget-header widget-header-flat">
												<h4 class="widget-title"><?php echo $language['create_date']?></h4>
											</div>
											<div class="widget-body">
												<div class="widget-main">
													<div class="row">
														<div class="col-sm-6">
															<ul>
																<li>
																<?php echo RenDateTime($data['create_date'])?>
																</li>
																<li>
																<?php echo (isset($data['create_by_name'])) ? $data['create_by_name'] : 'System' ?>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
			</div>

			<div class="col-sm-4">

										<div class="widget-box">
											<div class="widget-header widget-header-flat">
												<h4 class="widget-title"><?php echo $language['lastupdate']?></h4>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<div class="row">
														<div class="col-sm-6">
															<ul>
																<li>
																<?php echo RenDateTime($data['lastupdate_date'])?>
																</li>
																<li>
																<?php if(isset($data['lastupdate_by_name'])) echo $data['lastupdate_by_name']?>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
				</div>
<?php
				if(isset($data['approve_date']) && $data['approve_date'] != 0){
?>
					<div class="col-sm-4">

										<div class="widget-box">
											<div class="widget-header widget-header-flat">
												<h4 class="widget-title"><?php echo $language['approve']?></h4>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<div class="row">
														<div class="col-sm-6">
															<ul>
																<li>
																<?php echo RenDateTime($data['approve_date'])?>
																</li>
																<li>
																<?php if(isset($data['approve_by_name'])) echo $data['approve_by_name']?>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
					</div>
<?php
				}
?>
		</div>
<?php
	}

    public function DisplayLogActivity($view_log){
		$language = $this->lang->language;
		$alllogs = count($view_log);
?>							
					<div class="col-sm-12">
							<div style="min-height: 31px;" class="col-xs-12 col-sm-12 widget-container-col ui-sortable">

									<div style="opacity: 1;" class="widget-box widget-color-orange ui-sortable-handle collapsed">
											<!-- #section:custom/widget-box.options.collapsed -->
											<div class="widget-header widget-header-small">
												<h6 class="widget-title">
													<i class="ace-icon fa fa-sort"></i>
													<?php echo $language['admin logs activity'] .' '. $alllogs .' record' ?>
												</h6>

												<div class="widget-toolbar">
													<a href="#" data-action="collapse">
														<i class="ace-icon fa fa-plus" data-icon-show="fa-plus" data-icon-hide="fa-minus"></i>
													</a>
													<a href="#" data-action="close">
														<i class="ace-icon fa fa-times"></i>
													</a>
												</div>
											</div>


											<div style="display: none;" class="widget-body">
												<div class="widget-main">
<?php
			echo '<table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>ID</th>
													<th>'.$language['title'].'</th>
													<th>'.$language['action'].'</th>
													<th>'.$language['admin'].' '.$language['username'].'</th>
													<th>'.$language['create_date'].'</th>
												</tr>
											</thead>
											<tbody>';

				for($i=0;$i<$alllogs;$i++){							
							$admin_log_id = $view_log[$i]->admin_log_id;
							//$ref_type = $view_log[$i]->ref_type;
							$ref_id = $view_log[$i]->ref_id;
							$ref_title = $view_log[$i]->ref_title;
							$action = action_view_logs($view_log[$i]->action);
							$admin_username = $view_log[$i]->admin_username;
							$create_date = RenDateTime($view_log[$i]->create_date);

							echo "<tr>
										<td>".$admin_log_id."</td>
										<td>".$ref_title."</td>
										<td>".$action."</td>
										<td>".$admin_username."</td>
										<td>".$create_date."</td>
							</tr>";				
				}

				echo '</tbody></table>';
?>
												</div>
											</div>

								</div>
						</div>
				</div>
 <?php
	}
}
?>	
