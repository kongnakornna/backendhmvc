<?php if ($isDetect == 'tablet') { ?>
    <div class="col-lg-12 col-xs-12 pl-0 pr-0 mt-10">
        <h2 class="col-lg-12 col-xs-12 h2 pr-0 pl-0 mb-0">
            <span>
                <div class="relate-box-one-top-title">เนื้อหาที่เกี่ยวข้อง</div>
            </span>
        </h2>
        <div class="col-lg-12 col-xs-12 swiper-container relate-sile-tablet mt-10 pl-5 pr-5 pb-20">
            <div class="swiper-wrapper">
                <?php
                if (!empty($right_relate['vdo'])) {
                    foreach ($right_relate['vdo'] as $key => $value) {
                        ?>
                        <div class="swiper-slide col-lg-3 relate-box-one pr-10 pl-10">
                            <div class="col-xs-12 pl-0 pr-0">
                                <a onclick="window.open('<?= $value['content_url'] ?>', '_blank')" href="<?= $value['content_url'] ?>" title="<?= $value['content_subject'] ?>" target="_blank">
                                    <img class="css-crop-206" src="<?= get_img_url($value['thumbnail']) ?>" alt="<?= $value['content_subject'] ?>">
                                </a>
                            </div>
                            <div class="col-xs-12 pl-0 pr-0 mt-5" style="overflow: hidden; height: 38px">
                                <a onclick="window.open('<?= $value['content_url'] ?>', '_blank')"  href="<?= $value['content_url'] ?>" title="<?= $value['content_subject'] ?>" target="_blank"><b><?= $value['content_subject'] ?></b></a>
                            </div>
                        </div>
                        <?php
                    }
                }
                if (!empty($right_relate['content'])) {
                    foreach ($right_relate['content']['data'] as $key => $value) {
                        ?>
                        <div class="swiper-slide col-lg-3 relate-box-one pr-10 pl-10">
                            <div class="col-xs-12 pl-0 pr-0">
                                <a onclick="window.open('<?= $value['url'] ?>', '_blank')" href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>" target="_blank">
                                    <img class="css-crop-206" src="<?= get_img_url($value['thumbnail']) ?>" alt="<?= $value['topic'] ?>">
                                </a>
                            </div>
                            <div class="col-xs-12 pl-0 pr-0 mt-5" style="overflow: hidden; height: 38px">
                                <a onclick="window.open('<?= $value['url'] ?>', '_blank')"  href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>" target="_blank"><b><?= $value['topic'] ?></b></a>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="swiper-pagination pagei-tab swiper-pagination-black" style="bottom:0px;"></div>
        </div>
        <div class="swi-next-tab swiper-button-next swiper-button-gray" style="background-size: 32px 32px; margin-top: -5px; margin-right: -29px;"></div>
        <div class="swi-prev-tab swiper-button-prev swiper-button-gray" style="background-size: 32px 32px;margin-top: -5px; margin-left: -29px;"></div>
    </div>
<?php } else { ?>
    <div class="col-lg-12 col-xs-12 pl-0 pr-0">
        <h2 class="h2 mb-0" style="font-size: 38px">เนื้อหาที่เกี่ยวข้อง</h2>
        <div class="mt-5" id="relate_content" style="height:385px;overflow: hidden">
            <?php
            if (!empty($right_relate['vdo'])) {
                foreach ($right_relate['vdo'] as $key => $value) {
                    ?>
                    <div class="col-lg-12 col-xs-12 border-higlight">
                        <a href="<?= $value['content_url'] ?>" title="<?= $value['content_subject'] ?>" target="_blank">
                            <div class="row col-lg-5 col-xs-5"> 
                                <img class="css-crop-110" src="<?= $value['thumbnail'] ?>" alt="">
                            </div>
                            <div class="col-lg-7 col-xs-7" style="height: auto;overflow: hidden;font-size: 13px"> 
                                <b><?= $value['content_subject'] ?></b>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            }
            if (!empty($right_relate['content'])) {
                foreach ($right_relate['content']['data'] as $key => $value) {
                    ?>
                    <div class="col-lg-12 col-xs-12 border-higlight">
                        <div class="row col-lg-5"> 
                            <a href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>" class="" target="_blank">
                                <img class="css-crop-110" src="<?= $value['thumbnail'] ?>" alt="">
                            </a>
                        </div>
                        <div class="col-lg-7"> 
                            <figcaption style="height: auto;overflow: hidden;font-size: 13px">
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
<?php } ?>


<script>
<?php if ($isDetect == 'tablet'){ ?>
        //$("#relate_content").mCustomScrollbar({theme: "dark-3",axis:"x"});
        var swiper_tablet = new Swiper('.relate-sile-tablet', {
            slidesPerView: 4,
            paginationClickable: true,
            spaceBetween: 10,
            nextButton: '.swi-next-tab',
            prevButton: '.swi-prev-tab',
            pagination: '.pagei-tab'
        });
<?php } else { ?>
        $(document).ready(function (event) {
            $("#relate_content").mCustomScrollbar({theme: "dark-3"});
            $("#mCSB_1_container").css({marginRight: "14px"});
        });
<?php } ?>
</script>