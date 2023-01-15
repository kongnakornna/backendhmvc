<?php $language = $this->lang->language; ?>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<div class="row">
									<!-- <form role="form" class="form-horizontal" action="category/save"> -->
<?php
		$attributes = array('class' => 'form-horizontal', 'id' => 'jform');
		echo form_open('admin_menu/update', $attributes);
?>
									<div class="page-header">
										<h1>
											<a href="<?php echo base_url($this->uri->segment(1)) ?>"><?php echo $language['admin_menu'] ?></a>
											<small>
												<i class="ace-icon fa fa-angle-double-right"></i>
												<?php echo $language['edit'].' '.$web_menu[0]['title'] ?>
											</small>
										</h1>
									</div>

									<div class="col-xs-12">

<?php
			if(function_exists('Debug')){
					//Debug($web_menu);
					//Debug($parent);
			}

			$countitem = count($web_menu);
			for($i=0;$i<$countitem ;$i++){
					if($web_menu[$i]['lang'] == 'th'){

							$title_th = $web_menu[$i]['title'];
							$admin_menu_id_th = $web_menu[$i]['admin_menu_id'];

					}else if($web_menu[$i]['lang'] == 'en'){

							$title_en = $web_menu[$i]['title'];
							$admin_menu_id_en = $web_menu[$i]['admin_menu_id'];

					}
					$admin_menu_id = $web_menu[$i]['admin_menu_id2'];
					$url = $web_menu[0]['url'];
					$status = $web_menu[0]['status'];
					$icon= $web_menu[0]['icon'];
					$parentid= $web_menu[0]['parent'];
			}

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


									<!-- #section:elements.form -->
									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (EN)</label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="title" id="title_en" name="title_en" value="<?php echo $title_en ?>">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['title']?> (TH)</label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="title" id="title_th" name="title_th" value="<?php echo $title_th?>">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">URL</label>

									  <div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="url" id="url" name="url" value="<?php echo $url?>">
										</div>
									</div>

									<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right">URL</label>

										<div class="col-sm-9">
										<select name="parentid" id="parentid">
											<option value=0>root</option>
										<?php
												//Debug($parent);
												if($parent){
														for($i=0;$i<count($parent);$i++){

																if($parent[$i]['admin_menu_id2'] == $web_menu[0]['parent'] || $parent[$i]['admin_menu_id2'] == $web_menu[1]['parent'])
																		echo "<option value=".$parent[$i]['admin_menu_id2']." selected>".$parent[$i]['title']."</option>";
																else
																		echo "<option value=".$parent[$i]['admin_menu_id2'].">".$parent[$i]['title']."</option>";
														}
												}
										?>
										</select>
										</div>
									</div>

<?php if($parentid!==''){
if($icon==Null){$icon='fa-android';}
 

?>
<div class="form-group">
										<label for="form-field-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['icon']?>  </label>

										<div class="col-sm-9">
											<input type="text" class="col-xs-10 col-sm-5" placeholder="Icon" id="icon" name="icon" value="<?php echo $icon; ?>">
										</div>
									</div>
<?php }else{?><input type="hidden"  id="icon" name="icon" value="<?php echo $icon ?>"><?php }?>

									<div class="form-group">
										<label for="form-field-1-1" class="col-sm-3 control-label no-padding-right"><?php echo $language['status']?></label>

										<div class="col-xs-3">
													<label>
														<!-- <input name="switch-field-1" class="ace ace-switch" type="checkbox"  name="status" <?php if($cat_arr[0]['status'] == 1) echo 'value=1 checked'?>/> -->
														<input type="checkbox" name="status" id="cat_status" class="ace ace-switch ace-switch-5" <?php if($status == 1) echo 'value=1 checked'; else echo 'value=0' ?>>

														<span class="lbl"></span>
													</label>
												</div>
										</div>
									</div>

								<input type="hidden" name="admin_menu_id" value="<?php echo $admin_menu_id;?>">
								<input type="hidden" name="admin_menu_id_en" value="<?php echo $admin_menu_id_en ?>">
								<input type="hidden" name="admin_menu_id_th" value="<?php echo $admin_menu_id_th ?>">

								<input type="hidden" name="parent" value="<?php echo $web_menu[0]['parent'];?>">

									<div style="clear: both;"></div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn btn-info">
												<i class="ace-icon fa fa-check bigger-110"></i>
												 <?php echo $language['save'] ?> 
											</button>

											&nbsp; &nbsp; &nbsp;
											<button type="reset" class="btn">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												<?php echo $language['reset'] ?> 
											</button>
										</div>
									</div>
							</div>
						<?php echo form_close();?>
						</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /.col -->
		</div><!-- /.row -->



