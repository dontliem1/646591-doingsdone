<?php
$link = mysqli_connect('localhost', 'root', '', 'doingsdone');
mysqli_set_charset($link, "utf8");

require_once('functions.php');
require_once('data.php');

$page_content = include_template('index.php',[
    'tasks_list' => $tasks_list,
    'show_complete_tasks' => $show_complete_tasks
    ]);
$layout_content = include_template('layout.php',[
    'content' => $page_content,
    'authorized' => $authorized,
    'projects_list' => $projects_list,
    'tasks_list_all' => $tasks_list_all,
    'tasks_list' => $tasks_list,
    'user' => esc($user_info['name']),
    'title' => 'Дела в порядке'
    ]);

print($layout_content);