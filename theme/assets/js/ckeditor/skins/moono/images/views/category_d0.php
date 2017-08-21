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
<div class="container-fulid" style="background-color:#ccc">
  <div class="row">
    <div class="container">
      <div class="row">
        <div class="text-center">
        <h1>Plook Knowledge </h1>
        <!-- !-->
          <div class="container text-center main-text-color" style="font-family:trueplookpanyaround; font-size:150%;">
            <div class="row">
              <div class="col-md-3 col-sm-3">
              <div class="content-cate-block" style="padding:10px; height:240px">
                <img src="https://cdn0.vox-cdn.com/images/verge/default-avatar.v9899025.gif" class="img-responsive img-circle main-border-color" style="margin: 0 auto; max-width:125px;"/>  
                <div style="font-weight:bold;  font-size:200%"><?=number_format($category_data['count_content'])?></div>
                <div class="kl-icon-des"><?=$category_data['category_name_en']?>'s Contents</div>
              </div>
              </div>  
              <div class="col-md-6 col-sm-6">
              <div class="content-cate-block" style="height: 240px; padding: 50px; line-height: 1.2;">
                <div class="vertical-align: middle;">
                  ร่วมสร้างคลังความรู้ออนไลน์ <br/> กับทรูปลูกปัญญา
                  <div>
                      <a href="" class="btn-plookpanya main-text-color kl-btn-border btn-lg"><i class="icon-pencil" style="font-size:75%;"></i> Add Content</a>
                  </div>
                </div>
              </div>
              </div>  
              <div class="col-md-3 col-sm-3">
              <div class="content-cate-block" style="padding:10px; height:240px">
                <img src="https://cdn0.vox-cdn.com/images/verge/default-avatar.v9899025.gif" class="img-responsive img-circle main-border-color" style="margin: 0 auto; max-width:125px;"/>  
                <div style="font-weight:bold; font-size:200%"><?=number_format($category_data['count_blogger'])?></div>
                <div class="kl-icon-des"><?=$category_data['category_name_en']?>'s Bloggers</div>
              </div>
              </div>  
            </div>
          </div>
          <!-- !-->
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fulid" style="background-color:#F26330">
  <div class="row">
    <div class="container">
      <div class="row">
        <div class="text-center">
          <div>
            <img src=""/>
            dasd
            </div>
        </div>
      </div>
    </div>
  </div>
</div>