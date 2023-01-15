<?php
$this->template->add_stylesheet('assets/css/custom_v2015.css');
$this->template->add_stylesheet('assets/css/bootstrap/css/bootstrap.css');
$this->template->add_javascript('assets/css/bootstrap/js/bootstrap.min.js');

//pattern include start....
//$this->template->add_stylesheet('assets/swiper/dist/css/swiper.min.css');
//$this->template->add_javascript('assets/swiper/dist/js/swiper.js');
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
    <?php
    //echo $isDetect;
    //echo file_get_contents(base_url() . 'global/pattern/index/2/' . $isDetect);
    //echo file_get_contents("http://www.trueplookpanya.com/global/pattern/index/2/" . $isDetect);
    //echo $isDetect;
    ?>
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
                                            if ($value->mul_category_id == $_GET['sid']) {
                                                ?>
                                                <li class="select"><?= $value->mul_category_name ?><span class="value"><?= $value->mul_category_id ?></span></li>
                                                <?php
                                            } else {
                                                ?>
                                                <li><?= $value->mul_category_name ?><span class="value"><?= $value->mul_category_id ?></span></li>    
                                                <?php
                                            }
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
                                            if ($value->mul_level_id == $_GET['lid']) {
                                                ?>
                                                <li class="select"><?= $value->mul_level_name ?><span class="value"><?= $value->mul_level_id ?></span></li>
                                                <?php
                                            } else {
                                                ?>    
                                                <li><?= $value->mul_level_name ?><span class="value"><?= $value->mul_level_id ?></span></li>
                                                <?php
                                            }
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
<!--                <span id="result"></span>
                <span id="contentdivtest"></span>-->
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

<!--<span id="result"></span>-->
            </div>

        </div>
    </div>
</section>


<section class="main-knowledge">
    <div class="container">
        <div class="row">
            <!-- กิจกรรมทักษะ -->


            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 variety-desktop" id="variety-all-tabs">
                <div id="css-search" class="box-border">
                    <h2 class="col-lg-12 col-xs-12 col-sm-12 col-md-12 h2 pl-0 pr-0">
                        <span id="sortdata">
