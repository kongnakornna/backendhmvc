<?php

$html = "";
foreach ($list as $key => $value) {
    $html .= '<a target="_blank" data-toggle="tooltip" onclick="return upcount(' . $value['id'] . ')" href="' . $value['url'] . '" title="' . $value['title'] . '<br>จำนวนการคลิก: '.$value['count'].'" alt="' . $value['title'] . '" data-count="' . $value['count'] . '" data-weight="' . $value['weight'] . '" class="green-btn hvr-grow-shadow">' . $value['title'] . '</a>';
}
echo $html;
