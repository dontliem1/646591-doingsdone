<?php
require_once('functions.php');
require_once('data.php');

$page_content = include_template('index.php',[
		'tasks_list' => $tasks_list,
		'show_complete_tasks' => $show_complete_tasks
		]);
$layout_content = include_template('layout.php',[
		'content' => $page_content,
		'projects_list' => $projects_list,
		'tasks_list' => $tasks_list,
		'username' => esc('Константин'),
		'title' => 'Дела в порядке'
		]);
print($layout_content);