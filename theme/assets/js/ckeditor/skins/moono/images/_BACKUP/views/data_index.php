<?php
$this->template->add_stylesheet('assets/css/custom_v2015.css');
$this->template->add_stylesheet('assets/css/bootstrap/css/bootstrap.css');
$this->template->add_javascript('assets/css/bootstrap/js/bootstrap.min.js');

//pattern include start....
$this->template->add_stylesheet('assets/swiper/dist/css/swiper.min.css');
$this->template->add_javascript('assets/swiper/dist/js/swiper.js');
$this->template->add_stylesheet('assets/css/pattern.css');
$this->template->add_stylesheet('assets/css/card-hover.css');
//pattern include end.....
//
//$this->template->add_stylesheet('assets/css/teacherV2.css');
//$this->template->add_stylesheet('assets/css/teacher-hover.css');
//tooltip
//$this->template->add_stylesheet('assets/tooltip/tipso.css');
//$this->template->add_javascript('assets/tooltip/tipso.js');
//$this->template->add_javascript('assets/js_custom/jquery.simple.select.js');
//$this->template->add_stylesheet('assets/css/bootstrap-select/bootstrap-select.css');
//$this->template->add_javascript('assets/css/bootstrap-select/bootstrap-select.js');
//knowledgeV2 start

$this->template->add_stylesheet('assets/knowledgeV2/select/select.css');

$this->template->add_stylesheet('assets/custom-scrollbar/jquery.mCustomScrollbar.css');
$this->template->add_javascript('assets/custom-scrollbar/jquery.mCustomScrollbar.js');

$this->template->add_stylesheet('assets/knowledgeV2/css/knowledgeV2.css');
//$this->template->add_stylesheet('assets/knowledgeV2/css/style.css');
//$this->template->add_javascript('assets/knowledgeV2/js/main.js');
?>
<main class="main home-page campaign-module">
    <?php $this->load->view('global/block/header_section/_knowledgeV2'); ?>
</main>
<?php
//render card

