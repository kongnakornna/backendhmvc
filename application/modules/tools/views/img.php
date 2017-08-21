<?php
$title = "ทดสอบภาพ ".$w."x".$h;
$description = "อธิบายการทดสอบภาพ ขนาด ".$w."x".$h;
$keywords = "คำค้น การทดสอบภาพ ขนาด ".$w."x".$h;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta id="vp" name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
    <base href="http://www.trueplookpanya.com/">
    <!--/////////////////////////-->
    <title><?=$title?></title>
<meta property="og:title" content="<?=$title?>" />
<meta property="og:url" content="<?=$url?>" />
<meta property="og:image" content="<?=$img?>" />
<meta property="fb:app_id" content="704799662982418" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="th_TH" />

</head>
<h1><?="การทดสอบภาพ ขนาด ".$w."x".$h;?></h1>
<p>
<?="อธิบายการทดสอบภาพ ขนาด ".$w."x".$h;?>
<a href="#" onclick="fb('704799662982418'); return false;"><img src="/assets/images/knowledge/icon_detail_page/icon_facebook.png" width="32" height="32"></a>
</p>
<img src="<?=$img?>" alt="<?="image : ".$w."x".$h?>" title="<?="image : ".$w."x".$h?>">
</html>
<script type="text/javascript">
//facebook zone
function fb(app_id) {
   //704799662982418
   var link = 'https://www.facebook.com/sharer/sharer.php?app_id=' + app_id + '&sdk=joey&u=' + encodeURIComponent(window.location.href) + '&display=popup&ref=plugin&src=share_button';
   //var link = 'https://www.facebook.com/sharer/sharer.php?app_id=' + app_id + '&sdk=joey&u=' + <?=$url?> + '&display=popup&ref=plugin&src=share_button';
   window.open(link, 'trueplookpanya', 'left=10,top=10,width=500,height=500,toolbar=1,resizable=0');
}
</script>
