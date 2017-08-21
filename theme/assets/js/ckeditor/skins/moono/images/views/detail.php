<?php
$CI = &get_instance();

// $this->template->add_stylesheet('assets/css/admissions-index.css');
// $this->template->add_stylesheet('assets/admissions-index-media-640.css');
// $this->template->add_stylesheet('assets/css/owl.carousel.css');
// $this->template->add_stylesheet('assets/css/owl.theme.css');
// $this->template->add_javascript('assets/js/admission-timeline.js');
// $this->template->add_javascript('assets/js/js-owl/owl.carousel.js');
// $this->template->add_javascript('assets/js/js-owl/bootstrap-collapse.js');
// $this->template->add_javascript('assets/js/js-owl/bootstrap-transition.js');
// $this->template->add_javascript('assets/js/js-owl/bootstrap-tab.js');
// $this->template->add_javascript('assets/js/js-owl/application.js');

$this->template->add_stylesheet('assets/css/knowledge/c_news/knowledge_preview.css'); //custom scss file path

$this->load->library('videojs');
$this->template->meta_title = 'ทรูปลูกปัญญา :: ' . $content['title'];
$this->template->meta_description = $content['description_short'];
$this->template->meta_keywords = $content['seo_description'];
$this->template->meta_og_title = 'ทรูปลูกปัญญา :: ' . $content['title'];
$this->template->meta_og_description = $content['description_short'];
$this->template->meta_og_image = $this->config->item('root_base_url') . $content['thumb_path']; //1200x630 pixels.
?>

<div class="content-detail" style="margin:10px auto;">
    <div class="container detail-container">
        <?php
        echo build_nav(array(
                'Home' => '/home',
                'Knowledge' => '/knowledge',
                $content['title'] => '#',
        ));
        ?>
    </div>
