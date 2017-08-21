<div class="content-cate-block">
    <div class="content row">
        <div class="col-md-6 text-center box-not-over">
            <?php if (!$disabled_cat) {
                ?>
                <a href="/cmsblog/index/#/#" class="btn-plookpanya main-text-color kl-btn-border btn-lg btn-on-img">
                    <?= $data['category_name_th'] ?>
                </a>
                <?php }
            ?>            
            <a href="/cmsblog/view/<?= $data['idx'] ?>/<?= $data['category_name_code'] ?>-<?= $data['title'] ?>" target="_blank">
                <img src="<?= $data['thumb_path'] ? $this->config->item('root_base_url') . $data['thumb_path'] : '/assets/images/no-image.png' ?>" class="img-responsive full-width" style="border-radius: 5px 0px 0px 5px; "/>
            </a>
        </div>
        <div class="col-md-6 content_text">
            <a href="/cmsblog/view/<?= $data['idx'] ?>/<?= $data['category_name_code'] ?>-<?= $data['title'] ?>" target="_blank">
                <div class="title" style="height:1.5em"><?= $data['title'] ?></div>
            </a>
            <div class="short_title" style="height:3em"><?= $data['description_short'] ?></div>
            <div><span class="main-text-color"><?= $data['category_name_th'] ?></span> | <?= ConvertToK($data['view_count']) ?> Views</div>
        </div>
    </div>
</div>