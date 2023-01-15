<style>
    .sharedBox{
        display: inline-block;
        margin-right: 5px;
        margin-top: 6px;
    }
    .sharedBox > span > p:first-child{
        line-height: 0;
    }
    .sharedBox > span > p:nth-child(2){
        line-height: 0.5;
    }
</style>
<div class="sharedBox">
    <span>
        <p>Shares</p>
        <p><?= number_format(1234) ?> K</p>
    </span>
</div>
<div style="display: inline-block;vertical-align: top">
    <span id="shared">
        <a class="hvr-buzz-out" onclick="fb('704799662982418')"><img src="/assets/img-mocup/facebook_32.png" width="32" height="32"></a>
        <a class="hvr-buzz-out" onclick="twShare('')"><img src="/assets/img-mocup/twitter_32.png" width="32" height="32"></a>
        <span id="line_ni">
            <script type="text/javascript" src="//media.line.me/js/line-button.js?v=20140411" >
            </script>                             
            <script type="text/javascript">
                new media_line_me.LineButton({"pc": false, "lang": "en", "type": "c"});
            </script>
        </span>
        <a class="hvr-buzz-out" onclick="gShare()"><img src="/assets/img-mocup/googleplus_32.png" width="32" height="32"></a>        
    </span>    
</div>
<style>
    .hvr-buzz-out{cursor:pointer;}  
</style>
<script type="text/javascript">
    //social zone >-------------------------------<
    //facebook zone
    function fb(app_id) {
        //704799662982418
        var link = 'https://www.facebook.com/sharer/sharer.php?app_id=' + app_id + '&sdk=joey&u=' + encodeURIComponent(window.location.href) + '&display=popup&ref=plugin&src=share_button';
        window.open(link, 'trueplookpanya', 'left=10,top=10,width=500,height=500,toolbar=1,resizable=0');
    }
    function fbShare(url, title, descr, image, winWidth, winHeight) {
        var winTop = (window.height / 2) - (winHeight / 2);
        var winLeft = (window.width / 2) - (winWidth / 2);
        window.open('http://www.facebook.com/sharer.php?s=100&p[title]=' + title + '&p[summary]=' + descr + '&p[url]=' + url + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
    }
    function fbShare_me() {
        window.open('http://www.facebook.com/sharer.php?s=100&u=' + encodeURIComponent(window.location.href), 'trueplookpanya', 'left=5,top=5,width=500,height=500,toolbar=1,resizable=0');
    }

    //google zone
    function gShare() {
        window.open('https://plus.google.com/share?url=' + encodeURIComponent(window.location.href) + '&hl=th', 'trueplookpanya', 'left=5,top=5,width=500,height=500,toolbar=1,resizable=0');
    }

    //twitter zone
    function twShare(desc) {
        window.open('https://twitter.com/intent/tweet?text=' + desc + '&source=trueplookpanya&related=trueplookpanya&via=trueplookpanya&url=' + (encodeURIComponent(window.location.href)), 'trueplookpanya', 'left=5,top=5,width=500,height=500,toolbar=1,resizable=0');
    }
    //----------------------------------------------
</script>