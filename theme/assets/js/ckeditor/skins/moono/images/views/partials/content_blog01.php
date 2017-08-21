<div class="content-cate-block">
  <div class="content">
      <a href="cmsblog/view/<?=$data['idx']?>/<?=$data['category_name_code']?>-<?=$data['title']?>" target="_blank">
        <img src="<?=$data['thumb_path'] ? $this->config->item('root_base_url').$data['thumb_path'] : '/assets/images/no-image.png' ?>" class="img-responsive thumb"/>
      </a>
      <div class="content_text">
          <a href="cmsblog/view/<?=$data['idx']?>/<?=$data['category_name_code']?>-<?=$data['title']?>" target="_blank">
        <div class="title"><?=$data['title']?></div>
        </a>
        <div class="short_title"><?=$data['description_short']?></div>
        <div><span class="main-text-color"><?=$data['category_name_th']?></span> | <?=ConvertToK($data['view_count'])?> Views</div>
      </div>
    </div>
</div>