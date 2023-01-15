<?php
$db_hostname = 'TPPYDB.ppm.in.th';
$db_username = "trueplookpanya";
$db_password = "Gc1D6bD5A231LDM";
$db_database = "trueplookpanya";
$db_port = 3306;

$DB = new mysqli($db_hostname, $db_username, $db_password, $db_database, $db_port) OR die("Connection failed: " . mysqli_connect_error());
$DB->set_charset("utf8");


$cat_id = $_POST['cat_id'] ? mysqli_real_escape_string($DB, $_POST['cat_id']) : '0';
$cat_parent_id = !empty($_POST['cat_parent_id']) ? mysqli_real_escape_string($DB, $_POST['cat_parent_id']) : '0';
$q = $_POST['q'] ? mysqli_real_escape_string($DB, $_POST['q']) : '';

//var_dump($_POST,$cat_parent_id);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8">

            <title>KCFinder: /images</title>
            <link href="./css.php?type=images" rel="stylesheet" type="text/css">
                <link href="./themes/oxygen/style.css" rel="stylesheet" type="text/css">
                    <script src="./js/jquery.js" type="text/javascript"></script>
                    <script src="./js/jquery.rightClick.js" type="text/javascript"></script>
                    <script src="./js/jquery.drag.js" type="text/javascript"></script>
                    <script src="./js/helper.js" type="text/javascript"></script>
                    <script src="./js/browser/joiner.php" type="text/javascript"></script>
                    <script src="./js_localize.php?lng=en" type="text/javascript"></script>
                    <script src="./themes/oxygen/init.js" type="text/javascript"></script>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            browser.resize2();
                            $('.file').click(function () {
                                $('.selected').removeClass('selected');
                                $(this).addClass('selected');
                            });
                        });
                        $(window).resize(function () {
                            browser.resize2();
                        });



                        browser.resize2 = function () {
                            _('left').style.width = '25%';
                            _('right').style.width = '75%';
                            _('toolbar').style.height = $('#toolbar a').outerHeight() + "px";
                            _('shadow').style.width = $(window).width() + 'px';
                            _('shadow').style.height = _('resizer').style.height = $(window).height() + 'px';
                            _('left').style.height = _('right').style.height =
                                    $(window).height() - $('#status').outerHeight() + 'px';
                            _('folders').style.height =
                                    $('#left').outerHeight() - _.outerVSpace('#folders') + 'px';
                            browser.fixFilesHeight2();
                            var width = $('#left').outerWidth() + $('#right').outerWidth();
                            _('status').style.width = width + 'px';
                            while ($('#status').outerWidth() > width)
                                _('status').style.width = _.nopx(_('status').style.width) - 1 + 'px';
                            while ($('#status').outerWidth() < width)
                                _('status').style.width = _.nopx(_('status').style.width) + 1 + 'px';
                            if ($.browser.msie && ($.browser.version.substr(0, 1) < 8))
                                _('right').style.width = $(window).width() - $('#left').outerWidth() + 'px';
                            _('files').style.width = $('#right').innerWidth() - _.outerHSpace('#files') + 'px';
                            _('resizer').style.left = $('#left').outerWidth() - _.outerRightSpace('#folders', 'm') + 'px';
                            _('resizer').style.width = _.outerRightSpace('#folders', 'm') + _.outerLeftSpace('#files', 'm') + 'px';
                        };

                        browser.fixFilesHeight2 = function () {
                            _('files').style.height =
                                    $('#left').outerHeight() -
                                    $('#toolbar').outerHeight() -
                                    _.outerVSpace('#files') - 20 + 'px';
                        };

                    </script>


                    <script type="text/javascript">
                        $('body').noContext();
                    </script>
                    </head>
                    <body>


                        <div id="resizer" style="height: 643px; left: 338px; width: 4px; cursor: col-resize;"></div>
                        <div id="shadow" style="width: 1366px; height: 643px; display: none;"></div>
                        <div id="dialog" style="display: none;"></div>
                        <div id="alert" style="display: none;"></div>
                        <div id="clipboard"></div>
                        <div id="all" style="visibility: visible;">
                            <div id="left" style="width: 25%; height: 620px;">
                                <div id="folders" style="height: 604px;">
                                    <div class="folder">
                                        <a href="browse.php?type=images&mediastock=true&CKEditor=description_long&langCode=th&enableStatus=1" >
                                            <span class="brace">&nbsp;</span>
                                            <span class="folder regular">รูปของฉัน</span>
                                        </a>
                                    </div>
                                    <form method="post">
                                        <?php

                                        function generateTreeMenu($cat_current_id, $datas, $parent = 0, $limit = 0) {
                                            if ($limit > 1000)
                                                return '';
                                            $tree = '';
                                            $tree .= '<div class="folders">';

                                            // $current=$cat_id ? intval($cat_id + 0 ,10) : '0';
                                            // var_dump($cat_id);
                                            for ($i = 0, $ni = count($datas); $i < $ni; $i++) {
                                                if ($datas[$i]['md_parent_id'] == $parent) {
                                                    $tree .= '<div class="folder">';
                                                    $tree .= '<input value="' . $datas[$i]['md_parent_id'] . '" name="cat_parent_id" type="hidden" >';
                                                    $tree .= '<button value="' . $datas[$i]['md_cat_id'] . '" name="cat_id" type="submit" href="#">';
                                                    // $tree.='<span class="brace">&nbsp;</span>';
                                                    $tree .= '<span class="folder ' . ($cat_current_id == $datas[$i]['md_cat_id'] ? 'current' : 'regular') . '">' . $datas[$i]['name'] . '</span>';
                                                    $tree .= '</button>';
                                                    $tree .= generateTreeMenu($cat_current_id, $datas, $datas[$i]['md_cat_id'], $limit++);
                                                    $tree .= "</div>";
                                                }
                                            }
                                            $tree .= "</div>";
                                            return $tree;
                                        }
                                        ?>
                                        <div class="folder">
                                            <a>
                                                <span class="brace opened">&nbsp;</span>
                                                <button value="0" name="cat_id" type="submit" href="#">
                                                    <span class="folder <?= ($cat_id == 0 ? 'current' : 'regular') ?>">Shutter Stock</span>
                                                </button>
                                                <div class="folders">
                                                    <?php
                                                    // echo $cat_id;
                                                    $sql = "SELECT * FROM mediastock_category WHERE enableStatus = 1 and md_cat_id !=27 and md_parent_id != 27 order by name asc";
                                                    $res = mysqli_query($DB, $sql);
                                                    $rows = array();
                                                    if ($res) {
                                                        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                                                            $rows[] = $row;
                                                        }
                                                    }
                                                    // var_dump($rows);
                                                    echo generateTreeMenu($cat_id, $rows);
                                                    ?>
                                                </div>
                                            </a>
                                        </div>
                                    </form>
                                <form method="post">
                                        <div class="folder">
                                            <a>
                                                <span class="brace opened">&nbsp;</span>
                                                <button value="27" name="cat_id" type="submit" href="#">
                                                    <span class="folder <?= ($cat_id == 27 ? 'current' : 'regular') ?>">ลิขสิทธิ์ภาพทรูปลูกปัญญา</span>
                                                </button>
                                                <div class="folders">
                                                    <?php
                                                    $sql = "SELECT * FROM mediastock_category WHERE 1=1 AND enableStatus = 1 and md_parent_id=27 order by name asc";
                                                    $res = mysqli_query($DB, $sql);
                                                    $rows = array();
                                                    if ($res) {
                                                        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                                                            $rows[] = $row;
                                                        }
                                                    }
                                                    // var_dump($rows);
                                                    echo generateTreeMenu($cat_id, $rows,27);
                                                    ?>
                                                </div>
                                            </a>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                            <div id="right" style="width: 75%; height: 620px;">
                                <div id="toolbar" style="height: 23px;">
                                    <div>
                                        <form method="post">
                                            <div id="settings" style="display: block;">
                                                <div>
                                                    <fieldset style="height:31px;">
                                                        <legend>Search:</legend>
                                                        <input type="text" name="q" value="<?= $q ?>"/>
                                                        <input type="hidden" name="cat_id" value="<?= $cat_id ?>"/>
                                                        <button>SUBMIT</button>
                                                        <button value="" name="q" type="submit" href="#">Clear</button>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div id="settings">
                                    <div>
                                        <fieldset>
                                            <legend>View:</legend>
                                            <table summary="view" id="view"><tbody><tr>
                                                        <th><input id="viewThumbs" type="radio" name="view" value="thumbs"></th>
                                                        <td><label for="viewThumbs">&nbsp;Thumbnails</label> &nbsp;</td>
                                                        <th><input id="viewList" type="radio" name="view" value="list"></th>
                                                        <td><label for="viewList">&nbsp;List</label></td>
                                                    </tr></tbody></table>
                                        </fieldset>
                                    </div>
                                    <div>
                                        <fieldset>
                                            <legend>Show:</legend>
                                            <table summary="show" id="show"><tbody><tr>
                                                        <th><input id="showName" type="checkbox" name="name"></th>
                                                        <td><label for="showName">&nbsp;Name</label> &nbsp;</td>
                                                        <th><input id="showSize" type="checkbox" name="size"></th>
                                                        <td><label for="showSize">&nbsp;Size</label> &nbsp;</td>
                                                        <th><input id="showTime" type="checkbox" name="time"></th>
                                                        <td><label for="showTime">&nbsp;Date</label></td>
                                                    </tr></tbody></table>
                                        </fieldset>
                                    </div>

                                    <div>
                                        <fieldset>
                                            <legend>Order by:</legend>
                                            <table summary="order" id="order"><tbody><tr>
                                                        <th><input id="sortName" type="radio" name="sort" value="name"></th>
                                                        <td><label for="sortName">&nbsp;Name</label> &nbsp;</td>
                                                        <th><input id="sortType" type="radio" name="sort" value="type"></th>
                                                        <td><label for="sortType">&nbsp;Type</label> &nbsp;</td>
                                                        <th><input id="sortSize" type="radio" name="sort" value="size"></th>
                                                        <td><label for="sortSize">&nbsp;Size</label> &nbsp;</td>
                                                        <th><input id="sortTime" type="radio" name="sort" value="date"></th>
                                                        <td><label for="sortTime">&nbsp;Date</label> &nbsp;</td>
                                                        <th><input id="sortOrder" type="checkbox" name="desc"></th>
                                                        <td><label for="sortOrder">&nbsp;Descending</label></td>
                                                    </tr></tbody></table>
                                        </fieldset>
                                    </div>

                                </div>
                                <div id="files" style="height: 575px; width: 1008px;">
                                    <?php
                                    //$sql = "SELECT * FROM mediastock WHERE 1=1 AND enableStatus = 1 AND md_cat_id=COALESCE(NULLIF(" . $cat_id . ",0), md_cat_id) AND CONCAT_WS(' ', display_name, filename, remark) LIKE '%" . $q . "%' ";
                                    //$cat_sql = ($cat_id == 0 ?'and md_cat_id !=27 ':'');
                                    //$sql = "SELECT * FROM mediastock WHERE 1=1 ".$cat_sql." AND enableStatus = 1 AND md_cat_id=COALESCE(NULLIF(" . $cat_id . ",0), md_cat_id)" . (!empty($type) ? ($type == 4 ? " AND type IS NULL " : "AND type In ('" . $type . "')") : "") . " AND CONCAT_WS(' ', display_name, filename, remark) LIKE '%" . $q . "%' ORDER BY addDateTime ";
                                    
                                    $sql_cat = $cat_parent_id == 0? (($cat_id!=0)?"0 and md_cat_id=".$cat_id:"0") :$cat_parent_id;
                                    $sql = "SELECT * FROM mediastock WHERE 1=1 and md_cat_id in (select md_cat_id from mediastock_category where md_parent_id=".$sql_cat." and enableStatus=1) AND enableStatus = 1 " . (!empty($type) ? ($type == 4 ? " AND type IS NULL " : "AND type In ('" . $type . "')") : "") . " AND CONCAT_WS(' ', display_name, filename, remark) LIKE '%" . $q . "%' ORDER BY addDateTime ";
                                    //echo '<!-- sql=' . $sql . '-->';
                                    echo '<br>';
                                    $res = mysqli_query($DB, $sql);

                                    if ($res) {
                                        while ($row = mysqli_fetch_array($res, MYSQLI_BOTH)) {
                                             echo '<div class="file" ondblclick="selectThisImage(\' ' . $row['file_fullURL'] . ' \', \' ' . $row['display_name'] . ' \', \' ' . ($row['credit_text'] != null ? $row['credit_text'] : "" ). ' \', \' ' . ($row['credit_link'] != null ? $row['credit_link'] : "" ) . ' \')">';
                                            echo '<div class="thumb" style="background-size: auto 100%;background-image:url(\'' . $row['file_fullURL'] . '\')"></div><div class="name" style="display: block;">' . $row['display_name'] . '</div>';
                                            echo '<div class="time" style="display: none;">' . date("Y-m-d H:i:s", strtotime($row['addDateTime'])) . '</div>';
                                            echo '<div class="size" style="display: none;">' . $row['filesize'] . '</div>';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div id="status" style="width: 1356px;"><span id="fileinfo">&nbsp;</span></div>
                        </div>
                        <script>
                            function selectThisImage($url, $text, $caption_text,caption_link) {

                                window.opener.CKEDITOR.tools.callFunction(1, $url);

                                // CAPTION
                                // window.opener.document.getElementById(window.opener.CKEDITOR.dialog.getCurrent().getContentElement('info', 'hasCaption').domId).childNodes.item("checkbox").checked=true;
                                //var namex =  window.opener.CKEDITOR.dialog.getCurrent().getContentElement('info', 'captionText').setValue($text);

                                //ที่มาของภาพ URL
                                window.opener.CKEDITOR.dialog.getCurrent().getContentElement('info', 'captionLink').setValue(caption_link);

                                //คำประกอบรูปภาพ
                                window.opener.CKEDITOR.dialog.getCurrent().getContentElement('info', 'alt').setValue($text);

                                //ที่มาของภาพ Text (Required)
                                window.opener.CKEDITOR.dialog.getCurrent().getContentElement('info', 'captionText').setValue($caption_text);

                                // console.log(window.opener.CKEDITOR.dialog.getCurrent());
                                //console.log(namex);
                                //console.log($url);

                                window.opener.CKEDITOR.dialog.getCurrent().getContentElement('info', 'width').setValue('');
                                window.opener.CKEDITOR.dialog.getCurrent().getContentElement('info', 'height').setValue('');
                                window.close();
                            }
                        </script>
                        <style>
                            .folder button   {
                                outline-width: 0;
                                background: transparent;
                                border: 0px solid #fff;
                                box-shadow: none !important;
                                -moz-box-shadow: none !important;
                                -webkit-box-shadow: none !important;
                                color: #000;

                            }
                        </style>
                    </body>
                    </html>