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
$this->template->add_stylesheet('assets/knowledgeV2/css/contentdetail.css');
//$this->template->add_stylesheet('assets/knowledgeV2/css/style.css');
//$this->template->add_javascript('assets/knowledgeV2/js/main.js');

$this->template->add_javascript('assets/js_custom/jquery.sharrre.min.js');

$this->template->add_stylesheet('assets/css/hover.css');

$this->template->add_javascript('assets/video-js/video.js');
$this->template->add_stylesheet('assets/video-js/video-js.css');

$this->template->add_stylesheet('assets/custom-scrollbar/jquery.mCustomScrollbar.css');
$this->template->add_javascript('assets/custom-scrollbar/jquery.mCustomScrollbar.js');
?>
<main class="main home-page campaign-module">
    <?php
    $this->load->view('global/block/header_section/_knowledgeV2');
    ?>
</main>

<section class="main-pading">
    <div class="container">
        <div class="row">
            <!-- กิจกรรมทักษะ -->
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="left-position">
                <div id="css-search" class="box-border">
                    <div class="col-lg-12 col-xs-12 col-sm-12 h2 pr-0 pl-0 mb-0">
                        <h2 >
                            <span id="sortdata">
                                <div class="title-top-nav"><?= $info->content_subject ?></div>
                                <div class="title-top-left"><?= $info->display_name ?> | <?= $info->date_post ?></div>
                                <div class="title-top-right"> <?= $info->viewcount ?> view </div>
                            </span>
                        </h2>
                    </div>
                    <div class="col-lg-12 col-xs-12 col-sm-12 pr-0 pl-0 mb-0">
                        <div class="button-nav-left col-lg-9 col-sm-8 pl-0 pr-0">
                            <div style="width: 100%">
                                <a href="<?= BASE_URL . "knowledgeV2/search?sid=" . $info->cat_super_id ?>" class="button-sky-outline mt-5" target="_blank"><?= $info->cat_super_name ?></a>
                                <a href="<?= BASE_URL . "knowledgeV2/search?lid=" . $info->cat_level_id ?>" class="button-sky-outline mt-5" target="_blank"><?= $info->cat_level_name ?></a>
                                <a href="<?= BASE_URL . "knowledgeV2/search?sid=" . $info->cat_super_id . "&ssid=" . $info->cat_id ?>"class="button-sky-outline mt-5" target="_blank">สาระ : <?= $info->cat_name ?> </a>
                            </div>
                        </div>
                        <div class="button-nav-right col-lg-3 col-sm-4 pl-0 pr-0 mobile-hidden">
                            <div id="demo1" data-url="<?= current_url() ?>" data-text="แชร์" data-title="แชร์"></div>
                            <div>    
                                <?= social_media_share_hover() ?>
                            </div>
                        </div>
                        <div style="width: 100%" class="button-nav-left col-lg-12 col-sm-8 pl-0 pr-0 mt-0" >
                            <div style="width: 100%">
                                <a class="button-gray mt-5" target="_blank"><?= $info->content_stage ?></a>
                                <a title="<?= $info->context_level . " " . $info->context_name ?>" data-toggle="tooltip" href="<?= BASE_URL . "knowledgeV2/search?sid=" . $info->cat_super_id . "&ssid=" . $info->cat_id . "&cid=" . $info->context_id ?>" id="context_hover" target="_blank">
                                    <div class="button-gray mt-5"><?= $info->context_level ?>..</div>
                                </a>
                            </div>
                        </div>

                    </div>    
                    <?php
                    //$info->vdo_play = '';
                    if (!empty($info->vdo_play)) {
                        ?>
                        <div class="col-lg-12 col-xs-12 pr-0 pl-0 mt-10 mb-0">
                            <div>
                                <!-- START #player -->
                                <div id="player">
                                    <center>
                                        <video id="example_video_1" class="video-js vjs-default-skin vjs-big-play-centered"
                                               controls preload="auto" width="auto" height="<?= $info->vdo_height ?>"
                                               poster="<?= $info->display_name ?>"
                                               data-setup='{ "controls": true, "autoplay": true, "preload": "auto" }'>
                                            <source src="<?= $info->vdo_play ?>" type='video/mp4' />                            
                                            <p class="vjs-no-js">To view this<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                                        </video>
                                    </center>
                                </div>
                                <!-- END #player -->
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
                        <?= $info->content_text ?>
                    </div>

                    <div class="col-lg-12 col-xs-12 col-sm-12 pl-0 pr-0">
                        <div class="button-nav-left col-lg-9 col-xs-12 col-sm-8 pl-0 pr-0">
                            <span style="font-weight: bold">Tag : </span>
                            <a href=""></a>
                            <?php
                            $cms_tag = explode(' ', $info->keyword);
                            foreach ($cms_tag as $key => $value) {
                                if ((count($cms_tag) - 1) == $key) {
                                    echo '<a style="color: #00c7ec">' . $value . '</a>';
                                } else {
                                    echo '<a style="color: #00c7ec">' . $value . '</a>,';
                                }
                                ?>
                                <?php
                            }
                            ?>
                        </div>
                        <!--                        <div class="button-nav-right col-lg-3 col-xs-12 col-sm-4 pl-0 pr-0">
                                                    <div id="demo2" data-url="<?= current_url() ?>" data-text="แชร์" data-title="share"></div>
                                                    <div>    
                        <?= social_media_share_hover() ?>
                                                    </div>
                                                </div>-->
                    </div>
                    <div class="col-lg-12 col-xs-12 pl-0 pr-0 mt-10">
                        <div class="fb-comments" data-href="<?= current_url() ?>" data-numposts="5"></div>

                        <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.5&appId=704838176241336";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
                    </div>

                    <div id='loading' style="display: none">
                        <img src="http://rpg.drivethrustuff.com/shared_images/ajax-loader.gif"/>
                    </div>
                    <div class="col-lg-12 col-xs-12 pl-0 pr-0 mt-10" id="examload">
                        <div class="col-lg-12">
                            <div style="text-align: center;">  
                                <img src="assets/knowledgeV2/images/ajax_loader_red_32.gif">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xs-12 pl-0 pr-0 mt-10" id="sildeload">
                        <div class="col-lg-12">
                            <div style="text-align: center;">  
                                <img src="assets/knowledgeV2/images/ajax_loader_red_32.gif">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="" class="inbox-border index-line-grey-right"></div>
            </div>                    
            <!-- กิจกรรมทักษะ -->

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pt-10 tablet-hide" id="right-position">
                <div class="col-lg-12 col-xs-12 pl-0 pr-0">
                    <?php
                    if (!empty($top_banner)) {
                        foreach ($top_banner as $key => $value) {
                            ?>
                            <a href="<?= $value->link ?>" title="<?= $value->title ?>" class="round" target="_blank">
                                <img src="<?= $value->thumbnail ?>">
                            </a>
                            <?php
                        }
                    }
                    ?>
                    <div class="col-lg-12 col-xs-12 pl-0 pr-0" id="vdoload">
                        <div style="text-align: center;">  
                            <img src="assets/knowledgeV2/images/ajax_loader_red_32.gif">
                        </div>
                    </div>
                    <!--                    <div class="col-lg-12 col-xs-12 pl-0 pr-0">
                                            <h2 class="h2 mb-0" >เนื้อหาที่เกี่ยวข้อง</h2>
                                            <div class="mt-5" id="relate_content" style="height:385px;overflow: hidden">
                    <?php
                    if (!empty($right_relate['vdo'])) {
                        foreach ($right_relate['vdo'] as $key => $value) {
                            ?>
                                    
                                                                        <div class="col-lg-12 col-xs-12 hvr-push border-higlight">
                                                                            <a href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>" target="_blank">
                                                                                <div class="row col-lg-5 col-xs-5"> 
                                                                                    <img class="css-crop-110" src="<?= $value['thumbnail'] ?>" alt="">
                                                                                </div>
                                                                                <div class="col-lg-7 col-xs-7" style="height: 57px;overflow: hidden;"> 
                                                                                    <b><?= $value['topic'] ?></b>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                    
                            <?php
                        }
                    }
                    if (!empty($right_relate['content'])) {
                        foreach ($right_relate['content']['data'] as $key => $value) {
                            ?>
                                                                        <div class="col-lg-12 col-xs-12 hvr-push border-higlight">
                                                                            <div class="row col-lg-5"> 
                                                                                <a href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>" class="" target="_blank">
                                                                                    <img class="css-crop-110" src="<?= $value['thumbnail'] ?>" alt="">
                                                                                </a>
                                                                            </div>
                                                                            <div class="col-lg-7"> 
                                                                                <figcaption style="height: 57px;overflow: hidden;">
                                                                                    <a href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>" target="_blank"><b><?= $value['topic'] ?></b></a>
                                                                                </figcaption>
                                                                            </div>
                                                                        </div>
                            <?php
                        }
                    }
                    ?>
                                            </div>
                                        </div>-->
                    <div class="col-lg-12 col-xs-12 social-all-right mt-10">
                        <nav class="social-nav col-lg-12 col-xs-12 pl-0 pr-0">
                            <a ><span>Follow Trueplookpanya </span></a>
                            <a  target="_blank" href="https://www.facebook.com/TruePlookpanya" title="Facebook"><i style="font-size: 32px" class="fa fa-facebook-square"></i></a>
                            <a  target="_blank" href="https://twitter.com/trueplookpanya" title="Twitter"><i style="font-size: 32px" class="fa fa-twitter-square"></i></a>
                            <a target="_blank" href="http://www.youtube.com/user/trueplookpanyadotcom" title="Youtube"><i style="font-size: 32px" class="fa fa-youtube-square"></i></a>
                        </nav>
                    </div>
                    <div class="row col-lg-12 col-xs-12 mt-10">
                        <div  class="fb-page" data-href="https://www.facebook.com/AdmissionsbyTruePlookpanya/" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/AdmissionsbyTruePlookpanya/"><a href="https://www.facebook.com/AdmissionsbyTruePlookpanya/">Admissions by TruePlookpanya</a></blockquote></div></div>
                        <div id="fb-root"></div>
                        <script>(function (d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (d.getElementById(id))
                                    return;
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.5";
                                fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pt-10 tablet-show" id="right-position">
                <div class="col-lg-12 col-xs-12 pl-0 pr-0">
                    <div class="col-md-6">
                        <a href="http://www.trueplookpanya.com/plook/emagazine/web_view.php?id=71" title="เล่มที่ 60 ฉบับเดือน ธันวาคม ปี 2015" class="round" target="_blank">
                            <img src="http://www.trueplookpanya.com/data/product/media/2013/hash_banner/309/309/BANNER_ADS3091930.png">
                        </a>
                    </div>
                    <div class="col-lg-12 col-xs-12 col-md-6 pl-0 pr-0">
                        <h2 class="h2 mb-0" >เนื้อหาที่เกี่ยวข้อง</h2>

                        <?php
                        if (!empty($right_relate['vdo'])) {
                            foreach ($right_relate['vdo'] as $key => $value) {
                                ?>

                                <div class="col-lg-12 col-xs-12 hvr-push border-higlight">
                                    <a href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>" target="_blank">
                                        <div class="row col-lg-5 col-xs-5"> 
                                            <img class="css-crop-110" src="<?= $value['thumbnail'] ?>" alt="">
                                        </div>
                                        <div class="col-lg-7 col-xs-7" style="height: 57px;overflow: hidden;"> 
                                            <b><?= $value['topic'] ?></b>
                                        </div>
                                    </a>
                                </div>

                                <?php
                            }
                        }
                        if (!empty($right_relate['content'])) {
                            foreach ($right_relate['content']['data'] as $key => $value) {
                                ?>
                                <div class="col-lg-12 col-xs-12 hvr-push border-higlight">
                                    <div class="row col-lg-5"> 
                                        <a href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>" class="" target="_blank">
                                            <img class="css-crop-110" src="<?= $value['thumbnail'] ?>" alt="">
                                        </a>
                                    </div>
                                    <div class="col-lg-7"> 
                                        <figcaption style="height: 57px;overflow: hidden;">
                                            <a href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>" target="_blank"><b><?= $value['topic'] ?></b></a>
                                        </figcaption>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pt-10 tablet-show" id="right-position">
                <div class="row col-lg-12 col-xs-12 col-md-6 mt-10">

                    <div  class="fb-page" data-href="https://www.facebook.com/AdmissionsbyTruePlookpanya/" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/AdmissionsbyTruePlookpanya/"><a href="https://www.facebook.com/AdmissionsbyTruePlookpanya/">Admissions by TruePlookpanya</a></blockquote></div></div>
                    <div id="fb-root"></div>
                    <script>(function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id))
                                return;
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.5";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
                    </script>
                </div>
                <div class="col-lg-12 col-xs-12 col-md-6 social-all-right mt-10">
                    <nav class="social-nav col-lg-12 col-xs-12 pl-0 pr-0">
                        <a ><span>Follow Trueplookpanya </span></a>
                        <a  target="_blank" href="https://www.facebook.com/TruePlookpanya" title="Facebook"><i style="font-size: 32px" class="fa fa-facebook-square"></i></a>
                        <a  target="_blank" href="https://twitter.com/trueplookpanya" title="Twitter"><i style="font-size: 32px" class="fa fa-twitter-square"></i></a>
                        <a target="_blank" href="http://www.youtube.com/user/trueplookpanyadotcom" title="Youtube"><i style="font-size: 32px" class="fa fa-youtube-square"></i></a>
                    </nav>
                </div>
            </div>
        </div>
</section>
<script>
    $(document).ready(function (event) {
        $('[data-toggle="tooltip"]').tooltip(
                {
                    'selector': '',
                    'placement': 'top',
                    'container': 'body'
                }
        );


        //$("#examload").click(function () {
        $("#examload").load('<?= site_url('knowledge/v2/ajax-exam/' . $info->content_id . "?sid=" . $info->cat_super_id . "&lid=" . $info->cat_level_id . "&cid=" . $info->cat_id) ?>');
        // });

        // $("#sildeload").click(function () {

        $("#sildeload").load('<?= site_url('knowledge/v2/ajax-upsale-crosssale/' . $info->content_id) ?>');
        //  });

        //$("#vdoload").click(function () {
        //alert();
<?php ?>
        $("#vdoload").load('<?= site_url('knowledge/v2/ajax-vdo/' . $info->content_id . '/' . $info->content_id_child) . '/?sid=' . $info->cat_super_id . '&lid=' . $info->cat_level_id . '&cid=' . $info->context_id ?>');



        //});
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

<script>
//    $("#context_hover")
//            .mouseover(function () {
//                $(this).html($("<span><?= $info->context_level . " " . $info->context_name ?></span>"));
//            });
//            .mouseout(function () {
//                $(this).html($("<span><?= $info->context_level ?></span>").fadeOut(500));
//            });

    //$('[data-toggle="tooltip"]').tooltip({html: true});
</script>











