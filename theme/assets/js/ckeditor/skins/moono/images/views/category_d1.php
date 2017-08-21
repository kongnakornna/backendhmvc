<?php
$CI = &get_instance();

$this->template->meta_title = 'ทรูปลูกปัญญา :: ' . $category_data['category_name_th'];
$this->template->meta_description = $category_data['menu_og_keyword'];
$this->template->meta_keywords = $category_data['menu_og_keyword'];
$this->template->meta_og_title = 'ทรูปลูกปัญญา :: ' . $category_data['cms_subject'];
$this->template->meta_og_description = $category_data['menu_og_keyword'];
$this->template->meta_og_image = '/canvas/themes/tppy/assets/images/logo_main.png'; //1200x630 pixels.
//default css
$this->template->add_stylesheet("assets/css/knowledge/c_news/knowledge.css");
$this->template->add_stylesheet("assets/css/knowledge/c_news/knowledge_main.css");
//default css
$this->template->add_stylesheet("assets/font/simple-line-icons/simple-line-icons.min.css");
//$this->template->add_stylesheet("assets/css/knowledge/" . $category_data['theme_path'] . "/knowledge.css");
?>
<div class="container" style="margin:10px auto;">
    <div class=" txt-nav ttf-textB ttf-s-md">
        <?= rtrim($CI->createPath($category_id), '&gt;') ?>
    </div>
</div>

<div class="container-fulid text-center kl-main-menu">
    <h1><?= $category_data['category_name_th'] ?></h1>
    <?php if ($category_child) { ?>
        <?php foreach ($category_child as $k => $v) { ?>
            <span clsss="cat_child" style="text-align:middle">
                <a href="<?= site_url("/cmsblog/index/" . $v['category_name_code']) ?>" target="_blank"><?= $v['category_name_th'] ?></a>
                <?= $v != end($category_child) ? '&#8226;' : '' ?>
            </span>
        <?php } ?>
    <?php } ?>
</div>

<!--   EDITOR PICKS   !-->
<div class="container-fulid" style="background-color:#333;">
    <?php $first = reset($editorpicks); ?>
    <div class="container">
        <div class="row">
            <div class="header-kl col-md-12 "><br>Editor's Picks</div>

            <div class="col-md-12">
                <?= $this->load->view('/partials/content_blog03', array('data' => $first)) ?>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <?php foreach (array_slice($editorpicks, 1, 4) as $k => $v) { ?>
                        <div class="col-md-3 col-sm-6 col-xs-12 <?= $k % 2 == 1 ? "alternate-row" : "" ?>">
                            <?= $this->load->view('/partials/content_blog02', array('data' => $v)) ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="margin-top:20px;">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <a href="<?= site_url('knowledge/index/' . $v['detail']['category_id']); ?>" class="btn-plookpanya main-text-color kl-btn-border kl-btn-active" style="width:80%">
                        Load More (xx of x,xxx)
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
<!--   EDITOR PICKS   !-->

<!-- DETAIL !-->
<div class="container text-center main-text-color" style="font-family:trueplookpanyaround; font-size:150%;">
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="content-cate-block" style="padding:10px; height:240px">
                <img src="https://cdn0.vox-cdn.com/images/verge/default-avatar.v9899025.gif" class="img-responsive img-circle main-border-color" style="margin: 0 auto; max-width:125px;"/>  
                <div style="font-weight:bold;  font-size:200%"><?= number_format($category_data['count_content']) ?></div>
                <div class="kl-icon-des"><?= $category_data['category_name_en'] ?>'s Contents</div>
            </div>
        </div>  
        <div class="col-md-3 col-sm-6">
            <div class="content-cate-block" style="padding:10px; height:240px">
                <img src="https://cdn0.vox-cdn.com/images/verge/default-avatar.v9899025.gif" class="img-responsive img-circle main-border-color" style="margin: 0 auto; max-width:125px;"/>  
                <div style="font-weight:bold; font-size:200%"><?= number_format($category_data['count_blogger']) ?></div>
                <div class="kl-icon-des"><?= $category_data['category_name_en'] ?>'s Bloggers</div>
            </div>
        </div>  
        <div class="col-md-6 col-sm-12">
            <div class="content-cate-block" style="height: 240px; padding: 50px; line-height: 1.2;">
                <div class="vertical-align: middle;">
                    ร่วมสร้างคลังความรู้ออนไลน์ <br/> กับทรูปลูกปัญญา
                    <div>
                        <a href="" class="btn-plookpanya main-text-color kl-btn-border btn-lg"><i class="icon-pencil" style="font-size:75%;"></i> Add Content</a>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
<!-- DETAIL !-->
<!-- EACH SUB MENU !-->
<?php foreach ($child as $k => $v) { ?>
    <div class="container-fulid <?= $k % 2 == 1 ? "kl-alternate-row" : "" ?>" style="background:<?= $v['detail']['child_background'] ?>">
        <div class="container ">
            <div class="row" style="text-align:bottom; margin-top:30px">
                <div class="header-kl col-md-9"><?= $v['detail']['category_name_th'] ?></div>
                <div class="header-kl col-md-3" style="top: 20px;">&nbsp;<a class="pull-right bottom-column btn btn-plookpanya btn-md cols-bottom" >View All</a></div>
            </div>

            <div class="row"  style="<?= $v['detail']['child_style'] ?>">
                <?= $this->load->view('partials/block4_style_1', array('data' => $v['data'], 'card_border_color' => 'transparent')) ?>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="content-cate-block" style="padding:0px 10px;">
                        <div class="main-text-color">Trainding</div>
                        <?php foreach (explode(",", $v['detail']['trending']) as $i => $j) { ?>
                            <a href="<?= $j ?>"><?= $j ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row" style="margin: 20px;">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <a href="<?= site_url('knowledge/index/' . $v['detail']['category_id']); ?>" class="btn-plookpanya main-text-color kl-btn-border kl-btn-active">View All</a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- EACH SUB MENU !-->