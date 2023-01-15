<!-- POPULAR SHOP -->
<?php if(!empty($knowledge_all)) { ?>
<div class="container">
    <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                <div class="feature-head-shop">
                    <h3>
                        <?=$knowledge_all['list'][0]['content_category_name']?>
                    </h3>	
                </div>
            </div>
            <!--</div>
            <div class="row">-->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 pull-right text-right">
                <?php
                echo $pagination->create_links();
                ?>
            </div>
        </div>
        <hr>
      <div class="row">
        <div class="col-md-12">
          <div class="feature-box">
          <?php foreach($knowledge_all['list'] as $v) { ?>
          <div class="col-md-4 col-sm-4 ">
              <div class="feature-box-heading img-thumbnail">
                <em>
                    <a title="<?=$v['content_subject']?>" href="<?=site_url('/content/content-detail/'.$v['content_id'].'/'.str_friendly($v['content_subject']))?>"><img  src="<?php echo image_thumb($v['content_image_url'], 600, 300, TRUE)?>"  alt="<?=$v['content_subject']?>"  class="img-responsive center-block "/></a>
                </em>
                <h4>
                    <a title="<?=$v['content_subject']?>" href="<?=site_url('/content/content-detail/'.$v['content_id'].'/'.str_friendly($v['content_subject']))?>"><?= str_substr($v['content_subject'], 105)?></a>
                </h4>
                <p class="limit-2">
                	<?=str_substr($v['content_detail_short'], 105)?>
              	</p>
              </div>
            </div>
          <?php } ?>
          </div>
          <!--feature end-->
        </div>
      </div>
    <div class="row text-center">
            <div class="col-lg-12">
                <div><?php echo $pagination->create_links(); ?></div>
            </div>
        </div>
    </div>
<?php } ?>
<style>.tag-tab a:hover{color: #fff; }</style>
<!-- POPULAR SHOP -->