Web Application Icons
<hr>
    &nbsp; fa-adjust
    &nbsp; fa-anchor
    &nbsp; fa-archive
    &nbsp; fa-asterisk
    &nbsp; fa-ban
    &nbsp; fa-bar-chart-o
    &nbsp; fa-barcode
    &nbsp; fa-beer
    &nbsp; fa-bell
    &nbsp; fa-bell-o
    &nbsp; fa-bolt
    &nbsp; fa-book
    &nbsp; fa-bookmark
    &nbsp; fa-bookmark-o
    &nbsp; fa-briefcase
    &nbsp; fa-bug
    &nbsp; fa-building
    &nbsp; fa-bullhorn
    &nbsp; fa-bullseye
    &nbsp; fa-calendar
    &nbsp; fa-calendar-o
    &nbsp; fa-camera
    &nbsp; fa-camera-retro
    &nbsp; fa-caret-square-o-down
    &nbsp; fa-caret-square-o-left
    &nbsp; fa-caret-square-o-right
    &nbsp; fa-caret-square-o-up
    &nbsp; fa-certificate
    &nbsp; fa-check
    &nbsp; fa-check-circle
    &nbsp; fa-check-circle-o
    &nbsp; fa-check-square
    &nbsp; fa-check-square-o
    &nbsp; fa-circle
    &nbsp; fa-circle-o
    &nbsp; fa-clock-o
    &nbsp; fa-cloud
    &nbsp; fa-cloud-download
    &nbsp; fa-cloud-upload
    &nbsp; fa-code
    &nbsp; fa-code-fork
    &nbsp; fa-coffee
    &nbsp; fa-cog
    &nbsp; fa-cogs
    &nbsp; fa-collapse-o
    &nbsp; fa-comment
    &nbsp; fa-comment-o
    &nbsp; fa-comments
    &nbsp; fa-comments-o
    &nbsp; fa-compass
    &nbsp; fa-credit-card
    &nbsp; fa-crop
    &nbsp; fa-crosshairs
    &nbsp; fa-cutlery
    &nbsp; fa-dashboard (alias)
    &nbsp; fa-desktop
    &nbsp; fa-dot-circle-o
    &nbsp; fa-download
    &nbsp; fa-edit (alias)
    &nbsp; fa-ellipsis-horizontal

    &nbsp; fa-ellipsis-vertical
    &nbsp; fa-envelope
    &nbsp; fa-envelope-o
    &nbsp; fa-eraser
    &nbsp; fa-exchange
    &nbsp; fa-exclamation
    &nbsp; fa-exclamation-circle
    &nbsp; fa-exclamation-triangle
    &nbsp; fa-expand-o
    &nbsp; fa-external-link
    &nbsp; fa-external-link-square
    &nbsp; fa-eye
    &nbsp; fa-eye-slash
    &nbsp; fa-female
    &nbsp; fa-fighter-jet
    &nbsp; fa-film
    &nbsp; fa-filter
    &nbsp; fa-fire
    &nbsp; fa-fire-extinguisher
    &nbsp; fa-flag
    &nbsp; fa-flag-checkered
    &nbsp; fa-flag-o
    &nbsp; fa-flash (alias)
    &nbsp; fa-flask
    &nbsp; fa-folder
    &nbsp; fa-folder-o
    &nbsp; fa-folder-open
    &nbsp; fa-folder-open-o
    &nbsp; fa-frown-o
    &nbsp; fa-gamepad
    &nbsp; fa-gavel
    &nbsp; fa-gear (alias)
    &nbsp; fa-gears (alias)
    &nbsp; fa-gift
    &nbsp; fa-glass
    &nbsp; fa-globe
    &nbsp; fa-group
    &nbsp; fa-hdd
    &nbsp; fa-headphones
    &nbsp; fa-heart
    &nbsp; fa-heart-o
    &nbsp; fa-home
    &nbsp; fa-inbox
    &nbsp; fa-info
    &nbsp; fa-info-circle
    &nbsp; fa-key
    &nbsp; fa-keyboard-o
    &nbsp; fa-laptop
    &nbsp; fa-leaf
    &nbsp; fa-legal (alias)
    &nbsp; fa-lemon-o
    &nbsp; fa-level-down
    &nbsp; fa-level-up
    &nbsp; fa-lightbulb-o
    &nbsp; fa-location-arrow
    &nbsp; fa-lock
    &nbsp; fa-magic
    &nbsp; fa-magnet
    &nbsp; fa-mail-forward (alias)
    &nbsp; fa-mail-reply (alias)

    &nbsp; fa-mail-reply-all
    &nbsp; fa-male
    &nbsp; fa-map-marker
    &nbsp; fa-meh-o
    &nbsp; fa-microphone
    &nbsp; fa-microphone-slash
    &nbsp; fa-minus
    &nbsp; fa-minus-circle
    &nbsp; fa-minus-square
    &nbsp; fa-minus-square-o
    &nbsp; fa-mobile
    &nbsp; fa-mobile-phone (alias)
    &nbsp; fa-money
    &nbsp; fa-moon-o
    &nbsp; fa-move
    &nbsp; fa-music
    &nbsp; fa-pencil
    &nbsp; fa-pencil-square
    &nbsp; fa-pencil-square-o
    &nbsp; fa-phone
    &nbsp; fa-phone-square
    &nbsp; fa-picture-o
    &nbsp; fa-plane
    &nbsp; fa-plus
    &nbsp; fa-plus-circle
    &nbsp; fa-plus-square
    &nbsp; fa-power-off
    &nbsp; fa-print
    &nbsp; fa-puzzle-piece
    &nbsp; fa-qrcode
    &nbsp; fa-question
    &nbsp; fa-question-circle
    &nbsp; fa-quote-left
    &nbsp; fa-quote-right
    &nbsp; fa-random
    &nbsp; fa-refresh
    &nbsp; fa-reorder
    &nbsp; fa-reply
    &nbsp; fa-reply-all
    &nbsp; fa-resize-horizontal
    &nbsp; fa-resize-vertical
    &nbsp; fa-retweet
    &nbsp; fa-road
    &nbsp; fa-rocket
    &nbsp; fa-rss
    &nbsp; fa-rss-square
    &nbsp; fa-search
    &nbsp; fa-search-minus
    &nbsp; fa-search-plus
    &nbsp; fa-share
    &nbsp; fa-share-square
    &nbsp; fa-share-square-o
    &nbsp; fa-shield
    &nbsp; fa-shopping-cart
    &nbsp; fa-sign-in
    &nbsp; fa-sign-out
    &nbsp; fa-signal
    &nbsp; fa-sitemap
    &nbsp; fa-smile-o
    &nbsp; fa-sort

    &nbsp; fa-sort-alpha-asc
    &nbsp; fa-sort-alpha-desc
    &nbsp; fa-sort-amount-asc
    &nbsp; fa-sort-amount-desc
    &nbsp; fa-sort-asc
    &nbsp; fa-sort-desc
    &nbsp; fa-sort-down (alias)
    &nbsp; fa-sort-numeric-asc
    &nbsp; fa-sort-numeric-desc
    &nbsp; fa-sort-up (alias)
    &nbsp; fa-spinner
    &nbsp; fa-square
    &nbsp; fa-square-o
    &nbsp; fa-star
    &nbsp; fa-star-half
    &nbsp; fa-star-half-empty (alias)
    &nbsp; fa-star-half-full (alias)
    &nbsp; fa-star-half-o
    &nbsp; fa-star-o
    &nbsp; fa-subscript
    &nbsp; fa-suitcase
    &nbsp; fa-sun-o
    &nbsp; fa-superscript
    &nbsp; fa-tablet
    &nbsp; fa-tachometer
    &nbsp; fa-tag
    &nbsp; fa-tags
    &nbsp; fa-tasks
    &nbsp; fa-terminal
    &nbsp; fa-thumb-tack
    &nbsp; fa-thumbs-down
    &nbsp; fa-thumbs-o-down
    &nbsp; fa-thumbs-o-up
    &nbsp; fa-thumbs-up
    &nbsp; fa-ticket
    &nbsp; fa-times
    &nbsp; fa-times-circle
    &nbsp; fa-times-circle-o
    &nbsp; fa-tint
    &nbsp; fa-toggle-down (alias)
    &nbsp; fa-toggle-left (alias)
    &nbsp; fa-toggle-right (alias)
    &nbsp; fa-toggle-up (alias)
    &nbsp; fa-trash-o
    &nbsp; fa-trophy
    &nbsp; fa-truck
    &nbsp; fa-umbrella
    &nbsp; fa-unlock
    &nbsp; fa-unlock-o
    &nbsp; fa-unsorted (alias)
    &nbsp; fa-upload
    &nbsp; fa-user
    &nbsp; fa-video-camera
    &nbsp; fa-volume-down
    &nbsp; fa-volume-off
    &nbsp; fa-volume-up
    &nbsp; fa-warning (alias)
    &nbsp; fa-wheelchair
    &nbsp; fa-wrench

