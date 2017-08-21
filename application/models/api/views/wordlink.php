<?php

$html = "";
foreach ($list as $key => $value) {
    if ($key > 0) {
        $html .= ',&nbsp;<a target="_blank" onclick="return upcount(' . $value['id'] . ')" href="' . $value['url'] . '" title="' . $value['title'] . '" alt="' . $value['title'] . '">' . $value['title'] . '</a>';
    } else {
        $html .= '<a target="_blank" onclick="return upcount(' . $value['id'] . ')" href="' . $value['url'] . '" title="' . $value['title'] . '" alt="' . $value['title'] . '">' . $value['title'] . '</a>';
    }
}
echo $html;
