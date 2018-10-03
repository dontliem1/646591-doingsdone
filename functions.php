<?php
//Функция для подсчёта задач в проекте
function count_tasks($list, $project) {
    $categories = array_column($list, 'project_id');
    $count = array_count_values($categories);
    if (array_key_exists($project, $count)) {
        return $count[$project];
    } else {return '0';}
}
//Функция для фильтрации данных
function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
}
//Функция для включения шаблона в вёрстку с передачей параметров
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
//Функция для проверки срока истечения задачи
function check_important($date) {
    $important = false;
    $difference = floor((strtotime($date) - time())/3600);
    if (!empty($date) && ($difference <= 24)) {$important = true;}

    return $important;
}