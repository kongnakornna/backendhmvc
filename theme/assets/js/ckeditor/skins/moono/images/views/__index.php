<?php
//echo '<pre>';
//print_r($eye_short);
//echo '</pre>';
?>

<!-- POPULAR SHOP -->
<?php if (!empty($eye_short)) { ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                <div class="feature-head-shop">
                    <h3>
                        <a href="<?= site_url('/content/content-category/' . $eye_short['list'][0]['content_category_id']) ?>">
                        <?= $eye_short['content_category_name'] ?>
                        </a>
                    </h3>	
                </div>
            </div>
            <!--</div>
            <div class="row">-->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 pull-right text-right">
                <div class="pad_shop"><a href="<?= site_url('/content/content-category/' . $eye_short['list'][0]['content_category_id']) ?>">+ ดูทั้งหมด</a></div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="feature-box">
                    <?php foreach ($eye_short['list'] as $v) { ?>
                        <div class="col-md-3 col-sm-3 ">
                            <div class="feature-box-heading img-thumbnail">
                                <em>
                                    <a title="<?= $v['content_subject'] ?>" href="<?= site_url('/content/content-detail/' . $v['content_id'] . '/' . str_friendly($v['content_subject'])) ?>"><img  src="<?php echo image_thumb($v['content_image_url'], 600, 300, TRUE) ?>"  alt="<?= $v['content_subject'] ?>"  class="img-responsive center-block "/></a>
                                </em>
                                <div class="box-head">
                                <h4>
                                    <a class="limit-h4" title="<?= $v['content_subject'] ?>" href="<?= site_url('/content/content-detail/' . $v['content_id'] . '/' . str_friendly($v['content_subject'])) ?>"><?= $v['content_subject'] ?></a>
                                </h4>
                                </div>
                                <p class="box-title">
                                    <?= $v['content_detail_short'] ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!--feature end-->
            </div>
        </div>
    </div>
<?php } ?>
<style>.tag-tab a:hover{color: #fff; }</style>
<!-- POPULAR SHOP -->

<!-- POPULAR SHOP -->
<?php if (!empty($eye_old)) { ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                <div class="feature-head-shop">
                    <h3>
                        <a href="<?= site_url('/content/content-category/' . $eye_old['list'][0]['content_category_id']) ?>">
                        <?= $eye_old['content_category_name'] ?>
                        </a>
                    </h3>	
                </div>
            </div>
            <!--</div>
            <div class="row">-->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 pull-right text-right">
                <div class="pad_shop"><a href="<?= site_url('/content/content-category/' . $eye_old['list'][0]['content_category_id']) ?>">+ ดูทั้งหมด</a></div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="feature-box">
                    <?php foreach ($eye_old['list'] as $v) { ?>
                        <div class="col-md-3 col-sm-3 ">
                            <div class="feature-box-heading img-thumbnail">
                                <em>
                                    <a title="<?= $v['content_subject'] ?>" href="<?= site_url('/content/content-detail/' . $v['content_id'] . '/' . str_friendly($v['content_subject'])) ?>"><img  src="<?php echo image_thumb($v['content_image_url'], 600, 300, TRUE) ?>"  alt="<?= $v['content_subject'] ?>"  class="img-responsive center-block "/></a>
                                </em>
                                <div class="box-head">
                                <h4>
                                    <a class="limit-h4" title="<?= $v['content_subject'] ?>" href="<?= site_url('/content/content-detail/' . $v['content_id'] . '/' . str_friendly($v['content_subject'])) ?>"><?= $v['content_subject'] ?></a>
                                </h4>
                                </div>
                                <p class="box-title">
                                    <?= $v['content_detail_short'] ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!--feature end-->
            </div>
        </div>
    </div>
<?php } ?>
<style>.tag-tab a:hover{color: #fff; }</style>
<!-- POPULAR SHOP -->

<!-- POPULAR SHOP -->
<?php if (!empty($eye_slope)) { ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                <div class="feature-head-shop">
                    <h3>
                        <a href="<?= site_url('/content/content-category/' . $eye_slope['list'][0]['content_category_id']) ?>">
                        <?= $eye_slope['content_category_name'] ?>
                        </a>
                    </h3>	
                </div>
            </div>
            <!--</div>
            <div class="row">-->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 pull-right text-right">
                <div class="pad_shop"><a href="<?= site_url('/content/content-category/' . $eye_slope['list'][0]['content_category_id']) ?>">+ ดูทั้งหมด</a></div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
               <div class="feature-box">
                    <?php foreach ($eye_slope['list'] as $v) { ?>
                        <div class="col-md-3 col-sm-3 ">
                            <div class="feature-box-heading img-thumbnail">
                                <em>
                                    <a title="<?= $v['content_subject'] ?>" href="<?= site_url('/content/content-detail/' . $v['content_id'] . '/' . str_friendly($v['content_subject'])) ?>"><img  src="<?php echo image_thumb($v['content_image_url'], 600, 300, TRUE) ?>"  alt="<?= $v['content_subject'] ?>"  class="img-responsive center-block "/></a>
                                </em>
                                <div class="box-head">
                                <h4>
                                    <a class="limit-h4" title="<?= $v['content_subject'] ?>" href="<?= site_url('/content/content-detail/' . $v['content_id'] . '/' . str_friendly($v['content_subject'])) ?>"><?= $v['content_subject'] ?></a>
                                </h4>
                                </div>
                                <p class="box-title">
                                    <?= $v['content_detail_short'] ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!--feature end-->
            </div>
        </div>
    </div>
<?php } ?>
<style>.tag-tab a:hover{color: #fff; }</style>
<!-- POPULAR SHOP -->