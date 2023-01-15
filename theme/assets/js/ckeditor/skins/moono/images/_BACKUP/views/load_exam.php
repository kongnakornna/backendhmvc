<?php if(!empty($exam_relate['data'])){?>
<div class="col-lg-12 col-xs-12 pl-0 pr-0 mt-10" id="exam-box">
    <h2 class="col-xs-12 h2 pr-0 pl-0 mb-0" style="border-bottom: 0px solid #e02125;font-size: 32px">
        ข้อสอบแนะนำ
    </h2>
    <?php
    $sum = 1;
    foreach ($exam_relate['data'] as $key => $value) {
        ?>
        <div class="col-lg-4 col-xs-12 col-sm-4 hvr-push mt-10">
            <div class="relate-box-zero">
                <a href="<?= $value['url'] ?>" title="<?= $value['topic'] ?>" target="_blank">
                    <div class="relate-box-zero-title-top">
                        <?= $value['topic'] ?>
                    </div>
                    <div>
                        <div class="relate-box-zero-icon"><img src="http://www.trueplookpanya.com/new/assets/images/icon/level/over/<?= $value['mul_level_id'] ?>.png" width="36" height="36" alt="" border="0"></div>
                        <div class="relate-box-zero-view">โพส : <?= DateToThai($value['addDateTime']) ?></div><br>
                        <div class="relate-box-zero-view"><?= $value['viewcount'] ?> views</div>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <?php
        if ($sum == 3 && $isDetect == '') {
            echo "<div class='col-lg-12 col-sm-12'></div>";
            $sum = 0;
        }
        $sum++;
    }
    ?>
</div>
<?php } ?>