</div>
<!------ BANER ------!-->
<?php if ($content['banner_path']) { ?>
    <div style="position: relative; max-height:400px; overflow:hidden;">
        <img src="<?= $this->config->item('root_base_url') . $content['banner_path'] ?>" class="img-responsive blur-image"  style="position:absolute; width: 125%; max-width: none;margin-left: -50px; top: -25%; z-index:-100;"/>
        <div class="special-container">
            <img src="<?= $this->config->item('root_base_url') . $content['banner_path'] ?>" class="img-responsive"  style="width:100%; height:auto;"/>
        </div>
    </div>
<?php } ?>
<!------ CONTENT ------!-->
<div id="content-detail">
    <div class="container detail-container ttf-textB ttf-s-md">
        <h1 id="h1-knowledge"><?= $content['title'] ?></h1>
    </div>
    <div class="container detail-container ttf-textB ttf-s-sm" >
        <div class="row" >
            <div class="col-md-6 pull-left " style="font-size: 22px; ">Posted By <?= $content['psn_display_name'] ?> | <?= date_th('d M y', strtotime($content['cms_subject'])) ?></div>
            <div class="col-md-6 text-right" style="font-size: 22px; "><img src="/assets/images/img-education-admissions/v-mb.png" style="width:22px; height:22px;top: -3px; position: relative;"> <?= $content['view_count'] ?> Views</div>
        </div>
    </div>
    <div class="container detail-container img-shared  ttf-textB ttf-s-sm" >
        <div style="padding-top:10px; border-top: 1px solid #888;">
            <div class="col-md-6 pull-left img-shared " ><img src="/assets/images/img-education-admissions/icon-favorite2.png" style="">&nbsp; Favorite</div>
            <div class="col-md-6 text-right ">
                <?= $this->load->view("share-hover") ?></div>
        </div>
    </div>        

    <div class="container detail-container">

        <?php if ($content['file_lists']) { ?>
            <?php
            $isShowArchive = false;
            ?>
            <div class="row" id="file">
                <?php foreach ($content['file_lists'] as $key => $file) { ?>
                    <!--<div class="text-center">-->
                    <?php if ($file['file_type'] == 'Video') { ?>
                        <?= $CI->videojs->video_player($CI->videojs->convert_json($file['file_detail_json'])); ?>
                    <?php } else if ($file['file_type'] == 'Flash') { ?>

                    <?php } elseif ($file['file_type'] == '0') { ?>
                        <a href="<?= $this->config->item('static_url') . $file['file_path'] . $file['file_name'] ?>" download="<?= $file['file_name'] ?>">
                            <div class="col-md-<?= end($content['file_lists']) == $file ? ($key % 2 == 0 ? "12" : "6") : "6" ?>">
                                <div class="box-download-file">
                                    <div class="box-download-cloud">
                                        <img class="img-responsive" src="assets/images/icon_download.png">
                                    </div>
                                    <div class="box-download-detail">
                                        <p>Download</p>
                                        <p><?= $file['file_title'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                    <!--</div>-->
                <?php } ?>
            </div>
        <?php } ?>


        <div class="row">
            <div class="col-md-12" >
                <?= $content['description_long'] ?>
            </div>
        </div>
        <div class="row" id="credit">
            <div class="col-md-6" style="color:#666" >
                <?php if ($credit_by = json_decode($content['credit_by'])) { ?>
                    <b>แหล่งข้อมูล</b><br/>
                    <?php foreach ($credit_by as $v) { ?>
                        <a href="<?= $v->link ?>" target="_blank"><?= $v->name ?></a><br/>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="col-md-6">
                <button class="btn btn-plookpanya pull-right btn-sm">REPORT</button>
            </div>
        </div>
        <div style="margin-top:25px;">
            เว็บไซต์ทรูปลูกปัญญาดอทคอมเป็นเพียงผู้ให้บริการพื้นที่เผยแพร่ความรู้เพื่อประโยชน์ของสังคม ข้อความและรูปภาพที่ปรากฏในบทความเป็นการเผยแพร่โดยผู้ใช้งาน หากพบเห็นข้อความและรูปภาพที่ไม่เหมาะสมหรือละเมิดลิขสิทธิ์ กรุณาแจ้งผู้ดูแลระบบเพื่อดำเนินการต่อไป
        </div>
        <hr style="margin:10px 0; border-top: 1px solid #D3D6DB;"/>
    </div>
    <div class="container detail-container">
        <div class="row">
            <div class="col-md-12">
                <div class="row postby-profile">
                    <div class="profile-picture">
                        <div class="display-image-circle">
                            <img src="<?= $content['psn_display_image'] ? $content['psn_display_image'] : '/assets/images/img-main-menu/icon-login.png' ?>" class="img-responsive" />
                        </div>
                    </div>
                    <div class="profile-name">
                        <p>Posted By</p>
                        <p><?= $content['psn_display_name'] == '' ? 'Plook Education' : $content['psn_display_name'] ?></p>
                        <p>1234 Followers</p>
                        <button class="btn btn-plookpanya btn-sm">Follower</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container detail-container"  style="margin-top:10px;">
        <div class="row">
            <div class="col-md-12">
                <div style="border:1px solid #D3D6DB; padding:10px; border-radius:10px;">
                    <span style="color:#e2231a">Tags </span><br/>
                    <?php if ($hashtag = $content['hashtag']) { ?>
                        <?php foreach (explode(',', $hashtag) as $v) { ?>
                            <a href="#" target="_blank"><?= $v ?></a>&nbsp;
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container detail-container img-shared ttf-textB ttf-s-sm">
        <hr style="margin:10px 0; border-top: 1px solid #D3D6DB;"/>
        <div class="row">
            <div class="col-md-6 pull-left img-shared "><img src="/assets/images/img-education-admissions/icon-favorite2.png" style="">&nbsp; Favorite</div>
            <div class="col-md-6 text-right "><?= $this->load->view("share-hover") ?></div>
        </div>
    </div>
    <div class="container detail-container">
        <div class="fb-comments" data-href="<?= current_url() ?>" data-numposts="5" data-width="100%"></div>
    </div>
</div>
<style>
    #content-detail .img-responsive{display:initial;}
</style>
