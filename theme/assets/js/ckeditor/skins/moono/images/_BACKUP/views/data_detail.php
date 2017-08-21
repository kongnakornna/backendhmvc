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

$this->template->add_stylesheet('assets/knowledgeV2/css/audioCss.css');
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
                                <div class="title-top-right"> <?= $info->viewcount ?> views </div>
                            </span>
                        </h2>
                    </div>
                    <div class="col-lg-12 col-xs-12 col-sm-12 pr-0 pl-0 mb-0">
                        <div class="button-nav-left col-lg-9 col-sm-8 pl-0 pr-0">
                            <div style="width: 100%">
                                <?php if ($menu_sid) { ?>
                                    <?php foreach ($menu_sid as $k => $v) { ?>
                                        <a href="<?= BASE_URL . "knowledge/search?sid=" . $v['sid'] ?>" class="button-sky-outline mt-5" target="_blank"><?= $v['sname'] ?></a>
                                    <?php } ?>
                                <?php } if ($menu_lid){ ?>
                                    <?php foreach ($menu_lid as $k => $v) { ?>
                                        <a href="<?= BASE_URL . "knowledge/search?lid=" . $v['lid'] ?>" class="button-sky-outline mt-5" target="_blank"><?= $v['lname'] ?></a>
                                    <?php } ?>
                                <?php } if ($menu_cid) { ?>
                                    <?php foreach ($menu_cid as $k => $v) { ?>
                                        <a href="<?= BASE_URL . "knowledge/search?sid=" . $v['sid'] . "&ssid=" . $v['cid'] ?>"class="button-sky-outline mt-5" target="_blank">สาระ : <?= $v['cname'] ?> </a>
                                    <?php } ?>
                                <?php } ?>
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
                                <?php if (!empty($info->content_stage)) { ?>
                                    <a class="button-gray mt-5" target="_blank"><?= $info->content_stage ?></a>
                                <?php } ?>
                                <?php if ($menu_ssid) { ?>
                                    <?php foreach ($menu_ssid as $k => $v) { ?>
                                        <?php $box = !empty($info->content_stage) ? 3 : 4; ?>
                                        <a class="<?= ($k > $box ? 'box_context' : '') ?>" title="<?= $v['ssname'] . " " . $v['ssname_level'] ?>" data-toggle="tooltip" href="<?= BASE_URL . "knowledge/search?sid=" . $v['sid'] . "&ssid=" . $v['cid'] . "&cid=" . $v['ssid'] ?>" id="context_hover" target="_blank">
                                            <div class="button-gray mt-5"><?= $v['ssname'] ?>..</div>
                                        </a>
                                    <?php } ?>
                                    <?php if (count($menu_ssid) > 4) { ?>
                                        <div id="more_context" class="button-gray mt-5" style="border: 2px solid #000;cursor: pointer">ดูทั้งหมด</div>
                                    <?php } ?>
                                <?php } ?>
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
                    if ($info->content_type == 'doc') {
                        ?>
                        <div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
                            <div style="display: table;width:auto; border:1px solid #CCC; margin:20px auto; padding:10px">
                                <div>
                                    <div style="float:left"><a href="<?= $info->file ?>" target="_blank" title="โหลดเอกสารเรื่อง <?= $info->content_subject ?>" ><img src="<?= base_url() ?>new/assets/images/icon/icon_download.png" width="48" height="48" border="" alt=""  /></a></div>
                                    <div style="float:left; margin-left:10px">
                                        <div style="padding-left:10px"><a href="<?= $info->file ?>" target="_blank" title="โหลดเอกสารเรื่อง <?= $info->content_subject ?>" >เอกสารแนบ</a></div>
                                        <div><div style="float:left ; margin:5px 5px 0px 0px; "><img src="<?= base_url() ?>new/assets/images/icon/<?= $info->doc_type ?>.gif" border="0" alt="" height="30"  /></div><div style="float:left; margin-top:5px">ขนาด : <?= $info->filesize ?> MB</div></div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else if ($info->content_type == 'audio') { ?>
                        <style type="text/css" title="audiojs">.audiojs audio { position: absolute; left: -1px; }         .audiojs { width: 460px; height: 36px; background: #404040; overflow: hidden; font-family: monospace; font-size: 12px;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #444), color-stop(0.5, #555), color-stop(0.51, #444), color-stop(1, #444));           background-image: -moz-linear-gradient(center top, #444 0%, #555 50%, #444 51%, #444 100%);           -webkit-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); -moz-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3);           -o-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3); }         .audiojs .play-pause { width: 25px; height: 40px; padding: 4px 6px; margin: 0px; float: left; overflow: hidden; border-right: 1px solid #000; }         .audiojs p { display: none; width: 25px; height: 40px; margin: 0px; cursor: pointer; }         .audiojs .play { display: block; }         .audiojs .scrubber { position: relative; float: left; width: 280px; background: #5a5a5a; height: 14px; margin: 10px; border-top: 1px solid #3f3f3f; border-left: 0px; border-bottom: 0px; overflow: hidden; }         .audiojs .progress { position: absolute; top: 0px; left: 0px; height: 14px; width: 0px; background: #ccc; z-index: 1;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ccc), color-stop(0.5, #ddd), color-stop(0.51, #ccc), color-stop(1, #ccc));           background-image: -moz-linear-gradient(center top, #ccc 0%, #ddd 50%, #ccc 51%, #ccc 100%); }         .audiojs .loaded { position: absolute; top: 0px; left: 0px; height: 14px; width: 0px; background: #000;           background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #222), color-stop(0.5, #333), color-stop(0.51, #222), color-stop(1, #222));           background-image: -moz-linear-gradient(center top, #222 0%, #333 50%, #222 51%, #222 100%); }         .audiojs .time { float: left; height: 36px; line-height: 36px; margin: 0px 0px 0px 6px; padding: 0px 6px 0px 12px; border-left: 1px solid #000; color: #ddd; text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.5); }         .audiojs .time em { padding: 0px 2px 0px 0px; color: #f9f9f9; font-style: normal; }         .audiojs .time strong { padding: 0px 0px 0px 2px; font-weight: normal; }         .audiojs .error-message { float: left; display: none; margin: 0px 10px; height: 36px; width: 400px; overflow: hidden; line-height: 36px; white-space: nowrap; color: #fff;           text-overflow: ellipsis; -o-text-overflow: ellipsis; -icab-text-overflow: ellipsis; -khtml-text-overflow: ellipsis; -moz-text-overflow: ellipsis; -webkit-text-overflow: ellipsis; }         .audiojs .error-message a { color: #eee; text-decoration: none; padding-bottom: 1px; border-bottom: 1px solid #999; white-space: wrap; }                 .audiojs .play { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -1px no-repeat; }         .audiojs .loading { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -31px no-repeat; }         .audiojs .error { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -61px no-repeat; }         .audiojs .pause { background: url("http://www.trueplookpanya.com/new/assets/javascript/audiojs/player-graphics.gif") -2px -91px no-repeat; }                 .playing .play, .playing .loading, .playing .error { display: none; }         .playing .pause { display: block; }                 .loading .play, .loading .pause, .loading .error { display: none; }         .loading .loading { display: block; }                 .error .time, .error .play, .error .pause, .error .scrubber, .error .loading { display: none; }         .error .error { display: block; }         .error .play-pause p { cursor: auto; }         .error .error-message { display: block; }</style>
                        <script src="<?= base_url() ?>new/assets/javascript/audiojs/audio.min.js"></script>   
                        <script>
                            audiojs.events.ready(function () {
                                var as = audiojs.createAll();
                            });
                        </script>
                        <div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
                            <div style="width:auto; margin:30px auto" id="audio_area"><audio src="<?= $info->file ?>" preload="auto" /></div>
                        </div>
                    <?php } ?>
                    <div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
                        <?= $info->content_text ?>
                    </div>
                    <div class="button-nav-right col-lg-3 col-sm-4 pl-0 pr-0 mobile-show">
                        <div id="demo1" data-url="<?= current_url() ?>" data-text="แชร์" data-title="แชร์"></div>
                        <div>    
                            <?= social_media_share_hover() ?>
                        </div>
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
                    </div>
                    <div class="col-lg-12 col-xs-12 pl-0 pr-0 mt-10">
                        <div class="fb-comments" data-href="<?= current_url() ?>" data-numposts="5"></div>

                        <div id="fb-root"></div>
                        <script>(function (d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (d.getElementById(id))
                                    return;
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.5&appId=704838176241336";
                                fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                    </div>
                    <?php if ($isDetect == "mobile") { ?>
                        <div class="col-lg-12 col-xs-12 pl-0 pr-0" id="vdoload">
                            <div style="text-align: center;">  
                                <img src="assets/knowledgeV2/images/ajax_loader_red_32.gif">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($isDetect == "tablet") { ?>
                        <div class="col-lg-12 col-xs-12 pl-0 pr-0" id="vdoload">
                            <div style="text-align: center;">  
                                <img src="assets/knowledgeV2/images/ajax_loader_red_32.gif">
                            </div>
                        </div>
                    <?php } ?>
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
                    <?php if ($isDetect != "mobile" && $isDetect != "tablet") { ?>
                        <div class="col-lg-12 col-xs-12 pl-0 pr-0" id="vdoload">
                            <div style="text-align: center;">  
                                <img src="assets/knowledgeV2/images/ajax_loader_red_32.gif">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-lg-12 col-xs-12 social-all-right mt-10">
                        <nav class="social-nav col-lg-12 col-xs-12 pl-0 pr-0">
                            <a ><span>Follow Trueplookpanya </span></a>
                            <a  target="_blank" href="https://www.facebook.com/534110370093207" title="Facebook"><i style="font-size: 32px" class="fa fa-facebook-square"></i></a>
                            <a  target="_blank" href="https://twitter.com/trueplookpanya" title="Twitter"><i style="font-size: 32px" class="fa fa-twitter-square"></i></a>
                            <a target="_blank" href="http://www.youtube.com/user/trueplookpanyadotcom" title="Youtube"><i style="font-size: 32px" class="fa fa-youtube-square"></i></a>
                        </nav>
                    </div>
                    <div class="col-lg-12 col-xs-12 mt-10 pl-0 pr-0">
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
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pt-10 tablet-show" id="right-position">
                <div class="col-lg-12 col-xs-12 pl-0 pr-0">
                    <?php
                    if (!empty($top_banner)) {
                        foreach ($top_banner as $key => $value) {
                            ?>
                            <div class="col-md-6">
                                <a href="<?= $value->link ?>" title="<?= $value->title ?>" class="round" target="_blank">
                                    <img src="<?= $value->thumbnail ?>">
                                </a>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <?php if ($isDetect == "tablet") { ?>
                        <div class="col-lg-12 col-xs-12 pl-0 pr-0" id="vdoload">
                            <div style="text-align: center;">  
                                <img src="assets/knowledgeV2/images/ajax_loader_red_32.gif">
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 pt-10 tablet-show" id="right-position">
                <div class="row col-lg-12 col-xs-12 col-md-6 mt-10">

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
                <div class="col-lg-12 col-xs-12 col-md-6 social-all-right mt-10">
                    <nav class="social-nav col-lg-12 col-xs-12 pl-0 pr-0">
                        <a ><span>Follow Trueplookpanya </span></a>
                        <a  target="_blank" href="https://www.facebook.com/534110370093207" title="Facebook"><i style="font-size: 32px" class="fa fa-facebook-square"></i></a>
                        <a  target="_blank" href="https://twitter.com/trueplookpanya" title="Twitter"><i style="font-size: 32px" class="fa fa-twitter-square"></i></a>
                        <a target="_blank" href="http://www.youtube.com/user/trueplookpanyadotcom" title="Youtube"><i style="font-size: 32px" class="fa fa-youtube-square"></i></a>
                    </nav>
                </div>
            </div>
        </div>
</section>
<script>
    $(document).ready(function (event) {
        
         $('#demo1').sharrre({
            share: {
                googlePlus: true,
                facebook: true,
                twitter: true
            }
        });
        
        $('.box_context').hide();
        $("#more_context").click(function () {
            $('.box_context').toggle();
            if ($(".box_context").is(":hidden")) {
                $('#more_context').html('ดูทั้งหมด');
            } else {
                $('#more_context').html('ซ่อน');
            }
        });

        $('[data-toggle="tooltip"]').tooltip(
                {
                    'selector': '',
                    'placement': 'top',
                    'container': 'body'
                }
        );

        //$("#examload").click(function () {
        
        
        $("#examload").load('<?= site_url('knowledge/ajax-exam/' . $info->content_id . "?sid=" . $menu_sid[0]['sid'] . "&lid=" . $menu_lid[0]['lid'] . "&cid=" . $menu_cid[0]['cid']) ?>');
        // });
        // $("#sildeload").click(function () {
        $("#sildeload").load('<?= site_url('knowledge/ajax-upsale-crosssale/' . $info->content_id) ?>');
        //  });
        //$("#vdoload").click(function () {
        //alert();

        $("#vdoload").load('<?= site_url('knowledge/ajax-vdo/' . $info->content_id . '/' . $info->content_id_child) . '/?sid=' . $menu_sid[0]['sid'] . '&lid=' . $menu_lid[0]['lid'] . '&cid=' . $menu_ssid[0]['ssid'] ?>');
        //});

   
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











