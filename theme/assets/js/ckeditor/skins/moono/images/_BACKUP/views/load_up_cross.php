<?php if (!empty($upsale_relate['data'])) { ?>
    <div class="col-lg-12 col-xs-12 pl-0 pr-0 mt-10" id="relate-box">
        <h2 class="col-lg-12 col-xs-12 h2 pr-0 pl-0 mb-0">
            <span>
                <div class="relate-box-one-top-title">เนื้อหาแนะนำ</div>
            </span>
        </h2>
        <div class="col-lg-12 col-xs-12 swiper-container relate-sile-one mt-10 pl-5 pr-5 pb-20">
            <div class="swiper-wrapper">
                <?php
                foreach ($upsale_relate['data'] as $key => $value) {
                    ?>
                    <div class="swiper-slide col-lg-3 col-xs-6 relate-box-one pr-10 pl-10">
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
                ?>
            </div>
            <div class="swiper-pagination pagei-one swiper-pagination-black" style="bottom:0px;"></div>
        </div>
        <div class="swi-next1 swiper-button-next swiper-button-gray" style="background-size: 32px 32px; margin-top: -5px; margin-right: -29px;"></div>
        <div class="swi-prev1 swiper-button-prev swiper-button-gray" style="background-size: 32px 32px;margin-top: -5px; margin-left: -29px;"></div>
    </div>
<?php } ?>
<?php if (!empty($crosssale_relate['data'])) { ?>
    <div class="col-lg-12 col-xs-12 pl-0 pr-0 mt-10">
        <h2 class="col-lg-12 col-xs-12 h2 pr-0 pl-0 mb-0">
            <span>
                <div class="relate-box-one-top-title">เรื่องที่คุณอาจสนใจ</div>
            </span>
        </h2>
        <div class="col-lg-12 col-xs-12 swiper-container relate-sile-two mt-10 pl-5 pr-5 pb-20">
            <div class="swiper-wrapper">
                <?php
                foreach ($crosssale_relate['data'] as $key => $value) {
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
                ?>
            </div>
            <div class="swiper-pagination pagei-two swiper-pagination-black" style="bottom:0px;"></div>

        </div>
        <div class="swi-next2 swiper-button-next swiper-button-gray" style="background-size: 32px 32px; margin-top: -5px; margin-right: -29px;"></div>
        <div class="swi-prev2 swiper-button-prev swiper-button-gray" style="background-size: 32px 32px;margin-top: -5px; margin-left: -29px;"></div>
    </div>
<?php } ?>
<script>
<?php
if ($isDetect == '') {
    ?>
        var swiper = new Swiper('.relate-sile-one', {
        slidesPerView: 3,
                paginationClickable: true,
                spaceBetween: 12,
                nextButton: '.swi-next1',
                prevButton: '.swi-prev1',
                pagination: '.pagei-one'
        });
                var swiper2 = new Swiper('.relate-sile-two', {
                slidesPerView: 3,
                        paginationClickable: true,
                        spaceBetween: 12,
                        nextButton: '.swi-next2',
                        prevButton: '.swi-prev2',
                        pagination: '.pagei-two'
                });
    <?php
} else if ($isDetect == 'mobile') {
    ?>
        var swiper = new Swiper('.relate-sile-one', {
        slidesPerView: 2,
                paginationClickable: true,
                spaceBetween: 10,
                pagination: '.pagei-one'
        });
                var swiper2 = new Swiper('.relate-sile-two', {
                slidesPerView: 2,
                        paginationClickable: true,
                        spaceBetween: 10,
                        pagination: '.pagei-two'
                });
    <?php
} else if ($isDetect == 'tablet') {
    ?>
        var swiper = new Swiper('.relate-sile-one', {
        slidesPerView: 4,
                paginationClickable: true,
                spaceBetween: 10,
                nextButton: '.swi-next1',
                prevButton: '.swi-prev1',
                pagination: '.pagei-one'
                
        });
                var swiper2 = new Swiper('.relate-sile-two', {
                slidesPerView: 4,
                        paginationClickable: true,
                        spaceBetween: 10,
                        nextButton: '.swi-next2',
                        prevButton: '.swi-prev2',
                        pagination: '.pagei-two'
                });
    <?php
}
?>
</script>