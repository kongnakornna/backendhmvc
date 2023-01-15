<?php
$CI=&get_instance();

$this->template->meta_title = 'ทรูปลูกปัญญา :: '.$category_data['category_name_th'];
$this->template->meta_description=$category_data['menu_og_keyword'];
$this->template->meta_keywords=$category_data['menu_og_keyword'];
$this->template->meta_og_title= 'ทรูปลูกปัญญา :: '.$category_data['cms_subject'];
$this->template->meta_og_description=$category_data['menu_og_keyword'];
$this->template->meta_og_image = '/canvas/themes/tppy/assets/images/logo_main.png'; //1200x630 pixels.
$this->template->add_stylesheet("assets/css/knowledge/{$category_data['theme_path']}/knowledge.css");

$this->template->add_stylesheet("assets/font/simple-line-icons/simple-line-icons.min.css");

?>
<div class="container txt-nav nopadding ttf-textB ttf-s-md">
<?=rtrim($CI->createPath($category_id), '&gt;')?>
</div>
<div class="container-fulid text-center kl-main-menu">
  <h1><?=$category_data['category_name_th']?></h1>
  <?php if($category_child) { ?>
  <?php //_vd($category_child)?>
  
    <?php if($menu_parent) { ?>
      <span clsss="cat_child ">
        <a href='<?=site_url("/knowledge/".$menu_parent['category_name_code'])?>'>All</a>&#8226;
      </span>
    <?php }?>
    <?php foreach($category_child as $k=>$v) { ?>
      <span clsss="cat_child ">
        <?php if($category_data['category_id'] == $v['category_id']) {?>
          <span class="main-text-color"><?=$v['category_name_th']?></span><?=$v != end($category_child) ? '&#8226;' : ''  ?>          
        <?php }else{ ?>
          <a href="<?=site_url("/knowledge/".$v['category_name_code'])?>" ><?=$v['category_name_th']?></a><?=$v != end($category_child) ? '&#8226;' : ''  ?>
        <?php } ?>
      </span>
    <?php } ?>
  <?php } ?>
</div>

<div class="container" style="margin-top:20px;">
  <div class="row">
    <span class="header-kl col-md-6"><?=$category_data['category_name_th']?></span> 
    <span class="pull-right col-md-6 text-right" style="display:inline-block; bottom-align-text; ">
      <form id="searchForm" action="" method="get" style="display: inline-block;">
        <div class="input-group kl-txtsearch">
          <input type="text" class="form-control nopadding" name="q" id="q2" placeholder="Search" required="" style="width: 350px;" />
          <button class="btn btn-default btnsearch btn-ghost" type="submit"></button>
        </div>
      </form>
      <a href="#" class="btn-plookpanya pull-right main-text-color kl-btn-border hidden-xs" style="margin-left:20px; position: relative; top: 0.25em;"><i class="icon-pencil" style="font-size:75%"></i> Add Content</a>
    </span>
  </div>
  <div class="hr_kl"></div>
</div>

<div class="container" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-12 text-center">
      <button class="btn-plookpanya main-text-color kl-btn-border">Editor's Picks </button>
      <button class="btn-plookpanya main-text-color kl-btn-border kl-btn-active" style="margin-left:20px; min-width:100px;">All</button>
      <a href="" class="btn-plookpanya pull-right main-text-color kl-btn-border visible-xs" style="line-height: 75%;"><i class="icon-pencil" style="font-size:75%;"></i></a>
    </div>
  </div>
</div>

<div class="container">
  <span><span class="main-text-color">ผลการค้นหา</span> <?=number_format(count($list), 0)?> บทความ</span>
  <span class="pull-right"><a href=""  class="main-text-color">Last</a> | <a href="">Most View</a></span>
</div>
<div class="container">
  <div class="row" id="content-blog">
  <?php foreach($list as $k=>$v) { ?>
    <div class="col-md-<?=$k+1 > 3 ? 3 : 4?> col-sm-6 col-xs-12">
    <?=$this->load->view('partials/content_blog01', array('data'=>$v))?>
    </div>
  <?php } ?>
  </div>
</div>
<div class="container" style="margin-top:20px;">
  <div class="col-md-6 col-md-offset-3 text-center">
   <button class="btn-plookpanya main-text-color kl-btn-border kl-btn-active" style="width:80%">Load More (20 of 1,234)</button>
  </div>
</div>