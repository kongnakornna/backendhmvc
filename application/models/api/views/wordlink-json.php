<?php

$html = "";
foreach ($list as $key => $value) {
    $html = '<a target="_blank" onclick="return upcount(' . $value['id'] . ')" href="' . $value['url'] . '" title="' . $value['title'] . '" alt="' . $value['title'] . '">' . $value['title'] . '</a>';
    $list[$key]['html'] = $html;
}
echo json_encode($list, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
