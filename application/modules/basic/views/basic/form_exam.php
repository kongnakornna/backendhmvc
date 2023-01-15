<?php $language = $this->lang->language; ?>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<!-- <button class="btn btn-sm btn-primary" onclick="window.location='<?php echo site_url('category/add') ?>';">
										<i class="ace-icon glyphicon glyphicon-plus align-top bigger-125"></i><?php echo $language['add_category'] ?>
								</button> -->

								<div class="row">

									<div class="page-header">
										<h1>
											Form Exam
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												Common form elements and layouts
											</small>
										</h1>
									</div>


									<div class="col-xs-12">
										<!-- <h3 class="header smaller lighter blue"><?php echo $language['category'] ?></h3> -->
										<!-- <div class="table-header">
											<?php echo $language['category'] ?>
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
			/*if($error){
?>
											<div class="alert alert-danger">
												<button data-dismiss="alert" class="close" type="button">
													<i class="ace-icon fa fa-times"></i>
												</button>

												<strong>
													<i class="ace-icon fa fa-times"></i>
													Oh snap!
												</strong>
												<?php echo $error?>.
												<br>
											</div>
<?php
			}*/
?>

<form role="form" class="form-horizontal">
									<!-- #section:elements.form -->
									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> Text Field </label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="Username" id="form-field-1">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"> Full Length </label>

										<div class="col-sm-9">
											<input type="text" class="form-control" placeholder="Text Field" id="form-field-1-1">
										</div>
									</div>

									<!-- /section:elements.form -->
									<div class="space-4"></div>

									<div class="form-group">
										<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Password Field </label>

										<div class="col-sm-9">
											<input type="password" class="col-xs-10 col-sm-5" placeholder="Password" id="form-field-2">
											<span class="help-inline col-xs-12 col-sm-7">
												<span class="middle">Inline help text</span>
											</span>
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label for="form-input-readonly" class="col-sm-3 control-label no-padding-right"> Readonly field </label>

										<div class="col-sm-9">
											<input type="text" value="This text field is readonly!" id="form-input-readonly" class="col-xs-10 col-sm-5" readonly="">
											<span class="help-inline col-xs-12 col-sm-7">
												<label class="middle">
													<input type="checkbox" id="id-disable-check" class="ace">
													<span class="lbl"> Disable it!</span>
												</label>
											</span>
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label for="form-field-4" class="col-sm-3 control-label no-padding-right">Relative Sizing</label>

										<div class="col-sm-9">
											<input type="text" placeholder=".input-sm" id="form-field-4" class="input-sm">
											<div class="space-2"></div>

											<div id="input-size-slider" class="help-block ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="width: 200px;"><div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 0%;"></div><span tabindex="0" class="ui-slider-handle ui-state-default ui-corner-all" style="left: 0%;"></span></div>
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-5" class="col-sm-3 control-label no-padding-right">Grid Sizing</label>

										<div class="col-sm-9">
											<div class="clearfix">
												<input type="text" placeholder=".col-xs-1" id="form-field-5" class="col-xs-1">
											</div>

											<div class="space-2"></div>

											<div id="input-span-slider" class="help-block ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 0%;"></div><span tabindex="0" class="ui-slider-handle ui-state-default ui-corner-all" style="left: 0%;"></span></div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right">Input with Icon</label>

										<div class="col-sm-9">
											<!-- #section:elements.form.input-icon -->
											<span class="input-icon">
												<input type="text" id="form-field-icon-1">
												<i class="ace-icon fa fa-leaf blue"></i>
											</span>

											<span class="input-icon input-icon-right">
												<input type="text" id="form-field-icon-2">
												<i class="ace-icon fa fa-leaf green"></i>
											</span>

											<!-- /section:elements.form.input-icon -->
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label for="form-field-6" class="col-sm-3 control-label no-padding-right">Tooltip and help button</label>

										<div class="col-sm-9">
											<input type="text" data-placement="bottom" title="" placeholder="Tooltip on hover" id="form-field-6" data-rel="tooltip" data-original-title="Hello Tooltip!">
											<span title="" data-content="More details." data-placement="left" data-trigger="hover" data-rel="popover" class="help-button" data-original-title="Popover on hover">?</span>
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-tags">Tag input</label>

										<div class="col-sm-9">
											<!-- #section:plugins/input.tag-input -->
											<div class="inline">
												<input type="text" name="tags" id="form-field-tags" value="Tag Input Control" placeholder="Enter tags ..." />
											</div>

											<!-- /section:plugins/input.tag-input -->
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-tags">Typeahead.js</label>

										<div class="col-sm-9">
											<!-- #section:plugins/bootstrap.typeahead-js -->
											<div class="pos-rel">
												<input class="typeahead scrollable" type="text" placeholder="States of USA" />
											</div>

											<!-- /section:plugins/bootstrap.typeahead-js -->
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button type="button" class="btn btn-info">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>

											&nbsp; &nbsp; &nbsp;
											<button type="reset" class="btn">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div>

									<div class="hr hr-24"></div>

									<div class="row">
										<div class="col-xs-12 col-sm-4">
											<div class="widget-box">
												<div class="widget-header">
													<h4 class="widget-title">Text Area</h4>

													<div class="widget-toolbar">
														<a href="#" data-action="collapse">
															<i class="ace-icon fa fa-chevron-up"></i>
														</a>

														<a href="#" data-action="close">
															<i class="ace-icon fa fa-times"></i>
														</a>
													</div>
												</div>

												<div class="widget-body">
													<div class="widget-main">
														<div>
															<label for="form-field-8">Default</label>

															<textarea class="form-control" id="form-field-8" placeholder="Default Text"></textarea>
														</div>

														<hr />

														<!-- #section:plugins/input.limiter -->
														<div>
															<label for="form-field-9">With Character Limit</label>

															<textarea class="form-control limited" id="form-field-9" maxlength="50"></textarea>
														</div>

														<!-- /section:plugins/input.limiter -->
														<hr />

														<!-- #section:plugins/input.autosize -->
														<div>
															<label for="form-field-11">Autosize</label>

															<textarea id="form-field-11" class="autosize-transition form-control"></textarea>
														</div>

														<!-- /section:plugins/input.autosize -->
													</div>
												</div>
											</div>
										</div><!-- /.span -->

										<div class="col-xs-12 col-sm-4">
											<div class="widget-box">
												<div class="widget-header">
													<h4 class="widget-title">Masked Input</h4>

													<span class="widget-toolbar">
														<a href="#" data-action="settings">
															<i class="ace-icon fa fa-cog"></i>
														</a>

														<a href="#" data-action="reload">
															<i class="ace-icon fa fa-refresh"></i>
														</a>

														<a href="#" data-action="collapse">
															<i class="ace-icon fa fa-chevron-up"></i>
														</a>

														<a href="#" data-action="close">
															<i class="ace-icon fa fa-times"></i>
														</a>
													</span>
												</div>

												<div class="widget-body">
													<div class="widget-main">
														<div>
															<label for="form-field-mask-1">
																Date
																<small class="text-success">99/99/9999</small>
															</label>

															<!-- #section:plugins/input.masked-input -->
															<div class="input-group">
																<input class="form-control input-mask-date" type="text" id="form-field-mask-1" />
																<span class="input-group-btn">
																	<button class="btn btn-sm btn-default" type="button">
																		<i class="ace-icon fa fa-calendar bigger-110"></i>
																		Go!
																	</button>
																</span>
															</div>

															<!-- /section:plugins/input.masked-input -->
														</div>

														<hr />
														<div>
															<label for="form-field-mask-2">
																Phone
																<small class="text-warning">(999) 999-9999</small>
															</label>

															<div class="input-group">
																<span class="input-group-addon">
																	<i class="ace-icon fa fa-phone"></i>
																</span>

																<input class="form-control input-mask-phone" type="text" id="form-field-mask-2" />
															</div>
														</div>

														<hr />
														<div>
															<label for="form-field-mask-3">
																Product Key
																<small class="text-error">a*-999-a999</small>
															</label>

															<div class="input-group">
																<input class="form-control input-mask-product" type="text" id="form-field-mask-3" />
																<span class="input-group-addon">
																	<i class="ace-icon fa fa-key"></i>
																</span>
															</div>
														</div>

														<hr />
														<div>
															<label for="form-field-mask-4">
																Eye Script
																<small class="text-info">~9.99 ~9.99 999</small>
															</label>

															<div>
																<input class="input-medium input-mask-eyescript" type="text" id="form-field-mask-4" />
															</div>
														</div>
													</div>
												</div>
											</div>
										</div><!-- /.span -->

										<div class="col-xs-12 col-sm-4">
											<div class="widget-box">
												<div class="widget-header">
													<h4 class="widget-title">Select Box</h4>

													<span class="widget-toolbar">
														<a href="#" data-action="settings">
															<i class="ace-icon fa fa-cog"></i>
														</a>

														<a href="#" data-action="reload">
															<i class="ace-icon fa fa-refresh"></i>
														</a>

														<a href="#" data-action="collapse">
															<i class="ace-icon fa fa-chevron-up"></i>
														</a>

														<a href="#" data-action="close">
															<i class="ace-icon fa fa-times"></i>
														</a>
													</span>
												</div>

												<div class="widget-body">
													<div class="widget-main">
														<div>
															<label for="form-field-select-1">Default</label>

															<select class="form-control" id="form-field-select-1">
																<option value=""></option>
																<option value="AL">Alabama</option>
																<option value="AK">Alaska</option>
																<option value="AZ">Arizona</option>
																<option value="AR">Arkansas</option>
																<option value="CA">California</option>
																<option value="CO">Colorado</option>
																<option value="CT">Connecticut</option>
																<option value="DE">Delaware</option>
																<option value="FL">Florida</option>
																<option value="GA">Georgia</option>
																<option value="HI">Hawaii</option>
																<option value="ID">Idaho</option>
																<option value="IL">Illinois</option>
																<option value="IN">Indiana</option>
																<option value="IA">Iowa</option>
																<option value="KS">Kansas</option>
																<option value="KY">Kentucky</option>
																<option value="LA">Louisiana</option>
																<option value="ME">Maine</option>
																<option value="MD">Maryland</option>
																<option value="MA">Massachusetts</option>
																<option value="MI">Michigan</option>
																<option value="MN">Minnesota</option>
																<option value="MS">Mississippi</option>
																<option value="MO">Missouri</option>
																<option value="MT">Montana</option>
																<option value="NE">Nebraska</option>
																<option value="NV">Nevada</option>
																<option value="NH">New Hampshire</option>
																<option value="NJ">New Jersey</option>
																<option value="NM">New Mexico</option>
																<option value="NY">New York</option>
																<option value="NC">North Carolina</option>
																<option value="ND">North Dakota</option>
																<option value="OH">Ohio</option>
																<option value="OK">Oklahoma</option>
																<option value="OR">Oregon</option>
																<option value="PA">Pennsylvania</option>
																<option value="RI">Rhode Island</option>
																<option value="SC">South Carolina</option>
																<option value="SD">South Dakota</option>
																<option value="TN">Tennessee</option>
																<option value="TX">Texas</option>
																<option value="UT">Utah</option>
																<option value="VT">Vermont</option>
																<option value="VA">Virginia</option>
																<option value="WA">Washington</option>
																<option value="WV">West Virginia</option>
																<option value="WI">Wisconsin</option>
																<option value="WY">Wyoming</option>
															</select>
														</div>

														<hr />
														<div>
															<label for="form-field-select-2">Multiple</label>

															<select class="form-control" id="form-field-select-2" multiple="multiple">
																<option value="AL">Alabama</option>
																<option value="AK">Alaska</option>
																<option value="AZ">Arizona</option>
																<option value="AR">Arkansas</option>
																<option value="CA">California</option>
																<option value="CO">Colorado</option>
																<option value="CT">Connecticut</option>
																<option value="DE">Delaware</option>
																<option value="FL">Florida</option>
																<option value="GA">Georgia</option>
																<option value="HI">Hawaii</option>
																<option value="ID">Idaho</option>
																<option value="IL">Illinois</option>
																<option value="IN">Indiana</option>
																<option value="IA">Iowa</option>
																<option value="KS">Kansas</option>
																<option value="KY">Kentucky</option>
																<option value="LA">Louisiana</option>
																<option value="ME">Maine</option>
																<option value="MD">Maryland</option>
																<option value="MA">Massachusetts</option>
																<option value="MI">Michigan</option>
																<option value="MN">Minnesota</option>
																<option value="MS">Mississippi</option>
																<option value="MO">Missouri</option>
																<option value="MT">Montana</option>
																<option value="NE">Nebraska</option>
																<option value="NV">Nevada</option>
																<option value="NH">New Hampshire</option>
																<option value="NJ">New Jersey</option>
																<option value="NM">New Mexico</option>
																<option value="NY">New York</option>
																<option value="NC">North Carolina</option>
																<option value="ND">North Dakota</option>
																<option value="OH">Ohio</option>
																<option value="OK">Oklahoma</option>
																<option value="OR">Oregon</option>
																<option value="PA">Pennsylvania</option>
																<option value="RI">Rhode Island</option>
																<option value="SC">South Carolina</option>
																<option value="SD">South Dakota</option>
																<option value="TN">Tennessee</option>
																<option value="TX">Texas</option>
																<option value="UT">Utah</option>
																<option value="VT">Vermont</option>
																<option value="VA">Virginia</option>
																<option value="WA">Washington</option>
																<option value="WV">West Virginia</option>
																<option value="WI">Wisconsin</option>
																<option value="WY">Wyoming</option>
															</select>
														</div>

														<hr />

														<!-- #section:plugins/input.chosen -->
														<div>
															<label for="form-field-select-3">Chosen</label>

															<br />
															<select class="chosen-select" id="form-field-select-3" data-placeholder="Choose a State...">
																<option value="">  </option>
																<option value="AL">Alabama</option>
																<option value="AK">Alaska</option>
																<option value="AZ">Arizona</option>
																<option value="AR">Arkansas</option>
																<option value="CA">California</option>
																<option value="CO">Colorado</option>
																<option value="CT">Connecticut</option>
																<option value="DE">Delaware</option>
																<option value="FL">Florida</option>
																<option value="GA">Georgia</option>
																<option value="HI">Hawaii</option>
																<option value="ID">Idaho</option>
																<option value="IL">Illinois</option>
																<option value="IN">Indiana</option>
																<option value="IA">Iowa</option>
																<option value="KS">Kansas</option>
																<option value="KY">Kentucky</option>
																<option value="LA">Louisiana</option>
																<option value="ME">Maine</option>
																<option value="MD">Maryland</option>
																<option value="MA">Massachusetts</option>
																<option value="MI">Michigan</option>
																<option value="MN">Minnesota</option>
																<option value="MS">Mississippi</option>
																<option value="MO">Missouri</option>
																<option value="MT">Montana</option>
																<option value="NE">Nebraska</option>
																<option value="NV">Nevada</option>
																<option value="NH">New Hampshire</option>
																<option value="NJ">New Jersey</option>
																<option value="NM">New Mexico</option>
																<option value="NY">New York</option>
																<option value="NC">North Carolina</option>
																<option value="ND">North Dakota</option>
																<option value="OH">Ohio</option>
																<option value="OK">Oklahoma</option>
																<option value="OR">Oregon</option>
																<option value="PA">Pennsylvania</option>
																<option value="RI">Rhode Island</option>
																<option value="SC">South Carolina</option>
																<option value="SD">South Dakota</option>
																<option value="TN">Tennessee</option>
																<option value="TX">Texas</option>
																<option value="UT">Utah</option>
																<option value="VT">Vermont</option>
																<option value="VA">Virginia</option>
																<option value="WA">Washington</option>
																<option value="WV">West Virginia</option>
																<option value="WI">Wisconsin</option>
																<option value="WY">Wyoming</option>
															</select>
														</div>

														<hr />
														<div>
															<div class="row">
																<div class="col-sm-6">
																	<span class="bigger-110">Multiple</span>
																</div><!-- /.span -->

																<div class="col-sm-6">
																	<span class="pull-right inline">
																		<span class="grey">style:</span>

																		<span class="btn-toolbar inline middle no-margin">
																			<span id="chosen-multiple-style" data-toggle="buttons" class="btn-group no-margin">
																				<label class="btn btn-xs btn-yellow active">
																					1
																					<input type="radio" value="1" />
																				</label>

																				<label class="btn btn-xs btn-yellow">
																					2
																					<input type="radio" value="2" />
																				</label>
																			</span>
																		</span>
																	</span>
																</div><!-- /.span -->
															</div>

															<div class="space-2"></div>

															<select multiple="" class="chosen-select" id="form-field-select-4" data-placeholder="Choose a State...">
																<option value="AL">Alabama</option>
																<option value="AK">Alaska</option>
																<option value="AZ">Arizona</option>
																<option value="AR">Arkansas</option>
																<option value="CA">California</option>
																<option value="CO">Colorado</option>
																<option value="CT">Connecticut</option>
																<option value="DE">Delaware</option>
																<option value="FL">Florida</option>
																<option value="GA">Georgia</option>
																<option value="HI">Hawaii</option>
																<option value="ID">Idaho</option>
																<option value="IL">Illinois</option>
																<option value="IN">Indiana</option>
																<option value="IA">Iowa</option>
																<option value="KS">Kansas</option>
																<option value="KY">Kentucky</option>
																<option value="LA">Louisiana</option>
																<option value="ME">Maine</option>
																<option value="MD">Maryland</option>
																<option value="MA">Massachusetts</option>
																<option value="MI">Michigan</option>
																<option value="MN">Minnesota</option>
																<option value="MS">Mississippi</option>
																<option value="MO">Missouri</option>
																<option value="MT">Montana</option>
																<option value="NE">Nebraska</option>
																<option value="NV">Nevada</option>
																<option value="NH">New Hampshire</option>
																<option value="NJ">New Jersey</option>
																<option value="NM">New Mexico</option>
																<option value="NY">New York</option>
																<option value="NC">North Carolina</option>
																<option value="ND">North Dakota</option>
																<option value="OH">Ohio</option>
																<option value="OK">Oklahoma</option>
																<option value="OR">Oregon</option>
																<option value="PA">Pennsylvania</option>
																<option value="RI">Rhode Island</option>
																<option value="SC">South Carolina</option>
																<option value="SD">South Dakota</option>
																<option value="TN">Tennessee</option>
																<option value="TX">Texas</option>
																<option value="UT">Utah</option>
																<option value="VT">Vermont</option>
																<option value="VA">Virginia</option>
																<option value="WA">Washington</option>
																<option value="WV">West Virginia</option>
																<option value="WI">Wisconsin</option>
																<option value="WY">Wyoming</option>
															</select>
														</div>

														<!-- /section:plugins/input.chosen -->
													</div>
												</div>
											</div>
										</div><!-- /.span -->
									</div><!-- /.row -->

									<div class="space-24"></div>

									<h3 class="header smaller lighter blue">
										Checkboxes & Radio
										<small>All Checkboxes, Radios and Switch Buttons Are Pure CSS</small>
									</h3>

									<div class="row">
										<div class="col-xs-12 col-sm-5">
											<div class="control-group">
												<label class="control-label bolder blue">Checkbox</label>

												<!-- #section:custom/checkbox -->
												<div class="checkbox">
													<label>
														<input name="form-field-checkbox" type="checkbox" class="ace" />
														<span class="lbl"> choice 1</span>
													</label>
												</div>

												<div class="checkbox">
													<label>
														<input name="form-field-checkbox" type="checkbox" class="ace" />
														<span class="lbl"> choice 2</span>
													</label>
												</div>

												<div class="checkbox">
													<label>
														<input name="form-field-checkbox" class="ace ace-checkbox-2" type="checkbox" />
														<span class="lbl"> choice 3</span>
													</label>
												</div>

												<div class="checkbox">
													<label class="block">
														<input name="form-field-checkbox" disabled="" type="checkbox" class="ace" />
														<span class="lbl"> disabled</span>
													</label>
												</div>

												<!-- /section:custom/checkbox -->
											</div>
										</div>

										<div class="col-xs-12 col-sm-6">
											<div class="control-group">
												<label class="control-label bolder blue">Radio</label>

												<div class="radio">
													<label>
														<input name="form-field-radio" type="radio" class="ace" />
														<span class="lbl"> radio option 1</span>
													</label>
												</div>

												<div class="radio">
													<label>
														<input name="form-field-radio" type="radio" class="ace" />
														<span class="lbl"> radio option 2</span>
													</label>
												</div>

												<div class="radio">
													<label>
														<input name="form-field-radio" type="radio" class="ace" />
														<span class="lbl"> radio option 3</span>
													</label>
												</div>

												<div class="radio">
													<label>
														<input disabled="" name="form-field-radio" type="radio" class="ace" />
														<span class="lbl"> disabled</span>
													</label>
												</div>
											</div>
										</div>
									</div><!-- /.row -->

									<hr />
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3">On/Off Switches</label>

										<div class="controls col-xs-12 col-sm-9">
											<!-- #section:custom/checkbox.switch -->
											<div class="row">
												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>

												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-2" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>

												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-3" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>

												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch" type="checkbox" />
														<span class="lbl" data-lbl="CUS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOM"></span>
													</label>
												</div>
											</div>

											<div class="row">
												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-4" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>

												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-5" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>

												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>

												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-7" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>
											</div>

											<div class="row">
												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch btn-rotate" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>

												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-4 btn-rotate" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>

												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-4 btn-empty" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>

												<div class="col-xs-3">
													<label>
														<input name="switch-field-1" class="ace ace-switch ace-switch-4 btn-flat" type="checkbox" />
														<span class="lbl"></span>
													</label>
												</div>
											</div>

											<!-- /section:custom/checkbox.switch -->
										</div>
									</div>

									<hr />
									<div class="row">
										<div class="col-sm-4">
											<div class="widget-box">
												<div class="widget-header">
													<h4 class="widget-title">Custom File Input</h4>

													<div class="widget-toolbar">
														<a href="#" data-action="collapse">
															<i class="ace-icon fa fa-chevron-up"></i>
														</a>

														<a href="#" data-action="close">
															<i class="ace-icon fa fa-times"></i>
														</a>
													</div>
												</div>

												<div class="widget-body">
													<div class="widget-main">
														<div class="form-group">
															<div class="col-xs-12">
																<!-- #section:custom/file-input -->
																<input type="file" id="id-input-file-2" />
															</div>
														</div>

														<div class="form-group">
															<div class="col-xs-12">
																<input multiple="" type="file" id="id-input-file-3" />

																<!-- /section:custom/file-input -->
															</div>
														</div>

														<!-- #section:custom/file-input.filter -->
														<label>
															<input type="checkbox" name="file-format" id="id-file-format" class="ace" />
															<span class="lbl"> Allow only images</span>
														</label>

														<!-- /section:custom/file-input.filter -->
													</div>
												</div>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="widget-box">
												<div class="widget-header">
													<h4 class="widget-title">jQuery UI Sliders</h4>
												</div>

												<div class="widget-body">
													<div class="widget-main">
														<div class="row">
															<div class="col-xs-3 col-md-2">
																<!-- #section:plugins/jquery.slider -->
																<div id="slider-range"></div>
															</div>

															<div class="col-xs-9 col-md-10">
																<div id="slider-eq">
																	<span class="ui-slider-green ui-slider-small">77</span>
																	<span class="ui-slider-red">55</span>
																	<span class="ui-slider-purple" data-rel="tooltip" title="Disabled!">33</span>
																	<span class="ui-slider-simple ui-slider-orange">40</span>
																	<span class="ui-slider-simple ui-slider-dark">88</span>
																</div>

																<!-- /section:plugins/jquery.slider -->
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="widget-box">
												<div class="widget-header">
													<h4 class="widget-title">Spinners</h4>
												</div>

												<div class="widget-body">
													<div class="widget-main">
														<!-- #section:plugins/fuelux.spinner -->
														<input type="text" class="input-mini" id="spinner1" />
														<div class="space-6"></div>

														<input type="text" class="input-mini" id="spinner2" />
														<div class="space-6"></div>

														<input type="text" class="input-mini" id="spinner3" />

														<!-- /section:plugins/fuelux.spinner -->
													</div>
												</div>
											</div>
										</div>
									</div>

									<hr />
									<div class="row">
										<div class="col-sm-4">
											<div class="widget-box">
												<div class="widget-header">
													<h4 class="widget-title">Date Picker</h4>

													<span class="widget-toolbar">
														<a href="#" data-action="settings">
															<i class="ace-icon fa fa-cog"></i>
														</a>

														<a href="#" data-action="reload">
															<i class="ace-icon fa fa-refresh"></i>
														</a>

														<a href="#" data-action="collapse">
															<i class="ace-icon fa fa-chevron-up"></i>
														</a>

														<a href="#" data-action="close">
															<i class="ace-icon fa fa-times"></i>
														</a>
													</span>
												</div>

												<div class="widget-body">
													<div class="widget-main">
														<label for="id-date-picker-1">Date Picker</label>

														<div class="row">
															<div class="col-xs-8 col-sm-11">
																<!-- #section:plugins/date-time.datepicker -->
																<div class="input-group">
																	<input class="form-control date-picker" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
																</div>
															</div>
														</div>

														<div class="space space-8"></div>
														<label>Range Picker</label>

														<div class="row">
															<div class="col-xs-8 col-sm-11">
																<div class="input-daterange input-group">
																	<input type="text" class="input-sm form-control" name="start" />
																	<span class="input-group-addon">
																		<i class="fa fa-exchange"></i>
																	</span>

																	<input type="text" class="input-sm form-control" name="end" />
																</div>

																<!-- /section:plugins/date-time.datepicker -->
															</div>
														</div>

														<hr />
														<label for="id-date-range-picker-1">Date Range Picker</label>

														<div class="row">
															<div class="col-xs-8 col-sm-11">
																<!-- #section:plugins/date-time.daterangepicker -->
																<div class="input-group">
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>

																	<input class="form-control" type="text" name="date-range-picker" id="id-date-range-picker-1" />
																</div>

																<!-- /section:plugins/date-time.daterangepicker -->
															</div>
														</div>

														<hr />
														<label for="timepicker1">Time Picker</label>

														<!-- #section:plugins/date-time.timepicker -->
														<div class="input-group bootstrap-timepicker">
															<input id="timepicker1" type="text" class="form-control" />
															<span class="input-group-addon">
																<i class="fa fa-clock-o bigger-110"></i>
															</span>
														</div>

														<!-- /section:plugins/date-time.timepicker -->
														<hr />
														<label for="date-timepicker1">Date/Time Picker</label>

														<!-- #section:plugins/date-time.datetimepicker -->
														<div class="input-group">
															<input id="date-timepicker1" type="text" class="form-control" />
															<span class="input-group-addon">
																<i class="fa fa-clock-o bigger-110"></i>
															</span>
														</div>

														<!-- /section:plugins/date-time.datetimepicker -->
													</div>
												</div>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="widget-box">
												<div class="widget-header">
													<h4 class="widget-title">
														<i class="ace-icon fa fa-tint"></i>
														Color Picker
													</h4>
												</div>

												<div class="widget-body">
													<div class="widget-main">
														<div class="clearfix">
															<label for="colorpicker1">Color Picker</label>
														</div>

														<div class="control-group">
															<div class="bootstrap-colorpicker">
																<!-- #section:plugins/misc.colorpicker -->
																<input id="colorpicker1" type="text" class="input-small" />

																<!-- /section:plugins/misc.colorpicker -->
															</div>
														</div>

														<hr />

														<!-- #section:custom/colorpicker -->
														<div>
															<label for="simple-colorpicker-1">Custom Color Picker</label>

															<select id="simple-colorpicker-1" class="hide">
																<option value="#ac725e">#ac725e</option>
																<option value="#d06b64">#d06b64</option>
																<option value="#f83a22">#f83a22</option>
																<option value="#fa573c">#fa573c</option>
																<option value="#ff7537">#ff7537</option>
																<option value="#ffad46" selected="">#ffad46</option>
																<option value="#42d692">#42d692</option>
																<option value="#16a765">#16a765</option>
																<option value="#7bd148">#7bd148</option>
																<option value="#b3dc6c">#b3dc6c</option>
																<option value="#fbe983">#fbe983</option>
																<option value="#fad165">#fad165</option>
																<option value="#92e1c0">#92e1c0</option>
																<option value="#9fe1e7">#9fe1e7</option>
																<option value="#9fc6e7">#9fc6e7</option>
																<option value="#4986e7">#4986e7</option>
																<option value="#9a9cff">#9a9cff</option>
																<option value="#b99aff">#b99aff</option>
																<option value="#c2c2c2">#c2c2c2</option>
																<option value="#cabdbf">#cabdbf</option>
																<option value="#cca6ac">#cca6ac</option>
																<option value="#f691b2">#f691b2</option>
																<option value="#cd74e6">#cd74e6</option>
																<option value="#a47ae2">#a47ae2</option>
																<option value="#555">#555</option>
															</select>
														</div>

														<!-- /section:custom/colorpicker -->
													</div>
												</div>
											</div>
										</div>

										<div class="col-sm-4">
											<div class="widget-box">
												<div class="widget-header">
													<h4 class="widget-title">
														<i class="ace-icon fa fa-tachometer"></i>
														Knob Input
													</h4>
												</div>

												<div class="widget-body">
													<div class="widget-main">
														<div class="control-group">
															<div class="row">
																<div class="col-xs-6 center">
																	<!-- #section:plugins/charts.knob -->
																	<div class="knob-container inline">
																		<input type="text" class="input-small knob" value="15" data-min="0" data-max="100" data-step="10" data-width="80" data-height="80" data-thickness=".2" />
																	</div>
																</div>

																<div class="col-xs-6  center">
																	<div class="knob-container inline">
																		<input type="text" class="input-small knob" value="41" data-min="0" data-max="100" data-width="80" data-height="80" data-thickness=".2" data-fgColor="#87B87F" data-displayPrevious="true" data-angleArc="250" data-angleOffset="-125" />
																	</div>

																	<!-- /section:plugins/charts.knob -->
																</div>
															</div>

															<div class="row">
																<div class="col-xs-12 center">
																	<div class="knob-container inline">
																		<input type="text" class="input-small knob" value="1" data-min="0" data-max="10" data-width="150" data-height="150" data-thickness=".2" data-fgColor="#B8877F" data-angleOffset="90" data-cursor="true" />
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>

								<div class="hr hr-18 dotted hr-double"></div>

								<h4 class="pink">
									<i class="ace-icon fa fa-hand-o-right green"></i>
									<a href="#modal-form" role="button" class="blue" data-toggle="modal"> Form Inside a Modal Box </a>
								</h4>

								<div class="hr hr-18 dotted hr-double"></div>
								<h4 class="header green">Form Layouts</h4>

								<div class="row">
									<div class="col-sm-5">
										<div class="widget-box">
											<div class="widget-header">
												<h4 class="widget-title">Default</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main no-padding">
													<form>
														<!-- <legend>Form</legend> -->
														<fieldset>
															<label>Label name</label>

															<input type="text" placeholder="Type something&hellip;" />
															<span class="help-block">Example block-level help text here.</span>

															<label class="pull-right">
																<input type="checkbox" class="ace" />
																<span class="lbl"> check me out</span>
															</label>
														</fieldset>

														<div class="form-actions center">
															<button type="button" class="btn btn-sm btn-success">
																Submit
																<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
															</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-7">
										<div class="widget-box">
											<div class="widget-header">
												<h4 class="widget-title">Inline Forms</h4>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<form class="form-inline">
														<input type="text" class="input-small" placeholder="Username" />
														<input type="password" class="input-small" placeholder="Password" />
														<label class="inline">
															<input type="checkbox" class="ace" />
															<span class="lbl"> remember me</span>
														</label>

														<button type="button" class="btn btn-info btn-sm">
															<i class="ace-icon fa fa-key bigger-110"></i>Login
														</button>
													</form>
												</div>
											</div>
										</div>

										<div class="space-6"></div>

										<div class="widget-box">
											<div class="widget-header widget-header-small">
												<h5 class="widget-title lighter">Search Form</h5>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<form class="form-search">
														<div class="row">
															<div class="col-xs-12 col-sm-8">
																<div class="input-group">
																	<input type="text" class="form-control search-query" placeholder="Type your query" />
																	<span class="input-group-btn">
																		<button type="button" class="btn btn-purple btn-sm">
																			Search
																			<i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
																		</button>
																	</span>
																</div>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div id="modal-form" class="modal" tabindex="-1">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="blue bigger">Please fill the following form fields</h4>
											</div>

											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12 col-sm-5">
														<div class="space"></div>

														<input type="file" />
													</div>

													<div class="col-xs-12 col-sm-7">
														<div class="form-group">
															<label for="form-field-select-3">Location</label>

															<div>
																<select class="chosen-select" data-placeholder="Choose a Country...">
																	<option value="">&nbsp;</option>
																	<option value="AL">Alabama</option>
																	<option value="AK">Alaska</option>
																	<option value="AZ">Arizona</option>
																	<option value="AR">Arkansas</option>
																	<option value="CA">California</option>
																	<option value="CO">Colorado</option>
																	<option value="CT">Connecticut</option>
																	<option value="DE">Delaware</option>
																	<option value="FL">Florida</option>
																	<option value="GA">Georgia</option>
																	<option value="HI">Hawaii</option>
																	<option value="ID">Idaho</option>
																	<option value="IL">Illinois</option>
																	<option value="IN">Indiana</option>
																	<option value="IA">Iowa</option>
																	<option value="KS">Kansas</option>
																	<option value="KY">Kentucky</option>
																	<option value="LA">Louisiana</option>
																	<option value="ME">Maine</option>
																	<option value="MD">Maryland</option>
																	<option value="MA">Massachusetts</option>
																	<option value="MI">Michigan</option>
																	<option value="MN">Minnesota</option>
																	<option value="MS">Mississippi</option>
																	<option value="MO">Missouri</option>
																	<option value="MT">Montana</option>
																	<option value="NE">Nebraska</option>
																	<option value="NV">Nevada</option>
																	<option value="NH">New Hampshire</option>
																	<option value="NJ">New Jersey</option>
																	<option value="NM">New Mexico</option>
																	<option value="NY">New York</option>
																	<option value="NC">North Carolina</option>
																	<option value="ND">North Dakota</option>
																	<option value="OH">Ohio</option>
																	<option value="OK">Oklahoma</option>
																	<option value="OR">Oregon</option>
																	<option value="PA">Pennsylvania</option>
																	<option value="RI">Rhode Island</option>
																	<option value="SC">South Carolina</option>
																	<option value="SD">South Dakota</option>
																	<option value="TN">Tennessee</option>
																	<option value="TX">Texas</option>
																	<option value="UT">Utah</option>
																	<option value="VT">Vermont</option>
																	<option value="VA">Virginia</option>
																	<option value="WA">Washington</option>
																	<option value="WV">West Virginia</option>
																	<option value="WI">Wisconsin</option>
																	<option value="WY">Wyoming</option>
																</select>
															</div>
														</div>

														<div class="space-4"></div>

														<div class="form-group">
															<label for="form-field-username">Username</label>

															<div>
																<input class="input-large" type="text" id="form-field-username" placeholder="Username" value="alexdoe" />
															</div>
														</div>

														<div class="space-4"></div>

														<div class="form-group">
															<label for="form-field-first">Name</label>

															<div>
																<input class="input-medium" type="text" id="form-field-first" placeholder="First Name" value="Alex" />
																<input class="input-medium" type="text" id="form-field-last" placeholder="Last Name" value="Doe" />
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="modal-footer">
												<button class="btn btn-sm" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Cancel
												</button>

												<button class="btn btn-sm btn-primary">
													<i class="ace-icon fa fa-check"></i>
													Save
												</button>
											</div>
										</form>
											

									</div>
								</div>
						<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
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