Form Control Icons
<hr>
    &nbsp; fa-check-square
    &nbsp; fa-check-square-o
    &nbsp; fa-circle

    &nbsp; fa-circle-o
    &nbsp; fa-dot-circle-o

    &nbsp; fa-minus-square
    &nbsp; fa-minus-square-o

    &nbsp; fa-square
    &nbsp; fa-square-o

Currency Icons
<hr>
    &nbsp; fa-bitcoin (alias)
    &nbsp; fa-btc
    &nbsp; fa-cny (alias)
    &nbsp; fa-dollar (alias)
    &nbsp; fa-eur
    &nbsp; fa-euro (alias)

    &nbsp; fa-gbp
    &nbsp; fa-inr
    &nbsp; fa-jpy
    &nbsp; fa-krw
    &nbsp; fa-money

    &nbsp; fa-rmb (alias)
    &nbsp; fa-rouble (alias)
    &nbsp; fa-rub
    &nbsp; fa-ruble (alias)
    &nbsp; fa-rupee (alias)

    &nbsp; fa-try
    &nbsp; fa-turkish-lira (alias)
    &nbsp; fa-usd
    &nbsp; fa-won (alias)
    &nbsp; fa-yen (alias)

Text Editor Icons
<hr>
    &nbsp; fa-align-center
    &nbsp; fa-align-justify
    &nbsp; fa-align-left
    &nbsp; fa-align-right
    &nbsp; fa-bold
    &nbsp; fa-chain (alias)
    &nbsp; fa-chain-broken
    &nbsp; fa-clipboard
    &nbsp; fa-columns
    &nbsp; fa-copy (alias)
    &nbsp; fa-cut (alias)
    &nbsp; fa-dedent (alias)

    &nbsp; fa-eraser
    &nbsp; fa-file
    &nbsp; fa-file-o
    &nbsp; fa-file-text
    &nbsp; fa-file-text-o
    &nbsp; fa-files-o
    &nbsp; fa-floppy-o
    &nbsp; fa-font
    &nbsp; fa-indent
    &nbsp; fa-italic
    &nbsp; fa-link

    &nbsp; fa-list
    &nbsp; fa-list-alt
    &nbsp; fa-list-ol
    &nbsp; fa-list-ul
    &nbsp; fa-outdent
    &nbsp; fa-paperclip
    &nbsp; fa-paste (alias)
    &nbsp; fa-repeat
    &nbsp; fa-rotate-left (alias)
    &nbsp; fa-rotate-right (alias)
    &nbsp; fa-save (alias)

    &nbsp; fa-scissors
    &nbsp; fa-strikethrough
    &nbsp; fa-table
    &nbsp; fa-text-height
    &nbsp; fa-text-width
    &nbsp; fa-th
    &nbsp; fa-th-large
    &nbsp; fa-th-list
    &nbsp; fa-underline
    &nbsp; fa-undo
    &nbsp; fa-unlink (alias)