<!--                            <div style="float: left;width: 70%;font-size: 38px"><?= $select_default['nav_search'] ?></div>
                            <div style="float: right;width: 30%">
                                <span class="dropdown-sort">จัดเรียงตาม</span>
                                <ul>
                                    <li class="lastest">ล่าสุด<span class="value">last</span></li>
                                    <li class="hits">ยอดนิยม<span class="value">hit</span></li>
                                </ul>
                            </div>-->
                            <div class="col-lg-9 col-xs-9 col-md-9 col-sm-9 pl-0 pr-0 title-head-section" style="float: left;"><?= $select_default['nav_search'] ?></div>
                            <div class="col-lg-3 col-xs-3 col-md-3 col-sm-3 pl-0 pr-0" style="float: right">
                                <span class="dropdown-sort">จัดเรียงตาม</span>
                                <ul>
                                    <li class="lastest">ล่าสุด<span class="value">last</span></li>
                                    <li class="hits">ยอดนิยม<span class="value">hit</span></li>
                                </ul>
                            </div>
                        </span>
                    </h2>
                    <nav id="variety-nav" class="pills-nav">
                        <a data-type="media-all" title="เนื้อหาทั้งหมด" class="active">เนื้อหาทั้งหมด</a>
                        <a data-type="media-news" title="เนื้อหาประเภทคลิปวิดีโอ" >เนื้อหาประเภทคลิปวิดีโอ</a>
                        <a data-type="media-prototype" title="เนื้อหาประเภทอื่น">เนื้อหาประเภทอื่น</a>
                    </nav>
                    <div class="variety-div-tabs">
                        <!-- all -->
                        <div class="row show" id="media-all" data-type="tabs">
                            <?php
                            if (!empty($media_all['all'])) {
                                foreach ($media_all['all'] as $key => $value) {
                                    ?>
                                    <div id="search-ex" data-views="<?= str_replace(',', '', $value['viewcount']) ?>" data-date="<?= $value['date_post'] ?>" class="news-all col-lg-3 col-md-3 col-sm-3 col-xs-12 mobile-hidden">
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
                                    <div data-views="<?= str_replace(',', '', $value['viewcount']) ?>" data-date="<?= $value['date_post'] ?>" class="news-all pl-0 pr-0 col-sm-12 mobile-show">
                                        <div class="col-lg-3 col-md-3 col-sm-6"> 
                                            <a target="_blank" class="grid-thumbnail css-crop-110" title="<?= $value['content_subject'] ?>" href="<?= $value['content_url'] ?>">
                                                <img alt="<?= $value['content_subject'] ?>" src="<?= $srcImg ?>">
                                            </a>
                                            <figcaption class="grid-thumbnail--caption" style="padding-left: 19px;">
                                                <h5 style="height: 40px;overflow: hidden;"><a target="_blank" title="<?= $value['content_subject'] ?>" href="<?= $value['content_url'] ?>"><?= $value['content_subject'] ?></a></h5>
                                                <p style="color: #939498;font-size: 11.9px;"><?= $value['view_show'] ?> views | <?= $value['date_short'] ?> </p>
                                            </figcaption>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="load-more-all col-lg-12 col-md-12 col-sm-12 col-xs-12 viewall pr-15 pbem-1"><a style="width: 100%;text-align: center;font-size: 23px;height: 30px" title="ดูทั้งหมด">โหลดเพิ่มเติม <img src="assets/img-ex-ku/ajax-loader.gif" id="loading-more0" style="display: none"></a></div>
                                <?php
                            }
                            ?>
                        </div>
                        <!-- all --> 
                        <!-- clip -->
                        <div class="row hide" id="media-news" data-type="tabs">
                            <?php
                            if (!empty($media_v['clip'])) {
                                foreach ($media_v['clip'] as $key => $value) {
                                    ?>
                                    <div id="search-ex" data-views="<?= str_replace(',', '', $value['viewcount']) ?>" data-date="<?= $value['date_post'] ?>" class="news-vdo col-lg-3 col-md-3 col-sm-3 col-xs-12 mobile-hidden">
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
                                    <div data-views="<?= str_replace(',', '', $value['viewcount']) ?>" data-date="<?= $value['date_post'] ?>" class="news-vdo pl-0 pr-0 col-sm-12 mobile-show">
                                        <div class="col-lg-3 col-md-3 col-sm-6"> 
                                            <a target="_blank" class="grid-thumbnail css-crop-110" title="<?= $value['content_subject'] ?>" href="<?= $value['content_url'] ?>">
                                                <img alt="<?= $value['content_subject'] ?>" src="<?= $srcImg ?>">
                                            </a>
                                            <figcaption class="grid-thumbnail--caption" style="padding-left: 19px;">
                                                <h5 style="height: 40px;overflow: hidden;"><a target="_blank" title="<?= $value['content_subject'] ?>" href="<?= $value['content_url'] ?>"><?= $value['content_subject'] ?></a></h5>
                                                <p style="color: #939498;font-size: 11.9px;"><?= $value['view_show'] ?> views | <?= $value['date_short'] ?> </p>
                                            </figcaption>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="load-more-news col-lg-12 col-md-12 col-sm-12 col-xs-12 viewall pr-15 pbem-1"><a style="width: 100%;text-align: center;font-size: 23px;height: 30px" title="ดูทั้งหมด">โหลดเพิ่มเติม <img src="assets/img-ex-ku/ajax-loader.gif" id="loading-more1" style="display: none"></a></div>
                                <?php
                            }
                            ?>
                        </div>
                        <!-- clip --> 
                        <!-- other -->
                        <div class="row hide" id="media-prototype" data-type="tabs">
                            <?php
                            if (!empty($media_t['other'])) {
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
                                                <p style="color: #939498;font-size: 11.9px;"><?= $value['view_show'] ?> views | <?= $value['date_short'] ?> </p>
                                            </figcaption>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="load-more-prototype col-lg-12 col-md-12 col-sm-12 col-xs-12 viewall pr-15 pbem-1"><a style="width: 100%;text-align: center;font-size: 23px;height: 30px" title="ดูทั้งหมด">โหลดเพิ่มเติม <img src="assets/img-ex-ku/ajax-loader.gif" id="loading-more2" style="display: none"></a></div>    
                                <?php
                            }
                            ?>

                        </div>
                        <!-- other -->
                    </div>
                </div>


            </div>                    
            <!-- กิจกรรมทักษะ -->
            <!-- คลังข้อสอบที่เกี่ยวข้อง -->


            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

                <div class="col-lg-12 col-xs-12 col-sm-6 col-md-12">
                    <?php
                    if (!empty($exam_relate['data'])) {
                        ?>

                        <h2 class="col-lg-12 col-xs-12 col-sm-12 col-md-12 h2 pl-0 pr-0">คลังข้อสอบที่เกี่ยวข้อง</h2>
                        <?php
                        foreach ($exam_relate['data'] as $key => $value) {
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0">
                                <figure class="exam-list--item"><a target="_blank" href="<?= $value['url'] ?>" title="Thumbnail" class="exam-list--thumb"><img src="<?= $value['thumbnail'] ?>"></a>
                                    <figcaption class="exam-list--body">
                                        <a class="css-text-limit-one" target="_blank" href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>"><?= $value['topic'] ?></a>
                                        <p><?= $value['mul_level_name'] ?> | เข้าชม : <?= $value['viewcount'] ?> ครั้ง</p>
                                    </figcaption>
                                </figure>
                            </div>
                            <?php
                        }
                        ?>            

                        <div class="viewall col-lg-12 col-xs-12 col-sm-12 col-md-12 pr-15 pbem-1"><a href="<?= BASE_URL . 'examination/index/' ?><?= $select_default['level']['id'] . '/' . $select_default['subject']['id'] ?>" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>
                        <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12 index-line-grey"></div>

                        <?php
                    }
                    ?>
                </div>
                <div id="webboard" class="col-lg-12 col-sm-6 col-md-12 col-xs-12">
                    <h2 class="col-lg-12 col-xs-12 col-sm-12 col-md-12 h2 pl-0 pr-0">Webboard</h2>
                    <?php
                    if (!empty($webboard[9]['webboard'])) {
                        foreach ($webboard[9]['webboard'] as $key => $value) {
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pl-0">
                                <figure class="exam-list--item"><a target="_blank" href="<?= $value->link ?>" title="Thumbnail" class="exam-list--thumb"><i class="icon-document"></i></a>
                                    <figcaption class="exam-list--body">
                                        <a  target="_blank" href="<?= $value->link ?>" title="<?= $value->title ?>">
                                            <div class="css-text-limit-two"><?= $value->title ?></div>
                                        </a>
                                        <p><span style="float: left;color: #28cbf7;"><?= $value->display_name ?></span> <span style="float: right"><?= $value->nums_reply ?> comments <img src="/assets/img-mocup/icon_comments.png"></span></p>
                                    </figcaption>
                                </figure>
                            </div>

                            <?php
                            if ($key < 4) {
                                echo "<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12 index-line-grey'></div>";
                            }
                        }
                    }
                    ?>
                    <div class="col-lg-12 col-xs-12 index-line-grey"></div>
                    <div class="col-lg-12 col-xs-12 viewall pr-0 pt-10 pbem-1"><a href="<?= BASE_URL ?>true/webboard_list.php?cateid=10" title="ดูทั้งหมด" target="_blank">ดูทั้งหมด</a></div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="pt-10 pb-20">
                                <nav class="social-nav desktop-hidden" style="background: #f0f0f0;border-radius: 7px;padding-top: 5px;padding-bottom: 5px;text-align: center;padding-left: 0">
                                    <a><span class="" style="font-family: 'trueplookpanya', Helvetica, sans-serif;font-size: 24px;font-weight: bold;">Follow Trueplookpanya </span></a>
                                    <a target="_blank" href="https://www.facebook.com/TruePlookpanya" title="Facebook"><i style="font-size: 32px" class="fa fa-facebook-square"></i></a>
                                    <a target="_blank" href="https://twitter.com/trueplookpanya" title="Twitter"><i style="font-size: 32px" class="fa fa-twitter-square"></i></a>
                                    <a target="_blank" href="http://www.youtube.com/user/trueplookpanyadotcom" title="Youtube"><i style="font-size: 32px" class="fa fa-youtube-square"></i></a>
                                </nav>
                                <nav class="social-nav mobile-hidden tablet-hide" style="padding-left: 0;background: #f0f0f0;border-radius: 7px;padding-top: 5px;padding-bottom: 5px;text-align: center">
                                    <a style="margin-right:.1em"><span class="" style="font-family: 'trueplookpanya', Helvetica, sans-serif;font-size: 24px;font-weight: bold;">Follow Trueplookpanya </span></a>
                                    <a style="margin-right:.1em" target="_blank" href="https://www.facebook.com/TruePlookpanya" title="Facebook"><i style="font-size: 32px" class="fa fa-facebook-square"></i></a>
                                    <a style="margin-right:.1em" target="_blank" href="https://twitter.com/trueplookpanya" title="Twitter"><i style="font-size: 32px" class="fa fa-twitter-square"></i></a>
                                    <a style="margin-right:.1em" target="_blank" href="http://www.youtube.com/user/trueplookpanyadotcom" title="Youtube"><i style="font-size: 32px" class="fa fa-youtube-square"></i></a>
                                </nav>
                            </div>
                            <div class="row">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- คลังข้อสอบที่เกี่ยวข้อง -->
        </div>
    </div>
</section>
<script>
    var idxsub = '#subject';

    $(document).ready(function (event) {

        $("#subject dd ul").mCustomScrollbar({theme: "dark"});
        $("#level dd ul").mCustomScrollbar({theme: "dark"});
        $("#context dd ul").mCustomScrollbar({theme: "dark"});


        var load_page = 40;


        var box_cout_vdo = $('#media-news').find('.news-vdo').length / 2;
        box_cout_vdo = (box_cout_vdo - 1) + 1;

        $.get("/api/knowledgebase/loadmore?type=all&sid=<?php echo $_GET['sid']; ?>&ssid=<?php echo $_GET['ssid']; ?>&cid=<?php echo $_GET['cid']; ?>&lid=<?php echo $_GET['lid']; ?>&start=" + box_cout_vdo + "&end=" + load_page, function (data) {
            if (data['clip'] === null) {
                $('#media-all .load-more-all').hide();
            }
        });

        $.get("/api/knowledgebase/loadmore?type=v&sid=<?php echo $_GET['sid']; ?>&ssid=<?php echo $_GET['ssid']; ?>&cid=<?php echo $_GET['cid']; ?>&lid=<?php echo $_GET['lid']; ?>&start=" + box_cout_vdo + "&end=" + load_page, function (data) {
            if (data['clip'] === null) {
                $('#media-news .load-more-news').hide();
            }
        });

        var box_cout_content = $('#media-prototype').find('.media-content').length / 2;
        box_cout_content = (box_cout_content - 1) + 1;
        $.get("/api/knowledgebase/loadmore?type=t&sid=<?php echo $_GET['sid']; ?>&ssid=<?php echo $_GET['ssid']; ?>&cid=<?php echo $_GET['cid']; ?>&lid=<?php echo $_GET['lid']; ?>&start=" + box_cout_content + "&end=" + load_page, function (data) {
            if (data['other'] === null) {
                $('#media-prototype .load-more-prototype').hide();
            }

        });

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


    /*<---------------------Level Select-------------------->*/     var idxlevel = '#level';
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
        //alert(cc);

        $("#subject dd ul").hide();
        $("#level dd ul").hide();

        $("#result").html("Selected value is: " + getSelectedValue(old_select));

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

        var main_data = {};
        var html5 = "";
        var elm = $("#context").find("dd ul #mCSB_3_container");
        var elmTitle = $("#context").find("dt");

        $.get("/api/knowledgebase/getCat?sid=" + subX + "&lid=" + levelX, function (data) {
            if (data !== null) {
                elm.html('');
                elmTitle.html('<dt><span>ค้นหาจากรายสาระ<span class="value">0</span></span></dt>');
                elm.append('<li>ทุกสาระการเรียนรู้<span class="value">0</span></li>');

                $.each(data, function (key, val) {
                    //console.log(old_select+":"+val.mul_category_id);

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
        var text = "";

        $("#sortdata div span").html("");
        text = $(this).html();
        $("#sortdata div span").html(text);

    });
    $('#sortdata ul .lastest').on('click', function () {


        var info0 = $('#media-all');
        info0.find('.news-all').sort(function (a, b) {
            return +new Date(b.dataset.date) - +new Date(a.dataset.date);
        }).appendTo(info0);
        info0.find('.load-more-all').appendTo(info0);

        var info = $('#media-news');
        info.find('.news-vdo').sort(function (a, b) {
            return +new Date(b.dataset.date) - +new Date(a.dataset.date);
        }).appendTo(info);
        info.find('.load-more-news').appendTo(info);

        var info2 = $('#media-prototype');
        info2.find('.media-content').sort(function (a, b) {
            return +new Date(b.dataset.date) - +new Date(a.dataset.date);
        }).appendTo(info2);
        info2.find('.load-more-prototype').appendTo(info2);


    });
    $('#sortdata ul .hits').on('click', function () {

        var info0 = $('#media-all');
        info0.find('.news-all').sort(function (a, b) {
            return +b.dataset.views - +a.dataset.views;
        }).appendTo(info0);
        info0.find('.load-more-all').appendTo(info0);

        var info = $('#media-news');
        info.find('.news-vdo').sort(function (a, b) {
            return +b.dataset.views - +a.dataset.views;
        }).appendTo(info);
        info.find('.load-more-news').appendTo(info);

        var info2 = $('#media-prototype');
        info2.find('.media-content').sort(function (a, b) {
            return +b.dataset.views - +a.dataset.views;
        }).appendTo(info2);
        info2.find('.load-more-prototype').appendTo(info2);

    });

    $('.load-more-all').on('click', function () {

        var html5 = "";
        var load_page = 40;
        var info = $('#media-all');


        var box_cout_vdo = $('#media-all').find('.news-all').length / 2;
        box_cout_vdo = box_cout_vdo + 1;

        var load = $('#loading-more0');
        console.log(load);

        load.fadeIn();

        //info.find('.load-more-news').hide();
        //alert("/api/knowledgebase/loadmore?type=v&sid=&cid=&lid=&start=" + box_cout_vdo + "&end=" + load_page);
        $.get("/api/knowledgebase/loadmore?type=all&q=<?php echo $_GET['q']; ?>&sid=<?php echo $_GET['sid']; ?>&ssid=<?php echo $_GET['ssid']; ?>&cid=<?php echo $_GET['cid']; ?>&lid=<?php echo $_GET['lid']; ?>&start=" + box_cout_vdo + "&end=" + load_page, function (data) {

            //alert(box_cout_vdo);
            var main_data = {};

//            var tt = "/api/knowledgebase/loadmore?type=v&sid=<?php echo $_GET['sid']; ?>&ssid=<?php echo $_GET['ssid']; ?>&cid=<?php echo $_GET['cid']; ?>&lid=<?php echo $_GET['lid']; ?>&start=" + box_cout_vdo + "&end=" + load_page;
//            
//            console.log(tt);

            if (data['all'] !== null) {


                var check_count = 0;

                $.each(data['all'], function (key, val) {

                    html5 = "";

                    var num = val.viewcount;
                    var viewcountX = num.toString();

                    html5 += "<div id='search-ex' data-views='" + viewcountX.replace(/,/g, "") + "' data-date='" + val.date_post + "' class='news-all col-lg-3 col-md-3 col-sm-3 col-xs-12 mobile-hidden'>";
                    html5 += "<a href='" + val.content_url + "' title='" + val.content_subject + "' class='grid-thumbnail css-crop-150 box' target='_blank'>";
                    html5 += "<img src='" + val.thumbnail + "' alt=''>";
                    html5 += "<div class='overlay-red'>";
                    html5 += "<div style='margin-top: -6px'>" + val.cat_level_name + "</div>";
                    html5 += "<div style='margin-top: -14px'>" + val.cat_super_name + "</div>";
                    html5 += "</div>";
                    html5 += "</a>";
                    html5 += "<figcaption class='grid-thumbnail--caption'>";
                    html5 += "<h5 class='content-2-line'><a href='" + val.content_url + "' title='" + val.content_subject + "' target='_blank'>" + val.content_subject + "</a></h5>";
                    html5 += "<p style='color: #939498;font-size: 11.9px;'> " + val.view_show + " views | " + val.date_short + " </p>";
                    html5 += "</figcaption>";
                    html5 += "</div>";

                    html5 += "<div data-views='" + viewcountX.replace(/,/g, "") + "' data-date='" + val.date_post + "' class='news-all pl-0 pr-0 col-sm-12 mobile-show'>";
                    html5 += "<div class='col-lg-3 col-md-3 col-sm-6'>";
                    html5 += "<a target='_blank' class='grid-thumbnail css-crop-110' title='" + val.content_subject + "' href='" + val.content_url + "'>";
                    html5 += "<img alt='" + val.content_subject + "' src='" + val.thumbnail + "'>";
                    html5 += "<figcaption class='grid-thumbnail--caption' style='padding-left: 19px;'>";
                    html5 += "<h5 style='height: 40px;overflow: hidden;'><a target='_blank' title='" + val.content_subject + "' href='" + val.content_url + "'>" + val.content_subject + "</a></h5>";
                    html5 += "<p style='color: #939498;font-size: 11.9px;'> " + val.view_show + " views | " + val.date_short + " </p>";
                    html5 += "</figcaption>";
                    html5 += "</a>";
                    html5 += "</div>";
                    html5 += "</div>";

                    info.append(html5);
                    check_count++;
                });

                if (check_count === load_page) {//load-more-isEnd
                    info.find('.load-more-all').appendTo(info);
                } else {
                    info.find('.load-more-all').html("");
                }

            } else {
                alert('ไม่พบข้อมูล');
                info.find('.load-more-all').html("");
            }
            load.fadeOut();
        });
    });



    $('.load-more-news').on('click', function () {

        var html5 = "";
        var load_page = 40;
        var info = $('#media-news');


        var box_cout_vdo = $('#media-news').find('.news-vdo').length / 2;
        box_cout_vdo = box_cout_vdo + 1;

        var load = $('#loading-more1');
        console.log(load);

        load.fadeIn();

        //info.find('.load-more-news').hide();
        //alert("/api/knowledgebase/loadmore?type=v&sid=&cid=&lid=&start=" + box_cout_vdo + "&end=" + load_page);
        $.get("/api/knowledgebase/loadmore?type=v&q=<?php echo $_GET['q']; ?>&sid=<?php echo $_GET['sid']; ?>&ssid=<?php echo $_GET['ssid']; ?>&cid=<?php echo $_GET['cid']; ?>&lid=<?php echo $_GET['lid']; ?>&start=" + box_cout_vdo + "&end=" + load_page, function (data) {

            //alert(box_cout_vdo);
            var main_data = {};

//            var tt = "/api/knowledgebase/loadmore?type=v&sid=<?php echo $_GET['sid']; ?>&ssid=<?php echo $_GET['ssid']; ?>&cid=<?php echo $_GET['cid']; ?>&lid=<?php echo $_GET['lid']; ?>&start=" + box_cout_vdo + "&end=" + load_page;
//            
//            console.log(tt);

            if (data['clip'] !== null) {


                var check_count = 0;

                $.each(data['clip'], function (key, val) {

                    html5 = "";

                    var num = val.viewcount;
                    var viewcountX = num.toString();

                    html5 += "<div id='search-ex' data-views='" + viewcountX.replace(/,/g, "") + "' data-date='" + val.date_post + "' class='news-vdo col-lg-3 col-md-3 col-sm-3 col-xs-12 mobile-hidden'>";
                    html5 += "<a href='" + val.content_url + "' title='" + val.content_subject + "' class='grid-thumbnail css-crop-150 box' target='_blank'>";
                    html5 += "<img src='" + val.thumbnail + "' alt=''>";
                    html5 += "<div class='overlay-red'>";
                    html5 += "<div style='margin-top: -6px'>" + val.cat_level_name + "</div>";
                    html5 += "<div style='margin-top: -14px'>" + val.cat_super_name + "</div>";
                    html5 += "</div>";
                    html5 += "</a>";
                    html5 += "<figcaption class='grid-thumbnail--caption'>";
                    html5 += "<h5 class='content-2-line'><a href='" + val.content_url + "' title='" + val.content_subject + "' target='_blank'>" + val.content_subject + "</a></h5>";
                    html5 += "<p style='color: #939498;font-size: 11.9px;'> " + val.view_show + " views | " + val.date_short + " </p>";
                    html5 += "</figcaption>";
                    html5 += "</div>";

                    html5 += "<div data-views='" + viewcountX.replace(/,/g, "") + "' data-date='" + val.date_post + "' class='news-vdo pl-0 pr-0 col-sm-12 mobile-show'>";
                    html5 += "<div class='col-lg-3 col-md-3 col-sm-6'>";
                    html5 += "<a target='_blank' class='grid-thumbnail css-crop-110' title='" + val.content_subject + "' href='" + val.content_url + "'>";
                    html5 += "<img alt='" + val.content_subject + "' src='" + val.thumbnail + "'>";
                    html5 += "<figcaption class='grid-thumbnail--caption' style='padding-left: 19px;'>";
                    html5 += "<h5 style='height: 40px;overflow: hidden;'><a target='_blank' title='" + val.content_subject + "' href='" + val.content_url + "'>" + val.content_subject + "</a></h5>";
                    html5 += "<p style='color: #939498;font-size: 11.9px;'> " + val.view_show + " views | " + val.date_short + " </p>";
                    html5 += "</figcaption>";
                    html5 += "</a>";
                    html5 += "</div>";
                    html5 += "</div>";

                    info.append(html5);
                    check_count++;
                });

                if (check_count === load_page) {//load-more-isEnd
                    info.find('.load-more-news').appendTo(info);
                } else {
                    info.find('.load-more-news').html("");
                }

            } else {
                alert('ไม่พบข้อมูล');
                info.find('.load-more-news').html("");
            }
            load.fadeOut();
        });
    });

    $('.load-more-prototype').on('click', function () {

        var html5 = "";
        var load_page_content = 40;

        var info = $('#media-prototype');
        var load = $('#loading-more2');
        console.log(load);
        load.fadeIn();
        var box_cout_content = $('#media-prototype').find('.media-content').length / 2;
        box_cout_content = box_cout_content + 1;

        //alert(sum_load_content);
        $.get("/api/knowledgebase/loadmore?type=t&q=<?php echo $_GET['q']; ?>&sid=<?php echo $_GET['sid']; ?>&ssid=<?php echo $_GET['ssid']; ?>&cid=<?php echo $_GET['cid']; ?>&lid=<?php echo $_GET['lid']; ?>&start=" + box_cout_content + "&end=" + load_page_content, function (data) {

            if (data['other'] !== null) {

                var check_count = 0;
                $.each(data['other'], function (key, val) {

                    html5 = "";
                    var num = val.viewcount;
                    var viewcountX = num.toString();
                    html5 += "<div id='search-ex' data-views='" + viewcountX.replace(/,/g, "") + "' data-date='" + val.date_post + "' class='media-content col-lg-3 col-md-3 col-sm-3 col-xs-12 mobile-hidden'>";
                    html5 += "<a href='" + val.content_url + "' title='" + val.content_subject + "' class='grid-thumbnail css-crop-150 box' target='_blank'>";
                    html5 += "<img src='" + val.thumbnail + "' alt=''>";
                    html5 += "<div class='overlay-red'>";
                    html5 += "<div style='margin-top: -6px'>" + val.cat_level_name + "</div>";
                    html5 += "<div style='margin-top: -14px'>" + val.cat_super_name + "</div>";
                    html5 += "</div>";
                    html5 += "</a>";
                    html5 += "<figcaption class='grid-thumbnail--caption'>";
                    html5 += "<h5 class='content-2-line'><a href='" + val.content_url + "' title='" + val.content_subject + "' target='_blank'>" + val.content_subject + "</a></h5>";
                    html5 += "<p style='color: #939498;font-size: 11.9px;'> " + val.view_show + " views | " + val.date_short + " </p>";
                    html5 += "</figcaption>";
                    html5 += "</div>";

                    html5 += "<div data-views='" + viewcountX.replace(/,/g, "") + "' data-date='" + val.date_post + "' class='media-content pl-0 pr-0 col-sm-12 mobile-show'>";
                    html5 += "<div class='col-lg-3 col-md-3 col-sm-6'>";
                    html5 += "<a target='_blank' class='grid-thumbnail css-crop-110' title='" + val.content_subject + "' href='" + val.content_url + "'>";
                    html5 += "<img alt='" + val.content_subject + "' src='" + val.thumbnail + "'>";
                    html5 += "<figcaption class='grid-thumbnail--caption' style='padding-left: 19px;'>";
                    html5 += "<h5 style='height: 40px;overflow: hidden;'><a target='_blank' title='" + val.content_subject + "' href='" + val.content_url + "'>" + val.content_subject + "</a></h5>";
                    html5 += "<p style='color: #939498;font-size: 11.9px;'> " + val.view_show + " views | " + val.date_short + " </p>";
                    html5 += "</figcaption>";
                    html5 += "</a>";
                    html5 += "</div>";
                    html5 += "</div>";

                    info.append(html5);

                    check_count++;
                });
                //info.find('.load-more-prototype').appendTo(info);
                //alert("cout:" + check_count);

                if (check_count === load_page_content) {//load-more-isEnd
                    info.find('.load-more-prototype').appendTo(info);
                } else {
                    info.find('.load-more-prototype').html("");
                }

            } else {
                alert('ไม่พบข้อมูล');
                info.find('.load-more-prototype').html("");
            }
            load.fadeOut();
        });
    });

    $('#search-data .btn-yellow').on('click', function () {

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

        window.location.assign('<?= site_url("knowledge/search?") ?>' + subX + levelX + contextX + textX);
//        window.location.href = "<?= base_url() ?>knowledge/search?" + subX + levelX + contextX + textX;
//        window.Location.reload();
    });

    $('#search-data .simple-input').keypress(function (e) {
        if (e.which === 13) {
            var subX = $("#subject").find("dt span .value").html();
            var levelX = $("#level").find("dt span .value").html();
            var contextX = $("#context").find("dt span .value").html();
            var textX = $("#search-data").find("input").val();
            //var text2 = "/api/knowledgebase/searching?sid=" + subX + "&lid=" + levelX + "&ssid=" + contextX + "&q=" + textX;
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

            //window.location = ;
            window.location.assign('<?= site_url("knowledge/search?") ?>' + subX + levelX + contextX + textX);


            //window.Location.reload();
        }
    });
</script>
