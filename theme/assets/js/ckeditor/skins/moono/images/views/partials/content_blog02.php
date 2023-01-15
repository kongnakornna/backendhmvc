<div class="content-cate-block">
  <div class="content">
    <div class="text-center">
        <a href="/cmsblog/view/<?=$data['idx']?>/<?=$data['category_name_code']?>-<?=$data['title']?>" target="_blank">
      <img src="<?=$data['thumb_path'] ? $this->config->item('root_base_url').$data['thumb_path'] : '/assets/images/no-image.png' ?>" class="img-responsive thumb"/>
      <span class="btn-plookpanya main-text-color kl-btn-border kl-btn-active" style="position:relative; top:-0.55em;"><?=$data['category_name_th']?></span>
      </a>
    </div>
    <div class="content_text">
        <a href="/cmsblog/view/<?=$data['idx']?>/<?=$data['category_name_code']?>-<?=$data['title']?>" target="_blank">
        <div class="title"><?=$data['title']?></div>
      </a>
      <div class="short_title"><?=$data['description_short']?></div>
      <div><?=ConvertToK($data['view_count'])?> Views</div>
    </div>
  </div>
</div>