if ($IE_version == "IE>8") {
    ?>
    <div id="knowledgepattle" class="main-knowledge">
        <?php
        //echo $isDetect;
        echo file_get_contents(base_url() . 'global/pattern/index/3/' . $isDetect);
        ?>
    </div>
    <?php
}
?>
<section id="main-top" class="main-knowledge">
    <div class="container" style="">
        <div class="row">
            <div class="col-md-10 col-sm-10 col-lg-10 col-xs-12">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="h2">
                            <img src="/assets/img-mocup/icon_search.png" style="position: absolute;z-index: 0;width: 20px">
                            <div style="padding-left: 26px">ค้นหาคลังความรู้</div>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 pt-10">
                        <h2 style="font-size: 24px;">วิชา</h2>
                        <dl id="subject" class="dropdown-content">

                            <dt><span><?= $select_default['subject']['name'] ?><span class="value"><?= $select_default['subject']['id'] ?></span></span></dt>

                            <dd>
                                <ul>
                                    <li>ทุกรายวิชา<span class="value">0</span></li>
                                    <?php
                                    foreach ($category as $key => $value) {
                                        if (($value->mul_category_id % 1000) == 0) {
                                            ?>
                                            <li><?= $value->mul_category_name ?><span class="value"><?= $value->mul_category_id ?></span></li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </dd>
                        </dl>
                    </div>

                    <div class="col-md-6 pt-10">
                        <h2 style="font-size: 24px;">ระดับชั้น</h2>
                        <dl id="level" class="dropdown-content">
                            <dt><span><?= $select_default['level']['name'] ?><span class="value"><?= $select_default['level']['id'] ?></span></span></dt>
                            <dd>
                                <ul>
                                    <li>ทุกระดับชั้น<span class="value">0</span></li>
                                    <?php
                                    foreach ($level as $key => $value) {
                                        if ($value->mul_level_id != $value->mul_level_parent_id) {
                                            ?>
                                            <li><?= $value->mul_level_name ?><span class="value"><?= $value->mul_level_id ?></span></li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </dd>
                        </dl>

                    </div>
                </div>

                <div class="row">
                    <div  class="col-md-6 pt-10 context-show">
                        <h2 style="font-size: 24px;">สาระ <img src="assets/img-ex-ku/ajax-loader.gif" id="loading-img" style="display: none"></h2>
                        <dl id="context" class="dropdown-content">
                            <dt><span><?= $select_default['knowledge']['name'] ?><span class="value"><?= $select_default['knowledge']['id'] ?></span></span></dt>
                            <dd>
                                <ul>
                                    <li>ทุกสาระการเรียนรู้<span class="value">0</span></li>
                                </ul>
                            </dd>
                        </dl>

                    </div>
                    <div id="search-data" class="col-md-6 pt-10">
                        <h2 style="font-size: 24px;">คำค้น</h2>
                        <input class="simple-input" type="text" value="<?= $select_default['text_search'] ?>" placeholder="กรุณาระบุคำค้น เช่น ภาษาถิ่น บทร้อยกรอง">
                        <div style="line-height: 35px;text-align: right">
                            <a class="btn-yellow tahoma-font" style="font-size: 16px;">ค้นหา</a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="word-link" class="col-md-2 col-sm-2 col-xs-12 col-lg-2 pt-20">
<!--                <span id="result"></span>-->
                <h2 style="font-size: 24px;">คำค้นยอดนิยม</h2>
                <div class="pl-0 pr-0 col-lg-12 mobile-hidden">
                    <?php
                    foreach ($word_link as $key => $value) {
                        echo $value->html . '<br>';
                    }
                    ?>
                </div>
                <div class="pl-0 pr-0 col-xs-12 mobile-show pb-20">
                    <?php
                    foreach ($word_link as $key => $value) {

                        echo $value->html;
                        if (($key + 1) != count($word_link)) {
                            echo ', ';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="main-knowledge">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 teacher-desktop" id="teacher-all-tabs">
                <div id="css-search" class="box-border">
                    <h2 class="h2">คลังความรู้ล่าสุด</h2>
                    <nav id="teacher-nav" class="pills-nav">
                        <a data-type="teacher-all" title="เนื้อหาทั้งหมด" class="active">เนื้อหาทั้งหมด</a>
                        <a data-type="teacher-prototype" title="เนื้อหาประเภทวิดีโอ" >เนื้อหาประเภทวิดีโอ</a>
                        <a data-type="teacher-portfolio" title="เนื้อหาประเภทอื่น">เนื้อหาประเภทอื่น</a>
                    </nav>
                    <div class="teacher-div-tabs">
                        <!-- all -->
                        <div class="row show" id="teacher-all" data-type="tabs">
                            <?php
                            foreach ($media_all['all'] as $key => $value) {
                                ?>
                                <div id="search-ex" data-views="<?= str_replace(',', '', $value['viewcount']) ?>" data-date="<?= $value['date_post'] ?>" class="media-content col-lg-3 col-md-3 col-sm-3 col-xs-12 mobile-hidden">
                                    <a href="<?= $value['content_url'] ?>" title="<?= $value['content_subject'] ?>" class="grid-thumbnail css-crop-150 box" target="_blank">
                                        <?php
                                        $srcImg = $value['thumbnail'];
                                        ?>
                                        <img src="<?= $srcImg ?>" alt="<?= $value['content_subject'] ?>">
                                        <div class="overlay-red">
                                            <div style="margin-top: -6px;"><?= $value['cat_level_name'] ?></div>
                                            <div style="margin-top: -14px;"><?= $value['cat_super_name'] ?></div>
                                        </div>
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <h5 class="content-2-line"><a href="<?= $value['content_url'] ?>" title="<?= $value['content_subject'] ?>" target="_blank"><?= $value['content_subject'] ?></a></h5>
                                        <p style="color: #939498;font-size: 11.9px;"><?= $value['view_show'] ?> views | <?= $value['date_short'] ?> </p>
                                    </figcaption>
                                </div>
                                <div data-views="<?= str_replace(',', '', $value['viewcount']) ?>" data-date="<?= $value['date_post'] ?>" class="media-content pl-0 pr-0 col-sm-12 mobile-show">
                                    <div class="col-lg-3 col-md-3 col-sm-6"> 
                                        <a target="_blank" class="grid-thumbnail css-crop-110" title="<?= $value['content_subject'] ?>" href="<?= $value['content_url'] ?>">
                                            <img alt="<?= $value['content_subject'] ?>" src="<?= $srcImg ?>">
                                        </a>
                                        <figcaption class="grid-thumbnail--caption" style="padding-left: 19px;">
                                            <h5 style="height: 40px;overflow: hidden;"><a target="_blank" title="<?= $value['content_subject'] ?>" href="<?= $value['content_url'] ?>"><?= $value['content_subject'] ?></a></h5>
                                        </figcaption>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 viewall pr-15 pbem-1"><a href="<?= base_url() ?>knowledge/search" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                        </div>
                        <!-- clip -->
                        <div class="row hide" id="teacher-prototype" data-type="tabs">
                            <?php
                            foreach ($media_v['clip'] as $key => $value) {
                                ?>
                                <div id="search-ex" data-views="<?= str_replace(',', '', $value['viewcount']) ?>" data-date="<?= $value['date_post'] ?>" class="media-content col-lg-3 col-md-3 col-sm-3 col-xs-12 mobile-hidden">
                                    <a href="<?= $value['content_url'] ?>" title="<?= $value['content_subject'] ?>" class="grid-thumbnail css-crop-150 box" target="_blank">
                                        <?php
                                        $srcImg = $value['thumbnail'];
                                        ?>
                                        <img src="<?= $srcImg ?>" alt="<?= $value['content_subject'] ?>">
                                        <div class="overlay-red">
                                            <div style="margin-top: -6px;"><?= $value['cat_level_name'] ?></div>
                                            <div style="margin-top: -14px;"><?= $value['cat_super_name'] ?></div>
                                        </div>
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <h5 class="content-2-line"><a href="<?= $value['content_url'] ?>" title="<?= $value['content_subject'] ?>" target="_blank"><?= $value['content_subject'] ?></a></h5>
                                        <p style="color: #939498;font-size: 11.9px;"><?= $value['view_show'] ?> views | <?= $value['date_short'] ?> </p>
                                    </figcaption>
                                </div>
                                <div data-views="<?= str_replace(',', '', $value['viewcount']) ?>" data-date="<?= $value['date_post'] ?>" class="media-content pl-0 pr-0 col-sm-12 mobile-show">
                                    <div class="col-lg-3 col-md-3 col-sm-6"> 
                                        <a target="_blank" class="grid-thumbnail css-crop-110" title="<?= $value['content_subject'] ?>" href="<?= $value['content_url'] ?>">
                                            <img alt="<?= $value['content_subject'] ?>" src="<?= $srcImg ?>">
                                        </a>
                                        <figcaption class="grid-thumbnail--caption" style="padding-left: 19px;">
                                            <h5 style="height: 40px;overflow: hidden;"><a target="_blank" title="<?= $value['content_subject'] ?>" href="<?= $value['content_url'] ?>"><?= $value['content_subject'] ?></a></h5>
                                        </figcaption>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 viewall pr-15 pbem-1"><a href="<?= base_url() ?>knowledge/search" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                        </div>
                        <!-- clip -->
                        <!-- other -->
                        <div class="row hide" id="teacher-portfolio" data-type="tabs">
                            <?php
                            foreach ($media_t['other'] as $key => $value) {
                                ?>
                                <div id="search-ex" data-views="<?= str_replace(',', '', $value['viewcount']) ?>" data-date="<?= $value['date_post'] ?>" class="media-content col-lg-3 col-md-3 col-sm-3 col-xs-12 mobile-hidden">
                                    <a href="<?= $value['content_url'] ?>" title="<?= $value['content_subject'] ?>" class="grid-thumbnail css-crop-150 box" target="_blank">
                                        <?php
                                        $srcImg = $value['thumbnail'];
                                        ?>
                                        <img src="<?= $srcImg ?>" alt="<?= $value['content_subject'] ?>">
                                        <div class="overlay-red">
                                            <div style="margin-top: -6px;"><?= $value['cat_level_name'] ?> </div>
                                            <div style="margin-top: -14px;"><?= $value['cat_super_name'] ?></div>
                                        </div>
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <h5 class="content-2-line"><a href="<?= $value['content_url'] ?>" title="<?= $value['content_subject'] ?>" target="_blank"><?= $value['content_subject'] ?></a></h5>
                                        <p style="color: #939498;font-size: 11.9px;"><?= $value['view_show'] ?> views | <?= $value['date_short'] ?> </p>
                                    </figcaption>
                                </div>
                                <div data-views="<?= str_replace(',', '', $value['viewcount']) ?>" data-date="<?= $value['date_post'] ?>" class="media-content pl-0 pr-0 col-sm-12 mobile-show">
                                    <div class="col-lg-3 col-md-3 col-sm-6"> 
                                        <a target="_blank" class="grid-thumbnail css-crop-110" title="<?= $value['content_subject'] ?>" href="<?= $value['content_url'] ?>">
                                            <img alt="<?= $value['content_subject'] ?>" src="<?= $srcImg ?>">
                                        </a>
                                        <figcaption class="grid-thumbnail--caption" style="padding-left: 19px;">
                                            <h5 style="height: 40px;overflow: hidden;"><a target="_blank" title="<?= $value['content_subject'] ?>" href="<?= $value['content_url'] ?>"><?= $value['content_subject'] ?></a></h5>
                                        </figcaption>
                                    </div>
                                </div>

                                <?php
                            }
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 viewall pr-15 pbem-1"><a href="<?= base_url() ?>knowledge/search" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                        </div>
                        <!-- other -->
                    </div>
                </div>
            </div>         

            <!-- INFOGRAPHIC -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 tablet-hide">
                <h2 class="h2">คลังฮิตติดกระแส</h2>
                <?php
                foreach ($theme as $key => $value) {
                    ?>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6" style="padding-left: 7px;padding-right: 7px"> 
                        <a href="<?= $value->link ?>" title="<?= $value->title ?>"  target="_blank">
                            <img src="<?= $value->thumbnail ?>" alt="<?= $value->title ?>" style="border-radius: 5px">
                        </a>
                        <figcaption class="grid-thumbnail--caption" style="min-height: 0;margin-left: 0">
                            <h5 style="height: 38px;overflow: hidden;margin-top: 15px;margin-bottom: 15px;"><a data-original-title="<?= $value->title ?>" data-toggle="tooltip" class="css-text-limit" href="<?= $value->link ?>" title="<?= $value->title ?>" target="_blank"><?= $value->title ?></a></h5>
                        </figcaption>
                    </div>
                    <?php
                }
                ?>

                <!--                <div class="viewall pr-15 pbem-1"><a href="http://www.trueplookpanya.com/infographic" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                -->
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 tablet-show">
                <h2 class="h2">คลังฮิตติดกระแส</h2>
                <?php
                foreach ($theme as $key => $value) {
                    ?>
                    <div class="col-lg-4 col-md-3 col-sm-3 col-xs-4"> 
                        <a href="<?= $value->link ?>" title="<?= $value->title ?>"  target="_blank">
                            <img class="center-cropped"  src="<?= $value->thumbnail ?>" alt="<?= $value->title ?>">
                        </a>
                        <figcaption class="grid-thumbnail--caption" style="min-height: 0;margin-left: 0">
                            <h5 style="height: 38px;overflow: hidden;margin-top: 15px;margin-bottom: 15px;"><a class="css-text-limit" href="<?= $value->link ?>" title="<?= $value->title ?>" target="_blank"><?= $value->title ?></a></h5>
                        </figcaption>
                    </div>
                    <?php
                }
                ?>

                <!--                <div class="viewall pr-15 pbem-1"><a href="http://www.trueplookpanya.com/infographic" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                -->
            </div>
            <!-- INFOGRAPHIC -->
        </div>
    </div>
</section>
<section class="main-knowledge">
    <div class="container">
        <div class="row">
            <!-- กิจกรรมทักษะ -->
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 variety-desktop" id="variety-all-tabs">
                <h2 class="h2">ความรู้และกิจกรรมเสริมทักษะ</h2>
                <div class="box-border">

                    <nav id="variety-nav" class="pills-nav">
                        <a data-type="media-child" title="ความรู้ปฐมวัย" class="active">ความรู้ปฐมวัย</a>
                        <a data-type="media-news" title="สื่อน่ารู้" >สื่อน่ารู้</a>
                        <a data-type="media-prototype" title="กิจกรรมนอกห้องเรียน">กิจกรรมนอกห้องเรียน</a>
                        <a data-type="media-portfolio" title="ลูกเสือ-เนตรนารี">ลูกเสือ-เนตรนารี</a>
                    </nav>
                    <div class="variety-div-tabs">
                        <!-- สื่อพัฒนานอกระบบ -->
                        <div class="row hide" id="media-news" data-type="tabs">
                            <?php
                            foreach ($media_out as $key => $value) {
                                ?>

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 mobile-hidden">
                                    <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>" title="<?= $value->mul_content_subject ?>" class="grid-thumbnail css-crop-150" target="_blank">
                                        <?php
                                        //$srcImg = 'http://www.trueplookpanya.com/data/product/media/'.$value->edu_photo_file[0]->photo_path.'/260/260/'.$value->edu_photo_file[0]->edu_photo_files_name;
                                        $srcImg = $value->thumb;
                                        ?>
                                        <img src="<?= $srcImg ?>" alt="<?= $value->mul_content_subject ?>">
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <h5 class="content-2-line"><a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>" title="<?= $value->mul_content_subject ?>" target="_blank"><?= $value->mul_content_subject ?></a></h5>
                                    </figcaption>
                                </div>

                                <div class="pl-0 pr-0 col-sm-12 mobile-show">
                                    <div class="col-lg-3 col-md-3 col-sm-6"> 
                                        <a target="_blank" class="grid-thumbnail css-crop-110" title="<?= $value->mul_content_subject ?>" href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>">
                                            <img alt="<?= $value->mul_content_subject ?>" src="<?= $srcImg ?>">
                                        </a>
                                        <figcaption class="grid-thumbnail--caption" style="padding-left: 19px;">
                                            <h5 style="height: 40px;overflow: hidden;"><a target="_blank" title="<?= $value->mul_content_subject ?>" href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>"><?= $value->mul_content_subject ?></a></h5>
                                        </figcaption>
                                    </div>
                                </div>

                                <?php
                            }
                            ?>
                            <div class="viewall pr-15 pbem-1"><a href="<?= BASE_URL . 'knowledge/activity' ?>" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                        </div>
                        <!-- สื่อพัฒนานอกระบบ -->
                        <!-- กิจกรรมห้องเรียน -->
                        <div class="row hide" id="media-prototype" data-type="tabs">
                            <?php
                            foreach ($room_activity as $key => $value) {
                                ?>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 mobile-hidden">
                                    <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>" title="<?= $value->mul_content_subject ?>" class="grid-thumbnail css-crop-150" target="_blank">
                                        <?php
                                        //$srcImg = 'http://www.trueplookpanya.com/data/product/media/'.$value->edu_photo_file[0]->photo_path.'/260/260/'.$value->edu_photo_file[0]->edu_photo_files_name;
                                        $srcImg = $value->thumb;
                                        ?>
                                        <img src="<?= $srcImg ?>" alt="<?= $value->mul_content_subject ?>">
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <h5 class="content-2-line"><a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>" title="<?= $value->mul_content_subject ?>" target="_blank"><?= $value->mul_content_subject ?></a></h5>
                                    </figcaption>
                                </div>

                                <div class="pl-0 pr-0 col-sm-12 mobile-show">
                                    <div class="col-lg-3 col-md-3 col-sm-6"> 
                                        <a target="_blank" class="grid-thumbnail css-crop-110" title="<?= $value->mul_content_subject ?>" href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>">
                                            <img alt="<?= $value->mul_content_subject ?>" src="<?= $srcImg ?>">
                                        </a>
                                        <figcaption class="grid-thumbnail--caption" style="padding-left: 19px;">
                                            <h5 style="height: 40px;overflow: hidden;"><a target="_blank" title="<?= $value->mul_content_subject ?>" href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>"><?= $value->mul_content_subject ?></a></h5>
                                        </figcaption>
                                    </div>
                                </div>

                                <?php
                            }
                            ?>
                            <div class="viewall pr-15 pbem-1"><a href="<?= BASE_URL . 'knowledge/activity' ?>" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                        </div>
                        <!-- กิจกรรมห้องเรียน -->

                        <!-- ลูกเสือ-เนตรนารี -->
                        <div class="row hide" id="media-portfolio" data-type="tabs">
                            <?php
                            foreach ($boyscout as $key => $value) {
                                ?>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 mobile-hidden">
                                    <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>" title="<?= $value->mul_content_subject ?>" class="grid-thumbnail css-crop-150" target="_blank">
                                        <?php
                                        //$srcImg = 'http://www.trueplookpanya.com/data/product/media/'.$value->edu_photo_file[0]->photo_path.'/260/260/'.$value->edu_photo_file[0]->edu_photo_files_name;
                                        $srcImg = $value->thumb;
                                        ?>
                                        <img src="<?= $srcImg ?>" alt="<?= $value->mul_content_subject ?>">
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <h5 class="content-2-line"><a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>" title="<?= $value->mul_content_subject ?>" target="_blank"><?= $value->mul_content_subject ?></a></h5>
                                    </figcaption>
                                </div>
                                <div class="pl-0 pr-0 col-sm-12 mobile-show">
                                    <div class="col-lg-3 col-md-3 col-sm-3"> 
                                        <a target="_blank" class="grid-thumbnail css-crop-110" title="<?= $value->mul_content_subject ?>" href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>">
                                            <img alt="<?= $value->mul_content_subject ?>" src="<?= $srcImg ?>">
                                        </a>
                                        <figcaption class="grid-thumbnail--caption" style="padding-left: 19px;">
                                            <h5 style="height: 40px;overflow: hidden;"><a target="_blank" title="<?= $value->mul_content_subject ?>" href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>"><?= $value->mul_content_subject ?></a></h5>
                                        </figcaption>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="viewall pr-15 pbem-1"><a href="<?= BASE_URL . 'knowledge/activity' ?>" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                        </div>
                        <!-- ลูกเสือ-เนตรนารี -->
                        
                         <!-- ความรู้ปฐมวัย -->
                        <div class="row show" id="media-child" data-type="tabs">
                            <?php
                            foreach ($childhood as $key => $value) {
                                ?>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 mobile-hidden">
                                    <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>" title="<?= $value->mul_content_subject ?>" class="grid-thumbnail css-crop-150" target="_blank">
                                        <?php
                                        //$srcImg = 'http://www.trueplookpanya.com/data/product/media/'.$value->edu_photo_file[0]->photo_path.'/260/260/'.$value->edu_photo_file[0]->edu_photo_files_name;
                                        $srcImg = $value->thumb;
                                        ?>
                                        <img src="<?= $srcImg ?>" alt="<?= $value->mul_content_subject ?>">
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <h5 class="content-2-line"><a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>" title="<?= $value->mul_content_subject ?>" target="_blank"><?= $value->mul_content_subject ?></a></h5>
                                    </figcaption>
                                </div>
                                <div class="pl-0 pr-0 col-sm-12 mobile-show">
                                    <div class="col-lg-3 col-md-3 col-sm-3"> 
                                        <a target="_blank" class="grid-thumbnail css-crop-110" title="<?= $value->mul_content_subject ?>" href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>">
                                            <img alt="<?= $value->mul_content_subject ?>" src="<?= $srcImg ?>">
                                        </a>
                                        <figcaption class="grid-thumbnail--caption" style="padding-left: 19px;">
                                            <h5 style="height: 40px;overflow: hidden;"><a target="_blank" title="<?= $value->mul_content_subject ?>" href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id ?><?= ($value->mul_source_id == ''?'':'-'.$value->mul_source_id) ?>"><?= $value->mul_content_subject ?></a></h5>
                                        </figcaption>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="viewall pr-15 pbem-1"><a href="<?= BASE_URL . 'knowledge/activity' ?>" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                        </div>
                        <!-- ความรู้ปฐมวัย -->
                        
                    </div>
                </div>


            </div>                    
            <!-- กิจกรรมทักษะ -->
            <!-- INFOGRAPHIC -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 tablet-hide">
                <h2 class="h2">infographic</h2>
                <a href="<?= $infographic['link'] ?>" alt="<?= $infographic['title'] ?>" title="<?= $infographic['title'] ?>" class="round infographic" target="_blank">
                    <img src="<?= $infographic['image'] ?>" alt="<?= $infographic['title'] ?>">
                </a>
                <div class="viewall pr-15 pbem-1"><a href="http://www.trueplookpanya.com/infographic" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
            </div>
            <!-- INFOGRAPHIC -->
        </div>
    </div>
</section>

<section class="webboard-home">
    <div class="container">
        <div class="row pb-10 ">

            <div class="col-lg-8" id="webboard-all-tabs">
                <h2 class="h2">Webboard</h2>
                <nav class="pills-nav">
                    <nav class="pills-nav">
                        <?php
                        if ($webboard) {
                            $loop = 0;
                            foreach ($webboard as $cat_id => $value) {
                                ?><a data-type="webboard_room_<?= $cat_id ?>" title="<?= $value['title'] ?>" class="<?= ($loop == 0) ? 'active' : '' ?>" target="_blank" ><?= $value['title'] ?></a><?php
                                $loop++;
                            }
                        }
                        ?>
                    </nav>
                </nav>
                <?php
                if ($webboard) {
                    $loop = 0;
                    foreach ($webboard as $cat_id => $valueArr) {
                        if (isset($valueArr['webboard']) and $valueArr['webboard']) {
                            ?><div class="<?= ($loop == 0) ? 'show' : 'hide' ?>"id="webboard_room_<?= $cat_id ?>" data-type="tabs"><ul class="list-links--strape"><?php
                            foreach ($valueArr['webboard'] as $value) {
                                ?><li class="list-link--item"><a href="<?= $value->link; ?>" title="<?= $value->title; ?>" target="_blank" ><i class="icon-document"></i> <?= cutStr($value->title, 70) ?></a></li><?php
                                    }
                                    ?></ul>
                                <div class="viewall mobile-hidden"><a href="<?= base_url() ?>true/webboard_list.php?cateid=<?= $cat_id ?>"  target="_blank" title="ดูทั้งหมด">ดูทั้งหมด</a></div>
                                <div class="mobile-viewall mt-20 mb-20 mobile-show" style="background-color:#e02125"><a href="<?= base_url() ?>true/webboard_list.php?cateid=<?= $cat_id ?>"  target="_blank" title="ดูทั้งหมด">ดูทั้งหมด</a></div>
                            </div><?php
                        }
                        $loop++;
                    }
                }
                ?>
            </div>
            <div class="col-lg-4" id="ads--section">
                <div class="row">
                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
                        <div class="pt-10 pb-20">
                            <nav class="social-nav" style="padding-left: 0;background: #f0f0f0;border-radius: 7px;padding-top: 5px;padding-bottom: 5px;text-align: center">
                                <a style="margin-right:.1em"><span class="" style="font-family: 'trueplookpanya', Helvetica, sans-serif;font-size: 24px;font-weight: bold;">Follow Trueplookpanya </span></a>
                                <a style="margin-right:.1em" target="_blank" href="https://www.facebook.com/534110370093207" title="Facebook"><i style="font-size: 32px" class="fa fa-facebook-square"></i></a>
                                <a style="margin-right:.1em" target="_blank" href="https://twitter.com/trueplookpanya" title="Twitter"><i style="font-size: 32px" class="fa fa-twitter-square"></i></a>
                                <a style="margin-right:.1em" target="_blank" href="http://www.youtube.com/user/trueplookpanyadotcom" title="Youtube"><i style="font-size: 32px" class="fa fa-youtube-square"></i></a>
<!--                                <span>
                                    <script type="text/javascript" src="//media.line.me/js/line-button.js?v=20140411" ></script>
                                    <script type="text/javascript">
                                        new media_line_me.LineButton({"pc": true, "lang": "en", "type": "d", "text": "", "withUrl": true});
                                    </script>
                                </span>-->
                            </nav>
                        </div>
                        <div class="col-xs-12 pl-0 pr-0">
                            <div class="fb-page" data-href="https://www.facebook.com/534110370093207/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/534110370093207/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/534110370093207/">Plook Education</a></blockquote></div>
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.7&appId=704799662982418";
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                        </div>
                        <!-- INFOGRAPHIC -->

                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 tablet-show">
                        <h2 class="h2">infographic</h2>
                        <a href="<?= $infographic['link'] ?>" alt="<?= $infographic['title'] ?>" title="<?= $infographic['title'] ?>" class="round infographic" target="_blank">
                            <img src="<?= $infographic['image'] ?>" alt="<?= $infographic['title'] ?>">
                        </a>
                        <div class="viewall pr-15 pbem-1"><a href="http://www.trueplookpanya.com/infographic" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
//config swiper pattern start....
if ($isDetect == "mobile") {
    ?>
    <script>
        var swipermb = new Swiper('.zone-mobile', {
            pagination: '.zone-mobile-page',
            paginationClickable: true,
            centeredSlides: true,
            autoplay: 5000,
            autoplayDisableOnInteraction: true,
            slidesPerView: 'auto',
            loop: true
        });
    </script>
    <?php
}
if ($isDetect == "tablet") {
    ?>
    <script>
        var swipertb = new Swiper('.zone-tablet', {
            pagination: '.zone-tablet-page',
            paginationClickable: true,
            centeredSlides: true,
            autoplay: 5000,
            autoplayDisableOnInteraction: true,
            slidesPerView: 'auto',
            loop: true
        });
    </script>
    <?php
} else {
    ?>
    <script>
        var swiper = new Swiper('.zone', {
            pagination: '.media-zone-page',
            paginationClickable: true,
            centeredSlides: true,
            autoplay: 3000,
            autoplayDisableOnInteraction: true,
            slidesPerView: 'auto',
            loop: true
        });

        $(".zone")
                .mouseover(function () {
                    swiper.stopAutoplay();
                })
                .mouseout(function () {
                    swiper.startAutoplay();
                });
    </script>
    <?php
}
?>
<script>
    var idxsub = '#subject';
    $(document).ready(function (event) {

        $("#subject dd ul").mCustomScrollbar({theme: "dark"});
        $("#level dd ul").mCustomScrollbar({theme: "dark"});
        $("#context dd ul").mCustomScrollbar({theme: "dark"});

        $('#subject dd ul li').click(function () {
            //var cc = $(this).find('span').html();
            $("#subject dd ul li").removeClass("select");
            $(this).addClass("select");
        });

        $('#level dd ul li').click(function () {
            //var cc = $(this).find('span').html();
            $("#level dd ul li").removeClass("select");
            $(this).addClass("select");
        });

        $('#context dd ul li').click(function () {
            //var cc = $(this).find('span').html();
            $("#context dd ul li").removeClass("select");
            $(this).addClass("select");
        });



    });

    $(document).click(function () {
        $("#subject dd ul").hide();
        $("#level dd ul").hide();
        $("#context dd ul").hide();
    });

    /*<-----------------------Subject Select------------------------>*/

    $(idxsub + ".dropdown-content dt").click(function (e) {
        $(idxsub + ".dropdown-content dd ul").toggle();
        
        $("#level dd ul").hide();
        $("#context dd ul").hide();
        
        e.stopPropagation();
    });

    $(idxsub + ".dropdown-content dd ul li").click(function (e) {
        var text = $(this).html();
        $(idxsub + ".dropdown-content dt span").html(text);
        $(idxsub + ".dropdown-content dd ul").hide();
        $("#result").html("Selected value is: " + getSelectedValue());
        e.stopPropagation();

    });
    $(idxsub).bind('click', function (e) {
        var $clicked = $(e.target);
        e.stopPropagation();
        if (!$clicked.parents().hasClass("dropdown-content"))
            $(idxsub + ".dropdown-content dd ul").hide();
    });

    /*<-----------------------Subject Select------------------------>*/


    /*<---------------------Level Select-------------------->*/     
    var idxlevel = '#level';
    $(idxlevel + ".dropdown-content dt").click(function (e) {
        $(idxlevel + ".dropdown-content dd ul").toggle();
        $("#subject dd ul").hide();
        $("#context dd ul").hide();
        
        e.stopPropagation();
    });
    $(idxlevel + ".dropdown-content dd ul li").click(function (e) {
        var text = $(this).html();
        $(idxlevel + ".dropdown-content dt span").html(text);
        $(idxlevel + ".dropdown-content dd ul").hide();
        $("#result").html("Selected value is: " + getSelectedValue());
        e.stopPropagation();

    });
    $(idxlevel).bind('click', function (e) {
        var $clicked = $(e.target);
        if (!$clicked.parents().hasClass("dropdown-content"))
            $(idxlevel + ".dropdown-content dd ul").hide();
        e.stopPropagation();
    });
    /*<--------------------Level Select-------------------->*/

    /*<---------------------Context Select-------------------->*/
    var idxcontext = '#context';
    $(idxcontext + ".dropdown-content dt").click(function (e) {
        $(idxcontext + ".dropdown-content dd ul").toggle();
        var old_select = $(this).find('dt span span').html();
        $("#result").html("Selected value is: " + getSelectedValue(old_select));
        
        $("#subject dd ul").hide();
        $("#level dd ul").hide();
        
        
        e.stopPropagation();
    });
    $('#context').on('click', 'dd ul li', function (e) {
        var text = $(this).html();
        $(idxcontext + ".dropdown-content dt span").html(text);
        $(idxcontext + ".dropdown-content dd ul").hide();
        //$("#result").html("Selected value is: " + getSelectedValue());
        e.stopPropagation();

    });
    $(idxcontext).bind('click', function (e) {
        var $clicked = $(e.target);
        if (!$clicked.parents().hasClass("dropdown-content"))
            $(idxcontext + ".dropdown-content dd ul").hide();
        e.stopPropagation();
    });
    /*<--------------------Context Select-------------------->*/

    var load = $('#loading-img');
    var context = $('#context');
    function getSelectedValue(old_select) {
        context.css('opacity', '0.5').css('cursor', 'not-allowed');
        load.fadeIn();
        //alert($("#level").find("dt span.value").html());         var text = "";
        var levelX = $("#level").find("dt span .value").html();
        var subX = $("#subject").find("dt span .value").html();
        var contextX = $("#context").find("dt span .value").html();

        text = "/api/knowledgebase/getCat?sid=" + subX + "&lid=" + levelX;

        var text2 = "/api/knowledgebase/getCat?sid=" + subX + "&lid=" + levelX;
        //alert(text2);
        var main_data = {};
        var html5 = "";
        var elm = $("#context").find("dd ul #mCSB_3_container");
        var elmTitle = $("#context").find("dt");
        $.get("/api/knowledgebase/getCat?sid=" + subX + "&lid=" + levelX, function (data) {
            //alert(data);
            if (data !== null) {
                elm.html('');
                elmTitle.html('<dt><span>ค้นหาจากรายสาระ<span class="value">0</span></span></dt>');
                elm.append('<li>ทุกสาระการเรียนรู้<span class="value">0</span></li>');
                $.each(data, function (key, val) {
                    if (parseInt(old_select) === val.mul_category_id) {
                        html5 = "<li class='select'>" + val.mul_category_name + '<span class="value">' + val.mul_category_id + '</span></li>';
                    } else {
                        html5 = '<li>' + val.mul_category_name + '<span class="value">' + val.mul_category_id + '</span></li>';
                    }
                    elm.append(html5);
                });
                load.fadeOut();
                context.css('opacity', '1').css('cursor', 'pointer');
            } else {
                alert('ไม่พบข้อมูล กลุ่มสาระ');
                load.fadeOut();
            }

            $("#result").html("Selected value is: " + text + "<br>" + text2);
        });
        return text;
    }
    var bording_pass = 1;
    $('#sortdata').on('click', function () {
        if (bording_pass) {
            $('#sortdata ul').fadeOut(100);
            bording_pass = 0;
        } else {
            $('#sortdata ul').fadeIn(100);
            bording_pass = 1;
        }
    });
    $('#sortdata ul li').on('click', function () {
        var text = $(this).html();
        $("#sortdata div span").html(text);
    });

    $('#search-data .btn-yellow').on('click', function () {

        var subX = $("#subject").find("dt span .value").html();
        var levelX = $("#level").find("dt span .value").html();
        var contextX = $("#context").find("dt span .value").html();
        var textX = $("#search-data").find("input").val();
        var text2 = "/api/knowledgebase/searching?sid=" + subX + "&lid=" + levelX + "&ssid=" + contextX + "&q=" + textX;
        //alert(text2);


        if (subX === "" || subX === "0") {
            subX = "";
        } else {
            subX = "sid=" + subX;
        }

        if (levelX === "" || levelX === "0") {
            levelX = "";
        } else {
            levelX = "&lid=" + levelX;
        }

        if (contextX === "" || contextX === "0") {
            contextX = "";
        } else {
            contextX = "&ssid=" + contextX;
        }

        if (textX !== "") {
            textX = "&q=" + textX;
        } else {
            textX = "";
        }

        //alert("<?= base_url() ?>knowledgeV2/search?"+ subX + levelX + contextX + textX);

        window.location.href = "<?= base_url() ?>knowledge/search?" + subX + levelX + contextX + textX;
        window.Location.reload();
    });

    $('#search-data .simple-input').keypress(function (e) {
        if (e.which === 13) {
            var subX = $("#subject").find("dt span .value").html();
            var levelX = $("#level").find("dt span .value").html();
            var contextX = $("#context").find("dt span .value").html();
            var textX = $("#search-data").find("input").val();
            //var text2 = "/api/knowledgebase/searching?sid=" + subX + "&lid=" + levelX + "&cid=" + contextX + "&q=" + textX;
            //alert(text2);


            if (subX === "" || subX === "0") {
                subX = "";
            } else {
                subX = "sid=" + subX;
            }

            if (levelX === "" || levelX === "0") {
                levelX = "";
            } else {
                levelX = "&lid=" + levelX;
            }

            if (contextX === "" || contextX === "0") {
                contextX = "";
            } else {
                contextX = "&ssid=" + contextX;
            }

            if (textX !== "") {
                textX = "&q=" + textX;
            } else {
                textX = "";
            }

            //alert("<?= base_url() ?>knowledgeV2/search?"+ subX + levelX + contextX + textX);

            window.location.href = "<?= base_url() ?>knowledge/search?" + subX + levelX + contextX + textX;
            window.Location.reload();
        }
    });

    $('[data-toggle="tooltip"]').tooltip({html: true});
</script>