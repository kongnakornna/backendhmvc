<?php 
		$language = $this->lang->language;
?>
		<div class="row">
					<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->
							<div class="row">
<?php
	$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
	echo form_open_multipart('ads/save', $attributes);
?>
				<div class="page-header">
						<h1>
									<?php echo $language['ads'] ?>
									<small>
										<i class="ace-icon fa fa-angle-double-right"></i>
										<?php echo $language['add'] ?>
									</small>
						</h1>
				</div>
				<div class="col-xs-12">
<?php
			if(function_exists('Debug')){
					//Debug($this->db) ;
					//Debug($news);
					//Debug($news_type);
			}

			if(isset($error)){
?>
							<div class="alert alert-danger">
									<button data-dismiss="alert" class="close" type="button">
											<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
											<i class="ace-icon fa fa-times"></i>
											</strong><?php echo $error?>.
									<br>
							</div>
<?php
			}
?>
			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['category']?></label>
					<div class="col-sm-9">
							<?php echo $category_list?>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['subcategory']?></label>
					<div class="col-sm-9">
							<select class="form-control" id="subcategory_id" name="subcategory_id"></select>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Header</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script Header" id="header" name="header"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Cover</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script Cover" id="cover" name="cover"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Leader board</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script Leader board" id="leader_board_big" name="leader_board_big"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Leader board 2</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script Leader board" id="leader_board_mediem" name="leader_board_mediem"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Leader board 3</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script Leader board" id="leader_board_small" name="leader_board_small"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Skin 1</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="skin1" name="skin1"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Skin 2</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="skin2" name="skin2"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Skin 3</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="skin3" name="skin3"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Ads 1<br>(Rectang 1)</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="ads_1" name="ads_1"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Ads 2<br>(Rectang 2)</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="ads_2" name="ads_2"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Ads 3<br>(Rectang 3)</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="ads_3" name="ads_3"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Ads 4</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="ads_4" name="ads_4"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Ads 5<br>(Rectang 4)</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="ads_5" name="ads_5"></textarea>
					</div>
			</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Footer</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script" id="footer" name="footer"></textarea>
					</div>
			</div>

<!-- Mobile -->
		<div class="page-header">
				<h1>
						<?php echo $language['ads'] ?>
						<small>
							<i class="ace-icon fa fa-angle-double-right"></i>
							Mobile
						</small>
				</h1>
		</div>

			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right">M Cover</label>
					<div class="col-sm-9">
							<textarea class="form-control" placeholder="Script M Cover" id="m_cover" name="m_cover"></textarea>
					</div>
			</div>

			<!-- <div class="form-group">
					<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>
					<div class="col-xs-3">
							<label>
								<input name="status" id="ads_status" class="ace ace-switch" type="checkbox" value=1 />
								<span class="lbl"></span>
							</label>
					</div>
			</div> -->
			<input name="status" type="hidden" value=1 />

			<div style="clear: both;"></div>
			<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn btn-info">
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

		</div>
<?php echo form_close();?>
		</div>
	<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<script type="text/javascript">
$( document ).ready(function() {
		console.log( "ready!" );
		$('#category_id').change(function( ) {
				var catid = $(this).val();
				$('#subcategory_id').load('<?php echo base_url() ?>subcategory/list_dd/' + catid);
		});

		$('.chosen-select').chosen({allow_single_deselect:true}); 
				//resize the chosen on window resize
				$(window)
				.off('resize.chosen')
				.on('resize.chosen', function() {
					$('.chosen-select').each(function() {
						 var $this = $(this);
						 $this.next().css({'width': $this.parent().width()});
					})
		}).trigger('resize.chosen');
			
		$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
					 else $('#form-field-select-4').removeClass('tag-input-style');
		});

		$('#modal-form').on('shown.bs.modal', function () {
					$(this).find('.chosen-container').each(function(){
						$(this).find('a:first-child').css('width' , '210px');
						$(this).find('.chosen-drop').css('width' , '210px');
						$(this).find('.chosen-search input').css('width' , '200px');
					});
		})

});

</script>