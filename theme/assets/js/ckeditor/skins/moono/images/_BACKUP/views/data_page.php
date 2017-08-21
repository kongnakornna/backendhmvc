<?php
$this->template->add_stylesheet('assets/css/custom_v2015.css');
$this->template->add_stylesheet('assets/css/bootstrap/css/bootstrap.css');
$this->template->add_javascript('assets/css/bootstrap/js/bootstrap.min.js');

//pattern include start....
$this->template->add_stylesheet('assets/swiper/dist/css/swiper.min.css');
$this->template->add_javascript('assets/swiper/dist/js/swiper.js');
//$this->template->add_stylesheet('assets/css/pattern.css');
//$this->template->add_stylesheet('assets/css/card-hover.css');
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
//$this->template->add_stylesheet('assets/knowledgeV2/select/select.css');
//$this->template->add_stylesheet('assets/custom-scrollbar/jquery.mCustomScrollbar.css');
//$this->template->add_javascript('assets/custom-scrollbar/jquery.mCustomScrollbar.js');
$this->template->add_stylesheet('assets/knowledgeV2/css/knowledgeV2.css');
//$this->template->add_stylesheet('assets/knowledgeV2/css/style.css');
//$this->template->add_javascript('assets/knowledgeV2/js/main.js');

$this->template->add_javascript('assets/js_custom/jquery.sharrre.min.js');

$this->template->add_stylesheet('assets/css/hover.css');
?>
<main class="main home-page campaign-module">
    <?php
    $this->load->view('global/block/header_section/_knowledgeV2');
    ?>
</main>