Directional Icons
<hr>
    &nbsp; fa-angle-double-down
    &nbsp; fa-angle-double-left
    &nbsp; fa-angle-double-right
    &nbsp; fa-angle-double-up
    &nbsp; fa-angle-down
    &nbsp; fa-angle-left
    &nbsp; fa-angle-right
    &nbsp; fa-angle-up
    &nbsp; fa-arrow-circle-down
    &nbsp; fa-arrow-circle-left
    &nbsp; fa-arrow-circle-o-down
    &nbsp; fa-arrow-circle-o-left

    &nbsp; fa-arrow-circle-o-right
    &nbsp; fa-arrow-circle-o-up
    &nbsp; fa-arrow-circle-right
    &nbsp; fa-arrow-circle-up
    &nbsp; fa-arrow-down
    &nbsp; fa-arrow-left
    &nbsp; fa-arrow-right
    &nbsp; fa-arrow-up
    &nbsp; fa-caret-down
    &nbsp; fa-caret-left
    &nbsp; fa-caret-right
    &nbsp; fa-caret-square-o-down

    &nbsp; fa-caret-square-o-left
    &nbsp; fa-caret-square-o-right
    &nbsp; fa-caret-square-o-up
    &nbsp; fa-caret-up
    &nbsp; fa-chevron-circle-down
    &nbsp; fa-chevron-circle-left
    &nbsp; fa-chevron-circle-right
    &nbsp; fa-chevron-circle-up
    &nbsp; fa-chevron-down
    &nbsp; fa-chevron-left
    &nbsp; fa-chevron-right
    &nbsp; fa-chevron-up

    &nbsp; fa-hand-o-down
    &nbsp; fa-hand-o-left
    &nbsp; fa-hand-o-right
    &nbsp; fa-hand-o-up
    &nbsp; fa-long-arrow-down
    &nbsp; fa-long-arrow-left
    &nbsp; fa-long-arrow-right
    &nbsp; fa-long-arrow-up
    &nbsp; fa-toggle-down (alias)
    &nbsp; fa-toggle-left (alias)
    &nbsp; fa-toggle-right (alias)
    &nbsp; fa-toggle-up (alias)

