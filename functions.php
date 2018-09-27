<?php
function count_tasks($list, $project) {
    $categories = array_column($list, 'category');
    $count = array_count_values($categories);
    if (array_key_exists($project, $count)) {
        return $count[$project];
    } else {return '0';}
}
function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
}
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require($name);

    $result = ob_get_clean();

    return $result;
}