<section>
    <div class="container">
        <div class="row">
            <!-- กิจกรรมทักษะ -->
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="left-position">
                <div id="css-search" class="box-border">
                    <h2 class="col-lg-12 h2 pr-0 pl-0 mb-0">
                        <span id="sortdata">
                            <div class="title-top-nav"><?=$info->content_subject?></div>
                            <div class="title-top-left">ทีมงานทรูปลูกปัญญา | 22 ส.ค. 2558</div>
                            <div class="title-top-right"> 2k view</div>
                        </span>
                    </h2>
                    
                    <span class="col-lg-12 pr-0 pl-0 mb-0">
                        <div class="button-nav-left">
                            <a class="button-sky-outline mt-5"><?=$info->cat_super_name?></a>
                            <a class="button-sky-fill mt-5">สาร: <?=$info->cat_name?> </a>
                            <a class="button-gray mt-5" ><?=$info->content_stage?></a>
                            <a class="button-gray mt-5" ><?=$info->context_level?></a>
                        </div>
                        <div class="button-nav-right ">
                            <div id="demo1" data-url="http://www.trueplookpanya.com/" data-text="แชร์" data-title="share"></div>
                            <div>    
                                <?= social_media_share_hover() ?>
                            </div>
                        </div>
                    </span>
                    <?php
                    if(!empty($vdo_info)){
                    ?>
                    <div class="col-lg-12 pr-0 pl-0 mt-10 mb-0">
                        <div>
                            <!-- START #player -->
                            <div id="player">
                                <div id="player_area">
                                    <div id="vdo_player_wrapper" style="position: relative; width: 620px; height: 350px;">
                                        <object type="application/x-shockwave-flash" data="http://www.trueplookpanya.com/new/includes/media/jw_player.swf" width="100%" height="100%" bgcolor="#000000" id="vdo_player" name="vdo_player" tabindex="0">
                                            <param name="allowfullscreen" value="true">
                                            <param name="allowscriptaccess" value="always">
                                            <param name="seamlesstabbing" value="true">
                                            <param name="wmode" value="opaque">
                                            <param name="flashvars" value="netstreambasepath=http%3A%2F%2Fwww.trueplookpanya.com%2Fnew%2Fcms_detail%2Fknowledge%2F30490-042969%2F&amp;image=%2Fdata%2Fproduct%2Fmedia%2Fhash_knowledge%2F201510%2F42969%2FVDOA1000042969_320x240.png&amp;controls=controls&amp;id=vdo_player&amp;levels=%5B%5BJSON%5D%5D%5B%7B%22file%22%3A%22http%3A%2F%2Fstatic.trueplookpanya.com%2Ftrueplookpanya%2Fmedia%2Fhash_knowledge%2F201510%2F42969%2FVDOA1000042969_480p.mp4%22%2C%22type%22%3A%22video%2Fmp4%22%2C%22id%22%3A%22source_play%22%2C%22width%22%3A0%2C%22bitrate%22%3A0%7D%5D&amp;file=http%3A%2F%2Fstatic.trueplookpanya.com%2Ftrueplookpanya%2Fmedia%2Fhash_knowledge%2F201510%2F42969%2FVDOA1000042969_480p.mp4&amp;skin=http%3A%2F%2Fwww.trueplookpanya.com%2Fnew%2Fincludes%2Fmedia%2Fskin%2Fnewtubedark.zip&amp;plugins=http%3A%2F%2Flp.longtailvideo.com%2F5%2Frelated%2Frelated.swf%2Chttp%3A%2F%2Flp.longtailvideo.com%2F5%2Fhd%2Fhd.swf&amp;related.file=http%3A%2F%2Fwww.trueplookpanya.com%2Fnew%2Fxml%2Frelate_cms_content%2Fknowledge%2F30490%2F1000%2F9%2F042969%2F&amp;related.onclick=link&amp;related.heading=%E0%B9%80%E0%B8%99%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%B2%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B9%80%E0%B8%81%E0%B8%B5%E0%B9%88%E0%B8%A2%E0%B8%A7%E0%B8%82%E0%B9%89%E0%B8%AD%E0%B8%87&amp;related.pluginmode=HYBRID&amp;hd.file=http%3A%2F%2Fstatic.trueplookpanya.com%2Ftrueplookpanya%2Fmedia%2Fhash_knowledge%2F201510%2F42969%2FVDOA1000042969_720p.mp4&amp;hd.pluginmode=HYBRID&amp;controlbar.position=bottom&amp;logo.file=http%3A%2F%2Fwww.trueplookpanya.com%2Fnew%2Fassets%2Fimages%2Flogo%2Flogo_player.png&amp;logo.link=http%3A%2F%2Fwww.trueplookpanya.com&amp;logo.linktarget=_blank&amp;logo.position=top-left&amp;logo.margin=15&amp;logo.out=0.6&amp;logo.hide=false&amp;volume=90">
                                        </object>
                                        <div id="vdo_player_related" style="position: absolute; z-index: 10; left: 0px; top: 0px;"></div>
                                        <div id="vdo_player_hd" style="position: absolute; z-index: 11; left: 0px; top: 0px;"></div>
                                    </div>
                                </div>
                                <script>
                                    jwplayer(playerID).setup({
                                        'flashplayer': flashPlayerLink,
                                        'width': player_width,
                                        'height': player_height,
                                        'file': vdoFile,
                                        'image': thumbnailImg,
                                        'skin': skinLink,
                                        'controlbar': 'bottom',
                                        'logo.file': base_url + 'assets/images/logo/logo_player.png',
                                        'logo.link': 'http://www.trueplookpanya.com',
                                        'logo.linktarget': '_blank',
                                        'logo.position': 'top-left',
                                        'logo.margin': '15',
                                        'logo.out': '0.6',
                                        'logo.hide': false,
                                        'plugins': {
                                            'related-1': {file: xmlLinkRelate, onclick: 'link', heading: 'เนื้อหาที่เกี่ยวข้อง'},
                                            'hd-2': {file: 'http://static.trueplookpanya.com/trueplookpanya/media/hash_knowledge/201510/42969/VDOA1000042969_720p.mp4'}
                                        }

                                    });</script>
                            </div>
                            <!-- END #player -->
                        </div>
                    </div>
                    <?php 
                    }
                    ?>
                    <div class="col-lg-12 mt-10 pl-0 pr-0">
                        <?=$info->content_text?>
                    </div>

                    <div class="col-lg-12 pl-0 pr-0">
                        <div class="button-nav-left">
                            <span style="font-weight: bold">Tag : </span>
                            <a style="color: #00c7ec">วิชาสังคม </a>,
                            <a style="color: #00c7ec">สาร: การอ่านๆๆๆ 5565 </a>,
                            <a style="color: #00c7ec" >5555555 </a>
                        </div>
                        <div class="button-nav-right">
                            <div id="demo2" data-url="http://www.trueplookpanya.com/teacher" data-text="แชร์" data-title="share"></div>
                            <div>    
                                <?= social_media_share_hover() ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 pl-0 pr-0 mt-10">
                        <div class="fb-comments" data-href="http://www.trueplookpanya.com/" data-numposts="5" style="border: 1px solid #e9eaed"></div>

                        <div id="fb-root"></div>
                        <script>(function (d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (d.getElementById(id))
                                    return;
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.5";
                                fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                    </div>
                    <div class="col-lg-12 pl-0 pr-0 mt-10">
                        <h2 class="h2 pr-0 pl-0 mb-0" style="border-bottom: 0px solid #e02125;font-size: 32px">
                            ข้อสอบแนะนำ
                        </h2>
                       <?php
                            for ($index1 = 0; $index1 < 6; $index1++) {
                       ?>
                        <div class="col-lg-4 hvr-push mt-10">
                            <div class="relate-box-zero">
                                <div class="relate-box-zero-title-top">
                                    <a href="http://www.trueplookpanya.com/new/cms_detail/knowledge/9437-016247/" title="ตัวอักษรภาษาอังกฤษ Q R" target="_self">
                                        ตัวอักษรภาษาอังกฤษ Q R
                                    </a>
                                </div>
                                <div>
                                    <div class="relate-box-zero-icon"><img src="http://www.trueplookpanya.com/new/assets/images/icon/level/03.png" width="30" height="30" alt="" border="0"></div>
                                    <div class="relate-box-zero-view">3,707 views | 5 ก.ค. 53<br><a href="http://www.trueplookpanya.com/true/blog_friend_main.php?friend_blog_id=47" title="เข้าสู่ blog ของ ทีมงานทรูปลูกปัญญา ">ทีมงานทรูปลูกปัญญา</a></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>

                    <div class="col-lg-12 pl-0 pr-0 mt-10">
                        <h2 class="col-lg-12 h2 pr-0 pl-0 mb-0">
                            <span >
                                <div class="relate-box-one-top-title">เรื่องที่แนะนำ</div>
                            </span>
                        </h2>
                        <div class="col-lg-12 swiper-container relate-sile-one mt-10 pl-5 pr-5">
                            <div class="swiper-wrapper">
                                <?php
                                
                                for ($index = 0; $index < 10; $index++) {
                                    ?>
                                    <div class="swiper-slide col-lg-3 relate-box-one">
                                        <div >
                                            <a href="http://www.trueplookpanya.com/new/cms_detail/entertainment/24391" title="7 สถานที่ท่องเที่ยว จัดแสดงไฟ แสง สี เสียง วันคริสต์มาสและปีใหม่" class="grid-thumbnail" target="_blank">
                                                <img class="css-crop-150" src="http://www.trueplookpanya.com/new/cutresize/re/305/170/hash_thumbnail-391-24391/IMG024391_128x96" alt="7 สถานที่ท่องเที่ยว จัดแสดงไฟ แสง สี เสียง วันคริสต์มาสและปีใหม่">
                                            </a>
                                            <figcaption class="grid-thumbnail--caption" style="min-height: 43px;">
                                                <h5 style="overflow: hidden; height: 38px"><a href="http://www.trueplookpanya.com/new/cms_detail/entertainment/24391" title="7 สถานที่ท่องเที่ยว จัดแสดงไฟ แสง สี เสียง วันคริสต์มาสและปีใหม่" target="_blank">7 สถานที่ท่องเที่ยว จัดแสดงไฟ แสง สี เสียง วันคริสต์มาสและปีใหม่</a></h5>
                                            </figcaption>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 pl-0 pr-0 mt-10">
                        <h2 class="col-lg-12 h2 pr-0 pl-0 mb-0">
                            <span>
                                <div class="relate-box-one-top-title">เรื่องที่คุณอาจสนใจ</div>
                            </span>
                        </h2>
                        <div class="col-lg-12 swiper-container relate-sile-two mt-10 pl-5 pr-5">
                            <div class="swiper-wrapper">
                                <?php
                                
                                for ($index = 0; $index < 10; $index++) {
                                    ?>
                                    <div class="swiper-slide col-lg-3 relate-box-one">
                                        <div >
                                            <a href="http://www.trueplookpanya.com/new/cms_detail/entertainment/24391" title="7 สถานที่ท่องเที่ยว จัดแสดงไฟ แสง สี เสียง วันคริสต์มาสและปีใหม่" class="grid-thumbnail" target="_blank">
                                                <img class="css-crop-150" src="http://www.trueplookpanya.com/new/cutresize/re/305/170/hash_thumbnail-391-24391/IMG024391_128x96" alt="7 สถานที่ท่องเที่ยว จัดแสดงไฟ แสง สี เสียง วันคริสต์มาสและปีใหม่">
                                            </a>
                                            <figcaption class="grid-thumbnail--caption" style="min-height: 43px;">
                                                <h5 style="overflow: hidden; height: 38px"><a href="http://www.trueplookpanya.com/new/cms_detail/entertainment/24391" title="7 สถานที่ท่องเที่ยว จัดแสดงไฟ แสง สี เสียง วันคริสต์มาสและปีใหม่" target="_blank">7 สถานที่ท่องเที่ยว จัดแสดงไฟ แสง สี เสียง วันคริสต์มาสและปีใหม่</a></h5>
                                            </figcaption>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="" class="inbox-border index-line-grey-right"></div>
            </div>                    
            <!-- กิจกรรมทักษะ -->

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 hidden-sm pt-10">
                <a href="http://www.trueplookpanya.com/plook/emagazine/web_view.php?id=71" title="เล่มที่ 60 ฉบับเดือน ธันวาคม ปี 2015" class="round" target="_blank">
                    <img src="http://www.trueplookpanya.com/data/product/media/2013/hash_banner/309/309/BANNER_ADS3091930.png">
                </a>

                <h2 class="h2 mb-0" >เนื้อหาที่เกี่ยวข้อง</h2>
                
                <?php
                for ($index2 = 0; $index2 < 5; $index2++) {
                ?>
                
                <div class="col-lg-12 border-higlight">
                    <div class="row col-lg-5"> 
                        <a href="http://www.trueplookpanya.com/new/cms_detail/entertainment/24391" title="7 สถานที่ท่องเที่ยว จัดแสดงไฟ แสง สี เสียง วันคริสต์มาสและปีใหม่" class="" target="_blank">
                            <img class="css-crop-110" src="http://www.trueplookpanya.com/new/cutresize/re/305/170/hash_thumbnail-391-24391/IMG024391_128x96" alt="">
                        </a>
                    </div>
                    <div class="col-lg-7"> 
                        <figcaption>
                            <a href="http://www.trueplookpanya.com/new/cms_detail/entertainment/24391" title="7 สถานที่ท่องเที่ยว จัดแสดงไฟ แสง สี เสียง วันคริสต์มาสและปีใหม่" target="_blank"><b>7 สถานที่ท่องเที่ยว จัดแสดงไฟ</b></a>
                        </figcaption>
                    </div>
                </div>
                <?php
                }
                ?>
                

                <div class="col-lg-12 social-all-right mt-10">
                    <nav class="social-nav pl-0">
                        <a><span>Follow Trueplookpanya </span></a>
                        <a target="_blank" href="https://www.facebook.com/TruePlookpanya" title="Facebook"><i style="font-size: 32px" class="fa fa-facebook-square"></i></a>
                        <a target="_blank" href="https://twitter.com/trueplookpanya" title="Twitter"><i style="font-size: 32px" class="fa fa-twitter-square"></i></a>
                        <a target="_blank" href="http://www.youtube.com/user/trueplookpanyadotcom" title="Youtube"><i style="font-size: 32px" class="fa fa-youtube-square"></i></a>
                    </nav>
                </div>
                <div class="col-lg-12 mt-10">
                    <div class="row">
                        <div class="fb-page" data-href="https://www.facebook.com/AdmissionsbyTruePlookpanya/" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/AdmissionsbyTruePlookpanya/"><a href="https://www.facebook.com/AdmissionsbyTruePlookpanya/">Admissions by TruePlookpanya</a></blockquote></div></div>
                        <div id="fb-root"></div>
                        <script>(function (d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (d.getElementById(id))
                                    return;
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.5";
                                fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var swiper = new Swiper('.relate-sile-one', {
        slidesPerView: 4,
        paginationClickable: true,
        spaceBetween: 10
    });
    var swiper2 = new Swiper('.relate-sile-two', {
        slidesPerView: 4,
        paginationClickable: true,
        spaceBetween: 10
    });
</script>
<script>
    $(function () {
        $('#demo1').sharrre({
            share: {
                googlePlus: true,
                facebook: true,
                twitter: true
            }
        });
        $('#demo2').sharrre({
            share: {
                googlePlus: true,
                facebook: true,
                twitter: true
            }
        });
    });
</script>