Video Player Icons
<hr>
    &nbsp; fa-backward
    &nbsp; fa-eject
    &nbsp; fa-&nbsp; fast-backward
    &nbsp; fa-&nbsp; fast-forward

    &nbsp; fa-forward
    &nbsp; fa-fullscreen
    &nbsp; fa-pause
    &nbsp; fa-play

    &nbsp; fa-play-circle
    &nbsp; fa-play-circle-o
    &nbsp; fa-resize-full
    &nbsp; fa-resize-small

    &nbsp; fa-step-backward
    &nbsp; fa-step-forward
    &nbsp; fa-stop
    &nbsp; fa-youtube-play

Brand Icons
<hr>
    &nbsp; fa-adn
    &nbsp; fa-android
    &nbsp; fa-apple
    &nbsp; fa-bitbucket
    &nbsp; fa-bitbucket-square
    &nbsp; fa-bitcoin (alias)
    &nbsp; fa-btc
    &nbsp; fa-css3
    &nbsp; fa-dribbble
    &nbsp; fa-dropbox
    &nbsp; fa-&nbsp; facebook
    &nbsp; fa-&nbsp; facebook-square

    &nbsp; fa-flickr
    &nbsp; fa-foursquare
    &nbsp; fa-github
    &nbsp; fa-github-alt
    &nbsp; fa-github-square
    &nbsp; fa-gittip
    &nbsp; fa-google-plus
    &nbsp; fa-google-plus-square
    &nbsp; fa-html5
    &nbsp; fa-instagram
    &nbsp; fa-linkedin
    &nbsp; fa-linkedin-square

    &nbsp; fa-linux
    &nbsp; fa-maxcdn
    &nbsp; fa-pagelines
    &nbsp; fa-pinterest
    &nbsp; fa-pinterest-square
    &nbsp; fa-renren
    &nbsp; fa-skype
    &nbsp; fa-stack-exchange
    &nbsp; fa-stack-overflow
    &nbsp; fa-trello
    &nbsp; fa-tumblr
    &nbsp; fa-tumblr-square

    &nbsp; fa-twitter
    &nbsp; fa-twitter-square
    &nbsp; fa-vimeo-square
    &nbsp; fa-vk
    &nbsp; fa-weibo
    &nbsp; fa-windows
    &nbsp; fa-xing
    &nbsp; fa-xing-square
    &nbsp; fa-youtube
    &nbsp; fa-youtube-play
    &nbsp; fa-youtube-square

Medical Icons
<hr>
    &nbsp; fa-ambulance
    &nbsp; fa-h-square

    &nbsp; fa-hospital
    &nbsp; fa-medkit

    &nbsp; fa-plus-square
    &nbsp; fa-stethoscope

    &nbsp; fa-user-md
    &nbsp; fa-wheelchair

